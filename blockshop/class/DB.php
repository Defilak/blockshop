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
        return $pdo->query($query);
    }

    public static function query(string $sql, array $params = [], string $className = 'stdClass'): ?array
    {
        $sth = DB::prepare($sql);
        $result = $sth->execute($params);

        if (false === $result) {
            return null;
        }

        return $sth->fetchAll(\PDO::FETCH_CLASS, $className);
    }

    public static function getConnection()
    {
        if (!isset(self::$pdo)) {

            $args = config('database');
            self::$pdo = new PDO("mysql:host={$args['host']}:{$args['port']};dbname={$args['db_name']}", $args['username'], $args['password']);

            if($args['show_errors']) {
                self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING); //спавнить предупреждения
            }
            self::$pdo->exec("SET NAMES {$args['charset']}");
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
