<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Konfirmasi_dc extends MY_Controller
{
    function konfirmasi_dc()
    {
        // $logged_in = $this->session->userdata('logged_in');
        // if (!isset($logged_in) || $logged_in != TRUE) {
        //     redirect('login/', 'refresh');
        // }
        // set_time_limit(0);
        $this->load->library(array('table', 'template', 'form_validation', 'email'));
        $this->load->helper(array('url','csv'));
        $this->load->model(array('M_menu', 'model_outlet_transaksi', 'M_dc'));
        $this->load->database();
    }

    public function konfirmasi()
    {
        // echo "hello konfirmasi dc";
        $data = [
            'get_data_row_keluar'  => $this->M_dc->get_data_row_keluar()->result(),
        ];

        $this->load->view('dc/konfirmasi_dc', $data);

    }
}
