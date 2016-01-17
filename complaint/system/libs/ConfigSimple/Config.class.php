<?php
/*-------------------------------------------------------
*
*   Roman Kovalyshyn
*   Copyright © 2016
*
*---------------------------------------------------------
*/

class Config {
	/**
	 * Config Instance
	 *
	 * @var array
	 */
	static protected $oInstance;
	/**
	 * Store for configuration entries for current instance
	 *
	 * @var object
	 */
	protected $aConfig=array();

	/**
	 * Disabled construct process
	 */
	protected function __construct() {

	}

	/**
	 * Only one instance
	 *
	 * @static
	 * @return Config
	 */
	static public function getInstance() {
		if (!(self::$oInstance instanceof self)) {
			self::$oInstance = new self();
		} 
		return self::$oInstance;
	}
	/**
	 * Load configuration array from file
	 *
	 * @static
	 * @param string $sFile		Path to file
	 * @return bool|Config
	 */
	static public function LoadFromFile($sFile,$bRewrite=true) {
		// Check if file exists
		if (!file_exists($sFile)) {
			return false;
		}
		// Get config from file
		$aConfig=include($sFile);
		// Set config
		self::getInstance()->SetConfig($aConfig);
		return self::getInstance();
	}
	/**
	 * Sets values of config
	 *
	 * @param array $aConfig	Config array
	 * @return bool
	 */
	public function SetConfig($aConfig=array()) {
		if (is_array($aConfig)) {
			$this->aConfig=$aConfig;
			return true;
		}
		$this->aConfig=array();
		return false;
	}
	/**
	 * Returns current config
	 *
	 * @return array
	 */
	public function GetConfig() {
		return $this->aConfig;
	}
	/**
	 * Retrive information from configuration array
	 *
	 * @param  string $sKey      Key
	 * @return mixed
	 */
	static public function Get($sKey='') {
		// Return all config array
		if($sKey=='') {
			return self::getInstance()->GetConfig();
		}

		// Return config by path (separator=".")
		$aKeys=explode('.',$sKey);

		$cfg=self::getInstance()->GetConfig();
		foreach ((array)$aKeys as $sK) {
			if(isset($cfg[$sK])) {
				$cfg=$cfg[$sK];
			} else {
				return null;
			}
		}

		$cfg = self::KeyReplace($cfg);
		return $cfg;
	}
	/**
	 * Replaces placeholders of keys in config values
	 *
	 * @static
	 * @param string|array $cfg	Config keys
	 * @return array|mixed
	 */
	static public function KeyReplace($cfg) {
		if(is_array($cfg)) {
			foreach($cfg as $k=>$v) {
				$k_replaced = self::KeyReplace($k);
				if($k==$k_replaced) {
					$cfg[$k] = self::KeyReplace($v);
				} else {
					$cfg[$k_replaced] = self::KeyReplace($v);
					unset($cfg[$k]);
				}
			}
		} else {
			if(preg_match_all('~___([\S|\.]+)___~Ui',$cfg,$aMatch,PREG_SET_ORDER)) {
				foreach($aMatch as $aItem) {
					$cfg=str_replace('___'.$aItem[1].'___',Config::Get($aItem[1]),$cfg);
				}
			}
		}
		return $cfg;
	}
	/**
	 * Try to find element by given key
	 * Using function ARRAY_KEY_EXISTS (like in SPL)
	 *
	 * Workaround for http://bugs.php.net/bug.php?id=40442
	 *
	 * @param  string $sKey      Path to needed value
	 * @return bool
	 */
	static public function isExist($sKey='') {
		// Return all config array
		if($sKey=='') {
			return (count((array)self::getInstance()->GetConfig())>0);
		}
		// Analyze config by path (separator=".")
		$aKeys=explode('.',$sKey);
		$cfg=self::getInstance()->GetConfig();
		foreach ((array)$aKeys as $sK) {
			if (array_key_exists($sK, $cfg)) {
				$cfg=$cfg[$sK];
			} else {
				return false;
			}
		}
		return true;
	}
	/**
	 * Add information in config array by handle path
	 *
	 * @param  string $sKey	Key
	 * @param  mixed $value	Value
	 * @return bool
	 */
	static public function Set($sKey,$value) {
		$aKeys=explode('.',$sKey);

		if(isset($value['$root$']) && is_array($value['$root$'])){
			$aRoot = $value['$root$'];
			unset($value['$root$']);
			foreach($aRoot as $sRk => $mRv){
				self::Set(
					$sRk,
					self::isExist($sRk)
						? func_array_merge_assoc(Config::Get($sRk), $mRv)
						: $mRv
				);
			}
		}

		$sEval='self::getInstance()->aConfig';
		foreach ($aKeys as $sK) {
			$sEval.='['.var_export((string)$sK,true).']';
		}
		$sEval.='=$value;';
		eval($sEval);
		return true;
	}
	/**
	 * Find all keys recursivly in config array
	 *
	 * @return array
	 */
	public function GetKeys() {
		$cfg=$this->GetConfig();
		// If it`s not array, return key
		if(!is_array($cfg)) {
			return false;
		}
		// If it`s array, get array_keys recursive
		return $this->func_array_keys_recursive($cfg);
	}
	/**
	 * Merges assocc arrays
	 *
	 * @param array $aArr1	Array
	 * @param array $aArr2	Array
	 * @return array
	 */
	protected function ArrayEmerge($aArr1,$aArr2) {
		return $this->func_array_merge_assoc($aArr1,$aArr2);
	}
	/**
	 * Recursive array_keys
	 *
	 * @param  array $array	Array
	 * @return array
	 */
	protected function func_array_keys_recursive($array) {
		if(!is_array($array)) {
			return false;
		} else {
			$keys = array_keys($array);
			foreach ($keys as $k=>$v) {
				if($append = $this->func_array_keys_recursive($array[$v])){
					unset($keys[$k]);
					foreach ($append as $new_key){
						$keys[] = $v.".".$new_key;
					}
				}
			}
			return $keys;
		}
	}
	/**
	 * Merges two assocc arrays
	 *
	 * @param array $aArr1	Array
	 * @param array $aArr2	Array
	 * @return array
	 */
	protected function func_array_merge_assoc($aArr1,$aArr2) {
		$aRes=$aArr1;
		foreach ($aArr2 as $k2 => $v2) {
			$bIsKeyInt=false;
			if (is_array($v2)) {
				foreach ($v2 as $k => $v) {
					if (is_int($k)) {
						$bIsKeyInt=true;
						break;
					}
				}
			}
			if (is_array($v2) and !$bIsKeyInt and isset($aArr1[$k2])) {
				$aRes[$k2]=$this->func_array_merge_assoc($aArr1[$k2],$v2);
			} else {
				$aRes[$k2]=$v2;
			}
		}
		return $aRes;
	}
}
?>