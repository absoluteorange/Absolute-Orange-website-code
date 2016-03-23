<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Controller for legacy site
 * @author amyvarga
 *
 * @package		Legacy
 *
 */
class AmyVarga extends CI_Controller {

    public function __construct() {
            parent::__construct();
            // Your own constructor code

            $this->load->library(array('ProfileDisplay'));
            $this->version='1.2';
            $this->employeeId = 1;
            $this->employeeName = 'Amy Varga';
            $this->employeeUrl = 'AmyVarga';
    }

    /**
	 * Displays home view
	 */
	public function index() {
        $url = site_url('/AmyVarga/work');
        header('Location: ' . $url, true, 301);
	    $this->work();
	}

    public function work() {
        $this->profiledisplay->work($this->employeeName, $this->employeeId, $this->employeeUrl);
    }
    
    public function labs() {
        $this->profiledisplay->labs($this->employeeName, $this->employeeId, $this->employeeUrl);
    }
    
    public function lab() {
        $this->profiledisplay->lab($this->employeeName);
    }

}
?>
