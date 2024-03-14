<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends MY_Controller
{
    var $image_properties = array(
          'src' => 'assets/css/images/printer.png',
          'alt' => 'Printing Document',
          'class' => 'post_images',
          'width' => '50',
          'height' => '50',
          'title' => 'Printer',
          'border' => 0,
    );
    var $querymenu='';
    ////'select menuview,target,groupname from mpm.menu where active = 1 order by groupname';
    function User()
    {
        $this->load->library(array('table','template'));
        $this->load->helper('url');
        $this->load->model('user_model','umodel');
        $this->querymenu = 'select a.id,a.menuview,a.target,a.groupname from mpm.menu a inner join mpm.menudetail b on a.id=b.menuid where b.userid='.$this->session->userdata('id').' and active=1 order by a.groupname,menuview';
    }
    private function template($view,$data)
    {
        $this->template->set_title('MPM SQUARE');
        $this->template->add_js('modules/skeleton.js');
        $this->template->add_css('modules/skeleton.css');
        $this->template->load_view($view, $data);
    }
    function index()
    {
       $logged_in= $this->session->userdata('logged_in');
       if(!isset($logged_in) || $logged_in != TRUE)
       {
            redirect('login/','refresh');
       }
       else
       {
            $data['page_title'] = 'Add New User';
            $data['page_content']='user/form_user';
            $data['url'] = site_url('user/addUser/');
            $data['query']=$this->getSupp();
            $data['menu']=$this->db->query($this->querymenu);
            $this->template($data['page_content'],$data);
       }
     
    }
  
    public function addUser()
    {
       $logged_in= $this->session->userdata('logged_in');
       if(!isset($logged_in) || $logged_in != TRUE)
       {
            redirect('login/','refresh');
       }
       else
       {
            if($this->_comment()==FALSE)
            {
                $data['page_title'] = 'Add New User';
                $data['page_content']='user/form_user';
                $data['url'] = site_url('user/addUser');
                $data['menu']=$this->db->query($this->querymenu);
                $data['query']=$this->getSupp();
                $this->template($data['page_content'],$data);
            }
            else
            {
                $this->load->model('user_model','umodel');
                $this->umodel->save_user($this->session->userdata('id'));
            }
       }
    }
    public function username_check($str)
    {
        if ($this->umodel->check('username',$str))
	{
            return TRUE;
	}
	else
	{
            $this->valid->set_message('username_check', 'The %s has been used');
            return FALSE;
	}
       
    }
    public function account()
    {
       $logged_in= $this->session->userdata('logged_in');
       if(!isset($logged_in) || $logged_in != TRUE)
       {
            redirect('login/','refresh');
       }
       else
       {
            $edit = array(
              'src' => 'assets/css/images/icon_edit.gif',
              'alt' => 'Printing Document',
              'class' => 'post_images',
              'title' => 'Edit User Account',
              'border' => 0);
            $pass = array(
              'src' => 'assets/css/images/icon_pass.gif',
              'alt' => 'Printing Document',
              'class' => 'post_images',
              'title' => 'Change Password',
              'border' => 0);
            $data['page_title'] = 'User Account';
            $data['page_content']='user/form_account_edit';
            $data['url'] = site_url('user/account/');
            $data['menu']=$this->db->query($this->querymenu);
            $data['list']=$this->umodel->account($this->session->userdata('id'));

            $data['edit']= anchor('user/account_edit/'.$this->input->post('year'),img($edit));
            $data['pass']= anchor('user/password_edit/'.$this->input->post('year'),img($pass));
            $this->template($data['page_content'],$data);
       }
    }
    public function account_edit($id=null)
    {
       $logged_in= $this->session->userdata('logged_in');
       if(!isset($logged_in) || $logged_in != TRUE)
       {
            redirect('login/','refresh');
       }
       else
       {
            $data['page_title'] = 'Edit User Account';
            $data['page_content']='user/form_account_edit';
            $data['url'] = site_url('user/account_save/'.$id);
            if($id=='')
            {
                $user=$this->session->userdata('id');
            }
            else
            {
                $user=$id;
            }

            $data['menu']=$this->db->query($this->querymenu);
            $data['edit']=$this->umodel->account($user);
            //$data['edit']=$this->umodel->account_edit($this->session->userdata('id'));
            $this->session->set_userdata('redirect', $_SERVER['HTTP_REFERER']);
            $this->template($data['page_content'],$data);
       }
    }
    public function account_save($id=null)
    {
       $logged_in= $this->session->userdata('logged_in');
       if(!isset($logged_in) || $logged_in != TRUE)
       {
            redirect('login/','refresh');
       }
       if($id=='')
       {
            $user=$this->session->userdata('id');
       }
       else
       {
            $user=$id;
       }
       $this->umodel->account_save($user);
       $this->session->flashdata('redirect');
       redirect($this->session->userdata('redirect'));
    }
    public function password_edit()
    {
       $logged_in= $this->session->userdata('logged_in');
       if(!isset($logged_in) || $logged_in != TRUE)
       {
            redirect('login/','refresh');
       }
       else
       {
            $data['page_title'] = 'Edit Password';
            $data['page_content']='user/form_pass_edit';
            $data['url'] = site_url('user/password_save/');
            $data['menu']=$this->db->query($this->querymenu);
            $data['name']=$this->session->userdata('username');
            $data['id']=$this->session->userdata('id');
            //$data['edit']=$this->umodel->account_edit($this->session->userdata('id'));
            $this->template($data['page_content'],$data);
       }
    }
    public function password_err()
    {
       $logged_in= $this->session->userdata('logged_in');
       if(!isset($logged_in) || $logged_in != TRUE)
       {
            redirect('login/','refresh');
       }
       else
       {
            $data['page_title'] = 'Edit Password';
            $data['page_content']='user/form_pass_edit';
            $data['url'] = site_url('user/password_save/');
            $data['menu']=$this->db->query($this->querymenu);
            $data['name']=$this->session->userdata('username');
            $data['id']=$this->session->userdata('id');
            $data['message']='Password cannot blank';
            //$data['edit']=$this->umodel->account_edit($this->session->userdata('id'));
            $this->template($data['page_content'],$data);
       }
    }
    public function password_save()
    {
       $logged_in= $this->session->userdata('logged_in');
       if(!isset($logged_in) || $logged_in != TRUE)
       {
            redirect('login/','refresh');
       }
       $this->umodel->password_save($this->session->userdata('id'));
    }
    public function show_menu()
    {
       $logged_in= $this->session->userdata('logged_in');
       if(!isset($logged_in) || $logged_in != TRUE)
       {
            redirect('login/','refresh');
       }
       else
       {
            $data['page_title'] = 'Add New User';
            $data['page_content']='user/form_user';
            $data['url'] = site_url('user/addUser');
            $data['menu']=$this->db->query($this->querymenu);
            $data['query']=$this->umodel->list_menu();
            $this->template($data['page_content'],$data);
       }
    }
    public function assign_menu($str_id=0,$id=0)
    {
       $logged_in= $this->session->userdata('logged_in');
       if(!isset($logged_in) || $logged_in != TRUE)
       {
            redirect('login/','refresh');
       }
       else
       {
            $data['page_title'] = 'Assign Menu to User';
            $data['page_content']='user/form_assign_menu';
            $data['url'] = site_url('user/assign_menu_save/'.$str_id.'/'.$id);
            $data['menu']=$this->db->query($this->querymenu);
            $data['query']=$this->umodel->assign_menu($str_id,$id);
            $this->template($data['page_content'],$data);
       }
    }
    public function assign_menu_save($str_id=0,$id=0)
    {
       $logged_in= $this->session->userdata('logged_in');
       if(!isset($logged_in) || $logged_in != TRUE)
       {
            redirect('login/','refresh');
       }
       $this->umodel->assign_menu_save($str_id,$id);
    }
    public function email_check($str)
    {
        if ($this->umodel->check('email',$str))
	{
            return TRUE;
	}
	else
	{
            $this->valid->set_message('email_check', 'The %s has been used');
            return FALSE;
	}
    }
    private function getSupp()
    {
        return $this->umodel->getSupp();
    }
    public function user_menu($id)
    {
       $logged_in= $this->session->userdata('logged_in');
       if(!isset($logged_in) || $logged_in != TRUE)
       {
            redirect('login/','refresh');
       }
       else
       {
            $data['page_title'] = 'Menu vs User';
            $data['page_content']='user/form_user';
            $data['url'] = site_url('user/addUser');
            $data['menu']=$this->db->query($this->querymenu);
            $data['query']=$this->umodel->select_menu($id);
            $this->template($data['page_content'],$data);
       }
    }
    public function print_doc()
    {
       $logged_in= $this->session->userdata('logged_in');
       if(!isset($logged_in) || $logged_in != TRUE)
       {
            redirect('login/','refresh');
       }
       echo $this->umodel->print_user();
    }
    public function show_user($offset=0)
    {
       $logged_in= $this->session->userdata('logged_in');
       if(!isset($logged_in) || $logged_in != TRUE)
       {
            redirect('login/','refresh');
       }
       else
       {
            $this->load->library('pagination');
            /*if(!is_null($offset))
            {
                $offset = $this->uri->segment(5);
            }*/
            $limit = 10;
            $data['query']= $this->umodel->show_user($limit,(int)$offset);
            $data['page_content'] = 'report';
            $data['page_title']='LIST USER';
            $data['search']=site_url('user/show_user/');

            $config['base_url'] = site_url('user/show_user/');
            $config['total_rows']= $this->umodel->getTotalQuery();
            //echo $this->umodel->getTotalQuery();
            $config['per_page']=$limit;

            $this->pagination->initialize($config);
            $data['pagination']=$this->pagination->create_links();
            $data['print']=anchor_popup('user/print_doc/',img($this->image_properties));
            $data['menu']=$this->db->query($this->querymenu);
            //echo $this->uri->uri_string();
            $this->session->set_userdata('redirect', $_SERVER['HTTP_REFERER']);
            //$data['print']=anchor_popup('user/print_doc','print');

            $this->template($data['page_content'],$data);
       }
    }
    public function delete_user($id=null)
    {
       $logged_in= $this->session->userdata('logged_in');
       if(!isset($logged_in) || $logged_in != TRUE)
       {
            redirect('login/','refresh');
       }
       else
       {
            $this->umodel->delete_user($id);
       }
    }
    public function activer($flag=null,$id=null)
    {
       if($this->session->userdata('logged_in')!=TRUE)
       {
            $this->check_session();
       }
       else
       {
            $this->session->set_userdata('redirect', $_SERVER['HTTP_REFERER']);
            $this->umodel->activer($flag,$id);
            redirect($this->session->userdata('redirect'));
       }
    }
    private function _comment()
    {
        $this->load->library('form_validation');
        $this->valid = $this->form_validation;
        $this->valid->set_rules('username','User Name','trim|required|min_length[3]|max_length[20]|callback_username_check|xss_clean');
        $this->valid->set_rules('password','Password','trim|required|matches[cpassword]|min_length[3]|md5');
        $this->valid->set_rules('cpassword','Password Confirmation','trim|required');
        $this->valid->set_rules('email','Email Address','trim|required|valid_email|callback_email_check');
        return ($this->form_validation->run()==FALSE)?FALSE:TRUE;
    }
   
}

?>
       
  