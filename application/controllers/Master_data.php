<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Master_data extends MY_Controller
{
    // public function __construct()
    // {
    //     // ini_set('memory_limit','-1');
    //     // ini_set('max-execution_time', '10');
    //     ini_set('memory_limit','-1');
    //     // ini_set('max-execution_time', '10');
    //     ini_set('MAX_EXECUTION_TIME', '-1');
    // }

    function master_data()
    {
        // ini_set('memory_limit','-1');
        // ini_set('max-execution_time', '10');
        // ini_set('MAX_EXECUTION_TIME', '-1');
        // ini_set('memory_limit','-1');
        // ini_set('max-execution_time', '50000');
        set_time_limit(0);
        $this->load->library(array('table','template','Excel_generator', 'form_validation','email'));
        $this->load->helper('url');
        $this->load->helper('csv');
        $this->load->model('M_menu');
        $this->load->model('model_request');
        $this->load->model('model_master_data');
        $this->load->model('model_per_hari');
        $this->load->model('model_sales_omzet');
        $this->load->model('model_outlet_transaksi');
        $this->load->model('model_rpd');
    }
    function index()
    {
        $this->model_master_data->get_salesman();
    }

    public function salesman()
    {
        $this->model_master_data->get_salesman();
    }

    public function customer()
    {
        $this->model_master_data->get_customer();
    }

    public function monitoring_stock()
    {
        $this->model_master_data->get_monitoring_stock();
    }

    public function monitoring_stock_harian()
    {
        $this->model_master_data->get_monitoring_stock_harian();
    }

    public function dashboard()
    {
        $this->load->model('model_outlet_transaksi');
        $created_date = $this->model_outlet_transaksi->timezone();
        $this->model_master_data->generate_dashboard($created_date);
    }

    public function dashboard_tahun_lalu()
    {
        $this->load->model('model_outlet_transaksi');
        $created_date = $this->model_outlet_transaksi->timezone();
        $this->model_master_data->generate_dashboard_tahun_lalu($created_date);
    }

    public function customer_intrafood()
    {
        $this->model_master_data->get_customer_intrafood();
    }

    

    public function export_customer_intrafood(){
        $query="
                select kode_lang, nama_lang, kodesalur, kode_type, alamat, nama_provinsi, 
                        nama_kota, nama_kecamatan, nama_kelurahan, branch_name, nama_comp, last_updated
                from db_master_data.t_customer_intrafood
                ";
                        
        $hasil = $this->db->query($query);
        query_to_csv($hasil,TRUE,"Master_Customer_Intrafood.csv");
    }

    public function po_outstanding_deltomed(){
        $this->model_master_data->get_po_outstanding_deltomed();
    }

    public function proses_schedule_truncate_auto(){
        $data['truncate'] = $this->model_master_data->proses_schedule_truncate_auto();
    }

    public function outlet(){
        $this->model_master_data->get_outlet();
    }

    public function insert_temp_suratjalan(){
        $this->model_master_data->insert_t_m_customer();
    }

    public function data_customer($id = ''){
        if ($id == 1) {
            $this->proses_data_customer();
        }else{

            $data = [
                'id'                => $this->session->userdata('id'),
                // 'url'               => 'master_data/proses_data_customer/',
                'url'               => 'master_data/data_customer/',
                'title'             => 'Master Customer',
                'get_label'         => $this->M_menu->get_label(),
                // 'get_customer'      => $this->model_master_data->master_customer(),
                's_code'            => $this->model_master_data->site_code()
            ];
            $this->load->view('template_claim/top_header');
            $this->load->view('template_claim/header');
            $this->load->view('template_claim/sidebar',$data);
            $this->load->view('template_claim/top_content',$data);
            $this->load->view('master_customer/data_customer',$data);
            $this->load->view('template_claim/bottom_content');
            $this->load->view('template_claim/footer');

        }

        

    }

    public function proses_data_customer(){
        $from = $this->input->post('from');
        $to = $this->input->post('to');
        $tahun_from = substr($from,0,4);
        $tahun_to = substr($to,0,4);
        // echo "<pre>";
        // echo "from : ".$from."<br>";
        // echo "to : ".$to."<br>";
        // echo "tahun_from : ".$tahun_from."<br>";
        // echo "tahun_to : ".$tahun_to."<br>";
        // echo "</pre>";
        // echo "<pre>";
        // echo "generating csv file";
        // echo "</pre>";

        if ($tahun_from - $tahun_to != 0) {
            echo '<script>alert("maaf saat ini belum dapat memproses dengan tahun yang berbeda");</script>';
            redirect('master_data/data_customer','refresh');
        }
        // die();
        $query="
        select  a.site_code, c.branch_name, c.nama_comp, a.kode_lang, a.tgl, b.alamat, b.nama_provinsi, b.nama_kota, b.nama_kecamatan, 
                b.nama_kelurahan, b.kodepos, b.telp, b.kode_type, b.kodesalur
        from
        (
            select 	concat(a.kode_comp, a.nocab) as site_code, concat(a.kode_comp, a.kode_lang) as kode_lang,
                    a.TGLDOKJDI, concat(a.thndok, '-', a.blndok, '-', a.hrdok) as tgl
            from data$tahun_from.fi a
            where concat(a.thndok, '-', a.blndok, '-', a.hrdok) between '$from' and '$to'
            GROUP BY concat(a.kode_comp, a.kode_lang)
        )a LEFT JOIN 
        (
            select 	concat(a.kode_comp,a.kode_lang) as kode_lang, a.kode_type, a.alamat1 as alamat, a.kodesalur, 
                    a.id_provinsi, a.nama_provinsi, a.kode_kota, a.nama_kota, a.id_kecamatan, a.nama_kecamatan,
                    a.id_kelurahan, a.nama_kelurahan, a.telp, a.kodepos, concat(a.kode_comp, a.nocab) as site_code
            from data$tahun_from.tblang a            
            GROUP BY concat(a.kode_comp,a.kode_lang)
        )b on a.kode_lang = b.kode_lang LEFT JOIN
        (
            select concat(a.kode_comp, a.nocab) as site_code, a.branch_name, a.nama_comp 
            from mpm.tbl_tabcomp a
            where a.`status` = 1
            GROUP BY concat(a.kode_comp, a.nocab)	
        )c on a.site_code = c.site_code 
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
                        
        $hasil = $this->db->query($query);
        query_to_csv($hasil,TRUE,"Master_Customer_$from.-.$to.csv");


    }

    public function get_mastercustomerid(){
        $customerID = $_GET['id'];
        // $data['edit']   = 'aas';
        $data['edit']   = $this->model_master_data->master_customer_by_ID($customerID)->row();
        echo json_encode($data);
    }

    public function edit_datacustomer()
    {
        $this->model_master_data->edit_datacustomer();
    }

    public function email_datacustomer($id){

        // var_dump($id);die;
        // echo "status_approve : ".$status_approve."<br>";
        $custID = $id;
        $get_detail = $this->model_master_data->master_customer_by_ID($custID)->result();
        // var_dump($get_detail);die;
        foreach ($get_detail as $key) {
            $kode           = $key->site_code;
            $name           = $key->name;
            $shop_name      = $key->shop_name;
            $address        = $key->address;
            $provinsi       = $key->provinsi;
            $kota           = $key->kota;
            $phone          = $key->phone;
            $kecamatan      = $key->kecamatan;
            $kelurahan      = $key->kelurahan;
            $kode_pos       = $key->kode_pos;
            $created_at     = $key->created_at;
        }

        // echo "kode : ".substr($kode,0,3)."<br>"; 
        // echo "branch_name : ".$branch_name."<br>"; 
        // echo "nama_comp : ".$nama_comp."<br>"; 
        // echo "signature : ".$signature."<br>"; 
        // echo "signaturex : ".$signaturex."<br>"; 
        
        $get_email_dp = $this->model_request->email_dp(substr($kode,0,3));
        foreach ($get_email_dp as $key) {
            $email_dp = $key->email;
        }
        

        $data['id'] = $id;
        $data['name'] = $name;
        $data['shop_name'] = $shop_name;
        $data['address'] = $address;
        $data['provinsi'] = $provinsi;
        $data['kota'] = $kota;
        $data['phone'] = $phone;
        $data['kecamatan'] = $kecamatan;
        $data['kelurahan'] = $kelurahan;
        $data['kode_pos'] = $kode_pos;
        $data['created_at'] = $created_at;

        // var_dump($data);die;

        $from = "suffy@muliaputramandiri.com";
        // $to = "suffy.mpm@gmail.com";
        $to = $email_dp; //setelah rilis
        // $to = "ilham@muliaputramandiri.com";
        $data['to'] = $to;
        $cc = "suffy.yanuar@gmail.com,ilham@muliaputramandiri.com,dewi@muliaputramandiri.com";
        $subject = "MPM Site|Register Customer Via Apps Semut Gajah";

        $message = $this->load->view("request/email_datacustomer_to_dp",$data,TRUE);
        $this->load->library('email');

        $config['protocol']     = 'smtp';
        $config['smtp_host']    = 'ssl://mail.muliaputramandiri.com';
        $config['smtp_port']    = '465';
        $config['smtp_timeout'] = '300';
        $config['smtp_user']    = 'support@muliaputramandiri.com';
        $config['smtp_pass']    = 'support123!@#';
        $config['charset']      = 'utf-8';
        $config['newline']      = "\r\n";
        $config['mailtype']     ="html";
        $config['use_ci_email'] = TRUE;
        $config['wordwrap']     = TRUE;

        // $config['protocol']     = 'smtp';
        // $config['smtp_host']    = 'ssl://smtp.gmail.com';
        // $config['smtp_port']    = '465';
        // $config['smtp_timeout'] = '300';
        // $config['smtp_user']    = 'suffy.yanuar@gmail.com';
        // $config['smtp_pass']    = 'ririn123!@#';
        // $config['charset']      = 'utf-8';
        // $config['newline']      = "\r\n";
        // $config['mailtype']     ="html";
        // $config['use_ci_email'] = TRUE;
        // $config['wordwrap']     = TRUE;

        $this->email->initialize($config);
        $this->email->from($from,'PT. Mulia Putra Mandiri');
        $this->email->to($to);
        $this->email->cc($cc);
        $this->email->subject($subject);
        $this->email->message($message);
        // $this->email->attach('assets/file/request/'.$id.'.csv');
        $send = $this->email->send();
        // echo $this->email->print_debugger();
        if ($send) {
            echo "<script>alert('pengiriman email berhasil'); </script>";
            redirect('master_data/data_customer','refresh');
        }else{
            echo "<script>alert('pengiriman email gagal'); </script>";
            redirect('master_data/data_customer','refresh');
        }

    }

    public function dashboard_sales()
    {
        $this->load->model('model_outlet_transaksi');
        $created_date = $this->model_outlet_transaksi->timezone();
        // echo "created_date : ".$created_date;
        // echo date_default_timezone_get();
        // die;
        $this->model_master_data->dashboard_sales($created_date);
    }

    public function dashboard_stock()
    {
        $data = [
            'created_date' => $this->model_sales_omzet->timezone2()
        ];
        $this->model_master_data->dashboard_stock($data);
    }

    public function semut_gajah_site(){
        $created_date = $this->model_sales_omzet->timezone();
        $result=$this->model_master_data->semut_gajah_site($created_date);
        // echo $result;
        if ($result) {
            echo "t_site updated";
        }else{
            echo "t_site update failed";
        }
    }
    
    public function db_afiliasi()
    {
        # code...
        $data = [
            'created_date' => $this->model_sales_omzet->timezone2()
        ];
        $this->model_master_data->insert_t_temp_omzet_profile($data);
    }

    public function rpd()
    {
        $this->load->model('model_outlet_transaksi');

        $signature = $this->uri->segment('3');
        $userid = $this->uri->segment('4');
        $pilihan = $this->uri->segment('5');

        
        $this->db->select('*');
        $this->db->where('signature', $signature);
        $rpd = $this->db->get('site.t_rpd',1)->row();

        $status_approval = $rpd->status_approval;

        // echo "status_approval : ".$status_approval;
        // die;

        if ($status_approval == '0') {
            if ($pilihan == md5('1')) {
                $data = [
                    "status_approval" => "1",
                    "nama_status_approval" => "approved",
                    "alasan_atasan" => 'ok',
                    "approved_at" => $this->model_outlet_transaksi->timezone(),
                    "approved_by" => $userid,
                ];
                $this->db->where('signature', $signature);
                $proses = $this->db->update('site.t_rpd',$data);
                if ($proses) {
                    $this->email_konfirmasi_rpd($signature);
                }
                $this->load->view('template_claim/top_header');
                $this->load->view('template_claim/header');
                $this->load->view('rpd/accept', $data);
                $this->load->view('template_claim/bottom_content');
                $this->load->view('template_claim/footer');
            } else {
    
                $data = [
                    "signature" => $signature,
                ];
    
                $this->load->view('template_claim/top_header');
                $this->load->view('template_claim/header');
                $this->load->view('rpd/form_reject', $data);
                $this->load->view('template_claim/bottom_content');
                $this->load->view('template_claim/footer');
            }
        } elseif($status_approval == '9') {
            echo "anda sudah reject";
        }else{
            echo "anda sudah approve";
        }
        
    }

    public function rpd_reject()
    {
        $this->load->model('model_outlet_transaksi');
        $signature = $this->input->post('signature');
        // var_dump($signature);die;
        $data = [
            "status_approval" => "9",
            "nama_status_approval" => "rejected",
            "alasan_atasan" => $this->input->post('alasan_atasan'),
            "approved_at" => $this->model_outlet_transaksi->timezone(),
            "approved_by" => $this->input->post('userid'),
        ];

        $this->db->where('signature', $signature);
        $proses = $this->db->update('site.t_rpd',$data);
        
        if ($proses) {
            $this->email_konfirmasi_rpd($signature);
        }
        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('rpd/reject', $data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
    }

    // function email(){
    //     $this->load->library('email');

    //     $config['protocol']     = 'smtp';
    //     $config['smtp_host']    = 'ssl://mail.muliaputramandiri.com';
    //     $config['smtp_port']    = '465';
    //     $config['smtp_timeout'] = '300';
    //     // $config['smtp_user']    = 'support@muliaputramandiri.com';
    //     // $config['smtp_pass']    = 'support123!@#';
    //     $config['smtp_user']    = 'suffy@muliaputramandiri.net';
    //     // $config['smtp_pass']    = 'support123!@#';
    //     $config['smtp_pass']    = 'vruzinbjlnsgzagy';
    //     $config['charset']      = 'utf-8';
    //     $config['newline']      = "\r\n";
    //     $config['mailtype']     ="html";
    //     $config['use_ci_email'] = TRUE;
    //     $config['wordwrap']     = TRUE;

    //     $this->email->initialize($config);
    // }

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

    public function email_konfirmasi_rpd($signature)
	{
        // var_dump($signature);die;
        $this->db->select('*');
        $this->db->where('signature', $signature);
        $data_rpd = $this->db->get('site.t_rpd')->row();
        $userid = $data_rpd->created_by;

        $data_karyawan = $this->model_rpd->getMaster_karyawan('',$userid)->row();
        $email_karyawan = $data_karyawan->email_karyawan;
        $email_atasan = $data_karyawan->email_atasan;

        $this->db->select_sum('nominal_biaya');
        $this->db->where('signature',$signature);
        $this->db->where('deleted','0');
        $biaya = $this->db->get('site.t_rpd_aktivitas')->row();

        // var_dump($biaya);die;

		$detail = [
            'atasan_id' => $data_karyawan->atasan_id,
            'nama_atasan' => $data_karyawan->nama_atasan,
			'kode' => $data_rpd->kode,
			'signature' => $signature,
			'tanggal_berangkat' => $data_rpd->tanggal_berangkat,
            'nama_karyawan' => $data_karyawan->nama_karyawan,
			'maksud_perjalanan_dinas' => $data_rpd->maksud_perjalanan_dinas,
			'tempat_tujuan' => $data_rpd->tempat_tujuan,
			'status_approval' => $data_rpd->status_approval,
			'alasan_atasan' => $data_rpd->alasan_atasan,
			'total_biaya' => $biaya->nominal_biaya,
		];

        $url = base_url().'rpd/aktivitas/'.$signature;

        // var_dump($url);die;

        // $from = "ilhammsyah@gmail.com";
        // $to = "ilhammsyah@gmail.com";

        $from = "$email_atasan";
        $to = "$email_karyawan";
        $cc = "ratri@muliaputramandiri.com, suffy@muliaputramandiri.com, hwiryanto@muliaputramandiri.com, yayang@muliaputramandiri.com, nanita@muliaputramandiri.com";
        $subject = "MPM Site | RPD - Konfirmasi";

        $message = $this->load->view("rpd/email_konfirmasi",$detail,TRUE);

        $this->email();
        $this->email->from($from,'PT. Mulia Putra Mandiri');
        $this->email->to($to);
        $this->email->cc($cc);
        $this->email->subject($subject);
        $this->email->message($message);
        // $this->email->attach('assets/file/request/'.$id.'.csv');
        $send = $this->email->send();
        // echo $this->email->print_debugger();
	}


    public function import_sales()
    {
        $created_at = $this->model_outlet_transaksi->timezone();
        $this->load->library('excel');

        // get tanggal hari ini
        $format_filename = date('ymd') . ".csv";

        // echo $format_filename;
        // die;

        

        $format_filename = "220915.csv";

        // echo dirname('c:\\');
        // $path    = 'C:\Users\suffy mpm\Desktop';
        // $files = scandir($path);

        // print_r($files);

        // $file = "C:/xampp/htdocs/cisk/assets/uploads/zip/" . date('Ym') . "/" . $filename;
        // $file = "C:/Users/suffy mpm/Desktop/" . $format_filename . ".csv";
        // echo $file;

        // $files = array_diff(scandir($path), array('.', '..'));
        // foreach($files as $file){
        // echo "<a href='$file'>$file</a>";
        // }

        // $object = PHPExcel_IOFactory::load($file);
        $object = PHPExcel_IOFactory::load("assets/supralita/sales/$format_filename");

        $jumlahSheet = $object->getSheetCount();

        foreach ($object->getWorksheetIterator() as $worksheet) {
            $highestRow = $worksheet->getHighestRow();
            $highestColumn = $worksheet->getHighestColumn();
            for ($row = 2; $row <= $highestRow; $row++) {
                $kd_cabang = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
                $cabang = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
                $principal_desc = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
                $cust_id = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
                $cust_name = $worksheet->getCellByColumnAndRow(4, $row)->getValue();
                $segment_id = $worksheet->getCellByColumnAndRow(5, $row)->getValue();
                $segment_desc = $worksheet->getCellByColumnAndRow(6, $row)->getValue();
                $tipe_id = $worksheet->getCellByColumnAndRow(7, $row)->getValue();
                $tipe_desc = $worksheet->getCellByColumnAndRow(8, $row)->getValue();
                $salesman_id = $worksheet->getCellByColumnAndRow(9, $row)->getValue();
                $salesman_desc = $worksheet->getCellByColumnAndRow(10, $row)->getValue();
                $bukti = $worksheet->getCellByColumnAndRow(11, $row)->getValue();
                $tgl = $worksheet->getCellByColumnAndRow(12, $row)->getValue();
                $produk_id = $worksheet->getCellByColumnAndRow(13, $row)->getValue();
                $produk_desc = $worksheet->getCellByColumnAndRow(14, $row)->getValue();
                $group = $worksheet->getCellByColumnAndRow(15, $row)->getValue();
                $isi_sedang = $worksheet->getCellByColumnAndRow(16, $row)->getValue();
                $isi_besar = $worksheet->getCellByColumnAndRow(17, $row)->getValue();
                $harga = $worksheet->getCellByColumnAndRow(18, $row)->getValue();
                $qty = $worksheet->getCellByColumnAndRow(19, $row)->getValue();
                $bruto = $worksheet->getCellByColumnAndRow(20, $row)->getValue();
                $disc_rutin = $worksheet->getCellByColumnAndRow(21, $row)->getValue();
                $disc_principal = $worksheet->getCellByColumnAndRow(22, $row)->getValue();
                $disc_1 = $worksheet->getCellByColumnAndRow(23, $row)->getValue();
                $disc_extra = $worksheet->getCellByColumnAndRow(24, $row)->getValue();
                $disc_2 = $worksheet->getCellByColumnAndRow(25, $row)->getValue();
                $disc_cod = $worksheet->getCellByColumnAndRow(26, $row)->getValue();
                $netto = $worksheet->getCellByColumnAndRow(27, $row)->getValue();

                $data = [
                    'kd_cabang'            =>    $kd_cabang,
                    'cabang'               =>    $cabang,
                    'principal_desc'    =>    $principal_desc,
                    'cust_id'            =>    $cust_id,
                    'cust_name'            =>    $cust_name,
                    'segment_id'        =>    $segment_id,
                    'segment_desc'        =>    $segment_desc,
                    'tipe_id'            =>    $tipe_id,
                    'tipe_desc'            =>    $tipe_desc,
                    'salesman_id'        =>    $salesman_id,
                    'salesman_desc'        =>    $salesman_desc,
                    'bukti'                =>    $bukti,
                    'tgl'                =>    $tgl,
                    'produk_id'            =>    $produk_id,
                    'produk_desc'        =>    $produk_desc,
                    'group'                =>    $group,
                    'isi_sedang'        =>    $isi_sedang,
                    'isi_besar'            =>    $isi_besar,
                    'harga'                =>    $harga,
                    'qty'                =>    $qty,
                    'bruto'                =>    $bruto,
                    'disc_rutin'        =>    $disc_rutin,
                    'disc_principal'    =>    $disc_principal,
                    'disc_1'            =>    $disc_1,
                    'disc_extra'        =>    $disc_extra,
                    'disc_2'            =>    $disc_2,
                    'disc_cod'            =>    $disc_cod,
                    'netto'                =>    $netto,
                    'filename'          =>    $format_filename,
                    'created_at'        =>    $created_at,
                ];

                $this->db->insert('site.temp_raw_sales_rtd', $data);
            }
        }

        $update_kodeprod = "
            update site.temp_raw_sales_rtd a 
            set a.produk_id = concat('0', a.produk_id)
            where length(a.produk_id) = 5 and a.created_at = '$created_at'
        ";
        $proses_update_kodeprod = $this->db->query($update_kodeprod);

        // die;

        $this->import_sales_to_transaction($created_at);
    }

    public function import_sales_to_transaction($created_at)
    {
        $sql = "
        insert into site.t_raw_sales_rtd
        select 	'', a.id, a.bukti as nodokjdi, STR_TO_DATE(a.tgl,'%m-%d-%Y') as tgldokjdi, a.cust_id as kode_lang, a.cust_name as nama_lang,
				'001' as supp, b.kode_comp, b.nocab, '' as kode_type, '' as kodesalur, '' as namasalur, 
				trim(a.salesman_id) as kodesales, a.salesman_desc as namasales, e.kodeprod, e.namaprod, a.isi_besar as qty1, a.isi_sedang as qty2, '' as qty3, a.harga, a.qty as banyak, a.bruto as tot1, day(STR_TO_DATE(a.tgl,'%m-%d-%Y')) as hrdok,month(STR_TO_DATE(a.tgl,'%m-%d-%Y')) as blndok, year(STR_TO_DATE(a.tgl,'%m-%d-%Y')) as thndok, concat(b.kode_comp, b.nocab) as siteid,  '$created_at'
        from site.temp_raw_sales_rtd a left join 
        (
            select a.kd_cabang, a.kode_comp, a.nocab
            from site.map_rtd_site_code a    
            where a.status_aktif = 1
        )b on a.kd_cabang = b.kd_cabang left join (

            select a.tipe_id, a.kode_type
            from site.map_rtd_type a
            where a.status_aktif = 1
        )c on a.tipe_id = c.tipe_id /*left join (

            select a.produk_id, a.kodeprod
            from site.map_rtd_product a
            where a.status_aktif = 1
        )d on a.produk_id = d.produk_id */left join 
        (
            select a.kodeprod, a.namaprod
            from mpm.tabprod a 
        )e on a.produk_id = e.kodeprod
        where a.created_at = '$created_at'
        ";
        $proses = $this->db->query($sql);

        $update_hrdok = "
            update site.t_raw_sales_rtd a 
            set a.hrdok = concat('0',a.hrdok)
            where length(a.hrdok) = 1 and a.created_at = '$created_at'
        ";
        $proses_update_hrdok = $this->db->query($update_hrdok);

        $update_blndok = "
            update site.t_raw_sales_rtd a 
            set a.blndok = concat('0',a.blndok)
            where length(a.blndok) = 1 and a.created_at = '$created_at'
        ";
        $proses_update_blndok = $this->db->query($update_blndok);

        // die;

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
            $proses_delete_nocab = $this->db->query($delete_nocab);

            // hapus outlet di data2022.tblang
            $delete_outlet = "
                delete from data$thndok.tblang
                where nocab = '$nocab'
            ";
            $proses_delete_outlet = $this->db->query($delete_outlet);

            $delete_transaksi = "
                delete from data$thndok.fi 
                where bulan = $bulan and nocab = '$nocab'
            ";
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
            $nama_lang = $key->nama_lang;
            $nocab = $key->nocab;
            $thndok = $key->thndok;

            $insert_outlet = "insert into data$thndok.tblang (kode_comp, kode_lang, nama_lang, nocab) values ('$kode_comp', '$kode_lang', '$nama_lang', '$nocab')";
            $proses_insert_outlet = $this->db->query($insert_outlet);
        }

        $this->update_transaksi($created_at);
    }

    public function update_transaksi($created_at)
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
            $nama_lang = $key->nama_lang;
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
            $potongan = '';

            $insert_fi = "insert into data2022.fi (nodokjdi, tgldokjdi, kodesales, kode_comp, kode_kota, kode_type, kode_lang, nama_lang, kodeprod, namaprod, qty1, qty2, qty3, harga, hrdok, blndok, thndok, banyak, potongan, tot1, kodesalur, nocab, bulan, siteid, supp) values ('$nodokjdi', '$tgldokjdi', '$kodesales', '$kode_comp', '$kode_kota', '$kode_type', '$kode_lang', '$nama_lang', '$kodeprod', '$namaprod', '$qty1', '$qty2', '$qty3', '$harga', '$hrdok', '$blndok', '$thndok', '$banyak', '$potongan', '$tot1', '$kodesalur', '$nocab', '$blndok', '$siteid', '$supp')";

            $proses_insert = $this->db->query($insert_fi);
        }
    }

    public function portal_raw_data_prepare(){

        $tahun = 2022;
        $bulan = 8;
        $limit = 10000;
        $created_at = $this->model_outlet_transaksi->timezone();
        echo $created_at;
        // die;
        
        $truncate_raw = $this->db->query("truncate db_raw_cloning.tbl_raw");
        $truncate_salesman = $this->db->query("truncate site.temp_tabsales_$tahun");
        $truncate_tblang = $this->db->query("truncate site.temp_tblang_$tahun");

        echo "truncate_raw : ".$truncate_raw;
        echo "truncate_salesman : ".$truncate_salesman;
        echo "truncate_tblang : ".$truncate_tblang;

        $insert_temp_salesman = "
            insert into site.temp_tabsales_$tahun
            select concat(a.KODESALES, a.nocab), a.KODESALES, a.NAMASALES, '$created_at'
            from data$tahun.tabsales a
            GROUP BY concat(a.KODESALES, a.nocab)
        ";
        $proses_insert_salesman = $this->db->query($insert_temp_salesman);
        echo "proses_insert_salesman : ".$proses_insert_salesman;

        // die;

        $insert_temp_tblang = "
            insert into site.temp_tblang_$tahun
            select *, '$created_at'
            from data$tahun.tblang a
            GROUP BY concat(a.kode_comp,a.kode_lang)
        ";
        $proses_insert_tblang = $this->db->query($insert_temp_tblang);
        echo "proses_insert_tblang : ".$proses_insert_tblang;

        // die;

    }

    public function portal_raw_data(){

        $tahun = 2022;
        $bulan = 9;
        $limit = 10000;
        $created_at = $this->model_outlet_transaksi->timezone();
        
        $get_status_traffic = $this->db->get_where('site.t_traffic', array(
                'menuid' => 777,
                'created_finish is null'   => null
            ))->num_rows();
        if ($get_status_traffic) {
            echo "ada yang sedang menjalankan";
            die;
        }else{
            $data = [
                'menuid'            => 777,
                'status_running'    => 1,
                'created_at'        => $created_at,
                'created_by'        => 297
            ];
            $proses_insert_traffic = $this->db->insert('site.t_traffic', $data);
            $insert_id = $this->db->insert_id();
        }

        $truncate_raw = $this->db->query("truncate db_raw_cloning.tbl_raw");

        $insert_raw = "
            insert into db_raw_cloning.tbl_raw
            select 	b.branch_name, a.kode_comp, b.nama_comp, a.nocab,
                    faktur, a.tipe_dokumen, kodeprod, namaprod, '' as `group`,'' as nama_group,'' as sub_group,'' as nama_sub_group,tanggal,
                    kode_lang, nama_lang, alamat, 
                    a.kode_kota, c.nama_kota,
                    a.KODE_TYPE, d.NAMA_TYPE,d.sektor as sektor, d.segment as segment,
                    '' as sektor_perkiraan, '' as sektor_delto,
                    a.kodesales, '' as namasales,
                    a.KODESALUR, f.jenis,
                    banyak as unit, harga,'' as hna, potongan as diskon, tot1 as bruto, 
                    tot1-potongan as netto2, $tahun, $bulan,'' as supp,'' as nama_supplier,
                    '' as kode_type_new, '' as nama_type_new,'' as sektor_new, '' as segment_new,
                    '' as kodesalur_current, '' as namasalur_current, 
                    '' as kode_type_current, '' as nama_type_current, 
                    '' as sektor_current,'' as segment_current,
                    '' as ec, no_urut, status_retur, '' as id_provinsi, '' as nama_provinsi, '' as id_kota,
                    '' as nama_kota_kabupaten, '' as id_kecamatan, '' as nama_kecamatan, '' as id_kelurahan, '' as nama_kelurahan,
                    '' as credit_limit, '' as tipe_bayar, '' as phone,UNITBONUS, term_payment, tipe_kl, '' as status_toko, '' as last_updated, '' as status_blacklist, keterangan,
                    '$created_at', 1 as flag
            FROM
            (
                select 	kode_comp, NOCAB,
                        concat(KDDOKJDI, NODOKJDI) as faktur, kodeprod, namaprod,
                        concat(thndok,'-',blndok,'-',hrdok) as tanggal,
                        kode_lang, '' as nama_lang, '' as alamat, KODE_KOTA,
                        KODESALES,
                        KODE_TYPE,
                        KODESALUR,
                        BANYAK, harga, POTONGAN, TOT1, 'faktur' as tipe_dokumen, no_urut, status_retur,UNITBONUS, term_payment, tipe_kl, keterangan
                from 	data$tahun.fi
                where 	blndok = $bulan  and kodeprod in (select kodeprod from mpm.tabprod where supp = 001)            
                union ALL
                select 	kode_comp, NOCAB,
                        concat(KDDOKJDI, NODOKJDI) as faktur, kodeprod, namaprod,
                        concat(thndok,'-',blndok,'-',hrdok) as tanggal,
                        kode_lang, '' as nama_lang, '' as alamat, KODE_KOTA,
                        KODESALES,
                        KODE_TYPE,
                        KODESALUR,
                        BANYAK, harga, POTONGAN, TOT1, 'retur' as tipe_dokumen, no_urut, status_retur,UNITBONUS, term_payment, tipe_kl, keterangan
                from 	data$tahun.ri
                where 	blndok = $bulan  and kodeprod in (select kodeprod from mpm.tabprod where supp = 001)
            )a LEFT JOIN
            (
                SELECT 		concat(kode_comp,nocab) as kode, branch_name,kode_comp, nama_comp, urutan
                FROM		mpm.tbl_tabcomp
                where		`status` = 1
                GROUP BY	kode
            )b on concat(a.kode_comp,a.nocab) = b.kode
            LEFT JOIN 
            (
                select	kode_comp, kode_kota, nama_kota, nocab
                from 		data$tahun.tbkota
                GROUP BY	concat(kode_comp, kode_kota)
            )c on concat(a.kode_comp, a.kode_kota) = concat(c.kode_comp, c.kode_kota)
            LEFT JOIN
            (
                select a.kode_type,a.nama_type,a.sektor,a.segment
                from mpm.tbl_bantu_type a
                GROUP BY KODE_TYPE 
            )d on a.KODE_TYPE = d.KODE_TYPE
            LEFT JOIN
            (
                select 	kode, jenis
                from 	mpm.tbl_tabsalur
            )f on f.kode = a.KODESALUR
        ";

        $proses_insert_raw = $this->db->query($insert_raw);
        
        $this->db->query("update db_raw_cloning.tbl_raw a set a.flag = 0");

        // die;

        $sql_count = "select ceiling(count(*)/$limit) as n from db_raw_cloning.tbl_raw";
        $proses_cek_count = $this->db->query($sql_count)->row();
        $n = $proses_cek_count->n;
        // echo "n : ".$n;
        // die;

        for ($i=1; $i <= $n; $i++) { 
            echo "looping salesman=>a ke : ".$i."<br>";
            $sql_update_salesman_a = "
                update db_raw_cloning.tbl_raw a
                set a.nama_sales = 'a', a.flag = 1
                where a.flag = 0
                limit $limit
            ";
            $proses_salesman_a = $this->db->query($sql_update_salesman_a);
            // echo "proses_salesman_a : ".$proses_salesman_a;
            echo "<pre>";
            print_r($sql_update_salesman_a);
            echo "</pre>";
            echo "<hr>";

        }
        
        $this->db->query("update db_raw_cloning.tbl_raw a set a.flag = 0");

        for ($i=1; $i <= $n; $i++) { 
            echo "looping nama salesman ke : ".$i."<br>";
            $sql_update_salesman = "
                update db_raw_cloning.tbl_raw a
                set a.nama_sales = (
                    select b.NAMASALES
                    from site.temp_tabsales_$tahun b
                    where concat(a.KODE_SALES, a.nocab) = b.kode
                ), a.flag = 1
                where a.nama_sales = 'a' and a.flag = 0
                limit $limit
            ";
            $proses_salesman = $this->db->query($sql_update_salesman);
            echo "<pre>";
            print_r($sql_update_salesman);
            echo "</pre>";
            // echo "proses_salesman : ".$proses_salesman;
            echo "<hr>";
        }

        $this->db->query("update db_raw_cloning.tbl_raw a set a.flag = 0");

        for ($i=1; $i <= $n; $i++) { 
            echo "looping namaprod ke : ".$i."<br>";
            $sql_update_namaprod = "
                update db_raw_cloning.tbl_raw a
                set namaprod = (
                    select 	namaprod 
                    FROM 	mpm.tabprod b
                    where a.kodeprod = b.KODEPROD
                    ORDER BY KODEPROD
                ),`group`= (
                    select 	grup
                    FROM    mpm.tabprod b
                    where 	a.kodeprod = b.KODEPROD
                    ORDER BY KODEPROD
                ), `sub_group`= (
                    select 	subgroup
                    FROM 	mpm.tabprod b
                    where 	a.kodeprod = b.KODEPROD
                    ORDER BY KODEPROD
                ), a.flag = 1
                where a.flag = 0
                limit $limit
            ";
            $proses_namaprod = $this->db->query($sql_update_namaprod);
            echo "<pre>";
            print_r($sql_update_namaprod);
            echo "</pre>";
            // echo "proses_salesman : ".$proses_salesman;
            echo "<hr>";
        }

        $this->db->query("update db_raw_cloning.tbl_raw a set a.flag = 0");

        for ($i=1; $i <= $n; $i++) { 
            echo "looping nama sub group ke : ".$i."<br>";
            $sql_update_group = "
                update db_raw_cloning.tbl_raw a
                set nama_group = (
                    select 	b.nama_group 
                    FROM 	mpm.tbl_group b
                    where 	a.`group` = b.kode_group
                ),a.nama_sub_group = (
                    select b.nama_sub_group
                    from db_produk.t_sub_group b
                    where a.sub_group = b.sub_group
                ), a.flag = 1
                where a.flag = 0
                limit $limit
            ";
            $proses_group = $this->db->query($sql_update_group);
            // echo "proses_group : ".$proses_group;
            echo "<pre>";
            print_r($sql_update_group);
            echo "</pre>";
            // echo "proses_salesman : ".$proses_salesman;
            echo "<hr>";
        }

        $this->db->query("update db_raw_cloning.tbl_raw a set a.flag = 2");
        
        // die;
        
        for ($i=1; $i <= $n; $i++) { 
            echo "looping nama lang ke : ".$i."<br>";
            
            $this->db->query("update db_raw_cloning.tbl_raw a set a.flag = 0 where a.flag = 2 limit $limit");

            $sql_update_outlet = "
                update db_raw_cloning.tbl_raw a left join 
                (
                    select concat(a.kode_comp, a.kode_lang) as outlet, b.*
                    from db_raw_cloning.tbl_raw a LEFT JOIN (
                        select 	concat(a.kode_comp,a.kode_lang) as kode, a.nama_lang, a.alamat1, a.kodesalur, a.kode_type,
                                a.id_provinsi, a.nama_provinsi, a.id_kota, a.nama_kota, a.id_kecamatan, a.nama_kecamatan,
                                a.id_kelurahan, a.nama_kelurahan, a.kodepos, a.last_updated, a.status_blacklist, a.credit_limit,
                                a.tipe_bayar, a.phone, a.aktif
                        from site.temp_tblang_$tahun a 
                    )b on concat(a.kode_comp,a.kode_lang) = b.kode
                    where a.flag = 0
                    /*limit $limit*/
                )b on concat(a.kode_comp, a.kode_lang) = b.outlet
                set a.nama_lang = b.nama_lang,
                    a.alamat = b.alamat1,
                    a.kodesalur_current = b.kodesalur,
                    a.kode_type_current = b.kode_type,
                    a.id_provinsi = b.id_provinsi,
                    a.nama_provinsi = b.nama_provinsi,
                    a.id_kota = b.id_kota,
                    a.nama_kota_kabupaten = b.nama_kota,
                    a.id_kecamatan = b.id_kecamatan,
                    a.nama_kecamatan = b.nama_kecamatan,
                    a.id_kelurahan = b.id_kelurahan,
                    a.nama_kelurahan = b.nama_kelurahan,
                    a.credit_limit = b.credit_limit,
                    a.status_toko = b.aktif,
                    a.tipe_bayar = b.tipe_bayar,
                    a.phone = b.phone,
                    a.last_updated = b.last_updated,
                    a.status_blacklist = b.status_blacklist,
                    a.flag = 1
                where a.flag = 0
            ";
            $proses_outlet = $this->db->query($sql_update_outlet);
            // echo "proses_outlet : ".$proses_outlet;
            echo "<pre>";
            print_r($sql_update_outlet);
            echo "</pre>";
            // echo "proses_salesman : ".$proses_salesman;
            echo "<hr>";
        }

        $this->db->query("update db_raw_cloning.tbl_raw a set a.flag = 0");

        // die;

        for ($i=1; $i <= $n; $i++) { 
            echo "looping nama concat(kode_comp,kode_lang) ke : ".$i."<br>";
            $sql_update_concat = "
                update db_raw_cloning.tbl_raw a
                set kode_lang = concat(a.kode_comp, a.kode_lang), a.flag = 1
                where a.flag = 0
                limit $limit
            ";
            // $proses_concat = $this->db->query($sql_update_concat);
            // echo "proses_concat : ".$proses_concat;
            echo "<pre>";
            print_r($sql_update_concat);
            echo "</pre>";
            // echo "proses_salesman : ".$proses_salesman;
            echo "<hr>";
        }

        $this->db->query("update db_raw_cloning.tbl_raw a set a.flag = 0");

        for ($i=1; $i <= $n; $i++) { 
            echo "looping hna ke : ".$i."<br>";
            $sql_update_hna = "
                update db_raw_cloning.tbl_raw a 
                set a.hna = (
                    select b.h_dp
                    from mpm.prod_detail b
                    where b.tgl = (
                        select max(tgl)
                        from mpm.prod_detail
                        where b.kodeprod = kodeprod
                    ) and a.kodeprod = b.kodeprod
                    GROUP BY tgl
                ), a.flag = 1
                where a.flag = 0
                limit $limit
            ";
            $proses_hna = $this->db->query($sql_update_hna);
            // echo "proses_hna : ".$proses_hna;
            echo "<pre>";
            print_r($sql_update_hna);
            echo "</pre>";
            // echo "proses_hna : ".$proses_hna;
            echo "<hr>";
        }

        $this->db->query("update db_raw_cloning.tbl_raw a set a.flag = 0");

        // die;

        for ($i=1; $i <= $n; $i++) { 
            echo "looping supplier ke : ".$i."<br>";
            $sql_update_supplier = "
                update db_raw_cloning.tbl_raw a
                set a.supplier = (
                    select b.supp
                    from mpm.tabprod b
                    where a.kodeprod = b.kodeprod
                ), a.flag = 1
                where a.flag = 0
                limit $limit
            ";
            $proses_supplier = $this->db->query($sql_update_supplier);
            // echo "proses_supplier : ".$proses_supplier;
            echo "<pre>";
            print_r($sql_update_supplier);
            echo "</pre>";
            // echo "proses_hna : ".$proses_hna;
            echo "<hr>";
        }

        $this->db->query("update db_raw_cloning.tbl_raw a set a.flag = 0");

        for ($i=1; $i <= $n; $i++) { 
            echo "looping nama supplier ke : ".$i."<br>";
            $sql_update_nama_supplier = "
                update db_raw_cloning.tbl_raw a
                set a.nama_supplier = (
                    select b.NAMASUPP
                    from mpm.tabsupp b
                    where b.supp = a.supplier
                ), a.flag = 1   
                where a.flag = 0         
                limit $limit
            ";
            $proses_nama_supplier = $this->db->query($sql_update_nama_supplier);
            // echo "proses_nama_supplier : ".$proses_nama_supplier;
            echo "<pre>";
            print_r($sql_update_nama_supplier);
            echo "</pre>";
            // echo "proses_hna : ".$proses_hna;
            echo "<hr>";
        }

        $this->db->query("update db_raw_cloning.tbl_raw a set a.flag = 0");

        for ($i=1; $i <= $n; $i++) { 
            echo "looping kode_type_new ke : ".$i."<br>";
            $sql_update_kode_type_new = "
                update db_raw_cloning.tbl_raw a
                set a.kode_type_new = a.kode_type, a.flag = 1   
                where a.flag = 0         
                limit $limit
            ";
            $proses_kode_type_new = $this->db->query($sql_update_kode_type_new);
            // echo "proses_kode_type_new : ".$proses_kode_type_new;
            echo "<pre>";
            print_r($sql_update_kode_type_new);
            echo "</pre>";
            // echo "proses_hna : ".$proses_hna;
            echo "<hr>";
        }

        $this->db->query("update db_raw_cloning.tbl_raw a set a.flag = 0");

        for ($i=1; $i <= $n; $i++) { 
            echo "looping kode_type_new ke : ".$i."<br>";
            $sql_update_kode_type_new = "
                update db_raw_cloning.tbl_raw a
                set a.kode_type_new = (
                    select b.kode_type_menjadi
                    from site.map_type b
                    where a.kode_type = b.kode_type_awal
                ), a.flag = 1
                where length(a.kode_type) = 2 and a.flag = 0         
                limit $limit
            ";
            $proses_kode_type_new = $this->db->query($sql_update_kode_type_new);
            // echo "proses_kode_type_new : ".$proses_kode_type_new;
            echo "<pre>";
            print_r($sql_update_kode_type_new);
            echo "</pre>";
            // echo "proses_hna : ".$proses_hna;
            echo "<hr>";
        }

        $this->db->query("update db_raw_cloning.tbl_raw a set a.flag = 0");

        for ($i=1; $i <= $n; $i++) { 
            echo "looping sektor_perkiraan ke : ".$i."<br>";
            $sql_sektor_perkiraan = "
                update db_raw_cloning.tbl_raw a
                set a.sektor_perkiraan = a.sektor, a.flag = 1
                where a.flag = 0     
                limit $limit
            ";
            $proses_sektor_perkiraan = $this->db->query($sql_sektor_perkiraan);
            // echo "proses_sektor_perkiraan : ".$proses_sektor_perkiraan;
            echo "<pre>";
            print_r($sql_sektor_perkiraan);
            echo "</pre>";
            // echo "proses_hna : ".$proses_hna;
            echo "<hr>";
        }

        $this->db->query("update db_raw_cloning.tbl_raw a set a.flag = 0");

        for ($i=1; $i <= $n; $i++) { 
            echo "looping sektor_perkiraan_apotik ke : ".$i."<br>";
            $sql_sektor_perkiraan_apotik = "
                update db_raw_cloning.tbl_raw a
                set a.sektor_perkiraan = 'APOTIK', a.flag = 1
                where (nama_lang like '%apotik%' or nama_lang like '%apot%' or
                nama_lang like '%,ap%') and sektor ='other' and a.flag = 0     
                limit $limit
            ";
            $proses_sql_sektor_perkiraan_apotik = $this->db->query($sql_sektor_perkiraan_apotik);
            // echo "proses_sql_sektor_perkiraan_apotik : ".$proses_sql_sektor_perkiraan_apotik;
            echo "<pre>";
            print_r($sql_sektor_perkiraan_apotik);
            echo "</pre>";
            // echo "proses_hna : ".$proses_hna;
            echo "<hr>";
        }

        $this->db->query("update db_raw_cloning.tbl_raw a set a.flag = 0");

        for ($i=1; $i <= $n; $i++) { 
            echo "looping sektor_perkiraan_mti ke : ".$i."<br>";
            $sql_sektor_perkiraan_mti = "
                update db_raw_cloning.tbl_raw a
                set a.sektor_perkiraan = 'MTI', a.flag = 1
                where  (nama_lang like '%minimarket%' or nama_lang like '%mini%market%' or nama_lang like '%supermarket%'
                or nama_lang like '%swalayan%' or nama_lang like '%marts%' or nama_lang like '%hyper%') and sektor ='other' and a.flag = 0
                limit $limit
            ";
            $proses_sql_sektor_perkiraan_mti = $this->db->query($sql_sektor_perkiraan_mti);
            // echo "proses_sql_sektor_perkiraan_mti : ".$proses_sql_sektor_perkiraan_mti;
            echo "<pre>";
            print_r($sql_sektor_perkiraan_mti);
            echo "</pre>";
            // echo "proses_hna : ".$proses_hna;
            echo "<hr>";
        }

        $this->db->query("update db_raw_cloning.tbl_raw a set a.flag = 0");

        for ($i=1; $i <= $n; $i++) { 
            echo "looping sektor_perkiraan ke : ".$i."<br>";
            $sql_sektor_delto = "
                UPDATE db_raw_cloning.tbl_raw a
                set a.sektor_delto = (
                    SELECT b.sektor FROM site.map_sales_outlet_deltomed b
                    where a.kode_lang = b.kode_lang
                ), a.flag = 1
                where a.flag = 0
                limit $limit
            ";
            $proses_sql_sektor_delto = $this->db->query($sql_sektor_delto);
            // echo "proses_sql_sektor_delto : ".$proses_sql_sektor_delto;
            echo "<pre>";
            print_r($sql_sektor_delto);
            echo "</pre>";
            // echo "proses_hna : ".$proses_hna;
            echo "<hr>";
        }

        $this->db->query("update db_raw_cloning.tbl_raw a set a.flag = 0");

        for ($i=1; $i <= $n; $i++) { 
            echo "looping nama_type_new ke : ".$i."<br>";
            $sql_nama_type_new_sektor = "
                update db_raw_cloning.tbl_raw a
                set a.nama_type_new = (
                    select b.nama_type
                    from mpm.tbl_bantu_type b
                    where a.kode_type_new = b.kode_type
                ),
                a.sektor_new = (
                    select b.sektor
                    from mpm.tbl_bantu_type b
                    where a.kode_type_new = b.kode_type
                ),
                a.segment_new = (
                    select b.segment
                    from mpm.tbl_bantu_type b
                    where a.kode_type_new = b.kode_type
                ),
                a.nama_type_current = (
                    select b.nama_type
                    from mpm.tbl_bantu_type b
                    where a.kode_type_current = b.kode_type
                ),
                a.sektor_current = (
                    select b.sektor
                    from mpm.tbl_bantu_type b
                    where a.kode_type_current = b.kode_type
                ),
                a.segment_current = (
                    select b.segment
                    from mpm.tbl_bantu_type b
                    where a.kode_type_current = b.kode_type
                ), a.flag = 1
                where a.flag = 0
                limit $limit
            ";
            $proses_sql_nama_type_new_sektor = $this->db->query($sql_nama_type_new_sektor);
            // echo "proses_sql_nama_type_new_sektor : ".$proses_sql_nama_type_new_sektor;
            echo "<pre>";
            print_r($sql_nama_type_new_sektor);
            echo "</pre>";
            // echo "proses_hna : ".$proses_hna;
            echo "<hr>";
        }

        $this->db->query("update db_raw_cloning.tbl_raw a set a.flag = 0");

        for ($i=1; $i <= $n; $i++) { 
            echo "looping namasalur_current ke : ".$i."<br>";
            $sql_nama_salur_current = "
                update db_raw_cloning.tbl_raw a
                set a.namasalur_current = (
                    select b.jenis
                    from mpm.tbl_tabsalur b
                    where a.kodesalur_current = b.kode
                ), a.flag = 1
                where a.flag = 0
                limit $limit
            ";
            $proses_sektor_perkiraan = $this->db->query($sql_nama_salur_current);
            // echo "prosessektor_perkiraan : ".$prosessektor_perkiraan;
            echo "<pre>";
            print_r($sql_nama_salur_current);
            echo "</pre>";
            // echo "proses_hna : ".$proses_hna;
            echo "<hr>";
        }



        $finished_at = $this->model_outlet_transaksi->timezone();

        $this->db->set('created_finish', $finished_at);
        $this->db->where('id', $insert_id);
        $this->db->update('site.t_traffic'); // gives UPDATE mytable SET field = field+1 WHERE id = 2

        die;

        

    }

    public function deltomed_nasional(){

        ini_set("memory_limit","15000M");
        ini_set("max-execution_time", "-1");

        $this->load->dbutil(); 
        $this->load->helper('file'); 
        $this->load->helper('download');
        $delimiter = ","; 
        $newline = "\r\n";
        $filename = "deltomed_nasional.csv";  
        $query = "select * from db_raw_cloning.tbl_raw";
        $result = $this->db->query($query);
        $data = $this->dbutil->csv_from_result($result, $delimiter, $newline);
        if ( ! write_file('./assets/file/portal_raw_data/test.csv', $data))
        {
            echo 'Unable to write the file';
        }
        else 
        {
            echo 'File written!';
        }

    }

    public function pdf_kalender_data()
    {
        $this->db->select('region');
        $this->db->where('region is not null');
        $this->db->where('active', 1);
        $this->db->group_by('region');
        $region = $this->db->get('mpm.tbl_tabcomp')->result();

        $this->load->library('mypdf');
        // echo "<pre>";
        // var_dump($region);
        // echo "</pre>";
        // die;

        foreach ($region as $key => $value) {
            echo $value->region."<br>";
            $data = [
                'detail' => $this->model_master_data->get_kalender_data($value->region)->result(),
            ];

            $filename_pdf = "kalender_data_".strtolower($value->region);

            $generate_pdf = $this->mypdf->download_pdf('dashboard/template_pdf_kalender_data',$data,$filename_pdf,'A4','portrait');
        }
        // die;

        $this->get_email_akses_region();
    }

    public function get_email_akses_region()
    {
        $this->db->select('userid, username, email');
        $this->db->where('mpm.user.active', 1);
        $this->db->where('site.map_akses_region.status', 1);
        $this->db->join('mpm.user', 'mpm.user.id = site.map_akses_region.userid', 'inner');
        $this->db->group_by('userid');
        $userid = $this->db->get('site.map_akses_region')->result();

        // echo "<pre>";
        // var_dump($userid);
        // echo "</pre>";
        // die;

        foreach ($userid as $key => $value) {
            $data[] = [
                'userid' => $value->userid,
                'username' => $value->username,
                'email' => $value->email,
            ];
        }

        // echo "<pre>";
        // var_dump($data);
        // echo "</pre>";
        // die;

        
        $jml = count($data);
        // echo "jml : ".$jml;
        // die;
        for ($i=0; $i < $jml ; $i++) 
        {

            $username = $data[$i]['username'];

            // echo "username : ".$username;
            // die;

            $userid_params = $data[$i]['userid'];

            // $this->db->select('region');
            // $this->db->where('userid', $data[$i]['userid']);
            // $this->db->where('status', 1);
            // $this->db->group_by('region');
            // $region[] = $this->db->get('site.map_akses_region')->result();

            $query = "
                select *
                from site.map_akses_region a
                where a.userid = $userid_params
                group by a.region
            ";
            $region = $this->db->query($query)->result_array();

            // echo "<pre>";
            // var_dump($query);
            // echo "</pre>";

            // echo "<pre>";
            // var_dump($region);
            // echo "</pre>";

            // echo "<hr>";

            $this->send_email($region);

        }
        // die;

        // echo "<pre>";
        // var_dump($region);
        // echo "</pre>";

        // die;
        // $this->send_email($region);

    }

    public function send_email($region)
    {
        $jml = count($region);
        // var_dump($jml_region);

        echo "<pre>";
        var_dump($region);
        echo "</pre>";
        // die;
        
        for ($i=0; $i < $jml; $i++) 
        { 
            $jml_region = count($region[$i]);

            // echo $jml_region;
            // var_dump($jml_region);

            // die;

            for ($j=0; $j < $jml_region; $j++) 
            { 
                # code...
                // var_dump($region[$i]['region']);

                // die;


                // $file_kalender = file_exists('assets/file/pdf/kalender_data_'.strtolower($region[$i][$j]->region).'.pdf');
                $file_kalender = file_exists('assets/file/pdf/kalender_data_'.strtolower($region[$i]['region']).'.pdf');
                    
                if ($file_kalender == false){
                        // $no_wa = '081283453274';
                
                        // // echo "message_result : ".$message_result;
                        // // echo "no_wa : ".$no_wa;
                
                        // $userkey = '6ecb7f9537ef';
                        // $passkey = 'e96c4c8cee6ac177f83477c2';
                        // $telepon = $no_wa;
                        // $message = "Pengiriman email gagal. File kalender_data_".strtolower($region[$i][$j]->region)." tidak ditemukan";
                        // $url = 'https://console.zenziva.net/wareguler/api/sendWA/';
                        // $curlHandle = curl_init();
                        // curl_setopt($curlHandle, CURLOPT_URL, $url);
                        // curl_setopt($curlHandle, CURLOPT_HEADER, 0);
                        // curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, 1);
                        // curl_setopt($curlHandle, CURLOPT_SSL_VERIFYHOST, 2);
                        // curl_setopt($curlHandle, CURLOPT_SSL_VERIFYPEER, 0);
                        // curl_setopt($curlHandle, CURLOPT_TIMEOUT,30);
                        // curl_setopt($curlHandle, CURLOPT_POST, 1);
                        // curl_setopt($curlHandle, CURLOPT_POSTFIELDS, array(
                        //     'userkey' => $userkey,
                        //     'passkey' => $passkey,
                        //     'to' => $telepon,
                        //     'message' => $message
                        // ));
                        // $results = json_decode(curl_exec($curlHandle), true);
                        // curl_close($curlHandle);
                    echo "Pengiriman email gagal. File kalender_data_".strtolower($region[$i][$j]->region)." tidak ditemukan";
                    die;
                }else {
                    $attach[$i] = $this->email->attach('assets/file/pdf/kalender_data_'.strtolower($region[$i]['region']).'.pdf');
                }
            }

            // echo "<pre>";
            // var_dump($attach[$i]);
            // echo "</pre>";
            // die;

            $from = "suffy@muliaputramandiri.com";
            $to = "suffy.yanuar@gmail.com";
            // $cc = "ilhammsyah@gmail.com";\
            $subject = "MPM SITE | Informasi Harian Otomatis - Kalender Data";
            
            $message = "FYI" ;

            $this->email();
            $this->email->from($from,'PT. Mulia Putra Mandiri');
            $this->email->to($to);
            // $this->email->cc($cc);
            $this->email->subject($subject);
            $this->email->message($message);
            $attach[$i];
            // $this->email->attach('assets/file/request/'.$id.'.csv');
            $send = $this->email->send();
            // echo $this->email->print_debugger();
        }
    }

    public function automatic_kalender_data_email($userid){

        $get_region = "
            select a.region
            from site.map_akses_region a INNER JOIN
                (
                    select 	a.region
                    from 	mpm.tbl_tabcomp a 
                    where 	a.region is not null and a.active = 1
                    GROUP BY a.region
                )c on a.region = c.region
            where a.`status` = 1 and a.`status_email` = 1 and a.userid = $userid
        ";
        $proses_get_region = $this->db->query($get_region)->result();
        foreach ($proses_get_region as $key) 
        {
            $region = $key->region;
            // echo $region;

            //generate region
            $this->load->library('mypdf');
            $data = [
                'detail' => $this->model_master_data->get_kalender_data($region)->result(),
            ];

            $filename_pdf = "kalender_data_".strtolower($region);
            $generate_pdf = $this->mypdf->download_pdf('dashboard/template_pdf_kalender_data',$data,$filename_pdf,'A4','portrait');
            // end generate pdf
        }

        $get_attachment = $this->model_master_data->get_attachment($userid)->result();

        foreach ($get_attachment as $key) {
            $region_attachment = $key->region;
            $get_filename_pdf[] = $this->model_master_data->get_filename_pdf($region_attachment);

        }

        $send_email = $this->model_master_data->send_email_kalender_data($get_filename_pdf,$userid);
        return true;

    }

    public function automatic_kalender_data_email_generate(){

        $get_userid = "
            select a.userid
            from site.map_akses_region a 
            where a.`status` = 1 and a.`status_email` = 1 and a.status_proses = 1
            GROUP BY a.userid
            limit 1
        ";
        # code...
        $proses_get_userid = $this->db->query($get_userid)->result();
        $jum = count($proses_get_userid);
        
        if ($jum == '1') {
            foreach ($proses_get_userid as $key) {
        
                $userid = $key->userid;
                // $userid = 445;
                $data = [
                    "status_proses" => 0,
                    "last_updated"  => $this->model_outlet_transaksi->timezone()
                ];
                $this->db->where('userid', $userid);
                $update = $this->db->update('site.map_akses_region', $data);
    
                $this->automatic_kalender_data_email($userid);
                // echo "userid : ".$userid;
                // echo "<hr>";
            }
        } else {
            $data = [
                "status_proses" => 1,
                "last_updated"  => $this->model_outlet_transaksi->timezone()
            ];
            
            $this->db->where('status_email', 1);
            $update = $this->db->update('site.map_akses_region', $data);
        }
        
    }

    public function warpin_automatic_post(){
        $query = "
            select *
            from site.t_warpin_schedule a
            where a.from_aplikasi = 'warpin' and a.status_proses_schedule = 1
        ";
        $proses = $this->db->query($query)->result();
        if ($proses) {
            foreach ($proses as $key) {
                $signature = $key->signature_mpm;

                $data = [
                    "status_proses_schedule"    => 0
                ];
                $this->db->where('signature_mpm', $signature);
                $this->db->update('site.t_warpin_schedule', $data);

                redirect(base_url().'broadcast/order_confirmation/'.$signature);
            }
        }
    }

    public function accept_supplychain($signature){
        $this->load->model('model_relokasi');
        $get_id_ref_relokasi_header = $this->model_relokasi->get_data_relokasi_header($signature)->row()->id;
        $principal = $this->model_relokasi->get_data_relokasi_header($signature)->row()->principal;
        
        $data = [
            'title'       => 'Accept Relokasi',
            'history_produk' => $this->model_relokasi->history_produk($get_id_ref_relokasi_header),
            'history_relokasi'     => $this->model_relokasi->history_relokasi('',$signature),
            // 'principal'   => $principal,
            'signature' => $signature
        ];

        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/top_header_signature');
        $this->load->view('template_claim/header_masterdata');
        $this->load->view('relokasi/accept_supplychain', $data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');

    }

    public function reject_supplychain($signature){

        $data = [
            'status'        => '5',
            'nama_status'   => 'REJECTED BY SUPPLYCHAIN',
            'approve_supplychain_at'    => $this->model_outlet_transaksi->timezone()
        ];
        $this->db->where('signature', $signature);
        $this->db->update('site.t_relokasi_header', $data);

        redirect('master_data/accept_supplychain/'.$signature);
    }

    public function accept_supplychain_proses(){

        $signature = $this->input->post('signature');

        // cek apakah sudah ada di database
        $folderPath = './assets/uploads/signature/';        
        $image_parts = explode(";base64,", $_POST['signed']);
        $image_type_aux = explode("image/", $image_parts[0]);
        $image_type = $image_type_aux[1];
        $image_base64 = base64_decode($image_parts[1]);
        $file = $folderPath . $signature . '-signature.' .$image_type;
        file_put_contents($file, $image_base64);

        $data = [
            'approve_supplychain_at'    => $this->model_outlet_transaksi->timezone(),
            'approve_supplychain_by'    => $signature . '-signature.' .$image_type,
            'approve_supplychain_signature' => $signature . '-signature.' .$image_type,
            'status'                    => '3',
            'nama_status'               => 'NEED FINANCE APPROVAL'
        ];

        $this->db->where('signature', $signature);
        $this->db->update('site.t_relokasi_header', $data);

        // redirect('master_data/accept_supplychain/'.$signature);
        redirect('master_data/approval_finance/'.$signature);
        // echo "signature uploaded successsfully";
    }

    public function approval_finance($signature){
        
        // 1. status dari DRAFT menjadi OPEN
        $data = [
            'status'        => '3',
            'nama_status'   => 'NEED FINANCE APPROVAL',
        ];
        $this->db->where('signature', $signature);
        $this->db->update('site.t_relokasi_header', $data);
        
        // 2. mengirim email ke supplychain head;

        redirect('master_data/email_finance/'.$signature);
    }

    public function email_finance($signature){
        $this->load->model('model_relokasi');
        // echo 'start email';

        // from linda@muliaputramandiri.com
        $from = "suffy@muliaputramandiri.com";

        // to hwiryanto@muliaputramandiri.com
        $to = 'suffy.yanuar@gmail.com';

        // cc email_dp
        $get_from_site= substr($this->model_relokasi->get_data_relokasi_header($signature)->row()->from_site,0,3);
        $get_email_from_site = $this->model_relokasi->get_email_from_username($get_from_site)->row()->email;
        // $cc = $get_email_from_site;
        $cc = 'suffy.mpm@gmail.com';
        
        $no_relokasi= $this->model_relokasi->get_data_relokasi_header($signature)->row()->no_relokasi;
        $subject = "MPM Site | Ajuan Relokasi : $no_relokasi | NEED FINANCE APPROVAL";
        // echo "no_relokasi : ".$no_relokasi;

        $this->model_relokasi->email();

        $get_id_ref_relokasi_header = $this->model_relokasi->get_data_relokasi_header($signature)->row()->id;

        $data = [
            'no_relokasi'       => $this->model_relokasi->history_relokasi('',$signature)->row()->no_relokasi,
            'tanggal_pengajuan' => $this->model_relokasi->history_relokasi('',$signature)->row()->tanggal_pengajuan,
            'from_nama_comp'    => $this->model_relokasi->history_relokasi('',$signature)->row()->from_nama_comp,
            'to_nama_comp'      => $this->model_relokasi->history_relokasi('',$signature)->row()->to_nama_comp,
            'nama'              => $this->model_relokasi->history_relokasi('',$signature)->row()->nama,
            'namasupp'          => $this->model_relokasi->history_relokasi('',$signature)->row()->namasupp,
            'nama_status'       => $this->model_relokasi->history_relokasi('',$signature)->row()->nama_status,
            'alasan'            => $this->model_relokasi->history_relokasi('',$signature)->row()->alasan,
            'history_produk'    => $this->model_relokasi->history_produk($get_id_ref_relokasi_header),
            'signature'         => $signature
        ];
        $message = $this->load->view("relokasi/email_finance",$data,TRUE);

        $this->email->from($from,'PT. Mulia Putra Mandiri');
        $this->email->to($to);
        $this->email->cc($cc);
        $this->email->subject($subject);
        $this->email->message($message);
        // $this->email->attach('assets/file/retur/'.str_replace('/','_',$no_pengajuan).'.csv');
        $send = $this->email->send();
        // echo $this->email->print_debugger();
        if ($send) {
            echo "<script>alert('pengiriman email berhasil'); </script>";
            redirect('master_data/accept_supplychain/'.$signature);
        }else{
            echo "<script>alert('pengiriman email gagal'); </script>";
            redirect('master_data/accept_supplychain/'.$signature);
        }
    }

    public function accept_finance($signature){
        $this->load->model('model_relokasi');
        $get_id_ref_relokasi_header = $this->model_relokasi->get_data_relokasi_header($signature)->row()->id;
        $principal = $this->model_relokasi->get_data_relokasi_header($signature)->row()->principal;
        
        $data = [
            'title'       => 'Accept Relokasi',
            'history_produk' => $this->model_relokasi->history_produk($get_id_ref_relokasi_header),
            'history_relokasi'     => $this->model_relokasi->history_relokasi('',$signature),
            // 'principal'   => $principal,
            'signature' => $signature
        ];

        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/top_header_signature');
        $this->load->view('template_claim/header_masterdata');
        $this->load->view('relokasi/accept_finance', $data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');

    }

    public function accept_finance_proses(){

        $signature = $this->input->post('signature');

        // cek apakah sudah ada di database
        $folderPath = './assets/uploads/signature/';        
        $image_parts = explode(";base64,", $_POST['signed']);
        $image_type_aux = explode("image/", $image_parts[0]);
        $image_type = $image_type_aux[1];
        $image_base64 = base64_decode($image_parts[1]);
        $file = $folderPath . $signature . '-signature-finance.' .$image_type;
        file_put_contents($file, $image_base64);

        $data = [
            'approve_finance_at'    => $this->model_outlet_transaksi->timezone(),
            'approve_finance_by'    => $signature . '-signature-finance.' .$image_type,
            'approve_finance_signature' => $signature . '-signature-finance.' .$image_type,
            'status'                    => '4',
            'nama_status'               => 'APPROVED'
        ];

        $this->db->where('signature', $signature);
        $this->db->update('site.t_relokasi_header', $data);

        redirect('master_data/accept_finance/'.$signature);
        // echo "signature uploaded successsfully";
    }

    public function reject_finance($signature){

        $data = [
            'status'        => '6',
            'nama_status'   => 'REJECTED BY FINANCE',
            'approve_finance_at'    => $this->model_outlet_transaksi->timezone()
        ];
        $this->db->where('signature', $signature);
        $this->db->update('site.t_relokasi_header', $data);

        redirect('master_data/accept_finance/'.$signature);
    }

    public function rpd_verifikasi1($signature_pengajuan){

        $this->load->model('model_management_rpd');

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
            $jumlah_verifikasi           = $key->jumlah_verifikasi;
            $verifikasi1_ttd           = $key->verifikasi1_ttd;
            $verifikasi2_ttd           = $key->verifikasi2_ttd;
        }
        $get_aktivitas = $this->model_management_rpd->get_aktivitas($id);
        $total_biaya = $this->model_management_rpd->get_total_biaya($id)->row()->total_biaya;

        $data = [
            'title'                     => 'Rencana Perjalanan Dinas - Verifikasi 1',
            'no_rpd'                    => $no_rpd,
            'pelaksana'                 => $pelaksana,
            'maksud_perjalanan_dinas'   => $maksud_perjalanan_dinas,
            'berangkat'                 => $tempat_berangkat.' at '.$tanggal_berangkat,
            'tiba'                      => $tempat_tiba.' at '.$tanggal_tiba,
            'url'                       => 'master_data/verifikasi1_update',
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
            'verifikasi1_ttd'          => $verifikasi1_ttd,
            'verifikasi2_ttd'          => $verifikasi2_ttd,
            'verifikasi1_keterangan'          => $verifikasi1_keterangan,
            'verifikasi2_keterangan'          => $verifikasi2_keterangan,
            'jumlah_verifikasi'          => $jumlah_verifikasi,
        ];

        $this->load->view('management_rpd/top_header', $data);        
        $this->load->view('template_claim/top_header_signature');
        $this->load->view('kalimantan/header_full_width', $data);
        $this->load->view('management_rpd/verifikasi1', $data);
        $this->load->view('kalimantan/footer');
    }

    public function verifikasi1_update(){
        $status_verifikasi = $this->input->post('status_verifikasi');
        $password_login = $this->input->post('password_login');
        $id_pengajuan = $this->input->post('id_pengajuan');
        $userid_verifikasi1 = $this->input->post('userid_verifikasi1');
        $signature_pengajuan = $this->input->post('signature_pengajuan');

        $signature = 'RPD-' . rand() . md5($this->model_outlet_transaksi->timezone()) . date('Ymd');

        $query = "select * from mpm.user a where a.id = $userid_verifikasi1";
        $password_user = $this->db->query($query)->row()->password;

        if (md5($password_login) != $password_user) {
            echo "<script>alert('password web salah'); </script>";
            redirect('master_data/rpd_verifikasi1/'.$signature_pengajuan,'refresh');
            die;
        }

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

        // cek apakah sudah ada di database
        $folderPath = './assets/uploads/signature/';        
        $image_parts = explode(";base64,", $_POST['signed']);
        $image_type_aux = explode("image/", $image_parts[0]);
        $image_type = $image_type_aux[1];
        $image_base64 = base64_decode($image_parts[1]);
        $file = $folderPath . $signature_pengajuan . '-verifikasi1.' .$image_type;
        file_put_contents($file, $image_base64);

        // echo $signed;

        $data = [
            // "id_pengajuan"          => $id_pengajuan,
            "verifikasi1_by"        => $userid_verifikasi1,
            "verifikasi1_at"        => $this->model_outlet_transaksi->timezone(),
            "verifikasi1_status"    => $status,
            "verifikasi1_name"      => $verifikasi_name,
            "verifikasi1_keterangan"=> $keterangan_verifikasi,
            "verifikasi1_ttd"       => $signature_pengajuan . '-verifikasi1.' .$image_type,
            // "signature"             => $signature
            "status"                => $status,
            "nama_status"           => $nama_status
        ];

        $this->db->where("signature", $signature_pengajuan);
        $this->db->update("management_rpd.pengajuan", $data);

        redirect('master_data/rpd_verifikasi1/'.$signature_pengajuan);



    }

    public function rpd_verifikasi2($signature_pengajuan){

        $this->load->model('model_management_rpd');

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
            $jumlah_verifikasi           = $key->jumlah_verifikasi;
        }
        $get_aktivitas = $this->model_management_rpd->get_aktivitas($id);
        $total_biaya = $this->model_management_rpd->get_total_biaya($id)->row()->total_biaya;

        $data = [
            'title'                     => 'Rencana Perjalanan Dinas - Verifikasi 2',
            'no_rpd'                    => $no_rpd,
            'pelaksana'                 => $pelaksana,
            'maksud_perjalanan_dinas'   => $maksud_perjalanan_dinas,
            'berangkat'                 => $tempat_berangkat.' at '.$tanggal_berangkat,
            'tiba'                      => $tempat_tiba.' at '.$tanggal_tiba,
            'url'                       => 'master_data/verifikasi2_update',
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
            'jumlah_verifikasi'          => $jumlah_verifikasi,
        ];

        $this->load->view('management_rpd/top_header', $data);        
        $this->load->view('template_claim/top_header_signature');
        $this->load->view('kalimantan/header_full_width', $data);
        $this->load->view('management_rpd/verifikasi2', $data);
        $this->load->view('kalimantan/footer');
    }

    public function verifikasi2_update(){
        $status_verifikasi = $this->input->post('status_verifikasi');
        $password_login = $this->input->post('password_login');
        $id_pengajuan = $this->input->post('id_pengajuan');
        $userid_verifikasi2 = $this->input->post('userid_verifikasi2');
        $signature_pengajuan = $this->input->post('signature_pengajuan');

        $signature = 'RPD-' . rand() . md5($this->model_outlet_transaksi->timezone()) . date('Ymd');

        $query = "select * from mpm.user a where a.id = $userid_verifikasi2";
        $password_user = $this->db->query($query)->row()->password;

        if (md5($password_login) != $password_user) {
            echo "<script>alert('password web salah'); </script>";
            redirect('master_data/rpd_verifikasi2/'.$signature_pengajuan,'refresh');
            die;
        }

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

        // cek apakah sudah ada di database
        $folderPath = './assets/uploads/signature/';        
        $image_parts = explode(";base64,", $_POST['signed']);
        $image_type_aux = explode("image/", $image_parts[0]);
        $image_type = $image_type_aux[1];
        $image_base64 = base64_decode($image_parts[1]);
        $file = $folderPath . $signature_pengajuan . '-verifikasi2.' .$image_type;
        file_put_contents($file, $image_base64);

        // echo $signed;

        $data = [
            // "id_pengajuan"          => $id_pengajuan,
            "verifikasi2_by"        => $userid_verifikasi2,
            "verifikasi2_at"        => $this->model_outlet_transaksi->timezone(),
            "verifikasi2_status"    => $status,
            "verifikasi2_name"      => $verifikasi_name,
            "verifikasi2_keterangan"=> $keterangan_verifikasi,
            "verifikasi2_ttd"       => $signature_pengajuan . '-verifikasi2.' .$image_type,
            // "signature"             => $signature
            "status"                => $status,
            "nama_status"           => $nama_status
        ];

        $this->db->where("signature", $signature_pengajuan);
        $this->db->update("management_rpd.pengajuan", $data);

        redirect('master_data/rpd_verifikasi2/'.$signature_pengajuan);



    }

    public function update_kalender_data(){

        $tahun_sekarang = date('Y');
        $bulan_sekarang = date('m');
        $tanggal_sekarang = date('d');

        $created_at = $this->model_outlet_transaksi->timezone();

        if ($tanggal_sekarang <= '7') {
            $tahun_sekarang_x = date("Y", strtotime("-1 month"));
            $bulan_sekarang_x = date("m", strtotime("-1 month"));
        }else{
            $tahun_sekarang_x = $tahun_sekarang;
            $bulan_sekarang_x = $bulan_sekarang;
        }

        $truncate = "truncate management_office.kalender_data";
        $truncate_proses = $this->db->query($truncate);
        if ($truncate_proses) {
            $query = "      
                insert into management_office.kalender_data      
                select '', a.kode,b.branch_name,b.nama_comp, a.tanggal_data, c.status_closing,'$created_at'
                FROM
                (
                    select concat(a.kode_comp,a.nocab) as kode,max(CAST(a.HRDOK as int)) as tanggal_data
                    from data$tahun_sekarang_x.fi a
                    where bulan = $bulan_sekarang_x
                    GROUP BY kode
                )a LEFT JOIN
                (
                    select concat(a.kode_comp,a.nocab) as kode, a.branch_name,a.nama_comp, a.urutan
                    from mpm.tbl_tabcomp a
                    where a.`status` = 1
                    GROUP BY concat(a.kode_comp,a.nocab)
                )b on a.kode = b.kode left join 
                (
                    select a.id, a.username, b.status_closing
                    from mpm.user a LEFT JOIN (
                        select a.id, a.userid, a.lastupload, a.filename, a.tanggal, a.bulan, a.tahun, a.status, a.status_closing, a.omzet
                        from mpm.upload a
                        where a.tahun = $tahun_sekarang_x and a.bulan = $bulan_sekarang_x and a.lastupload = (
                            select max(b.lastupload)
                            from mpm.upload b 
                            where a.userid = b.userid and b.tahun = $tahun_sekarang_x and b.bulan = $bulan_sekarang_x
                        )
                        ORDER BY a.userid
                    )b on a.id = b.userid
                )c on left(a.kode, 3) = c.username
                ORDER BY urutan
            ";

            echo "<pre>";
            print_r($query);
            echo "</pre>";

            $proses = $this->db->query($query);

        }
    }

    public function update_monitoring_count(){
        $created_at = $this->model_outlet_transaksi->timezone();
        $tahun_sekarang = date('Y');

        $cek_null = $this->db->query('select * from management_office.monitoring_count')->num_rows();
        if($cek_null > 0){

            $get_last_data = "
                select *
                from management_office.monitoring_count a 
                where a.created_at = (
                    select max(b.created_at)
                    from management_office.monitoring_count b
                )
            ";

            $proses_get_last = $this->db->query($get_last_data)->result();
            foreach ($proses_get_last as $last) {
                $tahun_last = $last->tahun;
                $bulan_last = $last->bulan;
                $count_fi_last = $last->count_fi;
                $count_ri_last = $last->count_ri;
                // echo "tahun : ".$tahun_last;
                // echo "bulan : ".$bulan_last;
                // echo "count_fi : ".$count_fi_last;
                // echo "count_ri : ".$count_ri_last;
                // echo "<hr>";

                $query = "
                    insert into management_office.monitoring_count
                    select 	'', $tahun_sekarang, a.bulan, sum(count_fi), sum(count_ri), if(sum(count_fi) = $count_fi_last, 1, 0), 
                            if(sum(count_ri) = $count_ri_last, 1, 0), '$created_at'
                    from 
                    (
                        select bulan, count(*) as count_fi, '' as count_ri
                        from data$tahun_sekarang.fi a 
                        where a.bulan = $bulan_last
                        union all
                        select bulan, '', count(*) as count_fi
                        from data$tahun_sekarang.ri a 
                        where a.bulan = $bulan_last
                    )a GROUP BY a.bulan
                ";

                $this->db->query($query);
            }
        }else{

            $query = "
                insert into management_office.monitoring_count
                select '', $tahun_sekarang, a.bulan, sum(count_fi), sum(count_ri), '','', '$created_at'
                from 
                (
                    select bulan, count(*) as count_fi, '' as count_ri
                    from data$tahun_sekarang.fi a 
                    GROUP BY a.bulan
                    union all
                    select bulan, '', count(*) as count_fi
                    from data$tahun_sekarang.ri a 
                    GROUP BY a.bulan
                )a GROUP BY a.bulan
            ";

            $proses = $this->db->query($query);
        }
    }

    public function proses_backup()
    {
        $site = $this->uri->segment('3');
        $this->load->model('model_outlet_transaksi');
        $time = $this->model_outlet_transaksi->timezone();

        $data = [
                'site'          => $site,
                'status'        => 'Berhasil Backup',
                'created_at'    => $time,
        ];

        $this->db->insert('site.log_backup', $data);
    }

    public function proses_insert_pajak_masukan()
    {
        $this->model_master_data->insert_pajak_masukan();
    }

    public function update_data_kam(){
        $this->load->model(array('model_mti','model_management_office'));
        $data_source = [
            'herbal'    => $this->model_mti->get_kodeprod_by_group('G0101'),
            'candy'     => $this->model_mti->get_kodeprod_by_group_exception('G0102', '010121'),
            'kode_type' => $this->model_mti->get_kode_type_by_sektor('MTI'),
            'kode_type_ph' => $this->model_mti->get_kode_type_by_segment('PH'),
            'all_principal'  => $this->model_mti->get_kodeprod_by_supp(),
        ];

        $update = $this->model_management_office->update_data_kam($data_source);

        if ($update) {
            redirect('management_office/');
        }else{
            echo "something happen. Please call IT";
        }        
    }

    public function update_sell_out(){   
        $this->load->model('model_mti');
        $kodeprod = $this->model_mti->get_kodeprod_by_supp('001');
        $update = $this->model_master_data->update_sell_out($kodeprod);
    }

    public function update_sell_out_us(){
        
        $this->load->model('model_mti');
        $kodeprod = $this->model_mti->get_kodeprod_by_supp('005');
        $update = $this->model_master_data->update_sell_out_us($kodeprod);
    }

    public function update_sell_out_deltomed_segment(){
        
        $this->load->model('model_mti');
        $kodeprod = $this->model_mti->get_kodeprod_by_supp('001');
        $update = $this->model_master_data->update_sell_out_deltomed_segment($kodeprod);
    }

    public function insert_temp_po_sds(){

        $created_at     = $this->model_outlet_transaksi->timezone(); 
        $this->model_master_data->insert_temp_po_sds($created_at); 

        $get_temp_po_sds = $this->model_master_data->get_temp_po_sds($created_at);
        if ($get_temp_po_sds->num_rows() > 0) {
            echo "collecting data from sds at $created_at success";
            $this->insert_po_sds();
        }else{
            $this->notifikasi_email();
        }
        
    }

    public function insert_po_sds() {
        // $tanggal = date("Y-m-d", strtotime("-4 days"));
        $tanggal = date("Y-m-d", strtotime($this->model_outlet_transaksi->timezone()));
        // var_dump($tanggal);die;
        $this->model_master_data->insert_fi_ri_sds($tanggal);
        //get


        $this->model_master_data->insert_tblang_sds($tanggal);
        // get
        
        $this->model_master_data->insert_tabsales_sds($tanggal);
        // get

    }

    public function notifikasi_email(){
        echo "gagal";
    }
}
