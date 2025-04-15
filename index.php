<?php
/**
 *  _____  _    _ _   _            _____ _____
 * |  __ \| |  | | \ | |     /\   |  __ \_   _|
 * | |__) | |  | |  \| |    /  \  | |__) || |
 * |  _  /| |  | | . ` |   / /\ \ |  ___/ | |
 * | | \ \| |__| | |\  |  / ____ \| |    _| |_
 * |_|  \_\\____/|_| \_| /_/    \_\_|   |_____|
 *
 * Call via cronjob
 *
 * Exp.
 *
 * /usr/local/php80/bin/php80 -q /home/zonnepanel/domains/api.zonnepanelen.io/api3/index.php >/dev/null 2>&1
 *
 */
namespace TwoSolar;

use TwoSolar\Wrapper;

require_once "settings.php";
require_once "vendor/autoload.php";

$wrapper = new Wrapper();
$invoker = $wrapper->createInvoker();
