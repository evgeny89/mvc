<?php


namespace Classes\Interfaces;


interface Connecting
{
    public function select_all($sql);
    public function select($sql);
    public function insert($tableName, $column, $values);
    public function update($tableName, $str, $who);
    public function delete($tableName, $who);
}