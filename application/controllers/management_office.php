<?php

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Management_office extends MY_Controller
{    
    function management_office()
    {       
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login_sistem/','refresh');
        }
        set_time_limit(0);

        $this->load->library(array('table', 'template', 'Excel_generator', 'form_validation', 'email', 'zip'));
        $this->load->helper(array('url', 'csv'));
        $this->load->model(array('model_outlet_transaksi', 'model_management_office', 'model_inventory', 'M_helpdesk', 'model_dashboard_dummy', 'model_monitor'));
    }

    function navbar($data){
        if ($this->session->userdata('level') === '4') { // jika dp
            $this->load->view('management_office/top_header_dp', $data);
        }elseif ($this->session->userdata('level') === '3') { // jika principal
            $this->load->view('management_office/top_header_principal', $data);
        }elseif ($this->session->userdata('level') === "3a") { // jika principal tanpa sales 
            $this->load->view('management_office/top_header_principal_nosales', $data);
        }elseif ($this->session->userdata('level') === "3b") { // jika principal hanya raw data, claim, rpd 
            $this->load->view('management_office/top_header_principal_rawdata', $data);
        }elseif ($this->session->userdata('level') === "3c") { // jika principal raw_data dan retur dan rpd = RSPH = ghozali yoseph sudarsono
            $this->load->view('management_office/top_header_principal_rawdata_retur', $data);
        }elseif ($this->session->userdata('level') === "3d") { // jika principal rpd
            $this->load->view('management_office/top_header_principal_rpd', $data);
        }else{
            $this->load->view('management_office/top_header', $data);
        }
    }
    function index()
    {
        $data = [
            'title'                 => 'Site MPM - Dashboard',
            'get_bulan_sekarang'    => $this->model_dashboard_dummy->get_bulan_sekarang(),
            'get_kalender_data'     => $this->model_management_office->get_kalender_data(),
            'monitoring_count'      => $this->model_management_office->monitoring_count(),
            'get_closing'           => $this->model_dashboard_dummy->get_closing(),
            'monitoring_kam'        => $this->model_management_office->monitoring_kam(),
            'kam_updated'           => $this->model_management_office->monitoring_kam()->row()->created_at,
            'sell_out_updated'      => $this->model_management_office->monitoring_sell_out()->row()->created_date,
        ];

        $this->navbar($data);

        $this->load->view('kalimantan/header_full_width', $data);
        $this->load->view('management_office/chart',$data);
        $this->load->view('management_office/dashboard', $data);
        if ($this->session->userdata('username') == 'suffy' || $this->session->userdata('username') == 'milla') {
            $this->load->view('management_office/monitoring_count',$data);
        }

        if ($this->session->userdata('username') == 'suffy'  || $this->session->userdata('username') == 'rinnie' || $this->session->userdata('username') == 'tarmiun' || $this->session->userdata('username') == 'agustin' || $this->session->userdata('username') == 'fardison') {
            $this->load->view('management_office/monitoring_kam',$data);
            $this->load->view('management_office/monitoring_sell_out',$data);
        }

        if ($this->session->userdata('username') == 'mardiyanto') {
            $this->load->view('management_office/monitoring_sell_out',$data);
        }

        if ($this->session->userdata('username') == 'suffy'  || $this->session->userdata('username') == 'julianto' || $this->session->userdata('username') == 'edi' || $this->session->userdata('username') == 'ultrasakti') {
            $this->load->view('management_office/monitoring_sell_out_us',$data);
        }
        
        $this->load->view('management_office/datatable',$data);
        $this->load->view('kalimantan/footer');
    }

    public function export_sell_out(){
        $id=$this->session->userdata('id');
        $query="
            select  substr(kode,1,3) as kode_comp, kode, branch_name,nama_comp, kodeprod, namaprod, groupsalur,sektor,segment,
                    unit_1,unit_2,unit_3,unit_4,unit_5,unit_6,
                    unit_7,unit_8,unit_9,unit_10,unit_11,unit_12,
                    value_1,value_2,value_3,value_4,value_5,value_6,
                    value_7,value_8,value_9,value_10,value_11,value_12, 
                    ot_1,ot_2,ot_3,ot_4,ot_5,ot_6,
                    ot_7,ot_8,ot_9,ot_10,ot_11,ot_12,
                    ec_1,ec_2,ec_3,ec_4,ec_5,ec_6,
                    ec_7,ec_8,ec_9,ec_10,ec_11,ec_12               
            from   site.temp_sell_out
            ORDER BY urutan asc
        ";                            
        $hasil = $this->db->query($query);
        query_to_csv($hasil,TRUE,"Sell Out Product Automatic.csv");
    }

    public function export_sell_out_us(){
        $id=$this->session->userdata('id');
        $query="
            select  substr(kode,1,3) as kode_comp, kode, branch_name,nama_comp, kodeprod, namaprod, `group`, nama_group,
                    unit_1,unit_2,unit_3,unit_4,unit_5,unit_6,
                    unit_7,unit_8,unit_9,unit_10,unit_11,unit_12,
                    value_1,value_2,value_3,value_4,value_5,value_6,
                    value_7,value_8,value_9,value_10,value_11,value_12, 
                    ot_1,ot_2,ot_3,ot_4,ot_5,ot_6,
                    ot_7,ot_8,ot_9,ot_10,ot_11,ot_12,
                    ec_1,ec_2,ec_3,ec_4,ec_5,ec_6,
                    ec_7,ec_8,ec_9,ec_10,ec_11,ec_12               
            from   site.temp_soprod_us
            ORDER BY urutan asc
        ";                            
        $hasil = $this->db->query($query);
        query_to_csv($hasil,TRUE,"Sell Out Product Us Automatic.csv");
    }

    public function update_data_kam(){
        $this->load->model('model_mti');
        $data_source = [
            'herbal'    => $this->model_mti->get_kodeprod_by_group('G0101'),
            'candy'     => $this->model_mti->get_kodeprod_by_group_exception('G0102', '010121'),
            'kode_type' => $this->model_mti->get_kode_type_by_sektor('MTI'),
            'kode_type_ph' => $this->model_mti->get_kode_type_by_segment('PH'),
            'all_principal'  => $this->model_mti->get_kodeprod_by_supp(),
        ];

        $update = $this->model_management_office->update_data_kam($data_source);

        if ($update) {
            redirect('management_office/');
        }else{
            echo "something happen. Please call IT";
        }        

    }
    
}
?>
