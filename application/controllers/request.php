<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Request extends MY_Controller
{
    function request()
    {
        set_time_limit(0);
        $this->load->library(array('table','template','Excel_generator', 'form_validation','email'));
        $this->load->helper('url');
        $this->load->helper('csv');
        $this->load->model('model_omzet');
        $this->load->model('M_menu');
        $this->load->model('model_per_hari');
        $this->load->model('model_request');
        $this->load->database();
    }
    function index()
    {
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
    }

    public function history(){
        $created_by=$this->session->userdata('id');
        if ( function_exists( 'date_default_timezone_set' ) )
        date_default_timezone_set('Asia/Jakarta');        
        $created_date='"'.date('Y-m-d H:i:s').'"';

        if ($created_by != 297 && $created_by != 452 && $created_by != 11 && $created_by != 547) {
            
            echo "<script>alert('anda tidak diijinkan mengakses ini'); </script>";
            redirect('http://site.muliaputramandiri.com','refresh');

        }else{

            $data = [
                'id'        => $this->session->userdata('id'),
                // 'url'       => 'request/history_proses/',
                'title'     => 'Request Perubahan Outlet (Tipe/Class/Segment)',
                'get_label' => $this->M_menu->get_label(),
                'request'   => $this->model_request->history()
            ];
            $this->load->view('template_claim/top_header');
            $this->load->view('template_claim/header');
            $this->load->view('template_claim/sidebar',$data);
            $this->load->view('template_claim/top_content',$data);
            $this->load->view('request/history',$data);
            $this->load->view('template_claim/bottom_content');
            $this->load->view('template_claim/footer');
            
        }
        
    }    

    public function detail_request($id){

        $data = [
            'get_null'  => $this->model_request->get_null($id),
            'id'        => $this->session->userdata('id'),
            'url'       => 'request/proses_detail_request/',
            'title'     => 'Custom Approval',
            'get_label' => $this->M_menu->get_label(),
            'request'   => $this->model_request->detail_request($id)
        ];
        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar',$data);
        // $this->load->view('template_claim/top_content',$data);
        $this->load->view('template_claim/top_content_request',$data);
        $this->load->view('request/detail_request',$data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
    }    

    public function proses_detail_request(){

        $request = $this->input->post('options');
        $code = '';
        foreach($request as $kode)
        {
            $code.=",".$kode;
        }

        $data = [
            'id_log' => $this->input->post('id_log'),
            'note' => $this->input->post('note'),
            'status_approve' => $this->input->post('status_approve'),
            'no_request'  => preg_replace('/,/', '', $code,1),

        ];
        $proses = $this->model_request->proses_detail_request($data);    
        // echo "proses : ".$proses;
        if ($proses) {
            
            foreach ($proses as $key) {
                $status_approve = $key->status_approve;
                $id = $key->id;
                $status = $key->status;
            }
            echo "<script>alert('$status data success'); </script>";
            echo "<script>alert('website akan mengirim informasi ini melalui email'); </script>";
            $this->email_approval($status_approve,$id);

        }else{
            // echo "a";
        }
        // // $this->email_ticket($proses);

    }    

    public function approval_request($status_approve,$id,$kode_comp){
        $proses = $this->model_request->proses_approval_request($status_approve,$id,$kode_comp); 
        // echo "proses : ".$proses;
        if ($proses) {
            // redirect('request/history/');
            // echo "<script>alert('$proses data success'); window.location = '../../../history';</script>";
            echo "<script>alert('$proses data success'); </script>";
            echo "<script>alert('website akan mengirim informasi ini melalui email'); </script>";
            $this->email_approval($status_approve,$id);
        }else{
            echo "error. hubungi it";
        }
    }

    public function approval_request_email($status_approve,$id,$signature){
        date_default_timezone_set('Asia/Jakarta'); 
        $date = date_create(date('Y-m-d H:i:s'));
        date_add($date, date_interval_create_from_date_string('-7 hours'));
        $created_date = '"'.date_format($date, 'Y-m-d H:i:s').'"';

        $created_by=$this->session->userdata('id');
        // if ( function_exists( 'date_default_timezone_set' ) )
        // date_default_timezone_set('Asia/Jakarta');        
        // $created_date='"'.date('Y-m-d H:i:s').'"';

        $sql_update_detail = "update dbrest.t_request_customer_detail a
            set a.status_approval = $status_approve, a.created_by = $created_by, a.created_date = $created_date
            where a.log_id = $id
        ";
        $proses_update = $this->db->query($sql_update_detail);
        $sql_update = "update dbrest.t_request_customer a
            set a.status_approval = $status_approve, a.created_by = $created_by, a.created_date = $created_date
            where a.log_id = $id
        ";
        $proses_update = $this->db->query($sql_update);

        echo "<script>alert('proses data success'); </script>";
     
        $this->email_approval($status_approve,$id);
    }

    public function email_approval($status_approve,$id){

        // echo "status_approve : ".$status_approve."<br>";

        $generate_detail = $this->model_request->generate_detail($id);
        $get_detail = $this->model_request->get_detail($id);
        foreach ($get_detail as $key) {
            $kode = $key->kode;
            $branch_name = $key->branch_name;
            $nama_comp = $key->nama_comp;
            $signature = $key->signature;
            $signaturex = $key->signaturex;
        }

        // echo "kode : ".substr($kode,0,3)."<br>"; 
        // echo "branch_name : ".$branch_name."<br>"; 
        // echo "nama_comp : ".$nama_comp."<br>"; 
        // echo "signature : ".$signature."<br>"; 
        // echo "signaturex : ".$signaturex."<br>"; 
        
        $get_email_dp = $this->model_request->email_dp(substr($kode,0,3));
        foreach ($get_email_dp as $key) {
            $email_dp = $key->email;
        }
        

        $data['id'] = $id;
        $data['branch_name'] = $branch_name;
        $data['nama_comp'] = $nama_comp;

        $from = "suffy@muliaputramandiri.com";
        // $to = "suffy.mpm@gmail.com";
        $to = $email_dp; //setelah rilis
        // $data['to'] = "suffy.mpm@gmail.com";
        $data['to'] = $to;
        $cc = "sampurno@muliaputramandiri.com,suffy.yanuar@gmail.com,felix@muliaputramandiri.com,ilham@muliaputramandiri.com,dewi@muliaputramandiri.com";
        // $cc = "sampurno@muliaputramandiri.com"; //setelah rilis
        $subject = "MPM Site|Request Perubahan Tipe Class Outlet";

        $message = $this->load->view("request/template_email_to_dp",$data,TRUE);

        $this->load->library('email');
        $config['protocol']     = 'smtp';
        // $config['smtp_host']    = 'ssl://mail.muliaputramandiri.com';
        $config['smtp_host']    = 'ssl://smtp.gmail.com';
        $config['smtp_port']    = '465';
        $config['smtp_timeout'] = '300';
        // $config['smtp_user']    = 'support@muliaputramandiri.com';
        $config['smtp_user']    = 'suffy@muliaputramandiri.net';
        // $config['smtp_pass']    = 'support123!@#';
        $config['smtp_pass']    = 'vruzinbjlnsgzagy';
        $config['charset']      = 'utf-8';
        $config['newline']      = "\r\n";
        $config['mailtype']     ="html";
        $config['use_ci_email'] = TRUE;
        $config['wordwrap']     = TRUE;
        
        // $this->load->library('email');
        // $config['protocol']     = 'smtp';
        // $config['smtp_host']    = 'ssl://mail.muliaputramandiri.com';
        // $config['smtp_port']    = '465';
        // $config['smtp_timeout'] = '300';
        // $config['smtp_user']    = 'support@muliaputramandiri.com';
        // $config['smtp_pass']    = 'support123!@#';
        // $config['charset']      = 'utf-8';
        // $config['newline']      = "\r\n";
        // $config['mailtype']     = "html";
        // $config['use_ci_email'] = TRUE;
        // $config['wordwrap']     = TRUE;

        // $config['protocol']     = 'smtp';
        // $config['smtp_host']    = 'ssl://smtp.gmail.com';
        // $config['smtp_port']    = '465';
        // $config['smtp_timeout'] = '300';
        // $config['smtp_user']    = 'suffy.yanuar@gmail.com';
        // $config['smtp_pass']    = 'ririn123!@#';
        // $config['charset']      = 'utf-8';
        // $config['newline']      = "\r\n";
        // $config['mailtype']     ="html";
        // $config['use_ci_email'] = TRUE;
        // $config['wordwrap']     = TRUE;

        $this->email->initialize($config);
        $this->email->from($from,'PT. Mulia Putra Mandiri');
        $this->email->to($to);
        $this->email->cc($cc);
        $this->email->subject($subject);
        $this->email->message($message);
        $this->email->attach('assets/file/request/'.$id.'.csv');
        $send = $this->email->send();
        // echo $this->email->print_debugger();
        if ($send) {
            echo "<script>alert('pengiriman email berhasil'); </script>";
            redirect('request/history','refresh');
        }else{
            echo "<script>alert('pengiriman email gagal'); </script>";
            redirect('request/history','refresh');
        }

    }

    

    public function approval_all($log_id,$signature,$status_approve){
        $created_by=$this->session->userdata('id');
        if ( function_exists( 'date_default_timezone_set' ) )
        date_default_timezone_set('Asia/Jakarta');        
        $created_date='"'.date('Y-m-d H:i:s').'"';

        if ($created_by != 297 && $created_by != 452) {
            
            echo "<script>alert('untuk sementara anda tidak diijinkan mengakses ini. Mohon pastikan anda sudah login web terlebih dahulu'); </script>";
            redirect('http://site.muliaputramandiri.com','refresh');

        }else{

            $this->approval_request_email($status_approve,$log_id,$signature);
            
        }
    }
    
}
?>
