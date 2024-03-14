<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class All_raw extends MY_Controller
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

    function all_raw()
    {
        set_time_limit(0);
        $this->load->library(array('table','template','Excel_generator', 'form_validation','email'));
        $this->load->helper('url');
        $this->load->helper('csv');
        $this->load->model('model_raw');
        $this->load->database();
        $this->querymenu='select  a.id,
                        a.menuview,
                        a.target,
                        a.groupname 
                from    mpm.menu a inner join mpm.menudetail b 
                            on a.id=b.menuid 
                where   b.userid='.$this->session->userdata('id').' and 
                        active=1 
                order by a.groupname,menuview ';
    }
    function index()
    {

        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }

        $this->list_raw();
    }

    private function template($view,$data)
    {
        $this->template->set_title('MPM SQUARE');
        $this->template->add_js('modules/skeleton.js');
        $this->template->add_css('modules/skeleton.css');
        $this->template->load_view($view, $data);
    }

    public function view_raw(){

      $logged_in= $this->session->userdata('logged_in');
      if(!isset($logged_in) || $logged_in != TRUE)
      {
          redirect('login/','refresh');
      }

        $data['page_content'] = 'raw/view_raw';                      
        $data['menu']=$this->db->query($this->querymenu);
        $data['query'] = $this->model_raw->getSuppbyid();
        //$data['helps']=$this->model_raw->view_raw();
        $this->template($data['page_content'],$data);
    }

    public function data_raw_hasil(){

      //$data['page_content'] = 'raw/table_view_raw';
      $data['query'] = $this->model_raw->getSuppbyid();
      $data['menu']=$this->db->query($this->querymenu);
      //$data['year']=$this->input->post('tahun');

      $year = $this->input->post('tahun');
      $supplier = $this->input->post('supp');
      $month = $this->input->post('month');
      $minggu = $this->input->post('minggu');
      
      /*echo "<pre>";
      echo "year contr : ".$year."<br>";
      echo "supp contr : ".$supplier."<br>";
      echo "month contr : ".$month."<br>";
      echo "minggu contr : ".$minggu."<br>";
      echo "</pre>";
      */
      $data['year'] = $year;
      $data['supplier'] = $supplier;
      $data['month'] = $month;
      $data['minggu'] = $minggu;

      if ($minggu == '0') {
        $data['query']=$this->model_raw->proses_raw_data($data);
      }else{
        $data['query']=$this->model_raw->proses_raw_data_mingguan($data);
      }      
      $this->template($data['page_content'],$data);
      
    }

    public function export_raw(){

      ini_set('memory_limit', '-1');

      $this->load->library('Excel_generator');
      $x = $this->uri->segment('3');
      $y = $this->uri->segment('4');
      $z = $this->uri->segment('5');
      
      $this->db->order_by('kode_comp','asc');
      $this->db->where('supplier', $z); 
      $this->db->where('bulan', $x); 
      $hasil = $this->db->get("mpm.tbl_raw_".$y);
      

      $this->excel_generator->set_query($hasil);
      $this->excel_generator->set_header(array
      (
        'kode_comp', 'nama_comp', 'faktur','kodeprod', 'namaprod','tanggal', 'kode_lang', 'nama_lang','alamat', 'kode_kota','nama_kota','kode_type','nama_type', 'kode_sales','nama_sales', 
        'kodesalur', 'namasalur', 'unit','harga', 'diskon','bruto', 'netto','tahun', 'bulan','supplier'
      ));
      $this->excel_generator->set_column(array
        (
          'kode_comp', 
          'nama_comp', 
          'faktur',
          'kodeprod', 
          'namaprod',
          'tanggal', 
          'kode_lang', 
          'nama_lang',
          'alamat', 
          'kode_kota',
          'nama_kota',
          'kode_type',
          'nama_type', 
          'kode_sales',
          'nama_sales', 
          'kodesalur', 
          'namasalur', 
          'unit',
          'harga', 
          'diskon',
          'bruto', 
          'netto',
          'tahun', 
          'bulan',
          'supplier'
        ));
      $this->excel_generator->set_width(array(8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8));
      $this->excel_generator->exportTo2007('Data_Raw');  
  
      

    }

    public function export_raw_csv(){

      ini_set('memory_limit', '-1');

      $x = $this->uri->segment('3'); //bulan
      $y = $this->uri->segment('4'); //tahun
      $z = $this->uri->segment('5');
      
      // echo "<pre>";
      // echo "x : ".$x ;
      // echo "<br>y : ".$y ;
      // echo "<br>z : ".$z ;
      // echo "</pre>";
      
      if ($z == 'XXX') {
      
        echo "<a href = ../../../export_all_csv/$y/$x target=blank  >download part 1 </a><br>";
        echo "<a href = ../../../export_all_csv_2/$y/$x target=blank>download part 2 </a>";

      }else{
        
        $this->db->order_by('kode_comp','asc');
        $this->db->where('supplier', $z); 
        $this->db->where('bulan', $x);
        $quer  =   $this->db->get("mpm.tbl_raw_".$y);
        
        
        // $sql = "
        // select * from mpm.tbl_raw_2019
        // where supplier ='001' and bulan in (1,2,3,4) and kode_comp in ('med','asa','lan','teb','sid','psi','sbt','sbj','rpa','bkl','jmi','pkb','sbm','pdg','met','ktb','sjd','btm','lmp','bdl','ach','llg','sbb','mbg')
        // union all
        // select * from mpm.tbl_raw_2020
        // where supplier ='001' and bulan in (1,2,3,4) and kode_comp in ('med','asa','lan','teb','sid','psi','sbt','sbj','rpa','bkl','jmi','pkb','sbm','pdg','met','ktb','sjd','btm','lmp','bdl','ach','llg','sbb','mbg')
        // ";
        // $quer = $this->db->query($sql);

        // $sql = "
        // select * from mpm.tbl_raw_2019
        // where supplier ='002'
        // ";
        // $quer = $this->db->query($sql);

        // $sql = "
        // select * from mpm.tbl_raw_2019
        // where bulan in (1,2,3,4) and kode_comp in ('bjm','bmk','css','gid','gto','mdo','ssm','wtk')
        // union all
        // select * from mpm.tbl_raw_2020
        // where bulan in (1,2,3,4) and kode_comp in ('bjm','bmk','css','gid','gto','mdo','ssm','wtk')
        
        // // ";
        
        // $quer = $this->db->query($sql);

        // $sql = "
        // select * from mpm.tbl_raw_2019
        // where supplier ='001' and bulan in (9)
        // ";
        // $quer = $this->db->query($sql);

        query_to_csv($quer,TRUE,'Tabel_Raw (Closing) bulan '.$x.'-'.$y.'.csv');
      }
      
    }

    public function export_raw_csv_minggu(){

      //ini_set('memory_limit', '-1');

      $x = $this->uri->segment('3');
      $y = $this->uri->segment('4');
      $z = $this->uri->segment('5');
      $m = $this->uri->segment('6');
      /*
      echo "<pre>";
      echo "x : ".$x ;
      echo "<br>y : ".$y ;
      echo "<br>z : ".$z ;
      echo "</pre>";
      */
      
      // $sql = "
      //   select *
      //   from mpm.tbl_raw_2019
      //   where bulan = 1 and supplier ='xxx'
      //   limit 400000
      // ";
      // $quer = $this->db->query($sql);

      $this->db->order_by('kode_comp','asc');
      $this->db->where('minggu', $m); 
      $this->db->where('supplier', $z); 
      $this->db->where('bulan', $x);
      $quer  =   $this->db->get("mpm.tbl_raw_".$y."_minggu");
      
      query_to_csv($quer,TRUE,'Tabel_Raw (mingguan) '.$y.'-'.$x.'-'.$m.'.csv');
      

    }

    function export_all_csv(){

      $x = $this->uri->segment('4'); //bulan
      $y = $this->uri->segment('3'); //tahun
      $z = $this->uri->segment('5');

      $this->db->order_by('kode_comp','asc');
      $this->db->limit(300000,0);
      $this->db->where('supplier', $z); 
      $this->db->where('bulan', $x);
      $quer  =   $this->db->get("mpm.tbl_raw_".$y);
      
      query_to_csv($quer,TRUE,'Tabel_Raw (Closing) bulan '.$x.'-'.$y.'_part1.csv');

    }

    function export_all_csv_2(){

      $x = $this->uri->segment('4'); //bulan
      $y = $this->uri->segment('3'); //tahun
      $z = $this->uri->segment('5');

      $this->db->order_by('kode_comp','asc');
      $this->db->limit(300000,300000);
      $this->db->where('supplier', $z); 
      $this->db->where('bulan', $x);
      $quer  =   $this->db->get("mpm.tbl_raw_".$y);
      
      query_to_csv($quer,TRUE,'Tabel_Raw (Closing) bulan '.$x.'-'.$y.'_part2.csv');

    }

    public function pmu() {
        
      $sql = "
        select * from pmu.fi_tbs
      ";

      //print_r($sql);

      $hasil = $this->db->query($sql);

     
      $this->excel_generator->set_query($hasil);
      $this->excel_generator->set_header(array
        (
           'KDDOKJDI','NODOKJDI', 'NODOKACU', 'TGLDOKJDI','KODESALES'
        ));
      $this->excel_generator->set_column(array
        ( 
          'kddokjdi','nodokjdi', 'nodokacu', 'tgldokjdi','kodesales'
        ));
      $this->excel_generator->set_width(array(7, 20, 10, 7,13));
      //$this->excel_generator->exportTo2007('Omzet'.'_'.$tahun.'_'.$supplier);   
      $this->excel_generator->exportTo2007('export pmu');   
  }

  public function pmu_csv(){

      //ini_set('memory_limit', '-1');

       
      $quer  =   $this->db->get("pmu.fi_tbs");
      
      
      query_to_csv($quer,TRUE,'pmu.csv');
      

    }

  public function list_raw(){
    $logged_in= $this->session->userdata('logged_in');
    if(!isset($logged_in) || $logged_in != TRUE)
    {
        redirect('login/','refresh');
    }

    $supp = $this->session->userdata('supp');

    $data['page_content'] = 'raw/list_raw';                      
    $data['menu']=$this->db->query($this->querymenu);
    // $data['query'] = $this->model_raw->get_raw($supp);
    $data['query'] = $this->model_raw->get_raw($supp);
    $this->template($data['page_content'],$data);
  }

}
?>
