<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bottom_category_model extends BaseModel
{
    protected $tblname = 'bottom_category';

    public function __construct()
    {
        parent::__construct();
    }

    public function get_categories($lang)
    {
        if (empty($lang)) return false;

        $bottom_categories = [];

        $this->db->select("
            id as id,
            name_$lang as title,
        ");
        $this->db->order_by('sorder ASC, id DESC');
        $bottom_categories_result = $this->db->get($this->tblname)->result();

        foreach($bottom_categories_result as $key=>$row) {
            $bottom_categories[$row->id] = $row;
        }

        return $bottom_categories;
    }
}
