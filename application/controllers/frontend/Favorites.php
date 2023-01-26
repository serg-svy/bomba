<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Favorites extends FrontEndController
{
    private $page_id;

    public function __construct() {
        parent::__construct();
        $this->page_id = 21;
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

        $get = $this->input->get(null, true);
        $ids = $_SESSION['favorite'];
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

            $this->load->library('products');
            $this->data = array_merge($this->data, $this->products->get($this->lclang, $category, $ids));

            $this->data['inner_view'] = 'pages/favorites/products';
            $this->data['find_categories'] = $categories;
            $this->data['category'] = $category;
            $this->data['link_parts'][] = $category->uri;

        } else {
            $page->title = FAVORITES_NOT_FOUND_TITLE;
            $page->subtitle = FAVORITES_NOT_FOUND_SUBTITLE;
            $this->data['inner_view'] = 'pages/favorites/notfound';
        }

        $this->data['page'] = $page;

        $this->_render();
    }

    public  function filters_only(): bool
    {
        $category_id = $this->input->get('cat', true);
        $category = curl_post('category/findOne', ['lang' => $this->lclang, 'uri' => $category_id], false);
        if (!$category) return false;

        $ids = $_SESSION['favorite'];

        $this->load->library('products');
        $this->products->filtered($this->lclang, $category->uri, $this->data['credit'], $this->data['credit_partner_ids'], $ids);

        return true;
    }
}
