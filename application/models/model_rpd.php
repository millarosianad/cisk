<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Model_rpd extends CI_Model
{

    public function hak_akses($userid)
    {
        // echo "userid : ".$userid;

        if ($userid == '297' || $userid == '561' || $userid == '444' || $userid == '231' || $userid == '547' || $userid == '18' || $userid == '557' || $userid == '362') {
            return true;
        } else {
            return $userid;
        }
    }

    public function get_pelaksana($userid)
    {
        return $this->db->get_where('mpm.user', array('id' => $userid));
    }

    public function list_user()
    {
        $id = $this->session->userdata('id');

        $sql = "
                select  a.id,a.kode_dp, a.username, a.company 
                from    mpm.`user` a
                where   active = 1
                order by a.company
                
            ";
        return $this->db->query($sql);
    }

    public function get_history($id = '', $hak_akses = '')
    {

        if ($hak_akses == '1') {
            $hak_aksesx = '';
        } else {
            $hak_aksesx = "and a.created_by in ($hak_akses)";
        }

        if ($id == '') {
            $idx = '';
        } else {
            $idx = "and a.id = $id";
        }
        $sql = "
        select 	a.id, a.kode, a.pelaksana, a.maksud_perjalanan_dinas, a.tanggal_berangkat, a.tanggal_tiba,
				a.tempat_berangkat, a.tempat_tujuan, a.keterangan, a.created_at, a.updated_at, a.status_approval, 
                a.nama_status_approval, a.approved_at, a.approved_by, a.signature
        from site.t_rpd a LEFT JOIN
        (
            select a.id, a.username 
            from mpm.user a 
        )b on a.created_by = b.id
        where a.deleted = 0 $idx $hak_aksesx
        order by a.id desc
        ";
        // echo "<pre><br><br><br><br>";
        // print_r($sql);
        // echo "</pre>";

        $proses = $this->db->query($sql);
        return $proses;
    }

    public function generate($userid, $tanggal_berangkat)
    {
        $get_by_user = $this->db->get_where("site.t_rpd", array("created_by" => $userid))->num_rows();
        if (!$get_by_user) { 

            // TANGGAL_BERANGKAT = 20221001 = 2022
            // KODE = MPM/RPD/2022/No.297-1;
            // urut = 1 

            // jika baru pertama kali
            // $kode = "MPM/RPD/" . date('Y', strtotime($tanggal_berangkat)) . "/No." . $userid . "-1";


            $reserve_nomor_params = "MPM_RPD/" . date('Y') . date('m'). "/" . $userid . "-001";



            
        } else { 

            //jika sudah ada sebelumnya

            $sql = "
                select  a.kode, right(a.kode,1) as urut
                from    site.t_rpd a
                where   a.created_by = '$userid' and year(a.tanggal_berangkat)= year('$tanggal_berangkat')
                ORDER BY a.id desc
                limit 1
            ";

            // // print_r($sql);
            // $query = $this->db->query($sql);

            // if ($query->num_rows() > 0) {
            //     // echo "a";
            //     $row = $query->row();
            //     $urut = $row->urut + 1;
            //     // $kode = "MPM/".$param_kategori."/".$urut;
            //     $kode = "MPM_RPD/" . date('Y', strtotime($tanggal_berangkat)) . "/No." . $userid . '-' . $urut;
            // } else {
            //     // echo "b";
            //     $row = $query->row();
            //     $urut = $row->urut + 1;
            //     // $kode = "MPM/".$param_kategori."/".$urut;
            //     $kode = "MPM/RPD/" . date('Y', strtotime($tanggal_berangkat)) . "/No." . $userid . '-' . $urut;
            // }

            $sql = "
                select  right(a.kode,3) as urut
                from    site.t_rpd a
                where   a.created_by = '$userid'
                ORDER BY right(a.kode,3) desc
                limit 1
            ";

            // $sql = "
            //     select right(a.kode,3) as urut
            //     from site.t_dc_do a
            //     GROUP BY a.kode
            //     ORDER BY right(a.kode,3) desc
            //     limit 1
            // ";

            // MPM_LBM/202209-099

            // URUT = 099
            // URUT = 99
            // RESERVE_NOMOR = 99 + 1 = 100
            // RESERVE_NOMOR_PARAMS = MPM_LBM/202209-002
            // RESERVE_NOMOR_PARAMS = MPM_LBM/202209-100


            $proses = $this->db->query($sql)->row();
            $reserve_nomor = $proses->urut + 1;
            if (strlen($reserve_nomor) === 1) {
                $reserve_nomor_params = "MPM_RPD/" . date('Y') . date('m') . "/" . $userid . "-00" . $reserve_nomor;
            } else if (strlen($reserve_nomor) === 2) {
                $reserve_nomor_params = "MPM_RPD/" . date('Y') . date('m') . "/" . $userid . "-0" . $reserve_nomor;
            } else {
                $reserve_nomor_params = "MPM_RPD/" . date('Y') . date('m') . "/" . $userid . "-" . $reserve_nomor;
            }


        }

        
        // echo "kode : ".$kode;
        // die;
        return $reserve_nomor_params;
    }

    public function insert($table, $data)
    {
        $insert = $this->db->insert($table, $data);
        $id_rpd = $this->db->insert_id();
        return $id_rpd;
    }

    public function get_aktivitas($id_rpd = '', $id_aktivitas = '' )
    {
        if ($id_rpd != '') {
            $rpd = "and a.id_rpd = $id_rpd";
        }else{
            $rpd = " ";

        }

        if ($id_aktivitas != '') {
            $aktivitas = "and a.id = $id_aktivitas";
        }else{
            $aktivitas = " ";
        }
        $sql = "
                SELECT a.*, b.nama_kategori FROM site.t_rpd_aktivitas a
                LEFT JOIN site.m_rpd_kategori_biaya b on a.jenis_pengeluaran = b.id
                where a.deleted = 0 $rpd $aktivitas
            ";
        $proses = $this->db->query($sql);
        return $proses;
    }

    public function get_realisasi($id_aktivitas = '', $id_realisasi = '', $id_rpd ='')
    {
        if ($id_realisasi == '') {
            $realisasi = '';
        } else {
            $realisasi = "and a.id = $id_realisasi";
        }

        if ($id_aktivitas == '') {
            $aktivitas = '';
        } else {
            $aktivitas = "and a.aktivitas_id = $id_aktivitas";
        }

        if ($id_rpd == '') {
            $rpd = '';
        } else {
            $rpd = "and a.rpd_id = $id_rpd";
        }
        
        $sql = "
        SELECT a.*, b.rencana
        FROM(
            SELECT a.*, SUBSTR(a.tanggal,1,16) as tanggal_realisasi
            from site.t_rpd_realisasi a
            where a.deleted = 0 $aktivitas $realisasi $rpd
        )a LEFT JOIN
        (
            SELECT a.id, a.rencana
            FROM site.t_rpd_aktivitas a
            where a.deleted = 0
        )b on a.aktivitas_id = b.id
        ";
        $proses = $this->db->query($sql);
        return $proses;
    }

    public function get_id_rpd($signature)
    {
        $get_id_rpd = $this->db->get_where('site.t_rpd', array('signature' => $signature))->row();
        return $get_id_rpd->id;
    }

    public function getMaster_karyawan($id = '', $userid = '')
    {
        if ($id == '') {
            $idx = '';
        } else {
            $idx = "and a.id = $id";
        }

        if ($userid == '') {
            $useridx = '';
        } else {
            $useridx = "and a.userid = $userid and a.`status` = 1";
        }
        $sql = "
            SELECT a.id, a.userid, b.username as nama_karyawan, b.email as email_karyawan, a.atasan_id, c.username as nama_atasan, c.email as email_atasan, a.`status`
            FROM site.m_karyawan a
            LEFT JOIN mpm.`user` b ON a.userid = b.id
            LEFT JOIN mpm.`user` c ON a.atasan_id= c.id
            where a.deleted = '0' $idx $useridx
        ";

        $proses = $this->db->query($sql);
        
        return $proses;
    }

    public function getMaster_biaya($id = '')
    {
        if ($id == '') {
            $idx = '';
        } else {
            $idx = "and a.id = $id";
        }
        
        $sql = "
            SELECT a.id, a.userid, c.username, a.kategori_id, b.nama_kategori, a.biaya, a.deleted
            FROM site.m_rpd_biaya a
            LEFT JOIN site.m_rpd_kategori_biaya b on a.kategori_id = b.id
            LEFT JOIN mpm.`user` c on a.userid = c.id
            where a.deleted = 0 $idx
        ";

        return $this->db->query($sql);
    }

    public function getKategori_biaya()
    {
        $this->db->select('*');
        $this->db->where('status','1');
        return $this->db->get('site.m_rpd_kategori_biaya');
    }

    public function get_pdf($signature)
    {   
        $sql = "
                SELECT a.kode, substr(a.tanggal_berangkat,1,10) as tanggal_berangkat, substr(a.tanggal_tiba,1,10) as tanggal_tiba, a.tempat_tujuan, a.tempat_berangkat, a.maksud_perjalanan_dinas, a.pelaksana, a.keterangan,
                        b. rencana as rencana_aktivitas, b.tanggal as tanggal_aktivitas, b.detail as detail_aktivitas, d.kategori_id, d.nama_kategori,
                        b.limit_budget as limit_budget, b.nominal_biaya as nominal_biaya_aktivitas, b.keterangan as keterangan_aktivitas,
                        c.tanggal as tanggal_realisasi, c.detail as detail_realisasi, c.jenis_pengeluaran as kategori_pengeluaran_realisasi,
                        c.nominal_biaya as nominal_biaya_realisasi, 
                        c.keterangan as keterangan_realisasi, a.created_at
                FROM
                (
                    SELECT * 
                    FROM site.t_rpd a
                    WHERE MD5(a.id) = 'c4ca4238a0b923820dcc509a6f75849b' and a.deleted = 0
                )a LEFT JOIN
                ( 
                    SELECT *
                    FROM site.t_rpd_aktivitas a
                )b on a.id = b.id_rpd
                    LEFT JOIN
                (
                    SELECT *
                    FROM site.t_rpd_realisasi a
                                    WHERE a.deleted = 0
                )c on a.id = c.aktivitas_id
                LEFT JOIN
                (
                    SELECT a.id, a.kategori_id, b.nama_kategori FROM site.m_rpd_biaya a
                    LEFT JOIN site.m_rpd_kategori_biaya b on a.kategori_id = b.id
                )d on b.jenis_pengeluaran = d.id
                Group By $group_by
        ";
        return $this->db->query($sql);
    }
}
