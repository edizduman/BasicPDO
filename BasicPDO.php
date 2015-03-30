<?php
/**
 * Created by PhpStorm.
 * User: ediz
 * Date: 29.3.2015
 * Time: 23:23
 *
 * Singleton PDO Sınıfı
 */

namespace DB;

use PDO;


class BasicPDO
{

	/*** mysql hostname ***/
	private static $hostname = 'localhost'; // Put your host name here
	/*** mysql username ***/
	private static $username = 'root'; // Put your MySQL User name here
	/*** mysql password ***/
	private  static$password = 'root'; // Put Your MySQL Password here
	/*** mysql password ***/
	private  static $dbName = 'kys'; // Put Your MySQL Database name here


	/**
	 * SQL'in tutulduğu değişken
	 * @var
	 */
	private static $sql;

	private static $tableName;

	public static $dbHandler = NULL;



	/**
	 * BasicPDO kurucu metodu
	 *
	 * @param $host
	 * @param $dbname
	 * @param $username
	 * @param $password
	 * @param string $charset
	 */
	private function __construct()
	{
		try
		{
			$dsn = 'mysql:host='.static::$hostname.';dbname='.static::$dbName;
			self::$dbHandler = new PDO($dsn , static::$username,static::$password);
			self::$dbHandler->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			self::$dbHandler->exec('SET CHARACTER SET utf8');
		}
		catch(PDOException $e)
		{
			echo __LINE__.$e->getMessage();
		}
	}

	public static function select($_tableName)
	{
		self::$sql = "SELECT * FROM ".$_tableName;
		self::$dbHandler->query(self::$sql);

	}

	public static function errormessage(PDOException $ex) {
		$kodeerror  = sprintf("%0.0f", strstr($ex->getMessage(), '1'));
		$regex      = preg_replace("#[^\w()/.%\-&/']#", " ", $ex->getMessage());
		$kesalahan  = substr($regex, strpos($regex, '1') + strlen($ex->getCode()));
		switch ($ex->getCode()) {
			case '1045':
			case '1049':
				echo "<div class='container'><h1>Terjadi Kesalahan Database</h1>
                      <p>Kode Error : " . $kodeerror . "</p>
                      <p>Kesalahan : " . $kesalahan . "</p>
                      <p>Nama File : " . $ex->getFile() . "</p>
                      <p>Baris : " . $ex->getLine() . "</p></div>";
				break;
			case '42S02':
			case '42S22':
			case '42000':
			case '23000':
				echo "<div class='container'><h1>Terjadi Kesalahan Database</h1>
                      <p>Kode Error : " . $kodeerror . "</p>
                      <p>Kesalahan : " . $kesalahan . "</p>
                      <p>Nama File : " . $ex->getTrace()[1]['file'] . "</p>
                      <p>Baris : " . $ex->getTrace()[1]['line'] . "</p></div>";
				break;
		}
	}

}