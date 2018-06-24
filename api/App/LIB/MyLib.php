<?php
namespace App\LIB;

class MyLib {

    function __construct($db, $v) {
        $this->db = $db;
        $this->v = $v;
    }

    function addToMyList($post) {
        $sql = "SELECT * FROM mylib where users_id='{$_SESSION['id']}' and libraries_id='{$post['id']}'";
        $data = $this->db->qF($sql);
        if(!$data){
            $sql = "INSERT into mylib set users_id='{$_SESSION['id']}', libraries_id='{$post['id']}'";
            $this->db->qID($sql);
        }else{
            return [
                'status' => "true",
                'alert' => 'Library already added'
            ];
        }
        return [
            'status' => "true",
            'alert' => 'Library added to your list successfully'
        ];
    }

    function listMyLibs($params, $post) {
        $where = " WHERE 1=1 ";
        foreach ($post['search'] as $key => $value) {
            if($value !== ''){
                $where .= "and {$key} like '%{$value}%'";
            }
        }
        $where .= "and l.id=ml.libraries_id and ml.users_id='{$_SESSION['id']}' ";
        $sql = "SELECT ml.id as id, l.name as name, l.isbn as isbn, l.type as type FROM libraries l, mylib ml " . $where . (isset($params['s'])?'order by '.$params['s'].' '.$params['so']:'');
        // var_dump($sql);
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

        }
        return [
            'status' => "true",
            'data' => $data
        ];
    }
}