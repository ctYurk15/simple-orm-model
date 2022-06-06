<?php

namespace ctyurk15\SimpleOrmModel;

class Model
{
    public static $table;
    public static $index_column;
    public static $conn;
    public static $dbdata_path;

    public $index_value;

    protected $values;
    protected $changed_values;

    public static function init_conn($host, $user, $pass, $dbname)
    {
        static::$conn = new \mysqli($host, $user, $pass, $dbname);
    }

    public static function all($where_clause = '')
    {
        $records = [];
        $class_name = static::class;

        //if(static::$conn == null) static::init_conn();

        $query = 'SELECT * FROM '.static::$table.'';
        $query .= ($where_clause == '') ? '' : ' WHERE '.$where_clause;

        $records_data = static::$conn->query($query);
        while($record_data = $records_data->fetch_array())
        {
            $records[] = new $class_name($record_data[static::$index_column], $record_data);
        }

        return $records;
    }

    public static function createFromarray($values)
    {
        //if(static::$conn == null) static::init_conn();
        $record_values = $values;

        $columns = implode(', ', array_map(function($column){
            return ''.$column.'';
        }, array_keys($values)));

        $values = implode(', ', array_map(function($value){
            if($value == 'NULL')
                return $value;
            else
                return '"'.$value.'"';
        },array_values($values)));

        $query = ' INSERT INTO '.static::$table.'('.$columns.') 
         VALUES ('.$values.')';
        static::$conn->query($query);

        $class_name = static::class;
        $record = new $class_name($record_values[static::$index_column], $record_values);
        return $record;
    }

    public function __construct($index_value, $values=[])
    {
        $this->index_value = $index_value;
        $this->values = $values;

        foreach($values as $column => $value)
        {
            if(!is_numeric($column))
            {
                $this->$column = $value;
            }
        }

        //if(static::$conn == null) static::init_conn();
    }

    public function getId()
    {
        return $this->index_value;
    }

    public function get($column)
    {
        if($column == static::$index_column)
        {
            return $this->index_value;
        }
        else if(isset($this->values[$column]))
        {
            return $this->values[$column];
        }
        else
        {
            $query = 'SELECT '.$column.' FROM '.static::$table.' WHERE '.static::$index_column.'='.$this->index_value;
            $data = static::$conn->query($query);
            $result = $data->fetch_array()[$column];
            $this->values[$column] = $result;
            return $result;
        }
    }

    public function set($column, $new_value)
    {
        $this->values[$column] = $new_value;
        $this->changed_values[$column] = $new_value;
    }

    public function save()
    {
        if($this->changed_values != null)
        {
            $query = 'UPDATE '.static::$table.' SET ';
            foreach ($this->changed_values as $column => $value)
            {
                $query .= ''.$column.'="'.$value.'" ';
            }
            $query .= ' WHERE '.static::$index_column.'='.$this->index_value;
            static::$conn->query($query);
        }
    }

    public function delete()
    {
        $query = 'DELETE FROM `'.static::$table.'` WHERE `'.static::$index_column.'` = '.$this->getId();
        static::$conn->query($query);
    }

}
