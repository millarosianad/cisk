<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_management_inventory extends CI_Model 
{

    // public function get_sitecode($id){

    //     if ($this->session->userdata('level') == 4) 
    //     {
    //         $query = "
    //             select a.username, b.branch_name, b.kode_comp, b.nama_comp, b.site_code
    //             from mpm.user a INNER JOIN 
    //             (
    //                 select concat(a.kode_comp, a.nocab) as site_code, a.branch_name, a.nama_comp, a.kode_comp
    //                 from mpm.tbl_tabcomp a 
    //                 where a.status = 1 and a.status_claim = 1
    //                 GROUP BY concat(a.kode_comp, a.nocab)
    //             )b on a.username = b.kode_comp
    //             where a.id =$id
    //         ";

    //         echo "<pre>";
    //         print_r($query);
    //         echo "</pre>";

    //         die;
                
    //         return  $this->db->query($query);    
        

    //     }else
    //     {    
    //         return $this->db->query("select 1");
    //     }
    // }

    public function get_sitecode()
    {
        $id = $this->session->userdata('id');
        // echo "id : ".$id;
        $year = date('Y');
        if($id == 547 || $id == 297 || $id == 588 || $id == 580){
            $query = "
                SELECT a.id, a.username, a.company, CONCAT(b.kode_comp,b.nocab) as site_code, b.nama_comp, b.branch_name
                FROM
                (
                    SELECT *
                    FROM mpm.`user` a
                    where a.`level` = 4 and a.active = 1
                )a INNER JOIN
                (
                    SELECT a.*
                    FROM
                    (
                        SELECT CONCAT(kode_comp, nocab) as kode, KODE_COMP, NOCAB, nama_comp, urutan, a.branch_name
                        FROM mpm.tbl_tabcomp a
                        where `status` = 1
                        GROUP BY concat(a.kode_comp, a.nocab)
                    )a INNER JOIN
                    (
                        SELECT CONCAT(kode_comp, nocab) as kode
                        FROM db_dp.t_dp
                        WHERE tahun = $year AND `status` = 1
                    )b on a.kode = b.kode
                )b on a.username = b.kode_comp
                order by b.urutan
            ";
        }
        else{

            $query = "
                SELECT  a.id, a.username, a.company, 
                        if(concat(b.kode_comp,b.nocab) is null, a.username, concat(b.kode_comp,b.nocab)) as site_code,
                        if(b.nama_comp is null, a.company, b.nama_comp) as nama_comp, 
                        if(b.branch_name is null, a.name, b.branch_name) as branch_name
                FROM
                (
                    SELECT *
                    FROM mpm.`user` a
                    where a.`level` = 4 and a.active = 1 and a.id = $id
                )a LEFT JOIN
                (
                    SELECT a.*
                    FROM
                    (
                        SELECT CONCAT(kode_comp, nocab) as kode, KODE_COMP, NOCAB, nama_comp, urutan, a.branch_name
                        FROM mpm.tbl_tabcomp a
                        where `status` = 1
                        GROUP BY concat(a.kode_comp, a.nocab)
                    )a INNER JOIN
                    (
                        SELECT CONCAT(kode_comp, nocab) as kode
                        FROM db_dp.t_dp
                        WHERE tahun = $year AND `status` = 1
                    )b on a.kode = b.kode
                )b on a.username = b.kode_comp
                order by b.urutan
            ";
        }
        // echo "<pre><br><br><br>";
        // print_r($query);
        // echo "</pre>";
        $proses = $this->db->query($query);
        return $proses;
    }

    public function get_pengajuan($signature = '', $kode_alamat = "", $advanced = ""){

        if ($signature) {
            $params = "and a.signature = '$signature'";
        }else{
            $params = "";
        }

        if ($kode_alamat) {
            $params_alamat = "and a.site_code in ($kode_alamat)";
        }else{
            $params_alamat = "";
        }

        $session_supp = $this->session->userdata('supp');

        if ($session_supp == '000' || $session_supp == null) {
            $params_supp = "";
        }else{
            $supp_paramsx = '';
            $get_supp = $this->get_supp_principal_akses($this->session->userdata('id'));
            if ($get_supp->num_rows()>0) {
                foreach ($get_supp->result() as $a) {
                    $supp_params = $a->supp;             
                    $supp_paramsx.=",'".$supp_params."'";
                }
                $akses_supp = preg_replace('/,/', '', $supp_paramsx,1);
                $params_supp = "and a.supp in ($akses_supp)";
            }else{
                $params_supp="and a.supp in (000)";
            }
            
        }

        if ($advanced) {
            // echo "aa";
            $from = $advanced["from"];
            $to = $advanced["to"];
            $status = $advanced["status"];

            if ($status == 0) {
                $params_status = "";
            }else{
                $params_status = "and a.status = $status";
            }
            $params_date = "and (a.tanggal_pengajuan between '$from 00:00:00' and '$to 23:59:59')";
        
        }else{

            // echo "bbb";
            $params_date = "";
            $params_status = "";
        }

        // jika user pabrik / yuni
        if ($this->session->userdata('username') == 'yuni') {
            $params_pabrik = "and a.status in (6,8)";
        }else{
            $params_pabrik = "";
        }

        $query = "
            select 	a.id, a.no_pengajuan, a.site_code, a.nama, a.supp, a.tanggal_pengajuan, a.file, 
                    a.status, a.nama_status, a.signature, a.deleted, a.tipe,
                    b.branch_name, if(b.nama_comp is null, d.company, b.nama_comp) as nama_comp, d.company,
                    c.namasupp, a.created_by, a.digital_signature, a.created_at, a.verifikasi_at, a.verifikasi_by, a.verifikasi_signature,
                    e.username as verifikasi_username, a.principal_area_at, a.principal_area_by, a.principal_area_signature, a.file_principal_area,
                    a.catatan_principal_area, f.username as principal_area_username, 
                    a.status_principal_ho, a.nama_status_principal_ho,
                    a.principal_ho_at, a.principal_ho_by, a.principal_ho_signature, a.file_principal_ho,
                    a.catatan_principal_ho, g.username as principal_ho_username, a.tanggal_kirim_barang, a.nama_ekspedisi, a.est_tanggal_tiba, 
                    proses_kirim_barang_at, a.proses_kirim_barang_by, h.username as username_kirim_barang, a.file_pengiriman,
                    a.tanggal_terima_barang, a.nama_penerima, a.no_terima_barang, a.file_terima_barang, a.terima_barang_at, 
                    i.username as username_terima_barang,
                    a.tanggal_pemusnahan, a.nama_pemusnahan, a.file_pemusnahan, a.foto_pemusnahan_1, a.foto_pemusnahan_2, a.video,
                    a.pemusnahan_at, a.pemusnahan_by, j.username as username_pemusnahan, k.noseri, k.noseri_beli, a.video
            from    management_inventory.pengajuan_retur a LEFT JOIN (
                select concat(a.kode_comp, a.nocab) as site_code, a.branch_name, a.nama_comp
                from mpm.tbl_tabcomp a 
                where a.`status` = 1
                GROUP BY concat(a.kode_comp, a.nocab)
            )b on a.site_code = b.site_code LEFT JOIN (
                select a.supp, a.namasupp
                from mpm.tabsupp a 
                union all 
                select '001-herbal' as supp, 'DELTOMED_HERBAL' as namasupp
                union all 
                select '001-herbana' as supp, 'DELTOMED_HERBANA' as namasupp
                union all 
                select '001-GT' as supp, 'DELTOMED-GT' as namasupp
                union all 
                select '001-MTI' as supp, 'DELTOMED-MTI' as namasupp
                union all 
                select '001-NKA' as supp, 'DELTOMED-NKA' as namasupp
                union all 
                select '001-GT-PHARMA' as supp, 'DELTOMED-GT-PHARMA' as namasupp
            )c on a.supp = c.supp left join 
            (
                select a.id, a.username, a.company
                from mpm.user a 
            )d on a.site_code = d.username left join 
            (
                select a.id, a.username, a.company
                from mpm.user a 
            )e on a.verifikasi_by = e.id left join 
            (
                select a.id, a.username, a.company
                from mpm.user a 
            )f on a.principal_area_by = f.id left join 
            (
                select a.id, a.username, a.company
                from mpm.user a 
            )g on a.principal_ho_by = g.id left join 
            (
                select a.id, a.username, a.company
                from mpm.user a 
            )h on a.proses_kirim_barang_by = h.id left join 
            (
                select a.id, a.username, a.company
                from mpm.user a 
            )i on a.terima_barang_by = i.id left join 
            (
                select a.id, a.username, a.company
                from mpm.user a 
            )j on a.pemusnahan_by = j.id left join (
                select a.no_ajuan_retur, a.noseri, a.noseri_beli
                from management_retur.ajuan_vs_nota_retur a 
                GROUP BY a.no_ajuan_retur
            )k on a.no_pengajuan = k.no_ajuan_retur
            where a.deleted is null $params $params_alamat $params_supp $params_date $params_status $params_pabrik
            order by a.id desc
        ";

            // echo "<pre>";
            // print_r($query);
            // echo "</pre>";
            // die;

        return $this->db->query($query);
    }


    public function generate($created_at){

        $bulan_now = date('m',strtotime($created_at));

        $romawi = $this->getRomawi($bulan_now);

        $tahun_now = date('Y');

        // $query = "
        //     select a.no_pengajuan, substr(a.no_pengajuan,5,3) as urut
        //     from management_inventory.pengajuan_retur a left join (
        //         select a.id, a.username
        //         from mpm.user a 
        //     )b on a.created_by = b.id
        //     where year(a.tanggal_pengajuan) = $tahun_now and month(a.tanggal_pengajuan) = $bulan_now and a.no_pengajuan is not null
        //     ORDER BY a.id desc
        //     limit 1
        // ";

        $query = "
            select a.no_pengajuan, substr(a.no_pengajuan,5,3) as urut
            from management_inventory.pengajuan_retur a
            where year(a.tanggal_pengajuan) = $tahun_now and month(a.tanggal_pengajuan) = $bulan_now and a.no_pengajuan is not null
            ORDER BY a.no_pengajuan desc
            limit 1
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        // die;

        $no_pengajuan_current = $this->db->query($query);
        if ($no_pengajuan_current->num_rows() > 0) {
            
            $params_urut = $no_pengajuan_current->row()->urut + 1;
            // echo $params_urut;

            if (strlen($params_urut) === 1) {
                $generate = "RTR-00$params_urut/MPM/$romawi/$tahun_now";
            }elseif (strlen($params_urut) === 2) {
                $generate = "RTR-0$params_urut/MPM/$romawi/$tahun_now";
            }else{
                $generate = "RTR-$params_urut/MPM/$romawi/$tahun_now";
            }
        }else{
            $generate = "RTR-001/MPM/$romawi/$tahun_now";
        }

        // echo "generate : ".$generate;

        // die;

        // echo $generate;
        // die;
        return $generate;
    }

    function getRomawi($bln){
        switch ($bln){
            case 1: 
                return "I";
                break;
            case 2:
                return "II";
                break;
            case 3:
                return "III";
                break;
            case 4:
                return "IV";
                break;
            case 5:
                return "V";
                break;
            case 6:
                return "VI";
                break;
            case 7:
                return "VII";
                break;
            case 8:
                return "VIII";
                break;
            case 9:
                return "IX";
                break;
            case 10:
                return "X";
                break;
            case 11:
                return "XI";
                break;
            case 12:
                return "XII";
                break;
        }
    }

    public function get_user($id){
        $query = "
            select *
            from mpm.user a where a.id = $id
        ";
        return $this->db->query($query);
    }

    public function cek_status($signature){
        $query = "
            select *
            from management_inventory.pengajuan_retur a 
            where a.signature = '$signature'
        ";
        return $this->db->query($query);
    }

    public function get_pengajuan_detail($id_pengajuan){
        $query = "
        select 	a.id, a.kodeprod, a.batch_number, a.expired_date, a.jumlah, a.satuan, a.nama_outlet, a.keterangan, a.status, a.nama_status,
                a.deskripsi, b.namaprod, a.signature, a.alasan, a.qty_approval, a.keterangan_principal_area
        from management_inventory.pengajuan_retur_detail a LEFT JOIN 
        (
            select a.kodeprod, a.namaprod
            from mpm.tabprod a 
        )b on a.kodeprod = b.kodeprod
        where a.deleted is null and a.id_pengajuan = $id_pengajuan
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function get_pengajuan_detail_email_ho($id_pengajuan){
        $query = "
        select 	a.id, a.kodeprod, a.batch_number, a.expired_date, a.jumlah, a.satuan, a.nama_outlet, a.keterangan, a.status, a.nama_status,
                a.deskripsi, b.namaprod, a.signature, a.alasan, a.qty_approval, a.keterangan_principal_area
        from management_inventory.pengajuan_retur_detail a LEFT JOIN 
        (
            select a.kodeprod, a.namaprod
            from mpm.tabprod a 
        )b on a.kodeprod = b.kodeprod
        where a.deleted is null and a.id_pengajuan = $id_pengajuan and a.status = 3
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function get_pengajuan_detail_filter($id_pengajuan){
        $query = "
        select 	a.id, a.kodeprod, a.batch_number, a.expired_date, a.jumlah, a.satuan, a.nama_outlet, a.keterangan, a.status, a.nama_status,
                a.deskripsi, b.namaprod, a.signature, a.alasan, a.qty_approval, a.keterangan_principal_area
        from management_inventory.pengajuan_retur_detail a LEFT JOIN 
        (
            select a.kodeprod, a.namaprod
            from mpm.tabprod a 
        )b on a.kodeprod = b.kodeprod
        where a.deleted is null and a.id_pengajuan = $id_pengajuan and a.qty_approval > 0 and a.status = 3
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function get_pengajuan_detail_accordion($id_pengajuan){
        $query = "
        select 	a.id, a.kodeprod, a.batch_number, a.expired_date, a.jumlah, a.satuan, a.nama_outlet, a.keterangan, a.status, a.nama_status,
                a.deskripsi, b.namaprod, a.signature, a.alasan, a.qty_approval, a.keterangan_principal_area
        from management_inventory.pengajuan_retur_detail a LEFT JOIN 
        (
            select a.kodeprod, a.namaprod
            from mpm.tabprod a 
        )b on a.kodeprod = b.kodeprod
        where a.deleted is null and a.id_pengajuan = $id_pengajuan
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function get_pengajuan_detail_pdf($id_pengajuan){

        // $query = "
        // select 	a.id, a.kodeprod, a.batch_number, a.expired_date, a.jumlah, a.satuan, a.nama_outlet, a.keterangan, a.status, a.nama_status,
        //         a.deskripsi, b.namaprod, a.signature, a.alasan, a.qty_approval, a.keterangan_principal_area
        // from management_inventory.pengajuan_retur_detail a LEFT JOIN 
        // (
        //     select a.kodeprod, a.namaprod
        //     from mpm.tabprod a 
        // )b on a.kodeprod = b.kodeprod
        // where a.deleted is null and a.id_pengajuan = $id_pengajuan and a.qty_approval > 0 and a.status = 3
        // ";

        $query = "
        select 	a.id, a.kodeprod, a.batch_number, a.expired_date, a.jumlah, a.satuan, a.nama_outlet, a.keterangan, a.status, a.nama_status,
                a.deskripsi, b.namaprod, a.signature, a.alasan, a.qty_approval, a.keterangan_principal_area
        from management_inventory.pengajuan_retur_detail a LEFT JOIN 
        (
            select a.kodeprod, a.namaprod
            from mpm.tabprod a 
        )b on a.kodeprod = b.kodeprod
        where a.deleted is null and a.id_pengajuan = $id_pengajuan
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        // die;
        return $this->db->query($query);
    }

    public function get_pengajuan_detail_summary($id_pengajuan){
        $query = "
            select count(a.kodeprod) as count_kodeprod, sum(a.jumlah * b.h_dp) as value_rbp, sum(a.jumlah) as sum_qty_pengajuan
            from management_inventory.pengajuan_retur_detail a LEFT JOIN 
            (
                select b.h_dp, b.kodeprod
                from mpm.prod_detail b 
                where b.tgl = (
                    select max(c.tgl)
                    from mpm.prod_detail c 
                    where b.kodeprod = c.kodeprod
                    GROUP BY c.kodeprod
                )
                GROUP BY b.kodeprod
            )b on a.kodeprod = b.kodeprod
            where a.deleted is null and a.id_pengajuan = $id_pengajuan
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function get_email($site_code){
        
        $username = substr($site_code,0,3);
        $query = "
            select a.email
            from mpm.user a 
            where a.username = '$username'
        ";

        return $this->db->query($query);
    }

    public function get_pengajuan_by_status(){
        $query = "
            select a.supp, b.namasupp, a.status, a.nama_status, sum(c.jumlah) as total_jumlah, sum(c.`value`) as total_value
            from management_inventory.pengajuan_retur a left join (
                select a.supp, a.namasupp
                from mpm.tabsupp a
            )b on a.supp = b.supp LEFT JOIN 
            (
                select a.id_pengajuan, a.kodeprod, sum(a.jumlah) as jumlah, b.h_dp, sum(a.jumlah * b.h_dp) as value
                from management_inventory.pengajuan_retur_detail a LEFT JOIN
                (
                    select a.kodeprod, a.namaprod, b.h_dp
                    from mpm.tabprod a INNER JOIN (
                        select b.kodeprod, b.h_dp
                        from mpm.prod_detail b
                        where b.tgl = (
                            select max(c.tgl)
                            from mpm.prod_detail c
                            where b.kodeprod = c.kodeprod
                            GROUP BY c.kodeprod
                        )
                    )b on a.kodeprod = b.kodeprod
                )b on a.kodeprod = b.kodeprod
                GROUP BY a.id_pengajuan
            )c on a.id = c.id_pengajuan
            GROUP BY a.supp, a.status
        ";


        return $this->db->query($query);
    }

    public function get_pengajuan_breakdown($breakdown){

        if ($breakdown == 'site_code') {
            $params_breakdown = "group by a.site_code";
        }elseif($breakdown == 'status'){
            $params_breakdown = "group by a.status";
        }

        $query = "
            select a.supp, b.namasupp, a.status, a.nama_status, count(*) as total_ajuan, sum(c.jumlah) as total_jumlah, sum(c.`value`) as total_value, a.site_code, d.branch_name, d.nama_comp
            from management_inventory.pengajuan_retur a left join (
                select a.supp, a.namasupp
                from mpm.tabsupp a
            )b on a.supp = b.supp LEFT JOIN 
            (
                select a.id_pengajuan, a.kodeprod, sum(a.jumlah) as jumlah, b.h_dp, sum(a.jumlah * b.h_dp) as value
                from management_inventory.pengajuan_retur_detail a LEFT JOIN
                (
                    select a.kodeprod, a.namaprod, b.h_dp
                    from mpm.tabprod a INNER JOIN (
                        select b.kodeprod, b.h_dp
                        from mpm.prod_detail b
                        where b.tgl = (
                            select max(c.tgl)
                            from mpm.prod_detail c
                            where b.kodeprod = c.kodeprod
                            GROUP BY c.kodeprod
                        )
                    )b on a.kodeprod = b.kodeprod
                )b on a.kodeprod = b.kodeprod
                GROUP BY a.id_pengajuan
            )c on a.id = c.id_pengajuan LEFT JOIN (
                select concat(a.kode_comp, a.nocab) as site_code, a.branch_name, a.nama_comp
                from mpm.tbl_tabcomp a 
                where a.status = 1
                GROUP BY concat(a.kode_comp, a.nocab)
            )d on a.site_code = d.site_code
            $params_breakdown
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        return $this->db->query($query);
    }

    public function get_pengajuan_detail_by_id($id){
        $query = "
            select *
            from management_inventory.pengajuan_retur_detail a 
            where a.id = $id
        ";
        return $this->db->query($query);
    }

    public function get_product($kodeprod){
        $query = "
            select *
            from mpm.tabprod a
            where a.kodeprod = '$kodeprod'
        ";

        return $this->db->query($query);
    }

    public function get_email_to_retur_by_site_code($site_code, $supp){

        $query = "
            select b.username, b.email
            from management_inventory.mapping_area_retur a INNER JOIN (
                select a.id, a.username, a.email
                from mpm.user a 
            )b on a.userid = b.id 
            where a.supp = '$supp' and a.site_code = '$site_code'
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        return $this->db->query($query);

    }

    public function get_principal_area($site_code, $supp){

        $query = "
            select b.username, b.email
            from management_inventory.mapping_area_retur a INNER JOIN (
                select a.id, a.username, a.email
                from mpm.user a 
            )b on a.userid = b.id 
            where a.supp = '$supp' and a.site_code = '$site_code' and a.status_ho is null
        ";

        // echo "<pre>";
        

        return $this->db->query($query);

    }

    public function get_email_ho_to_retur_by_site_code($site_code, $supp){

        $query = "
            select b.username, b.email
            from management_inventory.mapping_area_retur a INNER JOIN (
                select a.id, a.username, a.email
                from mpm.user a 
            )b on a.userid = b.id 
            where a.supp = '$supp' and a.site_code = '$site_code' and a.status_ho = 1 
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        return $this->db->query($query);

    }

    public function get_email_cc_retur_by_site_code($site_code, $supp){

        $query = "
            select b.username, b.email
            from management_inventory.mapping_area_retur a INNER JOIN (
                select a.id, a.username, a.email
                from mpm.user a 
            )b on a.userid = b.id 
            where a.supp = $supp and a.site_code = '$site_code'
        ";

        return $this->db->query($query);

    }

    public function get_principal_akses($userid){
        $query = "
            select *
            from management_inventory.mapping_area_retur a 
            where a.userid = $userid
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "<pre>";

        return $this->db->query($query);
    }

    public function get_supp_principal_akses($userid){
        $query = "
            select *
            from management_inventory.mapping_area_retur a 
            where a.userid = $userid
            group by a.supp
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "<pre>";

        return $this->db->query($query);
    }

    public function get_level_ho($site_code, $supp){
        $userid = $this->session->userdata('id');
        $query = "
            select *
            from management_inventory.mapping_area_retur a
            where a.site_code = '$site_code' and a.userid = $userid and a.supp = '$supp'
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function get_pengajuan_group($sign_principal_ho_date, $userid_for_group_approval, $principal_for_group_approval){
        $query = "
            select 	a.id,a.no_pengajuan, a.tipe, a.site_code, a.nama, a.tanggal_pengajuan, a.signature, a.nama_status,
                    c.total_qty_approval, d.nama_comp, a.supp
            from management_inventory.pengajuan_retur a INNER JOIN (
                select a.supp, a.site_code
                from management_inventory.mapping_area_retur a 
                where a.userid = $userid_for_group_approval and a.status_ho = 1
            )b on a.supp = b.supp and a.site_code = b.site_code LEFT JOIN (
                select 	a.id_pengajuan, a.kodeprod, a.batch_number, a.expired_date, a.jumlah, a.satuan, a.alasan, 
                        a.nama_outlet, sum(a.qty_approval) as total_qty_approval, a.keterangan_principal_area
                from management_inventory.pengajuan_retur_detail a
                where a.qty_approval is not null
                GROUP BY a.id_pengajuan
            )c on a.id = c.id_pengajuan LEFT JOIN
            (
                select concat(a.kode_comp, a.nocab) as site_code, a.branch_name, a.nama_comp
                from mpm.tbl_tabcomp a 
                where a.`status` = 1
                GROUP BY concat(a.kode_comp, a.nocab)
            )d on a.site_code = d.site_code
            where a.`status` = 4 
        ";

        return $this->db->query($query);

    }

    public function get_pengajuan_by_signature($signature){
        $query = "
            select *
            from management_inventory.pengajuan_retur a
            where a.signature = '$signature'
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        return $this->db->query($query);
    }

    public function get_qty_pengajuan_by_id_product($id_product){
        $query = "
            select *
            from management_inventory.pengajuan_retur_detail a 
            where a.id = $id_product
        ";
        return $this->db->query($query);
    }

    public function get_traffic(){
        $query = "
            select * 
            from management_inventory.traffic a 
            ORDER BY a.id desc
            limit 1
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function insert_traffic($site_code = '', $created_by, $status_import)
    {
        $created_at = $this->model_outlet_transaksi->timezone();
        $data = [
            "site_code"         => $site_code,
            "created_by"        => $created_by,
            "created_at"        => $created_at,
            "status_generate"   => $status_import
        ];

        $insert = $this->db->insert("management_inventory.traffic", $data);
        return $insert;
    }

    public function get_nama_status_by_id($id){
        if ($id == 1) {
            $nama_status = "PENDING DP";
        }elseif ($id == 2) {
            $nama_status = "PENDING MPM";
        }elseif ($id == 3) {
            $nama_status = "PENDING PRINCIPAL AREA";
        }elseif ($id == 4) {
            $nama_status = "PENDING PRINCIPAL HO";
        }elseif ($id == 5) {
            $nama_status = "PENDING KIRIM BARANG";
        }elseif ($id == 6) {
            $nama_status = "PENDING TERIMA BARANG";
        }elseif ($id == 7) {
            $nama_status = "PENDING PEMUSNAHAN";
        }elseif ($id == 8) {
            $nama_status = "BARANG DITERIMA";
        }elseif ($id == 9) {
            $nama_status = "PEMUSNAHAN OLEH DP";
        }elseif ($id == 10) {
            $nama_status = "REJECT PRINCIPAL HO";
        }elseif ($id == 11) {
            $nama_status = "RETUR SAMPLE";
        }

        return $nama_status;
    }



}