<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mycommonutilities {
	
	private $_ci;
	
	public function __construct() {
		$this->_ci =& get_instance();
		$this->_ci->load->library('session');
		$this->_ci->load->helper('file');
	}
	
	/***************************/
	/*   SESSION UTILITIES    */
	/**************************/
	/**
	 * Gets session data
	 * @param String $item
	 * @return Array all session data
	 * @return String session data
	 */
	public function getSessionData ($item = null) {
		if ($this->testSession()) {
			if ($item == null) {
				return $this->_ci->session->all_userdata();
			} else {
				return $this->_ci->session->userdata($item);
			}
		}
	}

	/**
	 * Sets session
	 * @param Array $sessionData
	 * @return boolean
	 */
	public function setSession ($sessionData) {
		$sessionArray = array();
		foreach ($sessionData as $key=>$value) {
			$sessionArray[$key] = $value;
		}
		$this->_ci->session->set_userdata($sessionArray);
		return true;
	}
	
	/**
	 * Tests if session data has been set
	 * @param String $item
	 * @return boolean
	 */
	public function testSession ($item = null) {
		$sessionData = $this->_ci->session->all_userdata();
		if ($item == null) {
			if (empty($sessionData)) {
				return false;
			} else {
				return true;
			}
		} else {
			foreach ($sessionData as $key=>$value) {
				if ($key == $item) {
					return true;
				}
			}
			return false;
		}
	}
	
	/***************************/
	/*   COOKIE  UTILITIES    */
	/**************************/
	public function setCookies ($cookieData) {
		$cookieArray = array();
		foreach ($cookieData as $key=>$value) {
			if (isset($_COOKIE[$key])) {
				setcookie($key, '', time()-10, '/');
			}
			setcookie($key, $value, NULL,'/');
		}
		return true;
	}
}
