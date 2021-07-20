<?php


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

    private function camelCaseToUnderscore(string $source): string
    {
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $source));
    }

    private function mapPropertiesToDbFormat(): array
    {
        $reflector = new \ReflectionObject($this);
        $properties = $reflector->getProperties();

        $mappedProperties = [];
        foreach ($properties as $property) {
            $propertyName = $property->getName();
            $propertyNameAsUnderscore = $this->camelCaseToUnderscore($propertyName);
            $mappedProperties[$propertyNameAsUnderscore] = $this->$propertyName;
        }

        return $mappedProperties;
    }

    public function save(): void
    {
        $mappedProperties = $this->mapPropertiesToDbFormat();
        if ($this->id !== null) {
            $this->update($mappedProperties);
        } else {
            $this->insert($mappedProperties);
        }
    }

    private function update(array $mappedProperties): void
    {
        $columns2params = [];
        $params2values = [];
        $index = 1;
        foreach ($mappedProperties as $column => $value) {
            $param = ':param' . $index; // :param1
            $columns2params[] = $column . ' = ' . $param; // column1 = :param1
            $params2values[$param] = $value; // [:param1 => value1]
            $index++;
        }
        $sql = 'UPDATE ' . static::getTableName() . ' SET ' . implode(', ', $columns2params) . ' WHERE id = ' . $this->id;
        DB::query($sql, $params2values);
    }

    private function insert(array $mappedProperties): void
    {
        //здесь мы создаём новую запись в базе
    }

    /**
     * @return static[]
     */
    public static function findAll(): array
    {
        $stmt = DB::prepare('SELECT * FROM `' . static::getTableName() . '`;');
        return $stmt->execute();
    }

    /**
     * @param int $id
     * @return static|null
     */
    public static function getById(int $id): ?self
    {
        $entities = DB::query(
            'SELECT * FROM `' . static::getTableName() . '` WHERE id=:id;',
            [
                ':id' => $id
            ],
            static::class
        );
        return $entities ? $entities[0] : null;
    }

    public static function where($column, $value)
    {
        $entities = DB::query('SELECT * FROM `' . static::getTableName() . "` WHERE $column=:val;", [
            'val' => $value
        ], static::class);
        return $entities ? $entities[0] : null;
    }

    /**
     * Возвращает все результаты.
     *
     * @return void
     */
    public static function whereAll($array): array
    {
        //Это проверка ассоциативный массив или не.
        if (array_keys($array) !== range(0, count($array) - 1)) {
            $columns2params = [];
            $params2values = [];
            $index = 1;
            foreach ($array as $column => $value) {
                $param = ':param' . $index; // :param1
                $columns2params[] = $column . ' = ' . $param; // column1 = :param1
                $params2values[$param] = $value; // [:param1 => value1]
                $index++;
            }
            $query = "SELECT * FROM `" . static::getTableName() . "` WHERE " . implode(' AND ', $columns2params);
            $entities = DB::query($query, $params2values, static::class);
        } else {
            //TODO: laravel-style args
            $args = [];
            foreach ($array as $entry) {
                $args[] = $entry[0] . ' ' . $entry[1] . ' ' . $entry[2];
            }

            $query = "SELECT * FROM `" . static::getTableName() . "` WHERE " . implode(' AND ', $args);
            //$entities = DB::query($query, $params2values);//todo
        }

        return $entities;
    }

    /**
     * Возвращает первый результат либо null.
     *
     * @param [type] $array
     * @return void
     */
    public static function whereFirst($array)
    {
        $entities = static::whereAll($array);
        return $entities ? $entities[0] : null;
    }

    abstract protected static function getTableName(): string;
}
