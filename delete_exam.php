<?php
/**
 * Created by PhpStorm.
 * User: edizduman
 * Date: 26.6.2015
 * Time: 16:48
 */

include "Config.php";
include "BasicPDO.php";

\Config\Config::readConfig();
use DB\BasicPDO as DB;

DB::delete("users");
DB::setWhere(array('id'=>4));
DB::done();