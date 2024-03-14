<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_inventory extends CI_Model 
{
    public function get_monitoring_stock_deltomed_max_updated(){
        $id = $this->session->userdata('id');
        $sql = $this->db->query("select max(a.created_date) as tanggal_max, userid, username 
        from db_inventory.t_monstock_deltomed_temp a LEFT JOIN mpm.`user` b on a.userid = b.id")->result();

        if ($sql) {
            return $sql;
        }else{
            return array();
        }
    }

    public function get_monitoring_stock_deltomed_last(){
        $id = $this->session->userdata('id');

        $sql = $this->db->query("select max(a.created_date) as tanggal_max from db_inventory.t_monstock_deltomed_temp a")->result();
        foreach ($sql as $key) {
            $tanggal_max = $key->tanggal_max;
        }

        $sql = "
            select *
            from db_inventory.t_monstock_deltomed_temp a
            where a.created_date = '$tanggal_max'
            order by nama_comp, kodeprod
        ";
        $proses = $this->db->query($sql)->result();
        if ($proses) {
            return $proses;
        }else{
            return array();
        }
    }

    public function get_monitoring_stock_deltomed()
    {
        $id = $this->session->userdata('id');
        date_default_timezone_set('Asia/Jakarta'); 
        $created = date('Y-m-d H:i:s');
        $tahun_sekarang = date('Y');
        $bulan_sekarang = date('m');
        $tanggal_sekarang = date('d');

        echo "<pre>";
        echo "id : ".$id."<br>";
        echo "created : ".$created."<br>";
        echo "tahun_sekarang : ".$tahun_sekarang."<br>";
        echo "bulan_sekarang : ".$bulan_sekarang."<br>";
        echo "tanggal_sekarang : ".$tanggal_sekarang."<br>";
        echo "</pre>";

        $this->db->query("delete from db_inventory.t_monstock_avg_temp where userid = $id");
        $sql = "
        insert into db_inventory.t_monstock_avg_temp
        select kode, right(kode,2) as nocab, a.KODEPROD, sum(tot1) as omzet, sum(banyak) as omzet_unit, sum(tot1/6) as avg, sum(banyak/6) as avg_unit,$id
        FROM
        (
            select concat(a.kode_comp, a.nocab) as kode, tot1, banyak, kodeprod
            from data$tahun_sekarang.fi a
            where bulan in (1,2,3,4,5,6) and (kodeprod like '60%' or kodeprod like '01%' or kodeprod like '50%' or kodeprod like '70%' or kodeprod like '110%') and kodeprod not in (select kodeprod from mpm.tabprod_unilever)
            union all
            select concat(a.kode_comp, a.nocab) as kode, tot1, banyak, kodeprod
            from data$tahun_sekarang.ri a
            where bulan in (1,2,3,4,5,6) and (kodeprod like '60%' or kodeprod like '01%' or kodeprod like '50%' or kodeprod like '70%' or kodeprod like '110%') and kodeprod not in (select kodeprod from mpm.tabprod_unilever)
        )a INNER JOIN (
            select a.kodeprod, a.namaprod
            from mpm.tabprod a
            where a.supp = '001'
        )b on a.kodeprod = b.kodeprod
        GROUP BY kode, kodeprod
        ";
        $proses = $this->db->query($sql);

        echo "<pre>";
        print_r($sql);
        echo "</pre>";

        $this->db->query("delete from db_inventory.t_monstock_stock_akhir_temp where userid = $id");
        $sql = "
            insert into db_inventory.t_monstock_stock_akhir_temp
            select c.kode, a.nocab, a.kodeprod, stock, stokValue, $id
            FROM
            (
                select a.nocab, a.kodeprod, a.stock, b.h_dp, (a.stock * b.h_dp) as stokValue
                FROM
                (
                    select  nocab,kodeprod,namaprod,substr(bulan,3) as bulan,
                            sum( if(kode_gdg='PST',
                            ((Saldoawal+masuk_pbk+masuk_supp+retur_sal+bPinjam+kvretur+kr_gudang+retur_depo)-
                            (sales+pinjam+minta_depo+kvbeli+kr_min)-(rusak+sisih)),(Saldoawal+masuk_pbk+retur_sal+kvretur+bPinjam+tukar_msk+msk_gddepo) -
                            (sales+Pinjam+kvbeli+retur_depo+tukar_klr+klr_gddepo))) as stock
                    from    data$tahun_sekarang.st 
                    where substr(bulan,3) = $bulan_sekarang
                    group by nocab, kodeprod,bulan
                    order by kodeprod
                )a LEFT JOIN
                (
                    select a.kodeprod, a.h_dp
                    from mpm.prod_detail a
                    where a.tgl = (
                        select max(b.tgl)
                        from mpm.prod_detail b
                        where a.kodeprod = b.kodeprod
                    )
                )b on a.kodeprod = b.kodeprod
            )a INNER JOIN
            (
                select a.kodeprod
                from mpm.tabprod a
                where a.SUPP ='001'
            )b on a.kodeprod = b.kodeprod
            LEFT JOIN
            (
                select a.kode, nocab, a.branch_name, a.nama_comp
                FROM
                (
                    select concat(a.kode_comp, a.nocab) as kode, a.branch_name, a.nama_comp, a.nocab
                    from mpm.tbl_tabcomp a
                    where a.`status` = 1 and (a.stock <> 2 or a.stock is null)
                    GROUP BY concat(a.kode_comp, a.nocab)
                )a INNER JOIN
                (
                    select concat(a.kode_comp, a.nocab) as kode
                    from data$tahun_sekarang.fi a
                    where a.bulan = $bulan_sekarang
                    GROUP BY concat(a.kode_comp, a.nocab)
                )b on a.kode = b.kode
                ORDER BY nocab
            )c on a.nocab = c.nocab
            ORDER BY nocab, kodeprod
        ";
        $proses = $this->db->query($sql);
        echo "<pre>";
        print_r($sql);
        echo "</pre>";



        $this->db->query("delete from db_inventory.t_monstock_po_temp where id = $id");
        $sql = "
        insert into db_inventory.t_monstock_po_temp
        select 	a.nopo, a.company,a.tipe, date(a.tglpo), a.alamat, f.kode, c.supp, b.kodeprod, b.banyak, 
                c.namaprod, c.grup, d.nama_group, a.userid, $id
        from    mpm.po a INNER JOIN mpm.po_detail b 
                    on a.id = b.id_ref LEFT JOIN mpm.tabprod c
                    on b.kodeprod = c.kodeprod	LEFT JOIN mpm.tbl_group d
                    on c.grup = d.kode_group LEFT JOIN mpm.`user` e
        on a.userid = e.id LEFT JOIN
        (
            select a.kode,a.kode_comp
            FROM
            (
                select a.kode, kode_comp, b.kode as kodex
                FROM
                (
                    select concat(kode_comp,a.nocab) as kode, a.kode_comp
                    from mpm.tbl_tabcomp a
                    where a.`status` = 1
                    GROUP BY kode
                )a LEFT JOIN (
                    select concat(a.kode_comp, a.nocab) as kode
                    from data$tahun_sekarang.fi a
                    where a.bulan = $bulan_sekarang
                    GROUP BY kode
                )b on a.kode = b.kode
                where b.kode is not null
            )a
        )f on e.username = f.kode_comp
        where a.supp ='001' and `open` = 1  and a.deleted = 0 and b.deleted = 0 and year(tglpo) = $tahun_sekarang 
        and month(tglpo) = $bulan_sekarang and nopo not like '/mpm%' and a.nopo not like '%batal%'
        ORDER BY a.id desc
        ";

        $proses = $this->db->query($sql);
        echo "<pre>";
        print_r($sql);
        echo "</pre>";

        $this->db->query("delete from db_inventory.t_monstock_do_deltomed_temp where id = $id");
        $sql = "
        insert into db_inventory.t_monstock_do_deltomed_temp
        select a.nopo, a.kodeprod, a.kodeprod_delto, a.total_qty, $id
        FROM
        (
            select sum(a.qty) total_qty, a.nopo, b.kodeprod, kodeprod_delto, str_to_date(a.tgldo,'%d/%m/%Y') as tgldo
            from db_po.t_do_deltomed a LEFT JOIN db_produk.tbl_produk b
                on a.kodeprod_delto = b.dl_kodeprod
            where month(str_to_date(a.tgldo,'%d/%m/%Y')) <= $bulan_sekarang
            GROUP BY a.nopo, a.kodeprod_delto
        )a
        ";

        $proses = $this->db->query($sql);
        echo "<pre>";
        print_r($sql);
        echo "</pre>";

        $this->db->query("delete from db_inventory.t_monstock_po_blank_temp where id = $id");
        $sql = "
        insert into db_inventory.t_monstock_po_blank_temp
        select  a.kode, a.kodeprod, sum(a.banyak) as unit, $id
        from    db_inventory.t_monstock_po_temp a LEFT JOIN (
                select a.nopo, a.kodeprod 
                from db_inventory.t_monstock_do_deltomed_temp a
                where a.id = $id
        )b on a.nopo = b.nopo and a.kodeprod = b.kodeprod
        where b.nopo is null
        GROUP BY a.kode, a.kodeprod
        ";

        $proses = $this->db->query($sql);
        echo "<pre>";
        print_r($sql);
        echo "</pre>";

        $this->db->query("delete from db_inventory.t_monstock_sales_berjalan_temp where userid = $id");
        $sql = "
        insert into db_inventory.t_monstock_sales_berjalan_temp
        select kode, right(kode,2) as nocab, KODEPROD, sum(tot1) as omzet, sum(banyak) as omzet_unit,$id
        FROM
        (
            select concat(a.kode_comp, a.nocab) as kode, tot1, banyak, kodeprod
            from data$tahun_sekarang.fi a
            where bulan in ($bulan_sekarang)
            union all
            select concat(a.kode_comp, a.nocab) as kode, tot1, banyak, kodeprod
            from data$tahun_sekarang.ri a
            where bulan in ($bulan_sekarang)
        )a 
        GROUP BY kode, kodeprod
        ";

        $proses = $this->db->query($sql);
        echo "<pre>";
        print_r($sql);
        echo "</pre>";

        $this->db->query("delete from db_inventory.t_monstock_gabung_temp where userid = $id");
        $sql = "
        insert into db_inventory.t_monstock_gabung_temp
        select a.kode, a.nocab, a.kodeprod, a.avg_unit, b.stock_unit, c.unit as po_outstanding, d.omzet_unit as omzet_berjalan, e.target, $id
        from db_inventory.t_monstock_avg_temp a LEFT JOIN
        (
            select a.kode, a.nocab, a.kodeprod, a.stock_unit
            from db_inventory.t_monstock_stock_akhir_temp a
            where a.userid = $id
        )b on a.kode = b.kode and a.kodeprod = b.kodeprod LEFT JOIN
        (
            select a.kode, a.kodeprod, a.unit
            from db_inventory.t_monstock_po_blank_temp a
            where a.id = $id
        )c on a.kode = c.kode and a.kodeprod = c.kodeprod LEFT JOIN
        (
            select a.kode, a.kodeprod, a.omzet_unit
            from db_inventory.t_monstock_sales_berjalan_temp a
            where a.userid = $id
        )d on a.kode = d.kode and a.kodeprod = d.kodeprod
        LEFT JOIN
        (
            select a.kode, a.kodeprod, a.target
            from db_inventory.t_monstock_target a
            where bulan = $bulan_sekarang
        )e on a.kode = e.kode and a.kodeprod = e.kodeprod
        ORDER BY a.kode, a.kodeprod
        ";

        $proses = $this->db->query($sql);
        echo "<pre>";
        print_r($sql);
        echo "</pre>";

        $this->db->query("update db_inventory.t_monstock_gabung_temp a set a.avg_unit = 0 where a.avg_unit is null");
        $this->db->query("update db_inventory.t_monstock_gabung_temp a set a.stock_unit = 0 where a.stock_unit is null");
        $this->db->query("update db_inventory.t_monstock_gabung_temp a set a.unit_po_outstanding = 0 where a.unit_po_outstanding is null");
        $this->db->query("update db_inventory.t_monstock_gabung_temp a set a.unit_omzet_berjalan = 0 where a.unit_omzet_berjalan is null");
        $this->db->query("update db_inventory.t_monstock_gabung_temp a set a.unit_target = 0 where a.unit_target is null");

        $this->db->query("delete from db_inventory.t_monstock_deltomed_temp where userid = $id");
        $sql = "
        insert into db_inventory.t_monstock_deltomed_temp
        select 	a.kode, c.branch_name, c.nama_comp, d.grup, e.nama_group, a.kodeprod, d.namaprod, a.avg_unit, a.stock_unit, a.unit_po_outstanding, (a.stock_unit + a.unit_po_outstanding) as total_stock,
                a.unit_omzet_berjalan, a.unit_target, (a.unit_target - a.unit_omzet_berjalan) as potensi_sales,
                ((a.stock_unit + a.unit_po_outstanding) - (a.unit_target - a.unit_omzet_berjalan)) as stock_berjalan,
                ((a.stock_unit + a.unit_po_outstanding) - (a.unit_target - a.unit_omzet_berjalan)) / a.avg_unit * 30 as doi,
                b.sl as stock_ideal_hari, (a.avg_unit / 30 * b.sl) as stock_ideal_unit,
                if(((a.avg_unit / 30 * b.sl) - ((a.stock_unit + a.unit_po_outstanding) - (a.unit_target - a.unit_omzet_berjalan))) < 0,0,((a.avg_unit / 30 * b.sl) - ((a.stock_unit + a.unit_po_outstanding) - (a.unit_target - a.unit_omzet_berjalan)))) as suggest_po,
                $id, '$created'
        from db_inventory.t_monstock_gabung_temp a LEFT JOIN
        (
            select a.kode, a.sl
            from db_inventory.t_monstock_stock_level a
        )b on a.kode = b.kode LEFT JOIN
        (
            select concat(a.kode_comp,a.nocab) as kode, a.branch_name,a.nama_comp, a.urutan
            from mpm.tbl_tabcomp a
            where a.`status` = 1
            GROUP BY concat(a.kode_comp,a.nocab)
        )c on a.kode = c.kode
        LEFT JOIN
        (
            select a.kodeprod,a.namaprod,a.grup
            from mpm.tabprod a
        )d on a.kodeprod = d.kodeprod
        LEFT JOIN
        (
            select a.kode_group, a.nama_group
            from mpm.tbl_group a
        )e on d.grup = e.kode_group
        where a.userid = $id
        ORDER BY urutan asc, kodeprod
        ";

        $proses = $this->db->query($sql);
        echo "<pre>";
        print_r($sql);
        echo "</pre>";

        $sql = "
            update db_inventory.t_monstock_deltomed_temp a
            set a.potensi_sales = 0
            where a.potensi_sales < 0
        ";
        $proses = $this->db->query($sql);
        echo "<pre>";
        print_r($sql);
        echo "</pre>";

        $sql = $this->db->query("select max(a.created_date) as tanggal_max from db_inventory.t_monstock_deltomed_temp a")->result();
        foreach ($sql as $key) {
            $tanggal_max = $key->tanggal_max;
        }

        $sql = "
            select *
            from db_inventory.t_monstock_deltomed_temp a
            where a.created_date = '$tanggal_max'
        ";

        echo "<pre>";
        print_r($sql);
        echo "</pre>";

        $proses = $this->db->query($sql)->result();
        return $proses;
    }

}