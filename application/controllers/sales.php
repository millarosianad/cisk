<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Sales extends MY_Controller
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
    function Sales()
    {
        set_time_limit(0);
        $this->load->library(array('table','template'));
        $this->load->helper('url');
        $this->load->model('sales_model','bmodel');
        $this->load->database();
        $this->querymenu='select a.id,a.menuview,a.target,a.groupname from mpm.menu a inner join mpm.menudetail b on a.id=b.menuid where b.userid='. $this->session->userdata('id') . ' and active=1 order by a.groupname,a.menuview ';

    }
    function index()
    {
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
    }
    private function template($view,$data)
    {
        $this->template->set_title('MPM SQUARE');
        $this->template->add_js('modules/skeleton.js');
        $this->template->add_css('modules/skeleton.css');
        $this->template->load_view($view, $data);
    }
    function show_option_konsolidasi()
    {

        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
        $data['page_content'] = 'sales/form_year';
        $data['page_title']='Sales Consolidation';
        $data['url'] = 'sales/sales_konsolidasi';
        $data['menu']=$this->db->query($this->querymenu);
        $this->template($data['page_content'],$data);
    }
    function show_option_dp_all()
    {
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
        $data['page_content'] = 'sales/form_year';
        $data['page_title']='Sales DP All';
        $data['url'] = 'sales/vessel/4';
        $data['menu']=$this->db->query($this->querymenu);
        $this->template($data['page_content'],$data);
    }
    //Tambahan Tizar
     function show_option_dp_barat()
    {
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
        $data['page_content'] = 'sales/form_year';
        $data['page_title']='Sales DP Barat';
        $data['url'] = 'sales/vessel/9';
        $data['menu']=$this->db->query($this->querymenu);
        $this->template($data['page_content'],$data);
    }
    //Tambahan Tizar
     function show_option_dp_timur()
    {
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
        $data['page_content'] = 'sales/form_year';
        $data['page_title']='Sales DP Timur';
        $data['url'] = 'sales/vessel/10';
        $data['menu']=$this->db->query($this->querymenu);
        $this->template($data['page_content'],$data);
    }
    function show_option_bsp()
    {
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
        $data['page_content'] = 'sales/form_year';
        $data['page_title']='Sales BSP';
        $data['url'] = 'sales/sales_bsp';
        $data['menu']=$this->db->query($this->querymenu);
        $this->template($data['page_content'],$data);
    }
    function show_raw()
    {
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
        $data['page_content'] = 'sales/form_raw';
        $data['url']='sales/raw';
        $data['page_title']='DOWNLOAD RAW DATA';
        $data['query']=$this->bmodel->getSuppbyid();
        $data['menu']=$this->db->query($this->querymenu);
        $this->template($data['page_content'],$data);
    }
    function show_option_dp()
    {
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
        $data['page_content'] = 'sales/form_dp_year';
        $data['url']='sales/vessel/2';
        $data['page_title']='Sales DP';
        $data['query']=$this->bmodel->list_dp();
        $data['menu']=$this->db->query($this->querymenu);
        $this->template($data['page_content'],$data);
    }
    function show_option_buy_dp()
    {
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
        $data['page_content'] = 'sales/form_dp_year';
        $data['url']='sales/vessel/6';
        $data['page_title']='Sales In DP';
        $data['query']=$this->bmodel->list_dp();
        $data['menu']=$this->db->query($this->querymenu);
        $this->template($data['page_content'],$data);
    }
    function show_option_omzet()
    {
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
        //$data['map']=$this->bmodel->peta();
        $data['page_content'] = 'sales/form_omzet';
        $data['url']='sales/vessel/3';
        $data['page_title']='Sales Omzet';
        $data['query']=$this->bmodel->getSuppbyid();
        $data['menu']=$this->db->query($this->querymenu);
        $this->template($data['page_content'],$data);
    }
    function show_option_omzet_barat()
    {
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
        //$data['map']=$this->bmodel->peta();
        $data['page_content'] = 'sales/form_omzet';
        $data['url']='sales/vessel/31';
        $data['page_title']='Sales Omzet Barat';
        $data['query']=$this->bmodel->getSuppbyid();
        $data['menu']=$this->db->query($this->querymenu);
        $this->template($data['page_content'],$data);
    }
    function show_option_omzet_timur()
    {
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
        //$data['map']=$this->bmodel->peta();
        $data['page_content'] = 'sales/form_omzet';
        $data['url']='sales/vessel/32';
        $data['page_title']='Sales Omzet Timur';
        $data['query']=$this->bmodel->getSuppbyid();
        $data['menu']=$this->db->query($this->querymenu);
        $this->template($data['page_content'],$data);
    }
    function show_option_product()
    {
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
        //$this->load->model('basic_model','bmodel');
        $data['page_content'] = 'sales/form_product_year';
        $data['url']='sales/vessel/1';
        $data['page_title']='Sales Per Product';
        $data['query']=$this->bmodel->list_product();
        $data['menu']=$this->db->query($this->querymenu);
        $this->template($data['page_content'],$data);
    }
    function show_option_product_permen()
    {
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
        //$this->load->model('basic_model','bmodel');
        $data['page_content'] = 'sales/form_product_year';
        $data['url']='sales/vessel/1';
        $data['page_title']='Sales Per Product';
        $data['query']=$this->bmodel->list_product_permen();
        $data['menu']=$this->db->query($this->querymenu);
        $this->template($data['page_content'],$data);
    }
    function show_option_product_checkbox()
    {
        //$this->load->model('basic_model','bmodel');
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
        $data['page_content'] = 'sales/form_product_year_checkbox';
        $data['url']='sales/vessel/5';
        $data['page_title']='Sales Outlet';
        //$data['query2']=$this->bmodel->list_dp();
        $data['query2']=$this->bmodel->list_dp_outlet();
        $data['query']=$this->bmodel->list_product();
        $data['menu']=$this->db->query($this->querymenu);
        $this->template($data['page_content'],$data);
    }
    function show_option_product_checkbox_permen()
    {
        //$this->load->model('basic_model','bmodel');
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
        $data['page_content'] = 'sales/form_product_year_checkbox';
        $data['url']='sales/vessel/5';
        $data['page_title']='Sales Outlet';
        $data['query2']=$this->bmodel->list_dp();
        $data['query']=$this->bmodel->list_product_permen();
        $data['menu']=$this->db->query($this->querymenu);
        $this->template($data['page_content'],$data);
    }
    function buildSalesName()
    {
        //$this->load->model('basic_model','bmodel');
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
        $dp = $this->input->post('id',TRUE);
        $output="<option value=''>ALL</option>";
        $query=$this->bmodel->getSalesName($dp);
        foreach($query->result() as $row)
        {
            $output .= "<option value='".$row->kodesales."'>".$row->namasales."</option>";
        }
        //$output="<option value=''>".$dp."</option>";
        echo $output;
    }
    function vessel($num)
    {
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
        $dp=$this->input->post('nocab');
        $year=$this->input->post('year');
        $code=$this->input->post('kodeprod');
        $options=$this->input->post('options');
        $format=$this->input->post('format');
        switch($num)
        {
            case 1:
            {
                $this->bmodel->vessel(1);
                foreach($options as $kode)
                {
                    $code.=",".$kode;
                }
                $code=preg_replace('/,/', '', $code,1);

                switch($format)
                {
                    case '1':
                    $this->sales_per_product($year,$code);break;
                    case '5':
                    $this->sales_per_product($year,$code,'RETUR');break;
                    case '2':
                    $this->print_per_product($code,$year,'PDF');break;
                    case '3':
                    $this->print_per_product($code,$year,'EXCEL');break;
                    case '4':
                    $this->print_per_product($code,$year,'GRAFIK');break;
                }
                break;
            }
            case 2:
            {
                $this->bmodel->vessel(2);
                //$this->sales_per_dp($dp,$year);break;
                switch($format)
                {
                    case '1':
                    $this->sales_per_dp($dp,$year);break;
                    case '5':
                    $this->sales_per_dp($dp,$year,0,true);break;
                    case '2':
                    $this->print_per_dp($dp,$year,'PDF');break;
                    case '3':
                    $this->print_per_dp($dp,$year,'EXCEL');break;
                    case '4':
                    $this->print_per_dp($dp,$year,'GRAFIK');break;
                }
                break;
            }
            case 3:
            {
                $this->bmodel->vessel(3);
                switch($format)
                {
                    case '1':
                    $this->sales_omzet($year);break;
                    case '5':
                    $this->sales_omzet($year,0,true);break;
                    case '2':
                    $this->print_omzet($year,'PDF');break;
                    case '3':
                    $this->print_omzet($year,'EXCEL');break;
                    case '4':
                    $this->print_omzet($year,'GRAFIK');break;
                    case '6':
                    $this->peta();break;
                }
                break;
            }
            case 31:
            {
                $this->bmodel->vessel(3);
                switch($format)
                {
                    case '1':
                    $this->sales_omzet_barat($year);break;
                    case '5':
                    $this->sales_omzet_barat($year,0,true);break;
                    case '2':
                    $this->print_omzet_barat($year,'PDF');break;
                    case '3':
                    $this->print_omzet_barat($year,'EXCEL');break;
                    case '4':
                    $this->print_omzet_barat($year,'GRAFIK');break;
                    case '6':
                    $this->peta();break;
                }
                break;
            }
            case 32:
            {
                $this->bmodel->vessel(3);
                switch($format)
                {
                    case '1':
                    $this->sales_omzet_timur($year);break;
                    case '5':
                    $this->sales_omzet_timur($year,0,true);break;
                    case '2':
                    $this->print_omzet_timur($year,'PDF');break;
                    case '3':
                    $this->print_omzet_timur($year,'EXCEL');break;
                    case '4':
                    $this->print_omzet_timur($year,'GRAFIK');break;
                    case '6':
                    $this->peta();break;
                }
                break;
            }
            case 4:
            {
                $this->bmodel->vessel(4);
                switch($format)
                {
                    case '1':
                    $this->sales_dp($year);break;
                    case '-1':
                    $this->sales_dp($year,true);break;
                    case '2':
                    $this->print_dp($year,'PDF');break;
                    case '3':
                    $this->print_dp($year,'EXCEL');break;
                }
                break;
            }
            case 5:
            {
                $this->bmodel->vessel(5);
                foreach($options as $kode)
                {
                    $code.=",".$kode;
                }
                $code=preg_replace('/,/', '', $code,1);
                switch($format)
                {
                    case '1':
                    $this->sales_outlet($year,$code,$dp);break;
                    case '-1':
                    $this->sales_outlet($year,$code,$dp,true);break;
                    case '2':
                    $this->print_outlet($year,$code,$dp,'PDF');break;
                    case '3':
                    $this->print_outlet($year,$code,$dp,'EXCEL');break;
                }
                break;

            }
            case 6:
            {
                $this->bmodel->vessel(6);
                //$this->buy_per_dp($dp,$year);break;
                switch($format)
                {
                    case '1':
                    $this->buy_per_dp($dp,$year);break;
                    case '-1':
                    $this->buy_per_dp($dp,$year,true);break;
                    case '2':
                    $this->print_buy_per_dp($dp,$year,'PDF');break;
                    case '3':
                    $this->print_buy_per_dp($dp,$year,'EXCEL');break;
                }
                break;
            }
            case 9:
            {
                $this->bmodel->vessel(9);
                switch($format)
                {
                    case '1':
                    $this->sales_dp_barat($year);break;
                    case '-1':
                    $this->sales_dp_barat($year,true);break;
                    case '2':
                    $this->print_dp_barat($year,'PDF');break;
                    case '3':
                    $this->print_dp_barat($year,'EXCEL');break;
                }
                break;
            }
            case 10:
            {
                $this->bmodel->vessel(10);
                switch($format)
                {
                    case '1':
                    $this->sales_dp_timur($year);break;
                    case '-1':
                    $this->sales_dp_timur($year,true);break;
                    case '2':
                    $this->print_dp_timur($year,'PDF');break;
                    case '3':
                    $this->print_dp_timur($year,'EXCEL');break;
                }
                break;
            }

            case 11:
            {
                $this->bmodel->vessel(11);
                switch($format)
                {
                    case '1':
                    $this->sell_out_permen($year);break;
                    case '5':
                    $this->sell_out_permen($year,0,true);break;
                    case '2':
                    $this->print_sell_out_permen($year,'PDF');break;
                    case '3':
                    $this->print_sell_out_permen($year,'EXCEL');break;
                    case '4':
                    $this->print_sell_out_permen($year,'GRAFIK');break;
                    case '6':
                    $this->peta();break;
                }
                break;
            }
        }

    }
    function print_konsolidasi($year=null)
    {
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
        echo $this->bmodel->print_konsolidasi($year);
    }
    function sales_konsolidasi($offset=0)
    {
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
        $this->load->library('pagination');
        //$this->load->model('basic_model','bmodel');
        $limit = 15;
        if($this->input->post('year'))
        {
            $data['query']= $this->bmodel->sales_konsolidasi($limit,(INT)$offset,$this->input->post('year'));
            $this->session->set_flashdata('year',$this->input->post('year'));
            $data['print_pdf']=anchor_popup('sales/print_konsolidasi/'.$this->input->post('year'),img($this->image_properties_pdf));
        }

        else
        {
            $data['query']= $this->bmodel->sales_konsolidasi($limit,(INT)$offset,$this->session->flashdata('year'));
            $this->session->keep_flashdata('year');
            $data['print_pdf']=anchor_popup('sales/print_konsolidasi/'.$this->session->flashdata('year'),img($this->image_properties_pdf));
        }

        $data['page_content'] = 'report';
        $data['page_title']='Report Sales Consolidation';
        //$data['query']= $this->bmodel->get_list_bsp($limit,(INT)$offset);

        $config['base_url'] = site_url('sales/sales_konsolidasi/');
        $config['total_rows']=$this->bmodel->getTotalQuery();
        $config['per_page']=$limit;

        $this->pagination->initialize($config);
        $data['pagination']=$this->pagination->create_links();

        if(!is_null($offset))
        {
            $offset = $this->uri->segment(5);
        }

	$this->template($data['page_content'],$data);
    }
    function print_per_product($kodeprod=null,$year=null,$file=null)
    {
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
        echo $this->bmodel->print_per_product($kodeprod,$year,$file);
    }
    function sales_per_product($year=null,$code=null,$offset=0,$retur=false)
    {
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }

        $this->load->library('pagination');

        $limit = 24; //$25
        $data['page_content'] = 'report';
        $data['page_title']='Sell Out By Product';
        $data['query']= $this->bmodel->sales_per_product($limit,(int)$offset,$code,$year,$retur);
        //$data['print_pdf']=anchor_popup('sales/print_per_product/'.$code.'/'.$year.'/pdf',img($this->image_properties_pdf));
        //$data['print_excel']=anchor_popup('sales/print_per_product/'.$code.'/'.$year.'/excel',img($this->image_properties_excel));
        $config['base_url'] = site_url('sales/sales_per_product/'.$year.'/'.$code);
        $config['total_rows']=$this->bmodel->getTotalQuery();
        $config['per_page']=$limit;
        $config['uri_segment']=5;

        $this->pagination->initialize($config);

        $data['menu']=$this->db->query($this->querymenu);
        $data['pagination']=$this->pagination->create_links();

	$this->template($data['page_content'],$data);

    }
    function print_outlet($year=null,$kodeprod=null,$dp=null,$file=null)
    {
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
        echo $this->bmodel->print_outlet($year,$kodeprod,$dp,$file);
    }
    function sales_outlet($year=null,$code=null,$dp=null,$offset=null,$retur=false)
    {
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
        $this->load->library('pagination');

        $limit=30;
        $data['page_content'] = 'report';
        $data['page_title']='Sell Outlet';
        $data['query'] = $this->bmodel->sales_outlet($limit,(int)$offset,$year,$code,$dp,$retur);
        $config['base_url'] = site_url('sales/sales_outlet/'.$year.'/'.$code.'/'.$dp);
        $config['total_rows']=$this->bmodel->getTotalQuery();
        $config['per_page']=$limit;
        $config['num_links'] = 5;
        $config['uri_segment']=6;


        $this->pagination->initialize($config);

        $data['menu']=$this->db->query($this->querymenu);
        $data['pagination']=$this->pagination->create_links();
        $data['sum']=$this->bmodel->getTotalQuery();
	$this->template($data['page_content'],$data);

    }
    function print_per_dp($dp=null,$year=null,$file=null)
    {
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
        echo $this->bmodel->print_per_dp($dp,$year,$file);
    }
    function print_buy_per_dp($dp=null,$year=null,$file=null)
    {
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
        echo $this->bmodel->print_buy_per_dp($dp,$year,$file);
    }
    function sales_per_dp($dp=null,$year=null,$offset=0,$retur=null)
    {
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }

        $this->load->library('pagination');

        $limit = 20;
        $data['page_content'] = 'report';
        $data['page_title']='Sell Out DP';
        $data['query']= $this->bmodel->sales_per_dp($limit,(int)$offset,$dp,$year,$retur);
        //$data['print_pdf']= anchor_popup('sales/print_per_dp/'.$dp.'/'.$year.'/pdf',img($this->image_properties_pdf));
        //$data['print_excel']= anchor_popup('sales/print_per_dp/'.$dp.'/'.$year.'/excel',img($this->image_properties_excel));
        $config['base_url'] = site_url('sales/sales_per_dp/'.$dp.'/'.$year);
        $config['total_rows']=$this->bmodel->getTotalQuery();
        $config['per_page']=$limit;
        $config['uri_segment']=5;

        $this->pagination->initialize($config);

        $data['menu']=$this->db->query($this->querymenu);
        $data['pagination']=$this->pagination->create_links();

	$this->template($data['page_content'],$data);
    }
    function buy_per_dp($dp=null,$year=null,$offset=0)
    {
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }

        $this->load->library('pagination');

        $limit = 20;
        $data['page_content'] = 'report';
        $data['page_title']='Sell In DP';
        $data['query']= $this->bmodel->buy_per_dp($limit,(int)$offset,$dp,$year);
        //data['print_pdf']= anchor_popup('sales/print_buy_per_dp/'.$dp.'/'.$year.'/pdf',img($this->image_properties_pdf));
        //$data['print_excel']= anchor_popup('sales/print_buy_per_dp/'.$dp.'/'.$year.'/excel',img($this->image_properties_excel));
        $config['base_url'] = site_url('sales/buy_per_dp/'.$dp.'/'.$year);
        $config['total_rows']=$this->bmodel->getTotalQuery();
        $config['per_page']=$limit;
        $config['uri_segment']=5;

        $this->pagination->initialize($config);

        $data['menu']=$this->db->query($this->querymenu);
        $data['pagination']=$this->pagination->create_links();

	$this->template($data['page_content'],$data);
    }
    function print_bsp($year=null)
    {
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
        echo $this->bmodel->print_bsp($year);
    }
    function sales_bsp($offset=0)
    {
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
        $this->load->library('pagination');
        //$this->load->model('basic_model','bmodel');
        $limit = 15;
        if($this->input->post('year'))
        {
            $data['query']= $this->bmodel->sales_bsp($limit,(INT)$offset,$this->input->post('year'));
            $this->session->set_flashdata('year',$this->input->post('year'));
            $data['print_pdf']=anchor_popup('sales/print_bsp/'.$this->input->post('year'),img($this->image_properties_pdf));
        }

        else
        {
            $data['query']= $this->bmodel->sales_bsp($limit,(INT)$offset,$this->session->flashdata('year'));
            $this->session->keep_flashdata('year');
            $data['print_pdf']=anchor_popup('sales/print_bsp/'.$this->session->flashdata('year'),img($this->image_properties_pdf));
        }

        $data['page_content'] = 'report';
        $data['page_title']='Report Penjualan BSP';
        //$data['query']= $this->bmodel->get_list_bsp($limit,(INT)$offset);

        $config['base_url'] = site_url('sales/sales_bsp/');
        $config['total_rows']=$this->bmodel->getTotalQuery();
        $config['per_page']=$limit;

        $this->pagination->initialize($config);
        $data['pagination']=$this->pagination->create_links();

        if(!is_null($offset))
        {
            $offset = $this->uri->segment(5);
        }

	$this->template($data['page_content'],$data);
    }
    function print_omzet($year=null,$file=null)
    {
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
        $this->bmodel->print_omzet($year,$file);
    }
    function print_omzet_barat($year=null,$file=null)
    {
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
        $this->bmodel->print_omzet_barat($year,$file);
    }
    function print_omzet_timur($year=null,$file=null)
    {
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
        $this->bmodel->print_omzet_timur($year,$file);
    }
    function raw($bulan=null,$tahun=null,$supp=null)
    {
        $bulan=$this->input->post('month');
        $tahun=$this->input->post('year');
        $supp=$this->input->post('supp');
        $this->bmodel->raw($bulan,$tahun,$supp);
    }
    function peta()
    {
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
        $data['page_content'] = 'report';
        $data['page_title']='PETA';
        $data['menu']=$this->db->query($this->querymenu);
        $data['map']=$this->bmodel->peta();
        $this->template($data['page_content'],$data);
    }
    function sales_omzet($year=null,$offset=null,$retur=null)
    {
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }

        $this->load->library('pagination');

        $limit = 25;
        $data['page_content'] = 'report';
        $data['page_title']='Omzet DP';
        $data['query']= $this->bmodel->sales_omzet($limit,(int)$offset,$year,$retur);
        //$data['print_pdf']= anchor_popup('sales/print_omzet/'.$year.'/pdf',img($this->image_properties_pdf));
        //$data['print_excel']= anchor_popup('sales/print_omzet/'.$year.'/excel',img($this->image_properties_excel));
        $config['base_url'] = site_url('sales/sales_omzet/'.$year);
        $config['total_rows']=$this->bmodel->getTotalQuery();
        $config['per_page']=$limit;
        $config['uri_segment']=4;

        $this->pagination->initialize($config);

        $data['menu']=$this->db->query($this->querymenu);
        $data['pagination']=$this->pagination->create_links();

	$this->template($data['page_content'],$data);
    }
    function sales_omzet_barat($year=null,$offset=null,$retur=null)
    {
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }

        $this->load->library('pagination');

        $limit = 25;
        $data['page_content'] = 'report';
        $data['page_title']='Omzet DP Barat';
        $data['query']= $this->bmodel->sales_omzet_barat($limit,(int)$offset,$year,$retur);
        //$data['print_pdf']= anchor_popup('sales/print_omzet/'.$year.'/pdf',img($this->image_properties_pdf));
        //$data['print_excel']= anchor_popup('sales/print_omzet/'.$year.'/excel',img($this->image_properties_excel));
        $config['base_url'] = site_url('sales/sales_omzet_barat/'.$year);
        $config['total_rows']=$this->bmodel->getTotalQuery();
        $config['per_page']=$limit;
        $config['uri_segment']=4;

        $this->pagination->initialize($config);

        $data['menu']=$this->db->query($this->querymenu);
        $data['pagination']=$this->pagination->create_links();

	$this->template($data['page_content'],$data);
    }
    function sales_omzet_timur($year=null,$offset=null,$retur=null)
    {
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }

        $this->load->library('pagination');

        $limit = 25;
        $data['page_content'] = 'report';
        $data['page_title']='Omzet DP Timur';
        $data['query']= $this->bmodel->sales_omzet_timur($limit,(int)$offset,$year,$retur);
        //$data['print_pdf']= anchor_popup('sales/print_omzet/'.$year.'/pdf',img($this->image_properties_pdf));
        //$data['print_excel']= anchor_popup('sales/print_omzet/'.$year.'/excel',img($this->image_properties_excel));
        $config['base_url'] = site_url('sales/sales_omzet_timur/'.$year);
        $config['total_rows']=$this->bmodel->getTotalQuery();
        $config['per_page']=$limit;
        $config['uri_segment']=4;

        $this->pagination->initialize($config);

        $data['menu']=$this->db->query($this->querymenu);
        $data['pagination']=$this->pagination->create_links();

	$this->template($data['page_content'],$data);
    }
    function sales_dp($year=null,$offset=0,$file=0,$retur=false)
    {
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
        $this->load->library('pagination');

        $limit = 20;
        $data['page_content'] = 'report';
        $data['page_title']='Sell Out DP National';
        $data['query']= $this->bmodel->sales_dp($limit,(int)$offset,$year,$retur);
        //$data['print_pdf']= anchor_popup('sales/print_dp/'.$year.'/pdf',img($this->image_properties_pdf));
        //$data['print_excel']= anchor_popup('sales/print_dp/'.$year.'/excel',img($this->image_properties_excel));
        $config['base_url'] = site_url('sales/sales_dp/'.$year);
        $config['total_rows']=$this->bmodel->getTotalQuery();
        $config['per_page']=$limit;
        $config['uri_segment']=4;

        $this->pagination->initialize($config);

        $data['menu']=$this->db->query($this->querymenu);
        $data['pagination']=$this->pagination->create_links();

	$this->template($data['page_content'],$data);
    }
    //Tambahan Tizar
    function sales_dp_barat($year=null,$offset=0,$file=0,$retur=false)
    {
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
        $this->load->library('pagination');

        $limit = 20;
        $data['page_content'] = 'report';
        $data['page_title']='Sell Out DP Barat';
        $data['query']= $this->bmodel->sales_dp_barat($limit,(int)$offset,$year,$retur);
        //$data['print_pdf']= anchor_popup('sales/print_dp/'.$year.'/pdf',img($this->image_properties_pdf));
        //$data['print_excel']= anchor_popup('sales/print_dp/'.$year.'/excel',img($this->image_properties_excel));
        $config['base_url'] = site_url('sales/sales_dp_barat/'.$year);
        $config['total_rows']=$this->bmodel->getTotalQuery();
        $config['per_page']=$limit;
        $config['uri_segment']=4;

        $this->pagination->initialize($config);

        $data['menu']=$this->db->query($this->querymenu);
        $data['pagination']=$this->pagination->create_links();

	$this->template($data['page_content'],$data);
    }
    //Tambahan Tizar
    function sales_dp_timur($year=null,$offset=0,$file=0,$retur=false)
    {
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
        $this->load->library('pagination');

        $limit = 20;
        $data['page_content'] = 'report';
        $data['page_title']='Sell Out DP Timur';
        $data['query']= $this->bmodel->sales_dp_timur($limit,(int)$offset,$year,$retur);
        //$data['print_pdf']= anchor_popup('sales/print_dp/'.$year.'/pdf',img($this->image_properties_pdf));
        //$data['print_excel']= anchor_popup('sales/print_dp/'.$year.'/excel',img($this->image_properties_excel));
        $config['base_url'] = site_url('sales/sales_dp_timur/'.$year);
        $config['total_rows']=$this->bmodel->getTotalQuery();
        $config['per_page']=$limit;
        $config['uri_segment']=4;

        $this->pagination->initialize($config);

        $data['menu']=$this->db->query($this->querymenu);
        $data['pagination']=$this->pagination->create_links();

	$this->template($data['page_content'],$data);
    }
    function print_dp($year=null,$file=null)
    {
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
        $this->bmodel->print_dp($year,$file);
    }
    //Tambahan Tizar
    function print_dp_timur($year=null,$file=null)
    {
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
        $this->bmodel->print_dp_timur($year,$file);
    }
    function print_dp_barat($year=null,$file=null)
    {
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
        $this->bmodel->print_dp_barat($year,$file);
    }

    function show_option_permen()
    {
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
        //$data['map']=$this->bmodel->peta();
        //$data['page_content'] = 'sales/form_omzet';
        $data['page_content'] = 'sales/form_permen';
        $data['url']='sales/vessel/11';
        //$data['page_title']='Sales Omzet';
        $data['page_title']='Sell Out Permen';
        $data['query']=$this->bmodel->getSuppbyid();
        $data['menu']=$this->db->query($this->querymenu);
        $this->template($data['page_content'],$data);
    }

    function sell_out_permen($year=null,$offset=null,$retur=null)
    {
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }

        $this->load->library('pagination');

        $limit = 25;
        $data['page_content'] = 'report';
        $data['page_title']='Sell Out Permen';
        $data['query']= $this->bmodel->sell_out_permen($limit,(int)$offset,$year,$retur);
        //$data['print_pdf']= anchor_popup('sales/print_omzet/'.$year.'/pdf',img($this->image_properties_pdf));
        //$data['print_excel']= anchor_popup('sales/print_omzet/'.$year.'/excel',img($this->image_properties_excel));
        $config['base_url'] = site_url('sales/sell_out_permen/'.$year);
        $config['total_rows']=$this->bmodel->getTotalQuery();
        $config['per_page']=$limit;
        $config['uri_segment']=4;

        $this->pagination->initialize($config);

        $data['menu']=$this->db->query($this->querymenu);
        $data['pagination']=$this->pagination->create_links();

    $this->template($data['page_content'],$data);
    }
}
?>
