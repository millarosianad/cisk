<?php

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Management_inventory extends MY_Controller
{    
    function management_inventory()
    {       
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login_sistem/','refresh');
        }
        set_time_limit(0);

        $this->load->library(array('table', 'template', 'Excel_generator', 'form_validation', 'email', 'zip'));
        $this->load->helper(array('url', 'csv'));
        $this->load->model(array('model_outlet_transaksi','model_retur', 'model_management_inventory', 'model_inventory', 'M_helpdesk'));

        // if ($this->session->userdata('username') != 'suffy' && $this->session->userdata('username') != 'linda') { // jika dp
        //     redirect('management_office');
        //     die;
        // }

        // cek traffic
        $traffic = $this->model_management_inventory->get_traffic();
        if($traffic->num_rows() > 0){
            $status_generate = $traffic->row()->status_generate;
            $created_at = $traffic->row()->created_at;

            // date_default_timezone_set('Asia/Jakarta');
            $waktu_awal  =strtotime($created_at);
            $waktu_akhir =strtotime(date('Y-m-d H:i:s')); // bisa juga waktu sekarang now()
            
            // echo "waktu_awal : ".$waktu_awal;
            // echo "<br>";
            // echo "waktu_akhir : ".$waktu_akhir;
            
            //menghitung selisih dengan hasil detik
            $diff    =$waktu_akhir - $waktu_awal;
            if ($diff > 300) {
                $this->model_management_inventory->insert_traffic($this->session->userdata('username'), $this->session->userdata('id'), 0);
            }   
        }

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
        }else{
            $this->load->view('management_office/top_header', $data);
        }
    }

    public function dashboard(){

        if ($this->session->userdata('level') == 4) { // jika dp
            redirect('management_inventory/pengajuan_retur');
            die;
        }else if ($this->session->userdata('level') == 3) { // jika principal
            
            $get_kode_alamat = $this->model_management_inventory->get_principal_akses($this->session->userdata('id'));
            $code = '';
            foreach ($get_kode_alamat->result() as $key) {
                $code.= ","."'".$key->site_code."'";
            }
            $kode_alamat = preg_replace('/,/', '', $code,1);

        }else{

            $get_kode_alamat = $this->model_inventory->get_kode_alamat();
            $code = '';
            foreach ($get_kode_alamat as $key) {
                $code.= ","."'".$key->kode_alamat."'";
            }
            $kode_alamat = preg_replace('/,/', '', $code,1);
;
        }

        if($this->input->get('from')){
            
            $advanced['from']   = $this->input->get('from');
            $advanced['to']     = $this->input->get('to');
            $advanced['status'] = $this->input->get('status');
            $advanced['type']   = $this->input->get('type');

            if ($this->input->get('type') == 2) {
                // echo "ini export : ".$this->input->get('type');
                $this->export_by_date_status($this->input->get('from'), $this->input->get('to'), $this->input->get('status'));
                die;
            }
        
        }else{
            $advanced = "";
        }

        $data = [
            'title'                     => 'Pengajuan Retur - Dashboard',
            // 'get_pengajuan_by_status'   => $this->model_management_inventory->get_pengajuan_breakdown('status'),
            // 'get_pengajuan_by_site_code'=> $this->model_management_inventory->get_pengajuan_breakdown('site_code'),
            'get_pengajuan'             => $this->model_management_inventory->get_pengajuan("", $kode_alamat, $advanced),
            'url'                       => 'management_inventory/pengajuan_retur_proses',
            'url_search'                => '',
            'url_override'              => 'management_inventory/override_status',
            'site_code'                 => $this->model_management_inventory->get_sitecode(),
        ];

        $this->navbar($data);

        $this->load->view('kalimantan/header_full_width', $data);
        $this->load->view('management_inventory/dashboard', $data);
        $this->load->view('kalimantan/footer');
    }

    public function override_status(){

        $updated_at = $this->model_outlet_transaksi->timezone();

        $userid = $this->session->userdata('id');
        $username = $this->session->userdata('username');
        if ($username != 'linda' && $username != 'suffy' && $username != 'melinda' ) {
            $this->session->set_flashdata("pesan", "anda tidak diijinkan melakukan proses ini");
            redirect('management_inventory/dashboard');
            die;
        }

        $id = $this->input->post('id');
        $status = $this->input->post('status');

        // echo "id : ".$id;
        // echo "status : ".$status;
        // die;

        if ($status == 1) { // jika memilih pending dp
            $data = [
                'tanggal_pengajuan' => NULL,
                'status'            => $status,
                'nama_status'       => $this->model_management_inventory->get_nama_status_by_id($status),
                'last_updated'      => $updated_at,
                'last_updated_by'   => $userid
            ];

            $this->db->where('id', $id);
            $this->db->update('management_inventory.pengajuan_retur', $data);
            $this->session->set_flashdata("pesan_success", "update data berhasil");
            redirect('management_inventory/dashboard');
            die;
        }elseif ($status == 2) { // jika memilih pending mpm
            $data = [
                'status'            => $status,
                'nama_status'       => $this->model_management_inventory->get_nama_status_by_id($status),
                'verifikasi_at'     => NULL,
                'verifikasi_by'     => NULL,
                'last_updated'      => $updated_at,
                'last_updated_by'   => $userid
            ];

            $this->db->where('id', $id);
            $this->db->update('management_inventory.pengajuan_retur', $data);
            $this->session->set_flashdata("pesan_success", "update data berhasil");
            redirect('management_inventory/dashboard');
            die;
        }elseif ($status == 3) { // jika memilih pending principal area
            $data = [
                'status'            => $status,
                'nama_status'       => $this->model_management_inventory->get_nama_status_by_id($status),
                'principal_area_at' => NULL,
                'principal_area_by'=> NULL,
                'last_updated'      => $updated_at,
                'last_updated_by'   => $userid
            ];

            $this->db->where('id', $id);
            $this->db->update('management_inventory.pengajuan_retur', $data);
            $this->session->set_flashdata("pesan_success", "update data berhasil");
            redirect('management_inventory/dashboard');
            die;
        }elseif ($status == 4) { // jika memilih pending principal ho
            $data = [
                'status'            => $status,
                'nama_status'       => $this->model_management_inventory->get_nama_status_by_id($status),
                'principal_ho_at'   => NULL,
                'principal_ho_by'   => NULL,
                'last_updated'      => $updated_at,
                'last_updated_by'   => $userid
            ];

            $this->db->where('id', $id);
            $this->db->update('management_inventory.pengajuan_retur', $data);
            $this->session->set_flashdata("pesan_success", "update data berhasil");
            redirect('management_inventory/dashboard');
            die;
        }elseif ($status == 5) { // jika memilih pending kirim barang
            $data = [
                'status'                => $status,
                'nama_status'           => $this->model_management_inventory->get_nama_status_by_id($status),
                'tanggal_kirim_barang'  => NULL,
                'nama_ekspedisi'        => NULL,
                'est_tanggal_tiba'      => NULL,
                'proses_kirim_barang_at'=> NULL,
                'proses_kirim_barang_by'=> NULL,
                'file_pengiriman'       => NULL,
                'nama_ekspedisi'        => NULL,
                'last_updated'          => $updated_at,
                'last_updated_by'       => $userid
            ];

            $this->db->where('id', $id);
            $this->db->update('management_inventory.pengajuan_retur', $data);
            $this->session->set_flashdata("pesan_success", "update data berhasil");
            redirect('management_inventory/dashboard');
            die;
        }elseif ($status == 6) { // jika memilih pending terima barang
            $data = [
                'status'                => $status,
                'nama_status'           => $this->model_management_inventory->get_nama_status_by_id($status),
                'tanggal_terima_barang' => NULL,
                'nama_penerima'         => NULL,
                'no_terima_barang'      => NULL,
                'file_terima_barang'    => NULL,
                'terima_barang_at'      => NULL,
                'terima_barang_by'      => NULL,
                'last_updated'          => $updated_at,
                'last_updated_by'       => $userid
            ];

            $this->db->where('id', $id);
            $this->db->update('management_inventory.pengajuan_retur', $data);
            $this->session->set_flashdata("pesan_success", "update data berhasil");
            redirect('management_inventory/dashboard');
            die;
        }elseif ($status == 7) { // jika memilih pending pemusnahan
            $data = [
                'status'                => $status,
                'nama_status'           => $this->model_management_inventory->get_nama_status_by_id($status),
                'tanggal_pemusnahan'    => NULL,
                'nama_pemusnahan'       => NULL,
                'file_pemusnahan'       => NULL,
                'foto_pemusnahan_1'     => NULL,
                'foto_pemusnahan_2'     => NULL,
                'video'                 => NULL,
                'pemusnahan_at'         => NULL,
                'pemusnahan_by'         => NULL,
                'last_updated'          => $updated_at,
                'last_updated_by'       => $userid
            ];

            $this->db->where('id', $id);
            $this->db->update('management_inventory.pengajuan_retur', $data);
            $this->session->set_flashdata("pesan_success", "update data berhasil");
            redirect('management_inventory/dashboard');
            die;
        }elseif ($status == 12) { // jika memilih pending principal ho
            $data = [
                'deleted'           => 1,
                'last_updated'      => $updated_at,
                'last_updated_by'   => $userid
            ];

            $this->db->where('id', $id);
            $this->db->update('management_inventory.pengajuan_retur', $data);
            $this->session->set_flashdata("pesan_success", "update data berhasil");
            redirect('management_inventory/dashboard');
            die;
        }else{
            $this->session->set_flashdata("pesan", "proses anda gagal. Status yang anda pilih belum tersedia saat ini");
            redirect('management_inventory/dashboard');
            die;
        }

    }

    public function export_by_date_status($from, $to, $status){

        if ($this->session->userdata('level') == 4) { // jika dp
            redirect('management_inventory/pengajuan_retur');
            die;
        }else if ($this->session->userdata('level') == 3) { // jika principal
            
            $get_kode_alamat = $this->model_management_inventory->get_principal_akses($this->session->userdata('id'));
            $code = '';
            foreach ($get_kode_alamat->result() as $key) {
                $code.= ","."'".$key->site_code."'";
            }
            $kode_alamat = preg_replace('/,/', '', $code,1);

        }else{

            $get_kode_alamat = $this->model_inventory->get_kode_alamat();
            $code = '';
            foreach ($get_kode_alamat as $key) {
                $code.= ","."'".$key->kode_alamat."'";
            }
            $kode_alamat = preg_replace('/,/', '', $code,1);

        }
        

        if ($status == 0) {
            $params_status = "";
        }else{
            $params_status = "and a.status = '$status'";
        }

        $supp = $this->session->userdata('supp');
        if ($supp == '000') {
            $params_supp = "";
        }else{
            $params_supp = "and a.supp = '$supp'";
        }

        $query = "
        select 	a.nama_status, a.no_pengajuan, a.site_code, 
                if(d.branch_name is null,i.name,d.branch_name) as branch_name, 
			    if(d.nama_comp is null, i.company, d.nama_comp) as nama_comp, 
                e.namasupp, date(a.tanggal_pengajuan) as tanggal_pengajuan, 
                b.kodeprod, c.namaprod, b.jumlah as qty_pengajuan, b.qty_approval, b.satuan, b.nama_outlet, b.keterangan, b.keterangan_principal_area, b.alasan, 
                a.principal_area_at, f.username as principal_area_name, f.email as principal_area_email,
                a.verifikasi_at, g.username as mpm_name, g.email as mpm_email, 
                a.principal_ho_at, h.username as principal_ho_name, h.email as principal_ho_email,
                a.tanggal_kirim_barang, a.nama_ekspedisi, a.est_tanggal_tiba, a.proses_kirim_barang_at,
                a.tanggal_terima_barang, a.nama_penerima, a.no_terima_barang, a.terima_barang_at,
                a.tanggal_pemusnahan, a.pemusnahan_at
        from management_inventory.pengajuan_retur a INNER JOIN 
        (
            select a.id_pengajuan, a.kodeprod, a.namaprod, a.jumlah, a.satuan, a.qty_approval, a.nama_outlet, a.keterangan, a.keterangan_principal_area, a.alasan
            from management_inventory.pengajuan_retur_detail a 
            where a.deleted is null 
        )b on a.id = id_pengajuan LEFT JOIN 
        (
            select a.kodeprod, a.namaprod
            from mpm.tabprod a
        )c on b.kodeprod = c.kodeprod LEFT JOIN 
        (
            select concat(a.kode_comp, a.nocab) as site_code, a.branch_name, a.nama_comp
            from mpm.tbl_tabcomp a 
            where a.status = 1
            GROUP BY concat(a.kode_comp, a.nocab)
        )d on a.site_code = d.site_code LEFT JOIN (
            select a.supp, a.namasupp
            from mpm.tabsupp a 
            union all 
            select '001-herbal' as supp, 'DELTOMED_HERBAL' as namasupp
            union all 
            select '001-herbana' as supp, 'DELTOMED_HERBANA' as namasupp
            union all 
            select '001-GT' as supp, 'DELTOMED-GT' as namasupp
            union all 
            select '001-MTI' as supp, 'DELTOMED-MTI' as namasupp
            union all 
            select '001-NKA' as supp, 'DELTOMED-NKA' as namasupp
            union all 
            select '001-GT-PHARMA' as supp, 'DELTOMED-GT-PHARMA' as namasupp
        )e on a.supp = e.supp LEFT JOIN 
        (
            select a.id, a.username, a.email
            from mpm.user a 
        )f on a.principal_area_by = f.id LEFT JOIN 
        (
            select a.id, a.username, a.email
            from mpm.user a 
        )g on a.verifikasi_by = g.id LEFT JOIN 
        (
            select a.id, a.username, a.email
            from mpm.user a 
        )h on a.principal_ho_by = h.id LEFT JOIN
		(
			select a.id, a.username, a.name, a.company, a.kode_alamat
			from mpm.user a 
		)i on a.site_code = i.kode_alamat
        where a.tanggal_pengajuan between '$from 00:00:00' and '$to 23:59:59' $params_status and a.site_code in ($kode_alamat) $params_supp
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        // die;

        $hsl = $this->db->query($query);

        query_to_csv($hsl,TRUE,'Export.csv');
    }

    public function pengajuan_retur(){

        if ($this->session->userdata('level') == 3) { // jika dp
            redirect('management_inventory');
            die;
        }

        

        $get_kode_alamat = $this->model_inventory->get_kode_alamat();
        $code = '';
        foreach ($get_kode_alamat as $key) {
            $code.= ","."'".$key->kode_alamat."'";
        }
        $kode_alamat = preg_replace('/,/', '', $code,1);
        // echo "kode_alamat : ".$kode_alamat;
        // die;

        $get_mpi = $this->session->userdata('username');        
        if(substr($get_mpi,0,3) == 'MPI'){
            $status_mpi = 1;
        }else{
            $status_mpi = 0;
        }

        $data = [
            'title'           => 'Pengajuan Retur',
            'get_pengajuan'   => $this->model_management_inventory->get_pengajuan("", $kode_alamat),
            'url'             => 'management_inventory/pengajuan_retur_proses',
            'status_mpi'      => $status_mpi,
            'site_code'       => $this->model_management_inventory->get_sitecode(),
        ];

        
        $this->navbar($data);

        $this->load->view('kalimantan/header_full_width', $data);
        $this->load->view('management_inventory/pengajuan_retur', $data);
        $this->load->view('kalimantan/footer');
    }

    public function pengajuan_retur_proses(){
        // $site_code = $this->input->post('site_code');
        // $supp = $this->input->post('supp');
        // $nama = $this->input->post('nama');
        $file = $this->input->post('file');        
        $created_at = $this->model_outlet_transaksi->timezone();
        $signature = 'RTR-' . rand() . md5($this->model_outlet_transaksi->timezone()) . date('Ymd');

        if (!is_dir('./assets/file/retur/')) {
            @mkdir('./assets/file/retur/', 0777);
        }

        $config['upload_path'] = './assets/file/retur/';
        $config['allowed_types'] = '*';
        $config['max_size'] = '*';

        $this->load->library('upload', $config);
        if (!$this->upload->do_upload('file')) {
            $filename = '';
        } else {

            $data = array('upload_data' => $this->upload->data());
            $file = $data['upload_data']['full_path'];
            chmod($file, 0777);
            $upload_data = $this->upload->data();
            $filename = $upload_data['file_name'];
        }

        $file = './assets/uploads/signature/'.$this->session->userdata('username').'-signature.png'; // 'images/'.$file (physical path)
        if (file_exists($file)) {
            $digital_signature = $this->session->userdata('username').'-signature.png';
        }else{
            $digital_signature = '';
        }

        // $this->db->trans_start();

        $data = [
            'site_code'         => $this->input->post('site_code'), 
            'file'              => $filename,
            'supp'              => $this->input->post('supp'),
            'nama'              => $this->input->post('nama'),
            'tipe'              => $this->input->post('tipe'),
            'status'            => '1',
            'nama_status'       => 'PENDING DP',
            // 'tanggal_pengajuan' => $created_at,
            'digital_signature' => $digital_signature,
            'created_at'        => $created_at,
            'created_by'        => $this->session->userdata('id'),
            'signature'         => $signature,
        ];

        $this->db->insert('management_inventory.pengajuan_retur', $data);
        
        // $this->db->trans_complete();
        // if ($this->db->trans_status() === FALSE)
        // {
        //     echo "ada kegagalan dalam pengajuan retur. Mungkin disebabkan internet. rollback diaktifkan ke keadaan sebelumnya";
        //     die;
        // }

        redirect('management_inventory/pengajuan_retur_detail/'.$signature.'/'.$this->input->post('supp'));

    }

    public function pengajuan_retur_detail($signature, $supp){

        $get_pengajuan = $this->model_management_inventory->get_pengajuan($signature);
        foreach ($get_pengajuan->result() as $a) {
            $id_pengajuan               = $a->id;
            $tipe                       = $a->tipe;
            $site_code                  = $a->site_code;
            $branch_name                = $a->branch_name;
            $nama_comp                  = $a->nama_comp;
            $supp                       = $a->supp;
            $namasupp                   = $a->namasupp;
            $no_pengajuan               = $a->no_pengajuan;
            $tanggal_pengajuan          = $a->tanggal_pengajuan;
            $nama                       = $a->nama;
            $status                     = $a->status;
            $nama_status                = $a->nama_status;
            $no_pengajuan               = $a->no_pengajuan;
            $created_at                 = $a->created_at;
            $file                       = $a->file;
            $digital_signature          = $a->digital_signature;
            $signature                  = $a->signature;
            $verifikasi_at              = $a->verifikasi_at;
            $verifikasi_username        = $a->verifikasi_username;
            $verifikasi_signature       = $a->verifikasi_signature;
            $principal_area_at          = $a->principal_area_at;
            $principal_area_signature   = $a->principal_area_signature;
            $principal_area_username    = $a->principal_area_username;
            $file_principal_area        = $a->file_principal_area;
            $catatan_principal_area     = $a->catatan_principal_area;
            $principal_ho_at            = $a->principal_ho_at;
            $principal_ho_signature     = $a->principal_ho_signature;
            $principal_ho_username      = $a->principal_ho_username;
            $file_principal_ho          = $a->file_principal_ho;
            $catatan_principal_ho       = $a->catatan_principal_ho;
            $tanggal_kirim_barang       = $a->tanggal_kirim_barang;
            $nama_ekspedisi             = $a->nama_ekspedisi;
            $est_tanggal_tiba           = $a->est_tanggal_tiba;
            $file_pengiriman            = $a->file_pengiriman;
            $proses_kirim_barang_at     = $a->proses_kirim_barang_at;
            $username_kirim_barang      = $a->username_kirim_barang;
            $tanggal_terima_barang      = $a->tanggal_terima_barang;
            $nama_penerima              = $a->nama_penerima;
            $no_terima_barang           = $a->no_terima_barang;
            $file_terima_barang         = $a->file_terima_barang;
            $terima_barang_at           = $a->terima_barang_at;
            $username_terima_barang     = $a->username_terima_barang;
            $tanggal_pemusnahan         = $a->tanggal_pemusnahan;
            $nama_pemusnahan            = $a->nama_pemusnahan;
            $file_pemusnahan            = $a->file_pemusnahan;
            $foto_pemusnahan_1          = $a->foto_pemusnahan_1;
            $foto_pemusnahan_2          = $a->foto_pemusnahan_2;
            $pemusnahan_at              = $a->pemusnahan_at;
            $username_pemusnahan        = $a->username_pemusnahan;
            $video                      = $a->video;
        }

        $get_pengajuan_detail_summary = $this->model_management_inventory->get_pengajuan_detail_summary($id_pengajuan);
        foreach ($get_pengajuan_detail_summary->result() as $a) {
            $count_kodeprod = $a->count_kodeprod;
            $value_rbp = $a->value_rbp;
            $sum_qty_pengajuan = $a->sum_qty_pengajuan;
        }
        $data = [
            'title'                      => 'Pending DP',
            'url'                        => 'management_inventory/pengajuan_retur_detail_proses',
            'url_import'                 => 'management_inventory/pengajuan_retur_detail_import',
            'get_pengajuan_detail_accordion'       => $this->model_management_inventory->get_pengajuan_detail_accordion($id_pengajuan),
            // 'url_pengajuan'              => 'management_inventory/proses_mpm',
            // 'url_pengajuan'              => 'management_inventory/preview_pengajuan_retur',
            'url_pengajuan'              => 'management_inventory/bridging_to_principal_area',
            'get_pengajuan_detail'       => $this->model_management_inventory->get_pengajuan_detail($id_pengajuan),
            'id_pengajuan'               => $id_pengajuan,
            'tipe'                       => $tipe,
            'site_code'                  => $site_code,
            'branch_name'                => $branch_name,
            'nama_comp'                  => $nama_comp,
            'supp'                       => $supp,
            'namasupp'                   => $namasupp,
            'no_pengajuan'               => $no_pengajuan,
            'tanggal_pengajuan'          => $tanggal_pengajuan,
            'nama'                       => $nama,
            'status'                     => $status,
            'nama_status'                => $nama_status,
            'no_pengajuan'               => $no_pengajuan,
            'created_at'                 => $created_at,
            'file'                       => $file,
            'digital_signature'          => $digital_signature,
            'signature'                  => $signature,
            'verifikasi_at'              => $verifikasi_at,
            'verifikasi_username'        => $verifikasi_username,
            'verifikasi_signature'       => $verifikasi_signature,
            'principal_area_at'          => $principal_area_at,
            'principal_area_signature'   => $principal_area_signature,
            'principal_area_username'    => $principal_area_username,
            'file_principal_area'        => $file_principal_area,
            'catatan_principal_area'     => $catatan_principal_area,
            'principal_ho_at'            => $principal_ho_at,
            'principal_ho_signature'     => $principal_ho_signature,
            'principal_ho_username'      => $principal_ho_username,
            'file_principal_ho'          => $file_principal_ho,
            'catatan_principal_ho'       => $catatan_principal_ho,
            'tanggal_kirim_barang'       => $tanggal_kirim_barang,
            'nama_ekspedisi'             => $nama_ekspedisi,
            'est_tanggal_tiba'           => $est_tanggal_tiba,
            'file_pengiriman'            => $file_pengiriman,
            'proses_kirim_barang_at'     => $proses_kirim_barang_at,
            'username_kirim_barang'      => $username_kirim_barang,
            'tanggal_terima_barang'      => $tanggal_terima_barang,
            'nama_penerima'              => $nama_penerima,
            'no_terima_barang'           => $no_terima_barang,
            'file_terima_barang'         => $file_terima_barang,
            'terima_barang_at'           => $terima_barang_at,
            'username_terima_barang'     => $username_terima_barang,
            'count_kodeprod'             => $count_kodeprod,
            'sum_qty_pengajuan'          => $sum_qty_pengajuan,
            'value_rbp'                  => $value_rbp,
            'tanggal_pemusnahan'         => $tanggal_pemusnahan,
            'nama_pemusnahan'            => $nama_pemusnahan,
            'file_pemusnahan'            => $file_pemusnahan,
            'foto_pemusnahan_1'          => $foto_pemusnahan_1,
            'foto_pemusnahan_2'          => $foto_pemusnahan_2,
            'pemusnahan_at'              => $pemusnahan_at,
            'username_pemusnahan'        => $username_pemusnahan,
            'video'                      => $video,
        ];

        $this->navbar($data);
        // $this->load->view('management_office/top_header', $data);
        $this->load->view('kalimantan/header_full_width', $data);
        $this->load->view('kalimantan/accordion', $data);
        $this->load->view('management_inventory/pengajuan_retur_detail', $data);
        $this->load->view('kalimantan/footer');
    }

    public function delete_product($signature_detail, $supp, $signature){
        $data = [
            "deleted"    => 1,
        ];

        $this->db->where('signature', $signature_detail);
        $this->db->update('management_inventory.pengajuan_retur_detail', $data);

        redirect('management_inventory/pengajuan_retur_detail/'.$signature.'/'.$supp);

    }

    public function pengajuan_retur_detail_proses(){
        
        $signature_detail = 'RTR-' . rand() . md5($this->model_outlet_transaksi->timezone()) . date('Ymd');

        // $this->db->trans_start();

        $data = [
            "id_pengajuan"      => $this->input->post('id_pengajuan'),
            "kodeprod"          => $this->input->post('kodeprod'),
            "batch_number"      => $this->input->post('batch_number'),
            "satuan"            => $this->input->post('satuan'),
            "expired_date"      => $this->input->post('ed'),
            "jumlah"            => $this->input->post('jumlah'),
            "nama_outlet"       => $this->input->post('nama_outlet'),
            "alasan"            => $this->input->post('alasan_retur'),
            "keterangan"        => $this->input->post('keterangan'),
            "created_at"        => $this->model_outlet_transaksi->timezone(),
            "created_by"        => $this->session->userdata('id'),
            "signature"         => $signature_detail
        ];

        $this->db->insert('management_inventory.pengajuan_retur_detail', $data);
        
        // $this->db->trans_complete();
        // if ($this->db->trans_status() === FALSE)
        // {
        //     echo "ada kegagalan dalam penginputan produk retur. Mungkin disebabkan internet. rollback diaktifkan ke keadaan sebelumnya";
        //     die;
        // }

        redirect('management_inventory/pengajuan_retur_detail/'.$this->input->post('signature').'/'.$this->input->post('supp'));

    }   

    public function proses_mpm(){
        $signature = $this->input->post('signature');
        $created_at = $this->model_outlet_transaksi->timezone();

        // $this->db->trans_start();

        $get_no_pengajuan = $this->model_management_inventory->get_pengajuan($signature)->row()->no_pengajuan;
        if ($get_no_pengajuan == '' || $get_no_pengajuan == NULL) {
            $no_ajuan = $this->model_management_inventory->generate($created_at);
        }else{
            $no_ajuan = $get_no_pengajuan;
        }

        $data = [
            "status"        => 2,
            "nama_status"   => "PROSES MPM",
            "nama_status"   => "PROSES MPM",
            "no_pengajuan"  => $no_ajuan
        ];

        $this->db->where("signature", $signature);
        $this->db->update("management_inventory.pengajuan_retur", $data);

        // $this->db->trans_complete();
        // if ($this->db->trans_status() === FALSE)
        // {
        //     echo "ada kegagalan dalam pengajuan ke mpm. Pengajuan BATAL. Mungkin disebabkan internet. rollback diaktifkan ke keadaan sebelumnya";
        //     die;
        // }

        redirect('management_inventory/email_pengajuan/'.$signature);
        die;

    }

    public function email_pengajuan($signature){
        $get_pengajuan = $this->model_management_inventory->get_pengajuan($signature);
        foreach ($get_pengajuan->result() as $a) {
            $id_pengajuan = $a->id;
            $site_code = $a->site_code;
            $branch_name = $a->branch_name;
            $nama_comp = $a->nama_comp;
            $supp = $a->supp;
            $namasupp = $a->namasupp;
            $no_pengajuan = $a->no_pengajuan;
            $tanggal_pengajuan = $a->tanggal_pengajuan;
            $nama = $a->nama;
            $status = $a->status;
            $nama_status = $a->nama_status;
            $no_pengajuan = $a->no_pengajuan;
            $created_by = $a->created_by;
            $file = $a->file;
            $signature = $a->signature;
        }

        $get_pengajuan_detail_summary = $this->model_management_inventory->get_pengajuan_detail_summary($id_pengajuan);
        foreach ($get_pengajuan_detail_summary->result() as $a) {
            $count_kodeprod = $a->count_kodeprod;
            $value_rbp = $a->value_rbp;
        }

        $data = [
            'get_pengajuan_detail'  => $this->model_management_inventory->get_pengajuan_detail($id_pengajuan),
            'no_pengajuan'    => $no_pengajuan,
            'branch_name'     => $branch_name,
            'nama_comp'       => $nama_comp,
            'site_code'       => $site_code,
            'namasupp'        => $namasupp,
            'tanggal_pengajuan'=> $tanggal_pengajuan,
            'nama'            => $nama,
            'status'          => $status,
            'nama_status'     => $nama_status,
            'created_by'      => $created_by,
            'file'            => $file,
            'id_pengajuan'    => $id_pengajuan,
            'supp'            => $supp,
            'signature'       => $signature,
            'count_kodeprod'  => $count_kodeprod,
            'value_rbp'       => $value_rbp,
            
        ];

        $from = "suffy@muliaputramandiri.com";
        $to = 'suffy.yanuar@gmail.com';
        $cc = 'suffy.mpm@gmail.com';

        $email_cc = $this->model_management_inventory->get_email($site_code)->row()->email.' ,linda@muliaputramandiri.com, fakhrul@muliaputramandiri.com';

        $subject = "MPM SITE | RETUR : $no_pengajuan | PROSES PRINCIPAL HO";
        $this->load->model('model_relokasi');
        $this->model_relokasi->email();

        $message = $this->load->view("management_inventory/email_pengajuan",$data,TRUE);

        $this->email->from($from,'PT. Mulia Putra Mandiri');
        $this->email->to($to);
        $this->email->cc($email_cc);
        $this->email->subject($subject);
        $this->email->message($message);
        $send = $this->email->send();
        // echo $this->email->print_debugger();
        if ($send) {
            echo "<script>alert('pengiriman email berhasil'); </script>";
            redirect('management_inventory/pengajuan_retur_detail/'.$signature.'/'.$supp);
        }else{
            echo "<script>alert('pengiriman email gagal'); </script>";
            redirect('management_inventory/pengajuan_retur_detail/'.$signature.'/'.$supp);
        }
    }

    public function verifikasi_retur($signature, $supp){

        $get_pengajuan = $this->model_management_inventory->get_pengajuan($signature, "");
        foreach ($get_pengajuan->result() as $a) {
            $id_pengajuan               = $a->id;
            $tipe                       = $a->tipe;
            $site_code                  = $a->site_code;
            $branch_name                = $a->branch_name;
            $nama_comp                  = $a->nama_comp;
            $supp                       = $a->supp;
            $namasupp                   = $a->namasupp;
            $no_pengajuan               = $a->no_pengajuan;
            $tanggal_pengajuan          = $a->tanggal_pengajuan;
            $nama                       = $a->nama;
            $status                     = $a->status;
            $nama_status                = $a->nama_status;
            $no_pengajuan               = $a->no_pengajuan;
            $created_at                 = $a->created_at;
            $file                       = $a->file;
            $digital_signature          = $a->digital_signature;
            $signature                  = $a->signature;
            $verifikasi_at              = $a->verifikasi_at;
            $verifikasi_username        = $a->verifikasi_username;
            $verifikasi_signature       = $a->verifikasi_signature;
            $principal_area_at          = $a->principal_area_at;
            $principal_area_signature   = $a->principal_area_signature;
            $principal_area_username    = $a->principal_area_username;
            $file_principal_area        = $a->file_principal_area;
            $catatan_principal_area     = $a->catatan_principal_area;
            $principal_ho_at            = $a->principal_ho_at;
            $principal_ho_signature     = $a->principal_ho_signature;
            $principal_ho_username      = $a->principal_ho_username;
            $file_principal_ho          = $a->file_principal_ho;
            $catatan_principal_ho       = $a->catatan_principal_ho;
            $tanggal_kirim_barang       = $a->tanggal_kirim_barang;
            $nama_ekspedisi             = $a->nama_ekspedisi;
            $est_tanggal_tiba           = $a->est_tanggal_tiba;
            $file_pengiriman            = $a->file_pengiriman;
            $proses_kirim_barang_at     = $a->proses_kirim_barang_at;
            $username_kirim_barang      = $a->username_kirim_barang;
            $tanggal_terima_barang      = $a->tanggal_terima_barang;
            $nama_penerima              = $a->nama_penerima;
            $no_terima_barang           = $a->no_terima_barang;
            $file_terima_barang         = $a->file_terima_barang;
            $terima_barang_at           = $a->terima_barang_at;
            $username_terima_barang     = $a->username_terima_barang;
            $tanggal_pemusnahan         = $a->tanggal_pemusnahan;
            $nama_pemusnahan            = $a->nama_pemusnahan;
            $file_pemusnahan            = $a->file_pemusnahan;
            $foto_pemusnahan_1          = $a->foto_pemusnahan_1;
            $foto_pemusnahan_2          = $a->foto_pemusnahan_2;
            $pemusnahan_at              = $a->pemusnahan_at;
            $username_pemusnahan        = $a->username_pemusnahan;
            $video                      = $a->video;
        }

        $get_pengajuan_detail_summary = $this->model_management_inventory->get_pengajuan_detail_summary($id_pengajuan);
        foreach ($get_pengajuan_detail_summary->result() as $a) {
            $count_kodeprod = $a->count_kodeprod;
            $value_rbp = $a->value_rbp;
        }

        $data = [
            'title'           => 'Pending MPM',
            'url'             => 'management_inventory/verifikasi_retur_proses',
            'url_pengajuan'   => 'management_inventory/proses_mpm',


            'get_pengajuan_detail'       => $this->model_management_inventory->get_pengajuan_detail($id_pengajuan),
            'get_pengajuan_detail_accordion'       => $this->model_management_inventory->get_pengajuan_detail_accordion($id_pengajuan),
            // 'site_code'                  => $this->model_management_inventory->get_sitecode(),
            'id_pengajuan'               => $id_pengajuan,
            'tipe'                       => $tipe,
            'site_code'                  => $site_code,
            'branch_name'                => $branch_name,
            'nama_comp'                  => $nama_comp,
            'supp'                       => $supp,
            'namasupp'                   => $namasupp,
            'no_pengajuan'               => $no_pengajuan,
            'tanggal_pengajuan'          => $tanggal_pengajuan,
            'nama'                       => $nama,
            'status'                     => $status,
            'nama_status'                => $nama_status,
            'no_pengajuan'               => $no_pengajuan,
            'created_at'                 => $created_at,
            'file'                       => $file,
            'digital_signature'          => $digital_signature,
            'signature'                  => $signature,
            'verifikasi_at'              => $verifikasi_at,
            'verifikasi_username'        => $verifikasi_username,
            'verifikasi_signature'       => $verifikasi_signature,
            'principal_area_at'          => $principal_area_at,
            'principal_area_signature'   => $principal_area_signature,
            'principal_area_username'    => $principal_area_username,
            'file_principal_area'        => $file_principal_area,
            'catatan_principal_area'     => $catatan_principal_area,
            'principal_ho_at'            => $principal_ho_at,
            'principal_ho_signature'     => $principal_ho_signature,
            'principal_ho_username'      => $principal_ho_username,
            'file_principal_ho'          => $file_principal_ho,
            'catatan_principal_ho'       => $catatan_principal_ho,
            'tanggal_kirim_barang'       => $tanggal_kirim_barang,
            'nama_ekspedisi'             => $nama_ekspedisi,
            'est_tanggal_tiba'           => $est_tanggal_tiba,
            'file_pengiriman'            => $file_pengiriman,
            'proses_kirim_barang_at'     => $proses_kirim_barang_at,
            'username_kirim_barang'      => $username_kirim_barang,
            'tanggal_terima_barang'      => $tanggal_terima_barang,
            'nama_penerima'              => $nama_penerima,
            'no_terima_barang'           => $no_terima_barang,
            'file_terima_barang'         => $file_terima_barang,
            'terima_barang_at'           => $terima_barang_at,
            'username_terima_barang'     => $username_terima_barang,
            'count_kodeprod'             => $count_kodeprod,
            'value_rbp'                  => $value_rbp,
            'tanggal_pemusnahan'         => $tanggal_pemusnahan,
            'nama_pemusnahan'            => $nama_pemusnahan,
            'file_pemusnahan'            => $file_pemusnahan,
            'foto_pemusnahan_1'          => $foto_pemusnahan_1,
            'foto_pemusnahan_2'          => $foto_pemusnahan_2,
            'pemusnahan_at'              => $pemusnahan_at,
            'username_pemusnahan'        => $username_pemusnahan,
            'video'                      => $video,
        ];

        // $this->load->view('management_office/top_header', $data);

        $this->navbar($data);

        $this->load->view('kalimantan/header_full_width', $data);
        $this->load->view('kalimantan/accordion', $data);
        $this->load->view('management_inventory/verifikasi_retur_detail', $data);
        $this->load->view('kalimantan/footer');
    }

    public function verifikasi_retur_proses(){
        $signature = $this->input->post('signature');
        $supp = $this->input->post('supp');
        
        $id = $this->input->post('options');

        // $this->db->trans_start();

        foreach ($id as $id_product) {

            $qty_pengajuan = $this->model_management_inventory->get_qty_pengajuan_by_id_product($id_product)->row()->jumlah;

            if ($this->input->post('status_approval') == '3') {
                $nama_status = 'verified';
            }else{
                $nama_status ='not verified';
                $qty_pengajuan = NULL;
            }

            if ($supp == '001-herbana' || $supp == '002' || $supp == '012' || $supp == '013' || $supp == '014' || $supp == '015') {
                $data = [ 
                    "status"        => $this->input->post('status_approval'), 
                    "nama_status"   => $nama_status,
                    "deskripsi"     => $this->input->post('deskripsi'), 
                    "qty_approval"  => $qty_pengajuan
                ];
            }else{
                $data = [ 
                    "status"        => $this->input->post('status_approval'), 
                    "nama_status"   => $nama_status,
                    "deskripsi"     => $this->input->post('deskripsi'), 
                ];
            }

            $this->db->where('id', $id_product);
            $this->db->update('management_inventory.pengajuan_retur_detail', $data);
        }

        // $this->db->trans_complete();
        // if ($this->db->trans_status() === FALSE)
        // {
        //     echo "ada kegagalan dalam verifikasi product retur. Mungkin disebabkan internet. rollback diaktifkan ke keadaan sebelumnya";
        //     die;
        // }

        redirect('management_inventory/verifikasi_retur/'.$signature.'/'.$supp);

    }

    public function bridging_to_principal_area(){

        $signature  = $this->input->post('signature');
        $suppx      = $this->input->post('supp');
        $tipe       = $this->input->post('tipe');
        $created_at = $this->model_outlet_transaksi->timezone();

        $get_site_code = $this->model_management_inventory->get_pengajuan_by_signature($signature)->row()->site_code;

        $traffic = $this->model_management_inventory->get_traffic();
        if($traffic->num_rows() > 0){
            $traffic = $traffic->row()->status_generate;

            // echo "traffic : ".$traffic;
            // die;

            if ($traffic == 1) {
                $this->session->set_flashdata("pesan", "Server sedang sibuk, anda masih dalam antrian. Silahkan coba lagi nanti");
                redirect('management_inventory/pengajuan_retur_detail/'.$signature.'/'.$suppx);
                die;
            }else{
                $insert_traffic = $this->model_management_inventory->insert_traffic($get_site_code, $this->session->userdata('id'), 1);
            }

        }else{
            $insert_traffic = $this->model_management_inventory->insert_traffic($get_site_code, $this->session->userdata('id'), 1);
        }

        // die;

        if($suppx == "001-GT" || $suppx == "001-GT-PHARMA" || $suppx == "001-MTI" || $suppx == "001-NKA"){
            $supp = "001";
        }else{
            $supp = $suppx;
        }

        // cek apakah sudah ada signature
        $cek_dp_signature = './assets/uploads/signature/'.$this->session->userdata('username').'-signature.png';
        if (!file_exists($cek_dp_signature)) {
            $this->session->set_flashdata("pesan", "Proses Gagal !! <br><br>Signature anda tidak ditemukan. Registrasikan dahulu signature anda di menu profile -> signature");
            redirect('management_inventory/pengajuan_retur_detail/'.$signature.'/'.$supp);
            die;
        }

        // echo "Aaa";
        // die;

        if ($supp == '001' || $supp == '005') {
            // cek apakah ada mapping di mapping_area_retur
            $get_site_code = $this->model_management_inventory->get_pengajuan_by_signature($signature)->row()->site_code;

            $get_principal_area = $this->model_management_inventory->get_principal_area($get_site_code, $suppx);
            if(!$get_principal_area->num_rows() > 0){
                $this->session->set_flashdata("pesan", "Proses pengajuan retur gagal. Tidak ditemukan PIC Principal Area. Infokan ini kepada IT");
                redirect('management_inventory/pengajuan_retur_detail/'.$signature.'/'.$supp);
                die;
            }
        }
        

        $get_pengajuan = $this->model_management_inventory->get_pengajuan($signature);
        foreach ($get_pengajuan->result() as $a) {
            $id_pengajuan   = $a->id;
        }

        // cek apakah ada produknya
        $query_cek_product_null = "
            select *
            from management_inventory.pengajuan_retur_detail a 
            where a.id_pengajuan = $id_pengajuan and a.deleted is null
        ";
        $proses_query_cek_product_null = $this->db->query($query_cek_product_null);
        if($proses_query_cek_product_null->num_rows() == 0){
            $this->session->set_flashdata("pesan", "Proses pengajuan retur gagal. Silahkan masukkan product terlebih dahulu");
            redirect('management_inventory/pengajuan_retur_detail/'.$signature.'/'.$supp);
            die;
        }

        // cek apakah satuan ada yang null
        $query_cek_satuan = "
            select *
            from management_inventory.pengajuan_retur_detail a 
            where a.id_pengajuan = $id_pengajuan and (a.satuan = '' or a.satuan is null) and a.deleted is null
        ";
        
        $cek = $this->db->query($query_cek_satuan);

        if($cek->num_rows() > 0){
            $this->session->set_flashdata("pesan", "Proses pengajuan retur gagal. Ditemukan produk yang tidak mempunyai satuan yaitu : ".$cek->row()->kodeprod);
            redirect('management_inventory/pengajuan_retur_detail/'.$signature.'/'.$supp);
            die;
        }

        // cek kodeprod
        if ($supp == '001-herbana') {
            $params_supp = "001";
            $params_group = "and b.grup = 'G0103'";
        }else{
            $params_supp = "$supp";
            $params_group = "";
        }
        $query_cek = "
            select * 
            from management_inventory.pengajuan_retur_detail a  
            where a.id_pengajuan = $id_pengajuan and a.deleted is null and a.kodeprod not in (
                select b.kodeprod
                from mpm.tabprod b
                where b.supp = '$params_supp' $params_group
            )
        ";

        $cek = $this->db->query($query_cek);
        if($cek->num_rows() > 0){
            $this->session->set_flashdata("pesan", "Proses pengajuan retur gagal. Ditemukan produk yang tidak seharusnya yaitu : ".$cek->row()->kodeprod);            
            redirect('management_inventory/pengajuan_retur_detail/'.$signature.'/'.$supp);
            die;
        }

        // cek ED khusus US
        // echo $supp;
        if ($tipe == "reguler") 
        {
            if ($supp == '005' || $suppx == '001-GT' || $suppx == '001-MTI' || $suppx == '001-GT-PHARMA' || $suppx == '001-herbana') 
            {
                $cek_selisih = "
                    select a.kodeprod, a.nama_outlet,  a.batch_number, a.expired_date, NOW(), DATEDIFF(NOW(),a.expired_date) as selisih
                    from management_inventory.pengajuan_retur_detail a 
                    where a.id_pengajuan = $id_pengajuan and a.deleted is null
                ";

                $proses_cek_selisih = $this->db->query($cek_selisih)->result();
                foreach($proses_cek_selisih as $a){
                    $selisih_ed = $a->selisih;
                    $kodeprod_ed = $a->kodeprod;
                    $batch_number_ed = $a->batch_number;
                    $expired_date_ed = $a->expired_date;

                    if ($selisih_ed > 31) {
                        $this->session->set_flashdata("pesan", "Proses pengajuan retur gagal. <br><br>Ditemukan ED yang melebihi batas 1 bulan yaitu kodeprod : ".$kodeprod_ed. ' , batch_number : '.$batch_number_ed. ' , ed : '.$expired_date_ed. ' ,selisih : '.$selisih_ed. ' hari');
                        redirect('management_inventory/pengajuan_retur_detail/'.$signature.'/'.$supp);
                        die;
                    }
                }
            }
        }

        // $this->db->trans_start();
        $get_no_pengajuan = $this->model_management_inventory->get_pengajuan($signature)->row()->no_pengajuan;
        if ($get_no_pengajuan == '' || $get_no_pengajuan == NULL) {
            $no_ajuan = $this->model_management_inventory->generate($created_at);
        }else{
            $no_ajuan = $get_no_pengajuan;
        }

        if ($supp == '002' || $supp == '012' || $supp == '013' || $supp == '015' || $supp == '001-herbana') {
            $data = [
                "status"            => 2,
                "tanggal_pengajuan" => $created_at,
                "nama_status"       => "PENDING MPM",
                "no_pengajuan"      => $no_ajuan,
                "verifikasi_at"     => NULL
            ];
        }else{
            $data = [
                "status"            => 3,
                "tanggal_pengajuan" => $created_at,
                "nama_status"       => "PENDING PRINCIPAL AREA",
                "no_pengajuan"      => $no_ajuan
            ];
        }
        $this->db->where("signature", $signature);
        $this->db->update("management_inventory.pengajuan_retur", $data);

        // $this->db->trans_complete();
        // if ($this->db->trans_status() === FALSE)
        // {
        //     echo "ada kegagalan dalam pengajuan ke principal area. Pengajuan BATAL. Mungkin disebabkan internet. rollback diaktifkan ke keadaan sebelumnya";
        //     die;
        // }

        $insert_traffic = $this->model_management_inventory->insert_traffic($get_site_code, $this->session->userdata('id'), 0);

        if ($supp == '002' || $supp == '012' || $supp == '013' || $supp == '015' || $supp == '001-herbana') {

            // redirect('management_inventory/email_proses_principal_ho/'.$signature);
            // redirect('management_inventory/email_pengajuan_new/'.$signature);
            redirect('management_inventory/email_proses_mpm/'.$signature);
            die;

        }else{
            redirect('management_inventory/email_proses_principal_area_new/'.$signature);
            die;
        }
    }

    public function principal_area($signature, $supp){

        $get_pengajuan = $this->model_management_inventory->get_pengajuan($signature);
        foreach ($get_pengajuan->result() as $a) {
            $id_pengajuan               = $a->id;
            $site_code                  = $a->site_code;
            $branch_name                = $a->branch_name;
            $nama_comp                  = $a->nama_comp;
            $supp                       = $a->supp;
            $namasupp                   = $a->namasupp;
            $no_pengajuan               = $a->no_pengajuan;
            $tanggal_pengajuan          = $a->tanggal_pengajuan;
            $nama                       = $a->nama;
            $status                     = $a->status;
            $nama_status                = $a->nama_status;
            $no_pengajuan               = $a->no_pengajuan;
            $created_at                 = $a->created_at;
            $file                       = $a->file;
            $digital_signature          = $a->digital_signature;
            $signature                  = $a->signature;
            $verifikasi_at              = $a->verifikasi_at;
            $verifikasi_username        = $a->verifikasi_username;
            $verifikasi_signature       = $a->verifikasi_signature;
            $principal_area_at          = $a->principal_area_at;
            $principal_area_signature   = $a->principal_area_signature;
            $principal_area_username    = $a->principal_area_username;
            $file_principal_area        = $a->file_principal_area;
            $catatan_principal_area     = $a->catatan_principal_area;
            $principal_ho_at            = $a->principal_ho_at;
            $principal_ho_signature     = $a->principal_ho_signature;
            $principal_ho_username      = $a->principal_ho_username;
            $file_principal_ho          = $a->file_principal_ho;
            $catatan_principal_ho       = $a->catatan_principal_ho;
            $tanggal_kirim_barang       = $a->tanggal_kirim_barang;
            $nama_ekspedisi             = $a->nama_ekspedisi;
            $est_tanggal_tiba           = $a->est_tanggal_tiba;
            $file_pengiriman            = $a->file_pengiriman;
            $proses_kirim_barang_at     = $a->proses_kirim_barang_at;
            $username_kirim_barang      = $a->username_kirim_barang;
            $tanggal_terima_barang      = $a->tanggal_terima_barang;
            $nama_penerima              = $a->nama_penerima;
            $no_terima_barang           = $a->no_terima_barang;
            $file_terima_barang         = $a->file_terima_barang;
            $terima_barang_at           = $a->terima_barang_at;
            $username_terima_barang     = $a->username_terima_barang;
            $tanggal_pemusnahan         = $a->tanggal_pemusnahan;
            $nama_pemusnahan            = $a->nama_pemusnahan;
            $file_pemusnahan            = $a->file_pemusnahan;
            $foto_pemusnahan_1          = $a->foto_pemusnahan_1;
            $foto_pemusnahan_2          = $a->foto_pemusnahan_2;
            $pemusnahan_at              = $a->pemusnahan_at;
            $username_pemusnahan        = $a->username_pemusnahan;
            $video                      = $a->video;
        }

        $get_pengajuan_detail_summary = $this->model_management_inventory->get_pengajuan_detail_summary($id_pengajuan);
        foreach ($get_pengajuan_detail_summary->result() as $a) {
            $count_kodeprod = $a->count_kodeprod;
            $value_rbp = $a->value_rbp;
        }

        $data = [
            'title'                      => 'Pending Principal Area',
            'url'                        => 'management_inventory/principal_area_proses',
            'url_pengajuan'              => 'management_inventory/proses_mpm',
            'url_proses_mpm'             => 'management_inventory/proses_bridging_mpm',
            'get_pengajuan_detail'       => $this->model_management_inventory->get_pengajuan_detail($id_pengajuan),
            'get_pengajuan_detail_accordion'       => $this->model_management_inventory->get_pengajuan_detail_accordion($id_pengajuan),
            // 'site_code'                  => $this->model_management_inventory->get_sitecode(),
            'id_pengajuan'               => $id_pengajuan,
            'site_code'                  => $site_code,
            'branch_name'                => $branch_name,
            'nama_comp'                  => $nama_comp,
            'supp'                       => $supp,
            'namasupp'                   => $namasupp,
            'no_pengajuan'               => $no_pengajuan,
            'tanggal_pengajuan'          => $tanggal_pengajuan,
            'nama'                       => $nama,
            'status'                     => $status,
            'nama_status'                => $nama_status,
            'no_pengajuan'               => $no_pengajuan,
            'created_at'                 => $created_at,
            'file'                       => $file,
            'digital_signature'          => $digital_signature,
            'signature'                  => $signature,
            'verifikasi_at'              => $verifikasi_at,
            'verifikasi_username'        => $verifikasi_username,
            'verifikasi_signature'       => $verifikasi_signature,
            'principal_area_at'          => $principal_area_at,
            'principal_area_signature'   => $principal_area_signature,
            'principal_area_username'    => $principal_area_username,
            'file_principal_area'        => $file_principal_area,
            'catatan_principal_area'     => $catatan_principal_area,
            'principal_ho_at'            => $principal_ho_at,
            'principal_ho_signature'     => $principal_ho_signature,
            'principal_ho_username'      => $principal_ho_username,
            'file_principal_ho'          => $file_principal_ho,
            'catatan_principal_ho'       => $catatan_principal_ho,
            'tanggal_kirim_barang'       => $tanggal_kirim_barang,
            'nama_ekspedisi'             => $nama_ekspedisi,
            'est_tanggal_tiba'           => $est_tanggal_tiba,
            'file_pengiriman'            => $file_pengiriman,
            'proses_kirim_barang_at'     => $proses_kirim_barang_at,
            'username_kirim_barang'      => $username_kirim_barang,
            'tanggal_terima_barang'      => $tanggal_terima_barang,
            'nama_penerima'              => $nama_penerima,
            'no_terima_barang'           => $no_terima_barang,
            'file_terima_barang'         => $file_terima_barang,
            'terima_barang_at'           => $terima_barang_at,
            'username_terima_barang'     => $username_terima_barang,
            'count_kodeprod'             => $count_kodeprod,
            'value_rbp'                  => $value_rbp,        
            'tanggal_pemusnahan'         => $tanggal_pemusnahan,
            'nama_pemusnahan'            => $nama_pemusnahan,
            'file_pemusnahan'            => $file_pemusnahan,
            'foto_pemusnahan_1'          => $foto_pemusnahan_1,
            'foto_pemusnahan_2'          => $foto_pemusnahan_2,
            'pemusnahan_at'              => $pemusnahan_at,
            'username_pemusnahan'        => $username_pemusnahan, 
            'video'                      => $video, 
        ];

        // $this->load->view('management_office/top_header', $data);
        
        $this->navbar($data);

        $this->load->view('kalimantan/header_full_width', $data);
        $this->load->view('kalimantan/accordion', $data);
        $this->load->view('management_inventory/principal_area_new', $data);
        $this->load->view('kalimantan/footer');
    }

    public function principal_area_proses(){

        $signature = $this->input->post('signature');

        $get_pengajuan = $this->model_management_inventory->get_pengajuan($signature);
        foreach ($get_pengajuan->result() as $a) {
            $id_pengajuan               = $a->id;
        }

        $supp = $this->input->post('supp');
        $id = $this->input->post('options');
        $status_approval = $this->input->post('status_approval');

        $keterangan_principal_area = $this->input->post("keterangan_principal_area");
        // echo $keterangan_principal_area;

        $data = array();
        $status_approval = $this->input->post('status_approval');

        if ($status_approval == 12) { // approve all

            // $this->db->trans_start();
            foreach ($id as $id_product) {

                $update = "
                    update management_inventory.pengajuan_retur_detail a 
                    set a.qty_approval = a.jumlah, 
                        a.keterangan_principal_area = '$keterangan_principal_area'
                    where a.id = $id_product and a.deleted is null
                ";

                $proses_update = $this->db->query($update);

                // $this->db->trans_complete();
                // if ($this->db->trans_status() === FALSE)
                // {
                //     echo "ada kegagalan saat insert tracking. Mungkin disebabkan internet. rollback diaktifkan ke proses sebelumnya";
                //     die;
                // }
            }

        }

        elseif ($status_approval == 13) { // reject all


            // $this->db->trans_start();
            foreach ($id as $id_product) {

                // echo $id_product;
                // echo $this->input->post('qty_approval')[$id_product];
                $data = [
                    "qty_approval"  => 0,
                    "keterangan_principal_area" => $keterangan_principal_area
                ];

                $this->db->where("id", $id_product);
                $this->db->update("management_inventory.pengajuan_retur_detail", $data);
            }
            // $this->db->trans_complete();
            // if ($this->db->trans_status() === FALSE)
            // {
            //     echo "ada kegagalan saat insert tracking. Mungkin disebabkan internet. rollback diaktifkan ke proses sebelumnya";
            //     die;
            // }

        }elseif ($status_approval == 11) { // approve partial

            // $this->db->trans_start();
            foreach ($id as $id_product) {

                // echo $id_product;
                // echo $this->input->post('qty_approval')[$id_product];
                $data = [
                    "qty_approval"  => $this->input->post('qty_approval')[$id_product],
                    "keterangan_principal_area" => $keterangan_principal_area
                ];

                $this->db->where("id", $id_product);
                $this->db->update("management_inventory.pengajuan_retur_detail", $data);
            }
            // $this->db->trans_complete();
            // if ($this->db->trans_status() === FALSE)
            // {
            //     echo "ada kegagalan saat insert tracking. Mungkin disebabkan internet. rollback diaktifkan ke proses sebelumnya";
            //     die;
            // }
        }
        redirect('management_inventory/principal_area/'.$signature.'/'.$supp);
    }

    public function principal_area_revision($signature, $supp){
        // echo "signature : ".$signature;
        // echo "supp : ".$supp;


        $get_pengajuan = $this->model_management_inventory->get_pengajuan($signature, "");
        foreach ($get_pengajuan->result() as $a) {
            $id_pengajuan               = $a->id;
            $site_code                  = $a->site_code;
            $branch_name                = $a->branch_name;
            $nama_comp                  = $a->nama_comp;
            $supp                       = $a->supp;
            $namasupp                   = $a->namasupp;
            $no_pengajuan               = $a->no_pengajuan;
            $tanggal_pengajuan          = $a->tanggal_pengajuan;
            $nama                       = $a->nama;
            $status                     = $a->status;
            $nama_status                = $a->nama_status;
            $no_pengajuan               = $a->no_pengajuan;
            $created_at                 = $a->created_at;
            $file                       = $a->file;
            $digital_signature          = $a->digital_signature;
            $signature                  = $a->signature;
            $verifikasi_at              = $a->verifikasi_at;
            $verifikasi_username        = $a->verifikasi_username;
            $verifikasi_signature       = $a->verifikasi_signature;
            $principal_area_at          = $a->principal_area_at;
            $principal_area_signature   = $a->principal_area_signature;
            $principal_area_username    = $a->principal_area_username;
            $file_principal_area        = $a->file_principal_area;
            $catatan_principal_area     = $a->catatan_principal_area;
            $principal_ho_at            = $a->principal_ho_at;
            $principal_ho_signature     = $a->principal_ho_signature;
            $principal_ho_username      = $a->principal_ho_username;
            $file_principal_ho          = $a->file_principal_ho;
            $catatan_principal_ho       = $a->catatan_principal_ho;
            $tanggal_kirim_barang       = $a->tanggal_kirim_barang;
            $nama_ekspedisi             = $a->nama_ekspedisi;
            $est_tanggal_tiba           = $a->est_tanggal_tiba;
            $file_pengiriman            = $a->file_pengiriman;
            $username_kirim_barang      = $a->username_kirim_barang;
            $tanggal_terima_barang      = $a->tanggal_terima_barang;
            $nama_penerima              = $a->nama_penerima;
            $no_terima_barang           = $a->no_terima_barang;
            $file_terima_barang         = $a->file_terima_barang;
            $terima_barang_at           = $a->terima_barang_at;
            $username_terima_barang     = $a->username_terima_barang;
        }

        $get_pengajuan_detail_summary = $this->model_management_inventory->get_pengajuan_detail_summary($id_pengajuan);
        foreach ($get_pengajuan_detail_summary->result() as $a) {
            $count_kodeprod = $a->count_kodeprod;
            $value_rbp = $a->value_rbp;
        }

        $data = [
            'title'                      => 'Principal Area Revision',
            'url'                        => 'management_inventory/principal_area_revision_proses',
            'url_pengajuan'              => 'management_inventory/proses_mpm',
            'get_pengajuan_detail'       => $this->model_management_inventory->get_pengajuan_detail($id_pengajuan),
            // 'site_code'                  => $this->model_management_inventory->get_sitecode(),
            'site_code'                  => $site_code,
            'branch_name'                => $branch_name,
            'nama_comp'                  => $nama_comp,
            'supp'                       => $supp,
            'namasupp'                   => $namasupp,
            'no_pengajuan'               => $no_pengajuan,
            'tanggal_pengajuan'          => $tanggal_pengajuan,
            'nama'                       => $nama,
            'status'                     => $status,
            'nama_status'                => $nama_status,
            'no_pengajuan'               => $no_pengajuan,
            'created_at'                 => $created_at,
            'file'                       => $file,
            'digital_signature'          => $digital_signature,
            'signature'                  => $signature,
            'verifikasi_at'              => $verifikasi_at,
            'verifikasi_username'        => $verifikasi_username,
            'verifikasi_signature'       => $verifikasi_signature,
            'principal_area_at'          => $principal_area_at,
            'principal_area_signature'   => $principal_area_signature,
            'principal_area_username'    => $principal_area_username,
            'file_principal_area'        => $file_principal_area,
            'catatan_principal_area'     => $catatan_principal_area,
            'principal_ho_at'            => $principal_ho_at,
            'principal_ho_signature'     => $principal_ho_signature,
            'principal_ho_username'      => $principal_ho_username,
            'file_principal_ho'          => $file_principal_ho,
            'catatan_principal_ho'       => $catatan_principal_ho,
            'tanggal_kirim_barang'       => $tanggal_kirim_barang,
            'nama_ekspedisi'             => $nama_ekspedisi,
            'est_tanggal_tiba'           => $est_tanggal_tiba,
            'file_pengiriman'            => $file_pengiriman,
            'username_kirim_barang'      => $username_kirim_barang,
            'tanggal_terima_barang'      => $tanggal_terima_barang,
            'nama_penerima'              => $nama_penerima,
            'no_terima_barang'           => $no_terima_barang,
            'file_terima_barang'         => $file_terima_barang,
            'terima_barang_at'           => $terima_barang_at,
            'username_terima_barang'     => $username_terima_barang,
            'count_kodeprod'             => $count_kodeprod,
            'value_rbp'                  => $value_rbp,
        ];

        // $this->load->view('management_office/top_header', $data);
        $this->navbar($data);
        $this->load->view('kalimantan/header_full_width', $data);
        $this->load->view('management_inventory/principal_area_revision', $data);
        $this->load->view('kalimantan/footer');

    }

    public function principal_area_revision_proses(){

        $signature = $this->input->post('signature');
        $supp = $this->input->post('supp');
        $id = $this->input->post('options');

        // echo "signature : ".$signature; 
        // echo "supp : ".$supp; 
        // var_dump($id);
        // die;

        // $this->db->trans_start();

        foreach ($id as $id_product) {
        
            if ($this->input->post('status_approval') == 1) {
                $nama_status = 'approve_principal_area';
            }else{
                $nama_status ='not reject_principal_area';
            }

            $data = [ 
                "status"        => $this->input->post('status_approval'), 
                "nama_status"   => $nama_status,
                "deskripsi"     => $this->input->post('deskripsi'), 
            ];

            $this->db->where('id', $id_product);
            $this->db->update('management_inventory.pengajuan_retur_detail', $data);
        }

        // $this->db->trans_complete();
        // if ($this->db->trans_status() === FALSE)
        // {
        //     echo "ada kegagalan dalam verifikasi product retur. Mungkin disebabkan internet. rollback diaktifkan ke keadaan sebelumnya";
        //     die;
        // }

        redirect('management_inventory/verifikasi_retur/'.$signature.'/'.$supp);
    }

    public function principal_ho($signature, $supp){

        $get_pengajuan = $this->model_management_inventory->get_pengajuan($signature);
        foreach ($get_pengajuan->result() as $a) {
            $id_pengajuan               = $a->id;
            $site_code                  = $a->site_code;
            $branch_name                = $a->branch_name;
            $nama_comp                  = $a->nama_comp;
            $supp                       = $a->supp;
            $namasupp                   = $a->namasupp;
            $no_pengajuan               = $a->no_pengajuan;
            $tanggal_pengajuan          = $a->tanggal_pengajuan;
            $nama                       = $a->nama;
            $status                     = $a->status;
            $nama_status                = $a->nama_status;
            $no_pengajuan               = $a->no_pengajuan;
            $created_at                 = $a->created_at;
            $file                       = $a->file;
            $digital_signature          = $a->digital_signature;
            $signature                  = $a->signature;
            $verifikasi_at              = $a->verifikasi_at;
            $verifikasi_username        = $a->verifikasi_username;
            $verifikasi_signature       = $a->verifikasi_signature;
            $principal_area_at          = $a->principal_area_at;
            $principal_area_signature   = $a->principal_area_signature;
            $principal_area_username    = $a->principal_area_username;
            $file_principal_area        = $a->file_principal_area;
            $catatan_principal_area     = $a->catatan_principal_area;
            $principal_ho_at            = $a->principal_ho_at;
            $principal_ho_signature     = $a->principal_ho_signature;
            $principal_ho_username      = $a->principal_ho_username;
            $file_principal_ho          = $a->file_principal_ho;
            $catatan_principal_ho       = $a->catatan_principal_ho;
            $tanggal_kirim_barang       = $a->tanggal_kirim_barang;
            $nama_ekspedisi             = $a->nama_ekspedisi;
            $est_tanggal_tiba           = $a->est_tanggal_tiba;
            $file_pengiriman            = $a->file_pengiriman;
            $proses_kirim_barang_at     = $a->proses_kirim_barang_at;
            $username_kirim_barang      = $a->username_kirim_barang;
            $tanggal_terima_barang      = $a->tanggal_terima_barang;
            $nama_penerima              = $a->nama_penerima;
            $no_terima_barang           = $a->no_terima_barang;
            $file_terima_barang         = $a->file_terima_barang;
            $terima_barang_at           = $a->terima_barang_at;
            $username_terima_barang     = $a->username_terima_barang;
            $tanggal_pemusnahan         = $a->tanggal_pemusnahan;
            $nama_pemusnahan            = $a->nama_pemusnahan;
            $file_pemusnahan            = $a->file_pemusnahan;
            $foto_pemusnahan_1          = $a->foto_pemusnahan_1;
            $foto_pemusnahan_2          = $a->foto_pemusnahan_2;
            $pemusnahan_at              = $a->pemusnahan_at;
            $username_pemusnahan        = $a->username_pemusnahan;
            $video                      = $a->video;
        }

        $get_pengajuan_detail_summary = $this->model_management_inventory->get_pengajuan_detail_summary($id_pengajuan);
        foreach ($get_pengajuan_detail_summary->result() as $a) {
            $count_kodeprod = $a->count_kodeprod;
            $value_rbp = $a->value_rbp;
        }

        $data = [
            'title'                      => 'Pending Principal HO',
            'url'                        => 'management_inventory/principal_ho_proses',
            'url_pengajuan'              => 'management_inventory/proses_mpm',
            'get_pengajuan_detail'       => $this->model_management_inventory->get_pengajuan_detail($id_pengajuan),
            'get_pengajuan_detail_accordion'       => $this->model_management_inventory->get_pengajuan_detail_accordion($id_pengajuan),
            // 'site_code'                  => $this->model_management_inventory->get_sitecode(),
            'site_code'                  => $site_code,
            'branch_name'                => $branch_name,
            'nama_comp'                  => $nama_comp,
            'supp'                       => $supp,
            'namasupp'                   => $namasupp,
            'no_pengajuan'               => $no_pengajuan,
            'tanggal_pengajuan'          => $tanggal_pengajuan,
            'nama'                       => $nama,
            'status'                     => $status,
            'nama_status'                => $nama_status,
            'no_pengajuan'               => $no_pengajuan,
            'created_at'                 => $created_at,
            'file'                       => $file,
            'digital_signature'          => $digital_signature,
            'signature'                  => $signature,
            'verifikasi_at'              => $verifikasi_at,
            'verifikasi_username'        => $verifikasi_username,
            'verifikasi_signature'       => $verifikasi_signature,
            'principal_area_at'          => $principal_area_at,
            'principal_area_signature'   => $principal_area_signature,
            'principal_area_username'    => $principal_area_username,
            'file_principal_area'        => $file_principal_area,
            'catatan_principal_area'     => $catatan_principal_area,
            'principal_ho_at'            => $principal_ho_at,
            'principal_ho_signature'     => $principal_ho_signature,
            'principal_ho_username'      => $principal_ho_username,
            'file_principal_ho'          => $file_principal_ho,
            'catatan_principal_ho'       => $catatan_principal_ho,
            'tanggal_kirim_barang'       => $tanggal_kirim_barang,
            'nama_ekspedisi'             => $nama_ekspedisi,
            'est_tanggal_tiba'           => $est_tanggal_tiba,
            'file_pengiriman'            => $file_pengiriman,
            'username_kirim_barang'      => $username_kirim_barang,
            'proses_kirim_barang_at'     => $proses_kirim_barang_at,
            'tanggal_terima_barang'      => $tanggal_terima_barang,
            'nama_penerima'              => $nama_penerima,
            'no_terima_barang'           => $no_terima_barang,
            'file_terima_barang'         => $file_terima_barang,
            'terima_barang_at'           => $terima_barang_at,
            'username_terima_barang'     => $username_terima_barang,
            'count_kodeprod'             => $count_kodeprod,
            'value_rbp'                  => $value_rbp,
            'tanggal_pemusnahan'         => $tanggal_pemusnahan,
            'nama_pemusnahan'            => $nama_pemusnahan,
            'file_pemusnahan'            => $file_pemusnahan,
            'foto_pemusnahan_1'          => $foto_pemusnahan_1,
            'foto_pemusnahan_2'          => $foto_pemusnahan_2,
            'pemusnahan_at'              => $pemusnahan_at,
            'username_pemusnahan'        => $username_pemusnahan,
            'status_ho'                  => $this->model_management_inventory->get_level_ho($site_code, $supp),
            'sign_principal_ho_date'     => $this->model_outlet_transaksi->timezone(),
            'url_group'                  => 'management_inventory/group_principal_ho',
            'video'                      => $video,
        ];

        // $this->load->view('management_office/top_header', $data);

        $this->navbar($data);

        $this->load->view('kalimantan/header_full_width', $data);
        $this->load->view('kalimantan/accordion', $data);
        $this->load->view('management_inventory/principal_ho', $data);
        $this->load->view('kalimantan/footer');
    }

    public function group_principal_ho(){
        
        $sign_principal_ho_date         = $this->input->post('sign_principal_ho_date');
        $userid_for_group_approval      = $this->input->post('userid_for_group_approval');
        $principal_for_group_approval   = $this->input->post('principal_for_group_approval');

        $data = [       
            'url'                            => 'management_inventory/group_principal_ho_proses',
            'get_pengajuan'                  => $this->model_management_inventory->get_pengajuan_group($sign_principal_ho_date, $userid_for_group_approval, $principal_for_group_approval),
            'sign_principal_ho_date'         => $this->input->post('sign_principal_ho_date'),
            'userid_for_group_approval'      => $this->input->post('userid_for_group_approval'),
            'principal_for_group_approval'   => $this->input->post('principal_for_group_approval'),
        ];

        // $this->load->view('management_office/top_header', $data);
        $this->navbar($data);
        $this->load->view('kalimantan/header_full_width', $data);
        $this->load->view('management_inventory/group_principal_ho', $data);
        $this->load->view('kalimantan/footer');

    }
    
    public function principal_ho_proses(){

        $file = $this->input->post('file'); 
        $status_principal_ho = $this->input->post('status_principal_ho');
        if ($status_principal_ho == 14) { // jika approve
            $nama_status_principal_ho = "APPROVE";

            $status = $this->input->post('status');
            if ($status == 5) {
                $nama_status = "PENDING KIRIM BARANG";
            }elseif($status == 7){
                $nama_status = "PENDING PEMUSNAHAN";
            }elseif($status == 11){
                $nama_status = "RETUR SAMPLE";
            }else{

                // echo "bb";
                // $status = 15;
                $status = 10;
                $nama_status = "REJECT PRINCIPAL HO";
            }

        }else{
            // echo "aaa";
            $nama_status_principal_ho = "REJECT";
            $status = 10;
            $nama_status = "REJECT PRINCIPAL HO";
        }

        // echo "status : ".$status;
        // echo "nama_status : ".$nama_status;
        // die;

        if (!is_dir('./assets/file/retur/')) {
            @mkdir('./assets/file/retur/', 0777);
        }

        $config['upload_path'] = './assets/file/retur/';
        $config['allowed_types'] = '*';
        $config['max_size'] = '*';

        $this->load->library('upload', $config);
        if (!$this->upload->do_upload('file')) {
            $filename = '';
        } else {

            $data = array('upload_data' => $this->upload->data());
            $file = $data['upload_data']['full_path'];
            chmod($file, 0777);
            $upload_data = $this->upload->data();
            $filename = $upload_data['file_name'];
        }

        $created_at = $this->model_outlet_transaksi->timezone();
        // $this->db->trans_start();

        $file = './assets/uploads/signature/'.$this->session->userdata('username').'-signature.png'; // 'images/'.$file (physical path)
        if (!file_exists($file)) {
            $this->session->set_flashdata("pesan", "Proses Gagal !! <br><br>Signature anda tidak ditemukan. Registrasikan dahulu signature anda di menu profile -> signature");
            redirect('management_inventory/principal_ho/'.$this->input->post('signature').'/'.$this->input->post('supp'));
            die;   
        }

        $data = [
            'status_principal_ho'           => $status_principal_ho,
            'nama_status_principal_ho'      => $nama_status_principal_ho,
            'file_principal_ho'             => $filename,
            'catatan_principal_ho'          => $this->input->post('catatan_principal_ho'),
            'status'                        => $status,
            'nama_status'                   => $nama_status,
            'principal_ho_at'               => $created_at,
            'principal_ho_by'               => $this->session->userdata('id'),
            'principal_ho_signature'        => $this->session->userdata('username').'-signature.png'
        ];

        $this->db->where('signature', $this->input->post('signature'));
        $this->db->update('management_inventory.pengajuan_retur', $data);
        
        // $this->db->trans_complete();
        // if ($this->db->trans_status() === FALSE)
        // {
        //     echo "ada kegagalan dalam proses principal ho. Mungkin disebabkan internet. rollback diaktifkan ke keadaan sebelumnya";
        //     die;
        // }

        redirect('management_inventory/email_principal_ho_success/'.$this->input->post('signature').'/'.$this->input->post('supp'));
    }

    public function update_status_pengajuan($signature, $status, $supp){

        // cek apakah mpm sudah ada signature
        $cek_mpm_signature = './assets/uploads/signature/'.$this->session->userdata('username').'-signature.png';
        if (!file_exists($cek_mpm_signature)) {
            $this->session->set_flashdata("pesan", "Proses Gagal !! <br><br>Signature anda tidak ditemukan. Registrasikan dahulu signature anda di menu profile -> signature");
            redirect('management_inventory/verifikasi_retur/'.$signature.'/'.$supp);
            die;
        }

        $created_at = $this->model_outlet_transaksi->timezone();
        $get_pengajuan = $this->model_management_inventory->get_pengajuan($signature);
        foreach ($get_pengajuan->result() as $a) {
            $id_pengajuan               = $a->id; 
        }
        // cek apakah ada status yang null
        $query = "
            select * 
            from management_inventory.pengajuan_retur_detail a 
            where a.id_pengajuan = $id_pengajuan and a.status is null and a.deleted is null and (a.qty_approval is not null and a.qty_approval > 0)
        ";
        
        $cek = $this->db->query($query);

        if ($cek->num_rows() > 0) {         
            $this->session->set_flashdata("pesan", "Proses Gagal !! <br><br>masih ditemukan data yang belum di verifikasi. yaitu : ".$cek->row()->kodeprod. " , batch : ".$cek->row()->batch_number. " , outlet : ".$cek->row()->nama_outlet);
            redirect('management_inventory/verifikasi_retur/'.$signature.'/'.$supp);
            die;
        }

        if ($status == 1) {
            $nama_status = 'PENDING DP';
            $data = [
                "status"                => $status,
                "nama_status"           => $nama_status,
                "tanggal_pengajuan"     => NULL,
                "verifikasi_signature"  => NULL,
                "verifikasi_at"         => $created_at,
                "verifikasi_by"         => $this->session->userdata('id')
            ];
            $this->db->where('signature', $signature);
            $this->db->update('management_inventory.pengajuan_retur', $data);

            redirect('management_inventory/verifikasi_retur/'.$signature.'/'.$supp);
            die;


        }elseif ($status == 3) {

            if ($supp == '005') {
                $status = 4;
                $nama_status = "PENDING PRINCIPAL HO";
            }else{
                $status = 4;
                $nama_status = "PENDING PRINCIPAL HO";
            }
        }

        $created_at = $this->model_outlet_transaksi->timezone();
        // $this->db->trans_start();

        $file = './assets/uploads/signature/'.$this->session->userdata('username').'-signature.png'; // 'images/'.$file (physical path)
        if (file_exists($file)) {
            $verifikasi_signature = $this->session->userdata('username').'-signature.png';
        }else{
            $verifikasi_signature = '';
        }

        $data = [
            "status"                => $status,
            "nama_status"           => $nama_status,
            "verifikasi_signature"  => $verifikasi_signature,
            "verifikasi_at"         => $created_at,
            "verifikasi_by"         => $this->session->userdata('id')
        ];
        $this->db->where('signature', $signature);
        $this->db->update('management_inventory.pengajuan_retur', $data);

        // update qty approval
        if ($supp == '002' || $supp == '012' || $supp == '013' || $supp == '014' || $supp == '015') {
            $update_qty_approval = "
                update management_inventory.pengajuan_retur_detail a 
                set a.qty_approval = a.jumlah 
                where a.id_pengajuan = $id_pengajuan
            ";
            $this->db->query($update_qty_approval);
        }
        
        // $this->db->trans_complete();
        // if ($this->db->trans_status() === FALSE)
        // {
        //     echo "ada kegagalan dalam proses update status. Mungkin disebabkan internet. Rollback diaktifkan ke keadaan sebelumnya";
        //     die;
        // }

        if ($supp == '005' || $supp == '001-GT' || $supp == '001-MTI' || $supp == '001-GT-PHARMA') {
            // redirect('management_inventory/email_proses_principal_area/'.$signature);
            redirect('management_inventory/email_proses_principal_ho/'.$signature);
            die;
        }else{
            // membuat pdf
            $this->generate_pdf($signature, $supp, 1);
            redirect('management_inventory/email_proses_principal_ho/'.$signature);
            die;
            // redirect('management_inventory/verifikasi_retur/'.$signature.'/'.$supp);
        }

    }

    public function email_proses_principal_area($signature){
        $get_pengajuan = $this->model_management_inventory->get_pengajuan($signature);
        foreach ($get_pengajuan->result() as $a) {
            $id_pengajuan = $a->id;
            $site_code = $a->site_code;
            $branch_name = $a->branch_name;
            $nama_comp = $a->nama_comp;
            $supp = $a->supp;
            $namasupp = $a->namasupp;
            $no_pengajuan = $a->no_pengajuan;
            $tanggal_pengajuan = $a->tanggal_pengajuan;
            $nama = $a->nama;
            $status = $a->status;
            $nama_status = $a->nama_status;
            $no_pengajuan = $a->no_pengajuan;
            $created_by = $a->created_by;
            $file = $a->file;
            $signature = $a->signature;
            $verifikasi_at = $a->verifikasi_at;
            $verifikasi_username = $a->verifikasi_username;
            $signature = $a->signature;
            $principal_area_at = $a->principal_area_at;
            $principal_area_signature = $a->principal_area_signature;
            $principal_area_username = $a->principal_area_username;
            $file_principal_area = $a->file_principal_area;
            $catatan_principal_area = $a->catatan_principal_area;
            $principal_ho_at = $a->principal_ho_at;
            $principal_ho_signature = $a->principal_ho_signature;
            $principal_ho_username = $a->principal_ho_username;
            $file_principal_ho = $a->file_principal_ho;
            $catatan_principal_ho = $a->catatan_principal_ho;
            $tanggal_kirim_barang = $a->tanggal_kirim_barang;
            $nama_ekspedisi = $a->nama_ekspedisi;
            $est_tanggal_tiba = $a->est_tanggal_tiba;
            $file_pengiriman = $a->file_pengiriman;
            $username_kirim_barang = $a->username_kirim_barang;
            $tanggal_terima_barang = $a->tanggal_terima_barang;
            $nama_penerima = $a->nama_penerima;
            $no_terima_barang = $a->no_terima_barang;
            $file_terima_barang = $a->file_terima_barang;
            $terima_barang_at = $a->terima_barang_at;
            $username_terima_barang = $a->username_terima_barang;
        }

        $get_pengajuan_detail_summary = $this->model_management_inventory->get_pengajuan_detail_summary($id_pengajuan);
        foreach ($get_pengajuan_detail_summary->result() as $a) {
            $count_kodeprod = $a->count_kodeprod;
            $value_rbp = $a->value_rbp;
        }

        $data = [
            'get_pengajuan_detail'      => $this->model_management_inventory->get_pengajuan_detail($id_pengajuan),
            'no_pengajuan'              => $no_pengajuan,
            'branch_name'               => $branch_name,
            'nama_comp'                 => $nama_comp,
            'site_code'                 => $site_code,
            'namasupp'                  => $namasupp,
            'tanggal_pengajuan'         => $tanggal_pengajuan,
            'nama'                      => $nama,
            'status'                    => $status,
            'nama_status'               => $nama_status,
            'created_by'                => $created_by,
            'file'                      => $file,
            'id_pengajuan'              => $id_pengajuan,
            'supp'                      => $supp,
            'signature'                 => $signature,
            'verifikasi_at'             => $verifikasi_at,
            'verifikasi_username'       => $verifikasi_username,
            'count_kodeprod'            => $count_kodeprod,
            'value_rbp'                 => $value_rbp,
            'principal_area_at'         => $principal_area_at,
            'principal_area_signature'  => $principal_area_signature,
            'principal_area_username'   => $principal_area_username,
            'file_principal_area'       => $file_principal_area,
            'catatan_principal_area'    => $catatan_principal_area,
            'principal_ho_at'           => $principal_ho_at,
            'principal_ho_signature'    => $principal_ho_signature,
            'principal_ho_username'     => $principal_ho_username,
            'file_principal_ho'         => $file_principal_ho,
            'catatan_principal_ho'      => $catatan_principal_ho,
            'tanggal_kirim_barang'      => $tanggal_kirim_barang,
            'nama_ekspedisi'            => $nama_ekspedisi,
            'est_tanggal_tiba'          => $est_tanggal_tiba,
            'file_pengiriman'           => $file_pengiriman,
            'username_kirim_barang'     => $username_kirim_barang,
            'tanggal_terima_barang'     => $tanggal_terima_barang,
            'nama_penerima'             => $nama_penerima,
            'no_terima_barang'          => $no_terima_barang,
            'file_terima_barang'        => $file_terima_barang,
            'terima_barang_at'          => $terima_barang_at,
            'username_terima_barang'    => $username_terima_barang,
        ];

        $from = "suffy@muliaputramandiri.com";
        $to = 'suffy.yanuar@gmail.com';
        $cc = 'suffy.mpm@gmail.com';

        $email_cc = $this->model_management_inventory->get_email($site_code)->row()->email.' ,linda@muliaputramandiri.com, fakhrul@muliaputramandiri.com';

        $subject = "MPM SITE | RETUR : $no_pengajuan | PROSES PRINCIPAL AREA";
        $this->load->model('model_relokasi');
        $this->model_relokasi->email();

        $message = $this->load->view("management_inventory/email_proses_principal_area",$data,TRUE);

        $this->email->from($from,'PT. Mulia Putra Mandiri');
        $this->email->to($to);
        $this->email->cc($email_cc);
        $this->email->subject($subject);
        $this->email->message($message);
        $send = $this->email->send();
        // echo $this->email->print_debugger();
        if ($send) {
            echo "<script>alert('pengiriman email berhasil'); </script>";
        }else{
            echo "<script>alert('pengiriman email gagal'); </script>";
            die;
        }

        redirect('management_inventory/verifikasi_retur/'.$signature.'/'.$supp);

    }

    public function email_proses_principal_area_new($signature){

        $get_pengajuan = $this->model_management_inventory->get_pengajuan($signature);

        foreach ($get_pengajuan->result() as $a) {
            $id_pengajuan           = $a->id;
            $site_code              = $a->site_code;
            $branch_name            = $a->branch_name;
            $nama_comp              = $a->nama_comp;
            $supp                   = $a->supp;
            $namasupp               = $a->namasupp;
            $no_pengajuan           = $a->no_pengajuan;
            $tanggal_pengajuan      = $a->tanggal_pengajuan;
            $nama                   = $a->nama;
            $status                 = $a->status;
            $nama_status            = $a->nama_status;
            $no_pengajuan           = $a->no_pengajuan;
            $created_by             = $a->created_by;
            $file                   = $a->file;
            $signature              = $a->signature;
            $verifikasi_at          = $a->verifikasi_at;
            $verifikasi_username    = $a->verifikasi_username;
            $signature              = $a->signature;
            $principal_area_at      = $a->principal_area_at;
            $principal_area_signature   = $a->principal_area_signature;
            $principal_area_username    = $a->principal_area_username;
            $file_principal_area        = $a->file_principal_area;
            $catatan_principal_area     = $a->catatan_principal_area;
            $principal_ho_at        = $a->principal_ho_at;
            $principal_ho_signature = $a->principal_ho_signature;
            $principal_ho_username  = $a->principal_ho_username;
            $file_principal_ho      = $a->file_principal_ho;
            $catatan_principal_ho   = $a->catatan_principal_ho;
            $tanggal_kirim_barang   = $a->tanggal_kirim_barang;
            $nama_ekspedisi         = $a->nama_ekspedisi;
            $est_tanggal_tiba       = $a->est_tanggal_tiba;
            $file_pengiriman        = $a->file_pengiriman;
            $username_kirim_barang  = $a->username_kirim_barang;
            $tanggal_terima_barang  = $a->tanggal_terima_barang;
            $nama_penerima          = $a->nama_penerima;
            $no_terima_barang       = $a->no_terima_barang;
            $file_terima_barang     = $a->file_terima_barang;
            $terima_barang_at       = $a->terima_barang_at;
            $username_terima_barang = $a->username_terima_barang;
        }

        $get_pengajuan_detail_summary = $this->model_management_inventory->get_pengajuan_detail_summary($id_pengajuan);
        foreach ($get_pengajuan_detail_summary->result() as $a) {
            $count_kodeprod = $a->count_kodeprod;
            $value_rbp = $a->value_rbp;
        }

        $get_email_to       = $this->model_management_inventory->get_email_to_retur_by_site_code($site_code, $supp)->row()->email;
        $get_username_to    = $this->model_management_inventory->get_email_to_retur_by_site_code($site_code, $supp)->row()->username;

        // echo "get_username_to : ".$get_username_to;
        // die;

        $data = [
            'get_pengajuan_detail'      => $this->model_management_inventory->get_pengajuan_detail($id_pengajuan),
            'no_pengajuan'              => $no_pengajuan,
            'branch_name'               => $branch_name,
            'nama_comp'                 => $nama_comp,
            'site_code'                 => $site_code,
            'namasupp'                  => $namasupp,
            'tanggal_pengajuan'         => $tanggal_pengajuan,
            'nama'                      => $nama,
            'status'                    => $status,
            'nama_status'               => $nama_status,
            'created_by'                => $created_by,
            'file'                      => $file,
            'id_pengajuan'              => $id_pengajuan,
            'supp'                      => $supp,
            'signature'                 => $signature,
            'verifikasi_at'             => $verifikasi_at,
            'verifikasi_username'       => $verifikasi_username,
            'count_kodeprod'            => $count_kodeprod,
            'value_rbp'                 => $value_rbp,
            'principal_area_at'         => $principal_area_at,
            'principal_area_signature'  => $principal_area_signature,
            'principal_area_username'   => $principal_area_username,
            'file_principal_area'       => $file_principal_area,
            'catatan_principal_area'    => $catatan_principal_area,
            'principal_ho_at'           => $principal_ho_at,
            'principal_ho_signature'    => $principal_ho_signature,
            'principal_ho_username'     => $principal_ho_username,
            'file_principal_ho'         => $file_principal_ho,
            'catatan_principal_ho'      => $catatan_principal_ho,
            'tanggal_kirim_barang'      => $tanggal_kirim_barang,
            'nama_ekspedisi'            => $nama_ekspedisi,
            'est_tanggal_tiba'          => $est_tanggal_tiba,
            'file_pengiriman'           => $file_pengiriman,
            'username_kirim_barang'     => $username_kirim_barang,
            'tanggal_terima_barang'     => $tanggal_terima_barang,
            'nama_penerima'             => $nama_penerima,
            'no_terima_barang'          => $no_terima_barang,
            'file_terima_barang'        => $file_terima_barang,
            'terima_barang_at'          => $terima_barang_at,
            'username_terima_barang'    => $username_terima_barang,
            'username_email'            => $get_username_to
        ];

        $from   = "suffy@muliaputramandiri.net";
        $to     = $get_email_to;
        // nanti cc nya linda@mpm
        // $cc     = 'suffy.mpm@gmail.com'; 

        $email_cc = $this->model_management_inventory->get_email($site_code)->row()->email . ",linda@muliaputramandiri.com, suffy@muliaputramandiri.com, fakhrul@muliaputramandiri.com";

        // echo "get_email_to : ".$get_email_to;
        // echo "get_username_to : ".$get_username_to;
        // echo "email_cc : ".$email_cc;

        // die;

        

        $subject = "MPM SITE | RETUR : $no_pengajuan | ".$nama_status;
        $this->load->model('model_relokasi');
        $this->model_relokasi->email();

        $message = $this->load->view("management_inventory/email_proses_principal_area_new",$data,TRUE);

        $this->email->from($from,'PT. Mulia Putra Mandiri');
        $this->email->to($to);
        $this->email->cc($email_cc);
        $this->email->subject($subject);
        $this->email->message($message);
        $send = $this->email->send();
        // echo $this->email->print_debugger();
        if ($send) {
            // echo "<script>alert('pengiriman email berhasil'); </script>";
            $this->session->set_flashdata("pesan_success", "pengiriman email berhasil. Terima kasih");
        }else{
            echo "<script>alert('pengiriman email gagal'); </script>";
            die;
        }

        redirect('management_inventory/pengajuan_retur_detail/'.$signature.'/'.$supp);

    }

    public function email_proses_mpm($signature){

        $get_pengajuan = $this->model_management_inventory->get_pengajuan($signature);

        foreach ($get_pengajuan->result() as $a) {
            $id_pengajuan           = $a->id;
            $site_code              = $a->site_code;
            $branch_name            = $a->branch_name;
            $nama_comp              = $a->nama_comp;
            $supp                   = $a->supp;
            $namasupp               = $a->namasupp;
            $no_pengajuan           = $a->no_pengajuan;
            $tanggal_pengajuan      = $a->tanggal_pengajuan;
            $nama                   = $a->nama;
            $status                 = $a->status;
            $nama_status            = $a->nama_status;
            $no_pengajuan           = $a->no_pengajuan;
            $created_by             = $a->created_by;
            $file                   = $a->file;
            $signature              = $a->signature;
            $verifikasi_at          = $a->verifikasi_at;
            $verifikasi_username    = $a->verifikasi_username;
            $signature              = $a->signature;
            $principal_area_at      = $a->principal_area_at;
            $principal_area_signature   = $a->principal_area_signature;
            $principal_area_username    = $a->principal_area_username;
            $file_principal_area        = $a->file_principal_area;
            $catatan_principal_area     = $a->catatan_principal_area;
            $principal_ho_at        = $a->principal_ho_at;
            $principal_ho_signature = $a->principal_ho_signature;
            $principal_ho_username  = $a->principal_ho_username;
            $file_principal_ho      = $a->file_principal_ho;
            $catatan_principal_ho   = $a->catatan_principal_ho;
            $tanggal_kirim_barang   = $a->tanggal_kirim_barang;
            $nama_ekspedisi         = $a->nama_ekspedisi;
            $est_tanggal_tiba       = $a->est_tanggal_tiba;
            $file_pengiriman        = $a->file_pengiriman;
            $username_kirim_barang  = $a->username_kirim_barang;
            $tanggal_terima_barang  = $a->tanggal_terima_barang;
            $nama_penerima          = $a->nama_penerima;
            $no_terima_barang       = $a->no_terima_barang;
            $file_terima_barang     = $a->file_terima_barang;
            $terima_barang_at       = $a->terima_barang_at;
            $username_terima_barang = $a->username_terima_barang;
        }

        $get_pengajuan_detail_summary = $this->model_management_inventory->get_pengajuan_detail_summary($id_pengajuan);
        foreach ($get_pengajuan_detail_summary->result() as $a) {
            $count_kodeprod = $a->count_kodeprod;
            $value_rbp = $a->value_rbp;
        }

        $get_email_to       = $this->model_management_inventory->get_email_to_retur_by_site_code($site_code, $supp)->row()->email;
        $get_username_to    = $this->model_management_inventory->get_email_to_retur_by_site_code($site_code, $supp)->row()->username;

        // echo "get_username_to : ".$get_username_to;
        // die;

        $data = [
            'get_pengajuan_detail'      => $this->model_management_inventory->get_pengajuan_detail($id_pengajuan),
            'no_pengajuan'              => $no_pengajuan,
            'branch_name'               => $branch_name,
            'nama_comp'                 => $nama_comp,
            'site_code'                 => $site_code,
            'namasupp'                  => $namasupp,
            'tanggal_pengajuan'         => $tanggal_pengajuan,
            'nama'                      => $nama,
            'status'                    => $status,
            'nama_status'               => $nama_status,
            'created_by'                => $created_by,
            'file'                      => $file,
            'id_pengajuan'              => $id_pengajuan,
            'supp'                      => $supp,
            'signature'                 => $signature,
            'verifikasi_at'             => $verifikasi_at,
            'verifikasi_username'       => $verifikasi_username,
            'count_kodeprod'            => $count_kodeprod,
            'value_rbp'                 => $value_rbp,
            'principal_area_at'         => $principal_area_at,
            'principal_area_signature'  => $principal_area_signature,
            'principal_area_username'   => $principal_area_username,
            'file_principal_area'       => $file_principal_area,
            'catatan_principal_area'    => $catatan_principal_area,
            'principal_ho_at'           => $principal_ho_at,
            'principal_ho_signature'    => $principal_ho_signature,
            'principal_ho_username'     => $principal_ho_username,
            'file_principal_ho'         => $file_principal_ho,
            'catatan_principal_ho'      => $catatan_principal_ho,
            'tanggal_kirim_barang'      => $tanggal_kirim_barang,
            'nama_ekspedisi'            => $nama_ekspedisi,
            'est_tanggal_tiba'          => $est_tanggal_tiba,
            'file_pengiriman'           => $file_pengiriman,
            'username_kirim_barang'     => $username_kirim_barang,
            'tanggal_terima_barang'     => $tanggal_terima_barang,
            'nama_penerima'             => $nama_penerima,
            'no_terima_barang'          => $no_terima_barang,
            'file_terima_barang'        => $file_terima_barang,
            'terima_barang_at'          => $terima_barang_at,
            'username_terima_barang'    => $username_terima_barang,
            'username_email'            => $get_username_to
        ];

        $from   = "suffy@muliaputramandiri.net";
        $to     = $get_email_to;
        // nanti cc nya linda@mpm
        // $cc     = 'suffy.mpm@gmail.com'; 

        $email_cc = $this->model_management_inventory->get_email($site_code)->row()->email . ",linda@muliaputramandiri.com, fakhrul@muliaputramandiri.com";

        // echo "get_email_to : ".$get_email_to;
        // echo "get_username_to : ".$get_username_to;
        // echo "email_cc : ".$email_cc;

        // die;

        $subject = "MPM SITE | RETUR : $no_pengajuan | ".$nama_status;
        $this->load->model('model_relokasi');
        $this->model_relokasi->email();

        $message = $this->load->view("management_inventory/email_proses_mpm",$data,TRUE);

        $this->email->from($from,'PT. Mulia Putra Mandiri');
        $this->email->to($to);
        $this->email->cc($email_cc);
        $this->email->subject($subject);
        $this->email->message($message);
        $send = $this->email->send();
        // echo $this->email->print_debugger();
        if ($send) {
            // echo "<script>alert('pengiriman email berhasil'); </script>";
            $this->session->set_flashdata("pesan_success", "pengiriman email berhasil. Terima kasih");
        }else{
            echo "<script>alert('pengiriman email gagal'); </script>";
            die;
        }

        redirect('management_inventory/pengajuan_retur_detail/'.$signature.'/'.$supp);

    }

    public function email_proses_principal_ho($signature){

        $this->load->model('model_retur');

        $get_pengajuan = $this->model_management_inventory->get_pengajuan($signature);
        foreach ($get_pengajuan->result() as $a) {
            $id_pengajuan           = $a->id;
            $site_code              = $a->site_code;
            $branch_name            = $a->branch_name;
            $nama_comp              = $a->nama_comp;
            $supp                   = $a->supp;
            $namasupp               = $a->namasupp;
            $no_pengajuan           = $a->no_pengajuan;
            $tanggal_pengajuan      = $a->tanggal_pengajuan;
            $nama                   = $a->nama;
            $status                 = $a->status;
            $nama_status            = $a->nama_status;
            $no_pengajuan           = $a->no_pengajuan;
            $created_by             = $a->created_by;
            $file                   = $a->file;
            $signature              = $a->signature;
            $verifikasi_at          = $a->verifikasi_at;
            $verifikasi_username    = $a->verifikasi_username;
            $signature              = $a->signature;
            $principal_area_at      = $a->principal_area_at;
            $principal_area_signature   = $a->principal_area_signature;
            $principal_area_username    = $a->principal_area_username;
            $file_principal_area        = $a->file_principal_area;
            $catatan_principal_area     = $a->catatan_principal_area;
            $principal_ho_at        = $a->principal_ho_at;
            $principal_ho_signature = $a->principal_ho_signature;
            $principal_ho_username  = $a->principal_ho_username;
            $file_principal_ho      = $a->file_principal_ho;
            $catatan_principal_ho   = $a->catatan_principal_ho;
            $tanggal_kirim_barang   = $a->tanggal_kirim_barang;
            $nama_ekspedisi         = $a->nama_ekspedisi;
            $est_tanggal_tiba       = $a->est_tanggal_tiba;
            $file_pengiriman        = $a->file_pengiriman;
            $username_kirim_barang  = $a->username_kirim_barang;
            $tanggal_terima_barang  = $a->tanggal_terima_barang;
            $nama_penerima          = $a->nama_penerima;
            $no_terima_barang       = $a->no_terima_barang;
            $file_terima_barang     = $a->file_terima_barang;
            $terima_barang_at       = $a->terima_barang_at;
            $username_terima_barang = $a->username_terima_barang;
        }

        // echo "principal_area_at : ".$principal_area_at;
        // die;

        $get_pengajuan_detail_summary = $this->model_management_inventory->get_pengajuan_detail_summary($id_pengajuan);
        foreach ($get_pengajuan_detail_summary->result() as $a) {
            $count_kodeprod = $a->count_kodeprod;
            $value_rbp = $a->value_rbp;
        }

        // echo "supp : ".$supp;
        if ($supp == '002' || $supp == '012' || $supp == '013' || $supp == '014' || $supp == '015') {
            $get_email_retur = $this->model_retur->get_email_principal($supp);
            foreach ($get_email_retur as $key) {
                $get_email_to = $key->email;
            } 
            $attach = $this->email->attach('assets/file/retur/'.str_replace('/','_',$no_pengajuan).'.pdf');
        }else{
            $get_email_to       = $this->model_management_inventory->get_email_ho_to_retur_by_site_code($site_code, $supp)->row()->email;
            $get_username_to    = $this->model_management_inventory->get_email_ho_to_retur_by_site_code($site_code, $supp)->row()->username;
            $attach = '';
        }
        // echo $get_email_to;
        // die;


        // echo "get_email_to : ".$get_email_to;
        // echo "get_username_to : ".$get_username_to;
        // die;

        $data = [
            'get_pengajuan_detail'      => $this->model_management_inventory->get_pengajuan_detail_email_ho($id_pengajuan),
            'get_pengajuan_detail_filter'      => $this->model_management_inventory->get_pengajuan_detail_filter($id_pengajuan),
            'no_pengajuan'              => $no_pengajuan,
            'branch_name'               => $branch_name,
            'nama_comp'                 => $nama_comp,
            'site_code'                 => $site_code,
            'namasupp'                  => $namasupp,
            'tanggal_pengajuan'         => $tanggal_pengajuan,
            'nama'                      => $nama,
            'status'                    => $status,
            'nama_status'               => $nama_status,
            'created_by'                => $created_by,
            'file'                      => $file,
            'id_pengajuan'              => $id_pengajuan,
            'supp'                      => $supp,
            'signature'                 => $signature,
            'verifikasi_at'             => $verifikasi_at,
            'verifikasi_username'       => $verifikasi_username,
            'count_kodeprod'            => $count_kodeprod,
            'value_rbp'                 => $value_rbp,
            'principal_area_at'         => $principal_area_at,
            'principal_area_signature'  => $principal_area_signature,
            'principal_area_username'   => $principal_area_username,
            'file_principal_area'       => $file_principal_area,
            'catatan_principal_area'    => $catatan_principal_area,
            'principal_ho_at'           => $principal_ho_at,
            'principal_ho_signature'    => $principal_ho_signature,
            'principal_ho_username'     => $principal_ho_username,
            'file_principal_ho'         => $file_principal_ho,
            'catatan_principal_ho'      => $catatan_principal_ho,
            'tanggal_kirim_barang'      => $tanggal_kirim_barang,
            'nama_ekspedisi'            => $nama_ekspedisi,
            'est_tanggal_tiba'          => $est_tanggal_tiba,
            'file_pengiriman'           => $file_pengiriman,
            'username_kirim_barang'     => $username_kirim_barang,
            'tanggal_terima_barang'     => $tanggal_terima_barang,
            'nama_penerima'             => $nama_penerima,
            'no_terima_barang'          => $no_terima_barang,
            'file_terima_barang'        => $file_terima_barang,
            'terima_barang_at'          => $terima_barang_at,
            'username_terima_barang'    => $username_terima_barang,
        ];

        $from = "suffy@muliaputramandiri.com";
        $to = $get_email_to;
        // $cc = 'suffy.mpm@gmail.com';

        $email_cc = $this->model_management_inventory->get_email($site_code)->row()->email . ', linda@muliaputramandiri.com, fakhrul@muliaputramandiri.com';

        $subject = "MPM SITE | RETUR : $no_pengajuan | PENDING PRINCIPAL HO";
        $this->load->model('model_relokasi');
        $this->model_relokasi->email();

        $message = $this->load->view("management_inventory/email_proses_principal_ho",$data,TRUE);

        $this->email->from($from,'PT. Mulia Putra Mandiri');
        $this->email->to($to);
        $this->email->cc($email_cc);
        $this->email->subject($subject);
        $this->email->message($message);
        $send = $this->email->send();
        // echo $this->email->print_debugger();
        if ($send) {
            // echo "<script>alert('pengiriman email berhasil'); </script>";
            $this->session->set_flashdata("pesan_success", "pengiriman email berhasil");
        }else{
            echo "<script>alert('pengiriman email gagal'); </script>";
            die;
        }

        redirect('management_inventory/verifikasi_retur/'.$signature.'/'.$supp);

    }

    public function email_principal_ho_success($signature){
        $get_pengajuan = $this->model_management_inventory->get_pengajuan($signature);
        foreach ($get_pengajuan->result() as $a) {
            $id_pengajuan           = $a->id;
            $site_code              = $a->site_code;
            $branch_name            = $a->branch_name;
            $nama_comp              = $a->nama_comp;
            $supp                   = $a->supp;
            $namasupp               = $a->namasupp;
            $no_pengajuan           = $a->no_pengajuan;
            $tanggal_pengajuan      = $a->tanggal_pengajuan;
            $nama                   = $a->nama;
            $status                 = $a->status;
            $nama_status            = $a->nama_status;
            $no_pengajuan           = $a->no_pengajuan;
            $created_by             = $a->created_by;
            $file                   = $a->file;
            $signature              = $a->signature;
            $verifikasi_at          = $a->verifikasi_at;
            $verifikasi_username    = $a->verifikasi_username;
            $signature              = $a->signature;
            $principal_area_at      = $a->principal_area_at;
            $principal_area_signature   = $a->principal_area_signature;
            $principal_area_username    = $a->principal_area_username;
            $file_principal_area        = $a->file_principal_area;
            $catatan_principal_area     = $a->catatan_principal_area;
            $status_principal_ho        = $a->status_principal_ho;
            $nama_status_principal_ho   = $a->nama_status_principal_ho;
            $principal_ho_at        = $a->principal_ho_at;
            $principal_ho_signature = $a->principal_ho_signature;
            $principal_ho_username  = $a->principal_ho_username;
            $file_principal_ho      = $a->file_principal_ho;
            $catatan_principal_ho   = $a->catatan_principal_ho;
            $tanggal_kirim_barang   = $a->tanggal_kirim_barang;
            $nama_ekspedisi         = $a->nama_ekspedisi;
            $est_tanggal_tiba       = $a->est_tanggal_tiba;
            $file_pengiriman        = $a->file_pengiriman;
            $username_kirim_barang  = $a->username_kirim_barang;
            $tanggal_terima_barang  = $a->tanggal_terima_barang;
            $nama_penerima          = $a->nama_penerima;
            $no_terima_barang       = $a->no_terima_barang;
            $file_terima_barang     = $a->file_terima_barang;
            $terima_barang_at       = $a->terima_barang_at;
            $username_terima_barang = $a->username_terima_barang;
        }

        // echo "principal_area_at : ".$principal_area_at;
        // die;

        $get_pengajuan_detail_summary = $this->model_management_inventory->get_pengajuan_detail_summary($id_pengajuan);
        foreach ($get_pengajuan_detail_summary->result() as $a) {
            $count_kodeprod = $a->count_kodeprod;
            $value_rbp = $a->value_rbp;
        }

        $get_email_cc       = $this->model_management_inventory->get_email_to_retur_by_site_code($site_code, $supp)->row()->email . ',suffy.yanuar@gmail.com';
        $get_username_cc    = $this->model_management_inventory->get_email_to_retur_by_site_code($site_code, $supp)->row()->username;

        $data = [
            'get_pengajuan_detail'      => $this->model_management_inventory->get_pengajuan_detail($id_pengajuan),
            'get_pengajuan_detail_filter'      => $this->model_management_inventory->get_pengajuan_detail_filter($id_pengajuan),
            'no_pengajuan'              => $no_pengajuan,
            'branch_name'               => $branch_name,
            'nama_comp'                 => $nama_comp,
            'site_code'                 => $site_code,
            'namasupp'                  => $namasupp,
            'tanggal_pengajuan'         => $tanggal_pengajuan,
            'nama'                      => $nama,
            'status'                    => $status,
            'nama_status'               => $nama_status,
            'created_by'                => $created_by,
            'file'                      => $file,
            'id_pengajuan'              => $id_pengajuan,
            'supp'                      => $supp,
            'signature'                 => $signature,
            'verifikasi_at'             => $verifikasi_at,
            'verifikasi_username'       => $verifikasi_username,
            'count_kodeprod'            => $count_kodeprod,
            'value_rbp'                 => $value_rbp,
            'principal_area_at'         => $principal_area_at,
            'principal_area_signature'  => $principal_area_signature,
            'principal_area_username'   => $principal_area_username,
            'file_principal_area'       => $file_principal_area,
            'catatan_principal_area'    => $catatan_principal_area,
            'status_principal_ho'       => $status_principal_ho,
            'nama_status_principal_ho'  => $nama_status_principal_ho,
            'principal_ho_at'           => $principal_ho_at,
            'principal_ho_signature'    => $principal_ho_signature,
            'principal_ho_username'     => $principal_ho_username,
            'file_principal_ho'         => $file_principal_ho,
            'catatan_principal_ho'      => $catatan_principal_ho,
            'tanggal_kirim_barang'      => $tanggal_kirim_barang,
            'nama_ekspedisi'            => $nama_ekspedisi,
            'est_tanggal_tiba'          => $est_tanggal_tiba,
            'file_pengiriman'           => $file_pengiriman,
            'username_kirim_barang'     => $username_kirim_barang,
            'tanggal_terima_barang'     => $tanggal_terima_barang,
            'nama_penerima'             => $nama_penerima,
            'no_terima_barang'          => $no_terima_barang,
            'file_terima_barang'        => $file_terima_barang,
            'terima_barang_at'          => $terima_barang_at,
            'username_terima_barang'    => $username_terima_barang,
        ];

        $from = "suffy@muliaputramandiri.com";

        $email_to = $this->model_management_inventory->get_email($site_code)->row()->email . ', linda@muliaputramandiri.com, fakhrul@muliaputramandiri.com';

        $subject = "MPM SITE | RETUR : $no_pengajuan | ".$nama_status;
        $this->load->model('model_relokasi');
        $this->model_relokasi->email();

        $message = $this->load->view("management_inventory/email_principal_success",$data,TRUE);

        $this->email->from($from,'PT. Mulia Putra Mandiri');
        $this->email->to($email_to);
        $this->email->cc($get_email_cc);
        $this->email->subject($subject);
        $this->email->message($message);
        $send = $this->email->send();
        // echo $this->email->print_debugger();
        if ($send) 
        {
            $this->session->set_flashdata("pesan_success", "Pengiriman email berhasil. Terima kasih");    
        }else{
            echo "<script>alert('pengiriman email gagal'); </script>";
            die;
        }

        redirect('management_inventory/principal_ho/'.$signature.'/'.$supp);

    }

    public function email_kirim_barang($signature){
        $get_pengajuan = $this->model_management_inventory->get_pengajuan($signature);
        foreach ($get_pengajuan->result() as $a) {
            $id_pengajuan           = $a->id;
            $site_code              = $a->site_code;
            $branch_name            = $a->branch_name;
            $nama_comp              = $a->nama_comp;
            $supp                   = $a->supp;
            $namasupp               = $a->namasupp;
            $no_pengajuan           = $a->no_pengajuan;
            $tanggal_pengajuan      = $a->tanggal_pengajuan;
            $nama                   = $a->nama;
            $status                 = $a->status;
            $nama_status            = $a->nama_status;
            $no_pengajuan           = $a->no_pengajuan;
            $created_by             = $a->created_by;
            $file                   = $a->file;
            $signature              = $a->signature;
            $verifikasi_at          = $a->verifikasi_at;
            $verifikasi_username    = $a->verifikasi_username;
            $signature              = $a->signature;
            $principal_area_at      = $a->principal_area_at;
            $principal_area_signature   = $a->principal_area_signature;
            $principal_area_username    = $a->principal_area_username;
            $file_principal_area        = $a->file_principal_area;
            $catatan_principal_area     = $a->catatan_principal_area;
            $principal_ho_at        = $a->principal_ho_at;
            $principal_ho_signature = $a->principal_ho_signature;
            $principal_ho_username  = $a->principal_ho_username;
            $file_principal_ho      = $a->file_principal_ho;
            $catatan_principal_ho   = $a->catatan_principal_ho;
            $tanggal_kirim_barang   = $a->tanggal_kirim_barang;
            $nama_ekspedisi         = $a->nama_ekspedisi;
            $est_tanggal_tiba       = $a->est_tanggal_tiba;
            $file_pengiriman        = $a->file_pengiriman;
            $proses_kirim_barang_at = $a->proses_kirim_barang_at;
            $username_kirim_barang  = $a->username_kirim_barang;
            $tanggal_terima_barang  = $a->tanggal_terima_barang;
            $nama_penerima          = $a->nama_penerima;
            $no_terima_barang       = $a->no_terima_barang;
            $file_terima_barang     = $a->file_terima_barang;
            $terima_barang_at       = $a->terima_barang_at;
            $username_terima_barang = $a->username_terima_barang;            
        }

        // echo "principal_area_at : ".$principal_area_at;
        // die;

        $get_pengajuan_detail_summary = $this->model_management_inventory->get_pengajuan_detail_summary($id_pengajuan);
        foreach ($get_pengajuan_detail_summary->result() as $a) {
            $count_kodeprod = $a->count_kodeprod;
            $value_rbp = $a->value_rbp;
        }

        $get_email_to       = $this->model_management_inventory->get_email_to_retur_by_site_code($site_code, $supp)->row()->email;
        $get_username_to    = $this->model_management_inventory->get_email_to_retur_by_site_code($site_code, $supp)->row()->username;

        $data = [
            'get_pengajuan_detail'      => $this->model_management_inventory->get_pengajuan_detail($id_pengajuan),
            'get_pengajuan_detail_filter'      => $this->model_management_inventory->get_pengajuan_detail_filter($id_pengajuan),
            'no_pengajuan'              => $no_pengajuan,
            'branch_name'               => $branch_name,
            'nama_comp'                 => $nama_comp,
            'site_code'                 => $site_code,
            'namasupp'                  => $namasupp,
            'tanggal_pengajuan'         => $tanggal_pengajuan,
            'nama'                      => $nama,
            'status'                    => $status,
            'nama_status'               => $nama_status,
            'created_by'                => $created_by,
            'file'                      => $file,
            'id_pengajuan'              => $id_pengajuan,
            'supp'                      => $supp,
            'signature'                 => $signature,
            'verifikasi_at'             => $verifikasi_at,
            'verifikasi_username'       => $verifikasi_username,
            'count_kodeprod'            => $count_kodeprod,
            'value_rbp'                 => $value_rbp,
            'principal_area_at'         => $principal_area_at,
            'principal_area_signature'  => $principal_area_signature,
            'principal_area_username'   => $principal_area_username,
            'file_principal_area'       => $file_principal_area,
            'catatan_principal_area'    => $catatan_principal_area,
            'principal_ho_at'           => $principal_ho_at,
            'principal_ho_signature'    => $principal_ho_signature,
            'principal_ho_username'     => $principal_ho_username,
            'file_principal_ho'         => $file_principal_ho,
            'catatan_principal_ho'      => $catatan_principal_ho,
            'tanggal_kirim_barang'      => $tanggal_kirim_barang,
            'nama_ekspedisi'            => $nama_ekspedisi,
            'est_tanggal_tiba'          => $est_tanggal_tiba,
            'file_pengiriman'           => $file_pengiriman,
            'proses_kirim_barang_at'    => $proses_kirim_barang_at,
            'username_kirim_barang'     => $username_kirim_barang,
            'tanggal_terima_barang'     => $tanggal_terima_barang,
            'nama_penerima'             => $nama_penerima,
            'no_terima_barang'          => $no_terima_barang,
            'file_terima_barang'        => $file_terima_barang,
            'terima_barang_at'          => $terima_barang_at,
            'username_terima_barang'    => $username_terima_barang,
        ];

        $from = "suffy@muliaputramandiri.com";
        // $to = 'suffy.yanuar@gmail.com';

        $email_cc = $this->model_management_inventory->get_email($site_code)->row()->email . ', linda@muliaputramandiri.com, fakhrul@muliaputramandiri.com';

        $subject = "MPM SITE | RETUR : $no_pengajuan | ".$nama_status;
        $this->load->model('model_relokasi');
        $this->model_relokasi->email();

        $message = $this->load->view("management_inventory/email_kirim_barang",$data,TRUE);

        $this->email->from($from,'PT. Mulia Putra Mandiri');
        $this->email->to($get_email_to);
        $this->email->cc($email_cc);
        $this->email->subject($subject);
        $this->email->message($message);
        $send = $this->email->send();
        // echo $this->email->print_debugger();
        if ($send) {
            // echo "<script>alert('pengiriman email berhasil'); </script>";
            $this->session->set_flashdata("pesan_success", "pengiriman email berhasil");
        }else{
            echo "<script>alert('pengiriman email gagal'); </script>";
            die;
        }

        redirect('management_inventory/kirim_barang/'.$signature.'/'.$supp);

    }

    public function email_terima_barang($signature){
        $get_pengajuan = $this->model_management_inventory->get_pengajuan($signature);
        foreach ($get_pengajuan->result() as $a) {
            $id_pengajuan           = $a->id;
            $site_code              = $a->site_code;
            $branch_name            = $a->branch_name;
            $nama_comp              = $a->nama_comp;
            $supp                   = $a->supp;
            $namasupp               = $a->namasupp;
            $no_pengajuan           = $a->no_pengajuan;
            $tanggal_pengajuan      = $a->tanggal_pengajuan;
            $nama                   = $a->nama;
            $status                 = $a->status;
            $nama_status            = $a->nama_status;
            $no_pengajuan           = $a->no_pengajuan;
            $created_by             = $a->created_by;
            $file                   = $a->file;
            $signature              = $a->signature;
            $verifikasi_at          = $a->verifikasi_at;
            $verifikasi_username    = $a->verifikasi_username;
            $signature              = $a->signature;
            $principal_area_at      = $a->principal_area_at;
            $principal_area_signature   = $a->principal_area_signature;
            $principal_area_username    = $a->principal_area_username;
            $file_principal_area        = $a->file_principal_area;
            $catatan_principal_area     = $a->catatan_principal_area;
            $principal_ho_at        = $a->principal_ho_at;
            $principal_ho_signature = $a->principal_ho_signature;
            $principal_ho_username  = $a->principal_ho_username;
            $file_principal_ho      = $a->file_principal_ho;
            $catatan_principal_ho   = $a->catatan_principal_ho;
            $tanggal_kirim_barang   = $a->tanggal_kirim_barang;
            $nama_ekspedisi         = $a->nama_ekspedisi;
            $est_tanggal_tiba       = $a->est_tanggal_tiba;
            $file_pengiriman        = $a->file_pengiriman;
            $proses_kirim_barang_at = $a->proses_kirim_barang_at;
            $username_kirim_barang  = $a->username_kirim_barang;
            $tanggal_terima_barang  = $a->tanggal_terima_barang;
            $nama_penerima          = $a->nama_penerima;
            $no_terima_barang       = $a->no_terima_barang;
            $file_terima_barang     = $a->file_terima_barang;
            $terima_barang_at       = $a->terima_barang_at;
            $username_terima_barang = $a->username_terima_barang;
            $terima_barang_by       = $a->terima_barang_by;
        }

        // echo "principal_area_at : ".$principal_area_at;
        // die;

        $get_pengajuan_detail_summary = $this->model_management_inventory->get_pengajuan_detail_summary($id_pengajuan);
        foreach ($get_pengajuan_detail_summary->result() as $a) {
            $count_kodeprod = $a->count_kodeprod;
            $value_rbp = $a->value_rbp;
        }

        $get_email_to       = $this->model_management_inventory->get_email_to_retur_by_site_code($site_code, $supp)->row()->email;
        $get_username_to    = $this->model_management_inventory->get_email_to_retur_by_site_code($site_code, $supp)->row()->username;


        $data = [
            'get_pengajuan_detail'      => $this->model_management_inventory->get_pengajuan_detail($id_pengajuan),
            'get_pengajuan_detail_filter'      => $this->model_management_inventory->get_pengajuan_detail_filter($id_pengajuan),
            'no_pengajuan'              => $no_pengajuan,
            'branch_name'               => $branch_name,
            'nama_comp'                 => $nama_comp,
            'site_code'                 => $site_code,
            'namasupp'                  => $namasupp,
            'tanggal_pengajuan'         => $tanggal_pengajuan,
            'nama'                      => $nama,
            'status'                    => $status,
            'nama_status'               => $nama_status,
            'created_by'                => $created_by,
            'file'                      => $file,
            'id_pengajuan'              => $id_pengajuan,
            'supp'                      => $supp,
            'signature'                 => $signature,
            'verifikasi_at'             => $verifikasi_at,
            'verifikasi_username'       => $verifikasi_username,
            'count_kodeprod'            => $count_kodeprod,
            'value_rbp'                 => $value_rbp,
            'principal_area_at'         => $principal_area_at,
            'principal_area_signature'  => $principal_area_signature,
            'principal_area_username'   => $principal_area_username,
            'file_principal_area'       => $file_principal_area,
            'catatan_principal_area'    => $catatan_principal_area,
            'principal_ho_at'           => $principal_ho_at,
            'principal_ho_signature'    => $principal_ho_signature,
            'principal_ho_username'     => $principal_ho_username,
            'file_principal_ho'         => $file_principal_ho,
            'catatan_principal_ho'      => $catatan_principal_ho,
            'tanggal_kirim_barang'      => $tanggal_kirim_barang,
            'nama_ekspedisi'            => $nama_ekspedisi,
            'est_tanggal_tiba'          => $est_tanggal_tiba,
            'file_pengiriman'           => $file_pengiriman,
            'proses_kirim_barang_at'    => $proses_kirim_barang_at,
            'username_kirim_barang'     => $username_kirim_barang,
            'tanggal_terima_barang'     => $tanggal_terima_barang,
            'nama_penerima'             => $nama_penerima,
            'no_terima_barang'          => $no_terima_barang,
            'file_terima_barang'        => $file_terima_barang,
            'terima_barang_at'          => $terima_barang_at,
            'username_terima_barang'    => $username_terima_barang,
            'terima_barang_by'          => $terima_barang_by,
        ];

        $from = "suffy@muliaputramandiri.com";
        // $to = 'suffy.yanuar@gmail.com';
        // $cc = 'suffy.mpm@gmail.com';

        $get_email_principal_terima_barang = $this->model_management_inventory->get_user($terima_barang_by);
        if ($get_email_principal_terima_barang->num_rows() > 0) {
            $email_principal_terima_barang = ",".$get_email_principal_terima_barang->row()->email;
        }else{
            $email_principal_terima_barang = "";
        }

        $email_cc = $this->model_management_inventory->get_email($site_code)->row()->email . ', linda@muliaputramandiri.com, fakhrul@muliaputramandiri.com'.$email_principal_terima_barang;

        $subject = "MPM SITE | RETUR : $no_pengajuan | BARANG DITERIMA OLEH PRINCIPAL";
        $this->load->model('model_relokasi');
        $this->model_relokasi->email();

        $message = $this->load->view("management_inventory/email_terima_barang",$data,TRUE);

        $this->email->from($from,'PT. Mulia Putra Mandiri');
        $this->email->to($email_cc);
        $this->email->cc($get_email_to);
        $this->email->subject($subject);
        $this->email->message($message);
        $send = $this->email->send();
        // echo $this->email->print_debugger();

        // echo "email_principal_terima_barang : ".$email_principal_terima_barang;
        // echo "email_cc : ".$email_cc;
        // echo "get_email_to : ".$get_email_to;
        // die;

        if ($send) {
            // echo "<script>alert('pengiriman email berhasil'); </script>";
            $this->session->set_flashdata("pesan_success", "pengiriman email berhasil");
        }else{
            echo "<script>alert('pengiriman email gagal'); </script>";
            die;
        }

        redirect('management_inventory/terima_barang/'.$signature.'/'.$supp);

    }

    public function kirim_barang($signature, $supp){

        $get_pengajuan = $this->model_management_inventory->get_pengajuan($signature);
        foreach ($get_pengajuan->result() as $a) {
            $id_pengajuan               = $a->id;
            $site_code                  = $a->site_code;
            $branch_name                = $a->branch_name;
            $nama_comp                  = $a->nama_comp;
            $supp                       = $a->supp;
            $namasupp                   = $a->namasupp;
            $no_pengajuan               = $a->no_pengajuan;
            $tanggal_pengajuan          = $a->tanggal_pengajuan;
            $nama                       = $a->nama;
            $status                     = $a->status;
            $nama_status                = $a->nama_status;
            $no_pengajuan               = $a->no_pengajuan;
            $created_at                 = $a->created_at;
            $file                       = $a->file;
            $digital_signature          = $a->digital_signature;
            $signature                  = $a->signature;
            $verifikasi_at              = $a->verifikasi_at;
            $verifikasi_username        = $a->verifikasi_username;
            $verifikasi_signature       = $a->verifikasi_signature;
            $principal_area_at          = $a->principal_area_at;
            $principal_area_signature   = $a->principal_area_signature;
            $principal_area_username    = $a->principal_area_username;
            $file_principal_area        = $a->file_principal_area;
            $catatan_principal_area     = $a->catatan_principal_area;
            $principal_ho_at            = $a->principal_ho_at;
            $principal_ho_signature     = $a->principal_ho_signature;
            $principal_ho_username      = $a->principal_ho_username;
            $file_principal_ho          = $a->file_principal_ho;
            $catatan_principal_ho       = $a->catatan_principal_ho;
            $tanggal_kirim_barang       = $a->tanggal_kirim_barang;
            $nama_ekspedisi             = $a->nama_ekspedisi;
            $est_tanggal_tiba           = $a->est_tanggal_tiba;
            $file_pengiriman            = $a->file_pengiriman;
            $proses_kirim_barang_at     = $a->proses_kirim_barang_at;
            $username_kirim_barang      = $a->username_kirim_barang;
            $tanggal_terima_barang      = $a->tanggal_terima_barang;
            $nama_penerima              = $a->nama_penerima;
            $no_terima_barang           = $a->no_terima_barang;
            $file_terima_barang         = $a->file_terima_barang;
            $terima_barang_at           = $a->terima_barang_at;
            $username_terima_barang     = $a->username_terima_barang;
            $tanggal_pemusnahan         = $a->tanggal_pemusnahan;
            $nama_pemusnahan            = $a->nama_pemusnahan;
            $file_pemusnahan            = $a->file_pemusnahan;
            $foto_pemusnahan_1          = $a->foto_pemusnahan_1;
            $foto_pemusnahan_2          = $a->foto_pemusnahan_2;
            $pemusnahan_at              = $a->pemusnahan_at;
            $username_pemusnahan        = $a->username_pemusnahan;
            $video                      = $a->video;
        }

        $get_pengajuan_detail_summary = $this->model_management_inventory->get_pengajuan_detail_summary($id_pengajuan);
        foreach ($get_pengajuan_detail_summary->result() as $a) {
            $count_kodeprod = $a->count_kodeprod;
            $value_rbp = $a->value_rbp;
        }

        $data = [
            'title'                     => 'Pending Kirim Barang',
            'url'                       => 'management_inventory/kirim_barang_proses',
            'url_pengajuan'             => 'management_inventory/proses_mpm',
            'get_pengajuan_detail'       => $this->model_management_inventory->get_pengajuan_detail($id_pengajuan),
            'get_pengajuan_detail_accordion'       => $this->model_management_inventory->get_pengajuan_detail_accordion($id_pengajuan),
            // 'site_code'                  => $this->model_management_inventory->get_sitecode(),
            'id_pengajuan'               => $id_pengajuan,
            'site_code'                  => $site_code,
            'branch_name'                => $branch_name,
            'nama_comp'                  => $nama_comp,
            'supp'                       => $supp,
            'namasupp'                   => $namasupp,
            'no_pengajuan'               => $no_pengajuan,
            'tanggal_pengajuan'          => $tanggal_pengajuan,
            'nama'                       => $nama,
            'status'                     => $status,
            'nama_status'                => $nama_status,
            'no_pengajuan'               => $no_pengajuan,
            'created_at'                 => $created_at,
            'file'                       => $file,
            'digital_signature'          => $digital_signature,
            'signature'                  => $signature,
            'verifikasi_at'              => $verifikasi_at,
            'verifikasi_username'        => $verifikasi_username,
            'verifikasi_signature'       => $verifikasi_signature,
            'principal_area_at'          => $principal_area_at,
            'principal_area_signature'   => $principal_area_signature,
            'principal_area_username'    => $principal_area_username,
            'file_principal_area'        => $file_principal_area,
            'catatan_principal_area'     => $catatan_principal_area,
            'principal_ho_at'            => $principal_ho_at,
            'principal_ho_signature'     => $principal_ho_signature,
            'principal_ho_username'      => $principal_ho_username,
            'file_principal_ho'          => $file_principal_ho,
            'catatan_principal_ho'       => $catatan_principal_ho,
            'tanggal_kirim_barang'       => $tanggal_kirim_barang,
            'nama_ekspedisi'             => $nama_ekspedisi,
            'est_tanggal_tiba'           => $est_tanggal_tiba,
            'file_pengiriman'            => $file_pengiriman,
            'proses_kirim_barang_at'     => $proses_kirim_barang_at,
            'username_kirim_barang'      => $username_kirim_barang,
            'tanggal_terima_barang'      => $tanggal_terima_barang,
            'nama_penerima'              => $nama_penerima,
            'no_terima_barang'           => $no_terima_barang,
            'file_terima_barang'         => $file_terima_barang,
            'terima_barang_at'           => $terima_barang_at,
            'username_terima_barang'     => $username_terima_barang,
            'count_kodeprod'             => $count_kodeprod,
            'value_rbp'                  => $value_rbp,
            'tanggal_pemusnahan'         => $tanggal_pemusnahan,
            'nama_pemusnahan'            => $nama_pemusnahan,
            'file_pemusnahan'            => $file_pemusnahan,
            'foto_pemusnahan_1'          => $foto_pemusnahan_1,
            'foto_pemusnahan_2'          => $foto_pemusnahan_2,
            'pemusnahan_at'              => $pemusnahan_at,
            'username_pemusnahan'        => $username_pemusnahan,
            'video'                      => $video,
        ];

        $this->navbar($data);

        $this->load->view('kalimantan/header_full_width', $data);
        $this->load->view('kalimantan/accordion', $data);
        $this->load->view('management_inventory/kirim_barang', $data);
        $this->load->view('kalimantan/footer');
    }

    public function kirim_barang_proses(){

        $file = $this->input->post('file'); 

        if (!is_dir('./assets/file/retur/')) {
            @mkdir('./assets/file/retur/', 0777);
        }

        $config['upload_path'] = './assets/file/retur/';
        $config['allowed_types'] = '*';
        $config['max_size'] = '*';

        $this->load->library('upload', $config);
        if (!$this->upload->do_upload('file')) {
            $filename = '';
        } else {

            $data = array('upload_data' => $this->upload->data());
            $file = $data['upload_data']['full_path'];
            chmod($file, 0777);
            $upload_data = $this->upload->data();
            $filename = $upload_data['file_name'];
        }

        $created_at = $this->model_outlet_transaksi->timezone();
        // $this->db->trans_start();

        $data = [
            'file_pengiriman'         => $filename,
            'tanggal_kirim_barang'    => $this->input->post('tanggal_kirim_barang'),
            'nama_ekspedisi'          => $this->input->post('nama_ekspedisi'),
            'est_tanggal_tiba'        => $this->input->post('est_tanggal_tiba'),
            'status'                  => 6,
            'nama_status'             => "PENDING TERIMA BARANG",
            'proses_kirim_barang_at'  => $created_at,
            'proses_kirim_barang_by'  => $this->session->userdata('id')
        ];

        $this->db->where('signature', $this->input->post('signature'));
        $this->db->update('management_inventory.pengajuan_retur', $data);
        
        // $this->db->trans_complete();
        // if ($this->db->trans_status() === FALSE)
        // {
        //     echo "ada kegagalan dalam proses kirim barang. Mungkin disebabkan internet. rollback diaktifkan ke keadaan sebelumnya";
        //     die;
        // }

        redirect('management_inventory/email_kirim_barang/'.$this->input->post('signature').'/'.$this->input->post('supp'));
    }

    public function terima_barang($signature, $supp){

        $get_pengajuan = $this->model_management_inventory->get_pengajuan($signature);
        foreach ($get_pengajuan->result() as $a) {
            $id_pengajuan               = $a->id;
            $site_code                  = $a->site_code;
            $branch_name                = $a->branch_name;
            $nama_comp                  = $a->nama_comp;
            $supp                       = $a->supp;
            $namasupp                   = $a->namasupp;
            $no_pengajuan               = $a->no_pengajuan;
            $tanggal_pengajuan          = $a->tanggal_pengajuan;
            $nama                       = $a->nama;
            $status                     = $a->status;
            $nama_status                = $a->nama_status;
            $no_pengajuan               = $a->no_pengajuan;
            $created_at                 = $a->created_at;
            $file                       = $a->file;
            $digital_signature          = $a->digital_signature;
            $signature                  = $a->signature;
            $verifikasi_at              = $a->verifikasi_at;
            $verifikasi_username        = $a->verifikasi_username;
            $verifikasi_signature       = $a->verifikasi_signature;
            $principal_area_at          = $a->principal_area_at;
            $principal_area_signature   = $a->principal_area_signature;
            $principal_area_username    = $a->principal_area_username;
            $file_principal_area        = $a->file_principal_area;
            $catatan_principal_area     = $a->catatan_principal_area;
            $principal_ho_at            = $a->principal_ho_at;
            $principal_ho_signature     = $a->principal_ho_signature;
            $principal_ho_username      = $a->principal_ho_username;
            $file_principal_ho          = $a->file_principal_ho;
            $catatan_principal_ho       = $a->catatan_principal_ho;
            $tanggal_kirim_barang       = $a->tanggal_kirim_barang;
            $nama_ekspedisi             = $a->nama_ekspedisi;
            $est_tanggal_tiba           = $a->est_tanggal_tiba;
            $file_pengiriman            = $a->file_pengiriman;
            $proses_kirim_barang_at     = $a->proses_kirim_barang_at;
            $username_kirim_barang      = $a->username_kirim_barang;
            $tanggal_terima_barang      = $a->tanggal_terima_barang;
            $nama_penerima              = $a->nama_penerima;
            $no_terima_barang           = $a->no_terima_barang;
            $file_terima_barang         = $a->file_terima_barang;
            $terima_barang_at           = $a->terima_barang_at;
            $username_terima_barang     = $a->username_terima_barang;
            $tanggal_pemusnahan         = $a->tanggal_pemusnahan;
            $nama_pemusnahan            = $a->nama_pemusnahan;
            $file_pemusnahan            = $a->file_pemusnahan;
            $foto_pemusnahan_1          = $a->foto_pemusnahan_1;
            $foto_pemusnahan_2          = $a->foto_pemusnahan_2;
            $pemusnahan_at              = $a->pemusnahan_at;
            $username_pemusnahan        = $a->username_pemusnahan;
            $video                      = $a->video;
        }

        $get_pengajuan_detail_summary = $this->model_management_inventory->get_pengajuan_detail_summary($id_pengajuan);
        foreach ($get_pengajuan_detail_summary->result() as $a) {
            $count_kodeprod = $a->count_kodeprod;
            $value_rbp = $a->value_rbp;
        }

        $data = [
            'title'                      => 'Pending Terima Barang',
            'url'                        => 'management_inventory/terima_barang_proses',
            'url_pengajuan'              => 'management_inventory/proses_mpm',
            'get_pengajuan_detail'       => $this->model_management_inventory->get_pengajuan_detail($id_pengajuan),
            'get_pengajuan_detail_accordion'       => $this->model_management_inventory->get_pengajuan_detail_accordion($id_pengajuan),
            // 'site_code'                  => $this->model_management_inventory->get_sitecode(),
            'id_pengajuan'               => $id_pengajuan,
            'site_code'                  => $site_code,
            'branch_name'                => $branch_name,
            'nama_comp'                  => $nama_comp,
            'supp'                       => $supp,
            'namasupp'                   => $namasupp,
            'no_pengajuan'               => $no_pengajuan,
            'tanggal_pengajuan'          => $tanggal_pengajuan,
            'nama'                       => $nama,
            'status'                     => $status,
            'nama_status'                => $nama_status,
            'no_pengajuan'               => $no_pengajuan,
            'created_at'                 => $created_at,
            'file'                       => $file,
            'digital_signature'          => $digital_signature,
            'signature'                  => $signature,
            'verifikasi_at'              => $verifikasi_at,
            'verifikasi_username'        => $verifikasi_username,
            'verifikasi_signature'       => $verifikasi_signature,
            'principal_area_at'          => $principal_area_at,
            'principal_area_signature'   => $principal_area_signature,
            'principal_area_username'    => $principal_area_username,
            'file_principal_area'        => $file_principal_area,
            'catatan_principal_area'     => $catatan_principal_area,
            'principal_ho_at'            => $principal_ho_at,
            'principal_ho_signature'     => $principal_ho_signature,
            'principal_ho_username'      => $principal_ho_username,
            'file_principal_ho'          => $file_principal_ho,
            'catatan_principal_ho'       => $catatan_principal_ho,
            'tanggal_kirim_barang'       => $tanggal_kirim_barang,
            'nama_ekspedisi'             => $nama_ekspedisi,
            'est_tanggal_tiba'           => $est_tanggal_tiba,
            'file_pengiriman'            => $file_pengiriman,
            'proses_kirim_barang_at'     => $proses_kirim_barang_at,
            'username_kirim_barang'      => $username_kirim_barang,
            'tanggal_terima_barang'      => $tanggal_terima_barang,
            'nama_penerima'              => $nama_penerima,
            'no_terima_barang'           => $no_terima_barang,
            'file_terima_barang'         => $file_terima_barang,
            'terima_barang_at'           => $terima_barang_at,
            'username_terima_barang'     => $username_terima_barang,
            'count_kodeprod'             => $count_kodeprod,
            'value_rbp'                  => $value_rbp,
            'tanggal_pemusnahan'         => $tanggal_pemusnahan,
            'nama_pemusnahan'            => $nama_pemusnahan,
            'file_pemusnahan'            => $file_pemusnahan,
            'foto_pemusnahan_1'          => $foto_pemusnahan_1,
            'foto_pemusnahan_2'          => $foto_pemusnahan_2,
            'pemusnahan_at'              => $pemusnahan_at,
            'username_pemusnahan'        => $username_pemusnahan,
            'status_ho'                  => $this->model_management_inventory->get_level_ho($site_code, $supp),
            'video'                      => $video,
        ];

        $this->navbar($data);

        $this->load->view('kalimantan/header_full_width', $data);
        $this->load->view('kalimantan/accordion', $data);
        $this->load->view('management_inventory/terima_barang', $data);
        $this->load->view('kalimantan/footer');
    }

    public function terima_barang_proses(){

        $file = $this->input->post('file'); 

        if (!is_dir('./assets/file/retur/')) {
            @mkdir('./assets/file/retur/', 0777);
        }

        $config['upload_path'] = './assets/file/retur/';
        $config['allowed_types'] = '*';
        $config['max_size'] = '*';

        $this->load->library('upload', $config);
        if (!$this->upload->do_upload('file')) {
            $filename = '';
        } else {

            $data = array('upload_data' => $this->upload->data());
            $file = $data['upload_data']['full_path'];
            chmod($file, 0777);
            $upload_data = $this->upload->data();
            $filename = $upload_data['file_name'];
        }

        $created_at = $this->model_outlet_transaksi->timezone();
        // $this->db->trans_start();

        $data = [
            'file_terima_barang'     => $filename,
            'tanggal_terima_barang'  => $this->input->post('tanggal_terima_barang'),
            'nama_penerima'          => $this->input->post('nama_penerima'),
            'no_terima_barang'       => $this->input->post('nomor_lpk'),
            'status'                 => 8,
            'nama_status'            => "BARANG DITERIMA",
            'terima_barang_at'       => $created_at,
            'terima_barang_by'       => $this->session->userdata('id')
        ];

        $this->db->where('signature', $this->input->post('signature'));
        $this->db->update('management_inventory.pengajuan_retur', $data);
        
        // $this->db->trans_complete();
        // if ($this->db->trans_status() === FALSE)
        // {
        //     echo "ada kegagalan dalam proses terima barang. Mungkin disebabkan internet. rollback diaktifkan ke keadaan sebelumnya";
        //     die;
        // }

        redirect('management_inventory/email_terima_barang/'.$this->input->post('signature').'/'.$this->input->post('supp'));
    }

    public function generate_pdf($signature, $supp, $save_pdf = 0){
        $this->load->library('mypdf');

        $get_pengajuan = $this->model_management_inventory->get_pengajuan($signature);
        foreach ($get_pengajuan->result() as $a) {
            $id_pengajuan               = $a->id;
            $site_code                  = $a->site_code;
            $branch_name                = $a->branch_name;
            $nama_comp                  = $a->nama_comp;
            $supp                       = $a->supp;
            $namasupp                   = $a->namasupp;
            $no_pengajuan               = $a->no_pengajuan;
            $tanggal_pengajuan          = $a->tanggal_pengajuan;
            $nama                       = $a->nama;
            $status                     = $a->status;
            $nama_status                = $a->nama_status;
            $no_pengajuan               = $a->no_pengajuan;
            $created_at                 = $a->created_at;
            $file                       = $a->file;
            $digital_signature          = $a->digital_signature;
            $signature                  = $a->signature;
            $verifikasi_at              = $a->verifikasi_at;
            $verifikasi_username        = $a->verifikasi_username;
            $verifikasi_signature       = $a->verifikasi_signature;
            $principal_area_at          = $a->principal_area_at;
            $principal_area_signature   = $a->principal_area_signature;
            $principal_area_username    = $a->principal_area_username;
            $file_principal_area        = $a->file_principal_area;
            $catatan_principal_area     = $a->catatan_principal_area;
            $principal_ho_at            = $a->principal_ho_at;
            $principal_ho_signature     = $a->principal_ho_signature;
            $principal_ho_username      = $a->principal_ho_username;
            $file_principal_ho          = $a->file_principal_ho;
            $catatan_principal_ho       = $a->catatan_principal_ho;
            $tanggal_kirim_barang       = $a->tanggal_kirim_barang;
            $nama_ekspedisi             = $a->nama_ekspedisi;
            $est_tanggal_tiba           = $a->est_tanggal_tiba;
            $file_pengiriman            = $a->file_pengiriman;
            $username_kirim_barang      = $a->username_kirim_barang;
            $tanggal_terima_barang      = $a->tanggal_terima_barang;
            $nama_penerima              = $a->nama_penerima;
            $no_terima_barang           = $a->no_terima_barang;
            $file_terima_barang         = $a->file_terima_barang;
            $terima_barang_at           = $a->terima_barang_at;
            $username_terima_barang     = $a->username_terima_barang;
            $company                    = $a->company;
            $digital_signature          = $a->digital_signature;
        }

        $get_pengajuan_detail_summary = $this->model_management_inventory->get_pengajuan_detail_summary($id_pengajuan);
        foreach ($get_pengajuan_detail_summary->result() as $a) {
            $count_kodeprod = $a->count_kodeprod;
            $value_rbp = $a->value_rbp;
        }

        $data = [
            'title'                      => 'Generate Pdf',
            'url'                        => 'management_inventory/terima_barang_proses',
            'url_pengajuan'              => 'management_inventory/proses_mpm',
            'get_pengajuan_detail'       => $this->model_management_inventory->get_pengajuan_detail_pdf($id_pengajuan),
            // 'site_code'                  => $this->model_management_inventory->get_sitecode(),
            'site_code'                  => $site_code,
            'branch_name'                => $branch_name,
            'nama_comp'                  => $nama_comp,
            'supp'                       => $supp,
            'namasupp'                   => $namasupp,
            'no_pengajuan'               => $no_pengajuan,
            'tanggal_pengajuan'          => $tanggal_pengajuan,
            'nama'                       => $nama,
            'status'                     => $status,
            'nama_status'                => $nama_status,
            'no_pengajuan'               => $no_pengajuan,
            'created_at'                 => $created_at,
            'file'                       => $file,
            'digital_signature'          => $digital_signature,
            'signature'                  => $signature,
            'verifikasi_at'              => $verifikasi_at,
            'verifikasi_username'        => $verifikasi_username,
            'verifikasi_signature'       => $verifikasi_signature,
            'principal_area_at'          => $principal_area_at,
            'principal_area_signature'   => $principal_area_signature,
            'principal_area_username'    => $principal_area_username,
            'file_principal_area'        => $file_principal_area,
            'catatan_principal_area'     => $catatan_principal_area,
            'principal_ho_at'            => $principal_ho_at,
            'principal_ho_signature'     => $principal_ho_signature,
            'principal_ho_username'      => $principal_ho_username,
            'file_principal_ho'          => $file_principal_ho,
            'catatan_principal_ho'       => $catatan_principal_ho,
            'tanggal_kirim_barang'       => $tanggal_kirim_barang,
            'nama_ekspedisi'             => $nama_ekspedisi,
            'est_tanggal_tiba'           => $est_tanggal_tiba,
            'file_pengiriman'            => $file_pengiriman,
            'username_kirim_barang'      => $username_kirim_barang,
            'tanggal_terima_barang'      => $tanggal_terima_barang,
            'nama_penerima'              => $nama_penerima,
            'no_terima_barang'           => $no_terima_barang,
            'file_terima_barang'         => $file_terima_barang,
            'terima_barang_at'           => $terima_barang_at,
            'username_terima_barang'     => $username_terima_barang,
            'count_kodeprod'             => $count_kodeprod,
            'value_rbp'                  => $value_rbp,
            'company'                    => $company,
            'digital_signature'          => $digital_signature,
        ];

        $filename_pdf = $no_pengajuan;

        if ($supp == '005') {
            $generate_pdf = $this->mypdf->generate('management_inventory/template_pdf_us',$data,$filename_pdf,'A4','landscape');
        }elseif($supp == '001-GT' || $supp == '001-MTI' || $supp == '001-NKA' || $supp == '001-herbana'){
            $generate_pdf = $this->mypdf->generate('management_inventory/template_pdf_retur_principal_deltomed',$data,$filename_pdf,'A4','landscape');
        }elseif($supp == '002' || $supp == '012' || $supp == '013' || $supp == '014' || $supp == '015' || $supp == '004'){
            if ($save_pdf == 1) {
                # code...
                $generate_pdf = $this->mypdf->download('management_inventory/template_pdf_universal',$data,str_replace('/','_',$filename_pdf),'A4','landscape');
            } else {
                # code...
                $generate_pdf = $this->mypdf->generate('management_inventory/template_pdf_universal',$data,$filename_pdf,'A4','landscape');
            }
        }

        
    }

    public function pemusnahan($signature, $supp){

        $get_pengajuan = $this->model_management_inventory->get_pengajuan($signature);
        foreach ($get_pengajuan->result() as $a) {
            $id_pengajuan               = $a->id;
            $site_code                  = $a->site_code;
            $branch_name                = $a->branch_name;
            $nama_comp                  = $a->nama_comp;
            $supp                       = $a->supp;
            $namasupp                   = $a->namasupp;
            $no_pengajuan               = $a->no_pengajuan;
            $tanggal_pengajuan          = $a->tanggal_pengajuan;
            $nama                       = $a->nama;
            $status                     = $a->status;
            $nama_status                = $a->nama_status;
            $no_pengajuan               = $a->no_pengajuan;
            $created_at                 = $a->created_at;
            $file                       = $a->file;
            $digital_signature          = $a->digital_signature;
            $signature                  = $a->signature;
            $verifikasi_at              = $a->verifikasi_at;
            $verifikasi_username        = $a->verifikasi_username;
            $verifikasi_signature       = $a->verifikasi_signature;
            $principal_area_at          = $a->principal_area_at;
            $principal_area_signature   = $a->principal_area_signature;
            $principal_area_username    = $a->principal_area_username;
            $file_principal_area        = $a->file_principal_area;
            $catatan_principal_area     = $a->catatan_principal_area;
            $principal_ho_at            = $a->principal_ho_at;
            $principal_ho_signature     = $a->principal_ho_signature;
            $principal_ho_username      = $a->principal_ho_username;
            $file_principal_ho          = $a->file_principal_ho;
            $catatan_principal_ho       = $a->catatan_principal_ho;
            $tanggal_kirim_barang       = $a->tanggal_kirim_barang;
            $nama_ekspedisi             = $a->nama_ekspedisi;
            $est_tanggal_tiba           = $a->est_tanggal_tiba;
            $file_pengiriman            = $a->file_pengiriman;
            $proses_kirim_barang_at     = $a->proses_kirim_barang_at;
            $username_kirim_barang      = $a->username_kirim_barang;
            $tanggal_terima_barang      = $a->tanggal_terima_barang;
            $nama_penerima              = $a->nama_penerima;
            $no_terima_barang           = $a->no_terima_barang;
            $file_terima_barang         = $a->file_terima_barang;
            $terima_barang_at           = $a->terima_barang_at;
            $username_terima_barang     = $a->username_terima_barang;
            $tanggal_pemusnahan         = $a->tanggal_pemusnahan;
            $nama_pemusnahan            = $a->nama_pemusnahan;
            $file_pemusnahan            = $a->file_pemusnahan;
            $foto_pemusnahan_1          = $a->foto_pemusnahan_1;
            $foto_pemusnahan_2          = $a->foto_pemusnahan_2;
            $pemusnahan_at              = $a->pemusnahan_at;
            $username_pemusnahan        = $a->username_pemusnahan;
            $video        = $a->video;
        }

        $get_pengajuan_detail_summary = $this->model_management_inventory->get_pengajuan_detail_summary($id_pengajuan);
        foreach ($get_pengajuan_detail_summary->result() as $a) {
            $count_kodeprod = $a->count_kodeprod;
            $value_rbp = $a->value_rbp;
        }

        $data = [
            'title'                      => 'Pending Pemusnahan',
            'url'                        => 'management_inventory/pemusnahan_proses',
            'url_pengajuan'              => 'management_inventory/proses_mpm',
            'get_pengajuan_detail'       => $this->model_management_inventory->get_pengajuan_detail($id_pengajuan),'get_pengajuan_detail_accordion'       => $this->model_management_inventory->get_pengajuan_detail_accordion($id_pengajuan),
            // 'site_code'                  => $this->model_management_inventory->get_sitecode(),
            'id_pengajuan'               => $id_pengajuan,
            'site_code'                  => $site_code,
            'branch_name'                => $branch_name,
            'nama_comp'                  => $nama_comp,
            'supp'                       => $supp,
            'namasupp'                   => $namasupp,
            'no_pengajuan'               => $no_pengajuan,
            'tanggal_pengajuan'          => $tanggal_pengajuan,
            'nama'                       => $nama,
            'status'                     => $status,
            'nama_status'                => $nama_status,
            'no_pengajuan'               => $no_pengajuan,
            'created_at'                 => $created_at,
            'file'                       => $file,
            'digital_signature'          => $digital_signature,
            'signature'                  => $signature,
            'verifikasi_at'              => $verifikasi_at,
            'verifikasi_username'        => $verifikasi_username,
            'verifikasi_signature'       => $verifikasi_signature,
            'principal_area_at'          => $principal_area_at,
            'principal_area_signature'   => $principal_area_signature,
            'principal_area_username'    => $principal_area_username,
            'file_principal_area'        => $file_principal_area,
            'catatan_principal_area'     => $catatan_principal_area,
            'principal_ho_at'            => $principal_ho_at,
            'principal_ho_signature'     => $principal_ho_signature,
            'principal_ho_username'      => $principal_ho_username,
            'file_principal_ho'          => $file_principal_ho,
            'catatan_principal_ho'       => $catatan_principal_ho,
            'tanggal_kirim_barang'       => $tanggal_kirim_barang,
            'nama_ekspedisi'             => $nama_ekspedisi,
            'est_tanggal_tiba'           => $est_tanggal_tiba,
            'file_pengiriman'            => $file_pengiriman,
            'proses_kirim_barang_at'     => $proses_kirim_barang_at,
            'username_kirim_barang'      => $username_kirim_barang,
            'tanggal_terima_barang'      => $tanggal_terima_barang,
            'nama_penerima'              => $nama_penerima,
            'no_terima_barang'           => $no_terima_barang,
            'file_terima_barang'         => $file_terima_barang,
            'terima_barang_at'           => $terima_barang_at,
            'username_terima_barang'     => $username_terima_barang,
            'count_kodeprod'             => $count_kodeprod,
            'value_rbp'                  => $value_rbp,
            'tanggal_pemusnahan'         => $tanggal_pemusnahan,
            'nama_pemusnahan'            => $nama_pemusnahan,
            'file_pemusnahan'            => $file_pemusnahan,
            'foto_pemusnahan_1'          => $foto_pemusnahan_1,
            'foto_pemusnahan_2'          => $foto_pemusnahan_2,
            'pemusnahan_at'              => $pemusnahan_at,
            'username_pemusnahan'        => $username_pemusnahan,
            'video'        => $video,


        ];

        // $this->load->view('management_office/top_header', $data);
        $this->navbar($data);
        $this->load->view('kalimantan/header_full_width', $data);
        $this->load->view('kalimantan/accordion', $data);
        $this->load->view('management_inventory/pemusnahan', $data);
        $this->load->view('kalimantan/footer');
    }

    public function pemusnahan_proses(){

        $file = $this->input->post('file'); 

        if (!is_dir('./assets/file/retur/')) {
            @mkdir('./assets/file/retur/', 0777);
        }

        $config['upload_path'] = './assets/file/retur/';
        $config['allowed_types'] = '*';
        $config['max_size'] = '*';

        $this->load->library('upload', $config);
        if (!$this->upload->do_upload('file_pemusnahan')) {
            $filename_berita_acara = '';

            var_dump($this->upload->display_errors());

        } else {

            $data = array('upload_data' => $this->upload->data());
            $file = $data['upload_data']['full_path'];
            chmod($file, 0777);
            $upload_data = $this->upload->data();
            $filename_berita_acara = $upload_data['file_name'];
        }

        // echo "filename_berita_acara : ".$filename_berita_acara;
        // die;

        if (!$this->upload->do_upload('foto_pemusnahan_1')) {
            $filename_foto_1 = '';
        } else {

            $data = array('upload_data' => $this->upload->data());
            $file = $data['upload_data']['full_path'];
            chmod($file, 0777);
            $upload_data = $this->upload->data();
            $filename_foto_1 = $upload_data['file_name'];
        }

        if (!$this->upload->do_upload('foto_pemusnahan_2')) {
            $filename_foto_2 = '';
        } else {

            $data = array('upload_data' => $this->upload->data());
            $file = $data['upload_data']['full_path'];
            chmod($file, 0777);
            $upload_data = $this->upload->data();
            $filename_foto_2 = $upload_data['file_name'];
        }

        if (!$this->upload->do_upload('video')) {
            $filename_video = '';
        } else {

            $data = array('upload_data' => $this->upload->data());
            $file = $data['upload_data']['full_path'];
            chmod($file, 0777);
            $upload_data = $this->upload->data();
            $filename_video = $upload_data['file_name'];
        }

        $created_at = $this->model_outlet_transaksi->timezone();

        // echo "tanggal_pemusnahan : ".$this->input->post('tanggal_pemusnahan')."<br>";
        // echo "filename_berita_acara : ".$filename_berita_acara."<br>";
        // echo "filename_foto_1 : ".$filename_foto_1."<br>";
        // echo "filename_foto_2 : ".$filename_foto_2."<br>";
        // echo "filename_video : ".$filename_video."<br>";
        // die;

        // $this->db->trans_start();

        $data = [
            'tanggal_pemusnahan'            => $this->input->post('tanggal_pemusnahan'),
            'nama_pemusnahan'               => $this->input->post('nama_pemusnahan'),
            'file_pemusnahan'               => $filename_berita_acara,
            'foto_pemusnahan_1'             => $filename_foto_1,
            'foto_pemusnahan_2'             => $filename_foto_2,
            'video'                         => $filename_video,
            'status'                        => 9,
            'nama_status'                   => "PEMUSNAHAN OLEH DP",
            // 'proses_kirim_barang_at'        => $created_at,
            'pemusnahan_at'                 => $created_at,
            // 'proses_kirim_barang_by'        => $this->session->userdata('id')
            'pemusnahan_by'                 => $this->session->userdata('id')
        ];

        $this->db->where('signature', $this->input->post('signature'));
        $this->db->update('management_inventory.pengajuan_retur', $data);
        
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE)
        {
            echo "ada kegagalan dalam proses pemusnahan barang. Mungkin disebabkan internet. rollback diaktifkan ke keadaan sebelumnya";
            die;
        }

        redirect('management_inventory/email_pemusnahan/'.$this->input->post('signature').'/'.$this->input->post('supp'));
    }

    public function email_pemusnahan($signature){
        $get_pengajuan = $this->model_management_inventory->get_pengajuan($signature);
        foreach ($get_pengajuan->result() as $a) {
            $id_pengajuan           = $a->id;
            $site_code              = $a->site_code;
            $branch_name            = $a->branch_name;
            $nama_comp              = $a->nama_comp;
            $supp                   = $a->supp;
            $namasupp               = $a->namasupp;
            $no_pengajuan           = $a->no_pengajuan;
            $tanggal_pengajuan      = $a->tanggal_pengajuan;
            $nama                   = $a->nama;
            $status                 = $a->status;
            $nama_status            = $a->nama_status;
            $no_pengajuan           = $a->no_pengajuan;
            $created_by             = $a->created_by;
            $file                   = $a->file;
            $signature              = $a->signature;
            $verifikasi_at          = $a->verifikasi_at;
            $verifikasi_username    = $a->verifikasi_username;
            $signature              = $a->signature;
            $principal_area_at      = $a->principal_area_at;
            $principal_area_signature   = $a->principal_area_signature;
            $principal_area_username    = $a->principal_area_username;
            $file_principal_area        = $a->file_principal_area;
            $catatan_principal_area     = $a->catatan_principal_area;
            $principal_ho_at        = $a->principal_ho_at;
            $principal_ho_signature = $a->principal_ho_signature;
            $principal_ho_username  = $a->principal_ho_username;
            $file_principal_ho      = $a->file_principal_ho;
            $catatan_principal_ho   = $a->catatan_principal_ho;
            $tanggal_kirim_barang   = $a->tanggal_kirim_barang;
            $nama_ekspedisi         = $a->nama_ekspedisi;
            $est_tanggal_tiba       = $a->est_tanggal_tiba;
            $file_pengiriman        = $a->file_pengiriman;
            $proses_kirim_barang_at = $a->proses_kirim_barang_at;
            $username_kirim_barang  = $a->username_kirim_barang;
            $tanggal_terima_barang  = $a->tanggal_terima_barang;
            $nama_penerima          = $a->nama_penerima;
            $no_terima_barang       = $a->no_terima_barang;
            $file_terima_barang     = $a->file_terima_barang;
            $terima_barang_at       = $a->terima_barang_at;
            $username_terima_barang = $a->username_terima_barang;
            $tanggal_pemusnahan     = $a->tanggal_pemusnahan;
            $nama_pemusnahan        = $a->nama_pemusnahan;
            $file_pemusnahan        = $a->file_pemusnahan;
            $foto_pemusnahan_1      = $a->foto_pemusnahan_1;
            $foto_pemusnahan_2      = $a->foto_pemusnahan_2;
        }

        // echo "principal_area_at : ".$principal_area_at;
        // die;

        $get_pengajuan_detail_summary = $this->model_management_inventory->get_pengajuan_detail_summary($id_pengajuan);
        foreach ($get_pengajuan_detail_summary->result() as $a) {
            $count_kodeprod = $a->count_kodeprod;
            $value_rbp = $a->value_rbp;
        }

        // echo "supp : ".$supp;
        if ($supp == '002' || $supp == '012' || $supp == '013' || $supp == '014' || $supp == '015') {
            $get_email_retur = $this->model_retur->get_email_principal($supp);
            foreach ($get_email_retur as $key) {
                $get_email_to = $key->email;
            } 
            // $attach = $this->email->attach('assets/file/retur/'.str_replace('/','_',$no_pengajuan).'.pdf');
        }

        $data = [
            'get_pengajuan_detail'      => $this->model_management_inventory->get_pengajuan_detail($id_pengajuan),
            'no_pengajuan'              => $no_pengajuan,
            'branch_name'               => $branch_name,
            'nama_comp'                 => $nama_comp,
            'site_code'                 => $site_code,
            'namasupp'                  => $namasupp,
            'tanggal_pengajuan'         => $tanggal_pengajuan,
            'nama'                      => $nama,
            'status'                    => $status,
            'nama_status'               => $nama_status,
            'created_by'                => $created_by,
            'file'                      => $file,
            'id_pengajuan'              => $id_pengajuan,
            'supp'                      => $supp,
            'signature'                 => $signature,
            'verifikasi_at'             => $verifikasi_at,
            'verifikasi_username'       => $verifikasi_username,
            'count_kodeprod'            => $count_kodeprod,
            'value_rbp'                 => $value_rbp,
            'principal_area_at'         => $principal_area_at,
            'principal_area_signature'  => $principal_area_signature,
            'principal_area_username'   => $principal_area_username,
            'file_principal_area'       => $file_principal_area,
            'catatan_principal_area'    => $catatan_principal_area,
            'principal_ho_at'           => $principal_ho_at,
            'principal_ho_signature'    => $principal_ho_signature,
            'principal_ho_username'     => $principal_ho_username,
            'file_principal_ho'         => $file_principal_ho,
            'catatan_principal_ho'      => $catatan_principal_ho,
            'tanggal_kirim_barang'      => $tanggal_kirim_barang,
            'nama_ekspedisi'            => $nama_ekspedisi,
            'est_tanggal_tiba'          => $est_tanggal_tiba,
            'file_pengiriman'           => $file_pengiriman,
            'proses_kirim_barang_at'    => $proses_kirim_barang_at,
            'username_kirim_barang'     => $username_kirim_barang,
            'tanggal_terima_barang'     => $tanggal_terima_barang,
            'nama_penerima'             => $nama_penerima,
            'no_terima_barang'          => $no_terima_barang,
            'file_terima_barang'        => $file_terima_barang,
            'terima_barang_at'          => $terima_barang_at,
            'username_terima_barang'    => $username_terima_barang,
            'tanggal_pemusnahan'        => $tanggal_pemusnahan,
            'nama_pemusnahan'           => $nama_pemusnahan,
            'file_pemusnahan'           => $file_pemusnahan,
            'foto_pemusnahan_1'         => $foto_pemusnahan_1,
            'foto_pemusnahan_2'         => $foto_pemusnahan_2,
        ];

        $from = "suffy@muliaputramandiri.com";
        $to =  $get_email_to;
        $cc = 'suffy.mpm@gmail.com';

        $email_cc = $this->model_management_inventory->get_email($site_code)->row()->email.' ,linda@muliaputramandiri.com, fakhrul@muliaputramandiri.com';

        $subject = "MPM SITE | RETUR : $no_pengajuan | ".$nama_status;
        $this->load->model('model_relokasi');
        $this->model_relokasi->email();

        $message = $this->load->view("management_inventory/email_pemusnahan",$data,TRUE);

        $this->email->from($from,'PT. Mulia Putra Mandiri');
        $this->email->to($to);
        $this->email->cc($email_cc);
        $this->email->subject($subject);
        $this->email->message($message);
        $send = $this->email->send();
        // echo $this->email->print_debugger();
        if ($send) {
            // echo "<script>alert('pengiriman email berhasil'); </script>";
            $this->session->set_flashdata("pesan_success", "pengiriman email berhasil");
        }else{
            echo "<script>alert('pengiriman email gagal'); </script>";
            die;
        }

        redirect('management_inventory/pemusnahan/'.$signature.'/'.$supp);

    }

    public function routing($signature){

        $status = $this->model_management_inventory->cek_status($signature)->row()->status;
        $supp = $this->model_management_inventory->cek_status($signature)->row()->supp;
        $no_pengajuan = $this->model_management_inventory->cek_status($signature)->row()->no_pengajuan;

        // echo "status : ".$status;
        // echo "supp : ".$supp;
        // echo "no_pengajuan : ".$no_pengajuan;
        // die;

        if ($status == 1) {

            if ($this->session->userdata('level') == 3) {
                $this->session->set_flashdata("pesan", "Ajuan Retur ini belum diselesaikan oleh DP");
                redirect('management_inventory');
                die;
            }
            redirect('management_inventory/pengajuan_retur_detail/'.$signature.'/'.$supp);
        }elseif ($status == 2) { // proses mpm 
            if ($this->session->userdata('username') == 'linda' || $this->session->userdata('username') == 'suffy'|| $this->session->userdata('username') == 'melinda') {
                redirect('management_inventory/verifikasi_retur/'.$signature.'/'.$supp);
            }
            redirect('management_inventory/pengajuan_retur_detail/'.$signature.'/'.$supp);
        }elseif ($status == 3) { // principal area

            if ($this->session->userdata('level') == 4) { // jika yg login adalah DP
                redirect('management_inventory/pengajuan_retur_detail/'.$signature.'/'.$supp);
            }else{
                redirect('management_inventory/principal_area/'.$signature.'/'.$supp);
            }

        }elseif ($status == 4) { // proses principal ho

            if ($this->session->userdata('level') == 4) { // jika yg login adalah DP
                redirect('management_inventory/pengajuan_retur_detail/'.$signature.'/'.$supp);
            }else{
                redirect('management_inventory/principal_ho/'.$signature.'/'.$supp);
            }

        }elseif ($status == 5) {
            redirect('management_inventory/kirim_barang/'.$signature.'/'.$supp);
        }elseif ($status == 6) {

            if ($this->session->userdata('level') == 4) {
                redirect('management_inventory/pengajuan_retur_detail/'.$signature.'/'.$supp);
            }else{
                redirect('management_inventory/terima_barang/'.$signature.'/'.$supp);
            }
            
        }elseif ($status == 7) { // pending pemusnahan
            if ($this->session->userdata('level') == 4) {
                redirect('management_inventory/pemusnahan/'.$signature.'/'.$supp);
            }else{
                redirect('management_inventory/pengajuan_retur_detail/'.$signature.'/'.$supp);
            }
        }elseif ($status == 8) {
            if ($this->session->userdata('level') == 4) {
                redirect('management_inventory/pengajuan_retur_detail/'.$signature.'/'.$supp);
            }else{
                redirect('management_inventory/terima_barang/'.$signature.'/'.$supp);
            }
        }elseif ($status == 9) {
            if ($this->session->userdata('level') == 4) {
                redirect('management_inventory/pemusnahan/'.$signature.'/'.$supp);
            }else{
                redirect('management_inventory/pengajuan_retur_detail/'.$signature.'/'.$supp);
            }
            
        }elseif ($status == 10) {
            redirect('management_inventory/pengajuan_retur_detail/'.$signature.'/'.$supp);
        }elseif ($status == 11) { // proses principal ho

            if ($this->session->userdata('level') == 4) { // jika yg login adalah DP
                redirect('management_inventory/pengajuan_retur_detail/'.$signature.'/'.$supp);
            }else{
                redirect('management_inventory/principal_ho/'.$signature.'/'.$supp);
            }

        }
    }

    public function signature_digital(){

        $data = [
            'title'           => 'Digital Signature',
            'get_pengajuan'   => $this->model_management_inventory->get_pengajuan(),
            'url'             => 'management_inventory/signature_digital_proses',
            'site_code'       => $this->model_management_inventory->get_sitecode(),
        ];

        // $this->load->view('management_office/top_header', $data);
        $this->navbar($data);
        $this->load->view('template_claim/top_header_signature');
        $this->load->view('kalimantan/header_full_width', $data);
        $this->load->view('management_inventory/signature_digital', $data);
        $this->load->view('kalimantan/footer');
    }
    

    public function signature_digital_proses(){
        
        $folderPath = './assets/uploads/signature/';  
        $image_parts = explode(";base64,", $_POST['signed']);
        $image_type_aux = explode("image/", $image_parts[0]);
        $image_type = $image_type_aux[1];
        $image_base64 = base64_decode($image_parts[1]);

        $file = $folderPath . $this->session->userdata('username') . '-signature.' .$image_type;
        file_put_contents($file, $image_base64);
        redirect('management_inventory/pengajuan_retur', 'refresh');

    }

    public function export_report($status){

        $query="
            select 	a.status, a.nama_status, a.no_pengajuan, a.site_code, d.branch_name, d.nama_comp, a.nama, a.supp, a.tanggal_pengajuan, 
                    b.kodeprod, c.namaprod, b.jumlah
            from management_inventory.pengajuan_retur a LEFT JOIN 
            (
                select a.id_pengajuan, a.kodeprod, a.namaprod, a.jumlah
                from management_inventory.pengajuan_retur_detail a 
            )b on a.id = id_pengajuan LEFT JOIN 
            (
                select a.kodeprod, a.namaprod
                from mpm.tabprod a
            )c on b.kodeprod = c.kodeprod LEFT JOIN 
            (
                select concat(a.kode_comp, a.nocab) as site_code, a.branch_name, a.nama_comp
                from mpm.tbl_tabcomp a 
                where a.status = 1
                GROUP BY concat(a.kode_comp, a.nocab)
            )d on a.site_code = d.site_code
            where a.status = $status
        ";                        
        $hsl = $this->db->query($query);

        query_to_csv($hsl,TRUE,'Export.csv');

    }

    public function export_report_site($site_code){

        $query="
            select 	a.status, a.nama_status, a.no_pengajuan, a.site_code, d.branch_name, d.nama_comp, a.nama, a.supp, a.tanggal_pengajuan, 
                    b.kodeprod, c.namaprod, b.jumlah
            from management_inventory.pengajuan_retur a LEFT JOIN 
            (
                select a.id_pengajuan, a.kodeprod, a.namaprod, a.jumlah
                from management_inventory.pengajuan_retur_detail a 
            )b on a.id = id_pengajuan LEFT JOIN 
            (
                select a.kodeprod, a.namaprod
                from mpm.tabprod a
            )c on b.kodeprod = c.kodeprod LEFT JOIN 
            (
                select concat(a.kode_comp, a.nocab) as site_code, a.branch_name, a.nama_comp
                from mpm.tbl_tabcomp a 
                where a.status = 1
                GROUP BY concat(a.kode_comp, a.nocab)
            )d on a.site_code = d.site_code
            where a.site_code = '$site_code'
        ";                        
        $hsl = $this->db->query($query);

        query_to_csv($hsl,TRUE,'Export.csv');

    }

    public function export_by_signature($signature){
        $no_pengajuan = $this->model_management_inventory->get_pengajuan_by_signature($signature)->row()->no_pengajuan;
        if ($no_pengajuan) {
            $params_no_pengajuan = $no_pengajuan ;
        }else{
            $params_no_pengajuan = "draft";
        }

        $query = "
        select 	a.nama_status, a.no_pengajuan, a.site_code, d.branch_name, d.nama_comp, a.nama, 
                e.namasupp, a.tanggal_pengajuan, 
                b.kodeprod, c.namaprod, 
                b.batch_number, b.expired_date, b.nama_outlet, b.alasan, b.jumlah, 
                b.qty_approval, b.satuan, 
                a.principal_area_at, f.username as principal_area_name, f.email as principal_area_email,
                a.verifikasi_at, g.username as mpm_name, g.email as mpm_email, 
                a.principal_ho_at, h.username as principal_ho_name, h.email as principal_ho_email,
                a.tanggal_kirim_barang, a.nama_ekspedisi, a.est_tanggal_tiba, a.proses_kirim_barang_at,
                a.tanggal_terima_barang, a.nama_penerima, a.no_terima_barang, a.terima_barang_at,
                a.tanggal_pemusnahan, a.pemusnahan_at
        from management_inventory.pengajuan_retur a INNER JOIN 
        (
            select a.id_pengajuan, a.kodeprod, a.namaprod, a.jumlah, a.satuan, a.qty_approval, a.batch_number, a.expired_date, a.nama_outlet, a.alasan
            from management_inventory.pengajuan_retur_detail a 
            where a.deleted is null
        )b on a.id = id_pengajuan LEFT JOIN 
        (
            select a.kodeprod, a.namaprod
            from mpm.tabprod a
        )c on b.kodeprod = c.kodeprod LEFT JOIN 
        (
            select concat(a.kode_comp, a.nocab) as site_code, a.branch_name, a.nama_comp
            from mpm.tbl_tabcomp a 
            where a.status = 1
            GROUP BY concat(a.kode_comp, a.nocab)
        )d on a.site_code = d.site_code LEFT JOIN (
            select a.supp, a.namasupp
            from mpm.tabsupp a 
        )e on a.supp = e.supp LEFT JOIN 
        (
            select a.id, a.username, a.email
            from mpm.user a 
        )f on a.principal_area_by = f.id LEFT JOIN 
        (
            select a.id, a.username, a.email
            from mpm.user a 
        )g on a.verifikasi_by = g.id LEFT JOIN 
        (
            select a.id, a.username, a.email
            from mpm.user a 
        )h on a.principal_ho_by = h.id
        where a.signature = '$signature'
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        // die;

        $hsl = $this->db->query($query);

        query_to_csv($hsl,TRUE,"$params_no_pengajuan.csv");
    }

    public function export_sortir_by_signature($signature){

        $no_pengajuan = $this->model_management_inventory->get_pengajuan_by_signature($signature)->row()->no_pengajuan;
        // echo $no_pengajuan;
        // die;

        $query = "
        select 	a.nama_status, a.no_pengajuan, a.site_code, d.branch_name, d.nama_comp, a.nama, 
                e.namasupp, a.tanggal_pengajuan, 
                b.kodeprod, c.namaprod, b.qty_approval, b.satuan, 
                a.principal_area_at, f.username as principal_area_name, f.email as principal_area_email,
                a.verifikasi_at, g.username as mpm_name, g.email as mpm_email, 
                a.principal_ho_at, h.username as principal_ho_name, h.email as principal_ho_email,
                a.tanggal_kirim_barang, a.nama_ekspedisi, a.est_tanggal_tiba, a.proses_kirim_barang_at,
                a.tanggal_terima_barang, a.nama_penerima, a.no_terima_barang, a.terima_barang_at,
                a.tanggal_pemusnahan, a.pemusnahan_at
        from management_inventory.pengajuan_retur a INNER JOIN 
        (
            select a.id_pengajuan, a.kodeprod, a.namaprod, a.jumlah, a.satuan, a.qty_approval
            from management_inventory.pengajuan_retur_detail a 
            where a.deleted is null and a.qty_approval > 0 and a.status = 3
        )b on a.id = id_pengajuan LEFT JOIN 
        (
            select a.kodeprod, a.namaprod
            from mpm.tabprod a
        )c on b.kodeprod = c.kodeprod LEFT JOIN 
        (
            select concat(a.kode_comp, a.nocab) as site_code, a.branch_name, a.nama_comp
            from mpm.tbl_tabcomp a 
            where a.status = 1
            GROUP BY concat(a.kode_comp, a.nocab)
        )d on a.site_code = d.site_code LEFT JOIN (
            select a.supp, a.namasupp
            from mpm.tabsupp a 
        )e on a.supp = e.supp LEFT JOIN 
        (
            select a.id, a.username, a.email
            from mpm.user a 
        )f on a.principal_area_by = f.id LEFT JOIN 
        (
            select a.id, a.username, a.email
            from mpm.user a 
        )g on a.verifikasi_by = g.id LEFT JOIN 
        (
            select a.id, a.username, a.email
            from mpm.user a 
        )h on a.principal_ho_by = h.id
        where a.signature = '$signature'
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        $hsl = $this->db->query($query);
        query_to_csv($hsl,TRUE,"$no_pengajuan.csv");
    }

    public function export_template_pengajuan_retur(){

        // $query = "
        //     select '' as kodeprod, '' as batch_number, '' as expired_date, '' as jumlah, '' as nama_outlet, '' as alasan_retur, '' as keterangan
        // ";
        // $hasil = $this->db->query($query);   
    
        // $this->excel_generator->set_query($hasil);
        // $this->excel_generator->set_header(array
        // (
        //     'kodeprod', 'batch_number', 'expired_date', 'jumlah', 'nama_outlet', 'alasan_retur', 'keterangan'
        // ));
        // $this->excel_generator->set_column(array
        // ( 
        //     'kodeprod', 'batch_number', 'expired_date', 'jumlah', 'nama_outlet', 'alasan_retur', 'keterangan'
        // ));
        // $this->excel_generator->set_width(array(10,10,10)); 
        // $this->excel_generator->exportTo2007('Template Pengajuan Retur 2023'); 
        $query = "
            select '' as kodeprod, '' as batch_number, '' as expired_date, '' as jumlah, '' as nama_outlet, '' as alasan_retur, '' as keterangan
        ";
        $hasil = $this->db->query($query);   
    
        $this->excel_generator->set_query($hasil);
        $this->excel_generator->set_header(array
        (
            'kodeprod', 'batch_number', 'expired_date(m/d/y)', 'jumlah', 'nama_outlet', 'alasan_retur', 'keterangan'
        ));
        $this->excel_generator->set_column(array
        ( 
            'kodeprod', 'batch_number', 'expired_date', 'jumlah', 'nama_outlet', 'alasan_retur', 'keterangan'
        ));
        $this->excel_generator->set_width(array(10,10,10,10,10,10,10)); 
        $this->excel_generator->exportTo2007('Template Pengajuan Retur 2023'); 
    }

    public function pengajuan_retur_detail_import(){

        $this->load->library('session');

        $signature_ajuan = $this->input->post('signature');
        $supp = $this->input->post('supp');

        $get_pengajuan = $this->model_management_inventory->get_pengajuan($signature_ajuan);
        foreach ($get_pengajuan->result() as $a) {
            $id_pengajuan = $a->id;
            $supp = $a->supp;
            // $signature = $a->signature;
            $created_at = $a->created_at;
        }

        if (!is_dir('./assets/uploads/retur/import/')) {
            @mkdir('./assets/uploads/retur/import/', 0777);
        }

        $this->load->library('upload'); // Load librari upload        
        $config['upload_path'] = './assets/uploads/retur/import/';
        $config['allowed_types'] = 'xls|xlsx';
        // $config['allowed_types'] = '*';    
        $config['max_size']  = '2048';
        $config['overwrite'] = true;
        $config['encrypt_name'] = TRUE;
        $this->upload->initialize($config);

        if ($this->upload->do_upload('file')) 
        {
            $upload_data = $this->upload->data();
            $filename = $upload_data['orig_name'];

            $this->load->library('excel');
            $object = PHPExcel_IOFactory::load("assets/uploads/retur/import/$filename");

            $jumlahSheet = $object->getSheetCount();
            if ($jumlahSheet > 1) {
                echo "jumlah_sheet : ".$jumlahSheet;
                echo "<script>alert('upload file gagal karena file mempunyai lebih dari 1 sheet'); </script>";
                redirect('mes','refresh');
            }

            $created_at = $this->model_outlet_transaksi->timezone();

            foreach ($object->getWorksheetIterator() as $worksheet) {

                $highestRow = $worksheet->getHighestRow();
                $highestColumn = $worksheet->getHighestColumn();

                for ($row = 2; $row <= $highestRow; $row++) {          
                
                    $kodeprod = trim($worksheet->getCellByColumnAndRow(0, $row)->getValue());
                    $batch_number = trim($worksheet->getCellByColumnAndRow(1, $row)->getValue());
                    $expired_date = trim($worksheet->getCellByColumnAndRow(2, $row)->getValue());

                    $unix_date = ($expired_date - 25569) * 86400;
                    $excel_date = 25569 + ($unix_date / 86400);
                    $unix_date = ($excel_date - 25569) * 86400;
                    $expired_date_final = gmdate("Y-m-d", $unix_date);

                    $jumlah = trim($worksheet->getCellByColumnAndRow(3, $row)->getValue());
                    $nama_outlet = trim($worksheet->getCellByColumnAndRow(4, $row)->getValue());
                    $alasan_retur = trim($worksheet->getCellByColumnAndRow(5, $row)->getValue());
                    $keterangan = trim($worksheet->getCellByColumnAndRow(6, $row)->getValue());

                    // echo "kodeprod : ".$kodeprod;
                    // die;

                    if(strlen("$kodeprod") == '5')
                    {
                        $kodeprodx = '0'.$kodeprod;
                    }else{
                        $kodeprodx = $kodeprod;
                    } 

                    $satuan = trim($this->model_management_inventory->get_product($kodeprodx)->row()->kecil);

                    $signature_detail = 'RTR-' . rand() . md5($this->model_outlet_transaksi->timezone()) . date('Ymd');

                    $data = [
                        'id_pengajuan'      => $id_pengajuan,
                        'kodeprod'          => $kodeprodx,
                        'batch_number'      => $batch_number,
                        'expired_date'      => $expired_date_final,
                        'jumlah'            => $jumlah,
                        'alasan'            => $alasan_retur,
                        'nama_outlet'       => $nama_outlet,
                        'keterangan'        => $keterangan,
                        'satuan'            => $satuan,
                        // 'supp'              => $supp,
                        // 'filename'          => $filename,
                        'created_at'        => $created_at,
                        'created_by'        => $this->session->userdata('id'),
                        'signature'         => $signature_detail
                    ];
                    $this->db->insert('management_inventory.pengajuan_retur_detail',$data);
                }
            }

            $this->session->set_flashdata("pesan_success", "Import Success");

        }else{
            $this->session->set_flashdata("pesan", "Import Gagal :".$this->upload->display_errors());
        };

        redirect('management_inventory/pengajuan_retur_detail/'.$signature_ajuan.'/'.$supp);
    }

    public function preview_pengajuan_retur(){

        $get_pengajuan = $this->model_management_inventory->get_pengajuan($this->input->post('signature'));
        foreach ($get_pengajuan->result() as $a) {
            $id_pengajuan               = $a->id;
            $site_code                  = $a->site_code;
            $branch_name                = $a->branch_name;
            $nama_comp                  = $a->nama_comp;
            $supp                       = $a->supp;
            $namasupp                   = $a->namasupp;
            $no_pengajuan               = $a->no_pengajuan;
            $tanggal_pengajuan          = $a->tanggal_pengajuan;
            $nama                       = $a->nama;
            $status                     = $a->status;
            $nama_status                = $a->nama_status;
            $no_pengajuan               = $a->no_pengajuan;
            $created_at                 = $a->created_at;
            $file                       = $a->file;
            $digital_signature          = $a->digital_signature;
            $signature                  = $a->signature;
            $verifikasi_at              = $a->verifikasi_at;
            $verifikasi_username        = $a->verifikasi_username;
            $verifikasi_signature       = $a->verifikasi_signature;
            $principal_area_at          = $a->principal_area_at;
            $principal_area_signature   = $a->principal_area_signature;
            $principal_area_username    = $a->principal_area_username;
            $file_principal_area        = $a->file_principal_area;
            $catatan_principal_area     = $a->catatan_principal_area;
            $principal_ho_at            = $a->principal_ho_at;
            $principal_ho_signature     = $a->principal_ho_signature;
            $principal_ho_username      = $a->principal_ho_username;
            $file_principal_ho          = $a->file_principal_ho;
            $catatan_principal_ho       = $a->catatan_principal_ho;
            $tanggal_kirim_barang       = $a->tanggal_kirim_barang;
            $nama_ekspedisi             = $a->nama_ekspedisi;
            $est_tanggal_tiba           = $a->est_tanggal_tiba;
            $file_pengiriman            = $a->file_pengiriman;
            $username_kirim_barang      = $a->username_kirim_barang;
            $tanggal_terima_barang      = $a->tanggal_terima_barang;
            $nama_penerima              = $a->nama_penerima;
            $no_terima_barang           = $a->no_terima_barang;
            $file_terima_barang         = $a->file_terima_barang;
            $terima_barang_at           = $a->terima_barang_at;
            $username_terima_barang     = $a->username_terima_barang;
        }

        $get_pengajuan_detail_summary = $this->model_management_inventory->get_pengajuan_detail_summary($id_pengajuan);
        foreach ($get_pengajuan_detail_summary->result() as $a) {
            $count_kodeprod = $a->count_kodeprod;
            $value_rbp = $a->value_rbp;
        }

        $data = [
            'title'                      => 'Pengajuan Retur - Preview',
            'url'                        => 'management_inventory/pengajuan_retur_detail_proses',
            'url_import'                 => 'management_inventory/pengajuan_retur_detail_import',
            // 'url_pengajuan'              => 'management_inventory/proses_mpm',
            'url_pengajuan'              => 'management_inventory/preview_pengajuan_retur',
            'get_pengajuan_detail'       => $this->model_management_inventory->get_pengajuan_detail($id_pengajuan),
            'id_pengajuan'               => $id_pengajuan,
            'site_code'                  => $site_code,
            'branch_name'                => $branch_name,
            'nama_comp'                  => $nama_comp,
            'supp'                       => $supp,
            'namasupp'                   => $namasupp,
            'no_pengajuan'               => $no_pengajuan,
            'tanggal_pengajuan'          => $tanggal_pengajuan,
            'nama'                       => $nama,
            'status'                     => $status,
            'nama_status'                => $nama_status,
            'no_pengajuan'               => $no_pengajuan,
            'created_at'                 => $created_at,
            'file'                       => $file,
            'digital_signature'          => $digital_signature,
            'signature'                  => $signature,
            'verifikasi_at'              => $verifikasi_at,
            'verifikasi_username'        => $verifikasi_username,
            'verifikasi_signature'       => $verifikasi_signature,
            'principal_area_at'          => $principal_area_at,
            'principal_area_signature'   => $principal_area_signature,
            'principal_area_username'    => $principal_area_username,
            'file_principal_area'        => $file_principal_area,
            'catatan_principal_area'     => $catatan_principal_area,
            'principal_ho_at'            => $principal_ho_at,
            'principal_ho_signature'     => $principal_ho_signature,
            'principal_ho_username'      => $principal_ho_username,
            'file_principal_ho'          => $file_principal_ho,
            'catatan_principal_ho'       => $catatan_principal_ho,
            'tanggal_kirim_barang'       => $tanggal_kirim_barang,
            'nama_ekspedisi'             => $nama_ekspedisi,
            'est_tanggal_tiba'           => $est_tanggal_tiba,
            'file_pengiriman'            => $file_pengiriman,
            'username_kirim_barang'      => $username_kirim_barang,
            'tanggal_terima_barang'      => $tanggal_terima_barang,
            'nama_penerima'              => $nama_penerima,
            'no_terima_barang'           => $no_terima_barang,
            'file_terima_barang'         => $file_terima_barang,
            'terima_barang_at'           => $terima_barang_at,
            'username_terima_barang'     => $username_terima_barang,
            'count_kodeprod'             => $count_kodeprod,
            'value_rbp'                  => $value_rbp,
        ];

        // $this->load->view('management_office/top_header', $data);
        $this->navbar($data);
        // $this->load->view('kalimantan/header_full_width', $data);
        // $this->load->view('kalimantan/accordion_preview', $data);
        $this->load->view('management_inventory/preview_pengajuan_retur', $data);
        $this->load->view('kalimantan/footer');


    }

    public function proses_bridging_mpm(){

        $signature = $this->input->post('signature');
        $supp = $this->input->post('supp');
        $created_at = $this->model_outlet_transaksi->timezone();

        // echo "signature : ".$signature;
        // echo "supp : ".$supp;

        $get_pengajuan = $this->model_management_inventory->get_pengajuan($signature);
        foreach ($get_pengajuan->result() as $a) {
            $id_pengajuan               = $a->id; 
        }

        // cek apakah principal area sudah ada signature
        $cek_principal_area_signature = './assets/uploads/signature/'.$this->session->userdata('username').'-signature.png';
        if (!file_exists($cek_principal_area_signature)) {
            $this->session->set_flashdata("pesan", "Proses Gagal !! <br><br>Signature anda tidak ditemukan. Registrasikan dahulu signature anda di menu profile -> signature");
            redirect('management_inventory/principal_area/'.$signature.'/'.$supp);
            die;
        }


        // cek apakah ada qty approval yang null
        $query = "
            select * 
            from management_inventory.pengajuan_retur_detail a 
            where a.id_pengajuan = $id_pengajuan and a.qty_approval is null and a.deleted is null
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        $cek = $this->db->query($query);

        // echo $cek->num_rows();

        // die;

        if ($cek->num_rows() > 0) {       
            
            // echo "ada" ;
            
            $this->session->set_flashdata("pesan", "Proses Gagal !! <br><br>QTY approval masih ditemukan qty approval NULL. yaitu : ".$cek->row()->kodeprod. " , batch : ".$cek->row()->batch_number. " , outlet : ".$cek->row()->nama_outlet);
            redirect('management_inventory/principal_area/'.$signature.'/'.$supp);
            die;

        }else{

            $data = [
                "status"                    => 2,
                "nama_status"               => "PENDING MPM",
                "principal_area_signature"  => $this->session->userdata('username').'-signature.png',
                "principal_area_at"         => $this->model_outlet_transaksi->timezone(),
                "principal_area_by"         => $this->session->userdata('id')
            ];
            $this->db->where("signature", $signature);
            $this->db->update("management_inventory.pengajuan_retur", $data);

            // $this->session->set_flashdata("pesan_success", "Proses Success. Terima kasih");
        }

        redirect('management_inventory/email_pengajuan_new/'.$signature);
        die;

    }

    public function email_pengajuan_new($signature){
        $get_pengajuan = $this->model_management_inventory->get_pengajuan($signature);
        foreach ($get_pengajuan->result() as $a) {
            $id_pengajuan               = $a->id;
            $site_code                  = $a->site_code;
            $branch_name                = $a->branch_name;
            $nama_comp                  = $a->nama_comp;
            $supp                       = $a->supp;
            $namasupp                   = $a->namasupp;
            $no_pengajuan               = $a->no_pengajuan;
            $tanggal_pengajuan          = $a->tanggal_pengajuan;
            $nama                       = $a->nama;
            $status                     = $a->status;
            $nama_status                = $a->nama_status;
            $no_pengajuan               = $a->no_pengajuan;
            $created_at                 = $a->created_at;
            $file                       = $a->file;
            $digital_signature          = $a->digital_signature;
            $signature                  = $a->signature;
            $verifikasi_at              = $a->verifikasi_at;
            $verifikasi_username        = $a->verifikasi_username;
            $verifikasi_signature       = $a->verifikasi_signature;
            $principal_area_at          = $a->principal_area_at;
            $principal_area_signature   = $a->principal_area_signature;
            $principal_area_username    = $a->principal_area_username;
            $file_principal_area        = $a->file_principal_area;
            $catatan_principal_area     = $a->catatan_principal_area;
            $principal_ho_at            = $a->principal_ho_at;
            $principal_ho_signature     = $a->principal_ho_signature;
            $principal_ho_username      = $a->principal_ho_username;
            $file_principal_ho          = $a->file_principal_ho;
            $catatan_principal_ho       = $a->catatan_principal_ho;
            $tanggal_kirim_barang       = $a->tanggal_kirim_barang;
            $nama_ekspedisi             = $a->nama_ekspedisi;
            $est_tanggal_tiba           = $a->est_tanggal_tiba;
            $file_pengiriman            = $a->file_pengiriman;
            $proses_kirim_barang_at     = $a->proses_kirim_barang_at;
            $username_kirim_barang      = $a->username_kirim_barang;
            $tanggal_terima_barang      = $a->tanggal_terima_barang;
            $nama_penerima              = $a->nama_penerima;
            $no_terima_barang           = $a->no_terima_barang;
            $file_terima_barang         = $a->file_terima_barang;
            $terima_barang_at           = $a->terima_barang_at;
            $username_terima_barang     = $a->username_terima_barang;
        }

        $get_pengajuan_detail_summary = $this->model_management_inventory->get_pengajuan_detail_summary($id_pengajuan);
        foreach ($get_pengajuan_detail_summary->result() as $a) {
            $count_kodeprod = $a->count_kodeprod;
            $value_rbp = $a->value_rbp;
        }

        $get_email_to       = $this->model_management_inventory->get_email_to_retur_by_site_code($site_code, $supp)->row()->email;
        $get_username_to    = $this->model_management_inventory->get_email_to_retur_by_site_code($site_code, $supp)->row()->username;

        $data = [
            'get_pengajuan_detail'  => $this->model_management_inventory->get_pengajuan_detail($id_pengajuan),
            'no_pengajuan'          => $no_pengajuan,
            'branch_name'           => $branch_name,
            'nama_comp'             => $nama_comp,
            'site_code'             => $site_code,
            'namasupp'              => $namasupp,
            'tanggal_pengajuan'     => $tanggal_pengajuan,
            'nama'                  => $nama,
            'status'                => $status,
            'nama_status'           => $nama_status,
            // 'created_by'            => $created_by,
            'file'                  => $file,
            'id_pengajuan'          => $id_pengajuan,
            'supp'                  => $supp,
            'signature'             => $signature,
            'count_kodeprod'        => $count_kodeprod,
            'value_rbp'             => $value_rbp,
            
            'verifikasi_at'         => $verifikasi_at,
            'verifikasi_username'   => $verifikasi_username,
            'principal_area_at'     => $principal_area_at,
            'principal_area_username' => $principal_area_username,
            'principal_ho_at'       => $principal_ho_at,
            'principal_ho_username' => $principal_ho_username,            
        ];

        $from = "suffy@muliaputramandiri.com";
        // rilis, to nya linda@muliaputramandiri.com
        $to = 'suffy.yanuar@gmail.com';

        $email_cc = $this->model_management_inventory->get_email($site_code)->row()->email . ', linda@muliaputramandiri.com, fakhrul@muliaputramandiri.com';

        $subject = "MPM SITE | RETUR : $no_pengajuan | ".$nama_status;
        $this->load->model('model_relokasi');
        $this->model_relokasi->email();

        $message = $this->load->view("management_inventory/email_pengajuan_new",$data,TRUE);

        $this->email->from($from,'PT. Mulia Putra Mandiri');
        $this->email->to($to);
        $this->email->cc($email_cc);
        $this->email->subject($subject);
        $this->email->message($message);
        $send = $this->email->send();
        // echo $this->email->print_debugger();
        if ($send) {
            // echo "<script>alert('pengiriman email berhasil'); </script>";
            $this->session->set_flashdata("pesan_success", "pengiriman email berhasil");
            redirect('management_inventory/principal_area/'.$signature.'/'.$supp);
        }else{
            echo "<script>alert('pengiriman email gagal'); </script>";
            redirect('management_inventory/principal_area/'.$signature.'/'.$supp);
        }
    }

    public function delete_detail($signature, $supp)
    {
        $id_pengajuan = $this->model_management_inventory->get_pengajuan_by_signature($signature)->row()->id;
        
        $query = "
            delete from management_inventory.pengajuan_retur_detail
            where id_pengajuan = $id_pengajuan
        ";

        $this->db->query($query);
        $this->session->set_flashdata("pesan_success", "berhasil menghapus seluruh product");
        redirect('management_inventory/pengajuan_retur_detail/'.$signature.'/'.$supp);


    }

    
}
?>
