<?php
	header('Content-Type: text/html; charset=utf-8');
	/**
	 * Created by PhpStorm.
	 * User: ediz
	 * Date: 29.3.2015
	 * Time: 23:50
	 */
	include "Config.php";
	include "BasicPDO.php";

	\Config\Config::readConfig();
	use DB\BasicPDO as DB;

/*	DB::insert("users");
	DB::setColumns(array('username'=>'aa','password'=>'aa'));
	DB::run(); */

	//DB::select ('departments');
	//DB::setFrom('department');
	//DB::setWhere(array('department' => 'İdari İşler'));
	//DB::setOrder('department');
	//DB::setLimit('0,2');

	$single=DB::getConnection()->query("select * from users where id='1'")->fetch();
	var_dump($single);


	$multiresult = DB::getConnection()->query("select * from users")->fetchAll();
	var_dump($multiresult);

	$bing = DB::getConnection()->prepare("select * from users where id = :id");
    $bing->execute(array(':id'=> '1'));

	var_dump($bing->fetchAll());
	//$s = DB::run (false);
	//var_dump($s);



	/*DB::update("users");
	DB::setColumns(array("username"=>"ediz"));
	DB::setWhere(array("id"=>"1"));
	DB::run();



	DB::delete("users");
	DB::setWhere(array('id'=>4));
	DB::done();
*/

