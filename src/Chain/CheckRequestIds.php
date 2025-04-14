<?php

namespace TwoSolar\Chain;

/**
 * Checken of de opgehaalde ids al reeds zijn afgehandeld
 * Afgehandelede ids zijn opgeslagen
 */
class CheckRequestIds extends Chain
{

    public function initial(array $rows):bool
    {
        // Logger
        $this->twoSolar->logger->info("[".__METHOD__."] Scan op status_id: ". $this->twoSolar->status_id.":".$this->twoSolar->status . ";");
        // check data before
        return $this->validateData($rows);
    }

    //----------------------------------------------------------------------------------------

    /**
     * Only array with ids accepted
     * @param array $array
     * @return bool
     */
    private function validateData(array $array):bool
    {
        $rows = [];
        // check array
        $rows = array_filter($array, function ($var) {
            return ( is_numeric($var['request_id']) &&
                     $var['request_client_status_id'] == $this->twoSolar->status);
        });
        // extra check
        return (count($rows) == count($array));
    }

    //----------------------------------------------------------------------------------------

    /**
     * Check of betreffende id al behandeld is en dus in de database staat
     * @param array $rows
     * @return array
     */
    public function run(array $rows):array
    {
        // extra check
        if (empty($rows)) {
            // No ids break chain with null
            $this->twoSolar->logger->debug("[".__METHOD__."] Geen items status_id: " . $this->twoSolar->status_id.":".$this->twoSolar->status);
            return [];
        }

        // items to handle
        // item can be the id of a person or an array with quote id and quote data
        $handle_items = [];
        foreach ($rows as $row) {
            if (is_numeric($row['request_id'])) {
                // Check if id is already handled
                if (!$this->twoSolar->db->checkId($row['request_id'])) {
                    array_push($handle_items, $row);
                }
            }
        }

        // empty log
        if (empty($handle_items)) {
            $this->twoSolar->logger->info("[".__METHOD__."] Geen items status_id: " . $this->twoSolar->status_id.":".$this->twoSolar->status);
        }

        // Remaining_ids for next step in the chain
        return $handle_items;
    }
}
