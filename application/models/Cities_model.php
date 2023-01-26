<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class cities_model extends BaseModel
{
    protected $tblname = 'city';

    public function __construct()
    {
        parent::__construct();
        $this->load->driver('cache', array('adapter' => 'apc', 'backup' => 'file'));
    }

    public function get($lang) {

        $cities = [];

        if (!$cities_result = $this->cache->get('cities_result_'.$lang)) {
            $cities_result = $this->db->select(
                "id,
                 name_$lang AS title,
                 declension_$lang AS declension,
                 textCourier_$lang AS text_courier,
                 textPickup_$lang AS text_pickup,
                 coords as coords,
             ")
                ->order_by('title ASC')
                ->get($this->tblname)
                ->result();

            $this->cache->save('cities_result_'.$lang, $cities_result, 60 * 60 * 60);
        }

        foreach($cities_result as $key=>$row) {
            $cities[$row->id] = $row;
        }

        return $cities;
    }

    public function autocomplete($lang, $match) {
        if(empty($lang)) return [];

            $this->db->select("name_$lang as title, id as id, region_id as region_id");
            if($match) {
                $this->db->group_start()
                    ->like("name_ru", $match)
                    ->or_like("name_ro", $match)
                    ->or_like("parent_name", $match)
                    ->group_end();
                $this->db->where("id !=", 1);
                $this->db->order_by("title asc");
            } else {
                $this->db->where("on_popup", 1);
                $this->db->order_by("sorder asc");
            }

            return $this->db->limit(10)
            ->get($this->tblname)->result();
    }

    function get_cities_for_pickup($lang, $id=false) {
        if(empty($lang)) return [];

        $this->db->select("
			city.id as id,
			city.name_$lang as title,
			city.coords as coords,
		")
            ->join("store", "store.city_id = city.id", "inner")
            ->group_by("city.id");
        if($id) $this->db->where("city.id", $id);
        $this->db->order_by('city.sorder ASC, city.id DESC');
        return $this->db->get($this->tblname)->result();
    }

    function get_city_by_id($lang, $id) {
        if(empty($lang) || empty($id)) return [];

        $this->db->select("
			city.id as id,
			city.name_$lang as title, 
			city.region_id as region_id,
			city.slots as slots,
		")
            ->join("store", "store.city_id = city.id", "inner")
            ->where('city.id', $id);
        return $this->db->get($this->tblname)->row();
    }
}
