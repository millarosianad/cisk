<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_management_retur extends CI_Model 
{
    public function get_master_dbsls($customerid = '', $productid ='', $limit = '', $from = '', $to = ''){

        if ($customerid) {
            $params_customerid = "where a.customerid = '$customerid'";
        }else{
            $params_customerid = "";
        }

        if ($productid) {
            $params_productid = "and a.productid = '$productid'";
        }else{
            $params_productid = "";
        }

        if ($limit) {
            $params_limit = "limit $limit";
        }else{
            $params_limit = "";
        }

        if ($from == '' || $to == '') {
            $params_tahun = "";
        }else{
            // $params_tahun = "and (DATE_FORMAT(a.tanggal, '%Y-%m') BETWEEN '$from' and '$to') and (DATE_FORMAT(a.tanggal, '%Y-%m') BETWEEN '$from' and '$to')";
            $params_tahun = "and (DATE_FORMAT(a.tanggal, '%Y-%m') BETWEEN '$from' and '$to')";
        }

        $query = "
            select * from management_retur.master_dbsls a
            $params_customerid $params_productid $params_limit $params_tahun
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        // die;

        return $this->db->query($query);
    }

    public function get_ajuan_retur($versi = '0'){
        if ($versi == 2) {
            $db = "management_inventory.pengajuan_retur";
            $query = "
                select 	a.id, a.no_pengajuan, a.site_code, a.nama, a.supp, a.tanggal_pengajuan, a.`status`,
                        a.nama_status, a.file_principal, a.tanggal_approval, a.keterangan_lain, a.signature,
                        b.branch_name, b.nama_comp, 
                        if(a.supp ='001-herbal', 'DELTOMED-HERBAL',if(a.supp='001-herbana','DELTOMED-HERBANA',c.namasupp)) as principal, count(signature_draft_nota_retur) as count_nota_retur, a.no_terima_barang as no_terima
                from management_inventory.pengajuan_retur a LEFT JOIN 
                (
                    select concat(a.kode_comp, a.nocab) as site_code, a.branch_name, a.nama_comp
                    from mpm.tbl_tabcomp a 
                    where a.status = 1
                    GROUP BY concat(a.kode_comp, a.nocab)
                )b on a.site_code = b.site_code left join 
                (
                    select a.supp, a.namasupp
                    from mpm.tabsupp a 
                )c on a.supp = c.supp left join 
                (
                    SELECT a.signature_ajuan_retur, a.signature_draft_nota_retur
                    from management_retur.ajuan_vs_nota_retur a
                )d on a.signature = d.signature_ajuan_retur
                where a.`status` in (7, 8, 9, 10)
                GROUP BY a.id
                ORDER BY a.tanggal_pengajuan desc
                limit 4000
                ";
        } else {
            $query = "
                select 	a.id, a.no_pengajuan, a.site_code, a.nama, a.supp, a.tanggal_pengajuan, a.`status`,
                        a.nama_status, a.file_principal, a.tanggal_approval, a.keterangan_lain, a.signature,
                        b.branch_name, b.nama_comp, 
                        if(a.supp ='001-herbal', 'DELTOMED-HERBAL',if(a.supp='001-herbana','DELTOMED-HERBANA',c.namasupp)) as principal, count(signature_draft_nota_retur) as count_nota_retur, a.no_terima
                from db_temp.t_temp_pengajuan_retur a LEFT JOIN 
                (
                    select concat(a.kode_comp, a.nocab) as site_code, a.branch_name, a.nama_comp
                    from mpm.tbl_tabcomp a 
                    where a.status = 1
                    GROUP BY concat(a.kode_comp, a.nocab)
                )b on a.site_code = b.site_code left join 
                (
                    select a.supp, a.namasupp
                    from mpm.tabsupp a 
                )c on a.supp = c.supp left join 
                (
                    SELECT a.signature_ajuan_retur, a.signature_draft_nota_retur
                    from management_retur.ajuan_vs_nota_retur a
                )d on a.signature = d.signature_ajuan_retur
                where a.`status` in (7, 8, 9, 10)
                GROUP BY a.id
                ORDER BY a.tanggal_pengajuan desc
                limit 4000
                ";
        }
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        return $this->db->query($query);
    }

    public function get_id_pengajuan_by_signature($signature, $versi ='0'){
        if ($versi == 2) {
            $db = "management_inventory.pengajuan_retur";
        } else {
            $db = "db_temp.t_temp_pengajuan_retur";
        }
        
        $query = "
        select *
        from $db a 
        where a.signature = '$signature'
    ";
        
        // print_r($query);
        return $this->db->query($query);
    }

    public function get_product_ajuan_retur($id_ajuan, $sum = '', $signature, $versi = '0'){

        if ($sum) {
            if ($versi == 2) {
                $params_header = "a.id, a.kodeprod, c.namaprod, a.batch_number, a.expired_date, sum(a.qty_approval) as qty_approval,
                a.alasan, a.satuan, a.nama_outlet, a.keterangan, a.kode_produksi, a.`status`,
                a.nama_status, a.deskripsi, b.qty_nota_retur, (sum(a.qty_lpk) - b.qty_nota_retur) as selisih, sum(a.qty_lpk) as qty_lpk";
                $params_group_by = "GROUP BY a.kodeprod";
            } else {
                $params_header = "a.id, a.kodeprod, c.namaprod, a.batch_number, a.expired_date, sum(a.jumlah) as jumlah,
                a.alasan, a.satuan, a.nama_outlet, a.keterangan, a.kode_produksi, a.`status`,
                a.nama_status, a.deskripsi, b.qty_nota_retur, (sum(a.qty_lpk) - b.qty_nota_retur) as selisih, sum(a.qty_lpk) as qty_lpk";
                $params_group_by = "GROUP BY a.kodeprod";
            }
        }else{
            $params_header = "a.*, c.namaprod";
            $params_group_by = "";
        }

        if ($versi == 2) {
            $db = "management_inventory.pengajuan_retur_detail";
        } else {
            $db = "db_temp.t_temp_produk_pengajuan_retur";
        }
        

        //status = 3 artinya sudah verified oleh linda
        $query = "
            select $params_header
            from $db a LEFT JOIN (
                select a.kodeprod, a.qty_nota_retur
                from management_retur.temp_draft_nota_retur a
                where a.signature_ajuan_retur = '$signature'
            )b on a.kodeprod = b.kodeprod
            LEFT JOIN (
                select a.kodeprod, a.namaprod
                from mpm.tabprod a
            )c on a.kodeprod = c.kodeprod
            where a.id_pengajuan in ($id_ajuan) and a.deleted is null and a.status = 3
            $params_group_by
        ";
        
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        return $this->db->query($query);
    }

    public function get_produk_ajuan_retur_by_id($id){
        $query = "
            select *
            from db_temp.t_temp_produk_pengajuan_retur a 
            where a.deleted is null and a.id = $id
        ";
        return $this->db->query($query);
    }

    public function get_no_seri_pajak($customerid, $productid){

        $query = "
            select *
            from management_retur.master_dbsls a 
            where a.customerid = '$customerid' and a.productid = '$productid'
        ";

        return $this->db->query($query);

    }

    public function get_raw_rekomendasi($signature){
        $query = "
        select a.customerid, a.nama_customer, a.no_seri_pajak, a.productid, a.qty_kecil, a.retur, a.qty_ajuan_retur, a.selisih_qty, a.qty_lpk, b.namaprod
        from management_retur.log_search_master_dbsls a left join (
            select kodeprod, namaprod
            from mpm.tabprod
        )b on a.productid = b.kodeprod
        where a.signature = '$signature'
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        return $this->db->query($query);
    }

    public function get_rekomendasi($signature){
        // $query = "
        // select a.tanggal,a.customerid, a.nama_customer, a.no_seri_pajak, a.ref, a.productid, a.qty_kecil, a.retur, a.qty_ajuan_retur, a.selisih_qty, count(a.no_seri_pajak) as count_no_seri_pajak, sum(a.selisih_qty) as sum_selisih_qty
        // from management_retur.log_search_master_dbsls a 
        // where a.signature = '$signature'
        // GROUP BY a.customerid, a.no_seri_pajak
        // ORDER BY count(a.no_seri_pajak) desc, sum(a.selisih_qty) asc
        // ";

        $query = "
            select 	a.tanggal,a.customerid, a.nama_customer, a.no_seri_pajak, a.ref, a.productid, 
                    a.qty_kecil, a.retur, a.qty_ajuan_retur, a.selisih_qty, 
                    count(a.no_seri_pajak) as count_no_seri_pajak, sum(a.selisih_qty) as sum_selisih_qty
            from    management_retur.log_search_master_dbsls a 
            where   a.signature = '$signature' and a.no_seri_pajak not in (
                select 	a.no_seri_pajak
                from 	management_retur.log_search_master_dbsls a 
                where 	a.signature = '$signature' and a.selisih_qty > 0
                GROUP BY a.no_seri_pajak
            )
            GROUP BY a.customerid, a.no_seri_pajak
            ORDER BY count(a.no_seri_pajak) desc, sum(a.selisih_qty) asc
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        return $this->db->query($query);
    }

    public function get_draft_nota_retur($signature, $no_seri_pajak, $customerid){
        $query = "
            select a.id, a.customerid, a.tanggal, a.productid, a.nama_customer, a.nama_product, a.brandid, a.nama_brand, a.ref, 
            a.no_seri_pajak, a.qty_kecil, a.retur, if(a.brandid = 11005, a.jual, a.beli) as beli, a.jual, a.disc_cabang, b.*, c.kode_prc,
            d.no_inv, count(d.no_sj) as jum_row,
            IF(a.brandid = 11005,
			CASE
				WHEN CONCAT(year(a.tanggal),IF(LENGTH(MONTH(a.tanggal)) = 1, CONCAT(0, MONTH(a.tanggal)), MONTH(a.tanggal))) <= 202201 THEN
					15
					WHEN CONCAT(year(a.tanggal),IF(LENGTH(MONTH(a.tanggal)) = 1, CONCAT(0, MONTH(a.tanggal)), MONTH(a.tanggal))) <= 202212 THEN
					14.5
				ELSE
					14
			END, a.disc_beli) as disc_beli
            from management_retur.master_dbsls a INNER JOIN (
                select 	a.customerid, a.nama_customer, a.no_seri_pajak, a.productid, 
                        a.qty_kecil, a.qty_ajuan_retur, a.selisih_qty, a.retur, a.brandid, a.qty_lpk, a.ref, a.tanggal
                from management_retur.log_search_master_dbsls a
                where a.signature = '$signature' and a.no_seri_pajak = '$no_seri_pajak'
            )b on a.productid = b.productid and a.no_seri_pajak = b.no_seri_pajak and a.customerid = b.customerid left join (
                select a.kodeprod, a.namaprod, a.kode_prc
                from mpm.tabprod a
            )c on a.productid = c.kodeprod
            left join
            (
                select a.* 
                from management_retur.temp_pajak_masukan a
            )d on a.ref = d.no_sj
            where a.customerid = '$customerid' and a.no_seri_pajak = '$no_seri_pajak'
            group by a.id
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        return $this->db->query($query);
    }

    public function get_company_by_signature($signature, $versi = '0'){
        if ($versi == '2') {
            $db = "management_inventory.pengajuan_retur";
        } else {
            $db = "db_temp.t_temp_pengajuan_retur";
        }
        
        $query = "
        select a.site_code, b.branch_name, b.nama_comp
        from $db a LEFT JOIN 
        (
            select concat(a.kode_comp, a.nocab) as site_code, a.branch_name, a.nama_comp
            from mpm.tbl_tabcomp a 
            where a.`status` = 1
            GROUP BY concat(a.kode_comp, a.nocab)
        )b on a.site_code = b.site_code
        where a.signature = '$signature'
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        // die;

        return $this->db->query($query);
    }

    public function get_temp_draft_nota_retur($signature){

        $query = "
            select *
            from management_retur.temp_draft_nota_retur a
            where a.signature = '$signature' and a.created_at = (
                select max(created_at)
                from management_retur.temp_draft_nota_retur a
                where a.signature = '$signature' 
            )
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        return $this->db->query($query);
    }

    public function get_customerid_byid($userid){
        $query = "
            select a.id, a.username, a.company, a.email, a.npwp, a.nama_wp, a.alamat_wp, concat('1', a.kode_lang) as kode_lang
            from mpm.user a 
            where a.id = $userid
        ";
        return $this->db->query($query);
    }

    public function get_noajuan($signature_ajuan_retur, $versi = '0'){
        if ($versi == '2') {
            $db = "management_inventory.pengajuan_retur";
        } else {
            $db = "db_temp.t_temp_pengajuan_retur";
        }
        
        $query = "
            select *
            from $db a 
            where a.signature = '$signature_ajuan_retur'
        ";

        return $this->db->query($query);
    }

    public function get_nota_retur(){
        $query = "
            select *
            from management_retur.temp_draft_nota_retur a
        ";
        // print_r($query);
        return $this->db->query($query);
    }

    public function cek_qty_lpk($id_ajuan, $versi = '0'){
        if ($versi == 2) {
            $db = "management_inventory.pengajuan_retur_detail";
        } else {
            $db = "db_temp.t_temp_produk_pengajuan_retur";
        }
        
        $query = "
            select *
            from 
            (
                select *
                from $db a 
                where a.id_pengajuan = $id_ajuan
                GROUP BY a.kodeprod
            )a where a.qty_lpk is null
        ";
        return $this->db->query($query);
    }

    public function update_data_dbsls(){
        $query = "
            insert into management_retur.master_dbsls
            select 	'', a.customerid, a.tanggal, a.productid, a.nama_customer, a.nama_product, a.brandid, a.nama_brand, a.ref, a.no_seri_pajak,
                        a.qty_kecil, (-1)*b.banyak, a.beli, a.jual, a.disc_cabang, a.disc_beli,
                        '','','','','','','',''
            from management_retur.master_dbsls_original a LEFT JOIN
            (
                select a.company, a.nodo, a.nodo_beli, a.noseri, b.kodeprod, sum(b.banyak) as banyak
                from mpm.trans a INNER JOIN mpm.trans_detail b 
                    on a.id = b.id_ref
                where a.deleted =0 and b.deleted = 0 
                GROUP BY a.noseri, b.kodeprod
            )b on a.no_seri_pajak = b.noseri and a.productid = b.kodeprod
        ";
        return $this->db->query($query);
    }

    public function get_signature_by_id($idx, $versi = '0'){
        if ($versi == 2) {
            $sql = $this->db->query("select signature from management_retur.pengajuan_retur a where a.id = $idx");
        } else {
            $sql = $this->db->query("select signature from db_temp.t_temp_pengajuan_retur a where a.id = $idx");
        }
        
        return $sql;
    }

    public function get_retur($userid = ""){

        if ($userid) {
            $params = "and a.userid = $userid";
        }else{
            $params = "";
        }

        $query = "
        select 	a.id,a.supp,a.userid,a.company,a.tipe,a.deleted,tglbuat,a.created,
                a.nodo_beli, nodo, date_format(tglbuat,'%d %M %Y, %T') as tglbuat,
                date_format(a.tgldo,'%Y-%m-%d') as tgldo, a.tgldo_beli,
                noseri, a.nopo, noseri_beli
        from 	mpm.trans a inner join mpm.user b 
                    on a.userid=b.id 
        where date_format(tglbuat,'%Y-%m-%d') and deleted=0 $params
        order by a.tglbuat desc, a.company asc
        limit 1000
        ";
        return $this->db->query($query);
    }

    public function get_retur_by_id($id){

        if ($id) {
            $params = "and a.id = $id";
        }else{
            $params = "";
        }

        $query = "
            select 	a.id,a.supp,a.userid,a.company,a.tipe,a.deleted,tglbuat,a.created,
                    a.nodo_beli, nodo, date_format(a.tgldo,'%Y-%m-%d') as tgldo,
                    noseri, noseri_beli, nopo, tgldo_beli, tgl_beli, c.*, d.namaprod
            from 	mpm.trans a inner join mpm.user b 
                        on a.userid=b.id LEFT JOIN
                        (
                            select 	a.id_ref, a.kodeprod, a.kode_prc, a.banyak, a.disc, a.diskon, a.diskon_beli, a.dpp,
                                    a.harga, a.harga_beli
                            from mpm.trans_detail a 
                            where a.deleted = 0
                        )c on a.id = c.id_ref   left join 
                        (
                            select a.kodeprod, a.namaprod
                            from mpm.tabprod a 
                        )d on c.kodeprod = d.kodeprod
            where date_format(tglbuat,'%Y-%m-%d') and deleted=0 $params
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        return $this->db->query($query);
    }

    public function get_user($id){
        $query = "
            select *
            from mpm.user a 
            where a.id = $id
        ";

        return $this->db->query($query);
    }

    public function get_import_nr($signature){
        $query = "
            select *
            from management_retur.import_retur a
            where a.signature = '$signature'
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        // die;

        return $this->db->query($query);
    }

    public function get_import_header($signature){
        $query = "
            select *
            from management_retur.import_retur a
            where a.signature = '$signature'
            group by a.noseri_penjualan
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        // die;

        return $this->db->query($query);
    }

    public function get_import_by_id($signature, $noseri_penjualan){
        $query = "
            select *
            from management_retur.import_retur a
            where a.signature = '$signature' and a.noseri_penjualan = '$noseri_penjualan'
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        // die;

        return $this->db->query($query);
    }

    public function get_produk($kodeprod){
        $query = "
            select *
            from mpm.tabprod a 
            where a.kodeprod = '$kodeprod'
        ";

        return $this->db->query($query);
    }

    public function get_totaltrans($id_ref, $supp) {
        if ($supp == 001) {
            $sql = "
                SELECT round(sum(a.bruto)) as tot_bruto,
                sum(a.disc) as tot_disc,
                round(sum(a.dpp)) as tot_dpp
                FROM mpm.trans_detail a
                WHERE a.id_ref = $id_ref
                GROUP BY a.id_ref
            ";
        }
        elseif ($supp == 002) {
            $sql = "
                SELECT floor(sum(a.bruto)) as tot_bruto,
                sum(floor(a.disc))as tot_disc,
                floor(sum(a.dpp)) as tot_dpp
                FROM mpm.trans_detail a
                WHERE a.id_ref = $id_ref
                GROUP BY a.id_ref
            ";
        }
        elseif ($supp == 005) {
            $sql = "
                select *
                from
                (
                    select 	floor(sum(a.bruto)) as tot_bruto
                    from 	mpm.trans_detail a
                    where 	id_ref= $id_ref and
                            deleted=0
                )a,
                (			
                    select sum(potongan) as tot_disc
                    from
                    (
                        select 	sum(a.disc) as potongan
                        from 	mpm.trans_detail a
                        where 	id_ref= $id_ref and
                                deleted=0 and a.kodeprod in (select b.kodeprod from db_produk.t_product_retur b where b.aktif = 1)
                        union all
                        select 	sum(banyak*floor(harga_beli*a.diskon_beli/100)*-1) as potongan
                        from 	mpm.trans_detail a
                        where 	id_ref= $id_ref and
                                deleted=0 and a.kodeprod not in (select b.kodeprod from db_produk.t_product_retur b where b.aktif = 1)
                    )a 
                )b,
                (
                    select sum(x) as tot_dpp
                    from
                    (
                        select 	floor(sum(a.bruto)) as x
                        from 	mpm.trans_detail a
                        where 	id_ref= $id_ref and
                                deleted=0
                        union all
                        select sum(potongan*-1) as x
                        from
                        (
                            select 	sum(a.disc) as potongan
                            from 	mpm.trans_detail a
                            where 	id_ref= $id_ref and
                                    deleted=0 and a.kodeprod in (select b.kodeprod from db_produk.t_product_retur b where b.aktif = 1)
                            union all
                            select 	sum(banyak*floor(harga_beli*a.diskon_beli/100)*-1) as potongan
                            from 	mpm.trans_detail a
                            where 	id_ref= $id_ref and
                                    deleted=0 and a.kodeprod not in (select b.kodeprod from db_produk.t_product_retur b where b.aktif = 1)
                        )a 
                    )a
                )c
            ";
        }
        elseif ($supp == 012) {
            $sql = "
                SELECT sum(floor(a.bruto)) as tot_bruto,
                sum(a.disc) as tot_disc,
                sum(floor(a.dpp)) as tot_dpp
                FROM mpm.trans_detail a
                WHERE a.id_ref = $id_ref
                GROUP BY a.id_ref
            ";
        }
        else {
            $sql = "
                SELECT floor(sum(a.bruto)) as tot_bruto,
                sum(a.disc)) as tot_disc,
                floor(sum(a.dpp)) as tot_dpp
                FROM mpm.trans_detail a
                WHERE a.id_ref = $id_ref
                GROUP BY a.id_ref
            ";
        }

        // echo "<pre>";
        // print_r($sql);
        // echo "</pre>";
        // die;
        
        return $this->db->query($sql);
    }

}