<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Uds_blocks_model extends BaseModel
{
    protected $tblname = 'uds_blocks';

    public function __construct()
    {
        parent::__construct();
    }

    public function get_blocks($lang)
    {
        if (empty($lang)) return false;

        $this->db->select("
            id as id,
            block_id as block_id,
            title_$lang as title,
            text_$lang as text,
            img as img,
        ");
        $this->db->order_by('sorder ASC, id DESC');
        return $this->db->get($this->tblname)->result();
    }
}
