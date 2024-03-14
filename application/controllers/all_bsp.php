<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class All_bsp extends MY_Controller
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

    function All_bsp()
    {
        set_time_limit(0);
        $this->load->library(array('table','template','Excel_generator', 'form_validation'));
        $this->load->helper('url');
        $this->load->model('model_bsp');
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
        //echo "suffy";
        $this->data_omzet_bsp();
    }

    private function template($view,$data)
    {
        $this->template->set_title('MPM SQUARE');
        $this->template->add_js('modules/skeleton.js');
        $this->template->add_css('modules/skeleton.css');
        $this->template->load_view($view, $data);
    }

    public function data_omzet_bsp(){

        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
            $data['page_content'] = 'bsp/view_bsp_omzet_kosong';
            $data['query'] = $this->model_bsp->getSuppbyid();
            $data['url'] = 'bsp/data_omzet_bsp_hasil/';
            $data['page_title'] = 'BSP omzet';
            $data['menu']=$this->db->query($this->querymenu);
            $this->template($data['page_content'],$data);
            
    }

    public function data_omzet_bsp_hasil(){

        $data['query'] = $this->model_bsp->getSuppbyid();
        $data['menu']=$this->db->query($this->querymenu);
        $data['year']=$this->input->post('tahun');
        $year = $this->input->post('tahun');
        //echo "tahun : ".$year."<br>";
        $data['tahun'] = $year;
        $supplier = $this->input->post('supp');
        //echo "supplier : ".$supplier."<br>";
        $data['supp'] = $supplier;

        if ($supplier == 'x') {
          redirect('all_bsp', 'refresh');
        } else {
          
        }
        

        $group=$this->input->post('group');
        //echo "group : ".$group;

        if ($group == '4') {

            $data['note'] ='Herbal';
            $data['note_x'] ='4';
            $data['page_content'] = 'bsp/view_omzet_bsp_herbal';
            //$data['query_dp']=$this->model_omzet->list_dp();
            //$data['getmenuid'] = $this->model_omzet->getmenuid($data);
            $data['omzets']=$this->model_bsp->omzet_all_bsp_herbal($data);
            $this->template($data['page_content'],$data);
        }
        elseif ($group == '5') {

            $data['note'] ='Candy';
            $data['note_x'] ='5';
            $data['page_content'] = 'bsp/view_omzet_bsp_herbal';
            //$data['query_dp']=$this->model_omzet->list_dp();
            //$data['getmenuid'] = $this->model_omzet->getmenuid($data);
            $data['omzets']=$this->model_bsp->omzet_all_bsp_herbal($data);
            $this->template($data['page_content'],$data);

        }elseif ($group == '12') {

            $data['note'] ='Beverage';
            $data['note_x'] ='12';
            $data['page_content'] = 'bsp/view_omzet_bsp_herbal';
            //$data['query_dp']=$this->model_omzet->list_dp();
            //$data['getmenuid'] = $this->model_omzet->getmenuid($data);
            $data['omzets']=$this->model_bsp->omzet_all_bsp_herbal($data);
            $this->template($data['page_content'],$data);
        }elseif ($group == '13') {

            $data['note'] ='Pilkita';
            $data['note_x'] ='13';
            $data['page_content'] = 'bsp/view_omzet_bsp_herbal';
            //$data['query_dp']=$this->model_omzet->list_dp();
            //$data['getmenuid'] = $this->model_omzet->getmenuid($data);
            $data['omzets']=$this->model_bsp->omzet_all_bsp_herbal($data);
            $this->template($data['page_content'],$data);
        }elseif ($group == '14') {

            $data['note'] ='Others Pilkita';
            $data['note_x'] ='14';
            $data['page_content'] = 'bsp/view_omzet_bsp_herbal';
            //$data['query_dp']=$this->model_omzet->list_dp();
            //$data['getmenuid'] = $this->model_omzet->getmenuid($data);
            $data['omzets']=$this->model_bsp->omzet_all_bsp_herbal($data);
            $this->template($data['page_content'],$data);
        }
        else{
            $data['page_content'] = 'bsp/view_omzet_bsp';
            $data['omzets']=$this->model_bsp->omzet_all_bsp($data);
            $this->template($data['page_content'],$data);

        }
        
    }

    function buildgroup()
    {
        //$this->load->model('basic_model','bmodel');
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
        $kode_supp = $this->input->post('kode_supp',TRUE);
        $output="<option value='0'>--</option>";
        //$query=$this->model_stock->get_namacomp($dp);

        $data['kode_supp'] = $kode_supp;
        $query=$this->model_bsp->get_group($data);
        foreach($query->result() as $row)
        {
            $output .= "<option value='".$row->id_group."'>".$row->nama_group."</option>";
        }
        echo $output;
    }

    public function export(){

      //$segment2 = $this->uri->segment('2');
      //$segment3 = $this->uri->segment('3');
      $id_user=$this->session->userdata('id');

      //$this->db->where("bsp.soprod.id = ".'"'.$id_user.'"');
      $this->db->where("bsp.omzet_new.id = ".'"'.$id_user.'"');
      $this->db->order_by('namacomp','asc');
      //$hasil = $this->db->get('bsp.soprod');
      $hasil = $this->db->get('bsp.omzet_new');
     
      $this->excel_generator->set_query($hasil);
      $this->excel_generator->set_header(array
        (
          'Cabang', 'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agus', 'Sep', 'Okt', 'Nov', 'Des'
        ));
      $this->excel_generator->set_column(array
        (
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
      $this->excel_generator->set_width(array(20, 15, 15, 15, 15, 15, 15, 15, 15, 15, 15, 15, 15));
      //$this->excel_generator->exportTo2007('Omzet'.'_'.$tahun.'_'.$supplier);   
      $this->excel_generator->exportTo2007('BSP'.'_Omzet');

    }

    public function export_group(){

      //$segment2 = $this->uri->segment('2');
      $tahun = $this->uri->segment('3');
      $id_user=$this->session->userdata('id');

      $query = "
        select * 
        from bsp.omzet_new_deltomed
        where id = '$id_user' and tahun = '$tahun' and `key` = (
          select max(`key`)
          from bsp.omzet_new_deltomed
          where tahun = '$tahun' and id = '$id_user'
        )
      ";

      $hasil = $this->db->query($query);

     
      $this->excel_generator->set_query($hasil);
      $this->excel_generator->set_header(array
        (
          'Cabang', 'b1_candy','b1_herbal', 
          'b2_candy','b2_herbal',
          'b3_candy','b3_herbal',
          'b4_candy','b4_herbal',
          'b5_candy','b5_herbal',
          'b6_candy','b6_herbal',
          'b7_candy','b7_herbal',
          'b8_candy','b8_herbal',
          'b9_candy','b9_herbal',
          'b10_candy','b10_herbal',
          'b11_candy','b11_herbal',
          'b12_candy','b12_herbal',
          'total_candy','total_herbal',
          'rata_candy','rata_herbal'
        ));
      $this->excel_generator->set_column(array
        (
          'namacomp',
          'b1_candy',
          'b1_herbal',
          'b2_candy',
          'b2_herbal',
          'b3_candy',
          'b3_herbal',
          'b4_candy',
          'b4_herbal',
          'b5_candy',
          'b5_herbal',
          'b6_candy',
          'b6_herbal',
          'b7_candy',
          'b7_herbal',
          'b8_candy',
          'b8_herbal',
          'b9_candy',
          'b9_herbal',
          'b10_candy',
          'b10_herbal',
          'b11_candy',
          'b11_herbal',
          'b12_candy',
          'b12_herbal',
          'total_candy',
          'total_herbal',
          'rata_candy',
          'rata_herbal'
        ));
      $this->excel_generator->set_width(array(20, 15, 15, 15, 15, 15, 15, 15, 15, 15, 15, 15, 15, 15, 15, 15, 15, 15, 15, 15, 15, 15, 15, 15, 15, 15, 15, 15, 15));
      //$this->excel_generator->exportTo2007('Omzet'.'_'.$tahun.'_'.$supplier);   
      $this->excel_generator->exportTo2007('BSP'.'_Omzet');

    }

    public function export_per_product(){

      //$segment2 = $this->uri->segment('2');
      //$segment3 = $this->uri->segment('3');
      $id_user=$this->session->userdata('id');

      $this->db->where("bsp.soprod.id = ".'"'.$id_user.'"');
      $this->db->order_by('nama_comp','asc');
      $hasil = $this->db->get('bsp.soprod');
     
      $this->excel_generator->set_query($hasil);
      $this->excel_generator->set_header(array
        (
          'Cabang', 'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agus', 'Sep', 'Okt', 'Nov', 'Des'
        ));
      $this->excel_generator->set_column(array
        (
          'nama_comp',
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
      $this->excel_generator->set_width(array(20, 15, 15, 15, 15, 15, 15, 15, 15, 15, 15, 15, 15));
      //$this->excel_generator->exportTo2007('Omzet'.'_'.$tahun.'_'.$supplier);   
      $this->excel_generator->exportTo2007('BSP'.'_SalesPerProduct');

    }

    public function export_outlet(){

      //$segment2 = $this->uri->segment('2');
      //$segment3 = $this->uri->segment('3');
      $id_user=$this->session->userdata('id');

      $this->db->where("bsp.outlet_new.id = ".'"'.$id_user.'"');
      $this->db->order_by('tipe,outlet','asc');
      $hasil = $this->db->get('bsp.outlet_new');
     
      $this->excel_generator->set_query($hasil);
      $this->excel_generator->set_header(array
        (
          'Kode', 'Tipe', 'Outlet', 'Address', 'Jan', 'Feb', 'Mar', 'Apr',  'Mei', 'Jun', 'Jul', 'Agus', 'Sep', 'Okt', 'Nov', 'Des', 'Rata'
        ));
      $this->excel_generator->set_column(array
        (
          'kode',
          'tipe',
          'outlet',
          'alamat',
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
          'rata'
        ));
      $this->excel_generator->set_width(array(12, 10, 15, 15, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10,10,10,10));
      //$this->excel_generator->exportTo2007('Omzet'.'_'.$tahun.'_'.$supplier);   
      $this->excel_generator->exportTo2007('BSP'.'_SalesOutlet');

    }

    public function bsp_per_product(){

        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
            $data['page_content'] = 'bsp/view_bsp_per_product_kosong';
            $data['query'] = $this->model_bsp->getSuppbyid();
            $data['url'] = 'all_bsp/bsp_per_product_hasil/';
            $data['page_title'] = 'Sales BSP Per Product';
            $data['menu']=$this->db->query($this->querymenu);
            $data['query']=$this->model_bsp->list_product();
            //$data['query2']=$this->model_sales->list_dp();
            $this->template($data['page_content'],$data);            
    }


    public function bsp_per_product_hasil(){

        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }

        $year = $this->input->post('year');
        $uv = $this->input->post('uv');

        $code=$this->input->post('kodeprod');
        $options=$this->input->post('options');
        
        foreach($options as $kode)
            {
                $code.=",".'"'.$kode.'"';
            }

        $code=preg_replace('/,/', '', $code,1);

        /*
        echo "<pre>";
        print_r($code);
        echo "</pre>";
        echo "<br>";
        */

        echo "<pre>";
        echo "<br>kode produk yang dipilih =  ".$code."<br>";
        echo "<br>tahun yang dipilih =  ".$year."<br>";
        //echo "<br>DP  =  ".$dp."<br>";
        echo "</pre>";
        
        $data['page_content'] = 'bsp/view_bsp_per_product';
        $data['menu']=$this->db->query($this->querymenu);
       
        $data['tahun'] = $year;
        $data['uv'] = $uv;
        $data['code'] = $code;
        //$data['query2']=$this->model_bsp->list_bsp();
        $data['products']=$this->model_bsp->bsp_per_product($data);

        $this->template($data['page_content'],$data);      
    }

    public function data_outlet(){

        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
            $data['page_content'] = 'bsp/view_bsp_outlet_kosong';
            $data['url'] = 'all_bsp/data_outlet_hasil/';
            //$data['url'] = 'outlet/view_outlet/';
            $data['page_title'] = 'BSP Sales Outlet';
            $data['query2']=$this->model_bsp->list_bsp_outlet();
            $data['query']=$this->model_bsp->list_product(); 
            $data['menu']=$this->db->query($this->querymenu);
            $this->template($data['page_content'],$data);

    }

    public function data_outlet_hasil(){

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
      echo "<br>kode dp yang dipilih =  ".$dp."<br>";
      echo "<br>kode sales yang dipilih =  ".$sm."<br>";
      echo "<br>tahun yang dipilih =  ".$year."<br>";
      echo "</pre>";
      
      $data['page_content'] = 'bsp/view_bsp_outlet';
      $data['menu']=$this->db->query($this->querymenu);

      $data['tahun'] = $year;
      $data['dp'] = $dp;
      $data['uv'] = $uv;
      $data['sm'] = $sm;
      $data['code'] = $code;
      $data['outlets']=$this->model_bsp->outlet_bsp($data);

      $this->template($data['page_content'],$data);

    }

    public function ambil_data()
    {

      $id_user=$this->session->userdata('id');

      /*Menagkap semua data yang dikirimkan oleh client*/

      /*Sebagai token yang yang dikrimkan oleh client, dan nantinya akan
      server kirimkan balik. Gunanya untuk memastikan bahwa user mengklik paging
      sesuai dengan urutan yang sebenarnya */
      $draw=$_REQUEST['draw'];

      /*Jumlah baris yang akan ditampilkan pada setiap page*/
      $length=$_REQUEST['length'];

      /*Offset yang akan digunakan untuk memberitahu database
      dari baris mana data yang harus ditampilkan untuk masing masing page
      */
      $start=$_REQUEST['start'];

      /*Keyword yang diketikan oleh user pada field pencarian*/
      $search=$_REQUEST['search']["value"];

      /*Menghitung total row didalam database*/
      $this->db->where('id', $id_user);
      $total=$this->db->count_all_results("bsp.outlet_new");

      /*Mempersiapkan array tempat kita akan menampung semua data
      yang nantinya akan server kirimkan ke client*/
      $output=array();

      /*Token yang dikrimkan client, akan dikirim balik ke client*/
      $output['draw']=$draw;

      /*
      $output['recordsTotal'] adalah total data sebelum difilter
      $output['recordsFiltered'] adalah total data ketika difilter
      Biasanya kedua duanya bernilai sama, maka kita assignment 
      keduaduanya dengan nilai dari $total
      */
      $output['recordsTotal']=$output['recordsFiltered']=$total;

      /*disini nantinya akan memuat data yang akan kita tampilkan 
      pada table client*/
      $output['data']=array();


      /*Jika $search mengandung nilai, berarti user sedang telah 
      memasukan keyword didalam filed pencarian*/
      if($search!=""){
      $this->db->like("kode",$search);
      }

      /*Lanjutkan pencarian ke database*/
      $this->db->limit($length,$start);
      /*Urutkan dari alphabet paling terkahir*/
      $this->db->order_by('tipe,outlet','ASC');
      $this->db->where('id', $id_user);
      $query=$this->db->get('bsp.outlet_new');


      /*Ketika dalam mode pencarian, berarti kita harus
      'recordsTotal' dan 'recordsFiltered' sesuai dengan jumlah baris
      yang mengandung keyword tertentu
      */
      if($search!=""){
      $this->db->like("kode",$search);
      $jum=$this->db->get('bsp.outlet_new');
      $output['recordsTotal']=$output['recordsFiltered']=$jum->num_rows();
      }


      $nomor_urut=$start+1;
      foreach ($query->result_array() as $kode) {
        $output['data'][]=array(
          $nomor_urut,
          $kode['kode'],
          $kode['outlet'],
          $kode['tipe'],
          $kode['alamat'],
          $kode['b1'],
          $kode['b2'],
          $kode['b3'],
          $kode['b4'],
          $kode['b5'],
          $kode['b6'],
          $kode['b7'],
          $kode['b8'],
          $kode['b9'],
          $kode['b10'],
          $kode['b11'],
          $kode['b12'],
          
        );
      $nomor_urut++;
      }

      echo json_encode($output);
  }
    
  public function sell_out(){

    $logged_in= $this->session->userdata('logged_in');
    if(!isset($logged_in) || $logged_in != TRUE)
    {
        redirect('login/','refresh');
    }
        $data['page_content'] = 'bsp/view_sell_out';
        $data['query'] = $this->model_bsp->getSuppbyid();
        $data['url'] = 'all_bsp/sell_out_hasil/';
        $data['page_title'] = 'Sell Out BSP';
        $data['menu']=$this->db->query($this->querymenu);
        $this->template($data['page_content'],$data);

  }

  public function sell_out_hasil(){

      $logged_in= $this->session->userdata('logged_in');
      if(!isset($logged_in) || $logged_in != TRUE)
      {
          redirect('login/','refresh');
      }

      $year = $this->input->post('tahun');
      $uv = $this->input->post('uv');
      /*
      echo "<pre>";
      echo "<br>tahun yang dipilih =  ".$year."<br>";
      echo "<br>Unit / Value  =  ".$uv."<br>";
      echo "</pre>";
      */
      $data['page_content'] = 'bsp/view_sell_out_hasil';
      $data['menu']=$this->db->query($this->querymenu);
      $data['url'] = 'all_bsp/sell_out_hasil/';
      $data['tahun'] = $year;
      $data['uv'] = $uv;
      $data['page_title'] = 'Sell Out BSP';
      
      $data['hasil']=$this->model_bsp->sell_out($data);

      $this->template($data['page_content'],$data);  

  }

  public function export_sell_out(){

      //$segment2 = $this->uri->segment('2');
      //$segment3 = $this->uri->segment('3');
      $id_user=$this->session->userdata('id');

      $this->db->where("bsp.sobsp.id = ".'"'.$id_user.'"');
      $this->db->order_by('deskripsi','asc');
      $hasil = $this->db->get('bsp.sobsp');
     
      $this->excel_generator->set_query($hasil);
      $this->excel_generator->set_header(array
        (
          'KodeProduk', 'NamaProduk', 'Rata', 'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agus', 'Sep', 'Okt', 'Nov', 'Des'
        ));
      $this->excel_generator->set_column(array
        (
          'kode_bsp',
          'deskripsi',
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
          'b12'
        ));
      $this->excel_generator->set_width(array(15, 20, 15, 15, 15, 15, 15, 15, 15, 15, 15, 15, 15, 15, 15));
      //$this->excel_generator->exportTo2007('Omzet'.'_'.$tahun.'_'.$supplier);   
      $this->excel_generator->exportTo2007('BSP'.'_SellOut');

    }

    public function stok_bsp_nasional()
    {
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
            $data['page_content'] = 'bsp/view_stok_bsp_nasional';
            $data['query'] = $this->model_bsp->getSuppbyid();
            $data['url'] = 'all_bsp/stok_bsp_nasional_hasil/';
            $data['page_title'] = 'Stok BSP Nasional';
            $data['menu']=$this->db->query($this->querymenu);
            $this->template($data['page_content'],$data);

    }

    public function stok_bsp_nasional_hasil()
    {
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
            $data['url'] = 'all_bsp/stok_bsp_nasional_hasil/';
            $data['page_title'] = 'Stok BSP Nasional';
            $data['menu']=$this->db->query($this->querymenu);
            $data['query'] = $this->model_bsp->getSuppbyid();
            $data['year']=$this->input->post('tahun');
            $year = $this->input->post('tahun');
            //echo "tahun : ".$year."<br>";
            $data['tahun'] = $year;
            $supplier = $this->input->post('supp');
            //echo "supplier : ".$supplier."<br>";
            $data['supp'] = $supplier;
            $group=$this->input->post('group');
            //echo "group : ".$group;

        if ($group == '4') {

            $data['note'] ='Herbal';
            $data['note_x'] ='4';
            $data['page_content'] = 'bsp/view_stok_bsp_nasional_hasil';
            $data['stoks']=$this->model_bsp->stock_bsp_nasional($data);
            $this->template($data['page_content'],$data);
        }
        elseif ($group == '5') {

            $data['note'] ='Candy';
            $data['note_x'] ='5';
            $data['page_content'] = 'bsp/view_stok_bsp_nasional_hasil';
            $data['stoks']=$this->model_bsp->stock_bsp_nasional($data);
            $this->template($data['page_content'],$data);

        }elseif ($group == '12') {

            $data['note'] ='Beverages';
            $data['note_x'] ='12';
            $data['page_content'] = 'bsp/view_stok_bsp_nasional_hasil';
            $data['stoks']=$this->model_bsp->stock_bsp_nasional($data);
            $this->template($data['page_content'],$data);

        }elseif ($group == '13') {

            $data['note'] ='Pilkita';
            $data['note_x'] ='13';
            $data['page_content'] = 'bsp/view_stok_bsp_nasional_hasil';
            $data['stoks']=$this->model_bsp->stock_bsp_nasional($data);
            $this->template($data['page_content'],$data);

        }elseif ($group == '14') {

            $data['note'] ='Others Pilkita';
            $data['note_x'] ='14';
            $data['page_content'] = 'bsp/view_stok_bsp_nasional_hasil';
            $data['stoks']=$this->model_bsp->stock_bsp_nasional($data);
            $this->template($data['page_content'],$data);

        }elseif ($group == '0' && $supplier = 'XXX') {

            $data['note'] ='ALL';
            $data['note_x'] ='';
            $data['page_content'] = 'bsp/view_stok_bsp_nasional_hasil';
            $data['stoks']=$this->model_bsp->stock_bsp_nasional($data);
            $this->template($data['page_content'],$data);

        }
        else{
            $data['note'] ='';
            $data['note_x'] ='';
            $data['page_content'] = 'bsp/view_stok_bsp_nasional_hasil';
            $data['stoks']=$this->model_bsp->stock_bsp_nasional($data);
            $this->template($data['page_content'],$data);

        }

    }

    public function export_stok() {

        $this->load->library(array('Excel_generator'));
        $id_user=$this->session->userdata('id');
        
        $query="
              select * 
              from        bsp.stokbsp
              where id = $id_user
              order by deskripsi
          ";
                          
          $hasil = $this->db->query($query);
   
          $this->excel_generator->set_query($hasil);
          $this->excel_generator->set_header(array
            (
              'kodeprod', 'deskripsi','b1','b2','b3','b4','b5','b6','b7','b8','b9','b10','b11','b12'
            ));
            $this->excel_generator->set_column(array
              (
                'kodeprod', 
                'deskripsi', 
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
        
        $this->excel_generator->set_width(array(15,15,8,8,8,8,8,8,8,8,8,8,8,8));
        $this->excel_generator->exportTo2007('Stock BSP');   
    }
     
}
?>
