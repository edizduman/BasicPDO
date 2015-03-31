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
    private static $password = 'root'; // Put Your MySQL Password here
    /*** mysql password ***/
    private static $dbName = 'kys'; // Put Your MySQL Database name here


    /**
     * SQL'in tutulduğu değişken
     * @var
     */
    private static $sql;

    private static $tableName;

    public static $db = NULL;

    /**
     * Sınıf içerisinde tanımlı olamayan ve
     * çağrılan tüm fonksiyonları PDO içersinden çağırır
     */
    public static function __callStatic($func, $args)
    {
        return call_user_func_array(
            array(self::getConnettion(), $func),
            $args
        );
    }


    /**
     * Bir bağlantı mevcut ise o bağlantıyı döndür.
     * Bağlantı Yoksa init'i tekrar tetikleyerek bağlantıyı başlatır
     * Sadece run() fonksiyonunda kullanıyoruz.
     *
     */
    public static function getConnection()
    {
        return
            self::$db == null ?
                self::init() :
                self::$db;
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
            $dsn = 'mysql:host=' . static::$hostname . ';dbname=' . static::$dbName;
            self::$db = new PDO($dsn, static::$username, static::$password);
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

    }


    public static function run($single = false)
    {
        $query = self::getConnection()->query(self::$sql);
        if ($single)
            return $query->fetch();
        else
            return $query->fetchAll();
    }


    /*public static function errormessage(PDOException $ex) {
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
    }*/

}