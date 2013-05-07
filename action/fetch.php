<?php
/**
 * Description of actionFetch
 *
 * @author mekdrop
 */
class actionFetch implements iAction {
    
    public function getVars() {
        return array(
            'type' => 'string'
        );
    }
    
    public function exec(array $params) {
        $instance = new fetchReviews();
        $ret = $instance->fetch();
        
        //var_dump($ret);
    }
    
}
