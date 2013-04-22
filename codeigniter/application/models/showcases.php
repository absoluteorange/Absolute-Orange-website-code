<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class Showcases extends CI_Model {
	
    function Showcases() {
        // Call the Model constructor
        parent::__construct();
    }
    
    function getAll() {
        $sql = "SELECT title, id, deliverable, DATE_FORMAT(date_completed, '%a, %D %M  %Y') as date
				FROM showcase
				ORDER BY date_completed desc";
        $query= $this->db->query($sql);
        return $query->result_array();
    }
    
	function getLogos($id) {
    	$sql = "SELECT *
				FROM showcase_logos
				WHERE showcase_id = '$id'";
        $query= $this->db->query($sql);
        return $query->result_array();
    }
    
	function getSkills($id) {
    	$sql = "SELECT expertise
				FROM skills
				JOIN showcase_skills ON skills.id=showcase_skills.skills_id
				WHERE showcase_id = '$id'";
        $query= $this->db->query($sql);
        return $query->result_array();
    }
    
	function getShowcase($name) {
       $sql = "SELECT *,
       			DATE_FORMAT(date_completed, '%a, %D %M  %Y') as date
				FROM showcase
				WHERE title = '$name'";
       $query= $this->db->query($sql);
       return $query->first_row();
    }
    
	function getLogo($id) {
    	$sql = "SELECT *
				FROM showcase_logos
				WHERE showcase_id = '$id'";
        $query= $this->db->query($sql);
        return $query->first_row();
    }
    
    function getDeveloper($id){
    	$sql = "SELECT employee_name
				FROM employees
				JOIN employee_showcase ON employees.employee_id=employee_showcase.employee_id
				WHERE showcase_id = '$id'";
        $query= $this->db->query($sql);
        return $query->first_row();
    }
    
	function getPrograms($id){
    	$sql = "SELECT name
				FROM programs
				JOIN showcase_programs ON programs.id=showcase_programs.programs_id
				WHERE showcase_id = '$id'";
        $query= $this->db->query($sql);
        return $query->result_array();
    }
    
	function getSoftware($id){
    	$sql = "SELECT name
				FROM software
				JOIN showcase_software ON software.id=showcase_software.software_id
				WHERE showcase_id = '$id'";
        $query= $this->db->query($sql);
         return $query->result_array();
    }
    
	function getFrameworks($id){
    	$sql = "SELECT name
				FROM frameworks
				JOIN showcase_frameworks ON frameworks.id=showcase_frameworks.frameworks_id
				WHERE showcase_id = '$id'";
        $query= $this->db->query($sql);
        return $query->result_array();
    }
    
    function getImages($id){
    	$sql = "SELECT *
				FROM showcase_images WHERE showcase_id = '$id'
    			ORDER BY order_index desc";
        $query= $this->db->query($sql);
        return $query->result_array();
     
    }
}