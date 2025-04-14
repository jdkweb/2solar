<?php

namespace TwoSolar\Database;

use TwoSolar\Database\DataHandler;

class DataPgsql implements DataHandler
{
    /**
     *  CONST MYSQL_SERVER
     *
     *  mysql server
     * @var string
     */

    /**
     *  CONST MYSQL_DB
     *
     *  mysql database
     * @var string
     */

    /**
     *  CONST MYSQL_USER
     *
     *  mysql username
     * @var string
     */

    /**
     *  CONST MYSQL_PASS
     *
     *  mysql password
     * @var string
     */


    private $pgsql;

    private int $status_id;

    private array $status_ids = [];

    private array $request_ids = [];

    public function __construct()
    {
        $this->pgsql = pg_connect("host=" . PGSQL_SERVER . " dbname=" . PGSQL_DB . " user=" . PGSQL_USER . " password=" . PGSQL_PASS) or die('Could not connect: ' . pg_last_error());
    }

    //----------------------------------------------------------------------------------------

    /**
     * Write id to database
     * @param int $id
     * @return bool
     */
    public function setId(int $id): bool
    {
        $res = pg_insert($this->pgsql, 'handled', array("request_id" => $id, "status_id" => $this->status_id));

        if ($res) {
            return true;
        } else {
            return false;
        }
    }

    //----------------------------------------------------------------------------------------

    /**
     * Get all ids that are available
     * @param bool $ret return result
     * @return void | array
     */
    public function getIds(bool $ret = false)
    {
        $query = "SELECT request_id FROM handled " .
            "WHERE status_id = '" . $this->status_id . "' " .
            "AND created > CURRENT_DATE + interval '1 year' " .
            "ORDER BY created";

        $result = pg_query($this->pgsql, $query);

        if (!$result) {
            echo "An error occurred.\n";
            exit;
        }

        $this->request_ids[$this->status_id] = [];
        while ($row = pg_fetch_row($result)) {
            $this->request_ids[$this->status_id][] = $row['request_id'];
        }

        if ($ret) {
            return $this->request_ids[$this->status_id];
        }
    }

    //----------------------------------------------------------------------------------------

    /**
     * Run select query in status table
     * @return array
     */
    public function statusSelectQuery(array $cols = []): array
    {
        if (empty($cols)) {
            $cols = ['id', 'status', 'description', 'page_id', 'get_quote', 'delay', 'mail_handler', 'options'];
            $query = "SELECT " . implode(",", $cols) . " FROM status WHERE active = TRUE";
        } else {
            $query = "SELECT " . implode(",", $cols) . " FROM status WHERE active = TRUE";
        }

        $resObj = pg_query($this->pgsql, $query);

        if (!$resObj) {
            echo "An error occurred.\n";
            exit;
        }

        $result = [];
        while ($row = pg_fetch_assoc($resObj)) {
            if (isset($cols['delay'])) {
                $row['delay'] = $row['delay'] + DEBUG_BACK_IN_TIME;
            }
            $result[] = $row;
        }

        return $result;
    }

    //----------------------------------------------------------------------------------------

    /**
     * Get page_id for HTML grabber
     * @param int $id id van de status in behandeling (niet de status_id zelf)
     * @return int | null
     */
    public function getPageId(int $id): int|null
    {
        $result = $this->statusSelectQuery(['id', 'status', 'page_id']);

        foreach ($result as $arr) {
            if ($arr['id'] == $id) {
                return $arr['page_id'];
            }
        }

        return null;
    }

    //----------------------------------------------------------------------------------------

    /**
     * Get mailhandler for specific way of sending
     * @param int $id id van de status in behandeling (niet de status_id zelf)
     * @return string
     */
    public function getMailHandler(int $id): string
    {
        $result = $this->statusSelectQuery(['id', 'status', 'mail_handler']);

        foreach ($result as $arr) {
            if ($arr['id'] == $id) {
                return $arr['mail_handler'];
            }
        }

        return '';
    }

    //----------------------------------------------------------------------------------------

    /**
     * Get all status_ids
     * @return array
     */
    public function getStatusIds(): array
    {
        if (empty($this->status_ids)) {
            $result = $this->statusSelectQuery();

            foreach ($result as $arr) {
                $this->status_ids[$arr['id']] = $arr;
            }
        }

        return $this->status_ids;
    }

    //----------------------------------------------------------------------------------------

    /**
     * Check of actuele status_id bestaat
     * @param int $status_id
     * @return bool
     */
    public function checkStatusId(int $id): bool
    {
        if (empty($this->status_ids)) {
            $this->status_ids = $this->getStatusIds();
        }

        //return in_array($status_id, $this->status_ids);
        return isset($this->status_ids[$id]);
    }

    //----------------------------------------------------------------------------------------

    public function checkId(int $id): bool
    {
        // Already used
        if (!isset($this->request_ids[$this->status_id])) {
            $this->request_ids = [];
            $this->getIds();
        }

        return in_array($id, $this->request_ids[$this->status_id]);
    }

    //----------------------------------------------------------------------------------------

    public function setStatusId(int $id)
    {
        $this->status_id = $id;
    }
}
