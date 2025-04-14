<?php

namespace TwoSolar\Handler;

interface HandlerFactory
{
    public function run():void;
    public function setRows(array $row): bool|int;
    public function setData(array $value):bool|int;
    public function getRows():array;
    public function getData():array;
}
