<?php

/**
 * Это банлист не серверный. 
 * Здесь только одна запись на одного игрока.
 */
class Banlist extends ActiveRecordEntity
{
    protected $name;
    protected $reason;
    protected $admin;
    protected $time;

    public static function getTableName(): string
    {
        return 'banlist';
    }
}
