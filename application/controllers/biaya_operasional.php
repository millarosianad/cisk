<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Biaya_operasional extends MY_Controller
{
    function Biaya_operasional()
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
        $this->load->model('M_menu');
        $this->load->model(array('M_menu','model_biaya','model_outlet_transaksi','model_sales_omzet', 'model_asset'));
        $this->load->database();

    }

    function index()
    {
        $this->history();
    }

    public function get_data()
    {
        $hak_akses = $this->model_biaya->hak_akses($this->session->userdata('id'));
        $id = $_GET['id'];
        // var_dump($id);die;
        $data['get_history']   = $this->model_biaya->get_history($id, $hak_akses)->row();
        echo json_encode($data);
    }

    public function history()
    {
        $hak_akses = $this->model_biaya->hak_akses($this->session->userdata('id'));

        $data = [
            'id' => $this->session->userdata('id'),
            'url' => 'biaya_operasional/proses_pengajuan/',
            'title' => 'History Pengajuan Biaya Operasional',
            'get_label' => $this->M_menu->get_label(),
            'get_history' => $this->model_biaya->get_history('', $hak_akses)->result(),
            'list_user' => $this->model_asset->getUser()->result(),
        ];
        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar',$data);
        $this->load->view('template_claim/top_content',$data);
        $this->load->view('biaya_operasional/history',$data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
    }

    public function proses_pengajuan(){
        $id = $this->input->post('id');
        $userid = $this->session->userdata('id');
        $tanggal_transaksi = $this->input->post('tanggal_transaksi');
        $km_akhir = $this->input->post('km_akhir');
        $liter = $this->input->post('liter');
        $biaya = $this->input->post('biaya');
        $kategori = $this->input->post('kategori');
        $tanggal_transaksi = $this->input->post('tanggal_transaksi');
        $created_at = $this->model_outlet_transaksi->timezone();
        
        $jml_data = count($tanggal_transaksi);
        for ($i=0; $i < $jml_data; $i++) {

            if(!empty($_FILES['foto_km']['name'][$i])){
    
                $_FILES['file']['name'] = $_FILES['foto_km']['name'][$i];
                $_FILES['file']['type'] = $_FILES['foto_km']['type'][$i];
                $_FILES['file']['tmp_name'] = $_FILES['foto_km']['tmp_name'][$i];
                $_FILES['file']['error'] = $_FILES['foto_km']['error'][$i];
                $_FILES['file']['size'] = $_FILES['foto_km']['size'][$i];
        
                $config['upload_path'] = './assets/file/biaya_operasional/';
                $config['allowed_types'] = 'jpg|jpeg|png|gif';
                $config['max_size'] = '5000';
                $config['overwrite'] = false;
                $config['file_name'] = $_FILES['foto_km']['name'][$i];
        
                $this->load->library('upload',$config); 
        
                if($this->upload->do_upload('file')){
                $uploadData = $this->upload->data();
                $filename_km = $uploadData['file_name'];
                }
            }

            if(!empty($_FILES['foto_struk']['name'][$i])){
    
                $_FILES['file']['name'] = $_FILES['foto_struk']['name'][$i];
                $_FILES['file']['type'] = $_FILES['foto_struk']['type'][$i];
                $_FILES['file']['tmp_name'] = $_FILES['foto_struk']['tmp_name'][$i];
                $_FILES['file']['error'] = $_FILES['foto_struk']['error'][$i];
                $_FILES['file']['size'] = $_FILES['foto_struk']['size'][$i];
        
                $config['upload_path'] = './assets/file/biaya_operasional/';
                $config['allowed_types'] = 'jpg|jpeg|png|gif';
                $config['max_size'] = '5000';
                $config['overwrite'] = false;
                $config['file_name'] = $_FILES['foto_struk']['name'][$i];
        
                $this->load->library('upload',$config); 
        
                if($this->upload->do_upload('file')){
                $uploadData = $this->upload->data();
                $filename_struk = $uploadData['file_name'];
                }
            }
            
            if ($id == '') {
                $data = [
                    "kode" => $this->model_biaya->generate($kategori, $userid, $tanggal_transaksi[$i]),
                    "kategori" => $kategori,
                    "tanggal_transaksi" => $tanggal_transaksi[$i],
                    "km_akhir" => $km_akhir[$i],
                    "liter" => $liter[$i],
                    "biaya" => $biaya[$i],
                    "foto_km" => $filename_km,
                    "foto_struk" => $filename_struk,
                    "signature" => md5(str_replace('-','',$created_at)). "-".md5($kategori.$tanggal_transaksi[$i].$userid),
                    "created_at" => $created_at,
                    "created_by" => $userid,
                ];

                $id_detail = $this->model_biaya->insert($data);
                $total_biaya = $this->model_biaya->total_biaya($id_detail);
            } else {
                $data['id'] = $id;
                $this->db->select('*');
                $this->db->where('id_header', $id);
                $proses =  $this->db->get('site.t_biaya_operasional_detail')->row();
                $id_detail = $proses->id;
                
                $data = [
                    "kategori" => $kategori,
                    "tanggal_transaksi" => $tanggal_transaksi[$i],
                    "km_akhir" => $km_akhir[$i],
                    "liter" => $liter[$i],
                    "biaya" => $biaya[$i],
                    "updated_at" => $created_at,
                    "updated_by" => $userid,
                ];

                if ($filename_km == '') {
                    $data['foto_km'] = $proses->foto_km;
                }else {
                    $data['foto_km'] = $filename_km;
                }
    
                if ($filename_struk == '') {
                    $data['foto_struk'] = $proses->foto_struk;
                }else {
                    $data['foto_struk'] = $filename_struk;
                }
    
                $proses = $this->model_biaya->edit($data);
                $total_biaya = $this->model_biaya->total_biaya($id_detail);
            }
        }

        redirect('biaya_operasional');
    }

    public function report(){
        
        $submit = $this->input->post('submit');
        
        if ($submit == 2) {
            $this->report_pdf();
        }

        $userid = $this->input->post('user');
        $data = [
            'userid' => $this->input->post('user'),
            'from' => $this->input->post('from'),
            'to' => $this->input->post('to')
        ];

        if ($userid == '0') {
            $userid = $this->session->userdata('id');
        }else{
            $userid = "$userid";
        }

        $this->db->select('*');
        $this->db->where('id', $userid);
        $data_user = $this->db->get('mpm.user')->row();

        $username = $data_user->username;
        
        $hsl = $this->model_biaya->get_report_by_periode($data);
        query_to_csv($hsl,TRUE,"report_biaya_operasional - $username.csv");
    }

    public function report_pdf(){
        $data = [
            'userid' => $this->input->post('user'),
            'from' => $this->input->post('from'),
            'to' => $this->input->post('to')
        ];

        $this->db->select('*');
        $this->db->where('id', $this->input->post('user'));
        $data_user = $this->db->get('mpm.user')->row();
        
        $username = $data_user->username;

        $proses = [
            'username' => $username,
            'detail' => $this->model_biaya->get_report_by_periode($data)->result(),
            'total_reimburse' => $this->model_biaya->get_total_reimburse_periode($data)->row(),
            'total_biaya' => $this->model_biaya->get_total_biaya_periode($data)->row(),
            'from' => $this->input->post('from'),
            'to' => $this->input->post('to')
        ];
        
        $this->load->library('mypdf');
        $generate_pdf = $this->mypdf->generate('biaya_operasional/template_pdf',$proses,"report_biaya_operasional - $username",'A4','portrait');
    }
    
    public function reimburse(){
        
        $data = [
            'userid' => $this->session->userdata('id'),
            'from' => $this->input->post('from'),
            'to' => $this->input->post('to'),
            'url' => 'biaya_operasional/proses_reimburse/',
            'title' => 'Generate Report',
            'get_label' => $this->M_menu->get_label(),
            'list_user' => $this->model_asset->getUser()->result(),
        ];
        $data['data_reimburse'] = $this->model_biaya->get_history_by_periode($data)->result();

        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar',$data);
        $this->load->view('template_claim/top_content',$data);
        $this->load->view('biaya_operasional/reimburse',$data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
    }

    public function proses_reimburse()
    {
        $data = $this->input->post('options');
        // var_dump($id);die;
        $proses = $this->model_biaya->update_reimburse($data);
        // echo "proses : ".$proses;
        // redirect('biaya_operasional/history','refresh');

        if ($proses) {
            $this->report_pdf_reimburse($data);
        }

    }
    
    public function delete($signature, $id_header)
    {
        
        $data = [
            'signature' => $signature,
            'id_header' => $id_header,
        ];
        
        $this->model_biaya->delete_biaya_operasional($data);
        redirect('biaya_operasional/history','refresh');
    }

    public function report_pdf_reimburse($data){
        $this->db->select('*');
        $this->db->where('id', $this->session->userdata('id'));
        $data_user = $this->db->get('mpm.user')->row();
        
        $username = $data_user->username;

        $proses = [
            'username' => $username,
            'detail' => $this->model_biaya->get_report_reimburse($data)->result(),
            'total_reimburse' => $this->model_biaya->get_report_total_reimburse($data)->row(),
            'from' => $this->input->post('from'),
            'to' => $this->input->post('to')
        ];

        // var_dump($proses);die;
        
        $this->load->library('mypdf');
        $generate_pdf = $this->mypdf->generate('biaya_operasional/template_pdf_reimburse',$proses,"report_biaya_operasional - $username",'A4','portrait');
    }

}
?>