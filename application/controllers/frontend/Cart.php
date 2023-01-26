<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cart extends FrontEndController
{
    private $page_id;

    public function __construct() {
        parent::__construct();
        $this->page_id = 16;
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

        $cartProducts = [];
        $_SESSION['cashback'] = 0;
        foreach ($_SESSION['cart'] as $key => $item) {
            $pizza = explode('~', ilabCrypt($key, false));
            $id = $pizza[0];

            $cartProducts[$key] = curl_post('product/findOne', ['lang' => $this->lclang, 'id' => $id], false);
            $cartProducts[$key]->cart_qty = $item['qty'];
            $cartProducts[$key]->cart_color = $pizza[1];
            $cartProducts[$key]->cart_size = $pizza[2];

            $_SESSION['cashback'] += $cartProducts[$key]->uds_cashback;
        }

        $new_products = curl_post('product/findByType', ['lang' => $this->lclang, 'type' => 'is_new'], false);

        $this->data['new_products'] = $new_products;
        $this->data['cartProducts'] = $cartProducts;
        $this->data['page'] = $page;
        $this->data['next'] = ($_SESSION['cashback'] != 0 or $this->data['cart_uds'] != 0) ? 'uds' : 'checkout';
        $this->data['inner_view'] = 'pages/cart/index';

        $this->_render();
    }

    public function uds() {
        if(empty($_SESSION['cart'])) redirect("/".$this->lclang."/".$this->data['menu']['all'][16]->uri."/");

        $page = $this->menu_model->get_page_data_by_id($this->lclang, $this->page_id);
        if (empty($page)) throw_on_404();

        $this->breadcrumbs[] = $this->_generate_bc_data($page->title, $page->uri);

        foreach(language(true) as $lang){
            $array = $lang.'_urls';
            $this->{$array}[] = $page->uri;
        }

        foreach(language(true) as $lang){
            $array = $lang.'_urls';
            $this->{$array}[] = 'uds';
        }

        $this->loadOGImgData($page);
        $this->_init_seo_data($page);

        $this->data['page'] = $page;
        $this->data['next'] = 'checkout';
        $this->data['inner_view'] = 'pages/cart/uds';

        $this->_render();
    }

    public function checkout() {
        if(empty($_SESSION['cart'])) redirect("/".$this->lclang."/".$this->data['menu']['all'][16]->uri."/");

        $page = $this->menu_model->get_page_data_by_id($this->lclang, $this->page_id);
        if (empty($page)) throw_on_404();

        $this->breadcrumbs[] = $this->_generate_bc_data($page->title, $page->uri);

        foreach(language(true) as $lang){
            $array = $lang.'_urls';
            $this->{$array}[] = $page->uri;
        }

        foreach(language(true) as $lang){
            $array = $lang.'_urls';
            $this->{$array}[] = 'checkout';
        }

        $this->loadOGImgData($page);
        $this->_init_seo_data($page);

        $this->data['next'] = 'create';
        $this->data['page'] = $page;
        $this->data['inner_view'] = 'pages/cart/checkout';

        $this->_render();
    }

    public function create() {
        if (empty($_SESSION['cart'])) redirect("/" . $this->lclang . "/" . $this->data['menu']['all'][16]->uri . "/");

        $page = $this->menu_model->get_page_data_by_id($this->lclang, $this->page_id);
        if (empty($page)) throw_on_404();

        $this->breadcrumbs[] = $this->_generate_bc_data($page->title, $page->uri);

        foreach(language(true) as $lang){
            $array = $lang.'_urls';
            $this->{$array}[] = $page->uri;
        }

        $this->loadOGImgData($page);
        $this->_init_seo_data($page);

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $order_total = 0;
            foreach ($_SESSION['cart'] as $key => $item) {

                if ($item['uds'] and !empty($_SESSION['uds'])) {
                    $reduction = curl_post('uds/reduction', [
                        'cart_total' => $this->data['cart_uds'],
                        'product_total' => $item['price'] * $item['qty'],
                        'reduction_total' => $_SESSION['uds']['points']
                    ]);
                } else {
                    $reduction = 0;
                }
                $order_total += ($item['price'] * $item['qty']) - $reduction;
            }

            if ($_SESSION['order']['delivery_type_id'] == 4) {
                $this->load->model('stores_model');
                $store = $this->stores_model->get_by_id($this->lclang, $_SESSION['order']['store_id']);
                $address = $store->title . ', ' . $store->address;
                $delivery_amount = 0;
            } else {
                $address = LOCALITY . ' ' . $_SESSION['order']['city_name'];
                $address .= ', ' . STREET . ' ' . $_SESSION['order']['street'];
                $address .= ', ' . HOME_NUMBER . ' ' . $_SESSION['order']['home_number'];
                $address .= ', ' . APARTMENT_OFFICE . ' ' . $_SESSION['order']['apartment'];
                $address .= ', ' . ENTRANCE . ' ' . $_SESSION['order']['entrance'];
                $address .= ', ' . FLOOR . ' ' . $_SESSION['order']['floor'];
                $delivery_amount = curl_post('delivery/amount', [
                    'total' => $order_total,
                    'city_id' => $_SESSION['order']['city_id'],
                    'key' => $_SESSION['order']['delivery_key']
                ], true);
            }

            $message = '';

            $uds_response = '';
            if (isset($_SESSION['uds']['points']) && !empty($_SESSION['uds']['points'])) {
                $uds_response = curl_post('uds/operations', ['code' => $_SESSION['uds']['code'], 'total' => $this->data['cart_total'], 'points' => $_SESSION['uds']['points']], true);
                $message .= 'Клиент использовал '.$_SESSION['uds']['points'].' бонусных баллов'.PHP_EOL;
            }

            if (!empty($_SESSION['order']['message'])) {
                $message .= '---------------------------------'.PHP_EOL;
                $message .= 'Комментарии от клиента: '.PHP_EOL;
                $message .= $_SESSION['order']['message'].PHP_EOL;
            }

            $order = [
                'total' => $order_total,
                'delivery_amount' => $delivery_amount,
                'delivery_date' => $_SESSION['order']['delivery_date'],
                'delivery_time' => @$_SESSION['order']['delivery_time'],
                'delivery_type_id' => $_SESSION['order']['delivery_type_id'],
                'store_id' => $_SESSION['order']['store_id'],
                'payment_type_id' => $_SESSION['order']['payment_type_id'],
                'name' => $_SESSION['order']['name'],
                'phone' => $_SESSION['order']['phone'],
                'email' => $_SESSION['order']['email'],
                'message' => $message,
                'is_gift' => $_SESSION['order']['is_gift'],
                'receive_gift_name' => $_SESSION['order']['receive_gift_name'],
                'receive_gift_phone' => $_SESSION['order']['receive_gift_phone'],
                'address' => $address,
                'fiscal_number' => '',
                'terminal_id' => '',
                'pay_flag' => ($_SESSION['order']['payment_type_id'] == 1) ? 1 : 0,
                'order_date' => date('Y-m-d H:i:s'),
                'uds_id' => (isset($_SESSION['uds']['id'])) ? $_SESSION['uds']['id'] : "",
                'uds_uid' => (isset($_SESSION['uds']['uid'])) ? $_SESSION['uds']['uid'] : "",
                'uds_response' => json_encode($uds_response),
                'status_changed_at' => date('Y-m-d H:i:s'),
                'client_ip' => getRealIpAddr(),
            ];

            $order_id = $this->db->insert('orders', $order) ? $this->db->insert_id() : false;

            if ($order_id) {
                $generated_id = rand(1, 9) . $order_id . rand(1, 9);
                $this->db->where('id', $order_id)->update('orders', ['generated_id' => $generated_id]);

                foreach ($_SESSION['cart'] as $key => $item) {

                    $pizza = explode('~', ilabCrypt($key, false));
                    $product = curl_post('product/findOne', ['lang' => $this->lclang, 'id' => $pizza[0]], true);

                    if ($item['uds'] and !empty($_SESSION['uds'])) {
                        $reduction = curl_post('uds/reduction', [
                            'cart_total' => $this->data['cart_uds'],
                            'product_total' => $item['price'] * $item['qty'],
                            'reduction_total' => $_SESSION['uds']['points']
                        ]);
                    } else {
                        $reduction = 0;
                    }

                    $item_insert = [];
                    $item_insert['order_id'] = $order_id;
                    $item_insert['product_id'] = $product['id'];
                    $item_insert['price'] = $product['discounted_price'];
                    $item_insert['reduction'] = $reduction;
                    $item_insert['quantity'] = $item['qty'];
                    $item_insert['photo_color'] = $pizza[1];
                    $item_insert['color'] = $pizza[1];
                    $item_insert['size'] = $pizza[2];
                    $item_insert['partner_id'] = $product['partner_id'];

                    $this->db->insert('order_items', $item_insert);
                }

                $_SESSION['cart'] = [];
                $_SESSION['order'] = [];
                $_SESSION['uds'] = [];

                curl_post('una/send', ['order_id' => $order_id], true);
                $this->_send_email($order_id);

                if ($order['payment_type_id'] == 3) {
                    redirect("/$this->lclang/cart/payment/$generated_id/");
                    exit();
                }

                redirect("/$this->lclang/cart/result/$generated_id/");
            } else {
                redirect("/");
            }
        }
    }

    public function payment() {
        $page = $this->menu_model->get_page_data_by_id($this->lclang, $this->page_id);
        if (empty($page)) throw_on_404();

        $this->breadcrumbs[] = $this->_generate_bc_data($page->title, $page->uri);

        foreach(language(true) as $lang){
            $array = $lang.'_urls';
            $this->{$array}[] = $page->uri;
        }

        foreach(language(true) as $lang){
            $array = $lang.'_urls';
            $this->{$array}[] = 'payment';
        }

        $this->loadOGImgData($page);
        $this->_init_seo_data($page);

        $generated_id = uri(4);
        $order = $this->db->where("generated_id", $generated_id)->get("orders")->row_array();

        $this->data['page'] = $page;
        $this->data['order'] = $order;
        $this->data['terminal'] = 92409023;
        $this->data['merchant'] = '02ECM024';
        $this->data['num'] = 900000000+intval($generated_id);
        $this->data['inner_view'] = 'pages/cart/payment';

        $this->_render();
    }

    public function result() {
        $page = $this->menu_model->get_page_data_by_id($this->lclang, $this->page_id);
        if (empty($page)) throw_on_404();

        $this->breadcrumbs[] = $this->_generate_bc_data($page->title, $page->uri);

        foreach(language(true) as $lang){
            $array = $lang.'_urls';
            $this->{$array}[] = $page->uri;
        }

        foreach(language(true) as $lang){
            $array = $lang.'_urls';
            $this->{$array}[] = 'result';
        }

        $generated_id = uri(4);

        foreach(language(true) as $lang){
            $array = $lang.'_urls';
            $this->{$array}[] = $generated_id;
        }

        $this->loadOGImgData($page);
        $this->_init_seo_data($page);

        $order = $this->db->where("generated_id", $generated_id)->get("orders")->row();
        if (empty($order)) throw_on_404();
        $order->products = $this->db->where("order_id", $order->id)->get("order_items")->result();

        $this->load->model('payment_type_model');
        $payment_type = $this->payment_type_model->get_payment_by_id($this->lclang, $order->payment_type_id);

        $this->data['page'] = $page;
        $this->data['order'] = $order;
        $this->data['payment_type'] = $payment_type;
        $this->data['inner_view'] = 'pages/cart/result';

        $this->_render();
    }

    public function add() {
        $post = $this->input->post(null, true);

        $row = $this->db->select("product_id as id")->where("articol", $post['articol'])
            ->where("color_ro", ($post['color'] == 'NO_COLOR') ? null : $post['color'])
            ->where("size", ($post['size'] == 'NO_SIZE') ? null : $post['size'] )
            ->where("qty >", 0)
            ->get("articol_color")
            ->row();

        if (empty($row)) {
            $id = $this->db->select("id as id")->where("articol", $post['articol'])->get("product")->row()->id;
        } else {
            $id = $row->id;
        }

        $idCrypted = ilabCrypt($id . '~'. $post['color'] .'~'. $post['size'] , true);

        if (empty($post['qty'])) {
            $qty = 1;
        } else {
            $qty = intval($_POST['qty']);
        }

        if (order_double($id)) {
            $qty = $qty * 2;
        }

        $product = curl_post('product/findOne', ['lang' => $this->lclang, 'id' => $id], false);

        $quantity_in_cart = (!empty($_SESSION['cart'][$idCrypted])) ? $_SESSION['cart'][$idCrypted]['qty'] : 0;

        $new_quantity = ($post['change']=== "true") ? $qty : $qty + $quantity_in_cart;

        $message = PRODUCT_ADDED;
        $_SESSION['cart'][$idCrypted]['id'] = $id;
        $_SESSION['cart'][$idCrypted]['qty'] = $new_quantity;
        $_SESSION['cart'][$idCrypted]['price'] = $product->discounted_price;
        // aici identificam daca produsul este uds sau nu
        $_SESSION['cart'][$idCrypted]['uds'] = ($product->partner_id == 1) ? $product->discounted_price : 0;
        $status = 'success';

        $total_qty = 0;
        $total_price = 0;

        foreach ($_SESSION['cart'] as $item) {
            $total_qty += $item['qty'];
            $total_price += $item['price'] * $item['qty'];
        }

        $result = [
            'productBtn' => $this->load->view('layouts/pages/product/btn', ['key' => $idCrypted], true),
            'status' =>  $status,
            'id' => $id,
            'idCrypted' => $idCrypted,
            'itemTotal' => numberFormat($product->discounted_price * $new_quantity) . ',–',
            'qty' => $total_qty,
            'total' => numberFormat($total_price) . ',–',
            'change' => $post['change'],
            'message' => $message,
            'confirmButton' => GO_TO_CART,
            'cancelButton' => CONTINUE_SHOPPING,
            'redirect' => '/'.$this->lclang.'/'.$this->data['menu']['all'][16]->uri,
        ];

        echo json_encode($result);
    }

    public function empty() {
        $_SESSION['cart'] = [];

        redirect("/".$this->lclang."/".$this->data['menu']['all'][16]->uri."/");
    }

    public function del() {
        if(isset($_SESSION['cart'][$_POST['key']])) unset($_SESSION['cart'][$_POST['key']]);
    }

    function _send_email($order_id): bool
    {
        $order_id = (int)$order_id;
        $order = $this->db->select(
            'uo.id as id,
            uo.generated_id,
            order_date,
            total,
            delivery_amount,
            email,
            name,
            phone,
            address,
            payment_type_id,
            delivery_type_id,
            status,
            uds_id,
            uds_accepted,
            uds_uid,
            uds_response,
            dt.name_'.  $this->lclang . ' as delivery_name,
            pt.name_'.  $this->lclang . ' as payment_name'
        )
            ->join('delivery_type as dt', 'dt.id = uo.delivery_type_id', 'left')
            ->join('payment_type as pt', 'pt.id = uo.payment_type_id', 'left')
            ->where('uo.id', $order_id)->get('orders as uo')->row();

        if (empty($order)) return false;

        $uds = null;
        if (!empty($order->uds_uid)) {
            $response = json_decode($order->uds_response, true);
            if (isset($response['state']) && $response['state'] == 'NORMAL') {
                if (isset($response['points']) && $response['points'] > 0) {
                    $uds = $response['points'];
                }
            }
        }

        $products = $this->db->select('
            product.*,
            order_items.price,
            order_items.reduction,
            order_items.quantity,
        ')
            ->from('order_items')
            ->join('product', 'order_items.product_id = product.id')
            ->where('order_items.order_id', $order_id)
            ->group_by('product.id')
            ->get()->result_array();
        $header_option = $this->db->where('id', 1)->get('header_options')->row();

        $url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . '://' . $_SERVER['HTTP_HOST'];

        $order_table = '';
        $total = 0;
        foreach ($products as $product) {
            $total = $total + ($product['price'] * $product['quantity']);
            $product['lang'] = $this->lclang;
            $product['url'] = $url;
            $order_table .= $this->load->view('layouts/email/product', $product, true);
        }

        $message = $this->load->view('layouts/email/index', array(
            'logo' => @$header_option->data,
            'url' => $url,
            'order_table' => $order_table,
            'total' => $total,
            'id' => $order->generated_id,
            'uds' => $uds,
            'delivery_cost' => $order->delivery_amount,
            'delivery_name' => $order->delivery_name,
            'payment_name' => $order->payment_name,
            'name' => $order->name,
            'address' => $order->address,
            'date' => $order->order_date,
        ), true);

        return send_notification($order->email, $_SERVER['HTTP_HOST'], $message, false);
    }

    function paymentdata()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $ttf = json_encode($_POST);
            $this->db->insert('payment_log', array('request' => 'First query - ' . $ttf));
            if (@$_POST['RC'] == '00' && intval(@$_POST['TRTYPE']) == 0 && !empty($_POST['ORDER'])) {
                require_once realpath('public/payment') . '/' . 'Transaction.php';

                $ts = date('YmdHis');
                $nonce = Transaction::set_nonce();
                $req = array(
                    'TRTYPE' => 21,
                    'TERMINAL' => $_POST['TERMINAL'],
                    'ORDER' => $_POST['ORDER'],
                    'CURRENCY' => $_POST['CURRENCY'],
                    'AMOUNT' => $_POST['AMOUNT'],
                    'TIMESTAMP' => $ts,
                    'NONCE' => $nonce,
                    'RRN' => $_POST['RRN'],
                    'INT_REF' => $_POST['INT_REF']
                );

                $order = intval($_POST['ORDER']) - 900000000;

                $rsaPrivKey = Transaction::_get_key(realpath('public/payment') . '/' . "ssl/private/privkey_prod.pem");
                $rsaPubKey = Transaction::get_key(realpath('public/payment') . '/' . "ssl/cert_prod.pem");

                $rsaKeyLength = Transaction::get_key_length($rsaPrivKey);

                $hashedData = Transaction::_create_hased_data($rsaKeyLength, $req);
                $macsource = Transaction::_generate_mac_source($req);
                $p_sign = Transaction::_encrypt_p_sign($rsaPrivKey, $hashedData);

                $fieldstring = 'ORDER=' . $_POST['ORDER'] . '&TERMINAL=' . $_POST['TERMINAL'] . '&RRN=' . $_POST['RRN'] . '&INT_REF=' . $_POST['INT_REF'] . '&CURRENCY=' . $_POST['CURRENCY'] . '&AMOUNT=' . $_POST['AMOUNT'] . '&TRTYPE=21&TIMESTAMP=' . $ts . '&NONCE=' . $nonce . '&P_SIGN=' . $p_sign;

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, MICB_URI);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $fieldstring);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                $res = curl_exec($ch);
                curl_close($ch);
            }

            $ttf = json_encode($_POST);
            $this->db->insert('payment_log', array('request' => $ttf));

            if (@$_POST['RC'] !== '00' && intval(@$_POST['TRTYPE']) == 0 && !empty($_POST['ORDER'])) {
                $bank_order = intval($_POST['ORDER']);
                $this->db->insert('payment_log', ['request' => "При оплате заказа номер $bank_order произошла ошибка"]);
            }

            if (@$_POST['RC'] == '00' && intval(@$_POST['TRTYPE']) == 21) {
                $bank_order = intval($_POST['ORDER']);
                $order = intval($_POST['ORDER']) - 900000000;
                $this->db->insert('payment_log', ['request' => "Заказ номер $bank_order успешно оплачен"]);
                $this->db->where('id', $order)->update('orders', array('transaction_id' => $_POST['INT_REF'], 'rrn_id' => $_POST['RRN'], 'status' => 4, 'pay_flag' => 1));
                $this->_send_email($order);
            }

            if (@$_POST['RC'] !== '00' && intval(@$_POST['TRTYPE']) == 21) {
                $bank_order = intval($_POST['ORDER']);
                $this->db->insert('payment_log', ['request' => "При оплате заказа номер $bank_order произошла ошибка"]);
            }

            if (@$_POST['RC'] == '00' && intval(@$_POST['TRTYPE']) == 24 && !empty($_POST['ORDER'])) {
                $bank_order = intval($_POST['ORDER']);
                $order = intval($_POST['ORDER']) - 900000000;
                $this->db->insert('payment_log', ['request' => "Заказ под номером $bank_order успешно возвращен"]);
            }

            if (@$_POST['RC'] !== '00' && intval(@$_POST['TRTYPE']) == 24 && !empty($_POST['ORDER'])) {
                $bank_order = intval($_POST['ORDER']);
                $postData = json_encode($_POST);
                $this->db->insert('payment_log', ['request' => "При возврате заказа под номеромом $bank_order произошла ошибка"]);
                $this->db->insert('payment_log', ['request' => $postData]);
            }
        } else {
            $this->db->insert('payment_log', ['request' => "Banca nu trimmite post"]);
        }
    }

    function paymentdata_json()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $json = file_get_contents('php://input');
            $post = json_decode($json, true);

            $this->db->insert('payment_log', array('request' => 'First query - ' . $json));

            if (@$post['RC'] == '00' && intval(@$post['TRTYPE']) == 0 && !empty($post['ORDER'])) {
                require_once realpath('public/payment') . '/' . 'Transaction.php';

                $ts = date('YmdHis');
                $nonce = Transaction::set_nonce();
                $req = array(
                    'TRTYPE' => 21,
                    'TERMINAL' => $post['TERMINAL'],
                    'ORDER' => $post['ORDER'],
                    'CURRENCY' => $post['CURRENCY'],
                    'AMOUNT' => $post['AMOUNT'],
                    'TIMESTAMP' => $ts,
                    'NONCE' => $nonce,
                    'RRN' => $post['RRN'],
                    'INT_REF' => $post['INT_REF']
                );

                $order = intval($post['ORDER']) - 900000000;

                $rsaPrivKey = Transaction::_get_key(realpath('public/payment') . '/' . "ssl/private/privkey_prod.pem");
                $rsaPubKey = Transaction::get_key(realpath('public/payment') . '/' . "ssl/cert_prod.pem");

                $rsaKeyLength = Transaction::get_key_length($rsaPrivKey);

                $hashedData = Transaction::_create_hased_data($rsaKeyLength, $req);
                $macsource = Transaction::_generate_mac_source($req);
                $p_sign = Transaction::_encrypt_p_sign($rsaPrivKey, $hashedData);

                $fieldstring = 'ORDER=' . $post['ORDER'] . '&TERMINAL=' . $post['TERMINAL'] . '&RRN=' . $post['RRN'] . '&INT_REF=' . $post['INT_REF'] . '&CURRENCY=' . $post['CURRENCY'] . '&AMOUNT=' . $post['AMOUNT'] . '&TRTYPE=21&TIMESTAMP=' . $ts . '&NONCE=' . $nonce . '&P_SIGN=' . $p_sign;

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, MICB_URI);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $fieldstring);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                $res = curl_exec($ch);
                curl_close($ch);
            }

            if (@$post['RC'] !== '00' && intval(@$post['TRTYPE']) == 0 && !empty($post['ORDER'])) {
                $bank_order = intval($post['ORDER']);
                $this->db->insert('payment_log', ['request' => "При оплате заказа номер $bank_order произошла ошибка"]);
            }

            if (@$post['RC'] == '00' && intval(@$post['TRTYPE']) == 21) {
                $bank_order = intval($post['ORDER']);
                $order = intval($post['ORDER']) - 900000000;
                $this->db->insert('payment_log', ['request' => "Заказ номер $bank_order успешно оплачен"]);
                $this->db->where('id', $order)->update('orders', array('transaction_id' => $post['INT_REF'], 'rrn_id' => $post['RRN'], 'status' => 4, 'pay_flag' => 1));
                $this->_send_email($order);
            }

            if (@$post['RC'] !== '00' && intval(@$post['TRTYPE']) == 21) {
                $bank_order = intval($post['ORDER']);
                $this->db->insert('payment_log', ['request' => "При оплате заказа номер $bank_order произошла ошибка"]);
            }
        } else {
            $this->db->insert('payment_log', ['request' => "Banca nu trimmite post"]);
        }
    }
}
