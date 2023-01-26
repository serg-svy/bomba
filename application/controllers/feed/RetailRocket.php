<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class RetailRocket extends CI_Controller
{
    public function __construct(){
        parent::__construct();
        ini_set('display_errors', '1');
        ini_set('display_startup_errors', '1');
        error_reporting(E_ALL);
        ini_set('memory_limit', '512M');
    }

    /**
     * @throws Exception
     */
    public function index()
    {

        $xml = new SimpleXMLElement('<?xml version="1.0" encoding="utf-8"?><yml_catalog></yml_catalog>');
        $xml->addAttribute("date", date('Y-m-d H:i:s'));

        $shop = $xml->addChild('shop');

        $brands = [];
        $brand_result = $this->db->get("brand")->result();
        foreach ($brand_result as $brand_row) {
            $brands[$brand_row->id] = $brand_row->name;
        }

        $prices = [];
        $product_price_result = $this->db->get("product_price")->result();
        foreach ($product_price_result as $product_price_row) {
            $prices[$product_price_row->product_id] = $product_price_row;
        }

        $categories = $shop->addChild('categories');
        $offers = $shop->addChild('offers');

        $categories_tree = curl_post('category/tree', ['lang' => 'ru'], true);
        foreach ($categories_tree as $category_tree) {
            $category = $categories->addChild('category', $category_tree['title']);
            $category->addAttribute('id', $category_tree['id']);
            if(!empty($category_tree['children'])) {
                foreach ($category_tree['children'] as $child) {
                    $category = $categories->addChild('category', $child['title']);
                    $category->addAttribute('id', $child['id']);
                    $category->addAttribute('parentId', $category_tree['id']);
                    if(!empty($child['children'])) {
                        foreach ($child['children'] as $child2) {
                            $category = $categories->addChild('category', $child2['title']);
                            $category->addAttribute('id', $child2['id']);
                            $category->addAttribute('parentId', $child['id']);
                        }
                    }
                }
            }
        }

        $ids = [];

        $category_result = $this->db->select("
            category_product.category_id as id,
            ")
            ->group_by("category_product.category_id")
            ->get('category_product')->result();

        foreach ($category_result as $category_row) {
            $product_result = $this->db->select("
            product.id,
            product.articol,
            product.first_color,
            product.brand_id,
            product.uri,
            product.name_ru,
            product.name_ro,
            product.description_ru,
            ")
            ->join("category_product", "category_product.product_id = product.id")
            ->where("product.is_shown", 1)
            ->where("category_product.category_id", $category_row->id)
            ->group_by("product.id")
            ->get("product")->result();

            foreach ($product_result as $product_row) {
                if(isset($prices[$product_row->id])) {
                    if(!in_array($product_row->id, $ids)) {
                        $offer = $offers->addChild('offer');

                        $lastTwo = substr($product_row->articol, -2);
                        $color = (!empty($product_row->first_color)) ? $product_row->first_color : 'NO_COLOR';
                        $img = base_url() . 'public/products/' . $lastTwo . '/' . $product_row->articol . '/' . $color . '/1.jpg';
                        $img = $this->clearStr($img);

                        $offer->addAttribute('id', $product_row->id);
                        $offer->addAttribute('available', 'true');
                        $offer->addAttribute('group_id', $product_row->articol);

                        $offer->addChild("url", base_url() . 'ru/product/' . $product_row->uri);
                        $offer->addChild("price", $prices[$product_row->id]->price);
                        $offer->addChild("oldprice", $prices[$product_row->id]->discounted_price);
                        $offer->addChild("categoryId", $category_row->id);
                        $offer->addChild("picture", $img);
                        $offer->addChild("description", $this->clearStr($product_row->description_ru));
                        $offer->addChild("vendor", $this->clearStr(@$brands[$product_row->brand_id]));

                        $addedDate = $offer->addChild("param", date('Y-m-d'));
                        $addedDate->addAttribute('name', 'addedDate');

                        $name_RO = $offer->addChild("param", $product_row->name_ro);
                        $name_RO->addAttribute('name', 'Name RO');

                        $url_RO = $offer->addChild("param", base_url() . 'ro/product/' . $product_row->uri);
                        $url_RO->addAttribute('name', 'URL RO');

                        $ids[] = $product_row->id;
                    }
                }
            }
        }

        $dom = dom_import_simplexml($xml)->ownerDocument;
        $dom->formatOutput = true;

        $dom->save(str_replace(__FILE__,'retail_rocket.xml',__FILE__));

        echo "ok <br>";

        die();
    }

    private function clearStr($str): string
    {
        $tr = [
            '"' => '&quot;',
            '&' => '&amp;',
            '>' => '&gt;',
            '<' => '&lt;',
            "'" => '&apos;',
        ];

        return strtr($str, $tr);
    }
}
