<?php defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH.'/libraries/Validation_Controller.php';

class Users extends Validation_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('webapp/Usersmodel');
		$this->load->library('encrypt');
		$this->load->library('form_validation');
		$this->load->library('myformvalidator');	
		$this->load->library('mycommonutilities');
	}
    
	public function register_post () {
    	if ($this->form_validation->run('register') == TRUE) {
	    	$user = $this->Usersmodel->get_user($_POST['email']);
	    	if (empty($user)) {
	    		$this->Usersmodel->create_user($_POST['username'], $_POST['email'], $this->encrypt->encode($_POST['password']));
	    		$this->response(array('success' => 'registered'), 200);
	    	} else {
	    		$this->response(array('error' => 'already registered'), 400);
	    	}
    	} else {
            $arrErrors = $this->myformvalidator->sendErrors();
			$this->response(array('errors' => $arrErrors), 403);
    	}
    }
}
?>
