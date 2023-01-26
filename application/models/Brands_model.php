<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Brands_model extends BaseModel
{
    protected $tblname = 'brand';

    public function __construct()
    {
        parent::__construct();
    }

    public function get_popular()
    {
        $this->db->select("
            id as id,
            name as title,
        ");
        $this->db->where('is_popular', 1);
        $this->db->order_by('id ASC');

        return $this->db->get($this->tblname)->result();
    }

    function get_connected($ids) {
        if (empty($ids)) return false;

        return $this->db->select(
            'brand.name as title,
			brand.id as id,
			COUNT(DISTINCT(product.articol)) AS count'
        )
            ->from('product')
            ->join('brand','brand.id=product.brand_id','inner')
            ->where_in('product.id',$ids)
            ->group_by('brand.id')
            ->order_by('brand.name asc')
            ->get()
            ->result();
    }
}
