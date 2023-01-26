<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Shop_categories_model extends BaseModel
{
    protected $tblname = 'shop_categories';

    public function get_shop_categories($lang, $shop_id)
    {
        if (empty($lang) or empty($shop_id)) return false;

        $this->db->select("
			id as id,
			name as title,
			url_$lang as uri,
			image_$lang as image,
        ");
        $this->db->where("shop_id", $shop_id);
        $this->db->order_by("sorder asc, id desc");
        return $this->db->get($this->tblname)->result();
    }
}
