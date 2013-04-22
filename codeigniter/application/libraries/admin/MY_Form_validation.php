<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * CodeIgniter Validation Controller
 *
 * A validation controller to hold all callback validation methods
 *
 */
class MY_Form_validation extends CI_Form_validation {
	
	public function __construct($config = array()) {
		parent::__construct($config);
		$this->_ci =& get_instance();
		$this->_ci->load->library(array('form_validation', 'uri', 'security'));	
		$this->_ci->lang->load('form_validation', 'english');
	}
	
	/**
	 * Validates filename
	 * @param string $filename
	 * @return Boolean
	 */
	public function _sanitiseFilename ($name, $filename) {
		if ($this->_ci->security->sanitize_filename($filename) == $filename) {
			return true;
		} else {
			$this->_ci->form_validation->$name = array('filename' => $this->_ci->lang->line('harmful_filename'));
			return false;
		}
	}	
	
	/**
	 * 
	 * Check that document format exists
	 * @param string $filename
	 * return Boolean
	 */
	public function _formatExists ($name, $filename, $dir) {
		if (file_exists($dir.'/'.$filename)):
			$this->_ci->form_validation->$name = array('exists' => $this->_ci->lang->line('format_exists'));
			return TRUE;
		else:
			return FALSE;
		endif;
	}
	
	public function _checkExists($name, $table) {
		if ($table == 'showcase'):
			$data = array('title' => $name);
		elseif ($table == 'expertise'):
			$data = array('expertise' => $name);
			$table = 'skills';
		else:
			$data = array('name' => $name);
		endif;
		$this->_ci->db->where($data);
		$query=$this->_ci->db->get($table);
		if ($query->num_rows() > 0):
			$this->_ci->form_validation->set_message('_checkExists', $this->_ci->lang->line('already_exists'));
			return FALSE;
		else:
			return TRUE;
		endif;
	}
	
	public function _validUrl ($related_link) {
		$pattern = "/^(http|https|ftp):\/\/([A-Z0-9][A-Z0-9_-]*(?:\.[A-Z0-9][A-Z0-9_-]*)+):?(\d+)?\/?/i";
		if (!preg_match($pattern, $related_link)):
    		$this->_ci->form_validation->set_message('_validUrl', $this->_ci->lang->line('valid_url'));
    		return FALSE;
    	else:
    		return TRUE;
    	endif;
	}
	
	/**
	 * 
	 * Check that the string contains the delimiter
	 * @param string $filename
	 * @param string $delimiter
	 * return Boolean
	 */
	
	public function _reqDelimiter ($name, $delimiter) {
		if (strpos($name, $delimiter) === false):
			$this->_ci->form_validation->set_message('_reqDelimiter',  $this->_ci->lang->line('required_delimiter'));
			return false;
		else:
			return true;
		endif;
	}
}