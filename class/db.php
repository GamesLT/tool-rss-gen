<?php

class gcDB extends mysqli {
    
    /**
     * @return \gcDB
     */
    public static function getInstance() {
        static $instance = null;
        if ($instance === null)
            $instance = new self();
        return $instance;
    }
    
    protected function __construct() {
        parent::connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    }
    
    public function makeArraySmaller(&$array) {                
        foreach ($array as $key => $value) {
            if (is_array($value))
                $array[$key] = json_encode($value);
        }
    }
    
    public function quickInsert(array $array, $table = 'data') {
        
        $this->makeArraySmaller($array);
                        
        $tmp = '(';
        foreach ($array as $value)
            $tmp .= '\'' . str_replace('\'', '\'\'', $value) .'\',';
        $tmp = substr($tmp, 0, -1);
        $tmp .= ')';
         
        $sql = 'INSERT INTO ' . $table . '(`' . implode('`, `',array_keys($array)) . '`) VALUES' . $tmp . ';';
        error_log($sql);
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
