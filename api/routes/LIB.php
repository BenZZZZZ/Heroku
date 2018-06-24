<?php

use Symfony\Component\HttpFoundation\Request;
// require_once '/var/www/html/BenZ/Test/Test3/CMCC/api/includes/includes.php';
// require_once __dir__ . '/../includes/includes.php';

$silex->post('libraries/listAllLibs', function(Request $request) use($silex, $lib) {
    return $silex->json(rF($lib, 'listAllLibs', [
        $request->query->all(), $request->request->all()
    ]));
});

$silex->post('libraries/listMyLibs', function(Request $request) use($silex, $myLib) {
    return $silex->json(rF($myLib, 'listMyLibs', [
        $request->query->all(), $request->request->all()
    ]));
});

$silex->post('libraries/addToMyList', function(Request $request) use($silex, $myLib) {
    return $silex->json(rF($myLib, 'addToMyList', [
        $request->request->all()
    ]));
});

$silex->post('libraries/addLibs', function(Request $request) use($silex, $lib) {
    return $silex->json(rF($lib, 'addLibs', [
        $request->request->all()
    ]));
});