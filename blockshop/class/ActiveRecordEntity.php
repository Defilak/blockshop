<?php

use DB;

/**
 * Pattern: Active Record.
 */
abstract class ActiveRecordEntity
{
    protected $id;

    public function getId()
    {
        return $this->id;
    }

    public function __set(string $name, $value)
    {
        $camelCaseName = $this->underscoreToCamelCase($name);
        $this->$camelCaseName = $value;
    }

    private function underscoreToCamelCase(string $source): string
    {
        return lcfirst(str_replace('_', '', ucwords($source, '_')));
    }

    /**
     * @return static[]
     */
    public static function findAll(): array
    {
        $stmt = DB::prepare('SELECT * FROM `' . static::getTableName() . '`;');
        return $stmt->execute();
    }

    abstract protected static function getTableName(): string;
}
