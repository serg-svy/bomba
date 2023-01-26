<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Search extends FrontEndController
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

    public function index() {
        if(empty($this->input->get('query', true)) and empty($this->input->get('brand', true))) throw_on_404();

        if($this->query) {
            $ids = curl_post('product/search', ['lang' => $this->lclang, 'query' => $this->query, "limit" => 1000, "full" => false], true);
            if($ids) {
                // bagam in sesie si in popular cautarea data
                $_SESSION['previous_search'][$this->query] = $this->query;
                $popular_request = $this->db->where("query", $this->query)->get("popular_requests")->row();
                if($popular_request) {
                    $this->db->where("query", $popular_request->query)->update('popular_requests', ['count' => $popular_request->count+1]);
                } else {
                    $this->db->insert("popular_requests", ['uri' => '', 'query' => $this->query, 'count' => 1]);
                }
            }
        } else {
            $brand_id = array_key_first($this->input->get('brand', true));
            $ids = curl_post('product/brand', ['lang' => $this->lclang, 'brand_id' => $brand_id, "limit" => 1000], true);

            $this->load->model('brand_banners_model');
            $this->data['left_banners'] = $this->brand_banners_model->get_banners_for_brand($this->lclang, $brand_id, 1);
            $this->data['right_banners'] = $this->brand_banners_model->get_banners_for_brand($this->lclang, $brand_id, 2);
            $this->data['mobile_banners'] = $this->brand_banners_model->get_banners_for_brand($this->lclang, $brand_id, 3);
        }

        if($ids) {
            $page = $this->menu_model->get_page_data_by_id($this->lclang, 27);
            if (empty($page)) throw_on_404();

            if(!$this->query) {
                $brand = $this->db->where("id", $brand_id)->get("brand")->row();
                $page->title .= ' ' . $brand->name;
            }

            $this->breadcrumbs[] = $this->_generate_bc_data($page->title, $page->uri);

            foreach(language(true) as $lang){
                $array = $lang.'_urls';
                $this->{$array}[] = $page->uri;
            }

            $this->loadOGImgData($page);
            $this->_init_seo_data($page);

            $this->load->library('products');
            $this->data = array_merge($this->data, $this->products->get($this->lclang, false, $ids));

            $result = $this->db->select("count(product_id) as count, category_id as category_id")
                ->where_in("product_id", $ids)
                ->group_by("category_id")->get("category_product")->result_array();

            $find_counts = [];
            foreach ($result as $row) {
                $find_counts[$row['category_id']] = $row['count'];
            }

            $category_ids = array_map(function($item){return $item['category_id'];}, $result);

            $categories_result = curl_post('category/findByIds', ['lang' => $this->lclang, 'ids' => $category_ids], true);

            usort($categories_result, function($a, $b) {
                return (int) ($a['priority'] < $b['priority']);
            });

            $find_categories = [];
            foreach ($categories_result as $categories_row) {
                if (isset($find_counts[$categories_row['id']])) {
                    $find_categories[$categories_row['id']] = $categories_row;
                    $find_categories[$categories_row['id']]['count'] = $find_counts[$categories_row['id']];
                }
            }

            $find_categories_tree = $this->createTreeForFindCategories($categories_result);

            $this->data['get_params_for_search'] = (!empty($this->input->get('query', true))) ? "?query=".$this->input->get('query', true) : "?brand[".array_key_first($this->input->get('brand', true))."]=".array_key_first($this->input->get('brand', true));
            $this->data['find_categories'] = $find_categories;
            $this->data['find_categories_tree'] = $find_categories_tree;
            $this->data['page'] = $page;
            $this->data['inner_view'] = 'pages/search/products';
        } else {
            $page = $this->menu_model->get_page_data_by_id($this->lclang, 20);
            if (empty($page)) throw_on_404();

            $this->breadcrumbs[] = $this->_generate_bc_data($page->title, $page->uri);

            foreach(language(true) as $lang){
                $array = $lang.'_urls';
                $this->{$array}[] = $page->uri;
            }

            $this->loadOGImgData($page);
            $this->_init_seo_data($page);

            $page->subtitle = str_replace('{query}', $this->query, $page->text);

            $new_products = curl_post('product/findByType', ['lang' => $this->lclang, 'type' => 'is_new'], false);

            $this->load->model("promotions_model");
            $promotions = $this->promotions_model->get_promotions($this->clang, false, 0, 4);

            $this->data['page'] = $page;
            $this->data['new_products'] = $new_products;
            $this->data['promotions'] = $promotions;
            $this->data['inner_view'] = 'pages/search/notfound';
        }

        $this->_render();
    }

    public  function filters_only() {

        if($this->query) {
            $ids = curl_post('product/search', ['lang' => $this->lclang, 'query' => $this->query, "limit" => 1000, "full" => false], true);
        } else {
            $brand_id = array_key_first($this->input->get('brand', true));
            $ids = curl_post('product/brand', ['lang' => $this->lclang, 'brand_id' => $brand_id, "limit" => 1000], true);
        }

        $this->load->library('products');
        $this->products->filtered($this->lclang, false, $this->data['credit'], $this->data['credit_partner_ids'], $ids);
    }

    private function createTreeForFindCategories($find_categories) {
        if(empty($find_categories)) return [];

        $parent_category_ids = [];
        $all_categories = curl_post('category/all', ['lang' => $this->lclang], true);

        foreach ($find_categories as $categories_row) {
            categories_cat_ids($all_categories, $categories_row['id']);
            $parent_category_ids = array_merge($_SESSION['cat_ids'], $parent_category_ids);
        }

        $parent_category_ids = array_unique($parent_category_ids);

        $parent_categories = curl_post('category/findByIds', ['lang' => $this->lclang, 'ids' => $parent_category_ids], true);

        foreach($parent_categories as $parent_category) {
            unset($parent_category['h1']);
            unset($parent_category['seo_title']);
            unset($parent_category['seo_keywords']);
            unset($parent_category['seo_desc']);
            unset($parent_category['description']);
            $levels[$parent_category['level']][$parent_category['id'].''] = $parent_category;
        }

        $main_list = $levels[1];
        $level2 = $levels[2];

        if(isset($levels[4])) {
            foreach ($levels[4] as $id => $category) {
                if (!empty($levels[3][$category['parent_id']])) {
                    $levels[3][$category['parent_id']]['children'][$id . ''] = $category;
                }
            }
        }

        foreach($levels[3] as $id => $category) {
            if (!empty($level2[$category['parent_id']])) {
                $level2[$category['parent_id']]['children'][$id.''] = $category;
            }
        }

        foreach($level2 as $id => $category) {
            if (!empty($main_list[$category['parent_id']])) {
                $main_list[$category['parent_id']]['children'][$id.''] = $category;
            }
        }

        return $main_list;
    }
}
