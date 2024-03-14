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

    public function sales_product_per_class(){

        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
            $data['page_content'] = 'all_sales/view_sales_per_product_kosong';
            //$data['query'] = $this->model_sales->getSuppbyid();
            $data['url'] = 'all_sales/sales_product_per_class_hasil/';
            $data['page_title'] = 'Sales Per Product (Class)';
            $data['menu']=$this->db->query($this->querymenu);
            $data['query']=$this->model_sales->list_product();
            //$data['query2']=$this->model_sales->list_dp();
            $this->template($data['page_content'],$data);            
    }

    public function sales_product_hasil(){

      $year = $this->input->post('year');
      $uv = $this->input->post('uv');

      $code=$this->input->post('kodeprod');
      $options=$this->input->post('options');
      $data['groupby'] = $this->input->post('groupby');
      
      foreach($options as $kode)
      {
          $code.=",".$kode;
      }
      $code=preg_replace('/,/', '', $code,1);
      echo "<pre>";
      echo "<br>kode produk yang dipilih =  ".$code."<br>";
      echo "<br>tahun yang dipilih =  ".$year."<br>";
      //echo "<br>DP  =  ".$dp."<br>";
      echo "</pre>";
      
      $groupby = $this->input->post('groupby');
      if ($groupby == '1') {
        $data['page_content'] = 'all_sales/view_sales_per_product_group_by_kodeprod';
      }else{
        $data['page_content'] = 'all_sales/view_sales_per_product';
      }
      $data['menu']=$this->db->query($this->querymenu);
     
      $data['tahun'] = $year;
      $data['uv'] = $uv;
      $data['code'] = $code;
      $data['query']=$this->model_sales->list_dp();
      $data['products']=$this->model_sales->sales_per_product($data);

      $this->template($data['page_content'],$data);            
    }

    function get_sales_product(){

      $id_user=$this->session->userdata('id');
      $draw=$_REQUEST['draw'];
      $length=$_REQUEST['length'];
      $start=$_REQUEST['start'];
      $search=$_REQUEST['search']["value"];
      $this->db->where('id', $id_user);
      $total=$this->db->count_all_results("soprod_new");
      $output=array();
      $output['draw']=$draw;
      $output['recordsTotal']=$output['recordsFiltered']=$total;
      $output['data']=array();
      if($search!=""){
      $this->db->like("nama_comp",$search);
      }
      $this->db->limit($length,$start);
      $this->db->order_by('urutan','ASC');
      $this->db->where('id', $id_user);
      $query=$this->db->get('soprod_new');
  
      if($search!=""){
      $this->db->like("nama_comp",$search);
      $jum=$this->db->get('soprod_new');
      $output['recordsTotal']=$output['recordsFiltered']=$jum->num_rows();
      }
  
      $nomor_urut=$start+1;
      foreach ($query->result_array() as $kode) {
        $output['data'][]=array(
          $nomor_urut,
          $kode['nama_comp'],
          number_format($kode['t1']),
          number_format($kode['b1']),
          number_format($kode['t2']),
          number_format($kode['b2']),
          number_format($kode['t3']),
          number_format($kode['b3']),
          number_format($kode['t4']),
          number_format($kode['b4']),
          number_format($kode['t5']),
          number_format($kode['b5']),
          number_format($kode['t6']),
          number_format($kode['b6']),
          number_format($kode['t7']),
          number_format($kode['b7']),
          number_format($kode['t8']),
          number_format($kode['b8']),
          number_format($kode['t9']),
          number_format($kode['b9']),
          number_format($kode['t10']),
          number_format($kode['b10']),
          number_format($kode['t11']),
          number_format($kode['b11']),
          number_format($kode['t12']),
          number_format($kode['b12'])
        );
      $nomor_urut++;
      }
  
      echo json_encode($output);
  }

  function get_sales_product_group_by_kodeprod(){

    $id_user=$this->session->userdata('id');
    $draw=$_REQUEST['draw'];
    $length=$_REQUEST['length'];
    $start=$_REQUEST['start'];
    $search=$_REQUEST['search']["value"];
    $this->db->where('id', $id_user);
    $total=$this->db->count_all_results("soprod_new");
    $output=array();
    $output['draw']=$draw;
    $output['recordsTotal']=$output['recordsFiltered']=$total;
    $output['data']=array();
    if($search!=""){
    $this->db->like("nama_comp",$search);
    }
    $this->db->limit($length,$start);
    $this->db->order_by('urutan','ASC');
    $this->db->order_by('namaprod','ASC');
    $this->db->where('id', $id_user);
    $query=$this->db->get('soprod_new');

    if($search!=""){
    $this->db->like("nama_comp",$search);
    $jum=$this->db->get('soprod_new');
    $output['recordsTotal']=$output['recordsFiltered']=$jum->num_rows();
    }

    $nomor_urut=$start+1;
    foreach ($query->result_array() as $kode) {
      $output['data'][]=array(
        $nomor_urut,
        $kode['nama_comp'],
        $kode['namaprod'],
        number_format($kode['t1']),
        number_format($kode['b1']),
        number_format($kode['t2']),
        number_format($kode['b2']),
        number_format($kode['t3']),
        number_format($kode['b3']),
        number_format($kode['t4']),
        number_format($kode['b4']),
        number_format($kode['t5']),
        number_format($kode['b5']),
        number_format($kode['t6']),
        number_format($kode['b6']),
        number_format($kode['t7']),
        number_format($kode['b7']),
        number_format($kode['t8']),
        number_format($kode['b8']),
        number_format($kode['t9']),
        number_format($kode['b9']),
        number_format($kode['t10']),
        number_format($kode['b10']),
        number_format($kode['t11']),
        number_format($kode['b11']),
        number_format($kode['t12']),
        number_format($kode['b12'])
      );
    $nomor_urut++;
    }

    echo json_encode($output);
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
      $data['query']=$this->model_sales->list_dp();
      $data['products']=$this->model_sales->sales_per_product($data);

      $this->template($data['page_content'],$data);            
    }   

    public function sales_product_per_class_hasil(){

      $year = $this->input->post('year');
      $uv = $this->input->post('uv');

      $code=$this->input->post('kodeprod');
      $options=$this->input->post('options');
      
      foreach($options as $kode)
      {
          $code.=",".$kode;
      }
      $code=preg_replace('/,/', '', $code,1);
      echo "<pre>";
      echo "<br>kode produk yang dipilih =  ".$code."<br>";
      echo "<br>tahun yang dipilih =  ".$year."<br>";
      //echo "<br>DP  =  ".$dp."<br>";
      echo "</pre>";
      
      $data['page_content'] = 'all_sales/view_sales_per_product_per_class';
      $data['menu']=$this->db->query($this->querymenu);
     
      $data['tahun'] = $year;
      $data['uv'] = $uv;
      $data['code'] = $code;
      $data['query']=$this->model_sales->list_dp();
      $kondisi_class = $this->input->post('kondisi_class');
      if ($kondisi_class == '1') {
        $data['products']=$this->model_sales->sales_per_product_per_class($data);
      }else{
        $data['products']=$this->model_sales->sales_per_product_per_class_current($data);
      }      

      $this->template($data['page_content'],$data);                
    }

    public function export() {
        
      $segment3 = $this->uri->segment('3');
      $id_user=$this->session->userdata('id');

      // echo "id : ".$id_user;
      
      $this->db->order_by('urutan','asc');     
      $this->db->order_by('kodeprod','asc');       
      $this->db->where("soprod_new.id = ".'"'.$id_user.'"');
      $hasil = $this->db->get('soprod_new');   
     
      $this->excel_generator->set_query($hasil);
      // echo "<pre>";
      // print_r($hasil);
      // echo "hasil : ".$hasil;
      // echo "</pre>";
      $this->excel_generator->set_header(array
        (
           'KodeComp','Branch','SubBranch', 'T1','Jan','T2', 'Feb','T3','Mar','T4','Apr','T5','Mei','T6','Jun','T7', 'Jul','T8','Agus','T9','Sep','T10','Okt','T11','Nov','T12','Des'
        ));
      $this->excel_generator->set_column(array
        ( 
          'kode_comp',
          'branch_name',
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
      $this->excel_generator->set_width(array(7, 15,15, 7,13,7,13,7,13,7,13,7,13,7,13,7,13,7,13,7,13,7,13,7,13,7,13));
       
      $this->excel_generator->exportTo2007('SalesPerProduct'.'_'.$segment3);    
  }

  public function export_group_by_kodeprod() {
      
    $segment3 = $this->uri->segment('3');
    //$segment4 = $this->uri->segment('4');
    $id_user=$this->session->userdata('id');

    //$this->db->join('tbl_tabcomp', 'tbl_tabcomp.naper = stokprod_new.naper','inner');
    //$this->db->where("status = 1");
    
    $this->db->order_by('urutan','asc');     
    $this->db->order_by('kodeprod','asc');       
    $this->db->where("soprod_new.id = ".'"'.$id_user.'"');
    $hasil = $this->db->get('soprod_new');   
   
    $this->excel_generator->set_query($hasil);
    $this->excel_generator->set_header(array
      (
         'KodeComp','Branch','SubBranch','Kodeprod','Namaprod','Group','NamaGroup', 'T1','Jan','T2', 'Feb','T3','Mar','T4','Apr','T5','Mei','T6','Jun','T7', 'Jul','T8','Agus','T9','Sep','T10','Okt','T11','Nov','T12','Des'
      ));
    $this->excel_generator->set_column(array
      ( 
        'kode_comp',
        'branch_name',
        'nama_comp', 
        'kodeprod',
        'namaprod',
        'group',
        'nama_group',
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
    $this->excel_generator->set_width(array(7, 15,15,10,10,10,10, 7,13,7,13,7,13,7,13,7,13,7,13,7,13,7,13,7,13,7,13,7,13,7,13));
    //$this->excel_generator->exportTo2007('Omzet'.'_'.$tahun.'_'.$supplier);   
    $this->excel_generator->exportTo2007('SalesPerProduct_GroupByKodeprod'.'_'.$segment3);    
}

    public function export_per_class() {
        
        $segment3 = $this->uri->segment('3');
        //$segment4 = $this->uri->segment('4');
        $id_user=$this->session->userdata('id');

        //$this->db->join('tbl_tabcomp', 'tbl_tabcomp.naper = stokprod_new.naper','inner');
        //$this->db->where("status = 1");
        $this->db->order_by('urutan','asc');           
        $this->db->where("soprod_class.id = ".'"'.$id_user.'"');
        $hasil = $this->db->get('soprod_class');   
       
        $this->excel_generator->set_query($hasil);
        $this->excel_generator->set_header(array
          (
             'KodeComp','SubBranch', 'Class', 'T1','Jan','T2', 'Feb','T3','Mar','T4','Apr','T5','Mei','T6','Jun','T7', 'Jul','T8','Agus','T9','Sep','T10','Okt','T11','Nov','T12','Des'
          ));
        $this->excel_generator->set_column(array
          ( 
            'kode_comp',
            'nama_comp', 
            'jenis',
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
        $this->excel_generator->set_width(array(7, 20, 10, 7,13,7,13,7,13,7,13,7,13,7,13,7,13,7,13,7,13,7,13,7,13,7,13));
        //$this->excel_generator->exportTo2007('Omzet'.'_'.$tahun.'_'.$supplier);   
        $this->excel_generator->exportTo2007('SalesPerProduct(class)'.'_'.$segment3);   
    }

    public function sell_out_product_unilever()
    {
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
            $data['page_content'] = 'all_sales/view_sales_per_product_kosong_unilever';
            $data['query'] = $this->model_sales->getSuppbyid();
            $data['url'] = 'all_sales/sales_product_hasil/';
            $data['page_title'] = 'Sales Per Product';
            $data['menu']=$this->db->query($this->querymenu);
            $data['query']=$this->model_sales->list_product_unilever();
            //$data['query2']=$this->model_sales->list_dp();
            $this->template($data['page_content'],$data);    
    }

    public function outlet_transaksi_class(){

        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
            $data['page_content'] = 'all_sales/outlet_transaksi_class_kosong';
            //$data['query'] = $this->model_sales->getSuppbyid();
            $data['url'] = 'all_sales/outlet_transaksi_class_hasil/';
            $data['page_title'] = 'Outlet Transaksi (YTD)';
            $data['menu']=$this->db->query($this->querymenu);
            $data['query']=$this->model_sales->list_product();
            //$data['query2']=$this->model_sales->list_dp();
            $this->template($data['page_content'],$data);            
    }


    public function outlet_transaksi_class_hasil()
    {
      $data['id']=$this->session->userdata('id');
      $start_year   = $this->input->post('start_year');
      $start_month  = $this->input->post('start_month');
      $end_year     = $this->input->post('end_year');
      $end_month    = $this->input->post('end_month');
      $code=$this->input->post('kodeprod');
      $options=$this->input->post('options');
      
      foreach($options as $kode)
      {
          $code.=",".$kode;
      }
      $code=preg_replace('/,/', '', $code,1);
      $group = $this->input->post('group');
      // echo "group : ".$group;

      $data['page_content'] = 'all_sales/view_ot_class';
      $data['menu']=$this->db->query($this->querymenu);
      $data['start_year'] = $start_year;
      $data['start_month'] = $start_month;
      $data['end_year'] = $end_year;
      $data['end_month'] = $end_month;
      $data['code'] = $code;
      $data['query']=$this->model_sales->list_dp();

      if ($group == '0') {
        $data['judul'] = 'None';
        $data['hasil']=$this->model_sales->ot($data);
      }elseif ($group == '1') {
        $data['judul'] = 'Class';
        $data['hasil']=$this->model_sales->ot_per_class($data);
      }elseif ($group == '2') {
        $data['judul'] = 'Tipe';
        $data['hasil']=$this->model_sales->ot_per_type($data);
      }
      
      $this->template($data['page_content'],$data);  

    }


    public function export_ot_class() {
        
        $segment3 = $this->uri->segment('3');
        //echo "id : ".$segment3;
        
        $this->db->order_by('urutan','asc');           
        $this->db->where("tbl_ot_class.id = ".'"'.$segment3.'"');
        $hasil = $this->db->get('tbl_ot_class');   
       
        $this->excel_generator->set_query($hasil);
        $this->excel_generator->set_header(array
          (
             'KodeComp','SubBranch', 'Class/Tipe','Ytd'
          ));
        $this->excel_generator->set_column(array
          ( 
            'kode_comp',
            'nama_comp',
            'jenis',
            'ytd'
          ));
        $this->excel_generator->set_width(array(7, 20, 7,13));
        //$this->excel_generator->exportTo2007('Omzet'.'_'.$tahun.'_'.$supplier);   
        $this->excel_generator->exportTo2007('OT (ytd)');   
    }

    public function error(){
      $this->load->view('info_ot_error');
    }

    function build_namacomp()
    {
        //$this->load->model('basic_model','bmodel');
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
        $year = $this->input->post('id_year',TRUE);
        $output="<option value=''>- Pilih Sub Branch -</option>";
        //$query=$this->model_stock->get_namacomp($dp);

        $data['year'] = $year;
        $query=$this->model_sales->get_namacomp($data);
        foreach($query->result() as $row)
        {
            $output .= "<option value='".$row->nocab."'>".$row->nama_comp."</option>";
        }
        //$output="<option value=''>".$dp."</option>";
        echo $output;
    }

    public function sell_in()
    {
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
            $data['page_content'] = 'all_sales/view_sell_in_kosong';
            $data['url'] = 'all_sales/sell_in_hasil/';
            $data['page_title'] = 'Sell IN';
            $data['query'] = $this->model_sales->getSuppbyid();
            $data['menu_uri1'] = $this->uri->segment('1');
            $data['menu_uri2'] = $this->uri->segment('2');
            $data['menu_uri3'] = $this->uri->segment('3');

            $data['menu']=$this->db->query($this->querymenu);
            $this->template($data['page_content'],$data);
    }

    public function sell_in_hasil()
    {
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
            $data['page_content'] = 'all_sales/view_sell_in';
            $data['url'] = 'all_sales/sell_in_hasil/';
            $data['page_title'] = 'Sell IN';
            $data['year'] = $this->input->post('year');
            $data['nocab'] = $this->input->post('nocab');
            $data['uv'] = $this->input->post('uv');
           /*
            echo "year : ".$year;
            echo "nocab : ".$nocab;
            echo "uv : ".$uv;
          */

            $data['menu']=$this->db->query($this->querymenu);
            $data['query'] = $this->model_sales->sell_in($data);
            $this->template($data['page_content'],$data);


    }

    public function export_sell_in() {
        
        $id=$this->session->userdata('id');   
        $this->db->where("tbl_sell_in.id = ".'"'.$id.'"');
        $hasil = $this->db->get('tbl_sell_in');   
       
        $this->excel_generator->set_query($hasil);
        $this->excel_generator->set_header(array
          (
             'nocab','nama_comp', 'kodeprod', 'namaprod',
             'b1_a','b1_b','b1_c','b1_d',
             'b2_a','b2_b','b2_c','b2_d',
             'b3_a','b3_b','b3_c','b3_d',
             'b4_a','b4_b','b4_c','b4_d',
             'b5_a','b5_b','b5_c','b5_d',
             'b6_a','b6_b','b6_c','b6_d',
             'b7_a','b7_b','b7_c','b7_d',
             'b8_a','b8_b','b8_c','b8_d',
             'b9_a','b9_b','b9_c','b9_d',
             'b10_a','b10_b','b10_c','b10_d',
             'b11_a','b11_b','b11_c','b11_d',
             'b12_a','b12_b','b12_c','b12_d'
          ));
        $this->excel_generator->set_column(array
          ( 
            'nocab','nama_comp', 'kodeprod', 'namaprod',
             'b1_a','b1_b','b1_c','b1_d',
             'b2_a','b2_b','b2_c','b2_d',
             'b3_a','b3_b','b3_c','b3_d',
             'b4_a','b4_b','b4_c','b4_d',
             'b5_a','b5_b','b5_c','b5_d',
             'b6_a','b6_b','b6_c','b6_d',
             'b7_a','b7_b','b7_c','b7_d',
             'b8_a','b8_b','b8_c','b8_d',
             'b9_a','b9_b','b9_c','b9_d',
             'b10_a','b10_b','b10_c','b10_d',
             'b11_a','b11_b','b11_c','b11_d',
             'b12_a','b12_b','b12_c','b12_d'
          ));
        $this->excel_generator->set_width(array(4,10,6,10, 7,7,7,7, 7,7,7,7, 7,7,7,7, 7,7,7,7, 7,7,7,7, 7,7,7,7, 7,7,7,7, 7,7,7,7, 7,7,7,7, 7,7,7,7, 7,7,7,7, 7,7,7,7));
        //$this->excel_generator->exportTo2007('Omzet'.'_'.$tahun.'_'.$supplier);   
        $this->excel_generator->exportTo2007('Sell In');   
    }

    public function form_sell_out_nasional()
    {
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
        $data['page_content'] = 'all_sales/form_sell_out_nasional';
        $data['url'] = 'all_sales/sell_out_nasional/';
        $data['menu']=$this->db->query($this->querymenu);
        $data['query']=$this->model_sales->list_product();
        $this->template($data['page_content'],$data);            
    
    }


    public function sell_out_nasional()
    {
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }

        $data['page_content'] = 'all_sales/view_sell_out_nasional';
        $data['url'] = 'all_sales/sell_out_nasional/';
        $data['year'] = $this->input->post('tahun');
        $data['bulan'] = $this->input->post('periode');
        $data['supp'] = $this->input->post('supp');
        $data['group'] = $this->input->post('group');
        /*
        echo "year : ".$data['year'];
        echo "bulan : ".$data['bulan'];
        echo "supp : ".$data['supp'];
        echo "group : ".$data['group'];
        */
        $data['menu']=$this->db->query($this->querymenu);
        $data['query']=$this->model_sales->list_product();
        $data['data'] = $this->model_sales->sell_out_nasional($data);
        $this->template($data['page_content'],$data);
    }

    public function sell_out_nasional_export() {
        
        $id=$this->session->userdata('id');   
        $this->db->where("tbl_sell_out_nasional.id = ".'"'.$id.'"');
        $hasil = $this->db->get('tbl_sell_out_nasional');   
       
        $this->excel_generator->set_query($hasil);
        $this->excel_generator->set_header(array
          (
             'kodeprod','namaprod', 'unit', 'value',
             'bulan'
          ));
        $this->excel_generator->set_column(array
          ( 
            'kodeprod','namaprod', 'unit', 'value',
             'bulan'
          ));
        $this->excel_generator->set_width(array(4,20,6,10,3));
        //$this->excel_generator->exportTo2007('Omzet'.'_'.$tahun.'_'.$supplier);   
        $this->excel_generator->exportTo2007('Sell Out Nasional');   
    }

    public function sales_product_ob(){

        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
            $data['page_content'] = 'all_sales/form_sales_per_product_ob';
            //$data['query'] = $this->model_sales->getSuppbyid();
            $data['url'] = 'all_sales/sales_product_ob_hasil/';
            $data['page_title'] = 'Sales Per Product & Outlet Binaan';
            $data['menu']=$this->db->query($this->querymenu);
            $data['query']=$this->model_sales->list_product();
            //$data['query2']=$this->model_sales->list_dp();
            $this->template($data['page_content'],$data);            
    }

    public function sales_product_ob_hasil(){

      $year = $this->input->post('year');
      $bulan=$this->input->post('bulan');
      $code=$this->input->post('kodeprod');
      $options=$this->input->post('options');
      $branch=$this->input->post('branch');

      foreach($options as $kode)
      {
          $code.=",".$kode;
      }
      $code=preg_replace('/,/', '', $code,1);
      /*echo "<pre>";
      echo "<br>kode produk =  ".$code;
      echo "<br>tahun =  ".$year;
      echo "<br>bulan =  ".$bulan;
      echo "<br>uv =  ".$uv;
      echo "<br>branch =  ".$branch;
      echo "</pre>";*/
      
      $data['page_content'] = 'all_sales/view_sales_per_product_ob';
      $data['menu']=$this->db->query($this->querymenu);
     
      $data['tahun'] = $year;
      $data['code'] = $code;
      $data['bulan'] = $bulan;
      $data['branch'] = $branch;
      
      $data['query']=$this->model_sales->list_subbranch($data);

      $data['proses']=$this->model_sales->sales_per_product_ob($data);
      $this->template($data['page_content'],$data);            
      
    }


    public function export_ob() {
        
        $id_user=$this->session->userdata('id');
         
        $this->db->where("tbl_sales_per_product_ob.userid = ".'"'.$id_user.'"');
        $hasil = $this->db->get('mpm.tbl_sales_per_product_ob');   
       
        $this->excel_generator->set_query($hasil);
        $this->excel_generator->set_header(array
          (
             'SubBranch', 'Tahun', 'Bulan','OT','Unit', 'Value','OT OB','Unit OB','Value OB'
          ));
        $this->excel_generator->set_column(array
          ( 
            'nama_comp',
            'tahun', 
            'bulan',
            'ot',
            'unit',
            'value',
            'ot_ob', 
            'unit_ob',
            'value_ob'
          ));
        $this->excel_generator->set_width(array(20,7,7,7,13,20,7,13,20));
        //$this->excel_generator->exportTo2007('Omzet'.'_'.$tahun.'_'.$supplier);   
        $this->excel_generator->exportTo2007('SalesPerProduct_OB');   
    }
}
?>
