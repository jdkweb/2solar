<?php

namespace TwoSolar\Log;

use TwoSolar\Log\LogFactory;
use TwoSolar\Log\Logger;
use Laminas\Log\Writer\Stream;

//use Laminas\Log\Logger;

class Logger implements LogFactory
{

    private string $log_path;

    private string $log_file;

    private object $logger;

    private int $log_level = 2;

    //----------------------------------------------------------------------------------------

    public function __construct()
    {
        $this->log_path = dirname(dirname(__DIR__)) ."/Log/";

        if (!$this->setLogFile()) {
            die();
        }

        // Create an API object
        $writer = new Stream($this->log_path . $this->log_file);
        $this->logger = new \Laminas\Log\Logger();
        $this->logger->addWriter($writer);
    }

    //----------------------------------------------------------------------------------------

    public function setLogFile():bool
    {
        $this->log_file = "log-01-".date("m-Y");

        if (!file_exists($this->log_path . $this->log_file)) {
            if (!fopen($this->log_path . $this->log_file, "w")) {
                return false;
            }
        }
        return true;
    }

    //----------------------------------------------------------------------------------------

    public function setLogLevel(int $level)
    {
        if (!in_array($level, [0,1,2,3])) {
            $this->log_level = 2;
        }

        $this->log_level = $level;
    }

    //----------------------------------------------------------------------------------------

    public function error(string $message)
    {
        $this->logger->err($message);
    }

    //----------------------------------------------------------------------------------------

    public function warn(string $message)
    {
        if ($this->log_level > 0) {
            $this->logger->warn($message);
        }
    }

    //----------------------------------------------------------------------------------------

    public function info(string $message)
    {
        if ($this->log_level > 1) {
            $this->logger->info($message);
        }
    }

    //----------------------------------------------------------------------------------------

    public function debug(string $message)
    {
        if ($this->log_level > 2) {
            $this->logger->debug($message);
        }
    }
}
