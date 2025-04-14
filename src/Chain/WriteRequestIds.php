<?php

namespace TwoSolar\Chain;

class WriteRequestIds extends Chain
{

    public function initial(array $ids):bool
    {
        // Logger
        $this->twoSolar->logger->info("[".__METHOD__."] Scan op status_id: ". $this->twoSolar->status_id.":".$this->twoSolar->status . ";");
        // validate ids
        return $this->validateData($ids);
    }

    //----------------------------------------------------------------------------------------

    /**
     * Only array with request_ids that where handled  in the previous class (sended by mail)
     * @param array $ids
     * @return bool
     */
    private function validateData(array $ids):bool
    {
        $res = array_filter($ids, function ($var) {
            return is_numeric($var);
        });

        return empty(array_diff($ids, $res));
    }

    //----------------------------------------------------------------------------------------

    /**
     * Write handled request_ids to database
     * @param array $ids
     * @return array
     */
    public function run(array $ids): array
    {
        $handle_ids = [];
        foreach ($ids as $id) {
            // Check if id is already in the list
            if (!$this->twoSolar->db->checkId($id)) {
                // Write to list
                if ($this->twoSolar->db->setId($id)) {
                    array_push($handle_ids, $id);
                    $this->twoSolar->logger->info("[".__METHOD__."] Weg geschreven in DB status_id: " . $this->twoSolar->status_id.":".$this->twoSolar->status . " | request_id: ".$id);
                }
            }
        }

        $rest_ids = array_diff($ids, $handle_ids);

        if (!empty($rest_ids)) {
            $this->twoSolar->logger->info("[".__METHOD__."] Geen success items status_id: " . $this->twoSolar->status_id.":".$this->twoSolar->status);
            $this->twoSolar->logger->info("[".__METHOD__."] ids: " . implode(",", $rest_ids));
        } else {
            $this->twoSolar->logger->info("[".__METHOD__."] Success alles verwerkt status_id: " . $this->twoSolar->status_id.":".$this->twoSolar->status);
        }

        return $rest_ids;
    }
}
