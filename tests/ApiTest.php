<?php

namespace WebSystems\Toyota\Tests;


use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use WebSystems\Toyota\Api;

class ApiTest extends TestCase
{
    private $api;

    protected function setUp(): void
    {
        $login = 'test_login';
        $password = 'test_password';
        $baseApiUri = 'https://diapi.toyota.ua/api/';
        $waitResponse = 5;

        // Mocking the Guzzle client
        $mockClient = $this->createMock(Client::class);

        // Mocking the authenticate method
        $mockClient->method('post')->willReturn(new Response(200, [], json_encode(['token' => 'test_token'])));

        // Mocking the getRequestId method
        $mockClient->method('get')->willReturn(new Response(200, [], json_encode([
            'deeplink' => 'test_deeplink',
            'requestId' => 'test_request_id'
        ])));

        // Mocking the getData method
        $mockClient->method('get')->willReturn(new Response(200, [], json_encode([
            'metaData' => json_encode(['data' => 'test_data'])
        ])));

        $this->api = new Api($login, $password, $baseApiUri, $waitResponse);
        $this->api->setClient($mockClient);
    }

    public function testGetRequestId()
    {
        $result = $this->api->getRequestId();
        $this->assertIsArray($result);
        $this->assertArrayHasKey('deeplink', $result);
        $this->assertArrayHasKey('requestId', $result);
        $this->assertEquals('test_deeplink', $result['deeplink']);
        $this->assertEquals('test_request_id', $result['requestId']);
    }

    public function testGetData()
    {
        $result = $this->api->getData('test_request_id');
        $this->assertIsString($result);
        $this->assertEquals('test_data', $result);
    }
}
