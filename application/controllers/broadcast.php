<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Broadcast extends MY_Controller
{
    
    function broadcast()
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
        $this->load->model(array('M_menu','model_broadcast','model_outlet_transaksi','M_import'));
        $this->load->database();
    }

    function index()
    {

        $data = [
            'id'          => $this->session->userdata('id'),
            'title'       => 'Broadcast Whatsapp',
            'get_label'   => $this->M_menu->get_label(),
            'get_contact' => $this->model_broadcast->get_contact()->result(), 
            'url'         => 'broadcast/preview_broadcast'
        ];

        // var_dump($data['get_contact']);
        // die;
        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar', $data);
        $this->load->view('broadcast/dashboard', $data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
    }

    public function preview_broadcast(){
        // $message = $this->input->post('message');
        // echo "message : ".$message;

        $created_by = $this->session->userdata('id');
        $data = [
            'id'          => $this->session->userdata('id'),
            'title'       => 'Broadcast Whatsapp',
            'get_label'   => $this->M_menu->get_label(),
            'get_contact' => $this->model_broadcast->get_contact()->result(), 
            'url'         => 'broadcast/send_broadcast',

            'message'       => $this->input->post('message'),
            'get_contact'   => $this->model_broadcast->get_contact()->result(),
            'created_at'    => $this->model_outlet_transaksi->timezone(),
            'created_by'    => $created_by
        ];
        
        $signature = $this->model_broadcast->insert_broadcast($data);

        // echo "insert_broadcast : ".$insert_broadcast;
        // die;

        $update = $this->model_broadcast->update_broadcast($signature);


        $data = [
            'title'       => 'Broadcast Whatsapp',
            'get_label'   => $this->M_menu->get_label(),
            'url'         => 'broadcast/send_broadcast',
            'created_at'    => $this->model_outlet_transaksi->timezone(),
            'created_by'    => $created_by,
            'get_preview'   => $this->model_broadcast->get_broadcast_preview($signature)->result(),
            'signature'     => $this->model_broadcast->get_broadcast_preview($signature)->row()->signature
        ];

        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar', $data);
        $this->load->view('broadcast/preview', $data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');

    }

    public function send_broadcast($signature){
        $data = [
            'get_preview' => $this->model_broadcast->get_broadcast_preview($signature)->result()
        ];
        $send_broadcast = $this->model_broadcast->send_broadcast($data);



    }

    public function import_proses()
    {
        if (!is_dir('./assets/file/broadcast_kontak/')) {
            @mkdir('./assets/file/broadcast_kontak/', 0777);
        }

        $id = $this->session->userdata('id');
        $date = $this->model_outlet_transaksi->timezone();
        $this->load->library('upload'); // Load librari upload
        $config['upload_path'] = './assets/file/broadcast_kontak';
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
            $object = PHPExcel_IOFactory::load("assets/file/broadcast_kontak/$filename");

            // ------------------------------------ kontak --------------------------------------
            foreach($object->getWorksheetIterator() as $worksheet)
                {
                    $highestRow = $worksheet->getHighestRow();
                    $highestColumn = $worksheet->getHighestColumn();
                    for($row=2; $row<=$highestRow; $row++)
                    {
                        $nama = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
                        $no_wa = $worksheet->getCellByColumnAndRow(1, $row)->getValue();

                        $data = [
                            'nama' => $nama,
                            'no_wa' => '0'.$no_wa,
                            'created_at' => $date,
                            'created_by' => $id,
                        ];
                        $insert = $this->M_import->insert("site.t_broadcast_contact",$data);
                    }      
                }
                echo "<script>alert('Import Berhasil !'); </script>";
                redirect("broadcast",'refresh');
        }else{
            $return = array('result' => 'failed', 'file' => '', 'error' => $this->upload->display_errors());
            redirect('broadcast');
        }
    }

    public function clear_contact()
    {
        $id = $this->session->userdata('id');
        $this->db->where('created_by', $id);
        $this->db->delete('site.t_broadcast_contact');

        redirect('broadcast');
    }

    public function warpin_eligible()
    {
        $get_cabang = $this->model_broadcast->get_cabang()->result();

        foreach ($get_cabang as $key) {
            $latitude = $key->latitude;
            $longitude = $key->longitude;
            $kodepos = $key->kodepos;

            $data = [
                "latitude" => $latitude,
                "longitude" => $longitude,
                "kodepos" => $kodepos,
            ];
            $this->model_broadcast->warpin_eligible($data);
        }
    }

    // public function warpin_store_generate(){

    //     $get_cabang = $this->model_broadcast->get_cabang()->result(); //MLG27
    //     foreach ($get_cabang as $key) {

    //         echo "proses generate ... ".$key->site_code;
    //         $this->warpin_store($key->site_code);
    //         echo "<hr>";

    //     }
    // }

    public function warpin_store()
    {
        $get_cabang = $this->model_broadcast->get_cabang()->result();

        foreach ($get_cabang as $key) {
            $originalid = $key->site_code;
            $company_id = '4';
            $name = $key->company;
            $latitude = $key->latitude;
            $longitude = $key->longitude;
            $address = $key->alamat;
            $contact_name = $key->contact_name;
            $contact_msisdn = $key->contact_msisdn;
            $sla_delivery = '1-2 hari';

            $data = [
                "original_id" => $originalid,
                "company_id" => $company_id,
                "name" => $name,
                "latitude" => $latitude,
                "longitude" => $longitude,
                "address" => $address,
                "contact_name" => $contact_name,
                "contact_msisdn" => $contact_msisdn,
                "sla_delivery" => $sla_delivery,
                "extra"         => [
                    "distributor_id" => "mpm"
                ]
            ];

            echo json_encode($data);
            // die;
            $proses = $this->model_broadcast->warpin_store($data);
            echo "<pre>";
            var_dump($proses);
            echo "</pre>";
        }
    }

    public function warpin_product_generate_update(){

        $get_cabang = $this->model_broadcast->get_cabang()->result(); //MLG27
        foreach ($get_cabang as $key) {

            echo "proses generate ... ".$key->site_code;
            $this->warpin_product_update($key->site_code);
            echo "<hr>";

        }
    }

    public function warpin_product_update($site_code)
    {
        // $get_cabang = $this->model_broadcast->get_cabang()->result(); //MLG27
        // foreach ($get_cabang as $key) {

            $get_product_site = $this->model_broadcast->get_product_site($site_code)->result();

            foreach ($get_product_site as $key) {
                $get_satuan = $this->model_broadcast->get_satuan($key->kodeprod)->row();

                
                $datas[] = [
                    "sku"               => $key->kode_prc,
                    "id"                => (int)$key->kodeprod,
                    "name"              => $key->namaprod,
                    "image_url"         => $key->apps_images,
                    "is_active"         => ($key->status_aktif) == 1 ? true : false,
                    "price"             => (float)$key->harga_jual_warpin,
                    "promotion_price"   => (float)$key->harga_jual_warpin,
                    "quantity"          => 100,
                    "uom"   => [
                        'id'            => (int)$key->id_satuan_jual_warpin,
                        'name'          => $key->satuan_jual_warpin,
                        'description'   => '',
                    ],
                    "uom_old"   => [
                        'id'            => (int)$key->id_satuan_jual_warpin,
                        'name'          => $key->satuan_jual_warpin,
                        'description'   => '',
                    ],
                    "category" => [
                        "id"            => 1,
                        "name"          => $key->nama_group,
                        "description"   => $key->nama_sub_group,
                    ]
                ];
            }

            $data = [
                "store" =>
                [
                    "id"            => $key->site_code,
                    "company_id"    => 4,
                    "name"          => $key->company
                ], "products" => $datas
            ];

            echo json_encode($data);
            // echo "<hr>";
            // die;
            // echo "<pre>";
            // var_dump($data);
            // echo "</pre>";
            // echo "<hr>";

            // die;
            $proses = $this->model_broadcast->warpin_product_update($data);
            echo "<pre>";
            var_dump($proses);
            echo "</pre>";
        // }
    }

    public function warpin_product_generate(){

        $get_cabang = $this->model_broadcast->get_cabang()->result(); //MLG27
        foreach ($get_cabang as $key) {

            echo "proses generate ... ".$key->site_code;
            $this->warpin_product($key->site_code);
            echo "<hr>";

        }
    }

    public function warpin_product($site_code)
    {
        // $get_cabang = $this->model_broadcast->get_cabang()->result(); //MLG27
        // foreach ($get_cabang as $key) {

            $get_product_site = $this->model_broadcast->get_product_site($site_code)->result();

            foreach ($get_product_site as $key) {
                $get_satuan = $this->model_broadcast->get_satuan($key->kodeprod)->row();

                
                $datas[] = [
                    "sku"               => $key->kode_prc,
                    "id"                => (int)$key->kodeprod,
                    "name"              => $key->namaprod,
                    "image_url"         => $key->apps_images,
                    "is_active"         => ($key->status_aktif) == 1 ? true : false,
                    "price"             => (float)$key->harga_jual_warpin,
                    "promotion_price"   => (float)$key->harga_jual_warpin,
                    "quantity"          => 100,
                    "uom"   => [
                        'id'            => (int)$key->id_satuan_jual_warpin,
                        'name'          => $key->satuan_jual_warpin,
                        'description'   => '',
                    ],
                    "uom_old"   => [
                        'id'            => (int)$key->id_satuan_jual_warpin,
                        'name'          => $key->satuan_jual_warpin,
                        'description'   => '',
                    ],
                    "category" => [
                        "id"            => 1,
                        "name"          => $key->nama_group,
                        "description"   => $key->nama_sub_group,
                    ]
                ];
            }

            $data = [
                "store" =>
                [
                    "id"            => $key->site_code,
                    "company_id"    => 4,
                    "name"          => $key->company
                ], "products" => $datas
            ];

            echo json_encode($data);
            // echo "<hr>";
            // die;
            // echo "<pre>";
            // var_dump($data);
            // echo "</pre>";
            // echo "<hr>";

            // die;
            $proses = $this->model_broadcast->warpin_product($data);
            echo "<pre>";
            var_dump($proses);
            echo "</pre>";
        // }
    }

    public function warpin_stock_generate(){

        $get_cabang = $this->model_broadcast->get_cabang()->result(); //MLG27
        foreach ($get_cabang as $key) {

            // echo "proses generate ... ".$key->site_code;
            $this->warpin_stock($key->site_code);
            // echo "<hr>";

        }
    }

    public function warpin_stock($site_code)
    {
        $tahun = date('Y');
        // $get_cabang = $this->model_broadcast->get_cabang()->result();

        // foreach ($get_cabang as $a) {

            $get_product_site = $this->model_broadcast->get_product_site($site_code)->result();

            foreach ($get_product_site as $b) {
                $get_stock = $this->model_broadcast->get_stock($b->kodeprod, $site_code, $tahun)->row();

                $datas[] = [
                    "id"       => (int)$b->kodeprod,
                    "sku"      => $b->kode_prc,
                    "quantity" => (int)$get_stock->stock,
                ];
            }

            $data = [
                "store_id"   => $site_code,
                "products" => $datas
            ];

            echo json_encode($data);
            // echo "<pre>";
            // var_dump($data);
            // echo "</pre>";
            die;
            $proses = $this->model_broadcast->warpin_stock($data);
            echo "<pre>";
            var_dump($proses);
            echo "</pre>";
            
        // }
    }

    // public function order_confirmation($id_order_status = ''){
    public function order_confirmation($signature = ''){
        
        // $get_data_order = $this->model_broadcast->get_data_order('site.t_erp_order_status', $id_order_status)->result();
        $get_data_order = $this->model_broadcast->get_data_order('site.t_erp_order_status', $signature)->result();
        // var_dump($get_data_order);
        foreach ($get_data_order as $key) {
            $from_aplikasi = $key->from_aplikasi;
        }

        // echo $from_aplikasi;
        if ($from_aplikasi == 'WARPIN') {
            $this->warpin_confirmation($get_data_order);
        }

    }

    public function warpin_confirmation($get_data_order)
    {
        // echo "<pre>";
        // var_dump($get_data_order);
        // echo "</pre>";
        // die;
        foreach ($get_data_order as $key) {
            $status = $key->status_erp;
            $invoice_aplikasi = $key->invoice_aplikasi;
            $created_at = $key->created_at;
            $created_at_custom = date("Y-m-d", strtotime($key->created_at)).'T'.date("h:i:s", strtotime($key->created_at));
            $id_status = $key->id;
            $payment_total = $key->payment_total;
        }

        $get_nama_status_order = $this->model_broadcast->get_nama_status_order($status);

        // echo "status : ".$status;
        // echo "get_nama_status_order : ".$get_nama_status_order;
        // die;

        $product = $this->model_broadcast->get_data_order_detail('site.t_erp_order_status_detail', $id_status)->result();

        if ($get_nama_status_order == 'order.confirmed') {

            foreach ($product as $key) {
                $items[] = [
                    "price_unit"            => (float)$key->total_harga,
                    "product_qty"           => (float)$key->total_qty_order,
                    "product_name"          => $key->namaprod,
                    "product_sku"           => $key->kodeprod,
                    "product_uom"           => $key->satuan_pemenuhan
                ];
            }
            
        }else if ($get_nama_status_order == 'order.delivery'){

            foreach ($product as $key) {
                $items[] = [
                    "price_unit"            => (float)$key->total_harga,
                    "product_qty"           => (float)$key->total_qty_order,
                    "product_delivered_qty" => (float)$key->total_qty_pemenuhan,
                    "product_name"          => $key->namaprod,
                    "product_sku"           => $key->kodeprod,
                    "product_uom"           => $key->satuan_pemenuhan
                ];
            }

        }else if ($get_nama_status_order == 'order.received.full'){

            foreach ($product as $key) {
                $items[] = [
                    "price_unit"            => (float)$key->total_harga,
                    "product_qty"           => (float)$key->total_qty_order,
                    "product_delivered_qty" => (float)$key->total_qty_pemenuhan,
                    "product_name"          => $key->namaprod,
                    "product_sku"           => $key->kodeprod,
                    "product_uom"           => $key->satuan_pemenuhan
                ];
            }
        }else if ($get_nama_status_order == 'order.canceled'){

            foreach ($product as $key) {
                $items[] = [
                    "price_unit"            => (float)$key->total_harga,
                    "product_qty"           => (float)$key->total_qty_order,
                    "product_name"          => $key->namaprod,
                    "product_sku"           => $key->kodeprod,
                    "product_uom"           => $key->satuan_pemenuhan
                ];
            }
        }else{
            
            foreach ($product as $key) {
                $items[] = [
                    "price_unit"            => (float)$key->total_harga,
                    "product_qty"           => (float)$key->total_qty_order,
                    // "product_delivered_qty" => (float)$key->total_qty_pemenuhan,
                    "product_name"          => $key->namaprod,
                    "product_sku"           => $key->kodeprod,
                    "product_uom"           => $key->satuan_pemenuhan
                ];
            }
        }

        

        $data = [
            "event_id"  => '',
            "action"    => $get_nama_status_order,
            "ref"       => $invoice_aplikasi,
            "sub"       => '',
            "date"      => $created_at_custom,
            "data"      => [
                "company_id"    => 4,
                "external_ref"  => $invoice_aplikasi,
                "payment_method"  => "COD",
                "sale_order_status"  => $get_nama_status_order,
                "sale_order_date"   => $created_at,
                "sale_order_item" => $items,
                "sale_order_total_price" => (float)$payment_total,
            ],
        ];

        echo json_encode($data);

        // $proses = $this->model_broadcast->warpin_confirmation($data);
        // echo "<pre>";
        // var_dump($proses);
        // echo "</pre>";

        // if ($proses) {
        //     $data = [
        //         "status_proses_schedule"    => 0
        //     ];
        // }

    }    
    
}
?>
