<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends CI_Controller {
	
	function admin() {
            parent::__construct();
            $this->load->library(array('admin/crud_methods', 'admin/common_methods', 'admin/user_methods', 'admin/admin_display', 'form_validation', 'admin/form_validation'));
	}
	
	public function index() {
		$data['heading'] = "Absolute Orange administration";
		$data['showLogo'] = true;
		if (!empty($_POST['userId'])):
			$this->user_methods->setUserCookie($this->form_validation->xss_clean($_POST['userId']));
		endif;
		if ($this->session->userdata('userId')):
			$this->user_methods->setUserDetails();
			$data['userName'] = $this->user_methods->userName;
		else:
			$data['users'] = $this->user_model->get_all();
		endif;
		$data['content'] = $this->load->view("admin/home" ,$data, true);
		$this->admin_display->makepage($data);
	}
	
	public function clear() {
		$this->user_methods->clearUserCookie();
	}
	
	public function delItem() {
		if ($this->crud_methods->delItem()):
			$this->common_methods->redirectPrevPage();
		endif;
	}
	
	public function delLink() {
		if ($this->crud_methods->delLink()):
			$this->common_methods->redirectPrevPage();
		endif;
	}
	
	public function delFile() {
		if ($this->crud_methods->delFile()):
			$this->common_methods->redirectPrevPage();
		endif;
	}
}
?>
