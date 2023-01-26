<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class FrontEndController extends CI_Controller
{
    protected $uri1;
    protected $uri2;
    protected $uri3;
    protected $uri4;
    protected $uri5;
    protected $uri6;
    protected $uri7;
    protected $data;
    protected $layout_path;
    protected $langs;
    protected $lclang;
    protected $clang;
    protected $site_url;
    protected $without_get_url;
    protected $full_url;
    protected $breadcrumbs;
    protected $query;

    private function _define_constants()
    {
        $this->load->model('constants_model');
        $constants = $this->constants_model->find();
        $lang = get_language(false);
        foreach ($constants as $constant) {
            if (!defined($constant->ConstantName)) {
                define($constant->ConstantName, $constant->$lang);
            }
        }
    }

    protected function _generate_bc_data($title = '', $url = false)
    {
        $result['title'] = $title;
        if (!empty($url)) {
            $result['url'] = $url;
        }
        return $result;
    }

    public function __construct()
    {
        parent::__construct();

        @session_start();

//        if($_SERVER['REMOTE_ADDR'] == '178.18.47.155'){
//            $this->output->enable_profiler(TRUE);
//        }

        $this->redirects_301();

        date_default_timezone_set('Europe/Bucharest');
        header('Content-type: text/html; charset=utf-8');
        header('X-XSS-Protection: 1; mode=block');
        header('X-Content-Type-Options: nosniff');
        header('Strict-Transport-Security: max-age=31536000');
       /* header('X-Frame-Options: deny');*/
        header("Cache-Control: max-age=31536000");
        header("Referrer-Policy: same-origin");
        /*header("Feature-Policy: microphone 'none';");*/

        $this->data['cart_count'] = 0;
        $this->data['cart_total'] = 0;
        $this->data['cart_uds'] = 0;

        if(!isset($_SESSION['cart'])) $_SESSION['cart'] = [];
        if(!isset($_SESSION['favorite'])) $_SESSION['favorite'] = [];
        if(!isset($_SESSION['compare'])) $_SESSION['compare'] = [];

        foreach($_SESSION['cart'] as $item) {
            $this->data['cart_count'] += $item['qty'];
            $this->data['cart_total'] += $item['price'] * $item['qty'];
            $this->data['cart_uds'] += $item['uds'] * $item['qty'];
        }

        if(uri(2)=='cart' and uri(3) == '') $_SESSION['uds'] = [];
        $this->data['cart_points'] = (isset($_SESSION['uds']['points'])) ? $_SESSION['uds']['points'] : 0;

        $this->data['query'] = $this->input->get('query', true);
        $this->query = $this->input->get('query', true);

        $this->uri1 = uri(1);
        $this->uri2 = uri(2);
        $this->uri3 = uri(3);
        $this->uri4 = uri(4);
        $this->uri5 = uri(5);
        $this->uri6 = uri(6);
        $this->uri7 = uri(7);

        if (empty($_SESSION['lang'])) get_prefered_language();

        assign_language($this->uri1);

        $this->langs = array_map('strtoupper', language(true));
        $this->lclang = get_language(FALSE);
        $this->clang = get_language(TRUE);

        $_SESSION['city_id'] = ($_SESSION['city_id'] ?? 1);

        $this->load->model('cities_model');
        $cities = $this->cities_model->get($this->lclang);

        $this->load->model('partner_model');
        $credit_partner_ids = $this->partner_model->get_credit();

        $credit = $this->db->order_by('months ASC')->where('is_shown', 1)->get('credit')->result_array();

        $this->data['uri1'] = $this->uri1;
        $this->data['uri2'] = $this->uri2;
        $this->data['uri3'] = $this->uri3;
        $this->data['uri4'] = $this->uri4;
        $this->data['uri5'] = $this->uri5;
        $this->data['uri6'] = $this->uri6;
        $this->data['uri7'] = $this->uri7;
        $this->data['langs'] = $this->langs;
        $this->data['lclang'] = $this->lclang;
        $this->data['clang'] = $this->clang;
        $this->data['cities'] = $cities;
        $this->data['credit_partner_ids'] = $credit_partner_ids;
        $this->data['credit'] = $credit;

        if(__CLASS__ !== 'Ajax') {
            $this->_define_constants();

            $this->output->set_header('X-XSS-Protection: 1; mode=block');
            $this->output->set_header('X-Content-Type-Options: nosniff');

            $this->layout_path = 'pages/index';
            $this->site_url = (isset($_SERVER['HTTPS']) ? "https" : "http") . '://' . $_SERVER['HTTP_HOST'];
            $this->without_get_url = $this->site_url . parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
            $_SESSION['without_get_url'] = $this->without_get_url;
            $this->full_url = $this->site_url . $_SERVER['REQUEST_URI'];
            $this->breadcrumbs = array();

            $this->load->model('menu_model');

            $menu = $this->menu_model->get_menu($this->lclang);

            $this->load->model('bottom_category_model');
            $bottom_categories = $this->bottom_category_model->get_categories($this->lclang);

            $categories = curl_post('category/tree', ['lang' => $this->lclang], true);
            $search_categories = curl_post('category/drop', ['lang' => $this->lclang], true);

            $this->load->model('options_model');
            $options = $this->options_model->get_options();

            $this->load->library('user_agent');
            $this->data['is_mobile'] = $this->agent->is_mobile();

            $this->data['site_url'] = $this->site_url;
            $this->data['without_get_url'] = $this->without_get_url;
            $this->data['full_url'] = $this->full_url;
            $this->data['menu'] = $menu;
            $this->data['bottom_categories'] = $bottom_categories;
            $this->data['categories'] = $categories;
            $this->data['search_categories'] = $search_categories;
            $this->data['options'] = $options;
        }
    }

    function redirects_301()
    {
        $lowerURI = strtolower($_SERVER['REQUEST_URI']);
        if(!isset($_GET)){
            if($_SERVER['REQUEST_URI'] != $lowerURI){
                if(substr($lowerURI, -1) != '/'){
                    $lowerUri = $lowerURI.'/';
                }
                header("HTTP/1.1 301 Moved Permanently");
                header("Location: https://" . $_SERVER['HTTP_HOST'] . $lowerURI);
                exit();
            }
        }else{
            $first_section = strtok($_SERVER['REQUEST_URI'], '?');
            $second_section = $_SERVER['QUERY_STRING'];

            if($first_section != strtolower($first_section)){
                $first_section = strtolower($first_section);
                header("HTTP/1.1 301 Moved Permanently");
                if(!empty($second_section)){
                    header("Location: https://" . $_SERVER['HTTP_HOST'] . $first_section . '?' . $second_section);
                }else{
                    header("Location: https://" . $_SERVER['HTTP_HOST'] . $first_section);
                }
                exit();
            }
        }

        $redirect_links = array(
            '/ro' => '/',
            '/ro/' => '/'
        );

        if(!empty($redirect_links[$_SERVER['REQUEST_URI']])){
            header("HTTP/1.1 301 Moved Permanently");
            header("Location: ".$redirect_links[$_SERVER['REQUEST_URI']]);
            exit();
        }
    }

    protected function _render()
    {
        $this->load->vars($this->data);
        $this->load->view($this->layout_path);
    }
}
