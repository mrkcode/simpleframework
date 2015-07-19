<?php

abstract class Model {
    protected $sql  = [];
    protected $vars = [];
    
    static private $mysqli;
    
    static public function setMysqli(Mysqli $instance)
    {
        self::$mysqli = $instance;
    }
    
    public function __call($key, $args)
    {
        if (!isset($this->sql[$key])) {
            throw new Exception("Undefined sql expression {$key}", 500);
        }
        
        if (is_array(current($args))) {
            $args = current($args);
        }
        
        $result = self::$mysqli->query(
            $this->parse($this->sql[$key], $args)
        );
        
        if (self::$mysqli->errno) {
            trigger_error(self::$mysqli->error);
            exit();
        }
        
        $return = [
            'row'    => [],
            'rows'   => [],
            'one'    => null,
            'num'    => self::$mysqli->affected_rows,
            'lastId' => self::$mysqli->insert_id
        ];
        
        if ($result instanceof mysqli_result) {
            $return['rows'] = $result->fetch_all(MYSQLI_ASSOC);
            $return['row']  = current($return['rows']);
            $return['one']  = current($return['row']);
            $return['num']  = $result->num_rows;
        }
        
        return (object) $return;
    }
    
    private function parse($sql, $args)
    {
        foreach ($args as $key => $value) {
            if (strpos($sql, ":{$key}")) {
                $sql = str_replace(":{$key}", $this->quote($value), $sql);
            }
        }
        
        foreach ($this->vars as $key => $value) {
            $sql = str_replace("{{$key}}", $value, $sql);
        }
        
        return $sql;
    }
    
    private function quote($value)
    {
        return "'" . self::$mysqli->real_escape_string($value) . "'";
    }
}