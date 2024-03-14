<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Transaction extends MY_Controller
{
    function transaction()
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
        $this->load->model('model_omzet');
        $this->load->model('M_menu');
        $this->load->model('model_transaction');
        $this->load->model('model_outlet_transaksi');
        $this->load->model('model_per_hari');
        $this->load->model('model_order');
        $this->load->model('model_inventory');
        $this->load->model('model_po');
        $this->load->database();

        // update traffic po
        // $traffic = $this->model_transaction->get_traffic_po();
        // if ($traffic->num_rows() > 0) {

        //     $date = $this->model_transaction->get_traffic_po()->row()->created_at;
        //     $currentDate = strtotime($date);
        //     $futureDate = $currentDate+(60*5);
        //     $countdown = date("Y-m-d H:i:s", $futureDate);

        //     $waktu_berjalan = $this->model_outlet_transaksi->timezone();

        //     $id_traffic = $this->model_transaction->get_traffic_po()->row()->id;

        //     if ($countdown <= $waktu_berjalan ) {
        //         $input =[
        //             'status' => '0'
        //         ];

        //         $this->db->where('id', $id_traffic);
        //         $proses = $this->db->update('mpm.traffic_po', $input);
        //     } 
        // }
        // echo $countdown;
        // echo $waktu_berjalan;
        // die;
    }

    public function index(){
        $this->list_product();
    }

    public function list_product(){
        $data = [
            'id' => $this->session->userdata('id'),
            'url' => 'transaction/proses_to_cart/',
            'title' => 'List Product',
            'get_label' => $this->M_menu->get_label(),
            'supp' => $this->model_order->getSupp()->result()
        ];
        $supp = $this->input->get('supp');
        // var_dump($supp);die;
        $data['header_supp'] = $this->model_outlet_transaksi->get_header_supp($supp);
        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar',$data);
        $this->load->view('template_claim/top_content',$data);
        $this->load->view('transaction/list_product',$data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
    }

    public function proses_to_cart()
    {
        $kodeprod = $this->input->post('options');

        if ($kodeprod == '') {
            echo "<script>alert('Silahkan pilih produk terlebih dahulu !'); </script>";
            redirect("transaction",'refresh');
            die;
        }

        $tgl = $this->model_outlet_transaksi->timezone();
        foreach($kodeprod as $kode)
        {
            $this->db->select('supp');
            $this->db->where('kodeprod', $kode);
            $prod = $this->db->get('mpm.tabprod')->row();

            $signature = md5($this->session->userdata('id').$prod->supp.substr($tgl,0,10));
            // var_dump($prod->supp);die;

            $this->db->select('id, kodeprod, qty, signature');
            $this->db->where('kodeprod', $kode);
            $this->db->where('signature', $signature);
            $temp_po = $this->db->get('site.t_temp_po')->row();


            $input = [
                'kodeprod' => $kode,
                'supp' => $prod->supp,
                'signature' => $signature,
                'created_date' => $tgl,
                'created_by' => $this->session->userdata('id')
            ];

            if ($temp_po) {
                # edit
                $input['id'] = $temp_po->id;
                $input['qty'] = $temp_po->qty+1;
                $proses = $this->model_order->edit('site.t_temp_po', $input);
            } else {
                # input
                $input['qty'] = '1';
                $proses = $this->model_order->tambah('site.t_temp_po', $input);
            }
        }

        redirect("transaction/cart/$signature/$prod->supp");
    }

    public function cart(){
        $signature = $this->uri->segment('3');
        $supp = $this->uri->segment('4');
        $data = [
            'id' => $this->session->userdata('id'),
            'url' => "transaction/proses_to_checkout/$signature/$supp",
            'title' => 'Cart Product',
            'get_label' => $this->M_menu->get_label(),
            'supp' => $supp,
            'get_data_cart' => $this->model_order->get_data_cart($signature)->result(),

        ];
        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar',$data);
        $this->load->view('template_claim/top_content',$data);
        $this->load->view('transaction/cart',$data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');

    }

    public function delete_cart()
    {
        $signature = $this->uri->segment('3');
        $id_product = $this->uri->segment('4');
        // var_dump($id_product);die;

        $this->db->where('id', $id_product);
        $this->db->delete('site.t_temp_po');

        redirect("transaction/cart/$signature");
    }

    public function reset_cart()
    {
        $signature = $this->uri->segment('3');
        // var_dump($id_product);die;

        $this->db->where('signature', $signature);
        $this->db->delete('site.t_temp_po');

        redirect("transaction/list_product");
    }

    public function proses_to_checkout(){
        $signature = $this->uri->segment('3');
        $supp = $this->uri->segment('4');

        // cek traffic
        // $traffic = $this->model_transaction->get_traffic_po();
        // if ($traffic->num_rows() > 0) {

        //     $status_traffic = $traffic->row()->status;
        //     $created_by = $traffic->row()->created_by;
        //     if ($status_traffic == 1) {
        //         if ($created_by != $this->session->userdata('id')) {
        //             $this->session->set_flashdata("pesan", "Server sedang sibuk, anda masih dalam antrian. Silahkan coba lagi nanti");
        //             redirect('transaction/cart/'.$signature.'/'.$supp);
        //             die;
        //         } 
        //     }else{
        //         $insert_traffic = $this->model_transaction->insert_traffic_po($this->session->userdata('username'), $this->session->userdata('id'), 1);
        //     }

        // }else{
        //     $insert_traffic = $this->model_transaction->insert_traffic_po($this->session->userdata('username'), $this->session->userdata('id'), 1);
        // }

        $amount = $this->input->post('amount');
        foreach( $amount as $key=>$value ) {
            $input =[
                'id' => $key,
                'qty' => $value,
                'status_sudah_updated' => '1'
            ];

            // die;
            $this->db->where('id', $key);
            $this->db->where('signature', $signature);
            $proses = $this->db->update('site.t_temp_po', $input);
        }
        redirect("transaction/checkout/$signature/$supp");
    }

    public function checkout(){
        $signature = $this->uri->segment('3');
        $supp = $this->uri->segment('4');

        if ($signature == '') {
            redirect("transaction");
        }

        $customer = $this->input->post('customer');
        $traffic = $this->model_transaction->get_traffic_po();
        if ($traffic->num_rows() > 0) {
            $date = $this->model_transaction->get_traffic_po()->row()->created_at;
            $currentDate = strtotime($date);
            $futureDate = $currentDate+(60*5);
            $countdown = date("Y-m-d H:i:s", $futureDate);
        }

        $data = [
            'id'                => $this->session->userdata('id'),
            'url'               => "transaction/checkout/$signature/$supp",
            'url2'              => "transaction/simpan/$signature",
            'signature'         => $signature,
            'supp'              => $supp,
            'title'             => 'Checkout',
            'get_label'         => $this->M_menu->get_label(),
            'client'            => $this->model_order->getCustInfo($customer)->row(),
            'alamat'            => $this->model_order->getCustInfo($customer)->result(),
            'list_client'       => $this->model_order->list_client()->result(),
            // 'list_client'    => $this->model_po->get_company()->result(),
            'get_data_cart'     => $this->model_order->get_data_cart($signature)->result(),
            'get_total_berat'   => $this->model_order->get_total_berat($signature)->row(),
            // 'countdown'         => $countdown,
        ];

        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar',$data);
        $this->load->view('template_claim/top_content',$data);
        $this->load->view('transaction/checkout',$data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
    }

    public function reset_checkout(){
        $signature = $this->uri->segment('3');
        $supp = $this->uri->segment('4');
        // var_dump($id_product);die;

        // $insert_traffic = $this->model_transaction->insert_traffic_po($this->session->userdata('username'), $this->session->userdata('id'), 0);

        redirect("transaction/cart/$signature/$supp");
    }

    public function simpan()
    {
        $signature = $this->uri->segment('3');
        $userid = $this->input->post('userid');
        $company=$this->input->post('company');
        $npwp=$this->input->post('npwp');
        $email=$this->input->post('email');
        $kode_alamat=$this->input->post('kode_alamat');
        $tipe=$this->input->post('tipe');

        $supp = $this->input->post('supp');
        // echo "supp : ".$supp;
        // die;
        if ($tipe == '' ) {
            $tipex = 'S';
        } else {
            $tipex = $tipe;
        }

        if ($kode_alamat == '' || $company == '' || $npwp == '' || $email == '' ){
            echo "<script>
            alert('Pesanan tidak mengandung data yang kosong. Harap lengkapi data anda terlebih dahulu !');
            window.location.href='../../transaction/checkout/$signature';
            </script>";
        }else {
            $prod_row = $this->model_order->getPo_temp($signature)->row();

            if ($supp == '005') {
                $cek_mapping_dc = "
                select *
                from site.map_dc_po a
                where a.site_code = '$kode_alamat'
            ";

            // echo "<pre>";
            // print_r($cek_mapping_dc);
            // // echo "supp : ".$supp;
            // echo "</pre>";

            // die;

                $proses_cek_mapping_dc = $this->db->query($cek_mapping_dc)->num_rows();
                if ($proses_cek_mapping_dc) {
                    $sql="
                    select company,npwp, address, email, b.alamat, b.kode_alamat, c.alamat_kirim
                    from mpm.user a LEFT JOIN mpm.t_alamat b
                        on a.username = b.username LEFT JOIN(
                            select a.site_code, a.alamat_kirim
                            from site.map_dc_po a
                            where a.site_code =  '$kode_alamat'
                        )c on b.kode_alamat = c.site_code
                    WHERE id=$userid and b.kode_alamat = '$kode_alamat'";
                }else{
                    $sql="  select company,npwp, address,email, b.alamat, null as alamat_kirim, b.kode_alamat from mpm.user a
                    LEFT JOIN mpm.t_alamat b on a.username = b.username
                    WHERE id=$userid and b.kode_alamat = '$kode_alamat'";
                }
            }else{
                $sql="  select company,npwp, address,email, b.alamat, null as alamat_kirim, b.kode_alamat from mpm.user a
                    LEFT JOIN mpm.t_alamat b on a.username = b.username
                    WHERE id=$userid and b.kode_alamat = '$kode_alamat'";
            }



            // echo "<pre>";
            // print_r($sql);
            // echo "</pre>";

            // die;

            $row=$this->db->query($sql)->row();

            // var_dump($prod_row);die;
            $data_po = [
                'userid' => $userid,
                'supp' => $prod_row->supp,
                'company' => $row->company,
                'npwp' => $row->npwp,
                'email' => $row->email,
                'alamat' => $row->alamat,
                'alamat_kirim' => $row->alamat_kirim,
                'kode_alamat' => $kode_alamat,
                'tglpesan' => $prod_row->created_date,
                'tipe' => $tipex,
                'created' => $prod_row->created_date,
                'created_by' => $prod_row->created_by,
            ];
            $proses = $this->model_order->tambah('mpm.po', $data_po);

            $this->db->select('id');
            $this->db->where('created', $prod_row->created_date);
            $this->db->where('userid', $userid);
            $po = $this->db->get('mpm.po')->row();

            $prod_result = $this->model_order->getPo_temp($signature)->result();

            // var_dump($prod_result);die;
            foreach ($prod_result as $a)
            {
                # code...
                $data_po_detail = [
                    'id_ref' => $po->id,
                    'supp' => $a->supp,
                    'kodeprod' => $a->kodeprod,
                    'namaprod' => $a->namaprod,
                    'kode_prc' => $a->kode_prc,
                    'banyak' => ($a->qty*$a->isisatuan),
                    'banyak_karton' => $a->qty,
                    'harga' => ($a->h_dp),
                    'berat' => ($a->berat),
                    'volume' => ($a->volume),
                    'userid' => $userid,
                ];
                $proses = $this->model_order->tambah('mpm.po_detail', $data_po_detail);
            }

            $this->db->where('signature', $signature);
            $this->db->delete('site.t_temp_po');

            // $insert_traffic = $this->model_transaction->insert_traffic_po($this->session->userdata('username'), $this->session->userdata('id'), 0);

            if ($this->session->userdata('id') == '442' || $this->session->userdata('id') == '588'  ) {
                # code...
                redirect('transaction/list_order');
            } else {
                # code...
                redirect('transaction/konfirmasi_po');
            }

        }
    }

    public function konfirmasi_po(){

        $get_kode_alamat = $this->model_inventory->get_kode_alamat();
        $code = '';
        foreach ($get_kode_alamat as $key) {
            $code.= ","."'".$key->kode_alamat."'";
        }

        $kode_alamat = preg_replace('/,/', '', $code,1);
        // echo $kode_alamat;

        $data = [
            'id'            => $this->session->userdata('id'),
            'title'         => 'Konfirmasi PO',
            'get_label'     => $this->M_menu->get_label(),
            'get_po'        => $this->model_inventory->get_po($kode_alamat),
        ];

        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar',$data);
        $this->load->view('template_claim/top_content',$data);
        $this->load->view('transaction/konfirmasi_po',$data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
    }

    public function konfirmasi_po_detail($supp,$id){

        $data = [
            'id'            => $this->session->userdata('id'),
            'url'           => 'transaction/konfirmasi_po_detail_proses/',
            'title'         => 'Konfirmasi PO',
            'get_label'     => $this->M_menu->get_label(),
            'get_do'        => $this->model_inventory->get_do($supp,$id),
        ];

        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar',$data);
        $this->load->view('template_claim/top_content',$data);
        $this->load->view('transaction/konfirmasi_po_detail',$data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
    }

    public function konfirmasi_po_detail_proses(){

        $request = $this->input->post('options');
        $code = '';
        foreach($request as $kode)
        {
            $code.=",".$kode;
        }

        // var_dump($request);

        $btn_terima = $this->input->post('btn_terima');
        $btn_cancel = $this->input->post('btn_cancel');

        if ($btn_terima == 1) {
            $btn_status = 1;
        }elseif($btn_cancel == 1){
            $btn_status = 0;
        }

        $data = [
            'id_po' => $this->input->post('id_po'),
            'supp' => $this->input->post('supp'),
            'tanggal_terima' => $this->input->post('tanggal_terima'),
            'btn_status' => $btn_status,
            // 'id_detail'  => preg_replace('/,/', '', $code,1),
            'kodeprod'  => preg_replace('/,/', '', $code,1),

        ];
        $proses = $this->model_inventory->konfirmasi_po_detail_proses($data);
    }

    public function export_order()
    {
        $id = $this->session->userdata('id');
        $tahun = substr($this->input->post('date'),0,4);
        $bulan = substr($this->input->post('date'),5,2);
        // var_dump($bulan);die;
        $sql = "
        select 	a.userid, a.nopo, c.NAMASUPP, a.tglpo, day(a.tglpo) as 'tanggal',
                a.tglpesan, a.tipe, a.alamat, b.kodeprod, b.kode_prc, b.banyak
        from    mpm.po a INNER JOIN mpm.po_detail b
                    on a.id = b.id_ref
                INNER JOIN mpm.tabsupp c
                            on a.supp = c.SUPP
        where 	year(a.tglpo) = $tahun and month(a.tglpo) = $bulan and a.userid = $id
                and a.deleted = 0 and b.deleted = 0 and a.nopo not like '/MPM%'

        ";
        $quer = $this->db->query($sql);
        query_to_csv($quer,TRUE,'export po per dp.csv');

    }

    public function download_pdf()
    {
        $this->load->library('mypdf');
        $id = $this->uri->segment('3');
        // var_dump($id);die;

        $this->db->select('userid','supp');
        $this->db->where('id', $id);
        $po = $this->db->get('mpm.po')->row();
        // var_dump($po->supp);die;
        if ($po->supp != '012') {
            $data = [
                "header" => $this->model_order->pdf($id, $po->supp)->row(),
                "detail" => $this->model_order->pdf($id, $po->supp )->result()
            ];

            $query_lulyana = "
                select a.userid, a.company, a.kode_alamat
                from mpm.po a INNER JOIN
                (
                    select a.kode
                    from db_po.t_mapping_kode_po_deltomed a
                    where a.kode in ('LL101', 'LL202', 'LL303')
                )b on a.kode_alamat = b.kode
                where a.id = $id
            ";

            $cek_lulyana = $this->db->query($query_lulyana)->num_rows();
            if ($cek_lulyana > 0) {
                $generate_pdf = $this->mypdf->generate('transaction/template_pdf_lulyana',$data,"po_$id",'A4','portrait');
            }else{
                $generate_pdf = $this->mypdf->generate('transaction/template_pdf',$data,"po_$id",'A4','portrait');
            }



        }else {
            $data = [
                "header" => $this->model_order->pdf($id,$po->supp)->row(),
                "detail" => $this->model_order->pdf($id,$po->supp)->result()
            ];
            $generate_pdf = $this->mypdf->generate('transaction/template_pdf_intrafood',$data,"po_$id",'A4','portrait');
        }
    }

    public function download_pdf_by_date()
    {
        $from = $this->input->post('from');
        $to = $this->input->post('to');
        // var_dump($from);
        $this->load->library('mypdf');

        $this->db->select('id');
        $this->db->where('tglpesan >=', $from);
        $this->db->where('tglpesan <=', $to);
        $po_id = $this->db->get('mpm.po')->result();

        foreach ($po_id as $a) {
            # code...
            $this->db->select('supp');
            $this->db->where('id', $a->id);
            $po = $this->db->get('mpm.po')->row();
            // var_dump($po->supp);die;
            if ($po->supp != '012') {
                $data = [
                    "header" => $this->model_order->pdf($a->id, $po->supp)->row(),
                    "detail" => $this->model_order->pdf($a->id, $po->supp )->result()
                ];

                $query_lulyana = "
                    select a.userid, a.company, a.kode_alamat
                    from mpm.po a INNER JOIN
                    (
                        select a.kode
                        from db_po.t_mapping_kode_po_deltomed a
                        where a.kode in ('LL101', 'LL202', 'LL303')
                    )b on a.kode_alamat = b.kode
                    where a.id = $a->id
                ";

                $cek_lulyana = $this->db->query($query_lulyana)->num_rows();
                if ($cek_lulyana > 0) {
                    $generate_pdf = $this->mypdf->download_pdf('transaction/template_pdf_lulyana',$data,"po_$a->id",'A4','portrait');
                }else{
                    $generate_pdf = $this->mypdf->download_pdf('transaction/template_pdf',$data,"po_$a->id",'A4','portrait');
                }

            }else {
                $data = [
                    "header" => $this->model_order->pdf($a->id,$po->supp)->row(),
                    "detail" => $this->model_order->pdf($a->id,$po->supp)->result()
                ];
                $generate_pdf = $this->mypdf->download_pdf('transaction/template_pdf_intrafood',$data,"po_$a->id",'A4','portrait');
            }
        }

        $filename = 'PO' . md5(date("d-m-Y H:i:s"));
        $zip = new ZipArchive();
        foreach ($po_id as $value) {
        if ($zip->open("assets/file/pdf/$filename.zip", ZipArchive::CREATE) === TRUE) {

                $zip->addFile("assets/file/pdf/po_$value->id.pdf");
                $zip->close();
            }
        }

        redirect("assets/file/pdf/$filename.zip");
    }

    public function list_order()
    {
        $log = [
            'userid'    => $this->session->userdata('id'),
            'menu'      => $this->uri->segment('2'),
            'created_at'=> $this->model_outlet_transaksi->timezone()
        ];
        $this->db->insert('site.log_kunjungan_website', $log);

        $id = $this->session->userdata('id');

        $userid = $this->input->post('company');
        $data = [
            'id' => $this->session->userdata('id'),
            'url' => 'transaction/cart/',
            'url1' => 'transaction/list_order',
            'title' => 'List Order',
            'get_label' => $this->M_menu->get_label(),
            'query' => $this->model_po->get_company_all(),
        ];

        if ($userid == null) {
            $data['hasil'] = $this->model_order->list_order()->result();
        } else {
            $data['hasil'] = $this->model_po->list_order_company($userid);
        }

        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar',$data);
        $this->load->view('template_claim/top_content',$data);
        $this->load->view('transaction/list_order',$data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
    }

    public function list_order_detail()
    {
        $id_po = $this->uri->segment('3');
        $supp = $this->uri->segment('4');
        if ($id_po == '') {
            redirect('transaction/list_order');
        }
        // $supp = '001';
        $data = [
            'id' => $this->session->userdata('id'),
            'url' => 'transaction/tambah_product/',
            'url_po' => 'transaction/submit_po/',
            'url_approval' => 'transaction/proses_approval/',
            'title' => 'List Order Detail',
            'get_label' => $this->M_menu->get_label(),
            'order_detail' => $this->model_order->list_order_detail($id_po)->row(),
            'order_produk' => $this->model_order->list_order_produk($id_po)->result(),
            'list_product' => $this->model_po->list_product_supp_admin($id_po,$supp),
        ];
        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar',$data);
        $this->load->view('template_claim/top_content',$data);
        $this->load->view('transaction/list_order_detail',$data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
    }

    public function proses_doi($id_po,$tahun,$bulan,$tglpesan,$kode,$supp)
    {
        $created = $this->model_outlet_transaksi->timezone();
        $data['proses'] = $this->model_order->proses_doi($id_po,$tahun,$bulan,$tglpesan,$kode,$supp,$created);
    }

    public function tambah_product(){

        // die;
        $data = [
            'id_po' => $this->input->post('id_po'),
            'kodeprod' => $this->input->post('kodeprod'),
            'jumlah' => $this->input->post('jumlah'),
            'userid' => $this->input->post('userid'),
        ];
        $produk = $this->model_order->getProductDetail($data['kodeprod'], $data['userid'])->row();

        $po_detail = [
            'id_ref' => $data['id_po'],
            'kodeprod' => $data['kodeprod'],
            'banyak_karton' => $data['jumlah'],
            'namaprod' => $produk->namaprod,
            'kode_prc' => $produk->kode_prc,
            'banyak' => $produk->isisatuan*$data['jumlah'],
            'harga' => $produk->harga,
            'supp' => $produk->supp,
            'berat' => $produk->berat,
            'volume' => $produk->volume,
            'userid' => $this->session->userdata('id'),
        ];

        // var_dump($po_detail);die;
        $this->db->select('id','id_ref','kodeprod');
        $this->db->where('id_ref',$data['id_po']);
        $this->db->where('kodeprod',$data['kodeprod']);
        $count = $this->db->get('mpm.po_detail')->row();
        // var_dump($count->id);die;
        if (count($count->id) <= 0) {
            $proses_po_detail = $this->model_order->tambah('mpm.po_detail', $po_detail);
        }else{
            $po_detail['id'] = $count->id;
            $po_detail['deleted'] = 0;
            $proses_po_detail = $this->model_order->edit('mpm.po_detail', $po_detail);

        }

        $po = [
            'id' => $data['id_po'],
            'lock' => null,
            'open' => null,
            'open_by' => null,
            'status' => null,
            'status_approval' => null,
            'alasan_approval' => null,
        ];
        $proses_po = $this->model_order->edit('mpm.po', $po);

        redirect("transaction/list_order_detail/".$data['id_po']."/".$produk->supp);
    }

    public function proses_approval()
    {
        $id_po = $this->input->post('id_po');
        $supp = $this->input->post('supp');
        $data = [
            'id' => $id_po,
            'alasan_approval' => $this->input->post('alasan_approval'),
            'status_approval' => '1',
            'status' => '2'
        ];

        // var_dump($data);die;
        $proses = $this->model_order->edit('mpm.po', $data);
        if ($proses) {
            echo "<script>
			alert('update approval berhasil dan terkirim ke Finance');
			window.location.href='../transaction/list_order_detail/$id_po/$supp';
			</script>";
        }else{
            echo "<script>
			alert('update approval gagal');
			window.location.href='../transaction/list_order_detail/$id_po/$supp';
			</script>";
        }
    }

    public function unlock_finance($id_po,$supp)
    {
        $data = [
            'id' => $id_po,
            'lock' => null,
            'open' => null,
            'open_by' => null,
            'status' => null,
            'status_approval' => null,
            'alasan_approval' => null,
        ];
        $proses = $this->model_order->edit('mpm.po', $data);

        if ($proses) {
            redirect('transaction/list_order_detail/'.$id_po.'/'.$supp);
        }else{
            return array();
        }
    }

    public function generate_new($id_po)
    {
        $tanggal = $this->model_outlet_transaksi->timezone();
        $tahun = date('Y', strtotime($tanggal));
        $bulan = date('m', strtotime($tanggal));
        // var_dump($bulan);die;

        $sql = "select a.`open`, a.supp from mpm.po a where a.id = $id_po";
        $proses = $this->db->query($sql)->row();
        $status_open = $proses->open;
        $supp = $proses->supp;
        // var_dump($status_open);die;

        if ($status_open != '1') {
            echo "<script>alert('Generate nopo Gagal karena order belum di open oleh finance'); </script>";
            redirect('transaction/list_order_detail/'.$id_po.'/'.$supp,'refresh');
        }else{
            //cek apakah PO dari PT.DJIGOLAK
            $sql_cek_userid = "
                select userid
                from mpm.po
                where id = $id_po
            ";
            $cek_userid = $this->db->query($sql_cek_userid)->row();
            $userid = $cek_userid->userid;

            if ($supp == '001')
            {
                $supp_kode = 'DL';
                $tambahan = "";
            }elseif($supp =='002'){
                $supp_kode ='PK';
                $tambahan = "";
            }elseif($supp =='012'){
                $supp_kode ='IF';
                $tambahan = "";
            }elseif($supp =='005')
            {
                if ($userid == '233')
                {
                    $supp_kode_dgl = 'FCB';
                    $supp_kode = 'FC';
                    $tambahan = "";
                }else{
                    $supp_kode = 'FC';
                    $tambahan = " and userid <> '233' or supp_sort ='FCB'";
                }
            }elseif($supp == '004'){
                $supp_kode = 'SJ';
                $tambahan = "";
            }elseif($supp == '010'){
                $supp_kode = 'AS';
                $tambahan = "";
            }elseif($supp == '013'){
                $supp_kode = 'ST';
                $tambahan = "";
            }elseif($supp == '014'){
                $supp_kode = 'HN';
                $tambahan = "";
            }elseif($supp == '015'){
                $supp_kode = 'MD';
                $tambahan = "";
            }elseif($supp == '023'){
                $supp_kode = 'UI';
                $tambahan = "";
            }elseif($supp == '025'){
                $supp_kode = 'GP';
                $tambahan = "";
            }elseif($supp == '026'){
                $supp_kode = 'GS';
                $tambahan = "";
            }


            //default = 4,3
            $sql = "
                select nopo_sort
                FROM
                (
                    select  nopo, SUBSTR(nopo,3,4) as nopo_sort, YEAR(open_date) as tahun_sort,SUBSTR(nopo,1,2) as supp_sort,
                            month(tglpo) as bulan_sort,
                            tipe, `open`,  company, supp, id, open_date,userid
                    from    mpm.po
                    where   supp = '$supp' and nopo not like 'fcb%'
                    ORDER BY id desc, open_date desc
                )a where tahun_sort = $tahun and bulan_sort = $bulan and supp_sort ='$supp_kode' ".$tambahan."
                ORDER BY nopo_sort desc, id desc
                limit 1
                ";

            // echo "<pre>";
            // print_r($sql);
            // echo "</pre>";

            // die;

            $cek = $this->db->query($sql);
            $jumlah = $cek->num_rows();
            // var_dump($jumlah);die;
            foreach ($cek->result() as $ck) {
                $nodaftar = $ck->nopo_sort;
            }

            //kondisi jika jumlah lebih dari nol dan kurang dari 1 :
            if ($jumlah <> 0)
            {
                $kode = intval($nodaftar)+1;
            }else{
                $kode = 1;
            }

            if ($userid == '233')//jika po djigolak
            {
                $kodemax = str_pad($kode, 3, "0", STR_PAD_LEFT);
                $kode_join = "$supp_kode_dgl$kodemax";
            }else{
                $kodemax = str_pad($kode, 4, "0", STR_PAD_LEFT);
                $kode_join = "$supp_kode$kodemax";
            }

            redirect('transaction/list_order_detail/'.$id_po.'/'.$supp.'/'.$kode_join);
        }
    }

    public function override_alamat($id_po, $supp, $nopo)
    {
        $data = [
            'id' => $id_po,
            'alamat_kirim' => 'jalan abc surabaya',
            'status_override' => '1'
        ];

        $proses = $this->model_order->edit('mpm.po', $data);
        if ($proses) {
            echo "<script>alert('override berhasil'); </script>";
            redirect("transaction/list_order_detail/$id_po/$supp/$nopo",'refresh');
        }else{
            echo "<script>alert('override gagal'); </script>";
            redirect("transaction/list_order_detail/$id_po/$supp/$nopo",'refresh');
        }
    }

    public function submit_po()
    {
        $nopo = $this->input->post('nopo');
        if ($nopo == '' || $nopo == null) {
            echo "<script>alert('Nomor PO tidak boleh kosong'); </script>";
            redirect('all_po/list_order','refresh');
        }

        $tgl = $this->model_outlet_transaksi->timezone();
        $data = [
            'id' => $this->input->post('id_po'),
            'nopo' => $this->input->post('nopo'),
            'alamat' => $this->input->post('alamat_kirim'),
            'note' => $this->input->post('note'),
            'po_ref' => $this->input->post('po_ref'),
            'tglpo' => $tgl,
            'modified' => $tgl,
            'modified_by' => $this->session->userdata('id')
        ];

        $proses = $this->model_order->edit('mpm.po', $data);
        if ($proses) {
            echo "<script>
			alert('Update po berhasil');
			window.location.href='../transaction/list_order';
			</script>";
        }else{
            echo "<script>
			alert('Update po Gagal. Harap ulangi kembali atau hubungi IT');
			window.location.href='../transaction/list_order';
			</script>";
        }
    }

    function delete_product($id_po, $supp , $id_kodeprod)
    {
        $sql_delete = "
            update mpm.po_detail a
            set a.deleted = 1
            where a.id_ref = $id_po and a.id = $id_kodeprod
        ";
        $proses = $this->db->query($sql_delete);

        if ($proses) {
            echo "<script>alert('Delete Produk Berhasil'); </script>";
            redirect("transaction/list_order_detail/$id_po/$supp",'refresh');
        }else{
            echo "<script>alert('Delete Produk Gagal'); </script>";
            redirect("transaction/list_order_detail/$id_po/$supp",'refresh');
        }
    }

    function delete_po($id_po)
    {
        $sql = "
            update mpm.po a
            set a.deleted = 1
            where a.id = $id_po
        ";
        $proses = $this->db->query($sql);

        if ($proses) {
            echo "<script>alert('Delete Produk Berhasil'); </script>";
            redirect("transaction/list_order",'refresh');
        }else{
            echo "<script>alert('Delete Produk Gagal'); </script>";
            redirect("transaction/list_order",'refresh');
        }
    }
}