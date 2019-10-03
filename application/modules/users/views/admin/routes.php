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

$route['site-offline'] = "admin/siteoffline";

$route['404_override'] = '';

$route['admin/forget-password'] = "admin/forgetpass";
$route['admin/site-settings'] = "admin/sitesettings";
$route['admin/new-password.html/(:any)'] = "admin/newpass/$1";
$route['admin/change-password'] = "admin/changePassword";
$route['admin/dashboard'] = "admin/dashboard";
$route['admin/role-management'] = "admin/roleManagement";
$route['admin/address-management'] = "admin/listAddress";
$route['admin/dispatcher-management'] = "admin/listDispatcher";
$route['admin/ambulance-personal-management'] = "admin/listAmbulancePersonnel";
$route['admin/transport-management'] = "admin/listTransportType";
$route['admin/dispatcher-notification'] = "admin/dispatcher_notification";
$route['admin/agreement-management'] = "admin/agreement_management";
$route['admin/trackingdata-management'] = "admin/trackingdata_management";
$route['admin/countdown-timer'] = "admin/countdown_timer";

$route['default_controller'] = 'admin';



 

/* End of file routes.php */
/* Location: ./application/config/routes.php */
