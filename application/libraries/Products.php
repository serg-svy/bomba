<?php defined('BASEPATH') OR exit('No direct script access allowed');

use JasonGrimes\Paginator;

class Products {

    protected $CI;

    public function __construct()
    {
        $this->CI =& get_instance();
    }

    public function get($lang, $category, $ids = []): array
    {
        $response = [];

        $this->CI->load->model('stores_model');
        $response['stores'] = $this->CI->stores_model->get_all_stores($lang);

        $filtered = curl_post('product/filtered', [
            'lang' => $lang,
            'uri' => uri(3),
            'category_id' => ($category) ? $category->id : false,
            'ids' => $ids,
            'category_uri' => ($category) ? $category->uri : false,
            'get' => $this->CI->input->get(null, true),
            'terminalFlag' => false,
            'first' => true,
        ], true);

        $active_filters = array();

        if($filtered['products'] and $category) {
            $filters_for_category = curl_post('cache/filters_for_category', [
                'lang' => $lang,
                'category_id' => $category->id
            ], true);

            $response['filters'] = $filters_for_category['filters'];
            $response['tag_attributes'] = $filters_for_category['tag_attributes'];

            // scoatem filtrele active pentru a le dezactiva pe cele cu count 0
            if($this->CI->input->get('query', true) or $category->multiple_filters == 0 or $ids) {
                $request = $this->CI->db->select("count(distinct(articol)) as count, attribute_id, value_ro")->where("category_id", $category->id)->where_in("articol", $filtered['articols'])->group_by("attribute_id, value_ro")->get("product_attribute_value_cached")->result();
                foreach ($request as $row) {
                    if ($row->value_ro == '\N') continue;
                    $active_filters[$row->attribute_id][transliteration($row->value_ro)] = $row->count;
                }
            }
        } else {
            $response['filters'] = [];
            $response['tag_attributes'] = [];
        }

        if(isset($filtered['redirect']['flag']) and $filtered['redirect']['flag']) redirect($filtered['redirect']['link'], 'refresh');

        $uri_parts = explode('?', $_SERVER['REQUEST_URI'], 2);
        $urlPattern = $uri_parts[0].'?page=(:num)';

        $paginator = new Paginator($filtered['count'], $filtered['limit'], $filtered['page'], $urlPattern);
        $paginator->setMaxPagesToShow(5);
        $response['paginator'] = $paginator;

        if($category) {
            $this->CI->load->model('category_banners_model');
            $response['product_banners'] = $this->CI->category_banners_model->get_banners_for_category($lang, $category->id, 3);
        }

        $response['inner_view'] = 'pages/category/products';
        $response['sorter_view'] = $filtered['sorter_view'];
        $response['cat'] = ($category) ? $category->id : false;
        $response['query'] = $filtered['query'];
        $response['sort'] = $filtered['sort'];
        $response['limit_view'] = $filtered['limit_view'];
        $response['limit'] = $filtered['limit'];
        $response['get_store'] = $filtered['get_store'];
        $response['brands'] = $filtered['brands'];
        $response['products'] = $filtered['products'];
        $response['count'] = $filtered['count'];
        $response['min_price'] = $filtered['min_price'];
        $response['max_price'] = $filtered['max_price'];
        $response['spoiler'] = $filtered['spoiler'];
        $response['active_filters'] = $active_filters;
        $response['deliveryCourier'] = getCourierDelivery($lang, $_SESSION['city_id']);

        return $response;
    }

    public function filtered($lang, $category_uri, $credit, $credit_partner_ids, $ids = []) {

        $category = false;
        if($category_uri) $category = curl_post('category/findOne', ['lang' => $lang, 'uri' => $category_uri], false);

        $apiData = curl_post('product/filtered', [
            'lang' => $lang,
            'uri' => uri(3),
            'category_id' => ($category) ? $category->id : false,
            'ids' => $ids,
            'category_uri' => ($category) ? $category->uri : false,
            'get' => $this->CI->input->get(null, true),
            'terminalFlag' => false,
            'first' => false,
        ], true);

        $apiData['lclang'] = $lang;

        $uri_parts = explode('?', $_SERVER['REQUEST_URI'], 2);
        $urlPattern = $uri_parts[0].'?page=(:num)';

        $paginator = new Paginator($apiData['count'], $apiData['limit'], $apiData['page'], $urlPattern);
        $paginator->setMaxPagesToShow(5);
        $apiData['paginator'] = $paginator;

        $apiData['deliveryCourier'] = getCourierDelivery($lang, $_SESSION['city_id']);

        if($category) {
            $filters_for_category = curl_post('cache/filters_for_category', [
                'lang' => $lang,
                'category_id' => $category->id
            ], true);

            $apiData['tag_attributes'] = $filters_for_category['tag_attributes'];

            if ($category->multiple_filters == 0) {
                $request = $this->CI->db->select("count(distinct(articol)) as count, attribute_id, value_ro")->where("category_id", $category->id)->where_in("articol", $apiData['articols'])->group_by("attribute_id, value_ro")->get("product_attribute_value_cached")->result();
                foreach ($request as $row) {
                    if ($row->value_ro == '\N') continue;
                    $response['active_filters'][$row->attribute_id][transliteration($row->value_ro)] = $row->count;
                }
            }

            $response['multiple_filters'] = (int) $category->multiple_filters;
        }
        $apiData['credit_partner_ids'] = $credit_partner_ids;
        $apiData['credit'] = $credit;

        $response['view'] = $this->CI->load->view('layouts/pages/ajax__list', $apiData, true);
        $response['filters'] = $this->CI->load->view('layouts/pages/ajax__filters', $apiData, true);
        $response['count'] = $apiData['count'];

        if(isset($apiData['redirect']['flag']) and $apiData['redirect']['flag']) $response['redirect'] = $apiData['redirect']['link'];

        echo json_encode($response, true);
    }
}
