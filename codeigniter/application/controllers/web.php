<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Controller for webapp
 * @author amy varga
 *
 * @package  Web
 *
 */

class Web extends CI_Controller {

	  public function __construct() {
        parent::__construct();
        $this->load->library('templateparser');
        $this->load->library(array('session', 'curl', 'mycommonutilities', 'form_validation'));
		$this->load->helper(array('url', 'cookie', 'security'));
		$this->form_validation->set_error_delimiters('','');
		$this->activeSession = '';
		$this->urlCSS = '';
		$this->session();
		$this->registerUrl = site_url('/web/register');
		$this->loginUrl = site_url('/web/login');
		$this->lang->load('form', 'english');
		$this->author = "Amy Varga";
		$this->keywords = "Responsive web design, Web app protoype";
		$this->copyright = "&copy; 2006 - 2013 All Rights Reserved";
		$this->title = "Web app prototype";
		$this->heading = '';
		$this->deviceGroup = '';
		$this->version='1.0';
	}

	public function index() {
		$this->register();
	}
	
	public function register() {
		//heading
		$this->heading = 'Register';
		
		//registration form
		$formValues = array('username', 'email', 'password');
		$registerData['errors'] = array();
		foreach ($formValues as $value) {
			$registerData['values'][$value] = 'Your '.$value;
		}
		if ($_POST) {
			$this->curl->create('/api/users/register/format/json');
			$this->curl->options(array(CURLOPT_BUFFERSIZE => 10));
			$this->curl->post($_POST);
			$this->curl->execute();
			$result = $this->curl->info['http_code'];
			if ($result == 200) {
				redirect(site_url('web/gallery'));
			} else if ($result == 400) {
				$registerData['response'] = array('result'=> $this->lang->line('registered'));
			} else if ($result == 403) {
				echo 'Post does not validate';
				echo 'TO DO: retrieve errors strings from rest API';
			}
			foreach ($formValues as $value) {
				$registerData['values'][$value] = $_POST[$value];
			}
		}
		$registerData['formUrl'] = $this->registerUrl;
		$registerData['loginUrl'] = $this->loginUrl;
		$registerData['passwordHelper'] = $this->lang->line('form_helper_password');
		$registerData['usernameHelper'] = $this->lang->line('form_helper_username');
		$registerData['csrf'] = $this->csrf();
		$container=$this->templateparser->parseTemplate('webapp/register.html', $registerData, true);
	    $this->displayContent($container);
	}

	public function login() {
		//heading
		$this->heading = 'Login';
		
		//Process login form
		$formValues = array('email', 'password');
		$loginData['errors'] = array();
		foreach ($formValues as $value) {
			$loginData['values'][$value] = 'Your '.$value;
		}
		if ($_POST) {
			$this->curl->create('/api/login/validate/format/json');
			$this->curl->options(array(CURLOPT_BUFFERSIZE => 10));
			$this->curl->post($_POST);
			$this->curl->execute();
			$result = $this->curl->info['http_code'];
			if ($result == 200) {
				redirect(site_url('web/gallery'));
			} else if ($result == 400) {
				$loginData['response'] = array('result'=> $this->lang->line('password_incorrect'));
			} else if ($result == 404) {
				$loginData['response'] = array('result'=> $this->lang->line('email_not_recognised'));
			} else if ($result == 403) {
				echo 'Post does not validate';
				echo 'TO DO: retrieve errors strings from rest API';
			}
			foreach ($formValues as $value) {
				echo $value;
				$loginData['values'][$value] = $_POST[$value];
			}
		}
		$loginData['formUrl'] = $this->loginUrl;
		$loginData['registerUrl'] = $this->registerUrl;
		$loginData['passwordHelper'] = $this->lang->line('form_helper_password');
		$loginData['usernameHelper'] = $this->lang->line('form_helper_username');
		$loginData['csrf'] = $this->csrf();
		$container=$this->templateparser->parseTemplate('webapp/login.html', $loginData, true);
	    $this->displayContent($container);
	}
	
	public function gallery() {		
		//heading
		$this->heading = 'Gallery';
		
		//get photos
		$this->curl->create(site_url('/api/photos/photo/format/json'));
		$this->curl->options(array(CURLOPT_BUFFERSIZE => 10));
		$this->curl->http_login('admin', '1234');
		$result = $this->curl->execute();
		$galleryData['photos'] = json_decode($result);
		//set photo size
//not using scale anymore
		/*switch($this->deviceGroup) {
			case '':
				$galleryData['scale'] = '200';
				break;
			case 'large' :
				$galleryData['scale'] = '400';
				break;
			case 'compact' :
				$galleryData['scale'] = '200';
				break;
			case 'smart' :
				$galleryData['scale'] = '200';
				break;
		}*/
		//set photo directory
		$galleryData['imageUrl'] = site_url('images/webapp');
		$container=$this->templateparser->parseTemplate('webapp/gallery.html', $galleryData, true);
	    $this->displayContent($container);
	}
	
	
	/***********************/
	/*   SESSION DATA      */
	/***********************/
	/**
	 * Sets Boolean activeSession
	 * Sets String deviceGroup
	 * Sets String urlCSS
	 * Deals with GET values to set new session
	 * Sets csrf session
	 *
	 */
	public function session () {
		if ($this->mycommonutilities->testSession('deviceGroup')) {
			$this->activeSession = true;
			$this->deviceGroup = $this->mycommonutilities->getSessionData('deviceGroup');
			switch ($this->deviceGroup) {
				case 'large':
					$this->urlCSS = 'large.css';
					break;
				case 'compact':
					$this->urlCSS = 'compact.css';
					break;
				case 'smart':
					$this->urlCSS = 'smart.css';
					break;
			}
		} else {
			$this->activeSession = false;
			if (isset($_GET['screenWidth'])) {
				$deviceGroup = $this->mycommonutilities->getDeviceGroup($_GET['screenWidth']);
				$this->deviceGroup = $deviceGroup;
				$data = array('deviceGroup' => $deviceGroup);
				$this->mycommonutilities->setSession($data);
				$this->mycommonutilities->setCookies($data);
				$this->csrf();
				$this->authenticated();
				echo true;
				exit;
			}
		}
	}
	
	/**
	 * Retrieves csrf hash
	 * Sets csrf ession
	 * Sets csrf cookie
	 * @param Integer $this->csrf_hash
	 * @return Integer $this->csrf_hash
	 */
	private function csrf() {
		$csrf_hash = $this->mycommonutilities->getSessionData('csrf');
		if ($csrf_hash == '') {
			$csrf_hash = md5(uniqid(rand(), TRUE));
			$sessionData = array('csrf' => $csrf_hash);
			$this->mycommonutilities->setSession($sessionData);
			$this->mycommonutilities->setCookies($sessionData);
		}
		return $csrf_hash;
	}	
	
	
	private function authenticated() {
		$authenticated = $this->mycommonutilities->getSessionData('authenticated');
		if ($authenticated == '') {
			$this->mycommonutilities->setSession(array('authenticated' => false));
		}
	}
	
	private function displayContent($container){
        $headerData=array(
        		'script' => 'app/main',
        		'version' => $this->version,
        		'CDNPath' => $this->config->item('CDNPath'),
        		'title' => $this->title,
        		'author' => $this->author,
        		'keywords' => $this->keywords,
        		'activeSession' => $this->activeSession
        );
        if ($this->urlCSS != ''):
        	$headerData['urlCSS']= $this->urlCSS;
        endif;
        $commonBodyData = array(
        	'heading' => $this->heading,
        	'activeSession' => $this->activeSession
        );
        $footerData['copyright']= $this->copyright;
        $header=$this->templateparser->parseTemplate('webapp/head.html', $headerData, true);
    	$commonBody=$this->templateparser->parseTemplate('webapp/commonBody.html', $commonBodyData, true);
        $commonEndBody=$this->templateparser->parseTemplate('webapp/commonEndBody.html', array(), true);
        $footer=$this->templateparser->parseTemplate('webapp/footer.html',$footerData, true);
    	$output=$this->templateparser->parseTemplate('webapp/layout.html',array(
	    		'header'=>$header,
    			'commonBody'=>$commonBody,
    			'container'=>$container,
    			'commonEndBody'=>$commonEndBody,
    			'footer'=>$footer
	         )
	    );
	    echo preg_replace('/[\t\s\n]*(<.*>)[\t\s\n]*/', '$1', $output);
	}
}