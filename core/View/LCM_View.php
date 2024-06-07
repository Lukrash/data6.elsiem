<?php

class LCMView
{
    function __construct()
    {

    }
    /*
    public function show($vars = array(), $httpcode=200){
        http_response_code($httpcode);
        echo json_encode($vars);
    }
    */

    private function SetApiHeaders(){
        header("Content-Type: application/json");
    }

    public function ResponseOk($httpcode=200, $message="Ok", $data = array()){
        $this->SetApiHeaders();

        $resp = array(
            "status" => "true",
            "message" => $message,
            "errorCode" => "0",
            "data" => $data
        );

        http_response_code($httpcode);
        echo json_encode($resp);
    }

    public function ResponseError($httpcode=500, $message="Ok", $errorCode="9999", $data = array()){
        $this->SetApiHeaders();

        $resp = array(
            "status" => "false",
            "message" => $message,
            "errorCode" => $errorCode,
            "data" => $data
        );

        http_response_code($httpcode);
        echo json_encode($resp);
    }

}
