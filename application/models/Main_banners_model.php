<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main_banners_model extends BaseModel
{
    protected $tblname = 'main_banners';

    public function __construct()
    {
        parent::__construct();
    }

    public function get_banners($lang)
    {
        if (empty($lang)) return false;

        return $this->db->select("
            text1_$lang as title,
            link_$lang as uri,
            image_$lang as img,
            image_mobile_$lang as mobile_img,
            position as position,
        ")
            ->order_by('sorder asc,id desc')
            ->get($this->tblname)->result();
    }
}
