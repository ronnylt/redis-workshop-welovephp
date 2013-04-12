<?php


namespace WeLovePhp\Items;

use Doctrine\DBAL\Connection;

class ItemsManager
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
        return $this->connection->executeQuery('SELECT * FROM items WHERE id = :id', array('id' => $id))->fetch(\PDO::FETCH_OBJ);
    }

    public function create($title)
    {
        $this->connection->executeQuery('INSERT INTO items (title, ts) VALUES (:title, :ts)', array(
            'ts' => time(),
            ':title' => $title,
        ));

        $id = $this->connection->lastInsertId();

        return $id;
    }

    public function getItems($ids)
    {
        return $this->connection->executeQuery('SELECT * FROM items WHERE id IN (?)',
            array($ids),
            array(\Doctrine\DBAL\Connection::PARAM_INT_ARRAY)
        )->fetchAll();
    }

    public function getLatestItems($n)
    {
        return $this->connection->executeQuery('SELECT * FROM items ORDER BY ts DESC LIMIT 0, ' . $n)->fetchAll();
    }


}