<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Vacancies extends FrontEndController
{
    private $page_id;

    public function __construct() {
        parent::__construct();
        $this->page_id = 29;
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

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $post = $this->input->post('vacancy', true);
            $files = $_FILES['vacancy'];
            $pizza = explode(".", $files['name']['file']);
            $attach = $_SERVER["DOCUMENT_ROOT"].'/public/uploads/'.rand().'.'.end($pizza);
            file_put_contents($attach, file_get_contents($files['tmp_name']['file']));

            $store = $this->db->select("name_$this->lclang as title, address_$this->lclang as address")->where("id", $post['store_id'])->get("store")->row();

            $this->load->model('vacancies_model');
            $vacancy = $this->vacancies_model->get_vacancy_by_id($this->lclang, $post['id']);

            $text = "Имя: ".$post['name']." ".$post['surname']."<br>";
            $text .= "Телефон: ".$post['phone']."<br>";
            $text .= "Магазин: ".$store->title.", ".$store->address."<br>";
            $text .= "Вакансия: ".$vacancy->title."<br>";

            $theme = 'Новый отзыв на вакансию на сайте '.$_SERVER['HTTP_HOST'];
            if(send_notification(ADMIN_EMAIL, $theme,$text, $attach)) {
                $_SESSION['vacancy_send'] = true;
            }

            unlink($attach);
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

        $this->load->model('vacancies_model');
        $vacancies = $this->vacancies_model->get_vacancies($this->lclang);

        foreach($vacancies as &$vacancy) {
            foreach($vacancy->stores as $store) {
                if(!isset($vacancy->cities[$store->city_id])) $vacancy->cities[$store->city_id] = (object) [];
                $vacancy->cities[$store->city_id]->id = $this->data['cities'][$store->city_id]->id;
                $vacancy->cities[$store->city_id]->title = $this->data['cities'][$store->city_id]->title;
                $vacancy->cities[$store->city_id]->coords = $this->data['cities'][$store->city_id]->coords;
                $vacancy->cities[$store->city_id]->count = (isset($vacancy->cities[$store->city_id]->count)) ? $vacancy->cities[$store->city_id]->count + 1 : 1;

                $pizza = explode(",", $store->coords);
                $array = ['lat' => $pizza[0], 'lng' => $pizza[1], 'id' => $store->id, 'title' => $store->store_title, 'address' => $store->store_address];
                $vacancy->cities[$store->city_id]->json[] = $array;
            }
        }

        $this->data['inner_view'] = 'pages/vacancies/index';
        $this->data['page'] = $page;
        $this->data['vacancies'] = $vacancies;

        $this->_render();
    }
}
