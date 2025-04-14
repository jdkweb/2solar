<?php

namespace TwoSolarTest;

use PHPUnit\Framework\TestCase;
use TwoSolar\Database\DataBridge;
use TwoSolar\Database\DataMysql;
use TwoSolar\Mailer\Mailer;
use TwoSolar\Invoker;
use TwoSolar\Lib\Database;
use TwoSolar\Log\Logger;
use TwoSolar\TwoSolar;
use TwoSolar\Wrapper;

require_once "../settings.php";
require_once "../vendor/autoload.php";


class WrapperTest extends TestCase
{
    function testCanCreateWrapperObject()
    {
        $wrapper = new Wrapper();

        $this->assertIsObject($wrapper);
        $this->assertInstanceOf(Wrapper::class, $wrapper);

        return $wrapper;
    }

    /**
     *  @depends testCanCreateWrapperObject
     */
    public function testCreateInstanceOftheLogger($wrapper)
    {
        $logger = $wrapper->createLogger();
        $this->assertEquals($logger::class, Logger::class);
        $this->assertInstanceOf(Logger::class, $logger);

        return $wrapper;
    }

    /**
     * @depends testCreateInstanceOftheLogger
     */
    function testGetDataHandlerName($wrapper)
    {
        $name = $wrapper->getNameDataHandler();
        $this->assertIsString($name);
        $this->assertEquals("TwoSolar\Database\Data" . DATA_HANDLER, $name);

        return $wrapper;
    }

    /**
     * @depends testGetDataHandlerName
     */
    function testCreateDataObject($wrapper)
    {
        $name = $wrapper->getNameDataHandler();
        $dataobject = $wrapper->createDataHandler();
        $this->assertIsObject($dataobject);
        $this->assertInstanceOf("TwoSolar\Database\Data" . DATA_HANDLER, $dataobject);

        return [$dataobject, $wrapper];
    }

    /**
     * @depends testCreateDataObject
     */
    function testCreateDataBridge($result)
    {
        $wrapper = $result[1];
        $wrapper->createDataBridge($result[0]);
        $this->assertIsObject($wrapper->datasource);
        $this->assertInstanceOf(DataBridge::class, $wrapper->datasource);

        return $wrapper;
    }

    /**
     * @depends testCreateDataBridge
     */
    function testCreateLogger($wrapper)
    {
        $logger = $wrapper->createLogger();
        $this->assertIsObject($logger);
        $this->assertInstanceOf(Logger::class, $logger);

        return $wrapper;
    }

    /**
     * @depends testCreateLogger
     */
    function testGetMailerClassName($wrapper)
    {
        $mailername = $wrapper->getNameMailHandler();
        $this->assertIsString($mailername);
        $this->assertEquals("TwoSolar\Mailer\Mail" . MAILER, $mailername);

        return $wrapper;
    }

    /**
     * @depends testCreateLogger
     */
    function testCreateMailer($wrapper)
    {
        $mailer = $wrapper->createMailer();
        $this->assertIsObject($mailer);
        $this->assertInstanceOf(Mailer::class, $mailer);

        return $wrapper;
    }

    /**
     * @depends      testCreateMailer
     * @dataProvider additionMailerProvider
     */
    function testGetMailHandler(string $name, string $classname, int $expected, $wrapper)
    {
        $wrapper->setMailer($name);

        $mailer_classname = $wrapper->getNameMailHandler();
        $this->assertIsString($mailer_classname);
        $this->assertEquals("TwoSolar\Mailer\Mail" . $name, $mailer_classname);

        $mailer = $wrapper->createMailer();
        $this->assertEquals($classname, $mailer::class);
        $this->assertInstanceOf(Mailer::class, $mailer);

        return $wrapper;
    }

    public function additionMailerProvider(): array
    {
        return [
            ["PHPMailer", 'TwoSolar\Mailer\MailPHPMailer', 1],
            ["Symfony", 'TwoSolar\Mailer\MailSymfony', 1]
        ];
    }

    /**
     * @depends      testCreateMailer
     * @dataProvider additionDatabaseProvider
     */
    function testToSetDataBridge(string $name, string $classname, int $expected, $wrapper)
    {
        $wrapper->setDataHandler($name);

        $database_classname = $wrapper->getNameDataHandler();
        $this->assertIsString($database_classname);
        $this->assertEquals("TwoSolar\Database\Data" . $name, $database_classname);

        $dataHandler = $wrapper->createDataHandler();

        $wrapper->createDataBridge($dataHandler);
        $this->assertEquals(DataBridge::class, $wrapper->datasource::class);
        $this->assertInstanceOf(DataBridge::class, $wrapper->datasource);

        return $wrapper;
    }

    public function additionDatabaseProvider(): array
    {
        return [
            ["Mysql", 'TwoSolar\Database\DataMysql', 1],
            ["FileService", 'TwoSolar\Database\DataFileService', 1]
        ];
    }
}
