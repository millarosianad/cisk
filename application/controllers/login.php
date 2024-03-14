<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends MY_Controller{
    
    function Login()
    {
        $this->load->model('login_model','lmodel');
        $this->load->model('M_menu');
    }
    public function index()
    {
        $this->load->library('template');

        $this->template->set_title('MPM SQUARE');
        $this->template->add_js('modules/skeleton.js');
        $this->template->add_css('modules/skeleton.css');
    
        $data['uri']=  site_url('login/login_check');
        $this->template->load_view('login/login', $data);
        //$this->template->
        redirect('login_sistem');
    }
    public function template($view,$data)
    {
        $this->load->library('template');
        $this->template->set_title('MPM SQUARE');
        $this->template->add_js('modules/skeleton.js');
        $this->template->add_css('modules/skeleton.css');
        $this->template->load_view($view, $data);
    }
    public function home()
    {
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
        else
        {   
            $data['username']= $this->session->userdata('username');
            $data['company']= $this->session->userdata('company');
            $data['email']= $this->session->userdata('email');
            $data['address']= $this->session->userdata('address');
            $data['page_title'] = 'Welcome to MPM Distribution System';
            $data['page_content']='home';
            $data['url'] = site_url('login/');
            // $data['map'] = $this->lmodel->peta();
            $this->querymenu = 'select a.id,a.menuview,a.target,a.groupname from mpm.menu a inner join mpm.menudetail b on a.id=b.menuid where b.userid='.$this->session->userdata('id').' and active=1 order by a.groupname,menuview';
            $data['menu']=$this->db->query($this->querymenu);
            
            // $this->template('home', $data);
            $update_hak_menu = $this->M_menu->get_strukur();
            if ($update_hak_menu === 1) {
                $this->template('home', $data);
            }else{
                $this->logout();
            }
        }
    }
        
    public function logout()
    {
         $this->session->unset_userdata($this->session->all_userdata());
         $this->session->sess_destroy();
         redirect('login_sistem/','refresh');
    }
    public function login_check()
    {
        $user=mysql_real_escape_string($this->input->post('userx'));
        $pass=md5(mysql_real_escape_string($this->input->post('passx')));
        
        $query=$this->lmodel->login_check($user,$pass);
        if($query)
        {
                //echo 'success';
            $row = $query->row_array(1);
            $data = array(
                'supp'      => $row['supp'],
                'username'  => $row['username'],
                'id'        => $row['id'],
                'nocab'     => $row['nocab'],
                'cabang'    => $row['cabang'], 
                'level'    => $row['level'],
                'logged_in' =>TRUE,
                'address' =>$row['address'],
                'email' =>$row['email'],
                'company' =>$row['company'],
                'kode_lang' =>$row['kode_lang'],
            );
                //$query->free_result();
            $this->session->set_userdata($data);
            $data['page_title'] = 'Welcome to MPM Distribution System';
            $data['page_content']='home';
            $data['url'] = site_url('welcome/home');
            //$data['map'] = $this->lmodel->peta();
            $this->querymenu = 'select a.id,a.menuview,a.target,a.groupname from mpm.menu a inner join mpm.menudetail b on a.id=b.menuid where b.userid='.$this->session->userdata('id').' and active=1 order by a.groupname,menuview';
            $data['menu']=$this->db->query($this->querymenu);
                //if($this->agent->is_mobile())
                  //  $this->load->view('mobile/wrapper',$data);
                //else
            $update_hak_menu = $this->M_menu->get_strukur();
            if ($update_hak_menu === 1) {
                $this->template('home', $data);
            }else{
                $this->logout();
            }
            // $this->template('home', $data);
            // $this->info();
            
        }
        else
        {
            $this->index();
        }
    }

    public function info(){
            $this->load->view('info');
        }

    
        
}
?>
