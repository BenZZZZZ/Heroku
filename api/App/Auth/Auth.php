<?php
namespace App\Auth;

use App\UMS\Users;

class Auth {

    function __construct($db, $v, Users $users) {
        $this->db = $db;
        $this->v = $v;
        $this->users = $users;
    }

    function login($params) {
        session_destroy();
        // unset($_SESSION['id']);
        // $_SESSION['perms'] = [];
        $valids = [
            'un' => [
                'required' => 'Email/Mobile No Cannot Be Empty',
                'email|number' => 'Email/Mobile is not valid'
            ],
            'lpwd' => [
                'required' => 'Password Cannot Be Empty',
            ]
        ];
        $check = $this->v->vR($valids, $params);if($check)return $check;
        $sql = "SELECT * FROM users WHERE (email='{$params['un']}' or phone='{$params['un']}') and pwd='{$params['lpwd']}' and status=1";
        $data = $this->db->qF1($sql);
        if(!$data){
            return [
                'status' => "false",
                'alert' => 'Your Login details does not match'
            ];
        }
        session_start();
        $_SESSION['id'] = $data->id;
        $perms = $this->users->getUserPerms($data->id);
        array_push($perms, 'login');
        $_SESSION['perms'] = $perms;
        return [
            'status' => "true",
            'alert' => 'You have Logged In Successfully',
            'sess' => [
                'sessId' => session_id()
            ],
            'redir' => 'dashboard'
        ];
    }

    function fblogin($params) {
        session_destroy();
        // var_dump($params);
        $sql = "SELECT * FROM users WHERE fbid='{$params['id']}'";
        $data = $this->db->qF1($sql);
        if(!$data){
            $sql = "INSERT into users set fbid='{$params['id']}', name='{$params['name']}', email='{$params['email']}'";
            $id = $this->db->qID($sql);
            $sql = "INSERT into userroles set users_id='{$id}', roles_id=4";
            $this->db->qID($sql);
        }else{
            $id = $data->id;
        }
        session_start();
        $_SESSION['id'] = $id;
        $perms = $this->users->getUserPerms($id);
        array_push($perms, 'login');
        $_SESSION['perms'] = $perms;
        return [
            'status' => "true",
            'alert' => 'You have Logged In Successfully',
            'sess' => [
                'sessId' => session_id()
            ],
            'redir' => 'dashboard'
        ];
    }

    function logout() {
        session_destroy();
        // unset($_SESSION['id']);
        // $_SESSION['perms'] = [];
        return [
            'status' => 'true',
            'alert' => 'You are Logged Out Successfully',
            'redir' => 'login'
        ];
    }

    function checkAccess() {
        if(!isset($_SESSION['id'])){
            return [
                'status' => 'false',
                'alert' => 'You dont have access for this page, please login',
                'redir' => "login"
            ];
        }
        return [
            'status' => 'true'
            // 'redir' => 'home'
        ];
    }

    function checkPerms(array $params) {
        $accessList = [];
        foreach ($params as $key => $value) {
            $val = explode(',', $value);
            $access = false;
            foreach ($val as $key1 => $value1) {
                $access = in_array($value, $_SESSION['perms']);
            }
            $accessList[$key] = $access;
        }
        return [
            'status' => true,
            'access' => $accessList
            // 'perms' => $_SESSION['perms']
        ];
        // $access = false;
        // foreach ($params as $key => $value) {
        //     $access = in_array($value, $_SESSION['perms']);
            
        // }
        // return [
        //     'status' => true,
        //     'access' => $access
        //     // 'perms' => $_SESSION['perms']
        // ];
    }

    function register($params) {
        $valids = [
            'name' => [
                'required' => 'Name Cannot Be Empty'
            ],
            'phone' => [
                'required' => 'Phone Number Cannot Be Empty',
                'number' => 'Phone Number Contains only Numbers',
                'unique' => [
                    'model' => 'users',
                    'field' => 'phone',
                    'editId' => '',
                    'err' => 'Phone no already Registered'
                ]
            ],
            'email' => [
                'required' => 'Email Cannot Be Empty',
                'email' => 'Please enter a Valid Email1',
                'unique' => [
                    'model' => 'users',
                    'field' => 'email',
                    'editId' => '',
                    'err' => 'Email ID Already Registered'
                ]
            ],
            'pwd' => [
                'required' => 'Password Cannot Be Empty'
            ]
        ];
        $check = $this->v->vR($valids, $params);if($check)return $check;
        $this->db->i('users', $params);
        return [
            'status' => "true",
            'alert' => 'You have Registered Successfully, Please Log In'
        ];
    }

    function registerWithOutValidation($params) {
        $lastInsertId = $this->db->i('users', $params);
        return $lastInsertId;
    }
}