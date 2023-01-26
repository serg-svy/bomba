<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Gift_model extends BaseModel
{
    protected $tblname = 'gift_cards';

    public function __construct()
    {
        parent::__construct();
    }

    public function get_cards($lang)
    {
        if (empty($lang)) return false;

        return $this->db->select("
            name_$lang as title,
            text_$lang as text,
            image_$lang as img,
        ")
            ->order_by('sorder asc,id desc')
            ->get($this->tblname)->result();
    }
}
