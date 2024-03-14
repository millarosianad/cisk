<?php

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class upload_data_text extends MY_Controller
{    
    function upload_data_text()
    {       
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
        set_time_limit(0);
        $this->load->library(array('table', 'template', 'Excel_generator', 'form_validation', 'email', 'zip'));
        $this->load->helper(array('url', 'csv'));
        $this->load->model(array('model_outlet_transaksi','model_target_principal'));
        $this->load->model('model_upload_data_text');
    }

    // function __construct()
    // {
    //     parent::__construct();
        
    //     $this->load->model('model_upload_data_text');
    // }

    function index()
    {
        $data = [
            'title'     => 'Upload Data Txt Afiliasi | Form Upload',
            // 'url'       => 'upload_data_text/upload',
            // 'url'       => 'target_principal/import_deltomed_target_by_subbranch',
            //"files"     => directory_map("./upload")
            'file'      => $this->model_upload_data_text->get_file(),
        
        ];

        $this->load->view('monitor/top_header', $data);
        $this->load->view('monitor/header_full_width', $data);
        $this->load->view('upload_data_text/index', $data);
        $this->load->view('monitor/footer');
    }
    
    // function create(){
        
    //     $this->load->view('');

    // }

    public function upload() {
        // ECHO 'A' ;DIE;

        // $data['filename'] = '';
        // $files = $_FILES['filename']['name'];

        // $config["upload_path"] = "./upload/";
        // $config["allowed_types"] = "*";

        // $this->load->library("upload", $config);

        // if (function_exists('date_default_timezone_set'))
        //     date_default_timezone_set('Asia/Jakarta');

        if (!is_dir('./assets/uploads/tester/' . date('Ym') . '/')) {
            @mkdir('./assets/uploads/tester/' . date('Ym') . '/', 0777);
        }

        //konfigurasi upload zip
        $config['upload_path'] = './assets/uploads/tester/' . date('Ym') . '';
        // $config['upload_path'] = './assets/uploads/tester/';
        $config['allowed_types'] = 'zip|ZIP';
        $config['max_size'] = '*';
        $config['overwrite'] = 'TRUE';

        $this->load->library('upload', $config);


        if ($this->upload->do_upload('file')) {

            $upload_data     = $this->upload->data();
            $filename        = $upload_data['orig_name'];
            $tanggal         = substr($upload_data['orig_name'], 6, 2);
            $month           = substr($upload_data['orig_name'], 8, 2);
            $tahun           = substr($upload_data['orig_name'], 10, 4);
            $username        = $this->session->userdata('username');
            $typedata        = $this->input->post('typedata');
            $created_by      = $this->session->userdata('id');
            $created_at      = $this->model_outlet_transaksi->timezone();
            $signature       = md5($created_at.$created_by);
            

            $data = [
                'created_by'        => $created_by,
                'username'          => $username,
                'filename'          => $filename,
                'tanggal'           => $tanggal,
                'bulan'             => $month,
                'tahun'             => $tahun,
                'status_proses'     => 1,
                'created_at'        => $created_at,
                'typedata'          => $typedata,
                'signature'         => $signature
            ];

            // $files = $this->upload->data('file_name');
            // $data['filename'] = $files;
            $this->db->insert('test_it.upload_dt', $data);
            
            
        } else {
            // $error = array('error' => $this->upload->display_errors());
            // $this->load->view('upload_form_view', $error);

            echo '<script>alert("File tidak sesuai. Pastikan File nya sudah benar. Jika perlu bantuan silahkan hubungi IT !");</script>';
            redirect("upload_data_text", 'refresh');// echo "Gagal mengunggah file";
        }
        // $query = $this->db->insert('test_it.upload', $data);
        // return $query;

        echo '<script>alert("Sukses Upload Data Text");</script>';
        redirect("upload_data_text", 'refresh');

    }

    public function status($id, $status){


        $data = [
            'status_proses' => $status
        ];
        $this->db->where('id', $id);
        $this->db->update('test_it.upload_dt', $data);
        redirect("upload_data_text");

    }

    public function status_error(){
        $data = [
            'status_proses' => 4
            // 'pesan_error' => $this->input->post('message-text'),
        ];
        $this->db->update('test_it.upload_dt', $data);

        redirect("upload_data_text");

    }

}
?>