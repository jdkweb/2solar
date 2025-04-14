<?php

namespace TwoSolar\Chain;

class DataHandler extends Chain
{

    /**
     * quotes met deze mailadressen niet behandelen
     * @var string[]
     */
    private $email_filter = [
       0 => "elektrotechniekregionaal@outlook.com",
       1 => "ericmeijerelectrotechniek@gmail.com"
    ];

    public function initial(array $rows):bool
    {
        // Logger
        $this->twoSolar->logger->info("[".__METHOD__."] Scan op status_id: ". $this->twoSolar->status_id.":".$this->twoSolar->status . ";");
        // check data before
        return $this->validateData($rows);
    }

    //----------------------------------------------------------------------------------------

    /**
     * Only array with API data accepted, generated in previous class
     * Data is reordered so check again
     * @param array $array ['status_data' => [....], 'items' => [....]];
     * @return bool
     */
    private function validateData(array $array):bool
    {
        // check array
        $rows = array_filter($array['items'], function ($var) {
            return (is_numeric($var['request_id'])  &&
                    $var['request_client_status_id'] == $this->twoSolar->status);
        });

        // specific status data to handle the data
        if (empty($array['status_data'])) {
            return false;
        }

        // required options
        foreach (['id','status','delay','mail_handler','options'] as $key) {
            if (!isset($array['status_data'][$key]) &&
                !is_null($array['status_data'][$key])) {
                // stop handeling
                return false;
            }
        }
        // is het de juiste status data
        if ($array['status_data']['id'] != $this->twoSolar->status_id) {
            return false;
        }

        return (count($array['items']) == count($rows));
    }

    //----------------------------------------------------------------------------------------

    /**
     * Run proces after data validation
     * @param array $data
     * @return array
     */
    public function run(array $data):array
    {
        foreach ($data['items'] as $key => $item) {
            // set data in useable format
            $d = $this->setData($item);

            if (empty($d)) {
                unset($data['items'][$key]);
                continue;
            }
            $data['items'][$key] = $d;
        }

        if (empty($data['items'])) {
            $data = [];
            $this->twoSolar->logger->info("[".__METHOD__."] Geen items status_id: " . $this->twoSolar->status_id.":".$this->twoSolar->status);
        }

        return $data;
    }

    //----------------------------------------------------------------------------------------

    /**
     * Herstructureren van de data om chunks te kunnen parsen voor mail
     * @param array $data
     * @return array
     */
    private function setData(array $data):array
    {
        return ( isset($data['quote']) ?
            $this->getQuoteData($data) :
            $this->getPersonData($data)
        );
    }

    //----------------------------------------------------------------------------------------

    /**
     * Email validation en (quotes) met bepaald emailadres niet behandelen
     * @param string $email
     * @return bool
     */
    private function checkEmail(string $email):bool
    {
        $email = trim($email);
        return !empty($email) &&
            filter_var($email, FILTER_VALIDATE_EMAIL) &&
            !in_array(trim($email), $this->email_filter);
    }

     //----------------------------------------------------------------------------------------

     /**
      * When peron is true, data is achieved by the grabber
      * It is possible there is no emailadres.
      * Mailings by grabber always go to admin so emailadres not required
      *
      * @param $data
      * @param $person
      * @return string|void
      */
    private function getQuoteData($data, $person = false):array
    {
        $data['contact']['infix'] = trim(@$data['contact']['infix']);
        $data['contact']['first_name'] = trim(@$data['contact']['first_name']);

        if (!isset($data['contact']['email']) || !$this->checkEmail($data['contact']['email'])) {
            return [];
        }

        // Create fullname
        $naam =  $data['contact']['first_name'];
        $naam .= ($data['contact']['first_name']!=''?" ":"");
        $naam .= $data['contact']['infix'];
        $naam .= (strlen($data['contact']['infix'])>0?" ":"");
        $naam .= $data['contact']['last_name'];

        // Create phonenumber
        $tel = ($data['contact']['telephone']==""?$data['contact']['mobile']:$data['contact']['telephone']);

        return [
            'request_id'        => $data['request_id'],
            'email'             => $data['contact']['email'],
            'telephone'         => $tel,
            'naam'              => $naam,
            'city'              => $data['contact']['city'],
            'account_manager'   => $data['contact']['account_manager'],
            'current_user_name' => @$data['contact']['current_user_name'],
            'factuurbedrag'     => $data['quote']['actualCostNoVat'],
            'offerte'           => $data['quote']['quote_file_location']
        ];
    }

     //----------------------------------------------------------------------------------------

     /**
      * No contact data available
      *
      * @param $data
      * @return string|void
      */
    private function getPersonData($data):array
    {
        $data['contact'] = $data;
        $data['contact']['account_manager'] = '';
        $data['request_id'] = $data['contact']['request_id'];
        $data['quote']['actualCostNoVat'] = '';
        $data['quote']['quote_file_location'] = '';
        return $this->getQuoteData($data, true);
    }
}
