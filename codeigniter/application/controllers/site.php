<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Controller for legacy site
 * @author amyvarga
 *
 * @package		Legacy
 *
 */
class Site extends CI_Controller {

    public function __construct() {
            parent::__construct();
            // Your own constructor code

            $this->load->library(array('displaycontent', 'templateparser'));
            $this->load->helper('url');
            $this->load->model(array('profiles'));
            $this->version='1.2';
    }

    /**
	 * Displays home view
	 */
	public function index() {
	    $this->home();
	}

	public function home() {
        $developers = $this->getAllProfiles();
	    $data = array(
            'contentPanels' => array (
                '0' => array(
                    'content'=> $this->templateparser->parseTemplate('profiles.html', $developers)
                )
            )
        );
	    $container=$this->templateparser->parseTemplate('layout/container.html',$data );
        $title = "Absolute Orange : our developers";
	    $this->displaycontent->display($container, $title, false);
	}


	/**
	 *Displays all profiles
    */

	function getAllProfiles() {
		$data['items'] = $this->profiles->getAll();
		foreach ($data['items'] as $key => $item):
			$id = $item['employee_id'];
			$data['items'][$key]['name'] = $this->profiles->getName($id);
            $data['items'][$key]['employee_url'] = str_replace(' ', '', $data['items'][$key]['name']->employee_name);
            $data['items'][$key]['skills'] = array();
            $data['items'][$key]['skills'] = $this->getEmployeeSkills($id, $data['items'][$key]['skills'], 'expertise');
            $data['items'][$key]['skills'][0]['name'] = ucfirst($data['items'][$key]['skills'][0]['name']);
		endforeach;

		return $data;
	}

    /**
     *
     * Returns employee skills based on the showcases they have submitted
     *
     * @param $id = employee id
     * @param $array = array to put skills in
     * @param $skill = 'skills' OR 'programs' OR 'software' OR 'frameworks' OR 'expertise'
     */
    function getEmployeeSkills($id, $array, $strSkill) {
        $showcases = $this->profiles->getShowcases($id);
        $employeeSkills = array();
        foreach ($showcases as $showcase):
            $skills = $this->profiles->getSkills($showcase['showcase_id'], $strSkill);
            if ($strSkill == 'expertise'):
                $col = 'expertise';
            else:
                $col = 'name';
            endif;
            foreach ($skills as $skill):
                if (!in_array($skill[$col], $employeeSkills)):
                    $employeeSkills[] = $skill[$col];
                endif;
            endforeach;
        endforeach;
        foreach ($employeeSkills as $skill):
            $array[] = array(
                'name'=>$skill
            );
        endforeach;
        sort($array);
        $array[count($array)-1]['last'] = true;
        return $array;
    }

	public function fileAPIUpload() {
		$this->load->library(array('admin/form_validation', 'admin/common_methods'));
		$fn = (isset($_SERVER['HTTP_X_FILENAME']) ? $_SERVER['HTTP_X_FILENAME'] : false);
		if (isset($fn) AND $this->common_methods->validateFile($fn)):
			echo $fn;
		endif;
	}
}
?>