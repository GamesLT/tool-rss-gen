<?php

/**
 * Description of defaultrss
 *
 * @author Raimondas
 */
class fetchGamesTop {
    
    static $platforms = array(
        'pc',
        'ps2',
        'ps3',
        'psp',
        'ds',
        'xbox',
        '3ds',
        'psvita',
        'x360',
        'rev',
        'mobile',
        'gboy',
        'gcube',        
    );
    
    const PLATFORM_URL = 'http://www.games.lt/g/%s.home';

    function getTable() {
        return 'top';
    }
    
    function getMode() {
        return array(
            'lastpos' => 'update',
            'score' => 'increase'
        );
    }

    function fetch() {
        class_exists('simple_html_dom_node', true);
        $games = array();
        foreach (self::$platforms as $platform) {
            $url = sprintf(self::PLATFORM_URL, $platform);
            $contents = $this->getURLContents($url);
            $html = str_get_html($contents);            
            $first = $html->find('.down', 0);
            $no = 0;
            foreach ($first->find('tr td div.spalva, tr td a') as $element) {
                switch ($no++) {
                    case 1:
                        $no++;
                        continue;
                    case 0:
                        $games[] = array(
                            'game' => trim(substr($element->plaintext, 2)),
                            'link' => 'http://games.lt/' .  $element->href,
                            'platform' => $platform,
                            'score' => floor(pow(log(30), 3) * 1000),
                            'lastpos' => 1
                        );
                    break;
                    default:
                        if ($element->tag == 'div') {
                            $number = intval($element->plaintext);
                        } else {
                            $games[] = array(
                                'game' => $element->plaintext,
                                'link' => 'http://games.lt/' . $element->href,
                                'platform' => $platform,
                                'score' => floor(pow(log(30 - $number), 3) * 1000),
                                'lastpos' => $number
                            );
                        }
                }
            }
        }
        return $games;
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

}
