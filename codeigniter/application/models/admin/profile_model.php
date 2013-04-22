<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class Profile_model extends CI_Model {
	
    function Profile_model() {
        parent::__construct();	
    }

	function getProfile($employee_id, $field = null) {
		if ($field == null):
			$sql = "select * from employees_summaries where employee_id = $employee_id";
		else:
			$sql = "select $field from employees_summaries where employee_id = $employee_id";
		endif;
		$query= $this->db->query($sql);
		return $query->first_row();	
	}
    function getContents($employee_id, $table) {
        $sql = "select * from $table WHERE employee_id = $employee_id";
        if ($table == 'employee_achievements' || $table == 'employee_skills'):
       		$sql .= " ORDER BY sort asc";
       	endif;
        $query= $this->db->query($sql);
        return $query->result_array();
    }
    
    function getCVName($id) {
    	$sql = "SELECT name FROM employee_cv WHERE id = $id";
    	$query = $this->db->query($sql);
    	return $query->first_row();
    }
}
?>