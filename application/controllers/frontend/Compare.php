<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Compare extends FrontEndController
{
    private $page_id;

    public function __construct() {
        parent::__construct();
        $this->page_id = 15;
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
        $ids = $_SESSION['compare'];
        $products = curl_post('product/find', ['lang' => $this->lclang, 'ids' => $ids, 'limit' => 513], true);


        if ($products) {
            $counts = array_count_values(array_column($products, 'category_id'));
            $category_ids = array_unique(array_map(function ($item) {return $item['category_id'];}, $products));
            $categories_request = curl_post('category/findByIds', ['lang' => $this->lclang, 'ids' => $category_ids], true);

            $categories = [];
            foreach($categories_request as $categories_row) {
                $categories[$categories_row['id']] = $categories_row;
                $categories[$categories_row['id']]['count'] = $counts[$categories_row['id']];
            }
            $category = (isset($get['cat']) and !empty($get['cat']) and isset($categories[$get['cat']])) ? (object) $categories[$get['cat']] :  (object) current($categories);
            if (empty($category)) throw_on_404();

            $this->load->library('products');
            $this->data = array_merge($this->data, $this->products->get($this->lclang, $category, $ids));

            $real = [];

            foreach($this->data['products'] as &$product) {
                $attribute_groups = curl_post('cache/attributes_for_product', ['lang' => $this->lclang, 'attribute_set_id' => $product['attribute_set_id'], 'product_id' => $product['id']], true);

                foreach($attribute_groups as &$attribute_group) {

                    if(!isset($real[$attribute_group['group_id']])) {
                        $real[$attribute_group['group_id']] = [
                            'group_id' => $attribute_group['group_id'],
                            'group_name' => $attribute_group['name']
                        ];
                    }

                    foreach($attribute_group['attributes'] as &$attribute) {
                        $attribute['value'] = '';
                        if (!empty($attribute['values']['value'])) {
                            $attribute['value'] = trim(str_replace("\N", '', $attribute['values']['value']));
                        }
                        if ($attribute['attribute_type'] == 'boolean') {
                                if ($attribute['value'] == 1) $attribute['value'] = YES;
                                if ($attribute['value'] == 0) $attribute['value'] = NO;
                        }
                        unset($attribute['values']);

                        if(!isset($real[$attribute_group['group_id']]['attributes'][$attribute['id']])) {
                            $real[$attribute_group['group_id']]['attributes'][$attribute['id']] = [
                                'attribute_name' => $attribute['name'],
                                'attribute_id' => $attribute['id'],
                                'attribute_type' => $attribute['attribute_type'],
                            ];
                        }

                        $real[$attribute_group['group_id']]['attributes'][$attribute['id']]['values'][] = $attribute['value'];

                        $current_values = $real[$attribute_group['group_id']]['attributes'][$attribute['id']]['values'];
                        $hidden = false;
                        $class = 'dif';
                        if(strlen(implode($current_values)) == 0) $hidden = true;
                        if(count(array_unique($current_values)) === 1) $class = 'same';

                        $real[$attribute_group['group_id']]['attributes'][$attribute['id']]['hidden'] = $hidden;
                        $real[$attribute_group['group_id']]['attributes'][$attribute['id']]['class'] = $class;
                    }
                }
            }

            foreach($real as &$group) {
                $hidden = array_unique(array_column($group['attributes'], 'hidden'));
                if(count($hidden) == 1 and in_array(1, $hidden)) unset($real[$group['group_id']]);
            }

            $this->data['inner_view'] = 'pages/compare/products';
            $this->data['find_categories'] = $categories;
            $this->data['category'] = $category;
            $this->data['real'] = $real;

        } else {
            $page->title = COMPARE_NOT_FOUND_TITLE;
            $page->subtitle = COMPARE_NOT_FOUND_SUBTITLE;
            $this->data['inner_view'] = 'pages/compare/notfound';
        }

        $this->data['page'] = $page;

        $this->_render();
    }
}
