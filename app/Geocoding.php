<?php

namespace App;

use GuzzleHttp\Client;

/**
 * Integration with Google Geocoding API
 *
 * Class Geocoding
 * @package App
 */
class Geocoding
{
    private $client;
    private $apiUrl;
    private $apiKey;

    const COMPONENT_SEPARATOR = '|';
    const OUTPUT_FORMAT = 'json';
    const ADDRESS_PARAMETER = 'address';

    const COMPONENTS_PARAMETER = 'components';
    const KEY_PARAMETER = 'key';

    const COUNTRY_COMPONENT = 'country';
    const POSTAL_CODE_COMPONENT = 'postal_code';
    const COUNTRY_VALUE = 'US';

    const STATUS_OK = 'OK';
    const STATUS_NO_RESULTS = 'ZERO_RESULTS';

    public function __construct()
    {
        $this->client = new Client();
        $this->apiUrl = env('GOOGLE_GEOCODING_API_URL');
        $this->apiKey = env('GOOGLE_GEOCODING_API_KEY');
    }

    /**
     * Request Google Geocoding service to obtain location information for a given zip code
     * If there's an exception, an empty array is returned
     *
     * @param string $zipCode
     * @return array Array containing latitude and longitude
     */
    public function getCoordinatesFromZipCode($zipCode)
    {
        $coordinates = [];
        try {
            $componentsFilter = $this->buildComponentsString([
                self::COUNTRY_COMPONENT => self::COUNTRY_VALUE,
                self::POSTAL_CODE_COMPONENT => $zipCode
            ]);
            $parameters = [
                self::COMPONENTS_PARAMETER => $componentsFilter,
                self::KEY_PARAMETER => $this->apiKey
            ];
            $url = $this->buildUrl($this->apiUrl, self::OUTPUT_FORMAT, $parameters);
            $request = $this->client->get($url);
            if ($request->getStatusCode() == 200) {
                $response = json_decode($request->getBody(), true);
                $coordinates = $this->getCoordinatesFromAPIResponse($response);
            }
        } catch (\Exception $e) {

        } finally {
            return $coordinates;
        }
    }

    /**
     * Get the coordinates from the JSON returned by Google Geocoding API
     * If the response doesn't have any result the coordinates are 0, 0
     *
     * @param array $response
     * @return array Latitude and longitude
     */
    private function getCoordinatesFromAPIResponse($response)
    {
        $coordinates = [
            'lat' => 0,
            'lng' => 0
        ];
        if ($response['status'] == self::STATUS_OK) {
            $coordinates = $response['results'][0]['geometry']['location'];
        }
        return $coordinates;
    }

    /**
     * Get the components filter string following Google Geocoding API
     *
     * @param array $components Key => value array representing the components filters
     * @return string Components filter string
     */
    private function buildComponentsString($components)
    {
        $data = [];
        foreach ($components as $key => $value) {
            $data[] = sprintf('%s:%s', $key, $value);
        }
        return implode(self::COMPONENT_SEPARATOR, $data);
    }

    /**
     * Get the url to request Google API
     *
     * @param string $baseUrl Base API url
     * @param string $outputFormat Response format
     * @param array $queryParameters Query parameters for a request
     * @return string Final url
     */
    private function buildUrl($baseUrl, $outputFormat, $queryParameters)
    {
        return sprintf('%s/%s?%s', $baseUrl, $outputFormat, http_build_query($queryParameters));
    }
}