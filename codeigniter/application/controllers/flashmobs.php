<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Flashmobs extends CI_Controller {
	public function index(){
		$this->home();
	}
	
	public function home()
	{	
		$this->load->view('flashmobs/flashmobs');
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */