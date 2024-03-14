<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Dc extends MY_Controller
{
    function dc()
    {
        $logged_in = $this->session->userdata('logged_in');
        if (!isset($logged_in) || $logged_in != TRUE) {
            redirect('login/', 'refresh');
        }
        set_time_limit(0);
        $this->load->library(array('table', 'template', 'Excel_generator', 'form_validation', 'email'));
        $this->load->helper('url');
        $this->load->helper('csv');
        $this->load->model('m_dc');
        $this->load->model('M_menu');
        $this->load->model('model_outlet_transaksi');
        $this->load->database();
    }

    public function dashboard()
    {
        $data = [
            'id'        => $this->session->userdata('id'),
            'title'     => 'DC',
            'url'       => 'dc/proses_dashboard',
            'url_keluar' => 'dc/proses_dashboard_keluar',
            'get_label' => $this->M_menu->get_label(),
            'get_data_row_mutasi'  => $this->m_dc->get_data_row_mutasi()->result(),
            'get_data_row_mutasi_by_produk'  => $this->m_dc->get_data_row_mutasi_by_produk()->result(),
            'get_data_row_mutasi_by_produk_total'  => $this->m_dc->get_data_row_mutasi_by_produk_total()->row(),
            'get_data_masuk'  => $this->m_dc->get_data_masuk()->row(),
            'get_data_row_masuk'  => $this->m_dc->get_data_row_masuk()->result(),
            'get_data_row_keluar'  => $this->m_dc->get_data_row_keluar()->result(),
        ];
        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar', $data);
        $this->load->view('template_claim/top_content', $data);
        $this->load->view('dc/dashboard', $data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
    }

    public function proses_dashboard()
    {
        $nodo = $this->input->post('nodo');
        $userid = $this->session->userdata('id');
        // echo "nodo : ".$nodo;

        $created_at = $this->model_outlet_transaksi->timezone();

        $signature = md5(str_replace('-', '', $created_at)) . "-" . md5($userid);
        $kode = $this->m_dc->generate($created_at, $userid);

        $insert_temp_dc_do = $this->m_dc->insert_temp_dc_do($nodo, $created_at, $userid, $signature, $kode);

        if (!$insert_temp_dc_do) {
            echo "<script>alert('ada kesalahan, silahkan ulangi kembali'); </script>";
            redirect('dc/dashboard', 'refresh');
        }

        $data = [
            'title'             => 'DC',
            'url'               => 'dc/save_barang_masuk',
            'get_label'         => $this->M_menu->get_label(),
            'get_data_header_by_nodo'  => $this->m_dc->get_data_by_nodo($created_at, $userid)->row(),
            'get_data_detail_by_nodo'  => $this->m_dc->get_data_by_nodo($created_at, $userid)->result(),
        ];

        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar', $data);
        $this->load->view('template_claim/top_content', $data);
        $this->load->view('dc/data_do', $data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
    }

    public function proses_dashboard_keluar()
    {
        // $created_at = $this->model_outlet_transaksi->timezone();

        // $signature = md5(str_replace('-', '', $created_at)) . "-" . md5($userid);
        // $kode = $this->m_dc->generate($created_at, $userid);

        // $insert_temp_dc_do = $this->m_dc->insert_temp_dc_do($nodo, $created_at, $userid, $signature, $kode);

        // if (!$insert_temp_dc_do) {
        //     echo "<script>alert('ada kesalahan, silahkan ulangi kembali'); </script>";
        //     redirect('dc/dashboard', 'refresh');
        // }


        $kode_masuk = $this->input->post('kode_masuk');
        $userid = $this->session->userdata('id');
        // echo "kode_masuk : " . $kode_masuk;
        // die;

        $created_at = $this->model_outlet_transaksi->timezone();

        $signature = md5(str_replace('-', '', $created_at)) . "-" . md5($userid);
        $kode = $this->m_dc->generate_keluar($created_at, $userid);


        $insert_temp_dc_do = $this->m_dc->insert_temp_dc_do_keluar($kode_masuk, $created_at, $userid, $signature, $kode);

        if (!$insert_temp_dc_do) {
            echo "<script>alert('ada kesalahan, silahkan ulangi kembali'); </script>";
            redirect('dc/dashboard', 'refresh');
        }

        $data = [
            'title'             => 'DC',
            'url'               => 'dc/save_barang_keluar',
            'get_label'         => $this->M_menu->get_label(),
            'get_data_header_by_nodo_keluar'  => $this->m_dc->get_data_header_by_nodo_keluar($created_at, $userid)->row(),
            'get_data_detail_by_nodo_keluar'  => $this->m_dc->get_data_detail_by_nodo_keluar($created_at, $userid)->result(),
        ];

        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar', $data);
        $this->load->view('template_claim/top_content', $data);
        $this->load->view('dc/data_do_keluar', $data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
    }

    public function generate_pdf_keluar($signature)
    {
        $this->load->library('mypdf');

        // echo "type : " . $type;
        // echo "signature : " . $signature;


        $get_data = $this->m_dc->get_data_row_keluar($signature)->result();
        // var_dump($get_data);

        $get_kode = $this->m_dc->get_data_row_keluar($signature)->row();
        $kode = $get_kode->kode;
        // echo "kode : ".$kode;
        // die;
        $filename_pdf = $kode;

        foreach ($get_data as $key) {
            $kode       = $key->kode;
            $nodo       = $key->nodo;
            $tgldo      = $key->tgldo;
            $nopo       = $key->nopo;
            $tglpo      = $key->tglpo;
            $tipe       = $key->tipe;
            $company    = $key->company;
            $kode_alamat = $key->kode_alamat;
            $alamat     = $key->alamat;
            $alamat_kirim = $key->alamat_kirim;
            $total_karton = $key->total_karton;
            $total_berat = $key->total_berat;
            $total_volume = $key->total_volume;
            $alamat_kirim = $key->alamat_kirim;
            $created_at = $key->created_at;
            $created_at_format = date("d M Y", strtotime($key->created_at));
        }

        // echo "created_at : " . $created_at;

        // $x = date_format($created_at, "Y/m/d H:i:s");

        // $originalDate = "2010-03-21";
        // $newDate = date("d M Y", strtotime($created_at));

        // echo "<br>";
        // echo "newDate : " . $newDate;
        // die;

        $data = [
            "kode"          => $kode,
            "nodo"          => $nodo,
            "tgldo"         => $tgldo,
            "nopo"          => $nopo,
            "tglpo"         => $tglpo,
            "tipe"          => $tipe,
            "company"       => $company,
            "kode_alamat"   => $kode_alamat,
            "alamat"        => $alamat,
            "alamat_kirim"  => $alamat_kirim,
            "total_karton"  => $total_karton,
            "total_berat"  => $total_berat,
            "total_volume"  => $total_volume,
            "created_at"    => $created_at,
            "created_at_format"    => $created_at_format,
            "detail"        => $this->m_dc->get_data_row_keluar_by_kodeprod($signature)->result()
        ];

        $generate_pdf = $this->mypdf->generate('dc/template_pdf_dc_keluar', $data, $kode, 'A4', 'portrait');
    }

    public function export_kartu_stock()
    {
        $id = $this->session->userdata('id');
        $query = "
            select  * from site.t_dc_mutasi a
            where a.deleted is null
        ";
        $hsl = $this->db->query($query);
        query_to_csv($hsl, TRUE, 'DC-Kartu Stock.csv');
    }

    public function export_total_stock()
    {
        $id = $this->session->userdata('id');
        $query = "
            select  a.kodeprod, a.namaprod, sum(a.banyak) as stock 
            from    site.t_dc_do a
            where   a.deleted is null
            GROUP BY a.kodeprod
        ";
        $hsl = $this->db->query($query);
        query_to_csv($hsl, TRUE, 'DC - Total Stock Produk.csv');
    }

    public function export_masuk()
    {
        $id = $this->session->userdata('id');
        $query = "
            select *
            from site.t_dc_do a
            where a.deleted is null
        ";
        $hsl = $this->db->query($query);
        query_to_csv($hsl, TRUE, 'DC - History Masuk.csv');
    }

    public function export_keluar()
    {
        $id = $this->session->userdata('id');
        $query = "
            select *
            from site.t_dc_do_keluar a
            where a.deleted is null
        ";
        $hsl = $this->db->query($query);
        query_to_csv($hsl, TRUE, 'DC - History Keluar.csv');
    }

    public function save_barang_masuk()
    {
        $id = $this->input->post('id');
        $qty_diterima = $this->input->post('qty_diterima');
        $kodeprod = $this->input->post('kodeprod');
        $namaprod = $this->input->post('namaprod');
        $nodo = $this->input->post('nodo');
        $tgldo = $this->input->post('tgldo');
        $nopo = $this->input->post('nopo');
        $branch_name = $this->input->post('branch_name');
        $nama_comp = $this->input->post('nama_comp');
        $site_code = $this->input->post('site_code');
        $kode = $this->input->post('kode');
        $tglpo = $this->input->post('tglpo');
        $tipe = $this->input->post('tipe');
        $company = $this->input->post('company');
        $kode_alamat = $this->input->post('kode_alamat');
        $alamat = $this->input->post('alamat');
        $signature = $this->input->post('signature');

        // var_dump($id);
        // echo count($id);
        // echo "<hr>";

        for ($i = 0; $i < count($id); $i++) {
            // echo "id : ".$id[$i]."<br>";
            // echo "qty_diterima : ".$qty_diterima[$i]."<br>";
            // echo "kodeprod : ".$kodeprod[$i]."<br>";
            // echo "kode : ".$kode."<br>";
            // echo "nodo : ".$nodo."<br>";
            // echo "tgldo : ".$tgldo."<br>";
            // echo "nopo : ".$nopo."<br>";
            // echo "tglpo : ".$tglpo."<br>";
            // echo "tipe : ".$tipe."<br>";
            // echo "namaprod : ".$namaprod[$i]."<br>";
            // echo "company : ".$company."<br>";
            // echo "kode_alamat : ".$kode_alamat."<br>";
            // echo "alamat : ".$alamat."<br>";
            // echo "signature : ".$signature."<br>";

            $data_x = [
                "id_do"         => $id[$i],
                "kode"          => $kode,
                "nodo"          => $nodo,
                "tgldo"         => $tgldo,
                "nopo"          => $nopo,
                "tglpo"         => $tglpo,
                "tipe"          => $tipe,
                "kodeprod"      => $kodeprod[$i],
                "namaprod"      => $namaprod[$i],
                "banyak"        => $qty_diterima[$i],
                "company"       => $company,
                "kode_alamat"   => $kode_alamat,
                "alamat"        => $alamat,
                "created_at"    => $this->model_outlet_transaksi->timezone(),
                "created_by"    => $this->session->userdata('id'),
                "signature"     => $signature
            ];

            $proses_masuk = $this->db->insert('site.t_dc_do', $data_x);

            // die;

            // mencari saldo awal
            $get_saldo_awal = $this->m_dc->get_saldo_awal($kodeprod[$i])->row();
            // var_dump($get_saldo_awal);

            if ($get_saldo_awal) {
                $saldo_awal_params = $get_saldo_awal->total;
            } else {
                $saldo_awal_params = 0;
            }

            // mencari total
            $total = $saldo_awal_params + $qty_diterima[$i];

            $data = [
                'id_dc_masuk'   => $id[$i],
                'kode'          => $kode,
                'masuk'         => $qty_diterima[$i],
                'kodeprod'      => $kodeprod[$i],
                'namaprod'      => $namaprod[$i],
                'nodo'          => $nodo,
                'nopo'          => $nopo,
                'branch_name'   => $branch_name,
                'nama_comp'     => $nama_comp,
                'site_code'     => $site_code,
                'saldo_awal'    => $saldo_awal_params,
                'total'         => $total,
                'created_at'    => $this->model_outlet_transaksi->timezone(),
                'created_by'    => $this->session->userdata('id')
            ];
            $proses_mutasi = $this->db->insert('site.t_dc_mutasi', $data);
        }

        if ($proses_mutasi) {
            echo "<script>alert('mutasi barang masuk berhasil'); </script>";
            redirect('dc/dashboard', 'refresh');
        }
    }

    public function save_barang_keluar()
    {
        $id = $this->input->post('id');
        $qty_diterima = $this->input->post('qty_diterima');
        $kodeprod = $this->input->post('kodeprod');
        $namaprod = $this->input->post('namaprod');
        $nodo = $this->input->post('nodo');
        $nopo = $this->input->post('nopo');
        $branch_name = $this->input->post('branch_name');
        $nama_comp = $this->input->post('nama_comp');
        $site_code = $this->input->post('site_code');


        $tgldo = $this->input->post('tgldo');
        $kode = $this->input->post('kode');
        $tglpo = $this->input->post('tglpo');
        $tipe = $this->input->post('tipe');
        $company = $this->input->post('company');
        $kode_alamat = $this->input->post('kode_alamat');
        $alamat = $this->input->post('alamat');
        $signature = $this->input->post('signature');

        // var_dump($id);
        // echo count($id);
        // echo "<hr>";

        for ($i = 0; $i < count($id); $i++) {
            // echo "id : " . $id[$i] . "<br>";
            // echo "qty_diterima : " . $qty_diterima[$i] . "<br>";
            // echo "kodeprod : " . $kodeprod[$i] . "<br>";
            // echo "kode : " . $kode . "<br>";
            // echo "nodo : " . $nodo . "<br>";
            // echo "tgldo : " . $tgldo . "<br>";
            // echo "nopo : " . $nopo . "<br>";
            // echo "tglpo : " . $tglpo . "<br>";
            // echo "tipe : " . $tipe . "<br>";
            // echo "namaprod : " . $namaprod[$i] . "<br>";
            // echo "company : " . $company . "<br>";
            // echo "kode_alamat : " . $kode_alamat . "<br>";
            // echo "alamat : " . $alamat . "<br>";
            // echo "signature : " . $signature . "<br>";

            $data_x = [
                "id_do"         => $id[$i],
                "kode"          => $kode,
                "nodo"          => $nodo,
                "tgldo"         => $tgldo,
                "nopo"          => $nopo,
                "tglpo"         => $tglpo,
                "tipe"          => $tipe,
                "kodeprod"      => $kodeprod[$i],
                "namaprod"      => $namaprod[$i],
                "banyak"        => $qty_diterima[$i],
                "company"       => $company,
                "kode_alamat"   => $kode_alamat,
                "alamat"        => $alamat,
                "created_at"    => $this->model_outlet_transaksi->timezone(),
                "created_by"    => $this->session->userdata('id'),
                "signature"     => $signature
            ];

            $proses_masuk = $this->db->insert('site.t_dc_do_keluar', $data_x);

            // mencari saldo awal
            $get_saldo_awal = $this->m_dc->get_saldo_awal($kodeprod[$i])->row();
            // var_dump($get_saldo_awal);

            if ($get_saldo_awal) {
                $saldo_awal_params = $get_saldo_awal->total;
            } else {
                $saldo_awal_params = 0;
            }

            // mencari total
            $total = $saldo_awal_params - $qty_diterima[$i];

            $data = [
                'id_dc_masuk'   => $id[$i],
                'kode'          => $kode,
                'keluar'        => $qty_diterima[$i],
                'kodeprod'      => $kodeprod[$i],
                'namaprod'      => $namaprod[$i],
                'nodo'          => $nodo,
                'nopo'          => $nopo,
                'branch_name'   => $branch_name,
                'nama_comp'     => $nama_comp,
                'site_code'     => $site_code,
                'saldo_awal'    => $saldo_awal_params,
                'total'         => $total,
                'created_at'    => $this->model_outlet_transaksi->timezone(),
                'created_by'    => $this->session->userdata('id')
            ];
            $proses_masuk = $this->db->insert('site.t_dc_mutasi', $data);
        }

        if ($proses_masuk) {
            echo "<script>alert('mutasi barang keluar berhasil'); </script>";
            redirect('dc/dashboard', 'refresh');
        }
    }

    public function nopo()
    {

        $curl = curl_init();
        $token = '11f3a8a682c1e8d097ae60d72ecf07c7';

        curl_setopt_array($curl, array(
            CURLOPT_URL => "http://site.muliaputramandiri.com/restapi/api/master_data/nopo?token=$token&X-API-KEY=123",
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
            $datanopo = $array_response['data'];
            echo "<option value=''> -- Pilih Nopo -- </option>";

            foreach ($datanopo as $key => $datanopo) {
                echo "<option value='" . $datanopo["nopo"] . "' id='" . $datanopo["id"] . "' >";
                echo $datanopo["nopo"] . ' - ' . $datanopo["company"];
                echo "</option>";
            }
        }
    }

    public function nodo_barang_keluar()
    {
        $curl = curl_init();
        $token = '11f3a8a682c1e8d097ae60d72ecf07c7';

        curl_setopt_array($curl, array(
            CURLOPT_URL => "http://site.muliaputramandiri.com/restapi/api/master_data/nodo_barang_keluar?token=$token&X-API-KEY=123",
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
            $datanodo = $array_response['data'];
            echo "<option value=''> -- Pilih Kode LBM -- </option>";

            foreach ($datanodo as $key => $tiapdatanodo) {
                echo "<option value='" . $tiapdatanodo["kode"] . "' kode='" . $tiapdatanodo["kode"] . "' >";
                echo $tiapdatanodo["kode"] . " - " . $tiapdatanodo["company"];
                echo "</option>";
            }
        }
    }

    public function list_po()
    {

        $data = [
            'id'            => $this->session->userdata('id'),
            'url_import'    => 'asn/upload_asn',
            'url_export'    => 'asn/export_asn',
            'title'         => 'DC',
            'get_label'     => $this->M_menu->get_label(),
            'get_list_po'        => $this->m_dc->get_list_po(),
            'get_po'        => $this->m_dc->get_po(),
            'supp'          => $this->session->userdata('supp')
        ];

        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar', $data);
        $this->load->view('template_claim/top_content', $data);
        $this->load->view('dc/list_po', $data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
    }

    public function list_do($signature, $supp, $nodo = "")
    {
        $data = [
            'get_label' => $this->M_menu->get_label(),
            'title'     => 'Input Barang Masuk | Silahkan Pilih Produk',
            'url'       => 'dc/edit_kartu_stock',
            // 'header'    => $this->m_dc->get_kartu_masuk($nodo)->row(),
            'header'    => $this->m_dc->get_do($signature, $supp, $nodo)->row(),
            'detail'    => $this->m_dc->get_do($signature, $supp, $nodo)->result(),
        ];

        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar', $data);
        $this->load->view('template_claim/top_content', $data);
        $this->load->view('dc/list_do', $data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
    }

    public function edit_kartu_stock()
    {
        $signature = $this->input->post('signature');
        $supp = $this->input->post('supp');
        $nodo = $this->input->post('nodo');
        $status_qty = $this->input->post('status_qty');
        $batch_number = $this->input->post('batch_number');
        $ed = $this->input->post('ed');
        $detail_produk = $this->input->post('options');
        $masuk = $this->input->post('masuk');

        if ($detail_produk == '') {
            echo "<script>alert('anda belum memilih produk satupun'); </script>";
            redirect('dc/list_do/' . $signature . '/' . $supp . '/' . $nodo, 'refresh');
        }

        foreach ($detail_produk as $id_do) {
            // echo $id_do;
            $get_do = $this->m_dc->get_do_single($id_do, "db_po.t_do_us")->row();

            if ($status_qty == "1") { // jika terima barang sesuai DO
                $qty_masuk = "$get_do->banyak";
            } else { // jika terima barang <> DO, sehingga mengambil kolom "masuk"
                $qty_masuk = $masuk;
            }

            // data yang akan di update atau insert
            $data = [
                "id_do"             => $id_do,
                "kode_kartu_masuk"  => $this->m_dc->generate_kode_kartu($supp, $nodo),
                "supp"              => $supp,
                "kodeprod"          => $get_do->kodeprod,
                "masuk"             => $qty_masuk,
                "batch_number"      => $batch_number,
                "ed"                => $ed,
                "nodo"              => $get_do->nodo,
                "tgldo"             => $get_do->tgldo,
                "qty_do"            => $get_do->banyak,
                "created_at"        => $this->model_outlet_transaksi->timezone(),
                "created_by"        => $this->session->userdata('id')
            ];

            //cek apakah sudah ada kodeprod yang sama di dalam tabel
            $get_data = $this->db->get_where("site.t_kartu_stock_log", array(
                "id_do" => $id_do,
            ));

            if ($get_data->num_rows() > 0) { //jika data sudah ada, maka lakukan update

                $this->db->where('id_do', $id_do);
                $update = $this->db->update("site.t_kartu_stock_log ", $data);
            } else { //jika data baru, maka lakukan insert

                $insert = $this->db->insert("site.t_kartu_stock_log", $data);
                $id_kartu_stock_log = $this->db->insert_id();
            }


            // validasi jika ada yg NULL
            $update_keluar_null = "
                update site.t_kartu_stock_log a 
                set a.keluar = 0
                where a.keluar is null and a.id_do = $id_do";
            $proses_update_keluar_null = $this->db->query($update_keluar_null);

            // menghitung sisa stock
            $sisa_stock = "
                update site.t_kartu_stock_log a 
                set a.sisa = a.masuk - a.keluar
                where a.id_do = $id_do";
            $proses_sisa_stock = $this->db->query($sisa_stock);
        }
        echo "<script>alert('input berhasil.'); </script>";
        redirect('dc/list_do/' . $signature . '/' . $supp . '/' . $nodo, 'refresh');
    }

    public function edit_kartu_stock_keluar()
    {

        $signature = $this->input->post('signature');
        $supp = $this->input->post('supp');
        $nodo = $this->input->post('nodo');
        $kode_kartu_keluar = $this->input->post('kode_kartu_keluar');
        $eta = $this->input->post('eta');
        $ekspedisi = $this->input->post('ekspedisi');
        $tanggal_keluar = $this->input->post('tanggal_keluar');

        // echo "signature : ".$signature;
        // echo "supp : ".$supp;
        // echo "nodo : ".$nodo;

        $status_qty = $this->input->post('status_qty');
        if ($status_qty == '1') {
            $keluar = "a.masuk";
        } else {
            $keluar = $this->input->post('keluar');
        }

        echo "status_qty : " . $status_qty;
        // die;

        // $batch_number = $this->input->post('batch_number');
        // $ed = $this->input->post('ed');
        $request = $this->input->post('options');

        foreach ($request as $id_kartu_masuk_log) {
            $sql_update = "
                update db_po.t_kartu_stock_log a 
                set a.keluar = $keluar, a.kode_kartu_keluar = '$kode_kartu_keluar', a.ekspedisi = '$ekspedisi',
                    a.tanggal_keluar = '$tanggal_keluar', a.eta = '$eta'
                where a.id = $id_kartu_masuk_log
            ";
            $proses_edit = $this->db->query($sql_update);

            $sql_sisa = "
                update db_po.t_kartu_stock_log a 
                set a.sisa = a.masuk - if(a.keluar is null, '0', a.keluar)
                where a.id = $id_kartu_masuk_log
            ";
            $proses_sisa = $this->db->query($sql_sisa);
        }

        echo "<script>alert('input berhasil.'); </script>";
        redirect('dc/list_stock_keluar/' . $signature . '/' . $supp . '/' . $nodo, 'refresh');

        // die;

    }

    public function list_kartu_stock()
    {
        $data = [
            'id'            => $this->session->userdata('id'),
            // 'url_import'    => 'asn/upload_asn',
            // 'url_export'    => 'asn/export_asn',
            'title'         => 'Input Barang Keluar | List Kartu Stock',
            'get_label'     => $this->M_menu->get_label(),
            'get_kartu_stock' => $this->m_dc->get_kartu_stock()->result(),
            'supp'          => $this->session->userdata('supp')
        ];

        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar', $data);
        $this->load->view('template_claim/top_content', $data);
        $this->load->view('dc/list_kartu_stock', $data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
    }

    public function list_stock_keluar($signature, $supp, $nodo = "")
    {

        // $kode_kartu_keluar = $this->m_dc->generate_kode_kartu_keluar($supp,$nodo);
        // // echo "cek : ".$cek;
        // echo "kode_kartu_keluar : ".$kode_kartu_keluar;

        // die;


        $x = $this->m_dc->get_kartu_masuk($nodo)->row();
        $header = [
            "nodo"  => $x->nodo,
            "namasupp" => $x->namasupp,
            "tgldo" => $x->tgldo,
            "nopo"  => $x->nopo
        ];

        $data = [
            'get_label' => $this->M_menu->get_label(),
            'title'     => 'Proses Barang Keluar | Silahkan Pilih Produk',
            'url'       => 'dc/edit_kartu_stock_keluar',
            'get_kartu_masuk'    => $this->m_dc->get_kartu_masuk($nodo)->result(),
            'kode_kartu_keluar'    => $this->m_dc->generate_kode_kartu_keluar($supp, $nodo),
        ];

        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar', $data);
        $this->load->view('template_claim/top_content', $data);
        $this->load->view('dc/list_stock_keluar', $header);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
    }

    public function resi_pdf($signature, $supp, $nodo)
    {
        $this->load->library('mypdf');

        $get_kartu_masuk = $this->m_dc->get_kartu_masuk($nodo)->result();


        $filename_pdf = $nodo;

        // $company ="PT A";

        // echo "<pre>";
        // print_r($get_kartu_masuk);
        // echo "</pre>";
        // die;
        // var_dump($get_pengajuan);die;
        foreach ($get_kartu_masuk as $key) {
            $kodeprod = $key->kodeprod;
            $namaprod = $key->namaprod;
            $keluar = $key->keluar;
            $ekspedisi = $key->ekspedisi;
            $tanggal_keluar = $key->tanggal_keluar;
            $eta = $key->eta;
            $batch_number = $key->batch_number;
            $ed = $key->ed;
            $namasupp = $key->namasupp;
            $nopo = $key->nopo;
            $company = $key->company;
            $alamat = $key->alamat;
            $email = $key->email;
        }

        // die;


        $data = [
            "kodeprod" => $kodeprod,
            "namaprod" => $namaprod,
            "company" => $company,
            "alamat" => $alamat,

            "detail" => $this->m_dc->get_kartu_masuk($nodo)->result(),
            // "get_discount" => $this->model_retur->get_discount($id),
            // "get_total" => $this->model_retur->get_total($id)
        ];

        $generate_pdf = $this->mypdf->generate('dc/template_pdf_resi', $data, $filename_pdf, 'A4', 'portrait');
    }

    // public function list_stock_keluar($signature, $supp){
    //     // $get_id_po = $this->m_dc->get_id_($signature);
    //     // foreach ($get_id_po as $a) {
    //     //     $nopo = $a->nopo;
    //     // }

    //     $data['get_kartu_stock_detail'] = $this->m_dc->get_kartu_stock_detail($signature);

    //     $data = [
    //         'get_label' => $this->M_menu->get_label(),
    //         'title'     => 'Input Barang Keluar | Silahkan Pilih Produk',
    //         'url'       => 'dc/input_stock_keluar',
    //         'get_kartu_stock_detail'    => $this->m_dc->get_kartu_stock_detail($signature),
    //         'generate_kode_kartu' => $this->m_dc->generate_kode_kartu_keluar($supp)
    //     ];

    //     $this->load->view('template_claim/top_header');
    //     $this->load->view('template_claim/header');
    //     $this->load->view('template_claim/sidebar',$data);
    //     $this->load->view('template_claim/top_content',$data);
    //     $this->load->view('dc/list_stock_keluar',$data);
    //     $this->load->view('template_claim/bottom_content');
    //     $this->load->view('template_claim/footer');

    // }

    public function get_nodo()
    {
        $curl = curl_init();
        $token = '11f3a8a682c1e8d097ae60d72ecf07c7';
        $tgldo = $this->input->post('tgldo');
        // $tgldo = '20220913';

        // echo "tgldo : ".date($tgldo);

        curl_setopt_array($curl, array(
            CURLOPT_URL => "http://site.muliaputramandiri.com/restapi/api/master_data/nodo?&token=$token&tgldo=$tgldo&X-API-KEY=123",
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
            $datado = $array_response['data'];

            echo "<option value=''> -- Pilih Surat Jalan -- </option>";

            foreach ($datado as $key => $tiap_do) {
                echo "<option value='" . $tiap_do["nodo"] . "' nodo='" . $tiap_do["nodo"] . "' >";
                echo $tiap_do["nodo"] . ' - ' . $tiap_do["company"];
                echo "</option>";
            }
        }
    }

    // public function nodo_barang_keluar()
    // {
    //     $curl = curl_init();
    //     $token = '11f3a8a682c1e8d097ae60d72ecf07c7';

    //     curl_setopt_array($curl, array(
    //         CURLOPT_URL => "http://localhost:81/restapi/api/master_data/nodo_barang_keluar?token=$token&X-API-KEY=123",
    //         CURLOPT_RETURNTRANSFER => true,
    //         CURLOPT_ENCODING => "",
    //         CURLOPT_MAXREDIRS => 10,
    //         CURLOPT_TIMEOUT => 30,
    //         CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    //         CURLOPT_CUSTOMREQUEST => "GET",
    //         // CURLOPT_HTTPHEADER => array('X-API-KEY : 123')
    //     ));

    //     $response = curl_exec($curl);
    //     $err = curl_error($curl);

    //     curl_close($curl);

    //     if ($err) {
    //         echo "cURL Error #:" . $err;
    //     } else {
    //         $array_response = json_decode($response, true);
    //         $datanodo = $array_response['data'];
    //         echo "<option value=''> -- Pilih Kode LBM -- </option>";

    //         foreach ($datanodo as $key => $tiapdatanodo) {
    //             echo "<option value='" . $tiapdatanodo["kode"] . "' kode='" . $tiapdatanodo["kode"] . "' >";
    //             echo $tiapdatanodo["kode"] . " - " . $tiapdatanodo["company"];
    //             echo "</option>";
    //         }
    //     }
    // }

    public function input_stock_keluar()
    {

        $signature = $this->input->post('signature');
        $supp = $this->input->post('supp');

        $request = $this->input->post('options');
        foreach ($request as $id_dc) {
            $supp = $this->input->post('supp');
            $jumlah_qty = $this->input->post('jumlah_qty');
            $status_qty = $this->input->post('status_qty');
            if ($status_qty == '1') {
                $banyak = $this->m_dc->get_banyak_dc($id_dc);
            } else {
                $banyak = $jumlah_qty;
            }
            // $data = [
            //     // 'id_do'         => $id_do,
            //     'supp'          => $supp,
            //     // 'nodo'          => $this->m_dc->get_nodo($id_do, $supp),
            //     'kode_kartu_keluar'    => $this->input->post('kode_kartu_keluar'),
            //     // 'batch_number'  => $this->input->post('batch_number'),
            //     // 'ed'            => $this->input->post('ed'),
            //     'kodeprod'      => $this->m_dc->get_kodeprod_dc($id_dc),
            //     'keluar'        => $banyak,
            //     'sisa'          => $banyak,
            //     'created_date'  => $this->model_outlet_transaksi->timezone(),
            //     'created_by'    => $this->session->userdata('id'), 
            //     'signature'    => md5($this->input->post('kode_kartu_keluar')) 
            // ];

            // $proses = $this->m_dc->input_kartu_stock($data,'db_po.t_kartu_stock');  

            $data = [
                'id_do'             => $id_dc,
                'supp'              => $supp,
                // 'nodo'              => $this->m_dc->get_nodo($id_do, $supp),
                'kode_kartu_keluar'  => $this->input->post('kode_kartu_keluar'),
                // 'batch_number'      => $this->input->post('batch_number'),
                // 'ed'                => $this->input->post('ed'),
                'kodeprod'          => $this->m_dc->get_kodeprod_dc($id_dc),
                'keluar'            => $banyak,
                'sisa'              => $banyak,
                'created_date'      => $this->model_outlet_transaksi->timezone(),
                'created_by'        => $this->session->userdata('id'),
                'signature'         => md5($this->input->post('kode_kartu_keluar')),
                'action'            => 'add'
            ];

            // input log
            $id_do = $this->m_dc->input_kartu_stock($data, 'db_po.t_kartu_stock_log');

            echo "<pre>";
            echo "id_do : " . $id_do;
            echo "</pre>";

            $cek_data = $this->db->get_where('db_po.t_kartu_stock', array('id_do' => $id_do))->row();

            // echo "cek_data : ".$cek_data;
            // echo "cek_data : ";
            // echo $cek_data->masuk;
            // var_dump($cek_data);
            if ($cek_data) {
                echo "update";
                // echo "a";
                // $data = array(
                //     'title' => $title,
                //     'name' => $name,
                //     'date' => $date
                //  );
                $data = [
                    'keluar'            => $banyak,
                    'sisa'              => $cek_data->masuk - $banyak,
                    'last_updated'      => $this->model_outlet_transaksi->timezone(),
                    'last_updated_by'   => $this->session->userdata('id'),
                    'kode_kartu_keluar'   => $this->input->post('kode_kartu_keluar'),
                    // 'signature'         => md5($this->input->post('kode_kartu_keluar')) ,
                    'action'            => 'add'
                ];

                $this->db->where('id_do', $id_do);
                $proses_kartu_stock = $this->db->update('db_po.t_kartu_stock', $data);
            } else {
                // echo "insert";

                // $data = [
                //     'id_do'             => $id_do,
                //     // 'id_log'            => $id_log,
                //     'supp'              => $supp,
                //     'nodo'              => $this->m_dc->get_nodo($id_do, $supp),
                //     'kode_kartu_masuk'  => $this->input->post('kode_kartu_masuk'),
                //     'batch_number'      => $this->input->post('batch_number'),
                //     'ed'                => $this->input->post('ed'),
                //     'kodeprod'          => $this->m_dc->get_kodeprod($id_do, $supp),
                //     'masuk'             => $banyak,
                //     'keluar'            => 0,
                //     'sisa'              => $banyak,
                //     'created_date'      => $this->model_outlet_transaksi->timezone(),
                //     'created_by'        => $this->session->userdata('id'), 
                //     'signature'         => md5($this->input->post('kode_kartu_masuk')) 
                // ];
                // $proses_kartu_stock = $this->m_dc->input_kartu_stock($data,'db_po.t_kartu_stock'); 

            }
        }

        if ($proses_kartu_stock) {


            // $this->load->view();
            echo "<script>alert('input berhasil.'); </script>";
            redirect('dc/list_stock_keluar/' . $signature . '/' . $supp, 'refresh');
        }
    }

    public function email_download($signature){
        $this->load->library('mypdf');
        $get_kode = $this->m_dc->get_data_row_keluar($signature)->row();
        $kode = $get_kode->kode;
        $filename_pdf = str_replace("/","_", $kode);
        // echo "filename_pdf : ".$filename_pdf;
        // die;

        $get_data = $this->m_dc->get_data_row_keluar($signature)->result();
        // var_dump($get_data);
        foreach ($get_data as $key) {
            $kode       = $key->kode;
            $nodo       = $key->nodo;
            $tgldo      = $key->tgldo;
            $nopo       = $key->nopo;
            $tglpo      = $key->tglpo;
            $tipe       = $key->tipe;
            $company    = $key->company;
            $kode_alamat = $key->kode_alamat;
            $alamat     = $key->alamat;
            $alamat_kirim = $key->alamat_kirim;
            $total_berat = $key->total_berat;
            $total_volume = $key->total_volume;
            $alamat_kirim = $key->alamat_kirim;
            $created_at = $key->created_at;
            $created_at_format = date("d M Y", strtotime($key->created_at));
        }

        $data = [
            "nama"          => 'PT. HITORI JAYA LOGISTIK',
            "kode"          => $kode,
            "nodo"          => $nodo,
            "tgldo"         => $tgldo,
            "nopo"          => $nopo,
            "tglpo"         => $tglpo,
            "tipe"          => $tipe,
            "company"       => $company,
            "kode_alamat"   => $kode_alamat,
            "alamat"        => $alamat,
            "alamat_kirim"  => $alamat_kirim,
            "total_berat"  => $total_berat,
            "total_volume"  => $total_volume,
            "created_at"    => $created_at,
            "created_at_format"    => $created_at_format,
            "detail"        => $this->m_dc->get_data_row_keluar_by_kodeprod($signature)->result()
        ];

        $generate_pdf = $this->mypdf->download_dc('dc/template_pdf_dc_keluar', $data, $filename_pdf, 'A4', 'portrait');

        $from = "suffy@muliaputramandiri.net";
        // $to = "suffy.yanuar@gmail.com";
        $to = "eviany@hitorijayalogistik.com, dila@hitorijayalogistik.com";
        // $to = "eviany@hitorijayalogistik.com, dila@hitorijayalogistik.com";
        $data['to'] = $to;
        $cc = "suffy@muliaputramandiri.com, linda@muliaputramandiri.com,fakhrul@muliaputramandiri.com, pondokcandra84@gmail.com";
        $subject = "MPM Site | DC - $filename_pdf";

        $message = $this->load->view("dc/email_barang_keluar",$data,TRUE);
        // $this->email->initialize($config);
        $this->email();
        $this->email->from($from,'PT. Mulia Putra Mandiri');
        $this->email->to($to);
        $this->email->cc($cc);
        $this->email->subject($subject);
        $this->email->message($message);
        $this->email->attach('assets/file/dc/'.str_replace('/','_',$kode).'.pdf');
        $send = $this->email->send();
        echo $this->email->print_debugger();

    }

    public function send_wa($signature){

        


        $get_kode = $this->m_dc->get_data_row_keluar($signature)->row();
        $kode = $get_kode->kode;
        $company = $get_kode->company;
        $alamat = $get_kode->alamat;
        $link = $get_kode->link;
        $kode_alamat = $get_kode->kode_alamat;
        $filename_pdf = str_replace("/","_", $kode);

        $sql = "select no_wa, nama_wa, no_wa_2, nama_wa_2 from site.map_dc_po a where a.site_code = '$kode_alamat'";

        echo "<pre>";
        print_r($sql);
        echo "</pre>";

        $get_wa = $this->db->query($sql)->row();

        $no_wa = $get_wa->no_wa;
        $nama_wa = $get_wa->nama_wa;
        $no_wa_2 = $get_wa->no_wa_2;
        $nama_wa_2 = $get_wa->nama_wa_2;


        // echo "no_wa : ".$no_wa;
        // echo "nama_wa : ".$nama_wa;

        // die;

        $no_wa = $no_wa;
        $message_result = "Hi $nama_wa, kami (MPM) merilis surat jalan dengan data sbb : \r\n\r\nNomor : *$filename_pdf*.\r\nCompany : $company\r\nAlamat : \r\n$alamat\r\n\r\nfile pdf sudah kami kirim juga via email atau bisa juga buka di link berikut \r\n$link.";

        $userkey = '6ecb7f9537ef';
        $passkey = 'e96c4c8cee6ac177f83477c2';
        $telepon = $no_wa;
        $image_link = 'http://site.muliaputramandiri.com/cisk/assets/file/dc/MPM_DO_202209-001.pdf';
        $message = $message_result;
        $caption  = $message_result;
        $url = 'https://console.zenziva.net/wareguler/api/sendWA/';
        $curlHandle = curl_init();
        curl_setopt($curlHandle, CURLOPT_URL, $url);
        curl_setopt($curlHandle, CURLOPT_HEADER, 0);
        curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curlHandle, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($curlHandle, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curlHandle, CURLOPT_TIMEOUT,30);
        curl_setopt($curlHandle, CURLOPT_POST, 1);
        curl_setopt($curlHandle, CURLOPT_POSTFIELDS, array(
            'userkey' => $userkey,
            'passkey' => $passkey,
            'to' => $telepon,
            'link' => $image_link,
            // 'caption' => $caption
            'message' => $message
        ));
        $results = json_decode(curl_exec($curlHandle), true);
        curl_close($curlHandle);

        echo "no_wa : ".$no_wa." message_result : ".$message_result."<br>";
        print_r($results)['status'];

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


    public function custom_barang_keluar()
    {

        $data = [
            'get_label' => $this->M_menu->get_label(),
            'title'     => 'Custom Barang Keluar',
            'url'       => 'dc/proses_custom_barang_keluar',
            // 'get_kartu_stock_detail'    => $this->m_dc->get_kartu_stock_detail($signature),
            // 'generate_kode_kartu' => $this->m_dc->generate_kode_kartu_keluar($supp)
        ];

        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar', $data);
        $this->load->view('template_claim/top_content', $data);
        $this->load->view('dc/custom_barang_keluar', $data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
    }

    public function confirm_picklist()
    {
    }

    public function konfirmasi_dc(){
        $data = [
            'id'        => $this->session->userdata('id'),
            'title'     => 'Konfirmasi DC',
            'url'       => 'dc/proses_dashboard',
            'url_keluar' => 'dc/proses_dashboard_keluar',
            'get_label' => $this->M_menu->get_label(),
            'get_konfirmasi'  => $this->m_dc->get_konfirmasi()->result(),
        ];

        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar', $data);
        $this->load->view('template_claim/top_content', $data);
        $this->load->view('dc/dashboard_konfirmasi_dc', $data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
    }

    public function konfirmasi_pengiriman_dc($signature){

        // echo "signature : ".$signature;
        // die;

        $created_at = $this->model_outlet_transaksi->timezone();

        $cek = $this->m_dc->konfirmasi_pengiriman($signature)->num_rows();
        if ($cek) {

        }else{
            
            $data = [
                "signature_dc"  => $signature,
                "created_at"  => $created_at,
            ];

            $proses_insert = $this->db->insert('site.t_konfirmasi_pengiriman_dc', $data);

        }

        // die;

        $data = [
            'id'        => $this->session->userdata('id'),
            'title'     => 'DC | Konfirmasi Pengiriman Barang - SJ1',
            'url'       => 'dc/proses_konfirmasi_pengiriman_dc',
            'get_label' => $this->M_menu->get_label(),
            'konfirmasi_pengiriman'  => $this->m_dc->konfirmasi_pengiriman($signature)->result(),
        ];

        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar', $data);
        $this->load->view('template_claim/top_content', $data);
        $this->load->view('dc/konfirmasi_pengiriman_dc', $data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
    }

    public function proses_konfirmasi_pengiriman_dc(){

        $created_at = $this->model_outlet_transaksi->timezone();
        $created_by = $this->session->userdata('id');
        $signature = $this->input->post('signature');
        $tanggal_kirim = $this->input->post('tanggal_kirim');
        $file_1 = $this->input->post('file_1');
        $file_2 = $this->input->post('file_2');
        $file_3 = $this->input->post('file_3');

        // echo $tanggal_kirim;

        if (!is_dir('./assets/file/dc/')) {
            @mkdir('./assets/file/dc/', 0777);
        }

        //konfigurasi upload
        $config['upload_path'] = './assets/file/dc/';
        $config['allowed_types'] = '*';
        $config['max_size'] = '*';
        // $config['encrypt_name'] = TRUE;
        // $config['file_name'] = $site_code.$name.'_'.$time;

        $this->load->library('upload', $config);
        if (!$this->upload->do_upload('file_1')) {
            // echo '<script>alert("File tidak sesuai. Pastikan File nya sudah benar. Jika perlu bantuan silahkan hubungi IT !");</script>';
            // redirect('all_upload/', 'refresh');
            // var_dump($this->upload->display_errors());
            $filename_1 = '';
        } else {

            $data = array('upload_data' => $this->upload->data());
            $file = $data['upload_data']['full_path'];
            chmod($file, 0777);

            //mengambil
            $upload_data = $this->upload->data();
            $filename_1 = $upload_data['file_name'];
            // $proses = $this->db->insert('db_temp.t_temp_file_sds', $file);
            // echo "filename : ".$filename;
        }

        if (!$this->upload->do_upload('file_2')) {
            // echo '<script>alert("File tidak sesuai. Pastikan File nya sudah benar. Jika perlu bantuan silahkan hubungi IT !");</script>';
            // redirect('all_upload/', 'refresh');
            // var_dump($this->upload->display_errors());
            $filename_2 = '';
        } else {

            $data = array('upload_data' => $this->upload->data());
            $file = $data['upload_data']['full_path'];
            chmod($file, 0777);

            //mengambil
            $upload_data = $this->upload->data();
            $filename_2 = $upload_data['file_name'];
            // $proses = $this->db->insert('db_temp.t_temp_file_sds', $file);
            // echo "filename : ".$filename;
        }

        if (!$this->upload->do_upload('file_3')) {
            // echo '<script>alert("File tidak sesuai. Pastikan File nya sudah benar. Jika perlu bantuan silahkan hubungi IT !");</script>';
            // redirect('all_upload/', 'refresh');
            // var_dump($this->upload->display_errors());
            $filename_3 = '';
        } else {

            $data = array('upload_data' => $this->upload->data());
            $file = $data['upload_data']['full_path'];
            chmod($file, 0777);

            //mengambil
            $upload_data = $this->upload->data();
            $filename_3 = $upload_data['file_name'];
            // $proses = $this->db->insert('db_temp.t_temp_file_sds', $file);
            // echo "filename : ".$filename;
        }

        // echo "filename_1 : ".$filename_1;
        // echo "filename_2 : ".$filename_2;
        // echo "filename_3 : ".$filename_3;

        $data = [
            "tanggal_kirim" => $tanggal_kirim,
            "file_1" => $filename_1,
            "file_2" => $filename_2,
            "file_3" => $filename_3,
            "signature_dc" => $signature,
            "created_at" => $created_at,
            "created_by" => $created_by,
        ];
        $this->db->where('signature_dc', $signature);
        $proses_update = $this->db->update('site.t_konfirmasi_pengiriman_dc', $data);

        echo '<script>alert("Terima kasih. Data anda sudah masuk.");</script>';
        redirect('dc/konfirmasi_dc/','refresh');

    }

    public function konfirmasi_penerimaan_dc($signature){

        // echo "signature : ".$signature;
        // die;

        $created_at = $this->model_outlet_transaksi->timezone();

        $cek = $this->m_dc->konfirmasi_pengiriman($signature)->num_rows();
        if ($cek) {

        }else{
            
            $data = [
                "signature_dc"  => $signature,
                "created_at"  => $created_at,
            ];

            $proses_insert = $this->db->insert('site.t_konfirmasi_pengiriman_dc', $data);

        }

        // die;

        $data = [
            'id'        => $this->session->userdata('id'),
            'title'     => 'DC | Konfirmasi Penerimaan Barang - SJ2',
            'url'       => 'dc/proses_konfirmasi_penerimaan_dc',
            'get_label' => $this->M_menu->get_label(),
            'konfirmasi_pengiriman'  => $this->m_dc->konfirmasi_pengiriman($signature)->result(),
        ];

        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar', $data);
        $this->load->view('template_claim/top_content', $data);
        $this->load->view('dc/konfirmasi_penerimaan_dc', $data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
    }

    public function proses_konfirmasi_penerimaan_dc(){

        $created_at = $this->model_outlet_transaksi->timezone();
        $created_by = $this->session->userdata('id');
        $signature = $this->input->post('signature');
        $tanggal_tiba = $this->input->post('tanggal_tiba');
        $file_4 = $this->input->post('file_4');


        // echo $tanggal_kirim;

        if (!is_dir('./assets/file/dc/')) {
            @mkdir('./assets/file/dc/', 0777);
        }

        //konfigurasi upload
        $config['upload_path'] = './assets/file/dc/';
        $config['allowed_types'] = '*';
        $config['max_size'] = '*';
        // $config['encrypt_name'] = TRUE;
        // $config['file_name'] = $site_code.$name.'_'.$time;

        $this->load->library('upload', $config);
        if (!$this->upload->do_upload('file_4')) {
            // echo '<script>alert("File tidak sesuai. Pastikan File nya sudah benar. Jika perlu bantuan silahkan hubungi IT !");</script>';
            // redirect('all_upload/', 'refresh');
            // var_dump($this->upload->display_errors());
            $filename_4 = '';
        } else {

            $data = array('upload_data' => $this->upload->data());
            $file = $data['upload_data']['full_path'];
            chmod($file, 0777);

            //mengambil
            $upload_data = $this->upload->data();
            $filename_4 = $upload_data['file_name'];
            // $proses = $this->db->insert('db_temp.t_temp_file_sds', $file);
            // echo "filename : ".$filename;
        }

        // echo "filename_1 : ".$filename_1;
        // echo "filename_2 : ".$filename_2;
        // echo "filename_3 : ".$filename_3;

        $data = [
            "tanggal_tiba" => $tanggal_tiba,
            "file_4" => $filename_4,
            "signature_dc" => $signature,
            "created_at" => $created_at,
            "created_by" => $created_by,
        ];
        $this->db->where('signature_dc', $signature);
        $proses_update = $this->db->update('site.t_konfirmasi_pengiriman_dc', $data);

        echo '<script>alert("Terima kasih. Data anda sudah masuk.");</script>';
        redirect('dc/konfirmasi_dc/','refresh');

    }


}
