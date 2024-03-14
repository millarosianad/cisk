<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_generate extends CI_Model 
{
    public function all(){
        //query semua record di table products
        $hasil = $this->db->get('user');
        if ($hasil->num_rows() > 0) {
            return $hasil->result();
        } else {
            return array();
        }
    }

    public function getfi()
    {
        $sql = "select * from data2019.fi where bulan = 11 and kode_comp = 'smg'";
        $proses = $this->db->query($sql)->result();

        // echo "<pre>";
        // var_dump($sql);
        // echo "</pre>";

        if ($proses) {
            return $proses;
        }else{
            return array();
        }        
    }




}