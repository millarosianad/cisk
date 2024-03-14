<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Report extends MY_Controller
{
    function Report()
    {
        $this->load->library(array('table','email','template'));
        $this->load->helper('url');
        $this->load->model('report_model','rmodel');
        $this->querymenu = 'select a.id,a.menuview,a.target,a.groupname from mpm.menu a inner join mpm.menudetail b on a.id=b.menuid where b.userid='.$this->session->userdata('id').' and active=1 order by a.groupname,menuview';
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
       $logged_in= $this->session->userdata('logged_in');
       if(!isset($logged_in) || $logged_in != TRUE)
       {
            redirect('login/','refresh');
       }
    }
  
    function outcast($state='showsales',$key=0,$id=0,$init=0)
    {
        $this->auth();
        $data['page_content'] = 'report/outcast';
        $data['page_title']='PIVOT TABLE';
        $data['menu']=$this->db->query($this->querymenu);
        $data['state']=$state;
        $data['keyword']=$this->input->post('keyword');
        switch($state)
        {
            case 'showsales':
            {
               $data['url']=site_url('report/outcast/dp');
               $data['query']=$this->rmodel->list_dp();
            }break;
            case 'dp':
            {
                $data['query']=$this->rmodel->outcast('dp');
                $data['page_content'] = 'report/pivot_outcast';
            }break;
        }
        $this->template($data['page_content'],$data);
    }
    function analisa($state='',$key=0,$id=0,$init=0)
    {
        $this->auth();
        $data['page_content'] = 'report/report';
        $data['page_title']='PIVOT TABLE';
        $data['menu']=$this->db->query($this->querymenu);
        $data['state']=$state;
        $data['keyword']=$this->input->post('keyword');
        switch($state)
        {
            case 'showsales':
            {
               $data['url']=site_url('report/analisa/switcher');
               //$data['query']=$this->rmodel->list_product();
            }break;
            case 'switcher':
            {
                switch($this->input->post('pilih'))
                {
                    case 'dp':  return $this->analisa('dp');break;
                    case 'bsp': return $this->analisa('bsp');break;
                    case 'permen':return $this->analisa('permen');break;
                }
            }break;
            case 'dp':
            {
                $data['query']=$this->rmodel->analisa('dp');
                $data['page_content'] = 'report/pivot_dp';
            }break;
            case 'permen':
            {
                $data['query']=$this->rmodel->analisa('permen');
                $data['page_content'] = 'report/pivot_permen';
            }break;
            case 'bsp':
            {
                $data['query']=$this->rmodel->analisa('bsp');
                $data['page_content'] = 'report/pivot_bsp';
            }break;
        }
        $this->template($data['page_content'],$data);
    }
    function analisapermen($state='',$key=0,$id=0,$init=0)
    {
        $this->auth();
        $data['page_content'] = 'report/report';
        $data['page_title']='PIVOT TABLE';
        $data['menu']=$this->db->query($this->querymenu);
        $data['state']=$state;
        $data['keyword']=$this->input->post('keyword');
        switch($state)
        {
            case 'showsales':
            {
               $data['url']=site_url('report/analisapermen/switcher');
               //$data['query']=$this->rmodel->list_product();
            }break;
            case 'switcher':
            {
                switch($this->input->post('pilih'))
                {
                    case 'dp':  return $this->analisapermen('dp');break;
                    case 'bsp': return $this->analisapermen('bsp');break;
                    case 'permen':return $this->analisapermen('permen');break;
                }
            }break;
            case 'dp':
            {
                $data['query']=$this->rmodel->analisapermen('dp');
                $data['page_content'] = 'report/pivot_dp';
            }break;
            case 'permen':
            {
                $data['query']=$this->rmodel->analisapermen('permen');
                $data['page_content'] = 'report/pivot_permen';
            }break;
            case 'bsp':
            {
                $data['query']=$this->rmodel->analisapermen('bsp');
                $data['page_content'] = 'report/pivot_bsp';
                
            }break;
        }
        $this->template($data['page_content'],$data);
    }
}
?>
