<?php
namespace App;

class Msg {

    function __construct($db, $v) {
        $this->db = $db;
        $this->v = $v;
    }

    function myContactMessages() {
        $sessUsrId = $_SESSION['id'];
        $sql = "SELECT * FROM contacts WHERE cId='{$sessUsrId}' and status=1 order by mdate";
        $data = $this->db->qF($sql);
        return [
            'status' => "true",
            'data' => $data
        ];
    }
}