<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_ecommerce extends CI_Model {
    function get_transaksi($invoice = '', $site_code = ''){
        $site = preg_replace('/,/', "','", $site_code);
        // var_dump($site);die;
        if ($invoice == ''){
            // $sql = "
            // SELECT a.id, a.order_id, a.invoice, a.`name`, a.phone, a.address, a.`status`, a.site_code FROM db_master_data.t_orders a 
            // INNER JOIN db_master_data.t_orders_detail b ON a.invoice = b.invoice
            // WHERE a.`server` = 'live' and a.site_code in ('$site')
            // group by order_id
            // order by id desc
            // ";

            $sql = "
            select a.invoice, a.customer_id, a.`name`, a.phone, a.address, a.site_code, a.created_at, a.payment_final, a.status_update_erp, a.last_updated_erp, a.order_id
            from db_master_data.t_orders a
            where a.`server` = 'live'
            ORDER BY a.id desc
            ";

        }else{
            // $sql = "
            // select a.invoice, a.customer_id, a.`name`, a.phone, a.address, a.site_code, a.created_at, a.payment_final, a.status_update_erp, a.last_updated_erp, a.order_id
            // from db_master_data.t_orders a
            // where a.`server` = 'live' and a.invoice = '$invoice'
            // ORDER BY a.id desc
            // ";
            $sql = "
            select  a.product_id, b.kodeprod, b.namaprod, b.apps_namaprod, a.invoice, a.small_qty, a.harga_product, 
                    a.harga_product_konversi, a.qty_konversi, a.item_disc,
                    a.total_price, a.disc_cabang, a.disc_principal, a.disc_extra, a.rp_cabang, a.rp_principal, a.rp_extra, a.bonus, a.bonus_konversi
            from db_master_data.t_orders_detail a LEFT JOIN
            (
                select a.kodeprod, a.namaprod, a.apps_namaprod
                from mpm.tabprod a
            )b on a.product_id = b.kodeprod
            where a.invoice ='$invoice'
            ";
        }
        // var_dump($sql);die;
        $proses = $this->db->query($sql);
        return $proses;
    }
    
    public function get_site_code($username){
        $id = $this->session->userdata('id');
        if($id == 547 || $id == 297 || $id == 231){
            $result = $this->db->get('mpm.t_alamat')->result();
            // $username = 'bgr';
            // $result = $this->db->get_where('mpm.t_alamat',array('username' => $username ))->result();
        }else{
            $result = $this->db->get_where('mpm.t_alamat',array('username' => $username ))->result();
        }
        return $result;
    }

    function get_subbranch($site_code){
        
        $code = explode(",",$site_code);
        $this->db->where_in('site_code', $code);
        $result = $this->db->get('db_master_data.t_site');

        return $result;
    }

    function get_detail_otp($kode_lang){
        
        $this->db->where_in('customer_code', $kode_lang);
        $result = $this->db->get('db_master_data.t_otp')->result();

        return $result;
    }

    function get_log_otp($kode_alamat){
        
        // $result = $this->db->get('db_master_data.t_otp')->result();
        // $sql = "select * from db_master_data.t_otp a order by a.id desc limit 100";
        $sql = "
            select b.branch_name, b.nama_comp, a.customer_code, a.email_phone, a.type, a.otp_code, a.created_at, a.valid_until
            from db_master_data.t_otp a LEFT JOIN
            (
                select a.site_code, left(a.site_code, 3) as kode_comp,  a.branch_name, a.nama_comp
                from db_master_data.t_site a
            )b on left(a.customer_code,3) = b.kode_comp
            where a.`server` = 'live'  and b.site_code in ($kode_alamat)
            ORDER BY a.id desc
        ";
        $result = $this->db->query($sql)->result();

        return $result;
    }

    function get_otp($site_code){
        // var_dump($site_code);die;
        $code = array('TGR39');
        // $code = explode(",",$site_code);
        $this->db->where_in('kode', $code);
        $result = $this->db->get('db_master_data.t_customer');

        return $result;
    }
}