<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_dc extends CI_Model
{
    public function get_po()
    {

        $sql = "
        select 	a.supp,c.namasupp,a.company,a.nopo, date(a.tglpo) as tglpo, a.company, a.alamat, a.kode_alamat, 
                if(b.nodo is null, 'belum tersedia', b.nodo) as nodo, if(b.tgldo is null, 'belum tersedia', date(b.tgldo)) as tgldo, md5(a.id) as id, b.id as id_do, c.kode_kartu_masuk, c.kode_kartu_keluar
        from	mpm.po a LEFT JOIN
        (
            select a.id, a.nodo, a.tgldo, a.nopo
            from db_po.t_do_deltomed a
            GROUP BY a.nodo
            union all
            select a.id, a.nodo, a.tgldo, a.nopo
            from db_po.t_do_us a
            GROUP BY a.nodo
        )b on a.nopo = b.nopo LEFT JOIN mpm.tabsupp c 
        on a.supp = c.supp LEFT JOIN
        (
            select  a.id_do, a.kode_kartu_masuk, a.kode_kartu_keluar
            from site.t_kartu_stock_log a
        )c on b.id = c.id_do
        where 	a.status_override = 1 and a.nopo not like '/mpm%' and a.nopo not like '%batal%' and
        ((
            year(a.tglpo) in (year(date(now()))) and month(a.tglpo) in (month(date(now())))
        ) or
        (
            year(a.tglpo) in (date_format(date(now()) - INTERVAL '1' MONTH,'%Y')) and 
            month(a.tglpo) in (date_format(date(now()) - INTERVAL '1' MONTH,'%m'))
        )) and a.status_override = 1
        order by a.id desc
        ";

        echo "<pre>";
        echo "<br><br><br><br><br>";
        print_r($sql);
        echo "</pre>";

        $proses = $this->db->query($sql)->result();
        return $proses;
    }

    public function get_list_po()
    {
        $sql = "
        select a.id, a.nopo, date(a.tglpo) as tglpo, a.company, a.kode_alamat, a.status_override
        from mpm.po a 
        where a.status_override = 1 and a.deleted = 0
        ORDER BY a.id desc
        ";

        return $this->db->query($sql);
    }

    public function get_id_po($signature)
    {
        return $this->db->get_where('mpm.po', array('md5(id)' => $signature))->result();
    }

    public function get_do($signature, $supp, $nodo = "")
    {
        if ($supp == '005') {
            // $this->db->join('db_po.t_kartu_stock','db_po.t_do_us.id = db_po.t_kartu_stock.id_do','left');
            // return $this->db->get_where('db_po.t_do_us',array('nopo' => $nopo))->result();
            $sql = "
            select 	a.id as id_do, a.nopo, c.tglpo, a.kodeprod, a.banyak, a.namaprod, a.nodo, date(a.tgldo) as tgldo,
                    b.id as id_kartu_stock_log, b.batch_number, b.ed, b.kode_kartu_masuk, b.masuk, c.company, c.alamat, c.alamat_kirim, c.email, c.kode_alamat, d.namasupp
            from 	db_po.t_do_us a LEFT JOIN
            (
                select a.id, a.id_do, a.nodo, a.kodeprod, a.batch_number, a.ed, a.sisa, a.kode_kartu_masuk, a.masuk
                from site.t_kartu_stock_log a
            )b on a.id = b.id_do left join (
                select a.nopo, a.tglpo, a.company, a.kode_alamat, a.alamat, a.alamat_kirim, a.email, a.supp
                from mpm.po a 
                where a.deleted = 0 and md5(a.id) = '$signature'
            )c on a.nopo = c.nopo LEFT JOIN 
            (
                select a.supp, a.namasupp
                from mpm.tabsupp a 
            )d on c.supp = d.supp
            where a.nodo = '$nodo'
            ";
        }
        // echo "<pre><br><br><br><br>";
        // printf($sql);
        // echo "</pre>";

        // die;

        return $this->db->query($sql);
    }

    public function get_do_single($id_do, $table)
    {
        return $this->db->get_where($table, array('id' => $id_do));
    }

    public function get_kartu_masuk($nodo)
    {
        $sql = "
            select 	a.id, a.id_do, a.supp, a.kode_kartu_masuk, a.kode_kartu_keluar, 
                    a.nodo, a.batch_number, a.ed, a.kodeprod, a.qty_do, a.masuk, a.keluar, a.sisa,
                    a.signature, a.action, a.tgldo, a.nopo, a.tglpo, b.namaprod, c.namasupp, a.ekspedisi, a.tanggal_keluar, a.eta,
                    a.company, a.alamat, a.email, a.kode_alamat, d.branch_name, d.nama_comp
            from    site.t_kartu_stock_log a LEFT JOIN
            (
                select a.kodeprod, a.namaprod
                from mpm.tabprod a
            )b on a.kodeprod = b.kodeprod left join mpm.tabsupp c on a.supp = c.supp LEFT JOIN(
                select concat(a.kode_comp, a.nocab) as site_code, a.branch_name, a.nama_comp
                from mpm.tbl_tabcomp a 
                where a.`status` = 1
                GROUP BY concat(a.kode_comp, a.nocab)
            )d on a.kode_alamat = d.site_code
            where a.nodo = '$nodo'
        ";

        echo "<pre><br><br><br>";
        print_r($sql);
        echo "</pre>";

        $proses = $this->db->query($sql);
        if ($proses->row()) {
            return $proses;
            // echo "ada";
        } else {
            echo "tidak ada";
            $sql = "
            select 	'' as kode_kartu_masuk, '' as namasupp, '' as nodo, '' as tgldo, '' as nopo, '' as tglpo,
                    '' as company, '' as alamat, '' as kode_alamat
            ";

            return $this->db->query($sql);
        }
    }

    public function get_kodeprod($id_do, $supp)
    {

        if ($supp == '005') {
            $table = 'db_po.t_do_us';
        } elseif ($supp == '001') {
            $table = 'db_po.t_do_deltomed';
        }

        $sql = $this->db->get_where($table, array('id' => $id_do))->result();
        foreach ($sql as $a) {
            $kodeprod = $a->kodeprod;
        }

        return $kodeprod;
    }

    public function get_kodeprod_dc($id_dc)
    {

        $sql = $this->db->get_where('db_po.t_kartu_stock', array('id_do' => $id_dc))->result();
        foreach ($sql as $a) {
            $kodeprod = $a->kodeprod;
        }

        return $kodeprod;
    }

    public function get_nodo($id_do, $supp)
    {

        if ($supp == '005') {
            $table = 'db_po.t_do_us';
        } elseif ($supp == '001') {
            $table = 'db_po.t_do_deltomed';
        }

        $sql = $this->db->get_where($table, array('id' => $id_do))->result();
        foreach ($sql as $a) {
            $nodo = $a->nodo;
        }

        return $nodo;
    }

    public function input_kartu_stock($data, $table)
    {
        $insert = $this->db->insert($table, $data);
        if ($insert) {

            $id_do = $this->db->get_where($table, array('id' => $this->db->insert_id()))->row()->id_do;
            // return $this->db->insert_id();
            return $id_do;
            // echo "id_do : ".$id_do;
            // echo "insert : ".$insert;
            die;
        } else {
            return array();
        }
    }

    public function get_banyak($id_do)
    {
        $sql = $this->db->get_where('db_po.t_do_us', array('id' => $id_do))->result();
        foreach ($sql as $a) {
            $banyak = $a->banyak;
        }

        // echo "id_do : ".$id_do;

        return $banyak;
    }

    public function get_banyak_dc($id_dc)
    {
        $sql = $this->db->get_where('db_po.t_kartu_stock', array('id_do' => $id_dc))->result();
        foreach ($sql as $a) {
            $banyak = $a->sisa;
        }

        // echo "id_do : ".$id_do;

        return $banyak;
    }

    public function get_kartu_stock()
    {

        $sql = "
        select a.id_do, a.kode_kartu_masuk, a.kode_kartu_keluar, a.supp, b.namasupp, a.nodo, a.batch_number, a.ed, a.masuk, a.keluar, sum(a.sisa) as total_unit, a.created_at, a.signature
        from site.t_kartu_stock_log a LEFT JOIN mpm.tabsupp b
            on a.supp = b.supp
        GROUP BY a.kode_kartu_masuk
        ";

        // echo "<pre>";
        // echo "<br><br><br><br><br>";
        // print_r($sql);
        // echo "</pre>";

        $proses = $this->db->query($sql);
        return $proses;
    }


    public function get_kartu_stock_detail($signature)
    {

        $sql = "
        select a.id, a.id_do, a.kode_kartu_masuk, a.kode_kartu_keluar, a.supp, a.nodo, a.batch_number, a.ed, a.kodeprod, a.masuk, c.keluar, c.sisa, a.created_date, a.signature, b.namaprod
        from db_po.t_kartu_stock a LEFT JOIN 
        (
            select a.kodeprod, a.namaprod
            from mpm.tabprod a
        )b on a.kodeprod = b.kodeprod LEFT JOIN
        (
            select a.id, a.kodeprod, a.signature, a.kode_kartu_masuk, a.kode_kartu_keluar, a.keluar, a.sisa
            from db_po.t_kartu_stock a
        )c on a.id = c.id
        where a.signature = '$signature'
        ";

        // echo "<pre>";
        // echo "<br><br><br><br><br>";
        // print_r($sql);
        // echo "</pre>";

        $proses = $this->db->query($sql)->result();
        return $proses;
    }

    public function generate_kode_kartu($supp, $nodo)
    {

        $get_by_supp = $this->db->get_where("site.t_kartu_stock_log", array("supp" => $supp))->num_rows();
        if ($get_by_supp) { //jika sudah ada sebelumnya

            $sql = "
                select  a.kode_kartu_masuk, right(a.kode_kartu_masuk,1) as urut
                from    site.t_kartu_stock_log a
                where   a.supp = '$supp' and a.nodo = '$nodo'
                ORDER BY a.id desc
                limit 1
            ";
            $query = $this->db->query($sql);

            if ($query->num_rows() > 0) {
                $row = $query->row();
                $urut = $row->urut;
                $kode_kartu = "LBM-MPM/" . $supp . "/" . $nodo . "/" . $urut;
            } else {
                $row = $query->row();
                $urut = $row->urut + 1;
                $kode_kartu = "LBM-MPM/" . $supp . "/" . $nodo . "/" . $urut;
            }
        } else { // jika baru pertama kali
            $kode_kartu = "LBM-MPM/" . $supp . "/" . $nodo . "/1";
        }
        return $kode_kartu;
    }

    public function generate_kode_kartu_keluar($supp, $nodo)
    {

        $sql = "select a.kode_kartu_keluar, right(a.kode_kartu_keluar,1) as urut
                from db_po.t_kartu_stock a
                where a.supp = '$supp' and a.kode_kartu_keluar is not null
                ORDER BY a.id desc
                limit 1
        ";
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            $row = $query->row();
            $urut = $row->urut + 1;
            $kode_kartu = "BBK-MPM/" . $supp . "/" . $nodo . "/" . "/" . $urut;
        } else {
            $kode_kartu = "BBK-MPM/" . $supp . "/" . $nodo . "/" . "1";
        }

        // echo "kode_barang_masuk : ".$kode_barang_masuk;
        return $kode_kartu;
    }

    public function get_data_by_nodo($created_at, $userid)
    {

        $sql = "
        select 	a.id, a.id_do, a.kode, a.nodo, a.tgldo, a.nopo, a.tglpo, a.tipe, a.company, a.kodeprod, a.namaprod, a.banyak, a.company,
                a.kode_alamat, a.alamat, a.alamat_kirim, b.branch_name, b.nama_comp, c.namaprod, a.signature
        from site.temp_dc_do a LEFT JOIN
        (
            select concat(a.kode_comp, a.nocab) as site_code, a.branch_name, a.nama_comp
            from mpm.tbl_tabcomp a 
            where a.status = 1
            GROUP BY concat(a.kode_comp,a.nocab)
        )b on a.kode_alamat = b.site_code LEFT JOIN
        (
            select a.kodeprod, a.namaprod
            from mpm.tabprod a 
        )c on a.kodeprod = c.kodeprod
        where a.created_at = '$created_at' and a.created_by = $userid
        ";

        // echo "<pre>";
        // print_r($sql);
        // echo "</pre>";
        $proses = $this->db->query($sql);
        return $proses;
    }

    public function get_data_detail_by_nodo_keluar($created_at, $userid)
    {

        $sql = "
        select 	a.id, a.id_do, a.nodo, a.tgldo, a.nopo, a.kodeprod, a.namaprod, a.banyak, a.company,
                a.kode_alamat, a.alamat, a.alamat_kirim, b.branch_name, b.nama_comp, c.namaprod
        from site.temp_dc_do_keluar a LEFT JOIN
        (
            select concat(a.kode_comp, a.nocab) as site_code, a.branch_name, a.nama_comp
            from mpm.tbl_tabcomp a 
            where a.status = 1
            GROUP BY concat(a.kode_comp,a.nocab)
        )b on a.kode_alamat = b.site_code LEFT JOIN
        (
            select a.kodeprod, a.namaprod
            from mpm.tabprod a 
        )c on a.kodeprod = c.kodeprod
        where a.created_at = '$created_at' and a.created_by = $userid
        ";

        // echo "<pre>";
        // print_r($sql);
        // echo "</pre>";
        $proses = $this->db->query($sql);
        return $proses;
    }

    public function get_data_header_by_nodo_keluar($created_at, $userid)
    {

        $sql = "
        select 	a.id, a.id_do, a.nodo, a.tgldo, a.nopo, a.kodeprod, a.namaprod, a.banyak, a.company,
                a.kode_alamat, a.alamat, a.alamat_kirim, b.branch_name, b.nama_comp, c.namaprod, a.kode, a.tglpo, a.tipe, a.signature
        from site.temp_dc_do_keluar a LEFT JOIN
        (
            select concat(a.kode_comp, a.nocab) as site_code, a.branch_name, a.nama_comp
            from mpm.tbl_tabcomp a 
            where a.status = 1
            GROUP BY concat(a.kode_comp,a.nocab)
        )b on a.kode_alamat = b.site_code LEFT JOIN
        (
            select a.kodeprod, a.namaprod
            from mpm.tabprod a 
        )c on a.kodeprod = c.kodeprod
        where a.created_at = '$created_at' and a.created_by = $userid
        ";

        // echo "<pre>";
        // print_r($sql);
        // echo "</pre>";
        $proses = $this->db->query($sql);
        return $proses;
    }

    public function get_data_summary_by_nodo()
    {

        $sql = "
            select *
            from site.temp_dc_do a 
            GROUP BY a.nodo
        ";

        // echo "<pre>";
        // print_r($sql);
        // echo "</pre>";
        $proses = $this->db->query($sql);
        return $proses;
    }

    public function get_saldo_awal($kodeprod)
    {
        $sql = "
        select *
        from site.t_dc_mutasi a
        where a.kodeprod = '$kodeprod'
        order by a.id desc, a.created_at desc
        limit 1
        ";

        // echo "<pre>";
        // print_r($sql);
        // echo "</pre>";

        return $this->db->query($sql);
    }

    public function insert_temp_dc_do($nodo, $created_at, $userid, $signature, $kode)
    {

        $sql = "
            insert into site.temp_dc_do
            select '', a.id as id_do, '$kode', a.nodo, date(a.tgldo) as tgldo, a.nopo, b.tglpo, b.tipe, a.kodeprod, a.namaprod, a.banyak, b.company, b.kode_alamat, b.alamat, b.alamat_kirim, '$created_at', $userid, '$signature',0
            from db_po.t_do_us a INNER JOIN
            (
                select a.nopo, a.tglpo, a.tipe, a.company, a.kode_alamat, a.alamat, a.alamat_kirim
                from mpm.po a 
                where a.deleted = 0
            )b on a.nopo = b.nopo
            where a.nodo = '$nodo'
        ";

        // echo "<pre>";
        // print_r($sql);
        // echo "</pre>";
        $proses = $this->db->query($sql);
        return $proses;
    }

    public function insert_temp_dc_do_keluar($kode_masuk, $created_at, $userid, $signature, $kode)
    {

        $sql = "
            insert into site.temp_dc_do_keluar
            select 	'', a.id_do, '$kode_masuk', '$kode', a.nodo, a.tgldo, a.nopo, a.tglpo, a.tipe, a.kodeprod, a.namaprod, 
                    a.banyak, a.company, a.kode_alamat, a.alamat, a.alamat_kirim, '$created_at', $userid, '$signature', 0
            from site.t_dc_do a
            where a.kode = '$kode_masuk'
        ";

        // echo "<pre><br><br><br><br><br>";
        // print_r($sql);
        // echo "</pre>";
        $proses = $this->db->query($sql);
        return $proses;
    }

    public function get_data_row_mutasi()
    {
        $sql = "
            select *
            from site.t_dc_mutasi a
            order by a.kodeprod
        ";
        return $this->db->query($sql);
    }

    public function get_data_row_mutasi_by_produk()
    {
        $sql = "
            select a.id, a.kodeprod, a.namaprod, total, a.created_at
            from site.t_dc_mutasi a
            where a.id = (
                select max(b.id)
                from site.t_dc_mutasi b 
                where b.kodeprod = a.kodeprod
            )
        ";

        // echo "<pre><br><br><br><br>";
        // print_r($sql);
        // echo "</pre>";

        return $this->db->query($sql);
    }

    public function get_data_row_mutasi_by_produk_total()
    {
        $sql = "
            select a.id, a.kodeprod, a.namaprod, sum(total) as total, a.created_at
            from site.t_dc_mutasi a
            where a.id = (
                select max(b.id)
                from site.t_dc_mutasi b 
                where b.kodeprod = a.kodeprod
            )
        ";

        // echo "<pre><br><br><br><br>";
        // print_r($sql);
        // echo "</pre>";

        return $this->db->query($sql);
    }

    public function get_data_masuk()
    {
        $sql = "
            select sum(a.banyak) as total_masuk
            from site.t_dc_do a
        ";

        // echo "<pre><br><br><br><br>";
        // print_r($sql);
        // echo "</pre>";

        return $this->db->query($sql);
    }

    public function get_data_row_masuk()
    {
        $sql = "
            select a.id, a.id_do, a.kode, a.nodo, a.tgldo, a.nopo, a.tglpo, a.company, sum(a.banyak) as total
            from site.t_dc_do a 
            GROUP BY a.kode
        ";
        return $this->db->query($sql);
    }

    public function get_data_row_keluar($signature = '')
    {
        if ($signature == '') {
            $signature_params = '';
        } else {
            $signature_params = "where a.signature = '$signature'";
        }

        $sql = "
            select a.id, a.id_do, a.kode, concat('http://site.muliaputramandiri.com/cisk/assets/file/dc/', REPLACE(a.kode,'/','_'),'.pdf') as link, a.nodo, a.tgldo, a.nopo, date(a.tglpo) as tglpo, a.company, sum(a.banyak) as total, a.signature, date(a.created_at) as created_at, a.alamat, a.alamat_kirim, round(sum((a.banyak / b.qty1) * b.berat),2) as total_berat, round(sum((a.banyak / b.qty1) * b.volume),3) as total_volume, a.tipe, a.kode_alamat, sum(a.banyak / b.qty1) as total_karton,
			c.status_kirim
            from site.t_dc_do_keluar a left join
            (
                select a.kodeprod, a.kode_prc, a.berat, a.berat_gr, a.volume, a.isisatuan, a.qty1, a.qty2, a.qty3
                from mpm.tabprod a
            )b on a.kodeprod = b.kodeprod LEFT JOIN 
            (
                select a.signature_dc, a.tanggal_kirim, a.tanggal_tiba, 
                        if(a.tanggal_tiba is null, if(a.tanggal_kirim is null, '-', 'sedang dikirim'),'sudah diterima') as status_kirim
                from site.t_konfirmasi_pengiriman_dc a
            )c on a.signature = c.signature_dc
            $signature_params
            GROUP BY a.kode
        ";

        // echo "<pre>";
        // print_r($sql);
        // echo "</pre>";
        // die;

        return $this->db->query($sql);
    }

    public function get_data_row_keluar_by_kodeprod($signature = '')
    {
        if ($signature == '') {
            $signature_params = '';
        } else {
            $signature_params = "where a.signature = '$signature'";
        }

        $sql = "
            select  a.nopo, a.company, a.kodeprod, a.namaprod, a.banyak, b.kode_prc, b.berat, b.volume, b.isisatuan, round(a.banyak / b.qty1, 3) as banyak_karton, round((a.banyak / b.qty1) * b.berat,3) as total_berat, round(((a.banyak / b.qty1) * b.volume),3) as total_volume
            from    site.temp_dc_do_keluar a left join
            (
                select a.kodeprod, a.kode_prc, a.berat, a.berat_gr, a.volume, a.isisatuan, a.qty1, a.qty2, a.qty3
                from mpm.tabprod a
            )b on a.kodeprod = b.kodeprod
            $signature_params
            ORDER BY a.kodeprod
        ";

        // echo "<pre>";
        // print_r($sql);
        // echo "</pre>";

        // die;

        return $this->db->query($sql);
    }

    public function get_konfirmasi()
    {
        $query = "            
            select a.id, a.kode, a.link, a.signature, b.signature_dc, b.created_at, b.tanggal_kirim, b.file_1, b.file_2, b.file_3, b.file_4, b.tanggal_tiba
            from 
            (
                select 	a.id, a.id_do, a.kode, 
                        concat('http://site.muliaputramandiri.com/cisk/assets/file/dc/', REPLACE(a.kode,'/','_'),'.pdf') as link, 
                        a.signature
                from site.t_dc_do_keluar a
                GROUP BY a.kode
            )a LEFT JOIN 
            (
                select *
                from site.t_konfirmasi_pengiriman_dc a 
            )b on a.signature = b.signature_dc
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        // die;

        return $this->db->query($query);
    }

    public function konfirmasi_pengiriman($signature){
        $query = "
            select *
            from site.t_konfirmasi_pengiriman_dc a
            where a.signature_dc = '$signature'
        ";

        return $this->db->query($query);
    }

    public function generate()
    {
        $cek_jumlah = $this->db->get("site.t_dc_do")->num_rows();
        if (!$cek_jumlah) {
            $reserve_nomor_params = "MPM_LBM/" . date('Y') . date('m') . "-001";
        } else {
            // echo "ada";
            $sql = "
                select right(a.kode,3) as urut
                from site.t_dc_do a
                GROUP BY a.kode
                ORDER BY right(a.kode,3) desc
                limit 1
            ";

            // MPM_LBM/202209-099

            // URUT = 099
            // URUT = 99
            // RESERVE_NOMOR = 99 + 1 = 100
            // RESERVE_NOMOR_PARAMS = MPM_LBM/202209-002
            // RESERVE_NOMOR_PARAMS = MPM_LBM/202209-100


            $proses = $this->db->query($sql)->row();
            $reserve_nomor = $proses->urut + 1;
            if (strlen($reserve_nomor) === 1) {
                $reserve_nomor_params = "MPM_LBM/" . date('Y') . date('m') . "-00" . $reserve_nomor;
            } else if (strlen($reserve_nomor) === 2) {
                $reserve_nomor_params = "MPM_LBM/" . date('Y') . date('m') . "-0" . $reserve_nomor;
            } else {
                $reserve_nomor_params = "MPM_LBM/" . date('Y') . date('m') . "-" . $reserve_nomor;
            }
            // echo "reserve_nomor_params : ".$reserve_nomor_params;
        }

        // echo "reserve_nomor_params : ".$reserve_nomor_params;
        // die;

        return $reserve_nomor_params;
    }

    public function generate_keluar()
    {
        $cek_jumlah = $this->db->get("site.t_dc_do_keluar")->num_rows();
        if (!$cek_jumlah) {
            $reserve_nomor_params = "MPM_DO/" . date('Y') . date('m') . "-001";
        } else {
            // echo "ada";
            $sql = "
                select right(a.kode,3) as urut
                from site.t_dc_do_keluar a
                GROUP BY a.kode
                ORDER BY right(a.kode,3) desc
                limit 1
            ";

            $proses = $this->db->query($sql)->row();
            $reserve_nomor = $proses->urut + 1;
            if (strlen($reserve_nomor) === 1) {
                $reserve_nomor_params = "MPM_DO/" . date('Y') . date('m') . "-00" . $reserve_nomor;
            } else if (strlen($reserve_nomor) === 2) {
                $reserve_nomor_params = "MPM_DO/" . date('Y') . date('m') . "-0" . $reserve_nomor;
            } else {
                $reserve_nomor_params = "MPM_DO/" . date('Y') . date('m') . "-" . $reserve_nomor;
            }
            // echo "reserve_nomor_params : ".$reserve_nomor_params;
        }

        return $reserve_nomor_params;
    }
}
