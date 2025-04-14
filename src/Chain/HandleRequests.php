<?php

namespace TwoSolar\Chain;

use TwoSolar\Handler\HandlerFactory;
use TwoSolar\Handler\HandlerGetPerson;
use TwoSolar\Handler\HandlerGetQuote;
use TwoSolar\Testdata\GetTestData;

class HandleRequests extends Chain
{

    public function initial(array $rows): bool
    {
        // Logger
        $this->twoSolar->logger->info("[" . __METHOD__ . "] Scan op status_id: " . $this->twoSolar->status_id . ":" . $this->twoSolar->status . ";");
        // count data before
        return (count($rows) > 0);
    }

    //----------------------------------------------------------------------------------------

    /**
     * @param array $rows
     * @return array
     */
    public function run(array $rows): array
    {
        // Status data, active statuses and options how to handle the status
        $status_data = $this->twoSolar->getStatusData();

        // Success data
        $data = ['status_data' => $status_data[$this->twoSolar->status_id], 'items' => []];
        foreach ($rows as $row) {
            // Quote ophalen als nodig
            if ($status_data[$this->twoSolar->status_id]['get_quote']) {
                $row = $this->addQuote($row);
            }

            // Check datum voor verzenden (delay)
            if ($this->checkTime($row, $status_data)) {
                // toevoegen van statusdata voor volgende stap
                $data['items'][] = $row;
            }
        }

        // empty log
        if (empty($data['items'])) {
            $data = [];
            $this->twoSolar->logger->info("[" . __METHOD__ . "] Geen items status_id: " . $this->twoSolar->status_id . ":" . $this->twoSolar->status);
        } else {
            $this->twoSolar->logger->info("[" . __METHOD__ . "] Aantal items status_id: " . count($data));
        }

        return $data;
    }

    //----------------------------------------------------------------------------------------

    /**
     * Quote gegevens toevoegen
     * @param array $row
     * @return array
     */
    private function addQuote(array $row): array
    {
        $handler = $this->getQuoteHandler();
        $result = $handler->getQuote($row['request_id']);

        if (is_array($result)) {
            $result = array_merge($result, $row);
        }

        return $result;
    }

    //----------------------------------------------------------------------------------------

    /**
     * Order met quote, persoons gegevens en offerte gegevens
     * @return HandlerFactory
     */
    private function getQuoteHandler():HandlerFactory
    {
        if (DEBUG_TWOSOLAR_USE_TESTDATA) {
            $handler = new GetTestData($this->twoSolar, 'quote');
        } else {
            $handler = new HandlerGetQuote($this->twoSolar->logger);
        }
        $handler->setStatus($this->twoSolar->status_data);
        return $handler;
    }


    //----------------------------------------------------------------------------------------

    /**
     * Check if this item still or already needs to be sended
     *
     * @param $item        Aanvraag data
     * @param $status_data Speciale settings die bij de status horen
     * @return bool
     */
    public function checkTime($item, $status_data): bool
    {
        $delay = $status_data[$this->twoSolar->status_id]['delay'];

        if (DEBUG_NO_DELAY) {
            $delay = 1;
        }

        $result = false;
        if (empty($item['request_edited'])) {
            return false;
        }

        // Tijd aanvraag
        $tijd = new \DateTime(substr($item['request_edited'], 0, 10));
        // Tijd nu
        $now = new \DateTime(date("Y-m-d"));
        // Verschil
        $interval = $tijd->diff($now);
        // Tijds verschil in dagen
        $dagen = $interval->format('%a');

        if ($delay == 1) {
            $result = ($dagen < 2);
        } else {
            $result = ($dagen == $delay);
        }

        return $result;
    }
}
