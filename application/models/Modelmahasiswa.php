<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Modelmahasiswa extends CI_Model
{
    protected $table = "upload";
    protected $primarykey = "id";

    public function get_upload(){
        $start = 0;
        $limit = 50;

        $this->db->limit($limit,$start);
        $this->db->order_by('urutan','asc');
        $proses = $this->db->get('mpm.tbl_tabcomp')->result();
        return $proses;
    }
}