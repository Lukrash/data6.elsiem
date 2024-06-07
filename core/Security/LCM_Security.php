<?php

class LCMSecurity
{
    protected $objSecurity;
    protected $controllersSecured;

    //Tipos de seguridad
    //1. Basic --> (http) LCM_SecurityHttp
    //2. Cookies (cockies) --> LCM_SecuritySessions
    //3. Tokens (jwt) --> LCM_SecurityJWT

    public function __construct($config)
    {
        $paramSecurity = $config->GetSecurityParams('Type');
        $this->controllersSecured = $config->GetSecurityParams('Controllers');
        $typeSecurity = $paramSecurity["Mode"];
        
        switch($typeSecurity){
            case "jwt":
                require 'LCM_SecurityJWT.php';
                $this->objSecurity = new LCMSecurityJWT($paramSecurity);
                break;
            default:
                $this->objSecurity = null;
                break;
        }
        error_log("Security Loaded!!:".$typeSecurity);
    }

    public function GetPublicInfo(){
        return $this->objSecurity->GetPublicInfo();
    }

    public function GetObjSecurity(){
        return $this->objSecurity;
    }


    /**
     * Este metodo se encargará de chequear la seguridad y cortara el acceso si es necesario al API.
     */
    public function CheckSecurity($controller, $action){

        error_log("CheckSecurity".print_r($this->controllersSecured,true));
        $response = null;

        if($this->IsMethodProtected($controller, $action)){
            $response = $this->objSecurity->CheckSecurity($controller, $action);
        }

        return $response;
    }

    /*
        Chequea si el metodo debe estar protegido segun el config
    */
    private function IsMethodProtected($controllerName, $actionName){
        
        foreach($this->controllersSecured as $controller=>$methods){
            if($controllerName === $controller){
                foreach($methods as $clave=>$methodName){
                    if($actionName === $methodName){
                        error_log("Protected:".$controllerName."-".$actionName);
                        return true;
                    }
                }
            }
        }
        return false;
    }

}