<?php

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Menu extends MY_Controller
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
    var $querymenu ="";

    function Menu()
    {
        $this->load->library(array('table','template'));
        $this->load->helper('url');
        $this->load->model('menu_model','mmodel');
        $this->querymenu='select a.id,a.menuview,a.target,a.groupname from mpm.menu a inner join mpm.menudetail b on a.id=b.menuid where b.userid='. $this->session->userdata('id') . ' and active=1 order by a.groupname,a.menuview';

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
        $data['page_title'] = 'Add New Menu';
        $data['page_content']='menu/form_menu';
        $data['url'] = site_url('menu/addMenu');
        $data['query']=$this->mmodel->list_group();
        $data['menu']=$this->db->query($this->querymenu);
        $this->template($data['page_content'],$data);
    }

    public function addMenu()
    {
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
        if($this->_comment()==FALSE)
        {
           $this->index();
        }
        else
        {
            $this->load->model('menu_model','mmodel');
            $this->mmodel->save_menu($this->session->userdata('id'));
        }
    }
    public function editMenu($id)
    {
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
        $data['page_title'] = 'Edit Menu';
        $data['page_content']='menu/form_menu';
        $data['url'] = site_url('menu/update_menu/'.$id);
        $data['query']=$this->mmodel->list_group();
        $data['edit']=$this->mmodel->show_menu_edit($id);
        $data['menu']=$this->db->query($this->querymenu);
        $this->template($data['page_content'],$data);
        
    }
    public function update_menu($id)
    {
        
        $this->mmodel->edit_menu($this->session->userdata('id'),$id);
 
    }
    public function menuname_check($str)
    {
        if ($this->mmodel->check('menuname',$str))
	{
            return TRUE;
	}
	else
	{
            $this->valid->set_message('menuname_check', 'The %s has been used');
            return FALSE;
	}
    }
    public function print_doc()
    {
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
        echo $this->mmodel->print_menu();
    }
    public function show_menu($offset=0)
    {
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
        $this->load->library('pagination');
       
        $limit = 20;
        $data['query']= $this->mmodel->show_menu($limit,(int)$offset);
        $data['page_content'] = 'report';
        $data['page_title']='Menu List';

        $config['base_url'] = site_url('menu/show_menu/');
        $config['total_rows']= $this->mmodel->getTotalQuery();
        //echo $this->mmodel->getTotalQuery();
        $config['per_page']=$limit;

        $this->pagination->initialize($config);
        $data['pagination']=$this->pagination->create_links();
        $data['print']=anchor_popup('menu/print_doc/',img($this->image_properties));
        $data['menu']=$this->db->query($this->querymenu);
        //$data['print']=anchor_popup('menu/print_doc','print');

	$this->template($data['page_content'],$data);
    }
    public function delete_menu($id=null)
    {
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
        $this->load->library('pagination');
        $this->mmodel->delete_menu($id);
    }
    public function activer($flag=null,$id=null)
    {
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
        $this->load->library('pagination');
        $this->mmodel->activer($flag,$id);
    }
    private function _comment()
    {
        $this->load->library('form_validation');
        $this->valid = $this->form_validation;
        $this->valid->set_rules('menuname','Menu Name','trim|required|min_length[3]|max_length[20]|callback_menuname_check|xss_clean');
        //$this->valid->set_rules('password','Password','trim|required|matches[cpassword]|max_length[8]|md5');
        //$this->valid->set_rules('cpassword','Password Confirmation','trim|required');
        //$this->valid->set_rules('email','Email Address','trim|required|valid_email|callback_email_check');
        return ($this->form_validation->run()==FALSE)?FALSE:TRUE;
    }

}

?>

