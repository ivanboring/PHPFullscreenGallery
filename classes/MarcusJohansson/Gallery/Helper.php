<?php

namespace MarcusJohansson\Gallery;

class Helper {
    static function sanitize($string, $force_lowercase = true, $anal = false) {
      $strip = array("~", "`", "!", "@", "#", "$", "%", "^", "&", "*", "(", ")", "_", "=", "+", "[", "{", "]",
                     "}", "\\", "|", ";", ":", "\"", "'", "&#8216;", "&#8217;", "&#8220;", "&#8221;", "&#8211;", "&#8212;",
                     "—", "–", ",", "<", ".", ">", "/", "?");
      $clean = trim(str_replace($strip, "", strip_tags($string)));
      $clean = preg_replace('/\s+/', "-", $clean);
      $clean = ($anal) ? preg_replace("/[^a-zA-Z0-9]/", "", $clean) : $clean ;
      return ($force_lowercase) ?
        (function_exists('mb_strtolower')) ?
            mb_strtolower($clean, 'UTF-8') :
            strtolower($clean) :
        $clean;
    }
  
    static function getUrl($uri, $query = array()) {
        if(count($query)) {
            $query = http_build_query($query);
        } else {
            $query = '';
        }

        if(Config::getConfig('niceurl')) {
            return trim(Config::getConfig('basepath'), '/') . '/' . $uri . '?' . $query;
        } else {
            if($query) {
                return trim(Config::getConfig('basepath'), '/') . '/' . '?q=' . $uri . '&' . $query;
            } else {
                return trim(Config::getConfig('basepath'), '/') . '/' . '?q=' . $uri;
            }
        }
    }
  
    static function getFilePath() {
        if (!isset($_SESSION['db_created'])) {
            $_SESSION['db_created'] = uniqid();
        }
        
        if (isset($_SESSION['db_created'])) {
            $db = $_SESSION['db_created'];
            if ($db) {
                return Config::getConfig('filedb') . '/' . substr($db, 0, 1) . '/' . substr($db, 0, 3) . '/' . $db . '.json';
            }
            return null;
        }
    }
    
    static function getApi() {
        $api = isset($_GET['api']) && $_GET['api'] ? self::sanitize($_GET['api'], false) : '';
        switch($api) {
            case 'Dropbox':
                $api = \MarcusJohansson\Gallery\ApiConnections\ApiFactory::create('Dropbox');
                return $api;
            default:
                return false;
        }
    }    
}