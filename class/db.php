<?php

class gcDB extends mysqli {
    
    public function __construct() {
        parent::connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    }
    
    public function quickInsert(array $array, $table = 'data') {
        if (!isset($array[0]))
            $data = array($array);
        else
            $data = $array;
                
        $sparts = array();
        foreach ($data as $content) {
            $tmp = '(';
            foreach ($content as $value)
                $tmp .= '\'' . str_replace('\'', '\'\'', $value) .'\'';
            $tmp .= ')';
            $sparts[] = $tmp;
        }
        $sql = 'INSERT INTO ' . $table . '(`' . implode('`, `',array_keys($array[0])) . '`) VALUES' . implode(', ', $sparts);
        return $this->query($sql);
    }
    
    public function quickFetch($where = 'type = 0', $table = 'data') {
        $sql = 'SELECT * FROM ' . $table . (($where)?' WHERE ' . $where:'');
        $rez = $this->query($sql);
        if (!$rez)
            return null;
        return $rez->fetch_all();
    }
    
}
