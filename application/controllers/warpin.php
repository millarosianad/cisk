<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Warpin extends MY_Controller
{
    function warpin()
    {
        $logged_in = $this->session->userdata('logged_in');
        if (!isset($logged_in) || $logged_in != TRUE) {
            redirect('login/', 'refresh');
        }
        set_time_limit(0);
        $this->load->library(array('table', 'template', 'form_validation', 'email'));
        $this->load->helper('url');
        $this->load->helper('csv');
        // $this->load->model('m_dc');
        $this->load->model(array('M_menu', 'model_outlet_transaksi', 'model_warpin'));
        $this->load->database();
    }

    public function test()
    {
        echo "test";

        $this->model_warpin->test_api();
    }

    public function test_post()
    {

        // curl --location --request POST '{{API_ENDPOINTS_URL}}/webhook/v1/listcabang' \
        // --header 'X-Client-ID: {encode_base64_client_id}' \
        // --header 'Authorization: Bearer {access_token}' \
        // --header 'Content-Type: application/json' \
        // --data-raw '{
        // "latitude": -6.921075262113815,
        // "longitude": 107.7093477427403,
        // "postal_code": "40614"
        // }'

        $latitude = '0';
        $longitude = '0';
        $postal_code = '0';
        $url = 'https://midas-staging.warungpintar.co/webhook/v1/listcabang/';
        $curlHandle = curl_init();
        curl_setopt($curlHandle, CURLOPT_URL, $url);
        // curl_setopt($curlHandle, CURLOPT_HEADER, 0);

        $headers = array(
            'Content-Type:application/json',
            'Authorization: Basic'
        );
        curl_setopt($curlHandle, CURLOPT_HTTPHEADER, $headers);

        // $headers = [
        //     'Cache-Control: no-cache',
        //     'Postman-Token: <calculated when request is sent>',
        //     'Content-Type: multipart/form-data; boundary=<calculated when request is sent>',
        //     'Content-Length: <calculated when request is sent>',
        //     'Host: <calculated when request is sent>',
        //     'User-Agent: Mozilla/5.0 (X11; Ubuntu; Linux i686; rv:28.0) Gecko/20100101 Firefox/28.0',
        //     'Accept: */*',
        //     'Accept-Encoding: gzip, deflate, br',
        //     'Authorization : Basic eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImtpZCI6Ilp1WFFkMVRldmNmWGk2VEJNdlptWmdWVUpVVlYxYWpTIn0.eyJpYXQiOjE2NjUzODczMzIsImV4cCI6MTY2NTM4NzMzNywiYXVkIjoiTVBNIn0.IFzQiiL34st6GdS7IF2iBs0y-LPpZu7FnHe5oZ4r3GU'
        // ];

        // curl_setopt($curlHandle, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curlHandle, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($curlHandle, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curlHandle, CURLOPT_TIMEOUT, 30);
        curl_setopt($curlHandle, CURLOPT_POST, 1);
        curl_setopt($curlHandle, CURLOPT_POSTFIELDS, array(
            'latitude' => $latitude,
            'longitude' => $longitude,
            'postal_code' => $postal_code,
        ));
        $results = json_decode(curl_exec($curlHandle), true);
        curl_close($curlHandle);

        print_r($results)['status'];
    }

    public function dashboard()
    {
        $data = [
            'id'        => $this->session->userdata('id'),
            'title'     => 'Warpin Dashboard',
            'url'       => 'dc/proses_dashboard',
            'url_keluar' => 'dc/proses_dashboard_keluar',
            'get_label' => $this->M_menu->get_label(),
            'get_warpin_action'  => $this->model_warpin->get_warpin_action()->result(),
            'get_warpin_log'  => $this->model_warpin->get_warpin_log()->result(),
            'get_warpin_coverage'  => $this->model_warpin->get_warpin_coverage()->result(),
            'get_warpin_order'  => $this->model_warpin->get_warpin_order()->result(),
            'get_warpin_report'  => $this->model_warpin->get_warpin_report()->result(),
        ];
        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar', $data);
        $this->load->view('template_claim/top_content', $data);
        $this->load->view('warpin/dashboard', $data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
    }

    public function order_detail($signature)
    {
        $data = [
            'id'        => $this->session->userdata('id'),
            'title'     => 'Manage Order Warung Pintar',
            'url'       => 'warpin/proses_manual_status',
            'get_label' => $this->M_menu->get_label(),
            'get_warpin_order_detail'  => $this->model_warpin->get_warpin_order_detail($signature)->result(),
            'get_erp_order_status'  => $this->model_warpin->get_erp_order_status($signature)->result()
        ];

        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar', $data);
        $this->load->view('template_claim/top_content', $data);
        $this->load->view('warpin/order_detail', $data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
    }

    public function proses_manual_status()
    {
        $id = $this->input->post('id');
        $status_transaksi_manual = $this->input->post('status_transaksi_manual');
        $status_product_manual = $this->input->post('status_product_manual');
        $total_qty_pemenuhan = $this->input->post('total_qty_pemenuhan');
        $satuan_pemenuhan = $this->input->post('satuan_pemenuhan');
        $total_qty_pemenuhan_pcs = $this->input->post('total_qty_pemenuhan_pcs');
        $satuan_pemenuhan_pcs = $this->input->post('satuan_pemenuhan_pcs');
        $hna_pcs = $this->input->post('hna_pcs');
        $total_harga_pcs = $this->input->post('total_harga_pcs');
        $total_qty_order = $this->input->post('total_qty_order');
        $satuan_order = $this->input->post('satuan_order');

        // var_dump($total_qty_pemenuhan_pcs);
        // die;

        $signature_mpm = $this->input->post('signature');

        if ($status_transaksi_manual == 5) {
            $nama_status = "delivery";
        } else if ($status_transaksi_manual == 6) {
            $nama_status = "terkirim";
        } else if ($status_transaksi_manual == 7) {
            $nama_status = "batal";
        } else if ($status_transaksi_manual == 8) {
            $nama_status = "pending";
        }

        $get_last_erp_status = $this->model_warpin->get_last_erp_status($signature_mpm)->row();
        $from_aplikasi = $get_last_erp_status->from_aplikasi;
        $invoice_aplikasi = $get_last_erp_status->invoice_aplikasi;
        $invoice_sds = $get_last_erp_status->invoice_sds;
        $payment_total = $get_last_erp_status->payment_total;

        $signature = md5($this->model_outlet_transaksi->timezone() . '' . $invoice_aplikasi);

        $data_header = [
            "from_aplikasi" => $from_aplikasi,
            "invoice_aplikasi" => $invoice_aplikasi,
            "signature_mpm" => $signature_mpm,
            "invoice_sds" => $invoice_sds,
            "status_erp" => $status_transaksi_manual,
            "nama_status_erp" => $nama_status,
            "tanggal_update" => $this->model_outlet_transaksi->timezone(),
            "payment_total" => $payment_total,
            "created_at" => $this->model_outlet_transaksi->timezone(),
            "signature" => $signature
        ];

        $proses_header = $this->db->insert('site.t_erp_order_status', $data_header);
        $id_header = $this->db->insert_id();

        // echo $id_header;
        // die;

        for ($i = 0; $i < count($id); $i++) {

            $get_kodeprod_by_id = $this->model_warpin->get_kodeprod_by_id($id[$i])->row();
            $kodeprod = $get_kodeprod_by_id->product_id;

            $get_namaprod_by_kodeprod = $this->model_warpin->get_namaprod_by_kodeprod($kodeprod)->row();
            $namaprod = $get_namaprod_by_kodeprod->namaprod;

            $get_warpin_order_detail = $this->model_warpin->get_warpin_order_detail_by_id($id[$i])->row();
            $product_quantity = $get_warpin_order_detail->product_quantity;

            $data_detail = [
                "id_order_status"           => $id_header,
                "kodeprod"                  => $kodeprod,
                "namaprod"                  => $namaprod,
                "total_qty_order"           => $total_qty_order[$i],
                "satuan_order"              => $satuan_order[$i],
                "total_qty_pemenuhan"       => $total_qty_pemenuhan[$i],
                "satuan_pemenuhan"          => $satuan_pemenuhan[$i],
                "total_qty_pemenuhan_pcs"   => $total_qty_pemenuhan_pcs[$i],
                "satuan_pemenuhan_pcs"      => $satuan_pemenuhan_pcs[$i],
                "hna"                       => $hna_pcs[$i],
                "total_harga"               => $total_harga_pcs[$i],
                "status_cancel"             => $status_product_manual[$i],
                "alasan_cancel"             => '',
                "created_at"                => $this->model_outlet_transaksi->timezone(),
            ];

            $proses_detail = $this->db->insert('site.t_erp_order_status_detail', $data_detail);

            // die;
        }

        redirect('warpin/order_detail/' . $signature_mpm);
    }
}
