<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Sitedisplay {
	
	public function __construct (){
		$this->_ci =& get_instance();
		$this->_ci->load->library(array('upload'));
	}
	
	private function sitedisplay() {
		$CI =& get_instance();
		$this->author = "Amy Varga";
		$this->keywords = "Absolute Orange Ltd, Web development only better, London, United Kingdom, front end web developer";
		$this->copyright = '&copy; 2006 - '.date('Y').' Absolute Orange Ltd All Rights Reserved';
		$this->navigation=array();
    }
	
    public function makepage($data) {
    	$CI =& get_instance();
		$page=array();
		$headerData['author']=$this->author;
		$headerData['keywords']=$this->keywords;
		$headerData['copyright']=$this->copyright;
		$headerData['title']=$data['heading'];
        $headerData['handheld']=$data['handheld'];
		$page['header']=$this->_ci->load->view("header_view", $headerData, true);
        $page['logo']=$this->_ci->load->view("logo_view", array(), true);
        $browser = $_SERVER['HTTP_USER_AGENT'];
        $CI->load->view("main", $page);
    }
}
?>
