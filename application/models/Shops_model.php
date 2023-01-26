<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Shops_model extends BaseModel
{
    protected $tblname = 'shops';

    public function get_main_shops($lang)
    {
        if (empty($lang)) {
            return false;
        }

        $this->db->select("
			id as id,
			name_$lang as title,
			desc_$lang as desc,
			logo as logo,
			items as items,
            mobile_items as mobile_items,
        ");
        $this->db->where("on_main", 1);
        $this->db->order_by("sorder asc, id desc");
        return $this->db->get($this->tblname)->result();
    }

    public function get_shop_by_id($lang, $id)
    {
        if (empty($lang) or empty($id)) return false;

        $this->db->select("
			id as id,
			name_$lang as title,
			desc_$lang as desc,
			logo as logo,
			items as items,
			mobile_items as mobile_items,
			banner_$lang as banner,
			mobile_banner_$lang as mobile_banner,
        ");
        $this->db->where("id", $id);
        return $this->db->get($this->tblname)->row();
    }
}
