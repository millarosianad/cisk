<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_loyalty extends CI_Model 
{
    public function get_herbal_sm_1(){
        $id = $this->session->userdata('id');

        // $this->db->query("delete from db_loyalty.t_report_loyalty_sm1_antangin_group");

        // $sql = "
        // insert into db_loyalty.t_report_loyalty_sm1_antangin_group
        // select 	b.kode,f.branch_name,f.nama_comp,
        //         a.kode_lang, a.kode_lang_alternatif, b.kode_lang, e.nama_lang,e.alamat,
        //         a.target_sm1_2020, a.target_produk_fokus_sm1_2020,
        //         c.b1 as b1_2020,c.b2 as b2_2020,c.b3 as b3_2020,c.b4 as b4_2020,c.b5 as b5_2020,c.b6 as b6_2020,
        //         (c.b1+c.b2+c.b3+c.b4+c.b5+c.b6) as actual_sm1_2020,
        //         ((c.b1+c.b2+c.b3+c.b4+c.b5+c.b6) / a.target_sm1_2020 * 100) as ach, 
        //         ((c.b1+c.b2+c.b3+c.b4+c.b5+c.b6) - a.target_sm1_2020) as gap_actual_vs_target,
        //         d.b1 as b1_fokus_2020,d.b2 as b2_fokus_2020,d.b3 as b3_fokus_2020,d.b4 as b4_fokus_2020,d.b5 as b5_fokus_2020,d.b6 as b6_fokus_2020,
        //         (d.b1+d.b2+d.b3+d.b4+d.b5+d.b6) as actual_fokus_sm1_2020,
        //         ((d.b1+d.b2+d.b3+d.b4+d.b5+d.b6) / a.target_produk_fokus_sm1_2020 * 100) as ach_fokus,
        //         a.paket_a,a.paket_b,a.paket_c,a.paket_d,a.paket_e,
        //         (a.paket_a * 21375000) as hadiah_a,
        //         (a.paket_b * 10500000) as hadiah_b,
        //         (a.paket_c * 7800000) as hadiah_c,
        //         (a.paket_d * 4950000) as hadiah_d,
        //         (a.paket_e * 2700000) as hadiah_e,
        //         ((a.paket_a * 21375000) + (a.paket_b * 10500000) + (a.paket_c * 7800000) + (a.paket_d * 4950000) + (a.paket_e * 2700000)) as total_hadiah,
        //         297
        // from 	db_loyalty.t_mentah a LEFT JOIN db_loyalty.t_mentah_mapping_2 b
        //             on 	a.dp = b.dp and 
        //                 a.kode_lang = b.kode_lang_excel and 
        //                 a.kode_lang_alternatif = b.kode_lang_excel_alternatif LEFT JOIN 
        // (
        //     select a.kode,a.outlet,b1,b2,b3,b4,b5,b6
        //     FROM
        //     (
        //         select a.kode, a.outlet, sum(b1) as b1,sum(b2) as b2,sum(b3) as b3,sum(b4) as b4,sum(b5) as b5,sum(b6) as b6
        //         FROM
        //         (
        //             select 	a.kode, if(a.outlet = 'LAN100001','LAN101461',a.outlet) as outlet, 
        //                     b1,b2,b3,b4,b5,b6
        //             FROM
        //             (
        //                 select 	kode, outlet, 
        //                         sum(if(bulan = 1, tot1, 0)) as b1,
        //                         sum(if(bulan = 2, tot1, 0)) as b2,
        //                         sum(if(bulan = 3, tot1, 0)) as b3,
        //                         sum(if(bulan = 4, tot1, 0)) as b4,
        //                         sum(if(bulan = 5, tot1, 0)) as b5,
        //                         sum(if(bulan = 6, tot1, 0)) as b6
        //                 FROM
        //                 (
        //                     select  concat(a.kode_comp,a.nocab) as kode, concat(a.kode_comp,a.kode_lang) as outlet, tot1, bulan
        //                     from    data2020.fi a
        //                     where   a.kodeprod in (010074,010070,010002,010001,010075,010063,010071)
        //                             and a.bulan in (1,2,3,4,5,6)
        //                     union ALL
        //                     select  concat(a.kode_comp,a.nocab) as kode, concat(a.kode_comp,a.kode_lang) as outlet, tot1, bulan
        //                     from    data2020.ri a
        //                     where   a.kodeprod in (010074,010070,010002,010001,010075,010063,010071)
        //                             and a.bulan in (1,2,3,4,5,6)
        //                 )a GROUP BY kode, outlet
        //             )a 
        //         )a GROUP BY outlet
        //     )a
        // )c on b.kode_lang = c.outlet LEFT JOIN
        // (
        //     select a.kode, outlet, b1,b2,b3,b4,b5,b6
        //     FROM
        //     (
        //         select 	kode, outlet, 
        //                 sum(if(bulan = 1, tot1, 0)) as b1,
        //                 sum(if(bulan = 2, tot1, 0)) as b2,
        //                 sum(if(bulan = 3, tot1, 0)) as b3,
        //                 sum(if(bulan = 4, tot1, 0)) as b4,
        //                 sum(if(bulan = 5, tot1, 0)) as b5,
        //                 sum(if(bulan = 6, tot1, 0)) as b6
        //         FROM
        //         (
        //                 select  concat(a.kode_comp,a.nocab) as kode, concat(a.kode_comp,a.kode_lang) as outlet, tot1, bulan
        //                 from    data2020.fi a
        //                 where   a.kodeprod in (010002,010001,010075,010063,010071)
        //                             and a.bulan in (1,2,3,4,5,6)
        //                 union ALL
        //                 select  concat(a.kode_comp,a.nocab) as kode, concat(a.kode_comp,a.kode_lang) as outlet, tot1, bulan
        //                 from    data2020.ri a
        //                 where   a.kodeprod in (010002,010001,010075,010063,010071)
        //                             and a.bulan in (1,2,3,4,5,6)
        //         )a GROUP BY kode, outlet
        //     )a
        // )d on b.kode_lang = d.outlet LEFT JOIN
        // (
        //     select a.kode, a.kode_lang, a.nama_lang, a.alamat 
        //     from mpm.tbl_bantu_pelanggan_2020 a
        // )e on b.kode_lang = e.kode_lang LEFT JOIN
        // (
        //     select concat(a.kode_comp,a.nocab) as kode, a.branch_name, a.nama_comp, a.urutan
        //     from mpm.tbl_tabcomp a
        //     where a.`status` = 1
        //     GROUP BY concat(a.kode_comp,a.nocab)
        // )f on b.kode = f.kode
        // ";
        // echo "<pre>";
        // echo "<br><br><br><br><br>";
        // print_r($sql);
        // echo "</pre>";

        // $proses =$this->db->query($sql);
        $hasil = $this->db->query("select * from db_loyalty.t_report_loyalty_sm1_antangin_group")->result();

        if ($hasil) {
            return $hasil;
        }else{
            return array();
        }
    }

    public function get_ob_herbal(){
        $id = $this->session->userdata('id');

    //     $this->db->query("delete from db_loyalty.t_report_loyalty_sm1_ob_herbal");

    //     $sql = "
    //     insert into db_loyalty.t_report_loyalty_sm1_ob_herbal
    //     select 	b.kode, e.branch_name,e.nama_comp,a.kode_lang,a.kode_lang_alternatif,
	// 			b.kode_lang_web, d.nama_lang,d.alamat, b.total_sales_2019, b.avg, 
	// 			b.kelas, b.ajuan_kelas_baru, b.rata_target_baru, b.growth * 100 as growth, 
	// 			b.total_target_3_bulan, b.bonus_cash_back * 100 as bonus_cash_back, b.value_cash_back, b.note,
	// 			b.class,b.target, c.b4,c.b5,c.b6, (c.b4 + c.b5 + c.b6) as total,
	// 			((c.b4 + c.b5 + c.b6) / b.target * 100) as ach, ((c.b4 + c.b5 + c.b6) - b.target) as gap,297
    // from 		db_loyalty.t_mentah_obherbal a LEFT JOIN db_loyalty.t_mentah_obherbal_mapping b
    //             on  a.dp = b.dp and a.kode_lang = b.kode_lang and 
    //                 a.kode_lang_alternatif = b.kode_lang_alternatif LEFT JOIN 
    //                 (
    //                     select a.kode,a.outlet,b4,b5,b6
    //                     FROM
    //                     (
    //                         select a.kode, a.outlet, sum(b4) as b4,sum(b5) as b5,sum(b6) as b6
    //                         FROM
    //                         (
    //                             select 	a.kode, if(a.outlet = 'LAN100001','LAN101461',a.outlet) as outlet, 
    //                                     b1,b2,b3,b4,b5,b6
    //                             FROM
    //                             (
    //                                 select 	kode, outlet, 
    //                                         sum(if(bulan = 1, tot1, 0)) as b1,
    //                                         sum(if(bulan = 2, tot1, 0)) as b2,
    //                                         sum(if(bulan = 3, tot1, 0)) as b3,
    //                                         sum(if(bulan = 4, tot1, 0)) as b4,
    //                                         sum(if(bulan = 5, tot1, 0)) as b5,
    //                                         sum(if(bulan = 6, tot1, 0)) as b6
    //                                 FROM
    //                                 (
    //                                     select  concat(a.kode_comp,a.nocab) as kode, concat(a.kode_comp,a.kode_lang) as outlet, tot1, bulan
    //                                     from    data2020.fi a
    //                                     where   a.kodeprod in (010029,010030,010031,010100,010101,010061,010086,010087,010088)
    //                                             and a.bulan in (4,5,6)
    //                                     union ALL
    //                                     select  concat(a.kode_comp,a.nocab) as kode, concat(a.kode_comp,a.kode_lang) as outlet, tot1, bulan
    //                                     from    data2020.ri a
    //                                     where   a.kodeprod in (010029,010030,010031,010100,010101,010061,010086,010087,010088)
    //                                             and a.bulan in (4,5,6)
    //                                 )a GROUP BY kode, outlet
    //                             )a 
    //                         )a GROUP BY outlet
    //                     )a
    //                 )c on b.kode_lang_web = c.outlet LEFT JOIN
    //                 (
    //                     select a.kode, a.kode_lang, a.nama_lang, a.alamat 
    //                     from mpm.tbl_bantu_pelanggan_2020 a
    //                 )d on b.kode_lang_web = d.kode_lang LEFT JOIN
    //                 (
    //                     select concat(a.kode_comp,a.nocab) as kode, a.branch_name, a.nama_comp, a.urutan
    //                     from mpm.tbl_tabcomp a
    //                     where a.`status` = 1
    //                     GROUP BY concat(a.kode_comp,a.nocab)
    //                 )e on b.kode = e.kode
    //     ";
    //     echo "<pre>";
    //     echo "<br><br><br><br><br>";
    //     print_r($sql);
    //     echo "</pre>";

    //     $proses =$this->db->query($sql);
        $hasil = $this->db->query("select * from db_loyalty.t_report_loyalty_sm1_ob_herbal")->result();

        if ($hasil) {
            return $hasil;
        }else{
            return array();
        }
    }

    public function get_candy(){
        $id = $this->session->userdata('id');

        // $this->db->query("delete from db_loyalty.t_report_loyalty_sm1_candy");

        // $sql = "
        // insert into db_loyalty.t_report_loyalty_sm1_candy
        // select 	b.kode, e.branch_name,e.nama_comp,a.kode_lang,a.kode_lang_alternatif,
		// 		b.kode_lang_web, d.nama_lang,d.alamat, b.total_sales_2019, b.avg, 
		// 		b.kelas, b.ajuan_kelas_baru, b.rata_target_baru, b.growth * 100 as growth, 
		// 		b.total_target_3_bulan, b.bonus_cash_back * 100 as bonus_cash_back, b.value_cash_back, b.note,
		// 		b.class,b.target, c.b1, c.b2, c.b3, c.b4,c.b5,c.b6, (c.b1 + c.b2 + c.b3 + c.b4 + c.b5 + c.b6) as total,
		// 		((c.b1 + c.b2 + c.b3 + c.b4 + c.b5 + c.b6) / b.target * 100) as ach, ((c.b1 + c.b2 + c.b3 + c.b4 + c.b5 + c.b6) - b.target) as gap,297
        // from 	db_loyalty.t_mentah_candy a LEFT JOIN db_loyalty.t_mentah_candy_mapping b
        //             on  a.dp = b.dp and a.kode_lang = b.kode_lang and 
        //                 a.kode_lang_alternatif = b.kode_lang_alternatif and a.nama_lang = b.nama_lang LEFT JOIN 
        //                 (
        //                     select a.kode,a.outlet,b1,b2,b3,b4,b5,b6
        //                     FROM
        //                     (
        //                         select a.kode, a.outlet, sum(b1) as b1,sum(b2) as b2,sum(b3) as b3, sum(b4) as b4,sum(b5) as b5,sum(b6) as b6
        //                         FROM
        //                         (
        //                             select 	a.kode, if(a.outlet = 'LAN100001','LAN101461',a.outlet) as outlet, 
        //                                     b1,b2,b3,b4,b5,b6
        //                             FROM
        //                             (
        //                                 select 	kode, outlet, 
        //                                         sum(if(bulan = 1, tot1, 0)) as b1,
        //                                         sum(if(bulan = 2, tot1, 0)) as b2,
        //                                         sum(if(bulan = 3, tot1, 0)) as b3,
        //                                         sum(if(bulan = 4, tot1, 0)) as b4,
        //                                         sum(if(bulan = 5, tot1, 0)) as b5,
        //                                         sum(if(bulan = 6, tot1, 0)) as b6
        //                                 FROM
        //                                 (
        //                                         select  concat(a.kode_comp,a.nocab) as kode, concat(a.kode_comp,a.kode_lang) as outlet, tot1, bulan
        //                                         from    data2020.fi a
        //                                         where   a.kodeprod in (010095,010102,010105,010069,600001,010081,010067,010046,010048,010052,010054,010051,010053,010068,010078,010073,700009,600002,010039,010097,010049,010040,010092,010089,010093,010090,010094,700012,010096,010098)
        //                                                 and a.bulan in (1,2,3,4,5,6)
        //                                         union ALL
        //                                         select  concat(a.kode_comp,a.nocab) as kode, concat(a.kode_comp,a.kode_lang) as outlet, tot1, bulan
        //                                         from    data2020.ri a
        //                                         where   a.kodeprod in (010095,010102,010105,010069,600001,010081,010067,010046,010048,010052,010054,010051,010053,010068,010078,010073,700009,600002,010039,010097,010049,010040,010092,010089,010093,010090,010094,700012,010096,010098)
        //                                                 and a.bulan in (1,2,3,4,5,6)
        //                                 )a GROUP BY kode, outlet
        //                             )a 
        //                         )a GROUP BY outlet
        //                     )a
        //                 )c on b.kode_lang_web = c.outlet LEFT JOIN
        //                 (
        //                     select a.kode, a.kode_lang, a.nama_lang, a.alamat 
        //                     from mpm.tbl_bantu_pelanggan_2020 a
        //                 )d on b.kode_lang_web = d.kode_lang LEFT JOIN
        //                 (
        //                     select concat(a.kode_comp,a.nocab) as kode, a.branch_name, a.nama_comp, a.urutan
        //                     from mpm.tbl_tabcomp a
        //                     where a.`status` = 1
        //                     GROUP BY concat(a.kode_comp,a.nocab)
        //                 )e on b.kode = e.kode
        // ";
        // echo "<pre>";
        // echo "<br><br><br><br><br>";
        // print_r($sql);
        // echo "</pre>";

        // $proses =$this->db->query($sql);
        $hasil = $this->db->query("select * from db_loyalty.t_report_loyalty_sm1_candy")->result();

        if ($hasil) {
            return $hasil;
        }else{
            return array();
        }
    }

    public function get_sales_q3()
    {
        date_default_timezone_set('Asia/Jakarta');        
        $tgl_created='"'.date('Y-m-d H:i:s').'"';
        $id = $this->session->userdata('id');

        // $this->db->query('truncate db_loyalty.t_loyalty_sales');
        // $this->db->query('truncate db_loyalty.t_loyalty_sales_antangin_group');
        // $this->db->query('truncate db_loyalty.t_loyalty_mapping_q3_antangin_group');
        // $this->db->query('truncate db_loyalty.t_loyalty_ant_group_q3_report');

        // $sql = "
        //     insert into db_loyalty.t_loyalty_sales
        //     select kode, outlet, banyak, tot1, bulan, 'q3', 'antangin_group',$tgl_created,$id 
        //     FROM
        //     (
        //         select  concat(a.kode_comp,a.nocab) as kode, concat(a.kode_comp,a.kode_lang) as outlet,banyak,tot1,bulan
        //         from    data2020.fi a
        //         where   a.kodeprod in (010074,010022,010070,010103)
        //                 and a.bulan in (7,8,9) 
        //         union ALL
        //         select  concat(a.kode_comp,a.nocab) as kode, concat(a.kode_comp,a.kode_lang) as outlet,banyak,tot1,bulan
        //         from    data2020.ri a
        //         where   a.kodeprod in (010074,010022,010070,010103)
        //                 and a.bulan in (7,8,9)
        //     )a
        //     union all
        //     select kode, outlet, banyak, tot1, bulan, 'q3', 'antangin_group_fokus',$tgl_created,$id 
        //     FROM
        //     (
        //         select  concat(a.kode_comp,a.nocab) as kode, concat(a.kode_comp,a.kode_lang) as outlet,banyak,tot1,bulan
        //         from    data2020.fi a
        //         where   a.kodeprod in (010104,010001,010002,010071,010063,010075,010077)
        //                 and a.bulan in (7,8,9) 
        //         union ALL
        //         select  concat(a.kode_comp,a.nocab) as kode, concat(a.kode_comp,a.kode_lang) as outlet,banyak,tot1,bulan
        //         from    data2020.ri a
        //         where   a.kodeprod in (010104,010001,010002,010071,010063,010075,010077)
        //                 and a.bulan in (7,8,9)
        //     )a

        // ";

        // $proses = $this->db->query($sql);

        // $sql = "
        // insert into db_loyalty.t_loyalty_sales_antangin_group
        // select 	kode, outlet, 
        //         sum(if(bulan = 7 and `group` = 'antangin_group',tot1,0)) as b7,
        //         sum(if(bulan = 8 and `group` = 'antangin_group',tot1,0)) as b8,
        //         sum(if(bulan = 9 and `group` = 'antangin_group',tot1,0)) as b9,
        //         sum(if(bulan = 7 and `group` = 'antangin_group_fokus',tot1,0)) as b7_fokus,
        //         sum(if(bulan = 8 and `group` = 'antangin_group_fokus',tot1,0)) as b8_fokus,
        //         sum(if(bulan = 9 and `group` = 'antangin_group_fokus',tot1,0)) as b9_fokus,$tgl_created,$id 
        // from 
        // (
        //     select a.kode, a.outlet, a.banyak, a.tot1, bulan, periode, `group`
        //     from db_loyalty.t_loyalty_sales a
        // )a GROUP BY kode,outlet
        // ";
        // $proses = $this->db->query($sql);

        // $sql = "
        //     insert into db_loyalty.t_loyalty_mapping_q3_antangin_group
        //     select 	a.dp, a.kode_outlet, a.kode_outlet_alternatif, a.nama_outlet, 
        //             b.kode as kode_lang_web,b.nama_lang,
        //             a.jan, a.feb, a.mar, a.apr, a.mei, a.jun, a.actual_semester_1, 
        //             a.average, a.growth_20persen, a.est_target_q3, 
        //             a.paket_s, a.paket_a, a.paket_b, a.paket_c, a.paket_d, a.paket_e,
        //             a.total_target_q3, a.target_produk_fokus, a.growth,$tgl_created,$id
        //     from db_loyalty.t_loyalty_master_q3_antangin_group a LEFT JOIN
        //     (
        //         select a.kode, a.nama_lang, a.alamat 
        //         from db_lang.tbl_bantu_pelanggan_2020 a
        //     )b on a.kode_outlet = b.kode
        // ";
        // $proses = $this->db->Query($sql);

        // $sql = "
        // insert into db_loyalty.t_loyalty_ant_group_q3_report
        // select 	b.kode,c.branch_name,c.nama_comp, a.dp,a.kode_outlet,a.kode_outlet_alternatif,a.nama_outlet,a.      
        //         kode_lang_web,a.nama_lang_web,
        //         a.jan,a.feb,a.mar,a.apr,a.mei,a.jun,a.actual_semester_1,a.average,a.growth_20persen,a.est_target_q3,
        //         a.paket_s,a.paket_a,a.paket_b,a.paket_c,a.paket_d,a.paket_e,a.total_target_q3,a.target_produk_fokus,a.growth,
        //         b.b7,b.b8,b.b9,b.total_actual_q3,
        //         (b.total_actual_q3/a.total_target_q3) as ach, (b.total_actual_q3-a.total_target_q3) as gap_actual_vs_target,
        //         b.b7_fokus,b.b8_fokus,b.b9_fokus, b.total_fokus_actual_q3, (b.total_fokus_actual_q3/a.target_produk_fokus) as ach_fokus,
        //         (b.total_fokus_actual_q3-a.target_produk_fokus) as gap_actual_fokus_vs_target,
        //         (a.paket_s * 11600000) as lm_s,(a.paket_a * 5600000) as lm_a,(a.paket_b * 4100000) as lm_b,
        //         (a.paket_c * 3150000) as lm_c,(a.paket_d * 2075000) as lm_d,(a.paket_e * 1125000) as lm_e,
        //         (a.paket_s * 11600000) + (a.paket_a * 5600000) + (a.paket_b * 4100000) + (a.paket_c * 3150000) + (a.paket_d * 2075000) + (a.paket_e * 1125000) as total_lm,c.urutan, $tgl_created, $id
        // from db_loyalty.t_loyalty_mapping_q3_antangin_group a LEFT JOIN 
        // (
        //     select 	a.kode,a.outlet,a.b7,a.b8,a.b9,
        //             (a.b7 + a.b8 + a.b9) as total_actual_q3,
        //             a.b7_fokus,a.b8_fokus,a.b9_fokus,
        //             (a.b7_fokus + a.b8_fokus + a.b9_fokus) as total_fokus_actual_q3
        //     from db_loyalty.t_loyalty_sales_antangin_group a
        // )b on a.kode_lang_web = b.outlet LEFT JOIN
        // (
        //     select concat(a.kode_comp,a.nocab) as kode,a.branch_name,a.nama_comp,a.urutan
        //     from mpm.tbl_tabcomp a
        //     where a.`status` = 1
        //     GROUP BY concat(a.kode_comp,a.nocab)
        // )c on b.kode = c.kode
        
        // ";
        // $proses = $this->db->Query($sql);

        $tampil = $this->db->query("select * from db_loyalty.t_loyalty_ant_group_q3_report a order by urutan")->result();
        return $tampil;

    }    

}