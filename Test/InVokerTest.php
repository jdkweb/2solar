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
 * Date: 10-03-2023
 * Time: 18:24
 * To change this template use File | Settings | File Templates.
 */
namespace TwoSolarTest;

use TwoSolar\Log\Logger;
use PHPUnit\Framework\TestCase;
use TwoSolar\Database\DataBridge;
use TwoSolar\Database\DataMysql;
use TwoSolar\InVoker;
use TwoSolar\Mailer\Mailer;
use TwoSolar\TwoSolar;
use TwoSolar\Wrapper;

require_once "../settings.php";
require_once "../vendor/autoload.php";

class InVokerTest extends TestCase
{

    function testInvokeWithRestAPI()
    {
        // replace datasource Only for Rest Handler is returned
        $dataBridge = $this->createMock(DataBridge::class);
        $dataBridge->expects($this->once())
                   ->method('getStatusIds')
                   ->willReturn([
                       // 2Solar status Id's
                       105309, 105311, 105314, 117869, 121749, 133446, 1052981, 1053141
                   ]);
        $dataBridge->method('checkStatusId')
                   ->will($this->returnValue(true));
        $dataBridge->method('setStatusId')
                   ->will($this->returnValue(true));

        $logger = $this->createMock(Logger::class);
        $logger->method('info');

        $mailer = $this->createMock(Mailer::class);

        $invoker = new InVoker(new TwoSolar($dataBridge, $logger, $mailer));

        $this->assertIsObject($invoker);
    }

    function ___testInvokeWithHTMLGrabber()
    {
        // replace datasource Only for Rest Handler is returned
        $dataBridge = $this->createMock(DataBridge::class);
        $dataBridge->expects($this->once())
                   ->method('getStatusIds')
                   ->willReturn([
                       // 2Solar status Id's
                       105260, 105265, 105292, 105293, 1052981
                   ]);
        $dataBridge->method('checkStatusId')
                   ->will($this->returnValue(true));
        $dataBridge->method('setStatusId')
                   ->will($this->returnValue(true));

        $logger = $this->createMock(Logger::class);
        $logger->method('info');

        $mailer = $this->createMock(Mailer::class);

        $invoker = new InVoker(new TwoSolar($dataBridge, $logger, $mailer));

        $this->assertIsObject($invoker);
    }
}
