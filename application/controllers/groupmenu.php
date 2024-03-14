<?php


class Groupmenu extends MY_Controller
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
    var $querymenu;
    function Groupmenu()
    {
        $this->load->library(array('table','template'));
        $this->load->helper('url');
        $this->load->model('groupmenu_model','gmmodel');
        $this->querymenu='select a.id,a.menuview,a.target,a.groupname from mpm.menu a inner join mpm.menudetail b on a.id=b.menuid where b.userid='. $this->session->userdata('id') . ' and active=1 order by a.groupname,a.menuview';
    }
    private function template($view,$data)
    {
        
        $this->template->set_title('MPM SQUARE');
        $this->template->add_js('modules/skeleton.js');
        $this->template->add_css('modules/skeleton.css');
        $this->template->load_view($view, $data);
    }
    private function auth()
    {
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
        else
        {
           $uri=$this->uri->segment(1).$this->uri->slash_segment(2,'both').$this->uri->segment(3);
           if(substr($uri,-1)!="/")
           {
               $uri.="/";
           }
           $sql="select 1 from menu a inner join menudetail b on a.id=b.menuid where b.userid=".$this->session->userdata('id')." and a.target='".$uri."'";
           $query=$this->db->query($sql,array($this->session->userdata('id'),$uri));

           if($query->num_rows()==0)
           {
              //redirect('welcome/home/','refresh');
           }
        }
        $this->load->library('pagination');
    }
    function index()
    {
        $this->auth();
        $data['page_title'] = 'New Group Menu';
        $data['page_content']='menu/form_groupmenu';
        $data['url'] = site_url('groupmenu/addgroupmenu');
        $data['menu']=$this->db->query($this->querymenu);
        $this->template($data['page_content'],$data);
    }

    public function addgroupmenu()
    {
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('welcome/login/','refresh');
        }
        if($this->_comment()==FALSE)
        {
            $data['page_title'] = 'New Group Menu';
            $data['page_content']='menu/form_groupmenu';
            $data['url'] = site_url('groupmenu/addgroupmenu');
            $date['query'] = $this->mmodel->list_group();
            $data['menu']=$this->db->query($this->querymenu);
            $this->template($data['page_content'],$data);
        }
        else
        {
            $this->load->model('groupmenu_model','mmodel');
            $this->gmmodel->save_groupmenu(1);
        }
    }
    public function groupmenuname_check($str)
    {
        if ($this->gmmodel->check('groupmenuname',$str))
	{
            return TRUE;
	}
	else
	{
            $this->valid->set_message('groupmenuname_check', 'The %s has been used');
            return FALSE;
	}
    }
    public function print_doc()
    {
        echo $this->gmmodel->print_groupmenu();
    }
    public function show_groupmenu($offset=0)
    {
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('welcome/login/','refresh');
        }
        $this->load->library('pagination');
        
        $limit = 5;
        $data['query']= $this->gmmodel->show_groupmenu($limit,(int)$offset);
        $data['page_content'] = 'report';
        $data['page_title']='Report Sales Consolidation';

        $config['base_url'] = site_url('groupmenu/show_groupmenu/');
        $config['total_rows']= $this->gmmodel->getTotalQuery();
        //echo $this->mmodel->getTotalQuery();
        $config['per_page']=$limit;
        $config['uri_segment']=3;
        $this->pagination->initialize($config);
        $data['pagination']=$this->pagination->create_links();
        $data['menu']=$this->db->query($this->querymenu);
        $data['print']=anchor_popup('groupmenu/print_doc/',img($this->image_properties));
        //$data['print']=anchor_popup('groupmenu/print_doc','print');

	$this->template($data['page_content'],$data);
    }
    public function delete_groupmenu($id=null)
    {
        $this->gmmodel->delete_groupmenu($id);
    }
    public function activer($flag=null,$id=null)
    {
        $this->gmmodel->activer($flag,$id);
    }
    private function _comment()
    {
        $this->load->library('form_validation');
        $this->valid = $this->form_validation;
        $this->valid->set_rules('groupname','groupmenu Name','trim|required|min_length[3]|max_length[20]|callback_groupmenuname_check|xss_clean');
        //$this->valid->set_rules('password','Password','trim|required|matches[cpassword]|max_length[8]|md5');
        //$this->valid->set_rules('cpassword','Password Confirmation','trim|required');
        //$this->valid->set_rules('email','Email Address','trim|required|valid_email|callback_email_check');
        return ($this->form_validation->run()==FALSE)?FALSE:TRUE;
    }

}

?>

