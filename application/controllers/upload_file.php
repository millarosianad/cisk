<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');
class Upload_file extends MY_Controller
{
    function upload_file()
    {
        $logged_in = $this->session->userdata('logged_in');
        if (!isset($logged_in) || $logged_in != TRUE) {
            redirect('login/', 'refresh');
        }
        set_time_limit(0);
        $this->load->library(array('table', 'template', 'Excel_generator', 'form_validation'));
        $this->load->helper('url', 'form');
        $this->load->model('model_upload_file');
        $this->load->model('model_sales_omzet');
        $this->load->model('M_menu');
        $this->load->database();
    }

    function index()
    {
        // $message = "Sedang ada maintenance di menu ini. Silahkan coba beberapa saat lagi atau hubungi IT !";
        // echo "<script type='text/javascript'>alert('$message');
        //     window.location.href = 'login/home';
        //     </script>";
        $id = $this->session->userdata('id');

        // proses check upload
        $check = $this->db->query("select * from mpm.upload where userid = $id order by id desc limit 1")->row_array();
        $id_upload = $check['id'];
        $filename = $check['filename'];
        $nocab = substr($check['filename'], 2, 2);
        $tahun = $check['tahun'];
        $bulan = $check['bulan'];
        $tanggal = $check['tanggal'];
        $flag_check = $check['flag_check'];
        $status_closing = $check['status_closing'];
        // var_dump($id_upload);die;
        if ($flag_check == 2) {
            redirect("upload_file/proses_extract_zip/$filename/$nocab/$tahun/$bulan/$tanggal/$status_closing");
        } elseif ($flag_check == 4) {
            redirect("upload_file/alert_success/$id_upload");
        } else {
            $this->view_upload();
        }
    }

    public function info_upload()
    {
        $this->load->view('info_upload');
    }

    public function view_upload()
    {
        $this->load->library('form_validation');
        $logged_in = $this->session->userdata('logged_in');
        if (!isset($logged_in) || $logged_in != TRUE) {
            redirect('login/', 'refresh');
        }
        $data['id'] = $this->session->userdata('id');
        $data['query'] = $this->model_upload_file->cek_upload_terakhir($data);
        $data['get_label'] = $this->M_menu->get_label();
        $data['title'] = 'Data Upload';

        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar', $data);
        $this->load->view('template_claim/top_content', $data);
        $this->load->view('upload_file/upload_form_view', $data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
    }

    public function file_upload()
    {
        // echo "magelang";
        $logged_in = $this->session->userdata('logged_in');
        if (!isset($logged_in) || $logged_in != TRUE) {
            redirect('login/', 'refresh');
        }

        if (!is_dir('./assets/uploads/zip/' . date('Ym') . '/')) {
            @mkdir('./assets/uploads/zip/' . date('Ym') . '/', 0777);
        }

        //konfigurasi upload zip
        $config['upload_path'] = './assets/uploads/zip/' . date('Ym') . '';
        $config['allowed_types'] = 'zip|ZIP|xls|csv|xlsx';
        $config['max_size'] = '*';
        $config['overwrite'] = 'TRUE';

        $this->load->library('upload', $config);
        if (!$this->upload->do_upload()) {
            var_dump($this->upload->display_errors());
            die;
            // echo '<script>alert("File tidak sesuai. Pastikan File nya sudah benar. Jika perlu bantuan silahkan hubungi IT !");</script>';
            // redirect('all_upload/', 'refresh');
        } else {

            // die;
            // mengambil data file upload
            $data = array('upload_data' => $this->upload->data());
            $zip = new ZipArchive;
            $file = $data['upload_data']['full_path'];
            chmod($file, 0777);

            $upload_data = $this->upload->data();
            $filename = $upload_data['orig_name'];
            $nocab = substr($upload_data['orig_name'], 2, 2);
            $tanggal = substr($upload_data['orig_name'], 6, 2);
            $year  = $this->input->post('year');
            $month = substr($upload_data['orig_name'], 4, 2);
            $status_closing  = $this->input->post('status_closing');

            // echo strlen($filename);
            // die;

            // proses cek kesesuaian file upload
            if (strlen($filename) >= '13') {
                echo '<script>alert("File tidak sesuai. Pastikan File nya sudah benar. Jika perlu bantuan silahkan hubungi IT !");</script>';
                // die;
                redirect('upload_file/', 'refresh');
            } else {
                // echo "aa";
                // die;
                $data['upload_data'] = $upload_data;
                $data['filename'] = $filename;
                $data['nocab'] = $nocab;
                $data['tanggal'] = $tanggal;
                $data['year'] = $year;
                $data['month'] = $month;
                $data['status_closing'] = $status_closing;
                $data['query'] = $this->cek_kesesuaian_upload($data);
            }
        }
    }

    public function cek_kesesuaian_upload($data)
    {

        //    echo "aaa";
        //    die;
        $data['id'] = $this->session->userdata('id');
        $data['query'] = $this->model_upload_file->cek_upload_terakhir($data);
        $data['cek'] = $this->model_upload_file->cek_kesesuaian_upload($data);
    }

    public function proses_extract_zip()
    {
        // echo "<br><br><br><br>aaaaaaaaaaaaa";

        if (function_exists('date_default_timezone_set'))
            date_default_timezone_set('Asia/Jakarta');
        $filename = $this->uri->segment(3);
        $nocab = $this->uri->segment(4);
        $year = $this->uri->segment(5);
        $month = $this->uri->segment(6);
        $tanggal = $this->uri->segment(7);

        // membuat folder
        if (!is_dir('./assets/uploads/zip/' . date('Ym') . '/')) {
            @mkdir('./assets/uploads/zip/' . date('Ym') . '/', 0777);
        }

        $config['upload_path'] = './assets/uploads/zip/' . date('Ym') . '';
        $config['allowed_types'] = array('zip', 'ZIP');
        $config['max_size'] = '';
        $config['overwrite'] = 'TRUE';

        $this->load->library('upload', $config);
        $zip = new ZipArchive;
        $file = "C:/xampp/htdocs/cisk/assets/uploads/zip/" . date('Ym') . "/" . $filename;
        //echo $file;

        $openZip = $zip->open($file);

        if ($openZip === TRUE) {
            if ($zip->setPassword("DELTOMED")) {
                if (!$zip->extractTo('./assets/uploads/unzip/' . $nocab . '')) {

                    echo "Extraction failed (wrong password?)";
                } else {
                    $pesan_extract = "Extraction Berhasil";
                }
            }

            $zip->close();
        } else {
            die("Failed opening archive: " . @$zip->getStatusString() . " (code: " . $zip_status . ")");
        }

        $data['get_label'] = $this->M_menu->get_label();
        $data['title'] = 'Data Upload';
        $data['nocab'] = $nocab;
        $data['tahun'] = $year;
        $data['bulan'] = $month;
        $data['tanggal'] = $tanggal;
        $data['pesan'] = $pesan_extract;
        $data['filenamezip'] = $filename;
        $data['lastupload'] = $this->model_sales_omzet->timezone2();

        // proses insert mpm upload
        $upl['userid'] = $this->session->userdata('id');
        $upl['lastupload'] = $this->model_sales_omzet->timezone2();
        $upl['filename'] = $filename;
        $upl['bulan'] = $month;
        $upl['tahun'] = $year;
        $upl['status'] = '0';
        $upl['tanggal'] = $tanggal;
        $upl['flag_check'] = '1';
        $proses = $this->db->insert('mpm.upload', $upl);
        if ($proses) {

            // echo "Aaaa";
            // die;

            /*
            if($nocab == '95'){
                $query= $this->model_proses_data->proses_data_jbr($data);
                $data['omzet'] = $query['omzet'];
                $data['id'] = $query['id'];
            }
            else{*/
            $query = $this->model_upload_file->proses_data($data);
            $data['omzet'] = $query['omzet'];
            $data['id'] = $query['id'];
            //}

        } else {
            echo "insert ke mpm.upload gagal";
        }

        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar', $data);
        $this->load->view('template_claim/top_content', $data);
        $this->load->view('upload_file/upload_diproses', $data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
    }

    public function prosesOmzet()
    {

        $logged_in = $this->session->userdata('logged_in');
        if (!isset($logged_in) || $logged_in != TRUE) {
            redirect('login/', 'refresh');
        }

        $data['nocab'] = $this->uri->segment('3');
        $data['tahun'] = $this->uri->segment('4');
        $data['bulan'] = $this->uri->segment('5');
        $data['tanggal'] = $this->uri->segment('6');
        $data['id_upload'] = $this->uri->segment('7');
        $data['status_closing']  = $this->input->post('status_closing');
        $data['query'] = $this->model_upload_file->submitOmzet($data);
    }

    public function alert_success()
    {
        
        $data['id_upload'] = $this->uri->segment('3');
        // var_dump($data['id_upload']);die;

        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('upload_file/submitOmzet', $data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
    }

    public function simpanOmzet()
    {
        $id = $this->uri->segment('3');
        // echo($id);die;
        $post['flag_check'] = '0';
        $this->db->where('id', $id);
        $this->db->update('mpm.upload', $post);

        redirect('upload_file');
    }

    public function reset_flag()
    {
        $userid = $this->session->userdata('id');
        $this->db->select('id');
        $this->db->where('userid', $userid);
        $this->db->order_by('id','DESC');
        $upload = $this->db->get('mpm.upload')->row();

        $this->db->set('flag_check','0');
        $this->db->where('id', $upload->id);
        $this->db->update('mpm.upload');

        redirect('upload_file');
    }
}
