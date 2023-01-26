<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Article_model extends BaseModel
{
    protected $tblname = 'article';

    public function __construct()
    {
        parent::__construct();
    }

    public function get_articles($lang = false, $start, $limit)
    {
        if (empty($lang)) {
            return false;
        }

        $this->db->select("
			id as id,
            url as uri,
            add_date add_date,
			name_$lang as title,
			announce_$lang as announce,
			image_list_$lang as image_list,
        ");
        $this->db->order_by("sorder asc, id desc");
        $this->db->limit($limit, $start);
        return $this->db->get($this->tblname)->result();
    }

    public function get_articles_count() {
        $result = $this->db->get($this->tblname);
        return $result->num_rows();
    }

    public function get_article_by_uri($lang = false, $uri = false) {
        if (empty($lang) || empty($uri)) return false;

        $this->db->select("
            id as id,
            url as uri,
            add_date add_date,
		    name_$lang as title,
		    text_$lang as text,
		    image_head_$lang as image_head,
		    image_list_$lang as image_list,
        ");
        $this->db->where("url", $uri);
        return $this->db->get($this->tblname)->row();
    }

    public function get_last_articles($lang, $id)
    {
        if (empty($lang) or empty($id)) return false;

        $this->db->select("
			id as id,
            url as uri,
			add_date add_date,
            name_$lang as title,
			announce_$lang as announce,
			image_list_$lang as image_list,
        ");
        $this->db->where('id <', $id);
        $this->db->limit(4);
        $this->db->order_by("add_date desc, id desc");
        return $this->db->get($this->tblname)->result();
    }

}
