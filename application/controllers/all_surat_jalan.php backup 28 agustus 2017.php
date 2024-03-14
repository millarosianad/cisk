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
            
            //echo "tgl : ".$data['tgl'];
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

    public function print_range()
    {
      $server='localhost';
      $user='root';
      $pass='';
      $db='mpm';
      $this->load->library('PHPJasperXML');
      //$xml = simplexml_load_file("assets/report/trans/permit_range.jrxml");
      $tgl=$this->input->post('range');
      $id=$this->input->post('keterangan');
      $format = date('Y-m-d', strtotime($tgl)); 
      $key = str_replace('-','',$format);
      
       if($id==1)
       {
            $xml = simplexml_load_file("assets/report/trans/permit_range_lunas.jrxml");
       }
       else
       {
            $xml = simplexml_load_file("assets/report/trans/permit_range_copy.jrxml");
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

      $server='localhost';
      $user='root';
      $pass='';
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

      $server='localhost';
      $user='root';
      $pass='';
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
            $pass='';
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
          $pass='';
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

    public function input_surat_jalan()
    {
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
            $data['page_content'] = 'surat_jalan/view_input_surat_jalan';
            
            $data['page_title'] = 'Input Surat Jalan';
            
            $data['grup_lang'] = "";

            $data['query']=$this->model_surat_jalan->list_pelanggan($data);
            $data['menu']=$this->db->query($this->querymenu);
            $data['url'] = 'all_surat_jalan/view_input_surat_jalan_show/';
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
            $data['url'] = 'all_surat_jalan/view_input_surat_jalan_show/';
           
            $data['page_title'] = 'Input Surat Jalan';
            $data['grup_lang'] = "";
            $data['query']=$this->model_surat_jalan->list_pelanggan($data);
            $data['menu']=$this->db->query($this->querymenu);
            $this->template($data['page_content'],$data);
    }

    public function view_input_surat_jalan_show()
    {
        //$this->session->set_flashdata('redirectToCurrent', current_url());

        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }

        $client=$this->session->userdata('client');
        //echo "var client : ".$client."<br>";
        $grup_lang = $this->input->post('grup_lang');
        //echo "grup lang controller : ".$grup_lang;
        $id=$this->input->post('keterangan');
        //echo "<br>jenis faktur controller : ".$id;

        //$data['page_content'] = 'surat_jalan/view_input_surat_jalan_faktur';
        $data['page_content'] = 'surat_jalan/view_input_surat_jalan_faktur_temp';

        
        $data['url1'] = 'all_surat_jalan/view_input_surat_jalan_show';
        $data['url2'] = 'all_surat_jalan/tambah_surat_jalan_temp/'.$grup_lang.'/'.$id;
        
        $data['page_title'] = 'Input Surat Jalan';

        $data['grup_lang'] = $grup_lang;
        $data['jenis_faktur'] = $id;
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
        //echo $jenis_faktur;
        //print_r($options);
        $grup_lang= $this->uri->segment(3);
        //echo "controller tambah_surat_jalan_temp, var grup_lang : ".$grup_lang."<br>";
        $id=$this->input->post('keterangan');
        $data['jenis_faktur'] = $jenis_faktur;
        foreach($options as $kode)
          {
              $code.=",".$kode;
          }

        $code=preg_replace('/,/', '', $code,1);

        //echo "<pre>";
        //echo "<br>kode faktur yang dipilih =  ".$code."<br>";
        //echo "</pre>"; 
        
        $data['page_content'] = 'surat_jalan/view_input_surat_jalan_faktur_temp';
        $data['menu']=$this->db->query($this->querymenu);
        $data['keterangan'] = $keterangan;
        $data['code'] = $code;
        
        $data['variabel']=$this->model_surat_jalan->get_faktur($data);
        
        $data['url1'] = 'all_surat_jalan/view_input_surat_jalan_show';
        $data['url2'] = 'all_surat_jalan/tambah_surat_jalan_temp/'.$grup_lang;
        $data['url3'] = 'all_surat_jalan/save_surat_jalan/'.$grup_lang;;
        $data['page_title'] = 'Input Surat Jalan';
        $data['grup_lang'] = $grup_lang;
        $data['query']=$this->model_surat_jalan->list_pelanggan($data);
        $data['kode']=$this->model_surat_jalan->list_faktur($data);
        $data['tampil_temp']=$this->model_surat_jalan->tampil_temp($data);

        $this->template($data['page_content'],$data);

        //redirect($this->session->userdata('http://localhost/cisk/all_surat_jalan/view_input_surat_jalan_show'));
         

    }

    public function delete_temp($id){

      $hasil = $this->model_surat_jalan->proses_delete($id);
      //redirect('all_surat_jalan/view_surat_jalan_show','refresh');
      //redirect(current_url(),'refresh');
      //redirect($this->session->flashdata('redirectToCurrent'));
      redirect('all_surat_jalan/input_surat_jalan','refresh');
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
   
}
?>