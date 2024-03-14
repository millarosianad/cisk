<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Bsp extends MY_Controller
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
    function Bsp()
    {
        set_time_limit(0);
        $this->load->library(array('table','template'));
        $this->load->helper('url');
        //$this->load->model('basic_model','bmodel');
        $this->load->model('bsp_model','bspmodel');
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
                $this->bspmodel->vessel(1);
                
                switch($format)
                {
                    case '1' :
                    $this->sales_bsp($year);break;
                    case '2' :
                    $this->print_bsp($year,'PDF');break;
                    case '3' :
                    $this->print_bsp($year,'EXCEL');break;
                }break;
            }break;
            case 2:
            {
                $this->bspmodel->vessel(2);
                foreach($options as $kode)
                {
                    $code.=",".'"'.$kode.'"';
                }
                $code=preg_replace('/,/', '', $code,1);
                $dp='"'.$dp.'"';
                switch($format)
                {
                case '1' :
                    $this->sales_outlet_bsp(0,$year,$code,$dp);break;
                case '2' :
                    $this->sales_outlet_bsp_pdf($year,$code,$dp);break;
                case '3' :
                    $this->sales_outlet_bsp_excel($year,$code,$dp);break;
                }

            }break;
            case 3:
            {
                $this->bspmodel->vessel(3);

                switch($format)
                {
                    case '1' :
                    $this->stok_bsp($year);break;
                    case '2' :
                    $this->print_stok_bsp($year,'PDF');break;
                    case '3' :
                    $this->print_stok_bsp($year,'EXCEL');break;
                }
            }break;
            case 4:
            {
                $this->bspmodel->vessel(4);
                foreach($options as $kode)
                {
                    $code.=",".'"'.$kode.'"';
                }
                $code=preg_replace('/,/', '', $code,1);
                switch($format)
                {
                case '1' :
                    $this->stok_produk_bsp(0,$year,$code);break;
                case '2' :
                    $this->sales_stok_produk_bsp_pdf($year,$code);break;
                case '3' :
                    $this->sales_stok_produk_bsp_excel($year,$code);break;
                }

            }break;
            
            case 5:
            {

                $this->bspmodel->vessel(5);
                foreach($options as $kode)
                {
                    $code.=",".'"'.$kode.'"';
                }
                $code=preg_replace('/,/', '', $code,1);
                switch($format)
                {
                    case '1' :
                        $this->sales_produk_bsp(0,$year,$code);break;
                    case '2' :
                        $this->sales_produk_bsp_pdf($year,$code);break;
                    case '3' :
                        $this->sales_produk_bsp_excel($year,$code);break;
                }
            }break;
        
            case 6:
            {

                $this->bspmodel->vessel(6);
               
                switch($format)
                {
                    case '1' :
                        $this->sales_omzet_bsp($year);break;
                    case '2' :
                        $this->sales_omzet_bsp_pdf($year);break;
                    case '3' :
                        $this->sales_omzet_bsp_excel($year);break;
                }
            }break;
            /*
            case 3:
            {
                $this->bspmodel->vessel(3);
                $this->sales_omzet($year);break;
            }
            case 4:
            {
                $this->bspmodel->vessel(4);
                $this->sales_dp($year);break;
            }
            
            case 6:
            {
                $this->bspmodel->vessel(6);
                $this->buy_per_dp($dp,$year);break;
            }*/
        }

    }
    function show_option_bsp()
    {
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('welcome/login/','refresh');
        }
        $data['page_content'] = 'sales/form_year';
        $data['page_title']='Sales BSP';
        $data['url'] = 'bsp/vessel/1';
        $data['menu']=$this->db->query($this->querymenu);
        $this->template($data['page_content'],$data);
    }
    function show_option_omzet()
    {
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('welcome/login/','refresh');
        }
        $data['page_content'] = 'bsp/bsp_omzet';
        $data['page_title']='Omzet BSP';
        $data['url'] = 'bsp/vessel/6';
        $data['menu']=$this->db->query($this->querymenu);
        $this->template($data['page_content'],$data);
    }
    function show_option_stok_bsp()
    {
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('welcome/login/','refresh');
        }
        $data['page_content'] = 'sales/form_year';
        $data['page_title']='Stok BSP Nasional';
        $data['url'] = 'bsp/vessel/3';
        $data['menu']=$this->db->query($this->querymenu);
        $this->template($data['page_content'],$data);
    }
    function show_option_product_checkbox_bsp()
    {
        //$this->load->model('basic_model','bmodel');
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('welcome/login/','refresh');
        }
        $data['page_content'] = 'bsp/form_product_year_checkbox';
        $data['url']='bsp/vessel/2';
        $data['page_title']='Sales Outlet BSP';
        $data['query2']=$this->bspmodel->listbsp();
        $data['query']=$this->bspmodel->listProduct();
        $data['menu']=$this->db->query($this->querymenu);
        $this->template($data['page_content'],$data);
    }
     function show_option_product_checkbox_bsp_permen()
    {
        //$this->load->model('basic_model','bmodel');
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('welcome/login/','refresh');
        }
        $data['page_content'] = 'bsp/form_product_year_checkbox';
        $data['url']='bsp/vessel/2';
        $data['page_title']='Sales Outlet BSP';
        $data['query2']=$this->bspmodel->listbsp();
        $data['query']=$this->bspmodel->listPermen();
        $data['menu']=$this->db->query($this->querymenu);
        $this->template($data['page_content'],$data);
    }
    function show_sales_product_bsp()
    {
        //$this->load->model('basic_model','bmodel');
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('welcome/login/','refresh');
        }
        $data['page_content'] = 'bsp/form_product_year_checkbox_1';
        $data['url']='bsp/vessel/5';
        $data['page_title']='Sales by Product BSP';
        $data['query2']=$this->bspmodel->listbsp();
        $data['query']=$this->bspmodel->listProduct();
        $data['menu']=$this->db->query($this->querymenu);
        $this->template($data['page_content'],$data);
    }
     function show_sales_product_bsp_permen()
    {
        //$this->load->model('basic_model','bmodel');
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('welcome/login/','refresh');
        }
        $data['page_content'] = 'bsp/form_product_year_checkbox_1';
        $data['url']='bsp/vessel/5';
        $data['page_title']='Sales by Product BSP';
        $data['query2']=$this->bspmodel->listbsp();
        $data['query']=$this->bspmodel->listPermen();
        $data['menu']=$this->db->query($this->querymenu);
        $this->template($data['page_content'],$data);
    }
    function show_stok_product_bsp()
    {
        //$this->load->model('basic_model','bmodel');
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('welcome/login/','refresh');
        }
        $data['page_content'] = 'bsp/form_product_year_checkbox_1';
        $data['url']='bsp/vessel/4';
        $data['page_title']='Stock by Product BSP';
        $data['query2']=$this->bspmodel->listbsp();
        $data['query']=$this->bspmodel->listProduct();
        $data['menu']=$this->db->query($this->querymenu);
        $this->template($data['page_content'],$data);
    }
    function print_bsp($year=null,$file=null)
    {
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('welcome/login/','refresh');
        }
        $this->bspmodel->print_bsp($year,$file);
    }
    function sales_outlet_bsp_pdf($year=null,$code=null,$dp=null)
    {
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('welcome/login/','refresh');
        }
        $this->bspmodel->sales_outlet_bsp_pdf($year,$code,$dp);
    }
    function sales_produk_bsp_pdf($year=null,$code=null,$dp=null)
    {
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('welcome/login/','refresh');
        }
        $this->bspmodel->sales_produk_bsp_pdf($year,$code,$dp);
    }
    function sales_produk_bsp_excel($year=null,$code=null,$dp=null)
    {
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('welcome/login/','refresh');
        }
        $this->bspmodel->sales_produk_bsp_excel($year,$code,$dp);
    }
    function sales_outlet_bsp_excel($year=null,$code=null,$dp=null)
    {
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('welcome/login/','refresh');
        }
        $this->bspmodel->sales_outlet_bsp_excel($year,$code,$dp);
    }
    function sales_bsp($year=null,$offset=null)
    {
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('welcome/login/','refresh');
        }
        $this->load->library('pagination');
        $limit = 20;
        $data['page_content'] = 'report';
        $data['page_title']='Sell Out Bsp';
        $data['query']= $this->bspmodel->sales_bsp($limit,(int)$offset,$year);
        //$data['print_pdf']= anchor_popup('bsp/print_bsp/'.$year.'/pdf',img($this->image_properties_pdf));
        //$data['print_excel']= anchor_popup('bsp/print_bsp/'.$year.'/excel',img($this->image_properties_excel));
        $config['base_url'] = site_url('bsp/sales_bsp/'.$year);
        $config['total_rows']=$this->bspmodel->getTotalQuery();
        $config['per_page']=$limit;
        $config['uri_segment']=4;

        $this->pagination->initialize($config);

        $data['menu']=$this->db->query($this->querymenu);
        $data['pagination']=$this->pagination->create_links();

	$this->template($data['page_content'],$data);
    }
     function sales_omzet_bsp($year=null,$offset=null)
    {
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('welcome/login/','refresh');
        }
        $this->load->library('pagination');
        $limit = 20;
        $data['page_content'] = 'report';
        $data['page_title']='Omzet Bsp';
        $data['query']= $this->bspmodel->sales_omzet($limit,(int)$offset,$year);
        //$data['print_pdf']= anchor_popup('bsp/print_bsp/'.$year.'/pdf',img($this->image_properties_pdf));
        //$data['print_excel']= anchor_popup('bsp/print_bsp/'.$year.'/excel',img($this->image_properties_excel));
        $config['base_url'] = site_url('bsp/sales_omzet_bsp/'.$year);
        $config['total_rows']=$this->bspmodel->getTotalQuery();
        $config['per_page']=$limit;
        $config['uri_segment']=4;

        $this->pagination->initialize($config);

        $data['menu']=$this->db->query($this->querymenu);
        $data['pagination']=$this->pagination->create_links();

	$this->template($data['page_content'],$data);
    }
    function sales_omzet_bsp_pdf($year=null)
    {
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('welcome/login/','refresh');
        }
        $this->bspmodel->sales_omzet_pdf($year);
    }
    function sales_omzet_bsp_excel($year=null)
    {
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('welcome/login/','refresh');
        }
        $this->bspmodel->sales_omzet_excel($year);
    }
    function stok_bsp($year=null,$offset=null)
    {
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('welcome/login/','refresh');
        }
        $this->load->library('pagination');
        $limit = 20;
        $data['page_content'] = 'report';
        $data['page_title']='Stok Bsp';
        $data['query']= $this->bspmodel->stok_bsp($limit,(int)$offset,$year);
        //$data['print_pdf']= anchor_popup('bsp/print_bsp/'.$year.'/pdf',img($this->image_properties_pdf));
        //$data['print_excel']= anchor_popup('bsp/print_bsp/'.$year.'/excel',img($this->image_properties_excel));
        $config['base_url'] = site_url('bsp/stok_bsp/'.$year);
        $config['total_rows']=$this->bspmodel->getTotalQuery();
        $config['per_page']=$limit;
        $config['uri_segment']=4;

        $this->pagination->initialize($config);

        $data['menu']=$this->db->query($this->querymenu);
        $data['pagination']=$this->pagination->create_links();

	$this->template($data['page_content'],$data);
    }

    function sales_outlet_bsp($offset=0,$year=null,$code=null,$dp=null,$format=null)
    {
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('welcome/login/','refresh');
        }
        $this->load->library('pagination');

        $limit = 20;
        $data['page_content'] = 'report';
        $data['page_title']='Sell Outlet BSP';
        $data['query'] = $this->bspmodel->sales_outlet_bsp((int)$offset,$year,$code,$dp,$format);
        $config['base_url'] = site_url('bsp/sales_outlet_bsp/');//.$year.'/'.$code.'/'.$dp);
        $config['total_rows']=$this->bspmodel->getTotalQuery();
        $config['uri_segment']=3;
        $config['per_page']=$limit;

        $this->pagination->initialize($config);

        $data['menu']=$this->db->query($this->querymenu);
        $data['pagination']=$this->pagination->create_links();
        $data['sum']=$this->bspmodel->getTotalQuery();
	$this->template($data['page_content'],$data);
    }
    function sales_produk_bsp($offset=0,$year=null,$code=null,$format=null)
    {
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('welcome/login/','refresh');
        }
        $this->load->library('pagination');

        $limit = 22;
        $data['page_content'] = 'report';
        $data['page_title']='Sales by Product BSP';
        $data['query'] = $this->bspmodel->sales_produk_bsp($limit,(int)$offset,$year,$code,$format);
        $config['base_url'] = site_url('bsp/sales_produk_bsp/');//.$year.'/'.$code.'/'.$dp);
        $config['total_rows']=$this->bspmodel->getTotalQuery();
        $config['uri_segment']=3;
        $config['per_page']=$limit;

        $this->pagination->initialize($config);

        $data['menu']=$this->db->query($this->querymenu);
        $data['pagination']=$this->pagination->create_links();
        //$data['sum']=$this->bspmodel->getTotalQuery();
	$this->template($data['page_content'],$data);
    }
    function stok_produk_bsp($offset=0,$year=null,$code=null,$format=null)
    {
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('welcome/login/','refresh');
        }
        $this->load->library('pagination');

        $limit = 22;
        $data['page_content'] = 'report';
        $data['page_title']='Stock by Product BSP';
        $data['query'] = $this->bspmodel->stok_produk_bsp($limit,(int)$offset,$year,$code,$format);
        $config['base_url'] = site_url('bsp/stok_produk_bsp/');//.$year.'/'.$code.'/'.$dp);
        $config['total_rows']=$this->bspmodel->getTotalQuery();
        $config['uri_segment']=3;
        $config['per_page']=$limit;

        $this->pagination->initialize($config);

        $data['menu']=$this->db->query($this->querymenu);
        $data['pagination']=$this->pagination->create_links();
        //$data['sum']=$this->bspmodel->getTotalQuery();
	$this->template($data['page_content'],$data);
    }
}
?>