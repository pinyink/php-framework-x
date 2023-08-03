<?php

namespace Acme\Todo\Core;

use React\MySQL\ConnectionInterface;
use React\MySQL\QueryResult;
use function React\Async\await;

class Model 
{
    protected $db;
    /**
     * Name of database table
     *
     * @var string
     */
    protected $table;

    /**
     * The table's primary key.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    public function __construct(ConnectionInterface $db)
    {
        $this->db = $db;
    }

    public function query(string $query, array $params)
    {
        $result = await($this->db->query(
            $query, $params
        ));
        assert($result instanceof QueryResult);
        if (count($result->resultRows) === 0) {
            return null;
        }
        return $result->resultRows;
    }

    public function exists(array $where) 
    {
        $query = 'SELECT count('.$this->primaryKey.') count FROM '.$this->table;
        $paramValue = [];
        if (!empty($where)) {
            $query .= " WHERE 1=1 ";
            foreach ($where as $key => $value) {
                $query .= "AND {$key} = ? ";
                array_push($paramValue, $value);
            }
        }
        $result = await($this->db->query(
            $query, $paramValue
        ));
        assert($result instanceof QueryResult);
        if (count($result->resultRows) === 0) {
            return null;
        }
        $queryResult = $result->resultRows[0];
        return $queryResult['count'];
    }

    public function insert(array $data)
    {
        if (empty($data)) {
            return null;
        }
        $columnValue = [];
        $column = [];
        $params = [];
        foreach ($data as $key => $value) {
            array_push($column, $key);
            array_push($columnValue, $value);
            array_push($params, "?");
        }
        $column = implode(', ', $column);
        $params = implode(', ', $params);
        $query = "insert into ".$this->table." ($column) VALUES ($params)";
        $result = await($this->db->query(
            $query, $columnValue
        ));
        assert($result instanceof QueryResult);
        return json_encode($result->insertId);
    }

    public function update(string $id, array $data)
    {
        if (empty($data)) {
            return null;
        }
        $columnValue = [];
        $column = [];
        $query = "UPDATE ".$this->table;
        foreach ($data as $key => $value) {
            array_push($column, $key . " = ?");
            array_push($columnValue, $value);
        }
        array_push($columnValue, $id);
        $column = implode(', ', $column);
        $query = $query . " SET $column WHERE ".$this->primaryKey.' = ?';
        $result = await($this->db->query(
            $query, $columnValue
        ));
        assert($result instanceof QueryResult);
        return json_encode($result->affectedRows);
    }

    public function delete(string $id)
    {
        $result = await($this->db->query(
            'DELETE FROM '.$this->table.' WHERE '.$this->primaryKey.' = ?',
            [$id]
        ));
        assert($result instanceof QueryResult);
        return json_encode($result->affectedRows);
    }

    public function find(string $id)
    {
        $result = await($this->db->query(
            'SELECT * FROM '.$this->table.' WHERE '.$this->primaryKey.' = ? limit 1',
            [$id]
        ));
        assert($result instanceof QueryResult);
        if (count($result->resultRows) === 0) {
            return null;
        }
        return $result->resultRows[0];
    }

    public function findAll(array $where = [], int $limit = 0, int $offset = 0)
    {
        $query = 'SELECT * FROM '.$this->table;
        $paramValue = [];
        if (!empty($where)) {
            $query .= " WHERE 1=1 ";
            foreach ($where as $key => $value) {
                $query .= "AND {$key} = ? ";
                array_push($paramValue, $value);
            }
        }
        if ($limit != 0) {
            $query .= 'limit ? offset ?';
            array_push($paramValue, $limit);
            array_push($paramValue, $offset);
        }
        $result = await($this->db->query(
            $query, $paramValue
        ));
        assert($result instanceof QueryResult);
        if (count($result->resultRows) === 0) {
            return null;
        }
        return $result->resultRows;
    }


    /**
     * Query for Datatables
     *
     */ 
    private string $queryDatatable;

    /**
     * Query for Filtered Datatables
     *
     */ 
    private string $filteredDatatable;

    /**
     * columnSearch Datatables
     *
     */ 
    private array $columnSearch;

    /**
     * columnOrder Datatables
     *
     */ 
    private array $columnOrder;

    /**
     * Set the value of queryDatatable without where
     *
     * @return  self
     */ 
    public function setQueryDatatable($queryDatatable)
    {
        $this->queryDatatable = $queryDatatable;

        return $this;
    }

    /**
     * Set the value of filteredDatatable
     *
     * @return  self
     */ 
    public function setFilteredDatatable($filteredDatatable)
    {
        $this->filteredDatatable = $filteredDatatable;

        return $this;
    }

    /**
     * Set the value of columnSearch
     *
     * @return  self
     */ 
    public function setColumnSearch(array $columnSearch)
    {
        $this->columnSearch = $columnSearch;

        return $this;
    }

    /**
     * Set the value of columnOrder
     *
     * @return  self
     */ 
    public function setColumnOrder(array $columnOrder)
    {
        $this->columnOrder = $columnOrder;

        return $this;
    }

    public function datatable(array $params) : array
    {
        $columnSearch = $this->columnSearch;
        $coloumOrder = $this->columnOrder;

        $query = $this->queryDatatable;
        $queryFiltered = $this->filteredDatatable;
        $queryCount = "select count(*) total from $this->table";

        // if search active
        $paramValue = [];
        foreach ($columnSearch as $kSearch => $vSearch) {
            if (isset($params['search']['value'])) {
                $query .= " WHERE 1=1 ";
                $queryFiltered .= " WHERE 1=1 ";
                foreach ($params['search']['value'] as $key => $value) {
                    $query .= "AND {$key} like '%?%' ";
                    $queryFiltered .= "AND {$key} like '%?%' ";
                    array_push($paramValue, $value);
                }
            }
        }
        if (isset($params['order'])) {
            $query .= " order by ".$params['order']['0']['column']." ".$params['order']['0']['dir'];
        }
        if (isset($params['length']) && $params['length'] != -1) {
            $query .= " limit ? offset ?" ;
            array_push($paramValue, $params['length']);
            array_push($paramValue, $params['start']);
        }

        $data = $this->query($query, $paramValue);
        $filter = $this->query($queryFiltered, $paramValue);
        $count = $this->query($queryCount, []);
        return [
            'data' => $data,
            'filter' => $filter[0]['total'],
            'count' => $count[0]['total']
        ];
    }
}