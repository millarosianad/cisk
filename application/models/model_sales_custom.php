<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_sales_custom extends CI_Model 
{
    public function proses_omzetd2($data){
        $id = $this->session->userdata('id');
        $tahun = $data['tahun'];
        $sql_del = "delete from mpm.tbl_sales_custom where userid = $id";
        $proses_sql_del = $this->db->query($sql_del);
        $sql = "
            insert into mpm.tbl_sales_custom
            select	a.kode,b.nama_comp,b.sub, grup, c.nama_group,
                    sum(unit1) as unit1,
                    sum(unit2) as unit2,
                    sum(unit3) as unit3,
                    sum(unit4) as unit4,
                    sum(unit5) as unit5,
                    sum(unit6) as unit6,
                    sum(unit7) as unit7,
                    sum(unit8) as unit8,
                    sum(unit9) as unit9,
                    sum(unit10) as unit10,
                    sum(unit11) as unit11,
                    sum(unit12) as unit12,
                    sum(omzet1) as omzet1,
                    sum(omzet2) as omzet2,
                    sum(omzet3) as omzet3,
                    sum(omzet4) as omzet4,
                    sum(omzet5) as omzet5,
                    sum(omzet6) as omzet6,
                    sum(omzet7) as omzet7,
                    sum(omzet8) as omzet8,
                    sum(omzet9) as omzet9,
                    sum(omzet10) as omzet10,
                    sum(omzet11) as omzet11,
                    sum(omzet12) as omzet12,
                    sum(ot1) as ot1,
                    sum(ot2) as ot2,
                    sum(ot3) as ot3,
                    sum(ot4) as ot4,
                    sum(ot5) as ot5,
                    sum(ot6) as ot6,
                    sum(ot7) as ot7,
                    sum(ot8) as ot8,
                    sum(ot9) as ot9,
                    sum(ot10) as ot10,
                    sum(ot11) as ot11,
                    sum(ot12) as ot12, $id, b.urutan
            FROM
            (
                select 	kode, grup, kodeprod, 
                        sum(if(bulan = 1, unit, 0)) as unit1,
                        sum(if(bulan = 2, unit, 0)) as unit2,
                        sum(if(bulan = 3, unit, 0)) as unit3,
                        sum(if(bulan = 4, unit, 0)) as unit4,
                        sum(if(bulan = 5, unit, 0)) as unit5,
                        sum(if(bulan = 6, unit, 0)) as unit6,
                        sum(if(bulan = 7, unit, 0)) as unit7,
                        sum(if(bulan = 8, unit, 0)) as unit8,
                        sum(if(bulan = 9, unit, 0)) as unit9,
                        sum(if(bulan = 10, unit, 0)) as unit10,
                        sum(if(bulan = 11, unit, 0)) as unit11,
                        sum(if(bulan = 12, unit, 0)) as unit12,
                        sum(if(bulan = 1, omzet, 0)) as omzet1,
                        sum(if(bulan = 2, omzet, 0)) as omzet2,
                        sum(if(bulan = 3, omzet, 0)) as omzet3,
                        sum(if(bulan = 4, omzet, 0)) as omzet4,
                        sum(if(bulan = 5, omzet, 0)) as omzet5,
                        sum(if(bulan = 6, omzet, 0)) as omzet6,
                        sum(if(bulan = 7, omzet, 0)) as omzet7,
                        sum(if(bulan = 8, omzet, 0)) as omzet8,
                        sum(if(bulan = 9, omzet, 0)) as omzet9,
                        sum(if(bulan = 10, omzet, 0)) as omzet10,
                        sum(if(bulan = 11, omzet, 0)) as omzet11,
                        sum(if(bulan = 12, omzet, 0)) as omzet12,
                        sum(if(bulan = 1, ot, 0)) as ot1,
                        sum(if(bulan = 2, ot, 0)) as ot2,
                        sum(if(bulan = 3, ot, 0)) as ot3,
                        sum(if(bulan = 4, ot, 0)) as ot4,
                        sum(if(bulan = 5, ot, 0)) as ot5,
                        sum(if(bulan = 6, ot, 0)) as ot6,
                        sum(if(bulan = 7, ot, 0)) as ot7,
                        sum(if(bulan = 8, ot, 0)) as ot8,
                        sum(if(bulan = 9, ot, 0)) as ot9,
                        sum(if(bulan = 10, ot, 0)) as ot10,
                        sum(if(bulan = 11, ot, 0)) as ot11,
                        sum(if(bulan = 12, ot, 0)) as ot12
                FROM
                (
                        select 	kode,count(distinct(outlet)) as ot, 'G0102' as grup, a.kodeprod,sum(banyak) as unit,sum(tot1) as omzet, bulan
                        FROM
                        (
                                select nocab, kode_comp,bulan, `banyak`,tot1, CONCAT(KODE_COMP,kode_lang) as outlet,concat(kode_comp, nocab) as kode, kodeprod
                                from `data$tahun`.`fi`
                                where bulan in (1,2,3,4,5,6,7,8,9,10,11,12) and
                                kodeprod in (010069,600001,010081,010067,010046,010048,010052,010054,010051,010053,010068,010078,010073,700009,600002,010039,010097,010049,010040,010092,010089,010093,010090,010094,700012,010096,010098)
                                union all
                                select nocab, kode_comp, bulan, `banyak`,tot1, null,concat(kode_comp, nocab) as kode, kodeprod
                                from `data$tahun`.`ri`
                                where bulan in (1,2,3,4,5,6,7,8,9,10,11,12) and  
                                kodeprod in (010069,600001,010081,010067,010046,010048,010052,010054,010051,010053,010068,010078,010073,700009,600002,010039,010097,010049,010040,010092,010089,010093,010090,010094,700012,010096,010098)
                        )a 	
                        GROUP BY kode, bulan
                )a GROUP BY kode
                union ALL
                select 	kode, grup, kodeprod, 
                        sum(if(bulan = 1, unit, 0)) as unit1,
                        sum(if(bulan = 2, unit, 0)) as unit2,
                        sum(if(bulan = 3, unit, 0)) as unit3,
                        sum(if(bulan = 4, unit, 0)) as unit4,
                        sum(if(bulan = 5, unit, 0)) as unit5,
                        sum(if(bulan = 6, unit, 0)) as unit6,
                        sum(if(bulan = 7, unit, 0)) as unit7,
                        sum(if(bulan = 8, unit, 0)) as unit8,
                        sum(if(bulan = 9, unit, 0)) as unit9,
                        sum(if(bulan = 10, unit, 0)) as unit10,
                        sum(if(bulan = 11, unit, 0)) as unit11,
                        sum(if(bulan = 12, unit, 0)) as unit12,
                        sum(if(bulan = 1, omzet, 0)) as omzet1,
                        sum(if(bulan = 2, omzet, 0)) as omzet2,
                        sum(if(bulan = 3, omzet, 0)) as omzet3,
                        sum(if(bulan = 4, omzet, 0)) as omzet4,
                        sum(if(bulan = 5, omzet, 0)) as omzet5,
                        sum(if(bulan = 6, omzet, 0)) as omzet6,
                        sum(if(bulan = 7, omzet, 0)) as omzet7,
                        sum(if(bulan = 8, omzet, 0)) as omzet8,
                        sum(if(bulan = 9, omzet, 0)) as omzet9,
                        sum(if(bulan = 10, omzet, 0)) as omzet10,
                        sum(if(bulan = 11, omzet, 0)) as omzet11,
                        sum(if(bulan = 12, omzet, 0)) as omzet12,
                        sum(if(bulan = 1, ot, 0)) as ot1,
                        sum(if(bulan = 2, ot, 0)) as ot2,
                        sum(if(bulan = 3, ot, 0)) as ot3,
                        sum(if(bulan = 4, ot, 0)) as ot4,
                        sum(if(bulan = 5, ot, 0)) as ot5,
                        sum(if(bulan = 6, ot, 0)) as ot6,
                        sum(if(bulan = 7, ot, 0)) as ot7,
                        sum(if(bulan = 8, ot, 0)) as ot8,
                        sum(if(bulan = 9, ot, 0)) as ot9,
                        sum(if(bulan = 10, ot, 0)) as ot10,
                        sum(if(bulan = 11, ot, 0)) as ot11,
                        sum(if(bulan = 12, ot, 0)) as ot12
                FROM
                (
                        select 	kode,count(distinct(outlet)) as ot, 'G0103' as grup, a.kodeprod,sum(banyak) as unit,sum(tot1) as omzet, bulan
                        FROM
                        (
                                select nocab, kode_comp,bulan, `banyak`,tot1, CONCAT(KODE_COMP,kode_lang) as outlet,concat(kode_comp, nocab) as kode, kodeprod
                                from `data$tahun`.`fi`
                                where bulan in (1,2,3,4,5,6,7,8,9,10,11,12) and
                                    kodeprod in (010095,010102)
                                union all
                                select nocab, kode_comp, bulan, `banyak`,tot1, null,concat(kode_comp, nocab) as kode, kodeprod
                                from `data$tahun`.`ri`
                                where bulan in (1,2,3,4,5,6,7,8,9,10,11,12) and  
                                    kodeprod in (010095,010102)
                        )a 	
                        GROUP BY kode, bulan
                )a 
                GROUP BY kode
                union ALL
                select 	kode, grup, kodeprod, 
                        sum(if(bulan = 1, unit, 0)) as unit1,
                        sum(if(bulan = 2, unit, 0)) as unit2,
                        sum(if(bulan = 3, unit, 0)) as unit3,
                        sum(if(bulan = 4, unit, 0)) as unit4,
                        sum(if(bulan = 5, unit, 0)) as unit5,
                        sum(if(bulan = 6, unit, 0)) as unit6,
                        sum(if(bulan = 7, unit, 0)) as unit7,
                        sum(if(bulan = 8, unit, 0)) as unit8,
                        sum(if(bulan = 9, unit, 0)) as unit9,
                        sum(if(bulan = 10, unit, 0)) as unit10,
                        sum(if(bulan = 11, unit, 0)) as unit11,
                        sum(if(bulan = 12, unit, 0)) as unit12,
                        sum(if(bulan = 1, omzet, 0)) as omzet1,
                        sum(if(bulan = 2, omzet, 0)) as omzet2,
                        sum(if(bulan = 3, omzet, 0)) as omzet3,
                        sum(if(bulan = 4, omzet, 0)) as omzet4,
                        sum(if(bulan = 5, omzet, 0)) as omzet5,
                        sum(if(bulan = 6, omzet, 0)) as omzet6,
                        sum(if(bulan = 7, omzet, 0)) as omzet7,
                        sum(if(bulan = 8, omzet, 0)) as omzet8,
                        sum(if(bulan = 9, omzet, 0)) as omzet9,
                        sum(if(bulan = 10, omzet, 0)) as omzet10,
                        sum(if(bulan = 11, omzet, 0)) as omzet11,
                        sum(if(bulan = 12, omzet, 0)) as omzet12,
                        sum(if(bulan = 1, ot, 0)) as ot1,
                        sum(if(bulan = 2, ot, 0)) as ot2,
                        sum(if(bulan = 3, ot, 0)) as ot3,
                        sum(if(bulan = 4, ot, 0)) as ot4,
                        sum(if(bulan = 5, ot, 0)) as ot5,
                        sum(if(bulan = 6, ot, 0)) as ot6,
                        sum(if(bulan = 7, ot, 0)) as ot7,
                        sum(if(bulan = 8, ot, 0)) as ot8,
                        sum(if(bulan = 9, ot, 0)) as ot9,
                        sum(if(bulan = 10, ot, 0)) as ot10,
                        sum(if(bulan = 11, ot, 0)) as ot11,
                        sum(if(bulan = 12, ot, 0)) as ot12
                FROM
                (
                        select 	kode,count(distinct(outlet)) as ot, 'G0401' as grup, a.kodeprod,sum(banyak) as unit,sum(tot1) as omzet, bulan
                        FROM
                        (
                            select nocab, kode_comp,bulan, `banyak`,tot1, CONCAT(KODE_COMP,kode_lang) as outlet,concat(kode_comp, nocab) as kode, kodeprod
                            from `data$tahun`.`fi`
                            where bulan in (1,2,3,4,5,6,7,8,9,10,11,12) and
                            kodeprod in (040002,040001)
                            union all
                            select nocab, kode_comp, bulan, `banyak`,tot1, null,concat(kode_comp, nocab) as kode, kodeprod
                            from `data$tahun`.`ri`
                            where bulan in (1,2,3,4,5,6,7,8,9,10,11,12) and  
                            kodeprod in (040002,040001)
                        )a 	
                        GROUP BY kode, bulan
                )a GROUP BY kode
                UNION ALL
                select 	kode, grup, kodeprod, 
                        sum(if(bulan = 1, unit, 0)) as unit1,
                        sum(if(bulan = 2, unit, 0)) as unit2,
                        sum(if(bulan = 3, unit, 0)) as unit3,
                        sum(if(bulan = 4, unit, 0)) as unit4,
                        sum(if(bulan = 5, unit, 0)) as unit5,
                        sum(if(bulan = 6, unit, 0)) as unit6,
                        sum(if(bulan = 7, unit, 0)) as unit7,
                        sum(if(bulan = 8, unit, 0)) as unit8,
                        sum(if(bulan = 9, unit, 0)) as unit9,
                        sum(if(bulan = 10, unit, 0)) as unit10,
                        sum(if(bulan = 11, unit, 0)) as unit11,
                        sum(if(bulan = 12, unit, 0)) as unit12,
                        sum(if(bulan = 1, omzet, 0)) as omzet1,
                        sum(if(bulan = 2, omzet, 0)) as omzet2,
                        sum(if(bulan = 3, omzet, 0)) as omzet3,
                        sum(if(bulan = 4, omzet, 0)) as omzet4,
                        sum(if(bulan = 5, omzet, 0)) as omzet5,
                        sum(if(bulan = 6, omzet, 0)) as omzet6,
                        sum(if(bulan = 7, omzet, 0)) as omzet7,
                        sum(if(bulan = 8, omzet, 0)) as omzet8,
                        sum(if(bulan = 9, omzet, 0)) as omzet9,
                        sum(if(bulan = 10, omzet, 0)) as omzet10,
                        sum(if(bulan = 11, omzet, 0)) as omzet11,
                        sum(if(bulan = 12, omzet, 0)) as omzet12,
                        sum(if(bulan = 1, ot, 0)) as ot1,
                        sum(if(bulan = 2, ot, 0)) as ot2,
                        sum(if(bulan = 3, ot, 0)) as ot3,
                        sum(if(bulan = 4, ot, 0)) as ot4,
                        sum(if(bulan = 5, ot, 0)) as ot5,
                        sum(if(bulan = 6, ot, 0)) as ot6,
                        sum(if(bulan = 7, ot, 0)) as ot7,
                        sum(if(bulan = 8, ot, 0)) as ot8,
                        sum(if(bulan = 9, ot, 0)) as ot9,
                        sum(if(bulan = 10, ot, 0)) as ot10,
                        sum(if(bulan = 11, ot, 0)) as ot11,
                        sum(if(bulan = 12, ot, 0)) as ot12
                FROM
                (
                    select 	kode,count(distinct(outlet)) as ot, 'G1201' as grup, a.kodeprod,sum(banyak) as unit,sum(tot1) as omzet, bulan
                    FROM
                    (
                        select nocab, kode_comp,bulan, `banyak`,tot1, CONCAT(KODE_COMP,kode_lang) as outlet,concat(kode_comp, nocab) as kode, kodeprod
                        from `data$tahun`.`fi`
                        where bulan in (1,2,3,4,5,6,7,8,9,10,11,12) and
                        kodeprod in (120014,120013,120012,120009,120011,120008,120010,120007,120005,120015,120001,120003,120006,120002,120004)
                        union all
                        select nocab, kode_comp, bulan, `banyak`,tot1, null,concat(kode_comp, nocab) as kode, kodeprod
                        from `data$tahun`.`ri`
                        where bulan in (1,2,3,4,5,6,7,8,9,10,11,12) and  
                        kodeprod in (120014,120013,120012,120009,120011,120008,120010,120007,120005,120015,120001,120003,120006,120002,120004)
                    )a 	
                    GROUP BY kode, bulan
                )a GROUP BY kode
            )a LEFT JOIN
            (
                select concat(kode_comp, nocab) as kode, nama_comp, urutan, sub
                from mpm.tbl_tabcomp_new
                where `status` = 1
                GROUP BY concat(kode_comp, nocab)
            )b on a.kode = b.kode
            LEFT JOIN
            (
                select kode_group,nama_group 
                from mpm.tbl_group
            )c on a.grup = c.kode_group
            GROUP BY kode, grup
        ";

        $proses = $this->db->query($sql);

        // echo "<pre>";
        // print_r($sql);
        // echo "</pre>";

        if ($proses) {
            $sql = "
            insert into mpm.tbl_sales_custom
            select 	'', b.nama_comp, a.sub, a.`group`, a.nama_group,
                    sum(a.unit1), sum(a.unit2), sum(a.unit3), sum(a.unit4), sum(a.unit5), 
                    sum(a.unit6), sum(a.unit7), sum(a.unit8), sum(a.unit9), sum(a.unit10), sum(a.unit11), sum(a.unit12),
                    sum(a.omzet1), sum(a.omzet2), sum(a.omzet3), sum(a.omzet4), sum(a.omzet5), 
                    sum(a.omzet6), sum(a.omzet7), sum(a.omzet8), sum(a.omzet9), sum(a.omzet10), sum(a.omzet11), sum(a.omzet12),
                    sum(a.ot1), sum(a.ot2), sum(a.ot3), sum(a.ot4), sum(a.ot5), 
                    sum(a.ot6), sum(a.ot7), sum(a.ot8), sum(a.ot9), sum(a.ot10), sum(a.ot11), sum(a.ot12),
                    $id, b.urutan
            from 	mpm.tbl_sales_custom a inner JOIN
                    (
                        select 	naper naper2,status, urutan, nama_comp, sub
                        from 		mpm.tbl_tabcomp_new
                        WHERE 	status = '2' and status_cluster <> '1'
                    )b on a.sub = b.sub
            where a.userid = $id
            GROUP BY a.sub, a.`group` 
            ORDER BY urutan, a.`group`
            ";

            $proses = $this->db->query($sql);

            if ($proses) {
                $sql = "
                insert into mpm.tbl_sales_custom
                        select 	'', 'GRAND TOTAL', '', a.`group`, a.nama_group,
                        sum(a.unit1), sum(a.unit2), sum(a.unit3), sum(a.unit4), sum(a.unit5), 
                        sum(a.unit6), sum(a.unit7), sum(a.unit8), sum(a.unit9), sum(a.unit10), sum(a.unit11), sum(a.unit12),
                        sum(a.omzet1), sum(a.omzet2), sum(a.omzet3), sum(a.omzet4), sum(a.omzet5), 
                        sum(a.omzet6), sum(a.omzet7), sum(a.omzet8), sum(a.omzet9), sum(a.omzet10), sum(a.omzet11), sum(a.omzet12),
                        sum(a.ot1), sum(a.ot2), sum(a.ot3), sum(a.ot4), sum(a.ot5), 
                        sum(a.ot6), sum(a.ot7), sum(a.ot8), sum(a.ot9), sum(a.ot10), sum(a.ot11), sum(a.ot12),
                        $id, '999' as urutan
                from 	mpm.tbl_sales_custom a inner JOIN
                        (
                            select concat(kode_comp, nocab) as kode, nama_comp, urutan, sub
                            from mpm.tbl_tabcomp_new
                            where `status` = 1
                            GROUP BY concat(kode_comp, nocab)
                        )b on a.kode = b.kode
                where a.userid = $id
                GROUP BY a.`group` 
                ORDER BY urutan, a.`group`
                ";

                // echo "<pre>";
                // print_r($sql);
                // echo "</pre>";

                $proses = $this->db->query($sql);
            }
        }     
        // echo "<pre>";
        // print_r($sql);
        // echo "</pre>";   

        $query="select * from mpm.tbl_sales_custom where userid = $id ORDER BY urutan";                        
        $hasil = $this->db->query($query);
        if ($hasil->num_rows() > 0) 
        {
            return $hasil->result();
        } else {
            return array();
        }

    }

}