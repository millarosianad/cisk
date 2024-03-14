<?php
defined('BASEPATH') or exit('No direct script access allowed');

require 'vendor/autoload.php';
use GuzzleHttp\Client;
use GuzzleHttp\Psr7;
use GuzzleHttp\Exception\RequestException;

class Model_warpin extends CI_Model
{

    private $_client;

    public function __construct()
    {
        $this->_client = new Client([
            'base_uri'  => 'http://localhost:81/restapi/api/master_data/',
            'auth'      => ['admin','1234']
        ]);
        // $this->_client = new Client([
        //     'base_uri'  => 'https://midas-staging.warungpintar.co/webhook/v1/',
        //     'auth'      => ['admin','1234']
        // ]);
    }

    public function test_api()
    {
        $response = $this->_client->request('GET', 'site', [
            'query' => [
                'X-API-KEY' => '123',
                'token'     => '11f3a8a682c1e8d097ae60d72ecf07c7',
                'kode'      => 'mlg27'
            ]
        ]);

        $result = json_decode($response->getBody()->getContents(),true);

        var_dump($result);

    }

    public function test_api_post()
    {
        $response = $this->_client->request('POST', 'listcabang', [
            'query' => [
                'X-API-KEY' => '123',
                'token'     => '11f3a8a682c1e8d097ae60d72ecf07c7',
                'kode'      => 'mlg27'
            ]
        ]);

        $result = json_decode($response->getBody()->getContents(),true);

        var_dump($result);

    }

    public function get_warpin_action(){
        return $this->db->get('site.t_warpin_action');
    }

    public function get_warpin_log(){
        $this->db->order_by('id','desc');
        return $this->db->get('site.log_warpin_api');
    }

    public function get_warpin_coverage(){
        $query = "
        select a.site_code, a.coverage, a.created_at, b.branch_name, b.nama_comp
        from site.m_warpin_coverage a LEFT JOIN
        (
            select concat(a.kode_comp, a.nocab) as site_code, a.branch_name, a.nama_comp, urutan
            from mpm.tbl_tabcomp a 
            where a.`status` = 1
            GROUP BY concat(a.kode_comp, a.nocab) 
        )b on a.site_code = b.site_code
        ORDER BY b.urutan
        ";
        return $this->db->query($query);
    }

    public function get_warpin_order(){
        return $this->db->get('site.t_warpin_order');
    }

    public function get_erp_order_status($signature)
    {
        // return $this->db->get_where('site.t_erp_order_status', array('signature_mpm' => $signature));
        $query = "
        select 	a.id, a.from_aplikasi, a.invoice_aplikasi, a.signature_mpm, a.invoice_sds, a.status_erp, a.nama_status_erp,
				a.tanggal_update, a.payment_total, a.signature, b.kodeprod, b.namaprod, 
                b.total_qty_pemenuhan, b.satuan_pemenuhan, b.total_qty_pemenuhan, b.satuan_pemenuhan,
                b.total_qty_pemenuhan_pcs, b.satuan_pemenuhan_pcs, b.hna, b.total_harga,
                b.status_cancel, b.alasan_cancel
        from site.t_erp_order_status a LEFT JOIN
        (
            select 	a.id_order_status, a.kodeprod, a.namaprod, a.total_qty_order, a.satuan_order, a.total_qty_pemenuhan, a.satuan_pemenuhan, a.total_qty_pemenuhan_pcs, a.satuan_pemenuhan_pcs, a.hna, a.total_harga, a.status_cancel, a.alasan_cancel, a.created_at
            from site.t_erp_order_status_detail a
        )b on a.id = b.id_order_status
        where a.signature_mpm = '$signature' 
        order by a.id asc
        ";

        // echo "<pre><br><br><br>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function get_warpin_order_detail($signature){
        $query = "
        select b.id, b.product_id, b.sku, b.uom, b.product_quantity, b.price_unit, c.namaprod, a.status_erp, a.signature,
        a.client_so_number, a.merchant_name, a.merchant_shop_name, a.merchant_shop_address, a.merchant_phone, a.merchant_shop_postal_code, d.status_erp, d.nama_status_erp
        from site.t_warpin_order a INNER JOIN 
        (
            select *, if(length(a.product_id = 5), concat('0', a.product_id), a.product_id) as product_id_format
            from site.t_warpin_order_detail a
        )b on a.id = b.id_order LEFT JOIN 
        (
            select a.kodeprod, a.namaprod
            from mpm.tabprod a 
        )c on b.product_id_format = c.kodeprod LEFT JOIN
        (
            select 	a.status_erp, a.nama_status_erp, a.signature_mpm
            from 	site.t_erp_order_status a 
            where 	a.signature_mpm = '9342ff8d414a06a2b3f8b6ecbef1624b' and 
                    a.id = (
                        select 	max(a.id)
                        from 	site.t_erp_order_status a 
                        where 	a.signature_mpm = '9342ff8d414a06a2b3f8b6ecbef1624b' 
                    )
        )d on a.signature = d.signature_mpm
        where a.signature = '$signature'
        ";

        // echo "<pre><br><br><br><br><br><br>";
        // print_r($query);
        // echo "</pre>";

        // die;
        return $this->db->query($query);
    }

    public function get_last_erp_status($signature){
        $query = "
            select *
            from site.t_erp_order_status a
            where a.signature_mpm = '$signature'
            order by id desc
            limit 1
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        // die;

        return $this->db->query($query);
    }

    public function get_kodeprod_by_id($id){
        $query = "
            select if(length(a.product_id = 5), concat('0',a.product_id), a.product_id) as product_id
            from site.t_warpin_order_detail a
            where a.id = $id
        ";
        echo "<pre>";
        print_r($query);
        echo "</pre>";

        return $this->db->query($query);
    }

    public function get_namaprod_by_kodeprod($kodeprod){
        $query = "
            select namaprod
            from mpm.tabprod a
            where a.kodeprod = if(length($kodeprod)=5,concat('0',$kodeprod),$kodeprod)
        ";
        echo "<pre>";
        print_r($query);
        echo "</pre>";

        return $this->db->query($query);
    }

    public function get_warpin_order_detail_by_id($id){
        $query = "
            select * from site.t_warpin_order_detail a
            where a.id = $id
        ";
        return $this->db->query($query);
    }

    public function get_warpin_report(){
        $query = "
        select 	b.branch_name, b.nama_comp, a.client_so_number, 
                a.merchant_shop_name, a.total_order as total_val_order_apps, 
                a.nama_status_erp, payment_total as total_val_sds, 
                a.created_at, a.tanggal_update, c.status_post
        from 
        (
        select 	a.client_so_number, a.store_id, a.merchant_shop_name, b.total_order, c.nama_status_erp, 
                c.status_erp, c.payment_total, c.created_at, c.tanggal_update
        from 	site.t_warpin_order a LEFT JOIN
        (
            select 	a.id, a.id_order, a.client_so_number, a.product_id, a.product_quantity, a.price_unit, 
                    sum(a.product_quantity * a.price_unit) as total_order
            from 	site.t_warpin_order_detail a left join 
            (
                select a.kodeprod, a.harga_jual_warpin
                from site.m_product_warpin a 
            )b on if(length(a.product_id) = 5, concat('0', a.product_id), a.product_id)  = b.kodeprod
            GROUP BY a.id_order
            ORDER BY a.id, a.id_order
        )b on a.id = b.id_order LEFT JOIN
        (
            select a.id, a.invoice_aplikasi, a.status_erp, a.nama_status_erp, a.payment_total, a.from_aplikasi, a.tanggal_update, a.created_at
            from site.t_erp_order_status a
            where a.invoice_aplikasi is not null and a.id = (
                select max(b.id)
                from site.t_erp_order_status b 
                where b.invoice_aplikasi = a.invoice_aplikasi
            ) and a.invoice_aplikasi not like '%test%'
            GROUP BY a.invoice_aplikasi
        )c on a.client_so_number = c.invoice_aplikasi
        where (a.client_so_number not like '%test%' and a.store_id <> 1)
        ORDER BY a.id 
        )a LEFT JOIN 
        (
            select concat(a.kode_comp,a.nocab) as site_code, a.branch_name, a.nama_comp
            from mpm.tbl_tabcomp a 
            where a.`status` = 1
            GROUP BY concat(a.kode_comp,a.nocab)
        )b on a.store_id = b.site_code LEFT JOIN 
        (
            select a.invoice_apps, a.status_post
            from site.t_warpin_status_post a 
        )c on a.client_so_number = c.invoice_apps
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>"; 
        // die;
        return $this->db->query($query);
    }

    
}
