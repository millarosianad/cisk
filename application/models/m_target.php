<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_target extends CI_Model {

    public function omzet_target($dataSegment){

        /* ---------DEFINISI VARIABEL----------------- */
            if ( function_exists( 'date_default_timezone_set' ) )
            date_default_timezone_set('Asia/Jakarta');        
            $tgl_created='"'.date('Y-m-d H:i:s').'"';
            $jam=date('ymdHis');
            $key_jam = $jam;
            $id=$this->session->userdata('id');
            
            $year = $dataSegment['year'];
            $bulan = $dataSegment['bulan'];
            $supp = $dataSegment['supplier'];
            $group = $dataSegment['group'];
            
            echo "<pre>";
            print_r($year);
            print_r($bulan);
            print_r($supp);
            print_r($group);
            echo "</pre>";
            
            if ($group == '0') {
                $group_product = "where supp = '$supp'";
                $group_x = '';
            } else {
                $group_product = "where grup ='$group'";
                $group_x = "and grup = '$group'";
            }
            
            echo "group : ".$group."<br>";
            echo "group_product : ".$group_product."<br>";
            echo "group x : ".$group_x."<br>";
            
    
            $sql_tbl_target_del = "delete from mpm.tbl_target_temp where userid = '$id'";
            $proses_delete_tbl_target = $this->db->query($sql_tbl_target_del);
            $sql_tbl_target = "
                insert into mpm.tbl_target_temp
                select kode_comp, periode, grup, sum(target_product) as target, $id
                FROM
                (
                    select periode, branchid, siteid, productid, target_product 
                    from mpm.t_target_salesman_product_jkt 
                    where month(periode) = '$bulan' and year(periode) = '$year'
                )a inner JOIN
                (
                    select 	KODEPROD,NAMAPROD,grup,supp
                    from 		mpm.tabprod
                )b on a.productid = b.KODEPROD 
                left join 
                (
                    select siteid, kode_comp
                    from mpm.tbl_siteid
                )c on a.siteid = c.siteid
                $group_product
                GROUP BY a.siteid
    
                union all
    
                select kode_comp, periode, grup, sum(target_product) as target, $id
                FROM
                (
                    select periode, branchid, siteid, productid, target_product 
                    from mpm.t_target_salesman_product_jbr
                    where month(periode) = '$bulan' and year(periode) = '$year'
                )a inner JOIN
                (
                    select 	KODEPROD,NAMAPROD,grup,supp
                    from 		mpm.tabprod
                )b on a.productid = b.KODEPROD 
                left join 
                (
                    select siteid, kode_comp
                    from mpm.tbl_siteid
                )c on a.siteid = c.siteid
                $group_product
                GROUP BY a.siteid
    
                union all
    
                select kode_comp, periode, grup, sum(target_product) as target, $id
                FROM
                (
                    select periode, branchid, siteid, productid, target_product 
                    from mpm.t_target_salesman_product_jtg
                    where month(periode) = '$bulan' and year(periode) = '$year'
                )a inner JOIN
                (
                    select 	KODEPROD,NAMAPROD,grup,supp 
                    from 		mpm.tabprod
                )b on a.productid = b.KODEPROD 
                left join 
                (
                    select siteid, kode_comp
                    from mpm.tbl_siteid
                )c on a.siteid = c.siteid
                $group_product
                GROUP BY a.siteid
    
                union all
    
                select kode_comp, periode, grup, sum(target_product) as target, $id
                FROM
                (
                    select periode, branchid, siteid, productid, target_product 
                    from mpm.t_target_salesman_product_jts
                    where month(periode) = '$bulan' and year(periode) = '$year'
                )a inner JOIN
                (
                    select 	KODEPROD,NAMAPROD,grup,supp
                    from 		mpm.tabprod
                )b on a.productid = b.KODEPROD 
                left join 
                (
                    select siteid, kode_comp
                    from mpm.tbl_siteid
                )c on a.siteid = c.siteid
                $group_product
                GROUP BY a.siteid
    
                union all
    
                select kode_comp, periode, grup, sum(target_product) as target, $id
                FROM
                (
                    select periode, branchid, siteid, productid, target_product 
                    from mpm.t_target_salesman_product_jtm
                    where month(periode) = '$bulan' and year(periode) = '$year'
                )a inner JOIN
                (
                    select 	KODEPROD,NAMAPROD,grup,supp
                    from 		mpm.tabprod
                )b on a.productid = b.KODEPROD 
                left join 
                (
                    select siteid, kode_comp
                    from mpm.tbl_siteid
                )c on a.siteid = c.siteid
                $group_product
                GROUP BY a.siteid
    
                union all
    
                select kode_comp, periode, grup, sum(target_product) as target, $id
                FROM
                (
                    select periode, branchid, siteid, productid, target_product 
                    from mpm.t_target_salesman_product_jbl
                    where month(periode) = '$bulan' and year(periode) = '$year'
                )a inner JOIN
                (
                    select 	KODEPROD,NAMAPROD,grup,supp 
                    from 		mpm.tabprod
                )b on a.productid = b.KODEPROD 
                left join 
                (
                    select siteid, kode_comp
                    from mpm.tbl_siteid
                )c on a.siteid = c.siteid
                $group_product
                GROUP BY a.siteid
    
                union all
    
                select kode_comp, periode, grup, sum(target_product) as target, $id
                FROM
                (
                    select periode, branchid, siteid, productid, target_product 
                    from mpm.t_target_salesman_product_diy
                    where month(periode) = '$bulan' and year(periode) = '$year'
                )a inner JOIN
                (
                    select 	KODEPROD,NAMAPROD,grup,supp 
                    from 		mpm.tabprod
                )b on a.productid = b.KODEPROD 
                left join 
                (
                    select siteid, kode_comp
                    from mpm.tbl_siteid
                )c on a.siteid = c.siteid
                $group_product
                GROUP BY a.siteid
    
                union all
    
                select kode_comp, periode, grup, sum(target_product) as target, $id
                FROM
                (
                    select periode, branchid, siteid, productid, target_product 
                    from mpm.t_target_salesman_product_krw
                    where month(periode) = '$bulan' and year(periode) = '$year'
                )a inner JOIN
                (
                    select 	KODEPROD,NAMAPROD,grup,supp 
                    from 		mpm.tabprod
                )b on a.productid = b.KODEPROD 
                left join 
                (
                    select siteid, kode_comp
                    from mpm.tbl_siteid
                )c on a.siteid = c.siteid
                $group_product
                GROUP BY a.siteid
    
                
            ";
    
            $proses_tbl_target = $this->db->query($sql_tbl_target);
            
            echo "<pre>";
            print_r($sql_tbl_target_del);
            print_r($sql_tbl_target);
            echo "</pre>";
            
            if ($proses_delete_tbl_target == TRUE && $proses_tbl_target == true) {
                echo "<pre>memperbarui tabel target afiliasi berhasil</pre>";
            } else {
                echo "<pre>memperbarui tabel target afiliasi gagal</pre>";
            }
            
    
            $sql_del = "delete from mpm.tbl_omzet_target where userid = '$id'";
            $proses_delete_tbl_omzet_target = $this->db->query($sql_del);
            
            $sql = "
                insert into mpm.tbl_omzet_target
                select a.kode_comp, c.nama_comp, c.sub, b.target_value, omzet, format((omzet/target_value*100),2) as `%`, $id,tanggal_data,urutan
                FROM
                (
                    SELECT kode_comp, sum(tot1) as omzet
                    FROM
                    (
                            select 	kode_comp, kodeprod, tot1, bulan 
                            from 	data$year.fi
                            where 	bulan = $bulan
    
                            union ALL
    
                            select 	kode_comp, kodeprod, tot1, bulan 
                            from 	data$year.ri
                            where 	bulan = $bulan
                    )a inner JOIN
                    (
                        select 	KODEPROD,NAMAPROD,grup,supp 
                        from 		mpm.tabprod
                    )b on a.kodeprod = b.KODEPROD 
                    $group_product
                    GROUP BY kode_comp
                )a LEFT JOIN
                (
                        select kode_comp, target_value 
                        from mpm.tbl_target_temp
                        where year(periode) = $year and userid = $id and month(periode) = $bulan $group_x
                )b on a.kode_comp = b.kode_comp
                LEFT JOIN
                (
                    select kode_comp, nama_comp, urutan, sub
                    from mpm.tbl_tabcomp_new
                    where `status` = 1
                    group by kode_comp
                )c on a.kode_comp = c.kode_comp
                LEFT JOIN
                (
                    select kode_comp, max(HRDOK) as tanggal_data
                    from data$year.fi
                    where bulan = $bulan
                    GROUP BY kode_comp
                )d on a.kode_comp = d.kode_comp
                ORDER BY urutan
            ";
            /*
            echo "<pre>";
            print_r($sql);
            echo "</pre>";
            */
            $proses = $this->db->query($sql);
    
            if ($proses == true) {
                
                $sql = "
                    insert into mpm.tbl_omzet_target
                    
                    select 	'', b.nama_comp, a.sub, sum(target_value) as subtotal_target, sum(omzet) as subtotal_omzet, 
                            format((sum(omzet)/sum(target_value)*100),2) as persen, userid, tanggal_data, b.urutan as urutan
                    from 	mpm.tbl_omzet_target a inner JOIN 
                    (
                        select kode_comp, nama_comp, sub, urutan
                        from mpm.tbl_tabcomp_new
                        where `status` = 2
                    )b on a.sub = b.sub
                    where userid = $id
                    GROUP BY a.sub
                    
    
                    union all
    
                    select '','Grand Total', a.sub, sum(target_value), sum(omzet), format((sum(omzet)/sum(target_value)*100),2) as persen, userid, '', '999'
                    from mpm.tbl_omzet_target a 
                    where userid = $id
    
                ";
    
                $this->db->query($sql);
                /*
                echo "<pre>";
                print_r($sql);
                echo "</pre>";
                */
    
            } else {
                echo "<pre>";
                echo "ada kesalahan penarikan Sub Total dan Grand Total. Harap ulangi kembali";
                echo "</pre>";
            }
            
    
    
                        
            /* PROSES TAMPIL KE WEBSITE */				
            $query="
                    select * 
                    from 		mpm.tbl_omzet_target
                    where 		userid = $id
                    ORDER BY urutan asc
                ";
            /*
                echo "<pre>";
            print_r($query);
            echo "</pre>";
            */		        
            $hasil = $this->db->query($query);
            if ($hasil->num_rows() > 0) 
            {
                return $hasil->result();
            } else {
                return array();
            }
            /* END PROSES TAMPIL KE WEBSITE */
            
        }

        public function get_target($data)
        {
            $id=$this->session->userdata('id');
            $year = $data['year'];
            $bulan = $data['bulan'];
            $supp = $data['supplier'];
            $group = $data['group'];
            if ($group == '0') {
                $group_product = "where supp = '$supp'";
                $group_x = '';
            } else {
                $group_product = "where grup ='$group'";
                $group_x = "and grup = '$group'";
            }

            echo "<pre>";
            echo "id : ".$id."<br>";
            echo "year : ".$year."<br>";
            echo "bulan : ".$bulan."<br>";
            echo "supp : ".$supp."<br>";
            echo "group : ".$group."<br>";
            echo "group_product : ".$group_product."<br>";
            echo "group x : ".$group_x."<br>";
            echo "</pre>";
            
            $this->db2 = $this->load->database('db2', TRUE);

            /*
            $this->db2->select("siteid,sales");
            $this->db2->from('JBL.dbo.t_target_salesman_product');
            $query = $this->db2->get();
            */

            $sql_del = "
                delete from mpm.tbl_target_temp
                where userid = $id
            ";
            $query = $this->db->query($sql_del);

            $sql_target_jkt = "
                select 	periode, branchid, siteid, productid, target_product 
                from 	JKT.dbo.t_target_salesman_product 
                where 	month(periode) = '1' and year(periode) = '2018'
            ";

            $query =  $this->db2->query($sql_target_jkt);

            echo "<pre>";
            print_r($sql_del);
            print_r($sql_target_jkt);
            echo "</pre>";
            
    

        }




}