<?php

namespace Model;

class User extends \Model {
    protected $vars = [
        'table' => 'lib_05_07_2015'
    ];
    
    protected $sql = [
        'get'    => 'SELECT name FROM {table} WHERE id = :0',
        'getAll' => 'SELECT * FROM {table}',
        'update' => 'UPDATE {table} SET name = :name, text = :text WHERE id = :id',
        'insert' => 'INSERT INTO {table} (name, text, date_added) VALUES (:name, :text, NOW())'
    ];
}