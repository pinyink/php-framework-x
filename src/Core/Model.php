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
    protected $setValue;
    protected $params;

    public function __construct(ConnectionInterface $db)
    {
        $this->db = $db;
    }

    private function set(array $data)
    {
        $set = '';
        $params = array();
        $x = 1;
        foreach ($data as $key => $value) {
            if ($x >= 1) {
                $set .= ",\"". $key ."\" = ?";
            } else {
                $set .= " ".$key ." = ?";
            }
            array_push($params, $value);
        }
        $this->params = $params;
        $this->setValue = $set;
        return true;
    }

    public function insert(array $data)
    {
        $query = "insert into ".$this->table." set";
        self::set($data);
        $result = await($this->db->query(
            $query.$this->setValue, $this->params
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

    public function findAll(int $limit = 0, int $offset = 0)
    {
        if ($limit != 0) {
            $result = await($this->db->query(
                'SELECT * FROM '.$this->table.' limit ? offset ?', [$limit, $offset]
            ));
        } else {
            $result = await($this->db->query(
                'SELECT * FROM '.$this->table
            ));
        }
        
        assert($result instanceof QueryResult);

        if (count($result->resultRows) === 0) {
            return null;
        }

        return $result->resultRows;
    }
}