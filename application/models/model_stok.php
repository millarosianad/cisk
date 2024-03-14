<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_stok extends CI_Model
{
    public function stok_product($data){
        $id = $this->session->userdata('id');
        date_default_timezone_set('Asia/Jakarta');        
        $created_date='"'.date('Y-m-d H:i:s').'"';
        // echo $created_date;
        // die;
        $tahun = $data ['tahun'];
        $breakdown =$data ['bd'];
        $kodeprod = $data['kodeprod'];
        $wilayah_nocab = $data['wilayah_nocab'];

        if ($breakdown == 1) {
            $bd = ",kodeprod";
        }else{
            $bd = "";
        }


        if ($wilayah_nocab == null) {
            $wilayah = '';
        }else{
            $wilayah = "and nocab in ($wilayah_nocab)";
        }

        // $sql_insert_temp = "insert into site.temp_stock_product ('created_at')";


        /* Insert db_temp.t_temo_stok*/
        $sql = "
                insert into site.temp_stock_product
                select  a.nocab, b.sub, b.branch_name, b.nama_comp, c.namasupp, a.kodeprod, c.namaprod, c.grup, c.nama_group,c.subgroup, c.nama_sub_group, h_dp,
                        sum(b1),sum(b2),sum(b3),sum(b4),sum(b5),sum(b6),sum(b7),sum(b8),sum(b9),sum(b10),sum(b11),sum(b12), 
                        h_dp,sum(b1*h_dp) as v1,sum(b2*h_dp) as v2, sum(b3*h_dp) as v3, sum(b4*h_dp) as v4,sum(b5*h_dp) as v5,sum(b6*h_dp) as v6,
                        sum(b7*h_dp) as v7,sum(b8*h_dp) as v8,sum(b9*h_dp) as v9,sum(b10*h_dp) as v10,sum(b11*h_dp) as v11,sum(b12*h_dp) as v12,
                        $id, urutan, $created_date
                FROM
                (
                    select  nocab,
                            a.kodeprod as kodeprod,
                            a.namaprod as namaprod
                            ,sum(if(bulan=01,stok,0))*1 as b1
                            ,sum(if(bulan=02,stok,0))*1 as b2
                            ,sum(if(bulan=03,stok,0))*1 as b3
                            ,sum(if(bulan=04,stok,0))*1 as b4
                            ,sum(if(bulan=05,stok,0))*1 as b5
                            ,sum(if(bulan=06,stok,0))*1 as b6
                            ,sum(if(bulan=07,stok,0))*1 as b7
                            ,sum(if(bulan=08,stok,0))*1 as b8
                            ,sum(if(bulan=09,stok,0))*1 as b9
                            ,sum(if(bulan=10,stok,0))*1 as b10
                            ,sum(if(bulan=11,stok,0))*1 as b11
                            ,sum(if(bulan=12,stok,0))*1 as b12
                    from
                    (
                        select  nocab,kodeprod,namaprod,substr(bulan,3) as bulan,
                                sum((Saldoawal+masuk_pbk+retur_sal+retur_depo)-(sales+minta_depo)) as stok
                        from    data$tahun.st 
                        where kodeprod in($kodeprod) $wilayah and kode_gdg in ('pst',1)
                        group by nocab, kodeprod,bulan
                        order by kodeprod
                    )a GROUP BY nocab, kodeprod
                )a LEFT JOIN
                (
                    select a.kode,a.branch_name,a.nama_comp,a.kode_comp,a.nocab,a.urutan, a.sub
                    from 
                    (
                        select  concat(a.kode_comp,a.nocab) as kode, a.branch_name, a.nama_comp, a.kode_comp,a.nocab,a.urutan,a.sub
                        FROM    mpm.tbl_tabcomp a
                        where   `status` = 1
                        GROUP BY kode
                    )a INNER join 
                    (
                        select if(concat(a.kode_comp, a.nocab) = 'JBG46' OR concat(a.kode_comp, a.nocab) = 'MJK46','SDO46',concat(a.kode_comp, a.nocab)) as kode
                        from db_dp.t_dp a
                        where a.tahun = $tahun
                        GROUP BY kode
                    )b on a.kode = b.kode
                )b on a.nocab = b.nocab
                INNER JOIN
                (
                    select  a.kodeprod, namaprod,b.h_dp, a.grup, c.nama_group, a.supp, a.subgroup, d.nama_sub_group, e.namasupp
                    FROM    mpm.tabprod a LEFT JOIN (                        
                        select 	b.h_dp as h_dp, b.kodeprod
                        from 		mpm.prod_detail b
                        where		b.tgl = (
                            select  max(tgl) 
                            from    mpm.prod_detail c
                            where   b.kodeprod=c.kodeprod
                        ) 
                    )b on a.kodeprod = b.kodeprod left join 
                    (
                        select	a.kode_group, a.nama_group
                        from	mpm.tbl_group a
                    )c on a.grup = c.kode_group LEFT JOIN 
                    (
                        select a.sub_group, a.nama_sub_group
                        from db_produk.t_sub_group a
                    )d on a.subgroup = d.sub_group LEFT JOIN mpm.tabsupp e
                        on a.supp = e.supp
                )c on a.kodeprod = c.kodeprod
                        GROUP BY nocab $bd
                ORDER BY urutan asc, nocab asc $bd
        ";

                

                // echo "<pre><br><br><br>";
                // print_r($sql);
                // echo "</pre>";

        /* PROSES TAMPIL KE WEBSITE */
        $proses = $this->db->query($sql);
        if ($proses) {
            $hasil = $this->db->query("select * from site.temp_stock_product where id = $id and created_date = $created_date")->result();
            return $hasil;
        }else{
            return array();
        }
    }
}