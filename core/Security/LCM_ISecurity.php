<?php
interface LCm_ISecurity
{
    public function __construct($arrParams);

    //Primer metodo al que llamar en un controlador para que haga las validaciones iniciales.
    public function CheckSecurity($controller, $action);
}