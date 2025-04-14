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

use PHPUnit\Framework\TestCase;
use TwoSolar\StatusHandler;
use TwoSolar\Wrapper;

require_once "../vendor/autoload.php";

class StatusHandlerTest extends TestCase
{

    function testCanCreateStatusHandlerObject()
    {
        $wrapper = new Wrapper();
        $statusHandler = new StatusHandler($wrapper->datasource);

        $this->assertIsObject($statusHandler);
        $this->assertInstanceOf(StatusHandler::class, $statusHandler);

        return $statusHandler;
    }

    /**
     * @depends testCanCreateStatusHandlerObject
     */
    function testGetStatusIds($statusHandler)
    {
        $result = $statusHandler->getStatusIds();

        $this->assertIsArray($result);
    }
}
