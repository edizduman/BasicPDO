<?php
	/**
	 * Created by PhpStorm.
	 * User: ediz
	 * Date: 29.3.2015
	 * Time: 23:23
	 *
	 * Singleton PDO Sınıfı
	 * https://github.com/edizduman/BasicPDO
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
		private static $password = ''; // Put Your MySQL Password here
		/*** mysql password ***/
		private static $dbName = 'kys'; // Put Your MySQL Database name here


		/**
		 * SQL'in tutulduğu değişken
		 * @var
		 */
		private static $sql;

		/**
		 * WHERE'in tutulduğu değişken
		 * @var
		 */
		private static $whereValue = array ();

		/**
		 * WHERE değerlerinin tutulduğu değişken
		 * @var
		 */
		private static $where = false;


		/**
		 * Insert veya Update için set edilecek column
		 * @var
		 */

		private static $columns = false;

		/**
		 * Insert veya Update için prepare edilecek column değerleri
		 * @var
		 */
		private static $columnsValue = array ();


		/**
		 * LIMIT'in tutulduğu değişken
		 * @var
		 */
		private static $limit = false;

		private static $tableName;

		public static $db = NULL;

		/**
		 * Sınıf içerisinde tanımlı olamayan ve
		 * çağrılan tüm fonksiyonları PDO içersinden çağırır
		 */
		public static function __callStatic ($func,$args)
		{
			return call_user_func_array (array (self::getConnettion (),$func),$args);
		}


		/**
		 * Bir bağlantı mevcut ise o bağlantıyı döndür.
		 * Bağlantı Yoksa init'i tekrar tetikleyerek bağlantıyı başlatır
		 * Sadece run() fonksiyonunda kullanıyoruz.
		 *
		 */
		public static function getConnection ()
		{
			return self::$db == null ? self::init () : self::$db;
		}

		/**
		 * BasicPDO kurucu metodu
		 *
		 * @param $host
		 * @param $dbname
		 * @param $username
		 * @param $password
		 * @param string $charset
		 */

		private static function init ()
		{
			try {
				$dsn      = 'mysql:host=' . static::$hostname . ';dbname=' . static::$dbName;
				self::$db = new PDO($dsn,static::$username,static::$password);
				self::$db->setAttribute (PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
				self::$db->exec ('SET CHARACTER SET utf8');
				return self::$db;
			}
			catch (PDOException $e) {
				echo __LINE__ . $e->getMessage () . "Hata";
			}

		}

		public static function select ($_tableName)
		{
			self::$sql = "SELECT * FROM " . $_tableName;
		}

		public static function insert ($_tableName)
		{
			self::$sql = "INSERT INTO  " . $_tableName;
		}

		public static function update ($_tableName)
		{
			self::$sql = "UPDATE " . $_tableName;
		}

		public static function delete ($_tableName)
		{
			self::$sql = "DELETE FROM " . $_tableName;
		}

		public static function setColumns ($_columns,$_param = ',')
		{
			self::$columns      = " SET ";
			self::$columnsValue = array ();
			foreach ($_columns as $key => $val) {
				self::$columns .= ' ' . $key . ' = ? ' . $_param;
				self::$columnsValue[] = $val;
			}
			self::$columns = substr (self::$columns,0,-strlen ($_param));
		}


		public static function setWhere ($_where,$_param = 'and')
		{
			self::$where      = " WHERE ";
			self::$whereValue = array ();
			foreach ($_where as $key => $val) {
				self::$where .= ' ' . $key . ' = ? ' . $_param;
				self::$whereValue[] = $val;
			}
			self::$where = substr (self::$where,0,-strlen ($_param));
		}

		public static function setLimit ($limit)
		{
			self::$limit = "LIMIT " . $limit;
		}

		public static function setFrom ($from)
		{
			self::$sql = str_replace ('*',$from,self::$sql);
		}

		public static function done() {
			if (self::$where != false) {
				self::$sql .= self::$where;
				$query = self::getConnection ()->prepare (self::$sql);
				$query->execute (self::$whereValue);
				return true;
			} else {
				return false;
			}
		}
		public static function run ($single = false)
		{
			$value = array ();
			if (self::$columns != false) {
				self::$sql .= self::$columns;
				$value = self::$columnsValue;
			}

			if (self::$where != false) {
				self::$sql .= self::$where;
				$value = array_merge ($value,self::$whereValue);
			}

			echo self::$sql;

			$query = self::getConnection ()->prepare (self::$sql);
			$query->execute ($value);
			if (self::$columns == false) {
				self::$where = false;
				if ($single) {
					return $query->fetch ();
				} else {
					return $query->fetchAll ();
				}
			} else {
				self::$columns = false;
				self::$where   = false;
				return $query;
			}
		}


	}