<?php
/**
 * Deze website is ontwikkeld door:
 *          _ _____  _  __ __          __   _
 *         | |  __ \| |/ / \ \        / /  | |
 *         | | |  | | ' /   \ \  /\  / /___| |__
 *     _   | | |  | |  <     \ \/  \/ // _ \ '_ \
 *    | |__| | |__| | . \     \  /\  /|  __/ |_) |
 *     \____/|_____/|_|\_\     \/  \/  \___|_.__/
 *
 * www.jdkweb.nl
 *
 * Created by PhpStorm.
 * User: jasper
 * Date: 13-03-2022
 * Time: 14:52
 *
 *
 */

namespace TwoSolar;

class InVoker
{
    public $chain = [
        'TwoSolar\Chain\GetRequestIds',
        'TwoSolar\Chain\CheckRequestIds',
        'TwoSolar\Chain\HandleRequests',
        'TwoSolar\Chain\DataHandler',
        'TwoSolar\Chain\MailHandler',
        'TwoSolar\Chain\WriteRequestIds'
    ];

    //----------------------------------------------------------------------------------------

    public function __construct(public TwoSolar $twoSolar)
    {
        // Get statusId's that must be checked en run chain
        $this->inVoke($this->twoSolar->db->getStatusIds());
    }

    //----------------------------------------------------------------------------------------

    /**
     * Invoke each id for Walk thru then chain to check and handle the id
     *
     * @param array $status_data (actual list of status_data to be checked)
     * @return void
     */
    public function inVoke(array $status_data)
    {
        // Gebruik tester op specifieke status
        //$this->testInVoke($status_data);

        foreach ($status_data as $id => $row) {
            $data = [];
            $this->twoSolar->setStatus($row);
            foreach ($this->chain as $class) {
                //echo "classname: " . $class . "\n";

                if (DEBUG_NO_DATABASE && $class == end($this->chain)) {
                    break;
                    //die('DEBUG EXIT WRITE NOT TO DATABASE');
                }
                $handler = $this->createInstance($class);

                if ($handler->initial($data)) {
                    $data = $handler->run($data);
                } else {
                    // empty data before
                    break;
                }

                // empty data after
                if (empty($data)) {
                    break;
                }
            }

            if (!empty($data)) {
                $this->twoSolar->logger->debug("[".__METHOD__."] niet alle items afgehandeld:");
                $this->twoSolar->logger->debug("[".__METHOD__."]  - status_id:" . $id.":".$row['status']);
            }

            $this->twoSolar->logger->info("=================================================");
        }
    }

    //----------------------------------------------------------------------------------------


    private function testInVoke(array $status_data)
    {
        // TESTEN
        ///////////////////////////////////////////////
        // 1    105260 Bedankt voor uw aanvraag (lead)
        // 2    105265 Afspraak gepland
        // 8    105311 montage
        // 9    105314 Afgerond kan verstuurd worden
        // 11   121749 Feedback
        // 12   133446 1st factuur sturen
        // 13   105298 Uw Offerte
        // 14   105314 Oplevering Compleet. 29 dagen

        $id = 11;
        $data = [];

        $this->twoSolar->setStatus($status_data[$id]);
        foreach ($this->chain as $class) {
            echo "classname: " . $class . "\n";

            if (DEBUG_NO_DATABASE && $class == end($this->chain)) {
                break;
                die('DEBUG EXIT NOT TO DATABASE');
            }

            $handler = $this->createInstance($class);
            if ($handler->initial($data)) {
                $data = $handler->run($data);
            } else {
                die('empty data before');
                break;
            }

            if (empty($data)) {
                die('empty data after');
                break;
            }
        }
        die("TEST INVOKER EXIT status: " . $this->twoSolar->status_id . ", " . $this->twoSolar->status);
    }

    //----------------------------------------------------------------------------------------

    /**
     * Create instance on actual class in chain
     * @param string $classname
     * @return mixed
     */
    private function createInstance(string $classname):object
    {
        return new $classname($this->twoSolar);
    }
}
