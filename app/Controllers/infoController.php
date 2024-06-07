<?php

class infoController
{
    protected $coreConfig;
    protected $coreSecure;
    
    public function __construct($config, $secure, $comm, $dbo){
        $this->coreConfig = $config;
        $this->coreSecure = $secure;
    }

    //index.php?controller=info
    public function GET($view, $params){
        
        $response = array(
            "application" => $this->coreConfig->GetRootParam('appName'),
            "version_application" => $this->coreConfig->GetRootParam('appVersion'),
            "company_application" => $this->coreConfig->GetRootParam('appCompany'),
            "description_application" => $this->coreConfig->GetRootParam('appDescription')
        );
        
        $view->ResponseOk(200,"OK",$response);
    }
}