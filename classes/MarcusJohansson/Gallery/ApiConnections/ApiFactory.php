<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace MarcusJohansson\Gallery\ApiConnections;

class ApiFactory {
    public static function create($type) {
        $classname = 'MarcusJohansson\Gallery\ApiConnections\\' .$type;
        return new $classname;
    }
}