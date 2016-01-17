<?php defined('CHECK') OR exit('No direct script access allowed');
/*-------------------------------------------------------
*
*   Roman Kovalyshyn
*   Copyright © 2016
*
*--------------------------------------------------------
*/

/*
 * Model of Complain fo working with DB
 */
class ModelComplain extends Model {

	public function AddComplain($oComplain) {
		$sql = "INSERT INTO ".Config::Get('db.table.complaints')."
			(
			user_name,
			user_email,
			user_web,
			user_message,
			user_ip,
			user_browser,
			date_add
			)
			VALUES( ?,  ?,	?,	?,	?,	?,	? )
		";
		if ($iId = $this->oDb->query($sql, $oComplain->user_name,
									 $oComplain->user_email,
									 $oComplain->user_web,
									 $oComplain->user_message,
									 $oComplain->user_ip,
									 $oComplain->user_browser,
									 $oComplain->date_add)) {
			return $iId;
		}
		return false;
	}

	public function UpdateComplain($oComplain) {
		$sql = "UPDATE ".Config::Get('db.table.complaints')."
			SET
				user_name = ?,
				user_email = ? ,
				user_web = ? ,
				user_message = ? ,
				date_edit = ?
			WHERE id = ?
		";
		if ($this->oDb->query($sql,$oComplain->user_name,
							 $oComplain->user_email,
							 $oComplain->user_web,
							 $oComplain->user_message,
							 $oComplain->date_edit,
							 $oComplain->id)) {
			return true;
		}
		return false;
	}
	
	public function DeleteComplain($sId) {
		$sql = "DELETE FROM ".Config::Get('db.table.complaints')." WHERE id = ?d ";
		if ($aRow=$this->oDb->selectRow($sql,$sId)) {
			return true;
		}
		return false;
	}
	
	public function GetComplainsByFilter($aFilter, $aOrder, $iOffset = 0, $iLimit = null) {
		$aOrderAllow=array('id', 'user_name','user_email','date_add');
		$sOrder='';
		$sLimit=null;
		foreach ($aOrder as $key=>$value) {
			if (!in_array($key,$aOrderAllow)) {
				unset($aOrder[$key]);
			} elseif (in_array($value,array('asc','desc'))) {
				$sOrder.=" {$key} {$value},";
			}
		}
		$sOrder=trim($sOrder,',');
		if ($sOrder=='') {
			$sOrder=' date_add asc ';
		}
		if ($iLimit || $iLimit && $iOffset) {
			$sLimit = trim($iOffset.','.$iLimit, ',');
		}

		$sql = "SELECT
					*
				FROM
					".Config::Get('db.table.complaints')."
				WHERE
					1 = 1
					{ AND id = ?d }
					{ AND user_name = ? }
					{ AND user_email = ? }
					{ AND user_web = ? }
					{ AND user_ip = ? }
					{ AND date_add = ? }
					{ AND date_edit = ? }
				ORDER by {$sOrder}
				LIMIT {$sLimit}
					";
		$aComplains = array();
		if ($aRows=$this->oDb->select($sql,
										  isset($aFilter['id']) ? $aFilter['id'] : DBSIMPLE_SKIP,
										  isset($aFilter['user_name']) ? $aFilter['user_name'] : DBSIMPLE_SKIP,
										  isset($aFilter['user_email']) ? $aFilter['user_email'] : DBSIMPLE_SKIP,
										  isset($aFilter['user_web']) ? $aFilter['user_web'] : DBSIMPLE_SKIP,
										  isset($aFilter['user_ip']) ? $aFilter['user_ip'] : DBSIMPLE_SKIP,
										  isset($aFilter['date_add']) ? $aFilter['date_add'] : DBSIMPLE_SKIP,
										  isset($aFilter['date_edit']) ? $aFilter['date_edit'] : DBSIMPLE_SKIP
		)) {
			foreach ($aRows as $aRow) {
				$oComplain = $this->oEngine->LoadModule('ModuleEntity', $aRow);
				$aComplains[]=$oComplain;
			}
		}
		return $aComplains;
	}

	public function GetComplainById($iId) {
		$sql = "SELECT
				*
			FROM
				".Config::Get('db.table.complaints')."
			WHERE
				id = ?d ";
		if ($aRow=$this->oDb->selectRow($sql, $iId)) {
			$oComplain = $this->oEngine->LoadModule('ModuleEntity', $aRow);
			return $oComplain;
		}
		return null;
	}

	public function GetCountAll($aFilter = null){
		$sql = "SELECT
					id
				FROM
					".Config::Get('db.table.complaints')."
				WHERE
					1 = 1
					{ AND id = ?d }
					{ AND user_name = ? }
					{ AND user_email = ? }
					{ AND user_web = ? }
					{ AND user_ip = ? }
					{ AND date_add = ? }
					{ AND date_edit = ? }
				";
		if ($aRows = $this->oDb->select($sql,
										  isset($aFilter['id']) ? $aFilter['id'] : DBSIMPLE_SKIP,
										  isset($aFilter['user_name']) ? $aFilter['user_name'] : DBSIMPLE_SKIP,
										  isset($aFilter['user_email']) ? $aFilter['user_email'] : DBSIMPLE_SKIP,
										  isset($aFilter['user_web']) ? $aFilter['user_web'] : DBSIMPLE_SKIP,
										  isset($aFilter['user_ip']) ? $aFilter['user_ip'] : DBSIMPLE_SKIP,
										  isset($aFilter['date_add']) ? $aFilter['date_add'] : DBSIMPLE_SKIP,
										  isset($aFilter['date_edit']) ? $aFilter['date_edit'] : DBSIMPLE_SKIP
		)) {
		}
		return count($aRows);
	}

}
?>