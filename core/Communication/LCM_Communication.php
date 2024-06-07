<?php

class LCMCommunication{

    private $arrApis;

    public function __construct($config)
    {
        error_log("Communication Loaded!!");
        $this->arrApis = $config->GetCommunicationParams('Apis');
    }

    /*
        Realiza la petición en función de como este parametrizado en el config    
    */
    public function DoRequest($apiName, $arrValueVars=null){
        foreach($this->arrApis as $key => $api){
            if($key === $apiName){
                $url = $this->CompleteUrl($api["UrlApi"], $arrValueVars);
                
                switch ($api["Method"]){
                    case "GET":{
                        return $this->DoGetRequest($url);
                    }
                }
            }
        }
    }

    public function GetPublicInfo(){
        return array(
            "Apis" => $this->arrApis,
            "state" => "active"
        );
    }

    /**
     * Hace una petición GET con o sin parámetros
     */
    public function DoGetRequest($url){
        $cURL = curl_init();
        curl_setopt($cURL, CURLOPT_URL, $url);
        curl_setopt($cURL, CURLOPT_HTTPGET, true);
        curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true); //para que devuelva el json en el $result
        curl_setopt($cURL, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Accept: application/json'
        ));
        $result = curl_exec($cURL);
        curl_close($cURL);
        return $result;
    }

    /**
     * Hace una petición POST con o sin parámetros
     */
    public function DoPostRequest($url, $arrParams=null){
        $jsonParams = json_encode($arrParams);

        $cURL = curl_init();
        curl_setopt($cURL,CURLOPT_URL, $url);
        curl_setopt($cURL,CURLOPT_POST, 1); //establece el metodo POST
        curl_setopt($cURL,CURLOPT_POSTFIELDS, $jsonParams); //Campos enviados en el POST
        curl_setopt($cURL,CURLOPT_RETURNTRANSFER, true); //para que devuelva el json en el $result 
        //$headers = [
        //    'User-Agent: Mozilla/5.0 (X11; Ubuntu; Linux i686; rv:28.0) Gecko/20100101 Firefox/28.0',
        //];
        //curl_setopt($cURL, CURLOPT_HTTPHEADER, $headers);
        
        $result = curl_exec($cURL);
        curl_close($cURL);
        return $result;
    }

    /*
        Completo la Url con los valores que hacen falta en tiempo de ejecución
    */
    private function CompleteUrl($url, $arrValueVars){
        $urlResponse = $url;        
        foreach($arrValueVars as $key => $value){
            $urlResponse = str_replace("#".$key."#",$value, $urlResponse);
        }
        return $urlResponse;
    }
}
