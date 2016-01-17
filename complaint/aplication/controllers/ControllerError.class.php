<?php defined('CHECK') OR exit('No direct script access allowed');
/*-------------------------------------------------------
*
*   Roman Kovalyshyn
*   Copyright © 2016
*
*---------------------------------------------------------
*/

/*
 * Controller of 404
 */
class ControllerError extends Controller {
	protected $aHttpErrors=array(
		'404' => array(
			'header' => '404 Not Found',
		),
		'503' => array(
			'header' => 'HTTP/1.1 503 Service Temporarily Unavailable',
			'header2' => 'Status: 503 Service Temporarily Unavailable',
			'header3' => 'Retry-After: 300',
		),
	);
	public function Init() {
		$this->SetDefaultEvent('index');
	}
	
	/*
	 *  Show 404 method
	 */
	public function NotFound() {
		$aHttpError=$this->aHttpErrors['404'];
		header($aHttpError['header']);
		$this->oViwer->GetView('header', array(
										'title' => '404 Not found'));
		$this->oViwer->GetView('404', array());
		$this->oViwer->GetView('footer', array());
	}
}
?>