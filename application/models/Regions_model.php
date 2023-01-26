<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Regions_model extends BaseModel
{
    protected $tblname = 'regions';

    public function __construct()
    {
        parent::__construct();
        $this->load->driver('cache', array('adapter' => 'apc', 'backup' => 'file'));
    }

    public function get($lang) {

        $regions = [];

        if (!$regions_result = $this->cache->get('regions_result_'.$lang)) {
            $regions_result = $this->db->select(
                "id,
                 title$lang AS title,
             ")
                ->order_by('title ASC')
                ->get($this->tblname)
                ->result();

            $this->cache->save('regions_result_'.$lang, $regions_result, 60 * 60 * 60);
        }

        foreach($regions_result as $key=>$row) {
            $regions[$row->id] = $row;
        }

        return $regions;
    }
}
