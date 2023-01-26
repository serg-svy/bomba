<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class About_images_model extends BaseModel
{
    protected $tblname = 'about_images';

    public function __construct()
    {
        parent::__construct();
    }

    public function get_images()
    {
        $this->db->select("
			id as id,
            image as img,
        ");
        $this->db->order_by("sorder asc, id desc");
        return $this->db->get($this->tblname)->result();
    }
}
