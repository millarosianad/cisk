<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Outlet_transaksi extends MY_Controller
{

    public function __construct()
    {
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
        // $id = $this->session->userdata('id');
        set_time_limit(0);
        $this->load->library(array('table','template','Excel_generator', 'form_validation','email'));
        $this->load->helper('url');
        $this->load->helper('csv');
        $this->load->model('model_omzet');
        $this->load->model('M_menu');
        $this->load->model('model_per_hari');
        $this->load->model('model_sales_omzet');
        $this->load->model('model_outlet_transaksi');
        $this->load->database();
    }

    function outlet_transaksi()
    {
        // $logged_in= $this->session->userdata('logged_in');
        // if(!isset($logged_in) || $logged_in != TRUE)
        // {
        //     redirect('login/','refresh');
        // }
        // set_time_limit(0);
        // $this->load->library(array('table','template','Excel_generator', 'form_validation','email'));
        // $this->load->helper('url');
        // $this->load->helper('csv');
        // $this->load->model('model_omzet');
        // $this->load->model('M_menu');
        // $this->load->model('model_per_hari');
        // $this->load->model('model_sales_omzet');
        // $this->load->model('model_outlet_transaksi');
        // $this->load->database();
    }
    
    public function pengambilan(){
        $data = [
            'id' => $this->session->userdata('id'),
            'url' => 'outlet_transaksi/pengambilan_hasil/',
            'title' => 'Outlet Transaksi (Pengambilan 1x, 2x, 3x)',
            'get_label' => $this->M_menu->get_label(),
            'get_supp' => $this->model_omzet->getSuppbyid(),
            // 'get_header_supp' =>$this->model_outlet_transaksi->get_header_supp(),
        ];

        $supp = $this->session->userdata('supp');
        $data['header_supp'] = $this->model_outlet_transaksi->get_header_supp($supp);

        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar',$data);
        $this->load->view('template_claim/top_content',$data);
        $this->load->view('outlet_transaksi/pengambilan',$data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
    }

    public function pengambilan_hasil(){
        $kodeprod = $this->input->post('options');
        $code = '';
        foreach($kodeprod as $kode)
        {
            $code.=",".$kode;
        }

        $data = [
            'id' => $this->session->userdata('id'),
            'url' => 'outlet_transaksi/pengambilan_hasil/',
            'title' => 'Outlet Transaksi (1x2x3x)',
            'get_label' => $this->M_menu->get_label(),
            'periode_1' => $this->input->post('periode_1'),
            'periode_2' => $this->input->post('periode_2'),
            'kodeprod'  => preg_replace('/,/', '', $code,1),
            'tipe_1'    => $this->input->post('tipe_1'),
            'tipe_2'    => $this->input->post('tipe_2'),
            'wilayah_nocab' => $this->model_per_hari->wilayah_nocab($this->session->userdata('id')),
        ];

        $tipe_1 = $this->input->post('tipe_1');
        $tipe_2 = $this->input->post('tipe_2');
        if ($tipe_1 == 1 && $tipe_2 == 1 ) {
            $data['judul'] = 'Class-Type';
            $view_content = 'pengambilan_hasil_class_type';
        }elseif ($tipe_1 == 1 && $tipe_2 == 0 ) {
            $data['judul'] = 'Class';
            $view_content = 'pengambilan_hasil_class';
        }elseif ($tipe_1 == 0 && $tipe_2 == 1 ) {
            $data['judul'] = 'Type';
            $view_content = 'pengambilan_hasil_type';
        }else{
            $view_content = 'pengambilan_hasil';
        }

        $data['proses'] = $this->model_outlet_transaksi->pengambilan($data);
        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar',$data);
        $this->load->view('template_claim/top_content',$data);
        $this->load->view("outlet_transaksi/$view_content",$data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');

    }

    public function export_pengambilan(){
        $id = $this->session->userdata('id'); 
        $kode = $this->uri->segment('3');
        if ($kode == '1') {
            $query="
                select  a.kode,a.branch_name,a.nama_comp,a.satu as '1x',a.dua as '2x',a.tiga as '3x',a.lebih_tiga as '>3x',
                substr(kode,1,3) as kode_comp
                from db_temp.t_temp_pengambilan_report a
                where a.id = $id and a.created_date = (select max(created_date) from db_temp.t_temp_pengambilan_report where id = $id)
            ";    
        }elseif ($kode == '2') {
            $query="
                select  a.kode,a.branch_name,a.nama_comp,a.class,a.satu as '1x',a.dua as '2x',a.tiga as '3x',a.lebih_tiga as '>3x',
                substr(kode,1,3) as kode_comp
                from db_temp.t_temp_pengambilan_report a
                where a.id = $id and a.created_date = (select max(created_date) from db_temp.t_temp_pengambilan_report where id = $id)
            ";    
        }elseif ($kode == '3') {
            $query="
                select  a.kode,a.branch_name,a.nama_comp,a.kode_type, a.nama_type, a.sektor,a.satu as '1x',a.dua as '2x',a.tiga as '3x',a.lebih_tiga as '>3x',
                substr(kode,1,3) as kode_comp
                from db_temp.t_temp_pengambilan_report a
                where a.id = $id and a.created_date = (select max(created_date) from db_temp.t_temp_pengambilan_report where id = $id)
            ";    
        }elseif ($kode == '4') {
            $query="
                select  a.kode,a.branch_name,a.nama_comp,class,kode_type, nama_type, sektor,a.satu as '1x',a.dua as '2x',a.tiga as '3x',a.lebih_tiga as '>3x',
                substr(kode,1,3) as kode_comp
                from db_temp.t_temp_pengambilan_report a
                where a.id = $id and a.created_date = (select max(created_date) from db_temp.t_temp_pengambilan_report where id = $id)
            ";    
        }
                                
        $hsl = $this->db->query($query);
        query_to_csv($hsl,TRUE,'pengambilan 1x2x3x.csv');
    }

    public function outlet_transaksi_ytd(){

        $data = [
          'id' => $this->session->userdata('id'),
          'url' => 'outlet_transaksi/outlet_transaksi_ytd_hasil',
          'title' => 'Outlet Transaksi (YTD)',
          'get_label' => $this->M_menu->get_label(),
          
        ];

        $supp = $this->session->userdata('supp');
        $data['header_supp'] = $this->model_outlet_transaksi->get_header_supp($supp);
        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar',$data);
        $this->load->view('template_claim/top_content',$data);
        $this->load->view('outlet_transaksi/outlet_transaksi',$data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
      
  }

    public function outlet_transaksi_ytd_hasil(){
        $kodeprod = $this->input->post('options');
        $code = '';
        foreach($kodeprod as $kode)
        {
            $code.=",".$kode;
        }

        $data = [
            'id' => $this->session->userdata('id'),
            'url' => 'outlet_transaksi/outlet_transaksi_ytd_hasil',
            'title' => 'Outlet Transaksi (YTD)',
            'get_label' => $this->M_menu->get_label(),
            'periode_1' => $this->input->post('periode_1'),
            'periode_2' => $this->input->post('periode_2'),
            'tipe_1'    => $this->input->post('tipe_1'),
            'tipe_2'    => $this->input->post('tipe_2'),
            'tipe_3'    => $this->input->post('tipe_3'),
            'kodeprod'  => preg_replace('/,/', '', $code,1),
            'wilayah_nocab' => $this->model_per_hari->wilayah_nocab($this->session->userdata('id'))
        ];

        $tipe_1 = $this->input->post('tipe_1');
        $tipe_2 = $this->input->post('tipe_2');
        $tipe_3 = $this->input->post('tipe_3');
        if ($tipe_1 == 1 && $tipe_2 == 1 && $tipe_3 == 0 ) {
            $data['judul'] = 'Class-Type';
            $view_content = 'outlet_transaksi_hasil_class_type';
        }elseif ($tipe_1 == 1 && $tipe_2 == 0 && $tipe_3 == 0  ) {
            $data['judul'] = 'Class';
            $view_content = 'outlet_transaksi_hasil_class';
        }elseif ($tipe_1 == 0 && $tipe_2 == 1 && $tipe_3 == 0  ) {
            $data['judul'] = 'Type';
            $view_content = 'outlet_transaksi_hasil_type';
        }elseif ($tipe_1 == 1 && $tipe_2 == 1 && $tipe_3 == 1  ) {
            $data['judul'] = 'Class-Type-Kodeprod';
            $view_content = 'outlet_transaksi_hasil_class_type_kodeprod';
        }elseif ($tipe_1 == 1 && $tipe_2 == 0 && $tipe_3 == 1  ) {
            $data['judul'] = 'Class-Kodeprod';
            $view_content = 'outlet_transaksi_hasil_class_kodeprod';
        }elseif ($tipe_1 == 0 && $tipe_2 == 1 && $tipe_3 == 1  ) {
            $data['judul'] = 'Type-Kodeprod';
            $view_content = 'outlet_transaksi_hasil_type_kodeprod';
        }elseif ($tipe_1 == 0 && $tipe_2 == 0 && $tipe_3 == 1  ) {
            $data['judul'] = 'Kodeprod';
            $view_content = 'outlet_transaksi_hasil_kodeprod';
        }else{
            $view_content = 'outlet_transaksi_hasil';
        }

        $supp = $this->session->userdata('supp');
        $data['header_supp'] = $this->model_outlet_transaksi->get_header_supp($supp);
        $data['proses']=$this->model_outlet_transaksi->outlet_transaksi($data);
        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar',$data);
        $this->load->view('template_claim/top_content',$data);
        $this->load->view("outlet_transaksi/$view_content",$data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');  
    }

    public function export_outlet_transaksi_ytd_kodeprod(){
        $id=$this->session->userdata('id');
        $query="
                    select kode_comp, nama_comp, kodeprod,namaprod, ytd 
                    from   db_temp.t_temp_outlet_transaksi
                    where  id = $id and created_date = (select max(created_date) from db_temp.t_temp_outlet_transaksi where id = $id)
                ";
                        
        $hasil = $this->db->query($query);
        query_to_csv($hasil,TRUE,"Outlet_Transaksi.csv");
    }

    public function export_outlet_transaksi_ytd_kodesalur(){
        $id=$this->session->userdata('id');
        $query="
                    select kode_comp, nama_comp, jenis as class, ytd 
                    from   db_temp.t_temp_outlet_transaksi
                    where  id = $id and created_date = (select max(created_date) from db_temp.t_temp_outlet_transaksi where id = $id)
                ";
                        
        $hasil = $this->db->query($query);
        query_to_csv($hasil,TRUE,"Outlet_Transaksi.csv");
    }

    public function export_outlet_transaksi_ytd_kode_type(){
        $id=$this->session->userdata('id');
        $query="
                    select kode_comp, nama_comp, kode_type as Tipe, nama_type, sektor, ytd 
                    from   db_temp.t_temp_outlet_transaksi
                    where  id = $id and created_date = (select max(created_date) from db_temp.t_temp_outlet_transaksi where id = $id)
                ";
                        
        $hasil = $this->db->query($query);
        query_to_csv($hasil,TRUE,"Outlet_Transaksi.csv");
    }

    public function export_outlet_transaksi_ytd_kodesalur_kode_type_kodeprod(){
        $id=$this->session->userdata('id');
        $query="
                    select kode_comp, nama_comp,  kodeprod,namaprod, jenis as class, kode_type as Tipe, nama_type, sektor, ytd 
                    from   db_temp.t_temp_outlet_transaksi
                    where  id = $id and created_date = (select max(created_date) from db_temp.t_temp_outlet_transaksi where id = $id)
                ";
                        
        $hasil = $this->db->query($query);
        query_to_csv($hasil,TRUE,"Outlet_Transaksi.csv");
    }

    public function export_outlet_transaksi_ytd_kodesalur_kodeprod(){
        $id=$this->session->userdata('id');
        $query="
                    select kode_comp, nama_comp,  kodeprod,namaprod, jenis as class, ytd 
                    from   db_temp.t_temp_outlet_transaksi
                    where  id = $id and created_date = (select max(created_date) from db_temp.t_temp_outlet_transaksi where id = $id)
                ";
                        
        $hasil = $this->db->query($query);
        query_to_csv($hasil,TRUE,"Outlet_Transaksi.csv");
    }

    public function export_outlet_transaksi_ytd_kode_type_kodeprod(){
        $id=$this->session->userdata('id');
        $query="
                    select kode_comp, nama_comp,  kodeprod,namaprod, kode_type as Tipe, nama_type, sektor, ytd 
                    from   db_temp.t_temp_outlet_transaksi
                    where  id = $id and created_date = (select max(created_date) from db_temp.t_temp_outlet_transaksi where id = $id)
                ";
                        
        $hasil = $this->db->query($query);
        query_to_csv($hasil,TRUE,"Outlet_Transaksi.csv");
    }

    public function export_outlet_transaksi_ytd(){
        $id=$this->session->userdata('id');
        $kondisi = $this->uri->segment('3');
        $bd = $this->uri->segment('4');

        if ($kondisi == '1'){
            $query="
                        select kode_comp, nama_comp, jenis as Class, kode_type, nama_type, sektor, ytd 
                        from   db_temp.t_temp_outlet_transaksi
                        where  id = $id and created_date = (select max(created_date) from db_temp.t_temp_outlet_transaksi where id = $id)
                    ";
                            
            $hasil = $this->db->query($query);

        }elseif ($kondisi == '2'){
            $query="
                    select kode_comp, nama_comp, jenis as Class, ytd 
                    from   db_temp.t_temp_outlet_transaksi
                    where  id = $id and created_date = (select max(created_date) from db_temp.t_temp_outlet_transaksi where id = $id)
                    ";
                            
            $hasil = $this->db->query($query);

        }elseif ($kondisi == '3'){
            $query="
                    select kode_comp, nama_comp, sektor as Tipe, ytd 
                    from   db_temp.t_temp_outlet_transaksi
                    where  id = $id and created_date = (select max(created_date) from db_temp.t_temp_outlet_transaksi where id = $id)
                    ";
                            
            $hasil = $this->db->query($query);

        }else{
        $query="
                    select kode_comp, nama_comp, ytd 
                    from   db_temp.t_temp_outlet_transaksi
                    where  id = $id and created_date = (select max(created_date) from db_temp.t_temp_outlet_transaksi where id = $id)
                ";

        $hasil = $this->db->query($query);
        }
            query_to_csv($hasil,TRUE,"Outlet_Transaksi.csv");
    }

    public function otsc(){

        $data = [
          'id' => $this->session->userdata('id'),
          'url' => 'outlet_transaksi/otsc_hasil',
          'title' => 'Outlet Transaksi - with exception',
          'get_label' => $this->M_menu->get_label()
        ];

        $supp = $this->session->userdata('supp');
        $data['header_supp'] = $this->model_outlet_transaksi->get_header_supp($supp);
        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar',$data);
        $this->load->view('template_claim/top_content',$data);
        $this->load->view('outlet_transaksi/otsc',$data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
      
    }

    public function otsc_hasil(){

        // $from_outlet = $this->input->post('from_outlet');
        // $to_outlet = $this->input->post('to_outlet');
        // $from_otsc = $this->input->post('from_otsc');
        // $to_otsc = $this->input->post('to_otsc');
        $kodeprod = $this->input->post('options');
        $output= $this->input->post('output');

        $code = '';
        foreach($kodeprod as $kode)
        {
            $code.=",".$kode;
        }

        $data = [
            'url' => 'outlet_transaksi/otsc_hasil/',
            'title' => 'Outlet Transaksi - with exception',
            'get_label' => $this->M_menu->get_label(),
            'from_outlet' => $this->input->post('from_outlet'),
            'to_outlet' => $this->input->post('to_outlet'),
            'from_otsc' => $this->input->post('from_otsc'),
            'to_otsc' => $this->input->post('to_otsc'),
            'kodeprod'  => preg_replace('/,/', '', $code,1),
            'wilayah_nocab' => $this->model_per_hari->wilayah_nocab($this->session->userdata('id')),
            'userid' => $this->session->userdata('id'),
            'output' => $output,
            'created_date' => $this->model_outlet_transaksi->timezone(),
        ];
        // $output == 0;
        // echo "output : ".$output;
        if($output == 1){
            $template = "outlet_transaksi/otsc_hasil";
        }elseif($output == 2){
            $template = "outlet_transaksi/otsc_hasil_harian";
        }elseif($output == 3){
            $template = "outlet_transaksi/otsc_hasil_type";
        }

        $data['proses'] = $this->model_outlet_transaksi->otsc($data);
        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar',$data);
        $this->load->view('template_claim/top_content',$data);
        $this->load->view($template,$data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
    }

    public function otsc_export(){
        $id = $this->session->userdata('id'); 
        $query="
                select  a.kode, a.branch_name, a.nama_comp, a.ot
                from db_temp.t_temp_outlet_siap_export a
                where a.created_by = $id
            ";  
                                
        $hsl = $this->db->query($query);
        query_to_csv($hsl,TRUE,'outlet_transaksi_with_exception.csv');
    }

    public function otsc_export_type(){
        $id = $this->session->userdata('id'); 
        $query="
                select  a.kode, a.branch_name, a.nama_comp, a.ot, a.kode_type, a.nama_type, a.sektor, a.segment
                from db_temp.t_temp_outlet_siap_export a
                where a.created_by = $id
            ";  
                                
        $hsl = $this->db->query($query);
        query_to_csv($hsl,TRUE,'outlet_transaksi_with_exception.csv');
    }

    public function otsc_export_harian(){
        $id = $this->session->userdata('id'); 
        $query="
                select  a.kode, a.branch_name, a.nama_comp, a.tahun, a.bulan, a.hrdok as tgl, a.ot
                from db_temp.t_temp_outlet_siap_export a
                where a.created_by = $id
            ";  
                                
        $hsl = $this->db->query($query);
        query_to_csv($hsl,TRUE,'outlet_transaksi_with_exception_breakdown_hari.csv');
    }

    public function otsc_export_exception(){
        $id = $this->session->userdata('id'); 
        $query="
                select  *
                from db_temp.t_temp_outlet_tidak_dihitung_core a
                where a.created_by = $id
            ";  
                                
        $hsl = $this->db->query($query);
        query_to_csv($hsl,TRUE,'outlet_transaksi_with_exception.csv');
    }

    public function otsc_master_export(){
        $id = $this->session->userdata('id'); 
        $query="
                select  *
                from db_temp.t_temp_master_otsc a
                where a.userid = $id and a.created_date = (select max(created_date) from db_temp.t_temp_master_otsc where userid = $id)
            ";  
                                
        $hsl = $this->db->query($query);
        query_to_csv($hsl,TRUE,'master outlet.csv');
    }

    public function otsc_detail_export(){
        $id = $this->session->userdata('id'); 
        $query="
                select  *
                from db_temp.t_temp_otsc_detail a
                where a.userid = $id and a.created_date = (select max(created_date) from db_temp.t_temp_otsc where userid = $id)
            ";  
                                
        $hsl = $this->db->query($query);
        query_to_csv($hsl,TRUE,'otsc_detail.csv');
    }

    public function otsc_outlet_export(){
        $id = $this->session->userdata('id'); 
        $query="
                select  *
                from db_temp.t_temp_otsc_outlet a
                where a.userid = $id and a.created_date = (select max(created_date) from db_temp.t_temp_otsc where userid = $id)
            ";  
                                
        $hsl = $this->db->query($query);
        query_to_csv($hsl,TRUE,'otsc_outlet.csv');
    }

    public function otsc_master_otsc_export(){
        $id = $this->session->userdata('id'); 
        $query="
                select  *
                from db_temp.t_temp_master_otsc_detail a
                where a.userid = $id and a.created_date = (select max(created_date) from db_temp.t_temp_master_otsc where userid = $id)
            ";  
                                
        $hsl = $this->db->query($query);
        query_to_csv($hsl,TRUE,'master outlet otsc.csv');
    }


}
?>
