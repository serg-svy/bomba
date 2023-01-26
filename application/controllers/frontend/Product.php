<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Product extends FrontEndController
{
    private $page_id;
    private $page_uri;

    public function __construct() {
        parent::__construct();
        $this->page_id = 17;
        $this->page_uri = 'product';
    }

    private function _init_seo_data($page) {
        $this->data['page_title'] = (!empty($page->seo_title)) ? $page->seo_title : str_replace("{{title}}", $page->title, SEO_TITLE_TEMPLATE);
        $this->data['page_name'] = $page->title;
        $this->data['keywords_for_layout'] = (!empty($page->seo_keywords)) ? $page->seo_keywords : "";
        $this->data['description_for_layout'] = (!empty($page->seo_desc)) ? $page->seo_desc : str_replace('{{delivery}}', SEO_DELIVERY_TEMPLATE, str_replace("{{title}}", $page->title, SEO_DESC_TEMPLATE));
        $this->data['otitle'] = (!empty($page->seo_title)) ? $page->seo_title : str_replace("{{title}}", $page->title, SEO_TITLE_TEMPLATE);
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

    public function num() {
        $product = curl_post('product/findOne', ['lang' => $this->lclang, 'id' => uri(3)], false);
        if (empty($product)) throw_on_404();

        redirect("/".$this->lclang."/".PRODUCT_URI."/".$product->uri."/");

    }
    public function index() {
        $page = $this->menu_model->get_page_data_by_id($this->lclang, $this->page_id);
        if (empty($page)) throw_on_404();

        $product = curl_post('product/findOne', ['lang' => $this->lclang, 'uri' => uri(3)], false);
        if (empty($product)) throw_on_404();

        $articol_color = $this->db->select("
            product_id as product_id,
            articol as articol,
            color_$this->lclang as color,
            color_ro as photo_color, 
            size as size,
            qty as qty
        ")
            ->where("articol", $product->articol)
            ->get("articol_color")->result();

        $sizes = $colors = $retail_ids = [];
        $colorQty = 0;
        foreach ($articol_color as $item) {
            $sizes[] = $item->size;
            $colors[] = $item->photo_color;
            $colorQty += $item->qty;
            $retail_ids[] = $item->product_id;
        }
        $product->sizes = array_filter(array_unique($sizes));
        $product->colors = array_filter(array_unique($colors));

        if(count($articol_color) > 1 and $colorQty == 0) {
            throw_on_404();
        }

        $photos = [];

        $dopa = substr($product->articol, -2);
        $path = realpath('public/products/' . $dopa . '/' . $product->articol . '/'. $product->first_color);
        for ($i = 1; $i <= 10; $i++) {
            if (is_file($path . '/' . $i . '.jpg')) {
                $photos[] = $i . '.jpg';
            }
        }

        $feedbacks = $this->db->where("product_id", $product->id)->where("isShown", 1)->get("product_feedback")->result();

        $attributes = curl_post('cache/attributes_for_product', ['lang' => $this->lclang, 'attribute_set_id' => $product->attribute_set_id, 'product_id' => $product->id], true);

        $dopa_id = substr($product->id, -2);
        $dir = realpath('public') . '/instruction/' . $dopa_id . '/' . $product->id;
        $instructions = false;
        if (is_dir($dir)) {
            $files = glob($dir . '/*.pdf');
            foreach ($files as $file) {
                $pizza = explode("/", $file);
                $instructions[] = [
                    'path' => '/public/instruction/'. $dopa_id .'/'. $product->id .'/'. end($pizza),
                    'label' => (strpos(end($pizza), 'nstruction')) ? INSTRUCTION_FOR : CERTIFICATE_FOR,
                    'size' => formatSizeUnits(filesize($file)),
                ];
            }
        }

        $related_products = [];
        $related_result = $this->db->where("product_id", $product->id)->get("product_related")->result_array();
        if($related_result) {
            $related_ids = array_map(function($item){return $item['related_id'];}, $related_result);
            $related_products = curl_post('product/find', ['lang' => $this->lclang, 'ids' => $related_ids, 'limit' => 8], true);
        }

        $this->load->model('stores_model');
        $stores = $this->stores_model->get_product_stores($this->lclang, $product->id);

        $all_categories = curl_post('category/all', ['lang' => $this->lclang], true);
        $_SESSION['cat_ids'] = false;
        categories_cat_ids($all_categories, $product->category_id);

        if(is_array($_SESSION['cat_ids'])) {
            ksort($_SESSION['cat_ids']);
            foreach ($_SESSION['cat_ids'] as $key) {
                $this->breadcrumbs[] = $this->_generate_bc_data($all_categories[$key]['title'], $all_categories[$key]['uri']);
            }
        }

        $blocks = $this->db->select("
            id as id,
            img as img,
            position as position,
            coordinates as coordinates,
            color as color,
            background as background,
            opacity as opacity,
            text_$this->lclang as text,
        ")->where('product_id', $product->id)->where('isshown', 1)->get('product_block')->result();

        $this->breadcrumbs[] = $this->_generate_bc_data($product->title, $product->uri);

        foreach(language(true) as $lang){
            $array = $lang.'_urls';
            $this->{$array}[] = $this->page_uri;
        }

        foreach(language(true) as $lang){
            $array = $lang.'_urls';
            $this->{$array}[] = $product->uri;
        }

        $this->loadOGImgData($product);
        $this->_init_seo_data($product);

        $key = ilabCrypt($product->id . '~NO_COLOR~NO_SIZE', true);

        $this->data['inner_view'] = 'pages/product/index';
        $this->data['page'] = $product;
        $this->data['product'] = $product;
        $this->data['key'] = $key;
        $this->data['photos'] = $photos;
        $this->data['feedbacks'] = $feedbacks;
        $this->data['filters'] = $attributes;
        $this->data['instructions'] = $instructions;
        $this->data['blocks'] = $blocks;
        $this->data['related_products'] = $related_products;
        $this->data['stores'] = $stores;
        $this->data['retail_ids'] = $retail_ids;
        $this->data['deliveryCourier'] = getCourierDelivery($this->lclang, $_SESSION['city_id']);

        $this->_render();
    }

    public function feedback(){
        $post = $this->input->post(null, true);
        $review = $post['review'];

        $img = '';

        if (!empty($_FILES['files']['name'][0])) {
            $files = $_FILES['files'];

            $cpt = count($_FILES['files']['name']);
            for ($i = 0; $i < $cpt; $i++) {
                $_FILES['files']['name'] = $files['name'][$i];
                $_FILES['files']['type'] = $files['type'][$i];
                $_FILES['files']['tmp_name'] = $files['tmp_name'][$i];
                $_FILES['files']['error'] = $files['error'][$i];
                $_FILES['files']['size'] = $files['size'][$i];

                if (!is_dir(realpath('public') . '/product_feedback/' . $review['product_id'])) {
                    mkdir(realpath('public') . '/product_feedback/' . $review['product_id']);
                }

                $config['upload_path'] = realpath("public") . '/product_feedback/' . $review['product_id'] ;
                $config['allowed_types'] = 'svg|jpg|jpeg|pdf|png|jp2';
                $config['encrypt_name'] = TRUE;
                $this->load->library('upload', $config);


                $this->upload->do_upload('files');
                $file_data = $this->upload->data();
                $img .= ' '.$file_data['file_name'];

            }
        }

        $data = array(
            'product_id' => $review['product_id'],
            'first_name' => $review['name'],
            'last_name' => $review['surname'],
            'email' => '',
            'phone' => '',
            'text' => $review['text'],
            'score' => $review['score'],
            'date' => date('Y-m-d'),
            'img' => $img
        );

        $this->db->insert("product_feedback", $data);

        $product = $this->db->select('uri')->where('id', $review['product_id'])->get('product')->row();

        header("Location: /$this->lclang/product/".$product->uri."/");
    }

    public function quick() {
        $post = $this->input->post(null, true);
        $quick = $post['quick'];

        $product = $this->db->select("name_$this->lclang as name, uri")->where('id', $quick['product_id'])->get('product')->row();
        $product_price = $this->db->where('product_id', $quick['product_id'])->get('product_price')->row();

        $data = array(
            'name' => $quick['name'],
            'email' => $quick['email'],
            'phone' => $quick['phone'],
            'product_id' => $quick['product_id'],
            'product_name' => $product->name,
            'product_price' => $product_price->discounted_price,
            'text' => $quick['text'],
            'created_at' => date('Y-m-d H:i:s')
        );

        $this->db->insert("quick_orders", $data);

        $_SESSION['quick_order'] = true;

        header("Location: /$this->lclang/product/".$product->uri."/");
    }

    public function preorder() {
        $post = $this->input->post(null, true);
        $quick = $post['preorder'];

        $product = $this->db->select("name_$this->lclang as name, uri")->where('id', $quick['product_id'])->get('product')->row();
        $product_price = $this->db->where('product_id', $quick['product_id'])->get('product_price')->row();

        $data = array(
            'name' => $quick['name'],
            'email' => $quick['email'],
            'phone' => $quick['phone'],
            'product_id' => $quick['product_id'],
            'product_name' => $product->name,
            'product_price' => $product_price->discounted_price,
            'created_at' => date('Y-m-d H:i:s')
        );

        $this->db->insert("preorder_orders", $data);

        $_SESSION['preorder_order'] = true;

        header("Location: /$this->lclang/product/".$product->uri."/");
    }

    public function credit() {
        $post = $this->input->post(null, true);
        $credit = $post['credit'];

        $product = $this->db->select("name_$this->lclang as name, uri")->where('id', $credit['product_id'])->get('product')->row();
        $product_price = $this->db->where('product_id', $credit['product_id'])->get('product_price')->row();

        $data = array(
            'phone' => $credit['phone'],
            'product_id' => $credit['product_id'],
            'product' => $product->name,
            'type' => $credit['type'],
            'credit' => $credit['month'],
            'product_price' => $product_price->discounted_price,
            'lang' => $this->lclang,
            'created_at' => date('Y-m-d H:i:s')
        );

        $this->db->insert("credit_orders", $data);

        $_SESSION['popup__success-title'] = SUCCESS_REQUEST;

        header("Location: /$this->lclang/product/".$product->uri."/");
    }

    public function multiple() {
        $post = $this->input->post(null, true);
        $quick = $post['quick'];

        foreach ($_SESSION['cart'] as $key => $item) {
            $pizza = explode('~', ilabCrypt($key, false));
            $id = $pizza[0];

            $product = $this->db->select("name_$this->lclang as name, uri")->where('id', $id)->get('product')->row();
            $product_price = $this->db->where('product_id', $id)->get('product_price')->row();

            $data = array(
                'name' => $quick['name'],
                'email' => $quick['email'],
                'phone' => $quick['phone'],
                'product_id' => $id,
                'product_name' => $product->name,
                'product_price' => $product_price->discounted_price,
                'text' => $quick['text'],
                'created_at' => date('Y-m-d H:i:s')
            );

            $this->db->insert("quick_orders", $data);
        }

        $_SESSION['cart'] = [];
        $_SESSION['order'] = [];
        $_SESSION['uds'] = [];

        $_SESSION['popup__success-title'] = SUCCESS_REQUEST;

        header("Location: /$this->lclang/product/".$product->uri."/");
    }

}
