<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Outlet extends MY_Controller
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

    function Outlet()
    {
        set_time_limit(0);
        $this->load->library(array('table','template','Excel_generator'));
        $this->load->helper('url');
        $this->load->helper('csv');
        $this->load->model(array('model_outlet','m_dp'));
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

        $data['page_content'] = 'sales/form_outlet';         
        
        //$data['omzets']=$this->model_omzet->omzet_all();
        $data['menu']=$this->db->query($this->querymenu);
        $this->template($data['page_content'],$data);
        
        
    }
    private function template($view,$data)
    {
        $this->template->set_title('MPM SQUARE');
        $this->template->add_js('modules/skeleton.js');
        $this->template->add_css('modules/skeleton.css');
        $this->template->load_view($view, $data);
    }

    public function data_outlet(){

        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
            $data['page_content'] = 'outlet/view_outlet_kosong';
            $data['url'] = 'outlet/data_outlet_hasil/';
            $data['page_title'] = 'sales outlet';
            //$data['query2']=$this->model_outlet->list_dp_outlet();
            $data['query']=$this->model_outlet->list_product();
            $data['menu']=$this->db->query($this->querymenu);
            $this->template($data['page_content'],$data);
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
        $query=$this->model_outlet->get_namacomp($data);
        foreach($query->result() as $row)
        {
            $output .= "<option value='".$row->kode."'>".$row->nama_comp."</option>";
        }
        //$output="<option value=''>".$dp."</option>";
        echo $output;
    }

    function buildSalesName()
    {
        //$this->load->model('basic_model','bmodel');
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
        $dp = $this->input->post('id_subbranch',TRUE);
        $output="<option value=''>All Salesman</option>";
        
        //$data['dp'] = substr($dp, 4,2);
        //$data['dp'] = 'a2';
        
        
        $data['dp'] = substr($dp, 3);
        
        //echo $dp;
        $query=$this->model_outlet->getSalesName($data);
        foreach($query->result() as $row)
        {
            $output .= "<option value='".$row->kodesales."'>".$row->namasales."</option>";
        }
        //$output="<option value=''>".$dp."</option>";
        echo $output;
    }

    public function data_outlet_hasil(){
  
      $year = $this->input->post('year');
      $dp = $this->input->post('nocab');
      $uv = $this->input->post('uv');
      $sm = $this->input->post('sm');
      $bd = $this->input->post('bd');

      $code=$this->input->post('kodeprod');
      $options=$this->input->post('options');
      
      foreach($options as $kode)
      {
          $code.=",".$kode;
      }
      $code=preg_replace('/,/', '', $code,1);
      // $dp =  implode(", ", $subbranch); 
      echo "<pre>";
      echo "<br>kode produk yang dipilih =  ".$code."<br>";
      // echo "<br>kode dp yang dipilih =  ".$dp."<br>";
      echo "<br>kode sales yang dipilih =  ".$sm."<br>";
      echo "<br>tahun yang dipilih =  ".$year."<br>";
      echo "<br>breakdown yang dipilih =  ".$bd."<br>";
      echo "</pre>";
      
      if($bd==1){
        $data['page_content'] = 'outlet/table_view_bd';
      }else{
        $data['page_content'] = 'outlet/table_view';
      }
      
      $data['menu']=$this->db->query($this->querymenu);

      $data['tahun'] = $year;
      $data['dp'] = $dp;
      $data['uv'] = $uv;
      $data['sm'] = $sm;
      $data['code'] = $code;
      $data['bd'] = $bd;
      $data['outlets']=$this->model_outlet->outlet_dp($data);

      $this->template($data['page_content'],$data);      

    }

    public function export_outlet() {
        
      //$segment2 = $this->uri->segment('2');
      $segment3 = $this->uri->segment('3');
      $id_user=$this->session->userdata('id');

      //$this->db->join('tbl_tabcomp', 'tbl_tabcomp.naper = omzet_new.naper','inner');

      $this->db->where("outlet_new.id = ".'"'.$id_user.'"');
      
      $this->db->order_by('outlet_new.kode','asc');
      $hasil = $this->db->get('outlet_new');
     
      $this->excel_generator->set_query($hasil);
      $this->excel_generator->set_header(array
        (
          'Kode', 'outlet', 'alamat','kode_type', 'kodesalur','rayon', 'kota', 'b1','b2', 'b3','b4','b5','b6', 'b7','b8', 
          'b9','b10', 'b11','b12', 'rata','id'
        ));
      $this->excel_generator->set_column(array
        (
          ('kode'), 
          'outlet', 
          'alamat',
          'kode_type', 
          'kodesalur',
          'rayon', 
          'kota', 
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
          'rata',
          'id'
        ));
        $this->excel_generator->set_width(array(8, 8, 8, 8, 8, 8, 8, 8, 8, 8, 8, 8, 8, 8, 8, 8, 8, 8, 8, 8, 8));
        //$this->excel_generator->exportTo2007('Omzet'.'_'.$tahun.'_'.$supplier);   
        $this->excel_generator->exportTo2007('Outlet'.'_'.$segment3);   
    }

    public function export_outlet_bd() {
        
        //$segment2 = $this->uri->segment('2');
        $segment3 = $this->uri->segment('3');
        $id_user=$this->session->userdata('id');
  
        //$this->db->join('tbl_tabcomp', 'tbl_tabcomp.naper = omzet_new.naper','inner');
        /*
        $this->db->where("outlet_new.id = ".'"'.$id_user.'"');
        $this->db->order_by('outlet_new.kode','asc');
        $hasil = $this->db->get('outlet_new');
        */
        $sql = "
        select  a.kode,a.outlet,a.alamat,a.kode_type,a.kodesalur,a.rayon,a.kota,a.kodeprod,b.namaprod,
                a.b1,a.b2,a.b3,a.b4,a.b5,a.b6,a.b7,a.b8,a.b9,a.b10,a.b11,a.b12,a.rata,a.id
        from mpm.outlet_new a LEFT JOIN mpm.tabprod b on a.kodeprod =b.KODEPROD
        where a.id = $id_user
        ORDER BY kode
        ";

        $hasil = $this->db->query($sql);
       
        $this->excel_generator->set_query($hasil);
        $this->excel_generator->set_header(array
          (
            'Kode', 'outlet', 'alamat','kode_type', 'class','rayon', 'kota', 'kodeprod','namaprod', 'b1','b2', 'b3','b4','b5','b6', 'b7','b8', 
            'b9','b10', 'b11','b12', 'rata','id'
          ));
        $this->excel_generator->set_column(array
          (
            ('kode'), 
            'outlet', 
            'alamat',
            'kode_type', 
            'kodesalur',
            'rayon', 
            'kota', 
            'kodeprod',
            'namaprod',
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
            'rata',
            'id'
          ));
          $this->excel_generator->set_width(array(8, 8, 8, 8, 8, 8, 8, 8, 8, 8, 8, 8, 8, 8,8, 8, 8, 8, 8, 8, 8, 8, 8));
          //$this->excel_generator->exportTo2007('Omzet'.'_'.$tahun.'_'.$supplier);   
          $this->excel_generator->exportTo2007('Outlet'.'_'.$segment3);   
      }

      public function export_outlet_bd_csv() {
        
        // $segment3 = $this->uri->segment('3');
        $id_user=$this->session->userdata('id');
  
        //$this->db->join('tbl_tabcomp', 'tbl_tabcomp.naper = omzet_new.naper','inner');
        /*
        $this->db->where("outlet_new.id = ".'"'.$id_user.'"');
        $this->db->order_by('outlet_new.kode','asc');
        $hasil = $this->db->get('outlet_new');
        */
        $sql = "
        select  a.kode,a.outlet,a.alamat,a.kode_type,a.kodesalur,a.rayon,a.kota,a.kodeprod,b.namaprod,
                a.b1,a.b2,a.b3,a.b4,a.b5,a.b6,a.b7,a.b8,a.b9,a.b10,a.b11,a.b12,a.rata,a.id
        from mpm.outlet_new a LEFT JOIN mpm.tabprod b on a.kodeprod =b.KODEPROD
        where a.id = $id_user
        ORDER BY kode
        ";

        $quer = $this->db->query($sql);
        
        query_to_csv($quer,TRUE,'SalesOutlet.csv');

      }

    function view_outlet(){
      $this->load->view("outlet/table_view");
    }

    function ambil_data(){

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
        $total=$this->db->count_all_results("outlet_new");

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
        $this->db->order_by('kode','ASC');
        $this->db->where('id', $id_user);
        $query=$this->db->get('outlet_new');


        /*Ketika dalam mode pencarian, berarti kita harus
        'recordsTotal' dan 'recordsFiltered' sesuai dengan jumlah baris
        yang mengandung keyword tertentu
        */
        if($search!=""){
        $this->db->like("kode",$search);
        $jum=$this->db->get('outlet_new');
        $output['recordsTotal']=$output['recordsFiltered']=$jum->num_rows();
        }


        $nomor_urut=$start+1;
        foreach ($query->result_array() as $kode) {
          $output['data'][]=array(
            $nomor_urut,
            $kode['kode'],
            $kode['outlet'],
            $kode['alamat'],
            $kode['kode_type'],
            $kode['kodesalur'],
            $kode['rayon'],
            $kode['kota'],
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
            $kode['b12']
          );
        $nomor_urut++;
        }

        echo json_encode($output);
  }

  function ambil_data_bd(){

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
    $total=$this->db->count_all_results("outlet_new");

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
    $this->db->order_by('kode','ASC');
    $this->db->where('id', $id_user);
    $query=$this->db->get('outlet_new');


    /*Ketika dalam mode pencarian, berarti kita harus
    'recordsTotal' dan 'recordsFiltered' sesuai dengan jumlah baris
    yang mengandung keyword tertentu
    */
    if($search!=""){
    $this->db->like("kode",$search);
    $jum=$this->db->get('outlet_new');
    $output['recordsTotal']=$output['recordsFiltered']=$jum->num_rows();
    }


    $nomor_urut=$start+1;
    foreach ($query->result_array() as $kode) {
      $output['data'][]=array(
        $nomor_urut,
        $kode['kode'],
        $kode['outlet'],
        $kode['kodesalur'],
        $kode['kodeprod'],
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
        $kode['b12']
      );
    $nomor_urut++;
    }

    echo json_encode($output);
}

}
?>
