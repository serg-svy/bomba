<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use JasonGrimes\Paginator;

class News extends FrontEndController
{
    private $page_id;

    public function __construct() {
        parent::__construct();
        $this->page_id = 6;

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

        $this->breadcrumbs[] = $this->_generate_bc_data($page->title, $page->uri);

        foreach(language(true) as $lang){
            $array = $lang.'_urls';
            $this->{$array}[] = $page->uri;
        }

        $this->loadOGImgData($page);
        $this->_init_seo_data($page);

        $get = array();
        $get['page'] = (isset($_GET['page'])) ? $_GET['page'] : 1;
        $start = ($get['page']-1)*NEWS_PER_PAGE;

        $this->load->model("news_model");
        $news = $this->news_model->get_news($this->clang, $start, NEWS_PER_PAGE);
        $count = $this->news_model->get_news_count();

        $news = array_chunk($news, 4);

        $uri_parts = explode('?', $_SERVER['REQUEST_URI'], 2);
        $urlPattern = $uri_parts[0].'?page=(:num)';

        $paginator = new Paginator($count, NEWS_PER_PAGE, $get['page'], $urlPattern);
        $paginator->setMaxPagesToShow(5);
        $this->data['paginator'] = $paginator;

        $this->data['inner_view'] = 'pages/news/index';
        $this->data['page'] = $page;
        $this->data['news'] = $news;

        $this->_render();
    }

    public function item() {
        $page = $this->menu_model->get_page_data_by_id( $this->clang, $this->page_id);
        if (empty($page)) throw_on_404();

        $this->breadcrumbs[] = $this->_generate_bc_data($page->title, $page->uri);

        foreach(language(true) as $lang){
            $array = $lang.'_urls';
            $this->{$array}[] = $page->uri;
        }

        $this->load->model("news_model");
        $new = $this->news_model->get_new_by_uri($this->lclang, uri(3));
        if (empty($new)) throw_on_404();

        $this->breadcrumbs[] = $this->_generate_bc_data($new->title, $new->uri);

        foreach(language(true) as $lang){
            $array = $lang.'_urls';
            $this->{$array}[] = $new->uri;
        }

        $this->loadOGImgData($page);
        $this->_init_seo_data($page);

        $products = [];
        $request = $this->db->where("news_id", $new->id)->get("news_product")->result();
        if($request) {
            $ids = array_map(function($item) {return $item->product_id;}, $request);
            $products = curl_post('product/find', ['lang' => $this->lclang, 'ids' => $ids, 'limit' => 999], false);
        }

        $photos = [];

        if(count($products) == 1) {
            $product = current($products);

            $dopa = substr($product->articol, -2);
            $path = realpath('public/products/' . $dopa . '/' . $product->articol . '/'. $product->first_color);
            for ($i = 1; $i <= 10; $i++) {
                if (is_file($path . '/' . $i . '.jpg')) {
                    $photos[] = $i . '.jpg';
                }
            }
        }

        $lastNews = $this->news_model->get_last_news($this->clang, $new->id);

        $this->data['inner_view'] = 'pages/news/item';
        $this->data['page'] = $new;
        $this->data['products'] = $products;
        $this->data['photos'] = $photos;
        $this->data['lastNews'] = $lastNews;
        $this->data['deliveryCourier'] = getCourierDelivery($this->lclang, $_SESSION['city_id']);

        $this->_render();
    }
}
