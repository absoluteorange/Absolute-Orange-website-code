<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class Content extends CI_Model {
	
    function Content() {
        // Call the Model constructor
        parent::__construct();
    }
    
    function getHome() {
    	$sql = "SELECT section_content
				FROM website_sections
				WHERE section_name = 'home'";
       $query= $this->db->query($sql);
       return $query->first_row();	
    }
}