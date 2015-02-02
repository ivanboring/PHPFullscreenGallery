<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace MarcusJohansson\Gallery\Routes;

class ApiGetDirs {
    
    function execute() {
        $api = \MarcusJohansson\Gallery\Helper::getApi();
        $return = array();
        $path = isset($_GET['path']) ? $_GET['path'] : '/';
        if ($api) {
            $return = $api->getDirectoryTree($path);
        }
        echo json_encode($return);
    }
}