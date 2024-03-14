<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Model_inventory extends CI_Model
{

    // function __construct()
    // {
    //     $created_by=$this->session->userdata('id');
    // }

    public function get_monitoring_stock_deltomed_last()
    {

        date_default_timezone_set('Asia/Jakarta');
        $created_date = '"' . date('Y-m-d H:i:s') . '"';
        $sql = "select * from db_temp.t_temp_monitoring_stock_report_update_hasil a where a.created_date = (select max(created_date) from db_temp.t_temp_monitoring_stock_report_update_hasil)";
        $proses = $this->db->query($sql)->result();

        if ($proses) {
            return $proses;
        } else {

            $sql = "
                select a.*,'' as total_stock_unit,'' as sales_berjalan_unit, '' as target_unit, '' as potensi_sales_unit, '' as stock_berjalan_unit,
                        '' as doi_unit, '' as sl_hari, '' as sl_unit, '' as suggest_po  
                from db_temp.t_temp_monitoring_stock_report a
                where a.created_date = (select max(created_date) from db_temp.t_temp_monitoring_stock_report)
                
            ";
            $proses = $this->db->query($sql)->result();
            if ($proses) {
                return $proses;
            } else {
                return array();
            }
        }
    }

    public function get_monitoring_stock_last($supp, $kode)
    {

        // echo $supp;
        // date_default_timezone_set('Asia/Jakarta'); 
        $created_date = '"' . date('Y-m-d H:i:s') . '"';
        // echo $created_date;

        if ($kode == null) {
            $isi_kode = "";
        } else {
            $isi_kode = "and kode in ($kode)";
        }

        $sql = "
            select  * 
            from    db_temp.t_temp_monitoring_stock a 
            where   a.created_date = (select max(created_date) from db_temp.t_temp_monitoring_stock) and a.supp = $supp $isi_kode";
        $proses = $this->db->query($sql)->result();

        if ($proses) {
            return $proses;
        } else {

            $sql = "
                select  a.*,'' as total_stock_unit,'' as sales_berjalan_unit, '' as target_unit, 
                        '' as potensi_sales_unit, '' as stock_berjalan_unit,
                        '' as doi_unit, '' as sl_hari, '' as sl_unit, '' as suggest_po  
                from    db_temp.t_temp_monitoring_stock_report a
                where   a.created_date = (select max(created_date) from db_temp.t_temp_monitoring_stock_report) and 
                        a.supp = $supp
            ";
            // echo "<pre>";
            // printf($sql);
            // echo "</pre>";
            $proses = $this->db->query($sql)->result();
            if ($proses) {
                return $proses;
            } else {
                return array();
            }
        }
    }

    public function monitoring_stock_deltomed_clear()
    {
        $sql = "
        truncate db_temp.t_temp_monitoring_stock_akhir
        ";
        $proses = $this->db->query($sql);
        $sql = "
        truncate db_temp.t_temp_monitoring_stock_avg
        ";
        $proses = $this->db->query($sql);
        $sql = "
        truncate db_temp.t_temp_monitoring_stock_po
        ";
        $proses = $this->db->query($sql);
        $sql = "
        truncate db_temp.t_temp_monitoring_stock_po_blank
        ";
        $proses = $this->db->query($sql);
        $sql = "
        truncate db_temp.t_temp_monitoring_stock_do_deltomed
        ";
        $proses = $this->db->query($sql);
        $sql = "
        truncate db_temp.t_temp_monitoring_stock_po_blank_update
        ";
        $proses = $this->db->query($sql);
        $sql = "
        truncate db_temp.t_temp_monitoring_stock_sales_berjalan
        ";
        $proses = $this->db->query($sql);
        $sql = "
        truncate db_temp.t_temp_monitoring_stock_report
        ";
        $proses = $this->db->query($sql);
        $sql = "
        truncate db_temp.t_temp_monitoring_stock_report_update
        ";
        $proses = $this->db->query($sql);
        $sql = "
        truncate db_temp.t_temp_monitoring_stock_report_update_hasil
        ";
        $proses = $this->db->query($sql);
        $sql = "
        truncate db_temp.t_temp_monitoring_stock_upload
        ";
        $proses = $this->db->query($sql);

        if ($proses) {
            return 1;
        } else {
            return array();
        }
    }

    public function monitoring_stock_deltomed_update()
    {
        $id = $this->session->userdata('id');
        date_default_timezone_set('Asia/Jakarta');
        $created_date = '"' . date('Y-m-d H:i:s') . '"';
        $tahun_sekarang = date('Y');
        $bulan_sekarang = date('m');
        $tanggal_sekarang = date('d');

        // echo "<pre>";
        // echo "id : ".$id."<br>";
        // echo "created_date : ".$created_date."<br>";
        // echo "tahun_sekarang : ".$tahun_sekarang."<br>";
        // echo "bulan_sekarang : ".$bulan_sekarang."<br>";
        // echo "tanggal_sekarang : ".$tanggal_sekarang."<br>";
        // echo "</pre>";

        $kodeprod = $this->model_per_hari->cari_kodeprod_supp('001');
        // echo $kodeprod;

        // $bulan_sekarang = '5';
        $bulan_sebelumnya = $bulan_sekarang - 1;
        $bulan_avg = $bulan_sekarang - 6;

        if ($bulan_avg > 0) {
            for ($i = $bulan_avg; $i < $bulan_sekarang; $i++) {
                $bulan_avg_x[] = $i;
            }
            $bulan_avg_y = implode(', ', $bulan_avg_x);
            $fi = "
                select 	concat(a.kode_comp, a.nocab) as kode, tot1, banyak, kodeprod
                from 	data$tahun_sekarang.fi a
                where 	bulan in ($bulan_avg_y) and 
                        kodeprod in ($kodeprod)
                union all
                select 	concat(a.kode_comp, a.nocab) as kode, tot1, banyak, kodeprod
                from 	data$tahun_sekarang.ri a
                where 	bulan in ($bulan_avg_y) and 
                        kodeprod in ($kodeprod)
            ";
        } elseif ($bulan_avg == 0) {
            // echo "bulan_sekarang : ".$bulan_sekarang;
            $tahun_avg_x = $tahun_sekarang - 1;
            // echo "tahun_avg_x : ".$tahun_avg_x;

            for ($i = 1; $i < $bulan_sekarang; $i++) {
                $bulan_avg_x[] = $i;
            }
            $bulan_avg_y = implode(', ', $bulan_avg_x);
            // echo "bulan_avg_y : ".$bulan_avg_y;

            $fi = "
                select 	concat(a.kode_comp, a.nocab) as kode, tot1, banyak, kodeprod
                from 	data$tahun_avg_x.fi a
                where 	bulan in (12) and 
                        kodeprod in ($kodeprod)
                union all
                select 	concat(a.kode_comp, a.nocab) as kode, tot1, banyak, kodeprod
                from 	data$tahun_avg_x.ri a
                where 	bulan in (12) and 
                        kodeprod in ($kodeprod)
                union all
                select 	concat(a.kode_comp, a.nocab) as kode, tot1, banyak, kodeprod
                from 	data$tahun_sekarang.fi a
                where 	bulan in ($bulan_avg_y) and 
                        kodeprod in ($kodeprod)
                union all
                select 	concat(a.kode_comp, a.nocab) as kode, tot1, banyak, kodeprod
                from 	data$tahun_sekarang.ri a
                where 	bulan in ($bulan_avg_y) and 
                        kodeprod in ($kodeprod)
            ";
        } else {
            // echo "bulan_sekarang : ".$bulan_sekarang;
            $tahun_avg_x = $tahun_sekarang - 1;
            // echo "tahun_avg_x : ".$tahun_avg_x;

            $bulan_avg_a = $bulan_sekarang - 6;
            $bulan_avg_ax = 12 + $bulan_avg_a;

            for ($i = $bulan_avg_ax; $i <= 12; $i++) {
                $bulan_avg_ay[] = $i;
            }
            $bulan_avg_a = implode(', ', $bulan_avg_ay);
            // echo "bulan_avg_a : ".$bulan_avg_a;

            for ($i = 1; $i < $bulan_sekarang; $i++) {
                $bulan_avg_x[] = $i;
            }
            $bulan_avg_y = implode(', ', $bulan_avg_x);
            // echo "bulan_avg_y : ".$bulan_avg_y;

            $fi = "
                select 	concat(a.kode_comp, a.nocab) as kode, tot1, banyak, kodeprod
                from 	data$tahun_avg_x.fi a
                where 	bulan in ($bulan_avg_a) and 
                        kodeprod in ($kodeprod)
                union all
                select 	concat(a.kode_comp, a.nocab) as kode, tot1, banyak, kodeprod
                from 	data$tahun_avg_x.ri a
                where 	bulan in ($bulan_avg_a) and 
                        kodeprod in ($kodeprod)
                union all
                select 	concat(a.kode_comp, a.nocab) as kode, tot1, banyak, kodeprod
                from 	data$tahun_sekarang.fi a
                where 	bulan in ($bulan_avg_y) and 
                        kodeprod in ($kodeprod)
                union all
                select 	concat(a.kode_comp, a.nocab) as kode, tot1, banyak, kodeprod
                from 	data$tahun_sekarang.ri a
                where 	bulan in ($bulan_avg_y) and 
                        kodeprod in ($kodeprod)
            ";
        }

        $sql_avg = "
            insert into db_temp.t_temp_monitoring_stock_avg
            SELECT kode, kodeprod, sum(banyak)/6 as rata,$id,$created_date
            FROM
            (
                $fi
            )a GROUP BY kode, kodeprod
        ";

        echo "<pre>";
        print_r($sql_avg);
        echo "</pre>";
        $proses_avg = $this->db->query($sql_avg);

        $sql_stock_akhir = "
        insert into db_temp.t_temp_monitoring_stock_akhir
        select c.kode, a.nocab, a.kodeprod, stock, $id, $created_date
        FROM
        (
            select a.nocab, a.kodeprod, a.stock
            FROM
            (
                select  nocab,kodeprod,namaprod,substr(bulan,3) as bulan,
                        sum( if(kode_gdg='PST',
                        ((Saldoawal+masuk_pbk+masuk_supp+retur_sal+bPinjam+kvretur+kr_gudang+retur_depo)-
                        (sales+pinjam+minta_depo+kvbeli+kr_min)-(rusak+sisih)),(Saldoawal+masuk_pbk+retur_sal+kvretur+bPinjam+tukar_msk+msk_gddepo) -
                        (sales+Pinjam+kvbeli+retur_depo+tukar_klr+klr_gddepo))) as stock
                from    data$tahun_sekarang.st 
                where 	substr(bulan,3) = $bulan_sekarang and kodeprod in ($kodeprod)
                group by nocab, kodeprod,bulan
                order by kodeprod
            )a 
        )a LEFT JOIN
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

        echo "<pre>";
        print_r($sql_stock_akhir);
        echo "</pre>";

        $proses_stock_akhir = $this->db->query($sql_stock_akhir);

        $sql = "
        insert into db_temp.t_temp_monitoring_stock_po
        select 	f.kode,a.nopo, a.tglpo,b.kodeprod, b.banyak, $id, $created_date
        from    mpm.po a INNER JOIN mpm.po_detail b 
                    on a.id = b.id_ref LEFT JOIN mpm.`user` e
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
                                where a.bulan in ($bulan_sebelumnya,$bulan_sekarang)
                                GROUP BY kode
                            )b on a.kode = b.kode
                            where b.kode is not null
                        )a
                    )f on e.username = f.kode_comp
        where `open` = 1  and a.deleted = 0 and b.deleted = 0 and year(tglpo) = $tahun_sekarang 
        and month(tglpo) in ($bulan_sebelumnya,$bulan_sekarang) and nopo not like '/mpm%' and a.nopo not like '%batal%' and b.kodeprod in ($kodeprod) and status_terima is null
        ORDER BY a.id desc
        ";

        $proses = $this->db->query($sql);
        echo "<br><br><br><br>";
        echo "<pre>";
        print_r($sql);
        echo "</pre>";

        $sql = "
        insert into db_temp.t_temp_monitoring_stock_do_deltomed
        select a.tgldo,a.nodo,a.nopo, a.kodeprod, a.kodeprod_delto, a.total_qty, $id, $created_date
        FROM
        (
            select sum(a.qty) total_qty, a.nopo,a.nodo, b.kodeprod, kodeprod_delto, str_to_date(a.tgldo,'%d/%m/%Y') as tgldo
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

        $sql = "
        insert into db_temp.t_temp_monitoring_stock_po_blank
        select  a.kode, a.nopo,a.tglpo, a.kodeprod, sum(a.banyak) as unit,b.nodo,b.tgldo, datediff(date(now()),date(tgldo)) as selisih, $id, $created_date
        from    db_temp.t_temp_monitoring_stock_po a LEFT JOIN 
                (
                    select  a.nopo, a.kodeprod,a.nodo,a.tgldo
                    from    db_temp.t_temp_monitoring_stock_do_deltomed a
                    where   a.id = $id
                )b on a.nopo = b.nopo and a.kodeprod = b.kodeprod        
        GROUP BY a.kode,a.nopo, a.kodeprod
        ";

        $proses = $this->db->query($sql);
        echo "<pre>";
        print_r($sql);
        echo "</pre>";

        $sql = "
        insert into db_temp.t_temp_monitoring_stock_sales_berjalan
        select kode, KODEPROD, sum(banyak) as omzet_unit,$id, $created_date
        FROM
        (
            select concat(a.kode_comp, a.nocab) as kode, banyak, kodeprod
            from data$tahun_sekarang.fi a
            where bulan in ($bulan_sekarang)
            union all
            select concat(a.kode_comp, a.nocab) as kode, banyak, kodeprod
            from data$tahun_sekarang.ri a
            where bulan in ($bulan_sekarang)
        )a 
        GROUP BY kode, kodeprod
        ";

        $proses = $this->db->query($sql);
        echo "<pre>";
        print_r($sql);
        echo "</pre>";

        $sql = "
        insert into db_temp.t_temp_monitoring_stock_upload
        select 	substring(filename,3,2) as nocab,lastupload,$id, $created_date
        from 		mpm.upload
        where 	bulan = $bulan_sekarang and tahun = $tahun_sekarang and id in (
            select max(id)
            from mpm.upload
            where tahun = $tahun_sekarang and bulan = $bulan_sekarang
            GROUP BY substring(filename,3,2)
        )and userid not in ('0','289')
        ";

        $proses = $this->db->query($sql);
        echo "<pre>";
        print_r($sql);
        echo "</pre>";

        $sql = "
        insert into db_temp.t_temp_monitoring_stock_report
        select a.kode, e.branch_name,e.nama_comp, a.kodeprod, f.namaprod, a.rata,b.stock_akhir_unit, c.unit as po_outstanding, d.omzet_unit as sales_berjalan,f.lastupload,$id, $created_date
        from db_temp.t_temp_monitoring_stock_avg a LEFT JOIN
        (
            select 	a.kode, a.kodeprod, a.stock_akhir_unit
            from 	db_temp.t_temp_monitoring_stock_akhir a
            where   a.id = $id and a.created_date = $created_date
        )b on a.kode = b.kode and a.kodeprod = b.kodeprod LEFT JOIN
        (
            select a.kode, a.kodeprod, sum(a.unit) as unit
            from db_temp.t_temp_monitoring_stock_po_blank a
            where a.kode is not null and a.id = $id and a.created_date = $created_date
            group by kode,kodeprod
        )c on a.kode = c.kode and a.kodeprod = c.kodeprod LEFT JOIN
        (
            select a.kode,a.kodeprod,a.omzet_unit
            from db_temp.t_temp_monitoring_stock_sales_berjalan a
            where   a.id = $id and a.created_date = $created_date
        )d on a.kode = d.kode and a.kodeprod = d.kodeprod LEFT JOIN
        (
            select concat(a.kode_comp,a.nocab) as kode, a.branch_name,a.nama_comp,a.urutan
            from mpm.tbl_tabcomp a
            where a.`status` = 1 
            GROUP BY concat(a.kode_comp,a.nocab)
        )e on a.kode = e.kode left join mpm.tabprod f on a.kodeprod = f.kodeprod LEFT JOIN
        (
            select a.nocab,a.lastupload
            from db_temp.t_temp_monitoring_stock_upload a
        )f on right(a.kode,2) = f.nocab
        where   a.id = $id and a.created_date = $created_date
        order by urutan,kodeprod
        ";

        $proses = $this->db->query($sql);
        echo "<pre>";
        print_r($sql);
        echo "</pre>";

        $sql = "select * from db_temp.t_temp_monitoring_stock_report a where a.id = $id and a.created_date = $created_date";
        $proses = $this->db->query($sql)->result();
        return $proses;
    }

    public function monitoring_stock_deltomed_update_next()
    {
        $id = $this->session->userdata('id');
        date_default_timezone_set('Asia/Jakarta');
        $created_date = '"' . date('Y-m-d H:i:s') . '"';
        $bulan_sekarang = date('m');

        $sql = "
            update  db_temp.t_temp_monitoring_stock_po_update a
            set     a.kodeprod = concat('0',a.kodeprod)
            where   length(a.kodeprod) = 5
        ";
        $proses = $this->db->query($sql);
        echo "<pre>";
        print_r($sql);
        echo "</pre>";

        $sql = "
            insert into db_temp.t_temp_monitoring_stock_po_blank_update
            select  a.kode, a.nopo, a.kodeprod, sum(a.po_outstanding_unit) as unit,b.nodo,b.tgldo,$id, $created_date
            from    db_temp.t_temp_monitoring_stock_po_update a LEFT JOIN 
                    (
                        select  a.nopo, a.kodeprod,a.nodo,a.tgldo
                        from    db_temp.t_temp_monitoring_stock_do_deltomed a
                        where   a.id = $id and created_date = (select max(created_date) from db_temp.t_temp_monitoring_stock_do_deltomed where id = $id)
                    )b on a.nopo = b.nopo and a.kodeprod = b.kodeprod  
            where   a.`status` = 'n'      
            GROUP BY a.kode, a.kodeprod
        ";
        $proses = $this->db->query($sql);
        echo "<pre>";
        print_r($sql);
        echo "</pre>";

        $sql = "
            insert into db_temp.t_temp_monitoring_stock_report_update
            select 	a.kode, a.branch_name, a.nama_comp, a.kodeprod, a.namaprod, a.rata, a.stock_akhir_unit, b.unit as po_outstanding, a.sales_berjalan,a.lastupload, c.unit as target,$id, $created_date
            from db_temp.t_temp_monitoring_stock_report a LEFT JOIN
            (
                select a.kode,a.kodeprod,a.unit
                from db_temp.t_temp_monitoring_stock_po_blank_update a
            )b on a.kode = b.kode and a.kodeprod = b.kodeprod LEFT JOIN
            (
                select a.kode, a.kodeprod, a.unit
                from db_temp.t_bantu_target a
                where a.bulan = $bulan_sekarang and kode not in ('PURP7')
            )c on a.kode = c.kode and a.kodeprod = c.kodeprod
            where a.id = $id and a.created_date = (select max(created_date) from db_temp.t_temp_monitoring_stock_report where id = $id)
        ";

        $proses = $this->db->query($sql);
        echo "<pre>";
        print_r($sql);
        echo "</pre>";

        $sql = "
            update db_temp.t_temp_monitoring_stock_report_update a
            set a.stock_akhir_unit = 0
            where a.stock_akhir_unit is null and a.id = $id and a.created_date = $created_date
        ";

        $proses = $this->db->query($sql);
        echo "<pre>";
        print_r($sql);
        echo "</pre>";

        $sql = "
            update db_temp.t_temp_monitoring_stock_report_update a
            set a.po_outstanding_unit = 0
            where a.po_outstanding_unit is null and a.id = $id and a.created_date = $created_date
        ";

        $proses = $this->db->query($sql);
        echo "<pre>";
        print_r($sql);
        echo "</pre>";

        $sql = "
            update db_temp.t_temp_monitoring_stock_report_update a
            set a.sales_berjalan = 0
            where a.sales_berjalan is null and a.id = $id and a.created_date = $created_date
        ";

        $proses = $this->db->query($sql);
        echo "<pre>";
        print_r($sql);
        echo "</pre>";

        $sql = "
            update db_temp.t_temp_monitoring_stock_report_update a
            set a.target_unit = 0
            where a.target_unit is null and a.id = $id and a.created_date = $created_date
        ";

        $proses = $this->db->query($sql);
        echo "<pre>";
        print_r($sql);
        echo "</pre>";

        $sql = "
        insert into db_temp.t_temp_monitoring_stock_report_update_hasil
        select 	a.kode, a.branch_name, a.nama_comp, a.kodeprod, a.namaprod, a.rata,
                a.stock_akhir_unit, a.po_outstanding_unit, 
                (a.stock_akhir_unit + a.po_outstanding_unit) as total_stock, 
                a.sales_berjalan, a.target_unit,
                (a.target_unit - a.sales_berjalan) as potensi_sales,
                ((a.stock_akhir_unit + a.po_outstanding_unit) - (a.target_unit - a.sales_berjalan)) as stock_berjalan,
                ((a.stock_akhir_unit + a.po_outstanding_unit) - (a.target_unit - a.sales_berjalan)) / a.rata * 30 as doi,
                b.sl as stock_ideal_hari, (a.rata / 30 * b.sl) as stock_ideal_unit,
                if(((a.rata / 30 * b.sl) - ((a.stock_akhir_unit + a.po_outstanding_unit) - 
                (a.target_unit - a.sales_berjalan))) < 0,0,((a.rata / 30 * b.sl) - ((a.stock_akhir_unit + a.po_outstanding_unit) - (a.target_unit - a.sales_berjalan)))) as suggest_po,(
                    if(((a.rata / 30 * b.sl) - ((a.stock_akhir_unit + a.po_outstanding_unit) - 
                    (a.target_unit - a.sales_berjalan))) < 0,0,((a.rata / 30 * b.sl) - ((a.stock_akhir_unit + a.po_outstanding_unit) - (a.target_unit - a.sales_berjalan))))
                )+(a.stock_akhir_unit + a.po_outstanding_unit)-(a.target_unit - a.sales_berjalan) as estimasi_stock_akhir,
                a.lastupload,$id, $created_date
        from db_temp.t_temp_monitoring_stock_report_update a LEFT JOIN
        (
            select a.kode, a.sl
            from db_inventory.t_monstock_stock_level a
            ORDER BY kode
        )b on a.kode = b.kode 
        where a.id = $id and a.created_date = $created_date and a.kode not in (
            select c.kode
            from db_temp.t_dp_non_stock c
            where c.`status` = 1 
        )
        ";
        $proses = $this->db->query($sql);
        echo "<pre>";
        print_r($sql);
        echo "</pre>";

        $sql = "
        insert into db_temp.t_temp_monitoring_stock_report_update_hasil
        select 	
                a.kode, a.branch_name, a.nama_comp, a.kodeprod, a.namaprod, 
				avg(a.rata),
				sum(a.stock_akhir_unit), sum(a.po_outstanding_unit), 
				sum((a.stock_akhir_unit + a.po_outstanding_unit)) as total_stock, 
				sum(a.sales_berjalan), sum(a.target_unit),
				(sum(a.target_unit) - sum(a.sales_berjalan)) as potensi_sales,
				((sum(a.stock_akhir_unit) + sum(a.po_outstanding_unit)) - (sum(a.target_unit) - sum(a.sales_berjalan))) as stock_berjalan,
				((sum(a.stock_akhir_unit) + sum(a.po_outstanding_unit)) - (sum(a.target_unit) - sum(a.sales_berjalan))) / avg(a.rata) * 30 as doi,
				b.sl as stock_ideal_hari, (avg(a.rata) / 30 * b.sl) as stock_ideal_unit,
				if(((avg(a.rata) / 30 * b.sl) - ((sum(a.stock_akhir_unit) + sum(a.po_outstanding_unit)) - 
				(sum(a.target_unit) - sum(a.sales_berjalan)))) < 0,0,((avg(a.rata) / 30 * b.sl) - ((sum(a.stock_akhir_unit) + sum(a.po_outstanding_unit)) - (sum(a.target_unit) - sum(a.sales_berjalan))))) as suggest_po,(
						if(((avg(a.rata) / 30 * b.sl) - ((sum(a.stock_akhir_unit) + sum(a.po_outstanding_unit)) - 
						(sum(a.target_unit) - sum(a.sales_berjalan)))) < 0,0,((avg(a.rata) / 30 * b.sl) - ((sum(a.stock_akhir_unit) + sum(a.po_outstanding_unit)) - (sum(a.target_unit) - sum(a.sales_berjalan)))))
				)+(sum(a.stock_akhir_unit) + sum(a.po_outstanding_unit))-(sum(a.target_unit) - sum(a.sales_berjalan)) as estimasi_stock_akhir,
				a.lastupload,$id, $created_date
        from db_temp.t_temp_monitoring_stock_report_update a LEFT JOIN
        (
            select a.kode, a.sl
            from db_inventory.t_monstock_stock_level a
            ORDER BY kode
        )b on a.kode = b.kode INNER JOIN
        (
            select a.kode,a.nama_comp,a.sub,a.status_branch 
            from db_temp.t_dp_non_stock a
            where a.`status` = 1 
        )c on a.kode = c.kode
        GROUP BY sub, kodeprod
        ";
        $proses = $this->db->query($sql);
        echo "<pre>";
        print_r($sql);
        echo "</pre>";

        $sql = "select * from db_temp.t_temp_monitoring_stock_report_update_hasil a where a.id = $id and a.created_date = $created_date";
        $proses = $this->db->query($sql)->result();
        return $proses;
    }

    public function stock_akhir_doi($data, $created_at)
    {
        $created_by = $this->session->userdata('id');
        $bulan = substr($data['bulan'], 5, 2);
        $tahun = substr($data['bulan'], 0, 4);
        $kodeprod = $data['kodeprod'];

        $wilayah_nocab = $data['wilayah_nocab'];
        if ($wilayah_nocab == null) {
            $wilayah = '';
            $wilayahx = '';
        } else {
            $wilayah = "and (nocab in ($wilayah_nocab) OR concat(kode_comp,nocab) in ($wilayah_nocab))";
            $wilayahx = "and right(a.kode,2) in ($wilayah_nocab)";
        }

        $bulan_sebelumnya = $bulan - 1;
        $bulan_avg = $bulan - 6;
        if ($bulan_avg >= 0) {
            for ($i = $bulan_avg + 1; $i <= $bulan; $i++) {
                $bulan_avg_x[] = $i;
            }
            $bulan_avg_y = implode(', ', $bulan_avg_x);
            $fi = "
                select 	if(concat(a.kode_comp, a.nocab) = 'JBG46' OR concat(a.kode_comp, a.nocab) = 'MJK46','SDO46',concat(a.kode_comp, a.nocab)) as site_code, tot1, banyak, kodeprod
                from 	data$tahun.fi a
                where 	bulan in ($bulan_avg_y) and 
                        kodeprod in ($kodeprod) $wilayah
                union all
                select 	if(concat(a.kode_comp, a.nocab) = 'JBG46' OR concat(a.kode_comp, a.nocab) = 'MJK46','SDO46',concat(a.kode_comp, a.nocab)) as site_code, tot1, banyak, kodeprod
                from 	data$tahun.ri a
                where 	bulan in ($bulan_avg_y) and 
                        kodeprod in ($kodeprod) $wilayah
            ";
        } else {
            $tahun_avg_x = $tahun - 1;
            $bulan_avg_a = $bulan - 6;
            $bulan_avg_ax = 12 + $bulan_avg_a;
            for ($i = $bulan_avg_ax + 1; $i <= 12; $i++) {
                $bulan_avg_ay[] = $i;
            }
            $bulan_avg_a = implode(', ', $bulan_avg_ay);

            for ($i = 1; $i <= $bulan; $i++) {
                $bulan_avg_x[] = $i;
            }
            $bulan_avg_y = implode(', ', $bulan_avg_x);

            $fi = "
                select 	if(concat(a.kode_comp, a.nocab) = 'JBG46' OR concat(a.kode_comp, a.nocab) = 'MJK46','SDO46',concat(a.kode_comp, a.nocab)) as site_code, tot1, banyak, kodeprod
                from 	data$tahun_avg_x.fi a
                where 	bulan in ($bulan_avg_a) and 
                        kodeprod in ($kodeprod) $wilayah
                union all
                select 	if(concat(a.kode_comp, a.nocab) = 'JBG46' OR concat(a.kode_comp, a.nocab) = 'MJK46','SDO46',concat(a.kode_comp, a.nocab)) as site_code, tot1, banyak, kodeprod
                from 	data$tahun_avg_x.ri a
                where 	bulan in ($bulan_avg_a) and 
                        kodeprod in ($kodeprod) $wilayah
                union all
                select 	if(concat(a.kode_comp, a.nocab) = 'JBG46' OR concat(a.kode_comp, a.nocab) = 'MJK46','SDO46',concat(a.kode_comp, a.nocab)) as site_code, tot1, banyak, kodeprod
                from 	data$tahun.fi a
                where 	bulan in ($bulan_avg_y) and 
                        kodeprod in ($kodeprod) $wilayah
                union all
                select 	if(concat(a.kode_comp, a.nocab) = 'JBG46' OR concat(a.kode_comp, a.nocab) = 'MJK46','SDO46',concat(a.kode_comp, a.nocab)) as site_code, tot1, banyak, kodeprod
                from 	data$tahun.ri a
                where 	bulan in ($bulan_avg_y) and 
                        kodeprod in ($kodeprod) $wilayah
            ";
        }

        $sql_insert = "
        insert into site.temp_stock_doi
        select  a.kode,a.branch_name, a.nama_comp, a.namasupp, a.kodeprod, a.harga, $bulan, 
                if(b.stok is NULL, stok_rumus_lama, b.stok) as stok, c.avg_unit,'$created_at', $created_by
        from db_master_data.t_temp_monitoring_stock_produk a LEFT JOIN 
        (
            select  nocab,kodeprod,substr(bulan,3) as bulan,
                    sum((Saldoawal+masuk_pbk+retur_sal+retur_depo)-(sales+minta_depo)) as stok_rumus_lama,
				    sum(a.stok_akhir) as stok
            from    data$tahun.st a
            where 	substr(bulan,3) = $bulan and kodeprod in ($kodeprod) and kode_gdg in ('pst',1) and 	(a.GUDANG_ID is NULL or a.GUDANG_ID in (1))
            group by nocab, kodeprod,bulan
            order by kodeprod
        )b on right(a.kode,2) = b.nocab and a.kodeprod = b.kodeprod LEFT JOIN 
        (
            select 	site_code, right(site_code,2) as nocab, KODEPROD, sum(tot1) as omzet, sum(tot1/6) as avg, 
                    sum(banyak) as omzet_unit, sum(banyak/6) as avg_unit
            FROM
            (  
                $fi
            )a GROUP BY site_code, kodeprod
        )c on a.kode = c.site_code and a.kodeprod = c.kodeprod
        where a.kodeprod in ($kodeprod) $wilayahx
        ";

        $proses_report = $this->db->query($sql_insert);

        // echo "<pre><br><br><br><br>";
        // print_r($sql_insert);
        // echo "</pre>";

        $sql_report = "
            insert into site.temp_stock_doi_report
            select  a.branch_name, a.nama_comp, a.namasupp, a.kodeprod, b.namaprod, b.nama_group, b.nama_sub_group,
                    a.stock_akhir, a.avg_unit, round(a.stock_akhir / a.avg_unit * 30) as doi_unit, a.harga,
                    round(a.stock_akhir * a.harga) as stock_value, round(a.avg_unit * a.harga) as avg_value,
                    round(a.stock_akhir * a.harga / a.avg_unit * 30) as doi_value, a.bulan, '$created_at', $created_by
            from    site.temp_stock_doi a LEFT JOIN
            (
                select a.supp,a.kodeprod, a.namaprod, a.grup, a.subgroup, b.nama_group, c.nama_sub_group
                from mpm.tabprod a LEFT JOIN 
                (
                    select a.kode_group, a.nama_group
                    from mpm.tbl_group a
                )b on a.grup = b.kode_group LEFT JOIN
                (
                    select a.sub_group, a.nama_sub_group
                    from db_produk.t_sub_group a
                )c on a.subgroup = c.sub_group
            )b on a.kodeprod = b.kodeprod
            where a.created_by = $created_by and a.created_at = '$created_at'
        ";
        $proses_report = $this->db->query($sql_report);

        // echo "<pre>";
        // print_r($sql_report);
        // echo "</pre>";

        // die;

        $sql = "select * from site.temp_stock_doi_report a where a.created_by = $created_by and a.created_at = '$created_at' limit 100";
        $proses = $this->db->query($sql)->result();

        if ($proses) {
            return $proses;
        } else {
            return array();
        }
    }

    public function laporan_po($data)
    {
        $id = $this->session->userdata('id');
        $periode_1 = $data['periode_1'];
        $periode_2 = $data['periode_2'];
        $kodeprod = $data['kodeprod'];
        $get_userid = $data['get_userid'];
        $created_date = $data['created_date'];

        $get_userid = $data['get_userid'];
        if ($get_userid == null) {
            $wilayah = '';
        } else {
            $wilayah = "and userid in ($get_userid)";
        }

        $bukan_po = $data['bukan_po'];
        if ($bukan_po == '1') {
            // echo "<br><br><br><br><br><br>ok";
            $parameter = "a.deleted = 0 and nopo is null and date(a.tglpesan) between '$periode_1' and '$periode_2' $wilayah";
        } else {
            // echo "<br><br><br><br><br><br>no";
            $parameter = "`open` = 1  and a.deleted = 0 and nopo not like '/mpm%' and a.nopo not like '%batal%' and date(a.tglpo) between '$periode_1' and '$periode_2' $wilayah";
        }

        $thn = date('Y');

        $sql = "
        insert into site.temp_laporan_po
        select  if(a.company like 'PT. MPI%', 'MPI', e.branch_name) as branch_name,if(a.company like 'PT. MPI%', a.company, e.nama_comp) as nama_comp,a.company, if(a.company like '%PT. MPI%','MT','GT') as channel,
                a.nopo, a.po_ref,a.tipe,a.tglpo,month(a.tglpo) as bulan, year(a.tglpo) as tahun, a.tglpesan,a.alamat, c.supp, f.namasupp as principal, b.kodeprod, 
                c.namaprod, c.nama_group,c.nama_sub_group, b.banyak, b.hna, b.total, $id, '$created_date'
        from 
        (
            select 	a.id, a.nopo,a.po_ref,a.tipe, date(a.tglpo) as tglpo, a.alamat, a.userid, a.company, date(a.tglpesan) as tglpesan, a.kode_alamat
            from 	mpm.po a 
            where $parameter 
        )a INNER JOIN
        (
            select b.id_ref, b.kodeprod, b.banyak ,b.harga as hna,(b.banyak*b.harga) as total
            from mpm.po_detail b
            where b.kodeprod in ($kodeprod) and b.deleted = 0
        )b on a.id = b.id_ref inner JOIN 
        (
            select 	a.kodeprod, a.namaprod, a.grup, b.nama_group, a.subgroup, c.nama_sub_group, supp
            from 	mpm.tabprod a LEFT JOIN mpm.tbl_group b 
                        on a.grup = b.kode_group LEFT JOIN db_produk.t_sub_group c
                        on a.subgroup = c.sub_group	
            where a.kodeprod in ($kodeprod)
        )c on b.kodeprod = c.kodeprod 
        left JOIN
        (
            select a.username,a.id
            from mpm.`user` a
        )d on a.userid = d.id
        left JOIN
        (
            select a.kode, a.branch_name, a.nama_comp, a.kode_comp
            FROM
            (
                select concat(a.kode_comp, a.nocab) as kode, a.branch_name, a.kode_comp, a.nama_comp
                from mpm.tbl_tabcomp a
                where a.`status` = 1
                GROUP BY kode
            )a LEFT JOIN 
            (
                select concat(a.kode_comp, a.nocab) as kode, a.kode_comp, a.nocab
                from db_dp.t_dp a
                where a.`status` = 1 and a.tahun = $thn
            )b on a.kode = b.kode
        )e on a.kode_alamat = e.kode left join 
        (
            select a.supp, a.namasupp
            from mpm.tabsupp a 
        )f on c.supp = f.supp
        ORDER BY nama_comp, kodeprod
        ";

        // echo "<pre><br><br><br>";
        // print_r($sql);
        // echo "</pre>";
        $this->db->query($sql);


        $tampil = "
        select a.branch_name, a.nama_comp, a.company, a.channel, sum(a.banyak) as total_unit, sum(a.banyak*a.harga) as total_value
        from site.temp_laporan_po a
        where a.id = $id and created_date = '$created_date'
        GROUP BY company
        union ALL
        select 'GRAND TOTAL', '', '', '',sum(a.banyak) as total_unit, sum(a.banyak*a.harga) as total_value
        from site.temp_laporan_po a
        where a.id = $id and created_date = '$created_date'
        ";
        // $tampil = "select * from db_temp.t_temp_laporan_po a where a.id = $id and created_date = $created_date";
        $proses_tampil = $this->db->query($tampil)->result();
        if ($proses_tampil) {
            return $proses_tampil;
        } else {
            return array();
        }
    }

    public function master_product()
    {
        $sql = "
            select b.namasupp, a.kodeprod,a.namaprod,a.kodeprod_deltomed, a.berat, a.volume, a.active
            from mpm.tabprod a LEFT JOIN mpm.tabsupp b
                on a.supp = b.SUPP        
            ORDER BY a.supp, a.kodeprod 
        ";
        $proses_tampil = $this->db->query($sql)->result();
        if ($proses_tampil) {
            return $proses_tampil;
        } else {
            return array();
        }
    }

    public function master_product_detail($kodeprod)
    {
        $sql = "
            select a.kodeprod, a.namaprod, a.kodeprod_deltomed, a.berat, a.volume, a.ISISATUAN as satuan
            from mpm.tabprod a
            where kodeprod = '$kodeprod' 
        ";
        $proses_tampil = $this->db->query($sql)->result();
        if ($proses_tampil) {
            return $proses_tampil;
        } else {
            return array();
        }
    }

    public function master_product_detail_update($data)
    {
        $kodeprod = $data['kodeprod'];
        $kodeprod_deltomed = $data['kodeprod_deltomed'];
        $berat = $data['berat'];
        $volume = $data['volume'];
        $satuan = $data['satuan'];
        $sql = "
            update mpm.tabprod a
            set a.kodeprod_deltomed = '$kodeprod_deltomed',
                a.berat = '$berat',
                a.volume = '$volume',
                a.isisatuan = '$satuan'
            where kodeprod = '$kodeprod' 
        ";
        $proses_tampil = $this->db->query($sql);
        if ($proses_tampil) {
            redirect(base_url() . "inventory/master_product");
        } else {
            return array();
        }
    }

    public function monitoring_stock_us_proses()
    {
        $id = $this->session->userdata('id');
        $created_date = '"' . date('Y-m-d H:i:s') . '"';
        $tahun_sekarang = date('Y');
        $bulan_sekarang = date('m');
        $tanggal_sekarang = date('d');
        $supp = "'005'";
        $kode_comp = "'BBT25','BJNB2','BYWB1','GSKG1','JMB29','JTM91','LMJL1','PRBP1','SB4S3','STBS1','TBNT1'";

        // echo "<pre>";
        // echo "id : ".$id."<br>";
        // echo "created_date : ".$created_date."<br>";
        // echo "tahun_sekarang : ".$tahun_sekarang."<br>";
        // echo "bulan_sekarang : ".$bulan_sekarang."<br>";
        // echo "tanggal_sekarang : ".$tanggal_sekarang."<br>";
        // echo "</pre>";

        $kodeprod = $this->model_per_hari->cari_kodeprod_supp('005');
        // echo $kodeprod;

        // $bulan_sekarang = '5';
        $bulan_sebelumnya = $bulan_sekarang - 1;
        $bulan_avg = $bulan_sekarang - 6;

        // echo "bulan_sebelumnya : ".$bulan_sebelumnya."<br>";
        // echo "bulan_avg : ".$bulan_avg;

        if ($bulan_avg > 0) {
            for ($i = $bulan_avg; $i < $bulan_sekarang; $i++) {
                $bulan_avg_x[] = $i;
            }
            $bulan_avg_y = implode(', ', $bulan_avg_x);
            $fi = "
                select 	concat(a.kode_comp, a.nocab) as kode, tot1, banyak, kodeprod
                from 	data$tahun_sekarang.fi a
                where 	bulan in ($bulan_avg_y) and 
                        kodeprod in ($kodeprod) and concat(a.kode_comp, a.nocab) in ($kode_comp)
                union all
                select 	concat(a.kode_comp, a.nocab) as kode, tot1, banyak, kodeprod
                from 	data$tahun_sekarang.ri a
                where 	bulan in ($bulan_avg_y) and 
                        kodeprod in ($kodeprod) and concat(a.kode_comp, a.nocab) in ($kode_comp)
            ";
        } elseif ($bulan_avg == 0) {
            // echo "bulan_sekarang : ".$bulan_sekarang;
            $tahun_avg_x = $tahun_sekarang - 1;
            // echo "tahun_avg_x : ".$tahun_avg_x;

            for ($i = 1; $i < $bulan_sekarang; $i++) {
                $bulan_avg_x[] = $i;
            }
            $bulan_avg_y = implode(', ', $bulan_avg_x);
            // echo "bulan_avg_y : ".$bulan_avg_y;

            $fi = "
                select 	concat(a.kode_comp, a.nocab) as kode, tot1, banyak, kodeprod
                from 	data$tahun_avg_x.fi a
                where 	bulan in (12) and 
                        kodeprod in ($kodeprod) and concat(a.kode_comp, a.nocab) in ($kode_comp)
                union all
                select 	concat(a.kode_comp, a.nocab) as kode, tot1, banyak, kodeprod
                from 	data$tahun_avg_x.ri a
                where 	bulan in (12) and 
                        kodeprod in ($kodeprod) and concat(a.kode_comp, a.nocab) in ($kode_comp)
                union all
                select 	concat(a.kode_comp, a.nocab) as kode, tot1, banyak, kodeprod
                from 	data$tahun_sekarang.fi a
                where 	bulan in ($bulan_avg_y) and 
                        kodeprod in ($kodeprod) and concat(a.kode_comp, a.nocab) in ($kode_comp)
                union all
                select 	concat(a.kode_comp, a.nocab) as kode, tot1, banyak, kodeprod
                from 	data$tahun_sekarang.ri a
                where 	bulan in ($bulan_avg_y) and 
                        kodeprod in ($kodeprod) and concat(a.kode_comp, a.nocab) in ($kode_comp)
            ";
        } else {
            // echo "bulan_sekarang : ".$bulan_sekarang;
            $tahun_avg_x = $tahun_sekarang - 1;
            // echo "tahun_avg_x : ".$tahun_avg_x;

            $bulan_avg_a = $bulan_sekarang - 6;
            $bulan_avg_ax = 12 + $bulan_avg_a;

            for ($i = $bulan_avg_ax; $i <= 12; $i++) {
                $bulan_avg_ay[] = $i;
            }
            $bulan_avg_a = implode(', ', $bulan_avg_ay);
            // echo "bulan_avg_a : ".$bulan_avg_a;

            for ($i = 1; $i < $bulan_sekarang; $i++) {
                $bulan_avg_x[] = $i;
            }
            $bulan_avg_y = implode(', ', $bulan_avg_x);
            // echo "bulan_avg_y : ".$bulan_avg_y;

            $fi = "
                select 	concat(a.kode_comp, a.nocab) as kode, tot1, banyak, kodeprod
                from 	data$tahun_avg_x.fi a
                where 	bulan in ($bulan_avg_a) and 
                        kodeprod in ($kodeprod) and concat(a.kode_comp, a.nocab) in ($kode_comp)
                union all
                select 	concat(a.kode_comp, a.nocab) as kode, tot1, banyak, kodeprod
                from 	data$tahun_avg_x.ri a
                where 	bulan in ($bulan_avg_a) and 
                        kodeprod in ($kodeprod) and concat(a.kode_comp, a.nocab) in ($kode_comp)
                union all
                select 	concat(a.kode_comp, a.nocab) as kode, tot1, banyak, kodeprod
                from 	data$tahun_sekarang.fi a
                where 	bulan in ($bulan_avg_y) and 
                        kodeprod in ($kodeprod) and concat(a.kode_comp, a.nocab) in ($kode_comp)
                union all
                select 	concat(a.kode_comp, a.nocab) as kode, tot1, banyak, kodeprod
                from 	data$tahun_sekarang.ri a
                where 	bulan in ($bulan_avg_y) and 
                        kodeprod in ($kodeprod) and concat(a.kode_comp, a.nocab) in ($kode_comp)
            ";
        }

        // echo "<pre>";
        // print_r($fi);
        // echo "</pre>";

        $sql_avg = "
            insert into db_temp.t_temp_monitoring_stock_avg
            SELECT kode, kodeprod, round(sum(banyak)/6) as rata,$id,$created_date,$supp
            FROM
            (
                $fi
            )a GROUP BY kode, kodeprod
        ";

        // echo "<pre>";
        // print_r($sql_avg);
        // echo "</pre>";
        $proses_avg = $this->db->query($sql_avg);

        $sql_stock_akhir = "
        insert into db_temp.t_temp_monitoring_stock_akhir
        select c.kode, a.nocab, a.kodeprod, stock, $id, $created_date,$supp
        FROM
        (
            select a.nocab, a.kodeprod, a.stock
            FROM
            (
                select  nocab,kodeprod,namaprod,substr(bulan,3) as bulan,
                        sum( if(kode_gdg='PST',
                        ((Saldoawal+masuk_pbk+masuk_supp+retur_sal+bPinjam+kvretur+kr_gudang+retur_depo)-
                        (sales+pinjam+minta_depo+kvbeli+kr_min)-(rusak+sisih)),(Saldoawal+masuk_pbk+retur_sal+kvretur+bPinjam+tukar_msk+msk_gddepo) -
                        (sales+Pinjam+kvbeli+retur_depo+tukar_klr+klr_gddepo))) as stock
                from    data$tahun_sekarang.st 
                where 	substr(bulan,3) = $bulan_sebelumnya and kodeprod in ($kodeprod)
                group by nocab, kodeprod,bulan
                order by kodeprod
            )a 
        )a INNER JOIN
        (
            select a.kode, nocab, a.branch_name, a.nama_comp
            FROM
            (
                select concat(a.kode_comp, a.nocab) as kode, a.branch_name, a.nama_comp, a.nocab
                from mpm.tbl_tabcomp a
                where a.`status` = 1 and (a.stock <> 2 or a.stock is null) and a.sub = 91
                GROUP BY concat(a.kode_comp, a.nocab)
            )a INNER JOIN
            (
                SELECT concat(a.kode_comp,a.nocab) as kode
                from db_dp.t_dp a
                where tahun = $tahun_sekarang
            )b on a.kode = b.kode
            ORDER BY nocab
        )c on a.nocab = c.nocab
        ORDER BY nocab, kodeprod
        ";

        // echo "<pre>";
        // print_r($sql_stock_akhir);
        // echo "</pre>";

        $proses_stock_akhir = $this->db->query($sql_stock_akhir);

        // $sql = "
        // insert into db_temp.t_temp_monitoring_stock_po
        // select b.kode,a.nopo,a.tglpo,a.kodeprod,a.banyak, $id, $created_date,$supp
        // from
        // (
        //     select 	a.nopo, a.tglpo,b.kodeprod, b.banyak,e.username
        //     from    mpm.po a INNER JOIN mpm.po_detail b 
        //                 on a.id = b.id_ref LEFT JOIN mpm.`user` e
        //                 on a.userid = e.id 
        //     where `open` = 1  and a.deleted = 0 and b.deleted = 0 and year(tglpo) = $tahun_sekarang 
        //     and month(tglpo) in ($bulan_sebelumnya,$bulan_sekarang) and nopo not like '/mpm%' and a.nopo not like '%batal%' and b.kodeprod in ($kodeprod) and status_terima is null
        //     ORDER BY a.id desc
        // )a INNER JOIN
        // (            
        //     select a.kode,a.kode_comp
        //     from
        //     (
        //         select concat(a.kode_comp,a.nocab) as kode, a.kode_comp
        //         from mpm.tbl_tabcomp a
        //         where a.`status` = 1 and concat(a.kode_comp,a.nocab) in ($kode_comp)
        //         GROUP BY kode
        //     )a INNER JOIN 
        //     (
        //         select concat(a.kode_comp,a.nocab) as kode, a.kode_comp
        //         from db_dp.t_dp a
        //         where tahun = $tahun_sekarang
        //     )b on a.kode=b.kode
        // )b on a.username = b.kode_comp
        // ";

        // $proses = $this->db->query($sql);
        // echo "<br><br><br><br>";
        // echo "<pre>";
        // print_r($sql);
        // echo "</pre>";

        // $sql = "
        // insert into db_temp.t_temp_monitoring_stock_do
        // select str_to_date(a.tgldo,'%Y%m%d') as tgldo, a.nodo , replace(a.nopo,right(a.nopo,'1'),'') as nopo, a.kodeprod,a.banyak, $id, $created_date,$supp
        // from db_po.t_do_us a
        // where month(str_to_date(a.tgldo,'%Y%m%d')) in ($bulan_sebelumnya,$bulan_sekarang) 
        // ";

        // $proses = $this->db->query($sql);
        // echo "<pre>";
        // print_r($sql);
        // echo "</pre>";

        // //men-join-kan tabel po dengan do
        // $sql = "
        // insert into db_temp.t_temp_monitoring_stock_po_blank
        // select a.kode,a.nopo,a.tglpo,a.kodeprod,a.banyak as unit_po, b.nodo, b.tgldo,datediff(date(now()),date(tgldo)) as selisih, $id, $created_date,$supp
        // from db_temp.t_temp_monitoring_stock_po a left join (
        //     select a.nopo, a.kodeprod, a.nodo, a.banyak, a.tgldo
        //     from db_temp.t_temp_monitoring_stock_do a
        //     where a.supp = $supp
        // )b on a.nopo = b.nopo and a.kodeprod = b.kodeprod
        // where a.supp = $supp 
        // group by a.nopo
        // ORDER BY b.banyak desc
        // ";

        // $proses = $this->db->query($sql);
        // echo "<pre>";
        // print_r($sql);
        // echo "</pre>";

        // $sql = "
        // insert into db_temp.t_temp_monitoring_stock_sales_berjalan
        // select kode, KODEPROD, sum(banyak) as omzet_unit,$id, $created_date,$supp
        // FROM
        // (
        //     select concat(a.kode_comp, a.nocab) as kode, banyak, kodeprod
        //     from data$tahun_sekarang.fi a
        //     where bulan in ($bulan_sekarang) and kodeprod in ($kodeprod) and concat(a.kode_comp, a.nocab) in ($kode_comp)
        //     union all
        //     select concat(a.kode_comp, a.nocab) as kode, banyak, kodeprod
        //     from data$tahun_sekarang.ri a
        //     where bulan in ($bulan_sekarang) and kodeprod in ($kodeprod) and concat(a.kode_comp, a.nocab) in ($kode_comp)
        // )a 
        // GROUP BY kode, kodeprod
        // ";

        // $proses = $this->db->query($sql);
        // echo "<pre>";
        // print_r($sql);
        // echo "</pre>";

        $sql_upload = "
        insert into db_temp.t_temp_monitoring_stock_upload
        select 	substring(filename,3,2) as nocab,date(lastupload) as lastupload,$id, $created_date
        from 		mpm.upload
        where 	bulan in ($bulan_sekarang,$bulan_sebelumnya) and tahun = $tahun_sekarang and id in (
            select max(id)
            from mpm.upload
            where tahun = $tahun_sekarang and bulan in ($bulan_sekarang,$bulan_sebelumnya)
            GROUP BY substring(filename,3,2)
        )and userid not in ('0','289')
        ";

        $proses_upload = $this->db->query($sql_upload);
        // echo "<pre>";
        // print_r($sql_upload);
        // echo "</pre>";

        // $sql = "
        // insert into db_temp.t_temp_monitoring_stock_target (kode,kodeprod,b1,b2,b3,b4,b5,b6,b7,id)
        // select kode, kodeprod, '', '', '', '', '', '', rata,$id
        // from db_temp.t_temp_monitoring_stock_avg 
        // where id = $id
        // ";
        // // $proses = $this->db->query($sql);
        // echo "<pre>";
        // print_r($sql);
        // echo "</pre>";

        $sql_report = "
        insert into db_temp.t_temp_monitoring_stock_report
        select 	a.kode, f.branch_name,f.nama_comp, a.kodeprod, h.namaprod, h.group, h.nama_group, h.h_dp,
                h.berat,h.volume,h.isisatuan, a.rata,b.stock_akhir_unit,
				/*c.unit*/ 0 as po_outstanding, d.omzet_unit as sales_berjalan,e.b7,i.sl,
				date(g.lastupload), $id,$created_date,$supp
        from db_temp.t_temp_monitoring_stock_avg a LEFT JOIN
        (
            select 	a.kode, a.kodeprod, a.stock_akhir_unit
            from 	db_temp.t_temp_monitoring_stock_akhir a
            where   a.id = $id and a.created_date = $created_date and supp = $supp
        )b on a.kode = b.kode and a.kodeprod = b.kodeprod LEFT JOIN
        (
            select a.kode, a.kodeprod, sum(a.unit) as unit
            from db_temp.t_temp_monitoring_stock_po_blank a
            where a.kode is not null and a.id = $id and a.created_date = $created_date and supp =$supp
            group by kode,kodeprod
        )c on a.kode = c.kode and a.kodeprod = c.kodeprod LEFT JOIN
        (
            select a.kode,a.kodeprod,a.omzet_unit
            from db_temp.t_temp_monitoring_stock_sales_berjalan a
            where   a.id = $id and a.created_date = $created_date and supp = $supp
        )d on a.kode = d.kode and a.kodeprod = d.kodeprod LEFT JOIN
        (
            select a.kode, a.kodeprod, a.b7
            from db_temp.t_temp_monitoring_stock_target a
        )e on a.kode = e.kode and a.kodeprod = e.kodeprod left join
        (
            select concat(a.kode_comp,a.nocab) as kode, a.branch_name,a.nama_comp,a.kode_comp, a.nocab, a.urutan
            from mpm.tbl_tabcomp a
            where a.`status` = 1 
            GROUP BY kode
        )f on a.kode = f.kode LEFT JOIN
        (
            select a.nocab,lastupload,b.kode
            from
            (
                select a.nocab,a.lastupload
                from db_temp.t_temp_monitoring_stock_upload a
                GROUP BY nocab
                ORDER BY nocab
            )a inner join 
            (
                select a.kode, nocab, a.branch_name, a.nama_comp
                FROM
                (
                    select concat(a.kode_comp, a.nocab) as kode, a.branch_name, a.nama_comp, a.nocab
                    from mpm.tbl_tabcomp a
                    where a.`status` = 1 and concat(a.kode_comp, a.nocab) in ($kode_comp)
                    GROUP BY concat(a.kode_comp, a.nocab)
                )a INNER JOIN
                (
                    SELECT concat(a.kode_comp,a.nocab) as kode
                    from db_dp.t_dp a
                    where tahun = $tahun_sekarang
                )b on a.kode = b.kode
                ORDER BY nocab
            )b on a.nocab = b.nocab
            ORDER BY nocab
        )g on a.kode = g.kode LEFT JOIN (
            select a.kodeprod, a.namaprod, a.berat, a.volume, a.ISISATUAN,a.grup as `group`,b.nama_group,c.h_dp
            from mpm.tabprod a LEFT JOIN 
            (
                select a.kode_group, a.nama_group
                from mpm.tbl_group a
            )b on a.grup = b.kode_group LEFT JOIN 
            (
                select a.kodeprod,a.h_dp
                from mpm.prod_detail a
                where a.tgl = (
                    select max(b.tgl)
                    from mpm.prod_detail b
                    where a.kodeprod = b.kodeprod
                )
            )c on a.kodeprod = c.kodeprod
            where supp = $supp
            ORDER BY kodeprod
        )h on a.kodeprod = h.kodeprod left join
        (
            select a.kode, a.sl
            from db_inventory.t_monstock_stock_level a
            ORDER BY kode
        )i on a.kode = i.kode
        where   a.id = $id and a.created_date = $created_date
        order by urutan,kodeprod
        ";

        $proses_report = $this->db->query($sql_report);
        // echo "<pre>";
        // print_r($sql_report);
        // echo "</pre>";

        $sql = "
            update db_temp.t_temp_monitoring_stock_report a
            set a.target = a.rata
            where a.id = $id and a.created_date = $created_date
        ";
        $proses = $this->db->query($sql);
        // echo "<pre>";
        // print_r($sql);
        // echo "</pre>";

        $sql = "
            update db_temp.t_temp_monitoring_stock_report a
            set a.po_outstanding_unit = 0
            where a.po_outstanding_unit is null and a.id = $id and a.created_date = $created_date
        ";

        $proses = $this->db->query($sql);
        // echo "<pre>";
        // print_r($sql);
        // echo "</pre>";

        // $sql = "
        //     update db_temp.t_temp_monitoring_stock_report a
        //     set a.sales_berjalan = 0
        //     where a.sales_berjalan is null and a.id = $id and a.created_date = $created_date
        // ";

        $proses = $this->db->query($sql);
        // echo "<pre>";
        // print_r($sql);
        // echo "</pre>";

        $sql = "
            update db_temp.t_temp_monitoring_stock_report a
            set a.stock_akhir_unit = 0
            where a.stock_akhir_unit is null and a.id = $id and a.created_date = $created_date
        ";

        $proses = $this->db->query($sql);
        // echo "<pre>";
        // print_r($sql);
        // echo "</pre>";

        $sql = "
        insert into db_temp.t_temp_monitoring_stock
        select 	a.kode,a.branch_name,a.nama_comp,a.nama_group,a.kodeprod,a.namaprod,a.isisatuan as isi_per_karton,
                a.berat,a.h_dp as harga,a.rata as average,a.stock_akhir_unit as stock_on_hand,a.po_outstanding_unit as git,
				(a.stock_akhir_unit + a.po_outstanding_unit) as total_stock, a.target as est_sales,
				(a.stock_akhir_unit + a.po_outstanding_unit) - a.target as est_saldo_berjalan,
				a.sl as stock_level, round(a.rata / 30 * a.sl) as stock_level_unit,
				round(if(
                    (a.rata / 30 * a.sl) - ((a.stock_akhir_unit + a.po_outstanding_unit) - a.target) < 0, 0, 
                    (a.rata / 30 * a.sl) - ((a.stock_akhir_unit + a.po_outstanding_unit) - a.target)
				)) as purchase_plan,
			    round((a.stock_akhir_unit + a.po_outstanding_unit) + 
				(if((a.rata / 30 * a.sl) - ((a.stock_akhir_unit + a.po_outstanding_unit) - a.target) < 0, 0, (a.rata / 30 * a.sl) - ((a.stock_akhir_unit + a.po_outstanding_unit) - a.target))) - a.target) as est_saldo_akhir,
			    round(((a.stock_akhir_unit + a.po_outstanding_unit) + 
				(if((a.rata / 30 * a.sl) - ((a.stock_akhir_unit + a.po_outstanding_unit) - a.target) < 0, 0, (a.rata / 30 * a.sl) - ((a.stock_akhir_unit + a.po_outstanding_unit) - a.target))) - a.target) / a.rata * 30) as est_doi_akhir,
				round(if(
                    (a.rata / 30 * a.sl) - ((a.stock_akhir_unit + a.po_outstanding_unit) - a.target) < 0, 0, 
                    (a.rata / 30 * a.sl) - ((a.stock_akhir_unit + a.po_outstanding_unit) - a.target)
				) / a.isisatuan) as pp_in_karton,
			    round((if(
                    (a.rata / 30 * a.sl) - ((a.stock_akhir_unit + a.po_outstanding_unit) - a.target) < 0, 0, 
                    (a.rata / 30 * a.sl) - ((a.stock_akhir_unit + a.po_outstanding_unit) - a.target)
                ) / a.isisatuan) * a.berat) as pp_in_kg,
			    round(if(
                    (a.rata / 30 * a.sl) - ((a.stock_akhir_unit + a.po_outstanding_unit) - a.target) < 0, 0, 
                    (a.rata / 30 * a.sl) - ((a.stock_akhir_unit + a.po_outstanding_unit) - a.target)
                )) * a.h_dp as pp_in_value,a.lastupload,$id,$created_date,$supp
                /*
				a.sales_berjalan,
				(a.target - a.sales_berjalan) as potensi_sales,
				(a.stock_akhir_unit + a.po_outstanding_unit) - (a.target - a.sales_berjalan) as stock_berjalan,
				((a.stock_akhir_unit + a.po_outstanding_unit) - (a.target - a.sales_berjalan)) / a.rata * 30 as doi,
				a.sl as stock_ideal_hari, (a.rata/30)*a.sl as stock_ideal_unit,
				if(
						((a.rata/30)*a.sl) - (a.stock_akhir_unit + a.po_outstanding_unit) - (a.target - a.sales_berjalan) < 0, 0, 
						((a.rata/30)*a.sl) - (a.stock_akhir_unit + a.po_outstanding_unit) - (a.target - a.sales_berjalan)
					) as suggest_po,
					if(
						((a.rata/30)*a.sl) - (a.stock_akhir_unit + a.po_outstanding_unit) - (a.target - a.sales_berjalan) < 0, 0, 
						((a.rata/30)*a.sl) - (a.stock_akhir_unit + a.po_outstanding_unit) - (a.target - a.sales_berjalan) + 
						(a.stock_akhir_unit + a.po_outstanding_unit) - (a.target - a.sales_berjalan)
					) as estimasi_stock_akhir*/
				
        from 	db_temp.t_temp_monitoring_stock_report a     
        where   a.id = $id and a.created_date = $created_date                            
        ";
        $prosesx = $this->db->query($sql);
        // echo "<pre>";
        // print_r($sql);
        // echo "</pre>";

        echo "<script>alert('generate purchase plan success'); window.location = 'monitoring_stock_us';</script>";
    }

    public function clear()
    {

        $this->db->query("truncate db_temp.t_temp_monitoring_stock_avg");
        $this->db->query("truncate db_temp.t_temp_monitoring_stock_akhir");
        $this->db->query("truncate db_temp.t_temp_monitoring_stock_po");
        $this->db->query("truncate db_temp.t_temp_monitoring_stock_do");
        $this->db->query("truncate db_temp.t_temp_monitoring_stock_po_blank");
        $this->db->query("truncate db_temp.t_temp_monitoring_stock_sales_berjalan");
        $this->db->query("truncate db_temp.t_temp_monitoring_stock_upload");
        $this->db->query("truncate db_temp.t_temp_monitoring_stock_target");
        $this->db->query("truncate db_temp.t_temp_monitoring_stock_report");
        $this->db->query("truncate db_temp.t_temp_monitoring_stock");

        echo "<script>alert('Clear data success'); window.location = 'monitoring_stock_us';</script>";
    }

    public function get_est_sales($kode, $kodeprod, $supp)
    {
        $sql = "
            select a.*
            from db_temp.t_temp_monitoring_stock a
            where a.created_date =  (select max(created_date) from db_temp.t_temp_monitoring_stock) and a.supp = $supp and a.kode = '$kode' and a.kodeprod = $kodeprod
        ";
        // echo "<pre><br><br><br><br><br><br>";
        // print_r($sql);
        // echo "</pre>";

        $proses_tampil = $this->db->query($sql)->result();
        if ($proses_tampil) {
            return $proses_tampil;
        } else {
            return array();
        }
    }

    public function monitoring_stock_us_proses_regenerate($supp)
    {
        $sql = "
            select max(created_date) as created_date
            from db_temp.t_temp_monitoring_stock a
            where a.supp = $supp
        ";
        $proses = $this->db->query($sql)->result();
        foreach ($proses as $key) {
            $created_date = $key->created_date;
        }

        $sql = "update db_temp.t_temp_monitoring_stock a
        set a.total_stock = a.stock_on_hand + a.git
        where a.created_date = '$created_date' and a.supp=$supp 
        ";
        $proses = $this->db->query($sql);
        // echo "<pre>";
        // print_r($sql);
        // echo "</pre>";

        $sql = "update db_temp.t_temp_monitoring_stock a
        set a.est_saldo_berjalan = a.total_stock - a.est_sales
        where a.created_date = '$created_date' and a.supp=$supp 
        ";
        $proses = $this->db->query($sql);
        // echo "<pre>";
        // print_r($sql);
        // echo "</pre>";

        $sql = "update db_temp.t_temp_monitoring_stock a
        set a.stock_level_unit = round(a.average / 30 * a.stock_level)
        where a.created_date = '$created_date' and a.supp=$supp 
        ";
        $proses = $this->db->query($sql);
        // echo "<pre>";
        // print_r($sql);
        // echo "</pre>";

        $sql = "update db_temp.t_temp_monitoring_stock a
        set a.purchase_plan = IF(a.stock_level_unit - a.est_saldo_berjalan < 0, 0, a.stock_level_unit - a.est_saldo_berjalan)
        where a.created_date = '$created_date' and a.supp=$supp 
        ";
        $proses = $this->db->query($sql);
        // echo "<pre>";
        // print_r($sql);
        // echo "</pre>";

        $sql = "update db_temp.t_temp_monitoring_stock a
        set a.est_saldo_akhir = a.total_stock + a.purchase_plan - a.est_sales        
        where a.created_date = '$created_date' and a.supp=$supp 
        ";
        $proses = $this->db->query($sql);
        // echo "<pre>";
        // print_r($sql);
        // echo "</pre>";

        $sql = "update db_temp.t_temp_monitoring_stock a
        set a.est_doi_akhir = round(a.est_saldo_akhir / a.average * 30)
        where a.created_date = '$created_date' and a.supp=$supp 
        ";
        $proses = $this->db->query($sql);
        // echo "<pre>";
        // print_r($sql);
        // echo "</pre>";

        $sql = "update db_temp.t_temp_monitoring_stock a
        set a.pp_in_karton = round(a.purchase_plan / a.isi_per_karton)
        where a.created_date = '$created_date' and a.supp=$supp 
        ";
        $proses = $this->db->query($sql);
        // echo "<pre>";
        // print_r($sql);
        // echo "</pre>";

        $sql = "update db_temp.t_temp_monitoring_stock a
        set a.pp_in_kg = round(a.pp_in_karton * a.berat)
        where a.created_date = '$created_date' and a.supp=$supp 
        ";
        $proses = $this->db->query($sql);
        // echo "<pre>";
        // print_r($sql);
        // echo "</pre>";

        $sql = "update db_temp.t_temp_monitoring_stock a
        set a.pp_in_value = a.purchase_plan * a.harga
        where a.created_date = '$created_date' and a.supp=$supp 
        ";
        $proses = $this->db->query($sql);

        // echo "<pre>";
        // print_r($sql);
        // echo "</pre>";

        // if ($proses) {
        //     return $proses;
        // }else{
        //     return array();
        // }
        echo "<script>alert('re-generate purchase plan success'); window.location = '../monitoring_stock_us';</script>";
    }

    public function update_monitoring_stock_proses($data)
    {

        $kode = $data['kode'];
        $kodeprod = $data['kodeprod'];
        $est_sales = $data['est_sales'];
        $git = $data['git'];
        $stock_level = $data['stock_level'];
        $supp = $data['supp'];
        $created_date = $data['created_date'];

        $sql = "
            update db_temp.t_temp_monitoring_stock a
            set a.est_sales = '$est_sales', a.git = '$git', a.stock_level = $stock_level
            where a.created_date =  '$created_date' and a.supp = $supp and a.kode = '$kode' and a.kodeprod = $kodeprod
        ";

        // echo "<pre>";
        // print_r($sql);
        // echo "</pre>";

        $this->db->query($sql);
        // if ($proses_tampil) {
        //     return $proses_tampil;
        // }else{
        //     return array();
        // }
        echo "<script>alert('update success'); window.location = 'monitoring_stock_us';</script>";
    }

    public function get_kode($id)
    {
        $sql = "
            select  a.kode
            from    mpm.t_pp a
            where   a.userid = $id and a.`status` = 1
        ";
        $proses = $this->db->query($sql)->result();
        return $proses;
    }

    public function get_po($kode_alamat)
    {
        // $sql = 
        $id = $this->session->userdata('id');
        // echo "id : ".$id;

        // echo $kode_alamat;
        $sql = "
        select a.id, a.nopo, date(a.tglpo) as tglpo, a.company, a.kode_alamat, b.branch_name, b.nama_comp, a.supp, c.namasupp, a.tipe, d.x, e.y
        from mpm.po a LEFT JOIN
        (
            select concat(a.kode_comp,a.nocab) as kode, a.branch_name, a.nama_comp
            from mpm.tbl_tabcomp a
            where a.`status` = 1
            GROUP BY concat(a.kode_comp,a.nocab) 
        )b on a.kode_alamat = b.kode left join (
            select a.supp,a.namasupp
            from mpm.tabsupp a
        )c on a.supp = c.supp LEFT JOIN 
        (
            select a.id, a.id_ref,count(*) as x
            from mpm.po_detail a
            where a.deleted = 0 
            GROUP BY a.id_ref
        )d on a.id = d.id_ref LEFT JOIN
        (
            select a.id, a.id_ref,count(*) as y
            from mpm.po_detail a
            where a.deleted = 0 and (a.status_terima is not null and a.tanggal_terima <> '0000-00-00')
            GROUP BY a.id_ref
        )e on a.id = e.id_ref
        where a.deleted = 0 and a.kode_alamat in ($kode_alamat) and
        ((
            year(a.tglpesan) in (year(date(now()))) and month(a.tglpesan) in (month(date(now())))
        ) or
        (
            year(a.tglpesan) in (date_format(date(now()) - INTERVAL '1' MONTH,'%Y')) and 
            month(a.tglpesan) in (date_format(date(now()) - INTERVAL '1' MONTH,'%m'))
        ) or
        (
            year(a.tglpesan) in (date_format(date(now()) - INTERVAL '2' MONTH,'%Y')) and 
            month(a.tglpesan) in (date_format(date(now()) - INTERVAL '2' MONTH,'%m'))
        ) or
        (
            year(a.tglpesan) in (date_format(date(now()) - INTERVAL '3' MONTH,'%Y')) and 
            month(a.tglpesan) in (date_format(date(now()) - INTERVAL '3' MONTH,'%m'))
        )or
        (
            year(a.tglpesan) in (date_format(date(now()) - INTERVAL '4' MONTH,'%Y')) and 
            month(a.tglpesan) in (date_format(date(now()) - INTERVAL '4' MONTH,'%m'))
        )) 
        order by a.id desc
        ";
        // echo "<pre><br><br><br><br>";
        // print_r($sql);
        // echo "</pre>";
        $proses = $this->db->query($sql)->result();
        if ($proses) {
            return $proses;
        } else {
            return array();
        }
    }

    public function get_kode_alamat()
    {
        $id = $this->session->userdata('id');

        if ($this->session->userdata('level') == 10) { // untuk user RM AM punya level = 10
            $params = "where b.kode_alamat is not null";
        }elseif ($this->session->userdata('username') == 'maserih' || $this->session->userdata('username') == 'rizki' || $this->session->userdata('username') == 'marketing') { // untuk user RM AM punya level = 10
            $params = "where b.kode_alamat is not null and a.username like 'MPI%'";
        }else{
            $params = "where a.id = $id";
        }

        $sql = "
            select b.kode_alamat
            from mpm.user a INNER JOIN mpm.t_alamat b
                on a.username = b.username
            $params
        ";

        $proses = $this->db->query($sql)->result();
        
        if ($proses) {
            return $proses;
        } else {
            return array();
        }
    }

    public function get_do($supp, $id)
    {
        $get_nopo = $this->db->query("select nopo from mpm.po a where a.id = $id ")->result();
        foreach ($get_nopo as $key) {
            $nopo = $key->nopo;
        }
        if ($supp == '001') {
            // $sql = "
            // select a.id, a.id_ref, a.kodeprod, c.namaprod,a.banyak, a.banyak_karton, c.kodeprod_deltomed, d.nodo, d.tgldo, d.qty, ceil(d.qty / c.isisatuan) as qty_karton, a.status_terima, a.tanggal_terima
            // from mpm.po_detail a LEFT JOIN 
            // (
            //     select a.id,a.nopo
            //     from mpm.po a
            //     where a.id = $id
            // )b on a.id_ref = b.id LEFT JOIN 
            // (
            //     select a.kodeprod, a.namaprod,a.kodeprod_deltomed, a.isisatuan
            //     from mpm.tabprod a
            //     where a.supp = 001
            // )c on a.kodeprod = c.kodeprod LEFT JOIN
            // (
            //     select a.nodo, a.kodedp, a.company, a.kodeprod_delto, a.namaprod, a.qty, a.nopo, str_to_date(a.tgldo,'%d/%m/%Y') as tgldo
            //     from db_po.t_do_deltomed a
            // )d on c.kodeprod_deltomed = d.kodeprod_delto and b.nopo = d.nopo
            // where a.id_ref = $id and a.deleted = 0
            // ";



            $sql = "
            select a.nodo, b.kodeprod, b.namaprod, a.qty, str_to_date(a.tgldo,'%d/%m/%Y') as tgldo, c.status_terima, c.tanggal_terima
            from db_po.t_do_deltomed a LEFT JOIN
            (
                select a.kodeprod, a.kodeprod_deltomed, a.namaprod
                from mpm.tabprod a
                where a.supp = $supp
            )b on a.kodeprod_delto = b.kodeprod_deltomed LEFT JOIN 
            (
                select a.id, a.kodeprod, a.status_terima, a.tanggal_terima
                from mpm.po_detail a
                where a.id_ref = $id 
            )c on b.kodeprod = c.kodeprod
            where a.nopo = '$nopo'
            ";

            // echo "<pre><br><br><br><br><br><br>";
            // print_r($sql);
            // echo "/pre>";

            $proses = $this->db->query($sql)->result();
            if ($proses) {
                return $proses;
            } else {
                return array();
            }
        } elseif ($supp == '005') {

            $sql = "
            select a.nodo, a.tgldo, a.kodeprod, b.namaprod, a.qty, c.status_terima, c.tanggal_terima
            from
            (
                select 	a.nodo, a.tgldo, a.kodeprod, a.nopo, 
                        if(b.satuan_box is null, sum(a.banyak), sum(a.banyak) * b.satuan_box) as qty 
                from 	db_po.t_do_us a LEFT JOIN
                (
                        select a.kodeprod, a.satuan_box, a.`status`
                        from db_produk.t_product_po a
                        where a.`status` = 1
                )b on a.kodeprod = b.kodeprod
                where a.nopo = '$nopo'
                group by a.nodo, a.kodeprod
            )a LEFT JOIN 
            (
                select a.kodeprod, a.namaprod
                from mpm.tabprod a
                where supp = 005
            )b on a.kodeprod = b.kodeprod LEFT JOIN 
            (
                select a.id, a.kodeprod, a.status_terima, a.tanggal_terima
                from mpm.po_detail a
                where a.id_ref = $id 
            )c on b.kodeprod = c.kodeprod
            ";

            // echo "<pre><br><br><br><br>";
            // print_r($sql);
            // echo "</pre>";
            $proses = $this->db->query($sql)->result();
            if ($proses) {
                return $proses;
            } else {
                return array();
            }
        } elseif ($supp == '012') {
            $sql = "
            select a.id_po, a.kodeprod, b.namaprod, a.tanggal_kirim as tgldo, a.jumlah_karton, b.isisatuan, c.banyak, (a.jumlah_karton*b.ISISATUAN) as qty, c.status_terima, c.tanggal_terima, '' as nodo
            from mpm.t_asn a left join 
            (
                select a.kodeprod, a.namaprod, a.ISISATUAN
                from mpm.tabprod a
                where supp = 012
            )b on a.kodeprod = b.kodeprod LEFT JOIN 
            (
                select a.id, a.kodeprod, a.status_terima, a.tanggal_terima, a.banyak, a.banyak_karton
                from mpm.po_detail a
                where a.id_ref = $id
            )c on b.kodeprod = c.kodeprod
            where a.id_po = $id
            ";
            $proses = $this->db->query($sql)->result();
            if ($proses) {
                return $proses;
            } else {
                return array();
            }
        } else {
            return array();
        }
    }

    public function konfirmasi_po_detail_proses($data)
    {

        date_default_timezone_set('Asia/Jakarta');
        $date = date_create(date('Y-m-d H:i:s'));
        date_add($date, date_interval_create_from_date_string('-7 hours'));
        $created_date = '"' . date_format($date, 'Y-m-d H:i:s') . '"';

        $id_po = $data['id_po'];
        $supp = $data['supp'];
        $tanggal_terima = $data['tanggal_terima'];
        // $id_detail = $data['id_detail'];
        $btn_status = $data['btn_status'];
        $kodeprod = $data['kodeprod'];
        // echo "kodeprod : ".$kodeprod;

        // $sql = "
        //     update mpm.po_detail a
        //     set a.status_terima = $btn_status, a.tanggal_terima = '$tanggal_terima', tanggal_terima_created_date = $created_date
        //     where a.id in ($id_detail)
        // ";
        // $proses = $this->db->query($sql);

        $sql = "
            update mpm.po_detail a
            set a.status_terima = $btn_status, a.tanggal_terima = '$tanggal_terima', tanggal_terima_created_date = $created_date
            where a.kodeprod in ($kodeprod) and a.id_ref = $id_po
        ";
        $proses = $this->db->query($sql);

        // echo "<pre>";
        // print_r($sql);
        // echo "</pre>";

        if ($proses) {
            echo "<script>alert('berhasil merubah data');document.location='konfirmasi_po_detail/$supp/$id_po'</script>";
        }
    }

    public function po_outstanding_deltomed($data)
    {
        date_default_timezone_set('Asia/Jakarta');
        $date = date_create(date('Y-m-d H:i:s'));
        date_add($date, date_interval_create_from_date_string('-7 hours'));
        $tgl = date_format($date, 'Y-m-d H:i:s');

        $id = $this->session->userdata('id');
        $periode1 = $data['periode_1'];
        $periode2 = $data['periode_2'];
        $kode_alamat = $data['kode_alamat'];

        // echo "<pre>";
        // echo $kode_alamat;
        // echo "</pre>";
        // die;

        if (strlen($kode_alamat) <= 4) {
            $kode_alamat_params = "";
        } else {
            $kode_alamat_params = "a.kode_alamat in ($kode_alamat) and";
        }

        $cek_traffic = $this->db->query("select * from db_temp.t_traffic where id_menu = 77")->result();
        foreach ($cek_traffic as $key) {
            $hasil_traffic = $key->status;
        }

        if ($hasil_traffic == 1) {
            $message = "Sedang ada update data terbaru di menu ini. Silahkan coba beberapa saat lagi (Sekitar 30 menit). Jika melebihi waktu tersebut, silahkan konfirmasi ke IT (suffy atau ilham)";
            echo "<script type='text/javascript'>alert('$message');
            window.location.href = 'po_outstanding';
            </script>";
        } else {

            $insert_print = "
            insert into db_po.t_po_outstanding_deltomed_print
            select  a.branch_name,a.nama_comp,a.company,a.tglpo,month(a.tglpo) as bulan_po,a.nopo,a.tipe,a.kodeprod,a.namaprod,a.qty_po,
                    a.qty_pemenuhan,a.harga, a.value_po,a.value_pemenuhan,a.berat, a.volume, a.tgldo,a.nodo,a.fulfilment,
                    a.leadtime_proses_do, a.po_ref,a.tanggal_terima,a.leadtime_proses_kirim,a.outstanding_po,a.kode_alamat,
                    '$tgl', $id
            from    db_po.t_po_outstanding_deltomed a
            where   $kode_alamat_params (date(a.tglpo) between '$periode1' and '$periode2')";
            $this->db->query($insert_print);

            $sql = "
            select * 
            from    db_po.t_po_outstanding_deltomed a
            where   $kode_alamat_params (date(a.tglpo) between '$periode1' and '$periode2')";

            // echo "<pre><br><br><br>";
            // print_r($insert_print);
            // print_r($sql);
            // echo "</pre>";

            $proses = $this->db->query($sql)->result();

            if ($proses) {
                return $proses;
            } else {
                return array();
            }
        }
    }

    public function po_outstanding_us($data)
    {
        // date_default_timezone_set('Asia/Jakarta'); 
        // $date = date_create(date('Y-m-d H:i:s'));
        // date_add($date, date_interval_create_from_date_string('-7 hours'));
        // $tgl = date_format($date, 'Y-m-d H:i:s'); 

        $id = $this->session->userdata('id');
        $periode1 = $data['periode_1'];
        $periode2 = $data['periode_2'];
        $kode_alamat = $data['kode_alamat'];
        $tgl = $data['created_date'];

        if (strlen($kode_alamat) <= 4) {
            $kode_alamat_params = "";
        } else {
            $kode_alamat_params = "a.kode_alamat in ($kode_alamat) and";
        }

        $cek_traffic = $this->db->query("select * from db_temp.t_traffic where id_menu = 77")->result();
        foreach ($cek_traffic as $key) {
            $hasil_traffic = $key->status;
        }

        if ($hasil_traffic == 1) {
            $message = "Sedang ada maintenance di menu ini. Silahkan coba beberapa saat lagi atau hubungi IT !";
            echo "<script type='text/javascript'>alert('$message');
            window.location.href = 'po_outstanding';
            </script>";
        } else {

            $insert_print = "
            insert into db_po.t_po_outstanding_us_print
            select  a.company,a.branch_name, a.nama_comp, a.tglpo,month(a.tglpo) as bulan_po, a.nopo, a.tipe, a.kodeprod, a.namaprod, a.qty_po, 
                    a.berat,a.volume,
                    a.qty_pemenuhan,a.tgldo,a.nodo, a.fulfilment,a.leadtime_proses_do,a.tanggal_terima,
                    a.leadtime_proses_kirim,a.outstanding_po,a.kode_alamat,
                    $tgl, $id
            from    db_po.t_po_outstanding_us a
            where   $kode_alamat_params (date(a.tglpo) between '$periode1' and '$periode2')";
            $this->db->query($insert_print);

            // echo "<pre><br><br><br><br>";
            // print_r($insert_print);
            // echo "</pre>";

            $sql = "
            select * from db_po.t_po_outstanding_us a
            where $kode_alamat_params (date(a.tglpo) between '$periode1' and '$periode2')";

            // echo "<pre><br><br><br><br><br><br>";
            // print_r($sql);
            // echo "</pre>";

            $proses = $this->db->query($sql)->result();
            if ($proses) {
                return $proses;
            } else {
                return array();
            }
        }
    }

    public function po_outstanding_intrafood($data)
    {
        // date_default_timezone_set('Asia/Jakarta'); 
        // $date = date_create(date('Y-m-d H:i:s'));
        // date_add($date, date_interval_create_from_date_string('-7 hours'));
        // $tgl = date_format($date, 'Y-m-d H:i:s'); 

        $tgl = $data['created_date'];
        $id = $this->session->userdata('id');
        $periode1 = $data['periode_1'];
        $periode2 = $data['periode_2'];
        $kode_alamat = $data['kode_alamat'];

        if (strlen($kode_alamat) <= 4) {
            $kode_alamat_params = "";
        } else {
            $kode_alamat_params = "a.kode_alamat in ($kode_alamat) and";
        }

        $cek_traffic = $this->db->query("select * from db_temp.t_traffic where id_menu = 77")->result();
        foreach ($cek_traffic as $key) {
            $hasil_traffic = $key->status;
        }

        if ($hasil_traffic == 1) {
            $message = "Sedang ada maintenance di menu ini. Silahkan coba beberapa saat lagi atau hubungi IT !";
            echo "<script type='text/javascript'>alert('$message');
            window.location.href = 'po_outstanding';
            </script>";
        } else {

            // $insert_print = "
            // insert into db_po.t_po_outstanding_intrafood_print
            // select  a.id, a.branch_name, a.nama_comp, a.tglpo, a.nopo, a.tipe, a.kodeprod, a.namaprod, 
            //         a.qty_po, a.qty_pemenuhan, a.tanggal_kirim,a.est_tanggal_tiba,a.fulfilment,
            //         a.leadtime_proses_do, a.status_closed, a.color, a.tanggal_terima, 
            //         a.leadtime_proses_kirim,a.outstanding_po, a.kode_alamat, 
            //         '$tgl', $id
            // from    db_po.t_po_outstanding_intrafood a
            // where   $kode_alamat_params (date(a.tglpo) between '$periode1' and '$periode2')";
            // $this->db->query($insert_print);


            $insert_print = "
            insert into db_po.t_po_outstanding_intrafood_print
            select 	a.id, a.branch_name, a.nama_comp, a.company, a.tglpo,month(a.tglpo) as bulan_po, a.nopo, a.tipe, a.kodeprod, a.namaprod,
                    a.qty_po_unit, a.qty_po_karton, a.qty_pemenuhan_unit, a.qty_pemenuhan_karton,
                    a.tanggal_kirim, a.est_tanggal_tiba, a.fulfilment_unit, a.fulfilment_karton,
                    a.leadtime_proses_do,a.status_closed,a.tanggal_terima,a.leadtime_proses_kirim,
                    a.outstanding_po_unit,a.outstanding_po_karton,$tgl, $id
            from db_po.t_po_outstanding_intrafood a
            where $kode_alamat_params (date(a.tglpo) between '$periode1' and '$periode2')
            ";

            $this->db->query($insert_print);

            // echo "<pre><br><br><br>";
            // print_r($insert_print);
            // echo "</pre>";

            $sql = "
            select * from db_po.t_po_outstanding_intrafood a
            where $kode_alamat_params (date(a.tglpo) between '$periode1' and '$periode2')";

            $proses = $this->db->query($sql)->result();
            if ($proses) {
                return $proses;
            } else {
                return array();
            }
        }
    }

    public function po_outstanding_marguna($data)
    {
        // date_default_timezone_set('Asia/Jakarta'); 
        // $date = date_create(date('Y-m-d H:i:s'));
        // date_add($date, date_interval_create_from_date_string('-7 hours'));
        // $tgl = date_format($date, 'Y-m-d H:i:s'); 
        $tgl = $data['created_date'];
        $id = $this->session->userdata('id');
        $periode1 = $data['periode_1'];
        $periode2 = $data['periode_2'];
        $kode_alamat = $data['kode_alamat'];

        if (strlen($kode_alamat) <= 4) {
            $kode_alamat_params = "";
        } else {
            $kode_alamat_params = "a.kode_alamat in ($kode_alamat) and";
        }

        $cek_traffic = $this->db->query("select * from db_temp.t_traffic where id_menu = 77")->result();
        foreach ($cek_traffic as $key) {
            $hasil_traffic = $key->status;
        }

        if ($hasil_traffic == 1) {
            $message = "Sedang ada maintenance di menu ini. Silahkan coba beberapa saat lagi atau hubungi IT !";
            echo "<script type='text/javascript'>alert('$message');
            window.location.href = 'po_outstanding';
            </script>";
        } else {

            // $insert_print = "
            // insert into db_po.t_po_outstanding_marguna_print
            // select  a.id, a.branch_name, a.nama_comp, a.company, a.tglpo, a.nopo, a.tipe, a.kodeprod, a.namaprod, 
            //         a.qty_po, a.kode_alamat, 
            //         '$tgl', $id
            // from    db_po.t_po_outstanding_marguna a
            // where   $kode_alamat_params (date(a.tglpo) between '$periode1' and '$periode2')";

            $insert_print = "
            insert into db_po.t_po_outstanding_marguna_print
            select 	a.id, a.branch_name, a.nama_comp, a.company, a.tglpo,month(a.tglpo) as bulan_po, a.nopo, a.tipe, a.kodeprod, a.namaprod,
                    a.qty_po_unit, a.qty_po_karton, a.qty_pemenuhan_unit, a.qty_pemenuhan_karton,
                    a.tanggal_kirim, a.est_tanggal_tiba, a.fulfilment_unit, a.fulfilment_karton,
                    a.leadtime_proses_do,a.status_closed,a.tanggal_terima,a.leadtime_proses_kirim,
                    a.outstanding_po_unit,a.outstanding_po_karton,$tgl, $id
            from db_po.t_po_outstanding_marguna a
            where $kode_alamat_params (date(a.tglpo) between '$periode1' and '$periode2')
            ";
            $this->db->query($insert_print);

            // echo "<pre><br><br><br>";
            // print_r($insert_print);
            // echo "</pre>";

            $sql = "
            select * from db_po.t_po_outstanding_marguna a
            where $kode_alamat_params (date(a.tglpo) between '$periode1' and '$periode2')";

            $proses = $this->db->query($sql)->result();
            if ($proses) {
                return $proses;
            } else {
                return array();
            }
        }
    }

    public function po_outstanding_jaya($data)
    {
        date_default_timezone_set('Asia/Jakarta');
        $date = date_create(date('Y-m-d H:i:s'));
        date_add($date, date_interval_create_from_date_string('-7 hours'));
        $tgl = date_format($date, 'Y-m-d H:i:s');

        $id = $this->session->userdata('id');
        $periode1 = $data['periode_1'];
        $periode2 = $data['periode_2'];
        $kode_alamat = $data['kode_alamat'];

        if (strlen($kode_alamat) <= 4) {
            $kode_alamat_params = "";
        } else {
            $kode_alamat_params = "a.kode_alamat in ($kode_alamat) and";
        }

        $cek_traffic = $this->db->query("select * from db_temp.t_traffic where id_menu = 77")->result();
        foreach ($cek_traffic as $key) {
            $hasil_traffic = $key->status;
        }

        if ($hasil_traffic == 1) {
            $message = "Sedang ada maintenance di menu ini. Silahkan coba beberapa saat lagi atau hubungi IT !";
            echo "<script type='text/javascript'>alert('$message');
            window.location.href = 'po_outstanding';
            </script>";
        } else {

            $insert_print = "
            insert into db_po.t_po_outstanding_jaya_print
            select  a.id, a.branch_name, a.nama_comp, a.company, a.tglpo,month(a.tglpo) as bulan_po, a.nopo, a.tipe, a.kodeprod, a.namaprod, 
                    a.qty_po, a.kode_alamat, 
                    '$tgl', $id
            from    db_po.t_po_outstanding_jaya a
            where   $kode_alamat_params (date(a.tglpo) between '$periode1' and '$periode2')";
            $this->db->query($insert_print);

            // echo "<pre><br><br><br>";
            // print_r($insert_print);
            // echo "</pre>";

            $sql = "
            select * from db_po.t_po_outstanding_jaya a
            where $kode_alamat_params (date(a.tglpo) between '$periode1' and '$periode2')";

            $proses = $this->db->query($sql)->result();
            if ($proses) {
                return $proses;
            } else {
                return array();
            }
        }
    }

    public function po_outstanding_strive($data)
    {
        date_default_timezone_set('Asia/Jakarta');
        $date = date_create(date('Y-m-d H:i:s'));
        date_add($date, date_interval_create_from_date_string('-7 hours'));
        $tgl = date_format($date, 'Y-m-d H:i:s');

        $id = $this->session->userdata('id');
        $periode1 = $data['periode_1'];
        $periode2 = $data['periode_2'];
        $kode_alamat = $data['kode_alamat'];

        if (strlen($kode_alamat) <= 4) {
            $kode_alamat_params = "";
        } else {
            $kode_alamat_params = "a.kode_alamat in ($kode_alamat) and";
        }

        $cek_traffic = $this->db->query("select * from db_temp.t_traffic where id_menu = 77")->result();
        foreach ($cek_traffic as $key) {
            $hasil_traffic = $key->status;
        }

        if ($hasil_traffic == 1) {
            $message = "Sedang ada maintenance di menu ini. Silahkan coba beberapa saat lagi atau hubungi IT !";
            echo "<script type='text/javascript'>alert('$message');
            window.location.href = 'po_outstanding';
            </script>";
        } else {

            $insert_print = "
            insert into db_po.t_po_outstanding_strive_print
            select  a.id, a.branch_name, a.nama_comp, a.company, a.tglpo,month(a.tglpo) as bulan_po, a.nopo, a.tipe, a.kodeprod, a.namaprod, 
                    a.qty_po, a.kode_alamat, 
                    '$tgl', $id
            from    db_po.t_po_outstanding_strive a
            where   $kode_alamat_params (date(a.tglpo) between '$periode1' and '$periode2')";
            $this->db->query($insert_print);

            // echo "<pre><br><br><br>";
            // print_r($insert_print);
            // echo "</pre>";

            $sql = "
            select * from db_po.t_po_outstanding_strive a
            where $kode_alamat_params (date(a.tglpo) between '$periode1' and '$periode2')";

            $proses = $this->db->query($sql)->result();
            if ($proses) {
                return $proses;
            } else {
                return array();
            }
        }
    }

    public function po_outstanding_hni($data)
    {
        date_default_timezone_set('Asia/Jakarta');
        $date = date_create(date('Y-m-d H:i:s'));
        date_add($date, date_interval_create_from_date_string('-7 hours'));
        $tgl = date_format($date, 'Y-m-d H:i:s');

        $id = $this->session->userdata('id');
        $periode1 = $data['periode_1'];
        $periode2 = $data['periode_2'];
        $kode_alamat = $data['kode_alamat'];

        if (strlen($kode_alamat) <= 4) {
            $kode_alamat_params = "";
        } else {
            $kode_alamat_params = "a.kode_alamat in ($kode_alamat) and";
        }

        $cek_traffic = $this->db->query("select * from db_temp.t_traffic where id_menu = 77")->result();
        foreach ($cek_traffic as $key) {
            $hasil_traffic = $key->status;
        }

        if ($hasil_traffic == 1) {
            $message = "Sedang ada maintenance di menu ini. Silahkan coba beberapa saat lagi atau hubungi IT !";
            echo "<script type='text/javascript'>alert('$message');
            window.location.href = 'po_outstanding';
            </script>";
        } else {

            $insert_print = "
            insert into db_po.t_po_outstanding_hni_print
            select  a.id, a.branch_name, a.nama_comp, a.company, a.tglpo,month(a.tglpo) as bulan_po, a.nopo, a.tipe, a.kodeprod, a.namaprod, 
                    a.qty_po, a.kode_alamat, 
                    '$tgl', $id
            from    db_po.t_po_outstanding_hni a
            where   $kode_alamat_params (date(a.tglpo) between '$periode1' and '$periode2')";
            $this->db->query($insert_print);

            // echo "<pre><br><br><br>";
            // print_r($insert_print);
            // echo "</pre>";

            $sql = "
            select * from db_po.t_po_outstanding_hni a
            where $kode_alamat_params (date(a.tglpo) between '$periode1' and '$periode2')";

            $proses = $this->db->query($sql)->result();
            if ($proses) {
                return $proses;
            } else {
                return array();
            }
        }
    }

    public function po_outstanding_mdj($data)
    {
        date_default_timezone_set('Asia/Jakarta');
        $date = date_create(date('Y-m-d H:i:s'));
        date_add($date, date_interval_create_from_date_string('-7 hours'));
        $tgl = date_format($date, 'Y-m-d H:i:s');

        $id = $this->session->userdata('id');
        $periode1 = $data['periode_1'];
        $periode2 = $data['periode_2'];
        $kode_alamat = $data['kode_alamat'];

        if (strlen($kode_alamat) <= 4) {
            $kode_alamat_params = "";
        } else {
            $kode_alamat_params = "a.kode_alamat in ($kode_alamat) and";
        }

        $cek_traffic = $this->db->query("select * from db_temp.t_traffic where id_menu = 77")->result();
        foreach ($cek_traffic as $key) {
            $hasil_traffic = $key->status;
        }

        if ($hasil_traffic == 1) {
            $message = "Sedang ada maintenance di menu ini. Silahkan coba beberapa saat lagi atau hubungi IT !";
            echo "<script type='text/javascript'>alert('$message');
            window.location.href = 'po_outstanding';
            </script>";
        } else {

            $insert_print = "
            insert into db_po.t_po_outstanding_mdj_print
            select  a.id, a.branch_name, a.nama_comp, a.company, a.tglpo,month(a.tglpo) as bulan_po, a.nopo, a.tipe, a.kodeprod, a.namaprod, 
                    a.qty_po, a.kode_alamat, 
                    '$tgl', $id
            from    db_po.t_po_outstanding_mdj a
            where   $kode_alamat_params (date(a.tglpo) between '$periode1' and '$periode2')";
            $this->db->query($insert_print);

            // echo "<pre><br><br><br>";
            // print_r($insert_print);
            // echo "</pre>";

            $sql = "
            select * from db_po.t_po_outstanding_mdj a
            where $kode_alamat_params (date(a.tglpo) between '$periode1' and '$periode2')";

            $proses = $this->db->query($sql)->result();
            if ($proses) {
                return $proses;
            } else {
                return array();
            }
        }
    }

    public function po_outstanding_deltomed_update($data)
    {
        // echo "supp : ".$supp;
        // echo "periode_1 : ".$periode_1;
        // echo "periode_2 : ".$periode_2;
        date_default_timezone_set('Asia/Jakarta');
        $date = date_create(date('Y-m-d H:i:s'));
        date_add($date, date_interval_create_from_date_string('-7 hours'));
        $tgl = date_format($date, 'Y-m-d H:i:s');
        $thn = date('Y');

        $id = $this->session->userdata('id');
        $supp = $data['supp'];
        $bln_1 = date('Y-m-d', strtotime($tgl));
        // $bln_2 = date('Y-m-d', strtotime('-2 month', strtotime($bln_1)));
        $bln_2 = date('Y-m-d', strtotime('-2 month', strtotime($tgl)));
        $kode_alamat = $data['kode_alamat'];
        // echo "<pre><br><br><br><br><br><br>";
        // echo " date : ".$date;
        // print_r($tgl);
        // echo " supp : ".$supp;
        // echo " tgl : ".$tgl;
        // echo " bln_1 : ".$bln_1;
        // echo " bln_2 : ".$bln_2;
        // echo "</pre>";

        if (strlen($kode_alamat) <= 4) {
            $kode_alamat_params = "";
        } else {
            $kode_alamat_params = "a.kode_alamat in ($kode_alamat) and";
        }

        $sql_do = "
        insert into db_po.t_temp_do_po_outstanding
        select 	a.nodo, a.kodedp, a.company, a.kodeprod_delto, a.namaprod, a.qty, a.nopo, 
                str_to_date(a.tgldo,'%d/%m/%Y') as tgldo,'$tgl',$id
        from db_po.t_do_deltomed a
        where month(str_to_date(a.tgldo,'%d/%m/%Y')) >= month('$bln_2') and year(str_to_date(a.tgldo,'%d/%m/%Y')) >= year('$bln_2')
        ";
        $this->db->query($sql_do);
        // echo "<pre><br><br><br><br><br><br>";
        // print_r($sql_do);
        // echo "</pre>";

        $sql_po = "
        INSERT INTO db_po.t_temp_report_po_update
        select 	'', a.id, b.id as id_po_detail,a.supp, a.grup, a.nopo, a.tglpo, a.nodo, a.tgldo, a.tglpesan,
                a.created, a.modified, a.created_by, a.modified_by, a.tipe, a.`open`, a.open_by, a.open_date,
                a.company, a.npwp, a.email, a.alamat, a.ambil, a.note, a.note_acc, a.`status`, a.status_approval,
                a.alasan_approval, a.po_ref, a.`lock`, a.kode_alamat, b.kodeprod, b.namaprod, 
                b.banyak, b.banyak_karton, b.`backup`, b.harga, b.kode_prc, b.berat, b.volume, b.stock_akhir, 
                b.rata, b.git, b.doi, b.status_terima, b.tanggal_terima, b.tanggal_terima_created_date, a.userid, a.deleted, $id,'$tgl'
        from 	mpm.po a INNER JOIN 
        (
                select a.*
                from mpm.po_detail a
                where a.deleted = 0
        )b on a.id = b.id_ref 
        where	$kode_alamat_params a.deleted = 0 and b.deleted = 0 and a.supp ='001' and 
        left(a.nopo,4) <> '/MPM' and 
        a.nopo not like '%batal%' and (date(a.tglpo) >= '$bln_2' and date(a.tglpo) <= '$bln_1')
        ";
        $this->db->query($sql_po);

        // echo "<pre><br><br><br><br><br><br>";
        // print_r($sql_po);
        // echo "</pre>";

        $sql = "
        insert into db_po.t_po_outstanding_deltomed
        select 	a.branch_name, a.nama_comp,a.company, a.tglpo, a.nopo,a.tipe, a.kodeprod, a.namaprod, 
                a.banyak as qty_po, b.qty as qty_pemenuhan, a.harga,(a.banyak*a.harga) as value_po,(b.qty*a.harga) as value_pemenuhan,
                a.berat, b.tgldo, b.nodo, (b.qty / a.banyak) * 100 as fulfilment,
                datediff(b.tgldo,DATE_FORMAT(a.tglpo,'%Y-%m-%d')) as lead_time_proses_do,
                a.po_ref, a.tanggal_terima, datediff(a.tanggal_terima,b.tgldo) as leadtime_proses_kirim,
                (a.banyak - b.qty) as outstanding_po,'$tgl', $id
        from
        (
            select 	d.branch_name, d.nama_comp, a.nopo, a.tglpo, a.tipe, a.userid, a.company, a.alamat, 
                    a.kodeprod, a.banyak, a.harga, e.namaprod,e.kodeprod_deltomed, 
                    a.po_ref, a.berat, a.status_terima, a.tanggal_terima
            from
            (
                select 	a.nopo, a.tglpo, a.tipe, a.userid, a.company, a.alamat, 
                        a.kodeprod, a.banyak, a.harga, a.po_ref, (a.berat * a.banyak_karton) as berat, a.status_terima, a.tanggal_terima
                from 	db_po.t_temp_report_po_update a  
                where   a.session_user = $id and a.supp ='001' and a.session_date = (select max(b.session_date) 
                        from db_po.t_temp_report_po_update b where b.session_user = $id)
                        
            )a LEFT JOIN
            (
                select a.id, a.username
                from mpm.`user` a
            )c on a.userid = c.id LEFT JOIN
            (
                select a.kode,a.branch_name,a.nama_comp,a.kode_comp
                from
                (
                    select concat(a.kode_comp,a.nocab) as kode, a.branch_name, a.nama_comp, a.kode_comp
                    from mpm.tbl_tabcomp a
                    where a.`status` = 1
                    GROUP BY kode
                )a INNER JOIN
                (
                    select concat(a.kode_comp,a.nocab) as kode
                    from db_dp.t_dp a
                    where tahun = $thn and a.`status` = 1
                )b on a.kode = b.kode
                GROUP BY kode_comp
            )d on c.username = d.kode_comp LEFT JOIN
            (
                select  a.kodeprod, a.namaprod, a.kodeprod_deltomed
                from    mpm.tabprod a
                where   supp = 001
            )e on a.kodeprod = e.kodeprod
        )a LEFT JOIN 
        (
            select a.nodo, a.kodedp, a.company, a.kodeprod_deltomed, a.namaprod, a.qty, a.nopo, tgldo
            from db_po.t_temp_do_po_outstanding a
            where a.created_by = $id and a.created_date = (select max(b.created_date) from db_po.t_temp_do_po_outstanding b where b.created_by = $id)
        )b on a.nopo = b.nopo and a.kodeprod_deltomed = b.kodeprod_deltomed 
        order by nama_comp, kodeprod
        ";
        $proses = $this->db->query($sql);
        // echo "<pre><br><br><br><br><br><br>";
        // print_r($sql);
        // echo "</pre>";

        if ($proses) {
            $sql = "select * from db_po.t_po_outstanding_deltomed where created_by = $id and created_date = '$tgl'";
            $proses = $this->db->query($sql)->result();
            if ($proses) {
                return $proses;
            } else {
                return array();
            }
        }
    }

    public function po_outstanding_us_update($data)
    {
        // echo "supp : ".$supp;
        // echo "periode_1 : ".$periode_1;
        // echo "periode_2 : ".$periode_2;
        date_default_timezone_set('Asia/Jakarta');
        $date = date_create(date('Y-m-d H:i:s'));
        date_add($date, date_interval_create_from_date_string('-7 hours'));
        $tgl = date_format($date, 'Y-m-d H:i:s');
        $thn = date('Y');

        $id = $this->session->userdata('id');
        $supp = $data['supp'];
        $bln_1 = date('Y-m-d');
        $bln_2 = date('Y-m-d', strtotime('-2 month', strtotime($bln_1)));
        $kode_alamat = $data['kode_alamat'];
        // echo "<pre>";
        // echo " supp : ".$supp;
        // echo " periode1 : ".$periode1;
        // echo " periode2 : ".$periode2;
        // echo "</pre>";

        if (strlen($kode_alamat) <= 4) {
            $kode_alamat_params = "";
        } else {
            $kode_alamat_params = "a.kode_alamat in ($kode_alamat) and";
        }

        $sql_do = "
        insert into db_po.t_temp_do_po_outstanding_us
        select 	a.nodo, a.tgldo, a.kodeprod,a.nopo,
                if(b.satuan_box is null, a.qty_pemenuhan, a.qty_pemenuhan * b.satuan_box) as qty_pemenuhan,
                '$tgl',$id
        FROM
        (
            select 	a.nodo, a.tgldo,
                    a.kodeprod, sum(a.banyak) as qty_pemenuhan, a.nopo
            from 	db_po.t_do_us a
            where year(a.tgldo) >= year('$bln_2') and month(a.tgldo) >= month('$bln_2')
            GROUP BY a.nopo, a.kodeprod
        )a LEFT JOIN
        (
            select a.kodeprod, a.satuan_box, a.`status`
            from db_produk.t_product_po a
            where a.`status` = 1
        )b on a.kodeprod = b.kodeprod
        ";
        $this->db->query($sql_do);
        // echo "<pre><br><br><br><br><br><br>";
        // print_r($sql_do);
        // echo "</pre>";

        $sql_po = "
        INSERT INTO db_po.t_temp_report_po_update
        select 	'', a.id, b.id as id_po_detail,a.supp, a.grup, a.nopo, a.tglpo, a.nodo, a.tgldo, a.tglpesan,
                a.created, a.modified, a.created_by, a.modified_by, a.tipe, a.`open`, a.open_by, a.open_date,
                a.company, a.npwp, a.email, a.alamat, a.ambil, a.note, a.note_acc, a.`status`, a.status_approval,
                a.alasan_approval, a.po_ref, a.`lock`, a.kode_alamat, b.kodeprod, b.namaprod, 
                b.banyak, b.banyak_karton, b.`backup`, b.harga, b.kode_prc, b.berat, b.volume, b.stock_akhir, 
                b.rata, b.git, b.doi, b.status_terima, b.tanggal_terima, b.tanggal_terima_created_date, a.userid, a.deleted, $id,'$tgl'
        from 	mpm.po a INNER JOIN 
        (
                select a.*
                from mpm.po_detail a
                where a.deleted = 0
        )b on a.id = b.id_ref 
        where	$kode_alamat_params a.deleted = 0 and b.deleted = 0 and a.supp ='005' and 
        left(a.nopo,4) <> '/MPM' and 
        a.nopo not like '%batal%' and (date(a.tglpo) >= '$bln_2' and date(a.tglpo) <= '$bln_1')
        ";
        $this->db->query($sql_po);

        // echo "<pre><br><br><br><br><br><br>";
        // print_r($sql_po);
        // echo "</pre>";

        $sql = "
        insert into db_po.t_po_outstanding_us
        select 	a.branch_name, a.nama_comp, date(a.tglpo) as tglpo,a.nopo,a.tipe, a.kodeprod,  
                a.namaprod, a.banyak as qty_po, 
                b.qty_pemenuhan,b.tgldo, b.nodo, (b.qty_pemenuhan / a.banyak * 100) as fulfilment,
                datediff(b.tgldo,DATE_FORMAT(a.tglpo,'%Y-%m-%d')) as lead_time_proses_do,  
                a.tanggal_terima, datediff(a.tanggal_terima, b.tgldo) as leadtime_proses_kirim, 
                (a.banyak - b.qty_pemenuhan) as outstanding_po,'$tgl',$id
        FROM 
        ( 
            select 	d.branch_name, d.nama_comp, 
                    a.nopo, a.tglpo, a.tipe, 
                    a.userid, a.company, a.alamat, 
                    a.kodeprod, a.banyak, a.harga, e.namaprod,a.po_ref,a.tanggal_terima
            from
            (
                select 	a.nopo, a.tglpo, a.tipe, 
                        a.userid, a.company, a.alamat, 
                        a.kodeprod, a.banyak, a.harga, a.po_ref, a.tanggal_terima
                from 	db_po.t_temp_report_po_update a
                where   a.session_user = $id and a.supp ='005' and a.session_date = (select max(b.session_date) 
                        from db_po.t_temp_report_po_update b where b.session_user = $id)
            )a LEFT JOIN
            (
                select a.id, a.username
                from mpm.`user` a
            )c on a.userid = c.id LEFT JOIN
            (
                select a.kode,a.branch_name,a.nama_comp,a.kode_comp
                from
                (
                    select concat(a.kode_comp,a.nocab) as kode, a.branch_name, a.nama_comp, a.kode_comp
                    from mpm.tbl_tabcomp a
                    where a.`status` = 1
                    GROUP BY kode
                )a INNER JOIN
                (
                    select concat(a.kode_comp,a.nocab) as kode
                    from db_dp.t_dp a
                    where tahun = $thn and a.`status` = 1
                )b on a.kode = b.kode
                GROUP BY kode_comp
            )d on c.username = d.kode_comp LEFT JOIN
            (
                select a.kodeprod, a.namaprod
                from mpm.tabprod a
            )e on a.kodeprod = e.kodeprod
        )a LEFT JOIN
        (
            select 	a.nodo, a.tgldo, a.kodeprod,a.nopo, qty_pemenuhan
            FROM db_po.t_temp_do_po_outstanding_us a	
            where a.created_by = $id and a.created_date = (select max(b.created_date) from db_po.t_temp_do_po_outstanding_us b where b.created_by = $id)
        )b on a.nopo = b.nopo and a.kodeprod = b.kodeprod
        order by nama_comp, kodeprod
        ";
        $proses = $this->db->query($sql);
        // echo "<pre><br><br><br><br><br><br>";
        // print_r($sql);
        // echo "</pre>";

        if ($proses) {
            $sql = "select * from db_po.t_po_outstanding_us where created_by = $id and created_date = '$tgl'";
            $proses = $this->db->query($sql)->result();
            if ($proses) {
                return $proses;
            } else {
                return array();
            }
        }
    }

    public function po_outstanding_intrafood_update($data)
    {
        // echo "supp : ".$supp;
        // echo "periode_1 : ".$periode_1;
        // echo "periode_2 : ".$periode_2;
        date_default_timezone_set('Asia/Jakarta');
        $date = date_create(date('Y-m-d H:i:s'));
        date_add($date, date_interval_create_from_date_string('-7 hours'));
        $tgl = date_format($date, 'Y-m-d H:i:s');
        $thn = date('Y');

        $id = $this->session->userdata('id');
        $supp = $data['supp'];
        $bln_1 = date('Y-m-d');
        $bln_2 = date('Y-m-d', strtotime('-2 month', strtotime($bln_1)));
        $kode_alamat = $data['kode_alamat'];
        // echo "<pre>";
        // echo " supp : ".$supp;
        // echo " periode1 : ".$periode1;
        // echo " periode2 : ".$periode2;
        // echo "</pre>";

        if (strlen($kode_alamat) <= 4) {
            $kode_alamat_params = "";
        } else {
            $kode_alamat_params = "a.kode_alamat in ($kode_alamat) and";
        }

        $sql_po = "
        INSERT INTO db_po.t_temp_report_po_update
        select 	'', a.id, b.id as id_po_detail,a.supp, a.grup, a.nopo, a.tglpo, a.nodo, a.tgldo, a.tglpesan,
                a.created, a.modified, a.created_by, a.modified_by, a.tipe, a.`open`, a.open_by, a.open_date,
                a.company, a.npwp, a.email, a.alamat, a.ambil, a.note, a.note_acc, a.`status`, a.status_approval,
                a.alasan_approval, a.po_ref, a.`lock`, a.kode_alamat, b.kodeprod, b.namaprod, b.volume, 
                b.banyak, b.banyak_karton, b.`backup`, b.harga, b.kode_prc, b.berat, b.stock_akhir, 
                b.rata, b.git, b.doi, b.status_terima, b.tanggal_terima, b.tanggal_terima_created_date, a.userid, a.deleted, $id,'$tgl'
        from 	mpm.po a INNER JOIN 
        (
                select a.*
                from mpm.po_detail a
                where a.deleted = 0
        )b on a.id = b.id_ref 
        where	$kode_alamat_params a.deleted = 0 and b.deleted = 0 and a.supp ='012' and 
        left(a.nopo,4) <> '/MPM' and 
        a.nopo not like '%batal%' and (date(a.tglpo) >= '$bln_2' and date(a.tglpo) <= '$bln_1')
        ";
        $this->db->query($sql_po);

        // echo "<pre><br><br><br><br><br><br>";
        // print_r($sql_po);
        // echo "</pre>";

        $sql = "
        insert into db_po.t_po_outstanding_intrafood
        select 	a.id, d.branch_name,d.nama_comp, a.tglpo, a.nopo, a.tipe, a.kodeprod, a.namaprod, 
                a.qty_po, b.total_karton, b.tanggal_kirim, b.tanggal_tiba, (if(b.total_karton = null,0,b.total_karton) / a.qty_po * 100 ) as fulfilment,
                datediff(b.tanggal_kirim,DATE_FORMAT(a.tglpo,'%Y-%m-%d')) as leadtime_proses_do,f.status_closed, 
                if(e.total_produk_po is null, 'red', if(e.total_karton_po = e.total_karton_asn, 'green', 'yellow')) as color,
                a.tanggal_terima,datediff(a.tanggal_terima, b.tanggal_kirim) as leadtime_proses_kirim, (a.qty_po - b.total_karton) as outstanding_po,
        '$tgl', $id
        from
        (
            select 	a.id, DATE_FORMAT(a.tglpo,'%Y-%m-%d') as tglpo,
                    a.nopo,a.tipe, a.kodeprod, a.namaprod, sum(a.banyak_karton) as qty_po,
                    a.tanggal_terima, a.userid
            from 	db_po.t_temp_report_po_update a
            where   a.session_user = $id and a.supp ='012' and a.session_date = (select max(b.session_date) 
                    from db_po.t_temp_report_po_update b where b.session_user = $id)
        )a left join 
        (
            select	a.id_po, a.kodeprod, sum(a.jumlah_karton) as total_karton, a.status_pemenuhan, 
                    a.tanggal_kirim, a.nama_ekspedisi, a.est_lead_time, 
                    a.tanggal_tiba, a.keterangan, a.status_closed, a.created_date
            from	mpm.t_asn a
            where 	a.tanggal_kirim >= '$bln_2'  
            GROUP BY a.id_po, a.kodeprod

        )b on a.id = b.id_po and a.kodeprod = b.kodeprod LEFT JOIN
        (
            select a.id, a.username
            from mpm.`user` a
        )c on a.userid = c.id LEFT JOIN
        (
            select a.kode,a.branch_name,a.nama_comp,a.kode_comp
            from
            (
                select concat(a.kode_comp,a.nocab) as kode, a.branch_name, a.nama_comp, a.kode_comp
                from mpm.tbl_tabcomp a
                where a.`status` = 1
                GROUP BY kode
            )a INNER JOIN
            (
                select concat(a.kode_comp,a.nocab) as kode
                from db_dp.t_dp a
                where tahun = $thn and a.`status` = 1
            )b on a.kode = b.kode
            GROUP BY kode_comp
        )d on c.username = d.kode_comp LEFT JOIN
        (
            select  a.id,a.id_po,a.total_unit_po,a.total_unit_asn,a.total_produk_po,
                    a.total_produk_asn, a.total_karton_po, a.total_karton_asn
            from    mpm.t_cek_asn a
            where a.id = (
                select max(b.id)
                from mpm.t_cek_asn b
                where a.id_po = b.id_po
            ) 
            ORDER BY a.id desc
        )e on a.id = e.id_po LEFT JOIN
        (
            select a.id_po,a.status_closed
            from mpm.t_asn a
            GROUP BY a.id_po
        )f on a.id = f.id_po
        order by nama_comp, kodeprod
        ";
        $proses = $this->db->query($sql);

        // echo "<pre><br><br><br><br>";
        // print_r($sql);
        // echo "</pre>";

        if ($proses) {
            $sql = "select * from db_po.t_po_outstanding_intrafood where created_by = $id and created_date = '$tgl'";
            $proses = $this->db->query($sql)->result();
            if ($proses) {
                return $proses;
            } else {
                return array();
            }
        }
    }

    public function ms_deltomed($kode)
    {
        $sql = "select * from db_master_data.t_temp_monitoring_stock_suggest_po a where a.kode in ($kode)";
        // echo "<pre>";
        // print_r($sql);
        // echo "</pre>";
        $proses = $this->db->query($sql)->result();
        if ($proses) {
            return $proses;
        } else {
            return array();
        }
    }

    public function get_ms($signature = null)
    {
        if ($signature == null) {
            $proses = $this->db->query("select * from db_master_data.t_temp_monitoring_stock_suggest_po a")->result();
        } else {
            $proses = $this->db->query("select * from db_master_data.t_temp_monitoring_stock_suggest_po a where a.signature = '$signature'")->result();
        }
        // echo "<pre>";
        // print_r($proses);
        // echo "</pre>";
        // die;
        if ($proses) {
            return $proses;
        } else {
            return array();
        }
    }

    public function update_ms($data)
    {
        $created_date = $data['created_date'];
        $id = $this->session->userdata('id');
        $signature = $data['signature'];
        $kode = $data['kode'];
        $kodeprod = $data['kodeprod'];
        $estimasi_sales = $data['estimasi_sales'];
        $stock_ideal = $data['stock_ideal'];

        $sql = "
        update db_master_data.t_temp_monitoring_stock_suggest_po a 
            set a.estimasi_sales = $estimasi_sales, 
            a.stock_ideal = $stock_ideal,
            a.last_updated = $created_date,
            a.last_updated_by = $id
        where a.signature = '$signature' and a.kode = '$kode' and a.kodeprod = '$kodeprod'";
        $proses = $this->db->query($sql);
        if ($proses) {
            // jika berhasil update, maka insert ke log
            $update_log = "
                update db_master_data.t_temp_monitoring_stock_log a 
                set a.status = 0
                where a.signature = '$signature' and a.kode = '$kode' and a.kodeprod = '$kodeprod'
            ";
            $proses_update_log = $this->db->query($update_log);
            $insert_log = "
                insert into db_master_data.t_temp_monitoring_stock_log
                select '',$estimasi_sales,$stock_ideal, '$kode', '$kodeprod', '$signature', $created_date, $id, 1
            ";
            $proses = $this->db->query($insert_log);
            // $update = "update db_master_data.t_temp_monitoring_stock_suggest_po a set a."


            echo "<script>alert('update berhasil. anda akan di arahkan ke halaman awal. Jika ingin mengupdate seluruh data, klik tombol konsolidasi'); window.location = 'ms_deltomed/';</script>";
        } else {
            echo "<script>alert('update gagal. silahkan informasikan ini ke IT (suffy)'); window.location = 'ms_edit/'.$signature;</script>";
        }
    }

    public function get_log_ms()
    {
        $id = $this->session->userdata('id');
        $sql = "
        select a.id, a.estimasi_sales, a.stock_ideal, a.kode, a.kodeprod, a.signature, a.created_date, a.status
        from db_master_data.t_temp_monitoring_stock_log a
        where a.created_by = $id and a.`status` = 1
        ";
        $proses = $this->db->query($sql)->result();
        if ($proses) {
            return $proses;
        } else {
            return array();
        }
    }

    public function konsolidasi_ms($data)
    {
        // var_dump($data['get_log_ms']);
        $id = $this->session->userdata('id');
        foreach ($data['get_log_ms'] as $a) {

            $potensi_sales = "
                update db_master_data.t_temp_monitoring_stock_suggest_po a
                set a.potensi_sales = (a.estimasi_sales - a.sales_berjalan),
                    a.last_updated_by = $id
                where a.kodeprod = '$a->kodeprod' and a.kode = '$a->kode' and a.signature = '$a->signature'
            ";
            $proses_potensi_sales = $this->db->query($potensi_sales);

            $stock_berjalan = "
                update db_master_data.t_temp_monitoring_stock_suggest_po a
                set a.stock_berjalan = (a.total_stock - a.potensi_sales),
                    a.last_updated_by = $id
                where a.kodeprod = '$a->kodeprod' and a.kode = '$a->kode' and a.signature = '$a->signature'
            ";
            $proses_stock_berjalan = $this->db->query($stock_berjalan);

            $doi = "
                update db_master_data.t_temp_monitoring_stock_suggest_po a
                set a.doi = round(a.stock_berjalan / a.rata * 30),
                    a.last_updated_by = $id
                where a.kodeprod = '$a->kodeprod' and a.kode = '$a->kode' and a.signature = '$a->signature'
            ";
            $proses_doi = $this->db->query($doi);

            $pp = "
                update db_master_data.t_temp_monitoring_stock_suggest_po a
                set a.pp = (if(a.stock_ideal_unit - a.stock_berjalan < 0, 0, a.stock_ideal_unit - a.stock_berjalan)),
                    a.last_updated_by = $id
                where a.kodeprod = '$a->kodeprod' and a.kode = '$a->kode' and a.signature = '$a->signature'
            ";
            $proses_pp = $this->db->query($pp);

            $estimasi_saldo_akhir = "
                update db_master_data.t_temp_monitoring_stock_suggest_po a
                set a.estimasi_saldo_akhir = (a.stock_berjalan + a.pp),
                    a.last_updated_by = $id
                where a.kodeprod = '$a->kodeprod' and a.kode = '$a->kode' and a.signature = '$a->signature'
            ";
            $proses_estimasi_saldo_akhir = $this->db->query($estimasi_saldo_akhir);

            $estimasi_doi_akhir = "
                update db_master_data.t_temp_monitoring_stock_suggest_po a
                set a.estimasi_doi_akhir = round(a.estimasi_saldo_akhir / a.rata * 30),
                    a.last_updated_by = $id
                where a.kodeprod = '$a->kodeprod' and a.kode = '$a->kode' and a.signature = '$a->signature'
            ";
            $proses_estimasi_doi_akhir = $this->db->query($estimasi_doi_akhir);

            $pp_karton = "
                update db_master_data.t_temp_monitoring_stock_suggest_po a
                set a.pp_karton = round(a.pp / a.isisatuan),
                    a.last_updated_by = $id
                where a.kodeprod = '$a->kodeprod' and a.kode = '$a->kode' and a.signature = '$a->signature'
            ";
            $proses_pp_karton = $this->db->query($pp_karton);

            $pp_kg = "
                update db_master_data.t_temp_monitoring_stock_suggest_po a
                set a.pp_kg = round(a.pp_karton * a.berat),
                    a.last_updated_by = $id
                where a.kodeprod = '$a->kodeprod' and a.kode = '$a->kode' and a.signature = '$a->signature'
            ";
            $proses_pp_kg = $this->db->query($pp_kg);

            $pp_volume = "
                update db_master_data.t_temp_monitoring_stock_suggest_po a
                set a.pp_volume = round(a.pp_karton * a.volume),
                    a.last_updated_by = $id
                where a.kodeprod = '$a->kodeprod' and a.kode = '$a->kode' and a.signature = '$a->signature'
            ";
            $proses_pp_volume = $this->db->query($pp_volume);

            $pp_value = "
                update db_master_data.t_temp_monitoring_stock_suggest_po a
                set a.pp_value = round(a.pp_karton * a.harga * a.isisatuan),
                    a.last_updated_by = $id
                where a.kodeprod = '$a->kodeprod' and a.kode = '$a->kode' and a.signature = '$a->signature'
            ";
            $proses_pp_value = $this->db->query($pp_value);
        }

        if ($proses_pp_value) {
            echo "<script>alert('konsolidasi berhasil, namun baiknya silahkan crosscek ulang data anda.'); window.location = 'ms_deltomed/';</script>";
        } else {
            echo "<script>alert('konsolidasi gagal, baiknya infokan hal ini ke IT (suffy) atau Tim Inventory (Pak Fakhrul).'); window.location = 'ms_deltomed/';</script>";
        }
    }

    function insert_ms($data, $table)
    {
        $this->db->insert_batch($table, $data);
    }

    function get_import_ms($created_by, $tgl)
    {

        $sql = "
            select *
            from db_master_data.t_import_ms a LEFT JOIN
            (
                select concat(a.kode_comp, a.nocab) as site_code, a.branch_name, a.nama_comp
                from mpm.tbl_tabcomp a
                where a.status = 1
                GROUP BY concat(a.kode_comp, a.nocab)
            )b on a.site_code = b.site_code LEFT JOIN 
            (
                select a.kodeprod, a.namaprod
                from mpm.tabprod a
            )c on a.kodeprod = c.kodeprod
            where a.created_by = $created_by and a.created_at = '$tgl' and (a.estimasi_sales is not null and a.stock_ideal is not null) and a.deleted is null
        ";
        $proses = $this->db->query($sql)->result();
        // echo "<pre>";
        // print_r($sql);
        // echo "</pre>";
        return $proses;
    }

    function get_import_ms_setting_product($created_by, $tgl)
    {

        $sql = "
            select a.kodeprod, b.namaprod, a.status, a.created_at, a.created_by
            from db_master_data.t_import_ms_setting_product a left join mpm.tabprod b
                on a.kodeprod = b.kodeprod
            where a.created_by = $created_by and a.created_at = '$tgl'
            group by a.kodeprod
        ";
        // echo "<pre><br><br><br>";
        // print_r($sql);
        // echo "</pre>";
        $proses = $this->db->query($sql)->result();
        return $proses;
    }
}
