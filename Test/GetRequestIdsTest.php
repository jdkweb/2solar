<?php

/**
 - 105293   Lead offertevegelijker.nl
            WEG 105298   offerte opvolgen                   ==> Geschrapt 24-01-2023
 - 105298  offerte opvolgen en er zijn 4 dagen verstreken   ==> Aangepast is nu geen sub_status meer
 - 105260   lead
 - 105292   lead website
 - 117869   Reclameren
 - 105265   afspraak gemaakt
 * 133446   1e factuur sturen
            WEG 105301   offerte akkoord
 * 121749   Afgerond
            WEG 1217491  Afgerond Extra mailing (LET OP EXTRA 1 statuscode[1])  ==> Geschrapt 19-09-2022
 * 105311   montage gepland
 * 105309   montage inplannen
 *          119144   offerte onderhandeling TIJDELIJK VOOR TESTEN
 * 105314   oplevering complete
 * 1053141  Klant heeft status ‘oplevering compleet’ (ID 105314) en er zijn 29 dagen verstreken
 *
 * 0 => get data with REST API
 - 1 => get data with HTML grabber
 *
 * @var int[]
 */

namespace TwoSolarTest;

session_start();

//use TwoSolar\Handler\HandlerRest;
use TwoSolar\Log\Logger;
use PHPUnit\Framework\TestCase;
use TwoSolar\Database\DataBridge;
//use TwoSolar\Database\DataMysql;
//use TwoSolar\InVoker;
use TwoSolar\Mailer\Mailer;
use TwoSolar\TwoSolar;
use TwoSolar\Chain\GetRequestIds;

require_once "../vendor/autoload.php";

class GetRequestIdsTest extends TestCase
{
//    function testGetRequestIdsWithRestAPI()
//    {
//        // replace datasource Only for Rest Handler is returned
//        $dataBridge = $this->createMock(DataBridge::class);
//        $dataBridge->method('checkStatusId')
//                   ->will($this->returnValue(true));
//        $dataBridge->method('setStatusId')
//                   ->will($this->returnValue(true));
//
//        $logger = $this->createMock(Logger::class);
//        $logger->method('info');
//
//        $mailer = $this->createMock(Mailer::class);
//
//        $twoSolar = new TwoSolar($dataBridge, $logger, $mailer);
//        $this->assertIsObject($twoSolar);
//
//        return $twoSolar;
//    }
    function testGetRequestIdsWithRestAPI()
    {
    }

    /**
     * @depends testGetRequestIdsWithRestAPI
     * @dataProvider additionProvider
     */
    function testGetHandler(int $status_id, string $handler, int $expected, $twoSolar)
    {
        // montage inplannen
        $twoSolar->setStatus(['status_id' => $status_id]);
        $getrequestids = new GetRequestIds($twoSolar);
        $handler = $getrequestids->getHandler();
        $this->assertIsObject($handler);
        $this->assertInstanceOf($handler::class, $handler);
    }

    /**
     * 2Solar Status Id's to check
     * @return array[]
     */
    public function additionProvider(): array
    {
        return [
            [105311,  'HandlerGetQuote', 1],
            [105314,  'HandlerGetQuote', 1],
            [117869,  'HandlerGetQuote', 1],
            [121749,  'HandlerGetQuote', 1],
            [133446,  'HandlerGetQuote', 1],
            [1053141, 'HandlerGetQuote', 1],
            [105260,  'HandlerGetPerson', 0],
            [105265,  'HandlerGetPerson', 0],
            [105292,  'HandlerGetPerson', 0],
            [105293,  'HandlerGetPerson', 0],
            [1052981, 'HandlerGetPerson', 0]
        ];
    }
}
