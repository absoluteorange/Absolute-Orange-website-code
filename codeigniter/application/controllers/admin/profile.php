<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Profile extends CI_Controller {
	
	public function __construct(){
            parent::__construct();
            $this->load->model(array('admin/profile_model'));
            $this->load->library(array('admin/crud_methods', 'admin/common_methods', 'admin/user_methods', 'form_validation', 'admin/form_validation', 'admin/admin_display'));
            $this->lang->load('form_validation', 'english');
	}
	
 	public function yourProfile() {
    	$employeeId = $this->session->userdata('userId');
    	if (!empty($_POST['formAction'])):
	    	$formAction = $_POST['formAction'];
	    	switch ($formAction):
	    		case ('editSkills'):
	        		$this->editProfileTable('skills');
	        	break;
	    		case ('editSkillsOrder'):
	    			$this->editTableOrder('skills');
	    		break;
	    		case ('addSkills'):
	    			$this->addToProfileTable($employeeId, 'skills');
	    		break;
	    		case ('editAchievements'):
	        		$this->editProfileTable('achievements');
	        	break;
	    		case ('editAchievementsOrder'):
	    			$this->editTableOrder('achievements');
	    		break;
	    		case ('addAchievements'):
	    			$this->addToProfileTable($employeeId, 'achievements');
	    		break;
	    		case ('updateProfile'):
	    			$this->validateEditProfile($employeeId);
	    		break;
	    	endswitch;
	    endif;
	    if (isset($_GET['editName'])):
        	$data['editFormId'] = $_GET['editId'];
       	endif;
        $data['heading'] = "Absolute Orange profile administration";
        $this->user_methods->setUserDetails();
		$data['userName'] = $this->user_methods->userName;
        $data['showLogo'] = false;
        $data['employeeId'] = $this->session->userdata('userId');
        $data['employeeName'] = $data['userName'];
        $data['content'] = $this->profile_model->getProfile($data['employeeId']);
        $data['profileTables'] = $this->config->item('profileTables');
        foreach ($data['profileTables'] as $table):
        	$data[$table.'Results'] = $this->profile_model->getContents($data['employeeId'], 'employee_'.$table);
        endforeach;
        $data['cvs'] = $this->profile_model->getContents($data['employeeId'], 'employee_cv');
        $data['content'] = $this->load->view("admin/edit_profile",$data,true);
        $this->admin_display->makepage($data);
     }

	 public function validateEditProfile($employeeId) {
        if ($this->form_validation->run('editProfile') == TRUE):
			$this->updateProfile($employeeId);
		endif;	
	 	if ($_FILES):
        	if (isset($_FILES['profile']) AND $_FILES['profile']['size'] > 0):
	        	if ($this->form_validation->run('addProfileImage') == TRUE):
        			return $this->addProfileImage($employeeId);
        		endif;
			endif;
			if (isset($_FILES['profile_back']) AND $_FILES['profile_back']['size'] > 0):
        		if ($this->form_validation->run('addProfileBackImage') == TRUE):
					return $this->addProfileBackImage($employeeId);
				endif;
        	endif;
			if (isset($_FILES['cv']) AND $_FILES['cv']['size'] > 0):
				if ($this->addCV($employeeId)):
					return TRUE;
				else:
	
					return FALSE;
				endif;
			endif;
		endif;	
     }

     private function updateProfile($employeeId){
     	$recordExists = $this->profile_model->getProfile($employeeId);
     	$data=array('years_of_experience' => $_POST['years_experience'],
                    'profile' => $_POST['profile']);
		if (empty($recordExists)):
				$this->db->insert('employees_summaries', $data); 
		else:
	        $this->db->where('employee_id', $employeeId);
	        $this->db->update('employees_summaries',$data);
	    endif;
     }
     
     private function addProfileImage($employeeId) {
     	$this->user_methods->setUserDetails();
        $ext = pathinfo($_FILES['profile']['name'], PATHINFO_EXTENSION);
        $fileName =  str_replace(' ', '_', $this->user_methods->userName).'.'.$ext;
   		$uploadConfig = array('max_height' => '600',
								  'upload_path' => './images/profile',
								  'allowed_types' => 'gif|jpg|png',
								  'max_size' => '1000',
   								  'file_name' => $fileName);
		$name = 'profile';
		if ($this->common_methods->uploadFile($uploadConfig, $name)):
	     	$data=array('photo' => $fileName);
	        $this->db->where('employee_id',$employeeId );
	        $this->db->update('employees_summaries',$data);
	        return true;
       	endif;
     }
     
     private function addProfileBackImage($employeeId) {
     	$this->user_methods->setUserDetails();
       	$ext = pathinfo($_FILES['profile_back']['name'], PATHINFO_EXTENSION);
        $fileName =  str_replace(' ', '_', $this->user_methods->userName).'_back.'.$ext;
   		$uploadConfig = array('max_height' => '600',
								  'upload_path' => './images/profile',
								  'allowed_types' => 'gif|jpg|png',
								  'max_size' => '1000',
   								  'file_name' => $fileName);
		$name = 'profile_back';
		if ($this->common_methods->uploadFile($uploadConfig, $name)):
	     	$data=array('photo_back' => $fileName);
	        $this->db->where('employee_id',$employeeId );
	        $this->db->update('employees_summaries',$data);
	        return true;
       	endif;
     }
     
     private function addCV($employeeId) {
     	$this->user_methods->setUserDetails();
     	$ext = pathinfo($_FILES['cv']['name'], PATHINFO_EXTENSION);
     	$fileName =  str_replace(' ', '_', $this->user_methods->userName).'.'.$ext;
     	$name = 'cv';
        if ($this->form_validation->_formatExists($fileName, './cv') == FALSE):
        	$uploadConfig = array('upload_path' => './cv',
								  'allowed_types' => 'doc|pdf',
	   							  'file_name' => $fileName);
        	if ($this->common_methods->uploadFile($uploadConfig, $name)):
        		$employeeId = $this->session->userdata('userId');
		     	$this->db->insert('employee_cv', array('name' => $fileName, 'employee_id' => $employeeId ));
				return TRUE;
       		endif;
       	else:
       		return FALSE;
       	endif;
     }

     private function editProfileTable($table){
     	$capitalisedTable = ucfirst($table);
     	if ($this->form_validation->run('editProfile'.$capitalisedTable) == TRUE):
	        $this->db->where('id', $_POST['editId']);
     		$this->db->update('employee_'.$table, array($table => $_POST['edit'.$capitalisedTable]));
     		return TRUE;
     	else:
     		return FALSE;
     	endif;
     }
     
 	 private function editTableOrder($table) {
     	$capitalisedTable = ucfirst($table);
     	if ($this->form_validation->run('edit'.$capitalisedTable.'Order') == TRUE):
     		$this->db->where('id', $_POST['editId']);
     		$this->db->update('employee_'.$table, array('sort' => $_POST['edit'.$capitalisedTable.'Order']));
     		return TRUE;
     	else:
     		return FALSE;
     	endif;
     }
     
     private function addToProfileTable ($employeeId, $table) {
     	$capitalisedTable = ucfirst($table);
     	if ($this->form_validation->run('addProfile'.$capitalisedTable) == TRUE):
     		$this->db->insert('employee_'.$table, array('employee_id' => $employeeId, $table => $_POST[$table], 'sort' => $_POST[$table.'Order']));
     		return TRUE;
     	else:
     		return FALSE;
     	endif;
     }
}