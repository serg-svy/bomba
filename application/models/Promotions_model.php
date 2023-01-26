<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Promotions_model extends BaseModel
{
    protected $tblname = 'promotions';

    public function __construct()
    {
        parent::__construct();
    }

    public function get_promotions($lang, $start, $category_id, $limit)
    {
        if (empty($lang)) return false;

        $this->db->select("
			id as id,
			category_id as category_id,
            url as uri,
			end_date as end_date,
            name_$lang as title,
			announce_$lang as announce,
			image_list_$lang as image_list,
        ");
        if($category_id) $this->db->where("category_id", $category_id);
        $this->db->order_by("end_date desc, id desc");
        $this->db->limit($limit, $start);
        return $this->db->get($this->tblname)->result();
    }

    public function get_promotions_count($category_id) {
        if($category_id) $this->db->where("category_id", $category_id);
        $result = $this->db->get($this->tblname);
        return $result->num_rows();
    }

    public function get_promotion_by_uri($lang = false, $uri = false) {
        if (empty($lang) || empty($uri)) return false;

        $this->db->select("
            id as id,
            url as uri,
			end_date as end_date,
            name_$lang as title,
			announce_$lang as announce,
			text_$lang as text,
			seo_title_$lang as seo_title,
			seo_kw_$lang as seo_keywords,
			seo_desc_$lang as seo_desc,
			image_list_$lang as image_list,
			image_header_left_$lang as image_header_left,
			image_header_right_$lang as image_header_right,
        ");
        $this->db->where("url", $uri);
        return $this->db->get($this->tblname)->row();
    }

}
