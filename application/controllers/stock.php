<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class Stock extends MY_Controller
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
    function  Stock() {
        set_time_limit(0);
        $this->load->library(array('table','template'));
        $this->load->helper('url');
        $this->load->model('sales_model','bmodel');

        $this->load->database();
        $this->querymenu='select a.id,a.menuview,a.target,a.groupname from mpm.menu a inner join mpm.menudetail b on a.id=b.menuid where b.userid='. $this->session->userdata('id') . ' and active=1 order by a.groupname,a.menuview ';
    }
    function index()
    {
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
    }
    private function auth()
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
    function show_dp()
    {
        $this->auth();
        $this->load->model('basic_model','bmodel');
        $data['url']='stock/vessel/1';
        $data['page_content'] = 'stock/form_dp_year_stok';
        $data['page_title']='Stock Per DP';
        $data['menu']=$this->db->query($this->querymenu);
        //$data['content_title']='DP\'s Latest Stock';
        $data['query']=$this->bmodel->list_dp();
        $this->bmodel->delete_stok_dp();
        $this->template($data['page_content'],$data);
    }
    function show_option_product()
    {
        $this->auth();
        $data['page_content'] = 'stock/form_product_year';
        $data['url']='stock/vessel/2';
        $data['page_title']='Stock By Product';
        $data['query']=$this->bmodel->list_product();
        $data['menu']=$this->db->query($this->querymenu);
        $this->template($data['page_content'],$data);
    }
    function show_option_product_permen()
    {
        $this->auth();
        $data['page_content'] = 'stock/form_product_year';
        $data['url']='stock/vessel/2';
        $data['page_title']='Stock By Product';
        $data['query']=$this->bmodel->list_product_permen();
        $data['menu']=$this->db->query($this->querymenu);
        $this->template($data['page_content'],$data);
    }
    function stock_nas($offset=0)
    {
        $this->auth();
        $this->load->library('pagination');
        $this->load->model('basic_model','bmodel');
        $limit = 15;

        $data['query']= $this->bmodel->stock_nas($limit,(INT)$offset);
        $data['page_content'] = 'report';
        $data['page_title']='National Stock';

        $config['base_url'] = site_url('stock/stock_nas/');
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
                $this->bmodel->vessel(8);
                switch($format)
                {
                    case 1:
                    $this->stock_dp($dp,$year);break;
                    case 2:
                    $this->print_stock_dp($dp,$year,'PDF');break;
                    case 3:
                    $this->print_stock_dp($dp,$year,'EXCEL');break;
                }
            }break;
            case 2:
            {
                foreach($options as $kode)
                {
                    $code.=",".$kode;
                }
                $code=preg_replace('/,/', '', $code,1);
                $this->bmodel->vessel(8);
                switch($format)
                {
                    case 1:
                    $this->stock_product($year,$code);break;
                    case 2:
                    $this->print_stock_product($year,$code,'PDF');break;
                    case 3:
                    $this->print_stock_product($year,$code,'EXCEL');break;
                }
            }break;
        }
    }
    function print_stock_dp($dp=null,$year=null,$file=null)
    {
        $this->auth();
        echo $this->bmodel->print_stock_dp($dp,$year,$file);
    }
    function print_stock_product($year=null,$code=null,$file=null)
    {
        $this->auth();
        echo $this->bmodel->print_stock_product($year,$code,$file);
    }
    function stock_dp($dp=null,$year=null,$offset=0)
    {
        $this->auth();
        $this->load->library('pagination');

        $limit = 20;
        $data['page_content'] = 'report';
        $data['page_title']='SALDO AKHIR STOK DP';
        $data['query']= $this->bmodel->stock_dp($limit,(int)$offset,$dp,$year);
        $config['base_url'] = site_url('stock/stock_dp/'.$dp.'/'.$year);
        $config['total_rows']=$this->bmodel->getTotalQuery();
        $config['per_page']=$limit;
        $config['uri_segment']=5;

        $this->pagination->initialize($config);

        $data['menu']=$this->db->query($this->querymenu);
        $data['pagination']=$this->pagination->create_links();

	$this->template($data['page_content'],$data);
    }
    function stock_product($year=null,$code=null,$offset=0)
    {
        $this->auth();
        $this->load->library('pagination');

        $limit = 20;
        $data['page_content'] = 'report';
        $data['page_title']='STOK BY PRODUK';
        $data['query']= $this->bmodel->stock_product($year,$code,(int)$offset,$limit);
        $config['base_url'] = site_url('stock/stock_product/'.$year.'/'.$code);
        $config['total_rows']=$this->bmodel->getTotalQuery();
        $config['per_page']=$limit;
        $config['uri_segment']=5;

        $this->pagination->initialize($config);

        $data['menu']=$this->db->query($this->querymenu);
        $data['pagination']=$this->pagination->create_links();

	$this->template($data['page_content'],$data);
    }
}
?>
