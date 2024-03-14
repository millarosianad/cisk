<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Model_file_download extends CI_Model
{
    public function download_file($data)
    {

        $sql = "
            select a.file, a.nama, a.target, a.target_detail, a.created_date
            from mpm.file_download a
            where a.status = 1
            order by id desc
        ";
        $proses = $this->db->query($sql)->result();
        if ($proses) {
            return $proses;
        } else {
            return array();
        }
    }

    public function get_site_code($area){
        $thn = date('Y');
        if ($area == "1") {
            $where = "";
        }elseif($area == "2"){
            $where = "and area = 1";
        }elseif($area == "3"){
            $where = "and area = 2";
        }elseif($area == "4"){
            $where = "and area = 3";
        }

        $sql = "
            select a.kode
            from
            (
                select concat(a.kode_comp, a.nocab) as kode
                from mpm.tbl_tabcomp a
                where a.`status` = 1 $where
                GROUP BY kode
            )a INNER JOIN 
            (
                select concat(a.kode_comp, a.nocab) as kode
                from db_dp.t_dp a
                where a.tahun = $thn
                GROUP BY kode
            )b on a.kode = b.kode
            ";
        $proses = $this->db->query($sql)->result();

        return $proses;
    }

    public function versi_file()
    {

        $sql = "
            select *
            from db_temp.t_temp_file_versi a
            order by id desc
        ";
        $proses = $this->db->query($sql)->result();
        if ($proses) {
            return $proses;
        } else {
            return array();
        }
    }

    public function detail_file($data)
    {
        $versi = $data['versi'];
        $sql = "
            select a.*, b.nama_comp
            from db_temp.t_temp_file a left join mpm.tabcomp b on a.kode_comp = concat(b.kode_comp,b.nocab)
            where versi = '$versi'
        ";
        $proses = $this->db->query($sql)->result();
        if ($proses) {
            return $proses;
        } else {
            return array();
        }
    }

    public function get_versi_by_ID($versiID)
    {
        $sql = '
            select *
            from db_temp.t_temp_file_versi a
            where versi = "' . $versiID . '"
        ';
        $proses = $this->db->query($sql)->row();
        return $proses;
    }
}
