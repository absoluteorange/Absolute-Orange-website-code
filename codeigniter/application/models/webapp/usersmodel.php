<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class Usersmodel extends CI_Model {
	
	function __construct() {
		parent::__construct();
	}
	
    function get_user ($email) {
        $sql = "select * from webapp_users where email = '".$email."'";
        $query = $this->db->query($sql);
        return $query->result_array();
    }
    
    function create_user ($name, $email, $password) {
    	$sql = "insert into webapp_users (name, email, password) values ('".$name."','".$email."', '".$password."')";
    	$query = $this->db->query($sql);
    	return true;
    }    
}
?>