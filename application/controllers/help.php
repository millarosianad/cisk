<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Help extends MY_Controller
{
    function help()
    {
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
        set_time_limit(0);
        $this->load->library(array('table','template','Excel_generator', 'form_validation','email'));
        $this->load->helper('url');
        $this->load->helper('csv');
        $this->load->model('model_help');
        $this->load->model('M_menu');
        $this->load->database();

    }

    public function view_ticket(){
        $data = [
            'id'        => $this->session->userdata('id'),
            'url'       => 'assets/view_assets/',
            'title'     => 'View Ticket',
            'get_label' => $this->M_menu->get_label(),
            'asset'     =>$this->model_help->view_ticket()
        ];
        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar',$data);
        $this->load->view('template_claim/top_content',$data);
        $this->load->view('help/view_ticket',$data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
    }

    public function detail_ticket($id){
        $data = [
            'id'        => $this->session->userdata('id'),
            'url'       => 'assets/view_assets/',
            'title'     => 'View Ticket',
            'get_label' => $this->M_menu->get_label(),
            'asset'     =>$this->model_help->view_ticket_detail($id)
        ];
        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar',$data);
        $this->load->view('template_claim/top_content',$data);
        $this->load->view('help/view_ticket_detail',$data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
    }

    public function input_ticket($from='',$to=''){
        $data = [
            'url'       => 'help/input_ticket_proses/',
            'title'     => 'Create Ticket',
            'get_label' => $this->M_menu->get_label(),
            'from'      => '',
            'to'        => '',
            'get_po'     =>$this->model_help->get_po($from,$to)
        ];

        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar',$data);
        $this->load->view('template_claim/top_content',$data);
        $this->load->view('help/input_ticket');
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
    }

    public function get_periode(){
        $from = trim($this->input->post('from',TRUE));
        $to = trim($this->input->post('to',TRUE));
        echo "contro from : ".$from;
        echo "contro to : ".$to;
        $data['get_po']=$this->model_help->get_po_x($from,$to);
        foreach($data['get_po'] as $row)
        {
            $output .= "<option value='".$row->nopo."'>".$row->nopo."</option>";
            // $output .= $row->nopo;

        }
        echo $output;

    }

    public function input_ticket_proses(){
        $from = $this->input->post('from');
        $to = $this->input->post('to');

        $data = [
            'url'       => 'help/input_ticket_proses/',
            'url2'       => 'help/submit_ticket/',
            'title'     => 'Create Ticket',
            'get_label' => $this->M_menu->get_label(),
            'get_po'    =>$this->model_help->get_po($from,$to),
            'from'      => $this->input->post('from'),
            'to'        => $this->input->post('to'),
        ];

        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar',$data);
        $this->load->view('template_claim/top_content',$data);
        $this->load->view('help/input_ticket_proses',$data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
    }

    public function submit_ticket(){
        $deskripsi = $this->input->post('deskripsi');

        $data = [
            'get_label' => $this->M_menu->get_label(),
            'deskripsi' => $this->input->post('deskripsi'),
            // 'nopo'  => preg_replace('/,/', '', $code,1),
            'po_id'  => $this->input->post('options')

        ];

        $proses = $this->model_help->submit_ticket($data);    
        $this->email_ticket($proses);
        

    }

    function email_ticket($id_ticket){
        
        // echo "id_ticket : ".$id_ticket;
        $email_cc = $this->session->userdata('email');
        // echo "email cc : ".$email_cc;

        $email_to = "suffy@muliaputramandiri.com";

        $from = "suffy@muliaputramandiri.com";
        $subject = "site.muliaputramandiri.com|helpdesk| No Tiket".$id_ticket;
        $this->load->library('email');
        $message = "Automatic Email By Sistem. Email ini menginformasikan bahwa tiket permintaan bantuan sudah aktif. Mohon tindak lanjuti tiket ini";
        $config['protocol']     = 'smtp';
        $config['smtp_host']    = 'ssl://smtp.gmail.com';
        $config['smtp_port']    = '465';
        $config['smtp_timeout'] = '300';
        $config['smtp_user']    = 'suffy.yanuar@gmail.com';
        $config['smtp_pass']    = 'yanuar123!@#';
        $config['charset']      = 'utf-8';
        $config['newline']      = "\r\n";
        $config['mailtype']     ="html";
        $config['use_ci_email'] = TRUE;
        
        $config['wordwrap']     = TRUE;

        $this->email->initialize($config);
        $this->email->from($from,'PT. Mulia Putra Mandiri');
        $this->email->to($email_to);
        $this->email->cc($email_cc);
        $this->email->subject($subject);
        $this->email->message($message);
        // $this->email->attach("assets/po/".$data['filename'].".pdf");
        $send = $this->email->send();
        // echo $this->email->print_debugger();
        if ($send) {
            redirect("help/view_ticket");
        }else{
            echo "ada kesalahan pengiriman email. mohon hubungi IT secara langsung";
        }

    }
 
}
?>