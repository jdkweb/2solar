<?php

namespace TwoSolar;

use TwoSolar\Database\DataBridge;
use TwoSolar\Log\Logger;
use TwoSolar\Mailer\Mailer;

class TwoSolar
{
    public int $status_id;

    public int $status;

    public array $status_data;

    //----------------------------------------------------------------------------------------

    /**
     * Wrapper for objects to use in the invoker
     * @param DataBridge $db
     * @param Logger $logger
     * @param Mailer $mailer
     */
    public function __construct(public DataBridge &$db, public Logger &$logger, public Mailer &$mailer)
    {
    }

    //----------------------------------------------------------------------------------------

    public function setStatus(array $status_array)
    {
        $this->status = $status_array['status'];
        $this->status_id = $status_array['id'];
        $this->status_data = $status_array;
    }

    //----------------------------------------------------------------------------------------

    public function getStatusData():array
    {
        $data = $this->db->statusSelectQuery(['id','status','page_id','get_quote','delay','mail_handler','options']);
        $status_data = [];
        foreach ($data as $row) {
            $status_data[$row['id']] = $row;
        }

        return $status_data;
    }
}
