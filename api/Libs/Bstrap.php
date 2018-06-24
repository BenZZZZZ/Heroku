<?php

namespace Libs;

class Bstrap {
    function rF($controller, $action, $params = []) { //routeFunctions
        try {
            call_user_func_array([$controller, 'register'], $params);
        } catch (Exception $e) {
            var_dump('inside route');
            var_dump($e->getMessage());
        }
    }

}
