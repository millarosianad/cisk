<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');
class Portal_raw extends MY_Controller
{
    public function portal_raw()
    {
        $logged_in = $this->session->userdata('logged_in');
        if (!isset($logged_in) || $logged_in != TRUE) {
            redirect('login/', 'refresh');
        }
        set_time_limit(0);
        $this->load->library(array('table', 'template', 'Excel_generator', 'form_validation', 'email'));
        $this->load->helper('url');
        $this->load->helper('csv');
        $this->load->model('M_menu');
        $this->load->model(array('M_menu', 'model_portal_raw', 'model_sales_omzet'));
        $this->load->database();
    }

    function index()
    {
        // $userid = $this->session->userdata('id');
        // if ($userid == 561) {
        //     redirect(base_url('sales_omzet/raw_data'));
        // }
        
        $data = [
            'title' => 'Portal Raw Data',
            'get_label' => $this->M_menu->get_label(),
            'list_data' => $this->model_portal_raw->get_list_raw()->result()
        ];

        $user = $this->session->userdata('username');
        $this->db->select('*');
        $this->db->where('kode_comp',$user);
        $jml = $this->db->count_all_results('mpm.tbl_tabcomp');

        // check user dp
        if ($jml > 0) {
            $view = 'portal_raw/index_dp';
        } else {
            $view = 'portal_raw/index';
        }
        
        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar', $data);
        $this->load->view('template_claim/top_content', $data);
        $this->load->view("$view", $data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
    }

    public function download_file()
    {
        $folder = $this->uri->segment('3');
        $file = $this->uri->segment('4');
        // var_dump($target2);die;
        $id = $this->session->userdata('id');
        $this->db->select('username');
        $this->db->where('id', $id);
        $data_user = $this->db->get('mpm.user')->row();

        $username = $data_user->username;
        $move_folder = md5($username);
        if (!file_exists("./assets/file/portal_raw/$move_folder/")) {
            mkdir("./assets/file/portal_raw/$move_folder/", 0777, true);
        }

        $source = base_url("assets/file/portal_raw/raw_data/$folder/$file"); 
        $destination = "./assets/file/portal_raw/$move_folder/$file"; 

        if( !copy($source, $destination) ) { 
            echo ("<script LANGUAGE='JavaScript'>
            window.alert('File Tidak Ditemukan');
            window.location.href='';
            </script>");
            redirect(base_url("portal_raw"));
        } 
        else { 
            redirect(base_url("assets/file/portal_raw/$move_folder/$file"));
        } 
    }

    public function download_file_dp()
    { 
        $user = $this->session->userdata('username');
        $supp = $this->uri->segment('3');

        $this->db->select('*');
        $this->db->where('supp',$supp);
        $tabsupp = $this->db->get('mpm.tabsupp')->row();
        // var_dump($supp);die;
        $query = "
        select a.branch_name, a.kode_comp, a.nama_comp, a.nocab, a.faktur, a.tipe_dokumen, a.kodeprod,
                a.namaprod, a.`group`, a.nama_group, a.sub_group, a.nama_sub_group, a.tanggal, a.kode_lang,
                a.nama_lang, '' as alamat, a.kode_kota, a.nama_kota, a.kode_type, a.nama_type, a.sektor, a.segment,
                a.sektor_perkiraan, a.sektor_delto, a.kode_sales, a.nama_sales, a.kodesalur, a.namasalur,
                a.unit, a.harga, a.hna, a.diskon, a.bruto, a.netto, a.tahun, a.bulan, a.supplier, a.nama_supplier,
                a.id_provinsi, a.nama_provinsi, a.id_kota, a.nama_kota_kabupaten, a.id_kecamatan, a.nama_kecamatan, a.id_kelurahan,
                a.nama_kelurahan, a.credit_limit, a.tipe_bayar, a.phone, a.unit_bonus, a.term_payment, 
                IF(a.tipe_kl is NULL || a.tipe_kl = 0, 'non_KL', 'KL') as tipe_kl,
                IF(a.status_toko = 'Y' || a.status_toko = 'y', 'Aktif', 'Non_aktif') as status_toko, a.keterangan
        from db_raw_cloning.tbl_raw a
        where a.supplier = $supp and a.kode_comp = '$user'
        ";
        $hasil = $this->db->query($query);
        query_to_csv($hasil,TRUE,"raw_dp_$tabsupp->NAMASUPP.csv");
    }

}

?>