<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace MarcusJohansson\Gallery\ApiConnections;

class Dropbox extends ApiConnections {

    private $dropbox;
    private $config;
    private $client;

    function __construct($config = '') {
        $this->config = $config ? $config : \MarcusJohansson\Gallery\Config::getConfig('dropbox');
        $this->dropbox = new \Dropbox\AppInfo($this->config['app_key'], $this->config['secret']);
    }

    function redirectUri() {
        $csrf = new \Dropbox\ArrayEntryStore($_SESSION, 'dropbox-auth-csrf-token');
        $webAuth = new \Dropbox\WebAuth($this->dropbox, 'dropbox-gallery', $this->config['returnuri'], $csrf, null);
        return $webAuth->start();
    }

    function returnUri() {
        $csrf = new \Dropbox\ArrayEntryStore($_SESSION, 'dropbox-auth-csrf-token');
        $webAuth = new \Dropbox\WebAuth($this->dropbox, 'dropbox-gallery', $this->config['returnuri'], $csrf, null);
        $answer = $webAuth->finish($_GET);
        if (is_array($answer) && count($answer) == 3) {
            $this->setToken($answer);
            $db = \MarcusJohansson\Gallery\FileDb::getInstance();
            $db->writeData(array('dropbox' => array('key' => $answer[0], 'uid' => $answer[1])), \MarcusJohansson\Gallery\Helper::getFilePath());
        }
    }

    function getDirectoryTree($path = '/') {
        if (!$this->client && !$this->getClient()) {
            return array('status' => 'error');
        }
        $returndirs = array('status' => 'ok');
        $directories = $this->client->getMetadataWithChildren($path);
        foreach($directories['contents'] as $directory) {
            if ($directory['is_dir']) {
                $answer = array('id' => $directory['rev'], 'path' => str_replace(array($path, '/'), '', $directory['path']));
                $returndirs['paths'][$directory['path']] = $answer;
            }
        }
        return $returndirs;
    }
    
    function getImage($path) {
        if (!$this->client && !$this->getClient()) {
            return null;
        }
        $fd = tmpfile();
        $metadata = $this->client->getFile($path, $fd);

        header("Content-Type: $metadata[mime_type]");
        fseek($fd, 0);
        fpassthru($fd);
        fclose($fd);
    }
    
    function getNextImage($paths = array(), $random = true, $current = '') {
        if (!$this->client && !$this->getClient()) {
            return null;
        }
        
        $db = \MarcusJohansson\Gallery\FileDb::getInstance();
        $data = $db->getData(\MarcusJohansson\Gallery\Helper::getFilePath());
        // Ready, just add extra
        if (isset($data['dropbox']->ready)) {

        // Caching or first time
        } else {
            $this->cacheImages($paths);
        }
        
        if($random) {
            $image = $this->getRandomImage($current);
        }
        return $image;
    }
    
    private function getRandomImage($current = '') {
        $db = \MarcusJohansson\Gallery\FileDb::getInstance();
        $data = $db->getData(\MarcusJohansson\Gallery\Helper::getFilePath());
        $files =  isset($data['dropbox']->files) ? (array) $data['dropbox']->files : array();
        $seenfiles = isset($data['dropbox']->seenfiles) ? (array) $data['dropbox']->seenfiles : array();
        $amount = count($files);
        // Don't show seen files
        foreach($seenfiles as $key => $value) {
            unset($files[$key]);
        }
        
        // If the current one is in the already shown, follow that order
        if ($current) {
          $temp = array_slice($seenfiles, 0, 1);
          if(!isset($temp[$current]) && isset($seenfiles[$current])) {
              $i = (array_search($current, array_keys($seenfiles)))-1;
              $temp = array_slice($seenfiles, $i, 1);
              return $temp;
          }
        }
        
        $rand = rand(0, ($amount-1));
        $returnvalue = array_slice($files, $rand, 1);
        $db->writeData(array('dropbox' => array('seenfiles' => $returnvalue)), \MarcusJohansson\Gallery\Helper::getFilePath());
        return $returnvalue;
    }
    
    /**
     * 
     * This caches the images folder by folder on each image load so that we don't spam Dropbox with requests
     * 
     * @param type $paths The paths to run through
     * @return boolean
     */
    private function cacheImages($paths = array(), $recursive = false) {
        $db = \MarcusJohansson\Gallery\FileDb::getInstance();
        $known = $db->getData(\MarcusJohansson\Gallery\Helper::getFilePath());
        $save = array();
        
        if(isset($known['dropbox']->pathsrun)) {
            foreach($paths as $key => $path) {
                if(in_array($path, $known['dropbox']->pathsrun)) {
                    unset($paths[$key]);
                }
            }
        }
        
        $found = false;
        $savedata = array();
        
        foreach ($paths as $path) {
            $data = $this->getDirectory($path);
            foreach ($data['contents'] as $filepath) {
                if ($filepath['is_dir']) {
                    if ($this->cacheImages(array($filepath['path']), true)) {
                        return true;
                    }
                }
            }
        }
        
        if(count($paths)) { 
            foreach($paths as $path) {
                $data = $this->getDirectory($path);
                foreach($data['contents'] as $filepath) {
                    if (!$filepath['is_dir']) {
                        $savedata[$filepath['rev']] = $filepath['path'];
                        $found = true;
                    }
                }
            }

            if ($found) {
                $save = array('dropbox' => 
                    array(
                        'files' => $savedata,
                        'pathsrun' => $paths
                    )
                );        
            }
        }

        // Last try to see if they are saved, which means we ran through it all
        if (!$found) {
            $known = $db->getData(\MarcusJohansson\Gallery\Helper::getFilePath());
            if (isset($known['dropbox']->files) && count($known['dropbox']->files) && $recursive == false) {
                $found = true;
                $save['dropbox']['ready'] = true;
            }
        }
     
        $db->writeData($save, \MarcusJohansson\Gallery\Helper::getFilePath()); 
        
        return $found;
    }

    private function getDirectory($path = '/') {
        if (!$this->client && !$this->getClient()) {
            return null;
        }

        return $this->client->getMetadataWithChildren($path);    
    }
    
    private function getClient() {
        $db = \MarcusJohansson\Gallery\FileDb::getInstance();
        $data = $db->getData(\MarcusJohansson\Gallery\Helper::getFilePath());

        if (isset($data['dropbox']->key)) {
            $client =  new \Dropbox\Client($data['dropbox']->key, 'dropbox-gallery', null, $this->dropbox->getHost());

            if ($client) {
                $this->client = $client;
                return true;
            }
            return false;
        }
        return false;
    }

}
