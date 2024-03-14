<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require 'vendor/autoload.php';
use GuzzleHttp\Client;
use GuzzleHttp\Psr7;
use GuzzleHttp\Exception\RequestException;

class Model_broadcast extends CI_Model 
{
    function get_contact()
	{
        $id = $this->session->userdata('id');
        $this->db->select('*');
        $this->db->where('created_by',$id);
        return $this->db->get('site.t_broadcast_contact');
    }

    function insert_broadcast($data){
        $message = $data['message'];
        $created_at = $data['created_at'];
        $created_by = $data['created_by'];
        $signature = md5($created_at.$created_by);

        foreach ($data['get_contact'] as $c) {
            $nama = $c->nama;
            $no_wa = $c->no_wa;

            $data= [
                'nama'              => $nama,
                'no_wa'             => $no_wa,
                'message_original'  => $message,
                'created_at'        => $created_at,
                'created_by'        => $created_by,
                'signature'         => $signature
            ];

            $insert = $this->db->insert('site.t_broadcast_history', $data);
        }
        return $signature;
    }

    function update_broadcast($signature){

        $update = "
            update site.t_broadcast_history a
            set a.message_result = REPLACE(a.message_original,'#nama',a.nama)
            where a.signature = '$signature'
        ";

        $proses = $this->db->query($update);
        return $signature;
    }

    function get_broadcast_preview($signature){
        $result = $this->db->get_where('site.t_broadcast_history', array(
            'signature' => $signature,
        ));
        return $result;
    }



    function send_broadcast($data){

        foreach ($data['get_preview'] as $d) {
            
            $message_result = $d->message_result;
            $no_wa = $d->no_wa;

            // echo "message_result : ".$message_result;
            // echo "no_wa : ".$no_wa;

            $userkey = '6ecb7f9537ef';
            $passkey = 'e96c4c8cee6ac177f83477c2';
            $telepon = $no_wa;
            $message = $message_result;
            $url = 'https://console.zenziva.net/wareguler/api/sendWA/';
            $curlHandle = curl_init();
            curl_setopt($curlHandle, CURLOPT_URL, $url);
            curl_setopt($curlHandle, CURLOPT_HEADER, 0);
            curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curlHandle, CURLOPT_SSL_VERIFYHOST, 2);
            curl_setopt($curlHandle, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($curlHandle, CURLOPT_TIMEOUT,30);
            curl_setopt($curlHandle, CURLOPT_POST, 1);
            curl_setopt($curlHandle, CURLOPT_POSTFIELDS, array(
                'userkey' => $userkey,
                'passkey' => $passkey,
                'to' => $telepon,
                'message' => $message
            ));
            $results = json_decode(curl_exec($curlHandle), true);
            curl_close($curlHandle);

            echo "no_wa : ".$no_wa." message_result : ".$message_result."<br>";
            print_r($results)['status'];
        }      
    }

    public function get_cabang()
    {
        $this->db->where('status_aktif', 1);
        return $this->db->get('site.m_site_warpin');
    }

    public function get_cabang_x()
    {
        $sql_cabang = "
            select a.site_code as site_code
            from site.m_site_warpin a
        ";
        $get_cabang = $this->db->query($sql_cabang)->result_array();

        $master_cabang = array();

        foreach ($get_cabang as $a) {
            $a['site_code'] = $a['site_code'];
            $a['master_product'] = array();

            $get_product_site = "
                select a.site_code, 4 as company_id, a.kodeprod, b.company
                from db_master_data.t_product_site a INNER JOIN 
                (
                    select a.site_code, a.branch, a.company, a.subbranch
                    from site.m_site_warpin a 
                )b on a.site_code = b.site_code
                where a.site_code =" . "'" . $a['site_code'] . "'" . "
            ";
            $proses_product = $this->db->query($get_product_site)->result_array();

            foreach ($proses_product as $b) {
                array_push($a['master_product'], $b);
            }

            array_push($master_cabang, $a);
        }

        return $master_cabang;
    }



    public function get_product($kodeprod)
    {
        $query = "
        select  a.kodeprod as sku, a.kode_prc as id, a.namaprod as name, a.apps_images as image_url,
                if(a.apps_status_aktif = 1, 1, 0) as is_active, 
                b.apps_harga_ritel_gt as price, 
                b.apps_harga_ritel_gt as promotion_price, 100 as quantity, a.apps_satuan_online
        from mpm.tabprod a INNER JOIN
        (
            select a.kodeprod, a.apps_harga_ritel_gt
            from mpm.prod_detail_apps a
            where a.tgl = 
            (
                select max(b.tgl)
                from mpm.prod_detail_apps b 
                where a.kodeprod = b.kodeprod
            ) 
        )b on a.kodeprod = b.kodeprod
        where a.apps_status_aktif = 1 and a.kodeprod = '$kodeprod'
        ";
        return $this->db->query($query);
    }

    public function get_product_site($site_code)
    {
        $query = "
        select  a.site_code, 4 as company_id, a.kodeprod, b.company, c.kode_prc, c.namaprod, c.apps_images, c.apps_status_aktif,
			    c.harga_jual_warpin, c.nama_group, c.nama_sub_group, c.satuan_jual_warpin, a.status_aktif_warpin, c.id_satuan_jual_warpin, c.status_aktif
        from db_master_data.t_product_site a INNER JOIN 
        (
                select a.site_code, a.branch, a.company, a.subbranch
                from site.m_site_warpin a 
        )b on a.site_code = b.site_code INNER JOIN
        (
            select 	a.kodeprod, a.kode_prc, a.namaprod, a.apps_images, a.apps_status_aktif, 
                    b.harga_jual_warpin, b.satuan_jual_warpin, a.grup, a.subgroup, c.nama_group, d.nama_sub_group, b.id_satuan_jual_warpin, b.status_aktif
            from mpm.tabprod a INNER JOIN
            (
                select a.kodeprod, a.harga_jual_warpin, a.konversi_warpin_to_pcs, a.satuan_jual_warpin, a.id_satuan_jual_warpin, a.status_aktif 
                from site.m_product_warpin a
                where a.status_aktif = 1
            )b on a.kodeprod = b.kodeprod LEFT JOIN
            (
                select a.kode_group, a.nama_group
                from mpm.tbl_group a
            )c on a.grup = c.kode_group LEFT JOIN
            (
                select a.sub_group, a.nama_sub_group
                from db_produk.t_sub_group a 
            )d on a.subgroup = d.sub_group
        )c on a.kodeprod = c.kodeprod
        where a.site_code = '$site_code'
        limit 2
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        return $this->db->query($query);
    }

    public function get_satuan($kodeprod)
    {
        $this->db->where('kodeprod', $kodeprod);
        return $this->db->get('mpm.tabprod');
    }

    public function get_stock($kodeprod, $site_code, $tahun)
    {
        $query = "
        select  nocab,kodeprod,namaprod,substr(bulan,3) as bulan,
                sum((Saldoawal+masuk_pbk+retur_sal+retur_depo)-(sales+minta_depo)) as stock
        from    data$tahun.st a
        where   kodeprod in ($kodeprod) and kode_gdg in ('pst',1) and a.nocab = right('$site_code',2) and bulan = (                
                    select max(bulan)
                    from data$tahun.st 
                    where a.nocab = right('$site_code',2)
                )
        ";
        return $this->db->query($query);
    }

    public function warpin_eligible($data)
    {

        var_dump($data);
        die;


        // $client = new Client([
        //     'headers' => [
        //         'Authorization' => 'Basic eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImtpZCI6Ilp1WFFkMVRldmNmWGk2VEJNdlptWmdWVUpVVlYxYWpTIn0.eyJpYXQiOjE2NjUzODczMzIsImV4cCI6MTY2NTM4NzMzNywiYXVkIjoiTVBNIn0.IFzQiiL34st6GdS7IF2iBs0y-LPpZu7FnHe5oZ4r3GU',
        //         'User-Agent' => 'testing/1.0',
        //         'Accept'     => 'application/json',
        //     ],
        //     'timeout'  => 5,
        // ]);

        // $data = [
        //     "latitude" => -6.921075262113815,
        //     "longitude" => 107.7093477427403,
        //     "postal_code" => 40614
        // ];

        // $response = $client->request('POST', 'https://midas-staging.warungpintar.co/webhook/v1/listcabang', [
        //     'json' => $data
        // ]);
        // $body = $response->getBody();
        // $body_array = json_decode($body);
        // print_r($body_array);
    }

    // Basic eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImtpZCI6IllDUmpUNWw0cWRtdVV6YXBxQTI1R1JHM0lJdGNjak1xIn0.eyJpYXQiOjE2NjYyMzk5OTcsImV4cCI6MTY2NjI0MDAwMiwiYXVkIjoiTUlEQVNfTVBNIn0.ohKym2pD6O4zDYygKzeHJIVMAz8OIEFFi4-rqX75kQM

    public function warpin_store($data)
    {
        $client = new Client([
            'verify' => false,
            'headers' => [
                // 'Authorization' => 'Basic eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImtpZCI6Ilp1WFFkMVRldmNmWGk2VEJNdlptWmdWVUpVVlYxYWpTIn0.eyJpYXQiOjE2NjUzODczMzIsImV4cCI6MTY2NTM4NzMzNywiYXVkIjoiTVBNIn0.IFzQiiL34st6GdS7IF2iBs0y-LPpZu7FnHe5oZ4r3GU',
                'Authorization' => 'Basic eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImtpZCI6IllDUmpUNWw0cWRtdVV6YXBxQTI1R1JHM0lJdGNjak1xIn0.eyJpYXQiOjE2NjYyMzk5OTcsImV4cCI6MTY2NjI0MDAwMiwiYXVkIjoiTUlEQVNfTVBNIn0.ohKym2pD6O4zDYygKzeHJIVMAz8OIEFFi4-rqX75kQM',
                'User-Agent' => 'testing/1.0',
                'Accept'     => 'application/json',
                'Content-Type' => 'application/json',
            ],
            'timeout'  => 5,
        ]);

        $datas = [
            "original_id"    => $data['original_id'],
            "company_id"    => $data['company_id'],
            "name"    => $data['name'],
            "latitude"    => $data['latitude'],
            "longitude"    => $data['longitude'],
            "address"    => $data['address'],
            "contact_name"    => $data['contact_name'],
            "contact_msisdn"    => $data['contact_msisdn'],
            "sla_delivery"    => $data['sla_delivery'],
            "extra"            => $data['extra'],
        ];

        $response = $client->request('POST', 'https://midas.warungpintar.co/webhook/v1/create/store', [
            'json' => $data
        ]);
        $body = $response->getBody();
        $body_array = json_decode($body, true);

        // echo "<pre>";
        // var_dump($body_array);
        // echo "</pre>";
        // die;

        $data_result = [
            "message_success"   => empty($body_array['data']['data']) == true ? 0 : ($body_array['data']['data']),
            "message_error"     => empty($body_array['data']['errors']) == true ? 0 : ($body_array['data']['errors']),
            'endpoint'          => 'store',
            'created_at'        => $this->model_outlet_transaksi->timezone(),
            'created_by'        => $this->session->userdata('id')
        ];

        $this->db->insert('site.log_warpin_api', $data_result);
    }

    public function warpin_product($data)
    {
        $client = new Client([
            'verify' => false,
            'headers' => [
                // 'Authorization' => 'Basic eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImtpZCI6Ilp1WFFkMVRldmNmWGk2VEJNdlptWmdWVUpVVlYxYWpTIn0.eyJpYXQiOjE2NjUzODczMzIsImV4cCI6MTY2NTM4NzMzNywiYXVkIjoiTVBNIn0.IFzQiiL34st6GdS7IF2iBs0y-LPpZu7FnHe5oZ4r3GU',
                'Authorization' => 'Basic eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImtpZCI6IllDUmpUNWw0cWRtdVV6YXBxQTI1R1JHM0lJdGNjak1xIn0.eyJpYXQiOjE2NjYyMzk5OTcsImV4cCI6MTY2NjI0MDAwMiwiYXVkIjoiTUlEQVNfTVBNIn0.ohKym2pD6O4zDYygKzeHJIVMAz8OIEFFi4-rqX75kQM',
                'User-Agent' => 'testing/1.0',
                'Accept'     => 'application/json',
                'Content-Type' => 'application/json',
            ],
            'timeout'  => 5,
        ]);

        $response = $client->request('POST', 'https://midas.warungpintar.co/webhook/v1/product/create', [
            'json' => $data
        ]);
        $body = $response->getBody();
        $body_array = json_decode($body, true);

        $data_result = [
            "message_success"   => empty($body_array['data']) == true ? 0 : ($body_array['data']),
            "message_error"     => empty($body_array['errors']) == true ? 0 : ($body_array['errors']),
            'endpoint'          => 'product',
            'created_at'        => $this->model_outlet_transaksi->timezone(),
            'created_by'        => $this->session->userdata('id')
        ];

        $this->db->insert('site.log_warpin_api', $data_result);
        return $body_array;
    }

    public function warpin_product_update($data)
    {
        $client = new Client([
            'verify' => false,
            'headers' => [
                // 'Authorization' => 'Basic eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImtpZCI6Ilp1WFFkMVRldmNmWGk2VEJNdlptWmdWVUpVVlYxYWpTIn0.eyJpYXQiOjE2NjUzODczMzIsImV4cCI6MTY2NTM4NzMzNywiYXVkIjoiTVBNIn0.IFzQiiL34st6GdS7IF2iBs0y-LPpZu7FnHe5oZ4r3GU',
                'Authorization' => 'Basic eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImtpZCI6IllDUmpUNWw0cWRtdVV6YXBxQTI1R1JHM0lJdGNjak1xIn0.eyJpYXQiOjE2NjYyMzk5OTcsImV4cCI6MTY2NjI0MDAwMiwiYXVkIjoiTUlEQVNfTVBNIn0.ohKym2pD6O4zDYygKzeHJIVMAz8OIEFFi4-rqX75kQM',
                'User-Agent' => 'testing/1.0',
                'Accept'     => 'application/json',
                'Content-Type' => 'application/json',
            ],
            'timeout'  => 5,
        ]);

        $response = $client->request('POST', 'https://midas.warungpintar.co/webhook/v1/product/update', [
            'json' => $data
        ]);
        $body = $response->getBody();
        $body_array = json_decode($body, true);

        $data_result = [
            "message_success"   => empty($body_array['data']) == true ? 0 : ($body_array['data']),
            "message_error"     => empty($body_array['errors']) == true ? 0 : ($body_array['errors']),
            'endpoint'          => 'update_product',
            'created_at'        => $this->model_outlet_transaksi->timezone(),
            'created_by'        => $this->session->userdata('id')
        ];

        $this->db->insert('site.log_warpin_api', $data_result);
        return $body_array;
    }

    public function warpin_stock($data)
    {

        $client = new Client([
            'verify' => false,
            'headers' => [
                // 'Authorization' => 'Basic eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImtpZCI6Ilp1WFFkMVRldmNmWGk2VEJNdlptWmdWVUpVVlYxYWpTIn0.eyJpYXQiOjE2NjUzODczMzIsImV4cCI6MTY2NTM4NzMzNywiYXVkIjoiTVBNIn0.IFzQiiL34st6GdS7IF2iBs0y-LPpZu7FnHe5oZ4r3GU',
                'Authorization' => 'Basic eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImtpZCI6IllDUmpUNWw0cWRtdVV6YXBxQTI1R1JHM0lJdGNjak1xIn0.eyJpYXQiOjE2NjYyMzk5OTcsImV4cCI6MTY2NjI0MDAwMiwiYXVkIjoiTUlEQVNfTVBNIn0.ohKym2pD6O4zDYygKzeHJIVMAz8OIEFFi4-rqX75kQM',
                'User-Agent' => 'testing/1.0',
                'Accept'     => 'application/json',
                'Content-Type' => 'application/json',
            ],
            'timeout'  => 5,
        ]);


        $response = $client->request('POST', 'https://midas.warungpintar.co/webhook/v1/stock', [
            'json' => $data
        ]);
        $body = $response->getBody();
        $body_array = json_decode($body, true);

        $data_result = [
            "message_success"   => empty($body_array['data']) == true ? 0 : ($body_array['data']),
            "message_error"     => empty($body_array['errors']) == true ? 0 : ($body_array['errors']),
            'endpoint'          => 'stock',
            'created_at'        => $this->model_outlet_transaksi->timezone(),
            'created_by'        => $this->session->userdata('id')
        ];

        $this->db->insert('site.log_warpin_api', $data_result);
        return $body_array;
    }

    public function get_nama_status_order($status){
        /*
        tabel status
        status | nama_status | deskripsi  | data_yang_dikirim oleh SDS ke WEB
        1   | transfered  | sds sukses get data dari api dan insert ke temporary    | SPOxxx / kode_dokumen
        2   | posting | sds posting  | SNRxxx
        3   | printed | sds print   | NULL
        4   | do | sds rekap do | SPBxxx
        5   | delivery | sds cetak sj  | SJLxxx
        6   | terkirim  | ekspedisi update status 'terkirim' di sds | tgl_kirim dari t_ekspedisi
        7   | batal | toko menolak | alasan      
        8   | pending | pending pengiriman | alasan
        */
        if ($status == 2) {
            return 'order.confirmed';
        }elseif($status == 3){
            return 'order.confirmed';
        }elseif($status == 4){
            return 'order.confirmed';
        }elseif($status == 5){
            return 'order.delivery';
        }elseif($status == 6){
            return 'order.received.full';
        }elseif($status == 7){
            return 'order.canceled';
        }


    }

    // public function get_data_order($table, $id_order_status){
    //     return $this->db->get_where($table, array('id' => $id_order_status));
    // }
    public function get_data_order($table, $signature){
        // return $this->db->get_where($table, array('signature_mpm' => $signature));
        $query = "
            select * from $table a
            where a.signature_mpm = '$signature'
            order by id desc
            limit 1
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        return $this->db->query($query);
    }

    public function get_data_order_detail($table, $id_order_status){
        // return $this->db->get_where($table, array(
        //     'id_order_status' => $id_order_status,
        //     'alasan_cancel' =>null
        // ));
        $query = "
            select *
            from $table a
            where a.id_order_status = $id_order_status and (a.alasan_cancel is null or a.status_cancel = '' or a.status_cancel = 0)
        ";
        return $this->db->Query($query);
    }

    public function warpin_confirmation($data)
    {


        $client = new Client([
            'verify' => false,
            'headers' => [
                // 'Authorization' => 'Basic eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImtpZCI6Ilp1WFFkMVRldmNmWGk2VEJNdlptWmdWVUpVVlYxYWpTIn0.eyJpYXQiOjE2NjUzODczMzIsImV4cCI6MTY2NTM4NzMzNywiYXVkIjoiTVBNIn0.IFzQiiL34st6GdS7IF2iBs0y-LPpZu7FnHe5oZ4r3GU',
                'Authorization' => 'Basic eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImtpZCI6IllDUmpUNWw0cWRtdVV6YXBxQTI1R1JHM0lJdGNjak1xIn0.eyJpYXQiOjE2NjYyMzk5OTcsImV4cCI6MTY2NjI0MDAwMiwiYXVkIjoiTUlEQVNfTVBNIn0.ohKym2pD6O4zDYygKzeHJIVMAz8OIEFFi4-rqX75kQM',
                'User-Agent' => 'testing/1.0',
                'Accept'     => 'application/json',
                'Content-Type' => 'application/json',
            ],
            'timeout'  => 5,
        ]);


        $response = $client->request('POST', 'https://midas.warungpintar.co/webhook/v1/order/status', [
            'json' => $data
        ]);
        $body = $response->getBody();
        $body_array = json_decode($body, true);

        $data_result = [
            "message_success"   => empty($body_array['data']) == true ? 0 : ($body_array['data']),
            "message_error"     => empty($body_array['errors']) == true ? 0 : ($body_array['errors']),
            'endpoint'          => 'order_status',
            'created_at'        => $this->model_outlet_transaksi->timezone(),
            'created_by'        => $this->session->userdata('id')
        ];

        $this->db->insert('site.log_warpin_api', $data_result);
        return $body_array;
    }
    
    
}