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
        $data = array();
        $table = null;
        $mode = null;
        foreach ($types as $type) {                    
            $this->useFetcher($type, $data, $table, $mode);
            foreach ($data as $rec) {
                $rec['type'] = $type;
                if (!isset($rec['id'])) {                    
                    $link = isset($rec['title'])?$rec['title']:$rec['link'];
                } else {
                    $link = $rec['id'];
                }
                $rec['id'] = sha1($link) . ';' . substr($link, 0, 1) . ';' . substr($link, -1) . ';' . strlen($link);
                if (isset($rec['date']))
                    $rec['date'] = strtotime($rec['date']);
                if ($db->recordExists($rec['id'], $table)) {
                    foreach ($mode as $field => $what) 
                        switch ($what) {
                            case 'increase':
                                $sql = sprintf('UPDATE `%s` SET `%s` = `%s` + %d WHERE id = \'%s\'', $table, $field, $field, $rec[$field], $rec['id']);
                                $db->query($sql);
                            break;
                            case 'update':
                                $sql = sprintf('UPDATE `%s` SET `%s` = \'%s\' WHERE id = \'%s\'', $table, $field, str_replace('\'', '\'\'', $rec[$field]), $rec['id']);
                                $db->query($sql);
                            break;
                        }
                } elseif ($db->quickInsert($rec, $table))
                   $i++;
            }
        }
        return "Fetched. $i new items.";
    }
    
    public function useFetcher($type, &$data, &$table, &$mode) {
        $class = 'fetch' . ucfirst($type);
        if (!class_exists($class, true))
            return '';

        $instance = new $class();
        $data = $instance->fetch();
        $table = $instance->getTable();
        $mode = $instance->getMode();
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
