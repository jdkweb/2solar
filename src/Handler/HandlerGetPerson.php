<?php

namespace TwoSolar\Handler;

use TwoSolar\Handler\Handler;
use TwoSolar\Handler\HandlerFactory;
use TwoSolar\Handler\SolarRestApi;

class HandlerGetPerson extends Handler implements HandlerFactory
{
    public int $status_id;

    public array $status_data = [];

    private SolarRestApi $api;

    //----------------------------------------------------------------------------------------

    /**
     * REST API object
     * @var SolarRestApi
     */
    public function __construct(private \TwoSolar\Log\Logger $logger)
    {
        $this->api = new SolarRestApi();
    }

    //----------------------------------------------------------------------------------------

    /**
     * Proces voor verzamelen van person data
     * @return void
     */
    public function run():void
    {
        $items = $this->getPersons();

        // error is > 0
        if (count($items) >= 1) {
            foreach ($items as $item) {
                if (!empty($item['request_id']) && is_numeric($item['request_id'])) {
                    // Verzamel de aanwezige persons (zonder quote)
                    $this->setData($item);
                }
            }
        }
    }

    //----------------------------------------------------------------------------------------

    /**
     * Status_id always 6 long extra number is for special handeling
     * @param int $id
     * @return void
     */
    public function setStatus(array $status_data)
    {
        $this->status_id = $status_data['id'];
        $this->status_data = $status_data;
    }

    //----------------------------------------------------------------------------------------

    /**
     * Persons selecteren op last edit (actuele rows)
     * @return array
     */
    public function getPersons():array
    {
        $arg_str = "?".http_build_query([
            'request_client_status_id' => $this->status_data['status'],
            'date_edited_from' => date("Y-m-d", strtotime(date('Y-m-d') . ' -'. ($this->status_data['delay']) . ' day'))
        ]);

        return $this->api->get('person/search/', [$arg_str], false);
    }

    //----------------------------------------------------------------------------------------

    /**
     * Ophalen person (eigennaar quote of als quote nog niet gemaakt is)
     * @param int $person_id
     * @param bool $raw
     * @return array
     */
    public function getPerson(int $person_id, bool $raw = false):array
    {
        return $this->api->get('person/'.$person_id, [], $raw);
    }
}
