<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Uds extends FrontEndController
{
    private $page_id;

    public function __construct() {
        parent::__construct();
        $this->page_id = 37;
    }

    private function _init_seo_data($page) {
        $this->data['page_title'] = (!empty($page->seo_title)) ? $page->seo_title : "";
        $this->data['page_name'] = $page->title;

        $this->data['keywords_for_layout'] = (!empty($page->seo_keywords)) ? $page->seo_keywords : "";
        $this->data['description_for_layout'] = (!empty($page->seo_desc)) ? $page->seo_desc : "";
        $this->data['otitle'] = $page->seo_title;
        foreach(language(true) as $lang){
            $array = $lang.'_urls';
            $this->data['lang_urls'][$lang] = $this->{$array};
        }

        $this->data['breadcrumbs'] = $this->breadcrumbs;
    }

    private function loadOGImgData($page, $dir = 'menu') {
        if (!empty($page->img)) {
            $this->data['og_img'] = newthumbs($page->img, $dir, 500, 300, 'og500x300x1', 1);
            $this->data['og_img_width'] = 500;
            $this->data['og_img_height'] = 300;
        } else {
            $this->data['og_img'] = newthumbs('og.png', 'i', 200, 65, 'og200x65x1', 1);
            $this->data['og_img_width'] = 200;
            $this->data['og_img_height'] = 65;
        }
    }

    public function index() {

        $page = $this->menu_model->get_page_data_by_id($this->lclang, $this->page_id);
        if (empty($page)) throw_on_404();

        $this->breadcrumbs[] = $this->_generate_bc_data($page->title, $page->uri);

        foreach(language(true) as $lang){
            $array = $lang.'_urls';
            $this->{$array}[] = $page->uri;
        }

        $this->loadOGImgData($page);
        $this->_init_seo_data($page);

        $this->load->model('uds_blocks_model');
        $blocks = $this->uds_blocks_model->get_blocks($this->lclang);

        $this->data['inner_view'] = 'pages/uds/index';
        $this->data['page'] = $page;
        $this->data['blocks'] = $blocks;

        $this->_render();
    }

    public function find() {
        $code = $this->input->post('code', true);
        $total = $this->input->post('total', true);

        $findResponse = curl_post('uds/find', ['code' => $code], false);

        if(isset($findResponse->user->participant->id)) {

            $calcResponse = curl_post('uds/calc', ['code' => $code, 'total' => $total], false);

            if(isset($calcResponse->user->participant->id)) {
                $_SESSION['uds']['code'] = $code;
                $_SESSION['uds']['id'] = $findResponse->user->participant->id;
                $_SESSION['uds']['uid'] = $findResponse->user->uid;
                $_SESSION['uds']['points'] = (int) $calcResponse->purchase->maxPoints;

                $data = $findResponse;
                $data->lclang = $this->lclang;
                $data->cashback = $_SESSION['cashback'];
                $data->points_to_use = (int) $calcResponse->purchase->maxPoints;

                $response['view'] = $this->load->view('layouts/pages/use__uds', ['data' => $data], true);
                $response['status'] = 'success';
            } else {
                $response['status'] = 'error';
            }
        } else {
            $response['status'] = 'error';
            $response['message'] = UDS_CODE_INVALID;
        }

        echo json_encode($response, true);
    }

    public function set() {
        $points = $this->input->post('points', true);
        $_SESSION['uds']['points'] = $points;

        $data = [
            'cart_total' => $this->data['cart_total'],
            'cart_count' => $this->data['cart_count'],
            'cart_points' => $points,
            'next' => 'checkout',
            'lclang' => $this->lclang,
            'menu' => $this->data['menu']
        ];

        $response['view'] = $this->load->view('layouts/pages/order__sidebar', $data, true);
        $response['message'] = $points.' '.TEXT_UDS_DEBITED;
        $response['total'] = numberFormat($this->data['cart_total'] - $points, 0) . ',-';
        $response['status'] = 'success';

        echo json_encode($response, true);
    }

    public function remove() {
        unset($_SESSION['uds']);

        echo numberFormat($this->data['cart_total'], 0).',-';
    }
}
