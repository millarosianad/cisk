<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class All_surat_jalan extends MY_Controller
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

    function all_surat_jalan()
    {
        set_time_limit(0);
        $this->load->library(array('table','template','Excel_generator', 'form_validation'));
        $this->load->helper('url');
        $this->load->model('model_surat_jalan');
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

        $this->view_surat_jalan();
    }

    private function template($view,$data)
    {
        $this->template->set_title('MPM SQUARE');
        $this->template->add_js('modules/skeleton.js');
        $this->template->add_css('modules/skeleton.css');
        $this->template->load_view($view, $data);
    }

    public function view_surat_jalan(){

      $this->load->library('form_validation');

      $logged_in= $this->session->userdata('logged_in');
      if(!isset($logged_in) || $logged_in != TRUE)
      {
          redirect('login/','refresh');
      }

        $data['page_content'] = 'surat_jalan/view_surat_jalan';                      
        $data['menu']=$this->db->query($this->querymenu);
        $data['surat_jalan']=$this->model_surat_jalan->view_surat_jalan();
        
        $data['grup_lang'] = "";
        $grup_lang = $this->input->post('grup_lang');
        $data['query']=$this->model_surat_jalan->list_pelanggan($data);
        $this->template($data['page_content'],$data);        
    }

    public function view_surat_jalan_by_tgl()
    {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('tgl', '"tanggal"', 'required');
  
        if($this->form_validation->run() === FALSE)
        {
          //echo "ada kesalahan";
          $data['page_content'] = 'surat_jalan/view_surat_jalan';             
          $data['menu']=$this->db->query($this->querymenu);
          $data['surat_jalan']=$this->model_surat_jalan->view_surat_jalan();
          $this->template($data['page_content'],$data); 
        }else{
            
            $data = array(
                'tgl'       => set_value('tgl')
            );
            
            // echo "tgl : ".$data['tgl'];
            $data['grup_lang'] = "";
            $data['query']=$this->model_surat_jalan->list_pelanggan($data);
            $data['page_content'] = 'surat_jalan/view_surat_jalan';                      
            $data['menu']=$this->db->query($this->querymenu);
            $data['surat_jalan']=$this->model_surat_jalan->view_surat_jalan_by_tgl($data);
            $this->template($data['page_content'],$data);

        }
    }

    public function print_surat(){

      echo "uri ke 3 : ".$this->uri->segment(3)."<br>";
      echo "uri ke 4 : ".$this->uri->segment(4);
      $id = $this->uri->segment(4);
      $key = $this->uri->segment(3);

      $userid=$this->session->userdata('id');
      echo "<br> userid : ".$userid;

      $server='localhost:3307';
      $user='root';
      $pass='mpm123!@#';
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

    public function print_range()
    {
      $server='localhost:3307';
      $user='root';
      $pass='mpm123!@#';
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
                from    dbsls.m_customer 
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
            $xml = simplexml_load_file("assets/report/trans/permit_range_copy.jrxml");
            //echo "a";

            @$this->phpjasperxml->debugsql=false;
            @$this->phpjasperxml->arrayParameter=array('id_start'=>$key_start, 'id_end'=>$key_end);
            @$this->phpjasperxml->xml_dismantle($xml);

            @$this->phpjasperxml->transferDBtoArray($server,$user,$pass,$db);
            @$this->phpjasperxml->outpage("D",$key_start.'-'.$key_end.'.pdf');
            
       }

       
      
      
      
    }

    

    public function amplop(){

      echo "uri ke 3 : ".$this->uri->segment(3)."<br>";
      $key = $this->uri->segment(3);

      $server='localhost:3307';
      $user='root';
      $pass='mpm123!@#';
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
      $pass='mpm123!@#';
      $db='pusat';

      $this->load->library('PHPJasperXML');
      $xml = simplexml_load_file("assets/report/trans/amplop_coklat.jrxml");
      @$this->phpjasperxml->debugsql=false;
      @$this->phpjasperxml->arrayParameter=array('id'=>substr($key,1));
      @$this->phpjasperxml->xml_dismantle($xml);

      @$this->phpjasperxml->transferDBtoArray($server,$user,$pass,$db);
      @$this->phpjasperxml->outpage("D",$key.'.pdf');

    }

    public function download()
    {
      $key = $this->uri->segment(3);
        //delete_files('./assets/faktur');
        
      $sql="
        select  if(tanggal>='2013-08-01',1,0) flag,
                kode_lang,faktur, pajak 
        from    pusat.permit a inner join pusat.permit_detail b 
                  on a.id=b.id_ref 
        where id_ref=".$key." and keterangan='Copy Faktur'
        ";

        echo "<pre>";
        print_r($sql);
        echo "</pre>";

        $query=$this->db->query($sql,array($key));
        if($query->num_rows() > 0)
        {
            $row=$query->row();

            $server='localhost:3307';
            $user='root';
            $pass='mpm123!@#';
            $db='mpm';
            $this->load->library('PHPJasperXML');
            $xml_fk = simplexml_load_file("assets/report/trans/faktur_komersil_baru.jrxml");
            $xml_fp = simplexml_load_file("assets/report/trans/faktur_pajak_baru.jrxml");
            $xml_fkl = simplexml_load_file("assets/report/trans/faktur_komersil.jrxml");
            $xml_fpl = simplexml_load_file("assets/report/trans/faktur_pajak.jrxml");
    
            foreach ($query->result() as $value)
            {
                 if($value->flag)
                 {    
                    //echo "sufy";
                    
                    $PHPJasperXML = new PHPJasperXML();
                    $PHPJasperXML->debugsql=false;
                    $PHPJasperXML->arrayParameter=array('id'=>substr($value->faktur,0,strlen($value->faktur)-9),'bulan'=>substr($value->faktur,-2,2).substr($value->faktur,-4,2));
                    $PHPJasperXML->xml_dismantle($xml_fk);
                    $PHPJasperXML->transferDBtoArray($server,$user,$pass,$db);
                    $PHPJasperXML->outpage("F",'assets/faktur/'.str_replace('/','_', $value->faktur).'.pdf');
                   //$this->email->attach('assets/faktur/'.str_replace('/','_', $value->faktur).'.pdf');
                    $this->zip->read_file('assets/faktur/'.str_replace('/','_', $value->faktur).'.pdf');

                    $PHPJasperXML = new PHPJasperXML();
                    $PHPJasperXML->debugsql=false;
                    $PHPJasperXML->arrayParameter=array('id'=>substr($value->pajak,0,strlen($value->pajak)-5),'bulan'=>substr($value->faktur,-2,2).substr($value->faktur,-4,2));
                    $PHPJasperXML->xml_dismantle($xml_fp);
                    $PHPJasperXML->transferDBtoArray($server,$user,$pass,$db);
                    $PHPJasperXML->outpage("F",'assets/faktur/'.str_replace('/','_', $value->pajak).'.pdf');
                    $this->zip->read_file('assets/faktur/'.str_replace('/','_', $value->pajak).'.pdf');
                 }
                 else {
                    $PHPJasperXML = new PHPJasperXML();
                    $PHPJasperXML->debugsql=false;
                    $PHPJasperXML->arrayParameter=array('id'=>substr($value->faktur,0,strlen($value->faktur)-9),'bulan'=>substr($value->faktur,-2,2).substr($value->faktur,-4,2));
                    $PHPJasperXML->xml_dismantle($xml_fkl);
                    $PHPJasperXML->transferDBtoArray($server,$user,$pass,$db);
                    $PHPJasperXML->outpage("F",'assets/faktur/'.str_replace('/','_', $value->faktur).'.pdf');
                   //$this->email->attach('assets/faktur/'.str_replace('/','_', $value->faktur).'.pdf');
                    $this->zip->read_file('assets/faktur/'.str_replace('/','_', $value->faktur).'.pdf');

                    $PHPJasperXML = new PHPJasperXML();
                    $PHPJasperXML->debugsql=false;
                    $PHPJasperXML->arrayParameter=array('id'=>substr($value->pajak,0,strlen($value->pajak)-5),'bulan'=>substr($value->faktur,-2,2).substr($value->faktur,-4,2));
                    $PHPJasperXML->xml_dismantle($xml_fpl);
                    $PHPJasperXML->transferDBtoArray($server,$user,$pass,$db);
                    $PHPJasperXML->outpage("F",'assets/faktur/'.str_replace('/','_', $value->pajak).'.pdf');
                    $this->zip->read_file('assets/faktur/'.str_replace('/','_', $value->pajak).'.pdf');
                 }
            }
            $filename=md5(date("d-m-Y H:i:s")).'.zip';
            $this->zip->download($filename);
        }
        else
        {
            return false;
        }

    }

    private function email_config()
    {
         $config['protocol']  = 'smtp';
         $config['smtp_host'] = 'ssl://smtp.gmail.com';
         $config['smtp_port'] = '465';
         $config['smtp_timeout'] = '3000';
         $config['smtp_user'] = 'suffy.yanuar@gmail.com';
         $config['smtp_pass'] = 'yanuar123!@#';
         $config['charset']  = 'utf-8';
         $config['newline']  = "\r\n";
         $config['use_ci_email'] = TRUE;

         $this->email->initialize($config);
    }

    public function email(){

      //delete_files('./assets/faktur');
      
      $sql="
          select  kode_lang,
                  faktur, 
                  pajak 
          from    pusat.permit a inner join pusat.permit_detail b 
                    on a.id=b.id_ref 
          where id_ref=? and keterangan='Copy Faktur'";
      
      echo "<pre>";
      print_r($sql);
      echo "</pre>";

      echo "uri ke 3 : ".$this->uri->segment(3)."<br>";
      echo "uri ke 4 : ".$this->uri->segment(4);
      $id = $this->uri->segment(4);
      $key = $this->uri->segment(3);

      $query=$this->db->query($sql,array($key));
      if($query->num_rows() > 0)
      {
          $row=$query->row();

          $server='localhost:3307';
          $user='root';
          $pass='mpm123!@#';
          $db='mpm';
          $this->load->library('PHPJasperXML');
          $xml_fk = simplexml_load_file("assets/report/trans/faktur_komersil_baru.jrxml");
          $xml_fp = simplexml_load_file("assets/report/trans/faktur_pajak_baru.jrxml");

          $this->email_config();
          //$this->email->from('muliaputramandiri@gmail.com', "PT. Mulia Putra Mandiri");
          $this->email->from('suffy.yanuar@gmail.com', "PT. Mulia Putra Mandiri");
          
          
          //$this->email->to($this->getEmailFinanceFaktur(substr($row->kode_lang,1)));
          //$list = array("yunitasasmita@gmail.com");
          
          //$this->email->cc($list);

          $this->email->to('suffy.project@gmail.com', "PT. Mulia Putra Mandiri");

          $this->email->subject("Faktur Pajak dan Faktur Penjualan");
          $this->email->message("This Email is sent by system,"."\r\n"."\r\n"."Process By Yunita"."\r\n"."\r\n"."Note : Mohon agar Faktur Pajak dapat di abaikan karena per tgl 1 Juli sudah menggunakan E-Faktur");

          foreach ($query->result() as $value)
          {
               $PHPJasperXML = new PHPJasperXML();
               $PHPJasperXML->debugsql=false;
               $PHPJasperXML->arrayParameter=array('id'=>substr($value->faktur,0,strlen($value->faktur)-9),'bulan'=>substr($value->faktur,-2,2).substr($value->faktur,-4,2));
               $PHPJasperXML->xml_dismantle($xml_fk);
               $PHPJasperXML->transferDBtoArray($server,$user,$pass,$db);
               $PHPJasperXML->outpage("F",'assets/faktur/'.str_replace('/','_', $value->faktur).'.pdf');
               
               
               //$this->email->attach('assets/faktur/'.str_replace('/','_', $value->faktur).'.pdf');
               $this->zip->read_file('assets/faktur/'.str_replace('/','_', $value->faktur).'.pdf');
               $PHPJasperXML = new PHPJasperXML();
               $PHPJasperXML->debugsql=false;
               $PHPJasperXML->arrayParameter=array('id'=>substr($value->pajak,0,strlen($value->pajak)-5),'bulan'=>substr($value->faktur,-2,2).substr($value->faktur,-4,2));
               $PHPJasperXML->xml_dismantle($xml_fp);
               $PHPJasperXML->transferDBtoArray($server,$user,$pass,$db);
               $PHPJasperXML->outpage("F",'assets/faktur/'.str_replace('/','_', $value->pajak).'.pdf');
               $this->zip->read_file('assets/faktur/'.str_replace('/','_', $value->pajak).'.pdf');
               
          }
          $filename=md5(date("d-m-Y H:i:s")).'.zip';
          $this->zip->archive('assets/faktur/'.$filename);
          $this->email->attach('assets/faktur/'.$filename);
          $this->email->send();
      }
      else
      {
          return false;
      }

    }

    public function formTampilFaktur()
    {
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
        $data['page_content'] = 'surat_jalan/view_input_surat_jalan';            
        $data['page_title'] = 'Input Surat Jalan';
        $data['grup_lang'] = "";
        $grup_lang = $this->input->post('grup_lang');
        // echo "<br><br><br>grup lang controller : ".$grup_lang;
        $data['query']=$this->model_surat_jalan->list_pelanggan($data);
        $data['menu']=$this->db->query($this->querymenu);
        $data['query2'] = $this->model_surat_jalan->status($data);
        $data['url'] = 'all_surat_jalan/viewFaktur/';
        $this->template($data['page_content'],$data);
    }

    public function tidak_ada_data()
    {
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
            $data['page_content'] = 'surat_jalan/view_input_surat_jalan_err';
            $data['url'] = 'all_surat_jalan/viewFaktur/';
           
            $data['page_title'] = 'Input Surat Jalan';
            $data['grup_lang'] = "";
            $data['query']=$this->model_surat_jalan->list_pelanggan($data);
            $data['menu']=$this->db->query($this->querymenu);
            $this->template($data['page_content'],$data);
    }

    public function viewFaktur()
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

        $data['page_content'] = 'surat_jalan/view_input_surat_jalan_faktur_temp';        
        $data['url1'] = 'all_surat_jalan/viewFaktur';   
        $data['url2'] = 'all_surat_jalan/tambah_surat_jalan_temp/';
        $data['page_title'] = 'Input Surat Jalan';
        $data['grup_lang'] = $grup_lang;
        $data['jenis_faktur'] = $id;
        $data['from'] = $from;
        $data['to'] = $to;
        $data['query']=$this->model_surat_jalan->list_pelanggan($data);        
        $data['menu']=$this->db->query($this->querymenu); 
        $data['lang']=$this->model_surat_jalan->getPelanggan($client);        
        $data['kode']=$this->model_surat_jalan->list_faktur($data);        
        $data['url3'] = 'all_surat_jalan/save_surat_jalan/'.$grup_lang;
        $data['tampil_temp']=$this->model_surat_jalan->tampil_temp($grup_lang);
        $this->template($data['page_content'],$data);       

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
        
        echo "<pre>";
        echo "controller : grup_lang : ".$grup_lang."<br>";
        echo "controller : code : ".$code."<br>";
        echo "controller : keterangan : ".$keterangan."<br>";
        echo "controller : jenis faktur : ".$jenis_faktur;
        echo "controller : from : ".$from;
        echo "controller : to : ".$to;
        echo "</pre>"; 

        if ($options != '') {
            // echo "A";
            foreach($options as $kode)
            {
                $code.=",".$kode;
            }
            $code=preg_replace('/,/', '', $code,1);
            $data['code'] = $code;
            
            $data['page_content'] = 'surat_jalan/view_input_surat_jalan_faktur_temp';
            $data['menu']=$this->db->query($this->querymenu);
            $data['page_title'] = 'Input Surat Jalan';
            $data['url1'] = 'all_surat_jalan/viewFaktur';
            $data['url2'] = 'all_surat_jalan/tambah_surat_jalan_temp/';
            $data['url3'] = 'all_surat_jalan/save_surat_jalan/'.$grup_lang;
            $data['grup_lang'] = $grup_lang;
            $data['from'] = $from;
            $data['to'] = $to;
            $data['variabel']=$this->model_surat_jalan->get_faktur($data);
            $data['query']=$this->model_surat_jalan->list_pelanggan($data);
            $data['kode']=$this->model_surat_jalan->list_faktur($data);
            $data['tampil_temp']=$this->model_surat_jalan->tampil_temp($data);
            $this->template($data['page_content'],$data);   

        }else{
            echo "b";
            $data['page_content'] = 'surat_jalan/view_input_surat_jalan_faktur_temp';
            $data['menu']=$this->db->query($this->querymenu);
            $data['page_title'] = 'Input Surat Jalan';
            $data['url1'] = 'all_surat_jalan/viewFaktur';
            $data['url2'] = 'all_surat_jalan/tambah_surat_jalan_temp/';
            $data['url3'] = 'all_surat_jalan/save_surat_jalan/'.$grup_lang;
            $data['grup_lang'] = $grup_lang;
            $data['query']=$this->model_surat_jalan->list_pelanggan($data);
            $data['kode']=$this->model_surat_jalan->list_faktur($data);
            $data['tampil_temp']=$this->model_surat_jalan->tampil_temp($data);
            $this->template($data['page_content'],$data);   

        }

    }

    public function delete_temp($grup_lang,$jenis_faktur,$id){        
        $hasil = $this->model_surat_jalan->proses_delete($id);        
        if ($hasil) {
            // redirect($_SERVER['HTTP_REFERER']);
            // redirect('all_surat_jalan/viewFaktur/'.$grup_lang.'/'.$jenis_faktur);
            redirect('all_surat_jalan/formTampilFaktur/');
        }
       
    }

    public function delete($id){
      $hasil = $this->model_surat_jalan->proses_delete_surat_jalan($id);
      redirect('all_surat_jalan/view_surat_jalan','refresh');
    }

    public function save_surat_jalan(){
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
        $userid=$this->session->userdata('id');


        //$sql='select customerid,group_descr from dbsls.m_customer where group_id="'.$key.'"';
        /*
         echo "<pre>";
         print_r($sql);
         echo "</pre>";

         $query=$this->db->query($sql);
         $row=$query->row();
         */
         //$post['kode_lang']=$row->customerid;
         //$post['nama_lang']=$row->group_descr;
         $post['tanggal']=$this->input->post('tanggal');
         $post['created_by']=$userid;
         $post['created']=date('Y-m-d H:i:s');

         $data = array(
                'userid'     => $userid,
                'created'    => date('Y-m-d H:i:s'),
                'tanggal'    => date('Y-m-d',strtotime($this->input->post('tanggal'))),
            );
              
            echo "userid : ".$userid."<br>";
            echo "created : ".date('Y-m-d H:i:s')."<br>";
            echo "tanggal : ".$this->input->post('tanggal')."<br>";

            //$tgl = date('Y-m-d',strtotime( $this->input->post('tanggal')));
            //echo "tgl : ".$tgl;

            $data['proses']=$this->model_surat_jalan->proses_save_surat_jalan($data);
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

        $data['page_content'] = 'surat_jalan/view_input_surat_jalan_faktur_temp';        
        $data['url1'] = 'all_surat_jalan/view_input_surat_jalan_show';
        $data['url2'] = 'all_surat_jalan/tambah_surat_jalan_temp/'.$grup_lang.'/'.$id;
        $data['page_title'] = 'Input Surat Jalan';

        $data['grup_lang'] = $grup_lang;
        $data['jenis_faktur'] = $id;
        //$data['query']=$this->model_surat_jalan->list_pelanggan($data);        
        //$data['menu']=$this->db->query($this->querymenu); 
        //$data['lang']=$this->model_surat_jalan->getPelanggan($client);        
        //$data['kode']=$this->model_surat_jalan->list_faktur($data);
        
        $data['url3'] = 'all_surat_jalan/save_surat_jalan/'.$grup_lang;
        $data['tampil_temp']=$this->model_surat_jalan->tampil_temp($grup_lang);
        $this->template($data['page_content'],$data);
    }

    public function view_do(){

      $logged_in= $this->session->userdata('logged_in');
      if(!isset($logged_in) || $logged_in != TRUE)
      {
          redirect('login/','refresh');
      }
          $data['page_content'] = 'surat_jalan/view_list_do';            
          $data['page_title'] = 'View List DO';            
          $data['grup_lang'] = "";
          $data['query']=$this->model_surat_jalan->list_pelanggan_do($data);
          $data['menu']=$this->db->query($this->querymenu);
          $data['url'] = 'all_surat_jalan/view_do_hasil/';
          $this->template($data['page_content'],$data);
    }

    public function view_do_hasil(){

      $logged_in= $this->session->userdata('logged_in');
      if(!isset($logged_in) || $logged_in != TRUE)
      {
          redirect('login/','refresh');
      } 

        $grup_lang = $this->input->post('grup_lang');
        $tahun=$this->input->post('tahun');
        $bulan=$this->input->post('bulan');
        
        $data['grup_lang'] = $this->input->post('grup_lang');
        $data['tahun']=$this->input->post('tahun');
        $data['bulan']=$this->input->post('bulan');
        $data['query'] = $this->model_surat_jalan->list_do($data);
        $data['page_content'] = 'surat_jalan/view_list_do_dp';
        $data['query_pel']=$this->model_surat_jalan->list_pelanggan_do($data);
        $data['page_title'] = 'View List DO';
        $data['url'] = 'all_surat_jalan/view_do_hasil/';
        $data['url2'] = 'all_surat_jalan/rekap_do/'.$grup_lang.'/'.$tahun.'/'.$bulan;
        $data['menu']=$this->db->query($this->querymenu);
        $data['kode']=$this->model_surat_jalan->list_do($data);
        $data['query_dp']=$this->model_surat_jalan->list_dp($data);
        $data['tampil'] = $this->model_surat_jalan->tampil_do($data);
        $this->template($data['page_content'],$data);   
        

    }

    public function rekap_do(){

      $logged_in= $this->session->userdata('logged_in');
      if(!isset($logged_in) || $logged_in != TRUE)
      {
          redirect('login/','refresh');
      }

      
        $data['grup_lang'] = $this->uri->segment('3');
        $data['tahun']= $this->uri->segment('4');
        $data['bulan']= $this->uri->segment('5');
        $grup_lang = $this->uri->segment('3');
        $tahun= $this->uri->segment('4');
        $bulan= $this->uri->segment('5');
        $options=$this->input->post('options');

        $code=$this->input->post('kodeprod');


        foreach($options as $kode)
        {
            $code.=",".$kode;
        }
        $code=preg_replace('/,/', '', $code,1);
        
        $data['query_pel']=$this->model_surat_jalan->list_pelanggan($data);
        $data['query_dp']=$this->model_surat_jalan->list_dp($data);
        $data['url'] = 'all_surat_jalan/view_do_hasil/';
        $data['url2'] = 'all_surat_jalan/rekap_do/'.$grup_lang.'/'.$tahun.'/'.$bulan;
        $data['code'] = $code;

        
        $data['query_hasil'] = $this->model_surat_jalan->rekap_do($data);
        $data['page_content'] = 'surat_jalan/view_rekap_do';
        $data['tampil'] = $this->model_surat_jalan->tampil_do($data);
        $data['page_title'] = 'View Rekap DO';

        $data['query'] = $this->model_surat_jalan->list_do($data);
        
        $this->template($data['page_content'],$data);   
      

      
    }

    public function tidak_ada_data_do()
    {
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
            $data['page_content'] = 'surat_jalan/view_do_err';
            $data['url'] = 'all_surat_jalan/view_do_hasil/';
           
            $data['page_title'] = 'View DO';
            $data['grup_lang'] = "";
            $data['query']=$this->model_surat_jalan->list_pelanggan($data);
            $data['menu']=$this->db->query($this->querymenu);
            $this->template($data['page_content'],$data);
    }

    public function delete_do($id){

      $hasil = $this->model_surat_jalan->proses_delete_do($id); 
      //redirect('all_surat_jalan/view_surat_jalan_show','refresh');
      //redirect(current_url(),'refresh');
      //redirect($this->session->flashdata('redirectToCurrent'));
      //redirect('all_surat_jalan/input_surat_jalan','refresh');
      //redirect($_SERVER['HTTP_REFERER']);
      redirect('all_surat_jalan/view_do','refresh');
    }

    public function export_do()
    {

        $this->load->library('pdf_report');

        $data['grup_lang'] = $this->uri->segment('3');
        $data['tahun']= $this->uri->segment('4');
        $data['bulan']= $this->uri->segment('5');

        $data['kode']=$this->model_surat_jalan->list_do_group($data);
        $data['query'] = $this->model_surat_jalan->export_do($data);
        $data['page_content'] = 'surat_jalan/export_do';

        $this->template($data['page_content'],$data);

    }
   
}
?>