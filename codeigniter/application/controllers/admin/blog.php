<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Blog extends CI_Controller {
	
	function blog() {
            parent::__construct();
            $this->load->model(array('admin/blog_model'));
            $this->load->library(array('admin/crud_methods', 'admin/common_methods', 'admin/user_methods', 'form_validation', 'admin/form_validation', 'admin/admin_display'));
            $this->lang->load('form_validation', 'english');
	}
	
	public function blogs () {
		if (!empty($_POST['addName'])):
			$this->addBlog();
		endif;
		$data['heading'] = "Absolute Orange blog administration";
		$data['showLogo'] = false;
		$this->user_methods->setUserDetails();
		$data['userName'] = $this->user_methods->userName;
		$items=$this->blog_model->getBlogs($this->session->userdata['userId']);
		$data['items'] = $items;
		$data['section'] = 'blog';
		$data['tableName'] = 'blog';
		$data['fieldName'] = 'name';
		$data['content'] = $this->load->view("admin/common/view",$data,true);
		$this->admin_display->makepage($data);
	}

    public function editBlog() {
        $title = $_GET['blog'];
        if (!empty($_POST['title'])) {
            if ($this->validateEdit()) {
            	if ($_POST['title'] != $title) {
					redirect(site_url('admin/blogs'));
				};
            }
        }
		$data['heading'] = "Absolute Orange blog administration";
		$data['showLogo'] = false;
		$blog = $this->blog_model->getBlog($title);
		$data['id']= $blog->id;
		$data['title']=$blog->name;
		$data['url']=$blog->url;
		$data['image']=$blog->image;
		$data['description']=$blog->description;
        $data['dateCompleted']=$blog->date_completed;
		$data['blogLogos'] = $this->blog_model->getLogos($data['id']);
		$data['blogImages'] = $this->blog_model->getImages($data['id']);
		$data['relatedLinks'] = $this->blog_model->getLinks($data['id']);
      	$data['content'] = $this->load->view("admin/edit_blog",$data,true);
		$this->admin_display->makepage($data);
	}

	private function addBlog() {
		if ($this->form_validation->run('addBlog') == TRUE):
			$this->db->insert('blog', array('name' => $_POST['addName']));
			$id =$this->db->insert_id();
            $this->db->insert('employee_blog', array('employee_id' => $this->session->userdata['userId'], 'blog_id' => $id));
            return TRUE;
        else:
        	return FALSE;
		endif;
	}
	
	public function validateEdit () {
		$title = $_GET['blog'];
		$id = $this->blog_model->getIdFromTitle($title);
		$imageTitle = str_replace(' ', '_', $title);
		if (($_FILES['logo']['size'] > 0) AND ($this->common_methods->validateLogo('blog'))):
			$this->updateLogo($id);
		endif;
		if ($_FILES['screenshot']['size'] > 0):
			if ($_FILES['screenshot_thumb']['size'] > 0):
				if ($this->common_methods->validateScreenshot('blog', $imageTitle)):
					$this->updateScreenshot($id, $imageTitle);
				endif;
			else:
				$this->form_validation->screenshot_thumb = 'Thumbnail is required';
				return FALSE;
			endif;
		endif;
		if (!empty($_POST['related_link']) AND $this->common_methods->validateRelatedlink('blog')):
			$this->updateRelatedLink($id);
		endif;
		if  ($this->form_validation->run('editBlog') == TRUE):
			$this->updateBlog($id);
			return TRUE;
		else:
			return FALSE;
		endif;
	}
	
	public function updateBlog ($id) {
		$data = array('name' => $_POST['title'], 
					  'url' => $_POST['url'],
					  'date_completed' => $_POST['date_completed'],
					  'description' => $_POST['description']);
		$where = 'id = '.$id;
		$this->db->update('blog', $data, $where);
		//screenshots order
		$images = $this->blog_model->getImages($id);
		foreach ($images as $image):
			$imageId = $image['id'];
			if (!empty($_POST['screenshot_order_'.$imageId])):
				$data = array('order_index' => $_POST['screenshot_order_'.$imageId]);
				$where = 'id = '.$imageId;
		        $this->db->update('blog_images', $data, $where);
		    endif;
	    endforeach;	
		return TRUE;
	}
	
	public function updateLogo($id) {
		$this->db->insert('blog_logos', array(
								'image_url' => $_FILES['logo']['name'],
                                'image_alt' => $_POST['logo_alt'],
                                'blog_id' => $id));
		return TRUE;
	}
	
	public function updateScreenshot ($id, $title) {
		$this->db->insert('blog_images', array(
								'image_url' => $title.'_'.$_FILES['screenshot']['name'],
                                'image_alt' => $title.' > '.$_POST['screenshot_alt'],
                                'blog_id' => $id));
		return TRUE;
	}
	
	public function updateRelatedLink ($showcaseId) {
		$data = array('blog_id' => $showcaseId, 
					  'url' => $_POST['related_link'],
					  'name' => $_POST['related_link_title']);
		$this->db->insert('blog_relatedlinks', $data);
		return TRUE;
	}
}