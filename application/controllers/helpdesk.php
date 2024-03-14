<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Helpdesk extends MY_Controller
{
    function helpdesk()
    {
        $logged_in = $this->session->userdata('logged_in');
        if (!isset($logged_in) || $logged_in != TRUE) {
            redirect('login/', 'refresh');
        }
        set_time_limit(0);
        $this->load->library(array('table', 'template', 'Excel_generator', 'form_validation', 'email'));
        $this->load->helper('url');
        $this->load->helper('csv');
        $this->load->model('M_menu');
        $this->load->model('M_helpdesk');
        $this->load->model('model_sales_omzet');
        $this->load->database();
    }

    function index()
    {
        $id = $this->session->userdata('id');
        $this->form_helpdesk();
    }

    function get_ImagebyID()
    {
        $ID = $_GET['id'];
        // $data['edit']   = 'aas';
        $data['image']   = $this->M_helpdesk->get_image_by_ID($ID);
        echo json_encode($data);
    }

    public function form_helpdesk()
    {
        $data = [
            'id' => $this->session->userdata('id'),
            'title' => 'Helpdesk',
            'get_label' => $this->M_menu->get_label(),
            'site_code' => $this->M_helpdesk->get_sitecode(),
            'history' => $this->M_helpdesk->history_helpdesk()
        ];
        // var_dump($data);die;

        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar', $data);
        $this->load->view('helpdesk/form_helpdesk', $data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
    }

    public function add_helpdesk()
    {
        $site_code = $this->input->post('site_code');
        $name = $this->input->post('name');
        $time = date('his');
        if (!is_dir('./assets/uploads/helpdesk/')) {
        @mkdir('./assets/uploads/helpdesk/', 0777);
        }

        //konfigurasi upload
        $config['upload_path'] = './assets/uploads/helpdesk/';
        $config['allowed_types'] = '*';
        $config['max_size'] = '*';
        $config['encrypt_name'] = FALSE;
        $config['file_name'] = $site_code.$name.'_'.$time;

        $this->load->library('upload', $config);
        if (!$this->upload->do_upload('userfile')) {
            echo '<script>alert("File tidak sesuai. Pastikan File nya sudah benar. Jika perlu bantuan silahkan hubungi IT !");</script>';
            // redirect('all_upload/', 'refresh');
            var_dump($this->upload->display_errors());
        } else {

            $data = array('upload_data' => $this->upload->data());
            $file = $data['upload_data']['full_path'];
            chmod($file, 0777);

            //mengambil
            $upload_data = $this->upload->data();
            $filename1 = $upload_data['file_name'];
            // $proses = $this->db->insert('db_temp.t_temp_file_sds', $file);
        }

        if (!$this->upload->do_upload('userfile2')) {
            $filename2 = '';
        } else {

            $data = array('upload_data' => $this->upload->data());
            $file = $data['upload_data']['full_path'];
            chmod($file, 0777);

            //mengambil
            $upload_data = $this->upload->data();
            $filename2 = $upload_data['file_name'];
            // $proses = $this->db->insert('db_temp.t_temp_file_sds', $file);
        }

        if (!$this->upload->do_upload('userfile3')) {
            $filename3 = '';
        } else {

            $data = array('upload_data' => $this->upload->data());
            $file = $data['upload_data']['full_path'];
            chmod($file, 0777);

            //mengambil
            $upload_data = $this->upload->data();
            $filename3 = $upload_data['file_name'];
            // $proses = $this->db->insert('db_temp.t_temp_file_sds', $file);
        }

        if (!$this->upload->do_upload('video')) {
            $video = '';
        } else {

            $data = array('upload_data' => $this->upload->data());
            $file = $data['upload_data']['full_path'];
            chmod($file, 0777);

            //mengambil
            $upload_data = $this->upload->data();
            $video = $upload_data['file_name'];
            // $proses = $this->db->insert('db_temp.t_temp_file_sds', $file);
        }

        $post = [
            'site_code' => $site_code,
            'subbranch' => $this->input->post('subbranch'),
            'company' => $this->input->post('company'),
            'name' => $name,
            'telp' => $this->input->post('telp'),
            'email' => $this->input->post('email'),
            'deskripsi' => $this->input->post('deskripsi'),
            'filename1' => $filename1,
            'filename2' => $filename2,
            'filename3' => $filename3,
            'video'     => $video,
            'created_date' => $this->model_sales_omzet->timezone2()
        ];

        $kategori = $this->input->post('masalah');
        if ($kategori == 'lain') {
            $post['masalah'] = $this->input->post('custom');
        } else {
            $post['masalah'] = $this->input->post('masalah');
        }

        // var_dump($post);die;
        $this->M_helpdesk->add_helpdesk($post);
    }

    public function proses_helpdesk()
    {
        $id_tiket = $this->uri->segment('3');
        // var_dump($id_tiket);die;
        $data['status'] = '1';
        $data['last_updated'] = $this->model_sales_omzet->timezone2();

        $this->db->where('md5(id_tiket)', $id_tiket);
        $this->db->update('db_temp.t_temp_helpdesk', $data);

        redirect("helpdesk/email_proses/$id_tiket");
    }

    public function success_helpdesk()
    {
        $id_tiket = $this->uri->segment('3');
        $data['status'] = '2';
        $data['last_updated'] = $this->model_sales_omzet->timezone2();
        $this->db->where('md5(id_tiket)', $id_tiket);
        $this->db->update('db_temp.t_temp_helpdesk', $data);

        redirect("helpdesk/email_closed/$id_tiket");
    }

    public function note_helpdesk()
    {

        $id_tiket = $this->uri->segment('3');
        $val = $this->db->query("select * from db_temp.t_temp_helpdesk where md5(id_tiket) = '$id_tiket'");
        // var_dump($val);die;
        if($val->num_rows() == 0){      
            redirect('helpdesk','refresh');
        }
        $data = [
            'id'          => $this->session->userdata('id'),
            'id_tiket' => $id_tiket,
            'title'       => 'Helpdesk',
            'get_label'   => $this->M_menu->get_label(),
            'note'   => $this->M_helpdesk->get_note_helpdesk($id_tiket)
        ];
        // var_dump($data);die;

        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar', $data);
        $this->load->view('helpdesk/note_helpdesk', $data);
        $this->load->view('template_claim/footer');
    }

    public function add_note_helpdesk()
    {

        if (!is_dir('./assets/uploads/helpdesk/')) {
        @mkdir('./assets/uploads/helpdesk/', 0777);
        }

        $id_tiket = $this->input->post('id_tiket');
        $time = date('his');

        //konfigurasi upload zip
        $config['upload_path'] = './assets/uploads/helpdesk/';
        $config['allowed_types'] = '*';
        $config['max_size'] = '*';
        $config['encrypt_name'] = FALSE;
        $config['file_name'] = $id_tiket.'_'.$time;

        $this->load->library('upload', $config);
        if (!$this->upload->do_upload('userfile')) {
            $filename1 = '';
        } else {
            $data = array('upload_data' => $this->upload->data());
            $file = $data['upload_data']['full_path'];
            chmod($file, 0777);

            //mengambil
            $upload_data = $this->upload->data();
            $filename1 = $upload_data['file_name'];
            // $proses = $this->db->insert('db_temp.t_temp_file_sds', $file);
        }

        if (!$this->upload->do_upload('userfile2')) {
            $filename2 = '';
        } else {

            $data = array('upload_data' => $this->upload->data());
            $file = $data['upload_data']['full_path'];
            chmod($file, 0777);

            //mengambil
            $upload_data = $this->upload->data();
            $filename2 = $upload_data['file_name'];
            // $proses = $this->db->insert('db_temp.t_temp_file_sds', $file);
        }

        if (!$this->upload->do_upload('userfile3')) {
            $filename3 = '';
        } else {

            $data = array('upload_data' => $this->upload->data());
            $file = $data['upload_data']['full_path'];
            chmod($file, 0777);

            //mengambil
            $upload_data = $this->upload->data();
            $filename3 = $upload_data['file_name'];
            // $proses = $this->db->insert('db_temp.t_temp_file_sds', $file);
        }

        if (!$this->upload->do_upload('video')) {
            $video = '';
        } else {

            $data = array('upload_data' => $this->upload->data());
            $file = $data['upload_data']['full_path'];
            chmod($file, 0777);

            //mengambil
            $upload_data = $this->upload->data();
            $video = $upload_data['file_name'];
            // $proses = $this->db->insert('db_temp.t_temp_file_sds', $file);
        }

        $post = [
            'id_tiket' => $id_tiket,
            'pesan' => $this->input->post('pesan'),
            'filename1' => $filename1,
            'filename2' => $filename2,
            'filename3' => $filename3,
            'video' => $video,
            'created_date' => $this->model_sales_omzet->timezone2()
        ];

        // var_dump($post);die;
        $this->M_helpdesk->add_note_helpdesk($post);
    }

    public function email(){
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

        $this->email->initialize($config);
    }

    public function email_helpdesk($tiket_email){

        // var_dump($tiket);die;
        $get_detail = $this->M_helpdesk->get_helpdesk_by_tiket($tiket_email)->result();
        // var_dump($get_detail);die;
        foreach ($get_detail as $key) {
            $tiket = $key->id_tiket;
            $kode = $key->site_code;
            $name = $key->name;
            $email = $key->email;
            $telp = $key->telp;
            $kategori = $key->id_kategori;
            $note = $key->note;
            $filename1 = $key->filename1;
            $filename2 = $key->filename2;
            $filename3 = $key->filename3;
        }
        $get_email_dp = $this->M_helpdesk->get_email_dp(substr($kode,0,3));
        foreach ($get_email_dp as $key) {
            $email_dp = $key->email;
        }
        
        $data['tiket'] = $tiket;
        $data['name'] = $name;
        $data['telp'] = $telp;
        $data['kategori'] = $kategori;
        $data['note'] = $note;
        $data['filename1'] = $filename1;
        $data['filename2'] = $filename2;
        $data['filename3'] = $filename3;
        
        // var_dump($data);die;

        $from = "linda@muliaputramandiri.com";
        // $to = "suffy.mpm@gmail.com";
        $to = $email.','.$email_dp; //setelah rilis
        // $to = "ilham@muliaputramandiri.com";
        $data['to'] = $to;
        // var_dump($data['to']);die;
        $cc = "suffy.yanuar@gmail.com,ilham@muliaputramandiri.com,fakhrul@muliaputramandiri.com,linda@muliaputramandiri.com,tria@muliaputramandiri.com";
        $subject = "MPM Site|Helpdesk - $tiket";

        $message = $this->load->view("helpdesk/email_helpdesk",$data,TRUE);
        $this->email();
        $this->email->from($from,'PT. Mulia Putra Mandiri');
        $this->email->to($to);
        $this->email->cc($cc);
        $this->email->reply_to('linda@muliaputramandiri.com');
        $this->email->subject($subject);
        $this->email->message($message);
        // $this->email->attach('assets/file/request/'.$id.'.csv');
        $send = $this->email->send();
        // echo $this->email->print_debugger();
        if ($send) {
            echo "<script>alert('pengiriman email berhasil'); </script>";
            redirect('helpdesk','refresh');
        }else{
            echo "<script>alert('pengiriman email gagal'); </script>";
            redirect('helpdesk','refresh');
        }
    }

    public function email_reminder($id_tiket)
    {
        $get_detail = $this->M_helpdesk->get_helpdesk_by_tiket($id_tiket)->result();

        foreach ($get_detail as $key) {
            $tiket = $key->id_tiket;
        }

        $data['tiket'] = $tiket;

        $from = "ilham@muliaputramandiri.com";
        // $to = "suffy.mpm@gmail.com";
        $to = "linda@muliaputramandiri.com,tria@muliaputramandiri.com"; //setelah rilis
        // $to = "ilham@muliaputramandiri.com";
        $data['to'] = $to;
        // var_dump($data['to']);die;
        $cc = "suffy.yanuar@gmail.com,ilham@muliaputramandiri.com,fakhrul@muliaputramandiri.com";
        $subject = "MPM Site|Helpdesk - Reminder";

        $message = $this->load->view("helpdesk/email_reminder",$data,TRUE);
        $this->email();
        $this->email->from($from,'PT. Mulia Putra Mandiri');
        $this->email->to($to);
        $this->email->cc($cc);
        $this->email->subject($subject);
        $this->email->message($message);
        // $this->email->attach('assets/file/request/'.$id.'.csv');
        $send = $this->email->send();
        // echo $this->email->print_debugger();
        if ($send) {
            echo "<script>alert('pengiriman email berhasil'); </script>";
            redirect('helpdesk','refresh');
        }else{
            echo "<script>alert('pengiriman email gagal'); </script>";
            redirect('helpdesk','refresh');
        }
    }

    public function email_proses($id_tiket)
    {
        $get_detail = $this->M_helpdesk->get_helpdesk_by_tiket($id_tiket)->result();

        foreach ($get_detail as $key) {
            $tiket = $key->id_tiket;
            $kode = $key->site_code;
            $email = $key->email;
        }
        $get_email_dp = $this->M_helpdesk->get_email_dp(substr($kode,0,3));
        foreach ($get_email_dp as $key) {
            $email_dp = $key->email;
        }
        
        $data['tiket'] = $tiket;

        $from = "linda@muliaputramandiri.com";
        // $to = "suffy.mpm@gmail.com";
        $to = $email.','.$email_dp ; //setelah rilis
        // $to = "ilham@muliaputramandiri.com";
        $data['to'] = $to;
        // var_dump($data['to']);die;
        $cc = "linda@muliaputramandiri.com,tria@muliaputramandiri.com,suffy.yanuar@gmail.com,ilham@muliaputramandiri.com,fakhrul@muliaputramandiri.com";
        $subject = "MPM Site|Helpdesk - Reminder";

        $message = $this->load->view("helpdesk/email_proses",$data,TRUE);
        $this->email();
        $this->email->from($from,'PT. Mulia Putra Mandiri');
        $this->email->to($to);
        $this->email->cc($cc);
        $this->email->subject($subject);
        $this->email->message($message);
        // $this->email->attach('assets/file/request/'.$id.'.csv');
        $send = $this->email->send();
        // echo $this->email->print_debugger();
        if ($send) {
            echo "<script>alert('pengiriman email berhasil'); </script>";
            redirect('helpdesk','refresh');
        }else{
            echo "<script>alert('pengiriman email gagal'); </script>";
            redirect('helpdesk','refresh');
        }
    }

    public function email_closed($id_tiket)
    {
        $get_detail = $this->M_helpdesk->get_helpdesk_by_tiket($id_tiket)->result();

        foreach ($get_detail as $key) {
            $tiket = $key->id_tiket;
            $kode = $key->site_code;
            $email = $key->email;
        }
        $get_email_dp = $this->M_helpdesk->get_email_dp(substr($kode,0,3));
        foreach ($get_email_dp as $key) {
            $email_dp = $key->email;
        }
        
        $data['tiket'] = $tiket;

        $from = "linda@muliaputramandiri.com";
        // $to = "suffy.mpm@gmail.com";
        $to = $email.','.$email_dp ; //setelah rilis
        // $to = "ilham@muliaputramandiri.com";
        $data['to'] = $to;
        // var_dump($data['to']);die;
        $cc = "linda@muliaputramandiri.com,tria@muliaputramandiri.com,suffy.yanuar@gmail.com,ilham@muliaputramandiri.com,fakhrul@muliaputramandiri.com";
        $subject = "MPM Site|Helpdesk - Reminder";

        $message = $this->load->view("helpdesk/email_closed",$data,TRUE);
        $this->email();
        $this->email->from($from,'PT. Mulia Putra Mandiri');
        $this->email->to($to);
        $this->email->cc($cc);
        $this->email->subject($subject);
        $this->email->message($message);
        // $this->email->attach('assets/file/request/'.$id.'.csv');
        $send = $this->email->send();
        // echo $this->email->print_debugger();
        if ($send) {
            echo "<script>alert('pengiriman email berhasil'); </script>";
            redirect('helpdesk','refresh');
        }else{
            echo "<script>alert('pengiriman email gagal'); </script>";
            redirect('helpdesk','refresh');
        }
    }
}