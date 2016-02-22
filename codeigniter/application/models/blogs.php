<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class Blogs extends CI_Model {
	
    function Blogs() {
        // Call the Model constructor
        parent::__construct();
    }
    
    function getAll($employeeId) {
        $join = '';
        if (isset($employeeId)) {
            $join = "JOIN employee_blog ON blog.id=employee_blog.blog_id WHERE employee_id=$employeeId";
        }
        /*$sql = "SELECT name, id, DATE_FORMAT(date_completed, '%a, %D %M  %Y') as date
				FROM blog
				".$join."
				ORDER BY date_completed desc";*/
        $sql = "SELECT name, id, description
				FROM blog
				".$join."
				ORDER BY date_completed desc";
        $query= $this->db->query($sql);
        return $query->result_array();
    }
    
    function getAuthor($id) {
    	$sql = "SELECT employee_name FROM employees    			
    			JOIN employee_blog on employees.employee_id=employee_blog.employee_id
    			WHERE blog_id = $id";
    	$query = $this->db->query($sql);
    	return $query->first_row();
    }
    
	function getBlog($name) {
       $sql = "SELECT * ,
       			DATE_FORMAT(date_completed, '%a, %D %M  %Y') as date
				FROM blog
				WHERE name = '$name'";
       $query= $this->db->query($sql);
       return $query->first_row();
    }
	
    function getCode($name) {
       $sql = "SELECT code
				FROM blog
				WHERE name = '$name'";
       $query= $this->db->query($sql);
       return $query->first_row();
    }
    
    function getLogo($id) {
    	$sql = "SELECT *
				FROM blog_logos
				WHERE blog_id = '$id'";
        $query= $this->db->query($sql);
        return $query->first_row();
    }
    
	function getLinks($id) {
    	$sql = "SELECT * FROM blog_relatedlinks    			
    			WHERE blog_id = $id";
    	$query = $this->db->query($sql);
    	return $query->result_array();
    }
    
	function getImages($id) {
    	$sql = "SELECT * FROM blog_images  			
    			WHERE blog_id = $id
    			ORDER BY order_index asc";
    	$query = $this->db->query($sql);
    	return $query->result_array();
    }
    
}
?>
