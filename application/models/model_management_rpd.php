<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_management_rpd extends CI_Model 
{
    public function get_pengajuan($id = ""){

        $userid = $this->session->userdata('id');
        $username = $this->session->userdata('username');

        if ($username == 'suffy' || $username == 'ratri'  || $username == 'nanita' || $username == 'hendra' || $username == 'milla') {
            $params_admin = "where a.deleted_by is null";
            $params = "";
        }elseif ($username == 'evlin'|| $username == 'anto' || $username == 'imas' || $username == 'admin_deltomed') {
            $params_admin = "where  ((c.supp = '001') and a.deleted_by is null )  or (a.created_by = '613')"; 
                if ($id) {
                    $params = "and a.id = $id"; 
                }else{
                    $params = "";
                }
        }else{

            $params_admin = "where  (a.created_by = $userid or a.verifikasi1_by  = $userid or a.verifikasi2_by  = $userid) and a.deleted_by is null";

            if ($id) {
                $params = "and a.id = $id";
            }else{
                $params = "";
            }
        }

        $query = "
        select 	a.no_rpd, a.pelaksana, a.maksud_perjalanan_dinas, a.created_at, a.attachment_akomodasi, b.attachment_realisasi,
                date(a.tanggal_berangkat) as tanggal_berangkat, a.tempat_berangkat, a.tanggal_tiba, a.tempat_tiba,
                b.total_biaya, a.signature, a.nama_status, a.verifikasi1_by as userid_verifikasi1, 
                a.verifikasi2_by as userid_verifikasi2, a.verifikasi1_name, a.verifikasi2_name, a.jumlah_verifikasi,
                a.verifikasi1_status, a.verifikasi2_status, a.jumlah_verifikasi,
                if(a.jumlah_verifikasi = 1, a.verifikasi1_status, 
                if(a.jumlah_verifikasi = 2, a.verifikasi1_status * a.verifikasi2_status, null)) as status, a.signature, a.status_realisasi,
                a.tanggal_mulai, a.tanggal_akhir
        from management_rpd.pengajuan a LEFT JOIN 
        (
            select a.id_pengajuan, sum(a.biaya) as total_biaya, a.attachment_realisasi
            from management_rpd.aktivitas a 
            where a.status_claim = 1 and a.deleted_at is null
            GROUP BY a.id_pengajuan
        )b on a.id = b.id_pengajuan LEFT JOIN(
            select a.id, a.username, a.supp
            from mpm.user a
        )c on a.pelaksana = c.username
            $params_admin
            $params
           
            order by a.id desc
        ";

        echo "<pre>";
        print_r($query);
        echo "</pre>";

        return $this->db->query($query);
    }

    public function get_pengajuan_bysignature($signature = ""){
        if ($signature) {
            $params = "where a.signature = '$signature'";
        }else{
            $params = "";
        }
        $query = "
            select 	a.id, a.no_rpd, a.pelaksana, a.jabatan, a.radius_perjalanan, a.attachment_akomodasi, a.keterangan_akomodasi, 
                    a.attachment_radius_perjalanan, a.tanggal_mulai, a.tanggal_akhir, a.jumlah_verifikasi, a.maksud_perjalanan_dinas, a.tanggal_berangkat, a.tempat_berangkat,b.userid_pelaksana as userid_pelaksana, 
                    a.tanggal_tiba, a.tempat_tiba, a.status, a.nama_status, a.signature, a.created_by, a.created_at,
                    IF(a.verifikasi1_by is null,b.userid_verifikasi1,a.verifikasi1_by) as userid_verifikasi1,
                    IF(a.verifikasi2_by is null,b.userid_verifikasi2,a.verifikasi2_by) as userid_verifikasi2,
                    c.username as username_verifikasi1, d.username as username_verifikasi2,
                    a.verifikasi1_name, a.verifikasi2_name, a.verifikasi2_at, a.verifikasi1_at, a.verifikasi1_keterangan, a.verifikasi2_keterangan,
                    a.verifikasi1_ttd, a.verifikasi2_ttd, a.jumlah_verifikasi, a.signature, a.status_realisasi, a.verifikasi1_name, a.verifikasi2_name
            from management_rpd.pengajuan a left join 
            (
                select a.userid_pelaksana, a.userid_verifikasi1, a.userid_verifikasi2
                from management_rpd.m_karyawan a 
            )b on a.created_by = b.userid_pelaksana LEFT JOIN (
                select a.id, a.username
                from mpm.user a 
            )c on a.verifikasi1_by = c.id LEFT JOIN (
                select a.id, a.username
                from mpm.user a 
            )d on a.verifikasi2_by = d.id
            $params
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        // die;

        return $this->db->query($query);
    }

    public function get_aktivitas($id){
        if ($id) {
            $params = "and a.id_pengajuan = $id";
        }else{
            $params = "";
        }
        $query = "
            select *
            from management_rpd.aktivitas a 
            where a.deleted_at is null
            $params
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        // die;

        return $this->db->query($query);
    }

    public function get_pengajuan_akomodasi($id){
        $query = "
            select *
            from management_rpd.pengajuan a 
            where a.deleted_at is null and id = $id
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        // die;

        return $this->db->query($query);
    }
    public function get_total_biaya($id = ""){
        if ($id) {
            $params = "and a.id_pengajuan = $id";
        }else{
            $params = "";
        }
        $query = "
            select sum(a.biaya) as total_biaya
            from management_rpd.aktivitas a 
            where a.status_claim = 1 and a.deleted_at is null
            $params
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        return $this->db->query($query);
    }

    public function generate($created_at){

        $bulan_now = date('m',strtotime($created_at));

        $romawi = $this->getRomawi($bulan_now);

        $tahun_now = date('Y');

        $query = "
            select a.no_rpd, substr(a.no_rpd,5,3) as urut
            from management_rpd.pengajuan a left join (
                select a.id, a.username
                from mpm.user a 
            )b on a.created_by = b.id
            where year(a.created_at) = $tahun_now and month(a.created_at) = $bulan_now and a.no_rpd is not null
            ORDER BY a.id desc
            limit 1
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        // die;

        $no_rpd_current = $this->db->query($query);
        if ($no_rpd_current->num_rows() > 0) {
            
            $params_urut = $no_rpd_current->row()->urut + 1;
            // echo $params_urut;

            if (strlen($params_urut) === 1) {
                $generate = "RPD-00$params_urut/MPM/$romawi/$tahun_now";
            }elseif (strlen($params_urut) === 2) {
                $generate = "RPD-0$params_urut/MPM/$romawi/$tahun_now";
            }else{
                $generate = "RPD-$params_urut/MPM/$romawi/$tahun_now";
            }
        }else{
            $generate = "RPD-001/MPM/$romawi/$tahun_now";
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

    public function get_user($id){
        $query = "
            select *
            from mpm.user a where a.id = $id
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        return $this->db->query($query);
    }

    public function cek_realisasi($id){

        $query = "
            select *
            from management_rpd.aktivitas a 
            where a.deleted_at is null and a.id_pengajuan = $id and a.status_realisasi is null
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        return $this->db->query($query);
    }

}