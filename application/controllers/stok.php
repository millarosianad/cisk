<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Stok extends MY_Controller
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

    function stok()
    {
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
        set_time_limit(0);
        $this->load->library(array('table','template','Excel_generator'));
        $this->load->helper('url');
        $this->load->helper('csv');
        $this->load->model(array('model_omzet','model_stok','M_menu','model_outlet_transaksi','model_per_hari','model_inventory','M_helpdesk'));
        $this->load->database();
        }

    public function stok_product()
    {
        $data = [
                'id' => $this->session->userdata('id'),
                'url' => 'stok/stok_product_hasil',
                'title' => 'Stock Product',
                'query' => $this->model_omzet->getSuppbyid(),
                'get_label' => $this->M_menu->get_label()
        ];
        $supp = $this->session->userdata('supp');
        $data['header_supp'] = $this->model_outlet_transaksi->get_header_supp($supp);
        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar',$data);
        $this->load->view('template_claim/top_content',$data);
        $this->load->view('stok/stok',$data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
    }

    public function stok_product_hasil()
    {
        $log = [
            'userid'    => $this->session->userdata('id'),
            'menu'      => $this->uri->segment('2'),
            'created_at'=> $this->model_outlet_transaksi->timezone()
        ];
        $this->db->insert('site.log_kunjungan_website', $log);
        
        $kodeprod = $this->input->post('options');
        $code = '';
        foreach($kodeprod as $kode)
        {
            $code.=",".$kode;
        }
        $data = [
                'id' => $this->session->userdata('id'),
                'url' => 'stok/stok_product_hasil/',
                'title' => 'Stock Product',
                'query' => $this->model_omzet->getSuppbyid(),
                'get_label' => $this->M_menu->get_label(),
                'tahun' => $this->input->post('tahun'),
                'bd' => $this->input->post('bd'),
                'kodeprod'  => preg_replace('/,/', '', $code,1)
        ];

        $bd = $this->input->post('bd');
        if ($bd == '1'){
            $view_content = 'stok_hasil_kodeprod';
        }else{
            $view_content = 'stok_hasil';
        }
        
        $supp = $this->session->userdata('supp');
        $data['header_supp'] = $this->model_outlet_transaksi->get_header_supp($supp);
        $data['wilayah_nocab'] = $this->model_per_hari->wilayah_nocab($this->session->userdata('id'));
        $data['proses'] = $this->model_stok->stok_product($data);
        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar',$data);
        $this->load->view('template_claim/top_content',$data);
        $this->load->view("stok/$view_content",$data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
    }

    public function export_stok_product($kondisi = ''){
        $id=$this->session->userdata('id');
        $tahun = $this->uri->segment('3');
        // $kondisi = $this->uri->segment('4');
        $bd = $this->uri->segment('5');

        // echo "kondisi : ".$kondisi;
        // die;

        if ($kondisi == '1'){
            $query="
                    SELECT  nocab, sub, branch_name, nama_comp as Sub_branch, namasupp, kodeprod, namaprod, `group`, nama_group, subgroup, nama_subgroup, h_dp,
                            unit_1,unit_2,unit_3,unit_4,unit_5,unit_6,
                            unit_7,unit_8,unit_9,unit_10,unit_11,unit_12,
                            value_1,value_2,value_3,value_4,value_5,value_6,
                            value_7,value_8,value_9,value_10,value_11,value_12, gudang_id, nama_gudang
                    FROM site.temp_stock_product
                    where  id = $id and created_date = (select max(created_date) from site.temp_stock_product where id = $id)
                    ";
                            
            $hasil = $this->db->query($query);

        }else{
            $query="
                  SELECT    nocab, sub,branch_name, nama_comp as Sub_branch,
                            unit_1,unit_2,unit_3,unit_4,unit_5,unit_6,
                            unit_7,unit_8,unit_9,unit_10,unit_11,unit_12,
                            value_1,value_2,value_3,value_4,value_5,value_6,
                            value_7,value_8,value_9,value_10,value_11,value_12, gudang_id, nama_gudang
                    FROM site.temp_stock_product a
                    where  id = $id and created_date = (select max(created_date) from site.temp_stock_product where id = $id)
                     ";

            $hasil = $this->db->query($query);
        }
         query_to_csv($hasil,TRUE,"Stok($bd)_$tahun.csv");
    }

    public function dashboard(){
        // $get_kode_alamat = $this->model_inventory->get_kode_alamat();
        // $code = '';
        // foreach ($get_kode_alamat as $key) {
        //     $code.= ","."'".$key->kode_alamat."'";
        // }
        // $kode_alamat = preg_replace('/,/', '', $code,1);

        // echo "kode_alamat : ".$kode_alamat;
        // die;

        $data = [
            'id'            => $this->session->userdata('id'),
            'url'           => 'stok/proses_import_stock',
            'title'         => 'History Import Stock',
            'query'         => $this->model_omzet->getSuppbyid(),
            'get_label'     => $this->M_menu->get_label(),
            'site_code'     => $this->M_helpdesk->get_sitecode(),
            'get_header'   => $this->model_stok->get_header_current(),
        ];
        $supp = $this->session->userdata('supp');
        $data['header_supp'] = $this->model_outlet_transaksi->get_header_supp($supp);
        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar',$data);
        $this->load->view('template_claim/top_content',$data);
        $this->load->view('stok/dashboard',$data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
    }

    public function proses_import_stock(){
        $created_date = $this->model_outlet_transaksi->timezone();
        $file = $this->input->post('file');
        $bulan = $this->input->post('bulan');
        $site_code = $this->input->post('site_code');

        $bulan_params = substr($bulan, 5, 2);
        $tahun_params = substr($bulan, 0, 4);

        // echo "bulan_params : ".$bulan_params;
        // echo "tahun_params : ".$tahun_params;
        // die;

        if (!is_dir('./assets/file/stock/files_import_stock/')) {
            @mkdir('./assets/file/stock/files_import_stock/', 0777);
        }
        //konfigurasi upload
        $config['upload_path'] = './assets/file/stock/files_import_stock';
        $config['allowed_types'] = '*';
        $config['max_size'] = '*';

        $this->load->library('upload', $config);
        if (!$this->upload->do_upload('file')) {
            var_dump($this->upload->display_errors());
            echo "gagal upload";
            $filename = '';
        } else {

            $data = array('upload_data' => $this->upload->data());
            $file = $data['upload_data']['full_path'];
            chmod($file, 0777);

            //mengambil
            $upload_data = $this->upload->data();
            $filename = $upload_data['file_name'];

            $data_history = [
                'tahun'     => $tahun_params,
                'bulan'     => $bulan_params,
                'filename'  => $filename,
                'site_code' => $site_code,
                'created_at'=> $created_date,
                'created_by'=> $this->session->userdata('id'),
                'signature'=> $site_code.'-'.md5($created_date),
            ];

            $insert_history = $this->db->insert('site.t_import_stock_history', $data_history);
            $id_history = $this->db->insert_id();

            $this->load->library('excel');
            $object = PHPExcel_IOFactory::load("assets/file/stock/files_import_stock/$filename");

            $jumlahSheet = $object->getSheetCount();
            if ($jumlahSheet > 1) {
                echo "jumlah_sheet : ".$jumlahSheet;
                echo "<script>alert('upload file gagal karena file mempunyai lebih dari 1 sheet'); </script>";
                redirect('stok/dashboard','refresh');
            }


            foreach ($object->getWorksheetIterator() as $worksheet) {

                $highestRow = $worksheet->getHighestRow();
                $highestColumn = $worksheet->getHighestColumn();

                for ($row = 2; $row <= $highestRow; $row++) {          
                
                    $kodeprod_file = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
                    $namaprod_file = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
                    $stock_file = $worksheet->getCellByColumnAndRow(2, $row)->getValue();

                    // echo "<pre>";
                    // echo "kodeprod_file : ".$kodeprod_file."<br>";
                    // echo "namaprod_file : ".$namaprod_file."<br>";
                    // echo "stock_file : ".$stock_file."<br>";
                    // echo "</pre>";

                    // die;
                    
                    if($kodeprod_file == null || $kodeprod_file == ''){
                        echo "
                        <script> 
                            window.alert('Import Gagal.\\nAda data kosong dikolom kodeprod, baris ke $row. Silahkan cek kembali');
                            window.location=history.go(-1);
                        </script>";
                        die;
                    }

                    if($namaprod_file == null || $namaprod_file == ''){
                        echo "
                        <script> 
                            window.alert('Import Gagal.\\nAda data kosong dikolom namaprod, baris ke $row. Silahkan cek kembali');
                            window.location=history.go(-1);
                        </script>";
                        die;
                    }

                    if($stock_file == null || $stock_file == ''){
                        echo "
                        <script> 
                            window.alert('Import Gagal.\\nAda data kosong dikolom stock, baris ke $row. Silahkan cek kembali');
                            window.location=history.go(-1);
                        </script>";
                        die;
                    }
                    
                    if(strlen("$kodeprod_file") == '5')
                    {
                        $kodeprodx = '0'.$kodeprod_file;
                    }else{
                        $kodeprodx = $kodeprod_file;
                    } 

                    $data = [
                        'id_history'    => $id_history,
                        'kodeprod_file' => $kodeprodx,
                        'namaprod_file' => $namaprod_file,
                        'stock_file' => $stock_file,
                        'created_by' => $this->session->userdata('id'),
                        'created_at' => $created_date
                    ];

                    $this->db->insert('site.temp_import_stock_detail',$data);
                }
            }
        }

        $query_update = "
            update site.temp_import_stock_detail a 
            set a.kodeprod = a.kodeprod_file,
                a.namaprod = (
                    select b.namaprod
                    from mpm.tabprod b
                    where a.kodeprod = b.kodeprod
                ),
                a.stock = a.stock_file
            where a.id_history = $id_history
        ";
        $this->db->query($query_update);

        $data = [
            'id'            => $this->session->userdata('id'),
            'url'           => 'stok/proses_import_stock',
            'title'         => 'Proses Import Stock',
            'query'         => $this->model_omzet->getSuppbyid(),
            'get_label'     => $this->M_menu->get_label(),
            'get_header_current'   => $this->model_stok->get_header_current($id_history),
            'get_import_current'   => $this->model_stok->get_import_current($id_history)->result(),
        ];
        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar',$data);
        $this->load->view('template_claim/top_content',$data);
        $this->load->view('stok/proses_import_stock',$data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');

    }

    public function export_stock($signature){
        $query="
            select a.site_code, c.branch_name, c.nama_comp, b.kodeprod_file, b.namaprod_file, b.stock_file, b.kodeprod, b.namaprod, b.stock, a.tahun, a.bulan
            from site.t_import_stock_history a left join
            (
                select a.id_history, a.kodeprod_file, a.namaprod_file, a.stock_file, a.kodeprod, a.namaprod, a.stock
                from site.temp_import_stock_detail a
            )b on a.id = b.id_history LEFT JOIN 
            (
                select concat(a.kode_comp, a.nocab) as site_code, a.branch_name, a.nama_comp
                from mpm.tbl_tabcomp a 
                where a.`status` = 1
                GROUP BY concat(a.kode_comp, a.nocab)
            )c on a.site_code = c.site_code
            where a.signature = '$signature'
            ";

        $hasil = $this->db->query($query);
        
        query_to_csv($hasil,TRUE,"export_import_stock.csv");
    }

}

