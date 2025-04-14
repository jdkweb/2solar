<?php

namespace TwoSolar\Chain;

use TwoSolar\TwoSolar;

abstract class Chain
{
    abstract public function initial(array $ids):bool;
    abstract public function run(array $ids):array;

    //----------------------------------------------------------------------------------------

    public function __construct(public TwoSolar $twoSolar)
    {
        // wrong id not automated
        if (!$this->twoSolar->db->checkStatusId($this->twoSolar->status_id)) {
            throw new \Exception("[".__METHOD__."] Actuel status_id does not exists, Chain has been broken");
        }

        // Set status id for datahandler
        $this->twoSolar->db->setStatusId($this->twoSolar->status_id);
    }

    //----------------------------------------------------------------------------------------

    /**
     * Does status id exists
     * @param int $status_id
     * @return bool
     */
    public function checkStatus(int $status_id):bool
    {
        return !empty($this->twoSolar->db->checkStatusId($status_id));
    }
}
