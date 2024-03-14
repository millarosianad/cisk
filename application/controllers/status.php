<?php

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Status extends MY_Controller
{

    function Status()
    {
        set_time_limit(0);
        $this->load->library(array('table','template','Excel_generator', 'form_validation','email'));
        $this->load->helper('url');
        $this->load->helper('csv');
        $this->load->model('M_loyalty');
        $this->load->model('M_claim');
        $this->load->model('M_menu');
        $this->load->model('model_status');
        $this->load->model('model_omzet');
        $this->load->model('model_verifikasi_harga');
        $this->load->model('model_kalender_data');
        $this->load->database();
    }
    function index()
    {

        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }

    }

    private function template($view,$data)
    {
        $this->template->set_title('MPM');
        $this->template->add_js('modules/skeleton.js');
        $this->template->add_css('modules/skeleton.css');
        $this->template->load_view($view, $data);
    }

                                        // VERIFIKASI HARGA

    public function input_data_verifikasi(){

        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
            
            $data['url'] = 'status/input_data_verifikasi_hasil/';
            $data ['title'] = 'Informasi Harga Jual DP';
            $data['query'] = $this->model_omzet->getSuppbyid();
            $data['get_label'] = $this->M_menu->get_label();
           

            $this->load->view('template_claim/top_header');
            $this->load->view('template_claim/header');
            $this->load->view('template_claim/sidebar',$data);
            $this->load->view('template_claim/top_content',$data);
            $this->load->view('template_verifikasi/input_data',$data);
            $this->load->view('template_claim/bottom_content');
            $this->load->view('template_claim/footer');
    }

    public function input_data_verifikasi_hasil(){

        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }

        $data['tahun'] = $this->input->post('tahun');
        $data['bulan'] = $this->input->post('bulan');

        $data['proses'] = $this->model_verifikasi_harga->input_data_verifikasi($data);

        $data['url'] = 'status/input_data_verifikasi_hasil/';
        $data ['title'] = 'Informasi Harga Jual DP';
        $data['bln'] = $this->input->post('bulan');
        $data['query'] = $this->model_omzet->getSuppbyid();
        $data['get_label'] = $this->M_menu->get_label();

        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar',$data);
        $this->load->view('template_claim/top_content',$data);
        $this->load->view('template_verifikasi/input_data_verifikasi_hasil',$data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
            
    }
    // export excel
   public function export_verifikasi_harga(){
        $id=$this->session->userdata('id');
        $sql = "
                    select kode, branch_name as Branch, nama_comp as Sub_Branch, kodeprod, namaprod as Nama_produk, harga as Harga_DP, h_dp as Harga_PO
                    from mpm.t_verifikasi_harga_temp
                    where id = $id
                ";
        $quer = $this->db->query($sql);

        query_to_csv($quer,TRUE,'Informasi Harga produk.csv');
    }


                                        // Kalender Data

    public function view_kalender_data(){    

        $data['page_content'] = 'kalender_data/view_kalender_data';                      
        $data['menu']=$this->db->query($this->querymenu);
        $data['url']="status/proses_kalender_data";
        $data['page_title']="Kalender Data";
        //$data['query']=$this->model_kalender_data->view_kalender_data();
        $this->template($data['page_content'],$data); 
                
    }

    public function proses_kalender_data(){    

        $data = array(                
                'jenis_data'       => $this->input->post('jenis_data'),
                'tahun'           => $this->input->post('tahun')
        );
        /*
        echo "jenis_data  : ".$data['jenis_data']."<br>";
        echo "tahun       : ".$data['tahun']."<br>";                 
        */
        $data['page_content'] = 'kalender_data/view_kalender_data_hasil';                      
        $data['menu']=$this->db->query($this->querymenu);
        $data['url']="status/proses_kalender_data";
        $data['page_title']="Kalender Data";
        $data['tahun_pilih'] = $this->input->post('tahun');
        $jenis_data_pilih = $this->input->post('jenis_data');
        if ($jenis_data_pilih == 1) {
            $data['jenis_data_pilih_x'] = 'Data Text MPM';
        }else{
            $data['jenis_data_pilih_x'] = 'Data Upload Website';
        }

        $data['query']=$this->model_kalender_data->view_kalender_data($data);
        $this->template($data['page_content'],$data); 
                
    }

    public function view_kalender_data_closing(){    

        $data['page_content'] = 'kalender_data/view_kalender_data_closing';                      
        $data['menu']=$this->db->query($this->querymenu);
        $data['url']="status/proses_kalender_data_closing";
        $data['page_title']="Kalender Data";
        $data['query']=$this->model_kalender_data->view_kalender_data_closing();
        $this->template($data['page_content'],$data); 
                
    }

    public function proses_kalender_data_closing(){    

        $data['page_content'] = 'kalender_data/view_kalender_data_closing';                      
        $data['menu']=$this->db->query($this->querymenu);
        $data['url']="status/proses_kalender_data_closing";
        $data['page_title']="Kalender Data";
        $data['query']=$this->model_kalender_data->view_kalender_data_closing();
        $this->template($data['page_content'],$data); 
                
    }

    public function history_upload(){    

        $data['userid'] = $this->uri->Segment('3');
        //echo $data['userid'];
        $data['page_content'] = 'kalender_data/view_history_data';                      
        $data['menu']=$this->db->query($this->querymenu);
        //$data['url']="all_kalender_data/proses_kalender_data";
        $data['page_title']="History Data";
        $data['query']=$this->model_kalender_data->view_history_data($data);
        $this->template($data['page_content'],$data); 
                
    }

    public function upload_stock(){

        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }

        $data = [
            'id'            => $this->session->userdata('id'),
            'url'           => 'status/upload_stock_hasil/',
            'get_label'     => $this->M_menu->get_label(),
            // 'query'         => $this->model_omzet->getSuppbyid(),
            'title'         => 'Upload Stock',
            'get_subbranch' => $this->model_status->get_subbranch()
        ];                   
        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar',$data);
        $this->load->view('template_claim/top_content',$data);
        $this->load->view('upload_data/upload_stock',$data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
    }

    public function upload_stock_hasil()
    {
        date_default_timezone_set('Asia/Jakarta');
        $logged_in = $this->session->userdata('logged_in');
        if (!isset($logged_in) || $logged_in != TRUE) {
            redirect('login/', 'refresh');
        }

        $id = $this->session->userdata('id');
        $subbranch = $this->input->post('subbranch');
        $tahun = $this->input->post('tahun');
        $bulan = $this->input->post('bulan');
        // echo "<br><br><br><br><br><br><br><br><br>subbranch : ".$subbranch."<br>";


        if (!is_dir('./assets/uploads/stock/' . date('Ym') . '/')) {
            @mkdir('./assets/uploads/stock/' . date('Ym') . '/', 0777);
        }

        $tgl_created = date('Y-m-d H:i:s');
        $this->load->library('upload'); // Load librari upload        
        $config['upload_path'] = './assets/uploads/stock/' . date('Ym') . '';
        // $config['allowed_types'] = 'xls|xlsx';
        $config['allowed_types'] = '*';    
        $config['max_size']  = '7048';
        $config['overwrite'] = true;
        $this->upload->initialize($config);

        if ($this->upload->do_upload('file')) {
            $upload_data = $this->upload->data();
            $filename = $upload_data['orig_name'];
            $file_type = $upload_data['file_type'];
            //   echo "filename : ".$filename."<br>";
            //   echo "file_type : ".$file_type;
            $this->load->library('excel');
            $object = PHPExcel_IOFactory::load("assets/uploads/stock/" . date('Ym') . "/$filename");

            $jumlahSheet = $object->getSheetCount();
            // echo "jumlahsheet : ".$jumlahSheet."<br>";
            if ($jumlahSheet > 1) {
                // echo "jumlah_sheet : ".$jumlahSheet;
            }
            if ($subbranch == 'SBMS2') {

                foreach ($object->getWorksheetIterator() as $worksheet) {
                    $highestRow = $worksheet->getHighestRow();
                    $highestColumn = $worksheet->getHighestColumn();
                    for ($row = 2; $row <= $highestRow; $row++) {
                        $kodeprod = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
                        $namaprod = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
                        $satuan = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
                        $stock = $worksheet->getCellByColumnAndRow(4, $row)->getValue();


                        $data[] = array(
                            'kode'              =>    $subbranch,
                            'kodeprod'          =>    $kodeprod,
                            'namaprod'          =>    $namaprod,
                            'satuan'            =>    $satuan,
                            'stock'             =>    $stock,
                            'filename'          =>    $filename,
                            'created_date'      =>    $tgl_created,
                            'id'                =>    $id,
                        );
                    }
                }

                $insert = $this->model_status->upload_stock($data, $subbranch);

                $data = [
                    'id'                    => $this->session->userdata('id'),
                    'url'                   => 'sales_omzet/omzet_dp_hasil/',
                    'get_label'             => $this->M_menu->get_label(),
                    'get_upload_stock'      => $this->model_status->get_upload_stock_bangka($tgl_created),
                    'kode'                  => $subbranch,
                    'tahun'                 => $tahun,
                    'bulan'                 => $bulan,
                    'title'                 => 'Upload Stock'
                ];

                $this->load->view('template_claim/top_header');
                $this->load->view('template_claim/header');
                $this->load->view('template_claim/sidebar', $data);
                $this->load->view('template_claim/top_content', $data);
                $this->load->view('upload_data/upload_stock_bangka_hasil', $data);
                $this->load->view('template_claim/bottom_content');
                $this->load->view('template_claim/footer');
                
                
            } else if ($subbranch == 'SBTB5') {

                foreach ($object->getWorksheetIterator() as $worksheet) {
                    $highestRow = $worksheet->getHighestRow();
                    $highestColumn = $worksheet->getHighestColumn();
                    for ($row = 2; $row <= $highestRow; $row++) {
                        $kd_cabang = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
                        $kd_gudang = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
                        $kd_paret = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
                        $kd_barang = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
                        $so_awal = $worksheet->getCellByColumnAndRow(4, $row)->getValue();
                        $masuk = $worksheet->getCellByColumnAndRow(5, $row)->getValue();
                        $keluar = $worksheet->getCellByColumnAndRow(6, $row)->getValue();
                        $so_akhir = $worksheet->getCellByColumnAndRow(7, $row)->getValue();
                        $lk_namagudang = $worksheet->getCellByColumnAndRow(8, $row)->getValue();
                        $lk_namacabang = $worksheet->getCellByColumnAndRow(9, $row)->getValue();
                        $cl_soawal = $worksheet->getCellByColumnAndRow(10, $row)->getValue();
                        $lk_namabarang = $worksheet->getCellByColumnAndRow(11, $row)->getValue();
                        $cl_masuk = $worksheet->getCellByColumnAndRow(12, $row)->getValue();
                        $cl_keluar = $worksheet->getCellByColumnAndRow(13, $row)->getValue();
                        $cl_soakhir = $worksheet->getCellByColumnAndRow(14, $row)->getValue();
                        $cl_satuan = $worksheet->getCellByColumnAndRow(15, $row)->getValue();
                        $spec = $worksheet->getCellByColumnAndRow(16, $row)->getValue();
                        $kd_merk = $worksheet->getCellByColumnAndRow(17, $row)->getValue();
                        $nama_barang = $worksheet->getCellByColumnAndRow(18, $row)->getValue();
                        $cl_nama_merk = $worksheet->getCellByColumnAndRow(19, $row)->getValue();
                        $kd_supplier = $worksheet->getCellByColumnAndRow(20, $row)->getValue();
                        $perusahaan = $worksheet->getCellByColumnAndRow(21, $row)->getValue();

                        $data[] = array(
                            'kode'          =>    $subbranch,
                            'kd_cabang'        =>    $kd_cabang,
                            'kd_gudang'       =>    $kd_gudang,
                            'kd_paret'        =>    $kd_paret,
                            'kd_barang'        =>    $kd_barang,
                            'so_awal'        =>    $so_awal,
                            'lk_namabarang'    =>    $lk_namabarang,
                            'cl_masuk'        =>    $cl_masuk,
                            'cl_keluar'        =>    $cl_keluar,
                            'cl_soakhir'    =>    $cl_soakhir,
                            'cl_satuan'        =>    $cl_satuan,
                            'spec'            =>    $spec,
                            'kd_merk'        =>    $kd_merk,
                            'nama_barang'    =>    $nama_barang,
                            'cl_nama_merk'    =>    $cl_nama_merk,
                            'kd_supplier'    =>    $kd_supplier,
                            'perusahaan'    =>    $perusahaan,
                            'masuk'            =>    $masuk,
                            'keluar'        =>    $keluar,
                            'so_akhir'        =>    $so_akhir,
                            'lk_namagudang'    =>    $lk_namagudang,
                            'lk_namacabang'    =>    $lk_namacabang,
                            'cl_soawal'        =>    $cl_soawal,
                            'filename'      =>    $filename,
                            'created_date'  =>  $tgl_created,
                            'id'            =>    $id,
                        );
                    }
                }

                $insert = $this->model_status->upload_stock($data, $subbranch);

                $data = [
                    'id'                    => $this->session->userdata('id'),
                    'url'                   => 'sales_omzet/omzet_dp_hasil/',
                    'get_label'             => $this->M_menu->get_label(),
                    'get_upload_stock'      => $this->model_status->get_upload_stock_baturaja($tgl_created),
                    'kode'                  => $subbranch,
                    'tahun'                 => $tahun,
                    'bulan'                 => $bulan,
                    'title'                 => 'Upload Stock'
                ];

                $this->load->view('template_claim/top_header');
                $this->load->view('template_claim/header');
                $this->load->view('template_claim/sidebar', $data);
                $this->load->view('template_claim/top_content', $data);
                $this->load->view('upload_data/upload_stock_baturaja_hasil', $data);
                $this->load->view('template_claim/bottom_content');
                $this->load->view('template_claim/footer');

            } else if ($subbranch == 'SBJS7') {

                foreach ($object->getWorksheetIterator() as $worksheet) {
                    $highestRow = $worksheet->getHighestRow();
                    $highestColumn = $worksheet->getHighestColumn();
                    for ($row = 2; $row <= $highestRow; $row++) {
                        $kd_cabang = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
                        $kd_gudang = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
                        $kd_paret = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
                        $kd_barang = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
                        $so_awal = $worksheet->getCellByColumnAndRow(4, $row)->getValue();
                        $masuk = $worksheet->getCellByColumnAndRow(5, $row)->getValue();
                        $keluar = $worksheet->getCellByColumnAndRow(6, $row)->getValue();
                        $so_akhir = $worksheet->getCellByColumnAndRow(7, $row)->getValue();
                        $lk_namagudang = $worksheet->getCellByColumnAndRow(8, $row)->getValue();
                        $lk_namacabang = $worksheet->getCellByColumnAndRow(9, $row)->getValue();
                        $cl_soawal = $worksheet->getCellByColumnAndRow(10, $row)->getValue();
                        $lk_namabarang = $worksheet->getCellByColumnAndRow(11, $row)->getValue();
                        $cl_masuk = $worksheet->getCellByColumnAndRow(12, $row)->getValue();
                        $cl_keluar = $worksheet->getCellByColumnAndRow(13, $row)->getValue();
                        $cl_soakhir = $worksheet->getCellByColumnAndRow(14, $row)->getValue();
                        $cl_satuan = $worksheet->getCellByColumnAndRow(15, $row)->getValue();
                        $spec = $worksheet->getCellByColumnAndRow(16, $row)->getValue();
                        $kd_merk = $worksheet->getCellByColumnAndRow(17, $row)->getValue();
                        $nama_barang = $worksheet->getCellByColumnAndRow(18, $row)->getValue();
                        $cl_nama_merk = $worksheet->getCellByColumnAndRow(19, $row)->getValue();
                        $kd_supplier = $worksheet->getCellByColumnAndRow(20, $row)->getValue();
                        $perusahaan = $worksheet->getCellByColumnAndRow(21, $row)->getValue();

                        $data[] = array(
                            'kode'          =>    $subbranch,
                            'kd_cabang'        =>    $kd_cabang,
                            'kd_gudang'       =>    $kd_gudang,
                            'kd_paret'        =>    $kd_paret,
                            'kd_barang'        =>    $kd_barang,
                            'so_awal'        =>    $so_awal,
                            'lk_namabarang'    =>    $lk_namabarang,
                            'cl_masuk'        =>    $cl_masuk,
                            'cl_keluar'        =>    $cl_keluar,
                            'cl_soakhir'    =>    $cl_soakhir,
                            'cl_satuan'        =>    $cl_satuan,
                            'spec'            =>    $spec,
                            'kd_merk'        =>    $kd_merk,
                            'nama_barang'    =>    $nama_barang,
                            'cl_nama_merk'    =>    $cl_nama_merk,
                            'kd_supplier'    =>    $kd_supplier,
                            'perusahaan'    =>    $perusahaan,
                            'masuk'            =>    $masuk,
                            'keluar'        =>    $keluar,
                            'so_akhir'        =>    $so_akhir,
                            'lk_namagudang'    =>    $lk_namagudang,
                            'lk_namacabang'    =>    $lk_namacabang,
                            'cl_soawal'        =>    $cl_soawal,
                            'filename'      =>    $filename,
                            'created_date'  =>  $tgl_created,
                            'id'            =>    $id,
                        );
                    }
                }

                $insert = $this->model_status->upload_stock($data, $subbranch);

                $data = [
                    'id'                    => $this->session->userdata('id'),
                    'url'                   => 'sales_omzet/omzet_dp_hasil/',
                    'get_label'             => $this->M_menu->get_label(),
                    'get_upload_stock'      => $this->model_status->get_upload_stock_palembang($tgl_created),
                    'kode'                  => $subbranch,
                    'tahun'                 => $tahun,
                    'bulan'                 => $bulan,
                    'title'                 => 'Upload Stock'
                ];

                $this->load->view('template_claim/top_header');
                $this->load->view('template_claim/header');
                $this->load->view('template_claim/sidebar', $data);
                $this->load->view('template_claim/top_content', $data);
                $this->load->view('upload_data/upload_stock_palembang_hasil', $data);
                $this->load->view('template_claim/bottom_content');
                $this->load->view('template_claim/footer');

            }elseif ($subbranch == 'ACHA1') {

                foreach ($object->getWorksheetIterator() as $worksheet) {
                    $highestRow = $worksheet->getHighestRow();
                    $highestColumn = $worksheet->getHighestColumn();
                    for ($row = 2; $row <= $highestRow; $row++) {
                        $kodeprod = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
                        $namaprod = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
                        $stock = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
                        $satuan = $worksheet->getCellByColumnAndRow(3, $row)->getValue();

                        $data[] = array(
                            'kode'          =>    $subbranch,
                            'kodeprod'      =>    $kodeprod,
                            'namaprod'      =>    $namaprod,
                            'stock'          =>   $stock,
                            'satuan'        =>    $satuan,
                            'filename'      =>    $filename,
                            'created_date'  =>    $tgl_created,
                            'id'            =>    $id,
                        );
                    }
                }

                $insert = $this->model_status->upload_stock($data, $subbranch);

                $data = [
                    'id'                    => $this->session->userdata('id'),
                    'url'                   => 'sales_omzet/omzet_dp_hasil/',
                    'get_label'             => $this->M_menu->get_label(),
                    'get_upload_stock'      => $this->model_status->get_upload_stock_aceh($tgl_created),
                    'kode'                  => $subbranch,
                    'tahun'                 => $tahun,
                    'bulan'                 => $bulan,
                    'title'                 => 'Upload Stock'
                ];

                $this->load->view('template_claim/top_header');
                $this->load->view('template_claim/header');
                $this->load->view('template_claim/sidebar', $data);
                $this->load->view('template_claim/top_content', $data);
                $this->load->view('upload_data/upload_stock_aceh_hasil', $data);
                $this->load->view('template_claim/bottom_content');
                $this->load->view('template_claim/footer');

            }elseif ($subbranch == 'BTM65') {

                foreach ($object->getWorksheetIterator() as $worksheet) {
                    $highestRow = $worksheet->getHighestRow();
                    $highestColumn = $worksheet->getHighestColumn();
                    for ($row = 2; $row <= $highestRow; $row++) {
                        $no = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
                        $kodeprod = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
                        $namaprod = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
                        $ukuran = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
                        $sisa = $worksheet->getCellByColumnAndRow(4, $row)->getValue();
                        $pcs = $worksheet->getCellByColumnAndRow(5, $row)->getValue();
                        $satuan_jual = $worksheet->getCellByColumnAndRow(6, $row)->getValue();

                        $data[] = array(
                            'no'            =>    $no,
                            'kodeprod'      =>    $kodeprod,
                            'nama_barang'      =>    $namaprod,
                            'ukuran'        =>   $ukuran,
                            'sisa'          =>    $sisa,
                            'pcs'           =>    $pcs,    
                            'satuan_jual'   =>    $satuan_jual,
                            'filename'      =>    $filename,
                            'created_date'  =>    $tgl_created,
                            'id'            =>    $id,
                        );
                    }
                }

                $insert = $this->model_status->upload_stock($data, $subbranch);

                $data = [
                    'id'                    => $this->session->userdata('id'),
                    'url'                   => 'sales_omzet/omzet_dp_hasil/',
                    'get_label'             => $this->M_menu->get_label(),
                    'get_upload_stock'      => $this->model_status->get_upload_stock_batam($tgl_created),
                    'kode'                  => $subbranch,
                    'tahun'                 => $tahun,
                    'bulan'                 => $bulan,
                    'title'                 => 'Upload Stock'
                ];

                $this->load->view('template_claim/top_header');
                $this->load->view('template_claim/header');
                $this->load->view('template_claim/sidebar', $data);
                $this->load->view('template_claim/top_content', $data);
                $this->load->view('upload_data/upload_stock_batam_hasil', $data);
                $this->load->view('template_claim/bottom_content');
                $this->load->view('template_claim/footer');
            }elseif ($subbranch == 'SJDT5') {

                foreach ($object->getWorksheetIterator() as $worksheet) {
                    $highestRow = $worksheet->getHighestRow();
                    $highestColumn = $worksheet->getHighestColumn();
                    for ($row = 2; $row <= $highestRow; $row++) {
                        $no = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
                        $kodeprod = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
                        $namaprod = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
                        $ukuran = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
                        $sisa = $worksheet->getCellByColumnAndRow(4, $row)->getValue();
                        $pcs = $worksheet->getCellByColumnAndRow(5, $row)->getValue();
                        $satuan_jual = $worksheet->getCellByColumnAndRow(6, $row)->getValue();

                        $data[] = array(
                            'no'            =>    $no,
                            'kodeprod'      =>    $kodeprod,
                            'nama_barang'      =>    $namaprod,
                            'ukuran'        =>   $ukuran,
                            'sisa'          =>    $sisa,
                            'pcs'           =>    $pcs,    
                            'satuan_jual'   =>    $satuan_jual,
                            'filename'      =>    $filename,
                            'created_date'  =>    $tgl_created,
                            'id'            =>    $id,
                        );
                    }
                }

                $insert = $this->model_status->upload_stock($data, $subbranch);

                $data = [
                    'id'                    => $this->session->userdata('id'),
                    'url'                   => 'sales_omzet/omzet_dp_hasil/',
                    'get_label'             => $this->M_menu->get_label(),
                    'get_upload_stock'      => $this->model_status->get_upload_stock_tanjung($tgl_created),
                    'kode'                  => $subbranch,
                    'tahun'                 => $tahun,
                    'bulan'                 => $bulan,
                    'title'                 => 'Upload Stock'
                ];

                $this->load->view('template_claim/top_header');
                $this->load->view('template_claim/header');
                $this->load->view('template_claim/sidebar', $data);
                $this->load->view('template_claim/top_content', $data);
                $this->load->view('upload_data/upload_stock_tanjung_hasil', $data);
                $this->load->view('template_claim/bottom_content');
                $this->load->view('template_claim/footer');
            }

            elseif ($subbranch == 'PKB51') {

                foreach ($object->getWorksheetIterator() as $worksheet) {
                    $highestRow = $worksheet->getHighestRow();
                    $highestColumn = $worksheet->getHighestColumn();
                    for ($row = 5; $row <= $highestRow; $row++) {
                        $kodebarang = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
                        $namabarang = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
                        $barcode = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
                        $gol = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
                        $merk = $worksheet->getCellByColumnAndRow(4, $row)->getValue();
                        $konversi = $worksheet->getCellByColumnAndRow(5, $row)->getValue();
                        $sisa = $worksheet->getCellByColumnAndRow(6, $row)->getValue();
                        $hargapokok = $worksheet->getCellByColumnAndRow(7, $row)->getValue();
                        $jumlah = $worksheet->getCellByColumnAndRow(8, $row)->getValue();
                        $catatan = $worksheet->getCellByColumnAndRow(9, $row)->getValue();
                        $kodesup = $worksheet->getCellByColumnAndRow(10, $row)->getValue();
                        $rasio = $worksheet->getCellByColumnAndRow(11, $row)->getValue();
                        $satuan = $worksheet->getCellByColumnAndRow(12, $row)->getValue();
                        $rak = $worksheet->getCellByColumnAndRow(13, $row)->getValue();
                        $adamutasi = $worksheet->getCellByColumnAndRow(14, $row)->getValue();

                        $data[] = array(
                            'kodebarang'    =>    $kodebarang,
                            'namabarang'    =>    $namabarang,
                            'barcode'       =>    $barcode,
                            'gol'           =>   $gol,
                            'merk'          =>    $merk,
                            'konversi'      =>    $konversi,
                            'sisa'          =>    $sisa,
                            'hargapokok'    =>    $hargapokok,
                            'jumlah'        =>    $jumlah,
                            'catatan'       =>    $catatan,
                            'kodesup'       =>    $kodesup,
                            'rasio'         =>    $rasio,
                            'satuan'        =>    $satuan,
                            'rak'           =>    $rak,
                            'adamutasi'     =>    $adamutasi,
                            'filename'      =>    $filename,
                            'created_date'  =>    $tgl_created,
                            'id'            =>    $id,
                        );
                    }
                }

                $insert = $this->model_status->upload_stock($data, $subbranch);

                $data = [
                    'id'                    => $this->session->userdata('id'),
                    'url'                   => 'sales_omzet/omzet_dp_hasil/',
                    'get_label'             => $this->M_menu->get_label(),
                    'get_upload_stock'      => $this->model_status->get_upload_stock_pekanbaru($tgl_created),
                    'kode'                  => $subbranch,
                    'tahun'                 => $tahun,
                    'bulan'                 => $bulan,
                    'title'                 => 'Upload Stock'
                ];

                $this->load->view('template_claim/top_header');
                $this->load->view('template_claim/header');
                $this->load->view('template_claim/sidebar', $data);
                $this->load->view('template_claim/top_content', $data);
                $this->load->view('upload_data/upload_stock_pekanbaru_hasil', $data);
                $this->load->view('template_claim/bottom_content');
                $this->load->view('template_claim/footer');
            }

        } else {
            $data['return'] = array('result' => 'failed', 'file' => '', 'error' => $this->upload->display_errors());
            $return = array('result' => 'failed', 'file' => '', 'error' => $this->upload->display_errors());
            var_dump($return);
        }
    }

    public function upload_stock_palembang()
    {
        $data = [
            'id'            => $this->session->userdata('id'),
            'url'           => 'status/upload_stock_palembang_hasil/',
            'get_label'     => $this->M_menu->get_label(),
            // 'query'         => $this->model_omzet->getSuppbyid(),
            'title'         => 'Upload Stock (* Khusus Palembang + Baturaja)',
            'get_subbranch' => $this->model_status->get_subbranch()
        ];                   
        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar',$data);
        $this->load->view('template_claim/top_content',$data);
        $this->load->view('upload_data/upload_stock_palembang',$data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
    }

    public function upload_stock_palembang_hasil()
    {
        date_default_timezone_set('Asia/Jakarta');
        $logged_in = $this->session->userdata('logged_in');
        if (!isset($logged_in) || $logged_in != TRUE) {
            redirect('login/', 'refresh');
        }

        $id = $this->session->userdata('id');
        $subbranch = $this->input->post('subbranch');
        $tahun = $this->input->post('tahun');
        $bulan = $this->input->post('bulan');

        echo "<pre>";
        echo "id : ".$id;
        echo "subbranch : ".$subbranch;
        echo "tahun : ".$tahun;
        echo "bulan : ".$bulan;
        echo "</pre>";

        if (!is_dir('./assets/uploads/stock/' . date('Ym') . '/')) {
            @mkdir('./assets/uploads/stock/' . date('Ym') . '/', 0777);
        }

        $tgl_created = date('Y-m-d H:i:s');
        $this->load->library('upload'); // Load librari upload        
        $config['upload_path'] = './assets/uploads/stock/' . date('Ym') . '';
        // $config['allowed_types'] = 'xls|xlsx';
        $config['allowed_types'] = '*';    
        $config['max_size']  = '2048';
        $config['overwrite'] = true;
        $this->upload->initialize($config);

        if ($this->upload->do_upload('file_cv')) 
        {
            $upload_data = $this->upload->data();
            $filename = $upload_data['orig_name'];
            $file_type = $upload_data['file_type'];
            //   echo "filename : ".$filename."<br>";
            //   echo "file_type : ".$file_type;
            $this->load->library('excel');
            $object = PHPExcel_IOFactory::load("assets/uploads/stock/" . date('Ym') . "/$filename");

            $jumlahSheet = $object->getSheetCount();
            // echo "jumlahsheet : ".$jumlahSheet."<br>";
            if ($jumlahSheet > 1) {
                // echo "jumlah_sheet : ".$jumlahSheet;
            }

            if ($subbranch == 'SBJS7') {

                foreach ($object->getWorksheetIterator() as $worksheet) {
                    $highestRow = $worksheet->getHighestRow();
                    $highestColumn = $worksheet->getHighestColumn();
                    for ($row = 2; $row <= $highestRow; $row++) {
                        $kd_cabang = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
                        $kd_gudang = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
                        $kd_paret = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
                        $kd_barang = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
                        $so_awal = $worksheet->getCellByColumnAndRow(4, $row)->getValue();
                        $masuk = $worksheet->getCellByColumnAndRow(5, $row)->getValue();
                        $keluar = $worksheet->getCellByColumnAndRow(6, $row)->getValue();
                        $so_akhir = $worksheet->getCellByColumnAndRow(7, $row)->getValue();
                        $lk_namagudang = $worksheet->getCellByColumnAndRow(8, $row)->getValue();
                        $lk_namacabang = $worksheet->getCellByColumnAndRow(9, $row)->getValue();
                        $cl_soawal = $worksheet->getCellByColumnAndRow(10, $row)->getValue();
                        $lk_namabarang = $worksheet->getCellByColumnAndRow(11, $row)->getValue();
                        $cl_masuk = $worksheet->getCellByColumnAndRow(12, $row)->getValue();
                        $cl_keluar = $worksheet->getCellByColumnAndRow(13, $row)->getValue();
                        $cl_soakhir = $worksheet->getCellByColumnAndRow(14, $row)->getValue();
                        $cl_satuan = $worksheet->getCellByColumnAndRow(15, $row)->getValue();
                        $spec = $worksheet->getCellByColumnAndRow(16, $row)->getValue();
                        $kd_merk = $worksheet->getCellByColumnAndRow(17, $row)->getValue();
                        $nama_barang = $worksheet->getCellByColumnAndRow(18, $row)->getValue();
                        $cl_nama_merk = $worksheet->getCellByColumnAndRow(19, $row)->getValue();
                        $kd_supplier = $worksheet->getCellByColumnAndRow(20, $row)->getValue();
                        $perusahaan = $worksheet->getCellByColumnAndRow(21, $row)->getValue();

                        $data[] = array(
                            'kode'          =>    $subbranch,
                            'kd_cabang'        =>    $kd_cabang,
                            'kd_gudang'       =>    $kd_gudang,
                            'kd_paret'        =>    $kd_paret,
                            'kd_barang'        =>    $kd_barang,
                            'so_awal'        =>    $so_awal,
                            'lk_namabarang'    =>    $lk_namabarang,
                            'cl_masuk'        =>    $cl_masuk,
                            'cl_keluar'        =>    $cl_keluar,
                            'cl_soakhir'    =>    $cl_soakhir,
                            'cl_satuan'        =>    $cl_satuan,
                            'spec'            =>    $spec,
                            'kd_merk'        =>    $kd_merk,
                            'nama_barang'    =>    $nama_barang,
                            'cl_nama_merk'    =>    $cl_nama_merk,
                            'kd_supplier'    =>    $kd_supplier,
                            'perusahaan'    =>    $perusahaan,
                            'masuk'            =>    $masuk,
                            'keluar'        =>    $keluar,
                            'so_akhir'        =>    $so_akhir,
                            'lk_namagudang'    =>    $lk_namagudang,
                            'lk_namacabang'    =>    $lk_namacabang,
                            'cl_soawal'        =>    $cl_soawal,
                            'filename'      =>    $filename,
                            'created_date'  =>  $tgl_created,
                            'id'            =>    $id,
                        );
                    }
                }
                $insert = $this->model_status->upload_stock($data,$subbranch);

                if ($this->upload->do_upload('file_ud')) 
                {
                    $upload_data = $this->upload->data();
                    $filename = $upload_data['orig_name'];
                    $file_type = $upload_data['file_type'];
                    // echo "filename : ".$filename."<br>";
                    // echo "file_type : ".$file_type;
                    $this->load->library('excel');
                    $object = PHPExcel_IOFactory::load("assets/uploads/stock/" . date('Ym') . "/$filename");

                    $jumlahSheet = $object->getSheetCount();
                    // echo "jumlahsheet : ".$jumlahSheet."<br>";
                    if ($jumlahSheet > 1) {
                        // echo "jumlah_sheet : ".$jumlahSheet;
                    }

                    foreach ($object->getWorksheetIterator() as $worksheet) {
                        $highestRow = $worksheet->getHighestRow();
                        $highestColumn = $worksheet->getHighestColumn();
                        for ($row = 2; $row <= $highestRow; $row++) {
                            $kd_cabang = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
                            $kd_gudang = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
                            $kd_paret = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
                            $kd_barang = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
                            $so_awal = $worksheet->getCellByColumnAndRow(4, $row)->getValue();
                            $masuk = $worksheet->getCellByColumnAndRow(5, $row)->getValue();
                            $keluar = $worksheet->getCellByColumnAndRow(6, $row)->getValue();
                            $so_akhir = $worksheet->getCellByColumnAndRow(7, $row)->getValue();
                            $lk_namagudang = $worksheet->getCellByColumnAndRow(8, $row)->getValue();
                            $lk_namacabang = $worksheet->getCellByColumnAndRow(9, $row)->getValue();
                            $cl_soawal = $worksheet->getCellByColumnAndRow(10, $row)->getValue();
                            $lk_namabarang = $worksheet->getCellByColumnAndRow(11, $row)->getValue();
                            $cl_masuk = $worksheet->getCellByColumnAndRow(12, $row)->getValue();
                            $cl_keluar = $worksheet->getCellByColumnAndRow(13, $row)->getValue();
                            $cl_soakhir = $worksheet->getCellByColumnAndRow(14, $row)->getValue();
                            $cl_satuan = $worksheet->getCellByColumnAndRow(15, $row)->getValue();
                            $spec = $worksheet->getCellByColumnAndRow(16, $row)->getValue();
                            $kd_merk = $worksheet->getCellByColumnAndRow(17, $row)->getValue();
                            $nama_barang = $worksheet->getCellByColumnAndRow(18, $row)->getValue();
                            $cl_nama_merk = $worksheet->getCellByColumnAndRow(19, $row)->getValue();
                            $kd_supplier = $worksheet->getCellByColumnAndRow(20, $row)->getValue();
                            $perusahaan = $worksheet->getCellByColumnAndRow(21, $row)->getValue();
    
                            $data_ud[] = array(
                                'kode'          =>    $subbranch,
                                'kd_cabang'        =>    $kd_cabang,
                                'kd_gudang'       =>    $kd_gudang,
                                'kd_paret'        =>    $kd_paret,
                                'kd_barang'        =>    $kd_barang,
                                'so_awal'        =>    $so_awal,
                                'lk_namabarang'    =>    $lk_namabarang,
                                'cl_masuk'        =>    $cl_masuk,
                                'cl_keluar'        =>    $cl_keluar,
                                'cl_soakhir'    =>    $cl_soakhir,
                                'cl_satuan'        =>    $cl_satuan,
                                'spec'            =>    $spec,
                                'kd_merk'        =>    $kd_merk,
                                'nama_barang'    =>    $nama_barang,
                                'cl_nama_merk'    =>    $cl_nama_merk,
                                'kd_supplier'    =>    $kd_supplier,
                                'perusahaan'    =>    $perusahaan,
                                'masuk'            =>    $masuk,
                                'keluar'        =>    $keluar,
                                'so_akhir'        =>    $so_akhir,
                                'lk_namagudang'    =>    $lk_namagudang,
                                'lk_namacabang'    =>    $lk_namacabang,
                                'cl_soawal'        =>    $cl_soawal,
                                'filename'      =>    $filename,
                                'created_date'  =>  $tgl_created,
                                'id'            =>    $id,
                            );
                        }
                    }

                    $insert = $this->model_status->upload_stock($data_ud,$subbranch);

                }


                $data = [
                    'id'                    => $this->session->userdata('id'),
                    'url'                   => 'sales_omzet/omzet_dp_hasil/',
                    'get_label'             => $this->M_menu->get_label(),
                    'get_upload_stock'      => $this->model_status->get_upload_stock_palembang($tgl_created),
                    'kode'                  => $subbranch,
                    'tahun'                 => $tahun,
                    'bulan'                 => $bulan,
                    'title'                 => 'Upload Stock'
                ];

                $this->load->view('template_claim/top_header');
                $this->load->view('template_claim/header');
                $this->load->view('template_claim/sidebar', $data);
                $this->load->view('template_claim/top_content', $data);
                $this->load->view('upload_data/upload_stock_palembang_hasil', $data);
                $this->load->view('template_claim/bottom_content');
                $this->load->view('template_claim/footer');
            }

        }else {
            $data['return'] = array('result' => 'failed', 'file' => '', 'error' => $this->upload->display_errors());
            $return = array('result' => 'failed', 'file' => '', 'error' => $this->upload->display_errors());
            var_dump($return);
        }
    }

    public function mapping_stock_palembang()
    {
        $kode = $this->uri->segment('3');
        $tahun = $this->uri->segment('4');
        $bulan = $this->uri->segment('5');
        $data = [
            'id'                    => $this->session->userdata('id'),
            'url'                   => 'status/proses_database_utama_palembang/',
            'get_label'             => $this->M_menu->get_label(),
            'mapping_stock'         => $this->model_status->mapping_stock_palembang($kode),
            'kode'                  => $kode,
            'tahun'                 => $tahun,
            'bulan'                 => $bulan,
            'title'                 => 'Upload Stock'
        ];

        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar', $data);
        $this->load->view('template_claim/top_content', $data);
        $this->load->view('upload_data/mapping_stock_palembang', $data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
    }

    public function export_mapping_stock_palembang()
    {
        $kode = $this->uri->segment('3');
        $id = $this->session->userdata('id');
        $sql = "
        select *
        from db_stock.t_mapping_stock_palembang a
        where a.kode = '$kode' and a.created_date = (select max(created_date) from db_stock.t_mapping_stock_palembang where id = $id)
                ";
        $quer = $this->db->query($sql);

        query_to_csv($quer, TRUE, 'Export Stock Hasil Mapping.csv');
    }

    public function export_mapping_stock_pekanbaru()
    {
        $id = $this->session->userdata('id');
        $sql = "
        select *
        from db_stock.t_mapping_stock_pekanbaru a
        where a.created_date = (select max(created_date) from db_stock.t_mapping_stock_pekanbaru where id = $id)
                ";
        $quer = $this->db->query($sql);

        query_to_csv($quer, TRUE, 'Export Stock Hasil Mapping.csv');
    }

    

    public function export_stock_palembang($kode, $tahun, $bulan)
    {
        if (strlen($bulan) == 1) {
			$bulan = '0'.$bulan;
		}else{
			$bulan = $bulan;
        }
        $nocab = substr($kode, 3, 2);
        $bulan_versi_st = substr($tahun, 2) . $bulan;
        
        $sql = "
        select  a.kodeprod, a.namaprod, 
                if(kode_gdg='PST',
                ((Saldoawal+masuk_pbk+masuk_supp+retur_sal+bPinjam+kvretur+kr_gudang+retur_depo)-
                (sales+pinjam+minta_depo+kvbeli+kr_min)-(rusak+sisih)),(Saldoawal+masuk_pbk+retur_sal+kvretur+bPinjam+tukar_msk+msk_gddepo) -
                (sales+Pinjam+kvbeli+retur_depo+tukar_klr+klr_gddepo)) as stock_akhir,
                a.nocab, a.bulan
        from data$tahun.st a
        where a.nocab ='$nocab' and bulan = $bulan_versi_st
        order by supp,kodeprod
        ";
        $quer = $this->db->query($sql);
        // echo "<pre>";
        // print_r($sql);
        // echo "</pre>";

        query_to_csv($quer, TRUE, 'Export Stock Palembang.csv');
    }


    public function mapping_stock()
    {
        $kode = $this->uri->segment('3');
        $tahun = $this->uri->segment('4');
        $bulan = $this->uri->segment('5');
        $data = [
            'id'                    => $this->session->userdata('id'),
            'url'                   => 'status/proses_database_utama/',
            'get_label'             => $this->M_menu->get_label(),
            'mapping_stock'         => $this->model_status->mapping_stock($kode),
            'kode'                  => $kode,
            'tahun'                 => $tahun,
            'bulan'                 => $bulan,
            'title'                 => 'Upload Stock'
        ];

        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar', $data);
        $this->load->view('template_claim/top_content', $data);
        $this->load->view('upload_data/mapping_stock', $data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
    }

    public function mapping_stock_baturaja()
    {
        $kode = $this->uri->segment('3');
        $tahun = $this->uri->segment('4');
        $bulan = $this->uri->segment('5');
        $data = [
            'id'                    => $this->session->userdata('id'),
            'url'                   => 'status/proses_database_utama_baturaja/',
            'get_label'             => $this->M_menu->get_label(),
            'mapping_stock'         => $this->model_status->mapping_stock_baturaja($kode),
            'kode'                  => $kode,
            'tahun'                 => $tahun,
            'bulan'                 => $bulan,
            'title'                 => 'Upload Stock'
        ];

        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar', $data);
        $this->load->view('template_claim/top_content', $data);
        $this->load->view('upload_data/mapping_stock_baturaja', $data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
    }

    public function mapping_stock_batam()
    {
        $kode = $this->uri->segment('3');
        $tahun = $this->uri->segment('4');
        $bulan = $this->uri->segment('5');
        $data = [
            'id'                    => $this->session->userdata('id'),
            'url'                   => 'status/proses_database_utama_batam/',
            'get_label'             => $this->M_menu->get_label(),
            'mapping_stock'         => $this->model_status->mapping_stock_batam($kode),
            'kode'                  => $kode,
            'tahun'                 => $tahun,
            'bulan'                 => $bulan,
            'title'                 => 'Upload Stock'
        ];

        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar', $data);
        $this->load->view('template_claim/top_content', $data);
        $this->load->view('upload_data/mapping_stock_batam', $data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
    }

    public function mapping_stock_tanjung()
    {
        $kode = $this->uri->segment('3');
        $tahun = $this->uri->segment('4');
        $bulan = $this->uri->segment('5');
        $data = [
            'id'                    => $this->session->userdata('id'),
            'url'                   => 'status/proses_database_utama_tanjung/',
            'get_label'             => $this->M_menu->get_label(),
            'mapping_stock'         => $this->model_status->mapping_stock_tanjung($kode),
            'kode'                  => $kode,
            'tahun'                 => $tahun,
            'bulan'                 => $bulan,
            'title'                 => 'Upload Stock'
        ];

        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar', $data);
        $this->load->view('template_claim/top_content', $data);
        $this->load->view('upload_data/mapping_stock_tanjung', $data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
    }

    public function export_mapping_stock()
    {
        $kode = $this->uri->segment('3');
        $id = $this->session->userdata('id');
        $sql = "
        select *
        from db_stock.t_mapping_stock a
        where a.kode = '$kode' and a.created_date = (select max(created_date) from db_stock.t_mapping_stock where id = $id)
                ";
        $quer = $this->db->query($sql);

        query_to_csv($quer, TRUE, 'Export Stock Hasil Mapping.csv');
    }

    public function export_mapping_stock_baturaja()
    {
        $kode = $this->uri->segment('3');
        $id = $this->session->userdata('id');
        $sql = "
        select *
        from db_stock.t_mapping_stock_baturaja a
        where a.kode = '$kode' and a.created_date = (select max(created_date) from db_stock.t_mapping_stock_baturaja where id = $id)
                ";
        $quer = $this->db->query($sql);

        query_to_csv($quer, TRUE, 'Export Stock Hasil Mapping.csv');
    }

    public function export_mapping_stock_batam()
    {
        $kode = $this->uri->segment('3');
        $id = $this->session->userdata('id');
        $sql = "
        select *
        from db_stock.t_mapping_stock_batam a
        where a.created_date = (select max(created_date) from db_stock.t_mapping_stock_batam where id = $id)
                ";
        $quer = $this->db->query($sql);

        query_to_csv($quer, TRUE, 'Export Stock Hasil Mapping.csv');
    }

    public function export_upload_stock()
    {
        $id = $this->session->userdata('id');
        $sql = "
                    select kode, branch_name as Branch, nama_comp as Sub_Branch, kodeprod, namaprod as Nama_produk, harga as Harga_DP, h_dp as Harga_PO
                    from mpm.t_verifikasi_harga_temp
                    where id = $id
                ";
        $quer = $this->db->query($sql);

        query_to_csv($quer, TRUE, 'Informasi Harga produk.csv');
    }

    public function export_upload_stock_aceh()
    {
        $kode = $this->uri->segment('3');
        $id = $this->session->userdata('id');
        $sql = "
        select *
        from db_stock.t_stock_aceh_original a
        where a.kode = '$kode' and a.created_date = (select max(created_date) from db_stock.t_stock_aceh_original where id = $id)
                ";
        $quer = $this->db->query($sql);

        query_to_csv($quer, TRUE, 'Export Stock Hasil upload.csv');
    }

    public function export_stock_aceh($kode, $tahun, $bulan)
    {
        if (strlen($bulan) == 1) {
			$bulan = '0'.$bulan;
		}else{
			$bulan = $bulan;
        }
        $nocab = substr($kode, 3, 2);
        $bulan_versi_st = substr($tahun, 2) . $bulan;
        
        $sql = "
        select  a.kodeprod, a.namaprod, a.saldoawal,
                a.nocab, a.bulan
        from data$tahun.st a
        where a.nocab ='$nocab' and bulan = $bulan_versi_st
        order by supp,kodeprod
        ";
        $quer = $this->db->query($sql);
        // echo "<pre>";
        // print_r($sql);
        // echo "</pre>";

        query_to_csv($quer, TRUE, 'Export Stock Aceh.csv');
    }
    
    public function export_mapping_stock_tanjung()
    {
        $kode = $this->uri->segment('3');
        $id = $this->session->userdata('id');
        $sql = "
        select *
        from db_stock.t_mapping_stock_tanjung a
        where a.created_date = (select max(created_date) from db_stock.t_mapping_stock_tanjung where id = $id)
                ";
        $quer = $this->db->query($sql);

        query_to_csv($quer, TRUE, 'Export Stock Hasil Mapping.csv');
    }

    public function export_stock_batam($kode, $tahun, $bulan)
    {
        if (strlen($bulan) == 1) {
			$bulan = '0'.$bulan;
		}else{
			$bulan = $bulan;
        }
        $nocab = substr($kode, 3, 2);
        $bulan_versi_st = substr($tahun, 2) . $bulan;
        
        $sql = "
        select  a.kodeprod, a.namaprod, a.saldoawal,
                a.nocab, a.bulan
        from data$tahun.st a
        where a.nocab ='$nocab' and bulan = $bulan_versi_st
        order by supp,kodeprod
        ";
        $quer = $this->db->query($sql);
        // echo "<pre>";
        // print_r($sql);
        // echo "</pre>";

        query_to_csv($quer, TRUE, 'Export Stock Batam.csv');
    }

    public function export_stock_tanjung($kode, $tahun, $bulan)
    {
        if (strlen($bulan) == 1) {
			$bulan = '0'.$bulan;
		}else{
			$bulan = $bulan;
        }
        $nocab = substr($kode, 3, 2);
        $bulan_versi_st = substr($tahun, 2) . $bulan;
        
        $sql = "
        select  a.kodeprod, a.namaprod, a.saldoawal,
                a.nocab, a.bulan
        from data$tahun.st a
        where a.nocab ='$nocab' and bulan = $bulan_versi_st
        order by supp,kodeprod
        ";
        $quer = $this->db->query($sql);
        // echo "<pre>";
        // print_r($sql);
        // echo "</pre>";

        query_to_csv($quer, TRUE, 'Export Stock Tanjung.csv');
    }

    public function export_upload_stock_bangka()
    {
        $kode = $this->uri->segment('3');
        $id = $this->session->userdata('id');
        $sql = "
        select *
        from db_stock.t_stock_bangka_original a
        where a.kode = '$kode' and a.created_date = (select max(created_date) from db_stock.t_stock_bangka_original where id = $id)
                ";
        $quer = $this->db->query($sql);

        query_to_csv($quer, TRUE, 'Export Stock Hasil upload.csv');
    }

    public function export_stock_bangka($kode, $tahun, $bulan)
    {
        if (strlen($bulan) == 1) {
			$bulan = '0'.$bulan;
		}else{
			$bulan = $bulan;
        }
        $nocab = substr($kode, 3, 2);
        $bulan_versi_st = substr($tahun, 2) . $bulan;
        
        $sql = "
        select  a.kodeprod, a.namaprod, a.saldoawal,
                a.nocab, a.bulan
        from data$tahun.st a
        where a.nocab ='$nocab' and bulan = $bulan_versi_st
        order by supp,kodeprod
        ";
        $quer = $this->db->query($sql);
        // echo "<pre>";
        // print_r($sql);
        // echo "</pre>";

        query_to_csv($quer, TRUE, 'Export Stock Bangka.csv');
    }

    public function stock_rilis($kode, $tahun, $bulan)
    {
        $data = [
            'id'                    => $this->session->userdata('id'),
            'url'                   => 'status/proses_database_utama/',
            'get_label'             => $this->M_menu->get_label(),
            'mapping_stock'         => $this->model_status->stock_rilis($kode, $tahun, $bulan),
            'kode'                  => $kode,
            'tahun'                 => $tahun,
            'bulan'                 => $bulan,
            'title'                 => 'Stock Rilis'
        ];

        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar', $data);
        $this->load->view('template_claim/top_content', $data);
        $this->load->view('upload_data/stock_rilis', $data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
    }

    public function stock_rilis_baturaja($kode, $tahun, $bulan)
    {
        $data = [
            'id'                    => $this->session->userdata('id'),
            'url'                   => 'status/proses_database_utama/',
            'get_label'             => $this->M_menu->get_label(),
            'mapping_stock'         => $this->model_status->stock_rilis_baturaja($kode, $tahun, $bulan),
            'kode'                  => $kode,
            'tahun'                 => $tahun,
            'bulan'                 => $bulan,
            'title'                 => 'Stock Rilis'
        ];

        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar', $data);
        $this->load->view('template_claim/top_content', $data);
        $this->load->view('upload_data/stock_rilis_baturaja', $data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
    }

    public function stock_rilis_palembang($kode, $tahun, $bulan)
    {
        $data = [
            'id'                    => $this->session->userdata('id'),
            'url'                   => 'status/proses_database_utama/',
            'get_label'             => $this->M_menu->get_label(),
            'mapping_stock'         => $this->model_status->stock_rilis_palembang($kode, $tahun, $bulan),
            'kode'                  => $kode,
            'tahun'                 => $tahun,
            'bulan'                 => $bulan,
            'title'                 => 'Stock Rilis'
        ];

        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar', $data);
        $this->load->view('template_claim/top_content', $data);
        $this->load->view('upload_data/stock_rilis_palembang', $data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
    }

    public function stock_rilis_aceh($kode, $tahun, $bulan)
    {
        $data = [
            'id'                    => $this->session->userdata('id'),
            'url'                   => 'status/proses_database_utama/',
            'get_label'             => $this->M_menu->get_label(),
            'mapping_stock'         => $this->model_status->stock_rilis_aceh($kode, $tahun, $bulan),
            'kode'                  => $kode,
            'tahun'                 => $tahun,
            'bulan'                 => $bulan,
            'title'                 => 'Stock Rilis'
        ];

        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar', $data);
        $this->load->view('template_claim/top_content', $data);
        $this->load->view('upload_data/stock_rilis_aceh', $data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
    }
     
    public function stock_rilis_batam($kode, $tahun, $bulan)
    {
        $data = [
            'id'                    => $this->session->userdata('id'),
            'url'                   => 'status/proses_database_utama/',
            'get_label'             => $this->M_menu->get_label(),
            'mapping_stock'         => $this->model_status->stock_rilis_batam($kode, $tahun, $bulan),
            'kode'                  => $kode,
            'tahun'                 => $tahun,
            'bulan'                 => $bulan,
            'title'                 => 'Stock Rilis'
        ];

        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar', $data);
        $this->load->view('template_claim/top_content', $data);
        $this->load->view('upload_data/stock_rilis_batam', $data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
    }

    public function stock_rilis_tanjung($kode, $tahun, $bulan)
    {
        $data = [
            'id'                    => $this->session->userdata('id'),
            'url'                   => 'status/proses_database_utama/',
            'get_label'             => $this->M_menu->get_label(),
            'mapping_stock'         => $this->model_status->stock_rilis_tanjung($kode, $tahun, $bulan),
            'kode'                  => $kode,
            'tahun'                 => $tahun,
            'bulan'                 => $bulan,
            'title'                 => 'Stock Rilis'
        ];

        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar', $data);
        $this->load->view('template_claim/top_content', $data);
        $this->load->view('upload_data/stock_rilis_tanjung', $data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
    }

    public function stock_rilis_pekanbaru($kode, $tahun, $bulan)
    {
        $data = [
            'id'                    => $this->session->userdata('id'),
            'url'                   => 'status/proses_database_utama/',
            'get_label'             => $this->M_menu->get_label(),
            'mapping_stock'         => $this->model_status->stock_rilis_pekanbaru($kode, $tahun, $bulan),
            'kode'                  => $kode,
            'tahun'                 => $tahun,
            'bulan'                 => $bulan,
            'title'                 => 'Stock Rilis'
        ];

        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar', $data);
        $this->load->view('template_claim/top_content', $data);
        $this->load->view('upload_data/stock_rilis_pekanbaru', $data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
    }

    public function upload_stock_padang()
    {
        $data = [
            'id'            => $this->session->userdata('id'),
            'url'           => 'status/upload_stock_padang_hasil/',
            'get_label'     => $this->M_menu->get_label(),
            // 'query'         => $this->model_omzet->getSuppbyid(),
            'title'         => 'Upload Stock (* Khusus Padang dan Bukit Tinggi)',
            'get_subbranch' => $this->model_status->get_subbranch()
        ];                   
        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar',$data);
        $this->load->view('template_claim/top_content',$data);
        $this->load->view('upload_data/upload_stock_padang',$data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
    }

    public function upload_stock_padang_hasil()
    {
        date_default_timezone_set('Asia/Jakarta');
        $logged_in = $this->session->userdata('logged_in');
        if (!isset($logged_in) || $logged_in != TRUE) {
            redirect('login/', 'refresh');
        }

        $id = $this->session->userdata('id');
        $subbranch = $this->input->post('subbranch');
        $tahun = $this->input->post('tahun');
        $bulan = $this->input->post('bulan');

        // echo "<pre>";
        // echo "id : ".$id;
        // echo "subbranch : ".$subbranch;
        // echo "tahun : ".$tahun;
        // echo "bulan : ".$bulan;
        // echo "</pre>";

        if (!is_dir('./assets/uploads/stock/' . date('Ym') . '/')) {
            @mkdir('./assets/uploads/stock/' . date('Ym') . '/', 0777);
        }

        $tgl_created = date('Y-m-d H:i:s');
        $this->load->library('upload'); // Load librari upload        
        $config['upload_path'] = './assets/uploads/stock/' . date('Ym') . '';
        // $config['allowed_types'] = 'xls|xlsx';
        $config['allowed_types'] = '*';    
        $config['max_size']  = '2048';
        $config['overwrite'] = true;
        $this->upload->initialize($config);

        if ($this->upload->do_upload('file_padang')) 
        {
            $upload_data = $this->upload->data();
            $filename = $upload_data['orig_name'];
            $file_type = $upload_data['file_type'];
            //   echo "filename : ".$filename."<br>";
            //   echo "file_type : ".$file_type;
            $this->load->library('excel');
            $object = PHPExcel_IOFactory::load("assets/uploads/stock/" . date('Ym') . "/$filename");

            $jumlahSheet = $object->getSheetCount();
            // echo "jumlahsheet : ".$jumlahSheet."<br>";
            if ($jumlahSheet > 1) {
                // echo "jumlah_sheet : ".$jumlahSheet;
            }

            if ($subbranch == 'PDG33') {

                foreach ($object->getWorksheetIterator() as $worksheet) {
                    $highestRow = $worksheet->getHighestRow();
                    $highestColumn = $worksheet->getHighestColumn();
                    for ($row = 2; $row <= $highestRow; $row++) {
                        $kodeprod_original = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
                        $nama = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
                        $isi = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
                        $harga_beli = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
                        $m_ctn = $worksheet->getCellByColumnAndRow(4, $row)->getValue();
                        $m_unt = $worksheet->getCellByColumnAndRow(5, $row)->getValue();
                        $rp_mbl = $worksheet->getCellByColumnAndRow(6, $row)->getValue();
                        $g_ctn = $worksheet->getCellByColumnAndRow(7, $row)->getValue();
                        $g_unt = $worksheet->getCellByColumnAndRow(8, $row)->getValue();
                        $rp_gdg = $worksheet->getCellByColumnAndRow(9, $row)->getValue();
                        $t_ctn = $worksheet->getCellByColumnAndRow(10, $row)->getValue();
                        $t_unt = $worksheet->getCellByColumnAndRow(11, $row)->getValue();
                        $rp_stock = $worksheet->getCellByColumnAndRow(12, $row)->getValue();

                        $data[] = array(
                            'kode'                  => $subbranch,
                            'kodeprod_original'     => $kodeprod_original,
                            'nama'                  => $nama,
                            'isi'                   => $isi,
                            'harga_beli'            => $harga_beli,
                            'm_ctn'                 => $m_ctn,
                            'm_unt'                 => $m_unt,
                            'rp_mbl'                => $rp_mbl,
                            'g_ctn'                 => $g_ctn,
                            'g_unt'                 => $g_unt,
                            'rp_gdg'                => $rp_gdg,
                            't_ctn'                 => $t_ctn,
                            't_unt'                 => $t_unt,
                            't_unt'                 => $t_unt,
                            'rp_stock'              => $rp_stock,
                            'filename'              => $filename,
                            'created_date'          => $tgl_created,
                            'id'                    => $id,
                        );
                    }
                }
                $insert = $this->model_status->upload_stock($data,$subbranch);

                if ($this->upload->do_upload('file_bukit')) 
                {
                    $upload_data = $this->upload->data();
                    $filename = $upload_data['orig_name'];
                    $file_type = $upload_data['file_type'];
                    // echo "filename : ".$filename."<br>";
                    // echo "file_type : ".$file_type;
                    $this->load->library('excel');
                    $object = PHPExcel_IOFactory::load("assets/uploads/stock/" . date('Ym') . "/$filename");

                    $jumlahSheet = $object->getSheetCount();
                    // echo "jumlahsheet : ".$jumlahSheet."<br>";
                    if ($jumlahSheet > 1) {
                        // echo "jumlah_sheet : ".$jumlahSheet;
                    }

                    foreach ($object->getWorksheetIterator() as $worksheet) {
                        $highestRow = $worksheet->getHighestRow();
                        $highestColumn = $worksheet->getHighestColumn();
                        for ($row = 2; $row <= $highestRow; $row++) {
                            $kodeprod_original = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
                            $nama = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
                            $isi = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
                            $harga_beli = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
                            $m_ctn = $worksheet->getCellByColumnAndRow(4, $row)->getValue();
                            $m_unt = $worksheet->getCellByColumnAndRow(5, $row)->getValue();
                            $rp_mbl = $worksheet->getCellByColumnAndRow(6, $row)->getValue();
                            $g_ctn = $worksheet->getCellByColumnAndRow(7, $row)->getValue();
                            $g_unt = $worksheet->getCellByColumnAndRow(8, $row)->getValue();
                            $rp_gdg = $worksheet->getCellByColumnAndRow(9, $row)->getValue();
                            $t_ctn = $worksheet->getCellByColumnAndRow(10, $row)->getValue();
                            $t_unt = $worksheet->getCellByColumnAndRow(11, $row)->getValue();
                            $rp_stock = $worksheet->getCellByColumnAndRow(12, $row)->getValue();
    
                            $data_ud[] = array(
                                'kode'                  => $subbranch,
                                'kodeprod_original'     => $kodeprod_original,
                                'nama'                  => $nama,
                                'isi'                   => $isi,
                                'harga_beli'            => $harga_beli,
                                'm_ctn'                 => $m_ctn,
                                'm_unt'                 => $m_unt,
                                'rp_mbl'                => $rp_mbl,
                                'g_ctn'                 => $g_ctn,
                                'g_unt'                 => $g_unt,
                                'rp_gdg'                => $rp_gdg,
                                't_ctn'                 => $t_ctn,
                                't_unt'                 => $t_unt,
                                't_unt'                 => $t_unt,
                                'rp_stock'              => $rp_stock,
                                'filename'              => $filename,
                                'created_date'          => $tgl_created,
                                'id'                    => $id,
                            );
                        }
                    }

                    $insert = $this->model_status->upload_stock($data_ud,$subbranch);
                }

                $data = [
                    'id'                    => $this->session->userdata('id'),
                    'url'                   => 'sales_omzet/omzet_dp_hasil/',
                    'get_label'             => $this->M_menu->get_label(),
                    'get_upload_stock'      => $this->model_status->get_upload_stock_padang($tgl_created),
                    'kode'                  => $subbranch,
                    'tahun'                 => $tahun,
                    'bulan'                 => $bulan,
                    'title'                 => 'Upload Stock'
                ];

                $this->load->view('template_claim/top_header');
                $this->load->view('template_claim/header');
                $this->load->view('template_claim/sidebar', $data);
                $this->load->view('template_claim/top_content', $data);
                $this->load->view('upload_data/upload_stock_padang_hasil', $data);
                $this->load->view('template_claim/bottom_content');
                $this->load->view('template_claim/footer');
            }
        }
        
        else {
            $data['return'] = array('result' => 'failed', 'file' => '', 'error' => $this->upload->display_errors());
            $return = array('result' => 'failed', 'file' => '', 'error' => $this->upload->display_errors());
            var_dump($return);
        }
    }

    public function mapping_stock_padang()
    {
        $kode = $this->uri->segment('3');
        $tahun = $this->uri->segment('4');
        $bulan = $this->uri->segment('5');
        $data = [
            'id'                    => $this->session->userdata('id'),
            'url'                   => 'status/proses_database_utama_padang/',
            'get_label'             => $this->M_menu->get_label(),
            'mapping_stock'         => $this->model_status->mapping_stock_padang($kode),
            'kode'                  => $kode,
            'tahun'                 => $tahun,
            'bulan'                 => $bulan,
            'title'                 => 'Upload Stock'
        ];

        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar', $data);
        $this->load->view('template_claim/top_content', $data);
        $this->load->view('upload_data/mapping_stock_padang', $data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
    }        
    
    public function mapping_stock_pekanbaru()
    {
        $kode = $this->uri->segment('3');
        $tahun = $this->uri->segment('4');
        $bulan = $this->uri->segment('5');
        $data = [
            'id'                    => $this->session->userdata('id'),
            'url'                   => 'status/proses_database_utama_pekanbaru/',
            'get_label'             => $this->M_menu->get_label(),
            'mapping_stock'         => $this->model_status->mapping_stock_pekanbaru($kode),
            'kode'                  => $kode,
            'tahun'                 => $tahun,
            'bulan'                 => $bulan,
            'title'                 => 'Upload Stock'
        ];

        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar', $data);
        $this->load->view('template_claim/top_content', $data);
        $this->load->view('upload_data/mapping_stock_pekanbaru', $data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
    }        
	
	public function export_stock_padang($kode, $tahun, $bulan)
    {
        if (strlen($bulan) == 1) {
			$bulan = '0'.$bulan;
		}else{
			$bulan = $bulan;
        }
        $nocab = substr($kode, 3, 2);
        $bulan_versi_st = substr($tahun, 2) . $bulan;
        
        $sql = "
        select  a.kodeprod, a.namaprod, 
                if(kode_gdg='PST',
                ((Saldoawal+masuk_pbk+masuk_supp+retur_sal+bPinjam+kvretur+kr_gudang+retur_depo)-
                (sales+pinjam+minta_depo+kvbeli+kr_min)-(rusak+sisih)),(Saldoawal+masuk_pbk+retur_sal+kvretur+bPinjam+tukar_msk+msk_gddepo) -
                (sales+Pinjam+kvbeli+retur_depo+tukar_klr+klr_gddepo)) as stock_akhir,
                a.nocab, a.bulan
        from data$tahun.st a
        where a.nocab ='$nocab' and bulan = $bulan_versi_st
        order by supp,kodeprod
        ";
        $quer = $this->db->query($sql);
        // echo "<pre>";
        // print_r($sql);
        // echo "</pre>";

        query_to_csv($quer, TRUE, 'Export Stock Padang.csv');
    }

    public function export_stock_pekanbaru($kode, $tahun, $bulan)
    {
        if (strlen($bulan) == 1) {
			$bulan = '0'.$bulan;
		}else{
			$bulan = $bulan;
        }
        $nocab = substr($kode, 3, 2);
        $bulan_versi_st = substr($tahun, 2) . $bulan;
        
        $sql = "
        select  a.kodeprod, a.namaprod, 
                if(kode_gdg='PST',
                ((Saldoawal+masuk_pbk+masuk_supp+retur_sal+bPinjam+kvretur+kr_gudang+retur_depo)-
                (sales+pinjam+minta_depo+kvbeli+kr_min)-(rusak+sisih)),(Saldoawal+masuk_pbk+retur_sal+kvretur+bPinjam+tukar_msk+msk_gddepo) -
                (sales+Pinjam+kvbeli+retur_depo+tukar_klr+klr_gddepo)) as stock_akhir,
                a.nocab, a.bulan
        from data$tahun.st a
        where a.nocab ='$nocab' and bulan = $bulan_versi_st
        order by supp,kodeprod
        ";
        $quer = $this->db->query($sql);
        // echo "<pre>";
        // print_r($sql);
        // echo "</pre>";

        query_to_csv($quer, TRUE, 'Export Stock Pekanbaru.csv');
    }
	
	public function export_mapping_stock_padang()
    {
        $kode = $this->uri->segment('3');
        $id = $this->session->userdata('id');
        $sql = "
        select *
        from db_stock.t_mapping_stock_padang a
        where a.kode = '$kode' and a.created_date = (select max(created_date) from db_stock.t_mapping_stock_padang where id = $id)
                ";
        $quer = $this->db->query($sql);

        query_to_csv($quer, TRUE, 'Export Stock Hasil Mapping.csv');
    }
	
	public function stock_rilis_padang($kode, $tahun, $bulan)
    {
        $data = [
            'id'                    => $this->session->userdata('id'),
            'url'                   => 'status/proses_database_utama/',
            'get_label'             => $this->M_menu->get_label(),
            'mapping_stock'         => $this->model_status->stock_rilis_padang($kode, $tahun, $bulan),
            'kode'                  => $kode,
            'tahun'                 => $tahun,
            'bulan'                 => $bulan,
            'title'                 => 'Stock Rilis'
        ];

        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar', $data);
        $this->load->view('template_claim/top_content', $data);
        $this->load->view('upload_data/stock_rilis_padang', $data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
    }
    
    public function stock_rilis_bangka($kode, $tahun, $bulan)
    {
        $data = [
            'id'                    => $this->session->userdata('id'),
            'url'                   => 'status/proses_database_utama/',
            'get_label'             => $this->M_menu->get_label(),
            'mapping_stock'         => $this->model_status->stock_rilis_bangka($kode, $tahun, $bulan),
            'kode'                  => $kode,
            'tahun'                 => $tahun,
            'bulan'                 => $bulan,
            'title'                 => 'Stock Rilis'
        ];

        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar', $data);
        $this->load->view('template_claim/top_content', $data);
        $this->load->view('upload_data/stock_rilis_bangka', $data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
    }
                                       
}