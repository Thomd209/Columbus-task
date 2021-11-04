<?php
declare(strict_types=1);

class Db 
{
    const DSN = 'mysql:dbname=columbus_task;host=127.0.0.1';
    const USER = 'root';
    const PASSWORD = 'redFish99';
    const OPTIONS = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false
    ];

    public static function getConn(): object
    {
        $pdo = new PDO(self::DSN, self::USER, self::PASSWORD ,
        self::OPTIONS);

        return $pdo;
    }
}