<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace MarcusJohansson\Gallery\ApiConnections;

class ApiConnections {
    private $token = "";

    function getToken() {
        return $this->token;
    }
    
    function setToken($token) {
        $this->token = $token;
    }
    
    function redirectUri() {}
    
    function returnUri() {}
    
    function randomImage() {}
    
    function nextImage() {}
    
    function lastImage() {}
}