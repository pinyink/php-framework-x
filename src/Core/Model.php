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
}