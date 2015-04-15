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
	use Config\Config as Config;


	class BasicPDO
	{


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

		/**
		 * LIMIT'in tutulduğu değişken
		 * @var
		 */
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

				$dsn      = 'mysql:host=' . Config::get('database/host'). ';dbname=' . Config::get('database/db');
				self::$db = new PDO($dsn,Config::get('database/username'),Config::get('database/password'));
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

		public static function setLimit ($_limit)
		{
			self::$sql.= "LIMIT " . $_limit;
		}

		public static function setOrder ($_order)
		{
			self::$sql.= "ORDER BY " . $_order;
		}

		public static function setGroupBy ($_group)
		{
			self::$sql.= "GROUP BY " . $_group;
		}


		public static function setFrom ($from)
		{
			self::$sql = str_replace ('*',$from,self::$sql);
		}

		/*
		 * Delete İşlemi final fonksiyonu
		 */
		public static function done() {
			if (self::$where != false) {
				self::$sql .= self::$where;

				if (self::s)

				$query = self::getConnection ()->prepare (self::$sql);
				$query->execute (self::$whereValue);
				return true;
			} else {
				return false;
			}
		}

		/*
		 * Insert/Update/Select İşlemleri final fonksiyonu
		 */
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
				self::$where = false;
			}

			$query = self::getConnection ()->prepare (self::$sql);
			$query->execute ($value);
			if (self::$columns == false) { //columns değişkenine bir veri set edimediyse  select  işlemidir. dönüş fetch olmalıdır
				if ($single) {
					return $query->fetch (PDO::FETCH_ASSOC);
				} else {
					return $query->fetchAll (PDO::FETCH_ASSOC);
				}
			} else {
				self::$columns = false;
				return $query;
			}
		}


	}
