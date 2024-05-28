<?php

namespace WebSystems\Toyota\Tests;


use PHPUnit\Framework\TestCase;
use WebSystems\Toyota\DiaToy;
use WebSystems\Toyota\Api;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;

class DiaToyTest extends TestCase
{
    private $diaToy;

    protected function setUp(): void
    {
        $login = 'test_login';
        $password = 'test_password';
        $baseApiUri = 'https://api.toyota.com/';
        $waitResponse = 5;
        $exceptionMessage = 'Providing a VIN number via Diya is currently not available';

        // Mocking the Api class
        $mockApi = $this->createMock(Api::class);
        $mockApi->method('getRequestId')->willReturn([
            'deeplink' => 'test_deeplink',
            'requestId' => 'test_request_id'
        ]);
        $mockApi->method('getData')->willReturn('test_data');

        // Using dependency injection to pass the mock Api class
        $this->diaToy = new DiaToy($login, $password, $baseApiUri, $waitResponse, $exceptionMessage);
        $this->diaToy->setApiService($mockApi);
    }

    public function testGetRequestId()
    {
        $result = $this->diaToy->getRequestId();
        $this->assertArrayHasKey('message', $result);
        $this->assertArrayHasKey('requestId', $result);
        $this->assertArrayHasKey('qrSrc', $result);
        $this->assertEquals('success', $result['message']);
        $this->assertEquals('test_request_id', $result['requestId']);
    }

    public function testGetData()
    {
        $result = $this->diaToy->getDate('test_request_id');
        $this->assertArrayHasKey('success', $result);
        $this->assertArrayHasKey('data', $result);
        $this->assertEquals('success', $result['success']);
        $this->assertEquals('test_data', $result['data']);
    }
}