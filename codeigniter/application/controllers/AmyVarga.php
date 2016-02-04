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

            $this->load->library(array('displaycontent', 'templateparser', 'twitter'));
            $this->load->helper('url');
            $this->load->model(array('blogs', 'showcases', 'profiles', 'content'));
            $this->version='1.2';
            $this->employeeId = 1;
            $this->employeeName = 'Amy Varga';
            $this->employeeUrl = 'AmyVarga';

            /*$this->load->library('wurfl');
			$this->wurfl->load('USER_AGENT_HERE');
			var_dump($this->wurfl->getAllCapabilities());
			echo 'is wireless device = '.$this->wurfl->getCapability('is_wireless_device');
			echo 'is handheld friendly = '.$this->wurfl->getCapability('handheldfriendly');
            echo 'is dual orientation = '.$this->wurfl->getCapability('dual_orientation');*/
    }

    /**
	 * Displays home view
	 */
	public function index() {
	    $this->home();
	}

	public function home() {
	    $data = array(
            'contentPanels' => array (
                '0' => array(
                    'content'=>$this->getHome()
                )
            )
        );
	    $container=$this->templateparser->parseTemplate('layout/container.html',$data );
        $title = $this->employeeName."'s labs";
	    $this->displaycontent->display($container, $title, true);
	}

	public function getHome() {
        $data = new stdClass();
        $data->blogs = $this->getAllLabs();       
        $data->title = $this->employeeName."'s labs";
		return $this->templateparser->parseTemplate('home.html', $data);
	}

	/**
	 * Display work
	 */

	public function work() {
		$data = array(
	    		'contentPanels' => array (
	    			'0' => array(
	    				'content'=>$this->getAllShowcases($this->employeeId))
	   				)
	   			);
	    $container = $this->templateparser->parseTemplate('layout/container.html',$data );
        $title = $this->employeeName."'s work";
	    $this->displaycontent->display($container, $title, true);
	}

	/**
	 *Displays all labs
	 */
	function getAllLabs() {
		$data['items'] = $this->blogs->getAll($this->employeeId);
		foreach ($data['items'] as $key => $item):
            $data['description'] = $item['description'];
		endforeach;
        $data['employeeUrl'] = $this->employeeUrl;
		return $this->templateparser->parseTemplate('labs.html', $data);
	}

	/**
	 *Displays lab
	 */
	function Lab() {
        $name = urldecode($this->uri->segment(3));
		$lab = $this->blogs->getBlog($name);
        $data = array(
            'contentPanels' => array (
                '0' => array(
                    'content'=> $this->templateparser->parseTemplate('lab.html', $lab)
                )
            )
        );
        $container=$this->templateparser->parseTemplate('layout/container.html', $data);
        $title = $name;
        $this->displaycontent->displayLab($container, $title);
	}

	/**
	 *Displays all showcases
	 */
	function getAllShowcases() {
        $this->title= $this->employeeName."'s work";
		if ($this->uri->segment(3)):
	    	$name = urldecode($this->uri->segment(3));
	    	$showcase = $this->getShowcase($name);
	    endif;
		$data['items'] = $this->showcases->getAll($this->employeeId);
		foreach ($data['items'] as $key => $item):
			$data['items'][$key]['logos'] = $this->showcases->getLogos($item['id']);
			foreach ($data['items'][$key]['logos'] as $thisKey => $logo):
				$arrImgUrl = explode('.', $logo['image_url']);
				$format = $arrImgUrl[1];
				if ($format == 'png' OR $format == 'gif'):
					$data['items'][$key]['logos'][$thisKey]['class'] = 'transparent';
				endif;
			endforeach;
			$data['items'][$key]['skills'] = $this->showcases->getSkills($item['id']);
			$data['items'][$key]['skills'][0]['expertise'] = ucfirst($data['items'][$key]['skills'][0]['expertise']);
			$data['items'][$key]['skills'][count($data['items'][$key]['skills'])-1]['last'] = true;
			if (isset($name) AND $item['title'] == $name):
				$data['items'][$key]['showcase'] = $showcase;
				$data['items'][$key]['class']="selected";
				$data['items'][$key]['anchor']="selected-content";
			endif;
		endforeach;
        $data['employeeName'] = $this->employeeName;
        $data['employeeUrl'] = $this->employeeUrl;
		return $this->templateparser->parseTemplate('showcases.html', $data);
	}

	/**
	 *Displays showcase
	 */
	function getShowcase($name) {
		$data = $this->showcases->getShowcase($name);
		$data->logo = $this->showcases->getLogo($data->id);
		$data->developer = $this->showcases->getDeveloper($data->id);
		$data->skillset = $this->showcases->getSkills($data->id);
		if(property_exists ($data,'technical')){
			$data->technical->languages = $this->showcases->getLanguages($data->id);
			$data->technical->software = $this->showcases->getSoftware($data->id);
			$data->technical->frameworks = $this->showcases->getFrameworks($data->id);
		}
		
        $data->relatedlinks = $this->showcases->getRelatedlinks($data->id);
		$data->images = $this->showcases->getImages($data->id);
		return $this->templateparser->parseTemplate('showcase.html', $data);
	}

}
?>
