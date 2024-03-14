<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_upload_data_text extends CI_Model{

    public function get_file(){
        
        // $id = $this->session->userdata('id');
        // $this->db->select('*');
        // $this->db->where('created_by',$id);
        // $query = $this->db->get('mpm.user')->result();
        $query = $this->db->get('test_it.upload_dt')->result();
        return $query;
    }

}

?>

