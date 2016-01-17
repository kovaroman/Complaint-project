<?php defined('CHECK') OR exit('No direct script access allowed');
/*-------------------------------------------------------
*
*   Roman Kovalyshyn
*   Copyright © 2016
*
*---------------------------------------------------------
*/

/*
 * Main config file
 */
$config['view']['name'] = 'Книга скарг';
$config['view']['mode'] = 1;	// 1 - open list for nonauthorized; 2 - only form for adding complain for nonauthorized, list only for authorized
$config['view']['per_page'] = 5;

/*
 * DB settings
 */
$config['db']['params']['lazy'] = false;
$config['db']['params']['host'] = 'localhost';
$config['db']['params']['port'] = '3306';
$config['db']['params']['user'] = 'root';
$config['db']['params']['pass'] = '';
$config['db']['params']['type']   = 'mysql'; // mysql, mysqli, mypdo, postgresql, mssql, sqlite, ibase
$config['db']['params']['dbname'] = 'test';
/*
 * DB tables settings
 */
$config['db']['table']['prefix'] 	  = 'iroom_';
$config['db']['table']['complaints']  = '___db.table.prefix___complaints';
$config['db']['table']['users']  	  = '___db.table.prefix___users';
$config['db']['tables']['engine'] 	  = 'InnoDB';
/*
 * Path settings
 */
$config['path']['offset_request_url'] = '0';
$config['path']['root']['web']        = 'http://'.$_SERVER['HTTP_HOST'];
$config['path']['root']['server']     = dirname(dirname(__FILE__));
$config['path']['root']['system']     = '___path.root.server___/system';
$config['path']['root']['aplication']     = '___path.root.server___/aplication';
$config['path']['root']['views']     = '___path.root.aplication___/views';
/*
 * Router settings
 */
$config['router']['page']['index']   = 'ControllerIndex';
$config['router']['page']['error']   = 'ControllerError';
$config['router']['page']['user']    = 'ControllerUser';
$config['router']['page']['complain']    = 'ControllerComplain';
$config['router']['default']         = 'index';
$config['router']['not_found']       = 'error';
/*
 * Autoload modules settings
 */
$config['module']['autoLoad'] = array(); // module names only
/*
 * Captcha settings
 */
$config['captcha']['key'] = '6LdW-sdfs';
$config['captcha']['secret'] = '6LdW-sdfsfs';


return $config;
?>
