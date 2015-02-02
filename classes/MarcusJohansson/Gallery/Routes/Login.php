<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace MarcusJohansson\Gallery\Routes;

class Login {
    function execute() {
        if ($api = $this->getApi()) {
            $url = $api->redirectUri();
            header('location: ' . $url);
        } else {
            header('location:' . \MarcusJohansson\Gallery\Helper::getUrl('NotFound'));
        }
    }
    
    function getApi() {
        $api = isset($_GET['api']) && $_GET['api'] ? \MarcusJohansson\Gallery\Helper::sanitize($_GET['api'], false) : '';
        switch($api) {
            case 'Dropbox':
                $api = \MarcusJohansson\Gallery\ApiConnections\ApiFactory::create('Dropbox');
                return $api;
            default:
                return false;
        }
    }
}