<?php

class ShopProduct extends ActiveRecordEntity
{
    public $image;
    public $block_id;
    public $amount;
    public $price;
    public $realprice;
    public $name;
    public $info;
    public $action;
    public $server;
    public $category;

    public static function getTableName(): string
    {
        return 'sale';
    }

    public static function getServerCategory($server, $category, $order_by = 'id'): array|ShopProduct
    {
        $table_name = self::getTableName();

        $stmt = DB::prepare("SELECT * FROM {$table_name} WHERE server = :server and category LIKE :category ORDER BY {$order_by}");
        $stmt->bindValue(':server', $server);
        $stmt->bindValue(':category', $category);
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_CLASS, self::class);
    }
}
