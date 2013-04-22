<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Example
 *
 * This is an example of a few basic user interaction methods you could use
 * all done with a hardcoded array.
 *
 * @package		CodeIgniter
 * @subpackage	Rest Server
 * @category	Controller
 * @author		Phil Sturgeon
 * @link		http://philsturgeon.co.uk/code/
*/

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
require APPPATH.'/libraries/REST_Controller.php';

class Blog extends REST_Controller
{
	function blog_get() {
        if (!$this->get('id')):
        	$this->response(NULL, 400);
        endif;
        $blog = $this->blog_model->getBlog($this->get('id'));
    	if($blog):
            $this->response($blog, 200); // 200 being the HTTP response code
		else:
            $this->response(array('error' => 'This blog could not be found'), 404);
		endif;
    }
    
	function blogs_get() {
        $blogs = $this->blog_model->getAllBlog();
    	if($blogs):
            $this->response($blogs, 200); // 200 being the HTTP response code
		else:
            $this->response(array('error' => 'There are no blogs'), 404);
		endif;
    }



	public function send_post()
	{
		var_dump($this->request->body);
	}


	public function send_put()
	{
		var_dump($this->put('foo'));
	}
}