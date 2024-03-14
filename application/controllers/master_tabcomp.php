<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Master_tabcomp extends MY_Controller
{
    public function Master_tabcomp(){
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
        set_time_limit(0);
        $this->load->library(array('table','template','Excel_generator', 'form_validation','email'));
        $this->load->helper('url');
        $this->load->helper('csv');
        $this->load->model('M_menu');
        $this->load->model('M_tabcomp');
        $this->load->database();
    }

    public function get_tabcompID(){
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }

        $tabcompID = $_GET['id'];
        $data['edit']   = $this->M_tabcomp->get_tabcomp_by_ID($tabcompID);
        echo json_encode($data);
    }

    public function tabcomp(){
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }

        $data = [
            'id'            => $this->session->userdata('id'),
            'url'           => 'master_tabcomp/tabcomp/',
            'title'         => 'Tabcomp',
            'get_label'     => $this->M_menu->get_label(),
            'tabcomp'       => $this->M_tabcomp->get_tabcomp()
        ];

        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar',$data);
        $this->load->view('template_claim/top_content',$data);
        $this->load->view('master_tabcomp/tabcomp',$data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
    }

    public function tambah_tabcomp()
    {
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
        
        $this->M_tabcomp->tambah_tabcomp();
    }

    public function edit_tabcomp()
    {
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
        
        $this->M_tabcomp->edit_tabcomp();
    }

    public function activer_tabcomp($flag=null,$id=null)
    {
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
        $this->M_tabcomp->activer_tabcomp($flag,$id);
    }

    public function activer_stok($flag=null,$id=null)
    {
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
        $this->M_tabcomp->activer_stok($flag,$id);
    }

    public function activer_api($flag=null,$id=null)
    {
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
        $this->M_tabcomp->activer_api($flag,$id);
    }

    public function activer_jawa($flag=null,$id=null)
    {
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
        $this->M_tabcomp->activer_jawa($flag,$id);
    }

    public function activer_cluster($flag=null,$id=null)
    {
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
        $this->M_tabcomp->activer_cluster($flag,$id);
    }

    public function activer_repl($flag=null,$id=null)
    {
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
        $this->M_tabcomp->activer_repl($flag,$id);
    }

    public function activer_grouprepl($flag=null,$id=null)
    {
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
        $this->M_tabcomp->activer_grouprepl($flag,$id);
    }
}