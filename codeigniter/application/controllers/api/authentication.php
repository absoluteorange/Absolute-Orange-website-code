<?php defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH.'/libraries/REST_Controller.php';

class Authentication extends REST_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->library('mycommonutilities');
	}
	
	public function authenticate_get() {
		if ($this->mycommonutilities->getSessionData('authenticated') == true) {
			$this->response(array('status' => true), 200);
		} else {
			$this->response(array('status' => false), 200);
		}
    }  
}
?>