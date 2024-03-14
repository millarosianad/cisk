<?php

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Monitor extends MY_Controller
{    
    function monitor()
    {       
        // $logged_in= $this->session->userdata('logged_in');
        // if(!isset($logged_in) || $logged_in != TRUE)
        // {
        //     redirect('login/','refresh');
        // }
        set_time_limit(0);
        $this->load->library(array('table', 'template', 'Excel_generator', 'form_validation', 'email', 'zip'));
        $this->load->helper(array('url', 'csv'));
        $this->load->model(array('model_outlet_transaksi','model_monitor'));
        $this->load->model('model_upload_data_text');
    }
    function index()
    {
        $this->dashboard();
    }

    private function template($view,$data)
    {
        $this->template->set_title('MPM SQUARE');
        $this->template->add_js('modules/skeleton.js');
        $this->template->add_css('modules/skeleton.css');
        $this->template->load_view($view, $data);
    }

    public function dashboard(){

        $data = [
            'title'                   => 'MPM Monitoring | Deltomed',
            'get_dashboard_monitor'   => $this->model_monitor->get_dashboard_monitor(),
            // 'get_mti_herbal'          => $this->model_monitor->get_mti_breakdown_site_code('herbal'),
            // 'get_mti_candy'           => $this->model_monitor->get_mti_breakdown_site_code('candy'),
            // 'get_mti_rtd'             => $this->model_monitor->get_mti_breakdown_site_code('rtd'),
            'signature'               => $this->model_monitor->get_last_signature()->row()->signature
        ];

        $this->load->view('monitor/top_header', $data);
        $this->load->view('monitor/header_full_width', $data);
        $this->load->view('monitor/content', $data);
        $this->load->view('monitor/chart',$data);
        $this->load->view('monitor/footer');

    }

    public function update_data(){
        
        $data_source = [
            'd1'                => $this->model_monitor->get_kodeprod_by_group('G0101'),
            'd2_exclude_rtd'   => $this->model_monitor->get_kodeprod_by_group_exception('G0102', '010121'),
            // 'all_principal'     => $this->model_monitor->get_kodeprod_by_supp(),
        ];

        $update = $this->model_monitor->update_data($data_source);

        if ($update) {
            redirect('monitor/dashboard');
        }else{
            echo "something happen. Please call IT";
        }        

    }

    function email($signature){

        $from = "suffy@muliaputramandiri.net";
        $to = 'suffy.yanuar@gmail.com';
        $cc = 'suffy.mpm@gmail.com';
        
        $subject = "MPM Site | Summary Deltomed";
        // echo "no_relokasi : ".$no_relokasi;

        $this->load->model('model_relokasi');
        $this->model_relokasi->email();

        $data = [
            'data'    => $this->model_monitor->get_dashboard_monitor($signature),
            'signature'         => $signature
        ];
        $message = $this->load->view("monitor/email",$data,TRUE);

        $this->email->from($from,'PT. Mulia Putra Mandiri');
        $this->email->to($to);
        $this->email->cc($cc);
        $this->email->subject($subject);
        $this->email->message($message);
        $send = $this->email->send();
        if ($send) {
            echo "<script>alert('pengiriman email berhasil'); </script>";
            redirect('monitor/dashboard','refresh');
        }else{
            echo "<script>alert('pengiriman email gagal'); </script>";
            redirect('monitor/dashboard','refresh');
        } 
    }

    public function manage_email(){

        $data = [
            'title'     => 'MPM Monitoring | Manage Email',
            'url'       => 'monitor/update_email',
            'get_email' => $this->model_monitor->get_email()
        ];

        $this->load->view('monitor/top_header', $data);
        // $this->load->view('monitor/header', $data);
        $this->load->view('monitor/manage_email', $data);
        $this->load->view('monitor/footer');

    }

    public function update_email(){

        $signature = 'x';
        $data = [
            'email_to'  => $this->input->post('email_to'),
            'email_cc'  => $this->input->post('email_cc'),
        ];

        $this->db->where('signature', $signature);
        $this->db->update('site.dashboard_monitor_email', $data);
        redirect('monitor/manage_email'); 

    }

    public function chart(){
        $data = [
            'title'     => 'MPM Monitoring | Deltomed',
            'get_dashboard_monitor'   => $this->model_monitor->get_dashboard_monitor(),
            'get_mti_herbal'          => $this->model_monitor->get_mti_breakdown_site_code('herbal'),
            'get_mti_candy'           => $this->model_monitor->get_mti_breakdown_site_code('candy'),
            'get_mti_rtd'             => $this->model_monitor->get_mti_breakdown_site_code('rtd'),
            'signature'               => $this->model_monitor->get_last_signature()->row()->signature
        ];

        $this->load->view('monitor/header', $data);
        // $this->load->view('monitor/header_chart', $data);
        $this->load->view('monitor/chart', $data);
        $this->load->view('monitor/footer');

    }

    public function library_raw_data(){

        $data = [
            'title'     => 'MPM Monitoring | Library Raw Data',
            'get_library_raw_data'   => $this->model_monitor->get_library_raw_data(),
            'signature'               => $this->model_monitor->get_last_signature()->row()->signature
        ];

        $this->load->view('monitor/top_header', $data);
        $this->load->view('monitor/header', $data);
        $this->load->view('monitor/library_raw_data', $data);
        // $this->load->view('monitor/chart',$data);
        $this->load->view('monitor/footer');

    }

    public function upload_data_text(){

        $data = [
            'title'     => 'Upload Data Txt Afiliasi | Form Upload',
            'url'       => 'monitor/upload',
            'file'      => $this->model_upload_data_text->get_file(),
        ];

        $this->load->view('management_rpd/top_header', $data);
        $this->load->view('kalimantan/header_full_width', $data);
        $this->load->view('monitor/upload_data_text', $data);
        $this->load->view('kalimantan/footer');

    }

    public function upload(){
        if (!is_dir('./assets/uploads/tester/')) {
            @mkdir('./assets/uploads/tester/', 0777);
        }

        //konfigurasi upload zip
        $config['upload_path'] = './assets/uploads/tester/';
        // $config['upload_path'] = './assets/uploads/tester/';
        $config['allowed_types'] = 'zip|ZIP';
        $config['max_size'] = '*';
        $config['overwrite'] = 'TRUE';

        $this->load->library('upload', $config);

        if ($this->upload->do_upload('file')) 
        {

            $upload_data     = $this->upload->data();
            $filename        = $upload_data['orig_name'];
            $tanggal         = substr($upload_data['orig_name'], 6, 2);
            $month           = substr($upload_data['orig_name'], 8, 2);
            $tahun           = substr($upload_data['orig_name'], 10, 4);
            $username        = $this->session->userdata('username');
            $typedata        = $this->input->post('typedata');
            $created_by      = $this->session->userdata('id');
            $created_at      = $this->model_outlet_transaksi->timezone();
            $signature       = md5($created_at.$created_by);
            
            $data = [
                'created_by'        => $created_by,
                'username'          => $username,
                'filename'          => $filename,
                'tanggal'           => $tanggal,
                'bulan'             => $month,
                'tahun'             => $tahun,
                'status_proses'     => 1,
                'created_at'        => $created_at,
                'typedata'          => $typedata,
                'signature'         => $signature
            ];

            $this->db->insert('test_it.upload_dt', $data);

            echo '<script>alert("Sukses Upload Data Text");</script>';
            // redirect("monitor/upload_data_text", 'refresh');
           
            $this->load->library('email');
            $config['protocol']     = 'smtp';
            $config['smtp_host']    = 'ssl://smtp.gmail.com';
            $config['smtp_port']    = '465';
            $config['smtp_timeout'] = '300';
            $config['smtp_user']    = 'suffy@muliaputramandiri.net';
            $config['smtp_pass']    = 'vruzinbjlnsgzagy';
            // $config['smtp_user']    = 'millarosianad@gmail.com';
            // $config['smtp_pass']    = 'swivsikmeoxxjccm';
            $config['charset']      = 'utf-8';
            $config['newline']      = "\r\n";
            $config['mailtype']     ="html";
            $config['use_ci_email'] = TRUE;
            $config['wordwrap']     = TRUE;

            $this->email->initialize($config);

            $from = "suffy@muliaputramandiri.net";
            $to = "datatext@muliaputramandiri.com";
            $subject = "Data Text | $username - $typedata - $filename";
            $message = "Automatic Email By Sistem, DP $username dengan file $filename telah berhasil upload pada $created_at . Mohon segera diproses !";

            $this->load->library('email');
            $this->email->from($from, $username);
            $this->email->to($to);
            $this->email->subject($subject);
            $this->email->message($message);
            // $this->email->attach('assets/file/dc/'.str_replace('/','_',$kode).'.pdf');
            // return $this->email->send();
            $send = $this->email->send();
            // echo $this->email->print_debugger();
            // die;
            if ($send) {
                echo "<script>alert('pengiriman email berhasil'); </script>";
                redirect('monitor/upload_data_text','refresh');
            }else{
                echo "<script>alert('pengiriman email gagal'); </script>";
                redirect('monitor/upload_data_text','refresh');
            } 
        
        } else {
            // $error = array('error' => $this->upload->display_errors());
            // $this->load->view('upload_form_view', $error);

            echo '<script>alert("File tidak sesuai. Pastikan File nya sudah benar. Jika perlu bantuan silahkan hubungi IT !");</script>';
            redirect("monitor/upload_data_text", 'refresh');// echo "Gagal mengunggah file";
        }
    }

    // public function status($signature, $status, $process_at, $finish_at){
    public function status(){

        // echo $this->input->post('signature');
        // die;
        // $process_at      = $this->input->post('event-dt');
        // $signature      = $this->input->post('signature');
        $data = [
            'status_proses' => 2,
            'process_at'    => $this->input->post('event-dt'),
        ];
        $this->db->where('signature', $this->input->post('signature'));        
        $this->db->update('test_it.upload_dt', $data);

        redirect("/monitor/upload_data_text");

    }

    public function status_finish(){

        // echo $this->input->post('signature');
        // die;
        // $process_at      = $this->input->post('event-dt');
        // $signature      = $this->input->post('signature');
        $data = [
            'status_proses' => 3,
            'finish_at'     => $this->input->post('event-dt'),
        ];
        $this->db->where('signature', $this->input->post('signature'));        
        $this->db->update('test_it.upload_dt', $data);

        redirect("/monitor/upload_data_text");

    }

    public function status_error(){
        $data = [
            'status_proses' => 4,
            'pesan_error' => $this->input->post('message-text'),
        ];
        // $this->db->where('signature', $signature); 
        $this->db->where('signature', $this->input->post('signature'));
        $this->db->update('test_it.upload_dt', $data);
        

        redirect("/monitor/upload_data_text");

    }
    
}
?>
