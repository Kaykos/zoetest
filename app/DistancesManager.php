<?php

namespace App;


class DistancesManager
{
    const EARTH_RADIUS = 6371; //km


    /**
     * Get the distance from an agent to each contact
     *
     * @param array $agent Agent information
     * @param array $contacts List of contacts
     * @return array Distances in km
     */
    public static function getDistancesToContacts($agent, $contacts)
    {
        $distances = [];
        foreach ($contacts as $contact) {
            if ($contact['lat'] != 0 and $contact['lng'] != 0) {
                $distances[$contact['zipcode']] = self::getDistanceBetweenCoordinates($agent['lat'], $agent['lng'],
                    $contact['lat'], $contact['lng']);
            }
        }
        return $distances;
    }

    /**
     * Get the closest agent depending on the distances
     *
     * @param float $distanceToAgent1 Distance to agent1
     * @param float $distanceToAgent2 Distance to agent2
     * @return int Id of the closest agent
     */
    public static function getClosestAgent($distanceToAgent1, $distanceToAgent2)
    {
        return $distanceToAgent1 <= $distanceToAgent2 ? 1 : 2;
    }

    /**
     * Get the distance in kilometers between two coordinates
     * Taken from https://stackoverflow.com/a/31367485
     *
     * @param float $lat1 First latitude
     * @param float $lng1 First longitude
     * @param float $lat2 Second latitude
     * @param float $lng2 Second longitude
     * @return float|int Distance in kilometers
     */
    private static function getDistanceBetweenCoordinates($lat1, $lng1, $lat2, $lng2)
    {
        $rad = M_PI / 180;
        return acos(sin($lat2 * $rad) * sin($lat1 * $rad) + cos($lat2 * $rad) * cos($lat1 * $rad)
                * cos($lng2 * $rad - $lng1 * $rad)) * self::EARTH_RADIUS;
    }
}