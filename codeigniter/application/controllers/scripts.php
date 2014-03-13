<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Controller for JS global variables
 * @author amy varga
 *
 */

class Scripts extends CI_Controller {

	function __construct() {
		parent::__construct();
		$this->load->helper('url');
		$this->load->library('templateparser');
		$this->lang->load('form_validation', 'english');
		$this->lang->load('form', 'english');
		$this->lang->load('success', 'english');
	}
	function index(){
		
	}
	public function jsGlobals() {
	
		$this->output->set_content_type('text/javascript');		
		echo  $this->templateparser->parseTemplate('webapp/scripts/jsGlobals.html',array(
			'domain'=> site_url()
		));
	}
	
	public function jsLanguage() {
		$messages = array();
		foreach ($this->lang->language as $key=>$value) {
				$messages[$key] = $value;
		}
		$this->output->set_content_type('text/javascript');
		echo  $this->templateparser->parseTemplate('webapp/scripts/language/english/lang.js', $messages);
	}
}