<?php
/**
 * Description of actionFetch
 *
 * @author mekdrop
 */
class actionUnTop implements iAction {    
    
    public function getVars() {
        return array();
    }
    
    public function exec(array $params) {
        $db = gcDB::getInstance();
        $db->query('UPDATE top SET score = score - 1000');
    }   
    
}