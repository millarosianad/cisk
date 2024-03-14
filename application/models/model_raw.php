<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_raw extends CI_Model 
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

    public function getSuppbyid()
    {
        $supp=$this->session->userdata('supp');
        if($supp=='000')
        {
            return $this->db->query('select supp,namasupp from mpm.tabsupp');
        }
        else
        {
            return $this->db->query("select supp,namasupp from mpm.tabsupp where supp='$supp'");
        }
    } 

    public function proses_raw_data($datacontroller){

        $year = $datacontroller['year'];
        $supplier = $datacontroller['supplier'];
        $month = $datacontroller['month'];

        /*
        echo "<pre>";
        echo "year model : ".$year."<br>";
        echo "supplier model : ".$supplier."<br>";
        echo "month model : ".$month."<br>";
        echo "</pre>";
        */

        //cek sudah ada datanya atau belum
        $sql = "
            select * from mpm.tbl_raw_".$year." 
            where bulan = $month and supplier = '$supplier'
            limit 1
        ";
        
        echo "<pre>";
        print_r($sql);
        echo "Loading data ... Mohon Menunggu";
        echo "</pre>";
        
        
        $hasil = $this->db->query($sql);

        if ($hasil->num_rows() > 0) {
        
            //redirect('all_raw/export_raw_csv/'.$month.'/'.$year.'/'.'/'.$supplier, 'refresh');
            redirect('all_raw/export_raw_csv/'.$month.'/'.$year.'/'.$supplier, 'refresh');

        } else {
           echo '<script>alert("Data tidak ditemukan. Mohon hubungi IT !");</script>';
           redirect('all_raw/', 'refresh');
        }
        


           
    }

    public function proses_raw_data_mingguan($data){

        $year = $data['year'];
        $supplier = $data['supplier'];
        $month = $data['month'];
        $minggu = $data['minggu'];

        /*
        echo "<pre>";
        echo "year model : ".$year."<br>";
        echo "supplier model : ".$supplier."<br>";
        echo "month model : ".$month."<br>";
        echo "</pre>";
        */

        //cek sudah ada datanya atau belum
        $sql = "
            select * from mpm.tbl_raw_".$year."_minggu 
            where bulan = $month and supplier = '$supplier' and minggu = '$minggu'
            limit 1
        ";
        
        echo "<pre>";
        //print_r($sql);
        echo "Loading data ... Mohon Menunggu";
        echo "</pre>";
        
        
        $hasil = $this->db->query($sql);

        if ($hasil->num_rows() > 0) {
        
            //redirect('all_raw/export_raw_csv/'.$month.'/'.$year.'/'.'/'.$supplier, 'refresh');
            redirect('all_raw/export_raw_csv_minggu/'.$month.'/'.$year.'/'.$supplier.'/'.$minggu, 'refresh');

        } else {
           echo '<script>alert("Data tidak ditemukan. Mohon hubungi IT !");</script>';
           redirect('all_raw/', 'refresh');
        }
        


           
    }

    public function proses_pmu($datacontroller){

        $year = $datacontroller['year'];
        $supplier = $datacontroller['supplier'];
        $month = $datacontroller['month'];

        /*
        echo "<pre>";
        echo "year model : ".$year."<br>";
        echo "supplier model : ".$supplier."<br>";
        echo "month model : ".$month."<br>";
        echo "</pre>";
        */

        //cek sudah ada datanya atau belum
        $sql = "
            select * from mpm.tbl_raw_".$year." 
            where bulan = $month and supplier = $supplier
            limit 1
        ";
        
        echo "<pre>";
        //print_r($sql);
        echo "Loading data ... Mohon Menunggu";
        echo "</pre>";
        
        
        $hasil = $this->db->query($sql);

        if ($hasil->num_rows() > 0) {
        
            //redirect('all_raw/export_raw_csv/'.$month.'/'.$year.'/'.'/'.$supplier, 'refresh');
            redirect('all_raw/export_raw_csv/'.$month.'/'.$year.'/'.$supplier, 'refresh');

        } else {
           echo '<script>alert("Data tidak ditemukan. Mohon hubungi IT !");</script>';
           redirect('all_raw/', 'refresh');
        }
          
    }

    public function get_raw($supp)
    {
        if($supp = '000'){
            $sql = "select * from db_raw.tbl_list order by id desc";
        }else{
            $sql = "select * from db_raw.tbl_list where supp ='$supp'  order by id desc";
        }
        
        $proses = $this->db->query($sql)->result();

        // echo "<pre>";
        // print_r($sql);
        // echo "</pre>";
        if ($proses) {
            return $proses;
        }else{
            return array();
        }
    }



}