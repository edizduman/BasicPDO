<?php
/**
 * Created by PhpStorm.
 * User: edizduman
 * Date: 26.6.2015
 * Time: 16:53
 */

include "Config.php";
include "BasicPDO.php";

\Config\Config::readConfig();
use DB\BasicPDO as DB;

DB::update("users");
DB::setColumns(array("name"=>"ediz","surname"=>"duman"));
DB::setWhere(array("id"=>"1"));
DB::run();