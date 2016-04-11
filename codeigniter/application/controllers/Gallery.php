<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Controller for gallery
 * @author amy varga
 *
 * @package  Gallery
 *
 */

class Gallery extends CI_Controller {

	  public function __construct() {
        parent::__construct();
        $this->load->library('templateparser');
        $this->load->library(array('session', 'curl', 'mycommonutilities', 'form_validation'));
		$this->load->helper(array('url', 'cookie', 'security'));
		$this->form_validation->set_error_delimiters('','');
		$this->activeSession = '';
		$this->urlCSS = '';
		$this->session();
		$this->registerUrl = site_url('/Gallery/register');
		$this->loginUrl = site_url('/Gallery/login');
		$this->galleryUrl = site_url('/Gallery/gallery');
		$this->lang->load('form', 'english');
		$this->lang->load('form_validation', 'english');
		$this->lang->load('success', 'english');
		$this->author = "Amy Varga";
		$this->keywords = "Responsive web design, Web app protoype";
		$this->copyright = '&copy; 2006 - '.date('Y').' Absolute Orange Ltd All Rights Reserved';
		$this->title = "Web app prototype";
		$this->heading = '';
		$this->deviceGroup = '';
		$this->version='1.0';
        //TODO: Need to open port 8080
        $this->localhostURL='http://localhost:80';
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
			$this->curlCreate('/api/users/register/format/json');
			$this->curl->options(array(CURLOPT_BUFFERSIZE => 10));
			$this->curl->post($_POST);
			$this->curl->execute();
			$result = $this->curl->info['http_code'];
			if ($result == 200) {
				redirect($this->galleryUrl);
			} else if ($result == 400) {
				$registerData['response'] = array('result'=> $this->lang->line('registered'));
			} else if ($result == 403) {
				$registerData['errors'] = array(
                    'email'=> $this->lang->line('email_invalid'), 
                    'password'=> $this->lang->line('password_invalid'),
                    'username'=> $this->lang->line('username_invalid')
                );
			}
			foreach ($formValues as $value) {
				if (!empty($_POST[$value])) {
                    $registerData['values'][$value] = $_POST[$value];
                }
			}
		}
		$registerData['formUrl'] = $this->registerUrl;
		$registerData['loginUrl'] = $this->loginUrl;
		$registerData['passwordHelper'] = $this->lang->line('helper_password');
		$registerData['usernameHelper'] = $this->lang->line('helper_username');
		$registerData['csrf'] = $this->csrf();
		$container=$this->templateparser->parseTemplate('gallery/register.html', $registerData, true);
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
			$this->curlCreate('/api/login/validate/format/json');
			$this->curl->options(array(CURLOPT_BUFFERSIZE => 10));
			$this->curl->post($_POST);
			$this->curl->execute();
			$result = $this->curl->info['http_code'];
			if ($result == 200) {
				redirect($this->galleryUrl);
			} else if ($result == 400) {
				$loginData['errors'] = array('password'=> $this->lang->line('password_incorrect'));
			} else if ($result == 404) {
				$loginData['errors'] = array('email'=> $this->lang->line('email_not_recognised'));
			} else if ($result == 403) {
				$loginData['errors'] = array(
                    'email'=> $this->lang->line('email_invalid'), 
                    'password'=> $this->lang->line('password_invalid')
                );
			}
			foreach ($formValues as $value) {
				if (!empty($_POST[$value])) {
                    $loginData['values'][$value] = $_POST[$value];
                }
			}
		}
		$loginData['formUrl'] = $this->loginUrl;
		$loginData['registerUrl'] = $this->registerUrl;
		$loginData['galleryUrl'] = $this->galleryUrl;
		$loginData['passwordHelper'] = $this->lang->line('helper_password');
		$loginData['usernameHelper'] = $this->lang->line('helper_username');
		$loginData['csrf'] = $this->csrf();
		$container=$this->templateparser->parseTemplate('Gallery/login.html', $loginData, true);
	    $this->displayContent($container);
	}
	
	public function gallery() {		
		//heading
		$this->heading = 'Gallery';
		//get photos
        $this->curlCreate('/api/photos/photo/format/json');
		$this->curl->options(array(CURLOPT_BUFFERSIZE => 10));
		$this->curl->http_login('admin', '1234');
		$result = $this->curl->execute();
		$galleryData['photos'] = json_decode($result);
		//set photo directory
		$galleryData['imageUrl'] = site_url('images/gallery');
		$container=$this->templateparser->parseTemplate('gallery/gallery.html', $galleryData, true);
	    $this->displayContent($container);
	}
        
    public function curlCreate($url) {    
        if (strpos(site_url(), 'localhost')) {
            $this->curl->create($this->localhostURL.$url);
        } else {
            $this->curl->create(site_url($url));
        }
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
        $header=$this->templateparser->parseTemplate('gallery/head.html', $headerData, true);
    	$commonBody=$this->templateparser->parseTemplate('gallery/commonBody.html', $commonBodyData, true);
        $commonEndBody=$this->templateparser->parseTemplate('gallery/commonEndBody.html', array(), true);
        $footer=$this->templateparser->parseTemplate('gallery/footer.html',$footerData, true);
    	$output=$this->templateparser->parseTemplate('gallery/layout.html',array(
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
