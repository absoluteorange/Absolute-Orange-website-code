<?php defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH.'/libraries/Validation_Controller.php';

class Photos extends Validation_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('webapp/Photosmodel');
		$this->load->library('form_validation');
		$this->load->library('myformvalidator');		
		$this->lang->load('form_validation', 'english');
	}
	
	function photo_get() {
		$photos = $this->Photosmodel->getPhotos();
        if($photos) {
            $this->response($photos, 200); // 200 being the HTTP response code
        } else {
            $this->response(array('error' => '404'), 404);
        }
	}
	
	function photo_post() {
		$deviceGroup = $this->session->userdata('deviceGroup');
		$authenticated = $this->session->userdata('authenticated');
		$photoData['errors'] = array();
		if ($deviceGroup == 'large' && $authenticated == true) {
			if ($_POST) {
			} else {
				$_POST = $this->processData($fields);
			}
			if ($this->form_validation->run('photo') == TRUE) {
				$data = $_POST['data'];
				$fileName = $_POST['name'];
				$fileFormat = $_POST['format'];
				$file = 'images/webapp/'.$fileName.'.'.$fileFormat;
				$alt =  '';
				$isSuccess = false;
				switch ($fileFormat) {
					case 'jpg':
						$data = str_replace('data:image/jpeg;base64,', '', $data);
						break;
					case 'png':
						$data = str_replace('data:image/png;base64,', '', $data);
						break;
				}
				$data = str_replace(' ', '+', $data);
				$data = base64_decode($data);
				if ($this->data_validate_decoded($data)) {
					$isSuccess = file_put_contents($file, $data);
					$this->Photosmodel->addImage($fileName, $alt, $fileFormat);
					if ($isSuccess) {
						$this->response(array('success' => '200'), 200);
					}	
				}
			} else {
				//echo 'CI form validation errors';
	    		$photoData['errors'] = $this->myformvalidator->sendErrors($photoData['errors'], $_POST);
    		}
		} else {
			$photoData['errors']['login'] = $this->lang->line('not_logged_in');
			$this->response(array('error' => $photoData['errors']), 403);
		}
	}
}
?>