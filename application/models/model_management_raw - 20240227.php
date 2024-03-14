<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_management_raw extends CI_Model 
{
    public function get_raw_draft($site_code, $signature = ''){

        if ($site_code == 'SSMS9') {
            $params_site_code = "raw_banjarmasin"; 
        }elseif ($site_code == 'SSJD2') {
            $params_site_code = "raw_batulicin"; 
        }elseif ($site_code == 'BRBS0') {
            $params_site_code = "raw_barabai"; 
        }elseif ($site_code == 'SMRB7') {
            $params_site_code = "raw_samarinda"; 
        }elseif ($site_code == 'BTGB8') {
            $params_site_code = "raw_bontang"; 
        }elseif ($site_code == 'PTK82') {
            $params_site_code = "raw_pontianak"; 
        }elseif ($site_code == 'CSSK3') {
            $params_site_code = "raw_kendari"; 
        }elseif ($site_code == 'DASD1') {
            $params_site_code = "raw_balikpapan"; 
        }elseif ($site_code == 'MANW5') {
            $params_site_code = "raw_manado"; 
        }elseif ($site_code == 'PBNP9') {
            $params_site_code = "raw_pangkalanbun"; 
        }

        if ($signature) {
            $params = "where a.signature = '$signature'";
        }else{
            $params = "";
        }
        
        
        $query = "
            select * from management_raw.$params_site_code a
            $params
        ";
        return $this->db->query($query);
    }

    public function get_raw_draft_kendari($site_code, $signature = ''){

        if ($site_code == 'CSSK3') {
            $params_site_code = "raw_kendari"; 
        }
        if ($signature) {
            $params = "where a.signature = '$signature'";
        }else{
            $params = "";
        }

        $query = "
            select a.*, b.kodeprod as kodeprod_mpm
            from
            (
                select a.*, if(length(a.kodeprod) = 5, concat(0,a.kodeprod), a.kodeprod) as kodeprodx from management_raw.$params_site_code a
                $params
            )a
            left join 
            (
                select a.code, a.kodeprod from pmu.mapping a
                where a.nocab = 'K3'
            )b on a.kodeprodx = b.kodeprod
            order by b.kodeprod asc
        ";
        return $this->db->query($query);
    }

    public function get_raw_draft_balikpapan($site_code, $signature = ''){

        if ($site_code == 'SSMS9') {
            $params_site_code = "raw_banjarmasin"; 
        }elseif ($site_code == 'SSJD2') {
            $params_site_code = "raw_batulicin"; 
        }elseif ($site_code == 'BRBS0') {
            $params_site_code = "raw_barabai"; 
        }elseif ($site_code == 'SMRB7') {
            $params_site_code = "raw_samarinda"; 
        }elseif ($site_code == 'BTGB8') {
            $params_site_code = "raw_bontang"; 
        }elseif ($site_code == 'PTK82') {
            $params_site_code = "raw_pontianak"; 
        }elseif ($site_code == 'CSSK3') {
            $params_site_code = "raw_kendari"; 
        }elseif ($site_code == 'DASD1') {
            $params_site_code = "raw_balikpapan"; 
        }

        if ($signature) {
            $params = "where a.signature = '$signature'";
        }else{
            $params = "";
        }

        $query = "
            select a.*, b.kodeprod, c.kd_cust_mpm
            from
            (
                select a.* from management_raw.$params_site_code a
                $params
            )a
            left join 
            (
                select a.code, a.KODEPROD from pmu.mapping a
                where a.nocab = 'D1'
            )b on a.kd_produk = b.code
            left join 
            (
                select a.kd_cust, a.kd_cust_mpm from management_raw.raw_customer_balikpapan a
            )c on a.kd_cust = c.kd_cust
            order by b.kodeprod asc
        ";
        return $this->db->query($query);
    }

    public function get_raw_draft_pangkalanbun($site_code, $signature = ''){

        if ($site_code == 'PBNP9') {
            $params_site_code = "raw_pangkalanbun"; 
            $params_customer = "raw_customer_pangkalanbun"; 
        }
        elseif ($site_code == 'SPTU4') {
            $params_site_code = "raw_sampit"; 
            $params_customer = "raw_customer_sampit"; 
        }
        elseif ($site_code == 'PKRP8') {
            $params_site_code = "raw_palangkaraya"; 
            $params_customer = "raw_customer_palangkaraya"; 
        }

        if ($signature) {
            $params = "where a.signature = '$signature'";
        }else{
            $params = "";
        }

        $query = "
            select a.*, b.customer_id as customer_id_mpm
            from
            (
                select a.* from management_raw.$params_site_code a
                $params
            )a
            left join 
            (
                select * from management_raw.$params_customer a
            )b on a.customerid = b.customer_id_nd6
        ";
        return $this->db->query($query);
    }

    public function update_kodeproduk($site_code, $signature){

        if ($site_code == 'SSMS9') {
            $params_site_code = "raw_banjarmasin"; 
        }elseif ($site_code == 'SSJD2') {
            $params_site_code = "raw_batulicin"; 
        }elseif ($site_code == 'BRBS0') {
            $params_site_code = "raw_barabai"; 
        }elseif ($site_code == 'BTGB8') {
            $params_site_code = "raw_bontang"; 
        }

        $query = "
            update management_raw.$params_site_code a 
            set a.kodeproduk = concat(0,a.kodeproduk)
            where a.signature = '$signature' and length(a.kodeproduk) = 5 
        ";
        $update = $this->db->query($query);

        $query2 = "
            update management_raw.$params_site_code a 
            set a.kodeprodukprincipal = concat(0,a.kodeprodukprincipal)
            where a.signature = '$signature' and length(a.kodeprodukprincipal) = 5 
        ";
        $update2 = $this->db->query($query2);

        if ($update2) {
            return $update2;
        }else{
            return array();
        }
    }

    public function update_kodeproduk_bontang($site_code, $signature){

        if ($site_code == 'SSMS9') {
            $params_site_code = "raw_banjarmasin"; 
        }elseif ($site_code == 'SSJD2') {
            $params_site_code = "raw_batulicin"; 
        }elseif ($site_code == 'BRBS0') {
            $params_site_code = "raw_barabai"; 
        }elseif ($site_code == 'BTGB8') {
            $params_site_code = "raw_bontang"; 
        }elseif ($site_code == 'PTK82') {
            $params_site_code = "raw_pontianak"; 
        }

        $query = "
            update management_raw.$params_site_code a 
            set a.productid = concat(0,a.productid)
            where a.signature = '$signature' and length(a.productid) = 5 
        ";
        $update = $this->db->query($query);

        if ($update) {
            return $update;
        }else{
            return array();
        }
    }

    public function update_kodeproduk_left($site_code, $signature){

        if ($site_code == 'SSMS9') {
            $params_site_code = "raw_banjarmasin"; 
        }elseif ($site_code == 'SSJD2') {
            $params_site_code = "raw_batulicin"; 
        }elseif ($site_code == 'BRBS0') {
            $params_site_code = "raw_barabai"; 
        }


        $query = "
            update management_raw.$params_site_code a 
            set a.kodeproduk = left(a.kodeproduk, 6)
            where a.signature = '$signature' and length(a.kodeproduk) > 6 
        ";
        $update = $this->db->query($query);

        $query2 = "
            update management_raw.$params_site_code a 
            set a.kodeprodukprincipal = left(a.kodeprodukprincipal, 6)
            where a.signature = '$signature' and length(a.kodeprodukprincipal) > 6
        ";
        $update2 = $this->db->query($query2);

        if ($update2) {
            return $update2;
        }else{
            return array();
        }
    }

    public function inner_kodeproduk($site_code, $signature){

        if ($site_code == 'SSMS9') {
            $params_site_code = "inner_raw_banjarmasin"; 
            $params_site_code2 = "raw_banjarmasin"; 
        }elseif ($site_code == 'SSJD2') {
            $params_site_code = "inner_raw_batulicin"; 
            $params_site_code2 = "raw_batulicin"; 
        }elseif ($site_code == 'BRBS0') {
            $params_site_code = "inner_raw_barabai"; 
            $params_site_code2 = "raw_barabai"; 
        }elseif ($site_code == 'SMRB7') {
            $params_site_code = "inner_raw_samarinda"; 
            $params_site_code2 = "raw_samarinda"; 
        }

        $query = "
            insert into management_raw.$params_site_code
            select '', '$site_code', '', '', '', a.*, '', '', '', '', '', '', '', '', '', '', b.supp, b.grupprod, '', '', '', ''
            from management_raw.$params_site_code2 a INNER JOIN (
                select a.kodeprod, a.supp, a.grupprod
                from mpm.tabprod a 
            )b on a.kodeprodukprincipal = b.kodeprod
            where a.signature = '$signature'
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        // die;
        $update = $this->db->query($query);
        if ($update) {
            return $update;
        }else{
            return array();
        }
    }

    public function inner_kodeproduk_barabai($site_code, $signature){

        if ($site_code == 'SSMS9') {
            $params_site_code = "inner_raw_banjarmasin"; 
            $params_site_code2 = "raw_banjarmasin"; 
        }elseif ($site_code == 'SSJD2') {
            $params_site_code = "inner_raw_batulicin"; 
            $params_site_code2 = "raw_batulicin"; 
        }elseif ($site_code == 'BRBS0') {
            $params_site_code = "inner_raw_barabai"; 
            $params_site_code2 = "raw_barabai"; 
        }elseif ($site_code == 'SMRB7') {
            $params_site_code = "inner_raw_samarinda"; 
            $params_site_code2 = "raw_samarinda"; 
        }

        $query = "
            insert into management_raw.$params_site_code
            select '', '$site_code', '', '', '', a.*, '', '', '', '', '', '', '', '', '', '', b.supp, b.grupprod, '', '', '', '', '', '', '', '', '', ''
            from management_raw.$params_site_code2 a INNER JOIN (
                select a.kodeprod, a.supp, a.grupprod
                from mpm.tabprod a 
            )b on a.kodeprodukprincipal = b.kodeprod
            where a.signature = '$signature'
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        // die;
        $update = $this->db->query($query);
        if ($update) {
            return $update;
        }else{
            return array();
        }
    }

    public function inner_kodeproduk_banjarmasin($site_code, $signature){

        if ($site_code == 'SSMS9') {
            $params_site_code = "inner_raw_banjarmasin"; 
            $params_site_code2 = "raw_banjarmasin"; 
        }elseif ($site_code == 'SSJD2') {
            $params_site_code = "inner_raw_batulicin"; 
            $params_site_code2 = "raw_batulicin"; 
        }elseif ($site_code == 'BRBS0') {
            $params_site_code = "inner_raw_barabai"; 
            $params_site_code2 = "raw_barabai"; 
        }

        $query = "
            insert into management_raw.$params_site_code
            select '', '$site_code', '', '', '', a.*, '', '', '', '', '', '', '', '', '', '', b.supp, b.grupprod, '', '', '', '', '', '', '', '', '', '' 
            from management_raw.$params_site_code2 a INNER JOIN (
                select a.kodeprod, a.supp, a.grupprod
                from mpm.tabprod a 
            )b on a.kodeprodukprincipal = b.kodeprod
            where a.signature = '$signature'
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        // die;
        $update = $this->db->query($query);
        if ($update) {
            return $update;
        }else{
            return array();
        }
    }

    public function inner_kodeproduk_samarinda($site_code, $signature){

        if ($site_code == 'SSMS9') {
            $params_site_code = "inner_raw_banjarmasin"; 
            $params_site_code2 = "raw_banjarmasin"; 
        }elseif ($site_code == 'SSJD2') {
            $params_site_code = "inner_raw_batulicin"; 
            $params_site_code2 = "raw_batulicin"; 
        }elseif ($site_code == 'BRBS0') {
            $params_site_code = "inner_raw_barabai"; 
            $params_site_code2 = "raw_barabai"; 
        }elseif ($site_code == 'SMRB7') {
            $params_site_code = "inner_raw_samarinda"; 
            $params_site_code2 = "raw_samarinda"; 
        }elseif ($site_code == 'BTGB8') {
            $params_site_code = "inner_raw_bontang"; 
            $params_site_code2 = "raw_bontang"; 
        }elseif ($site_code == 'PTK82') {
            $params_site_code = "inner_raw_pontianak"; 
            $params_site_code2 = "raw_pontianak"; 
        }
        

        $query = "
            insert into management_raw.$params_site_code
            select '', '$site_code', '', '', a.*, '', '', '', '', '', '', b.supp, b.grupprod, '', '', '', ''
            from management_raw.$params_site_code2 a INNER JOIN (
                select a.kodeprod, a.supp, a.grupprod
                from mpm.tabprod a 
            )b on a.productid = b.kodeprod
            where a.signature = '$signature'
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        // die;
        $update = $this->db->query($query);
        if ($update) {
            return $update;
        }else{
            return array();
        }
    }

    public function update_namaprod($site_code, $signature){

        if ($site_code == 'SSMS9') {
            $params_site_code = "inner_raw_banjarmasin"; 
        }elseif ($site_code == 'SSJD2') {
            $params_site_code = "inner_raw_batulicin"; 
        }elseif ($site_code == 'BRBS0') {
            $params_site_code = "inner_raw_barabai"; 
        }elseif ($site_code == 'SMRB7') {
            $params_site_code = "inner_raw_samarinda"; 
        }

        $query = "
            update management_raw.$params_site_code a 
            set a.namaproduk = (
                select b.namaprod
                from mpm.tabprod b 
                where a.kodeproduk = b.kodeprod
            ), a.map_divisi = (
                select b.new_divisi
                from mpm.tabprod b 
                where a.kodeproduk = b.kodeprod
            ), a.map_group = (
                select b.new_group
                from mpm.tabprod b 
                where a.kodeproduk = b.kodeprod
            ), a.map_subgroup = (
                select b.new_subgroup
                from mpm.tabprod b 
                where a.kodeproduk = b.kodeprod
            ), a.map_groupdiv = (
                select b.new_groupdiv
                from mpm.tabprod b 
                where a.kodeprodukprincipal = b.kodeprod
            )
            where a.signature = '$signature'
        ";
        $update = $this->db->query($query);
        if ($update) {
            return $update;
        }else{
            return array();
        }
    }

    public function update_namaprod_samarinda($site_code, $signature){

        if ($site_code == 'SSMS9') {
            $params_site_code = "inner_raw_banjarmasin"; 
        }elseif ($site_code == 'SSJD2') {
            $params_site_code = "inner_raw_batulicin"; 
        }elseif ($site_code == 'BRBS0') {
            $params_site_code = "inner_raw_barabai"; 
        }elseif ($site_code == 'SMRB7') {
            $params_site_code = "inner_raw_samarinda"; 
        }elseif ($site_code == 'BTGB8') {
            $params_site_code = "inner_raw_bontang"; 
        }elseif ($site_code == 'PTK82') {
            $params_site_code = "inner_raw_pontianak"; 
        }

        $query = "
            update management_raw.$params_site_code a 
            set a.nama_invoice = (
                select b.namaprod
                from mpm.tabprod b 
                where a.productid = b.kodeprod
            ), a.map_divisi = (
                select b.new_divisi
                from mpm.tabprod b 
                where a.productid = b.kodeprod
            ), a.map_group = (
                select b.new_group
                from mpm.tabprod b 
                where a.productid = b.kodeprod
            ), a.map_subgroup = (
                select b.new_subgroup
                from mpm.tabprod b 
                where a.productid = b.kodeprod
            ), a.map_groupdiv = (
                select b.new_groupdiv
                from mpm.tabprod b 
                where a.productid = b.kodeprod
            )
            where a.signature = '$signature'
        ";
        $update = $this->db->query($query);
        if ($update) {
            return $update;
        }else{
            return array();
        }
    }

    public function update_branch($site_code, $signature){
        
        if ($site_code == 'SSMS9') {
            $params_site_code = "inner_raw_banjarmasin"; 
        }elseif ($site_code == 'SSJD2') {
            $params_site_code = "inner_raw_batulicin"; 
        }elseif ($site_code == 'BRBS0') {
            $params_site_code = "inner_raw_barabai"; 
        }elseif ($site_code == 'SMRB7') {
            $params_site_code = "inner_raw_samarinda"; 
        }elseif ($site_code == 'BTGB8') {
            $params_site_code = "inner_raw_bontang"; 
        }elseif ($site_code == 'PTK82') {
            $params_site_code = "inner_raw_pontianak"; 
        }elseif ($site_code == 'CSSK3') {
            $params_site_code = "inner_raw_kendari"; 
        }elseif ($site_code == 'DASD1') {
            $params_site_code = "inner_raw_balikpapan"; 
        }elseif ($site_code == 'MWRW6') {
            $params_site_code = "inner_raw_manokwari"; 
        }elseif ($site_code == 'BLGW4') {
            $params_site_code = "inner_raw_palu"; 
        }elseif ($site_code == 'VBTV1') {
            $params_site_code = "inner_raw_vbt_makasar"; 
        }elseif ($site_code == 'BKMV5') {
            $params_site_code = "inner_raw_vbt_bulukumba"; 
        }elseif ($site_code == 'BONV4') {
            $params_site_code = "inner_raw_vbt_bone"; 
        }elseif ($site_code == 'PALV2') {
            $params_site_code = "inner_raw_vbt_palopo"; 
        }elseif ($site_code == 'PARV3') {
            $params_site_code = "inner_raw_vbt_pare"; 
        }elseif ($site_code == 'MANW5') {
            $params_site_code = "inner_raw_manado"; 
        }elseif ($site_code == 'GTO87') {
            $params_site_code = "inner_raw_gorontalo"; 
        }elseif ($site_code == 'PBNP9') {
            $params_site_code = "inner_raw_pangkalanbun"; 
        }elseif ($site_code == 'SPTU4') {
            $params_site_code = "inner_raw_sampit"; 
        }elseif ($site_code == 'PKRP8') {
            $params_site_code = "inner_raw_palangkaraya"; 
        }

        $query = "
            update management_raw.$params_site_code a 
            set a.branch_name = (
                select b.branch_name
                from mpm.tbl_tabcomp b 
                where b.status = 1 and a.site_code = concat(b.kode_comp, b.nocab)
                group by concat(b.kode_comp, b.nocab)
            ),a.nama_comp = (
                select b.nama_comp
                from mpm.tbl_tabcomp b 
                where b.status = 1 and a.site_code = concat(b.kode_comp, b.nocab)
                group by concat(b.kode_comp, b.nocab)
            )
            where a.signature = '$signature'
        ";
        $update = $this->db->query($query);

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        if ($update) {
            return $update;
        }else{
            return array();
        }
    }

    public function get_log_upload($site_code){
        
        if ($site_code) {
            $params = "and a.site_code = '$site_code'";
        }else{
            $params = "";
        }

        $query = "
            select  a.site_code, a.filename, a.created_at, a.created_by, a.signature, a.count_raw, a.count_mapping, a.tahun, a.bulan, a.omzet_raw, a.omzet_web,
                    b.branch_name, b.nama_comp, c.username
            from management_raw.log_upload a LEFT JOIN (
                select concat(a.kode_comp, a.nocab) as site_code, a.branch_name, a.nama_comp
                from mpm.tbl_tabcomp a 
                where a.`status` = 1
                GROUP BY concat(a.kode_comp, a.nocab)
            )b on a.site_code = b.site_code LEFT JOIN (
                select a.id, a.username
                from mpm.user a 
            )c on a.created_by = c.id
            where a.type_file = 'raw_sales' $params
            order by a.id desc
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        // die;
        return $this->db->query($query);
    }

    public function get_log_customer_upload($site_code = ''){

        if ($site_code) {
            $params = "and a.site_code = '$site_code'";
        }else{
            $params = "";
        }

        $query = "
            select a.site_code, a.filename, a.count_raw, a.count_mapping, a.created_at, a.created_by, a.signature, b.branch_name, b.nama_comp, c.username
            from management_raw.log_upload a LEFT JOIN (
                select concat(a.kode_comp, a.nocab) as site_code, a.branch_name, a.nama_comp
                from mpm.tbl_tabcomp a 
                where a.`status` = 1
                GROUP BY concat(a.kode_comp, a.nocab)
            )b on a.site_code = b.site_code LEFT JOIN (
                select a.id, a.username
                from mpm.user a 
            )c on a.created_by = c.id
            where a.type_file = 'raw_customer' $params
            order by a.id desc
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        // die;

        // if ($this->db->query($query)->num_rows() > 0) {
        //     return $this->db->query($query);
        // }else{
        //     return array();
        // }        

        return $this->db->query($query);
    }

    public function get_raw_customer_draft($signature = ''){

        if ($signature) {
            $params = "where a.signature = '$signature'";
        }else{
            $params = "";
        }

        $query = "
            select * from management_raw.raw_customer_banjarmasin a
            $params
        ";
        return $this->db->query($query);
    }

    public function get_raw_customer_draft_barabai($signature = ''){

        if ($signature) {
            $params = "where a.signature = '$signature'";
        }else{
            $params = "";
        }

        $query = "
            select * from management_raw.raw_customer_barabai a
            $params
        ";
        return $this->db->query($query);
    }

    public function trim_customer_banjarmasin($signature){
        $query = "
        update management_raw.raw_customer_banjarmasin a 
        set a.kode_type = trim(a.kode_type), a.kode_class = trim(a.kode_class), a.customer_id = trim(a.customer_id), a.customer_id_nd6 = trim(a.customer_id_nd6)
        where a.signature = '$signature'
        ";
        $update = $this->db->query($query);

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        // die;

        if ($update) {
            return $update;
        }else{
            return array();
        }
    }

    public function trim_customer_barabai($signature){
        $query = "
        update management_raw.raw_customer_barabai a 
        set a.kode_type = trim(a.kode_type), a.kode_class = trim(a.kode_class), a.customer_id = trim(a.customer_id), a.customer_id_nd6 = trim(a.customer_id_nd6)
        where a.signature = '$signature'
        ";
        $update = $this->db->query($query);

        echo "<pre>";
        print_r($query);
        echo "</pre>";

        die;

        if ($update) {
            return $update;
        }else{
            return array();
        }
    }

    public function update_type_n_class($signature){
        $query = "
        update management_raw.raw_customer_banjarmasin a 
        set a.nama_type = (
            select b.nama_type
            from mpm.tbl_bantu_type b
            where a.kode_type = b.kode_type
        ), a.sektor = (
            select b.sektor
            from mpm.tbl_bantu_type b 
            where a.kode_type = b.kode_type
        ), a.segment = (
            select b.segment
            from mpm.tbl_bantu_type b 
            where a.kode_type = b.kode_type
        ), a.nama_class = (
            select c.jenis
            from mpm.tbl_tabsalur c 
            where a.kode_class = c.kode
        ), a.group_class = (
            select c.group
            from mpm.tbl_tabsalur c 
            where a.kode_class = c.kode
        )
        where a.signature = '$signature'
        ";
        $update = $this->db->query($query);

        if ($update) {
            return $update;
        }else{
            return array();
        }
    }

    public function update_type_n_class_barabai($signature){
        $query = "
        update management_raw.raw_customer_barabai a 
        set a.nama_type = (
            select b.nama_type
            from mpm.tbl_bantu_type b
            where a.kode_type = b.kode_type
        ), a.sektor = (
            select b.sektor
            from mpm.tbl_bantu_type b 
            where a.kode_type = b.kode_type
        ), a.segment = (
            select b.segment
            from mpm.tbl_bantu_type b 
            where a.kode_type = b.kode_type
        ), a.nama_class = (
            select c.jenis
            from mpm.tbl_tabsalur c 
            where a.kode_class = c.kode
        ), a.group_class = (
            select c.group
            from mpm.tbl_tabsalur c 
            where a.kode_class = c.kode
        )
        where a.signature = '$signature'
        ";

        echo "<pre>";
        print_r($query);
        echo "</pre>";

        die;

        $update = $this->db->query($query);

        if ($update) {
            return $update;
        }else{
            return array();
        }
    }

    public function inner_customer_banjarmasin($signature){
        $query = "
        insert into management_raw.inner_raw_customer_banjarmasin
        select '', a.*
        from management_raw.raw_customer_banjarmasin a 
        where a.signature = '$signature'
        group by a.customer_id_nd6
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        $update = $this->db->query($query);

        if ($update) {
            return $update;
        }else{
            return array();
        }
    }

    public function inner_customer_barabai($signature){
        $query = "
        insert into management_raw.inner_raw_customer_barabai
        select '', a.*
        from management_raw.raw_customer_barabai a 
        where a.signature = '$signature'
        group by a.customer_id_nd6
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        $update = $this->db->query($query);

        if ($update) {
            return $update;
        }else{
            return array();
        }
    }

    public function update_inner_customer_id_banjarmasin($signature){

        $query = "
        update management_raw.inner_raw_banjarmasin a 
        set a.customer_id = (
            select b.customer_id
            from management_raw.raw_customer_banjarmasin b
            where a.kodecustomer = b.customer_id_nd6
            group by b.customer_id_nd6
        ),a.nama_customer = (
            select b.nama_customer
            from management_raw.raw_customer_banjarmasin b
            where a.kodecustomer = b.customer_id_nd6
            group by b.customer_id_nd6
        ),a.alamat = (
            select b.alamat
            from management_raw.raw_customer_banjarmasin b
            where a.kodecustomer = b.customer_id_nd6
            group by b.customer_id_nd6
        ),a.kode_type = (
            select b.kode_type
            from management_raw.raw_customer_banjarmasin b
            where a.kodecustomer = b.customer_id_nd6
            group by b.customer_id_nd6
        ),a.nama_type = (
            select b.nama_type
            from management_raw.raw_customer_banjarmasin b
            where a.kodecustomer = b.customer_id_nd6
            group by b.customer_id_nd6
        ),a.sektor = (
            select b.sektor
            from management_raw.raw_customer_banjarmasin b
            where a.kodecustomer = b.customer_id_nd6
            group by b.customer_id_nd6
        ),a.segment = (
            select b.segment
            from management_raw.raw_customer_banjarmasin b
            where a.kodecustomer = b.customer_id_nd6
            group by b.customer_id_nd6
        ),a.kode_class = (
            select b.kode_class
            from management_raw.raw_customer_banjarmasin b
            where a.kodecustomer = b.customer_id_nd6
            group by b.customer_id_nd6
        ),a.nama_class = (
            select b.nama_class
            from management_raw.raw_customer_banjarmasin b
            where a.kodecustomer = b.customer_id_nd6
            group by b.customer_id_nd6
        ),a.group_class = (
            select b.group_class
            from management_raw.raw_customer_banjarmasin b
            where a.kodecustomer = b.customer_id_nd6
            group by b.customer_id_nd6
        ),a.kode_kota = (
            select b.kode_kota
            from management_raw.raw_customer_banjarmasin b
            where a.kodecustomer = b.customer_id_nd6
            group by b.customer_id_nd6
        ),a.nama_kota = (
            select b.nama_kota
            from management_raw.raw_customer_banjarmasin b
            where a.kodecustomer = b.customer_id_nd6
            group by b.customer_id_nd6
        ),a.kode_kecamatan = (
            select b.kode_kecamatan
            from management_raw.raw_customer_banjarmasin b
            where a.kodecustomer = b.customer_id_nd6
            group by b.customer_id_nd6
        ),a.nama_kecamatan = (
            select b.nama_kecamatan
            from management_raw.raw_customer_banjarmasin b
            where a.kodecustomer = b.customer_id_nd6
            group by b.customer_id_nd6
        ),a.kode_kelurahan = (
            select b.kode_kelurahan
            from management_raw.raw_customer_banjarmasin b
            where a.kodecustomer = b.customer_id_nd6
            group by b.customer_id_nd6
        ),a.nama_kelurahan = (
            select b.nama_kelurahan
            from management_raw.raw_customer_banjarmasin b
            where a.kodecustomer = b.customer_id_nd6
            group by b.customer_id_nd6
        )
        where a.signature = '$signature'
        
        ";
        $update = $this->db->query($query);

        if ($update) {
            return $update;
        }else{
            return array();
        }
    }

    public function update_inner_customer_id_barabai($signature){

        $query = "
        update management_raw.inner_raw_barabai a 
        set a.customer_id = (
            select b.customer_id
            from management_raw.raw_customer_barabai b
            where a.kodecustomer = b.customer_id_nd6 
            group by b.customer_id_nd6
        ),a.nama_customer = (
            select b.nama_customer
            from management_raw.raw_customer_barabai b
            where a.kodecustomer = b.customer_id_nd6 
            group by b.customer_id_nd6
        ),a.alamat = (
            select b.alamat
            from management_raw.raw_customer_barabai b
            where a.kodecustomer = b.customer_id_nd6 
            group by b.customer_id_nd6
        ),a.kode_type = (
            select b.kode_type
            from management_raw.raw_customer_barabai b
            where a.kodecustomer = b.customer_id_nd6 
            group by b.customer_id_nd6
        ),a.nama_type = (
            select b.nama_type
            from management_raw.raw_customer_barabai b
            where a.kodecustomer = b.customer_id_nd6 
            group by b.customer_id_nd6
        ),a.sektor = (
            select b.sektor
            from management_raw.raw_customer_barabai b
            where a.kodecustomer = b.customer_id_nd6 
            group by b.customer_id_nd6
        ),a.segment = (
            select b.segment
            from management_raw.raw_customer_barabai b
            where a.kodecustomer = b.customer_id_nd6 
            group by b.customer_id_nd6
        ),a.kode_class = (
            select b.kode_class
            from management_raw.raw_customer_barabai b
            where a.kodecustomer = b.customer_id_nd6 
            group by b.customer_id_nd6
        ),a.nama_class = (
            select b.nama_class
            from management_raw.raw_customer_barabai b
            where a.kodecustomer = b.customer_id_nd6 
            group by b.customer_id_nd6
        ),a.group_class = (
            select b.group_class
            from management_raw.raw_customer_barabai b
            where a.kodecustomer = b.customer_id_nd6 
            group by b.customer_id_nd6
        ),a.kode_kota = (
            select b.kode_kota
            from management_raw.raw_customer_barabai b
            where a.kodecustomer = b.customer_id_nd6 
            group by b.customer_id_nd6
        ),a.nama_kota = (
            select b.nama_kota
            from management_raw.raw_customer_barabai b
            where a.kodecustomer = b.customer_id_nd6 
            group by b.customer_id_nd6
        ),a.kode_kecamatan = (
            select b.kode_kecamatan
            from management_raw.raw_customer_barabai b
            where a.kodecustomer = b.customer_id_nd6 
            group by b.customer_id_nd6
        ),a.nama_kecamatan = (
            select b.nama_kecamatan
            from management_raw.raw_customer_barabai b
            where a.kodecustomer = b.customer_id_nd6
            group by b.customer_id_nd6
        ),a.kode_kelurahan = (
            select b.kode_kelurahan
            from management_raw.raw_customer_barabai b
            where a.kodecustomer = b.customer_id_nd6 
            group by b.customer_id_nd6
        ),a.nama_kelurahan = (
            select b.nama_kelurahan
            from management_raw.raw_customer_barabai b
            where a.kodecustomer = b.customer_id_nd6
            group by b.customer_id_nd6
        )
        where a.signature = '$signature'
        
        ";
        $update = $this->db->query($query);


        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";



        if ($update) {
            return $update;
        }else{
            return array();
        }
    }

    public function update_inner_customer_id_batulicin($signature){

        $query = "
        update management_raw.inner_raw_batulicin a 
        set a.customer_id = (
            select b.customer_id
            from management_raw.raw_customer_batulicin b
            where a.kodecustomer = b.customer_id_nd6
            group by b.customer_id_nd6
        ),a.nama_customer = (
            select b.nama_customer
            from management_raw.raw_customer_batulicin b
            where a.kodecustomer = b.customer_id_nd6
            group by b.customer_id_nd6
        ),a.alamat = (
            select b.alamat
            from management_raw.raw_customer_batulicin b
            where a.kodecustomer = b.customer_id_nd6
            group by b.customer_id_nd6
        )
        where a.signature = '$signature'
        
        ";
        $update = $this->db->query($query);

        if ($update) {
            return $update;
        }else{
            return array();
        }
    }

    public function update_master_customer_batulicin($signature){

        $query = "
            select a.kodecustomer, a.customer_id, a.namacustomer, a.alamatcustomer
            from management_raw.inner_raw_batulicin a
            where a.signature = '$signature' and a.customer_id is null
            group by a.kodecustomer
        ";

        $get_customer_null =  $this->db->query($query);

        if ($get_customer_null->num_rows() > 0) {

            foreach ($get_customer_null->result() as $key) {
                $query = "
                    select right(customer_id,5) as customer_id
                    from management_raw.raw_customer_batulicin a
                    order by a.id desc
                ";

                $get_customer_last = $this->db->query($query);

                if ($get_customer_last->num_rows() > 0) {
                    $last_customer_id = $get_customer_last->row()->customer_id;
                }else{
                    $last_customer_id = 0000;
                }

                $insert = [
                    'customer_id_nd6'   => $key->kodecustomer,
                    'customer_id'       => "1".sprintf('%05d',intval($last_customer_id) + 1),
                    'nama_customer'     => $key->namacustomer,
                    'alamat'            => $key->alamatcustomer,
                ];
                $this->db->insert('management_raw.raw_customer_batulicin', $insert);
            }

            return 1;
        }else{
            return array();
        }

    }

    public function insert_mpm_upload($site_code, $signature, $omzet = '', $status_closing = 0){

        if ($site_code == 'SSMS9') {
            $params_site_code = "inner_raw_banjarmasin"; 
        }elseif ($site_code == 'SSJD2') {
            $params_site_code = "inner_raw_batulicin"; 
        }elseif ($site_code == 'BRBS0') {
            $params_site_code = "inner_raw_barabai"; 
        }elseif ($site_code == 'SMRB7') {
            $params_site_code = "inner_raw_samarinda"; 
        }elseif ($site_code == 'BTGB8') {
            $params_site_code = "inner_raw_bontang"; 
        }elseif ($site_code == 'PTK82') {
            $params_site_code = "inner_raw_pontianak"; 
        }elseif ($site_code == 'DASD1') {
            $params_site_code = "inner_raw_balikpapan"; 
        }elseif ($site_code == 'BLGW4') {
            $params_site_code = "inner_raw_palu"; 
        }elseif ($site_code == 'MANW5') {
            $params_site_code = "inner_raw_manado"; 
        }elseif ($site_code == 'GTO87') {
            $params_site_code = "inner_raw_gorontalo"; 
        }

        $query_tanggal = "
            select  a.tanggal, if(length(max(day(a.tanggal))) = 1, concat('0',max(day(a.tanggal))), max(day(a.tanggal))) as hari, if(length(max(month(a.tanggal))) = 1, concat('0',max(month(a.tanggal))), max(month(a.tanggal))) as bulan, max(year(a.tanggal)) as tahun  
            from management_raw.$params_site_code a
            where a.signature = '$signature'
        ";

        // die;

        $get_hari = $this->db->query($query_tanggal)->row()->hari;
        $get_bulan = $this->db->query($query_tanggal)->row()->bulan;
        $get_tahun = $this->db->query($query_tanggal)->row()->tahun;

        // die;
        $created_at = $this->model_outlet_transaksi->timezone();

        // die;

        $cek_userid  = "
            select id
            from mpm.user a
            where a.username = left('$site_code',3)
        ";

        // die;

        $userid = $this->db->query($cek_userid)->row()->id;

        $insert = [
            'userid'            => $userid,
            'lastupload'        => $created_at,
            'filename'          => 'NON SDS',
            'tanggal'           => $get_hari,
            'bulan'             => $get_bulan,
            'tahun'             => $get_tahun,
            'status'            => 1,
            'status_closing'    => $status_closing,
            'omzet'             => $omzet,
            'flag_check'        => 0
        ];

        return $this->db->insert('mpm.upload', $insert);

    }

    public function update_tanggal($site_code, $signature){
        
        if ($site_code == 'SSMS9') {
            $params_site_code = "inner_raw_banjarmasin"; 
        }elseif ($site_code == 'SSJD2') {
            $params_site_code = "inner_raw_batulicin"; 
        }elseif ($site_code == 'BRBS0') {
            $params_site_code = "inner_raw_barabai"; 
        }elseif ($site_code == 'SMRB7') {
            $params_site_code = "inner_raw_samarinda"; 
        }elseif ($site_code == 'BTGB8') {
            $params_site_code = "inner_raw_bontang"; 
        }elseif ($site_code == 'PTK82') {
            $params_site_code = "inner_raw_pontianak"; 
        }elseif ($site_code == 'BLGW4') {
            $params_site_code = "inner_raw_palu"; 
        }elseif ($site_code == 'MANW5') {
            $params_site_code = "inner_raw_manado"; 
        }

        $query = "
            update management_raw.$params_site_code a 
            set a.tanggal = str_to_date(a.tanggal,'%m/%d/%Y')
            where a.signature = '$signature'
        ";
        $update = $this->db->query($query);
        if ($update) {
            return $update;
        }else{
            return array();
        }
    }

    public function delete_tabel($site_code, $signature){
        $nocab = substr($site_code, 3, 2);

        $get_tahun = "
            select * 
            from management_raw.log_upload a 
            where a.signature = '$signature'
        ";
        $tahun = $this->db->query($get_tahun)->row()->tahun;
        $bulan = $this->db->query($get_tahun)->row()->bulan;

        $query = "
            delete from data$tahun.fi
            where bulan = '$bulan' and concat(kode_comp, nocab) = '$site_code'
        ";
        $this->db->query($query);

        $query = "
            delete from data$tahun.ri
            where bulan = '$bulan' and concat(kode_comp, nocab) = '$site_code'
        ";
        $delete = $this->db->query($query);

        $query = "
            delete from data$tahun.tblang
            where concat(kode_comp, nocab) = '$site_code'
        ";
        $delete = $this->db->query($query);

        $query = "
            delete from data$tahun.tabsales
            where nocab = '$nocab'
        ";
        $delete = $this->db->query($query);

        $query = "
            delete from data$tahun.tbkota
            where concat(kode_comp, nocab) = '$site_code'
        ";
        $delete = $this->db->query($query);

        $query = "
            delete from data$tahun.tabtype
            where nocab = '$nocab'
        ";
        $delete = $this->db->query($query);

        return $delete;
    }

    public function insert_fi_batulicin($site_code, $signature){
        $get_tahun = "
            select * 
            from management_raw.log_upload a 
            where a.site_code = '$site_code' and a.signature = '$signature'
        ";
        $tahun = $this->db->query($get_tahun)->row()->tahun;
        $bulan = $this->db->query($get_tahun)->row()->bulan;


        $insert = "
        insert data$tahun.fi
        select 	'07' as kddokjdi, a.nomornota as nodokjdi, a.nomornota as nodokacu, a.tanggal as tgldokjdi, a.kodesalesman as kodesales, 
                'SSJ' as kode_comp, 'BAT' as kode_kota, 
                case 		
                    when a.namacustomer LIKE '%APOTIK%' then 'APT'		
                    when a.namacustomer LIKE '%APOTEK%' then 'APT'	
                    when a.channel like '%MODERN OUTLET (LOKAL)%' then 'MML'
                    when NAMACUSTOMER LIKE'%PT.%' then 'PBF'		
                    else 'TKL'		
                end as kode_type,
                a.customer_id as kode_lang,
                '' as kode_rayon,
                a.kodeprodukprincipal as kodeprod,
                a.supp as supp,
                day(a.tanggal) as hrdok,
                if(LENGTH(month(a.tanggal)) = 1, concat('0',month(a.tanggal)), month(a.tanggal)) as blndok,	
                year(a.tanggal) as thndok,
                a.namaproduk as namaprod,
                a.map_group as groupprod,
                if(a.grossamount*1.11='0','0',a.qtysoldpcs/b.satuan) as banyak,		
                if(a.grossamount*1.11/a.qtysoldpcs is null,0,round(a.grossamount*1.11/(a.qtysoldpcs/b.satuan),2)) as harga,	
                round(a.totallinediscount*1.11,2) as potongan,		
                round(a.grossamount*1.11,2)as tot1,		
                '' as jum_promo, 		
                if(a.grossamount='0',concat(a.kodeprodukprincipal,' ',a.qtysoldpcs),'') as keterangan,  		
                '' as user_isi,		
                '' as jam_isi,		
                '' as tgl_isi, 		
                '' as user_edit, 		
                '' as jam_edit, 		
                '' as tgl_edit,		
                '' as user_del, 		
                '' as jam_del, 		
                '' as tgl_del, 		
                '' as no, 		
                '' as backup, 		
                '' as no_urut, 		
                'PST' as kode_gdg, 		
                '' as nama_gdg,
                case		
                    when a.channel LIKE'%SEMI GROSIR%' THEN 'SWS'		
                    when a.channel LIKE'%MINI MARKET%' THEN 'WS'		
                else 'RT' end as kodesalur,		
                '' as kodebonus,		
                '' as namabonus, 		
                '' as grupbonus, 		
                '' as unitbonus, 		
                a.namasalesman as lampiran, 		
                '' as h_beli, 		
                '' as kodearea, 		
                a.alamatcustomer as namaarea, 		
                '' as pinjam, 		
                '' as jualbanyak, 		
                '' as jualpinjam, 		
                '' as harga_excl, 		
                '' as tot1_excl, 		
                a.namacustomer as nama_lang,		
                'D2' as nocab,		
                if(LENGTH(month(a.tanggal)) = 1, concat('0',month(a.tanggal)), month(a.tanggal)) as bulan,
                ' ' as siteid,		
                ' ' as qty1,		
                ' ' as qty2,		
                ' ' as qty3,		
                ' ' as qty_bonus,		
                ' ' as flag_bonus,		
                ' ' as disc_persen,		
                ' ' as disc_rp,		
                ' ' as disc_value,		
                ' ' as disc_cabang,		
                ' ' as disc_prinsipal,		
                ' ' as disc_xtra,		
                ' ' as rp_cabang,		
                ' ' as rp_prinsipal,		
                ' ' as rp_xtra,		
                ' ' as bonus,		
                ' ' as prinsipalid,		
                ' ' as ex_no_sales,		
                ' ' as status_retur,		
                ' ' as ref,		
                ' ' as term_payment,		
                ' ' as tipe_kl				

        from management_raw.inner_raw_batulicin a LEFT JOIN pmu.mapping b 
        on a.kodeprodukprincipal = b.kodeprod
        where a.signature = '$signature' and year(a.tanggal) = $tahun and month(a.tanggal) = $bulan and b.nocab = 'd2' and a.tipetrans = 'sales'
        ";

        // echo "<pre>";
        // print_r($insert);
        // echo "</pre>";

        return $this->db->query($insert);
    }

    public function insert_fi_banjarmasin($site_code, $signature){
        $get_tahun = "
            select * 
            from management_raw.log_upload a 
            where a.site_code = '$site_code' and a.signature = '$signature'
        ";
        $tahun = $this->db->query($get_tahun)->row()->tahun;
        $bulan = $this->db->query($get_tahun)->row()->bulan;

        $insert = "
        insert data$tahun.fi
        select  '08' as kddokjdi, a.nomornota as nodokjdi, a.nomornota as nodokacu, a.tanggal as tgldokjdi, 
					a.kodesalesman as kodesales, 'SSM' as kode_comp, a.kode_kota AS kode_kota, 
					a.kode_type as kode_type, a.customer_id as kode_lang, '' as koderayon, a.kodeprodukprincipal as kodeprod,
					a.supp, if(length(day(a.tanggal)) = 1, concat('0',day(a.tanggal)), day(a.tanggal)) as hrdok,
					if(length(month(a.tanggal)) = 1, concat('0',month(a.tanggal)), month(a.tanggal)) as blndok,
					if(length(year(a.tanggal)) = 1, concat('0',year(a.tanggal)), year(a.tanggal)) as thndok,
					a.namaproduk as namaprod, a.groupprod, a.qtysoldtotalpcs as banyak,
					(a.grossamount*1.11) / a.qtysoldtotalpcs as harga, '' as potongan,
					(a.grossamount*1.11) as tot1, '' as jum_promo, '' as keterangan, '' as  user_isi, '' as jam_isi, '' as tgl_isi,
					'' as  user_edit, '' as jam_edit, '' as tgl_edit, '' as  user_del, '' as jam_del, '' as tgl_del, '' as no, '' as backup, '' as no_urut,
					'PST' as kode_gdg, '' as nama_gdg, a.kode_class as kodesalur, '' as kodebonus, '' as namabonus, '' as grupbonus,
					'' as unitbonus, '' as lampiran, (a.grossamount*1.11) / a.qtysoldtotalpcs as h_beli, '' as kodearea, '' as namarea,
					'' as pinjam, '' as jualbanyak, '' as jualpinjam, '' as harga_excl, '' as tot1_excl, a.namacustomer as namalang, 
					right(a.site_code,2) as nocab, if(length(month(a.tanggal)) = 1, concat('0',month(a.tanggal)), month(a.tanggal)) as bulan,
					'SSM001' as siteid, '' as qty1, '' as qty2, '' as qty3, '' as qty_bonus, '' as flag_bonus, '' as disc_persen, '' as disc_rp, 
					'' as disc_value, '' as disc_cabang, '' as disc_prinsipal, '' as disc_xtra,
					'' as rp_cabang, '' as rp_prinsipal, '' as rp_xtra, '' as bonus, concat('11',a.supp) as prinsipalid, '' as ex_no_sales, 
					'' as status_retur, '' as ref, '' as term_payment, '' as tipe_kl                       
        from management_raw.inner_raw_banjarmasin a 
        where a.signature = '$signature' and year(a.tanggal) = $tahun and month(a.tanggal) = $bulan and a.tipetrans = 'sales'
        ";

        // echo "<pre>";
        // print_r($insert);
        // echo "</pre>";

        return $this->db->query($insert);
    }

    public function insert_ri_banjarmasin($site_code, $signature){
        $get_tahun = "
            select * 
            from management_raw.log_upload a 
            where a.site_code = '$site_code' and a.signature = '$signature'
        ";
        $tahun = $this->db->query($get_tahun)->row()->tahun;
        $bulan = $this->db->query($get_tahun)->row()->bulan;

        $insert = "
        insert data$tahun.ri
        select  '08' as kddokjdi, a.nomornota as nodokjdi, a.nomornota as nodokacu, a.tanggal as tgldokjdi, 
					a.kodesalesman as kodesales, 'SSM' as kode_comp, a.kode_kota AS kode_kota, 
					a.kode_type as kode_type, a.customer_id as kode_lang, '' as koderayon, a.kodeprodukprincipal as kodeprod,
					a.supp, if(length(day(a.tanggal)) = 1, concat('0',day(a.tanggal)), day(a.tanggal)) as hrdok,
					if(length(month(a.tanggal)) = 1, concat('0',month(a.tanggal)), month(a.tanggal)) as blndok,
					if(length(year(a.tanggal)) = 1, concat('0',year(a.tanggal)), year(a.tanggal)) as thndok,
					a.namaproduk as namaprod, a.groupprod, a.qtysoldtotalpcs as banyak,
					(a.grossamount*1.11) / a.qtysoldtotalpcs as harga, '' as potongan,
					(a.grossamount*1.11) as tot1, '' as jum_promo, '' as keterangan, '' as  user_isi, '' as jam_isi, '' as tgl_isi,
					'' as  user_edit, '' as jam_edit, '' as tgl_edit, '' as  user_del, '' as jam_del, '' as tgl_del, '' as no, '' as backup, '' as no_urut,
					'PST' as kode_gdg, '' as nama_gdg, a.kode_class as kodesalur, '' as kodebonus, '' as namabonus, '' as grupbonus,
					'' as unitbonus, '' as lampiran, (a.grossamount*1.11) / a.qtysoldtotalpcs as h_beli, '' as kodearea, '' as namarea,
					'' as pinjam, '' as jualbanyak, '' as jualpinjam, '' as harga_excl, a.namacustomer as namalang, 
					right(a.site_code,2) as nocab, if(length(month(a.tanggal)) = 1, concat('0',month(a.tanggal)), month(a.tanggal)) as bulan,
					'SSM001' as siteid, '' as qty1, '' as qty2, '' as qty3, '' as qty_bonus, '' as flag_bonus, '' as disc_persen, '' as disc_rp, 
					'' as disc_value, '' as disc_cabang, '' as disc_prinsipal, '' as disc_xtra,
					'' as rp_cabang, '' as rp_prinsipal, '' as rp_xtra, '' as bonus, concat('11',a.supp) as prinsipalid, '' as ex_no_sales, 
					'' as status_retur, '' as ref, '' as term_payment, '' as tipe_kl                       
        from management_raw.inner_raw_banjarmasin a 
        where a.signature = '$signature' and year(a.tanggal) = $tahun and month(a.tanggal) = $bulan and a.tipetrans <> 'sales'
        ";

        // echo "<pre>";
        // print_r($insert);
        // echo "</pre>";

        return $this->db->query($insert);
    }

    public function insert_fi_barabai($site_code, $signature){
        $get_tahun = "
            select * 
            from management_raw.log_upload a 
            where a.site_code = '$site_code' and a.signature = '$signature'
        ";
        $tahun = $this->db->query($get_tahun)->row()->tahun;
        $bulan = $this->db->query($get_tahun)->row()->bulan;

        $insert = "
        insert data$tahun.fi
        select  '08' as kddokjdi, a.nomornota as nodokjdi, a.nomornota as nodokacu, a.tanggal as tgldokjdi, 
                a.kodesalesman as kodesales, 'BRB' as kode_comp, a.kode_kota AS kode_kota, 
                a.kode_type as kode_type, a.customer_id as kode_lang, '' as koderayon, a.kodeprodukprincipal as kodeprod,
                a.supp, if(length(day(a.tanggal)) = 1, concat('0',day(a.tanggal)), day(a.tanggal)) as hrdok,
                if(length(month(a.tanggal)) = 1, concat('0',month(a.tanggal)), month(a.tanggal)) as blndok,
                if(length(year(a.tanggal)) = 1, concat('0',year(a.tanggal)), year(a.tanggal)) as thndok,
                a.namaproduk as namaprod, a.groupprod, a.qtysoldpcs as banyak,
                (a.grossamount*1.11) / a.qtysoldpcs as harga, '' as potongan,
                (a.grossamount*1.11) as tot1, '' as jum_promo, '' as keterangan, '' as  user_isi, '' as jam_isi, '' as tgl_isi,
                '' as  user_edit, '' as jam_edit, '' as tgl_edit, '' as  user_del, '' as jam_del, '' as tgl_del, '' as no, '' as backup, '' as no_urut,
                'PST' as kode_gdg, '' as nama_gdg, a.kode_class as kodesalur, '' as kodebonus, '' as namabonus, '' as grupbonus,
                '' as unitbonus, '' as lampiran, (a.grossamount*1.11) / a.qtysoldpcs as h_beli, '' as kodearea, a.alamat as namarea,
                '' as pinjam, '' as jualbanyak, '' as jualpinjam, '' as harga_excl, '' as tot1_excl, a.namacustomer as namalang, 
                right(a.site_code,2) as nocab, if(length(month(a.tanggal)) = 1, concat('0',month(a.tanggal)), month(a.tanggal)) as bulan,
                'BRB001' as siteid, '' as qty1, '' as qty2, '' as qty3, '' as qty_bonus, '' as flag_bonus, '' as disc_persen, '' as disc_rp, 
                '' as disc_value, '' as disc_cabang, '' as disc_prinsipal, '' as disc_xtra,
                '' as rp_cabang, '' as rp_prinsipal, '' as rp_xtra, '' as bonus, concat('11',a.supp) as prinsipalid, '' as ex_no_sales, 
                '' as status_retur, '' as ref, '' as term_payment, '' as tipe_kl                       
        from management_raw.inner_raw_barabai a 
        where a.signature = '$signature' and year(a.tanggal) = $tahun and month(a.tanggal) = $bulan and a.tipetrans = 'sales'
        ";

        // echo "<pre>";
        // print_r($insert);
        // echo "</pre>";

        // die;

        return $this->db->query($insert);
    }

    public function insert_ri_barabai($site_code, $signature){
        $get_tahun = "
            select * 
            from management_raw.log_upload a 
            where a.site_code = '$site_code' and a.signature = '$signature'
        ";
        $tahun = $this->db->query($get_tahun)->row()->tahun;
        $bulan = $this->db->query($get_tahun)->row()->bulan;

        $insert = "
        insert data$tahun.ri
        select  '08' as kddokjdi, a.nomornota as nodokjdi, a.nomornota as nodokacu, a.tanggal as tgldokjdi, 
                a.kodesalesman as kodesales, 'BRB' as kode_comp, a.kode_kota AS kode_kota, 
                a.kode_type as kode_type, a.customer_id as kode_lang, '' as koderayon, a.kodeprodukprincipal as kodeprod,
                a.supp, if(length(day(a.tanggal)) = 1, concat('0',day(a.tanggal)), day(a.tanggal)) as hrdok,
                if(length(month(a.tanggal)) = 1, concat('0',month(a.tanggal)), month(a.tanggal)) as blndok,
                if(length(year(a.tanggal)) = 1, concat('0',year(a.tanggal)), year(a.tanggal)) as thndok,
                a.namaproduk as namaprod, a.groupprod, a.qtysoldpcs as banyak,
                (a.grossamount*1.11) / a.qtysoldpcs as harga, '' as potongan,
                (a.grossamount*1.11) as tot1, '' as jum_promo, '' as keterangan, '' as  user_isi, '' as jam_isi, '' as tgl_isi,
                '' as user_edit, '' as jam_edit, '' as tgl_edit, '' as  user_del, '' as jam_del, '' as tgl_del, '' as no, '' as backup, '' as no_urut,
                'PST' as kode_gdg, '' as nama_gdg, a.kode_class as kodesalur, '' as kodebonus, '' as namabonus, '' as grupbonus,
                '' as unitbonus, '' as lampiran, (a.grossamount*1.11) / a.qtysoldpcs as h_beli, '' as kodearea, '' as namarea,
                '' as pinjam, '' as jualbanyak, '' as jualpinjam, '' as harga_excl, a.namacustomer as namalang, 
                right(a.site_code,2) as nocab, if(length(month(a.tanggal)) = 1, concat('0',month(a.tanggal)), month(a.tanggal)) as bulan,
                'BRB001' as siteid, '' as qty1, '' as qty2, '' as qty3, '' as qty_bonus, '' as flag_bonus, '' as disc_persen, '' as disc_rp, 
                '' as disc_value, '' as disc_cabang, '' as disc_prinsipal, '' as disc_xtra,
                '' as rp_cabang, '' as rp_prinsipal, '' as rp_xtra, '' as bonus, concat('11',a.supp) as prinsipalid, '' as ex_no_sales, 
                '' as status_retur, '' as ref, '' as term_payment, '' as tipe_kl                       
        from management_raw.inner_raw_barabai a 
        where a.signature = '$signature' and year(a.tanggal) = $tahun and month(a.tanggal) = $bulan and a.tipetrans <> 'sales'
        ";

        // echo "<pre>";
        // print_r($insert);
        // echo "</pre>";

        // die;

        return $this->db->query($insert);
    }

    public function insert_fi_samarinda($site_code, $signature){

        if ($site_code == 'SMRB7') {
            $params_site_code = "inner_raw_samarinda"; 
        }elseif ($site_code == 'BTGB8') {
            $params_site_code = "inner_raw_bontang"; 
        }elseif ($site_code == 'PTK82') {
            $params_site_code = "inner_raw_pontianak"; 
        }
        
        $get_tahun = "
            select * 
            from management_raw.log_upload a 
            where a.site_code = '$site_code' and a.signature = '$signature'
        ";
        $tahun = $this->db->query($get_tahun)->row()->tahun;
        $bulan = $this->db->query($get_tahun)->row()->bulan;

        $insert = "
        insert data$tahun.fi
        select  '07' as kddokjdi, a.no_sales as nodokjdi, a.no_sales as nodokacu, a.tanggal as tgldokjdi,
                a.salesmanid as kodesales, left(a.site_code, 3) as kode_comp, a.kotaid as kode_kota,
                a.typeid as kode_type, a.customerid as kode_lang, '' as koderayon, a.productid as kodeprod,
                a.supp as supp, if(length(day(a.tanggal)) = 1, concat('0', day(a.tanggal)), day(a.tanggal))  as hrdok,
                if(length(month(a.tanggal)) = 1, concat('0', month(a.tanggal)), month(a.tanggal))  as blndok,
                year(a.tanggal) as thndok, a.nama_invoice, a.map_group as groupprod, a.qty_kecil as banyak,
                (a.rp_kotor / a.qty_kecil) as harga, a.rp_discount as potongan, a.rp_kotor as tot1, 0 as jum_promo,
                '' as keterangan, '' as user_isi,		
                '' as jam_isi,		
                '' as tgl_isi, 		
                '' as user_edit, 		
                '' as jam_edit, 		
                '' as tgl_edit,		
                '' as user_del, 		
                '' as jam_del, 		
                '' as tgl_del, 		
                '' as no, 		
                '' as backup, 		
                '' as no_urut, 'PST' as kode_gdg, '' as nama_gdg, a.classid as kodesalur, '' as kodebonus,		
                '' as namabonus, '' as grupbonus, '' as unitbonus, 	a.nama_salesman as lampiran, (a.rp_kotor / a.qty_kecil) as h_beli,
                '' as kodearea, a.alamat as namaarea, '' as pinjam, '' as jualbanyak, '' as jualpinjam, '' as harga_excl, '' as tot1_excl, 		
                a.nama_customer, right(a.site_code, 2) as nocab, 
                if(LENGTH(month(a.tanggal)) = 1, concat('0',month(a.tanggal)), month(a.tanggal)) as bulan,	
                a.siteid, '' as qty1, '' as qty2, a.qty_kecil as qty3,
                ' ' as qty_bonus,		
                ' ' as flag_bonus,		
                ' ' as disc_persen,		
                ' ' as disc_rp,		
                ' ' as disc_value,		
                ' ' as disc_cabang,		
                ' ' as disc_prinsipal,		
                ' ' as disc_xtra,		
                ' ' as rp_cabang,		
                ' ' as rp_prinsipal,		
                ' ' as rp_xtra,		
                ' ' as bonus,		
                ' ' as prinsipalid,		
                ' ' as ex_no_sales,		
                ' ' as status_retur,		
                ' ' as ref,		
                ' ' as term_payment,		
                ' ' as tipe_kl	
        from management_raw.$params_site_code a 
        where a.signature = '$signature' and year(a.tanggal) = $tahun and month(a.tanggal) = $bulan and a.tipe_trans = 'sales'
        ";

        // echo "<pre>";
        // print_r($insert);
        // echo "</pre>";

        // die;

        return $this->db->query($insert);
    }

    public function insert_ri_samarinda($site_code, $signature){
        if ($site_code == 'SMRB7') {
            $params_site_code = "inner_raw_samarinda"; 
        }elseif ($site_code == 'BTGB8') {
            $params_site_code = "inner_raw_bontang"; 
        }elseif ($site_code == 'PTK82') {
            $params_site_code = "inner_raw_pontianak"; 
        }

        $get_tahun = "
            select * 
            from management_raw.log_upload a 
            where a.site_code = '$site_code' and a.signature = '$signature'
        ";
        $tahun = $this->db->query($get_tahun)->row()->tahun;
        $bulan = $this->db->query($get_tahun)->row()->bulan;


        $insert = "
        insert data$tahun.ri
        select  '07' as kddokjdi, a.no_sales as nodokjdi, a.no_sales as nodokacu, a.tanggal as tgldokjdi,
                a.salesmanid as kodesales, left(a.site_code, 3) as kode_comp, a.kotaid as kode_kota,
                a.typeid as kode_type, a.customerid as kode_lang, '' as koderayon, a.productid as kodeprod,
                a.supp as supp, if(length(day(a.tanggal)) = 1, concat('0', day(a.tanggal)), day(a.tanggal)) as hrdok,
                if(length(month(a.tanggal)) = 1, concat('0', month(a.tanggal)), month(a.tanggal)) as blndok,
                year(a.tanggal) as thndok, a.nama_invoice, a.map_group as groupprod, a.qty_kecil as banyak,
                (a.rp_kotor / a.qty_kecil) as harga, a.rp_discount as potongan, a.rp_kotor as tot1, 0 as jum_promo,
                '' as keterangan, '' as user_isi,		
                '' as jam_isi,		
                '' as tgl_isi, 		
                '' as user_edit, 		
                '' as jam_edit, 		
                '' as tgl_edit,		
                '' as user_del, 		
                '' as jam_del, 		
                '' as tgl_del, 		
                '' as no, 		
                '' as backup, 		
                '' as no_urut, 'PST' as kode_gdg, '' as nama_gdg, a.classid as kodesalur, '' as kodebonus,		
                '' as namabonus, '' as grupbonus, '' as unitbonus, 	a.nama_salesman as lampiran, (a.rp_kotor / a.qty_kecil) as h_beli,
                '' as kodearea, a.alamat as namaarea, '' as pinjam, '' as jualbanyak, '' as jualpinjam, '' as harga_excl, 		
                a.nama_customer, right(a.site_code, 2) as nocab, 
                if(LENGTH(month(a.tanggal)) = 1, concat('0',month(a.tanggal)), month(a.tanggal)) as bulan,	
                a.siteid, '' as qty1, '' as qty2, a.qty_kecil as qty3,
                ' ' as qty_bonus,		
                ' ' as flag_bonus,		
                ' ' as disc_persen,		
                ' ' as disc_rp,		
                ' ' as disc_value,		
                ' ' as disc_cabang,		
                ' ' as disc_prinsipal,		
                ' ' as disc_xtra,		
                ' ' as rp_cabang,		
                ' ' as rp_prinsipal,		
                ' ' as rp_xtra,		
                ' ' as bonus,		
                ' ' as prinsipalid,		
                ' ' as ex_no_sales,		
                ' ' as status_retur,		
                ' ' as ref,		
                ' ' as term_payment,		
                ' ' as tipe_kl	
        from management_raw.$params_site_code a 
        where a.signature = '$signature' and year(a.tanggal) = $tahun and month(a.tanggal) = $bulan and a.tipe_trans = 'retur'
        ";

        // echo "<pre>";
        // print_r($insert);
        // echo "</pre>";

        // die;

        return $this->db->query($insert);
    }

    public function insert_ri_batulicin($site_code, $signature){
        $get_tahun = "
            select * 
            from management_raw.log_upload a 
            where a.site_code = '$site_code' and a.signature = '$signature'
        ";
        $tahun = $this->db->query($get_tahun)->row()->tahun;
        $bulan = $this->db->query($get_tahun)->row()->bulan;

        $insert = "
        insert data$tahun.ri
        select 	'07' as kddokjdi, a.nomornota as nodokjdi, a.nomornota as nodokacu, a.tanggal as tgldokjdi, a.kodesalesman as kodesales, 
                'SSJ' as kode_comp, 'bat' as kode_kota, 
                case 		
                    when a.namacustomer LIKE '%APOTIK%' then 'APT'		
                    when a.namacustomer LIKE '%APOTEK%' then 'APT'	
                    when a.channel like '%MODERN OUTLET (LOKAL)%' then 'MML'
                    when NAMACUSTOMER LIKE'%PT.%' then 'PBF'		
                    else 'TKL'		
                end as kode_type,
                a.customer_id as kode_lang,
                '' as kode_rayon,
                a.kodeprodukprincipal as kodeprod,
                a.supp as supp,
                day(a.tanggal) as hrdok,
                if(LENGTH(month(a.tanggal)) = 1, concat('0',month(a.tanggal)), month(a.tanggal)) as blndok,
                year(a.tanggal) as thndok,
                a.namaproduk as namaprod,
                a.map_group as groupprod,
                if(a.grossamount*1.11='0','0',a.qtysoldpcs/b.satuan) as banyak,		
                if(a.grossamount*1.11/a.qtysoldpcs is null,0,round(a.grossamount*1.11/(a.qtysoldpcs/b.satuan),2)) as harga,	
                round(a.totallinediscount*1.11,2) as potongan,		
                round(a.grossamount*1.11,2)as tot1,		
                '' as jum_promo, 		
                if(a.grossamount='0',concat(a.kodeprodukprincipal,' ',a.qtysoldpcs),'') as keterangan,  		
                '' as user_isi,		
                '' as jam_isi,		
                '' as tgl_isi, 		
                '' as user_edit, 		
                '' as jam_edit, 		
                '' as tgl_edit,		
                '' as user_del, 		
                '' as jam_del, 		
                '' as tgl_del, 		
                '' as no, 		
                '' as backup, 		
                '' as no_urut, 		
                'PST' as kode_gdg, 		
                '' as nama_gdg,
                case		
                    when a.channel LIKE'%SEMI GROSIR%' THEN 'SWS'		
                    when a.channel LIKE'%MINI MARKET%' THEN 'WS'		
                else 'RT' end as kodesalur,		
                '' as kodebonus,		
                '' as namabonus, 		
                '' as grupbonus, 		
                '' as unitbonus, 		
                a.namasalesman as lampiran, 		
                '' as h_beli, 		
                '' as kodearea, 		
                a.alamatcustomer as namaarea, 		
                '' as pinjam, 		
                '' as jualbanyak, 		
                '' as jualpinjam, 		
                '' as harga_excl, 			
                a.namacustomer as nama_lang,		
                'D2' as nocab,		
                if(LENGTH(month(a.tanggal)) = 1, concat('0',month(a.tanggal)), month(a.tanggal)) as bulan,
                ' ' as siteid,		
                ' ' as qty1,		
                ' ' as qty2,		
                ' ' as qty3,		
                ' ' as qty_bonus,		
                ' ' as flag_bonus,		
                ' ' as disc_persen,		
                ' ' as disc_rp,		
                ' ' as disc_value,		
                ' ' as disc_cabang,		
                ' ' as disc_prinsipal,		
                ' ' as disc_xtra,		
                ' ' as rp_cabang,		
                ' ' as rp_prinsipal,		
                ' ' as rp_xtra,		
                ' ' as bonus,		
                ' ' as prinsipalid,		
                ' ' as ex_no_sales,		
                ' ' as status_retur,		
                ' ' as ref,		
                ' ' as term_payment,		
                ' ' as tipe_kl				

        from management_raw.inner_raw_batulicin a LEFT JOIN pmu.mapping b 
        on a.kodeprodukprincipal = b.kodeprod
        where a.signature = '$signature' and year(a.tanggal) = $tahun and month(a.tanggal) = $bulan and b.nocab = 'd2' and a.tipetrans <> 'sales'
        ";

        // echo "<pre>";
        // print_r($insert);
        // echo "</pre>";

        // die;

        return $this->db->query($insert);
    }

    public function insert_tblang_batulicin($site_code, $signature){
        $get_tahun = "
            select * 
            from management_raw.log_upload a 
            where a.site_code = '$site_code' and a.signature = '$signature'
        ";
        $tahun = $this->db->query($get_tahun)->row()->tahun;
        $bulan = $this->db->query($get_tahun)->row()->bulan;

        $insert = "
            insert into data$tahun.tblang
            select a.kode_comp, a.kode_kota, a.kode_type, trim(a.kode_lang), a.koderayon, a.nama_lang,
					a.namaarea as alamat1, '' as alamat2, '' as telp, 
					'' as kodepos,		
					'' as tgl,		
					'' as npwp,		
					'0' as bts_utang,		
					'0' as sales01,		
					'0' as sales02,		
					'0' as sales03,		
					'0' as sales04,		
					'0' as sales05,		
					'0' as sales06,		
					'0' as sales07,		
					'0' as sales08,		
					'0' as sales09,		
					'0' as sales10,		
					'0' as sales11,		
					'0' as sales12,		
					'0' as ket,		
					'0' as debit,		
					'0' as kredit,		
					a.kodesalur as kodesalur,		
					'0' as top,		
					'Y' as aktif,		
					'' as tgl_aktif,		
					'T' as ppn,		
					'0' as kode_lama,		
					'1' as jum_dok,		
					'0' as statjual,		
					'0' as limit1,		
					'' as tglnaktif,		
					'' as ALAMAT_WP,		
					'' as NILAI_PPN,		
					'' as NAMA_WP,		
					'' as NEWFLD,		
					a.nocab as nocab,		
					'' as kodelang_copy,		
					'' as id_provinsi,		
					'' as nama_provinsi,		
					'' as id_kota,		
					'' as nama_kota,		
					'' as id_kecamatan,		
					'' as nama_kecamatan,		
					'' as id_kelurahan,		
					'' as nama_kelurahan,		
					'' as credit_limit,		
					'' as tipe_bayar,		
					'' as phone,		
					'' AS last_updated,		
					'' as status_blacklist,		
					'' as status_payment,		
					'' as CUSTID,		
					'' as COMPID,		
					'' as LATITUDE,		
					'' as LONGITUDE,		
					'' as FOTO_DISP,		
					'' as FOTO_TOKO	
            from data$tahun.fi a 
            where a.bulan = $bulan and concat(a.kode_comp, a.nocab) = '$site_code'
            GROUP BY concat(a.kode_comp, a.kode_lang)
        ";

        // echo "<pre>";
        // print_r($insert);
        // echo "</pre>";

        // die;

        return $this->db->query($insert);
    }

    public function insert_tblang_banjarmasin($site_code, $signature){

        // insert tblang banjarmasin berfungsi create tabel temp_tblang_bantu, delete column temp tblang bantu lalu insert into tblang utama

        if ($site_code == 'BRBS0') {
            $params_temp = "temp_tblang_bantu_barabai";
            $params = "tblang_bantu_barabai";
        }else if($site_code == 'SSMS9'){
            $params_temp = "temp_tblang_bantu";
            $params = "tblang_bantu";
        }else if($site_code == 'SSJD2'){
            $params_temp = "temp_tblang_bantu_batulicin";
            $params = "tblang_bantu_batulicin";
        }else if($site_code == 'MANW5'){
            $params_temp = "temp_tblang_bantu_manado";
            $params = "tblang_bantu_manado";
        }else if($site_code == 'PTK82'){
            $params_temp = "temp_tblang_bantu_pontianak";
            $params = "tblang_bantu_pontianak";
        }else if($site_code == 'SMRB7'){
            $params_temp = "temp_tblang_bantu_samarinda";
            $params = "tblang_bantu_samarinda";
        }else if($site_code == 'BTGB8'){
            $params_temp = "temp_tblang_bantu_bontang";
            $params = "tblang_bantu_bontang";
        }else if($site_code == 'GTO87'){
            $params_temp = "temp_tblang_bantu_gorontalo";
            $params = "tblang_bantu_gorontalo";
        }else if($site_code == 'PBNP9'){
            $params_temp = "temp_tblang_bantu_pangkalanbun";
            $params = "tblang_bantu_pangkalanbun";
        }else if($site_code == 'SPTU4'){
            $params_temp = "temp_tblang_bantu_sampit";
            $params = "tblang_bantu_sampit";
        }else if($site_code == 'PKRP8'){
            $params_temp = "temp_tblang_bantu_palangkaraya";
            $params = "tblang_bantu_palangkaraya";
        }

        $get_tahun = "
            select * 
            from management_raw.log_upload a 
            where a.site_code = '$site_code' and a.signature = '$signature'
        ";
        $tahun = $this->db->query($get_tahun)->row()->tahun;

        $drop_table = "drop table if exists data$tahun.$params_temp";
        $proses_drop_table = $this->db->query($drop_table);

        if ($proses_drop_table) {
            $create_temp_table = "
            CREATE TABLE data$tahun.$params_temp AS 
            SELECT * FROM data$tahun.$params a
            where concat(a.kode_comp,a.nocab) = '$site_code' and a.id = (
                select 	max(b.id)
                from 	data$tahun.$params b
                where 	concat(a.kode_comp,a.kode_lang) = concat(b.kode_comp,b.kode_lang) and 
                        concat(a.kode_comp,a.nocab) = '$site_code'
            ) GROUP BY concat(a.kode_comp,a.kode_lang)
            "; 
            $this->db->query($create_temp_table);
            
            $drop_column_id = "
                ALTER TABLE data$tahun.$params_temp 
                DROP COLUMN id, DROP COLUMN signature
            ";
            $this->db->query($drop_column_id);
        }


        $insert_tblang = "
            insert into data$tahun.tblang
            select a.*
            from data$tahun.$params_temp a 
        ";

        return $this->db->query($insert_tblang);
    }

    

    public function insert_tblang_bantu_banjarmasin($site_code, $signature){

        // insert tblang bantu banjarmasin berfungsi untuk menambah tblang_bantu dengan data dari FI terbaru

        if ($site_code == 'BRBS0') {
            $params = "tblang_bantu_barabai";
        }else if($site_code == 'SSMS9'){
            $params = "tblang_bantu";
        }else if($site_code == 'SSJD2'){
            $params = "tblang_bantu_batulicin";
        }else if($site_code == 'MANW5'){
            $params = "tblang_bantu_manado";
        }else if($site_code == 'PTK82'){
            $params = "tblang_bantu_pontianak";
        }else if($site_code == 'SMRB7'){
            $params = "tblang_bantu_samarinda";
        }else if($site_code == 'BTGB8'){
            $params = "tblang_bantu_bontang";
        }else if($site_code == 'PBNP9'){
            $params = "tblang_bantu_pangkalanbun";
        }else if($site_code == 'SPTU4'){
            $params = "tblang_bantu_sampit";
        }else if($site_code == 'PKRP8'){
            $params = "tblang_bantu_palangkaraya";
        }

        $get_tahun = "
            select * 
            from management_raw.log_upload a 
            where a.site_code = '$site_code' and a.signature = '$signature'
        ";

        $tahun = $this->db->query($get_tahun)->row()->tahun;
        $bulan = $this->db->query($get_tahun)->row()->bulan;

        $insert_tblang_bantu = "
            insert into data$tahun.$params
            select '', a.kode_comp, a.kode_kota, a.kode_type, trim(a.kode_lang), a.koderayon, a.nama_lang,
					a.namaarea as alamat1, '' as alamat2, '' as telp, 
					'' as kodepos,		
					'' as tgl,		
					'' as npwp,		
					'0' as bts_utang,		
					'0' as sales01,		
					'0' as sales02,		
					'0' as sales03,		
					'0' as sales04,		
					'0' as sales05,		
					'0' as sales06,		
					'0' as sales07,		
					'0' as sales08,		
					'0' as sales09,		
					'0' as sales10,		
					'0' as sales11,		
					'0' as sales12,		
					'0' as ket,		
					'0' as debit,		
					'0' as kredit,		
					a.kodesalur as kodesalur,		
					'0' as top,		
					'Y' as aktif,		
					'' as tgl_aktif,		
					'T' as ppn,		
					'0' as kode_lama,		
					'1' as jum_dok,		
					'0' as statjual,		
					'0' as limit1,		
					'' as tglnaktif,		
					'' as ALAMAT_WP,		
					'' as NILAI_PPN,		
					'' as NAMA_WP,		
					'' as NEWFLD,		
					a.nocab as nocab,		
					'' as kodelang_copy,		
					'' as id_provinsi,		
					'' as nama_provinsi,		
					'' as id_kota,		
					'' as nama_kota,		
					'' as id_kecamatan,		
					'' as nama_kecamatan,		
					'' as id_kelurahan,		
					'' as nama_kelurahan,		
					'' as credit_limit,		
					'' as tipe_bayar,		
					'' as phone,		
					'' AS last_updated,		
					'' as status_blacklist,		
					'' as status_payment,		
					'' as CUSTID,		
					'' as COMPID,		
					'' as LATITUDE,		
					'' as LONGITUDE,		
					'' as FOTO_DISP,		
					'' as FOTO_TOKO, '$signature'
            from data$tahun.fi a 
            where a.bulan = $bulan and concat(a.kode_comp, a.nocab) = '$site_code'
            GROUP BY concat(a.kode_comp, a.kode_lang)

            union all
            
            select '', a.kode_comp, a.kode_kota, a.kode_type, trim(a.kode_lang), a.koderayon, a.nama_lang,
					a.namaarea as alamat1, '' as alamat2, '' as telp, 
					'' as kodepos,		
					'' as tgl,		
					'' as npwp,		
					'0' as bts_utang,		
					'0' as sales01,		
					'0' as sales02,		
					'0' as sales03,		
					'0' as sales04,		
					'0' as sales05,		
					'0' as sales06,		
					'0' as sales07,		
					'0' as sales08,		
					'0' as sales09,		
					'0' as sales10,		
					'0' as sales11,		
					'0' as sales12,		
					'0' as ket,		
					'0' as debit,		
					'0' as kredit,		
					a.kodesalur as kodesalur,		
					'0' as top,		
					'Y' as aktif,		
					'' as tgl_aktif,		
					'T' as ppn,		
					'0' as kode_lama,		
					'1' as jum_dok,		
					'0' as statjual,		
					'0' as limit1,		
					'' as tglnaktif,		
					'' as ALAMAT_WP,		
					'' as NILAI_PPN,		
					'' as NAMA_WP,		
					'' as NEWFLD,		
					a.nocab as nocab,		
					'' as kodelang_copy,		
					'' as id_provinsi,		
					'' as nama_provinsi,		
					'' as id_kota,		
					'' as nama_kota,		
					'' as id_kecamatan,		
					'' as nama_kecamatan,		
					'' as id_kelurahan,		
					'' as nama_kelurahan,		
					'' as credit_limit,		
					'' as tipe_bayar,		
					'' as phone,		
					'' AS last_updated,		
					'' as status_blacklist,		
					'' as status_payment,		
					'' as CUSTID,		
					'' as COMPID,		
					'' as LATITUDE,		
					'' as LONGITUDE,		
					'' as FOTO_DISP,		
					'' as FOTO_TOKO, '$signature'
            from data$tahun.ri a 
            where a.bulan = $bulan and concat(a.kode_comp, a.nocab) = '$site_code'
            GROUP BY concat(a.kode_comp, a.kode_lang)
        ";

        return $this->db->query($insert_tblang_bantu);
    }

    public function update_tblang_bantu($site_code, $signature){

        // update tblang bantu berfungsi mengupdate data tblang bantu dengan data dari raw customer barabai

        if ($site_code == 'BRBS0') {
            $params = "tblang_bantu_barabai";
            $params_customer = "raw_customer_barabai";
        }else if($site_code == 'SSMS9'){
            $params = "tblang_bantu";
            $params_customer = "raw_customer_banjarmasin";
        }else if($site_code == 'SSJD2'){
            $params = "tblang_bantu_batulicin";
            $params_customer = "raw_customer_batulicin";
        }else if($site_code == 'GTO87'){
            $params = "tblang_bantu_gorontalo";
            $params_customer = "raw_customer_gorontalo";
        }else if($site_code == 'PBNP9'){
            $params = "tblang_bantu_pangkalanbun";
            $params_customer = "raw_customer_pangkalanbun";
        }else if($site_code == 'MANW5'){
            $params = "tblang_bantu_manado";
            $params_customer = "raw_customer_manado";
        }else if($site_code == 'SPTU4'){
            $params = "tblang_bantu_sampit";
            $params_customer = "raw_customer_sampit";
        }else if($site_code == 'PKRP8'){
            $params = "tblang_bantu_palangkaraya";
            $params_customer = "raw_customer_palangkaraya";
        }

        $get_tahun = "
            select * 
            from management_raw.log_upload a 
            where a.site_code = '$site_code' and a.signature = '$signature'
        ";
        $tahun = $this->db->query($get_tahun)->row()->tahun;
        $created_at = $this->db->query($get_tahun)->row()->created_at;

        $update = "
            update data$tahun.$params a
            set a.alamat1 = (
                select b.alamat
                from management_raw.$params_customer b
                where b.customer_id = a.kode_lang 
                GROUP BY b.customer_id
            ), a.id_kota = (
                select b.kode_kota
                from management_raw.$params_customer b
                where b.customer_id = a.kode_lang 
                GROUP BY b.customer_id
            ), a.nama_kota = (
                select b.nama_kota
                from management_raw.$params_customer b
                where b.customer_id = a.kode_lang 
                GROUP BY b.customer_id
            ), a.id_kecamatan = (
                select b.kode_kecamatan
                from management_raw.$params_customer b
                where b.customer_id = a.kode_lang 
                GROUP BY b.customer_id
            ), a.nama_kecamatan = (
                select b.nama_kecamatan
                from management_raw.$params_customer b
                where b.customer_id = a.kode_lang 
                GROUP BY b.customer_id
            ), a.id_kelurahan = (
                select b.kode_kelurahan
                from management_raw.$params_customer b
                where b.customer_id = a.kode_lang 
                GROUP BY b.customer_id
            ), a.nama_kelurahan = (
                select b.nama_kelurahan
                from management_raw.$params_customer b
                where b.customer_id = a.kode_lang 
                GROUP BY b.customer_id
            ), a.kode_type = (
                select b.kode_type
                from management_raw.$params_customer b
                where b.customer_id = a.kode_lang 
                GROUP BY b.customer_id
            ), a.kodesalur = (
                select b.kode_class
                from management_raw.$params_customer b
                where b.customer_id = a.kode_lang 
                GROUP BY b.customer_id
            ), a.last_updated = '$created_at'
            where concat(a.kode_comp, a.nocab) = '$site_code' and a.signature = '$signature'
        ";
        
        // echo "<pre>";
        // print_r($update);
        // echo "</pre>";
        
        // die;
        $proses = $this->db->query($update);


        return $proses;
    }

    public function prepare_tblang_bantu($site_code, $signature){

        //prepare adalah mem-backup data2023.tblang ke dalam tabel tblang_bantu_barabai namun ditambahkan kolom id dan signature

        if ($site_code == 'BRBS0') {
            $params = "tblang_bantu_barabai";
        }else if($site_code == 'SSMS9'){
            $params = "tblang_bantu";
        }else if($site_code == 'SSJD2'){
            $params = "tblang_bantu_batulicin";
        }else if($site_code == 'MANW5'){
            $params = "tblang_bantu_manado";
        }else if($site_code == 'PTK82'){
            $params = "tblang_bantu_pontianak";
        }else if($site_code == 'SMRB7'){
            $params = "tblang_bantu_samarinda";
        }else if($site_code == 'BTGB8'){
            $params = "tblang_bantu_bontang";
        }else if($site_code == 'GTO87'){
            $params = "tblang_bantu_gorontalo";
        }else if($site_code == 'PBNP9'){
            $params = "tblang_bantu_pangkalanbun";
        }else if($site_code == 'SPTU4'){
            $params = "tblang_bantu_sampit";
        }else if($site_code == 'PKRP8'){
            $params = "tblang_bantu_palangkaraya";
        }

        $get_tahun = "
            select * 
            from management_raw.log_upload a 
            where a.site_code = '$site_code' and a.signature = '$signature'
        ";
        $tahun = $this->db->query($get_tahun)->row()->tahun;
        $created_at = $this->db->query($get_tahun)->row()->created_at;

        // echo "tahun : ".$tahun;
        // echo "site_code : ".$site_code;

        // die;

        $truncate = "
            truncate data$tahun.$params
        ";
        $proses = $this->db->query($truncate);

        // echo "<pre>";
        // print_r($truncate);
        // echo "</pre>";

        // die;

        if ($proses) {
            
            $insert = "
                insert into data$tahun.$params 
                select '', a.*, '$signature'
                from data$tahun.tblang a 
                where concat(a.kode_comp, a.nocab) = '$site_code'
            ";
            $proses_insert = $this->db->query($insert);
        }

        // die;

        return $proses_insert;
    }

    public function insert_tabsales_batulicin($site_code, $signature){
        $get_tahun = "
            select * 
            from management_raw.log_upload a 
            where a.site_code = '$site_code' and a.signature = '$signature'
        ";
        $tahun = $this->db->query($get_tahun)->row()->tahun;
        $bulan = $this->db->query($get_tahun)->row()->bulan;

        $insert = "
            insert into data$tahun.tabsales
            select *
            from 
            (
                select  a.kodesales,		
                        a.lampiran as namasales,		
                        ''AS koderayon,		
                        'S'AS `status`,		
                        a.namaarea AS alamat1,		
                        a.namaarea AS alamat2,		
                        ''AS NO_TELP,		
                        '' AS KODEPOS,		
                        '' AS PROPINSI,		
                        '' AS DATA1,		
                        '' AS TAHAP,		
                        '' AS FILEID,		
                        '' AS NAMA_DEPO,		
                        a.kode_kota,		
                        '' AS KODE_GDG,		
                        '' AS NAMA_GDG,		
                        'Y' AS AKTIF,		
                        a.nocab
                from data$tahun.fi a 
                where a.bulan = $bulan and concat(a.kode_comp, a.nocab) = '$site_code'
                union all
                select  a.kodesales,		
                        a.lampiran as namasales,		
                        ''AS koderayon,		
                        'S'AS `status`,		
                        a.namaarea AS alamat1,		
                        a.namaarea AS alamat2,		
                        ''AS NO_TELP,		
                        '' AS KODEPOS,		
                        '' AS PROPINSI,		
                        '' AS DATA1,		
                        '' AS TAHAP,		
                        '' AS FILEID,		
                        '' AS NAMA_DEPO,		
                        a.kode_kota,		
                        '' AS KODE_GDG,		
                        '' AS NAMA_GDG,		
                        'Y' AS AKTIF,		
                        a.nocab
                from data$tahun.ri a 
                where a.bulan = $bulan and concat(a.kode_comp, a.nocab) = '$site_code'
            )a GROUP BY a.kodesales
        ";

        // echo "<pre>";
        // print_r($insert);
        // echo "</pre>";

        // die;

        return $this->db->query($insert);
    }

    public function insert_tbkota_batulicin($site_code, $signature){
        $get_tahun = "
            select * 
            from management_raw.log_upload a 
            where a.site_code = '$site_code' and a.signature = '$signature'
        ";
        $tahun = $this->db->query($get_tahun)->row()->tahun;
        $bulan = $this->db->query($get_tahun)->row()->bulan;

        $insert = "
            insert into data$tahun.tbkota
            select  KODE_COMP AS KODE_COMP, 		
                    a.kode_kota AS KODE_KOTA,		
                    'BATULICIN' AS NAMA_KOTA,		
                    a.nocab as nocab		
            from data$tahun.fi a 
            where a.bulan = $bulan and concat(a.kode_comp, a.nocab) = '$site_code'
            GROUP BY a.kode_kota
        ";

        // echo "<pre>";
        // print_r($insert);
        // echo "</pre>";

        // die;

        return $this->db->query($insert);
    }

    public function insert_tbkota_samarinda($site_code, $signature){

        if ($site_code == 'SMRB7') {
            $params_site_code = "inner_raw_samarinda"; 
        }elseif ($site_code == 'BTGB8') {
            $params_site_code = "inner_raw_bontang"; 
        }elseif ($site_code == 'PTK82') {
            $params_site_code = "inner_raw_pontianak"; 
        }

        $get_tahun = "
            select * 
            from management_raw.log_upload a 
            where a.site_code = '$site_code' and a.signature = '$signature'
        ";
        $tahun = $this->db->query($get_tahun)->row()->tahun;
        // $bulan = $this->db->query($get_tahun)->row()->bulan;

        $nocab = substr($site_code, 3, 2);
        $kode_comp = substr($site_code, 0, 3);

        $insert = "
            insert into data$tahun.tbkota
            select '$kode_comp', a.kotaid, a.nama_kota, '$nocab'
            from management_raw.$params_site_code a 
            where a.signature = '$signature'
            GROUP BY a.kotaid
        ";

        // echo "<pre>";
        // print_r($insert);
        // echo "</pre>";

        // die;

        return $this->db->query($insert);
    }

    public function insert_tbkota_banjarmasin($site_code, $signature){
        
        if ($site_code == 'SSMS9') {
            $params_site_code = "inner_raw_banjarmasin"; 
        }elseif ($site_code == 'SSJD2') {
            $params_site_code = "inner_raw_batulicin"; 
        }elseif ($site_code == 'BRBS0') {
            $params_site_code = "inner_raw_barabai"; 
        }elseif ($site_code == 'SMRB7') {
            $params_site_code = "inner_raw_samarinda"; 
        }

        $get_tahun = "
            select * 
            from management_raw.log_upload a 
            where a.site_code = '$site_code' and a.signature = '$signature'
        ";
        $tahun = $this->db->query($get_tahun)->row()->tahun;
        // $bulan = $this->db->query($get_tahun)->row()->bulan;

        $nocab = substr($site_code, 3, 2);
        $kode_comp = substr($site_code, 0, 3);

        $insert = "
            insert into data$tahun.tbkota
            select '$kode_comp', a.kode_kota, a.nama_kota, '$nocab'
            from management_raw.$params_site_code a 
            where a.signature = '$signature'
            GROUP BY a.kode_kota
        ";

        // echo "<pre>";
        // print_r($insert);
        // echo "</pre>";

        // die;

        return $this->db->query($insert);
    }

    public function get_log_upload_by_signature($signature){

        $query = "select * from management_raw.log_upload a where a.signature = '$signature'";
        return $this->db->query($query);

    }

    public function get_omzet_web($tahun, $bulan, $site_code){
        $query = "
            select sum(omzet) as omzet
            from 
            (
                select sum(a.tot1) as omzet
                from data$tahun.fi a 
                where a.bulan = $bulan and concat(a.kode_comp, a.nocab) = '$site_code'
                union all
                select sum(a.tot1) as omzet
                from data$tahun.ri a 
                where a.bulan = $bulan and concat(a.kode_comp, a.nocab) = '$site_code'
            )a
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        return $this->db->query($query);
    }

    public function get_raw_customer($site_code, $customer_id_nd6){
        if ($site_code == 'BRBS0') {
            $params_site_code = "raw_customer_barabai"; 
        }elseif($site_code == 'SSMS9'){
            $params_site_code = "raw_customer_banjarmasin";
        }
        $query = "
            select *
            from management_raw.$params_site_code a 
            where a.customer_id_nd6 = '$customer_id_nd6'
        ";
        return $this->db->query($query);
    }

    public function get_summary_raw_sales($site_code, $signature = ''){

        if ($site_code == 'SSMS9') {
            $params_site_code = "raw_banjarmasin"; 
        }elseif ($site_code == 'SSJD2') {
            $params_site_code = "raw_batulicin"; 
        }elseif ($site_code == 'BRBS0') {
            $params_site_code = "raw_barabai"; 
        }elseif ($site_code == 'SMRB7') {
            $params_site_code = "raw_samarinda"; 
        }

        if ($signature) {
            $params = "where a.signature = '$signature'";
        }else{
            $params = "";
        }

        $query = "
            select count(*) as count_raw, sum(a.grossamount * 1.11) as raw_bruto, sum(a.grossamount) as raw_exclude_ppn 
            from management_raw.$params_site_code a
            $params
        ";
        return $this->db->query($query);
    }

    public function get_summary_raw_sales_samarinda($site_code, $signature = ''){

        if ($site_code == 'SSMS9') {
            $params_site_code = "raw_banjarmasin"; 
        }elseif ($site_code == 'SSJD2') {
            $params_site_code = "raw_batulicin"; 
        }elseif ($site_code == 'BRBS0') {
            $params_site_code = "raw_barabai"; 
        }elseif ($site_code == 'SMRB7') {
            $params_site_code = "raw_samarinda"; 
        }elseif ($site_code == 'BTGB8') {
            $params_site_code = "raw_bontang"; 
        }elseif ($site_code == 'PTK82') {
            $params_site_code = "raw_pontianak"; 
        }

        if ($signature) {
            $params = "where a.signature = '$signature'";
        }else{
            $params = "";
        }

        $query = "
            select count(*) as count_raw, sum(a.rp_kotor) as raw_bruto, sum(a.rp_netto) as raw_netto 
            from management_raw.$params_site_code a
            $params
        ";
        return $this->db->query($query);
    }

    public function update_kodeproduk_kendari($site_code, $signature){

        if ($site_code == 'CSSK3') {
            $params_site_code = "raw_kendari"; 
        }

        $query = "
            update management_raw.$params_site_code a 
            set a.kodeprod = concat(0,a.kodeprod)
            where a.signature = '$signature' and length(a.kodeprod) = 5 
        ";
        $update = $this->db->query($query);

        if ($update) {
            return $update;
        }else{
            return array();
        }
    }

    public function inner_kodeproduk_kendari($site_code, $signature){

        if ($site_code == 'SSMS9') {
            $params_site_code = "inner_raw_banjarmasin"; 
            $params_site_code2 = "raw_banjarmasin"; 
        }elseif ($site_code == 'SSJD2') {
            $params_site_code = "inner_raw_batulicin"; 
            $params_site_code2 = "raw_batulicin"; 
        }elseif ($site_code == 'BRBS0') {
            $params_site_code = "inner_raw_barabai"; 
            $params_site_code2 = "raw_barabai"; 
        }elseif ($site_code == 'SMRB7') {
            $params_site_code = "inner_raw_samarinda"; 
            $params_site_code2 = "raw_samarinda"; 
        }elseif ($site_code == 'CSSK3') {
            $params_site_code = "inner_raw_kendari"; 
            $params_site_code2 = "raw_kendari"; 
        }

        $query = "
            insert into management_raw.$params_site_code
            select '', '$site_code', '', '', a.*, b.supp, b.grupprod, '', '', ''
            from management_raw.$params_site_code2 a INNER JOIN (
                select a.kodeprod, a.supp, a.grupprod
                from mpm.tabprod a 
            )b on a.kodeprod = b.kodeprod
            where a.signature = '$signature'
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        // die;
        $update = $this->db->query($query);
        if ($update) {
            return $update;
        }else{
            return array();
        }
    }

    public function update_namaprod_kendari($site_code, $signature){

        if ($site_code == 'SSMS9') {
            $params_site_code = "inner_raw_banjarmasin"; 
        }elseif ($site_code == 'SSJD2') {
            $params_site_code = "inner_raw_batulicin"; 
        }elseif ($site_code == 'BRBS0') {
            $params_site_code = "inner_raw_barabai"; 
        }elseif ($site_code == 'SMRB7') {
            $params_site_code = "inner_raw_samarinda"; 
        }elseif ($site_code == 'CSSK3') {
            $params_site_code = "inner_raw_kendari"; 
        }

        $query = "
            update management_raw.$params_site_code a 
            set a.namaprod = (
                select b.namaprod
                from mpm.tabprod b 
                where a.kodeprod = b.kodeprod
            ), a.map_divisi = (
                select b.new_divisi
                from mpm.tabprod b 
                where a.kodeprod = b.kodeprod
            ), a.map_group = (
                select b.new_group
                from mpm.tabprod b 
                where a.kodeprod = b.kodeprod
            ), a.map_subgroup = (
                select b.new_subgroup
                from mpm.tabprod b 
                where a.kodeprod = b.kodeprod
            )
            where a.signature = '$signature'
        ";
        $update = $this->db->query($query);
        if ($update) {
            return $update;
        }else{
            return array();
        }
    }

    public function update_tanggal_kendari($site_code, $signature){
        
        if ($site_code == 'SSMS9') {
            $params_site_code = "inner_raw_banjarmasin"; 
        }elseif ($site_code == 'SSJD2') {
            $params_site_code = "inner_raw_batulicin"; 
        }elseif ($site_code == 'BRBS0') {
            $params_site_code = "inner_raw_barabai"; 
        }elseif ($site_code == 'SMRB7') {
            $params_site_code = "inner_raw_samarinda"; 
        }elseif ($site_code == 'CSSK3') {
            $params_site_code = "inner_raw_kendari"; 
        }

        $query = "
            update management_raw.$params_site_code a 
            set a.tgldokjdi = str_to_date(a.tgldokjdi,'%Y-%m-%d')
            where a.signature = '$signature'
        ";
        $update = $this->db->query($query);
        if ($update) {
            return $update;
        }else{
            return array();
        }
    }

    public function insert_fi_kendari($site_code, $signature){
        $get_tahun = "
            select * 
            from management_raw.log_upload a 
            where a.site_code = '$site_code' and a.signature = '$signature'
        ";
        $tahun = $this->db->query($get_tahun)->row()->tahun;
        $bulan = $this->db->query($get_tahun)->row()->bulan;


        $insert = "
        insert data$tahun.fi
        select 				
        KDDOKJDI as KDOKJDI,				
        NODOKJDI as NODOKJDI,				
        NODOKACU as NODOKACU,				
        tgldokjdi as TGLDOKJDI,				
        KODEJAJA as KODESALES,				
        'CSS' as KODE_COMP,				
        AREA as KODE_KOTA,				
        case 				
        when `desclang` like'%Apotik%' or `desclang` like'%Apotek%' THEN 'APT'				
        when `desclang` like'%Mart%'or `desclang` like '%MM%' THEN 'MML'				
        when `desclang` like'%Supermarket%'or `desclang` like '%Super market%' THEN 'MMN'				
        when `desclang` like'%PT%' THEN 'PBF'				
        ELSE 'TKL'				
        END 				
        as KODE_TYPE,				
        kodelang as KODE_LANG,				
        '' as KODERAYON,				
        c.KODEPROD as KODEPROD,				
        a.SUPP,				
        right(TGLDOKJDI,2) as HRDOK,				
        '$bulan' as BLNDOK,				
        left(TGLDOKJDI,4) as THNDOK,				
        c.NAMAPROD as NAMAPROD,				
        c.grupprod as GROUPPROD,				
        (BANYAK-BANYS)*b.SATUAN as BANYAK,				
        round((HNA*1.11)/b.SATUAN) as HARGA,				
        round(potongan*1.11) as POTONGAN,				
        round(((BANYAK-BANYS)*HNA)*1.11) as TOT1,				
        '' as JUM_PROMO, 				
        if(BANYS <> '0',concat(c.KODEPROD,' ',BANYS),'') as KETERANGAN, 				
        '' as USER_ISI,				
        '' as JAM_ISI,				
        '' as TGL_ISI, 				
        '' as USER_EDIT, 				
        '' as JAM_EDIT, 				
        '' as TGL_EDIT,				
        '' as USER_DEL, 				
        '' as JAM_DEL, 				
        '' as TGL_DEL, 				
        '' as NO, 				
        '' as BACKUP, 				
        '' as NO_URUT, 				
        'PST' as KODE_GDG, 				
        '' as NAMA_GDG, 				
        IF(CLASS='RETAIL','RT','WS') as KODESALUR, 				
        '' as KODEBONUS,				
        '' as NAMABONUS, 				
        '' as GRUPBONUS, 				
        '0' as UNITBONUS, 				
        NAMAJAJA as LAMPIRAN, 				
        '' as H_BELI, 				
        '' as KODEAREA, 				
        ALMTLANG as NAMAAREA, 				
        '' as PINJAM, 				
        '' as JUALBANYAK, 				
        '' as JUALPINJAM, 				
        '' as HARGA_EXCL, 				
        '' as TOT1_EXCL, 				
        NAMALANG as NAMA_LANG,				
        'K3' as NOCAB,				
        '$bulan' as BULAN,				
        ' ' as siteid,				
        ' ' as qty1,				
        ' ' as qty2,				
        ' ' as qty3,				
        '0' as qty_bonus,				
        '0' as flag_bonus,				
        ' ' as disc_persen,				
        ' ' as disc_rp,				
        ' ' as disc_value,				
        ' ' as disc_cabang,				
        ' ' as disc_prinsipal,				
        ' ' as disc_xtra,				
        ' ' as rp_cabang,				
        ' ' as rp_prinsipal,				
        ' ' as rp_xtra,				
        ' ' as bonus,				
        ' ' as prinsipalid,				
        ' ' as ex_no_sales,				
        ' ' as status_retur,				
        ' ' as ref,				
        ' ' as term_payment,				
        ' ' as tipe_kl				
                        
        from management_raw.inner_raw_kendari a 				
        left JOIN pmu.mapping b on a.kodeprod=b.`code`				
        LEFT JOIN mpm.tabprod c ON a.kodeprod=c.kodeprod				
        where (banyak not like'%-%' or tothna not like'%-%') and a.signature = '$signature' and year(a.tgldokjdi) = $tahun 
        and month(a.tgldokjdi) = $bulan and b.nocab = 'K3'

        union all

        select 				
        KDDOKJDI as KDOKJDI,				
        NODOKJDI as NODOKJDI,				
        NODOKACU as NODOKACU,				
        tgldokjdi as TGLDOKJDI,				
        KODEJAJA as KODESALES,				
        'CSS' as KODE_COMP,				
        AREA as KODE_KOTA,				
        case 				
        when `desclang` like'%Apotik%' or `desclang` like'%Apotek%' THEN 'APT'				
        when `desclang` like'%Mart%'or `desclang` like '%MM%' THEN 'MML'				
        when `desclang` like'%Supermarket%'or `desclang` like '%Super market%' THEN 'MMN'				
        when `desclang` like'%PT%' THEN 'PBF'				
        ELSE 'TKL'				
        END 				
        as KODE_TYPE,				
        kodelang as KODE_LANG,				
        '' as KODERAYON,				
        c.KODEPROD as KODEPROD,				
        a.SUPP,				
        right(TGLDOKJDI,2) as HRDOK,				
        '$bulan' as BLNDOK,				
        left(TGLDOKJDI,4) as THNDOK,				
        c.NAMAPROD as NAMAPROD,				
        c.grupprod as GROUPPROD,				
        '0' as BANYAK,				
        '0' as HARGA,				
        '0' as POTONGAN,				
        '0' as TOT1,				
        '' as JUM_PROMO, 				
        if(BANYS <> '0',concat(c.KODEPROD,' ',BANYS),'') as KETERANGAN, 				
        '' as USER_ISI,				
        '' as JAM_ISI,				
        '' as TGL_ISI, 				
        '' as USER_EDIT, 				
        '' as JAM_EDIT, 				
        '' as TGL_EDIT,				
        '' as USER_DEL, 				
        '' as JAM_DEL, 				
        '' as TGL_DEL, 				
        '' as NO, 				
        '' as BACKUP, 				
        '' as NO_URUT, 				
        'PST' as KODE_GDG, 				
        '' as NAMA_GDG, 				
        IF(CLASS='RETAIL','RT','WS') as KODESALUR, 				
        '' as KODEBONUS,				
        '' as NAMABONUS, 				
        '' as GRUPBONUS, 				
        BANYS*b.satuan as UNITBONUS, 				
        NAMAJAJA as LAMPIRAN, 				
        '' as H_BELI, 				
        '' as KODEAREA, 				
        ALMTLANG as NAMAAREA, 				
        '' as PINJAM, 				
        '' as JUALBANYAK, 				
        '' as JUALPINJAM, 				
        '' as HARGA_EXCL, 				
        '' as TOT1_EXCL, 				
        NAMALANG as NAMA_LANG,				
        'K3' as NOCAB,				
        '$bulan' as BULAN,				
        ' ' as siteid,				
        ' ' as qty1,				
        ' ' as qty2,				
        ' ' as qty3,				
        BANYS*b.satuan as qty_bonus,				
        '1' as flag_bonus,				
        ' ' as disc_persen,				
        ' ' as disc_rp,				
        ' ' as disc_value,				
        ' ' as disc_cabang,				
        ' ' as disc_prinsipal,				
        ' ' as disc_xtra,				
        ' ' as rp_cabang,				
        ' ' as rp_prinsipal,				
        ' ' as rp_xtra,				
        ' ' as bonus,				
        ' ' as prinsipalid,				
        ' ' as ex_no_sales,				
        ' ' as status_retur,				
        ' ' as ref,				
        ' ' as term_payment,				
        ' ' as tipe_kl				
                        
        from management_raw.inner_raw_kendari a 				
        left JOIN pmu.mapping b on a.kodeprod=b.`code`				
        LEFT JOIN mpm.tabprod c ON a.kodeprod=c.kodeprod				
        where (banyak not like'%-%' or tothna not like'%-%') and a.signature = '$signature' and year(a.tgldokjdi) = $tahun 
        and month(a.tgldokjdi) = $bulan and b.nocab = 'K3' and BANYS != 0
        order by harga
        ";

        // echo "<pre>";
        // print_r($insert);
        // echo "</pre>";

        return $this->db->query($insert);
    }

    public function insert_ri_kendari($site_code, $signature){
        $get_tahun = "
            select * 
            from management_raw.log_upload a 
            where a.site_code = '$site_code' and a.signature = '$signature'
        ";
        $tahun = $this->db->query($get_tahun)->row()->tahun;
        $bulan = $this->db->query($get_tahun)->row()->bulan;

        $insert = "
        insert data$tahun.ri
        select 				
        KDDOKJDI as KDOKJDI,				
        NODOKJDI as NODOKJDI,				
        NODOKACU as NODOKACU,				
        tgldokjdi as TGLDOKJDI,				
        KODEJAJA as KODESALES,				
        'CSS' as KODE_COMP,				
        AREA as KODE_KOTA,				
        case 				
        when `desclang` like'%Apotik%' or `desclang` like'%Apotek%' THEN 'APT'				
        when `desclang` like'%Mart%'or `desclang` like '%MM%' THEN 'MML'				
        when `desclang` like'%Supermarket%'or `desclang` like '%Super market%' THEN 'MMN'				
        when `desclang` like'%PT%' THEN 'PBF'		
        ELSE 'TKL'				
        END 				
        as KODE_TYPE,				
        kodelang as KODE_LANG,				
        '' as KODERAYON,				
        c.KODEPROD as KODEPROD,				
        a.SUPP,				
        right(TGLDOKJDI,2) as HRDOK,				
        '$bulan' as BLNDOK,				
        left(TGLDOKJDI,4) as THNDOK,				
        c.NAMAPROD as NAMAPROD,				
        c.grupprod as GROUPPROD,				
        (BANYAK-BANYS)*b.SATUAN as BANYAK,				
        round((HNA*1.11)/b.SATUAN) as HARGA,				
        round(potongan*1.11) as POTONGAN,				
        round(((BANYAK-BANYS)*HNA)*1.11) as TOT1,				
        '' as JUM_PROMO, 				
        if(BANYS <> '0',concat(c.KODEPROD,' ',BANYS),'') as KETERANGAN, 				
        '' as USER_ISI,				
        '' as JAM_ISI,				
        '' as TGL_ISI, 				
        '' as USER_EDIT, 				
        '' as JAM_EDIT, 				
        '' as TGL_EDIT,				
        '' as USER_DEL, 				
        '' as JAM_DEL, 				
        '' as TGL_DEL, 				
        '' as NO, 				
        '' as BACKUP, 				
        '' as NO_URUT, 				
        'PST' as KODE_GDG, 				
        '' as NAMA_GDG, 				
        IF(CLASS='RETAIL','RT','WS') as KODESALUR, 				
        '' as KODEBONUS,				
        '' as NAMABONUS, 				
        '' as GRUPBONUS, 				
        '0' as UNITBONUS, 				
        NAMAJAJA as LAMPIRAN, 				
        '' as H_BELI, 				
        '' as KODEAREA, 				
        ALMTLANG as NAMAAREA, 				
        '' as PINJAM, 				
        '' as JUALBANYAK, 				
        '' as JUALPINJAM, 				
        '' as HARGA_EXCL, 				
        NAMALANG as NAMA_LANG,				
        'K3' as NOCAB,				
        '$bulan' as BULAN,				
        ' ' as siteid,				
        ' ' as qty1,				
        ' ' as qty2,				
        ' ' as qty3,				
        '0' as qty_bonus,				
        '0' as flag_bonus,				
        ' ' as disc_persen,				
        ' ' as disc_rp,				
        ' ' as disc_value,				
        ' ' as disc_cabang,				
        ' ' as disc_prinsipal,				
        ' ' as disc_xtra,				
        ' ' as rp_cabang,				
        ' ' as rp_prinsipal,				
        ' ' as rp_xtra,				
        ' ' as bonus,				
        ' ' as prinsipalid,				
        ' ' as ex_no_sales,				
        ' ' as status_retur,				
        ' ' as ref,				
        ' ' as term_payment,				
        ' ' as tipe_kl				
                        
        from management_raw.inner_raw_kendari a 				
        left JOIN pmu.mapping b on a.kodeprod=b.`code`				
        LEFT JOIN mpm.tabprod c ON a.kodeprod=c.kodeprod				
        where (banyak like'%-%' or tothna like'%-%') and a.signature = '$signature' and year(a.tgldokjdi) = $tahun 
        and month(a.tgldokjdi) = $bulan and b.nocab = 'K3'

        union all

        select 				
        KDDOKJDI as KDOKJDI,				
        NODOKJDI as NODOKJDI,				
        NODOKACU as NODOKACU,				
        tgldokjdi as TGLDOKJDI,				
        KODEJAJA as KODESALES,				
        'CSS' as KODE_COMP,				
        AREA as KODE_KOTA,				
        case 				
        when `desclang` like'%Apotik%' or `desclang` like'%Apotek%' THEN 'APT'				
        when `desclang` like'%Mart%'or `desclang` like '%MM%' THEN 'MML'				
        when `desclang` like'%Supermarket%'or `desclang` like '%Super market%' THEN 'MMN'				
        when `desclang` like'%PT%' THEN 'PBF'		
        ELSE 'TKL'				
        END 				
        as KODE_TYPE,				
        kodelang as KODE_LANG,				
        '' as KODERAYON,				
        c.KODEPROD as KODEPROD,				
        a.SUPP,				
        right(TGLDOKJDI,2) as HRDOK,				
        '$bulan' as BLNDOK,				
        left(TGLDOKJDI,4) as THNDOK,				
        c.NAMAPROD as NAMAPROD,				
        c.grupprod as GROUPPROD,				
        '0' as BANYAK,				
        '0' as HARGA,				
        '0' as POTONGAN,				
        '0' as TOT1,				
        '' as JUM_PROMO, 				
        if(BANYS <> '0',concat(c.KODEPROD,' ',BANYS),'') as KETERANGAN, 				
        '' as USER_ISI,				
        '' as JAM_ISI,				
        '' as TGL_ISI, 				
        '' as USER_EDIT, 				
        '' as JAM_EDIT, 				
        '' as TGL_EDIT,				
        '' as USER_DEL, 				
        '' as JAM_DEL, 				
        '' as TGL_DEL, 				
        '' as NO, 				
        '' as BACKUP, 				
        '' as NO_URUT, 				
        'PST' as KODE_GDG, 				
        '' as NAMA_GDG, 				
        IF(CLASS='RETAIL','RT','WS') as KODESALUR, 				
        '' as KODEBONUS,				
        '' as NAMABONUS, 				
        '' as GRUPBONUS, 				
        BANYS*b.satuan as UNITBONUS, 				
        NAMAJAJA as LAMPIRAN, 				
        '' as H_BELI, 				
        '' as KODEAREA, 				
        ALMTLANG as NAMAAREA, 				
        '' as PINJAM, 				
        '' as JUALBANYAK, 				
        '' as JUALPINJAM, 				
        '' as HARGA_EXCL, 				
        NAMALANG as NAMA_LANG,				
        'K3' as NOCAB,				
        '$bulan' as BULAN,				
        ' ' as siteid,				
        ' ' as qty1,				
        ' ' as qty2,				
        ' ' as qty3,				
        BANYS*b.satuan as qty_bonus,				
        '1' as flag_bonus,				
        ' ' as disc_persen,				
        ' ' as disc_rp,				
        ' ' as disc_value,				
        ' ' as disc_cabang,				
        ' ' as disc_prinsipal,				
        ' ' as disc_xtra,				
        ' ' as rp_cabang,				
        ' ' as rp_prinsipal,				
        ' ' as rp_xtra,				
        ' ' as bonus,				
        ' ' as prinsipalid,				
        ' ' as ex_no_sales,				
        ' ' as status_retur,				
        ' ' as ref,				
        ' ' as term_payment,				
        ' ' as tipe_kl				
                        
        from management_raw.inner_raw_kendari a 				
        left JOIN pmu.mapping b on a.kodeprod=b.`code`				
        LEFT JOIN mpm.tabprod c ON a.kodeprod=c.kodeprod				
        where (banyak like'%-%' or tothna like'%-%') and a.signature = '$signature' and year(a.tgldokjdi) = $tahun 
        and month(a.tgldokjdi) = $bulan and b.nocab = 'K3' and BANYS != 0
        order by harga
        ";

        // echo "<pre>";
        // print_r($insert);
        // echo "</pre>";

        // die;

        return $this->db->query($insert);
    }

    public function insert_tblang_kendari($site_code, $signature){
        $get_tahun = "
            select * 
            from management_raw.log_upload a 
            where a.site_code = '$site_code' and a.signature = '$signature'
        ";
        $tahun = $this->db->query($get_tahun)->row()->tahun;
        $bulan = $this->db->query($get_tahun)->row()->bulan;

        $insert = "
            insert into data$tahun.tblang
            select * from(				
            SELECT				
            KODE_COMP,				
            KODE_KOTA,				
            KODE_TYPE,				
            KODE_LANG,				
            KODERAYON,				
            NAMA_LANG,				
            NAMAAREA as ALAMAT1,				
            '' as ALAMAT2,				
            '' as TELP,				
            '' as KODEPOS,				
            '' as TGL,				
            '' as NPWP,				
            '0' as BTS_UTANG,				
            '0' as SALES01,				
            '0' as SALES02,				
            '0' as SALES03,				
            '0' as SALES04,				
            '0' as SALES05,				
            '0' as SALES06,				
            '0' as SALES07,				
            '0' as SALES08,				
            '0' as SALES09,				
            '0' as SALES10,				
            '0' as SALES11,				
            '0' as SALES12,				
            '0' as KET,				
            '0' as DEBIT,				
            '0' as KREDIT,				
            KODESALUR as KODESALUR,				
            '0' as TOP,				
            'Y' as AKTIF,				
            '' as TGL_AKTIF,				
            'T' as PPN,				
            '0' as KODE_LAMA,				
            '1' as JUM_DOK,				
            '0' as STATJUAL,				
            '0' as LIMIT1,				
            '' as TGLNAKTIF,				
            '' as ALAMAT_WP,				
            '' as NILAI_PPN,				
            '' as NAMA_WP,				
            '' as NEWFLD,				
            nocab as NOCAB,				
            '' as kodelang_copy,				
            '' as id_provinsi,				
            '' as nama_provinsi,				
            '' as id_kota,				
            '' as nama_kota,				
            '' as id_kecamatan,				
            '' as nama_kecamatan,				
            '' as id_kelurahan,				
            '' as nama_kelurahan,				
            '' as phone,				
            '' as tipe_bayar,				
            '' as credit_limit,				
            '' AS last_updated,				
            '' as status_blacklist,				
            '' as status_payment,				
            '' as CUSTID,				
            '' as COMPID,				
            '' as LATITUDE,				
            '' as LONGITUDE,				
            '' as FOTO_DISP,				
            '' as FOTO_TOKO				
                            
            FROM(				
                SELECT CONCAT(KODE_COMP,KODE_LANG,max(BULAN)) as mapp				
                FROM DATA$tahun.fi 
                WHERE concat(kode_comp, nocab) = '$site_code' 				
                GROUP BY kode_comp,KODE_LANG 				
            )A				
            LEFT JOIN 				
            (				
            SELECT * FROM(				
                SELECT *, CONCAT(KODE_COMP, KODE_LANG,BULAN) as mapp			
                FROM DATA$tahun.fi  				
                WHERE concat(kode_comp, nocab) = '$site_code'				
                GROUP BY MAPP 				
                )A				
            )C USING(MAPP)				
            union ALL				
            SELECT				
            KODE_COMP,				
            KODE_KOTA,				
            KODE_TYPE,				
            KODE_LANG,				
            KODERAYON,				
            NAMA_LANG,				
            NAMAAREA as ALAMAT1,				
            '' as ALAMAT2,				
            '' as TELP,				
            '' as KODEPOS,				
            '' as TGL,				
            '' as NPWP,				
            '0' as BTS_UTANG,				
            '0' as SALES01,				
            '0' as SALES02,				
            '0' as SALES03,				
            '0' as SALES04,				
            '0' as SALES05,				
            '0' as SALES06,				
            '0' as SALES07,				
            '0' as SALES08,				
            '0' as SALES09,				
            '0' as SALES10,				
            '0' as SALES11,				
            '0' as SALES12,				
            '0' as KET,				
            '0' as DEBIT,				
            '0' as KREDIT,				
            KODESALUR as KODESALUR,				
            '0' as TOP,				
            'Y' as AKTIF,				
            '' as TGL_AKTIF,				
            'T' as PPN,				
            '0' as KODE_LAMA,				
            '1' as JUM_DOK,				
            '0' as STATJUAL,				
            '0' as LIMIT1,				
            '' as TGLNAKTIF,				
            '' as ALAMAT_WP,				
            '' as NILAI_PPN,				
            '' as NAMA_WP,				
            '' as NEWFLD,				
            nocab as NOCAB,				
            '' as kodelang_copy,				
            '' as id_provinsi,				
            '' as nama_provinsi,				
            '' as id_kota,				
            '' as nama_kota,				
            '' as id_kecamatan,				
            '' as nama_kecamatan,				
            '' as id_kelurahan,				
            '' as nama_kelurahan,				
            '' as phone,				
            '' as tipe_bayar,				
            '' as credit_limit,				
            '' AS last_updated,				
            '' as status_blacklist,				
            '' as status_payment,				
            '' as CUSTID,				
            '' as COMPID,				
            '' as LATITUDE,				
            '' as LONGITUDE,				
            '' as FOTO_DISP,				
            '' as FOTO_TOKO				
                            
            FROM(				
                SELECT CONCAT(KODE_COMP,KODE_LANG,max(BULAN)) as mapp				
                FROM DATA$tahun.ri  				
                WHERE concat(kode_comp, nocab) = '$site_code'				
                GROUP BY kode_comp,KODE_LANG 				
            )A				
            LEFT JOIN 				
            (				
            SELECT * FROM(SELECT *,CONCAT(KODE_COMP,KODE_LANG,BULAN) as mapp
                FROM DATA$tahun.ri
                WHERE concat(kode_comp, nocab) = '$site_code' 				
                GROUP BY MAPP 				
                )A				
            )C USING(MAPP)				
            )a group by kode_comp,kode_lang	
        ";

        // echo "<pre>";
        // print_r($insert);
        // echo "</pre>";

        // die;

        return $this->db->query($insert);
    }

    public function insert_tabsales_kendari($site_code, $signature){
        $get_tahun = "
            select * 
            from management_raw.log_upload a 
            where a.site_code = '$site_code' and a.signature = '$signature'
        ";
        $tahun = $this->db->query($get_tahun)->row()->tahun;
        $bulan = $this->db->query($get_tahun)->row()->bulan;

        if ($site_code == 'CSSK3') {
            $params = "KENDARI"; 
        }elseif ($site_code == 'BLGW4') {
            $params = "PALU"; 
        }else if ($site_code == 'VBTV1') {
            $params = "MAKASAR"; 
        }elseif ($site_code == 'BKMV5') {
            $params = "BULUKUMBA"; 
        }elseif ($site_code == 'BONV4') {
            $params = "BONE"; 
        }elseif ($site_code == 'PALV2') {
            $params = "PALOPO"; 
        }elseif ($site_code == 'PARV3') {
            $params = "PARE-PARE"; 
        }elseif ($site_code == 'MWRW6') {
            $params = "MANOKWARI"; 
        }elseif ($site_code == 'DASD1') {
            $params = "BALIKPAPAN"; 
        }elseif ($site_code == 'GTO87') {
            $params = "GORONTALO"; 
        }elseif ($site_code == 'MANW5') {
            $params = "MANADO"; 
        }elseif ($site_code == 'PBNP9') {
            $params = "PANGKALANBUN"; 
        }elseif ($site_code == 'SPTU4') {
            $params = "SAMPIT"; 
        }elseif ($site_code == 'PKRP8') {
            $params = "PALANGKARAYA"; 
        }

        $insert = "
            insert into data$tahun.tabsales			
            SELECT 				
            a.KODESALES,				
            a.lampiran as NAMASALES,				
            ''AS KODERAYON,				
            'S'AS `STATUS`,				
            '$params' AS ALAMAT1,				
            '$params' AS ALAMAT2,				
            ''AS NO_TELP,				
            '' AS KODEPOS,				
            '' AS PROPINSI,				
            '' AS DATA1,				
            '' AS TAHAP,				
            '' AS FILEID,				
            '' AS NAMA_DEPO,				
            KODE_KOTA,				
            '' AS KODE_GDG,				
            '' AS NAMA_GDG,				
            'Y' AS AKTIF,				
            NOCAB 				
            FROM data$tahun.fi a				
            inner JOIN 				
            (				
            SELECT kodesales, MAX(concat(kodesales,bulan)) times 				
            FROM data$tahun.fi 				
            where concat(kode_comp, nocab) = '$site_code'				
            GROUP BY KODESALES				
            ) b				
                ON b.times=concat(a.KODESALES,a.BULAN)				
            where concat(kode_comp, nocab) = '$site_code'				
            GROUP BY kodesales
        ";

        // echo "<pre>";
        // print_r($insert);
        // echo "</pre>";

        // die;

        return $this->db->query($insert);
    }

    public function insert_tbkota_kendari($site_code, $signature){
        $get_tahun = "
            select * 
            from management_raw.log_upload a 
            where a.site_code = '$site_code' and a.signature = '$signature'
        ";
        $tahun = $this->db->query($get_tahun)->row()->tahun;
        $bulan = $this->db->query($get_tahun)->row()->bulan;

        $insert = "
            insert into data$tahun.tbkota		
            select 				
            KODE_COMP AS KODE_COMP, 				
            KODE_KOTA AS KODE_KOTA,				
            CASE 				
            WHEN KODE_KOTA=101	THEN 'DALAM KOTA'			
            WHEN KODE_KOTA=201	THEN 'UNAAHA'			
            WHEN KODE_KOTA=202	THEN 'KOLAKA'			
            WHEN KODE_KOTA=203	THEN 'ASERA'			
            WHEN KODE_KOTA=204	THEN 'BAU-BAU'			
            WHEN KODE_KOTA=205	THEN 'RAHA'			
            WHEN KODE_KOTA=206	THEN 'BOMBANA'			
            WHEN KODE_KOTA=208	THEN 'KONAWE SELATAN'			
                            
            ELSE 'KENDARI'				
            END AS NAMA_KOTA,				
            nocab AS NOCAB				
            from data$tahun.fi				
            where concat(kode_comp, nocab) = '$site_code'
            group by KODE_COMP,KODE_KOTA
        ";

        // echo "<pre>";
        // print_r($insert);
        // echo "</pre>";

        // die;

        return $this->db->query($insert);
    }

    public function insert_mpm_upload_kendari($site_code, $signature, $omzet = '', $status_closing = 0){

        if ($site_code == 'SSMS9') {
            $params_site_code = "inner_raw_banjarmasin"; 
        }elseif ($site_code == 'SSJD2') {
            $params_site_code = "inner_raw_batulicin"; 
        }elseif ($site_code == 'BRBS0') {
            $params_site_code = "inner_raw_barabai"; 
        }elseif ($site_code == 'SMRB7') {
            $params_site_code = "inner_raw_samarinda"; 
        }elseif ($site_code == 'CSSK3') {
            $params_site_code = "inner_raw_kendari"; 
        }

        $query_tanggal = "
            select  a.tgldokjdi, if(length(max(day(a.tgldokjdi))) = 1, concat('0',max(day(a.tgldokjdi))), max(day(a.tgldokjdi))) as hari, if(length(max(month(a.tgldokjdi))) = 1, concat('0',max(month(a.tgldokjdi))), max(month(a.tgldokjdi))) as bulan, max(year(a.tgldokjdi)) as tahun  
            from management_raw.$params_site_code a
            where a.signature = '$signature'
        ";

        // die;

        $get_hari = $this->db->query($query_tanggal)->row()->hari;
        $get_bulan = $this->db->query($query_tanggal)->row()->bulan;
        $get_tahun = $this->db->query($query_tanggal)->row()->tahun;

        // die;
        $created_at = $this->model_outlet_transaksi->timezone();

        // die;

        $cek_userid  = "
            select id
            from mpm.user a
            where a.username = left('$site_code',3)
        ";

        // die;

        $userid = $this->db->query($cek_userid)->row()->id;

        $insert = [
            'userid'            => $userid,
            'lastupload'        => $created_at,
            'filename'          => 'NON SDS',
            'tanggal'           => $get_hari,
            'bulan'             => $get_bulan,
            'tahun'             => $get_tahun,
            'status'            => 1,
            'status_closing'    => $status_closing,
            'omzet'             => $omzet,
            'flag_check'        => 0
        ];

        return $this->db->insert('mpm.upload', $insert);
    }

    public function update_kodeproduk_balikpapan($site_code, $signature){

        if ($site_code == 'DASD1') {
            $params_site_code = "raw_balikpapan"; 
        }

        $nocab = substr($site_code,3,2);

        // $query = "
        //     update management_raw.$params_site_code a 
        //     set a.kd_produk = if(a.kd_produk = (select b.code from pmu.mapping b where b.nocab = '$nocab' and b.code = a.kd_produk),
        //     (select b.kodeprod from pmu.mapping b where b.nocab = '$nocab' and b.code = a.kd_produk), concat(a.kd_produk, '(blank)'))
        //     where a.signature = '$signature'
        // ";

        $query = "
            update management_raw.$params_site_code a 
            set a.kd_produk = (select b.kodeprod from pmu.mapping b where b.nocab = '$nocab' and b.code = a.kd_produk)
            where a.signature = '$signature'
        ";
        $update = $this->db->query($query);

        if ($update) {
            return $update;
        }else{
            return array();
        }
    }

    public function inner_kodeproduk_balikpapan($site_code, $signature){

        if ($site_code == 'SSMS9') {
            $params_site_code = "inner_raw_banjarmasin"; 
            $params_site_code2 = "raw_banjarmasin"; 
        }elseif ($site_code == 'SSJD2') {
            $params_site_code = "inner_raw_batulicin"; 
            $params_site_code2 = "raw_batulicin"; 
        }elseif ($site_code == 'BRBS0') {
            $params_site_code = "inner_raw_barabai"; 
            $params_site_code2 = "raw_barabai"; 
        }elseif ($site_code == 'SMRB7') {
            $params_site_code = "inner_raw_samarinda"; 
            $params_site_code2 = "raw_samarinda"; 
        }elseif ($site_code == 'CSSK3') {
            $params_site_code = "inner_raw_kendari"; 
            $params_site_code2 = "raw_kendari"; 
        }elseif ($site_code == 'DASD1') {
            $params_site_code = "inner_raw_balikpapan"; 
            $params_site_code2 = "raw_balikpapan"; 
        }

        $query = "
            insert into management_raw.$params_site_code
            select '', '$site_code', '', '', a.*, b.supp, b.grupprod, '', '', ''
            from management_raw.$params_site_code2 a INNER JOIN (
                select a.kodeprod, a.supp, a.grupprod
                from mpm.tabprod a 
            )b on a.kd_produk = b.kodeprod
            where a.signature = '$signature'
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        // die;
        $update = $this->db->query($query);
        if ($update) {
            return $update;
        }else{
            return array();
        }
    }

    public function update_namaprod_balikpapan($site_code, $signature){

        if ($site_code == 'SSMS9') {
            $params_site_code = "inner_raw_banjarmasin"; 
        }elseif ($site_code == 'SSJD2') {
            $params_site_code = "inner_raw_batulicin"; 
        }elseif ($site_code == 'BRBS0') {
            $params_site_code = "inner_raw_barabai"; 
        }elseif ($site_code == 'SMRB7') {
            $params_site_code = "inner_raw_samarinda"; 
        }elseif ($site_code == 'CSSK3') {
            $params_site_code = "inner_raw_kendari"; 
        }elseif ($site_code == 'DASD1') {
            $params_site_code = "inner_raw_balikpapan"; 
        }

        $query = "
            update management_raw.$params_site_code a 
            set a.nm_produk = (
                select b.namaprod
                from mpm.tabprod b 
                where a.kd_produk = b.kodeprod
            ), a.map_divisi = (
                select b.new_divisi
                from mpm.tabprod b 
                where a.kd_produk = b.kodeprod
            ), a.map_group = (
                select b.new_group
                from mpm.tabprod b 
                where a.kd_produk = b.kodeprod
            ), a.map_subgroup = (
                select b.new_subgroup
                from mpm.tabprod b 
                where a.kd_produk = b.kodeprod
            )
            where a.signature = '$signature'
        ";
        $update = $this->db->query($query);
        if ($update) {
            return $update;
        }else{
            return array();
        }
    }

    public function update_tanggal_balikpapan($site_code, $signature){
        
        if ($site_code == 'SSMS9') {
            $params_site_code = "inner_raw_banjarmasin"; 
        }elseif ($site_code == 'SSJD2') {
            $params_site_code = "inner_raw_batulicin"; 
        }elseif ($site_code == 'BRBS0') {
            $params_site_code = "inner_raw_barabai"; 
        }elseif ($site_code == 'SMRB7') {
            $params_site_code = "inner_raw_samarinda"; 
        }elseif ($site_code == 'BTGB8') {
            $params_site_code = "inner_raw_bontang"; 
        }elseif ($site_code == 'PTK82') {
            $params_site_code = "inner_raw_pontianak"; 
        }elseif ($site_code == 'DASD1') {
            $params_site_code = "inner_raw_balikpapan"; 
        }elseif ($site_code == 'GTO87') {
            $params_site_code = "inner_raw_gorontalo"; 
        }

        $query = "
            update management_raw.$params_site_code a 
            set a.tanggal = str_to_date(a.tanggal,'%d/%m/%Y')
            where a.signature = '$signature'
        ";
        $update = $this->db->query($query);
        if ($update) {
            return $update;
        }else{
            return array();
        }
    }

    public function update_inner_customer_id_balikpapan($signature){

        $query = "
        update management_raw.inner_raw_balikpapan a 
        set a.kd_cust = (
            select b.kd_cust_mpm
            from management_raw.raw_customer_balikpapan b
            where a.kd_cust = b.kd_cust
            group by b.kd_cust
        )
        where a.signature = '$signature'
        
        ";
        $update = $this->db->query($query);

        if ($update) {
            return $update;
        }else{
            return array();
        }
    }

    public function insert_fi_balikpapan($site_code, $signature){
        $get_tahun = "
            select * 
            from management_raw.log_upload a 
            where a.site_code = '$site_code' and a.signature = '$signature'
        ";
        $tahun = $this->db->query($get_tahun)->row()->tahun;
        $bulan = $this->db->query($get_tahun)->row()->bulan;


        $insert = "
        insert data$tahun.fi
        select 		
        '07' as KDOKJDI,		
        NO_NOTA as NODOKJDI,		
        NO_NOTA as NODOKACU,		
        tanggal as TGLDOKJDI,		
        KODESALESMAN as KODESALES,		
        'DAS' as KODE_COMP,		
        'BLP' as KODE_KOTA,		
        CASE 		
        WHEN TYPE LIKE'%APOTIK%' THEN 'APT'		
        WHEN TYPE LIKE'%MINI MARKET%' THEN 'MML'		
        WHEN TYPE LIKE'%COSMETIK%' THEN 'TCO'		
        WHEN TYPE LIKE'%OTHER%' THEN 'OTH'		
        WHEN TYPE LIKE'%INSTITUSI%' THEN 'PBF'		
        WHEN TYPE LIKE'%OBAT%' THEN 'TOB'		
        ELSE 'TKL'		
        END AS KODE_TYPE,		
        KD_CUST as KODE_LANG,		
        '' as KODERAYON,		
        IF(a.KD_PRODUK = c.KODEPROD, c.KODEPROD, 'blank') as KODEPROD,		
        if(left(c.kodeprod,2) in(01,60,70,50),'001',IF(left(c.kodeprod,2)='06','005','002')) as SUPP,		
        right(TANGGAL,2) as HRDOK,		
        '$bulan' as BLNDOK,		
        '$tahun' as THNDOK,		
        IF(a.KD_PRODUK = c.KODEPROD, c.NAMAPROD, a.KD_PRODUK) as NAMAPROD,		
        c.GRUPPROD as GROUPPROD,		
        IF(NILAI_BRUTTO_DPP='0','0',QTYSOLD_PCS) as BANYAK,		
        if(NILAI_BRUTTO_DPP/QTYSOLD_PCS is null,0,ROUND(NILAI_BRUTTO_DPP*1.11/QTYSOLD_PCS,2)) as HARGA,		
        ROUND(DISCITEM*1.11,2) as POTONGAN,		
        ROUND(NILAI_BRUTTO_DPP*1.11,2)as TOT1,		
        '' as JUM_PROMO, 		
        if(NILAI_BRUTTO_DPP='0',concat(c.KODEPROD,' ',QTY),'') as KETERANGAN,  		
        '' as USER_ISI,		
        '' as JAM_ISI,		
        '' as TGL_ISI, 		
        '' as USER_EDIT, 		
        '' as JAM_EDIT, 		
        '' as TGL_EDIT,		
        '' as USER_DEL, 		
        '' as JAM_DEL, 		
        '' as TGL_DEL, 		
        '' as NO, 		
        '' as BACKUP, 		
        '' as NO_URUT, 		
        'PST' as KODE_GDG, 		
        '' as NAMA_GDG, 		
        IF(TyPE='INSTITUSI','SO','RT') AS KODESALUR,		
        '' as KODEBONUS,		
        '' as NAMABONUS, 		
        '' as GRUPBONUS, 		
        '' as UNITBONUS, 		
        salesman as LAMPIRAN, 		
        '' as H_BELI, 		
        '' as KODEAREA, 		
        AREA as NAMAAREA, 		
        '' as PINJAM, 		
        '' as JUALBANYAK, 		
        '' as JUALPINJAM, 		
        '' as HARGA_EXCL, 		
        '' as TOT1_EXCL, 		
        NM_CUST as NAMA_LANG,		
        'D1' as NOCAB,		
        '$bulan' as BULAN,		
        ' ' as siteid,		
        ' ' as qty1,		
        ' ' as qty2,		
        ' ' as qty3,		
        ' ' as qty_bonus,		
        ' ' as flag_bonus,		
        ' ' as disc_persen,		
        ' ' as disc_rp,		
        ' ' as disc_value,		
        a.DISCSPECIAL as disc_cabang,		
        ' 'as disc_prinsipal,		
        ' ' as disc_xtra,		
        a.DISCSPECIALRP as rp_cabang,		
        a.DISCPROMOSITOTALRP as rp_prinsipal,		
        ' ' as rp_xtra,		
        ' ' as bonus,		
        ' ' as prinsipalid,		
        ' ' as ex_no_sales,		
        ' ' as status_retur,		
        ' ' as ref,		
        ' ' as term_payment,		
        ' ' as tipe_kl		
        from management_raw.inner_raw_balikpapan a		
        LEFT JOIN mpm.tabprod c ON a.kd_produk=c.kodeprod		
        where NILAI_BRUTTO_DPP not like'-%' and a.signature = '$signature' and year(a.tanggal) = $tahun and month(a.tanggal) = $bulan	
        order by harga
        ";

        // echo "<pre>";
        // print_r($insert);
        // echo "</pre>";

        return $this->db->query($insert);
    }

    public function insert_ri_balikpapan($site_code, $signature){
        $get_tahun = "
            select * 
            from management_raw.log_upload a 
            where a.site_code = '$site_code' and a.signature = '$signature'
        ";
        $tahun = $this->db->query($get_tahun)->row()->tahun;
        $bulan = $this->db->query($get_tahun)->row()->bulan;

        $insert = "
        insert data$tahun.ri
        select 		
        '07' as KDOKJDI,		
        NO_NOTA as NODOKJDI,		
        NO_NOTA as NODOKACU,		
        TANGGAL as TGLDOKJDI,		
        KODESALESMAN as KODESALES,		
        'DAS' as KODE_COMP,		
        'BLP' as KODE_KOTA,		
        CASE 		
        WHEN TYPE LIKE'%APOTIK%' THEN 'APT'		
        WHEN TYPE LIKE'%MINI MARKET%' THEN 'MML'		
        WHEN TYPE LIKE'%COSMETIK%' THEN 'TCO'		
        WHEN TYPE LIKE'%OTHER%' THEN 'OTH'		
        WHEN TYPE LIKE'%INSTITUSI%' THEN 'PBF'		
        WHEN TYPE LIKE'%OBAT%' THEN 'TOB'		
        ELSE 'TKL'		
        END AS KODE_TYPE,		
        KD_CUST as KODE_LANG,		
        '' as KODERAYON,		
        IF(a.KD_PRODUK = c.KODEPROD, c.KODEPROD, 'blank') as KODEPROD,		
        if(left(c.kodeprod,2) in(01,60,70,50),'001',IF(left(c.kodeprod,2)='06','005','002')) as SUPP,		
        right(TANGGAL,2) as HRDOK,		
        '$bulan' as BLNDOK,		
        '$tahun' as THNDOK,		
        IF(a.KD_PRODUK = c.KODEPROD, c.NAMAPROD, a.KD_PRODUK) as NAMAPROD,		
        c.GRUPPROD as GROUPPROD,		
        IF(NILAI_BRUTTO_DPP='0','0',QTYSOLD_PCS) as BANYAK,		
        if(NILAI_BRUTTO_DPP/QTYSOLD_PCS is null,0,ROUND(NILAI_BRUTTO_DPP*1.11/QTYSOLD_PCS,2)) as HARGA,		
        ROUND(DISCITEM*1.11,2) as POTONGAN,		
        ROUND(NILAI_BRUTTO_DPP*1.11,2)as TOT1,		
        '' as JUM_PROMO, 		
        if(NILAI_BRUTTO_DPP='0',concat(c.KODEPROD,' ',QTY),'') as KETERANGAN,  		
        '' as USER_ISI,		
        '' as JAM_ISI,		
        '' as TGL_ISI, 		
        '' as USER_EDIT, 		
        '' as JAM_EDIT, 		
        '' as TGL_EDIT,		
        '' as USER_DEL, 		
        '' as JAM_DEL, 		
        '' as TGL_DEL, 		
        '' as NO, 		
        '' as BACKUP, 		
        '' as NO_URUT, 		
        'PST' as KODE_GDG, 		
        '' as NAMA_GDG,		
        IF(TyPE='INSTITUSI','SO','RT') AS KODESALUR,		
        '' as KODEBONUS,		
        '' as NAMABONUS, 		
        '' as GRUPBONUS, 		
        '' as UNITBONUS, 		
        salesman as LAMPIRAN, 		
        '' as H_BELI, 		
        '' as KODEAREA, 		
        AREA as NAMAAREA, 		
        '' as PINJAM, 		
        '' as JUALBANYAK, 		
        '' as JUALPINJAM, 		
        '' as HARGA_EXCL, 		
        NM_CUST as NAMA_LANG,		
        'D1' as NOCAB,		
        '$bulan' as BULAN,		
        ' ' as siteid,		
        ' ' as qty1,		
        ' ' as qty2,		
        ' ' as qty3,		
        ' ' as qty_bonus,		
        ' ' as flag_bonus,		
        ' ' as disc_persen,		
        ' ' as disc_rp,		
        ' ' as disc_value,		
        a.DISCSPECIAL as disc_cabang,		
        ' ' as disc_prinsipal,		
        ' ' as disc_xtra,		
        a.DISCSPECIALRP as rp_cabang,		
        a.DISCPROMOSITOTALRP as rp_prinsipal,		
        ' ' as rp_xtra,		
        ' ' as bonus,		
        ' ' as prinsipalid,		
        ' ' as ex_no_sales,		
        ' ' as status_retur,		
        ' ' as ref,		
        ' ' as term_payment,		
        ' ' as tipe_kl		
        from management_raw.inner_raw_balikpapan a		
        LEFT JOIN mpm.tabprod c ON a.kd_produk=c.kodeprod		
        where NILAI_BRUTTO_DPP like'-%' and a.signature = '$signature' and year(a.tanggal) = $tahun and month(a.tanggal) = $bulan	
        order by harga
        ";

        // echo "<pre>";
        // print_r($insert);
        // echo "</pre>";

        // die;

        return $this->db->query($insert);
    }

    public function insert_tbkota_balikpapan($site_code, $signature){
        $get_tahun = "
            select * 
            from management_raw.log_upload a 
            where a.site_code = '$site_code' and a.signature = '$signature'
        ";
        $tahun = $this->db->query($get_tahun)->row()->tahun;
        $bulan = $this->db->query($get_tahun)->row()->bulan;

        $insert = "
            insert into data$tahun.tbkota		
            select 		
            KODE_COMP AS KODE_COMP, 		
            'BLP' AS KODE_KOTA,		
            'BALIKPAPAN' AS NAMA_KOTA,		
            NOCAB AS NOCAB		
            from data$tahun.fi		
            where concat(kode_comp, nocab) = '$site_code'	
            group by KODE_COMP,KODE_KOTA	
        ";

        // echo "<pre>";
        // print_r($insert);
        // echo "</pre>";

        // die;

        return $this->db->query($insert);
    }

    public function get_raw_draft_manokwari($site_code, $signature = ''){

        if ($site_code == 'SSMS9') {
            $params_site_code = "raw_banjarmasin"; 
        }elseif ($site_code == 'SSJD2') {
            $params_site_code = "raw_batulicin"; 
        }elseif ($site_code == 'BRBS0') {
            $params_site_code = "raw_barabai"; 
        }elseif ($site_code == 'SMRB7') {
            $params_site_code = "raw_samarinda"; 
        }elseif ($site_code == 'BTGB8') {
            $params_site_code = "raw_bontang"; 
        }elseif ($site_code == 'PTK82') {
            $params_site_code = "raw_pontianak"; 
        }elseif ($site_code == 'CSSK3') {
            $params_site_code = "raw_kendari"; 
        }elseif ($site_code == 'DASD1') {
            $params_site_code = "raw_balikpapan"; 
        }elseif ($site_code == 'MWRW6') {
            $params_site_code = "raw_manokwari"; 
        }

        if ($signature) {
            $params = "where a.signature = '$signature'";
        }else{
            $params = "";
        }

        $query = "
            select a.*, c.kode_customer_mpm
            from
            (
                select a.* from management_raw.$params_site_code a
                $params
            )a
            left join 
            (
                select a.kode_customer, a.kode_customer_mpm from management_raw.raw_customer_manokwari a
            )c on a.kode_customer = c.kode_customer
        ";
        return $this->db->query($query);
    }

    public function update_kodeproduk_manokwari($site_code, $signature){

        if ($site_code == 'MWRW6') {
            $params_site_code = "raw_manokwari"; 
        }

        $query = "
            update management_raw.$params_site_code a 
            set a.kode_barang = concat(0,a.kode_barang)
            where a.signature = '$signature' and length(a.kode_barang) = 5 
        ";
        $update = $this->db->query($query);

        if ($update) {
            return $update;
        }else{
            return array();
        }
    }

    public function inner_kodeproduk_manokwari($site_code, $signature){

        if ($site_code == 'SSMS9') {
            $params_site_code = "inner_raw_banjarmasin"; 
            $params_site_code2 = "raw_banjarmasin"; 
        }elseif ($site_code == 'SSJD2') {
            $params_site_code = "inner_raw_batulicin"; 
            $params_site_code2 = "raw_batulicin"; 
        }elseif ($site_code == 'BRBS0') {
            $params_site_code = "inner_raw_barabai"; 
            $params_site_code2 = "raw_barabai"; 
        }elseif ($site_code == 'SMRB7') {
            $params_site_code = "inner_raw_samarinda"; 
            $params_site_code2 = "raw_samarinda"; 
        }elseif ($site_code == 'CSSK3') {
            $params_site_code = "inner_raw_kendari"; 
            $params_site_code2 = "raw_kendari"; 
        }elseif ($site_code == 'DASD1') {
            $params_site_code = "inner_raw_balikpapan"; 
            $params_site_code2 = "raw_balikpapan"; 
        }elseif ($site_code == 'MWRW6') {
            $params_site_code = "inner_raw_manokwari"; 
            $params_site_code2 = "raw_manokwari"; 
        }

        $query = "
            insert into management_raw.$params_site_code
            select '', '$site_code', '', '', a.*, b.supp, b.grupprod, '', '', ''
            from management_raw.$params_site_code2 a INNER JOIN (
                select a.kodeprod, a.supp, a.grupprod
                from mpm.tabprod a 
            )b on a.kode_barang = b.kodeprod
            where a.signature = '$signature'
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        // die;
        $update = $this->db->query($query);
        if ($update) {
            return $update;
        }else{
            return array();
        }
    }

    public function update_namaprod_manokwari($site_code, $signature){

        if ($site_code == 'SSMS9') {
            $params_site_code = "inner_raw_banjarmasin"; 
        }elseif ($site_code == 'SSJD2') {
            $params_site_code = "inner_raw_batulicin"; 
        }elseif ($site_code == 'BRBS0') {
            $params_site_code = "inner_raw_barabai"; 
        }elseif ($site_code == 'SMRB7') {
            $params_site_code = "inner_raw_samarinda"; 
        }elseif ($site_code == 'CSSK3') {
            $params_site_code = "inner_raw_kendari"; 
        }elseif ($site_code == 'DASD1') {
            $params_site_code = "inner_raw_balikpapan"; 
        }elseif ($site_code == 'MWRW6') {
            $params_site_code = "inner_raw_manokwari"; 
        }

        $query = "
            update management_raw.$params_site_code a 
            set a.nama_barang = (
                select b.namaprod
                from mpm.tabprod b 
                where a.kode_barang = b.kodeprod
            ), a.map_divisi = (
                select b.new_divisi
                from mpm.tabprod b 
                where a.kode_barang = b.kodeprod
            ), a.map_group = (
                select b.new_group
                from mpm.tabprod b 
                where a.kode_barang = b.kodeprod
            ), a.map_subgroup = (
                select b.new_subgroup
                from mpm.tabprod b 
                where a.kode_barang = b.kodeprod
            )
            where a.signature = '$signature'
        ";
        $update = $this->db->query($query);
        if ($update) {
            return $update;
        }else{
            return array();
        }
    }

    public function update_tanggal_manokwari($site_code, $signature){
        
        if ($site_code == 'SSMS9') {
            $params_site_code = "inner_raw_banjarmasin"; 
        }elseif ($site_code == 'SSJD2') {
            $params_site_code = "inner_raw_batulicin"; 
        }elseif ($site_code == 'BRBS0') {
            $params_site_code = "inner_raw_barabai"; 
        }elseif ($site_code == 'SMRB7') {
            $params_site_code = "inner_raw_samarinda"; 
        }elseif ($site_code == 'BTGB8') {
            $params_site_code = "inner_raw_bontang"; 
        }elseif ($site_code == 'PTK82') {
            $params_site_code = "inner_raw_pontianak"; 
        }elseif ($site_code == 'MWRW6') {
            $params_site_code = "inner_raw_manokwari"; 
        }

        $query = "
            update management_raw.$params_site_code a 
            set a.tgl_faktur = str_to_date(a.tgl_faktur,'%m/%d/%Y')
            where a.signature = '$signature'
        ";
        $update = $this->db->query($query);
        if ($update) {
            return $update;
        }else{
            return array();
        }
    }

    public function update_inner_customer_id_manokwari($signature){

        $query = "
        update management_raw.inner_raw_manokwari a 
        set a.kode_customer = (
            select b.kode_customer_mpm
            from management_raw.raw_customer_manokwari b
            where a.kode_customer = b.kode_customer
            group by b.kode_customer
        )
        where a.signature = '$signature'
        
        ";
        $update = $this->db->query($query);

        if ($update) {
            return $update;
        }else{
            return array();
        }
    }

    public function insert_fi_manokwari($site_code, $signature){
        $get_tahun = "
            select * 
            from management_raw.log_upload a 
            where a.site_code = '$site_code' and a.signature = '$signature'
        ";
        $tahun = $this->db->query($get_tahun)->row()->tahun;
        $bulan = $this->db->query($get_tahun)->row()->bulan;


        $insert = "
        insert data$tahun.fi
        select 		
        '07' as KDOKJDI,		
        No_Faktur as NODOKJDI,		
        No_Faktur as NODOKACU,		
        tgl_faktur as TGLDOKJDI,		
        left(Kode_Salesman,6) as KODESALES,
        'MWR' as KODE_COMP,		
        'MWR' as KODE_KOTA,		
        case		
        when Tipe_Customer LIKE'%MEDICAL%' THEN 'PBF'				
        when Tipe_Customer like'%modern trade%' THEN 'MML'	

        ELSE 'TKL'		
        END 		
        as KODE_TYPE,		
        Kode_Customer as KODE_LANG,		
        '' as KODERAYON,		
        KODE_BARANG as KODEPROD,		
        if(left(c.kodeprod,2) in(01,60,70,50),'001',IF(left(c.kodeprod,2)='06','005','002')) as SUPP,		
        RIGHT(Tgl_Faktur,2) as HRDOK,
        '$bulan' as BLNDOK,		
        LEFT(Tgl_Faktur,4) as THNDOK,	
        c.NAMAPROD as NAMAPROD,		
        c.GRUPPROD as GROUPPROD,		
        QTY_Terkecil as BANYAK,		
        ROUND(Nilai_Faktur/QTY_Terkecil,2)as HARGA,		
        Disc_Value as POTONGAN,		
        Nilai_Faktur as TOT1,		
        '' as JUM_PROMO, 		
        BONUS as KETERANGAN, 		
        '' as USER_ISI,		
        '' as JAM_ISI,		
        '' as TGL_ISI, 		
        '' as USER_EDIT, 		
        '' as JAM_EDIT, 		
        '' as TGL_EDIT,		
        '' as USER_DEL, 		
        '' as JAM_DEL, 		
        '' as TGL_DEL, 		
        '' as NO, 		
        '' as BACKUP, 		
        '' as NO_URUT, 		
        'PST' as KODE_GDG, 		
        '' as NAMA_GDG, 		
        case		
        when Tipe_Customer LIKE'%MEDICAL%' THEN 'WS'				
        when Tipe_Customer like'%modern trade%' THEN 'WS'	
        when Tipe_Customer like'%WHOLESALE%' THEN 'SWS'	
        ELSE 'RT'		 		
        END AS KODESALUR,		
        '' as KODEBONUS,		
        '' as NAMABONUS, 		
        '' as GRUPBONUS, 		
        '' as UNITBONUS, 		
        KODE_SALESMAN as LAMPIRAN, 		
        '' as H_BELI, 		
        '' as KODEAREA, 		
        ALAMAT_CUSTOMER as NAMAAREA, 		
        '' as PINJAM, 		
        '' as JUALBANYAK, 		
        '' as JUALPINJAM, 		
        '' as HARGA_EXCL, 		
        '' as TOT1_EXCL, 		
        NAMA_CUSTOMER as NAMA_LANG,		
        'W6' as NOCAB,		
        '$bulan' as BULAN,		
        ' ' as siteid,		
        ' ' as qty1,		
        ' ' as qty2,		
        ' ' as qty3,		
        ' ' as qty_bonus,		
        ' ' as flag_bonus,		
        ' ' as disc_persen,		
        ' ' as disc_rp,		
        ' ' as disc_value,		
        ' ' as disc_cabang,		
        ' ' as disc_prinsipal,		
        ' ' as disc_xtra,		
        ' ' as rp_cabang,		
        ' ' as rp_prinsipal,		
        ' ' as rp_xtra,		
        ' ' as bonus,		
        ' ' as prinsipalid,		
        ' ' as ex_no_sales,		
        ' ' as status_retur,		
        ' ' as ref,		
        ' ' as term_payment,		
        ' ' as tipe_kl		
                
        from management_raw.inner_raw_manokwari a		
        LEFT JOIN mpm.tabprod c ON a.KODE_BARANG = c.kodeprod		
        where Nilai_Faktur not like'-%' and a.signature = '$signature' and year(a.Tgl_Faktur) = $tahun and month(a.Tgl_Faktur) = $bulan	
        order by harga
        ";

        // echo "<pre>";
        // print_r($insert);
        // echo "</pre>";

        return $this->db->query($insert);
    }

    public function insert_ri_manokwari($site_code, $signature){
        $get_tahun = "
            select * 
            from management_raw.log_upload a 
            where a.site_code = '$site_code' and a.signature = '$signature'
        ";
        $tahun = $this->db->query($get_tahun)->row()->tahun;
        $bulan = $this->db->query($get_tahun)->row()->bulan;

        $insert = "
        insert data$tahun.ri
        select 		
        '07' as KDOKJDI,		
        No_Faktur as NODOKJDI,		
        No_Faktur as NODOKACU,		
        tgl_faktur as TGLDOKJDI,		
        left(Kode_Salesman,6) as KODESALES,
        'MWR' as KODE_COMP,		
        'MWR' as KODE_KOTA,		
        case		
        when Tipe_Customer LIKE'%MEDICAL%' THEN 'PBF'				
        when Tipe_Customer like'%modern trade%' THEN 'MML'	

        ELSE 'TKL'		
        END 		
        as KODE_TYPE,		
        Kode_Customer as KODE_LANG,		
        '' as KODERAYON,		
        KODE_BARANG as KODEPROD,		
        if(left(c.kodeprod,2) in(01,60,70,50),'001',IF(left(c.kodeprod,2)='06','005','002')) as SUPP,		
        RIGHT(Tgl_Faktur,2) as HRDOK,
        '$bulan' as BLNDOK,		
        LEFT(Tgl_Faktur,4) as THNDOK,
        c.NAMAPROD as NAMAPROD,		
        c.GRUPPROD as GROUPPROD,		
        QTY_Terkecil as BANYAK,		
        ROUND(Nilai_Faktur/QTY_Terkecil,2)as HARGA,		
        Disc_Value as POTONGAN,		
        Nilai_Faktur as TOT1,		
        '' as JUM_PROMO, 		
        BONUS as KETERANGAN, 		
        '' as USER_ISI,		
        '' as JAM_ISI,		
        '' as TGL_ISI, 		
        '' as USER_EDIT, 		
        '' as JAM_EDIT, 		
        '' as TGL_EDIT,		
        '' as USER_DEL, 		
        '' as JAM_DEL, 		
        '' as TGL_DEL, 		
        '' as NO, 		
        '' as BACKUP, 		
        '' as NO_URUT, 		
        'PST' as KODE_GDG, 		
        '' as NAMA_GDG, 		
        case		
        when Tipe_Customer LIKE'%MEDICAL%' THEN 'WS'				
        when Tipe_Customer like'%modern trade%' THEN 'WS'	
        when Tipe_Customer like'%WHOLESALE%' THEN 'SWS'	
        ELSE 'RT'		 		
        END AS KODESALUR,		
        '' as KODEBONUS,		
        '' as NAMABONUS, 		
        '' as GRUPBONUS, 		
        '' as UNITBONUS, 		
        KODE_SALESMAN as LAMPIRAN, 		
        '' as H_BELI, 		
        '' as KODEAREA, 		
        ALAMAT_CUSTOMER as NAMAAREA, 		
        '' as PINJAM, 		
        '' as JUALBANYAK, 		
        '' as JUALPINJAM, 		
        '' as HARGA_EXCL, 				
        NAMA_CUSTOMER as NAMA_LANG,		
        'W6' as NOCAB,		
        '$bulan' as BULAN,		
        ' ' as siteid,		
        ' ' as qty1,		
        ' ' as qty2,		
        ' ' as qty3,		
        ' ' as qty_bonus,		
        ' ' as flag_bonus,		
        ' ' as disc_persen,		
        ' ' as disc_rp,		
        ' ' as disc_value,		
        ' ' as disc_cabang,		
        ' ' as disc_prinsipal,		
        ' ' as disc_xtra,		
        ' ' as rp_cabang,		
        ' ' as rp_prinsipal,		
        ' ' as rp_xtra,		
        ' ' as bonus,		
        ' ' as prinsipalid,		
        ' ' as ex_no_sales,		
        ' ' as status_retur,		
        ' ' as ref,		
        ' ' as term_payment,		
        ' ' as tipe_kl				
        from management_raw.inner_raw_manokwari a		
        LEFT JOIN mpm.tabprod c ON a.KODE_BARANG = c.kodeprod		
        where Nilai_Faktur like'-%' and a.signature = '$signature' and year(a.Tgl_Faktur) = $tahun and month(a.Tgl_Faktur) = $bulan	
        order by harga
        ";

        // echo "<pre>";
        // print_r($insert);
        // echo "</pre>";

        // die;

        return $this->db->query($insert);
    }

    public function insert_tbkota_manokwari($site_code, $signature){
        $get_tahun = "
            select * 
            from management_raw.log_upload a 
            where a.site_code = '$site_code' and a.signature = '$signature'
        ";
        $tahun = $this->db->query($get_tahun)->row()->tahun;
        $bulan = $this->db->query($get_tahun)->row()->bulan;

        $insert = "
            insert into data$tahun.tbkota 
            select 
            KODE_COMP AS KODE_COMP, 
            'MWR' AS KODE_KOTA, 
            'MANOKWARI' AS NAMA_KOTA, 
            nocab AS NOCAB 
            from data$tahun.fi 
            where concat(kode_comp, nocab) = '$site_code' 
            group by KODE_COMP,KODE_KOTA
        ";

        // echo "<pre>";
        // print_r($insert);
        // echo "</pre>";

        // die;

        return $this->db->query($insert);
    }

    public function insert_mpm_upload_manokwari($site_code, $signature, $omzet = '', $status_closing = 0){

        if ($site_code == 'SSMS9') {
            $params_site_code = "inner_raw_banjarmasin"; 
        }elseif ($site_code == 'SSJD2') {
            $params_site_code = "inner_raw_batulicin"; 
        }elseif ($site_code == 'BRBS0') {
            $params_site_code = "inner_raw_barabai"; 
        }elseif ($site_code == 'SMRB7') {
            $params_site_code = "inner_raw_samarinda"; 
        }elseif ($site_code == 'CSSK3') {
            $params_site_code = "inner_raw_kendari"; 
        }elseif ($site_code == 'MWRW6') {
            $params_site_code = "inner_raw_manokwari"; 
        }

        $query_tanggal = "
            select  a.tgl_faktur, if(length(max(day(a.tgl_faktur))) = 1, concat('0',max(day(a.tgl_faktur))), max(day(a.tgl_faktur))) as hari,
            if(length(max(month(a.tgl_faktur))) = 1, concat('0',max(month(a.tgl_faktur))), max(month(a.tgl_faktur))) as bulan,
            max(year(a.tgl_faktur)) as tahun  
            from management_raw.$params_site_code a
            where a.signature = '$signature'
        ";

        // die;

        $get_hari = $this->db->query($query_tanggal)->row()->hari;
        $get_bulan = $this->db->query($query_tanggal)->row()->bulan;
        $get_tahun = $this->db->query($query_tanggal)->row()->tahun;

        // die;
        $created_at = $this->model_outlet_transaksi->timezone();

        // die;

        $cek_userid  = "
            select id
            from mpm.user a
            where a.username = left('$site_code',3)
        ";

        // die;

        $userid = $this->db->query($cek_userid)->row()->id;

        $insert = [
            'userid'            => $userid,
            'lastupload'        => $created_at,
            'filename'          => 'NON SDS',
            'tanggal'           => $get_hari,
            'bulan'             => $get_bulan,
            'tahun'             => $get_tahun,
            'status'            => 1,
            'status_closing'    => $status_closing,
            'omzet'             => $omzet,
            'flag_check'        => 0
        ];

        return $this->db->insert('mpm.upload', $insert);
    }

    public function get_raw_draft_palu($site_code, $signature = ''){

        if ($site_code == 'SSMS9') {
            $params_site_code = "raw_banjarmasin"; 
        }elseif ($site_code == 'SSJD2') {
            $params_site_code = "raw_batulicin"; 
        }elseif ($site_code == 'BRBS0') {
            $params_site_code = "raw_barabai"; 
        }elseif ($site_code == 'SMRB7') {
            $params_site_code = "raw_samarinda"; 
        }elseif ($site_code == 'BTGB8') {
            $params_site_code = "raw_bontang"; 
        }elseif ($site_code == 'PTK82') {
            $params_site_code = "raw_pontianak"; 
        }elseif ($site_code == 'CSSK3') {
            $params_site_code = "raw_kendari"; 
        }elseif ($site_code == 'DASD1') {
            $params_site_code = "raw_balikpapan"; 
        }elseif ($site_code == 'BLGW4') {
            $params_site_code = "raw_palu"; 
        }

        if ($signature) {
            $params = "where a.signature = '$signature'";
        }else{
            $params = "";
        }

        $query = "
            select a.*, c.kodeprod
            from
            (
                select a.* from management_raw.$params_site_code a
                $params
            )a
            left join 
            (
                select a.nama_barang, a.kodeprod from management_raw.raw_kodeprod_palu a
            )c on a.nama_barang = c.nama_barang
        ";
        return $this->db->query($query);
    }

    public function update_kodeproduk_palu($site_code, $signature){

        if ($site_code == 'BLGW4') {
            $params_site_code = "raw_palu"; 
        }

        $query = "
            update management_raw.$params_site_code a 
            set a.kodeprod = (select b.kodeprod from management_raw.raw_kodeprod_palu b 
            where b.nama_barang = a.nama_barang and b.status = 1)
            where a.signature = '$signature'
        ";
        $update = $this->db->query($query);

        if ($update) {
            return $update;
        }else{
            return array();
        }
    }

    public function inner_kodeproduk_palu($site_code, $signature){

        if ($site_code == 'SSMS9') {
            $params_site_code = "inner_raw_banjarmasin"; 
            $params_site_code2 = "raw_banjarmasin"; 
        }elseif ($site_code == 'SSJD2') {
            $params_site_code = "inner_raw_batulicin"; 
            $params_site_code2 = "raw_batulicin"; 
        }elseif ($site_code == 'BRBS0') {
            $params_site_code = "inner_raw_barabai"; 
            $params_site_code2 = "raw_barabai"; 
        }elseif ($site_code == 'SMRB7') {
            $params_site_code = "inner_raw_samarinda"; 
            $params_site_code2 = "raw_samarinda"; 
        }elseif ($site_code == 'CSSK3') {
            $params_site_code = "inner_raw_kendari"; 
            $params_site_code2 = "raw_kendari"; 
        }elseif ($site_code == 'DASD1') {
            $params_site_code = "inner_raw_balikpapan"; 
            $params_site_code2 = "raw_balikpapan"; 
        }elseif ($site_code == 'BLGW4') {
            $params_site_code = "inner_raw_palu"; 
            $params_site_code2 = "raw_palu"; 
        }

        $query = "
            insert into management_raw.$params_site_code
            select '', '$site_code', '', '', a.*, b.supp, b.grupprod, '', '', ''
            from management_raw.$params_site_code2 a INNER JOIN (
                select a.kodeprod, a.supp, a.grupprod
                from mpm.tabprod a 
            )b on a.kodeprod = b.kodeprod
            where a.signature = '$signature'
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        // die;
        $update = $this->db->query($query);
        if ($update) {
            return $update;
        }else{
            return array();
        }
    }

    public function update_namaprod_palu($site_code, $signature){

        if ($site_code == 'SSMS9') {
            $params_site_code = "inner_raw_banjarmasin"; 
        }elseif ($site_code == 'SSJD2') {
            $params_site_code = "inner_raw_batulicin"; 
        }elseif ($site_code == 'BRBS0') {
            $params_site_code = "inner_raw_barabai"; 
        }elseif ($site_code == 'SMRB7') {
            $params_site_code = "inner_raw_samarinda"; 
        }elseif ($site_code == 'CSSK3') {
            $params_site_code = "inner_raw_kendari"; 
        }elseif ($site_code == 'DASD1') {
            $params_site_code = "inner_raw_balikpapan"; 
        }elseif ($site_code == 'BLGW4') {
            $params_site_code = "inner_raw_palu"; 
        }

        $query = "
            update management_raw.$params_site_code a 
            set a.nama_barang = (
                select b.namaprod
                from mpm.tabprod b 
                where a.kodeprod = b.kodeprod
            ), a.map_divisi = (
                select b.new_divisi
                from mpm.tabprod b 
                where a.kodeprod = b.kodeprod
            ), a.map_group = (
                select b.new_group
                from mpm.tabprod b 
                where a.kodeprod = b.kodeprod
            ), a.map_subgroup = (
                select b.new_subgroup
                from mpm.tabprod b 
                where a.kodeprod = b.kodeprod
            )
            where a.signature = '$signature'
        ";
        $update = $this->db->query($query);
        if ($update) {
            return $update;
        }else{
            return array();
        }
    }

    public function insert_fi_palu($site_code, $signature){
        $get_tahun = "
            select * 
            from management_raw.log_upload a 
            where a.site_code = '$site_code' and a.signature = '$signature'
        ";
        $tahun = $this->db->query($get_tahun)->row()->tahun;
        $bulan = $this->db->query($get_tahun)->row()->bulan;


        $insert = "
        insert data$tahun.fi
        select 		
        '07' as KDOKJDI,		
        `ID_INVOICE` as NODOKJDI,		
        `ID_INVOICE` as NODOKACU,		
        TANGGAL  as TGLDOKJDI,		
        salesman as KODESALES,		
        'BLG' as KODE_COMP,		
        CASE 		
        WHEN Territory = 'PALU TIMUR' THEN 'PTU'		
        WHEN Territory = 'PALU UTARA' THEN 'PUT'		
        WHEN Territory = 'SIGI' THEN 'SGI'		
        WHEN Territory = 'PALU SELATAN' THEN 'PSL'		
        WHEN Territory = 'DONGGALA' THEN 'DGA'		
        WHEN Territory = 'PALU BARAT' THEN 'PBT'		
        WHEN Territory = 'PANTAI BARAT' THEN 'PTB'		
        ELSE 'BLG'		
        END AS 		
        KODE_KOTA,		
        CASE 		
        WHEN CHANNAME = 'TOKO SNACK' THEN 'SNK'		
        WHEN CHANNAME = 'GROSIR' THEN 'TKL'		
        WHEN CHANNAME = 'MODERN PHARMACY' THEN 'APT'		
        WHEN CHANNAME = 'TRADING' THEN 'TRD'		
        WHEN CHANNAME = 'APOTIK' THEN 'APT'		
        WHEN CHANNAME = 'OTHERS' THEN 'OTH'		
        WHEN CHANNAME = 'TOKO' THEN 'TKL'		
        WHEN CHANNAME = 'KIOS' THEN 'TKL'		
        WHEN CHANNAME = '' THEN 'TKL'		
        ELSE CHANNAME		
        END 		
        AS KODE_TYPE,		
        RIGHT(CUSTOMER_ID,6) as KODE_LANG,		
        '' as KODERAYON,		
        a.KODEPROD as KODEPROD,		
        a.SUPP,		
        RIGHT(TANGGAL,2) as HRDOK,		
        '$bulan' as BLNDOK,		
        LEFT(TANGGAL,4) as THNDOK,		
        c.NAMAPROD as NAMAPROD,		
        c.GRUPPROD as GROUPPROD,		
        IF(harga = 1,'0',QTY) as BANYAK,		
        IF(harga = 1,'0',round(harga)) as HARGA,		
        disc3 as POTONGAN,		
        IF(harga = 1,'0',TOTAL) as TOT1,		
        '' as JUM_PROMO, 		
        if(harga = 1,concat(C.KODEPROD,' ',QTY),'') as KETERANGAN,  		
        '' as USER_ISI,		
        '' as JAM_ISI,		
        '' as TGL_ISI, 		
        '' as USER_EDIT, 		
        '' as JAM_EDIT, 		
        '' as TGL_EDIT,		
        '' as USER_DEL, 		
        '' as JAM_DEL, 		
        '' as TGL_DEL, 		
        '' as NO, 		
        '' as BACKUP, 		
        '' as NO_URUT, 		
        'PST' as KODE_GDG, 		
        '' as NAMA_GDG, 		
        IF (CHANNAME IN('PBF','APT','SML','MML','PBF','APT','MODERN PHARMACY','APT','GROSIR','TOB'),'WS','RT')		
        as KODESALUR, 		
        '' as KODEBONUS,		
        '' as NAMABONUS, 		
        '' as GRUPBONUS, 		
        IF(harga = 1,QTY,'0') as UNITBONUS, 		
        Salesman as LAMPIRAN, 		
        '' as H_BELI, 		
        '' as KODEAREA, 		
        ALAMAT as NAMAAREA, 		
        '' as PINJAM, 		
        '' as JUALBANYAK, 		
        '' as JUALPINJAM, 		
        '' as HARGA_EXCL, 		
        '' as TOT1_EXCL, 		
        Customer as NAMA_LANG,		
        'W4' as NOCAB,		
        '$bulan' as BULAN,		
        ' ' as siteid,		
        ' ' as qty1,		
        ' ' as qty2,		
        ' ' as qty3,		
        IF(harga = 1,QTY,'0') as qty_bonus,		
        IF(harga = 1,'1','0') as flag_bonus,		
        ' ' as disc_persen,		
        ' ' as disc_rp,		
        ' ' as disc_value,		
        ' ' as disc_cabang,		
        ' ' as disc_prinsipal,		
        ' ' as disc_xtra,		
        ' ' as rp_cabang,		
        ' ' as rp_prinsipal,		
        ' ' as rp_xtra,		
        ' ' as bonus,		
        concat('11',a.supp) as prinsipalid,		
        ' ' as ex_no_sales,		
        ' ' as status_retur,		
        ' ' as ref,		
        ' ' as term_payment,		
        ' ' as tipe_kl		
                
        from management_raw.inner_raw_palu a		
        LEFT JOIN mpm.tabprod c ON a.kodeprod = c.kodeprod		
        where (QTY not like'-%' or harga not like'-%' or TOTAL not like'-%') and a.signature = '$signature'
        and year(a.tanggal) = $tahun and month(a.tanggal) = $bulan
        order by harga
        ";

        // echo "<pre>";
        // print_r($insert);
        // echo "</pre>";

        return $this->db->query($insert);
    }

    public function insert_ri_palu($site_code, $signature){
        $get_tahun = "
            select * 
            from management_raw.log_upload a 
            where a.site_code = '$site_code' and a.signature = '$signature'
        ";
        $tahun = $this->db->query($get_tahun)->row()->tahun;
        $bulan = $this->db->query($get_tahun)->row()->bulan;

        $insert = "
        insert data$tahun.ri
        select 		
        '07' as KDOKJDI,		
        `ID_INVOICE` as NODOKJDI,		
        `ID_INVOICE` as NODOKACU,		
        TANGGAL as TGLDOKJDI,		
        salesman as KODESALES,		
        'BLG' as KODE_COMP,		
        CASE 		
        WHEN Territory = 'PALU TIMUR' THEN 'PTU'		
        WHEN Territory = 'PALU UTARA' THEN 'PUT'		
        WHEN Territory = 'SIGI' THEN 'SGI'		
        WHEN Territory = 'PALU SELATAN' THEN 'PSL'		
        WHEN Territory = 'DONGGALA' THEN 'DGA'		
        WHEN Territory = 'PALU BARAT' THEN 'PBT'		
        WHEN Territory = 'PANTAI BARAT' THEN 'PTB'		
        ELSE 'BLG'		
        END AS 		
        KODE_KOTA,		
        CASE 		
        WHEN CHANNAME = 'TOKO SNACK' THEN 'SNK'		
        WHEN CHANNAME = 'GROSIR' THEN 'TKL'		
        WHEN CHANNAME = 'MODERN PHARMACY' THEN 'APT'		
        WHEN CHANNAME = 'TRADING' THEN 'TRD'		
        WHEN CHANNAME = 'APOTIK' THEN 'APT'		
        WHEN CHANNAME = 'OTHERS' THEN 'OTH'		
        WHEN CHANNAME = 'TOKO' THEN 'TKL'		
        WHEN CHANNAME = 'KIOS' THEN 'TKL'		
        WHEN CHANNAME = '' THEN 'TKL'		
        ELSE CHANNAME		
        END 		
        AS KODE_TYPE,		
        RIGHT(CUSTOMER_ID,6) as KODE_LANG,		
        '' as KODERAYON,		
        a.KODEPROD as KODEPROD,		
        a.SUPP,		
        RIGHT(TANGGAL,2) as HRDOK,		
        $bulan as BLNDOK,		
        LEFT(TANGGAL,4) as THNDOK,		
        c.NAMAPROD as NAMAPROD,		
        c.GRUPPROD as GROUPPROD,		
        IF(harga = 1,'0',QTY) as BANYAK,		
        IF(harga = 1,'0',round(harga)) as HARGA,		
        disc3 as POTONGAN,		
        IF(harga = 1,'0',TOTAL) as TOT1,		
        '' as JUM_PROMO, 		
        if(harga = 1,concat(C.KODEPROD,' ',QTY),'') as KETERANGAN,  		
        '' as USER_ISI,		
        '' as JAM_ISI,		
        '' as TGL_ISI, 		
        '' as USER_EDIT, 		
        '' as JAM_EDIT, 		
        '' as TGL_EDIT,		
        '' as USER_DEL, 		
        '' as JAM_DEL, 		
        '' as TGL_DEL, 		
        '' as NO, 		
        '' as BACKUP, 		
        '' as NO_URUT, 		
        'PST' as KODE_GDG, 		
        '' as NAMA_GDG, 		
        IF (CHANNAME IN('PBF','APT','SML','MML','PBF','APT','MODERN PHARMACY','APT','GROSIR','TOB'),'WS','RT')		
        as KODESALUR,		
        '' as KODEBONUS,		
        '' as NAMABONUS, 		
        '' as GRUPBONUS, 		
        IF(harga = 1,QTY,'0') as UNITBONUS, 		
        Salesman as LAMPIRAN, 		
        '' as H_BELI, 		
        '' as KODEAREA, 		
        ALAMAT as NAMAAREA, 		
        '' as PINJAM, 		
        '' as JUALBANYAK, 		
        '' as JUALPINJAM, 		
        '' as HARGA_EXCL,  		
        Customer as NAMA_LANG,		
        'W4' as NOCAB,		
        $bulan as BULAN,		
        ' ' as siteid,		
        ' ' as qty1,		
        ' ' as qty2,		
        ' ' as qty3,		
        IF(harga = 1,QTY,'0') as qty_bonus,		
        IF(harga = 1,'1','0') as flag_bonus,		
        ' ' as disc_persen,		
        ' ' as disc_rp,		
        ' ' as disc_value,		
        ' ' as disc_cabang,		
        ' ' as disc_prinsipal,		
        ' ' as disc_xtra,		
        ' ' as rp_cabang,		
        ' ' as rp_prinsipal,		
        ' ' as rp_xtra,		
        ' ' as bonus,		
        concat('11',a.supp) as prinsipalid,		
        ' ' as ex_no_sales,		
        ' ' as status_retur,		
        ' ' as ref,		
        ' ' as term_payment,		
        ' ' as tipe_kl			
        from management_raw.inner_raw_palu a		
        LEFT JOIN mpm.tabprod c ON a.kodeprod = c.kodeprod		
        where (QTY  like'-%' or harga  like'-%' or TOTAL  like'-%') and a.signature = '$signature'
        and year(a.tanggal) = $tahun and month(a.tanggal) = $bulan	
        order by harga
        ";

        // echo "<pre>";
        // print_r($insert);
        // echo "</pre>";

        // die;

        return $this->db->query($insert);
    }

    public function insert_tbkota_palu($site_code, $signature){
        $get_tahun = "
            select * 
            from management_raw.log_upload a 
            where a.site_code = '$site_code' and a.signature = '$signature'
        ";
        $tahun = $this->db->query($get_tahun)->row()->tahun;
        $bulan = $this->db->query($get_tahun)->row()->bulan;

        $insert = "
            insert into data$tahun.tbkota 
            select 		
            KODE_COMP AS KODE_COMP, 		
            KODE_KOTA,		
            CASE		
            WHEN kode_kota = 'PTU' THEN 'PALU TIMUR'		
            WHEN kode_kota = 'PUT' THEN 'PALU UTARA'		
            WHEN kode_kota = 'SGI' THEN 'SIGI'		
            WHEN kode_kota = 'PSL' THEN 'PALU SELATAN'		
            WHEN kode_kota = 'DGA' THEN 'DONGGALA'		
            WHEN kode_kota = 'PBT' THEN 'PALU BARAT'		
            WHEN kode_kota = 'PTB' THEN 'PANTAI BARAT'		
            ELSE 'PAU'		
            END AS NAMA_KOTA,		
            NOCAB			
            from data$tahun.fi 
            where concat(kode_comp, nocab) = '$site_code' 
            group by KODE_COMP,KODE_KOTA
        ";

        // echo "<pre>";
        // print_r($insert);
        // echo "</pre>";

        // die;

        return $this->db->query($insert);
    }

    public function get_raw_draft_vbt_makasar($site_code, $signature = ''){

        if ($site_code == 'VBTV1') {
            $params_site_code = "raw_vbt_makasar"; 
        }elseif ($site_code == 'BKMV5') {
            $params_site_code = "raw_vbt_bulukumba"; 
        }elseif ($site_code == 'BONV4') {
            $params_site_code = "raw_vbt_bone"; 
        }elseif ($site_code == 'PALV2') {
            $params_site_code = "raw_vbt_palopo"; 
        }elseif ($site_code == 'PARV3') {
            $params_site_code = "raw_vbt_pare"; 
        }

        if ($signature) {
            $params = "where a.signature = '$signature'";
        }else{
            $params = "";
        }

        $query = "
            select a.*, b.kodeprod, c.kode_cust_mpm
            from
            (
                select a.* from management_raw.$params_site_code a
                $params
            )a
            left join 
            (
                select a.code, a.kodeprod from pmu.mapping a
                where a.nocab = 'V'
            )b on a.s_kode = b.code or a.nama_barang = b.code
            left join 
            (
                select a.kode, a.kode_cust_mpm from management_raw.raw_customer_vbt a
                where a.site = '$site_code'
            )c on concat(left(a.kd_pelanggan,3),a.nama_karyawan) = c.kode
            order by b.kodeprod asc
        ";
        return $this->db->query($query);
    }

    public function update_kodeproduk_vbt_makasar($site_code, $signature){

        if ($site_code == 'VBTV1') {
            $params_site_code = "raw_vbt_makasar"; 
        }elseif ($site_code == 'BKMV5') {
            $params_site_code = "raw_vbt_bulukumba"; 
        }elseif ($site_code == 'BONV4') {
            $params_site_code = "raw_vbt_bone"; 
        }elseif ($site_code == 'PALV2') {
            $params_site_code = "raw_vbt_palopo"; 
        }elseif ($site_code == 'PARV3') {
            $params_site_code = "raw_vbt_pare"; 
        }

        $query = "
            update management_raw.$params_site_code a 
            set a.s_kode = (select b.kodeprod from pmu.mapping b 
            where (a.s_kode = b.code or a.nama_barang = b.code) and b.nocab = 'V')
            where a.signature = '$signature'
        ";
        echo "<pre>";
        print_r($query);
        echo "</pre>";
        $update = $this->db->query($query);
        
        die;
        if ($update) {
            return $update;
        }else{
            return array();
        }
    }

    public function inner_kodeproduk_vbt_makasar($site_code, $signature){

        if ($site_code == 'VBTV1') {
            $params_site_code = "inner_raw_vbt_makasar"; 
            $params_site_code2 = "raw_vbt_makasar"; 
        }elseif ($site_code == 'BKMV5') {
            $params_site_code = "inner_raw_vbt_bulukumba"; 
            $params_site_code2 = "raw_vbt_bulukumba"; 
        }elseif ($site_code == 'BONV4') {
            $params_site_code = "inner_raw_vbt_bone"; 
            $params_site_code2 = "raw_vbt_bone"; 
        }elseif ($site_code == 'PALV2') {
            $params_site_code = "inner_raw_vbt_palopo"; 
            $params_site_code2 = "raw_vbt_palopo"; 
        }elseif ($site_code == 'PARV3') {
            $params_site_code = "inner_raw_vbt_pare"; 
            $params_site_code2 = "raw_vbt_pare"; 
        }

        $query = "
            insert into management_raw.$params_site_code
            select '', '$site_code', '', '', a.*, b.supp, b.grupprod, '', '', ''
            from management_raw.$params_site_code2 a INNER JOIN (
                select a.kodeprod, a.supp, a.grupprod
                from mpm.tabprod a 
            )b on a.s_kode = b.kodeprod
            where a.signature = '$signature'
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        // die;
        $update = $this->db->query($query);
        if ($update) {
            return $update;
        }else{
            return array();
        }
    }

    public function update_namaprod_vbt_makasar($site_code, $signature){

        if ($site_code == 'VBTV1') {
            $params_site_code = "inner_raw_vbt_makasar"; 
        }elseif ($site_code == 'BKMV5') {
            $params_site_code = "inner_raw_vbt_bulukumba"; 
        }elseif ($site_code == 'BONV4') {
            $params_site_code = "inner_raw_vbt_bone"; 
        }elseif ($site_code == 'PALV2') {
            $params_site_code = "inner_raw_vbt_palopo"; 
        }elseif ($site_code == 'PARV3') {
            $params_site_code = "inner_raw_vbt_pare"; 
        }
        $query = "
            update management_raw.$params_site_code a 
            set a.nama_barang = (
                select b.namaprod
                from mpm.tabprod b 
                where a.s_kode = b.kodeprod
            ), a.map_divisi = (
                select b.new_divisi
                from mpm.tabprod b 
                where a.s_kode = b.kodeprod
            ), a.map_group = (
                select b.new_group
                from mpm.tabprod b 
                where a.s_kode = b.kodeprod
            ), a.map_subgroup = (
                select b.new_subgroup
                from mpm.tabprod b 
                where a.s_kode = b.kodeprod
            )
            where a.signature = '$signature'
        ";
        $update = $this->db->query($query);
        if ($update) {
            return $update;
        }else{
            return array();
        }
    }

    public function update_tanggal_vbt_makasar($site_code, $signature){
        
        if ($site_code == 'VBTV1') {
            $params_site_code = "inner_raw_vbt_makasar"; 
        }elseif ($site_code == 'BKMV5') {
            $params_site_code = "inner_raw_vbt_bulukumba"; 
        }elseif ($site_code == 'BONV4') {
            $params_site_code = "inner_raw_vbt_bone"; 
        }elseif ($site_code == 'PALV2') {
            $params_site_code = "inner_raw_vbt_palopo"; 
        }elseif ($site_code == 'PARV3') {
            $params_site_code = "inner_raw_vbt_pare"; 
        }

        $query = "
            update management_raw.$params_site_code a 
            set a.tgl_order = str_to_date(a.tgl_order,'%m/%d/%Y')
            where a.signature = '$signature'
        ";
        $update = $this->db->query($query);
        if ($update) {
            return $update;
        }else{
            return array();
        }
    }

    public function insert_fi_vbt_makasar($site_code, $signature){
        $get_tahun = "
            select * 
            from management_raw.log_upload a 
            where a.site_code = '$site_code' and a.signature = '$signature'
        ";
        $tahun = $this->db->query($get_tahun)->row()->tahun;
        $bulan = $this->db->query($get_tahun)->row()->bulan;


        $insert = "
        insert data$tahun.fi
        select 		
        '07' as KDOKJDI,		
        Kd_Penjualan as NODOKJDI,		
        Kd_Penjualan as NODOKACU,		
        Tgl_Order as TGLDOKJDI,		
        b.kode_cust_mpm as KODESALES,		
        'VBT' as KODE_COMP,		
        'MKS' as KODE_KOTA,		
        CASE 		
            WHEN Principal_Channel LIKE'PEDAGANG BESAR CONSUMER%'THEN 'PDC'	
            WHEN Principal_Channel LIKE'PEDAGANG BESAR FARMASI%'THEN 'PBF'	
            WHEN Principal_Channel LIKE'Minimarket%'THEN 'MML'	
            WHEN Principal_Channel LIKE'Supermarket%'THEN 'SMR'	
            WHEN Principal_Channel LIKE'Warung%'THEN 'WRG'	
            WHEN Principal_Channel LIKE'Toko Kelontong%'THEN 'TKL'	
            WHEN Principal_Channel LIKE'Apotik%'THEN 'APT'	
            WHEN Principal_Channel LIKE'Hotel%'THEN 'HRK'	
            WHEN Principal_Channel LIKE'Trading %'THEN 'TRD'	
            ELSE	
                'OTH'
        END AS KODE_TYPE,		
        CONCAT(1,RIGHT(Kd_Pelanggan,5)) as KODE_LANG,		
        '' as KODERAYON,		
        d.kodeprod as KODEPROD,		
        a.SUPP,		
        RIGHT(Tgl_Order,2) as HRDOK,		
        '$bulan' as BLNDOK,		
        LEFT(Tgl_Order,4) as THNDOK,		
        c.NAMAPROD as NAMAPROD,		
        c.GRUPPROD as GROUPPROD,		
        IF(TOTAL='0','0',`qty_pcs`*d.satuan) as BANYAK,		
        ROUND(TOTAL/(`qty_pcs`*d.satuan),2) as HARGA,		
        '' as POTONGAN,		
        round(Total,2) as TOT1,		
        '' as JUM_PROMO, 		
        if(total=0 and `qty_pcs`<>0,concat(d.kodeprod,' ',`qty_pcs`*d.satuan),'')as KETERANGAN,  		 		
        '' as USER_ISI,		
        '' as JAM_ISI,		
        '' as TGL_ISI, 		
        '' as USER_EDIT, 		
        '' as JAM_EDIT, 		
        '' as TGL_EDIT,		
        '' as USER_DEL, 		
        '' as JAM_DEL, 		
        '' as TGL_DEL, 		
        '' as NO, 		
        '' as BACKUP, 		
        '' as NO_URUT, 		
        'PST' as KODE_GDG, 		
        '' as NAMA_GDG, 		
        CASE 		
        WHEN Segment LIKE 'SO%' THEN 'WS'		
        WHEN Segment LIKE '%MARKET%' THEN 'WS'		
        WHEN Segment LIKE '%CAFES%' THEN 'SWS'		
        WHEN Segment LIKE '%FOOD%' THEN 'SWS'		
        WHEN Segment LIKE '%STORE%' THEN 'SWS'		
        ELSE 'RT'		
        END AS KODESALUR,		
        '' as KODEBONUS,		
        '' as NAMABONUS, 		
        '' as GRUPBONUS, 		
        '0' as UNITBONUS, 		
        Nama_Karyawan as LAMPIRAN, 		
        '' as H_BELI, 		
        '' as KODEAREA, 		
        ALAMAT as NAMAAREA, 		
        '' as PINJAM, 		
        '' as JUALBANYAK, 		
        '' as JUALPINJAM, 		
        '' as HARGA_EXCL, 		
        '' as TOT1_EXCL, 		
        Nama_Pelanggan as NAMA_LANG,		
        'V1' as NOCAB,		
        '$bulan' as BULAN,		
        ' ' as siteid,		
        ' ' as qty1,		
        ' ' as qty2,		
        ' ' as qty3,		
        '0' as qty_bonus,		
        '0' as flag_bonus,		
        ' ' as disc_persen,		
        ' ' as disc_rp,		
        ' ' as disc_value,		
        discounttd as disc_cabang,		
        discountp as disc_prinsipal,		
        ' ' as disc_xtra,		
        ' ' as rp_cabang,		
        ' ' as rp_prinsipal,		
        ' ' as rp_xtra,		
        ' ' as bonus,		
        concat(11,a.supp) as prinsipalid,		
        ' ' as ex_no_sales,		
        ' ' as status_retur,		
        ' ' as ref,		
        ' ' as term_payment,		
        ' ' as tipe_kl		
                
        from management_raw.inner_raw_vbt_makasar a		
        INNER JOIN (
            SELECT * FROM pmu.mapping where nocab='V'
            GROUP BY kodeprod
            )d on IF(a.S_Kode=0,a.NAMA_BARANG,a.S_Kode)=d.`kodeprod` or IF(a.S_Kode is NULL,a.NAMA_BARANG,a.S_Kode)=d.`kodeprod` 	
            INNER JOIN mpm.tabprod c on d.kodeprod=c.KODEPROD 
            INNER JOIN management_raw.raw_customer_vbt b on concat(left(a.kd_pelanggan,3),a.nama_karyawan)=b.kode 		
        where `tipe_transaksi`='PENJUALAN' and a.signature = '$signature'
        and year(a.tgl_order) = $tahun and month(a.tgl_order) = $bulan AND b.site = '$site_code' and discountp != '100'
        
        union all

        select 		
        '07' as KDOKJDI,		
        Kd_Penjualan as NODOKJDI,		
        Kd_Penjualan as NODOKACU,		
        Tgl_Order as TGLDOKJDI,		
        b.kode_cust_mpm as KODESALES,		
        'VBT' as KODE_COMP,		
        'MKS' as KODE_KOTA,		
        CASE 		
            WHEN Principal_Channel LIKE'PEDAGANG BESAR CONSUMER%'THEN 'PDC'	
            WHEN Principal_Channel LIKE'PEDAGANG BESAR FARMASI%'THEN 'PBF'	
            WHEN Principal_Channel LIKE'Minimarket%'THEN 'MML'	
            WHEN Principal_Channel LIKE'Supermarket%'THEN 'SMR'	
            WHEN Principal_Channel LIKE'Warung%'THEN 'WRG'	
            WHEN Principal_Channel LIKE'Toko Kelontong%'THEN 'TKL'	
            WHEN Principal_Channel LIKE'Apotik%'THEN 'APT'	
            WHEN Principal_Channel LIKE'Hotel%'THEN 'HRK'	
            WHEN Principal_Channel LIKE'Trading %'THEN 'TRD'	
            ELSE	
                'OTH'
        END AS KODE_TYPE,		
        CONCAT(1,RIGHT(Kd_Pelanggan,5)) as KODE_LANG,		
        '' as KODERAYON,		
        d.kodeprod as KODEPROD,		
        a.SUPP,		
        RIGHT(Tgl_Order,2) as HRDOK,		
        '$bulan' as BLNDOK,		
        LEFT(Tgl_Order,4) as THNDOK,		
        c.NAMAPROD as NAMAPROD,		
        c.GRUPPROD as GROUPPROD,		
        '0' as BANYAK,		
        '0' as HARGA,		
        '0' as POTONGAN,		
        '0' as TOT1,		
        '' as JUM_PROMO, 		
        if(total=0 and `qty_pcs`<>0,concat(d.kodeprod,' ',`qty_pcs`*d.satuan),'')as KETERANGAN,  		 		
        '' as USER_ISI,		
        '' as JAM_ISI,		
        '' as TGL_ISI, 		
        '' as USER_EDIT, 		
        '' as JAM_EDIT, 		
        '' as TGL_EDIT,		
        '' as USER_DEL, 		
        '' as JAM_DEL, 		
        '' as TGL_DEL, 		
        '' as NO, 		
        '' as BACKUP, 		
        '' as NO_URUT, 		
        'PST' as KODE_GDG, 		
        '' as NAMA_GDG, 		
        CASE 		
        WHEN Segment LIKE 'SO%' THEN 'WS'		
        WHEN Segment LIKE '%MARKET%' THEN 'WS'		
        WHEN Segment LIKE '%CAFES%' THEN 'SWS'		
        WHEN Segment LIKE '%FOOD%' THEN 'SWS'		
        WHEN Segment LIKE '%STORE%' THEN 'SWS'		
        ELSE 'RT'		
        END AS KODESALUR,		
        '' as KODEBONUS,		
        '' as NAMABONUS, 		
        '' as GRUPBONUS, 		
        `qty_pcs` as UNITBONUS, 		
        Nama_Karyawan as LAMPIRAN, 		
        '' as H_BELI, 		
        '' as KODEAREA, 		
        ALAMAT as NAMAAREA, 		
        '' as PINJAM, 		
        '' as JUALBANYAK, 		
        '' as JUALPINJAM, 		
        '' as HARGA_EXCL, 		
        '' as TOT1_EXCL, 		
        Nama_Pelanggan as NAMA_LANG,		
        'V1' as NOCAB,		
        '$bulan' as BULAN,		
        ' ' as siteid,		
        ' ' as qty1,		
        ' ' as qty2,		
        ' ' as qty3,		
        `qty_pcs` as qty_bonus,		
        '1' as flag_bonus,		
        ' ' as disc_persen,		
        ' ' as disc_rp,		
        ' ' as disc_value,		
        discounttd as disc_cabang,		
        discountp as disc_prinsipal,		
        ' ' as disc_xtra,		
        ' ' as rp_cabang,		
        ' ' as rp_prinsipal,		
        ' ' as rp_xtra,		
        ' ' as bonus,		
        concat(11,a.supp) as prinsipalid,		
        ' ' as ex_no_sales,		
        ' ' as status_retur,		
        ' ' as ref,		
        ' ' as term_payment,		
        ' ' as tipe_kl		
                
        from management_raw.inner_raw_vbt_makasar a		
        INNER JOIN (
            SELECT * FROM pmu.mapping where nocab='V'
            GROUP BY kodeprod
            )d on IF(a.S_Kode=0,a.NAMA_BARANG,a.S_Kode)=d.`kodeprod` or IF(a.S_Kode is NULL,a.NAMA_BARANG,a.S_Kode)=d.`kodeprod` 	
            INNER JOIN mpm.tabprod c on d.kodeprod=c.KODEPROD 
            INNER JOIN management_raw.raw_customer_vbt b on concat(left(a.kd_pelanggan,3),a.nama_karyawan)=b.kode 		
        where `tipe_transaksi`='PENJUALAN' and a.signature = '$signature'
        and year(a.tgl_order) = $tahun and month(a.tgl_order) = $bulan AND b.site = '$site_code' and discountp = '100'
        order by harga	
        ";

        // echo "<pre>";
        // print_r($insert);
        // echo "</pre>";

        return $this->db->query($insert);
    }

    public function insert_ri_vbt_makasar($site_code, $signature){
        $get_tahun = "
            select * 
            from management_raw.log_upload a 
            where a.site_code = '$site_code' and a.signature = '$signature'
        ";
        $tahun = $this->db->query($get_tahun)->row()->tahun;
        $bulan = $this->db->query($get_tahun)->row()->bulan;

        $insert = "
        insert data$tahun.ri
        select 		
        '07' as KDOKJDI,		
        Kd_Penjualan as NODOKJDI,		
        Kd_Penjualan as NODOKACU,		
        Tgl_Order as TGLDOKJDI,		
        b.kode_cust_mpm as KODESALES,		
        'VBT' as KODE_COMP,		
        'MKS' as KODE_KOTA,		
        CASE 		
            WHEN Principal_Channel LIKE'PEDAGANG BESAR CONSUMER%'THEN 'PDC'	
            WHEN Principal_Channel LIKE'PEDAGANG BESAR FARMASI%'THEN 'PBF'	
            WHEN Principal_Channel LIKE'Minimarket%'THEN 'MML'	
            WHEN Principal_Channel LIKE'Supermarket%'THEN 'SMR'	
            WHEN Principal_Channel LIKE'Warung%'THEN 'WRG'	
            WHEN Principal_Channel LIKE'Toko Kelontong%'THEN 'TKL'	
            WHEN Principal_Channel LIKE'Apotik%'THEN 'APT'	
            WHEN Principal_Channel LIKE'Hotel%'THEN 'HRK'	
            WHEN Principal_Channel LIKE'Trading %'THEN 'TRD'	
            ELSE	
                'OTH'
        END AS KODE_TYPE,		
        CONCAT(1,RIGHT(Kd_Pelanggan,5)) as KODE_LANG,		
        '' as KODERAYON,		
        d.kodeprod as KODEPROD,		
        a.SUPP,		
        RIGHT(Tgl_Order,2) as HRDOK,		
        '$bulan' as BLNDOK,		
        LEFT(Tgl_Order,4) as THNDOK,		
        c.NAMAPROD as NAMAPROD,		
        c.GRUPPROD as GROUPPROD,		
        IF(TOTAL='0','0',`qty_pcs`*d.satuan) as BANYAK,		
        IF(TOTAL='0',0,ROUND(TOTAL/(`qty_pcs`*d.satuan),2)) as HARGA,		
        '' as POTONGAN,		
        round(Total,2) as TOT1,		
        '' as JUM_PROMO, 		
        if(total=0 and `qty_pcs`<>0,concat(d.kodeprod,' ',`qty_pcs`*d.satuan),'')as KETERANGAN,  		  		
        '' as USER_ISI,		
        '' as JAM_ISI,		
        '' as TGL_ISI, 		
        '' as USER_EDIT, 		
        '' as JAM_EDIT, 		
        '' as TGL_EDIT,		
        '' as USER_DEL, 		
        '' as JAM_DEL, 		
        '' as TGL_DEL, 		
        '' as NO, 		
        '' as BACKUP, 		
        '' as NO_URUT, 		
        'PST' as KODE_GDG, 		
        '' as NAMA_GDG, 		
        CASE 		
        WHEN Segment LIKE 'SO%' THEN 'WS'		
        WHEN Segment LIKE '%MARKET%' THEN 'WS'		
        WHEN Segment LIKE '%CAFES%' THEN 'SWS'		
        WHEN Segment LIKE '%FOOD%' THEN 'SWS'		
        WHEN Segment LIKE '%STORE%' THEN 'SWS'		
        ELSE 'RT'		
        END AS KODESALUR,		
        '' as KODEBONUS,		
        '' as NAMABONUS, 		
        '' as GRUPBONUS, 		
        '0' as UNITBONUS, 		
        Nama_Karyawan as LAMPIRAN, 		
        '' as H_BELI, 		
        '' as KODEAREA, 		
        ALAMAT as NAMAAREA, 		
        '' as PINJAM, 		
        '' as JUALBANYAK, 		
        '' as JUALPINJAM, 		
        '' as HARGA_EXCL, 				
        Nama_Pelanggan as NAMA_LANG,		
        'V1' as NOCAB,		
        '$bulan' as BULAN,		
        ' ' as siteid,		
        ' ' as qty1,		
        ' ' as qty2,		
        ' ' as qty3,		
        '0' as qty_bonus,		
        '0' as flag_bonus,		
        ' ' as disc_persen,		
        ' ' as disc_rp,		
        ' ' as disc_value,	
        discounttd as disc_cabang,		
        discountp as disc_prinsipal,	
        ' ' as disc_xtra,		
        ' ' as rp_cabang,		
        ' ' as rp_prinsipal,		
        ' ' as rp_xtra,		
        ' ' as bonus,		
        ' ' as prinsipalid,		
        ' ' as ex_no_sales,		
        ' ' as status_retur,		
        ' ' as ref,		
        ' ' as term_payment,		
        ' ' as tipe_kl				
                
        from management_raw.inner_raw_vbt_makasar a		
        INNER JOIN (
            SELECT * FROM pmu.mapping where nocab='V'
            GROUP BY kodeprod
            )d on IF(a.S_Kode=0,a.NAMA_BARANG,a.S_Kode)=d.`kodeprod` or IF(a.S_Kode is NULL,a.NAMA_BARANG,a.S_Kode)=d.`kodeprod` 	
            INNER JOIN mpm.tabprod c on d.kodeprod=c.KODEPROD 
            INNER JOIN management_raw.raw_customer_vbt b on concat(left(a.kd_pelanggan,3),a.nama_karyawan)=b.kode 		
        where `tipe_transaksi`='RETUR_PENJUALAN' and a.signature = '$signature'
        and year(a.tgl_order) = $tahun and month(a.tgl_order) = $bulan AND b.site = '$site_code' and discountp != 100
        
        union all

        select 		
        '07' as KDOKJDI,		
        Kd_Penjualan as NODOKJDI,		
        Kd_Penjualan as NODOKACU,		
        Tgl_Order as TGLDOKJDI,		
        b.kode_cust_mpm as KODESALES,		
        'VBT' as KODE_COMP,		
        'MKS' as KODE_KOTA,		
        CASE 		
            WHEN Principal_Channel LIKE'PEDAGANG BESAR CONSUMER%'THEN 'PDC'	
            WHEN Principal_Channel LIKE'PEDAGANG BESAR FARMASI%'THEN 'PBF'	
            WHEN Principal_Channel LIKE'Minimarket%'THEN 'MML'	
            WHEN Principal_Channel LIKE'Supermarket%'THEN 'SMR'	
            WHEN Principal_Channel LIKE'Warung%'THEN 'WRG'	
            WHEN Principal_Channel LIKE'Toko Kelontong%'THEN 'TKL'	
            WHEN Principal_Channel LIKE'Apotik%'THEN 'APT'	
            WHEN Principal_Channel LIKE'Hotel%'THEN 'HRK'	
            WHEN Principal_Channel LIKE'Trading %'THEN 'TRD'	
            ELSE	
                'OTH'
        END AS KODE_TYPE,		
        CONCAT(1,RIGHT(Kd_Pelanggan,5)) as KODE_LANG,		
        '' as KODERAYON,		
        d.kodeprod as KODEPROD,		
        a.SUPP,		
        RIGHT(Tgl_Order,2) as HRDOK,		
        '$bulan' as BLNDOK,		
        LEFT(Tgl_Order,4) as THNDOK,		
        c.NAMAPROD as NAMAPROD,		
        c.GRUPPROD as GROUPPROD,		
        '0'as BANYAK,		
        '0' as HARGA,		
        '0' as POTONGAN,		
        '0' as TOT1,		
        '' as JUM_PROMO, 		
        if(total=0 and `qty_pcs`<>0,concat(d.kodeprod,' ',`qty_pcs`*d.satuan),'')as KETERANGAN,  		  		
        '' as USER_ISI,		
        '' as JAM_ISI,		
        '' as TGL_ISI, 		
        '' as USER_EDIT, 		
        '' as JAM_EDIT, 		
        '' as TGL_EDIT,		
        '' as USER_DEL, 		
        '' as JAM_DEL, 		
        '' as TGL_DEL, 		
        '' as NO, 		
        '' as BACKUP, 		
        '' as NO_URUT, 		
        'PST' as KODE_GDG, 		
        '' as NAMA_GDG, 		
        CASE 		
        WHEN Segment LIKE 'SO%' THEN 'WS'		
        WHEN Segment LIKE '%MARKET%' THEN 'WS'		
        WHEN Segment LIKE '%CAFES%' THEN 'SWS'		
        WHEN Segment LIKE '%FOOD%' THEN 'SWS'		
        WHEN Segment LIKE '%STORE%' THEN 'SWS'		
        ELSE 'RT'		
        END AS KODESALUR,		
        '' as KODEBONUS,		
        '' as NAMABONUS, 		
        '' as GRUPBONUS, 		
        `qty_pcs` as UNITBONUS, 		
        Nama_Karyawan as LAMPIRAN, 		
        '' as H_BELI, 		
        '' as KODEAREA, 		
        ALAMAT as NAMAAREA, 		
        '' as PINJAM, 		
        '' as JUALBANYAK, 		
        '' as JUALPINJAM, 		
        '' as HARGA_EXCL, 				
        Nama_Pelanggan as NAMA_LANG,		
        'V1' as NOCAB,		
        '$bulan' as BULAN,		
        ' ' as siteid,		
        ' ' as qty1,		
        ' ' as qty2,		
        ' ' as qty3,		
        `qty_pcs` as qty_bonus,		
        '1' as flag_bonus,		
        ' ' as disc_persen,		
        ' ' as disc_rp,		
        ' ' as disc_value,	
        discounttd as disc_cabang,		
        discountp as disc_prinsipal,	
        ' ' as disc_xtra,		
        ' ' as rp_cabang,		
        ' ' as rp_prinsipal,		
        ' ' as rp_xtra,		
        ' ' as bonus,		
        ' ' as prinsipalid,		
        ' ' as ex_no_sales,		
        ' ' as status_retur,		
        ' ' as ref,		
        ' ' as term_payment,		
        ' ' as tipe_kl				
                
        from management_raw.inner_raw_vbt_makasar a		
        INNER JOIN (
            SELECT * FROM pmu.mapping where nocab='V'
            GROUP BY kodeprod
            )d on IF(a.S_Kode=0,a.NAMA_BARANG,a.S_Kode)=d.`kodeprod` or IF(a.S_Kode is NULL,a.NAMA_BARANG,a.S_Kode)=d.`kodeprod` 	
            INNER JOIN mpm.tabprod c on d.kodeprod=c.KODEPROD 
            INNER JOIN management_raw.raw_customer_vbt b on concat(left(a.kd_pelanggan,3),a.nama_karyawan)=b.kode 		
        where `tipe_transaksi`='RETUR_PENJUALAN' and a.signature = '$signature'
        and year(a.tgl_order) = $tahun and month(a.tgl_order) = $bulan AND b.site = '$site_code' and discountp = 100
        order by harga
        ";

        // echo "<pre>";
        // print_r($insert);
        // echo "</pre>";

        // die;

        return $this->db->query($insert);
    }

    public function insert_tbkota_vbt_makasar($site_code, $signature){
        $get_tahun = "
            select * 
            from management_raw.log_upload a 
            where a.site_code = '$site_code' and a.signature = '$signature'
        ";
        $tahun = $this->db->query($get_tahun)->row()->tahun;
        $bulan = $this->db->query($get_tahun)->row()->bulan;

        $insert = "
            insert into data$tahun.tbkota 
            select 			
            KODE_COMP AS KODE_COMP, 		
            'MKS' AS KODE_KOTA,		
            'MAKASSAR' AS NAMA_KOTA,		
            NOCAB			
            from data$tahun.fi 
            where concat(kode_comp, nocab) = '$site_code' 
            group by KODE_COMP,KODE_KOTA
        ";

        // echo "<pre>";
        // print_r($insert);
        // echo "</pre>";

        // die;

        return $this->db->query($insert);
    }

    public function insert_mpm_upload_vbt_makasar($site_code, $signature, $omzet = '', $status_closing = 0){

        if ($site_code == 'VBTV1') {
            $params_site_code = "inner_raw_vbt_makasar"; 
        }elseif ($site_code == 'BKMV5') {
            $params_site_code = "inner_raw_vbt_bulukumba"; 
        }elseif ($site_code == 'BONV4') {
            $params_site_code = "inner_raw_vbt_bone"; 
        }elseif ($site_code == 'PALV2') {
            $params_site_code = "inner_raw_vbt_palopo"; 
        }elseif ($site_code == 'PARV3') {
            $params_site_code = "inner_raw_vbt_pare"; 
        }

        $query_tanggal = "
            select  a.tgl_order, if(length(max(day(a.tgl_order))) = 1, concat('0',max(day(a.tgl_order))), max(day(a.tgl_order))) as hari,
            if(length(max(month(a.tgl_order))) = 1, concat('0',max(month(a.tgl_order))), max(month(a.tgl_order))) as bulan,
            max(year(a.tgl_order)) as tahun  
            from management_raw.$params_site_code a
            where a.signature = '$signature'
        ";

        $get_hari = $this->db->query($query_tanggal)->row()->hari;
        $get_bulan = $this->db->query($query_tanggal)->row()->bulan;
        $get_tahun = $this->db->query($query_tanggal)->row()->tahun;

        // die;
        $created_at = $this->model_outlet_transaksi->timezone();

        // die;

        $cek_userid  = "
            select id
            from mpm.user a
            where a.username = left('$site_code',3)
        ";

        // die;

        $userid = $this->db->query($cek_userid)->row()->id;

        $insert = [
            'userid'            => $userid,
            'lastupload'        => $created_at,
            'filename'          => 'NON SDS',
            'tanggal'           => $get_hari,
            'bulan'             => $get_bulan,
            'tahun'             => $get_tahun,
            'status'            => 1,
            'status_closing'    => $status_closing,
            'omzet'             => $omzet,
            'flag_check'        => 0
        ];

        return $this->db->insert('mpm.upload', $insert);
    }

    public function insert_fi_vbt_bulukumba($site_code, $signature){
        $get_tahun = "
            select * 
            from management_raw.log_upload a 
            where a.site_code = '$site_code' and a.signature = '$signature'
        ";
        $tahun = $this->db->query($get_tahun)->row()->tahun;
        $bulan = $this->db->query($get_tahun)->row()->bulan;


        $insert = "
        insert data$tahun.fi
        select 		
        '07' as KDOKJDI,		
        Kd_Penjualan as NODOKJDI,		
        Kd_Penjualan as NODOKACU,		
        Tgl_Order as TGLDOKJDI,		
        b.kode_cust_mpm as KODESALES,		
        'BKM' as KODE_COMP,		
        'MKS' as KODE_KOTA,		
        CASE 		
            WHEN Principal_Channel LIKE'PEDAGANG BESAR CONSUMER%'THEN 'PDC'	
            WHEN Principal_Channel LIKE'PEDAGANG BESAR FARMASI%'THEN 'PBF'	
            WHEN Principal_Channel LIKE'Minimarket%'THEN 'MML'	
            WHEN Principal_Channel LIKE'Supermarket%'THEN 'SMR'	
            WHEN Principal_Channel LIKE'Warung%'THEN 'WRG'	
            WHEN Principal_Channel LIKE'Toko Kelontong%'THEN 'TKL'	
            WHEN Principal_Channel LIKE'Apotik%'THEN 'APT'	
            WHEN Principal_Channel LIKE'Hotel%'THEN 'HRK'	
            WHEN Principal_Channel LIKE'Trading %'THEN 'TRD'	
            ELSE	
                'OTH'
        END AS KODE_TYPE,		
        CONCAT(1,RIGHT(Kd_Pelanggan,5)) as KODE_LANG,		
        '' as KODERAYON,		
        d.kodeprod as KODEPROD,		
        a.SUPP,		
        RIGHT(Tgl_Order,2) as HRDOK,		
        '$bulan' as BLNDOK,		
        LEFT(Tgl_Order,4) as THNDOK,		
        c.NAMAPROD as NAMAPROD,		
        c.GRUPPROD as GROUPPROD,		
        IF(TOTAL='0','0',`qty_pcs`*d.satuan) as BANYAK,		
        ROUND(TOTAL/(`qty_pcs`*d.satuan),2) as HARGA,		
        '' as POTONGAN,		
        round(Total,2) as TOT1,		
        '' as JUM_PROMO, 		
        if(total=0 and `qty_pcs`<>0,concat(d.kodeprod,' ',`qty_pcs`*d.satuan),'')as KETERANGAN,  		 		
        '' as USER_ISI,		
        '' as JAM_ISI,		
        '' as TGL_ISI, 		
        '' as USER_EDIT, 		
        '' as JAM_EDIT, 		
        '' as TGL_EDIT,		
        '' as USER_DEL, 		
        '' as JAM_DEL, 		
        '' as TGL_DEL, 		
        '' as NO, 		
        '' as BACKUP, 		
        '' as NO_URUT, 		
        'PST' as KODE_GDG, 		
        '' as NAMA_GDG, 		
        CASE 		
        WHEN Segment LIKE 'SO%' THEN 'WS'		
        WHEN Segment LIKE '%MARKET%' THEN 'WS'		
        WHEN Segment LIKE '%CAFES%' THEN 'SWS'		
        WHEN Segment LIKE '%FOOD%' THEN 'SWS'		
        WHEN Segment LIKE '%STORE%' THEN 'SWS'		
        ELSE 'RT'		
        END AS KODESALUR,		
        '' as KODEBONUS,		
        '' as NAMABONUS, 		
        '' as GRUPBONUS, 		
        '0' as UNITBONUS, 		
        Nama_Karyawan as LAMPIRAN, 		
        '' as H_BELI, 		
        '' as KODEAREA, 		
        ALAMAT as NAMAAREA, 		
        '' as PINJAM, 		
        '' as JUALBANYAK, 		
        '' as JUALPINJAM, 		
        '' as HARGA_EXCL, 		
        '' as TOT1_EXCL, 		
        Nama_Pelanggan as NAMA_LANG,		
        'V5' as NOCAB,		
        '$bulan' as BULAN,		
        ' ' as siteid,		
        ' ' as qty1,		
        ' ' as qty2,		
        ' ' as qty3,		
        '0' as qty_bonus,		
        '0' as flag_bonus,		
        ' ' as disc_persen,		
        ' ' as disc_rp,		
        ' ' as disc_value,		
        discounttd as disc_cabang,		
        discountp as disc_prinsipal,		
        ' ' as disc_xtra,		
        ' ' as rp_cabang,		
        ' ' as rp_prinsipal,		
        ' ' as rp_xtra,		
        ' ' as bonus,		
        concat(11,a.supp) as prinsipalid,		
        ' ' as ex_no_sales,		
        ' ' as status_retur,		
        ' ' as ref,		
        ' ' as term_payment,		
        ' ' as tipe_kl		
                
        from management_raw.inner_raw_vbt_bulukumba a		
        INNER JOIN (
            SELECT * FROM pmu.mapping where nocab='V'
            GROUP BY kodeprod
            )d on IF(a.S_Kode=0,a.NAMA_BARANG,a.S_Kode)=d.`kodeprod` or IF(a.S_Kode is NULL,a.NAMA_BARANG,a.S_Kode)=d.`kodeprod` 	
            INNER JOIN mpm.tabprod c on d.kodeprod=c.KODEPROD 
            INNER JOIN management_raw.raw_customer_vbt b on concat(left(a.kd_pelanggan,3),a.nama_karyawan)=b.kode 		
        where `tipe_transaksi`='PENJUALAN' and a.signature = '$signature'
        and year(a.tgl_order) = $tahun and month(a.tgl_order) = $bulan AND b.site = '$site_code' and discountp != 100

        union all

        select 		
        '07' as KDOKJDI,		
        Kd_Penjualan as NODOKJDI,		
        Kd_Penjualan as NODOKACU,		
        Tgl_Order as TGLDOKJDI,		
        b.kode_cust_mpm as KODESALES,		
        'BKM' as KODE_COMP,		
        'MKS' as KODE_KOTA,		
        CASE 		
            WHEN Principal_Channel LIKE'PEDAGANG BESAR CONSUMER%'THEN 'PDC'	
            WHEN Principal_Channel LIKE'PEDAGANG BESAR FARMASI%'THEN 'PBF'	
            WHEN Principal_Channel LIKE'Minimarket%'THEN 'MML'	
            WHEN Principal_Channel LIKE'Supermarket%'THEN 'SMR'	
            WHEN Principal_Channel LIKE'Warung%'THEN 'WRG'	
            WHEN Principal_Channel LIKE'Toko Kelontong%'THEN 'TKL'	
            WHEN Principal_Channel LIKE'Apotik%'THEN 'APT'	
            WHEN Principal_Channel LIKE'Hotel%'THEN 'HRK'	
            WHEN Principal_Channel LIKE'Trading %'THEN 'TRD'	
            ELSE	
                'OTH'
        END AS KODE_TYPE,		
        CONCAT(1,RIGHT(Kd_Pelanggan,5)) as KODE_LANG,		
        '' as KODERAYON,		
        d.kodeprod as KODEPROD,		
        a.SUPP,		
        RIGHT(Tgl_Order,2) as HRDOK,		
        '$bulan' as BLNDOK,		
        LEFT(Tgl_Order,4) as THNDOK,		
        c.NAMAPROD as NAMAPROD,		
        c.GRUPPROD as GROUPPROD,		
        '0' as BANYAK,		
        '0' as HARGA,		
        '' as POTONGAN,		
        '0' as TOT1,		
        '' as JUM_PROMO, 		
        if(total=0 and `qty_pcs`<>0,concat(d.kodeprod,' ',`qty_pcs`*d.satuan),'')as KETERANGAN,  		 		
        '' as USER_ISI,		
        '' as JAM_ISI,		
        '' as TGL_ISI, 		
        '' as USER_EDIT, 		
        '' as JAM_EDIT, 		
        '' as TGL_EDIT,		
        '' as USER_DEL, 		
        '' as JAM_DEL, 		
        '' as TGL_DEL, 		
        '' as NO, 		
        '' as BACKUP, 		
        '' as NO_URUT, 		
        'PST' as KODE_GDG, 		
        '' as NAMA_GDG, 		
        CASE 		
        WHEN Segment LIKE 'SO%' THEN 'WS'		
        WHEN Segment LIKE '%MARKET%' THEN 'WS'		
        WHEN Segment LIKE '%CAFES%' THEN 'SWS'		
        WHEN Segment LIKE '%FOOD%' THEN 'SWS'		
        WHEN Segment LIKE '%STORE%' THEN 'SWS'		
        ELSE 'RT'		
        END AS KODESALUR,		
        '' as KODEBONUS,		
        '' as NAMABONUS, 		
        '' as GRUPBONUS, 		
        `qty_pcs` as UNITBONUS, 		
        Nama_Karyawan as LAMPIRAN, 		
        '' as H_BELI, 		
        '' as KODEAREA, 		
        ALAMAT as NAMAAREA, 		
        '' as PINJAM, 		
        '' as JUALBANYAK, 		
        '' as JUALPINJAM, 		
        '' as HARGA_EXCL, 		
        '' as TOT1_EXCL, 		
        Nama_Pelanggan as NAMA_LANG,		
        'V5' as NOCAB,		
        '$bulan' as BULAN,		
        ' ' as siteid,		
        ' ' as qty1,		
        ' ' as qty2,		
        ' ' as qty3,		
        `qty_pcs` as qty_bonus,		
        '1' as flag_bonus,		
        ' ' as disc_persen,		
        ' ' as disc_rp,		
        ' ' as disc_value,		
        discounttd as disc_cabang,		
        discountp as disc_prinsipal,		
        ' ' as disc_xtra,		
        ' ' as rp_cabang,		
        ' ' as rp_prinsipal,		
        ' ' as rp_xtra,		
        ' ' as bonus,		
        concat(11,a.supp) as prinsipalid,		
        ' ' as ex_no_sales,		
        ' ' as status_retur,		
        ' ' as ref,		
        ' ' as term_payment,		
        ' ' as tipe_kl		
                
        from management_raw.inner_raw_vbt_bulukumba a		
        INNER JOIN (
            SELECT * FROM pmu.mapping where nocab='V'
            GROUP BY kodeprod
            )d on IF(a.S_Kode=0,a.NAMA_BARANG,a.S_Kode)=d.`kodeprod` or IF(a.S_Kode is NULL,a.NAMA_BARANG,a.S_Kode)=d.`kodeprod` 	
            INNER JOIN mpm.tabprod c on d.kodeprod=c.KODEPROD 
            INNER JOIN management_raw.raw_customer_vbt b on concat(left(a.kd_pelanggan,3),a.nama_karyawan)=b.kode 		
        where `tipe_transaksi`='PENJUALAN' and a.signature = '$signature'
        and year(a.tgl_order) = $tahun and month(a.tgl_order) = $bulan AND b.site = '$site_code' and discountp = 100
        order by harga	
        ";

        // echo "<pre>";
        // print_r($insert);
        // echo "</pre>";

        return $this->db->query($insert);
    }

    public function insert_ri_vbt_bulukumba($site_code, $signature){
        $get_tahun = "
            select * 
            from management_raw.log_upload a 
            where a.site_code = '$site_code' and a.signature = '$signature'
        ";
        $tahun = $this->db->query($get_tahun)->row()->tahun;
        $bulan = $this->db->query($get_tahun)->row()->bulan;

        $insert = "
        insert data$tahun.ri
        select 		
        '07' as KDOKJDI,		
        Kd_Penjualan as NODOKJDI,		
        Kd_Penjualan as NODOKACU,		
        Tgl_Order as TGLDOKJDI,		
        b.kode_cust_mpm as KODESALES,		
        'BKM' as KODE_COMP,		
        'MKS' as KODE_KOTA,		
        CASE 		
            WHEN Principal_Channel LIKE'PEDAGANG BESAR CONSUMER%'THEN 'PDC'	
            WHEN Principal_Channel LIKE'PEDAGANG BESAR FARMASI%'THEN 'PBF'	
            WHEN Principal_Channel LIKE'Minimarket%'THEN 'MML'	
            WHEN Principal_Channel LIKE'Supermarket%'THEN 'SMR'	
            WHEN Principal_Channel LIKE'Warung%'THEN 'WRG'	
            WHEN Principal_Channel LIKE'Toko Kelontong%'THEN 'TKL'	
            WHEN Principal_Channel LIKE'Apotik%'THEN 'APT'	
            WHEN Principal_Channel LIKE'Hotel%'THEN 'HRK'	
            WHEN Principal_Channel LIKE'Trading %'THEN 'TRD'	
            ELSE	
                'OTH'
        END AS KODE_TYPE,		
        CONCAT(1,RIGHT(Kd_Pelanggan,5)) as KODE_LANG,		
        '' as KODERAYON,		
        d.kodeprod as KODEPROD,		
        a.SUPP,		
        RIGHT(Tgl_Order,2) as HRDOK,		
        '$bulan' as BLNDOK,		
        LEFT(Tgl_Order,4) as THNDOK,		
        c.NAMAPROD as NAMAPROD,		
        c.GRUPPROD as GROUPPROD,		
        IF(TOTAL='0','0',`qty_pcs`*d.satuan) as BANYAK,		
        IF(TOTAL='0',0,ROUND(TOTAL/(`qty_pcs`*d.satuan),2)) as HARGA,		
        '' as POTONGAN,		
        round(Total,2) as TOT1,		
        '' as JUM_PROMO, 		
        if(total=0 and `qty_pcs`<>0,concat(d.kodeprod,' ',`qty_pcs`*d.satuan),'')as KETERANGAN,  		  		
        '' as USER_ISI,		
        '' as JAM_ISI,		
        '' as TGL_ISI, 		
        '' as USER_EDIT, 		
        '' as JAM_EDIT, 		
        '' as TGL_EDIT,		
        '' as USER_DEL, 		
        '' as JAM_DEL, 		
        '' as TGL_DEL, 		
        '' as NO, 		
        '' as BACKUP, 		
        '' as NO_URUT, 		
        'PST' as KODE_GDG, 		
        '' as NAMA_GDG, 		
        CASE 		
        WHEN Segment LIKE 'SO%' THEN 'WS'		
        WHEN Segment LIKE '%MARKET%' THEN 'WS'		
        WHEN Segment LIKE '%CAFES%' THEN 'SWS'		
        WHEN Segment LIKE '%FOOD%' THEN 'SWS'		
        WHEN Segment LIKE '%STORE%' THEN 'SWS'		
        ELSE 'RT'		
        END AS KODESALUR,		
        '' as KODEBONUS,		
        '' as NAMABONUS, 		
        '' as GRUPBONUS, 		
        '0' as UNITBONUS, 		
        Nama_Karyawan as LAMPIRAN, 		
        '' as H_BELI, 		
        '' as KODEAREA, 		
        ALAMAT as NAMAAREA, 		
        '' as PINJAM, 		
        '' as JUALBANYAK, 		
        '' as JUALPINJAM, 		
        '' as HARGA_EXCL, 				
        Nama_Pelanggan as NAMA_LANG,		
        'V5' as NOCAB,		
        '$bulan' as BULAN,		
        ' ' as siteid,		
        ' ' as qty1,		
        ' ' as qty2,		
        ' ' as qty3,		
        '0' as qty_bonus,		
        '0' as flag_bonus,		
        ' ' as disc_persen,		
        ' ' as disc_rp,		
        ' ' as disc_value,		
        discounttd as disc_cabang,		
        discountp as disc_prinsipal,	
        ' ' as disc_xtra,		
        ' ' as rp_cabang,		
        ' ' as rp_prinsipal,		
        ' ' as rp_xtra,		
        ' ' as bonus,		
        ' ' as prinsipalid,		
        ' ' as ex_no_sales,		
        ' ' as status_retur,		
        ' ' as ref,		
        ' ' as term_payment,		
        ' ' as tipe_kl				
                
        from management_raw.inner_raw_vbt_bulukumba a		
        INNER JOIN (
            SELECT * FROM pmu.mapping where nocab='V'
            GROUP BY kodeprod
            )d on IF(a.S_Kode=0,a.NAMA_BARANG,a.S_Kode)=d.`kodeprod` or IF(a.S_Kode is NULL,a.NAMA_BARANG,a.S_Kode)=d.`kodeprod` 	
            INNER JOIN mpm.tabprod c on d.kodeprod=c.KODEPROD 
            INNER JOIN management_raw.raw_customer_vbt b on concat(left(a.kd_pelanggan,3),a.nama_karyawan)=b.kode 		
        where `tipe_transaksi`='RETUR_PENJUALAN' and a.signature = '$signature'
        and year(a.tgl_order) = $tahun and month(a.tgl_order) = $bulan AND b.site = '$site_code' and discountp != 100

        union all

        select 		
        '07' as KDOKJDI,		
        Kd_Penjualan as NODOKJDI,		
        Kd_Penjualan as NODOKACU,		
        Tgl_Order as TGLDOKJDI,		
        b.kode_cust_mpm as KODESALES,		
        'BKM' as KODE_COMP,		
        'MKS' as KODE_KOTA,		
        CASE 		
            WHEN Principal_Channel LIKE'PEDAGANG BESAR CONSUMER%'THEN 'PDC'	
            WHEN Principal_Channel LIKE'PEDAGANG BESAR FARMASI%'THEN 'PBF'	
            WHEN Principal_Channel LIKE'Minimarket%'THEN 'MML'	
            WHEN Principal_Channel LIKE'Supermarket%'THEN 'SMR'	
            WHEN Principal_Channel LIKE'Warung%'THEN 'WRG'	
            WHEN Principal_Channel LIKE'Toko Kelontong%'THEN 'TKL'	
            WHEN Principal_Channel LIKE'Apotik%'THEN 'APT'	
            WHEN Principal_Channel LIKE'Hotel%'THEN 'HRK'	
            WHEN Principal_Channel LIKE'Trading %'THEN 'TRD'	
            ELSE	
                'OTH'
        END AS KODE_TYPE,		
        CONCAT(1,RIGHT(Kd_Pelanggan,5)) as KODE_LANG,		
        '' as KODERAYON,		
        d.kodeprod as KODEPROD,		
        a.SUPP,		
        RIGHT(Tgl_Order,2) as HRDOK,		
        '$bulan' as BLNDOK,		
        LEFT(Tgl_Order,4) as THNDOK,		
        c.NAMAPROD as NAMAPROD,		
        c.GRUPPROD as GROUPPROD,		
        '0' as BANYAK,		
        '0' as HARGA,		
        '' as POTONGAN,		
        '0' as TOT1,		
        '' as JUM_PROMO, 		
        if(total=0 and `qty_pcs`<>0,concat(d.kodeprod,' ',`qty_pcs`*d.satuan),'')as KETERANGAN,  		  		
        '' as USER_ISI,		
        '' as JAM_ISI,		
        '' as TGL_ISI, 		
        '' as USER_EDIT, 		
        '' as JAM_EDIT, 		
        '' as TGL_EDIT,		
        '' as USER_DEL, 		
        '' as JAM_DEL, 		
        '' as TGL_DEL, 		
        '' as NO, 		
        '' as BACKUP, 		
        '' as NO_URUT, 		
        'PST' as KODE_GDG, 		
        '' as NAMA_GDG, 		
        CASE 		
        WHEN Segment LIKE 'SO%' THEN 'WS'		
        WHEN Segment LIKE '%MARKET%' THEN 'WS'		
        WHEN Segment LIKE '%CAFES%' THEN 'SWS'		
        WHEN Segment LIKE '%FOOD%' THEN 'SWS'		
        WHEN Segment LIKE '%STORE%' THEN 'SWS'		
        ELSE 'RT'		
        END AS KODESALUR,		
        '' as KODEBONUS,		
        '' as NAMABONUS, 		
        '' as GRUPBONUS, 		
        `qty_pcs` as UNITBONUS, 		
        Nama_Karyawan as LAMPIRAN, 		
        '' as H_BELI, 		
        '' as KODEAREA, 		
        ALAMAT as NAMAAREA, 		
        '' as PINJAM, 		
        '' as JUALBANYAK, 		
        '' as JUALPINJAM, 		
        '' as HARGA_EXCL, 				
        Nama_Pelanggan as NAMA_LANG,		
        'V5' as NOCAB,		
        '$bulan' as BULAN,		
        ' ' as siteid,		
        ' ' as qty1,		
        ' ' as qty2,		
        ' ' as qty3,		
        `qty_pcs` as qty_bonus,		
        '1' as flag_bonus,		
        ' ' as disc_persen,		
        ' ' as disc_rp,		
        ' ' as disc_value,		
        discounttd as disc_cabang,		
        discountp as disc_prinsipal,	
        ' ' as disc_xtra,		
        ' ' as rp_cabang,		
        ' ' as rp_prinsipal,		
        ' ' as rp_xtra,		
        ' ' as bonus,		
        ' ' as prinsipalid,		
        ' ' as ex_no_sales,		
        ' ' as status_retur,		
        ' ' as ref,		
        ' ' as term_payment,		
        ' ' as tipe_kl				
                
        from management_raw.inner_raw_vbt_bulukumba a		
        INNER JOIN (
            SELECT * FROM pmu.mapping where nocab='V'
            GROUP BY kodeprod
            )d on IF(a.S_Kode=0,a.NAMA_BARANG,a.S_Kode)=d.`kodeprod` or IF(a.S_Kode is NULL,a.NAMA_BARANG,a.S_Kode)=d.`kodeprod` 	
            INNER JOIN mpm.tabprod c on d.kodeprod=c.KODEPROD 
            INNER JOIN management_raw.raw_customer_vbt b on concat(left(a.kd_pelanggan,3),a.nama_karyawan)=b.kode 		
        where `tipe_transaksi`='RETUR_PENJUALAN' and a.signature = '$signature'
        and year(a.tgl_order) = $tahun and month(a.tgl_order) = $bulan AND b.site = '$site_code' and discountp = 100
        order by harga
        ";

        // echo "<pre>";
        // print_r($insert);
        // echo "</pre>";

        // die;

        return $this->db->query($insert);
    }

    public function insert_tbkota_vbt_bulukumba($site_code, $signature){
        $get_tahun = "
            select * 
            from management_raw.log_upload a 
            where a.site_code = '$site_code' and a.signature = '$signature'
        ";
        $tahun = $this->db->query($get_tahun)->row()->tahun;
        $bulan = $this->db->query($get_tahun)->row()->bulan;

        $insert = "
            insert into data$tahun.tbkota 
            select 			
            KODE_COMP AS KODE_COMP, 		
            'BKM' AS KODE_KOTA,		
            'BULUKUMBA' AS NAMA_KOTA,		
            NOCAB			
            from data$tahun.fi 
            where concat(kode_comp, nocab) = '$site_code' 
            group by KODE_COMP,KODE_KOTA
        ";

        // echo "<pre>";
        // print_r($insert);
        // echo "</pre>";

        // die;

        return $this->db->query($insert);
    }

    public function insert_fi_vbt_bone($site_code, $signature){
        $get_tahun = "
            select * 
            from management_raw.log_upload a 
            where a.site_code = '$site_code' and a.signature = '$signature'
        ";
        $tahun = $this->db->query($get_tahun)->row()->tahun;
        $bulan = $this->db->query($get_tahun)->row()->bulan;


        $insert = "
        insert data$tahun.fi
        select 		
        '07' as KDOKJDI,		
        Kd_Penjualan as NODOKJDI,		
        Kd_Penjualan as NODOKACU,		
        Tgl_Order as TGLDOKJDI,		
        b.kode_cust_mpm as KODESALES,		
        'BON' as KODE_COMP,		
        'MKS' as KODE_KOTA,		
        CASE 		
            WHEN Principal_Channel LIKE'PEDAGANG BESAR CONSUMER%'THEN 'PDC'	
            WHEN Principal_Channel LIKE'PEDAGANG BESAR FARMASI%'THEN 'PBF'	
            WHEN Principal_Channel LIKE'Minimarket%'THEN 'MML'	
            WHEN Principal_Channel LIKE'Supermarket%'THEN 'SMR'	
            WHEN Principal_Channel LIKE'Warung%'THEN 'WRG'	
            WHEN Principal_Channel LIKE'Toko Kelontong%'THEN 'TKL'	
            WHEN Principal_Channel LIKE'Apotik%'THEN 'APT'	
            WHEN Principal_Channel LIKE'Hotel%'THEN 'HRK'	
            WHEN Principal_Channel LIKE'Trading %'THEN 'TRD'	
            ELSE	
                'OTH'
        END AS KODE_TYPE,		
        CONCAT(1,RIGHT(Kd_Pelanggan,5)) as KODE_LANG,		
        '' as KODERAYON,		
        d.kodeprod as KODEPROD,		
        a.SUPP,		
        RIGHT(Tgl_Order,2) as HRDOK,		
        '$bulan' as BLNDOK,		
        LEFT(Tgl_Order,4) as THNDOK,		
        c.NAMAPROD as NAMAPROD,		
        c.GRUPPROD as GROUPPROD,		
        IF(TOTAL='0','0',`qty_pcs`*d.satuan) as BANYAK,		
        ROUND(TOTAL/(`qty_pcs`*d.satuan),2) as HARGA,		
        '' as POTONGAN,		
        round(Total,2) as TOT1,		
        '' as JUM_PROMO, 		
        if(total=0 and `qty_pcs`<>0,concat(d.kodeprod,' ',`qty_pcs`*d.satuan),'')as KETERANGAN,  		 		
        '' as USER_ISI,		
        '' as JAM_ISI,		
        '' as TGL_ISI, 		
        '' as USER_EDIT, 		
        '' as JAM_EDIT, 		
        '' as TGL_EDIT,		
        '' as USER_DEL, 		
        '' as JAM_DEL, 		
        '' as TGL_DEL, 		
        '' as NO, 		
        '' as BACKUP, 		
        '' as NO_URUT, 		
        'PST' as KODE_GDG, 		
        '' as NAMA_GDG, 		
        CASE 		
        WHEN Segment LIKE 'SO%' THEN 'WS'		
        WHEN Segment LIKE '%MARKET%' THEN 'WS'		
        WHEN Segment LIKE '%CAFES%' THEN 'SWS'		
        WHEN Segment LIKE '%FOOD%' THEN 'SWS'		
        WHEN Segment LIKE '%STORE%' THEN 'SWS'		
        ELSE 'RT'		
        END AS KODESALUR,		
        '' as KODEBONUS,		
        '' as NAMABONUS, 		
        '' as GRUPBONUS, 		
        '0' as UNITBONUS, 	
        Nama_Karyawan as LAMPIRAN, 		
        '' as H_BELI, 		
        '' as KODEAREA, 		
        ALAMAT as NAMAAREA, 		
        '' as PINJAM, 		
        '' as JUALBANYAK, 		
        '' as JUALPINJAM, 		
        '' as HARGA_EXCL, 		
        '' as TOT1_EXCL, 		
        Nama_Pelanggan as NAMA_LANG,		
        'V4' as NOCAB,		
        '$bulan' as BULAN,		
        ' ' as siteid,		
        ' ' as qty1,		
        ' ' as qty2,		
        ' ' as qty3,		
        '0' as qty_bonus,		
        '0' as flag_bonus,		
        ' ' as disc_persen,		
        ' ' as disc_rp,		
        ' ' as disc_value,		
        discounttd as disc_cabang,		
        discountp as disc_prinsipal,		
        ' ' as disc_xtra,		
        ' ' as rp_cabang,		
        ' ' as rp_prinsipal,		
        ' ' as rp_xtra,		
        ' ' as bonus,		
        concat(11,a.supp) as prinsipalid,		
        ' ' as ex_no_sales,		
        ' ' as status_retur,		
        ' ' as ref,		
        ' ' as term_payment,		
        ' ' as tipe_kl		
                
        from management_raw.inner_raw_vbt_bone a		
        INNER JOIN (
            SELECT * FROM pmu.mapping where nocab='V'
            GROUP BY kodeprod
            )d on IF(a.S_Kode=0,a.NAMA_BARANG,a.S_Kode)=d.`kodeprod` or IF(a.S_Kode is NULL,a.NAMA_BARANG,a.S_Kode)=d.`kodeprod` 	
            INNER JOIN mpm.tabprod c on d.kodeprod=c.KODEPROD 
            INNER JOIN management_raw.raw_customer_vbt b on concat(left(a.kd_pelanggan,3),a.nama_karyawan)=b.kode 		
        where `tipe_transaksi`='PENJUALAN' and a.signature = '$signature'
        and year(a.tgl_order) = $tahun and month(a.tgl_order) = $bulan AND b.site = '$site_code' and discountp != 100

        union all

        select 		
        '07' as KDOKJDI,		
        Kd_Penjualan as NODOKJDI,		
        Kd_Penjualan as NODOKACU,		
        Tgl_Order as TGLDOKJDI,		
        b.kode_cust_mpm as KODESALES,		
        'BON' as KODE_COMP,		
        'MKS' as KODE_KOTA,		
        CASE 		
            WHEN Principal_Channel LIKE'PEDAGANG BESAR CONSUMER%'THEN 'PDC'	
            WHEN Principal_Channel LIKE'PEDAGANG BESAR FARMASI%'THEN 'PBF'	
            WHEN Principal_Channel LIKE'Minimarket%'THEN 'MML'	
            WHEN Principal_Channel LIKE'Supermarket%'THEN 'SMR'	
            WHEN Principal_Channel LIKE'Warung%'THEN 'WRG'	
            WHEN Principal_Channel LIKE'Toko Kelontong%'THEN 'TKL'	
            WHEN Principal_Channel LIKE'Apotik%'THEN 'APT'	
            WHEN Principal_Channel LIKE'Hotel%'THEN 'HRK'	
            WHEN Principal_Channel LIKE'Trading %'THEN 'TRD'	
            ELSE	
                'OTH'
        END AS KODE_TYPE,		
        CONCAT(1,RIGHT(Kd_Pelanggan,5)) as KODE_LANG,		
        '' as KODERAYON,		
        d.kodeprod as KODEPROD,		
        a.SUPP,		
        RIGHT(Tgl_Order,2) as HRDOK,		
        '$bulan' as BLNDOK,		
        LEFT(Tgl_Order,4) as THNDOK,		
        c.NAMAPROD as NAMAPROD,		
        c.GRUPPROD as GROUPPROD,		
        '0' as BANYAK,		
        '0' as HARGA,		
        '' as POTONGAN,		
        '0' as TOT1,		
        '' as JUM_PROMO, 		
        if(total=0 and `qty_pcs`<>0,concat(d.kodeprod,' ',`qty_pcs`*d.satuan),'')as KETERANGAN,  		 		
        '' as USER_ISI,		
        '' as JAM_ISI,		
        '' as TGL_ISI, 		
        '' as USER_EDIT, 		
        '' as JAM_EDIT, 		
        '' as TGL_EDIT,		
        '' as USER_DEL, 		
        '' as JAM_DEL, 		
        '' as TGL_DEL, 		
        '' as NO, 		
        '' as BACKUP, 		
        '' as NO_URUT, 		
        'PST' as KODE_GDG, 		
        '' as NAMA_GDG, 		
        CASE 		
        WHEN Segment LIKE 'SO%' THEN 'WS'		
        WHEN Segment LIKE '%MARKET%' THEN 'WS'		
        WHEN Segment LIKE '%CAFES%' THEN 'SWS'		
        WHEN Segment LIKE '%FOOD%' THEN 'SWS'		
        WHEN Segment LIKE '%STORE%' THEN 'SWS'		
        ELSE 'RT'		
        END AS KODESALUR,		
        '' as KODEBONUS,		
        '' as NAMABONUS, 		
        '' as GRUPBONUS, 		
        `qty_pcs` as UNITBONUS, 	
        Nama_Karyawan as LAMPIRAN, 		
        '' as H_BELI, 		
        '' as KODEAREA, 		
        ALAMAT as NAMAAREA, 		
        '' as PINJAM, 		
        '' as JUALBANYAK, 		
        '' as JUALPINJAM, 		
        '' as HARGA_EXCL, 		
        '' as TOT1_EXCL, 		
        Nama_Pelanggan as NAMA_LANG,		
        'V4' as NOCAB,		
        '$bulan' as BULAN,		
        ' ' as siteid,		
        ' ' as qty1,		
        ' ' as qty2,		
        ' ' as qty3,		
        `qty_pcs` as qty_bonus,		
        '1' as flag_bonus,		
        ' ' as disc_persen,		
        ' ' as disc_rp,		
        ' ' as disc_value,		
        discounttd as disc_cabang,		
        discountp as disc_prinsipal,		
        ' ' as disc_xtra,		
        ' ' as rp_cabang,		
        ' ' as rp_prinsipal,		
        ' ' as rp_xtra,		
        ' ' as bonus,		
        concat(11,a.supp) as prinsipalid,		
        ' ' as ex_no_sales,		
        ' ' as status_retur,		
        ' ' as ref,		
        ' ' as term_payment,		
        ' ' as tipe_kl		
                
        from management_raw.inner_raw_vbt_bone a		
        INNER JOIN (
            SELECT * FROM pmu.mapping where nocab='V'
            GROUP BY kodeprod
            )d on IF(a.S_Kode=0,a.NAMA_BARANG,a.S_Kode)=d.`kodeprod` or IF(a.S_Kode is NULL,a.NAMA_BARANG,a.S_Kode)=d.`kodeprod` 	
            INNER JOIN mpm.tabprod c on d.kodeprod=c.KODEPROD 
            INNER JOIN management_raw.raw_customer_vbt b on concat(left(a.kd_pelanggan,3),a.nama_karyawan)=b.kode 		
        where `tipe_transaksi`='PENJUALAN' and a.signature = '$signature'
        and year(a.tgl_order) = $tahun and month(a.tgl_order) = $bulan AND b.site = '$site_code' and discountp = 100
        order by harga	
        ";

        // echo "<pre>";
        // print_r($insert);
        // echo "</pre>";

        return $this->db->query($insert);
    }

    public function insert_ri_vbt_bone($site_code, $signature){
        $get_tahun = "
            select * 
            from management_raw.log_upload a 
            where a.site_code = '$site_code' and a.signature = '$signature'
        ";
        $tahun = $this->db->query($get_tahun)->row()->tahun;
        $bulan = $this->db->query($get_tahun)->row()->bulan;

        $insert = "
        insert data$tahun.ri
        select 		
        '07' as KDOKJDI,		
        Kd_Penjualan as NODOKJDI,		
        Kd_Penjualan as NODOKACU,		
        Tgl_Order as TGLDOKJDI,		
        b.kode_cust_mpm as KODESALES,		
        'BON' as KODE_COMP,		
        'MKS' as KODE_KOTA,		
        CASE 		
            WHEN Principal_Channel LIKE'PEDAGANG BESAR CONSUMER%'THEN 'PDC'	
            WHEN Principal_Channel LIKE'PEDAGANG BESAR FARMASI%'THEN 'PBF'	
            WHEN Principal_Channel LIKE'Minimarket%'THEN 'MML'	
            WHEN Principal_Channel LIKE'Supermarket%'THEN 'SMR'	
            WHEN Principal_Channel LIKE'Warung%'THEN 'WRG'	
            WHEN Principal_Channel LIKE'Toko Kelontong%'THEN 'TKL'	
            WHEN Principal_Channel LIKE'Apotik%'THEN 'APT'	
            WHEN Principal_Channel LIKE'Hotel%'THEN 'HRK'	
            WHEN Principal_Channel LIKE'Trading %'THEN 'TRD'	
            ELSE	
                'OTH'
        END AS KODE_TYPE,		
        CONCAT(1,RIGHT(Kd_Pelanggan,5)) as KODE_LANG,		
        '' as KODERAYON,		
        d.kodeprod as KODEPROD,		
        a.SUPP,		
        RIGHT(Tgl_Order,2) as HRDOK,		
        '$bulan' as BLNDOK,		
        LEFT(Tgl_Order,4) as THNDOK,		
        c.NAMAPROD as NAMAPROD,		
        c.GRUPPROD as GROUPPROD,		
        IF(TOTAL='0','0',`qty_pcs`*d.satuan) as BANYAK,		
        IF(TOTAL='0',0,ROUND(TOTAL/(`qty_pcs`*d.satuan),2)) as HARGA,		
        '' as POTONGAN,		
        round(Total,2) as TOT1,		
        '' as JUM_PROMO, 		
        if(total=0 and `qty_pcs`<>0,concat(d.kodeprod,' ',`qty_pcs`*d.satuan),'')as KETERANGAN,  		  		
        '' as USER_ISI,		
        '' as JAM_ISI,		
        '' as TGL_ISI, 		
        '' as USER_EDIT, 		
        '' as JAM_EDIT, 		
        '' as TGL_EDIT,		
        '' as USER_DEL, 		
        '' as JAM_DEL, 		
        '' as TGL_DEL, 		
        '' as NO, 		
        '' as BACKUP, 		
        '' as NO_URUT, 		
        'PST' as KODE_GDG, 		
        '' as NAMA_GDG, 		
        CASE 		
        WHEN Segment LIKE 'SO%' THEN 'WS'		
        WHEN Segment LIKE '%MARKET%' THEN 'WS'		
        WHEN Segment LIKE '%CAFES%' THEN 'SWS'		
        WHEN Segment LIKE '%FOOD%' THEN 'SWS'		
        WHEN Segment LIKE '%STORE%' THEN 'SWS'		
        ELSE 'RT'		
        END AS KODESALUR,		
        '' as KODEBONUS,		
        '' as NAMABONUS, 		
        '' as GRUPBONUS, 		
        '0' as UNITBONUS, 		
        Nama_Karyawan as LAMPIRAN, 		
        '' as H_BELI, 		
        '' as KODEAREA, 		
        ALAMAT as NAMAAREA, 		
        '' as PINJAM, 		
        '' as JUALBANYAK, 		
        '' as JUALPINJAM, 		
        '' as HARGA_EXCL, 				
        Nama_Pelanggan as NAMA_LANG,		
        'V4' as NOCAB,		
        '$bulan' as BULAN,		
        ' ' as siteid,		
        ' ' as qty1,		
        ' ' as qty2,		
        ' ' as qty3,		
        '0' as qty_bonus,		
        '0' as flag_bonus,		
        ' ' as disc_persen,		
        ' ' as disc_rp,		
        ' ' as disc_value,		
        discounttd as disc_cabang,		
        discountp as disc_prinsipal,	
        ' ' as disc_xtra,		
        ' ' as rp_cabang,		
        ' ' as rp_prinsipal,		
        ' ' as rp_xtra,		
        ' ' as bonus,		
        ' ' as prinsipalid,		
        ' ' as ex_no_sales,		
        ' ' as status_retur,		
        ' ' as ref,		
        ' ' as term_payment,		
        ' ' as tipe_kl				
                
        from management_raw.inner_raw_vbt_bone a		
        INNER JOIN (
            SELECT * FROM pmu.mapping where nocab='V'
            GROUP BY kodeprod
            )d on IF(a.S_Kode=0,a.NAMA_BARANG,a.S_Kode)=d.`kodeprod` or IF(a.S_Kode is NULL,a.NAMA_BARANG,a.S_Kode)=d.`kodeprod` 	
            INNER JOIN mpm.tabprod c on d.kodeprod=c.KODEPROD 
            INNER JOIN management_raw.raw_customer_vbt b on concat(left(a.kd_pelanggan,3),a.nama_karyawan)=b.kode 		
        where `tipe_transaksi`='RETUR_PENJUALAN' and a.signature = '$signature'
        and year(a.tgl_order) = $tahun and month(a.tgl_order) = $bulan AND b.site = '$site_code' and discountp != 100

        union all

        select 		
        '07' as KDOKJDI,		
        Kd_Penjualan as NODOKJDI,		
        Kd_Penjualan as NODOKACU,		
        Tgl_Order as TGLDOKJDI,		
        b.kode_cust_mpm as KODESALES,		
        'BON' as KODE_COMP,		
        'MKS' as KODE_KOTA,		
        CASE 		
            WHEN Principal_Channel LIKE'PEDAGANG BESAR CONSUMER%'THEN 'PDC'	
            WHEN Principal_Channel LIKE'PEDAGANG BESAR FARMASI%'THEN 'PBF'	
            WHEN Principal_Channel LIKE'Minimarket%'THEN 'MML'	
            WHEN Principal_Channel LIKE'Supermarket%'THEN 'SMR'	
            WHEN Principal_Channel LIKE'Warung%'THEN 'WRG'	
            WHEN Principal_Channel LIKE'Toko Kelontong%'THEN 'TKL'	
            WHEN Principal_Channel LIKE'Apotik%'THEN 'APT'	
            WHEN Principal_Channel LIKE'Hotel%'THEN 'HRK'	
            WHEN Principal_Channel LIKE'Trading %'THEN 'TRD'	
            ELSE	
                'OTH'
        END AS KODE_TYPE,		
        CONCAT(1,RIGHT(Kd_Pelanggan,5)) as KODE_LANG,		
        '' as KODERAYON,		
        d.kodeprod as KODEPROD,		
        a.SUPP,		
        RIGHT(Tgl_Order,2) as HRDOK,		
        '$bulan' as BLNDOK,		
        LEFT(Tgl_Order,4) as THNDOK,		
        c.NAMAPROD as NAMAPROD,		
        c.GRUPPROD as GROUPPROD,		
        '0' as BANYAK,		
        '0' as HARGA,		
        '' as POTONGAN,		
        '0' as TOT1,		
        '' as JUM_PROMO, 		
        if(total=0 and `qty_pcs`<>0,concat(d.kodeprod,' ',`qty_pcs`*d.satuan),'')as KETERANGAN,  		  		
        '' as USER_ISI,		
        '' as JAM_ISI,		
        '' as TGL_ISI, 		
        '' as USER_EDIT, 		
        '' as JAM_EDIT, 		
        '' as TGL_EDIT,		
        '' as USER_DEL, 		
        '' as JAM_DEL, 		
        '' as TGL_DEL, 		
        '' as NO, 		
        '' as BACKUP, 		
        '' as NO_URUT, 		
        'PST' as KODE_GDG, 		
        '' as NAMA_GDG, 		
        CASE 		
        WHEN Segment LIKE 'SO%' THEN 'WS'		
        WHEN Segment LIKE '%MARKET%' THEN 'WS'		
        WHEN Segment LIKE '%CAFES%' THEN 'SWS'		
        WHEN Segment LIKE '%FOOD%' THEN 'SWS'		
        WHEN Segment LIKE '%STORE%' THEN 'SWS'		
        ELSE 'RT'		
        END AS KODESALUR,		
        '' as KODEBONUS,		
        '' as NAMABONUS, 		
        '' as GRUPBONUS, 		
        `qty_pcs` as UNITBONUS, 		
        Nama_Karyawan as LAMPIRAN, 		
        '' as H_BELI, 		
        '' as KODEAREA, 		
        ALAMAT as NAMAAREA, 		
        '' as PINJAM, 		
        '' as JUALBANYAK, 		
        '' as JUALPINJAM, 		
        '' as HARGA_EXCL, 				
        Nama_Pelanggan as NAMA_LANG,		
        'V4' as NOCAB,		
        '$bulan' as BULAN,		
        ' ' as siteid,		
        ' ' as qty1,		
        ' ' as qty2,		
        ' ' as qty3,		
        `qty_pcs` as qty_bonus,		
        '1' as flag_bonus,		
        ' ' as disc_persen,		
        ' ' as disc_rp,		
        ' ' as disc_value,		
        discounttd as disc_cabang,		
        discountp as disc_prinsipal,	
        ' ' as disc_xtra,		
        ' ' as rp_cabang,		
        ' ' as rp_prinsipal,		
        ' ' as rp_xtra,		
        ' ' as bonus,		
        ' ' as prinsipalid,		
        ' ' as ex_no_sales,		
        ' ' as status_retur,		
        ' ' as ref,		
        ' ' as term_payment,		
        ' ' as tipe_kl				
                
        from management_raw.inner_raw_vbt_bone a		
        INNER JOIN (
            SELECT * FROM pmu.mapping where nocab='V'
            GROUP BY kodeprod
            )d on IF(a.S_Kode=0,a.NAMA_BARANG,a.S_Kode)=d.`kodeprod` or IF(a.S_Kode is NULL,a.NAMA_BARANG,a.S_Kode)=d.`kodeprod` 	
            INNER JOIN mpm.tabprod c on d.kodeprod=c.KODEPROD 
            INNER JOIN management_raw.raw_customer_vbt b on concat(left(a.kd_pelanggan,3),a.nama_karyawan)=b.kode 		
        where `tipe_transaksi`='RETUR_PENJUALAN' and a.signature = '$signature'
        and year(a.tgl_order) = $tahun and month(a.tgl_order) = $bulan AND b.site = '$site_code' and discountp = 100
        order by harga
        ";

        // echo "<pre>";
        // print_r($insert);
        // echo "</pre>";

        // die;

        return $this->db->query($insert);
    }

    public function insert_tbkota_vbt_bone($site_code, $signature){
        $get_tahun = "
            select * 
            from management_raw.log_upload a 
            where a.site_code = '$site_code' and a.signature = '$signature'
        ";
        $tahun = $this->db->query($get_tahun)->row()->tahun;
        $bulan = $this->db->query($get_tahun)->row()->bulan;

        $insert = "
            insert into data$tahun.tbkota 
            select 			
            KODE_COMP AS KODE_COMP, 		
            'BON' AS KODE_KOTA,		
            'BONE' AS NAMA_KOTA,		
            NOCAB			
            from data$tahun.fi 
            where concat(kode_comp, nocab) = '$site_code' 
            group by KODE_COMP,KODE_KOTA
        ";

        // echo "<pre>";
        // print_r($insert);
        // echo "</pre>";

        // die;

        return $this->db->query($insert);
    }

    public function insert_fi_vbt_palopo($site_code, $signature){
        $get_tahun = "
            select * 
            from management_raw.log_upload a 
            where a.site_code = '$site_code' and a.signature = '$signature'
        ";
        $tahun = $this->db->query($get_tahun)->row()->tahun;
        $bulan = $this->db->query($get_tahun)->row()->bulan;


        $insert = "
        insert data$tahun.fi
        select 		
        '07' as KDOKJDI,		
        Kd_Penjualan as NODOKJDI,		
        Kd_Penjualan as NODOKACU,		
        Tgl_Order as TGLDOKJDI,		
        b.kode_cust_mpm as KODESALES,		
        'PAL' as KODE_COMP,		
        'MKS' as KODE_KOTA,		
        CASE 		
            WHEN Principal_Channel LIKE'PEDAGANG BESAR CONSUMER%'THEN 'PDC'	
            WHEN Principal_Channel LIKE'PEDAGANG BESAR FARMASI%'THEN 'PBF'	
            WHEN Principal_Channel LIKE'Minimarket%'THEN 'MML'	
            WHEN Principal_Channel LIKE'Supermarket%'THEN 'SMR'	
            WHEN Principal_Channel LIKE'Warung%'THEN 'WRG'	
            WHEN Principal_Channel LIKE'Toko Kelontong%'THEN 'TKL'	
            WHEN Principal_Channel LIKE'Apotik%'THEN 'APT'	
            WHEN Principal_Channel LIKE'Hotel%'THEN 'HRK'	
            WHEN Principal_Channel LIKE'Trading %'THEN 'TRD'	
            ELSE	
                'OTH'
        END AS KODE_TYPE,		
        CONCAT(1,RIGHT(Kd_Pelanggan,5)) as KODE_LANG,		
        '' as KODERAYON,		
        d.kodeprod as KODEPROD,		
        a.SUPP,		
        RIGHT(Tgl_Order,2) as HRDOK,		
        '$bulan' as BLNDOK,		
        LEFT(Tgl_Order,4) as THNDOK,		
        c.NAMAPROD as NAMAPROD,		
        c.GRUPPROD as GROUPPROD,		
        IF(TOTAL='0','0',`qty_pcs`*d.satuan) as BANYAK,		
        ROUND(TOTAL/(`qty_pcs`*d.satuan),2) as HARGA,		
        '' as POTONGAN,		
        round(Total,2) as TOT1,		
        '' as JUM_PROMO, 		
        if(total=0 and `qty_pcs`<>0,concat(d.kodeprod,' ',`qty_pcs`*d.satuan),'')as KETERANGAN,  		 		
        '' as USER_ISI,		
        '' as JAM_ISI,		
        '' as TGL_ISI, 		
        '' as USER_EDIT, 		
        '' as JAM_EDIT, 		
        '' as TGL_EDIT,		
        '' as USER_DEL, 		
        '' as JAM_DEL, 		
        '' as TGL_DEL, 		
        '' as NO, 		
        '' as BACKUP, 		
        '' as NO_URUT, 		
        'PST' as KODE_GDG, 		
        '' as NAMA_GDG, 		
        CASE 		
        WHEN Segment LIKE 'SO%' THEN 'WS'		
        WHEN Segment LIKE '%MARKET%' THEN 'WS'		
        WHEN Segment LIKE '%CAFES%' THEN 'SWS'		
        WHEN Segment LIKE '%FOOD%' THEN 'SWS'		
        WHEN Segment LIKE '%STORE%' THEN 'SWS'		
        ELSE 'RT'		
        END AS KODESALUR,		
        '' as KODEBONUS,		
        '' as NAMABONUS, 		
        '' as GRUPBONUS, 		
        '0' as UNITBONUS, 	
        Nama_Karyawan as LAMPIRAN, 		
        '' as H_BELI, 		
        '' as KODEAREA, 		
        ALAMAT as NAMAAREA, 		
        '' as PINJAM, 		
        '' as JUALBANYAK, 		
        '' as JUALPINJAM, 		
        '' as HARGA_EXCL, 		
        '' as TOT1_EXCL, 		
        Nama_Pelanggan as NAMA_LANG,		
        'V2' as NOCAB,		
        '$bulan' as BULAN,		
        ' ' as siteid,		
        ' ' as qty1,		
        ' ' as qty2,		
        ' ' as qty3,		
        '0' as qty_bonus,		
        '0' as flag_bonus,		
        ' ' as disc_persen,		
        ' ' as disc_rp,		
        ' ' as disc_value,		
        discounttd as disc_cabang,		
        discountp as disc_prinsipal,		
        ' ' as disc_xtra,		
        ' ' as rp_cabang,		
        ' ' as rp_prinsipal,		
        ' ' as rp_xtra,		
        ' ' as bonus,		
        concat(11,a.supp) as prinsipalid,		
        ' ' as ex_no_sales,		
        ' ' as status_retur,		
        ' ' as ref,		
        ' ' as term_payment,		
        ' ' as tipe_kl	
                
        from management_raw.inner_raw_vbt_palopo a		
        INNER JOIN (
            SELECT * FROM pmu.mapping where nocab='V'
            GROUP BY kodeprod
            )d on IF(a.S_Kode=0,a.NAMA_BARANG,a.S_Kode)=d.`kodeprod` or IF(a.S_Kode is NULL,a.NAMA_BARANG,a.S_Kode)=d.`kodeprod` 	
            INNER JOIN mpm.tabprod c on d.kodeprod=c.KODEPROD 
            INNER JOIN management_raw.raw_customer_vbt b on concat(left(a.kd_pelanggan,3),a.nama_karyawan)=b.kode 		
        where `tipe_transaksi`='PENJUALAN' and a.signature = '$signature'
        and year(a.tgl_order) = $tahun and month(a.tgl_order) = $bulan AND b.site = '$site_code' and discountp != 100

        union all 

        select 		
        '07' as KDOKJDI,		
        Kd_Penjualan as NODOKJDI,		
        Kd_Penjualan as NODOKACU,		
        Tgl_Order as TGLDOKJDI,		
        b.kode_cust_mpm as KODESALES,		
        'PAL' as KODE_COMP,		
        'MKS' as KODE_KOTA,		
        CASE 		
            WHEN Principal_Channel LIKE'PEDAGANG BESAR CONSUMER%'THEN 'PDC'	
            WHEN Principal_Channel LIKE'PEDAGANG BESAR FARMASI%'THEN 'PBF'	
            WHEN Principal_Channel LIKE'Minimarket%'THEN 'MML'	
            WHEN Principal_Channel LIKE'Supermarket%'THEN 'SMR'	
            WHEN Principal_Channel LIKE'Warung%'THEN 'WRG'	
            WHEN Principal_Channel LIKE'Toko Kelontong%'THEN 'TKL'	
            WHEN Principal_Channel LIKE'Apotik%'THEN 'APT'	
            WHEN Principal_Channel LIKE'Hotel%'THEN 'HRK'	
            WHEN Principal_Channel LIKE'Trading %'THEN 'TRD'	
            ELSE	
                'OTH'
        END AS KODE_TYPE,		
        CONCAT(1,RIGHT(Kd_Pelanggan,5)) as KODE_LANG,		
        '' as KODERAYON,		
        d.kodeprod as KODEPROD,		
        a.SUPP,		
        RIGHT(Tgl_Order,2) as HRDOK,		
        '$bulan' as BLNDOK,		
        LEFT(Tgl_Order,4) as THNDOK,		
        c.NAMAPROD as NAMAPROD,		
        c.GRUPPROD as GROUPPROD,		
        '0' as BANYAK,		
        '0' as HARGA,		
        '' as POTONGAN,		
        '0' as TOT1,		
        '' as JUM_PROMO, 		
        if(total=0 and `qty_pcs`<>0,concat(d.kodeprod,' ',`qty_pcs`*d.satuan),'')as KETERANGAN,  		 		
        '' as USER_ISI,		
        '' as JAM_ISI,		
        '' as TGL_ISI, 		
        '' as USER_EDIT, 		
        '' as JAM_EDIT, 		
        '' as TGL_EDIT,		
        '' as USER_DEL, 		
        '' as JAM_DEL, 		
        '' as TGL_DEL, 		
        '' as NO, 		
        '' as BACKUP, 		
        '' as NO_URUT, 		
        'PST' as KODE_GDG, 		
        '' as NAMA_GDG, 		
        CASE 		
        WHEN Segment LIKE 'SO%' THEN 'WS'		
        WHEN Segment LIKE '%MARKET%' THEN 'WS'		
        WHEN Segment LIKE '%CAFES%' THEN 'SWS'		
        WHEN Segment LIKE '%FOOD%' THEN 'SWS'		
        WHEN Segment LIKE '%STORE%' THEN 'SWS'		
        ELSE 'RT'		
        END AS KODESALUR,		
        '' as KODEBONUS,		
        '' as NAMABONUS, 		
        '' as GRUPBONUS, 		
        `qty_pcs` as UNITBONUS, 	
        Nama_Karyawan as LAMPIRAN, 		
        '' as H_BELI, 		
        '' as KODEAREA, 		
        ALAMAT as NAMAAREA, 		
        '' as PINJAM, 		
        '' as JUALBANYAK, 		
        '' as JUALPINJAM, 		
        '' as HARGA_EXCL, 		
        '' as TOT1_EXCL, 		
        Nama_Pelanggan as NAMA_LANG,		
        'V2' as NOCAB,		
        '$bulan' as BULAN,		
        ' ' as siteid,		
        ' ' as qty1,		
        ' ' as qty2,		
        ' ' as qty3,		
        `qty_pcs` as qty_bonus,		
        '1' as flag_bonus,		
        ' ' as disc_persen,		
        ' ' as disc_rp,		
        ' ' as disc_value,		
        discounttd as disc_cabang,		
        discountp as disc_prinsipal,		
        ' ' as disc_xtra,		
        ' ' as rp_cabang,		
        ' ' as rp_prinsipal,		
        ' ' as rp_xtra,		
        ' ' as bonus,		
        concat(11,a.supp) as prinsipalid,		
        ' ' as ex_no_sales,		
        ' ' as status_retur,		
        ' ' as ref,		
        ' ' as term_payment,		
        ' ' as tipe_kl	
                
        from management_raw.inner_raw_vbt_palopo a		
        INNER JOIN (
            SELECT * FROM pmu.mapping where nocab='V'
            GROUP BY kodeprod
            )d on IF(a.S_Kode=0,a.NAMA_BARANG,a.S_Kode)=d.`kodeprod` or IF(a.S_Kode is NULL,a.NAMA_BARANG,a.S_Kode)=d.`kodeprod` 	
            INNER JOIN mpm.tabprod c on d.kodeprod=c.KODEPROD 
            INNER JOIN management_raw.raw_customer_vbt b on concat(left(a.kd_pelanggan,3),a.nama_karyawan)=b.kode 		
        where `tipe_transaksi`='PENJUALAN' and a.signature = '$signature'
        and year(a.tgl_order) = $tahun and month(a.tgl_order) = $bulan AND b.site = '$site_code' and discountp = 100
        order by harga	
        ";

        // echo "<pre>";
        // print_r($insert);
        // echo "</pre>";

        return $this->db->query($insert);
    }

    public function insert_ri_vbt_palopo($site_code, $signature){
        $get_tahun = "
            select * 
            from management_raw.log_upload a 
            where a.site_code = '$site_code' and a.signature = '$signature'
        ";
        $tahun = $this->db->query($get_tahun)->row()->tahun;
        $bulan = $this->db->query($get_tahun)->row()->bulan;

        $insert = "
        insert data$tahun.ri
        select 		
        '07' as KDOKJDI,		
        Kd_Penjualan as NODOKJDI,		
        Kd_Penjualan as NODOKACU,		
        Tgl_Order as TGLDOKJDI,		
        b.kode_cust_mpm as KODESALES,		
        'PAL' as KODE_COMP,		
        'MKS' as KODE_KOTA,		
        CASE 		
            WHEN Principal_Channel LIKE'PEDAGANG BESAR CONSUMER%'THEN 'PDC'	
            WHEN Principal_Channel LIKE'PEDAGANG BESAR FARMASI%'THEN 'PBF'	
            WHEN Principal_Channel LIKE'Minimarket%'THEN 'MML'	
            WHEN Principal_Channel LIKE'Supermarket%'THEN 'SMR'	
            WHEN Principal_Channel LIKE'Warung%'THEN 'WRG'	
            WHEN Principal_Channel LIKE'Toko Kelontong%'THEN 'TKL'	
            WHEN Principal_Channel LIKE'Apotik%'THEN 'APT'	
            WHEN Principal_Channel LIKE'Hotel%'THEN 'HRK'	
            WHEN Principal_Channel LIKE'Trading %'THEN 'TRD'	
            ELSE	
                'OTH'
        END AS KODE_TYPE,		
        CONCAT(1,RIGHT(Kd_Pelanggan,5)) as KODE_LANG,		
        '' as KODERAYON,		
        d.kodeprod as KODEPROD,		
        a.SUPP,		
        RIGHT(Tgl_Order,2) as HRDOK,		
        '$bulan' as BLNDOK,		
        LEFT(Tgl_Order,4) as THNDOK,		
        c.NAMAPROD as NAMAPROD,		
        c.GRUPPROD as GROUPPROD,		
        IF(TOTAL='0','0',`qty_pcs`*d.satuan) as BANYAK,		
        IF(TOTAL='0',0,ROUND(TOTAL/(`qty_pcs`*d.satuan),2)) as HARGA,		
        '' as POTONGAN,		
        round(Total,2) as TOT1,		
        '' as JUM_PROMO, 		
        if(total=0 and `qty_pcs`<>0,concat(d.kodeprod,' ',`qty_pcs`*d.satuan),'')as KETERANGAN,  		  		
        '' as USER_ISI,		
        '' as JAM_ISI,		
        '' as TGL_ISI, 		
        '' as USER_EDIT, 		
        '' as JAM_EDIT, 		
        '' as TGL_EDIT,		
        '' as USER_DEL, 		
        '' as JAM_DEL, 		
        '' as TGL_DEL, 		
        '' as NO, 		
        '' as BACKUP, 		
        '' as NO_URUT, 		
        'PST' as KODE_GDG, 		
        '' as NAMA_GDG, 		
        CASE 		
        WHEN Segment LIKE 'SO%' THEN 'WS'		
        WHEN Segment LIKE '%MARKET%' THEN 'WS'		
        WHEN Segment LIKE '%CAFES%' THEN 'SWS'		
        WHEN Segment LIKE '%FOOD%' THEN 'SWS'		
        WHEN Segment LIKE '%STORE%' THEN 'SWS'		
        ELSE 'RT'		
        END AS KODESALUR,		
        '' as KODEBONUS,		
        '' as NAMABONUS, 		
        '' as GRUPBONUS, 		
        '0' as UNITBONUS, 		
        Nama_Karyawan as LAMPIRAN, 		
        '' as H_BELI, 		
        '' as KODEAREA, 		
        ALAMAT as NAMAAREA, 		
        '' as PINJAM, 		
        '' as JUALBANYAK, 		
        '' as JUALPINJAM, 		
        '' as HARGA_EXCL, 				
        Nama_Pelanggan as NAMA_LANG,		
        'V2' as NOCAB,		
        '$bulan' as BULAN,		
        ' ' as siteid,		
        ' ' as qty1,		
        ' ' as qty2,		
        ' ' as qty3,		
        '0' as qty_bonus,		
        '0' as flag_bonus,		
        ' ' as disc_persen,		
        ' ' as disc_rp,		
        ' ' as disc_value,		
        discounttd as disc_cabang,		
        discountp as disc_prinsipal,
        ' ' as disc_xtra,		
        ' ' as rp_cabang,		
        ' ' as rp_prinsipal,		
        ' ' as rp_xtra,		
        ' ' as bonus,		
        ' ' as prinsipalid,		
        ' ' as ex_no_sales,		
        ' ' as status_retur,		
        ' ' as ref,		
        ' ' as term_payment,		
        ' ' as tipe_kl				
                
        from management_raw.inner_raw_vbt_palopo a		
        INNER JOIN (
            SELECT * FROM pmu.mapping where nocab='V'
            GROUP BY kodeprod
            )d on IF(a.S_Kode=0,a.NAMA_BARANG,a.S_Kode)=d.`kodeprod` or IF(a.S_Kode is NULL,a.NAMA_BARANG,a.S_Kode)=d.`kodeprod` 	
            INNER JOIN mpm.tabprod c on d.kodeprod=c.KODEPROD 
            INNER JOIN management_raw.raw_customer_vbt b on concat(left(a.kd_pelanggan,3),a.nama_karyawan)=b.kode 		
        where `tipe_transaksi`='RETUR_PENJUALAN' and a.signature = '$signature'
        and year(a.tgl_order) = $tahun and month(a.tgl_order) = $bulan AND b.site = '$site_code' and discountp != 100

        union all

        select 		
        '07' as KDOKJDI,		
        Kd_Penjualan as NODOKJDI,		
        Kd_Penjualan as NODOKACU,		
        Tgl_Order as TGLDOKJDI,		
        b.kode_cust_mpm as KODESALES,		
        'PAL' as KODE_COMP,		
        'MKS' as KODE_KOTA,		
        CASE 		
            WHEN Principal_Channel LIKE'PEDAGANG BESAR CONSUMER%'THEN 'PDC'	
            WHEN Principal_Channel LIKE'PEDAGANG BESAR FARMASI%'THEN 'PBF'	
            WHEN Principal_Channel LIKE'Minimarket%'THEN 'MML'	
            WHEN Principal_Channel LIKE'Supermarket%'THEN 'SMR'	
            WHEN Principal_Channel LIKE'Warung%'THEN 'WRG'	
            WHEN Principal_Channel LIKE'Toko Kelontong%'THEN 'TKL'	
            WHEN Principal_Channel LIKE'Apotik%'THEN 'APT'	
            WHEN Principal_Channel LIKE'Hotel%'THEN 'HRK'	
            WHEN Principal_Channel LIKE'Trading %'THEN 'TRD'	
            ELSE	
                'OTH'
        END AS KODE_TYPE,		
        CONCAT(1,RIGHT(Kd_Pelanggan,5)) as KODE_LANG,		
        '' as KODERAYON,		
        d.kodeprod as KODEPROD,		
        a.SUPP,		
        RIGHT(Tgl_Order,2) as HRDOK,		
        '$bulan' as BLNDOK,		
        LEFT(Tgl_Order,4) as THNDOK,		
        c.NAMAPROD as NAMAPROD,		
        c.GRUPPROD as GROUPPROD,		
        '0' as BANYAK,		
        '0' as HARGA,		
        '' as POTONGAN,		
        '0' as TOT1,		
        '' as JUM_PROMO, 		
        if(total=0 and `qty_pcs`<>0,concat(d.kodeprod,' ',`qty_pcs`*d.satuan),'')as KETERANGAN,  		  		
        '' as USER_ISI,		
        '' as JAM_ISI,		
        '' as TGL_ISI, 		
        '' as USER_EDIT, 		
        '' as JAM_EDIT, 		
        '' as TGL_EDIT,		
        '' as USER_DEL, 		
        '' as JAM_DEL, 		
        '' as TGL_DEL, 		
        '' as NO, 		
        '' as BACKUP, 		
        '' as NO_URUT, 		
        'PST' as KODE_GDG, 		
        '' as NAMA_GDG, 		
        CASE 		
        WHEN Segment LIKE 'SO%' THEN 'WS'		
        WHEN Segment LIKE '%MARKET%' THEN 'WS'		
        WHEN Segment LIKE '%CAFES%' THEN 'SWS'		
        WHEN Segment LIKE '%FOOD%' THEN 'SWS'		
        WHEN Segment LIKE '%STORE%' THEN 'SWS'		
        ELSE 'RT'		
        END AS KODESALUR,		
        '' as KODEBONUS,		
        '' as NAMABONUS, 		
        '' as GRUPBONUS, 		
        `qty_pcs` as UNITBONUS, 		
        Nama_Karyawan as LAMPIRAN, 		
        '' as H_BELI, 		
        '' as KODEAREA, 		
        ALAMAT as NAMAAREA, 		
        '' as PINJAM, 		
        '' as JUALBANYAK, 		
        '' as JUALPINJAM, 		
        '' as HARGA_EXCL, 				
        Nama_Pelanggan as NAMA_LANG,		
        'V2' as NOCAB,		
        '$bulan' as BULAN,		
        ' ' as siteid,		
        ' ' as qty1,		
        ' ' as qty2,		
        ' ' as qty3,		
        `qty_pcs` as qty_bonus,		
        '1' as flag_bonus,		
        ' ' as disc_persen,		
        ' ' as disc_rp,		
        ' ' as disc_value,		
        discounttd as disc_cabang,		
        discountp as disc_prinsipal,
        ' ' as disc_xtra,		
        ' ' as rp_cabang,		
        ' ' as rp_prinsipal,		
        ' ' as rp_xtra,		
        ' ' as bonus,		
        ' ' as prinsipalid,		
        ' ' as ex_no_sales,		
        ' ' as status_retur,		
        ' ' as ref,		
        ' ' as term_payment,		
        ' ' as tipe_kl				
                
        from management_raw.inner_raw_vbt_palopo a		
        INNER JOIN (
            SELECT * FROM pmu.mapping where nocab='V'
            GROUP BY kodeprod
            )d on IF(a.S_Kode=0,a.NAMA_BARANG,a.S_Kode)=d.`kodeprod` or IF(a.S_Kode is NULL,a.NAMA_BARANG,a.S_Kode)=d.`kodeprod` 	
            INNER JOIN mpm.tabprod c on d.kodeprod=c.KODEPROD 
            INNER JOIN management_raw.raw_customer_vbt b on concat(left(a.kd_pelanggan,3),a.nama_karyawan)=b.kode 		
        where `tipe_transaksi`='RETUR_PENJUALAN' and a.signature = '$signature'
        and year(a.tgl_order) = $tahun and month(a.tgl_order) = $bulan AND b.site = '$site_code' and discountp = 100
        order by harga
        ";

        // echo "<pre>";
        // print_r($insert);
        // echo "</pre>";

        // die;

        return $this->db->query($insert);
    }

    public function insert_tbkota_vbt_palopo($site_code, $signature){
        $get_tahun = "
            select * 
            from management_raw.log_upload a 
            where a.site_code = '$site_code' and a.signature = '$signature'
        ";
        $tahun = $this->db->query($get_tahun)->row()->tahun;
        $bulan = $this->db->query($get_tahun)->row()->bulan;

        $insert = "
            insert into data$tahun.tbkota 
            select 			
            KODE_COMP AS KODE_COMP, 		
            'PLP' AS KODE_KOTA,		
            'PALOPO' AS NAMA_KOTA,		
            NOCAB			
            from data$tahun.fi 
            where concat(kode_comp, nocab) = '$site_code' 
            group by KODE_COMP,KODE_KOTA
        ";

        return $this->db->query($insert);
    }

    public function insert_fi_vbt_pare($site_code, $signature){
        $get_tahun = "
            select * 
            from management_raw.log_upload a 
            where a.site_code = '$site_code' and a.signature = '$signature'
        ";
        $tahun = $this->db->query($get_tahun)->row()->tahun;
        $bulan = $this->db->query($get_tahun)->row()->bulan;


        $insert = "
        insert data$tahun.fi
        select 		
        '07' as KDOKJDI,		
        Kd_Penjualan as NODOKJDI,		
        Kd_Penjualan as NODOKACU,		
        Tgl_Order as TGLDOKJDI,		
        b.kode_cust_mpm as KODESALES,		
        'PAR' as KODE_COMP,		
        'MKS' as KODE_KOTA,		
        CASE 		
            WHEN Principal_Channel LIKE'PEDAGANG BESAR CONSUMER%'THEN 'PDC'	
            WHEN Principal_Channel LIKE'PEDAGANG BESAR FARMASI%'THEN 'PBF'	
            WHEN Principal_Channel LIKE'Minimarket%'THEN 'MML'	
            WHEN Principal_Channel LIKE'Supermarket%'THEN 'SMR'	
            WHEN Principal_Channel LIKE'Warung%'THEN 'WRG'	
            WHEN Principal_Channel LIKE'Toko Kelontong%'THEN 'TKL'	
            WHEN Principal_Channel LIKE'Apotik%'THEN 'APT'	
            WHEN Principal_Channel LIKE'Hotel%'THEN 'HRK'	
            WHEN Principal_Channel LIKE'Trading %'THEN 'TRD'	
            ELSE	
                'OTH'
        END AS KODE_TYPE,		
        CONCAT(1,RIGHT(Kd_Pelanggan,5)) as KODE_LANG,		
        '' as KODERAYON,		
        d.kodeprod as KODEPROD,		
        a.SUPP,		
        RIGHT(Tgl_Order,2) as HRDOK,		
        '$bulan' as BLNDOK,		
        LEFT(Tgl_Order,4) as THNDOK,		
        c.NAMAPROD as NAMAPROD,		
        c.GRUPPROD as GROUPPROD,		
        IF(TOTAL='0','0',`qty_pcs`*d.satuan) as BANYAK,		
        ROUND(TOTAL/(`qty_pcs`*d.satuan),2) as HARGA,		
        '' as POTONGAN,		
        round(Total,2) as TOT1,		
        '' as JUM_PROMO, 		
        if(total=0 and `qty_pcs`<>0,concat(d.kodeprod,' ',`qty_pcs`*d.satuan),'')as KETERANGAN,  		 		
        '' as USER_ISI,		
        '' as JAM_ISI,		
        '' as TGL_ISI, 		
        '' as USER_EDIT, 		
        '' as JAM_EDIT, 		
        '' as TGL_EDIT,		
        '' as USER_DEL, 		
        '' as JAM_DEL, 		
        '' as TGL_DEL, 		
        '' as NO, 		
        '' as BACKUP, 		
        '' as NO_URUT, 		
        'PST' as KODE_GDG, 		
        '' as NAMA_GDG, 		
        CASE 		
        WHEN Segment LIKE 'SO%' THEN 'WS'		
        WHEN Segment LIKE '%MARKET%' THEN 'WS'		
        WHEN Segment LIKE '%CAFES%' THEN 'SWS'		
        WHEN Segment LIKE '%FOOD%' THEN 'SWS'		
        WHEN Segment LIKE '%STORE%' THEN 'SWS'		
        ELSE 'RT'		
        END AS KODESALUR,		
        '' as KODEBONUS,		
        '' as NAMABONUS, 		
        '' as GRUPBONUS, 		
        '0' as UNITBONUS, 	
        Nama_Karyawan as LAMPIRAN, 		
        '' as H_BELI, 		
        '' as KODEAREA, 		
        ALAMAT as NAMAAREA, 		
        '' as PINJAM, 		
        '' as JUALBANYAK, 		
        '' as JUALPINJAM, 		
        '' as HARGA_EXCL, 		
        '' as TOT1_EXCL, 		
        Nama_Pelanggan as NAMA_LANG,		
        'V3' as NOCAB,		
        '$bulan' as BULAN,		
        ' ' as siteid,		
        ' ' as qty1,		
        ' ' as qty2,		
        ' ' as qty3,		
        '0' as qty_bonus,		
        '0' as flag_bonus,		
        ' ' as disc_persen,		
        ' ' as disc_rp,		
        ' ' as disc_value,		
        discounttd as disc_cabang,		
        discountp as disc_prinsipal,		
        ' ' as disc_xtra,		
        ' ' as rp_cabang,		
        ' ' as rp_prinsipal,		
        ' ' as rp_xtra,		
        ' ' as bonus,		
        concat(11,a.supp) as prinsipalid,		
        ' ' as ex_no_sales,		
        ' ' as status_retur,		
        ' ' as ref,		
        ' ' as term_payment,		
        ' ' as tipe_kl	
                
        from management_raw.inner_raw_vbt_pare a		
        INNER JOIN (
            SELECT * FROM pmu.mapping where nocab='V'
            GROUP BY kodeprod
            )d on IF(a.S_Kode=0,a.NAMA_BARANG,a.S_Kode)=d.`kodeprod` or IF(a.S_Kode is NULL,a.NAMA_BARANG,a.S_Kode)=d.`kodeprod` 	
            INNER JOIN mpm.tabprod c on d.kodeprod=c.KODEPROD 
            INNER JOIN management_raw.raw_customer_vbt b on concat(left(a.kd_pelanggan,3),a.nama_karyawan)=b.kode 		
        where `tipe_transaksi`='PENJUALAN' and a.signature = '$signature'
        and year(a.tgl_order) = $tahun and month(a.tgl_order) = $bulan AND b.site = '$site_code' and discountp != 100

        union all

        select 		
        '07' as KDOKJDI,		
        Kd_Penjualan as NODOKJDI,		
        Kd_Penjualan as NODOKACU,		
        Tgl_Order as TGLDOKJDI,		
        b.kode_cust_mpm as KODESALES,		
        'PAR' as KODE_COMP,		
        'MKS' as KODE_KOTA,		
        CASE 		
            WHEN Principal_Channel LIKE'PEDAGANG BESAR CONSUMER%'THEN 'PDC'	
            WHEN Principal_Channel LIKE'PEDAGANG BESAR FARMASI%'THEN 'PBF'	
            WHEN Principal_Channel LIKE'Minimarket%'THEN 'MML'	
            WHEN Principal_Channel LIKE'Supermarket%'THEN 'SMR'	
            WHEN Principal_Channel LIKE'Warung%'THEN 'WRG'	
            WHEN Principal_Channel LIKE'Toko Kelontong%'THEN 'TKL'	
            WHEN Principal_Channel LIKE'Apotik%'THEN 'APT'	
            WHEN Principal_Channel LIKE'Hotel%'THEN 'HRK'	
            WHEN Principal_Channel LIKE'Trading %'THEN 'TRD'	
            ELSE	
                'OTH'
        END AS KODE_TYPE,		
        CONCAT(1,RIGHT(Kd_Pelanggan,5)) as KODE_LANG,		
        '' as KODERAYON,		
        d.kodeprod as KODEPROD,		
        a.SUPP,		
        RIGHT(Tgl_Order,2) as HRDOK,		
        '$bulan' as BLNDOK,		
        LEFT(Tgl_Order,4) as THNDOK,		
        c.NAMAPROD as NAMAPROD,		
        c.GRUPPROD as GROUPPROD,		
        '0' as BANYAK,		
        '0' as HARGA,		
        '' as POTONGAN,		
        '0' as TOT1,		
        '' as JUM_PROMO, 		
        if(total=0 and `qty_pcs`<>0,concat(d.kodeprod,' ',`qty_pcs`*d.satuan),'')as KETERANGAN,  		 		
        '' as USER_ISI,		
        '' as JAM_ISI,		
        '' as TGL_ISI, 		
        '' as USER_EDIT, 		
        '' as JAM_EDIT, 		
        '' as TGL_EDIT,		
        '' as USER_DEL, 		
        '' as JAM_DEL, 		
        '' as TGL_DEL, 		
        '' as NO, 		
        '' as BACKUP, 		
        '' as NO_URUT, 		
        'PST' as KODE_GDG, 		
        '' as NAMA_GDG, 		
        CASE 		
        WHEN Segment LIKE 'SO%' THEN 'WS'		
        WHEN Segment LIKE '%MARKET%' THEN 'WS'		
        WHEN Segment LIKE '%CAFES%' THEN 'SWS'		
        WHEN Segment LIKE '%FOOD%' THEN 'SWS'		
        WHEN Segment LIKE '%STORE%' THEN 'SWS'		
        ELSE 'RT'		
        END AS KODESALUR,		
        '' as KODEBONUS,		
        '' as NAMABONUS, 		
        '' as GRUPBONUS, 		
        `qty_pcs` as UNITBONUS, 	
        Nama_Karyawan as LAMPIRAN, 		
        '' as H_BELI, 		
        '' as KODEAREA, 		
        ALAMAT as NAMAAREA, 		
        '' as PINJAM, 		
        '' as JUALBANYAK, 		
        '' as JUALPINJAM, 		
        '' as HARGA_EXCL, 		
        '' as TOT1_EXCL, 		
        Nama_Pelanggan as NAMA_LANG,		
        'V3' as NOCAB,		
        '$bulan' as BULAN,		
        ' ' as siteid,		
        ' ' as qty1,		
        ' ' as qty2,		
        ' ' as qty3,		
        `qty_pcs` as qty_bonus,		
        '1' as flag_bonus,		
        ' ' as disc_persen,		
        ' ' as disc_rp,		
        ' ' as disc_value,		
        discounttd as disc_cabang,		
        discountp as disc_prinsipal,		
        ' ' as disc_xtra,		
        ' ' as rp_cabang,		
        ' ' as rp_prinsipal,		
        ' ' as rp_xtra,		
        ' ' as bonus,		
        concat(11,a.supp) as prinsipalid,		
        ' ' as ex_no_sales,		
        ' ' as status_retur,		
        ' ' as ref,		
        ' ' as term_payment,		
        ' ' as tipe_kl	
                
        from management_raw.inner_raw_vbt_pare a		
        INNER JOIN (
            SELECT * FROM pmu.mapping where nocab='V'
            GROUP BY kodeprod
            )d on IF(a.S_Kode=0,a.NAMA_BARANG,a.S_Kode)=d.`kodeprod` or IF(a.S_Kode is NULL,a.NAMA_BARANG,a.S_Kode)=d.`kodeprod` 	
            INNER JOIN mpm.tabprod c on d.kodeprod=c.KODEPROD 
            INNER JOIN management_raw.raw_customer_vbt b on concat(left(a.kd_pelanggan,3),a.nama_karyawan)=b.kode 		
        where `tipe_transaksi`='PENJUALAN' and a.signature = '$signature'
        and year(a.tgl_order) = $tahun and month(a.tgl_order) = $bulan AND b.site = '$site_code' and discountp = 100
        order by harga	
        ";

        // echo "<pre>";
        // print_r($insert);
        // echo "</pre>";

        return $this->db->query($insert);
    }

    public function insert_ri_vbt_pare($site_code, $signature){
        $get_tahun = "
            select * 
            from management_raw.log_upload a 
            where a.site_code = '$site_code' and a.signature = '$signature'
        ";
        $tahun = $this->db->query($get_tahun)->row()->tahun;
        $bulan = $this->db->query($get_tahun)->row()->bulan;

        $insert = "
        insert data$tahun.ri
        select 		
        '07' as KDOKJDI,		
        Kd_Penjualan as NODOKJDI,		
        Kd_Penjualan as NODOKACU,		
        Tgl_Order as TGLDOKJDI,		
        b.kode_cust_mpm as KODESALES,		
        'PAR' as KODE_COMP,		
        'MKS' as KODE_KOTA,		
        CASE 		
            WHEN Principal_Channel LIKE'PEDAGANG BESAR CONSUMER%'THEN 'PDC'	
            WHEN Principal_Channel LIKE'PEDAGANG BESAR FARMASI%'THEN 'PBF'	
            WHEN Principal_Channel LIKE'Minimarket%'THEN 'MML'	
            WHEN Principal_Channel LIKE'Supermarket%'THEN 'SMR'	
            WHEN Principal_Channel LIKE'Warung%'THEN 'WRG'	
            WHEN Principal_Channel LIKE'Toko Kelontong%'THEN 'TKL'	
            WHEN Principal_Channel LIKE'Apotik%'THEN 'APT'	
            WHEN Principal_Channel LIKE'Hotel%'THEN 'HRK'	
            WHEN Principal_Channel LIKE'Trading %'THEN 'TRD'	
            ELSE	
                'OTH'
        END AS KODE_TYPE,		
        CONCAT(1,RIGHT(Kd_Pelanggan,5)) as KODE_LANG,		
        '' as KODERAYON,		
        d.kodeprod as KODEPROD,		
        a.SUPP,		
        RIGHT(Tgl_Order,2) as HRDOK,		
        '$bulan' as BLNDOK,		
        LEFT(Tgl_Order,4) as THNDOK,		
        c.NAMAPROD as NAMAPROD,		
        c.GRUPPROD as GROUPPROD,		
        IF(TOTAL='0','0',`qty_pcs`*d.satuan) as BANYAK,		
        IF(TOTAL='0',0,ROUND(TOTAL/(`qty_pcs`*d.satuan),2)) as HARGA,		
        '' as POTONGAN,		
        round(Total,2) as TOT1,		
        '' as JUM_PROMO, 		
        if(total=0 and `qty_pcs`<>0,concat(d.kodeprod,' ',`qty_pcs`*d.satuan),'')as KETERANGAN,  		  		
        '' as USER_ISI,		
        '' as JAM_ISI,		
        '' as TGL_ISI, 		
        '' as USER_EDIT, 		
        '' as JAM_EDIT, 		
        '' as TGL_EDIT,		
        '' as USER_DEL, 		
        '' as JAM_DEL, 		
        '' as TGL_DEL, 		
        '' as NO, 		
        '' as BACKUP, 		
        '' as NO_URUT, 		
        'PST' as KODE_GDG, 		
        '' as NAMA_GDG, 		
        CASE 		
        WHEN Segment LIKE 'SO%' THEN 'WS'		
        WHEN Segment LIKE '%MARKET%' THEN 'WS'		
        WHEN Segment LIKE '%CAFES%' THEN 'SWS'		
        WHEN Segment LIKE '%FOOD%' THEN 'SWS'		
        WHEN Segment LIKE '%STORE%' THEN 'SWS'		
        ELSE 'RT'		
        END AS KODESALUR,		
        '' as KODEBONUS,		
        '' as NAMABONUS, 		
        '' as GRUPBONUS, 		
        '0' as UNITBONUS, 		
        Nama_Karyawan as LAMPIRAN, 		
        '' as H_BELI, 		
        '' as KODEAREA, 		
        ALAMAT as NAMAAREA, 		
        '' as PINJAM, 		
        '' as JUALBANYAK, 		
        '' as JUALPINJAM, 		
        '' as HARGA_EXCL, 				
        Nama_Pelanggan as NAMA_LANG,		
        'V3' as NOCAB,		
        '$bulan' as BULAN,		
        ' ' as siteid,		
        ' ' as qty1,		
        ' ' as qty2,		
        ' ' as qty3,		
        '0' as qty_bonus,		
        '0' as flag_bonus,		
        ' ' as disc_persen,		
        ' ' as disc_rp,		
        ' ' as disc_value,		
        discounttd as disc_cabang,		
        discountp as disc_prinsipal,
        ' ' as disc_xtra,		
        ' ' as rp_cabang,		
        ' ' as rp_prinsipal,		
        ' ' as rp_xtra,		
        ' ' as bonus,		
        ' ' as prinsipalid,		
        ' ' as ex_no_sales,		
        ' ' as status_retur,		
        ' ' as ref,		
        ' ' as term_payment,		
        ' ' as tipe_kl				
                
        from management_raw.inner_raw_vbt_pare a		
        INNER JOIN (
            SELECT * FROM pmu.mapping where nocab='V'
            GROUP BY kodeprod
            )d on IF(a.S_Kode=0,a.NAMA_BARANG,a.S_Kode)=d.`kodeprod` or IF(a.S_Kode is NULL,a.NAMA_BARANG,a.S_Kode)=d.`kodeprod` 	
            INNER JOIN mpm.tabprod c on d.kodeprod=c.KODEPROD 
            INNER JOIN management_raw.raw_customer_vbt b on concat(left(a.kd_pelanggan,3),a.nama_karyawan)=b.kode 		
        where `tipe_transaksi`='RETUR_PENJUALAN' and a.signature = '$signature'
        and year(a.tgl_order) = $tahun and month(a.tgl_order) = $bulan AND b.site = '$site_code' and discountp != 100

        union all

        select 		
        '07' as KDOKJDI,		
        Kd_Penjualan as NODOKJDI,		
        Kd_Penjualan as NODOKACU,		
        Tgl_Order as TGLDOKJDI,		
        b.kode_cust_mpm as KODESALES,		
        'PAR' as KODE_COMP,		
        'MKS' as KODE_KOTA,		
        CASE 		
            WHEN Principal_Channel LIKE'PEDAGANG BESAR CONSUMER%'THEN 'PDC'	
            WHEN Principal_Channel LIKE'PEDAGANG BESAR FARMASI%'THEN 'PBF'	
            WHEN Principal_Channel LIKE'Minimarket%'THEN 'MML'	
            WHEN Principal_Channel LIKE'Supermarket%'THEN 'SMR'	
            WHEN Principal_Channel LIKE'Warung%'THEN 'WRG'	
            WHEN Principal_Channel LIKE'Toko Kelontong%'THEN 'TKL'	
            WHEN Principal_Channel LIKE'Apotik%'THEN 'APT'	
            WHEN Principal_Channel LIKE'Hotel%'THEN 'HRK'	
            WHEN Principal_Channel LIKE'Trading %'THEN 'TRD'	
            ELSE	
                'OTH'
        END AS KODE_TYPE,		
        CONCAT(1,RIGHT(Kd_Pelanggan,5)) as KODE_LANG,		
        '' as KODERAYON,		
        d.kodeprod as KODEPROD,		
        a.SUPP,		
        RIGHT(Tgl_Order,2) as HRDOK,		
        '$bulan' as BLNDOK,		
        LEFT(Tgl_Order,4) as THNDOK,		
        c.NAMAPROD as NAMAPROD,		
        c.GRUPPROD as GROUPPROD,		
        '0' as BANYAK,		
        '0' as HARGA,		
        '' as POTONGAN,		
        '0' as TOT1,		
        '' as JUM_PROMO, 		
        if(total=0 and `qty_pcs`<>0,concat(d.kodeprod,' ',`qty_pcs`*d.satuan),'')as KETERANGAN,  		  		
        '' as USER_ISI,		
        '' as JAM_ISI,		
        '' as TGL_ISI, 		
        '' as USER_EDIT, 		
        '' as JAM_EDIT, 		
        '' as TGL_EDIT,		
        '' as USER_DEL, 		
        '' as JAM_DEL, 		
        '' as TGL_DEL, 		
        '' as NO, 		
        '' as BACKUP, 		
        '' as NO_URUT, 		
        'PST' as KODE_GDG, 		
        '' as NAMA_GDG, 		
        CASE 		
        WHEN Segment LIKE 'SO%' THEN 'WS'		
        WHEN Segment LIKE '%MARKET%' THEN 'WS'		
        WHEN Segment LIKE '%CAFES%' THEN 'SWS'		
        WHEN Segment LIKE '%FOOD%' THEN 'SWS'		
        WHEN Segment LIKE '%STORE%' THEN 'SWS'		
        ELSE 'RT'		
        END AS KODESALUR,		
        '' as KODEBONUS,		
        '' as NAMABONUS, 		
        '' as GRUPBONUS, 		
        `qty_pcs` as UNITBONUS, 		
        Nama_Karyawan as LAMPIRAN, 		
        '' as H_BELI, 		
        '' as KODEAREA, 		
        ALAMAT as NAMAAREA, 		
        '' as PINJAM, 		
        '' as JUALBANYAK, 		
        '' as JUALPINJAM, 		
        '' as HARGA_EXCL, 				
        Nama_Pelanggan as NAMA_LANG,		
        'V3' as NOCAB,		
        '$bulan' as BULAN,		
        ' ' as siteid,		
        ' ' as qty1,		
        ' ' as qty2,		
        ' ' as qty3,		
        `qty_pcs` as qty_bonus,		
        '1' as flag_bonus,		
        ' ' as disc_persen,		
        ' ' as disc_rp,		
        ' ' as disc_value,		
        discounttd as disc_cabang,		
        discountp as disc_prinsipal,
        ' ' as disc_xtra,		
        ' ' as rp_cabang,		
        ' ' as rp_prinsipal,		
        ' ' as rp_xtra,		
        ' ' as bonus,		
        ' ' as prinsipalid,		
        ' ' as ex_no_sales,		
        ' ' as status_retur,		
        ' ' as ref,		
        ' ' as term_payment,		
        ' ' as tipe_kl				
                
        from management_raw.inner_raw_vbt_pare a		
        INNER JOIN (
            SELECT * FROM pmu.mapping where nocab='V'
            GROUP BY kodeprod
            )d on IF(a.S_Kode=0,a.NAMA_BARANG,a.S_Kode)=d.`kodeprod` or IF(a.S_Kode is NULL,a.NAMA_BARANG,a.S_Kode)=d.`kodeprod` 	
            INNER JOIN mpm.tabprod c on d.kodeprod=c.KODEPROD 
            INNER JOIN management_raw.raw_customer_vbt b on concat(left(a.kd_pelanggan,3),a.nama_karyawan)=b.kode 		
        where `tipe_transaksi`='RETUR_PENJUALAN' and a.signature = '$signature'
        and year(a.tgl_order) = $tahun and month(a.tgl_order) = $bulan AND b.site = '$site_code' and discountp = 100
        order by harga
        ";

        // echo "<pre>";
        // print_r($insert);
        // echo "</pre>";

        // die;

        return $this->db->query($insert);
    }

    public function insert_tbkota_vbt_pare($site_code, $signature){
        $get_tahun = "
            select * 
            from management_raw.log_upload a 
            where a.site_code = '$site_code' and a.signature = '$signature'
        ";
        $tahun = $this->db->query($get_tahun)->row()->tahun;
        $bulan = $this->db->query($get_tahun)->row()->bulan;

        $insert = "
            insert into data$tahun.tbkota 
            select 			
            KODE_COMP AS KODE_COMP, 		
            'PAL' AS KODE_KOTA,		
            'PARE-PARE' AS NAMA_KOTA,		
            NOCAB			
            from data$tahun.fi 
            where concat(kode_comp, nocab) = '$site_code' 
            group by KODE_COMP,KODE_KOTA
        ";

        return $this->db->query($insert);
    }
    
    public function get_customer_batulicin(){
        $query = "
            select *
            from management_raw.raw_customer_batulicin a 
        ";
        return $this->db->query($query);
    }

    public function inner_kodeproduk_manado($site_code, $signature){

        if ($site_code == 'MANW5') {
            $params_site_code = "inner_raw_manado"; 
            $params_site_code2 = "raw_manado"; 
        }

        $query = "
            insert into management_raw.$params_site_code
            select '', '$site_code', '', '', a.*, b.supp, b.grupprod, '', '', ''
            from management_raw.$params_site_code2 a INNER JOIN (
                select a.kodeprod, a.supp, a.grupprod
                from mpm.tabprod a 
            )b on a.product_id_supplier = b.kodeprod
            where a.signature = '$signature'
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        // die;
        $update = $this->db->query($query);
        if ($update) {
            return $update;
        }else{
            return array();
        }
    }

    public function update_namaprod_manado($site_code, $signature){

        if ($site_code == 'MANW5') {
            $params_site_code = "inner_raw_manado"; 
        }
        
        $query = "
            update management_raw.$params_site_code a 
            set a.nama_invoice = (
                select b.namaprod
                from mpm.tabprod b 
                where a.product_id_supplier = b.kodeprod
            ), a.map_divisi = (
                select b.new_divisi
                from mpm.tabprod b 
                where a.product_id_supplier = b.kodeprod
            ), a.map_group = (
                select b.new_group
                from mpm.tabprod b 
                where a.product_id_supplier = b.kodeprod
            ), a.map_subgroup = (
                select b.new_subgroup
                from mpm.tabprod b 
                where a.product_id_supplier = b.kodeprod
            )
            where a.signature = '$signature'
        ";
        $update = $this->db->query($query);
        if ($update) {
            return $update;
        }else{
            return array();
        }
    }

    public function insert_fi_manado($site_code, $signature){
        $get_tahun = "
            select * 
            from management_raw.log_upload a 
            where a.site_code = '$site_code' and a.signature = '$signature'
        ";
        $tahun = $this->db->query($get_tahun)->row()->tahun;
        $bulan = $this->db->query($get_tahun)->row()->bulan;


        $insert = "
        insert data$tahun.fi
        select 		
        '08' as KDOKJDI,		
        no_sales as NODOKJDI,		
        no_sales as NODOKACU,		
        tanggal as TGLDOKJDI,		
        salesmanid as KODESALES,		
        'MAN' as KODE_COMP,		
        regionalid as KODE_KOTA,		
        typeid AS KODE_TYPE,		
        customerid as KODE_LANG,		
        areaid as KODERAYON,		
        a.product_id_supplier as KODEPROD,		
        a.SUPP,		
        RIGHT(tanggal,2) as HRDOK,		
        '$bulan' as BLNDOK,		
        LEFT(tanggal,4) as THNDOK,		
        a.nama_invoice as NAMAPROD,		
        a.grupprod as GROUPPROD,		
        sum(qty_kecil) as BANYAK,		
        harga_jual as HARGA,		
        sum(rp_discount) as POTONGAN,		
        sum(rp_kotor) as TOT1,		
        '' as JUM_PROMO, 		
        '' as KETERANGAN,  		 		
        '' as USER_ISI,		
        '' as JAM_ISI,		
        '' as TGL_ISI, 		
        '' as USER_EDIT, 		
        '' as JAM_EDIT, 		
        '' as TGL_EDIT,		
        '' as USER_DEL, 		
        '' as JAM_DEL, 		
        '' as TGL_DEL, 		
        '' as NO, 		
        '' as BACKUP, 		
        '' as NO_URUT, 		
        'PST' as KODE_GDG, 		
        '' as NAMA_GDG, 		
        classid AS KODESALUR,		
        '' as KODEBONUS,		
        '' as NAMABONUS, 		
        '' as GRUPBONUS, 		
        '0' as UNITBONUS, 		
        nama_salesman as LAMPIRAN, 		
        '' as H_BELI, 		
        a.areaid as KODEAREA, 		
        a.nama_area as NAMAAREA, 		
        '' as PINJAM, 		
        '' as JUALBANYAK, 		
        '' as JUALPINJAM, 		
        '' as HARGA_EXCL, 		
        '' as TOT1_EXCL, 		
        nama_customer as NAMA_LANG,		
        'W5' as NOCAB,		
        '$bulan' as BULAN,		
        siteid,		
        ' ' as qty1,		
        ' ' as qty2,		
        sum(qty_kecil) as qty3,		
        '0' as qty_bonus,		
        '0' as flag_bonus,		
        disc_cod as disc_persen,	
        sum(rp_disc_cod) as disc_rp,		
        ' ' as disc_value,		
        disc_cabang,		
        disc_prinsipal,		
        disc_xtra,		
        sum(rp_disc_cabang) as rp_cabang,		
        sum(rp_disc_prinsipal) as rp_prinsipal,		
        sum(rp_disc_xtra) as rp_xtra,		
        ' ' as bonus,		
        brandid as prinsipalid,		
        ex_no_sales,		
        status_retur,		
        ref,		
        term_payment,		
        '' as tipe_kl		
                
        from management_raw.inner_raw_manado a
        where trans = 'FAKTUR' and a.signature = '$signature'
        and a.year = $tahun and a.month = $bulan and qty_kecil != 0
        group by no_sales, a.product_id_supplier

        union all

        select 		
        '08' as KDOKJDI,		
        no_sales as NODOKJDI,		
        no_sales as NODOKACU,		
        tanggal as TGLDOKJDI,		
        salesmanid as KODESALES,		
        'MAN' as KODE_COMP,		
        regionalid as KODE_KOTA,		
        typeid AS KODE_TYPE,		
        customerid as KODE_LANG,		
        areaid as KODERAYON,		
        a.product_id_supplier as KODEPROD,		
        a.SUPP,		
        RIGHT(tanggal,2) as HRDOK,		
        '$bulan' as BLNDOK,		
        LEFT(tanggal,4) as THNDOK,		
        a.nama_invoice as NAMAPROD,		
        a.grupprod as GROUPPROD,		
        '0' as BANYAK,		
        '0' as HARGA,		
        '0' as POTONGAN,		
        '0' as TOT1,		
        '' as JUM_PROMO, 		
        '' as KETERANGAN,  		 		
        '' as USER_ISI,		
        '' as JAM_ISI,		
        '' as TGL_ISI, 		
        '' as USER_EDIT, 		
        '' as JAM_EDIT, 		
        '' as TGL_EDIT,		
        '' as USER_DEL, 		
        '' as JAM_DEL, 		
        '' as TGL_DEL, 		
        '' as NO, 		
        '' as BACKUP, 		
        '' as NO_URUT, 		
        'PST' as KODE_GDG, 		
        '' as NAMA_GDG, 		
        classid AS KODESALUR,		
        '' as KODEBONUS,		
        '' as NAMABONUS, 		
        '' as GRUPBONUS, 		
        sum(qty_bonus) as UNITBONUS, 		
        nama_salesman as LAMPIRAN, 		
        '' as H_BELI, 		
        a.areaid as KODEAREA, 		
        a.nama_area as NAMAAREA, 		
        '' as PINJAM, 		
        '' as JUALBANYAK, 		
        '' as JUALPINJAM, 		
        '' as HARGA_EXCL, 		
        '' as TOT1_EXCL, 		
        nama_customer as NAMA_LANG,		
        'W5' as NOCAB,		
        '$bulan' as BULAN,		
        siteid,		
        ' ' as qty1,		
        ' ' as qty2,		
        ' ' as qty3,		
        sum(qty_bonus),		
        '1' as flag_bonus,		
        '' as disc_persen,	
        '' as disc_rp,		
        '' as disc_value,		
        '' as disc_cabang,		
        '' as disc_prinsipal,		
        '' as disc_xtra,		
        '' as rp_cabang,		
        ''  as rp_prinsipal,		
        '' as rp_xtra,		
        ' ' as bonus,		
        brandid as prinsipalid,		
        ex_no_sales,		
        status_retur,		
        ref,		
        term_payment,		
        '' as tipe_kl		
                
        from management_raw.inner_raw_manado a
        where trans = 'FAKTUR' and a.signature = '$signature'
        and a.year = $tahun and a.month = $bulan and qty_bonus != 0
        group by no_sales, a.product_id_supplier
        order by harga	
        
        ";

        // echo "<pre>";
        // print_r($insert);
        // echo "</pre>";

        return $this->db->query($insert);
    }

    public function insert_ri_manado($site_code, $signature){
        $get_tahun = "
            select * 
            from management_raw.log_upload a 
            where a.site_code = '$site_code' and a.signature = '$signature'
        ";
        $tahun = $this->db->query($get_tahun)->row()->tahun;
        $bulan = $this->db->query($get_tahun)->row()->bulan;


        $insert = "
        insert data$tahun.ri
        select 		
        '08' as KDOKJDI,		
        no_sales as NODOKJDI,		
        no_sales as NODOKACU,		
        tanggal as TGLDOKJDI,		
        salesmanid as KODESALES,		
        'MAN' as KODE_COMP,		
        regionalid as KODE_KOTA,		
        typeid AS KODE_TYPE,		
        customerid as KODE_LANG,		
        areaid as KODERAYON,		
        a.product_id_supplier as KODEPROD,		
        a.SUPP,		
        RIGHT(tanggal,2) as HRDOK,		
        '$bulan' as BLNDOK,		
        LEFT(tanggal,4) as THNDOK,			
        a.nama_invoice as NAMAPROD,		
        a.grupprod as GROUPPROD,	
        sum(qty_kecil) as BANYAK,		
        harga_jual as HARGA,		
        sum(rp_discount) as POTONGAN,		
        sum(rp_kotor) as TOT1,		
        '' as JUM_PROMO, 		
        '' as KETERANGAN,  		 		
        '' as USER_ISI,		
        '' as JAM_ISI,		
        '' as TGL_ISI, 		
        '' as USER_EDIT, 		
        '' as JAM_EDIT, 		
        '' as TGL_EDIT,		
        '' as USER_DEL, 		
        '' as JAM_DEL, 		
        '' as TGL_DEL, 		
        '' as NO, 		
        '' as BACKUP, 		
        '' as NO_URUT, 		
        'PST' as KODE_GDG, 		
        '' as NAMA_GDG, 		
        classid AS KODESALUR,		
        '' as KODEBONUS,		
        '' as NAMABONUS, 		
        '' as GRUPBONUS, 		
        '0' as UNITBONUS, 		
        nama_salesman as LAMPIRAN, 		
        '' as H_BELI, 		
        a.areaid as KODEAREA, 		
        a.nama_area as NAMAAREA, 	
        '' as PINJAM, 		
        '' as JUALBANYAK, 		
        '' as JUALPINJAM, 		
        '' as HARGA_EXCL, 		
        nama_customer as NAMA_LANG,		
        'W5' as NOCAB,		
        '$bulan' as BULAN,		
        siteid,		
        ' ' as qty1,		
        ' ' as qty2,		
        sum(qty_kecil) as qty3,		
        '0' as qty_bonus,		
        '0' as flag_bonus,		
        disc_cod as disc_persen,		
        sum(rp_disc_cod) as disc_rp,		
        ' ' as disc_value,		
        disc_cabang,		
        disc_prinsipal,		
        disc_xtra,		
        sum(rp_disc_cabang) as rp_cabang,		
        sum(rp_disc_prinsipal) as rp_prinsipal,		
        sum(rp_disc_xtra) as rp_xtra,		
        '' as bonus,		
        brandid as prinsipalid,		
        ex_no_sales,		
        status_retur,		
        ref,		
        term_payment,		
        '' as tipe_kl		
                
        from management_raw.inner_raw_manado a	
        where trans = 'RETUR' and a.signature = '$signature'
        and a.year= $tahun and a.month = $bulan and qty_kecil != 0
        group by no_sales, a.product_id_supplier

        union all

        select 		
        '08' as KDOKJDI,		
        no_sales as NODOKJDI,		
        no_sales as NODOKACU,		
        tanggal as TGLDOKJDI,		
        salesmanid as KODESALES,		
        'MAN' as KODE_COMP,		
        regionalid as KODE_KOTA,		
        typeid AS KODE_TYPE,		
        customerid as KODE_LANG,		
        areaid as KODERAYON,		
        a.product_id_supplier as KODEPROD,		
        a.SUPP,		
        RIGHT(tanggal,2) as HRDOK,		
        '$bulan' as BLNDOK,		
        LEFT(tanggal,4) as THNDOK,			
        a.nama_invoice as NAMAPROD,		
        a.grupprod as GROUPPROD,	
        '0' as BANYAK,		
        '0' as HARGA,		
        '0' as POTONGAN,		
        '0' as TOT1,		
        '' as JUM_PROMO, 		
        '' as KETERANGAN,  		 		
        '' as USER_ISI,		
        '' as JAM_ISI,		
        '' as TGL_ISI, 		
        '' as USER_EDIT, 		
        '' as JAM_EDIT, 		
        '' as TGL_EDIT,		
        '' as USER_DEL, 		
        '' as JAM_DEL, 		
        '' as TGL_DEL, 		
        '' as NO, 		
        '' as BACKUP, 		
        '' as NO_URUT, 		
        'PST' as KODE_GDG, 		
        '' as NAMA_GDG, 		
        classid AS KODESALUR,		
        '' as KODEBONUS,		
        '' as NAMABONUS, 		
        '' as GRUPBONUS, 		
        sum(qty_bonus) as UNITBONUS, 		
        nama_salesman as LAMPIRAN, 		
        '' as H_BELI, 		
        a.areaid as KODEAREA, 		
        a.nama_area as NAMAAREA, 	
        '' as PINJAM, 		
        '' as JUALBANYAK, 		
        '' as JUALPINJAM, 		
        '' as HARGA_EXCL, 		
        nama_customer as NAMA_LANG,		
        'W5' as NOCAB,		
        '$bulan' as BULAN,		
        siteid,		
        ' ' as qty1,		
        ' ' as qty2,		
        ' ' as qty3,		
        sum(qty_bonus),		
        '1' as flag_bonus,		
        '' as disc_persen,		
        '' as disc_rp,		
        ' ' as disc_value,		
        '' as disc_cabang,		
        '' as disc_prinsipal,		
        '' as disc_xtra,		
        '' as rp_cabang,		
        '' as rp_prinsipal,		
        '' as rp_xtra,		
        '' as bonus,		
        brandid as prinsipalid,		
        ex_no_sales,		
        status_retur,		
        ref,		
        term_payment,		
        '' as tipe_kl		
                
        from management_raw.inner_raw_manado a	
        where trans = 'RETUR' and a.signature = '$signature'
        and a.year= $tahun and a.month = $bulan and qty_bonus != 0
        group by no_sales, a.product_id_supplier
        order by harga	
        ";

        // echo "<pre>";
        // print_r($insert);
        // echo "</pre>";

        return $this->db->query($insert);
    }

    public function insert_tbkota_manado($site_code, $signature){
        $get_tahun = "
            select * 
            from management_raw.log_upload a 
            where a.site_code = '$site_code' and a.signature = '$signature'
        ";
        $tahun = $this->db->query($get_tahun)->row()->tahun;
        $bulan = $this->db->query($get_tahun)->row()->bulan;

        $insert = "
            insert into data$tahun.tbkota
            select  KODE_COMP AS KODE_COMP, 		
                    a.kode_kota AS KODE_KOTA,		
                    b.nama_kota AS NAMA_KOTA,		
                    a.nocab as nocab		
            from data$tahun.fi a left join management_raw.raw_customer_manado b
            on a.kode_kota = b.kode_kota
            where concat(a.kode_comp, a.nocab) = '$site_code'
            group by KODE_COMP,KODE_KOTA
        ";

        return $this->db->query($insert);
    }

    public function get_raw_draft_gorontalo($site_code, $signature = ''){

        if ($site_code == 'GTO87') {
            $params_site_code = "raw_gorontalo"; 
        }

        $get_tahun = "
            select * 
            from management_raw.log_upload a 
            where a.site_code = '$site_code' and a.signature = '$signature'
        ";
        $tahun = $this->db->query($get_tahun)->row()->tahun;
        $bulan = $this->db->query($get_tahun)->row()->bulan;


        if ($signature) {
            $params = "where a.signature = '$signature'";
        }else{
            $params = "";
        }

        $query = "
            select a.*, b.kode_lang
            from
            (
                select a.* from management_raw.$params_site_code a
                $params
            )a
            left join
            (
                select a.kode_lang, nama_lang
                from data$tahun.tblang a
                where concat(kode_comp,nocab) = '$site_code'
                group by a.nama_lang
            )b on a.customer = b.nama_lang
        ";
        return $this->db->query($query);
    }

    public function update_kodeproduk_gorontalo($site_code, $signature){

        if ($site_code == 'GTO87') {
            $params_site_code = "raw_gorontalo"; 
        }

        $query = "
            update management_raw.$params_site_code a 
            set a.kode_mpm = concat(0,a.kode_mpm)
            where a.signature = '$signature' and length(a.kode_mpm) = 5 
        ";
        $update = $this->db->query($query);

        if ($update) {
            return $update;
        }else{
            return array();
        }
    }

    public function inner_kodeproduk_gorontalo($site_code, $signature){

        if ($site_code == 'GTO87') {
            $params_site_code = "inner_raw_gorontalo"; 
            $params_site_code2 = "raw_gorontalo"; 
        }
        

        $query = "
            insert into management_raw.$params_site_code
            select '', '$site_code', '', '', a.*, b.supp, b.grupprod, '', '', ''
            from management_raw.$params_site_code2 a INNER JOIN (
                select a.kodeprod, a.supp, a.grupprod
                from mpm.tabprod a 
            )b on a.kode_mpm = b.kodeprod
            where a.signature = '$signature'
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        // die;
        $update = $this->db->query($query);
        if ($update) {
            return $update;
        }else{
            return array();
        }
    }

    public function update_namaprod_gorontalo($site_code, $signature){

        if ($site_code == 'GTO87') {
            $params_site_code = "inner_raw_gorontalo"; 
        }
        
        $query = "
            update management_raw.$params_site_code a 
            set a.nama_barang = (
                select b.namaprod
                from mpm.tabprod b 
                where a.kode_mpm = b.kodeprod
            ), a.map_divisi = (
                select b.new_divisi
                from mpm.tabprod b 
                where a.kode_mpm = b.kodeprod
            ), a.map_group = (
                select b.new_group
                from mpm.tabprod b 
                where a.kode_mpm = b.kodeprod
            ), a.map_subgroup = (
                select b.new_subgroup
                from mpm.tabprod b 
                where a.kode_mpm = b.kodeprod
            )
            where a.signature = '$signature'
        ";
        $update = $this->db->query($query);
        if ($update) {
            return $update;
        }else{
            return array();
        }
    }

    public function insert_fi_gorontalo($site_code, $signature){
        $get_tahun = "
            select * 
            from management_raw.log_upload a 
            where a.site_code = '$site_code' and a.signature = '$signature'
        ";
        $tahun = $this->db->query($get_tahun)->row()->tahun;
        $bulan = $this->db->query($get_tahun)->row()->bulan;

        $insert = "
        insert data$tahun.fi
        select  '08' as kddokjdi, a.nomor_nota as nodokjdi, a.nomor_nota as nodokacu, a.tanggal as tgldokjdi, 
					'001' as kodesales, 'GTO' as kode_comp, 'GTO' AS kode_kota, 
					a.kode_type as kode_type, '' as kode_lang, '' as koderayon, a.kode_mpm as kodeprod,
					a.supp, if(length(day(a.tanggal)) = 1, concat('0',day(a.tanggal)), day(a.tanggal)) as hrdok,
					if(length(month(a.tanggal)) = 1, concat('0',month(a.tanggal)), month(a.tanggal)) as blndok,
					if(length(year(a.tanggal)) = 1, concat('0',year(a.tanggal)), year(a.tanggal)) as thndok,
					a.nama_barang as namaprod, a.grupprod, a.jumlah as banyak,
					a.nilai_unit / a.jumlah as harga, potongan,
					a.nilai_unit as tot1, '' as jum_promo, '' as keterangan, '' as  user_isi, '' as jam_isi, '' as tgl_isi,
					'' as  user_edit, '' as jam_edit, '' as tgl_edit, '' as  user_del, '' as jam_del, '' as tgl_del, '' as no, '' as backup, '' as no_urut,
					'PST' as kode_gdg, '' as nama_gdg, a.kode_class as kodesalur, '' as kodebonus, '' as namabonus, '' as grupbonus,
					'' as unitbonus, a.sales as lampiran, a.nilai_unit / a.jumlah as h_beli, '' as kodearea, a.wilayah as namaarea,
					'' as pinjam, '' as jualbanyak, '' as jualpinjam, '' as harga_excl, '' as tot1_excl, a.customer as namalang, 
					'87' as nocab, if(length(month(a.tanggal)) = 1, concat('0',month(a.tanggal)), month(a.tanggal)) as bulan,
					'' as siteid, '' as qty1, '' as qty2, '' as qty3, '0' as qty_bonus, '0' as flag_bonus, '' as disc_persen, '' as disc_rp, 
					'' as disc_value, '' as disc_cabang, '' as disc_prinsipal, '' as disc_xtra,
					'' as rp_cabang, '' as rp_prinsipal, '' as rp_xtra, '' as bonus, concat('11',a.supp) as prinsipalid, '' as ex_no_sales, 
					'' as status_retur, '' as ref, '' as term_payment, '' as tipe_kl                       
        from management_raw.inner_raw_gorontalo a 
        where a.signature = '$signature' and year(a.tanggal) = $tahun and month(a.tanggal) = $bulan and a.jumlah > 0

        union all

        select  '08' as kddokjdi, a.nomor_nota as nodokjdi, a.nomor_nota as nodokacu, a.tanggal as tgldokjdi, 
					'001' as kodesales, 'GTO' as kode_comp, 'GTO' AS kode_kota, 
					a.kode_type as kode_type, '' as kode_lang, '' as koderayon, a.kode_mpm as kodeprod,
					a.supp, if(length(day(a.tanggal)) = 1, concat('0',day(a.tanggal)), day(a.tanggal)) as hrdok,
					if(length(month(a.tanggal)) = 1, concat('0',month(a.tanggal)), month(a.tanggal)) as blndok,
					if(length(year(a.tanggal)) = 1, concat('0',year(a.tanggal)), year(a.tanggal)) as thndok,
					a.nama_barang as namaprod, a.grupprod, '0' as banyak,
					'0' as harga, '0' as potongan,
					'0' as tot1, '' as jum_promo, '' as keterangan, '' as  user_isi, '' as jam_isi, '' as tgl_isi,
					'' as  user_edit, '' as jam_edit, '' as tgl_edit, '' as  user_del, '' as jam_del, '' as tgl_del, '' as no, '' as backup, '' as no_urut,
					'PST' as kode_gdg, '' as nama_gdg, a.kode_class as kodesalur, '' as kodebonus, '' as namabonus, '' as grupbonus,
					a.tpr as unitbonus, a.sales as lampiran, '' as h_beli, '' as kodearea, a.wilayah as namaarea,
					'' as pinjam, '' as jualbanyak, '' as jualpinjam, '' as harga_excl, '' as tot1_excl, a.customer as namalang, 
					'87' as nocab, if(length(month(a.tanggal)) = 1, concat('0',month(a.tanggal)), month(a.tanggal)) as bulan,
					'' as siteid, '' as qty1, '' as qty2, '' as qty3, a.tpr as qty_bonus, '1' as flag_bonus, '' as disc_persen, '' as disc_rp, 
					'' as disc_value, '' as disc_cabang, '' as disc_prinsipal, '' as disc_xtra,
					'' as rp_cabang, '' as rp_prinsipal, '' as rp_xtra, '' as bonus, concat('11',a.supp) as prinsipalid, '' as ex_no_sales, 
					'' as status_retur, '' as ref, '' as term_payment, '' as tipe_kl                       
        from management_raw.inner_raw_gorontalo a 
        where a.signature = '$signature' and year(a.tanggal) = $tahun and month(a.tanggal) = $bulan and a.tpr !=0
        ";

        // echo "<pre>";
        // print_r($insert);
        // echo "</pre>";

        return $this->db->query($insert);
    }
    
    public function insert_tblang_bantu_gorontalo($site_code, $signature){

        // insert tblang bantu gorontalo berfungsi untuk menambah tblang_bantu dengan data dari FI terbaru

        if ($site_code == 'BRBS0') {
            $params = "tblang_bantu_barabai";
        }else if($site_code == 'SSMS9'){
            $params = "tblang_bantu";
        }else if($site_code == 'SSJD2'){
            $params = "tblang_bantu_batulicin";
        }else if($site_code == 'MANW5'){
            $params = "tblang_bantu_manado";
        }else if($site_code == 'PTK82'){
            $params = "tblang_bantu_pontianak";
        }else if($site_code == 'SMRB7'){
            $params = "tblang_bantu_samarinda";
        }else if($site_code == 'BTGB8'){
            $params = "tblang_bantu_bontang";
        }else if($site_code == 'GTO87'){
            $params = "tblang_bantu_gorontalo";
        }

        $get_tahun = "
            select * 
            from management_raw.log_upload a 
            where a.site_code = '$site_code' and a.signature = '$signature'
        ";
        $tahun = $this->db->query($get_tahun)->row()->tahun;
        $bulan = $this->db->query($get_tahun)->row()->bulan;

        $insert_tblang_bantu = "
            insert into data$tahun.$params
            select '', a.kode_comp, a.kode_kota, a.kode_type, trim(b.customer_id), a.koderayon, a.nama_lang,
					a.namaarea as alamat1, a.namaarea as alamat2, '' as telp, 
					'' as kodepos,		
					'' as tgl,		
					'' as npwp,		
					'0' as bts_utang,		
					'0' as sales01,		
					'0' as sales02,		
					'0' as sales03,		
					'0' as sales04,		
					'0' as sales05,		
					'0' as sales06,		
					'0' as sales07,		
					'0' as sales08,		
					'0' as sales09,		
					'0' as sales10,		
					'0' as sales11,		
					'0' as sales12,		
					'0' as ket,		
					'0' as debit,		
					'0' as kredit,		
					a.kodesalur as kodesalur,		
					'0' as top,		
					'Y' as aktif,		
					'' as tgl_aktif,		
					'T' as ppn,		
					'0' as kode_lama,		
					'1' as jum_dok,		
					'0' as statjual,		
					'0' as limit1,		
					'' as tglnaktif,		
					'' as ALAMAT_WP,		
					'' as NILAI_PPN,		
					'' as NAMA_WP,		
					'' as NEWFLD,		
					a.nocab as nocab,		
					'' as kodelang_copy,		
					'' as id_provinsi,		
					'' as nama_provinsi,		
					'' as id_kota,		
					'' as nama_kota,		
					'' as id_kecamatan,		
					'' as nama_kecamatan,		
					'' as id_kelurahan,		
					'' as nama_kelurahan,		
					'' as credit_limit,		
					'' as tipe_bayar,		
					'' as phone,		
					'' AS last_updated,		
					'' as status_blacklist,		
					'' as status_payment,		
					'' as CUSTID,		
					'' as COMPID,		
					'' as LATITUDE,		
					'' as LONGITUDE,		
					'' as FOTO_DISP,		
					'' as FOTO_TOKO, '$signature'
            from data$tahun.fi a LEFT JOIN management_raw.raw_customer_gorontalo b on a.nama_lang = b.nama_customer
            where a.bulan = $bulan and concat(a.kode_comp, a.nocab) = '$site_code'
            GROUP BY concat(a.kode_comp, b.customer_id)

            union all
            
            select '', a.kode_comp, a.kode_kota, a.kode_type, trim(b.customer_id), a.koderayon, a.nama_lang,
					a.namaarea as alamat1, a.namaarea as alamat2, '' as telp, 
					'' as kodepos,		
					'' as tgl,		
					'' as npwp,		
					'0' as bts_utang,		
					'0' as sales01,		
					'0' as sales02,		
					'0' as sales03,		
					'0' as sales04,		
					'0' as sales05,		
					'0' as sales06,		
					'0' as sales07,		
					'0' as sales08,		
					'0' as sales09,		
					'0' as sales10,		
					'0' as sales11,		
					'0' as sales12,		
					'0' as ket,		
					'0' as debit,		
					'0' as kredit,		
					a.kodesalur as kodesalur,		
					'0' as top,		
					'Y' as aktif,		
					'' as tgl_aktif,		
					'T' as ppn,		
					'0' as kode_lama,		
					'1' as jum_dok,		
					'0' as statjual,		
					'0' as limit1,		
					'' as tglnaktif,		
					'' as ALAMAT_WP,		
					'' as NILAI_PPN,		
					'' as NAMA_WP,		
					'' as NEWFLD,		
					a.nocab as nocab,		
					'' as kodelang_copy,		
					'' as id_provinsi,		
					'' as nama_provinsi,		
					'' as id_kota,		
					'' as nama_kota,		
					'' as id_kecamatan,		
					'' as nama_kecamatan,		
					'' as id_kelurahan,		
					'' as nama_kelurahan,		
					'' as credit_limit,		
					'' as tipe_bayar,		
					'' as phone,		
					'' AS last_updated,		
					'' as status_blacklist,		
					'' as status_payment,		
					'' as CUSTID,		
					'' as COMPID,		
					'' as LATITUDE,		
					'' as LONGITUDE,		
					'' as FOTO_DISP,		
					'' as FOTO_TOKO, '$signature'
            from data$tahun.ri a LEFT JOIN management_raw.raw_customer_gorontalo b on a.nama_lang = b.nama_customer
            where a.bulan = $bulan and concat(a.kode_comp, a.nocab) = '$site_code'
            GROUP BY concat(a.kode_comp, b.customer_id)
        ";

        return $this->db->query($insert_tblang_bantu);
    }

    public function insert_tbkota_gorontalo($site_code, $signature){
        $get_tahun = "
            select * 
            from management_raw.log_upload a 
            where a.site_code = '$site_code' and a.signature = '$signature'
        ";
        $tahun = $this->db->query($get_tahun)->row()->tahun;
        $bulan = $this->db->query($get_tahun)->row()->bulan;

        $insert = "
            insert into data$tahun.tbkota
            select  KODE_COMP AS KODE_COMP, 		
                    a.kode_kota AS KODE_KOTA,		
                    'GORONTALO' AS NAMA_KOTA,		
                    a.nocab as nocab		
            from data$tahun.fi a 
            where concat(a.kode_comp, a.nocab) = '$site_code'
            group by KODE_COMP, KODE_KOTA
        ";

        return $this->db->query($insert);
    }

    public function update_fi($site_code, $signature){

        if ($site_code == 'GTO87') {
            $params_site_code = "raw_customer_gorontalo"; 
            $params1 = 'nama_lang';
            $params2 = 'nama_customer';
        }elseif ($site_code == 'PBNP9') {
            $params_site_code = "raw_customer_pangkalanbun"; 
            $params1 = 'kode_lang';
            $params2 = 'customer_id_nd6';
        }elseif ($site_code == 'SPTU4') {
            $params_site_code = "raw_customer_sampit"; 
            $params1 = 'kode_lang';
            $params2 = 'customer_id_nd6';
        }elseif ($site_code == 'PKRP8') {
            $params_site_code = "raw_customer_palangkaraya"; 
            $params1 = 'kode_lang';
            $params2 = 'customer_id_nd6';
        }
        
        $get_tahun = "
            select * 
            from management_raw.log_upload a 
            where a.site_code = '$site_code' and a.signature = '$signature'
        ";
        $tahun = $this->db->query($get_tahun)->row()->tahun;
        $bulan = $this->db->query($get_tahun)->row()->bulan;

        $query = "
                update data$tahun.fi a 
                set a.kode_lang = (select customer_id from management_raw.$params_site_code b
                where a.$params1 = b.$params2
                group by b.$params2),
                a.kode_type = (select kode_type from management_raw.$params_site_code b
                where a.$params1 = b.$params2
                group by b.$params2),
                a.kodesalur = (select kode_class from management_raw.$params_site_code b
                where a.$params1 = b.$params2
                group by b.$params2)
                where concat(a.kode_comp,a.nocab) = '$site_code' and a.bulan = $bulan
            ";
        $update = $this->db->query($query);

        if ($update) {
            return $update;
        }else{
            return array();
        }
    }

    public function update_kodeproduk_pangkalanbun($site_code, $signature){

        if ($site_code == 'PBNP9') {
            $params_site_code = "raw_pangkalanbun"; 
        }
        elseif ($site_code == 'SPTU4') {
            $params_site_code = "raw_sampit"; 
        }
        elseif ($site_code == 'PKRP8') {
            $params_site_code = "raw_palangkaraya"; 
        }

        $query = "
            update management_raw.$params_site_code a 
            set a.productid = concat(0,a.productid)
            where a.signature = '$signature' and length(a.productid) = 5 
        ";
        $update = $this->db->query($query);

        if ($update) {
            return $update;
        }else{
            return array();
        }
    }

    public function inner_kodeproduk_pangkalanbun($site_code, $signature){

        if ($site_code == 'PBNP9') {
            $params_site_code = "inner_raw_pangkalanbun"; 
            $params_site_code2 = "raw_pangkalanbun"; 
        }
        elseif ($site_code == 'SPTU4') {
            $params_site_code = "inner_raw_sampit"; 
            $params_site_code2 = "raw_sampit"; 
        }
        elseif ($site_code == 'PKRP8') {
            $params_site_code = "inner_raw_palangkaraya"; 
            $params_site_code2 = "raw_palangkaraya"; 
        }

        $query = "
            insert into management_raw.$params_site_code
            select '', '$site_code', '', '', a.*, b.supp, b.grupprod, '', '', ''
            from management_raw.$params_site_code2 a INNER JOIN (
                select a.kodeprod, a.supp, a.grupprod
                from mpm.tabprod a 
            )b on a.productid = b.kodeprod
            where a.signature = '$signature'
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        // die;
        $update = $this->db->query($query);
        if ($update) {
            return $update;
        }else{
            return array();
        }
    }

    public function update_namaprod_pangkalanbun($site_code, $signature){

        if ($site_code == 'PBNP9') {
            $params_site_code = "inner_raw_pangkalanbun"; 
        }
        elseif ($site_code == 'SPTU4') {
            $params_site_code = "inner_raw_sampit"; 
        }
        elseif ($site_code == 'PKRP8') {
            $params_site_code = "inner_raw_palangkaraya"; 
        }

        $query = "
            update management_raw.$params_site_code a 
            set a.product_descr = (
                select b.namaprod
                from mpm.tabprod b 
                where a.productid = b.kodeprod
            ), a.map_divisi = (
                select b.new_divisi
                from mpm.tabprod b 
                where a.productid = b.kodeprod
            ), a.map_group = (
                select b.new_group
                from mpm.tabprod b 
                where a.productid = b.kodeprod
            ), a.map_subgroup = (
                select b.new_subgroup
                from mpm.tabprod b 
                where a.productid = b.kodeprod
            )
            where a.signature = '$signature'
        ";
        $update = $this->db->query($query);
        if ($update) {
            return $update;
        }else{
            return array();
        }
    }

    public function update_tanggal_pangkalanbun($site_code, $signature){
        
        if ($site_code == 'PBNP9') {
            $params_site_code = "inner_raw_pangkalanbun"; 
        }
        elseif ($site_code == 'SPTU4') {
            $params_site_code = "inner_raw_sampit"; 
        }
        elseif ($site_code == 'PKRP8') {
            $params_site_code = "inner_raw_palangkaraya"; 
        }

        $query = "
            update management_raw.$params_site_code a 
            set a.tanggal_sales = str_to_date(a.tanggal_sales,'%m/%d/%Y')
            where a.signature = '$signature'
        ";
        $update = $this->db->query($query);
        if ($update) {
            return $update;
        }else{
            return array();
        }
    }

    public function insert_fi_pangkalanbun($site_code, $signature){
        $get_tahun = "
            select * 
            from management_raw.log_upload a 
            where a.site_code = '$site_code' and a.signature = '$signature'
        ";
        $tahun = $this->db->query($get_tahun)->row()->tahun;
        $bulan = $this->db->query($get_tahun)->row()->bulan;


        $insert = "
        insert data$tahun.fi
        select 		
        '08' as KDOKJDI,		
        nosales as NODOKJDI,		
        nosales as NODOKACU,		
        tanggal_sales as TGLDOKJDI,		
        salesmanid as KODESALES,		
        'PBN' as KODE_COMP,		
        '' as KODE_KOTA,		
        '' AS KODE_TYPE,		
        a.customerid as KODE_LANG,		
        '' as KODERAYON,		
        productid as KODEPROD,		
        a.SUPP,		
        RIGHT(tanggal_sales,2) as HRDOK,		
        '$bulan' as BLNDOK,		
        LEFT(tanggal_sales,4) as THNDOK,		
        b.NAMAPROD as NAMAPROD,		
        b.GRUPPROD as GROUPPROD,		
        a.qty as BANYAK,		
        a.harga as HARGA,		
        (rp_cabang + rp_prinsipal + rp_xtra + rp_cash) as POTONGAN,		
        bruto as TOT1,		
        '' as JUM_PROMO, 		
        '' as KETERANGAN,  		 		
        '' as USER_ISI,		
        '' as JAM_ISI,		
        '' as TGL_ISI, 		
        '' as USER_EDIT, 		
        '' as JAM_EDIT, 		
        '' as TGL_EDIT,		
        '' as USER_DEL, 		
        '' as JAM_DEL, 		
        '' as TGL_DEL, 		
        '' as NO, 		
        '' as BACKUP, 		
        '' as NO_URUT, 		
        'PST' as KODE_GDG, 		
        '' as NAMA_GDG, 		
        '' AS KODESALUR,		
        '' as KODEBONUS,		
        '' as NAMABONUS, 		
        '' as GRUPBONUS, 		
        '0' as UNITBONUS, 		
        nama_salesman as LAMPIRAN, 		
        '' as H_BELI, 		
        '' as KODEAREA, 		
        '' as NAMAAREA, 		
        '' as PINJAM, 		
        '' as JUALBANYAK, 		
        '' as JUALPINJAM, 		
        '' as HARGA_EXCL, 		
        '' as TOT1_EXCL, 		
        a.nama_customer as NAMA_LANG,		
        'P9' as NOCAB,		
        '$bulan' as BULAN,		
        siteid,		
        ' ' as qty1,		
        ' ' as qty2,		
        qty as qty3,		
        '0' as qty_bonus,		
        '0' as flag_bonus,		
        '' as disc_persen,	
        '' as disc_rp,		
        '' as disc_value,		
        disc_cabang,		
        disc_prinsipal,		
        disc_xtra,		
        rp_cabang as rp_cabang,		
        rp_prinsipal as rp_prinsipal,		
        rp_xtra as rp_xtra,		
        ' ' as bonus,
        concat('11',a.supp) as prinsipalid,		
        '' as ex_no_sales,		
        '' as status_retur,		
        '' as ref,		
        '' as term_payment,		
        '' as tipe_kl		
                
        from management_raw.inner_raw_pangkalanbun a	
        INNER JOIN mpm.tabprod b on a.productid = b.KODEPROD
        where a.signature = '$signature' and year(a.tanggal_sales) = $tahun and month(a.tanggal_sales) = $bulan and a.flag_bonus = 0

        union all

        select 		
        '08' as KDOKJDI,		
        nosales as NODOKJDI,		
        nosales as NODOKACU,		
        tanggal_sales as TGLDOKJDI,		
        salesmanid as KODESALES,		
        'PBN' as KODE_COMP,		
        '' as KODE_KOTA,		
        '' AS KODE_TYPE,		
        a.customerid as KODE_LANG,		
        '' as KODERAYON,		
        productid as KODEPROD,		
        a.SUPP,		
        RIGHT(tanggal_sales,2) as HRDOK,		
        '$bulan' as BLNDOK,		
        LEFT(tanggal_sales,4) as THNDOK,		
        b.NAMAPROD as NAMAPROD,		
        b.GRUPPROD as GROUPPROD,		
        '' as BANYAK,		
        '' as HARGA,		
        '' as POTONGAN,		
        '' as TOT1,		
        '' as JUM_PROMO, 		
        '' as KETERANGAN,  		 		
        '' as USER_ISI,		
        '' as JAM_ISI,		
        '' as TGL_ISI, 		
        '' as USER_EDIT, 		
        '' as JAM_EDIT, 		
        '' as TGL_EDIT,		
        '' as USER_DEL, 		
        '' as JAM_DEL, 		
        '' as TGL_DEL, 		
        '' as NO, 		
        '' as BACKUP, 		
        '' as NO_URUT, 		
        'PST' as KODE_GDG, 		
        '' as NAMA_GDG, 		
        '' AS KODESALUR,		
        '' as KODEBONUS,		
        '' as NAMABONUS, 		
        '' as GRUPBONUS, 		
        qty as UNITBONUS, 		
        a.nama_salesman as LAMPIRAN, 		
        '' as H_BELI, 		
        '' as KODEAREA, 		
        '' as NAMAAREA, 		
        '' as PINJAM, 		
        '' as JUALBANYAK, 		
        '' as JUALPINJAM, 		
        '' as HARGA_EXCL, 		
        '' as TOT1_EXCL, 		
        a.nama_customer as NAMA_LANG,		
        'P9' as NOCAB,		
        '$bulan' as BULAN,		
        siteid,		
        '' as qty1,		
        '' as qty2,		
        '0' as qty3,		
        qty as qty_bonus,		
        '1' as flag_bonus,		
        '' as disc_persen,	
        '' as disc_rp,		
        '' as disc_value,		
        '' as disc_cabang,		
        '' as disc_prinsipal,		
        '' as disc_xtra,		
        '' as rp_cabang,		
        '' as rp_prinsipal,		
        '' as rp_xtra,		
        '' as bonus,
        concat('11',a.supp) as prinsipalid,		
        '' as ex_no_sales,		
        '' as status_retur,		
        '' as ref,		
        '' as term_payment,		
        '' as tipe_kl		
                
        from management_raw.inner_raw_pangkalanbun a	
        INNER JOIN mpm.tabprod b on a.productid = b.KODEPROD 	
        where a.signature = '$signature' and year(a.tanggal_sales) = $tahun and month(a.tanggal_sales) = $bulan and a.flag_bonus = 1

        ";

        return $this->db->query($insert);

    }

    public function insert_tbkota_pangkalanbun($site_code, $signature){
        $get_tahun = "
            select * 
            from management_raw.log_upload a 
            where a.site_code = '$site_code' and a.signature = '$signature'
        ";
        $tahun = $this->db->query($get_tahun)->row()->tahun;
        $bulan = $this->db->query($get_tahun)->row()->bulan;

        $insert = "
            insert into data$tahun.tbkota
            select  KODE_COMP AS KODE_COMP, 		
                    a.kode_kota AS KODE_KOTA,		
                    b.nama_kota AS NAMA_KOTA,		
                    a.nocab as nocab
            from
            (
                select  KODE_COMP AS KODE_COMP, 		
                    a.kode_kota AS KODE_KOTA,		
                    a.nocab as nocab		
                from data$tahun.fi a 
                where concat(a.kode_comp, a.nocab) = '$site_code'
                group by KODE_COMP, KODE_KOTA
            )a left join
            (
            select a.kode_kota, a.nama_kota from data$tahun.tblang a
            where concat(a.kode_comp, a.nocab) = '$site_code'
            group by KODE_COMP, KODE_KOTA
            )b on a.kode_kota = b.kode_kota
        ";

        return $this->db->query($insert);
    }

    public function insert_mpm_upload_pangkalanbun($site_code, $signature, $omzet = '', $status_closing = 0){

        if ($site_code == 'PBNP9') {
            $params_site_code = "inner_raw_pangkalanbun"; 
        }
        elseif ($site_code == 'SPTU4') {
            $params_site_code = "inner_raw_sampit"; 
        }
        elseif ($site_code == 'PKRP8') {
            $params_site_code = "inner_raw_palangkaraya"; 
        }

        $query_tanggal = "
            select  a.tanggal_sales, if(length(max(day(a.tanggal_sales))) = 1, concat('0',max(day(a.tanggal_sales))), max(day(a.tanggal_sales))) as hari, if(length(max(month(a.tanggal_sales))) = 1, concat('0',max(month(a.tanggal_sales))), max(month(a.tanggal_sales))) as bulan, max(year(a.tanggal_sales)) as tahun  
            from management_raw.$params_site_code a
            where a.signature = '$signature'
        ";

        // die;

        $get_hari = $this->db->query($query_tanggal)->row()->hari;
        $get_bulan = $this->db->query($query_tanggal)->row()->bulan;
        $get_tahun = $this->db->query($query_tanggal)->row()->tahun;

        // die;
        $created_at = $this->model_outlet_transaksi->timezone();

        // die;

        $cek_userid  = "
            select id
            from mpm.user a
            where a.username = left('$site_code',3)
        ";

        // die;

        $userid = $this->db->query($cek_userid)->row()->id;

        $insert = [
            'userid'            => $userid,
            'lastupload'        => $created_at,
            'filename'          => 'NON SDS',
            'tanggal'           => $get_hari,
            'bulan'             => $get_bulan,
            'tahun'             => $get_tahun,
            'status'            => 1,
            'status_closing'    => $status_closing,
            'omzet'             => $omzet,
            'flag_check'        => 0
        ];

        return $this->db->insert('mpm.upload', $insert);
    }

    public function get_customer_pangkalanbun($site_code){
        if ($site_code == 'PBNP9') {
            $params_site_code = "raw_customer_pangkalanbun"; 
        }
        elseif ($site_code == 'SPTU4') {
            $params_site_code = "raw_customer_sampit"; 
        }
        elseif ($site_code == 'PKRP8') {
            $params_site_code = "raw_customer_palangkaraya"; 
        }

        $query = "
            select *
            from management_raw.$params_site_code a 
        ";
        return $this->db->query($query);
    }

    public function insert_fi_sampit($site_code, $signature){
        $get_tahun = "
            select * 
            from management_raw.log_upload a 
            where a.site_code = '$site_code' and a.signature = '$signature'
        ";
        $tahun = $this->db->query($get_tahun)->row()->tahun;
        $bulan = $this->db->query($get_tahun)->row()->bulan;


        $insert = "
        insert data$tahun.fi
        select 		
        '08' as KDOKJDI,		
        nosales as NODOKJDI,		
        nosales as NODOKACU,		
        tanggal_sales as TGLDOKJDI,		
        salesmanid as KODESALES,		
        'SPT' as KODE_COMP,		
        '' as KODE_KOTA,		
        '' AS KODE_TYPE,		
        a.customerid as KODE_LANG,		
        '' as KODERAYON,		
        productid as KODEPROD,		
        a.SUPP,		
        RIGHT(tanggal_sales,2) as HRDOK,		
        '$bulan' as BLNDOK,		
        LEFT(tanggal_sales,4) as THNDOK,		
        b.NAMAPROD as NAMAPROD,		
        b.GRUPPROD as GROUPPROD,		
        a.qty as BANYAK,		
        a.harga as HARGA,		
        (rp_cabang + rp_prinsipal + rp_xtra + rp_cash) as POTONGAN,		
        bruto as TOT1,		
        '' as JUM_PROMO, 		
        '' as KETERANGAN,  		 		
        '' as USER_ISI,		
        '' as JAM_ISI,		
        '' as TGL_ISI, 		
        '' as USER_EDIT, 		
        '' as JAM_EDIT, 		
        '' as TGL_EDIT,		
        '' as USER_DEL, 		
        '' as JAM_DEL, 		
        '' as TGL_DEL, 		
        '' as NO, 		
        '' as BACKUP, 		
        '' as NO_URUT, 		
        'PST' as KODE_GDG, 		
        '' as NAMA_GDG, 		
        '' AS KODESALUR,		
        '' as KODEBONUS,		
        '' as NAMABONUS, 		
        '' as GRUPBONUS, 		
        '0' as UNITBONUS, 		
        nama_salesman as LAMPIRAN, 		
        '' as H_BELI, 		
        '' as KODEAREA, 		
        '' as NAMAAREA, 		
        '' as PINJAM, 		
        '' as JUALBANYAK, 		
        '' as JUALPINJAM, 		
        '' as HARGA_EXCL, 		
        '' as TOT1_EXCL, 		
        a.nama_customer as NAMA_LANG,		
        'U4' as NOCAB,		
        '$bulan' as BULAN,		
        siteid,		
        ' ' as qty1,		
        ' ' as qty2,		
        qty as qty3,		
        '0' as qty_bonus,		
        '0' as flag_bonus,		
        '' as disc_persen,	
        '' as disc_rp,		
        '' as disc_value,		
        disc_cabang,		
        disc_prinsipal,		
        disc_xtra,		
        rp_cabang as rp_cabang,		
        rp_prinsipal as rp_prinsipal,		
        rp_xtra as rp_xtra,		
        ' ' as bonus,
        concat('11',a.supp) as prinsipalid,		
        '' as ex_no_sales,		
        '' as status_retur,		
        '' as ref,		
        '' as term_payment,		
        '' as tipe_kl		
                
        from management_raw.inner_raw_sampit a	
        INNER JOIN mpm.tabprod b on a.productid = b.KODEPROD
        where a.signature = '$signature' and year(a.tanggal_sales) = $tahun and month(a.tanggal_sales) = $bulan and a.flag_bonus = 0

        union all

        select 		
        '08' as KDOKJDI,		
        nosales as NODOKJDI,		
        nosales as NODOKACU,		
        tanggal_sales as TGLDOKJDI,		
        salesmanid as KODESALES,		
        'SPT' as KODE_COMP,		
        '' as KODE_KOTA,		
        '' AS KODE_TYPE,		
        a.customerid as KODE_LANG,		
        '' as KODERAYON,		
        productid as KODEPROD,		
        a.SUPP,		
        RIGHT(tanggal_sales,2) as HRDOK,		
        '$bulan' as BLNDOK,		
        LEFT(tanggal_sales,4) as THNDOK,		
        b.NAMAPROD as NAMAPROD,		
        b.GRUPPROD as GROUPPROD,		
        '' as BANYAK,		
        '' as HARGA,		
        '' as POTONGAN,		
        '' as TOT1,		
        '' as JUM_PROMO, 		
        '' as KETERANGAN,  		 		
        '' as USER_ISI,		
        '' as JAM_ISI,		
        '' as TGL_ISI, 		
        '' as USER_EDIT, 		
        '' as JAM_EDIT, 		
        '' as TGL_EDIT,		
        '' as USER_DEL, 		
        '' as JAM_DEL, 		
        '' as TGL_DEL, 		
        '' as NO, 		
        '' as BACKUP, 		
        '' as NO_URUT, 		
        'PST' as KODE_GDG, 		
        '' as NAMA_GDG, 		
        '' AS KODESALUR,		
        '' as KODEBONUS,		
        '' as NAMABONUS, 		
        '' as GRUPBONUS, 		
        qty as UNITBONUS, 		
        a.nama_salesman as LAMPIRAN, 		
        '' as H_BELI, 		
        '' as KODEAREA, 		
        '' as NAMAAREA, 		
        '' as PINJAM, 		
        '' as JUALBANYAK, 		
        '' as JUALPINJAM, 		
        '' as HARGA_EXCL, 		
        '' as TOT1_EXCL, 		
        a.nama_customer as NAMA_LANG,		
        'U4' as NOCAB,		
        '$bulan' as BULAN,		
        siteid,		
        '' as qty1,		
        '' as qty2,		
        '0' as qty3,		
        qty as qty_bonus,		
        '1' as flag_bonus,		
        '' as disc_persen,	
        '' as disc_rp,		
        '' as disc_value,		
        '' as disc_cabang,		
        '' as disc_prinsipal,		
        '' as disc_xtra,		
        '' as rp_cabang,		
        '' as rp_prinsipal,		
        '' as rp_xtra,		
        '' as bonus,
        concat('11',a.supp) as prinsipalid,		
        '' as ex_no_sales,		
        '' as status_retur,		
        '' as ref,		
        '' as term_payment,		
        '' as tipe_kl		
                
        from management_raw.inner_raw_sampit a	
        INNER JOIN mpm.tabprod b on a.productid = b.KODEPROD 	
        where a.signature = '$signature' and year(a.tanggal_sales) = $tahun and month(a.tanggal_sales) = $bulan and a.flag_bonus = 1

        ";

        return $this->db->query($insert);

    }

    public function insert_fi_palangkaraya($site_code, $signature){
        $get_tahun = "
            select * 
            from management_raw.log_upload a 
            where a.site_code = '$site_code' and a.signature = '$signature'
        ";
        $tahun = $this->db->query($get_tahun)->row()->tahun;
        $bulan = $this->db->query($get_tahun)->row()->bulan;


        $insert = "
        insert data$tahun.fi
        select 		
        '08' as KDOKJDI,		
        nosales as NODOKJDI,		
        nosales as NODOKACU,		
        tanggal_sales as TGLDOKJDI,		
        salesmanid as KODESALES,		
        'PKR' as KODE_COMP,		
        '' as KODE_KOTA,		
        '' AS KODE_TYPE,		
        a.customerid as KODE_LANG,		
        '' as KODERAYON,		
        productid as KODEPROD,		
        a.SUPP,		
        RIGHT(tanggal_sales,2) as HRDOK,		
        '$bulan' as BLNDOK,		
        LEFT(tanggal_sales,4) as THNDOK,		
        b.NAMAPROD as NAMAPROD,		
        b.GRUPPROD as GROUPPROD,		
        a.qty as BANYAK,		
        a.harga as HARGA,		
        (rp_cabang + rp_prinsipal + rp_xtra + rp_cash) as POTONGAN,		
        bruto as TOT1,		
        '' as JUM_PROMO, 		
        '' as KETERANGAN,  		 		
        '' as USER_ISI,		
        '' as JAM_ISI,		
        '' as TGL_ISI, 		
        '' as USER_EDIT, 		
        '' as JAM_EDIT, 		
        '' as TGL_EDIT,		
        '' as USER_DEL, 		
        '' as JAM_DEL, 		
        '' as TGL_DEL, 		
        '' as NO, 		
        '' as BACKUP, 		
        '' as NO_URUT, 		
        'PST' as KODE_GDG, 		
        '' as NAMA_GDG, 		
        '' AS KODESALUR,		
        '' as KODEBONUS,		
        '' as NAMABONUS, 		
        '' as GRUPBONUS, 		
        '0' as UNITBONUS, 		
        nama_salesman as LAMPIRAN, 		
        '' as H_BELI, 		
        '' as KODEAREA, 		
        '' as NAMAAREA, 		
        '' as PINJAM, 		
        '' as JUALBANYAK, 		
        '' as JUALPINJAM, 		
        '' as HARGA_EXCL, 		
        '' as TOT1_EXCL, 		
        a.nama_customer as NAMA_LANG,		
        'P8' as NOCAB,		
        '$bulan' as BULAN,		
        siteid,		
        ' ' as qty1,		
        ' ' as qty2,		
        qty as qty3,		
        '0' as qty_bonus,		
        '0' as flag_bonus,		
        '' as disc_persen,	
        '' as disc_rp,		
        '' as disc_value,		
        disc_cabang,		
        disc_prinsipal,		
        disc_xtra,		
        rp_cabang as rp_cabang,		
        rp_prinsipal as rp_prinsipal,		
        rp_xtra as rp_xtra,		
        ' ' as bonus,
        concat('11',a.supp) as prinsipalid,		
        '' as ex_no_sales,		
        '' as status_retur,		
        '' as ref,		
        '' as term_payment,		
        '' as tipe_kl		
                
        from management_raw.inner_raw_palangkaraya a	
        INNER JOIN mpm.tabprod b on a.productid = b.KODEPROD
        where a.signature = '$signature' and year(a.tanggal_sales) = $tahun and month(a.tanggal_sales) = $bulan and a.flag_bonus = 0

        union all

        select 		
        '08' as KDOKJDI,		
        nosales as NODOKJDI,		
        nosales as NODOKACU,		
        tanggal_sales as TGLDOKJDI,		
        salesmanid as KODESALES,		
        'PKR' as KODE_COMP,		
        '' as KODE_KOTA,		
        '' AS KODE_TYPE,		
        a.customerid as KODE_LANG,		
        '' as KODERAYON,		
        productid as KODEPROD,		
        a.SUPP,		
        RIGHT(tanggal_sales,2) as HRDOK,		
        '$bulan' as BLNDOK,		
        LEFT(tanggal_sales,4) as THNDOK,		
        b.NAMAPROD as NAMAPROD,		
        b.GRUPPROD as GROUPPROD,		
        '' as BANYAK,		
        '' as HARGA,		
        '' as POTONGAN,		
        '' as TOT1,		
        '' as JUM_PROMO, 		
        '' as KETERANGAN,  		 		
        '' as USER_ISI,		
        '' as JAM_ISI,		
        '' as TGL_ISI, 		
        '' as USER_EDIT, 		
        '' as JAM_EDIT, 		
        '' as TGL_EDIT,		
        '' as USER_DEL, 		
        '' as JAM_DEL, 		
        '' as TGL_DEL, 		
        '' as NO, 		
        '' as BACKUP, 		
        '' as NO_URUT, 		
        'PST' as KODE_GDG, 		
        '' as NAMA_GDG, 		
        '' AS KODESALUR,		
        '' as KODEBONUS,		
        '' as NAMABONUS, 		
        '' as GRUPBONUS, 		
        qty as UNITBONUS, 		
        a.nama_salesman as LAMPIRAN, 		
        '' as H_BELI, 		
        '' as KODEAREA, 		
        '' as NAMAAREA, 		
        '' as PINJAM, 		
        '' as JUALBANYAK, 		
        '' as JUALPINJAM, 		
        '' as HARGA_EXCL, 		
        '' as TOT1_EXCL, 		
        a.nama_customer as NAMA_LANG,		
        'P8' as NOCAB,		
        '$bulan' as BULAN,		
        siteid,		
        '' as qty1,		
        '' as qty2,		
        '0' as qty3,		
        qty as qty_bonus,		
        '1' as flag_bonus,		
        '' as disc_persen,	
        '' as disc_rp,		
        '' as disc_value,		
        '' as disc_cabang,		
        '' as disc_prinsipal,		
        '' as disc_xtra,		
        '' as rp_cabang,		
        '' as rp_prinsipal,		
        '' as rp_xtra,		
        '' as bonus,
        concat('11',a.supp) as prinsipalid,		
        '' as ex_no_sales,		
        '' as status_retur,		
        '' as ref,		
        '' as term_payment,		
        '' as tipe_kl		
                
        from management_raw.inner_raw_palangkaraya a	
        INNER JOIN mpm.tabprod b on a.productid = b.KODEPROD 	
        where a.signature = '$signature' and year(a.tanggal_sales) = $tahun and month(a.tanggal_sales) = $bulan and a.flag_bonus = 1

        ";

        return $this->db->query($insert);

    }

}