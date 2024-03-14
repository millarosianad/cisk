<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Import extends MY_Controller
{
    var $nocab;
    var $options;
    var $image_properties_pdf = array(
          'src' => 'assets/css/images/pdf.png',
          'alt' => 'Printing Document',
          'class' => 'post_images',
          'width' => '50',
          'height' => '50',
          'title' => 'Printer',
          'border' => 0,
    );
    var $image_properties_excel = array(
          'src' => 'assets/css/images/excel.png',
          'alt' => 'Printing Document',
          'class' => 'post_images',
          'width' => '50',
          'height' => '50',
          'title' => 'Printer',
          'border' => 0,
    );
    var $querymenu;
    var $attr = array(
              'width'      => '800',
              'height'     => '600',
              'scrollbars' => 'yes',
              'status'     => 'yes',
              'resizable'  => 'yes',
              'screenx'   =>  '\'+((parseInt(screen.width) - 800)/2)+\'',
              'screeny'   =>  '\'+((parseInt(screen.height) - 600)/2)+\'',
            );

    function import()
    {
        set_time_limit(0);
        $this->load->library(array('table','template','Excel_generator', 'form_validation','email'));
        $this->load->helper('url');
        $this->load->helper('csv');
        $this->load->model('model_import');
        $this->load->database();
        $this->querymenu='select  a.id,
                        a.menuview,
                        a.target,
                        a.groupname 
                from    mpm.menu a inner join mpm.menudetail b 
                            on a.id=b.menuid 
                where   b.userid='.$this->session->userdata('id').' and 
                        active=1 
                order by a.groupname,menuview ';
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
        $this->template->set_title('MPM SQUARE');
        $this->template->add_js('modules/skeleton.js');
        $this->template->add_css('modules/skeleton.css');
        $this->template->load_view($view, $data);
    }

    public function import_dp(){
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
        $data['page_content'] = 'import/import_dp';
        $data['url'] = 'import/import_dp_proses/';
        $data['page_title'] = 'Import excel (.xls) DP';
        $data['menu']=$this->db->query($this->querymenu);
        $this->template($data['page_content'],$data);
    }

    public function import_gagal($data = ''){
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
        // echo $data;
        $data['pesan'] = "format file salah";
        $data['page_content'] = 'import/import_gagal';
        $data['url'] = 'import/import_dp_proses/';
        $data['page_title'] = 'Import Retur';
        $data['menu']=$this->db->query($this->querymenu);
        $this->template($data['page_content'],$data);
    }

    public function import_dp_proses()
    {
        $id = $this->session->userdata('id');
        date_default_timezone_set('Asia/Jakarta');        
        // $tgl_created='"'.date('Y-m-d H:i:s').'"';
        $tgl_created=date('Y-m-d H:i:s');
        $this->load->library('upload'); // Load librari upload        
        $config['upload_path'] = './assets/uploads/import_excel/';    
        $config['allowed_types'] = 'xls|xlsx';    
        $config['max_size']  = '2048';    
        $config['overwrite'] = true;    
        $this->upload->initialize($config); 
    
        // Load konfigurasi uploadnya    
        if($this->upload->do_upload('file'))
        { 
          $upload_data = $this->upload->data();
          $filename = $upload_data['orig_name'];
          $this->load->library('excel');
          $object = PHPExcel_IOFactory::load("assets/uploads/import_excel/$filename");

            $jumlahSheet = $object->getSheetCount();
            // echo "jumlahsheet : ".$jumlahSheet."<br>";
            if ($jumlahSheet > 1) {
                redirect('import/import_dp');
            }
            // for ($i=1; $i<= $jumlahSheet ; $i++) { 
            //     echo "i : ".$i."<br>";
            //     // $test = $object->removeSheetByIndex($i);
            // }

            foreach($object->getWorksheetIterator() as $worksheet)
            {   
                
                // if ($jumlahSheet > 0) {
                //     $test = $object->removeSheetByIndex(1);
                // }                
                
                $highestRow = $worksheet->getHighestRow();
                $highestColumn = $worksheet->getHighestColumn();
                for($row=2; $row<=$highestRow; $row++)
                {
                    $no_transaksi = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
                    $tanggal = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
                    $tanggal_convert = PHPExcel_Shared_Date::ExcelToPHP($tanggal);
                    $tanggal_final =date('Y-m-d', $tanggal_convert);
                    $kodesalesman = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
                    $salesman = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
                    $kode_outlet = $worksheet->getCellByColumnAndRow(4, $row)->getValue();
                    $nama_outlet = $worksheet->getCellByColumnAndRow(5, $row)->getValue();
                    $tipe_outlet = $worksheet->getCellByColumnAndRow(6, $row)->getValue();
                    $alamat_outlet = $worksheet->getCellByColumnAndRow(7, $row)->getValue();
                    $kode_kota = $worksheet->getCellByColumnAndRow(8, $row)->getValue();
                    $kota = $worksheet->getCellByColumnAndRow(9, $row)->getValue();
                    $kota_kecamatan = $worksheet->getCellByColumnAndRow(10, $row)->getValue();
                    $kecamatan = $worksheet->getCellByColumnAndRow(11, $row)->getValue();
                    $class_outlet = $worksheet->getCellByColumnAndRow(12, $row)->getValue();
                    $kodeprod = $worksheet->getCellByColumnAndRow(13, $row)->getValue();
                    $supplier = $worksheet->getCellByColumnAndRow(14, $row)->getValue();
                    $namaprod = $worksheet->getCellByColumnAndRow(15, $row)->getValue();
                    $qty = $worksheet->getCellByColumnAndRow(16, $row)->getValue();
                    $satuan = $worksheet->getCellByColumnAndRow(17, $row)->getValue();
                    $harga = $worksheet->getCellByColumnAndRow(18, $row)->getValue();
                    $potongan = $worksheet->getCellByColumnAndRow(19, $row)->getValue();
                    $total_harga = $worksheet->getCellByColumnAndRow(20, $row)->getValue();
                    // echo "no_transaksi : ".$no_transaksi."<br>";
                    // echo "tanggal_final : ".$tanggal_final."<br>";
                    // echo "kodesalesman : ".$kodesalesman."<br>";
                    // echo "salesman : ".$salesman."<br>";
                    // echo "kode_outlet : ".$kode_outlet."<br>";
                    // echo "nama_outlet : ".$nama_outlet."<br>";
                    // echo "tipe_outlet : ".$tipe_outlet."<br>";
                    // echo "alamat_outlet : ".$alamat_outlet."<br>";
                    // echo "kode_kota : ".$kode_kota."<br>";
                    // echo "kota : ".$kota."<br>";
                    // echo "kota_kecamatan : ".$kota_kecamatan."<br>";
                    // echo "kecamatan : ".$kecamatan."<br>";
                    // echo "class_outlet : ".$class_outlet."<br>";
                    // echo "kodeprod : ".$kodeprod."<br>";
                    // echo "supplier : ".$supplier."<br>";
                    // echo "namaprod : ".$namaprod."<br>";
                    // echo "qty : ".$qty."<br>";
                    // echo "satuan : ".$satuan."<br>";
                    // echo "harga : ".$harga."<br>";
                    // echo "potongan : ".$potongan."<br>";
                    // echo "total_harga : ".$total_harga."<br>";
                    // echo "created_date : ".$tgl_created."<br>";

                    $data[] = array(
                        'no_transaksi'		    =>	$no_transaksi,
                        'tanggal'   		    =>	$tanggal_final,
                        'kodesalesman'	        =>	$kodesalesman,
                        'salesman'	            =>	$salesman,
                        'kode_outlet'	        =>	$kode_outlet,
                        'nama_outlet'		    =>	$nama_outlet,
                        'tipe_outlet'	        =>	$tipe_outlet,
                        'alamat_outlet'	        =>	$alamat_outlet,
                        'kode_kota'	            =>	$kode_kota,
                        'kota'	                =>	$kota,
                        'kota_kecamatan'		=>	$kota_kecamatan,
                        'kecamatan'		        =>	$kecamatan,
                        'class_outlet'		    =>	$class_outlet,
                        'kodeprod'		        =>	$kodeprod,
                        'supplier'		        =>	$supplier,
                        'namaprod'		        =>	$namaprod,
                        'qty'		            =>	$qty,
                        'satuan'		        =>	$satuan,
                        'harga'		            =>	$harga,
                        'potongan'		        =>	$potongan,
                        'total_harga'		    =>	$total_harga,
                        'created_date'	        =>	$tgl_created,
                        'created_by'	        =>	$id,
                        'filename'  	        =>	$filename,
                    );
                }
            }
            $insert = $this->model_import->insert($data);
        }else{  
            $data['return'] = array('result' => 'failed', 'file' => '', 'error' => $this->upload->display_errors());      
            redirect('import/import_gagal',$data);
            // var_dump($return);
            // foreach($return as $x){
            //     $error = $x['error'];
            // }
            // echo "error : ".$error;
            // $data['page_content'] = 'import/import_dp';
            // $data['url'] = 'import/import_dp_proses/';
            // // $data['query'] = $this->model_import->get_import();
            // $data['page_title'] = 'Import excel (.xls) DP';
            // $data['menu']=$this->db->query($this->querymenu);
            // $this->template($data['page_content'],$data);

        }

        $data['page_content'] = 'import/import_dp_proses';
        $data['url'] = 'import/import_dp_proses/';
        $data['query'] = $this->model_import->get_import();
        $data['page_title'] = 'Review Import';
        $data['menu']=$this->db->query($this->querymenu);
        $this->template($data['page_content'],$data);


    }

    public function konversi(){

        $data['url'] = 'import/konversi/';
        $transfer = $this->model_import->konversi();
        foreach($transfer as $a){
            $data['omzet'] = $a->omzet;
            $data['lastupload'] = $a->lastupload;
            $data['tahun'] = $a->tahun;
            $data['bulan'] = $a->bulan;
            $data['tanggal'] = $a->tanggal;
        }


        $data['page_title'] = 'Jika omzet sudah sesuai, silahkan lanjut import data stock anda';
        $data['page_content'] = 'import/import_stock';
        $data['menu']=$this->db->query($this->querymenu);
        $this->template($data['page_content'],$data);

    }

    public function transfer(){

        $data['url'] = 'import/transfer/';
        $transfer = $this->model_import->transfer();
        if ($transfer) {
            redirect('import/import_dp');
        }else{
            echo "transfer data gagal. Silahkan hubungi IT";
        }
        // $data['page_title'] = 'Review Retur';
        // $data['menu']=$this->db->query($this->querymenu);
        // $this->template($data['page_content'],$data);

    }


}
?>
