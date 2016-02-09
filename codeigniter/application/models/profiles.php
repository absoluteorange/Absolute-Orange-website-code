<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class Profiles extends CI_Model {
	
    function Profiles() {
        // Call the Model constructor
        parent::__construct();
    }
    
    function getAll() {
        $sql = "SELECT photo, years_of_experience, employee_id
				FROM employees_summaries";
        $query= $this->db->query($sql);
        return $query->result_array();
    }
    
    function getEmployeeNames() {
        $sql = "SELECT employee_name
				FROM employees";
        $query= $this->db->query($sql);
        return $query->result_array();
    }
    
	function getName($id) {
        $sql = "SELECT employee_name
				FROM employees WHERE employee_id=$id";
        $query= $this->db->query($sql);
        return $query->first_row();
    }
    
    function getShowcases($id) {
    	$sql = "SELECT showcase_id 
    			FROM employee_showcase 
    			WHERE employee_id = $id";
    	$query= $this->db->query($sql);
        return $query->result_array();
    }
    
    function getSkills($id, $table) {
    	if ($table == 'expertise'):
    		$table = 'skills';
    		$col = 'expertise';
    	else:
    		$col = 'name';
    	endif;
    	$strTableCol = $table.'_id';
    	$sql = "SELECT $col
    			FROM $table
    			JOIN showcase_$table ON $table.id=showcase_$table.$strTableCol
    			WHERE showcase_id = $id";
    	$query= $this->db->query($sql);
        return $query->result_array();
    }
    
    function getId($name) {
    	$sql = "SELECT employee_id 
    			FROM employees
    			WHERE employee_name = '$name'";
    	$query= $this->db->query($sql);
        return $query->first_row();
    }
    
    function getProfile($id) {
    	$sql = "SELECT *  
    			FROM employees_summaries
    			WHERE employee_id = $id";
    	$query= $this->db->query($sql);
        return $query->first_row();
    }
    
	function getCV($id) {
    	$sql = "SELECT *  
    			FROM employee_cv
    			WHERE employee_id = $id";
    	$query= $this->db->query($sql);
        return $query->result_array();
    }
    
	function getAchievements($id) {
    	$sql = "SELECT *  
    			FROM employee_achievements
    			WHERE employee_id = $id
    			ORDER by sort asc";
    	$query= $this->db->query($sql);
        return $query->result_array();
    }
    
	function getSkillSet($id) {
    	$sql = "SELECT *  
    			FROM employee_skills
    			WHERE employee_id = $id
    			ORDER by sort asc";
    	$query= $this->db->query($sql);
        return $query->result_array();
    }
}  
