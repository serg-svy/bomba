<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Vacancies_model extends BaseModel
{
    protected $tblname = 'vacancy';

    public function __construct()
    {
        parent::__construct();
    }

    public function get_vacancies($lang)
    {
        if (empty($lang)) return false;

        $response = $this->db->select("
            $this->tblname.id as id,
            $this->tblname.name_$lang as title,
            $this->tblname.short_name_$lang as short_title,
            $this->tblname.description_$lang as text,
        ")
            ->order_by("$this->tblname.sorder asc,$this->tblname.id desc")
            ->get($this->tblname)->result();

        foreach ($response as &$row) {
            $row->stores = $this->db->select("store.id as id, store.name_$lang as store_title, address_$lang as store_address, coords, city_id")
                ->join("store", "store.id = vacancy_store.store_id", "inner")
                ->where("vacancy_id", $row->id)->get("vacancy_store")->result();
        }

        return $response;
    }

    public function get_vacancy_by_id($lang, $id) {
        return $this->db->select("
            $this->tblname.id as id,
            $this->tblname.name_$lang as title,
            $this->tblname.short_name_$lang as short_title,
            $this->tblname.description_$lang as text,
        ")->where("id", $id)->get($this->tblname)->row();
    }
}
