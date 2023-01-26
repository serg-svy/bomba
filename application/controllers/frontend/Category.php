<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Category extends FrontEndController
{
    private $page_uri;

    public function __construct() {
        parent::__construct();
        $this->page_uri = 'category';
        $this->load->model("product_model");
    }

    private function _init_seo_data($page) {
        $this->data['page_title'] = (!empty($page->seo_title)) ? $page->seo_title : str_replace("{{title}}", $page->title, SEO_TITLE_TEMPLATE);
        $this->data['page_name'] = $page->title;

        $this->data['keywords_for_layout'] = (!empty($page->seo_keywords)) ? $page->seo_keywords : "";
        $this->data['description_for_layout'] = (!empty($page->seo_desc)) ? $page->seo_desc : str_replace('{{delivery}}', SEO_DELIVERY_TEMPLATE, str_replace("{{title}}", $page->title, SEO_DESC_TEMPLATE));
        $this->data['otitle'] = (!empty($page->seo_title)) ? $page->seo_title : str_replace("{{title}}", $page->title, SEO_TITLE_TEMPLATE);;
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

    public function categories() {
        $page = $this->menu_model->get_page_data_by_id($this->lclang, 11);
        if (empty($page)) throw_on_404();

        $this->breadcrumbs[] = $this->_generate_bc_data($page->title, $page->uri);

        foreach(language(true) as $lang){
            $array = $lang.'_urls';
            $this->{$array}[] = $page->uri;
        }

        $this->loadOGImgData($page);
        $this->_init_seo_data($page);


        $this->data['page'] = $page;
        $this->data['inner_view'] = 'pages/category/categories';

        $this->_render();
    }

    public function index() {

        $page = $this->menu_model->get_page_data_by_id($this->lclang, 11);
        if (empty($page)) throw_on_404();

        $this->data['link_parts'] = explode("__", uri(3));

        $category = curl_post('category/findOne', ['lang' => $this->lclang, 'uri' => $this->data['link_parts'][0]], false);
        if (empty($category)) throw_on_404();

        $fictive_category = $this->db->select("
            title_$this->lclang as title, 
            meta_title_$this->lclang as seo_title,
            seo_desc_$this->lclang as seo_desc,
            desc_$this->lclang as text,
        ")
            ->where("rendered_link", uri(3))->get("category_filtered")->row();

        if($fictive_category) {
            $category->title = $fictive_category->title;
            $category->seo_title = $fictive_category->seo_title;
            $category->seo_desc = $fictive_category->seo_desc;
            $category->text = $fictive_category->text;
        }

        $category->subcategories = curl_post('category/subcategories', ['lang' => $this->lclang, 'id' => $category->id], false);

        $all_categories = curl_post('category/all', ['lang' => $this->lclang], true);
        $_SESSION['cat_ids'] = false;
        categories_cat_ids($all_categories, $category->id);

        if(is_array($_SESSION['cat_ids'])) {
            ksort($_SESSION['cat_ids']);

            foreach ($_SESSION['cat_ids'] as $key) {
                $this->breadcrumbs[] = $this->_generate_bc_data($all_categories[$key]['title'], $all_categories[$key]['uri']);
            }

            foreach(language(true) as $lang){
                $array = $lang.'_urls';
                $this->{$array}[] = $this->page_uri;
            }

            foreach(language(true) as $lang){
                $array = $lang.'_urls';
                $this->{$array}[] = $all_categories[$key]['uri'];
            }
        }

        if ($category->need_update) curl_post('cache/init_category_cache', ['lang' => 'ru', 'id' => $category->id], false);

        if (!$category->subcategories) {
            $ids = [];
            if($this->query) {
                $ids = curl_post('product/search', ['lang' => $this->lclang, 'query' => $this->query, "limit" => 1000, "full" => false], true);
            }
            $this->load->library('products');
            $this->data = array_merge($this->data, $this->products->get($this->lclang, $category, $ids));
        } else {
            $this->load->model('category_banners_model');
            $this->data['left_banners'] = $this->category_banners_model->get_banners_for_category($this->lclang, $category->id, 2);
            $this->data['right_banners'] = $this->category_banners_model->get_banners_for_category($this->lclang, $category->id, 4);
            $this->data['mobile_banners'] = $this->category_banners_model->get_banners_for_category($this->lclang, $category->id, 5);

            $this->load->model('brands_model');
            $this->data['brands'] = $this->brands_model->get_popular();

            $this->data['recommended'] = curl_post('product/findByType', ['lang' => $this->lclang, 'type' => 'is_recommended'], false);
            $this->data['popular'] = curl_post('product/findByType', ['lang' => $this->lclang, 'type' => 'is_popular', 'category_id' => $category->id], false);

            $this->data['inner_view'] = 'pages/category/index';
        }

        $this->data['category'] = $category;

        $this->loadOGImgData($category, 'category');
        $this->_init_seo_data($category);

        $this->data['page'] = $category;

        $this->_render();
    }

    public  function filters_only() {
        $this->data['link_parts'] = explode("__", uri(3));

        $ids = [];
        if($this->query) {
            $ids = curl_post('product/search', ['lang' => $this->lclang, 'query' => $this->query, "limit" => 1000, "full" => false], true);
        }

        $this->load->library('products');
        $this->products->filtered($this->lclang, $this->data['link_parts'][0], $this->data['credit'], $this->data['credit_partner_ids'], $ids);
    }
}
