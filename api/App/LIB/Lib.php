<?php
namespace App\LIB;

class Lib {

    function __construct($db, $v) {
        $this->db = $db;
        $this->v = $v;
    }

    function listAllLibs($params, $post) {
        $where = " WHERE 1=1 ";
        foreach ($post['search'] as $key => $value) {
            if($value !== ''){
                $where .= "and {$key} like '%{$value}%'";
            }
        }
        $sql = "SELECT * FROM libraries " . $where . (isset($params['s'])?'order by '.$params['s'].' '.$params['so']:'');
        $data = $this->db->qFP($sql, $params['p'], $params['l']);
        $data['page'] = $params['p'];
        $data['limit'] = $params['l'];
        $bookType = [
            1 => "Book",
            2 => "Video",
            3 => "Game"
        ];
        foreach ($data['data'] as $key => $value) {
            // var_dump($key);
            $value->type = $bookType[$value->type];
            // var_dump($value);
            $value->editable = ($value->cid==$_SESSION['id']?true:false);

        }
        return [
            'status' => "true",
            'data' => $data
        ];
    }

    function addLibs($post) {
        // var_dump($post);exit;
        if($post['id'] === ""){
            $sql = "INSERT into ";
        }else{
            $sql = "UPDATE ";
        }
        $sql .= " libraries set cid='{$_SESSION['id']}', name='{$post['name']}', isbn='{$post['isbn']}', description='{$post['description']}', type='{$post['type']}'";

        if($post['id'] === ""){
            $this->db->qID($sql);
        }else{
            $this->db->qUD($sql, " where id='{$post['id']}'");
        }
        return [
            'status' => "true",
            'alert' => 'Library updated successfully'
        ];
    }
}