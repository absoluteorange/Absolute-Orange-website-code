<?php defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH.'/libraries/Validation_Controller.php';

class Users extends Validation_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('webapp/Usersmodel');
		$this->load->library('encrypt');
		$this->load->library('form_validation');
		$this->load->library('myformvalidator');	
	}
    
	public function register_post () {
    	$fields = array('username', 'email', 'password', 'csrf_secure');
    	if ($_POST) {
    	} else {
    		$_POST = $this->myformvalidator->processData($fields);
    	}
    	$registerData['errors'] = array();
    	if ($this->form_validation->run('register') == TRUE) {
	    	$dbUser = $this->Usersmodel->get_user($_POST['email']);
	    	if (empty($dbUser)) {
	    		$this->Usersmodel->create_user($_POST['username'], $_POST['email'], $this->encrypt->encode($_POST['password']));
	    		$this->response(array('success' => '200'), 200);
	    	} else {
	    		$this->response(array('error' => '400'), 400);
	    	}
    	} else {
    		$registerData['errors'] = $this->myformvalidator->sendErrors($registerData['errors'], $_POST);
    	}
    }
}
?>