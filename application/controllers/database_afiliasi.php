<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Database_afiliasi extends MY_Controller
{
    function database_afiliasi()
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
        $this->load->model('M_asn');
        $this->load->model('M_menu');
        $this->load->model('M_helpdesk');
        $this->load->model('model_inventory');
        $this->load->model('model_outlet_transaksi');
        $this->load->model('ModelDatabaseAfiliasi');
        $this->load->database();
    }

    public function index(){
        $this->profile();
    }

    public function profile(){
        $get_kode_alamat = $this->model_inventory->get_kode_alamat();
        $code = '';
        foreach ($get_kode_alamat as $key) {
            $code.= ","."'".$key->kode_alamat."'";
        }
        $kode_alamat = preg_replace('/,/', '', $code,1);
        // echo "kode_alamat : ".$kode_alamat;
        $data = [
            'id'                => $this->session->userdata('id'),
            'title'             => 'Profile DP',
            'get_label'         => $this->M_menu->get_label(),
            'url_profile'       => 'database_afiliasi/tambah_profile',
            'url_detail_gudang' => 'database_afiliasi/tambah_detail_gudang',
            'url_karyawan'      => 'database_afiliasi/tambah_karyawan',
            'url_niaga'         => 'database_afiliasi/tambah_niaga',
            'url_non_niaga'     => 'database_afiliasi/tambah_non_niaga',
            'url_it_asset'      => 'database_afiliasi/tambah_it_asset',
            'url_asset'         => 'database_afiliasi/tambah_asset',
            'url_struktur'      => 'database_afiliasi/tambah_struktur',
            'get_profile'       => $this->ModelDatabaseAfiliasi->get_profile($kode_alamat,'')->result(),
            'get_detail'        => $this->ModelDatabaseAfiliasi->get_detail($kode_alamat)->result(),
            'get_karyawan'      => $this->ModelDatabaseAfiliasi->get_karyawan($kode_alamat)->result(),
            'get_niaga'         => $this->ModelDatabaseAfiliasi->get_niaga($kode_alamat)->result(),
            'get_non_niaga'     => $this->ModelDatabaseAfiliasi->get_non_niaga($kode_alamat)->result(),
            'get_it_asset'      => $this->ModelDatabaseAfiliasi->get_it_asset($kode_alamat)->result(),
            'get_asset'         => $this->ModelDatabaseAfiliasi->get_asset($kode_alamat)->result(),
            'supp'              => $this->session->userdata('supp'),
            'provinsi'          => $this->ModelDatabaseAfiliasi->get_provinsi()
        ];

        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar',$data);
        $this->load->view('template_claim/top_content',$data);
        $this->load->view('database_afiliasi/profile',$data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
    }

    public function get_dbafiliasi()
    {

        $id = $_GET['id'];
        // $data['get_profile']   = 'aas';
        $data['get_profile']   = $this->ModelDatabaseAfiliasi->get_profile('', $id)->row();
        $data['get_detail']   = $this->ModelDatabaseAfiliasi->get_detail('', $id)->row();
        $data['get_karyawan']   = $this->ModelDatabaseAfiliasi->get_karyawan('', $id)->row();
        $data['get_niaga']   = $this->ModelDatabaseAfiliasi->get_niaga('', $id)->row();
        $data['get_non_niaga']   = $this->ModelDatabaseAfiliasi->get_non_niaga('', $id)->row();
        $data['get_it_asset']   = $this->ModelDatabaseAfiliasi->get_it_asset('', $id)->row();
        $data['get_asset']   = $this->ModelDatabaseAfiliasi->get_asset('', $id)->row();
        echo json_encode($data);

    }

    public function tambah_profile(){

        $id_profile = $this->input->post('id_profile');
        // var_dump($id_profile) ;die;

        if (!is_dir('./assets/file/database_afiliasi/')) {
            @mkdir('./assets/file/database_afiliasi/', 0777);
        }

        $this->load->library('upload'); // Load librari upload
        $config['upload_path'] = './assets/file/database_afiliasi/';
        $config['allowed_types'] = 'jpg|png|jpeg|';
        $config['max_size']  = '5000';
        $config['overwrite'] = false;
        $this->upload->initialize($config);

        // Load konfigurasi uploadnya
        if($this->upload->do_upload('foto_tampak_depan'))
        {
            $upload_data = $this->upload->data();
            $foto_tampak_depan = $upload_data['file_name'];
        }else{
            $upload_data = '';
            $foto_tampak_depan = '';
        }

        if($this->upload->do_upload('foto_gudang'))
        {
            $upload_data = $this->upload->data();
            $foto_gudang = $upload_data['file_name'];
        }else{
            $upload_data = '';
            $foto_gudang = '';
        }

        if($this->upload->do_upload('foto_kantor'))
        {
            $upload_data = $this->upload->data();
            $foto_kantor = $upload_data['file_name'];
        }else{
            $upload_data = '';
            $foto_kantor = '';
        }

        if($this->upload->do_upload('foto_area_loading_gudang'))
        {
            $upload_data = $this->upload->data();
            $foto_area_loading_gudang = $upload_data['file_name'];
        }else{
            $upload_data = '' ;
            $foto_area_loading_gudang = '';
        }

        if($this->upload->do_upload('foto_gudang_baik'))
        {
            $upload_data = $this->upload->data();
            $foto_gudang_baik = $upload_data['file_name'];
        }else{
            $upload_data = '';
            $foto_gudang_baik = '';
        }

        if($this->upload->do_upload('foto_gudang_retur'))
        {
            $upload_data = $this->upload->data();
            $foto_gudang_retur = $upload_data['file_name'];
        }else{
            $upload_data = '';
            $foto_gudang_retur = '';
        }

        // die;

        $data = [
            "site_code"         => $this->input->post('site_code'),
            "nama"              => $this->input->post('nama'),
            "status_afiliasi"   => $this->input->post('status_afiliasi'),
            "alamat"            => $this->input->post('alamat'),
            "propinsi"          => $this->input->post('propinsi'),
            "kota"              => $this->input->post('kabupaten'),
            "kecamatan"         => $this->input->post('kecamatan'),
            "kelurahan"         => $this->input->post('kelurahan'),
            "kodepos"           => $this->input->post('kodepos'),
            "telp"              => $this->input->post('telp'),
            "status_properti"   => $this->input->post('status_properti'),
            "sewa_from"         => $this->input->post('sewa_from'),
            "sewa_to"           => $this->input->post('sewa_to'),
            "harga_sewa"        => $this->input->post('harga_sewa'),
            "bentuk_bangunan"   => $this->input->post('bentuk_bangunan'),
            "foto_tampak_depan" => $foto_tampak_depan,
            "foto_gudang"       => $foto_gudang,
            "foto_kantor"       => $foto_kantor,
            "foto_area_loading_gudang"   => $foto_area_loading_gudang,
            "foto_gudang_baik"  => $foto_gudang_baik,
            "foto_gudang_retur" => $foto_gudang_retur,
        ];
        // var_dump($data);die;

        if ($id_profile == '') {
            # code...
            $data["created_at"] = $this->model_outlet_transaksi->timezone();
            $data["created_by"] = $this->session->userdata('id');
            $proses = $this->ModelDatabaseAfiliasi->tambah('site.t_dbafiliasi_tabel_profile', $data);
        } else {
            # code...
            $this->db->select('*');
            $this->db->where('id', $id_profile);
            $proses =  $this->db->get('site.t_dbafiliasi_tabel_profile')->row();


            $data["id"]= $id_profile;

            if ($foto_tampak_depan == '') {
                $data['foto_tampak_depan'] = $proses->foto_tampak_depan;
            }else {
                $data['foto_tampak_depan'] = $foto_tampak_depan;
            }

            if ($foto_gudang == '') {
                $data['foto_gudang'] = $proses->foto_gudang;
            }else {
                $data['foto_gudang'] = $foto_gudang;
            }

            if ($foto_kantor == '') {
                $data['foto_kantor'] = $proses->foto_kantor;
            }else {
                $data['foto_kantor'] = $foto_kantor;
            }

            if ($foto_area_loading_gudang == '') {
                $data['foto_area_loading_gudang'] = $proses->foto_area_loading_gudang;
            }else {
                $data['foto_area_loading_gudang'] = $foto_area_loading_gudang;
            }

            if ($foto_gudang_baik == '') {
                $data['foto_gudang_baik'] = $proses->foto_gudang_baik;
            }else {
                $data['foto_gudang_baik'] = $foto_gudang_baik;
            }

            if ($foto_gudang_retur == '') {
                $data['foto_gudang_retur'] = $proses->foto_gudang_retur;
            }else {
                $data['foto_gudang_retur'] = $foto_gudang_retur;
            }
            $data["updated_at"] = $this->model_outlet_transaksi->timezone();
            $data["created_by"] = $this->session->userdata('id');
            $proses = $this->ModelDatabaseAfiliasi->edit('site.t_dbafiliasi_tabel_profile', $data);
        }

        if ($proses) {
            echo '<script>alert("Data berhasil terbentuk);</script>';
            redirect('database_afiliasi','refresh');

        }else{
            echo '<script>alert("Tidak berhasil menginput data. Jika perlu bantuan silahkan hubungi IT !");</script>';
            redirect('database_afiliasi','refresh');
        }

    }

    public function tambah_detail_gudang(){
        $id_gudang = $this->input->post('id_gudang');

        $data = [
            "site_code"             => $this->input->post('site_code'),
            "luas_gudang"           => $this->input->post('panjang_gudang') * $this->input->post('lebar_gudang'),
            "panjang_gudang"        => $this->input->post('panjang_gudang'),
            "lebar_gudang"          => $this->input->post('lebar_gudang'),
            "pallet_gudang"         => $this->input->post('pallet_gudang'),
            "racking_gudang"        => $this->input->post('racking_gudang'),

            "luas_gudang_baik"      => $this->input->post('panjang_gudang_baik') * $this->input->post('lebar_gudang_baik'),
            "panjang_gudang_baik"   => $this->input->post('panjang_gudang_baik'),
            "lebar_gudang_baik"     => $this->input->post('lebar_gudang_baik'),
            "pallet_gudang_baik"    => $this->input->post('pallet_gudang_baik'),
            "racking_gudang_baik"   => $this->input->post('racking_gudang_baik'),

            "luas_gudang_retur"     => $this->input->post('panjang_gudang_retur') * $this->input->post('lebar_gudang_retur'),
            "panjang_gudang_retur"  => $this->input->post('panjang_gudang_retur'),
            "lebar_gudang_retur"    => $this->input->post('lebar_gudang_retur'),
            "pallet_gudang_retur"   => $this->input->post('pallet_gudang_retur'),
            "racking_gudang_retur"  => $this->input->post('racking_gudang_retur'),

            "luas_gudang_karantina"     => $this->input->post('panjang_gudang_karantina') * $this->input->post('lebar_gudang_karantina'),
            "panjang_gudang_karantina"  => $this->input->post('panjang_gudang_karantina'),
            "lebar_gudang_karantina"    => $this->input->post('lebar_gudang_karantina'),
            "pallet_gudang_karantina"   => $this->input->post('pallet_gudang_karantina'),
            "racking_gudang_karantina"  => $this->input->post('racking_gudang_karantina'),

            "luas_gudang_ac"        => $this->input->post('panjang_gudang_ac') * $this->input->post('lebar_gudang_ac'),
            "panjang_gudang_ac"     => $this->input->post('panjang_gudang_ac'),
            "lebar_gudang_ac"       => $this->input->post('lebar_gudang_ac'),
            "pallet_gudang_ac"      => $this->input->post('pallet_gudang_ac'),
            "racking_gudang_ac"     => $this->input->post('racking_gudang_ac'),

            "luas_loading_dock"     => $this->input->post('panjang_loading_dock') * $this->input->post('lebar_loading_dock'),
            "panjang_loading_dock"  => $this->input->post('panjang_loading_dock'),
            "lebar_loading_dock"    => $this->input->post('lebar_loading_dock'),
            "pallet_gudang_loading" => $this->input->post('pallet_gudang_loading'),
            "racking_gudang_loading"=> $this->input->post('racking_gudang_loading'),

            "jumlah_pallet"         => $this->input->post('jumlah_pallet'),
            "jumlah_hand_pallet"    => $this->input->post('jumlah_hand_pallet'),
            "jumlah_trolley"        => $this->input->post('jumlah_trolley'),
            "jumlah_sealer"         => $this->input->post('jumlah_sealer'),
            "jumlah_ac"             => $this->input->post('jumlah_ac'),
            "jumlah_exhaust_fan"    => $this->input->post('jumlah_exhaust_fan'),
            "jumlah_kipas_angin"    => $this->input->post('jumlah_kipas_angin'),

            "luas_kantor_div_logistik"      => $this->input->post('panjang_kantor_div_logistik') * $this->input->post('lebar_kantor_div_logistik'),
            "panjang_kantor_div_logistik"   => $this->input->post('panjang_kantor_div_logistik'),
            "lebar_kantor_div_logistik"     => $this->input->post('lebar_kantor_div_logistik'),

            "total_mobil_penumpang"         => $this->input->post('jumlah_mobil_penumpang_sewa') + $this->input->post('jumlah_mobil_penumpang_milik_sendiri'),
            "jumlah_mobil_penumpang_sewa"   => $this->input->post('jumlah_mobil_penumpang_sewa'),
            "jumlah_mobil_penumpang_milik_sendiri" => $this->input->post('jumlah_mobil_penumpang_milik_sendiri'),

            "total_mobil_pengiriman"            => $this->input->post('jumlah_mobil_pengiriman_blind_van') + $this->input->post('jumlah_mobil_pengiriman_engkel') + $this->input->post('jumlah_mobil_pengiriman_double'),
            "jumlah_mobil_pengiriman_blind_van" => $this->input->post('jumlah_mobil_pengiriman_blind_van'),
            "jumlah_mobil_pengiriman_engkel"    => $this->input->post('jumlah_mobil_pengiriman_engkel'),
            "jumlah_mobil_pengiriman_double"    => $this->input->post('jumlah_mobil_pengiriman_double'),

            "total_blind_van"                   => $this->input->post('jumlah_blind_van_sewa') + $this->input->post('jumlah_blind_van_milik_sendiri'),
            "jumlah_blind_van_sewa"             => $this->input->post('jumlah_blind_van_sewa'),
            "jumlah_blind_van_milik_sendiri"    => $this->input->post('jumlah_blind_van_milik_sendiri'),

            "total_engkel"                  => $this->input->post('jumlah_engkel_sewa') + $this->input->post('jumlah_engkel_milik_sendiri'),
            "jumlah_engkel_sewa"            => $this->input->post('jumlah_engkel_sewa'),
            "jumlah_engkel_milik_sendiri"   => $this->input->post('jumlah_engkel_milik_sendiri'),

            "total_double"                  => $this->input->post('jumlah_double_sewa') + $this->input->post('jumlah_double_milik_sendiri'),
            "jumlah_double_sewa"            => $this->input->post('jumlah_double_sewa'),
            "jumlah_double_milik_sendiri"   => $this->input->post('jumlah_double_milik_sendiri'),

            "total_motor_pengiriman"                => $this->input->post('jumlah_motor_pengiriman_sewa') + $this->input->post('jumlah_motor_pengiriman_milik_sendiri'),
            "jumlah_motor_pengiriman_sewa"          => $this->input->post('jumlah_motor_pengiriman_sewa'),
            "jumlah_motor_pengiriman_milik_sendiri" => $this->input->post('jumlah_motor_pengiriman_milik_sendiri'),

            "total_saddle_bag"              => $this->input->post('jumlah_saddle_bag_dipakai') + $this->input->post('jumlah_saddle_bag_cadangan'),
            "jumlah_saddle_bag_dipakai"     => $this->input->post('jumlah_saddle_bag_dipakai'),
            "jumlah_saddle_bag_cadangan"    => $this->input->post('jumlah_saddle_bag_cadangan'),

            "luas_kantor_total"     => $this->input->post('panjang_kantor_total') * $this->input->post('lebar_kantor_total'),
            "panjang_kantor_total"  => $this->input->post('panjang_kantor_total'),
            "lebar_kantor_total"    => $this->input->post('lebar_kantor_total'),

            "luas_ruang_sales"      => $this->input->post('panjang_ruang_sales') * $this->input->post('lebar_ruang_sales'),
            "panjang_ruang_sales"   => $this->input->post('panjang_ruang_sales'),
            "lebar_ruang_sales"     => $this->input->post('lebar_ruang_sales'),

            "luas_ruang_finance"    => $this->input->post('panjang_ruang_finance') * $this->input->post('lebar_ruang_finance'),
            "panjang_ruang_finance" => $this->input->post('panjang_ruang_finance'),
            "lebar_ruang_finance"   => $this->input->post('lebar_ruang_finance'),

            "luas_ruang_logistik"       => $this->input->post('panjang_ruang_logistik') * $this->input->post('lebar_ruang_logistik'),
            "panjang_ruang_logistik"    => $this->input->post('panjang_ruang_logistik'),
            "lebar_ruang_logistik"      => $this->input->post('lebar_ruang_logistik'),

            "luas_gudang_arsip"     => $this->input->post('panjang_gudang_arsip') * $this->input->post('lebar_gudang_arsip'),
            "panjang_gudang_arsip"  => $this->input->post('panjang_gudang_arsip'),
            "lebar_gudang_arsip"    => $this->input->post('lebar_gudang_arsip'),
        ];

        if ($id_gudang == '') {
            # code...
            $data["created_at"] = $this->model_outlet_transaksi->timezone();
            $data["created_by"] = $this->session->userdata('id');
            $proses = $this->ModelDatabaseAfiliasi->tambah('site.t_dbafiliasi_tabel_detail_gudang', $data);
        } else {
            # code...
            $data["id"]= $id_gudang;
            $data["updated_at"] = $this->model_outlet_transaksi->timezone();
            $data["created_by"] = $this->session->userdata('id');
            $proses = $this->ModelDatabaseAfiliasi->edit('site.t_dbafiliasi_tabel_detail_gudang', $data);
        }

        if ($proses) {
            echo '<script>alert("Data berhasil terbentuk);</script>';
            redirect('database_afiliasi','refresh');

        }else{
            echo '<script>alert("Tidak berhasil menginput data. Jika perlu bantuan silahkan hubungi IT !");</script>';
            redirect('database_afiliasi','refresh');
        }

    }

    public function tambah_karyawan(){
        $id_krywn = $this->input->post('id_krywn');

        $data = [
            "site_code"         => $this->input->post('site_code'),
            "nama"              => $this->input->post('nama'),
            "jenis_kelamin"     => $this->input->post('jenis_kelamin'),
            "tempat"            => $this->input->post('tempat'),
            "tanggal_lahir"     => $this->input->post('tanggal_lahir'),
            "tingkat_pendidikan" => $this->input->post('tingkat_pendidikan'),
            "status_pernikahan" => $this->input->post('status_pernikahan'),
            "department"        => $this->input->post('department'),
            "jabatan"           => $this->input->post('jabatan'),
            "status_karyawan"   => $this->input->post('status_karyawan'),
            "tanggal_masuk_kerja" => $this->input->post('tanggal_masuk_kerja')
        ];

        if ($id_krywn == '') {
            # code...
            $data["created_at"] = $this->model_outlet_transaksi->timezone();
            $data["created_by"] = $this->session->userdata('id');
            $proses = $this->ModelDatabaseAfiliasi->tambah('site.t_dbafiliasi_tabel_karyawan', $data);
        } else {
            # code...
            $data["id"]= $id_krywn;
            $data["updated_at"] = $this->model_outlet_transaksi->timezone();
            $data["created_by"] = $this->session->userdata('id');
            $proses = $this->ModelDatabaseAfiliasi->edit('site.t_dbafiliasi_tabel_karyawan', $data);
        }

        if ($proses) {
            echo '<script>alert("Data berhasil terbentuk);</script>';
            redirect('database_afiliasi','refresh');

        }else{
            echo '<script>alert("Tidak berhasil menginput data. Jika perlu bantuan silahkan hubungi IT !");</script>';
            redirect('database_afiliasi','refresh');
        }

    }

    public function tambah_niaga(){
        $id_niaga = $this->input->post('id_niaga');

        $data = [
            "site_code" => $this->input->post('site_code'),
            "jenis_kendaraan" => $this->input->post('jenis_kendaraan'),
            "kepemilikan" => $this->input->post('kepemilikan'),
            "bahan_bakar" => $this->input->post('bahan_bakar'),
            "no_polisi" => $this->input->post('no_polisi'),
            "tahun_pembuatan" => $this->input->post('tahun_pembuatan'),
            "tanggal_pajak_berakhir" => $this->input->post('tanggal_pajak_berakhir'),
            "tanggal_pajak_kir" => $this->input->post('tanggal_pajak_kir'),
            "vendor" => $this->input->post('vendor'),
            "tanggal_awal_sewa" => $this->input->post('tanggal_awal_sewa'),
            "tanggal_akhir_sewa" => $this->input->post('tanggal_akhir_sewa'),
            "created_at"        => $this->model_outlet_transaksi->timezone(),
            "created_by"        => $this->session->userdata('id')
        ];

        if ($id_niaga == '') {
            # code...
            $data["created_at"] = $this->model_outlet_transaksi->timezone();
            $data["created_by"] = $this->session->userdata('id');
            $proses = $this->ModelDatabaseAfiliasi->tambah('site.t_dbafiliasi_tabel_niaga', $data);
        } else {
            # code...
            $data["id"]= $id_niaga;
            $data["updated_at"] = $this->model_outlet_transaksi->timezone();
            $data["created_by"] = $this->session->userdata('id');
            $proses = $this->ModelDatabaseAfiliasi->edit('site.t_dbafiliasi_tabel_niaga', $data);
        }

        if ($proses) {
            echo '<script>alert("Data berhasil terbentuk);</script>';
            redirect('database_afiliasi','refresh');

        }else{
            echo '<script>alert("Tidak berhasil menginput data. Jika perlu bantuan silahkan hubungi IT !");</script>';
            redirect('database_afiliasi','refresh');
        }

    }

    public function tambah_non_niaga(){
        $id_non_niaga = $this->input->post('id_non_niaga');

        $data = [
            "site_code"             => $this->input->post('site_code'),
            "jenis_kendaraan"       => $this->input->post('jenis_kendaraan'),
            "kepemilikan"           => $this->input->post('kepemilikan'),
            "nama_pemakai"          => $this->input->post('nama_pemakai'),
            "jabatan"               => $this->input->post('jabatan'),
            "bahan_bakar"           => $this->input->post('bahan_bakar'),
            "no_polisi"             => $this->input->post('no_polisi'),
            "tahun_pembuatan"       => $this->input->post('tahun_pembuatan'),
            "tanggal_pajak_berakhir"=> $this->input->post('tanggal_pajak_berakhir'),
            "vendor"                => $this->input->post('vendor'),
            "tanggal_awal_sewa"     => $this->input->post('tanggal_awal_sewa'),
            "tanggal_akhir_sewa"    => $this->input->post('tanggal_akhir_sewa')
        ];

        if ($id_non_niaga == '') {
            # code...
            $data["created_at"] = $this->model_outlet_transaksi->timezone();
            $data["created_by"] = $this->session->userdata('id');
            $proses = $this->ModelDatabaseAfiliasi->tambah('site.t_dbafiliasi_tabel_non_niaga', $data);
        } else {
            # code...
            $data["id"]= $id_non_niaga;
            $data["updated_at"] = $this->model_outlet_transaksi->timezone();
            $data["created_by"] = $this->session->userdata('id');
            $proses = $this->ModelDatabaseAfiliasi->edit('site.t_dbafiliasi_tabel_non_niaga', $data);
        }

        if ($proses) {
            echo '<script>alert("Data berhasil terbentuk);</script>';
            redirect('database_afiliasi','refresh');

        }else{
            echo '<script>alert("Tidak berhasil menginput data. Jika perlu bantuan silahkan hubungi IT !");</script>';
            redirect('database_afiliasi','refresh');
        }

    }

    public function tambah_it_asset(){
        $id_it_asset = $this->input->post('id_it_asset');

        $data = [
            "site_code"         => $this->input->post('site_code'),
            "nama_asset"        => $this->input->post('nama_asset'),
            "merk"              => $this->input->post('merk'),
            "type"              => $this->input->post('type'),
            "tanggal_pembelian" => $this->input->post('tanggal_pembelian'),
            "operating_system"  => $this->input->post('operating_system'),
            "processor"         => $this->input->post('processor'),
            "ram"               => $this->input->post('ram'),
            "storage"           => $this->input->post('storage'),
            "kapasitas_baterai" => $this->input->post('kapasitas_baterai'),
            "divisi_pemakai"    => $this->input->post('divisi_pemakai'),
            "jabatan_pemakai"   => $this->input->post('jabatan_pemakai'),
            "created_at"        => $this->model_outlet_transaksi->timezone(),
            "created_by"        => $this->session->userdata('id')
        ];

        if ($id_it_asset == '') {
            # code...
            $data["created_at"] = $this->model_outlet_transaksi->timezone();
            $data["created_by"] = $this->session->userdata('id');
            $proses = $this->ModelDatabaseAfiliasi->tambah('site.t_dbafiliasi_tabel_it_asset', $data);
        } else {
            # code...
            $data["id"]= $id_it_asset;
            $data["updated_at"] = $this->model_outlet_transaksi->timezone();
            $data["created_by"] = $this->session->userdata('id');
            $proses = $this->ModelDatabaseAfiliasi->edit('site.t_dbafiliasi_tabel_it_asset', $data);
        }

        if ($proses) {
            echo '<script>alert("Data berhasil terbentuk);</script>';
            redirect('database_afiliasi','refresh');

        }else{
            echo '<script>alert("Tidak berhasil menginput data. Jika perlu bantuan silahkan hubungi IT !");</script>';
            redirect('database_afiliasi','refresh');
        }

    }

    public function tambah_asset(){
        $id_asset = $this->input->post('id_asset');

        $data = [
            "site_code"         => $this->input->post('site_code'),
            "jenis_asset"       => $this->input->post('jenis_asset'),
            "merk"              => $this->input->post('merk'),
            "type"              => $this->input->post('type'),
            "tanggal_pembelian" => $this->input->post('tanggal_pembelian'),
            "divisi_pemakai"    => $this->input->post('divisi_pemakai'),
            "jabatan_pemakai"   => $this->input->post('jabatan_pemakai'),
            "created_at"        => $this->model_outlet_transaksi->timezone(),
            "created_by"        => $this->session->userdata('id')
        ];

        if ($id_asset == '') {
            # code...
            $data["created_at"] = $this->model_outlet_transaksi->timezone();
            $data["created_by"] = $this->session->userdata('id');
            $proses = $this->ModelDatabaseAfiliasi->tambah('site.t_dbafiliasi_tabel_asset', $data);
        } else {
            # code...
            $data["id"]= $id_asset;
            $data["updated_at"] = $this->model_outlet_transaksi->timezone();
            $data["created_by"] = $this->session->userdata('id');
            $proses = $this->ModelDatabaseAfiliasi->edit('site.t_dbafiliasi_tabel_asset', $data);
        }

        if ($proses) {
            echo '<script>alert("Data berhasil terbentuk);</script>';
            redirect('database_afiliasi','refresh');

        }else{
            echo '<script>alert("Tidak berhasil menginput data. Jika perlu bantuan silahkan hubungi IT !");</script>';
            redirect('database_afiliasi','refresh');
        }


    }

    public function tambah_struktur(){

        $data = [
            "site_code"         => $this->input->post('site_code'),
            "karyawan"          => $this->input->post('karyawan'),
            "atasan_langsung"   => $this->input->post('atasan_langsung'),
            "created_at"        => $this->model_outlet_transaksi->timezone(),
            "created_by"        => $this->session->userdata('id')
        ];

        $proses = $this->ModelDatabaseAfiliasi->tambah('site.t_dbafiliasi_tabel_struktur', $data);
        if ($proses) {
            echo '<script>alert("Data berhasil terbentuk);</script>';
            redirect('database_afiliasi','refresh');

        }else{
            echo '<script>alert("Tidak berhasil menginput data. Jika perlu bantuan silahkan hubungi IT !");</script>';
            redirect('database_afiliasi','refresh');
        }

    }

    public function delete()
    {
        $table = $this->uri->segment('3');
        $data = $this->uri->segment('4');
        // $data =[
        //     'id' => $this->uri->segment('4'),
        //     'deleted' => '1',
        //     'updated_at' => $this->model_outlet_transaksi->timezone(),
        //     'created_by' => $this->session->userdata('id')
        // ];
        // var_dump($table);die;

        if ($table == md5('profile')) {
            $tables = 'site.t_dbafiliasi_tabel_profile';
        }elseif ($table == md5('gudang')) {
            $tables = 'site.t_dbafiliasi_tabel_detail_gudang';
        }elseif ($table == md5('karyawan')) {
            $tables = 'site.t_dbafiliasi_tabel_karyawan';
        }elseif ($table == md5('niaga')) {
            $tables = 'site.t_dbafiliasi_tabel_niaga';
        }elseif ($table == md5('non_niaga')) {
            $tables = 'site.t_dbafiliasi_tabel_non_niaga';
        }elseif ($table == md5('it_asset')) {
            $tables = 'site.t_dbafiliasi_tabel_it_asset';
        }elseif ($table == md5('asset')) {
            $tables = 'site.t_dbafiliasi_tabel_asset';
        }


        $proses = $this->ModelDatabaseAfiliasi->delete($tables, $data);
        if ($proses) {
            echo '<script>alert("Data berhasil terhapus);</script>';
            redirect('database_afiliasi','refresh');

        }else{
            echo '<script>alert("Tidak berhasil hapus data. Jika perlu bantuan silahkan hubungi IT !");</script>';
            redirect('database_afiliasi','refresh');
        }
    }

    public function struktur_organisasi(){
        $this->load->view('database_afiliasi/struktur_organisasi');
        // echo "a";
    }

    public function modal(){

        $get_kode_alamat = $this->model_inventory->get_kode_alamat();
        $code = '';
        foreach ($get_kode_alamat as $key) {
            $code.= ","."'".$key->kode_alamat."'";
        }
        $kode_alamat = preg_replace('/,/', '', $code,1);

        $data = [
            'id'            => $this->session->userdata('id'),
            'title'         => 'Database Afiliasi | Profile',
            'get_label'     => $this->M_menu->get_label(),
            'url'           => 'database_afiliasi/tambah_profile',
            'site_code'     => $this->M_helpdesk->get_sitecode(),
            'get_profile'   => $this->ModelDatabaseAfiliasi->get_profile($kode_alamat),
            'get_detail'   => $this->ModelDatabaseAfiliasi->get_detail($kode_alamat),
            'get_karyawan'   => $this->ModelDatabaseAfiliasi->get_karyawan($kode_alamat),
            'get_niaga'   => $this->ModelDatabaseAfiliasi->get_niaga($kode_alamat),
            'get_nonniaga'   => $this->ModelDatabaseAfiliasi->get_nonniaga($kode_alamat),
            'get_itasset'   => $this->ModelDatabaseAfiliasi->get_itasset($kode_alamat),
            'get_asset'   => $this->ModelDatabaseAfiliasi->get_asset($kode_alamat),
            // 'get_strukturorganisasi'   => $this->ModelDatabaseAfiliasi->get_strukturorganisasi($kode_alamat),
            'supp'          => $this->session->userdata('supp'),
            'province'      => '$this->province()'
        ];

        // $this->load->view('template_claim/top_header');
        // $this->load->view('template_claim/header');
        // $this->load->view('template_claim/sidebar',$data);
        // $this->load->view('template_claim/top_content',$data);
        $this->load->view('database_afiliasi/modal',$data);
        // $this->load->view('template_claim/bottom_content');
        // $this->load->view('template_claim/footer');
    }

    public function province(){

        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => "http://api.rajaongkir.com/starter/province",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => array(
            "key: fbe2d39b51701d074f48c5aa146b5312"
        ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
        echo "cURL Error #:" . $err;
        } else {
            $array_response = json_decode($response, true);
            $dataprovinsi = $array_response['rajaongkir']['results'];
            echo "<pre>";
            print_r($dataprovinsi);
            echo "</pre>";
        }
    }

    public function provinsi(){

        $curl = curl_init();
        $token = '11f3a8a682c1e8d097ae60d72ecf07c7';

        curl_setopt_array($curl, array(
            // CURLOPT_URL => "http://site.muliaputramandiri.com/restapi/api/master_data/provinsi?token=$token",
            CURLOPT_URL => "http://site.muliaputramandiri.com/restapi/api/master_data/provinsi?token=$token&X-API-KEY=123",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            // CURLOPT_HTTPHEADER => array('X-API-KEY : 123')
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
        echo "cURL Error #:" . $err;
        } else {
            $array_response = json_decode($response, true);
            $dataprovinsi = $array_response['data'];
            // var_dump($dataprovinsi);die;
            echo "<option value=''> -- Pilih Provinsi -- </option>";

            foreach ($dataprovinsi as $key => $tiap_provinsi)
            {
                echo "<option value='". $tiap_provinsi["nama_provinsi"] ."' id_provinsi='" . $tiap_provinsi["id"] . "' >";
                echo $tiap_provinsi["nama_provinsi"];
                echo "</option>";
            }
        }
    }

    public function kabupaten(){

        $curl = curl_init();

        $token = '11f3a8a682c1e8d097ae60d72ecf07c7';
        $id_provinsi = $this->input->post('id_provinsi');

        curl_setopt_array($curl, array(
        // CURLOPT_URL => "http://site.muliaputramandiri.com/restapi/api/master_data/kabupaten?token=$token?id_provinsi = $id_provinsi",
        CURLOPT_URL => "http://site.muliaputramandiri.com/restapi/api/master_data/kabupaten?&token=$token&id_provinsi=$id_provinsi&X-API-KEY=123",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        // CURLOPT_HTTPHEADER => array('X-API-KEY : 123')
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
        echo "cURL Error #:" . $err;
        } else {
            $array_response = json_decode($response, true);
            $datakabupaten = $array_response['data'];

            echo "<option value=''> -- Pilih Kabupaten -- </option>";

            foreach ($datakabupaten as $key => $tiap_kabupaten)
            {
                echo "<option value='". $tiap_kabupaten["nama_kabupaten"] ."' id_kabupaten='" . $tiap_kabupaten["id"] . "' >";
                echo $tiap_kabupaten["nama_kabupaten"];
                echo "</option>";
            }
        }
    }

    public function kecamatan(){

        $curl = curl_init();

        $token = '11f3a8a682c1e8d097ae60d72ecf07c7';
        $id_kabupaten = $this->input->post('id_kabupaten');

        curl_setopt_array($curl, array(
        // CURLOPT_URL => "http://site.muliaputramandiri.com/restapi/api/master_data/kabupaten?token=$token?id_provinsi = $id_provinsi",
        CURLOPT_URL => "http://site.muliaputramandiri.com/restapi/api/master_data/kecamatan?&token=$token&id_kabupaten=$id_kabupaten&X-API-KEY=123",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        // CURLOPT_HTTPHEADER => array('X-API-KEY : 123')
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
        echo "cURL Error #:" . $err;
        } else {
            $array_response = json_decode($response, true);
            $datakecamatan = $array_response['data'];

            echo "<option value=''> -- Pilih Kabupaten -- </option>";

            foreach ($datakecamatan as $key => $tiap_kecamatan)
            {
                echo "<option value='". $tiap_kecamatan["nama_kecamatan"] ."' id_kecamatan='" . $tiap_kecamatan["id_kecamatan"] . "' >";
                echo $tiap_kecamatan["nama_kecamatan"];
                echo "</option>";
            }
        }
    }

    public function kelurahan(){

        $curl = curl_init();

        $token = '11f3a8a682c1e8d097ae60d72ecf07c7';
        $id_kecamatan = $this->input->post('id_kecamatan');

        curl_setopt_array($curl, array(
        // CURLOPT_URL => "http://site.muliaputramandiri.com/restapi/api/master_data/kabupaten?token=$token?id_provinsi = $id_provinsi",
        CURLOPT_URL => "http://site.muliaputramandiri.com/restapi/api/master_data/kelurahan?&token=$token&id_kecamatan=$id_kecamatan&X-API-KEY=123",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        // CURLOPT_HTTPHEADER => array('X-API-KEY : 123')
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
        echo "cURL Error #:" . $err;
        } else {
            $array_response = json_decode($response, true);

            $datakelurahan = $array_response['data'];
            echo "<option value=''> -- Pilih Kelurahan -- </option>";

            foreach ($datakelurahan as $key => $tiap_kelurahan)
            {
                echo "<option value='". $tiap_kelurahan["nama_kelurahan"] ."' id_kelurahan='" . $tiap_kelurahan["id"] . "' >";
                echo $tiap_kelurahan["nama_kelurahan"];
                echo "</option>";
            }
        }
    }

    public function subbranch(){

        $curl = curl_init();
        $token = '11f3a8a682c1e8d097ae60d72ecf07c7';
        $tahun = date('Y');
        $userid = 297;

        curl_setopt_array($curl, array(
        // CURLOPT_URL => "http://site.muliaputramandiri.com/restapi/api/master_data/provinsi?token=$token",
        CURLOPT_URL => "http://site.muliaputramandiri.com/restapi/api/master_data/site_code?token=$token&userid=$userid&tahun=$tahun&X-API-KEY=123",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        // CURLOPT_HTTPHEADER => array('X-API-KEY : 123')
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
        echo "cURL Error #:" . $err;
        } else {
            $array_response = json_decode($response, true);
            $datasubbranch = $array_response['data'];
            echo "<option value=''> -- Pilih SubBranch -- </option>";

            foreach ($datasubbranch as $key => $datasubbranch)
            {
                echo "<option value='". $datasubbranch["site_code"] ."' site_code='" . $datasubbranch["site_code"] . "' >";
                echo $datasubbranch["nama_comp"];
                echo "</option>";
            }
        }
    }

    public function karyawan(){

        $curl = curl_init();
        $token = '11f3a8a682c1e8d097ae60d72ecf07c7';
        $site_code = $this->input->post('site_code');
        // $site_code = 'bgr04';

        curl_setopt_array($curl, array(
        CURLOPT_URL => "http://site.muliaputramandiri.com/restapi/api/master_data/karyawan_afiliasi?token=$token&site_code=$site_code&X-API-KEY=123",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        // CURLOPT_HTTPHEADER => array('X-API-KEY : 123')
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
        echo "cURL Error #:" . $err;
        } else {
            $array_response = json_decode($response, true);
            $datakaryawan = $array_response['data'];
            echo "<option value=''> -- Pilih Karyawan -- </option>";

            foreach ($datakaryawan as $key => $datakaryawan)
            {
                echo "<option value='". $datakaryawan["nama"] ."'>";
                echo $datakaryawan["nama"];
                echo "</option>";
            }
        }
    }

    public function kodeprod(){

        $curl = curl_init();
        $token = '11f3a8a682c1e8d097ae60d72ecf07c7';
        $supp = $this->input->post('supp');

        curl_setopt_array($curl, array(
            // CURLOPT_URL => "http://site.muliaputramandiri.com/restapi/api/master_data/provinsi?token=$token",
            CURLOPT_URL => "http://site.muliaputramandiri.com/restapi/api/master_data/kodeprod?token=$token&supp=$supp&X-API-KEY=123",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            // CURLOPT_HTTPHEADER => array('X-API-KEY : 123')
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
        echo "cURL Error #:" . $err;
        } else {
            $array_response = json_decode($response, true);
            $datakodeprod = $array_response['data'];
            // var_dump($dataprovinsi);die;
            echo "<option value=''> -- Pilih Kodeprod -- </option>";

            foreach ($datakodeprod as $key => $tiap_kodeprod)
            {
                echo "<option value='". $tiap_kodeprod["KODEPROD"] ."' id_kodeprod='" . $tiap_kodeprod["KODEPROD"] . "' >";
                echo $tiap_kodeprod["KODEPROD"]." - ".$tiap_kodeprod["NAMAPROD"]." - ".$tiap_kodeprod["kecil"];
                echo "</option>";
            }
        }
    }

    public function namaprod(){

        $curl = curl_init();

        $token = '11f3a8a682c1e8d097ae60d72ecf07c7';
        $id_kodeprod = $this->input->post('id_kodeprod');

        curl_setopt_array($curl, array(
        CURLOPT_URL => "http://site.muliaputramandiri.com/restapi/api/master_data/namaprod?token=$token&X-API-KEY=123&id_kodeprod=$id_kodeprod",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        // CURLOPT_HTTPHEADER => array('X-API-KEY : 123')
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
        echo "cURL Error #:" . $err;
        } else {
            $array_response = json_decode($response, true);
            $datanamaprod = $array_response['data'];

            // echo "<option value=''> -- Pilih Namaprod -- </option>";

            foreach ($datanamaprod as $key => $tiap_namaprod)
            {
                echo "<option value='". $tiap_namaprod["NAMAPROD"] ."' id_kodeprod='" . $tiap_namaprod["NAMAPROD"] . "' >";
                echo $tiap_namaprod["NAMAPROD"];
                echo "</option>";
            }
        }
    }

    public function namaprod_input_type(){

        $curl = curl_init();

        $token = '11f3a8a682c1e8d097ae60d72ecf07c7';
        $id_kodeprod = $this->input->post('id_kodeprod');

        curl_setopt_array($curl, array(
        CURLOPT_URL => "http://site.muliaputramandiri.com/restapi/api/master_data/namaprod?token=$token&X-API-KEY=123&id_kodeprod=$id_kodeprod",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        // CURLOPT_HTTPHEADER => array('X-API-KEY : 123')
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
        echo "cURL Error #:" . $err;
        } else {
            $array_response = json_decode($response, true);
            $datanamaprod = $array_response['data'];

            // echo "<option value=''> -- Pilih Namaprod -- </option>";

            foreach ($datanamaprod as $key => $tiap_namaprod)
            {
                echo $tiap_namaprod["NAMAPROD"];
            }
        }
    }

    public function get_ip_public(){

        // if(!empty($_SERVER['HTTP_CLIENT_IP']))
        // {
        //     $ip=$_SERVER['HTTP_CLIENT_IP'];
        // }elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
        // {
        //     $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
        // }else
        // {
        //     $ip=$_SERVER['REMOTE_ADDR'];
        // }
        // $hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']);

        // echo "Host Name Public=".$hostname;
        // echo "IP Address=".$ip;

        // kedua

        // $ipaddress = '';
        // if (isset($_SERVER['HTTP_CLIENT_IP']))
        //     $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        // else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
        //     $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        // else if(isset($_SERVER['HTTP_X_FORWARDED']))
        //     $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        // else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
        //     $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        // else if(isset($_SERVER['HTTP_FORWARDED']))
        //     $ipaddress = $_SERVER['HTTP_FORWARDED'];
        // else if(isset($_SERVER['REMOTE_ADDR']))
        //     $ipaddress = $_SERVER['REMOTE_ADDR'];
        // else{
        //     $ipaddress = 'IP tidak dikenali';
        // }

        // echo $ipaddress;

        // ketiga

        // $ipaddress = '';
        // if (getenv('HTTP_CLIENT_IP'))
        //     $ipaddress = getenv('HTTP_CLIENT_IP');
        // else if(getenv('HTTP_X_FORWARDED_FOR'))
        //     $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        // else if(getenv('HTTP_X_FORWARDED'))
        //     $ipaddress = getenv('HTTP_X_FORWARDED');
        // else if(getenv('HTTP_FORWARDED_FOR'))
        //     $ipaddress = getenv('HTTP_FORWARDED_FOR');
        // else if(getenv('HTTP_FORWARDED'))
        // $ipaddress = getenv('HTTP_FORWARDED');
        // else if(getenv('REMOTE_ADDR'))
        //     $ipaddress = getenv('REMOTE_ADDR');
        // else{

        //     $ipaddress = 'IP tidak dikenali';
        // }
        // echo $ipaddress;

        if (isset($_SERVER)) {

            if (isset($_SERVER["HTTP_X_FORWARDED_FOR"]))
                return $_SERVER["HTTP_X_FORWARDED_FOR"];

            if (isset($_SERVER["HTTP_CLIENT_IP"]))
                return $_SERVER["HTTP_CLIENT_IP"];

            return $_SERVER["REMOTE_ADDR"];
        }

        if (getenv('HTTP_X_FORWARDED_FOR'))
            return getenv('HTTP_X_FORWARDED_FOR');

        if (getenv('HTTP_CLIENT_IP'))
            return getenv('HTTP_CLIENT_IP');

        // return getenv('REMOTE_ADDR');
    }

    public function download()
    {
        $id = $this->session->userdata('id');
        $table = $this->uri->segment('3');

        if ($table == md5('profile')) {
            $nama = 'Data_Afiliasi_Profile.csv';
            $tables = 'site.t_dbafiliasi_tabel_profile';
        }elseif ($table == md5('gudang')) {
            $nama = 'Data_Afiliasi_Gudang.csv';
            $tables = 'site.t_dbafiliasi_tabel_detail_gudang';
        }elseif ($table == md5('karyawan')) {
            $nama = 'Data_Afiliasi_Karyawan.csv';
            $tables = 'site.t_dbafiliasi_tabel_karyawan';
        }elseif ($table == md5('niaga')) {
            $nama = 'Data_Afiliasi_Niaga.csv';
            $tables = 'site.t_dbafiliasi_tabel_niaga';
        }elseif ($table == md5('non_niaga')) {
            $nama = 'Data_Afiliasi_Non_niaga.csv';
            $tables = 'site.t_dbafiliasi_tabel_non_niaga';
        }elseif ($table == md5('it_asset')) {
            $nama = 'Data_Afiliasi_IT_Asset.csv';
            $tables = 'site.t_dbafiliasi_tabel_it_asset';
        }elseif ($table == md5('asset')) {
            $nama = 'Data_Afiliasi_Asset.csv';
            $tables = 'site.t_dbafiliasi_tabel_asset';
        }

        $this->db->select('*');
        $this->db->where('created_by', $id);
        $proses =  $this->db->get("$tables");
        query_to_csv($proses,TRUE,"$nama");
    }

    public function export_csv()
    {
        $sql = "
            SELECT a.site_code, a.nama, a.status_afiliasi, a.alamat, a.propinsi, a.kota, a.kecamatan,
                a.kelurahan, a.kodepos, a.telp, a.status_properti, a.sewa_from, a.sewa_to, a.harga_sewa,a.bentuk_bangunan,
                b.luas_gudang, b.panjang_gudang,b.lebar_gudang, b.pallet_gudang, b.racking_gudang, b.luas_gudang_baik,
                b.panjang_gudang_baik, b.lebar_gudang_baik, b.pallet_gudang_baik, b.racking_gudang_baik, b.luas_gudang_retur,
                b.panjang_gudang_retur, b.lebar_gudang_retur, b.pallet_gudang_retur, b.racking_gudang_retur,
                b.luas_gudang_karantina, b.panjang_gudang_karantina, b.lebar_gudang_karantina,
                b.pallet_gudang_karantina, b.racking_gudang_karantina, b.luas_gudang_ac, b.panjang_gudang_ac,
                b.lebar_gudang_ac, b.pallet_gudang_ac, b.racking_gudang_ac, b.luas_loading_dock, b.panjang_loading_dock,
                b.lebar_loading_dock, b.pallet_gudang_loading, b.racking_gudang_loading, b.jumlah_pallet, b.jumlah_hand_pallet,
                b.jumlah_trolley, b.jumlah_sealer, b.jumlah_ac, b.jumlah_exhaust_fan, b.jumlah_kipas_angin,
                b.total_karyawan_pria_wanita, b.jumlah_pria, b.jumlah_wanita, b.total_karyawan_per_divisi, b.jumlah_karyawan_gudang,
                b.jumlah_karyawan_ekspedisi, b.total_karyawan_per_level, b.jumlah_karyawan_spv, b.jumlah_karyawan_staff,
                b.jumlah_karyawan_pelaksana, b.total_karyawan_per_jabatan, b.jumlah_karyawan_kalog, b.jumlah_karyawan_adm,
                b.jumlah_karyawan_picker, b.jumlah_karyawan_driver, b.luas_kantor_div_logistik, b.panjang_kantor_div_logistik,
                b.lebar_kantor_div_logistik, b.total_mobil_penumpang, b.jumlah_mobil_penumpang_sewa, b.jumlah_mobil_penumpang_milik_sendiri,
                b.total_mobil_pengiriman, b.jumlah_mobil_pengiriman_blind_van, b.jumlah_mobil_pengiriman_engkel,
                b.jumlah_mobil_pengiriman_double, b.total_blind_van, b.jumlah_blind_van_sewa, b.jumlah_blind_van_milik_sendiri,
                b.total_engkel, b.jumlah_engkel_sewa, b.jumlah_engkel_milik_sendiri, b.total_double, b.jumlah_double_sewa,
                b.jumlah_double_milik_sendiri, b.total_motor_pengiriman, b.jumlah_motor_pengiriman_sewa, b.jumlah_motor_pengiriman_milik_sendiri,
                b.total_saddle_bag, b.jumlah_saddle_bag_dipakai, b.jumlah_saddle_bag_cadangan, b.luas_kantor_total, b.panjang_kantor_total,
                b.lebar_kantor_total, b.luas_ruang_sales, b.panjang_ruang_sales, b.lebar_ruang_sales, b.total_sdm_sales, b.jumlah_spv_sales,
                b.jumlah_salesforce, b.luas_ruang_finance, b.panjang_ruang_finance, b.lebar_ruang_finance, b.total_sdm_finance, b.jumlah_adm_spv,
                b.jumlah_kasir, b.jumlah_fakturis, b.luas_ruang_logistik, b.panjang_ruang_logistik, b.lebar_ruang_logistik, b.total_sdm_logistik,
                b.jumlah_kalog, b.jumlah_adm_logistik, b.jumlah_adm_ekspedisi, b.luas_gudang_arsip, b.panjang_gudang_arsip, b.lebar_gudang_arsip,
                c.nama as nama_karyawan, c.jenis_kelamin, c.tempat, c.tanggal_lahir, c.tingkat_pendidikan, c.status_pernikahan, c.department,
                c.jabatan, c.status_karyawan, c.tanggal_masuk_kerja,
                d.jenis_kendaraan as jenis_kendaraan_niaga, d.kepemilikan as kepemilikan_niaga, d.bahan_bakar as bahan_bakar_niaga,
                d.no_polisi as no_polisi_niaga, d.tahun_pembuatan as tahun_pembuatan_niaga, d.tanggal_pajak_berakhir as tanggal_pajak_berakhir_niaga,
                d.tanggal_pajak_kir as tanggal_pajak_kir_niaga, d.vendor as vendor_niaga, d.tanggal_awal_sewa as tanggal_awal_sewa_niaga,
                d.tanggal_akhir_sewa as tanggal_akhir_sewa_niaga,
                e.jenis_kendaraan as jenis_kendaraan_non_niaga, e.kepemilikan as kepemilikan_non_niaga, e.nama_pemakai as nama_pemakai_non_niaga,
                e.jabatan as jabatan_non_niaga, e.bahan_bakar as bahan_bakar_non_niaga, e.no_polisi as no_polisi_non_niaga,
                e.tahun_pembuatan as tahun_pembuatan_non_niaga, e.tanggal_pajak_berakhir as tanggal_pajak_berakhir_non_niaga,
                e.vendor as vendor_non_niaga, e.tanggal_awal_sewa as tanggal_awal_non_sewa, e.tanggal_akhir_sewa as tanggal_akhir_sewa_non_niaga,
                f.nama_asset as nama_asset_it_asset, f.merk as merk_it_asset, f.type as type_it_asset, f.tanggal_pembelian as tanggal_pembelian_it_asset,
                f.operating_system as operating_system_it_asset, f.processor as processor_it_asset, f.ram as ram_it_asset, f.storage as storage_it_asset,
                f.kapasitas_baterai as kapasitas_baterai_it_asset, f.divisi_pemakai as divisi_pemakai_it_asset, f.jabatan_pemakai as jabatan_pemakai_it_asset,
                g.jenis_asset, g.merk as merk_asset, g.type as type_asset, g.tanggal_pembelian as tanggal_pembelian_asset,
                g.divisi_pemakai as divisi_pemakai_asset, g.jabatan_pemakai as jabatan_pemakai_asset
            FROM
            (
                SELECT *
                FROM site.t_dbafiliasi_tabel_profile a
            ) a
            LEFT JOIN
            (
                SELECT *
                FROM site.t_dbafiliasi_tabel_detail_gudang a
            )b ON a.site_code = b.site_code
            LEFT JOIN
            (
                SELECT *
                FROM site.t_dbafiliasi_tabel_karyawan a
            )c ON a.site_code = c.site_code
            LEFT JOIN
            (
                SELECT *
                FROM site.t_dbafiliasi_tabel_niaga a
            )d ON a.site_code = d.site_code
            LEFT JOIN
            (
                SELECT *
                FROM site.t_dbafiliasi_tabel_non_niaga a
            )e ON a.site_code = e.site_code
            LEFT JOIN
            (
                SELECT *
                FROM site.t_dbafiliasi_tabel_it_asset a
            )f ON a.site_code = f.site_code
            LEFT JOIN
            (
                SELECT *
                FROM site.t_dbafiliasi_tabel_asset a
            )g ON a.site_code = g.site_code
            ";

        $proses =  $this->db->query($sql);
        query_to_csv($proses,TRUE,"database_afiliasi.csv");
    }

    public function download_pdf(){

        $this->load->library('mypdf');
        $table = $this->uri->segment('3');
        $id = $this->uri->segment('4');

        $this->db->select('site_code');
        $this->db->where('id', $id);
        $profile = $this->db->get('site.t_dbafiliasi_tabel_profile',1)->row();
        // var_dump($profile->site_code);die;

        if ($table == md5('profile')) {
            $data = [
                'profile' => $this->ModelDatabaseAfiliasi->pdf_profile($id)->row(),
                'omzet_herbal' => $this->ModelDatabaseAfiliasi->omzet_herbal_pdf_profile($profile->site_code)->row(),
                'omzet_candy' => $this->ModelDatabaseAfiliasi->omzet_candy_pdf_profile($profile->site_code)->row(),
                'omzet_marguna' => $this->ModelDatabaseAfiliasi->omzet_marguna_pdf_profile($profile->site_code)->row(),
                'omzet_jaya' => $this->ModelDatabaseAfiliasi->omzet_jaya_pdf_profile($profile->site_code)->row(),
                'omzet_us' => $this->ModelDatabaseAfiliasi->omzet_us_pdf_profile($profile->site_code)->row(),
                'omzet_intrafood' => $this->ModelDatabaseAfiliasi->omzet_intrafood_pdf_profile($profile->site_code)->row(),
                'omzet_strive' => $this->ModelDatabaseAfiliasi->omzet_strive_pdf_profile($profile->site_code)->row(),
                'omzet_hni' => $this->ModelDatabaseAfiliasi->omzet_hni_pdf_profile($profile->site_code)->row(),
                'omzet_mdj' => $this->ModelDatabaseAfiliasi->omzet_mdj_pdf_profile($profile->site_code)->row(),
                'omzet_total' => $this->ModelDatabaseAfiliasi->omzet_total_pdf_profile($profile->site_code)->row(),
            ];
            $generate_pdf = $this->mypdf->generate('database_afiliasi/template_pdf_profile',$data,'','A4','portrait');
        }elseif ($table == md5('gudang')) {
            $data = $this->ModelDatabaseAfiliasi->pdf_detail_gudang($id)->row();
            $generate_pdf = $this->mypdf->generate('database_afiliasi/template_pdf_gudang',$data,'','A4','portrait');
        }elseif ($table == md5('karyawan')) {
            $data = $this->ModelDatabaseAfiliasi->get_karyawan('', $id)->row();
            $generate_pdf = $this->mypdf->generate('database_afiliasi/template_pdf_karyawan',$data,'','A4','portrait');
        }elseif ($table == md5('niaga')) {
            $data = $this->ModelDatabaseAfiliasi->get_niaga('', $id)->row();
            $generate_pdf = $this->mypdf->generate('database_afiliasi/template_pdf_niaga',$data,'','A4','portrait');
        }elseif ($table == md5('non_niaga')) {
            $data = $this->ModelDatabaseAfiliasi->get_non_niaga('', $id)->row();
            $generate_pdf = $this->mypdf->generate('database_afiliasi/template_pdf_non_niaga',$data,'','A4','portrait');
        }elseif ($table == md5('it_asset')) {
            $data = $this->ModelDatabaseAfiliasi->get_it_asset('', $id)->row();
            $generate_pdf = $this->mypdf->generate('database_afiliasi/template_pdf_it_asset',$data,'','A4','portrait');
        }elseif ($table == md5('asset')) {
            $data = $this->ModelDatabaseAfiliasi->get_asset('', $id)->row();
            $generate_pdf = $this->mypdf->generate('database_afiliasi/template_pdf_asset',$data,'','A4','portrait');
        }
    }

    public function import_proses()
    {
        $id = $this->session->userdata('id');
        $date = $this->model_outlet_transaksi->timezone();
        $this->load->library('upload'); // Load librari upload
        $config['upload_path'] = './assets/file/database_afiliasi';
        $config['allowed_types'] = '*';
        $config['max_size']  = '*';
        $config['overwrite'] = true;
        $this->upload->initialize($config);

        // Load konfigurasi uploadnya
        if($this->upload->do_upload('file'))
        {
            $upload_data = $this->upload->data();
            $filename = $upload_data['orig_name'];
            $this->load->library('excel');
            $object = PHPExcel_IOFactory::load("assets/file/database_afiliasi/$filename");

            // ------------------------------------ Profil --------------------------------------
            $object->setActiveSheetIndex(0);
            $site_code = $object->getActiveSheet()->getCellByColumnAndRow(3, 5)->getValue();
            $profile = [
                'site_code' => $site_code,
                'nama' => $object->getActiveSheet()->getCellByColumnAndRow(3, 6)->getValue(),
                'status_afiliasi' => $object->getActiveSheet()->getCellByColumnAndRow(3, 7)->getValue(),
                'alamat' => $object->getActiveSheet()->getCellByColumnAndRow(4, 8)->getValue(),
                'kota' => $object->getActiveSheet()->getCellByColumnAndRow(4, 9)->getValue(),
                'propinsi' => $object->getActiveSheet()->getCellByColumnAndRow(4, 10)->getValue(),
                'kecamatan' => $object->getActiveSheet()->getCellByColumnAndRow(4, 11)->getValue(),
                'kodepos' => $object->getActiveSheet()->getCellByColumnAndRow(4, 12)->getValue(),
                'kelurahan' => $object->getActiveSheet()->getCellByColumnAndRow(4, 13)->getValue(),
                'telp' => $object->getActiveSheet()->getCellByColumnAndRow(4, 14)->getValue(),
                'status_properti' => $object->getActiveSheet()->getCellByColumnAndRow(3, 15)->getValue(),
                'sewa_from' => date('Y-m-d',strtotime($object->getActiveSheet()->getCellByColumnAndRow(3, 16)->getValue())),
                'sewa_to' => date('Y-m-d',strtotime($object->getActiveSheet()->getCellByColumnAndRow(5, 16)->getValue())),
                'harga_sewa' => $object->getActiveSheet()->getCellByColumnAndRow(4, 17)->getValue(),
                'bentuk_bangunan' => $object->getActiveSheet()->getCellByColumnAndRow(3, 18)->getValue(),
                'created_by' => $id,
                'created_at' => $date
            ];
            // var_dump($profile);die;
            $input_profile = array_filter($profile);
            if (count($input_profile) == count($profile)) {
                // echo 'a';
                $this->ModelDatabaseAfiliasi->tambah('site.t_temp_dbafiliasi_tabel_profile', $profile);
                // alert("message");
            }else{
                $message = 'Data Profile Ditolak, \nSilahkan Cek Kembali File Anda atau Hubungi Tim IT';
                echo "<script>alert('$message');
                window.location=history.go(-1);</script>";
            }

            // -------------------------------------------------------------------------------------

            // ---------------------------------- detail gudang ------------------------------------

            $object->setActiveSheetIndex(1);
            $detail_gudang = [
                'site_code' => $site_code,
                'luas_gudang' => $object->getActiveSheet()->getCellByColumnAndRow(4, 5)->getValue() * $object->getActiveSheet()->getCellByColumnAndRow(7, 5)->getValue(),
                'panjang_gudang' => $object->getActiveSheet()->getCellByColumnAndRow(4, 5)->getValue(),
                'lebar_gudang' => $object->getActiveSheet()->getCellByColumnAndRow(7, 5)->getValue(),
                'pallet_gudang' => $object->getActiveSheet()->getCellByColumnAndRow(4, 6)->getValue(),
                'racking_gudang' => $object->getActiveSheet()->getCellByColumnAndRow(7, 6)->getValue(),
                'luas_gudang_baik' => $object->getActiveSheet()->getCellByColumnAndRow(4, 7)->getValue() * $object->getActiveSheet()->getCellByColumnAndRow(7, 7)->getValue(),
                'panjang_gudang_baik' => $object->getActiveSheet()->getCellByColumnAndRow(4, 7)->getValue(),
                'lebar_gudang_baik' => $object->getActiveSheet()->getCellByColumnAndRow(7, 7)->getValue(),
                'pallet_gudang_baik' => $object->getActiveSheet()->getCellByColumnAndRow(4, 8)->getValue(),
                'racking_gudang_baik' => $object->getActiveSheet()->getCellByColumnAndRow(7, 8)->getValue(),
                'luas_gudang_retur' => $object->getActiveSheet()->getCellByColumnAndRow(4, 9)->getValue() * $object->getActiveSheet()->getCellByColumnAndRow(7, 9)->getValue(),
                'panjang_gudang_retur' => $object->getActiveSheet()->getCellByColumnAndRow(4, 9)->getValue(),
                'lebar_gudang_retur' => $object->getActiveSheet()->getCellByColumnAndRow(7, 9)->getValue(),
                'pallet_gudang_retur' => $object->getActiveSheet()->getCellByColumnAndRow(4, 10)->getValue(),
                'racking_gudang_retur' => $object->getActiveSheet()->getCellByColumnAndRow(7, 10)->getValue(),
                'luas_gudang_karantina' => $object->getActiveSheet()->getCellByColumnAndRow(4, 11)->getValue() * $object->getActiveSheet()->getCellByColumnAndRow(7, 11)->getValue(),
                'panjang_gudang_karantina' => $object->getActiveSheet()->getCellByColumnAndRow(4, 11)->getValue(),
                'lebar_gudang_karantina' => $object->getActiveSheet()->getCellByColumnAndRow(7, 11)->getValue(),
                'pallet_gudang_karantina' => $object->getActiveSheet()->getCellByColumnAndRow(4, 12)->getValue(),
                'racking_gudang_karantina' => $object->getActiveSheet()->getCellByColumnAndRow(7, 12)->getValue(),
                'luas_gudang_ac' => $object->getActiveSheet()->getCellByColumnAndRow(4, 13)->getValue() * $object->getActiveSheet()->getCellByColumnAndRow(7, 13)->getValue(),
                'panjang_gudang_ac' => $object->getActiveSheet()->getCellByColumnAndRow(4, 13)->getValue(),
                'lebar_gudang_ac' => $object->getActiveSheet()->getCellByColumnAndRow(7, 13)->getValue(),
                'pallet_gudang_ac' => $object->getActiveSheet()->getCellByColumnAndRow(4, 14)->getValue(),
                'racking_gudang_ac' => $object->getActiveSheet()->getCellByColumnAndRow(7, 14)->getValue(),
                'luas_loading_dock' => $object->getActiveSheet()->getCellByColumnAndRow(4, 15)->getValue() * $object->getActiveSheet()->getCellByColumnAndRow(7, 15)->getValue(),
                'panjang_loading_dock' => $object->getActiveSheet()->getCellByColumnAndRow(4, 15)->getValue(),
                'lebar_loading_dock' => $object->getActiveSheet()->getCellByColumnAndRow(7, 15)->getValue(),
                'pallet_gudang_loading' => $object->getActiveSheet()->getCellByColumnAndRow(4, 16)->getValue(),
                'racking_gudang_loading' => $object->getActiveSheet()->getCellByColumnAndRow(7, 16)->getValue(),
                'jumlah_pallet' => $object->getActiveSheet()->getCellByColumnAndRow(4, 17)->getValue(),
                'jumlah_hand_pallet' => $object->getActiveSheet()->getCellByColumnAndRow(7, 17)->getValue(),
                'jumlah_trolley' => $object->getActiveSheet()->getCellByColumnAndRow(9, 17)->getValue(),
                'jumlah_sealer' => $object->getActiveSheet()->getCellByColumnAndRow(11, 17)->getValue(),
                'jumlah_ac' => $object->getActiveSheet()->getCellByColumnAndRow(4, 18)->getValue(),
                'jumlah_exhaust_fan' => $object->getActiveSheet()->getCellByColumnAndRow(7, 18)->getValue(),
                'jumlah_kipas_angin' => $object->getActiveSheet()->getCellByColumnAndRow(9, 18)->getValue(),
                'luas_kantor_div_logistik' => $object->getActiveSheet()->getCellByColumnAndRow(4, 19)->getValue() * $object->getActiveSheet()->getCellByColumnAndRow(7, 19)->getValue(),
                'panjang_kantor_div_logistik' => $object->getActiveSheet()->getCellByColumnAndRow(4, 19)->getValue(),
                'lebar_kantor_div_logistik' => $object->getActiveSheet()->getCellByColumnAndRow(7, 19)->getValue(),
                'total_mobil_penumpang' => $object->getActiveSheet()->getCellByColumnAndRow(4, 20)->getValue() + $object->getActiveSheet()->getCellByColumnAndRow(7, 20)->getValue(),
                'jumlah_mobil_penumpang_sewa' => $object->getActiveSheet()->getCellByColumnAndRow(4, 20)->getValue(),
                'jumlah_mobil_penumpang_milik_sendiri' => $object->getActiveSheet()->getCellByColumnAndRow(7, 20)->getValue(),
                'total_mobil_pengiriman' => $object->getActiveSheet()->getCellByColumnAndRow(4, 21)->getValue() + $object->getActiveSheet()->getCellByColumnAndRow(7, 21)->getValue() + $object->getActiveSheet()->getCellByColumnAndRow(9, 21)->getValue(),
                'jumlah_mobil_pengiriman_blind_van' => $object->getActiveSheet()->getCellByColumnAndRow(4, 21)->getValue(),
                'jumlah_mobil_pengiriman_engkel' => $object->getActiveSheet()->getCellByColumnAndRow(7, 21)->getValue(),
                'jumlah_mobil_pengiriman_double' => $object->getActiveSheet()->getCellByColumnAndRow(9, 21)->getValue(),
                'total_blind_van' => $object->getActiveSheet()->getCellByColumnAndRow(4, 22)->getValue() + $object->getActiveSheet()->getCellByColumnAndRow(7, 22)->getValue(),
                'jumlah_blind_van_sewa' => $object->getActiveSheet()->getCellByColumnAndRow(4, 22)->getValue(),
                'jumlah_blind_van_milik_sendiri' => $object->getActiveSheet()->getCellByColumnAndRow(7, 22)->getValue(),
                'total_engkel' => $object->getActiveSheet()->getCellByColumnAndRow(4, 23)->getValue() + $object->getActiveSheet()->getCellByColumnAndRow(7, 23)->getValue(),
                'jumlah_engkel_sewa' => $object->getActiveSheet()->getCellByColumnAndRow(4, 23)->getValue(),
                'jumlah_engkel_milik_sendiri' => $object->getActiveSheet()->getCellByColumnAndRow(7, 23)->getValue(),
                'total_double' => $object->getActiveSheet()->getCellByColumnAndRow(4, 24)->getValue() + $object->getActiveSheet()->getCellByColumnAndRow(7, 24)->getValue(),
                'jumlah_double_sewa' => $object->getActiveSheet()->getCellByColumnAndRow(4, 24)->getValue(),
                'jumlah_double_milik_sendiri' => $object->getActiveSheet()->getCellByColumnAndRow(7, 24)->getValue(),
                'total_motor_pengiriman' => $object->getActiveSheet()->getCellByColumnAndRow(4, 25)->getValue() + $object->getActiveSheet()->getCellByColumnAndRow(7, 25)->getValue(),
                'jumlah_motor_pengiriman_sewa' => $object->getActiveSheet()->getCellByColumnAndRow(4, 25)->getValue(),
                'jumlah_motor_pengiriman_milik_sendiri' => $object->getActiveSheet()->getCellByColumnAndRow(7, 25)->getValue(),
                'total_saddle_bag' => $object->getActiveSheet()->getCellByColumnAndRow(4, 26)->getValue() + $object->getActiveSheet()->getCellByColumnAndRow(7, 26)->getValue(),
                'jumlah_saddle_bag_dipakai' => $object->getActiveSheet()->getCellByColumnAndRow(4, 26)->getValue(),
                'jumlah_saddle_bag_cadangan' => $object->getActiveSheet()->getCellByColumnAndRow(7, 26)->getValue(),
                'luas_kantor_total' => $object->getActiveSheet()->getCellByColumnAndRow(4, 29)->getValue() * $object->getActiveSheet()->getCellByColumnAndRow(7, 29)->getValue(),
                'panjang_kantor_total' => $object->getActiveSheet()->getCellByColumnAndRow(4, 29)->getValue(),
                'lebar_kantor_total' => $object->getActiveSheet()->getCellByColumnAndRow(7, 29)->getValue(),
                'luas_ruang_sales' => $object->getActiveSheet()->getCellByColumnAndRow(4, 30)->getValue() * $object->getActiveSheet()->getCellByColumnAndRow(7, 30)->getValue(),
                'panjang_ruang_sales' => $object->getActiveSheet()->getCellByColumnAndRow(4, 30)->getValue(),
                'lebar_ruang_sales' => $object->getActiveSheet()->getCellByColumnAndRow(7, 30)->getValue(),
                'luas_ruang_finance' => $object->getActiveSheet()->getCellByColumnAndRow(4, 31)->getValue() * $object->getActiveSheet()->getCellByColumnAndRow(7, 31)->getValue(),
                'panjang_ruang_finance' => $object->getActiveSheet()->getCellByColumnAndRow(4, 31)->getValue(),
                'lebar_ruang_finance' => $object->getActiveSheet()->getCellByColumnAndRow(7, 31)->getValue(),
                'luas_ruang_logistik' => $object->getActiveSheet()->getCellByColumnAndRow(4, 32)->getValue() * $object->getActiveSheet()->getCellByColumnAndRow(7, 32)->getValue(),
                'panjang_ruang_logistik' => $object->getActiveSheet()->getCellByColumnAndRow(4, 32)->getValue(),
                'lebar_ruang_logistik' => $object->getActiveSheet()->getCellByColumnAndRow(7, 32)->getValue(),
                'luas_gudang_arsip' => $object->getActiveSheet()->getCellByColumnAndRow(4, 33)->getValue() * $object->getActiveSheet()->getCellByColumnAndRow(7, 31)->getValue(),
                'panjang_gudang_arsip' => $object->getActiveSheet()->getCellByColumnAndRow(4, 33)->getValue(),
                'lebar_gudang_arsip' => $object->getActiveSheet()->getCellByColumnAndRow(7, 33)->getValue(),
                'created_by' => $id,
                'created_at' => $date
            ];
            // var_dump($detail_gudang);die;
            $input_detail_gudang = array_filter($detail_gudang);
            if (count($input_detail_gudang) == count($detail_gudang)) {
                // echo 'a';
                $this->ModelDatabaseAfiliasi->tambah('site.t_temp_dbafiliasi_tabel_detail_gudang', $detail_gudang);
                // alert("message");
            }else{
                $message = 'Data Detail Gudang Ditolak, \nSilahkan Cek Kembali File Anda atau Hubungi Tim IT';
                echo "<script>alert('$message');
                window.location=history.go(-1);</script>";
            }

            // -------------------------------------------------------------------------------------
            // ------------------------------------ karyawan ---------------------------------------

            $highestRow = $object->setActiveSheetIndex(2)->getHighestRow();
            $highestColumn = $object->setActiveSheetIndex(2)->getHighestColumn();
            for($row=5; $row<=$highestRow; $row++)
            {
                $site_code = $site_code;
                $nama = $object->getActiveSheet()->getCellByColumnAndRow(0, $row)->getValue();
                $jenis_kelamin = $object->getActiveSheet()->getCellByColumnAndRow(1, $row)->getValue();
                $tempat = $object->getActiveSheet()->getCellByColumnAndRow(2, $row)->getValue();
                $tanggal_lahir = date('Y-m-d',strtotime($object->getActiveSheet()->getCellByColumnAndRow(3, $row)->getValue()));
                $tingkat_pendidikan = $object->getActiveSheet()->getCellByColumnAndRow(4, $row)->getValue();
                $status_pernikahan = $object->getActiveSheet()->getCellByColumnAndRow(5, $row)->getValue();
                $department = $object->getActiveSheet()->getCellByColumnAndRow(6, $row)->getValue();
                $jabatan = $object->getActiveSheet()->getCellByColumnAndRow(7, $row)->getValue();
                $status_karyawan = $object->getActiveSheet()->getCellByColumnAndRow(8, $row)->getValue();
                $tanggal_masuk_kerja = date('Y-m-d',strtotime($object->getActiveSheet()->getCellByColumnAndRow(9, $row)->getValue()));

                $karyawan=[
                    'site_code' => $site_code,
                    'nama' => $nama,
                    'jenis_kelamin' => $jenis_kelamin,
                    'tempat' => $tempat,
                    'tanggal_lahir' => $tanggal_lahir,
                    'tingkat_pendidikan' => $tingkat_pendidikan,
                    'status_pernikahan' => $status_pernikahan,
                    'department' => $department,
                    'jabatan' => $jabatan,
                    'status_karyawan' => $status_karyawan,
                    'tanggal_masuk_kerja' => $tanggal_masuk_kerja,
                    'created_by' => $id,
                    'created_at' => $date
                ];
                // var_dump($highestRow);die;
                $input_karyawan = array_filter($karyawan);
                if (count($input_karyawan) == count($karyawan)) {
                    // echo 'a';
                    $this->ModelDatabaseAfiliasi->tambah('site.t_temp_dbafiliasi_tabel_karyawan', $karyawan);
                    // alert("message");
                }else{
                    $message = 'Data Karyawan Ditolak, \nSilahkan Cek Kembali File Anda atau Hubungi Tim IT';
                    echo "<script>alert('$message');
                    window.location=history.go(-1);</script>";
                }
            }

            // -------------------------------------------------------------------------------------
            // ------------------------------------- niaga -----------------------------------------

            $highestRow = $object->setActiveSheetIndex(3)->getHighestRow();
            $highestColumn = $object->setActiveSheetIndex(3)->getHighestColumn();
            for($row=5; $row<=$highestRow; $row++)
            {
                $site_code = $site_code;
                $jenis_kendaraan = $object->getActiveSheet()->getCellByColumnAndRow(0, $row)->getValue();
                $kepemilikan = $object->getActiveSheet()->getCellByColumnAndRow(1, $row)->getValue();
                $bahan_bakar = $object->getActiveSheet()->getCellByColumnAndRow(2, $row)->getValue();
                $no_polisi = $object->getActiveSheet()->getCellByColumnAndRow(3, $row)->getValue();
                $tahun_pembuatan = $object->getActiveSheet()->getCellByColumnAndRow(4, $row)->getValue();
                $tanggal_pajak_berakhir = date('Y-m-d',strtotime($object->getActiveSheet()->getCellByColumnAndRow(5, $row)->getValue()));
                $tanggal_pajak_kir = date('Y-m-d',strtotime($object->getActiveSheet()->getCellByColumnAndRow(6, $row)->getValue()));
                $vendor = $object->getActiveSheet()->getCellByColumnAndRow(7, $row)->getValue();
                $tanggal_awal_sewa = date('Y-m-d',strtotime($object->getActiveSheet()->getCellByColumnAndRow(8, $row)->getValue()));
                $tanggal_akhir_sewa = date('Y-m-d',strtotime($object->getActiveSheet()->getCellByColumnAndRow(9, $row)->getValue()));

                $niaga=[
                    'site_code' => $site_code,
                    'jenis_kendaraan' => $jenis_kendaraan,
                    'kepemilikan' => $kepemilikan,
                    'bahan_bakar' => $bahan_bakar,
                    'no_polisi' => $no_polisi,
                    'tahun_pembuatan' => $tahun_pembuatan,
                    'tanggal_pajak_berakhir' => $tanggal_pajak_berakhir,
                    'tanggal_pajak_kir' => $tanggal_pajak_kir,
                    'vendor' => $vendor,
                    'tanggal_awal_sewa' => $tanggal_awal_sewa,
                    'tanggal_akhir_sewa' => $tanggal_akhir_sewa,
                    'created_by' => $id,
                    'created_at' => $date
                ];
                // var_dump($niaga);die;
                $input_niaga = array_filter($niaga);
                if (count($input_niaga) == count($niaga)) {
                    // echo 'a';
                    $this->ModelDatabaseAfiliasi->tambah('site.t_temp_dbafiliasi_tabel_niaga', $niaga);
                    // alert("message");
                }else{
                    $message = 'Data Karyawan Ditolak, \nSilahkan Cek Kembali File Anda atau Hubungi Tim IT';
                    echo "<script>alert('$message');
                    window.location=history.go(-1);</script>";
                }
            }

            // -------------------------------------------------------------------------------------
            // ----------------------------------- non niaga ---------------------------------------

            $highestRow = $object->setActiveSheetIndex(4)->getHighestRow();
            $highestColumn = $object->setActiveSheetIndex(4)->getHighestColumn();
            for($row=5; $row<=$highestRow; $row++)
            {
                $site_code = $site_code;
                $jenis_kendaraan = $object->getActiveSheet()->getCellByColumnAndRow(0, $row)->getValue();
                $kepemilikan = $object->getActiveSheet()->getCellByColumnAndRow(1, $row)->getValue();
                $nama_pemakai = $object->getActiveSheet()->getCellByColumnAndRow(2, $row)->getValue();
                $jabatan = $object->getActiveSheet()->getCellByColumnAndRow(3, $row)->getValue();
                $bahan_bakar = $object->getActiveSheet()->getCellByColumnAndRow(4, $row)->getValue();
                $no_polisi = $object->getActiveSheet()->getCellByColumnAndRow(5, $row)->getValue();
                $tahun_pembuatan = $object->getActiveSheet()->getCellByColumnAndRow(6, $row)->getValue();
                $tanggal_pajak_berakhir = date('Y-m-d',strtotime($object->getActiveSheet()->getCellByColumnAndRow(7, $row)->getValue()));
                $vendor = $object->getActiveSheet()->getCellByColumnAndRow(8, $row)->getValue();
                $tanggal_awal_sewa = date('Y-m-d',strtotime($object->getActiveSheet()->getCellByColumnAndRow(9, $row)->getValue()));
                $tanggal_akhir_sewa = date('Y-m-d',strtotime($object->getActiveSheet()->getCellByColumnAndRow(10, $row)->getValue()));

                $non_niaga=[
                    'site_code' => $site_code,
                    'jenis_kendaraan' => $jenis_kendaraan,
                    'kepemilikan' => $kepemilikan,
                    'nama_pemakai' => $nama_pemakai,
                    'jabatan' => $jabatan,
                    'bahan_bakar' => $bahan_bakar,
                    'no_polisi' => $no_polisi,
                    'tahun_pembuatan' => $tahun_pembuatan,
                    'tanggal_pajak_berakhir' => $tanggal_pajak_berakhir,
                    'vendor' => $vendor,
                    'tanggal_awal_sewa' => $tanggal_awal_sewa,
                    'tanggal_akhir_sewa' => $tanggal_akhir_sewa,
                    'created_by' => $id,
                    'created_at' => $date
                ];
                // var_dump($highestRow);die;
                $input_non_niaga = array_filter($non_niaga);
                if (count($input_non_niaga) == count($non_niaga)) {
                    // echo 'a';
                    $this->ModelDatabaseAfiliasi->tambah('site.t_temp_dbafiliasi_tabel_non_niaga', $non_niaga);
                    // alert("message");
                }else{
                    $message = 'Data Karyawan Ditolak, \nSilahkan Cek Kembali File Anda atau Hubungi Tim IT';
                    echo "<script>alert('$message');
                    window.location=history.go(-1);</script>";
                }
            }

            // -------------------------------------------------------------------------------------
            // ----------------------------------- it asset  ---------------------------------------
            $highestRow = $object->setActiveSheetIndex(5)->getHighestRow();
            $highestColumn = $object->setActiveSheetIndex(5)->getHighestColumn();
            for($row=5; $row<=$highestRow; $row++)
            {
                $site_code = $site_code;
                $nama_asset = $object->getActiveSheet()->getCellByColumnAndRow(0, $row)->getValue();
                $merk = $object->getActiveSheet()->getCellByColumnAndRow(1, $row)->getValue();
                $type = $object->getActiveSheet()->getCellByColumnAndRow(2, $row)->getValue();
                $tanggal_pembelian = date('Y-m-d',strtotime($object->getActiveSheet()->getCellByColumnAndRow(3, $row)->getValue()));
                $operating_system = $object->getActiveSheet()->getCellByColumnAndRow(4, $row)->getValue();
                $processor = $object->getActiveSheet()->getCellByColumnAndRow(5, $row)->getValue();
                $ram = $object->getActiveSheet()->getCellByColumnAndRow(6, $row)->getValue();
                $storage = $object->getActiveSheet()->getCellByColumnAndRow(7, $row)->getValue();
                $kapasitas_baterai = $object->getActiveSheet()->getCellByColumnAndRow(8, $row)->getValue();
                $divisi_pemakai = $object->getActiveSheet()->getCellByColumnAndRow(9, $row)->getValue();
                $jabatan_pemakai = $object->getActiveSheet()->getCellByColumnAndRow(10, $row)->getValue();

                $it_asset = [
                    'site_code' => $site_code,
                    'nama_asset' => $nama_asset,
                    'merk' => $merk,
                    'type' => $type,
                    'tanggal_pembelian' => $tanggal_pembelian,
                    'operating_system' => $operating_system,
                    'processor' => $processor,
                    'ram' => $ram,
                    'storage' => $storage,
                    'kapasitas_baterai' => $kapasitas_baterai,
                    'divisi_pemakai' => $divisi_pemakai,
                    'jabatan_pemakai' => $jabatan_pemakai,
                    'created_by' => $id,
                    'created_at' => $date
                ];
                $input_it_asset = array_filter($it_asset);
                if (count($input_it_asset) == count($it_asset)) {
                    // echo 'a';
                    $this->ModelDatabaseAfiliasi->tambah('site.t_temp_dbafiliasi_tabel_it_asset', $it_asset);
                    // alert("message");
                }else{
                    $message = 'Data IT Asset Ditolak, \nSilahkan Cek Kembali File Anda atau Hubungi Tim IT';
                    echo "<script>alert('$message');
                    window.location=history.go(-1);</script>";
                }
            // var_dump($it_asset);die;
            }
            // -------------------------------------------------------------------------------------
            // ----------------------------------- asset  ---------------------------------------
            $highestRow = $object->setActiveSheetIndex(6)->getHighestRow();
            $highestColumn = $object->setActiveSheetIndex(6)->getHighestColumn();
            for($row=5; $row<=$highestRow; $row++)
            {
                $site_code = $site_code;
                $jenis_asset = $object->getActiveSheet()->getCellByColumnAndRow(0, $row)->getValue();
                $merk = $object->getActiveSheet()->getCellByColumnAndRow(1, $row)->getValue();
                $type = $object->getActiveSheet()->getCellByColumnAndRow(2, $row)->getValue();
                $tanggal_pembelian = date('Y-m-d',strtotime($object->getActiveSheet()->getCellByColumnAndRow(3, $row)->getValue()));
                $divisi_pemakai = $object->getActiveSheet()->getCellByColumnAndRow(4, $row)->getValue();
                $jabatan_pemakai = $object->getActiveSheet()->getCellByColumnAndRow(5, $row)->getValue();

                $asset = [
                    'site_code' => $site_code,
                    'jenis_asset' => $jenis_asset,
                    'merk' => $merk,
                    'type' => $type,
                    'tanggal_pembelian' => $tanggal_pembelian,
                    'divisi_pemakai' => $divisi_pemakai,
                    'jabatan_pemakai' => $jabatan_pemakai,
                    'created_by' => $id,
                    'created_at' => $date
                ];
                $input_asset = array_filter($asset);
                if (count($input_asset) == count($asset)) {
                    // echo 'a';
                    $this->ModelDatabaseAfiliasi->tambah('site.t_temp_dbafiliasi_tabel_asset', $asset);
                    // alert("message");
                }else {
                    $message = 'Data IT Asset Ditolak, \nSilahkan Cek Kembali File Anda atau Hubungi Tim IT';
                    echo "<script>alert('$message');
                    window.location=history.go(-1);</script>";
                }
            }
            $this->preview($date);
            // -------------------------------------------------------------------------------------

        }else{
            $return = array('result' => 'failed', 'file' => '', 'error' => $this->upload->display_errors());
            redirect('database_afiliasi');
        }
    }

    public function preview($date)
    {
        if ($date == '') {
            redirect('database_afiliasi');
        }
        $data = [
            'url' => 'database_afiliasi/preview_simpan',
            'title' => 'Preview',
            'get_label' => $this->M_menu->get_label(),
            'get_preview_profile' => $this->ModelDatabaseAfiliasi->get_preview_profile($this->session->userdata('id'),$date)->row(),
            'get_preview_detail' => $this->ModelDatabaseAfiliasi->get_preview_detail_gudang($this->session->userdata('id'),$date)->row(),
            'get_preview_karyawan' => $this->ModelDatabaseAfiliasi->get_preview_karyawan($this->session->userdata('id'),$date)->result(),
            'get_preview_niaga' => $this->ModelDatabaseAfiliasi->get_preview_niaga($this->session->userdata('id'),$date)->result(),
            'get_preview_non_niaga' => $this->ModelDatabaseAfiliasi->get_preview_non_niaga($this->session->userdata('id'),$date)->result(),
            'get_preview_it_asset' => $this->ModelDatabaseAfiliasi->get_preview_it_asset($this->session->userdata('id'),$date)->result(),
            'get_preview_asset' => $this->ModelDatabaseAfiliasi->get_preview_asset($this->session->userdata('id'),$date)->result()
        ];

        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar',$data);
        $this->load->view('template_claim/top_content',$data);
        $this->load->view('database_afiliasi/preview',$data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
    }

    public function preview_simpan()
    {
        if (!is_dir('./assets/file/database_afiliasi/')) {
            @mkdir('./assets/file/database_afiliasi/', 0777);
        }

        $this->load->library('upload'); // Load librari upload
        $config['upload_path'] = './assets/file/database_afiliasi/';
        $config['allowed_types'] = 'jpg|png|jpeg|';
        $config['max_size']  = '*';
        $config['overwrite'] = false;
        $this->upload->initialize($config);

        // Load konfigurasi uploadnya
        if($this->upload->do_upload('foto_tampak_depan'))
        {
            $upload_data = $this->upload->data();
            $foto_tampak_depan = $upload_data['file_name'];
        }else{
            $upload_data = '';
            $foto_tampak_depan = '';
        }

        if($this->upload->do_upload('foto_gudang'))
        {
            $upload_data = $this->upload->data();
            $foto_gudang = $upload_data['file_name'];
        }else{
            $upload_data = '';
            $foto_gudang = '';
        }

        if($this->upload->do_upload('foto_kantor'))
        {
            $upload_data = $this->upload->data();
            $foto_kantor = $upload_data['file_name'];
        }else{
            $upload_data = '';
            $foto_kantor = '';
        }

        if($this->upload->do_upload('foto_area_loading_gudang'))
        {
            $upload_data = $this->upload->data();
            $foto_area_loading_gudang = $upload_data['file_name'];
        }else{
            $upload_data = '' ;
            $foto_area_loading_gudang = '';
        }

        if($this->upload->do_upload('foto_gudang_baik'))
        {
            $upload_data = $this->upload->data();
            $foto_gudang_baik = $upload_data['file_name'];
        }else{
            $upload_data = '';
            $foto_gudang_baik = '';
        }

        if($this->upload->do_upload('foto_gudang_retur'))
        {
            $upload_data = $this->upload->data();
            $foto_gudang_retur = $upload_data['file_name'];
        }else{
            $upload_data = '';
            $foto_gudang_retur = '';
        }

        // die;

        $data = [
            "id" => $this->input->post('id'),
            "site_code" => $this->input->post('site_code'),
            "nama" => $this->input->post('nama'),
            "status_afiliasi" => $this->input->post('status_afiliasi'),
            "alamat" => $this->input->post('alamat'),
            "propinsi" => $this->input->post('propinsi'),
            "kota" => $this->input->post('kabupaten'),
            "kecamatan" => $this->input->post('kecamatan'),
            "kelurahan" => $this->input->post('kelurahan'),
            "kodepos" => $this->input->post('kodepos'),
            "telp" => $this->input->post('telp'),
            "status_properti" => $this->input->post('status_properti'),
            "sewa_from" => $this->input->post('sewa_from'),
            "sewa_to" => $this->input->post('sewa_to'),
            "harga_sewa" => $this->input->post('harga_sewa'),
            "bentuk_bangunan" => $this->input->post('bentuk_bangunan'),
            "foto_tampak_depan" => $foto_tampak_depan,
            "foto_gudang" => $foto_gudang,
            "foto_kantor" => $foto_kantor,
            "foto_area_loading_gudang" => $foto_area_loading_gudang,
            "foto_gudang_baik" => $foto_gudang_baik,
            "foto_gudang_retur" => $foto_gudang_retur,
        ];

        $created_date = $this->input->post('created_at');
        // var_dump($created_date);die;
        
        $proses = $this->ModelDatabaseAfiliasi->edit('site.t_temp_dbafiliasi_tabel_profile', $data);
        if ($proses) {
            $this->ModelDatabaseAfiliasi->simpan_preview($created_date);
            redirect('database_afiliasi');
        }else{
            echo '<script>alert("Tidak berhasil menginput data. Jika perlu bantuan silahkan hubungi IT !");</script>';
            redirect('database_afiliasi/preview','refresh');
        }
    }

    public function alasan_retur(){

        $curl = curl_init();
        $token = '11f3a8a682c1e8d097ae60d72ecf07c7';

        curl_setopt_array($curl, array(
            // CURLOPT_URL => "http://site.muliaputramandiri.com/restapi/api/master_data/provinsi?token=$token",
            CURLOPT_URL => "http://site.muliaputramandiri.com/restapi/api/master_data/alasan_retur?token=$token&X-API-KEY=123",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            // CURLOPT_HTTPHEADER => array('X-API-KEY : 123')
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
        echo "cURL Error #:" . $err;
        } else {
            $array_response = json_decode($response, true);
            $dataalasan = $array_response['data'];
            // var_dump($dataprovinsi);die;
            echo "<option value=''> -- Pilih Alasan -- </option>";

            foreach ($dataalasan as $key => $tiap_alasan)
            {
                echo "<option value='". $tiap_alasan["alasan"] . "' >";
                echo $tiap_alasan["alasan"];
                echo "</option>";
            }
        }
    }

    public function relokasi(){

        $curl = curl_init();
        $token = '11f3a8a682c1e8d097ae60d72ecf07c7';

        curl_setopt_array($curl, array(
            // CURLOPT_URL => "http://site.muliaputramandiri.com/restapi/api/master_data/provinsi?token=$token",
            CURLOPT_URL => "http://site.muliaputramandiri.com/restapi/api/master_data/relokasi?token=$token&X-API-KEY=123",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            // CURLOPT_HTTPHEADER => array('X-API-KEY : 123')
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
        echo "cURL Error #:" . $err;
        } else {
            $array_response = json_decode($response, true);
            $datarelokasi = $array_response['data'];
            // var_dump($dataprovinsi);die;
            echo "<option value=''> -- Pilih Alasan -- </option>";

            foreach ($datarelokasi as $key => $tiap_relokasi)
            {
                echo "<option value='". $tiap_relokasi["no_relokasi"] . "'signature_relokasi='" . $tiap_relokasi["signature"] . "' >";
                echo $tiap_relokasi["no_relokasi"];
                echo "</option>";
            }
        }
    }

    public function relokasi_from_to(){

        $curl = curl_init();
        $token = '11f3a8a682c1e8d097ae60d72ecf07c7';
        $signature = $this->input->post('signature_relokasi');
        // $signature = '9a9be42559e3a7a00f98f919c6975de9';

        curl_setopt_array($curl, array(
            // CURLOPT_URL => "http://site.muliaputramandiri.com/restapi/api/master_data/provinsi?token=$token",
            CURLOPT_URL => "http://site.muliaputramandiri.com/restapi/api/master_data/relokasi?token=$token&X-API-KEY=123&signature=$signature",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            // CURLOPT_HTTPHEADER => array('X-API-KEY : 123')
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
        echo "cURL Error #:" . $err;
        } else {
            $array_response = json_decode($response, true);
            $datarelokasi = $array_response['data'];
            // var_dump($datarelokasi);die;
            // echo "<option value=''> -- Pilih Relokasi -- </option>";

            foreach ($datarelokasi as $key => $tiap_relokasi)
            {
                echo "<option value='". $tiap_relokasi["from_site"] . "' >";
                echo $tiap_relokasi["from_nama_comp"]." -> ".$tiap_relokasi["to_nama_comp"];
                echo "</option>";
            }
        }
    }

    public function relokasi_tanggal_pengajuan(){

        $curl = curl_init();
        $token = '11f3a8a682c1e8d097ae60d72ecf07c7';
        $signature = $this->input->post('signature_relokasi');
        // $signature = '9a9be42559e3a7a00f98f919c6975de9';

        curl_setopt_array($curl, array(
            // CURLOPT_URL => "http://site.muliaputramandiri.com/restapi/api/master_data/provinsi?token=$token",
            CURLOPT_URL => "http://site.muliaputramandiri.com/restapi/api/master_data/relokasi?token=$token&X-API-KEY=123&signature=$signature",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            // CURLOPT_HTTPHEADER => array('X-API-KEY : 123')
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
        echo "cURL Error #:" . $err;
        } else {
            $array_response = json_decode($response, true);
            $datarelokasi = $array_response['data'];
            // var_dump($datarelokasi);die;
            // echo "<option value=''> -- Pilih Relokasi -- </option>";

            foreach ($datarelokasi as $key => $tiap_relokasi)
            {
                echo "<option value='". $tiap_relokasi["tanggal_pengajuan"] . "' >";
                echo $tiap_relokasi["tanggal_pengajuan"];
                echo "</option>";
            }
        }
    }

    public function relokasi_pic(){

        $curl = curl_init();
        $token = '11f3a8a682c1e8d097ae60d72ecf07c7';
        $signature = $this->input->post('signature_relokasi');
        // $signature = '9a9be42559e3a7a00f98f919c6975de9';

        curl_setopt_array($curl, array(
            // CURLOPT_URL => "http://site.muliaputramandiri.com/restapi/api/master_data/provinsi?token=$token",
            CURLOPT_URL => "http://site.muliaputramandiri.com/restapi/api/master_data/relokasi?token=$token&X-API-KEY=123&signature=$signature",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            // CURLOPT_HTTPHEADER => array('X-API-KEY : 123')
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
        echo "cURL Error #:" . $err;
        } else {
            $array_response = json_decode($response, true);
            $datarelokasi = $array_response['data'];
            // var_dump($datarelokasi);die;
            // echo "<option value=''> -- Pilih Relokasi -- </option>";

            foreach ($datarelokasi as $key => $tiap_relokasi)
            {
                echo "<option value='". $tiap_relokasi["tanggal_pengajuan"] . "' >";
                echo $tiap_relokasi["nama"];
                echo "</option>";
            }
        }
    }

    public function mes_kodeprod(){

        $curl = curl_init();
        $token = '11f3a8a682c1e8d097ae60d72ecf07c7';
        // $supp = $this->input->post('supp');

        curl_setopt_array($curl, array(
            // CURLOPT_URL => "http://site.muliaputramandiri.com/restapi/api/master_data/mes_kodeprod?token=$token&X-API-KEY=123",
            CURLOPT_URL => "http://site.muliaputramandiri.com/restapi/api/master_data/mes_kodeprod?token=$token&X-API-KEY=123",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            // CURLOPT_HTTPHEADER => array('X-API-KEY : 123')
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
        echo "cURL Error #:" . $err;
        } else {
            $array_response = json_decode($response, true);
            $datakodeprod = $array_response['data'];
            // var_dump($dataprovinsi);die;
            echo "<option value=''> -- Pilih Productid -- </option>";

            foreach ($datakodeprod as $key => $tiap_kodeprod)
            {
                echo "<option value='". $tiap_kodeprod["productid"] ."' id_kodeprod='" . $tiap_kodeprod["productid"] . "' >";
                echo $tiap_kodeprod["productid"]." - ".$tiap_kodeprod["nama_product"];
                echo "</option>";
            }
        }
    }

    public function mes_store(){

        $curl = curl_init();
        $token = '11f3a8a682c1e8d097ae60d72ecf07c7';
        // $supp = $this->input->post('supp');

        curl_setopt_array($curl, array(
            // CURLOPT_URL => "http://site.muliaputramandiri.com/restapi/api/master_data/mes_kodeprod?token=$token&X-API-KEY=123",
            CURLOPT_URL => "http://site.muliaputramandiri.com/restapi/api/master_data/mes_store?token=$token&X-API-KEY=123",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            // CURLOPT_HTTPHEADER => array('X-API-KEY : 123')
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
        echo "cURL Error #:" . $err;
        } else {
            $array_response = json_decode($response, true);
            $datastore = $array_response['data'];
            // var_dump($dataprovinsi);die;
            echo "<option value=''> -- Pilih Store -- </option>";

            foreach ($datastore as $key => $tiap_store)
            {
                echo "<option value='". $tiap_store["storeid"] ."' id_store='" . $tiap_store["storeid"] . "' >";
                echo $tiap_store["storeid"]." - ".$tiap_store["nama_store"];
                echo "</option>";
            }
        }
    }

    public function mes_olshop(){

        $curl = curl_init();
        $token = '11f3a8a682c1e8d097ae60d72ecf07c7';
        // $supp = $this->input->post('supp');

        curl_setopt_array($curl, array(
            // CURLOPT_URL => "http://site.muliaputramandiri.com/restapi/api/master_data/mes_kodeprod?token=$token&X-API-KEY=123",
            CURLOPT_URL => "http://site.muliaputramandiri.com/restapi/api/master_data/mes_olshop?token=$token&X-API-KEY=123",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            // CURLOPT_HTTPHEADER => array('X-API-KEY : 123')
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
        echo "cURL Error #:" . $err;
        } else {
            $array_response = json_decode($response, true);
            $dataskuolshop = $array_response['data'];
            // var_dump($dataprovinsi);die;
            echo "<option value=''> -- Pilih Sku Olshop -- </option>";

            foreach ($dataskuolshop as $key => $tiap_sku_olshop)
            {
                echo "<option value='". $tiap_sku_olshop["olshopid"] ."' id_olshop='" . $tiap_sku_olshop["olshopid"] . "' >";
                echo $tiap_sku_olshop["olshopid"]." - ".$tiap_sku_olshop["nama_olshop"];
                echo "</option>";
            }
        }
    }

    public function mes_sku_olshop(){

        $this->load->model('model_mes');
        $olshopid = $this->model_mes->get_transaksi_detail_by_signature($this->input->post('signature'))->row()->olshopid;
        // $olshopid = 'O0001';
        $curl = curl_init();
        $token = '11f3a8a682c1e8d097ae60d72ecf07c7';
        
        curl_setopt_array($curl, array(
            // CURLOPT_URL => "http://site.muliaputramandiri.com/restapi/api/master_data/mes_kodeprod?token=$token&X-API-KEY=123",
            // CURLOPT_URL => "http://localhost:81/restapi/api/master_data/mes_sku_olshop?token=$token&X-API-KEY=123&olshopid=$olshopid",
            CURLOPT_URL => "http://site.muliaputramandiri.com/restapi/api/master_data/mes_sku_olshop?token=$token&X-API-KEY=123&olshopid=$olshopid",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            // CURLOPT_HTTPHEADER => array('X-API-KEY : 123')
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
        echo "cURL Error #:" . $err;
        } else {
            $array_response = json_decode($response, true);
            $dataskuolshop = $array_response['data'];
            // var_dump($dataprovinsi);die;
            echo "<option value=''> -- Pilih SKU Olshop -- </option>";

            foreach ($dataskuolshop as $key => $tiap_olshop)
            {
                echo "<option value='". $tiap_olshop["skuid"] ."' id_sku='" . $tiap_olshop["skuid"] . "' >";
                echo $tiap_olshop["skuid"]." - ".$tiap_olshop["nama_sku"];
                echo "</option>";
            }
        }
    }

    public function branch(){

        $curl = curl_init();
        $token = '11f3a8a682c1e8d097ae60d72ecf07c7';
        // $supp = $this->input->post('supp');

        curl_setopt_array($curl, array(
            // CURLOPT_URL => "http://site.muliaputramandiri.com/restapi/api/master_data/mes_kodeprod?token=$token&X-API-KEY=123",
            CURLOPT_URL => "http://site.muliaputramandiri.com/restapi/api/master_data/branch?token=$token&X-API-KEY=123",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            // CURLOPT_HTTPHEADER => array('X-API-KEY : 123')
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
        echo "cURL Error #:" . $err;
        } else {
            $array_response = json_decode($response, true);
            $datasbranch = $array_response['data'];
            // var_dump($dataprovinsi);die;
            echo "<option value=''> Pilih DP </option>";

            foreach ($datasbranch as $key => $tiapbranch)
            {
                echo "<option value='". $tiapbranch["id"] ."' customerid='" . $tiapbranch["kode_lang"] . "' >";
                echo $tiapbranch["company"]." - ".$tiapbranch["username"]." - ".$tiapbranch["id"];
                echo "</option>";
            }
        }
    }

    public function nama_comp_bonus(){

        $curl = curl_init();
        $token = '11f3a8a682c1e8d097ae60d72ecf07c7';
        // $supp = $this->input->post('supp');

        curl_setopt_array($curl, array(
            // CURLOPT_URL => "http://site.muliaputramandiri.com/restapi/api/master_data/mes_kodeprod?token=$token&X-API-KEY=123",
            CURLOPT_URL => "http://site.muliaputramandiri.com/restapi/api/master_data/nama_comp_bonus?token=$token&X-API-KEY=123",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            // CURLOPT_HTTPHEADER => array('X-API-KEY : 123')
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
        echo "cURL Error #:" . $err;
        } else {
            $array_response = json_decode($response, true);
            $datasbranch = $array_response['data'];
            // var_dump($dataprovinsi);die;
            echo "<option value=''> -- Pilih Subbranch -- </option>";

            foreach ($datasbranch as $key => $tiapbranch)
            {
                echo "<option value='". $tiapbranch["site_code"] ."' site_code='" . $tiapbranch["site_code"] . "' >";
                echo $tiapbranch["branch_name"]." - ".$tiapbranch["nama_comp"];
                echo "</option>";
            }
        }
    }

    public function nama_program_bonus(){

        $curl = curl_init();
        $token = '11f3a8a682c1e8d097ae60d72ecf07c7';
        // $supp = $this->input->post('supp');

        curl_setopt_array($curl, array(
            // CURLOPT_URL => "http://site.muliaputramandiri.com/restapi/api/master_data/mes_kodeprod?token=$token&X-API-KEY=123",
            CURLOPT_URL => "http://site.muliaputramandiri.com/restapi/api/master_data/nama_program_bonus?token=$token&X-API-KEY=123",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            // CURLOPT_HTTPHEADER => array('X-API-KEY : 123')
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
        echo "cURL Error #:" . $err;
        } else {
            $array_response = json_decode($response, true);
            $datasbranch = $array_response['data'];
            // var_dump($dataprovinsi);die;
            echo "<option value=''> -- Pilih Periode Program -- </option>";

            foreach ($datasbranch as $key => $tiapbranch)
            {
                echo "<option value='". $tiapbranch["nama_program"] ."' nama_program='" . $tiapbranch["nama_program"] . "' >";
                echo $tiapbranch["nama_program"];
                echo "</option>";
            }
        }
    }

    public function satuan(){

        $curl = curl_init();

        $token = '11f3a8a682c1e8d097ae60d72ecf07c7';
        $kodeprod = $this->input->post('kodeprod');

        curl_setopt_array($curl, array(
        // CURLOPT_URL => "http://site.muliaputramandiri.com/restapi/api/master_data/kabupaten?token=$token?id_provinsi = $id_provinsi",
        CURLOPT_URL => "http://site.muliaputramandiri.com/restapi/api/master_data/satuan?&token=$token&kodeprod=$kodeprod&X-API-KEY=123",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        // CURLOPT_HTTPHEADER => array('X-API-KEY : 123')
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
        echo "cURL Error #:" . $err;
        } else {
            $array_response = json_decode($response, true);
            $datasatuan = $array_response['data'];

            // echo "<option value=''> -- Pilih Kabupaten -- </option>";

            foreach ($datasatuan as $key => $tiap_satuan)
            {
                echo "<option value='". $tiap_satuan["kecil"] ."' id_satuan='" . $tiap_satuan["kecil"] . "' >";
                echo $tiap_satuan["kecil"];
                echo "</option>";
            }
        }
    }

    public function alasan_retur_new(){

        $curl = curl_init();
        $token = '11f3a8a682c1e8d097ae60d72ecf07c7';
        $supp = $this->input->post('supp');
        // $supp = "005";

        // echo "supp : ".$supp;
        // die;

        curl_setopt_array($curl, array(
            CURLOPT_URL => "http://site.muliaputramandiri.com/restapi/api/master_data/alasan_retur_new?token=$token&X-API-KEY=123&supp=$supp",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            // CURLOPT_HTTPHEADER => array('X-API-KEY : 123')
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
        echo "cURL Error #:" . $err;
        } else {
            $array_response = json_decode($response, true);
            $dataalasan = $array_response['data'];
            // var_dump($dataalasan);die;
            echo "<option value=''> -- Pilih Alasan -- </option>";

            foreach ($dataalasan as $key => $tiap_alasan)
            {
                echo "<option value='". $tiap_alasan["kode_alasan"] . "' >";
                echo $tiap_alasan["nama_alasan"];
                echo "</option>";
            }
        }
    }

    public function action_pengajuan_retur(){

        $curl = curl_init();

        $token = '11f3a8a682c1e8d097ae60d72ecf07c7';
        $status_principal_ho_terpilih = $this->input->post('status_principal_ho_terpilih');
        $supp = $this->input->post('supp');
        $signature = $this->input->post('signature');
        // $signature = 'RTR-17740fff259797d26a67b11f34dd02636b69520231104';
        // $signature = '1';

        curl_setopt_array($curl, array(
        CURLOPT_URL => "http://site.muliaputramandiri.com/restapi/api/master_data/action_pengajuan_retur?&token=$token&status_principal_ho_terpilih=$status_principal_ho_terpilih&supp=$supp&signature=$signature&X-API-KEY=123",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        // CURLOPT_HTTPHEADER => array('X-API-KEY : 123')
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
        echo "cURL Error #:" . $err;
        } else {
            $array_response = json_decode($response, true);
            $dataaction = $array_response['data'];

            // echo "<option value=''> -- Pilih Kabupaten -- </option>";

            foreach ($dataaction as $key => $tiap_action)
            {
                echo "<option value='". $tiap_action["id_status"] ."' id_action='" . $tiap_action["action_retur"] . "' >";
                echo $tiap_action["action_retur"];
                echo "</option>";
            }
        }
    }

    public function nama_comp_claim(){

        $curl = curl_init();
        $token = '11f3a8a682c1e8d097ae60d72ecf07c7';
        // $supp = $this->input->post('supp');

        curl_setopt_array($curl, array(
            // CURLOPT_URL => "http://site.muliaputramandiri.com/restapi/api/master_data/mes_kodeprod?token=$token&X-API-KEY=123",
            CURLOPT_URL => "http://site.muliaputramandiri.com/restapi/api/master_data/nama_comp_claim?token=$token&X-API-KEY=123",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            // CURLOPT_HTTPHEADER => array('X-API-KEY : 123')
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
        echo "cURL Error #:" . $err;
        } else {
            $array_response = json_decode($response, true);
            $datasbranch = $array_response['data'];
            // var_dump($dataprovinsi);die;
            echo "<option value=''> -- Pilih DP -- </option>";

            foreach ($datasbranch as $key => $tiapbranch)
            {
                echo "<option value='". $tiapbranch["site_code"] ."' site_code='" . $tiapbranch["site_code"] . "' >";
                echo $tiapbranch["branch_name"]." - ".$tiapbranch["nama_comp"];
                echo "</option>";
            }
        }
    }

}
?>
