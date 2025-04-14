<?php

namespace TwoSolar\Database;

use TwoSolar\Database\DataHandler;

abstract class DataService
{
    /**
     * Create Databridge and set the dataHandler
     * @param \TwoSolar\Database\DataHandler $implementation
     */
    public function __construct(protected DataHandler $implementation)
    {
    }

    /**
     * Change defined dataHandler
     * @param \TwoSolar\Database\DataHandler $implementation
     * @return void
     */
    public function setImplementation(DataHandler $implementation)
    {
        $this->implementation = $implementation;
    }

    abstract public function setId(int $id): bool;
    abstract public function getIds(bool $ret = false);
    abstract public function checkId(int $id): bool;
    abstract public function setStatusId(int $status_id);
    abstract public function checkStatusId(int $status_id): bool;
    abstract public function getPageId(int $status_id): int | null;
    abstract public function getMailHandler(int $status_id): string;
    abstract public function statusSelectQuery(array $cols):array;
}
