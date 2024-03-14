<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_relokasi extends CI_Model 
{
    function history_relokasi($kode_alamat, $signature = '')
	{
        if ($kode_alamat == '') {
            if ($signature == '') {
                $params_kode_alamat = '';
                $params_signature = '';
            }else{
                $params_kode_alamat = '';
                $params_signature = "where a.signature = '$signature'";
            }
        }else{
            if ($signature == '') {
                $params_kode_alamat = "where a.from_site in ($kode_alamat) or a.to_site in ($kode_alamat)";
                $params_signature = '';
            }else{
                $params_kode_alamat = "where a.from_site in ($kode_alamat) or a.to_site in ($kode_alamat)";
                $params_signature = "and a.signature = '$signature'";
            }
            
        }

        $query = "
        select 	a.id, a.no_relokasi, a.from_site, b.branch_name as from_branch, b.nama_comp as from_nama_comp, 
                a.to_site, c.branch_name as to_branch, c.nama_comp as to_nama_comp,
                a.nama, a.tanggal_pengajuan, a.created_at, d.username, a.signature, a.principal,
                if(a.principal = '001-herbal', 'deltomed herbal', if(a.principal = '001-herbana', 'deltomed herbana', a.principal)), 
                if(e.namasupp is null and a.principal = '001-herbal','DELTOMED HERBAL', if(e.namasupp is null and a.principal = '001-herbana', 'DELTOMED HERBANA', e.namasupp)) as namasupp, a.status, a.nama_status, a.approve_supplychain_at,
                approve_supplychain_by, approve_supplychain_signature, approve_finance_at, approve_finance_by, 
                approve_finance_signature, a.alasan, a.file_surat_jalan
        from site.t_relokasi_header a LEFT JOIN 
        (
            select concat(a.kode_comp, a.nocab) as site_code, a.branch_name, a.nama_comp
            from mpm.tbl_tabcomp a 
            where a.`status` = 1
            GROUP BY concat(a.kode_comp, a.nocab)
        )b on a.from_site = b.site_code LEFT JOIN 
        (
            select concat(a.kode_comp, a.nocab) as site_code, a.branch_name, a.nama_comp
            from mpm.tbl_tabcomp a 
            where a.`status` = 1
            GROUP BY concat(a.kode_comp, a.nocab)
        )c on a.to_site = c.site_code left join (
            select a.id, a.username
            from mpm.user a 
        )d on a.created_by = d.id left join (
            select a.supp, a.namasupp
            from mpm.tabsupp a
        )e on a.principal = e.supp
        $params_kode_alamat
        $params_signature
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        // die;

        return $this->db->query($query);
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

    public function generate($from_site, $tanggal_pengajuan){

        $bulan_now = date('m',strtotime($tanggal_pengajuan));

        $romawi = $this->getRomawi($bulan_now);

        $tahun_now = date('Y');

        $query = "
            select a.no_relokasi, substr(a.no_relokasi,5,3) as urut, a.from_site, a.to_site, a.nama, a.tanggal_pengajuan, a.created_by, a.created_at, b.username
            from site.t_relokasi_header a left join (
                select a.id, a.username
                from mpm.user a 
            )b on a.created_by = b.id
            where year(a.tanggal_pengajuan) = $tahun_now and month(a.tanggal_pengajuan) = $bulan_now
            ORDER BY a.id desc
            limit 1
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        // die;

        $no_relokasi_current = $this->db->query($query);
        if ($no_relokasi_current->num_rows() > 0) {
            
            $params_urut = $no_relokasi_current->row()->urut + 1;
            // echo $params_urut;

            if (strlen($params_urut) === 1) {
                $generate = "RLK-00$params_urut/MPM/$romawi/$tahun_now";
            }elseif (strlen($params_urut) === 2) {
                $generate = "RLK-0$params_urut/MPM/$romawi/$tahun_now";
            }else{
                $generate = "RLK-$params_urut/MPM/$romawi/$tahun_now";
            }
        }else{
            $generate = "RLK-001/MPM/$romawi/$tahun_now";
        }
        // die;
        return $generate;
    }


    public function history_produk($id_ref){
        // echo "signature : ".$signature;
        // die;
        // $this->db->where('id_ref', $id_ref);
        // return $this->db->get('site.t_relokasi_detail');

        $query = "
            select * 
            from site.t_relokasi_detail a inner join (
                select a.kodeprod, a.namaprod
                from mpm.tabprod a 

            )b on a.kodeprod = b.kodeprod left join (
                select a.id, a.username
                from mpm.user a 
            )c on a.created_by = c.id
            where a.id_ref = $id_ref and a.deleted is null

        ";
        return $this->db->query($query);

    }

    public function get_data_relokasi_header($signature){
        $this->db->where('signature', $signature);
        return $this->db->get('site.t_relokasi_header');
    }

    public function get_email_from_username($username){
        $this->db->where('username', $username);
        return $this->db->get('mpm.user');
    }

    public function email(){
        $this->load->library('email');
        $config['protocol']     = getenv('EMAIL_PROTOCOL');
        $config['smtp_host']    = getenv('EMAIL_HOST');
        $config['smtp_port']    = getenv('EMAIL_PORT');
        $config['smtp_timeout'] = getenv('EMAIL_TIMEOUT');
        $config['smtp_user']    = getenv('EMAIL_USERNAME');
        $config['smtp_pass']    = getenv('EMAIL_PASSWORD');
        $config['charset']      = 'utf-8';
        $config['newline']      = "\r\n";
        $config['mailtype']     ="html";
        $config['use_ci_email'] = TRUE;
        $config['wordwrap']     = TRUE;

        $this->email->initialize($config);
    }

    public function get_trans($no_relokasi){
        $query = "
            select *
            from mpm.trans a 
            where a.no_ajuan_relokasi = '$no_relokasi'
        ";
        return $this->db->query($query);
    }

}