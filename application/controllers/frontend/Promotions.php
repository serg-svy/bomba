<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use JasonGrimes\Paginator;

class Promotions extends FrontEndController
{
    private $page_id;

    public function __construct() {
        parent::__construct();
        $this->page_id = 10;
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

        $this->load->model("promotion_category_model");
        $promotion_categories = $this->promotion_category_model->get_categories($this->clang);

        $category_id = uri(3);
        $get = array();
        $get['page'] = (isset($_GET['page'])) ? $_GET['page'] : 1;
        $start = ($get['page']-1)*PROMOTIONS_PER_PAGE;

        $this->load->model("promotions_model");
        $promotions = $this->promotions_model->get_promotions($this->clang, $start, $category_id, PROMOTIONS_PER_PAGE);
        $count = $this->promotions_model->get_promotions_count($category_id);

        $uri_parts = explode('?', $_SERVER['REQUEST_URI'], 2);
        $urlPattern = $uri_parts[0].'?page=(:num)';

        $paginator = new Paginator($count, PROMOTIONS_PER_PAGE, $get['page'], $urlPattern);
        $paginator->setMaxPagesToShow(5);
        $this->data['paginator'] = $paginator;

        $this->data['inner_view'] = 'pages/promotions/index';
        $this->data['page'] = $page;
        $this->data['promotions'] = $promotions;
        $this->data['promotion_categories'] = $promotion_categories;

        $this->_render();
    }

    public function item() {
        $page = $this->menu_model->get_page_data_by_id($this->lclang, $this->page_id);
        if (empty($page)) throw_on_404();

        $this->breadcrumbs[] = $this->_generate_bc_data($page->title, $page->uri);

        foreach(language(true) as $lang){
            $array = $lang.'_urls';
            $this->{$array}[] = $page->uri;
        }

        $this->load->model("promotions_model");
        $promotion = $this->promotions_model->get_promotion_by_uri($this->lclang, uri(4));
        if (empty($promotion)) throw_on_404();

        $this->breadcrumbs[] = $this->_generate_bc_data($promotion->title, $promotion->uri);

        foreach(language(true) as $lang){
            $array = $lang.'_urls';
            $this->{$array}[] = $promotion->uri;
        }

        $this->loadOGImgData($promotion, 'promotions');
        $this->_init_seo_data($promotion);

        $products_ids = $this->db->where("promotion_id", $promotion->id)->get("promotion_product")->result();


        if($products_ids) {
            $get = $this->input->get(null, true);
            $ids = array_map(function ($item) { return $item->product_id;}, $products_ids);
            $products = curl_post('product/find', ['lang' => $this->lclang, 'ids' => $ids, 'limit' => 513], true);

            if ($products) {
                $category_ids = array_unique(array_map(function ($item) {return $item['category_id'];}, $products));
                $categories_request = curl_post('category/findByIds', ['lang' => $this->lclang, 'ids' => $category_ids], true);

                $categories = [];
                foreach($categories_request as $categories_row) {
                    $categories[$categories_row['id']] = $categories_row;
                }
                $category = (isset($get['cat']) and !empty($get['cat']) and isset($categories[$get['cat']])) ? (object) $categories[$get['cat']] :  (object) current($categories);
                if (empty($category)) throw_on_404();


                if(isset($get['cat'])) {
                    $this->load->library('products');
                    $this->data = array_merge($this->data, $this->products->get($this->lclang, $category, $ids));
                } else {
                    $this->data['products'] = $products;
                    $this->data['count'] = count($products);
                    $this->data['deliveryCourier'] = getCourierDelivery($this->lclang, $_SESSION['city_id']);
                }

                $this->data['inner_view'] = 'pages/promotions/products';
                $this->data['find_categories'] = $categories;
                $this->data['category'] = $category;
                $this->data['link_parts'][] = $category->uri;

            } else {
                throw_on_404();
            }
        } else {
            $this->data['inner_view'] = 'pages/promotions/item';
        }

        $this->data['page'] = $promotion;
        $this->data['promotion'] = $promotion;

        $this->_render();
    }

    public  function filters_only(): bool
    {
        $category_id = $this->input->get('cat', true);
        $category = curl_post('category/findOne', ['lang' => $this->lclang, 'uri' => $category_id], false);
        if (!$category) return false;

        $this->load->model("promotions_model");
        $promotion = $this->promotions_model->get_promotion_by_uri($this->lclang, uri(4));
        if (!$promotion) return false;

        $products_ids = $this->db->where("promotion_id", $promotion->id)->get("promotion_product")->result();
        $ids = array_map(function ($item) { return $item->product_id;}, $products_ids);

        $this->load->library('products');
        $this->products->filtered($this->lclang, $category->uri, $this->data['credit'], $this->data['credit_partner_ids'], $ids);

        return true;
    }
}
