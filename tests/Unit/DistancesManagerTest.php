<?php

namespace Tests\Unit;

use App\DistancesManager;
use Tests\TestCase;

class DistancesManagerTest extends TestCase
{

    public function testGetClosestAgent()
    {
        $closestId = DistancesManager::getClosestAgent(5, 10);
        $this->assertEquals(1, $closestId);

        $closestId = DistancesManager::getClosestAgent(10, 5);
        $this->assertEquals(2, $closestId);
    }

    public function testGetDistancesToContacts()
    {
        $agent = ['lat' => 30.645269, 'lng' => -81.842351];
        $contacts = [
            ['zipcode' => '123', 'lat' => 30.595193, 'lng' => -81.843778],
            ['zipcode' => '234', 'lat' => 30.520382, 'lng' => -81.777837]
        ];

        $expectedDistances = [
            '123' => 5.56,
            '234' => 15.19,
        ];

        $distances = DistancesManager::getDistancesToContacts($agent, $contacts);
        foreach ($distances as $zip => $distance){
            $this->assertEqualsWithDelta($expectedDistances[$zip], $distance, 0.1);
        }
    }
}
