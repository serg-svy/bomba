<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Contacts extends FrontEndController
{
    private $page_id;

    public function __construct() {
        parent::__construct();
        $this->page_id = 7;
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

        if($_SERVER['REQUEST_METHOD'] == 'POST') {

            $post = $this->input->post('contact', true);

            $tx = '';
            $tx.='Имя: '.$post['name'].'<br>';
            $tx.='E-mail: '.$post['email'].'<br>';
            $tx.='Вопрос: '.$post['question'].'<br>';

            if(send_notification(ADMIN_EMAIL, 'Вопрос с сайта ' . $_SERVER['HTTP_HOST'], $tx)) {
                $_SESSION['SEND_FEEDBACK'] = true;
            }
        }

        $page = $this->menu_model->get_page_data_by_id($this->lclang, $this->page_id);
        if (empty($page)) throw_on_404();

        $this->breadcrumbs[] = $this->_generate_bc_data($page->title, $page->uri);

        foreach(language(true) as $lang){
            $array = $lang.'_urls';
            $this->{$array}[] = $page->uri;
        }

        $this->loadOGImgData($page);
        $this->_init_seo_data($page);

        $this->load->model("departaments_model");
        $result = $this->departaments_model->get_departments($this->lclang);
        $departaments = array_chunk($result , 4);

        $this->load->model("stores_model");
        $store = $this->stores_model->get_main($this->lclang);

        $this->load->model("contacts_model");
        $contacts = $this->contacts_model->get_contacts($this->lclang);

        $this->data['inner_view'] = 'pages/contacts/index';
        $this->data['page'] = $page;
        $this->data['departaments'] = $departaments;
        $this->data['contacts'] = $contacts;
        $this->data['store'] = $store;

        $this->_render();
    }
}
