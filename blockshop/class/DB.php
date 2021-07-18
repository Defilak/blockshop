<?php

//namespace Database;
//use PDO;

class DB
{
    private static $pdo;
    public static $params = null;

    public static function prepare($query)
    {
        $pdo = self::getConnection();
        return $pdo->prepare($query);
    }

    public static function insert($query)
    {
        $pdo = self::getConnection();
        return $pdo->insert($query);
    }

    public static function update($query)
    {
        $pdo = self::getConnection();
        return $pdo->update($query);
    }

    public static function select($query)
    {
        $pdo = self::getConnection();
        return $pdo->select($query);
    }

    public function query(string $sql, $params = []): ?array
    {
        $pdo = self::getConnection();
        $sth = $pdo->prepare($sql);
        $result = $sth->execute($params);

        if (false === $result) {
            return null;
        }

        return $sth->fetchAll();
    }

    public static function getConnection()
    {
        if (isset(self::$params) && !isset(self::$pdo)) {
            $args = self::$params;
            self::$pdo = new PDO("mysql:host={$args['host']}:{$args['port']};dbname={$args['db_name']}", $args['user'], $args['password']);
            self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING); //спавнить предупреждения
            self::$pdo->exec('SET NAMES UTF8');
        }

        return self::$pdo;
    }

    public static function initParams($params)
    {
        if (!isset(self::$params)) {
            self::$params = $params;
        }
    }
}
