<?php
namespace Extlibs;

use Libs\Validator;
// use Symfony\Component\HttpFoundation\Session\Session;

class AppValidator extends Validator{
    function __construct($db){
        parent::__construct($db);
    }
    function validateForm($valids, $request){
        // $session = new Session;
        // // var_dump($session->all());exit;
        // $this->allValidations($valids, $request);
        // if($this->errFlag == 1){
        //     $_SESSION['ferr'] = $this->err;
        //     $session->set('ferr', $this->err);
        //     header('Location:'.$_SERVER['HTTP_REFERER']);exit;
        // }
    }
    function vR($valids, $request){//validateResponse
        $this->allValidations($valids, $request);
        // var_dump('test1');
        // var_dump($this->errFlag);

        if($this->errFlag){
            return [
                'status' => "false",
                'formErr' => $this->err
            ];
        }
    }
    function allValidations($valids, $request){
        foreach($valids as $k => $v){
            $tempArr = explode('.', $k);
            $value = $this->getFormArrVal($tempArr, $request);
            if($value === null)continue;
            $this->err[$k]['val'] = $value;
            foreach($v as $k1 => $v1){
                $k2 = explode('|', $k1);
                $orCheck = [];
                foreach ($k2 as $k3 => $v3) {
                    if($v3=='required'){
                        $orCheck[] = $this->required($value);
                    }elseif($v3=='email'){
                        $orCheck[] = $this->email($value);
                    }elseif($v3=='number'){
                        $orCheck[] = $this->number($value);
                    }elseif($v3=='unique'){
                        $orCheck[] = $this->unique(
                            $value, $v1['model'], $v1['field'], $v1['editId']
                        );
                    }elseif($v3=='match'){
                        $orCheck[] = $this->match($value, $v1['field'], $request);
                    }
                }
                if(!in_array(NULL, $orCheck)){
                    $this->err[$k]['err'] = $v1;
                    $this->errFlag = 1;
                    break;
                }
                // if($k1=='required'){
                //     if($this->required($value)){
                //         $this->err[$k]['err'] = $v1;
                //         $this->errFlag = 1;
                //         break;
                //     }
                // }elseif($k1=='email'){
                //     if($this->email($value)){
                //         $this->err[$k]['err'] = $v1;
                //         $this->errFlag = 1;
                //         break;
                //     }
                // }elseif($k1=='number'){
                //     if($this->number($value)){
                //         $this->err[$k]['err'] = $v1;
                //         $this->errFlag = 1;
                //         break;
                //     }
                // }elseif($k1=='unique'){
                //     $result = $this->unique($value, $v1['model'], $v1['field'], $v1['editId']);
                //     if($result){
                //         $this->err[$k]['err'] = $v1['err'];
                //         $this->err[$k]['errExId'] = $result;
                //         $this->errFlag = 1;
                //         break;
                //     }
                // }elseif($k1=='match'){
                //     if($this->match($value, $v1['field'], $request)){
                //         $this->err[$k]['err'] = $v1['err'];
                //         $this->errFlag = 1;
                //         break;
                //     }
                // }
            }
        }
    }
}
