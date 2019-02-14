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
    $user->signup($app->form('username'), $app->form('password'));
    
    $app->set('success', 'Thanks for Signing Up ' . $app->form('full_name') . '!');
    $app->render('home');
});

get('/say/:message', function($app) {
    $app->set('message', $app->request('message'));
    $app->render('home');
});

?>