<?php
/**
 * Created by PhpStorm.
 * User: APG
 * Date: 27.12.2019
 * Time: 16:08
 */
namespace nic;
/**
 * @return resource
 */
function getConn()
{
    $host = HOST ?? null;
    $dbName = DB_NAME ?? null;
    $user = DB_USER ?? null;
    $pass = DB_PASS ?? null;

    if ($host == null || $dbName == null || $user == null || $pass == null) {
        $pdo = null;
    } else {
        $dsn = 'mysql:host=' . HOST . ';dbname=' . DB_NAME;
        $opt = [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
            \PDO::MYSQL_ATTR_INIT_COMMAND => "SET time_zone = '+02:00'",
        ];
        $pdo = new \PDO($dsn, DB_USER, DB_PASS, $opt);
    }
    return $pdo;
}