<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Surat_jalan extends MY_Controller
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

    function surat_jalan()
    {
        set_time_limit(0);
        $this->load->library(array('table','template','Excel_generator', 'form_validation'));
        $this->load->helper('url');
        $this->load->model('M_suratjalan');
        $this->load->model('M_menu');
        $this->load->database();
    }

    function index()
    {

        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
        $this->view_surat_jalan();
    }

    public function view_surat_jalan(){

        $this->load->library('form_validation');
  
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
        
        $data['surat_jalan']=$this->M_suratjalan->view_surat_jalan();
        $data['title']  = "Surat Jalan";
        $data['grup_lang'] = "";
        $data['get_label'] = $this->M_menu->get_label();
        $data['query']=$this->M_suratjalan->list_pelanggan($data);

        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar',$data);
        $this->load->view('template_claim/top_content',$data);
        $this->load->view('surat_jalan_new/view_surat_jalan',$data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
        
      }

    public function viewfaktur()
    {
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
        $client=$this->session->userdata('client');       
        // echo "<br><br><br>client : ".$client."<br>"; 
        $grup_lang = $this->input->post('grup_lang');  
        if ($grup_lang == '') {
            $grup_lang = $this->uri->segment(3);
        }      
        // echo "grup_lang : ".$grup_lang."<br>";
        
        $id=$this->input->post('keterangan');
        if ($id == '') {
            $id = $this->uri->segment(4);
        }
        // echo "id : ".$id."<br>";

        $from  = $this->input->post('from');
        $to    = $this->input->post('to'  );      

        $data['url'] = 'surat_jalan/tambah_surat_jalan_temp/';
        $data['title'] = 'Input Surat Jalan';
        $data['grup_lang'] = $grup_lang;
        $data['jenis_faktur'] = $id;
        $data['from'] = $from;
        $data['to'] = $to;
        $data['get_label'] = $this->M_menu->get_label();
        $data['query']=$this->M_suratjalan->list_pelanggan($data); 
        $data['lang']=$this->M_suratjalan->getPelanggan($client);        
        $data['kode']=$this->M_suratjalan->list_faktur($data);

        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar',$data);
        $this->load->view('template_claim/top_content',$data);
        $this->load->view('surat_jalan_new/view_input_surat_jalan_faktur',$data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
    }

    public function tambah_surat_jalan_temp()
    {
        $keterangan = $this->input->post('keterangan');
        $code=$this->input->post('kodeprod');
        $options=$this->input->post('options');
        $jenis_faktur=$this->uri->segment(4);
        $grup_lang= $this->uri->segment(3);
        $id=$this->input->post('keterangan');
        $data['jenis_faktur'] = $jenis_faktur;
        $data['keterangan'] = $keterangan;
        $from = $this->input->post('from');
        $to = $this->input->post('to');
        $data['grup_lang'] = "";
        $data['query']=$this->M_suratjalan->list_pelanggan($data);
        
        // echo "<pre>";
        // echo "controller : grup_lang : ".$grup_lang."<br>";
        // echo "controller : code : ".$code."<br>";
        // echo "controller : keterangan : ".$keterangan."<br>";
        // echo "controller : jenis faktur : ".$jenis_faktur;
        // echo "controller : from : ".$from;
        // echo "controller : to : ".$to;
        // echo "</pre>"; 

        // var_dump($options);
        // die;

        if ($options != '') {
            // echo "A";
            // die;
            foreach($options as $kode)
            {
                $code.=",".$kode;
            }
            $code=preg_replace('/,/', '', $code,1);
            $data['code'] = $code;
            
            $data['title'] = 'Input Surat Jalan';
            $data['get_label'] = $this->M_menu->get_label();
            $data['url'] = 'surat_jalan/save_surat_jalan/'.$grup_lang;
            $data['grup_lang'] = $grup_lang;
            $data['from'] = $from;
            $data['to'] = $to;
            $data['variabel']=$this->M_suratjalan->get_faktur($data);
            die;
            $data['tampil_temp']=$this->M_suratjalan->tampil_temp($data);

            // var_dump($data['variabel']);
            // var_dump($data['variabel']);
            // die;

            $this->load->view('template_claim/top_header');
            $this->load->view('template_claim/header');
            $this->load->view('template_claim/sidebar',$data);
            $this->load->view('template_claim/top_content',$data);
            $this->load->view('surat_jalan_new/view_input_faktur_temp',$data);
            $this->load->view('template_claim/bottom_content');
            $this->load->view('template_claim/footer');

        }else{
            // echo "b";
            // die;
            $data['title'] = 'Input Surat Jalan';
            $data['get_label'] = $this->M_menu->get_label();
            $data['url'] = 'surat_jalan/save_surat_jalan/'.$grup_lang;
            $data['grup_lang'] = $grup_lang;
            $data['tampil_temp']=$this->M_suratjalan->tampil_temp($data);
            
            $this->load->view('template_claim/top_header');
            $this->load->view('template_claim/header');
            $this->load->view('template_claim/sidebar',$data);
            $this->load->view('template_claim/top_content',$data);
            $this->load->view('surat_jalan_new/view_input_faktur_temp',$data);
            $this->load->view('template_claim/bottom_content');
            $this->load->view('template_claim/footer');
        }

    }

    public function delete_temp($grup_lang,$jenis_faktur,$id){        
        $hasil = $this->M_suratjalan->proses_delete($id);        
        if ($hasil) {
            redirect("surat_jalan/tambah_surat_jalan_temp/$grup_lang/$jenis_faktur");
        }
       
    }

    public function delete($id){
      $hasil = $this->M_suratjalan->proses_delete_surat_jalan($id);
      redirect('surat_jalan/view_surat_jalan','refresh');
    }

    public function save_surat_jalan(){
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
        $userid=$this->session->userdata('id');
        $post['tanggal']=$this->input->post('tanggal');
        $post['created_by']=$userid;
        $post['created']=date('Y-m-d H:i:s');

        $data = array(
            'userid'     => $userid,
            'created'    => date('Y-m-d H:i:s'),
            'tanggal'    => date('Y-m-d',strtotime($this->input->post('tanggal'))),
        );
              
        // echo "userid : ".$userid."<br>";
        // echo "created : ".date('Y-m-d H:i:s')."<br>";
        // echo "tanggal : ".$this->input->post('tanggal')."<br>";

        //$tgl = date('Y-m-d',strtotime( $this->input->post('tanggal')));
        //echo "tgl : ".$tgl;

        $data['proses']=$this->M_suratjalan->proses_save_surat_jalan($data);
    }

    public function edit(){

        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }

        $client=$this->session->userdata('client');
        echo "var client : ".$client."<br>";
        $grup_lang = $this->input->post('grup_lang');
        echo "grup lang controller : ".$grup_lang;
        $id=$this->input->post('keterangan');
        echo "<br>jenis faktur controller : ".$id;
     
        $data['url1'] = 'surat_jalan/view_input_surat_jalan_show';
        $data['url2'] = 'surat_jalan/tambah_surat_jalan_temp/'.$grup_lang.'/'.$id;
        $data['get_label'] = $this->M_menu->get_label();
        $data['title'] = 'Input Surat Jalan';
        $data['grup_lang'] = $grup_lang;
        $data['jenis_faktur'] = $id;
        $data['url3'] = 'surat_jalan/save_surat_jalan/'.$grup_lang;
        $data['tampil_temp']=$this->M_suratjalan->tampil_temp($grup_lang);

        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar',$data);
        $this->load->view('template_claim/top_content',$data);
        $this->load->view('surat_jalan_new/view_input_surat_jalan_faktur',$data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
    }

    
    public function print_range()
    {
        $server='localhost:3307';
        $user='root';
        $pass='mpm123#@!';
        $db='mpm';
        $this->load->library('PHPJasperXML');
        //$xml = simplexml_load_file("assets/report/trans/permit_range.jrxml");
        //$tgl=$this->input->post('range');
        $start=$this->input->post('start');
        $end=$this->input->post('end');
        $id=$this->input->post('keterangan');

        //$format = date('Y-m-d', strtotime($tgl)); 
        $format_start = date('Y-m-d', strtotime($start)); 
        $key_start = str_replace('-','',$format_start);

        $format_end = date('Y-m-d', strtotime($end)); 
        $key_end = str_replace('-','',$format_end);

        $grup_lang = $this->input->post('grup_lang');
        echo "grup lang controller : ".$grup_lang;


        echo "<pre>";
        echo "tgl start : ".$start."<br>";
        echo "tgl end : ".$end."<br>";
        echo "id : ".$id."<br>";
        echo "format tgl start : ".$format_start."<br>";
        echo "key tgl start : ".$key_start."<br>";
        echo "tgl end : ".$end."<br>";
        echo "format tgl end : ".$format_end."<br>";
        echo "key tgl end : ".$key_end."<br>";
        // die;
        echo "</pre>";
        
        if($id==1)
        {
                $xml = simplexml_load_file("assets/report/trans/permit_range_lunas.jrxml");
                @$this->phpjasperxml->debugsql=false;
                @$this->phpjasperxml->arrayParameter=array('id_start'=>$key_start, 'id_end'=>$key_end);
                @$this->phpjasperxml->xml_dismantle($xml);

                @$this->phpjasperxml->transferDBtoArray($server,$user,$pass,$db);
                @$this->phpjasperxml->outpage("D",$key_start.'-'.$key_end.'.pdf');
        }
        elseif ($id==2) {

            if ($grup_lang == '0') {
                echo "belum memilih DP";
            }else{
                $xml = simplexml_load_file("assets/report/trans/permit_range_lunas_dp.jrxml");
                
                //cek nama pelanggan
                $sql = "
                select distinct * 
                from
                (
                    select  distinct concat('1',grup_lang) grup_lang,
                            grup_nama 
                    from    pusat.user 
                    where   grup_lang<>'' and 
                            grup_lang<>'00159'   
                    union all   
                    select  distinct group_id grup_lang,
                            group_descr grup_nama 
                    from    db_temp.t_m_customer 
                    where   group_id<>'' and 
                            group_id<>'100159'
                )a where grup_lang = $grup_lang
                ";
                $query=$this->db->query($sql);
                $row=$query->row();
                $nama_pelanggan=$row->grup_nama;
                

                @$this->phpjasperxml->debugsql=false;
                @$this->phpjasperxml->arrayParameter=array('id_start'=>$key_start, 'id_end'=>$key_end, 'id_lang'=>$grup_lang);
                @$this->phpjasperxml->xml_dismantle($xml);

                @$this->phpjasperxml->transferDBtoArray($server,$user,$pass,$db);
                @$this->phpjasperxml->outpage("D",$key_start.'-'.$key_end.'-'.$nama_pelanggan.'.pdf');
            }
            //echo "a";
        }
        elseif($id==0)
        {
            // echo "aaa";
            // die;
                $xml = simplexml_load_file("assets/report/trans/permit_range_copy.jrxml");
                //echo "a";

                @$this->phpjasperxml->debugsql=false;
                @$this->phpjasperxml->arrayParameter=array('id_start'=>$key_start, 'id_end'=>$key_end);
                @$this->phpjasperxml->xml_dismantle($xml);

                @$this->phpjasperxml->transferDBtoArray($server,$user,$pass,$db);
                @$this->phpjasperxml->outpage("D",$key_start.'-'.$key_end.'.pdf');
                
        }
    }

    public function print_surat(){

        echo "uri ke 3 : ".$this->uri->segment(3)."<br>";
        echo "uri ke 4 : ".$this->uri->segment(4);
        $id = $this->uri->segment(4);
        $key = $this->uri->segment(3);
  
        $userid=$this->session->userdata('id');
        echo "<br> userid : ".$userid;
  
        $server='localhost';
        $user='root';
        $pass='';
        $db='mpm';
        $this->load->library('PHPJasperXML');
        
        $xml='';
  
        echo "<br> id : ".$id;
       
        if($id==1)
        {
              $xml = simplexml_load_file("assets/report/trans/permit_lunas.jrxml");
        }
        
        else
        {
              $xml = simplexml_load_file("assets/report/trans/permit_copy.jrxml");
        }
         
         
        @$this->phpjasperxml->debugsql=false;
        @$this->phpjasperxml->arrayParameter=array('id'=>$key);
        @$this->phpjasperxml->xml_dismantle($xml);
  
        @$this->phpjasperxml->transferDBtoArray($server,$user,$pass,$db);
        @$this->phpjasperxml->outpage("D",$key.'.pdf');
  
      }

    public function amplop(){

        echo "uri ke 3 : ".$this->uri->segment(3)."<br>";
        $key = $this->uri->segment(3);
  
        $server='localhost:3307';
        $user='root';
        $pass='mpm123#@!';
        $db='mpm';
        $this->load->library('PHPJasperXML');
        $xml = simplexml_load_file("assets/report/trans/amplop.jrxml");
        @$this->phpjasperxml->debugsql=false;
        @$this->phpjasperxml->arrayParameter=array('id'=>substr($key,1));
        @$this->phpjasperxml->xml_dismantle($xml);
  
        @$this->phpjasperxml->transferDBtoArray($server,$user,$pass,$db);
        @$this->phpjasperxml->outpage("D",$key.'.pdf');
  
      }
  
      public function amplop_coklat(){
  
        echo "uri ke 3 : ".$this->uri->segment(3)."<br>";
        $key = $this->uri->segment(3);
  
        $server='localhost:3307';
        $user='root';
        $pass='mpm123#@!';
        $db='mpm';
  
        $this->load->library('PHPJasperXML');
        $xml = simplexml_load_file("assets/report/trans/amplop_coklat.jrxml");
        @$this->phpjasperxml->debugsql=false;
        @$this->phpjasperxml->arrayParameter=array('id'=>substr($key,1));
        @$this->phpjasperxml->xml_dismantle($xml);
  
        @$this->phpjasperxml->transferDBtoArray($server,$user,$pass,$db);
        @$this->phpjasperxml->outpage("D",$key.'.pdf');
  
      }

}