<?php
namespace App\PR;

class Reviewer {

    function __construct($db, $v) {
        $this->db = $db;
        $this->v = $v;
    }

    function reviewerList($params, $post) {
        $where = " WHERE 1=1 ";
        if(isset($post['search'])){
            foreach ($post['search'] as $key => $value) {
                if($value !== ''){
                    $where .= "and {$key} like '%{$value}%'";
                }
            }
        }
        $where .= " and ur.users_id=u.id and r.id=ur.roles_id and r.code='prreviewer'";
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

    function reviewerStudentList($id, $params, $post) {
        // var_dump($id);
        $where = " WHERE 1=1 ";
        if(isset($post['search'])){
            foreach ($post['search'] as $key => $value) {
                if($value !== ''){
                    $where .= "and {$key} like '%{$value}%'";
                }
            }
        }
        $where .= " and prrs.student_id=u.id and prrs.reviewer_id=$id ";
        $sql = "SELECT u.id, u.name, u.phone, u.email FROM users u, pr_reviewerstudents prrs" . $where . (isset($params['s'])?'order by '.$params['s'].' '.$params['so']:'');
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

    function reviewerStudentUpdate($id, $params, $post) {
        var_dump($id);
        var_dump($post);

        if(!isset($params['name'])){
            $params['name'] = [];
        }
        $sql = "SELECT * FROM pr_reviewerstudents where reviewer_id='{$id}'";
        $data = $this->db->qF($sql);
        $tempIds = [];
        foreach ($data as $key => $value) {
            $tempIds[] = $value->id;
            var_dump($value->student_id);
            if (($k = array_search($value->roles_id, $params['name'])) !== false) {
                unset($params['name'][$k]);
            }else{
                $sql = "DELETE from pr_reviewerstudents where id={$value->id}";
                $this->db->qI($sql);
            }
        }
        return [
            'status' => "true",
            'data' => 'test'
        ];
    }


}