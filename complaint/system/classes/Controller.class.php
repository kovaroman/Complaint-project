<?php defined('CHECK') OR exit('No direct script access allowed');
/*-------------------------------------------------------
*
*   Roman Kovalyshyn
*   Copyright © 2016
*
*---------------------------------------------------------
*/

/*
 * Controller class, all controllers extend this
 */
abstract class Controller {

	protected $aParams = array();
	protected $oEngine = null;
	protected $oViwer = null;
	protected $sCurrentController = null;
	protected $sCurrentEvent = null;
	protected $sDefaultEvent = 'index';
	protected $aRules = array();
	protected $aErrors = array();

	public function __construct(Engine $oEngine) {
		$this->oEngine = $oEngine;
		$this->oViwer = $oEngine->LoadModule('ModuleViewer');
		$this->sCurrentController = Router::GetController();
		$this->sCurrentEvent = Router::GetControllerEvent();
		$this->aParams = Router::GetParams();
	}
	
	abstract protected function Init();
	/*
	 * Starts event of controller (event = public method)
	 */
	public function ExecEvent() {
		if ($this->sCurrentEvent == null) {
			$this->sCurrentEvent = $this->GetDefaultEvent();
			Router::SetControllerEvent($this->sCurrentEvent);
		}
		if (method_exists($this, $this->sCurrentEvent)){
			$reflection = new ReflectionMethod($this, $this->sCurrentEvent);
			if (!$reflection->isPublic()) {
				return $this->EventNotFound();
			}
			$result = call_user_func_array(array($this, $this->sCurrentEvent),array());
			return $result;
		}
		return $this->EventNotFound();
	}
	/*
	 * Set default event name
	 */
	public function SetDefaultEvent($sEvent) {
		$this->sDefaultEvent = $sEvent;
	}
	/*
	 * Get default event name
	 */
	public function GetDefaultEvent() {
		return $this->sDefaultEvent;
	}
	/*
	 * Get url parameter by num
	 */
	public function GetParam($iOffset, $default = null) {
		$iOffset=(int)$iOffset;
		return isset($this->aParams[$iOffset]) ? $this->aParams[$iOffset] : $default;
	}
	/*
	 * Get all url parameters
	 */
	public function GetParams() {
		return $this->aParams;
	}
	/*
	 * Set url parameter by num
	 */
	public function SetParam($iOffset, $value) {
		Router::SetParam($iOffset, $value);
		$this->aParams=Router::GetParams();
	}
	/*
	 * Default 404 event
	 */
	protected function EventNotFound() {
		return Router::Controller('error','NotFound');
	}
	/*
	 * Forms validation mathod
	 */
	protected function Validate() {
		$aErrors = array();
		$aRules = $this->aRules;
		foreach ($aRules as $name => $rule) {
			if ($input = getRequest($name, null, 'post')) {
				if (!preg_match($rule[0], $input)){
					$aErrors[] = array('name' => $name, 'text' => $rule[1]);
				}
			} else if (isset($rule[2]) && $rule[2] === 'required'){
				$aErrors[] = array('name' => $name, 'text' => $rule[1]);
			}
		}
		if (!empty($aErrors)){
			$this->aErrors = $aErrors;
			return false;
		}
		return true;
	}
	/*
	 * Last tasks before end
	 */
	public function EventShutdown() {

	}
}
?>