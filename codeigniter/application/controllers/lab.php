<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * XHR endpoint for retrieving lab code
 * @author amyvarga
 *
 *
 */
class Lab extends CI_Controller {

    public function __construct() {
            parent::__construct();

            $this->load->model(array('blogs'));
    }

    public function getCode() {
        var_dump($this->blogs->getCode($_GET['name']));
    }
}
?>
