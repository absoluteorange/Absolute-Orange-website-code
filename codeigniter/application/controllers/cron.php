<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cron extends CI_Controller {
	
	 public function __construct() {
     	parent::__construct();
     	$this->input->is_cli_request();
	 }
	 
	 private function twitterCron () {
	 	//site_url('auth/oauth/twitter');
	 }
	 
}