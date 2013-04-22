<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Common_methods extends CI_Controller {

	public function __construct (){
		$this->_ci =& get_instance();
		$this->_ci->load->library(array('upload'));
	}
	
	public function redirectPrevPage (){
		$prevPage = $_SERVER['HTTP_REFERER'];
        redirect($prevPage);
	}
	
	public function uploadFile ($uploadConfig, $name){
		if ($this->_ci->form_validation->_sanitiseFilename($name, $_FILES[$name]['name']) == TRUE):
			foreach ($uploadConfig as $key => $value) {
				$config[$key] = $value;
			}
	        $this->_ci->upload->initialize($config);
	        if (!$this->_ci->upload->do_upload($name)):
				$this->setUploadError($name);
				return FALSE;
			else:
				return TRUE;
		endif;
		else:
			$this->setUploadError($name);
			return FALSE;
		endif;
		
	}
	
	public function setUploadError ($name) {		
		$error = array('error' => $this->_ci->upload->display_errors('',''));
		$this->_ci->form_validation->$name = $error;
	}
	
	public function validateLogo ($table) {
		$capitalisedTable = ucfirst($table);
		if ($this->_ci->form_validation->run('add'.$capitalisedTable.'Logo') == TRUE):
			$uploadConfig = array('max_height' => '100',
								  'upload_path' => './images/'.$table.'/logos',
								  'max_height' => '100',
								  'allowed_types' => 'gif|jpg|png',
								  'max_size' => '1000');
			$name = 'logo';
			if ($this->uploadFile($uploadConfig, $name)):
				return TRUE;
			else:
				return FALSE;
			endif;
		endif;
	}
	
	public function validateScreenshot ($table, $title) {
		$capitalisedTable = ucfirst($table);
		if ($this->_ci->form_validation->run('add'.$capitalisedTable.'Screenshot') == TRUE):
			$uploadConfig = array('max_height' => '600', 
								  'upload_path' => './images/'.$table,
								  'allowed_types' => 'gif|jpg|png',
								  'max_size' => '1000', 
								  'file_name' => $title.'_'.$_FILES['screenshot']['name']);
			$name = 'screenshot';
			if ($this->uploadFile($uploadConfig, $name)):
				$uploadConfig = array('max_height' => '100', 
								  'upload_path' => './images/'.$table.'/thumbnails',
								  'allowed_types' => 'gif|jpg|png',
								  'max_size' => '1000', 
								  'file_name' => $title.'_'.$_FILES['screenshot']['name']);
				$name = 'screenshot_thumb';
				if ($this->uploadFile($uploadConfig, $name)):
					return TRUE;
				else:
					return FALSE;
				endif;
			else:
				return FALSE;
			endif;
		else:
			return FALSE;
		endif;
	}
		
	public function validateRelatedLink ($table) {
		$capitalisedTable = ucfirst($table);
		if ($this->_ci->form_validation->run('add'.$capitalisedTable.'Link') == TRUE):
			return TRUE;
		endif;
	}
}