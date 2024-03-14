<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class File_download extends MY_Controller
{
    function file_download()
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
        $this->load->model('model_file_download');
        $this->load->model('model_master_data');
        $this->load->database();
    }

    public function get_versifile()
    {
        $logged_in = $this->session->userdata('logged_in');
        if (!isset($logged_in) || $logged_in != TRUE) {
            redirect('login/', 'refresh');
        }
        set_time_limit(0);

        $versiID = $_GET['id'];
        $data['versi']   = $this->model_file_download->get_versi_by_ID($versiID);
        echo json_encode($data);
    }

    public function download()
    {
        $data = [
            'id' => $this->session->userdata('id'),
            'url' => 'file_download/download',
            'title' => 'File Download',
            'tombol' => $this->input->post('tombol'),
            'get_label' => $this->M_menu->get_label(),
            's_code' => $this->model_master_data->site_code()
        ];

        $data['proses'] = $this->model_file_download->versi_file($data);
        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar', $data);
        $this->load->view('template_claim/top_content', $data);
        $this->load->view('file_download/file_download', $data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
    }

    public function  upload_file()
    {
        date_default_timezone_set('Asia/Jakarta');
        $date = date_create(date('Y-m-d H:i:s'));
        date_add($date, date_interval_create_from_date_string('-7 hours'));
        $tgl = date_format($date, 'Y-m-d H:i:s');

        $logged_in = $this->session->userdata('logged_in');
        if (!isset($logged_in) || $logged_in != TRUE) {
            redirect('login/', 'refresh');
        }

        if (!is_dir('./assets/uploads/sds/')) {
            @mkdir('./assets/uploads/sds/', 0777);
        }

        //konfigurasi upload zip
        $config['upload_path'] = './assets/uploads/sds/';
        $config['allowed_types'] = '*';
        $config['max_size'] = '*';
        $config['overwrite'] = 'TRUE';

        $this->load->library('upload', $config);
        if (!$this->upload->do_upload()) {
            echo '<script>alert("File tidak sesuai. Pastikan File nya sudah benar. Jika perlu bantuan silahkan hubungi IT !");</script>';
            // redirect('all_upload/', 'refresh');
            var_dump($this->upload->display_errors());
        } else {

            $data = array('upload_data' => $this->upload->data());
            $zip = new ZipArchive;
            $file = $data['upload_data']['full_path'];
            chmod($file, 0777);

            //mengambil
            $upload_data = $this->upload->data();
            $orig_name = $upload_data['orig_name'];
            $post['filename'] = $upload_data['orig_name'];
            $post['link'] = "http://site.muliaputramandiri.com/assets/uploads/sds/$orig_name";
            // $proses = $this->db->insert('db_temp.t_temp_file_sds', $file);
        }

        $post['versi'] = $this->input->post('versi');
        $post['link_gdrive'] = $this->input->post('link_gdrive');
        $post['status'] = '1';
        $post['created_date'] = $tgl;

        $site_code = $this->input->post('site_code');
        if ($site_code == 5) {
            $post['kode_comp'] = $this->input->post('branch');
            $proses = $this->db->insert('db_temp.t_temp_file', $post);
        } else {
            $site_codex = $this->model_file_download->get_site_code($site_code);
            foreach ($site_codex as $x) {
                // echo $x->kode;
                $post['kode_comp'] = $x->kode;
                $proses = $this->db->insert('db_temp.t_temp_file', $post);
            }
        }
        redirect('file_download/download');
        // var_dump($post);die;
    }

    public function add_versi()
    {
        date_default_timezone_set('Asia/Jakarta');
        $date = date_create(date('Y-m-d H:i:s'));
        date_add($date, date_interval_create_from_date_string('-7 hours'));
        $tgl = date_format($date, 'Y-m-d H:i:s');

        $logged_in = $this->session->userdata('logged_in');
        if (!isset($logged_in) || $logged_in != TRUE) {
            redirect('login/', 'refresh');
        }

        $post['versi'] = $this->input->post('versi');
        $post['tanggal'] = $this->input->post('tanggal');
        $post['status'] = $this->input->post('status');
        $post['created_date'] = "$tgl";

        $proses = $this->db->insert('db_temp.t_temp_file_versi', $post);
        redirect('file_download/download');
    }

    public function aktiv_versi()
    {
        $logged_in = $this->session->userdata('logged_in');
        if (!isset($logged_in) || $logged_in != TRUE) {
            redirect('login/', 'refresh');
        }

        $id = $this->uri->segment('3');
        $versi = $this->uri->segment('4');
        // var_dump($id);die;
        $post['status'] = '1';
        $this->db->where('id', $id);
        $this->db->update('db_temp.t_temp_file_versi', $post);

        $this->db->where('versi', $versi);
        $this->db->update('db_temp.t_temp_file', $post);
        redirect('file_download/download');
    }

    public function nonaktiv_versi()
    {
        $logged_in = $this->session->userdata('logged_in');
        if (!isset($logged_in) || $logged_in != TRUE) {
            redirect('login/', 'refresh');
        }

        $id = $this->uri->segment('3');
        $versi = $this->uri->segment('4');
        // var_dump($id);die;
        $post['status'] = '0';
        $this->db->where('id', $id);
        $this->db->update('db_temp.t_temp_file_versi', $post);

        $this->db->where('versi', $versi);
        $this->db->update('db_temp.t_temp_file', $post);
        redirect('file_download/download');
    }

    public function detail_versi()
    {

        $logged_in = $this->session->userdata('logged_in');
        if (!isset($logged_in) || $logged_in != TRUE) {
            redirect('login/', 'refresh');
        }

        $data = [
            'id' => $this->session->userdata('id'),
            'title' => 'Detail',
            'tombol' => $this->input->post('tombol'),
            'get_label' => $this->M_menu->get_label(),
            'versi' => $this->uri->segment('3')
        ];

        $data['proses'] = $this->model_file_download->detail_file($data);
        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar', $data);
        $this->load->view('template_claim/top_content', $data);
        $this->load->view('file_download/detail_versi', $data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
    }

    public function aktiv_detail()
    {
        $logged_in = $this->session->userdata('logged_in');
        if (!isset($logged_in) || $logged_in != TRUE) {
            redirect('login/', 'refresh');
        }

        $id = $this->uri->segment('3');
        $versi = $this->uri->segment('4');
        // var_dump($id);die;
        $post['status'] = '1';

        $this->db->where('id', $id);
        $this->db->update('db_temp.t_temp_file', $post);
        redirect("file_download/detail_versi/$versi");
    }

    public function nonaktiv_detail()
    {
        $logged_in = $this->session->userdata('logged_in');
        if (!isset($logged_in) || $logged_in != TRUE) {
            redirect('login/', 'refresh');
        }

        $id = $this->uri->segment('3');
        $versi = $this->uri->segment('4');
        // var_dump($id);die;
        $post['status'] = '0';

        $this->db->where('id', $id);
        $this->db->update('db_temp.t_temp_file', $post);
        redirect("file_download/detail_versi/$versi");
    }
}
