<?php

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Assetdp extends MY_Controller
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
    var $image_add = array(
                 'src'    => 'assets/css/images/ADD.png',
                 'height' => '30',
            );
    var $querymenu='';
    ////'select menuview,target,groupname from mpm.menu where active = 1 order by groupname';

    function Assetdp()
    {
        $this->load->library(array('table','template'));
        $this->load->helper('url');
        $this->load->model('assetdp_model','adpmodel');
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
       $this->auth();
       $this->input_asset();
    }
    function input_grupasset()
    {
       $this->auth();
       $data['page_title'] = 'Input Grup Asset';
       $data['menu']=$this->db->query($this->querymenu);
       $data['page_content']='assetdp/form_grupasset';
       $data['url'] = site_url('assetdp/save_grupasset/');
      
       $this->template($data['page_content'],$data);
    }
    function input_asset()
    {
       $this->auth();
       $data['page_title'] = 'Input Asset';
       $data['menu']=$this->db->query($this->querymenu);
       $data['page_content']='assetdp/form_asset';
       $data['url'] = site_url('assetdp/save_asset/');
       $data['query']=$this->adpmodel->getGrupassetcombo();
       $data['query2']=$this->adpmodel->getKotacombo();
       $this->template($data['page_content'],$data);
    }
    public function save_asset()
    {
       $this->auth();
       $this->adpmodel->save_asset($this->session->userdata('id'));
    }
    public function delete_asset($id=null)
    {
       $this->auth();
       $this->adpmodel->delete_asset($id);
    }
    public function delete_grupasset($id=null)
    {
       $this->auth();
       $this->adpmodel->delete_grupasset($id);
    }
    public function update_asset($id=null)
    {
       $this->auth();
       $this->adpmodel->update_asset($id);
    }
    public function update_grupasset($id=null)
    {
       $this->auth();
       $this->adpmodel->update_grupasset($id);
    }

    public function edit_asset($id=null)
    {
       $this->auth();
       $data['page_title'] = 'Edit Asset';
       $data['page_content']='assetdp/form_asset';
       $data['url'] = site_url('assetdp/update_asset/'.$id);
       $data['menu']=$this->db->query($this->querymenu);
       $data['edit']=$this->adpmodel->getAsset($id);
       $data['query']=$this->adpmodel->getGrupassetcombo();
       $data['query2']=$this->adpmodel->getKotacombo();
       $this->template($data['page_content'],$data);
    }
    public function edit_grupasset($id=null)
    {
       $this->auth();
       $data['page_title'] = 'EDIT GRUP ASSET';
       $data['page_content']='assetdp/form_grupasset';
       $data['url'] = site_url('assetdp/update_grupasset/'.$id);
       $data['menu']=$this->db->query($this->querymenu);
       $data['edit']=$this->adpmodel->getGrupasset($id);
       //$data['query']=$this->adpmodel->getGrupasset();
       $this->template($data['page_content'],$data);
    }
    public function print_asset_dialog()
    {
        $this->auth();
        $data['page_title'] = 'Print Asset';
        $data['page_content']='assetdp/form_print_asset';
        $data['uri_asset'] = site_url('assetdp/print_asset/');
        $data['uri_asset_jual'] = site_url('assetdp/print_asset_jual/');
        $data['menu']=$this->db->query($this->querymenu);
        //$data['query']=$this->adpmodel->print_asset($id);
        $data['query']=$this->adpmodel->getGrupassetcombo();
        $data['query2']=$this->adpmodel->getKotacombo();
        $this->template($data['page_content'],$data);
    }
    public function print_asset_tahun_dialog()
    {
        $this->auth();
        $data['page_title'] = 'Print Asset Pertahun';
        $data['page_content']='assetdp/form_print_asset_tahun';
        $data['url'] = site_url('assetdp/print_asset_tahun/');
        $data['menu']=$this->db->query($this->querymenu);
        //$data['query']=$this->adpmodel->print_asset_tahun($id);
        $data['query']=$this->adpmodel->getGrupassetcombo();
        $this->template($data['page_content'],$data);
    }
    public function print_asset_jual_dialog()
    {
        $this->auth();
        $data['page_title'] = 'Print Asset';
        $data['page_content']='assetdp/form_print_asset_jual';
        $data['url'] = site_url('assetdp/print_asset_jual/');
        $data['menu']=$this->db->query($this->querymenu);
        //$data['query']=$this->adpmodel->print_asset($id);
        $data['query']=$this->adpmodel->getGrupassetcombo();
        $this->template($data['page_content'],$data);
    }
    public function print_asset()
    {
        $this->auth();
        return $this->adpmodel->print_asset();
    }
    public function print_asset_tahun()
    {
        $this->auth();
        return $this->adpmodel->print_asset_tahun();
    }
    public function print_asset_jual()
    {
        $this->auth();
        return $this->adpmodel->print_asset_jual();
    }
    public function show_asset($offset=0)
    {
        $this->auth();
        
        $this->load->library('pagination');

        $limit = 10;
        $data['page_content'] = 'report';
        $data['page_title']='List Of Asset';
        $data['query']= $this->adpmodel->show_asset($limit,(int)$offset,$this->input->post('keyword'));
        $data['add']=anchor('assetdp/input_asset/',img($this->image_add,'ADD'));
        //$data['print_pdf']= anchor_popup('asset/print_asset/'.$year.'/pdf',img($this->image_properties_pdf));
        //$data['print_excel']= anchor_popup('asset/print_omzet/'.$year.'/excel',img($this->image_properties_excel));
        $config['base_url'] = site_url('assetdp/show_asset/');
        $config['total_rows']=$this->adpmodel->getTotalQuery();
        $config['per_page']=$limit;
        $config['uri_segment']=3;

        $this->pagination->initialize($config);

        $data['menu']=$this->db->query($this->querymenu);
        $data['pagination']=$this->pagination->create_links();
        $data['uri']=site_url('assetdp/show_asset');
        $data['keyword']=$this->input->post('keyword');
        $this->template($data['page_content'],$data);
    }
    public function show_grupasset($offset=0)
    {
        $this->auth();
        $this->load->library('pagination');

        $limit = 10;
        $data['page_content'] = 'report';
        $data['page_title']='LIST GRUP ASSET';
        $data['query']= $this->adpmodel->show_grupasset($limit,(int)$offset);
        $data['add']=anchor('assetdp/input_grupasset/',img($this->image_add,'ADD'));
        //$data['print_pdf']= anchor_popup('asset/print_omzet/'.$year.'/pdf',img($this->image_properties_pdf));
        //$data['print_excel']= anchor_popup('asset/print_omzet/'.$year.'/excel',img($this->image_properties_excel));
        $config['base_url'] = site_url('assetdp/show_grupasset/');
        $config['total_rows']=$this->adpmodel->getTotalQuery();
        $config['per_page']=$limit;
        $config['uri_segment']=3;

        $this->pagination->initialize($config);

        $data['menu']=$this->db->query($this->querymenu);
        $data['pagination']=$this->pagination->create_links();
        $this->template($data['page_content'],$data);
    }
    function save_grupasset()
    {
       $this->auth();
       $this->adpmodel->save_grupasset($this->session->userdata('id'));
    }
    
}

?>