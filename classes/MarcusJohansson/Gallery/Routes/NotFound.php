<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace MarcusJohansson\Gallery\Routes;

class NotFound {
    protected $smartyObject;
    
    function execute() {
        $this->smartyObject->assign('title', 'Page not found');
        $this->smartyObject->assign('body', $this->smartyObject->fetch('start.tpl'));
        $this->smartyObject->display('body.tpl');
    }

    function getSmarty($smarty) {
        $this->smartyObject = $smarty;        
    }    

}