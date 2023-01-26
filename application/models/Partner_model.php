<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Partner_model extends BaseModel
{
    protected $tblname = 'partner';

    public function __construct()
    {
        parent::__construct();

        $this->load->driver('cache', array('adapter' => 'apc', 'backup' => 'file'));
    }

    public function get_credit() {
        if (!$credit_partner_ids = $this->cache->get('credit_partner_ids')) {

            $query = $this->db->where('credit', 1)->get($this->tblname)->result();

            $credit_partner_ids = array_map(function ($item){return $item->id;},$query);

            $this->cache->save('credit_partner_ids', $credit_partner_ids, 60 * 60 * 6);
        }

        return $credit_partner_ids;
    }
}
