<?php

namespace Classes;

use Classes\Interfaces\Connecting;
use mysqli;

class Connect implements Connecting
{
    public $link;
    private $args = [];

    public function __construct($table) {
        $this->link = new mysqli('localhost', 'root', 'root', $table);
    }

    public function __set($val, $res) {
        $this->args[$val] = $res;
    }

    public function __get($val) {
        return $this->args[$val];
    }

    public function select_all($sql) {
        return $this->link->query($sql)->fetch_all(MYSQLI_ASSOC);
    }

    public function select($sql) {
        return $this->link->query($sql)->fetch_assoc();
    }

    public function insert($tableName, $column, $values) {
        $sql = "INSERT INTO {$tableName} ({$column}) VALUES ({$values})";
        return $this->link->query($sql);
    }

    public function update($tableName, $str, $who) {
        $sql = "UPDATE {$tableName} SET {$str} WHERE {$who}";
        return $this->link->query($sql);
    }

    public function delete($tableName, $who) {
        $sql = "DELETE FROM {$tableName} WHERE {$who}";
        return $this->link->query($sql);
    }
}