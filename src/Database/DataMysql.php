<?php

namespace TwoSolar\Database;

use TwoSolar\Database\DataHandler;

class DataMysql implements DataHandler
{
    /**
     *  CONST MYSQL_SERVER
     *
     *  mysql server
     *  @var string
     */

    /**
     *  CONST MYSQL_DB
     *
     *  mysql database
     *  @var string
     */

    /**
     *  CONST MYSQL_USER
     *
     *  mysql username
     *  @var string
     */

    /**
     *  CONST MYSQL_PASS
     *
     *  mysql password
     *  @var string
     */


    private $mysqi;

    private int $status_id;

    private array $status_ids = [];

    private array $request_ids = [];

    public function __construct()
    {
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        $this->mysqli = new \mysqli(MYSQL_SERVER, MYSQL_USER, MYSQL_PASS, MYSQL_DB);
        $this->mysqli->set_charset('utf8');
    }

    //----------------------------------------------------------------------------------------

    /**
     * Write id to database
     * @param int $id
     * @return bool
     */
    public function setId(int $id): bool
    {
        $query = "INSERT INTO handled " .
                 "(`request_id`, `status_id`) " .
                 "VALUES ('".$id."', '".$this->status_id."')";

        if ($stmt = $this->mysqli->prepare($query)) {
            /* execute statement */
            $stmt->execute();
            /* close statement */
            $stmt->close();

            return is_numeric($this->mysqli->insert_id);
        }

        return false;
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
                 "WHERE status_id = '".$this->status_id."' " .
                 "AND created > DATE_SUB(CURDATE(), INTERVAL 1 YEAR) " .
                 "ORDER BY created";

        if ($stmt = $this->mysqli->prepare($query)) {
            /* execute statement */
            $stmt->execute();
            /* bind result variables */
            $stmt->bind_result($request_id);

            $this->request_ids[$this->status_id] = [];
            /* fetch values */
            while ($stmt->fetch()) {
                $this->request_ids[$this->status_id][] = $request_id;
            }

            /* close statement */
            $stmt->close();
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
    public function statusSelectQuery(array $cols = []):array
    {
        if (empty($cols)) {
            $cols = ['id', 'status','description','page_id','get_quote','delay','mail_handler','options'];
            $query = "SELECT ".implode(",", $cols)." FROM status WHERE active = 1";
        } else {
            $query = "SELECT ".implode(",", $cols)." FROM status WHERE active = 1";
        }
        $keys = $cols;


        $result = [];
        if ($stmt = $this->mysqli->prepare($query)) {
            /* execute statement */
            $stmt->execute();
            /* bind result variables */
            $stmt->bind_result(...$cols);
            //$stmt->bind_result($status_id);

            /* fetch values */
            while ($stmt->fetch()) {
                $arr = [];
                foreach ($cols as $key => $name) {
                    if ($keys[$key] == 'delay') {
                        $name = $name + DEBUG_BACK_IN_TIME;
                    }
                    $arr[$keys[$key]] = $name;
                }
                $result[] = $arr;
            }

            /* close statement */
            $stmt->close();
        }

        return $result;
    }

    //----------------------------------------------------------------------------------------

    /**
     * Get page_id for HTML grabber
     * @param int $id id van de status in behandeling (niet de status_id zelf)
     * @return int | null
     */
    public function getPageId(int $id): int | null
    {
        $result = $this->statusSelectQuery(['id','status', 'page_id']);

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
        $result = $this->statusSelectQuery(['id','status','mail_handler']);

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
    public function getStatusIds():array
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
