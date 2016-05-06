<?php defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH.'/libraries/Validation_Controller.php';

class Login extends Validation_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('webapp/Usersmodel');
		$this->load->library('encrypt');
		$this->load->library('form_validation');
		$this->load->library('myformvalidator');
		$this->load->library('mycommonutilities');
	}
	
	public function validate_post() {
        if (!$_POST){
            $fields = array('email', 'password', 'csrf_secure');
            $this->myformvalidator->processData($fields);
        }
		if ($this->form_validation->run('login') == TRUE) {
			$user = $this->Usersmodel->get_user($_POST['email']);
			if (empty($user)) {
				$this->response(array('error' => 'user does not exist'), 404);
			} else {
				if ($_POST['password'] == $this->encrypt->decode($user[0]['password'])) {
                    $sessionData = array('user-name' => $user[0]['name'], 'authenticated' => true);
                    $this->mycommonutilities->setSession($sessionData);
                    $cookieData = array('user-name' => $user[0]['name'], 'authenticated' => 'true');
                    $this->mycommonutilities->setCookies($cookieData);
					$this->response(array('success', 200));
				} else {
					$this->response(array('error', 'incorrect password'), 400);
				}
			}
		} else {
            $arrErrors = $this->myformvalidator->sendErrors();
			$this->response(array('error' => $arrErrors), 403);
    	}
    }  
}
?>
