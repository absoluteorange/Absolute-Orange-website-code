<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Myformvalidator {
	
	private $_ci;
	
	public function __construct() {
		$this->_ci =& get_instance();
		$this->_ci->load->library('session');
		$this->_ci->lang->load('form_validation', 'english');
	}
	
	/**
	 * Creates $_POST from returned data
	 * @param array $fields
	 * @return $_POST
	 */
	public function processData ($fields) {
		$post = json_decode(file_get_contents('php://input'));
		foreach ($fields as $field) {
			if ($post->$field != '') {
				$_POST[$field] = $post->$field;
			}
		}
		return $_POST;
	}
	
	/**
	 * Collates and send errors from CI form validation
	 * @param array $array
	 *
	 */
	public function sendErrors() {
        $arrErrors = array();
		foreach ($_POST as $key => $value) {
			if (form_error($key) != '') {
				$arrErrors[$key] = form_error($key);
			}
		}
		if (!empty($arrErrors)) {
			return $arrErrors;
		} 
	}
}
