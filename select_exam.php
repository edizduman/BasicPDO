<?php
/**
 * Created by PhpStorm.
 * User: edizduman
 * Date: 19.6.2015
 * Time: 13:15
 */
include "Config.php";
include "BasicPDO.php";

\Config\Config::readConfig();
use DB\BasicPDO as DB;

DB::select ('users');
DB::setFrom('username');
DB::setWhere(array('id' => 1));
DB::setOrder('id');
DB::setLimit('0,2');
$result = DB::run (false);
var_dump($result);