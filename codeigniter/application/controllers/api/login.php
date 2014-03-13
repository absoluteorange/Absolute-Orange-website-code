<?php defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH.'/libraries/Validation_Controller.php';

class Login extends Validation_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('webapp/Usersmodel');
		$this->load->library('encrypt');
		$this->load->library('form_validation');
		$this->load->library('session');
		$this->load->helper('cookie');
		$this->load->library('myformvalidator');
		$this->load->library('mycommonutilities');
	}
	
	public function validate_post() {
		$fields = array('email', 'password', 'csrf_secure');
		if ($_POST) {
		} else {
			$_POST = $this->myformvalidator->processData($fields);
		}
		$loginData['errors'] = array();
		if ($this->form_validation->run('login') == TRUE) {
			$dbUser = $this->Usersmodel->get_user($_POST['email']);
			if (!empty($dbUser)) {
				$storedPassword = $this->encrypt->decode($dbUser[0]['password']);
				if ($_POST['password'] == $storedPassword) {
					$this->mycommonutilities->setSession(array('authenticated' => true));
					$this->response(NULL, 200);
				} else {
					$this->response(NULL, 400);
				}
			} else {
				$this->response(NULL, 404);
			}
		} else {
			$loginData['errors'] = $this->myformvalidator->sendErrors($loginData['errors'], $_POST);
    	}
    }  
}
?>