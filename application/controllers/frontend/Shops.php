<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Shops extends FrontEndController
{
    private $page_id;

    public function __construct() {
        parent::__construct();
        $this->page_id = 26;
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

        $this->load->model('shops_model');
        $shop = $this->shops_model->get_shop_by_id($this->lclang, uri(3));
        if (empty($shop)) throw_on_404();

        $this->load->model('shop_advantages_model');
        $advantages = $this->shop_advantages_model->get_shop_advantages($this->lclang, $shop->id);

        $this->load->model('shop_categories_model');
        $shop_categories = $this->shop_categories_model->get_shop_categories($this->lclang, $shop->id);

        $result = $this->db->where("shop_id", $shop->id)->order_by("sorder asc, product_id asc")->get("shop_product")->result();
        $product_categories = $best_products = [];
        if($result) {
            $product_ids = array_map(function ($item) {return $item->product_id;}, $result);
            $best_product_ids = array_map(function ($item) {return ($item->best) ? $item->product_id : 0;}, $result);

            $products = curl_post('product/find', ['lang' => $this->lclang, 'ids' => $product_ids, 'limit' => 999], true);

            if($products) {
                $this->load->model('category_banners_model');
                $category_ids = array_map(function ($item) {
                    return $item['category_id'];
                }, $products);
                $categories_result = curl_post('category/findByIds', ['lang' => $this->lclang, 'ids' => $category_ids], false);
                $product_categories = [];
                foreach ($categories_result as $categories_row) {
                    $product_categories[$categories_row->id] = $categories_row;
                    $product_categories[$categories_row->id]->banners = $this->category_banners_model->get_banners_for_category($this->lclang, $categories_row->id, 7);
                    $product_categories[$categories_row->id]->mobile_banners = $this->category_banners_model->get_banners_for_category($this->lclang, $categories_row->id, 8);
                }

                foreach ($products as $product) {
                    $product_categories[$product['category_id']]->products[] = $product;
                    if(in_array($product['id'], $best_product_ids)) $best_products[] = $product;
                }
            }
        }

        $this->breadcrumbs[] = $this->_generate_bc_data($shop->title, $shop->id);

        foreach(language(true) as $lang){
            $array = $lang.'_urls';
            $this->{$array}[] = $shop->id;
        }

        $this->loadOGImgData($page);
        $this->_init_seo_data($page);

        $this->data['inner_view'] = 'pages/shops/index';
        $this->data['page'] = $shop;
        $this->data['shop'] = $shop;
        $this->data['advantages'] = $advantages;
        $this->data['shop_categories'] = $shop_categories;
        $this->data['product_categories'] = $product_categories;
        $this->data['best_products'] = $best_products;

        $this->_render();
    }
}
