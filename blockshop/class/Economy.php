<?php

class Economy extends ActiveRecordEntity
{
    public $username;
    public $balance;
    public $money;
    public $group;
    public $bancount;
    public $buys;

    public static function getTableName(): string
    {
        return 'iconomy';
    }
}
