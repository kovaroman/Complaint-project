<?php
/*-------------------------------------------------------
*
*   Roman Kovalyshyn
*   Copyright © 2016
*
*---------------------------------------------------------
*/
error_reporting(E_ALL);
ini_set('display_errors', 1);
define('CHECK', true);

header('Content-Type: text/html; charset=utf-8');

set_include_path(get_include_path().PATH_SEPARATOR.dirname(__FILE__));
chdir(dirname(__FILE__));

require_once("./config/loader.php");
require_once(Config::Get('path.root.system')."/classes/Engine.class.php");

$oRouter = Router::getInstance();
$oRouter->Exec();

?>