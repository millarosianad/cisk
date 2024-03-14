<?php

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Management_rpd extends MY_Controller
{    
    function management_rpd()
    {       
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login_sistem/','refresh');
        }
        set_time_limit(0);
        $this->load->library(array('table', 'template', 'Excel_generator', 'form_validation', 'email', 'zip'));
        $this->load->helper(array('url', 'csv', 'download'));
        $this->load->model(array('model_outlet_transaksi', 'model_management_rpd'));
    }
    function index()
    {
        $this->dashboard();
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
    public function dashboard(){
        $data = [
            'title'             => 'Dashboard',
            'get_pengajuan'   => $this->model_management_rpd->get_pengajuan(),
            'url'               => 'management_bonus/import_master_data'
        ];

        $this->navbar($data);
        // $this->load->view('management_office/top_header', $data);
        $this->load->view('kalimantan/header_full_width', $data);
        $this->load->view('management_report/dashboard', $data);
        $this->load->view('kalimantan/footer');
    }

    public function pengajuan(){

        $this->load->model('model_master_data');

        $get_username_by_id = $this->model_master_data->get_username_by_id($this->session->userdata('id'));
        if ($get_username_by_id->num_rows() > 0) {
            $name = $get_username_by_id->row()->name;
            $jabatan = $get_username_by_id->row()->jabatan;
            $level_karyawan = $get_username_by_id->row()->level_karyawan;
        }else{
            $name = "-";
            $jabatan = "-";
            $level_karyawan = "-";
        }

        $data = [
            'title'         => 'Rencana Perjalanan Dinas - Input Pengajuan',
            'get_pengajuan' => $this->model_management_rpd->get_pengajuan(),
            'url'           => 'management_rpd/pengajuan_tambah',
            'name'          => $name,
            'jabatan'       => $jabatan,
            'level_karyawan' => $level_karyawan    
        ];

        $this->navbar($data);
        // $this->load->view('management_office/top_header', $data);
        $this->load->view('kalimantan/header_full_width', $data);
        $this->load->view('management_rpd/pengajuan', $data);
        $this->load->view('kalimantan/footer');
    }

    public function pengajuan_tambah(){

        if(!$this->input->post('pelaksana')){
            redirect('management_rpd/pengajuan');
            die;
        }

        $created_at = $this->model_outlet_transaksi->timezone();
        $signature = 'RPD-' . rand() . md5($this->model_outlet_transaksi->timezone()) . date('Ymd');

        $attachment_radius_perjalanan = $this->input->post('attachment_radius_perjalanan');

        if (!is_dir('./assets/file/rpd/')) {
            @mkdir('./assets/file/rpd/', 0777);
        }
        $config['upload_path'] = './assets/file/rpd/';
        $config['allowed_types'] = 'gif|jpg|png|jpeg';
        $config['max_size'] = '*';
        $config['overwrite'] = 'false';

        $this->load->library('upload', $config);
        if($this->upload->do_upload('attachment_radius_perjalanan')){
            $uploadData     = $this->upload->data();
            $filename       = $uploadData['orig_name'];
            $link           = $uploadData['full_path'];
        }else{ 
            $this->session->set_flashdata("pesan", "RPD anda gagal. Ada kesalahan di Attachment");
            redirect('management_rpd/pengajuan/');
            die;
        }

        $data = [
            'pelaksana'                     => $this->input->post('pelaksana'),
            'jabatan'                       => $this->input->post('jabatan'),
            // 'level_karyawan'                => $this->input->post('level_karyawan'),
            'maksud_perjalanan_dinas'       => $this->input->post('maksud_perjalanan_dinas'),
            'tanggal_berangkat'             => $this->input->post('tanggal_berangkat'),
            'tempat_berangkat'              => $this->input->post('tempat_berangkat'),
            'tanggal_tiba'                  => $this->input->post('tanggal_tiba'),
            'tempat_tiba'                   => $this->input->post('tempat_tiba'),
            'tanggal_mulai'                 => $this->input->post('tanggal_mulai'),
            'tanggal_akhir'                 => $this->input->post('tanggal_akhir'),
            'radius_perjalanan'             => $this->input->post('radius_perjalanan'),
            'attachment_radius_perjalanan'  => $filename,
            'created_at'                    => $created_at,
            'created_by'                    => $this->session->userdata('id'),
            'signature'                     => $signature
        ];
        //    var_dump($data); die;
        $this->db->insert('management_rpd.pengajuan', $data);
        $id = $this->db->insert_id();       
        
        // phpinfo();
        $signature = $this->model_management_rpd->get_pengajuan($id)->row()->signature;

        $this->session->set_flashdata("pesan_success", "pengajuan rpd anda terbentuk. Namun lengkapi aktivitas terlebih dahulu dan ajukan ke atasan");

        redirect('management_rpd/aktivitas/'.$signature);
    }

    public function aktivitas($signature){
        $get_pengajuan = $this->model_management_rpd->get_pengajuan_bysignature($signature)->result();
        foreach ($get_pengajuan as $key) {
            $no_rpd                     = $key->no_rpd;
            $pelaksana                  = $key->pelaksana;
            $maksud_perjalanan_dinas    = $key->maksud_perjalanan_dinas;
            $tanggal_berangkat          = $key->tanggal_berangkat;
            $tempat_berangkat           = $key->tempat_berangkat;
            $tanggal_tiba               = $key->tanggal_tiba;
            $tempat_tiba                = $key->tempat_tiba;
            $id                         = $key->id;
            $status                     = $key->status;
            $nama_status                = $key->nama_status;
            $username_verifikasi1         = $key->username_verifikasi1;
            $userid_verifikasi1           = $key->userid_verifikasi1;
            $username_verifikasi2         = $key->username_verifikasi2;
            $userid_verifikasi2           = $key->userid_verifikasi2;
            $verifikasi1_name           = $key->verifikasi1_name;
            $verifikasi2_name           = $key->verifikasi2_name;
            $verifikasi2_at           = $key->verifikasi2_at;
            $verifikasi1_at           = $key->verifikasi1_at;
            $verifikasi1_keterangan           = $key->verifikasi1_keterangan;
            $verifikasi2_keterangan           = $key->verifikasi2_keterangan;
            $verifikasi1_ttd           = $key->verifikasi1_ttd;
            $verifikasi2_ttd           = $key->verifikasi2_ttd;
            $jumlah_verifikasi           = $key->jumlah_verifikasi;
        }
        $get_aktivitas = $this->model_management_rpd->get_aktivitas($id);
        $total_biaya = $this->model_management_rpd->get_total_biaya($id)->row()->total_biaya;

        $data = [
            'title'                     => 'Rencana Perjalanan Dinas - Input Rincian Aktivitas',
            'no_rpd'                    => $no_rpd,
            'pelaksana'                 => $pelaksana,
            'maksud_perjalanan_dinas'   => $maksud_perjalanan_dinas,
            'berangkat'                 => $tempat_berangkat.' at '.$tanggal_berangkat,
            'tiba'                      => $tempat_tiba.' at '.$tanggal_tiba,
            'url'                       => 'management_rpd/aktivitas_tambah',
            'url_verifikasi'              => 'management_rpd/verifikasi_pengajuan',
            'total_biaya'               => $total_biaya,
            'get_aktivitas'             => $get_aktivitas,
            'id_pengajuan'              => $id,
            'signature_pengajuan'       => $signature,
            'status'                    => $status,
            'nama_status'               => $nama_status,
            'username_verifikasi1'        => $username_verifikasi1,
            'userid_verifikasi1'          => $userid_verifikasi1,
            'username_verifikasi2'        => $username_verifikasi2,
            'userid_verifikasi2'          => $userid_verifikasi2,
            'verifikasi1_name'          => $verifikasi1_name,
            'verifikasi2_name'          => $verifikasi2_name,
            'verifikasi2_at'          => $verifikasi2_at,
            'verifikasi1_at'          => $verifikasi1_at,
            'verifikasi1_keterangan'          => $verifikasi1_keterangan,
            'verifikasi2_keterangan'          => $verifikasi2_keterangan,
            'verifikasi1_ttd'          => $verifikasi1_ttd,
            'verifikasi2_ttd'          => $verifikasi2_ttd,
            'jumlah_verifikasi'          => $jumlah_verifikasi,
        ];

        $this->navbar($data);
        // $this->load->view('management_office/top_header', $data);
        $this->load->view('kalimantan/header_full_width', $data);
        $this->load->view('management_rpd/aktivitas', $data);
        $this->load->view('kalimantan/footer');

    }

    public function aktivitas_tambah(){
        $created_at = $this->model_outlet_transaksi->timezone();
        $signature_pengajuan = $this->input->post('signature_pengajuan');
        $signature = 'RPD-' . rand() . md5($this->model_outlet_transaksi->timezone()) . date('Ymd');

        $data = [
            'aktivitas'          => $this->input->post('aktivitas'),
            'id_pengajuan'       => $this->input->post('id_pengajuan'),
            'detail_aktivitas'   => $this->input->post('detail_aktivitas'),
            'tanggal_aktivitas'  => $this->input->post('tanggal_aktivitas'),
            'biaya'              => $this->input->post('biaya'),
            'status_claim'       => $this->input->post('status_claim'),
            'keterangan'         => $this->input->post('keterangan'),
            'created_at'         => $created_at,
            'signature'          => $signature
        ];

        $this->db->insert('management_rpd.aktivitas', $data);
        redirect('management_rpd/aktivitas/'.$signature_pengajuan);
    }

    public function verifikasi_pengajuan(){
        
        $signature_pengajuan = $this->input->post('signature_pengajuan');

        $level = $this->session->userdata('level');
        // var_dump($level);die;
        if ($level == '3d' || $level == '3c' || $level == '3b' ) {
            redirect("management_rpd/verifikasi_pengajuan_delto/$signature_pengajuan");
            die;
        } 

        $get_pengajuan = $this->model_management_rpd->get_pengajuan_bysignature($signature_pengajuan)->result();
        foreach ($get_pengajuan as $key) {
            $pelaksana                  = $key->pelaksana;
            $maksud_perjalanan_dinas    = $key->maksud_perjalanan_dinas;
            $tanggal_berangkat          = $key->tanggal_berangkat;
            $tempat_berangkat           = $key->tempat_berangkat;
            $tanggal_tiba               = $key->tanggal_tiba;
            $tempat_tiba                = $key->tempat_tiba;
            $id                         = $key->id;
            $created_at                 = $key->created_at;
            $no_rpd                     = $key->no_rpd;
            $verifikasi1_by               = $key->userid_verifikasi1;
            $verifikasi2_by               = $key->userid_verifikasi2;
        }
        $get_aktivitas = $this->model_management_rpd->get_aktivitas($id);
        // var_dump($get_aktivitas);

        if (!$get_aktivitas->num_rows() > 0) {

            $this->session->set_flashdata("pesan", "Pengajuan RPD Gagal. Silahkan isi aktivitas terlebih dahulu");
            redirect('management_rpd/aktivitas/'.$signature_pengajuan);

        }
        // generate no_rpd
        if ($no_rpd == null) {
            $update = [
                "no_rpd"    => $this->model_management_rpd->generate($created_at),
                // 'status'    => 3,
                // 'nama_status'   => 'pending atasan 1'
            ];
    
            $this->db->where('signature', $signature_pengajuan);
            $this->db->update('management_rpd.pengajuan', $update);
        }
        // end generate rpd
    
        // biaya

        $update_approval = [
            "verifikasi1_by"  => $verifikasi1_by,
            "verifikasi2_by"  => $verifikasi2_by,
            "status"        => 1,
            "nama_status"   => "menunggu verifikasi",
        ];
        $this->db->where('signature', $signature_pengajuan);
        $this->db->update('management_rpd.pengajuan', $update_approval);

        $total_biaya = $this->model_management_rpd->get_total_biaya($id)->row()->total_biaya;
        // echo "total_biaya : ".$total_biaya;
        if ($total_biaya >= 1000000) {
            $jumlah_verifikasi = 2;
        }else{
            $jumlah_verifikasi = 1;
        }

        $update_jumlah_verifikasi = [
            "jumlah_verifikasi" => $jumlah_verifikasi
        ];
        $this->db->where('signature', $signature_pengajuan);
        $this->db->update('management_rpd.pengajuan', $update_jumlah_verifikasi);

        redirect('management_rpd/email_verifikasi1/'.$signature_pengajuan);
        die;

    }

    public function verifikasi_pengajuan_delto($signature_pengajuan){
        $get_pengajuan = $this->model_management_rpd->get_pengajuan_bysignature($signature_pengajuan)->result();
        foreach ($get_pengajuan as $key) {
            $pelaksana                  = $key->pelaksana;
            $maksud_perjalanan_dinas    = $key->maksud_perjalanan_dinas;
            $tanggal_berangkat          = $key->tanggal_berangkat;
            $tempat_berangkat           = $key->tempat_berangkat;
            $tanggal_tiba               = $key->tanggal_tiba;
            $tempat_tiba                = $key->tempat_tiba;
            $id                         = $key->id;
            $created_at                 = $key->created_at;
            $no_rpd                     = $key->no_rpd;
            $verifikasi1_by               = $key->userid_verifikasi1;
            $verifikasi2_by               = $key->userid_verifikasi2;
        }
        $get_aktivitas = $this->model_management_rpd->get_aktivitas($id);
        // var_dump($get_aktivitas);

        if (!$get_aktivitas->num_rows() > 0) {

            echo "<h3>Result</h3>";
            echo "Permintaan Verifikasi ditolak !!<br><br>";
            echo "Anda belum mengisi satu pun aktivitas<br>";

            echo "<br>anda akan di redirect ke menu awal dalam 5 detik";
            header('Refresh: 5; URL='.base_url().'management_rpd/aktivitas/'.$signature_pengajuan);
            die;

        }
        // generate no_rpd
        if ($no_rpd == null) {
            $update = [
                "no_rpd"    => $this->model_management_rpd->generate($created_at)
            ];
    
            $this->db->where('signature', $signature_pengajuan);
            $this->db->update('management_rpd.pengajuan', $update);
        }
        // end generate rpd

        $total_biaya = $this->model_management_rpd->get_total_biaya($id)->row()->total_biaya;
        echo "total_biaya : ".$total_biaya;
        if ($total_biaya >= 1000000) {
            // // pak hendy dan pak yayang
            // if ($verifikasi2_by == 521) {
            //     $verif1 = $verifikasi1_by;
            //     $verif2 = $verifikasi2_by;
            // } else {
            //     $verif1 = $verifikasi2_by;
            //     $verif2 = 521;
            // }
            
            $update_approval = [
                "verifikasi1_by"  => $verifikasi2_by,
                "verifikasi2_by"  => 521,
                "status"        => 1,
                "nama_status"   => "menunggu verifikasi",
                "jumlah_verifikasi" => 2
            ];
        }else{
            // rpsh dan pak hendy
            $update_approval = [
                "verifikasi1_by"    => $verifikasi1_by,
                "verifikasi2_by"    => $verifikasi2_by,
                "status"            => 1,
                "nama_status"       => "menunggu verifikasi",
                "jumlah_verifikasi" => 2
            ];
        }
        $this->db->where('signature', $signature_pengajuan);
        $this->db->update('management_rpd.pengajuan', $update_approval);

        redirect('management_rpd/email_verifikasi1/'.$signature_pengajuan);
        die;

    }

    public function verifikasi1($signature_pengajuan){
        $get_pengajuan = $this->model_management_rpd->get_pengajuan_bysignature($signature_pengajuan)->result();
        foreach ($get_pengajuan as $key) {
            $no_rpd                     = $key->no_rpd;
            $pelaksana                  = $key->pelaksana;
            $maksud_perjalanan_dinas    = $key->maksud_perjalanan_dinas;
            $tanggal_berangkat          = $key->tanggal_berangkat;
            $tempat_berangkat           = $key->tempat_berangkat;
            $tanggal_tiba               = $key->tanggal_tiba;
            $tempat_tiba                = $key->tempat_tiba;
            $id                         = $key->id;
            $status                     = $key->status;
            $nama_status                = $key->nama_status;
            $username_verifikasi1       = $key->username_verifikasi1;
            $userid_verifikasi1         = $key->userid_verifikasi1;
            $username_verifikasi2       = $key->username_verifikasi2;
            $userid_verifikasi2         = $key->userid_verifikasi2;
            $verifikasi1_name           = $key->verifikasi1_name;
            $verifikasi2_name           = $key->verifikasi2_name;
            $verifikasi2_at             = $key->verifikasi2_at;
            $verifikasi1_at             = $key->verifikasi1_at;
            $verifikasi1_keterangan     = $key->verifikasi1_keterangan;
            $verifikasi2_keterangan     = $key->verifikasi2_keterangan;
            $created_by                 = $key->created_by;
        }
        $get_aktivitas = $this->model_management_rpd->get_aktivitas($id);
        $total_biaya = $this->model_management_rpd->get_total_biaya($id)->row()->total_biaya;

        $data = [
            'title'                     => 'Rencana Perjalanan Dinas - Verifikasi',
            'no_rpd'                    => $no_rpd,
            'pelaksana'                 => $pelaksana,
            'maksud_perjalanan_dinas'   => $maksud_perjalanan_dinas,
            'berangkat'                 => $tempat_berangkat.' at '.$tanggal_berangkat,
            'tiba'                      => $tempat_tiba.' at '.$tanggal_tiba,
            'url'                       => 'management_rpd/verifikasi1_update',
            'url2'                      => "management_rpd/signature_digital/$signature_pengajuan/verifikasi1",
            'total_biaya'               => $total_biaya,
            'get_aktivitas'             => $get_aktivitas,
            'id_pengajuan'              => $id,
            'signature_pengajuan'       => $signature_pengajuan,
            'status'                    => $status,
            'nama_status'               => $nama_status,
            'username_verifikasi1'      => $username_verifikasi1,
            'userid_verifikasi1'        => $userid_verifikasi1,
            'username_verifikasi2'      => $username_verifikasi2,
            'userid_verifikasi2'        => $userid_verifikasi2,
            'verifikasi1_name'          => $verifikasi1_name,
            'verifikasi2_name'          => $verifikasi2_name,
            'verifikasi2_at'            => $verifikasi2_at,
            'verifikasi1_at'            => $verifikasi1_at,
            'verifikasi1_keterangan'    => $verifikasi1_keterangan,
            'verifikasi2_keterangan'    => $verifikasi2_keterangan,
            'created_by'                => $created_by,
        ];

        $this->navbar($data);
        // $this->load->view('management_office/top_header', $data);
        $this->load->view('kalimantan/header_full_width', $data);
        $this->load->view('management_rpd/verifikasi', $data);
        $this->load->view('kalimantan/footer');
    }

    public function verifikasi1_update(){
        $status_verifikasi = $this->input->post('status_verifikasi');

        if ($status_verifikasi == 0) {
            $verifikasi_name = 'reject';
            $status = 0;
            $nama_status = 'reject';
        }elseif ($status_verifikasi == 1) {
            $verifikasi_name = 'approve';
            $status = 1;
            $nama_status = 'approve';
        }

        $keterangan_verifikasi = $this->input->post('keterangan_verifikasi');
        $signature_pengajuan = $this->input->post('signature_pengajuan');

        $data = [
            "verifikasi1_by"            => $this->session->userdata('id'),
            "verifikasi1_at"            => $this->model_outlet_transaksi->timezone(),
            'verifikasi1_status'        => $status_verifikasi,
            'verifikasi1_name'          => $verifikasi_name,
            'status'                    => $status,
            'nama_status'               => $nama_status,
            'verifikasi1_keterangan'    => $keterangan_verifikasi,
            'verifikasi1_ttd'           => $this->session->userdata('username').'-signature.png'
        ];

        $this->db->where('signature', $signature_pengajuan);
        $this->db->update('management_rpd.pengajuan', $data);

        redirect('management_rpd/verifikasi1/'.$signature_pengajuan);

        
    }

    public function verifikasi2($signature_pengajuan){
        $get_pengajuan = $this->model_management_rpd->get_pengajuan_bysignature($signature_pengajuan)->result();
        foreach ($get_pengajuan as $key) {
            $no_rpd                     = $key->no_rpd;
            $pelaksana                  = $key->pelaksana;
            $maksud_perjalanan_dinas    = $key->maksud_perjalanan_dinas;
            $tanggal_berangkat          = $key->tanggal_berangkat;
            $tempat_berangkat           = $key->tempat_berangkat;
            $tanggal_tiba               = $key->tanggal_tiba;
            $tempat_tiba                = $key->tempat_tiba;
            $id                         = $key->id;
            $status                     = $key->status;
            $nama_status                = $key->nama_status;
            $username_verifikasi1       = $key->username_verifikasi1;
            $userid_verifikasi1         = $key->userid_verifikasi1;
            $username_verifikasi2       = $key->username_verifikasi2;
            $userid_verifikasi2         = $key->userid_verifikasi2;
            $verifikasi1_name           = $key->verifikasi1_name;
            $verifikasi2_name           = $key->verifikasi2_name;
            $verifikasi2_at             = $key->verifikasi2_at;
            $verifikasi1_at             = $key->verifikasi1_at;
            $verifikasi1_keterangan     = $key->verifikasi1_keterangan;
            $verifikasi2_keterangan     = $key->verifikasi2_keterangan;
            $created_by                 = $key->created_by;
        }
        $get_aktivitas = $this->model_management_rpd->get_aktivitas($id);
        $total_biaya = $this->model_management_rpd->get_total_biaya($id)->row()->total_biaya;

        $data = [
            'title'                     => 'Rencana Perjalanan Dinas - Verifikasi',
            'no_rpd'                    => $no_rpd,
            'pelaksana'                 => $pelaksana,
            'maksud_perjalanan_dinas'   => $maksud_perjalanan_dinas,
            'berangkat'                 => $tempat_berangkat.' at '.$tanggal_berangkat,
            'tiba'                      => $tempat_tiba.' at '.$tanggal_tiba,
            'url'                       => 'management_rpd/verifikasi2_update',
            'url2'                      => "management_rpd/signature_digital/$signature_pengajuan/verifikasi2",
            'total_biaya'               => $total_biaya,
            'get_aktivitas'             => $get_aktivitas,
            'id_pengajuan'              => $id,
            'signature_pengajuan'       => $signature_pengajuan,
            'status'                    => $status,
            'nama_status'               => $nama_status,
            'username_verifikasi1'      => $username_verifikasi1,
            'userid_verifikasi1'        => $userid_verifikasi1,
            'username_verifikasi2'      => $username_verifikasi2,
            'userid_verifikasi2'        => $userid_verifikasi2,
            'verifikasi1_name'          => $verifikasi1_name,
            'verifikasi2_name'          => $verifikasi2_name,
            'verifikasi2_at'            => $verifikasi2_at,
            'verifikasi1_at'            => $verifikasi1_at,
            'verifikasi1_keterangan'    => $verifikasi1_keterangan,
            'verifikasi2_keterangan'    => $verifikasi2_keterangan,
            'created_by'                => $created_by,
        ];

        $this->navbar($data);
        // $this->load->view('management_office/top_header', $data);
        $this->load->view('kalimantan/header_full_width', $data);
        $this->load->view('management_rpd/verifikasi', $data);
        $this->load->view('kalimantan/footer');
    }

    public function verifikasi2_update(){
        $status_verifikasi = $this->input->post('status_verifikasi');

        if ($status_verifikasi == 0) {
            $verifikasi_name = 'reject';
            $status = 0;
            $nama_status = 'reject';
        }elseif ($status_verifikasi == 1) {
            $verifikasi_name = 'approve';
            $status = 1;
            $nama_status = 'approve';
        }

        $keterangan_verifikasi = $this->input->post('keterangan_verifikasi');
        $signature_pengajuan = $this->input->post('signature_pengajuan');

        $data = [
            "verifikasi2_by"            => $this->session->userdata('id'),
            "verifikasi2_at"            => $this->model_outlet_transaksi->timezone(),
            'verifikasi2_status'        => $status_verifikasi,
            'verifikasi2_name'          => $verifikasi_name,
            'status'                    => $status,
            'nama_status'               => $nama_status,
            'verifikasi2_keterangan'    => $keterangan_verifikasi,
            'verifikasi2_ttd'           => $this->session->userdata('username').'-signature.png'
        ];

        $this->db->where('signature', $signature_pengajuan);
        $this->db->update('management_rpd.pengajuan', $data);

        redirect('management_rpd/verifikasi2/'.$signature_pengajuan);

        
    }

    public function email_verifikasi1($signature_pengajuan){
        
        $get_pengajuan = $this->model_management_rpd->get_pengajuan_bysignature($signature_pengajuan)->result();
        foreach ($get_pengajuan as $key) {
            $no_rpd                     = $key->no_rpd;
            $pelaksana                  = $key->pelaksana;
            $maksud_perjalanan_dinas    = $key->maksud_perjalanan_dinas;
            $tanggal_berangkat          = $key->tanggal_berangkat;
            $tempat_berangkat           = $key->tempat_berangkat;
            $tanggal_tiba               = $key->tanggal_tiba;
            $tempat_tiba                = $key->tempat_tiba;
            $id                         = $key->id;
            $status                     = $key->status;
            $nama_status                = $key->nama_status;
            $username_verifikasi1         = $key->username_verifikasi1;
            $userid_verifikasi1           = $key->userid_verifikasi1;
            $username_verifikasi2         = $key->username_verifikasi2;
            $userid_verifikasi2           = $key->userid_verifikasi2;
            $verifikasi1_name           = $key->verifikasi1_name;
            $verifikasi2_name           = $key->verifikasi2_name;
            $verifikasi2_at           = $key->verifikasi2_at;
            $verifikasi1_at           = $key->verifikasi1_at;
            $verifikasi1_keterangan           = $key->verifikasi1_keterangan;
            $verifikasi2_keterangan           = $key->verifikasi2_keterangan;
            $created_by           = $key->created_by;
            $jumlah_verifikasi           = $key->jumlah_verifikasi;
        }

        // echo "jumlah_verifikasi : ".$jumlah_verifikasi;
        // die;

        $from = "suffy@muliaputramandiri.net";
        // $to = 'suffy.yanuar@gmail.com';
        // $cc = 'suffy.mpm@gmail.com';

        $email_to = $this->model_management_rpd->get_user($userid_verifikasi1)->row()->email;
        // $email_cc = $this->model_management_rpd->get_user($created_by)->row()->email.',suffy@muliaputramandiri.com,ratri@muliaputramandiri.com,nanita@muliaputramandiri.com';
        $email_cc = $this->model_management_rpd->get_user($created_by)->row()->email.',suffy@muliaputramandiri.com';

        $get_aktivitas = $this->model_management_rpd->get_aktivitas($id);
        $total_biaya = $this->model_management_rpd->get_total_biaya($id)->row()->total_biaya;

        $subject = "MPM Site | RPD : $no_rpd | VERIFIKASI 1";
        $this->load->model('model_relokasi');
        $this->model_relokasi->email();

        // echo "userid_verifikasi1 : ".$userid_verifikasi1;
        // echo "email_to : ".$email_to;
        // echo "email_cc : ".$email_cc;

        // die;

        $data = [
            'title'                     => 'Rencana Perjalanan Dinas - Verifikasi 1',
            'no_rpd'                    => $no_rpd,
            'pelaksana'                 => $pelaksana,
            'maksud_perjalanan_dinas'   => $maksud_perjalanan_dinas,
            'berangkat'                 => $tempat_berangkat.' at '.$tanggal_berangkat,
            'tiba'                      => $tempat_tiba.' at '.$tanggal_tiba,
            'url'                       => 'management_rpd/verifikasi2_update',
            'total_biaya'               => $total_biaya,
            'get_aktivitas'             => $get_aktivitas,
            'id_pengajuan'              => $id,
            'signature_pengajuan'       => $signature_pengajuan,
            'status'                    => $status,
            'nama_status'               => $nama_status,
            'username_verifikasi1'        => $username_verifikasi1,
            'userid_verifikasi1'          => $userid_verifikasi1,
            'username_verifikasi2'        => $username_verifikasi2,
            'userid_verifikasi2'          => $userid_verifikasi2,
            'verifikasi1_name'          => $verifikasi1_name,
            'verifikasi2_name'          => $verifikasi2_name,
            'verifikasi2_at'          => $verifikasi2_at,
            'verifikasi1_at'          => $verifikasi1_at,
            'verifikasi1_keterangan'          => $verifikasi1_keterangan,
            'verifikasi2_keterangan'          => $verifikasi2_keterangan,
            
        ];

        $message = $this->load->view("management_rpd/email_verifikasi1",$data,TRUE);

        $this->email->from($from,'PT. Mulia Putra Mandiri');
        $this->email->to($email_to);
        // $this->email->cc($email_cc);
        $this->email->subject($subject);
        $this->email->message($message);
        $send = $this->email->send();
        if ($send) {
            // echo "<script>alert('pengiriman email berhasil'); </script>";

            $this->session->set_flashdata("pesan_success", "Pengajuan RPD dan Pengiriman Email Berhasil");

            if ($jumlah_verifikasi == 1) {
                redirect('management_rpd/aktivitas/'.$signature_pengajuan,'refresh');

                die;
            }

            redirect('management_rpd/email_verifikasi2/'.$signature_pengajuan);

            // redirect('management_rpd/email_verifikasi2/'.$signature_pengajuan);
            // redirect('management_rpd/aktivitas/'.$signature_pengajuan,'refresh');
        }else{
            echo "<script>alert('pengiriman email gagal'); </script>";
            redirect('management_rpd/aktivitas/'.$signature_pengajuan,'refresh');
        }
    }

    public function email_verifikasi2($signature_pengajuan){
        // $to = 'suffy.yanuar@gmail.com';
        // $cc = 'suffy.mpm@gmail.com';

        $get_pengajuan = $this->model_management_rpd->get_pengajuan_bysignature($signature_pengajuan)->result();
        foreach ($get_pengajuan as $key) {
            $no_rpd                     = $key->no_rpd;
            $pelaksana                  = $key->pelaksana;
            $maksud_perjalanan_dinas    = $key->maksud_perjalanan_dinas;
            $tanggal_berangkat          = $key->tanggal_berangkat;
            $tempat_berangkat           = $key->tempat_berangkat;
            $tanggal_tiba               = $key->tanggal_tiba;
            $tempat_tiba                = $key->tempat_tiba;
            $id                         = $key->id;
            $status                     = $key->status;
            $nama_status                = $key->nama_status;
            $username_verifikasi1       = $key->username_verifikasi1;
            $userid_verifikasi1         = $key->userid_verifikasi1;
            $username_verifikasi2       = $key->username_verifikasi2;
            $userid_verifikasi2         = $key->userid_verifikasi2;
            $verifikasi1_name           = $key->verifikasi1_name;
            $verifikasi2_name           = $key->verifikasi2_name;
            $verifikasi2_at             = $key->verifikasi2_at;
            $verifikasi1_at             = $key->verifikasi1_at;
            $verifikasi1_keterangan     = $key->verifikasi1_keterangan;
            $verifikasi2_keterangan     = $key->verifikasi2_keterangan;
            $created_by                 = $key->created_by;
        }
        $get_aktivitas = $this->model_management_rpd->get_aktivitas($id);
        $total_biaya = $this->model_management_rpd->get_total_biaya($id)->row()->total_biaya;

        $from = "suffy@muliaputramandiri.net";
        // $to = 'suffy.yanuar@gmail.com';
        // $cc = 'suffy.mpm@gmail.com';

        $email_to = $this->model_management_rpd->get_user($userid_verifikasi2)->row()->email;
        // $email_cc = $this->model_management_rpd->get_user($created_by)->row()->email.',suffy@muliaputramandiri.com,ratri@muliaputramandiri.com,nanita@muliaputramandiri.com';
        $email_cc = $this->model_management_rpd->get_user($created_by)->row()->email.',suffy@muliaputramandiri.com';

        $subject = "MPM Site | RPD : $no_rpd | VERIFIKASI 2";
        $this->load->model('model_relokasi');
        $this->model_relokasi->email();

        // $get_id_ref_relokasi_header = $this->model_relokasi->get_data_relokasi_header($signature)->row()->id;

        $data = [
            'title'                     => 'Rencana Perjalanan Dinas - Verifikasi 2',
            'no_rpd'                    => $no_rpd,
            'pelaksana'                 => $pelaksana,
            'maksud_perjalanan_dinas'   => $maksud_perjalanan_dinas,
            'berangkat'                 => $tempat_berangkat.' at '.$tanggal_berangkat,
            'tiba'                      => $tempat_tiba.' at '.$tanggal_tiba,
            'url'                       => 'management_rpd/verifikasi2_update',
            'total_biaya'               => $total_biaya,
            'get_aktivitas'             => $get_aktivitas,
            'id_pengajuan'              => $id,
            'signature_pengajuan'       => $signature_pengajuan,
            'status'                    => $status,
            'nama_status'               => $nama_status,
            'username_verifikasi1'        => $username_verifikasi1,
            'userid_verifikasi1'          => $userid_verifikasi1,
            'username_verifikasi2'        => $username_verifikasi2,
            'userid_verifikasi2'          => $userid_verifikasi2,
            'verifikasi1_name'          => $verifikasi1_name,
            'verifikasi2_name'          => $verifikasi2_name,
            'verifikasi2_at'          => $verifikasi2_at,
            'verifikasi1_at'          => $verifikasi1_at,
            'verifikasi1_keterangan'          => $verifikasi1_keterangan,
            'verifikasi2_keterangan'          => $verifikasi2_keterangan,
        ];

        $message = $this->load->view("management_rpd/email_verifikasi2",$data,TRUE);

        $this->email->from($from,'PT. Mulia Putra Mandiri');
        $this->email->to($email_to);
        // $this->email->cc($email_cc);
        $this->email->subject($subject);
        $this->email->message($message);
        $send = $this->email->send();
        // echo $this->email->print_debugger();
        if ($send) {
            // echo "<script>alert('pengiriman email berhasil'); </script>";
            $this->session->set_flashdata("pesan_success", "Pengajuan RPD dan Pengiriman Email Berhasil");
            redirect('management_rpd/aktivitas/'.$signature_pengajuan,'refresh');
        }else{
            // echo "<script>alert('pengiriman email gagal'); </script>";
            $this->session->set_flashdata("pesan", "Pengajuan RPD Berhasil Namun Pengiriman Email Gagal");
            redirect('management_rpd/aktivitas/'.$signature_pengajuan,'refresh');
        }
    }

    public function aktivitas_delete_soft($signature_aktivitas, $signature_pengajuan){
        $data = [
            "deleted_by"    => $this->session->userdata('id'),
            "deleted_at"    => $this->model_outlet_transaksi->timezone()
        ];

        $this->db->where("signature", $signature_aktivitas);
        $this->db->update("management_rpd.aktivitas", $data);

        redirect("management_rpd/aktivitas/".$signature_pengajuan);
        
    }

    public function realisasi($signature){
        $get_pengajuan = $this->model_management_rpd->get_pengajuan_bysignature($signature)->result();
        foreach ($get_pengajuan as $key) {
            $no_rpd                     = $key->no_rpd;
            $pelaksana                  = $key->pelaksana;
            $maksud_perjalanan_dinas    = $key->maksud_perjalanan_dinas;
            $tanggal_berangkat          = $key->tanggal_berangkat;
            $tempat_berangkat           = $key->tempat_berangkat;
            $tanggal_tiba               = $key->tanggal_tiba;
            $tempat_tiba                = $key->tempat_tiba;
            $id                         = $key->id;
            $status                     = $key->status;
            $nama_status                = $key->nama_status;
            $username_verifikasi1       = $key->username_verifikasi1;
            $userid_verifikasi1         = $key->userid_verifikasi1;
            $username_verifikasi2       = $key->username_verifikasi2;
            $userid_verifikasi2         = $key->userid_verifikasi2;
            $verifikasi1_name           = $key->verifikasi1_name;
            $verifikasi2_name           = $key->verifikasi2_name;
            $verifikasi2_at             = $key->verifikasi2_at;
            $verifikasi1_at             = $key->verifikasi1_at;
            $verifikasi1_keterangan     = $key->verifikasi1_keterangan;
            $verifikasi2_keterangan     = $key->verifikasi2_keterangan;
            $verifikasi1_ttd            = $key->verifikasi1_ttd;
            $verifikasi2_ttd            = $key->verifikasi2_ttd;
            $jumlah_verifikasi          = $key->jumlah_verifikasi;
        }

        $get_aktivitas = $this->model_management_rpd->get_aktivitas($id);
        $total_biaya = $this->model_management_rpd->get_total_biaya($id)->row()->total_biaya;

        $data = [
            'title'                     => 'Rencana Perjalanan Dinas - Input Realisasi',
            'no_rpd'                    => $no_rpd,
            'pelaksana'                 => $pelaksana,
            'maksud_perjalanan_dinas'   => $maksud_perjalanan_dinas,
            'berangkat'                 => $tempat_berangkat.' at '.$tanggal_berangkat,
            'tiba'                      => $tempat_tiba.' at '.$tanggal_tiba,
            'url'                       => 'management_rpd/realisasi_proses',
            'signature_pengajuan'       => $signature,
            'total_biaya'               => $total_biaya,
            'get_aktivitas'             => $get_aktivitas,
            'id_pengajuan'              => $id,
            'signature_pengajuan'       => $signature,
            'status'                    => $status,
            'nama_status'               => $nama_status,
            'username_verifikasi1'      => $username_verifikasi1,
            'userid_verifikasi1'        => $userid_verifikasi1,
            'username_verifikasi2'      => $username_verifikasi2,
            'userid_verifikasi2'        => $userid_verifikasi2,
            'verifikasi1_name'          => $verifikasi1_name,
            'verifikasi2_name'          => $verifikasi2_name,
            'verifikasi2_at'            => $verifikasi2_at,
            'verifikasi1_at'            => $verifikasi1_at,
            'verifikasi1_keterangan'    => $verifikasi1_keterangan,
            'verifikasi2_keterangan'    => $verifikasi2_keterangan,
            'verifikasi1_ttd'           => $verifikasi1_ttd,
            'verifikasi2_ttd'           => $verifikasi2_ttd,
            'jumlah_verifikasi'         => $jumlah_verifikasi,
        ];

        $this->navbar($data);
        // $this->load->view('management_office/top_header', $data);
        $this->load->view('kalimantan/header_full_width', $data);
        $this->load->view('management_rpd/realisasi', $data);
        $this->load->view('kalimantan/footer');
    }

    public function realisasi_proses(){
        // $this->db->trans_start();

        $data = array();
        $count = count($this->input->post('keterangan_realisasi'));
        for($i=0; $i < $count; $i++) {
            if(!empty($_FILES['attachment']['name'][$i])){
    
                $_FILES['file']['name']     = $_FILES['attachment']['name'][$i];
                $_FILES['file']['type']     = $_FILES['attachment']['type'][$i];
                $_FILES['file']['tmp_name'] = $_FILES['attachment']['tmp_name'][$i];
                $_FILES['file']['error']    = $_FILES['attachment']['error'][$i];
                $_FILES['file']['size']     = $_FILES['attachment']['size'][$i];
        
                $config['upload_path']      = './assets/file/rpd/';
                $config['allowed_types']    = '*';
                $config['max_size']         = '*';
                $config['overwrite']        = false;
                $config['file_name']        = $_FILES['attachment']['name'][$i];
        
                $this->load->library('upload',$config); 
        
                if($this->upload->do_upload('file')){
                    $uploadData = $this->upload->data();
                    $filename = $uploadData['client_name'];
                    $link = $uploadData['full_path'];
                }

                $data = [
                    'status_realisasi'      => $this->input->post('status_realisasi')[$i+1],
                    'keterangan_realisasi'  => $this->input->post('keterangan_realisasi')[$i],
                    'attachment_realisasi'  => $filename,
                    'attachment_link'       => substr_replace($link,"",0,21),
                ];
            }
            else {
                # code...
                $data = [
                    'status_realisasi'      => $this->input->post('status_realisasi')[$i+1],
                    'keterangan_realisasi'  => $this->input->post('keterangan_realisasi')[$i],
                ];
                
            }
            
            $this->db->where('signature', $this->input->post('signature_aktivitas')[$i]);
            $this->db->update('management_rpd.aktivitas', $data);
        }

        $update = [
            'status_realisasi'  => 1,
            'realisasi_at'      => $this->model_outlet_transaksi->timezone()
        ];

        $this->db->where('signature', $this->input->post('signature_pengajuan'));
        $this->db->update('management_rpd.pengajuan', $update);


        // $this->db->trans_complete();
        // if ($this->db->trans_status() === FALSE)
        // {
        //     echo "ada kegagalan dalam proses realisasi. Mungkin disebabkan internet. rollback diaktifkan ke keadaan sebelumnya";
        //     die;
        // }

        redirect('management_rpd/pengajuan');

    }

    public function input_akomodasi($signature){
        $get_pengajuan = $this->model_management_rpd->get_pengajuan_bysignature($signature)->result();
        foreach ($get_pengajuan as $key) {
            $no_rpd                     = $key->no_rpd;
            $pelaksana                  = $key->pelaksana;
            $jabatan                    = $key->jabatan;
            $id                         = $key->id;
            $status                     = $key->status;
            $nama_status                = $key->nama_status;
            $radius_perjalanan          = $key->radius_perjalanan;
            $attachment_radius_perjalanan = $key->attachment_radius_perjalanan;
            $attachment_akomodasi       = $key->attachment_akomodasi;
            $keterangan_akomodasi       = $key->keterangan_akomodasi;
            
        }

        $get_aktivitas = $this->model_management_rpd->get_pengajuan_akomodasi($id);
        $total_biaya = $this->model_management_rpd->get_total_biaya($id)->row()->total_biaya;

        $data = [
            'title'                     => 'Rencana Perjalanan Dinas - Input Akomodasi',
            'no_rpd'                    => $no_rpd,
            'pelaksana'                 => $pelaksana,
            'jabatan'                   => $jabatan,
            'url'                       => 'management_rpd/input_akomodasi_proses',
            'signature_pengajuan'       => $signature,
            'total_biaya'               => $total_biaya,
            'get_aktivitas'             => $get_aktivitas,
            'id_pengajuan'              => $id,
            'signature_pengajuan'       => $signature,
            'status'                    => $status,
            'nama_status'               => $nama_status,
            'radius_perjalanan'         => $radius_perjalanan,
            'attachment_radius_perjalanan' => $attachment_radius_perjalanan,
            'attachment_akomodasi'     => $attachment_akomodasi,
            'keterangan_akomodasi'     => $keterangan_akomodasi
        ];

        $this->navbar($data);
        // $this->load->view('management_office/top_header', $data);
        $this->load->view('kalimantan/header_full_width', $data);
        $this->load->view('management_rpd/input_akomodasi', $data);
        $this->load->view('kalimantan/footer');
    }

    public function input_akomodasi_proses(){
        echo 'A';
        // $this->db->trans_start();

        if (!is_dir('./assets/file/rpd/')) {
            @mkdir('./assets/file/rpd/', 0777);
        }
        $attachment_akomodasi = $this->input->post('attachment_akomodasi');
        $keterangan_akomodasi  = $this->input->post('keterangan_akomodasi');

        $config['upload_path'] = './assets/file/rpd/';
        $config['allowed_types'] = '*';
        $config['max_size'] = '*';
        $config['overwrite'] = 'false';

        $this->load->library('upload', $config);

        if($this->upload->do_upload('attachment_akomodasi')){
            $uploadData     = $this->upload->data();
            $filename       = $uploadData['orig_name'];
            $link           = $uploadData['full_path'];
        }
        $data = [
            'attachment_akomodasi'  => $filename,
            'keterangan_akomodasi'  => $keterangan_akomodasi,
            // 'attachment_link'       => substr_replace($link,"",0,21),

        ];
        $this->db->where('signature', $this->input->post('signature_pengajuan'));
        $this->db->update('management_rpd.pengajuan', $data);

        redirect('management_rpd/pengajuan');

    }

    public function generate_pdf($signature){
        $this->load->library('mypdf');

        $get_pengajuan = $this->model_management_rpd->get_pengajuan_bysignature($signature)->result();
        foreach ($get_pengajuan as $key) {
            $no_rpd                         = $key->no_rpd;
            $pelaksana                      = $key->pelaksana;
            $jabatan                        = $key->jabatan;
            $level_karyawan                 = $key->level_karyawan;
            $maksud_perjalanan_dinas        = $key->maksud_perjalanan_dinas;
            $tanggal_berangkat              = $key->tanggal_berangkat;
            $tempat_berangkat               = $key->tempat_berangkat;
            $tanggal_tiba                   = $key->tanggal_tiba;
            $tempat_tiba                    = $key->tempat_tiba;
            $id                             = $key->id;
            $status                         = $key->status;
            $nama_status                    = $key->nama_status;
            $username_verifikasi1           = $key->username_verifikasi1;
            $userid_verifikasi1             = $key->userid_verifikasi1;
            $username_verifikasi2           = $key->username_verifikasi2;
            $userid_verifikasi2             = $key->userid_verifikasi2;
            $verifikasi1_name               = $key->verifikasi1_name;
            $verifikasi2_name               = $key->verifikasi2_name;
            $verifikasi2_at                 = $key->verifikasi2_at;
            $verifikasi1_at                 = $key->verifikasi1_at;
            $verifikasi1_keterangan         = $key->verifikasi1_keterangan;
            $verifikasi2_keterangan         = $key->verifikasi2_keterangan;
            $verifikasi1_ttd                = $key->verifikasi1_ttd;
            $verifikasi2_ttd                = $key->verifikasi2_ttd;
            $jumlah_verifikasi              = $key->jumlah_verifikasi;
            $tanggal_mulai                  = $key->tanggal_mulai;
            $tanggal_akhir                  = $key->tanggal_akhir;
            $verifikasi1_status              = $key->verifikasi1_status;
            $verifikasi2_status              = $key->verifikasi2_status;
        }

        $get_aktivitas = $this->model_management_rpd->get_aktivitas($id);
        // die;
        $total_biaya = $this->model_management_rpd->get_total_biaya($id)->row()->total_biaya;

        $data = [
            'title'                     => 'Rencana Perjalanan Dinas',
            'no_rpd'                    => $no_rpd,
            'pelaksana'                 => $pelaksana,
            'jabatan'                   => $jabatan,
            'level_karyawan'            => $level_karyawan,
            'maksud_perjalanan_dinas'   => $maksud_perjalanan_dinas,
            'berangkat'                 => $tempat_berangkat.' at '.$tanggal_berangkat,
            'tiba'                      => $tempat_tiba.' at '.$tanggal_tiba,
            'url'                       => 'management_rpd/realisasi_proses',
            'signature_pengajuan'       => $signature,
            'total_biaya'               => $total_biaya,
            'get_aktivitas'             => $get_aktivitas,
            'cek_realisasi'             => $this->model_management_rpd->cek_realisasi($id),
            'id_pengajuan'              => $id,
            'signature_pengajuan'       => $signature,
            'status'                    => $status,
            'nama_status'               => $nama_status,
            'username_verifikasi1'        => $username_verifikasi1,
            'userid_verifikasi1'          => $userid_verifikasi1,
            'username_verifikasi2'        => $username_verifikasi2,
            'userid_verifikasi2'          => $userid_verifikasi2,
            'verifikasi1_name'          => $verifikasi1_name,
            'verifikasi2_name'          => $verifikasi2_name,
            'verifikasi2_at'          => $verifikasi2_at,
            'verifikasi1_at'          => $verifikasi1_at,
            'verifikasi1_keterangan'          => $verifikasi1_keterangan,
            'verifikasi2_keterangan'          => $verifikasi2_keterangan,
            'verifikasi1_ttd'          => $verifikasi1_ttd,
            'verifikasi2_ttd'          => $verifikasi2_ttd,
            'jumlah_verifikasi'          => $jumlah_verifikasi,
            'tanggal_mulai'             => $tanggal_mulai,
            'tanggal_akhir'             => $tanggal_akhir,
            'verifikasi1_status'         => $verifikasi1_status,
            'verifikasi2_status'         => $verifikasi2_status
        ];

        $filename_pdf = $no_rpd;

        $generate_pdf = $this->mypdf->generate('management_rpd/template_rpd',$data,$filename_pdf,'A4','landscape');

    }

    public function signature_digital(){
        
        $signature = $this->uri->segment('3');
        $url = $this->uri->segment('4');
        $data = [
            'title'           => 'Digital Signature',
            'url'             => "management_rpd/signature_digital_proses/$signature/$url",
        ];

        $this->navbar($data);
        // $this->load->view('management_office/top_header', $data);
        $this->load->view('template_claim/top_header_signature');
        $this->load->view('kalimantan/header_full_width', $data);
        $this->load->view('management_rpd/signature_digital', $data);
        $this->load->view('kalimantan/footer');
    }
    

    public function signature_digital_proses(){
        
        $signature = $this->uri->segment('3');
        $url = $this->uri->segment('4');
        $folderPath = './assets/uploads/signature/';  
        $image_parts = explode(";base64,", $_POST['signed']);
        $image_type_aux = explode("image/", $image_parts[0]);
        $image_type = $image_type_aux[1];
        $image_base64 = base64_decode($image_parts[1]);

        $file = $folderPath . $this->session->userdata('username') . '-signature.' .$image_type;
        file_put_contents($file, $image_base64);
        redirect("management_rpd/$url/$signature");

    }

}
?>
