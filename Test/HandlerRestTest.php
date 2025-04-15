<?php

namespace TwoSolarTest;

session_start();

use PHPUnit\Framework\TestCase;
use TwoSolar\Log\Logger;
use TwoSolar\Handler\HandlerGetQuote;

require_once "../vendor/autoload.php";

class HandlerRestTest extends TestCase
{
    private $api;
    public function setUp(): void
    {
        $logger = $this->createMock(Logger::class);
        $logger->method('info');

        $this->api = new HandlerGetQuote($logger);
    }

    /**
     * @dataProvider additionProvider
     */
    public function testGetQuotesForEachStatus(int $status_id, string $handler, int $expected)
    {
        $this->api->set_status_id($status_id);
        $items = $this->api->getQuotes();
        $this->assertIsArray($items);

        if (count($items) >= 1 && !isset($items['error'])) {
            foreach ($items as $item) {
                $this->assertIsArray($item);
                $this->assertIsInt($item['request_id']);
            }
        } elseif (isset($items['error'])) {
            // Geen resultaten
            $this->assertEquals("Nothing found", $items['error']);
        }
    }

    /**
     * 2Solar Status Id's to check
     * @return array[]
     */
    public function additionProvider(): array
    {
        return [
            [105309,  'HandlerRest', 1],
            [133446,  'HandlerRest', 1],
            [105314,  'HandlerRest', 1],
            [1053141, 'HandlerRest', 1],
            [121749,  'HandlerRest', 1],
            [105311,  'HandlerRest', 1],
            [117869,  'HandlerRest', 1]
        ];
    }
}
