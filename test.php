<?php
/**
 * Created by PhpStorm.
 * User: ediz
 * Date: 29.3.2015
 * Time: 23:50
 */

include "BasicPDO.php";

use DB\BasicPDO as DB;

DB::select("users");
$result = DB::run();
var_dump($result);

