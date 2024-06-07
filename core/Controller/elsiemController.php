<?php
/*
Controller de ejemplo para usar la conexion con la base de datos configurada en el config.
*/

class elsiemController
{
    protected $coreConfig;
    protected $coreSecure;
    protected $coreComm;
    protected $coreDbo;

    public function __construct($config, $secure, $comm, $dbo){
        $this->coreConfig = $config;
        $this->coreSecure = $secure;
        $this->coreComm = $comm;
        $this->coreDbo = $dbo;
    }

    //index.php?controller=security&key=asdasldmaslkmdlasm
    public function GET_GetToken($view, $params){

        error_log("key:".$params["key"]);

        $sec = $this->coreSecure->GetObjSecurity();
        $token = $sec->GetToken($params["key"]);

        $arrResponse = array(
            "token" => $token,
        );
        $view->ResponseOk(200,"OK",$arrResponse);
    }
}