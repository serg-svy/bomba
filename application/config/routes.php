<?php
defined('BASEPATH') OR exit('No direct script access allowed');

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
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$langs_array = array('ru', 'ro', 'en');
//$CI = &get_instance();
//$langs_array = $CI->language();
$langs = '(' . implode('|', $langs_array) . ')';

$route['default_controller'] = 'pages';

$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

/* Frontend */
$route[$langs] = "pages/index";

$route[$langs . '/news'] = "frontend/news/index";
$route[$langs . '/news/:any'] = "frontend/news/item";

$route[$langs . '/article'] = "frontend/article/index";
$route[$langs . '/article/:any'] = "frontend/article/item";

$route[$langs . '/vacancies'] = "frontend/vacancies/index";

$route[$langs . '/stores'] = "frontend/stores/index";
$route[$langs . '/stores/:any'] = "frontend/stores/index";

$route[$langs . '/shops/(:num)'] = "frontend/shops/index";

$route[$langs . '/contacts'] = "frontend/contacts/index";

$route[$langs . '/delivery'] = "frontend/delivery/index";

$route[$langs . '/about'] = "frontend/about/index";

$route[$langs . '/payment'] = "frontend/payment/index";

$route[$langs . '/pickup'] = "frontend/pickup/index";

$route[$langs . '/gift-cards'] = "frontend/gift/index";

$route[$langs . '/bomba-club'] = "frontend/uds/index";

$route[$langs . '/credit'] = "frontend/credit/index";

$route[$langs . '/promotions'] = "frontend/promotions/index";
$route[$langs . '/promotions/(:num)'] = "frontend/promotions/index";
$route[$langs . '/promotions/(:num)/:any'] = "frontend/promotions/item";
$route[$langs . '/ajax_promotions/(:num)/:any'] = 'frontend/promotions/filters_only';

$route[$langs . '/search'] = "frontend/search/index";
$route[$langs . '/ajax_search'] = "frontend/search/filters_only";

$route[$langs . '/category'] = "frontend/category/categories";
$route[$langs . '/category/:any'] = "frontend/category/index";
$route[$langs . '/ajax_category/:any'] = 'frontend/category/filters_only';

$route[$langs . '/product/feedback'] = "frontend/product/feedback";
$route[$langs . '/product/quick'] = "frontend/product/quick";
$route[$langs . '/product/preorder'] = "frontend/product/preorder";
$route[$langs . '/product/credit'] = "frontend/product/credit";
$route[$langs . '/product/multiple'] = "frontend/product/multiple";
$route[$langs . '/product/(:num)'] = "frontend/product/num";
$route[$langs . '/product/:any'] = "frontend/product/index";

$route[$langs . '/favorites'] = "frontend/favorites/index";
$route[$langs . '/ajax_favorites'] = 'frontend/favorites/filters_only';

$route[$langs . '/compare'] = "frontend/compare/index";

$route[$langs . '/qr'] = "frontend/qr/index";

$route[$langs . '/cart'] = "frontend/cart/index";
$route[$langs . '/cart/uds'] = "frontend/cart/uds";
$route[$langs . '/cart/add'] = "frontend/cart/add";
$route[$langs . '/cart/empty'] = "frontend/cart/empty";
$route[$langs . '/cart/del'] = "frontend/cart/del";
$route[$langs . '/cart/checkout'] = "frontend/cart/checkout";
$route[$langs . '/cart/create'] = "frontend/cart/create";
$route[$langs . '/cart/payment/(:num)'] = "frontend/cart/payment";
$route[$langs . '/cart/result/(:num)'] = "frontend/cart/result";

$route[$langs . '/uds/find'] = "frontend/uds/find";
$route[$langs . '/uds/set'] = "frontend/uds/set";
$route[$langs . '/uds/remove'] = "frontend/uds/remove";

/* Default Routing */
$route[$langs . '/ajax/subscribe'] = 'ajax/subscribe';
$route[$langs . '/ajax/get_city'] = 'ajax/get_city';
$route[$langs . '/ajax/set_city'] = 'ajax/set_city';
$route[$langs . '/ajax/set_delivery'] = "ajax/set_delivery";
$route[$langs . '/ajax/set_payment'] = "ajax/set_payment";
$route[$langs . '/ajax/set_contact'] = "ajax/set_contact";
$route[$langs . '/ajax/set_city_automatically'] = 'ajax/set_city_automatically';
$route[$langs . '/ajax/check_color_and_size'] = "ajax/check_color_and_size";
$route[$langs . '/ajax/get_disponible_sizes'] = "ajax/get_disponible_sizes";
$route[$langs . '/ajax/get_disponible_colors'] = "ajax/get_disponible_colors";
$route[$langs . '/ajax/search'] = "ajax/search";
$route[$langs . '/ajax/previous_search'] = "ajax/previous_search";
$route[$langs . '/ajax/add_to_favorite'] = "ajax/add_to_favorite";
$route[$langs . '/ajax/add_to_compare'] = "ajax/add_to_compare";
$route[$langs . '/ajax/list_clean'] = "ajax/list_clean";
$route[$langs . '/ajax/get_store_info'] = "ajax/get_store_info";
$route[$langs . '/ajax/get_store_info_mobile'] = "ajax/get_store_info_mobile";

/* Default Routing */
$route[$langs . '/:any/:any/:any/:any/:any'] = 'pages/text_pages';
$route[$langs . '/:any/:any/:any/:any'] = 'pages/text_pages';
$route[$langs . '/:any/:any/:any'] = 'pages/text_pages';
$route[$langs . '/:any/:any'] = 'pages/text_pages';
$route[$langs . '/:any'] = 'pages/text_pages';

/* Dashboard */
$route[ADM_CONTROLLER] = "admin/login";
$route[ADM_CONTROLLER.'/(:any)'] = "admin/$1";
$route[ADM_CONTROLLER.'/(:any)/(:any)'] = "admin/$1/$2";
$route[ADM_CONTROLLER.'/(:any)/(:any)/(:any)'] = "admin/$1/$2/$3";
$route[ADM_CONTROLLER.'/(:any)/(:any)/(:any)/(:any)'] = "admin/$1/$2/$3/$4";
