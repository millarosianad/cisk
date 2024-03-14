<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Mahasiswa extends MY_Controller
{
    function Mahasiswa()
    {
        set_time_limit(0);
        $this->load->library(array('table','template','Excel_generator', 'form_validation','email'));
        $this->load->helper('url');
        $this->load->helper('csv');
        // $this->load->model('model_omzet');
        // $this->load->model('M_menu');
        // $this->load->model('model_dashboard_dummy');
        // $this->load->model('model_per_hari');
        $this->load->model('modelmahasiswa');
        $this->load->database();
    }
    function index()
    {
        
        // $data['dataMahasiswa']=$this->modelmahasiswa->orderBy('filename','asc')
        // ->findAll($limit, $mulai);
        $data['get_upload'] = $this->modelmahasiswa->get_upload();
        return $this->load->view('mahasiswa/mahasiswa_view',$data);
    }
}
?>
