<?php defined('CHECK') OR exit('No direct script access allowed');
/*-------------------------------------------------------
*
*   Roman Kovalyshyn
*   Copyright © 2016
*
*---------------------------------------------------------
*/

/*
 * Router class
 */
class Router {

	protected $aConfigRoute=array();
	protected $oController = null;
	protected $oEngine = null;
	static protected $oInstance = null;
	static protected $sPathWebCurrent = null;
	static protected $sController = null;
	static protected $sControllerEvent = null;
	static protected $sControllerClass=null;
	static protected $aParams = array();

	static public function getInstance() {
		if (isset(self::$oInstance) and (self::$oInstance instanceof self)) {
			return self::$oInstance;
		} else {
			self::$oInstance= new self();
			return self::$oInstance;
		}
	}

	protected function __construct() {
		$this->LoadConfig();
	}
	
	/*
	 * Load routes from config
	 */
	protected function LoadConfig() {
		$this->aConfigRoute = Config::Get('router');
	}
	
	/*
	 * Start all processes
	 */
	public function Exec() {
		session_start();
		$this->ParseUrl();
		$this->DefineControllerClass();
		$this->oEngine=Engine::getInstance();
		$this->oEngine->Init();
		$this->ExecController();
		$this->Shutdown(false);
	}
	
	/*
	 * Parse url
	 */
	protected function ParseUrl() {
		$sReq = $this->GetRequestUri();
		$aRequestUrl=$this->GetRequestArray($sReq);

		self::$sController = array_shift($aRequestUrl);
		self::$sControllerEvent = array_shift($aRequestUrl);
		self::$aParams=$aRequestUrl;
	}
	
	/*
	 * Some uri check and replace
	 */
	protected function GetRequestUri() {
		$sReq=preg_replace("/\/+/",'/',$_SERVER['REQUEST_URI']);
		$sReq=preg_replace("/^\/(.*)\/?$/U",'\\1',$sReq);
		$sReq=preg_replace("/^(.*)\?.*$/U",'\\1',$sReq);

		self::$sPathWebCurrent=rtrim(Config::Get('path.root.web'), '/')."/".join('/',$this->GetRequestArray($sReq));
		return $sReq;
	}
	
	/*
	 * Check if site is in subfolder
	 */
	protected function GetRequestArray($sReq) {
		$aRequestUrl = ($sReq=='') ? array() : explode('/',$sReq);
		for ($i=0;$i<Config::Get('path.offset_request_url');$i++) {
			array_shift($aRequestUrl);
		}
		$aRequestUrl = array_map('urldecode',$aRequestUrl);
		return $aRequestUrl;
	}
	
	/*
	 * Check Controlle class file and redirects 404
	 */
	protected function DefineControllerClass() {
		
		if (isset($this->aConfigRoute['page'][self::$sController])) {
		
		} elseif (self::$sController === null) {
			self::$sController = $this->aConfigRoute['default'];
		} else {
			//error 404		
			self::$sController = $this->aConfigRoute['not_found'];
			self::$sControllerEvent = 'NotFound';
		}
		self::$sControllerClass = $this->aConfigRoute['page'][self::$sController];
		return self::$sControllerClass;
	}
	
	/*
	 * Start Controller script and it's events
	 */
	public function ExecController() {
		require_once(rtrim(Config::Get('path.root.aplication'), '/').'/controllers/'.self::$sControllerClass.'.class.php');

		$sClassName = self::$sControllerClass;
		$this->oController = new $sClassName($this->oEngine);

		$sInitResult = $this->oController->Init();

		if ($sInitResult === 'next') {
			$this->ExecController();
		} else {
			$res = $this->oController->ExecEvent();
			$this->oController->EventShutdown();

			if ($res === 'next') {
				$this->DefineControllerClass();
				$this->ExecController();
			}
		}
	}
	
	/*
	 * Get url parameters
	 */
	static public function GetParams() {
		return self::$aParams;
	}
	/*
	 * Get Controller name as in config
	 */
	static public function GetController() {
		return self::$sController;
	}
	/*
	 * Get Controller event name
	 */
	static public function GetControllerEvent() {
		return self::$sControllerEvent;
	}
	/*
	 * Set Controller event name
	 */
	static public function SetControllerEvent($sEvent) {
		self::$sControllerEvent = $sEvent;
	}
	/*
	 * Get url parameter by num
	 */
	static public function GetParam($iOffset, $def = null) {
		$iOffset=(int)$iOffset;
		return isset(self::$aParams[$iOffset]) ? self::$aParams[$iOffset] : $def;
	}
	/*
	 * Set url parameter by num
	 */
	static public function SetParam($iOffset, $value) {
		self::$aParams[$iOffset] = $value;
	}
	/*
	 * Redirect method to another controller
	 */
	static public function Controller($sController, $sEvent=null, $aParams = null) {
		self::$sController = $sController;
		self::$sControllerEvent = $sEvent;
		if (is_array($aParams)) {
			self::$aParams = $aParams;
		}
		return 'next';
	}
	/*
	 * Redirect method to another URL
	 */
	static public function Location($sLocation) {
		self::getInstance()->oEngine->Shutdown();
		func_header_location($sLocation);
	}
	/*
	 * Last tasks before end
	 */
	public function Shutdown($bExit=true) {
		$this->oEngine->Shutdown();
		if ($bExit) {
			exit();
		}
	}
}
?>