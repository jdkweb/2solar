<?php

namespace TwoSolar\Chain;

use TwoSolar\Handler\HandlerFactory;
use TwoSolar\Handler\HandlerGetPerson;
use TwoSolar\Handler\HandlerGetQuote;
use TwoSolar\Testdata\GetTestData;

/**
 * Checken welke status moet worden afgehandeld
 * Statussen zonder quote kunnen niet direct via de API worden opgehaald
 * Deze worden opgehaald op de status-code en de laatste aanpassingsdatum
 * (Voorheen werd dit gedaan vai de front-end met een HTML grabber, dit is niet (meer) nodig)
 *
 * Bij het ophalen van de ids via API wordt gelijk de data van de quote meegenomen.
 */
class GetRequestIds extends Chain
{

    public function initial(array $ids):bool
    {
        // Logger
        $this->twoSolar->logger->info("[".__METHOD__."] Scan op status_id: ". $this->twoSolar->status_id.":".$this->twoSolar->status . ";");
        // start always true
        return true;
    }

    //----------------------------------------------------------------------------------------

    /**
     * Called when a object of this class is called like a function
     * @return array|null
     */
    public function run(array $ids = []):array
    {
        $handler = $this->getPersonHandler();
        // run handler
        $handler->run();
        // available records
        $rows = $handler->getRows();

        // empty log
        if (empty($rows)) {
            $this->twoSolar->logger->info("[".__METHOD__."] Geen items status_id: " . $this->twoSolar->status_id.":".$this->twoSolar->status);
        }

        // return result
        return $rows;
    }

    //----------------------------------------------------------------------------------------

    /**
     * Order waar nog geen quote beschikbaar is, alleen persoons gegevens
     * @return HandlerFactory
     */
    private function getPersonHandler():HandlerFactory
    {
        if (DEBUG_TWOSOLAR_USE_TESTDATA) {
            $handler = new GetTestData($this->twoSolar, 'person');
        } else {
            $handler = new HandlerGetPerson($this->twoSolar->logger);
        }
        $handler->setStatus($this->twoSolar->status_data);
        return $handler;
    }
}
