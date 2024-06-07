<?php
interface IController
{
    public function __construct($controllerName, $action);

    public function InitializeParameters($method, $dataGet, $dataBody);

    //Primer metodo al que llamar en un controlador para que haga las validaciones iniciales.
    public function Starter($config, $security, $communication, $dbo);

    public function Finisher();
}