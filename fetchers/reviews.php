<?php

/**
 * Description of defaultrss
 *
 * @author Raimondas
 */
class fetchReviews {
        
    public function fetch() {
        
        class_exists('simple_html_dom_node', true);
        $html = str_get_html($this->getReviewsPageHTML());
        $ret = array();
        foreach($html->find('a.s2, div.s1') as $element) {
            if (isset($element->href)) {
                $line = array(
                    'url' => $element->href,
                    'title' => $element->plaintext,
                    'date' => trim(substr($element->title, strlen('Patalpinimo data:'))),
                    'image' => $this->getImageForURL($element->href)
                );
            } else {
                list($line['platform'], $line['game']) = array_map('trim', explode(',', $element->plaintext, 2));
            }
            if (isset($line['game']))
                $ret[] = $line;
        }
        
        var_dump($ret);
             /*$ret[] = array(
                 'link' => $item->get_id(),
                 'text' => $desc,
                 'date' => $item->get_date('j F Y | g:i a'),
                 'title' => $item->get_title(),
                 'author' => $author,
                 'id' => $item->get_id(),
                 'image' => null,
                 'interesting' => true
             );*/
        
     }
     
     public function getImageForURL($review_url) {
         $html = str_get_html($this->getURLContents($review_url));
         foreach($html->find('.img img') as $element)
             return $element->src;
         $photos_url = str_replace(array('.apzvalgos/', '.apzvalga/'), '.foto/', $review_url);
         $html = str_get_html($this->getURLContents($photos_url));         
         foreach($html->find('a img') as $element) {
             if (substr($element->src, 0, 8) == 'w/gshot/')
                return 'http://games.lt/' . $element->src;
         }  
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
     
     public function getReviewsPageHTML() {
         return $this->getURLContents('http://www.games.lt/g/all.all_apzvalgos/0.201304?sev=month');
     }
    
}