<?php
/**
 * Importeer setttings uit oude filebases setup naar database
 */

namespace TwoSolar;

require_once "../settings.php";

use TwoSolar\Database\DataBridge;
# use TwoSolar\Database\DataFileService;
use TwoSolar\Database\DataMysql;

require_once "../vendor/autoload.php";

$database = new DataBridge(new DataMysql());

$status_ids = $database->getStatusIds();

foreach ($status_ids as $status_id => $arr) {
    $database->setStatusId($arr['id']);

    $file = "../../api/data/requests_".$status_id;

    if (file_exists($file)) {
        $request_ids = file($file);
        //echo $status_id."\t\t".count($request_ids)."\n";
    }

    foreach ($request_ids as $rid) {
        if (!$database->checkId($rid)) {
            $database->setId($rid);
            echo "SET\t\t". $arr['id'] ."-". $status_id ."\t\t". $rid;
        }
    }
}
