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
        $this->load->library(array('session', 'curl', 'mycommonutilities', 'form_validation', 'myformvalidator'));
		$this->load->model('webapp/Usersmodel');
		$this->load->helper(array('url', 'cookie', 'security'));
		$this->form_validation->set_error_delimiters('','');

        $this->authenticated = false;
        $this->typeHeaderSubscribeForm = 'login';
        $this->headerSubscribeForm = '';
        $this->welcomeHeading = '';
        
        $this->registerFields = array('username', 'email', 'password');
        $this->registerData['errors'] = array();
        $this->registerData['default'] = array();
        $this->registerData['values'] = array();
        $this->registerData['response'] = array();
        $this->registerData['formUrl'] = site_url('/Gallery/register');
        $this->registerData['loginUrl'] = site_url('/Gallery/login');
		
        $this->loginFields = array('email', 'password');
        $this->loginData['errors'] = array();
        $this->loginData['default'] = array();
        $this->loginData['values'] = array();
        $this->loginData['response'] = array();
        $this->loginData['formUrl'] = site_url('/Gallery/login');
        $this->loginData['registerUrl'] = site_url('/Gallery/register');

        $this->galleryUrl = site_url('/Gallery/gallery');
		$this->lang->load('form', 'english');
		$this->lang->load('form_validation', 'english');
		$this->lang->load('success', 'english');
		$this->author = "Amy Varga";
		$this->keywords = "Responsive web design, Web app protoype";
		$this->copyright = '&copy; 2006 - '.date('Y').' Absolute Orange Ltd All Rights Reserved';
		$this->title = "Backbone gallery";
		$this->heading = '';
		$this->version='1.0';
        //TODO: Need to open port 8080
        $this->localhostURL='http://localhost:80';
		
        $this->setSession();
	}

	public function index() {
		$this->gallery();
	}
    
   public function gallery() {
        $this->heading = 'Gallery';
        $this->isAuthenticated();
        if (! $this->authenticated) {
            $this->setHeaderSubscribeForm($this->typeHeaderSubscribeForm);
        } else {
            $userName = $this->mycommonutilities->getSessionData('user-name');
            $welcomeHeader['welcomeText'] = 'Welcome '.$userName;
            $this->welcomeHeading = $this->templateparser->parseTemplate('gallery/headerWelcome.html', $welcomeHeader, true);
        } 
        $this->executeCurl('/api/photos/photo/format/json');
		$result = $this->curl->execute();
		$galleryData['photos'] = json_decode($result);
		$galleryData['imageUrl'] = site_url('images/gallery');
		$bodyContent = $this->templateparser->parseTemplate('gallery/gallery.html', $galleryData, true);
	    $this->displayContent($bodyContent);
	}
        
	private function displayContent($bodyContent){
        $headerData=array(
        		'script' => 'app/main',
        		'version' => $this->version,
        		'CDNPath' => $this->config->item('CDNPath'),
        		'title' => $this->title,
        		'author' => $this->author,
        		'keywords' => $this->keywords
        );
        $commonBodyData = array(
        	'heading' => $this->heading,
            'subscribe' => $this->headerSubscribeForm,
            'welcomeHeading' => $this->welcomeHeading
        );
        $footerData['copyright']= $this->copyright;
        $header=$this->templateparser->parseTemplate('gallery/head.html', $headerData, true);
    	$commonBody=$this->templateparser->parseTemplate('gallery/commonBody.html', $commonBodyData, true);
        $commonEndBody=$this->templateparser->parseTemplate('gallery/commonEndBody.html', array(), true);
        $footer=$this->templateparser->parseTemplate('gallery/footer.html',$footerData, true);
    	$output=$this->templateparser->parseTemplate('gallery/layout.html',array(
	    		'header'=>$header,
    			'commonBody'=>$commonBody,
    			'container'=>$bodyContent,
    			'commonEndBody'=>$commonEndBody,
    			'footer'=>$footer
	         )
	    );
	    echo preg_replace('/[\t\s\n]*(<.*>)[\t\s\n]*/', '$1', $output);
    }

    private function setHeaderSubscribeForm() {
		$this->heading = 'Gallery';
        if ($this->typeHeaderSubscribeForm == 'login') {
            $this->setDefaultFormValues($this->loginFields, 'login');
            $this->loginData['csrf'] = $this->getCsrfHash();
            $this->headerSubscribeForm = $this->templateparser->parseTemplate('Gallery/headerLogin.html', $this->loginData, true);
        } else if ($this->typeHeaderSubscribeForm == 'register') {
            $this->setDefaultFormValues($this->registerFields, 'register');
            $this->registerData['csrf'] = $this->getCsrfHash();
            $this->headerSubscribeForm = $this->templateparser->parseTemplate('Gallery/headerRegister.html', $this->registerData, true);
        } 
    }

    public function register() {
        $form = 'register';
        if ($_POST) {
            $this->saveUserData($this->registerFields, $form);
            if ($this->checkFieldsEmpty($this->registerFields)) {
                $this->setEmptyMessages($this->registerFields, $form);
                $this->typeHeaderSubscribeForm = 'register';
                $this->gallery();
            } else {
                $this->executeCurl('/api/users/register/format/json');
                $this->curl->post($_POST);
                $this->curl->execute();
                $httpCode = $this->curl->info['http_code'];
                switch ($httpCode) {
                    case 200:
                        $this->setSessionAuthenticated();
                        $this->gallery();
                        break;
                    case 400:
                        $this->registerData['errors']['email'] = $this->lang->line('registered');
                        $this->displayRegisterPage();
                        break;
                    case 403:
                        $errors = $this->myformvalidator->SendErrors();
                        $this->setValidationErrors($errors, $form);
                        $this->displayRegisterPage();
                        break;
                }
            }
        } else {
            $this->typeHeaderSubscribeForm = 'register';
            $this->gallery();
        }
    }

    public function login() {
        $form = 'login';
        if ($_POST) {
            $this->saveUserData($this->loginFields, $form);
            if ($this->checkFieldsEmpty($this->loginFields)) {
                $this->setEmptyMessages($this->loginFields, $form);
                $this->gallery();
            } else {
                $this->executeCurl('/api/login/validate/format/json');
                $this->curl->post($_POST);
                $this->curl->execute();
                $httpCode = $this->curl->info['http_code'];
                switch ($httpCode) {
                    case 200:
                        $this->setSessionAuthenticated();
                        $this->gallery();
                        break;
                    case 400:
                        $this->loginData['errors']['password'] = $this->lang->line('password_incorrect');
                        $this->displayLoginPage();
                        break;
                    case 404:
                        $this->loginData['errors']['email'] = $this->lang->line('email_not_recognised');
                        $this->displayLoginPage();
                        break;
                    case 403:
                        $errors = $this->myformvalidator->SendErrors();
                        $this->setValidationErrors($errors, $form);
                        $this->displayLoginPage();
                        break;
                }
            }
        } else {
            $this->gallery();
        }
    }

    public function displayLoginPage() {
        $this->headerSubscribeForm = '';
		$this->heading = 'Login';
		$loginData['csrf'] = $this->getCsrfHash();
		$bodyContent =  $this->templateparser->parseTemplate('gallery/login.html', $this->loginData, true);
	    $this->displayContent($bodyContent);
    }

    public function displayRegisterPage() {
        $this->headerSubscribeForm = '';
		$this->heading = 'Register';
		$registerData['csrf'] = $this->getCsrfHash();
		$bodyContent =  $this->templateparser->parseTemplate('gallery/register.html', $this->registerData, true);
	    $this->displayContent($bodyContent);
    }

    private function executeCurl ($url) {
        if (strpos(site_url(), 'localhost')) {
            $this->curl->create($this->localhostURL.$url);
        } else {
            $this->curl->create(site_url($url));
        }
        $this->curl->options(array(CURLOPT_BUFFERSIZE => 10));
		$this->curl->http_login('admin', '1234');
        return true;
    }

    public function setValidationErrors($errors, $form) {
        foreach ($errors as $field => $error) {
            if ($form == 'login') {
                $this->loginData['errors'][$field] = $error;
            } else {
                $this->registerData['errors'][$field] = $error;
            }
        }
    }
    
   private function checkFieldsEmpty ($fields) {
        foreach ($fields as $field) {
            if (empty($_POST[$field])) {
                return true;
            }
        }
        return false;
    }
    
    private function setEmptyMessages ($formFields, $form) {
        if (!$_POST['email']) {
            switch ($form) {
                case 'login':
                    $this->loginData['errors']['email'] = $this->lang->line('email_empty');
                break;
                case 'register':
                    $this->registerData['errors']['email'] = $this->lang->line('email_empty');
                break;
            }
        }
        if (!$_POST['password']) {
            switch ($form) {
                case 'login':
                    $this->loginData['errors']['password'] = $this->lang->line('password_empty');
                break;
                case 'register':
                    $this->registerData['errors']['password'] = $this->lang->line('password_empty');
                break;
            }
        }
        if (isset($_POST['username']) && !$_POST['username']) {
            switch ($form) {
                case 'login':
                    $this->loginData['errors']['username'] = $this->lang->line('username_empty');
                break;
                case 'register':
                    $this->registerData['errors']['username'] = $this->lang->line('username_empty');
                break;
            }
        }
    }

    private function setDefaultFormValues ($formFields, $form) {
		foreach ($formFields as $field) {
            switch ($form) {
                case 'login':
                    $this->loginData['default'][$field] = 'Your '.$field;
                break;
                case 'register':
                    $this->registerData['default'][$field] = 'Your '.$field;
                break;
            }
		}
    }

    private function saveUserData ($formFields, $form) {
        foreach ($formFields as $field) {
            if (!empty($_POST[$field])) {
                switch ($form) {
                    case 'login':
                        $this->loginData['values'][$field] = $_POST[$field];
                    break;
                    case 'register':
                        $this->registerData['values'][$field] = $_POST[$field];
                    break;
                }
            }
        }
    }
    
    private function getCsrfHash() {
        return $this->mycommonutilities->getSessionData('csrf');
    }

    private function setSessionAuthenticated() {
        $user = $this->Usersmodel->get_user($_POST['email']);
        $sessionData = array('user-name' => $user[0]['name'], 'authenticated' => true);
        $this->mycommonutilities->setSession($sessionData);
        return true;
    }

    private function setSession() {
        if (empty($this->mycommonutilities->getSessionData('csrf'))) {
            $csrf_hash = md5(uniqid(rand(), TRUE));
            $sessionData = array('csrf' => $csrf_hash);
            $this->mycommonutilities->setCookies($sessionData);
            $sessionData = array('csrf' => $csrf_hash, 'authenticated' => false);
            $this->mycommonutilities->setSession($sessionData);
        }
    }	

    private function isAuthenticated() {
        if ($this->mycommonutilities->getSessionData('authenticated') == true){
            $this->authenticated = true; 
        }
    }
	
}
