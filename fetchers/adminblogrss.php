<?php

/**
 * Description of defaultrss
 *
 * @author Raimondas
 */
class fetchAdminBlogRSS {
    
     function fetch() {
         $feed = new SimplePie();
         $feed->set_feed_url('http://www.games.lt/RSS/blog/160005.xml');
         $feed->set_cache_location(sys_get_temp_dir());
         $feed->set_cache_duration(3600);
         $feed->set_cache_name_function('sha1');
         $feed->init();
         $feed->handle_content_type();         
         $ret = array();
         foreach ($feed->get_items() as $item) {
             $author = (array)$item->get_author();
             $ret[] = array(
                 'link' => $item->get_id(),
                 'text' => $item->get_description(),
                 'date' => $item->get_date('j F Y | g:i a'),
                 'title' => $item->get_title(),
                 'author' => $author,
                 'id' => $item->get_id(),
                 'image' => null,
                 'interesting' => true
             );
         }
         return $ret;
     }
    
}
