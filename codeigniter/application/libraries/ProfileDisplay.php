<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class ProfileDisplay {
	
	public function __construct (){
		$this->_ci =& get_instance();
        $this->_ci->load->library(array('displaycontent', 'templateparser', 'twitter'));
        $this->_ci->load->helper('url');
        $this->_ci->load->model(array('blogs', 'showcases', 'profiles', 'content'));
	}
	
	public function home($employeeName, $employeeId, $employeeUrl) {
	    $this->work($employeeName, $employeeId, $employeeUrl);
    }

	/**
	 * Display work
	 */
	public function work($employeeName, $employeeId, $employeeUrl) {
        $data = array(
            'contentPanels' => array (
                '0' => array(
                    'content'=> $this->getAllShowcases($employeeName, $employeeId, $employeeUrl)
                )
            )
        );
        $container = $this->_ci->templateparser->parseTemplate('layout/container.html',$data );
        $title = $employeeName."'s work";
        $this->_ci->displaycontent->display($container, $title, true);
    }
	
    /**
	 * Display labs
	 */
	public function labs($employeeName, $employeeId, $employeeUrl) {
        $data = array(
            'contentPanels' => array (
                '0' => array(
                    'content'=> $this->getAllLabs($employeeName, $employeeId, $employeeUrl)
                )
            )
        );
        $container = $this->_ci->templateparser->parseTemplate('layout/container.html',$data );
        $title = $employeeName."'s labs";
        $this->_ci->displaycontent->display($container, $title, true);
    }

	/**
	 * Get labs
	 */
	private function getAllLabs($employeeName, $employeeId, $employeeUrl) {
		$data['items'] = $this->_ci->blogs->getAll($employeeId);
		foreach ($data['items'] as $key => $item):
            $data['description'] = $item['description'];
		endforeach;
        $data['employeeName'] = $employeeName;
        $data['employeeUrl'] = $employeeUrl;
		return $this->_ci->templateparser->parseTemplate('labs.html', $data);
	}

	/**
	 * Display lab
	 */
	public function lab() {
        $name = urldecode($this->_ci->uri->segment(3));
		$lab = $this->_ci->blogs->getBlog($name);
        $data = array(
            'contentPanels' => array (
                '0' => array(
                    'content'=> $this->_ci->templateparser->parseTemplate('lab.html', $lab)
                )
            )
        );
        $container=$this->_ci->templateparser->parseTemplate('layout/container.html', $data);
        $title = $name;
        $this->_ci->displaycontent->displayLab($container, $title);
	}

	/**
	 * Get showcases
	 */
	private function getAllShowcases($employeeName, $employeeId, $employeeUrl) {
        $this->title= $employeeName."'s work";
		if ($this->_ci->uri->segment(3)):
	    	$name = urldecode($this->_ci->uri->segment(3));
	    	$showcase = $this->getShowcase($name);
	    endif;
		$data['items'] = $this->_ci->showcases->getAll($employeeId);
		foreach ($data['items'] as $key => $item):
			$data['items'][$key]['logos'] = $this->_ci->showcases->getLogos($item['id']);
			foreach ($data['items'][$key]['logos'] as $thisKey => $logo):
				$arrImgUrl = explode('.', $logo['image_url']);
				$format = $arrImgUrl[1];
				if ($format == 'png' OR $format == 'gif'):
					$data['items'][$key]['logos'][$thisKey]['class'] = 'transparent';
				endif;
			endforeach;
			$data['items'][$key]['skills'] = $this->_ci->showcases->getSkills($item['id']);
			$data['items'][$key]['skills'][0]['expertise'] = ucfirst($data['items'][$key]['skills'][0]['expertise']);
			$data['items'][$key]['skills'][count($data['items'][$key]['skills'])-1]['last'] = true;
			if (isset($name) AND $item['title'] == $name):
				$data['items'][$key]['showcase'] = $showcase;
				$data['items'][$key]['class']="selected";
				$data['items'][$key]['anchor']="selected-content";
			endif;
		endforeach;
        $data['employeeName'] = $employeeName;
        $data['employeeUrl'] = $employeeUrl;
		return $this->_ci->templateparser->parseTemplate('showcases.html', $data);
	}

	/**
	 * Get showcase
	 */
	public function getShowcase($name) {
		$data = $this->_ci->showcases->getShowcase($name);
		$data->logo = $this->_ci->showcases->getLogo($data->id);
		$data->developer = $this->_ci->showcases->getDeveloper($data->id);
		$data->skillset = $this->_ci->showcases->getSkills($data->id);
		if(property_exists ($data,'technical')){
			$data->technical->languages = $this->showcases->getLanguages($data->id);
			$data->technical->software = $this->showcases->getSoftware($data->id);
			$data->technical->frameworks = $this->showcases->getFrameworks($data->id);
		}
		
        $data->relatedlinks = $this->_ci->showcases->getRelatedlinks($data->id);
		$data->images = $this->_ci->showcases->getImages($data->id);
		return $this->_ci->templateparser->parseTemplate('showcase.html', $data);
	}

}
?>
