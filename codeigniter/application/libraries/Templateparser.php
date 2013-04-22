<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include(APPPATH.'libraries/Mustache.php');

class Templateparser {

    public function __construct()
    {
        // Do something with $params
        $this->_ci =& get_instance();
        $this->_ci->load->helper('file');
        $this->strTemplatesPath="./sharedTemplates";
        $this->fileCache= array();

    }

	/**
	 * Parses template with data
	 * @param String $name	template file
	 * @param array $data
	 * @return String
	 */
	public function parseTemplate($name,$data=array()){
	    if (is_array($data)){
	         $data['CDNPath']=$this->_ci->config->item('CDNPath');
	    } else {
	        $data->CDNPath=$this->_ci->config->item('CDNPath');
	    }
	    $this->m = new Mustache;
	    if( isset($this->fileCache[$name])){
	        $string=$this->fileCache[$name];
	    }else{
	         $string = read_file( $this->strTemplatesPath.'/'.$name);
	         $this->fileCache[$name]=$string;
	    }
	    $string = read_file( $this->strTemplatesPath.'/'.$name);
	   return $this->m->render($string,$data);
	}
}
?>