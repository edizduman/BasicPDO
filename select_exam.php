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


/**
 * Sınıfa ait fonksiyonlar ile kullanım
 */
DB::select ('users');
DB::setFrom('username');
DB::setJoin('members','members.id','users.member_id','left');
DB::setWhere(array('id' => 1));
DB::setOrder('id');
DB::setLimit('0,2');
$result = DB::run (false);
var_dump($result);

/**
 * Sınıf üzerinden PDO prepare ile kullanım
 */
$bing = DB::getConnection()->prepare("select * from users where id = :id");
$bing->execute(array(':id'=> '1'));


/**
 * Sınıf üzerinden PDO query ile kullanımlar
 */

$single=DB::getConnection()->query("select * from users where id='1'")->fetch();
var_dump($single);

$multi = DB::getConnection()->query("select * from users")->fetchAll();
var_dump($multi);
