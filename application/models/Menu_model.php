<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Menu_model extends BaseModel
{
    protected $tblname = 'menu';

    public function __construct()
    {
        parent::__construct();
    }

    public function get_page_data($lang = false, $uri = false)
    {
        if (empty($uri) || empty($lang)) {
            return false;
        }

        $this->db->select("
            id as id,
            bottom_category_id as bottom_category_id,
            title_$lang as title,
            text_$lang as text,
            seo_title_$lang as seo_title,
            seo_kw_$lang as seo_keywords,
            seo_desc_$lang as seo_desc,
            url as uri,
            youtube as youtube,
        ");
        $this->db->where("url", $uri);
        return $this->db->get($this->tblname)->row();
    }

    public function get_page_data_by_id($lang = false, $id = false)
    {
        if (empty($lang) || empty($id)) {
            return false;
        }

        $id = (int) $id;

        $this->db->select("
            id as id,
            bottom_category_id as bottom_category_id,
            title_$lang as title,
            text_$lang as text,
            seo_title_$lang as seo_title,
            seo_kw_$lang as seo_keywords,
            seo_desc_$lang as seo_desc,
            url as uri,
            youtube as youtube,
        ");
        $this->db->where('id', $id);
        return $this->db->get($this->tblname)->row();
    }

    public function get_menu($lang)
    {
        if (empty($lang)) return false;

        $this->db->select("
            id as id,
            bottom_category_id as bottom_category_id,
            title_$lang as title,
            url as uri,
            alternative_url as alternative_uri,
            is_top as onTop,
            is_bottom as onBottom,
            order_top as order_top,
            order_bottom as order_bottom,
            icon as icon,
        ");
        $data = $this->db->get($this->tblname)->result();

        $arr = array();
        $response = array();
        $response['top'] = array();
        $response['bottom'] = array();
        $response['all'] = array();
        if (!empty($data)) {
            foreach ($data as $key => $value) {
                $arr[$value->id] = $value;
            }
            $data = $arr;
            foreach ($data as $key => $value) {
                if ($value->onTop == 1) $response['top'][$value->order_top] = $data[$key];
            }
            foreach ($data as $key => $value) {
                if ($value->onBottom == 1) $response['bottom'][$value->order_bottom] = $data[$key];
            }
        }

        ksort($response['top']);
        ksort($response['bottom']);

        $response['all'] = $data;

        return $response;
    }
}
