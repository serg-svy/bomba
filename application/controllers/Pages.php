<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pages extends FrontEndController
{
    private $page_id;

    public function __construct() {
        parent::__construct();
        $this->page_id = 1;

        $this->data['credit'] = $this->db->order_by('months ASC')->where('is_shown', 1)->get('credit')->result_array();
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

        //$this->breadcrumbs[] = $this->_generate_bc_data($page->title, $page->uri);

        foreach(language(true) as $lang){
            $array = $lang.'_urls';
            $this->{$array}[] = $page->uri;
        }

        $this->loadOGImgData($page);
        $this->_init_seo_data($page);

        $sliders = curl_post('content/sliders', ['lang' => $this->lclang], false);

        $this->load->model('main_banners_model');
        $banners = $this->main_banners_model->get_banners($this->lclang);

        $this->load->model('main_blocks_model');
        $block_request = $this->main_blocks_model->get_blocks($this->lclang);

        $blocks = [];
        foreach($block_request as $block_row) {
            $block_ids = $this->db->where("main_block_id", $block_row->id)->get("main_blocks_product")->result();

            if($block_ids) {
                $product_ids = array_map(function($item){return $item->product_id;}, $block_ids);
                $blocks[$block_row->position] =  $block_row;
                $block_products = curl_post('product/find', ['lang' => $this->lclang, 'ids' => $product_ids, 'limit'=> 8]);
                $blocks[$block_row->position]->products = $block_products;
                if($block_products and $block_row->position == 2) {
                    $category_ids = array_unique(array_map(function($item){return $item->category_id;}, $block_products));
                    $blocks[$block_row->position]->categories = curl_post('category/findByIds', ['lang' => $this->lclang, 'ids' => $category_ids], false);
                }
            }
        }

        $new_products = curl_post('product/findByType', ['lang' => $this->lclang, 'type' => 'is_new'], false);

        if(isset($_SESSION['watched_products'])) {
            $watched_products = curl_post('product/find', ['lang' => $this->lclang, 'ids' => $_SESSION['watched_products'], 'limit' => 8], true);
        } else {
            $watched_products = [];
        }

        $bestseller_products_result = $this->db->select("
            bestseller.name_$this->lclang as title,
            bestseller_product.product_id as product_id,
            bestseller_product.bestseller_id as bestseller_id
            ")
            ->join("bestseller", "bestseller.id = bestseller_product.bestseller_id")
            ->group_by("bestseller_product.bestseller_id")->get('bestseller_product')->result();

        $bestseller_products = [];
        foreach($bestseller_products_result as $bestseller_products_item) {
            $product = curl_post('product/findOne', ['lang' => $this->lclang, 'id' => $bestseller_products_item->product_id], false);
            if($product) {
                $bestseller_products[$bestseller_products_item->bestseller_id] = $product;
                $bestseller_products[$bestseller_products_item->bestseller_id]->bestseller_title = $bestseller_products_item->title;
            }
        }

        $this->load->model('brands_model');
        $this->data['brands'] = $this->brands_model->get_popular();

        $popular_categories = curl_post('category/popular', ['lang' => $this->lclang], true);

        $benefit_products = curl_post('product/findByType', ['lang' => $this->lclang, 'type' => 'is_benefit'], false);

        $this->load->model('shops_model');
        $shops = $this->shops_model->get_main_shops($this->lclang);

        $this->data['inner_view'] = 'pages/main/index';
        $this->data['page'] = $page;
        $this->data['sliders'] = $sliders;
        $this->data['banners'] = $banners;
        $this->data['blocks'] = $blocks;
        $this->data['new_products'] = $new_products;
        $this->data['watched_products'] = $watched_products;
        $this->data['bestseller_products'] = $bestseller_products;
        $this->data['popular_categories'] = $popular_categories;
        $this->data['benefit_products'] = $benefit_products;
        $this->data['shops'] = $shops;

        $this->_render();
    }

    public function text_pages() {
        $page = $this->menu_model->get_page_data($this->clang, $this->uri2);
        if (empty($page)) throw_on_404();

        $this->breadcrumbs[] = $this->_generate_bc_data($page->title, $page->uri);

        foreach(language(true) as $lang){
            $array = $lang.'_urls';
            $this->{$array}[] = $page->uri;
        }

        $this->loadOGImgData($page);
        $this->_init_seo_data($page);

        $this->data['inner_view'] = 'pages/main/text';
        $this->data['page'] = $page;

        $this->_render();
    }
}
