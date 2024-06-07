<?php
/**
 * Project: Elsiem Framework
 * User: lucaschicharro
 * Date: 06/08/2019
 * Time: 21:01
 */
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, PATCH, POST, DELETE, OPTIONS");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Accept, Authorization, Origin");
header("Accept: */*");
header("Content-Type: application/json");

require 'core/Core.php';
$core = new Core("dev");
$core->main();
?>