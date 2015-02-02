<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace MarcusJohansson\Gallery\Routes;

class ApiGetImage {
    
    function execute() {
        $api = \MarcusJohansson\Gallery\Helper::getApi();
        $path = isset($_GET['path']) ? $_GET['path'] : array();
        header('cache-control:no-transform, max-age=1209600');
        if ($api) {
            $image = $api->getImage($path);
        }
    }
}