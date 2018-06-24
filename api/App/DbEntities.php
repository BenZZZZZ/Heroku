<?php
namespace App;

class DbEntities {

    function __construct($db, $v) {
        $this->db = $db;
        $this->v = $v;
    }

    function listEntity($entityName, $params, $post) {
        $where = " WHERE 1=1 ";
        if(isset($post['search'])){
            foreach ($post['search'] as $key => $value) {
                if($value !== ''){
                    $where .= "and {$key} like '%{$value}%'";
                }
            }
        }
        $sql = "SELECT * FROM {$entityName} " . $where . (isset($params['s'])?'order by '.$params['s'].' '.$params['so']:'');
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

    function listOneEntity($entityName, $id) {
        $sql = "SELECT * FROM {$entityName} where id='{$id}'";
        $data = $this->db->qF1($sql);
        return [
            'status' => "true",
            'data' => $data
        ];
    }

    function update($entityName, $params) {
        // $valids = [
        //     'name' => [
        //         'required' => 'Name Cannot Be Empty'
        //     ],
        //     'phone' => [
        //         'required' => 'Phone Number Cannot Be Empty',
        //         'number' => 'Phone Number Contains only Numbers',
        //         'unique' => [
        //             'model' => 'users',
        //             'field' => 'phone',
        //             'editId' => $params['id'],
        //             'err' => 'Phone no already Registered'
        //         ]
        //     ],
        //     'email' => [
        //         'required' => 'Email Cannot Be Empty',
        //         'email' => 'Please enter a Valid Email1',
        //         'unique' => [
        //             'model' => 'users',
        //             'field' => 'email',
        //             'editId' => $params['id'],
        //             'err' => 'Email ID Already Registered'
        //         ]
        //     ],
        //     'pwd' => [
        //         'required' => 'Password Cannot Be Empty',
        //     ]
        // ];
        // $check = $this->v->vR($valids, $params);if($check)return $check;
        $this->db->i($entityName, $params);
        return [
            'status' => "true",
            'alert' => 'Entity Updated Successfully'
        ];
    }

    function delete($entityName, $params) {
        var_dump($entityName);
        var_dump($params);
        $this->db->d($entityName, $params);
        return [
            'status' => "true",
            'alert' => 'Entity Deleted Successfully'
        ];
    }
}