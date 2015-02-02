<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace MarcusJohansson\Gallery\Routes;

class ApiGetNextImage {
    
    function execute() {
        $api = \MarcusJohansson\Gallery\Helper::getApi();
        $paths = isset($_GET['paths']) ? $_GET['paths'] : array();
        $current = isset($_GET['current']) ? $_GET['current'] : array();
        $random = isset($_GET['random']) ? $_GET['random'] : true;
        if ($api) {
            $image = $api->getNextImage($paths, $random, $current);
            if ($image) {
                echo json_encode(array(
                    'status' => 'ok', 
                    'image' => \MarcusJohansson\Gallery\Helper::getUrl('ApiGetImage', array('api' => $_GET['api'], 'path' => $image[key($image)])), 
                    'hash' => key($image)
                ));
                exit;
            }
        }
        echo json_encode(array('status' => 'error'));
    }
}