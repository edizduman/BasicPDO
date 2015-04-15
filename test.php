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

	DB::select ('departments');
	DB::setFrom('department');
	DB::setWhere(array('department' => 'İdari İşler'));
	DB::setOrder('department');
	DB::setLimit('0,2');

	$s = DB::run (false);

	var_dump($s);



	/*DB::update("users");
	DB::setColumns(array("username"=>"ediz"));
	DB::setWhere(array("id"=>"1"));
	DB::run();



	DB::delete("users");
	DB::setWhere(array('id'=>4));
	DB::done();
*/

