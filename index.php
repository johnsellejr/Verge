<?php

include 'lib/bones.php';
define('ADMIN_USER', 'cdbadmin');
define('ADMIN_PASSWORD', 'DandBlab5?!');

get('/', function($app) {
    $app->set('message', 'Welcome Back!');
    $app->render('home');
});

get('/signup', function($app) {
    $app->render('user/signup');
});

post('/signup', function($app) {
    $user = new User();
    $user->full_name = $app->form('full_name');
    $user->email = $app->form('email');
    $user->roles = array();
    $user->name = preg_replace('/[^a-z0-9-]/', '',
        strtolower($app->form('username')));
    $user->_id = 'org.couchdb.user:' . $user->name;
    $user->salt = 'secret_salt';
    $user->password_sha = sha1($app->form('password') . $user->salt);
    
    try {
        $response = $app->couch->storeDoc($user);
    } catch (Exception $e) {
        echo "ERROR: ".$e->getMessage()." (".$e->getCode().")<br>\n";
    }    
    
    $app->set('message', 'Thanks for Signing Up ' . $app->form('name') . '!');
    //$app->set('message', "Document Stored; CouchDB response: ".print_r($response,true)."\n".'Thanks for Signing Up ' . $app->form('name') . '!');
    //echo "The document is stored. CouchDB response body: ".print_r($response,true)."\n";
    $app->render('home');
});

get('/say/:message', function($app) {
    $app->set('message', $app->request('message'));
    $app->render('home');
});

?>