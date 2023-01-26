<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Payment_type_model extends BaseModel
{
    protected $tblname = 'payment_type';

    public function __construct()
    {
        parent::__construct();
    }

    public function get_payment_for_site($lang) {
        if(empty($lang)) return false;

        return $this->db->select("id as id, name_$lang as title, image as img")->where('terminal', 0)->order_by("sorder asc")->get($this->tblname)->result();
    }

    public function get_payment_by_id($lang, $id) {
        if(empty($lang) or empty($id)) return false;

        return $this->db->select("id as id, name_$lang as title, image as img")->where('id', $id)->get($this->tblname)->row();
    }
}
