<?php

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Management_event extends MY_Controller
{    
    function management_event()
    {       
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login_sistem/','refresh');
        }
        set_time_limit(0);
        $this->load->library(array('table', 'template', 'Excel_generator', 'form_validation', 'email', 'zip'));
        $this->load->helper(array('url', 'csv', 'download'));
        $this->load->model(array('model_outlet_transaksi', 'model_management_event'));
    }
    function index()
    {
        // $this->dashboard();
        if ($this->session->userdata('id') == '749') {
            $this->pelaporan();
        }else {
            redirect('management_office/','refresh');
        }
    }
    function navbar($data){
        if ($this->session->userdata('level') === '4') { // jika dp
            $this->load->view('management_office/top_header_dp', $data);
        }elseif ($this->session->userdata('level') === '3') { // jika principal
            $this->load->view('management_office/top_header_principal', $data);
        }elseif ($this->session->userdata('level') === "3a") { // jika principal tanpa sales 
            $this->load->view('management_office/top_header_principal_nosales', $data);
        }elseif ($this->session->userdata('level') === "3b") { // jika principal hanya raw data, claim, rpd 
            $this->load->view('management_office/top_header_principal_rawdata', $data);
        }elseif ($this->session->userdata('level') === "3c") { // jika principal raw_data dan retur dan rpd = RSPH = ghozali yoseph sudarsono
            $this->load->view('management_office/top_header_principal_rawdata_retur', $data);
        }elseif ($this->session->userdata('level') === "3d") { // jika principal rpd
            $this->load->view('management_office/top_header_principal_rpd', $data);
        }else{
            $this->load->view('management_office/top_header', $data);
        }
    }
    public function pelaporan($from = '', $to = ''){

        $from = $this->input->get('from');
        $to = $this->input->get('to');
        $type = $this->input->get('type');

        // echo "from : ".$from;
        // echo "to : ".$to;

        if ($from == '' || $to == '') {
            $params_from = date('Y-01-01');
            $params_to = date('Y-12-31');
        }else{

            $params_from = $from;
            $params_to = $to;

            if ($type == 2) {
                $this->export($params_from, $params_to);
                die;
            }

        }
        // die;

        $data = [
            'title'                 => 'Pelaporan Event',
            'get_pelaporan_event'   => $this->model_management_event->get_pelaporan_event($params_from, $params_to),
            'url'                   => 'management_event/pelaporan_tambah',
            'url_search'            => 'management_event/pelaporan',
        ];

        $this->navbar($data);
        // $this->load->view('management_office/top_header', $data);
        $this->load->view('kalimantan/header_full_width', $data);
        $this->load->view('management_event/pelaporan', $data);
        $this->load->view('kalimantan/footer');
    }

    public function export($from, $to){
        $query = "
            select a.*, b.*
            from site.management_event_pelaporan a LEFT JOIN (
                select a.id, a.username, a.`name`
                from mpm.user a 
            )b on a.created_by = b.id 
            where a.deleted_at is null and a.event_from between '$from 00:00:00' and '$to 23:59:59'";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        // die;

        $hsl = $this->db->query($query);

        query_to_csv($hsl,TRUE,'Export Event.csv');
    }

    public function pelaporan_tambah(){
        $nama_event = $this->input->post('nama_event');
        $from = $this->input->post('from');
        $to = $this->input->post('to');
        $lokasi_event = $this->input->post('lokasi_event');
        $ref_perdin = $this->input->post('ref_perdin');
        $value_omzet = $this->input->post('value_omzet');

        $created_at = $this->model_outlet_transaksi->timezone();
        $signature = 'Event-' . rand() . md5($this->model_outlet_transaksi->timezone()) . date('Ymd');

        if (!is_dir('./assets/uploads/management_event/')) {
            @mkdir('./assets/uploads/management_event/', 0777);
        }

        $this->load->library('upload'); // Load librari upload        
        $config['upload_path'] = './assets/uploads/management_event/';
        // $config['allowed_types'] = 'xls|xlsx|csv';
        $config['allowed_types'] = '*';    
        $config['max_size']  = '2048000';
        $config['overwrite'] = false;
        $config['encrypt_name'] = false;
        $this->upload->initialize($config);

        if ($this->upload->do_upload('attach1')) 
        {
            $upload_data = $this->upload->data();
            $filename_attach1 = $upload_data['orig_name'];
        }else{
            // var_dump($this->upload->display_errors());
            // die;
            $filename_attach1 = '';
        };

        if ($this->upload->do_upload('attach2')) 
        {
            $upload_data = $this->upload->data();
            $filename_attach2 = $upload_data['orig_name'];
        }else{
            // var_dump($this->upload->display_errors());
            // die;
            $filename_attach2 = '';
        };

        if ($this->upload->do_upload('attach3')) 
        {
            $upload_data = $this->upload->data();
            $filename_attach3 = $upload_data['orig_name'];
        }else{
            // var_dump($this->upload->display_errors());
            // die;
            $filename_attach3 = '';
        };

        // echo "nama_event : ".$nama_event;
        // echo "<br>";
        // echo "tanggal_event : ".$tanggal_event;
        // echo "<br>";
        // echo "lokasi_event : ".$lokasi_event;
        // echo "<br>";
        // echo "ref_perdin : ".$ref_perdin;
        // echo "<br>";
        // echo "value_omzet : ".$value_omzet;
        // echo "<br>";
        // echo "attach1 : ".$filename_attach1;
        // echo "<br>";
        // echo "attach2 : ".$filename_attach2;
        // echo "<br>";
        // echo "attach3 : ".$filename_attach3;

        $no_pelaporan_event = $this->model_management_event->generate($created_at);
        // echo "no_pelaporan_event : ".$no_pelaporan_event;
        // die;

        $data = [
            'no_pelaporan_event'    => $no_pelaporan_event,
            'event_from'            => $from,
            'event_to'              => $to,
            'lokasi_event'          => $lokasi_event,
            'nama_event'            => $nama_event,
            'referensi_rpd'         => $ref_perdin,
            'value_omzet'           => $value_omzet,
            'attach_1'              => $filename_attach1,
            'attach_2'              => $filename_attach2,
            'attach_3'              => $filename_attach3,
            'status'                => 1,
            'nama_status'           => 'PENDING REVIEW',
            'created_by'            => $this->session->userdata('id'),
            'created_at'            => $created_at,
            'signature'             => $signature,
        ];

        $this->db->insert('site.management_event_pelaporan', $data);

        $this->session->set_flashdata("pesan_success", "Penginputan Event Berhasil");
        redirect('management_event/pelaporan', 'refresh');

    }

    public function review($signature){
        $id_pelaporan = $this->model_management_event->get_pelaporan_event($signature)->row()->id;
        $data = [
            'title'                 => 'Pelaporan Event | Review',
            'get_pelaporan_event'   => $this->model_management_event->get_pelaporan_event($signature),
            'get_review'            => $this->model_management_event->get_review($id_pelaporan),
            'url'                   => 'management_event/tambah_review/',
            'signature'             => $signature
        ];

        $this->navbar($data);
        // $this->load->view('management_office/top_header', $data);
        $this->load->view('kalimantan/header_full_width', $data);
        $this->load->view('management_event/review', $data);
        $this->load->view('kalimantan/footer');
    }

    public function tambah_review(){
        $signature_pelaporan = $this->input->post('signature_pelaporan');
        $review = $this->input->post('review');

        $id_pelaporan = $this->model_management_event->get_pelaporan_event($signature_pelaporan)->row()->id;
        $created_at = $this->model_outlet_transaksi->timezone();
        $signature = 'Event-' . rand() . md5($this->model_outlet_transaksi->timezone()) . date('Ymd');

        $data = [
            'id_pelaporan'          => $id_pelaporan,
            'review'                => $review,
            'created_by'            => $this->session->userdata('id'),
            'created_at'            => $created_at,
            'signature'             => $signature,
        ];

        $this->db->insert('site.management_event_review', $data);

        $data_pelaporan = [
            'status'                => 2,
            'nama_status'           => 'REVIEWED',
        ];

        $this->db->where('signature', $signature_pelaporan);
        $this->db->update('site.management_event_pelaporan', $data_pelaporan);


        $this->session->set_flashdata("pesan_success", "Inserting Data Successfully");
        redirect('management_event/review/'.$signature_pelaporan, 'refresh');

    }

    public function pelaporan_delete($signature){

        $created_at = $this->model_outlet_transaksi->timezone();

        $data = [
            'deleted_at'        => $created_at,
            'deleted_by'        => $this->session->userdata('id'),
        ];

        $this->db->where('signature', $signature);
        $this->db->update('site.management_event_pelaporan', $data);

        $this->session->set_flashdata("pesan_success", "Updating Data Successfully");
        redirect('management_event/pelaporan/', 'refresh');

    }

    public function pelaporan_edit($signature){

        $get_pelaporan = $this->model_management_event->get_pelaporan_event($signature);
        if ($get_pelaporan->num_rows() > 0) {
            $no_pelaporan_event     = $get_pelaporan->row()->no_pelaporan_event;
            $event_from             = $get_pelaporan->row()->event_from;
            $event_to               = $get_pelaporan->row()->event_to;
            $lokasi_event           = $get_pelaporan->row()->lokasi_event;
            $nama_event             = $get_pelaporan->row()->nama_event;
            $referensi_rpd          = $get_pelaporan->row()->referensi_rpd;
            $value_omzet            = $get_pelaporan->row()->value_omzet;
            $attach_1               = $get_pelaporan->row()->attach_1;
            $attach_2               = $get_pelaporan->row()->attach_2;
            $attach_3               = $get_pelaporan->row()->attach_3;
            $status                 = $get_pelaporan->row()->status;
            $nama_status            = $get_pelaporan->row()->nama_status;
            $signature_pelaporan    = $get_pelaporan->row()->signature;
        }

        $data = [
            'title'                 => 'Pelaporan Event | Edit',
            'get_pelaporan_event'   => $this->model_management_event->get_pelaporan_event($signature),
            'url'                   => 'management_event/pelaporan_edit_proses',
            'signature'             => $signature_pelaporan,
            'no_pelaporan_event'    => $no_pelaporan_event,
            'event_from'            => $event_from,
            'event_to'              => $event_to,
            'lokasi_event'          => $lokasi_event,
            'nama_event'            => $nama_event,
            'referensi_rpd'         => $referensi_rpd,
            'value_omzet'           => $value_omzet,
            'attach_1'              => $attach_1,
            'attach_2'              => $attach_2,
            'attach_3'              => $attach_3,
            'status'                => $status,
            'nama_status'           => $nama_status,
            'signature'             => $signature
        ];

        $this->navbar($data);
        // $this->load->view('management_office/top_header', $data);
        $this->load->view('kalimantan/header_full_width', $data);
        $this->load->view('management_event/pelaporan_edit', $data);
        $this->load->view('kalimantan/footer');
    }

    public function pelaporan_edit_proses(){
        $nama_event = $this->input->post('nama_event');
        $from = $this->input->post('from');
        $to = $this->input->post('to');
        $lokasi_event = $this->input->post('lokasi_event');
        $ref_perdin = $this->input->post('ref_perdin');
        $value_omzet = $this->input->post('value_omzet');
        $signature = $this->input->post('signature');

        $created_at = $this->model_outlet_transaksi->timezone();
        

        if (!is_dir('./assets/uploads/management_event/')) {
            @mkdir('./assets/uploads/management_event/', 0777);
        }

        $this->load->library('upload'); // Load librari upload        
        $config['upload_path'] = './assets/uploads/management_event/';
        // $config['allowed_types'] = 'xls|xlsx|csv';
        $config['allowed_types'] = '*';    
        $config['max_size']  = '2048000';
        $config['overwrite'] = false;
        $config['encrypt_name'] = false;
        $this->upload->initialize($config);

        if ($this->upload->do_upload('attach1')) 
        {
            $upload_data = $this->upload->data();
            $filename_attach1 = $upload_data['orig_name'];
        }else{
            // var_dump($this->upload->display_errors());
            // die;
            $filename_attach1 = $this->input->post('attach1_old');
        };

        if ($this->upload->do_upload('attach2')) 
        {
            $upload_data = $this->upload->data();
            $filename_attach2 = $upload_data['orig_name'];
        }else{
            // var_dump($this->upload->display_errors());
            // die;
            $filename_attach2 = $this->input->post('attach2_old');
        };

        if ($this->upload->do_upload('attach3')) 
        {
            $upload_data = $this->upload->data();
            $filename_attach3 = $upload_data['orig_name'];
        }else{
            // var_dump($this->upload->display_errors());
            // die;
            $filename_attach3 = $this->input->post('attach3_old');
        };

        // echo "nama_event : ".$nama_event;
        // echo "<br>";
        // echo "tanggal_event : ".$tanggal_event;
        // echo "<br>";
        // echo "lokasi_event : ".$lokasi_event;
        // echo "<br>";
        // echo "ref_perdin : ".$ref_perdin;
        // echo "<br>";
        // echo "value_omzet : ".$value_omzet;
        // echo "<br>";
        // echo "attach1 : ".$filename_attach1;
        // echo "<br>";
        // echo "attach2 : ".$filename_attach2;
        // echo "<br>";
        // echo "attach3 : ".$filename_attach3;

        // die;

        $no_pelaporan_event = $this->model_management_event->generate($created_at);
        // echo "no_pelaporan_event : ".$no_pelaporan_event;
        // die;

        $data = [
            'no_pelaporan_event'    => $no_pelaporan_event,
            'event_from'            => $from,
            'event_to'              => $to,
            'lokasi_event'          => $lokasi_event,
            'nama_event'            => $nama_event,
            'referensi_rpd'         => $ref_perdin,
            'value_omzet'           => $value_omzet,
            'attach_1'              => $filename_attach1,
            'attach_2'              => $filename_attach2,
            'attach_3'              => $filename_attach3,
            'updated_by'            => $this->session->userdata('id'),
            'updated_at'            => $created_at,
        ];

        $this->db->where('signature', $signature);
        $this->db->update('site.management_event_pelaporan', $data);

        $this->session->set_flashdata("pesan_success", "Update Event Berhasil");
        redirect('management_event/pelaporan_edit/'.$signature, 'refresh');
    }

}
?>
