<?php
/**
 * Schrijf database gegevens naar bestanden
 */

namespace TwoSolar;

require_once "../settings.php";

use TwoSolar\Database\DataBridge;
use TwoSolar\Database\DataFileService;
use TwoSolar\Database\DataMysql;

require_once "../vendor/autoload.php";

$database = new DataBridge(new DataMysql());


$status_ids = $database->getStatusIds();

foreach ($status_ids as $status_id => $arr) {
    $database->setStatusId($arr['id']);
    $id_list =  $database->getIds(true);

    $file = "../data/requests_".$arr['id']."_".$arr['status'];

    foreach ($id_list as $rid) {
        file_put_contents($file, $rid."\n", FILE_APPEND);
    }
}
