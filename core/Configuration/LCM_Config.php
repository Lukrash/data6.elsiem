<?php

class LCMConfig
{
    private $pathConfig;
    private $config;

    public function __construct($path)
    {
        $this->pathConfig = $path;
        $this->LoadConfig();
    }

    /**
     * Devuelve el valor de la variable de base de datos pedida si existe
     */
    public function GetDataBaseParams($param){
        return $this->GetParams('modDatabase', $param);
    }

    public function IsDatabaseDefined(){
        return isset($this->config['modDatabase']);
    }

    /**
     * Devuelve el valor de la variable de seguridad pedida si existe
     */
    public function GetSecurityParams($param){
        return $this->GetParams('modSecurity', $param);
    }

    public function IsSecurityDefined(){
        return isset($this->config['modSecurity']);
    }

    public function GetCommunicationParams($param){
        return $this->GetParams('modCommunication', $param);
    }

    public function IsCommunicationDefined(){
        return isset($this->config['modCommunication']);
    }

    public function GetRootParam($param){
        return $this->config[$param];
    }

    //Devuelve la ruta donde comienzan los ficheros
    public function GetRootPath(){
        if(empty($this->config['appRootFolder'])){
            return $_SERVER['DOCUMENT_ROOT'].'/';
        }
        else
        {
            return $_SERVER['DOCUMENT_ROOT'].'/'.$this->config['appRootFolder'].'/';
        }
    }
    /**
     * Devuelve el valor de los parametros pedidos
     */
    private function GetParams($module, $param){
        if(isset($this->config[$module])){
            if(isset($this->config[$module][$param])){
                return $this->config[$module][$param];
            }
            else{
                error_log(get_class($this) . ": <$param> field not exist (Case sensitive)");
            }
        }
        else{
            error_log(get_class($this) . ":$module configuration not exist");
        }   
        return null;
    }

    /**
     * Carga la configuracion en memoria desde el JSON
     */
    private function LoadConfig(){
        $data = file_get_contents($this->pathConfig);
        $temp = json_decode($data, true);
        $this->config = $temp[0];
        error_log("Configuration Loaded!!:".$data);
    }

}