<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace MarcusJohansson\Gallery\Routes;

class GetAuth {
    function execute() {
        if ($api = \MarcusJohansson\Gallery\Helper::getApi()) {
            $api->returnUri();
            $auth = $api->getToken();
            if($auth != null) {
                header('location:' . \MarcusJohansson\Gallery\Helper::getUrl('Gallery', array('api' => $_GET['api'])));
            }
            
        } else {
            header('location:' . \MarcusJohansson\Gallery\Helper::getUrl('NotFound'));
        }
    }
}