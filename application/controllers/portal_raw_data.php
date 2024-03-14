<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Portal_raw_data extends MY_Controller
{
    function portal_raw_data()
    {
        $logged_in = $this->session->userdata('logged_in');
        if (!isset($logged_in) || $logged_in != TRUE) {
            redirect('login/', 'refresh');
        }
        set_time_limit(0);
        $this->load->library(array('table', 'template', 'Excel_generator', 'form_validation', 'email'));
        $this->load->helper('url');
        $this->load->helper('csv');
        // $this->load->model('m_dc');
        $this->load->model(array('M_menu','model_outlet_transaksi', 'model_portal'));
        $this->load->database();
    }

    public function dashboard()
    {
        $data = [
            'id'        => $this->session->userdata('id'),
            'title'     => 'Portal Raw Data',
            'get_label' => $this->M_menu->get_label(),
            'get_list_raw'  => $this->model_portal->get_list_raw()->result(),
        ];
        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar', $data);
        $this->load->view('template_claim/top_content', $data);
        $this->load->view('portal_raw_data/dashboard', $data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
    }
}
