<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Cookie {
	var $CI="";

	function cookie() {
		$this->CI=& get_instance();
		$this->CI->load->helper('cookie');
		$this->CI->load->library("session");
        $this->CI->load->model("user_model");
		if (get_cookie('Absoluteorange') == true) {
	        $user_id = $this->CI->session->userdata('userId');
	        $user = $this->CI->user_model->get_user_details($user_id);
	        if (count($user) > 0) {
		        $user_name = $user[0]['name'];
		        $user_email = $user[0]['email'];
	        }
		}
	}	
	
	function set_a_cookie() {
		$value = uniqid (rand());
		setcookie("Absoluteorange", $value, time() + (52 * 7 * 24 * 60 * 60));
	}
}