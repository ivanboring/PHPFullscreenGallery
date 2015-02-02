<?php

/**
 * File for the start route
 */
namespace MarcusJohansson\Gallery\Routes;

class Start {
    protected $smartyObject;
    
    function execute() {
        $this->smartyObject->assign('title', 'PHP Simple Touchscreen Gallery');
        $this->smartyObject->assign('dropboxLogin', \MarcusJohansson\Gallery\Helper::getUrl('Login', array('api' => 'Dropbox')));
        $this->smartyObject->assign('body', $this->smartyObject->fetch('start.tpl'));
        $this->smartyObject->display('body.tpl');
    }
    
    function getSmarty($smarty) {
        $this->smartyObject = $smarty;        
    }

}
