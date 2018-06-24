<?php
namespace App;

class CustomException {

    function methodNotFoundException() {
        $errJson = [
            'status' => "false",
            'alert' => 'Execption : ' . $e->getMessage()
        ];
        return $errJson;
    }
}