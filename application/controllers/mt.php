<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Mt extends MY_Controller
{
    
    function mt()
    {       
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
        set_time_limit(0);
        $this->load->library(array('table','template','Excel_generator', 'form_validation','email'));
        $this->load->helper(array('url','csv'));
        $this->load->model(array('model_omzet','model_retur','model_inventory','M_helpdesk','M_menu','model_mt','ModelDatabaseAfiliasi','model_outlet_transaksi','M_asn'));
        $this->load->database();
        
        // $this->tabel_header = "site.t_history_import";
        // $this->tabel_detail = "site.t_import";
    }
    function index()
    {
        $this->import_order();
    }

    private function template($view,$data)
    {
        $this->template->set_title('MPM SQUARE');
        $this->template->add_js('modules/skeleton.js');
        $this->template->add_css('modules/skeleton.css');
        $this->template->load_view($view, $data);
    }

    public function import_order(){
        $get_kode_alamat = $this->model_inventory->get_kode_alamat();
        $code = '';
        foreach ($get_kode_alamat as $key) {
            $code.= ","."'".$key->kode_alamat."'";
        }
        $kode_alamat = preg_replace('/,/', '', $code,1);

        $data = [
            'id'          => $this->session->userdata('id'),
            'title'       => "Purchase Order B2B",
            'get_label'   => $this->M_menu->get_label(),
            'url'         => 'mt/proses_import_order',
            'history'     => $this->model_mt->history_import('site.t_history_import', $kode_alamat)->result(),
            'url'         => 'mt/proses_import_order'
        ];

        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar', $data);
        $this->load->view('mt/import_order', $data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
    }

    public function proses_import_order(){
        $this->config_upload();
        $tgl = $this->model_outlet_transaksi->timezone();

        // Load konfigurasi uploadnya    
        if($this->upload->do_upload('file_csv'))
        { 
          $upload_data = $this->upload->data();
          $filename_csv = $upload_data['file_name'];
          $this->load->library('excel');
          $object = PHPExcel_IOFactory::load("assets/uploads/import_mt/$filename_csv");
        }else{  
            $return = array('result' => 'failed', 'file' => '', 'error' => $this->upload->display_errors());      
            echo "<pre>";
            print_r($return);
            echo "</pre>";
        }

        // echo "filename_csv : ".$filename_csv;

        if($this->upload->do_upload('file_pdf'))
        { 
          $upload_data = $this->upload->data();
          $filename_pdf = $upload_data['file_name'];
        }else{  
            $return = array('result' => 'failed', 'file' => '', 'error' => $this->upload->display_errors());      
            echo "<pre>";
            print_r($return);
            echo "</pre>";
        }

        // echo "filename_pdf : ".$filename_pdf;
        // die;

        $data= [
            // 'site_code'     => $this->input->post('site_code'),
            'partner'       => $this->input->post('partner'),
            'filename_csv'  => $filename_csv,
            'filename_pdf'  => $filename_pdf,
            'created_by'    => $this->session->userdata('id'),
            'created_at'    => $tgl,
            'signature'     => $signature = md5($this->input->post('site_code').$tgl)
        ];        
        $id_history = $this->M_asn->insert("site.t_history_import", $data);

        $partner = $this->input->post('partner');
        if ($partner == 'sbl') {
            $this->import_sbl($data, $id_history, $partner);
            // die;
        }
    }

    public function import_sbl($params, $id_history, $partner){

        // $site_code = $params['site_code'];
        $partner = $params['partner'];
        $filename_csv = $params['filename_csv'];
        $created_at = $params['created_at'];
        $created_by = $params['created_by'];
        $signature = $params['signature'];

        $this->load->library('excel');
        $object = PHPExcel_IOFactory::load("assets/uploads/import_mt/$filename_csv");

        foreach($object->getWorksheetIterator() as $worksheet)
        {
            $highestRow = $worksheet->getHighestRow();
            $highestColumn = $worksheet->getHighestColumn();                

            for($row=2; $row<=$highestRow; $row++)
            {
                // echo "product_code : ".$worksheet->getCellByColumnAndRow(8, $row)->getValue()."<br>";
                $data[] = array(
                    // 'site_code'         => $site_code,
                    'id_history'        => $id_history,
                    'po_number'		    => $worksheet->getCellByColumnAndRow(0, $row)->getValue(),
                    'po_group'		    => $worksheet->getCellByColumnAndRow(1, $row)->getValue(),
                    'supplier_name'     => $worksheet->getCellByColumnAndRow(2, $row)->getValue(),  
                    'supplier_code'     => $worksheet->getCellByColumnAndRow(3, $row)->getValue(),  
                    'contact_person'    => $worksheet->getCellByColumnAndRow(4, $row)->getValue(),  
                    'delivery_to_name'  => $worksheet->getCellByColumnAndRow(5, $row)->getValue(),  
                    'delivery_location_code' => $worksheet->getCellByColumnAndRow(6, $row)->getValue(),  
                    'line_item_no'      => $worksheet->getCellByColumnAndRow(7, $row)->getValue(),  
                    'product_code'      => $worksheet->getCellByColumnAndRow(8, $row)->getValue(),  
                    'item_barcode'      => $worksheet->getCellByColumnAndRow(9, $row)->getValue(),  
                    'order_quantity'    => $worksheet->getCellByColumnAndRow(10, $row)->getValue(),  
                    'uom'               => $worksheet->getCellByColumnAndRow(11, $row)->getValue(),  
                    'uom_pack_size'     => $worksheet->getCellByColumnAndRow(12, $row)->getValue(),  
                    'uom_inner'         => $worksheet->getCellByColumnAndRow(13, $row)->getValue(),  
                    'unit_price'        => $worksheet->getCellByColumnAndRow(14, $row)->getValue(),  
                    'discount'          => $worksheet->getCellByColumnAndRow(15, $row)->getValue(),  
                    'line_item_total'   => $worksheet->getCellByColumnAndRow(16, $row)->getValue(),  
                    'item_descriptions' => $worksheet->getCellByColumnAndRow(17, $row)->getValue(),  
                    'expected_delivery_date'     => $worksheet->getCellByColumnAndRow(18, $row)->getValue(),  
                    'line_total_dc_alw_x_dock'   => $worksheet->getCellByColumnAndRow(19, $row)->getValue(),  
                    'line_total_dmg_allowance'   => $worksheet->getCellByColumnAndRow(20, $row)->getValue(),  
                    'line_vat_total'    => $worksheet->getCellByColumnAndRow(21, $row)->getValue(),  
                    'grand_total'       => $worksheet->getCellByColumnAndRow(22, $row)->getValue(),  
                    'po_order_date'     => $worksheet->getCellByColumnAndRow(23, $row)->getValue(),
                    'created_at'        => $created_at, 
                    'created_by'        => $created_by
                );
                // $insert = $this->model_inventory->insert_ms($data, 'db_mt.t_import');
            }

            $insert = $this->model_inventory->insert_ms($data, 'site.t_import');
            // $insert = $this->M_asn->insert('db_mt.t_import', $data);
        }

        $update_detail = $this->model_mt->mapping_mpm($id_history, $partner);
        // $update_header = $this->model_mt->update_header_import($id_history);

        $update_status = $this->model_mt->update_status($id_history);


        // $cek_mapping = "
        //     select kodeprod_mpm, a.customer_code_mpm
        //     from site.t_import a 
        //     where a.id_history = $id_history
        // ";

        // $proses_mapping = $this->db->query($cek_mapping)->result();

        // foreach($proses_mapping as $key){
        //     if ($key->kodeprod_mpm == null) {
                
        //         $update = "
        //             update site.t_history_import a
        //             set a.status_mapping_kodeprod = 'failed'
        //             where a.id = $id_history
        //         ";
        //         $proses_update =- $this->db->query($update);         
        //     }else{
        //         $update = "
        //             update site.t_history_import a
        //             set a.status_mapping_kodeprod = 'success'
        //             where a.id = $id_history
        //         ";
        //         $proses_update =- $this->db->query($update);
        //     }

        //     if ($key->customer_code_mpm == null) {
                
        //         $update = "
        //             update site.t_history_import a
        //             set a.status_mapping_outlet = 'failed'
        //             where a.id = $id_history
        //         ";
        //         $proses_update =- $this->db->query($update);         
        //     }else{
        //         $update = "
        //             update site.t_history_import a
        //             set a.status_mapping_outlet = 'success'
        //             where a.id = $id_history
        //         ";
        //         $proses_update =- $this->db->query($update);  
        //     }
        // }
        echo "<script>alert('import berhasil'); </script>";
        redirect('mt/import_order','refresh');     
    } 

    public function delete_order($signature){
        $tgl = $this->model_outlet_transaksi->timezone();
        $data = [
            'deleted'       => '1',
            'updated_at'    => $this->model_outlet_transaksi->timezone(),
            'updated_by'    => $this->session->userdata('id')
        ];

        $this->db->where('signature',$signature);
        $proses = $this->db->update('site.t_history_import',$data);
        redirect("mt/import_order/");

    }

    public function download_mapping_product(){
        $query="
        select a.partner_id, a.kodeprod_mpm, a.kodeprod_partner
        from site.map_product_mt a        
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        $hasil = $this->db->query($query);
        query_to_csv($hasil,TRUE,"download mapping product.csv");        
    }

    public function config_upload(){        
        $this->load->library('upload'); // Load librari upload        
        $config['upload_path'] = './assets/uploads/import_mt/';  
        $config['allowed_types'] = '*';    
        $config['max_size']  = '5000';    
        $config['overwrite'] = false;   
        $this->upload->initialize($config);
    }

    public function proses_mapping_product()
    {
        $created_at = $this->model_outlet_transaksi->timezone();
        $this->config_upload();
        // Load konfigurasi uploadnya    
        if($this->upload->do_upload('file'))
        { 
            $upload_data = $this->upload->data();
            $filename_csv = $upload_data['file_name'];
            $this->load->library('excel');
            $object = PHPExcel_IOFactory::load("assets/uploads/import_mt/$filename_csv");


            foreach($object->getWorksheetIterator() as $worksheet)
            {
                $highestRow = $worksheet->getHighestRow();
                $highestColumn = $worksheet->getHighestColumn();                

                for($row=2; $row<=$highestRow; $row++)
                {
                    $partner_id = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
                    $kodeprod_mpm = $worksheet->getCellByColumnAndRow(1, $row)->getValue();

                    if ($kodeprod_mpm == null) {
                        echo "<script>alert('ada kesalahan. kodeprod_mpm tidak boleh null. cek kembali file anda'); </script>";
                        redirect('mt/import_order','refresh');   
                    }
              
                    // echo $kodeprod_mpm;
                    if (strlen($kodeprod_mpm) == '5') {
                        $kodeprod_mpm = '0'.$kodeprod_mpm; 
                    }
                    $cek = "
                    select *
                    from site.map_product_mt a 
                    where a.partner_id = '$partner_id' and a.kodeprod_mpm = '$kodeprod_mpm' 
                    ";

                    // print_r($cek);
                    // die;

                    $proses_cek = $this->db->query($cek)->num_rows();
                    if ($proses_cek) {
                        // echo "ada<br>";
                        $data = [
                            'kodeprod_partner'  => $worksheet->getCellByColumnAndRow(2, $row)->getValue(),  
                            'active'            => $worksheet->getCellByColumnAndRow(3, $row)->getValue(),  
                            'deleted'           => $worksheet->getCellByColumnAndRow(4, $row)->getValue(),  
                            'filename'          => $filename_csv,
                            'created_at'        => $created_at, 
                            'created_by'        => $this->session->userdata('id')
                        ]; 

                        $this->db->where('partner_id',$partner_id);
                        $this->db->where('kodeprod_mpm',$kodeprod_mpm);
                        $update = $this->db->update('site.map_product_mt',$data);


                    }else{
                        // echo "baru<br>";
                        $data = [
                            'partner_id'		=> $worksheet->getCellByColumnAndRow(0, $row)->getValue(),
                            'kodeprod_mpm'	    => $worksheet->getCellByColumnAndRow(1, $row)->getValue(),
                            'kodeprod_partner'  => $worksheet->getCellByColumnAndRow(2, $row)->getValue(),  
                            'active'            => $worksheet->getCellByColumnAndRow(3, $row)->getValue(),  
                            'deleted'           => $worksheet->getCellByColumnAndRow(4, $row)->getValue(),  
                            'filename'          => $filename_csv,
                            'created_at'        => $created_at, 
                            'created_by'        => $this->session->userdata('id')
                        ];
                        $insert = $this->db->insert('site.map_product_mt', $data);
                    }
                }
            }

            $update = "
                update site.map_product_mt a 
                set a.kodeprod_mpm = concat('0',a.kodeprod_mpm)
                where length(a.kodeprod_mpm) = 5 and a.created_at = '$created_at'
            ";
            $proses_update = $this->db->query($update);

        }else{  
            $return = array('result' => 'failed', 'file' => '', 'error' => $this->upload->display_errors());      
            echo "<pre>";
            print_r($return);
            echo "</pre>";
        }

        echo "<script>alert('import berhasil'); </script>";
        redirect('mt/import_order','refresh');      

    }

    public function download_mapping_outlet(){
        $query="
        select a.partner_id, a.site_code, a.customer_code_mpm, a.customer_code_partner
        from site.map_outlet_mt a      
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        $hasil = $this->db->query($query);
        query_to_csv($hasil,TRUE,"download mapping outlet.csv");        
    }

    public function proses_mapping_outlet(){

        $created_at = $this->model_outlet_transaksi->timezone();
        // echo "tgl : ".$tgl;
        // die;

        $this->load->library('upload'); // Load librari upload        
        $config['upload_path'] = './assets/uploads/import_mt/';  
        $config['allowed_types'] = '*';    
        $config['max_size']  = '5000';    
        $config['overwrite'] = false;   
        $this->upload->initialize($config);

        // Load konfigurasi uploadnya    
        if($this->upload->do_upload('file'))
        { 
            $upload_data = $this->upload->data();
            $filename_csv = $upload_data['file_name'];
            $this->load->library('excel');
            $object = PHPExcel_IOFactory::load("assets/uploads/import_mt/$filename_csv");


            foreach($object->getWorksheetIterator() as $worksheet)
            {
                $highestRow = $worksheet->getHighestRow();
                $highestColumn = $worksheet->getHighestColumn();                

                for($row=2; $row<=$highestRow; $row++)
                {
                    $partner_id             = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
                    $site_code              = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
                    $customer_code_mpm      = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
                    $customer_code_partner  = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
              
                    $cek = "
                    select *
                    from site.map_outlet_mt a 
                    where a.partner_id = '$partner_id' and a.customer_code_mpm = '$customer_code_mpm' 
                    ";

                    // print_r($cek);
                    // die;

                    $proses_cek = $this->db->query($cek)->num_rows();
                    if ($proses_cek) {
                        // echo "ada<br>";
                        $data = [
                            'site_code'              => $worksheet->getCellByColumnAndRow(1, $row)->getValue(),  
                            'customer_code_partner'  => $worksheet->getCellByColumnAndRow(3, $row)->getValue(),  
                            'active'                 => $worksheet->getCellByColumnAndRow(4, $row)->getValue(),  
                            'deleted'                => $worksheet->getCellByColumnAndRow(5, $row)->getValue(),  
                            'created_at'             => $created_at, 
                            'created_by'             => $this->session->userdata('id')
                        ]; 

                        $this->db->where('partner_id',$partner_id);
                        $this->db->where('customer_code_mpm',$customer_code_mpm);
                        $update = $this->db->update('site.map_outlet_mt',$data);


                    }else{
                        // echo "baru<br>";
                        $data = [
                            'partner_id'		    => $worksheet->getCellByColumnAndRow(0, $row)->getValue(),
                            'site_code'		        => $worksheet->getCellByColumnAndRow(1, $row)->getValue(),
                            'customer_code_mpm'	    => $worksheet->getCellByColumnAndRow(2, $row)->getValue(),
                            'customer_code_partner' => $worksheet->getCellByColumnAndRow(3, $row)->getValue(),  
                            'active'                => $worksheet->getCellByColumnAndRow(4, $row)->getValue(),  
                            'deleted'               => $worksheet->getCellByColumnAndRow(5, $row)->getValue(),  
                            'filename'              => $filename_csv,
                            'created_at'            => $created_at, 
                            'created_by'            => $this->session->userdata('id')
                        ];
                        $insert = $this->db->insert('site.map_outlet_mt', $data);
                    }
                }
            }

        }else{  
            $return = array('result' => 'failed', 'file' => '', 'error' => $this->upload->display_errors());      
            echo "<pre>";
            print_r($return);
            echo "</pre>";
        }

        echo "<script>alert('import berhasil'); </script>";
        redirect('mt/import_order','refresh');      

    }

    public function cek_json($signature){

        // echo "signature : ".$signature;

        $cek = "
            select a.site_code, date(a.created_at) as created_at
            from site.t_history_import a 
            where a.signature = '$signature'
        ";
        $proses = $this->db->query($cek)->row();
        // print_r($proses);

        // echo "site_code : ".$proses->site_code;

        // redirect('http://localhost:81/restapi/api/request/order_mt?token=870d89768fe3dcc1488de9328338b7cb&X-API-KEY=123&site_code='.$proses->site_code.'&from='.$proses->created_at.'&'.'to='.$proses->created_at.'', 'refresh');
        redirect('http://site.muliaputramandiri.com/restapi/api/request/order_mt?token=870d89768fe3dcc1488de9328338b7cb&X-API-KEY=123&site_code='.$proses->site_code.'&from='.$proses->created_at.'&'.'to='.$proses->created_at.'', 'refresh');

    }

    public function reload_order($signature){
        // echo $signature;
        $id_history = $this->model_mt->get_header($signature)->row()->id;
        $partner = $this->model_mt->get_header($signature)->row()->partner;
        $update_detail = $this->model_mt->mapping_mpm($id_history, $partner);
        $update_header = $this->model_mt->update_status($id_history);
        redirect('mt/import_order','refresh'); 

    }

    public function log_history($signature){

        // echo $signature;

        $data = [
            'title'          => 'LOG HIT API',
            'get_label'      => $this->M_menu->get_label(),
            'get_log_header' => $this->model_mt->get_log_header($signature)->row(),
            'get_log'        => $this->model_mt->get_log($signature)->result(),
        ]; 

        // var_dump($data);

        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar', $data);
        $this->load->view('mt/log_history', $data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');

    }

    public function detail_history($signature){

        $data = [
            'title'     => 'Detail PO',
            'get_label' => $this->M_menu->get_label(),
            // 'get_header'   => $this->model_mt->get_header($signature)->row(),
            'get_detail'   => $this->model_mt->get_detail($signature) 
        ]; 
        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar', $data);
        $this->load->view('mt/detail_history', $data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');

    }

}
?>
