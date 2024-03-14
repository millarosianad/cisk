<?php

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Management_raw extends MY_Controller
{    
    function management_raw()
    {       
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
        set_time_limit(0);
        $this->load->library(array('table', 'template', 'Excel_generator', 'form_validation', 'email', 'zip'));
        $this->load->helper(array('url', 'csv'));
        $this->load->model(array('model_outlet_transaksi','model_management_raw'));
    }
    function index()
    {
        $this->banjarmasin();
    }

    private function template($view,$data)
    {
        $this->template->set_title('MPM SQUARE');
        $this->template->add_js('modules/skeleton.js');
        $this->template->add_css('modules/skeleton.css');
        $this->template->load_view($view, $data);
    }

    public function banjarmasin(){

        $data = [
            'title'     => 'Management Raw / Import data banjarmasin',
            'id'        => $this->session->userdata('id'),
            'url_import'=> 'management_raw/import_banjarmasin',
            'get_log_upload'    => $this->model_management_raw->get_log_upload('SSMS9')
        ];
        $this->load->view('management_raw/header');
        $this->load->view('management_raw/banjarmasin', $data);
        $this->load->view('mes/footer');
    }

    public function template_banjarmasin(){        
        $query = "
            select  '' as distributor, '' as cabang, '' as tipetrans, '' as divisi, '' as principal, '' as productgroup1, '' as productgroup2, '' as productgroup3, '' as brand, '' as kodeproduk, '' as kodevarian, '' as kodeprodukprincipal, '' as namaproduk, '' as packaging, '' as productclass, '' as kodecustomer,'' as namacustomer, '' as alamatcustomer, '' as area, '' as subarea, '' as channel, '' as subchannel, '' as customergroup, '' as keyaccount, '' as kodesalesman, '' as namasalesman, '' as kodesalesco, '' as namasalesco, '' as kodespv, '' as namaspv, '' as tahunbulan, '' as bulan, '' as tanggal, '' as weekno, '' as nomornota, '' as salesmethod, '' as sellingtype, '' as qtysold, '' as qtysoldcrt, '' as qtysolduom1, '' as qtysolduom2, '' as qtysolduom3, '' as qtysolduom4, '' as qtysoldtotalpcs, '' as freegoodtotalpcs, '' as tonnage, '' as volumeltr, '' as grossamount, '' as linediscount1, '' as linediscount2, '' as linediscount3, '' as linediscount4, '' as linediscount5, '' as totallinediscount, '' as discountnota1, '' as discountnota2, '' as discountnota3, '' as totaldiscountnota, '' as dpp, '' as ppn, '' as ppnbm, '' as tax3, '' as netamount, '' as warehouse, '' as customerpo, '' as customerjoindate, '' as nofakturpajak, '' as tglfakturpajak, '' as nomorfakturproforma, '' as tglfakturproforma, '' as term, '' as uom1, '' as uom2, '' as uom3, '' as uom4, '' as isiuom1, '' as isiuom2, '' as isiuom3, '' as sellingprice, '' as cogs, '' as sellingpriceinkg, '' as caseweightinkg, '' as qtyordertotalpcs, '' as end
        ";

        $hsl = $this->db->query($query);
        query_to_csv($hsl,TRUE,'Template_Banjarmasin.csv');
    }

    public function barabai(){

        $data = [
            'title'     => 'Management Raw / Import data barabai',
            'id'        => $this->session->userdata('id'),
            'url_import'=> 'management_raw/import_barabai',
            'get_log_upload'    => $this->model_management_raw->get_log_upload('BRBS0')
        ];
        $this->load->view('management_raw/header');
        $this->load->view('management_raw/barabai', $data);
        $this->load->view('mes/footer');
    }

    public function template_barabai(){        
        $query = "
            select  '' as distributor, '' as cabang, '' as tipetrans, '' as divisi, '' as principal, '' as productgroup1, '' as productgroup2, '' as productgroup3, '' as brand, '' as kodeproduk, '' as kodevarian, '' as kodeprodukprincipal, '' as namaproduk, '' as packaging, '' as productclass, '' as kodecustomer,'' as namacustomer, '' as alamatcustomer, '' as area, '' as subarea, '' as channel, '' as subchannel, '' as customergroup, '' as keyaccount, '' as kodesalesman, '' as namasalesman, '' as kodesalesco, '' as namasalesco, '' as kodespv, '' as namaspv, '' as tahunbulan, '' as bulan, '' as tanggal, '' as weekno, '' as nomornota, '' as salesmethod, '' as sellingtype, '' as qtysold, '' as kartonutuh, '' as qtysoldpcs, '' as freegoodpcs, '' as tonnage, '' as volumeltr, '' as grossamount, '' as linediscount1, '' as linediscount2, '' as linediscount3, '' as linediscount4, '' as linediscount5, '' as totallinediscount, '' as discountnota1, '' as discountnota2, '' as discountnota3, '' as totaldiscountnota, '' as dpp, '' as ppn, '' as ppnbm, '' as tax3, '' as netamount, '' as warehouse, '' as customerpo, '' as customerjoindate, '' as nofakturpajak, '' as tglfakturpajak, '' as nomorfakturproforma, '' as tglfakturproforma, '' as cogs, '' as caseweightinkg, '' as end
        ";

        $hsl = $this->db->query($query);
        query_to_csv($hsl,TRUE,'Template_Barabai.csv');
    }

    public function import_barabai(){
        // $bulan = $this->input->post('bulan');
        // echo "bulan : ".$bulan;

        $bulan_from_input = substr($this->input->post('bulan'), 5, 2);
        $tahun_from_input = substr($this->input->post('bulan'), 0, 4);

        $cek_last_upload = "
            select *
            from mpm.upload a 
            where a.userid = 703 
            ORDER BY a.id desc
        ";
        $tahun_last_upload = $this->db->query($cek_last_upload)->row()->tahun;
        $bulan_last_upload = $this->db->query($cek_last_upload)->row()->bulan;
        $closing_last_upload = $this->db->query($cek_last_upload)->row()->status_closing;

        if ($closing_last_upload == 1) {
            $tanggal_seharusnya = date('Y-m-d', strtotime('+1 month', strtotime($tahun_last_upload.'-'.$bulan_last_upload.'-01')));           
            $bulan_seharusnya = date('m', strtotime($tanggal_seharusnya));
            $tahun_seharusnya = date('Y', strtotime($tanggal_seharusnya));

            if ($tahun_from_input === $tahun_seharusnya && $bulan_from_input === $bulan_seharusnya) {
                
            }else{
                echo "<br><hr>Tanggal yang anda pilih adalah :<br><br>";
                // echo "<hr>";
                echo "tahun : ".$tahun_from_input."<br>";
                echo "bulan : ".$bulan_from_input."<br><br>";
                echo "<hr>";

                echo "<br>Tanggal upload terakhir adalah :<br><br>";
                // echo "<hr>";
                echo "tahun_last_upload : ".$tahun_last_upload."<br>";
                echo "bulan_last_upload : ".$bulan_last_upload."<br>";
                echo "closing_last_upload : ".$closing_last_upload."<br><br>";
                echo "<hr>";
                
                echo "<h3>Result</h3>";
                echo "Data ditolak !!<br><br>";
                echo "Anda hanya diijinkan mengupload data untuk <b>tahun : $tahun_seharusnya</b>, dan <b>bulan : $bulan_seharusnya</b><br>";

                echo "<br>anda akan di redirect ke menu awal dalam 10 detik";
                header('Refresh: 10; URL='.base_url().'management_raw/barabai');
                die;
            }

        }else{

            $tanggal_seharusnya = date('Y-m-d', strtotime('+0 month', strtotime($tahun_last_upload.'-'.$bulan_last_upload.'-01')));           
            $bulan_seharusnya = date('m', strtotime($tanggal_seharusnya));
            $tahun_seharusnya = date('Y', strtotime($tanggal_seharusnya));

            if ($tahun_from_input === $tahun_seharusnya && $bulan_from_input === $bulan_seharusnya) {
                // echo "diijinkan";
            }else{
                echo "<br><hr>Tanggal yang anda pilih adalah :<br><br>";
                echo "tahun : ".$tahun_from_input."<br>";
                echo "bulan : ".$bulan_from_input."<br><br>";
                echo "<hr>";


                echo "<br>Tanggal upload terakhir adalah :<br><br>";
                echo "tahun_last_upload : ".$tahun_last_upload."<br>";
                echo "bulan_last_upload : ".$bulan_last_upload."<br>";
                echo "closing_last_upload : ".$closing_last_upload."<br><br>";
                echo "<hr>";
                
                echo "<h3>Result</h3>";
                echo "Data ditolak !!<br><br>";
                echo "Anda hanya diijinkan mengupload data untuk <b>tahun : $tahun_seharusnya</b>, dan <b>bulan : $bulan_seharusnya</b><br>";

                echo "<br>anda akan di redirect ke menu awal dalam 10 detik";
                header('Refresh: 10; URL='.base_url().'management_raw/barabai');
                die;
            }

        }

        if (!is_dir('./assets/uploads/management_raw/import/')) {
            @mkdir('./assets/uploads/management_raw/import/', 0777);
        }

        $this->load->library('upload'); // Load librari upload        
        $config['upload_path'] = './assets/uploads/management_raw/import/';
        // $config['allowed_types'] = 'xls|xlsx|csv';
        $config['allowed_types'] = '*';    
        $config['max_size']  = '2048000';
        $config['overwrite'] = true;
        $config['encrypt_name'] = TRUE;
        $this->upload->initialize($config);

        if ($this->upload->do_upload('file')) 
        {

            $upload_data = $this->upload->data();
            $filename = $upload_data['orig_name'];

            $this->load->library('excel');
            $object = PHPExcel_IOFactory::load("assets/uploads/management_raw/import/$filename");

            $jumlahSheet = $object->getSheetCount();
            if ($jumlahSheet > 1) {
                echo "jumlah_sheet : ".$jumlahSheet;
                echo "<script>alert('upload file gagal karena file mempunyai lebih dari 1 sheet'); </script>";
                redirect('management_raw','refresh');
            }

            // die;

            $created_at = $this->model_outlet_transaksi->timezone();
            $signature = md5($this->model_outlet_transaksi->timezone());

            foreach ($object->getWorksheetIterator() as $worksheet) {

                $highestRow = $worksheet->getHighestRow();
                $highestColumn = $worksheet->getHighestColumn();

                for ($row = 2; $row <= $highestRow; $row++) {          
                
                    $distributor = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
                    $cabang = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
                    $tipetrans = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
                    $divisi = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
                    $principal = $worksheet->getCellByColumnAndRow(4, $row)->getValue();
                    $productgroup1 = $worksheet->getCellByColumnAndRow(5, $row)->getValue();
                    $productgroup2 = $worksheet->getCellByColumnAndRow(6, $row)->getValue();
                    $productgroup3 = $worksheet->getCellByColumnAndRow(7, $row)->getValue();
                    $brand = $worksheet->getCellByColumnAndRow(8, $row)->getValue();
                    $kodeproduk = $worksheet->getCellByColumnAndRow(9, $row)->getValue();
                    $kodevarian = $worksheet->getCellByColumnAndRow(10, $row)->getValue();
                    $kodeprodukprincipal = $worksheet->getCellByColumnAndRow(11, $row)->getValue();
                    $namaproduk = $worksheet->getCellByColumnAndRow(12, $row)->getValue();
                    $packaging = $worksheet->getCellByColumnAndRow(13, $row)->getValue();
                    $productclass = $worksheet->getCellByColumnAndRow(14, $row)->getValue();
                    $kodecustomer = $worksheet->getCellByColumnAndRow(15, $row)->getValue();
                    $namacustomer = $worksheet->getCellByColumnAndRow(16, $row)->getValue();
                    $alamatcustomer = $worksheet->getCellByColumnAndRow(17, $row)->getValue();
                    $area = $worksheet->getCellByColumnAndRow(18, $row)->getValue();
                    $subarea = $worksheet->getCellByColumnAndRow(19, $row)->getValue();
                    $channel = $worksheet->getCellByColumnAndRow(20, $row)->getValue();
                    $subchannel = $worksheet->getCellByColumnAndRow(21, $row)->getValue();
                    $customergroup = $worksheet->getCellByColumnAndRow(22, $row)->getValue();
                    $keyaccount = $worksheet->getCellByColumnAndRow(23, $row)->getValue();
                    $kodesalesman = $worksheet->getCellByColumnAndRow(24, $row)->getValue();
                    $namasalesman = $worksheet->getCellByColumnAndRow(25, $row)->getValue();
                    $kodesalesco = $worksheet->getCellByColumnAndRow(26, $row)->getValue();
                    $namasalesco = $worksheet->getCellByColumnAndRow(27, $row)->getValue();
                    $kodespv = $worksheet->getCellByColumnAndRow(28, $row)->getValue();
                    $namaspv = $worksheet->getCellByColumnAndRow(29, $row)->getValue();
                    $tahunbulan = $worksheet->getCellByColumnAndRow(30, $row)->getValue();
                    $bulan = $worksheet->getCellByColumnAndRow(31, $row)->getValue();
                    $tanggal = $worksheet->getCellByColumnAndRow(32, $row)->getValue();
                    $weekno = $worksheet->getCellByColumnAndRow(33, $row)->getValue();
                    $nomornota = $worksheet->getCellByColumnAndRow(34, $row)->getValue();
                    $salesmethod = $worksheet->getCellByColumnAndRow(35, $row)->getValue();
                    $sellingtype = $worksheet->getCellByColumnAndRow(36, $row)->getValue();
                    $qtysold = $worksheet->getCellByColumnAndRow(37, $row)->getValue();
                    $kartonutuh = $worksheet->getCellByColumnAndRow(38, $row)->getValue();
                    $qtysoldpcs = $worksheet->getCellByColumnAndRow(39, $row)->getValue();
                    $freegoodpcs = $worksheet->getCellByColumnAndRow(40, $row)->getValue();
                    $tonnage = $worksheet->getCellByColumnAndRow(41, $row)->getValue();
                    $volumeltr = $worksheet->getCellByColumnAndRow(42, $row)->getValue();
                    $grossamount = $worksheet->getCellByColumnAndRow(43, $row)->getValue();
                    $linediscount1 = $worksheet->getCellByColumnAndRow(44, $row)->getValue();
                    $linediscount2 = $worksheet->getCellByColumnAndRow(45, $row)->getValue();
                    $linediscount3 = $worksheet->getCellByColumnAndRow(46, $row)->getValue();
                    $linediscount4 = $worksheet->getCellByColumnAndRow(47, $row)->getValue();
                    $linediscount5 = $worksheet->getCellByColumnAndRow(48, $row)->getValue();
                    $totallinediscount = $worksheet->getCellByColumnAndRow(49, $row)->getValue();
                    $discountnota1 = $worksheet->getCellByColumnAndRow(50, $row)->getValue();
                    $discountnota2 = $worksheet->getCellByColumnAndRow(51, $row)->getValue();
                    $discountnota3 = $worksheet->getCellByColumnAndRow(52, $row)->getValue();
                    $totaldiscountnota = $worksheet->getCellByColumnAndRow(53, $row)->getValue();
                    $dpp = $worksheet->getCellByColumnAndRow(54, $row)->getValue();
                    $ppn = $worksheet->getCellByColumnAndRow(55, $row)->getValue();
                    $ppnbm = $worksheet->getCellByColumnAndRow(56, $row)->getValue();
                    $tax3 = $worksheet->getCellByColumnAndRow(57, $row)->getValue();
                    $netamount = $worksheet->getCellByColumnAndRow(58, $row)->getValue();
                    $warehouse = $worksheet->getCellByColumnAndRow(59, $row)->getValue();
                    $customerpo = $worksheet->getCellByColumnAndRow(60, $row)->getValue();
                    $customerjoindate = $worksheet->getCellByColumnAndRow(61, $row)->getValue();
                    $nofakturpajak = $worksheet->getCellByColumnAndRow(62, $row)->getValue();
                    $tglfakturpajak = $worksheet->getCellByColumnAndRow(63, $row)->getValue();
                    $nomorfakturproforma = $worksheet->getCellByColumnAndRow(64, $row)->getValue();
                    $tglfakturproforma = $worksheet->getCellByColumnAndRow(65, $row)->getValue();
                    $cogs = $worksheet->getCellByColumnAndRow(66, $row)->getValue();
                    $caseweightinkg = $worksheet->getCellByColumnAndRow(67, $row)->getValue();
                    $end = $worksheet->getCellByColumnAndRow(68, $row)->getValue();
                                        

                    $data = [
                        'distributor'      => $distributor,
                        'cabang'      => $cabang,
                        'tipetrans'      => $tipetrans,
                        'divisi'      => $divisi,
                        'principal'      => $principal,
                        'productgroup1'      => $productgroup1,
                        'productgroup2'      => $productgroup2,
                        'productgroup3'      => $productgroup3,
                        'brand'      => $brand,
                        'kodeproduk'      => $kodeproduk,
                        'kodevarian'      => $kodevarian,
                        'kodeprodukprincipal'      => $kodeprodukprincipal,
                        'namaproduk'      => $namaproduk,
                        'packaging'      => $packaging,
                        'productclass'      => $productclass,
                        'kodecustomer'      => $kodecustomer,
                        'namacustomer'      => $namacustomer,
                        'alamatcustomer'      => $alamatcustomer,
                        'area'      => $area,
                        'subarea'      => $subarea,
                        'channel'      => $channel,
                        'subchannel'      => $subchannel,
                        'customergroup'      => $customergroup,
                        'keyaccount'      => $keyaccount,
                        'kodesalesman'      => $kodesalesman,
                        'namasalesman'      => $namasalesman,
                        'kodesalesco'      => $kodesalesco,
                        'namasalesco'      => $namasalesco,
                        'kodespv'      => $kodespv,
                        'namaspv'      => $namaspv,
                        'tahunbulan'      => $tahunbulan,
                        'bulan'      => $bulan,
                        'tanggal'      => $tanggal,
                        'weekno'      => $weekno,
                        'nomornota'      => $nomornota,
                        'salesmethod'      => $salesmethod,
                        'sellingtype'      => $sellingtype,
                        'qtysold'      => $qtysold,
                        'kartonutuh'      => $kartonutuh,
                        'qtysoldpcs'      => $qtysoldpcs,
                        'freegoodpcs'      => $freegoodpcs,
                        'tonnage'      => $tonnage,
                        'volumeltr'      => $volumeltr,
                        'grossamount'      => $grossamount,
                        'linediscount1'      => $linediscount1,
                        'linediscount2'      => $linediscount2,
                        'linediscount3'      => $linediscount3,
                        'linediscount4'      => $linediscount4,
                        'linediscount5'      => $linediscount5,
                        'totallinediscount'      => $totallinediscount,
                        'discountnota1'      => $discountnota1,
                        'discountnota2'      => $discountnota2,
                        'discountnota3'      => $discountnota3,
                        'totaldiscountnota'      => $totaldiscountnota,
                        'dpp'      => $dpp,
                        'ppn'      => $ppn,
                        'ppnbm'      => $ppnbm,
                        'tax3'      => $tax3,
                        'netamount'      => $netamount,
                        'warehouse'      => $warehouse,
                        'customerpo'      => $customerpo,
                        'nofakturpajak'      => $nofakturpajak,
                        'customerjoindate'      => $customerjoindate,
                        'tglfakturpajak'      => $tglfakturpajak,
                        'nomorfakturproforma'      => $nomorfakturproforma,
                        'tglfakturproforma'      => $tglfakturproforma,
                        'cogs'      => $cogs,
                        'caseweightinkg'      => $caseweightinkg,
                        'end'      => $end,
                        'signature' => $signature,
                        'created_at'    => $created_at,
                        'created_by'    => $this->session->userdata('id')
                    ];

                    $this->db->insert('management_raw.raw_barabai',$data);
                }
            }
        }else{
           
        };

        $get_count = "select count(*) as count from management_raw.raw_barabai a where a.signature = '$signature'";
        $count = $this->db->query($get_count)->row()->count;

        $get_omzet = "select sum(a.grossamount * 1.11) as omzet_raw from management_raw.raw_barabai a where a.signature = '$signature'";
        $omzet_raw = $this->db->query($get_omzet)->row()->omzet_raw;

        $upload = [
            'site_code'     => 'BRBS0',
            'filename'      => $filename,
            'signature'     => $signature,
            'count_raw'      => $count,
            'omzet_raw'     => $omzet_raw,
            'type_file'      => 'raw_sales',
            'bulan'         => $bulan_from_input,
            'tahun'         => $tahun_from_input,
            'created_at'    => $created_at,
            'created_by'    => $this->session->userdata('id')
        ];
        $this->db->insert('management_raw.log_upload', $upload);

        redirect('management_raw/import_barabai_draft/'.$signature);

    }

    public function import_barabai_draft($signature){

        $data = [
            'title'     => 'Management Raw / Preview Import data barabai',
            'id'        => $this->session->userdata('id'),
            'get_raw_draft'  => $this->model_management_raw->get_raw_draft('BRBS0', $signature),
            'url'=> 'management_raw/proses_mapping_barabai',
            'get_summary' => $this->model_management_raw->get_summary_raw_sales('BRBS0', $signature),
            'signature' => $signature
        ];
        $this->load->view('management_raw/header');
        $this->load->view('management_raw/draft_barabai', $data);
        $this->load->view('mes/footer');
    }    

    public function template_customer_banjarmasin(){        
        $query = "
            select  '' as customer_id, '' as customer_id_nd6, '' as nama_customer, '' as alamat, '' as kode_type, '' as kode_class, '' as kode_kota, '' as nama_kota, '' as kode_kecamatan, '' as nama_kecamatan, '' as kode_kelurahan, '' as nama_kelurahan
        ";

        $hsl = $this->db->query($query);
        query_to_csv($hsl,TRUE,'Template_Customer_Banjarmasin.csv');
    }

    public function template_customer_barabai(){        
        $query = "
            select  '' as customer_id, '' as customer_id_nd6, '' as nama_customer, '' as alamat, '' as kode_type, '' as kode_class, '' as kode_kota, '' as nama_kota, '' as kode_kecamatan, '' as nama_kecamatan, '' as kode_kelurahan, '' as nama_kelurahan
        ";

        $hsl = $this->db->query($query);
        query_to_csv($hsl,TRUE,'Template_Customer_Barabai.csv');
    }

    public function template_customer_batulicin(){        
        // $query = "
        //     select  '' as customer_id, '' as customer_id_nd6, '' as nama_customer, '' as alamat, '' as kode_type, '' as kode_class
        // ";

        $query = "
        select  a.customer_id, a.customer_id_nd6, a.nama_customer, a.alamat, a.kode_type, a.nama_type,
                a.sektor, a.segment, a.kode_class, a.nama_class, a.group_class, a.kode_kota, a.nama_kota,
                a.kode_kecamatan, a.nama_kecamatan, a.kode_kelurahan, a.nama_kelurahan
        from management_raw.raw_customer_batulicin a
        ";

        $hsl = $this->db->query($query);
        query_to_csv($hsl,TRUE,'Template_Customer_Batulicin.csv');
    }

    public function import_banjarmasin(){
        $bulan_from_input = substr($this->input->post('bulan'), 5, 2);
        $tahun_from_input = substr($this->input->post('bulan'), 0, 4);

        $cek_last_upload = "
            select *
            from mpm.upload a 
            where a.userid = 465 
            ORDER BY a.id desc
        ";
        $tahun_last_upload = $this->db->query($cek_last_upload)->row()->tahun;
        $bulan_last_upload = $this->db->query($cek_last_upload)->row()->bulan;
        $closing_last_upload = $this->db->query($cek_last_upload)->row()->status_closing;

        if ($closing_last_upload == 1) {
            $tanggal_seharusnya = date('Y-m-d', strtotime('+1 month', strtotime($tahun_last_upload.'-'.$bulan_last_upload.'-01')));           
            $bulan_seharusnya = date('m', strtotime($tanggal_seharusnya));
            $tahun_seharusnya = date('Y', strtotime($tanggal_seharusnya));

            if ($tahun_from_input === $tahun_seharusnya && $bulan_from_input === $bulan_seharusnya) {
                
            }else{
                echo "<br><hr>Tanggal yang anda pilih adalah :<br><br>";
                // echo "<hr>";
                echo "tahun : ".$tahun_from_input."<br>";
                echo "bulan : ".$bulan_from_input."<br><br>";
                echo "<hr>";

                echo "<br>Tanggal upload terakhir adalah :<br><br>";
                // echo "<hr>";
                echo "tahun_last_upload : ".$tahun_last_upload."<br>";
                echo "bulan_last_upload : ".$bulan_last_upload."<br>";
                echo "closing_last_upload : ".$closing_last_upload."<br><br>";
                echo "<hr>";
                
                echo "<h3>Result</h3>";
                echo "Data ditolak !!<br><br>";
                echo "Anda hanya diijinkan mengupload data untuk <b>tahun : $tahun_seharusnya</b>, dan <b>bulan : $bulan_seharusnya</b><br>";

                echo "<br>anda akan di redirect ke menu awal dalam 10 detik";
                header('Refresh: 10; URL='.base_url().'management_raw/banjarmasin');
                die;
            }

        }else{

            $tanggal_seharusnya = date('Y-m-d', strtotime('+0 month', strtotime($tahun_last_upload.'-'.$bulan_last_upload.'-01')));           
            $bulan_seharusnya = date('m', strtotime($tanggal_seharusnya));
            $tahun_seharusnya = date('Y', strtotime($tanggal_seharusnya));

            if ($tahun_from_input === $tahun_seharusnya && $bulan_from_input === $bulan_seharusnya) {
                // echo "diijinkan";
            }else{
                echo "<br><hr>Tanggal yang anda pilih adalah :<br><br>";
                echo "tahun : ".$tahun_from_input."<br>";
                echo "bulan : ".$bulan_from_input."<br><br>";
                echo "<hr>";


                echo "<br>Tanggal upload terakhir adalah :<br><br>";
                echo "tahun_last_upload : ".$tahun_last_upload."<br>";
                echo "bulan_last_upload : ".$bulan_last_upload."<br>";
                echo "closing_last_upload : ".$closing_last_upload."<br><br>";
                echo "<hr>";
                
                echo "<h3>Result</h3>";
                echo "Data ditolak !!<br><br>";
                echo "Anda hanya diijinkan mengupload data untuk <b>tahun : $tahun_seharusnya</b>, dan <b>bulan : $bulan_seharusnya</b><br>";

                echo "<br>anda akan di redirect ke menu awal dalam 10 detik";
                header('Refresh: 10; URL='.base_url().'management_raw/banjarmasin');
                die;
            }

        }

        if (!is_dir('./assets/uploads/management_raw/import/')) {
            @mkdir('./assets/uploads/management_raw/import/', 0777);
        }

        $this->load->library('upload'); // Load librari upload        
        $config['upload_path'] = './assets/uploads/management_raw/import/';
        // $config['allowed_types'] = 'xls|xlsx|csv';
        $config['allowed_types'] = '*';    
        $config['max_size']  = '2048000';
        $config['overwrite'] = true;
        $config['encrypt_name'] = TRUE;
        $this->upload->initialize($config);

        if ($this->upload->do_upload('file')) 
        {

            $upload_data = $this->upload->data();
            $filename = $upload_data['orig_name'];

            $this->load->library('excel');
            $object = PHPExcel_IOFactory::load("assets/uploads/management_raw/import/$filename");

            $jumlahSheet = $object->getSheetCount();
            if ($jumlahSheet > 1) {
                echo "jumlah_sheet : ".$jumlahSheet;
                echo "<script>alert('upload file gagal karena file mempunyai lebih dari 1 sheet'); </script>";
                redirect('management_raw','refresh');
            }

            $created_at = $this->model_outlet_transaksi->timezone();
            $signature = md5($this->model_outlet_transaksi->timezone());

            foreach ($object->getWorksheetIterator() as $worksheet) {

                $highestRow = $worksheet->getHighestRow();
                $highestColumn = $worksheet->getHighestColumn();

                for ($row = 2; $row <= $highestRow; $row++) {          
                
                    $distributor = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
                    $cabang = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
                    $tipetrans = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
                    $divisi = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
                    $principal = $worksheet->getCellByColumnAndRow(4, $row)->getValue();
                    $productgroup1 = $worksheet->getCellByColumnAndRow(5, $row)->getValue();
                    $productgroup2 = $worksheet->getCellByColumnAndRow(6, $row)->getValue();
                    $productgroup3 = $worksheet->getCellByColumnAndRow(7, $row)->getValue();
                    $brand = $worksheet->getCellByColumnAndRow(8, $row)->getValue();
                    $kodeproduk = $worksheet->getCellByColumnAndRow(9, $row)->getValue();
                    $kodevarian = $worksheet->getCellByColumnAndRow(10, $row)->getValue();
                    $kodeprodukprincipal = $worksheet->getCellByColumnAndRow(11, $row)->getValue();
                    $namaproduk = $worksheet->getCellByColumnAndRow(12, $row)->getValue();
                    $packaging = $worksheet->getCellByColumnAndRow(13, $row)->getValue();
                    $productclass = $worksheet->getCellByColumnAndRow(14, $row)->getValue();
                    $kodecustomer = $worksheet->getCellByColumnAndRow(15, $row)->getValue();
                    $namacustomer = $worksheet->getCellByColumnAndRow(16, $row)->getValue();
                    $alamatcustomer = $worksheet->getCellByColumnAndRow(17, $row)->getValue();
                    $area = $worksheet->getCellByColumnAndRow(18, $row)->getValue();
                    $subarea = $worksheet->getCellByColumnAndRow(19, $row)->getValue();
                    $channel = $worksheet->getCellByColumnAndRow(20, $row)->getValue();
                    $subchannel = $worksheet->getCellByColumnAndRow(21, $row)->getValue();
                    $customergroup = $worksheet->getCellByColumnAndRow(22, $row)->getValue();
                    $keyaccount = $worksheet->getCellByColumnAndRow(23, $row)->getValue();
                    $kodesalesman = $worksheet->getCellByColumnAndRow(24, $row)->getValue();
                    $namasalesman = $worksheet->getCellByColumnAndRow(25, $row)->getValue();
                    $kodesalesco = $worksheet->getCellByColumnAndRow(26, $row)->getValue();
                    $namasalesco = $worksheet->getCellByColumnAndRow(27, $row)->getValue();
                    $kodespv = $worksheet->getCellByColumnAndRow(28, $row)->getValue();
                    $namaspv = $worksheet->getCellByColumnAndRow(29, $row)->getValue();
                    $tahunbulan = $worksheet->getCellByColumnAndRow(30, $row)->getValue();
                    $bulan = $worksheet->getCellByColumnAndRow(31, $row)->getValue();
                    $tanggal = $worksheet->getCellByColumnAndRow(32, $row)->getValue();
                    $weekno = $worksheet->getCellByColumnAndRow(33, $row)->getValue();
                    $nomornota = $worksheet->getCellByColumnAndRow(34, $row)->getValue();
                    $salesmethod = $worksheet->getCellByColumnAndRow(35, $row)->getValue();
                    $sellingtype = $worksheet->getCellByColumnAndRow(36, $row)->getValue();
                    $qtysold = $worksheet->getCellByColumnAndRow(37, $row)->getValue();
                    $qtysoldcrt = $worksheet->getCellByColumnAndRow(38, $row)->getValue();
                    $qtysolduom1 = $worksheet->getCellByColumnAndRow(39, $row)->getValue();
                    $qtysolduom2 = $worksheet->getCellByColumnAndRow(40, $row)->getValue();
                    $qtysolduom3 = $worksheet->getCellByColumnAndRow(41, $row)->getValue();
                    $qtysolduom4 = $worksheet->getCellByColumnAndRow(42, $row)->getValue();
                    $qtysoldtotalpcs = $worksheet->getCellByColumnAndRow(43, $row)->getValue();
                    $freegoodtotalpcs = $worksheet->getCellByColumnAndRow(44, $row)->getValue();
                    $tonnage = $worksheet->getCellByColumnAndRow(45, $row)->getValue();
                    $volumeltr = $worksheet->getCellByColumnAndRow(46, $row)->getValue();
                    $grossamount = $worksheet->getCellByColumnAndRow(47, $row)->getValue();
                    $linediscount1 = $worksheet->getCellByColumnAndRow(48, $row)->getValue();
                    $linediscount2 = $worksheet->getCellByColumnAndRow(49, $row)->getValue();
                    $linediscount3 = $worksheet->getCellByColumnAndRow(50, $row)->getValue();
                    $linediscount4 = $worksheet->getCellByColumnAndRow(51, $row)->getValue();
                    $linediscount5 = $worksheet->getCellByColumnAndRow(52, $row)->getValue();
                    $totallinediscount = $worksheet->getCellByColumnAndRow(53, $row)->getValue();
                    $discountnota1 = $worksheet->getCellByColumnAndRow(54, $row)->getValue();
                    $discountnota2 = $worksheet->getCellByColumnAndRow(55, $row)->getValue();
                    $discountnota3 = $worksheet->getCellByColumnAndRow(56, $row)->getValue();
                    $totaldiscountnota = $worksheet->getCellByColumnAndRow(57, $row)->getValue();
                    $dpp = $worksheet->getCellByColumnAndRow(58, $row)->getValue();
                    $ppn = $worksheet->getCellByColumnAndRow(59, $row)->getValue();
                    $ppnbm = $worksheet->getCellByColumnAndRow(60, $row)->getValue();
                    $tax3 = $worksheet->getCellByColumnAndRow(61, $row)->getValue();
                    $netamount = $worksheet->getCellByColumnAndRow(62, $row)->getValue();
                    $warehouse = $worksheet->getCellByColumnAndRow(63, $row)->getValue();
                    $customerpo = $worksheet->getCellByColumnAndRow(64, $row)->getValue();
                    $customerjoindate = $worksheet->getCellByColumnAndRow(65, $row)->getValue();
                    $nofakturpajak = $worksheet->getCellByColumnAndRow(66, $row)->getValue();
                    $tglfakturpajak = $worksheet->getCellByColumnAndRow(67, $row)->getValue();
                    $nomorfakturproforma = $worksheet->getCellByColumnAndRow(68, $row)->getValue();
                    $tglfakturproforma = $worksheet->getCellByColumnAndRow(69, $row)->getValue();
                    $term = $worksheet->getCellByColumnAndRow(70, $row)->getValue();
                    $uom1 = $worksheet->getCellByColumnAndRow(71, $row)->getValue();
                    $uom2 = $worksheet->getCellByColumnAndRow(72, $row)->getValue();
                    $uom3 = $worksheet->getCellByColumnAndRow(73, $row)->getValue();
                    $uom4 = $worksheet->getCellByColumnAndRow(74, $row)->getValue();
                    $isiuom1 = $worksheet->getCellByColumnAndRow(75, $row)->getValue();
                    $isiuom2 = $worksheet->getCellByColumnAndRow(76, $row)->getValue();
                    $isiuom3 = $worksheet->getCellByColumnAndRow(77, $row)->getValue();
                    $sellingprice = $worksheet->getCellByColumnAndRow(78, $row)->getValue();
                    $cogs = $worksheet->getCellByColumnAndRow(79, $row)->getValue();
                    $sellingpriceinkg = $worksheet->getCellByColumnAndRow(80, $row)->getValue();
                    $caseweightinkg = $worksheet->getCellByColumnAndRow(81, $row)->getValue();
                    $qtyordertotalpcs = $worksheet->getCellByColumnAndRow(82, $row)->getValue();
                    $end = $worksheet->getCellByColumnAndRow(83, $row)->getValue();

                    $data = [
                        'distributor'      => $distributor,
                        'cabang'      => $cabang,
                        'tipetrans'      => $tipetrans,
                        'divisi'      => $divisi,
                        'principal'      => $principal,
                        'productgroup1'      => $productgroup1,
                        'productgroup2'      => $productgroup2,
                        'productgroup3'      => $productgroup3,
                        'brand'      => $brand,
                        'kodeproduk'      => $kodeproduk,
                        'kodevarian'      => $kodevarian,
                        'kodeprodukprincipal'      => $kodeprodukprincipal,
                        'namaproduk'      => $namaproduk,
                        'packaging'      => $packaging,
                        'productclass'      => $productclass,
                        'kodecustomer'      => $kodecustomer,
                        'namacustomer'      => $namacustomer,
                        'alamatcustomer'      => $alamatcustomer,
                        'area'      => $area,
                        'subarea'      => $subarea,
                        'channel'      => $channel,
                        'subchannel'      => $subchannel,
                        'customergroup'      => $customergroup,
                        'keyaccount'      => $keyaccount,
                        'kodesalesman'      => $kodesalesman,
                        'namasalesman'      => $namasalesman,
                        'kodesalesco'      => $kodesalesco,
                        'namasalesco'      => $namasalesco,
                        'kodespv'      => $kodespv,
                        'namaspv'      => $namaspv,
                        'tahunbulan'      => $tahunbulan,
                        'bulan'      => $bulan,
                        'tanggal'      => $tanggal,
                        'weekno'      => $weekno,
                        'nomornota'      => $nomornota,
                        'salesmethod'      => $salesmethod,
                        'sellingtype'      => $sellingtype,
                        'qtysold'      => $qtysold,
                        'qtysoldcrt'      => $qtysoldcrt,
                        'qtysolduom1'      => $qtysolduom1,
                        'qtysolduom2'      => $qtysolduom2,
                        'qtysolduom3'      => $qtysolduom3,
                        'qtysolduom4'      => $qtysolduom4,
                        'qtysoldtotalpcs'      => $qtysoldtotalpcs,
                        'freegoodtotalpcs'      => $freegoodtotalpcs,
                        'tonnage'      => $tonnage,
                        'volumeltr'      => $volumeltr,
                        'grossamount'      => $grossamount,
                        'linediscount1'      => $linediscount1,
                        'linediscount2'      => $linediscount2,
                        'linediscount3'      => $linediscount3,
                        'linediscount4'      => $linediscount4,
                        'linediscount5'      => $linediscount5,
                        'totallinediscount'      => $totallinediscount,
                        'discountnota1'      => $discountnota1,
                        'discountnota2'      => $discountnota2,
                        'discountnota3'      => $discountnota3,
                        'totaldiscountnota'      => $totaldiscountnota,
                        'dpp'      => $dpp,
                        'ppn'      => $ppn,
                        'ppnbm'      => $ppnbm,
                        'tax3'      => $tax3,
                        'netamount'      => $netamount,
                        'warehouse'      => $warehouse,
                        'customerpo'      => $customerpo,
                        'nofakturpajak'      => $nofakturpajak,
                        'customerjoindate'      => $customerjoindate,
                        'tglfakturpajak'      => $tglfakturpajak,
                        'nomorfakturproforma'      => $nomorfakturproforma,
                        'tglfakturproforma'      => $tglfakturproforma,
                        'term'      => $term,
                        'uom1'      => $uom1,
                        'uom2'      => $uom2,
                        'uom3'      => $uom3,
                        'uom4'      => $uom4,
                        'isiuom1'      => $isiuom1,
                        'isiuom2'      => $isiuom2,
                        'isiuom3'      => $isiuom3,
                        'sellingprice'      => $sellingprice,
                        'cogs'      => $cogs,
                        'sellingpriceinkg'      => $sellingpriceinkg,
                        'caseweightinkg'      => $caseweightinkg,
                        'qtyordertotalpcs'      => $qtyordertotalpcs,
                        'end'      => $end,
                        'signature' => $signature,
                        'created_at'    => $created_at,
                        'created_by'    => $this->session->userdata('id')
                    ];

                    $this->db->insert('management_raw.raw_banjarmasin',$data);
                }
            }
        }else{
           
        };

        $get_count = "select count(*) as count from management_raw.raw_banjarmasin a where a.signature = '$signature'";
        $count = $this->db->query($get_count)->row()->count;

        $get_omzet = "select sum(a.grossamount * 1.11) as omzet_raw from management_raw.raw_banjarmasin a where a.signature = '$signature'";
        $omzet_raw = $this->db->query($get_omzet)->row()->omzet_raw;

        $upload = [
            'site_code'     => 'SSMS9',
            'filename'      => $filename,
            'signature'     => $signature,
            'type_file'      => 'raw_sales',
            'count_raw'      => $count,
            'omzet_raw'     => $omzet_raw,
            'bulan'         => $bulan_from_input,
            'tahun'         => $tahun_from_input,
            'created_at'    => $created_at,
            'created_by'    => $this->session->userdata('id')
        ];
        $this->db->insert('management_raw.log_upload', $upload);
        redirect('management_raw/import_banjarmasin_draft/'.$signature);
    }

    public function import_banjarmasin_draft($signature){
     
        $data = [
            'title'     => 'Management Raw / Preview Import data banjarmasin',
            'id'        => $this->session->userdata('id'),
            'get_raw_draft'  => $this->model_management_raw->get_raw_draft('SSMS9', $signature),
            'url'=> 'management_raw/proses_mapping_banjarmasin',
            'get_summary' => $this->model_management_raw->get_summary_raw_sales('SSMS9', $signature),
            'signature' => $signature
        ];
        $this->load->view('management_raw/header');
        $this->load->view('management_raw/draft_banjarmasin', $data);
        $this->load->view('mes/footer');
    }

    public function customer_banjarmasin(){
        $data = [
            'title'     => 'Customer banjarmasin',
            'id'        => $this->session->userdata('id'),
            'url_import'=> 'management_raw/import_customer_banjarmasin',
            'get_log_customer_upload'    => $this->model_management_raw->get_log_customer_upload('SSMS9')
        ];
        $this->load->view('management_raw/header');
        $this->load->view('management_raw/customer_banjarmasin', $data);
        $this->load->view('mes/footer');
    }

    public function customer_barabai(){
        $data = [
            'title'     => 'Customer barabai',
            'id'        => $this->session->userdata('id'),
            'url_import'=> 'management_raw/import_customer_barabai',
            'get_log_customer_upload'    => $this->model_management_raw->get_log_customer_upload('BRBS0')
        ];
        $this->load->view('management_raw/header');
        $this->load->view('management_raw/customer_barabai', $data);
        $this->load->view('mes/footer');
    }

    public function customer_batulicin(){
        $data = [
            'title'     => 'Customer batulicin',
            'id'        => $this->session->userdata('id'),
            'url_import'=> 'management_raw/import_customer_batulicin',
            'get_customer_batulicin'    => $this->model_management_raw->get_customer_batulicin('SSJD2')
        ];
        $this->load->view('management_raw/header');
        $this->load->view('management_raw/customer_batulicin', $data);
        $this->load->view('mes/footer');
    }

    public function import_customer_banjarmasin(){
        $bulan = $this->input->post('bulan');
        // echo "bulan : ".$bulan;

        if (!is_dir('./assets/uploads/management_raw/import/')) {
            @mkdir('./assets/uploads/management_raw/import/', 0777);
        }

        $this->load->library('upload'); // Load librari upload        
        $config['upload_path'] = './assets/uploads/management_raw/import/';
        // $config['allowed_types'] = 'xls|xlsx|csv';
        $config['allowed_types'] = '*';    
        $config['max_size']  = '2048000';
        $config['overwrite'] = true;
        $config['encrypt_name'] = TRUE;
        $this->upload->initialize($config);

        if ($this->upload->do_upload('file')) 
        {

            $upload_data = $this->upload->data();
            $filename = $upload_data['orig_name'];

            $this->load->library('excel');
            $object = PHPExcel_IOFactory::load("assets/uploads/management_raw/import/$filename");

            $jumlahSheet = $object->getSheetCount();
            if ($jumlahSheet > 1) {
                echo "jumlah_sheet : ".$jumlahSheet;
                echo "<script>alert('upload file gagal karena file mempunyai lebih dari 1 sheet'); </script>";
                redirect('management_raw','refresh');
            }

            $highestColumm = $object->setActiveSheetIndex(0)->getHighestColumn();
            // var_dump($highestColumm);die;
            $highestColumm = $object->setActiveSheetIndex(0)->getHighestColumn();
            // var_dump($highestColumm);die;
            if ($highestColumm != 'L') {
                echo "<script>alert('upload file gagal karena column tidak sesuai'); </script>";
                redirect('management_raw/customer_banjarmasin','refresh');
            }

            $created_at = $this->model_outlet_transaksi->timezone();

            foreach ($object->getWorksheetIterator() as $worksheet) {

                $highestRow = $worksheet->getHighestRow();
                $highestColumn = $worksheet->getHighestColumn();

                for ($row = 2; $row <= $highestRow; $row++) {          
                
                    $customer_id = trim($worksheet->getCellByColumnAndRow(0, $row)->getValue());
                    $customer_id_nd6 = trim($worksheet->getCellByColumnAndRow(1, $row)->getValue());
                    $nama_customer = trim($worksheet->getCellByColumnAndRow(2, $row)->getValue());
                    $alamat = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
                    $kode_type = trim($worksheet->getCellByColumnAndRow(4, $row)->getValue());
                    $kode_class = trim($worksheet->getCellByColumnAndRow(5, $row)->getValue());
                    $kode_kota = trim($worksheet->getCellByColumnAndRow(6, $row)->getValue());
                    $nama_kota = $worksheet->getCellByColumnAndRow(7, $row)->getValue();
                    $kode_kecamatan = trim($worksheet->getCellByColumnAndRow(8, $row)->getValue());
                    $nama_kecamatan = $worksheet->getCellByColumnAndRow(9, $row)->getValue();
                    $kode_kelurahan = trim($worksheet->getCellByColumnAndRow(10, $row)->getValue());
                    $nama_kelurahan = $worksheet->getCellByColumnAndRow(11, $row)->getValue();

                    $signature = md5($this->model_outlet_transaksi->timezone().$customer_id_nd6);

                    

                    $cek_customer = $this->model_management_raw->get_raw_customer('SSMS9', $customer_id_nd6);
                    if($cek_customer->num_rows() > 0){

                        $data = [
                            'customer_id'      => $customer_id,
                            'customer_id_nd6'      => $customer_id_nd6,
                            'nama_customer'      => $nama_customer,
                            'alamat'      => $alamat,
                            'kode_type'      => $kode_type,
                            'kode_class'      => $kode_class,
                            'kode_kota'      => $kode_kota,
                            'nama_kota'      => $nama_kota,
                            'kode_kecamatan'      => $kode_kecamatan,
                            'nama_kecamatan'      => $nama_kecamatan,
                            'kode_kelurahan'      => $kode_kelurahan,
                            'nama_kelurahan'      => $nama_kelurahan,
                            'signature' => $signature,
                            'created_at'    => $created_at,
                            'created_by'    => $this->session->userdata('id')
                        ];

                        $this->db->where('customer_id_nd6', $customer_id_nd6);
                        $this->db->update('management_raw.raw_customer_banjarmasin',$data);
                        
                    }else{
                        
                        $data = [
                            'customer_id'      => $customer_id,
                            'customer_id_nd6'      => $customer_id_nd6,
                            'nama_customer'      => $nama_customer,
                            'alamat'      => $alamat,
                            'kode_type'      => $kode_type,
                            'kode_class'      => $kode_class,
                            'kode_kota'      => $kode_kota,
                            'nama_kota'      => $nama_kota,
                            'kode_kecamatan'      => $kode_kecamatan,
                            'nama_kecamatan'      => $nama_kecamatan,
                            'kode_kelurahan'      => $kode_kelurahan,
                            'nama_kelurahan'      => $nama_kelurahan,
                            'signature' => $signature,
                            'created_at'    => $created_at,
                            'created_by'    => $this->session->userdata('id')
                        ];
    
                        $this->db->insert('management_raw.raw_customer_banjarmasin',$data);

                    }

                }
            }
        }else{
           
        };

        $get_count = "select count(*) as count from management_raw.raw_customer_banjarmasin a";
        $count = $this->db->query($get_count)->row()->count;

        $upload = [
            'site_code'     => 'SSMS9',
            'signature'     => $signature,
            'filename'      => $filename,
            'type_file'      => 'raw_customer',
            'count_raw'      => $count,
            'created_at'    => $created_at,
            'created_by'    => $this->session->userdata('id')
        ];
        $this->db->insert('management_raw.log_upload', $upload);

        echo "<br><center><i>Upload Customer Banjarmasin Done ... </i></b><br>";
        echo "<br><center><i>dalam 5 detik anda akan di redirect ke halaman awal ...</i></b><br>";
        header('Refresh: 5; URL='.base_url().'management_raw/customer_banjarmasin');
    }

    public function import_customer_barabai(){
        $bulan = $this->input->post('bulan');
        // echo "bulan : ".$bulan;

        if (!is_dir('./assets/uploads/management_raw/import/')) {
            @mkdir('./assets/uploads/management_raw/import/', 0777);
        }

        $this->load->library('upload'); // Load librari upload        
        $config['upload_path'] = './assets/uploads/management_raw/import/';
        // $config['allowed_types'] = 'xls|xlsx|csv';
        $config['allowed_types'] = '*';    
        $config['max_size']  = '2048000';
        $config['overwrite'] = true;
        $config['encrypt_name'] = TRUE;
        $this->upload->initialize($config);

        if ($this->upload->do_upload('file')) 
        {

            $upload_data = $this->upload->data();
            $filename = $upload_data['orig_name'];

            $this->load->library('excel');
            $object = PHPExcel_IOFactory::load("assets/uploads/management_raw/import/$filename");

            $jumlahSheet = $object->getSheetCount();
            if ($jumlahSheet > 1) {
                echo "jumlah_sheet : ".$jumlahSheet;
                echo "<script>alert('upload file gagal karena file mempunyai lebih dari 1 sheet'); </script>";
                redirect('management_raw','refresh');
            }

            $highestColumm = $object->setActiveSheetIndex(0)->getHighestColumn();
            // var_dump($highestColumm);die;
            $highestColumm = $object->setActiveSheetIndex(0)->getHighestColumn();
            // var_dump($highestColumm);die;
            if ($highestColumm != 'L') {
                echo "<script>alert('upload file gagal karena column tidak sesuai'); </script>";
                redirect('management_raw/customer_barabai','refresh');
            }

            $created_at = $this->model_outlet_transaksi->timezone();

            foreach ($object->getWorksheetIterator() as $worksheet) {

                $highestRow = $worksheet->getHighestRow();
                $highestColumn = $worksheet->getHighestColumn();

                for ($row = 2; $row <= $highestRow; $row++) {          
                
                    $customer_id = trim($worksheet->getCellByColumnAndRow(0, $row)->getValue());
                    $customer_id_nd6 = trim($worksheet->getCellByColumnAndRow(1, $row)->getValue());
                    $nama_customer = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
                    $alamat = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
                    $kode_type = trim($worksheet->getCellByColumnAndRow(4, $row)->getValue());
                    $kode_class = trim($worksheet->getCellByColumnAndRow(5, $row)->getValue());
                    $kode_kota = trim($worksheet->getCellByColumnAndRow(6, $row)->getValue());
                    $nama_kota = $worksheet->getCellByColumnAndRow(7, $row)->getValue();
                    $kode_kecamatan = $worksheet->getCellByColumnAndRow(8, $row)->getValue();
                    $nama_kecamatan = $worksheet->getCellByColumnAndRow(9, $row)->getValue();
                    $kode_kelurahan = $worksheet->getCellByColumnAndRow(10, $row)->getValue();
                    $nama_kelurahan = $worksheet->getCellByColumnAndRow(11, $row)->getValue();

                    $signature = md5($this->model_outlet_transaksi->timezone().$customer_id_nd6);

                    $cek_customer = $this->model_management_raw->get_raw_customer('BRBS0', $customer_id_nd6);
                    if($cek_customer->num_rows() > 0){

                        $data = [
                            'customer_id'      => $customer_id,
                            'customer_id_nd6'      => $customer_id_nd6,
                            'nama_customer'      => $nama_customer,
                            'alamat'      => $alamat,
                            'kode_type'      => $kode_type,
                            'kode_class'      => $kode_class,
                            'kode_kota'      => $kode_kota,
                            'nama_kota'      => $nama_kota,
                            'kode_kecamatan'      => $kode_kecamatan,
                            'nama_kecamatan'      => $nama_kecamatan,
                            'kode_kelurahan'      => $kode_kelurahan,
                            'nama_kelurahan'      => $nama_kelurahan,
                            'signature' => $signature,
                            'created_at'    => $created_at,
                            'created_by'    => $this->session->userdata('id')
                        ];

                        $this->db->where('customer_id_nd6', $customer_id_nd6);
                        $this->db->update('management_raw.raw_customer_barabai',$data);
                        
                    }else{
                        
                        $data = [
                            'customer_id'      => $customer_id,
                            'customer_id_nd6'      => $customer_id_nd6,
                            'nama_customer'      => $nama_customer,
                            'alamat'      => $alamat,
                            'kode_type'      => $kode_type,
                            'kode_class'      => $kode_class,
                            'kode_kota'      => $kode_kota,
                            'nama_kota'      => $nama_kota,
                            'kode_kecamatan'      => $kode_kecamatan,
                            'nama_kecamatan'      => $nama_kecamatan,
                            'kode_kelurahan'      => $kode_kelurahan,
                            'nama_kelurahan'      => $nama_kelurahan,
                            'signature' => $signature,
                            'created_at'    => $created_at,
                            'created_by'    => $this->session->userdata('id')
                        ];
    
                        $this->db->insert('management_raw.raw_customer_barabai',$data);

                    }
                    
                }
            }
        }else{
           
        };

        $get_count = "select count(*) as count from management_raw.raw_customer_barabai a";
        $count = $this->db->query($get_count)->row()->count;

        $upload = [
            'site_code'     => 'BRBS0',
            'signature'     => $signature,
            'filename'      => $filename,
            'type_file'      => 'raw_customer',
            'count_raw'      => $count,
            'created_at'    => $created_at,
            'created_by'    => $this->session->userdata('id')
        ];
        $this->db->insert('management_raw.log_upload', $upload);

        echo "<br><center><i>Upload Customer Barabai Done ... </i></b><br>";
        echo "<br><center><i>dalam 5 detik anda akan di redirect ke halaman awal ...</i></b><br>";
        header('Refresh: 5; URL='.base_url().'management_raw/customer_barabai');
    }

    public function import_customer_barabai_draft($signature){
       
        $data = [
            'title'     => 'Preview Import customer barabai',
            'id'        => $this->session->userdata('id'),
            'get_raw_customer_draft'  => $this->model_management_raw->get_raw_customer_draft_barabai($signature),
            'url'=> 'management_raw/proses_mapping_customer_barabai',
            'signature' => $signature
        ];
        $this->load->view('management_raw/header');
        $this->load->view('management_raw/draft_customer_banjarmasin', $data);
        $this->load->view('mes/footer');
    }

    public function proses_mapping_customer_barabai(){

        $signature = $this->input->post('signature');
        $trim_customer_barabai = $this->model_management_raw->trim_customer_barabai($signature);

        if ($trim_customer_barabai) {
            echo "<br><center><i>trimming kode_type, kode_class, customer_id, customer_id_nd6 done ... </i></b><br>";
            $update_type_n_class = $this->model_management_raw->update_type_n_class_barabai($signature);
        
            if ($update_type_n_class) {
                echo "<br><center><i>updating nama_type, sektor, segment, nama_class done ... </i></b><br>";
                
                $inner = $this->model_management_raw->inner_customer_barabai($signature);
                if ($inner) {
                    
                    echo "<br><center><i>deleting redundant customer_id_nd5 done ... </i></b><br>";
                    $get_count = "select count(*) as count from management_raw.inner_raw_customer_barabai a where a.signature = '$signature'";
                    $count = $this->db->query($get_count)->row()->count;

                    $update = [
                        'count_mapping'  => $count
                    ];
                    $this->db->where('signature', $signature);
                    $this->db->update('management_raw.log_upload', $update);

                    echo "<br><center><i>dalam 5 detik anda akan di redirect ke halaman awal ...</i></b><br>";
                    header('Refresh: 5; URL='.base_url().'management_raw/customer_barabai');

                }

            }
        }
    }

    public function import_customer_banjarmasin_draft($signature){
       
        $data = [
            'title'     => 'Preview Import customer banjarmasin',
            'id'        => $this->session->userdata('id'),
            'get_raw_customer_draft'  => $this->model_management_raw->get_raw_customer_draft($signature),
            'url'=> 'management_raw/proses_mapping_customer_banjarmasin',
            'signature' => $signature
        ];
        $this->load->view('management_raw/header');
        $this->load->view('management_raw/draft_customer_banjarmasin', $data);
        $this->load->view('mes/footer');
    }

    

    public function import_customer_batulicin(){
        $bulan = $this->input->post('bulan');
        // echo "bulan : ".$bulan;

        if (!is_dir('./assets/uploads/management_raw/import/')) {
            @mkdir('./assets/uploads/management_raw/import/', 0777);
        }

        $this->load->library('upload'); // Load librari upload        
        $config['upload_path'] = './assets/uploads/management_raw/import/';
        // $config['allowed_types'] = 'xls|xlsx|csv';
        $config['allowed_types'] = '*';    
        $config['max_size']  = '2048000';
        $config['overwrite'] = true;
        $config['encrypt_name'] = TRUE;
        $this->upload->initialize($config);

        if ($this->upload->do_upload('file')) 
        {

            $upload_data = $this->upload->data();
            $filename = $upload_data['orig_name'];

            $this->load->library('excel');
            $object = PHPExcel_IOFactory::load("assets/uploads/management_raw/import/$filename");

            $jumlahSheet = $object->getSheetCount();
            if ($jumlahSheet > 1) {
                echo "jumlah_sheet : ".$jumlahSheet;
                echo "<script>alert('upload file gagal karena file mempunyai lebih dari 1 sheet'); </script>";
                redirect('management_raw','refresh');
            }

            $created_at = $this->model_outlet_transaksi->timezone();
            $signature = md5($this->model_outlet_transaksi->timezone());

            foreach ($object->getWorksheetIterator() as $worksheet) {

                $highestRow = $worksheet->getHighestRow();
                $highestColumn = $worksheet->getHighestColumn();

                for ($row = 2; $row <= $highestRow; $row++) {          
                
                    $customer_id = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
                    $customer_id_nd6 = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
                    $nama_customer = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
                    $alamat = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
                    $kode_type = $worksheet->getCellByColumnAndRow(4, $row)->getValue();
                    $nama_type = $worksheet->getCellByColumnAndRow(5, $row)->getValue();
                    $sektor = $worksheet->getCellByColumnAndRow(6, $row)->getValue();
                    $segment = $worksheet->getCellByColumnAndRow(7, $row)->getValue();
                    $kode_class = $worksheet->getCellByColumnAndRow(8, $row)->getValue();
                    $nama_class = $worksheet->getCellByColumnAndRow(9, $row)->getValue();
                    $group_class = $worksheet->getCellByColumnAndRow(10, $row)->getValue();
                    $kode_kota = $worksheet->getCellByColumnAndRow(11, $row)->getValue();
                    $nama_kota = $worksheet->getCellByColumnAndRow(12, $row)->getValue();
                    $kode_kecamatan = $worksheet->getCellByColumnAndRow(13, $row)->getValue();
                    $nama_kecamatan = $worksheet->getCellByColumnAndRow(14, $row)->getValue();
                    $kode_kelurahan = $worksheet->getCellByColumnAndRow(15, $row)->getValue();
                    $nama_kelurahan = $worksheet->getCellByColumnAndRow(16, $row)->getValue();

                    $cek = "select * from management_raw.raw_customer_batulicin a where a.customer_id_nd6 = '$customer_id_nd6'";
                    if($this->db->query($cek)->num_rows() > 0){

                        // echo "update";
                        $data = [
                            'customer_id'      => $customer_id,
                            'nama_customer'      => $nama_customer,
                            'alamat'      => $alamat,
                            'kode_type'      => $kode_type,
                            'nama_type'      => $nama_type,
                            'sektor'      => $sektor,
                            'segment'      => $segment,
                            'kode_class'      => $kode_class,
                            'nama_class'      => $nama_class,
                            'group_class'      => $group_class,
                            'kode_kota'      => $kode_kota,
                            'nama_kota'      => $nama_kota,
                            'kode_kecamatan'      => $kode_kecamatan,
                            'nama_kecamatan'      => $nama_kecamatan,
                            'kode_kelurahan'      => $kode_kelurahan,
                            'nama_kelurahan'      => $nama_kelurahan,
                            'signature' => $signature,
                            'created_at'    => $created_at,
                            'created_by'    => $this->session->userdata('id')
                        ];

                        $this->db->where('customer_id_nd6', $customer_id_nd6);
                        $this->db->update('management_raw.raw_customer_batulicin', $data);


                    }else{
                        // echo "insert";
                        $data = [
                            'customer_id'      => $customer_id,
                            'customer_id_nd6'      => $customer_id_nd6,
                            'nama_customer'      => $nama_customer,
                            'alamat'      => $alamat,
                            'kode_type'      => $kode_type,
                            'nama_type'      => $nama_type,
                            'sektor'      => $sektor,
                            'segment'      => $segment,
                            'kode_class'      => $kode_class,
                            'nama_class'      => $nama_class,
                            'group_class'      => $group_class,
                            'kode_kota'      => $kode_kota,
                            'nama_kota'      => $nama_kota,
                            'kode_kecamatan'      => $kode_kecamatan,
                            'nama_kecamatan'      => $nama_kecamatan,
                            'kode_kelurahan'      => $kode_kelurahan,
                            'nama_kelurahan'      => $nama_kelurahan,
                            'signature' => $signature,
                            'created_at'    => $created_at,
                            'created_by'    => $this->session->userdata('id')
                        ];
    
                        $this->db->insert('management_raw.temp_raw_customer_batulicin',$data);

                    }

                    
                }
            }
        }

        $update = "
            update management_raw.raw_customer_batulicin a 
            set a.nama_type = (
                select b.nama_type
                from mpm.tbl_bantu_type b
                where a.kode_type = b.kode_type
            ), a.sektor = (
                select b.sektor
                from mpm.tbl_bantu_type b
                where a.kode_type = b.kode_type
            ), a.segment = (
                select b.segment
                from mpm.tbl_bantu_type b
                where a.kode_type = b.kode_type
            ), a.nama_class = (
                select c.jenis
                from mpm.tbl_tabsalur c 
                where a.kode_class = c.kode
            ), a.group_class = (
                select c.`group`
                from mpm.tbl_tabsalur c 
                where a.kode_class = c.kode
            )
        ";
        $proses_update = $this->db->query($update);

        // echo "<pre>";
        // print_r($update);
        // echo "proses_update : ".$proses_update;
        // echo "</pre>";

        $get_count = "select count(*) as count from management_raw.raw_customer_batulicin a where a.signature = '$signature'";
        $count = $this->db->query($get_count)->row()->count;

        $upload = [
            'site_code'     => 'SSMS9',
            'signature'     => $signature,
            'filename'      => $filename,
            'type_file'      => 'raw_customer',
            'count_raw'      => $count,
            'created_at'    => $created_at,
            'created_by'    => $this->session->userdata('id')
        ];
        $this->db->insert('management_raw.log_upload', $upload);

        redirect('management_raw/customer_batulicin/');
    }

    public function proses_mapping_banjarmasin(){

        $signature = $this->input->post('signature');
        $update_kodeproduk = $this->model_management_raw->update_kodeproduk('SSMS9', $signature);
        if ($update_kodeproduk) {            
            echo "<br><center><i>sukses menambahkan angka 0 di kodeproduk ... </i></b><br>";
        }
        
        $inner_kodeproduk = $this->model_management_raw->inner_kodeproduk_banjarmasin('SSMS9', $signature);
        if ($inner_kodeproduk) { 
            echo "<br><center><i>sukses memfilter produk khusus mpm / membuang produk selain mpm ...</i></b><br>";
        }

        $update_namaprod = $this->model_management_raw->update_namaprod('SSMS9', $signature);
        if ($update_namaprod) {
            echo "<br><center><i>updating namaproduk done ...</i></b><br>";
        }

        $update_branch = $this->model_management_raw->update_branch('SSMS9', $signature);
        if ($update_branch) {                       
            echo "<br><center><i>updating branch_name dan nama_comp done ...</i></b><br>";
        }

        $update_tanggal = $this->model_management_raw->update_tanggal('SSMS9', $signature);                       
        if ($update_tanggal) {
            echo "<br><center><i>sukses memperbaiki format tanggal ...</i></b><br>";
        }

        $prepare_tblang_bantu = $this->model_management_raw->prepare_tblang_bantu('SSMS9', $signature);
        if ($prepare_tblang_bantu) {
            echo "<br><center><i>prepare_tblang_bantu done ...</i></b><br>";
        }

        $delete_tabel = $this->model_management_raw->delete_tabel('SSMS9', $signature);
        if ($delete_tabel) {
            echo "<br><center><i>delete tabel done ...</i></b><br>";
        }

        // update customer, type, class, dll

        $update_inner_customer_id_banjarmasin = $this->model_management_raw->update_inner_customer_id_banjarmasin($signature);           
        if ($update_inner_customer_id_banjarmasin) {
            echo "<br><center><i>updating customer_id, nama_customer, alamat, kode_type, nama_type, kode_class, nama_class, group_class done ...</i></b><br>";
        }

        $insert_fi = $this->model_management_raw->insert_fi_banjarmasin('SSMS9', $signature);
        if ($insert_fi) {
            echo "<br><center><i>insert fi (sales) done ...</i></b><br>";
        }

        $insert_ri = $this->model_management_raw->insert_ri_banjarmasin('SSMS9', $signature);
        if ($insert_ri) {
            echo "<br><center><i>insert ri (sales) done ...</i></b><br>";
        }

        $insert_tblang_bantu = $this->model_management_raw->insert_tblang_bantu_banjarmasin('SSMS9', $signature);
        if ($insert_tblang_bantu) {
            echo "<br><center><i>insert tblang bantu done ...</i></b><br>";
        }

        $update_tblang_bantu = $this->model_management_raw->update_tblang_bantu('SSMS9', $signature);
        if ($update_tblang_bantu) {
            echo "<br><center><i>update_ tblang bantu done ...</i></b><br>";
        }

        $insert_tblang = $this->model_management_raw->insert_tblang_banjarmasin('SSMS9', $signature);
        if ($insert_tblang) {
            echo "<br><center><i>insert tblang done ...</i></b><br>";
        }

        $insert_tabsales = $this->model_management_raw->insert_tabsales_batulicin('SSMS9', $signature);
        if ($insert_tabsales) {
            echo "<br><center><i>insert tabsales done ...</i></b><br>";
        }

        $insert_tbkota = $this->model_management_raw->insert_tbkota_banjarmasin('SSMS9', $signature);
        if ($insert_tbkota) {
            echo "<br><center><i>insert tbkota done ...</i></b><br>";
        }       

        $get_count = "select count(*) as count from management_raw.inner_raw_banjarmasin a where a.signature = '$signature'";
        $count = $this->db->query($get_count)->row()->count;

        $get_bulan = $this->model_management_raw->get_log_upload_by_signature($signature)->row()->bulan;
        $get_tahun = $this->model_management_raw->get_log_upload_by_signature($signature)->row()->tahun;

        $get_omzet = $this->model_management_raw->get_omzet_web($get_tahun, $get_bulan, 'SSMS9')->row()->omzet;

        $update = [
            'count_mapping'  => $count,
            'omzet_web'     => $get_omzet,
        ];
        $this->db->where('signature', $signature);
        $this->db->update('management_raw.log_upload', $update);

        $insert_mpm_upload = $this->model_management_raw->insert_mpm_upload('SSMS9', $signature, $get_omzet);
        if ($insert_mpm_upload) {
            echo "<br><center><i>insert mpm.upload done ...</i></b><br>";
        }

        echo "<br><center><i>dalam 5 detik anda akan di redirect ke halaman awal ...</i></b><br>";
        header('Refresh: 5; URL='.base_url().'management_raw/banjarmasin');
    
    }

    public function proses_mapping_banjarmasin_old(){

        $signature = $this->input->post('signature');
        $update_kodeproduk = $this->model_management_raw->update_kodeproduk('SSMS9', $signature);
        if ($update_kodeproduk) {            
            echo "<br><center><i>sukses menambahkan angka 0 di kodeproduk ... </i></b><br>";
        }
        
        $inner_kodeproduk = $this->model_management_raw->inner_kodeproduk_banjarmasin('SSMS9', $signature);
        if ($inner_kodeproduk) { 
            echo "<br><center><i>sukses memfilter produk khusus mpm / membuang produk selain mpm ...</i></b><br>";
        }

        $update_namaprod = $this->model_management_raw->update_namaprod('SSMS9', $signature);
        if ($update_namaprod) {
            echo "<br><center><i>updating namaproduk done ...</i></b><br>";
        }

        $update_branch = $this->model_management_raw->update_branch('SSMS9', $signature);
        if ($update_branch) {                       
            echo "<br><center><i>updating branch_name dan nama_comp done ...</i></b><br>";
        }

        $update_tanggal = $this->model_management_raw->update_tanggal('SSMS9', $signature);                       
        if ($update_tanggal) {
            echo "<br><center><i>sukses memperbaiki format tanggal ...</i></b><br>";
        }

        $delete_tabel = $this->model_management_raw->delete_tabel('SSMS9', $signature);
        if ($delete_tabel) {
            echo "<br><center><i>delete tabel done ...</i></b><br>";
        }

        // update customer, type, class, dll

        $update_inner_customer_id_banjarmasin = $this->model_management_raw->update_inner_customer_id_banjarmasin($signature);           
        if ($update_inner_customer_id_banjarmasin) {
            echo "<br><center><i>updating customer_id, nama_customer, alamat, kode_type, nama_type, kode_class, nama_class, group_class done ...</i></b><br>";
        }

        $insert_fi = $this->model_management_raw->insert_fi_banjarmasin('SSMS9', $signature);
        if ($insert_fi) {
            echo "<br><center><i>insert fi (sales) done ...</i></b><br>";
        }

        $insert_ri = $this->model_management_raw->insert_ri_banjarmasin('SSMS9', $signature);
        if ($insert_ri) {
            echo "<br><center><i>insert ri (sales) done ...</i></b><br>";
        }

        $insert_tblang = $this->model_management_raw->insert_tblang_batulicin('SSMS9', $signature);
        if ($insert_tblang) {
            echo "<br><center><i>insert tblang done ...</i></b><br>";
        }

        $insert_tabsales = $this->model_management_raw->insert_tabsales_batulicin('SSMS9', $signature);
        if ($insert_tabsales) {
            echo "<br><center><i>insert tabsales done ...</i></b><br>";
        }

        $insert_tbkota = $this->model_management_raw->insert_tbkota_banjarmasin('SSMS9', $signature);
        if ($insert_tbkota) {
            echo "<br><center><i>insert tbkota done ...</i></b><br>";
        }       

        $get_count = "select count(*) as count from management_raw.inner_raw_banjarmasin a where a.signature = '$signature'";
        $count = $this->db->query($get_count)->row()->count;

        $get_bulan = $this->model_management_raw->get_log_upload_by_signature($signature)->row()->bulan;
        $get_tahun = $this->model_management_raw->get_log_upload_by_signature($signature)->row()->tahun;

        $get_omzet = $this->model_management_raw->get_omzet_web($get_tahun, $get_bulan, 'SSMS9')->row()->omzet;

        $update = [
            'count_mapping'  => $count,
            'omzet_web'     => $get_omzet,
        ];
        $this->db->where('signature', $signature);
        $this->db->update('management_raw.log_upload', $update);

        echo "<br><center><i>dalam 5 detik anda akan di redirect ke halaman awal ...</i></b><br>";
        header('Refresh: 5; URL='.base_url().'management_raw/banjarmasin');
    
    }

    public function proses_mapping_customer_banjarmasin(){

        $signature = $this->input->post('signature');
        $trim_customer_banjarmasin = $this->model_management_raw->trim_customer_banjarmasin($signature);

        if ($trim_customer_banjarmasin) {
            echo "<br><center><i>trimming kode_type, kode_class, customer_id, customer_id_nd6 done ... </i></b><br>";
            $update_type_n_class = $this->model_management_raw->update_type_n_class($signature);
        
            if ($update_type_n_class) {
                echo "<br><center><i>updating nama_type, sektor, segment, nama_class done ... </i></b><br>";
                
                $inner = $this->model_management_raw->inner_customer_banjarmasin($signature);
                if ($inner) {
                    
                    echo "<br><center><i>deleting redundance customer_id_nd5 done ... </i></b><br>";
                    $get_count = "select count(*) as count from management_raw.inner_raw_customer_banjarmasin a where a.signature = '$signature'";
                    $count = $this->db->query($get_count)->row()->count;

                    $update = [
                        'count_mapping'  => $count
                    ];
                    $this->db->where('signature', $signature);
                    $this->db->update('management_raw.log_upload', $update);

                    echo "<br><center><i>dalam 5 detik anda akan di redirect ke halaman awal ...</i></b><br>";
                    header('Refresh: 5; URL='.base_url().'management_raw/customer_banjarmasin');

                }

            }
        }
    }
    
    

    public function download_raw($site_code, $signature){

        if ($site_code == 'SSJD2') {
            $params = "inner_raw_batulicin";
            $title = "batulicin";
        }elseif ($site_code == 'SSMS9') {
            $params = "inner_raw_banjarmasin";
            $title = "banjarmasin";
        }

        $query = "
            select *
            from management_raw.$params a 
            where a.signature = '$signature'
        ";
        $hsl = $this->db->query($query);
        query_to_csv($hsl,TRUE,"raw $title already mapping.csv");
    }
    

    public function download_customer_banjarmasin($signature){
        $query = "
            select *
            from management_raw.inner_raw_customer_banjarmasin a 
            where a.signature = '$signature'
        ";
        $hsl = $this->db->query($query);
        query_to_csv($hsl,TRUE,'customer_banjarmasin_already_mapping.csv');
    }


    public function batulicin(){

        $data = [
            'title'     => 'Management Raw / Import data batulicin',
            'id'        => $this->session->userdata('id'),
            'url_import'=> 'management_raw/import_batulicin',
            'get_log_upload'    => $this->model_management_raw->get_log_upload('SSJD2')
        ];
        $this->load->view('management_raw/header');
        $this->load->view('management_raw/batulicin', $data);
        $this->load->view('mes/footer');
    }

    public function template_batulicin(){        
        $query = "
            select  '' as distributor, '' as cabang, '' as tipetrans, '' as divisi, '' as principal, '' as productgroup1, '' as productgroup2, '' as productgroup3, '' as brand, '' as kodeproduk, '' as kodevarian, '' as kodeprodukprincipal, '' as namaproduk, '' as packaging, '' as productclass, '' as kodecustomer,'' as namacustomer, '' as alamatcustomer, '' as area, '' as subarea, '' as channel, '' as subchannel, '' as customergroup, '' as keyaccount, '' as kodesalesman, '' as namasalesman, '' as kodesalesco, '' as namasalesco, '' as kodespv, '' as namaspv, '' as tahunbulan, '' as bulan, '' as tanggal, '' as weekno, '' as nomornota, '' as salesmethod, '' as sellingtype, '' as qtysold, '' as kartonutuh, '' as qtysoldpcs, '' as freegoodpcs, '' as tonnage, '' as volumeltr, '' as grossamount, '' as linediscount1, '' as linediscount2, '' as linediscount3, '' as linediscount4, '' as linediscount5, '' as totallinediscount, '' as discountnota1, '' as discountnota2, '' as discountnota3, '' as totaldiscountnota, '' as dpp, '' as ppn, '' as ppnbm, '' as tax3, '' as netamount, '' as warehouse, '' as customerpo, '' as customerjoindate, '' as nofakturpajak, '' as tglfakturpajak, '' as nomorfakturproforma, '' as tglfakturproforma, '' as cogs, '' as caseweightinkg, '' as end
        ";

        $hsl = $this->db->query($query);
        query_to_csv($hsl,TRUE,'Template_Batulicin.csv');
    }

    public function import_batulicin(){
        $bulan_from_input = substr($this->input->post('bulan'), 5, 2);
        $tahun_from_input = substr($this->input->post('bulan'), 0, 4);

        $cek_last_upload = "
            select *
            from mpm.upload a 
            where a.userid = 624 
            ORDER BY a.id desc
        ";
        $tahun_last_upload = $this->db->query($cek_last_upload)->row()->tahun;
        $bulan_last_upload = $this->db->query($cek_last_upload)->row()->bulan;
        $closing_last_upload = $this->db->query($cek_last_upload)->row()->status_closing;

        if ($closing_last_upload == 1) {
            $tanggal_seharusnya = date('Y-m-d', strtotime('+1 month', strtotime($tahun_last_upload.'-'.$bulan_last_upload.'-01')));           
            $bulan_seharusnya = date('m', strtotime($tanggal_seharusnya));
            $tahun_seharusnya = date('Y', strtotime($tanggal_seharusnya));

            if ($tahun_from_input === $tahun_seharusnya && $bulan_from_input === $bulan_seharusnya) {
                
            }else{
                echo "<br><hr>Tanggal yang anda pilih adalah :<br><br>";
                // echo "<hr>";
                echo "tahun : ".$tahun_from_input."<br>";
                echo "bulan : ".$bulan_from_input."<br><br>";
                echo "<hr>";

                echo "<br>Tanggal upload terakhir adalah :<br><br>";
                // echo "<hr>";
                echo "tahun_last_upload : ".$tahun_last_upload."<br>";
                echo "bulan_last_upload : ".$bulan_last_upload."<br>";
                echo "closing_last_upload : ".$closing_last_upload."<br><br>";
                echo "<hr>";
                
                echo "<h3>Result</h3>";
                echo "Data ditolak !!<br><br>";
                echo "Anda hanya diijinkan mengupload data untuk <b>tahun : $tahun_seharusnya</b>, dan <b>bulan : $bulan_seharusnya</b><br>";

                echo "<br>anda akan di redirect ke menu awal dalam 10 detik";
                header('Refresh: 10; URL='.base_url().'management_raw/batulicin');
                die;
            }

        }else{

            $tanggal_seharusnya = date('Y-m-d', strtotime('+0 month', strtotime($tahun_last_upload.'-'.$bulan_last_upload.'-01')));           
            $bulan_seharusnya = date('m', strtotime($tanggal_seharusnya));
            $tahun_seharusnya = date('Y', strtotime($tanggal_seharusnya));

            if ($tahun_from_input === $tahun_seharusnya && $bulan_from_input === $bulan_seharusnya) {
                // echo "diijinkan";
            }else{
                echo "<br><hr>Tanggal yang anda pilih adalah :<br><br>";
                echo "tahun : ".$tahun_from_input."<br>";
                echo "bulan : ".$bulan_from_input."<br><br>";
                echo "<hr>";


                echo "<br>Tanggal upload terakhir adalah :<br><br>";
                echo "tahun_last_upload : ".$tahun_last_upload."<br>";
                echo "bulan_last_upload : ".$bulan_last_upload."<br>";
                echo "closing_last_upload : ".$closing_last_upload."<br><br>";
                echo "<hr>";
                
                echo "<h3>Result</h3>";
                echo "Data ditolak !!<br><br>";
                echo "Anda hanya diijinkan mengupload data untuk <b>tahun : $tahun_seharusnya</b>, dan <b>bulan : $bulan_seharusnya</b><br>";

                echo "<br>anda akan di redirect ke menu awal dalam 10 detik";
                header('Refresh: 10; URL='.base_url().'management_raw/batulicin');
                die;
            }

        }

        // die;

        if (!is_dir('./assets/uploads/management_raw/import/')) {
            @mkdir('./assets/uploads/management_raw/import/', 0777);
        }

        $this->load->library('upload'); // Load librari upload        
        $config['upload_path'] = './assets/uploads/management_raw/import/';
        // $config['allowed_types'] = 'xls|xlsx|csv';
        $config['allowed_types'] = '*';    
        $config['max_size']  = '2048000';
        $config['overwrite'] = true;
        $config['encrypt_name'] = TRUE;
        $this->upload->initialize($config);

        if ($this->upload->do_upload('file')) 
        {

            $upload_data = $this->upload->data();
            $filename = $upload_data['orig_name'];

            $this->load->library('excel');
            $object = PHPExcel_IOFactory::load("assets/uploads/management_raw/import/$filename");

            $jumlahSheet = $object->getSheetCount();
            if ($jumlahSheet > 1) {
                echo "jumlah_sheet : ".$jumlahSheet;
                echo "<script>alert('upload file gagal karena file mempunyai lebih dari 1 sheet'); </script>";
                redirect('management_raw','refresh');
            }

            // die;

            $created_at = $this->model_outlet_transaksi->timezone();
            $signature = md5($this->model_outlet_transaksi->timezone());

            foreach ($object->getWorksheetIterator() as $worksheet) {

                $highestRow = $worksheet->getHighestRow();
                $highestColumn = $worksheet->getHighestColumn();

                for ($row = 2; $row <= $highestRow; $row++) {          
                
                    $distributor = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
                    $cabang = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
                    $tipetrans = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
                    $divisi = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
                    $principal = $worksheet->getCellByColumnAndRow(4, $row)->getValue();
                    $productgroup1 = $worksheet->getCellByColumnAndRow(5, $row)->getValue();
                    $productgroup2 = $worksheet->getCellByColumnAndRow(6, $row)->getValue();
                    $productgroup3 = $worksheet->getCellByColumnAndRow(7, $row)->getValue();
                    $brand = $worksheet->getCellByColumnAndRow(8, $row)->getValue();
                    $kodeproduk = $worksheet->getCellByColumnAndRow(9, $row)->getValue();
                    $kodevarian = $worksheet->getCellByColumnAndRow(10, $row)->getValue();
                    $kodeprodukprincipal = $worksheet->getCellByColumnAndRow(11, $row)->getValue();
                    $namaproduk = $worksheet->getCellByColumnAndRow(12, $row)->getValue();
                    $packaging = $worksheet->getCellByColumnAndRow(13, $row)->getValue();
                    $productclass = $worksheet->getCellByColumnAndRow(14, $row)->getValue();
                    $kodecustomer = $worksheet->getCellByColumnAndRow(15, $row)->getValue();
                    $namacustomer = $worksheet->getCellByColumnAndRow(16, $row)->getValue();
                    $alamatcustomer = $worksheet->getCellByColumnAndRow(17, $row)->getValue();
                    $area = $worksheet->getCellByColumnAndRow(18, $row)->getValue();
                    $subarea = $worksheet->getCellByColumnAndRow(19, $row)->getValue();
                    $channel = $worksheet->getCellByColumnAndRow(20, $row)->getValue();
                    $subchannel = $worksheet->getCellByColumnAndRow(21, $row)->getValue();
                    $customergroup = $worksheet->getCellByColumnAndRow(22, $row)->getValue();
                    $keyaccount = $worksheet->getCellByColumnAndRow(23, $row)->getValue();
                    $kodesalesman = $worksheet->getCellByColumnAndRow(24, $row)->getValue();
                    $namasalesman = $worksheet->getCellByColumnAndRow(25, $row)->getValue();
                    $kodesalesco = $worksheet->getCellByColumnAndRow(26, $row)->getValue();
                    $namasalesco = $worksheet->getCellByColumnAndRow(27, $row)->getValue();
                    $kodespv = $worksheet->getCellByColumnAndRow(28, $row)->getValue();
                    $namaspv = $worksheet->getCellByColumnAndRow(29, $row)->getValue();
                    $tahunbulan = $worksheet->getCellByColumnAndRow(30, $row)->getValue();
                    $bulan = $worksheet->getCellByColumnAndRow(31, $row)->getValue();
                    $tanggal = $worksheet->getCellByColumnAndRow(32, $row)->getValue();
                    $weekno = $worksheet->getCellByColumnAndRow(33, $row)->getValue();
                    $nomornota = $worksheet->getCellByColumnAndRow(34, $row)->getValue();
                    $salesmethod = $worksheet->getCellByColumnAndRow(35, $row)->getValue();
                    $sellingtype = $worksheet->getCellByColumnAndRow(36, $row)->getValue();
                    $qtysold = $worksheet->getCellByColumnAndRow(37, $row)->getValue();
                    $kartonutuh = $worksheet->getCellByColumnAndRow(38, $row)->getValue();
                    $qtysoldpcs = $worksheet->getCellByColumnAndRow(39, $row)->getValue();
                    $freegoodpcs = $worksheet->getCellByColumnAndRow(40, $row)->getValue();
                    $tonnage = $worksheet->getCellByColumnAndRow(41, $row)->getValue();
                    $volumeltr = $worksheet->getCellByColumnAndRow(42, $row)->getValue();
                    $grossamount = $worksheet->getCellByColumnAndRow(43, $row)->getValue();
                    $linediscount1 = $worksheet->getCellByColumnAndRow(44, $row)->getValue();
                    $linediscount2 = $worksheet->getCellByColumnAndRow(45, $row)->getValue();
                    $linediscount3 = $worksheet->getCellByColumnAndRow(46, $row)->getValue();
                    $linediscount4 = $worksheet->getCellByColumnAndRow(47, $row)->getValue();
                    $linediscount5 = $worksheet->getCellByColumnAndRow(48, $row)->getValue();
                    $totallinediscount = $worksheet->getCellByColumnAndRow(49, $row)->getValue();
                    $discountnota1 = $worksheet->getCellByColumnAndRow(50, $row)->getValue();
                    $discountnota2 = $worksheet->getCellByColumnAndRow(51, $row)->getValue();
                    $discountnota3 = $worksheet->getCellByColumnAndRow(52, $row)->getValue();
                    $totaldiscountnota = $worksheet->getCellByColumnAndRow(53, $row)->getValue();
                    $dpp = $worksheet->getCellByColumnAndRow(54, $row)->getValue();
                    $ppn = $worksheet->getCellByColumnAndRow(55, $row)->getValue();
                    $ppnbm = $worksheet->getCellByColumnAndRow(56, $row)->getValue();
                    $tax3 = $worksheet->getCellByColumnAndRow(57, $row)->getValue();
                    $netamount = $worksheet->getCellByColumnAndRow(58, $row)->getValue();
                    $warehouse = $worksheet->getCellByColumnAndRow(59, $row)->getValue();
                    $customerpo = $worksheet->getCellByColumnAndRow(60, $row)->getValue();
                    $customerjoindate = $worksheet->getCellByColumnAndRow(61, $row)->getValue();
                    $nofakturpajak = $worksheet->getCellByColumnAndRow(62, $row)->getValue();
                    $tglfakturpajak = $worksheet->getCellByColumnAndRow(63, $row)->getValue();
                    $nomorfakturproforma = $worksheet->getCellByColumnAndRow(64, $row)->getValue();
                    $tglfakturproforma = $worksheet->getCellByColumnAndRow(65, $row)->getValue();
                    $cogs = $worksheet->getCellByColumnAndRow(66, $row)->getValue();
                    $caseweightinkg = $worksheet->getCellByColumnAndRow(67, $row)->getValue();
                    $end = $worksheet->getCellByColumnAndRow(68, $row)->getValue();
                                        

                    $data = [
                        'distributor'      => $distributor,
                        'cabang'      => $cabang,
                        'tipetrans'      => $tipetrans,
                        'divisi'      => $divisi,
                        'principal'      => $principal,
                        'productgroup1'      => $productgroup1,
                        'productgroup2'      => $productgroup2,
                        'productgroup3'      => $productgroup3,
                        'brand'      => $brand,
                        'kodeproduk'      => $kodeproduk,
                        'kodevarian'      => $kodevarian,
                        'kodeprodukprincipal'      => $kodeprodukprincipal,
                        'namaproduk'      => $namaproduk,
                        'packaging'      => $packaging,
                        'productclass'      => $productclass,
                        'kodecustomer'      => $kodecustomer,
                        'namacustomer'      => $namacustomer,
                        'alamatcustomer'      => $alamatcustomer,
                        'area'      => $area,
                        'subarea'      => $subarea,
                        'channel'      => $channel,
                        'subchannel'      => $subchannel,
                        'customergroup'      => $customergroup,
                        'keyaccount'      => $keyaccount,
                        'kodesalesman'      => $kodesalesman,
                        'namasalesman'      => $namasalesman,
                        'kodesalesco'      => $kodesalesco,
                        'namasalesco'      => $namasalesco,
                        'kodespv'      => $kodespv,
                        'namaspv'      => $namaspv,
                        'tahunbulan'      => $tahunbulan,
                        'bulan'      => $bulan,
                        'tanggal'      => $tanggal,
                        'weekno'      => $weekno,
                        'nomornota'      => $nomornota,
                        'salesmethod'      => $salesmethod,
                        'sellingtype'      => $sellingtype,
                        'qtysold'      => $qtysold,
                        'kartonutuh'      => $kartonutuh,
                        'qtysoldpcs'      => $qtysoldpcs,
                        'freegoodpcs'      => $freegoodpcs,
                        'tonnage'      => $tonnage,
                        'volumeltr'      => $volumeltr,
                        'grossamount'      => $grossamount,
                        'linediscount1'      => $linediscount1,
                        'linediscount2'      => $linediscount2,
                        'linediscount3'      => $linediscount3,
                        'linediscount4'      => $linediscount4,
                        'linediscount5'      => $linediscount5,
                        'totallinediscount'      => $totallinediscount,
                        'discountnota1'      => $discountnota1,
                        'discountnota2'      => $discountnota2,
                        'discountnota3'      => $discountnota3,
                        'totaldiscountnota'      => $totaldiscountnota,
                        'dpp'      => $dpp,
                        'ppn'      => $ppn,
                        'ppnbm'      => $ppnbm,
                        'tax3'      => $tax3,
                        'netamount'      => $netamount,
                        'warehouse'      => $warehouse,
                        'customerpo'      => $customerpo,
                        'nofakturpajak'      => $nofakturpajak,
                        'customerjoindate'      => $customerjoindate,
                        'tglfakturpajak'      => $tglfakturpajak,
                        'nomorfakturproforma'      => $nomorfakturproforma,
                        'tglfakturproforma'      => $tglfakturproforma,
                        'cogs'      => $cogs,
                        'caseweightinkg'      => $caseweightinkg,
                        'end'      => $end,
                        'signature' => $signature,
                        'created_at'    => $created_at,
                        'created_by'    => $this->session->userdata('id')
                    ];

                    $this->db->insert('management_raw.raw_batulicin',$data);
                }
            }
        }else{
           
        };

        $get_count = "select count(*) as count from management_raw.raw_batulicin a where a.signature = '$signature'";
        $count = $this->db->query($get_count)->row()->count;
        
        $get_omzet = "select sum(a.grossamount * 1.11) as omzet_raw from management_raw.raw_batulicin a where a.signature = '$signature'";
        $omzet_raw = $this->db->query($get_omzet)->row()->omzet_raw;

        $upload = [
            'site_code'     => 'SSJD2',
            'filename'      => $filename,
            'signature'     => $signature,
            'count_raw'     => $count,
            'omzet_raw'     => $omzet_raw,
            'type_file'     => 'raw_sales',
            'bulan'         => $bulan_from_input,
            'tahun'         => $tahun_from_input,
            'created_at'    => $created_at,
            'created_by'    => $this->session->userdata('id')
        ];
        $this->db->insert('management_raw.log_upload', $upload);

        redirect('management_raw/import_batulicin_draft/'.$signature);
    }

    public function import_batulicin_draft($signature){

        $data = [
            'title'     => 'Management Raw / Preview Import data batulicin',
            'id'        => $this->session->userdata('id'),
            'get_raw_draft'  => $this->model_management_raw->get_raw_draft('SSJD2', $signature),
            'url'=> 'management_raw/proses_mapping_batulicin',
            'get_summary' => $this->model_management_raw->get_summary_raw_sales('SSJD2', $signature),
            'signature' => $signature
        ];
        $this->load->view('management_raw/header');
        $this->load->view('management_raw/draft_batulicin', $data);
        $this->load->view('mes/footer');
    }

    public function proses_mapping_batulicin(){

        $signature = $this->input->post('signature');
        $id_imports = $this->input->post('options');

        $update_kodeproduk = $this->model_management_raw->update_kodeproduk('SSJD2', $signature);
        if ($update_kodeproduk) {   
            echo "<br><center><i>sukses menambahkan angka 0 di kodeproduk ... </i></b><br>";
        }

        $inner_kodeproduk = $this->model_management_raw->inner_kodeproduk('SSJD2', $signature);
        if ($inner_kodeproduk) {
            echo "<br><center><i>sukses memfilter produk khusus mpm / membuang produk selain mpm ...</i></b><br>";
        }

        $update_namaprod = $this->model_management_raw->update_namaprod('SSJD2', $signature);
        if ($update_namaprod) {
            echo "<br><center><i>sukses mengupdate namaproduk ...</i></b><br>";
        }

        $update_branch = $this->model_management_raw->update_branch('SSJD2', $signature);
        if ($update_branch) {
            echo "<br><center><i>sukses mengupdate branch_name dan nama_comp ...</i></b><br>";
        }

        $update_tanggal = $this->model_management_raw->update_tanggal('SSJD2', $signature);                       
        if ($update_tanggal) {
            echo "<br><center><i>sukses memperbaiki format tanggal ...</i></b><br>";
        }

        $update_inner_customer_id_batulicin = $this->model_management_raw->update_inner_customer_id_batulicin($signature);
        if ($update_inner_customer_id_batulicin) {
            echo "<br><center><i>updating customer_id, nama_customer, alamat done ...</i></b><br>";
        }

        $update_master_customer_batulicin = $this->model_management_raw->update_master_customer_batulicin($signature);
        if ($update_master_customer_batulicin) {
            echo "<br><center><i>penambahan master customer batulicin done ...</i></b><br>";
        }else{
            echo "<br><center><i>tidak ada penambahan master customer batulicin ...</i></b><br>";
        }

        $update_inner_customer_id_batulicin = $this->model_management_raw->update_inner_customer_id_batulicin($signature);
        if ($update_inner_customer_id_batulicin) {
            echo "<br><center><i>updating ulang customer_id, nama_customer, alamat done ...</i></b><br>";
        }

        $prepare_tblang_bantu = $this->model_management_raw->prepare_tblang_bantu('SSJD2', $signature);
        if ($prepare_tblang_bantu) {
            echo "<br><center><i>prepare_tblang_bantu done ...</i></b><br>";
        }

        $delete_tabel = $this->model_management_raw->delete_tabel('SSJD2', $signature);
        if ($delete_tabel) {
            echo "<br><center><i>delete tabel done ...</i></b><br>";
        }

        $insert_fi = $this->model_management_raw->insert_fi_batulicin('SSJD2', $signature);
        if ($insert_fi) {
            echo "<br><center><i>insert fi (sales) done ...</i></b><br>";
        }

        $insert_ri = $this->model_management_raw->insert_ri_batulicin('SSJD2', $signature);
        if ($insert_ri) {
            echo "<br><center><i>insert ri (retur) done ...</i></b><br>";
        }

        $insert_tblang_bantu = $this->model_management_raw->insert_tblang_bantu_banjarmasin('SSJD2', $signature);
        if ($insert_tblang_bantu) {
            echo "<br><center><i>insert tblang bantu done ...</i></b><br>";
        }

        // die;

        $update_tblang_bantu = $this->model_management_raw->update_tblang_bantu('SSJD2', $signature);
        if ($update_tblang_bantu) {
            echo "<br><center><i>update_ tblang bantu done ...</i></b><br>";
        }

        $insert_tblang = $this->model_management_raw->insert_tblang_banjarmasin('SSJD2', $signature);
        if ($insert_tblang) {
            echo "<br><center><i>insert tblang done ...</i></b><br>";
        }

        // die;

        // $insert_tblang = $this->model_management_raw->insert_tblang_batulicin('SSJD2', $signature);
        // if ($insert_tblang) {
        //     echo "<br><center><i>insert tblang done ...</i></b><br>";
        // }

        $insert_tabsales = $this->model_management_raw->insert_tabsales_batulicin('SSJD2', $signature);
        if ($insert_tabsales) {
            echo "<br><center><i>insert tabsales done ...</i></b><br>";
        }

        $insert_tbkota = $this->model_management_raw->insert_tbkota_batulicin('SSJD2', $signature);
        if ($insert_tbkota) {
            echo "<br><center><i>insert tbkota done ...</i></b><br>";
        }        

        $get_count = "select count(*) as count from management_raw.inner_raw_batulicin a where a.signature = '$signature'";
        $count = $this->db->query($get_count)->row()->count;

        $get_bulan = $this->model_management_raw->get_log_upload_by_signature($signature)->row()->bulan;
        $get_tahun = $this->model_management_raw->get_log_upload_by_signature($signature)->row()->tahun;

        $get_omzet = $this->model_management_raw->get_omzet_web($get_tahun, $get_bulan, 'SSJD2')->row()->omzet;

        $update = [
            'count_mapping' => $count,
            'omzet_web'     => $get_omzet,
        ];
        $this->db->where('signature', $signature);
        $this->db->update('management_raw.log_upload', $update);

        $insert_mpm_upload = $this->model_management_raw->insert_mpm_upload('SSJD2', $signature, $get_omzet);
        if ($insert_mpm_upload) {
            echo "<br><center><i>insert mpm.upload done ...</i></b><br>";
        }
        
        echo "<br><center><i>dalam 5 detik anda akan di redirect ke halaman awal ...</i></b><br>";
        header('Refresh: 5; URL='.base_url().'management_raw/batulicin');    
    }

    public function proses_mapping_barabai(){

        $signature = $this->input->post('signature');
        $id_imports = $this->input->post('options');

        $update_kodeproduk = $this->model_management_raw->update_kodeproduk_left('BRBS0', $signature);
        if ($update_kodeproduk) {
            echo "<br><center><i>fix kodeproduk dan kodeprodukprincipal ... </i></b><br>";
        }

        $inner_kodeproduk = $this->model_management_raw->inner_kodeproduk_barabai('BRBS0', $signature);
        if ($inner_kodeproduk) {
            echo "<br><center><i>sukses memfilter produk khusus mpm / membuang produk selain mpm ...</i></b><br>";
        }

        $update_namaprod = $this->model_management_raw->update_namaprod('BRBS0', $signature);
        if ($update_namaprod) {
            echo "<br><center><i>sukses mengupdate namaproduk ...</i></b><br>";
        }
        
        $update_branch = $this->model_management_raw->update_branch('BRBS0', $signature);
        if ($update_branch) {
            echo "<br><center><i>sukses mengupdate branch_name dan nama_comp ...</i></b><br>";
        }

        $update_tanggal = $this->model_management_raw->update_tanggal('BRBS0', $signature);                       
        if ($update_tanggal) {
            echo "<br><center><i>sukses memperbaiki format tanggal ...</i></b><br>";
        }

        $prepare_tblang_bantu = $this->model_management_raw->prepare_tblang_bantu('BRBS0', $signature);
        if ($prepare_tblang_bantu) {
            echo "<br><center><i>prepare_tblang_bantu done ...</i></b><br>";
        }

        $delete_tabel = $this->model_management_raw->delete_tabel('BRBS0', $signature);
        if ($delete_tabel) {
            echo "<br><center><i>delete tabel done ...</i></b><br>";
        }

        // update customer, type, class, dll

        $update_inner_customer_id_barabai = $this->model_management_raw->update_inner_customer_id_barabai($signature);           
        if ($update_inner_customer_id_barabai) {
            echo "<br><center><i>updating customer_id, nama_customer, alamat, kode_type, nama_type, kode_class, nama_class, group_class done ...</i></b><br>";
        }

        $insert_fi = $this->model_management_raw->insert_fi_barabai('BRBS0', $signature);
        if ($insert_fi) {
            echo "<br><center><i>insert fi (sales) done ...</i></b><br>";
        }

        $insert_ri = $this->model_management_raw->insert_ri_barabai('BRBS0', $signature);
        if ($insert_ri) {
            echo "<br><center><i>insert ri (sales) done ...</i></b><br>";
        }

        $insert_tblang_bantu = $this->model_management_raw->insert_tblang_bantu_banjarmasin('BRBS0', $signature);
        if ($insert_tblang_bantu) {
            echo "<br><center><i>insert tblang bantu done ...</i></b><br>";
        }

        $update_tblang_bantu = $this->model_management_raw->update_tblang_bantu('BRBS0', $signature);
        if ($update_tblang_bantu) {
            echo "<br><center><i>update_ tblang bantu done ...</i></b><br>";
        }

        $insert_tblang = $this->model_management_raw->insert_tblang_banjarmasin('BRBS0', $signature);
        if ($insert_tblang) {
            echo "<br><center><i>insert tblang done ...</i></b><br>";
        }

        $insert_tabsales = $this->model_management_raw->insert_tabsales_batulicin('BRBS0', $signature);
        if ($insert_tabsales) {
            echo "<br><center><i>insert tabsales done ...</i></b><br>";
        }

        $insert_tbkota = $this->model_management_raw->insert_tbkota_banjarmasin('BRBS0', $signature);
        if ($insert_tbkota) {
            echo "<br><center><i>insert tbkota done ...</i></b><br>";
        }

        $get_count = "select count(*) as count from management_raw.inner_raw_barabai a where a.signature = '$signature'";
        $count = $this->db->query($get_count)->row()->count;

        $get_bulan = $this->model_management_raw->get_log_upload_by_signature($signature)->row()->bulan;
        $get_tahun = $this->model_management_raw->get_log_upload_by_signature($signature)->row()->tahun;
        $get_omzet = $this->model_management_raw->get_omzet_web($get_tahun, $get_bulan, 'BRBS0')->row()->omzet;

        $update = [
            'count_mapping' => $count,
            'omzet_web'     => $get_omzet,
        ];
        $this->db->where('signature', $signature);
        $this->db->update('management_raw.log_upload', $update);

        $insert_mpm_upload = $this->model_management_raw->insert_mpm_upload('BRBS0', $signature, $get_omzet);
        if ($insert_mpm_upload) {
            echo "<br><center><i>insert mpm.upload done ...</i></b><br>";
        }

        echo "<br><center><i>dalam 5 detik anda akan di redirect ke halaman awal ...</i></b><br>";
        header('Refresh: 5; URL='.base_url().'management_raw/barabai');    
        
        // die;
    
    }

    public function samarinda(){
        $data = [
            'title'     => 'Management Raw / Import data samarinda',
            'id'        => $this->session->userdata('id'),
            'url_import'=> 'management_raw/import_samarinda',
            'get_log_upload'    => $this->model_management_raw->get_log_upload('SMRB7')
        ];
        $this->load->view('management_raw/header');
        $this->load->view('management_raw/samarinda', $data);
        $this->load->view('mes/footer');
    }

    public function template_samarinda(){        
        $query = "
            select  '' as tipe_order, '' as status_cutomer, '' as group_divisi, '' as flag_sp_tt, '' as tipe, '' as status_site, '' as nama_branch, 
                    '' as siteid, '' as nama_site, '' as salesmanid, '' as nama_salesman, '' as status_faktur, '' as type_sales, '' as tipe_trans,
                    '' as categoryid, '' as nama_category, '' as productid, '' as nama_invoice, '' as ket, '' as qty_kecil, '' as qty_bonus, 
                    '' as rp_kotor, '' as rp_discount, '' as rp_netto, '' as brandid, '' as nama_brand, '' as varianid, '' as nama_varian, 
                    '' as groupid, '' as nama_group, '' as customerid, '' as nama_customer, '' as prefix, '' as alamat, '' as segmentid, '' as nama_segment,  
                    '' as typeid, '' as nama_type, '' as group1, '' as propinsiid, '' as nama_propinsi, '' as regionalid, '' as nama_regional,
                    '' as areaid, '' as nama_area, '' as no_sales, '' as ref, '' as tgl_po, '' as tanggal, '' as month, '' as year, '' as classid,
                    '' as nama_class, '' as kotaid, '' as nama_kota, '' as kecamatanid, '' as nama_kecamatan, '' as nama_kelurahan, '' as kelurahanid,
                    '' as disc_cabang, '' as disc_prinsipal, '' as disc_xtra, '' as disc_cash, '' as subareaid, '' as subarea, '' as keterangan, 
                    '' as areaid2, '' as nama_area2, '' as spot_id, '' as nama_spot, '' as subareaid2, '' as subarea2, '' as regionalid2, '' as nama_regional2, 
                    '' as status_outlet, '' as rp_net_reguler, '' as rp_net_ritel, '' as rp_net_selisih, '' as rp_net_ritel_motoris, '' as tipe_trans1, 
                    '' as qty_kecil_crt, '' as qty_bonus_crt, '' as latitude, '' as longitude, '' as tanggal_fjp, '' as status_ob 
        ";

        $hsl = $this->db->query($query);
        query_to_csv($hsl,TRUE,'Template_samarinda.csv');
    }

    public function import_samarinda(){
        $bulan_from_input = substr($this->input->post('bulan'), 5, 2);
        $tahun_from_input = substr($this->input->post('bulan'), 0, 4);

        $cek_last_upload = "
            select *
            from mpm.upload a 
            where a.userid = 620 
            ORDER BY a.id desc
        ";
        $tahun_last_upload = $this->db->query($cek_last_upload)->row()->tahun;
        $bulan_last_upload = $this->db->query($cek_last_upload)->row()->bulan;
        $closing_last_upload = $this->db->query($cek_last_upload)->row()->status_closing;

        if ($closing_last_upload == 1) {
            $tanggal_seharusnya = date('Y-m-d', strtotime('+1 month', strtotime($tahun_last_upload.'-'.$bulan_last_upload.'-01')));           
            $bulan_seharusnya = date('m', strtotime($tanggal_seharusnya));
            $tahun_seharusnya = date('Y', strtotime($tanggal_seharusnya));

            if ($tahun_from_input === $tahun_seharusnya && $bulan_from_input === $bulan_seharusnya) {
                
            }else{
                echo "<br><hr>Tanggal yang anda pilih adalah :<br><br>";
                // echo "<hr>";
                echo "tahun : ".$tahun_from_input."<br>";
                echo "bulan : ".$bulan_from_input."<br><br>";
                echo "<hr>";

                echo "<br>Tanggal upload terakhir adalah :<br><br>";
                // echo "<hr>";
                echo "tahun_last_upload : ".$tahun_last_upload."<br>";
                echo "bulan_last_upload : ".$bulan_last_upload."<br>";
                echo "closing_last_upload : ".$closing_last_upload."<br><br>";
                echo "<hr>";
                
                echo "<h3>Result</h3>";
                echo "Data ditolak !!<br><br>";
                echo "Anda hanya diijinkan mengupload data untuk <b>tahun : $tahun_seharusnya</b>, dan <b>bulan : $bulan_seharusnya</b><br>";

                echo "<br>anda akan di redirect ke menu awal dalam 10 detik";
                header('Refresh: 10; URL='.base_url().'management_raw/samarinda');
                die;
            }

        }else{

            $tanggal_seharusnya = date('Y-m-d', strtotime('+0 month', strtotime($tahun_last_upload.'-'.$bulan_last_upload.'-01')));           
            $bulan_seharusnya = date('m', strtotime($tanggal_seharusnya));
            $tahun_seharusnya = date('Y', strtotime($tanggal_seharusnya));

            if ($tahun_from_input === $tahun_seharusnya && $bulan_from_input === $bulan_seharusnya) {
                // echo "diijinkan";
            }else{
                echo "<br><hr>Tanggal yang anda pilih adalah :<br><br>";
                echo "tahun : ".$tahun_from_input."<br>";
                echo "bulan : ".$bulan_from_input."<br><br>";
                echo "<hr>";


                echo "<br>Tanggal upload terakhir adalah :<br><br>";
                echo "tahun_last_upload : ".$tahun_last_upload."<br>";
                echo "bulan_last_upload : ".$bulan_last_upload."<br>";
                echo "closing_last_upload : ".$closing_last_upload."<br><br>";
                echo "<hr>";
                
                echo "<h3>Result</h3>";
                echo "Data ditolak !!<br><br>";
                echo "Anda hanya diijinkan mengupload data untuk <b>tahun : $tahun_seharusnya</b>, dan <b>bulan : $bulan_seharusnya</b><br>";

                echo "<br>anda akan di redirect ke menu awal dalam 10 detik";
                header('Refresh: 10; URL='.base_url().'management_raw/samarinda');
                die;
            }

        }

        // die;

        if (!is_dir('./assets/uploads/management_raw/import/')) {
            @mkdir('./assets/uploads/management_raw/import/', 0777);
        }

        $this->load->library('upload'); // Load librari upload        
        $config['upload_path'] = './assets/uploads/management_raw/import/';
        // $config['allowed_types'] = 'xls|xlsx|csv';
        $config['allowed_types'] = '*';    
        $config['max_size']  = '2048000';
        $config['overwrite'] = true;
        $config['encrypt_name'] = TRUE;
        $this->upload->initialize($config);

        if ($this->upload->do_upload('file')) 
        {

            $upload_data = $this->upload->data();
            $filename = $upload_data['orig_name'];

            $this->load->library('excel');
            $object = PHPExcel_IOFactory::load("assets/uploads/management_raw/import/$filename");

            $jumlahSheet = $object->getSheetCount();
            if ($jumlahSheet > 1) {
                echo "jumlah_sheet : ".$jumlahSheet;
                echo "<script>alert('upload file gagal karena file mempunyai lebih dari 1 sheet'); </script>";
                redirect('management_raw','refresh');
            }

            // die;

            $created_at = $this->model_outlet_transaksi->timezone();
            $signature = md5($this->model_outlet_transaksi->timezone());

            foreach ($object->getWorksheetIterator() as $worksheet) {

                $highestRow = $worksheet->getHighestRow();
                $highestColumn = $worksheet->getHighestColumn();

                for ($row = 2; $row <= $highestRow; $row++) {          
                
                    $tipe_order = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
                    $status_cutomer = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
                    $group_divisi = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
                    $flag_sp_tt = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
                    $tipe = $worksheet->getCellByColumnAndRow(4, $row)->getValue();
                    $status_site = $worksheet->getCellByColumnAndRow(5, $row)->getValue();
                    $nama_branch = $worksheet->getCellByColumnAndRow(6, $row)->getValue();
                    $siteid = $worksheet->getCellByColumnAndRow(7, $row)->getValue();
                    $nama_site = $worksheet->getCellByColumnAndRow(8, $row)->getValue();
                    $salesmanid = $worksheet->getCellByColumnAndRow(9, $row)->getValue();
                    $nama_salesman = $worksheet->getCellByColumnAndRow(10, $row)->getValue();
                    $status_faktur = $worksheet->getCellByColumnAndRow(11, $row)->getValue();
                    $type_sales = $worksheet->getCellByColumnAndRow(12, $row)->getValue();
                    $tipe_trans = $worksheet->getCellByColumnAndRow(13, $row)->getValue();
                    $categoryid = $worksheet->getCellByColumnAndRow(14, $row)->getValue();
                    $nama_category = $worksheet->getCellByColumnAndRow(15, $row)->getValue();
                    $productid = $worksheet->getCellByColumnAndRow(16, $row)->getValue();
                    $nama_invoice = $worksheet->getCellByColumnAndRow(17, $row)->getValue();
                    $ket = $worksheet->getCellByColumnAndRow(18, $row)->getValue();
                    $qty_kecil = $worksheet->getCellByColumnAndRow(19, $row)->getValue();
                    $qty_bonus = $worksheet->getCellByColumnAndRow(20, $row)->getValue();
                    $rp_kotor = $worksheet->getCellByColumnAndRow(21, $row)->getValue();
                    $rp_discount = $worksheet->getCellByColumnAndRow(22, $row)->getValue();
                    $rp_netto = $worksheet->getCellByColumnAndRow(23, $row)->getValue();
                    $brandid = $worksheet->getCellByColumnAndRow(24, $row)->getValue();
                    $nama_brand = $worksheet->getCellByColumnAndRow(25, $row)->getValue();
                    $varianid = $worksheet->getCellByColumnAndRow(26, $row)->getValue();
                    $nama_varian = $worksheet->getCellByColumnAndRow(27, $row)->getValue();
                    $groupid = $worksheet->getCellByColumnAndRow(28, $row)->getValue();
                    $nama_group = $worksheet->getCellByColumnAndRow(29, $row)->getValue();
                    $customerid = $worksheet->getCellByColumnAndRow(30, $row)->getValue();
                    $nama_customer = $worksheet->getCellByColumnAndRow(31, $row)->getValue();
                    $prefix = $worksheet->getCellByColumnAndRow(32, $row)->getValue();
                    $alamat = $worksheet->getCellByColumnAndRow(33, $row)->getValue();
                    $segmentid = $worksheet->getCellByColumnAndRow(34, $row)->getValue();
                    $nama_segment = $worksheet->getCellByColumnAndRow(35, $row)->getValue();
                    $typeid = $worksheet->getCellByColumnAndRow(36, $row)->getValue();
                    $nama_type = $worksheet->getCellByColumnAndRow(37, $row)->getValue();
                    $group1 = $worksheet->getCellByColumnAndRow(38, $row)->getValue();
                    $propinsiid = $worksheet->getCellByColumnAndRow(39, $row)->getValue();
                    $nama_propinsi = $worksheet->getCellByColumnAndRow(40, $row)->getValue();
                    $regionalid = $worksheet->getCellByColumnAndRow(41, $row)->getValue();
                    $nama_regional = $worksheet->getCellByColumnAndRow(42, $row)->getValue();
                    $areaid = $worksheet->getCellByColumnAndRow(43, $row)->getValue();
                    $nama_area = $worksheet->getCellByColumnAndRow(44, $row)->getValue();
                    $no_sales = $worksheet->getCellByColumnAndRow(45, $row)->getValue();
                    $ref = $worksheet->getCellByColumnAndRow(46, $row)->getValue();
                    $tgl_po = $worksheet->getCellByColumnAndRow(47, $row)->getValue();
                    $tanggal = $worksheet->getCellByColumnAndRow(48, $row)->getValue();
                    $month = $worksheet->getCellByColumnAndRow(49, $row)->getValue();
                    $year = $worksheet->getCellByColumnAndRow(50, $row)->getValue();
                    $classid = $worksheet->getCellByColumnAndRow(51, $row)->getValue();
                    $nama_class = $worksheet->getCellByColumnAndRow(52, $row)->getValue();
                    $kotaid = $worksheet->getCellByColumnAndRow(53, $row)->getValue();
                    $nama_kota = $worksheet->getCellByColumnAndRow(54, $row)->getValue();
                    $kecamatanid = $worksheet->getCellByColumnAndRow(55, $row)->getValue();
                    $nama_kecamatan = $worksheet->getCellByColumnAndRow(56, $row)->getValue();
                    $nama_kelurahan = $worksheet->getCellByColumnAndRow(57, $row)->getValue();
                    $kelurahanid = $worksheet->getCellByColumnAndRow(58, $row)->getValue();
                    $disc_cabang = $worksheet->getCellByColumnAndRow(59, $row)->getValue();
                    $disc_prinsipal = $worksheet->getCellByColumnAndRow(60, $row)->getValue();
                    $disc_xtra = $worksheet->getCellByColumnAndRow(61, $row)->getValue();
                    $disc_cash = $worksheet->getCellByColumnAndRow(62, $row)->getValue();
                    $subareaid = $worksheet->getCellByColumnAndRow(63, $row)->getValue();
                    $subarea = $worksheet->getCellByColumnAndRow(64, $row)->getValue();
                    $keterangan = $worksheet->getCellByColumnAndRow(65, $row)->getValue();
                    $areaid2 = $worksheet->getCellByColumnAndRow(66, $row)->getValue();
                    $nama_area2 = $worksheet->getCellByColumnAndRow(67, $row)->getValue();
                    $spot_id = $worksheet->getCellByColumnAndRow(68, $row)->getValue();
                    $nama_spot = $worksheet->getCellByColumnAndRow(69, $row)->getValue();
                    $subareaid2 = $worksheet->getCellByColumnAndRow(70, $row)->getValue();
                    $subarea2 = $worksheet->getCellByColumnAndRow(71, $row)->getValue();
                    $regionalid2 = $worksheet->getCellByColumnAndRow(72, $row)->getValue();
                    $nama_regional2 = $worksheet->getCellByColumnAndRow(73, $row)->getValue();
                    $status_outlet = $worksheet->getCellByColumnAndRow(74, $row)->getValue();
                    $rp_net_reguler = $worksheet->getCellByColumnAndRow(75, $row)->getValue();
                    $rp_net_ritel = $worksheet->getCellByColumnAndRow(76, $row)->getValue();
                    $rp_net_selisih = $worksheet->getCellByColumnAndRow(77, $row)->getValue();
                    $rp_net_ritel_motoris = $worksheet->getCellByColumnAndRow(78, $row)->getValue();
                    $tipe_trans1 = $worksheet->getCellByColumnAndRow(79, $row)->getValue();
                    $qty_kecil_crt = $worksheet->getCellByColumnAndRow(80, $row)->getValue();
                    $qty_bonus_crt = $worksheet->getCellByColumnAndRow(81, $row)->getValue();
                    $latitude = $worksheet->getCellByColumnAndRow(82, $row)->getValue();
                    $longitude = $worksheet->getCellByColumnAndRow(83, $row)->getValue();
                    $tanggal_fjp = $worksheet->getCellByColumnAndRow(84, $row)->getValue();
                    $status_ob = $worksheet->getCellByColumnAndRow(85, $row)->getValue();
                        
                    $data = [
                        'tipe_order'      => $tipe_order,
                        'status_cutomer'      => $status_cutomer,
                        'group_divisi'      => $group_divisi,
                        'flag_sp_tt'      => $flag_sp_tt,
                        'tipe'      => $tipe,
                        'status_site'      => $status_site,
                        'nama_branch'      => $nama_branch,
                        'siteid'      => $siteid,
                        'nama_site'      => $nama_site,
                        'salesmanid'      => $salesmanid,
                        'nama_salesman'      => $nama_salesman,
                        'status_faktur'      => $status_faktur,
                        'type_sales'      => $type_sales,
                        'tipe_trans'      => $tipe_trans,
                        'categoryid'      => $categoryid,
                        'nama_category'      => $nama_category,
                        'productid'      => $productid,
                        'nama_invoice'      => $nama_invoice,
                        'ket'      => $ket,
                        'qty_kecil'      => $qty_kecil,
                        'qty_bonus'      => $qty_bonus,
                        'rp_kotor'      => $rp_kotor,
                        'rp_discount'      => $rp_discount,
                        'rp_netto'      => $rp_netto,
                        'brandid'      => $brandid,
                        'nama_brand'      => $nama_brand,
                        'varianid'      => $varianid,
                        'nama_varian'      => $nama_varian,
                        'groupid'      => $groupid,
                        'nama_group'      => $nama_group,
                        'customerid'      => $customerid,
                        'nama_customer'      => $nama_customer,
                        'prefix'      => $prefix,
                        'alamat'      => $alamat,
                        'segmentid'      => $segmentid,
                        'nama_segment'      => $nama_segment,
                        'typeid'      => $typeid,
                        'nama_type'      => $nama_type,
                        'group1'      => $group1,
                        'propinsiid'      => $propinsiid,
                        'nama_propinsi'      => $nama_propinsi,
                        'regionalid'      => $regionalid,
                        'nama_regional'      => $nama_regional,
                        'areaid'      => $areaid,
                        'nama_area'      => $nama_area,
                        'no_sales'      => $no_sales,
                        'ref'      => $ref,
                        'tgl_po'      => $tgl_po,
                        'tanggal'      => $tanggal,
                        'month'      => $month,
                        'year'      => $year,
                        'classid'      => $classid,
                        'nama_class'      => $nama_class,
                        'kotaid'      => $kotaid,
                        'nama_kota'      => $nama_kota,
                        'kecamatanid'      => $kecamatanid,
                        'nama_kecamatan'      => $nama_kecamatan,
                        'nama_kelurahan'      => $nama_kelurahan,
                        'kelurahanid'      => $kelurahanid,
                        'disc_cabang'      => $disc_cabang,
                        'disc_prinsipal'      => $disc_prinsipal,
                        'disc_xtra'      => $disc_xtra,
                        'disc_cash'      => $disc_cash,
                        'subareaid'      => $subareaid,
                        'subarea'      => $subarea,
                        'keterangan'      => $keterangan,
                        'areaid2'      => $areaid2,
                        'nama_area2'      => $nama_area2,
                        'spot_id'      => $spot_id,
                        'nama_spot'      => $nama_spot,
                        'subareaid2'      => $subareaid2,
                        'subarea2'      => $subarea2,
                        'regionalid2'      => $regionalid2,
                        'nama_regional2'      => $nama_regional2,
                        'status_outlet'      => $status_outlet,
                        'rp_net_reguler'      => $rp_net_reguler,
                        'rp_net_ritel'      => $rp_net_ritel,
                        'rp_net_selisih'      => $rp_net_selisih,
                        'rp_net_ritel_motoris'      => $rp_net_ritel_motoris,
                        'tipe_trans1'      => $tipe_trans1,
                        'qty_kecil_crt'      => $qty_kecil_crt,
                        'qty_bonus_crt'      => $qty_bonus_crt,
                        'latitude'      => $latitude,
                        'longitude'      => $longitude,
                        'tanggal_fjp'      => $tanggal_fjp,
                        'status_ob'      => $status_ob,                        
                        'signature' => $signature,
                        'created_at'    => $created_at,
                        'created_by'    => $this->session->userdata('id')
                    ];

                    $this->db->insert('management_raw.raw_samarinda',$data);
                }
            }
        }else{
           
        };

        $get_count = "select count(*) as count from management_raw.raw_samarinda a where a.signature = '$signature'";
        $count = $this->db->query($get_count)->row()->count;

        $get_omzet = "select sum(a.rp_kotor) as omzet_raw from management_raw.raw_samarinda a where a.signature = '$signature'";
        $omzet_raw = $this->db->query($get_omzet)->row()->omzet_raw;

        $upload = [
            'site_code'     => 'SMRB7',
            'filename'      => $filename,
            'signature'     => $signature,
            'count_raw'     => $count,
            'omzet_raw'     => $omzet_raw,
            'type_file'     => 'raw_sales',
            'bulan'         => $bulan_from_input,
            'tahun'         => $tahun_from_input,
            'created_at'    => $created_at,
            'created_by'    => $this->session->userdata('id')
        ];
        $this->db->insert('management_raw.log_upload', $upload);

        redirect('management_raw/import_samarinda_draft/'.$signature);
    }

    public function import_samarinda_draft($signature){

        $data = [
            'title'     => 'Management Raw / Preview Import data samarinda',
            'id'        => $this->session->userdata('id'),
            'get_raw_draft'  => $this->model_management_raw->get_raw_draft('SMRB7', $signature),
            'url'=> 'management_raw/proses_mapping_samarinda',
            'get_summary' => $this->model_management_raw->get_summary_raw_sales_samarinda('SMRB7', $signature),
            'signature' => $signature
        ];
        $this->load->view('management_raw/header');
        $this->load->view('management_raw/draft_samarinda', $data);
        $this->load->view('mes/footer');
    }

    public function proses_mapping_samarinda(){

        $signature = $this->input->post('signature');

        $inner_kodeproduk = $this->model_management_raw->inner_kodeproduk_samarinda('SMRB7', $signature);
        if ($inner_kodeproduk) {
            echo "<br><center><i>sukses memfilter produk khusus mpm / membuang produk selain mpm ...</i></b><br>";
        }

        $update_namaprod = $this->model_management_raw->update_namaprod_samarinda('SMRB7', $signature);
        if ($update_namaprod) {
            echo "<br><center><i>sukses mengupdate namaproduk ...</i></b><br>";
        }

        $update_branch = $this->model_management_raw->update_branch('SMRB7', $signature);
        if ($update_branch) {
            echo "<br><center><i>sukses mengupdate branch_name dan nama_comp ...</i></b><br>";
        }

        $update_tanggal = $this->model_management_raw->update_tanggal('SMRB7', $signature);                       
        if ($update_tanggal) {
            echo "<br><center><i>sukses memperbaiki format tanggal ...</i></b><br>";
        }

        $prepare_tblang_bantu = $this->model_management_raw->prepare_tblang_bantu('SMRB7', $signature);
        if ($prepare_tblang_bantu) {
            echo "<br><center><i>prepare_tblang_bantu done ...</i></b><br>";
        }

        $delete_tabel = $this->model_management_raw->delete_tabel('SMRB7', $signature);
        if ($delete_tabel) {
            echo "<br><center><i>delete tabel done ...</i></b><br>";
        }

        $insert_fi = $this->model_management_raw->insert_fi_samarinda('SMRB7', $signature);
        if ($insert_fi) {
            echo "<br><center><i>insert fi (sales) done ...</i></b><br>";
        }

        $insert_ri = $this->model_management_raw->insert_ri_samarinda('SMRB7', $signature);
        if ($insert_ri) {
            echo "<br><center><i>insert ri (sales) done ...</i></b><br>";
        }

        $insert_tblang_bantu = $this->model_management_raw->insert_tblang_bantu_banjarmasin('SMRB7', $signature);
        if ($insert_tblang_bantu) {
            echo "<br><center><i>insert tblang bantu done ...</i></b><br>";
        }

        $insert_tblang = $this->model_management_raw->insert_tblang_banjarmasin('SMRB7', $signature);
        if ($insert_tblang) {
            echo "<br><center><i>insert tblang done ...</i></b><br>";
        }

        // $insert_tblang = $this->model_management_raw->insert_tblang_batulicin('SMRB7', $signature);
        // if ($insert_tblang) {
        //     echo "<br><center><i>insert tblang done ...</i></b><br>";
        // }

        $insert_tabsales = $this->model_management_raw->insert_tabsales_batulicin('SMRB7', $signature);
        if ($insert_tabsales) {
            echo "<br><center><i>insert tabsales done ...</i></b><br>";
        }

        $insert_tbkota = $this->model_management_raw->insert_tbkota_samarinda('SMRB7', $signature);
        if ($insert_tbkota) {
            echo "<br><center><i>insert tbkota done ...</i></b><br>";
        }

        $get_count = "select count(*) as count from management_raw.inner_raw_samarinda a where a.signature = '$signature'";
        $count = $this->db->query($get_count)->row()->count;

        $get_bulan = $this->model_management_raw->get_log_upload_by_signature($signature)->row()->bulan;
        $get_tahun = $this->model_management_raw->get_log_upload_by_signature($signature)->row()->tahun;

        $get_omzet = $this->model_management_raw->get_omzet_web($get_tahun, $get_bulan, 'SMRB7')->row()->omzet;

        $update = [
            'count_mapping' => $count,
            'omzet_web'     => $get_omzet,
        ];
        $this->db->where('signature', $signature);
        $this->db->update('management_raw.log_upload', $update);

        $insert_mpm_upload = $this->model_management_raw->insert_mpm_upload('SMRB7', $signature, $get_omzet);
        if ($insert_mpm_upload) {
            echo "<br><center><i>insert mpm.upload done ...</i></b><br>";
        }

        echo "<br><center><i>dalam 5 detik anda akan di redirect ke halaman awal ...</i></b><br>";
        header('Refresh: 5; URL='.base_url().'management_raw/samarinda');    

    }

    public function bontang(){
        $data = [
            'title'     => 'Management Raw / Import data bontang',
            'id'        => $this->session->userdata('id'),
            'url_import'=> 'management_raw/import_bontang',
            'get_log_upload'    => $this->model_management_raw->get_log_upload('BTGB8')
        ];
        $this->load->view('management_raw/header');
        $this->load->view('management_raw/bontang', $data);
        $this->load->view('mes/footer');
    }

    public function template_bontang(){        
        $query = "
            select  '' as tipe_order, '' as status_cutomer, '' as group_divisi, '' as flag_sp_tt, '' as tipe, '' as status_site, '' as nama_branch, 
                    '' as siteid, '' as nama_site, '' as salesmanid, '' as nama_salesman, '' as status_faktur, '' as type_sales, '' as tipe_trans,
                    '' as categoryid, '' as nama_category, '' as productid, '' as nama_invoice, '' as ket, '' as qty_kecil, '' as qty_bonus, 
                    '' as rp_kotor, '' as rp_discount, '' as rp_netto, '' as brandid, '' as nama_brand, '' as varianid, '' as nama_varian, 
                    '' as groupid, '' as nama_group, '' as customerid, '' as nama_customer, '' as prefix, '' as alamat, '' as segmentid, '' as nama_segment,  
                    '' as typeid, '' as nama_type, '' as group1, '' as propinsiid, '' as nama_propinsi, '' as regionalid, '' as nama_regional,
                    '' as areaid, '' as nama_area, '' as no_sales, '' as ref, '' as tgl_po, '' as tanggal, '' as month, '' as year, '' as classid,
                    '' as nama_class, '' as kotaid, '' as nama_kota, '' as kecamatanid, '' as nama_kecamatan, '' as nama_kelurahan, '' as kelurahanid,
                    '' as disc_cabang, '' as disc_prinsipal, '' as disc_xtra, '' as disc_cash, '' as subareaid, '' as subarea, '' as keterangan, 
                    '' as areaid2, '' as nama_area2, '' as spot_id, '' as nama_spot, '' as subareaid2, '' as subarea2, '' as regionalid2, '' as nama_regional2, 
                    '' as status_outlet, '' as rp_net_reguler, '' as rp_net_ritel, '' as rp_net_selisih, '' as rp_net_ritel_motoris, '' as tipe_trans1, 
                    '' as qty_kecil_crt, '' as qty_bonus_crt, '' as latitude, '' as longitude, '' as tanggal_fjp, '' as status_ob 
        ";

        $hsl = $this->db->query($query);
        query_to_csv($hsl,TRUE,'Template_bontang.csv');
    }

    public function import_bontang(){
        $bulan_from_input = substr($this->input->post('bulan'), 5, 2);
        $tahun_from_input = substr($this->input->post('bulan'), 0, 4);

        $cek_last_upload = "
            select *
            from mpm.upload a 
            where a.userid = 621 
            ORDER BY a.id desc
        ";
        $tahun_last_upload = $this->db->query($cek_last_upload)->row()->tahun;
        $bulan_last_upload = $this->db->query($cek_last_upload)->row()->bulan;
        $closing_last_upload = $this->db->query($cek_last_upload)->row()->status_closing;

        if ($closing_last_upload == 1) {
            $tanggal_seharusnya = date('Y-m-d', strtotime('+1 month', strtotime($tahun_last_upload.'-'.$bulan_last_upload.'-01')));           
            $bulan_seharusnya = date('m', strtotime($tanggal_seharusnya));
            $tahun_seharusnya = date('Y', strtotime($tanggal_seharusnya));

            if ($tahun_from_input === $tahun_seharusnya && $bulan_from_input === $bulan_seharusnya) {
                
            }else{
                echo "<br><hr>Tanggal yang anda pilih adalah :<br><br>";
                // echo "<hr>";
                echo "tahun : ".$tahun_from_input."<br>";
                echo "bulan : ".$bulan_from_input."<br><br>";
                echo "<hr>";

                echo "<br>Tanggal upload terakhir adalah :<br><br>";
                // echo "<hr>";
                echo "tahun_last_upload : ".$tahun_last_upload."<br>";
                echo "bulan_last_upload : ".$bulan_last_upload."<br>";
                echo "closing_last_upload : ".$closing_last_upload."<br><br>";
                echo "<hr>";
                
                echo "<h3>Result</h3>";
                echo "Data ditolak !!<br><br>";
                echo "Anda hanya diijinkan mengupload data untuk <b>tahun : $tahun_seharusnya</b>, dan <b>bulan : $bulan_seharusnya</b><br>";

                echo "<br>anda akan di redirect ke menu awal dalam 10 detik";
                header('Refresh: 10; URL='.base_url().'management_raw/bontang');
                die;
            }

        }else{

            $tanggal_seharusnya = date('Y-m-d', strtotime('+0 month', strtotime($tahun_last_upload.'-'.$bulan_last_upload.'-01')));           
            $bulan_seharusnya = date('m', strtotime($tanggal_seharusnya));
            $tahun_seharusnya = date('Y', strtotime($tanggal_seharusnya));

            if ($tahun_from_input === $tahun_seharusnya && $bulan_from_input === $bulan_seharusnya) {
                // echo "diijinkan";
            }else{
                echo "<br><hr>Tanggal yang anda pilih adalah :<br><br>";
                echo "tahun : ".$tahun_from_input."<br>";
                echo "bulan : ".$bulan_from_input."<br><br>";
                echo "<hr>";


                echo "<br>Tanggal upload terakhir adalah :<br><br>";
                echo "tahun_last_upload : ".$tahun_last_upload."<br>";
                echo "bulan_last_upload : ".$bulan_last_upload."<br>";
                echo "closing_last_upload : ".$closing_last_upload."<br><br>";
                echo "<hr>";
                
                echo "<h3>Result</h3>";
                echo "Data ditolak !!<br><br>";
                echo "Anda hanya diijinkan mengupload data untuk <b>tahun : $tahun_seharusnya</b>, dan <b>bulan : $bulan_seharusnya</b><br>";

                echo "<br>anda akan di redirect ke menu awal dalam 10 detik";
                header('Refresh: 10; URL='.base_url().'management_raw/bontang');
                die;
            }

        }

        // die;

        if (!is_dir('./assets/uploads/management_raw/import/')) {
            @mkdir('./assets/uploads/management_raw/import/', 0777);
        }

        $this->load->library('upload'); // Load librari upload        
        $config['upload_path'] = './assets/uploads/management_raw/import/';
        // $config['allowed_types'] = 'xls|xlsx|csv';
        $config['allowed_types'] = '*';    
        $config['max_size']  = '2048000';
        $config['overwrite'] = true;
        $config['encrypt_name'] = TRUE;
        $this->upload->initialize($config);

        if ($this->upload->do_upload('file')) 
        {

            $upload_data = $this->upload->data();
            $filename = $upload_data['orig_name'];

            $this->load->library('excel');
            $object = PHPExcel_IOFactory::load("assets/uploads/management_raw/import/$filename");

            $jumlahSheet = $object->getSheetCount();
            if ($jumlahSheet > 1) {
                echo "jumlah_sheet : ".$jumlahSheet;
                echo "<script>alert('upload file gagal karena file mempunyai lebih dari 1 sheet'); </script>";
                redirect('management_raw','refresh');
            }

            // die;

            $created_at = $this->model_outlet_transaksi->timezone();
            $signature = md5($this->model_outlet_transaksi->timezone());

            foreach ($object->getWorksheetIterator() as $worksheet) {

                $highestRow = $worksheet->getHighestRow();
                $highestColumn = $worksheet->getHighestColumn();

                for ($row = 2; $row <= $highestRow; $row++) {          
                
                    $tipe_order = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
                    $status_cutomer = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
                    $group_divisi = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
                    $flag_sp_tt = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
                    $tipe = $worksheet->getCellByColumnAndRow(4, $row)->getValue();
                    $status_site = $worksheet->getCellByColumnAndRow(5, $row)->getValue();
                    $nama_branch = $worksheet->getCellByColumnAndRow(6, $row)->getValue();
                    $siteid = $worksheet->getCellByColumnAndRow(7, $row)->getValue();
                    $nama_site = $worksheet->getCellByColumnAndRow(8, $row)->getValue();
                    $salesmanid = $worksheet->getCellByColumnAndRow(9, $row)->getValue();
                    $nama_salesman = $worksheet->getCellByColumnAndRow(10, $row)->getValue();
                    $status_faktur = $worksheet->getCellByColumnAndRow(11, $row)->getValue();
                    $type_sales = $worksheet->getCellByColumnAndRow(12, $row)->getValue();
                    $tipe_trans = $worksheet->getCellByColumnAndRow(13, $row)->getValue();
                    $categoryid = $worksheet->getCellByColumnAndRow(14, $row)->getValue();
                    $nama_category = $worksheet->getCellByColumnAndRow(15, $row)->getValue();
                    $productid = $worksheet->getCellByColumnAndRow(16, $row)->getValue();
                    $nama_invoice = $worksheet->getCellByColumnAndRow(17, $row)->getValue();
                    $ket = $worksheet->getCellByColumnAndRow(18, $row)->getValue();
                    $qty_kecil = $worksheet->getCellByColumnAndRow(19, $row)->getValue();
                    $qty_bonus = $worksheet->getCellByColumnAndRow(20, $row)->getValue();
                    $rp_kotor = $worksheet->getCellByColumnAndRow(21, $row)->getValue();
                    $rp_discount = $worksheet->getCellByColumnAndRow(22, $row)->getValue();
                    $rp_netto = $worksheet->getCellByColumnAndRow(23, $row)->getValue();
                    $brandid = $worksheet->getCellByColumnAndRow(24, $row)->getValue();
                    $nama_brand = $worksheet->getCellByColumnAndRow(25, $row)->getValue();
                    $varianid = $worksheet->getCellByColumnAndRow(26, $row)->getValue();
                    $nama_varian = $worksheet->getCellByColumnAndRow(27, $row)->getValue();
                    $groupid = $worksheet->getCellByColumnAndRow(28, $row)->getValue();
                    $nama_group = $worksheet->getCellByColumnAndRow(29, $row)->getValue();
                    $customerid = $worksheet->getCellByColumnAndRow(30, $row)->getValue();
                    $nama_customer = $worksheet->getCellByColumnAndRow(31, $row)->getValue();
                    $prefix = $worksheet->getCellByColumnAndRow(32, $row)->getValue();
                    $alamat = $worksheet->getCellByColumnAndRow(33, $row)->getValue();
                    $segmentid = $worksheet->getCellByColumnAndRow(34, $row)->getValue();
                    $nama_segment = $worksheet->getCellByColumnAndRow(35, $row)->getValue();
                    $typeid = $worksheet->getCellByColumnAndRow(36, $row)->getValue();
                    $nama_type = $worksheet->getCellByColumnAndRow(37, $row)->getValue();
                    $group1 = $worksheet->getCellByColumnAndRow(38, $row)->getValue();
                    $propinsiid = $worksheet->getCellByColumnAndRow(39, $row)->getValue();
                    $nama_propinsi = $worksheet->getCellByColumnAndRow(40, $row)->getValue();
                    $regionalid = $worksheet->getCellByColumnAndRow(41, $row)->getValue();
                    $nama_regional = $worksheet->getCellByColumnAndRow(42, $row)->getValue();
                    $areaid = $worksheet->getCellByColumnAndRow(43, $row)->getValue();
                    $nama_area = $worksheet->getCellByColumnAndRow(44, $row)->getValue();
                    $no_sales = $worksheet->getCellByColumnAndRow(45, $row)->getValue();
                    $ref = $worksheet->getCellByColumnAndRow(46, $row)->getValue();
                    $tgl_po = $worksheet->getCellByColumnAndRow(47, $row)->getValue();
                    $tanggal = $worksheet->getCellByColumnAndRow(48, $row)->getValue();
                    $month = $worksheet->getCellByColumnAndRow(49, $row)->getValue();
                    $year = $worksheet->getCellByColumnAndRow(50, $row)->getValue();
                    $classid = $worksheet->getCellByColumnAndRow(51, $row)->getValue();
                    $nama_class = $worksheet->getCellByColumnAndRow(52, $row)->getValue();
                    $kotaid = $worksheet->getCellByColumnAndRow(53, $row)->getValue();
                    $nama_kota = $worksheet->getCellByColumnAndRow(54, $row)->getValue();
                    $kecamatanid = $worksheet->getCellByColumnAndRow(55, $row)->getValue();
                    $nama_kecamatan = $worksheet->getCellByColumnAndRow(56, $row)->getValue();
                    $nama_kelurahan = $worksheet->getCellByColumnAndRow(57, $row)->getValue();
                    $kelurahanid = $worksheet->getCellByColumnAndRow(58, $row)->getValue();
                    $disc_cabang = $worksheet->getCellByColumnAndRow(59, $row)->getValue();
                    $disc_prinsipal = $worksheet->getCellByColumnAndRow(60, $row)->getValue();
                    $disc_xtra = $worksheet->getCellByColumnAndRow(61, $row)->getValue();
                    $disc_cash = $worksheet->getCellByColumnAndRow(62, $row)->getValue();
                    $subareaid = $worksheet->getCellByColumnAndRow(63, $row)->getValue();
                    $subarea = $worksheet->getCellByColumnAndRow(64, $row)->getValue();
                    $keterangan = $worksheet->getCellByColumnAndRow(65, $row)->getValue();
                    $areaid2 = $worksheet->getCellByColumnAndRow(66, $row)->getValue();
                    $nama_area2 = $worksheet->getCellByColumnAndRow(67, $row)->getValue();
                    $spot_id = $worksheet->getCellByColumnAndRow(68, $row)->getValue();
                    $nama_spot = $worksheet->getCellByColumnAndRow(69, $row)->getValue();
                    $subareaid2 = $worksheet->getCellByColumnAndRow(70, $row)->getValue();
                    $subarea2 = $worksheet->getCellByColumnAndRow(71, $row)->getValue();
                    $regionalid2 = $worksheet->getCellByColumnAndRow(72, $row)->getValue();
                    $nama_regional2 = $worksheet->getCellByColumnAndRow(73, $row)->getValue();
                    $status_outlet = $worksheet->getCellByColumnAndRow(74, $row)->getValue();
                    $rp_net_reguler = $worksheet->getCellByColumnAndRow(75, $row)->getValue();
                    $rp_net_ritel = $worksheet->getCellByColumnAndRow(76, $row)->getValue();
                    $rp_net_selisih = $worksheet->getCellByColumnAndRow(77, $row)->getValue();
                    $rp_net_ritel_motoris = $worksheet->getCellByColumnAndRow(78, $row)->getValue();
                    $tipe_trans1 = $worksheet->getCellByColumnAndRow(79, $row)->getValue();
                    $qty_kecil_crt = $worksheet->getCellByColumnAndRow(80, $row)->getValue();
                    $qty_bonus_crt = $worksheet->getCellByColumnAndRow(81, $row)->getValue();
                    $latitude = $worksheet->getCellByColumnAndRow(82, $row)->getValue();
                    $longitude = $worksheet->getCellByColumnAndRow(83, $row)->getValue();
                    $tanggal_fjp = $worksheet->getCellByColumnAndRow(84, $row)->getValue();
                    $status_ob = $worksheet->getCellByColumnAndRow(85, $row)->getValue();
                        
                    $data = [
                        'tipe_order'      => $tipe_order,
                        'status_cutomer'      => $status_cutomer,
                        'group_divisi'      => $group_divisi,
                        'flag_sp_tt'      => $flag_sp_tt,
                        'tipe'      => $tipe,
                        'status_site'      => $status_site,
                        'nama_branch'      => $nama_branch,
                        'siteid'      => $siteid,
                        'nama_site'      => $nama_site,
                        'salesmanid'      => $salesmanid,
                        'nama_salesman'      => $nama_salesman,
                        'status_faktur'      => $status_faktur,
                        'type_sales'      => $type_sales,
                        'tipe_trans'      => $tipe_trans,
                        'categoryid'      => $categoryid,
                        'nama_category'      => $nama_category,
                        'productid'      => $productid,
                        'nama_invoice'      => $nama_invoice,
                        'ket'      => $ket,
                        'qty_kecil'      => $qty_kecil,
                        'qty_bonus'      => $qty_bonus,
                        'rp_kotor'      => $rp_kotor,
                        'rp_discount'      => $rp_discount,
                        'rp_netto'      => $rp_netto,
                        'brandid'      => $brandid,
                        'nama_brand'      => $nama_brand,
                        'varianid'      => $varianid,
                        'nama_varian'      => $nama_varian,
                        'groupid'      => $groupid,
                        'nama_group'      => $nama_group,
                        'customerid'      => $customerid,
                        'nama_customer'      => $nama_customer,
                        'prefix'      => $prefix,
                        'alamat'      => $alamat,
                        'segmentid'      => $segmentid,
                        'nama_segment'      => $nama_segment,
                        'typeid'      => $typeid,
                        'nama_type'      => $nama_type,
                        'group1'      => $group1,
                        'propinsiid'      => $propinsiid,
                        'nama_propinsi'      => $nama_propinsi,
                        'regionalid'      => $regionalid,
                        'nama_regional'      => $nama_regional,
                        'areaid'      => $areaid,
                        'nama_area'      => $nama_area,
                        'no_sales'      => $no_sales,
                        'ref'      => $ref,
                        'tgl_po'      => $tgl_po,
                        'tanggal'      => $tanggal,
                        'month'      => $month,
                        'year'      => $year,
                        'classid'      => $classid,
                        'nama_class'      => $nama_class,
                        'kotaid'      => $kotaid,
                        'nama_kota'      => $nama_kota,
                        'kecamatanid'      => $kecamatanid,
                        'nama_kecamatan'      => $nama_kecamatan,
                        'nama_kelurahan'      => $nama_kelurahan,
                        'kelurahanid'      => $kelurahanid,
                        'disc_cabang'      => $disc_cabang,
                        'disc_prinsipal'      => $disc_prinsipal,
                        'disc_xtra'      => $disc_xtra,
                        'disc_cash'      => $disc_cash,
                        'subareaid'      => $subareaid,
                        'subarea'      => $subarea,
                        'keterangan'      => $keterangan,
                        'areaid2'      => $areaid2,
                        'nama_area2'      => $nama_area2,
                        'spot_id'      => $spot_id,
                        'nama_spot'      => $nama_spot,
                        'subareaid2'      => $subareaid2,
                        'subarea2'      => $subarea2,
                        'regionalid2'      => $regionalid2,
                        'nama_regional2'      => $nama_regional2,
                        'status_outlet'      => $status_outlet,
                        'rp_net_reguler'      => $rp_net_reguler,
                        'rp_net_ritel'      => $rp_net_ritel,
                        'rp_net_selisih'      => $rp_net_selisih,
                        'rp_net_ritel_motoris'      => $rp_net_ritel_motoris,
                        'tipe_trans1'      => $tipe_trans1,
                        'qty_kecil_crt'      => $qty_kecil_crt,
                        'qty_bonus_crt'      => $qty_bonus_crt,
                        'latitude'      => $latitude,
                        'longitude'      => $longitude,
                        'tanggal_fjp'      => $tanggal_fjp,
                        'status_ob'      => $status_ob,                        
                        'signature' => $signature,
                        'created_at'    => $created_at,
                        'created_by'    => $this->session->userdata('id')
                    ];

                    $this->db->insert('management_raw.raw_bontang',$data);
                }
            }
        }else{
           
        };

        $get_count = "select count(*) as count from management_raw.raw_bontang a where a.signature = '$signature'";
        $count = $this->db->query($get_count)->row()->count;

        $get_omzet = "select sum(a.rp_kotor) as omzet_raw from management_raw.raw_bontang a where a.signature = '$signature'";
        $omzet_raw = $this->db->query($get_omzet)->row()->omzet_raw;

        $upload = [
            'site_code'     => 'BTGB8',
            'filename'      => $filename,
            'signature'     => $signature,
            'count_raw'     => $count,
            'omzet_raw'     => $omzet_raw,
            'type_file'     => 'raw_sales',
            'bulan'         => $bulan_from_input,
            'tahun'         => $tahun_from_input,
            'created_at'    => $created_at,
            'created_by'    => $this->session->userdata('id')
        ];
        $this->db->insert('management_raw.log_upload', $upload);

        redirect('management_raw/import_bontang_draft/'.$signature);
    }

    public function import_bontang_draft($signature){

        $data = [
            'title'     => 'Management Raw / Preview Import data bontang',
            'id'        => $this->session->userdata('id'),
            'get_raw_draft'  => $this->model_management_raw->get_raw_draft('BTGB8', $signature),
            'url'=> 'management_raw/proses_mapping_bontang',
            'get_summary' => $this->model_management_raw->get_summary_raw_sales_samarinda('BTGB8', $signature),
            'signature' => $signature
        ];
        $this->load->view('management_raw/header');
        $this->load->view('management_raw/draft_bontang', $data);
        $this->load->view('mes/footer');
    }

    public function proses_mapping_bontang(){

        $signature = $this->input->post('signature');

        $update_kodeproduk = $this->model_management_raw->update_kodeproduk_bontang('BTGB8', $signature);
        if ($update_kodeproduk) {            
            echo "<br><center><i>sukses menambahkan angka 0 di kodeproduk ... </i></b><br>";
        }
        
        $inner_kodeproduk = $this->model_management_raw->inner_kodeproduk_samarinda('BTGB8', $signature);
        if ($inner_kodeproduk) {
            echo "<br><center><i>sukses memfilter produk khusus mpm / membuang produk selain mpm ...</i></b><br>";
        }

        $update_namaprod = $this->model_management_raw->update_namaprod_samarinda('BTGB8', $signature);
        if ($update_namaprod) {
            echo "<br><center><i>sukses mengupdate namaproduk ...</i></b><br>";
        }

        $update_branch = $this->model_management_raw->update_branch('BTGB8', $signature);
        if ($update_branch) {
            echo "<br><center><i>sukses mengupdate branch_name dan nama_comp ...</i></b><br>";
        }

        $update_tanggal = $this->model_management_raw->update_tanggal('BTGB8', $signature);                       
        if ($update_tanggal) {
            echo "<br><center><i>sukses memperbaiki format tanggal ...</i></b><br>";
        }

        $prepare_tblang_bantu = $this->model_management_raw->prepare_tblang_bantu('BTGB8', $signature);
        if ($prepare_tblang_bantu) {
            echo "<br><center><i>prepare_tblang_bantu done ...</i></b><br>";
        }

        $delete_tabel = $this->model_management_raw->delete_tabel('BTGB8', $signature);
        if ($delete_tabel) {
            echo "<br><center><i>delete tabel done ...</i></b><br>";
        }

        $insert_fi = $this->model_management_raw->insert_fi_samarinda('BTGB8', $signature);
        if ($insert_fi) {
            echo "<br><center><i>insert fi (sales) done ...</i></b><br>";
        }

        $insert_ri = $this->model_management_raw->insert_ri_samarinda('BTGB8', $signature);
        if ($insert_ri) {
            echo "<br><center><i>insert ri (sales) done ...</i></b><br>";
        }

        $insert_tblang_bantu = $this->model_management_raw->insert_tblang_bantu_banjarmasin('BTGB8', $signature);
        if ($insert_tblang_bantu) {
            echo "<br><center><i>insert tblang bantu done ...</i></b><br>";
        }

        $insert_tblang = $this->model_management_raw->insert_tblang_banjarmasin('BTGB8', $signature);
        if ($insert_tblang) {
            echo "<br><center><i>insert tblang done ...</i></b><br>";
        }

        // $insert_tblang = $this->model_management_raw->insert_tblang_batulicin('BTGB8', $signature);
        // if ($insert_tblang) {
        //     echo "<br><center><i>insert tblang done ...</i></b><br>";
        // }

        $insert_tabsales = $this->model_management_raw->insert_tabsales_batulicin('BTGB8', $signature);
        if ($insert_tabsales) {
            echo "<br><center><i>insert tabsales done ...</i></b><br>";
        }

        $insert_tbkota = $this->model_management_raw->insert_tbkota_samarinda('BTGB8', $signature);
        if ($insert_tbkota) {
            echo "<br><center><i>insert tbkota done ...</i></b><br>";
        }

        $get_count = "select count(*) as count from management_raw.inner_raw_bontang a where a.signature = '$signature'";
        $count = $this->db->query($get_count)->row()->count;

        $get_bulan = $this->model_management_raw->get_log_upload_by_signature($signature)->row()->bulan;
        $get_tahun = $this->model_management_raw->get_log_upload_by_signature($signature)->row()->tahun;

        $get_omzet = $this->model_management_raw->get_omzet_web($get_tahun, $get_bulan, 'BTGB8')->row()->omzet;

        $update = [
            'count_mapping' => $count,
            'omzet_web'     => $get_omzet,
        ];
        $this->db->where('signature', $signature);
        $this->db->update('management_raw.log_upload', $update);

        $insert_mpm_upload = $this->model_management_raw->insert_mpm_upload('BTGB8', $signature, $get_omzet);
        if ($insert_mpm_upload) {
            echo "<br><center><i>insert mpm.upload done ...</i></b><br>";
        }

        echo "<br><center><i>dalam 5 detik anda akan di redirect ke halaman awal ...</i></b><br>";
        header('Refresh: 5; URL='.base_url().'management_raw/bontang');    

    }

    public function pontianak(){
        $data = [
            'title'     => 'Management Raw / Import data pontianak',
            'id'        => $this->session->userdata('id'),
            'url_import'=> 'management_raw/import_pontianak',
            'get_log_upload'    => $this->model_management_raw->get_log_upload('PTK82')
        ];
        $this->load->view('management_raw/header');
        $this->load->view('management_raw/pontianak', $data);
        $this->load->view('mes/footer');
    }

    public function template_pontianak(){        
        $query = "
            select  '' as tipe_order, '' as status_cutomer, '' as group_divisi, '' as flag_sp_tt, '' as tipe, '' as status_site, '' as nama_branch, 
                    '' as siteid, '' as nama_site, '' as salesmanid, '' as nama_salesman, '' as status_faktur, '' as type_sales, '' as tipe_trans,
                    '' as categoryid, '' as nama_category, '' as productid, '' as nama_invoice, '' as ket, '' as qty_kecil, '' as qty_bonus, 
                    '' as rp_kotor, '' as rp_discount, '' as rp_netto, '' as brandid, '' as nama_brand, '' as varianid, '' as nama_varian, 
                    '' as groupid, '' as nama_group, '' as customerid, '' as nama_customer, '' as prefix, '' as alamat, '' as segmentid, '' as nama_segment,  
                    '' as typeid, '' as nama_type, '' as group1, '' as propinsiid, '' as nama_propinsi, '' as regionalid, '' as nama_regional,
                    '' as areaid, '' as nama_area, '' as no_sales, '' as ref, '' as tgl_po, '' as tanggal, '' as month, '' as year, '' as classid,
                    '' as nama_class, '' as kotaid, '' as nama_kota, '' as kecamatanid, '' as nama_kecamatan, '' as nama_kelurahan, '' as kelurahanid,
                    '' as disc_cabang, '' as disc_prinsipal, '' as disc_xtra, '' as disc_cash, '' as subareaid, '' as subarea, '' as keterangan, 
                    '' as areaid2, '' as nama_area2, '' as spot_id, '' as nama_spot, '' as subareaid2, '' as subarea2, '' as regionalid2, '' as nama_regional2, 
                    '' as status_outlet, '' as rp_net_reguler, '' as rp_net_ritel, '' as rp_net_selisih, '' as rp_net_ritel_motoris, '' as tipe_trans1, 
                    '' as qty_kecil_crt, '' as qty_bonus_crt, '' as latitude, '' as longitude, '' as tanggal_fjp, '' as status_ob 
        ";

        $hsl = $this->db->query($query);
        query_to_csv($hsl,TRUE,'Template_pontianak.csv');
    }

    public function import_pontianak(){
        $bulan_from_input = substr($this->input->post('bulan'), 5, 2);
        $tahun_from_input = substr($this->input->post('bulan'), 0, 4);

        $cek_last_upload = "
            select *
            from mpm.upload a 
            where a.userid = 30 
            ORDER BY a.id desc
        ";
        $tahun_last_upload = $this->db->query($cek_last_upload)->row()->tahun;
        $bulan_last_upload = $this->db->query($cek_last_upload)->row()->bulan;
        $closing_last_upload = $this->db->query($cek_last_upload)->row()->status_closing;

        if ($closing_last_upload == 1) {
            $tanggal_seharusnya = date('Y-m-d', strtotime('+1 month', strtotime($tahun_last_upload.'-'.$bulan_last_upload.'-01')));           
            $bulan_seharusnya = date('m', strtotime($tanggal_seharusnya));
            $tahun_seharusnya = date('Y', strtotime($tanggal_seharusnya));

            if ($tahun_from_input === $tahun_seharusnya && $bulan_from_input === $bulan_seharusnya) {
                
            }else{
                echo "<br><hr>Tanggal yang anda pilih adalah :<br><br>";
                // echo "<hr>";
                echo "tahun : ".$tahun_from_input."<br>";
                echo "bulan : ".$bulan_from_input."<br><br>";
                echo "<hr>";

                echo "<br>Tanggal upload terakhir adalah :<br><br>";
                // echo "<hr>";
                echo "tahun_last_upload : ".$tahun_last_upload."<br>";
                echo "bulan_last_upload : ".$bulan_last_upload."<br>";
                echo "closing_last_upload : ".$closing_last_upload."<br><br>";
                echo "<hr>";
                
                echo "<h3>Result</h3>";
                echo "Data ditolak !!<br><br>";
                echo "Anda hanya diijinkan mengupload data untuk <b>tahun : $tahun_seharusnya</b>, dan <b>bulan : $bulan_seharusnya</b><br>";

                echo "<br>anda akan di redirect ke menu awal dalam 10 detik";
                header('Refresh: 10; URL='.base_url().'management_raw/pontianak');
                die;
            }

        }else{

            $tanggal_seharusnya = date('Y-m-d', strtotime('+0 month', strtotime($tahun_last_upload.'-'.$bulan_last_upload.'-01')));           
            $bulan_seharusnya = date('m', strtotime($tanggal_seharusnya));
            $tahun_seharusnya = date('Y', strtotime($tanggal_seharusnya));

            if ($tahun_from_input === $tahun_seharusnya && $bulan_from_input === $bulan_seharusnya) {
                // echo "diijinkan";
            }else{
                echo "<br><hr>Tanggal yang anda pilih adalah :<br><br>";
                echo "tahun : ".$tahun_from_input."<br>";
                echo "bulan : ".$bulan_from_input."<br><br>";
                echo "<hr>";


                echo "<br>Tanggal upload terakhir adalah :<br><br>";
                echo "tahun_last_upload : ".$tahun_last_upload."<br>";
                echo "bulan_last_upload : ".$bulan_last_upload."<br>";
                echo "closing_last_upload : ".$closing_last_upload."<br><br>";
                echo "<hr>";
                
                echo "<h3>Result</h3>";
                echo "Data ditolak !!<br><br>";
                echo "Anda hanya diijinkan mengupload data untuk <b>tahun : $tahun_seharusnya</b>, dan <b>bulan : $bulan_seharusnya</b><br>";

                echo "<br>anda akan di redirect ke menu awal dalam 10 detik";
                header('Refresh: 10; URL='.base_url().'management_raw/pontianak');
                die;
            }

        }

        // die;

        if (!is_dir('./assets/uploads/management_raw/import/')) {
            @mkdir('./assets/uploads/management_raw/import/', 0777);
        }

        $this->load->library('upload'); // Load librari upload        
        $config['upload_path'] = './assets/uploads/management_raw/import/';
        // $config['allowed_types'] = 'xls|xlsx|csv';
        $config['allowed_types'] = '*';    
        $config['max_size']  = '2048000';
        $config['overwrite'] = true;
        $config['encrypt_name'] = TRUE;
        $this->upload->initialize($config);

        if ($this->upload->do_upload('file')) 
        {

            $upload_data = $this->upload->data();
            $filename = $upload_data['orig_name'];

            $this->load->library('excel');
            $object = PHPExcel_IOFactory::load("assets/uploads/management_raw/import/$filename");

            $jumlahSheet = $object->getSheetCount();
            if ($jumlahSheet > 1) {
                echo "jumlah_sheet : ".$jumlahSheet;
                echo "<script>alert('upload file gagal karena file mempunyai lebih dari 1 sheet'); </script>";
                redirect('management_raw','refresh');
            }

            // die;

            $created_at = $this->model_outlet_transaksi->timezone();
            $signature = md5($this->model_outlet_transaksi->timezone());

            foreach ($object->getWorksheetIterator() as $worksheet) {

                $highestRow = $worksheet->getHighestRow();
                $highestColumn = $worksheet->getHighestColumn();

                for ($row = 2; $row <= $highestRow; $row++) {          
                
                    $tipe_order = trim($worksheet->getCellByColumnAndRow(0, $row)->getValue());
                    $status_cutomer = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
                    $group_divisi = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
                    $flag_sp_tt = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
                    $tipe = $worksheet->getCellByColumnAndRow(4, $row)->getValue();
                    $status_site = $worksheet->getCellByColumnAndRow(5, $row)->getValue();
                    $nama_branch = $worksheet->getCellByColumnAndRow(6, $row)->getValue();
                    $siteid = $worksheet->getCellByColumnAndRow(7, $row)->getValue();
                    $nama_site = $worksheet->getCellByColumnAndRow(8, $row)->getValue();
                    $salesmanid = trim($worksheet->getCellByColumnAndRow(9, $row)->getValue());
                    $nama_salesman = $worksheet->getCellByColumnAndRow(10, $row)->getValue();
                    $status_faktur = $worksheet->getCellByColumnAndRow(11, $row)->getValue();
                    $type_sales = $worksheet->getCellByColumnAndRow(12, $row)->getValue();
                    $tipe_trans = $worksheet->getCellByColumnAndRow(13, $row)->getValue();
                    $categoryid = $worksheet->getCellByColumnAndRow(14, $row)->getValue();
                    $nama_category = $worksheet->getCellByColumnAndRow(15, $row)->getValue();
                    $productid = trim($worksheet->getCellByColumnAndRow(16, $row)->getValue());
                    $nama_invoice = $worksheet->getCellByColumnAndRow(17, $row)->getValue();
                    $ket = $worksheet->getCellByColumnAndRow(18, $row)->getValue();
                    $qty_kecil = $worksheet->getCellByColumnAndRow(19, $row)->getValue();
                    $qty_bonus = $worksheet->getCellByColumnAndRow(20, $row)->getValue();
                    $rp_kotor = $worksheet->getCellByColumnAndRow(21, $row)->getValue();
                    $rp_discount = $worksheet->getCellByColumnAndRow(22, $row)->getValue();
                    $rp_netto = $worksheet->getCellByColumnAndRow(23, $row)->getValue();
                    $brandid = $worksheet->getCellByColumnAndRow(24, $row)->getValue();
                    $nama_brand = $worksheet->getCellByColumnAndRow(25, $row)->getValue();
                    $varianid = $worksheet->getCellByColumnAndRow(26, $row)->getValue();
                    $nama_varian = $worksheet->getCellByColumnAndRow(27, $row)->getValue();
                    $groupid = $worksheet->getCellByColumnAndRow(28, $row)->getValue();
                    $nama_group = $worksheet->getCellByColumnAndRow(29, $row)->getValue();
                    $customerid = trim($worksheet->getCellByColumnAndRow(30, $row)->getValue());
                    $nama_customer = $worksheet->getCellByColumnAndRow(31, $row)->getValue();
                    $prefix = $worksheet->getCellByColumnAndRow(32, $row)->getValue();
                    $alamat = $worksheet->getCellByColumnAndRow(33, $row)->getValue();
                    $segmentid = trim($worksheet->getCellByColumnAndRow(34, $row)->getValue());
                    $nama_segment = $worksheet->getCellByColumnAndRow(35, $row)->getValue();
                    $typeid = trim($worksheet->getCellByColumnAndRow(36, $row)->getValue());
                    $nama_type = $worksheet->getCellByColumnAndRow(37, $row)->getValue();
                    $group1 = $worksheet->getCellByColumnAndRow(38, $row)->getValue();
                    $propinsiid = $worksheet->getCellByColumnAndRow(39, $row)->getValue();
                    $nama_propinsi = $worksheet->getCellByColumnAndRow(40, $row)->getValue();
                    $regionalid = $worksheet->getCellByColumnAndRow(41, $row)->getValue();
                    $nama_regional = $worksheet->getCellByColumnAndRow(42, $row)->getValue();
                    $areaid = $worksheet->getCellByColumnAndRow(43, $row)->getValue();
                    $nama_area = $worksheet->getCellByColumnAndRow(44, $row)->getValue();
                    $no_sales = $worksheet->getCellByColumnAndRow(45, $row)->getValue();
                    $ref = $worksheet->getCellByColumnAndRow(46, $row)->getValue();
                    $tgl_po = $worksheet->getCellByColumnAndRow(47, $row)->getValue();
                    $tanggal = $worksheet->getCellByColumnAndRow(48, $row)->getValue();
                    $month = $worksheet->getCellByColumnAndRow(49, $row)->getValue();
                    $year = $worksheet->getCellByColumnAndRow(50, $row)->getValue();
                    $classid = trim($worksheet->getCellByColumnAndRow(51, $row)->getValue());
                    $nama_class = $worksheet->getCellByColumnAndRow(52, $row)->getValue();
                    $kotaid = trim($worksheet->getCellByColumnAndRow(53, $row)->getValue());
                    $nama_kota = $worksheet->getCellByColumnAndRow(54, $row)->getValue();
                    $kecamatanid = $worksheet->getCellByColumnAndRow(55, $row)->getValue();
                    $nama_kecamatan = $worksheet->getCellByColumnAndRow(56, $row)->getValue();
                    $nama_kelurahan = $worksheet->getCellByColumnAndRow(57, $row)->getValue();
                    $kelurahanid = $worksheet->getCellByColumnAndRow(58, $row)->getValue();
                    $disc_cabang = $worksheet->getCellByColumnAndRow(59, $row)->getValue();
                    $disc_prinsipal = $worksheet->getCellByColumnAndRow(60, $row)->getValue();
                    $disc_xtra = $worksheet->getCellByColumnAndRow(61, $row)->getValue();
                    $disc_cash = $worksheet->getCellByColumnAndRow(62, $row)->getValue();
                    $subareaid = $worksheet->getCellByColumnAndRow(63, $row)->getValue();
                    $subarea = $worksheet->getCellByColumnAndRow(64, $row)->getValue();
                    $keterangan = $worksheet->getCellByColumnAndRow(65, $row)->getValue();
                    $areaid2 = $worksheet->getCellByColumnAndRow(66, $row)->getValue();
                    $nama_area2 = $worksheet->getCellByColumnAndRow(67, $row)->getValue();
                    $spot_id = $worksheet->getCellByColumnAndRow(68, $row)->getValue();
                    $nama_spot = $worksheet->getCellByColumnAndRow(69, $row)->getValue();
                    $subareaid2 = $worksheet->getCellByColumnAndRow(70, $row)->getValue();
                    $subarea2 = $worksheet->getCellByColumnAndRow(71, $row)->getValue();
                    $regionalid2 = $worksheet->getCellByColumnAndRow(72, $row)->getValue();
                    $nama_regional2 = $worksheet->getCellByColumnAndRow(73, $row)->getValue();
                    $status_outlet = $worksheet->getCellByColumnAndRow(74, $row)->getValue();
                    $rp_net_reguler = $worksheet->getCellByColumnAndRow(75, $row)->getValue();
                    $rp_net_ritel = $worksheet->getCellByColumnAndRow(76, $row)->getValue();
                    $rp_net_selisih = $worksheet->getCellByColumnAndRow(77, $row)->getValue();
                    $rp_net_ritel_motoris = $worksheet->getCellByColumnAndRow(78, $row)->getValue();
                    $tipe_trans1 = $worksheet->getCellByColumnAndRow(79, $row)->getValue();
                    $qty_kecil_crt = $worksheet->getCellByColumnAndRow(80, $row)->getValue();
                    $qty_bonus_crt = $worksheet->getCellByColumnAndRow(81, $row)->getValue();
                    $latitude = $worksheet->getCellByColumnAndRow(82, $row)->getValue();
                    $longitude = $worksheet->getCellByColumnAndRow(83, $row)->getValue();
                    $tanggal_fjp = $worksheet->getCellByColumnAndRow(84, $row)->getValue();
                    $status_ob = $worksheet->getCellByColumnAndRow(85, $row)->getValue();
                        
                    $data = [
                        'tipe_order'      => $tipe_order,
                        'status_cutomer'      => $status_cutomer,
                        'group_divisi'      => $group_divisi,
                        'flag_sp_tt'      => $flag_sp_tt,
                        'tipe'      => $tipe,
                        'status_site'      => $status_site,
                        'nama_branch'      => $nama_branch,
                        'siteid'      => $siteid,
                        'nama_site'      => $nama_site,
                        'salesmanid'      => $salesmanid,
                        'nama_salesman'      => $nama_salesman,
                        'status_faktur'      => $status_faktur,
                        'type_sales'      => $type_sales,
                        'tipe_trans'      => $tipe_trans,
                        'categoryid'      => $categoryid,
                        'nama_category'      => $nama_category,
                        'productid'      => $productid,
                        'nama_invoice'      => $nama_invoice,
                        'ket'      => $ket,
                        'qty_kecil'      => $qty_kecil,
                        'qty_bonus'      => $qty_bonus,
                        'rp_kotor'      => $rp_kotor,
                        'rp_discount'      => $rp_discount,
                        'rp_netto'      => $rp_netto,
                        'brandid'      => $brandid,
                        'nama_brand'      => $nama_brand,
                        'varianid'      => $varianid,
                        'nama_varian'      => $nama_varian,
                        'groupid'      => $groupid,
                        'nama_group'      => $nama_group,
                        'customerid'      => $customerid,
                        'nama_customer'      => $nama_customer,
                        'prefix'      => $prefix,
                        'alamat'      => $alamat,
                        'segmentid'      => $segmentid,
                        'nama_segment'      => $nama_segment,
                        'typeid'      => $typeid,
                        'nama_type'      => $nama_type,
                        'group1'      => $group1,
                        'propinsiid'      => $propinsiid,
                        'nama_propinsi'      => $nama_propinsi,
                        'regionalid'      => $regionalid,
                        'nama_regional'      => $nama_regional,
                        'areaid'      => $areaid,
                        'nama_area'      => $nama_area,
                        'no_sales'      => $no_sales,
                        'ref'      => $ref,
                        'tgl_po'      => $tgl_po,
                        'tanggal'      => $tanggal,
                        'month'      => $month,
                        'year'      => $year,
                        'classid'      => $classid,
                        'nama_class'      => $nama_class,
                        'kotaid'      => $kotaid,
                        'nama_kota'      => $nama_kota,
                        'kecamatanid'      => $kecamatanid,
                        'nama_kecamatan'      => $nama_kecamatan,
                        'nama_kelurahan'      => $nama_kelurahan,
                        'kelurahanid'      => $kelurahanid,
                        'disc_cabang'      => $disc_cabang,
                        'disc_prinsipal'      => $disc_prinsipal,
                        'disc_xtra'      => $disc_xtra,
                        'disc_cash'      => $disc_cash,
                        'subareaid'      => $subareaid,
                        'subarea'      => $subarea,
                        'keterangan'      => $keterangan,
                        'areaid2'      => $areaid2,
                        'nama_area2'      => $nama_area2,
                        'spot_id'      => $spot_id,
                        'nama_spot'      => $nama_spot,
                        'subareaid2'      => $subareaid2,
                        'subarea2'      => $subarea2,
                        'regionalid2'      => $regionalid2,
                        'nama_regional2'      => $nama_regional2,
                        'status_outlet'      => $status_outlet,
                        'rp_net_reguler'      => $rp_net_reguler,
                        'rp_net_ritel'      => $rp_net_ritel,
                        'rp_net_selisih'      => $rp_net_selisih,
                        'rp_net_ritel_motoris'      => $rp_net_ritel_motoris,
                        'tipe_trans1'      => $tipe_trans1,
                        'qty_kecil_crt'      => $qty_kecil_crt,
                        'qty_bonus_crt'      => $qty_bonus_crt,
                        'latitude'      => $latitude,
                        'longitude'      => $longitude,
                        'tanggal_fjp'      => $tanggal_fjp,
                        'status_ob'      => $status_ob,                        
                        'signature' => $signature,
                        'created_at'    => $created_at,
                        'created_by'    => $this->session->userdata('id')
                    ];

                    $this->db->insert('management_raw.raw_pontianak',$data);
                }
            }
        }else{
            var_dump($this->upload->display_errors());
            die;
        };

        $get_count = "select count(*) as count from management_raw.raw_pontianak a where a.signature = '$signature'";
        $count = $this->db->query($get_count)->row()->count;

        $get_omzet = "select sum(a.rp_kotor) as omzet_raw from management_raw.raw_pontianak a where a.signature = '$signature'";
        $omzet_raw = $this->db->query($get_omzet)->row()->omzet_raw;

        $upload = [
            'site_code'     => 'PTK82',
            'filename'      => $filename,
            'signature'     => $signature,
            'count_raw'     => $count,
            'omzet_raw'     => $omzet_raw,
            'type_file'     => 'raw_sales',
            'bulan'         => $bulan_from_input,
            'tahun'         => $tahun_from_input,
            'created_at'    => $created_at,
            'created_by'    => $this->session->userdata('id')
        ];
        $this->db->insert('management_raw.log_upload', $upload);

        redirect('management_raw/import_pontianak_draft/'.$signature);
    }

    public function import_pontianak_draft($signature){

        $data = [
            'title'     => 'Management Raw / Preview Import data pontianak',
            'id'        => $this->session->userdata('id'),
            'get_raw_draft'  => $this->model_management_raw->get_raw_draft('PTK82', $signature),
            'url'=> 'management_raw/proses_mapping_pontianak',
            'get_summary' => $this->model_management_raw->get_summary_raw_sales_samarinda('PTK82', $signature),
            'signature' => $signature
        ];
        $this->load->view('management_raw/header');
        $this->load->view('management_raw/draft_pontianak', $data);
        $this->load->view('mes/footer');
    }

    public function proses_mapping_pontianak(){

        $signature = $this->input->post('signature');

        $update_kodeproduk = $this->model_management_raw->update_kodeproduk_bontang('PTK82', $signature);
        if ($update_kodeproduk) {            
            echo "<br><center><i>sukses menambahkan angka 0 di kodeproduk ... </i></b><br>";
        }
        
        $inner_kodeproduk = $this->model_management_raw->inner_kodeproduk_samarinda('PTK82', $signature);
        if ($inner_kodeproduk) {
            echo "<br><center><i>sukses memfilter produk khusus mpm / membuang produk selain mpm ...</i></b><br>";
        }

        $update_namaprod = $this->model_management_raw->update_namaprod_samarinda('PTK82', $signature);
        if ($update_namaprod) {
            echo "<br><center><i>sukses mengupdate namaproduk ...</i></b><br>";
        }

        $update_branch = $this->model_management_raw->update_branch('PTK82', $signature);
        if ($update_branch) {
            echo "<br><center><i>sukses mengupdate branch_name dan nama_comp ...</i></b><br>";
        }

        $update_tanggal = $this->model_management_raw->update_tanggal('PTK82', $signature);                       
        if ($update_tanggal) {
            echo "<br><center><i>sukses memperbaiki format tanggal ...</i></b><br>";
        }

        $prepare_tblang_bantu = $this->model_management_raw->prepare_tblang_bantu('PTK82', $signature);
        if ($prepare_tblang_bantu) {
            echo "<br><center><i>prepare_tblang_bantu done ...</i></b><br>";
        }
        // die;

        $delete_tabel = $this->model_management_raw->delete_tabel('PTK82', $signature);
        if ($delete_tabel) {
            echo "<br><center><i>delete tabel done ...</i></b><br>";
        }

        $insert_fi = $this->model_management_raw->insert_fi_samarinda('PTK82', $signature);
        if ($insert_fi) {
            echo "<br><center><i>insert fi (sales) done ...</i></b><br>";
        }

        $insert_ri = $this->model_management_raw->insert_ri_samarinda('PTK82', $signature);
        if ($insert_ri) {
            echo "<br><center><i>insert ri (sales) done ...</i></b><br>";
        }

        $insert_tblang_bantu = $this->model_management_raw->insert_tblang_bantu_banjarmasin('PTK82', $signature);
        if ($insert_tblang_bantu) {
            echo "<br><center><i>insert tblang bantu done ...</i></b><br>";
        }

        // die;

        // $update_tblang_bantu = $this->model_management_raw->update_tblang_bantu('PTK82', $signature);
        // if ($update_tblang_bantu) {
        //     echo "<br><center><i>update_tblang bantu done ...</i></b><br>";
        // }

        $insert_tblang = $this->model_management_raw->insert_tblang_banjarmasin('PTK82', $signature);
        if ($insert_tblang) {
            echo "<br><center><i>insert tblang done ...</i></b><br>";
        }

        // die;

        // $insert_tblang = $this->model_management_raw->insert_tblang_batulicin('PTK82', $signature);
        // if ($insert_tblang) {
        //     echo "<br><center><i>insert tblang done ...</i></b><br>";
        // }

        $insert_tabsales = $this->model_management_raw->insert_tabsales_batulicin('PTK82', $signature);
        if ($insert_tabsales) {
            echo "<br><center><i>insert tabsales done ...</i></b><br>";
        }

        $insert_tbkota = $this->model_management_raw->insert_tbkota_samarinda('PTK82', $signature);
        if ($insert_tbkota) {
            echo "<br><center><i>insert tbkota done ...</i></b><br>";
        }

        $get_count = "select count(*) as count from management_raw.inner_raw_pontianak a where a.signature = '$signature'";
        $count = $this->db->query($get_count)->row()->count;

        $get_bulan = $this->model_management_raw->get_log_upload_by_signature($signature)->row()->bulan;
        $get_tahun = $this->model_management_raw->get_log_upload_by_signature($signature)->row()->tahun;

        $get_omzet = $this->model_management_raw->get_omzet_web($get_tahun, $get_bulan, 'PTK82')->row()->omzet;

        $update = [
            'count_mapping' => $count,
            'omzet_web'     => $get_omzet,
        ];
        $this->db->where('signature', $signature);
        $this->db->update('management_raw.log_upload', $update);

        $insert_mpm_upload = $this->model_management_raw->insert_mpm_upload('PTK82', $signature, $get_omzet);
        if ($insert_mpm_upload) {
            echo "<br><center><i>insert mpm.upload done ...</i></b><br>";
        }

        echo "<br><center><i>dalam 5 detik anda akan di redirect ke halaman awal ...</i></b><br>";
        header('Refresh: 5; URL='.base_url().'management_raw/pontianak');    

    }

    public function template_kendari(){        
        $query = "
            select  '' as KDDOKJDI,	'' as NODOKJDI, '' as TGLDOKJDI, '' as NODOKACU, '' as KODEPROD, '' as NAMAPROD, '' as AREA, '' as NAMAAREA,
                    '' as KODEJAJA, '' as NAMAJAJA, '' as KODELANG, '' as NAMALANG, '' as TYPELANG, '' as DESCLANG, '' as CLASS, '' as ALMTLANG,
                    '' as BANYAK, '' as BANYS, '' as HNA, '' as POTONGAN, '' as TOTHNA

        ";

        $hsl = $this->db->query($query);
        query_to_csv($hsl,TRUE,'Template_kendari.csv');
    }

    public function kendari(){

        $data = [
            'title' => 'Management Raw / Import data kendari',
            'id' => $this->session->userdata('id'),
            'url_import' => 'management_raw/import_kendari',
            'get_log_upload' => $this->model_management_raw->get_log_upload('CSSK3')
        ];
        $this->load->view('management_raw/header');
        $this->load->view('management_raw/kendari', $data);
        $this->load->view('mes/footer');
    }

    public function import_kendari(){
        $bulan_from_input = substr($this->input->post('bulan'), 5, 2);
        $tahun_from_input = substr($this->input->post('bulan'), 0, 4);

        $cek_last_upload = "
            select *
            from mpm.upload a 
            where a.userid = 382 
            ORDER BY a.id desc
        ";
        $tahun_last_upload = $this->db->query($cek_last_upload)->row()->tahun;
        $bulan_last_upload = $this->db->query($cek_last_upload)->row()->bulan;
        $closing_last_upload = $this->db->query($cek_last_upload)->row()->status_closing;

        if ($closing_last_upload == 1) {
            $tanggal_seharusnya = date('Y-m-d', strtotime('+1 month', strtotime($tahun_last_upload.'-'.$bulan_last_upload.'-01')));           
            $bulan_seharusnya = date('m', strtotime($tanggal_seharusnya));
            $tahun_seharusnya = date('Y', strtotime($tanggal_seharusnya));

            if ($tahun_from_input === $tahun_seharusnya && $bulan_from_input === $bulan_seharusnya) {
                
            }else{
                echo "<br><hr>Tanggal yang anda pilih adalah :<br><br>";
                // echo "<hr>";
                echo "tahun : ".$tahun_from_input."<br>";
                echo "bulan : ".$bulan_from_input."<br><br>";
                echo "<hr>";

                echo "<br>Tanggal upload terakhir adalah :<br><br>";
                // echo "<hr>";
                echo "tahun_last_upload : ".$tahun_last_upload."<br>";
                echo "bulan_last_upload : ".$bulan_last_upload."<br>";
                echo "closing_last_upload : ".$closing_last_upload."<br><br>";
                echo "<hr>";
                
                echo "<h3>Result</h3>";
                echo "Data ditolak !!<br><br>";
                echo "Anda hanya diijinkan mengupload data untuk <b>tahun : $tahun_seharusnya</b>, dan <b>bulan : $bulan_seharusnya</b><br>";

                echo "<br>anda akan di redirect ke menu awal dalam 10 detik";
                header('Refresh: 10; URL='.base_url().'management_raw/kendari');
                die;
            }

        }else{

            $tanggal_seharusnya = date('Y-m-d', strtotime('+0 month', strtotime($tahun_last_upload.'-'.$bulan_last_upload.'-01')));           
            $bulan_seharusnya = date('m', strtotime($tanggal_seharusnya));
            $tahun_seharusnya = date('Y', strtotime($tanggal_seharusnya));

            if ($tahun_from_input === $tahun_seharusnya && $bulan_from_input === $bulan_seharusnya) {
                // echo "diijinkan";
            }else{
                echo "<br><hr>Tanggal yang anda pilih adalah :<br><br>";
                echo "tahun : ".$tahun_from_input."<br>";
                echo "bulan : ".$bulan_from_input."<br><br>";
                echo "<hr>";


                echo "<br>Tanggal upload terakhir adalah :<br><br>";
                echo "tahun_last_upload : ".$tahun_last_upload."<br>";
                echo "bulan_last_upload : ".$bulan_last_upload."<br>";
                echo "closing_last_upload : ".$closing_last_upload."<br><br>";
                echo "<hr>";
                
                echo "<h3>Result</h3>";
                echo "Data ditolak !!<br><br>";
                echo "Anda hanya diijinkan mengupload data untuk <b>tahun : $tahun_seharusnya</b>, dan <b>bulan : $bulan_seharusnya</b><br>";

                echo "<br>anda akan di redirect ke menu awal dalam 10 detik";
                header('Refresh: 10; URL='.base_url().'management_raw/kendari');
                die;
            }

        }

        // die;

        if (!is_dir('./assets/uploads/management_raw/import/')) {
            @mkdir('./assets/uploads/management_raw/import/', 0777);
        }

        $this->load->library('upload'); // Load librari upload        
        $config['upload_path'] = './assets/uploads/management_raw/import/';
        // $config['allowed_types'] = 'xls|xlsx|csv';
        $config['allowed_types'] = '*';    
        $config['max_size']  = '2048000';
        $config['overwrite'] = true;
        $config['encrypt_name'] = TRUE;
        $this->upload->initialize($config);

        if ($this->upload->do_upload('file')) 
        {

            $upload_data = $this->upload->data();
            $filename = $upload_data['orig_name'];

            $this->load->library('excel');
            $object = PHPExcel_IOFactory::load("assets/uploads/management_raw/import/$filename");

            $jumlahSheet = $object->getSheetCount();
            if ($jumlahSheet > 1) {
                echo "jumlah_sheet : ".$jumlahSheet;
                echo "<script>alert('upload file gagal karena file mempunyai lebih dari 1 sheet'); </script>";
                redirect('management_raw/kendari','refresh');
            }

            $highestColumm = $object->setActiveSheetIndex(0)->getHighestColumn();
            // var_dump($highestColumm);die;
            if ($highestColumm != 'U') {
                echo "<script>alert('upload file gagal karena column tidak sesuai'); </script>";
                redirect('management_raw/kendari','refresh');
            }

            // die;

            $created_at = $this->model_outlet_transaksi->timezone();
            $signature = md5($this->model_outlet_transaksi->timezone());

            foreach ($object->getWorksheetIterator() as $worksheet) {

                $highestRow = $worksheet->getHighestRow();
                $highestColumn = $worksheet->getHighestColumn();

                for ($row = 2; $row <= $highestRow; $row++) {         
                    $KDDOKJDI = trim($worksheet->getCellByColumnAndRow(0, $row)->getValue());
                    $NODOKJDI = trim($worksheet->getCellByColumnAndRow(1, $row)->getValue());
                    $TGLDOKJDI = trim($worksheet->getCellByColumnAndRow(2, $row)->getValue());
                    $NODOKACU = trim($worksheet->getCellByColumnAndRow(3, $row)->getValue());
                    $KODEPROD = trim($worksheet->getCellByColumnAndRow(4, $row)->getValue());
                    $NAMAPROD = trim($worksheet->getCellByColumnAndRow(5, $row)->getValue());
                    $AREA = trim($worksheet->getCellByColumnAndRow(6, $row)->getValue());
                    $NAMAAREA = trim($worksheet->getCellByColumnAndRow(7, $row)->getValue());
                    $KODEJAJA = trim($worksheet->getCellByColumnAndRow(8, $row)->getValue());
                    $NAMAJAJA = trim($worksheet->getCellByColumnAndRow(9, $row)->getValue());
                    $KODELANG = trim($worksheet->getCellByColumnAndRow(10, $row)->getValue());
                    $NAMALANG = trim($worksheet->getCellByColumnAndRow(11, $row)->getValue());
                    $TYPELANG = trim($worksheet->getCellByColumnAndRow(12, $row)->getValue());
                    $DESCLANG = trim($worksheet->getCellByColumnAndRow(13, $row)->getValue());
                    $CLASS = trim($worksheet->getCellByColumnAndRow(14, $row)->getValue());
                    $ALMTLANG = trim($worksheet->getCellByColumnAndRow(15, $row)->getValue());
                    $BANYAK = trim($worksheet->getCellByColumnAndRow(16, $row)->getValue());
                    $BANYS = trim($worksheet->getCellByColumnAndRow(17, $row)->getValue());
                    $HNA = trim($worksheet->getCellByColumnAndRow(18, $row)->getValue());
                    $POTONGAN = trim($worksheet->getCellByColumnAndRow(19, $row)->getValue());
                    $TOTHNA = trim($worksheet->getCellByColumnAndRow(20, $row)->getValue());

                    $data = [
                        'KDDOKJDI' => $KDDOKJDI,
                        'NODOKJDI' => $NODOKJDI,
                        'TGLDOKJDI' => $TGLDOKJDI,
                        'NODOKACU' => $NODOKACU,
                        'KODEPROD' => $KODEPROD,
                        'NAMAPROD' => $NAMAPROD,
                        'AREA' => $AREA,
                        'NAMAAREA' => $NAMAAREA,
                        'KODEJAJA' => $KODEJAJA,
                        'NAMAJAJA' => $NAMAJAJA,
                        'KODELANG' => $KODELANG,
                        'NAMALANG' => $NAMALANG,
                        'TYPELANG' => $TYPELANG,
                        'DESCLANG' => $DESCLANG,
                        'CLASS' => $CLASS,
                        'ALMTLANG' => $ALMTLANG,
                        'BANYAK' => $BANYAK,
                        'BANYS' => $BANYS,
                        'HNA' => $HNA,
                        'POTONGAN' => $POTONGAN,
                        'TOTHNA' => $TOTHNA,
                        'SIGNATURE' => $signature,
                        'CREATED_AT' => $created_at,
                        'CREATED_BY' => $this->session->userdata('id')
                    ];

                    $this->db->insert('management_raw.raw_kendari',$data);
                }
            }
        }else{
        
        };

        $get_count = "select count(*) as count from management_raw.raw_kendari a where a.signature = '$signature'";
        $count = $this->db->query($get_count)->row()->count;
        
        $get_omzet = "select sum(a.tothna * 1.11) as omzet_raw from management_raw.raw_kendari a where a.signature = '$signature'";
        $omzet_raw = $this->db->query($get_omzet)->row()->omzet_raw;

        $upload = [
            'site_code'     => 'CSSK3',
            'filename'      => $filename,
            'signature'     => $signature,
            'count_raw'     => $count,
            'omzet_raw'     => $omzet_raw,
            'type_file'     => 'raw_sales',
            'bulan'         => $bulan_from_input,
            'tahun'         => $tahun_from_input,
            'created_at'    => $created_at,
            'created_by'    => $this->session->userdata('id')
        ];
        $this->db->insert('management_raw.log_upload', $upload);
        redirect('management_raw/import_kendari_draft/'.$signature);
    }
    
    public function import_kendari_draft($signature){
        $get_omzet = "select sum(a.tothna * 1.11) as omzet_raw from management_raw.raw_kendari a where a.signature = '$signature'";
        $omzet_raw = $this->db->query($get_omzet)->row()->omzet_raw;

        $data = [
            'title'     => 'Management Raw / Preview Import data kendari',
            'id'        => $this->session->userdata('id'),
            'get_raw_draft'  => $this->model_management_raw->get_raw_draft_kendari('CSSK3', $signature),
            'omzet_raw' => $omzet_raw,
            'url'=> 'management_raw/proses_mapping_kendari',
            'signature' => $signature
        ];
        $this->load->view('management_raw/header');
        $this->load->view('management_raw/draft_kendari', $data);
        $this->load->view('mes/footer');
    }

    public function proses_mapping_kendari(){

        $signature = $this->input->post('signature');
        $status_closing = $this->input->post('status_closing');
        $id_imports = $this->input->post('options');

        $update_kodeproduk = $this->model_management_raw->update_kodeproduk_kendari('CSSK3', $signature);
        if ($update_kodeproduk) {   
            echo "<br><center><i>sukses menambahkan angka 0 di kodeproduk ... </i></b><br>";
        }

        $inner_kodeproduk = $this->model_management_raw->inner_kodeproduk_kendari('CSSK3', $signature);
        if ($inner_kodeproduk) {
            echo "<br><center><i>sukses memfilter produk khusus mpm / membuang produk selain mpm ...</i></b><br>";
        }

        $update_namaprod = $this->model_management_raw->update_namaprod_kendari('CSSK3', $signature);
        if ($update_namaprod) {
            echo "<br><center><i>sukses mengupdate namaproduk ...</i></b><br>";
        }

        $update_branch = $this->model_management_raw->update_branch('CSSK3', $signature);
        if ($update_branch) {
            echo "<br><center><i>sukses mengupdate branch_name dan nama_comp ...</i></b><br>";
        }

        $update_tanggal = $this->model_management_raw->update_tanggal_kendari('CSSK3', $signature);                       
        if ($update_tanggal) {
            echo "<br><center><i>sukses memperbaiki format tanggal ...</i></b><br>";
        }

        $delete_tabel = $this->model_management_raw->delete_tabel('CSSK3', $signature);
        if ($delete_tabel) {
            echo "<br><center><i>delete tabel done ...</i></b><br>";
        }

        $insert_fi = $this->model_management_raw->insert_fi_kendari('CSSK3', $signature);
        if ($insert_fi) {
            echo "<br><center><i>insert fi (sales) done ...</i></b><br>";
        }

        $insert_ri = $this->model_management_raw->insert_ri_kendari('CSSK3', $signature);
        if ($insert_ri) {
            echo "<br><center><i>insert ri (retur) done ...</i></b><br>";
        }

        $insert_tblang = $this->model_management_raw->insert_tblang_kendari('CSSK3', $signature);
        if ($insert_tblang) {
            echo "<br><center><i>insert tblang done ...</i></b><br>";
        }

        $insert_tabsales = $this->model_management_raw->insert_tabsales_kendari('CSSK3', $signature);
        if ($insert_tabsales) {
            echo "<br><center><i>insert tabsales done ...</i></b><br>";
        }

        $insert_tbkota = $this->model_management_raw->insert_tbkota_kendari('CSSK3', $signature);
        if ($insert_tbkota) {
            echo "<br><center><i>insert tbkota done ...</i></b><br>";
        }        

        $get_count = "select count(*) as count from management_raw.inner_raw_kendari a where a.signature = '$signature'";
        $count = $this->db->query($get_count)->row()->count;

        $get_bulan = $this->model_management_raw->get_log_upload_by_signature($signature)->row()->bulan;
        $get_tahun = $this->model_management_raw->get_log_upload_by_signature($signature)->row()->tahun;

        $get_omzet = $this->model_management_raw->get_omzet_web($get_tahun, $get_bulan, 'CSSK3')->row()->omzet;

        $update = [
            'count_mapping' => $count,
            'omzet_web'     => $get_omzet,
        ];
        $this->db->where('signature', $signature);
        $this->db->update('management_raw.log_upload', $update);

        $insert_mpm_upload = $this->model_management_raw->insert_mpm_upload_kendari('CSSK3', $signature, $get_omzet, $status_closing);
        if ($insert_mpm_upload) {
            echo "<br><center><i>insert mpm.upload done ...</i></b><br>";
        }
        
        echo "<br><center><i>dalam 5 detik anda akan di redirect ke halaman awal ...</i></b><br>";
        header('Refresh: 5; URL='.base_url().'management_raw/kendari');    
    }

    public function template_balikpapan(){        
        $query = "
            select  '' as DISTRIBUTOR, '' as TRANS, '' as KD_PRODUK, '' as KD_PABRIK, '' as NM_PRODUK, '' as 'SUBBRAND(GROUP)', '' as BRAND,
                    '' as DEPT, '' as PRINCIPAL, '' as KATEGORI, '' as PROD_CLASS, '' as QTY, '' as KARTONUTUH, '' as PIECES, '' as FREEGOOD_PCS,
                    '' as NILAI_BRUTTO_DPP, '' as NILAI_NETTO_DPP, '' as NILAI_NETTO_PPN, '' as NILAI_FREEGOOD, '' as DISCITEM, '' as DISCNOTA,
                    '' as KD_CUST, '' as NM_CUST, '' as TYPE, '' as AREA, '' as SUBAREA, '' as SALESMAN, '' as SUPERVISOR, '' as HCODE1, '' as HDESC1,
                    '' as HCODE2, '' as HDESC2, '' as HCODE3, '' as HDESC3, '' as NO_NOTA, '' as TANGGAL, '' as BULAN, '' as 'TAHUN-BULAN', '' as WEEK,
                    '' as KEYACCOUNT, '' as KODESALESMAN, '' as CUSTGROUP, '' as CABANG, '' as ALAMAT, '' as CUSTOMERPO, '' as DIVISI, '' as TONNAGE,
                    '' as QTYSOLD_PCS, '' as DISCSPECIAL, '' as DISCSPECIALRP, '' as DISCPROMOSITOTALRP, '' as JOINDATESALESMAN, '' as JOINDATECUSTOMER, '' as PRODUCT_BARCODE,
                    '' as CUSTOMER_BARCODE, '' as DRIVERID
        ";

        $hsl = $this->db->query($query);
        query_to_csv($hsl,TRUE,'Template_balikpapan.csv');
    }

    public function balikpapan(){

        $data = [
            'title' => 'Management Raw / Import data balikpapan',
            'id' => $this->session->userdata('id'),
            'url_import' => 'management_raw/import_balikpapan',
            'get_log_upload' => $this->model_management_raw->get_log_upload('DASD1')
        ];
        $this->load->view('management_raw/header');
        $this->load->view('management_raw/balikpapan', $data);
        $this->load->view('mes/footer');
    }

    public function import_balikpapan(){
        $bulan_from_input = substr($this->input->post('bulan'), 5, 2);
        $tahun_from_input = substr($this->input->post('bulan'), 0, 4);

        $cek_last_upload = "
            select *
            from mpm.upload a 
            where a.userid = 622 
            ORDER BY a.id desc
        ";
        $tahun_last_upload = $this->db->query($cek_last_upload)->row()->tahun;
        $bulan_last_upload = $this->db->query($cek_last_upload)->row()->bulan;
        $closing_last_upload = $this->db->query($cek_last_upload)->row()->status_closing;

        if ($closing_last_upload == 1) {
            $tanggal_seharusnya = date('Y-m-d', strtotime('+1 month', strtotime($tahun_last_upload.'-'.$bulan_last_upload.'-01')));           
            $bulan_seharusnya = date('m', strtotime($tanggal_seharusnya));
            $tahun_seharusnya = date('Y', strtotime($tanggal_seharusnya));

            if ($tahun_from_input === $tahun_seharusnya && $bulan_from_input === $bulan_seharusnya) {
                
            }else{
                echo "<br><hr>Tanggal yang anda pilih adalah :<br><br>";
                // echo "<hr>";
                echo "tahun : ".$tahun_from_input."<br>";
                echo "bulan : ".$bulan_from_input."<br><br>";
                echo "<hr>";

                echo "<br>Tanggal upload terakhir adalah :<br><br>";
                // echo "<hr>";
                echo "tahun_last_upload : ".$tahun_last_upload."<br>";
                echo "bulan_last_upload : ".$bulan_last_upload."<br>";
                echo "closing_last_upload : ".$closing_last_upload."<br><br>";
                echo "<hr>";
                
                echo "<h3>Result</h3>";
                echo "Data ditolak !!<br><br>";
                echo "Anda hanya diijinkan mengupload data untuk <b>tahun : $tahun_seharusnya</b>, dan <b>bulan : $bulan_seharusnya</b><br>";

                echo "<br>anda akan di redirect ke menu awal dalam 10 detik";
                header('Refresh: 10; URL='.base_url().'management_raw/balikpapan');
                die;
            }

        }else{

            $tanggal_seharusnya = date('Y-m-d', strtotime('+0 month', strtotime($tahun_last_upload.'-'.$bulan_last_upload.'-01')));           
            $bulan_seharusnya = date('m', strtotime($tanggal_seharusnya));
            $tahun_seharusnya = date('Y', strtotime($tanggal_seharusnya));

            if ($tahun_from_input === $tahun_seharusnya && $bulan_from_input === $bulan_seharusnya) {
                // echo "diijinkan";
            }else{
                echo "<br><hr>Tanggal yang anda pilih adalah :<br><br>";
                echo "tahun : ".$tahun_from_input."<br>";
                echo "bulan : ".$bulan_from_input."<br><br>";
                echo "<hr>";


                echo "<br>Tanggal upload terakhir adalah :<br><br>";
                echo "tahun_last_upload : ".$tahun_last_upload."<br>";
                echo "bulan_last_upload : ".$bulan_last_upload."<br>";
                echo "closing_last_upload : ".$closing_last_upload."<br><br>";
                echo "<hr>";
                
                echo "<h3>Result</h3>";
                echo "Data ditolak !!<br><br>";
                echo "Anda hanya diijinkan mengupload data untuk <b>tahun : $tahun_seharusnya</b>, dan <b>bulan : $bulan_seharusnya</b><br>";

                echo "<br>anda akan di redirect ke menu awal dalam 10 detik";
                header('Refresh: 10; URL='.base_url().'management_raw/balikpapan');
                die;
            }

        }

        // die;

        if (!is_dir('./assets/uploads/management_raw/import/')) {
            @mkdir('./assets/uploads/management_raw/import/', 0777);
        }

        $this->load->library('upload'); // Load librari upload        
        $config['upload_path'] = './assets/uploads/management_raw/import/';
        // $config['allowed_types'] = 'xls|xlsx|csv';
        $config['allowed_types'] = '*';    
        $config['max_size']  = '2048000';
        $config['overwrite'] = true;
        $config['encrypt_name'] = TRUE;
        $this->upload->initialize($config);

        if ($this->upload->do_upload('file')) 
        {

            $upload_data = $this->upload->data();
            $filename = $upload_data['orig_name'];

            $this->load->library('excel');
            $object = PHPExcel_IOFactory::load("assets/uploads/management_raw/import/$filename");

            $jumlahSheet = $object->getSheetCount();
            if ($jumlahSheet > 1) {
                echo "jumlah_sheet : ".$jumlahSheet;
                echo "<script>alert('upload file gagal karena file mempunyai lebih dari 1 sheet'); </script>";
                redirect('management_raw/balikpapan','refresh');
            }

            $highestColumm = $object->setActiveSheetIndex(0)->getHighestColumn();
            // var_dump($highestColumm);die;
            if ($highestColumm != 'BD') {
                echo "<script>alert('upload file gagal karena column tidak sesuai'); </script>";
                redirect('management_raw/balikpapan','refresh');
            }

            // die;

            $created_at = $this->model_outlet_transaksi->timezone();
            $signature = md5($this->model_outlet_transaksi->timezone());

            foreach ($object->getWorksheetIterator() as $worksheet) {

                $highestRow = $worksheet->getHighestRow();
                $highestColumn = $worksheet->getHighestColumn();

                for ($row = 2; $row <= $highestRow; $row++) {         
                    $DISTRIBUTOR = trim($worksheet->getCellByColumnAndRow(0, $row)->getValue());
                    $TRANS = trim($worksheet->getCellByColumnAndRow(1, $row)->getValue());
                    $KD_PRODUK = trim($worksheet->getCellByColumnAndRow(2, $row)->getValue());
                    $KD_PABRIK = trim($worksheet->getCellByColumnAndRow(3, $row)->getValue());
                    $NM_PRODUK = trim($worksheet->getCellByColumnAndRow(4, $row)->getValue());
                    $GROUP = trim($worksheet->getCellByColumnAndRow(5, $row)->getValue());
                    $BRAND = trim($worksheet->getCellByColumnAndRow(6, $row)->getValue());
                    $DEPT = trim($worksheet->getCellByColumnAndRow(7, $row)->getValue());
                    $PRINCIPAL = trim($worksheet->getCellByColumnAndRow(8, $row)->getValue());
                    $KATEGORI = trim($worksheet->getCellByColumnAndRow(9, $row)->getValue());
                    $PROD_CLASS = trim($worksheet->getCellByColumnAndRow(10, $row)->getValue());
                    $QTY = trim($worksheet->getCellByColumnAndRow(11, $row)->getValue());
                    $KARTONUTUH = trim($worksheet->getCellByColumnAndRow(12, $row)->getValue());
                    $PIECES = trim($worksheet->getCellByColumnAndRow(13, $row)->getValue());
                    $FREEGOOD_PCS = trim($worksheet->getCellByColumnAndRow(14, $row)->getValue());
                    $NILAI_BRUTTO_DPP = trim($worksheet->getCellByColumnAndRow(15, $row)->getValue());
                    $NILAI_NETTO_DPP = trim($worksheet->getCellByColumnAndRow(16, $row)->getValue());
                    $NILAI_NETTO_PPN = trim($worksheet->getCellByColumnAndRow(17, $row)->getValue());
                    $NILAI_FREEGOOD = trim($worksheet->getCellByColumnAndRow(18, $row)->getValue());
                    $DISCITEM = trim($worksheet->getCellByColumnAndRow(19, $row)->getValue());
                    $DISCNOTA = trim($worksheet->getCellByColumnAndRow(20, $row)->getValue());
                    $KD_CUST = trim($worksheet->getCellByColumnAndRow(21, $row)->getValue());
                    $NM_CUST = trim($worksheet->getCellByColumnAndRow(22, $row)->getValue());
                    $TYPE = trim($worksheet->getCellByColumnAndRow(23, $row)->getValue());
                    $AREA = trim($worksheet->getCellByColumnAndRow(24, $row)->getValue());
                    $SUBAREA = trim($worksheet->getCellByColumnAndRow(25, $row)->getValue());
                    $SALESMAN = trim($worksheet->getCellByColumnAndRow(26, $row)->getValue());
                    $SUPERVISOR = trim($worksheet->getCellByColumnAndRow(27, $row)->getValue());
                    $HCODE1 = trim($worksheet->getCellByColumnAndRow(28, $row)->getValue());
                    $HDESC1 = trim($worksheet->getCellByColumnAndRow(29, $row)->getValue());
                    $HCODE2 = trim($worksheet->getCellByColumnAndRow(30, $row)->getValue());
                    $HDESC2 = trim($worksheet->getCellByColumnAndRow(31, $row)->getValue());
                    $HCODE3 = trim($worksheet->getCellByColumnAndRow(32, $row)->getValue());
                    $HDESC3 = trim($worksheet->getCellByColumnAndRow(33, $row)->getValue());             
                    $NO_NOTA = trim($worksheet->getCellByColumnAndRow(34, $row)->getValue());             
                    $TANGGAL = trim($worksheet->getCellByColumnAndRow(35, $row)->getValue());             
                    $BULAN = trim($worksheet->getCellByColumnAndRow(36, $row)->getValue());             
                    $TAHUN_BULAN = trim($worksheet->getCellByColumnAndRow(37, $row)->getValue());             
                    $WEEK = trim($worksheet->getCellByColumnAndRow(38, $row)->getValue());             
                    $KEYACCOUNT = trim($worksheet->getCellByColumnAndRow(39, $row)->getValue());             
                    $KODESALESMAN = trim($worksheet->getCellByColumnAndRow(40, $row)->getValue());             
                    $CUSTGROUP = trim($worksheet->getCellByColumnAndRow(41, $row)->getValue());             
                    $CABANG = trim($worksheet->getCellByColumnAndRow(42, $row)->getValue());             
                    $ALAMAT = trim($worksheet->getCellByColumnAndRow(43, $row)->getValue());             
                    $CUSTOMERPO = trim($worksheet->getCellByColumnAndRow(44, $row)->getValue());             
                    $DIVISI = trim($worksheet->getCellByColumnAndRow(45, $row)->getValue());             
                    $TONNAGE = trim($worksheet->getCellByColumnAndRow(46, $row)->getValue());             
                    $QTYSOLD_PCS = trim($worksheet->getCellByColumnAndRow(47, $row)->getValue());             
                    $DISCSPECIAL = trim($worksheet->getCellByColumnAndRow(48, $row)->getValue());             
                    $DISCSPECIALRP = trim($worksheet->getCellByColumnAndRow(49, $row)->getValue());             
                    $DISCPROMOSITOTALRP = trim($worksheet->getCellByColumnAndRow(50, $row)->getValue());             
                    $JOINDATESALESMAN = trim($worksheet->getCellByColumnAndRow(51, $row)->getValue());             
                    $JOINDATECUSTOMER = trim($worksheet->getCellByColumnAndRow(52, $row)->getValue());             
                    $PRODUCT_BARCODE = trim($worksheet->getCellByColumnAndRow(53, $row)->getValue());             
                    $CUSTOMER_BARCODE = trim($worksheet->getCellByColumnAndRow(54, $row)->getValue());             
                    $DRIVERID = trim($worksheet->getCellByColumnAndRow(55, $row)->getValue());             

                    $data = [
                        'DISTRIBUTOR' => $DISTRIBUTOR,
                        'TRANS' => $TRANS,
                        'KD_PRODUK' => $KD_PRODUK,
                        'KD_PABRIK' => $KD_PABRIK,
                        'NM_PRODUK' => $NM_PRODUK,
                        'GROUP' => $GROUP,
                        'BRAND' => $BRAND,
                        'DEPT' => $DEPT,
                        'PRINCIPAL' => $PRINCIPAL,
                        'KATEGORI' => $KATEGORI,
                        'PROD_CLASS' => $PROD_CLASS,
                        'QTY' => $QTY,
                        'KARTONUTUH' => $KARTONUTUH,
                        'PIECES' => $PIECES,
                        'FREEGOOD_PCS' => $FREEGOOD_PCS,
                        'NILAI_BRUTTO_DPP' => $NILAI_BRUTTO_DPP,
                        'NILAI_NETTO_DPP' => $NILAI_NETTO_DPP,
                        'NILAI_NETTO_PPN' => $NILAI_NETTO_PPN,
                        'NILAI_FREEGOOD' => $NILAI_FREEGOOD,
                        'DISCITEM' => $DISCITEM,
                        'DISCNOTA' => $DISCNOTA,
                        'KD_CUST' => $KD_CUST,
                        'NM_CUST' => $NM_CUST,
                        'TYPE' => $TYPE,
                        'AREA' => $AREA,
                        'SUBAREA' => $SUBAREA,
                        'SALESMAN' => $SALESMAN,
                        'SUPERVISOR' => $SUPERVISOR,
                        'HCODE1' => $HCODE1,
                        'HDESC1' => $HDESC1,
                        'HCODE2' => $HCODE2,
                        'HDESC2' => $HDESC2,
                        'HCODE3' => $HCODE3,
                        'HDESC3' => $HDESC3,
                        'NO_NOTA' => $NO_NOTA,
                        'TANGGAL' => $TANGGAL,
                        'BULAN' => $BULAN,
                        'TAHUN_BULAN' => $TAHUN_BULAN,
                        'WEEK' => $WEEK,
                        'KEYACCOUNT' => $KEYACCOUNT,
                        'KODESALESMAN' => $KODESALESMAN,
                        'CUSTGROUP' => $CUSTGROUP,
                        'CABANG' => $CABANG,
                        'ALAMAT' => $ALAMAT,
                        'CUSTOMERPO' => $CUSTOMERPO,
                        'DIVISI' => $DIVISI,
                        'TONNAGE' => $TONNAGE,
                        'QTYSOLD_PCS' => $QTYSOLD_PCS,
                        'DISCSPECIAL' => $DISCSPECIAL,
                        'DISCSPECIALRP' => $DISCSPECIALRP,
                        'DISCPROMOSITOTALRP' => $DISCPROMOSITOTALRP,
                        'JOINDATESALESMAN' => $JOINDATESALESMAN,
                        'JOINDATECUSTOMER' => $JOINDATECUSTOMER,
                        'PRODUCT_BARCODE' => $PRODUCT_BARCODE,
                        'CUSTOMER_BARCODE' => $CUSTOMER_BARCODE,
                        'DRIVERID' => $DRIVERID,
                        'SIGNATURE' => $signature,
                        'CREATED_AT' => $created_at,
                        'CREATED_BY' => $this->session->userdata('id')
                    ];

                    $this->db->insert('management_raw.raw_balikpapan',$data);

                    $this->db->select('*');
                    $this->db->where("kd_cust", $KD_CUST);
                    $query = $this->db->get('management_raw.raw_customer_balikpapan');

                    if ($query->num_rows() <= 0) {
                        $this->db->select('kd_cust_mpm');
                        $this->db->order_by('kd_cust_mpm', 'DESC');
                        $query = $this->db->get('management_raw.raw_customer_balikpapan', 1);

                        $data2 = [
                            'KD_CUST' => $KD_CUST,
                            'KD_CUST_MPM' => $query->row()->kd_cust_mpm + 1,
                            'NM_CUST' => $NM_CUST,
                            'TYPE' => $TYPE,
                            'AREA' => $AREA,
                            'CREATED_AT' => $created_at,
                            'CREATED_BY' => $this->session->userdata('id')
                        ];
                        $this->db->insert('management_raw.raw_customer_balikpapan',$data2);
                    }
                }
            }
        }else{
        
        };

        $get_count = "select count(*) as count from management_raw.raw_balikpapan a where a.signature = '$signature'";
        $count = $this->db->query($get_count)->row()->count;
        
        $get_omzet = "select ROUND(sum(a.NILAI_BRUTTO_DPP * 1.11),2) as omzet_raw from management_raw.raw_balikpapan a where a.signature = '$signature'";
        $omzet_raw = $this->db->query($get_omzet)->row()->omzet_raw;

        $upload = [
            'site_code'     => 'DASD1',
            'filename'      => $filename,
            'signature'     => $signature,
            'count_raw'     => $count,
            'omzet_raw'     => $omzet_raw,
            'type_file'     => 'raw_sales',
            'bulan'         => $bulan_from_input,
            'tahun'         => $tahun_from_input,
            'created_at'    => $created_at,
            'created_by'    => $this->session->userdata('id')
        ];
        $this->db->insert('management_raw.log_upload', $upload);
        redirect('management_raw/import_balikpapan_draft/'.$signature);
    }

    public function import_balikpapan_draft($signature){

        $get_omzet = "select ROUND(sum(a.NILAI_BRUTTO_DPP * 1.11),2) as omzet_raw from management_raw.raw_balikpapan a where a.signature = '$signature'";
        $omzet_raw = $this->db->query($get_omzet)->row()->omzet_raw;
        
        $data = [
            'title' => 'Management Raw / Preview Import data balikpapan',
            'id' => $this->session->userdata('id'),
            'get_raw_draft' => $this->model_management_raw->get_raw_draft_balikpapan('DASD1', $signature),
            'omzet_raw' => $omzet_raw,
            'url' => 'management_raw/proses_mapping_balikpapan',
            'signature' => $signature
        ];
        $this->load->view('management_raw/header');
        $this->load->view('management_raw/draft_balikpapan', $data);
        $this->load->view('mes/footer');
    }

    public function proses_mapping_balikpapan(){

        $signature = $this->input->post('signature');
        $status_closing = $this->input->post('status_closing');
        $id_imports = $this->input->post('options');

        $update_kodeproduk = $this->model_management_raw->update_kodeproduk_balikpapan('DASD1', $signature);
        if ($update_kodeproduk) {   
            echo "<br><center><i>sukses menambahkan angka 0 di kodeproduk ... </i></b><br>";
        }

        $inner_kodeproduk = $this->model_management_raw->inner_kodeproduk_balikpapan('DASD1', $signature);
        if ($inner_kodeproduk) {
            echo "<br><center><i>sukses memfilter produk khusus mpm / membuang produk selain mpm ...</i></b><br>";
        }

        $update_namaprod = $this->model_management_raw->update_namaprod_balikpapan('DASD1', $signature);
        if ($update_namaprod) {
            echo "<br><center><i>sukses mengupdate namaproduk ...</i></b><br>";
        }

        $update_branch = $this->model_management_raw->update_branch('DASD1', $signature);
        if ($update_branch) {
            echo "<br><center><i>sukses mengupdate branch_name dan nama_comp ...</i></b><br>";
        }

        $update_tanggal = $this->model_management_raw->update_tanggal_balikpapan('DASD1', $signature);                       
        if ($update_tanggal) {
            echo "<br><center><i>sukses memperbaiki format tanggal ...</i></b><br>";
        }

        $update_inner_customer_id_balikpapan = $this->model_management_raw->update_inner_customer_id_balikpapan($signature);
        if ($update_inner_customer_id_balikpapan) {
            echo "<br><center><i>updating customer_id, nama_customer, alamat done ...</i></b><br>";
        }

        $delete_tabel = $this->model_management_raw->delete_tabel('DASD1', $signature);
        if ($delete_tabel) {
            echo "<br><center><i>delete tabel done ...</i></b><br>";
        }

        $insert_fi = $this->model_management_raw->insert_fi_balikpapan('DASD1', $signature);
        if ($insert_fi) {
            echo "<br><center><i>insert fi (sales) done ...</i></b><br>";
        }

        $insert_ri = $this->model_management_raw->insert_ri_balikpapan('DASD1', $signature);
        if ($insert_ri) {
            echo "<br><center><i>insert ri (retur) done ...</i></b><br>";
        }

        $insert_tblang = $this->model_management_raw->insert_tblang_kendari('DASD1', $signature);
        if ($insert_tblang) {
            echo "<br><center><i>insert tblang done ...</i></b><br>";
        }

        $insert_tabsales = $this->model_management_raw->insert_tabsales_kendari('DASD1', $signature);
        if ($insert_tabsales) {
            echo "<br><center><i>insert tabsales done ...</i></b><br>";
        }

        $insert_tbkota = $this->model_management_raw->insert_tbkota_balikpapan('DASD1', $signature);
        if ($insert_tbkota) {
            echo "<br><center><i>insert tbkota done ...</i></b><br>";
        }        

        $get_count = "select count(*) as count from management_raw.inner_raw_balikpapan a where a.signature = '$signature'";
        $count = $this->db->query($get_count)->row()->count;

        $get_bulan = $this->model_management_raw->get_log_upload_by_signature($signature)->row()->bulan;
        $get_tahun = $this->model_management_raw->get_log_upload_by_signature($signature)->row()->tahun;

        $get_omzet = $this->model_management_raw->get_omzet_web($get_tahun, $get_bulan, 'DASD1')->row()->omzet;

        $update = [
            'count_mapping' => $count,
            'omzet_web'     => $get_omzet,
        ];
        $this->db->where('signature', $signature);
        $this->db->update('management_raw.log_upload', $update);

        $insert_mpm_upload = $this->model_management_raw->insert_mpm_upload('DASD1', $signature, $get_omzet, $status_closing);
        if ($insert_mpm_upload) {
            echo "<br><center><i>insert mpm.upload done ...</i></b><br>";
        }
        
        echo "<br><center><i>dalam 5 detik anda akan di redirect ke halaman awal ...</i></b><br>";
        header('Refresh: 5; URL='.base_url().'management_raw/balikpapan');    
    }

    public function template_manokwari(){        
        $query = "
            select  '' as SCOPE_CABANG, '' as PROVINSI, '' as KAB_KOTA, '' as ZONA, '' as KODE_CUSTOMER, '' as NAMA_CUSTOMER, '' as LIST_CUSTOMER,
                    '' as ALAMAT_CUSTOMER, '' as KODE_KECAMATAN, '' as NAMA_KECAMATAN, '' as CONTACT_PERSON, '' as TELF_CUSTOMER, '' as NO_HP_CUSTOMER,
                    '' as TOP, '' as LIMIT_PIUTANG, '' as LIMIT_NOTA, '' as TMT_AKTIF, '' as TIPE_CUSTOMER, '' as KATEGORI_CUSTOMER, '' as KODE_SALESMAN,
                    '' as BULAN_FAKTUR, '' as TGL_FAKTUR, '' as TGL_JATUH_TEMPO, '' as TOKEN_FAKTUR, '' as NO_FAKTUR, '' as KODE_BARANG, '' as NAMA_BARANG,
                    '' as QTY_FAKTUR, '' as SATUAN_FAKTUR, '' as QTY_TERBESAR, '' as SATUAN_TERBESAR, '' as QTY_TERKECIL, '' as SATUAN_TERKECIL,
                    '' as HARGA_FAKTUR, '' as HARGA_SEBELUM_POT, '' as DISC_1, '' as DISC_2, '' as DISC_3, '' as DISC_4, '' as DISC_VALUE, '' as POT_HARGA,
                    '' as SUBTOTAL_FAKTUR, '' as DPP, '' as PPN, '' as NILAI_PPN, '' as NILAI_FAKTUR, '' as BONUS, '' as DIREKTORI, '' as SUPPLIER,
                    '' as JENIS_BARANG, '' as KATEGORI_BARANG, '' as MERK_BARANG, '' as SIFAT_BARANG, '' as VOLUME_SATUAN, '' as SATUAN_TERKECIL,
                    '' as HPP_FIFO, '' as MARGIN_SATUAN, '' as SUB_TOTAL_MARGIN, '' as TGL_ORDER, '' as NO_DETAIL_SALES_ORDER, '' as QTY_ORDER,
                    '' as SATUAN_ORDER, '' as HARGA_ORDER, '' as SUBTOTAL_ORDER, '' as KETERANGAN, '' as KODE_CUSTOMER_LAMA, '' as TOKEN_DETAIL_PENJUALAN
        ";

        $hsl = $this->db->query($query);
        query_to_csv($hsl,TRUE,'Template_manokwari.csv');
    }

    public function manokwari(){

        $data = [
            'title' => 'Management Raw / Import data manokwari',
            'id' => $this->session->userdata('id'),
            'url_import' => 'management_raw/import_manokwari',
            'get_log_upload' => $this->model_management_raw->get_log_upload('MWRW6')
        ];
        $this->load->view('management_raw/header');
        $this->load->view('management_raw/manokwari', $data);
        $this->load->view('mes/footer');
    }

    public function import_manokwari(){
        $bulan_from_input = substr($this->input->post('bulan'), 5, 2);
        $tahun_from_input = substr($this->input->post('bulan'), 0, 4);

        $cek_last_upload = "
            select *
            from mpm.upload a 
            where a.userid = 618 
            ORDER BY a.id desc
        ";
        $tahun_last_upload = $this->db->query($cek_last_upload)->row()->tahun;
        $bulan_last_upload = $this->db->query($cek_last_upload)->row()->bulan;
        $closing_last_upload = $this->db->query($cek_last_upload)->row()->status_closing;

        if ($closing_last_upload == 1) {
            $tanggal_seharusnya = date('Y-m-d', strtotime('+1 month', strtotime($tahun_last_upload.'-'.$bulan_last_upload.'-01')));           
            $bulan_seharusnya = date('m', strtotime($tanggal_seharusnya));
            $tahun_seharusnya = date('Y', strtotime($tanggal_seharusnya));

            if ($tahun_from_input === $tahun_seharusnya && $bulan_from_input === $bulan_seharusnya) {
                
            }else{
                echo "<br><hr>Tanggal yang anda pilih adalah :<br><br>";
                // echo "<hr>";
                echo "tahun : ".$tahun_from_input."<br>";
                echo "bulan : ".$bulan_from_input."<br><br>";
                echo "<hr>";

                echo "<br>Tanggal upload terakhir adalah :<br><br>";
                // echo "<hr>";
                echo "tahun_last_upload : ".$tahun_last_upload."<br>";
                echo "bulan_last_upload : ".$bulan_last_upload."<br>";
                echo "closing_last_upload : ".$closing_last_upload."<br><br>";
                echo "<hr>";
                
                echo "<h3>Result</h3>";
                echo "Data ditolak !!<br><br>";
                echo "Anda hanya diijinkan mengupload data untuk <b>tahun : $tahun_seharusnya</b>, dan <b>bulan : $bulan_seharusnya</b><br>";

                echo "<br>anda akan di redirect ke menu awal dalam 10 detik";
                header('Refresh: 10; URL='.base_url().'management_raw/manokwari');
                die;
            }

        }else{

            $tanggal_seharusnya = date('Y-m-d', strtotime('+0 month', strtotime($tahun_last_upload.'-'.$bulan_last_upload.'-01')));           
            $bulan_seharusnya = date('m', strtotime($tanggal_seharusnya));
            $tahun_seharusnya = date('Y', strtotime($tanggal_seharusnya));

            if ($tahun_from_input === $tahun_seharusnya && $bulan_from_input === $bulan_seharusnya) {
                // echo "diijinkan";
            }else{
                echo "<br><hr>Tanggal yang anda pilih adalah :<br><br>";
                echo "tahun : ".$tahun_from_input."<br>";
                echo "bulan : ".$bulan_from_input."<br><br>";
                echo "<hr>";


                echo "<br>Tanggal upload terakhir adalah :<br><br>";
                echo "tahun_last_upload : ".$tahun_last_upload."<br>";
                echo "bulan_last_upload : ".$bulan_last_upload."<br>";
                echo "closing_last_upload : ".$closing_last_upload."<br><br>";
                echo "<hr>";
                
                echo "<h3>Result</h3>";
                echo "Data ditolak !!<br><br>";
                echo "Anda hanya diijinkan mengupload data untuk <b>tahun : $tahun_seharusnya</b>, dan <b>bulan : $bulan_seharusnya</b><br>";

                echo "<br>anda akan di redirect ke menu awal dalam 10 detik";
                header('Refresh: 10; URL='.base_url().'management_raw/manokwari');
                die;
            }

        }

        // die;

        if (!is_dir('./assets/uploads/management_raw/import/')) {
            @mkdir('./assets/uploads/management_raw/import/', 0777);
        }

        $this->load->library('upload'); // Load librari upload        
        $config['upload_path'] = './assets/uploads/management_raw/import/';
        // $config['allowed_types'] = 'xls|xlsx|csv';
        $config['allowed_types'] = '*';    
        $config['max_size']  = '2048000';
        $config['overwrite'] = true;
        $config['encrypt_name'] = TRUE;
        $this->upload->initialize($config);

        if ($this->upload->do_upload('file')) 
        {

            $upload_data = $this->upload->data();
            $filename = $upload_data['orig_name'];

            $this->load->library('excel');
            $object = PHPExcel_IOFactory::load("assets/uploads/management_raw/import/$filename");

            $jumlahSheet = $object->getSheetCount();
            if ($jumlahSheet > 1) {
                echo "jumlah_sheet : ".$jumlahSheet;
                echo "<script>alert('upload file gagal karena file mempunyai lebih dari 1 sheet'); </script>";
                redirect('management_raw/manokwari','refresh');
            }

            $highestColumm = $object->setActiveSheetIndex(0)->getHighestColumn();
            // var_dump($highestColumm);die;
            if ($highestColumm != 'BO') {
                echo "<script>alert('upload file gagal karena column tidak sesuai'); </script>";
                redirect('management_raw/manokwari','refresh');
            }

            // die;

            $created_at = $this->model_outlet_transaksi->timezone();
            $signature = md5($this->model_outlet_transaksi->timezone());

            foreach ($object->getWorksheetIterator() as $worksheet) {

                $highestRow = $worksheet->getHighestRow();
                $highestColumn = $worksheet->getHighestColumn();

                for ($row = 2; $row <= $highestRow; $row++) {

                    $scope_cabang = trim($worksheet->getCellByColumnAndRow(0, $row)->getValue());
                    $provinsi = trim($worksheet->getCellByColumnAndRow(1, $row)->getValue());
                    $kab_kota = trim($worksheet->getCellByColumnAndRow(2, $row)->getValue());
                    $zona = trim($worksheet->getCellByColumnAndRow(3, $row)->getValue());
                    $kode_customer = trim($worksheet->getCellByColumnAndRow(4, $row)->getValue());
                    $nama_customer = trim($worksheet->getCellByColumnAndRow(5, $row)->getValue());
                    $list_customer = trim($worksheet->getCellByColumnAndRow(6, $row)->getValue());
                    $alamat_customer = trim($worksheet->getCellByColumnAndRow(7, $row)->getValue());
                    $kode_kecamatan = trim($worksheet->getCellByColumnAndRow(8, $row)->getValue());
                    $nama_kecamatan = trim($worksheet->getCellByColumnAndRow(9, $row)->getValue());
                    $contact_person = trim($worksheet->getCellByColumnAndRow(10, $row)->getValue());
                    $telf_customer = trim($worksheet->getCellByColumnAndRow(11, $row)->getValue());
                    $no_hp_customer = trim($worksheet->getCellByColumnAndRow(12, $row)->getValue());
                    $top = trim($worksheet->getCellByColumnAndRow(13, $row)->getValue());
                    $limit_piutang = trim($worksheet->getCellByColumnAndRow(14, $row)->getValue());
                    $limit_nota = trim($worksheet->getCellByColumnAndRow(15, $row)->getValue());
                    $tmt_aktif = trim($worksheet->getCellByColumnAndRow(16, $row)->getValue());
                    $tipe_customer = trim($worksheet->getCellByColumnAndRow(17, $row)->getValue());
                    $kategori_customer = trim($worksheet->getCellByColumnAndRow(18, $row)->getValue());
                    $kode_salesman = trim($worksheet->getCellByColumnAndRow(19, $row)->getValue());
                    $bulan_faktur = trim($worksheet->getCellByColumnAndRow(20, $row)->getValue());
                    $tgl_faktur = trim($worksheet->getCellByColumnAndRow(21, $row)->getValue());
                    $tgl_jatuh_tempo = trim($worksheet->getCellByColumnAndRow(22, $row)->getValue());
                    $token_faktur = trim($worksheet->getCellByColumnAndRow(23, $row)->getValue());
                    $no_faktur = trim($worksheet->getCellByColumnAndRow(24, $row)->getValue());
                    $kode_barang = trim($worksheet->getCellByColumnAndRow(25, $row)->getValue());
                    $nama_barang = trim($worksheet->getCellByColumnAndRow(26, $row)->getValue());
                    $qty_faktur = trim($worksheet->getCellByColumnAndRow(27, $row)->getValue());
                    $satuan_faktur = trim($worksheet->getCellByColumnAndRow(28, $row)->getValue());
                    $qty_terbesar = trim($worksheet->getCellByColumnAndRow(29, $row)->getValue());
                    $satuan_terbesar = trim($worksheet->getCellByColumnAndRow(30, $row)->getValue());
                    $qty_terkecil = trim($worksheet->getCellByColumnAndRow(31, $row)->getValue());
                    $satuan_terkecil = trim($worksheet->getCellByColumnAndRow(32, $row)->getValue());
                    $harga_faktur = trim($worksheet->getCellByColumnAndRow(33, $row)->getValue());
                    $harga_sebelum_pot = trim($worksheet->getCellByColumnAndRow(34, $row)->getValue());
                    $disc_1 = trim($worksheet->getCellByColumnAndRow(35, $row)->getValue());
                    $disc_2 = trim($worksheet->getCellByColumnAndRow(36, $row)->getValue());
                    $disc_3 = trim($worksheet->getCellByColumnAndRow(37, $row)->getValue());
                    $disc_4 = trim($worksheet->getCellByColumnAndRow(38, $row)->getValue());
                    $disc_value = trim($worksheet->getCellByColumnAndRow(39, $row)->getValue());
                    $pot_harga = trim($worksheet->getCellByColumnAndRow(40, $row)->getValue());
                    $subtotal_faktur = trim($worksheet->getCellByColumnAndRow(41, $row)->getValue());
                    $dpp = trim($worksheet->getCellByColumnAndRow(42, $row)->getValue());
                    $ppn = trim($worksheet->getCellByColumnAndRow(43, $row)->getValue());
                    $nilai_ppn = trim($worksheet->getCellByColumnAndRow(44, $row)->getValue());
                    $nilai_faktur = trim($worksheet->getCellByColumnAndRow(45, $row)->getValue());
                    $bonus = trim($worksheet->getCellByColumnAndRow(46, $row)->getValue());
                    $direktori = trim($worksheet->getCellByColumnAndRow(47, $row)->getValue());
                    $supplier = trim($worksheet->getCellByColumnAndRow(48, $row)->getValue());
                    $jenis_barang = trim($worksheet->getCellByColumnAndRow(49, $row)->getValue());
                    $kategori_barang = trim($worksheet->getCellByColumnAndRow(50, $row)->getValue());
                    $merk_barang = trim($worksheet->getCellByColumnAndRow(51, $row)->getValue());
                    $sifat_barang = trim($worksheet->getCellByColumnAndRow(52, $row)->getValue());
                    $volume_satuan = trim($worksheet->getCellByColumnAndRow(53, $row)->getValue());
                    $satuan_terkecil = trim($worksheet->getCellByColumnAndRow(54, $row)->getValue());
                    $hpp_fifo = trim($worksheet->getCellByColumnAndRow(55, $row)->getValue());
                    $margin_satuan = trim($worksheet->getCellByColumnAndRow(56, $row)->getValue());
                    $sub_total_margin = trim($worksheet->getCellByColumnAndRow(57, $row)->getValue());
                    $tgl_order = trim($worksheet->getCellByColumnAndRow(58, $row)->getValue());
                    $no_detail_sales_order = trim($worksheet->getCellByColumnAndRow(59, $row)->getValue());
                    $qty_order = trim($worksheet->getCellByColumnAndRow(60, $row)->getValue());
                    $satuan_order = trim($worksheet->getCellByColumnAndRow(61, $row)->getValue());
                    $harga_order = trim($worksheet->getCellByColumnAndRow(62, $row)->getValue());
                    $subtotal_order = trim($worksheet->getCellByColumnAndRow(63, $row)->getValue());
                    $keterangan = trim($worksheet->getCellByColumnAndRow(64, $row)->getValue());
                    $kode_customer_lama = trim($worksheet->getCellByColumnAndRow(65, $row)->getValue());
                    $token_detail_penjualan = trim($worksheet->getCellByColumnAndRow(66, $row)->getValue());

                    $data = [
                        'SCOPE_CABANG' => $scope_cabang,
                        'PROVINSI' => $provinsi,
                        'KAB_KOTA' => $kab_kota,
                        'ZONA' => $zona,
                        'KODE_CUSTOMER' => $kode_customer,
                        'NAMA_CUSTOMER' => $nama_customer,
                        'LIST_CUSTOMER' => $list_customer,
                        'ALAMAT_CUSTOMER' => $alamat_customer,
                        'KODE_KECAMATAN' => $kode_kecamatan,
                        'NAMA_KECAMATAN' => $nama_kecamatan,
                        'CONTACT_PERSON' => $contact_person,
                        'TELF_CUSTOMER' => $telf_customer,
                        'NO_HP_CUSTOMER' => $no_hp_customer,
                        'TOP' => $top,
                        'LIMIT_PIUTANG' => $limit_piutang,
                        'LIMIT_NOTA' => $limit_nota,
                        'TMT_AKTIF' => $tmt_aktif,
                        'TIPE_CUSTOMER' => $tipe_customer,
                        'KATEGORI_CUSTOMER' => $kategori_customer,
                        'KODE_SALESMAN' => $kode_salesman,
                        'BULAN_FAKTUR' => $bulan_faktur,
                        'TGL_FAKTUR' => $tgl_faktur,
                        'TGL_JATUH_TEMPO' => $tgl_jatuh_tempo,
                        'TOKEN_FAKTUR' => $token_faktur,
                        'NO_FAKTUR' => $no_faktur,
                        'KODE_BARANG' => $kode_barang,
                        'NAMA_BARANG' => $nama_barang,
                        'QTY_FAKTUR' => $qty_faktur,
                        'SATUAN_FAKTUR' => $satuan_faktur,
                        'QTY_TERBESAR' => $qty_terbesar,
                        'SATUAN_TERBESAR' => $satuan_terbesar,
                        'QTY_TERKECIL' => $qty_terkecil,
                        'SATUAN_TERKECIL' => $satuan_terkecil,
                        'HARGA_FAKTUR' => $harga_faktur,
                        'HARGA_SEBELUM_POT' => $harga_sebelum_pot,
                        'DISC_1' => $disc_1,
                        'DISC_2' => $disc_2,
                        'DISC_3' => $disc_3,
                        'DISC_4' => $disc_4,
                        'DISC_VALUE' => $disc_value,
                        'POT_HARGA' => $pot_harga,
                        'SUBTOTAL_FAKTUR' => $subtotal_faktur,
                        'DPP' => $dpp,
                        'PPN' => $ppn,
                        'NILAI_PPN' => $nilai_ppn,
                        'NILAI_FAKTUR' => $nilai_faktur,
                        'BONUS' => $bonus,
                        'DIREKTORI' => $direktori,
                        'SUPPLIER' => $supplier,
                        'JENIS_BARANG' => $jenis_barang,
                        'KATEGORI_BARANG' => $kategori_barang,
                        'MERK_BARANG' => $merk_barang,
                        'SIFAT_BARANG' => $sifat_barang,
                        'VOLUME_SATUAN' => $volume_satuan,
                        'SATUAN_TERKECIL' => $satuan_terkecil,
                        'HPP_FIFO' => $hpp_fifo,
                        'MARGIN_SATUAN' => $margin_satuan,
                        'SUB_TOTAL_MARGIN' => $sub_total_margin,
                        'TGL_ORDER' => $tgl_order,
                        'NO_DETAIL_SALES_ORDER' => $no_detail_sales_order,
                        'QTY_ORDER' => $qty_order,
                        'SATUAN_ORDER' => $satuan_order,
                        'HARGA_ORDER' => $harga_order,
                        'SUBTOTAL_ORDER' => $subtotal_order,
                        'KETERANGAN' => $keterangan,
                        'KODE_CUSTOMER_LAMA' => $kode_customer_lama,
                        'TOKEN_DETAIL_PENJUALAN' => $token_detail_penjualan,
                        'SIGNATURE' => $signature,
                        'CREATED_AT' => $created_at,
                        'CREATED_BY' => $this->session->userdata('id')
                    ];

                    $this->db->insert('management_raw.raw_manokwari',$data);

                    $this->db->select('*');
                    $this->db->where("kode_customer", $kode_customer);
                    $query = $this->db->get('management_raw.raw_customer_manokwari');

                    if ($query->num_rows() <= 0) {
                        $this->db->select('kode_customer_mpm');
                        $this->db->order_by('kode_customer_mpm', 'DESC');
                        $query = $this->db->get('management_raw.raw_customer_manokwari', 1);

                        $data2 = [
                            'kode_customer' => $kode_customer,
                            'kode_customer_mpm' => $query->row()->kode_customer_mpm + 1,
                            'nama_customer' => $nama_customer,
                            'alamat_customer' => $alamat_customer,
                            'CREATED_AT' => $created_at,
                            'CREATED_BY' => $this->session->userdata('id')
                        ];
                        $this->db->insert('management_raw.raw_customer_manokwari',$data2);
                    }
                }
            }
        }else{
        
        };

        $get_count = "select count(*) as count from management_raw.raw_manokwari a where a.signature = '$signature'";
        $count = $this->db->query($get_count)->row()->count;
        
        $get_omzet = "select ROUND(sum(a.nilai_faktur),2) as omzet_raw from management_raw.raw_manokwari a where a.signature = '$signature'";
        $omzet_raw = $this->db->query($get_omzet)->row()->omzet_raw;

        $upload = [
            'site_code'     => 'MWRW6',
            'filename'      => $filename,
            'signature'     => $signature,
            'count_raw'     => $count,
            'omzet_raw'     => $omzet_raw,
            'type_file'     => 'raw_sales',
            'bulan'         => $bulan_from_input,
            'tahun'         => $tahun_from_input,
            'created_at'    => $created_at,
            'created_by'    => $this->session->userdata('id')
        ];
        $this->db->insert('management_raw.log_upload', $upload);
        redirect('management_raw/import_manokwari_draft/'.$signature);
    }

    public function import_manokwari_draft($signature){

        $get_omzet = "select ROUND(sum(a.nilai_faktur),2) as omzet_raw from management_raw.raw_manokwari a where a.signature = '$signature'";
        $omzet_raw = $this->db->query($get_omzet)->row()->omzet_raw;
        
        $data = [
            'title' => 'Management Raw / Preview Import data manokwari',
            'id' => $this->session->userdata('id'),
            'get_raw_draft' => $this->model_management_raw->get_raw_draft_manokwari('MWRW6', $signature),
            'omzet_raw' => $omzet_raw,
            'url' => 'management_raw/proses_mapping_manokwari',
            'signature' => $signature
        ];
        $this->load->view('management_raw/header');
        $this->load->view('management_raw/draft_manokwari', $data);
        $this->load->view('mes/footer');
    }

    public function proses_mapping_manokwari(){

        $signature = $this->input->post('signature');
        $status_closing = $this->input->post('status_closing');
        $id_imports = $this->input->post('options');

        $update_kodeproduk = $this->model_management_raw->update_kodeproduk_manokwari('MWRW6', $signature);
        if ($update_kodeproduk) {   
            echo "<br><center><i>sukses menambahkan angka 0 di kodeproduk ... </i></b><br>";
        }

        $inner_kodeproduk = $this->model_management_raw->inner_kodeproduk_manokwari('MWRW6', $signature);
        if ($inner_kodeproduk) {
            echo "<br><center><i>sukses memfilter produk khusus mpm / membuang produk selain mpm ...</i></b><br>";
        }

        $update_namaprod = $this->model_management_raw->update_namaprod_manokwari('MWRW6', $signature);
        if ($update_namaprod) {
            echo "<br><center><i>sukses mengupdate namaproduk ...</i></b><br>";
        }

        $update_branch = $this->model_management_raw->update_branch('MWRW6', $signature);
        if ($update_branch) {
            echo "<br><center><i>sukses mengupdate branch_name dan nama_comp ...</i></b><br>";
        }

        $update_tanggal = $this->model_management_raw->update_tanggal_manokwari('MWRW6', $signature);                       
        if ($update_tanggal) {
            echo "<br><center><i>sukses memperbaiki format tanggal ...</i></b><br>";
        }

        $update_inner_customer_id_manokwari = $this->model_management_raw->update_inner_customer_id_manokwari($signature);
        if ($update_inner_customer_id_manokwari) {
            echo "<br><center><i>updating customer_id, nama_customer, alamat done ...</i></b><br>";
        }
        
        $delete_tabel = $this->model_management_raw->delete_tabel('MWRW6', $signature);
        if ($delete_tabel) {
            echo "<br><center><i>delete tabel done ...</i></b><br>";
        }

        $insert_fi = $this->model_management_raw->insert_fi_manokwari('MWRW6', $signature);
        if ($insert_fi) {
            echo "<br><center><i>insert fi (sales) done ...</i></b><br>";
        }

        $insert_ri = $this->model_management_raw->insert_ri_manokwari('MWRW6', $signature);
        if ($insert_ri) {
            echo "<br><center><i>insert ri (retur) done ...</i></b><br>";
        }

        // die;

        $insert_tblang = $this->model_management_raw->insert_tblang_kendari('MWRW6', $signature);
        if ($insert_tblang) {
            echo "<br><center><i>insert tblang done ...</i></b><br>";
        }

        $insert_tabsales = $this->model_management_raw->insert_tabsales_kendari('MWRW6', $signature);
        if ($insert_tabsales) {
            echo "<br><center><i>insert tabsales done ...</i></b><br>";
        }

        $insert_tbkota = $this->model_management_raw->insert_tbkota_manokwari('MWRW6', $signature);
        if ($insert_tbkota) {
            echo "<br><center><i>insert tbkota done ...</i></b><br>";
        }        

        $get_count = "select count(*) as count from management_raw.inner_raw_manokwari a where a.signature = '$signature'";
        $count = $this->db->query($get_count)->row()->count;

        $get_bulan = $this->model_management_raw->get_log_upload_by_signature($signature)->row()->bulan;
        $get_tahun = $this->model_management_raw->get_log_upload_by_signature($signature)->row()->tahun;

        $get_omzet = $this->model_management_raw->get_omzet_web($get_tahun, $get_bulan, 'MWRW6')->row()->omzet;

        $update = [
            'count_mapping' => $count,
            'omzet_web'     => $get_omzet,
        ];
        $this->db->where('signature', $signature);
        $this->db->update('management_raw.log_upload', $update);

        $insert_mpm_upload = $this->model_management_raw->insert_mpm_upload_manokwari('MWRW6', $signature, $get_omzet, $status_closing);
        if ($insert_mpm_upload) {
            echo "<br><center><i>insert mpm.upload done ...</i></b><br>";
        }
        
        echo "<br><center><i>dalam 5 detik anda akan di redirect ke halaman awal ...</i></b><br>";
        header('Refresh: 5; URL='.base_url().'management_raw/manokwari');    
    }

    public function template_palu(){        
        $query = "
            SELECT '' as 'No', '' as 'ID Invoice', '' as 'Tanggal', '' as 'Customer ID', '' as 'Customer', '' as 'Territory',
            '' as 'Alamat', '' as 'NPWP', '' as 'NIK', '' as 'CHANNAME', '' as 'Salesman', '' as 'Nama Barang', '' as 'Brand',
            '' as 'Kelompok Barang', '' as 'UOM', '' as 'DUS', '' as 'PCS', '' as 'Harga', '' as 'DISC1', '' as 'DISC2',
            '' as 'TOTAL ', '' as 'DISC3', '' as 'TOTAL 1', '' as 'Retur DUS', '' as 'Retur PCS', '' as 'Retur Harga',
            '' as 'Retur DISC1', '' as 'Retur DISC2', '' as 'Retur TOTAL', '' as 'Retur DISC3', '' as 'TOTAL 2',
            '' as 'GRAND TOTAL', '' as 'Item Code', '' as 'QTY', '' as 'noo'

        ";

        $hsl = $this->db->query($query);
        query_to_csv($hsl,TRUE,'Template_palu.csv');
    }

    public function palu(){

        $data = [
            'title' => 'Management Raw / Import data palu',
            'id' => $this->session->userdata('id'),
            'url_import' => 'management_raw/import_palu',
            'get_log_upload' => $this->model_management_raw->get_log_upload('BLGW4')
        ];
        $this->load->view('management_raw/header');
        $this->load->view('management_raw/palu', $data);
        $this->load->view('mes/footer');
    }

    public function import_palu(){
        $bulan_from_input = substr($this->input->post('bulan'), 5, 2);
        $tahun_from_input = substr($this->input->post('bulan'), 0, 4);

        $cek_last_upload = "
            select *
            from mpm.upload a 
            where a.userid = 550 
            ORDER BY a.id desc
        ";
        $tahun_last_upload = $this->db->query($cek_last_upload)->row()->tahun;
        $bulan_last_upload = $this->db->query($cek_last_upload)->row()->bulan;
        $closing_last_upload = $this->db->query($cek_last_upload)->row()->status_closing;
        $id_upload = $this->db->query($cek_last_upload)->row()->id;

        // echo $closing_last_upload;

        // die;

        if ($closing_last_upload == 1) {
            $tanggal_seharusnya = date('Y-m-d', strtotime('+1 month', strtotime($tahun_last_upload.'-'.$bulan_last_upload.'-01')));           
            $bulan_seharusnya = date('m', strtotime($tanggal_seharusnya));
            $tahun_seharusnya = date('Y', strtotime($tanggal_seharusnya));

            if ($tahun_from_input === $tahun_seharusnya && $bulan_from_input === $bulan_seharusnya) {
                
            }else{
                echo "<br><hr>Tanggal yang anda pilih adalah :<br><br>";
                // echo "<hr>";
                echo "tahun : ".$tahun_from_input."<br>";
                echo "bulan : ".$bulan_from_input."<br><br>";
                echo "<hr>";

                echo "<br>Tanggal upload terakhir adalah :<br><br>";
                // echo "<hr>";
                echo "tahun_last_upload : ".$tahun_last_upload."<br>";
                echo "bulan_last_upload : ".$bulan_last_upload."<br>";
                echo "closing_last_upload : ".$closing_last_upload."<br><br>";
                echo "<hr>";
                
                echo "<h3>Result</h3>";
                echo "Data ditolak !!<br><br>";
                echo "Anda hanya diijinkan mengupload data untuk <b>tahun : $tahun_seharusnya</b>, dan <b>bulan : $bulan_seharusnya</b><br>";

                echo "<br>anda akan di redirect ke menu awal dalam 10 detik";
                header('Refresh: 10; URL='.base_url().'management_raw/palu');
                die;
            }

        }else{

            $tanggal_seharusnya = date('Y-m-d', strtotime('+0 month', strtotime($tahun_last_upload.'-'.$bulan_last_upload.'-01')));           
            $bulan_seharusnya = date('m', strtotime($tanggal_seharusnya));
            $tahun_seharusnya = date('Y', strtotime($tanggal_seharusnya));

            if ($tahun_from_input === $tahun_seharusnya && $bulan_from_input === $bulan_seharusnya) {
                // echo "diijinkan";
            }else{
                echo "<br><hr>Tanggal yang anda pilih adalah :<br><br>";
                echo "tahun : ".$tahun_from_input."<br>";
                echo "bulan : ".$bulan_from_input."<br><br>";
                echo "<hr>";


                echo "<br>Tanggal upload terakhir adalah :<br><br>";
                echo "tahun_last_upload : ".$tahun_last_upload."<br>";
                echo "bulan_last_upload : ".$bulan_last_upload."<br>";
                echo "closing_last_upload : ".$closing_last_upload."<br><br>";
                echo "<hr>";
                
                echo "<h3>Result</h3>";
                echo "Data ditolak !!<br><br>";
                echo "Anda hanya diijinkan mengupload data untuk <b>tahun : $tahun_seharusnya</b>, dan <b>bulan : $bulan_seharusnya</b><br>";

                // echo "<br>anda akan di redirect ke menu awal dalam 10 detik";
                // header('Refresh: 10; URL='.base_url().'management_raw/palu');

                $update = "
                    update mpm.upload a
                    set a.userid = '550_ex' 
                    where a.userid = 550 and a.id = $id_upload
                ";

                // $proses 

                // echo "id_upload : ".$id_upload;

                // die
            }

        }

        // die;

        if (!is_dir('./assets/uploads/management_raw/import/')) {
            @mkdir('./assets/uploads/management_raw/import/', 0777);
        }

        $this->load->library('upload'); // Load librari upload        
        $config['upload_path'] = './assets/uploads/management_raw/import/';
        // $config['allowed_types'] = 'xls|xlsx|csv';
        $config['allowed_types'] = '*';    
        $config['max_size']  = '2048000';
        $config['overwrite'] = true;
        $config['encrypt_name'] = TRUE;
        $this->upload->initialize($config);

        if ($this->upload->do_upload('file')) 
        {

            $upload_data = $this->upload->data();
            $filename = $upload_data['orig_name'];

            $this->load->library('excel');
            $object = PHPExcel_IOFactory::load("assets/uploads/management_raw/import/$filename");

            $jumlahSheet = $object->getSheetCount();
            if ($jumlahSheet > 1) {
                echo "jumlah_sheet : ".$jumlahSheet;
                echo "<script>alert('upload file gagal karena file mempunyai lebih dari 1 sheet'); </script>";
                redirect('management_raw/palu','refresh');
            }

            $highestColumm = $object->setActiveSheetIndex(0)->getHighestColumn();
            // var_dump($highestColumm);die;
            if ($highestColumm != 'AI') {
                echo "<script>alert('upload file gagal karena column tidak sesuai'); </script>";
                redirect('management_raw/palu','refresh');
            }

            // die;

            $created_at = $this->model_outlet_transaksi->timezone();
            $signature = md5($this->model_outlet_transaksi->timezone());

            foreach ($object->getWorksheetIterator() as $worksheet) {

                $highestRow = $worksheet->getHighestRow();
                $highestColumn = $worksheet->getHighestColumn();

                for ($row = 2; $row <= $highestRow; $row++) {

                    $no = trim($worksheet->getCellByColumnAndRow(0, $row)->getValue());
                    $id_invoice = trim($worksheet->getCellByColumnAndRow(1, $row)->getValue());
                    $tanggal = trim($worksheet->getCellByColumnAndRow(2, $row)->getValue());
                    $customer_id = trim($worksheet->getCellByColumnAndRow(3, $row)->getValue());
                    $customer = trim($worksheet->getCellByColumnAndRow(4, $row)->getValue());
                    $territory = trim($worksheet->getCellByColumnAndRow(5, $row)->getValue());
                    $alamat = trim($worksheet->getCellByColumnAndRow(6, $row)->getValue());
                    $npwp = trim($worksheet->getCellByColumnAndRow(7, $row)->getValue());
                    $nik = trim($worksheet->getCellByColumnAndRow(8, $row)->getValue());
                    $channame = trim($worksheet->getCellByColumnAndRow(9, $row)->getValue());
                    $salesman = trim($worksheet->getCellByColumnAndRow(10, $row)->getValue());
                    $nama_barang = trim($worksheet->getCellByColumnAndRow(11, $row)->getValue());
                    $brand = trim($worksheet->getCellByColumnAndRow(12, $row)->getValue());
                    $kelompok_barang = trim($worksheet->getCellByColumnAndRow(13, $row)->getValue());
                    $uom = trim($worksheet->getCellByColumnAndRow(14, $row)->getValue());
                    $dus = trim($worksheet->getCellByColumnAndRow(15, $row)->getValue());
                    $pcs = trim($worksheet->getCellByColumnAndRow(16, $row)->getValue());
                    $harga = trim($worksheet->getCellByColumnAndRow(17, $row)->getValue());
                    $disc1 = trim($worksheet->getCellByColumnAndRow(18, $row)->getValue());
                    $disc2 = trim($worksheet->getCellByColumnAndRow(19, $row)->getValue());
                    $total = trim($worksheet->getCellByColumnAndRow(20, $row)->getValue());
                    $disc3 = trim($worksheet->getCellByColumnAndRow(21, $row)->getValue());
                    $total_1 = trim($worksheet->getCellByColumnAndRow(22, $row)->getValue());
                    $retur_dus = trim($worksheet->getCellByColumnAndRow(23, $row)->getValue());
                    $retur_pcs = trim($worksheet->getCellByColumnAndRow(24, $row)->getValue());
                    $retur_harga = trim($worksheet->getCellByColumnAndRow(25, $row)->getValue());
                    $retur_disc1 = trim($worksheet->getCellByColumnAndRow(26, $row)->getValue());
                    $retur_disc2 = trim($worksheet->getCellByColumnAndRow(27, $row)->getValue());
                    $retur_total = trim($worksheet->getCellByColumnAndRow(28, $row)->getValue());
                    $retur_disc3 = trim($worksheet->getCellByColumnAndRow(29, $row)->getValue());
                    $total_2 = trim($worksheet->getCellByColumnAndRow(30, $row)->getValue());
                    $grand_total = trim($worksheet->getCellByColumnAndRow(31, $row)->getValue());
                    $item_code = trim($worksheet->getCellByColumnAndRow(32, $row)->getValue());
                    $qty = trim($worksheet->getCellByColumnAndRow(33, $row)->getValue());
                    $noo = trim($worksheet->getCellByColumnAndRow(34, $row)->getValue());

                    $data = [
                        'no' => $no,
                        'id_invoice' => $id_invoice,
                        'tanggal' => $tanggal,
                        'customer_id' => $customer_id,
                        'customer' => $customer,
                        'territory' => $territory,
                        'alamat' => $alamat,
                        'npwp' => $npwp,
                        'nik' => $nik,
                        'channame' => $channame,
                        'salesman' => $salesman,
                        'nama_barang' => $nama_barang,
                        'brand' => $brand,
                        'kelompok_barang' => $kelompok_barang,
                        'uom' => $uom,
                        'dus' => $dus,
                        'pcs' => $pcs,
                        'harga' => $harga,
                        'disc1' => $disc1,
                        'disc2' => $disc2,
                        'total' => $total,
                        'disc3' => $disc3,
                        'total_1' => $total_1,
                        'retur_dus' => $retur_dus,
                        'retur_pcs' => $retur_pcs,
                        'retur_harga' => $retur_harga,
                        'retur_disc1' => $retur_disc1,
                        'retur_disc2' => $retur_disc2,
                        'retur_total' => $retur_total,
                        'retur_disc3' => $retur_disc3,
                        'total_2' => $total_2,
                        'grand_total' => $grand_total,
                        'item_code' => $item_code,
                        'qty' => $qty,
                        'noo' => $noo,
                        'SIGNATURE' => $signature,
                        'CREATED_AT' => $created_at,
                        'CREATED_BY' => $this->session->userdata('id')
                    ];

                    $this->db->insert('management_raw.raw_palu',$data);
                }
            }
        }else{
        
        };

        $get_count = "select count(*) as count from management_raw.raw_palu a where a.signature = '$signature'";
        $count = $this->db->query($get_count)->row()->count;
        
        $get_omzet = "select ROUND(sum(a.total),2) as omzet_raw from management_raw.raw_palu a
                        where a.signature = '$signature' and a.total > 100";
        $omzet_raw = $this->db->query($get_omzet)->row()->omzet_raw;

        $upload = [
            'site_code'     => 'BLGW4',
            'filename'      => $filename,
            'signature'     => $signature,
            'count_raw'     => $count,
            'omzet_raw'     => $omzet_raw,
            'type_file'     => 'raw_sales',
            'bulan'         => $bulan_from_input,
            'tahun'         => $tahun_from_input,
            'created_at'    => $created_at,
            'created_by'    => $this->session->userdata('id')
        ];
        $this->db->insert('management_raw.log_upload', $upload);
        redirect('management_raw/import_palu_draft/'.$signature);
    }

    public function import_palu_draft($signature){

        $get_omzet = "select ROUND(sum(a.total),2) as omzet_raw from management_raw.raw_palu a
        where a.signature = '$signature' and a.total > 100";
        $omzet_raw = $this->db->query($get_omzet)->row()->omzet_raw;
        
        $data = [
            'title' => 'Management Raw / Preview Import data palu',
            'id' => $this->session->userdata('id'),
            'get_raw_draft' => $this->model_management_raw->get_raw_draft_palu('BLGW4', $signature),
            'omzet_raw' => $omzet_raw,
            'url' => 'management_raw/proses_mapping_palu',
            'signature' => $signature
        ];
        $this->load->view('management_raw/header');
        $this->load->view('management_raw/draft_palu', $data);
        $this->load->view('mes/footer');
    }

    public function proses_mapping_palu(){

        $signature = $this->input->post('signature');
        $status_closing = $this->input->post('status_closing');
        $id_imports = $this->input->post('options');

        $update_kodeproduk = $this->model_management_raw->update_kodeproduk_palu('BLGW4', $signature);
        if ($update_kodeproduk) {   
            echo "<br><center><i>sukses menambahkan angka 0 di kodeproduk ... </i></b><br>";
        }

        $inner_kodeproduk = $this->model_management_raw->inner_kodeproduk_palu('BLGW4', $signature);
        if ($inner_kodeproduk) {
            echo "<br><center><i>sukses memfilter produk khusus mpm / membuang produk selain mpm ...</i></b><br>";
        }

        $update_namaprod = $this->model_management_raw->update_namaprod_palu('BLGW4', $signature);
        if ($update_namaprod) {
            echo "<br><center><i>sukses mengupdate namaproduk ...</i></b><br>";
        }

        $update_branch = $this->model_management_raw->update_branch('BLGW4', $signature);
        if ($update_branch) {
            echo "<br><center><i>sukses mengupdate branch_name dan nama_comp ...</i></b><br>";
        }

        $update_tanggal = $this->model_management_raw->update_tanggal('BLGW4', $signature);                       
        if ($update_tanggal) {
            echo "<br><center><i>sukses memperbaiki format tanggal ...</i></b><br>";
        }

        $delete_tabel = $this->model_management_raw->delete_tabel('BLGW4', $signature);
        if ($delete_tabel) {
            echo "<br><center><i>delete tabel done ...</i></b><br>";
        }

        $insert_fi = $this->model_management_raw->insert_fi_palu('BLGW4', $signature);
        if ($insert_fi) {
            echo "<br><center><i>insert fi (sales) done ...</i></b><br>";
        }

        $insert_ri = $this->model_management_raw->insert_ri_palu('BLGW4', $signature);
        if ($insert_ri) {
            echo "<br><center><i>insert ri (retur) done ...</i></b><br>";
        }

        // die;

        $insert_tblang = $this->model_management_raw->insert_tblang_kendari('BLGW4', $signature);
        if ($insert_tblang) {
            echo "<br><center><i>insert tblang done ...</i></b><br>";
        }

        $insert_tabsales = $this->model_management_raw->insert_tabsales_kendari('BLGW4', $signature);
        if ($insert_tabsales) {
            echo "<br><center><i>insert tabsales done ...</i></b><br>";
        }

        $insert_tbkota = $this->model_management_raw->insert_tbkota_palu('BLGW4', $signature);
        if ($insert_tbkota) {
            echo "<br><center><i>insert tbkota done ...</i></b><br>";
        }        

        $get_count = "select count(*) as count from management_raw.inner_raw_palu a where a.signature = '$signature'";
        $count = $this->db->query($get_count)->row()->count;

        $get_bulan = $this->model_management_raw->get_log_upload_by_signature($signature)->row()->bulan;
        $get_tahun = $this->model_management_raw->get_log_upload_by_signature($signature)->row()->tahun;

        $get_omzet = $this->model_management_raw->get_omzet_web($get_tahun, $get_bulan, 'BLGW4')->row()->omzet;

        $update = [
            'count_mapping' => $count,
            'omzet_web'     => $get_omzet,
        ];
        $this->db->where('signature', $signature);
        $this->db->update('management_raw.log_upload', $update);

        $insert_mpm_upload = $this->model_management_raw->insert_mpm_upload('BLGW4', $signature, $get_omzet, $status_closing);
        if ($insert_mpm_upload) {
            echo "<br><center><i>insert mpm.upload done ...</i></b><br>";
        }
        
        echo "<br><center><i>dalam 5 detik anda akan di redirect ke halaman awal ...</i></b><br>";
        header('Refresh: 5; URL='.base_url().'management_raw/palu');    
    }

    public function template_vbt(){        
        $query = "
            SELECT '' as Kd_Penjualan, '' as Kd_Pelanggan, '' as DBC, '' as Nama_Pelanggan, '' as Grup, '' as Alamat, '' as Nama_Pelanggan_Ivc,
                    '' as Alamat_Ivc, '' as Nama_Karyawan, '' as Tgl_Order, '' as Monthly, '' as Weekly, '' as Kota, '' as Kecamatan, 
                    '' as Kelurahan, '' as Jenis_Pelanggan, '' as Segment, '' as Channel, '' as Tipe, '' as Category, '' as Principal_Channel,
                    '' as Principal_Channel_Lv1, '' as Principal_Channel_Lv2, '' as S_Kode, '' as Nama_Barang, '' as Grup1, '' as Grup2,
                    '' as Grup3, '' as Alias, '' as Qty_Pcs, '' as Total, '' as Isi, '' as Harga_Satuan_Pcs, '' as DiscountP, '' as DiscountTD,
                    '' as Keterangan, '' as Tgl_Kirim, '' as Kd_Barang, '' as Operation, '' as Principal, '' as Supervisor, '' as Contact_Person,
                    '' as Telpon, '' as HP, '' as Tipe_Transaksi, '' as KTP_NPWP, '' as Principal_Channel_Lv_2_ID, '' as Depo, '' as NoPO
        ";

        $hsl = $this->db->query($query);
        query_to_csv($hsl,TRUE,'Template_vbt.csv');
    }

    public function vbt_makasar(){

        $data = [
            'title' => 'Management Raw / Import data vbt makasar',
            'id' => $this->session->userdata('id'),
            'url_import' => 'management_raw/import_vbt_makasar',
            'get_log_upload' => $this->model_management_raw->get_log_upload('VBTV1')
        ];
        $this->load->view('management_raw/header');
        $this->load->view('management_raw/vbt_makasar', $data);
        $this->load->view('mes/footer');
    }

    public function import_vbt_makasar(){
        $bulan_from_input = substr($this->input->post('bulan'), 5, 2);
        $tahun_from_input = substr($this->input->post('bulan'), 0, 4);

        $cek_last_upload = "
            select *
            from mpm.upload a 
            where a.userid = 607 
            ORDER BY a.id desc
        ";
        $tahun_last_upload = $this->db->query($cek_last_upload)->row()->tahun;
        $bulan_last_upload = $this->db->query($cek_last_upload)->row()->bulan;
        $closing_last_upload = $this->db->query($cek_last_upload)->row()->status_closing;

        if ($closing_last_upload == 1) {
            $tanggal_seharusnya = date('Y-m-d', strtotime('+1 month', strtotime($tahun_last_upload.'-'.$bulan_last_upload.'-01')));           
            $bulan_seharusnya = date('m', strtotime($tanggal_seharusnya));
            $tahun_seharusnya = date('Y', strtotime($tanggal_seharusnya));

            if ($tahun_from_input === $tahun_seharusnya && $bulan_from_input === $bulan_seharusnya) {
                
            }else{
                echo "<br><hr>Tanggal yang anda pilih adalah :<br><br>";
                // echo "<hr>";
                echo "tahun : ".$tahun_from_input."<br>";
                echo "bulan : ".$bulan_from_input."<br><br>";
                echo "<hr>";

                echo "<br>Tanggal upload terakhir adalah :<br><br>";
                // echo "<hr>";
                echo "tahun_last_upload : ".$tahun_last_upload."<br>";
                echo "bulan_last_upload : ".$bulan_last_upload."<br>";
                echo "closing_last_upload : ".$closing_last_upload."<br><br>";
                echo "<hr>";
                
                echo "<h3>Result</h3>";
                echo "Data ditolak !!<br><br>";
                echo "Anda hanya diijinkan mengupload data untuk <b>tahun : $tahun_seharusnya</b>, dan <b>bulan : $bulan_seharusnya</b><br>";

                echo "<br>anda akan di redirect ke menu awal dalam 10 detik";
                header('Refresh: 10; URL='.base_url().'management_raw/vbt_makasar');
                die;
            }

        }else{

            $tanggal_seharusnya = date('Y-m-d', strtotime('+0 month', strtotime($tahun_last_upload.'-'.$bulan_last_upload.'-01')));           
            $bulan_seharusnya = date('m', strtotime($tanggal_seharusnya));
            $tahun_seharusnya = date('Y', strtotime($tanggal_seharusnya));

            if ($tahun_from_input === $tahun_seharusnya && $bulan_from_input === $bulan_seharusnya) {
                // echo "diijinkan";
            }else{
                echo "<br><hr>Tanggal yang anda pilih adalah :<br><br>";
                echo "tahun : ".$tahun_from_input."<br>";
                echo "bulan : ".$bulan_from_input."<br><br>";
                echo "<hr>";


                echo "<br>Tanggal upload terakhir adalah :<br><br>";
                echo "tahun_last_upload : ".$tahun_last_upload."<br>";
                echo "bulan_last_upload : ".$bulan_last_upload."<br>";
                echo "closing_last_upload : ".$closing_last_upload."<br><br>";
                echo "<hr>";
                
                echo "<h3>Result</h3>";
                echo "Data ditolak !!<br><br>";
                echo "Anda hanya diijinkan mengupload data untuk <b>tahun : $tahun_seharusnya</b>, dan <b>bulan : $bulan_seharusnya</b><br>";

                echo "<br>anda akan di redirect ke menu awal dalam 10 detik";
                header('Refresh: 10; URL='.base_url().'management_raw/vbt_makasar');
                die;
            }

        }

        // die;

        if (!is_dir('./assets/uploads/management_raw/import/')) {
            @mkdir('./assets/uploads/management_raw/import/', 0777);
        }

        $this->load->library('upload'); // Load librari upload        
        $config['upload_path'] = './assets/uploads/management_raw/import/';
        // $config['allowed_types'] = 'xls|xlsx|csv';
        $config['allowed_types'] = '*';    
        $config['max_size']  = '2048000';
        $config['overwrite'] = true;
        $config['encrypt_name'] = TRUE;
        $this->upload->initialize($config);

        if ($this->upload->do_upload('file')) 
        {

            $upload_data = $this->upload->data();
            $filename = $upload_data['orig_name'];

            $this->load->library('excel');
            $object = PHPExcel_IOFactory::load("assets/uploads/management_raw/import/$filename");

            $jumlahSheet = $object->getSheetCount();
            if ($jumlahSheet > 1) {
                echo "jumlah_sheet : ".$jumlahSheet;
                echo "<script>alert('upload file gagal karena file mempunyai lebih dari 1 sheet'); </script>";
                redirect('management_raw/vbt_makasar','refresh');
            }

            $highestColumm = $object->setActiveSheetIndex(0)->getHighestColumn();
            // var_dump($highestColumm);die;
            if ($highestColumm != 'AW') {
                echo "<script>alert('upload file gagal karena column tidak sesuai'); </script>";
                redirect('management_raw/vbt_makasar','refresh');
            }

            // die;

            $created_at = $this->model_outlet_transaksi->timezone();
            $signature = md5($this->model_outlet_transaksi->timezone());

            foreach ($object->getWorksheetIterator() as $worksheet) {

                $highestRow = $worksheet->getHighestRow();
                $highestColumn = $worksheet->getHighestColumn();

                for ($row = 2; $row <= $highestRow; $row++) {

                    $kd_penjualan = trim($worksheet->getCellByColumnAndRow(0, $row)->getValue());
                    $kd_pelanggan = trim($worksheet->getCellByColumnAndRow(1, $row)->getValue());
                    $dbc = trim($worksheet->getCellByColumnAndRow(2, $row)->getValue());
                    $nama_pelanggan = trim($worksheet->getCellByColumnAndRow(3, $row)->getValue());
                    $grup = trim($worksheet->getCellByColumnAndRow(4, $row)->getValue());
                    $alamat = trim($worksheet->getCellByColumnAndRow(5, $row)->getValue());
                    $nama_pelanggan_ivc = trim($worksheet->getCellByColumnAndRow(6, $row)->getValue());
                    $alamat_ivc = trim($worksheet->getCellByColumnAndRow(7, $row)->getValue());
                    $nama_karyawan = trim($worksheet->getCellByColumnAndRow(8, $row)->getValue());
                    $tgl_order = trim($worksheet->getCellByColumnAndRow(9, $row)->getValue());
                    $monthly = trim($worksheet->getCellByColumnAndRow(10, $row)->getValue());
                    $weekly = trim($worksheet->getCellByColumnAndRow(11, $row)->getValue());
                    $kota = trim($worksheet->getCellByColumnAndRow(12, $row)->getValue());
                    $kecamatan = trim($worksheet->getCellByColumnAndRow(13, $row)->getValue());
                    $kelurahan = trim($worksheet->getCellByColumnAndRow(14, $row)->getValue());
                    $jenis_pelanggan = trim($worksheet->getCellByColumnAndRow(15, $row)->getValue());
                    $segment = trim($worksheet->getCellByColumnAndRow(16, $row)->getValue());
                    $channel = trim($worksheet->getCellByColumnAndRow(17, $row)->getValue());
                    $tipe = trim($worksheet->getCellByColumnAndRow(18, $row)->getValue());
                    $category = trim($worksheet->getCellByColumnAndRow(19, $row)->getValue());
                    $principal_channel = trim($worksheet->getCellByColumnAndRow(20, $row)->getValue());
                    $principal_channel_lv1 = trim($worksheet->getCellByColumnAndRow(21, $row)->getValue());
                    $principal_channel_lv2 = trim($worksheet->getCellByColumnAndRow(22, $row)->getValue());
                    $s_kode = trim($worksheet->getCellByColumnAndRow(23, $row)->getValue());
                    $nama_barang = trim($worksheet->getCellByColumnAndRow(24, $row)->getValue());
                    $grup1 = trim($worksheet->getCellByColumnAndRow(25, $row)->getValue());
                    $grup2 = trim($worksheet->getCellByColumnAndRow(26, $row)->getValue());
                    $grup3 = trim($worksheet->getCellByColumnAndRow(27, $row)->getValue());
                    $alias = trim($worksheet->getCellByColumnAndRow(28, $row)->getValue());
                    $qty_pcs = trim($worksheet->getCellByColumnAndRow(29, $row)->getValue());
                    $total = trim($worksheet->getCellByColumnAndRow(30, $row)->getValue());
                    $isi = trim($worksheet->getCellByColumnAndRow(31, $row)->getValue());
                    $harga_satuan_pcs = trim($worksheet->getCellByColumnAndRow(32, $row)->getValue());
                    $discountp = trim($worksheet->getCellByColumnAndRow(33, $row)->getValue());
                    $discounttd = trim($worksheet->getCellByColumnAndRow(34, $row)->getValue());
                    $keterangan = trim($worksheet->getCellByColumnAndRow(35, $row)->getValue());
                    $tgl_kirim = trim($worksheet->getCellByColumnAndRow(36, $row)->getValue());
                    $kd_barang = trim($worksheet->getCellByColumnAndRow(37, $row)->getValue());
                    $operation = trim($worksheet->getCellByColumnAndRow(38, $row)->getValue());
                    $principal = trim($worksheet->getCellByColumnAndRow(39, $row)->getValue());
                    $supervisor = trim($worksheet->getCellByColumnAndRow(40, $row)->getValue());
                    $contact_person = trim($worksheet->getCellByColumnAndRow(41, $row)->getValue());
                    $telpon = trim($worksheet->getCellByColumnAndRow(42, $row)->getValue());
                    $hp = trim($worksheet->getCellByColumnAndRow(43, $row)->getValue());
                    $tipe_transaksi = trim($worksheet->getCellByColumnAndRow(44, $row)->getValue());
                    $ktp_npwp = trim($worksheet->getCellByColumnAndRow(45, $row)->getValue());
                    $principal_channel_lv_2_id = trim($worksheet->getCellByColumnAndRow(46, $row)->getValue());
                    $depo = trim($worksheet->getCellByColumnAndRow(47, $row)->getValue());
                    $nopo = trim($worksheet->getCellByColumnAndRow(48, $row)->getValue());

                    if (strtolower($depo) == 'viardi') {
                        # khusus data viardi makasar filter by kolom depo 
                        $data = [
                            'kd_penjualan' => $kd_penjualan,
                            'kd_pelanggan' => $kd_pelanggan,
                            'dbc' => $dbc,
                            'nama_pelanggan' => $nama_pelanggan,
                            'grup' => $grup,
                            'alamat' => $alamat,
                            'nama_pelanggan_ivc' => $nama_pelanggan_ivc,
                            'alamat_ivc' => $alamat_ivc,
                            'nama_karyawan' => $nama_karyawan,
                            'tgl_order' => $tgl_order,
                            'monthly' => $monthly,
                            'weekly' => $weekly,
                            'kota' => $kota,
                            'kecamatan' => $kecamatan,
                            'kelurahan' => $kelurahan,
                            'jenis_pelanggan' => $jenis_pelanggan,
                            'segment' => $segment,
                            'channel' => $channel,
                            'tipe' => $tipe,
                            'category' => $category,
                            'principal_channel' => $principal_channel,
                            'principal_channel_lv1' => $principal_channel_lv1,
                            'principal_channel_lv2' => $principal_channel_lv2,
                            's_kode' => $s_kode,
                            'nama_barang' => $nama_barang,
                            'grup1' => $grup1,
                            'grup2' => $grup2,
                            'grup3' => $grup3,
                            'alias' => $alias,
                            'qty_pcs' => $qty_pcs,
                            'total' => $total,
                            'isi' => $isi,
                            'harga_satuan_pcs' => $harga_satuan_pcs,
                            'discountp' => $discountp,
                            'discounttd' => $discounttd,
                            'keterangan' => $keterangan,
                            'tgl_kirim' => $tgl_kirim,
                            'kd_barang' => $kd_barang,
                            'operation' => $operation,
                            'principal' => $principal,
                            'supervisor' => $supervisor,
                            'contact_person' => $contact_person,
                            'telpon' => $telpon,
                            'hp' => $hp,
                            'tipe_transaksi' => $tipe_transaksi,
                            'ktp_npwp' => $ktp_npwp,
                            'principal_channel_lv_2_id' => $principal_channel_lv_2_id,
                            'depo' => $depo,
                            'nopo' => $nopo,
                            'SIGNATURE' => $signature,
                            'CREATED_AT' => $created_at,
                            'CREATED_BY' => $this->session->userdata('id')
                        ];
    
                        $this->db->insert('management_raw.raw_vbt_makasar',$data);
    
                        $kd_salesman = substr($kd_pelanggan,0,3).$nama_karyawan;
                        $this->db->select('*');
                        $this->db->where("site", 'VBTV1');
                        $this->db->where("kode", $kd_salesman);
                        $query = $this->db->get('management_raw.raw_customer_vbt');
    
                        if ($query->num_rows() <= 0) {
    
                            $query = "
                                select a.kode_cust_mpm, substr(a.kode_cust_mpm,2) as urut
                                from management_raw.raw_customer_vbt a 
                                where site = 'VBTV1'
                                ORDER BY a.id desc
                                limit 1
                            ";
    
                            $no_cust_current = $this->db->query($query);
                            if ($no_cust_current->num_rows() > 0) {
                                
                                $params_urut = $no_cust_current->row()->urut + 1;
                                // echo $params_urut;
    
                                if (strlen($params_urut) === 1) {
                                    $generate = "S00$params_urut";
                                }elseif (strlen($params_urut) === 2) {
                                    $generate = "S0$params_urut";
                                }else{
                                    $generate = "S$params_urut";
                                }
                            }else{
                                $generate = "S001";
                            }
    
                            $data2 = [
                                'SITE' => 'VBTV1',
                                'KODE' => $kd_salesman,
                                'KODE_CUST_MPM' => $generate,
                                'CREATED_AT' => $created_at,
                                'CREATED_BY' => $this->session->userdata('id')
                            ];
                            $this->db->insert('management_raw.raw_customer_vbt',$data2);
                        }
                    };
                }
            }
        }else{
        
        };

        $get_count = "select count(*) as count from management_raw.raw_vbt_makasar a where a.signature = '$signature'";
        $count = $this->db->query($get_count)->row()->count;
        
        $get_omzet = "select ROUND(sum(a.total),2) as omzet_raw from management_raw.raw_vbt_makasar a
                        where a.signature = '$signature' ";
        $omzet_raw = $this->db->query($get_omzet)->row()->omzet_raw;

        $upload = [
            'site_code'     => 'VBTV1',
            'filename'      => $filename,
            'signature'     => $signature,
            'count_raw'     => $count,
            'omzet_raw'     => $omzet_raw,
            'type_file'     => 'raw_sales',
            'bulan'         => $bulan_from_input,
            'tahun'         => $tahun_from_input,
            'created_at'    => $created_at,
            'created_by'    => $this->session->userdata('id')
        ];
        $this->db->insert('management_raw.log_upload', $upload);
        redirect('management_raw/import_vbt_makasar_draft/'.$signature);
    }

    public function import_vbt_makasar_draft($signature){

        $get_omzet = "select ROUND(sum(a.total),2) as omzet_raw from management_raw.raw_vbt_makasar a
        where a.signature = '$signature'";
        $omzet_raw = $this->db->query($get_omzet)->row()->omzet_raw;
        
        $data = [
            'title' => 'Management Raw / Preview Import data vbt_makasar',
            'id' => $this->session->userdata('id'),
            'get_raw_draft' => $this->model_management_raw->get_raw_draft_vbt_makasar('VBTV1', $signature),
            'omzet_raw' => $omzet_raw,
            'url' => 'management_raw/proses_mapping_vbt_makasar',
            'signature' => $signature
        ];
        $this->load->view('management_raw/header');
        $this->load->view('management_raw/draft_vbt_makasar', $data);
        $this->load->view('mes/footer');
    }

    public function proses_mapping_vbt_makasar(){

        $signature = $this->input->post('signature');
        $status_closing = $this->input->post('status_closing');
        $id_imports = $this->input->post('options');

        $update_kodeproduk = $this->model_management_raw->update_kodeproduk_vbt_makasar('VBTV1', $signature);
        if ($update_kodeproduk) {   
            echo "<br><center><i>sukses menambahkan angka 0 di kodeproduk ... </i></b><br>";
        }

        $inner_kodeproduk = $this->model_management_raw->inner_kodeproduk_vbt_makasar('VBTV1', $signature);
        if ($inner_kodeproduk) {
            echo "<br><center><i>sukses memfilter produk khusus mpm / membuang produk selain mpm ...</i></b><br>";
        }

        $update_namaprod = $this->model_management_raw->update_namaprod_vbt_makasar('VBTV1', $signature);
        if ($update_namaprod) {
            echo "<br><center><i>sukses mengupdate namaproduk ...</i></b><br>";
        }

        $update_branch = $this->model_management_raw->update_branch('VBTV1', $signature);
        if ($update_branch) {
            echo "<br><center><i>sukses mengupdate branch_name dan nama_comp ...</i></b><br>";
        }

        $update_tanggal = $this->model_management_raw->update_tanggal_vbt_makasar('VBTV1', $signature);                       
        if ($update_tanggal) {
            echo "<br><center><i>sukses memperbaiki format tanggal ...</i></b><br>";
        }

        $delete_tabel = $this->model_management_raw->delete_tabel('VBTV1', $signature);
        if ($delete_tabel) {
            echo "<br><center><i>delete tabel done ...</i></b><br>";
        }

        $insert_fi = $this->model_management_raw->insert_fi_vbt_makasar('VBTV1', $signature);
        if ($insert_fi) {
            echo "<br><center><i>insert fi (sales) done ...</i></b><br>";
        }

        $insert_ri = $this->model_management_raw->insert_ri_vbt_makasar('VBTV1', $signature);
        if ($insert_ri) {
            echo "<br><center><i>insert ri (retur) done ...</i></b><br>";
        }

        // die;

        $insert_tblang = $this->model_management_raw->insert_tblang_kendari('VBTV1', $signature);
        if ($insert_tblang) {
            echo "<br><center><i>insert tblang done ...</i></b><br>";
        }

        $insert_tabsales = $this->model_management_raw->insert_tabsales_kendari('VBTV1', $signature);
        if ($insert_tabsales) {
            echo "<br><center><i>insert tabsales done ...</i></b><br>";
        }

        $insert_tbkota = $this->model_management_raw->insert_tbkota_vbt_makasar('VBTV1', $signature);
        if ($insert_tbkota) {
            echo "<br><center><i>insert tbkota done ...</i></b><br>";
        }        

        $get_count = "select count(*) as count from management_raw.inner_raw_vbt_makasar a where a.signature = '$signature'";
        $count = $this->db->query($get_count)->row()->count;

        $get_bulan = $this->model_management_raw->get_log_upload_by_signature($signature)->row()->bulan;
        $get_tahun = $this->model_management_raw->get_log_upload_by_signature($signature)->row()->tahun;

        $get_omzet = $this->model_management_raw->get_omzet_web($get_tahun, $get_bulan, 'VBTV1')->row()->omzet;

        $update = [
            'count_mapping' => $count,
            'omzet_web'     => $get_omzet,
        ];
        $this->db->where('signature', $signature);
        $this->db->update('management_raw.log_upload', $update);

        $insert_mpm_upload = $this->model_management_raw->insert_mpm_upload_vbt_makasar('VBTV1', $signature, $get_omzet, $status_closing);
        if ($insert_mpm_upload) {
            echo "<br><center><i>insert mpm.upload done ...</i></b><br>";
        }
        
        echo "<br><center><i>dalam 5 detik anda akan di redirect ke halaman awal ...</i></b><br>";
        header('Refresh: 5; URL='.base_url().'management_raw/vbt_makasar');    
    }

    public function vbt_bulukumba(){

        $data = [
            'title' => 'Management Raw / Import data vbt bulukumba',
            'id' => $this->session->userdata('id'),
            'url_import' => 'management_raw/import_vbt_bulukumba',
            'get_log_upload' => $this->model_management_raw->get_log_upload('BKMV5')
        ];
        $this->load->view('management_raw/header');
        $this->load->view('management_raw/vbt_bulukumba', $data);
        $this->load->view('mes/footer');
    }

    public function import_vbt_bulukumba(){
        $bulan_from_input = substr($this->input->post('bulan'), 5, 2);
        $tahun_from_input = substr($this->input->post('bulan'), 0, 4);

        $cek_last_upload = "
            select *
            from mpm.upload a 
            where a.userid = 611 
            ORDER BY a.id desc
        ";
        $tahun_last_upload = $this->db->query($cek_last_upload)->row()->tahun;
        $bulan_last_upload = $this->db->query($cek_last_upload)->row()->bulan;
        $closing_last_upload = $this->db->query($cek_last_upload)->row()->status_closing;

        if ($closing_last_upload == 1) {
            $tanggal_seharusnya = date('Y-m-d', strtotime('+1 month', strtotime($tahun_last_upload.'-'.$bulan_last_upload.'-01')));           
            $bulan_seharusnya = date('m', strtotime($tanggal_seharusnya));
            $tahun_seharusnya = date('Y', strtotime($tanggal_seharusnya));

            if ($tahun_from_input === $tahun_seharusnya && $bulan_from_input === $bulan_seharusnya) {
                
            }else{
                echo "<br><hr>Tanggal yang anda pilih adalah :<br><br>";
                // echo "<hr>";
                echo "tahun : ".$tahun_from_input."<br>";
                echo "bulan : ".$bulan_from_input."<br><br>";
                echo "<hr>";

                echo "<br>Tanggal upload terakhir adalah :<br><br>";
                // echo "<hr>";
                echo "tahun_last_upload : ".$tahun_last_upload."<br>";
                echo "bulan_last_upload : ".$bulan_last_upload."<br>";
                echo "closing_last_upload : ".$closing_last_upload."<br><br>";
                echo "<hr>";
                
                echo "<h3>Result</h3>";
                echo "Data ditolak !!<br><br>";
                echo "Anda hanya diijinkan mengupload data untuk <b>tahun : $tahun_seharusnya</b>, dan <b>bulan : $bulan_seharusnya</b><br>";

                echo "<br>anda akan di redirect ke menu awal dalam 10 detik";
                header('Refresh: 10; URL='.base_url().'management_raw/vbt_bulukumba');
                die;
            }

        }else{

            $tanggal_seharusnya = date('Y-m-d', strtotime('+0 month', strtotime($tahun_last_upload.'-'.$bulan_last_upload.'-01')));           
            $bulan_seharusnya = date('m', strtotime($tanggal_seharusnya));
            $tahun_seharusnya = date('Y', strtotime($tanggal_seharusnya));

            if ($tahun_from_input === $tahun_seharusnya && $bulan_from_input === $bulan_seharusnya) {
                // echo "diijinkan";
            }else{
                echo "<br><hr>Tanggal yang anda pilih adalah :<br><br>";
                echo "tahun : ".$tahun_from_input."<br>";
                echo "bulan : ".$bulan_from_input."<br><br>";
                echo "<hr>";


                echo "<br>Tanggal upload terakhir adalah :<br><br>";
                echo "tahun_last_upload : ".$tahun_last_upload."<br>";
                echo "bulan_last_upload : ".$bulan_last_upload."<br>";
                echo "closing_last_upload : ".$closing_last_upload."<br><br>";
                echo "<hr>";
                
                echo "<h3>Result</h3>";
                echo "Data ditolak !!<br><br>";
                echo "Anda hanya diijinkan mengupload data untuk <b>tahun : $tahun_seharusnya</b>, dan <b>bulan : $bulan_seharusnya</b><br>";

                echo "<br>anda akan di redirect ke menu awal dalam 10 detik";
                header('Refresh: 10; URL='.base_url().'management_raw/vbt_bulukumba');
                die;
            }

        }

        // die;

        if (!is_dir('./assets/uploads/management_raw/import/')) {
            @mkdir('./assets/uploads/management_raw/import/', 0777);
        }

        $this->load->library('upload'); // Load librari upload        
        $config['upload_path'] = './assets/uploads/management_raw/import/';
        // $config['allowed_types'] = 'xls|xlsx|csv';
        $config['allowed_types'] = '*';    
        $config['max_size']  = '2048000';
        $config['overwrite'] = true;
        $config['encrypt_name'] = TRUE;
        $this->upload->initialize($config);

        if ($this->upload->do_upload('file')) 
        {

            $upload_data = $this->upload->data();
            $filename = $upload_data['orig_name'];

            $this->load->library('excel');
            $object = PHPExcel_IOFactory::load("assets/uploads/management_raw/import/$filename");

            $jumlahSheet = $object->getSheetCount();
            if ($jumlahSheet > 1) {
                echo "jumlah_sheet : ".$jumlahSheet;
                echo "<script>alert('upload file gagal karena file mempunyai lebih dari 1 sheet'); </script>";
                redirect('management_raw/vbt_bulukumba','refresh');
            }

            $highestColumm = $object->setActiveSheetIndex(0)->getHighestColumn();
            // var_dump($highestColumm);die;
            if ($highestColumm != 'AW') {
                echo "<script>alert('upload file gagal karena column tidak sesuai'); </script>";
                redirect('management_raw/vbt_bulukumba','refresh');
            }

            // die;

            $created_at = $this->model_outlet_transaksi->timezone();
            $signature = md5($this->model_outlet_transaksi->timezone());

            foreach ($object->getWorksheetIterator() as $worksheet) {

                $highestRow = $worksheet->getHighestRow();
                $highestColumn = $worksheet->getHighestColumn();

                for ($row = 2; $row <= $highestRow; $row++) {

                    $kd_penjualan = trim($worksheet->getCellByColumnAndRow(0, $row)->getValue());
                    $kd_pelanggan = trim($worksheet->getCellByColumnAndRow(1, $row)->getValue());
                    $dbc = trim($worksheet->getCellByColumnAndRow(2, $row)->getValue());
                    $nama_pelanggan = trim($worksheet->getCellByColumnAndRow(3, $row)->getValue());
                    $grup = trim($worksheet->getCellByColumnAndRow(4, $row)->getValue());
                    $alamat = trim($worksheet->getCellByColumnAndRow(5, $row)->getValue());
                    $nama_pelanggan_ivc = trim($worksheet->getCellByColumnAndRow(6, $row)->getValue());
                    $alamat_ivc = trim($worksheet->getCellByColumnAndRow(7, $row)->getValue());
                    $nama_karyawan = trim($worksheet->getCellByColumnAndRow(8, $row)->getValue());
                    $tgl_order = trim($worksheet->getCellByColumnAndRow(9, $row)->getValue());
                    $monthly = trim($worksheet->getCellByColumnAndRow(10, $row)->getValue());
                    $weekly = trim($worksheet->getCellByColumnAndRow(11, $row)->getValue());
                    $kota = trim($worksheet->getCellByColumnAndRow(12, $row)->getValue());
                    $kecamatan = trim($worksheet->getCellByColumnAndRow(13, $row)->getValue());
                    $kelurahan = trim($worksheet->getCellByColumnAndRow(14, $row)->getValue());
                    $jenis_pelanggan = trim($worksheet->getCellByColumnAndRow(15, $row)->getValue());
                    $segment = trim($worksheet->getCellByColumnAndRow(16, $row)->getValue());
                    $channel = trim($worksheet->getCellByColumnAndRow(17, $row)->getValue());
                    $tipe = trim($worksheet->getCellByColumnAndRow(18, $row)->getValue());
                    $category = trim($worksheet->getCellByColumnAndRow(19, $row)->getValue());
                    $principal_channel = trim($worksheet->getCellByColumnAndRow(20, $row)->getValue());
                    $principal_channel_lv1 = trim($worksheet->getCellByColumnAndRow(21, $row)->getValue());
                    $principal_channel_lv2 = trim($worksheet->getCellByColumnAndRow(22, $row)->getValue());
                    $s_kode = trim($worksheet->getCellByColumnAndRow(23, $row)->getValue());
                    $nama_barang = trim($worksheet->getCellByColumnAndRow(24, $row)->getValue());
                    $grup1 = trim($worksheet->getCellByColumnAndRow(25, $row)->getValue());
                    $grup2 = trim($worksheet->getCellByColumnAndRow(26, $row)->getValue());
                    $grup3 = trim($worksheet->getCellByColumnAndRow(27, $row)->getValue());
                    $alias = trim($worksheet->getCellByColumnAndRow(28, $row)->getValue());
                    $qty_pcs = trim($worksheet->getCellByColumnAndRow(29, $row)->getValue());
                    $total = trim($worksheet->getCellByColumnAndRow(30, $row)->getValue());
                    $isi = trim($worksheet->getCellByColumnAndRow(31, $row)->getValue());
                    $harga_satuan_pcs = trim($worksheet->getCellByColumnAndRow(32, $row)->getValue());
                    $discountp = trim($worksheet->getCellByColumnAndRow(33, $row)->getValue());
                    $discounttd = trim($worksheet->getCellByColumnAndRow(34, $row)->getValue());
                    $keterangan = trim($worksheet->getCellByColumnAndRow(35, $row)->getValue());
                    $tgl_kirim = trim($worksheet->getCellByColumnAndRow(36, $row)->getValue());
                    $kd_barang = trim($worksheet->getCellByColumnAndRow(37, $row)->getValue());
                    $operation = trim($worksheet->getCellByColumnAndRow(38, $row)->getValue());
                    $principal = trim($worksheet->getCellByColumnAndRow(39, $row)->getValue());
                    $supervisor = trim($worksheet->getCellByColumnAndRow(40, $row)->getValue());
                    $contact_person = trim($worksheet->getCellByColumnAndRow(41, $row)->getValue());
                    $telpon = trim($worksheet->getCellByColumnAndRow(42, $row)->getValue());
                    $hp = trim($worksheet->getCellByColumnAndRow(43, $row)->getValue());
                    $tipe_transaksi = trim($worksheet->getCellByColumnAndRow(44, $row)->getValue());
                    $ktp_npwp = trim($worksheet->getCellByColumnAndRow(45, $row)->getValue());
                    $principal_channel_lv_2_id = trim($worksheet->getCellByColumnAndRow(46, $row)->getValue());
                    $depo = trim($worksheet->getCellByColumnAndRow(47, $row)->getValue());
                    $nopo = trim($worksheet->getCellByColumnAndRow(48, $row)->getValue());

                    if (strtolower($depo) == 'bulukumba') {
                        # khusus data bulukumba filter by kolom depo
                        $data = [
                            'kd_penjualan' => $kd_penjualan,
                            'kd_pelanggan' => $kd_pelanggan,
                            'dbc' => $dbc,
                            'nama_pelanggan' => $nama_pelanggan,
                            'grup' => $grup,
                            'alamat' => $alamat,
                            'nama_pelanggan_ivc' => $nama_pelanggan_ivc,
                            'alamat_ivc' => $alamat_ivc,
                            'nama_karyawan' => $nama_karyawan,
                            'tgl_order' => $tgl_order,
                            'monthly' => $monthly,
                            'weekly' => $weekly,
                            'kota' => $kota,
                            'kecamatan' => $kecamatan,
                            'kelurahan' => $kelurahan,
                            'jenis_pelanggan' => $jenis_pelanggan,
                            'segment' => $segment,
                            'channel' => $channel,
                            'tipe' => $tipe,
                            'category' => $category,
                            'principal_channel' => $principal_channel,
                            'principal_channel_lv1' => $principal_channel_lv1,
                            'principal_channel_lv2' => $principal_channel_lv2,
                            's_kode' => $s_kode,
                            'nama_barang' => $nama_barang,
                            'grup1' => $grup1,
                            'grup2' => $grup2,
                            'grup3' => $grup3,
                            'alias' => $alias,
                            'qty_pcs' => $qty_pcs,
                            'total' => $total,
                            'isi' => $isi,
                            'harga_satuan_pcs' => $harga_satuan_pcs,
                            'discountp' => $discountp,
                            'discounttd' => $discounttd,
                            'keterangan' => $keterangan,
                            'tgl_kirim' => $tgl_kirim,
                            'kd_barang' => $kd_barang,
                            'operation' => $operation,
                            'principal' => $principal,
                            'supervisor' => $supervisor,
                            'contact_person' => $contact_person,
                            'telpon' => $telpon,
                            'hp' => $hp,
                            'tipe_transaksi' => $tipe_transaksi,
                            'ktp_npwp' => $ktp_npwp,
                            'principal_channel_lv_2_id' => $principal_channel_lv_2_id,
                            'depo' => $depo,
                            'nopo' => $nopo,
                            'SIGNATURE' => $signature,
                            'CREATED_AT' => $created_at,
                            'CREATED_BY' => $this->session->userdata('id')
                        ];
    
                        $this->db->insert('management_raw.raw_vbt_bulukumba',$data);
    
                        $kd_salesman = substr($kd_pelanggan,0,3).$nama_karyawan;
                        $this->db->select('*');
                        $this->db->where("site", 'BKMV5');
                        $this->db->where("kode", $kd_salesman);
                        $query = $this->db->get('management_raw.raw_customer_vbt');
    
                        if ($query->num_rows() <= 0) {
    
                            $query = "
                                select a.kode_cust_mpm, substr(a.kode_cust_mpm,2) as urut
                                from management_raw.raw_customer_vbt a 
                                where site = 'BKMV5'
                                ORDER BY a.id desc
                                limit 1
                            ";
    
                            $no_cust_current = $this->db->query($query);
                            if ($no_cust_current->num_rows() > 0) {
                                
                                $params_urut = $no_cust_current->row()->urut + 1;
                                // echo $params_urut;
    
                                if (strlen($params_urut) === 1) {
                                    $generate = "S00$params_urut";
                                }elseif (strlen($params_urut) === 2) {
                                    $generate = "S0$params_urut";
                                }else{
                                    $generate = "S$params_urut";
                                }
                            }else{
                                $generate = "S001";
                            }
    
                            $data2 = [
                                'SITE' => 'BKMV5',
                                'KODE' => $kd_salesman,
                                'KODE_CUST_MPM' => $generate,
                                'CREATED_AT' => $created_at,
                                'CREATED_BY' => $this->session->userdata('id')
                            ];
                            $this->db->insert('management_raw.raw_customer_vbt',$data2);
                        }
                    }
                }
            }
        }else{
        
        };

        $get_count = "select count(*) as count from management_raw.raw_vbt_bulukumba a where a.signature = '$signature'";
        $count = $this->db->query($get_count)->row()->count;
        
        $get_omzet = "select ROUND(sum(a.total),2) as omzet_raw from management_raw.raw_vbt_bulukumba a
                        where a.signature = '$signature' ";
        $omzet_raw = $this->db->query($get_omzet)->row()->omzet_raw;

        $upload = [
            'site_code'     => 'BKMV5',
            'filename'      => $filename,
            'signature'     => $signature,
            'count_raw'     => $count,
            'omzet_raw'     => $omzet_raw,
            'type_file'     => 'raw_sales',
            'bulan'         => $bulan_from_input,
            'tahun'         => $tahun_from_input,
            'created_at'    => $created_at,
            'created_by'    => $this->session->userdata('id')
        ];
        $this->db->insert('management_raw.log_upload', $upload);
        redirect('management_raw/import_vbt_bulukumba_draft/'.$signature);
    }

    public function import_vbt_bulukumba_draft($signature){

        $get_omzet = "select ROUND(sum(a.total),2) as omzet_raw from management_raw.raw_vbt_bulukumba a
        where a.signature = '$signature'";
        $omzet_raw = $this->db->query($get_omzet)->row()->omzet_raw;
        
        $data = [
            'title' => 'Management Raw / Preview Import data vbt_bulukumba',
            'id' => $this->session->userdata('id'),
            'get_raw_draft' => $this->model_management_raw->get_raw_draft_vbt_makasar('BKMV5', $signature),
            'omzet_raw' => $omzet_raw,
            'url' => 'management_raw/proses_mapping_vbt_bulukumba',
            'signature' => $signature
        ];
        $this->load->view('management_raw/header');
        $this->load->view('management_raw/draft_vbt_bulukumba', $data);
        $this->load->view('mes/footer');
    }

    public function proses_mapping_vbt_bulukumba(){

        $signature = $this->input->post('signature');
        $status_closing = $this->input->post('status_closing');
        $id_imports = $this->input->post('options');

        $update_kodeproduk = $this->model_management_raw->update_kodeproduk_vbt_makasar('BKMV5', $signature);
        if ($update_kodeproduk) {   
            echo "<br><center><i>sukses menambahkan angka 0 di kodeproduk ... </i></b><br>";
        }

        $inner_kodeproduk = $this->model_management_raw->inner_kodeproduk_vbt_makasar('BKMV5', $signature);
        if ($inner_kodeproduk) {
            echo "<br><center><i>sukses memfilter produk khusus mpm / membuang produk selain mpm ...</i></b><br>";
        }

        $update_namaprod = $this->model_management_raw->update_namaprod_vbt_makasar('BKMV5', $signature);
        if ($update_namaprod) {
            echo "<br><center><i>sukses mengupdate namaproduk ...</i></b><br>";
        }

        $update_branch = $this->model_management_raw->update_branch('BKMV5', $signature);
        if ($update_branch) {
            echo "<br><center><i>sukses mengupdate branch_name dan nama_comp ...</i></b><br>";
        }

        $update_tanggal = $this->model_management_raw->update_tanggal_vbt_makasar('BKMV5', $signature);                       
        if ($update_tanggal) {
            echo "<br><center><i>sukses memperbaiki format tanggal ...</i></b><br>";
        }

        $delete_tabel = $this->model_management_raw->delete_tabel('BKMV5', $signature);
        if ($delete_tabel) {
            echo "<br><center><i>delete tabel done ...</i></b><br>";
        }

        $insert_fi = $this->model_management_raw->insert_fi_vbt_bulukumba('BKMV5', $signature);
        if ($insert_fi) {
            echo "<br><center><i>insert fi (sales) done ...</i></b><br>";
        }

        $insert_ri = $this->model_management_raw->insert_ri_vbt_bulukumba('BKMV5', $signature);
        if ($insert_ri) {
            echo "<br><center><i>insert ri (retur) done ...</i></b><br>";
        }

        // die;

        $insert_tblang = $this->model_management_raw->insert_tblang_kendari('BKMV5', $signature);
        if ($insert_tblang) {
            echo "<br><center><i>insert tblang done ...</i></b><br>";
        }

        $insert_tabsales = $this->model_management_raw->insert_tabsales_kendari('BKMV5', $signature);
        if ($insert_tabsales) {
            echo "<br><center><i>insert tabsales done ...</i></b><br>";
        }

        $insert_tbkota = $this->model_management_raw->insert_tbkota_vbt_bulukumba('BKMV5', $signature);
        if ($insert_tbkota) {
            echo "<br><center><i>insert tbkota done ...</i></b><br>";
        }        

        $get_count = "select count(*) as count from management_raw.inner_raw_vbt_bulukumba a where a.signature = '$signature'";
        $count = $this->db->query($get_count)->row()->count;

        $get_bulan = $this->model_management_raw->get_log_upload_by_signature($signature)->row()->bulan;
        $get_tahun = $this->model_management_raw->get_log_upload_by_signature($signature)->row()->tahun;

        $get_omzet = $this->model_management_raw->get_omzet_web($get_tahun, $get_bulan, 'BKMV5')->row()->omzet;

        $update = [
            'count_mapping' => $count,
            'omzet_web'     => $get_omzet,
        ];
        $this->db->where('signature', $signature);
        $this->db->update('management_raw.log_upload', $update);

        $insert_mpm_upload = $this->model_management_raw->insert_mpm_upload_vbt_makasar('BKMV5', $signature, $get_omzet, $status_closing);
        if ($insert_mpm_upload) {
            echo "<br><center><i>insert mpm.upload done ...</i></b><br>";
        }
        
        echo "<br><center><i>dalam 5 detik anda akan di redirect ke halaman awal ...</i></b><br>";
        header('Refresh: 5; URL='.base_url().'management_raw/vbt_bulukumba');    
    }

    public function vbt_bone(){

        $data = [
            'title' => 'Management Raw / Import data vbt bone',
            'id' => $this->session->userdata('id'),
            'url_import' => 'management_raw/import_vbt_bone',
            'get_log_upload' => $this->model_management_raw->get_log_upload('BONV4')
        ];
        $this->load->view('management_raw/header');
        $this->load->view('management_raw/vbt_bone', $data);
        $this->load->view('mes/footer');
    }

    public function import_vbt_bone(){
        $bulan_from_input = substr($this->input->post('bulan'), 5, 2);
        $tahun_from_input = substr($this->input->post('bulan'), 0, 4);

        $cek_last_upload = "
            select *
            from mpm.upload a 
            where a.userid = 610 
            ORDER BY a.id desc
        ";
        $tahun_last_upload = $this->db->query($cek_last_upload)->row()->tahun;
        $bulan_last_upload = $this->db->query($cek_last_upload)->row()->bulan;
        $closing_last_upload = $this->db->query($cek_last_upload)->row()->status_closing;

        if ($closing_last_upload == 1) {
            $tanggal_seharusnya = date('Y-m-d', strtotime('+1 month', strtotime($tahun_last_upload.'-'.$bulan_last_upload.'-01')));           
            $bulan_seharusnya = date('m', strtotime($tanggal_seharusnya));
            $tahun_seharusnya = date('Y', strtotime($tanggal_seharusnya));

            if ($tahun_from_input === $tahun_seharusnya && $bulan_from_input === $bulan_seharusnya) {
                
            }else{
                echo "<br><hr>Tanggal yang anda pilih adalah :<br><br>";
                // echo "<hr>";
                echo "tahun : ".$tahun_from_input."<br>";
                echo "bulan : ".$bulan_from_input."<br><br>";
                echo "<hr>";

                echo "<br>Tanggal upload terakhir adalah :<br><br>";
                // echo "<hr>";
                echo "tahun_last_upload : ".$tahun_last_upload."<br>";
                echo "bulan_last_upload : ".$bulan_last_upload."<br>";
                echo "closing_last_upload : ".$closing_last_upload."<br><br>";
                echo "<hr>";
                
                echo "<h3>Result</h3>";
                echo "Data ditolak !!<br><br>";
                echo "Anda hanya diijinkan mengupload data untuk <b>tahun : $tahun_seharusnya</b>, dan <b>bulan : $bulan_seharusnya</b><br>";

                echo "<br>anda akan di redirect ke menu awal dalam 10 detik";
                header('Refresh: 10; URL='.base_url().'management_raw/vbt_bone');
                die;
            }

        }else{

            $tanggal_seharusnya = date('Y-m-d', strtotime('+0 month', strtotime($tahun_last_upload.'-'.$bulan_last_upload.'-01')));           
            $bulan_seharusnya = date('m', strtotime($tanggal_seharusnya));
            $tahun_seharusnya = date('Y', strtotime($tanggal_seharusnya));

            if ($tahun_from_input === $tahun_seharusnya && $bulan_from_input === $bulan_seharusnya) {
                // echo "diijinkan";
            }else{
                echo "<br><hr>Tanggal yang anda pilih adalah :<br><br>";
                echo "tahun : ".$tahun_from_input."<br>";
                echo "bulan : ".$bulan_from_input."<br><br>";
                echo "<hr>";


                echo "<br>Tanggal upload terakhir adalah :<br><br>";
                echo "tahun_last_upload : ".$tahun_last_upload."<br>";
                echo "bulan_last_upload : ".$bulan_last_upload."<br>";
                echo "closing_last_upload : ".$closing_last_upload."<br><br>";
                echo "<hr>";
                
                echo "<h3>Result</h3>";
                echo "Data ditolak !!<br><br>";
                echo "Anda hanya diijinkan mengupload data untuk <b>tahun : $tahun_seharusnya</b>, dan <b>bulan : $bulan_seharusnya</b><br>";

                echo "<br>anda akan di redirect ke menu awal dalam 10 detik";
                header('Refresh: 10; URL='.base_url().'management_raw/vbt_bone');
                die;
            }

        }

        // die;

        if (!is_dir('./assets/uploads/management_raw/import/')) {
            @mkdir('./assets/uploads/management_raw/import/', 0777);
        }

        $this->load->library('upload'); // Load librari upload        
        $config['upload_path'] = './assets/uploads/management_raw/import/';
        // $config['allowed_types'] = 'xls|xlsx|csv';
        $config['allowed_types'] = '*';    
        $config['max_size']  = '2048000';
        $config['overwrite'] = true;
        $config['encrypt_name'] = TRUE;
        $this->upload->initialize($config);

        if ($this->upload->do_upload('file')) 
        {

            $upload_data = $this->upload->data();
            $filename = $upload_data['orig_name'];

            $this->load->library('excel');
            $object = PHPExcel_IOFactory::load("assets/uploads/management_raw/import/$filename");

            $jumlahSheet = $object->getSheetCount();
            if ($jumlahSheet > 1) {
                echo "jumlah_sheet : ".$jumlahSheet;
                echo "<script>alert('upload file gagal karena file mempunyai lebih dari 1 sheet'); </script>";
                redirect('management_raw/vbt_bone','refresh');
            }

            $highestColumm = $object->setActiveSheetIndex(0)->getHighestColumn();
            // var_dump($highestColumm);die;
            if ($highestColumm != 'AW') {
                echo "<script>alert('upload file gagal karena column tidak sesuai'); </script>";
                redirect('management_raw/vbt_bone','refresh');
            }

            // die;

            $created_at = $this->model_outlet_transaksi->timezone();
            $signature = md5($this->model_outlet_transaksi->timezone());

            foreach ($object->getWorksheetIterator() as $worksheet) {

                $highestRow = $worksheet->getHighestRow();
                $highestColumn = $worksheet->getHighestColumn();

                for ($row = 2; $row <= $highestRow; $row++) {

                    $kd_penjualan = trim($worksheet->getCellByColumnAndRow(0, $row)->getValue());
                    $kd_pelanggan = trim($worksheet->getCellByColumnAndRow(1, $row)->getValue());
                    $dbc = trim($worksheet->getCellByColumnAndRow(2, $row)->getValue());
                    $nama_pelanggan = trim($worksheet->getCellByColumnAndRow(3, $row)->getValue());
                    $grup = trim($worksheet->getCellByColumnAndRow(4, $row)->getValue());
                    $alamat = trim($worksheet->getCellByColumnAndRow(5, $row)->getValue());
                    $nama_pelanggan_ivc = trim($worksheet->getCellByColumnAndRow(6, $row)->getValue());
                    $alamat_ivc = trim($worksheet->getCellByColumnAndRow(7, $row)->getValue());
                    $nama_karyawan = trim($worksheet->getCellByColumnAndRow(8, $row)->getValue());
                    $tgl_order = trim($worksheet->getCellByColumnAndRow(9, $row)->getValue());
                    $monthly = trim($worksheet->getCellByColumnAndRow(10, $row)->getValue());
                    $weekly = trim($worksheet->getCellByColumnAndRow(11, $row)->getValue());
                    $kota = trim($worksheet->getCellByColumnAndRow(12, $row)->getValue());
                    $kecamatan = trim($worksheet->getCellByColumnAndRow(13, $row)->getValue());
                    $kelurahan = trim($worksheet->getCellByColumnAndRow(14, $row)->getValue());
                    $jenis_pelanggan = trim($worksheet->getCellByColumnAndRow(15, $row)->getValue());
                    $segment = trim($worksheet->getCellByColumnAndRow(16, $row)->getValue());
                    $channel = trim($worksheet->getCellByColumnAndRow(17, $row)->getValue());
                    $tipe = trim($worksheet->getCellByColumnAndRow(18, $row)->getValue());
                    $category = trim($worksheet->getCellByColumnAndRow(19, $row)->getValue());
                    $principal_channel = trim($worksheet->getCellByColumnAndRow(20, $row)->getValue());
                    $principal_channel_lv1 = trim($worksheet->getCellByColumnAndRow(21, $row)->getValue());
                    $principal_channel_lv2 = trim($worksheet->getCellByColumnAndRow(22, $row)->getValue());
                    $s_kode = trim($worksheet->getCellByColumnAndRow(23, $row)->getValue());
                    $nama_barang = trim($worksheet->getCellByColumnAndRow(24, $row)->getValue());
                    $grup1 = trim($worksheet->getCellByColumnAndRow(25, $row)->getValue());
                    $grup2 = trim($worksheet->getCellByColumnAndRow(26, $row)->getValue());
                    $grup3 = trim($worksheet->getCellByColumnAndRow(27, $row)->getValue());
                    $alias = trim($worksheet->getCellByColumnAndRow(28, $row)->getValue());
                    $qty_pcs = trim($worksheet->getCellByColumnAndRow(29, $row)->getValue());
                    $total = trim($worksheet->getCellByColumnAndRow(30, $row)->getValue());
                    $isi = trim($worksheet->getCellByColumnAndRow(31, $row)->getValue());
                    $harga_satuan_pcs = trim($worksheet->getCellByColumnAndRow(32, $row)->getValue());
                    $discountp = trim($worksheet->getCellByColumnAndRow(33, $row)->getValue());
                    $discounttd = trim($worksheet->getCellByColumnAndRow(34, $row)->getValue());
                    $keterangan = trim($worksheet->getCellByColumnAndRow(35, $row)->getValue());
                    $tgl_kirim = trim($worksheet->getCellByColumnAndRow(36, $row)->getValue());
                    $kd_barang = trim($worksheet->getCellByColumnAndRow(37, $row)->getValue());
                    $operation = trim($worksheet->getCellByColumnAndRow(38, $row)->getValue());
                    $principal = trim($worksheet->getCellByColumnAndRow(39, $row)->getValue());
                    $supervisor = trim($worksheet->getCellByColumnAndRow(40, $row)->getValue());
                    $contact_person = trim($worksheet->getCellByColumnAndRow(41, $row)->getValue());
                    $telpon = trim($worksheet->getCellByColumnAndRow(42, $row)->getValue());
                    $hp = trim($worksheet->getCellByColumnAndRow(43, $row)->getValue());
                    $tipe_transaksi = trim($worksheet->getCellByColumnAndRow(44, $row)->getValue());
                    $ktp_npwp = trim($worksheet->getCellByColumnAndRow(45, $row)->getValue());
                    $principal_channel_lv_2_id = trim($worksheet->getCellByColumnAndRow(46, $row)->getValue());
                    $depo = trim($worksheet->getCellByColumnAndRow(47, $row)->getValue());
                    $nopo = trim($worksheet->getCellByColumnAndRow(48, $row)->getValue());

                    if (strtolower($depo) == 'bone') {
                        # khusus data bone filter by kolom depo
                        $data = [
                            'kd_penjualan' => $kd_penjualan,
                            'kd_pelanggan' => $kd_pelanggan,
                            'dbc' => $dbc,
                            'nama_pelanggan' => $nama_pelanggan,
                            'grup' => $grup,
                            'alamat' => $alamat,
                            'nama_pelanggan_ivc' => $nama_pelanggan_ivc,
                            'alamat_ivc' => $alamat_ivc,
                            'nama_karyawan' => $nama_karyawan,
                            'tgl_order' => $tgl_order,
                            'monthly' => $monthly,
                            'weekly' => $weekly,
                            'kota' => $kota,
                            'kecamatan' => $kecamatan,
                            'kelurahan' => $kelurahan,
                            'jenis_pelanggan' => $jenis_pelanggan,
                            'segment' => $segment,
                            'channel' => $channel,
                            'tipe' => $tipe,
                            'category' => $category,
                            'principal_channel' => $principal_channel,
                            'principal_channel_lv1' => $principal_channel_lv1,
                            'principal_channel_lv2' => $principal_channel_lv2,
                            's_kode' => $s_kode,
                            'nama_barang' => $nama_barang,
                            'grup1' => $grup1,
                            'grup2' => $grup2,
                            'grup3' => $grup3,
                            'alias' => $alias,
                            'qty_pcs' => $qty_pcs,
                            'total' => $total,
                            'isi' => $isi,
                            'harga_satuan_pcs' => $harga_satuan_pcs,
                            'discountp' => $discountp,
                            'discounttd' => $discounttd,
                            'keterangan' => $keterangan,
                            'tgl_kirim' => $tgl_kirim,
                            'kd_barang' => $kd_barang,
                            'operation' => $operation,
                            'principal' => $principal,
                            'supervisor' => $supervisor,
                            'contact_person' => $contact_person,
                            'telpon' => $telpon,
                            'hp' => $hp,
                            'tipe_transaksi' => $tipe_transaksi,
                            'ktp_npwp' => $ktp_npwp,
                            'principal_channel_lv_2_id' => $principal_channel_lv_2_id,
                            'depo' => $depo,
                            'nopo' => $nopo,
                            'SIGNATURE' => $signature,
                            'CREATED_AT' => $created_at,
                            'CREATED_BY' => $this->session->userdata('id')
                        ];

                        $this->db->insert('management_raw.raw_vbt_bone',$data);

                        $kd_salesman = substr($kd_pelanggan,0,3).$nama_karyawan;
                        $this->db->select('*');
                        $this->db->where("site", 'BONV4');
                        $this->db->where("kode", $kd_salesman);
                        $query = $this->db->get('management_raw.raw_customer_vbt');

                        if ($query->num_rows() <= 0) {

                            $query = "
                                select a.kode_cust_mpm, substr(a.kode_cust_mpm,2) as urut
                                from management_raw.raw_customer_vbt a 
                                where site = 'BONV4'
                                ORDER BY a.id desc
                                limit 1
                            ";

                            $no_cust_current = $this->db->query($query);
                            if ($no_cust_current->num_rows() > 0) {
                                
                                $params_urut = $no_cust_current->row()->urut + 1;
                                // echo $params_urut;

                                if (strlen($params_urut) === 1) {
                                    $generate = "S00$params_urut";
                                }elseif (strlen($params_urut) === 2) {
                                    $generate = "S0$params_urut";
                                }else{
                                    $generate = "S$params_urut";
                                }
                            }else{
                                $generate = "S001";
                            }

                            $data2 = [
                                'SITE' => 'BONV4',
                                'KODE' => $kd_salesman,
                                'KODE_CUST_MPM' => $generate,
                                'CREATED_AT' => $created_at,
                                'CREATED_BY' => $this->session->userdata('id')
                            ];
                            $this->db->insert('management_raw.raw_customer_vbt',$data2);
                        }
                    }
                }
            }
        }else{
        
        };

        $get_count = "select count(*) as count from management_raw.raw_vbt_bone a where a.signature = '$signature'";
        $count = $this->db->query($get_count)->row()->count;
        
        $get_omzet = "select ROUND(sum(a.total),2) as omzet_raw from management_raw.raw_vbt_bone a
                        where a.signature = '$signature' ";
        $omzet_raw = $this->db->query($get_omzet)->row()->omzet_raw;

        $upload = [
            'site_code'     => 'BONV4',
            'filename'      => $filename,
            'signature'     => $signature,
            'count_raw'     => $count,
            'omzet_raw'     => $omzet_raw,
            'type_file'     => 'raw_sales',
            'bulan'         => $bulan_from_input,
            'tahun'         => $tahun_from_input,
            'created_at'    => $created_at,
            'created_by'    => $this->session->userdata('id')
        ];
        $this->db->insert('management_raw.log_upload', $upload);
        redirect('management_raw/import_vbt_bone_draft/'.$signature);
    }

    public function import_vbt_bone_draft($signature){

        $get_omzet = "select ROUND(sum(a.total),2) as omzet_raw from management_raw.raw_vbt_bone a
        where a.signature = '$signature'";
        $omzet_raw = $this->db->query($get_omzet)->row()->omzet_raw;
        
        $data = [
            'title' => 'Management Raw / Preview Import data vbt_bone',
            'id' => $this->session->userdata('id'),
            'get_raw_draft' => $this->model_management_raw->get_raw_draft_vbt_makasar('BONV4', $signature),
            'omzet_raw' => $omzet_raw,
            'url' => 'management_raw/proses_mapping_vbt_bone',
            'signature' => $signature
        ];
        $this->load->view('management_raw/header');
        $this->load->view('management_raw/draft_vbt_bone', $data);
        $this->load->view('mes/footer');
    }

    public function proses_mapping_vbt_bone(){

        $signature = $this->input->post('signature');
        $status_closing = $this->input->post('status_closing');
        $id_imports = $this->input->post('options');

        $update_kodeproduk = $this->model_management_raw->update_kodeproduk_vbt_makasar('BONV4', $signature);
        if ($update_kodeproduk) {   
            echo "<br><center><i>sukses menambahkan angka 0 di kodeproduk ... </i></b><br>";
        }

        $inner_kodeproduk = $this->model_management_raw->inner_kodeproduk_vbt_makasar('BONV4', $signature);
        if ($inner_kodeproduk) {
            echo "<br><center><i>sukses memfilter produk khusus mpm / membuang produk selain mpm ...</i></b><br>";
        }

        $update_namaprod = $this->model_management_raw->update_namaprod_vbt_makasar('BONV4', $signature);
        if ($update_namaprod) {
            echo "<br><center><i>sukses mengupdate namaproduk ...</i></b><br>";
        }

        $update_branch = $this->model_management_raw->update_branch('BONV4', $signature);
        if ($update_branch) {
            echo "<br><center><i>sukses mengupdate branch_name dan nama_comp ...</i></b><br>";
        }

        $update_tanggal = $this->model_management_raw->update_tanggal_vbt_makasar('BONV4', $signature);                       
        if ($update_tanggal) {
            echo "<br><center><i>sukses memperbaiki format tanggal ...</i></b><br>";
        }

        $delete_tabel = $this->model_management_raw->delete_tabel('BONV4', $signature);
        if ($delete_tabel) {
            echo "<br><center><i>delete tabel done ...</i></b><br>";
        }

        $insert_fi = $this->model_management_raw->insert_fi_vbt_bone('BONV4', $signature);
        if ($insert_fi) {
            echo "<br><center><i>insert fi (sales) done ...</i></b><br>";
        }

        $insert_ri = $this->model_management_raw->insert_ri_vbt_bone('BONV4', $signature);
        if ($insert_ri) {
            echo "<br><center><i>insert ri (retur) done ...</i></b><br>";
        }

        // die;

        $insert_tblang = $this->model_management_raw->insert_tblang_kendari('BONV4', $signature);
        if ($insert_tblang) {
            echo "<br><center><i>insert tblang done ...</i></b><br>";
        }

        $insert_tabsales = $this->model_management_raw->insert_tabsales_kendari('BONV4', $signature);
        if ($insert_tabsales) {
            echo "<br><center><i>insert tabsales done ...</i></b><br>";
        }

        $insert_tbkota = $this->model_management_raw->insert_tbkota_vbt_bone('BONV4', $signature);
        if ($insert_tbkota) {
            echo "<br><center><i>insert tbkota done ...</i></b><br>";
        }        

        $get_count = "select count(*) as count from management_raw.inner_raw_vbt_bone a where a.signature = '$signature'";
        $count = $this->db->query($get_count)->row()->count;

        $get_bulan = $this->model_management_raw->get_log_upload_by_signature($signature)->row()->bulan;
        $get_tahun = $this->model_management_raw->get_log_upload_by_signature($signature)->row()->tahun;

        $get_omzet = $this->model_management_raw->get_omzet_web($get_tahun, $get_bulan, 'BONV4')->row()->omzet;

        $update = [
            'count_mapping' => $count,
            'omzet_web'     => $get_omzet,
        ];
        $this->db->where('signature', $signature);
        $this->db->update('management_raw.log_upload', $update);

        $insert_mpm_upload = $this->model_management_raw->insert_mpm_upload_vbt_makasar('BONV4', $signature, $get_omzet, $status_closing);
        if ($insert_mpm_upload) {
            echo "<br><center><i>insert mpm.upload done ...</i></b><br>";
        }
        
        echo "<br><center><i>dalam 5 detik anda akan di redirect ke halaman awal ...</i></b><br>";
        header('Refresh: 5; URL='.base_url().'management_raw/vbt_bone');    
    }

    public function vbt_palopo(){

        $data = [
            'title' => 'Management Raw / Import data vbt palopo',
            'id' => $this->session->userdata('id'),
            'url_import' => 'management_raw/import_vbt_palopo',
            'get_log_upload' => $this->model_management_raw->get_log_upload('PALV2')
        ];
        $this->load->view('management_raw/header');
        $this->load->view('management_raw/vbt_palopo', $data);
        $this->load->view('mes/footer');
    }

    public function import_vbt_palopo(){
        $bulan_from_input = substr($this->input->post('bulan'), 5, 2);
        $tahun_from_input = substr($this->input->post('bulan'), 0, 4);

        $cek_last_upload = "
            select *
            from mpm.upload a 
            where a.userid = 608 
            ORDER BY a.id desc
        ";
        $tahun_last_upload = $this->db->query($cek_last_upload)->row()->tahun;
        $bulan_last_upload = $this->db->query($cek_last_upload)->row()->bulan;
        $closing_last_upload = $this->db->query($cek_last_upload)->row()->status_closing;

        if ($closing_last_upload == 1) {
            $tanggal_seharusnya = date('Y-m-d', strtotime('+1 month', strtotime($tahun_last_upload.'-'.$bulan_last_upload.'-01')));           
            $bulan_seharusnya = date('m', strtotime($tanggal_seharusnya));
            $tahun_seharusnya = date('Y', strtotime($tanggal_seharusnya));

            if ($tahun_from_input === $tahun_seharusnya && $bulan_from_input === $bulan_seharusnya) {
                
            }else{
                echo "<br><hr>Tanggal yang anda pilih adalah :<br><br>";
                // echo "<hr>";
                echo "tahun : ".$tahun_from_input."<br>";
                echo "bulan : ".$bulan_from_input."<br><br>";
                echo "<hr>";

                echo "<br>Tanggal upload terakhir adalah :<br><br>";
                // echo "<hr>";
                echo "tahun_last_upload : ".$tahun_last_upload."<br>";
                echo "bulan_last_upload : ".$bulan_last_upload."<br>";
                echo "closing_last_upload : ".$closing_last_upload."<br><br>";
                echo "<hr>";
                
                echo "<h3>Result</h3>";
                echo "Data ditolak !!<br><br>";
                echo "Anda hanya diijinkan mengupload data untuk <b>tahun : $tahun_seharusnya</b>, dan <b>bulan : $bulan_seharusnya</b><br>";

                echo "<br>anda akan di redirect ke menu awal dalam 10 detik";
                header('Refresh: 10; URL='.base_url().'management_raw/vbt_palopo');
                die;
            }

        }else{

            $tanggal_seharusnya = date('Y-m-d', strtotime('+0 month', strtotime($tahun_last_upload.'-'.$bulan_last_upload.'-01')));           
            $bulan_seharusnya = date('m', strtotime($tanggal_seharusnya));
            $tahun_seharusnya = date('Y', strtotime($tanggal_seharusnya));

            if ($tahun_from_input === $tahun_seharusnya && $bulan_from_input === $bulan_seharusnya) {
                // echo "diijinkan";
            }else{
                echo "<br><hr>Tanggal yang anda pilih adalah :<br><br>";
                echo "tahun : ".$tahun_from_input."<br>";
                echo "bulan : ".$bulan_from_input."<br><br>";
                echo "<hr>";


                echo "<br>Tanggal upload terakhir adalah :<br><br>";
                echo "tahun_last_upload : ".$tahun_last_upload."<br>";
                echo "bulan_last_upload : ".$bulan_last_upload."<br>";
                echo "closing_last_upload : ".$closing_last_upload."<br><br>";
                echo "<hr>";
                
                echo "<h3>Result</h3>";
                echo "Data ditolak !!<br><br>";
                echo "Anda hanya diijinkan mengupload data untuk <b>tahun : $tahun_seharusnya</b>, dan <b>bulan : $bulan_seharusnya</b><br>";

                echo "<br>anda akan di redirect ke menu awal dalam 10 detik";
                header('Refresh: 10; URL='.base_url().'management_raw/vbt_palopo');
                die;
            }

        }

        // die;

        if (!is_dir('./assets/uploads/management_raw/import/')) {
            @mkdir('./assets/uploads/management_raw/import/', 0777);
        }

        $this->load->library('upload'); // Load librari upload        
        $config['upload_path'] = './assets/uploads/management_raw/import/';
        // $config['allowed_types'] = 'xls|xlsx|csv';
        $config['allowed_types'] = '*';    
        $config['max_size']  = '2048000';
        $config['overwrite'] = true;
        $config['encrypt_name'] = TRUE;
        $this->upload->initialize($config);

        if ($this->upload->do_upload('file')) 
        {

            $upload_data = $this->upload->data();
            $filename = $upload_data['orig_name'];

            $this->load->library('excel');
            $object = PHPExcel_IOFactory::load("assets/uploads/management_raw/import/$filename");

            $jumlahSheet = $object->getSheetCount();
            if ($jumlahSheet > 1) {
                echo "jumlah_sheet : ".$jumlahSheet;
                echo "<script>alert('upload file gagal karena file mempunyai lebih dari 1 sheet'); </script>";
                redirect('management_raw/vbt_palopo','refresh');
            }

            $highestColumm = $object->setActiveSheetIndex(0)->getHighestColumn();
            // var_dump($highestColumm);die;
            if ($highestColumm != 'AW') {
                echo "<script>alert('upload file gagal karena column tidak sesuai'); </script>";
                redirect('management_raw/vbt_palopo','refresh');
            }

            // die;

            $created_at = $this->model_outlet_transaksi->timezone();
            $signature = md5($this->model_outlet_transaksi->timezone());

            foreach ($object->getWorksheetIterator() as $worksheet) {

                $highestRow = $worksheet->getHighestRow();
                $highestColumn = $worksheet->getHighestColumn();

                for ($row = 2; $row <= $highestRow; $row++) {

                    $kd_penjualan = trim($worksheet->getCellByColumnAndRow(0, $row)->getValue());
                    $kd_pelanggan = trim($worksheet->getCellByColumnAndRow(1, $row)->getValue());
                    $dbc = trim($worksheet->getCellByColumnAndRow(2, $row)->getValue());
                    $nama_pelanggan = trim($worksheet->getCellByColumnAndRow(3, $row)->getValue());
                    $grup = trim($worksheet->getCellByColumnAndRow(4, $row)->getValue());
                    $alamat = trim($worksheet->getCellByColumnAndRow(5, $row)->getValue());
                    $nama_pelanggan_ivc = trim($worksheet->getCellByColumnAndRow(6, $row)->getValue());
                    $alamat_ivc = trim($worksheet->getCellByColumnAndRow(7, $row)->getValue());
                    $nama_karyawan = trim($worksheet->getCellByColumnAndRow(8, $row)->getValue());
                    $tgl_order = trim($worksheet->getCellByColumnAndRow(9, $row)->getValue());
                    $monthly = trim($worksheet->getCellByColumnAndRow(10, $row)->getValue());
                    $weekly = trim($worksheet->getCellByColumnAndRow(11, $row)->getValue());
                    $kota = trim($worksheet->getCellByColumnAndRow(12, $row)->getValue());
                    $kecamatan = trim($worksheet->getCellByColumnAndRow(13, $row)->getValue());
                    $kelurahan = trim($worksheet->getCellByColumnAndRow(14, $row)->getValue());
                    $jenis_pelanggan = trim($worksheet->getCellByColumnAndRow(15, $row)->getValue());
                    $segment = trim($worksheet->getCellByColumnAndRow(16, $row)->getValue());
                    $channel = trim($worksheet->getCellByColumnAndRow(17, $row)->getValue());
                    $tipe = trim($worksheet->getCellByColumnAndRow(18, $row)->getValue());
                    $category = trim($worksheet->getCellByColumnAndRow(19, $row)->getValue());
                    $principal_channel = trim($worksheet->getCellByColumnAndRow(20, $row)->getValue());
                    $principal_channel_lv1 = trim($worksheet->getCellByColumnAndRow(21, $row)->getValue());
                    $principal_channel_lv2 = trim($worksheet->getCellByColumnAndRow(22, $row)->getValue());
                    $s_kode = trim($worksheet->getCellByColumnAndRow(23, $row)->getValue());
                    $nama_barang = trim($worksheet->getCellByColumnAndRow(24, $row)->getValue());
                    $grup1 = trim($worksheet->getCellByColumnAndRow(25, $row)->getValue());
                    $grup2 = trim($worksheet->getCellByColumnAndRow(26, $row)->getValue());
                    $grup3 = trim($worksheet->getCellByColumnAndRow(27, $row)->getValue());
                    $alias = trim($worksheet->getCellByColumnAndRow(28, $row)->getValue());
                    $qty_pcs = trim($worksheet->getCellByColumnAndRow(29, $row)->getValue());
                    $total = trim($worksheet->getCellByColumnAndRow(30, $row)->getValue());
                    $isi = trim($worksheet->getCellByColumnAndRow(31, $row)->getValue());
                    $harga_satuan_pcs = trim($worksheet->getCellByColumnAndRow(32, $row)->getValue());
                    $discountp = trim($worksheet->getCellByColumnAndRow(33, $row)->getValue());
                    $discounttd = trim($worksheet->getCellByColumnAndRow(34, $row)->getValue());
                    $keterangan = trim($worksheet->getCellByColumnAndRow(35, $row)->getValue());
                    $tgl_kirim = trim($worksheet->getCellByColumnAndRow(36, $row)->getValue());
                    $kd_barang = trim($worksheet->getCellByColumnAndRow(37, $row)->getValue());
                    $operation = trim($worksheet->getCellByColumnAndRow(38, $row)->getValue());
                    $principal = trim($worksheet->getCellByColumnAndRow(39, $row)->getValue());
                    $supervisor = trim($worksheet->getCellByColumnAndRow(40, $row)->getValue());
                    $contact_person = trim($worksheet->getCellByColumnAndRow(41, $row)->getValue());
                    $telpon = trim($worksheet->getCellByColumnAndRow(42, $row)->getValue());
                    $hp = trim($worksheet->getCellByColumnAndRow(43, $row)->getValue());
                    $tipe_transaksi = trim($worksheet->getCellByColumnAndRow(44, $row)->getValue());
                    $ktp_npwp = trim($worksheet->getCellByColumnAndRow(45, $row)->getValue());
                    $principal_channel_lv_2_id = trim($worksheet->getCellByColumnAndRow(46, $row)->getValue());
                    $depo = trim($worksheet->getCellByColumnAndRow(47, $row)->getValue());
                    $nopo = trim($worksheet->getCellByColumnAndRow(48, $row)->getValue());

                    if (strtolower($depo) == 'palopo') {
                        # khusus data palopo filter by kolom depo
                        $data = [
                            'kd_penjualan' => $kd_penjualan,
                            'kd_pelanggan' => $kd_pelanggan,
                            'dbc' => $dbc,
                            'nama_pelanggan' => $nama_pelanggan,
                            'grup' => $grup,
                            'alamat' => $alamat,
                            'nama_pelanggan_ivc' => $nama_pelanggan_ivc,
                            'alamat_ivc' => $alamat_ivc,
                            'nama_karyawan' => $nama_karyawan,
                            'tgl_order' => $tgl_order,
                            'monthly' => $monthly,
                            'weekly' => $weekly,
                            'kota' => $kota,
                            'kecamatan' => $kecamatan,
                            'kelurahan' => $kelurahan,
                            'jenis_pelanggan' => $jenis_pelanggan,
                            'segment' => $segment,
                            'channel' => $channel,
                            'tipe' => $tipe,
                            'category' => $category,
                            'principal_channel' => $principal_channel,
                            'principal_channel_lv1' => $principal_channel_lv1,
                            'principal_channel_lv2' => $principal_channel_lv2,
                            's_kode' => $s_kode,
                            'nama_barang' => $nama_barang,
                            'grup1' => $grup1,
                            'grup2' => $grup2,
                            'grup3' => $grup3,
                            'alias' => $alias,
                            'qty_pcs' => $qty_pcs,
                            'total' => $total,
                            'isi' => $isi,
                            'harga_satuan_pcs' => $harga_satuan_pcs,
                            'discountp' => $discountp,
                            'discounttd' => $discounttd,
                            'keterangan' => $keterangan,
                            'tgl_kirim' => $tgl_kirim,
                            'kd_barang' => $kd_barang,
                            'operation' => $operation,
                            'principal' => $principal,
                            'supervisor' => $supervisor,
                            'contact_person' => $contact_person,
                            'telpon' => $telpon,
                            'hp' => $hp,
                            'tipe_transaksi' => $tipe_transaksi,
                            'ktp_npwp' => $ktp_npwp,
                            'principal_channel_lv_2_id' => $principal_channel_lv_2_id,
                            'depo' => $depo,
                            'nopo' => $nopo,
                            'SIGNATURE' => $signature,
                            'CREATED_AT' => $created_at,
                            'CREATED_BY' => $this->session->userdata('id')
                        ];
    
                        $this->db->insert('management_raw.raw_vbt_palopo',$data);
    
                        $kd_salesman = substr($kd_pelanggan,0,3).$nama_karyawan;
                        $this->db->select('*');
                        $this->db->where("site", 'PALV2');
                        $this->db->where("kode", $kd_salesman);
                        $query = $this->db->get('management_raw.raw_customer_vbt');
    
                        if ($query->num_rows() <= 0) {
    
                            $query = "
                                select a.kode_cust_mpm, substr(a.kode_cust_mpm,2) as urut
                                from management_raw.raw_customer_vbt a 
                                where site = 'PALV2'
                                ORDER BY a.id desc
                                limit 1
                            ";
    
                            $no_cust_current = $this->db->query($query);
                            if ($no_cust_current->num_rows() > 0) {
                                
                                $params_urut = $no_cust_current->row()->urut + 1;
                                // echo $params_urut;
    
                                if (strlen($params_urut) === 1) {
                                    $generate = "S00$params_urut";
                                }elseif (strlen($params_urut) === 2) {
                                    $generate = "S0$params_urut";
                                }else{
                                    $generate = "S$params_urut";
                                }
                            }else{
                                $generate = "S001";
                            }
    
                            $data2 = [
                                'SITE' => 'PALV2',
                                'KODE' => $kd_salesman,
                                'KODE_CUST_MPM' => $generate,
                                'CREATED_AT' => $created_at,
                                'CREATED_BY' => $this->session->userdata('id')
                            ];
                            $this->db->insert('management_raw.raw_customer_vbt',$data2);
                        }
                    }
                }
            }
        }else{
        
        };

        $get_count = "select count(*) as count from management_raw.raw_vbt_palopo a where a.signature = '$signature'";
        $count = $this->db->query($get_count)->row()->count;
        
        $get_omzet = "select ROUND(sum(a.total),2) as omzet_raw from management_raw.raw_vbt_palopo a
                        where a.signature = '$signature' ";
        $omzet_raw = $this->db->query($get_omzet)->row()->omzet_raw;

        $upload = [
            'site_code'     => 'PALV2',
            'filename'      => $filename,
            'signature'     => $signature,
            'count_raw'     => $count,
            'omzet_raw'     => $omzet_raw,
            'type_file'     => 'raw_sales',
            'bulan'         => $bulan_from_input,
            'tahun'         => $tahun_from_input,
            'created_at'    => $created_at,
            'created_by'    => $this->session->userdata('id')
        ];
        $this->db->insert('management_raw.log_upload', $upload);
        redirect('management_raw/import_vbt_palopo_draft/'.$signature);
    }

    public function import_vbt_palopo_draft($signature){

        $get_omzet = "select ROUND(sum(a.total),2) as omzet_raw from management_raw.raw_vbt_palopo a
        where a.signature = '$signature'";
        $omzet_raw = $this->db->query($get_omzet)->row()->omzet_raw;
        
        $data = [
            'title' => 'Management Raw / Preview Import data vbt_palopo',
            'id' => $this->session->userdata('id'),
            'get_raw_draft' => $this->model_management_raw->get_raw_draft_vbt_makasar('PALV2', $signature),
            'omzet_raw' => $omzet_raw,
            'url' => 'management_raw/proses_mapping_vbt_palopo',
            'signature' => $signature
        ];
        $this->load->view('management_raw/header');
        $this->load->view('management_raw/draft_vbt_palopo', $data);
        $this->load->view('mes/footer');
    }

    public function proses_mapping_vbt_palopo(){

        $signature = $this->input->post('signature');
        $status_closing = $this->input->post('status_closing');
        $id_imports = $this->input->post('options');

        $update_kodeproduk = $this->model_management_raw->update_kodeproduk_vbt_makasar('PALV2', $signature);
        if ($update_kodeproduk) {   
            echo "<br><center><i>sukses menambahkan angka 0 di kodeproduk ... </i></b><br>";
        }

        $inner_kodeproduk = $this->model_management_raw->inner_kodeproduk_vbt_makasar('PALV2', $signature);
        if ($inner_kodeproduk) {
            echo "<br><center><i>sukses memfilter produk khusus mpm / membuang produk selain mpm ...</i></b><br>";
        }

        $update_namaprod = $this->model_management_raw->update_namaprod_vbt_makasar('PALV2', $signature);
        if ($update_namaprod) {
            echo "<br><center><i>sukses mengupdate namaproduk ...</i></b><br>";
        }

        $update_branch = $this->model_management_raw->update_branch('PALV2', $signature);
        if ($update_branch) {
            echo "<br><center><i>sukses mengupdate branch_name dan nama_comp ...</i></b><br>";
        }

        $update_tanggal = $this->model_management_raw->update_tanggal_vbt_makasar('PALV2', $signature);                       
        if ($update_tanggal) {
            echo "<br><center><i>sukses memperbaiki format tanggal ...</i></b><br>";
        }

        $delete_tabel = $this->model_management_raw->delete_tabel('PALV2', $signature);
        if ($delete_tabel) {
            echo "<br><center><i>delete tabel done ...</i></b><br>";
        }

        $insert_fi = $this->model_management_raw->insert_fi_vbt_palopo('PALV2', $signature);
        if ($insert_fi) {
            echo "<br><center><i>insert fi (sales) done ...</i></b><br>";
        }

        $insert_ri = $this->model_management_raw->insert_ri_vbt_palopo('PALV2', $signature);
        if ($insert_ri) {
            echo "<br><center><i>insert ri (retur) done ...</i></b><br>";
        }

        // die;

        $insert_tblang = $this->model_management_raw->insert_tblang_kendari('PALV2', $signature);
        if ($insert_tblang) {
            echo "<br><center><i>insert tblang done ...</i></b><br>";
        }

        $insert_tabsales = $this->model_management_raw->insert_tabsales_kendari('PALV2', $signature);
        if ($insert_tabsales) {
            echo "<br><center><i>insert tabsales done ...</i></b><br>";
        }

        $insert_tbkota = $this->model_management_raw->insert_tbkota_vbt_palopo('PALV2', $signature);
        if ($insert_tbkota) {
            echo "<br><center><i>insert tbkota done ...</i></b><br>";
        }        

        $get_count = "select count(*) as count from management_raw.inner_raw_vbt_palopo a where a.signature = '$signature'";
        $count = $this->db->query($get_count)->row()->count;

        $get_bulan = $this->model_management_raw->get_log_upload_by_signature($signature)->row()->bulan;
        $get_tahun = $this->model_management_raw->get_log_upload_by_signature($signature)->row()->tahun;

        $get_omzet = $this->model_management_raw->get_omzet_web($get_tahun, $get_bulan, 'PALV2')->row()->omzet;

        $update = [
            'count_mapping' => $count,
            'omzet_web'     => $get_omzet,
        ];
        $this->db->where('signature', $signature);
        $this->db->update('management_raw.log_upload', $update);

        $insert_mpm_upload = $this->model_management_raw->insert_mpm_upload_vbt_makasar('PALV2', $signature, $get_omzet, $status_closing);
        if ($insert_mpm_upload) {
            echo "<br><center><i>insert mpm.upload done ...</i></b><br>";
        }
        
        echo "<br><center><i>dalam 5 detik anda akan di redirect ke halaman awal ...</i></b><br>";
        header('Refresh: 5; URL='.base_url().'management_raw/vbt_palopo');    
    }

    public function vbt_pare(){

        $data = [
            'title' => 'Management Raw / Import data vbt pare',
            'id' => $this->session->userdata('id'),
            'url_import' => 'management_raw/import_vbt_pare',
            'get_log_upload' => $this->model_management_raw->get_log_upload('PARV3')
        ];
        $this->load->view('management_raw/header');
        $this->load->view('management_raw/vbt_pare', $data);
        $this->load->view('mes/footer');
    }

    public function import_vbt_pare(){
        $bulan_from_input = substr($this->input->post('bulan'), 5, 2);
        $tahun_from_input = substr($this->input->post('bulan'), 0, 4);

        $cek_last_upload = "
            select *
            from mpm.upload a 
            where a.userid = 609 
            ORDER BY a.id desc
        ";
        $tahun_last_upload = $this->db->query($cek_last_upload)->row()->tahun;
        $bulan_last_upload = $this->db->query($cek_last_upload)->row()->bulan;
        $closing_last_upload = $this->db->query($cek_last_upload)->row()->status_closing;

        if ($closing_last_upload == 1) {
            $tanggal_seharusnya = date('Y-m-d', strtotime('+1 month', strtotime($tahun_last_upload.'-'.$bulan_last_upload.'-01')));           
            $bulan_seharusnya = date('m', strtotime($tanggal_seharusnya));
            $tahun_seharusnya = date('Y', strtotime($tanggal_seharusnya));

            if ($tahun_from_input === $tahun_seharusnya && $bulan_from_input === $bulan_seharusnya) {
                
            }else{
                echo "<br><hr>Tanggal yang anda pilih adalah :<br><br>";
                // echo "<hr>";
                echo "tahun : ".$tahun_from_input."<br>";
                echo "bulan : ".$bulan_from_input."<br><br>";
                echo "<hr>";

                echo "<br>Tanggal upload terakhir adalah :<br><br>";
                // echo "<hr>";
                echo "tahun_last_upload : ".$tahun_last_upload."<br>";
                echo "bulan_last_upload : ".$bulan_last_upload."<br>";
                echo "closing_last_upload : ".$closing_last_upload."<br><br>";
                echo "<hr>";
                
                echo "<h3>Result</h3>";
                echo "Data ditolak !!<br><br>";
                echo "Anda hanya diijinkan mengupload data untuk <b>tahun : $tahun_seharusnya</b>, dan <b>bulan : $bulan_seharusnya</b><br>";

                echo "<br>anda akan di redirect ke menu awal dalam 10 detik";
                header('Refresh: 10; URL='.base_url().'management_raw/vbt_pare');
                die;
            }

        }else{

            $tanggal_seharusnya = date('Y-m-d', strtotime('+0 month', strtotime($tahun_last_upload.'-'.$bulan_last_upload.'-01')));           
            $bulan_seharusnya = date('m', strtotime($tanggal_seharusnya));
            $tahun_seharusnya = date('Y', strtotime($tanggal_seharusnya));

            if ($tahun_from_input === $tahun_seharusnya && $bulan_from_input === $bulan_seharusnya) {
                // echo "diijinkan";
            }else{
                echo "<br><hr>Tanggal yang anda pilih adalah :<br><br>";
                echo "tahun : ".$tahun_from_input."<br>";
                echo "bulan : ".$bulan_from_input."<br><br>";
                echo "<hr>";


                echo "<br>Tanggal upload terakhir adalah :<br><br>";
                echo "tahun_last_upload : ".$tahun_last_upload."<br>";
                echo "bulan_last_upload : ".$bulan_last_upload."<br>";
                echo "closing_last_upload : ".$closing_last_upload."<br><br>";
                echo "<hr>";
                
                echo "<h3>Result</h3>";
                echo "Data ditolak !!<br><br>";
                echo "Anda hanya diijinkan mengupload data untuk <b>tahun : $tahun_seharusnya</b>, dan <b>bulan : $bulan_seharusnya</b><br>";

                echo "<br>anda akan di redirect ke menu awal dalam 10 detik";
                header('Refresh: 10; URL='.base_url().'management_raw/vbt_pare');
                die;
            }

        }

        // die;

        if (!is_dir('./assets/uploads/management_raw/import/')) {
            @mkdir('./assets/uploads/management_raw/import/', 0777);
        }

        $this->load->library('upload'); // Load librari upload        
        $config['upload_path'] = './assets/uploads/management_raw/import/';
        // $config['allowed_types'] = 'xls|xlsx|csv';
        $config['allowed_types'] = '*';    
        $config['max_size']  = '2048000';
        $config['overwrite'] = true;
        $config['encrypt_name'] = TRUE;
        $this->upload->initialize($config);

        if ($this->upload->do_upload('file')) 
        {

            $upload_data = $this->upload->data();
            $filename = $upload_data['orig_name'];

            $this->load->library('excel');
            $object = PHPExcel_IOFactory::load("assets/uploads/management_raw/import/$filename");

            $jumlahSheet = $object->getSheetCount();
            if ($jumlahSheet > 1) {
                echo "jumlah_sheet : ".$jumlahSheet;
                echo "<script>alert('upload file gagal karena file mempunyai lebih dari 1 sheet'); </script>";
                redirect('management_raw/vbt_pare','refresh');
            }

            $highestColumm = $object->setActiveSheetIndex(0)->getHighestColumn();
            // var_dump($highestColumm);die;
            if ($highestColumm != 'AW') {
                echo "<script>alert('upload file gagal karena column tidak sesuai'); </script>";
                redirect('management_raw/vbt_pare','refresh');
            }
            // die;

            $created_at = $this->model_outlet_transaksi->timezone();
            $signature = md5($this->model_outlet_transaksi->timezone());

            foreach ($object->getWorksheetIterator() as $worksheet) {

                $highestRow = $worksheet->getHighestRow();
                $highestColumn = $worksheet->getHighestColumn();

                for ($row = 2; $row <= $highestRow; $row++) {

                    $kd_penjualan = trim($worksheet->getCellByColumnAndRow(0, $row)->getValue());
                    $kd_pelanggan = trim($worksheet->getCellByColumnAndRow(1, $row)->getValue());
                    $dbc = trim($worksheet->getCellByColumnAndRow(2, $row)->getValue());
                    $nama_pelanggan = trim($worksheet->getCellByColumnAndRow(3, $row)->getValue());
                    $grup = trim($worksheet->getCellByColumnAndRow(4, $row)->getValue());
                    $alamat = trim($worksheet->getCellByColumnAndRow(5, $row)->getValue());
                    $nama_pelanggan_ivc = trim($worksheet->getCellByColumnAndRow(6, $row)->getValue());
                    $alamat_ivc = trim($worksheet->getCellByColumnAndRow(7, $row)->getValue());
                    $nama_karyawan = trim($worksheet->getCellByColumnAndRow(8, $row)->getValue());
                    $tgl_order = trim($worksheet->getCellByColumnAndRow(9, $row)->getValue());
                    $monthly = trim($worksheet->getCellByColumnAndRow(10, $row)->getValue());
                    $weekly = trim($worksheet->getCellByColumnAndRow(11, $row)->getValue());
                    $kota = trim($worksheet->getCellByColumnAndRow(12, $row)->getValue());
                    $kecamatan = trim($worksheet->getCellByColumnAndRow(13, $row)->getValue());
                    $kelurahan = trim($worksheet->getCellByColumnAndRow(14, $row)->getValue());
                    $jenis_pelanggan = trim($worksheet->getCellByColumnAndRow(15, $row)->getValue());
                    $segment = trim($worksheet->getCellByColumnAndRow(16, $row)->getValue());
                    $channel = trim($worksheet->getCellByColumnAndRow(17, $row)->getValue());
                    $tipe = trim($worksheet->getCellByColumnAndRow(18, $row)->getValue());
                    $category = trim($worksheet->getCellByColumnAndRow(19, $row)->getValue());
                    $principal_channel = trim($worksheet->getCellByColumnAndRow(20, $row)->getValue());
                    $principal_channel_lv1 = trim($worksheet->getCellByColumnAndRow(21, $row)->getValue());
                    $principal_channel_lv2 = trim($worksheet->getCellByColumnAndRow(22, $row)->getValue());
                    $s_kode = trim($worksheet->getCellByColumnAndRow(23, $row)->getValue());
                    $nama_barang = trim($worksheet->getCellByColumnAndRow(24, $row)->getValue());
                    $grup1 = trim($worksheet->getCellByColumnAndRow(25, $row)->getValue());
                    $grup2 = trim($worksheet->getCellByColumnAndRow(26, $row)->getValue());
                    $grup3 = trim($worksheet->getCellByColumnAndRow(27, $row)->getValue());
                    $alias = trim($worksheet->getCellByColumnAndRow(28, $row)->getValue());
                    $qty_pcs = trim($worksheet->getCellByColumnAndRow(29, $row)->getValue());
                    $total = trim($worksheet->getCellByColumnAndRow(30, $row)->getValue());
                    $isi = trim($worksheet->getCellByColumnAndRow(31, $row)->getValue());
                    $harga_satuan_pcs = trim($worksheet->getCellByColumnAndRow(32, $row)->getValue());
                    $discountp = trim($worksheet->getCellByColumnAndRow(33, $row)->getValue());
                    $discounttd = trim($worksheet->getCellByColumnAndRow(34, $row)->getValue());
                    $keterangan = trim($worksheet->getCellByColumnAndRow(35, $row)->getValue());
                    $tgl_kirim = trim($worksheet->getCellByColumnAndRow(36, $row)->getValue());
                    $kd_barang = trim($worksheet->getCellByColumnAndRow(37, $row)->getValue());
                    $operation = trim($worksheet->getCellByColumnAndRow(38, $row)->getValue());
                    $principal = trim($worksheet->getCellByColumnAndRow(39, $row)->getValue());
                    $supervisor = trim($worksheet->getCellByColumnAndRow(40, $row)->getValue());
                    $contact_person = trim($worksheet->getCellByColumnAndRow(41, $row)->getValue());
                    $telpon = trim($worksheet->getCellByColumnAndRow(42, $row)->getValue());
                    $hp = trim($worksheet->getCellByColumnAndRow(43, $row)->getValue());
                    $tipe_transaksi = trim($worksheet->getCellByColumnAndRow(44, $row)->getValue());
                    $ktp_npwp = trim($worksheet->getCellByColumnAndRow(45, $row)->getValue());
                    $principal_channel_lv_2_id = trim($worksheet->getCellByColumnAndRow(46, $row)->getValue());
                    $depo = trim($worksheet->getCellByColumnAndRow(47, $row)->getValue());
                    $nopo = trim($worksheet->getCellByColumnAndRow(48, $row)->getValue());

                    if (strtolower($depo) == 'parepare') {
                        # khusus data pare-pare filter by kolom depo
                        $data = [
                            'kd_penjualan' => $kd_penjualan,
                            'kd_pelanggan' => $kd_pelanggan,
                            'dbc' => $dbc,
                            'nama_pelanggan' => $nama_pelanggan,
                            'grup' => $grup,
                            'alamat' => $alamat,
                            'nama_pelanggan_ivc' => $nama_pelanggan_ivc,
                            'alamat_ivc' => $alamat_ivc,
                            'nama_karyawan' => $nama_karyawan,
                            'tgl_order' => $tgl_order,
                            'monthly' => $monthly,
                            'weekly' => $weekly,
                            'kota' => $kota,
                            'kecamatan' => $kecamatan,
                            'kelurahan' => $kelurahan,
                            'jenis_pelanggan' => $jenis_pelanggan,
                            'segment' => $segment,
                            'channel' => $channel,
                            'tipe' => $tipe,
                            'category' => $category,
                            'principal_channel' => $principal_channel,
                            'principal_channel_lv1' => $principal_channel_lv1,
                            'principal_channel_lv2' => $principal_channel_lv2,
                            's_kode' => $s_kode,
                            'nama_barang' => $nama_barang,
                            'grup1' => $grup1,
                            'grup2' => $grup2,
                            'grup3' => $grup3,
                            'alias' => $alias,
                            'qty_pcs' => $qty_pcs,
                            'total' => $total,
                            'isi' => $isi,
                            'harga_satuan_pcs' => $harga_satuan_pcs,
                            'discountp' => $discountp,
                            'discounttd' => $discounttd,
                            'keterangan' => $keterangan,
                            'tgl_kirim' => $tgl_kirim,
                            'kd_barang' => $kd_barang,
                            'operation' => $operation,
                            'principal' => $principal,
                            'supervisor' => $supervisor,
                            'contact_person' => $contact_person,
                            'telpon' => $telpon,
                            'hp' => $hp,
                            'tipe_transaksi' => $tipe_transaksi,
                            'ktp_npwp' => $ktp_npwp,
                            'principal_channel_lv_2_id' => $principal_channel_lv_2_id,
                            'depo' => $depo,
                            'nopo' => $nopo,
                            'SIGNATURE' => $signature,
                            'CREATED_AT' => $created_at,
                            'CREATED_BY' => $this->session->userdata('id')
                        ];
    
                        $this->db->insert('management_raw.raw_vbt_pare',$data);
    
                        $kd_salesman = substr($kd_pelanggan,0,3).$nama_karyawan;
                        $this->db->select('*');
                        $this->db->where("site", 'PARV3');
                        $this->db->where("kode", $kd_salesman);
                        $query = $this->db->get('management_raw.raw_customer_vbt');
    
                        if ($query->num_rows() <= 0) {
    
                            $query = "
                                select a.kode_cust_mpm, substr(a.kode_cust_mpm,2) as urut
                                from management_raw.raw_customer_vbt a 
                                where site = 'PARV3'
                                ORDER BY a.id desc
                                limit 1
                            ";
    
                            $no_cust_current = $this->db->query($query);
                            if ($no_cust_current->num_rows() > 0) {
                                
                                $params_urut = $no_cust_current->row()->urut + 1;
                                // echo $params_urut;
    
                                if (strlen($params_urut) === 1) {
                                    $generate = "S00$params_urut";
                                }elseif (strlen($params_urut) === 2) {
                                    $generate = "S0$params_urut";
                                }else{
                                    $generate = "S$params_urut";
                                }
                            }else{
                                $generate = "S001";
                            }
    
                            $data2 = [
                                'SITE' => 'PARV3',
                                'KODE' => $kd_salesman,
                                'KODE_CUST_MPM' => $generate,
                                'CREATED_AT' => $created_at,
                                'CREATED_BY' => $this->session->userdata('id')
                            ];
                            $this->db->insert('management_raw.raw_customer_vbt',$data2);
                        }
                    }
                }
            }
        }else{
        
        };

        $get_count = "select count(*) as count from management_raw.raw_vbt_pare a where a.signature = '$signature'";
        $count = $this->db->query($get_count)->row()->count;
        
        $get_omzet = "select ROUND(sum(a.total),2) as omzet_raw from management_raw.raw_vbt_pare a
                        where a.signature = '$signature' ";
        $omzet_raw = $this->db->query($get_omzet)->row()->omzet_raw;

        $upload = [
            'site_code'     => 'PARV3',
            'filename'      => $filename,
            'signature'     => $signature,
            'count_raw'     => $count,
            'omzet_raw'     => $omzet_raw,
            'type_file'     => 'raw_sales',
            'bulan'         => $bulan_from_input,
            'tahun'         => $tahun_from_input,
            'created_at'    => $created_at,
            'created_by'    => $this->session->userdata('id')
        ];
        $this->db->insert('management_raw.log_upload', $upload);
        redirect('management_raw/import_vbt_pare_draft/'.$signature);
    }

    public function import_vbt_pare_draft($signature){

        $get_omzet = "select ROUND(sum(a.total),2) as omzet_raw from management_raw.raw_vbt_pare a
        where a.signature = '$signature'";
        $omzet_raw = $this->db->query($get_omzet)->row()->omzet_raw;
        
        $data = [
            'title' => 'Management Raw / Preview Import data vbt_pare',
            'id' => $this->session->userdata('id'),
            'get_raw_draft' => $this->model_management_raw->get_raw_draft_vbt_makasar('PARV3', $signature),
            'omzet_raw' => $omzet_raw,
            'url' => 'management_raw/proses_mapping_vbt_pare',
            'signature' => $signature
        ];
        $this->load->view('management_raw/header');
        $this->load->view('management_raw/draft_vbt_pare', $data);
        $this->load->view('mes/footer');
    }

    public function proses_mapping_vbt_pare(){

        $signature = $this->input->post('signature');
        $status_closing = $this->input->post('status_closing');
        $id_imports = $this->input->post('options');

        $update_kodeproduk = $this->model_management_raw->update_kodeproduk_vbt_makasar('PARV3', $signature);
        if ($update_kodeproduk) {   
            echo "<br><center><i>sukses menambahkan angka 0 di kodeproduk ... </i></b><br>";
        }

        $inner_kodeproduk = $this->model_management_raw->inner_kodeproduk_vbt_makasar('PARV3', $signature);
        if ($inner_kodeproduk) {
            echo "<br><center><i>sukses memfilter produk khusus mpm / membuang produk selain mpm ...</i></b><br>";
        }

        $update_namaprod = $this->model_management_raw->update_namaprod_vbt_makasar('PARV3', $signature);
        if ($update_namaprod) {
            echo "<br><center><i>sukses mengupdate namaproduk ...</i></b><br>";
        }

        $update_branch = $this->model_management_raw->update_branch('PARV3', $signature);
        if ($update_branch) {
            echo "<br><center><i>sukses mengupdate branch_name dan nama_comp ...</i></b><br>";
        }

        $update_tanggal = $this->model_management_raw->update_tanggal_vbt_makasar('PARV3', $signature);                       
        if ($update_tanggal) {
            echo "<br><center><i>sukses memperbaiki format tanggal ...</i></b><br>";
        }

        $delete_tabel = $this->model_management_raw->delete_tabel('PARV3', $signature);
        if ($delete_tabel) {
            echo "<br><center><i>delete tabel done ...</i></b><br>";
        }

        $insert_fi = $this->model_management_raw->insert_fi_vbt_pare('PARV3', $signature);
        if ($insert_fi) {
            echo "<br><center><i>insert fi (sales) done ...</i></b><br>";
        }

        $insert_ri = $this->model_management_raw->insert_ri_vbt_pare('PARV3', $signature);
        if ($insert_ri) {
            echo "<br><center><i>insert ri (retur) done ...</i></b><br>";
        }

        // die;

        $insert_tblang = $this->model_management_raw->insert_tblang_kendari('PARV3', $signature);
        if ($insert_tblang) {
            echo "<br><center><i>insert tblang done ...</i></b><br>";
        }

        $insert_tabsales = $this->model_management_raw->insert_tabsales_kendari('PARV3', $signature);
        if ($insert_tabsales) {
            echo "<br><center><i>insert tabsales done ...</i></b><br>";
        }

        $insert_tbkota = $this->model_management_raw->insert_tbkota_vbt_pare('PARV3', $signature);
        if ($insert_tbkota) {
            echo "<br><center><i>insert tbkota done ...</i></b><br>";
        }        

        $get_count = "select count(*) as count from management_raw.inner_raw_vbt_pare a where a.signature = '$signature'";
        $count = $this->db->query($get_count)->row()->count;

        $get_bulan = $this->model_management_raw->get_log_upload_by_signature($signature)->row()->bulan;
        $get_tahun = $this->model_management_raw->get_log_upload_by_signature($signature)->row()->tahun;

        $get_omzet = $this->model_management_raw->get_omzet_web($get_tahun, $get_bulan, 'PARV3')->row()->omzet;

        $update = [
            'count_mapping' => $count,
            'omzet_web'     => $get_omzet,
        ];
        $this->db->where('signature', $signature);
        $this->db->update('management_raw.log_upload', $update);

        $insert_mpm_upload = $this->model_management_raw->insert_mpm_upload_vbt_makasar('PARV3', $signature, $get_omzet, $status_closing);
        if ($insert_mpm_upload) {
            echo "<br><center><i>insert mpm.upload done ...</i></b><br>";
        }
        
        echo "<br><center><i>dalam 5 detik anda akan di redirect ke halaman awal ...</i></b><br>";
        header('Refresh: 5; URL='.base_url().'management_raw/vbt_pare');    
    }
    
    public function template_manado(){        
        $query = "
            SELECT '' as Trans, '' as status_retur, '' as nama_branch, '' as tipe_sales, '' as siteid, '' as nama_site, '' as salesmanid, '' as nama_salesman, '' as disc_cabang, '' as disc_prinsipal, '' as disc_xtra, '' as disc_cod, '' as group_divisi, '' as kode, '' as depo, '' as type_sales, '' as categoryid, '' as nama_category, '' as productid, '' as nama_invoice, '' as qty_kecil_karton, '' as qty_bonus_karton, '' as qty_kecil, '' as qty_bonus, '' as rp_kotor, '' as rp_Discount, '' as rp_disc_cabang, '' as rp_disc_prinsipal, '' as rp_disc_xtra, '' as rp_disc_cod, '' as rp_netto, '' as rp_bonus, '' as brandid, '' as nama_brand, '' as varianid, '' as nama_varian, '' as groupid, '' as nama_group, '' as customerid_maping, '' as customerid, '' as nama_customer, '' as prefix, '' as alamat, '' as segmentid, '' as nama_segment, '' as typeid, '' as nama_type, '' as group1, '' as contact, '' as propinsiid, '' as nama_propinsi, '' as regionalid, '' as nama_regional, '' as areaid, '' as nama_area, '' as ref, '' as no_sales, '' as ex_no_sales, '' as keterangan, '' as nama_dihubungi, '' as telp_dihubungi, '' as hubungan, '' as term_payment, '' as tanggal, '' as Month, '' as Year, '' as classid, '' as nama_class, '' as harga_jual, '' as tipe_tax, '' as supplierid, '' as product_id_supplier

        ";

        $hsl = $this->db->query($query);
        query_to_csv($hsl,TRUE,'Template_manado.csv');
    }

    public function manado(){

        $data = [
            'title' => 'Management Raw / Import data manado',
            'id' => $this->session->userdata('id'),
            'url_import' => 'management_raw/import_manado',
            'get_log_upload' => $this->model_management_raw->get_log_upload('MANW5')
        ];
        $this->load->view('management_raw/header');
        $this->load->view('management_raw/manado', $data);
        $this->load->view('mes/footer');
    }

    public function import_manado(){
        $bulan_from_input = substr($this->input->post('bulan'), 5, 2);
        $tahun_from_input = substr($this->input->post('bulan'), 0, 4);

        $cek_last_upload = "
            select *
            from mpm.upload a 
            where a.userid = 600 
            ORDER BY a.id desc
        ";
        $tahun_last_upload = $this->db->query($cek_last_upload)->row()->tahun;
        $bulan_last_upload = $this->db->query($cek_last_upload)->row()->bulan;
        $closing_last_upload = $this->db->query($cek_last_upload)->row()->status_closing;

        if ($closing_last_upload == 1) {
            $tanggal_seharusnya = date('Y-m-d', strtotime('+1 month', strtotime($tahun_last_upload.'-'.$bulan_last_upload.'-01')));           
            $bulan_seharusnya = date('m', strtotime($tanggal_seharusnya));
            $tahun_seharusnya = date('Y', strtotime($tanggal_seharusnya));

            if ($tahun_from_input === $tahun_seharusnya && $bulan_from_input === $bulan_seharusnya) {
                
            }else{
                echo "<br><hr>Tanggal yang anda pilih adalah :<br><br>";
                // echo "<hr>";
                echo "tahun : ".$tahun_from_input."<br>";
                echo "bulan : ".$bulan_from_input."<br><br>";
                echo "<hr>";

                echo "<br>Tanggal upload terakhir adalah :<br><br>";
                // echo "<hr>";
                echo "tahun_last_upload : ".$tahun_last_upload."<br>";
                echo "bulan_last_upload : ".$bulan_last_upload."<br>";
                echo "closing_last_upload : ".$closing_last_upload."<br><br>";
                echo "<hr>";
                
                echo "<h3>Result</h3>";
                echo "Data ditolak !!<br><br>";
                echo "Anda hanya diijinkan mengupload data untuk <b>tahun : $tahun_seharusnya</b>, dan <b>bulan : $bulan_seharusnya</b><br>";

                echo "<br>anda akan di redirect ke menu awal dalam 10 detik";
                header('Refresh: 10; URL='.base_url().'management_raw/manado');
                die;
            }

        }else{

            $tanggal_seharusnya = date('Y-m-d', strtotime('+0 month', strtotime($tahun_last_upload.'-'.$bulan_last_upload.'-01')));           
            $bulan_seharusnya = date('m', strtotime($tanggal_seharusnya));
            $tahun_seharusnya = date('Y', strtotime($tanggal_seharusnya));

            if ($tahun_from_input === $tahun_seharusnya && $bulan_from_input === $bulan_seharusnya) {
                // echo "diijinkan";
            }else{
                echo "<br><hr>Tanggal yang anda pilih adalah :<br><br>";
                echo "tahun : ".$tahun_from_input."<br>";
                echo "bulan : ".$bulan_from_input."<br><br>";
                echo "<hr>";


                echo "<br>Tanggal upload terakhir adalah :<br><br>";
                echo "tahun_last_upload : ".$tahun_last_upload."<br>";
                echo "bulan_last_upload : ".$bulan_last_upload."<br>";
                echo "closing_last_upload : ".$closing_last_upload."<br><br>";
                echo "<hr>";
                
                echo "<h3>Result</h3>";
                echo "Data ditolak !!<br><br>";
                echo "Anda hanya diijinkan mengupload data untuk <b>tahun : $tahun_seharusnya</b>, dan <b>bulan : $bulan_seharusnya</b><br>";

                echo "<br>anda akan di redirect ke menu awal dalam 10 detik";
                header('Refresh: 10; URL='.base_url().'management_raw/manado');
                die;
            }

        }

        // die;

        if (!is_dir('./assets/uploads/management_raw/import/')) {
            @mkdir('./assets/uploads/management_raw/import/', 0777);
        }

        $this->load->library('upload'); // Load librari upload        
        $config['upload_path'] = './assets/uploads/management_raw/import/';
        // $config['allowed_types'] = 'xls|xlsx|csv';
        $config['allowed_types'] = '*';    
        $config['max_size']  = '2048000';
        $config['overwrite'] = true;
        $config['encrypt_name'] = TRUE;
        $this->upload->initialize($config);

        if ($this->upload->do_upload('file')) 
        {

            $upload_data = $this->upload->data();
            $filename = $upload_data['orig_name'];

            $this->load->library('excel');
            $object = PHPExcel_IOFactory::load("assets/uploads/management_raw/import/$filename");

            $jumlahSheet = $object->getSheetCount();
            if ($jumlahSheet > 1) {
                echo "jumlah_sheet : ".$jumlahSheet;
                echo "<script>alert('upload file gagal karena file mempunyai lebih dari 1 sheet'); </script>";
                redirect('management_raw/manado','refresh');
            }

            $highestColumm = $object->setActiveSheetIndex(0)->getHighestColumn();
            // var_dump($highestColumm);die;
            if ($highestColumm != 'BT') {
                echo "<script>alert('upload file gagal karena column tidak sesuai'); </script>";
                redirect('management_raw/manado','refresh');
            }

            // die;

            $created_at = $this->model_outlet_transaksi->timezone();
            $signature = md5($this->model_outlet_transaksi->timezone());

            foreach ($object->getWorksheetIterator() as $worksheet) {

                $highestRow = $worksheet->getHighestRow();
                $highestColumn = $worksheet->getHighestColumn();

                for ($row = 2; $row <= $highestRow; $row++) {

                    $trans = trim($worksheet->getCellByColumnAndRow(0, $row)->getValue());
                    $status_retur = trim($worksheet->getCellByColumnAndRow(1, $row)->getValue());
                    $nama_branch = trim($worksheet->getCellByColumnAndRow(2, $row)->getValue());
                    $tipe_sales = trim($worksheet->getCellByColumnAndRow(3, $row)->getValue());
                    $siteid = trim($worksheet->getCellByColumnAndRow(4, $row)->getValue());
                    $nama_site = trim($worksheet->getCellByColumnAndRow(5, $row)->getValue());
                    $salesmanid = trim($worksheet->getCellByColumnAndRow(6, $row)->getValue());
                    $nama_salesman = trim($worksheet->getCellByColumnAndRow(7, $row)->getValue());
                    $disc_cabang = trim($worksheet->getCellByColumnAndRow(8, $row)->getValue());
                    $disc_prinsipal = trim($worksheet->getCellByColumnAndRow(9, $row)->getValue());
                    $disc_xtra = trim($worksheet->getCellByColumnAndRow(10, $row)->getValue());
                    $disc_cod = trim($worksheet->getCellByColumnAndRow(11, $row)->getValue());
                    $group_divisi = trim($worksheet->getCellByColumnAndRow(12, $row)->getValue());
                    $kode = trim($worksheet->getCellByColumnAndRow(13, $row)->getValue());
                    $depo = trim($worksheet->getCellByColumnAndRow(14, $row)->getValue());
                    $type_sales = trim($worksheet->getCellByColumnAndRow(15, $row)->getValue());
                    $categoryid = trim($worksheet->getCellByColumnAndRow(16, $row)->getValue());
                    $nama_category = trim($worksheet->getCellByColumnAndRow(17, $row)->getValue());
                    $productid = trim($worksheet->getCellByColumnAndRow(18, $row)->getValue());
                    $nama_invoice = trim($worksheet->getCellByColumnAndRow(19, $row)->getValue());
                    $qty_kecil_karton = trim($worksheet->getCellByColumnAndRow(20, $row)->getValue());
                    $qty_bonus_karton = trim($worksheet->getCellByColumnAndRow(21, $row)->getValue());
                    $qty_kecil = trim($worksheet->getCellByColumnAndRow(22, $row)->getValue());
                    $qty_bonus = trim($worksheet->getCellByColumnAndRow(23, $row)->getValue());
                    $rp_kotor = trim($worksheet->getCellByColumnAndRow(24, $row)->getValue());
                    $rp_Discount = trim($worksheet->getCellByColumnAndRow(25, $row)->getValue());
                    $rp_disc_cabang = trim($worksheet->getCellByColumnAndRow(26, $row)->getValue());
                    $rp_disc_prinsipal = trim($worksheet->getCellByColumnAndRow(27, $row)->getValue());
                    $rp_disc_xtra = trim($worksheet->getCellByColumnAndRow(28, $row)->getValue());
                    $rp_disc_cod = trim($worksheet->getCellByColumnAndRow(29, $row)->getValue());
                    $rp_netto = trim($worksheet->getCellByColumnAndRow(30, $row)->getValue());
                    $rp_bonus = trim($worksheet->getCellByColumnAndRow(31, $row)->getValue());
                    $brandid = trim($worksheet->getCellByColumnAndRow(32, $row)->getValue());
                    $nama_brand = trim($worksheet->getCellByColumnAndRow(33, $row)->getValue());
                    $varianid = trim($worksheet->getCellByColumnAndRow(34, $row)->getValue());
                    $nama_varian = trim($worksheet->getCellByColumnAndRow(35, $row)->getValue());
                    $groupid = trim($worksheet->getCellByColumnAndRow(36, $row)->getValue());
                    $nama_group = trim($worksheet->getCellByColumnAndRow(37, $row)->getValue());
                    $customerid_maping = trim($worksheet->getCellByColumnAndRow(38, $row)->getValue());
                    $customerid = trim($worksheet->getCellByColumnAndRow(39, $row)->getValue());
                    $nama_customer = trim($worksheet->getCellByColumnAndRow(40, $row)->getValue());
                    $prefix = trim($worksheet->getCellByColumnAndRow(41, $row)->getValue());
                    $alamat = trim($worksheet->getCellByColumnAndRow(42, $row)->getValue());
                    $segmentid = trim($worksheet->getCellByColumnAndRow(43, $row)->getValue());
                    $nama_segment = trim($worksheet->getCellByColumnAndRow(44, $row)->getValue());
                    $typeid = trim($worksheet->getCellByColumnAndRow(45, $row)->getValue());
                    $nama_type = trim($worksheet->getCellByColumnAndRow(46, $row)->getValue());
                    $group1 = trim($worksheet->getCellByColumnAndRow(47, $row)->getValue());
                    $contact = trim($worksheet->getCellByColumnAndRow(48, $row)->getValue());
                    $propinsiid = trim($worksheet->getCellByColumnAndRow(49, $row)->getValue());
                    $nama_propinsi = trim($worksheet->getCellByColumnAndRow(50, $row)->getValue());
                    $regionalid = trim($worksheet->getCellByColumnAndRow(51, $row)->getValue());
                    $nama_regional = trim($worksheet->getCellByColumnAndRow(52, $row)->getValue());
                    $areaid = trim($worksheet->getCellByColumnAndRow(53, $row)->getValue());
                    $nama_area = trim($worksheet->getCellByColumnAndRow(54, $row)->getValue());
                    $ref = trim($worksheet->getCellByColumnAndRow(55, $row)->getValue());
                    $no_sales = trim($worksheet->getCellByColumnAndRow(56, $row)->getValue());
                    $ex_no_sales = trim($worksheet->getCellByColumnAndRow(57, $row)->getValue());
                    $keterangan = trim($worksheet->getCellByColumnAndRow(58, $row)->getValue());
                    $nama_dihubungi = trim($worksheet->getCellByColumnAndRow(59, $row)->getValue());
                    $telp_dihubungi = trim($worksheet->getCellByColumnAndRow(60, $row)->getValue());
                    $hubungan = trim($worksheet->getCellByColumnAndRow(61, $row)->getValue());
                    $term_payment = trim($worksheet->getCellByColumnAndRow(62, $row)->getValue());
                    $tanggal = trim($worksheet->getCellByColumnAndRow(63, $row)->getValue());
                    $Month = trim($worksheet->getCellByColumnAndRow(64, $row)->getValue());
                    $Year = trim($worksheet->getCellByColumnAndRow(65, $row)->getValue());
                    $classid = trim($worksheet->getCellByColumnAndRow(66, $row)->getValue());
                    $nama_class = trim($worksheet->getCellByColumnAndRow(67, $row)->getValue());
                    $harga_jual = trim($worksheet->getCellByColumnAndRow(68, $row)->getValue());
                    $tipe_tax = trim($worksheet->getCellByColumnAndRow(69, $row)->getValue());
                    $supplierid = trim($worksheet->getCellByColumnAndRow(70, $row)->getValue());
                    $product_id_supplier = trim($worksheet->getCellByColumnAndRow(71, $row)->getValue());
                    
                    if(strlen($product_id_supplier) == 5){
                        $kodeprod = '0'.$product_id_supplier;
                    }else {
                        $kodeprod = $product_id_supplier;
                    }

                    $data = [
                        'trans' => $trans,
                        'status_retur' => $status_retur,
                        'nama_branch' => $nama_branch,
                        'tipe_sales' => $tipe_sales,
                        'siteid' => $siteid,
                        'nama_site' => $nama_site,
                        'salesmanid' => $salesmanid,
                        'nama_salesman' => $nama_salesman,
                        'disc_cabang' => $disc_cabang,
                        'disc_prinsipal' => $disc_prinsipal,
                        'disc_xtra' => $disc_xtra,
                        'disc_cod' => $disc_cod,
                        'group_divisi' => $group_divisi,
                        'kode' => $kode,
                        'depo' => $depo,
                        'type_sales' => $type_sales,
                        'categoryid' => $categoryid,
                        'nama_category' => $nama_category,
                        'productid' => $productid,
                        'nama_invoice' => $nama_invoice,
                        'qty_kecil_karton' => $qty_kecil_karton,
                        'qty_bonus_karton' => $qty_bonus_karton,
                        'qty_kecil' => $qty_kecil,
                        'qty_bonus' => $qty_bonus,
                        'rp_kotor' => $rp_kotor,
                        'rp_Discount' => $rp_Discount,
                        'rp_disc_cabang' => $rp_disc_cabang,
                        'rp_disc_prinsipal' => $rp_disc_prinsipal,
                        'rp_disc_xtra' => $rp_disc_xtra,
                        'rp_disc_cod' => $rp_disc_cod,
                        'rp_netto' => $rp_netto,
                        'rp_bonus' => $rp_bonus,
                        'brandid' => $brandid,
                        'nama_brand' => $nama_brand,
                        'varianid' => $varianid,
                        'nama_varian' => $nama_varian,
                        'groupid' => $groupid,
                        'nama_group' => $nama_group,
                        'customerid_maping' => $customerid_maping,
                        'customerid' => $customerid,
                        'nama_customer' => $nama_customer,
                        'prefix' => $prefix,
                        'alamat' => $alamat,
                        'segmentid' => $segmentid,
                        'nama_segment' => $nama_segment,
                        'typeid' => $typeid,
                        'nama_type' => $nama_type,
                        'group1' => $group1,
                        'contact' => $contact,
                        'propinsiid' => $propinsiid,
                        'nama_propinsi' => $nama_propinsi,
                        'regionalid' => $regionalid,
                        'nama_regional' => $nama_regional,
                        'areaid' => $areaid,
                        'nama_area' => $nama_area,
                        'ref' => $ref,
                        'no_sales' => $no_sales,
                        'ex_no_sales' => $ex_no_sales,
                        'keterangan' => $keterangan,
                        'nama_dihubungi' => $nama_dihubungi,
                        'telp_dihubungi' => $telp_dihubungi,
                        'hubungan' => $hubungan,
                        'term_payment' => $term_payment,
                        'tanggal' => $tanggal,
                        'Month' => $Month,
                        'Year' => $Year,
                        'classid' => $classid,
                        'nama_class' => $nama_class,
                        'harga_jual' => $harga_jual,
                        'tipe_tax' => $tipe_tax,
                        'supplierid' => $supplierid,
                        'product_id_supplier' => $kodeprod,
                        'SIGNATURE' => $signature,
                        'CREATED_AT' => $created_at,
                        'CREATED_BY' => $this->session->userdata('id')

                    ];

                    $this->db->insert('management_raw.raw_manado',$data);

                    $this->db->select('*');
                    $this->db->where("customer_id", $customerid);
                    $query = $this->db->get('management_raw.raw_customer_manado');

                    if ($query->num_rows() <= 0) {

                        $data2 = [
                            'customer_id' => $customerid,
                            'nama_customer' => $nama_customer,
                            'alamat' => $alamat,
                            'kode_type' => $typeid,
                            'nama_type' => $nama_type,
                            'kode_class' => $classid,
                            'nama_class' => $nama_class,
                            'segment' => $segmentid,
                            'kode_kota' => $regionalid,
                            'nama_kota' => $nama_regional,
                            'CREATED_AT' => $created_at,
                            'CREATED_BY' => $this->session->userdata('id')
                        ];
                        $this->db->insert('management_raw.raw_customer_manado',$data2);
                    }
                    else {
                        $data2 = [
                            'nama_customer' => $nama_customer,
                            'alamat' => $alamat,
                            'kode_type' => $typeid,
                            'nama_type' => $nama_type,
                            'kode_class' => $classid,
                            'nama_class' => $nama_class,
                            'segment' => $segmentid,
                            'kode_kota' => $regionalid,
                            'nama_kota' => $nama_regional,
                            'CREATED_AT' => $created_at,
                            'CREATED_BY' => $this->session->userdata('id')
                        ];
                        $this->db->where('customer_id', $customerid);
                        $this->db->update('management_raw.raw_customer_manado', $data2);
                    }
                }
            }
        }else{
        
        };

        $get_count = "select count(*) as count from management_raw.raw_manado a where a.signature = '$signature'";
        $count = $this->db->query($get_count)->row()->count;
        
        $get_omzet = "select sum(a.rp_kotor)as omzet_raw from management_raw.raw_manado a
                        where a.signature = '$signature' ";
        $omzet_raw = $this->db->query($get_omzet)->row()->omzet_raw;

        $upload = [
            'site_code'     => 'MANW5',
            'filename'      => $filename,
            'signature'     => $signature,
            'count_raw'     => $count,
            'omzet_raw'     => $omzet_raw,
            'type_file'     => 'raw_sales',
            'bulan'         => $bulan_from_input,
            'tahun'         => $tahun_from_input,
            'created_at'    => $created_at,
            'created_by'    => $this->session->userdata('id')
        ];
        $this->db->insert('management_raw.log_upload', $upload);
        redirect('management_raw/import_manado_draft/'.$signature);
    }

    public function import_manado_draft($signature){

        $get_omzet = "select sum(a.rp_kotor) as omzet_raw from management_raw.raw_manado a
        where a.signature = '$signature'";
        $omzet_raw = $this->db->query($get_omzet)->row()->omzet_raw;
        
        $data = [
            'title' => 'Management Raw / Preview Import data manado',
            'id' => $this->session->userdata('id'),
            'get_raw_draft' => $this->model_management_raw->get_raw_draft('MANW5', $signature),
            'omzet_raw' => $omzet_raw,
            'url' => 'management_raw/proses_mapping_manado',
            'signature' => $signature
        ];
        $this->load->view('management_raw/header');
        $this->load->view('management_raw/draft_manado', $data);
        $this->load->view('mes/footer');
    }

    public function proses_mapping_manado(){

        $signature = $this->input->post('signature');
        $status_closing = $this->input->post('status_closing');
        $id_imports = $this->input->post('options');

        $inner_kodeproduk = $this->model_management_raw->inner_kodeproduk_manado('MANW5', $signature);
        if ($inner_kodeproduk) {
            echo "<br><center><i>sukses memfilter produk khusus mpm / membuang produk selain mpm ...</i></b><br>";
        }

        $update_namaprod = $this->model_management_raw->update_namaprod_manado('MANW5', $signature);
        if ($update_namaprod) {
            echo "<br><center><i>sukses mengupdate namaproduk ...</i></b><br>";
        }

        $update_branch = $this->model_management_raw->update_branch('MANW5', $signature);
        if ($update_branch) {
            echo "<br><center><i>sukses mengupdate branch_name dan nama_comp ...</i></b><br>";
        }

        $update_tanggal = $this->model_management_raw->update_tanggal('MANW5', $signature);                       
        if ($update_tanggal) {
            echo "<br><center><i>sukses memperbaiki format tanggal ...</i></b><br>";
        }

        $prepare_tblang_bantu = $this->model_management_raw->prepare_tblang_bantu('MANW5', $signature);
        if ($prepare_tblang_bantu) {
            echo "<br><center><i>prepare_tblang_bantu done ...</i></b><br>";
        }

        $delete_tabel = $this->model_management_raw->delete_tabel('MANW5', $signature);
        if ($delete_tabel) {
            echo "<br><center><i>delete tabel done ...</i></b><br>";
        }

        $insert_fi = $this->model_management_raw->insert_fi_manado('MANW5', $signature);
        if ($insert_fi) {
            echo "<br><center><i>insert fi (sales) done ...</i></b><br>";
        }

        $insert_ri = $this->model_management_raw->insert_ri_manado('MANW5', $signature);
        if ($insert_ri) {
            echo "<br><center><i>insert ri (retur) done ...</i></b><br>";
        }

        $insert_tblang_bantu = $this->model_management_raw->insert_tblang_bantu_banjarmasin('MANW5', $signature);
        if ($insert_tblang_bantu) {
            echo "<br><center><i>insert tblang bantu done ...</i></b><br>";
        }

        $update_tblang_bantu = $this->model_management_raw->update_tblang_bantu('MANW5', $signature);
        if ($update_tblang_bantu) {
            echo "<br><center><i>update_ tblang bantu done ...</i></b><br>";
        }

        $insert_tblang = $this->model_management_raw->insert_tblang_banjarmasin('MANW5', $signature);
        if ($insert_tblang) {
            echo "<br><center><i>insert tblang done ...</i></b><br>";
        }

        $insert_tabsales = $this->model_management_raw->insert_tabsales_kendari('MANW5', $signature);
        if ($insert_tabsales) {
            echo "<br><center><i>insert tabsales done ...</i></b><br>";
        }

        $insert_tbkota = $this->model_management_raw->insert_tbkota_manado('MANW5', $signature);
        if ($insert_tbkota) {
            echo "<br><center><i>insert tbkota done ...</i></b><br>";
        }        

        $get_count = "select count(*) as count from management_raw.inner_raw_manado a where a.signature = '$signature'";
        $count = $this->db->query($get_count)->row()->count;

        $get_bulan = $this->model_management_raw->get_log_upload_by_signature($signature)->row()->bulan;
        $get_tahun = $this->model_management_raw->get_log_upload_by_signature($signature)->row()->tahun;

        $get_omzet = $this->model_management_raw->get_omzet_web($get_tahun, $get_bulan, 'MANW5')->row()->omzet;

        $update = [
            'count_mapping' => $count,
            'omzet_web'     => $get_omzet,
        ];
        $this->db->where('signature', $signature);
        $this->db->update('management_raw.log_upload', $update);

        $insert_mpm_upload = $this->model_management_raw->insert_mpm_upload('MANW5', $signature, $get_omzet, $status_closing);
        if ($insert_mpm_upload) {
            echo "<br><center><i>insert mpm.upload done ...</i></b><br>";
        }
        
        echo "<br><center><i>dalam 5 detik anda akan di redirect ke halaman awal ...</i></b><br>";
        header('Refresh: 5; URL='.base_url().'management_raw/manado');    
    }

    public function template_gorontalo(){        
        $query = "
            SELECT  '' as No, '' as Nomor_Nota, '' as Tanggal, '' as Customer, '' as Wilayah, '' as Outlet, '' as Kode_Type, '' as Kode_Class, '' as Alamat, '' as Sales, '' as Kode_MPM, '' as Kode_Brg, '' as Nama_Barang, '' as Supplier, '' as Merk, '' as Jumlah, '' as T.P.R, '' as Harga_Kcl, '' as Potongan, '' as Nilai_Unit

        ";

        $hsl = $this->db->query($query);
        query_to_csv($hsl,TRUE,'Template_gorontalo.csv');
    }

    public function gorontalo(){

        $data = [
            'title' => 'Management Raw / Import data gorontalo',
            'id' => $this->session->userdata('id'),
            'url_import' => 'management_raw/import_gorontalo',
            'get_log_upload' => $this->model_management_raw->get_log_upload('GTO87')
        ];
        $this->load->view('management_raw/header');
        $this->load->view('management_raw/gorontalo', $data);
        $this->load->view('mes/footer');
    }

    public function import_gorontalo(){
        $bulan_from_input = substr($this->input->post('bulan'), 5, 2);
        $tahun_from_input = substr($this->input->post('bulan'), 0, 4);

        $cek_last_upload = "
            select *
            from mpm.upload a 
            where a.userid = 108 
            ORDER BY a.id desc
        ";
        $tahun_last_upload = $this->db->query($cek_last_upload)->row()->tahun;
        $bulan_last_upload = $this->db->query($cek_last_upload)->row()->bulan;
        $closing_last_upload = $this->db->query($cek_last_upload)->row()->status_closing;

        if ($closing_last_upload == 1) {
            $tanggal_seharusnya = date('Y-m-d', strtotime('+1 month', strtotime($tahun_last_upload.'-'.$bulan_last_upload.'-01')));           
            $bulan_seharusnya = date('m', strtotime($tanggal_seharusnya));
            $tahun_seharusnya = date('Y', strtotime($tanggal_seharusnya));

            if ($tahun_from_input === $tahun_seharusnya && $bulan_from_input === $bulan_seharusnya) {
                
            }else{
                echo "<br><hr>Tanggal yang anda pilih adalah :<br><br>";
                // echo "<hr>";
                echo "tahun : ".$tahun_from_input."<br>";
                echo "bulan : ".$bulan_from_input."<br><br>";
                echo "<hr>";

                echo "<br>Tanggal upload terakhir adalah :<br><br>";
                // echo "<hr>";
                echo "tahun_last_upload : ".$tahun_last_upload."<br>";
                echo "bulan_last_upload : ".$bulan_last_upload."<br>";
                echo "closing_last_upload : ".$closing_last_upload."<br><br>";
                echo "<hr>";
                
                echo "<h3>Result</h3>";
                echo "Data ditolak !!<br><br>";
                echo "Anda hanya diijinkan mengupload data untuk <b>tahun : $tahun_seharusnya</b>, dan <b>bulan : $bulan_seharusnya</b><br>";

                echo "<br>anda akan di redirect ke menu awal dalam 10 detik";
                header('Refresh: 10; URL='.base_url().'management_raw/gorontalo');
                die;
            }

        }else{

            $tanggal_seharusnya = date('Y-m-d', strtotime('+0 month', strtotime($tahun_last_upload.'-'.$bulan_last_upload.'-01')));           
            $bulan_seharusnya = date('m', strtotime($tanggal_seharusnya));
            $tahun_seharusnya = date('Y', strtotime($tanggal_seharusnya));

            if ($tahun_from_input === $tahun_seharusnya && $bulan_from_input === $bulan_seharusnya) {
                // echo "diijinkan";
            }else{
                echo "<br><hr>Tanggal yang anda pilih adalah :<br><br>";
                echo "tahun : ".$tahun_from_input."<br>";
                echo "bulan : ".$bulan_from_input."<br><br>";
                echo "<hr>";


                echo "<br>Tanggal upload terakhir adalah :<br><br>";
                echo "tahun_last_upload : ".$tahun_last_upload."<br>";
                echo "bulan_last_upload : ".$bulan_last_upload."<br>";
                echo "closing_last_upload : ".$closing_last_upload."<br><br>";
                echo "<hr>";
                
                echo "<h3>Result</h3>";
                echo "Data ditolak !!<br><br>";
                echo "Anda hanya diijinkan mengupload data untuk <b>tahun : $tahun_seharusnya</b>, dan <b>bulan : $bulan_seharusnya</b><br>";

                echo "<br>anda akan di redirect ke menu awal dalam 10 detik";
                header('Refresh: 10; URL='.base_url().'management_raw/gorontalo');
                die;
            }

        }

        // die;

        if (!is_dir('./assets/uploads/management_raw/import/')) {
            @mkdir('./assets/uploads/management_raw/import/', 0777);
        }

        $this->load->library('upload'); // Load librari upload        
        $config['upload_path'] = './assets/uploads/management_raw/import/';
        // $config['allowed_types'] = 'xls|xlsx|csv';
        $config['allowed_types'] = '*';    
        $config['max_size']  = '2048000';
        $config['overwrite'] = true;
        $config['encrypt_name'] = TRUE;
        $this->upload->initialize($config);

        if ($this->upload->do_upload('file')) 
        {

            $upload_data = $this->upload->data();
            $filename = $upload_data['orig_name'];

            $this->load->library('excel');
            $object = PHPExcel_IOFactory::load("assets/uploads/management_raw/import/$filename");

            $jumlahSheet = $object->getSheetCount();
            if ($jumlahSheet > 1) {
                echo "jumlah_sheet : ".$jumlahSheet;
                echo "<script>alert('upload file gagal karena file mempunyai lebih dari 1 sheet'); </script>";
                redirect('management_raw/gorontalo','refresh');
            }

            $highestColumm = $object->setActiveSheetIndex(0)->getHighestColumn();
            // var_dump($highestColumm);die;
            if ($highestColumm != 'T') {
                echo "<script>alert('upload file gagal karena column tidak sesuai'); </script>";
                redirect('management_raw/gorontalo','refresh');
            }

            // die;

            $created_at = $this->model_outlet_transaksi->timezone();
            $signature = md5($this->model_outlet_transaksi->timezone());

            foreach ($object->getWorksheetIterator() as $worksheet) {

                $highestRow = $worksheet->getHighestRow();
                $highestColumn = $worksheet->getHighestColumn();

                for ($row = 2; $row <= $highestRow; $row++) {

                    $no = trim($worksheet->getCellByColumnAndRow(0, $row)->getValue());
                    $nomor_nota = trim($worksheet->getCellByColumnAndRow(1, $row)->getValue());
                    $tanggal = trim($worksheet->getCellByColumnAndRow(2, $row)->getValue());
                    $customer = trim($worksheet->getCellByColumnAndRow(3, $row)->getValue());
                    $wilayah = trim($worksheet->getCellByColumnAndRow(4, $row)->getValue());
                    $outlet = trim($worksheet->getCellByColumnAndRow(5, $row)->getValue());
                    $kode_type = trim($worksheet->getCellByColumnAndRow(6, $row)->getValue());
                    $kode_class = trim($worksheet->getCellByColumnAndRow(7, $row)->getValue());
                    $alamat = trim($worksheet->getCellByColumnAndRow(8, $row)->getValue());
                    $sales = trim($worksheet->getCellByColumnAndRow(9, $row)->getValue());
                    $kode_mpm = trim($worksheet->getCellByColumnAndRow(10, $row)->getValue());
                    $kode_brg = trim($worksheet->getCellByColumnAndRow(11, $row)->getValue());
                    $nama_barang = trim($worksheet->getCellByColumnAndRow(12, $row)->getValue());
                    $supplier = trim($worksheet->getCellByColumnAndRow(13, $row)->getValue());
                    $merk = trim($worksheet->getCellByColumnAndRow(14, $row)->getValue());
                    $jumlah = trim($worksheet->getCellByColumnAndRow(15, $row)->getValue());
                    $tpr = trim($worksheet->getCellByColumnAndRow(16, $row)->getValue());
                    $harga_kcl = trim($worksheet->getCellByColumnAndRow(17, $row)->getValue());
                    $potongan = trim($worksheet->getCellByColumnAndRow(18, $row)->getValue());
                    $nilai_unit = trim($worksheet->getCellByColumnAndRow(19, $row)->getValue());
                    $nama_customer = trim($customer);

                    $data = [
                        'nomor_nota' => $nomor_nota,
                        'tanggal' => $tanggal,
                        'customer' => $nama_customer,
                        'wilayah' => $wilayah,
                        'outlet' => $outlet,
                        'kode_type' => $kode_type,
                        'kode_class' => $kode_class,
                        'alamat' => $alamat,
                        'sales' => $sales,
                        'kode_mpm' => $kode_mpm,
                        'kode_brg' => $kode_brg,
                        'nama_barang' => $nama_barang,
                        'supplier' => $supplier,
                        'merk' => $merk,
                        'jumlah' => $jumlah,
                        'tpr' => $tpr,
                        'harga_kcl' => str_replace(',','',$harga_kcl),
                        'potongan' => str_replace(',','',$potongan),
                        'nilai_unit' => str_replace(',','',$nilai_unit),
                        'SIGNATURE' => $signature,
                        'CREATED_AT' => $created_at,
                        'CREATED_BY' => $this->session->userdata('id')

                    ];

                    if ($nomor_nota != null || $nomor_nota != '') {
                        $this->db->insert('management_raw.raw_gorontalo',$data);
                    }

                    $this->db->select('*');
                    $this->db->where("nama_customer", $nama_customer);
                    $query = $this->db->get('management_raw.raw_customer_gorontalo');

                    if ($query->num_rows() <= 0) {
                        $this->db->select('customer_id');
                        $this->db->order_by('customer_id', 'DESC');
                        $query = $this->db->get('management_raw.raw_customer_gorontalo', 1);

                        $params_urut = $query->row()->customer_id + 1;
                        // echo $params_urut;

                        if (strlen($params_urut) === 1) {
                            $generate = "0000$params_urut";
                        }elseif (strlen($params_urut) === 2) {
                            $generate = "000$params_urut";
                        }elseif (strlen($params_urut) === 3) {
                            $generate = "00$params_urut";
                        }elseif (strlen($params_urut) === 4) {
                            $generate = "0$params_urut";
                        }else{
                            $generate = "$params_urut";
                        }

                        $data2 = [
                            'customer_id' => $generate,
                            'nama_customer' => $nama_customer,
                            'ALAMAT' => $alamat,
                            'KODE_KOTA' => 'GTO',
                            'KODE_TYPE' => $kode_type,
                            'KODE_CLASS' => $kode_class,
                            'CREATED_AT' => $created_at,
                            'CREATED_BY' => $this->session->userdata('id')
                        ];
                        $this->db->insert('management_raw.raw_customer_gorontalo',$data2);
                    }
                    else {

                        $data2 = [
                            'ALAMAT' => $alamat,
                            'KODE_TYPE' => $kode_type,
                            'KODE_CLASS' => $kode_class,
                            'CREATED_AT' => $created_at,
                            'CREATED_BY' => $this->session->userdata('id')
                        ];
                        
                        $this->db->where('nama_customer', $nama_customer);
                        $this->db->update('management_raw.raw_customer_gorontalo',$data2);
                    }
                }
            }
        }else{
        
        };

        $get_count = "select count(*) as count from management_raw.raw_gorontalo a where a.signature = '$signature'";
        $count = $this->db->query($get_count)->row()->count;
        
        $get_omzet = "select sum(a.nilai_unit) as omzet_raw from management_raw.raw_gorontalo a
                        where a.signature = '$signature' ";
        $omzet_raw = $this->db->query($get_omzet)->row()->omzet_raw;

        $upload = [
            'site_code'     => 'GTO87',
            'filename'      => $filename,
            'signature'     => $signature,
            'count_raw'     => $count,
            'omzet_raw'     => $omzet_raw,
            'type_file'     => 'raw_sales',
            'bulan'         => $bulan_from_input,
            'tahun'         => $tahun_from_input,
            'created_at'    => $created_at,
            'created_by'    => $this->session->userdata('id')
        ];
        $this->db->insert('management_raw.log_upload', $upload);
        redirect('management_raw/import_gorontalo_draft/'.$signature);
    }

    public function import_gorontalo_draft($signature){

        $get_omzet = "select sum(a.nilai_unit) as omzet_raw from management_raw.raw_gorontalo a where a.signature = '$signature'";
        $omzet_raw = $this->db->query($get_omzet)->row()->omzet_raw;
        
        $data = [
            'title' => 'Management Raw / Preview Import data gorontalo',
            'id' => $this->session->userdata('id'),
            'get_raw_draft' => $this->model_management_raw->get_raw_draft_gorontalo('GTO87', $signature),
            'omzet_raw' => $omzet_raw,
            'url' => 'management_raw/proses_mapping_gorontalo',
            'signature' => $signature
        ];
        $this->load->view('management_raw/header');
        $this->load->view('management_raw/draft_gorontalo', $data);
        $this->load->view('mes/footer');
    }

    public function proses_mapping_gorontalo(){

        $signature = $this->input->post('signature');
        $status_closing = $this->input->post('status_closing');
        $id_imports = $this->input->post('options');

        $update_kodeproduk = $this->model_management_raw->update_kodeproduk_gorontalo('GTO87', $signature);
        if ($update_kodeproduk) {   
            echo "<br><center><i>sukses menambahkan angka 0 di kodeproduk ... </i></b><br>";
        }

        $inner_kodeproduk = $this->model_management_raw->inner_kodeproduk_gorontalo('GTO87', $signature);
        if ($inner_kodeproduk) {
            echo "<br><center><i>sukses memfilter produk khusus mpm / membuang produk selain mpm ...</i></b><br>";
        }

        $update_namaprod = $this->model_management_raw->update_namaprod_gorontalo('GTO87', $signature);
        if ($update_namaprod) {
            echo "<br><center><i>sukses mengupdate namaproduk ...</i></b><br>";
        }

        $update_branch = $this->model_management_raw->update_branch('GTO87', $signature);
        if ($update_branch) {
            echo "<br><center><i>sukses mengupdate branch_name dan nama_comp ...</i></b><br>";
        }

        $update_tanggal = $this->model_management_raw->update_tanggal_balikpapan('GTO87', $signature);                       
        if ($update_tanggal) {
            echo "<br><center><i>sukses memperbaiki format tanggal ...</i></b><br>";
        }

        $prepare_tblang_bantu = $this->model_management_raw->prepare_tblang_bantu('GTO87', $signature);
        if ($prepare_tblang_bantu) {
            echo "<br><center><i>prepare_tblang_bantu done ...</i></b><br>";
        }

        $delete_tabel = $this->model_management_raw->delete_tabel('GTO87', $signature);
        if ($delete_tabel) {
            echo "<br><center><i>delete tabel done ...</i></b><br>";
        }

        $insert_fi = $this->model_management_raw->insert_fi_gorontalo('GTO87', $signature);
        if ($insert_fi) {
            echo "<br><center><i>insert fi (sales) done ...</i></b><br>";
        }

        $insert_tblang_bantu = $this->model_management_raw->insert_tblang_bantu_gorontalo('GTO87', $signature);
        if ($insert_tblang_bantu) {
            echo "<br><center><i>insert tblang bantu done ...</i></b><br>";
        }

        $update_tblang_bantu = $this->model_management_raw->update_tblang_bantu('GTO87', $signature);
        if ($update_tblang_bantu) {
            echo "<br><center><i>update_ tblang bantu done ...</i></b><br>";
        }

        $insert_tblang = $this->model_management_raw->insert_tblang_banjarmasin('GTO87', $signature);
        if ($insert_tblang) {
            echo "<br><center><i>insert tblang done ...</i></b><br>";
        }

        $update_fi = $this->model_management_raw->update_fi('GTO87', $signature);
        if ($update_fi) {
            echo "<br><center><i>update fi done ...</i></b><br>";
        }

        $insert_tabsales = $this->model_management_raw->insert_tabsales_kendari('GTO87', $signature);
        if ($insert_tabsales) {
            echo "<br><center><i>insert tabsales done ...</i></b><br>";
        }

        $insert_tbkota = $this->model_management_raw->insert_tbkota_gorontalo('GTO87', $signature);
        if ($insert_tbkota) {
            echo "<br><center><i>insert tbkota done ...</i></b><br>";
        }
        

        $get_count = "select count(*) as count from management_raw.inner_raw_gorontalo a where a.signature = '$signature'";
        $count = $this->db->query($get_count)->row()->count;

        $get_bulan = $this->model_management_raw->get_log_upload_by_signature($signature)->row()->bulan;
        $get_tahun = $this->model_management_raw->get_log_upload_by_signature($signature)->row()->tahun;

        $get_omzet = $this->model_management_raw->get_omzet_web($get_tahun, $get_bulan, 'GTO87')->row()->omzet;

        $update = [
            'count_mapping' => $count,
            'omzet_web'     => $get_omzet,
        ];
        $this->db->where('signature', $signature);
        $this->db->update('management_raw.log_upload', $update);

        $insert_mpm_upload = $this->model_management_raw->insert_mpm_upload('GTO87', $signature, $get_omzet, $status_closing);
        if ($insert_mpm_upload) {
            echo "<br><center><i>insert mpm.upload done ...</i></b><br>";
        }
        
        echo "<br><center><i>dalam 5 detik anda akan di redirect ke halaman awal ...</i></b><br>";
        header('Refresh: 5; URL='.base_url().'management_raw/gorontalo');    
    }

    
    public function template_pangkalanbun(){        
        $query = "
            SELECT '' as siteid, '' as nosales, '' as tanggal_sales, '' as salesmanid, '' as nama_salesman, '' as customerid, '' as nama_customer, '' as productid, '' as product_Descr, '' as flag_retur, '' as flag_bonus, '' as harga, '' as qty, '' as bruto, '' as disc_cabang, '' as rp_cabang, '' as disc_prinsipal, '' as rp_prinsipal, '' as disc_xtra, '' as rp_xtra, '' as disc_cash, '' as rp_cash, '' as netto

        ";

        $hsl = $this->db->query($query);
        query_to_csv($hsl,TRUE,'Template_pangkalanbun.csv');
    }

    public function pangkalanbun(){

        $data = [
            'title'     => 'Management Raw / Import data pangkalanbun',
            'id'        => $this->session->userdata('id'),
            'url_import'=> 'management_raw/import_pangkalanbun',
            'get_log_upload'    => $this->model_management_raw->get_log_upload('PBNP9')
        ];
        $this->load->view('management_raw/header');
        $this->load->view('management_raw/pangkalanbun', $data);
        $this->load->view('mes/footer');
    }

    public function import_pangkalanbun(){
        // $bulan = $this->input->post('bulan');
        // echo "bulan : ".$bulan;

        $bulan_from_input = substr($this->input->post('bulan'), 5, 2);
        $tahun_from_input = substr($this->input->post('bulan'), 0, 4);

        $cek_last_upload = "
            select *
            from mpm.upload a 
            where a.userid = 545 
            ORDER BY a.id desc
        ";
        $tahun_last_upload = $this->db->query($cek_last_upload)->row()->tahun;
        $bulan_last_upload = $this->db->query($cek_last_upload)->row()->bulan;
        $closing_last_upload = $this->db->query($cek_last_upload)->row()->status_closing;

        if ($closing_last_upload == 1) {
            $tanggal_seharusnya = date('Y-m-d', strtotime('+1 month', strtotime($tahun_last_upload.'-'.$bulan_last_upload.'-01')));           
            $bulan_seharusnya = date('m', strtotime($tanggal_seharusnya));
            $tahun_seharusnya = date('Y', strtotime($tanggal_seharusnya));

            if ($tahun_from_input === $tahun_seharusnya && $bulan_from_input === $bulan_seharusnya) {
                
            }else{
                echo "<br><hr>Tanggal yang anda pilih adalah :<br><br>";
                // echo "<hr>";
                echo "tahun : ".$tahun_from_input."<br>";
                echo "bulan : ".$bulan_from_input."<br><br>";
                echo "<hr>";

                echo "<br>Tanggal upload terakhir adalah :<br><br>";
                // echo "<hr>";
                echo "tahun_last_upload : ".$tahun_last_upload."<br>";
                echo "bulan_last_upload : ".$bulan_last_upload."<br>";
                echo "closing_last_upload : ".$closing_last_upload."<br><br>";
                echo "<hr>";
                
                echo "<h3>Result</h3>";
                echo "Data ditolak !!<br><br>";
                echo "Anda hanya diijinkan mengupload data untuk <b>tahun : $tahun_seharusnya</b>, dan <b>bulan : $bulan_seharusnya</b><br>";

                echo "<br>anda akan di redirect ke menu awal dalam 10 detik";
                header('Refresh: 10; URL='.base_url().'management_raw/pangkalanbun');
                die;
            }

        }else{

            $tanggal_seharusnya = date('Y-m-d', strtotime('+0 month', strtotime($tahun_last_upload.'-'.$bulan_last_upload.'-01')));           
            $bulan_seharusnya = date('m', strtotime($tanggal_seharusnya));
            $tahun_seharusnya = date('Y', strtotime($tanggal_seharusnya));

            if ($tahun_from_input === $tahun_seharusnya && $bulan_from_input === $bulan_seharusnya) {
                // echo "diijinkan";
            }else{
                echo "<br><hr>Tanggal yang anda pilih adalah :<br><br>";
                echo "tahun : ".$tahun_from_input."<br>";
                echo "bulan : ".$bulan_from_input."<br><br>";
                echo "<hr>";


                echo "<br>Tanggal upload terakhir adalah :<br><br>";
                echo "tahun_last_upload : ".$tahun_last_upload."<br>";
                echo "bulan_last_upload : ".$bulan_last_upload."<br>";
                echo "closing_last_upload : ".$closing_last_upload."<br><br>";
                echo "<hr>";
                
                echo "<h3>Result</h3>";
                echo "Data ditolak !!<br><br>";
                echo "Anda hanya diijinkan mengupload data untuk <b>tahun : $tahun_seharusnya</b>, dan <b>bulan : $bulan_seharusnya</b><br>";

                echo "<br>anda akan di redirect ke menu awal dalam 10 detik";
                header('Refresh: 10; URL='.base_url().'management_raw/pangkalanbun');
                die;
            }

        }

        if (!is_dir('./assets/uploads/management_raw/import/')) {
            @mkdir('./assets/uploads/management_raw/import/', 0777);
        }

        $this->load->library('upload'); // Load librari upload        
        $config['upload_path'] = './assets/uploads/management_raw/import/';
        // $config['allowed_types'] = 'xls|xlsx|csv';
        $config['allowed_types'] = '*';    
        $config['max_size']  = '2048000';
        $config['overwrite'] = true;
        $config['encrypt_name'] = TRUE;
        $this->upload->initialize($config);

        if ($this->upload->do_upload('file')) 
        {

            $upload_data = $this->upload->data();
            $filename = $upload_data['orig_name'];

            $this->load->library('excel');
            $object = PHPExcel_IOFactory::load("assets/uploads/management_raw/import/$filename");

            $jumlahSheet = $object->getSheetCount();
            if ($jumlahSheet > 1) {
                echo "jumlah_sheet : ".$jumlahSheet;
                echo "<script>alert('upload file gagal karena file mempunyai lebih dari 1 sheet'); </script>";
                redirect('management_raw/pangkalanbun','refresh');
            }

            $highestColumm = $object->setActiveSheetIndex(0)->getHighestColumn();
            // var_dump($highestColumm);die;
            $highestColumm = $object->setActiveSheetIndex(0)->getHighestColumn();
            // var_dump($highestColumm);die;
            if ($highestColumm != 'W') {
                echo "<script>alert('upload file gagal karena column tidak sesuai'); </script>";
                redirect('management_raw/pangkalanbun','refresh');
            }

            // die;

            $created_at = $this->model_outlet_transaksi->timezone();
            $signature = md5($this->model_outlet_transaksi->timezone());

            foreach ($object->getWorksheetIterator() as $worksheet) {

                $highestRow = $worksheet->getHighestRow();
                $highestColumn = $worksheet->getHighestColumn();

                for ($row = 2; $row <= $highestRow; $row++) {          
                    $siteid = trim($worksheet->getCellByColumnAndRow(0, $row)->getValue());
                    $nosales = trim($worksheet->getCellByColumnAndRow(1, $row)->getValue());
                    $tanggal_sales = trim($worksheet->getCellByColumnAndRow(2, $row)->getValue());
                    $salesmanid = trim($worksheet->getCellByColumnAndRow(3, $row)->getValue());
                    $nama_salesman = trim($worksheet->getCellByColumnAndRow(4, $row)->getValue());
                    $customerid = trim($worksheet->getCellByColumnAndRow(5, $row)->getValue());
                    $nama_customer = trim($worksheet->getCellByColumnAndRow(6, $row)->getValue());
                    $productid = trim($worksheet->getCellByColumnAndRow(7, $row)->getValue());
                    $product_descr = trim($worksheet->getCellByColumnAndRow(8, $row)->getValue());
                    $flag_retur = trim($worksheet->getCellByColumnAndRow(9, $row)->getValue());
                    $flag_bonus = trim($worksheet->getCellByColumnAndRow(10, $row)->getValue());
                    $harga = trim($worksheet->getCellByColumnAndRow(11, $row)->getValue());
                    $qty = trim($worksheet->getCellByColumnAndRow(12, $row)->getValue());
                    $bruto = trim($worksheet->getCellByColumnAndRow(13, $row)->getValue());
                    $disc_cabang = trim($worksheet->getCellByColumnAndRow(14, $row)->getValue());
                    $rp_cabang = trim($worksheet->getCellByColumnAndRow(15, $row)->getValue());
                    $disc_prinsipal = trim($worksheet->getCellByColumnAndRow(16, $row)->getValue());
                    $rp_prinsipal = trim($worksheet->getCellByColumnAndRow(17, $row)->getValue());
                    $disc_xtra = trim($worksheet->getCellByColumnAndRow(18, $row)->getValue());
                    $rp_xtra = trim($worksheet->getCellByColumnAndRow(19, $row)->getValue());
                    $disc_cash = trim($worksheet->getCellByColumnAndRow(20, $row)->getValue());
                    $rp_cash = trim($worksheet->getCellByColumnAndRow(21, $row)->getValue());
                    $netto = trim($worksheet->getCellByColumnAndRow(22, $row)->getValue());


                    $data = [
                        'siteid' => $siteid,
                        'nosales' => $nosales,
                        'tanggal_sales' => $tanggal_sales,
                        'salesmanid' => $salesmanid,
                        'nama_salesman' => $nama_salesman,
                        'customerid' => $customerid,
                        'nama_customer' => $nama_customer,
                        'productid' => $productid,
                        'product_descr' => $product_descr,
                        'flag_retur' => $flag_retur,
                        'flag_bonus' => $flag_bonus,
                        'harga' => $harga,
                        'qty' => $qty,
                        'bruto' => str_replace(',','',$bruto),
                        'disc_cabang' => $disc_cabang,
                        'rp_cabang' => $rp_cabang,
                        'disc_prinsipal' => $disc_prinsipal,
                        'rp_prinsipal' => $rp_prinsipal,
                        'disc_xtra' => $disc_xtra,
                        'rp_xtra' => $rp_xtra,
                        'disc_cash' => $disc_cash,
                        'rp_cash' => $rp_cash,
                        'netto' => str_replace(',','',$netto),
                        'signature' => $signature,
                        'created_at' => $created_at,
                        'created_by' => $this->session->userdata('id')
                    ];

                    $this->db->insert('management_raw.raw_pangkalanbun',$data);
                }
            }
        }else{

        };

        $get_count = "select count(*) as count from management_raw.raw_pangkalanbun a where a.signature = '$signature'";
        $count = $this->db->query($get_count)->row()->count;

        $get_omzet = "select sum(a.bruto) as omzet_raw from management_raw.raw_pangkalanbun a where a.signature = '$signature'";
        $omzet_raw = $this->db->query($get_omzet)->row()->omzet_raw;

        $upload = [
            'site_code'     => 'PBNP9',
            'filename'      => $filename,
            'signature'     => $signature,
            'count_raw'      => $count,
            'omzet_raw'     => $omzet_raw,
            'type_file'      => 'raw_sales',
            'bulan'         => $bulan_from_input,
            'tahun'         => $tahun_from_input,
            'created_at'    => $created_at,
            'created_by'    => $this->session->userdata('id')
        ];
        $this->db->insert('management_raw.log_upload', $upload);

        redirect('management_raw/import_pangkalanbun_draft/'.$signature);

    }

    public function import_pangkalanbun_draft($signature){
        $get_omzet = "select sum(a.bruto) as omzet_raw from management_raw.raw_pangkalanbun a where a.signature = '$signature'";
        $omzet_raw = $this->db->query($get_omzet)->row()->omzet_raw;

        $data = [
            'title' => 'Management Raw / Preview Import data pangkalanbun',
            'id' => $this->session->userdata('id'),
            'get_raw_draft' => $this->model_management_raw->get_raw_draft_pangkalanbun('PBNP9', $signature),
            'omzet_raw' => $omzet_raw,
            'url' => 'management_raw/proses_mapping_pangkalanbun',
            'signature' => $signature
        ];
        $this->load->view('management_raw/header');
        $this->load->view('management_raw/draft_pangkalanbun', $data);
        $this->load->view('mes/footer');
    }    

    public function proses_mapping_pangkalanbun(){

        $signature = $this->input->post('signature');
        $status_closing = $this->input->post('status_closing');
        $id_imports = $this->input->post('options');

        $update_kodeproduk = $this->model_management_raw->update_kodeproduk_pangkalanbun('PBNP9', $signature);
        if ($update_kodeproduk) {
            echo "<br><center><i>fix kodeproduk ... </i></b><br>";
        }

        $inner_kodeproduk = $this->model_management_raw->inner_kodeproduk_pangkalanbun('PBNP9', $signature);
        if ($inner_kodeproduk) {
            echo "<br><center><i>sukses memfilter produk khusus mpm / membuang produk selain mpm ...</i></b><br>";
        }

        $update_namaprod = $this->model_management_raw->update_namaprod_pangkalanbun('PBNP9', $signature);
        if ($update_namaprod) {
            echo "<br><center><i>sukses mengupdate namaproduk ...</i></b><br>";
        }
        
        $update_branch = $this->model_management_raw->update_branch('PBNP9', $signature);
        if ($update_branch) {
            echo "<br><center><i>sukses mengupdate branch_name dan nama_comp ...</i></b><br>";
        }

        $update_tanggal = $this->model_management_raw->update_tanggal_pangkalanbun('PBNP9', $signature);                       
        if ($update_tanggal) {
            echo "<br><center><i>sukses memperbaiki format tanggal ...</i></b><br>";
        }

        $prepare_tblang_bantu = $this->model_management_raw->prepare_tblang_bantu('PBNP9', $signature);
        if ($prepare_tblang_bantu) {
            echo "<br><center><i>prepare_tblang_bantu done ...</i></b><br>";
        }

        $delete_tabel = $this->model_management_raw->delete_tabel('PBNP9', $signature);
        if ($delete_tabel) {
            echo "<br><center><i>delete tabel done ...</i></b><br>";
        }

        $insert_fi = $this->model_management_raw->insert_fi_pangkalanbun('PBNP9', $signature);
        if ($insert_fi) {
            echo "<br><center><i>insert fi (sales) done ...</i></b><br>";
        }

        $insert_tblang_bantu = $this->model_management_raw->insert_tblang_bantu_banjarmasin('PBNP9', $signature);
        if ($insert_tblang_bantu) {
            echo "<br><center><i>insert tblang bantu done ...</i></b><br>";
        }

        $update_tblang_bantu = $this->model_management_raw->update_tblang_bantu('PBNP9', $signature);
        if ($update_tblang_bantu) {
            echo "<br><center><i>update_ tblang bantu done ...</i></b><br>";
        }

        $insert_tblang = $this->model_management_raw->insert_tblang_banjarmasin('PBNP9', $signature);
        if ($insert_tblang) {
            echo "<br><center><i>insert tblang done ...</i></b><br>";
        }

        $update_fi = $this->model_management_raw->update_fi('PBNP9', $signature);
        if ($update_fi) {
            echo "<br><center><i>update fi done ...</i></b><br>";
        }

        $insert_tabsales = $this->model_management_raw->insert_tabsales_kendari('PBNP9', $signature);
        if ($insert_tabsales) {
            echo "<br><center><i>insert tabsales done ...</i></b><br>";
        }

        $insert_tbkota = $this->model_management_raw->insert_tbkota_pangkalanbun('PBNP9', $signature);
        if ($insert_tbkota) {
            echo "<br><center><i>insert tbkota done ...</i></b><br>";
        }        

        $get_count = "select count(*) as count from management_raw.inner_raw_pangkalanbun a where a.signature = '$signature'";
        $count = $this->db->query($get_count)->row()->count;

        $get_bulan = $this->model_management_raw->get_log_upload_by_signature($signature)->row()->bulan;
        $get_tahun = $this->model_management_raw->get_log_upload_by_signature($signature)->row()->tahun;

        $get_omzet = $this->model_management_raw->get_omzet_web($get_tahun, $get_bulan, 'PBNP9')->row()->omzet;

        $update = [
            'count_mapping' => $count,
            'omzet_web'     => $get_omzet,
        ];
        $this->db->where('signature', $signature);
        $this->db->update('management_raw.log_upload', $update);

        $insert_mpm_upload = $this->model_management_raw->insert_mpm_upload_pangkalanbun('PBNP9', $signature, $get_omzet, $status_closing);
        if ($insert_mpm_upload) {
            echo "<br><center><i>insert mpm.upload done ...</i></b><br>";
        }
        
        echo "<br><center><i>dalam 5 detik anda akan di redirect ke halaman awal ...</i></b><br>";
        header('Refresh: 5; URL='.base_url().'management_raw/pangkalanbun');    
    }

    public function template_customer_pangkalanbun(){        
        $query = "
            select  '' as Kategori, '' as Nama_Site, '' as Regional, '' as Customer_Id, '' as Maping_ULI, '' as Maping_ND6, '' as maping_warung_pintar, '' as maping_pbf, '' as Prefix, '' as Nama_Customer, '' as Alamat, '' as Tipe_Bayar, '' as TOP, '' as Status_Konsinyasi, '' as Status_Fuguh, '' as Kelurahan_Id, '' as Nama_Kelurahan, '' as Kecamatan_Id, '' as Nama_Kecamatan, '' as Kota_Id, '' as Nama_Kota, '' as Propinsi_Id, '' as Nama_Propinsi, '' as Kode_Pos, '' as Telp, '' as Fax, '' as Email, '' as Head_Office_Id, '' as Nama_Head_Office, '' as Company_Id, '' as Nama_Company, '' as Branch_Id, '' as Nama_Branch_Office, '' as Site_Id, '' as Segment_Id, '' as Nama_Segment, '' as Type_Id, '' as Nama_Type, '' as Class_Id, '' as Class, '' as Spot_Id, '' as No_KTP, '' as Kartu_Keluarga, '' as PLN, '' as Nama_Penghubung, '' as Alamat_Penghubung, '' as Telp_Penghubung, '' as Hubungan, '' as Latitude, '' as Longitude, '' as Member, '' as Black_List, '' as Aktif, '' as Show_Alamat_PKP, '' as Date_Create, '' as pbf_izin_no_tdp_tgl, '' as pbf_izin_no_tdp, '' as pbf_izin_no_siup_tgl, '' as pbf_izin_no_siup, '' as pbf_izin_no_sito_tgl, '' as pbf_izin_no_sito, '' as pbf_izin_no_sipa_tgl, '' as pbf_izin_no_sipa, '' as pbf_izin_no_sia_tgl, '' as pbf_izin_no_sia, '' as pbf_izin_no_nib_tgl, '' as pbf_izin_no_nib, '' as pbf_izin_no_cdob_tgl, '' as pbf_izin_no_cdob, '' as pbf_asis_apoteker_tgl_sipa, '' as pbf_asis_apoteker_tgl_lahir, '' as pbf_asis_apoteker_telpon, '' as pbf_asis_apoteker_no_sipa, '' as pbf_asis_apoteker_no_ktp, '' as pbf_asis_apoteker_email, '' as pbf_asis_apoteker_nama, '' as pbf_asis_apoteker_alamat, '' as pbf_apoteker_tgl_sipa, '' as pbf_apoteker_tgl_lahir, '' as pbf_apoteker_telpon, '' as pbf_apoteker_no_sipa, '' as pbf_apoteker_no_ktp, '' as pbf_apoteker_nama, '' as pbf_apoteker_alamat, '' as pbf_apoteker_email
        ";

        $hsl = $this->db->query($query);
        query_to_csv($hsl,TRUE,'Template_Customer_Pangkalanbun.csv');
    }

    public function customer_pangkalanbun(){
        $data = [
            'title' => 'Customer pangkalanbun',
            'id' => $this->session->userdata('id'),
            'url_import' => 'management_raw/import_customer_pangkalanbun',
            'get_customer' => $this->model_management_raw->get_customer_pangkalanbun('PBNP9')
        ];
        $this->load->view('management_raw/header');
        $this->load->view('management_raw/customer_pangkalanbun', $data);
        $this->load->view('mes/footer');
    }

    public function import_customer_pangkalanbun(){
        $bulan = $this->input->post('bulan');
        // echo "bulan : ".$bulan;

        if (!is_dir('./assets/uploads/management_raw/import/')) {
            @mkdir('./assets/uploads/management_raw/import/', 0777);
        }

        $this->load->library('upload'); // Load librari upload        
        $config['upload_path'] = './assets/uploads/management_raw/import/';
        // $config['allowed_types'] = 'xls|xlsx|csv';
        $config['allowed_types'] = '*';    
        $config['max_size']  = '2048000';
        $config['overwrite'] = true;
        $config['encrypt_name'] = TRUE;
        $this->upload->initialize($config);

        if ($this->upload->do_upload('file')) 
        {

            $upload_data = $this->upload->data();
            $filename = $upload_data['orig_name'];

            $this->load->library('excel');
            $object = PHPExcel_IOFactory::load("assets/uploads/management_raw/import/$filename");

            $jumlahSheet = $object->getSheetCount();
            if ($jumlahSheet > 1) {
                echo "jumlah_sheet : ".$jumlahSheet;
                echo "<script>alert('upload file gagal karena file mempunyai lebih dari 1 sheet'); </script>";
                redirect('management_raw/customer_pangkalanbun','refresh');
            }

            $created_at = $this->model_outlet_transaksi->timezone();
            $signature = md5($this->model_outlet_transaksi->timezone());

            foreach ($object->getWorksheetIterator() as $worksheet) {

                $highestRow = $worksheet->getHighestRow();
                $highestColumn = $worksheet->getHighestColumn();

                for ($row = 2; $row <= $highestRow; $row++) {          
                
                    $customer_id = trim($worksheet->getCellByColumnAndRow(3, $row)->getValue());
                    $customer_id_nd6 = trim($worksheet->getCellByColumnAndRow(4, $row)->getValue());
                    $nama_customer = trim($worksheet->getCellByColumnAndRow(9, $row)->getValue());
                    $alamat = trim($worksheet->getCellByColumnAndRow(10, $row)->getValue());
                    $kode_type = trim($worksheet->getCellByColumnAndRow(36, $row)->getValue());
                    $nama_type = trim($worksheet->getCellByColumnAndRow(37, $row)->getValue());
                    $segment = trim($worksheet->getCellByColumnAndRow(35, $row)->getValue());
                    $kode_class = trim($worksheet->getCellByColumnAndRow(38, $row)->getValue());
                    $nama_class = trim($worksheet->getCellByColumnAndRow(39, $row)->getValue());
                    $kode_kota = trim($worksheet->getCellByColumnAndRow(19, $row)->getValue());
                    $nama_kota = trim($worksheet->getCellByColumnAndRow(20, $row)->getValue());
                    $kode_kecamatan = trim($worksheet->getCellByColumnAndRow(17, $row)->getValue());
                    $nama_kecamatan = trim($worksheet->getCellByColumnAndRow(18, $row)->getValue());
                    $kode_kelurahan = trim($worksheet->getCellByColumnAndRow(15, $row)->getValue());
                    $nama_kelurahan = trim($worksheet->getCellByColumnAndRow(16, $row)->getValue());

                    if ($customer_id_nd6 != null || $customer_id_nd6 != '') {
                        $cek = "select * from management_raw.raw_customer_pangkalanbun a where a.customer_id = '$customer_id' and a.customer_id_nd6 = '$customer_id_nd6'";
                        if($this->db->query($cek)->num_rows() > 0){
    
                            // echo "update";
                            $data = [
                                'customer_id' => $customer_id,
                                'nama_customer' => $nama_customer,
                                'alamat' => $alamat,
                                'kode_type' => $kode_type,
                                'nama_type' => $nama_type,
                                'segment' => $segment,
                                'kode_class' => $kode_class,
                                'nama_class' => $nama_class,
                                'kode_kota' => $kode_kota,
                                'nama_kota' => $nama_kota,
                                'kode_kecamatan' => $kode_kecamatan,
                                'nama_kecamatan' => $nama_kecamatan,
                                'kode_kelurahan' => $kode_kelurahan,
                                'nama_kelurahan' => $nama_kelurahan,
                                'signature' => $signature,
                                'created_at' => $created_at,
                                'created_by' => $this->session->userdata('id')
                            ];
    
                            $this->db->where('customer_id', $customer_id);
                            $this->db->where('customer_id_nd6', $customer_id_nd6);
                            $this->db->update('management_raw.raw_customer_pangkalanbun', $data);
    
    
                        }else{
                            // echo "insert";
                            $data = [
                                'customer_id' => $customer_id,
                                'customer_id_nd6' => $customer_id_nd6,
                                'nama_customer' => $nama_customer,
                                'alamat' => $alamat,
                                'kode_type' => $kode_type,
                                'nama_type' => $nama_type,
                                'segment' => $segment,
                                'kode_class' => $kode_class,
                                'nama_class' => $nama_class,
                                'kode_kota' => $kode_kota,
                                'nama_kota' => $nama_kota,
                                'kode_kecamatan' => $kode_kecamatan,
                                'nama_kecamatan' => $nama_kecamatan,
                                'kode_kelurahan' => $kode_kelurahan,
                                'nama_kelurahan' => $nama_kelurahan,
                                'signature' => $signature,
                                'created_at' => $created_at,
                                'created_by' => $this->session->userdata('id')
                            ];
        
                            $this->db->insert('management_raw.raw_customer_pangkalanbun',$data);
    
                        }
                    } 
                }
            }
        }

        $update = "
            update management_raw.raw_customer_pangkalanbun a 
            set a.nama_type = (
                select b.nama_type
                from mpm.tbl_bantu_type b
                where a.kode_type = b.kode_type
            ), a.sektor = (
                select b.sektor
                from mpm.tbl_bantu_type b
                where a.kode_type = b.kode_type
            ), a.segment = (
                select b.segment
                from mpm.tbl_bantu_type b
                where a.kode_type = b.kode_type
            ), a.nama_class = (
                select c.jenis
                from mpm.tbl_tabsalur c 
                where a.kode_class = c.kode
            ), a.group_class = (
                select c.`group`
                from mpm.tbl_tabsalur c 
                where a.kode_class = c.kode
            )
        ";
        $proses_update = $this->db->query($update);

        // echo "<pre>";
        // print_r($update);
        // echo "proses_update : ".$proses_update;
        // echo "</pre>";

        $get_count = "select count(*) as count from management_raw.raw_customer_pangkalanbun a where a.signature = '$signature'";
        $count = $this->db->query($get_count)->row()->count;

        $upload = [
            'site_code'     => 'PBNP9',
            'signature'     => $signature,
            'filename'      => $filename,
            'type_file'      => 'raw_customer',
            'count_raw'      => $count,
            'created_at'    => $created_at,
            'created_by'    => $this->session->userdata('id')
        ];
        $this->db->insert('management_raw.log_upload', $upload);

        redirect('management_raw/customer_pangkalanbun/');
    }

    public function template_sampit(){        
        $query = "
            SELECT '' as siteid, '' as nosales, '' as tanggal_sales, '' as salesmanid, '' as nama_salesman, '' as customerid, '' as nama_customer, '' as productid, '' as product_Descr, '' as flag_retur, '' as flag_bonus, '' as harga, '' as qty, '' as bruto, '' as disc_cabang, '' as rp_cabang, '' as disc_prinsipal, '' as rp_prinsipal, '' as disc_xtra, '' as rp_xtra, '' as disc_cash, '' as rp_cash, '' as netto

        ";

        $hsl = $this->db->query($query);
        query_to_csv($hsl,TRUE,'Template_sampit.csv');
    }

    public function sampit(){

        $data = [
            'title'     => 'Management Raw / Import data sampit',
            'id'        => $this->session->userdata('id'),
            'url_import'=> 'management_raw/import_sampit',
            'get_log_upload'    => $this->model_management_raw->get_log_upload('SPTU4')
        ];
        $this->load->view('management_raw/header');
        $this->load->view('management_raw/sampit', $data);
        $this->load->view('mes/footer');
    }

    public function import_sampit(){
        // $bulan = $this->input->post('bulan');
        // echo "bulan : ".$bulan;

        $bulan_from_input = substr($this->input->post('bulan'), 5, 2);
        $tahun_from_input = substr($this->input->post('bulan'), 0, 4);

        $cek_last_upload = "
            select *
            from mpm.upload a 
            where a.userid = 546 
            ORDER BY a.id desc
        ";
        $tahun_last_upload = $this->db->query($cek_last_upload)->row()->tahun;
        $bulan_last_upload = $this->db->query($cek_last_upload)->row()->bulan;
        $closing_last_upload = $this->db->query($cek_last_upload)->row()->status_closing;

        if ($closing_last_upload == 1) {
            $tanggal_seharusnya = date('Y-m-d', strtotime('+1 month', strtotime($tahun_last_upload.'-'.$bulan_last_upload.'-01')));           
            $bulan_seharusnya = date('m', strtotime($tanggal_seharusnya));
            $tahun_seharusnya = date('Y', strtotime($tanggal_seharusnya));

            if ($tahun_from_input === $tahun_seharusnya && $bulan_from_input === $bulan_seharusnya) {
                
            }else{
                echo "<br><hr>Tanggal yang anda pilih adalah :<br><br>";
                // echo "<hr>";
                echo "tahun : ".$tahun_from_input."<br>";
                echo "bulan : ".$bulan_from_input."<br><br>";
                echo "<hr>";

                echo "<br>Tanggal upload terakhir adalah :<br><br>";
                // echo "<hr>";
                echo "tahun_last_upload : ".$tahun_last_upload."<br>";
                echo "bulan_last_upload : ".$bulan_last_upload."<br>";
                echo "closing_last_upload : ".$closing_last_upload."<br><br>";
                echo "<hr>";
                
                echo "<h3>Result</h3>";
                echo "Data ditolak !!<br><br>";
                echo "Anda hanya diijinkan mengupload data untuk <b>tahun : $tahun_seharusnya</b>, dan <b>bulan : $bulan_seharusnya</b><br>";

                echo "<br>anda akan di redirect ke menu awal dalam 10 detik";
                header('Refresh: 10; URL='.base_url().'management_raw/sampit');
                die;
            }

        }else{

            $tanggal_seharusnya = date('Y-m-d', strtotime('+0 month', strtotime($tahun_last_upload.'-'.$bulan_last_upload.'-01')));           
            $bulan_seharusnya = date('m', strtotime($tanggal_seharusnya));
            $tahun_seharusnya = date('Y', strtotime($tanggal_seharusnya));

            if ($tahun_from_input === $tahun_seharusnya && $bulan_from_input === $bulan_seharusnya) {
                // echo "diijinkan";
            }else{
                echo "<br><hr>Tanggal yang anda pilih adalah :<br><br>";
                echo "tahun : ".$tahun_from_input."<br>";
                echo "bulan : ".$bulan_from_input."<br><br>";
                echo "<hr>";


                echo "<br>Tanggal upload terakhir adalah :<br><br>";
                echo "tahun_last_upload : ".$tahun_last_upload."<br>";
                echo "bulan_last_upload : ".$bulan_last_upload."<br>";
                echo "closing_last_upload : ".$closing_last_upload."<br><br>";
                echo "<hr>";
                
                echo "<h3>Result</h3>";
                echo "Data ditolak !!<br><br>";
                echo "Anda hanya diijinkan mengupload data untuk <b>tahun : $tahun_seharusnya</b>, dan <b>bulan : $bulan_seharusnya</b><br>";

                echo "<br>anda akan di redirect ke menu awal dalam 10 detik";
                header('Refresh: 10; URL='.base_url().'management_raw/sampit');
                die;
            }

        }

        if (!is_dir('./assets/uploads/management_raw/import/')) {
            @mkdir('./assets/uploads/management_raw/import/', 0777);
        }

        $this->load->library('upload'); // Load librari upload        
        $config['upload_path'] = './assets/uploads/management_raw/import/';
        // $config['allowed_types'] = 'xls|xlsx|csv';
        $config['allowed_types'] = '*';    
        $config['max_size']  = '2048000';
        $config['overwrite'] = true;
        $config['encrypt_name'] = TRUE;
        $this->upload->initialize($config);

        if ($this->upload->do_upload('file')) 
        {

            $upload_data = $this->upload->data();
            $filename = $upload_data['orig_name'];

            $this->load->library('excel');
            $object = PHPExcel_IOFactory::load("assets/uploads/management_raw/import/$filename");

            $jumlahSheet = $object->getSheetCount();
            if ($jumlahSheet > 1) {
                echo "jumlah_sheet : ".$jumlahSheet;
                echo "<script>alert('upload file gagal karena file mempunyai lebih dari 1 sheet'); </script>";
                redirect('management_raw/sampit','refresh');
            }
            

            $highestColumm = $object->setActiveSheetIndex(0)->getHighestColumn();
            // var_dump($highestColumm);die;
            if ($highestColumm != 'W') {
                echo "<script>alert('upload file gagal karena column tidak sesuai'); </script>";
                redirect('management_raw/sampit','refresh');
            }

            // die;

            $created_at = $this->model_outlet_transaksi->timezone();
            $signature = md5($this->model_outlet_transaksi->timezone());

            foreach ($object->getWorksheetIterator() as $worksheet) {

                $highestRow = $worksheet->getHighestRow();
                $highestColumn = $worksheet->getHighestColumn();

                for ($row = 2; $row <= $highestRow; $row++) {          
                    $siteid = trim($worksheet->getCellByColumnAndRow(0, $row)->getValue());
                    $nosales = trim($worksheet->getCellByColumnAndRow(1, $row)->getValue());
                    $tanggal_sales = trim($worksheet->getCellByColumnAndRow(2, $row)->getValue());
                    $salesmanid = trim($worksheet->getCellByColumnAndRow(3, $row)->getValue());
                    $nama_salesman = trim($worksheet->getCellByColumnAndRow(4, $row)->getValue());
                    $customerid = trim($worksheet->getCellByColumnAndRow(5, $row)->getValue());
                    $nama_customer = trim($worksheet->getCellByColumnAndRow(6, $row)->getValue());
                    $productid = trim($worksheet->getCellByColumnAndRow(7, $row)->getValue());
                    $product_descr = trim($worksheet->getCellByColumnAndRow(8, $row)->getValue());
                    $flag_retur = trim($worksheet->getCellByColumnAndRow(9, $row)->getValue());
                    $flag_bonus = trim($worksheet->getCellByColumnAndRow(10, $row)->getValue());
                    $harga = trim($worksheet->getCellByColumnAndRow(11, $row)->getValue());
                    $qty = trim($worksheet->getCellByColumnAndRow(12, $row)->getValue());
                    $bruto = trim($worksheet->getCellByColumnAndRow(13, $row)->getValue());
                    $disc_cabang = trim($worksheet->getCellByColumnAndRow(14, $row)->getValue());
                    $rp_cabang = trim($worksheet->getCellByColumnAndRow(15, $row)->getValue());
                    $disc_prinsipal = trim($worksheet->getCellByColumnAndRow(16, $row)->getValue());
                    $rp_prinsipal = trim($worksheet->getCellByColumnAndRow(17, $row)->getValue());
                    $disc_xtra = trim($worksheet->getCellByColumnAndRow(18, $row)->getValue());
                    $rp_xtra = trim($worksheet->getCellByColumnAndRow(19, $row)->getValue());
                    $disc_cash = trim($worksheet->getCellByColumnAndRow(20, $row)->getValue());
                    $rp_cash = trim($worksheet->getCellByColumnAndRow(21, $row)->getValue());
                    $netto = trim($worksheet->getCellByColumnAndRow(22, $row)->getValue());


                    $data = [
                        'siteid' => $siteid,
                        'nosales' => $nosales,
                        'tanggal_sales' => $tanggal_sales,
                        'salesmanid' => $salesmanid,
                        'nama_salesman' => $nama_salesman,
                        'customerid' => $customerid,
                        'nama_customer' => $nama_customer,
                        'productid' => $productid,
                        'product_descr' => $product_descr,
                        'flag_retur' => $flag_retur,
                        'flag_bonus' => $flag_bonus,
                        'harga' => $harga,
                        'qty' => $qty,
                        'bruto' => str_replace(',','',$bruto),
                        'disc_cabang' => $disc_cabang,
                        'rp_cabang' => $rp_cabang,
                        'disc_prinsipal' => $disc_prinsipal,
                        'rp_prinsipal' => $rp_prinsipal,
                        'disc_xtra' => $disc_xtra,
                        'rp_xtra' => $rp_xtra,
                        'disc_cash' => $disc_cash,
                        'rp_cash' => $rp_cash,
                        'netto' => str_replace(',','',$netto),
                        'signature' => $signature,
                        'created_at' => $created_at,
                        'created_by' => $this->session->userdata('id')
                    ];

                    $this->db->insert('management_raw.raw_sampit',$data);
                }
            }
        }else{

        };

        $get_count = "select count(*) as count from management_raw.raw_sampit a where a.signature = '$signature'";
        $count = $this->db->query($get_count)->row()->count;

        $get_omzet = "select sum(a.bruto) as omzet_raw from management_raw.raw_sampit a where a.signature = '$signature'";
        $omzet_raw = $this->db->query($get_omzet)->row()->omzet_raw;

        $upload = [
            'site_code'     => 'SPTU4',
            'filename'      => $filename,
            'signature'     => $signature,
            'count_raw'      => $count,
            'omzet_raw'     => $omzet_raw,
            'type_file'      => 'raw_sales',
            'bulan'         => $bulan_from_input,
            'tahun'         => $tahun_from_input,
            'created_at'    => $created_at,
            'created_by'    => $this->session->userdata('id')
        ];
        $this->db->insert('management_raw.log_upload', $upload);

        redirect('management_raw/import_sampit_draft/'.$signature);

    }

    public function import_sampit_draft($signature){
        $get_omzet = "select sum(a.bruto) as omzet_raw from management_raw.raw_sampit a where a.signature = '$signature'";
        $omzet_raw = $this->db->query($get_omzet)->row()->omzet_raw;

        $data = [
            'title' => 'Management Raw / Preview Import data sampit',
            'id' => $this->session->userdata('id'),
            'get_raw_draft' => $this->model_management_raw->get_raw_draft_pangkalanbun('SPTU4', $signature),
            'omzet_raw' => $omzet_raw,
            'url' => 'management_raw/proses_mapping_sampit',
            'signature' => $signature
        ];
        $this->load->view('management_raw/header');
        $this->load->view('management_raw/draft_sampit', $data);
        $this->load->view('mes/footer');
    } 

    public function proses_mapping_sampit(){

        $signature = $this->input->post('signature');
        $status_closing = $this->input->post('status_closing');
        $id_imports = $this->input->post('options');

        $update_kodeproduk = $this->model_management_raw->update_kodeproduk_pangkalanbun('SPTU4', $signature);
        if ($update_kodeproduk) {
            echo "<br><center><i>fix kodeproduk ... </i></b><br>";
        }

        $inner_kodeproduk = $this->model_management_raw->inner_kodeproduk_pangkalanbun('SPTU4', $signature);
        if ($inner_kodeproduk) {
            echo "<br><center><i>sukses memfilter produk khusus mpm / membuang produk selain mpm ...</i></b><br>";
        }

        $update_namaprod = $this->model_management_raw->update_namaprod_pangkalanbun('SPTU4', $signature);
        if ($update_namaprod) {
            echo "<br><center><i>sukses mengupdate namaproduk ...</i></b><br>";
        }
        
        $update_branch = $this->model_management_raw->update_branch('SPTU4', $signature);
        if ($update_branch) {
            echo "<br><center><i>sukses mengupdate branch_name dan nama_comp ...</i></b><br>";
        }

        $update_tanggal = $this->model_management_raw->update_tanggal_pangkalanbun('SPTU4', $signature);                       
        if ($update_tanggal) {
            echo "<br><center><i>sukses memperbaiki format tanggal ...</i></b><br>";
        }

        $prepare_tblang_bantu = $this->model_management_raw->prepare_tblang_bantu('SPTU4', $signature);
        if ($prepare_tblang_bantu) {
            echo "<br><center><i>prepare_tblang_bantu done ...</i></b><br>";
        }

        $delete_tabel = $this->model_management_raw->delete_tabel('SPTU4', $signature);
        if ($delete_tabel) {
            echo "<br><center><i>delete tabel done ...</i></b><br>";
        }

        $insert_fi = $this->model_management_raw->insert_fi_sampit('SPTU4', $signature);
        if ($insert_fi) {
            echo "<br><center><i>insert fi (sales) done ...</i></b><br>";
        }

        $insert_tblang_bantu = $this->model_management_raw->insert_tblang_bantu_banjarmasin('SPTU4', $signature);
        if ($insert_tblang_bantu) {
            echo "<br><center><i>insert tblang bantu done ...</i></b><br>";
        }

        $update_tblang_bantu = $this->model_management_raw->update_tblang_bantu('SPTU4', $signature);
        if ($update_tblang_bantu) {
            echo "<br><center><i>update_ tblang bantu done ...</i></b><br>";
        }

        $insert_tblang = $this->model_management_raw->insert_tblang_banjarmasin('SPTU4', $signature);
        if ($insert_tblang) {
            echo "<br><center><i>insert tblang done ...</i></b><br>";
        }

        $update_fi = $this->model_management_raw->update_fi('SPTU4', $signature);
        if ($update_fi) {
            echo "<br><center><i>update fi done ...</i></b><br>";
        }

        $insert_tabsales = $this->model_management_raw->insert_tabsales_kendari('SPTU4', $signature);
        if ($insert_tabsales) {
            echo "<br><center><i>insert tabsales done ...</i></b><br>";
        }

        $insert_tbkota = $this->model_management_raw->insert_tbkota_pangkalanbun('SPTU4', $signature);
        if ($insert_tbkota) {
            echo "<br><center><i>insert tbkota done ...</i></b><br>";
        }        

        $get_count = "select count(*) as count from management_raw.inner_raw_sampit a where a.signature = '$signature'";
        $count = $this->db->query($get_count)->row()->count;

        $get_bulan = $this->model_management_raw->get_log_upload_by_signature($signature)->row()->bulan;
        $get_tahun = $this->model_management_raw->get_log_upload_by_signature($signature)->row()->tahun;

        $get_omzet = $this->model_management_raw->get_omzet_web($get_tahun, $get_bulan, 'SPTU4')->row()->omzet;

        $update = [
            'count_mapping' => $count,
            'omzet_web'     => $get_omzet,
        ];
        $this->db->where('signature', $signature);
        $this->db->update('management_raw.log_upload', $update);

        $insert_mpm_upload = $this->model_management_raw->insert_mpm_upload_pangkalanbun('SPTU4', $signature, $get_omzet, $status_closing);
        if ($insert_mpm_upload) {
            echo "<br><center><i>insert mpm.upload done ...</i></b><br>";
        }
        
        echo "<br><center><i>dalam 5 detik anda akan di redirect ke halaman awal ...</i></b><br>";
        header('Refresh: 5; URL='.base_url().'management_raw/sampit');    
    }

    public function template_customer_sampit(){        
        $query = "
            select  '' as Kategori, '' as Nama_Site, '' as Regional, '' as Customer_Id, '' as Maping_ULI, '' as Maping_ND6, '' as maping_warung_pintar, '' as maping_pbf, '' as Prefix, '' as Nama_Customer, '' as Alamat, '' as Tipe_Bayar, '' as TOP, '' as Status_Konsinyasi, '' as Status_Fuguh, '' as Kelurahan_Id, '' as Nama_Kelurahan, '' as Kecamatan_Id, '' as Nama_Kecamatan, '' as Kota_Id, '' as Nama_Kota, '' as Propinsi_Id, '' as Nama_Propinsi, '' as Kode_Pos, '' as Telp, '' as Fax, '' as Email, '' as Head_Office_Id, '' as Nama_Head_Office, '' as Company_Id, '' as Nama_Company, '' as Branch_Id, '' as Nama_Branch_Office, '' as Site_Id, '' as Segment_Id, '' as Nama_Segment, '' as Type_Id, '' as Nama_Type, '' as Class_Id, '' as Class, '' as Spot_Id, '' as No_KTP, '' as Kartu_Keluarga, '' as PLN, '' as Nama_Penghubung, '' as Alamat_Penghubung, '' as Telp_Penghubung, '' as Hubungan, '' as Latitude, '' as Longitude, '' as Member, '' as Black_List, '' as Aktif, '' as Show_Alamat_PKP, '' as Date_Create, '' as pbf_izin_no_tdp_tgl, '' as pbf_izin_no_tdp, '' as pbf_izin_no_siup_tgl, '' as pbf_izin_no_siup, '' as pbf_izin_no_sito_tgl, '' as pbf_izin_no_sito, '' as pbf_izin_no_sipa_tgl, '' as pbf_izin_no_sipa, '' as pbf_izin_no_sia_tgl, '' as pbf_izin_no_sia, '' as pbf_izin_no_nib_tgl, '' as pbf_izin_no_nib, '' as pbf_izin_no_cdob_tgl, '' as pbf_izin_no_cdob, '' as pbf_asis_apoteker_tgl_sipa, '' as pbf_asis_apoteker_tgl_lahir, '' as pbf_asis_apoteker_telpon, '' as pbf_asis_apoteker_no_sipa, '' as pbf_asis_apoteker_no_ktp, '' as pbf_asis_apoteker_email, '' as pbf_asis_apoteker_nama, '' as pbf_asis_apoteker_alamat, '' as pbf_apoteker_tgl_sipa, '' as pbf_apoteker_tgl_lahir, '' as pbf_apoteker_telpon, '' as pbf_apoteker_no_sipa, '' as pbf_apoteker_no_ktp, '' as pbf_apoteker_nama, '' as pbf_apoteker_alamat, '' as pbf_apoteker_email
        ";

        $hsl = $this->db->query($query);
        query_to_csv($hsl,TRUE,'Template_Customer_Sampit.csv');
    }

    public function customer_sampit(){
        $data = [
            'title' => 'Customer sampit',
            'id' => $this->session->userdata('id'),
            'url_import' => 'management_raw/import_customer_sampit',
            'get_customer' => $this->model_management_raw->get_customer_pangkalanbun('SPTU4')
        ];
        $this->load->view('management_raw/header');
        $this->load->view('management_raw/customer_sampit', $data);
        $this->load->view('mes/footer');
    }

    public function import_customer_sampit(){
        $bulan = $this->input->post('bulan');
        // echo "bulan : ".$bulan;

        if (!is_dir('./assets/uploads/management_raw/import/')) {
            @mkdir('./assets/uploads/management_raw/import/', 0777);
        }

        $this->load->library('upload'); // Load librari upload        
        $config['upload_path'] = './assets/uploads/management_raw/import/';
        // $config['allowed_types'] = 'xls|xlsx|csv';
        $config['allowed_types'] = '*';    
        $config['max_size']  = '2048000';
        $config['overwrite'] = true;
        $config['encrypt_name'] = TRUE;
        $this->upload->initialize($config);

        if ($this->upload->do_upload('file')) 
        {

            $upload_data = $this->upload->data();
            $filename = $upload_data['orig_name'];

            $this->load->library('excel');
            $object = PHPExcel_IOFactory::load("assets/uploads/management_raw/import/$filename");

            $jumlahSheet = $object->getSheetCount();
            if ($jumlahSheet > 1) {
                echo "jumlah_sheet : ".$jumlahSheet;
                echo "<script>alert('upload file gagal karena file mempunyai lebih dari 1 sheet'); </script>";
                redirect('management_raw/customer_sampit','refresh');
            }

            $created_at = $this->model_outlet_transaksi->timezone();
            $signature = md5($this->model_outlet_transaksi->timezone());

            foreach ($object->getWorksheetIterator() as $worksheet) {

                $highestRow = $worksheet->getHighestRow();
                $highestColumn = $worksheet->getHighestColumn();

                for ($row = 2; $row <= $highestRow; $row++) {          
                
                    $customer_id = trim($worksheet->getCellByColumnAndRow(3, $row)->getValue());
                    $customer_id_nd6 = trim($worksheet->getCellByColumnAndRow(4, $row)->getValue());
                    $nama_customer = trim($worksheet->getCellByColumnAndRow(9, $row)->getValue());
                    $alamat = trim($worksheet->getCellByColumnAndRow(10, $row)->getValue());
                    $kode_type = trim($worksheet->getCellByColumnAndRow(36, $row)->getValue());
                    $nama_type = trim($worksheet->getCellByColumnAndRow(37, $row)->getValue());
                    $segment = trim($worksheet->getCellByColumnAndRow(35, $row)->getValue());
                    $kode_class = trim($worksheet->getCellByColumnAndRow(38, $row)->getValue());
                    $nama_class = trim($worksheet->getCellByColumnAndRow(39, $row)->getValue());
                    $kode_kota = trim($worksheet->getCellByColumnAndRow(19, $row)->getValue());
                    $nama_kota = trim($worksheet->getCellByColumnAndRow(20, $row)->getValue());
                    $kode_kecamatan = trim($worksheet->getCellByColumnAndRow(17, $row)->getValue());
                    $nama_kecamatan = trim($worksheet->getCellByColumnAndRow(18, $row)->getValue());
                    $kode_kelurahan = trim($worksheet->getCellByColumnAndRow(15, $row)->getValue());
                    $nama_kelurahan = trim($worksheet->getCellByColumnAndRow(16, $row)->getValue());

                    if ($customer_id_nd6 != null || $customer_id_nd6 != '') {
                        $cek = "select * from management_raw.raw_customer_sampit a where a.customer_id = '$customer_id' and a.customer_id_nd6 = '$customer_id_nd6'";
                        if($this->db->query($cek)->num_rows() > 0){
    
                            // echo "update";
                            $data = [
                                'customer_id' => $customer_id,
                                'nama_customer' => $nama_customer,
                                'alamat' => $alamat,
                                'kode_type' => $kode_type,
                                'nama_type' => $nama_type,
                                'segment' => $segment,
                                'kode_class' => $kode_class,
                                'nama_class' => $nama_class,
                                'kode_kota' => $kode_kota,
                                'nama_kota' => $nama_kota,
                                'kode_kecamatan' => $kode_kecamatan,
                                'nama_kecamatan' => $nama_kecamatan,
                                'kode_kelurahan' => $kode_kelurahan,
                                'nama_kelurahan' => $nama_kelurahan,
                                'signature' => $signature,
                                'created_at' => $created_at,
                                'created_by' => $this->session->userdata('id')
                            ];
    
                            $this->db->where('customer_id', $customer_id);
                            $this->db->where('customer_id_nd6', $customer_id_nd6);
                            $this->db->update('management_raw.raw_customer_sampit', $data);
    
    
                        }else{
                            // echo "insert";
                            $data = [
                                'customer_id' => $customer_id,
                                'customer_id_nd6' => $customer_id_nd6,
                                'nama_customer' => $nama_customer,
                                'alamat' => $alamat,
                                'kode_type' => $kode_type,
                                'nama_type' => $nama_type,
                                'segment' => $segment,
                                'kode_class' => $kode_class,
                                'nama_class' => $nama_class,
                                'kode_kota' => $kode_kota,
                                'nama_kota' => $nama_kota,
                                'kode_kecamatan' => $kode_kecamatan,
                                'nama_kecamatan' => $nama_kecamatan,
                                'kode_kelurahan' => $kode_kelurahan,
                                'nama_kelurahan' => $nama_kelurahan,
                                'signature' => $signature,
                                'created_at' => $created_at,
                                'created_by' => $this->session->userdata('id')
                            ];
        
                            $this->db->insert('management_raw.raw_customer_sampit',$data);
    
                        }
                    } 
                }
            }
        }

        $update = "
            update management_raw.raw_customer_sampit a 
            set a.nama_type = (
                select b.nama_type
                from mpm.tbl_bantu_type b
                where a.kode_type = b.kode_type
            ), a.sektor = (
                select b.sektor
                from mpm.tbl_bantu_type b
                where a.kode_type = b.kode_type
            ), a.segment = (
                select b.segment
                from mpm.tbl_bantu_type b
                where a.kode_type = b.kode_type
            ), a.nama_class = (
                select c.jenis
                from mpm.tbl_tabsalur c 
                where a.kode_class = c.kode
            ), a.group_class = (
                select c.`group`
                from mpm.tbl_tabsalur c 
                where a.kode_class = c.kode
            )
        ";
        $proses_update = $this->db->query($update);

        // echo "<pre>";
        // print_r($update);
        // echo "proses_update : ".$proses_update;
        // echo "</pre>";

        $get_count = "select count(*) as count from management_raw.raw_customer_sampit a where a.signature = '$signature'";
        $count = $this->db->query($get_count)->row()->count;

        $upload = [
            'site_code'     => 'SPTU4',
            'signature'     => $signature,
            'filename'      => $filename,
            'type_file'      => 'raw_customer',
            'count_raw'      => $count,
            'created_at'    => $created_at,
            'created_by'    => $this->session->userdata('id')
        ];
        $this->db->insert('management_raw.log_upload', $upload);

        redirect('management_raw/customer_sampit/');
    }

    public function template_palangkaraya(){        
        $query = "
            SELECT '' as siteid, '' as nosales, '' as tanggal_sales, '' as salesmanid, '' as nama_salesman, '' as customerid, '' as nama_customer, '' as productid, '' as product_Descr, '' as flag_retur, '' as flag_bonus, '' as harga, '' as qty, '' as bruto, '' as disc_cabang, '' as rp_cabang, '' as disc_prinsipal, '' as rp_prinsipal, '' as disc_xtra, '' as rp_xtra, '' as disc_cash, '' as rp_cash, '' as netto

        ";

        $hsl = $this->db->query($query);
        query_to_csv($hsl,TRUE,'Template_palangkaraya.csv');
    }

    public function palangkaraya(){

        $data = [
            'title'     => 'Management Raw / Import data palangkaraya',
            'id'        => $this->session->userdata('id'),
            'url_import'=> 'management_raw/import_palangkaraya',
            'get_log_upload'    => $this->model_management_raw->get_log_upload('PKRP8')
        ];
        $this->load->view('management_raw/header');
        $this->load->view('management_raw/palangkaraya', $data);
        $this->load->view('mes/footer');
    }

    public function import_palangkaraya(){
        // $bulan = $this->input->post('bulan');
        // echo "bulan : ".$bulan;

        $bulan_from_input = substr($this->input->post('bulan'), 5, 2);
        $tahun_from_input = substr($this->input->post('bulan'), 0, 4);

        $cek_last_upload = "
            select *
            from mpm.upload a 
            where a.userid = 544 
            ORDER BY a.id desc
        ";
        $tahun_last_upload = $this->db->query($cek_last_upload)->row()->tahun;
        $bulan_last_upload = $this->db->query($cek_last_upload)->row()->bulan;
        $closing_last_upload = $this->db->query($cek_last_upload)->row()->status_closing;

        if ($closing_last_upload == 1) {
            $tanggal_seharusnya = date('Y-m-d', strtotime('+1 month', strtotime($tahun_last_upload.'-'.$bulan_last_upload.'-01')));           
            $bulan_seharusnya = date('m', strtotime($tanggal_seharusnya));
            $tahun_seharusnya = date('Y', strtotime($tanggal_seharusnya));

            if ($tahun_from_input === $tahun_seharusnya && $bulan_from_input === $bulan_seharusnya) {
                
            }else{
                echo "<br><hr>Tanggal yang anda pilih adalah :<br><br>";
                // echo "<hr>";
                echo "tahun : ".$tahun_from_input."<br>";
                echo "bulan : ".$bulan_from_input."<br><br>";
                echo "<hr>";

                echo "<br>Tanggal upload terakhir adalah :<br><br>";
                // echo "<hr>";
                echo "tahun_last_upload : ".$tahun_last_upload."<br>";
                echo "bulan_last_upload : ".$bulan_last_upload."<br>";
                echo "closing_last_upload : ".$closing_last_upload."<br><br>";
                echo "<hr>";
                
                echo "<h3>Result</h3>";
                echo "Data ditolak !!<br><br>";
                echo "Anda hanya diijinkan mengupload data untuk <b>tahun : $tahun_seharusnya</b>, dan <b>bulan : $bulan_seharusnya</b><br>";

                echo "<br>anda akan di redirect ke menu awal dalam 10 detik";
                header('Refresh: 10; URL='.base_url().'management_raw/palangkaraya');
                die;
            }

        }else{

            $tanggal_seharusnya = date('Y-m-d', strtotime('+0 month', strtotime($tahun_last_upload.'-'.$bulan_last_upload.'-01')));           
            $bulan_seharusnya = date('m', strtotime($tanggal_seharusnya));
            $tahun_seharusnya = date('Y', strtotime($tanggal_seharusnya));

            if ($tahun_from_input === $tahun_seharusnya && $bulan_from_input === $bulan_seharusnya) {
                // echo "diijinkan";
            }else{
                echo "<br><hr>Tanggal yang anda pilih adalah :<br><br>";
                echo "tahun : ".$tahun_from_input."<br>";
                echo "bulan : ".$bulan_from_input."<br><br>";
                echo "<hr>";


                echo "<br>Tanggal upload terakhir adalah :<br><br>";
                echo "tahun_last_upload : ".$tahun_last_upload."<br>";
                echo "bulan_last_upload : ".$bulan_last_upload."<br>";
                echo "closing_last_upload : ".$closing_last_upload."<br><br>";
                echo "<hr>";
                
                echo "<h3>Result</h3>";
                echo "Data ditolak !!<br><br>";
                echo "Anda hanya diijinkan mengupload data untuk <b>tahun : $tahun_seharusnya</b>, dan <b>bulan : $bulan_seharusnya</b><br>";

                echo "<br>anda akan di redirect ke menu awal dalam 10 detik";
                header('Refresh: 10; URL='.base_url().'management_raw/palangkaraya');
                die;
            }

        }

        if (!is_dir('./assets/uploads/management_raw/import/')) {
            @mkdir('./assets/uploads/management_raw/import/', 0777);
        }

        $this->load->library('upload'); // Load librari upload        
        $config['upload_path'] = './assets/uploads/management_raw/import/';
        // $config['allowed_types'] = 'xls|xlsx|csv';
        $config['allowed_types'] = '*';    
        $config['max_size']  = '2048000';
        $config['overwrite'] = true;
        $config['encrypt_name'] = TRUE;
        $this->upload->initialize($config);

        if ($this->upload->do_upload('file')) 
        {

            $upload_data = $this->upload->data();
            $filename = $upload_data['orig_name'];

            $this->load->library('excel');
            $object = PHPExcel_IOFactory::load("assets/uploads/management_raw/import/$filename");

            $jumlahSheet = $object->getSheetCount();
            if ($jumlahSheet > 1) {
                echo "jumlah_sheet : ".$jumlahSheet;
                echo "<script>alert('upload file gagal karena file mempunyai lebih dari 1 sheet'); </script>";
                redirect('management_raw/palangkaraya','refresh');
            }

            $highestColumm = $object->setActiveSheetIndex(0)->getHighestColumn();
            // var_dump($highestColumm);die;
            if ($highestColumm != 'W') {
                echo "<script>alert('upload file gagal karena column tidak sesuai'); </script>";
                redirect('management_raw/palangkaraya','refresh');
            }

            // die;

            $created_at = $this->model_outlet_transaksi->timezone();
            $signature = md5($this->model_outlet_transaksi->timezone());

            foreach ($object->getWorksheetIterator() as $worksheet) {

                $highestRow = $worksheet->getHighestRow();
                $highestColumn = $worksheet->getHighestColumn();

                for ($row = 2; $row <= $highestRow; $row++) {          
                    $siteid = trim($worksheet->getCellByColumnAndRow(0, $row)->getValue());
                    $nosales = trim($worksheet->getCellByColumnAndRow(1, $row)->getValue());
                    $tanggal_sales = trim($worksheet->getCellByColumnAndRow(2, $row)->getValue());
                    $salesmanid = trim($worksheet->getCellByColumnAndRow(3, $row)->getValue());
                    $nama_salesman = trim($worksheet->getCellByColumnAndRow(4, $row)->getValue());
                    $customerid = trim($worksheet->getCellByColumnAndRow(5, $row)->getValue());
                    $nama_customer = trim($worksheet->getCellByColumnAndRow(6, $row)->getValue());
                    $productid = trim($worksheet->getCellByColumnAndRow(7, $row)->getValue());
                    $product_descr = trim($worksheet->getCellByColumnAndRow(8, $row)->getValue());
                    $flag_retur = trim($worksheet->getCellByColumnAndRow(9, $row)->getValue());
                    $flag_bonus = trim($worksheet->getCellByColumnAndRow(10, $row)->getValue());
                    $harga = trim($worksheet->getCellByColumnAndRow(11, $row)->getValue());
                    $qty = trim($worksheet->getCellByColumnAndRow(12, $row)->getValue());
                    $bruto = trim($worksheet->getCellByColumnAndRow(13, $row)->getValue());
                    $disc_cabang = trim($worksheet->getCellByColumnAndRow(14, $row)->getValue());
                    $rp_cabang = trim($worksheet->getCellByColumnAndRow(15, $row)->getValue());
                    $disc_prinsipal = trim($worksheet->getCellByColumnAndRow(16, $row)->getValue());
                    $rp_prinsipal = trim($worksheet->getCellByColumnAndRow(17, $row)->getValue());
                    $disc_xtra = trim($worksheet->getCellByColumnAndRow(18, $row)->getValue());
                    $rp_xtra = trim($worksheet->getCellByColumnAndRow(19, $row)->getValue());
                    $disc_cash = trim($worksheet->getCellByColumnAndRow(20, $row)->getValue());
                    $rp_cash = trim($worksheet->getCellByColumnAndRow(21, $row)->getValue());
                    $netto = trim($worksheet->getCellByColumnAndRow(22, $row)->getValue());

                    $data = [
                        'siteid' => $siteid,
                        'nosales' => $nosales,
                        'tanggal_sales' => $tanggal_sales,
                        'salesmanid' => $salesmanid,
                        'nama_salesman' => $nama_salesman,
                        'customerid' => $customerid,
                        'nama_customer' => $nama_customer,
                        'productid' => $productid,
                        'product_descr' => $product_descr,
                        'flag_retur' => $flag_retur,
                        'flag_bonus' => $flag_bonus,
                        'harga' => $harga,
                        'qty' => $qty,
                        'bruto' => str_replace(',','',$bruto),
                        'disc_cabang' => $disc_cabang,
                        'rp_cabang' => $rp_cabang,
                        'disc_prinsipal' => $disc_prinsipal,
                        'rp_prinsipal' => $rp_prinsipal,
                        'disc_xtra' => $disc_xtra,
                        'rp_xtra' => $rp_xtra,
                        'disc_cash' => $disc_cash,
                        'rp_cash' => $rp_cash,
                        'netto' => str_replace(',','',$netto),
                        'signature' => $signature,
                        'created_at' => $created_at,
                        'created_by' => $this->session->userdata('id')
                    ];

                    $this->db->insert('management_raw.raw_palangkaraya',$data);
                }
            }
        }else{

        };

        $get_count = "select count(*) as count from management_raw.raw_palangkaraya a where a.signature = '$signature'";
        $count = $this->db->query($get_count)->row()->count;

        $get_omzet = "select sum(a.bruto) as omzet_raw from management_raw.raw_palangkaraya a where a.signature = '$signature'";
        $omzet_raw = $this->db->query($get_omzet)->row()->omzet_raw;

        $upload = [
            'site_code'     => 'PKRP8',
            'filename'      => $filename,
            'signature'     => $signature,
            'count_raw'      => $count,
            'omzet_raw'     => $omzet_raw,
            'type_file'      => 'raw_sales',
            'bulan'         => $bulan_from_input,
            'tahun'         => $tahun_from_input,
            'created_at'    => $created_at,
            'created_by'    => $this->session->userdata('id')
        ];
        $this->db->insert('management_raw.log_upload', $upload);

        redirect('management_raw/import_palangkaraya_draft/'.$signature);

    }

    public function import_palangkaraya_draft($signature){
        $get_omzet = "select sum(a.bruto) as omzet_raw from management_raw.raw_palangkaraya a where a.signature = '$signature'";
        $omzet_raw = $this->db->query($get_omzet)->row()->omzet_raw;

        $data = [
            'title' => 'Management Raw / Preview Import data palangkaraya',
            'id' => $this->session->userdata('id'),
            'get_raw_draft' => $this->model_management_raw->get_raw_draft_pangkalanbun('PKRP8', $signature),
            'omzet_raw' => $omzet_raw,
            'url' => 'management_raw/proses_mapping_palangkaraya',
            'signature' => $signature
        ];
        $this->load->view('management_raw/header');
        $this->load->view('management_raw/draft_palangkaraya', $data);
        $this->load->view('mes/footer');
    } 

    public function proses_mapping_palangkaraya(){

        $signature = $this->input->post('signature');
        $status_closing = $this->input->post('status_closing');
        $id_imports = $this->input->post('options');

        $update_kodeproduk = $this->model_management_raw->update_kodeproduk_pangkalanbun('PKRP8', $signature);
        if ($update_kodeproduk) {
            echo "<br><center><i>fix kodeproduk ... </i></b><br>";
        }

        $inner_kodeproduk = $this->model_management_raw->inner_kodeproduk_pangkalanbun('PKRP8', $signature);
        if ($inner_kodeproduk) {
            echo "<br><center><i>sukses memfilter produk khusus mpm / membuang produk selain mpm ...</i></b><br>";
        }

        $update_namaprod = $this->model_management_raw->update_namaprod_pangkalanbun('PKRP8', $signature);
        if ($update_namaprod) {
            echo "<br><center><i>sukses mengupdate namaproduk ...</i></b><br>";
        }
        
        $update_branch = $this->model_management_raw->update_branch('PKRP8', $signature);
        if ($update_branch) {
            echo "<br><center><i>sukses mengupdate branch_name dan nama_comp ...</i></b><br>";
        }

        $update_tanggal = $this->model_management_raw->update_tanggal_pangkalanbun('PKRP8', $signature);                       
        if ($update_tanggal) {
            echo "<br><center><i>sukses memperbaiki format tanggal ...</i></b><br>";
        }

        $prepare_tblang_bantu = $this->model_management_raw->prepare_tblang_bantu('PKRP8', $signature);
        if ($prepare_tblang_bantu) {
            echo "<br><center><i>prepare_tblang_bantu done ...</i></b><br>";
        }

        $delete_tabel = $this->model_management_raw->delete_tabel('PKRP8', $signature);
        if ($delete_tabel) {
            echo "<br><center><i>delete tabel done ...</i></b><br>";
        }

        $insert_fi = $this->model_management_raw->insert_fi_palangkaraya('PKRP8', $signature);
        if ($insert_fi) {
            echo "<br><center><i>insert fi (sales) done ...</i></b><br>";
        }

        $insert_tblang_bantu = $this->model_management_raw->insert_tblang_bantu_banjarmasin('PKRP8', $signature);
        if ($insert_tblang_bantu) {
            echo "<br><center><i>insert tblang bantu done ...</i></b><br>";
        }

        $update_tblang_bantu = $this->model_management_raw->update_tblang_bantu('PKRP8', $signature);
        if ($update_tblang_bantu) {
            echo "<br><center><i>update_ tblang bantu done ...</i></b><br>";
        }

        $insert_tblang = $this->model_management_raw->insert_tblang_banjarmasin('PKRP8', $signature);
        if ($insert_tblang) {
            echo "<br><center><i>insert tblang done ...</i></b><br>";
        }

        $update_fi = $this->model_management_raw->update_fi('PKRP8', $signature);
        if ($update_fi) {
            echo "<br><center><i>update fi done ...</i></b><br>";
        }

        $insert_tabsales = $this->model_management_raw->insert_tabsales_kendari('PKRP8', $signature);
        if ($insert_tabsales) {
            echo "<br><center><i>insert tabsales done ...</i></b><br>";
        }

        $insert_tbkota = $this->model_management_raw->insert_tbkota_pangkalanbun('PKRP8', $signature);
        if ($insert_tbkota) {
            echo "<br><center><i>insert tbkota done ...</i></b><br>";
        }        

        $get_count = "select count(*) as count from management_raw.inner_raw_palangkaraya a where a.signature = '$signature'";
        $count = $this->db->query($get_count)->row()->count;

        $get_bulan = $this->model_management_raw->get_log_upload_by_signature($signature)->row()->bulan;
        $get_tahun = $this->model_management_raw->get_log_upload_by_signature($signature)->row()->tahun;

        $get_omzet = $this->model_management_raw->get_omzet_web($get_tahun, $get_bulan, 'PKRP8')->row()->omzet;

        $update = [
            'count_mapping' => $count,
            'omzet_web'     => $get_omzet,
        ];
        $this->db->where('signature', $signature);
        $this->db->update('management_raw.log_upload', $update);

        $insert_mpm_upload = $this->model_management_raw->insert_mpm_upload_pangkalanbun('PKRP8', $signature, $get_omzet, $status_closing);
        if ($insert_mpm_upload) {
            echo "<br><center><i>insert mpm.upload done ...</i></b><br>";
        }
        
        echo "<br><center><i>dalam 5 detik anda akan di redirect ke halaman awal ...</i></b><br>";
        header('Refresh: 5; URL='.base_url().'management_raw/palangkaraya');    
    }

    public function template_customer_palangkaraya(){        
        $query = "
            select  '' as Kategori, '' as Nama_Site, '' as Regional, '' as Customer_Id, '' as Maping_ULI, '' as Maping_ND6, '' as maping_warung_pintar, '' as maping_pbf, '' as Prefix, '' as Nama_Customer, '' as Alamat, '' as Tipe_Bayar, '' as TOP, '' as Status_Konsinyasi, '' as Status_Fuguh, '' as Kelurahan_Id, '' as Nama_Kelurahan, '' as Kecamatan_Id, '' as Nama_Kecamatan, '' as Kota_Id, '' as Nama_Kota, '' as Propinsi_Id, '' as Nama_Propinsi, '' as Kode_Pos, '' as Telp, '' as Fax, '' as Email, '' as Head_Office_Id, '' as Nama_Head_Office, '' as Company_Id, '' as Nama_Company, '' as Branch_Id, '' as Nama_Branch_Office, '' as Site_Id, '' as Segment_Id, '' as Nama_Segment, '' as Type_Id, '' as Nama_Type, '' as Class_Id, '' as Class, '' as Spot_Id, '' as No_KTP, '' as Kartu_Keluarga, '' as PLN, '' as Nama_Penghubung, '' as Alamat_Penghubung, '' as Telp_Penghubung, '' as Hubungan, '' as Latitude, '' as Longitude, '' as Member, '' as Black_List, '' as Aktif, '' as Show_Alamat_PKP, '' as Date_Create, '' as pbf_izin_no_tdp_tgl, '' as pbf_izin_no_tdp, '' as pbf_izin_no_siup_tgl, '' as pbf_izin_no_siup, '' as pbf_izin_no_sito_tgl, '' as pbf_izin_no_sito, '' as pbf_izin_no_sipa_tgl, '' as pbf_izin_no_sipa, '' as pbf_izin_no_sia_tgl, '' as pbf_izin_no_sia, '' as pbf_izin_no_nib_tgl, '' as pbf_izin_no_nib, '' as pbf_izin_no_cdob_tgl, '' as pbf_izin_no_cdob, '' as pbf_asis_apoteker_tgl_sipa, '' as pbf_asis_apoteker_tgl_lahir, '' as pbf_asis_apoteker_telpon, '' as pbf_asis_apoteker_no_sipa, '' as pbf_asis_apoteker_no_ktp, '' as pbf_asis_apoteker_email, '' as pbf_asis_apoteker_nama, '' as pbf_asis_apoteker_alamat, '' as pbf_apoteker_tgl_sipa, '' as pbf_apoteker_tgl_lahir, '' as pbf_apoteker_telpon, '' as pbf_apoteker_no_sipa, '' as pbf_apoteker_no_ktp, '' as pbf_apoteker_nama, '' as pbf_apoteker_alamat, '' as pbf_apoteker_email
        ";

        $hsl = $this->db->query($query);
        query_to_csv($hsl,TRUE,'Template_Customer_Palangkaraya.csv');
    }

    public function customer_palangkaraya(){
        $data = [
            'title' => 'Customer palangkaraya',
            'id' => $this->session->userdata('id'),
            'url_import' => 'management_raw/import_customer_palangkaraya',
            'get_customer' => $this->model_management_raw->get_customer_pangkalanbun('PKRP8')
        ];
        $this->load->view('management_raw/header');
        $this->load->view('management_raw/customer_palangkaraya', $data);
        $this->load->view('mes/footer');
    }

    public function import_customer_palangkaraya(){
        $bulan = $this->input->post('bulan');
        // echo "bulan : ".$bulan;

        if (!is_dir('./assets/uploads/management_raw/import/')) {
            @mkdir('./assets/uploads/management_raw/import/', 0777);
        }

        $this->load->library('upload'); // Load librari upload        
        $config['upload_path'] = './assets/uploads/management_raw/import/';
        // $config['allowed_types'] = 'xls|xlsx|csv';
        $config['allowed_types'] = '*';    
        $config['max_size']  = '2048000';
        $config['overwrite'] = true;
        $config['encrypt_name'] = TRUE;
        $this->upload->initialize($config);

        if ($this->upload->do_upload('file')) 
        {

            $upload_data = $this->upload->data();
            $filename = $upload_data['orig_name'];

            $this->load->library('excel');
            $object = PHPExcel_IOFactory::load("assets/uploads/management_raw/import/$filename");

            $jumlahSheet = $object->getSheetCount();
            if ($jumlahSheet > 1) {
                echo "jumlah_sheet : ".$jumlahSheet;
                echo "<script>alert('upload file gagal karena file mempunyai lebih dari 1 sheet'); </script>";
                redirect('management_raw/customer_palangkaraya','refresh');
            }

            $created_at = $this->model_outlet_transaksi->timezone();
            $signature = md5($this->model_outlet_transaksi->timezone());

            foreach ($object->getWorksheetIterator() as $worksheet) {

                $highestRow = $worksheet->getHighestRow();
                $highestColumn = $worksheet->getHighestColumn();

                for ($row = 2; $row <= $highestRow; $row++) {          
                
                    $customer_id = trim($worksheet->getCellByColumnAndRow(3, $row)->getValue());
                    $customer_id_nd6 = trim($worksheet->getCellByColumnAndRow(4, $row)->getValue());
                    $nama_customer = trim($worksheet->getCellByColumnAndRow(9, $row)->getValue());
                    $alamat = trim($worksheet->getCellByColumnAndRow(10, $row)->getValue());
                    $kode_type = trim($worksheet->getCellByColumnAndRow(36, $row)->getValue());
                    $nama_type = trim($worksheet->getCellByColumnAndRow(37, $row)->getValue());
                    $segment = trim($worksheet->getCellByColumnAndRow(35, $row)->getValue());
                    $kode_class = trim($worksheet->getCellByColumnAndRow(38, $row)->getValue());
                    $nama_class = trim($worksheet->getCellByColumnAndRow(39, $row)->getValue());
                    $kode_kota = trim($worksheet->getCellByColumnAndRow(19, $row)->getValue());
                    $nama_kota = trim($worksheet->getCellByColumnAndRow(20, $row)->getValue());
                    $kode_kecamatan = trim($worksheet->getCellByColumnAndRow(17, $row)->getValue());
                    $nama_kecamatan = trim($worksheet->getCellByColumnAndRow(18, $row)->getValue());
                    $kode_kelurahan = trim($worksheet->getCellByColumnAndRow(15, $row)->getValue());
                    $nama_kelurahan = trim($worksheet->getCellByColumnAndRow(16, $row)->getValue());

                    if ($customer_id_nd6 != null || $customer_id_nd6 != '') {
                        $cek = "select * from management_raw.raw_customer_palangkaraya a where a.customer_id = '$customer_id' and a.customer_id_nd6 = '$customer_id_nd6'";
                        if($this->db->query($cek)->num_rows() > 0){
    
                            // echo "update";
                            $data = [
                                'customer_id' => $customer_id,
                                'nama_customer' => $nama_customer,
                                'alamat' => $alamat,
                                'kode_type' => $kode_type,
                                'nama_type' => $nama_type,
                                'segment' => $segment,
                                'kode_class' => $kode_class,
                                'nama_class' => $nama_class,
                                'kode_kota' => $kode_kota,
                                'nama_kota' => $nama_kota,
                                'kode_kecamatan' => $kode_kecamatan,
                                'nama_kecamatan' => $nama_kecamatan,
                                'kode_kelurahan' => $kode_kelurahan,
                                'nama_kelurahan' => $nama_kelurahan,
                                'signature' => $signature,
                                'created_at' => $created_at,
                                'created_by' => $this->session->userdata('id')
                            ];
    
                            $this->db->where('customer_id', $customer_id);
                            $this->db->where('customer_id_nd6', $customer_id_nd6);
                            $this->db->update('management_raw.raw_customer_palangkaraya', $data);
    
    
                        }else{
                            // echo "insert";
                            $data = [
                                'customer_id' => $customer_id,
                                'customer_id_nd6' => $customer_id_nd6,
                                'nama_customer' => $nama_customer,
                                'alamat' => $alamat,
                                'kode_type' => $kode_type,
                                'nama_type' => $nama_type,
                                'segment' => $segment,
                                'kode_class' => $kode_class,
                                'nama_class' => $nama_class,
                                'kode_kota' => $kode_kota,
                                'nama_kota' => $nama_kota,
                                'kode_kecamatan' => $kode_kecamatan,
                                'nama_kecamatan' => $nama_kecamatan,
                                'kode_kelurahan' => $kode_kelurahan,
                                'nama_kelurahan' => $nama_kelurahan,
                                'signature' => $signature,
                                'created_at' => $created_at,
                                'created_by' => $this->session->userdata('id')
                            ];
        
                            $this->db->insert('management_raw.raw_customer_palangkaraya',$data);
    
                        }
                    } 
                }
            }
        }

        $update = "
            update management_raw.raw_customer_palangkaraya a 
            set a.nama_type = (
                select b.nama_type
                from mpm.tbl_bantu_type b
                where a.kode_type = b.kode_type
            ), a.sektor = (
                select b.sektor
                from mpm.tbl_bantu_type b
                where a.kode_type = b.kode_type
            ), a.segment = (
                select b.segment
                from mpm.tbl_bantu_type b
                where a.kode_type = b.kode_type
            ), a.nama_class = (
                select c.jenis
                from mpm.tbl_tabsalur c 
                where a.kode_class = c.kode
            ), a.group_class = (
                select c.`group`
                from mpm.tbl_tabsalur c 
                where a.kode_class = c.kode
            )
        ";
        $proses_update = $this->db->query($update);

        // echo "<pre>";
        // print_r($update);
        // echo "proses_update : ".$proses_update;
        // echo "</pre>";

        $get_count = "select count(*) as count from management_raw.raw_customer_palangkaraya a where a.signature = '$signature'";
        $count = $this->db->query($get_count)->row()->count;

        $upload = [
            'site_code' => 'PKRP8',
            'signature' => $signature,
            'filename' => $filename,
            'type_file' => 'raw_customer',
            'count_raw' => $count,
            'created_at' => $created_at,
            'created_by' => $this->session->userdata('id')
        ];
        $this->db->insert('management_raw.log_upload', $upload);

        redirect('management_raw/customer_palangkaraya/');
    }
}
?>