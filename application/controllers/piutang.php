<?php

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Piutang extends MY_Controller
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

    function piutang()
    {
        set_time_limit(0);
        $this->load->library(array('table','template','Excel_generator', 'form_validation'));
        $this->load->helper('url');
        $this->load->model('mpiutang');
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

        // $this->view_surat_jalan();
    }

    private function template($view,$data)
    {
        $this->template->set_title('MPM SQUARE');
        $this->template->add_js('modules/skeleton.js');
        $this->template->add_css('modules/skeleton.css');
        $this->template->load_view($view, $data);
    }

    public function formPiutang()
    {
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
        $data['page_content'] = 'piutang/formPiutang';            
        $data['page_title'] = 'View Piutang';
        $data['grup_lang'] = "";
        $grup_lang = $this->input->post('grup_lang');
        // echo "<br><br><br>grup lang controller : ".$grup_lang;
        $data['menu']=$this->db->query($this->querymenu);
        $data['url'] = 'piutang/viewPiutang/';
        $this->template($data['page_content'],$data);
    }

    public function viewPiutang()
    {
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
        
        $data['range'] = $this->input->post('range');
        $data['tanggal'] = $this->input->post('tanggal');

        // echo "<pre>";
        // echo "tangga : ".$this->input->post('tanggal');
        // echo "</pre>";
        
        if ($data['range']=='1') {
            $data['page_content'] = 'piutang/viewPiutang';            
            $data['page_title'] = 'View Piutang - Bulan';    
            $data['query']=$this->mpiutang->viewPiutang($data);
        }else{
            $data['page_content'] = 'piutang/viewPiutangMinggu';            
            $data['page_title'] = 'View Piutang - Minggu';
            $data['query']=$this->mpiutang->viewPiutangMinggu($data);
        }
        
        $data['menu']=$this->db->query($this->querymenu);
        $data['url'] = 'piutang/viewPiutang/';
        $this->template($data['page_content'],$data);
    }

    public function detailPiutang()
    {
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
        $data['page_content'] = 'piutang/detailPiutang';            
        $data['page_title'] = 'Detail Piutang - Bulan';
        $data['key'] = $this->uri->segment('3');
        $data['grup_lang'] = $this->uri->segment('4');
        $data['tanggal'] = $this->uri->segment('5');
        
        $data['query']=$this->mpiutang->DetailPiutang($data);
        $data['menu']=$this->db->query($this->querymenu);
        // $data['query2'] = $this->model_surat_jalan->status($data);
        $data['url'] = 'piutang/detailPiutang/';
        $this->template($data['page_content'],$data);
    }

    public function detailPiutangMinggu()
    {
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
        $data['page_content'] = 'piutang/detailPiutangMinggu';            
        $data['page_title'] = 'Detail Piutang - Minggu';
        $data['key'] = $this->uri->segment('3');
        $data['grup_lang'] = $this->uri->segment('4');
        $data['tanggal'] = $this->uri->segment('5');
        
        $data['query']=$this->mpiutang->DetailPiutangMinggu($data);
        $data['menu']=$this->db->query($this->querymenu);
        // $data['query2'] = $this->model_surat_jalan->status($data);
        $data['url'] = 'piutang/detailPiutangMinggu/';
        $this->template($data['page_content'],$data);
    }


    // public function emailPiutang()
    // {
    //     $logged_in= $this->session->userdata('logged_in');
    //     if(!isset($logged_in) || $logged_in != TRUE)
    //     {
    //         redirect('login/','refresh');
    //     }
    //     // $data['page_content'] = 'piutang/detailPiutang';            
    //     $data['page_title'] = 'Detail Piutang - Bulan';
    //     $data['key'] = $this->uri->segment('3');
    //     $data['grup_lang'] = $this->uri->segment('4');
    //     $data['tanggal'] = $this->uri->segment('5');
        
    //     $data['menu']=$this->db->query($this->querymenu);
    //     $data['company']=$this->mpiutang->getCompanyByKodelang($data); 
    //     $data['query']=$this->mpiutang->emailPiutang($data);        
    //     // $data['url'] = 'piutang/detailPiutang/';
    //     // $this->template($data['page_content'],$data);
    // }

    public function emailPiutang(){
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }

        $this->load->library('email');

        $data['key'] = $this->uri->segment('3');
        $data['grup_lang'] = $this->uri->segment('4');
        $data['tanggal'] = $this->uri->segment('5');
        $data['company']=$this->mpiutang->getCompanyByKodelang($data);
        $data['emailTo']=$this->mpiutang->getEmailFinance($data); 
        $data['getMessage'] = $this->mpiutang->getMessage($data);
        $data['page_content'] = 'piutang/emailPiutang'; 
        $data['page_title'] = 'Preview Email Piutang'; 
        $data['url'] = 'piutang/kirimEmailPiutang';
        $data['menu']=$this->db->query($this->querymenu);
        
        $data['from'] = 'suriana@muliaputramandiri.com'; 
        // $data['cc'] = array('suffy.yanuar@gmail.com','suffy@muliaputramandiri.com'); 
        $data['cc'] = 'nanita@muliaputramandiri.com,herman.oscar@muliaputramandiri.com,hwiryanto@muliaputramandiri.com'; 
        

        $key = $this->uri->segment('3');
        if($key == '3'){ //current
            $subject = "Tagihan Due Date";
            $waktu = "akan jatuh tempo";
            $waktu_2 = "maksimal sampai dengan tanggal Jatuh Tempo, sehingga memperlancar proses PO yang akan / sudah diajukan.";
        }elseif($key == '5'){ //1-30
            $subject = "Tagihan Overdue";
            $waktu = "telah jatuh tempo selama 1 bulan";
            $waktu_2 = "dalam minggu ini, sehingga PO yang belum di jalankan dapat di proses.";        
        }elseif ($key == '6') {
            $subject = "Tagihan Overdue";
            $waktu = "telah jatuh tempo selama 2 bulan";
            $waktu_2 = "dalam minggu ini, sehingga PO yang belum di jalankan dapat di proses.";
        }elseif ($key == '7') {
            $subject = "Tagihan Overdue";
            $waktu = "telah jatuh tempo selama 3 bulan";
            $waktu_2 = "dalam minggu ini, sehingga PO yang belum di jalankan dapat di proses.";
        }elseif ($key == '8') {
            $subject = "Tagihan Overdue";
            $waktu = "telah jatuh tempo selama lebih dari 3 bulan";
            $waktu_2 = "dalam minggu ini, sehingga PO yang belum di jalankan dapat di proses.";
        }elseif ($key == '9') {
            $subject = "Tagihan Overdue";
            $waktu = "telah jatuh tempo";
            $waktu_2 = "dalam minggu ini, sehingga PO yang belum di jalankan dapat di proses.";
        }

        $data['waktu'] = $waktu;
        $data['waktu_2'] = $waktu_2;
        $grup_lang = $this->uri->segment('4');
        $tanggal = $this->uri->segment('5');
        $company=$this->mpiutang->getCompanyByKodelang($data); 
        $emailTo =$this->mpiutang->getEmailFinance($data);
        $getMessage = $this->mpiutang->getMessage($data);

        // $emailCc = array('nanita@muliaputramandiri.com','hwiryanto@muliaputramandiri.com','herman.oscar@muliaputramandiri.com');
      
        // echo "<pre>";
        // echo "key : ".$key."<br>";
        // echo "grup_lang : ".$grup_lang."<br>";
        // echo "tanggal : ".$tanggal."<br>";
        // echo "company : ".$company."<br>";
        // echo "emailTo : ".$emailTo."<br>";
        // // echo "getMessage : ".$getMessage."<br>";
        // echo "</pre>";

        $data['subject'] = $subject.' '.$company; 
        $this->template($data['page_content'],$data);

    }

    public function kirimEmailPiutang(){
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }

        $from = $this->input->post('from');
        $to = $this->input->post('to');
        $cc = $this->input->post('cc');
        $subject = $this->input->post('subject');
        $message = $this->input->post('message');

        // echo "<pre>";
        // echo "from : ".$from."<br>";
        // echo "to : ".$to."<br>";
        // echo "cc : ".$cc."<br>";
        // echo "subject : ".$subject."<br>";
        // echo "message : ".$message."<br>";
        // echo "</pre>";

        $this->load->library('email');

        $config['protocol']  = 'smtp';
         $config['smtp_host'] = 'ssl://smtp.gmail.com';
         $config['smtp_port'] = '465';
         $config['smtp_timeout'] = '300';
         $config['smtp_user'] = 'surianampm@gmail.com';
         $config['smtp_pass'] = 'kasumaputra';
         $config['charset']  = 'utf-8';
         $config['newline']  = "\r\n";
         $config['mailtype'] ="html";
         $config['use_ci_email'] = TRUE;
        $config['wordwrap'] = TRUE;

        $this->email->initialize($config);
        $this->email->from($from,'PT. Mulia Putra Mandiri');
        $this->email->to($to);
        $this->email->cc($cc);
        $this->email->subject($subject);
        $this->email->message($message);
        // $this->email->message("Dear ".$this->getCompanyByKodelang(substr($id,1)).
		// 		"<br/><br/>Sehubungan dengan tagihan ".$this->getCompanyByKodelang(substr($id,1))." yang telah jatuh tempo lebih dari tujuh hari ,dengan rincian sbb<br/><br/>"
		// 		.$this->piutang('detail7up',$key,$id,$init)."<br/><br/>Kami meminta bantuannya untuk dapat melunasi tagihan yang telah jatuh tempo dalam minggu ini, sehingga PO yang belum di jalankan dapat di proses."
		// 		."Bila ada pertanyaan dapat menghubungi PT.MPM.<br/><br/>Terima Kasih<br/><br/>Rgds<br/><br/>Suriana<br/><br/>NB:Abaikan email ini jika sudah melakukan pembayaran atas faktur tersebut"
		// 		);
        // $this->email->message('Testing the email class.');
        // $this->email->message(print_r($getMessage, true));
        $this->email->send();
        $this->email->print_debugger();



        // $this->template($data['page_content'],$data);




    }

    public function emailPiutangMinggu(){
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }

        $this->load->library('email');

        $data['key'] = $this->uri->segment('3');
        $data['grup_lang'] = $this->uri->segment('4');
        $data['tanggal'] = $this->uri->segment('5');
        $data['company']=$this->mpiutang->getCompanyByKodelang($data);
        $data['emailTo']=$this->mpiutang->getEmailFinance($data); 
        $data['getMessage'] = $this->mpiutang->getMessageMinggu($data);
        $data['page_content'] = 'piutang/emailPiutang'; 
        $data['page_title'] = 'Preview Email Piutang'; 
        $data['url'] = 'piutang/kirimEmailPiutang';
        $data['menu']=$this->db->query($this->querymenu);
        
        $data['from'] = 'suriana@muliaputramandiri.com'; 
        // $data['cc'] = array('suffy.yanuar@gmail.com','suffy@muliaputramandiri.com'); 
        $data['cc'] = 'nanita@muliaputramandiri.com,herman.oscar@muliaputramandiri.com,hwiryanto@muliaputramandiri.com'; 
        

        $key = $this->uri->segment('3');
        if($key == '3'){ //current
            $waktu = "akan jatuh tempo";
            $waktu_2 = "maksimal sampai dengan tanggal Jatuh Tempo, sehingga memperlancar proses PO yang akan / sudah diajukan.";
        
        }elseif($key == '5'){ //1-7
            $waktu = "telah jatuh tempo selama 1 minggu";
            $waktu_2 = "dalam minggu ini, sehingga PO yang belum di jalankan dapat di proses.";
        }elseif ($key == '6') { //8-14
            $waktu = "telah jatuh tempo selama 2 minggu"; 
            $waktu_2 = "dalam minggu ini, sehingga PO yang belum di jalankan dapat di proses.";
        }elseif ($key == '7') { //15-30
            $waktu = "telah jatuh tempo selama 3 minggu";
            $waktu_2 = "dalam minggu ini, sehingga PO yang belum di jalankan dapat di proses.";
        }elseif ($key == '8') { //30
            $waktu = "telah jatuh tempo selama lebih dari 3 minggu";
            $waktu_2 = "dalam minggu ini, sehingga PO yang belum di jalankan dapat di proses.";
        }

        $data['waktu'] = $waktu;
        $data['waktu_2'] = $waktu_2;
        $grup_lang = $this->uri->segment('4');
        $tanggal = $this->uri->segment('5');
        $company=$this->mpiutang->getCompanyByKodelang($data); 
        $emailTo =$this->mpiutang->getEmailFinance($data);
        $getMessage = $this->mpiutang->getMessage($data);

        // $emailCc = array('nanita@muliaputramandiri.com','hwiryanto@muliaputramandiri.com','herman.oscar@muliaputramandiri.com');
      
        // echo "<pre>";
        // echo "key : ".$key."<br>";
        // echo "grup_lang : ".$grup_lang."<br>";
        // echo "tanggal : ".$tanggal."<br>";
        // echo "company : ".$company."<br>";
        // echo "emailTo : ".$emailTo."<br>";
        // // echo "getMessage : ".$getMessage."<br>";
        // echo "</pre>";

        $data['subject'] = 'Tagihan Overdue '.$company; 


        $this->template($data['page_content'],$data);

    }


   
}
?>