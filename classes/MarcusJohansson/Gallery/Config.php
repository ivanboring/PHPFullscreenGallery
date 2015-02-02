<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace MarcusJohansson\Gallery;

class Config {
    static private $config = array();
    
    static function setConfig($config) {
        self::$config = $config;
    }
    
    static function getConfig($key = '') {
        if($key) {
            return isset(self::$config [$key]) ? self::$config [$key] : null;
        } else {
            return self::$config ;
        }
    }
}