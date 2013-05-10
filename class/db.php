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
        if ($this->connect_errno) {
            echo "Failed to connect to MySQL: (" . $this->connect_errno . ") " . $this->connect_error;
            die();
        }
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
         
        $sql = 'INSERT IGNORE INTO ' . $table . '(`' . implode('`, `',array_keys($array)) . '`) VALUES' . $tmp . ';';
        $ret = $this->query($sql);
        if (!$ret) {
            switch ($this->errno) {
                case 1146:
                    if (isset($array['id'])) {
                        $id = $array['id'];
                        unset($array['id']);
                        $array = array_merge(array('id' => $id), $array);
                    } else {
                        $array = array_merge(array('id' => 0), $array);
                    }
                    $sql2 = 'CREATE TABLE `' . $table  . '` (';
                    foreach ($array as $key => $value) {                        
                        $sql2 .= '  `' . $key . '` ';
                        if ($key == 'id') {
                            if (is_string($value)) {
                                $sql2 .= ' CHAR(50) CHARACTER SET \'ASCII\' NOT NULL PRIMARY KEY';
                            } else {
                                $sql2 .= ' INT NOT NULL AUTO_INCREMENT PRIMARY KEY';
                            }
                        } else {
                            if (is_bool($value)) {
                                $sql2 .= ' BOOL NOT NULL';
                            } elseif (is_int($value)) {
                                $sql2 .= ' INT NOT NULL';
                            } elseif (is_float($value)) {
                                $sql2 .= ' FLOAT NOT NULL';
                            } else {
                                $sql2 .= ' TEXT CHARACTER SET \'UTF8\' NOT NULL';
                            }
                        }
                        $sql2 .= ',' . "\n";
                    }
                    $sql2 = substr($sql2, 0, -2) . "\n" . ');';
                    if (!$this->query($sql2)) {
                         error_log(sprintf('MySQL ERROR #%d (%s; %s)', $this->errno, $this->error, $sql));
                         die();
                    }
                    $ret = $this->query($sql);
                break;
            }
        }
        if ($this->errno)
            error_log(sprintf('MySQL ERROR #%d (%s; %s)', $this->errno, $this->error, $sql));
        return $ret;
    }
    
    public function quickFetch($where = 'type = 0', $table = 'data') {
        $sql = 'SELECT * FROM ' . $table . (($where)?' WHERE ' . $where:'');
        $rez = $this->query($sql);
        if ($this->errno)
            error_log(sprintf('MySQL ERROR #%d (%s; %s)', $this->errno, $this->error, $sql));
        if (!$rez)
            return null;  
        $ret = array();
        while ($row = $rez->fetch_assoc()) 
            $ret[] = $row;
        return $ret;
    }
    
}
