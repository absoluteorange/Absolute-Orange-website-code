<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_methods extends CI_Controller {
	
	var $userName;
	var $userEmail;
	var $CI="";
	
	public function __construct(){
		$this->_ci =& get_instance();
		$this->_ci->load->library('session');
		$this->_ci->load->model('admin/user_model');
	}
	
	public function setUserCookie($userId) {
		$user_id = $this->_ci->session->set_userdata('userId', $userId);
		$this->setUserDetails();
		return TRUE;
	}
	
	public function setUserDetails() {
		if ($this->_ci->session->userdata('userId')):
			$user = $this->_ci->user_model->get_user_details($this->_ci->session->userdata('userId'));
			$this->userName = $user->name;
			$this->userEmail = $user->email;
			return TRUE;
		else:
			redirect(site_url('admin'));
		endif;
	}
	
	public function clearUserCookie() {
		$this->_ci->session->unset_userdata('userId');
		redirect(site_url('admin'));
	}
}	