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
     * Hatalı Kullanım ile sınıfın birden fazla türetilmemesi için _contruct _clone ve _ wakeup fonskyionları private etmemiz gerekiyor.
     * Sadece BasicPDO Sınıfı içerisinde kullanılabilir.
     */

    private function __construct() {

    }
    private function __clone() {

    }
    private function __wakeup() {

    }

    /**
     * SQL'in tutulduğu değişken
     * @var
     */
    private static $sql;

    /**
     * Table bilgisinin tutulduğu değişken
     * @var
     */
    private static $table;

    /**
     * JOIN sqlinin tutulduğu değişken
     * @var
     */
    private static $join = array();

    /**
     * WHERE'in tutulduğu değişken
     * @var
     */
    private static $whereValue = array();

    /**
     * WHERE değerlerinin tutulduğu değişken
     * @var
     */
    private static $where = false;


    /**
     * Insert veya Update için set edilecek sütunlar tutulur
     * @var
     */
    private static $columns = false;

    /**
     * Insert veya Update için kaydedilecek değerler tutulur
     * @var
     */
    private static $columnsValue = array();


    /**
     * LIMIT'in tutulduğu değişken
     * @var
     */
    private static $limit = false;

    /**
     * GROUP'in tutulduğu değişken
     * @var
     */
    private static $group = false;

    /**
     * ORDER'in tutulduğu değişken
     * @var
     */
    private static $order = false;

    /**
     * DB bağlantısının tutulduğu değişken
     * @var
     */
    public static $db = NULL;


    /**
     * Bir bağlantı mevcut ise o bağlantıyı döndür.
     * Bağlantı Yoksa init'i tekrar tetikleyerek bağlantıyı başlatır
     * Sadece run() fonksiyonunda kullanıyoruz.
     *
     */
    public static function getConnection()
    {
        return self::$db == null ? self::init() : self::$db;
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

    private static function init()
    {
        try {

            $dsn = 'mysql:host=' . Config::get('database/host') . ';dbname=' . Config::get('database/db');
            self::$db = new PDO($dsn, Config::get('database/username'), Config::get('database/password'));
            self::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            self::$db->exec('SET CHARACTER SET utf8');
            return self::$db;
        } catch (PDOException $e) {
            echo __LINE__ . $e->getMessage() . "Hata";
        }

    }

    public static function select($_tableName)
    {
        self::$sql = "SELECT * FROM " . $_tableName;
        self::$table = $_tableName;
    }

    public static function insert($_tableName)
    {
        self::$sql = "INSERT INTO  " . $_tableName;
        self::$table = $_tableName;
    }

    public static function update($_tableName)
    {
        self::$sql = "UPDATE " . $_tableName;
        self::$table = $_tableName;
    }

    public static function delete($_tableName)
    {
        self::$sql = "DELETE FROM " . $_tableName;
        self::$table = $_tableName;
    }

    public static function setColumns($_columns, $_param = ',')
    {
        self::$columns = " SET ";
        self::$columnsValue = array();
        foreach ($_columns as $key => $val) {
            self::$columns .= ' ' . $key . ' = ? ' . $_param;
            self::$columnsValue[] = $val;
        }
        self::$columns = substr(self::$columns, 0, -strlen($_param));
    }

    public static function setJoin($_target_table, $_target_field, $_source_field, $_type = "LEFT")
    {
        self::$sql .= " " . $_type . " JOIN " . $_target_table . " ON " . $_target_field . "=" . $_source_field;
    }


    public static function setWhere($_where, $_param = 'and')
    {
        self::$where = " WHERE ";
        self::$whereValue = array();
        foreach ($_where as $key => $val) {
            self::$where .= ' ' . self::$table.'.'.$key . ' = ? ' . $_param;
            self::$whereValue[] = $val;
        }
        self::$where = substr(self::$where, 0, -strlen($_param));
    }

    public static function setLimit($_limit)
    {
        self::$limit .= " LIMIT " . $_limit;
    }

    public static function setOrder($_field, $_order = "ASC")
    {
        self::$order .= " ORDER BY " . self::$table.'.'.$_field . " " . $_order;

    }

    public static function setGroupBy($_group)
    {
        self::$group .= " GROUP BY " . self::$table.'.'.$_group;
    }


    public static function setFrom($from)
    {
        self::$sql = str_replace('*', $from, self::$sql);
    }

    /*
     * Delete İşlemi final fonksiyonu
     */
    public static function done()
    {
        if (self::$where != false) {
            self::$sql .= self::$where;

            $query = self::getConnection()->prepare(self::$sql);
            $query->execute(self::$whereValue);
            return true;
        } else {
            return false;
        }
    }


    /*
     * Insert/Update/Select İşlemleri final fonksiyonu
     */
    public static function run($single = false)
    {
        $value = array();
        if (self::$columns != false) {
            self::$sql .= self::$columns;
            $value = self::$columnsValue;
        }

        if (self::$join != false) {
            self::$sql .= implode(' ', self::$join);
            unset(self::$join);
        }

        if (self::$where != false) {
            self::$sql .= self::$where;
            $value = array_merge($value, self::$whereValue);
            self::$where = false;
        }
        if (self::$group != false) {
            self::$sql .= self::$group;
            self::$group = false;
        }

        if (self::$order != false) {
            self::$sql .= self::$order;
            self::$order = false;
        }

        if (self::$limit != false) {
            self::$sql .= self::$limit;
            self::$limit = false;
        }

        echo self::$sql;
        $query = self::getConnection()->prepare(self::$sql);
        $query->execute($value);

        if (self::$columns == false) { //columns değişkenine bir veri set edilmediyse  select  işlemidir. dönüş fetch olmalıdır
            if ($single) {
                return $query->fetch(PDO::FETCH_ASSOC);
            } else {
                return $query->fetchAll(PDO::FETCH_ASSOC);
            }
        } else {
            self::$columns = false;
            return $query;
        }
    }

    /**
     * Sınıf içerisinde tanımlı olamayan ve
     * çağrılan tüm fonksiyonları PDO içersinden çağırır
     */
    public static function __callStatic($func, $args)
    {
        return call_user_func_array(array(self::getConnection(), $func), $args);
    }


}
