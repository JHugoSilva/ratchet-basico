<?php

namespace Api\Websocket;

use PDO;
use PDOException;

class DbConnection {

    private string $host = "localhost";
    private string $user = "userx";
    private string $pass = "password";
    private string $dbname = "chat-ratchet";
    private int|string $port = "3306";
    private object $connect;

    public function __construct()
    {
        $this->getConnection();
    }

    private function getConnection(): object {
        try {
            $this->connect = new PDO("mysql:host={$this->host};port={$this->port};dbname=".$this->dbname, $this->user, $this->pass);
            return $this->connect;
        } catch (PDOException $err) {
            die("Erro: Conexão com banco de dados não realizado com sucesso!");
        }
    }

    public function execute($query, $params=[]) {
        try {
            $statement = $this->connect->prepare($query);
            $statement->execute($params);
            return $statement;
        } catch (PDOException $e) {
            die('ERROR'. $e->getMessage());
        }
    }

    public function insert($table, $values) {
        $fields = array_keys($values);
        $binds = array_pad([], count($fields),'?');
        $query = "INSERT INTO {$table} (".implode(',', $fields).") VALUES (".implode(',', $binds).")";
        $this->execute($query, array_values($values));
        return $this->connect->lastInsertId();
    }

    public function select($table, $where = null, $order = null, $limit = null, $fields = '*') {

        if (!empty($where)) {
            $where = strlen($where) ? 'WHERE '.$where:'';
        }
        if (!empty($order)) {
            $order = strlen($order) ? 'ORDER BY '.$order:'';
        }
        if (!empty($limit)) {
            $limit = strlen($limit) ? 'LIMIT '.$limit:'';
        }
        $query = 'SELECT '. $fields .' FROM '. $table.' '.$where.' '.$order.' '.$limit;
        return $this->execute($query);
    }

    public function update($table, $where, $values = []) {
        $query = 'UPDATE '.$table.' SET ';
        $campos = [];
        foreach ($values as $c => $v) {
            $campos[] = $c."='".$v."'";
        }
        $query .= implode(', ', $campos);
        $query .= ' WHERE '.$where;
        $this->execute($query);
        return true;
    }

    public function delete($table, $where) {
        $query = 'DELETE FROM '.$table.' WHERE '.$where;
        $this->execute($query);
        return true;
    }
}