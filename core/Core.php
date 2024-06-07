<?php

require "Controller/Controller.php";

class Core
{
    protected $cfg;
    
    public function __construct($environment=null)
    {
        error_log ("*********** INIT *****************");
        require 'Configuration/LCM_Config.php'; //Recuperamos la clase de configuración

        switch($environment){
            case "dev":
                error_log ("Environment: Develop");
                $this->cfg = new LCMConfig('config/config.dev.json');
                break;    
            default:
                error_log ("Environment: Production");
                $this->cfg = new LCMConfig('config/config.pro.json');
                break; 
        }

        //https://www.php.net/manual/es/timezones.php
        date_default_timezone_set($this->cfg->GetRootParam("appTimeZone"));
    }

    
    public function main()
    {
        /*require 'View/LCM_View.php';
        $view = new LCMView();*/

        $dbo = null;
        if($this->cfg->IsDatabaseDefined()){
            require 'Database/LCM_DB.php';
            $dbo = new LCMDB($this->cfg);
        }
          
        $security = null;
        if($this->cfg->IsSecurityDefined()){
            require 'Security/LCM_Security.php';
            $security = new LCMSecurity($this->cfg);
        }

        $communication = null;
        if($this->cfg->IsCommunicationDefined()){
            require 'Communication/LCM_Communication.php';
            $communication = new LCMCommunication($this->cfg);
        }

        //Recuperamos el controlador de la URL,
        $controllerName = "";
        if(!empty($_GET['controller'])){
            $controllerName = $_GET['controller'];
        }
        else{
            if(!empty($_GET['ctrl'])){
                $controllerName = $_GET['ctrl'];
            }
        }


        //Recuperamos la accion del controlador
        $actionName="";
        if(!empty($_GET['action'])){
            $actionName = $_GET['action'];
        }
        else{
            if(!empty($_GET['act'])){
                $actionName = $_GET['act'];
            }
        }
            

        //Recuperamos el metodo llamado y sus parámetros
        $method = $_SERVER['REQUEST_METHOD'];
        $dataGet = $_GET;
        $dataBody = file_get_contents('php://input');

        $controller = new Controller($controllerName, $actionName);
        $controller->InitializeParameters($method, $dataGet, $dataBody);
        $controller->Starter($this->cfg, $security,$communication, $dbo);
        error_log ("*********** END *****************");
    }

    
};