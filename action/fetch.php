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
        if (!empty($params['type'])) {
            $types = array($params['type']);
        } else {
            $types = $this->getFetchers();
        }
        $db = gcDB::getInstance();
        $i = 0;
        foreach ($types as $type) {                    
            $data = $this->useFetcher($type);
            foreach ($data as $rec) {
                $rec['type'] = $type;
                if (!isset($rec['id'])) {                    
                    $link = $rec['title'];                    
                } else {
                    $link = $rec['id'];
                }
                $rec['id'] = sha1($link) . ';' . substr($link, 0, 1) . ';' . substr($link, -1) . ';' . strlen($link);
                $rec['date'] = strtotime($rec['date']);
                if ($db->quickInsert($rec, 'data'))
                     $i++;
            }
        }
        return "Fetched. $i new items.";
    }
    
    public function useFetcher($type) {
        $class = 'fetch' . ucfirst($type);
        if (!class_exists($class, true))
            return '';

        $instance = new $class();
        return $instance->fetch();
    }
    
    public function getFetchers() {
        $path = ROOT_PATH . DIRECTORY_SEPARATOR . 'fetchers';
        $dir = opendir($path);
        $ret = array();
        while (false !== ($entry = readdir($dir))) {
            if (strlen($entry) < 5 || !is_file($path . DIRECTORY_SEPARATOR . $entry) || (substr($entry, -4) != '.php'))
                continue;
            $ret[] = substr($entry, 0, -4);
        }
        closedir($dir);
        return $ret;
    }
    
}
