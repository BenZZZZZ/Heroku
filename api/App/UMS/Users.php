<?php
namespace App\UMS;

class Users {

    function __construct($db, $v) {
        $this->db = $db;
        $this->v = $v;
    }

    function listAllUsers($params, $post) {
        $where = " WHERE 1=1 ";
        foreach ($post['search'] as $key => $value) {
            if($value !== ''){
                $where .= "and {$key} like '%{$value}%'";
            }
        }
        $sql = "SELECT * FROM users " . $where . (isset($params['s'])?'order by '.$params['s'].' '.$params['so']:'');
        $data = $this->db->qFP($sql, $params['p'], $params['l']);
        $data['page'] = $params['p'];
        $data['limit'] = $params['l'];
        return [
            'status' => "true",
            'data' => $data
        ];
    }

    function userRoles($id) {
        $sql = "SELECT * FROM userroles where users_id='{$id}'";
        $data = $this->db->qF($sql);
        return [
            'status' => "true",
            'data' => $data
        ];
    }

    function rolePerms($id) {
        $sql = "SELECT * FROM roleperms where roles_id='{$id}'";
        $data = $this->db->qF($sql);
        return [
            'status' => "true",
            'data' => $data
        ];
    }

    function userPerms($id) {
        // $sql = "SELECT * FROM roleperms where roles='{$id}' and roletype='role'";
        // $data = $this->db->qF($sql);
        $data = $this->getUserPerms($id);
        $perms = [];
        foreach ($data as $key => $value) {
            $perms[] = [
                'id' => $key
            ];
        }
        return [
            'status' => "true",
            'data' => $perms
        ];
    }

    function updateUserRoles($params) {

        if(!isset($params['name'])){
            $params['name'] = [];
        }
        $sql = "SELECT * FROM userroles where users_id='{$params['id']}'";
        $data = $this->db->qF($sql);
        foreach ($data as $key => $value) {
            if (($k = array_search($value->roles_id, $params['name'])) !== false) {
                unset($params['name'][$k]);
            }else{
                $sql = "DELETE from userroles where id={$value->id}";
                $this->db->qI($sql);
            }
        }
        foreach ($params['name'] as $key => $value) {
            $sql = "INSERT into userroles set users_id='{$params['id']}', roles_id='{$value}'";
            $this->db->qID($sql);
        }
        return [
            'status' => "true",
            'alert' => 'Roles successfully updated'
        ];
    }

    function updateRolePerms($params) {

        if(!isset($params['name'])){
            $params['name'] = [];
        }
        $sql = "SELECT * FROM roleperms where roles_id='{$params['id']}'";
        $data = $this->db->qF($sql);
        foreach ($data as $key => $value) {
            if (($k = array_search($value->perms_id, $params['name'])) !== false) {
                unset($params['name'][$k]);
            }else{
                $sql = "DELETE from roleperms where id={$value->id}";
                $this->db->qI($sql);
            }
        }
        foreach ($params['name'] as $key => $value) {
            $sql = "INSERT into roleperms set roles_id='{$params['id']}', perms_id='{$value}'";
            $this->db->qID($sql);
        }
        return [
            'status' => "true",
            'alert' => 'Roles successfully updated'
        ];
    }

    function getUserPerms($id) {

        // var_dump($id);
        $sql = "SELECT * FROM userroles where users_id='{$id}'";
        $data = $this->db->qF($sql);
        $perms = [];
        foreach ($data as $key => $value) {
            $sql = "SELECT * FROM roleperms rp, perms p where rp.roles_id='{$value->roles_id}' and p.id=rp.perms_id";
            $data1 = $this->db->qF($sql);
            foreach ($data1 as $key1 => $value1) {
                $perms[$value1->perms_id] = $value1->code;
            }
        }
        $sql = "SELECT up.perms_id, p.code, up.addrm FROM userperms up, perms p where up.users_id='{$id}' and p.id=up.perms_id";
        $data = $this->db->qF($sql);
        foreach ($data as $key => $value) {
            if($value->addrm){
                $perms[$value->perms_id] = $value->code;
            }else{
                unset($perms[$value->perms_id]);
            }
        }
        return $perms;
    }

    function updateUserPerms($params) {

        if(!isset($params['name'])){
            $params['name'] = [];
        }
        $sql = "SELECT * FROM userroles where users_id='{$params['id']}'";
        $data = $this->db->qF($sql);
        $rolePerms = [];
        foreach ($data as $key => $value) {
            $sql = "SELECT * FROM roleperms rp, perms p where rp.roles_id='{$value->roles_id}' and p.id=rp.perms_id";
            $data1 = $this->db->qF($sql);

            foreach ($data1 as $key1 => $value1) {
                $rolePerms[$value1->perms_id] = $value1->perms_id;
                $sql = "SELECT * FROM userperms WHERE users_id='{$params['id']}' AND perms_id='{$value1->perms_id}' AND addrm='0'";
                $data2 = $this->db->qF1($sql);
                if(!in_array($value1->perms_id, $params['name'])){
                    if(!$data2){
                        $sql = "INSERT into userperms set users_id='{$params['id']}', perms_id='{$value1->perms_id}', addrm='0'";
                        $this->db->qID($sql);
                    }
                }else{
                    if($data2){
                        $sql = "DELETE FROM userperms WHERE id='{$data2->id}'";
                        $this->db->qI($sql);
                    }
                }
            }
        }
        $sql = "SELECT * FROM userperms where users_id='{$params['id']}' and addrm='1'";
        $data = $this->db->qF($sql);
        foreach ($data as $key => $value) {
            if(!in_array($value->perms_id, $params['name'])){
                $sql = "SELECT * FROM userperms WHERE users_id='{$params['id']}' AND perms_id='{$value->perms_id}' AND addrm='1'";
                $data1 = $this->db->qF1($sql);
                if($data1){
                    $sql = "DELETE FROM userperms WHERE id='{$data1->id}'";
                    $this->db->qI($sql);
                }
            }
        }
        foreach ($params['name'] as $key => $value) {
            if(!in_array($value, $rolePerms)){
                $sql = "SELECT * FROM userperms WHERE users_id='{$params['id']}' AND perms_id='{$value}' AND addrm='1'";
                $data = $this->db->qF1($sql);
                if(!$data){
                    $sql = "INSERT into userperms set users_id='{$params['id']}', perms_id='{$value}', addrm='1'";
                    $this->db->qID($sql);
                }
            }
        }
        return [
            'status' => "true",
            'alert' => 'User Permissions successfully updated'
        ];
    }
}