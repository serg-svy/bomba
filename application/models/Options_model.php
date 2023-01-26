<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Options_model extends BaseModel
{
    protected $tblname = 'header_options';

    public function __construct()
    {
        parent::__construct();
    }

    public function get_options()
    {
        return $this->db->get($this->tblname)->result();
    }
}
