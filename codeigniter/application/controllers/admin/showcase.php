<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Showcase extends CI_Controller {
	
	function showcase() {
            parent::__construct();
            $this->load->model(array('admin/showcase_model'));
            $this->load->library(array('admin/user_methods', 'admin/crud_methods', 'admin/common_methods', 'form_validation', 'admin/form_validation', 'admin/admin_display'));
            $this->lang->load('form_validation', 'english');
    }
    
	public function showcases() {
		if (!empty($_POST['addTitle'])):
			$this->addShowcase();
		endif;
		$data['heading'] = "Absolute Orange showcases administration";		
		$data['showLogo'] = false;
		$this->user_methods->setUserDetails();
		$data['userName'] = $this->user_methods->userName;
		$employeeId = $this->session->userdata['userId'];
		$items=$this->showcase_model->getShowcases($employeeId);
		$data['items'] = $items;
		$data['section'] = 'showcase';
		$data['tableName'] = 'showcase';
		$data['fieldName'] = 'title';
		$data['content'] = $this->load->view("admin/common/view" ,$data ,true);	
		$this->admin_display->makepage($data);
	}
	
	public function editShowcase() {
		$title = $_GET['showcase'];
        if (!empty($_POST['title'])) {
            if ($this->validateEdit()) {
            	if ($_POST['title'] != $title) {
					redirect(site_url('admin/showcases'));
				};
            }
        }
		$data['heading'] = "Absolute Orange showcase administration";		
		$data['showLogo'] = false;
		$this->user_methods->setUserDetails();
		$data['userName'] = $this->user_methods->userName;	
		$showcase = $this->showcase_model->getShowcase($title);
		$data['id'] = $showcase->id;
		$data['title'] = $showcase->title;
		$data['image'] = $showcase->image;
		$data['link'] = $showcase->link;
		$data['description'] = $showcase->description;
        $data['dateStarted'] = str_replace(' 00:00:00', '', $showcase->date_started);
        $data['dateCompleted'] = str_replace(' 00:00:00', '',$showcase->date_completed);
        $data['deliverable'] = $showcase->deliverable;
        $data['contractor'] = $showcase->contractor;
        $data['owner'] = $showcase->owner;
		$data['showcaseTables'] = $this->config->item('showcaseTables');
		foreach ($this->config->item('showcaseTables') as $table) {
			$data[$table] = $this->showcase_model->getItems($table);
			$data[$table.'_selected'] = $this->showcase_model->getSelectedItems($table, $data['id']);
		}
        $data['showcaseImages'] = $this->showcase_model->getImages($data['id']);
        $data['relatedLinks'] = $this->showcase_model->getRelatedLinks($data['id']);
        $data['showcaseLogos'] = $this->showcase_model->getLogos($data['id']);
		$data['content'] = $this->load->view("admin/edit_showcase",$data,true);	
		$this->admin_display->makepage($data);                
	}
	
	public function validateEdit () {
		$title = $_GET['showcase'];
		$id = $this->showcase_model->getIdFromTitle($title);
		$imageTitle = str_replace(' ', '_', $title);
		if (($_FILES['logo']['size'] > 0) AND ($this->common_methods->validateLogo('showcase'))):
			$this->updateLogo($id);
		endif;
		if ($_FILES['screenshot']['size'] > 0):
			if ($_FILES['screenshot_thumb']['size'] > 0):
				if ($this->common_methods->validateScreenshot('showcase', $imageTitle)):
					$this->updateScreenshot($id, $imageTitle);
				endif;
			else:
				$this->form_validation->screenshot_thumb = 'Thumbnail is required';
				return FALSE;
			endif;
		endif;
		if (!empty($_POST['related_link']) AND $this->common_methods->validateRelatedlink('showcase')):
			$this->updateRelatedLink($id);
		endif;
		if  ($this->form_validation->run('editShowcase') == TRUE):
			$this->updateShowcase($id);
			return TRUE;
		else:
			return FALSE;
		endif;
	}
	
	public function updateShowcase ($id) {
		$data = array('title' => $_POST['title'], 
					  'date_started' => $_POST['date_started'],
					  'date_completed' => $_POST['date_completed'],
					  'description' => $_POST['description'],
					  'deliverable' => $_POST['deliverable'],
					  'contractor' => $_POST['contractor'],
					  'owner' => $_POST['owner'],
					  'link' => $_POST['link']);
		$where = 'id = '.$id;
		$this->db->update('showcase', $data, $where);
		//screenshots order
		$images = $this->showcase_model->getImages($id);
		foreach ($images as $image):
			$imageId = $image['id'];
			if (!empty($_POST['screenshot_order_'.$imageId])):
				$data = array('order_index' => $_POST['screenshot_order_'.$imageId]);
				$where = 'id = '.$imageId;
		        $this->db->update('showcase_images', $data, $where);
		    endif;
	    endforeach;	
	    //headings
	    foreach ($this->config->item('showcaseTables') as $table):
	    	//delete all existing headings for showcase
	    	$this->db->delete('showcase_'.$table, array('showcase_id' => $id));
	    	if (!empty($_POST[$table])):
	    		foreach ($_POST[$table] as $itemId):
	    			$data = array('showcase_id' => $id, $table.'_id' => $itemId);
	    			$this->db->insert('showcase_'.$table, $data);
	    		endforeach;
	    	endif;
	    endforeach;	
		return TRUE;
	}
	
	public function updateLogo ($id) {
		$this->db->insert('showcase_logos', array(
						   'image_url' => $_FILES['logo']['name'],
                           'image_alt' => $_POST['logo_alt'],
                           'showcase_id' => $id));
		return TRUE;
	}
	
	public function updateScreenshot ($id, $title) {
		$this->db->insert('showcase_images', array(
							'image_url' => $title.'_'.$_FILES['screenshot']['name'],
                            'image_alt' => $title.' > '.$_POST['screenshot_alt'],
                            'showcase_id' => $id));
		return TRUE;
	}
	
	public function updateRelatedLink ($showcaseId) {
		$data = array('showcase_id' => $showcaseId, 
					  'url' => $_POST['related_link'],
					  'name' => $_POST['related_link_title']);
		$this->db->insert('showcase_relatedlinks', $data);
		return TRUE;
	}
	
	private function addShowcase() {
		if ($this->form_validation->run('addShowcase') == TRUE):
			$this->db->insert('showcase', array('title' => $_POST['addTitle']));
			$showcaseId =$this->db->insert_id();
            $this->db->insert('employee_showcase', array('employee_id' => $this->session->userdata['userId'], 'showcase_id' => $showcaseId));
            return TRUE;
        else:
        	return FALSE;
		endif;
	}
	
	public function languages() {
		if (!empty($_POST['formAction'])):
			$action = $_POST['formAction'];
			switch ($action):
				case 'addLanguages':
					$this->crud_methods->addItem('languages', $_POST['addName']);
				break;
				case 'editLanguages':
					$this->crud_methods->editItem('languages', $_POST['editId'], $_POST['editName']);
				break;
			endswitch;
		endif;
		if (isset($_GET['editName'])):
			$data['editFormId'] = $_GET['editId'];
		endif;
		$this->user_methods->setUserDetails();
		$data['userName'] =  $this->user_methods->userName;			
		$data['heading'] = "Absolute Orange languages administration";		
		$data['showLogo'] = false;	
		$items=$this->showcase_model->getItems('languages');
		$data['items'] = $items;
		$data['section'] = 'showcase';
		$data['tableName'] = 'languages';
		$data['fieldName'] = 'name';
		$data['content'] = $this->load->view("admin/common/view",$data,true);	
		$this->admin_display->makepage($data);
	}
	        	
	public function software() {
		if (!empty($_POST['formAction'])):
			$action = $_POST['formAction'];
			switch ($action):
				case 'addSoftware':
					$this->crud_methods->addItem('software', $_POST['addName']);
				break;
				case 'editSoftware':
					$this->crud_methods->editItem('software', $_POST['editId'], $_POST['editName']);
				break;
			endswitch;
		endif;
		if (isset($_GET['editName'])):
			$data['editFormId'] = $_GET['editId'];
		endif;
		$this->user_methods->setUserDetails();
		$data['userName'] =  $this->user_methods->userName;			
		$data['heading'] = "Absolute Orange software administration";		
		$data['showLogo'] = false;	
		$items=$this->showcase_model->getItems('software');
		$data['items'] = $items;
		$data['section'] = 'showcase';
		$data['tableName'] = 'software';
		$data['fieldName'] = 'name';			
		$data['content'] = $this->load->view("admin/common/view",$data,true);
		$this->admin_display->makepage($data);
	}
	
	public function frameworks() {
		if (!empty($_POST['formAction'])):
			$action = $_POST['formAction'];
			switch ($action):
				case 'addFrameworks':
					$this->crud_methods->addItem('frameworks', $_POST['addName']);
				break;
				case 'editFrameworks':
					$this->crud_methods->editItem('frameworks', $_POST['editId'], $_POST['editName']);
				break;
			endswitch;
		endif;
		if (isset($_GET['editName'])):
			$data['editFormId'] = $_GET['editId'];
		endif;
		$this->user_methods->setUserDetails();
		$data['userName'] =  $this->user_methods->userName;			
		$data['heading'] = "Absolute Orange framework administration";		
		$data['showLogo'] = false;	
		$items=$this->showcase_model->getItems('frameworks');
		$data['items'] = $items;
		$data['section'] = 'showcase';
		$data['tableName'] = 'frameworks';
		$data['fieldName'] = 'name';
		$data['content'] = $this->load->view("admin/common/view",$data,true);	
		$this->admin_display->makepage($data);
	}
	
 	public function skills() {
    	if (!empty($_POST['formAction'])):
			$action = $_POST['formAction'];
			switch ($action):
				case 'addSkills':
					$this->addSkill();
				break;
				case 'editSkills':
					$this->editSkill();
				break;
				case 'editExpertise':
					$this->editExpertise();
				break;
			endswitch;
		endif;
		if (isset($_GET['editName'])):
			$data['editFormId'] = $_GET['editId'];
		endif;
		$this->user_methods->setUserDetails();
		$data['userName'] =  $this->user_methods->userName;
		$data['heading'] = "Absolute Orange skills administration";
		$data['showLogo'] = false;
		$items=$this->showcase_model->getItems('skills');
		$data['items'] = $items;
		$data['section'] = 'showcase';
		$data['tableName'] = 'skills';
		$data['fieldName'] = 'name';
        $data['expertise'] = 'expertise';
		$data['content'] = $this->load->view("admin/common/view",$data,true);
		
		$this->admin_display->makepage($data);
	}
	
	private function addSkill(){
		if ($this->form_validation->run('addExpertise') == TRUE):
			if ($this->form_validation->run('addSkills') == TRUE):
				$this->db->insert('skills', array('name' => $_POST['addName'], 'expertise' => $_POST['addExpertise']));
				return TRUE;
			else:
				return FALSE;
			endif;
		else:
			return FALSE;
		endif;
	}
	
	private function editSkill() {
		if ($this->form_validation->run('editSkill') == TRUE):
			$query = $this->db->get_where('skills', array('id' => $_POST['editId']));
			$skill = $query->first_row()->name;
			$this->db->where('name', $skill);
			$this->db->update('skills', array('name'=> $_POST['editName']));
			return TRUE;
		else:
			return FALSE;
		endif;
	}
	
	private function editExpertise() {
		if ($this->form_validation->run('editExpertise') == TRUE):
			$query = $this->db->get_where('skills', array('id' => $_POST['editId']));
			$expertise = $query->first_row()->expertise;
			$this->db->where('expertise', $expertise);
			$this->db->update('skills', array('expertise'=> $_POST['editExpertise']));
			return TRUE;
		else:
			return FALSE;
		endif;
	}
	
	public function experience() {	
		if (!empty($_POST['formAction'])):
			$action = $_POST['formAction'];
			switch ($action):
				case 'addExperience':
					$this->crud_methods->addItem('experience', $_POST['addName']);
				break;
				case 'editExperience':
					$this->crud_methods->editItem('experience', $_POST['editId'], $_POST['editName']);
				break;
			endswitch;
		endif;
		if (isset($_GET['editName'])):
			$data['editFormId'] = $_GET['editId'];
		endif;
		$this->user_methods->setUserDetails();
		$data['userName'] = $this->user_methods->userName;		
		$data['heading'] = "Absolute Orange showcase experience administration";		
		$data['showLogo'] = false;	
		$items=$this->showcase_model->getItems('experience');
		$data['items'] = $items;
		$data['section'] = 'showcase';
		$data['tableName'] = 'experience';
		$data['fieldName'] = 'name';
		$data['content'] = $this->load->view("admin/common/view",$data,true);	
		$this->admin_display->makepage($data);
	}
}
