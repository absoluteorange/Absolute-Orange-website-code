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
		if ($this->form_validation->run('login') == TRUE) {
			$user = $this->Usersmodel->get_user($_POST['email']);
			if (empty($user)) {
				$this->response(array('error' => 'user does not exist'), 404);
			} else {
				if ($_POST['password'] == $this->encrypt->decode($user[0]['password'])) {
					$this->response(array('success' => 'registered'), 200);
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
