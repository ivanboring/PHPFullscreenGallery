<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace MarcusJohansson\Gallery;

class FileDb {
    
    public static function getInstance() {
        static $instance = null;
        if (null === $instance) {
            $instance = new static();
        }
        return $instance;
    }

    public function getData($path) {
        if (!file_exists($path)) {
            return null;
        } else {
            return (array) json_decode(file_get_contents($path));
        }
    }  

    public function writeData($newdata, $path) {
        if (!file_exists($path)) {
            $this->createDbFile($path);
        }
        $data = (array) json_decode(file_get_contents($path));
        file_put_contents($path, json_encode((array) array_merge_recursive($newdata, $data)));

    }

    protected function __construct() {}
    
    private function createDbFile($url) {
        $paths = explode('/', $url);
        $newurl = '';
        $count = count($paths);
        $i = 1;
        foreach($paths as $path) {
            if($path == '..' || $path == '') {
                $newurl .= $path;
            } else {
                $newurl .= '/' . $path;
                if ($i != $count && !file_exists($newurl)) {
                    mkdir($newurl, 0777, true);
                } else if ($i == $count) {
                    file_put_contents($newurl, '');                   
                }
            }
            $i++;
        }
    }
    
    private function __clone() {}
    
    private function __wakeup() {}
}