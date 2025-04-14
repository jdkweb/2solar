<?php

namespace TwoSolar\Handler;

use TwoSolar\Handler\HandlerFactory;

abstract class Handler implements HandlerFactory
{
    /**
     * Persons/Quotes to handle
     * @var array
     */
    protected $rows = [];

    /**
     * Logger
     * @var object
     */
    protected object $log;

    /**
     * @return void
     */
    abstract public function run():void;

    //----------------------------------------------------------------------------------------

    /**
     * Collecting requested person / quote
     * @param int $id
     * @return array|false
     */
    public function setRows(array $row):bool|int
    {
        if (is_numeric($row['request_id'])) { //records
            return array_push($this->rows, $row);
        }
        return false;
    }

    //----------------------------------------------------------------------------------------

    /**
     * Synoniem voor setRows
     * @param array $value
     * @return bool|int
     */
    public function setData(array $value):bool|int
    {
        return $this->setRows($value);
    }

    //----------------------------------------------------------------------------------------

    /**
     * Get collected request_ids or id and the requested quote
     * @return array|false
     */
    public function getRows():array
    {
        if (!empty($this->rows)) {
            return $this->rows;
        }
        return [];
    }

    //----------------------------------------------------------------------------------------

    public function getData():array
    {
        return $this->getRows();
    }
}
