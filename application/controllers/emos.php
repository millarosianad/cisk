<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Emos extends MY_Controller
{

    public function __construct()
    {
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
        // $id = $this->session->userdata('id');
        set_time_limit(0);
        $this->load->library(array('table','template','Excel_generator', 'form_validation','email'));
        $this->load->helper('url');
        $this->load->helper('csv');
        $this->load->model('model_emos','emos');
        $this->load->database();
    }

    function emos()
    {        
    }

    public function faktur_order($data = null){
        $data = [
            "CustomerPONumber" => "c"
        ];
        $proses = $this->emos->insert_faktur_order($data);

    }

}
?>
