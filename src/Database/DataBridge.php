<?php

namespace TwoSolar\Database;

use TwoSolar\Database\DataService;

class DataBridge extends DataService
{

    public function setId(int $id): bool
    {
        return $this->implementation->setId($id);
    }

    public function getIds(bool $ret = false)
    {
        return $this->implementation->getIds($ret);
    }

    public function checkId(int $id): bool
    {
        return $this->implementation->checkId($id);
    }

    public function setStatusId(int $status_id)
    {
        $this->implementation->setStatusId($status_id);
    }

    public function getStatusIds(): array
    {
        return $this->implementation->getStatusIds();
    }

    public function checkStatusId(int $status_id): bool
    {
        return $this->implementation->checkStatusId($status_id);
    }

    public function getPageId(int $status_id): int | null
    {
        return $this->implementation->getPageId($status_id);
    }

    public function getMailHandler(int $status_id): string
    {
        return $this->implementation->getMailHandler($status_id);
    }

    public function statusSelectQuery(array $cols):array
    {
        return $this->implementation->statusSelectQuery($cols);
    }
}
