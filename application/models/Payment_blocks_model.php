<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Payment_blocks_model extends BaseModel
{
    protected $tblname = 'payment_blocks';

    public function __construct()
    {
        parent::__construct();
    }

    public function get_blocks($lang)
    {
        if (empty($lang)) return false;

        $this->db->select("
            id as id,
            name_$lang as title,
            desc_$lang as desc,
            uri_name_$lang as uri_name,
            uri_$lang as uri,
        ");
        $this->db->order_by('sorder ASC, id DESC');
        return $this->db->get($this->tblname)->result();
    }
}
