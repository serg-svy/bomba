<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Promotion_category_model extends BaseModel
{
    protected $tblname = 'promotion_category';

    public function __construct()
    {
        parent::__construct();
    }

    public function get_categories($lang)
    {
        if (empty($lang)) return false;

        $this->db->select("
			$this->tblname.id as id,
            $this->tblname.name_$lang as title,
			$this->tblname.img as img,
			count(promotions.id) as count,
        ");
        $this->db->join("promotions", "promotions.category_id = promotion_category.id");
        $this->db->order_by("$this->tblname.sorder desc, $this->tblname.id desc");
        $this->db->group_by("$this->tblname.id");
        return $this->db->get($this->tblname)->result();
    }

}
