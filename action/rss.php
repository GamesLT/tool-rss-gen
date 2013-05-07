<?php
/**
 * Description of actionFetch
 *
 * @author mekdrop
 */
class actionRSS implements iAction {    
    
    public function getVars() {
        return array('type' => 'int');
    }
    
    public function exec(array $params) {
        $db = new gcDB();
        $ret = '<?xml version="1.0" encoding="ISO-8859-1" ?>';
        $ret .= $this->renderXMLStartTag('rss', array('version' => '2.0'));
        $ret .= $this->renderXMLStartTag('channel');
        switch ($param['type']) {
        }
        $ret .= $this->renderXMLFastTag('title', 'Games.lt ');
        foreach ($db->quickFetch(sprintf('type = %d LIMIT %d', $params['type'], 10)) as $record) {
            
        }
        return $ret;
    }
    
    public function renderXMLFastTag($tag, $content, $params = array()) {
        return $this->renderXMLStartTag($tag, $params) . $content . $this->renderXMLCloseTag($tag);
    }
    
    public function renderXMLStartTag($tag, $params = array()) {
        $tag = '<' . $tag;
        if (!empty($params)) {
            $sparams = array();
            foreach ($params as $key => $value)
                $sparams[] = $key .'="' . addslashes($value) .'"';
            $tag .= ' ' . implode(' ', $sparams);
        }
        return $tag . '>';
    }
    
    public function renderXMLCloseTag($tag) {
        return '</' . $tag . '>';
    }
    
}

/*

 <rss version="2.0">

 <channel>
   <title>W3Schools Home Page</title>
   <link>http://www.w3schools.com</link>
   <description>Free web building tutorials</description>
   <item>
     <title>RSS Tutorial</title>
     <link>http://www.w3schools.com/rss</link>
     <description>New RSS tutorial on W3Schools</description>
   </item>
   <item>
     <title>XML Tutorial</title>
     <link>http://www.w3schools.com/xml</link>
     <description>New XML tutorial on W3Schools</description>
   </item>
 </channel>

 </rss>