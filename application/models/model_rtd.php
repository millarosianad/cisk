<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Model_rtd extends CI_Model
{
    public function get_data($table, $id = '')
    {
        if ($id == '') {
            $params_id = '';
        } else {
            $params_id = "and a.id = $id";
        }

        if ($table == "site.t_proforma") {
            $sql = "
            select a.id, a.kode, a.supp, b.namasupp, a.kodeprod, c.namaprod, c.kode_deltomed, a.unit, a.ed, a.batch_number, 
            a.signature, a.created_at, a.created_by
            from $table a LEFT JOIN 
            (
                select a.supp, a.namasupp
                from mpm.tabsupp a
            )b on a.supp = b.supp LEFT JOIN 
            (
                select a.kodeprod, a.namaprod, a.kode_deltomed
                from mpm.tabprod a 
            )c on a.kodeprod = c.kodeprod
            where a.deleted is null $params_id
        ";
        } else {
            $sql = "
            select 	a.id, a.kode, a.supp, c.namasupp, b.kodeprod, d.namaprod, d.kodeprod_deltomed, 
                    b.unit, b.ed, b.batch_number, a.nopo, b.nodo, a.company, a.alamat, a.email,
                    a.signature, date(a.created_at) as created_at, a.created_by
            from site.t_surat_jalan a inner join 
            (
                select a.id, a.surat_jalan_id, a.kodeprod, a.nodo, a.ed, a.batch_number, a.unit, a.signature
                from site.t_surat_jalan_detail a
            )b on a.id = b.surat_jalan_id LEFT JOIN 
            (
                select a.supp, a.namasupp
                from mpm.tabsupp a
            )c on a.supp = c.supp LEFT JOIN 
            (
                select a.kodeprod, a.namaprod, a.kode_deltomed, a.kodeprod_deltomed
                from mpm.tabprod a 
            )d on d.kodeprod = b.kodeprod  
            where a.deleted is null $params_id
        ";
        }

        // echo "<pre><br><br><br><br>";
        // print_r($sql);
        // echo "</pre>";

        return $this->db->query($sql);
    }

    public function get_detail_surat_jalan($id)
    {
        $sql = "
        select a.kodeprod, a.ed, a.batch_number, a.unit, b.namaprod, b.kode_prc
        from site.t_surat_jalan_detail a LEFT JOIN
        (
            select a.kodeprod, a.kode_prc, a.namaprod
            from mpm.tabprod a 
        )b on a.kodeprod = b.kodeprod
        where a.id = $id
        ";

        return $this->db->query($sql);

    }

    public function get_data_by_ed()
    {
        $sql = "
            select a.id, a.kode, a.supp, b.namasupp, a.kodeprod, c.namaprod, c.kode_deltomed, sum(a.unit) as total_unit, a.ed, a.batch_number, a.signature, a.created_at, a.created_by
            from site.t_proforma a LEFT JOIN 
            (
                select a.supp, a.namasupp
                from mpm.tabsupp a
            )b on a.supp = b.supp LEFT JOIN 
            (
                select a.kodeprod, a.namaprod, a.kode_deltomed
                from mpm.tabprod a 
            )c on a.kodeprod = c.kodeprod
            where a.deleted is null
            group by a.ed
        ";
        return $this->db->query($sql);
    }

    public function get_data_by_batch_number()
    {
        $sql = "
            select a.id, a.kode, a.supp, b.namasupp, a.kodeprod, c.namaprod, c.kode_deltomed, sum(a.unit) as total_unit, a.ed, a.batch_number, a.signature, a.created_at, a.created_by
            from site.t_proforma a LEFT JOIN 
            (
                select a.supp, a.namasupp
                from mpm.tabsupp a
            )b on a.supp = b.supp LEFT JOIN 
            (
                select a.kodeprod, a.namaprod, a.kode_deltomed
                from mpm.tabprod a 
            )c on a.kodeprod = c.kodeprod
            where a.deleted is null
            group by a.batch_number
        ";
        return $this->db->query($sql);
    }

    public function get_data_mutasi()
    {
        $sql = "
            select  a.id, a.kode as kode, a.proforma_id, a.surat_jalan_id, a.kodeprod, d.namaprod,
                    a.ed, a.batch_number, a.saldo_awal, a.masuk, a.keluar, a.total, a.created_at, a.created_by
            from site.t_proforma_mutasi a LEFT JOIN site.t_proforma b 
            on a.proforma_id = b.id left join site.t_surat_jalan c
            on a.surat_jalan_id = c.id LEFT JOIN 
            (
                select a.kodeprod, a.namaprod
                from mpm.tabprod a 
            )d on a.kodeprod = d.kodeprod
        ";
        // echo "<pre><br><br><br><br>";
        // print_r($sql);
        // echo "</pre>";
        return $this->db->query($sql);
    }

    public function get_data_surat_jalan()
    {
        $sql = "
            select a.id, a.kode as kode, a.proforma_id, a.surat_jalan_id, a.ed, a.batch_number, a.masuk, a.keluar, (a.masuk - a.keluar) as sisa, a.created_at, a.created_by
            from site.t_proforma_mutasi a LEFT JOIN site.t_proforma b 
            on a.proforma_id = b.id left join site.t_surat_jalan c
            on a.surat_jalan_id = c.id
            union all
            select '' as id, '' as kode,  '' as proforma_id, '' as surat_jalan_id, '' as ed, 'TOTAL' as batch_number, sum(a.masuk), sum(a.keluar), (sum(a.masuk) - sum(a.keluar)) as sisa, '' as created_at, '' as created_by
            from site.t_proforma_mutasi a 
        ";
        echo "<pre><br><br><br><br>";
        print_r($sql);
        echo "</pre>";
        return $this->db->query($sql);
    }

    public function insert($table, $data)
    {
        $this->db->insert($table, $data);
        $id_rtd = $this->db->insert_id();
        return $id_rtd;
    }

    public function get_saldo_awal($kodeprod){
        $sql = "
        select *
        from site.t_proforma_mutasi a
        where a.kodeprod = '$kodeprod'
        order by a.created_at desc
        limit 1
        ";

        return $this->db->query($sql);
    }

    public function mutasi($id_rtd, $tipe)
    {
        if ($tipe == 'out') {
            $table = "site.t_surat_jalan_detail";
        } else {
            $table = "site.t_proforma";
        }
        $get_data = $this->get_data($table, $id_rtd)->result();
        echo "<pre>";
        var_dump($get_data);
        echo "</pre>";
        die;
        foreach ($get_data as $a) {
            $proforma_id = $a->id;
            $kode = $a->kode;
            $ed = $a->ed;
            $kodeprod = $a->kodeprod;
            $batch_number = $a->batch_number;
            if ($tipe == 'in') {
                $masuk = $a->unit;
                $keluar = 0;
            } elseif ($tipe == 'out') {
                $keluar = $a->unit;
                $masuk = 0;
            }
            $created_at = $a->created_at;
            $created_by = $a->created_by;
            $signature = $a->signature;
            $sisa = $masuk - $keluar;
        }

        echo "<pre>";
        echo "kode : ".$kode."<br>";
        echo "ed : ".$ed."<br>";
        echo "kodeprod : ".$kodeprod."<br>";
        echo "batch_number : ".$batch_number."<br>";
        echo "tipe : ".$tipe."<br>";
        echo "masuk : ".$masuk."<br>";
        echo "keluar : ".$keluar."<br>";
        echo "</pre>";

        $data = [
            'surat_jalan_id'   => $proforma_id,
            'kode'              => $kode,
            'ed'                => $ed,
            'kodeprod'          => $kodeprod,
            'batch_number'      => $batch_number,
            'masuk'             => $masuk,
            'keluar'            => $keluar,
            'sisa'            => $sisa,
            'created_at'        => $created_at,
            'created_by'        => $created_by,
            'signature'         => $signature,
        ];
        $id_mutasi = $this->insert('site.t_proforma_mutasi', $data);
        // die;
        return $id_mutasi;
    }

    public function get_total($saldo_awal, $masuk, $keluar){
        return $saldo_awal + $masuk - $keluar;
    }

    public function get_aktivitas($id_rpd)
    {
        return $this->db->get_where('site.t_rpd_aktivitas', array('id_rpd' => $id_rpd));
    }

    public function get_id_rpd($signature)
    {
        $get_id_rpd = $this->db->get_where('site.t_rpd', array('signature' => $signature))->row();
        return $get_id_rpd->id;
    }

    public function get_supp($id_po)
    {
        $get_supp = $this->db->get_where('mpm.po', array('nopo' => $id_po))->row();
        return $get_supp->supp;
    }

    public function get_company($id_po)
    {
        $get_supp = $this->db->get_where('mpm.po', array('nopo' => $id_po))->row();
        return $get_supp->company;
    }

    public function get_email($id_po)
    {
        $get_supp = $this->db->get_where('mpm.po', array('nopo' => $id_po))->row();
        return $get_supp->email;
    }

    public function get_alamat($id_po)
    {
        $get_supp = $this->db->get_where('mpm.po', array('nopo' => $id_po))->row();
        return $get_supp->alamat;
    }

    public function generate($userid, $ed)
    {

        // echo date('Y-m',strtotime($tanggal_transaksi));
        // die;

        $get_by_user = $this->db->get("site.t_proforma")->num_rows();
        if ($get_by_user) { //jika sudah ada sebelumnya

            $sql = "
                select  a.kode, right(a.kode,1) as urut
                from    site.t_proforma a
                where   year(a.ed)= year('$ed')
                ORDER BY a.id desc
                limit 1
            ";

            // print_r($sql);
            $query = $this->db->query($sql);

            if ($query->num_rows() > 0) {
                // echo "a";
                $row = $query->row();
                $urut = $row->urut + 1;
                $kode = "MPM/PROFORMA/" . date('Y', strtotime($ed)) . "/No." . $urut;
            } else {
                // echo "b";
                $row = $query->row();
                $urut = $row->urut + 1;
                $kode = "MPM/PROFORMA/" . date('Y', strtotime($ed)) . "/No." . $urut;
            }
        } else { // jika baru pertama kali
            $kode = "MPM/PROFORMA/" . date('Y', strtotime($ed)) . "/No.1";
        }

        // echo "kode : ".$kode;
        // die;
        return $kode;
    }

    public function generate_surat_jalan($userid, $ed)
    {

        // echo date('Y-m',strtotime($tanggal_transaksi));
        // die;

        $get_by_user = $this->db->get("site.t_surat_jalan_detail")->num_rows();
        if ($get_by_user) { //jika sudah ada sebelumnya

            $sql = "
                select  a.kode, right(a.kode,1) as urut
                from    site.t_surat_jalan_detail a
                where   year(a.ed)= year('$ed')
                ORDER BY a.id desc
                limit 1
            ";

            // print_r($sql);
            $query = $this->db->query($sql);

            if ($query->num_rows() > 0) {
                // echo "a";
                $row = $query->row();
                $urut = $row->urut + 1;
                $kode = "MPM/SJ/" . date('Y', strtotime($ed)) . "/No." . $urut;
            } else {
                // echo "b";
                $row = $query->row();
                $urut = $row->urut + 1;
                $kode = "MPM/SJ/" . date('Y', strtotime($ed)) . "/No." . $urut;
            }
        } else { // jika baru pertama kali
            $kode = "MPM/SJ/" . date('Y', strtotime($ed)) . "/No.1";
        }

        // echo "kode : ".$kode;
        // die;
        return $kode;
    }
}
