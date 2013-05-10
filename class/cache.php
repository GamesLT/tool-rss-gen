<?php
/**
 * Description of cache
 *
 * @author Raimondas
 */
class gcCache {
    
    public static function get($key, $defaultValue = null) {
        $db = gcDB::getInstance();
        $data = $db->quickFetch('key = \''.$key.'\' LIMIT 1', 'cache');
        if (empty($data))
            return $defaultValue;
        return json_decode($data[0]);
    }
    
    public static function set($key, $value) {
        $db = gcDB::getInstance();        
        return $db->quickInsert(array('id' => $key, 'value' => json_encode($value, true)), 'cache');
    }
    
    private function __construct() {
    }
    
}