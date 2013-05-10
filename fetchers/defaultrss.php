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
         $feed->set_output_encoding('UTF-8');
         $feed->set_cache_location(sys_get_temp_dir());
         $feed->set_cache_duration(1);
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
                 'date' => $item->get_date(),
                 'title' => $item->get_title(),
                 'author' => $author,
                 'id' => $item->get_id(),
                 'interesting' => $interesting = $this->isInteresting($item->get_title()),
                 'image' => $interesting?$this->getImage($item->get_id()):null,
                 'game' => null,
                 'platform' => null
             );
         }
         return $ret;
     }
     
     public function getImage($news_url) {
         class_exists('simple_html_dom_node', true);
         
         $contents = $this->getURLContents($news_url);
         $html = str_get_html($contents);
         $images = array();
         foreach ($html->find('#article img') as $element) {
             if (isset($images[$element->width]))
                 continue;
             $link = $element->src;             
             if (substr($element->src, 0, 4) != 'http')
                  $link = 'http://www.games.lt/' . $link;             
             $images[$element->width] = $link;
         }
         
         if (!empty($images))
             return $images[max(array_keys($images))];
         
         return null;                 
     }
     
     public function getURLContents($url) {
        $ch = curl_init();

        // set the url to fetch
        curl_setopt($ch, CURLOPT_URL, $url);

        // don't give me the headers just the content
        curl_setopt($ch, CURLOPT_HEADER, 0);

        // return the value instead of printing the response to browser
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        // use a user agent to mimic a browser
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.7.5) Gecko/20041107 Firefox/1.0');

        $content = curl_exec($ch);

        // remember to always close the session and free all resources 
        curl_close($ch);

        return $content;
    }
     
     public function isInteresting($title) {
         $mt = strtolower(html_entity_decode($title));
         foreach (self::$words as $word)
             if (strpos($mt, $word))
                 return true;
         return false;
     }
    
}
