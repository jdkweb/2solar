<?php

namespace TwoSolar\Database;

use TwoSolar\Database\DataHandler;

class DataFileService implements DataHandler
{
    /**
     * Dataset voor iedere status
     *
     *  Required:
     *  @param int status_id
     *  @param string description
     *  @param int|null page_id
     *  @param int delay
     *  @param string mail_handler
     *
     * @var array|array[]
     */
    private array $dataset = [
        1 => [
            'id' => 1,
            'status' => '105260',
            'description' => 'lead',
            'page_id' => 83308,
            'get_quote' => 0,
            'delay' => '1',
            'mail_handler' => 'richmailer',
            'mail_bcc' => 'jdkweb@hotmail.com,info@zonnepanelen.io',
            'mail_subject' => 'Bedankt voor uw aanvraag'
        ],
        2 => [
            'id' => 2,
            'status' => '105265',
            'description' => 'afspraak gemaakt',
            'page_id' => 83309,
            'get_quote' => 0,
            'delay' => '2',
            'mail_handler' => 'richmailer',
            'mail_bcc' => 'jdkweb@hotmail.com,info@zonnepanelen.io',
            'mail_subject' => 'Afspraak gepland'
        ],
        3 => [
            'id' => 3,
            'status' => '105292',
            'description' => 'lead website',
            'page_id' => 91828,
            'get_quote' => 0,
            'delay' => '1',
            'mail_handler' => 'richmailer',
            'mail_bcc' =>  'jdkweb@hotmail.com,info@zonnepanelen.io',
            'mail_subject' => 'Bedankt voor uw aanvraag',
        ],
        4 => [
            'id' => 4,
            'status' => '105293',
            'description' => 'lead offertevergelijker.nl',
            'page_id' => 97066,
            'get_quote' => 0,
            'delay' => '1',
            'mail_handler' => 'richmailer',
            'mail_bcc' =>  'jdkweb@hotmail.com,info@zonnepanelen.io',
            'mail_subject' => 'Bedankt voor je aanvraag',
        ],
//        4 => [
//            'status' => '105309',
//            'description' => 'montage inplannen',
//            'page_id' => '',
//            'delay' => '5',
//            'mail_handler' => 'richmailer',
//            'mail_bcc' =>  'jdkweb@hotmail.com,info@zonnepanelen.io',
//            'mail_subject' => 'Wij zijn u niet vergeten!',
//        ],
        5 => [
            'id' => 8,
            'status' => '105311',
            'description' => 'montage gepland',
            'page_id' => null,
            'get_quote' => 0,
            'delay' => '2',
            'mail_handler' => 'richmailer',
            'mail_bcc' =>  'jdkweb@hotmail.com,info@zonnepanelen.io',
            'mail_subject' => 'Montage gepland'
        ],
        6 => [
            'id' => 9,
            'status' => '105314',
            'description' => 'oplevering compleet',
            'page_id' => null,
            'get_quote' => 1,
            'delay' => '1',
            'mail_handler' => 'mailer',
            'mail_to' => 'administratie@zonnepanelen.io',
            'mail_subject' => 'Dit project is afgerond en de laatste factuur kan verstuurd worden'
        ],
        7 => [
            'id' => 10,
            'status' => '117869',
            'description' => 'reclameren',
            'page_id' => 93812,
            'get_quote' => 0,
            'delay' => '1',
            'mail_handler' => 'mailer',
            'mail_to' => 'info@zonnepanelen.io',
            'mail_subject' => 'Deze klant moet gereclameerd worden'
        ],
        8 => [
            'id' => 11,
            'status' => '121749',
            'description' => 'afgerond',
            'page_id' => null,
            'get_quote' => 0,
            'delay' => '7',
            'mail_handler' => 'richmailer',
            'mail_bcc' =>  'jdkweb@hotmail.com,info@zonnepanelen.io',
            'mail_subject' => 'Feedback',
        ],
        9 => [
            'id' => 12,
            'status' => '133446',
            'description' => '1st factuur sturen',
            'page_id' => null,
            'get_quote' => 1,
            'delay' => '1',
            'mail_handler' => 'mailer',
            'mail_to' => 'administratie@zonnepanelen.io',
            'mail_subject' => 'Deze klant akkoord met offerte, voorschotfactuur kan gemaakt worden.'
        ],
        10 => [
            'id' => 13,
            'status' => 105298,
            'description' => 'offerte opvolgen, 4 dagen verstreken',
            'page_id' => 83314,
            'get_quote' => 0,
            'delay' => '4',
            'mail_handler' => 'richmailer',
            'mail_bcc' =>  'jdkweb@hotmail.com,info@zonnepanelen.io',
            'mail_subject' => 'Uw offerte'
        ],
        11 => [
            'id' => 14,
            'status' => '105314',
            'description' => 'Oplevering compleet 29 dagen verstreken',
            'page_id' => null,
            'get_quote' => 0,
            'delay' => '29',
            'mail_handler' => 'richmailer',
            'mail_bcc' =>  'jdkweb@hotmail.com,info@zonnepanelen.io',
            'mail_subject' => 'Wilt u ons binnenkort beoordelen?'
        ],
    ];

    private string $file_path;

    /**
     * Prefix file data filenames
     * Filename: [prefix][status_id]
     * @var string
     */
    private string $file_prefix = "requests_";

    /**
     * Actuel status_id
     * @var int
     */
    private int $status_id;

    private array $status_ids = [];

    private array $request_ids = [];

    //----------------------------------------------------------------------------------------

    public function __construct()
    {
        $this->file_path = dirname(dirname(__DIR__)) . "/data/";

        if (!is_dir($this->file_path)) {
            mkdir($this->file_path);
        }
    }

    //----------------------------------------------------------------------------------------

    /**
     * Write id to database
     * @param int $id
     * @return bool
     */
    public function setId(int $id): bool
    {
        $file = $this->file_path . $this->file_prefix . $this->status_id."_".$this->status_ids[$this->status_id]['status'];
        // mode a: write only; pointer at the end of the file.
        // If the file does not exist, attempt to create it.
        $fp = fopen($file, 'a');
        if (fwrite($fp, $id . PHP_EOL)) {
            return true;
        }
        return false;
    }

    //----------------------------------------------------------------------------------------

    /**
     * Get all ids that are available
     * @return void
     */
    public function getIds(bool $ret = false)
    {
        $list = [];
        if (empty($this->request_ids)) {
            $this->request_ids[$this->status_id] = [];
            $file = $this->file_path . $this->file_prefix . $this->status_id."_".$this->status_ids[$this->status_id]['status'];
            if (file_exists($file)) {
                $list = file($file);
            }
        }

        foreach ($list as $r) {
            $this->request_ids[$this->status_id][] = trim($r);
        }
    }

    //----------------------------------------------------------------------------------------

    /**
     * Settings terug brengen naar database structuur
     * @param array $cols
     * @return array
     */
    public function statusSelectQuery(array $cols = []): array
    {
        $result = [];
        foreach ($this->dataset as $key => $set) {
            if (empty($cols)) {
                $result[$key] = array_slice($set, 0, count($cols)-2);
                $result[$key]['options'] = json_encode(array_slice($set, count($cols)-2));
            } else {
                foreach ($cols as $col) {
                    if ($col != 'options') {
                        if (isset($set[$col])) {
                            $result[$key][$col] = $set[$col];
                        }
                    } else {
                        $result[$key][$col] = json_encode(array_slice($set, count($cols)-1));
                    }
                }
            }
        }

        return $result;
    }

    //----------------------------------------------------------------------------------------

    /**
     * Get page for HTML grabber
     * @param int $status_id
     * @return bool
     */
    public function getPageId(int $id): int|null
    {
        foreach ($this->dataset as $row) {
            if ($id == $row['id']) {
                return $row['page_id'];
            }
        }

        return null;
    }

    //----------------------------------------------------------------------------------------

    /**
     * Get mailhandler for specific way of sending
     * @param int $status_id
     * @return string
     */
    public function getMailHandler(int $status_id): string
    {
        $row = [];
        foreach ($this->dataset as $row) {
            if ($status_id == $row['status_id']) {
                break;
            }
        }

        return $row[$status_id]['mailhandler'] ?? 'mailer';
    }


    //----------------------------------------------------------------------------------------

    public function getStatusIds(): array
    {
        foreach ($this->dataset as $key => $row) {
            $this->status_ids[$row['id']] = $row;
        }

        return  $this->status_ids;
    }

    //----------------------------------------------------------------------------------------

    public function checkStatusId(int $status_id): bool
    {
        if (empty($this->status_ids)) {
            $this->status_ids = $this->getStatusIds();
        }

        //return in_array($status_id, $this->status_ids);
        return isset($this->status_ids[$status_id]);
    }


    //----------------------------------------------------------------------------------------

    public function checkId(int $id): bool
    {
        // Already used
        if (!isset($this->request_ids[$this->status_id])) {
            $this->request_ids = [];
            $this->getIds();
        }

        return in_array(trim($id), $this->request_ids[$this->status_id]);
    }

    //----------------------------------------------------------------------------------------

    public function setStatusId(int $status_id)
    {
        $this->status_id = $status_id;
    }
}
