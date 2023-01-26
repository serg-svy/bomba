<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Departaments_model extends BaseModel
{
    protected $tblname = 'departments';

    public function __construct()
    {
        parent::__construct();
    }

    public function get_departments($lang)
    {
        if (empty($lang)) return false;

        return $this->db->select("
			id as id,
            header_$lang as title,
			worktime_$lang as worktime,
			short_worktime_$lang as short_worktime,
			phones as phones,
			email as email
        ")
            ->order_by('sorder asc,id desc')
            ->get($this->tblname)->result();
    }
}
