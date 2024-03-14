<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Rtd extends MY_Controller
{
    function Rtd()
    {
        // $logged_in = $this->session->userdata('logged_in');
        // if (!isset($logged_in) || $logged_in != TRUE) {
        //     redirect('login/', 'refresh');
        // }
        set_time_limit(0);
        $this->load->library(array('table', 'template', 'Excel_generator', 'form_validation', 'email'));
        $this->load->helper('url');
        $this->load->helper('csv');
        $this->load->model('M_menu');
        $this->load->model(array('M_menu', 'model_rtd', 'model_outlet_transaksi', 'ModelDatabaseAfiliasi', 'model_inventory', 'model_rpd'));
        $this->load->database();
    }

    function index()
    {
        $this->proforma();
    }

    public function get_data()
    {
        $hak_akses = $this->model_biaya->hak_akses($this->session->userdata('id'));
        $id = $_GET['id'];
        $data['get_history']   = $this->model_rpd->get_history($id, $hak_akses)->row();
        echo json_encode($data);
    }

    public function proforma()
    {
        $get_kode_alamat = $this->model_inventory->get_kode_alamat();
        $code = '';
        foreach ($get_kode_alamat as $key) {
            $code .= "," . "'" . $key->kode_alamat . "'";
        }
        $kode_alamat = preg_replace('/,/', '', $code, 1);

        $data = [
            'id'        => $this->session->userdata('id'),
            'title'     => 'Proforma',
            'url'       => 'rtd/proses_proforma',
            'url_surat_jalan'       => 'rtd/proses_surat_jalan',
            'get_label' => $this->M_menu->get_label(),
            'get_data'  => $this->model_rtd->get_data('site.t_proforma')->result(),
            'get_data_by_ed'  => $this->model_rtd->get_data_by_ed()->result(),
            'get_data_by_batch_number'  => $this->model_rtd->get_data_by_batch_number()->result(),
            'get_data_surat_jalan'  => $this->model_rtd->get_data('site.t_surat_jalan')->result(),
            'get_data_mutasi'  => $this->model_rtd->get_data_mutasi()->result(),
        ];
        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar', $data);
        $this->load->view('template_claim/top_content', $data);
        $this->load->view('rtd/proforma', $data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
    }

    public function proses_proforma()
    {
        $created_at = $this->model_outlet_transaksi->timezone();
        $id = $this->input->post('id');
        $userid = $this->session->userdata('id');
        $ed = $this->input->post('ed');
        // $batch_number = $this->input->post('batch_number');
        $masuk = $this->input->post('unit');
        $keluar = 0;
        // $supp = $this->input->post('supp');
        // $kodeprod = $this->input->post('kodeprod');

        $signature = md5(str_replace('-', '', $created_at)) . "-" . md5($userid . $ed);
        $kode = $this->model_rtd->generate($userid, $ed);
        // echo "<pre>";
        // echo "supp : ".$supp;
        // echo "kodeprod : ".$kodeprod;
        // echo "created_at : ".$created_at;
        // echo "id : ".$id;
        // echo "userid : ".$userid;
        // echo "created_at : ".$created_at;
        // echo "ed : ".$ed;
        // echo "batch_number : ".$batch_number;
        // echo "unit : ".$unit;
        // echo "kode : ".$kode;
        // echo "</pre>";
        // die;

        $data = [
            "kode"          => $kode,
            "supp"          => $this->input->post('supp'),
            "kodeprod"      => $this->input->post('kodeprod_supp'),
            "unit"          => $masuk,
            "ed"            => $this->input->post('ed'),
            "batch_number"  => $this->input->post('batch_number'),
            "signature"     => $signature,
            "created_at"    => $created_at,
            "created_by"    => $userid
        ];

        if ($id == '') {
            $id_rtd = $this->model_rtd->insert('site.t_proforma', $data);
            // echo "id_rtd : " . $id_rtd;

            $get_saldo_awal = $this->model_rtd->get_saldo_awal($this->input->post('kodeprod_supp'))->row();
            $saldo_awal = $get_saldo_awal->total;
            // echo "saldo_awal : ".$saldo_awal;

            if ($saldo_awal) {
                $saldo_awal_params = $saldo_awal;
            }else{
                $saldo_awal_params = 0;
            }

            // echo "saldo_awal_params : ".$saldo_awal_params;
            // die;

            $data_mutasi = [
                "proforma_id"   => $id_rtd,
                "kodeprod"      => $this->input->post('kodeprod_supp'),
                "kode"          => $kode,
                "ed"            => $this->input->post('ed'),
                "kodeprod"      => $this->input->post('kodeprod_supp'),
                "batch_number"  => $this->input->post('batch_number'),
                "saldo_awal"    => $saldo_awal_params,
                "masuk"         => $masuk,
                "keluar"        => $keluar,
                "total"         => $this->model_rtd->get_total($saldo_awal_params,$masuk,$keluar),
                "created_at"    => $created_at,
                "created_by"    => $userid
            ];
            $id_proforma_mutasi = $this->model_rtd->insert('site.t_proforma_mutasi', $data_mutasi);
            // die;
            // $mutasi = $this->model_rtd->mutasi($id_rtd, 'in');

            redirect('rtd/proforma');
        } else {
        }
    }

    public function proses_surat_jalan()
    {
        $created_at = $this->model_outlet_transaksi->timezone();
        $id = $this->input->post('id');
        $userid = $this->session->userdata('id');
        // $id_do = $this->input->post('id_do');
        // $batch_number_text = $this->input->post('batch_number_text');
        $kodeprod = $this->input->post('kodeprod');
        $ed_text = $this->input->post('ed_text');
        $masuk = 0;
        $keluar = $this->input->post('unit');
        $signature = md5(str_replace('-', '', $created_at)) . "-" . md5($userid . $ed_text);
        $kode = $this->model_rtd->generate_surat_jalan($userid, $ed_text);

        //cek apakah total stock di gudang mencukupi ?
        $get_saldo_awal = $this->model_rtd->get_saldo_awal($kodeprod)->row();
        $saldo_awal = $get_saldo_awal->total;
        // echo "saldo_awal : ".$saldo_awal;

        if ($saldo_awal) {
            $saldo_awal_params = $saldo_awal;
        }else{
            $saldo_awal_params = 0;
        }

        // echo "saldo_awal : ".$saldo_awal;

        // die;

        if ($saldo_awal < $keluar) {
            echo '<script type="text/javascript">alert("input surat jalan gagal. Stock tidak mencukupi. Stock saat ini :'.$saldo_awal.'");</script>';
            redirect('rtd/proforma','refresh');
        }

        // die;
        

        $data = [
            "kode"          => $kode,
            "nopo"          => $this->input->post('id_po'),
            "supp"          => $this->model_rtd->get_supp($this->input->post('id_po')),
            "company"       => $this->model_rtd->get_company($this->input->post('id_po')),
            "alamat"        => $this->model_rtd->get_alamat($this->input->post('id_po')),
            "email"         => $this->model_rtd->get_email($this->input->post('id_po')),
            "signature"     => $signature,
            "created_at"    => $created_at,
            "created_by"    => $userid
        ];

        if ($id == '') {
            $id_surat_jalan = $this->model_rtd->insert('site.t_surat_jalan', $data);
            // echo "id_surat_jalan : " . $id_surat_jalan;
            // die;
            $data_detail = [
                "surat_jalan_id"=> $id_surat_jalan,
                "kode"          => $kode,
                "kodeprod"      => $kodeprod,
                "nodo"          => $this->input->post('id_do'),
                "ed"            => $this->input->post('ed'),
                "batch_number"  => $this->input->post('batch_number'),
                "unit"          => $keluar,
                "signature"     => md5($id_surat_jalan.$created_at),
                "created_at"    => $created_at,
                "created_by"    => $userid,
            ];

            $id_surat_jalan_detail = $this->model_rtd->insert('site.t_surat_jalan_detail', $data_detail);

            

            $data_mutasi = [
                "surat_jalan_id"=> $id_surat_jalan,
                "kode"          => $kode,
                "ed"            => $this->input->post('ed'),
                "kodeprod"      => $this->input->post('kodeprod'),
                "batch_number"  => $this->input->post('batch_number'),
                "saldo_awal"    => $saldo_awal_params,
                "masuk"         => $masuk,
                "keluar"        => $keluar,
                "total"         => $this->model_rtd->get_total($saldo_awal_params,$masuk,$keluar),
                "created_at"    => $created_at,
                "created_by"    => $userid
            ];
            $id_proforma_mutasi = $this->model_rtd->insert('site.t_proforma_mutasi', $data_mutasi);

            // echo "id_surat_jalan_detail : ".$id_surat_jalan_detail;

            // die;

            // $mutasi = $this->model_rtd->mutasi($id_surat_jalan_detail, 'out');

            // die;
            redirect('rtd/proforma');
        } else {
        }
    }

    public function generate_pdf($type, $signature)
    {
        $this->load->library('mypdf');

        // echo "type : " . $type;
        // echo "signature : " . $signature;

        if ($type == "surat_jalan") {
            $table = "site.t_surat_jalan";
        } else {
            $table = "site.t_proforma";
        }

        $get_data = $this->model_rtd->get_data($table)->result();
        // var_dump($get_data);
        // die;
        // $filename_pdf = $nodo;

        foreach ($get_data as $key) {
            $kode       = $key->kode;
            $alamat     = $key->alamat;
            $company    = $key->company;
            $email      = $key->email;
            $nopo       = $key->nopo;
            $created_at = $key->created_at;
            $id = $key->id;
        }

        $data = [
            "kode"      => $kode,
            "alamat"    => $alamat,
            "company"   => $company,
            "email"     => $email,
            "nopo"      => $nopo,
            "created_at"=> $created_at,
            "detail"    => $this->model_rtd->get_detail_surat_jalan($id)->result()

            // "detail" => $this->m_dc->get_kartu_masuk($nodo)->result(),
            // "get_discount" => $this->model_retur->get_discount($id),
            // "get_total" => $this->model_retur->get_total($id)
        ];

        $generate_pdf = $this->mypdf->generate('rtd/template_pdf_surat_jalan', $data, 'test', 'A4', 'portrait');
    }

    public function detail_surat_jalan($signature){
        echo "signature : ".$signature;

        $data = [
            'id'        => $this->session->userdata('id'),
            'title'     => 'Surat Jalan',
            'url'       => 'rtd/proses_detail_surat_jalan',
            'url_surat_jalan' => 'rtd/proses_surat_jalan',
            'get_label' => $this->M_menu->get_label(),
            'get_data'  => $this->model_rtd->get_data_surat_jalan()->result()
        ];
        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar', $data);
        $this->load->view('template_claim/top_content', $data);
        $this->load->view('rtd/detail_surat_jalan', $data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');

    }

    public function supp()
    {

        $curl = curl_init();
        $token = '11f3a8a682c1e8d097ae60d72ecf07c7';

        curl_setopt_array($curl, array(
            CURLOPT_URL => "http://site.muliaputramandiri.com/restapi/api/master_data/supp?token=$token&X-API-KEY=123",
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
            $datasupp = $array_response['data'];
            echo "<option value=''> -- Pilih Principal -- </option>";

            foreach ($datasupp as $key => $datasupp) {
                echo "<option value='" . $datasupp["SUPP"] . "' supp='" . $datasupp["SUPP"] . "' >";
                echo $datasupp["NAMASUPP"];
                echo "</option>";
            }
        }
    }


    public function get_po()
    {

        $curl = curl_init();
        $token = '11f3a8a682c1e8d097ae60d72ecf07c7';

        curl_setopt_array($curl, array(
            CURLOPT_URL => "http://site.muliaputramandiri.com/restapi/api/master_data/po?token=$token&X-API-KEY=123",
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
            $datapo = $array_response['data'];
            echo "<option value=''> -- Pilih PO -- </option>";

            foreach ($datapo as $key => $datapo) {
                echo "<option value='" . $datapo["nopo"] . "' id_po='" . $datapo["id_po"] . "' >";
                echo $datapo["nopo"] . ' - (' . $datapo["company"] . ')';
                echo "</option>";
            }
        }
    }

    public function get_kodeprod_supp()
    {

        $curl = curl_init();

        $token = '11f3a8a682c1e8d097ae60d72ecf07c7';
        $supp = $this->input->post('supp');

        curl_setopt_array($curl, array(
            // CURLOPT_URL => "http://site.muliaputramandiri.com/restapi/api/master_data/kabupaten?token=$token?id_provinsi = $id_provinsi",
            CURLOPT_URL => "http://site.muliaputramandiri.com/restapi/api/master_data/kodeprod_supp?&token=$token&supp=001&X-API-KEY=123",
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

            echo "<option value=''> -- Pilih Produk -- </option>";

            foreach ($datakodeprod as $key => $tiap_kodeprod) {
                echo "<option value='" . $tiap_kodeprod["KODEPROD"] . "' kodeprod='" . $tiap_kodeprod["KODEPROD"] . "' >";
                echo $tiap_kodeprod["KODEPROD"] . ' - ' . $tiap_kodeprod["NAMAPROD"];
                echo "</option>";
            }
        }
    }

    public function get_kodeprod_po()
    {

        $curl = curl_init();

        $token = '11f3a8a682c1e8d097ae60d72ecf07c7';
        $id_ref = $this->input->post('id_ref');
        // $id_ref = '116879';

        curl_setopt_array($curl, array(
            // CURLOPT_URL => "http://site.muliaputramandiri.com/restapi/api/master_data/kabupaten?token=$token?id_provinsi = $id_provinsi",
            CURLOPT_URL => "http://site.muliaputramandiri.com/restapi/api/master_data/kodeprod_po?&token=$token&id_ref=$id_ref&X-API-KEY=123",
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

            echo "<option value=''> -- Pilih Produk -- </option>";

            foreach ($datakodeprod as $key => $tiap_kodeprod) {
                echo "<option value='" . $tiap_kodeprod["kodeprod"] . "' kodeprod='" . $tiap_kodeprod["kodeprod"] . "' >";
                echo $tiap_kodeprod["kodeprod"] . ' - ' . $tiap_kodeprod["namaprod"] . ' - ' . $tiap_kodeprod["banyak"];
                echo "</option>";
            }
        }
    }

    public function get_do()
    {

        $curl = curl_init();

        $token = '11f3a8a682c1e8d097ae60d72ecf07c7';
        $nopo = $this->input->post('nopo');
        $kodeprod = $this->input->post('kodeprod');

        curl_setopt_array($curl, array(
            CURLOPT_URL => "http://site.muliaputramandiri.com/restapi/api/master_data/do?&token=$token&nopo=$nopo&X-API-KEY=123&kodeprod=$kodeprod",
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

            echo "<option value=''> -- Pilih DO -- </option>";

            foreach ($datado as $key => $tiap_do) {
                echo "<option value='" . $tiap_do["nodo"] . "' nodo='" . $tiap_do["nodo"] . "' >";
                echo $tiap_do["nodo"] . ' - ' . $tiap_do["qty"];
                echo "</option>";
            }
        }
    }

    public function get_ed()
    {

        $curl = curl_init();

        $token = '11f3a8a682c1e8d097ae60d72ecf07c7';
        $kodeprod = $this->input->post('kodeprod');

        curl_setopt_array($curl, array(
            CURLOPT_URL => "http://site.muliaputramandiri.com/restapi/api/master_data/ed?&token=$token&X-API-KEY=123&kodeprod=$kodeprod",
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
            $dataed = $array_response['data'];

            echo "<option value=''> -- Pilih ED -- </option>";

            foreach ($dataed as $key => $tiap_ed) {
                echo "<option value='" . $tiap_ed["ed"] . "' ed='" . $tiap_ed["ed"] . "' >";
                echo $tiap_ed["ed"] . ' - ' . $tiap_ed["sisa"];
                echo "</option>";
            }
        }
    }
    public function get_batch_number()
    {

        $curl = curl_init();

        $token = '11f3a8a682c1e8d097ae60d72ecf07c7';
        $ed = $this->input->post('ed');

        curl_setopt_array($curl, array(
            CURLOPT_URL => "http://site.muliaputramandiri.com/restapi/api/master_data/batch_number?&token=$token&X-API-KEY=123&ed=$ed",
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
            $databatchnumber = $array_response['data'];

            echo "<option value=''> -- Pilih Batch Number -- </option>";

            foreach ($databatchnumber as $key => $tiap_batch) {
                echo "<option value='" . $tiap_batch["batch_number"] . "' batch_number='" . $tiap_batch["batch_number"] . "' >";
                echo $tiap_batch["batch_number"] . ' - ' . $tiap_batch["unit"];
                echo "</option>";
            }
        }
    }

    public function import_sales()
    {
        $created_at = $this->model_outlet_transaksi->timezone();
        $this->load->library('excel');

        echo "created_at : ".$created_at;
        echo "<br><br>";

        // get tanggal hari ini
        $format_filename = date('ymd') . ".csv";

        // echo $format_filename;
        // die;
        $format_filename = "221031.csv";
        // $object = PHPExcel_IOFactory::load($file);
        $object = PHPExcel_IOFactory::load("assets/supralita/sales/$format_filename");

        $jumlahSheet = $object->getSheetCount();

        foreach ($object->getWorksheetIterator() as $worksheet) {
            $highestRow = $worksheet->getHighestRow();
            $highestColumn = $worksheet->getHighestColumn();
            for ($row = 2; $row <= $highestRow; $row++) {
                // $kd_cabang = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
                $siteid = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
                $no_faktur = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
                $tanggal_faktur = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
                $tanggal_tempo = $worksheet->getCellByColumnAndRow(4, $row)->getValue();
                $salesmanid = $worksheet->getCellByColumnAndRow(5, $row)->getValue();
                $nama_salesman = $worksheet->getCellByColumnAndRow(6, $row)->getValue();
                $divisiid = $worksheet->getCellByColumnAndRow(7, $row)->getValue();
                $nama_divisi = $worksheet->getCellByColumnAndRow(8, $row)->getValue();
                $prinsipalid = $worksheet->getCellByColumnAndRow(9, $row)->getValue();
                $nama_prinsipal = $worksheet->getCellByColumnAndRow(10, $row)->getValue();
                $groupid_product1 = $worksheet->getCellByColumnAndRow(11, $row)->getValue();
                $product_group1 = $worksheet->getCellByColumnAndRow(12, $row)->getValue();
                $groupid_product2 = $worksheet->getCellByColumnAndRow(13, $row)->getValue();
                $product_group2 = $worksheet->getCellByColumnAndRow(14, $row)->getValue();
                $productid = $worksheet->getCellByColumnAndRow(15, $row)->getValue();
                $nama_invoice = $worksheet->getCellByColumnAndRow(16, $row)->getValue();
                $ket = $worksheet->getCellByColumnAndRow(17, $row)->getValue();
                $qty_kecil = $worksheet->getCellByColumnAndRow(18, $row)->getValue();
                $qty_bonus = $worksheet->getCellByColumnAndRow(19, $row)->getValue();
                $rp_kotor = $worksheet->getCellByColumnAndRow(20, $row)->getValue();
                $rp_discount = $worksheet->getCellByColumnAndRow(21, $row)->getValue();
                $rp_netto = $worksheet->getCellByColumnAndRow(22, $row)->getValue();
                $customerid = $worksheet->getCellByColumnAndRow(23, $row)->getValue();
                $nama_customer = $worksheet->getCellByColumnAndRow(24, $row)->getValue();
                $alamat = $worksheet->getCellByColumnAndRow(25, $row)->getValue();
                $segmentid = $worksheet->getCellByColumnAndRow(26, $row)->getValue();
                $nama_segment = $worksheet->getCellByColumnAndRow(27, $row)->getValue();
                $typeid = $worksheet->getCellByColumnAndRow(28, $row)->getValue();
                $nama_type = $worksheet->getCellByColumnAndRow(29, $row)->getValue();
                $classid = $worksheet->getCellByColumnAndRow(30, $row)->getValue();
                $nama_class = $worksheet->getCellByColumnAndRow(31, $row)->getValue();
                $propinsiid = $worksheet->getCellByColumnAndRow(32, $row)->getValue();
                $nama_propinsi = $worksheet->getCellByColumnAndRow(33, $row)->getValue();
                $kotaid = $worksheet->getCellByColumnAndRow(34, $row)->getValue();
                $nama_kota = $worksheet->getCellByColumnAndRow(35, $row)->getValue();
                $kecamatanid = $worksheet->getCellByColumnAndRow(36, $row)->getValue();
                $nama_kecamatan = $worksheet->getCellByColumnAndRow(37, $row)->getValue();
                $kelurahanid = $worksheet->getCellByColumnAndRow(38, $row)->getValue();
                $nama_kelurahan = $worksheet->getCellByColumnAndRow(39, $row)->getValue();
                $regionalid = $worksheet->getCellByColumnAndRow(40, $row)->getValue();
                $nama_regional = $worksheet->getCellByColumnAndRow(41, $row)->getValue();
                $areaid = $worksheet->getCellByColumnAndRow(42, $row)->getValue();
                $nama_area = $worksheet->getCellByColumnAndRow(43, $row)->getValue();
                $subareaid = $worksheet->getCellByColumnAndRow(44, $row)->getValue();
                $subarea = $worksheet->getCellByColumnAndRow(45, $row)->getValue();
                $rp_discount_cabang = $worksheet->getCellByColumnAndRow(46, $row)->getValue();
                $rp_discount_prinsipal = $worksheet->getCellByColumnAndRow(47, $row)->getValue();
                $rp_cbp = $worksheet->getCellByColumnAndRow(48, $row)->getValue();
                $rp_hna = $worksheet->getCellByColumnAndRow(49, $row)->getValue();
                $tipe_sales = $worksheet->getCellByColumnAndRow(50, $row)->getValue();
                $tax_value = $worksheet->getCellByColumnAndRow(51, $row)->getValue();
                $location_no = $worksheet->getCellByColumnAndRow(52, $row)->getValue();
                $location_name = $worksheet->getCellByColumnAndRow(53, $row)->getValue();
                $source_data = $worksheet->getCellByColumnAndRow(54, $row)->getValue();
                $salesmanid_trans = $worksheet->getCellByColumnAndRow(55, $row)->getValue();
                $nama_salesman_trans = $worksheet->getCellByColumnAndRow(56, $row)->getValue();

                $data = [
                    'siteid'            => $siteid,
                    'no_faktur'         => $no_faktur,
                    'tanggal_faktur'    => $tanggal_faktur,
                    'tanggal_tempo'     => $tanggal_tempo,
                    'salesmanid'        => $salesmanid,
                    'nama_salesman'     => $nama_salesman,
                    'divisiid'          => $divisiid,
                    'nama_divisi'       => $nama_divisi,
                    'prinsipalid'       => $prinsipalid,
                    'nama_prinsipal'    => $nama_prinsipal,
                    'groupid_product1'  => $groupid_product1,
                    'product_group1'    => $product_group1,
                    'groupid_product2'  => $groupid_product2,
                    'product_group2'    => $product_group2,
                    'productid'         => $productid,
                    'nama_invoice'      => $nama_invoice,
                    'ket'               => $ket,
                    'qty_kecil'         => $qty_kecil,
                    'qty_bonus'         => $qty_bonus,
                    'rp_kotor'          => $rp_kotor,
                    'rp_discount'       => $rp_discount,
                    'rp_netto'          => $rp_netto,
                    'customerid'        => $customerid,
                    'nama_customer'     => $nama_customer,
                    'alamat'        =>    $alamat,
                    'segmentid'            =>    $segmentid,
                    'nama_segment'            =>    $nama_segment,
                    'typeid'                =>    $typeid,
                    'nama_type'                =>    $nama_type,
                    'classid'                =>    $classid,
                    'nama_class'                =>    $nama_class,
                    'propinsiid'                =>    $propinsiid,
                    'nama_propinsi'                =>    $nama_propinsi,
                    'kotaid'                =>    $kotaid,
                    'nama_kota'                =>    $nama_kota,
                    'kecamatanid'                =>    $kecamatanid,
                    'nama_kecamatan'                =>    $nama_kecamatan,
                    'kelurahanid'                =>    $kelurahanid,
                    'nama_kelurahan'                =>    $nama_kelurahan,
                    'regionalid'                =>    $regionalid,
                    'nama_regional'                =>    $nama_regional,
                    'areaid'                =>    $areaid,
                    'nama_area'                =>    $nama_area,
                    'subareaid'                =>    $subareaid,
                    'subarea'                =>    $subarea,
                    'rp_discount_cabang'                =>    $rp_discount_cabang,
                    'rp_discount_prinsipal'                =>    $rp_discount_prinsipal,
                    'rp_cbp'                =>    $rp_cbp,
                    'rp_hna'                =>    $rp_hna,
                    'tipe_sales'                =>    $tipe_sales,
                    'tax_value'                =>    $tax_value,
                    'location_no'                =>    $location_no,
                    'location_name'                =>    $location_name,
                    'source_data'                =>    $source_data,
                    'salesmanid_trans'                =>    $salesmanid_trans,
                    'nama_salesman_trans'                =>    $nama_salesman_trans,
                    'filename'          =>    $format_filename,
                    'created_at'        =>    $created_at,
                ];

                $this->db->insert('site.temp_raw_sales_rtd_new', $data);
            }
        }

        // die;

        $update_kodeprod = "
            update site.temp_raw_sales_rtd_new a 
            set a.productid = concat('0', a.productid)
            where length(a.productid) = 5 and a.created_at = '$created_at'
        ";
        $proses_update_kodeprod = $this->db->query($update_kodeprod);

        // die;

        $this->import_sales_to_transaction($created_at);
    }

    public function import_sales_to_transaction($created_at)
    {
        $sql = "
        insert into site.t_raw_sales_rtd
        select 		'', a.id, a.no_faktur as nodokjdi, STR_TO_DATE(a.tanggal_faktur,'%m-%d-%Y') as tgldokjdi, 
				a.customerid as kode_lang, a.nama_customer as nama_lang,
				'001' as supp, b.kode_comp, b.nocab, c.kode_type_mpm as kode_type, a.classid as kodesalur,
				 f.jenis as namasalur, 
				trim(a.salesmanid) as kodesales, a.nama_salesman as namasales, e.kodeprod, 
				e.namaprod, '' as qty1, '' as qty2, '' as qty3, 
				a.rp_hna, a.qty_kecil as banyak, a.rp_kotor as tot1, a.rp_discount, a.rp_netto as netto,
				day(STR_TO_DATE(a.tanggal_faktur,'%m-%d-%Y')) as hrdok,
				month(STR_TO_DATE(a.tanggal_faktur,'%m-%d-%Y')) as blndok, 
				year(STR_TO_DATE(a.tanggal_faktur,'%m-%d-%Y')) as thndok, 
				concat(b.kode_comp, b.nocab) as siteid,  '$created_at'
        from site.temp_raw_sales_rtd_new a left join 
        (
            select a.kd_cabang, a.kode_comp, a.nocab
            from site.map_rtd_site_code a    
            where a.status_aktif = 1
        )b on a.siteid = b.kd_cabang left join (
            select a.cek_type, a.kode_type_mpm, a.nama_type_mpm, a.sektor_mpm, a.segment_mpm
            from site.map_rtd_type_new a
        )c on concat(a.nama_type, a.segmentid, a.nama_segment) = c.cek_type /*left join (
            select a.produk_id, a.kodeprod
            from site.map_rtd_product a
            where a.status_aktif = 1
        )d on a.produk_id = d.produk_id */left join 
        (
            select a.kodeprod, a.namaprod
            from mpm.tabprod a 
        )e on a.productid = e.kodeprod LEFT JOIN 
        (
            select a.kode, a.jenis
            from mpm.tbl_tabsalur a 
        )f on a.classid = f.kode
        where a.created_at = '$created_at'
        ";
        echo "<pre>";
        print_r($sql);
        echo "</pre>";
        
        $proses = $this->db->query($sql);

        // die;

        $update_hrdok = "
            update site.t_raw_sales_rtd a 
            set a.hrdok = concat('0',a.hrdok)
            where length(a.hrdok) = 1 and a.created_at = '$created_at'
        ";
        echo "<pre>";
        print_r($update_hrdok);
        echo "</pre>";
        $proses_update_hrdok = $this->db->query($update_hrdok);

        $update_blndok = "
            update site.t_raw_sales_rtd a 
            set a.blndok = concat('0',a.blndok)
            where length(a.blndok) = 1 and a.created_at = '$created_at'
        ";
        $proses_update_blndok = $this->db->query($update_blndok);

        // die;

        $update_type_final = "
        update site.t_raw_sales_rtd a INNER JOIN 
        (
            select a.cust_id_mpm
            from site.map_rtd_type_new_final a
            group by a.cust_id_mpm
        )c on concat(a.kode_comp, a.kode_lang)  = c.cust_id_mpm
        set a.kode_type = (
            select b.new_type_final
            from site.map_rtd_type_new_final b
            where concat(a.kode_comp, a.kode_lang)  = b.cust_id_mpm 
            group by b.cust_id_mpm
        )
        where a.created_at = '$created_at' 
        ";
        $proses_update_type_final = $this->db->query($update_type_final);

        $this->delete_master($created_at);
    }

    public function delete_master($created_at)
    {
        $get_nocab = "
            select a.nocab, a.blndok, a.thndok
            from site.t_raw_sales_rtd a
            where a.created_at = '$created_at'
            group by a.nocab
        ";
        $proses_get_nocab = $this->db->query($get_nocab)->result();

        foreach ($proses_get_nocab as $key) {
            $nocab = $key->nocab;
            $bulan = $key->blndok;
            $thndok = $key->thndok;
            // echo "nocab : " . $nocab;

            // hapus salesman di data2022.tabsales
            $delete_nocab = "
                delete from data$thndok.tabsales
                where nocab = '$nocab'
            ";
            echo "<pre>";
            print_r($delete_nocab);
            echo "</pre>";
            $proses_delete_nocab = $this->db->query($delete_nocab);

            // hapus outlet di data2022.tblang
            $delete_outlet = "
                delete from data$thndok.tblang
                where nocab = '$nocab'
            ";
            echo "<pre>";
            print_r($delete_outlet);
            echo "</pre>";
            $proses_delete_outlet = $this->db->query($delete_outlet);

            $delete_transaksi = "
                delete from data$thndok.fi 
                where bulan = $bulan and nocab = '$nocab'
            ";
            echo "<pre>";
            print_r($delete_transaksi);
            echo "</pre>";
            $proses_delete_transaksi = $this->db->query($delete_transaksi);

            $delete_retur = "
                delete from data$thndok.ri 
                where bulan = $bulan and nocab = '$nocab'
            ";
            $proses_delete_retur = $this->db->query($delete_retur);
        }

        $this->update_salesman($created_at);
    }

    public function update_salesman($created_at)
    {
        $get_salesman = "
            select a.kodesales, a.namasales, a.nocab, a.thndok
            from site.t_raw_sales_rtd a
            where a.created_at = '$created_at'
            group by concat(a.kodesales, a.nocab)
        ";
        $proses_get_salesman = $this->db->query($get_salesman)->result();

        foreach ($proses_get_salesman as $key) {
            $kodesales = $key->kodesales;
            $namasales = $key->namasales;
            $nocab = $key->nocab;
            $thndok = $key->thndok;

            $insert_salesman = "insert into data$thndok.tabsales (kodesales, namasales, nocab) values ('$kodesales', '$namasales', '$nocab')";
            $proses_insert_salesman = $this->db->query($insert_salesman);
        }

        $this->update_outlet($created_at);
    }

    public function update_outlet($created_at)
    {
        $get_outlet = "
            select a.kode_comp, a.kode_lang, a.nama_lang, a.nocab, a.thndok
            from site.t_raw_sales_rtd a
            where a.created_at = '$created_at'
            group by concat(a.kode_comp, a.kode_lang)
        ";
        $proses_get_outlet = $this->db->query($get_outlet)->result();

        foreach ($proses_get_outlet as $key) {
            $kode_comp = $key->kode_comp;
            $kode_lang = $key->kode_lang;
            // $nama_lang = $key->nama_lang;
            $nama_lang = str_replace('"','', $key->nama_lang);
            $nocab = $key->nocab;
            $thndok = $key->thndok;

            $insert_outlet = 'insert into data'.$thndok.'.tblang (kode_comp, kode_lang, nama_lang, nocab) values ('.'"'.$kode_comp.'"'.','.'"'.$kode_lang.'"'.','.'"'.$nama_lang.'"'.','.'"'.$nocab.'"'.')';
            $proses_insert_outlet = $this->db->query($insert_outlet);
        }

        $this->update_transaksi_faktur($created_at);
    }

    // public function update_type($created_at){
    //     $sql = "
    //         update site.t_raw_sales_rtd a 
    //         set a.
    //     ";
    // }

    public function update_transaksi_faktur($created_at)
    {
        $get_transaksi = "
            select * 
            from site.t_raw_sales_rtd a
            where a.created_at = '$created_at' 
        ";

        $proses_get_transaksi = $this->db->query($get_transaksi)->result();

        foreach ($proses_get_transaksi as $key) {
            $nodokjdi = $key->nodokjdi;
            $tgldokjdi = $key->tgldokjdi;
            $kode_lang = $key->kode_lang;
            // $nama_lang = $key->nama_lang;
            $nama_lang = str_replace('"','', $key->nama_lang);
            $supp = $key->supp;
            $kode_comp = $key->kode_comp;
            $nocab = $key->nocab;
            $kode_type = $key->kode_type;
            $kodesalur = $key->kodesalur;
            $namasalur = $key->namasalur;
            $kodesales = $key->kodesales;
            $namasales = $key->namasales;
            $kodeprod = $key->kodeprod;
            $namaprod = $key->namaprod;
            $qty1 = $key->qty1;
            $qty2 = $key->qty2;
            $qty3 = $key->qty3;
            $harga = $key->harga;
            $banyak = $key->banyak;
            $tot1 = $key->tot1;
            $hrdok = $key->hrdok;
            $blndok = $key->blndok;
            $thndok = $key->thndok;
            $siteid = $key->siteid;
            $kode_kota = '';
            $potongan = $key->potongan;

            $insert_fi = 'insert into data'.$thndok.'.fi (nodokjdi, tgldokjdi, kodesales, kode_comp, kode_kota, kode_type, kode_lang, nama_lang, kodeprod, namaprod, qty1, qty2, qty3, harga, hrdok, blndok, thndok, banyak, potongan, tot1, kodesalur, nocab, bulan, siteid, supp) values ('.'"'.$nodokjdi.'"'.', '.'"'.$tgldokjdi.'"'.', '.'"'.$kodesales.'"'.', '.'"'.$kode_comp.'"'.', '.'"'.$kode_kota.'"'.', '.'"'.$kode_type.'"'.', '.'"'.$kode_lang.'"'.', '.'"'.$nama_lang.'"'.', '.'"'.$kodeprod.'"'.', '.'"'.$namaprod.'"'.', '.'"'.$qty1.'"'.', '.'"'.$qty2.'"'.', '.'"'.$qty3.'"'.', '.'"'.$harga.'"'.', '.'"'.$hrdok.'"'.', '.'"'.$blndok.'"'.', '.'"'.$thndok.'"'.', '.'"'.$banyak.'"'.', '.'"'.$potongan.'"'.', '.'"'.$tot1.'"'.', '.'"'.$kodesalur.'"'.', '.'"'.$nocab.'"'.', '.'"'.$blndok.'"'.', '.'"'.$siteid.'"'.', '.'"'.$supp.'"'.')';

            $proses_insert = $this->db->query($insert_fi);
        }
    }
}
