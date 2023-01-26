<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ajax extends FrontEndController
{
    function subscribe() {
        $user = 'dana.api@ab.md';
        $password = 'Ff6MwEwHMt';
        $subscribe_contact_url = 'https://esputnik.com.ua/api/v1/contact/subscribe';

        $email = $this->input->post("email", true);

        $json_contact_value = new stdClass();
        $contact = new stdClass();
        $contact->channels = array(array('type'=>'email', 'value' => $email));
        $contact->contactKey = 'email';
        $json_contact_value->contact = $contact;
        $json_contact_value->groups = array('FooterSubscription');
        $json_contact_value->formType = 'FooterSubscription';
        $this->send_request($subscribe_contact_url, $json_contact_value, $user, $password);

        $response['response'] = true;
        $response['message'] = SUCCESSFULL_SUBSCRIBE;
        echo json_encode($response);
    }

    function get_store_info() {
        $id = $this->input->post("id", true);
        $this->load->model("stores_model");
        $store = $store = $this->stores_model->get_by_id($this->lclang, $id);

        $html = $this->load->view("layouts/pages/store/info", ['store' => $store], true);
        $response['html'] = $html;
        echo json_encode($response);
    }

    function get_store_info_mobile() {
        $id = $this->input->post("id", true);
        $this->load->model("stores_model");
        $store = $store = $this->stores_model->get_by_id($this->lclang, $id);

        $html = $this->load->view("layouts/pages/store/info_mobile", ['store' => $store], true);
        $response['html'] = $html;
        echo json_encode($response);
    }

    function get_city() {
        $value = $this->input->post("value", true);
        $this->load->model("cities_model");
        $cities = $this->cities_model->autocomplete($this->lclang, $value);

        $this->load->model("regions_model");
        $regions = $this->regions_model->get($this->clang);

        $html = $this->load->view("layouts/pages/city_list", ['cities' => $cities, 'match' => $value, 'regions' => $regions], true);
        $response['value'] = $value;
        $response['html'] = $html;
        echo json_encode($response);
    }

    public function set_city() {
        $value = $this->input->post("value", true);
        $_SESSION['city_id'] = $value;

        $city = $this->db->where("id", $value)->get("city")->row_array();
        $region = $this->db->where("id", $city['region_id'])->get("regions")->row_array();

        $this->load->model("stores_model");
        $stores = $this->stores_model->get_pickup_stores($this->lclang, ($city['is_sector']) ? 1 : $value);

        $current = date('N');
        $result = $this->db->get("weeks")->result();
        $weeks = [];
        foreach($result as $row) {
            $weeks[$row->id]['name'] = $row->{'name_'.$this->lclang};
        }

        if(!empty($city['slots'])) {
            $slots = json_decode($city['slots'], true);
        } else {
            $slots = json_decode($region['slots'], true);
        }

        $dates = ($city['is_shown_slots']) ? [] : curl_get('delivery/dates', true);

        foreach ($stores as &$store) {
            $store->workhours = trim(preg_replace('/\s\s+/', ' ', $store->workhours));
            $store->parking = trim(preg_replace('/\s\s+/', ' ', $store->parking));
            $store->troleibus = trim(preg_replace('/\s\s+/', ' ', $store->troleibus));
        }

        $data  = [
            'city'=>$city,
            'region'=>$region,
            'stores'=>$stores,
            'weeks' => $weeks,
            'slots' => $slots,
            'dates' => $dates,
            'cart_total' => $this->data['cart_total'] - $this->data['cart_points'],
            'delivery_date' => @$_SESSION['order']['delivery_date'],
            'delivery_time' => @$_SESSION['order']['delivery_time'],
        ];

        $response['active'] = (isset($_SESSION['order']['delivery_type_id']) and $_SESSION['order']['delivery_type_id']==4) ? 1 : 0;

        $deliveryCourier = getCourierDelivery($this->lclang, $_SESSION['city_id']);

        $response['city'] = $this->data['cities'][$value]->title;
        $response['text_courier'] = str_replace(['{nearest_date_delivery}', '{nearest_date}'], $deliveryCourier['day'], $this->data['cities'][$value]->text_courier);
        $response['text_pickup'] = str_replace(['{nearest_date_delivery}', '{nearest_date}'], $deliveryCourier['day'], $this->data['cities'][$value]->text_pickup);
        $response['view'] = $this->load->view("layouts/pages/cart/delivery", $data, true);
        echo json_encode($response);
    }

    public function set_city_automatically() {
        $geoplugin = unserialize( file_get_contents('http://www.geoplugin.net/php.gp?ip=' . $_SERVER['REMOTE_ADDR']) );
        $pizza = explode(" ", $geoplugin['geoplugin_regionName']);

        $this->load->model("cities_model");
        $cities = $this->cities_model->autocomplete($this->lclang,  $pizza[0]);

        if(!empty($cities)) {
            $response['response'] = true;
            $response['city'] = $cities[0]->title;
            $_SESSION['city_id'] = $cities[0]->id;
        } else {
            $response['response'] = false;
            $response['message'] = NOTHING_FOUND;
        }
        echo json_encode($response);
    }

    public function send_request($url, $json_value, $user, $password) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($json_value));
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json', 'Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_USERPWD, $user.':'.$password);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);

        curl_close($ch);
    }

    function get_disponible_sizes() {

        $articol = $this->input->post('articol');
        $color = $this->input->post('color');
        $size = $this->input->post('size');

        $articol_color = $this->db->select("
                articol as articol,
                product_id as product_id,
                color_ro as color, 
                size as size,
                qty as qty
            ")
            ->where("articol", $articol)
            ->where("color_ro", $color)
            ->where("qty >", 0)
            ->get("articol_color")->result();

        $sizes = array_map(function ($item){return $item->size;}, $articol_color);
        $sizes = array_filter(array_unique($sizes));

        if($color and $size) {
            $product = $this->db->where("articol", $articol)->where("color_ro", $color)->where("size", $size)->get("articol_color")->row();
            $idCrypted = ilabCrypt($product->product_id . '~' . $color . '~' . $size, true);
        } else {
            $idCrypted = 'nix';
        }
        $productBtn = $this->load->view('layouts/pages/product/btn', ['key' => $idCrypted], true);

        echo json_encode(array('ids' => $sizes, 'productBtn' => $productBtn));

    }

    function get_disponible_colors() {

        $articol = $this->input->post('articol');
        $size = $this->input->post('size');
        $color = $this->input->post('color');

        $articol_color = $this->db->select("
                articol as articol,
                product_id as product_id,
                color_ro as color, 
                size as size,
                qty as qty
            ")
            ->where("articol", $articol)
            ->where("size", $size)
            ->where("qty >", 0)
            ->get("articol_color")->result();

        $colors = array_map(function ($item){return $item->color;}, $articol_color);
        $colors = array_filter(array_unique($colors));

        if($color and $size) {
            $product = $this->db->where("articol", $articol)->where("color_ro", $color)->where("size", $size)->get("articol_color")->row();
            $idCrypted = ilabCrypt($product->product_id . '~' . $color . '~' . $size, true);
        } else {
            $idCrypted = 'nix';
        }
        $productBtn = $this->load->view('layouts/pages/product/btn', ['key' => $idCrypted], true);

        echo json_encode(array('ids' => $colors, 'productBtn' => $productBtn));
    }

    function check_color_and_size() {
        $post = $this->input->post(null, true);

        if (!empty($post['id']) && !empty($post['articol']) ) {

            $query = $this->db
                ->where([
                    'product_id' => $post['id'],
                    'articol' => $post['articol'],
                    'qty !=' => 0
                ])
                ->limit(1)
                ->get('articol_color')
                ->result_array();

            switch (count($query)) {
                case 0:
                    echo json_encode(['status' => true, 'color' => 'NO_COLOR', 'size' => 'NO_SIZE']);
                    break;
                case 1:
                    echo json_encode([
                        'status' => true,
                        'color' => (!empty($query[0]['color_ro'])) ? $query[0]['color_ro'] : 'NO_COLOR',
                        'size' => (!empty($query[0]['size'])) ? $query[0]['size'] : 'NO_SIZE'
                    ]);
                    break;
            }
        } else {
            echo json_encode(['status' => false]);
        }
    }

    public function set_delivery() {
        $post = $this->input->post(null, true);

        //$_SESSION['order'] = $post['order'];

        $_SESSION['order']['city_id'] = $post['order']['city_id'];
        $_SESSION['order']['city_name'] = $post['order']['city_name'];
        $_SESSION['order']['street'] = $post['order']['street'];
        $_SESSION['order']['home_number'] = $post['order']['home_number'];
        $_SESSION['order']['apartment'] = $post['order']['apartment'];
        $_SESSION['order']['entrance'] = $post['order']['entrance'];
        $_SESSION['order']['floor'] = $post['order']['floor'];
        $_SESSION['order']['delivery_date'] = $post['order']['delivery_date'];
        $_SESSION['order']['delivery_time'] = @$post['order']['delivery_time'];
        $_SESSION['order']['delivery_type_id'] = $post['order']['delivery_type_id'];
        $_SESSION['order']['store_id'] = $post['order']['store_id'];
        $_SESSION['order']['delivery_key'] = $post['order']['delivery_key'];

        if($_SESSION['order']['delivery_type_id'] == 4) {
            $this->load->model("stores_model");
            $store = $this->stores_model->get_by_id($this->lclang, $_SESSION['order']['store_id']);
            $text = $store->title;
        } else {
            $text = LOCALITY. ' ' . $_SESSION['order']['city_name'];
            $text .= ', ' . STREET . ' ' . $_SESSION['order']['street'];
            $text .= ', ' . HOME_NUMBER . ' ' . $_SESSION['order']['home_number'];
            $text .= ', ' . APARTMENT_OFFICE . ' ' . $_SESSION['order']['apartment'];
            $text .= ', ' . ENTRANCE . ' ' . $_SESSION['order']['entrance'];
            $text .= ', ' . FLOOR . ' ' . $_SESSION['order']['floor'];
        }
        $response['text'] = $text;

        $this->load->model('payment_type_model');
        $data['payment_types'] = $this->payment_type_model->get_payment_for_site($this->lclang);
        $data['onlyOnlinePayment'] = curl_post('delivery/online', [
            'city_id' => $_SESSION['order']['city_id'],
            'delivery_type_id' => $_SESSION['order']['delivery_type_id']
        ], true);
        $response['payment'] = $this->load->view("layouts/pages/cart/payment", $data, true);
        echo json_encode($response);
    }

    public function set_payment() {
        $post = $this->input->post(null, true);

        $_SESSION['order']['payment_type_id'] = $post['order']['payment_type_id'];

        $this->load->model('payment_type_model');
        $response['text'] = $this->payment_type_model->get_payment_by_id($this->lclang, $post['order']['payment_type_id'])->title;

        $data = [
            'lclang' => $this->lclang,
            'menu' => $this->data['menu']
        ];
        $response['payment'] = $this->load->view("layouts/pages/cart/contact", $data, true);
        echo json_encode($response);
    }

    public function set_contact() {
        $post = $this->input->post(null, true);

        $_SESSION['order']['name'] = $post['order']['name'];
        $_SESSION['order']['phone'] = $post['order']['phone'];
        $_SESSION['order']['email'] = $post['order']['email'];
        $_SESSION['order']['message'] = $post['order']['message'];
        $_SESSION['order']['is_gift'] = isset($post['order']['is_gift']) ? 1 : 0;
        $_SESSION['order']['receive_gift_name'] = $post['order']['receive_gift_name'];
        $_SESSION['order']['receive_gift_phone'] = $post['order']['receive_gift_phone'];

        $response['text'] = '<div class="b_info3 v1">
								<div class="bi3_item">
									<div class="bi3_title">'.NAME.'</div>
									<p>'.$post['order']['name'].'</p>
								</div>
								<div class="bi3_item">
									<div class="bi3_title">'.PHONE.'</div>
									<p>'.$post['order']['phone'].'</p>
								</div>
								<div class="bi3_item">
									<div class="bi3_title">E-mail</div>
									<p>'.$post['order']['email'].'</p>
								</div>
								<div class="bi3_item">
									<div class="bi3_title">'.COMMENT_TO_ORDER.'</div>
									<p>'.$post['order']['message'].'</p>
								</div>
							</div>';

        echo json_encode($response);
    }

    public function search() {
        $find_categories = curl_post('category/search', [ 'lang' => $this->lclang, 'query' => $this->query, "limit" => 4], true);
        $products = curl_post('product/search', [ 'lang' => $this->lclang, 'query' => $this->query, "limit" => 2, "full" => true], true);

        $previous_search = [];
        if(isset($_SESSION['previous_search']) and !empty($_SESSION['previous_search'])) {
            $index = array_search_partial($_SESSION['previous_search'], $this->query);
            if($index) $previous_search[] = $_SESSION['previous_search'][$index];
        }
        $popular_requests = $this->db->like("query", $this->query)->limit(2)->order_by("count desc")->get("popular_requests")->result();

        $response = [
            'query' => $this->query,
            'products' => $this->load->view('layouts/pages/search/products', [
                'lclang' => $this->lclang,
                'query'=>$this->query,
                'previous_search' => $previous_search,
                'popular_requests' => $popular_requests,
                'find_categories' => $find_categories,
                'products' => $products,
                'is_mobile' => $this->data['is_mobile'],
            ], true),
            'categories' => $this->load->view('layouts/pages/search/categories', [
                'find_categories' => $find_categories,
                'lclang' => $this->lclang,
                'query'=>$this->query
            ], true)
        ];

        echo json_encode($response);
    }

    public function previous_search() {

        $previous_search = [];
        if(isset($_SESSION['previous_search']) and !empty($_SESSION['previous_search'])) {
            $previous_search = array_chunk(array_reverse($_SESSION['previous_search']), 3)[0];
        }

        $response = [
            'count' => ($this->data['is_mobile']==1) ? 1 : count($previous_search),
            'products' => $this->load->view('layouts/pages/search/products', [
                'lclang' => $this->lclang,
                'previous_search' => $previous_search,
                'popular_requests' => [],
                'find_categories' => [],
                'products' => [],
                'is_mobile' => $this->data['is_mobile'],
            ], true),
        ];

        echo json_encode($response);
    }

    public function add_to_favorite() {
        $id = $this->input->post("id", true);

        if(isset($_SESSION['favorite'][$id])) {
            unset($_SESSION['favorite'][$id]);
            $response['action'] = 'remove';
            if(!isset($_SESSION['favorite'])) $_SESSION['favorite'] = [];
        } else {
            $_SESSION['favorite'][$id] = $id;
            $response['action'] = 'add';
        }
        $response['count'] = count($_SESSION['favorite']);

        echo json_encode($response);
    }

    public function add_to_compare() {
        $id = $this->input->post("id", true);

        if(isset($_SESSION['compare'][$id])) {
            unset($_SESSION['compare'][$id]);
            $response['action'] = 'remove';
            if(!isset($_SESSION['compare'])) $_SESSION['compare'] = [];
        } else {
            $_SESSION['compare'][$id] = $id;
            $response['action'] = 'add';
        }
        $response['count'] = count($_SESSION['compare']);

        echo json_encode($response);
    }

    public function list_clean() {
        $ids = $this->input->post("ids", true);

        if(isset($ids) and is_array($ids)) {
            foreach ($ids as $id) {
                unset($_SESSION['compare'][$id]);
            }
        }

        echo "ok";
    }
}
