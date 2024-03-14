<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_dashboard extends CI_Model
{
    public function get_bulan_sekarang(){
        $tahun_sekarang = date('Y');
        $bulan_sekarang = date('m');
        $tanggal_sekarang = date('d');
        // echo "<br><br><br>bulan : ".$bulan_sekarang;
        // echo "tanggal : ".$tanggal_sekarang;

        if ($tanggal_sekarang <= '10') {
            // echo "<br><br>aaaa";
            $bulan_sekarang_x = (int)$bulan_sekarang - 1; 
        }else{
            // echo "<br><br>bbb";
            $bulan_sekarang_x = $bulan_sekarang;
        
        }
;

        if ($bulan_sekarang_x == '1') {
            $bulan = "Januari";
        }elseif ($bulan_sekarang_x == '2') {
            $bulan = "Februari";
        }elseif ($bulan_sekarang_x == '3') {
            $bulan = "Maret";
        }elseif ($bulan_sekarang_x == '4') {
            $bulan = "April";
        }elseif ($bulan_sekarang_x == '5') {
            $bulan = "Mei";
        }elseif ($bulan_sekarang_x == '6') {
            $bulan = "Juni";
        }elseif ($bulan_sekarang_x == '7') {
            $bulan = "Juli";
        }elseif ($bulan_sekarang_x == '8') {
            $bulan = "Agustus";
        }elseif ($bulan_sekarang_x == '9') {
            $bulan = "September";
        }elseif ($bulan_sekarang_x == '10') {
            $bulan = "Oktober";
        }elseif ($bulan_sekarang_x == '11') {
            $bulan = "November";
        }elseif ($bulan_sekarang_x == '12') {
            $bulan = "Desember";
        }else{
            $bulan = "Desember";
        }
        return $bulan;
        // echo "bulan_sekarang_x : ".$bulan_sekarang_x;
    }

    public function get_closing(){
        $tahun_sekarang = date('Y');
        $bulan_sekarang = date('m');
        $tanggal_sekarang = date('d');
        // echo "bulan : ".$bulan_sekarang;
        // echo "tanggal : ".$tanggal_sekarang;

        if ($tanggal_sekarang <= '10') {
            $bulan_sekarang_x = (int)$bulan_sekarang - 1; 
            // $bulan_sekarang_x = 12;
        }else{
            $bulan_sekarang_x = $bulan_sekarang;
        }
        // echo "bulan_sekarang_x : ".$bulan_sekarang_x;

        
        $sql = "
        select 	if(status_closing = 1, 'Sudah Closing','Belum Closing') as status, count(status_closing) as jumlah_dp
        from 	mpm.upload
        where 	bulan = $bulan_sekarang_x and tahun = $tahun_sekarang and id in (
            select max(id)
            from mpm.upload
            where tahun = $tahun_sekarang and bulan = $bulan_sekarang_x
            GROUP BY substring(filename,3,2)
        )and userid not in ('0','289')
        GROUP BY status_closing
        ORDER BY status_closing desc
        ";
        $proses = $this->db->query($sql)->result();
        // echo "<pre>";
        // echo "<br><br><br><br>";
        // print_r($sql);
        // echo "</pre>";
        return $proses;
    }

    public function get_dp_belum_closing(){
        $tahun_sekarang = date('Y');
        $bulan_sekarang = date('m');
        $tanggal_sekarang = date('d');
        // echo "bulan : ".$bulan_sekarang;
        // echo "tanggal : ".$tanggal_sekarang;

        if ($tanggal_sekarang <= '10') {
            $bulan_sekarang_x = (int)$bulan_sekarang - 1; 
            // $bulan_sekarang_x = 12;
        }else{
            $bulan_sekarang_x = $bulan_sekarang;
        }

        $sql = "
        select a.userid, a.nocab, b.username, concat(b.username,a.nocab) as kode, c.branch_name, c.nama_comp
        from
        (
            select 	id ,userid, filename, tahun, bulan, status_closing,substring(filename,3,2) as nocab,lastupload
            from 	mpm.upload
            where 	bulan = $bulan_sekarang_x and tahun = $tahun_sekarang and id in (
                select max(id)
                from mpm.upload
                where tahun = $tahun_sekarang and bulan = $bulan_sekarang_x
                GROUP BY substring(filename,3,2)
            )and userid not in ('0','289') and status_closing = 0
        )a LEFT JOIN mpm.`user` b on a.userid = b.id LEFT JOIN
        (
            select concat(a.kode_comp,a.nocab) as kode, a.branch_name, a.nama_comp, a.urutan
            from mpm.tbl_tabcomp a
            where a.status = 1
            GROUP BY concat(a.kode_comp,a.nocab)
        )c on c.kode = concat(b.username,a.nocab)
        where b.active = 1
        ORDER BY c.urutan
        limit 9
        ";
        $proses = $this->db->query($sql)->result();
        return $proses;
    }

    public function get_tanggal_data(){
        $tahun_sekarang = date('Y');
        $bulan_sekarang = date('m');
        $tanggal_sekarang = date('d');
        // echo "bulan : ".$bulan_sekarang;
        // echo "tanggal : ".$tanggal_sekarang;

        if ($tanggal_sekarang <= '10') {
            $bulan_sekarang_x = (int)$bulan_sekarang - 1; 
            // $bulan_sekarang_x = 12;
        }else{
            $bulan_sekarang_x = $bulan_sekarang;
        }

        $sql = "
        select a.kode,b.branch_name,b.nama_comp, a.tanggal_data
        FROM
        (
            select concat(a.kode_comp,a.nocab) as kode,max(hrdok) as tanggal_data
            from data$tahun_sekarang.fi a
            where bulan = $bulan_sekarang_x
            GROUP BY kode
        )a LEFT JOIN
        (
            select concat(a.kode_comp,a.nocab) as kode, a.branch_name,a.nama_comp, a.urutan
            from mpm.tbl_tabcomp a
            where a.`status` = 1
            GROUP BY concat(a.kode_comp,a.nocab)
        )b on a.kode = b.kode
        ORDER BY urutan
        ";

        $proses = $this->db->query($sql)->result();
        return $proses;

    }

    public function detail_kalender_data($userid){

        $query = "
            select *
            from mpm.upload a 
            where a.userid = $userid
            ORDER BY id desc
            limit 30
        ";

        return $this->db->query($query);

    }


}