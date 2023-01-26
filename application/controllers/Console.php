<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Console extends FrontEndController
{
    public function manual_create_cache($category_url) {

        ini_set('memory_limit', '2048M');
        ini_set('max_execution_time', '0');

        $category = $this->db->select("id")->where("url", $category_url)->get("category")->row();
        if($category) {
            echo $category->id;
            curl_post('cache/init_category_cache', ['lang' => 'ru', 'id' => $category->id], false);
        }
    }

    public function create_product_url() {
        $result = $this->db->select("name_ro, id")->where("uri", "")->limit(1000)->get("product")->result();

        foreach ($result as $row) {
            $uri = transliteration($row->name_ro . '-' . $row->id);
            $this->db->where("id", $row->id)->update("product", ["uri" => $uri]);
        }

        dump(count($result));
    }

    public function create_product_thumbs($articol, $color): bool
    {
        $product = $this->db->where("articol", $articol)->get("product")->row();
        if(!$product) return false;

        $photos = [];
        $dopa = substr($articol, -2);
        $path = realpath('public/products/' . $dopa . '/' . $articol . '/'. $color);
        for ($i = 1; $i <= 10; $i++) {
            if (is_file($path . '/' . $i . '.jpg')) {
                $photos[] = $i . '.jpg';
            }
        }

        foreach($photos as $photo) {
            product_image($photo, $articol, $color, 968, 630);
            product_image($photo, $articol, $color, 222, 166);
            product_image($photo, $articol, $color, 68, 68);
        }

        return true;
    }

    public function setPartnerProducts(){
        ini_set('memory_limit', '256M');

        $json = file_get_contents('products.json');
        $products = json_decode($json, false);

        $i=0;
        $ins = [];

        foreach ($products as $product) {
            $i++;

            $ins[$product->id] = [
                'id' => $product->id,
                'code' => $product->code,
                'brand_id' => $product->brand_id,
                'sku' => $product->sku,
                'articol' => $product->articol,
                'name_ro' => $product->name_ro,
                'name_ru' => $product->name_ru,
                'short_description_ro' => $product->short_description_ro,
                'short_description_ru' => $product->short_description_ru,
                'description_ro' => $product->description_ro,
                'description_ru' => $product->description_ru,
                'meta_keywords_ro' => $product->meta_keywords_ro,
                'meta_keywords_ru' => $product->meta_keywords_ru,
                'meta_description_ro' => $product->meta_description_ro,
                'meta_description_ru' => $product->meta_description_ru,
                'is_new' => $product->is_new,
                'is_popular' => $product->is_popular,
                'is_recommended' => $product->is_recommended,
                'attribute_set_id' => $product->attribute_set_id,
                'rate' => $product->rate,
                'razm_setka' => $product->razm_setka,
                'partner_id' => $product->partner_id,
                'youtube' => $product->youtube,
                'is_promo_preorder' => $product->is_promo_preorder,
                'gift' => $product->gift,
                'is_benefit' => 0,
                'uri' => generate_url($product->id, $product->name_ro)
            ];

            if($i == 1000) {
                echo count($ins).'<br>';
                $this->db->insert_batch('product', $ins);
                $i=0;
                $ins = [];
            }
        }
        echo count($ins).'<br>';
        $this->db->insert_batch('product', $ins);
    }

    public function setPartnerPrices(){
        ini_set('memory_limit', '256M');

        $pricesJson = file_get_contents('prices.json');
        $prices = json_decode($pricesJson, false);

        $idsJson = file_get_contents('ids.json');
        $ids = json_decode($idsJson, false);

        $i=0;
        $ins = [];

        foreach ($prices as $price) {
            if(in_array($price->id, $ids)) {
                $i++;
                $ins[$price->id] = [
                    'product_id' => $price->id,
                    'currency_price' => 0,
                    'price' => $price->price1,
                    'discounted_price' => $price->price4,
                    'partner_id' => $price->partner_id,
                    'manual_change' => $price->manual_change,
                ];

                if ($i == 1000) {
                    echo count($ins) . '<br>';
                    $this->db->insert_batch('product_price', $ins);
                    $i = 0;
                    $ins = [];
                }
            }
        }
        echo count($ins).'<br>';
        $this->db->insert_batch('product_price', $ins);
    }

    public function setPartnerStocks(){
        ini_set('memory_limit', '256M');

        $stocksJson = file_get_contents('stocks.json');
        $stocks = json_decode($stocksJson, false);

        $idsJson = file_get_contents('ids.json');
        $ids = json_decode($idsJson, false);

        $i=0;
        $ins = [];

        foreach ($stocks as $stock) {
            if(in_array($stock->product_id, $ids)) {
                $i++;
                unset($stock->id);
                $ins[] = $stock;

                if ($i == 1000) {
                    echo count($ins) . '<br>';
                    $this->db->insert_batch('product_stock', $ins);
                    $i = 0;
                    $ins = [];
                }
            }
        }
        echo count($ins).'<br>';
        $this->db->insert_batch('product_stock', $ins);
    }

    public function setPartnerCategories(){
        ini_set('memory_limit', '256M');

        $categoriesJson = file_get_contents('categories.json');
        $categories = json_decode($categoriesJson, false);

        $idsJson = file_get_contents('ids.json');
        $ids = json_decode($idsJson, false);

        $i=0;
        $ins = [];

        foreach ($categories as $category) {
            if(in_array($category->product_id, $ids)) {
                $i++;
                unset($category->id);
                $ins[$category->product_id.'-'.$category->category_id] = $category;

                if ($i == 1000) {
                    echo count($ins) . '<br>';
                    $this->db->insert_batch('category_product', $ins);
                    $i = 0;
                    $ins = [];
                }
            }
        }
        echo count($ins).'<br>';
        $this->db->insert_batch('category_product', $ins);
    }

    public function setPartnerValues(){
        ini_set('memory_limit', '256M');

        $valuesJson = file_get_contents('values.json');
        $values = json_decode($valuesJson, false);

        $idsJson = file_get_contents('ids.json');
        $ids = json_decode($idsJson, false);

        $i=0;
        $ins = [];

        foreach ($values as $value) {
            if(in_array($value->product_id, $ids)) {
                $i++;
                unset($value->count);
                $ins[$value->product_id.'-'.$value->attribute_id] = $value;

                if ($i == 1000) {
                    echo count($ins) . '<br>';
                    $this->db->insert_batch('product_attribute_value', $ins);
                    $i = 0;
                    $ins = [];
                }
            }
        }
        echo count($ins).'<br>';
        $this->db->insert_batch('product_attribute_value', $ins);
    }

    public function generateUrlForAll() {

        $products = $this->db->where('uri', '')->limit(1000)->get('product')->result();
        foreach ($products as $product) {
            generate_url($product->id, $product->name_ro, true);
        }

        echo "ok";
    }

    public function checkImages() {
        $this->load->view('console/check_images');
    }

    public function checkImagesJson() {
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $json = file_get_contents('php://input');
            $post = json_decode($json, true);

            $articol = $post['articol'];

            $dopa = substr($articol, -2);
            $dir = realpath('public')."/products/" . $dopa . "/" . $articol;

            if(!is_dir($dir)) {
                $response = array('error' => 'true', 'text' => 'is not a folder', 'found' => 0);
            } else {
                $dirs = array_filter(glob($dir . '/*'), 'is_dir');
                $count = 0;
                foreach ($dirs as $directory) {
                    $pizza = explode('/', $directory);
                    if (end($pizza) != 'thumbs') {
                        $images = glob($directory . "/*.jpg");
                        if($images) $count = $count + count($images);
                    }
                }

                if($count == 0) {
                    $response = array('error' => 'true', 'text' => 'Empty folder', 'found' => 0);
                } else {
                    $response = array('error' => 'false', 'text' => 'ok', 'found' =>$count);
                }
            }
        } else {
            $response = array('error' => 'true', 'text' => 'method not allowed', 'found' => 0);
        }
        echo json_encode($response);
    }

    // elastic

    public function elasticPopulateCategories() {
        $this->load->library('elasticsearch');
        $elastic = new Elasticsearch();

        $categories = $this->db->select("id, name_ro, name_ru, tags, priority")->get("category")->result();

        foreach($categories as $category){

            $params['id'] = $category->id;
            $params['body'] = [
                'id' => $category->id,
                'tags' => $category->tags,
                'name_ru' => $category->name_ru,
                'name_ro' => $category->name_ro,
                'priority' => $category->priority,
                'updated_at' => date('Y-m-d H:i:s')
            ];
            $result = $elastic->index($params, 'category');
        }
    }

    public function elasticPopulateAll() {

        ini_set('memory_limit', '256M');

        $this->load->library('elasticsearch');
        $elastic = new Elasticsearch();

        $brands = [];

        $brandsResult = $this->db->get('brand')->result();

        foreach($brandsResult as $brandItem) {
            $brands[$brandItem->id] = $brandItem->name;
        }

        $products = $this->db->select('
            product.*, 
            product_price.discounted_price,
            ')
            ->join('product_price', 'product_price.product_id = product.id')
            ->limit(10000, 20000)->get("product")->result();


        foreach($products as $product){

            $params['id'] = $product->id;
            $params['body'] = [
                'id' => $product->id,
                'articol' => $product->articol,
                'brand' => (isset($brands[$product->brand_id])) ? $brands[$product->brand_id] : '',
                'partner' => $product->partner_id,
                'price' => $product->discounted_price,
                'priority' => $product->priority,
                'name_ru' => $product->name_ru,
                'name_ro' => $product->name_ro,
                'updated_at' => date('Y-m-d H:i:s')
            ];

            $result = $elastic->index($params);
        }

        echo "ok";
    }

    public function elasticPopulateOne($id): bool
    {
        ini_set('memory_limit', '256M');

        if(empty($id)) return false;

        $this->load->library('elasticsearch');
        $elastic = new Elasticsearch();

        $categories = [];
        $result = $this->db->select("id, name_ro, name_ru")->get("category")->result();
        foreach($result as $row) {
            $categories[$row->id] = $row->name_ru . ' ' . $row->name_ro;
        }


        $product = $this->db->select('
            product.*, 
            product_price.discounted_price,
            ')
            ->join('product_price', 'product_price.product_id = product.id')
            ->where("product.id", $id)->get("product")->row();

        if(empty($product)) return false;

        $this->db->select("category_id, product_id");
        $this->db->where("category_id >", 0);
        $this->db->or_where("product_id", $id);
        $result2 = $this->db->get("category_product")->result();

        $product_categories = [];
        foreach($result2 as $row2) {
            if(isset($categories[$row2->category_id])) {
                if (!isset($product_categories[$row2->product_id])) {
                    $product_categories[$row2->product_id] = $categories[$row2->category_id];
                } else {
                    $product_categories[$row2->product_id] .= ' | ' . $categories[$row2->category_id];
                }
            }
        }

        $params['id'] = $product->id;
        $params['body'] = [
            'id' => $product->id,
            'code' => $product->code,
            'articol' => $product->articol,
            'brand' => $product->brand_id,
            'categories' => $product_categories[$product->id] ?? '',
            'partner' => $product->partner_id,
            'price' => $product->discounted_price,
            'name_ru' => $product->name_ru,
            'name_ro' => $product->name_ro,
            'updated_at' => date('Y-m-d H:i:s')
        ];

        $elastic->index($params);

        return true;
    }

    public function elasticSearchCategory() {
        $this->load->library('elasticsearch');
        $elastic = new Elasticsearch();

        $query = '[jkjlbkmybrb';

        $byMulti = [
            'index' => 'app_categories',
            'body'  => [
                'size' => 5,
                'query' => [
                    'multi_match' => [
                        'query' => $query,
                        'fields' => ['tags', 'name_*'],
                        'fuzziness' => 'AUTO',
                        'operator' => 'and',
                    ]
                ],
                'sort' => ['priority' => 'desc', '_score' => 'desc']
            ]
        ];

        $result = $elastic->search($byMulti);
        if($result['hits']['total'] > 0)  dd($result);

        $search_ru = switcher_ru($query);
        $search_en = switcher_en($query);
        $must = $query .' '.$search_ru.' '.$search_en;

        $byMust = [
            'index' => 'app_categories',
            'body'  => [
                'size' => 5,
                'query' => [
                    'bool' => [
                        'must' => [
                            [
                                'multi_match' => [
                                    'query' => $search_ru,
                                    'fields' => [ "tags", "name_*" ],
                                    'operator' => 'or'
                                ]
                            ]
                        ]
                    ]
                ],
                'sort' => ['priority' => 'desc', '_score' => 'desc']
            ]
        ];

        $result = $elastic->search($byMust);
        dump($result);
    }

    public function elasticSearch() {
        $this->load->library('elasticsearch');
        $elastic = new Elasticsearch();

        $query = 'kubb';

        $byArticol = [
            'index' => 'app_products',
            'body'  => [
                'size' => 1,
                'query' => [
                    'match' => [
                        'articol' => $query
                    ]
                ],
            ]
        ];

        $result = $elastic->search($byArticol);
        if($result['hits']['total'] > 0)  dd($result);

        $byBrand = [
            'index' => 'app_products',
            'body'  => [
                'size' => 10,
                'query' => [
                    'multi_match' => [
                        'query' => $query,
                        'fields' => ['brand', 'name_*'],
                        'fuzziness' => 'auto',
                        'operator' => 'and'
                    ]
                ],
                'sort' => ['priority' => 'desc', '_score' => 'desc']
            ]
        ];

        $result = $elastic->search($byBrand);
        if($result['hits']['total'] > 0)  dd($result);

        $search_ru = switcher_ru($query);
        $search_en = switcher_en($query);
        $must = $query .' '.$search_ru.' '.$search_en;

        $byMust = [
            'index' => 'app_products',
            'body'  => [
                'size' => 10,
                'query' => [
                    'bool' => [
                        'must' => [
                            [
                                'multi_match' => [
                                    'query' => $search_ru,
                                    'fields' => [ "brand", "name_*" ],
                                    'operator' => 'or'
                                ]
                            ]
                        ]
                    ]
                ],
                'sort' => ['priority' => 'desc', '_score' => 'desc']
            ]
        ];

        $result = $elastic->search($byMust);

        dd($result);
    }

    public function elasticICU (){
        $icu =[
            'analyzer' => 'latin',
            'text' => '195 65 r15'
        ];

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'http://localhost:9200/app_icu/_analyze',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($icu),
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);

        dump(json_decode($response, true));
    }
}
