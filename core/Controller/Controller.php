<?php
require 'IController.php';

class Controller implements IController
{
    protected $view;

    protected $controllerName;
    protected $action;
    protected $method;
    protected $getParams;
    protected $bodyParams;
    protected $config;

    private $version = '1.0.1.6';
    private $currentVersion = 'April 2020';
    private $firstVersion = 'September 2019';
    private $author = 'Lucas Chicharro';
    private $urlAuthor = 'http://lucas.chicharro.net';
    
    public function __construct($controllerName, $action)
    {        
        $this->controllerName = $controllerName.'Controller';
        $this->action = $action;
    }

    public function InitializeParameters($method, $dataGet, $dataBody){
        //Recuperamos los parametros que nos pueden venir en el GET
        $this->getParams = array();
        foreach($dataGet as $key => $value){
            if($key!="controller" && $key!="action"){
                $this->getParams[$key] = $value;
            }
        }
        $this->method = strtoupper($method);
        if($this->action===""){
            $this->action = $this->method;
        }
        else{
            $this->action = $this->method."_".$this->action;
        }

        if($this->method !== "GET"){
            $this->bodyParams = json_decode($dataBody,true);
        }
    }

    public function Starter($config, $secure, $comm, $dbo){
        error_log ("Starter Ctl:".$this->controllerName. "/".$this->action);
        $this->config = $config;

        //Preparo el View
        require $this->config->GetRootPath().'core/View/LCM_View.php';
        $this->view = new LCMView();

        
        $jsonFormat = (strtoupper($this->config->GetRootParam('appInfoPage')) == "HTML" ? false : true );
        if($this->controllerName==="Controller" && $this->action==="GET"){
            $this->ShowCoreInfo($jsonFormat,$dbo, $secure, $comm);
        }
        
        //Incluimos el fichero que contiene nuestra clase controladora solicitada
        $jsonFormat = (strtoupper($this->config->GetRootParam('app404Page')) == "HTML" ? false : true );

        //Busco la ruta del controlador elsiem para funciones propias del framework
        if($this->controllerName ==='elsiemController'){
            $controllerPath = $this->config->GetRootPath()."core/Controller/".$this->controllerName.".php";
        }
        else{
            $controllerPath = $this->config->GetRootPath()."app/Controllers/".$this->controllerName.".php";
        }

        if(is_file($controllerPath)){
            require $controllerPath;
        }
        else
        {
            $this->ShowCore404($jsonFormat,$this->controllerName.".php",$this->action);
        }

        //Si no existe la clase que buscamos y su acción, mostramos un error 404
        if (is_callable(array($this->controllerName, $this->action)) == false)
        {
            $this->ShowCore404($jsonFormat, $this->controllerName,$this->action);
        }
        else{

            //Security actions
            if(!empty($secure)){
                //Chequeamos si el metodo esta protegido
                error_log("Check Method Protected");
                $error = $secure->CheckSecurity($this->controllerName, $this->action);
                if(!empty($error)){
                    error_log("Error".print_r($error, true));
                    $this->view->ResponseError($error["httpstatus"],$error["message"], $error["code"]);
                    exit;
                }
            }

            $controllerNameComplete = $this->controllerName;
            $action = $this->action;
            $controller = new $controllerNameComplete($this->config, $secure, $comm, $dbo);

            
            if($this->method !== "GET"){
                $result = $controller->$action($this->view, $this->getParams, $this->bodyParams);
            }
            else{
                $result = $controller->$action($this->view, $this->getParams);
            }
        }

        $this->Finisher();
    }

    public function Finisher(){
        error_log ("Ender Ctl:".$this->controllerName. "/".$this->action);
        exit;
    }

    /**
     * Muestra información del Framework sino existe la accion info o si la accion es info_elsiem
     */
    private function ShowCoreInfo($jsonFormat=false, $dbo, $security, $comm){
        error_log("ShowCoreInfo Elseim ". ($jsonFormat?"true":"false"));
        //Si la accion es info pero no esta definida en el controlador, pinto un Info propio del framework
        $arrResponse = array(
            "application" => $this->config->GetRootParam('appName'),
            "version_application" => $this->config->GetRootParam('appVersion'),
            "company_application" => $this->config->GetRootParam('appCompany'),
            "description_application" => $this->config->GetRootParam('appDescription'),
            "framework" => "ELSIEM",
            "description" => "The PHP Framework for API REST",
            "long_description" => "Elseim is a API rest framework with MySql database configuration, security and communication with other APIs",
            "first_version" => $this->firstVersion,
            "current_version" => $this->currentVersion,
            "version" => $this->version,
            "author" => $this->author,
            "url_author" => $this->urlAuthor,
            "database" => (!empty($dbo) ? $dbo->GetPublicInfo() : null),
            "security" => (!empty($security) ? $security->GetPublicInfo() : null),
            "communication" => (!empty($comm) ? $comm->GetPublicInfo() : null)
        );
        if($jsonFormat){
            $this->view->ResponseOk(200,"OK",$arrResponse);
        }
        else{
            //Pinta la pagina HTML de Info y su Content Type
            header("Content-Type: text/html; charset=UTF-8");
            include ($this->config->GetRootPath()."core/View/LCM_Info.php");
        }
        $this->Finisher();
    }

    /**
     * Muestra error sino encuentra el Controlle indicado
     */
    private function ShowCore404($jsonFormat, $controller, $action=""){
        $message ="File ".$controller." not found";
        if(!empty($action)){
            $message ="Action ". $action." not found in Controller ".$controller;
        }
        
        $arrResponse = array(
            "application" => $this->config->GetRootParam('appName'),
            "framework" => "ELSIEM",
            "message" => $message,
            "controller" => $controller,
            "action" => $action,
            "version" => $this->version,
            "author" => $this->author,
            "url_author" => $this->urlAuthor,
            "first_version" => $this->firstVersion,
            "current_version" => $this->currentVersion,
        );
        if($jsonFormat){
            $this->view->ResponseOk(200,"OK",$arrResponse);
        }
        else{
            //Pinta la pagina HTML de Info
            header("Content-Type: text/html; charset=UTF-8");
            include ($this->config->GetRootPath()."core/View/LCM_404.php");
        }
        $this->Finisher();
    }
}