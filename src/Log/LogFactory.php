<?php

namespace TwoSolar\Log;

interface LogFactory
{
    public function setLogFile():bool;
    public function setLogLevel(int $level);
    public function error(string $message);
    public function warn(string $message);
    public function info(string $message);
    public function debug(string $message);
}
