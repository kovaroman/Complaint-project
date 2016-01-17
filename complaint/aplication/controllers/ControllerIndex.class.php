<?php defined('CHECK') OR exit('No direct script access allowed');
/*-------------------------------------------------------
*
*   Roman Kovalyshyn
*   Copyright © 2016
*
*---------------------------------------------------------
*/
/*
 * Controller of main page
 */
class ControllerIndex extends Controller {
	private $bUserLogin = null;

	public function Init() {
//		$this->SetDefaultEvent('index');

		if( ! isset($_SESSION['login']) || ! $_SESSION['login']){
			$this->bUserLogin = false;
		} else {
			$this->bUserLogin = true;
		}
	}

	public function index() {
		$this->oViwer->GetView('header', array('title' => 'Книга скарг', 'bUserLogin' => $this->bUserLogin));
		$this->oViwer->GetView('index', array());
		$this->oViwer->GetView('footer', array());
	}

	public function EventShutdown() {

	}
}
?>
