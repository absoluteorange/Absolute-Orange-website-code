<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Controller for legacy site
 * @author amyvarga
 *
 * @package		Legacy
 *
 */
class Showcase extends CI_Controller {

    public function __construct() {
            parent::__construct();
            // Your own constructor code

            $this->load->library(array('profiledisplay'));
    }

    public function getShowcase() {
        echo($this->profiledisplay->getShowcase($_GET['name']));
    }

}
?>
