<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_rofo extends CI_Model 
{
    // public function __construct(){
    //     $this->load->database('po',TRUE);
    // }

	public function proses_rofo($data)
    {
        $id = $this->session->userdata('id');
        $bulan = $data['bulan'];
        $tahun = $data['tahun'];
        $supp = $data['supp'];

        $sql_del = $this->db->query("delete from db_rofo.t_laporan_po_temp where bulan = $bulan and tahun = $tahun and supp = '$supp'");
        $sql_ambil_po_temp = "
            insert into db_rofo.t_laporan_po_temp
            select 	a.nopo, a.company,a.tipe, date(a.tglpo), a.alamat, f.kode, c.supp, b.kodeprod, b.banyak, 
                    c.namaprod, c.grup, d.nama_group, a.userid, $id,'$bulan','$tahun'
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
                                    from mpm.tbl_tabcomp_new a
                                    where a.`status` = 1
                                    GROUP BY kode
                                )a LEFT JOIN (
                                    select concat(a.kode_comp, a.nocab) as kode
                                    from data$tahun.fi a
                                    where a.bulan = $bulan
                                    GROUP BY kode
                                )b on a.kode = b.kode
                                where b.kode is not null
							)a
						)f on e.username = f.kode_comp
            where a.supp ='$supp' and `open` = 1  and a.deleted = 0 and b.deleted = 0 and year(tglpo) = $tahun 
                and month(tglpo) = $bulan and nopo not like '/mpm%' and a.nopo not like '%batal%'
            ORDER BY a.id desc
        ";
        echo "<pre>";
        print_r($sql_ambil_po_temp);
        echo "</pre>"; 
        $proses_ambil_po_temp = $this->db->query($sql_ambil_po_temp);

        $sql_del = $this->db->query("delete from db_rofo.t_laporan_po_harga_temp where bulan = $bulan and tahun = $tahun");
        $sql_harga = "
            insert into db_rofo.t_laporan_po_harga_temp
            select  a.kodeprod, 
                    a.h_dp * (100-d_dp)/100 as h_dp, $id,'$bulan','$tahun'
            from    mpm.prod_detail a 
                    inner join mpm.tabprod b using(kodeprod)
            where   tgl=(
                        select  max(tgl) 
                        from    mpm.prod_detail 
                        where   kodeprod=a.kodeprod
            )ORDER BY KODEPROD
        ";
        echo "<pre>";
        print_r($sql_harga);
        echo "</pre>";

        $proses_harga = $this->db->query($sql_harga);

        $sql_del = $this->db->query("delete from db_rofo.t_laporan_po where bulan = $bulan and tahun = $tahun and supp='$supp'");
        $sql_ambil_po = "
            insert into db_rofo.t_laporan_po
            select 	a.nopo, a.kode, a.company, d.branch_name, d.nama_comp, a.tipe, a.tglpo, a.alamat, a.supp, a.kodeprod,  a.namaprod, a.grup, a.nama_group, 
                    a.banyak, b.h_dp as harga, (a.banyak*b.h_dp) as total, urutan, $id,'$bulan','$tahun'
            from    db_rofo.t_laporan_po_temp a LEFT JOIN db_rofo.t_laporan_po_harga_temp b 
                        on a.kodeprod = b.kodeprod and b.id=$id LEFT JOIN mpm.`user` c
                        on c.id = a.userid LEFT JOIN
            (
                select concat(a.kode_comp, a.nocab) as kode, a.branch_name, a.nama_comp, a.kode_comp, urutan
                from mpm.tbl_tabcomp_new a
                where a.`status` = 1
                GROUP BY a.kode_comp
            )d on d.kode_comp = c.username 
            where a.id = $id and a.supp = '$supp' and a.bulan = '$bulan' and a.tahun='$tahun'
        "; 
        $proses_ambil_po = $this->db->query($sql_ambil_po);

        echo "<pre>";
        print_r($sql_ambil_po);
        echo "</pre>";
        
        $sql_del_do = $this->db->query("delete from db_rofo.t_do_deltomed_temp where id=$id");
        $sql_do = "
            insert into db_rofo.t_do_deltomed_temp
            select a.nopo, a.kodeprod, a.kodeprod_delto, a.total_qty, $id
            FROM
            (
                select sum(a.qty) total_qty, a.nopo, b.kodeprod, kodeprod_delto, str_to_date(a.tgldo,'%d/%m/%Y') as tgldo
                from db_po.t_do_deltomed a LEFT JOIN db_produk.tbl_produk b
                    on a.kodeprod_delto = b.dl_kodeprod
                where month(str_to_date(a.tgldo,'%d/%m/%Y')) <= $bulan
                GROUP BY a.nopo, a.kodeprod_delto
            )a 
        ";
        $proses_ambil_do = $this->db->query($sql_do);

        echo "<pre>";
        print_r($sql_do);
        echo "</pre>";

        $sql_del_po_blank = $this->db->query("delete from db_rofo.t_po_blank_temp where id=$id");
        $sql_po_blank = "
            insert into db_rofo.t_po_blank_temp
            select  a.kode, a.kodeprod, sum(a.banyak) as unit, $id
            from    db_rofo.t_laporan_po a LEFT JOIN (
                select a.nopo, a.kodeprod 
                from db_rofo.t_do_deltomed_temp a
                where a.id = $id
            )b on a.nopo = b.nopo and a.kodeprod = b.kodeprod
            where b.nopo is null
            GROUP BY a.kode, a.kodeprod
        ";
        $proses_po_blank = $this->db->query($sql_po_blank);

        echo "<pre>";
        print_r($sql_po_blank);
        echo "</pre>";

        $bulan_berjalan = $bulan + 1;
        $sql_del_po_berjalan = $this->db->query("delete from db_rofo.t_po_bulan_berjalan_temp where id=$id");
        $sql_po_berjalan = "
            insert into db_rofo.t_po_bulan_berjalan_temp
            select 	f.kode, b.kodeprod, sum(b.banyak), '$bulan_berjalan', $id
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
                            from mpm.tbl_tabcomp_new a
                            where a.`status` = 1
                            GROUP BY kode
                    )a LEFT JOIN (
                            select concat(a.kode_comp, a.nocab) as kode
                            from data$tahun.fi a
                            where a.bulan = $bulan_berjalan
                            GROUP BY kode
                    )b on a.kode = b.kode
                    where b.kode is not null
                )a
            )f on e.username = f.kode_comp
            where a.supp ='$supp' and `open` = 1  and a.deleted = 0 and b.deleted = 0 and year(tglpo) = $tahun 
                    and month(tglpo) = '$bulan_berjalan' and nopo not like '/mpm%' and a.nopo not like '%batal%'
            GROUP BY f.kode, b.kodeprod
            ORDER BY a.id desc
        ";
        $proses_po_berjalan = $this->db->query($sql_po_berjalan);

        echo "<pre>";
        print_r($sql_po_berjalan);
        echo "</pre>";

        $sql_del_stock_doi = $this->db->query("delete from db_rofo.t_stock_doi_temp where id=$id");

        $sql_stock_doi = "
        insert into db_rofo.t_stock_doi_temp
        select 	a.kode, a.nocab, c.branch_name, c.nama_comp, d.supp, d.grup,f.nama_group, e.namasupp, a.kodeprod, d.namaprod, 
                avg, b.stokValue, (b.stokValue / avg * 30) as doi_value, avg_unit, b.stok, (b.stok / avg_unit * 30) as doi_unit, $id
        FROM
        (
            select kode, right(kode,2) as nocab, KODEPROD, sum(tot1) as omzet, sum(tot1/6) as avg, sum(banyak) as omzet_unit, sum(banyak/6) as avg_unit
            FROM
            (
                select  concat(a.kode_comp, a.nocab) as kode, tot1, banyak, kodeprod
                from    data2019.fi a
                where   bulan in (11,12)
                union all
                select  concat(a.kode_comp, a.nocab) as kode, tot1, banyak, kodeprod
                from    data2019.ri a
                where   bulan in (11,12)
                union all
                select  concat(a.kode_comp, a.nocab) as kode, tot1, banyak, kodeprod
                from    data2020.fi a
                where   bulan in (1,2,3,4)
                union all
                select  concat(a.kode_comp, a.nocab) as kode, tot1, banyak, kodeprod
                from    data2020.ri a
                where   bulan in (1,2,3,4)
            )a GROUP BY kode, kodeprod
        )a LEFT JOIN
        (
            select nocab, kodeprod, stok, stokValue
            FROM
            (
                select a.nocab, a.kodeprod, a.stok, b.h_dp, (a.stok * b.h_dp) as stokValue
                FROM
                (
                    select  nocab,kodeprod,namaprod,substr(bulan,3) as bulan,
                            sum( if(kode_gdg='PST',
                            ((Saldoawal+masuk_pbk+masuk_supp+retur_sal+bPinjam+kvretur+kr_gudang+retur_depo)-
                            (sales+pinjam+minta_depo+kvbeli+kr_min)-(rusak+sisih)),(Saldoawal+masuk_pbk+retur_sal+kvretur+bPinjam+tukar_msk+msk_gddepo) -
                            (sales+Pinjam+kvbeli+retur_depo+tukar_klr+klr_gddepo))) as stok
                    from    data$tahun.st 
                    where substr(bulan,3) = $bulan
                    group by nocab, kodeprod,bulan
                    order by kodeprod
                )a LEFT JOIN
                (
                    select  a.kodeprod, a.h_dp
                    from    mpm.prod_detail a
                    where   a.tgl = (
                    select max(b.tgl)
                    from mpm.prod_detail b
                    where a.kodeprod = b.kodeprod
                    )
                )b on a.kodeprod = b.kodeprod
            )a 
        )b on a.nocab = b.nocab and a.kodeprod = b.kodeprod
        LEFT JOIN
        (
            select  concat(a.kode_comp, a.nocab) as kode, a.branch_name, a.nama_comp, a.urutan
            from    mpm.tbl_tabcomp_new a
            where   a.`status` = 1
            GROUP BY concat(a.kode_comp, a.nocab)
        )c on a.kode = c.kode
        INNER JOIN
        (
            select a.kodeprod, a.namaprod, a.supp, a.grup
            from mpm.tabprod a
            where a.supp ='$supp'
        )d on a.kodeprod = d.kodeprod
        LEFT JOIN
        (
            select a.supp, a.namasupp
            from mpm.tabsupp a
        )e on d.supp = e.supp
        LEFT JOIN
        (
            select a.kode_group, a.nama_group 
            from mpm.tbl_group a
        )f on d.grup = f.kode_group
        ORDER BY urutan
        ";
        
        $proses = $this->db->query($sql_stock_doi);

        $sql_del_insert_git = $this->db->query("delete from db_rofo.t_insert_git where bulan=$bulan and tahun = $tahun and supp = '$supp'");
        $sql_insert_git = "
            insert into db_rofo.t_insert_git
            select 	a.kode, a.branch_name, a.nama_comp, replace(a.kodeprod,' ','') as kodeprod, a.namaprod, 
                    a.avg_unit, a.stock_akhir, b.unit as po_belum_do, c.banyak as po_berjalan,'' as git,
                    '' as target_1,'' as target_2,'' as target_3,'' as target_4, '' as stock_level, '' as stock_level_unit,
                    '' as total_stock, '' as est_1, '' as purchase_plan, '' as est_2, '' as isi_satuan,
                    '' as purchase_plan_karton, '' as purchase_plan_volume, '' as purchase_plan_berat, 
                    '' as volume, '' as berat, '' as est_3,'' as est_doi,'' as est_4,
                    '' as purchase_plan_2,'' as purchase_plan_2_karton,'' as purchase_plan_2_volume, '' as purchase_plan_2_berat, 
                    '' as est_5,'' as est_doi_2,'' as est_6,
                    '' as purchase_plan_3,'' as purchase_plan_3_karton,'' as purchase_plan_3_volume,'' as purchase_plan_3_berat,
                    '' as est_7,'' as est_doi_3,
                    '$supp','$bulan','$tahun',$id
            from db_rofo.t_stock_doi_temp a LEFT JOIN
            (
                select a.kode,a.kodeprod,a.unit
                from db_rofo.t_po_blank_temp a
                where a.id = $id
            )b on a.kode = b.kode and a.kodeprod = b.kodeprod LEFT JOIN
            (
                select a.kode, a.kodeprod, a.banyak
                from db_rofo.t_po_bulan_berjalan_temp a
                where a.id = $id and a.kode is not null
            )c on a.kode = c.kode and a.kodeprod = c.kodeprod
            where a.id = $id 
        ";
        $proses_insert_git = $this->db->query($sql_insert_git);

        echo "<pre>";
        print_r($sql_insert_git);
        echo "</pre>";

        $sql = "select * from db_rofo.t_insert_git where tahun='$tahun' and bulan='$bulan' and supp ='$supp' and id=$id";
        $proses= $this->db->query($sql)->result();
        if ($proses) {
            return $proses;
        }else{
            return array();
        }
    }

    function insert_git($data)
	{
        $id = $this->session->userdata('id');
        $sql_del = "delete from db_rofo.t_insert_git_from_file where id = $id";
        $proses_del = $this->db->query($sql_del);

        // $this->db->query("truncate db_import.t_import");
        $this->db->insert_batch('db_rofo.t_insert_git_from_file', $data);
        
        $sql_update_git = "
            update db_rofo.t_insert_git a
            set a.git = (                
                SELECT b.git
                FROM
                (
                    select  if(length(a.kodeprod) = 5, concat('0',a.kodeprod), a.kodeprod) as kodeprod, a.kode, a.git
                    from    db_rofo.t_insert_git_from_file a
                    where   a.id = $id
                )b where a.kode = b.kode and a.kodeprod = b.kodeprod
            ) where a.id = $id
        ";
        $proses_update_git = $this->db->query($sql_update_git);

        $sql_update_target_1 = "
            update db_rofo.t_insert_git a
            set a.target_1 = (        
                select b.target
                from db_rofo.t_target b
                where b.bulan = 6 and a.kodeprod = b.kodeprod and left(a.kode,3) = b.kode_comp
            )where a.id = $id
        ";
        $proses_update_target_1 = $this->db->query($sql_update_target_1);

        $sql_update_target_2 = "
            update db_rofo.t_insert_git a
            set a.target_2 = (        
                select b.target
                from db_rofo.t_target b
                where b.bulan = 7 and a.kodeprod = b.kodeprod and left(a.kode,3) = b.kode_comp
                GROUP BY b.kode_comp, b.kodeprod
            )where a.id = $id
        ";
        $proses_update_target_2 = $this->db->query($sql_update_target_2);

        $sql_update_target_3 = "
            update db_rofo.t_insert_git a
            set a.target_3 = (        
                select b.target
                from db_rofo.t_target b
                where b.bulan = 8 and a.kodeprod = b.kodeprod and left(a.kode,3) = b.kode_comp
                GROUP BY b.kode_comp, b.kodeprod
            )where a.id = $id
        ";
        $proses_update_target_3 = $this->db->query($sql_update_target_3);

        $sql_update_target_4 = "
            update db_rofo.t_insert_git a
            set a.target_4 = (        
                select b.target
                from db_rofo.t_target b
                where b.bulan = 9 and a.kodeprod = b.kodeprod and left(a.kode,3) = b.kode_comp
                GROUP BY b.kode_comp, b.kodeprod
            )where a.id = $id
        ";
        $proses_update_target_4 = $this->db->query($sql_update_target_4);

        $sql_del_stock_level = $this->db->query("delete from db_rofo.t_stock_level_temp where id=$id");
        $sql_insert_stock_level = "
            insert into db_rofo.t_stock_level_temp
            select concat(kode_comp,nocab) as kode, b.kode_comp, b.kodeprod, b.sl, $id
            from mpm.tbl_stok_level b INNER JOIN 
            (
                select concat(a.kode_comp, a.nocab) as kode
                from data2020.fi a
                where bulan = 5
                GROUP BY concat(a.kode_comp, a.nocab)
            )b on concat(kode_comp,nocab) = b.kode        
        ";
        $proses_insert_stock_level = $this->db->query($sql_insert_stock_level);

        $sql_update_stock_level = "
            update db_rofo.t_insert_git a
            set a.stock_level = (
                select b.sl
                from db_rofo.t_stock_level_temp b
                where left(a.kode,3) = b.kode_comp and a.kodeprod = b.kodeprod and b.id = $id
            )where a.id = $id
        ";
        $proses_stock_level = $this->db->query($sql_update_stock_level);

        $sql_update_stock_dp_null = "
            update db_rofo.t_insert_git a
            set a.stock = (
                select b.stock
                from db_rofo.t_insert_stock_from_file b
                where b.kode = a.kode and a.kodeprod = b.kodeprod
            )where a.id = $id
        ";
        $proses_stock_dp_null = $this->db->query($sql_update_stock_dp_null);

        $sql_update_total_stock = "
        update db_rofo.t_insert_git a
            set a.total_stock = if(a.stock is null, 0, a.stock) + if(a.git is null, 0, a.git) + if(a.po_belum_do is null, 0, a.po_belum_do) + if(a.po_berjalan is null, 0, a.po_berjalan)
        where a.id = $id
        ";
        $proses_update_total_stock = $this->db->query($sql_update_total_stock);

        $sql_update_est_1 = "
        update db_rofo.t_insert_git a
            set a.est_1 = (a.total_stock) - if(a.target_1 is null, 0, a.target_1)
        where a.id = $id
        ";
        $proses_update_est_1 = $this->db->query($sql_update_est_1);

        $sql_update_stock_level_unit = "
        update db_rofo.t_insert_git a
            set a.stock_level_unit = if(a.avg is null, 0, a.avg) / 30 * (if(a.stock_level is null, 0, a.stock_level))
        where a.id = $id
        ";
        $proses_stock_level_unit = $this->db->query($sql_update_stock_level_unit);

        $sql_update_purchase_plan = "
        update db_rofo.t_insert_git a
            set a.purchase_plan = if((a.stock_level_unit) - (a.est_1) < 0, 0, (a.stock_level_unit)-(a.est_1)) 
        where a.id = $id
        ";
        $proses_update_purchase_plan = $this->db->query($sql_update_purchase_plan);

        $sql_update_est_2 = "
        update db_rofo.t_insert_git a
		    set a.est_2 = (a.est_1) - if((a.target_2) is null, 0, (a.target_2))
        where a.id = $id
        ";
        $proses_update_est_2 = $this->db->query($sql_update_est_2);

        // $sql = "select * from db_rofo.t_insert_git where tahun='2020' and bulan='5' and supp ='001' and id=$id";
        // $proses= $this->db->query($sql)->result();
        // if ($proses) {
        //     return $proses;
        // }else{
        //     return array();
        // }

    }

    public function get_rofo(){

      $id = $this->session->userdata('id');
  
      $sql = "
        select 	a.supp,a.kode, a.branch_name,a.nama_comp,a.kodeprod,a.namaprod,a.avg,a.stock, a.po_belum_do,a.git,a.po_berjalan,a.total_stock,
                a.target_1,a.est_1, a.target_2, a.est_2,
                a.stock_level, a.stock_level_unit, a.purchase_plan
        from    db_rofo.t_insert_git a
        where a.id = $id
        ORDER BY a.branch_name, a.kodeprod
      ";
      $proses = $this->db->query($sql)->result();

      if ($proses) {
          return $proses;
      }else{
          return array();
      }
    }

    function insert_sl($data)
	{
        $id = $this->session->userdata('id');
        $sql_del = "delete from db_rofo.t_insert_sl_from_file where id = $id";
        $proses_del = $this->db->query($sql_del);

        // $this->db->query("truncate db_import.t_import");
        $this->db->insert_batch('db_rofo.t_insert_sl_from_file', $data);

        $sql_update_sl = "
            update db_rofo.t_insert_git a
            set a.stock_level = (                
                SELECT b.sl
                FROM
                (
                    select  if(length(a.kodeprod) = 5, concat('0',a.kodeprod), a.kodeprod) as kodeprod, a.kode, a.sl
                    from    db_rofo.t_insert_sl_from_file a
                    where   a.id = $id
                )b where a.kode = b.kode and a.kodeprod = b.kodeprod
            ) where a.id = $id
        ";
        $proses_update_sl = $this->db->query($sql_update_sl);
        
        $sql_update_isi_satuan = "
            update db_rofo.t_insert_git a
            set a.isi_satuan = (
                select b.ISISATUAN
                from mpm.tabprod b
                where a.kodeprod = b.kodeprod
            )
            where a.id = $id
        ";
        $proses_update_satuan = $this->db->query($sql_update_isi_satuan);

        $sql_update_stock_level_unit = "
        update db_rofo.t_insert_git a
            set a.stock_level_unit = if(a.avg is null, 0, a.avg) / 30 * (if(a.stock_level is null, 0, a.stock_level))
        where a.id = $id
        ";
        $proses_stock_level_unit = $this->db->query($sql_update_stock_level_unit);

        $sql_update_purchase_plan = "
        update db_rofo.t_insert_git a
            set a.purchase_plan = if((a.stock_level_unit) - (a.est_2) < 0, 0, (a.stock_level_unit)-(a.est_2)) 
        where a.id = $id
        ";
        $proses_update_purchase_plan = $this->db->query($sql_update_purchase_plan);

        $sql_update_purchase_karton = "
            update db_rofo.t_insert_git a
            set a.purchase_plan_karton = round(purchase_plan / isi_satuan)
            where a.id = $id
        ";
        $proses_update_purchase_karton = $this->db->query($sql_update_purchase_karton);

        $sql_update_volume = "
            update db_rofo.t_insert_git a
            set a.volume = (
                select b.volume
                from db_rofo.t_volume b
                where a.kodeprod = b.kodeprod
            ),a.berat = (
                select b.berat
                from db_rofo.t_volume b
                where a.kodeprod = b.kodeprod
            )
            where a.id = $id
        ";
        $proses_update_volume = $this->db->query($sql_update_volume);

        $sql_update_purchase_volume = "
            update db_rofo.t_insert_git a
            set a.purchase_plan_volume = purchase_plan_karton * volume
            where a.id = $id
        ";
        $proses_update_purchase_volume = $this->db->query($sql_update_purchase_volume);

        $sql_update_purchase_berat = "
            update db_rofo.t_insert_git a
            set a.purchase_plan_berat = purchase_plan_karton * berat
            where a.id = $id
        ";
        $proses_update_purchase_berat = $this->db->query($sql_update_purchase_berat);

        $sql_update_est_3 = "
            update db_rofo.t_insert_git a
            set a.est_3 = (a.est_1) + (a.purchase_plan) - (a.target_2)
            where a.id = $id
        ";
        $proses_update_est_3 = $this->db->query($sql_update_est_3);

        $sql_update_est_doi = "
            update db_rofo.t_insert_git a
            set a.est_doi = (a.est_3) / if(a.avg is null, 0, a.avg) * 30
            where a.id = $id
        ";
        $proses_update_est_doi = $this->db->query($sql_update_est_doi);

        $sql_update_est_4 = "
            update db_rofo.t_insert_git a
            set a.est_4 = (a.est_3) - (a.target_3)
            where a.id = $id
        ";
        $proses_update_est_4 = $this->db->query($sql_update_est_4);


        $sql_update_purchase_plan_2 = "
        update db_rofo.t_insert_git a
            set a.purchase_plan_2 = if((a.stock_level_unit) - (a.est_4) < 0, 0, (a.stock_level_unit)-(a.est_4)) 
        where a.id = $id
        ";
        $proses_update_purchase_plan_2 = $this->db->query($sql_update_purchase_plan_2);

        $sql_update_purchase_plan_2_karton = "
            update db_rofo.t_insert_git a
            set a.purchase_plan_2_karton = round(purchase_plan_2 / isi_satuan)
            where a.id = $id
        ";
        $proses_update_purchase_2_karton = $this->db->query($sql_update_purchase_plan_2_karton);

        $sql_update_purchase_2_volume = "
            update db_rofo.t_insert_git a
            set a.purchase_plan_2_volume = purchase_plan_2_karton * volume
            where a.id = $id
        ";
        $proses_update_purchase_2_volume = $this->db->query($sql_update_purchase_2_volume);

        $sql_update_purchase_2_berat = "
            update db_rofo.t_insert_git a
            set a.purchase_plan_2_berat = purchase_plan_2_karton * berat
            where a.id = $id
        ";
        $proses_update_purchase_2_berat = $this->db->query($sql_update_purchase_2_berat);

        $sql_update_est_5 = "
            update db_rofo.t_insert_git a
            set a.est_5 = (a.est_3) + (a.purchase_plan_2) - (a.target_3)
            where a.id = $id
        ";
        $proses_update_est_5 = $this->db->query($sql_update_est_5);

        $sql_update_est_doi_2 = "
            update db_rofo.t_insert_git a
            set a.est_doi_2 = (a.est_5) / if(a.avg is null, 0, a.avg) * 30
            where a.id = $id
        ";
        $proses_update_est_doi_2 = $this->db->query($sql_update_est_doi_2);

        $sql_update_est_6 = "
            update db_rofo.t_insert_git a
            set a.est_6 = (a.est_5) - (a.target_4)
            where a.id = $id
        ";
        $proses_update_est_6 = $this->db->query($sql_update_est_6);

        $sql_update_purchase_plan_3 = "
        update db_rofo.t_insert_git a
            set a.purchase_plan_3 = if((a.stock_level_unit) - (a.est_6) < 0, 0, (a.stock_level_unit)-(a.est_6)) 
        where a.id = $id
        ";
        $proses_update_purchase_plan_3 = $this->db->query($sql_update_purchase_plan_3);

        $sql_update_purchase_plan_3_karton = "
            update db_rofo.t_insert_git a
            set a.purchase_plan_3_karton = round(purchase_plan_3 / isi_satuan)
            where a.id = $id
        ";
        $proses_update_purchase_3_karton = $this->db->query($sql_update_purchase_plan_3_karton);

        $sql_update_purchase_3_volume = "
            update db_rofo.t_insert_git a
            set a.purchase_plan_3_volume = purchase_plan_3_karton * volume
            where a.id = $id
        ";
        $proses_update_purchase_3_volume = $this->db->query($sql_update_purchase_3_volume);

        $sql_update_purchase_3_berat = "
            update db_rofo.t_insert_git a
            set a.purchase_plan_3_berat = purchase_plan_3_karton * berat
            where a.id = $id
        ";
        $proses_update_purchase_3_berat = $this->db->query($sql_update_purchase_3_berat);

        $sql_update_est_7 = "
            update db_rofo.t_insert_git a
            set a.est_7 = (a.est_5) + (a.purchase_plan_3) - (a.target_4)
            where a.id = $id
        ";
        $proses_update_est_7 = $this->db->query($sql_update_est_7);
        
        $sql_update_est_doi_3 = "
            update db_rofo.t_insert_git a
            set a.est_doi_3 = (a.est_7) / if(a.avg is null, 0, a.avg) * 30
            where a.id = $id
        ";
        $proses_update_est_doi_3 = $this->db->query($sql_update_est_doi_3);

//         update db_rofo.t_insert_git a
// set a.stock = (
// 	select b.stock
// 	from db_rofo.t_insert_stock_from_file b
// 	where b.kode = a.kode and a.kodeprod = b.kodeprod
// )

    }



}