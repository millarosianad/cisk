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
        $this->load->helper(array('url', 'csv'));
        $this->load->model(array('model_outlet_transaksi', 'model_management_rpd'));
    }
    function index()
    {
        $this->dashboard();
    }

    public function dashboard(){
        $data = [
            'title'             => 'Dashboard',
            'get_pengajuan'   => $this->model_management_rpd->get_pengajuan(),
            'url'               => 'management_bonus/import_master_data'
        ];

        $this->load->view('management_rpd/top_header', $data);
        $this->load->view('kalimantan/header_full_width', $data);
        $this->load->view('management_report/dashboard', $data);
        $this->load->view('kalimantan/footer');
    }

    public function pengajuan(){

        $data = [
            'title'         => 'Rencana Perjalanan Dinas - Input Pengajuan',
            'get_pengajuan' => $this->model_management_rpd->get_pengajuan(),
            'url'           => 'management_rpd/pengajuan_tambah',
            'username'      => $this->session->userdata('username')
        ];

        $this->load->view('management_rpd/top_header', $data);
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

        $this->db->trans_start();

        $data = [
            'pelaksana'                 => $this->input->post('pelaksana'),
            'maksud_perjalanan_dinas'   => $this->input->post('maksud_perjalanan_dinas'),
            'tanggal_berangkat'         => $this->input->post('tanggal_berangkat'),
            'tempat_berangkat'          => $this->input->post('tempat_berangkat'),
            'tanggal_tiba'              => $this->input->post('tanggal_tiba'),
            'tempat_tiba'               => $this->input->post('tempat_tiba'),
            'created_at'                => $created_at,
            'created_by'                => $this->session->userdata('id'),
            'signature'                 => $signature
        ];

        $this->db->insert('management_rpd.pengajuan', $data);
        $id = $this->db->insert_id();
        // echo "id : ".$id;
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE)
        {
            echo "ada kegagalan dalam pengajuan rpd. Mungkin disebabkan internet. rollback diaktifkan ke keadaan sebelumnya";
            die;
        }

        $signature = $this->model_management_rpd->get_pengajuan($id)->row()->signature;
        
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

        $this->load->view('management_rpd/top_header', $data);
        $this->load->view('kalimantan/header_full_width', $data);
        $this->load->view('management_rpd/aktivitas', $data);
        $this->load->view('kalimantan/footer');

    }

    public function aktivitas_tambah(){
        $created_at = $this->model_outlet_transaksi->timezone();
        $signature_pengajuan = $this->input->post('signature_pengajuan');
        $signature = 'RPD-' . rand() . md5($this->model_outlet_transaksi->timezone()) . date('Ymd');

        $this->db->trans_start();

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
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE)
        {
            echo "ada kegagalan dalam penambahan aktivitas. Mungkin disebabkan internet. rollback diaktifkan ke keadaan sebelumnya";
            die;
        }
        redirect('management_rpd/aktivitas/'.$signature_pengajuan);
    }

    public function verifikasi_pengajuan(){
        
        $signature_pengajuan = $this->input->post('signature_pengajuan');

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
        echo "total_biaya : ".$total_biaya;
        if ($total_biaya >= 2000000) {
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

        $this->load->view('management_rpd/top_header', $data);
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
            "verifikasi1_by" => $this->session->userdata('id'),
            "verifikasi1_at" => $this->model_outlet_transaksi->timezone(),
            'verifikasi1_status'    => $status_verifikasi,
            'verifikasi1_name'  => $verifikasi_name,
            'status'    => $status,
            'nama_status'    => $nama_status,
            'verifikasi1_keterangan'    => $keterangan_verifikasi
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

        $this->load->view('management_rpd/top_header', $data);
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
        }

        $keterangan_verifikasi = $this->input->post('keterangan_verifikasi');
        $signature_pengajuan = $this->input->post('signature_pengajuan');

        $data = [
            "verifikasi2_by" => $this->session->userdata('id'),
            "verifikasi2_at" => $this->model_outlet_transaksi->timezone(),
            'verifikasi2_status'    => $status_verifikasi,
            'verifikasi2_name'  => $verifikasi_name,
            'status'    => $status,
            'nama_status'    => $nama_status,
            'verifikasi2_keterangan'    => $keterangan_verifikasi
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
        $email_cc = $this->model_management_rpd->get_user($created_by)->row()->email;

        $get_aktivitas = $this->model_management_rpd->get_aktivitas($id);
        $total_biaya = $this->model_management_rpd->get_total_biaya($id)->row()->total_biaya;

        $subject = "MPM Site | RPD : $no_rpd | VERIFIKASI 1";
        $this->load->model('model_relokasi');
        $this->model_relokasi->email();

        // $get_id_ref_relokasi_header = $this->model_relokasi->get_data_relokasi_header($signature)->row()->id;

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
        $this->email->cc($email_cc);
        $this->email->subject($subject);
        $this->email->message($message);
        $send = $this->email->send();
        // echo $this->email->print_debugger();
        if ($send) {
            echo "<script>alert('pengiriman email berhasil'); </script>";

            if ($jumlah_verifikasi == 1) {
                redirect('management_rpd/aktivitas/'.$signature_pengajuan,'refresh');

                die;
            }

            redirect('management_rpd/email_verifikasi2/'.$signature_pengajuan);
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
        }
        $get_aktivitas = $this->model_management_rpd->get_aktivitas($id);
        $total_biaya = $this->model_management_rpd->get_total_biaya($id)->row()->total_biaya;

        $from = "suffy@muliaputramandiri.net";
        // $to = 'suffy.yanuar@gmail.com';
        // $cc = 'suffy.mpm@gmail.com';

        $email_to = $this->model_management_rpd->get_user($userid_verifikasi2)->row()->email;
        $email_cc = $this->model_management_rpd->get_user($created_by)->row()->email;

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
        $this->email->cc($email_cc);
        $this->email->subject($subject);
        $this->email->message($message);
        $send = $this->email->send();
        // echo $this->email->print_debugger();
        if ($send) {
            echo "<script>alert('pengiriman email berhasil'); </script>";
            redirect('management_rpd/aktivitas/'.$signature_pengajuan,'refresh');
        }else{
            echo "<script>alert('pengiriman email gagal'); </script>";
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

        $this->load->view('management_rpd/top_header', $data);
        $this->load->view('kalimantan/header_full_width', $data);
        $this->load->view('management_rpd/realisasi', $data);
        $this->load->view('kalimantan/footer');
    }

    public function realisasi_proses(){

        $this->db->trans_start();

        $data = array();
        $count = count($this->input->post('keterangan_realisasi'));
        for($i=0; $i < $count; $i++) {

            $data = [
                'status_realisasi'  => $this->input->post('status_realisasi')[$i+1],
                'keterangan_realisasi'  => $this->input->post('keterangan_realisasi')[$i],
            ];
            $this->db->where('signature', $this->input->post('signature_aktivitas')[$i]);
            $this->db->update('management_rpd.aktivitas', $data);

        }

        $update = [
            'status_realisasi'  => 1,
            'realisasi_at'      => $this->model_outlet_transaksi->timezone()
        ];

        $this->db->where('signature', $this->input->post('signature_pengajuan'));
        $this->db->update('management_rpd.pengajuan', $update);


        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE)
        {
            echo "ada kegagalan dalam proses realisasi. Mungkin disebabkan internet. rollback diaktifkan ke keadaan sebelumnya";
            die;
        }

        redirect('management_rpd/pengajuan');

    }

    public function generate_pdf($signature){
        $this->load->library('mypdf');

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
        // die;
        $total_biaya = $this->model_management_rpd->get_total_biaya($id)->row()->total_biaya;

        $data = [
            'title'                     => 'Rencana Perjalanan Dinas',
            'no_rpd'                    => $no_rpd,
            'pelaksana'                 => $pelaksana,
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
        ];

        $filename_pdf = $no_rpd;

        $generate_pdf = $this->mypdf->generate('management_rpd/template_rpd',$data,$filename_pdf,'A4','landscape');

    }


}
?>
