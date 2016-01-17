<?php defined('CHECK') OR exit('No direct script access allowed');
/*-------------------------------------------------------
*
*   Roman Kovalyshyn
*   Copyright © 2016
*
*---------------------------------------------------------
*/
/*
 * DataBase module to connect to DB
 */
require_once(rtrim(Config::Get('path.root.system'), '/').'/libs/DbSimple/Generic.php');

class ModuleDatabase extends Module {
	protected $aInstance=array();

	public function Init() {

	}
	/*
	 * Create connection to DB
	 */
	public function GetConnect($aConfig=null) {
		if (is_null($aConfig)) {
			$aConfig = Config::Get('db.params');
		}
		$sDSN=$aConfig['type'].'wrapper://'.$aConfig['user'].':'.$aConfig['pass'].'@'.$aConfig['host'].':'.$aConfig['port'].'/'.$aConfig['dbname'];
		$sDSNKey=md5($sDSN);

		if (isset($this->aInstance[$sDSNKey])) {
			return $this->aInstance[$sDSNKey];
		} else {
			$oDbSimple=DbSimple_Generic::connect($sDSN);
			$oDbSimple->setErrorHandler('databaseErrorHandler');
			$oDbSimple->query("set character_set_client='utf8', character_set_results='utf8', collation_connection='utf8_bin' ");
			$this->aInstance[$sDSNKey]=$oDbSimple;
			return $oDbSimple;
		}
	}

}

function databaseErrorHandler($message, $info) {

	$msg="SQL Error: $message<br>\n";
	$msg.=print_r($info,true);

	if (error_reporting() && ini_get('display_errors')) {
		exit($msg);
	}
}

?>