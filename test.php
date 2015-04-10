<?php
	/**
	 * Created by PhpStorm.
	 * User: ediz
	 * Date: 29.3.2015
	 * Time: 23:50
	 */

	include "BasicPDO.php";

	use DB\BasicPDO as DB;



/*
	DB::insert("users");
	DB::setColumns(array('username'=>'aa','password'=>'aa'));
	DB::run();

	DB::select ("users");
	DB::setFrom ('username,password');
	DB::setWhere(array('username'=>'c' ,'password'=>'c'));
	DB::run ();

	DB::update("users");
	DB::setColumns(array("username"=>"ediz"));
	DB::setWhere(array("id"=>"1"));
	DB::run();
*/


	DB::delete("users");
	DB::setWhere(array('id'=>4));
	DB::done();


