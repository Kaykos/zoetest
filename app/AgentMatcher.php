<?php
/**
 * Created by PhpStorm.
 * User: sebas
 * Date: 24/03/19
 * Time: 08:32 PM
 */

namespace App;

class AgentMatcher
{
    const CONTACTS_FILENAME = 'contacts.csv';
    const EMPTY_COORDINATES_MESSAGE = 'Google Geocoding API couldn\'t find location information for ZIP code: ';
    const EMPTY_RESPONSE_MESSAGE = 'Error requesting Google Geocoding API';

    private $contacts;
    private $agent1;
    private $agent2;

    private $Geocoding;

    public function __construct()
    {
        $this->contacts = DataLoader::getAssociativeArrayFromCSV(self::CONTACTS_FILENAME);
        $this->Geocoding = new Geocoding();
        $this->agent1 = ['zipcode' => null];
        $this->agent2 = ['zipcode' => null];
    }

    /**
     * Set the data for the agents
     *
     * @param string $zipCode1 Agent1 zip code
     * @param string $zipCode2 Agent2 zip code
     */
    public function setAgentsData($zipCode1, $zipCode2)
    {
        $this->agent1['zipcode'] = $zipCode1;
        $this->agent2['zipcode'] = $zipCode2;

        $this->agent1 += $this->Geocoding->getCoordinatesFromZipCode($this->agent1['zipcode']);
        $this->agent2 += $this->Geocoding->getCoordinatesFromZipCode($this->agent2['zipcode']);
    }

    /**
     * Get the matching information for the contacts
     *
     * @return array Array containing contact information and designed agent
     */
    public function getMatches()
    {
        $this->setContactsCoordinates();

        $agent1Distances = DistancesManager::getDistancesToContacts($this->agent1, $this->contacts);
        $agent2Distances = DistancesManager::getDistancesToContacts($this->agent2, $this->contacts);

        $matches = [];
        foreach ($this->contacts as $contact) {
            if ($contact['lat'] != 0 and $contact['lng'] != 0) {
                $zipCode = $contact['zipcode'];
                $matches[] = [
                    'agentId' => DistancesManager::getClosestAgent($agent1Distances[$zipCode], $agent2Distances[$zipCode]),
                    'contactName' => $contact['name'],
                    'zipcode' => $zipCode,
                ];
            }
        }
        return $matches;
    }

    /**
     * Check if the obtained coordinates are valid for comparison
     *
     * @return array Validity of the agents coordinates and error message
     */
    public function checkForValidAgentsInformation()
    {
        $errorMessage = '';
        if (array_key_exists('lat', $this->agent1) and array_key_exists('lat', $this->agent2)) {
            $errorMessage .= $this->agent1['lat'] == 0 ? sprintf('%s%s', self::EMPTY_COORDINATES_MESSAGE, $this->agent1['zipcode']) : '';
            $errorMessage .= $this->agent2['lat'] == 0 ? sprintf('%s%s', self::EMPTY_COORDINATES_MESSAGE, $this->agent2['zipcode']) : '';
        } else {
            $errorMessage .= self::EMPTY_RESPONSE_MESSAGE;
        }
        return [
            'valid' => empty($errorMessage),
            'errors' => $errorMessage
        ];
    }


    /**
     * Update contacts list with each of its coordinates
     */
    private function setContactsCoordinates()
    {
        foreach ($this->contacts as &$contact) {
            $contact += $this->getContactCoordinates($contact);
        }
    }

    /**
     * Get the coordinates of a contact
     *
     * @param array $contact Representation of a contact
     * @return array Coordinates for the given contact zip code
     */
    private function getContactCoordinates($contact)
    {
        return $this->Geocoding->getCoordinatesFromZipCode($contact['zipcode']);
    }

    /**
     * Get contacts list
     *
     * @return array List of contacts
     */
    public function getContacts()
    {
        return $this->contacts;
    }
}