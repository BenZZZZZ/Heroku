<?php

namespace Libs;
use PDO;

class DB {//Direct sql query should always be the first parameter
    // public $con;
    private $con;
    public $lEQ;

    public function __construct(Array $dbConfig) {
        $this->connectDB($dbConfig);
    }

    private function connectDB(Array $dbConfig) {
        $type = $dbConfig['type'];
        $host = $dbConfig['host'];
        $un = $dbConfig['un'];
        $pass = $dbConfig['pass'];
        $db = $dbConfig['db'];
        $conn = new PDO("$type:host=$host;dbname=$db", $un, $pass);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->con = $conn;
    }

    private function find($table, $where = '', Array $params = []) {
        $sql = "SELECT";
        if(is_array($table)){
            if(isset($table['fields'])){
                $sql .= sprintf(' %s', $table['fields']);
            }else{
                $sql .= " *";
            }
            $tab = $table['table'];
        }else{
            $sql .= " *";
            $tab = $table;
        }
        $sql .= sprintf(' FROM %s', $tab);
        if($where !== ''){
            $sql .= " WHERE {$where}";
        }
        // var_dump($sql);
        // var_dump($params);
        $stmt = $this->con->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    private function qFind($sql) {
        $stmt = $this->con->prepare($sql);
        $stmt->execute();
        return $stmt;
    }

    private function f($table, $where = '', Array $params = []) {//find
        $stmt = $this->find($table, $where, $params);

        return $stmt->fetchAll(PDO::FETCH_CLASS);
    }

    private function f1($table, $where = '', Array $params = []) {//findOne
        $stmt = $this->find($table, $where, $params);

        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    private function qF($sql) {//queryFind
        $stmt = $this->qFind($sql);

        return $stmt->fetchAll(PDO::FETCH_CLASS);
    }

    private function qFP($cSql, $offset = 0, $limit = 10) {//queryFindWithPagination
        $stmt = $this->qFind($cSql);
        $count = $stmt->rowCount();
        // $sql = sprintf($cSql . ' LIMIT %s, %s', ($offset * $limit) - $limit, $limit);
        $sql = "{$cSql}";
        if($offset !== 'a'){
            $offset = ($offset * $limit) - $limit;
            $sql = "{$cSql} LIMIT {$offset}, {$limit}";
        }
        $data = $this->qF($sql);

        return [
            'count' => $count,
            'data' => $data
        ];
    }

    private function qF1($sql) {//queryFindOne
        $stmt = $this->qFind($sql);

        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    private function qID($sql) {//queryInsertDefault
        $date = date('Y-m-d H:i:s');
        $sql = "{$sql}, cdate = '{$date}', mdate = '{$date}', status = 1";
        // var_dump($sql);
        return $this->qI($sql);
    }

    private function qUD($sql, $where) {//queryUpdateDefault
        $date = date('Y-m-d H:i:s');
        $sql = "{$sql}, mdate = '{$date}' {$where}";
        // var_dump($sql);
        return $this->qI($sql);
    }

    private function qI($sql) {//queryInsert
        // $this->con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->con->exec($sql);
        $lastInsertId = $this->con->lastInsertId();
        return $lastInsertId;
    }

    private function i($table, $data, $extra = []) {//insert and update
        $sql = ($data['id']===''?'insert into ':'update ') . $table . ' set ';
        // $sql = 'insert into ' . $table . ' set ';
        $data = array_merge($data, [
            'cdate' => date('Y-m-d H:i:s'),
            'mdate' => date('Y-m-d H:i:s'),
            'status' => 1
        ]);
        $data = array_merge($data, $extra);
        foreach ($data as $k => $v) {
            $sql .= "{$k}='{$v}', ";
        }
        $sql = rtrim($sql, ', ');
        if($data['id'] !== ''){
            $sql .= " where id='{$data['id']}'";
        }
        // var_dump($sql);
        return $this->qI($sql);
    }

    private function d($table, $data) {//delete
        $sql = "delete from {$table} where ";
        foreach ($data as $k => $v) {
            $sql .= "{$k}='{$v}' and ";
        }
        $sql = rtrim($sql, ' and ');
        // $this->con->exec($sql);
        $this->qI($sql);

    }

    public function __call($method, $args) {
        if(method_exists($this, $method)) {
            $this->beforeQuery($args);
            return call_user_func_array(array($this,$method),$args);
            $this->afterQuery();
        }
    }

    private function beforeQuery($args) {
        // if(count($args) === 1){
            $this->lEQ = $args[0];//lastExecutedQuery
        // }
    }

    private function afterQuery($args) {

    }

}
