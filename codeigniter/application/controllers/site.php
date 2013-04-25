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

            $this->load->library(array('templateparser', 'twitter'));
            $this->load->helper('url');
            $this->load->model(array('blogs', 'showcases', 'profiles', 'content'));
            $this->version='1.2';
            
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
		$this->title = "Absolute Orange : home";
	    $data = array(
	            'contentPanels' => array (
	    			'0' => array(
	    				'content'=>$this->getHome())
	   				)
	   			);
	    $container=$this->templateparser->parseTemplate('layout/container.html',$data );
	    $this->displayContent($container);
	}
	
	public function getHome() {
		$content = $this->content->getHome();	
		$arrContent = explode(':', $content->section_content);
		$data->intro = $arrContent[0];
		for ($i=1; $i<count($arrContent)-1; $i++):
			$j = $i++;
			$data->sections[] = array(
				'content' => $arrContent[$i],
				'heading' => $arrContent[$j],
			);
		endfor;	
		return $this->templateparser->parseTemplate('home.html', $data);
	}

	/**
	 * Displays blog view
	 */
	public function blog() {
	    $this->title="Absolute Orange : our blog";
	    $data = array(
	    		'contentPanels' => array (
	    			'0' => array(
	    				'content'=>$this->getAllBlogs())
	   				)
	   			);
	    $container = $this->templateparser->parseTemplate('layout/container.html',$data );
	    $this->displayContent($container);
	}
	
	/**
	 * Display work
	 */
	
	public function work() {
		$this->title="Absolute Orange : our work";
		$data = array(
	    		'contentPanels' => array (
	    			'0' => array(
	    				'content'=>$this->getAllShowcases())
	   				)
	   			);
	    $container = $this->templateparser->parseTemplate('layout/container.html',$data );
	    $this->displayContent($container);
	}
	
	/**
	 * Display developers
	 */
	
	public function developers() {
		$this->title="Absolute Orange : our developers";
		$data = array(
	    		'contentPanels' => array (
	    			'0' => array(
	    				'content'=>$this->getAllProfiles())
	   				)
	   			);
	    $container = $this->templateparser->parseTemplate('layout/container.html',$data );
	    $this->displayContent($container);
	}
	
	/**
	 * Displays main navigation
	 */
	private function getNav() {
		$section = $this->uri->segment(1);
		
		$data['items'] = array(
			'0' => array(
				'url' => site_url(''),
				'class' => '',
				'title' => 'Home'
			),
			'3' => array(
				'url' => site_url('blog'),
				'class' => '',
				'title' => 'Our blogs'
			),
			'1' => array(
				'url' => site_url('work'),
				'class' => '',
				'title' => 'Our work'
			),
			'2' => array(
				'url' => site_url('developers'),
				'class' => '',
				'title' => 'Our developers')
		);
		ksort($data['items']);
		for ($i=0; $i<count($data['items']); $i++):
			if ($data['items'][$i]['url'] == site_url($section)):
				$data['items'][$i]['class'] = 'selected';
			endif;
		endfor;
		return $data;
	}
	
	/**
	 * Display all tweets
	 */
	function getTweets() {
		$twitter = json_decode(file_get_contents('./tweets.txt'));
		$tweets = $this->twitter->extractTweets($twitter->tweets);
		$formattedTweets = $this->twitter->formatTweets($tweets);
		return $formattedTweets;
	}
	
	/**
	 *Displays all blogs
	 */
	function getAllBlogs() {
		$this->title = "Absolute Orange : our blogs";
		if ($this->uri->segment(2)):
	    	$name = urldecode($this->uri->segment(2));
	    	$blog = $this->getBlog($name);
	    endif;
		$data['items'] = $this->blogs->getAll();
		foreach ($data['items'] as $key => $item):
			$data['items'][$key]['author'] = $this->blogs->getAuthor($item['id']);
			if (isset($name) AND $item['name'] == $name):
				$data['items'][$key]['blog'] = $blog;
			endif;
		endforeach;
		return $this->templateparser->parseTemplate('blogs.html', $data);
	}
	
	/**
	 *Displays blog
	 */
	function getBlog($name) {
		$this->title = "Absolute Orange : our blog : $name";
		$data = $this->blogs->getBlog($name);
		$data->author = $this->blogs->getAuthor($data->id);
		$data->logo = $this->blogs->getLogo($data->id);
		$data->links = $this->blogs->getLinks($data->id);
		return $this->templateparser->parseTemplate('blog.html', $data);
	}
	
	/**
	 *Displays all showcases
	 */
	function getAllShowcases() {
		$this->title = "Absolute Orange : our work";
		if ($this->uri->segment(2)):
	    	$name = urldecode($this->uri->segment(2));
	    	$showcase = $this->getShowcase($name);
	    endif;
		$data['items'] = $this->showcases->getAll();
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
			$thisKey = 0;
			foreach ($data['items'][$key]['skills'] as $thisKey => $skill):
				if ($thisKey == 0):
					$data['items'][$key]['skills'][$thisKey]['class'] = 'first';
				else:
					$data['items'][$key]['skills'][$thisKey]['class'] = '';
				endif;
			endforeach;
			$data['items'][$key]['skills'][count($data['items'][$key]['skills'])-1]['last'] = true;
			if (isset($name) AND $item['title'] == $name):
				$data['items'][$key]['showcase'] = $showcase;
			endif; 
		endforeach;
		return $this->templateparser->parseTemplate('showcases.html', $data);
	}
	
	/**
	 *Displays showcase
	 */
	function getShowcase($name) {
		$this->title = "Absolute Orange : our work : $name";
		$data = $this->showcases->getShowcase($name);
		$data->logo = $this->showcases->getLogo($data->id);
		$data->developer = $this->showcases->getDeveloper($data->id);
		$data->skillset = $this->showcases->getSkills($data->id);
		$data->technical->languages = $this->showcases->getLanguages($data->id);
		$data->technical->software = $this->showcases->getSoftware($data->id);
		$data->technical->frameworks = $this->showcases->getFrameworks($data->id);
		$data->images = $this->showcases->getImages($data->id);
		return $this->templateparser->parseTemplate('showcase.html', $data);
	}
	
	/**
	 *Displays all profiles
	 */
	function getAllProfiles() {
		$this->title = "Absolute Orange : our developers";
		if ($this->uri->segment(2)):
	    	$name = urldecode($this->uri->segment(2));
	    	$profile = $this->getProfile($name);
	    	$featureId = $this->profiles->getId($name)->employee_id;
	    endif;
		$data['items'] = $this->profiles->getAll();
		foreach ($data['items'] as $key => $item):
			$id = $item['employee_id'];
			$data['items'][$key]['name'] = $this->profiles->getName($id);
			$data['items'][$key]['skills'] = array();
			$data['items'][$key]['skills'] = $this->getEmployeeSkills($id, $data['items'][$key]['skills'], 'expertise');
			if (isset($featureId) AND $featureId == $id):
				$data['items'][$key]['profile'] = $profile;
			endif;		
		endforeach;	    
		return $this->templateparser->parseTemplate('profiles.html', $data);
	}
	
	/**
	 *Displays profile
	 */
	function getProfile($name) {
		$this->title = "Absolute Orange : our developers : $name";
		$arrId = $this->profiles->getId($name);
		$id = $arrId->employee_id;
		$data = $this->profiles->getProfile($id);
		$data->employeeId = $id;
		$data->name = $name;
		$data->skillset = array();
		$data->skillset = $this->getSkillset($id, $data->skillset);
		$data->cv = $this->profiles->getCV($id);
		$data->technical->languages = array();
		$data->technical->languages = $this->getEmployeeSkills($id, $data->technical->languages, 'languages');
		$data->technical->software = array();
		$data->technical->software = $this->getEmployeeSkills($id, $data->technical->software, 'software');
		$data->technical->frameworks = array();
		$data->technical->frameworks = $this->getEmployeeSkills($id, $data->technical->frameworks, 'frameworks');
		$data->myAchievements = $this->profiles->getAchievements($id);
		return $this->templateparser->parseTemplate('profile.html', $data);
	}
	
	/**
	 * 
	 * Post form for downloading CV
	 */
	function downloadCV(){
		$format = $_POST['format'];
		$id = $_POST['id'];
		$arrName = $this->profiles->getName($id);
		$name = str_replace(' ', '_', $arrName->employee_name);
		header('Location: '.site_url('/cv/'.$name.'.'.$format));
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
		$array[count($array)-1]['last'] = true;
		return $array;
	}
	
	function getSkillset($id, $array){
		$skillset = $this->profiles->getSkillset($id);
		foreach ($skillset as $skill):
			$arrSkill = explode(':', $skill['skills']);
			$array[] = array(
				'heading'=>$arrSkill[0],
				'content'=>$arrSkill[1]
			);
		endforeach;
		return $array;
	}
	
	/**
	 *Display specified content
	 * @param $strContent
	 * @param $isQunit
	 */
	private function displayContent($container){
        $scriptData=array(
        		'script'=>'app/main',
        		'version'=>$this->version,
        		'CDNPath'=>$this->config->item('CDNPath'),
        		'title'=> $this->title
        );
        $contactData=array(
        		'email'=>'info@absoluteorange.com',
        		'tel'=>'+44 (0)75322 75 361'
        );
        $headerData=array(
        	'heading'=>'absolute orange',
        	'strapline'=>'web development, only better'
        );
        $tweetData=$this->getTweets();
        $navData=$this->getNav();
        $footerData['copy']='&copy; 2006 - 2013 Absolute Orange Ltd, all rights reserved';
	    $headScript=$this->templateparser->parseTemplate('layout/headScript.html',$scriptData,true);
    	$header=$this->templateparser->parseTemplate('layout/logo.html',$headerData,true);
	    $nav=$this->templateparser->parseTemplate('layout/nav.html',$navData,true);
    	$contact=$this->templateparser->parseTemplate('layout/contact.html', $contactData,true);
	    $tweets=$this->templateparser->parseTemplate('layout/tweets.html',$tweetData,true);
    	$footerScript=$this->templateparser->parseTemplate('layout/footerScript.html',$scriptData,true);
	    $footer=$this->templateparser->parseTemplate('layout/footer.html',$footerData,true);
    	echo $this->templateparser->parseTemplate('layout/layout.html',array(
    	        'headScript'=>$headScript,
	    		'header'=>$header,
	    		'nav'=>$nav,
	   			'contact'=>$contact,
    			'tweets'=>$tweets,
	    		'container'=>$container,
	     		'footerScript'=>$footerScript,
    			'footer'=>$footer
	         )
	     );
	}
	
	public function fileAPIUpload() {
		if (($_FILES['upload']['size'] > 0) AND ($this->common_methods->validateFile())):
			echo $_FILES['upload']['name'];
		endif;
	}
}
?>