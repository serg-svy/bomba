<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {

    function __construct() {
        parent::__construct();

        @session_start();
        header('Content-type: text/html; charset=utf-8');
        $this->load->driver('cache', array('adapter' => 'apc', 'backup' => 'file'));

        $this->load->helper("admin_helper");

        $il = @$_SESSION['islogged'];
        $ll = @$_SESSION['login'];
        $uid = @$_SESSION['user_id'];

        if(empty($il) || empty($ll) || empty ($uid))
        {
            $cc = $this->uri->segment(1);
            $ac = $this->uri->segment(2);

            if($cc != ADM_CONTROLLER || $ac != 'login')
            {
                header("Location: /".ADM_CONTROLLER."/login");
                exit();
            }
        }
    }

    public function _langs() {
        return array('ru','ro');
    }

    public function index() {
        header("Location: /".ADM_CONTROLLER."/menu/");
    }

    function checkboxChange() {
        $table = $this->input->post('table', true);
        $col = $this->input->post('col', true);
        $id = $this->input->post('id', true);
        $val = $this->input->post('val', true);
        $this->db->where('id', $id)->update($table, array($col=>$val));
    }

    function myRemoveImage() {
        $table = $this->input->post('table', true);
        $col = $this->input->post('col', true);
        $id = $this->input->post('id', true);

        $row = $this->db->where('id', $id)->get($table)->row_array();
        if(!$row) return false;

        unlink_files($table, $row[$col]);
        $this->db->where('id', $id)->update($table, array($col=>''));

        return true;
    }

    function delete_row() {
        $table=uri(3);
        $id=uri(4);

        $row = $this->db->where("id", $id)->get($table)->row();

        $images = [];
        if($table == 'category') $images = [$row->image, $row->image_size_ru, $row->image_size_ro, $row->image_terminal];
        if($table == 'slider') $images = [$row->image_ru, $row->image_ro, $row->image_mobile_ru, $row->image_mobile_ro, $row->image_terminal_ru, $row->image_terminal_ro, $row->image_terminal_sleep_ru, $row->image_terminal_sleep_ro];
        if($table == 'main_blocks') $images = [$row->image_ru, $row->image_ro];
        if($table == 'main_banners') $images = [$row->image_ru, $row->image_ro, $row->image_mobile_ru, $row->image_mobile_ro];
        if($table == 'category_banners') $images = [$row->image_ru, $row->image_ro];
        if($table == 'brand_banners') $images = [$row->image_ru, $row->image_ro];
        if($table == 'article') $images = [$row->image_head_ru, $row->image_head_ro, $row->image_list_ru, $row->image_list_ro];
        if($table == 'news') $images = [$row->image_head_ru, $row->image_head_ro, $row->image_list_ru, $row->image_list_ro];
        if($table == 'store') $images = [$row->image];
        if($table == 'gift_cards') $images = [$row->image_ru, $row->image_ro];
        if($table == 'uds_blocks') $images = [$row->img];
        if($table == 'credit_companies') $images = [$row->img];
        if($table == 'promotions') $images = [$row->image_list_ru, $row->image_list_ro, $row->image_header_left_ru, $row->image_header_left_ro, $row->image_header_right_ru, $row->image_header_right_ro, $row->image_terminal_ru, $row->image_terminal_ro];
        if($table == 'promotion_category') $images = [$row->img];
        if($table == 'payment_type') $images = [$row->image];
        if($table == 'delivery_type') $images = [$row->image, $row->image_active];
        if($table == 'product_feedback') $images = explode(" ", $row->img);
        if($table == 'shops') $images = [$row->logo, $row->items, $row->banner_ro, $row->banner_ru, $row->mobile_banner_ro, $row->mobile_banner_ru];
        if($table == 'shop_advantages') $images = [$row->image];
        if($table == 'shop_categories') $images = [$row->image_ru, $row->image_ro];

        if($images) unlink_files(($table == 'product_feedback') ? $table.'/'.$row->product_id : $table, $images);

        $this->db->where('id',$id)->delete($table);

        header("Location: /".ADM_CONTROLLER."/$table/");
        exit();
    }

    function delete_row_return() {
        $table=uri(3);
        $id=uri(4);
        $return_id=uri(5);
        $this->db->where('id',$id)->delete($table);
        header("Location: /".ADM_CONTROLLER."/$table/$return_id");
        exit();
    }

    function delete_all() {
        $table=uri(3);
        $id=uri(4);
        $this->db->where('product_id',$id)->delete($table);
        header("Location: /".ADM_CONTROLLER."/$table/");
        exit();
    }

    function autocomplete() {
        $data = array();

        $query = $this->input->get('term');

        $this->db->select('name_ru,id,sku');
        $this->db->where('id', $query);
        $this->db->or_where('code', $query);
        $this->db->or_like('sku', $query);
        $this->db->or_like('name_ru', $query);
        $this->db->limit(15);
        $prod = $this->db->get('product')->result_array();

        foreach($prod as $key => $row) {
            if($row['id']) {
                $name = str_replace("\n"," ",$row['name_ru']);
                $name = str_replace("\r"," ",$name);
                $name = $name.' ('.$row['sku'].')';

                $data[] = array(
                    'label' => $name,
                    'value' => $name,
                    'id'    => $row['id']
                );
            }
        }

        echo json_encode($data);
    }

    /*------------------------------*/

    public function login()
    {

        $fldata='';
        if($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $check_user=$this->db->where('login',$_POST['login'])->where('pass',md5($_POST['pass']))->get('user')->row_array();
            if(!empty($check_user))
            {
                $_SESSION["login"]=$_POST['login'];
                $_SESSION["islogged"]= TRUE;
                $_SESSION["user_id"]= $check_user['id'];
                header("Location: /".ADM_CONTROLLER."/menu");
            }
            else
            {
                $fldata="неправильный логин/пароль";
            }
        }

        $this->load->view("/".ADM_CONTROLLER."/login", array('fldata'=>$fldata));
    }

    function logout()
    {
        unset($_SESSION["user_id"]);
        header("Location: /".ADM_CONTROLLER."/login");
    }

    function users()
    {
        $err1=$err2='';
        if($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $ll = $_SESSION["login"];
            $checku = $this->db->where("login", $ll)->where("pass", md5($_POST['oldp']))->get("user")->row_array();
            if(!empty($checku))
            {
                if(!empty($_POST['newp']) && !empty($_POST['newp1']) && $_POST['newp'] == $_POST['newp1'])
                {
                    $uid = $checku['id'];
                    $pass = md5($_POST['newp']);

                    $this->db->where("id", $uid)->update("user", ['pass' => $pass]);
                } else {
                    $err2='<div style="color:#ff0000;">Введенные пароли не совпадают</div>';
                }
            }else {
                $err2='<div style="color:#ff0000;">Введенные пароли не совпадают</div>';
            }
        }
        if (!empty($_SESSION['er1'])) {
            $err1='<div style="color:#ff0000;">'.$_SESSION['er1'].'</div>';
            unset($_SESSION['er1']);
        }
        $this->load->view('layouts/cp_layout',array('inner_view'=>ADM_CONTROLLER.'/users','err1'=>$err1,'err2'=>$err2));
    }

    function delUser()
    {
        $uid = $this->uri->segment(3);
        if(!empty($uid))
        {
            $this->db->where("id", $uid)->delete("user");
        }
        header("Location: /".ADM_CONTROLLER."/users/");
    }

    function newUser()
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            if(!empty($_POST['login']) && !empty($_POST['newp']) && !empty($_POST['newp1']) && $_POST['newp'] == $_POST['newp1'])
            {
                $pass = md5($_POST['newp']);
                $login = $_POST['login'];

                $this->db->insert("user", ['login' => $login, 'pass' => $pass]);
            } else {
                $_SESSION['er1']='Введенные пароли не совпадают';
            }
        }
        header("Location: /".ADM_CONTROLLER."/users/");
        exit();
    }

    public function admin_auth() {
        $response['status'] = 'nok';

        $pass = $this->input->get('pass');
        if($pass == 'LwAP6AcsZm8AoTKn8dYXt0sKkkTHEo') {

            $_SESSION['admin_auth'] = time();
            $response['status'] = 'ok';
        } else {
            set_status_header(404);
        }

        echo json_encode($response);
        exit();
    }

    /*------------------------------*/

    function constants()
    {
        if (!isset($_SESSION['admin_auth']) or time() > $_SESSION['admin_auth'] + 600) {
            $this->load->view('layouts/cp_layout',array('inner_view'=>ADM_CONTROLLER.'/admin_auth'));
            return false;
        }

        if($_SERVER['REQUEST_METHOD'] == 'POST')
        {
           foreach($_POST['ru'] as $key=>$val)
            {
                $this->db->where('id',$key)->update('constants',array('ru'=>$val));
            }
            foreach($_POST['ro'] as $key=>$val)
            {
                $this->db->where('id',$key)->update('constants',array('ro'=>$val));
            }
        }

        $this->load->view('layouts/cp_layout',array('inner_view'=>ADM_CONTROLLER.'/constants'));
    }

    /*------------------------------*/

    function edit_table_order_top() {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            if(!empty($_POST['data'])) {
                $vals=explode('<>',$_POST['data']);
                if (empty($_POST['field'])) $field='order_top'; else $field=$_POST['field'];
                foreach($vals as $value) {
                    $dt=explode(':',$value);
                    $this->db->where('id',$dt[0])->update($_POST['table'],array($field=>$dt[1]));
                }
            }
        }
    }

    function edit_table_order_bottom() {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            if(!empty($_POST['data'])) {
                $vals=explode('<>',$_POST['data']);
                if (empty($_POST['field'])) $field='order_bottom'; else $field=$_POST['field'];
                foreach($vals as $value) {
                    $dt=explode(':',$value);
                    $this->db->where('id',$dt[0])->update($_POST['table'],array($field=>$dt[1]));
                }
            }
        }
    }

    function edit_table_order() {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            if(!empty($_POST['data'])) {
                $vals=explode('<>',$_POST['data']);
                if (empty($_POST['field'])) $field='sorder'; else $field=$_POST['field'];
                foreach($vals as $value) {
                    $dt=explode(':',$value);
                    $this->db->where('id',$dt[0])->update($_POST['table'],array($field=>$dt[1]));
                }
            }
        }
    }

    function menu() {
        $main_name='menu';
        init_load_img($main_name);
        $data=array('inner_view'=>ADM_CONTROLLER.'/menu');
        $this->load->view('layouts/cp_layout',$data);
    }

    function slider() {
        $main_name='slider';
        init_load_img($main_name);

        $this->load->view('layouts/cp_layout',array('inner_view'=>ADM_CONTROLLER."/".$main_name));
    }

    function credit() {
        $main_name='credit';
        init_load_img($main_name);

        $this->load->view('layouts/cp_layout',array('inner_view'=>ADM_CONTROLLER."/".$main_name));
    }

    public function popular_requests() {
        $this->load->library('pagination');
        $main_name='popular_requests';
        $this->load->view('layouts/cp_layout', ['inner_view' => ADM_CONTROLLER."/".$main_name]);
    }

    public function product_feedback($id = '') {

        if($id =='download') {
            $this->load->library('excel');
            $this->excel->createFeedbackExcelFile();
        } else {
            $this->load->library('pagination');
            $main_name='product_feedback';
            init_load_img($main_name);
            $this->load->view('layouts/cp_layout', ['inner_view' => ADM_CONTROLLER."/".$main_name]);
        }
    }

    public function product_feedback_edit($id = '') {
        $main_name='product_feedback_edit';
        init_load_img($main_name);
        $this->load->view('layouts/cp_layout', ['inner_view' => ADM_CONTROLLER."/".$main_name]);
    }

    function main_banners() {
        $main_name='main_banners';
        init_load_img($main_name);

        $this->load->view('layouts/cp_layout',array('inner_view'=>ADM_CONTROLLER."/".$main_name));
    }

    function category_banners() {
        $main_name='category_banners';
        init_load_img($main_name);

        $this->load->view('layouts/cp_layout',array('inner_view'=>ADM_CONTROLLER."/".$main_name));
    }

    function brand_banners() {
        $main_name='brand_banners';
        init_load_img($main_name);

        $this->load->view('layouts/cp_layout',array('inner_view'=>ADM_CONTROLLER."/".$main_name));
    }

    function shop_advantages() {
        $main_name='shop_advantages';
        init_load_img($main_name);

        $this->load->view('layouts/cp_layout',array('inner_view'=>ADM_CONTROLLER."/".$main_name));
    }

    function shop_categories() {
        $main_name='shop_categories';
        init_load_img($main_name);

        $this->load->view('layouts/cp_layout',array('inner_view'=>ADM_CONTROLLER."/".$main_name));
    }

    function main_blocks() {
        $main_name='main_blocks';
        init_load_img($main_name);

        $this->load->view('layouts/cp_layout',array('inner_view'=>ADM_CONTROLLER."/".$main_name));
    }

    function bestseller() {
        $main_name='bestseller';
        init_load_img($main_name);

        $this->load->view('layouts/cp_layout',array('inner_view'=>ADM_CONTROLLER."/".$main_name));
    }

    function about_images() {
        $main_name='about_images';
        init_load_img($main_name);
        $this->load->view('layouts/cp_layout',array('inner_view'=>ADM_CONTROLLER."/".$main_name));
    }

    function del_about_images() {
        $id=$this->uri->segment(3);
        $image = $this->db->where("id", $id)->get("about_images")->row()->image;
        unlink(realpath("public") . "/about_images/" . $image);
        $this->db->where('id',$id)->delete('about_images');
    }

    function store() {
        $main_name='store';
        init_load_img($main_name);

        unset($_POST['filepond']);
        $this->load->view('layouts/cp_layout',array('inner_view'=>ADM_CONTROLLER."/".$main_name));
    }

    function delStoreImg() {
        $uri3=uri(3);
        $uri4=uri(4);

        $item = $this->db->where('id', $uri3)->get('store_images')->row();
        if(!empty($item)) {
            unlink_files('store', $item->img);
        }

        $this->db->where("id", $uri3)->delete("store_images");
        header("Location: /cp/store/$uri4/");
    }

    function updStoreImgSort() {
        $uri3=uri(3);
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            if(!empty($_POST['so'])) {
                foreach($_POST['so'] as $key=>$val) {
                    $this->db->where("id", $key)->update("store_images", ["sorder" => $val]);
                }
            }
        }
        header("Location: /cp/store/$uri3/");
        header("Location: /cp/store/$uri3/");
        exit();
    }

    function news() {
        $main_name='news';
        init_load_img($main_name);
        $this->load->view('layouts/cp_layout',array('inner_view'=>ADM_CONTROLLER."/".$main_name));
    }

    function reviews() {
        $main_name='reviews';
        init_load_img($main_name);
        $this->load->view('layouts/cp_layout',array('inner_view'=>ADM_CONTROLLER."/".$main_name));
    }

    function departments() {
        $main_name='departments';
        $this->load->view('layouts/cp_layout',array('inner_view'=>ADM_CONTROLLER."/".$main_name));
    }

    function contacts() {
        $main_name='contacts';
        $this->load->view('layouts/cp_layout',array('inner_view'=>ADM_CONTROLLER."/".$main_name));
    }

    function promotions() {
        $main_name='promotions';
        init_load_img($main_name);
        $this->load->view('layouts/cp_layout',array('inner_view'=>ADM_CONTROLLER."/".$main_name));
    }

    function promotion_category() {
        $main_name='promotion_category';
        $this->load->view('layouts/cp_layout',array('inner_view'=>ADM_CONTROLLER."/".$main_name));
    }

    function article() {
        $main_name='article';
        init_load_img($main_name);
        $this->load->view('layouts/cp_layout',array('inner_view'=>ADM_CONTROLLER."/".$main_name));
    }

    function shops() {
        $main_name='shops';
        init_load_img($main_name);
        $this->load->view('layouts/cp_layout',array('inner_view'=>ADM_CONTROLLER."/".$main_name));
    }

    function category() {
        if($this->uri->segment(3) == 'delete') {
            $pass = $this->input->get('pass');
            if($pass == 'LwAP6AcsZm8AoTKn8dYXt0sKkkTHEo') {

                $response['status'] = 'ok';
                $category_id = $this->uri->segment(4);

                $category = $this->db->where('id', $category_id)->get('category')->row_array();
                if($category) {
                    $category_ids = $this->recursie_cat($category['id']);
                    $category_ids[] = $category_id;

                    if(count($category_ids)) {
                        foreach($category_ids as $cat_id) {
                            $this->db->where('id', $cat_id)->delete('category');
                        }
                    }

                    $response['categ'] = $category_ids;
                } else {
                    set_status_header(404);
                }
            } else {
                set_status_header(404);
            }

            echo json_encode($response);
            exit();
        }

        $main_name='category';
        init_load_img($main_name);

        $this->load->view('layouts/cp_layout',array('inner_view'=>ADM_CONTROLLER."/".$main_name));
    }

    function recursie_cat($cat_id, $category_ids = array()) {
        $subcat = $this->db->where('parent_id', $cat_id)->get('category')->result_array();
        if($subcat) {
            foreach($subcat as $sub) {
                $category_ids[] = $sub['id'];
                $category_ids = $this->recursie_cat($sub['id'], $category_ids);
            }
        }

        return $category_ids;
    }

    function category_filtered(){
        if($this->uri->segment(4) && $this->uri->segment(4) == 1){
            $id_categorie = intval($this->uri->segment(3));
            $sqldata = array(
                'category_id' => $id_categorie,
                'rendered_link' => '',
                'title_ro' => '',
                'title_ru' => '',
                'meta_title_ro' => '',
                'meta_title_ru' => '',
                'seo_desc_ro' => '',
                'seo_desc_ru' => '',
                'desc_ro' => '',
                'desc_ru' => '',
                'attr_json_data' => '',
                'index_page' => ''
            );
            if($this->db->insert('category_filtered', $sqldata)){
                $error = null;
                $get_i = $this->db->select('MAX(id) as id')->get('category_filtered')->row();
                $filtered_id = $get_i->id;
                header("Location: /".ADM_CONTROLLER."/category_filtered/".$filtered_id."/");
                exit;
            }else{
                $error = 'Ошибка при генерации ссылки';
                header("Location: /".ADM_CONTROLLER."/category_filtered/");
                exit;
            }

        }
        $main_name = 'category_filtered';
        if($this->input->server('REQUEST_METHOD') == 'POST'){

        }
        $this->load->view('layouts/cp_layout', array('inner_view'=>ADM_CONTROLLER."/".$main_name));
    }

    function getSubcategories() {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $response['status'] = 'fail';

            $post = $this->input->post();
            $response['post'] = $post;

            $countChildSelect = $this->db
                ->select('count(id) as c')
                ->where('parent_id=cId')
                ->get_compiled_select('category');

            $subcategories = $this->db->select(
                'name_ru as name,
                 id,
                 id as cId,
                 url,
                 sorder,
                 parent_id,
                 is_shown,
                 show_popup_18,
                 level,
                 is_russian_size,
                 is_new,
                 ('. $countChildSelect.') as count_child
                 ')
                ->where('parent_id', $post['category_id'])
                ->order_by('sorder asc,id desc')
                ->get('category')->result_array();

            if($subcategories) {
                $response['status'] = 'ok';
                $response['subcategories'] = $subcategories;

                $response['html'] = $this->load->view(ADM_CONTROLLER.'/category_template', $response, true);
            } else {
                $response['status'] = 'empty';
            }

            echo json_encode($response);
        }
    }

    function product_block($id = false) {

        $main_name='product_block';
        init_load_img($main_name);
        $this->load->model('product_block_model');

        if($_SERVER['REQUEST_METHOD'] == 'POST'){

            if(empty($_POST)){
                return false;
            }
            if(empty($id)){
                $new_array = diverse_array($_POST);

                $counter = 0;
                $tempimg = $_FILES['img'];
                foreach($new_array as $ar){

                    $post = [];
                    foreach($ar as $key => $value){
                        if(preg_match('/(text_ru_)/', $key)){
                            $post['text_ru'] = $value;
                        } elseif ( preg_match('/text_ro_/', $key)){
                            $post['text_ro'] = $value;
                        } else {
                            $post[$key] = $value;
                        }
                    }
                    $post['product_id'] = $_POST['product_id'];

                    if (!empty($_FILES['img'])) {

                        $image_array = diverse_array($tempimg);

                        $_FILES = array();
                        if(!empty($image_array[$counter]['name'])) {
                            $_FILES['img'] = $image_array[$counter];


                            $this->upload->do_upload('img');
                            $file_data = $this->upload->data();
                            $file = $file_data['file_name'];

                            if($file_data['file_size'] > 2048){
                                throw new Exception('Максимальный размер фотографии не должен превышать 2МБ!');
                            }

                            $file_types = array('.jpg', '.jpeg', '.gif', '.png');

                            if (in_array(strtolower($file_data['file_ext']), $file_types)) {
                                $post['img'] = $file;
                            }
                        }
                    }

                    $find_block = $this->product_block_model->findOne($post['block_id']);

                    if(!empty($post)){
                        if(!empty($find_block)){
                            unset($post['block_id']);
                            $id = $this->product_block_model->update($post, $_POST['product_id']);
                        } else {
                            unset($post['block_id']);
                            $id = $this->product_block_model->put($post);
                        }
                    }
                    $counter++;
                    $_FILES['img'] = $tempimg;
                }
            } else {
                $posts = [];
                $posts = diverse_array($_POST);
                $tempimg = $_FILES['img'];
                $i = 0;
                $imgs = [];
                foreach($posts as $po){
                    $post = [];
                    foreach($po as $key => $value){
                        if(preg_match('/(text_ru_)/', $key)){
                            $post['text_ru'] = $value;
                        } elseif ( preg_match('/text_ro_/', $key)){
                            $post['text_ro'] = $value;
                        } else {
                            $post[$key] = $value;
                        }
                    }
                    $post['product_id'] = $id;

                    if (!empty($_FILES['img']['name'][$post['block_id']])) {
                        $image_array = diverse_array($tempimg);
                        $_FILES = array();
                        $_FILES['img'] = $image_array[$post['block_id']];

                        $this->upload->do_upload('img');
                        $file_data = $this->upload->data();
                        $file = "";
                        $file = $file_data['file_name'];
                        $imgs[] = $file;

                        if($file_data['file_size'] > 2048){
                            throw new Exception('Максимальный размер фотографии не должен превышать 2МБ!');
                        }

                        $file_types = array('.jpg', '.jpeg', '.gif', '.png');

                        if (in_array(strtolower($file_data['file_ext']), $file_types)) {
                            $post['img'] = $file;
                        }
                    }
                    $find_block = "";
                    $find_block = $this->product_block_model->findOne($post['block_id']);
                    if(!empty($post)){
                        if(!empty($find_block)){
                            unset($post['block_id']);
                            $res = $this->product_block_model->update($post, $find_block['id']);
                        } else {
                            unset($post['block_id']);
                            $res = $this->product_block_model->put($post);
                        }
                    }
                    $i++;
                }
            }

        }
        $_POST = [];
        $this->load->view('layouts/cp_layout', array('inner_view'=>ADM_CONTROLLER."/".$main_name));
    }

    function product_seo_check(){
        $main_name = 'product_seo';
        if($this->input->server('REQUEST_METHOD') == 'POST'){
            $product_id = $this->input->post('identificator');
            if(!empty($product_id)){
                $result = $this->db->where('id', $product_id)->get('product')->row;
                if($result){
                    header("Location: /".ADM_CONTROLLER."/product_seo/".$product_id."/");
                }else{
                    header("Location: /".ADM_CONTROLLER."/product_seo/");
                }
            }
        }

        $this->load->view('layouts/cp_layout', array('inner_view'=>ADM_CONTROLLER."/".$main_name));
    }

    function product_seo_list(){
        if($this->input->server('REQUEST_METHOD') == 'POST'){
            $product_id = $this->input->post('identificator');
            if(!empty($product_id)){
                $result = $this->db->like('id', $product_id)->get('product')->result_array();
                if($result){
                    echo json_encode($result);
                }else{
                    echo json_encode(array('res' => 1));
                }
            }
        }
    }

    function product_seo(){
        $main_name = 'product_seo';
        if($this->input->server('REQUEST_METHOD') == 'POST'){
            $product_id = $this->uri->segment(3);
            $meta_title_ru = $this->input->post('meta_title_ru');
            $meta_title_ro = $this->input->post('meta_title_ro');
            $meta_description_ru = $this->input->post('meta_description_ru');
            $meta_description_ro = $this->input->post('meta_description_ro');
            $data = array(
                'meta_title_ru' => $meta_title_ru,
                'meta_title_ro' => $meta_title_ro,
                'meta_description_ru' => $meta_description_ru,
                'meta_description_ro' => $meta_description_ro
            );
            $this->db->where('id', $product_id);
            $this->db->update('product', $data);
        }

        $this->load->view('layouts/cp_layout', array('inner_view'=>ADM_CONTROLLER."/".$main_name));
    }

    function category_search_list(){
        if($this->input->server('REQUEST_METHOD') == 'POST'){
            $product_id = $this->input->post('identificator');
            if(!empty($product_id)){
                $result = $this->db->where('is_shown', 1)->where('has_products', 1)->like('name_ru', $product_id)->get('category')->result_array();

                if($result){
                    echo json_encode($result);
                }else{
                    echo json_encode(array('res' => 1));
                }
            }
        }
    }

    function category_filtered_validate(){
        //validate action
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $post = $_POST;
            $category_id = $_POST['filt_id'];
            $cat_id = $post['category_id'];
            $check_if_exist = $this->db->where('id', $category_id)->get('category_filtered')->row();

            if(isset($post['brand'])){
                $total[] = array('brand' => $post['brand'], 'sort' => 0);
            }

            if($post['atribute']){
                foreach($post['atribute']['status'] as $key => $atribut){
                    $total[] = array(
                        'atribute' => $key,
                        'value' => $post['atribute']['value'][$key],
                        'sort' => $post['sort'][$key]
                    );
                }
            }else{
                $total[] = null;
            }

            if(!empty($check_if_exist)){
                $renderlink = $this->render_category_filter_link(json_encode($total), $cat_id);
                $sqldata = array(
                    'rendered_link' => $renderlink,
                    'title_ro' => $post['title_ro'],
                    'title_ru' => $post['title_ru'],
                    'meta_title_ro' => $post['meta_title_ro'],
                    'meta_title_ru' => $post['meta_title_ru'],
                    'seo_desc_ro' => $post['seo_desc_ro'],
                    'seo_desc_ru' => $post['seo_desc_ru'],
                    'desc_ro' => $post['desc_ro'],
                    'desc_ru' => $post['desc_ru'],
                    'attr_json_data' => json_encode($total),
                    'index_page' => $post['index_page']
                );
                if($this->db->where('id', $category_id)->update('category_filtered', $sqldata)){
                    $error = null;
                }else{
                    $this->db->_error_message();
                    $error = 'Ошибка при обновление';
                }
            }else{
                $renderlink = $this->render_category_filter_link(json_encode($total), $cat_id);
                $sqldata = array(
                    'category_id' => $category_id,
                    'rendered_link' => $renderlink,
                    'title_ro' => $post['title_ro'],
                    'title_ru' => $post['title_ru'],
                    'meta_title_ro' => $post['meta_title_ro'],
                    'meta_title_ru' => $post['meta_title_ru'],
                    'seo_desc_ro' => $post['seo_desc_ro'],
                    'seo_desc_ru' => $post['seo_desc_ru'],
                    'desc_ro' => $post['desc_ro'],
                    'desc_ru' => $post['desc_ru'],
                    'attr_json_data' => json_encode($total),
                    'index_page' => $post['index_page']
                );
                if($this->db->insert('category_filtered', $sqldata)){
                    $error = null;
                }else{
                    $error = 'Ошибка при генерации ссылки';
                }
            }

            header("Location: /".ADM_CONTROLLER."/category_filtered/".$category_id."/");
            exit;
        }else{
            header("Location: /".ADM_CONTROLLER."/category_filtered/");
            exit;
        }
    }

    function render_category_filter_link($json_data = '', $categ_id){
        ini_set('display_errors', 1);
        error_reporting(E_ALL);
        if($json_data != '' && $json_data != null){
            $obj = json_decode($json_data, true);
            $brand = null;
            foreach($obj as $key => $item){
                if(isset($item['brand']) && $item['brand'] != 0){
                    $brand = $item['brand'];
                }
                if(isset($item['atribute'])){
                        $atribute[] = array(
                            'atribute' => $item['atribute'],
                            'value' => $item['value'],
                            'sort' => $item['sort']
                        );
                }
            }
            if($brand != false){
                $get_brand_data = $this->db->select('name')->where('id', $brand)->get('brand')->row_array();
                $get_brand_data = transliteration($get_brand_data['name']);
            }
            $get_categ = $this->db->select('url')->where('id', $categ_id)->get('category')->row_array();
            $link_categ = $get_categ['url'];
            $for_link[] = $link_categ;
            if($brand != false){
                $for_link[] = $get_brand_data;
            }
            if(isset($atribute)) {
                if (is_array($atribute)) {
                    foreach ($atribute as $key => $item) {
                        if($item['value'] != '0'){
                            $for_link[] = transliteration($item['value']);
                        }
                    }
                } else {
                    return false;
                }
            }

            $rendered_link = implode('__', $for_link);
            return $rendered_link;
        }
    }

    function delete_category_filtered(){
        $id = $this->uri->segment(3);
        $isset = $this->db->where('id', $id)->get('category_filtered')->row();
        if(!empty($isset)){
            $row_id = $isset->id;
            $this->db->where('id', $row_id)->delete('category_filtered');
            header('Location: https://'.$_SERVER['HTTP_HOST']."/".ADM_CONTROLLER."/category_filtered");
            exit;
        }else{
            header('Location: https://'.$_SERVER['HTTP_HOST']."/".ADM_CONTROLLER."/category_filtered");
            exit;
        }
    }

    function render_category_sitemap(){
        ini_set('display_errors', 1);
        error_reporting(E_ALL);
        $get_all_links = $this->db->get('category_filtered')->result_array();
        if(!empty($get_all_links)){
            $mydate = date('Y-m-d');
            $links = array();
            $xmldata = '<?xml version="1.0" encoding="UTF-8"?>
                        <urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"> 
                         ';
            foreach($get_all_links as $key => $link){
                $links[] = $link['rendered_link'];
                $xmldata .= '<url>
                                <loc>'.$link['rendered_link'].'</loc>
                                <lastmod>'.$mydate.'</lastmod>
                            </url>';
            }
            $xmldata .= '</urlset>';

            if(write_file(FCPATH.'seocategory_sitemap.xml', $xmldata)){
                echo '<br> <span style="color:green; width: 100%; text-align: center; font-size: 20px; font-weight: bold; padding:20px 0px; background: mediumseagreen; display: block">
                    Category sitemap успешно создан!
                    </span>
                    <span style="color:black; width: 100%; text-align: center; font-size: 16px; font-weight: bold; padding:20px 0px; background: mediumseagreen; display: block">
                    Ссылка на файл: <a href="https://'.$_SERVER['HTTP_HOST'].'/seocategory_sitemap.xml" target="_blank">seocategory_sitemap.xml</a>
                    </span> ';
            }else{
                echo '<br> <span style="color:red;  width: 100%; text-align: center; font-size: 20px; font-weight: bold; padding:20px 0px; background:lightcoral; display: block">Ошыбка записи файла!</span>';
            }

        }else{

        }
    }

    function payment_type() {
        $main_name='payment_type';
        init_load_img($main_name);
        $this->load->view('layouts/cp_layout',array('inner_view'=>ADM_CONTROLLER."/".$main_name));
    }

    function delivery_type() {
        $main_name='delivery_type';
        init_load_img($main_name);
        $this->load->view('layouts/cp_layout',array('inner_view'=>ADM_CONTROLLER."/".$main_name));
    }

    function orders() {
        $this->load->model(array('product_model'));

        $main_name='orders';
        $this->load->view('layouts/cp_layout',array('inner_view'=>ADM_CONTROLLER."/".$main_name));
    }

    function quick_orders() {
        $main_name = 'quick_orders';
        $this->load->view('layouts/cp_layout', array('inner_view'=>ADM_CONTROLLER."/".$main_name));
    }

    function preorders() {
        $main_name = 'preorder_orders';
        $this->load->view('layouts/cp_layout', array('inner_view'=>ADM_CONTROLLER."/".$main_name));
    }


    function credit_orders() {
        $main_name = 'credit_orders';
        $this->load->view('layouts/cp_layout', array('inner_view'=>ADM_CONTROLLER."/".$main_name));
    }

    function uds_blocks() {
        $main_name='uds_blocks';
        init_load_img($main_name);
        $this->load->view('layouts/cp_layout',array('inner_view'=>ADM_CONTROLLER."/".$main_name));
    }

    function credit_companies() {
        $main_name='credit_companies';
        init_load_img($main_name);
        $this->load->view('layouts/cp_layout',array('inner_view'=>ADM_CONTROLLER."/".$main_name));
    }

    function change_order_status($id,$val) {

        $this->db->where('id',$id)->update('orders',
            [
                'status' => $val,
                'status_changed_at' => date('Y-m-d H:i:s')
            ]
        );
        if ($val == 6) {
            $this->sendSMS($id);
            $this->sendEmailToPartners($id);

            if (in_array($_SERVER['REMOTE_ADDR'], ['178.18.47.155', '188.25.121.157', '185.181.230.151', '81.196.3.36'])) {
                $this->update_slots($id);
            }
        }
    }

    function set_order_message() {
        $id = $this->input->post("id", true);
        $message = $this->input->post("message", true);
        $this->db->where('id',$id)->update('orders',array('message'=>$message));
    }

    function manual_send_to_una() {
        $id = $this->input->post("id", true);
        $this->load->library("una");
        $this->una->send($id);
    }

    function credit_send_to_una() {
        $id = $this->input->post("id", true);
        $this->load->library("una");
        $this->una->credit_send($id);
    }

    function fast_send_to_una() {
        $id = $this->input->post("id", true);
        $this->load->library("una");
        $this->una->fast_send($id);
    }

    function update_slots($id){
        $order = $this->db->where("id", $id)->get("orders")->row();
        $pizza = explode(" ", $order->delivery_date);
        if(is_array($pizza) and !empty($pizza)) {
            $city = $this->db->where("id", $order->city_id)->get("city")->row();
            if (!empty($city)) {
                $region = $this->db->where("id", $city->region_id)->get("regions")->row();
                if (!empty($region)) {
                    $slots = json_decode($region->slots, true);
                    $n = date("N", strtotime($pizza[0]));

                    if(isset($slots[$n])) {
                        foreach ($slots[$n] as &$day) {
                            if ($day['start'] == $pizza[1] and $day['end'] == $pizza[3]) {
                                --$day['qty'];
                            }
                        }

                        $this->db->where("id", $region->id)->update("regions", ["slots" => json_encode($slots)]);
                    }
                }
            }
        }
    }

    function change_slot_tab() {
        $id = $this->input->post("id", true);
        $_SESSION['slot_tab'] = $id;
        echo "ok";
    }

    function sendSMS($order_id) {
        $order = $this->db->where('id', $order_id)->get('orders')->row();
        if(!$order) return false;

        $city = $this->db->select("name_ro AS name")->where('id', $order->city_id)->get('city')->row();
        $address = trim($city->name);
        if($order->shop>0) {
            $shop = $this->db->select('name_ro AS name, address_ro AS address')->where('id', $order->shop)->get('store')->row();
            $address = trim($shop->name) .', '. trim($shop->address);
        }

        $address = str_replace(["«", "»"], " ", $address);

        $delivery_date = date('d.m.Y', strtotime('+2 days'));
        if (date('w', strtotime('+1 day')) != 7 || date('d.m.Y', strtotime('+2 days')) != 7)
            $delivery_date = date('Hi') < 1200 ? date('d.m.Y', strtotime('+1 day')) : date('d.m.Y', strtotime('+2 days'));
        elseif (date('w', strtotime('+1 day')) == 7) {
            $delivery_date = date('d.m.Y', strtotime('+2 days'));
        } elseif (date('w', strtotime('+2 days')) == 7) {
            $delivery_date = date('d.m.Y', strtotime('+3 days'));
        }

        // possibly format phone in db '0 74166323' or '+373 935 63 791' or '+37393563791'
        $phone = (strpos($order->phone,'+') === false) ? '+373' . substr($order->phone, 1) : $order->phone;
        $phone =  str_replace([' '], '', $phone);

        if ($order->delivery == 2)
            $text = "Numarul comenzii Dvs: $order->generated_id. Comanda Dvs va fi livrata doar dupa confirmarea acesteia de catre operatorul nostru.";
        elseif ($order->delivery == 4)
            $text = "Numarul comenzii Dvs: $order->generated_id. Punctul de eliberare a comenzilor: " . iconv("UTF-8", "ISO-8859-1//TRANSLIT", $address) . '. Comanda poate fi ridicata doar dupa confirmare.';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://193.16.111.11/sms.asp?smsid=1&to=' . $phone . '&from=+37322840054&text=' . urlencode($text) . '&username=bomba&password=Qp5Q984u');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        $response = curl_exec($ch);
        curl_close($ch);

        return true;
    }

    function sendEmailToPartners($order_id) {
        $order = $this->db->where('id', $order_id)->get('orders')->row();
        if(!$order) return false;

        $products = $this->db->select("
        order_items.quantity as quantity, 
        order_items.price as price, 
        order_items.reduction as reduction, 
        order_items.partner_id as partner_id,
        product.name_ro as name,
        product.id as id,
        product.code as code
        ")
            ->join("product", "product.id = order_items.product_id", "inner")
            ->where("order_items.order_id", $order_id)
            ->where("order_items.partner_id >", 1)
            ->get("order_items")
            ->result();

        $partner_products = array();
        foreach ($products as $product) {
            $code = $product->code;
            if(!$code) {
                if($product->partner_id > 1) {
                    $code = $product->id - (1000000 * $product->partner_id);
                } else {
                    $code = $product->id;
                }
            }
            if (isset($partner_products[$product->partner_id])) {
                $partner_products[$product->partner_id] = $partner_products[$product->partner_id] . '<br>' . $code .' - '. $product->name . ' x ' . $product->quantity . ' = ' . ($product->quantity * $product->price) . ' MDL';
            } else {
                $partner_products[$product->partner_id] = $code .' - '. $product->name . ' x ' . $product->quantity . ' = ' . ($product->quantity * $product->price) . ' MDL';
            }
        }

        if (!empty($partner_products)) {
            foreach ($partner_products as $partner_id => $partner_product) {

                $message = 'Pe site-ul bomba.md au fost vandute urmatoarele produse: <br>' . $partner_product;

                $partner_email = $this->db->select("email")->where("id", $partner_id)->get("partner")->row()->email;

                $this->load->library('email');
                $config = array();
                $config['charset'] = 'utf-8';
                $config['mailtype'] = 'html';
                $this->email->initialize($config);

                $this->email->from('noreply@' . $_SERVER['HTTP_HOST'], $_SERVER['HTTP_HOST']);
                $this->email->to([$partner_email]);
                $this->email->subject($_SERVER['HTTP_HOST']);
                $this->email->message($message);

                $this->email->send();
            }
        }

        return true;
    }

    function regions() {
        $main_name='regions';
        $this->load->view('layouts/cp_layout',array('inner_view'=>ADM_CONTROLLER."/".$main_name));
    }

    function city($task='', $id=0, $value=0) {
        if ($task == 'delivery') {
            $this->db->where('id', $id)->update('city', ['delivery' =>$value]);
            return false;
        }
        if ($task == 'pickup') {
            $this->db->where('id', $id)->update('city', ['pickup' =>$value]);
            return false;
        }
        $main_name='city';
        $this->load->view('layouts/cp_layout',array('inner_view'=>ADM_CONTROLLER."/".$main_name));
    }

    function bottom_category() {
        $main_name='bottom_category';
        $this->load->view('layouts/cp_layout',array('inner_view'=>ADM_CONTROLLER."/".$main_name));
    }

    function about_blocks() {
        $main_name='about_blocks';
        $this->load->view('layouts/cp_layout',array('inner_view'=>ADM_CONTROLLER."/".$main_name));
    }

    function payment_blocks() {
        $main_name='payment_blocks';
        $this->load->view('layouts/cp_layout',array('inner_view'=>ADM_CONTROLLER."/".$main_name));
    }

    function pickup_blocks() {
        $main_name='pickup_blocks';
        $this->load->view('layouts/cp_layout',array('inner_view'=>ADM_CONTROLLER."/".$main_name));
    }

    function gift_cards() {
        $main_name='gift_cards';
        $this->load->view('layouts/cp_layout',array('inner_view'=>ADM_CONTROLLER."/".$main_name));
    }

    function bannerCat($promo,$prod=0) {
        if (!empty($prod)) {
            $ins=array('category_id'=>$prod,'banner_id'=>$promo);
            $ch=$this->db->where('category_id',$prod)->where('banner_id',$promo)->get('banner_category')->row_array();
            $p_id = $ch['id'];
            if (empty($ch)) {
                $this->db->insert('banner_category',$ins);
                $p_id=$this->db->insert_id();
            }
        }
        $blocks=$this->db->where('banner_id',$promo)->order_by('id desc')->get('banner_category')->result_array();
        $data = $this->db->select('banner_category.category_id,banner_category.id as p_id,banner_category.banner_id,category.name_ru')->join('category','category.id = banner_category.category_id')
            ->where('banner_category.banner_id',$promo)->get('banner_category')->result_array();
        foreach($data as $row) {
            echo '<tr>
				<td>'.$row['name_ru'].'</td>';
            echo '<td width="220"><a href="javascript:void(0);" onclick="bannerCatDel('.$promo.','.$row['category_id'].');" class="btn red"><i class="fa fa-trash"></i> Удалить</a></td>
			</tr>';
        }
    }

    function bannerCatDel($promo,$prod=0) {
        if (!empty($prod)) {
            $this->db->where('category_id',$prod)->where('banner_id',$promo)->delete('banner_category');
        }
        $blocks=$this->db->where('banner_id',$promo)->order_by('id desc')->get('banner_category')->result_array();
        $data = $this->db->select('banner_category.category_id,banner_category.id as p_id,banner_category.banner_id,category.name_ru')->join('category','category.id = banner_category.category_id')
            ->where('banner_category.banner_id',$promo)->get('banner_category')->result_array();
        foreach($data as $row) {
            echo '<tr>
				<td>'.$row['name_ru'].'</td>';
            echo '<td width="220"><a href="javascript:void(0);" onclick="bannerCatDel('.$promo.','.$row['category_id'].');" class="btn red"><i class="fa fa-trash"></i> Удалить</a></td>
			</tr>';
        }
    }

    function bannerBrand($banner_id,$brand_id=false) {
        if (!empty($brand_id)) {
            $ins=array('brand_id'=>$brand_id,'banner_id'=>$banner_id);
            $ch=$this->db->where('brand_id',$brand_id)->where('banner_id',$banner_id)->get('banner_brand')->row_array();
            if (empty($ch)) {
                $this->db->insert('banner_brand', $ins);
            }
        }
        $data = $this->db->select('
        banner_brand.brand_id,
        banner_brand.id as p_id,
        banner_brand.banner_id,
        brand.name')
            ->join('brand','brand.id = banner_brand.brand_id')
            ->where('banner_brand.banner_id',$banner_id)->get('banner_brand')->result_array();
        foreach($data as $row) {
            echo '<tr>
				<td>'.$row['name'].'</td>';
            echo '<td width="220"><a href="javascript:void(0);" onclick="bannerBrandDel('.$banner_id.','.$row['brand_id'].');" class="btn red"><i class="fa fa-trash"></i> Удалить</a></td>
			</tr>';
        }
    }

    function bannerBrandDel($banner_id, $brand_id=false) {
        if (!empty($brand_id)) {
            $this->db->where('brand_id',$brand_id)->where('banner_id',$banner_id)->delete('banner_brand');
        }
        $data = $this->db->select('
        banner_brand.brand_id,
        banner_brand.id as p_id,
        banner_brand.banner_id,
        brand.name')
            ->join('brand','brand.id = banner_brand.brand_id')
            ->where('banner_brand.banner_id',$banner_id)->get('banner_brand')->result_array();
        foreach($data as $row) {
            echo '<tr>
				<td>'.$row['name'].'</td>';
            echo '<td width="220"><a href="javascript:void(0);" onclick="bannerBrandDel('.$banner_id.','.$row['brand_id'].');" class="btn red"><i class="fa fa-trash"></i> Удалить</a></td>
			</tr>';
        }
    }

    function main_blocks_product($main_block_id,$product_id=0) {
        if (!empty($product_id)) {
            $ins=array('main_block_id'=>$main_block_id,'product_id'=>$product_id);
            $ch=$this->db->where('main_block_id',$main_block_id)->where('product_id',$product_id)->get('main_blocks_product')->row_array();
            if (empty($ch)) {
                $this->db->insert('main_blocks_product',$ins);
            }
        }
        $data = $this->db->select('main_blocks_product.product_id, product.name_ru')
            ->join('product','product.id = main_blocks_product.product_id')
            ->where('main_blocks_product.main_block_id', $main_block_id)->get('main_blocks_product')->result_array();
        foreach($data as $row) {
            echo '<tr>
				<td>'.$row['name_ru'].'</td>';
            echo '<td style="width: 100px;"><a href="javascript:void(0);" onclick="delProd('.$main_block_id.','.$row['product_id'].');" class="btn red"><i class="fa fa-trash"></i> Удалить</a></td>
			</tr>';
        }
    }

    function main_blocks_product_del($main_block_id,$product_id=0) {
        if (!empty($product_id)) {
            $this->db->where('main_block_id',$main_block_id)->where('product_id',$product_id)->delete('main_blocks_product');
        }
        $data = $this->db->select('main_blocks_product.product_id, product.name_ru')
            ->join('product','product.id = main_blocks_product.product_id')
            ->where('main_blocks_product.main_block_id', $main_block_id)->get('main_blocks_product')->result_array();
        foreach($data as $row) {
            echo '<tr>
				<td>'.$row['name_ru'].'</td>';
            echo '<td style="width: 100px;"><a href="javascript:void(0);" onclick="delProd('.$main_block_id.','.$row['product_id'].');" class="btn red"><i class="fa fa-trash"></i> Удалить</a></td>
			</tr>';
        }
    }

    function bestseller_product($bestseller_id,$product_id=0) {
        if (!empty($product_id)) {
            $ins=array('bestseller_id'=>$bestseller_id,'product_id'=>$product_id);
            $ch=$this->db->where('bestseller_id',$bestseller_id)->where('product_id',$product_id)->get('bestseller_product')->row_array();
            if (empty($ch)) {
                $this->db->insert('bestseller_product',$ins);
            }
        }
        $data = $this->db->select('bestseller_product.product_id, product.name_ru')
            ->join('product','product.id = bestseller_product.product_id')
            ->where('bestseller_product.bestseller_id', $bestseller_id)->get('bestseller_product')->result_array();
        foreach($data as $row) {
            echo '<tr>
				<td>'.$row['name_ru'].'</td>';
            echo '<td style="width: 100px;"><a href="javascript:void(0);" onclick="delProd('.$bestseller_id.','.$row['product_id'].');" class="btn red"><i class="fa fa-trash"></i> Удалить</a></td>
			</tr>';
        }
    }

    function bestseller_product_del($bestseller_id,$product_id=0) {
        if (!empty($product_id)) {
            $this->db->where('bestseller_id',$bestseller_id)->where('product_id',$product_id)->delete('bestseller_product');
        }
        $data = $this->db->select('bestseller_product.product_id, product.name_ru')
            ->join('product','product.id = bestseller_product.product_id')
            ->where('bestseller_product.bestseller_id', $bestseller_id)->get('bestseller_product')->result_array();
        foreach($data as $row) {
            echo '<tr>
				<td>'.$row['name_ru'].'</td>';
            echo '<td style="width: 100px;"><a href="javascript:void(0);" onclick="delProd('.$bestseller_id.','.$row['product_id'].');" class="btn red"><i class="fa fa-trash"></i> Удалить</a></td>
			</tr>';
        }
    }

    function promotion_product($promotion_id,$product_id=0) {
        if (!empty($product_id)) {
            $ins=array('promotion_id'=>$promotion_id,'product_id'=>$product_id);
            $ch=$this->db->where('promotion_id',$promotion_id)->where('product_id',$product_id)->get('promotion_product')->row_array();
            if (empty($ch)) {
                $this->db->insert('promotion_product',$ins);
            }
        }
        $data = $this->db->select('promotion_product.product_id, product.name_ru')
            ->join('product','product.id = promotion_product.product_id')
            ->where('promotion_product.promotion_id', $promotion_id)->get('promotion_product')->result_array();
        foreach($data as $row) {
            echo '<tr>
				<td>'.$row['name_ru'].'</td>';
            echo '<td style="width: 100px;"><a href="javascript:void(0);" onclick="delProd('.$promotion_id.','.$row['product_id'].');" class="btn red"><i class="fa fa-trash"></i> Удалить</a></td>
			</tr>';
        }
    }

    function promotion_product_del($promotion_id,$product_id=0) {
        if (!empty($product_id)) {
            $this->db->where('promotion_id',$promotion_id)->where('product_id',$product_id)->delete('promotion_product');
        }
        $data = $this->db->select('promotion_product.product_id, product.name_ru')
            ->join('product','product.id = promotion_product.product_id')
            ->where('promotion_product.promotion_id', $promotion_id)->get('promotion_product')->result_array();
        foreach($data as $row) {
            echo '<tr>
				<td>'.$row['name_ru'].'</td>';
            echo '<td style="width: 100px;"><a href="javascript:void(0);" onclick="delProd('.$promotion_id.','.$row['product_id'].');" class="btn red"><i class="fa fa-trash"></i> Удалить</a></td>
			</tr>';
        }
    }

    function news_product($news_id,$product_id=0) {
        if (!empty($product_id)) {
            $ins=array('news_id'=>$news_id,'product_id'=>$product_id);
            $ch=$this->db->where('news_id',$news_id)->where('product_id',$product_id)->get('news_product')->row_array();
            if (empty($ch)) {
                $this->db->insert('news_product',$ins);
            }
        }
        $data = $this->db->select('news_product.product_id, product.name_ru')
            ->join('product','product.id = news_product.product_id')
            ->where('news_product.news_id', $news_id)->get('news_product')->result_array();
        foreach($data as $row) {
            echo '<tr>
				<td>'.$row['name_ru'].'</td>';
            echo '<td style="width: 100px;"><a href="javascript:void(0);" onclick="delProd('.$news_id.','.$row['product_id'].');" class="btn red"><i class="fa fa-trash"></i> Удалить</a></td>
			</tr>';
        }
    }

    function news_product_del($news_id,$product_id=0) {
        if (!empty($product_id)) {
            $this->db->where('news_id',$news_id)->where('product_id',$product_id)->delete('news_product');
        }
        $data = $this->db->select('news_product.product_id, product.name_ru')
            ->join('product','product.id = news_product.product_id')
            ->where('news_product.news_id', $news_id)->get('news_product')->result_array();
        foreach($data as $row) {
            echo '<tr>
				<td>'.$row['name_ru'].'</td>';
            echo '<td style="width: 100px;"><a href="javascript:void(0);" onclick="delProd('.$news_id.','.$row['product_id'].');" class="btn red"><i class="fa fa-trash"></i> Удалить</a></td>
			</tr>';
        }
    }

    function shop_product($shop_id,$product_id=0) {
        if (!empty($product_id)) {
            $ins=array('shop_id'=>$shop_id,'product_id'=>$product_id);
            $ch=$this->db->where('shop_id',$shop_id)->where('product_id',$product_id)->get('shop_product')->row_array();
            if (empty($ch)) {
                $this->db->insert('shop_product',$ins);
            }
        }
        $data = $this->db->select('shop_product.product_id, shop_product.best, product.name_ru')
            ->join('product','product.id = shop_product.product_id')
            ->where('shop_product.shop_id', $shop_id)->order_by('shop_product.sorder asc')->get('shop_product')->result_array();
        foreach($data as $row) {
            $check = ($row['best'] == 1) ? "checked" : "";
            echo '<tr id="products[]_'.$row['product_id'].'">
                <td style="text-align: center;vertical-align: middle;cursor: grab;"><i class="fa fa-sort"></i></td>
				<td>'.$row['name_ru'].'</td>
                <td>
                    <input id="'.$shop_id.'-'.$row['product_id'].'" type="checkbox" '.$check.' onchange="setBest('.$shop_id.','.$row['product_id'].')">
                    <label for="'.$shop_id.'-'.$row['product_id'].'"> Best</label></td>';
            echo '<td style="width: 100px;"><a href="javascript:void(0);" onclick="delProd('.$shop_id.','.$row['product_id'].');" class="btn red"><i class="fa fa-trash"></i> Удалить</a></td>
			</tr>';
        }
    }

    function shop_product_del($shop_id,$product_id=0) {
        if (!empty($product_id)) {
            $this->db->where('shop_id',$shop_id)->where('product_id',$product_id)->delete('shop_product');
        }
        $data = $this->db->select('shop_product.product_id, shop_product.best, product.name_ru')
            ->join('product','product.id = shop_product.product_id')
            ->where('shop_product.shop_id', $shop_id)->order_by('shop_product.sorder asc')->get('shop_product')->result_array();
        foreach($data as $row) {
            $check = ($row['best'] == 1) ? "checked" : "";
            echo '<tr id="products[]_'.$row['product_id'].'">
                <td style="text-align: center;vertical-align: middle;cursor: grab;"><i class="fa fa-sort"></i></td>
				<td>'.$row['name_ru'].'</td>
				<td>
                    <input id="'.$shop_id.'-'.$row['product_id'].'" type="checkbox" '.$check.' onchange="setBest('.$shop_id.','.$row['product_id'].')">
                    <label for="'.$shop_id.'-'.$row['product_id'].'"> Best</label></td>';
            echo '<td style="width: 100px;"><a href="javascript:void(0);" onclick="delProd('.$shop_id.','.$row['product_id'].');" class="btn red"><i class="fa fa-trash"></i> Удалить</a></td>
			</tr>';
        }
    }

    function shop_product_sort($id) {
        $products = $this->input->post('products', true);

        if(!empty($products) and is_array($products)) {
            foreach ($products as $sorder => $product) {
                $this->db->where("shop_id", $id)->where("product_id", $product)->update("shop_product", ['sorder' => $sorder]);
            }
        }
    }

    function setBest() {
        $shop_id=$this->uri->segment(3);
        $product_id=$this->uri->segment(4);
        $val=$this->uri->segment(5);
        $this->db->where('shop_id',$shop_id)->where('product_id', $product_id)->update('shop_product',array('best'=>$val));
    }

    function article_product($article_id,$product_id=0) {
        if (!empty($product_id)) {
            $ins=array('article_id'=>$article_id,'product_id'=>$product_id);
            $ch=$this->db->where('article_id',$article_id)->where('product_id',$product_id)->get('article_product')->row_array();
            if (empty($ch)) {
                $this->db->insert('article_product',$ins);
            }
        }
        $data = $this->db->select('article_product.product_id, product.name_ru')
            ->join('product','product.id = article_product.product_id')
            ->where('article_product.article_id', $article_id)->get('article_product')->result_array();
        foreach($data as $row) {
            echo '<tr>
				<td>'.$row['name_ru'].'</td>';
            echo '<td style="width: 100px;"><a href="javascript:void(0);" onclick="delProd('.$article_id.','.$row['product_id'].');" class="btn red"><i class="fa fa-trash"></i> Удалить</a></td>
			</tr>';
        }
    }

    function article_product_del($article_id,$product_id=0) {
        if (!empty($product_id)) {
            $this->db->where('article_id',$article_id)->where('product_id',$product_id)->delete('article_product');
        }
        $data = $this->db->select('article_product.product_id, product.name_ru')
            ->join('product','product.id = article_product.product_id')
            ->where('article_product.article_id', $article_id)->get('article_product')->result_array();
        foreach($data as $row) {
            echo '<tr>
				<td>'.$row['name_ru'].'</td>';
            echo '<td style="width: 100px;"><a href="javascript:void(0);" onclick="delProd('.$article_id.','.$row['product_id'].');" class="btn red"><i class="fa fa-trash"></i> Удалить</a></td>
			</tr>';
        }
    }

    function catsort($category_id,$value) {
        $this->db->where('id',$category_id)->update('category',array('sorder'=>$value));
    }

    function vacancy() {
        $main_name='vacancy';
        $this->load->view('layouts/cp_layout',array('inner_view'=>ADM_CONTROLLER."/".$main_name));
    }

    function header_options() {
        $main_name='header_options';
        init_load_img($main_name);
        $this->load->view('layouts/cp_layout', array('inner_view' => ADM_CONTROLLER."/".$main_name));
    }

    function crm($id) {
        if($id =='download') {

            $sdr=$_GET['sd'].' 00:00:00';
            $edr=$_GET['ed'].' 23:59:59';

            $this->load->library('excel');
            $this->excel->createTerminalExcelFile($sdr, $edr);
        } else {
            $main_name = 'crm';
            init_load_img($main_name);
            $this->load->view('layouts/cp_layout', array('inner_view' => ADM_CONTROLLER."/" . $main_name . '/' . $id));
        }
    }

    function terminals() {
        $main_name='terminals';
        init_load_img($main_name);
        $this->load->view('layouts/cp_layout', array('inner_view' => ADM_CONTROLLER."/".$main_name));
    }

    function terminal_users() {
        $main_name='terminal_users';
        init_load_img($main_name);
        $this->load->view('layouts/cp_layout', array('inner_view' => ADM_CONTROLLER."/".$main_name));
    }

    function cashback($id) {
        require_once realpath('public/payment').'/'.'Transaction.php';
        $lang = $this->config->item('current_lang');
        $uri3 = intval($this->uri->segment(3));

        $num=900000000+intval($uri3);

        $order = $this->db->where('id',$uri3)->get('orders')->row_array();
        if (empty($order)) {
            show_404();
            die();
        }

        $refund_amount = (!empty($_GET['q'])) ? intval($_GET['q']) : 0;

        $merchant='02ECM024';

        date_default_timezone_set('UTC');

        if (empty($order['rrn_id']) || empty($order['transaction_id'])) {
            show_404();
            die();
        }

        $total = $order['total']+$order['delivery_amount'];

        if ($refund_amount <= 0 || $refund_amount > $total) {
            show_404();
            die();
        }

        if ($order['refund_amount']) {
            echo 'По данному заказу уже запросили возврат денежных средств. Ожидайте ответа из банка';
            die();
        }

        $this->db->where('id', $uri3)->update('orders', array('refund_amount' => $refund_amount));

        $order['refund_amount'] = $refund_amount;

        $data = array();
        $data['TRTYPE'] = 24;
        $data['TERMINAL'] ='92409023';
        $data['ORDER'] = $num;
        $data['CURRENCY'] = 'MDL';
        $data['AMOUNT'] = number_format($refund_amount,2,'.','');
        $data['TIMESTAMP'] = date('YmdHis', time());
        $data['NONCE'] = Transaction::set_nonce();
        $data['RRN'] = $order['rrn_id'];
        $data['INT_REF'] = $order['transaction_id'];

        $rsaPrivKey   = Transaction::_get_key(realpath('public/payment') . "/ssl/private/privkey_prod.pem");
        $rsaPubKey    = Transaction::get_key(realpath('public/payment') . "/ssl/cert_prod.pem");

        $rsaKeyLength = Transaction::get_key_length($rsaPrivKey);

        $hashedData   = Transaction::_create_hased_data( $rsaKeyLength ,$data );
        $macsource    = Transaction::_generate_mac_source( $data );
        $p_sign       = Transaction::_encrypt_p_sign( $rsaPrivKey , $hashedData );

        $data['P_SIGN'] = $p_sign;
        $data['ORG_AMOUNT'] = number_format($total,2,'.','');

        $postData = http_build_query($data);
        $url = MICB_URI;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_POST, count($postData));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        $output = curl_exec($ch);
        curl_close($ch);


        echo "
        <script type=\"text/javascript\">
            window.location.href = 'https://bomba.md/".ADM_CONTROLLER."/orders/" . $order['id'] . "';
        </script>";
        exit();
    }

    public function search_by_sku(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $term = $_POST['term'];
            $items = $this->db->like('sku', $term, 'after')->or_like('articol', $term, 'after')->limit(10)->get('product')->result();
            foreach($items as $item){
                $result[] = array('value' => $item->id, 'label' => $item->name_ru, 'bar_code' => $item->sku);
            }
            echo json_encode($result);
        }
    }

    public function get_products_blocks(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $product_id = $_POST['product_id'];
            $blocks = $this->db->where('product_id', $product_id)->get('product_block')->result();
            if(!empty($blocks)){
                echo json_encode($blocks);
            }
        }
    }

    public function delete_page_block(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $product_id = $_POST['product_id'];
            if($this->db->where('product_id', $product_id)->delete('product_block')){
                echo 0;
            } else {
                echo 1;
            }
        }
    }

    public function delete_page_block_img(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $id = $_POST['id'];
            if($this->db->where('id', $id)->update('product_block', array('img'=>''))){
                echo 0;
            } else {
                echo 1;
            }
        }
    }

    public function sorder_page_block(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $id = $_POST['id'];
            $value = $_POST['value'];

            if($this->db->where('id', $id)->update('product_block', array('sorder'=>$value))){
                echo 0;
            } else {
                echo 1;
            }
        }
    }

    public function delete_product_block(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $id = $_POST['id'];
            if($this->db->where('id', $id)->delete('product_block')){
                echo 0;
            } else {
                echo 1;
            }
        }
    }

    public function delete_image_thumbs() {
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $articol = $this->input->post('articol', true);

            $dopa = substr($articol, -2);
            $dir = "/home/bombamd/public_html/public/products/" . $dopa . "/" . $articol . "/NO_COLOR";

            remove_dir($dir."/thumbs");

            $_SESSION['delete_thumbs_response'] = "Удачно удалено";
        }
        $this->load->view('layouts/cp_layout', array('inner_view' => ADM_CONTROLLER.'/delete_image_thumbs'));
    }

    public function uds_reward() {
        $id = $this->input->post('id', true);

        $result = curl_post('uds/reward', ['id' => $id], true);

        $message = '';
        $status = '';
        switch ($result['message']) {
            case 'ADD_POINTS_STATUS__ERROR' :
                $message = 'Произошла ошибка, попробуйте еще раз';
                $status = 'fail';
                break;
            case 'ADD_POINTS_STATUS__NOT_UDS_ORDER' :
                $message = '';
                $status = 'ok';
                break;
            case 'ADD_POINTS_STATUS__POINTS_ALREADY_ADDED':
                $message = 'Бонусы уже были добавлены';
                $status = 'ok';
                break;
            case 'ADD_POINTS_STATUS__NO_BONUSES' :
                $message = 'Бонусы по заказу - ноль';
                $status = 'ok';
                break;
            case 'ADD_POINTS_STATUS__SUCCESS' :
                $message = 'Зачислен бонус в размере ' . $result['cashback'] . ' баллов';
                $status = 'ok';
                break;
        }

        $response = [
            'message' => $message,
            'status' => $status
        ];

        echo json_encode($response);
    }

    public function uds_refund() {
        $response['status'] = 'ok';
        $response['message'] = 'success';
        $id = $this->input->post('id', true);
        $result = $this->db->where("id", $id)->get("orders")->row_array();
        if($result && !empty($result['uds_response'])) {
            $uds_response = json_decode($result['uds_response'], true);
            if(!isset($uds_response['errorCode']) && !empty($uds_response['state']) && $uds_response['state'] != 'REVERSAL' ) {
                $decode_data = curl_post('uds/refund', ['id' => $uds_response['id'], 'total' => $uds_response['total']], true);
                if (isset($decode_data['id']) && $decode_data['state'] == 'REVERSAL') {
                    $to_update = [
                        'uds_response' => json_encode($decode_data),
                    ];
                    $this->db->where('id', $id)->update('orders', $to_update);
                } else {
                    $response['status'] = 'fail';
                    $response['message'] = 'fail';
                }
            }
        }

        echo json_encode($response);
    }
}
