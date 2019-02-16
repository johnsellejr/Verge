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

get('/login', function($app) {
    $app->render('user/login');
});

post('/login', function($app) {
    $user = new User();
    $user->name = $app->form('username');
    $user->password = $app->form('password');
    //$user->password = $app->form('password');
    $user->login();
    echo $user->session_cookie;
    
    $app->set('success', "You were logged in " . $user->session_cookie . '!');
    $app->render('home');
});

get('/logout', function($app) {
    User::logout();
    $app->redirect('/');
});

get('/user/:username', function($app) {
    $app->set('user', User::get_by_username($app->request('username')));
    $app->set('is_current_user', ($app->request('username') == User::current_user() ? true : false));
    $app->render('user/profile');
});

resolve();
?>
