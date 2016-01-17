<?php defined('CHECK') OR exit('No direct script access allowed');
/*-------------------------------------------------------
*
*   Roman Kovalyshyn
*   Copyright © 2016
*
*---------------------------------------------------------
*/
/*
 * Abstract class, all modules extend this
 */
abstract class Module {
	protected $oEngine = null;
	protected $bIsInit = false;

	public function __construct(Engine $oEngine) {
		$this->oEngine = $oEngine;
	}

	protected function __clone() {

	}

	abstract public function Init();

	public function Shutdown() {

	}

	public function isInit() {
		return $this->bIsInit;
	}

	public function SetInit() {
		$this->bIsInit = true;
	}
}
?>