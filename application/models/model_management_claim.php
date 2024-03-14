<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_management_claim extends CI_Model 
{
    public function get_registrasi_program($kode_alamat = '', $signature = ''){

        if ($signature) {
            $params_where = "where a.signature = '$signature'";
        }else{
            $params_where = "";
        }

        if ($kode_alamat) {
            $params_alamat = "and a.site_code in ($kode_alamat)";
        }else{
            $params_alamat = "";
        }

        $query = "
        select 	a.id, d.id as id_ajuan, f.id as id_revisi, a.kategori, a.supp, b.namasupp, 
                a.from, a.to, a.nama_program, a.nomor_surat, a.syarat, 
                a.upload_jpg, a.upload_pdf, a.signature, a.duedate, c.username,
                d.nama_pengirim, d.email_pengirim, d.ajuan_excel, d.ajuan_zip, d.created_at as tgl_kirim_ajuan_claim,
                e.nama_comp, d.status, d.nama_status, d.signature as signature_ajuan, d.nomor_ajuan,
                d.keterangan_mpm, d.file_mpm, d.mpm_at, d.keterangan_principal_area, d.file_principal_area, d.principal_area_at, 
                d.keterangan_principal_ho, d.file_principal_ho, d.principal_ho_at, d.pic_mpm, g.username as pic_mpm_by
        from management_claim.registrasi_program a LEFT JOIN 
        (
            select a.supp, a.namasupp
            from mpm.tabsupp a
        )b on a.supp = b.supp left join 
        (
            select a.id, a.username
            from mpm.user a 
        )c on a.created_by = c.id LEFT JOIN 
        (
            select  a.site_code, a.id, a.nama_pengirim, a.email_pengirim, a.ajuan_excel, a.ajuan_zip, a.id_program, a.created_at, a.created_by,
                    a.status, a.nama_status, a.signature, a.nomor_ajuan, a.keterangan_mpm, a.file_mpm, a.mpm_at, a.keterangan_principal_area, 
                    a.file_principal_area, a.principal_area_at, a.keterangan_principal_ho, a.file_principal_ho, a.principal_ho_at, a.pic_mpm
            from    management_claim.ajuan_claim a 
            where a.deleted is null $params_alamat
        )d on a.id = d.id_program LEFT JOIN (
            select  concat(a.kode_comp, a.nocab) as site_code, a.branch_name, a.nama_comp
            from    mpm.tbl_tabcomp a 
            where   a.`status` = 1
            GROUP BY concat(a.kode_comp, a.nocab)
        )e on d.site_code = e.site_code LEFT JOIN
        (
            select *
            from management_claim.revisi_ajuan a 
        )f on d.id = f.id_ajuan left join 
        (
            select a.id, a.username
            from mpm.user a 
        )g on d.pic_mpm = g.id
        $params_where
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        return $this->db->query($query);
    }

    public function get_registrasi_program_by_signature_ajuan($signature = ''){

        if ($signature) {
            $params_where = "where d.signature = '$signature'";
        }else{
            $params_where = "";
        }

        $query = "
            select 	a.id, a.kategori, a.supp, b.namasupp, a.from, a.to, a.nama_program, a.nomor_surat, a.syarat, 
                    a.upload_jpg, a.upload_pdf, a.signature, a.duedate, c.username,
                    d.nama_pengirim, d.email_pengirim, d.ajuan_excel, d.ajuan_zip, d.created_at as tgl_kirim_ajuan_claim,
                    e.nama_comp, d.status, d.nama_status, d.signature as signature_ajuan, d.nomor_ajuan
            from management_claim.registrasi_program a LEFT JOIN 
            (
                select a.supp, a.namasupp
                from mpm.tabsupp a
            )b on a.supp = b.supp left join 
            (
                select a.id, a.username
                from mpm.user a 
            )c on a.created_by = c.id LEFT JOIN 
            (
                select  a.site_code, a.id, a.nama_pengirim, a.email_pengirim, a.ajuan_excel, a.ajuan_zip, a.id_program, a.created_at, a.created_by,
                        a.status, a.nama_status, a.signature, a.nomor_ajuan
                from management_claim.ajuan_claim a 
                where a.deleted is null
            )d on a.id = d.id_program LEFT JOIN (
                select concat(a.kode_comp, a.nocab) as site_code, a.branch_name, a.nama_comp
                from mpm.tbl_tabcomp a 
                where a.`status` = 1
                GROUP BY concat(a.kode_comp, a.nocab)
            )e on d.site_code = e.site_code
            $params_where
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        return $this->db->query($query);
    }

    

    public function get_registrasi_program_by_id($id =''){

        $session_user = $this->session->userdata('id');

        if ($id) {
            $params = "and a.id = $id";
        }else{
            $params = "";
        }

        $query = "
            select 	a.id, a.kategori, a.supp, b.namasupp, a.from, a.to, a.nama_program, a.nomor_surat, a.syarat, 
                    a.upload_jpg, a.upload_pdf, a.signature, a.duedate, a.upload_template_program, a.created_by,
                    c.username, a.created_at
            from management_claim.registrasi_program a LEFT JOIN 
            (
                select a.supp, a.namasupp
                from mpm.tabsupp a
            )b on a.supp = b.supp left join 
            (
                select a.id, a.username
                from mpm.user a 
            )c on a.created_by = c.id
            where a.deleted is null and a.created_by = $session_user
            $params
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        return $this->db->query($query);
    }

    public function get_registrasi_program_by_signature($signature_program = ''){

        if ($signature_program) {
            $params_signature = "where a.signature = '$signature_program'";
        }else{
            $params_signature = "";
        }

        $query = "
            select a.*, b.namasupp, c.username, c.email
            from management_claim.registrasi_program a left join (
                select a.supp, a.namasupp
                from mpm.tabsupp a 
            )b on a.supp = b.supp LEFT JOIN (
                select a.id, a.username, a.email
                from mpm.user a
            )c on a.created_by = c.id
            $params_signature
        ";
        //  echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        return $this->db->query($query);
    }

    public function get_ajuan_claim($signature = ''){

        if ($signature) {
            $params_where = "where a.signature = '$signature'";
        }else{
            $params_where = "";
        }

        $query = "
            select 	a.id, a.kategori, a.supp, b.namasupp, a.from, a.to, 
                    a.nama_program, a.nomor_surat, a.syarat, a.duedate, 
                    a.upload_jpg, a.upload_pdf, a.upload_template_program, a.signature,
                    a.created_by, c.email
            from management_claim.registrasi_program a LEFT JOIN 
            (
                select a.supp, a.namasupp
                from mpm.tabsupp a
            )b on a.supp = b.supp LEFT JOIN (
                select a.id, a.username, a.email
                from mpm.user a 
            )c on a.created_by = c.id
            $params_where
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        // die;

        return $this->db->query($query);
    }

    public function generate($from_site, $created_at){

        $bulan_now = date('m',strtotime($created_at));

        $romawi = $this->getRomawi($bulan_now);

        $tahun_now = date('Y');

        // $query = "
        //     select a.nomor_ajuan, substr(a.nomor_ajuan,5,3) as urut, a.created_by, a.created_at, b.username
        //     from management_claim.ajuan_claim a left join (
        //         select a.id, a.username
        //         from mpm.user a 
        //     )b on a.created_by = b.id
        //     where year(a.created_at) = $tahun_now and month(a.created_at) = $bulan_now
        //     ORDER BY a.id desc
        //     limit 1
        // ";
        $query = "
            select a.nomor_ajuan, substr(a.nomor_ajuan,5,3) as urut, a.created_by, a.created_at
            from management_claim.ajuan_claim a
            where year(a.created_at) = $tahun_now and month(a.created_at) = $bulan_now
            ORDER BY a.id desc
            limit 1
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        // die;

        $nomor_ajuan_current = $this->db->query($query);
        if ($nomor_ajuan_current->num_rows() > 0) {
            
            $params_urut = $nomor_ajuan_current->row()->urut + 1;
            // echo $params_urut;

            if (strlen($params_urut) === 1) {
                $generate = "CLM-00$params_urut/MPM/$romawi/$tahun_now";
            }elseif (strlen($params_urut) === 2) {
                $generate = "CLM-0$params_urut/MPM/$romawi/$tahun_now";
            }else{
                $generate = "CLM-$params_urut/MPM/$romawi/$tahun_now";
            }
        }else{
            $generate = "CLM-001/MPM/$romawi/$tahun_now";
        }
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

    public function get_verifikasi_ajuan($signature_ajuan){

        // <option value="3"> On MPM Check </option>
        // <option value="4"> On Principal Check </option>
        // <option value="5"> Reject Principal </option>
        // <option value="6"> Approve </option>
        // <option value="7"> DP Kirim DN (Debit Note / Faktur Pajak) </option>
        // <option value="8"> Finance (Principal kirim ke MPM) </option>
        // <option value="9"> Finance (MPM kirim ke DP) </option>

        $query = "
            select 	a.id, a.nomor_ajuan, a.`status`, 
                    a.nama_status,
                    a.tanggal, a.catatan_verifikasi, a.created_at, a.created_by,b.username,
                    a.signature, a.signature_ajuan
            from management_claim.verifikasi_ajuan a left join (
                select a.id, a.username
                from mpm.user a
            )b on a.created_by = b.id
            where a.signature_ajuan = '$signature_ajuan'
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function get_data_user($id){
        $query = "
            select *
            from mpm.user a 
            where a.id = $id
        ";

        return $this->db->query($query);
    }

    public function get_site_code_by_program($id =''){

        if ($id) {
            $params = "where a.id_program = $id";
        }else{
            $params = "";
        }

        $query = "
            select a.site_code, a.nomor_ajuan, a.nama_status, b.id, c.nama_comp
            from management_claim.ajuan_claim a LEFT JOIN 
            (
                select a.id
                from management_claim.registrasi_program a 
            )b on a.id_program = b.id left join (
                select concat(a.kode_comp, a.nocab) as site_code, a.branch_name, a.nama_comp
                from mpm.tbl_tabcomp a 
                where a.status = 1
                group by concat(a.kode_comp, a.nocab)
            )c on a.site_code = c.site_code
            $params
            GROUP BY a.site_code
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        return $this->db->query($query);
    }

    public function get_site_code_by_program_site_code($id, $site_code){

        // if ($id) {
        //     $params = "where a.id_program = $id";
        // }else{
        //     $params = "";
        // }

        $query = "
            select a.site_code, a.nomor_ajuan, a.nama_status, a.id
            from management_claim.ajuan_claim a LEFT JOIN 
            (
                select a.id
                from management_claim.registrasi_program a 
            )b on a.id_program = b.id
            where a.id_program = $id and a.site_code = '$site_code'
            
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        return $this->db->query($query);
    }

    public function get_site($site_code = ""){

        if ($site_code) {
            $params = "where a.site_code ='$site_code'";
        }else{
            $params = "";
        }

        $query = "
            select a.site_code, a.branch_name, a.nama_comp, a.status_ho, a.status_claim
            from 
            (
                select concat(a.kode_comp, a.nocab) as site_code, a.branch_name, a.nama_comp, a.urutan, a.status_ho, a.status_claim
                from mpm.tbl_tabcomp a 
                where a.status = 1
                GROUP BY concat(a.kode_comp, a.nocab)
            )a inner join (
                select concat(a.kode_comp, a.nocab) as site_code
                from db_dp.t_dp a 
                where a.tahun = 2023 and a.`status` = 1
            )b on a.site_code = b.site_code
            $params
            ORDER BY a.urutan asc
        ";
        return $this->db->query($query);
    }

    public function get_sitecode($id){

        $tahun_now = date('Y');

        if ($this->session->userdata('level') == 4) 
        {
            $query = "
                select a.username, b.branch_name, b.kode_comp, b.nama_comp, b.site_code
                from mpm.user a INNER JOIN 
                (
                    select concat(a.kode_comp, a.nocab) as site_code, a.branch_name, a.nama_comp, a.kode_comp
                    from mpm.tbl_tabcomp a INNER JOIN (
                        select concat(a.kode_comp, a.nocab) as site_code
                        from db_dp.t_dp a
                        where tahun = $tahun_now and a.`status` = 1
                    )b on concat(a.kode_comp, a.nocab) = b.site_code
                    where a.status = 1 
                    GROUP BY concat(a.kode_comp, a.nocab)
                )b on a.username = b.kode_comp
                where a.id = $id
            ";

            // echo "<pre>";
            // print_r($query);
            // echo "</pre>";

            // die;
                
            return  $this->db->query($query);    
        

        }else
        {    
            return $this->db->query("select 1");
        }
    }

    public function cek_user_pengajuan($signature_ajuan){
        $userid = $this->session->userdata('id');
        $query = "
            select *
            from management_claim.ajuan_claim a
            where a.signature = '$signature_ajuan' and a.created_by = $userid
        ";

        return $this->db->query($query);
    }

    public function cek_revisi_by_signature_ajuan($signature_ajuan){
        $userid = $this->session->userdata('id');
        $query = "
            select *
            from management_claim.revisi_ajuan a
            where a.signature_ajuan = '$signature_ajuan' and a.created_by = $userid
        ";

        return $this->db->query($query);
    }

    public function cek_status_validasi_excel($signature_program){
        $query = "
            select *
            from management_claim.registrasi_program a 
            where a.signature = '$signature_program'
        ";

        return $this->db->query($query);
    }

    public function get_registrasi_program_by_supp_date($advanced){

        $id = $this->session->userdata('id');

        if ($advanced) {
            // echo "aA";
            // die;
            $supp = $advanced['supp'];
            if ($supp) {
                $params_supp = "and a.supp = '$supp'";
            }else{
                $params_supp = "";
            }

            $from = $advanced['from'];
            $to = $advanced['to'];
            $kategori = $advanced['kategori'];
            $site_code = $advanced['site_code'];

            if ($kategori) {
                $params_kategori = "and a.kategori = '$kategori'";
            }else{
                $params_kategori = "";
            }

            if ($site_code) {
                $params_site_code = "and a.site_code = '$site_code'";
            }else{
                $params_site_code = "";
            }

            if ($from && $to) {
                $params_periode = "and date(a.from) between '$from' and '$to'";
            }else{
                $params_periode = "";
            }

            // kalau user mpm
            $level = $this->session->userdata('level');
            if($level == 10){
                $params_created_by = "and a.created_by = $id";
                $params_where_ajuan_created_by = "";
            }else{
                $params_created_by = "";
                $params_where_ajuan_created_by = "and a.created_by = $id";
            }

            $params = "where a.deleted is null $params_supp $params_periode $params_kategori $params_created_by";
        }else{
            // echo "bb";
            // die;
            // kalau user mpm
            $level = $this->session->userdata('level');
            if($level == 10){
                $params_created_by = "where a.created_by = $id";
                $params_where_ajuan_created_by = "";
            }else{
                $params_created_by = "";
                $params_where_ajuan_created_by = "and a.created_by = $id";
            }

            $params_site_code = '';
            $params_kategori = '';

            $params = $params_created_by;
        }

        $query = "
        select 	a.id, a.kategori, a.supp, b.namasupp, a.`from`, a.`to`, a.nama_program, a.nomor_surat, a.syarat, 
                a.duedate, a.upload_jpg, a.upload_pdf, a.upload_template_program,
                a.status_validasi, a.signature, c.status as `status`, c.nama_status, c.signature as signature_ajuan,
                c.site_code, d.branch_name, d.nama_comp, a.created_by, e.username, c.nomor_ajuan, c.status_data_final,
                c.status_hardcopy, c.nama_status_hardcopy, c.file_hardcopy, c.nomor_hardcopy
        from management_claim.registrasi_program a LEFT JOIN 
        (
            select a.supp, a.namasupp
            from mpm.tabsupp a 
        )b on a.supp = b.supp LEFT JOIN (
            select *
            from management_claim.ajuan_claim a 
            where a.deleted is null
            $params_where_ajuan_created_by $params_site_code
        )c on a.id = c.id_program LEFT JOIN (
            select concat(a.kode_comp, a.nocab) as site_code, a.branch_name, a.nama_comp
            from mpm.tbl_tabcomp a 
            where a.`status` = 1
            GROUP BY concat(a.kode_comp, a.nocab)
        )d on c.site_code = d.site_code LEFT JOIN (
            select a.id, a.username
            from mpm.user a 
        )e on a.created_by = e.id
        $params
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        // die;
        return $this->db->query($query);
    }

    public function get_ajuan_by_signature($signature){
        $query = "
            select 	*
            from management_claim.ajuan_claim a
            where a.signature = '$signature'
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        return $this->db->query($query);
    }

    public function get_status($id){

        if ($id == 1) {
            $nama_status = "PENDING DP";
        }elseif($id == 2){
            $nama_status = "PENDING MPM";
        }elseif($id == 3){
            $nama_status = "REJECT MPM";
        }elseif($id == 4){
            $nama_status = "PENDING PRINCIPAL";
        }elseif($id == 5){
            $nama_status = "REJECT PRINCIPAL";
        }elseif($id == 6){
            $nama_status = "APPROVE";
        }

        return $nama_status;
    }

    public function get_status_hardcopy($id){

        if ($id == 1) {
            $nama_status = "PENDING DP Hardcopy";
        }elseif($id == 2){
            $nama_status = "PENDING MPM Hardcopy";
        }elseif($id == 3){
            $nama_status = "REJECT MPM Hardcopy";
        }elseif($id == 4){
            $nama_status = "PENDING PRINCIPAL Hardcopy";
        }elseif($id == 5){
            $nama_status = "REJECT PRINCIPAL Hardcopy";
        }elseif($id == 6){
            $nama_status = "APPROVE Hardcopy";
        }

        return $nama_status;
    }

    public function get_revisi_by_id_ajuan($id_ajuan){
        $query = "
            select *
            from management_claim.revisi_ajuan a
            where a.id_ajuan = '$id_ajuan'
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function get_ajuan_claim_by_id_program($id_program){
        $query = "
            select *
            from management_claim.ajuan_claim a 
            where a.id_program in ($id_program)
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        return $this->db->query($query);
    }

    public function get_ajuan_claim_by_id_program_and_signature($id_program, $signature){
        $query = "
            select *
            from management_claim.ajuan_claim a 
            where a.id_program in ($id_program) and a.signature in ('$signature')
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        return $this->db->query($query);
    }

    public function get_verifikasi_by_id($id = ''){
        
        if ($id) {
            $params = "where a.id = $id";
        }else{
            $params = "";
        }

        $query = "
            select a.*, b.*
            from management_claim.verifikasi_ajuan a left join (
                select a.id, a.username
                from mpm.user a
            )b on a.created_by = b.id
            $params
        ";

        return $this->db->query($query);

    }

    public function get_ajuan_claim_by_id_program_and_user($id_program, $id_user){

        $query = "
            select *
            from management_claim.ajuan_claim a
            where a.deleted is null and a.id_program = $id_program and a.created_by = $id_user
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        // die;

        return $this->db->query($query);
    }

    public function get_revisi_ajuan_by_id_ajuan($id_ajuan){

        $query = "
            select a.*, b.username
            from management_claim.revisi_ajuan a left join (
                select a.id, a.username
                from mpm.user a 
            )b on a.created_by = b.id
            where a.id_ajuan = $id_ajuan
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        return $this->db->query($query);
    }

    public function get_preview_import($signature_program, $signature){
        $query = "
            select *
            from management_claim.import_bonus_barang a
            where a.signature_program = '$signature_program' and a.signature = '$signature'
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>"; 
        
        return $this->db->query($query);
    }

    public function get_preview_import_diskon($signature_program, $signature){
        $query = "
            select *
            from management_claim.import_diskon a
            where a.signature_program = '$signature_program' and a.signature = '$signature'
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>"; 
        
        return $this->db->query($query);
    }

    public function get_preview_import_failed($signature_program, $signature){
        $query = "
            select *
            from management_claim.import_bonus_barang a
            where a.signature_program = '$signature_program' and a.signature = '$signature' and a.validasi_row > 0
            limit 100
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>"; 
        
        return $this->db->query($query);
    }

    public function get_preview_import_failed_diskon($signature_program, $signature){
        $query = "
            select *
            from management_claim.import_diskon a
            where a.signature_program = '$signature_program' and a.signature = '$signature' and a.validasi_row > 0
            limit 100
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>"; 
        
        return $this->db->query($query);
    }

    public function get_count_validasi_failed($signature_program, $signature){
        $query = "
            select count(*) as total
            from management_claim.import_bonus_barang a 
            where a.signature_program = '$signature_program' and a.signature = '$signature' and a.validasi_row > 0
        ";
        return $this->db->query($query);
    }
    
    public function get_count_validasi_failed_diskon($signature_program, $signature){
        $query = "
            select count(*) as total
            from management_claim.import_diskon a 
            where a.signature_program = '$signature_program' and a.signature = '$signature' and a.validasi_row > 0
        ";
        return $this->db->query($query);
    }

    public function get_count_import($signature_program, $signature){
        $query = "
            select count(*) as total
            from management_claim.import_bonus_barang a 
            where a.signature_program = '$signature_program' and a.signature = '$signature'
        ";
        return $this->db->query($query);
    }

    public function get_count_import_diskon($signature_program, $signature){
        $query = "
            select count(*) as total
            from management_claim.import_diskon a 
            where a.signature_program = '$signature_program' and a.signature = '$signature'
        ";
        return $this->db->query($query);
    }

    public function get_count_validasi_success($signature_program, $signature){
        $query = "
            select count(*) as total
            from management_claim.import_bonus_barang a 
            where a.signature_program = '$signature_program' and a.signature = '$signature' and a.validasi_row = 0
        ";
        return $this->db->query($query);
    }

    public function get_count_validasi_success_diskon($signature_program, $signature){
        $query = "
            select count(*) as total
            from management_claim.import_diskon a 
            where a.signature_program = '$signature_program' and a.signature = '$signature' and a.validasi_row = 0
        ";
        return $this->db->query($query);
    }

    public function get_sum_import_bonus($signature_program, $signature){
        $query = "
            select sum(a.qty_jual) as total_qty_jual, sum(a.qty_bonus) as total_qty_bonus, sum(a.value_jual) as total_value_jual, sum(a.value_bonus) as total_value_bonus 
            from management_claim.import_bonus_barang a 
            where a.signature_program = '$signature_program' and a.signature = '$signature' and a.validasi_row = 0
        ";
        return $this->db->query($query);
    }

    public function get_sum_import_diskon($signature_program, $signature){
        $query = "
            select  sum(a.qty_jual) as total_qty_jual, sum(a.value_jual) as total_value_jual, sum(a.disc_principal) as total_disc_principal, sum(a.disc_cabang) as total_disc_cabang, 
                    sum(a.disc_extra) as total_disc_extra, sum(a.disc_cash) as total_disc_cash, sum(a.disc_claim) as total_disc_claim
            from management_claim.import_diskon a 
            where a.signature_program = '$signature_program' and a.signature = '$signature' and a.validasi_row = 0
        ";
        return $this->db->query($query);
    }

    public function get_import_bonus_by_signature($signature){
        $query = "
            select *
            from management_claim.import_bonus_barang a 
            where a.signature in ($signature)
        ";

        return $this->db->query($query);
    }

    public function insert_traffic_import($site_code = '', $created_by, $status_import)
    {

        $created_at = $this->model_outlet_transaksi->timezone();

        $data = [
            "site_code"     => $site_code,
            "created_by"    => $created_by,
            "created_at"    => $created_at,
            "status_import" => $status_import
        ];

        $insert = $this->db->insert("management_claim.traffic_import", $data);
        return $insert;

    }

    public function get_traffic_import()
    {
        $query = "select * from management_claim.traffic_import a order by a.id desc limit 1";
        return $this->db->query($query);

    }

    public function insert_traffic($site_code = '', $created_by, $status_import)
    {
        $created_at = $this->model_outlet_transaksi->timezone();
        $data = [
            "site_code"         => $site_code,
            "created_by"        => $created_by,
            "created_at"        => $created_at,
            "status_import"     => $status_import
        ];

        $insert = $this->db->insert("management_claim.traffic_import", $data);
        return $insert;
    }

    public function get_dashboard($advanced){

        $id = $this->session->userdata('id');

        if ($advanced) {
            $supp = $advanced['supp'];
            $from = $advanced['from'];
            $to = $advanced['to'];
            $kategori = $advanced['kategori'];

            if ($kategori) {
                $params_kategori = "and a.kategori = '$kategori'";
            }else{
                $params_kategori = "";
            }


            // kalau user mpm
            $level = $this->session->userdata('level');
            if($level == 10){
                $params_created_by = "and a.created_by = $id";
                $params_where_ajuan_created_by = "";
            }else{
                $params_created_by = "";
                $params_where_ajuan_created_by = "where a.created_by = $id";
            }

            $params = "where a.supp = '$supp' and date(a.from) between '$from' and '$to' $params_kategori $params_created_by";
        }else{
            // kalau user mpm
            $level = $this->session->userdata('level');
            if($level == 10){
                $params_created_by = "where a.created_by = $id";
                $params_where_ajuan_created_by = "";
            }else{
                $params_created_by = "";
                $params_where_ajuan_created_by = "where a.created_by = $id";
            }

            $params_site_code = '';
            $params_kategori = '';

            $params = $params_created_by;
        }

        $query = "
        select 	a.id, a.kategori, a.supp, b.namasupp, a.`from`, a.`to`, a.nama_program, a.nomor_surat, a.syarat, 
                a.duedate, a.upload_jpg, a.upload_pdf, a.upload_template_program,
                a.status_validasi, a.signature, c.status as `status`, c.nama_status, c.signature as signature_ajuan,
                c.site_code, d.branch_name, d.nama_comp, a.created_by, e.username, c.nomor_ajuan
        from management_claim.registrasi_program a LEFT JOIN 
        (
            select a.supp, a.namasupp
            from mpm.tabsupp a 
        )b on a.supp = b.supp LEFT JOIN (
            select *
            from management_claim.ajuan_claim a 
            $params_where_ajuan_created_by $params_site_code
        )c on a.id = c.id_program LEFT JOIN (
            select concat(a.kode_comp, a.nocab) as site_code, a.branch_name, a.nama_comp
            from mpm.tbl_tabcomp a 
            where a.`status` = 1
            GROUP BY concat(a.kode_comp, a.nocab)
        )d on c.site_code = d.site_code LEFT JOIN (
            select a.id, a.username
            from mpm.user a 
        )e on a.created_by = e.id
        $params
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        // die;
        return $this->db->query($query);
    }

    public function get_registrasi_program_by_supp_kategori_periode($advanced){

        $supp               = $advanced['supp'];
        $kategori           = $advanced['kategori'];
        $from               = $advanced['from'];
        $to                 = $advanced['to'];
        $pic                = $advanced['pic'];
        // $site_code_join     = $advanced['site_code_join'];

        if ($supp) {
            $params_supp = "where a.supp = '$supp'";
        }else{
            // $params_supp = "where a.supp = '123'";
            $params_supp = "";
        }

        if ($kategori) {
            $params_kategori = "and a.kategori = '$kategori'";
        }else{
            $params_kategori = '';
        }

        if ($from) {
            $params_periode = "and date(a.from) between '$from' and '$to'";
        }else{
            $params_periode = '';
        }

        if ($pic == "all") {
            $params_pic = '';
        }else{
            $params_pic = "and a.created_by = '$pic'";
        }

        if ($kategori == "all") {
            $params_kategori = "";
        }else{
            $params_kategori = "and a.kategori = '$kategori'";
        }



        // if ($site_code_join) {
        //     $params_site_code_join = "and a.site_code in ($site_code_join)";
        // }else{
        //     $params_site_code_join = '';
        // }

        $query = "
            select a.*, b.namasupp, c.username
            from management_claim.registrasi_program a left join (
                select a.supp, a.namasupp
                from mpm.tabsupp a
            )b on a.supp = b.supp left join (
                select a.id, a.username
                from mpm.user a 
            )c on a.created_by = c.id
            $params_supp $params_kategori $params_periode $params_pic
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        // die;

        return $this->db->query($query);
    }

    public function get_ajuan_claim_group_subbranch_by_idprogram($id_program){

        $query = "
            select *
            from management_claim.ajuan_claim a 
            where a.id_program in ($id_program)       
            GROUP BY a.site_code
        ";

        
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function get_ajuan_claim_group_subbranch_by_idprogram_sitecode($id_program, $site_code_join = ''){

        if ($site_code_join) {
            $params_site_code_join = "and a.site_code in ($site_code_join)";
        }else{
            $params_site_code_join = '';
        }

        $query = "
            select *
            from management_claim.ajuan_claim a 
            where a.id_program in ($id_program) $params_site_code_join    
            GROUP BY a.site_code
        ";

        
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function get_ajuan_claim_by_idprogram_sitecode($id_program, $site_code){
        $query = "
            select *
            from management_claim.ajuan_claim a 
            where a.id_program = $id_program and a.site_code = '$site_code'
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        return $this->db->query($query);
    }

    public function summary_ajuan_claim_group_status_by_idprogram($id_program){
        $query = "
            select	a.nama_status, count(a.status) as count_status
            from management_claim.ajuan_claim a
            where a.id_program = $id_program
            GROUP BY a.status
        ";
        return $this->db->query($query);
    }

    

}