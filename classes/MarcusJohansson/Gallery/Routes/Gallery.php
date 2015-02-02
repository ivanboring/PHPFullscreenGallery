<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace MarcusJohansson\Gallery\Routes;

class Gallery {
    protected $smartyObject;
    
    function execute() {
        $this->smartyObject->assign('title', 'PHP Simple Touchscreen Gallery');
        $this->smartyObject->assign('start', \MarcusJohansson\Gallery\Helper::getUrl(''));
        $this->smartyObject->assign('nextimage', \MarcusJohansson\Gallery\Helper::getUrl('ApiGetNextImage', array('api' => $_GET['api'])));
        $this->smartyObject->assign('previousimage', \MarcusJohansson\Gallery\Helper::getUrl('ApiGetPreviousImage', array('api' => $_GET['api'])));
        $this->smartyObject->assign('image', \MarcusJohansson\Gallery\Helper::getUrl('ApiGetImage', array('api' => $_GET['api'])));
        $this->smartyObject->assign('dirs', \MarcusJohansson\Gallery\Helper::getUrl('ApiGetDirs', array('api' => $_GET['api'])));
        $this->smartyObject->assign('body', $this->smartyObject->fetch('gallery.tpl'));
        $this->smartyObject->display('body.tpl');
    }
    
    function getSmarty($smarty) {
        $this->smartyObject = $smarty;        
    }
}