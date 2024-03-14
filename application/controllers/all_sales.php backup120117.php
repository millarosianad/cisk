<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class All_sales extends MY_Controller
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

    function All_sales()
    {
        set_time_limit(0);
        $this->load->library(array('table','template','Excel_generator', 'form_validation'));
        $this->load->helper('url');
        $this->load->model('model_sales');
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
        $this->sales_product();
    }

    private function template($view,$data)
    {
        $this->template->set_title('MPM SQUARE');
        $this->template->add_js('modules/skeleton.js');
        $this->template->add_css('modules/skeleton.css');
        $this->template->load_view($view, $data);
    }

    public function sales_product(){

        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
            $data['page_content'] = 'all_sales/view_sales_per_product_kosong';
            $data['query'] = $this->model_sales->getSuppbyid();
            $data['url'] = 'all_sales/sales_product_hasil/';
            $data['page_title'] = 'Sales Per Product';
            $data['menu']=$this->db->query($this->querymenu);
            $data['query']=$this->model_sales->list_product();
            //$data['query2']=$this->model_sales->list_dp();
            $this->template($data['page_content'],$data);            
    }

     public function sales_product_permen(){

        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
            $data['page_content'] = 'all_sales/view_sales_per_product_kosong';
            $data['query'] = $this->model_sales->getSuppbyid();
            $data['url'] = 'all_sales/sales_product_hasil_permen/';
            $data['page_title'] = 'Sales Per Product';
            $data['menu']=$this->db->query($this->querymenu);
            $data['query']=$this->model_sales->list_product_permen();
            //$data['query2']=$this->model_sales->list_dp();
            $this->template($data['page_content'],$data);            
    }

    public function sales_product_hasil(){

    $year = $this->input->post('year');
      //$dp = $this->input->post('nocab');
      $uv = $this->input->post('uv');
      //$sm = $this->input->post('sm');

      $code=$this->input->post('kodeprod');
      $options=$this->input->post('options');
      
      foreach($options as $kode)
      {
          $code.=",".$kode;
      }
      $code=preg_replace('/,/', '', $code,1);
      echo "<pre>";
      echo "<br>kode produk yang dipilih =  ".$code."<br>";
      //echo "<br>kode dp yang dipilih =  ".$dp."<br>";
      //echo "<br>kode sales yang dipilih =  ".$sm."<br>";
      echo "<br>tahun yang dipilih =  ".$year."<br>";
      echo "</pre>";
      
      $data['page_content'] = 'all_sales/view_sales_per_product';
      $data['menu']=$this->db->query($this->querymenu);
     
      $data['tahun'] = $year;
      $data['uv'] = $uv;
      $data['code'] = $code;
      $data['products']=$this->model_sales->sales_per_product($data);

      $this->template($data['page_content'],$data);            
    }

    public function sales_product_hasil_permen(){

    $year = $this->input->post('year');
      //$dp = $this->input->post('nocab');
      $uv = $this->input->post('uv');
      //$sm = $this->input->post('sm');

      $code=$this->input->post('kodeprod');
      $options=$this->input->post('options');
      
      foreach($options as $kode)
      {
          $code.=",".$kode;
      }
      $code=preg_replace('/,/', '', $code,1);
      echo "<pre>";
      echo "<br>kode produk yang dipilih =  ".$code."<br>";
      //echo "<br>kode dp yang dipilih =  ".$dp."<br>";
      //echo "<br>kode sales yang dipilih =  ".$sm."<br>";
      echo "<br>tahun yang dipilih =  ".$year."<br>";
      echo "</pre>";
      
      $data['page_content'] = 'all_sales/view_sales_per_product_permen';
      $data['menu']=$this->db->query($this->querymenu);
     
      $data['tahun'] = $year;
      $data['uv'] = $uv;
      $data['code'] = $code;
      $data['products']=$this->model_sales->sales_per_product($data);

      $this->template($data['page_content'],$data);            
    }   

    public function export() {
        
        $segment3 = $this->uri->segment('3');
        //$segment4 = $this->uri->segment('4');
        $id_user=$this->session->userdata('id');

        //$this->db->join('tbl_tabcomp_new', 'tbl_tabcomp_new.naper = stokprod_new.naper','inner');
        //$this->db->where("status = 1");
        $this->db->order_by('urutan','asc');           
        $this->db->where("soprod_new.id = ".'"'.$id_user.'"');
        $hasil = $this->db->get('soprod_new');   
       
        $this->excel_generator->set_query($hasil);
        $this->excel_generator->set_header(array
          (
             'SubBranch', 'T1','Jan','T2', 'Feb','T3','Mar','T4','Apr','T5','Mei','T6','Jun','T7', 'Jul','T8','Agus','T9','Sep','T10','Okt','T11','Nov','T12','Des'
          ));
        $this->excel_generator->set_column(array
          ( 
            'nama_comp', 
            't1',
            'b1',
            't2',
            'b2', 
            't3',
            'b3', 
            't4',
            'b4',
            't5',
            'b5', 
            't6',
            'b6',            
            't7',
            'b7', 
            't8',
            'b8', 
            't9',
            'b9',
            't10',
            'b10', 
            't11',
            'b11', 
            't12',
            'b12'
          ));
        $this->excel_generator->set_width(array(20, 7,13,7,13,7,13,7,13,7,13,7,13,7,13,7,13,7,13,7,13,7,13,7,13));
        //$this->excel_generator->exportTo2007('Omzet'.'_'.$tahun.'_'.$supplier);   
        $this->excel_generator->exportTo2007('SalesPerProduct'.'_'.$segment3);   
    }
}
?>
