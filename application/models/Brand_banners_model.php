<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Brand_banners_model extends BaseModel
{
    protected $tblname = 'brand_banners';

    public function __construct()
    {
        parent::__construct();
    }

    public function get_banners_for_brand($lang, $brand_id, $type_id){
        if(empty($brand_id) || empty($type_id)) return false;

        return $this->db
            ->select("link_$lang as link, image_$lang as img")
            ->join("banner_brand","banner_brand.banner_id = $this->tblname.id")
            ->where('type_id',$type_id)
            ->where('brand_id',$brand_id)
            ->order_by('banner_brand.id desc')
            ->get($this->tblname)->result();
    }
}
