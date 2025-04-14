<?php

namespace TwoSolar\Handler;

use TwoSolar\Handler\Handler;
use TwoSolar\Handler\HandlerFactory;
use TwoSolar\Handler\SolarRestApi;

class HandlerGetQuote extends Handler implements HandlerFactory
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
     * Proces voor verzamelen van quote data
     * @return void
     */
    public function run():void
    {
        $items = $this->getQuotes();

        // error is > 0
        if (count($items) >= 1) {
            foreach ($items as $item) {
                if (!empty($item['request_id']) && is_numeric($item['request_id'])) {
                    // sold_date niet ouder dan 1/2 jaar
                    if (strtotime($item['sold_date']) < (time() - 60*60*24*180)) {
                        continue;
                    }
                    // Verzamel de aanwezige quotes
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
     * Get actual quotes on status_id
     * @param bool $raw
     * @return array
     */
    public function getQuotes()
    {
        $q = ['request_client_status_id' => $this->status_data['status']];

        $arg_str = "?".http_build_query($q);
        $quotes = $this->api->get('quote', [$arg_str], false);

        return $quotes;
    }

    //----------------------------------------------------------------------------------------

    /**
     * Ophalen van specifieke quote op request_id
     * @param int $request_id
     * @return array
     */
    public function getQuote(int $request_id):array
    {
        $result = $this->api->get('quote/'.$request_id, [], false);
        return $result;
    }
}
