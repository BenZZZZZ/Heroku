<?php

use Symfony\Component\HttpFoundation\Request;
// require_once '/var/www/html/BenZ/Test/Test3/CMCC/api/includes/includes.php';
// require_once __dir__ . '/../includes/includes.php';

$silex->post('login', function(Request $request) use($silex, $auth) {
    return $silex->json(rF($auth, 'login', [$request->request->all()]));
});

$silex->post('fblogin', function(Request $request) use($silex, $auth) {
    return $silex->json(rF($auth, 'fblogin', [$request->request->all()]));
});
    
$silex->post('checkAccess', function(Request $request) use($silex, $auth) {
    // return $silex->json($auth->login($request->get('email'), $request->get('pwd')));
    return $silex->json(rF($auth, 'checkAccess', []));
});

$silex->post('checkPerms', function(Request $request) use($silex, $auth) {
    // return $silex->json($auth->login($request->get('email'), $request->get('pwd')));
    return $silex->json(rF($auth, 'checkPerms', [$request->request->all()]));
});

$silex->post('logout', function() use($silex, $auth) {
    return $silex->json(rF($auth, 'logout', []));
});

$silex->post('register', function(Request $request) use($silex, $auth) {
    return $silex->json(rF($auth, 'register', [$request->request->all()]));
});