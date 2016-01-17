<?php defined('CHECK') OR exit('No direct script access allowed');
/*-------------------------------------------------------
*
*   Roman Kovalyshyn
*   Copyright © 2016
*
*---------------------------------------------------------
*/
/*
 * Model class for working with DB, all models extend this
 */
abstract class Model {

	protected $oDb;
	protected $oEngine = null;

	public function __construct(Engine $oEngine) {
		$this->oEngine = $oEngine;
		$this->oDb = $this->oEngine->LoadModule('ModuleDatabase')->GetConnect();
	}

}
?>