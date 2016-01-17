<?php defined('CHECK') OR exit('No direct script access allowed');
/*-------------------------------------------------------
*
*   Roman Kovalyshyn
*   Copyright © 2016
*
*---------------------------------------------------------
*/

/*
 * Controller of /complain/ url
 */
class ControllerComplain extends Controller {
	private $bUserLogin = null;
	private $sMes = null;
	private $bMesError = null;
	protected $bUseCaptcha = false;
	// validation rules
	protected $aRules = array('name' => array(
											'/^[А-Яа-яєїіa-zA-Z(\s){0,2}]{2,35}$/ui',
											"Ім'я повинне складатись лише з текстових символів в кількості від 2 до 25",
											'required'),
							'email' => array(
											'/^([a-z0-9_\.-]+)@([a-z0-9_\.-]+)\.([a-z\.]{2,6})$/i',
											"Введіть коректний емейл",
											'required'),
							'web' => array(
											'/^(https?:\/\/)?([\w\.]+)\.([a-z]{2,6}\.?)(\/[\w\.]*)*\/?$/i',
											"Введіть корректний веб адрес"),
							'mesg' => array(
											'/^[0-9А-Яа-яєїіa-zA-Z-"\'@!\.\/(\s){0,4}]{10,700}$/ui',
											"Текст повідомлення має складатись від 10 до 700 текстових символів",
											'required'),
							'g-recaptcha-response' => array(
											'/^(.+){1,500}$/ui',
											"Ви не пройшли перевірку CAPTCHA",
											'required')
							);

	protected $oModel = null;
	// pagination links
	protected $aLinks = array();
	
	/*
	 * General actions needed befor starting
	 */
	public function Init() {
		$this->SetDefaultEvent('ShowList');

		if( ! isset($_SESSION['login']) || ! $_SESSION['login']){
			$this->bUserLogin = false;
		} else {
			$this->bUserLogin = true;
		}
		$this->oModel = $this->oEngine->LoadModel('ModelComplain');
	}
	
	/*
	 * ajax complain add method
	 */
	public function add_new() {
		if (!isAjaxRequest()) {
			exit('No direct script access allowed');
		}
		// captcha needed
		$this->bUseCaptcha = true;
		if (!$this->Validate()){
			// captcha name rewrite (container id was the same that captcha needs in another container)
			foreach ($this->aErrors as $key => $aError) {
				if ($aError['name'] == 'g-recaptcha-response') {
					$this->aErrors[$key]['name'] = 'g-recaptcha';
				}
			}
			// validate err response
			$aResponse['status'] = "ERR";
			$aResponse['errors'] = $this->aErrors;
			echo json_encode($aResponse);
			return false;
		}
		// create object of Complain with new data and send it to DB
		$oComplain = $this->oEngine->LoadModule('ModuleEntity', array(	'user_name' => getRequest('name', null, 'post'),
																		'user_email' => getRequest('email', null, 'post'),
																		'user_web' => getRequest('web', null, 'post'),
																		'user_message' => htmlspecialchars(strip_tags(getRequest('mesg', null, 'post'))),
																		'user_ip' => func_getIp(),
																		'user_browser' => htmlspecialchars($_SERVER['HTTP_USER_AGENT']),
																		'date_add' => date("Y-m-d H:i:s"),
																		'date_edit' => null,
		));
		if ($iId = $this->oModel->AddComplain($oComplain)) {
			// success response
			$aResponse['status'] = "OK";
			echo json_encode($aResponse);
			return true;
		}
		exit('System error');
	}
	
	/*
	 * ajax complain edit method
	 */
	public function edit() {
		if (!isAjaxRequest()) {
			exit('No direct script access allowed');
		}
		// send object of complain for editing
		if ($this->bUserLogin !== false && !getRequest('type', null, 'post') && is_numeric(getRequest('id', null, 'post')) && $oComplain = $this->oModel->GetComplainById(getRequest('id', null, 'post'))) {
			$aResponse['status'] = "OK";
			$aResponse['id'] = $oComplain->id;
			$aResponse['name'] = $oComplain->user_name;
			$aResponse['email'] = $oComplain->user_email;
			$aResponse['web'] = $oComplain->user_web;
			$aResponse['message'] = $oComplain->user_message;
			echo json_encode($aResponse);
			return true;
		// save object of complain after editing
		} else if ($this->bUserLogin !== false && getRequest('type', null, 'post') == 'edit' && is_numeric(getRequest('id', null, 'post')) && $oComplain = $this->oModel->GetComplainById(getRequest('id', null, 'post'))) {
			// not req captcha
			$this->aRules['g-recaptcha-response'][2] = '';
			$this->bUseCaptcha = false;
			if (!$this->Validate()){
				// validate err response
				$aResponse['status'] = "ERR";
				$aResponse['errors'] = $this->aErrors;
				echo json_encode($aResponse);
				return false;
			}
			$oComplain->user_name = getRequest('name', null, 'post');
			$oComplain->user_email = getRequest('email', null, 'post');
			$oComplain->user_web = getRequest('web', null, 'post');
			$oComplain->user_message = htmlspecialchars(strip_tags(getRequest('mesg', null, 'post')));
			$oComplain->date_edit = date("Y-m-d H:i:s");
			if ($this->oModel->UpdateComplain($oComplain)) {
				// success response
				$aResponse['status'] = "OK";
				echo json_encode($aResponse);
				return true;
			}
		}
		exit('System error');
	}
	
	/*
	 * ajax complain delete method
	 */
	public function delete() {
		if (!isAjaxRequest()) {
			exit('No direct script access allowed');
		}
		// check user and complain for deleting
		if ($this->bUserLogin !== false && is_numeric(getRequest('id', null, 'post')) && $oComplain = $this->oModel->GetComplainById(getRequest('id', null, 'post'))) {
			$this->oModel->DeleteComplain($oComplain->id);
			$aResponse['status'] = "OK";
			echo json_encode($aResponse);
			return true;
		// if unsuccessfull check return
		} else {
			// unsuccessfull response
			$aResponse['status'] = "ERR";
			echo json_encode($aResponse);
			return false;
		}
	}
	
	/*
	 * Show complain journal method with pagination
	 */
	public function ShowList($iPage = 1) {
		if (Config::Get('view.mode') === 2 && $this->bUserLogin === false){
			Router::Location(base_url());
		}
		$aFilter = array();
		// set sort order
		if (isset($_COOKIE['sort'])) {
			$aCookies = json_decode($_COOKIE['sort'], true);
			$aViewOrder = array($aCookies['type'] => $aCookies['direction']);
			switch ($aCookies['type']) {
				case 'name':
					$sSortField = 'user_name';
					break;
				case 'email':
					$sSortField = 'user_email';
					break;
				case 'date':
					$sSortField = 'date_add';
					break;
				default:
					$sSortField = 'date_add';
					break;
			}
			
			switch ($aCookies['direction']) {
				case 'up':
					$sDirection = 'asc';
					break;
				case 'down':
					$sDirection = 'desc';
					break;
				default:
					$sDirection = 'desc';
					break;
			}
			$aOrder = array($sSortField => $sDirection, 'id' => 'desc');
		} else {
			$aViewOrder = array('date' => 'down');
			$aOrder = array('date_add' => 'desc', 'id' => 'desc');
		}
		// set pagination
		$iCount = $this->oModel->GetCountAll();
		$iPerPage = Config::Get('view.per_page');
		$this->BuildPages($iCount, $iPage, $iPerPage);
		$iOffset = ($iPage - 1) * $iPerPage;
		// get complains
		$aComplains = $this->oModel->GetComplainsByFilter($aFilter, $aOrder, $iOffset, $iPerPage = 5);
		// set numbers
		$i = ($iPage - 1) * $iPerPage;
		foreach ($aComplains as $oComplain){
			$i++;
			$oComplain->number = $i;
		}
		// view complains
		$this->oViwer->GetView('header', array('bUserLogin' => $this->bUserLogin, 'menu_item' => 'complain', 'title' => 'Журнал повідомлень'));
		$this->oViwer->GetView('complains_list', array('aViewOrder' => $aViewOrder,'bUserLogin' => $this->bUserLogin,'aLinks' => $this->aLinks, 'aComplains' => $aComplains, 'title' => 'Журнал повідомлень'));
		$this->oViwer->GetView('footer');
	}
	
	/*
	 * Some page of complain journal method
	 */
	public function page() {
		$this->ShowList((int)$this->aParams[0]);
	}
	
	/*
	 * Pagination method
	 */
	private function BuildPages($iCount, $iPage = 1, $iPerPage) {
		$iPages = ceil($iCount / $iPerPage);
		$iPageActive = '';
		for($i=1; $i<=$iPages; $i++){
			($i==$iPage) ? $iPageActive = true : $iPageActive = false;
			if ($i==1) {
				$this->aLinks[] = array('active' => $iPageActive, 'link' => base_url('complain'), 'title' => '<<');
				$this->aLinks[] = array('active' => $iPageActive, 'link' => base_url('complain'), 'title' => $i);
			} else if ($i==$iPages) {
				$this->aLinks[] = array('active' => $iPageActive, 'link' => base_url('complain/page/'.$i), 'title' => $i);
				$this->aLinks[] = array('active' => $iPageActive, 'link' => base_url('complain/page/'.$i), 'title' => '>>');
			} else {
				$this->aLinks[] = array('active' => $iPageActive, 'link' => base_url('complain/page/'.$i), 'title' => $i);
			}
		}
	}
	
	/*
	 * Validating method
	 */
	protected function Validate() {
		$bParVal = parent::Validate();
		
		if ($bParVal === false) {
			return false;	
		} else if ($bParVal === true) {
			if ($this->bUseCaptcha === true) {
				$data = http_build_query(array('secret' => Config::Get('captcha.secret'), 'response' => getRequest('g-recaptcha-response', null, 'post'), 'remoteip' => func_getIp()));
				$opts = array(
					'http'=>array(
						'method'=>"POST",
						'header'=>"Content-type: application/x-www-form-urlencoded\r\n"
									. "Content-Length: " . strlen($data) . "\r\n",
						'content' => $data
					)
				);
				$context = stream_context_create($opts);
				$response = file_get_contents('https://www.google.com/recaptcha/api/siteverify', false, $context);
				$response = json_decode($response);
				if ($response->success === true) {
					return true;
				}
				$aErrors[] = array('name' => 'g-recaptcha', 'text' => 'Ви не пройшли перевірку CAPTCHA');
				return false;
			}
			return true;
		}
	}
	
	/*
	 * If something is needed when finishing script
	 */
	public function EventShutdown() {

	}
}
?>
