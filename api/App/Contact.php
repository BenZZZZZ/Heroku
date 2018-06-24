<?php
namespace App;

use Auth;

class Contact {

    function __construct($db, $v) {
        $this->db = $db;
        $this->v = $v;
    }

    function myContacts() {
        $sessUsrId = $_SESSION['id'];
        $sql = "SELECT * FROM contacts WHERE cId='{$sessUsrId}' and status=1 order by mdate";
        $data = $this->db->qF($sql);
        return [
            'status' => "true",
            'data' => $data
        ];
    }

    function addContact($params, $auth) {
        // var_dump('test');
        $valids = [
            'name' => [
                'required' => 'Name Cannot Be Empty'
            ],
            'un' => [
                'required' => 'Email/Mobile Cannot Be Empty',
                'email|number' => 'Email/Mobile is not valid'
            ]
        ];
        $check = $this->v->vR($valids, $params);if($check)return $check;
        $sessUsrId = $_SESSION['id'];
        $sql = "SELECT * FROM users WHERE (email='{$params['un']}' or phone='{$params['un']}') and id!='{$sessUsrId}'";
        $data = $this->db->qF1($sql);
        if($data){
            $params['usersId'] = $data->id;
            $msg = "Contact added Successfully";
        }else{
            $mobCheck = $this->v->number($params['un']);
            $emailCheck = $this->v->email($params['un']);
            if(!$mobCheck){
                $regist['phone'] = $params['un'];
            }
            if(!$emailCheck){
                $regist['email'] = $params['un'];
            }
            $regist['refId'] = $sessUsrId;
            $lastInsertId = $auth->registerWithOutValidation($regist);
            $params['usersId'] = $lastInsertId;
            
            //to do
                //send mail for email and sms for mobile no
            //to do
            $msg = "{$params['un']} is not registered with cmcc, invite has been sent to him sucessfully";
        }
        $params['cId'] = $sessUsrId;
        $params['type'] = 1;
        unset($params['un']);
        $data = $this->db->i('contacts', $params);
        return [
            'status' => "true",
            'alert' => $msg
        ];
    }
}