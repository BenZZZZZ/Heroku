<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


require_once __DIR__.'/vendor/autoload.php';


spl_autoload_register(function($className){
    $className = str_replace("\\", "/", $className);
    // var_dump($className.'.php');
    $skipClassArray = [
    ];
    if (!in_array($className, $skipClassArray) && strpos($className, 'Model_') === false) {
        require_once $className.'.php';
    }
});


use Symfony\Component\Yaml\Yaml;
use Symfony\Component\HttpFoundation\Request;
// use Symfony\Component\HttpFoundation\Response;
use Silex\Application as Silex;
use Libs\Bstrap;
use Extlibs\AppValidator;
// use App\CustomException;
use Libs\DB;
use App\Auth\Auth;
use App\UMS\Users;
// use App\PR\Reviewer;
// use App\PR\Student;
// use App\PR\Docs;
use App\DbEntities;
use App\LIB\Lib;
use App\LIB\MyLib;

try {
    $configPath = 'config.yml';
    $config = Yaml::parseFile($configPath);

    session_start();
    if(!isset($_SESSION['perms'])){
        $_SESSION['perms'] = [];
    }
    $db = new DB($config['DB']);
    $validator = new AppValidator($db);
    // $bs = new Bstrap();

    // $db->con->exec("insert into users set test='test'");

    $users = new Users($db, $validator);
    // $reviewer = new Reviewer($db, $validator);
    // $student = new Student($db, $validator);
    // $docs = new Docs($db, $validator);
    $auth = new Auth($db, $validator, $users);
    $dbEntities = new DbEntities($db, $validator);
    $lib = new Lib($db, $validator);
    $myLib = new MyLib($db, $validator);
    // $cE = new CustomException();

    $allClass = [
        'db' => $db,
        'auth' => $auth,
        'users' => $users,
        // 'reviewer' => $reviewer,
        // 'student' => $student,
        // 'docs' => $docs,
        'lib' => $lib,
        'myLib' => $myLib,
    ];

    $silex = new Silex();
    $silex['debug'] = true;
    // Request::setTrustedProxies(['172.16.19.219']);

    $fileList = scandir('routes');
    $fileList = glob('routes/*.php');
    foreach ($fileList as $key => $value) {
        require_once $value;
    }

    $silex->post('dbentities/list/{entityName}', function(
        Request $request, $entityName
    ) use($silex, $dbEntities) {
        return $silex->json(rF($dbEntities, 'listEntity', [
            $entityName, $request->query->all(), $request->request->all()
        ]));
    });

    $silex->get('dbentities/list/{entityName}/{id}', function(
        $entityName, $id
    ) use($silex, $dbEntities) {
        return $silex->json(rF($dbEntities, 'listOneEntity', [
            $entityName, $id
        ]));
    });

    $silex->post('dbentities/update/{entityName}', function(
        Request $request, $entityName
    ) use($silex, $dbEntities) {
        return $silex->json(rF($dbEntities, 'update', [
            $entityName, $request->request->all()
        ]));
    });

    $silex->post('dbentities/delete/{entityName}', function(
        Request $request, $entityName
    ) use($silex, $dbEntities) {
        return $silex->json(rF($dbEntities, 'delete', [
            $entityName, $request->request->all()
        ]));
    });

    $silex->get('test/{controller}/{action}', function(
        Request $request, $controller, $action
    ) use($allClass) {
        $params = $request->query->all();
        unset($params['url']);
        $values = [];
        foreach ($params as $key => $value) {
            $values[] = $key;
        }
        call_user_func_array([$allClass[$controller], $action], $values);


        // $db->pag('');
    });

    $silex->error(function (\Exception $e, Request $request, $code) use($silex) {
        switch ($code) {
            case 404:
                // $message = 'The requested page could not be found.';
                $message = $code . ': ' .$e->getMessage();
                break;
            case 405:
                $message = $code . ': Method Not Allowed';
                break;
            case 500:
                $message = $code . ': Internal Server Error';
                break;
            default:
                $message = $code . ': We are sorry, but something went terribly wrong.';
        }
        
        return $silex->json(silexErrorFunction($message));
    });

    $silex->run();
        
} catch (Exception $e) {
    return eF($e);
}


function rF($controller, $action, $params = [], $perms = []) { //routeFunctions
    try {
        $access = false;
        if(empty($perms)){
            $access = true;
        }
        if(!$access){
            foreach ($perms as $key => $value) {
                if(in_array($value, $_SESSION['perms'])){
                    $access = true;
                    break;
                }
            }
        }
        if(!$access){
            throw new Exception("No API Access for this user");
        }
        return call_user_func_array([$controller, $action], $params);
    } catch (PDOException $e) {
        global $db;
        $ex = ' ('. $db->lEQ . ')';
        return eF($e, $ex);
    } catch (Exception $e) {
        return eF($e);
    }
}


function silexErrorFunction($msg) { //silexErrorFunction
    try {
        throw new Exception($msg);
    } catch (Exception $e) {
        return eF($e);
    }
}


function eF($e, $ex = '') { //errorFunctions
    $errJson = [
        'status' => "false",
        'alert' => 'API Execption : ' . $e->getMessage() . $ex
    ];
    return $errJson;
}