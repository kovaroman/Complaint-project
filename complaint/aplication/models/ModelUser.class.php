<?php defined('CHECK') OR exit('No direct script access allowed');
/*-------------------------------------------------------
*
*   Roman Kovalyshyn
*   Copyright © 2016
*
*--------------------------------------------------------
*/

class ModelUser extends Model {

	public function GetUserByLogin($sLogin) {
		$sql = "SELECT * FROM ".Config::Get('db.table.users')."	WHERE user_name = ? ";
		if ($aRow = $this->oDb->selectRow($sql, $sLogin)) {
			$oUser = $this->oEngine->LoadModule('ModuleEntity', $aRow);
			return $oUser;
		}
		return null;
	}
	
	public function UpdateUser($oUser) {
		$sql = "UPDATE ".Config::Get('db.table.users')."
			SET
				user_name = ?,
				user_password = ? ,
				date_login = ? ,
				user_role = ? 
			WHERE id = ?d
		";
		if ($this->oDb->query($sql, $oUser->user_name,
							 $oUser->user_password,
							 $oUser->date_login,
							 $oUser->user_role,
							 $oUser->id)) {
			return true;
		}
		return false;
	}
}
?>