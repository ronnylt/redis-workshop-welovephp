<?php


namespace WeLovePhp\Users;

use Doctrine\DBAL\Connection;

class UsersManager
{
    protected $connection;
    protected $redis;

    public function __construct(Connection $conn, $redis)
    {
        $this->connection = $conn;
        $this->redis = $redis;
    }

    public function load($id)
    {
        return $this->connection->executeQuery('SELECT * FROM users WHERE id = :id', array('id' => $id))->fetch(\PDO::FETCH_OBJ);
    }

    public function create($name)
    {
        $this->connection->executeQuery('INSERT INTO users (name) VALUES (:name)', array(
            'name' => $name,
        ));

        $id = $this->connection->lastInsertId();

        return $id;
    }

    public function getUsers($ids)
    {
        return $this->connection->executeQuery('SELECT * FROM users WHERE id IN (?)',
            array($ids),
            array(\Doctrine\DBAL\Connection::PARAM_INT_ARRAY)
        )->fetchAll();
    }
}