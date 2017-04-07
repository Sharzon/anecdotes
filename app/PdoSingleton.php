<?php

namespace Sharzon\Anecdotes;

use PDO;

class PdoSingleton
{
    private static $_instance;

    private static $host = 'localhost';
    private static $user = 'root';
    private static $password = 'manman';
    private static $db_name = 'anecdotes';
    private static $charset = 'utf8';

    private static $opt = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];

    public static function get()
    {
        if (self::$_instance === null) {
            $dsn = "mysql:host=".self::$host.
                   ";dbname=".self::$db_name.
                   ";charset=".self::$charset;
            self::$_instance = new PDO(
                $dsn,
                self::$user,
                self::$password,
                self::$opt
            );
        }

        return self::$_instance;
    }
}
