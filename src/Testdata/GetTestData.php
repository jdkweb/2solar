<?php

namespace TwoSolar\Testdata;

use TwoSolar\TwoSolar;
use TwoSolar\Handler\Handler;
use TwoSolar\Handler\HandlerFactory;

class GetTestData extends Handler implements HandlerFactory
{
    //public $status_ids = [];
    public $request_ids = [
          9990001
//        9990002,
//        9990003,
//        9990004,
//        9990005
    ];

    public $status_id;

    public $status_data = [];

    public function __construct(public TwoSolar $twoSolar, public string $type)
    {
        //$this->status_ids = $this->twoSolar->db->getStatusIds();
    }

    public function getPerson($request_id)
    {
        $date = new \DateTimeImmutable();
        if (DEBUG_TWOSOLAR_USE_TESTDATA_NO_DELAY) {
            $date = $date->modify('-'.$this->status_data['delay'].' day');
        }
        $output = [];

        foreach ($this->request_ids as $key => $id) {
            if ($request_id == $id) {
                $data = [
                    'REQUEST_ID' => $id,
                    'STATUS_ID' => $this->status_data['status'],
                    'PERSON_ID' => $id,
                    'REQUEST_EDITED' => $date->format('Y-m-d h:i:s'),
                    'REQUEST_UPDATED' => $date->format('Y-m-d h:i:s'),
                    'FIRST_NAME' => 'Adriënne'.$key+1,
                    'INFIX' => '',
                    'LAST_NAME' => 'Jänzên'.$key+1,
                    'ADDRESS' => 'STRAATWEG',
                    'NUMBER' => $key+1,
                    'POSTCODE' => '4040AB',
                    'CITY' => 'Tèststäd'.$key+1,
                    'EMAIL' => 'test'.$key.'@jaspderdk.nl'
                ];

                $json = $this->getChunk('person', $data);
                break;
            }
        }

        return json_decode($json, true);
    }

    public function getQuote($request_id)
    {
        $date = new \DateTimeImmutable();
        if (DEBUG_TWOSOLAR_USE_TESTDATA_NO_DELAY) {
            $date = $date->modify('-' . $this->status_data['delay'] . ' day');
        }
        $output = [];

        foreach ($this->request_ids as $key => $id) {
            if ($request_id == $id) {
                $data = [
                    'REQUEST_ID' => $id,
                    'STATUS_ID' => $this->status_data['status'],
                    'REQUEST_STATUS' => $this->status_data['description'],
                    'PERSON_ID' => $id,
                    'REQUEST_UPDATED' => $date->format('Y-m-d h:i:s'),
                    'EMAIL' => 'test'.$key.'@jaspderdk.nl',
                    'FIRST_NAME' => 'Adriënne'.$key+1,
                    'INFIX' => '',
                    'LAST_NAME' => 'Jänzên'.$key+1,
                    'ADDRESS' => 'STRAATWEG',
                    'NUMBER' => $key+1,
                    'POSTCODE' => '4040AB',
                    'CITY' => 'Tèststäd'.$key+1
                ];

                $json = $this->getChunk('quote', $data);
                break;
            }
        }

        return json_decode($json, true);
    }

    //---------------------------------------------------------------------------------------

    private function getChunk(string $name, array $data): string
    {
        $file_path = dirname(__DIR__) ."/Testdata/".$name.".tpl";

        if (!file_exists($file_path)) {
            return "file not found: " .$file_path;
        }

        $arr = [];
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                continue;
            }
            $arr["[".strtoupper($key)."]"] = $value;
        }

        $text = file_get_contents($file_path);

        return str_replace(array_keys($arr), array_values($arr), $text);
    }

    //----------------------------------------------------------------------------------------

    /**
     * Status_id always 6 long extra number is for special handeling
     * @param int $id
     * @return void
     */
    public function setStatus(array $status_data)
    {
        $this->status_id = $status_data['id'];
        $this->status_data = $status_data;
    }

    //---------------------------------------------------------------------------------------

    /**
     * Functies om HandlerFactory type te realiseren
     * Deze functies zijn iet nodig voor de test data
     */
    public function run():void
    {
        $this->{$this->type."_run"}();
    }

    //---------------------------------------------------------------------------------------

    public function person_run()
    {
        $items = [];
        foreach ($this->request_ids as $request_id) {
            array_push($items, $this->getPerson($request_id));
        }

        if (count($items) >= 1) {
            foreach ($items as $item) {
                $item = reset($item);
                if (!empty($item['request_id']) && is_numeric($item['request_id'])) {
                    // Verzamel de aanwezige persons (zonder quote)
                    $this->setData($item);
                }
            }
        }
    }

    //---------------------------------------------------------------------------------------

    public function quote_run()
    {
        $items = [];
        foreach ($this->request_ids as $request_id) {
            array_push($items, $this->getQuote($request_id));
        }

        // error is > 0
        if (count($items) >= 1) {
            foreach ($items as $item) {
                if (!empty($item['request_id']) && is_numeric($item['request_id'])) {
                    // Person toevoegen, voor request_edited datum en persoons gegevens
                    $person = $this->getPerson($item['request_id']);
                    $person = reset($person);
                    $item['request_edited'] = $person['request_edited'];

                    // Verzamel de aanwezige quotes
                    // Opgehaalde quote meteen meegeven zodat dit niet nog eens hoeft te gebeuren.
                    $this->setData(array('id' => $item['request_id'], 'quote' => $item));
                }
            }
        }
    }
}
