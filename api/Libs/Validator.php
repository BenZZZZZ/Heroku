<?php
namespace Libs;

class Validator {
    var $err;
    var $errFlag = 0;
    var $db;
    function __construct($db){
        $this->db = $db;
    }
    function required($inputField){
        if($inputField == ""){
            return true;
        }
    }
    function email($inputField){
        if(!filter_var($inputField, FILTER_VALIDATE_EMAIL) && $inputField != ''){
            return true;
        }
    }
    function number($inputField){
        if(!is_numeric($inputField) && $inputField != ''){
            return true;
        }
    }
    function unique($inputField, $model, $field, $editId){
        if($inputField == '')return false;
        $row = $this->db->qF1("select * from $model where $field='$inputField' and id!='$editId'");
        // $table = R::findOne( $model, $field.' = ? and id != ?', [ $inputField, $editId ] );
        if($row){
            // return $row->id;
            return true;
        }

    }
    function uniqueType($inputField, $model, $field, $editId){
        require_once('models/'.$model.'.php');
        $table = $model::where($field, $inputField)->where('id','!=',$editId)->first();
        if($table){
            // return $table->id;
            return true;
        }
    }
    function match($inputField, $field, $request){
        $tempArr = explode('.', $field);
        $value = $this->getFormArrVal($tempArr, $request);
        if($inputField != $value){
            return true;
        }
    }
    function getFormArrVal($tempArr, $request){
        $i = 0;
        foreach($tempArr as $ta){
            if($i == 0){
                if(!isset($request[$ta]))return null;
                $value =$request[$ta];
            }else{
                $value = $value[$ta];
            }
            $i++;
        }
        return $value;
    }
}
