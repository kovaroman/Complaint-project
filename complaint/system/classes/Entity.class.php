<?php defined('CHECK') OR exit('No direct script access allowed');
/*-------------------------------------------------------
*
*   Roman Kovalyshyn
*   Copyright © 2016
*
*---------------------------------------------------------
*/
/*
 * Entity class for creating objects with loaded parameters
 */
class ModuleEntity extends Module {
	private $_aData=array();


	public function __construct($aParam = false) {
		$this->_setData($aParam);
		$this->Init();
	}

	public function Init() {

	}
	
	public function __set($sName, $value) {
        $this->_aData[$sName] = $value;
    }

    public function __get($sName) {
		if (array_key_exists($sName, $this->_aData)) {
            return $this->_aData[$sName];
        }
	}
	
	public function _setData($aData) {
		if(is_array($aData)) {
			foreach ($aData as $sKey => $val)    {
				$this->_aData[$sKey] = $val;
			}
		}
	}

	public function _getData($aKeys=array()) {
		if(!is_array($aKeys) or !count($aKeys)) return $this->_aData;

		$aReturn=array();
		foreach ($aKeys as $key) {
			if(array_key_exists($key, $this->_aData)) {
				$aReturn[$key] = $this->_aData[$key];
			}
		}
		return $aReturn;
	}
}
?>