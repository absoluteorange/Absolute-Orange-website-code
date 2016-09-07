<?php defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH.'/libraries/REST_Controller.php';

/**
 * CodeIgniter Validation Controller
 *
 * A validation controller to hold all callback validation methods
 *
 */
abstract class Validation_Controller extends REST_Controller {
	public function __construct() {
		parent::__construct();
		$this->_ci =& get_instance();
		$this->_ci->load->library('myformvalidator');
		$this->_ci->load->library('mycommonutilities');
		$this->_ci->lang->load('form_validation', 'english');
	}

	/**
	 * Validates the password
	 * @param string $password
	 * @return Boolean
	 */
	public function checkPassword ($password) {
		if (preg_match('/[A-Z]+/', $password) && preg_match('/[0-9]+/', $password)) {
			return true;
		} else {
			$this->form_validation->set_message('checkPassword', $this->_ci->lang->line('password_invalid'));
			return false;
		}
	}

	/**
	 * Checks form value is not a default value
	 * @param String $str form value
	 * @return Boolean
	 */
	public function checkDefault ($str) {
		if (strstr($str,'Your')) {
			$this->form_validation->set_message('checkDefault', 'Please enter a %s');
			return false;
		} else {
			return true;
		}
	}

	/**
	 * Validates csrf number
	 * @param int $integer
	 * @return Boolean
	 */
	public function checkCSRF ($integer) {
		/*$csrf = $this->_ci->mycommonutilities->getSessionData('csrf');
		if ($integer == $csrf) {
			return true;
		} else {
			$this->_ci->form_validation->set_message('csrf',   $this->_ci->lang->line('insecure'));
			return false;
		}*/
		return true;
	}

	/***************************/
	/*   DATA VALIDATION      */
	/*************************/
	/**
	 * Validates base64 encoded data for mime type and filesize
	 * @param base64 encoded data $data
	 * @return Boolean
	 *
	 */
	public function dataValidate ($data) {
		$f = finfo_open();
		$mime_type = finfo_buffer($f, $data, FILEINFO_MIME_TYPE);
		$acceptableFormats = array('image/jpeg', 'image/png', 'image/gif');
		foreach ($acceptableFormats as $format) {
			if (!$mime_type = $format) {
				echo 'dataValidate mime type error';
				$this->response(array('error' => '400'), 400);
				return false;
			}
		}
		$file_size = strlen(rtrim($data, '='));
		if ($file_size > 13500000) {
			$this->_ci->response(array('error' => '400'), 400);
			return false;
		}
		return true;
	}

	/**
	 * Validates base64 decoded data
	 * @param base64 decoded data $data
	 * @return Boolean
	 *
	 */
	public function data_validate_decoded ($data) {
		/*if ($this->_ci->security->xss_clean($data, TRUE)) {
			return true;
		} else {
			$this->_ci->response(array('error' => '400'), 400);
			return false;
		}*/
		return true;
	}

	/**
	 * Validates filename
	 * @param string $filename
	 * @return Boolean
	 */
	public function sanitiseFilename ($filename) {
		//echo 'sanitise';
		if ($this->_ci->security->sanitize_filename($filename) == $filename) {
			return true;
		} else {
			//echo 'sanitise filename error';
			$this->_ci->response(array('error' => '111'), 111);
			return false;
		}
	}
}
