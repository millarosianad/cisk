<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Micro extends MY_Controller
{
    function micro()
    {
        // set_time_limit(0);
        ini_set("memory_limit","-1");
        ini_set("max-execution_time", "-1");
        $this->load->library(array('table','template','Excel_generator', 'form_validation','email'));
        $this->load->helper(array('url','csv'));
        $this->load->model(array('model_micro','model_outlet_transaksi'));
    }
    function index()
    {
        $this->model_master_data->get_salesman();
    }

    public function generate(){

        $this->db->query("truncate site.t_portal_raw_data_list");

        $sql = "select * from mpm.tabsupp a where a.supp not in (000,007,003,011,008,009,'bsp')";
        $proses = $this->db->query($sql)->result();
    
        // echo "<pre>";
        // print_r($proses);
        // echo "</pre>";

        foreach ($proses as $key) {
            $supp = $key->SUPP;
            if ($supp == 001) {
                $folder = 'deltomed';
            }elseif ($supp == 002) {
                $folder = 'marguna';
            }elseif ($supp == 004) {
                $folder = 'jaya';
            }elseif ($supp == 005) {
                $folder = 'ultra';
            }elseif ($supp == 010) {
                $folder = 'natura';
            }elseif ($supp == 012) {
                $folder = 'intra';
            }elseif ($supp == 013) {
                $folder = 'strive';
            }elseif ($supp == 014) {
                $folder = 'hni';
            }elseif ($supp == 015) {
                $folder = 'multi';
            }
            $this->generate_csv($supp, 2022, 10, $folder);
        }

    }

    public function generate_csv($supp, $tahun, $bulan, $folder){

        ini_set("memory_limit","-1");
        ini_set("max-execution_time", "-1");
        ini_set("max_execution_time", "-1");

        $created_at = $this->model_outlet_transaksi->timezone();

        $this->db->query("update site.t_portal_raw_data a set a.status_generate_csv = 0");

        $get_site = "
            select a.site_code, a.branch_name, a.nama_comp, a.created_at, a.status_generate_csv, left(a.site_code, 3) as kode_comp 
            from site.t_portal_raw_data a
            where a.status_generate_csv = 0
        ";

        $proses_site = $this->db->query($get_site)->result();
        foreach ($proses_site as $key) {

            // echo "branch_name : ".$key->branch_name;
            // die;

            $this->load->dbutil(); 
            $this->load->helper('file'); 
            $this->load->helper('download');
            $delimiter = ","; 
            $newline = "\r\n";
            $filename = $key->kode_comp."_".date('Ymd')."_".$this->generate_acak().".csv";  
            $query = "
                select * 
                from db_raw_cloning.tbl_raw a
                where a.kode_comp = '$key->kode_comp' and a.supplier = $supp and a.tahun = $tahun and a.bulan = $bulan
            ";
            $result = $this->db->query($query);
            $data = $this->dbutil->csv_from_result($result, $delimiter, $newline);

            // print_r($data);

            if ( ! write_file('./assets/file/portal_raw_data/'.$folder.'/'.$filename, $data))
            {
                echo 'Unable to write the file';
            }
            else 
            {
                echo "$key->kode_comp success!";
                echo "<br>";
                $data = [
                    "status_generate_csv"   => 1,
                    "created_at"            => date("Y-m-d H:i:s")
                ];
                $this->db->where('left(site_code,3)', $key->kode_comp);
                $this->db->update('site.t_portal_raw_data', $data);

                $data_insert = [
                    "supp"          => $supp,
                    "filename"      => $filename,
                    "site_code"     => $key->site_code,
                    "tahun"         => $tahun,    
                    "bulan"         => $bulan,
                    "created_at"    => $created_at
                ];

                $this->db->insert('site.t_portal_raw_data_list', $data_insert);
            }
        } 
    }

    public function generate_acak(){
        $karakter = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz123456789';
        $shuffle  = str_shuffle($karakter);
        return $shuffle;
    }

    public function show_processlist(){
        
        $sum = $this->model_micro->show_processlist();
        echo "sum : ".$sum;
        echo "<hr>";
        echo memory_get_peak_usage();
        echo "<hr>";
        ob_flush();

    }

}
?>
