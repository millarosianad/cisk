<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Profile extends MY_Controller
{
    ////'select menuview,target,groupname from mpm.menu where active = 1 order by groupname';
    function Profile()
    {
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
        $this->load->library(array('table','template'));
        $this->load->helper('url');
        $this->load->model('M_profile');
        $this->load->model('M_menu');
    }

    function index()
    {
        $this->account();      
    }

    public function account()
    {
        $data = [
            'id' => $this->session->userdata('id'),
            'url' => 'profile/account/',
            'title' => 'Profile Account',
            'get_label' => $this->M_menu->get_label(),
            'list' => $this->M_profile->account($this->session->userdata('id')),
        ];

        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar',$data);
        $this->load->view('template_claim/top_content',$data);
        $this->load->view('profile/form_account',$data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
    }

    public function account_edit($id=null)
    {
        if($id=='')
        {
            $user=$this->session->userdata('id');
        }
        else
        {
            $user=$id;
        }
        $this->M_profile->account_save($user);
        $this->session->flashdata('redirect');
        redirect($this->session->userdata('redirect'));
    }

    public function password_save()
    {
        $this->M_profile->password_save($this->session->userdata('id'));
    }
}

?>