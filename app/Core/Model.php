<?php

namespace Core;

/**
 * Class Model
 */
class Model implements DbModelInterface
{
    /**
     * @var
     */
    protected $table_name;
    /**
     * @var
     */
    protected $id_column;
    /**
     * @var array
     */
    protected $columns = [];
    /**
     * @var
     */
    protected $collection;
    /**
     * @var
     */
    protected $sql;
    /**
     * @var array
     */
    protected $params = [];

    /**
     * @return $this
     */
    public function initCollection()
    {
        $columns = implode(',', $this->getColumns());
        $this->sql = "select $columns from " . $this->table_name;
        return $this;
    }

    /**
     * @return array
     */
    public function getColumns()
    {
        $db = new DB();
        $sql = "show columns from  $this->table_name;";
        $results = $db->query($sql);
        foreach ($results as $result) {
            array_push($this->columns, $result['Field']);
        }
        return $this->columns;
    }


    /**
     * @param $params
     * @return $this
     */
    public function sort($params)
    {
        $sqlByOrderParams = [];

        foreach ($params as $field => $typeSort) {
            $sqlByOrderParams[] = "{$field} {$typeSort}";
        }

        if (count($sqlByOrderParams) > 0) {
            $this->sql = $this->sql . ' order by ' . implode(',', $sqlByOrderParams);
        }


        return $this;
    }

    /**
     * @param $params
     */
    public function filter($params)
    {
    }

    /**
     * @return $this
     */
    public function getCollection()
    {
        $db = new DB();
        $this->sql .= ";";
        $this->collection = $db->query($this->sql, $this->params);
        return $this;
    }

    /**
     * @return mixed
     */
    public function select()
    {
        return $this->collection;
    }

    /**
     * @return null
     */
    public function selectFirst()
    {
        return isset($this->collection[0]) ? $this->collection[0] : null;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getItem($id)
    {
        $sql = "select * from $this->table_name where $this->id_column = ? limit 1;";
        $db = new DB();
        $params = array($id);
        $result = $db->query($sql, $params);

        if ($result) {
            return $result[0];
        } else {
            return 0;
        }
    }

    public function updateItem($values, $columns, $id)
    {
        $params = [];
        $sql = "update $this->table_name set ";

        foreach ($values as $key => $value) {
            foreach ($columns as $index => $column) {
                if ($key === $column) {
                    $sql .= "{$column} = :{$column},";
                    $params[":{$column}"] = $value;
                }
            }
        }

        $sql = trim($sql, ',') . " where id = :id";
        $params[':id'] = $id;

        $db = new DB();
        $db->query($sql, $params);
    }

    /**
     * @return array
     */
    public function getPostValues()
    {
        $values = [];
        $columns = $this->getColumns();
        foreach ($columns as $column) {
            /*
            if ( isset($_POST[$column]) && $column !== $this->id_column ) {
                $values[$column] = $_POST[$column];
            }
             * 
             */
            $column_value = filter_input(INPUT_POST, $column);
            if ($column_value && $column !== $this->id_column) {
                $values[$column] = $column_value;
            }
        }

        return $values;
    }

    public function getTableName(): string
    {
        return $this->table_name;
    }

    public function getPrimaryKeyName(): string
    {
        return $this->id_column;
    }

    public function getId()
    {
        return 1;
    }
}
