<?php

namespace TwoSolar\Database;

interface DataHandler
{
    public function setId(int $id): bool;
    public function getIds(bool $ret = false);
    public function checkId(int $id): bool;
    public function setStatusId(int $id);
    public function checkStatusId(int $id): bool;
    public function getPageId(int $id): int | null;
    public function getMailHandler(int $status_id): string;
    public function statusSelectQuery(array $cols):array;
}
