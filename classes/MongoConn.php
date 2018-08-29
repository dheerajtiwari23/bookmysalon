<?php
include_once("config/config.php");
class MongoConn {

    static private $_conn = null;
    private function __construct() {
        
    }

    private function __clone() {
        
    }

    static public function getConnection() {
        if (self::$_conn == null) {
            try {
                self::$_conn = new MongoDB\Driver\Manager('mongodb://'.M_USER.':'.M_PASS.'@'.M_HOST.':27017/'.M_NAME.'');
            } catch (Exception $e) {
                //echo $e->getMessage();
                die('<h1>Sorry. The Database connection is temporarily unavailable.</h1>');
            }
            return self::$_conn;
        } else {
            return self::$_conn;
        }
    }

}
