<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main_blocks_model extends BaseModel
{
    protected $tblname = 'main_blocks';

    public function __construct()
    {
        parent::__construct();
    }

    public function get_blocks($lang)
    {
        if (empty($lang)) return false;

        return $this->db->select("
            $this->tblname.id as id,
            $this->tblname.name_$lang as title,
            $this->tblname.url_$lang as uri,
            $this->tblname.url_name_$lang as uri_title,
            $this->tblname.image as img,
            $this->tblname.image_mobile as mobile_img,
            $this->tblname.position as position,
        ")
            ->get($this->tblname)->result();
    }
}
