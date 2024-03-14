<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Aam extends MY_Controller
{
    var $nocab;
    var $options;
    var $image_properties_pdf = array(
          'src' => 'assets/css/images/pdf.png',
          'alt' => 'Printing Document',
          'class' => 'post_images',
          'width' => '50',
          'height' => '50',
          'title' => 'Printer',
          'border' => 0,
    );
    var $image_properties_excel = array(
          'src' => 'assets/css/images/excel.png',
          'alt' => 'Printing Document',
          'class' => 'post_images',
          'width' => '50',
          'height' => '50',
          'title' => 'Printer',
          'border' => 0,
    );
    var $querymenu;
    var $attr = array(
              'width'      => '800',
              'height'     => '600',
              'scrollbars' => 'yes',
              'status'     => 'yes',
              'resizable'  => 'yes',
              'screenx'   =>  '\'+((parseInt(screen.width) - 800)/2)+\'',
              'screeny'   =>  '\'+((parseInt(screen.height) - 600)/2)+\'',

            );
    function Aam()
    {
        set_time_limit(0);
        $this->load->library(array('table','template'));
        $this->load->helper('url');
        //$this->load->model('basic_model','bmodel');
        $this->load->model('aam_model','aammodel');
        $this->load->database();
        $this->querymenu='select a.id,a.menuview,a.target,a.groupname from mpm.menu a inner join mpm.menudetail b on a.id=b.menuid where b.userid='. $this->session->userdata('id') . ' and active=1 order by a.groupname,a.menuview ';
    }
    private function template($view,$data)
    {
        $this->template->set_title('MPM SQUARE');
        $this->template->add_js('modules/skeleton.js');
        $this->template->add_css('modules/skeleton.css');
        $this->template->load_view($view, $data);
    }
    function vessel($num)
    {
        $this->auth();
        $dp=$this->input->post('nocab');
        $year=$this->input->post('year');
        $code=$this->input->post('kodeprod');
        $options=$this->input->post('options');
        $format=$this->input->post('format');
        switch($num)
        {
            case 1:
            {
                $this->aammodel->vessel(1);
                
                switch($format)
                {
                    case '1' :
                    $this->sales_aam($year);break;
                    case '2' :
                    $this->print_aam($year,'PDF');break;
                    case '3' :
                    $this->print_aam($year,'EXCEL');break;
                }break;
            }break;
            case 2:
            {
                $this->aammodel->vessel(2);
                foreach($options as $kode)
                {
                    $code.=",".'"'.$kode.'"';
                }
                $code=preg_replace('/,/', '', $code,1);
                $dp='"'.$dp.'"';
                switch($format)
                {
                case '1' :
                    $this->sales_outlet_aam(0,$year,$code,$dp);break;
                case '2' :
                    $this->sales_outlet_aam_pdf($year,$code,$dp);break;
                case '3' :
                    $this->sales_outlet_aam_excel($year,$code,$dp);break;
                }

            }break;
            
            case 5:
            {

                $this->aammodel->vessel(5);
                foreach($options as $kode)
                {
                    //$code.=",'".$kode."'";
                    $code.=",".'"'.$kode.'"';
                }
                $code=preg_replace('/,/', '', $code,1);

                switch($format)
                {
                case '1' :
                    $this->sales_per_product_aam($year,$code,$dp);break;
                case '2' :
                    $this->print_per_product_aam($year,$code,'PDF');break;
                case '3' :
                    $this->print_per_product_aam($year,$code,'EXCEL');break;
                }
            }break;
           /*
            case 3:
            {
                $this->aammodel->vessel(3);
                $this->sales_omzet($year);break;
            }
            case 4:
            {
                $this->aammodel->vessel(4);
                $this->sales_dp($year);break;
            }
            
            case 6:
            {
                $this->aammodel->vessel(6);
                $this->buy_per_dp($dp,$year);break;
            }*/
        }

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
    function show_option_aam()
    {
        $this->auth();
        $data['page_content'] = 'sales/form_year';
        $data['page_title']='Sales aam';
        $data['url'] = 'aam/vessel/1';
        $data['menu']=$this->db->query($this->querymenu);
        $this->template($data['page_content'],$data);
    }
    function show_option_product_checkbox_aam()
    {
        $this->auth();
        $data['page_content'] = 'aam/form_product_year_checkbox';
        $data['url']='aam/vessel/2';
        $data['page_title']='Sales Outlet aam';
        $data['query2']=$this->aammodel->listaam();
        $data['query']=$this->aammodel->listProduct();
        $data['menu']=$this->db->query($this->querymenu);
        $this->template($data['page_content'],$data);
    }
    function show_option_product_aam()
    {
        $this->auth();
        $data['page_content'] = 'aam/form_product_year';
        $data['url']='aam/vessel/5';
        $data['page_title']='Sales Out Per Product AAM';
        $data['query']=$this->aammodel->listProduct();
        $data['menu']=$this->db->query($this->querymenu);
        $this->template($data['page_content'],$data);
    }
    function print_aam($year=null,$file=null)
    {
        $this->auth();
        $this->aammodel->print_aam($year,$file);
    }
    function sales_outlet_aam_pdf($year=null,$code=null,$dp=null)
    {
        $this->auth();
        $this->aammodel->sales_outlet_aam_pdf($year,$code,$dp);
    }
    function sales_outlet_aam_excel($year=null,$code=null,$dp=null)
    {
        $this->auth();
        $this->aammodel->sales_outlet_aam_excel($year,$code,$dp);
    }
    function sales_aam($year=null,$offset=null)
    {
        $this->auth();
        $this->load->library('pagination');
        $limit = 20;
        $data['page_content'] = 'report';
        $data['page_title']='Sell Out aam';
        $data['query']= $this->aammodel->sales_aam($limit,(int)$offset,$year);
        //$data['print_pdf']= anchor_popup('aam/print_aam/'.$year.'/pdf',img($this->image_properties_pdf));
        //$data['print_excel']= anchor_popup('aam/print_aam/'.$year.'/excel',img($this->image_properties_excel));
        $config['base_url'] = site_url('aam/sales_aam/'.$year);
        $config['total_rows']=$this->aammodel->getTotalQuery();
        $config['per_page']=$limit;
        $config['uri_segment']=4;

        $this->pagination->initialize($config);

        $data['menu']=$this->db->query($this->querymenu);
        $data['pagination']=$this->pagination->create_links();

	$this->template($data['page_content'],$data);
    }
    function sales_per_product_aam($year=null,$kode=null,$offset=null)
    {
        $this->auth();
        $this->load->library('pagination');
        $limit = 20;
        $data['page_content'] = 'report';
        $data['page_title']='Sell Out aam';
        $data['query']= $this->aammodel->sales_per_product_aam($limit,(int)$offset,$year,$kode);
        //$data['print_pdf']= anchor_popup('aam/print_aam/'.$year.'/pdf',img($this->image_properties_pdf));
        //$data['print_excel']= anchor_popup('aam/print_aam/'.$year.'/excel',img($this->image_properties_excel));
        $config['base_url'] = site_url('aam/sales_per_product_aam/'.$year.'/'.$kode);
        $config['total_rows']=$this->aammodel->getTotalQuery();
        $config['per_page']=$limit;
        $config['uri_segment']=5;

        $this->pagination->initialize($config);

        $data['menu']=$this->db->query($this->querymenu);
        $data['pagination']=$this->pagination->create_links();

	$this->template($data['page_content'],$data);
    }
    function print_per_product_aam($year=null,$code=null,$format=null)
    {
        $this->auth();
        $this->aammodel->print_per_product_aam($year,$code,$format);
    }
    function sales_outlet_aam($offset=0,$year=null,$code=null,$dp=null,$format=null)
    {
        $this->auth();
        $limit = 20;
        $data['page_content'] = 'report';
        $data['page_title']='Sell Outlet aam';
        $data['query'] = $this->aammodel->sales_outlet_aam((int)$offset,$year,$code,$dp,$format);
        $config['base_url'] = site_url('aam/sales_outlet_aam/');//.$year.'/'.$code.'/'.$dp);
        $config['total_rows']=$this->aammodel->getTotalQuery();
        $config['uri_segment']=3;
        $config['per_page']=$limit;

        $this->pagination->initialize($config);

        $data['menu']=$this->db->query($this->querymenu);
        $data['pagination']=$this->pagination->create_links();
        $data['sum']=$this->aammodel->getTotalQuery();
	$this->template($data['page_content'],$data);
    }
}
?>