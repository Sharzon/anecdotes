<?php

namespace App;

use PDO;

class PdoFactory
{
    protected static $host = 'localhost';
    protected static $user = 'root';
    protected static $password = 'manman';
    protected static $db_name = 'anecdotes';
    protected static $charset = 'utf8';

    protected static $opt = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];

    public static function create()
    {
        $dsn = "mysql:host=".self::$host.
               ";dbname=".self::$db_name.
               ";charset=".self::$charset;
        return new PDO(
            $dsn,
            self::$user,
            self::$password,
            self::$opt
        );
    }
}
