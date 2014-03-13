<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class Photosmodel extends CI_Model {
	
	function __construct() {
		parent::__construct();
	}
	
    function getPhotos() {
            $sql = "select * from webapp_photos";
            $query = $this->db->query($sql);
            return $query->result_array();
    }
    
    function addImage($name, $alt, $format) {
    	$sql = "insert into webapp_photos (name, alt, format) values ('".$name."', '".$alt."', '".$format."')";
    	$query = $this->db->query($sql);
    	return true;
    }
}
?>