<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Category_banners_model extends BaseModel
{
    protected $tblname = 'category_banners';

    public function __construct()
    {
        parent::__construct();
    }

    public function get_banners_for_category($lang, $category_id, $type_id){
        if(empty($category_id) || empty($type_id)) return false;

        return $this->db
            ->select("link_$lang as link, image_$lang as img")
            ->join("banner_category","banner_category.banner_id = $this->tblname.id")
            ->where('type_id',$type_id)
            ->where('category_id',$category_id)
            ->order_by('banner_category.id desc')
            ->get($this->tblname)->result();
    }
}
