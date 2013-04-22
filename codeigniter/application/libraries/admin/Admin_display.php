<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Admin_display {
	var $CI="";
	var $navigation=array();

	function admin_display() {
		$this->CI=& get_instance();
		$this->CI->load->helper('url');
		$this->author = "Amy Varga";
		$this->keywords = "Absolute Orange, web administration";
		$this->copyright = "Copyright &copy; 2006 - 2013 Absolute Orange All Rights Reserved";
		$this->navigation[0]=array();
		$this->navigation[0]['title']= "admin";
		$this->navigation[0]['link']= $this->CI->config->item('base_url')."admin/";
		$this->navigation[1]['title']= "showcases";
		$this->navigation[1]['link']= $this->CI->config->item('base_url')."admin/showcases";
        $this->navigation[2]['title']= "profile";
		$this->navigation[2]['link']= $this->CI->config->item('base_url')."admin/profile";
        $this->navigation[3]['title']= "blogs";
		$this->navigation[3]['link']= $this->CI->config->item('base_url')."admin/blogs";
	}
	
    function makepage($data) {
		$page=array();
		$headerData['author']=$this->author;
		$headerData['keywords']=$this->keywords;
		$headerData['copyright']=$this->copyright;
		$headerData['title']=$data['heading'];
		$page['header']=$this->CI->load->view("admin/common/header", $headerData, true);
		$page['heading']=$this->CI->load->view("admin/common/heading", $data, true);
		$page['user']=$this->CI->load->view("admin/common/user", $data, true);
		$navigationData['navigation']=$this->navigation;
		$page['navigation']=$this->CI->load->view("admin/common/navigation", $navigationData, true);
		$breadcrumbData['showcase'] = $this->getShowcaseDetails();
		$page['breadcrumbs']=$this->CI->load->view("admin/common/breadcrumb", $breadcrumbData , true);
		if ($data['showLogo'] == true):
			$page['logo']=$this->CI->load->view("admin/common/logo", $data, true);
		endif;
		$page['content']=$data['content'];
		$footerData['footer']=$this->copyright;
		$page['footer']=$this->CI->load->view("admin/common/footer", $footerData, true);
		$this->CI->load->view("admin/main",$page);
    }

    private function getShowcaseDetails () {
    	if (isset($_GET['showcase'])):
	    	$showcase = array();
	    	$showcase['name'] = $_GET['showcase'];
	    	$showcase['link'] = site_url('admin/showcase/editItem/'.$showcase['name']);
	    	return $showcase;
	    endif;    		
    }
    
}

?>
