<?php defined('CHECK') OR exit('No direct script access allowed');
/*-------------------------------------------------------
*
*   Roman Kovalyshyn
*   Copyright © 2016
*
*---------------------------------------------------------
*/

/*
 * Controller of /user/ url
 */
class ControllerUser extends Controller {
	
	private $bUserLogin = null;
	protected $oModel = null;
	protected $aRules = array('username' => array(
											'/^[a-z0-9_-]{3,16}$/i',
											"",
											''),
							'password' => array(
											'/^[a-z0-9_-]{6,16}$/i',
											"",
											'')
							);

	public function Init() {
		if( ! isset($_SESSION['login']) || ! $_SESSION['login']){
			$this->bUserLogin = false;
		} else {
			$this->bUserLogin = true;
		}
	}
	/*
	 * Login page method
	 */
	public function login() {
	
		if($this->bUserLogin === true){
			Router::Location(base_url());
		}
		
		$bErr = false;
		$sUsername = getRequest('username', null, 'post');
		$sPassword = getRequest('password', null, 'post');
		$sCheck = getRequest('check', null, 'post');
		// validation
		if (!$this->Validate()){
			$bErr = true;
			$this->oViwer->GetView('header', array('bUserLogin' => false, 'title' => 'Книга скарг - Логін'));
			$this->oViwer->GetView('login', array('err' => $bErr));
			$this->oViwer->GetView('footer');
			return false;
		}
		// check usename and password
		if($sUsername && $sPassword && $sCheck === 'login_form'){
			
			$this->oModel = $this->oEngine->LoadModel('ModelUser');
			$oUser = $this->oModel->GetUserByLogin($sUsername);

			if($oUser && $oUser->user_password === md5($sPassword)){
				$_SESSION['user'] = $oUser->_getData();
				$_SESSION['login'] = true;
				unset($_SESSION['user']['user_password']);
				$oUser->date_login = date("Y-m-d H:i:s");
				$this->oModel->UpdateUser($oUser);
				Router::Location(base_url('/complain'));
			} else {
				$bErr = true;
			}
		}
		// show login page
		$this->oViwer->GetView('header', array('bUserLogin' => false, 'title' => 'Книга скарг - Логін'));
		$this->oViwer->GetView('login', array('err' => $bErr));
		$this->oViwer->GetView('footer');
	}
	/*
	 * Logout method
	 */
	public function logout(){
		if($this->bUserLogin == true){
			unset($_SESSION['user']);
			$_SESSION['login'] = false;
			$this->bUserLogin = false;
		}
		Router::Location(base_url());
	}
}