<?php

namespace TwoSolar;

use TwoSolar\Database\DataBridge;
use TwoSolar\Database\DataHandler;
use TwoSolar\Log\Logger;
use TwoSolar\InVoker;
use TwoSolar\Mailer\Mailer;

class Wrapper
{

    public string $database_classname_prefix = "Data";
    public string $database_classname;
    public DataBridge $datasource;

    public Logger $logger;

    public string $mailer_classname_prefix = "Mail";
    public string $mailer_classname;

    public Mailer $mailer;

    public function __construct()
    {
        $this->initial();
    }

    //----------------------------------------------------------------------------------------

    public function initial()
    {
        // Set data handler
        $this->setDataHandler();
        $this->createDataBridge($this->createDataHandler());
        // Set Logger
        $this->logger = $this->createLogger();
        // Set mailer
        $this->setMailer();
        $this->mailer = $this->createMailer();
    }

    //----------------------------------------------------------------------------------------

    /**
     * Create DataHandler object
     * @return DataHandler
     * @throws \Exception
     */
    public function createDataHandler(): DataHandler
    {
        $classname = $this->getNameDataHandler();
        return new $classname();
    }

    //----------------------------------------------------------------------------------------

    /**
     * Set Classname and add prefix to use as datahandler
     * @param string $mailer
     * @return void
     */
    public function setDataHandler(string $datahandler = DATA_HANDLER)
    {
        $this->database_classname = $this->database_classname_prefix . $datahandler;
    }

    //----------------------------------------------------------------------------------------

    /**
     * Get name of the actual dataHandler, check if it exists
     * @return string
     * @throws \Exception
     */
    public function getNameDataHandler():string
    {
        if ($this->database_classname == '') {
            $this->setDataHandler();
        }

        $name_handler = "TwoSolar\Database\\" . $this->database_classname;

        if (class_exists($name_handler)) {
            return $name_handler;
        }
        throw new \Exception($name_handler . ' cannot be called, method not available');
    }

    //----------------------------------------------------------------------------------------

    /**
     * Bridge will handle implemented dataHandler
     * @param DataHandler $dataHandler
     * @return void
     */
    public function createDataBridge(DataHandler $dataHandler)
    {
        $this->datasource = new DataBridge($dataHandler);
    }

    //----------------------------------------------------------------------------------------

    public function createLogger(): Logger
    {
        $logger =  new Logger();
        $logger->setLogLevel(LOG_LEVEL);
        return $logger;
    }

    //----------------------------------------------------------------------------------------

    public function createMailer(): Mailer
    {
        $classname = $this->getNameMailHandler();
        return new $classname();
    }

    //----------------------------------------------------------------------------------------

    /**
     * Set Classname and add prefix to use as mailer
     * @param string $mailer
     * @return void
     */
    public function setMailer(string $mailer = MAILER)
    {
        $this->mailer_classname = $this->mailer_classname_prefix . $mailer;
    }

    //----------------------------------------------------------------------------------------

    /**
     * Get name of the actual mailHandler, check if it exists
     * Factory wil handle the mail
     * @return string
     * @throws \Exception
     */
    public function getNameMailHandler():string
    {
        if ($this->mailer_classname == '') {
            $this->setMailer();
        }

        $name_handler = "TwoSolar\Mailer\\" . $this->mailer_classname;

        if (class_exists($name_handler)) {
            return $name_handler;
        }
        throw new \Exception($name_handler . ' cannot be called, method not available');
    }

    //----------------------------------------------------------------------------------------

    public function createInvoker(): invoker
    {
        return new Invoker(new TwoSolar($this->datasource, $this->logger, $this->mailer));
    }
}
