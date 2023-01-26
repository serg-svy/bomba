<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Contacts_model extends BaseModel
{
    protected $tblname = 'contacts';

    public function __construct()
    {
        parent::__construct();
    }

    public function get_contacts($lang)
    {
        if (empty($lang)) return false;

        return $this->db->select("
			id as id,
            title_$lang as title,
			desc_$lang as desc,
        ")
            ->order_by('sorder asc,id desc')
            ->get($this->tblname)->result();
    }
}
