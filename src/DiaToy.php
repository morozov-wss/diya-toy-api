<?php

namespace WebSystems\Toyota;

use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use GuzzleHttp\Exception\GuzzleException;

/**
 *
 */
class DiaToy
{
    /**
     * @var Api
     */
    private $apiService;

    /**
     * @var string
     */
    private $exceptionMessage = 'Providing a VIN number via Diya is currently not available';

    /**
     * DiaToy constructor.
     *
     * @param $login
     * @param $password
     * @param $baseApiUri
     * @param $waitResponse
     * @param $exceptionMessage
     * @throws GuzzleException
     */
    public function __construct($login, $password, $baseApiUri, $waitResponse, $exceptionMessage)
    {
        $this->apiService = new Api($login, $password, $baseApiUri, $waitResponse);
        $this->exceptionMessage = $exceptionMessage;
    }

    /**
     * @return array
     * @throws GuzzleException
     */
    public function getRequestId(): array
    {
        $response = $this->apiService->getRequestId();

        $qrSrc = $response['deeplink']
            ? (new QRCode(
                new QROptions([
                    'addQuietzone' => false,
                    'scale' => 10,
                ])
            ))->render($response['deeplink'])
            : null;

        return [
            'message' => !empty($response['requestId']) && !empty($qrSrc) ? 'success' : $this->exceptionMessage,
            'requestId' => $response['requestId'],
            'qrSrc' => $qrSrc,
        ];
    }

    /**
     * @param string $requestId
     * @return array
     * @throws GuzzleException
     */
    public function getDate(string $requestId): array
    {
        $response = $this->apiService->getData($requestId);
        return [
            'success' => !$response ? $this->exceptionMessage : 'success',
            'data' => $response,
        ];
    }

    /**
     * @param $apiService
     * @return void
     */
    public function setApiService($apiService)
    {
        $this->apiService = $apiService;
    }
}
