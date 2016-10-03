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

$route['default_controller'] = "admin/login";
$route['404_override'] = '';
$route['ajax-login'] = "admin/login/ajax_login";

// Add Customer
$route['customer-list'] = "admin/users/index";
$route['add-customer'] = "admin/users/ajax_add";
$route['get-customer'] = "admin/users/edit";
$route['update-customer'] = "admin/users/ajax_edit";

// Add Category

$route['category-list'] = "admin/category/index";
$route['add-category'] = "admin/category/ajax_add";
$route['get-category'] = "admin/category/edit";
$route['update-category'] = "admin/category/ajax_edit";

// Add Channel

$route['channel-list'] = "admin/category/channel_list";
$route['add-channel'] = "admin/category/ajax_add_channel";
$route['get-channel'] = "admin/category/channel_edit";
$route['update-channel'] = "admin/category/ajax_edit_channel";
/* End of file routes.php */
/* Location: ./application/config/routes.php */