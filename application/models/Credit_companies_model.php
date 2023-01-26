<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Credit_companies_model extends BaseModel
{
    protected $tblname = 'credit_companies';

    public function __construct()
    {
        parent::__construct();
    }

    public function get_credit_companies($lang)
    {
        if (empty($lang)) return false;

        $this->db->select("
            id as id,      
            title_$lang as title,
            text_$lang as text,
            img as img,
        ");
        $this->db->order_by('sorder ASC, id DESC');
        return $this->db->get($this->tblname)->result();
    }
}
