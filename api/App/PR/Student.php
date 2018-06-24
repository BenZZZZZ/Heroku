<?php
namespace App\PR;

class Student {

    function __construct($db, $v) {
        $this->db = $db;
        $this->v = $v;
    }

    function studentList($params, $post) {
        $where = " WHERE 1=1 ";
        if(isset($post['search'])){
            foreach ($post['search'] as $key => $value) {
                if($value !== ''){
                    $where .= "and {$key} like '%{$value}%'";
                }
            }
        }
        $where .= " and ur.users_id=u.id and r.id=ur.roles_id and r.code='prstudent'";
        $sql = "SELECT u.id, u.name, u.phone, u.email FROM users u, userroles ur, roles r" . $where . (isset($params['s'])?'order by '.$params['s'].' '.$params['so']:'');
        // var_dump($sql);
        $page = isset($params['p'])?$params['p']:1;
        $limit = isset($params['l'])?$params['l']:10;
        $data = $this->db->qFP($sql, $page, $limit);
        $data['page'] = $page;
        $data['limit'] = $limit;
        return [
            'status' => "true",
            'data' => $data
        ];
    }
}