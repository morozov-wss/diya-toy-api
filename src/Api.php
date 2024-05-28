<?php

namespace WebSystems\Toyota;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class Api implements ApiInterface
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @var int
     */
    private $waitResponse;

    /**
     * @var string|null
     */
    private $token;

    /**
     * Api constructor.
     *
     * @param $login
     * @param $password
     * @param $baseApiUri
     * @param $waitResponse
     * @throws GuzzleException
     */
    public function __construct($login, $password, $baseApiUri, $waitResponse)
    {
        $this->waitResponse = $waitResponse;
        $this->client = new Client([
            'base_uri' => $baseApiUri,
            'timeout'  => ($this->waitResponse + 1) * 60,
            'verify' => false,
        ]);
        $this->token = $this->authenticate($login, $password);
    }

    /**
     * Return request id and deep link.
     *
     * @return array
     * @throws GuzzleException
     */
    public function getRequestId(): array
    {
        try {
            $response = $this->client->get('branch/sharing/online/var?DocumentType=vehicle-license', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->token,
                ],
            ]);

            $data = json_decode($response->getBody(), true);

            return [
                'deeplink' => $data['deeplink'] ?? null,
                'requestId' => $data['requestId'] ?? null,
            ];
        } catch (Exception $e) {
            return [
                'deeplink' => null,
                'requestId' => null,
            ];
        }
    }

    /**
     * Get data from dia.
     *
     * @param string $requestId
     * @return string|null
     * @throws GuzzleException
     */
    public function getData(string $requestId): ?string
    {
        try {
            $response = $this->client->get(
                "branch/sharing/upload/var?WaitResponse={$this->waitResponse}&RequestID={$requestId}", [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->token,
                ],
            ],
            );

            $data = json_decode($response->getBody(), true);

            if (!isset($data['metaData'])) {
                throw new Exception('empty data');
            }

            return json_decode($data['metaData'], true);
        } catch (Exception $e) {
            return null;
        }
    }

    /**
     * Get token.
     *
     * @param string $username
     * @param string $password
     * @return string|null
     * @throws GuzzleException
     */
    private function authenticate(string $username, string $password): ?string
    {
        $response = $this->client->post('users/authenticate', [
            'json' => [
                'UserName' => $username,
                'Password' => $password,
            ],
        ]);

        $data = json_decode($response->getBody(), true);

        return $data['token'] ?? null;
    }

    /**
     * @param $client
     * @return void
     */
    public function setClient($client)
    {
        $this->client = $client;
    }
}