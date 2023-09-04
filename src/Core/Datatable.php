<?php

namespace Acme\Todo\Core;

use React\MySQL\ConnectionInterface;
use React\MySQL\QueryResult;

use function React\Async\await;

class Datatable
{
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

    protected $table;
    /**
     * Set the value of table
     *
     * @return  self
     */ 
    public function setTable($table)
    {
        $this->table = $table;

        return $this;
    }

    private $db;

    private $where = [];

    /**
     * Set the value of where
     *
     * @return  self
     */ 
    public function setWhere(array $where)
    {
        $this->where = $where;

        return $this;
    }

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
        $queryFiltered = $this->queryDatatable;
        
        // get charachter 
        $strPost = strpos($queryFiltered, "from $this->table");
        // get select until before from
        $str = substr($queryFiltered, 0, $strPost);
        $queryFiltered = str_replace($str, "select count(*) total ", $this->queryDatatable);
        
        $queryCount = "select count(*) total from $this->table";

        $queryValue = [];
        if (!empty($this->where)) {
            $query .= " WHERE 1=1 ";
            foreach ($this->where as $key => $value) {
                $query .= "AND {$key} = ? ";
                array_push($queryValue, $value);
            }
        }
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
                    array_push($queryValue, $value);
                }
            }
        }
        if (isset($params['order'])) {
            $query .= " order by ".$params['order']['0']['column']." ".$params['order']['0']['dir'];
        }
        if (isset($params['length']) && $params['length'] != -1) {
            $query .= " limit ? offset ?" ;
            array_push($queryValue, $params['length']);
            array_push($queryValue, $params['start']);
        }

        $data = $this->query($query, $queryValue);
        $filter = $this->query($queryFiltered, $paramValue);
        $count = $this->query($queryCount, []);
        return [
            'data' => $data,
            'filter' => $filter[0]['total'],
            'count' => $count[0]['total']
        ];
    }
}