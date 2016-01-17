<?php defined('CHECK') OR exit('No direct script access allowed');
/*-------------------------------------------------------
*
*   Roman Kovalyshyn
*   Copyright © 2016
*
*---------------------------------------------------------
*/

/*
 * Engine class
 */
set_include_path(get_include_path().PATH_SEPARATOR.dirname(__FILE__));
// load main classes
require_once("Controller.class.php");
require_once("Module.class.php");
require_once("Router.class.php");
require_once("Model.class.php");

class Engine {
	
	static protected $oInstance = null;
	protected $aModules = array();
	protected $aModulesInfo = array();
	protected $aConfigModule = array();
	
	protected function __construct() {
		if (get_magic_quotes_gpc()) {
			func_stripslashes($_REQUEST);
			func_stripslashes($_GET);
			func_stripslashes($_POST);
			func_stripslashes($_COOKIE);
		}
	}
	/*
	 * Only one instance
	 */
	static public function getInstance() {
		if (isset(self::$oInstance) and (self::$oInstance instanceof self)) {
			return self::$oInstance;
		} else {
			self::$oInstance= new self();
			return self::$oInstance;
		}
	}
	/*
	 * lock cloning
	 */
	protected function __clone() {

	}
	/*
	 * Loading modules from autoload config
	 */
	public function Init() {
		$this->LoadModules();
		$this->InitModules();
	}
	
	protected function LoadModules() {
		$this->LoadConfig();
		foreach ($this->aConfigModule['autoLoad'] as $sModuleName) {
			$sModuleClass = 'Module'.$sModuleName;

			if (!isset($this->aModules[$sModuleClass])) {
				$this->LoadModule($sModuleClass);
			}
		}
	}

	protected function LoadConfig() {
		$this->aConfigModule = Config::Get('module');
	}
	/*
	 * Loading module by name
	 */
	public function LoadModule($sModuleClass, $constructor = null, $bInit=false) {
		if ($sModulePath = $this->GetModulePath($sModuleClass)) {
			require_once($sModulePath);
		} else if (!$sModulePath || !class_exists($sModuleClass)){
			throw new RuntimeException(sprintf('Class "%s" not found!', $sModuleClass));
		}
		if ($constructor) {
			$oModule = new $sModuleClass($constructor);
		} else {
			$oModule = new $sModuleClass($this);
		}
		$this->aModules[$sModuleClass] = $oModule;
		if ($bInit) {
			$this->InitModule($oModule);
		}
		return $oModule;
	}
	/*
	 * Get path to module file, user modules rewrites system modules
	 */
	protected function GetModulePath($sModuleClass){
		$sModuleFilename = $sModuleClass.'.class.php';
		$sSysModuleDir=Config::get('path.root.system').'/modules/';
		$sUsrModuleDir=Config::get('path.root.aplication').'/modules/';
		
		if (file_exists($sUsrModuleDir.$sModuleFilename)){
			return $sUsrModuleDir.$sModuleFilename;
		} else if (file_exists($sSysModuleDir.$sModuleFilename)){
			return $sSysModuleDir.$sModuleFilename;
		} else {
			return false;
		}
	}
	/*
	 * Loading model by name
	 */
	public function LoadModel($sModelClass) {
		if ($sModelPath = $this->GetModelPath($sModelClass)) {
			require_once($sModelPath);
		} else if (!$sModelPath || !class_exists($sModelClass)){
			throw new RuntimeException(sprintf('Class "%s" not found!', $sModelClass));
		}

		$oModel = new $sModelClass($this);
		return $oModel;
	}
	/*
	 * Get path to model file, only user models
	 */
	protected function GetModelPath($sModelClass){
		$sModelFilename = $sModelClass.'.class.php';
		$sUsrModelDir=Config::get('path.root.aplication').'/models/';
		
		if (file_exists($sUsrModelDir.$sModelFilename)){
			return $sUsrModelDir.$sModelFilename;
		} else {
			return false;
		}
	}
	/*
	 * Init module
	 */
	protected function InitModule($oModule){
		$oModule->Init();
		$oModule->SetInit();
	}
	/*
	 * Init all modules
	 */
	protected function InitModules() {
		foreach ($this->aModules as $oModule) {
			if(!$oModule->isInit()) {
				$this->InitModule($oModule);
			}
		}
	}
	/*
	 * Last tasks before end
	 */
	public function Shutdown() {
		$this->ShutdownModules();
	}
	
	protected function ShutdownModules() {
		foreach ($this->aModules as $sKey => $oModule) {
			$oModule->Shutdown();
		}
	}
}

?>