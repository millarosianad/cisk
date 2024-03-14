<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Backup extends MY_Controller
{
    function backup()
    {
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
        set_time_limit(0);
        $this->load->library(array('table','template','Excel_generator', 'form_validation','email'));
        $this->load->helper('url');
        $this->load->helper('csv');
        $this->load->model('model_backup');
        $this->load->database();
    }

    public function backupFi(){
        $backupFi = $this->model_backup->getFi();
        // print_r($backupFi);
    }

}
?>
