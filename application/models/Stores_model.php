<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stores_model extends BaseModel
{
    protected $tblname = 'store';

    public function __construct()
    {
        parent::__construct();
    }

    public function get_pickup_stores($lang, $id = false)
    {
        if (empty($lang)) return false;

        $this->db->select("
			store.id as id,
			store.city_id as city_id,
			store.name_$lang as title, 
			store.address_$lang as address,
			store.workhours_$lang as workhours,
			store.troleibus_$lang as troleibus,
			store.parking_$lang as parking,
			store.coords as coords,
			store.phone as phone,
		");
        $this->db->where("coords !=", "");
        $this->db->where("pickup", 1);
        if($id) $this->db->where("city_id", $id);
        return $this->db->get('store')->result();
    }

    public function get_by_id($lang, $id)
    {
        if (empty($lang)) return false;

        $this->db->select("
			store.id as id,
			store.city_id as city_id,
			store.name_$lang as title, 
			store.address_$lang as address,
			store.workhours_$lang as workhours,
			store.parking_$lang as parking,
			store.troleibus_$lang as troleibus,
			store.coords as coords,
			store.phone as phone,
		");
        $this->db->where("id", $id);
        $store = $this->db->get('store')->row();

        if($store) $store->images = $this->db->where("store_id", $id)->get("store_images")->result();

        return $store;
    }

    public function get_stores($lang, $id = false)
    {
        if (empty($lang)) return false;

        $this->db->select("
			store.id as id,
			store.city_id as city_id,
			store.name_$lang as title, 
			store.address_$lang as address,
			store.coords as coords,
			store.phone as phone,
		");
        $this->db->where("coords !=", "");
        if($id) $this->db->where("city_id", $id);
        return $this->db->get('store')->result();
    }

    public function get_main($lang)
    {
        if (empty($lang)) return false;

        $this->db->select("
			store.id as id,
			store.city_id as city_id,
			store.name_$lang as title, 
			store.address_$lang as address,
			store.workhours_$lang as workhours,
			store.parking_$lang as parking,
			store.troleibus_$lang as troleibus,
			store.coords as coords,
			store.phone as phone,
		");
        $this->db->where("id", 156702);
        return $this->db->get('store')->row();
    }

    public function get_all_stores($lang)
    {
        if (empty($lang)) return false;

        $stores = [];

        $this->db->select("
			store.id as id,
			city.name_$lang as city_name,
			store.name_$lang as title, 
			store.address_$lang as address,
		");
        $this->db->join("city", "city.id = store.city_id", "inner");
        $this->db->order_by("city.sorder asc, city.id desc, store.sorder asc, store.id desc");
        $result = $this->db->get('store')->result();

        foreach ($result as $row) {
            $stores[$row->id] = $row;
        }

        return $stores;
    }

    public function get_product_stores($lang, $product_id)
    {
        if (empty($lang) || empty($product_id)) return false;

        $stores = [];

        $this->db->select("
			store.id as id,
			city.name_$lang as city_name,
			store.name_$lang as title, 
			store.address_$lang as address,
			product_stock.quantity as quantity,
		");
        $this->db->join("city", "city.id = store.city_id", "inner");
        $this->db->join("product_stock", "product_stock.store_id = store.id", "inner");
        $this->db->where("store.pickup", 1);
        $this->db->where("product_stock.product_id", $product_id);
        $this->db->where("product_stock.quantity >", 0);
        $this->db->order_by("city.sorder asc, city.id desc, store.sorder asc, store.id desc");
        $result = $this->db->get('store')->result();

        foreach ($result as $row) {
            $stores[$row->id] = $row;
        }

        return $stores;
    }
}
