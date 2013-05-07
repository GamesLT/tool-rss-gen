<?php

/**
 * Description of defaultrss
 *
 * @author Raimondas
 */
class fetchDefaultRSS {
    
    static $words = array(
        'gta',
        'konkursas',
        'laimėtojai',
        'blogo įrašas',
        'geek\'as iš rytų europos',
        'apžvalga',
        'call of duty',
        'cod',
        'grand theft auto',
        'pristatymas',
        'marvel'
    );
    
    public function fetch() {
         $feed = new SimplePie();
         $feed->set_feed_url('http://feeds.feedburner.com/GamesLT');
         $feed->set_cache_location(sys_get_temp_dir());
         $feed->set_cache_duration(3600);
         $feed->set_cache_name_function('sha1');
         $feed->init();
         $feed->handle_content_type();         
         $ret = array();
         foreach ($feed->get_items() as $item) {
             $author = (array)$item->get_author();
             $desc = $item->get_description();
             if ($author['name'] == 'games.lt') {
                 preg_match_all('/<p>Para&scaron;&#279; : <a href="([^"]+)"[^>]+>([^<]+)<\/a><\/p>/ui', $desc, $parts);
                 $desc = str_replace($parts[0][0], '', $desc);
                 $author['name'] = $parts[2][0];
                 $author['link'] = $parts[1][0];
             }
             $ret[] = array(
                 'link' => $item->get_id(),
                 'text' => $desc,
                 'date' => $item->get_date('j F Y | g:i a'),
                 'title' => $item->get_title(),
                 'author' => $author,
                 'id' => $item->get_id(),
                 'image' => null,
                 'interesting' => $this->isInteresting($item->get_title())
             );
         }
         return $ret;
     }
     
     public function isInteresting($title) {
         $mt = strtolower(html_entity_decode($title));
         foreach (self::$words as $word)
             if (strpos($mt, $word))
                 return true;
         return false;
     }
    
}
