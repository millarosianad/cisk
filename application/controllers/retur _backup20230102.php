<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Retur extends MY_Controller
{
    
    function retur()
    {       
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
        set_time_limit(0);
        $this->load->library(array('table', 'template', 'Excel_generator', 'form_validation', 'email', 'zip'));
        $this->load->helper('url');
        $this->load->helper('csv');
        $this->load->model('model_omzet');
        $this->load->model('model_retur');
        $this->load->model('M_menu');
        $this->load->model('model_per_hari');
        $this->load->model('model_sales_omzet');
        $this->load->model('model_sales');
        $this->load->model('model_outlet_transaksi');
        $this->load->model('model_master_data');
        $this->load->model('M_helpdesk');
        $this->load->model('M_product');
        $this->load->model('model_inventory');
        $this->load->database();
    }
    function index()
    {
        $this->pengajuan();
    }

    private function template($view,$data)
    {
        $this->template->set_title('MPM SQUARE');
        $this->template->add_js('modules/skeleton.js');
        $this->template->add_css('modules/skeleton.css');
        $this->template->load_view($view, $data);
    }

    public function import(){
        $data['page_content'] = 'retur/import';
        $data['url'] = 'retur/import_proses/';
        $data['page_title'] = 'Import Retur';
        // $data['menu']=$this->db->query($this->querymenu);
        $this->template($data['page_content'],$data);
    }

    public function import_gagal(){
        $data['page_content'] = 'retur/import_gagal';
        $data['url'] = 'retur/import_proses/';
        $data['page_title'] = 'Import Retur';
        $data['menu']=$this->db->query($this->querymenu);
        $this->template($data['page_content'],$data);
    }

    public function import_proses()
    {
        $id = $this->session->userdata('id');
        date_default_timezone_set('Asia/Jakarta');        
		$tgl_created='"'.date('Y-m-d H:i:s').'"';
        $this->load->library('upload'); // Load librari upload        
        $config['upload_path'] = './assets/uploads/retur/';    
        $config['allowed_types'] = 'xls';    
        $config['max_size']  = '2048';    
        $config['overwrite'] = true;    
        $this->upload->initialize($config); 
    
        // Load konfigurasi uploadnya    
        if($this->upload->do_upload('file'))
        { 
          $upload_data = $this->upload->data();
          $filename = $upload_data['orig_name'];
          $this->load->library('excel');
          $object = PHPExcel_IOFactory::load("assets/uploads/retur/$filename");

            foreach($object->getWorksheetIterator() as $worksheet)
            {
                $highestRow = $worksheet->getHighestRow();
                $highestColumn = $worksheet->getHighestColumn();
                for($row=2; $row<=$highestRow; $row++)
                {
                    $supp = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
                    $tgldo = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
                    $tgldo_convert = PHPExcel_Shared_Date::ExcelToPHP($tgldo);
                    $tgldo_final =date('Y-m-d', $tgldo_convert);

                    $userid = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
                    $kodeprod = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
                    $banyak = $worksheet->getCellByColumnAndRow(4, $row)->getValue();
                    $nodo = $worksheet->getCellByColumnAndRow(5, $row)->getValue();
                    $nodo_beli = $worksheet->getCellByColumnAndRow(6, $row)->getValue();
                    $noseri = $worksheet->getCellByColumnAndRow(7, $row)->getValue();
                    $noseri_beli = $worksheet->getCellByColumnAndRow(8, $row)->getValue();
                    $nopo = $worksheet->getCellByColumnAndRow(9, $row)->getValue();
                    $tglbuat = $worksheet->getCellByColumnAndRow(10, $row)->getValue();
                    // echo "supp : ".$supp."<br>";
                    // echo "tgldo : ".$tgldo."<br>";
                    // echo "userid : ".$userid."<br>";
                    // echo "kodeprod : ".$kodeprod."<br>";
                    // echo "banyak : ".$banyak."<br>";
                    // echo "nodo : ".$nodo."<br>";
                    // echo "nodo_beli : ".$nodo_beli."<br>";
                    // echo "noseri : ".$noseri."<br>";
                    // echo "noseri_beli : ".$noseri_beli."<br>";
                    // echo "nopo : ".$nopo."<br>";
                    // echo "tglbuat : ".$tglbuat."<br>";

                    $data[] = array(
                        'supp'		    =>	$supp,
                        'tgldo'		    =>	$tgldo_final,
                        'userid'	    =>	$userid,
                        'kodeprod'	    =>	$kodeprod,
                        'banyak'	    =>	$banyak,
                        'nodo'		    =>	$nodo,
                        'nodo_beli'	    =>	$nodo_beli,
                        'noseri'	    =>	$noseri,
                        'noseri_beli'	=>	$noseri_beli,
                        'nopo'	        =>	$nopo,
                        'tglbuat'		=>	$tglbuat,
                        'created_date'	=>	$tgl_created,
                        'created_by'	=>	$id,
                        'filename'  	=>	$filename,
                    );
                }
            }
            $insert = $this->model_retur->insert($data);
        }else{  
            $return = array('result' => 'failed', 'file' => '', 'error' => $this->upload->display_errors());      
            redirect('retur/import');
        }

        $data['page_content'] = 'retur/import_proses';
        $data['url'] = 'retur/import_proses/';
        $data['query'] = $this->model_retur->get_import_retur();
        $data['page_title'] = 'Review Retur';
        $data['menu']=$this->db->query($this->querymenu);
        $this->template($data['page_content'],$data);


    }

    public function transfer_retur(){

        $data['page_content'] = 'retur/transfer_retur';
        $data['url'] = 'retur/transfer_retur/';
        $transfer = $this->model_retur->transfer_retur();
        if ($transfer) {
            redirect('trans/retur');
        }else{
            echo "transfer data gagal. Silahkan hubungi IT";
        }
        // $data['page_title'] = 'Review Retur';
        // $data['menu']=$this->db->query($this->querymenu);
        // $this->template($data['page_content'],$data);

    }

    public function generate(){
        $data = [
            'id' => $this->session->userdata('id'),
            'url' => 'retur/generateProses/',
            'title' => 'Generate Retur (Bruto - Disc - DPP)',
            'get_label' => $this->M_menu->get_label(),
            'get_supp' => $this->model_omzet->getSuppbyid(),
        ];

        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar',$data);
        $this->load->view('template_claim/top_content',$data);
        $this->load->view('retur/generate',$data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
    }

    public function generateProses(){
        $data = [
            'id' => $this->session->userdata('id'),
            'url' => 'retur/generateProses/',
            'title' => 'Generate Retur (Bruto - Disc - DPP)',
            'get_label' => $this->M_menu->get_label(),
            'periode_1' => $this->input->post('periode_1'),
            'periode_2' => $this->input->post('periode_2'),
        ];

        $data['proses'] = $this->model_retur->generate($data);
        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar',$data);
        $this->load->view('template_claim/top_content',$data);
        $this->load->view('retur/generateProses');
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
    }

    public function exportGenerate(){
        $id=$this->session->userdata('id');
        $query="
            select  * from db_temp.t_temp_generate_retur a
            where a.id = $id and a.created_date = (select max(created_date) from db_temp.t_temp_generate_retur where id = $id)
        ";                        
        $hsl = $this->db->query($query);
        query_to_csv($hsl,TRUE,'Generate Retur.csv');
    }

    function delete_files($path, $del_dir = FALSE, $level = 0)
    {
        // Trim the trailing slash
        $path = rtrim($path, DIRECTORY_SEPARATOR);

        if (!$current_dir = @opendir($path)) {
            return FALSE;
        }

        while (FALSE !== ($filename = @readdir($current_dir))) {
            if ($filename != "." and $filename != "..") {
                if (is_dir($path . DIRECTORY_SEPARATOR . $filename)) {
                    // Ignore empty folders
                    if (substr($filename, 0, 1) != '.') {
                        delete_files($path . DIRECTORY_SEPARATOR . $filename, $del_dir, $level + 1);
                    }
                } else {
                    unlink($path . DIRECTORY_SEPARATOR . $filename);
                }
            }
        }
        @closedir($current_dir);

        if ($del_dir == TRUE and $level > 0) {
            return @rmdir($path);
        }

        return TRUE;
    }

    public function report_retur()
    {
        $bulan = substr($this->input->post('bulan'), 5, 2);
        $tahun = substr($this->input->post('bulan'), 0, 4);
        $principal = $this->input->post('principal');

        // die;

        if ($principal == 'all') {
            $this->delete_files('./assets/faktur');

            $cek_faktur = "
            select 	id,nodo_beli,supp 
            from mpm.trans a
            where month(tglbuat)=$bulan and deleted=0 and year(tglbuat)=$tahun
            
            ";

            $proses_cek_faktur = $this->db->query($cek_faktur);
            if ($proses_cek_faktur->num_rows() > 0) {
                // echo "Ada";
            
                $server='localhost:3307';
                $user='root';
                $pass='mpm123#@!';
                $db='mpm';

                $this->load->library('PHPJasperXML');
                $xml = simplexml_load_file("assets/report/report_retur_supp_non_tdt.jrxml");

                foreach ($proses_cek_faktur->result() as $value) {

                    // echo "nopesan : " . $value->id . "<br>";
                    // echo "nodo_beli : " . $value->nodo_beli . "<br>";

                    $PHPJasperXML = new PHPJasperXML();
                    $PHPJasperXML->debugsql = false;
                    $PHPJasperXML->arrayParameter = array('nopesan' => $value->id);
                    $PHPJasperXML->xml_dismantle($xml);
                    $PHPJasperXML->transferDBtoArray($server, $user, $pass, $db);
                    $PHPJasperXML->outpage("F", 'assets/faktur/' . $value->supp . '_' . str_replace('/', '_', $value->nodo_beli) . '.pdf');
                    $this->zip->read_file('assets/faktur/' . $value->supp . '_' . str_replace('/', '_', $value->nodo_beli) . '.pdf');
                }
                $filename = 'RETUR' . md5(date("d-m-Y H:i:s")) . '.zip';
                $this->zip->download($filename);
            } else {
                echo "tidak ada";
            }
        }else if ($principal == '001') {
            $this->delete_files('./assets/faktur');

            $cek_faktur = "
            select 	id,nodo_beli,supp 
            from mpm.trans a
            where month(tglbuat)=$bulan and deleted=0 and year(tglbuat)=$tahun and supp = 001
            limit 10
            ";

            $proses_cek_faktur = $this->db->query($cek_faktur);
            if ($proses_cek_faktur->num_rows() > 0) {
                // echo "Ada";
            
                $server='localhost:3307';
                $user='root';
                $pass='mpm123#@!';
                $db='mpm';

                $this->load->library('PHPJasperXML');
                $xml = simplexml_load_file("assets/report/report_retur_supp_non_tdt_deltomed.jrxml");

                foreach ($proses_cek_faktur->result() as $value) {

                    // echo "nopesan : " . $value->id . "<br>";
                    // echo "nodo_beli : " . $value->nodo_beli . "<br>";

                    $PHPJasperXML = new PHPJasperXML();
                    $PHPJasperXML->debugsql = false;
                    $PHPJasperXML->arrayParameter = array('nopesan' => $value->id);
                    $PHPJasperXML->xml_dismantle($xml);
                    $PHPJasperXML->transferDBtoArray($server, $user, $pass, $db);
                    $PHPJasperXML->outpage("F", 'assets/faktur/' . $value->supp . '_' . str_replace('/', '_', $value->nodo_beli) . '.pdf');
                    $this->zip->read_file('assets/faktur/' . $value->supp . '_' . str_replace('/', '_', $value->nodo_beli) . '.pdf');
                }
                $filename = 'RETUR' . md5(date("d-m-Y H:i:s")) . '.zip';
                $this->zip->download($filename);
            } else {
                echo "tidak ada";
            }
        }

        
    }

    public function pengajuan(){
        $get_kode_alamat = $this->model_inventory->get_kode_alamat();
        $code = '';
        foreach ($get_kode_alamat as $key) {
            $code.= ","."'".$key->kode_alamat."'";
        }
        $kode_alamat = preg_replace('/,/', '', $code,1);

        $data = [
            'id'          => $this->session->userdata('id'),
            'title'       => 'Pengajuan Retur',
            'get_label'   => $this->M_menu->get_label(),
            'site_code'   => $this->M_helpdesk->get_sitecode(),
            'history'     => $this->model_retur->history_pengajuan_retur($kode_alamat,''),
            'url'         => 'retur/proses_pengajuan'
        ];

        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar', $data);
        $this->load->view('retur/pengajuan', $data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
    }

    public function proses_pengajuan(){
        // echo "proses pengajuan";
        $site_code = $this->input->post('site_code');
        $principal = $this->input->post('principal');
        $tanggal_pengajuan = $this->input->post('tanggal_pengajuan');
        $nama = $this->input->post('nama');
        $file = $this->input->post('file');
        $created_date = $this->model_outlet_transaksi->timezone();
        $no_pengajuan = $this->model_retur->generate_no_pengajuan_retur($site_code,$tanggal_pengajuan);
        $signature = md5(str_replace('-','',$created_date)). "-".md5($site_code.$principal.$nama);

        // echo "created_date : ".$created_date;
        // echo "signature : ".$signature;
        // die;

        if (!is_dir('./assets/file/retur/')) {
            @mkdir('./assets/file/retur/', 0777);
        }

        //konfigurasi upload
        $config['upload_path'] = './assets/file/retur/';
        $config['allowed_types'] = '*';
        $config['max_size'] = '*';
        // $config['encrypt_name'] = TRUE;
        // $config['file_name'] = $site_code.$name.'_'.$time;

        $this->load->library('upload', $config);
        if (!$this->upload->do_upload('file')) {
            // echo '<script>alert("File tidak sesuai. Pastikan File nya sudah benar. Jika perlu bantuan silahkan hubungi IT !");</script>';
            // redirect('all_upload/', 'refresh');
            // var_dump($this->upload->display_errors());
            $filename = '';
        } else {

            $data = array('upload_data' => $this->upload->data());
            $file = $data['upload_data']['full_path'];
            chmod($file, 0777);

            //mengambil
            $upload_data = $this->upload->data();
            $filename = $upload_data['file_name'];
            // $proses = $this->db->insert('db_temp.t_temp_file_sds', $file);
            // echo "filename : ".$filename;
        }

        $data = [
            'site_code'         => $site_code,
            'no_pengajuan'      => $no_pengajuan,    
            'file'              => $filename,
            'supp'         => $principal,
            'nama'              => $nama,
            'tanggal_pengajuan' => $tanggal_pengajuan,
            'status'            => '1',
            'nama_status'       => 'pending dp',
            'created_date'      => $created_date,
            'created_by'        => $this->session->userdata('id'),
            'last_updated'      => '',
            'last_updated_by'   => '',
            'signature'         => $signature,
        ];
        $proses = $this->model_retur->insert_pengajuan_retur($data);
        // echo "proses : ".$proses;
        if ($proses) {
            // var_dump($proses);

            $supp = $this->model_retur->get_supp_pengajuan_by_signature($proses);
            
            echo '<script>alert("Data terbentuk, silahkan masukkan detail produk yang ingin di retur !");</script>';
            redirect('retur/produk_pengajuan/'.$proses.'/'.$supp,'refresh');
            
        }else{
            echo '<script>alert("Tidak berhasil menginput data. Jika perlu bantuan silahkan hubungi IT !");</script>';
            
            // var_dump($proses);
        }

        
    }

    public function produk_pengajuan($signature){

        $id = $this->model_retur->get_id_by_signature($signature);
        $supp = $this->model_retur->get_supp_by_signature($signature);
        
        // echo "supp : ".$supp;
        // die;

        $data = [
            'id'            => $this->session->userdata('id'),
            'url'           => 'retur/approval_produk_pengajuan_ceklist/',
            'title'         => 'Pengajuan Retur | Detail Produk',
            'get_label'     => $this->M_menu->get_label(),
            'product'       => $this->M_product->get_product(),
            'suppq'         => $this->M_product->getSupp(),
            'group'         => $this->M_product->getGroup(),
            'subgroup'      => $this->M_product->getSubgroup(),
            'jenis'         => $this->M_product->getJenis(),
            's_code'        => $this->model_master_data->site_code(),
            'get_produk_pengajuan'     => $this->model_retur->get_produk_pengajuan($id, $supp),
            'get_status_pengajuan'     => $this->model_retur->get_status_pengajuan($id),
            'get_discount'             => $this->model_retur->get_discount($id),
            'id_pengajuan' => $id, //ini id pengajuan retur
        ];
        // var_dump($data['get_discount']);die;

        $supp = $this->model_retur->get_supp_pengajuan($id);
        if ($supp == '001' || $supp == '002' || $supp == '004' || $supp == '013') {
            $view = 'produk_pengajuan';
            $data_discount ='';
        }elseif ($supp == '005'){
            $view = 'produk_pengajuan_us';
            $data_discount ='';
        }elseif ($supp == '012'){
            $view = 'produk_pengajuan_intrafood';
            // $get_discount = $this->model_retur->get_discount($id);
            // var_dump($get_discount);

            // die;
            // $get_diskon ='';
            // foreach ($get_discount as $row) {
            //     $data_discount=[
            //         "diskon" => $row->diskon,
            //         "ppn" => $row->ppn,
            //     ];
            // }

            // $data_discount ="a";

            // var_dump($diskon);
        }
        // var_dump($data);
        // die;

        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar', $data);
        $this->load->view('template_claim/top_content', $data);
        $this->load->view('retur/'.$view, $data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
    }

    public function diskon(){
        $supp = $this->input->post('supp');
        $signature = $this->input->post('signature');
        $id = $this->model_retur->get_id_by_signature($signature);

        $created_date = $this->model_outlet_transaksi->timezone();

        $data = [
            'diskon'                => $this->input->post('diskon'),
            'signature'             => $this->input->post('signature'),
            'ppn'                   => $this->input->post('ppn'),
            'id_retur'              => $id,
            'created_by'            => $this->session->userdata('userid'),
            'created_date'          => $created_date,
            'last_updated_by'       => $this->session->userdata('userid'),
            'last_updated_date'     => $created_date,
        ];

        $insert_diskon = $this->model_retur->insert_diskon($data);
        if ($insert_diskon) {
            echo '<script>alert("insert diskon berhasil");</script>';
            redirect('retur/pengajuan/','refresh');
        }else{
            echo '<script>alert("insert diskon berhasil");</script>';
            redirect('produk_pengajuan/'.$signature.'/'.$supp,'refresh');
        }

    }

    public function get_produk_retur_by_id()
    {
        $id_produk = $_GET['id'];
        // echo $id_produk;
        // $data['edit']   = 'aas';
        // $data['edit']   = $this->M_product->get_product_by_ID($id_produk);
        $data['edit']   = $this->model_retur->get_produk_retur_by_id($id_produk);
        echo json_encode($data);
    }

    public function tambah_produk_ajuan_retur($signature,$supp){

        // echo "sedang maintenance.. ";
        // die;
        $id_pengajuan = $this->input->post('id_pengajuan');
        $kodeprod = $this->input->post('kodeprod');
        $namaprod = $this->input->post('namaprod');
        $batch_number = $this->input->post('batch_number');
        $expired_date = $this->input->post('expired_date');
        $jumlah = $this->input->post('jumlah');
        $alasan = $this->input->post('alasan');

        $satuan = $this->input->post('satuan');
        $nama_outlet = $this->input->post('nama_outlet');
        $keterangan = $this->input->post('keterangan');
        
        $total_karton = $this->input->post('total_karton');
        $total_dus = $this->input->post('total_dus');
        $total_pcs = $this->input->post('total_pcs');
        // $harga_karton = $this->input->post('harga_karton');
        // $harga_dus = $this->input->post('harga_dus');
        // $harga_pcs = $this->input->post('harga_pcs');
        // $value = $this->input->post('value');
        $kode_produksi = $this->input->post('kode_produksi');

        // echo "<pre>";
        // echo "id_pengajuan : ".$id_pengajuan."<br>";
        // echo "kodeprod : ".$kodeprod."<br>";
        // echo "namaprod : ".$namaprod."<br>";
        // echo "batch_number : ".$batch_number."<br>";
        // echo "expired_date : ".$expired_date."<br>";
        // echo "jumlah : ".$jumlah."<br>";
        // echo "alasan : ".$alasan."<br>";

        // echo "satuan : ".$satuan."<br>";
        // echo "nama_outlet : ".$nama_outlet."<br>";
        // echo "keterangan : ".$keterangan."<br>";

        // echo "total_karton : ".$total_karton = $this->input->post('total_karton');
        // echo "total_dus : ".$total_dus = $this->input->post('total_dus');
        // echo "total_pcs : ".$total_pcs = $this->input->post('total_pcs');
        // echo "harga_karton : ".$harga_karton = $this->input->post('harga_karton');
        // echo "harga_dus : ".$harga_dus = $this->input->post('harga_dus');
        // echo "harga_pcs : ".$harga_pcs = $this->input->post('harga_pcs');
        // echo "value : ".$value = $this->input->post('value');
        // echo "kode_produksi : ".$kode_produksi = $this->input->post('kode_produksi');

        // echo "</pre>";

        // die;



        // echo "<pre>";
        // echo "id_pengajuan : ".$id_pengajuan."<br>";
        // echo "kodeprod : ".$kodeprod."<br>";
        // echo "namaprod : ".$namaprod."<br>";
        // echo "batch_number : ".$batch_number."<br>";
        // echo "expired_date : ".$expired_date."<br>";
        // echo "jumlah : ".$jumlah."<br>";
        // echo "alasan : ".$alasan."<br>";
        // echo "satuan : ".$satuan."<br>";
        // echo "nama_outlet : ".$nama_outlet."<br>";
        // echo "keterangan : ".$keterangan;
        // echo "</pre>";

        // die;
        $data = [
            'id_pengajuan'  => $id_pengajuan,
            'kodeprod'      => $kodeprod,
            'namaprod'      => $namaprod,
            'batch_number'  => $batch_number,
            'expired_date'  => $expired_date,
            'jumlah'        => $jumlah,
            'alasan'        => $alasan,
            'satuan'        => $satuan,
            'nama_outlet'   => $nama_outlet,
            'keterangan'    => $keterangan,

            'total_karton'    => $total_karton,
            'total_dus'    => $total_dus,
            'total_pcs'    => $total_pcs,
            
            'kode_produksi'    => $kode_produksi,

            'status'        => '1',
            'nama_status'   => 'pending di dp',
        ];

        $proses = $this->model_retur->insert_produk_ajuan_retur($data);
        if ($proses) {


            if ($supp == '012') {
                $sql = "
                update db_temp.t_temp_produk_pengajuan_retur a LEFT JOIN 
                (
                    select a.kodeprod, a.qty1, a.qty2, a.qty3, b.h_dp
                    from mpm.tabprod a INNER JOIN mpm.prod_detail b
                            on a.kodeprod = b.kodeprod
                    where a.supp = 012 and b.tgl = (
                        select max(c.tgl)
                        from mpm.prod_detail c
                        where b.kodeprod = c.kodeprod
                    )
                )b on a.kodeprod = b.kodeprod
                set a.harga_karton = round((b.qty1 / b.qty3)*b.h_dp,0),
                    a.harga_dus = round((b.qty2 / b.qty3)*b.h_dp,0),
                    a.harga_pcs = round(b.h_dp,0),
                    a.`value` = round((b.qty1 / b.qty3)*b.h_dp*a.total_karton)+
                                round((b.qty2 / b.qty3)*b.h_dp*a.total_dus) +
                                round(b.h_dp * a.total_pcs)
                where a.id_pengajuan = $id_pengajuan
                ";
                $this->db->query($sql);
            }


            // die;
            redirect('retur/produk_pengajuan/'.$signature.'/'.$supp,'refresh');
        }
    }

    public function hapus_produk_pengajuan_retur($id, $signature,$supp){

        $data = array(
            'status'    => '2',
            'deleted'    => '1',
        );
        
        $proses = $this->model_retur->delete_produk_pengajuan_retur($data,$id);

        // $this->db->where('id',$id);
        // $this->db->delete('db_temp.t_temp_produk_pengajuan_retur');
        // $hasil = $this->db->affected_rows(); 
        if ($proses) {
            redirect('retur/produk_pengajuan/'.$signature.'/'.$supp,'refresh');
        }
    }

    public function export_pengajuan($signature, $supp = ''){
        // echo "supp : ".$supp;
        // die;
        if ($supp == '005') {
            
            echo "Dear PT. Ultra Sakti \n";
            echo "Kami ingin mengirimkan pengajuan retur kami dengan data sebagai berikut : \n\n";
        
            $query = "
                select  c.branch_name, c.nama_comp as subbranch, a.no_pengajuan, b.kodeprod, b.namaprod, b.batch_number, b.expired_date, b.jumlah, 
                        b.satuan, b.nama_outlet, b.alasan, b.keterangan
                from db_temp.t_temp_pengajuan_retur a INNER JOIN
                (
                    select * 
                    from db_temp.t_temp_produk_pengajuan_retur a
                )b on a.id = b.id_pengajuan LEFT JOIN mpm.tabsupp c
                    on a.supp = c.supp LEFT JOIN 
                    (
                        select concat(a.kode_comp,a.nocab) as site_code, a.branch_name, a.nama_comp
                        from mpm.tbl_tabcomp a 
                        where a.`status` = 1
                        GROUP BY concat(a.kode_comp,a.nocab)
                    )c on a.site_code = c.site_code
                where a.signature = '$signature' and b.deleted is null
            ";

            // echo "<pre>";
            // print_r($query);
            // echo "</pre>";

        }else if($supp == '012'){
        // echo "intrafood";
            $query = "
            select  c.branch_name, c.nama_comp as subbranch, a.no_pengajuan, 
                    b.kodeprod, b.namaprod, 
                    b.total_karton,b.total_dus, b.total_pcs,
                    b.harga_karton, b.harga_dus, b.harga_pcs, b.value, b.kode_produksi, b.keterangan
            from db_temp.t_temp_pengajuan_retur a INNER JOIN
            (
                select * 
                from db_temp.t_temp_produk_pengajuan_retur a
            )b on a.id = b.id_pengajuan LEFT JOIN mpm.tabsupp c
                on a.supp = c.supp LEFT JOIN 
                (
                        select concat(a.kode_comp,a.nocab) as site_code, a.branch_name, a.nama_comp
                        from mpm.tbl_tabcomp a 
                        where a.`status` = 1
                        GROUP BY concat(a.kode_comp,a.nocab)
                )c on a.site_code = c.site_code
            where a.signature = '$signature' and b.deleted is null
            ";

            // echo "<pre>";
            // print_r($query);
            // echo "</pre>";
        
        }else{
            $query="
            select  c.namasupp as principal, a.no_pengajuan, a.nama, a.tanggal_pengajuan, a.file, 
                    b.kodeprod, b.namaprod, b.batch_number, b.expired_date, b.jumlah, b.alasan , b.nama_status, b.deskripsi
            from db_temp.t_temp_pengajuan_retur a INNER JOIN
            (
                select * 
                from db_temp.t_temp_produk_pengajuan_retur a
            )b on a.id = b.id_pengajuan LEFT JOIN mpm.tabsupp c
                on a.supp = c.supp
            where a.signature = '$signature' and a.deleted is null
            ";     

        }


        
        
                           
        $hsl = $this->db->query($query);
        query_to_csv($hsl,TRUE,'Ajuan Retur.csv');

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

    }

    // public function locked_pengajuan($id){

    // }

    public function email_pengajuan($signature,$supp){

        $id = $this->model_retur->get_id_by_signature($signature);
        $update_detail = "
            update db_temp.t_temp_produk_pengajuan_retur a 
            set a.status = 2, nama_status = 'menunggu verifikasi mpm'
            where a.id_pengajuan = $id and a.status = 1 and a.deleted is null
        ";
        $this->db->query($update_detail);

        $update_header = "
            update db_temp.t_temp_pengajuan_retur a 
            set a.status = 2, nama_status = 'proses mpm'
            where a.id = $id
        ";
        $this->db->query($update_header);

        $data = [
            'history'     => $this->model_retur->generate_attachment_pengajuan_retur($id,$supp),
        ];
        // var_dump($data['history']);die;
        $get_pengajuan = $this->model_retur->get_pengajuan_retur($id);
        // var_dump($get_pengajuan);die;
        foreach ($get_pengajuan as $key) {
            $no_pengajuan = $key->no_pengajuan;
            $site_code = $key->site_code;
            $nama = $key->nama;
            $principal = $key->supp;
            $tanggal_pengajuan = $key->tanggal_pengajuan;
            $file = $key->file;
            $branch_name = $key->branch_name;
            $nama_comp = $key->nama_comp;
            $namasupp = $key->namasupp;
            $company = $key->company;
        }

        $get_email_dp = $this->model_retur->get_email_dp($site_code);
        foreach ($get_email_dp as $key) {
            $email_dp = $key->email;
        } 

        $get_email_principal = $this->model_retur->get_email_principal($principal);
        foreach ($get_email_principal as $key) {
            $email_principal = $key->email;
        } 

        $data=[
            "no_pengajuan" => $no_pengajuan,
            "site_code" => $site_code,
            "nama" => $nama,
            "principal" => $principal,
            "tanggal_pengajuan" => $tanggal_pengajuan,
            "file" => $file,
            "branch_name" => $branch_name,
            "nama_comp" => $nama_comp,
            "namasupp" => $namasupp,
            "company" => $company,
        ];
        
        $from = "linda@muliaputramandiri.com";
        // $to = "suffy.mpm@gmail.com";
        $to = $email_dp;
        $data['to'] = $to;
        $cc = "suffy@muliaputramandiri.com, ilham@muliaputramandiri.com, linda@muliaputramandiri.com, fakhrul@muliaputramandiri.com";
        $subject = "MPM Site|Request Retur - $no_pengajuan";

        // if ($supp == '001') {
        //     $message = $this->load->view("retur/email_retur",$data,TRUE);
        // }elseif ($supp = '005') {
        //     $message = $this->load->view("retur/email_retur_us",$data,TRUE);
        // }elseif ($supp = '012') {
        //     $message = $this->load->view("retur/email_retur_intrafood",$data,TRUE);
        // }
        $message = $this->load->view("retur/email_retur",$data,TRUE);
        // $this->email->initialize($config);
        $this->email();
        $this->email->from($from,'PT. Mulia Putra Mandiri');
        $this->email->to($to);
        $this->email->cc($cc);
        $this->email->subject($subject);
        $this->email->message($message);
        $this->email->attach('assets/file/retur/'.str_replace('/','_',$no_pengajuan).'.csv');
        $send = $this->email->send();
        // echo $this->email->print_debugger();
        if ($send) {
            echo "<script>alert('pengiriman email berhasil'); </script>";
            redirect('retur/produk_pengajuan/'.$signature.'/'.$supp,'refresh');
        }else{
            echo "<script>alert('pengiriman email gagal'); </script>";
            redirect('produk_pengajuan/'.$signature.'/'.$supp,'refresh');
        }
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

    public function approval_produk_pengajuan()
    {
        $id = $this->input->post('id_produk_pengajuan');
        $signature = $this->input->post('signature');
        $supp = $this->input->post('supp');
        // $status = $this->input->post('status_approval');

        if ($this->input->post('status_approval') == '3') {
            $nama_status = 'verified';
        }else{
            $nama_status ='not verified';
        }

        $data = [ 
            "status"        => $this->input->post('status_approval'), 
            "nama_status"   => $nama_status,
            "deskripsi"     => $this->input->post('deskripsi'), 
        ];
        $proses = $this->model_retur->approval_produk_pengajuan($data,$id);
        // var_dump($proses);
        if ($proses) {
            redirect("retur/produk_pengajuan/".$signature."/".$supp);
        }
    }

    public function revisi($signature,$supp){

        $id = $this->model_retur->get_id_by_signature($signature);

        $sql = "
            update db_temp.t_temp_pengajuan_retur a 
            set a.status = 3, a.nama_status = 'proses dp'
            where a.id = $id
        ";
        $hasil = $this->db->query($sql);

        $data = [
            'history'     => $this->model_retur->generate_attachment_pengajuan_retur($id,$supp),
        ];
        // var_dump($data['history']);die;
        $get_pengajuan = $this->model_retur->get_pengajuan_retur($id);
        // var_dump($get_pengajuan);die;
        foreach ($get_pengajuan as $key) {
            $no_pengajuan = $key->no_pengajuan;
            $site_code = $key->site_code;
            $nama = $key->nama;
            $principal = $key->supp;
            $tanggal_pengajuan = $key->tanggal_pengajuan;
            $file = $key->file;
            $branch_name = $key->branch_name;
            $nama_comp = $key->nama_comp;
            $namasupp = $key->namasupp;
        }

        $get_email_dp = $this->model_retur->get_email_dp($site_code);
        foreach ($get_email_dp as $key) {
            $email_dp = $key->email;
        } 

        $get_email_principal = $this->model_retur->get_email_principal($principal);
        foreach ($get_email_principal as $key) {
            $email_principal = $key->email;
        } 

        $data=[
            "no_pengajuan" => $no_pengajuan,
            "site_code" => $site_code,
            "nama" => $nama,
            "principal" => $principal,
            "tanggal_pengajuan" => $tanggal_pengajuan,
            "file" => $file,
            "branch_name" => $branch_name,
            "nama_comp" => $nama_comp,
            "namasupp" => $namasupp,
        ];
        
        $from = "suffy.yanuar@gmail.com";
        // $to = "suffy.mpm@gmail.com";
        $to = $email_dp;
        $data['to'] = $to;
        $cc = "suffy@muliaputramandiri.com, ilham@muliaputramandiri.com, linda@muliaputramandiri.com, fakhrul@muliaputramandiri.com";
        $subject = "MPM Site|Request Retur - $no_pengajuan";

        $message = $this->load->view("retur/email_retur_revisi",$data,TRUE);
        // $this->email->initialize($config);
        $this->email();
        $this->email->from($from,'PT. Mulia Putra Mandiri');
        $this->email->to($to);
        $this->email->cc($cc);
        $this->email->subject($subject);
        $this->email->message($message);
        $this->email->attach('assets/file/retur/'.str_replace('/','_',$no_pengajuan).'.csv');
        $send = $this->email->send();
        // echo $this->email->print_debugger();
        if ($send) {
            echo "<script>alert('pengiriman email berhasil'); </script>";
            redirect('retur/produk_pengajuan/'.$signature.'/'.$supp,'refresh');
        }else{
            echo "<script>alert('pengiriman email gagal'); </script>";
            redirect('produk_pengajuan/'.$signature.'/'.$supp,'refresh');
        }

    }

    public function email_principal($signature,$supp){

        $this->load->library('mypdf');
        $id = $this->model_retur->get_id_by_signature($signature);
        // echo "id : ".$id;

        // die;
        $sql = "
            update db_temp.t_temp_pengajuan_retur a 
            set a.status = 4, a.nama_status = 'pending principal'
            where a.id = $id
        ";
        $hasil = $this->db->query($sql);

        // die;
        $data = [
            'history'     => $this->model_retur->generate_attachment_pengajuan_retur_principal($id,$supp),
            // 'detail'      => $this->model_retur->get_produk_pengajuan($id),
            // 'generate_pdf'=> $this->model_retur->generate_pdf_pengajuan_retur_principal($id),
            // 'generate_pdf'=> $this->mypdf->download('retur/template_pdf_retur_principal','','test'),
        ];
        // var_dump($data['history']);die;
        $get_pengajuan = $this->model_retur->get_pengajuan_retur($id);

        // die;
        // var_dump($get_pengajuan);die;
        foreach ($get_pengajuan as $key) {
            $no_pengajuan = $key->no_pengajuan;
            $site_code = $key->site_code;
            $nama = $key->nama;
            $principal = $key->supp;
            $tanggal_pengajuan = $key->tanggal_pengajuan;
            $file = $key->file;
            $branch_name = $key->branch_name;
            $nama_comp = $key->nama_comp;
            $namasupp = $key->namasupp;
            $filename_pdf = str_replace('/','_',$no_pengajuan);
            $company = $key->company;
        }

        // die;
        
        // die();


        $get_email_dp = $this->model_retur->get_email_dp($site_code);
        foreach ($get_email_dp as $key) {
            $email_dp = $key->email;
        } 

        $get_email_principal = $this->model_retur->get_email_principal($principal);
        foreach ($get_email_principal as $key) {
            $email_principal = $key->email;
        } 

        $data=[
            "no_pengajuan" => $no_pengajuan,
            "site_code" => $site_code,
            "nama" => $nama,
            "principal" => $principal,
            "tanggal_pengajuan" => $tanggal_pengajuan,
            "file" => $file,
            "branch_name" => $branch_name,
            "nama_comp" => $nama_comp,
            "namasupp" => $namasupp,
            "company" => $company,
            "detail" => $this->model_retur->get_produk_pengajuan($id),
            "get_discount" => $this->model_retur->get_discount($id),
            "get_total" => $this->model_retur->get_total($id)
        ];

        if ($supp == '001' || $supp == '002' || $supp == '004') {
            $generate_pdf = $this->mypdf->download('retur/template_pdf_retur_principal',$data,$filename_pdf,'A3','portrait');
        }elseif($supp == '005'){
            $generate_pdf = $this->mypdf->download('retur/template_pdf_retur_principal_us',$data,$filename_pdf,'A3','portrait');
        }elseif($supp == '012'){
            $generate_pdf = $this->mypdf->download('retur/template_pdf_retur_principal_intrafood',$data,$filename_pdf,'A3','portrait');
        }

        // die;

        if ($supp == '002') {
            $cc = $email_dp.", suffy@muliaputramandiri.com, ilham@muliaputramandiri.com, linda@muliaputramandiri.com, fakhrul@muliaputramandiri.com, michael@marguna.id, riki.sugiarto@marguna.id,logistik@marguna.id,finance@marguna.id, andi.wijaya2014@gmail.com";
        }else{
            
            $cc = $email_dp.", suffy@muliaputramandiri.com, ilham@muliaputramandiri.com, linda@muliaputramandiri.com, fakhrul@muliaputramandiri.com";
        }
        
        $from = "linda@muliaputramandiri.com";
        // $to = "suffy.mpm@gmail.com";
        $to = $email_principal;
        $data['to'] = $to;
        
        // $cc = "suffy@muliaputramandiri.com, ilham@muliaputramandiri.com";
        $subject = "MPM Site|Request Retur - $no_pengajuan";

        $message = $this->load->view("retur/email_retur_revisi_principal",$data,TRUE);
        // $this->email->initialize($config);
        $this->email();
        $this->email->from($from,'PT. Mulia Putra Mandiri');
        $this->email->to($to);
        $this->email->cc($cc);
        $this->email->subject($subject);
        $this->email->message($message);
        // $this->email->attach('assets/file/retur/'.$file);
        $this->email->attach('assets/file/retur/'.str_replace('/','_',$no_pengajuan).'.csv');
        $this->email->attach('assets/file/retur/'.str_replace('/','_',$no_pengajuan).'.pdf');
        $send = $this->email->send();
        // echo $this->email->print_debugger();
        // echo "to : ".$to;
        // echo "cc : ".$cc;
        if ($send) {
            echo "<script>alert('pengiriman email berhasil'); </script>";
            redirect('retur/produk_pengajuan/'.$signature.'/'.$supp,'refresh');
        }else{
            echo "<script>alert('pengiriman email gagal'); </script>";
            redirect('produk_pengajuan/'.$signature.'/'.$supp,'refresh');
        }

    }

    public function approval_pengajuan_retur($signature){
        $id = $this->model_retur->get_id_by_signature($signature);
        // echo "id : ".$id;

        $get_kode_alamat = $this->model_inventory->get_kode_alamat();
        $code = '';
        foreach ($get_kode_alamat as $key) {
            $code.= ","."'".$key->kode_alamat."'";
        }
        $kode_alamat = preg_replace('/,/', '', $code,1);

        $data = [
            'id'          => $this->session->userdata('id'),
            'title'       => 'Approval Pengajuan Retur',
            'get_label'   => $this->M_menu->get_label(),
            'site_code'   => $this->M_helpdesk->get_sitecode(),
            'history'     => $this->model_retur->history_pengajuan_retur($kode_alamat,''),
            'url'         => 'retur/proses_approval_pengajuan_retur'
        ];

        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar', $data);
        $this->load->view('retur/approval_pengajuan', $data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');

    }

    public function proses_approval_pengajuan_retur(){

        $tanggal_approval = $this->input->post('tanggal_approval');
        $keterangan_principal = $this->input->post('keterangan_principal');
        $signature = $this->input->post('signature');
        $file_principal = $this->input->post('file_principal');
        // $supp = $this->input->post('supp');
        $status = $this->input->post('status');
        if ($status == 5) {
            $nama_status = "proses kirim barang";
        }else{
            $nama_status = "proses pemusnahan";
        }
        $created_date = $this->model_outlet_transaksi->timezone();

        $id = $this->model_retur->get_id_by_signature($signature);

        if (!is_dir('./assets/file/retur/')) {
            @mkdir('./assets/file/retur/', 0777);
        }

        //konfigurasi upload
        $config['upload_path'] = './assets/file/retur/';
        $config['allowed_types'] = '*';
        $config['max_size'] = '*';
        // $config['encrypt_name'] = TRUE;
        // $config['file_name'] = $site_code.$name.'_'.$time;

        $this->load->library('upload', $config);
        if (!$this->upload->do_upload('file_principal')) {
            // echo '<script>alert("File tidak sesuai. Pastikan File nya sudah benar. Jika perlu bantuan silahkan hubungi IT !");</script>';
            // redirect('all_upload/', 'refresh');
            // var_dump($this->upload->display_errors());
            echo "gagal upload";
            $filename = '';
        } else {

            $data = array('upload_data' => $this->upload->data());
            $file = $data['upload_data']['full_path'];
            chmod($file, 0777);

            //mengambil
            $upload_data = $this->upload->data();
            $filename = $upload_data['file_name'];
            // $proses = $this->db->insert('db_temp.t_temp_file_sds', $file);
            // echo "filename : ".$filename;
        }

        $data = [           
            'tanggal_approval'    => $tanggal_approval,
            'status'            => $status,
            'nama_status'       => $nama_status,
            'keterangan_principal' => $keterangan_principal,
            'file_principal'    => $filename,
            'created_date'      => $created_date,
            'created_by'        => $this->session->userdata('id'),
            'last_updated'      => '',
            'last_updated_by'   => '',
        ];
        $proses = $this->model_retur->update_pengajuan_retur($data,$id);
        // echo "proses : ".$proses;
        if ($proses) {
            // var_dump($proses);
            
            echo '<script>alert("approval pengajuan retur sukses !");</script>';
            // redirect('retur/pengajuan/','refresh');

            //jalankan email

            $this->email_approval($signature);

            
        }else{
            echo '<script>alert("Tidak berhasil menginput data. Jika perlu bantuan silahkan hubungi IT !");</script>';
            redirect('retur/pengajuan/','refresh');
            // var_dump($proses);
        }

        
    }

    public function email_approval($signature){

        $id = $this->model_retur->get_id_by_signature($signature);

        $get_pengajuan = $this->model_retur->get_pengajuan_retur($id);
        // var_dump($get_pengajuan);die;
        foreach ($get_pengajuan as $key) {
            $no_pengajuan = $key->no_pengajuan;
            $site_code = $key->site_code;
            $nama = $key->nama;
            $principal = $key->supp;
            $tanggal_pengajuan = $key->tanggal_pengajuan;
            $tanggal_approval = $key->tanggal_approval;
            $file = $key->file;
            $branch_name = $key->branch_name;
            $nama_comp = $key->nama_comp;
            $namasupp = $key->namasupp;
            $file_principal = $key->file_principal;
            $keterangan_principal = $key->keterangan_principal;
            $nama_status = $key->nama_status;
        }

        $get_email_dp = $this->model_retur->get_email_dp($site_code);
        foreach ($get_email_dp as $key) {
            $email_dp = $key->email;
        } 

        $get_email_principal = $this->model_retur->get_email_principal($principal);
        foreach ($get_email_principal as $key) {
            $email_principal = $key->email;
        } 

        $data=[
            "no_pengajuan" => $no_pengajuan,
            "site_code" => $site_code,
            "nama" => $nama,
            "principal" => $principal,
            "tanggal_pengajuan" => $tanggal_pengajuan,
            "tanggal_approval" => $tanggal_approval,
            "file" => $file,
            "branch_name" => $branch_name,
            "nama_comp" => $nama_comp,
            "namasupp" => $namasupp,
            "file_principal" => $file_principal,
            "keterangan_principal" => $keterangan_principal,
            "nama_status" => $nama_status,
        ];
        
        $from = "linda@muliaputramandiri.com";
        // $to = "suffy.mpm@gmail.com";
        $to = $email_dp;
        $data['to'] = $to;
        $cc = "suffy@muliaputramandiri.com, ilham@muliaputramandiri.com, linda@muliaputramandiri.com, fakhrul@muliaputramandiri.com, kezia@muliaputramandiri.com";                          
        $subject = "MPM Site|Request Retur - $no_pengajuan";

        $message = $this->load->view("retur/email_approval",$data,TRUE);
        // $this->email->initialize($config);
        $this->email();
        $this->email->from($from,'PT. Mulia Putra Mandiri');
        $this->email->to($to);
        $this->email->cc($cc);
        $this->email->subject($subject);
        $this->email->message($message);
        // $this->email->attach('assets/file/retur/'.$file);
        $this->email->attach('assets/file/retur/'.str_replace('/','_',$no_pengajuan).'.csv');
        $this->email->attach('assets/file/retur/'.str_replace('/','_',$no_pengajuan).'.pdf');
        $this->email->attach('assets/file/retur/'.$file_principal);
        $send = $this->email->send();
        // echo $this->email->print_debugger();
        if ($send) {
            echo "<script>alert('pengiriman email berhasil'); </script>";
            redirect('retur/pengajuan/','refresh');
        }else{
            echo "<script>alert('pengiriman email gagal'); </script>";
            redirect('retur/pengajuan/','refresh');
        }


    }

    public function kirim_barang($signature){
        $id = $this->model_retur->get_id_by_signature($signature);
        // echo "id : ".$id;

        $get_kode_alamat = $this->model_inventory->get_kode_alamat();
        $code = '';
        foreach ($get_kode_alamat as $key) {
            $code.= ","."'".$key->kode_alamat."'";
        }
        $kode_alamat = preg_replace('/,/', '', $code,1);

        $data = [
            'id'          => $this->session->userdata('id'),
            'title'       => 'Isi data pengiriman',
            'get_label'   => $this->M_menu->get_label(),
            'site_code'   => $this->M_helpdesk->get_sitecode(),
            'history'     => $this->model_retur->history_pengajuan_retur($kode_alamat,''),
            'url'         => 'retur/proses_kirim_barang'
        ];

        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar', $data);
        $this->load->view('retur/kirim_barang', $data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');

    }

    public function proses_kirim_barang(){

        $tanggal_kirim = $this->input->post('tanggal_kirim');
        $est_tanggal_tiba = $this->input->post('est_tanggal_tiba');
        $nama_ekspedisi = $this->input->post('nama_ekspedisi');
        $signature = $this->input->post('signature');
        $file_pengiriman = $this->input->post('file_pengiriman');
 
        $created_date = $this->model_outlet_transaksi->timezone();

        $id = $this->model_retur->get_id_by_signature($signature);

        if (!is_dir('./assets/file/retur/')) {
            @mkdir('./assets/file/retur/', 0777);
        }

        //konfigurasi upload
        $config['upload_path'] = './assets/file/retur/';
        $config['allowed_types'] = '*';
        $config['max_size'] = '*';
        // $config['encrypt_name'] = TRUE;
        // $config['file_name'] = $site_code.$name.'_'.$time;

        $this->load->library('upload', $config);
        if (!$this->upload->do_upload('file_pengiriman')) {
            // echo '<script>alert("File tidak sesuai. Pastikan File nya sudah benar. Jika perlu bantuan silahkan hubungi IT !");</script>';
            // redirect('all_upload/', 'refresh');
            // var_dump($this->upload->display_errors());
            echo "gagal upload";
            $filename = '';
        } else {

            $data = array('upload_data' => $this->upload->data());
            $file = $data['upload_data']['full_path'];
            chmod($file, 0777);

            //mengambil
            $upload_data = $this->upload->data();
            $filename = $upload_data['file_name'];
            // $proses = $this->db->insert('db_temp.t_temp_file_sds', $file);
            // echo "filename : ".$filename;
        }

        $data = [           
            'tanggal_kirim_barang'    => $tanggal_kirim,
            'est_tanggal_tiba'        => $est_tanggal_tiba,
            'file_pengiriman'         => $filename,
            'status'                  => '5',
            'nama_status'             => 'proses dp kirim barang',
            'nama_ekspedisi'          => $nama_ekspedisi,
            'last_updated'            => $created_date,
            'last_updated_by'         => $this->session->userdata('id'),
        ];
        $proses = $this->model_retur->update_pengajuan_retur($data,$id);
        // echo "proses : ".$proses;
        if ($proses) {
            // var_dump($proses);
            
            echo '<script>alert("pengisian data pengiriman barang retur sukses !");</script>';
            // redirect('retur/pengajuan/','refresh');

            //jalankan email

            $this->email_kirim_barang($signature);

            
        }else{
            echo '<script>alert("Tidak berhasil menginput data. Jika perlu bantuan silahkan hubungi IT !");</script>';
            redirect('retur/pengajuan/','refresh');
            // var_dump($proses);
        }

        
    }

    public function email_kirim_barang($signature){

        $id = $this->model_retur->get_id_by_signature($signature);
        
        $sql = "
            update db_temp.t_temp_pengajuan_retur a 
            set a.status = 7, a.nama_status = 'proses principal terima barang'
            where a.id = $id
        ";
        $hasil = $this->db->query($sql);

        // $data = [
        //     'history'     => $this->model_retur->generate_attachment_pengajuan_retur($id),
        // ];
        // var_dump($data['history']);die;
        $get_pengajuan = $this->model_retur->get_pengajuan_retur($id);
        // var_dump($get_pengajuan);die;
        foreach ($get_pengajuan as $key) {
            $no_pengajuan = $key->no_pengajuan;
            $site_code = $key->site_code;
            $nama = $key->nama;
            $principal = $key->supp;
            $tanggal_pengajuan = $key->tanggal_pengajuan;
            $tanggal_approval = $key->tanggal_approval;
            $keterangan_principal = $key->keterangan_principal;
            $nama_status = $key->nama_status;
            $file = $key->file;
            $file_principal = $key->file_principal;
            $file_pengiriman = $key->file_pengiriman;
            $branch_name = $key->branch_name;
            $nama_comp = $key->nama_comp;
            $namasupp = $key->namasupp;
            $company = $key->company;
            $tanggal_kirim_barang = $key->tanggal_kirim_barang;
            $nama_ekspedisi = $key->nama_ekspedisi;
            $est_tanggal_tiba = $key->est_tanggal_tiba;
        }

        $get_email_dp = $this->model_retur->get_email_dp($site_code);
        foreach ($get_email_dp as $key) {
            $email_dp = $key->email;
        } 

        $get_email_principal = $this->model_retur->get_email_principal($principal);
        foreach ($get_email_principal as $key) {
            $email_principal = $key->email;
        } 

        $data=[
            "no_pengajuan" => $no_pengajuan,
            "site_code" => $site_code,
            "nama" => $nama,
            "principal" => $principal,
            "tanggal_pengajuan" => $tanggal_pengajuan,
            "tanggal_approval" => $tanggal_approval,
            "keterangan_principal" => $keterangan_principal,
            "nama_status" => $nama_status,
            "file" => $file,
            "branch_name" => $branch_name,
            "nama_comp" => $nama_comp,
            "namasupp" => $namasupp,
            "company" => $company,
            "file_principal" => $file_principal,
            "file_pengiriman" => $file_pengiriman,
            "nama_ekspedisi" => $nama_ekspedisi,
            "tanggal_kirim_barang" => $tanggal_kirim_barang,
            "est_tanggal_tiba" => $est_tanggal_tiba,
        ];
        
        $from = "linda@muliaputramandiri.com";
        // $to = "suffy.yanuar@gmail.com";
        $to = $email_principal;
        $data['to'] = $to;
        $cc = $email_dp.",suffy@muliaputramandiri.com, ilham@muliaputramandiri.com, linda@muliaputramandiri.com, fakhrul@muliaputramandiri.com, kezia@muliaputramandiri.com"; 
        $subject = "MPM Site|Request Retur - $no_pengajuan";

        $message = $this->load->view("retur/email_kirim_barang",$data,TRUE);
        // $this->email->initialize($config);
        $this->email();
        $this->email->from($from,'PT. Mulia Putra Mandiri');
        $this->email->to($to);
        $this->email->cc($cc);
        $this->email->subject($subject);
        $this->email->message($message);
        // $this->email->attach('assets/file/retur/'.$file);
        $this->email->attach('assets/file/retur/'.str_replace('/','_',$no_pengajuan).'.csv');
        $this->email->attach('assets/file/retur/'.str_replace('/','_',$no_pengajuan).'.pdf');
        $this->email->attach('assets/file/retur/'.$file_principal);
        $this->email->attach('assets/file/retur/'.$file_pengiriman);
        $send = $this->email->send();
        // echo $this->email->print_debugger();
        if ($send) {
            echo "<script>alert('pengiriman email berhasil'); </script>";
            redirect('retur/pengajuan/','refresh');
        }else{
            echo "<script>alert('pengiriman email gagal'); </script>";
            redirect('retur/pengajuan/','refresh');
        }
    }

    public function terima_barang($signature){
        $id = $this->model_retur->get_id_by_signature($signature);
        // echo "id : ".$id;

        $get_kode_alamat = $this->model_inventory->get_kode_alamat();
        $code = '';
        foreach ($get_kode_alamat as $key) {
            $code.= ","."'".$key->kode_alamat."'";
        }
        $kode_alamat = preg_replace('/,/', '', $code,1);

        $data = [
            'id'          => $this->session->userdata('id'),
            'title'       => 'Isi Data Penerimaan Barang Retur',
            'get_label'   => $this->M_menu->get_label(),
            'site_code'   => $this->M_helpdesk->get_sitecode(),
            'history'     => $this->model_retur->history_pengajuan_retur($kode_alamat,''),
            'url'         => 'retur/proses_terima_barang'
        ];

        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar', $data);
        $this->load->view('retur/terima_barang', $data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');

    }

    public function proses_terima_barang(){

        $tanggal_terima = $this->input->post('tanggal_terima');
        $nama_penerima = $this->input->post('nama_penerima');
        $no_terima = $this->input->post('no_terima');
        $signature = $this->input->post('signature');
        $file_terima = $this->input->post('file_terima');
 
        $created_date = $this->model_outlet_transaksi->timezone();

        $id = $this->model_retur->get_id_by_signature($signature);

        if (!is_dir('./assets/file/retur/')) {
            @mkdir('./assets/file/retur/', 0777);
        }

        //konfigurasi upload
        $config['upload_path'] = './assets/file/retur/';
        $config['allowed_types'] = '*';
        $config['max_size'] = '*';
        // $config['encrypt_name'] = TRUE;
        // $config['file_name'] = $site_code.$name.'_'.$time;

        $this->load->library('upload', $config);
        if (!$this->upload->do_upload('file_terima')) {
            // echo '<script>alert("File tidak sesuai. Pastikan File nya sudah benar. Jika perlu bantuan silahkan hubungi IT !");</script>';
            // redirect('all_upload/', 'refresh');
            // var_dump($this->upload->display_errors());
            echo "gagal upload";
            $filename = '';
        } else {

            $data = array('upload_data' => $this->upload->data());
            $file = $data['upload_data']['full_path'];
            chmod($file, 0777);

            //mengambil
            $upload_data = $this->upload->data();
            $filename = $upload_data['file_name'];
            // $proses = $this->db->insert('db_temp.t_temp_file_sds', $file);
            // echo "filename : ".$filename;
        }

        $data = [           
            'tanggal_terima'    => $tanggal_terima,
            'nama_penerima'     => $nama_penerima,
            'no_terima'         => $no_terima,
            'file_terima'       => $filename,
            'status'            => '8',
            'nama_status'       => 'barang di terima oleh principal',
            'last_updated'      => $created_date,
            'last_updated_by'   => $this->session->userdata('id'),
        ];
        $proses = $this->model_retur->update_pengajuan_retur($data,$id);
        // echo "proses : ".$proses;
        if ($proses) {
            // var_dump($proses);
            
            echo '<script>alert("pengisian data penerimaan barang retur sukses !");</script>';
            // redirect('retur/pengajuan/','refresh');

            //jalankan email

            $this->email_terima_barang($signature);

            
        }else{
            echo '<script>alert("Tidak berhasil menginput data. Jika perlu bantuan silahkan hubungi IT !");</script>';
            redirect('retur/pengajuan/','refresh');
            // var_dump($proses);
        }

        
    }

    public function email_terima_barang($signature){

        $id = $this->model_retur->get_id_by_signature($signature);
        
        // $sql = "
        //     update db_temp.t_temp_pengajuan_retur a 
        //     set a.status = 7, a.nama_status = 'proses principal terima barang'
        //     where a.id = $id
        // ";
        // $hasil = $this->db->query($sql);

        // $data = [
        //     'history'     => $this->model_retur->generate_attachment_pengajuan_retur($id),
        // ];
        // var_dump($data['history']);die;
        $get_pengajuan = $this->model_retur->get_pengajuan_retur($id);
        // var_dump($get_pengajuan);die;
        foreach ($get_pengajuan as $key) {
            $no_pengajuan = $key->no_pengajuan;
            $site_code = $key->site_code;
            $nama = $key->nama;
            $principal = $key->supp;
            $tanggal_pengajuan = $key->tanggal_pengajuan;
            $tanggal_approval = $key->tanggal_approval;
            $keterangan_principal = $key->keterangan_principal;
            $nama_status = $key->nama_status;
            $file = $key->file;
            $file_principal = $key->file_principal;
            $file_pengiriman = $key->file_pengiriman;
            $file_terima = $key->file_terima;
            $branch_name = $key->branch_name;
            $nama_comp = $key->nama_comp;
            $namasupp = $key->namasupp;
            $company = $key->company;
            $tanggal_kirim_barang = $key->tanggal_kirim_barang;
            $nama_ekspedisi = $key->nama_ekspedisi;
            $est_tanggal_tiba = $key->est_tanggal_tiba;
            $nama_penerima = $key->nama_penerima;
            $no_terima = $key->no_terima;
            $tanggal_terima = $key->tanggal_terima;
        }

        $get_email_dp = $this->model_retur->get_email_dp($site_code);
        foreach ($get_email_dp as $key) {
            $email_dp = $key->email;
        } 

        $get_email_principal = $this->model_retur->get_email_principal($principal);
        foreach ($get_email_principal as $key) {
            $email_principal = $key->email;
        } 

        $data=[
            "no_pengajuan" => $no_pengajuan,
            "site_code" => $site_code,
            "nama" => $nama,
            "principal" => $principal,
            "tanggal_pengajuan" => $tanggal_pengajuan,
            "tanggal_approval" => $tanggal_approval,
            "keterangan_principal" => $keterangan_principal,
            "nama_status" => $nama_status,
            "file" => $file,
            "branch_name" => $branch_name,
            "nama_comp" => $nama_comp,
            "namasupp" => $namasupp,
            "company" => $company,
            "file_principal" => $file_principal,
            "file_pengiriman" => $file_pengiriman,
            "file_terima" => $file_terima,
            "nama_ekspedisi" => $nama_ekspedisi,
            "tanggal_kirim_barang" => $tanggal_kirim_barang,
            "est_tanggal_tiba" => $est_tanggal_tiba,
            "nama_penerima" => $nama_penerima,
            "no_terima" => $no_terima,
            "tanggal_terima" => $tanggal_terima,
        ];
        
        $from = "linda@muliaputramandiri.com";
        // $to = "suffy.mpm@gmail.com";
        $to = $email_dp;
        $data['to'] = $to;
        $cc = "suffy@muliaputramandiri.com, ilham@muliaputramandiri.com, linda@muliaputramandiri.com, fakhrul@muliaputramandiri.com, kezia@muliaputramandiri.com"; 
        $subject = "MPM Site|Request Retur - $no_pengajuan";

        $message = $this->load->view("retur/email_terima_barang",$data,TRUE);
        // $this->email->initialize($config);
        $this->email();
        $this->email->from($from,'PT. Mulia Putra Mandiri');
        $this->email->to($to);
        $this->email->cc($cc);
        $this->email->subject($subject);
        $this->email->message($message);
        // $this->email->attach('assets/file/retur/'.$file);
        $this->email->attach('assets/file/retur/'.str_replace('/','_',$no_pengajuan).'.csv');
        $this->email->attach('assets/file/retur/'.str_replace('/','_',$no_pengajuan).'.pdf');
        $this->email->attach('assets/file/retur/'.$file_principal);
        $this->email->attach('assets/file/retur/'.$file_pengiriman);
        $this->email->attach('assets/file/retur/'.$file_terima);
        $send = $this->email->send();
        // echo $this->email->print_debugger();
        if ($send) {
            echo "<script>alert('pengiriman email berhasil'); </script>";
            redirect('retur/pengajuan/','refresh');
        }else{
            echo "<script>alert('pengiriman email gagal'); </script>";
            redirect('retur/pengajuan/','refresh');
        }
    }

    public function pemusnahan($signature){
        $id = $this->model_retur->get_id_by_signature($signature);
        // echo "id : ".$id;

        $get_kode_alamat = $this->model_inventory->get_kode_alamat();
        $code = '';
        foreach ($get_kode_alamat as $key) {
            $code.= ","."'".$key->kode_alamat."'";
        }
        $kode_alamat = preg_replace('/,/', '', $code,1);

        $data = [
            'id'          => $this->session->userdata('id'),
            'title'       => 'Isi data pemusnahan barang',
            'get_label'   => $this->M_menu->get_label(),
            'site_code'   => $this->M_helpdesk->get_sitecode(),
            'history'     => $this->model_retur->history_pengajuan_retur($kode_alamat,''),
            'url'         => 'retur/proses_pemusnahan'
        ];

        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar', $data);
        $this->load->view('retur/pemusnahan', $data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');

    }    

    public function proses_pemusnahan(){

        $tanggal_pemusnahan = $this->input->post('tanggal_pemusnahan');
        $nama_pemusnahan = $this->input->post('nama_pemusnahan');
        $signature = $this->input->post('signature');
        $file_pemusnahan = $this->input->post('file_pemusnahan');
        $foto_pemusnahan_1 = $this->input->post('foto_pemusnahan_1');
        $foto_pemusnahan_2 = $this->input->post('foto_pemusnahan_2');
 
        $created_date = $this->model_outlet_transaksi->timezone();

        $id = $this->model_retur->get_id_by_signature($signature);

        if (!is_dir('./assets/file/retur/')) {
            @mkdir('./assets/file/retur/', 0777);
        }

        //konfigurasi upload
        $config['upload_path'] = './assets/file/retur/';
        $config['allowed_types'] = '*';
        $config['max_size'] = '*';
        // $config['encrypt_name'] = TRUE;
        // $config['file_name'] = $site_code.$name.'_'.$time;

        $this->load->library('upload', $config);
        if (!$this->upload->do_upload('file_pemusnahan')) {
            // echo '<script>alert("File tidak sesuai. Pastikan File nya sudah benar. Jika perlu bantuan silahkan hubungi IT !");</script>';
            // redirect('all_upload/', 'refresh');
            // var_dump($this->upload->display_errors());
            echo "gagal upload";
            $filename = '';
        } else {

            $data = array('upload_data' => $this->upload->data());
            $file = $data['upload_data']['full_path'];
            chmod($file, 0777);

            //mengambil
            $upload_data = $this->upload->data();
            $filename = $upload_data['file_name'];
            // $proses = $this->db->insert('db_temp.t_temp_file_sds', $file);
            // echo "filename : ".$filename;
        }

        if (!$this->upload->do_upload('foto_pemusnahan_1')) {
            // echo '<script>alert("File tidak sesuai. Pastikan File nya sudah benar. Jika perlu bantuan silahkan hubungi IT !");</script>';
            // redirect('all_upload/', 'refresh');
            // var_dump($this->upload->display_errors());
            echo "gagal upload";
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

        if (!$this->upload->do_upload('foto_pemusnahan_2')) {
            // echo '<script>alert("File tidak sesuai. Pastikan File nya sudah benar. Jika perlu bantuan silahkan hubungi IT !");</script>';
            // redirect('all_upload/', 'refresh');
            // var_dump($this->upload->display_errors());
            echo "gagal upload";
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

        $data = [         
            'tanggal_pemusnahan'    => $tanggal_pemusnahan,
            'nama_pemusnahan'       => $nama_pemusnahan,
            'file_pemusnahan'       => $filename,
            'foto_pemusnahan_1'       => $filename_1,
            'foto_pemusnahan_2'       => $filename_2,
            'status'                => '9',
            'nama_status'           => 'pemusnahan oleh dp',
            'last_updated'            => $created_date,
            'last_updated_by'         => $this->session->userdata('id'),
        ];
        $proses = $this->model_retur->update_pengajuan_retur($data,$id);
        // echo "proses : ".$proses;
        if ($proses) {
            // var_dump($proses);
            
            echo '<script>alert("pemusnahan barang retur sukses !");</script>';
            // redirect('retur/pengajuan/','refresh');

            //jalankan email

            $this->email_pemusnahan($signature);

            
        }else{
            echo '<script>alert("Tidak berhasil menginput data. Jika perlu bantuan silahkan hubungi IT !");</script>';
            redirect('retur/pengajuan/','refresh');
            // var_dump($proses);
        }

        
    }

    public function email_pemusnahan($signature){

        $id = $this->model_retur->get_id_by_signature($signature);
        
        // $sql = "
        //     update db_temp.t_temp_pengajuan_retur a 
        //     set a.status = 7, a.nama_status = 'proses principal terima barang'
        //     where a.id = $id
        // ";
        // $hasil = $this->db->query($sql);

        // $data = [
        //     'history'     => $this->model_retur->generate_attachment_pengajuan_retur($id),
        // ];
        // var_dump($data['history']);die;
        $get_pengajuan = $this->model_retur->get_pengajuan_retur($id);
        // var_dump($get_pengajuan);die;
        foreach ($get_pengajuan as $key) {
            $no_pengajuan = $key->no_pengajuan;
            $site_code = $key->site_code;
            $nama = $key->nama;
            $principal = $key->supp;
            $tanggal_pengajuan = $key->tanggal_pengajuan;
            $tanggal_approval = $key->tanggal_approval;
            $keterangan_principal = $key->keterangan_principal;
            $nama_status = $key->nama_status;
            $file = $key->file;
            $file_principal = $key->file_principal;
            $file_pengiriman = $key->file_pengiriman;
            $file_pemusnahan = $key->file_pemusnahan;
            $branch_name = $key->branch_name;
            $nama_comp = $key->nama_comp;
            $namasupp = $key->namasupp;
            $company = $key->company;
            $tanggal_kirim_barang = $key->tanggal_kirim_barang;
            $nama_ekspedisi = $key->nama_ekspedisi;
            $est_tanggal_tiba = $key->est_tanggal_tiba;
            $tanggal_pemusnahan = $key->tanggal_pemusnahan;
            $nama_pemusnahan = $key->nama_pemusnahan;
            $foto_pemusnahan_1 = $key->foto_pemusnahan_1;
            $foto_pemusnahan_2 = $key->foto_pemusnahan_2;
        }

        $get_email_dp = $this->model_retur->get_email_dp($site_code);
        foreach ($get_email_dp as $key) {
            $email_dp = $key->email;
        } 

        $get_email_principal = $this->model_retur->get_email_principal($principal);
        foreach ($get_email_principal as $key) {
            $email_principal = $key->email;
        } 

        $data=[
            "no_pengajuan" => $no_pengajuan,
            "site_code" => $site_code,
            "nama" => $nama,
            "principal" => $principal,
            "tanggal_pengajuan" => $tanggal_pengajuan,
            "tanggal_approval" => $tanggal_approval,
            "keterangan_principal" => $keterangan_principal,
            "nama_status" => $nama_status,
            "file" => $file,
            "branch_name" => $branch_name,
            "nama_comp" => $nama_comp,
            "namasupp" => $namasupp,
            "company" => $company,
            "file_principal" => $file_principal,
            "file_pengiriman" => $file_pengiriman,
            "nama_ekspedisi" => $nama_ekspedisi,
            "tanggal_kirim_barang" => $tanggal_kirim_barang,
            "est_tanggal_tiba" => $est_tanggal_tiba,
            "tanggal_pemusnahan" => $tanggal_pemusnahan,
            "nama_pemusnahan" => $nama_pemusnahan,
            "file_pemusnahan" => $file_pemusnahan,
            "foto_pemusnahan_1" => $foto_pemusnahan_1,
            "foto_pemusnahan_2" => $foto_pemusnahan_2,
        ];
        
        $from = "linda@muliaputramandiri.com";
        $to = $email_principal;
        // $to = $email_principal;
        $data['to'] = $to;
        $cc = $email_dp.",suffy@muliaputramandiri.com, ilham@muliaputramandiri.com, linda@muliaputramandiri.com, fakhrul@muliaputramandiri.com, kezia@muliaputramandiri.com"; 
        $subject = "MPM Site|Request Retur - $no_pengajuan";

        $message = $this->load->view("retur/email_pemusnahan",$data,TRUE);
        // $this->email->initialize($config);
        $this->email();
        $this->email->from($from,'PT. Mulia Putra Mandiri');
        $this->email->to($to);
        $this->email->cc($cc);
        $this->email->subject($subject);
        $this->email->message($message);
        // $this->email->attach('assets/file/retur/'.$file);
        $this->email->attach('assets/file/retur/'.$foto_pemusnahan_1);
        $this->email->attach('assets/file/retur/'.$foto_pemusnahan_2);
        $this->email->attach('assets/file/retur/'.str_replace('/','_',$no_pengajuan).'.csv');
        $this->email->attach('assets/file/retur/'.str_replace('/','_',$no_pengajuan).'.pdf');
        $this->email->attach('assets/file/retur/'.$file_principal);
        $this->email->attach('assets/file/retur/'.$file_pemusnahan);
        $send = $this->email->send();
        // echo $this->email->print_debugger();
        if ($send) {
            echo "<script>alert('pengiriman email berhasil'); </script>";
            redirect('retur/pengajuan/','refresh');
        }else{
            echo "<script>alert('pengiriman email gagal'); </script>";
            redirect('retur/pengajuan/','refresh');
        }
    }

    public function generate_pdf($supp = '', $signature = ''){
        $this->load->library('mypdf');

        $id = $this->model_retur->get_id_by_signature($signature);
        $get_pengajuan = $this->model_retur->get_pengajuan_retur($id);
        $supp = $this->uri->segment('3');
        // var_dump($supp);die;

        // die;
        // var_dump($get_pengajuan);die;
        foreach ($get_pengajuan as $key) {
            $no_pengajuan = $key->no_pengajuan;
            $site_code = $key->site_code;
            $nama = $key->nama;
            $principal = $key->supp;
            $tanggal_pengajuan = $key->tanggal_pengajuan;
            $file = $key->file;
            $branch_name = $key->branch_name;
            $nama_comp = $key->nama_comp;
            $namasupp = $key->namasupp;
            $filename_pdf = str_replace('/','_',$no_pengajuan);
            $company = $key->company;
        }


        $data=[
            "no_pengajuan" => $no_pengajuan,
            "site_code" => $site_code,
            "nama" => $nama,
            "principal" => $principal,
            "tanggal_pengajuan" => $tanggal_pengajuan,
            "file" => $file,
            "branch_name" => $branch_name,
            "nama_comp" => $nama_comp,
            "namasupp" => $namasupp,
            "company" => $company,
            "detail" => $this->model_retur->get_produk_pengajuan($id, $supp),
            "get_discount" => $this->model_retur->get_discount($id),
            "get_total" => $this->model_retur->get_total($id)
        ];

        if ($supp == '001' || $supp == '002') {
            $generate_pdf = $this->mypdf->generate('retur/template_pdf_retur_principal',$data,$filename_pdf,'A4','portrait');
        }elseif($supp == '005'){
            $generate_pdf = $this->mypdf->generate('retur/template_pdf_retur_principal_us',$data,$filename_pdf,'A4','portrait');
        }elseif($supp == '012'){
            $generate_pdf = $this->mypdf->generate('retur/template_pdf_retur_principal_intrafood',$data,$filename_pdf,'A3','portrait');
        }

    }

    public function download_template()
    {
        $signature = $this->uri->segment('3');
        $supp = $this->uri->segment('4');
        // var_dump($supp);die;
        if($supp == '005'){
            $sql ="
                select '' as kodeprod, '' as batch_number, '' as 'expired_date (MM/DD/YYYY)', '' as jumlah, '' as kode_satuan, '' as kode_alasan, '' as nama_outlet, '' as keterangan
            ";
        }else{
            $sql ="
                select '' as kodeprod, '' as batch_number, '' as 'expired_date (MM/DD/YYYY)', '' as jumlah, '' as alasan
            ";
        }

        $proses = $this->db->query($sql);       
        
        query_to_csv($proses,TRUE,'pengajuan_retur.csv');
    }

    public function upload_pengajuan_retur()
    {
        $id = $this->session->userdata('id');
        // if ($id != 547) {
        //     echo "
        //     <script> 
        //         window.alert('Menu ini sedang maintenance !');
        //         window.location=history.go(-1);
        //     </script>";
        //     die;
        // }
        if (!is_dir('./assets/uploads/retur/' . date('Ym') . '/')) {
        @mkdir('./assets/uploads/retur/' . date('Ym') . '/', 0777);
        }

        // if ($supp == '012') { // jika intrafood maka gunakan karton
        //     $params_satuan = 'jumlah_karton';
        // }else{ // jika selain intrafood maka gunakan unit
        //     $params_satuan = 'jumlah_unit';
        // }

        $id = $this->session->userdata('id');
        // $this->db->where('created_by',$id)
        //         ->delete('db_temp.t_temp_pengajuan_retur_import');
        $signature = $this->uri->segment(3);
        $supp = $this->uri->segment(4);
        $id_retur = $this->db->select('id')
                ->where('signature',$signature)
                ->get('db_temp.t_temp_pengajuan_retur')->row();
        // var_dump($id_retur->id);die;
        // $tgl_created = date('Y-m-d H:i:s');
        $created_date = $this->model_outlet_transaksi->timezone();
        $this->load->library('upload'); // Load librari upload        
        $config['upload_path'] = './assets/uploads/retur/' . date('Ym') . '';
        // $config['allowed_types'] = 'xls|xlsx|csv';
        $config['allowed_types'] = '*';    
        $config['max_size']  = '2048';
        $config['overwrite'] = true;
        $this->upload->initialize($config);

        if ($this->upload->do_upload('file')) 
        {
            $upload_data = $this->upload->data();
            $filename = $upload_data['orig_name'];
            $file_type = $upload_data['file_type'];
            //   echo "filename : ".$filename."<br>";
            //   echo "file_type : ".$file_type;
            $this->load->library('excel');
            $object = PHPExcel_IOFactory::load("assets/uploads/retur/" . date('Ym') . "/$filename");

            $jumlahSheet = $object->getSheetCount();
            // echo "jumlahsheet : ".$jumlahSheet."<br>";
            // die;
            if ($jumlahSheet > 1) {
                echo "jumlah_sheet : ".$jumlahSheet;
                echo "<script>alert('upload file gagal karena file mempunyai lebih dari 1 sheet'); </script>";
                redirect('retur','refresh');
            }

            $created_date = $this->model_outlet_transaksi->timezone();

            if($supp == '005'){
                foreach ($object->getWorksheetIterator() as $worksheet) {

                    $highestRow = $worksheet->getHighestRow();
                    $highestColumn = $worksheet->getHighestColumn();
    
                    for ($row = 2; $row <= $highestRow; $row++) {          
                    
                        $kodeprod = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
                        $batch_number = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
                        $expired = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
                        $jumlah = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
                        $satuan = $worksheet->getCellByColumnAndRow(4, $row)->getValue();
                        $alasan = $worksheet->getCellByColumnAndRow(5, $row)->getValue();
                        $nama_outlet = $worksheet->getCellByColumnAndRow(6, $row)->getValue();
                        $keterangan = $worksheet->getCellByColumnAndRow(7, $row)->getValue();
    
                        // echo "<pre>";
                        // echo "kodeprod : ".$kodeprod."<br>";
                        // // echo "namaprod : ".$namaprod."<br>";
                        // echo "batch_number : ".$batch_number."<br>";
                        // echo "expired : ".$expired."<br>";
                        // echo "jumlah : ".$jumlah."<br>";
                        // echo "alasan : ".$alasan."<br>";
                        // echo "satuan : ".$satuan."<br>";
                        // echo "</pre>";

                        // die;
                        
                        if($kodeprod == null || $kodeprod == ''){
                            echo "
                            <script> 
                                window.alert('Import Gagal.\\nAda data kosong dikolom kodeprod, baris ke $row. Silahkan cek kembali');
                                window.location=history.go(-1);
                            </script>";
                            die;
                        }
                        if($batch_number == null || $batch_number == ''){
                            echo "
                            <script> 
                                window.alert('Import Gagal.\\nAda data kosong dikolom batch_number, baris ke $row. Silahkan cek kembali');
                                window.location=history.go(-1);
                            </script>";
                            die;
                        }
                        if($expired == null || $expired == ''){
                            echo "
                            <script> 
                                window.alert('Import Gagal.\\nAda data kosong dikolom expired, baris $row. Silahkan cek kembali');
                                window.location=history.go(-1);
                            </script>";
                            die;
                        }
                        if($jumlah == null || $jumlah == ''){
                            echo "
                            <script> 
                                window.alert('Import Gagal.\\nAda data kosong dikolom jumlah, baris $row. Silahkan cek kembali');
                                window.location=history.go(-1);
                            </script>";
                            die;
                        }
                        if($satuan == null || $satuan == ''){
                            echo "
                            <script> 
                                window.alert('Import Gagal.\\nAda data kosong dikolom satuan, baris $row. Silahkan cek kembali');
                                window.location=history.go(-1);
                            </script>";
                            die;
                        }
                        if($alasan == null || $alasan == ''){
                            echo "
                            <script> 
                                window.alert('Import Gagal.\\nAda data kosong dikolom alasan, baris $row. Silahkan cek kembali');
                                window.location=history.go(-1);
                            </script>";
                            die;
                        }
                        if($nama_outlet == null || $nama_outlet == ''){
                            echo "
                            <script> 
                                window.alert('Import Gagal.\\nAda data kosong dikolom nama_outlet, baris $row. Silahkan cek kembali');
                                window.location=history.go(-1);
                            </script>";
                            die;
                        }
                        if($keterangan == null || $keterangan == ''){
                            echo "
                            <script> 
                                window.alert('Import Gagal.\\nAda data kosong dikolom keterangan, baris $row. Silahkan cek kembali');
                                window.location=history.go(-1);
                            </script>";
                            die;
                        }
                        // die;
                        if($satuan == '1'){
                            $satuanx = 'sachet';
                        }elseif($satuan == '2'){
                            $satuanx ='botol';
                        }elseif($satuan == '3'){
                            $satuanx ='amplop';
                        }elseif($satuan == '4'){
                            $satuanx ='tube';
                        }elseif($satuan == '5'){
                            $satuanx ='pump';
                        }elseif($satuan == '6'){
                            $satuanx ='patch';
                        }elseif($satuan == '7'){
                            $satuanx ='pouch';
                        }elseif($satuan == '8'){
                            $satuanx ='box';
                        }elseif($satuan == '9'){
                            $satuanx ='pot';
                        }

                        // echo "satuan : ".$satuan;
                        // echo "satuanx : ".$satuanx;
                        // die;

                        if(strlen("$kodeprod") == '5')
                        {
                            $kodeprodx = '0'.$kodeprod;
                        }else{
                            $kodeprodx = $kodeprod;
                        } 
    
                        $data = [
                            'id_pengajuan' => $id_retur->id,
                            'kodeprod' => $kodeprodx,
                            'batch_number' => $batch_number,
                            'expired_date' => date('Y-m-d',strtotime($expired)),
                            'jumlah' => $jumlah,
                            'satuan' => $satuanx,
                            'alasan' => $alasan,
                            'nama_outlet' => $nama_outlet,
                            'keterangan' => $keterangan,
                            'signature' => $signature,
                            'created_by' => $id,
                            'created_date' => $created_date
                        ];

                        $controller_produk_import = 'produk_import';

                        $this->db->insert('db_temp.t_temp_pengajuan_retur_import',$data);

                        // $this->db->where('id_pengajuan',$id_retur->id);
                        // $this->db->where('kodeprod',$kodeprodx);
                        // $this->db->where('batch_number',"$batch_number");
                        // $this->db->where('expired_date',date('Y-m-d',strtotime($expired)));
                        // $this->db->where('jumlah',$jumlah);
                        // $this->db->where('satuan',"$satuanx");
                        // $this->db->where('alasan',"$alasan");
                        // $this->db->where('nama_outlet',$nama_outlet);
                        // $this->db->where('keterangan',"$keterangan");
                        // $this->db->where('created_by',"$id");
                        // $x = $this->db->get('db_temp.t_temp_pengajuan_retur_import')->num_rows();

                        // if ( $x >= 1 ) 
                        // {
                        //     // echo "update":
                        //     $this->db->where('id_pengajuan',$id_retur->id);
                        //     $this->db->where('kodeprod',$kodeprodx);
                        //     $this->db->where('batch_number',"$batch_number");
                        //     $this->db->where('expired_date',date('Y-m-d',strtotime($expired)));
                        //     $this->db->where('jumlah',$jumlah);
                        //     $this->db->where('satuan',"$satuan");
                        //     $this->db->where('alasan',"$alasan");
                        //     $this->db->where('nama_outlet',$nama_outlet);
                        //     $this->db->where('keterangan',"$keterangan");
                        //     // $this->db->where('created_by',"$id");
                        //     $this->db->update('db_temp.t_temp_pengajuan_retur_import',$data);
                        // }else{
                        //     echo "insert";
                        //     $this->db->insert('db_temp.t_temp_pengajuan_retur_import',$data);
                        // }
                    }
                }
            }else{
                foreach ($object->getWorksheetIterator() as $worksheet) {

                    $highestRow = $worksheet->getHighestRow();
                    $highestColumn = $worksheet->getHighestColumn();
    
                    for ($row = 2; $row <= $highestRow; $row++) {          
                    
                        $kodeprod = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
                        $batch_number = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
                        $expired = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
                        $jumlah = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
                        $alasan = $worksheet->getCellByColumnAndRow(4, $row)->getValue();
    
                        // echo "<pre>";
                        // echo "kodeprod : ".$kodeprod."<br>";
                        // echo "namaprod : ".$namaprod."<br>";
                        // echo "batch_number : ".$batch_number."<br>";
                        // echo "expired : ".$expired."<br>";
                        // echo "jumlah : ".$jumlah."<br>";
                        // echo "alasan : ".$alasan."<br>";
                        
                        if($kodeprod == null || $kodeprod == ''){
                            echo "
                            <script> 
                                window.alert('Import Gagal.\\nAda data kosong dikolom kodeprod, baris ke $row. Silahkan cek kembali');
                                window.location=history.go(-1);
                            </script>";
                            die;
                        }
                        if($batch_number == null || $batch_number == ''){
                            echo "
                            <script> 
                                window.alert('Import Gagal.\\nAda data kosong dikolom batch_number, baris ke $row. Silahkan cek kembali');
                                window.location=history.go(-1);
                            </script>";
                            die;
                        }
                        if($expired == null || $expired == ''){
                            echo "
                            <script> 
                                window.alert('Import Gagal.\\nAda data kosong dikolom expired, baris $row. Silahkan cek kembali');
                                window.location=history.go(-1);
                            </script>";
                            die;
                        }
                        if($jumlah == null || $jumlah == ''){
                            echo "
                            <script> 
                                window.alert('Import Gagal.\\nAda data kosong dikolom jumlah, baris $row. Silahkan cek kembali');
                                window.location=history.go(-1);
                            </script>";
                            die;
                        }
                        if($alasan == null || $alasan == ''){
                            echo "
                            <script> 
                                window.alert('Import Gagal.\\nAda data kosong dikolom alasan, baris $row. Silahkan cek kembali');
                                window.location=history.go(-1);
                            </script>";
                            die;
                        }
                        // die;
                        if(strlen("$kodeprod") == '5')
                        {
                            $kodeprodx = '0'.$kodeprod;
                        }else{
                            $kodeprodx = $kodeprod;
                        } 
    
                        $data = [
                            'id_pengajuan' => $id_retur->id,
                            'kodeprod' => $kodeprodx,
                            'batch_number' => $batch_number,
                            'expired_date' => date('Y-m-d',strtotime($expired)),
                            'jumlah' => $jumlah,
                            'alasan' => $alasan,
                            'signature' => $signature,
                            'created_by' => $id,
                            'created_date' => $created_date
                        ];

                        $this->db->insert('db_temp.t_temp_pengajuan_retur_import',$data);

                        $controller_produk_import = 'produk_import_deltomed';
                        
                        // $this->db->where('id_pengajuan',$id_retur->id);
                        // $this->db->where('kodeprod',$kodeprodx);
                        // $this->db->where('batch_number',"$batch_number");
                        // $this->db->where('expired_date',date('Y-m-d',strtotime($expired)));
                        // $this->db->where('jumlah',$jumlah);
                        // $this->db->where('alasan',"$alasan");
                        // $this->db->where('signature',"$signature");
                        // $this->db->where('created_by',"$id");
                        // $x = $this->db->get('db_temp.t_temp_pengajuan_retur_import')->num_rows();

                        // echo "x : ".$x;
                        // print_r($x);
                        // die;

                        // $x = 0;
                        // if ( $x->num_rows() > 0 ) 
                        // if ($x >= 1) 
                        // {
                        //     // echo "update";
                        //     // die;
                        //     $this->db->where('id_pengajuan',$id_retur->id);
                        //     $this->db->where('kodeprod',$kodeprodx);
                        //     $this->db->where('batch_number',"$batch_number");
                        //     $this->db->where('expired_date',date('Y-m-d',strtotime($expired)));
                        //     $this->db->where('jumlah',$jumlah);
                        //     $this->db->where('alasan',"$alasan");
                        //     $this->db->where('signature',"$signature");
                        //     // $this->db->where('created_by',"$id");
                        //     $this->db->update('db_temp.t_temp_pengajuan_retur_import',$data);
                        // }else{
                        //     // echo "insert";
                        //     // die;
                        //     $this->db->insert('db_temp.t_temp_pengajuan_retur_import',$data);
                        // }
                    }
                }
            }
            echo "<script>alert('Import Berhasil'); </script>";
            redirect("retur/$controller_produk_import/$signature/$supp");
        } else {
            echo "<script>alert('Import Gagal'); </script>";
            redirect("retur/produk_pengajuan/$signature/$supp");
        }
    }

    public function produk_import()
    {
        $data = [
            'id' => $this->session->userdata('id'),
            'title' => 'Pengajuan Retur - Import CSV | Jika sistem menemukan kodeprod, batch number, nama outlet yang sama maka otomatis akan di SUM. Mohon cek kembali data anda, jika sudah tepat klik Simpan',
            'get_label' => $this->M_menu->get_label(),
            'product' => $this->model_retur->get_tableimport_product()->result(),
            'signature' => $this->uri->segment(3),
            'supp' => $this->uri->segment(4)
        ];

        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar', $data);
        $this->load->view('template_claim/top_content', $data);
        $this->load->view( 'retur/produk_import', $data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
    }

    public function produk_import_deltomed()
    {
        $data = [
            'id' => $this->session->userdata('id'),
            'title' => 'Pengajuan Retur | Import CSV | Mohon cek kembali data anda, jika sudah tepat klik Simpan',
            'get_label' => $this->M_menu->get_label(),
            'url' => 'retur/produk_import_simpan_deltomed',
            'product' => $this->model_retur->get_tableimport_product_deltomed()->result(),
            'signature' => $this->uri->segment(3),
            'supp' => $this->uri->segment(4)
        ];

        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar', $data);
        $this->load->view('template_claim/top_content', $data);
        $this->load->view( 'retur/produk_import', $data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
    }

    public function produk_import_simpan($signature, $supp)
    {
        // $product = $this->model_retur->get_dataimport_product()->result();

        if ($supp == '005') {
            # code...
            $product = $this->model_retur->get_tableimport_product()->result();
        }else{
            $product = $this->model_retur->get_tableimport_product_deltomed()->result();

        }

        // var_dump($product);
        // die;

        $created_date = $this->model_outlet_transaksi->timezone();
        // $signature = $this->uri->segment(3);
        // $supp = $this->uri->segment(4);
        foreach($product as $a) {
            $data = [
                'id_pengajuan' => $a->id_pengajuan,
                'kodeprod' => $a->kodeprod,
                'namaprod' => $a->namaprod,
                'batch_number' =>"$a->batch_number",
                'expired_date' => $a->expired_date,
                'jumlah' => $a->jumlah,
                'satuan' => $a->satuan,
                'alasan' => $a->alasan,
                'nama_outlet' => $a->nama_outlet,
                'keterangan' => $a->keterangan,
                'total_karton' => '0',
                'total_dus' => '0',
                'total_pcs' => '0',
                'kode_produksi' => '0',
                'status' => '1',
                'nama_status' => 'pending di dp',
                'deleted' => null,
            ];

            // echo "satuan : ".$a->satuan;
            // die;
            // $this->db->insert('db_temp.t_temp_produk_pengajuan_retur',$data);
            
            if ($supp == '005') {
                $this->db->where('id_pengajuan',$a->id_pengajuan);
                $this->db->where('kodeprod',$a->kodeprod);
                $this->db->where('batch_number',"$a->batch_number");
                $this->db->where('expired_date',$a->expired_date);
                // $this->db->where('jumlah',$a->jumlah);
                // $this->db->where('satuan',"$a->satuan");
                // $this->db->where('alasan',"$a->alasan");
                $this->db->where('nama_outlet',$a->nama_outlet);
                // $this->db->where('keterangan',"$a->keterangan");
                $this->db->where('status','1');
                $x = $this->db->get('db_temp.t_temp_produk_pengajuan_retur');

                // echo "x : ".$x->num_rows();

                // die;

                if ( $x->num_rows() > 0 ) 
                {        
                    $this->db->where('id_pengajuan',$a->id_pengajuan);
                    $this->db->where('kodeprod',$a->kodeprod);
                    $this->db->where('batch_number',"$a->batch_number");
                    $this->db->where('expired_date',$a->expired_date);
                    // $this->db->where('jumlah',$a->jumlah);
                    // $this->db->where('satuan',$a->satuan);
                    // $this->db->where('alasan',"$a->alasan");
                    $this->db->where('nama_outlet',$a->nama_outlet);
                    // $this->db->where('keterangan',"$a->keterangan");
                    $this->db->where('status','1');
                    $this->db->update('db_temp.t_temp_produk_pengajuan_retur',$data);
                } else {
                    $this->db->insert('db_temp.t_temp_produk_pengajuan_retur',$data);
                }
            }else{
                $this->db->where('id_pengajuan',$a->id_pengajuan);
                $this->db->where('kodeprod',$a->kodeprod);
                $this->db->where('batch_number',"$a->batch_number");
                $this->db->where('expired_date',$a->expired_date);
                // $this->db->where('jumlah',$a->jumlah);
                // $this->db->where('alasan',"$a->alasan");
                $this->db->where('status','1');
                $x = $this->db->get('db_temp.t_temp_produk_pengajuan_retur');

                // echo "kodeprod : ".$a->kodeprod;
                // echo "batch_number : ".$a->batch_number;
                // echo "expired_date : ".$a->expired_date;
                // echo "jumlah : ".$a->jumlah;
                // echo "alasan : ".$a->alasan;
                // echo "<br>x : ".$x->num_rows();

                // die;

                if ( $x->num_rows() > 0 ) 
                {        
                    $this->db->where('id_pengajuan',$a->id_pengajuan);
                    $this->db->where('kodeprod',$a->kodeprod);
                    $this->db->where('batch_number',"$a->batch_number");
                    $this->db->where('expired_date',$a->expired_date);
                    // $this->db->where('jumlah',$a->jumlah);
                    // $this->db->where('alasan',"$a->alasan");
                    $this->db->update('db_temp.t_temp_produk_pengajuan_retur',$data);
                } else {
                    $this->db->insert('db_temp.t_temp_produk_pengajuan_retur',$data);
                }
            }

            $id_ref = $this->db->query("select id from db_temp.t_temp_produk_pengajuan_retur
                where id_pengajuan = $a->id_pengajuan && kodeprod = $a->kodeprod && batch_number = '$a->batch_number'")->row();
                
            $data2 = [
                'id_ref' => $id_ref->id,
                'created_date' => $created_date,
                'created_by' => $this->session->userdata('id'),
                'action' => 'user add'
            ];
            $this->db->insert('db_temp.t_temp_produk_pengajuan_retur_log',$data2);
        }
        redirect("retur/produk_pengajuan/$signature/$supp");
    }

    public function produk_import_delete()
    {   
        $signature = $this->uri->segment('3');
        $supp = $this->uri->segment('4');
        $id = $this->uri->segment('5');
        $this->db->where('id',$id)->delete('db_temp.t_temp_pengajuan_retur_import');

        redirect('retur/produk_import/'.$signature.'/'.$supp,'refresh');
    }

    public function delete_retur($signature){

        $data = array(
            'deleted'   => '1',
        );

        $cek_status = "
            select *
            from db_temp.t_temp_pengajuan_retur a 
            where a.signature = '$signature'
        ";
        $proses_cek = $this->db->query($cek_status)->row();

        if ($proses_cek->status == '1') {
            $this->db->where('signature', $signature);
            $proses = $this->db->update('db_temp.t_temp_pengajuan_retur', $data); 

            if ($proses) {
                redirect('retur','refresh');
            }
            
        }else{
            echo '<script>alert("Delete gagal. Anda tidak bisa delete di tahap ini !");</script>';
            redirect('retur','refresh');
        }
        
    }

    public function approval_produk_pengajuan_ceklist()
    {
        $request = $this->input->post('options');
        $code = '';
        foreach($request as $kode)
        {
            $code.=",".$kode;
            $get_produk = $this->model_retur->get_produk_retur_by_id($kode);
            $id = $get_produk->id;
            $signature = $get_produk->signature;
            $supp = $get_produk->supp;
            $status = $this->input->post('status_approval');

            if ($this->input->post('status_approval') == '3') {
                $nama_status = 'verified';
            }else{
                $nama_status ='not verified';
            }

            $data = [ 
                "status"        => $this->input->post('status_approval'), 
                "nama_status"   => $nama_status,
                "deskripsi"     => $this->input->post('deskripsi'), 
            ];
            $proses = $this->model_retur->approval_produk_pengajuan($data,$id);

        }

        echo '<script>alert("Anda akan di redirect ke halaman sebelumnya !");</script>';
        redirect('retur/produk_pengajuan/'.$signature.'/'.$supp, 'refresh');

    }

    public function raw_data(){
        $from = $this->input->post('from');
        $to = $this->input->post('to');

        $get_kode_alamat = $this->model_inventory->get_kode_alamat();
        $code = '';
        foreach ($get_kode_alamat as $key) {
            $code.= ","."'".$key->kode_alamat."'";
        }
        $kode_alamat = preg_replace('/,/', '', $code,1);

        // echo "kode_alamat : ".$kode_alamat;

        $query="
        select 	a.nama_status as status_ajuan, a.site_code, d.branch_name, d.nama_comp, c.NAMASUPP as principal, a.no_pengajuan, a.tanggal_pengajuan, a.file, a.nama_status, a.keterangan_principal, a.file_principal,
                a.tanggal_approval, a.tanggal_kirim_barang, a.nama_ekspedisi, a.est_tanggal_tiba, a.tanggal_tiba, a.file_pengiriman, 
                a.tanggal_terima, a.nama_penerima, a.no_terima, a.file_terima, a.tanggal_pemusnahan, a.nama_pemusnahan, 
                a.file_pemusnahan, a.foto_pemusnahan_1, a.foto_pemusnahan_2, b.*, a.created_date
        from 	db_temp.t_temp_pengajuan_retur a INNER JOIN
        (
            select 	a.id_pengajuan, a.kodeprod, a.namaprod, a.batch_number, a.expired_date, a.jumlah, a.alasan, a.satuan, 
                    a.nama_outlet, a.keterangan, a.total_karton, a.total_dus, a.total_pcs, a.harga_karton, a.harga_dus,
                    a.value, a.kode_produksi, a.nama_status, a.deskripsi, a.deleted			
            from db_temp.t_temp_produk_pengajuan_retur a
        )b on a.id = b.id_pengajuan LEFT JOIN mpm.tabsupp c 
            on a.supp = c.SUPP LEFT JOIN 
        (
            select concat(a.kode_comp, a.nocab) as site_code, a.branch_name, a.nama_comp
            from mpm.tbl_tabcomp a
            where a.`status` = 1
            GROUP BY concat(a.kode_comp, a.nocab)
        )d on a.site_code = d.site_code
        where a.deleted is null and a.site_code in ($kode_alamat) and a.tanggal_pengajuan BETWEEN '$from' and '$to'
        ORDER BY a.site_code, a.no_pengajuan, b.kodeprod
        ";                        
        $hsl = $this->db->query($query);
        query_to_csv($hsl,TRUE,'Raw Data Pengajuan Retur.csv');
        // print_r($query);


    }

    public function log_retur()
    {
        $data = [
            'id' => $this->session->userdata('id'),
            'title' => 'Log Retur',
            'get_label' => $this->M_menu->get_label(),
            'site_code' => $this->M_helpdesk->get_sitecode(),
            'log' => $this->model_retur->log_retur($this->uri->segment('3'))->row()
        ];

        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar', $data);
        $this->load->view('retur/log_retur', $data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
    }
}
?>
