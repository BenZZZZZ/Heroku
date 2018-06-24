<?php
namespace App\PR;

class Docs {

    function __construct($db, $v) {
        $this->db = $db;
        $this->v = $v;
    }

    function getDoc() {

        $sql = "SELECT name FROM docs";
        $data = $this->db->qF1($sql);
        return [
            'status' => "true",
            'data' => $data
        ];
    }

    function updateDoc($params) {

        $params['id'] = 1;
        $params['name'] = htmlentities($params['name']);
        $data = $this->db->i('docs', $params);
        return [
            'status' => "true",
            'alert' => "Updated successfully"
        ];
    }
}