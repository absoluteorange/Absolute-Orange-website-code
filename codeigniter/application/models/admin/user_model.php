<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class User_model extends CI_Model {

    function User_model() {
        // Call the Model constructor
        parent::__construct();
    }

    function get_all() {
    	$sql = 'select * from user';
    	$query = $this->db->query($sql);
    	return $query->result_array();
    }
    
    function get_user_details($user_id) {
        $sql = 'select * from user where user_id = "'.$user_id.'" ';
        $query= $this->db->query($sql);
        return $query->first_row();
    }

    function get_user_id ($email) {
        $sql = 'select user_id from user where email = "'.$email.'" ';
        $query= $this->db->query($sql);
        return $query->result_array();
    }
}
?>
