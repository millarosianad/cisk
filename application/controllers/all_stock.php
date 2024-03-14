<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class All_stock extends MY_Controller
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

    function all_stock()
    {
        set_time_limit(0);
        $this->load->library(array('table','template','Excel_generator'));
        $this->load->helper('url');
        $this->load->helper('csv');
        $this->load->model('model_stock');
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

    function index()
    {
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }

        redirect('all_stock/stock_by_product','refresh');
    }

    public function stock_by_product(){

        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
            $data['page_content'] = 'stock/view_stock_by_product_kosong';
            $data['query'] = $this->model_stock->getSuppbyid();
            $data['url'] = 'all_stock/stock_by_product_hasil/';
            $data['page_title'] = 'Stock By Product';
            $data['menu']=$this->db->query($this->querymenu);
            $data['query']=$this->model_stock->list_product();
            $data['query2']=$this->model_stock->list_dp();
            $this->template($data['page_content'],$data);            
    }

    public function stock_by_product_hasil(){

      $year = $this->input->post('year');
      $dp = $this->input->post('nocab');
      $uv = $this->input->post('uv');
      $sm = $this->input->post('sm');

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
      
      $data['page_content'] = 'stock/view_stock_by_product';
      $data['menu']=$this->db->query($this->querymenu);
      $data['query']=$this->model_stock->list_dp_stock();     
      $data['tahun'] = $year;
      $data['dp'] = $dp;
      $data['uv'] = $uv;
      $data['sm'] = $sm;
      $data['code'] = $code;
      $data['stocks']=$this->model_stock->stock_by_product($data);

      $this->template($data['page_content'],$data);

    }

    public function export() {
        
        $segment3 = $this->uri->segment('3');
        //$segment4 = $this->uri->segment('4');
        $id_user=$this->session->userdata('id');

        //$this->db->join('tbl_tabcomp', 'tbl_tabcomp.naper = stokprod_new.naper','inner');
        //$this->db->where("status = 1");
        //$this->db->order_by('tbl_tabcomp.urutan','asc');
        $this->db->order_by('stokprod_new.urutan','asc');        
        $this->db->where("stokprod_new.id = ".'"'.$id_user.'"');
        $hasil = $this->db->get('stokprod_new');   
       
        $this->excel_generator->set_query($hasil);
        $this->excel_generator->set_header(array
          (
            'NoSubBranch', 'SubBranch', 'Jan', 'Feb','Mar','Apr','Mei','Jun', 'Jul','Agus','Sep','Okt','Nov','Des'
          ));
        $this->excel_generator->set_column(array
          (
            'naper', 
            'namacomp', 
            'b1',
            'b2', 
            'b3', 
            'b4',
            'b5', 
            'b6',            
            'b7', 
            'b8', 
            'b9',
            'b10', 
            'b11', 
            'b12'
          ));
        $this->excel_generator->set_width(array(8, 20, 13,13,13,13,13,13,13,13,13,13,13,13));
        //$this->excel_generator->exportTo2007('Omzet'.'_'.$tahun.'_'.$supplier);   
        $this->excel_generator->exportTo2007('StockByProduct'.'_'.$segment3);   
    }

    public function stock_avg(){

      $logged_in= $this->session->userdata('logged_in');
      if(!isset($logged_in) || $logged_in != TRUE)
      {
          redirect('login/','refresh');
      }
          //$this->info();
          /* untuk sementara di nonaktifkan */
          
          $data['page_content'] = 'stock/view_stock_by_product_kosong_avg';
          $data['query'] = $this->model_stock->getSuppbyid();
          $data['url'] = 'all_stock/stock_by_product_hasil_avg/';
          $data['page_title'] = 'AVG 6 Bln dan Stok Akhir';
          $data['menu']=$this->db->query($this->querymenu);
          $data['query']=$this->model_stock->list_product();
          //$data['query2']=$this->model_stock->list_dp();
          $this->template($data['page_content'],$data);  
    }

    public function info(){
        $this->load->view('info_pop_up_stock_avg');
    }

    public function stock_by_product_hasil_avg(){

      $year = $this->input->post('year');
      $dp = $this->input->post('nocab');
      $uv = $this->input->post('uv');
      $sm = $this->input->post('sm');

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
      
      $data['page_content'] = 'stock/view_stock_by_product_avg';
      $data['menu']=$this->db->query($this->querymenu);
     
      $data['tahun'] = $year;
      $data['dp'] = $dp;
      $data['uv'] = $uv;
      $data['sm'] = $sm;
      $data['code'] = $code;
      $data['stocks']=$this->model_stock->stock_by_product_avg($data);

      $this->template($data['page_content'],$data);

    }

    public function export_avg() {
        
        $segment3 = $this->uri->segment('3');
        //$segment4 = $this->uri->segment('4');
        $id_user=$this->session->userdata('id');

        //$this->db->join('tbl_tabcomp', 'tbl_tabcomp.naper = stokprod_new.naper','inner');
        //$this->db->where("status = 1");
        //$this->db->order_by('tbl_tabcomp.urutan','asc');
        $this->db->order_by('tbl_avg_sales_stok.urutan','asc');   
        $this->db->where("tbl_avg_sales_stok.id = ".'"'.$id_user.'"');
        $hasil = $this->db->get('tbl_avg_sales_stok');   
       
        $this->excel_generator->set_query($hasil);
        $this->excel_generator->set_header(array
          (
            'SubBranch', 'KodeProduk', 'NamaProduk','Total(Unit)','Total(Value)','Avg(Unit)','Avg(Value)','StokAkhir','DOI'
          ));
        $this->excel_generator->set_column(array
          (
            'nama_comp', 
            'kodeprod',
            'namaprod', 
            'total_unit', 
            'total_value',
            'avg_unit', 
            'avg_value',
            'stok_akhir',
            'doi'
          ));
        $this->excel_generator->set_width(array(20, 13, 20,13,13,13,13,13,13));
        //$this->excel_generator->exportTo2007('Omzet'.'_'.$tahun.'_'.$supplier);   
        $this->excel_generator->exportTo2007('AvgPer6bln_dan_StokAkhir'.'_'.$segment3);    
    }

    public function stock_dp(){

        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
            $data['page_content'] = 'stock/view_stock_dp_kosong';
            $data['query'] = $this->model_stock->getSuppbyid();
            $data['url'] = 'all_stock/stock_dp_hasil/';
            $data['page_title'] = 'Stock By DP';
            $data['menu']=$this->db->query($this->querymenu);
            $data['query']=$this->model_stock->list_product();
            //$data['query2']=$this->model_stock->list_dp();
            $this->template($data['page_content'],$data);  
    }

    public function stock_dp_hasil(){

        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
            $data['page_content'] = 'stock/view_stock_dp';
            $data['query'] = $this->model_stock->getSuppbyid();
            $data['url'] = 'all_stock/stock_dp_hasil/';
            $data['page_title'] = 'Stock By DP';
            $data['menu']=$this->db->query($this->querymenu);
            $data['query']=$this->model_stock->list_product();
            $data['query2']=$this->model_stock->list_dp();

            //echo "<pre>";
            $year = $this->input->post('year');
            $kode = $this->input->post('nocab');
            $uv = $this->input->post('uv');
            //echo "year : ".$year."<br>";
            //echo "kodedp : ".$kode."<br>";         
            //echo "unit/value : ".$uv."<br>";
            //echo "</pre>";

            $data['year'] = $year;
            $data['kode'] = $kode;
            $data['uv'] = $uv;
            $data['query3']=$this->model_stock->stock_dp($data);

            $this->template($data['page_content'],$data);  
    }

    public function export_stok_dp() {
        
        $segment3 = $this->uri->segment('3');
        //$segment4 = $this->uri->segment('4');
        $id_user=$this->session->userdata('id');

        //$this->db->join('tbl_tabcomp', 'tbl_tabcomp.naper = stokprod_new.naper','inner');
        //$this->db->where("status = 1");
        //$this->db->order_by('tbl_tabcomp.urutan','asc');
        $this->db->order_by('stokdp.kodeprod','asc');   
        $this->db->where("stokdp.id = ".'"'.$id_user.'"');
        $hasil = $this->db->get('stokdp');   
       
        $this->excel_generator->set_query($hasil);
        $this->excel_generator->set_header(array
          (
            'KodeProduk', 'NamaProduk', 'Avg (6bln)','Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des','Doi'
          ));
        $this->excel_generator->set_column(array
          (
            'kodeprod', 
            'namaprod',
            'rata', 
            'b1', 
            'b2',
            'b3', 
            'b4',
            'b5',
            'b6', 
            'b7',
            'b8', 
            'b9',
            'b10',
            'b11', 
            'b12',
            'doi'
          ));
        $this->excel_generator->set_width(array(20, 13, 20,13,13,13,13,13,13,13,13,13,13,13,13,13));
        //$this->excel_generator->exportTo2007('Omzet'.'_'.$tahun.'_'.$supplier);   
        $this->excel_generator->exportTo2007('Stok_By_DP');   
    }

    function build_namacomp()
    {
        
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
        $year = $this->input->post('id_year',TRUE);
        $output="<option value=''>- Pilih Sub Branch -</option>";

        $data['year'] = $year;
        $query=$this->model_stock->get_namacomp($data);
        foreach($query->result() as $row)
        {
            $output .= "<option value='".$row->nocab."'>".$row->branch_name."</option>";
        }
        //$output="<option value=''>".$dp."</option>";
        echo $output;
    }

    public function stock_principal()
    {
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
            $data['page_content'] = 'stock/view_stock_by_principal_kosong';
            $data['query'] = $this->model_stock->getSuppbyid_stok();
            $data['url'] = 'all_stock/stock_by_principal_hasil/';
            $data['page_title'] = 'Stock By Principal';
            $data['menu']=$this->db->query($this->querymenu);
            $this->template($data['page_content'],$data);         

    }

    public function stock_by_principal_hasil(){
      $id_user=$this->session->userdata('id');
      $year = $this->input->post('year');
      $supp = $this->input->post('supp');
      $uv = $this->input->post('uv');
      /*
      echo "<pre>";
      echo "<br>year =  ".$year."<br>";
      echo "<br>supp =  ".$supp."<br>";
      echo "<br>uv =  ".$uv."<br>";
      echo "</pre>";
      */
      $data['page_content'] = 'stock/view_stock_by_principal';
      $data['menu']=$this->db->query($this->querymenu);     
      $data['tahun'] = $year;
      $data['uv'] = $uv;
      $data['supp'] = $supp;
      $data['id'] = $id_user;
      $data['stocks']=$this->model_stock->stock_by_principal($data);

      $this->template($data['page_content'],$data);

    }

    public function export_principal() {
      $segment3 = $this->uri->segment('3');
      //$segment4 = $this->uri->segment('4');
      $id_user=$this->session->userdata('id');

      //$this->db->join('tbl_tabcomp', 'tbl_tabcomp.naper = stokprod_new.naper','inner');
      //$this->db->where("status = 1");
      //$this->db->order_by('tbl_tabcomp.urutan','asc');
      $this->db->order_by('stok_principal.urutan','asc');        
      $this->db->where("stok_principal.id = ".'"'.$id_user.'"');
      $hasil = $this->db->get('stok_principal');   
     
      $this->excel_generator->set_query($hasil);
      $this->excel_generator->set_header(array
      (
        'NoSubBranch', 'Branch','SubBranch', 'kodeprod', 'namaprod', 'Group','Nama Group','Jan_unit', 'Feb_unit','Mar_unit','Apr_unit','Mei_unit','Jun_unit', 'Jul_unit','Agus_unit','Sep_unit','Okt_unit','Nov_unit','Des_unit','Jan_value', 'Feb_value','Mar_value','Apr_value','Mei_value','Jun_value', 'Jul_value','Agus_value','Sep_value','Okt_value','Nov_value','Des_value','hna'
      ));
      $this->excel_generator->set_column(array
        (
          'nocab', 
          'branch_name',
          'namacomp', 
          'kodeprod', 
          'namaprod', 
          'group',
          'nama_group',
          'b1',
          'b2', 
          'b3', 
          'b4',
          'b5', 
          'b6',            
          'b7', 
          'b8', 
          'b9',
          'b10', 
          'b11', 
          'b12',
          'v1',
          'v2', 
          'v3', 
          'v4',
          'v5', 
          'v6',            
          'v7', 
          'v8', 
          'v9',
          'v10', 
          'v11', 
          'v12',
          'hna'
        ));
      $this->excel_generator->set_width(array(8, 20,20,20,20, 13,13,13,13,13,13,13,13,13,13,13,13,13,13, 13,13,13,13,13,13,13,13,13,13,13,13,13));
      //$this->excel_generator->exportTo2007('Omzet'.'_'.$tahun.'_'.$supplier);   
      $this->excel_generator->exportTo2007('StockByPrincipal');   
    }

    public function form_git()
    {
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
            $data['page_content'] = 'stock/form_git';
            $data['query'] = $this->model_stock->getSuppbyid_stok();
            $data['url'] = 'all_stock/git/';
            $data['page_title'] = 'Stock Akhir dan GIT (Goods in Transit)';
            $data['menu']=$this->db->query($this->querymenu);
            $this->template($data['page_content'],$data);         

    }


    public function git(){
        $id_user=$this->session->userdata('id');
        $year = $this->input->post('year');
        $supp = $this->input->post('supp');
        $bulan = $this->input->post('bulan');
        /*
        echo "<pre>";
        echo "<br>year =  ".$year."<br>";
        echo "<br>supp =  ".$supp."<br>";
        echo "<br>bulan =  ".$bulan."<br>";
        echo "</pre>";
        */
        $data['page_content'] = 'stock/view_stock_git';
        $data['query'] = $this->model_stock->getSuppbyid_stok();
        $data['menu']=$this->db->query($this->querymenu);     
        $data['tahun'] = $year;
        $data['bulan'] = $bulan;
        $data['supp'] = $supp;
        $data['id'] = $id_user;
        $data['stock']=$this->model_stock->git_new($data);
        $data['url'] = 'all_stock/git/';
        $data['page_title'] = 'Stock Akhir dan GIT';
        $this->template($data['page_content'],$data);
  
      }

    public function export_git_new()
    {
      $this->load->helper('csv');
      $id = $this->session->userdata('id');
      $sql = "select * from db_po.t_git_new where id = $id";
      $quer = $this->db->query($sql);
      query_to_csv($quer,TRUE,'export stock akhir dan git.csv');
        
    }

      public function export_git() {
        //$segment4 = $this->uri->segment('4');
        $id_user=$this->session->userdata('id');

        //$this->db->join('tbl_tabcomp', 'tbl_tabcomp.naper = stokprod_new.naper','inner');
        //$this->db->where("status = 1");
        //$this->db->order_by('tbl_tabcomp.urutan','asc');
  

        $sql = "
            select a.nocab, a.nama_comp, a.kodeprod, c.namaprod, a.stok, a.git, a.tahun, a.bulan 
            from db_po.t_temp_git a
            LEFT JOIN
            (
                select nocab,urutan
                from mpm.tbl_tabcomp
                where `status` = 1
                GROUP BY nocab
            )b on a.nocab = b.nocab
            LEFT JOIN mpm.tabprod c on a.kodeprod = c.kodeprod
            where a.userid = $id_user
            ORDER BY urutan asc
        ";
        $hasil = $this->db->query($sql);
       
        $this->excel_generator->set_query($hasil);
        $this->excel_generator->set_header(array
          (
            'SubBranch', 'kodeprod', 'namaprod', 'Stok', 'GIT', 'Tahun', 'Bulan'
          ));
        $this->excel_generator->set_column(array
          (
            'nama_comp', 
            'kodeprod', 
            'namaprod', 
            'stok', 
            'git',
            'tahun',
            'bulan'
          ));
        $this->excel_generator->set_width(array(20,20,20,20,20,10,10));
        //$this->excel_generator->exportTo2007('Omzet'.'_'.$tahun.'_'.$supplier);   
        $this->excel_generator->exportTo2007('Stock & GIT');   
    }

    public function insert_stock_mpi(){
      $logged_in= $this->session->userdata('logged_in');
      if(!isset($logged_in) || $logged_in != TRUE)
      {
          redirect('login/','refresh');
      }
      $data['page_content'] = 'mpi/insert_stock_mpi';
      $data['url'] = 'all_stock/proses_insert_stock_mpi/';
      $data['page_title'] = 'Raw Stock MPI ';
      $data['stock'] = $this->model_stock->data_stock_mpi($data);
      $data['menu']=$this->db->query($this->querymenu);
      $this->template($data['page_content'],$data);
  
    }
  
    public function proses_insert_stock_mpi(){
      $logged_in= $this->session->userdata('logged_in');
      if(!isset($logged_in) || $logged_in != TRUE)
      {
          redirect('login/','refresh');
      }
      $data['page_content'] = 'mpi/proses_insert_stock_mpi';
      $data['url'] = 'all_stock/proses_insert_stock_mpi/';
      $data['page_title'] = 'Raw Stock MPI ';
      $data['proses'] = $this->model_stock->insert_stock_mpi();
      $data['menu']=$this->db->query($this->querymenu);
      $this->template($data['page_content'],$data);
  
    }
  
    public function insert_mpi_to_db(){
      $logged_in= $this->session->userdata('logged_in');
      if(!isset($logged_in) || $logged_in != TRUE)
      {
          redirect('login/','refresh');
      }
  
      $proses= $this->model_stock->insert_mpi_to_db();
    }
  
    public function monitoring_stock_mpi(){
      $logged_in= $this->session->userdata('logged_in');
      if(!isset($logged_in) || $logged_in != TRUE)
      {
          redirect('login/','refresh');
      }
      $data['page_content'] = 'mpi/monitoring_stock_mpi';
      $data['url'] = 'all_stock/proses_monitoring_stock_mpi/';
      $data['page_title'] = 'Stock dan AVG Sales MPI ';
      $data['stock'] = $this->model_stock->data_stock_mpi($data);
      $data['menu']=$this->db->query($this->querymenu);
      $this->template($data['page_content'],$data);

      // $this->info();
  
    }

    
  
    public function proses_monitoring_stock_mpi(){
      $logged_in= $this->session->userdata('logged_in');
      if(!isset($logged_in) || $logged_in != TRUE)
      {
          redirect('login/','refresh');
      }
      
      $p1 = trim($this->input->post("cut_off_stock"));
      $cut_off_stock=strftime('%Y-%m-%d',strtotime($p1));
      // $p2 = trim($this->input->post("periode1"));
      // $periode1=strftime('%Y-%m-%d',strtotime($p2));
      // $p3 = trim($this->input->post("periode2"));
      // $periode2=strftime('%Y-%m-%d',strtotime($p3));
      $avg = trim($this->input->post("avg"));
  
      $data['page_content'] = 'mpi/proses_monitoring_stock_mpi';
      $data['url'] = 'all_stock/proses_monitoring_stock_mpi/';
      $data['page_title'] = 'Stock dan AVG Sales MPI ';
      $data['stock'] = $this->model_stock->monitoring_stock_mpi($cut_off_stock,$avg);
      $data['menu']=$this->db->query($this->querymenu);
      $this->template($data['page_content'],$data);
  
    }
  
    public function export_stock_mpi()
    {
      $cut_off = $this->uri->segment('3');
      
      $sql = "
        select 	* from mpi.t_stock_mpi a
        where 	date(a.cut_off) = '$cut_off'
      ";
      $quer = $this->db->query($sql);
      query_to_csv($quer,TRUE,'export stock mpi.csv');
        
    }
  
    public function export_monitoring_doi()
    {    
      $id = $this->session->userdata('id');
      $sql = "
        select 	* from mpi.t_temp_monitoring_doi_mpi a
        where 	id = '$id'
      ";
      $quer = $this->db->query($sql);
      query_to_csv($quer,TRUE,'export_stock_avg_sales.csv');
        
    }
   
}
?>
