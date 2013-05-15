<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/

/*$route['default_controller'] = "welcome";
$route['404_override'] = '';*/


$root="site/";
$route['default_controller'] = 'site';
  
//-----------------------admin ---------------------------------

$route['admin'] = "admin/admin";
$route['admin/clear'] = "admin/admin/clear";
$route['admin/showcases'] = "admin/showcase/showcases";
$route['admin/delItem'] = "admin/admin/delItem";
$route['admin/delLink'] = "admin/admin/delLink";
$route['admin/delFile'] = "admin/admin/delFile";
$route['admin/(:any)/editShowcase'] = "admin/showcase/editShowcase";
$route['admin/(:any)/editShowcase/languages'] = "admin/showcase/languages";
$route['admin/(:any)/editShowcase/software'] = "admin/showcase/software";
$route['admin/(:any)/editShowcase/frameworks'] = "admin/showcase/frameworks";
$route['admin/(:any)/editShowcase/skills'] = "admin/showcase/skills";
$route['admin/(:any)/editShowcase/experience'] = "admin/showcase/experience";
$route['admin/profile'] = "admin/profile/yourProfile";
$route['admin/blogs'] = "admin/blog/blogs";
$route['admin/blogs/editBlog'] = "admin/blog/editBlog";
$route['^(home|blog|work|developers|cv)(/:any)?$'] = "site/$0";
$route['downloadCV'] = "site/downloadCV";
$route['fileAPIUpload'] = "site/fileAPIUpload";
$route['flashmobs'] = "flashmobs";

$route['auth'] = "auth";


$route['dynamicScripts/jsGlobals.js'] = "scripts/jsGlobals";
$route['dynamicScripts/jsLanguage.js'] = "scripts/jsLanguage";

/* End of file routes.php */
/* Location: ./application/config/routes.php */