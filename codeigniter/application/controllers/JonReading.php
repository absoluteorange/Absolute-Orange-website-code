<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Controller for legacy site
 * @author amyvarga
 *
 * @package		Legacy
 *
 */
class JonReading extends CI_Controller {

    public function __construct() {
            parent::__construct();
            // Your own constructor code

            $this->load->library(array('ProfileDisplay'));
            $this->version='1.2';
            $this->employeeId = 2;
            $this->employeeName = 'Jon Reading';
            $this->employeeUrl = 'JonReading';
    }

    /**
	 * Displays home view
	 */
	public function index() {
	    $this->displayHome();
	}

    public function displayHome() {
        $this->profiledisplay->home($this->employeeName, $this->employeeId, $this->employeeUrl);
    }

    public function work() {
        $this->profiledisplay->work($this->employeeName, $this->employeeId, $this->employeeUrl);
    }
    
    public function labs() {
        $this->profiledisplay->labs($this->employeeName, $this->employeeId, $this->employeeUrl);
    }
    
    public function lab() {
        $this->profiledisplay->lab($this->employeeName, $this->employeeId, $this->employeeUrl);
    }

}
?>
