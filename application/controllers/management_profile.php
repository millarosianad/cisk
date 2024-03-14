<?php

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Management_profile extends MY_Controller
{
    function management_profile()
    {       
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login_sistem/management_asset','refresh');
        }
        set_time_limit(0);
        $this->load->library(array('table', 'template', 'Excel_generator', 'form_validation', 'email', 'zip'));
        $this->load->helper(array('url', 'csv'));
        // $this->load->model(array('model_management_profile'));
    }
    
    function index()
    {
        $this->signature();
    }

    function navbar($data){
        if ($this->session->userdata('level') === 4) { // jika dp
            $this->load->view('management_office/top_header_dp', $data);
        }elseif ($this->session->userdata('level') === 3) { // jika principal
            $this->load->view('management_office/top_header_principal', $data);
        }elseif ($this->session->userdata('level') === "3a") { // jika principal tanpa sales
            $this->load->view('management_office/top_header_principal_nosales', $data);
        }else{
            $this->load->view('management_office/top_header', $data);
        }
    }

    public function signature()
    {
        $data = [
            'title'     => 'Digital Signature',
            'url'       => 'management_profile/signature_tambah',
        ];

        // $this->load->view('management_office/top_header', $data);  
        
        $this->navbar($data);

        $this->load->view('template_claim/top_header_signature');
        $this->load->view('kalimantan/header_full_width', $data);
        $this->load->view('management_profile/signature',$data);
        $this->load->view('kalimantan/footer');
    }

    public function signature_tambah()
    {
        $id = $this->session->userdata('id');
        $username = $this->session->userdata('username');
        // var_dump($id);die;

        $folderPath = './assets/uploads/signature/';        
        $image_parts = explode(";base64,", $_POST['signed']);
        $image_type_aux = explode("image/", $image_parts[0]);
        $image_type = $image_type_aux[1];
        $image_base64 = base64_decode($image_parts[1]);
        $file = $folderPath . $username. '-signature.' .$image_type;
        file_put_contents($file, $image_base64);
        
        redirect("management_profile/signature/",'refresh');
    }

    public function signature_digital(){
        
        $data = [
            'title'     => 'Digital Signature',
            'url'       => "management_profile/signature_tambah",
        ];

        // $this->load->view('management_office/top_header', $data);
        $this->navbar($data);
        
        $this->load->view('template_claim/top_header_signature');
        $this->load->view('kalimantan/header_full_width', $data);
        $this->load->view('management_rpd/signature_digital', $data);
        $this->load->view('kalimantan/footer');
    }
}