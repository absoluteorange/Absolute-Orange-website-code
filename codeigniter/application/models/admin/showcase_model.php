<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class Showcase_model extends CI_Model {
	
    function Showcase_model() {
        parent::__construct();	
    }
    
    function getIdFromTitle ($title) {
    	$sql = "Select id from showcase where title = '$title'";
    	$query= $this->db->query($sql);
    	return $query->first_row()->id;
    }
	
	function getShowcases($employeeId){
		$sql="SELECT * ,
				DATE_FORMAT(date_started, '%M  %Y') as formatted_start_date, 
				DATE_FORMAT(date_completed, '%M  %Y') as formatted_end_date 
				FROM showcase 
				JOIN employee_showcase on showcase.id=employee_showcase.showcase_id
				WHERE employee_id=$employeeId 
				ORDER BY title desc";
		$query= $this->db->query($sql);
		return $query->result_array();		
	}
		
	function getShowcase($title) {
		$title=$this->db->escape($title);
		$sql="SELECT * from showcase WHERE title = $title";
		$query= $this->db->query($sql);
		return $query->first_row();
	}
	
	function getItems ($table) {
		$sql = "SELECT * from $table ORDER BY name asc";
		$query= $this->db->query($sql);
		return $query->result_array();
	}
	
	function getSelectedItems($table, $showcaseId) {
		$relatedTable = 'showcase_'.$table;
		$id = $table.'_id';
		$sql="SELECT $table.name, $table.id FROM $table LEFT JOIN showcase_$table ON $table.id = $relatedTable.$id WHERE showcase_id=$showcaseId ORDER BY name asc";
		$query= $this->db->query($sql);
		return $query->result_array();	
	}
	
	function getImages($showcaseId) {
        $sql = "select * from showcase_images where showcase_id = $showcaseId";
        $sql.= " ORDER BY order_index asc";
        $query = $this->db->query($sql);
        return $query->result_array();
    }
    
	function getLogos($showcaseId) {
         $sql = "select * from showcase_logos where showcase_id = $showcaseId";
         $query = $this->db->query($sql);
         return $query->result_array();
    }
    
	function getRelatedLinks($showcaseId) {
         $sql = "select * from showcase_relatedlinks where showcase_id = $showcaseId";
         $query = $this->db->query($sql);
         return $query->result_array();
    }
    
	function getFileName ($table, $id) {
      	$sql = "select * from $table where id = $id";
        $query = $this->db->query($sql);
        return $query->first_row()->image_url;
    }
}
?>
