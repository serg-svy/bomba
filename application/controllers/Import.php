<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Import extends CI_Controller
{
    public function articol_color() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $json = file_get_contents('php://input');
            $post = json_decode($json, true);

            $ids = array_unique(array_column($post, 'product_id'));

            $this->db->where_in("id", $ids)->update("product", ['first_color'=>'']);

            $this->_save_data('articol_color', $post, $ids);

            echo "Au fost incarcate ".count($post)." Articol Color";
        } else {
            echo "Articol Color nu vine POST";
        }
    }

    public function attribute() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $json = file_get_contents('php://input');
            $post = json_decode($json, true);

            $this->_save_data('attribute', $post, false);

            echo "Au fost incarcate ".count($post)." Attribute";
        } else {
            echo "Attribute nu vine POST";
        }
    }

    public function attribute_group() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $json = file_get_contents('php://input');
            $post = json_decode($json, true);

            $this->_save_data('attribute_group', $post, false);

            echo "Au fost incarcate ".count($post)." Attribute Group";
        } else {
            echo "Attribute Group nu vine POST";
        }
    }

    public function attribute_group_attribute() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $json = file_get_contents('php://input');
            $post = json_decode($json, true);

            $this->_save_data('attribute_group_attribute', $post, false);

            echo "Au fost incarcate ".count($post)." Attribute Group Attribute";
        } else {
            echo "Attribute Group Attribute nu vine POST";
        }
    }

    public function attribute_set() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $json = file_get_contents('php://input');
            $post = json_decode($json, true);

            $this->_save_data('attribute_set', $post, false);

            echo "Au fost incarcate ".count($post)." Attribute Set";
        } else {
            echo "Attribute Set nu vine POST";
        }
    }

    public function attribute_set_group() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $json = file_get_contents('php://input');
            $post = json_decode($json, true);

            $this->_save_data('attribute_set_group', $post, false);

            echo "Au fost incarcate ".count($post)." Attribute Set Group";
        } else {
            echo "Attribute Set Group nu vine POST";
        }
    }

    public function brand() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $json = file_get_contents('php://input');
            $post = json_decode($json, true);

            $this->_save_data('brand', $post, false);

            echo "Au fost incarcate ".count($post)." Brand";
        } else {
            echo "Brand nu vine POST";
        }
    }

    public function category() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $json = file_get_contents('php://input');

            $this->db->insert('import_log', ['json' => $json, 'table' => 'category']);

            $post = json_decode($json, true);

            $ins_count = $upd_count = 0;

            foreach ($post as $row) {
                $check = $this->db->where('id',intval($row['id']))->get('category')->row_array();
                if (empty($check)) {
                    $this->db->insert('category',$row);
                    $ins_count++;
                } else {
                    unset($row['seo_kw_ro']);
                    unset($row['seo_kw_ru']);
                    unset($row['seo_title_ro']);
                    unset($row['seo_title_ru']);
                    $this->db->where('id',intval($row['id']))->update('category', $row);
                    $upd_count++;
                }
            }

            curl_get('category/deleteCache', true);

            echo "Au fost adaugate ".$ins_count." Categorii <br>";
            echo "Au fost modificate ".$upd_count." Categorii";
        } else {
            echo "Category nu vine POST";
        }
    }

    public function category_product() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $json = file_get_contents('php://input');

            $post = json_decode($json, true);

            $ids = array_unique(array_column($post, 'product_id'));

            $this->_save_data('category_product', $post, $ids);

            echo "Au fost incarcate ".count($post)." Category Product";
        } else {
            echo "Category Product nu vine POST";
        }
    }

    public function noapply_cashback() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $json = file_get_contents('php://input');
            $post = json_decode($json, true);

            $ids = array_map(function($item) {return $item['product_id'];}, $post);
            $ids = array_unique($ids);
            $result = array_chunk($ids, 1000);

            foreach ($result as $row) {
                $this->db->where_in("id", $row)->update('product', ['uds_noapply' => 1]);
            }
            echo "La ".count($ids)." produse nu se va aplica cashback";
        } else {
            echo "Noapply Cashback nu vine POST";
        }
    }

    public function product() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $json = file_get_contents('php://input');

            $this->db->insert('import_log', ['json' => $json, 'table' => 'product']);

            $post = json_decode($json, true);

            $ins_count = $upd_count = 0;

            require_once(realpath('application') . '/controllers/Console.php');
            $console = new console();

            foreach ($post as $row) {
                $check = $this->db->where('id',intval($row['id']))->get('product')->row_array();
                if (empty($check)) {
                    $this->db->insert('product',$row);
                    $ins_count++;
                } else {
                    $this->db->where('id',intval($row['id']))->update('product', $row);
                    $upd_count++;
                }
                generate_new_name($row['id']);

                //$console->elasticPopulateOne($row['id']);
                //dump($row['id'], true);
            }

            echo "Au fost adaugate ".$ins_count." Produse <br>";
            echo "Au fost modificate ".$upd_count." Produse";
        } else {
            echo "Product nu vine POST";
        }
    }

    public function product_attribute_value() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $json = file_get_contents('php://input');
            $post = json_decode($json, true);

            $ids = array_unique(array_column($post, 'product_id'));

            $this->_save_data('product_attribute_value', $post, $ids);

            echo "Au fost incarcate ".count($post)." Product Attribute Value";
        } else {
            echo "Product Attribute Value nu vine POST";
        }
    }

    public function product_cashback() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $json = file_get_contents('php://input');
            $post = json_decode($json, true);

            foreach ($post as $row) {
                $this->db->where("id", $row['product_id'])->update('product', ['uds_cashback' => $row['uds_cashback']]);
            }
            echo "La ".count($post)." produse s-a modificat cashback-ul";
        } else {
            echo "Product Cashback nu vine POST";
        }
    }

    public function product_price() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $json = file_get_contents('php://input');

            $this->db->insert('import_log', ['json' => $json, 'table' => 'product_price']);

            $post = json_decode($json, true);

            $ids = array_unique(array_column($post, 'product_id'));

            $this->_save_data('product_price', $post, $ids, false);

            echo "Au fost incarcate ".count($post)." Product Price";
        } else {
            echo "Product Price nu vine POST";
        }
    }

    public function product_stock() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $json = file_get_contents('php://input');

            $this->db->insert('import_log', ['json' => $json, 'table' => 'product_stock']);

            $post = json_decode($json, true);

            $ids = array_unique(array_column($post, 'product_id'));

            $this->_save_data('product_stock', $post, $ids);

            echo "Au fost incarcate ".count($post)." Product Stock";
        } else {
            echo "Product Stock nu vine POST";
        }
    }

    public function product_badge() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $json = file_get_contents('php://input');
            $post = json_decode($json, true);

            $ids = array_unique(array_column($post, 'product_id'));

            $this->_save_data('product_badge', $post, $ids);

            echo "Au fost incarcate ".count($post)." Product Badge";
        } else {
            echo "Product Badge nu vine POST";
        }
    }

    public function product_related() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $json = file_get_contents('php://input');
            $post = json_decode($json, true);

            $ids = array_unique(array_column($post, 'product_id'));

            $this->_save_data('product_related', $post, $ids);

            echo "Au fost incarcate ".count($post)." Product Related";
        } else {
            echo "Product Related nu vine POST";
        }
    }

    public function product_label() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $json = file_get_contents('php://input');
            $post = json_decode($json, true);

            $ids = array_unique(array_column($post, 'product_id'));

            $this->_save_data('product_label', $post, $ids);

            echo "Au fost incarcate ".count($post)." Product Label";
        } else {
            echo "Product Related nu vine POST";
        }
    }

    public function exclude_product() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $json = file_get_contents('php://input');
            $post = json_decode($json, true);

            $ids = array_unique(array_column($post, 'product_id'));

            $this->db->where_in("id", $ids)->delete('product');
            $this->db->where_in("product_id", $ids)->delete('product_price');
            $this->db->where_in("product_id", $ids)->delete('product_stock');
            $this->db->where_in("product_id", $ids)->delete('product_image');
            $this->db->where_in("product_id", $ids)->delete('product_feedback');
            $this->db->where_in("product_id", $ids)->delete('product_related');
            $this->db->where_in("product_id", $ids)->delete('product_subscribe');
            $this->db->where_in("product_id", $ids)->delete('category_product');
            $this->db->where_in("product_id", $ids)->delete('product_attribute_value');
            $this->db->where_in("product_id", $ids)->delete('product_attribute_value_cached');

            echo "Au fost sterse ".count($post)." Produse";
        } else {
            echo "Exclude Product nu vine POST";
        }
    }

    public function promotion() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            echo "<h1>какой результат вы хотите получить? Я не знаю, что это за данные, зачем они мне на сайте</h1>";
        } else {
            echo "Product Related nu vine POST";
        }
    }

    public function _save_data($table, $data, $ids, $truncate = false): bool
    {
        if (empty($table) || empty($data)) return false;
        if($ids) {
            $this->db->where_in("product_id", $ids)->delete($table);
            $data = array_chunk($data, 1000);

            foreach ($data as $row) {
                $this->db->insert_batch($table, $row);
            }
        } elseif ($truncate) {
            $this->db->where_in("partner_id", [1,13])->delete($table);
            $data = array_chunk($data, 1000);

            foreach ($data as $row) {
                $this->db->insert_batch($table, $row);
            }
        } else {
            foreach ($data as $row) {
                $this->db->replace($table, $row);
            }
        }

        return true;
    }
}
