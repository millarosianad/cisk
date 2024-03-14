<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Dashboard extends MY_Controller
{
    function dashboard()
    {
        set_time_limit(0);
        $this->load->library(array('table','template','Excel_generator', 'form_validation','email'));
        $this->load->helper('url');
        $this->load->helper('csv');
        $this->load->model('model_omzet');
        $this->load->model('M_menu');
        $this->load->model('model_dashboard');
        $this->load->model('model_per_hari');
        $this->load->model('model_sales_omzet');
        $this->load->database();
    }
    function index()
    {
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }        
    }

    public function default_user(){
        $data = [
            'id' => $this->session->userdata('id'),
            'url' => 'sales_omzet/line_sold_hasil/',
            'title' => 'Dashboard',
            'get_label' => $this->M_menu->get_label(),   
            'get_bulan_sekarang' => $this->model_dashboard->get_bulan_sekarang(),
            'get_closing' => $this->model_dashboard->get_closing(),
            'get_dp_belum_closing' => $this->model_dashboard->get_dp_belum_closing(),
            'get_tanggal_data' => $this->model_dashboard->get_tanggal_data(),
        ];
        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar',$data);
        $this->load->view('dashboard/closing_top');
        $this->load->view('dashboard/pie_data_closing',$data);
        $this->load->view('dashboard/closing_bottom');
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
    }
}
?>
