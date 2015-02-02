<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

// Basepath of the application (The public should be the seen directory)
$config['basepath'] = 'http://localhost/public';

// Nice Urls (If you have modrewrite in Apache or you setup Nginx/Lighty etc to have ?q as nice url
$config['niceurl'] = false;

// Your Dropbox App Key and Secret (dev app is enough since this is only meant for private use). Returnuri must be same as Dropbox API
$config['dropbox']['app_key'] = '';
$config['dropbox']['secret'] = '';
$config['dropbox']['returnuri'] = 'http://localhost/gallery/public?q=GetAuth&api=Dropbox';

// Url for filedb
$config['filedb'] = '../db_files';