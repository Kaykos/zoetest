<?php

namespace Tests\Unit;

use App\Geocoding;
use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;
use Tests\TestCase;

class GeocodingTest extends TestCase
{

    private $geocoding;
    private $mockGuzzleClient;

    public function setUp(): void
    {
        $this->mockGuzzleClient = $this->getMockBuilder('GuzzleHttp\Client')->setMethods(['get'])->getMock();
    }

    public function testGetCoordinatesFromZipCode()
    {
        $mockJsonResponse = '{"results": [{"geometry": {"location" : {"lat": 30.595193, "lng": -81.843778}}}], "status": "OK"}';

        $mockHttpResponse = \Mockery::mock('Psr\Http\Message\ResponseInterface');
        $mockHttpResponse->shouldReceive('getStatusCode')->andReturn(200);
        $mockHttpResponse->shouldReceive('getBody')->andReturn($mockJsonResponse);
        $this->mockGuzzleClient->method('get')->will($this->returnValue($mockHttpResponse));
        $this->geocoding = new Geocoding($this->mockGuzzleClient);

        $expectedCoordinates = ['lat' => 30.595193, 'lng' => -81.843778];

        $coordinates = $this->geocoding->getCoordinatesFromZipCode('123');

        $this->assertEquals($expectedCoordinates, $coordinates);
    }

    public function testGetCoordinatesFromZipCodeZeroResults()
    {
        $mockJsonResponse = '{ "results" : [], "status" : "ZERO_RESULTS" }';

        $mockHttpResponse = \Mockery::mock('Psr\Http\Message\ResponseInterface');
        $mockHttpResponse->shouldReceive('getStatusCode')->andReturn(200);
        $mockHttpResponse->shouldReceive('getBody')->andReturn($mockJsonResponse);
        $this->mockGuzzleClient->method('get')->will($this->returnValue($mockHttpResponse));
        $this->geocoding = new Geocoding($this->mockGuzzleClient);

        $expectedCoordinates = ['lat' => 0, 'lng' => 0];

        $coordinates = $this->geocoding->getCoordinatesFromZipCode('123');

        $this->assertEquals($expectedCoordinates, $coordinates);
    }
}
