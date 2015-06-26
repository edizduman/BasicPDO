<?php
/**
 * Created by PhpStorm.
 * User: edizduman
 * Date: 26.6.2015
 * Time: 16:47
 */
include "Config.php";
include "BasicPDO.php";

\Config\Config::readConfig();
use DB\BasicPDO as DB;

DB::insert("users");
DB::setColumns(array('name'=>'Ediz','surname'=>'DUMAN'));
DB::run();