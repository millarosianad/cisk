<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_management_asset extends CI_Model 
{   
    public function my_asset($userid)
    {
        $sql = "
            SELECT a.*, b.userid_pengguna, b.tgl_penyerahan FROM management_asset.asset a
            LEFT JOIN management_asset.penyerahan_asset b on a.id = b.id_ref
            WHERE a.deleted = 0 and b.flag_pengguna = 1 and b.userid_pengguna = $userid
            ";

        return $this->db->query($sql);
    }
    public function generate_draft($created_at)
    {

        $bulan_now = date('m',strtotime($created_at));

        $romawi = $this->getRomawi($bulan_now);

        $tahun_now = date('Y');

        $query = "
            select a.no_pr, substr(a.no_pr,5,3) as urut
            from management_asset.pengajuan a left join (
                select a.id, a.username
                from mpm.user a 
            )b on a.created_by = b.id
            where year(a.created_at) = $tahun_now and month(a.created_at) = $bulan_now and a.no_pr is not null
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
                $generate = "DRAFT-PR-00$params_urut/MPM/$romawi/$tahun_now";
            }elseif (strlen($params_urut) === 2) {
                $generate = "DRAFT-PR-0$params_urut/MPM/$romawi/$tahun_now";
            }else{
                $generate = "DRAFT-PR-$params_urut/MPM/$romawi/$tahun_now";
            }
        }else{
            $generate = "DRAFT-PR-001/MPM/$romawi/$tahun_now";
        }
        // die;
        return $generate;
    }

    public function generate($created_at)
    {

        $bulan_now = date('m',strtotime($created_at));

        $romawi = $this->getRomawi($bulan_now);

        $tahun_now = date('Y');

        $query = "
            select a.no_pr, substr(a.no_pr,5,3) as urut
            from management_asset.pengajuan a left join (
                select a.id, a.username
                from mpm.user a 
            )b on a.created_by = b.id
            where year(a.created_at) = $tahun_now and month(a.created_at) = $bulan_now and (a.no_pr is not null or a.no_pr not like 'DRAFT%')
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
                $generate = "PR-00$params_urut/MPM/$romawi/$tahun_now";
            }elseif (strlen($params_urut) === 2) {
                $generate = "PR-0$params_urut/MPM/$romawi/$tahun_now";
            }else{
                $generate = "PR-$params_urut/MPM/$romawi/$tahun_now";
            }
        }else{
            $generate = "PR-001/MPM/$romawi/$tahun_now";
        }
        // die;
        return $generate;
    }

    function getRomawi($bln)
    {
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
    
    public function pengajuan_asset()
    {

        $sql = "
            SELECT a.*, b.total_biaya
            FROM
            (
                SELECT a.*, b.username
                FROM management_asset.pengajuan a 
                LEFT JOIN mpm.user b on a.created_by = b.id
            )a
            LEFT JOIN
            (
            SELECT a.id_ref, SUM(a.harga) as total_biaya
            FROM management_asset.pengajuan_detail a
            where a.deleted = 0
            GROUP BY a.id_ref
            )b on a.id = b.id_ref
            order by a.id desc
        ";


        return $this->db->query($sql);
    }

    public function pengajuan_asset_by_m_karyawan($userid = 0)
    {
        $sql = "
        SELECT b.*
        FROM
        (
            SELECT * FROM management_rpd.m_karyawan a
            WHERE a.userid_verifikasi1 = $userid
        )a
        LEFT JOIN
        (
            SELECT a.*, b.total_biaya
            FROM
            (
                SELECT a.*, b.username
                FROM management_asset.pengajuan a 
                LEFT JOIN mpm.user b on a.created_by = b.id
            )a
            LEFT JOIN
            (
            SELECT a.id_ref, SUM(a.harga) as total_biaya
            FROM management_asset.pengajuan_detail a
            where a.deleted = 0
            GROUP BY a.id_ref
            )b on a.id = b.id_ref
            order by a.id desc
        )b on a.userid_pelaksana = b.created_by

        union all
        
        SELECT b.*
        FROM
        (
            SELECT * FROM management_rpd.m_karyawan a
            WHERE a.userid_pelaksana = $userid
        )a
        LEFT JOIN
        (
            SELECT a.*, b.total_biaya
            FROM
            (
                SELECT a.*, b.username
                FROM management_asset.pengajuan a 
                LEFT JOIN mpm.user b on a.created_by = b.id
            )a
            LEFT JOIN
            (
            SELECT a.id_ref, SUM(a.harga) as total_biaya
            FROM management_asset.pengajuan_detail a
            where a.deleted = 0
            GROUP BY a.id_ref
            )b on a.id = b.id_ref
            order by a.id desc
        )b on a.userid_pelaksana = b.created_by
        ";

        return $this->db->query($sql);
    }

    public function pengajuan_asset_by_signature($signature)
    {

        $sql = "
            SELECT a.*, b.total_biaya
            FROM
            (
                SELECT a.*, b.username
                FROM management_asset.pengajuan a 
                LEFT JOIN mpm.user b on a.created_by = b.id
                where a.signature = '$signature'
            )a
            LEFT JOIN
            (
            SELECT a.id_ref, SUM(a.harga) as total_biaya
            FROM management_asset.pengajuan_detail a
            where a.deleted = 0
            GROUP BY a.id_ref
            )b on a.id = b.id_ref
            order by a.id desc
        ";


        return $this->db->query($sql);
    }

    public function pengajuan_asset_detail($signature)
    {
        $sql = "
            SELECT a.*
            FROM management_asset.pengajuan_detail a
            where a.deleted = 0 and a.signature = '$signature'
        ";

        return $this->db->query($sql);
    }
    
    public function data_asset($id = 0)
    {
        if ($id == 0) {
            $where = "";
        } else {
            $where = "and a.id = $id";
        }
        
        $sql = "
            SELECT a.*, b.username
            FROM
            (
                SELECT a.*, b.namagrup
                FROM management_asset.asset a
                LEFT JOIN mpm.grupasset b on a.grupid = b.id
                where a.deleted = 0 $where
            )a
            LEFT JOIN
            (
                SELECT a.*, b.username
                FROM management_asset.penyerahan_asset a
                LEFT JOIN mpm.user b on a.userid_pengguna = b.id
                where a.flag_pengguna = 1
            )b on a.id = b.id_ref
            order by a.id desc
        ";

        return $this->db->query($sql);
    }

    public function data_asset_penyerahan($id = 0)
    {
        
        $sql = "
            SELECT a.*, b.username
            FROM management_asset.penyerahan_asset a
            LEFT JOIN mpm.user b on a.userid_pengguna = b.id
            where a.id_ref = $id
            order by a.id desc
        ";

        return $this->db->query($sql);
    }

    public function grup_asset()
    {
        $sql='select id,namagrup from mpm.grupasset';
        return $this->db->query($sql);
    }

    public function getAssets_sds($kode)
    {   
        $userid = $this->session->userdata('id');
        $from = $kode['from'];
        $to = $kode['to'];
        $created_at = $kode['created_at'];
        
        $serverName = "backup.muliaputramandiri.com"; //serverName\instanceName, portNumber (default is 1433)
        $connectionInfo = array("Database" => "", "UID" => "sa", "PWD" => "mpm12345");
        $conn = sqlsrv_connect($serverName, $connectionInfo);

        // echo "<pre><br><br><br><br><br>";
        // print_r($nv);
        // print_r($from);
        // print_r($to);
        // echo "</pre>";
        if ($conn) {
            echo "<script>
            alert('Koneksi dengan Server SDS Berhasil');
            </script>";
            $sql1 = "
                    SELECT	a.*
                    FROM    dbsls.dbo.t_gl_jurnal a
                    WHERE   a.coa_id in ('1120000110','1120000120','1120000130','1120000140','1120000150','1120000160', '1120000170') and  (a.tgl_entry >= '$from' and a.tgl_entry <= '$to')
            ";

            $query = sqlsrv_query($conn, $sql1);

            $this->db->query("delete from management_asset.temp_asset_sds_jurnal where created_by = $userid");

            if ($query) {
                while ($data = sqlsrv_fetch_array($query)){
                    $data = array(
                                    'siteid' =>$data['siteid'],
                                    'nojurnal' =>$data['nojurnal'],
                                    'coa_id' =>$data['coa_id'],
                                    'nourut' =>$data['nourut'],
                                    'description' =>$data['description'],
                                    'tgl_trans' =>$data['tgl_trans']->format('Y-m-d H:i:s'),
                                    'debet' =>$data['debet'],
                                    'kredit' =>$data['kredit'],
                                    'keterangan' =>$data['keterangan'],
                                    'currency_id' =>$data['currency_id'],
                                    'rate_currency' =>$data['rate_currency'],
                                    'group_saldo' =>$data['group_saldo'],
                                    'tgl_entry' =>$data['tgl_entry']->format('Y-m-d H:i:s'),
                                    'userid' =>$data['userid'],
                                    'flag_jurnal' =>$data['flag_jurnal'],
                                    'created_by' => $userid,
                                    'created_at' =>  $created_at
                                );

                    $this->db->insert('management_asset.temp_asset_sds_jurnal',$data);
                }
            }
        }
    }

    public function getAssets_temp($userid)
    {
        $sql = "
            SELECT * FROM management_asset.temp_asset_sds_jurnal
            WHERE created_by = $userid AND nojurnal not in (SELECT b.kode FROM mpm.asset b)
        ";
        return $this->db->query($sql);
    }
    
    public function getAssets_temp_by_kode($kode_sds)
    {
        $sql = "
            SELECT * FROM management_asset.temp_asset_sds_jurnal
            WHERE concat (nojurnal,'-',nourut) = '$kode_sds'
        ";

        return $this->db->query($sql);
    }

    public function getUser($id = 0)
    {
        if ($id == 0) {
            $where = '';
        } else {
            $where = "and id = $id";
        }
        
        $sql="
            select id,username, email from mpm.user
            where level not in (4,5,6) and supp = 000 and active = 1 $where
            order by username
            ";
        return $this->db->query($sql);
    }
}
?>