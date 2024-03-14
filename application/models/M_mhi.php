<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_mhi extends CI_Model 
{

    public function get_po_mhi(){
        $sql = "
            select 	a.id,a.nopo, a.company, d.branch_name, d.nama_comp,
                    a.userid,a.tipe,date_format(a.tglpo,'%Y-%m-%d') as tglpo,
                    sum(b.banyak) as u, sum(b.banyak * b.harga) as v
            from    mpm.po a INNER JOIN mpm.po_detail b
                        on a.id = b.id_ref LEFT JOIN
            (
                select a.id, a.username
                from mpm.`user` a
            )c on a.userid = c.id LEFT JOIN 
            (
                select a.kode, a.branch_name, a.nama_comp, a.kode_comp
                FROM
                (
                    select 	concat(a.kode_comp,a.nocab) as kode, a.branch_name,a.nama_comp, a.kode_comp
                    from 	mpm.tbl_tabcomp a
                    WHERE	a.`status` = 1
                    GROUP BY a.kode_comp
                )a
            )d on c.username = d.kode_comp
            where a.deleted = 0 and b.deleted = 0 and nopo not like '/mpm%' and a.nopo not like '%batal%' 
            GROUP BY a.nopo
            ORDER BY a.id DESC
            limit 10
        ";

        // echo "<pre>";
        // print_r($sql);
        // echo "</pre>";

        $proses = $this->db->query($sql)->result();
        return $proses;
    }

    public function get_po_mhi_by_id($id){
        $sql = "
            select 	a.id,a.nopo, a.company, d.branch_name, d.nama_comp,
                    a.userid,a.tipe,date_format(a.tglpo,'%Y-%m-%d') as tglpo,
                    b.kodeprod as kodeprod, e.NAMAPROD as namaprod, b.banyak
            from    mpm.po a INNER JOIN mpm.po_detail b
                        on a.id = b.id_ref LEFT JOIN
            (
                select a.id, a.username
                from mpm.`user` a
            )c on a.userid = c.id LEFT JOIN 
            (
                select a.kode, a.branch_name, a.nama_comp, a.kode_comp
                FROM
                (
                    select 	concat(a.kode_comp,a.nocab) as kode, a.branch_name,a.nama_comp, a.kode_comp
                    from 	mpm.tbl_tabcomp a
                    WHERE	a.`status` = 1
                    GROUP BY a.kode_comp
                )a
            )d on c.username = d.kode_comp LEFT JOIN
            (
                select a.kodeprod, a.NAMAPROD, a.ISISATUAN
                from mpm.tabprod a
            )e on b.kodeprod = e.kodeprod 
            where a.deleted = 0 and b.deleted = 0 and nopo not like '/mpm%' and a.nopo not like '%batal%' and a.id = $id
        ";

        // echo "<pre>";
        // print_r($sql);
        // echo "</pre>";

        $proses = $this->db->query($sql)->result();
        return $proses;
    }

    public function get_po_by_produk($id){

        $sql = "
        select 	a.id, b.kodeprod, b.namaprod, b.banyak, 
                c.asn_kodeprod, c.asn_unit, c.asn_tanggal_kirim, c.asn_nama_expedisi, c.asn_est_lead_time, c.asn_eta
        from mpm.po a INNER JOIN mpm.po_detail b 
            on a.id = b.id_ref LEFT JOIN
        (
            select a.id as id_asn, a.id_po, a.asn_kodeprod, a.asn_tanggal_kirim, a.asn_est_lead_time, a.asn_nama_expedisi, a.asn_unit, a.asn_eta
            from mhi.t_asn a
        )c on a.id = c.id_po and b.kodeprod = c.asn_kodeprod
        where a.id = $id and a.deleted = 0 and b.deleted = 0
        ";

        // echo "<pre>";
        // print_r($sql);
        // echo "</pre>";

        $proses = $this->db->query($sql)->result();
        return $proses;
    }

    public function proses_tambah_asn($data){
        date_default_timezone_set('Asia/Jakarta'); 
        
        $id = $this->session->userdata('id');
        $id_po = $data['id_po'];
        $asn_kodeprod = $data['asn_kodeprod'];
        
        $asn_tanggalKirim = trim($data['asn_tanggalKirim']);
        if ($asn_tanggalKirim == '') {
            $convert_asn_tanggalKirim='';
        }else{
            $convert_asn_tanggalKirim=strftime('%Y-%m-%d',strtotime($asn_tanggalKirim));
        }

        $asn_unit = $data['asn_unit'];
        $asn_nama_expedisi = $data['asn_nama_expedisi'];
        $asn_est_lead_time = $data['asn_est_lead_time'];
        $asn_eta = $data['asn_eta'];

        $asn_eta = trim($data['asn_eta']);
        if ($asn_eta == '') {
            $convert_asn_eta='';
        }else{
            $convert_asn_eta=strftime('%Y-%m-%d',strtotime($asn_eta));
        }

        // echo "<pre>";
        // echo "id_po : ".$id_po;
        // echo "asn_kodeprod : ".$asn_kodeprod;
        // echo "convert_asn_tanggalKirim : ".$convert_asn_tanggalKirim;
        // echo "asn_unit : ".$asn_unit;
        // echo "asn_nama_expedisi : ".$asn_nama_expedisi;
        // echo "asn_est_lead_time : ".$asn_est_lead_time;
        // echo "convert_asn_eta : ".$convert_asn_eta;
        // echo "</pre>";
        
        $sql_cek = "select * from mhi.t_asn where id_po = '$id_po'";
        $proses_cek = $this->db->query($sql_cek)->num_rows();
        if ($proses_cek) {
            
            $data = array(
                'id_po' => $data['id_po'],
                'asn_kodeprod' => $data['asn_kodeprod'],
                'asn_tanggal_kirim' => $convert_asn_tanggalKirim,
                'asn_unit' => $data['asn_unit'],
                'asn_nama_expedisi' => $data['asn_nama_expedisi'],
                'asn_est_lead_time' => $data['asn_est_lead_time'],
                'asn_eta' => $convert_asn_eta,
                'createdDate' => date('Y-m-d H:i:s'),
                'createdById' => $id,
                'lastUpdated' => date('Y-m-d H:i:s'),
                'lastUpdatedById' => $id
            );
            $this->db->where('id_po', $id_po);
            $proses = $this->db->update('mhi.t_asn', $data);
            
        }else{
            
            $data = array(
                'id_po' => $data['id_po'],
                'asn_kodeprod' => $data['asn_kodeprod'],
                'asn_tanggal_kirim' => $convert_asn_tanggalKirim,
                'asn_unit' => $data['asn_unit'],
                'asn_nama_expedisi' => $data['asn_nama_expedisi'],
                'asn_est_lead_time' => $data['asn_est_lead_time'],
                'asn_eta' => $convert_asn_eta,
                'createdDate' => date('Y-m-d H:i:s'),
                'createdById' => $id,
                'lastUpdated' => date('Y-m-d H:i:s'),
                'lastUpdatedById' => $id
            );
            $proses = $this->db->insert('mhi.t_asn', $data);
        }

        if ($proses) {
            redirect('mhi/tambah_asn/'.$id_po);
        }else{
            return false;
        }
    }

    public function get_po_sudah_asn(){
        $sql = "
        select 	a.id,a.nopo, a.company, d.branch_name, d.nama_comp,
                a.userid,a.tipe,date_format(a.tglpo,'%Y-%m-%d') as tglpo,
                sum(b.banyak) as u, sum(b.banyak * b.harga) as v
        from    mpm.po a INNER JOIN mpm.po_detail b
                        on a.id = b.id_ref LEFT JOIN
        (
            select a.id, a.username
            from mpm.`user` a
        )c on a.userid = c.id LEFT JOIN 
        (
            select a.kode, a.branch_name, a.nama_comp, a.kode_comp
            FROM
            (
                    select 	concat(a.kode_comp,a.nocab) as kode, a.branch_name,a.nama_comp, a.kode_comp
                    from 	mpm.tbl_tabcomp a
                    WHERE	a.`status` = 1
                    GROUP BY a.kode_comp
            )a
        )d on c.username = d.kode_comp INNER JOIN
        (
            select a.id as id_asn, a.id_po, a.asn_kodeprod, a.asn_tanggal_kirim, a.asn_est_lead_time, a.asn_nama_expedisi, a.asn_unit, a.asn_eta
            from mhi.t_asn a
        )e on a.id = e.id_po and b.kodeprod = e.asn_kodeprod
        where a.deleted = 0 and b.deleted = 0 and nopo not like '/mpm%' and a.nopo not like '%batal%' 
        GROUP BY a.nopo
        ORDER BY a.id DESC
        limit 10
        ";

        // echo "<pre>";
        // print_r($sql);
        // echo "</pre>";

        $proses = $this->db->query($sql)->result();
        return $proses;
    }

}