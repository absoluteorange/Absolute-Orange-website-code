<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class Blog_model extends CI_Model {
	
    function Blog_model() {
        // Call the Model constructor
        parent::__construct();
    }
    
	function getIdFromTitle ($title) {
    	$sql = "Select id from blog where name = '$title'";
    	$query= $this->db->query($sql);
    	return $query->first_row()->id;
    }
    
    function getBlogs($employeeId) {
           $sql = "SELECT name, id
				FROM blog
				JOIN employee_blog on blog.id=employee_blog.blog_id
				where employee_id = $employeeId
				ORDER BY date_completed desc";
            $query= $this->db->query($sql);
            return $query->result_array();
    }
    
	function getBlog($title) {
            $sql = "SELECT *, 
            	DATE_FORMAT(date_completed, '%a, %D %M  %Y') as formatted_complete_date 
            	FROM blog WHERE name = '".$title."'";
            $query= $this->db->query($sql);
            return $query->first_row();
    }
	
	function getLogos($id) {
            $sql = "select * from blog_logos where blog_id = $id";
            $query = $this->db->query($sql);
            return $query->result_array();
    }
    
    function getImages($id) {
            $sql = "select * from blog_images where blog_id = $id";
            $sql.= " ORDER BY order_index asc";
            $query = $this->db->query($sql);
            return $query->result_array();
    }
    
	function getLinks($id) {
        $sql = "select * from blog_relatedlinks where blog_id = '".$id."'";
        $query = $this->db->query($sql);
        return $query->result_array();
    }
    
	function getFileName ($table, $id) {
      	$sql = "select * from $table where id = $id";
        $query = $this->db->query($sql);
        return $query->first_row()->image_url;
    }
} ?>