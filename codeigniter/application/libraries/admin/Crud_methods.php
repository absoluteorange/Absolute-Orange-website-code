<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Crud_methods extends CI_Controller {

	public function __construct(){
		$this->_ci =& get_instance();
		$this->_ci->load->model(array('admin/showcase_model', 'admin/profile_model', 'admin/blog_model'));
	}
	
	/**
	 * 
	 * Deletes item using an HTML href
	 * 
	 * string $_GET['section'] = controller
	 * string $_GET['table'] = db table
	 * string $_GET['id'] = record id
	 */
	public function delItem() {
		$section = $_GET['section'];
		$table = $_GET['table'];
		$id = $_GET['id'];
		switch ($section):
		case ('showcase'):
			$showcaseTables = $this->_ci->config->item('showcaseTables');
			if (in_array($table, $showcaseTables)):
				foreach ($showcaseTables as $showcaseTable):
					$this->_ci->db->delete($showcaseTable, array('id' => $id));
					$this->_ci->db->delete('showcase_'.$showcaseTable, array($showcaseTable.'_id' => $id));
				endforeach;
				return TRUE;
			endif;
			if ($table == 'showcase'):
				$this->_ci->db->delete($table, array('id' => $id));
				$showcaseTables = $this->_ci->config->item('showcaseTables');
				$showcaseTables[] = 'images';
				$showcaseTables[] = 'logos';
				$showcaseTables[] = 'relatedlinks';
				foreach ($showcaseTables as $relatedTable):
					if ($relatedTable == 'logos'):
						$query = $this->_ci->db->get_where('showcase_logos', array('showcase_id' => $id));
						foreach ($query->result() as $row):
							$this->delFile('showcase', 'logo', $row->id);
						endforeach;
					elseif ($relatedTable == 'images'):
						$query = $this->_ci->db->get_where('showcase_images', array('showcase_id' => $id));
						foreach ($query->result() as $row):
							$this->delFile('showcase', 'screenshot', $row->id);
						endforeach;
					endif;
					$this->_ci->db->delete('showcase_'.$relatedTable, array('showcase_id' => $id));
				endforeach;
				$this->_ci->db->delete('employee_showcase', array('showcase_id' => $id));
				return TRUE;
			else:
				$showcaseId = $_GET['showcase'];
				$this->_ci->db->delete('showcase_'.$table, array($table.'_id' => $id, 'showcase_id' => $showcaseId));
				return TRUE;
			endif;
		break;
		case ('profile'):
			if (in_array($table, $this->_ci->config->item('profileTables'))):
				$this->_ci->db->delete('employee_'.$table, array('id' => $id));
				return TRUE;
			endif;
		break;
		case ('blog'):
			if ($table == 'blog'):
				$this->_ci->db->delete($table, array('id' => $id));
				foreach ($this->_ci->config->item('blogTables') as $relatedTable):
					$this->_ci->db->delete('blog_'.$relatedTable, array('blog_id' => $id));
				endforeach;
				return TRUE;
			endif;
		break;
		endswitch;
	}
	
	/**
	 * 
	 * Deletes a link using an HTML href
	 * 
	 * string $_GET['section'] = controller
	 * string $_GET['table'] = db table
	 * string $_GET['id'] = record id
	 */
	 public function delLink() {
	 	$section = $_GET['section'];
		$id = $_GET['id'];
    	switch ($section):
			case ('showcase'):
		        $title = $_GET['showcase'];
		        if ($this->_ci->db->delete('showcase_relatedlinks', array('id' => $id))):
		        	return TRUE;
		        else:
		        	return FALSE;
		        endif;
		    break;
			case ('blog'):
				$title = $_GET['blog'];
				if ($this->_ci->db->delete('blog_relatedlinks', array('id' => $id))):
		        	return TRUE;
		        else:
		        	return FALSE;
		        endif;
		    break;
		endswitch;
    }
    
    /**
	 * 
	 * Deletes an image using an HTML href
	 * 
	 * string $_GET['section'] = controller
	 * string $_GET['type'] = type of image
	 * string $_GET['id'] = image id
	 */
    public function delFile($section = NULL, $type = NULL, $id = NULL) {
    	if ($section == NULL):
	    	$section = $_GET['section'];
	        $type = $_GET['type'];
	        $id = $_GET['id'];
	    endif;
        switch ($section):
    	case ('showcase'):
        	switch($type):
    			case 'logo':
	        		$logoName = $this->_ci->showcase_model->getFileName('showcase_logos', $id);
	        		$this->_ci->db->delete('showcase_logos', array('id' => $id));
	            	unlink('./images/showcase/logos/'.$logoName);
	            	return TRUE;
	            break;
    			case 'screenshot':
    				$screenshotName = $this->_ci->showcase_model->getFileName('showcase_images', $id);
    				$this->_ci->db->delete('showcase_images', array('id' => $id));
            		unlink('./images/showcase/thumbnails'.$screenshotName);
    				unlink('./images/showcase/'.$screenshotName);
            		return TRUE;
            	break;
            endswitch;
        break;
    	case ('profile'):
        	if ($type == 'profile'):
     			$fileName = $this->_ci->profile_model->getProfile($id, 'photo');
	        	$this->_ci->db->where('employee_id', $id);
	        	$this->_ci->db->update('employees_summaries', array('photo' => ''));
	        	unlink('./images/profile/'.$fileName->photo);
	        	return TRUE;
	        elseif ($type == 'profile-back'):
     			$fileName = $this->_ci->profile_model->getProfile($id, 'photo_back');
	        	$this->_ci->db->where('employee_id', $id);
	        	$this->_ci->db->update('employees_summaries', array('photo_back' => ''));
	        	unlink('./images/profile/'.$fileName->photo_back);
	        	return TRUE;
	        elseif ($type == 'cv'):
	        	$fileName = $this->_ci->profile_model->getCVName($id);
	        	$this->_ci->db->delete('employee_cv', array('id' => $id));
	        	unlink('./cv/'.$fileName->name);
	        	return TRUE;
	        endif;
        break;
    	case ('blog'):
            switch ($type):
    			case ('logo'):
	            	$fileName = $this->_ci->blog_model->getFileName('blog_logos', $id);
		        	$this->_ci->db->delete('blog_logos', array('id' => $id));
		        	unlink('./images/blog/logos'.$fileName);
		        	return TRUE;
	        	break;
    			case ('screenshot'):
    				$screenshotName = $this->_ci->blog_model->getFileName('blog_images', $id);
    				$this->_ci->db->delete('blog_images', array('id' => $id));
            		unlink('./images/blog/thumbnails/'.$screenshotName);
    				unlink('./images/blog/'.$screenshotName);
            		return TRUE;
        		break;
        	endswitch;
        endswitch;
    }
    
	public function addItem($table, $name) {
		if ($this->_ci->form_validation->run('add'.ucfirst($table)) == TRUE):
			$this->_ci->db->insert($table, array('name' => $name));
			return TRUE;
        else:
        	return FALSE;
		endif;
	}
	
	public function editItem($table, $id, $name) {
		if ($this->_ci->form_validation->run('edit'.ucfirst($table)) == TRUE):
			$this->_ci->db->delete($table, array('id'=> $id));
			$this->_ci->db->insert($table, array('name' => $name));
			return TRUE;
		else:
			return FALSE;
		endif;
	}
}