<?php

namespace Divido\MerchantSDKGuzzle6\Test;

use Divido\MerchantSDK\Client;
use Divido\MerchantSDK\Environment;
use Divido\MerchantSDK\Handlers\ApiRequestOptions;
use Divido\MerchantSDK\HttpClient\HttpClientWrapper;
use Divido\MerchantSDKGuzzle6\GuzzleAdapter;
use GuzzleHttp\Client as Guzzle;
use GuzzleHttp\Psr7\Response;

use function GuzzleHttp\Psr7\stream_for;
use PHPUnit\Framework\TestCase;

class HttpClientWrapperTest extends TestCase
{
    private $testDir;

    public function setUp()
    {
        $this->testDir = realpath(dirname(__FILE__) . '/');
    }

    public function test_GuzzleAdapter_GetRequest_ReturnsBody()
    {
        $response_get_all_applications = file_get_contents(($this->testDir . '/assets/responses/applications/get_all.json'));
        $payload = stream_for($response_get_all_applications);

        $mock_Guzzle = \Mockery::spy(Guzzle::class);
        $mock_Guzzle->shouldReceive('send')
            ->once()
            ->andReturn(new Response(200, [], $payload));

        $env = 'dev';

        $httpClientWrapper = new HttpClientWrapper(
            new GuzzleAdapter($mock_Guzzle),
            Environment::CONFIGURATION[$env]['base_uri'],
            'test_cfabc123.querty098765merchantsdk12345'
        );

        $requestOptions = (new ApiRequestOptions());

        $sdk = new Client($httpClientWrapper, $env);
        $result = $sdk->getAllApplications($requestOptions);

        $this->assertEquals(true, true);
    }
}
