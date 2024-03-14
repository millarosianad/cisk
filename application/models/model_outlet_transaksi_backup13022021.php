<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_outlet_transaksi extends CI_Model
{
    public function get_header_supp($supp){        
        // echo "supp : ".$supp;
        if ($supp == 'xxx' || $supp == '000') {
            $where = '';
        }else{
            $where = "and a.supp = '$supp'";
        }

        $sql = "
            select 	a.supp, a.namasupp
            from 	mpm.tabsupp a INNER JOIN mpm.tabprod b 
                        on a.SUPP = b.SUPP
            where   b.active = 1 $where
            GROUP BY b.supp
        ";
        $proses = $this->db->query($sql)->result();
        return $proses;
    }

    public function pengambilan($data){     
        $id = $this->session->userdata('id');        
        date_default_timezone_set('Asia/Jakarta');        
		$created_date='"'.date('Y-m-d H:i:s').'"';
        $periode_1 = $data['periode_1'];
        $periode_2 = $data['periode_2'];
        $tahun_periode_1 = substr($periode_1,0,4);
        $tahun_periode_2 = substr($periode_2,0,4);
        $kodeprod = $data['kodeprod'];
        $wilayah_nocab = $data['wilayah_nocab']; 
        $tipe_1 = $data['tipe_1']; 
        $tipe_2 = $data['tipe_2'];

        if ($tipe_1 == 1 && $tipe_2 == 1 ) {
            $bd = ',class ,sektor';
        }elseif ($tipe_1 == 1 && $tipe_2 == 0 ) {
            $bd = ',class';
        }elseif ($tipe_1 == 0 && $tipe_2 == 1 ) {
            $bd = ',sektor';
        }else{
            $bd = '';
        }

        if ($wilayah_nocab == null) {
            $wilayah = '';
        }else{
            $wilayah = "and nocab in ($wilayah_nocab)";
        }

        if ($tahun_periode_2 == $tahun_periode_1) {
            $fi = "
            insert into db_temp.t_temp_pengambilan_master
            select 	a.kode, count(outlet) as jumlahtransaksi, outlet, b.kodesalur,c.jenis,c.`group`,b.kode_type,d.nama_type,d.sektor,$id,$created_date
            FROM
            (								
                select count(faktur) as faktur,kode,outlet,kodeprod,bulan
                FROM
                (
                    select 	concat(kode_comp,KDDOKJDI,NODOKJDI,kode_lang) as faktur,
                            concat(a.kode_comp, a.nocab) as kode,
                            concat(a.kode_comp, a.kode_lang) as outlet,
                            kodeprod, bulan
                    from 	data$tahun_periode_1.fi a
                    where 	kodeprod in ($kodeprod) and 
                            (date_format(a.TGLDOKJDI,'%Y-%m-%d') >= '$periode_1' and date_format(a.TGLDOKJDI,'%Y-%m-%d') <= '$periode_2') $wilayah
                )a GROUP BY faktur
            )a LEFT JOIN
            (
                select a.kode,kodesalur,kode_type
                from db_lang.tbl_bantu_pelanggan_$tahun_periode_1 a
            )b on a.outlet = b.kode LEFT JOIN
            (
                select a.kode,a.jenis, a.`group`
                from mpm.tbl_tabsalur a 
            )c on b.kodesalur = c.kode LEFT JOIN
            (
                select a.kode_type,a.nama_type,a.sektor
                from mpm.tbl_bantu_type a
            )d on b.kode_type = d.kode_type
            GROUP BY outlet
            ";
            $proses = $this->db->query($fi);
        }elseif($tahun_periode_2 - $tahun_periode_1 == 1){
            // $fi = "
            // select 	concat(kode_comp,KDDOKJDI,NODOKJDI,kode_lang) as faktur,
            //         concat(a.kode_comp, a.nocab) as kode,
            //         concat(a.kode_comp, a.kode_lang) as outlet,
            //         kodeprod, bulan
            // from 	data$tahun_periode_1.fi a
            // where 	kodeprod in ($kodeprod) and 
            // date_format(a.TGLDOKJDI,'%Y-%m-%d') >= '$periode_1' $wilayah
            // union all
            // select 	concat(kode_comp,KDDOKJDI,NODOKJDI,kode_lang) as faktur,
            //         concat(a.kode_comp, a.nocab) as kode,
            //         concat(a.kode_comp, a.kode_lang) as outlet,
            //         kodeprod, bulan
            // from 	data$tahun_periode_2.fi a
            // where 	kodeprod in ($kodeprod) and 
            // date_format(a.TGLDOKJDI,'%Y-%m-%d') <= '$periode_2' $wilayah
            // ";
            $fi = "
            insert into db_temp.t_temp_pengambilan_master
            select 	a.kode, count(outlet) as jumlahtransaksi, outlet, b.kodesalur,c.jenis,c.`group`,b.kode_type,d.nama_type,d.sektor,$id,$created_date
            FROM
            (								
                select count(faktur) as faktur,kode,outlet,kodeprod,bulan
                FROM
                (
                    select 	concat(kode_comp,KDDOKJDI,NODOKJDI,kode_lang) as faktur,
                            concat(a.kode_comp, a.nocab) as kode,
                            concat(a.kode_comp, a.kode_lang) as outlet,
                            kodeprod, bulan
                    from 	data$tahun_periode_1.fi a
                    where 	kodeprod in ($kodeprod) and 
                    date_format(a.TGLDOKJDI,'%Y-%m-%d') >= '$periode_1' $wilayah
                    union all
                    select 	concat(kode_comp,KDDOKJDI,NODOKJDI,kode_lang) as faktur,
                            concat(a.kode_comp, a.nocab) as kode,
                            concat(a.kode_comp, a.kode_lang) as outlet,
                            kodeprod, bulan
                    from 	data$tahun_periode_2.fi a
                    where 	kodeprod in ($kodeprod) and 
                    date_format(a.TGLDOKJDI,'%Y-%m-%d') <= '$periode_2' $wilayah
                )a GROUP BY faktur
            )a LEFT JOIN
            (
                select a.kode,kodesalur,kode_type
                from db_lang.tbl_bantu_pelanggan_$tahun_periode_1 a
            )b on a.outlet = b.kode LEFT JOIN
            (
                select a.kode,a.jenis, a.`group`
                from mpm.tbl_tabsalur a 
            )c on b.kodesalur = c.kode LEFT JOIN
            (
                select a.kode_type,a.nama_type,a.sektor
                from mpm.tbl_bantu_type a
            )d on b.kode_type = d.kode_type
            GROUP BY outlet
            ";
            $proses = $this->db->query($fi);
        }else{
            return array(); 
        }

        // echo "<pre>";
        // echo "<br><br><br><br>";
        // print_r($fi);
        // echo "</pre>";

        for ($i=1; $i<4 ; $i++) { 
            $sql = "
                insert into db_temp.t_temp_pengambilan
                select 	a.kode,b.branch_name, b.nama_comp,b.sub,b.urutan,
                a.`group` as class,a.sektor,$i as pengambilan, count(outlet) as jumlah,$id,$created_date
                FROM
                (	
                    select *
                    from db_temp.t_temp_pengambilan_master a
                    where a.created_date = $created_date
                    HAVING jumlahtransaksi = $i
                )a LEFT JOIN
                (
                    select 	   concat(a.kode_comp, a.nocab) as kode, branch_name, nama_comp, sub,urutan
                    from 	   mpm.tbl_tabcomp a
                    where 	   `status` = 1
                    GROUP BY   concat(a.kode_comp, a.nocab)
                    ORDER BY   urutan asc
                )b on a.kode = b.kode
                GROUP BY a.kode $bd
                ORDER BY urutan
            ";
            $proses = $this->db->query($sql);

            // echo "<pre>";
            // echo "<br><br><br>";
            // print_r($sql);
            // echo "</pre>";

        }

        $sql = "
            insert into db_temp.t_temp_pengambilan
            select 	a.kode,b.branch_name, b.nama_comp,b.sub,b.urutan,
            a.`group` as class,a.sektor,$i as pengambilan, count(outlet) as jumlah,$id,$created_date
            FROM
            (	
                select *
                from db_temp.t_temp_pengambilan_master a
                where a.created_date = $created_date
                HAVING jumlahtransaksi >3
            )a LEFT JOIN
            (
                select 	   concat(a.kode_comp, a.nocab) as kode, branch_name, nama_comp, sub,urutan
                from 	   mpm.tbl_tabcomp a
                where 	   `status` = 1
                GROUP BY   concat(a.kode_comp, a.nocab)
                ORDER BY   urutan asc
            )b on a.kode = b.kode
            GROUP BY a.kode $bd
            ORDER BY urutan
        ";
        $proses = $this->db->query($sql);

            // echo "<pre>";
            // print_r($sql);
            // echo "</pre>";

            $sql_report = "
            insert into db_temp.t_temp_pengambilan_report
            SELECT 	a.kode, a.branch_name,a.nama_comp,class,sektor,
                    sum(if(a.pengambilan =1,jumlah,0)) as '1x',
                    sum(if(a.pengambilan =2,jumlah,0)) as '2x',
                    sum(if(a.pengambilan =3,jumlah,0)) as '3x',
                    sum(if(a.pengambilan =4,jumlah,0)) as '>3x',
                    a.id,$created_date
            from    db_temp.t_temp_pengambilan a
            where   a.id = $id and a.created_date = $created_date
            GROUP BY kode $bd
            ORDER BY urutan,class,sektor
            "; 
            $proses_report = $this->db->query($sql_report);
            // echo "<pre>";
            // print_r($sql_report);
            // echo "</pre>";

            $tampil = "select * from db_temp.t_temp_pengambilan_report a where a.id = $id and created_date = (select max(created_date) from db_temp.t_temp_pengambilan_report where id = $id)";
            $proses_tampil = $this->db->query($tampil)->result();
            if ($proses_tampil) {
                return $proses_tampil;
            }else{
                return array();
            }

    }

    public function outlet_transaksi($data){
        $id = $this->session->userdata('id');
        date_default_timezone_set('Asia/Jakarta');        
		$tgl_created='"'.date('Y-m-d H:i:s').'"';
        $periode_1 = $data['periode_1'];
        $periode_2 = $data['periode_2'];
        $tahun_periode_1 = substr($periode_1,0,4);
        $tahun_periode_2 = substr($periode_2,0,4);
        $kodeprod = $data['kodeprod']; 
        $tipe_1 = $data['tipe_1']; 
        $tipe_2 = $data['tipe_2'];
        $tipe_3 = $data['tipe_3'];




        if ($tipe_1 == 1 && $tipe_2 == 1 && $tipe_3 == 1 ) {
            $bd = ',kodesalur ,sektor, kodeprod';
        }elseif ($tipe_1 == 1 && $tipe_2 == 1 && $tipe_3 == 0 ) {
            $bd = ',kodesalur, sektor';
        }elseif ($tipe_1 == 1 && $tipe_2 == 0 && $tipe_3 == 1 ) {
            $bd = ',kodesalur, kodeprod';
        }elseif ($tipe_1 == 1 && $tipe_2 == 0 && $tipe_3 == 0 ) {
            $bd = ',kodesalur';
        }elseif ($tipe_1 == 0 && $tipe_2 == 1 && $tipe_3 == 1 ) {
            $bd = ',sektor, kodeprod';
        }elseif ($tipe_1 == 0 && $tipe_2 == 1 && $tipe_3 == 0 ) {
            $bd = ',sektor';
        }elseif ($tipe_1 == 0 && $tipe_2 == 0 && $tipe_3 == 1 ) {
            $bd = ',kodeprod';
        }else{
            $bd = '';
        }

        $wilayah_nocab = $data['wilayah_nocab']; 

        if ($wilayah_nocab == null) {
            $wilayah = '';
        }else{
            $wilayah = "and nocab in ($wilayah_nocab)";
        }

        if ($tahun_periode_2 == $tahun_periode_1) {
            $fi="
                select  nocab, kode_comp,blndok, 
                        CONCAT(KODE_COMP,kode_lang) as outlet,
                        concat(kode_comp, nocab) as kode, KODE_TYPE, kodesalur,kodeprod
                from    data$tahun_periode_1.fi a
                where   kodeprod in ($kodeprod) and 
                        (date_format(a.TGLDOKJDI,'%Y-%m-%d') >= '$periode_1' and date_format(a.TGLDOKJDI,'%Y-%m-%d') <= '$periode_2') $wilayah
                
            ";

        }elseif($tahun_periode_2 - $tahun_periode_1 == 1){
            $fi="
                select  nocab, kode_comp,blndok, 
                        CONCAT(KODE_COMP,kode_lang) as outlet,
                        concat(kode_comp, nocab) as kode, KODE_TYPE, kodesalur,kodeprod
                from    data$tahun_periode_1.fi a
                where   kodeprod in ($kodeprod) and 
                        date_format(a.TGLDOKJDI,'%Y-%m-%d') >= '$periode_1' $wilayah
                
                union all

                select  nocab, kode_comp,blndok, 
                        CONCAT(KODE_COMP,kode_lang) as outlet,
                        concat(kode_comp, nocab) as kode, KODE_TYPE, kodesalur,kodeprod
                from    data$tahun_periode_2.fi a
                where   kodeprod in ($kodeprod) and 
                        date_format(a.TGLDOKJDI,'%Y-%m-%d') >= '$periode_2' $wilayah
            ";

        }else{
            // echo "<pre>";
            // echo "periode anda berjarak 2 tahun. Sehingga tidak dapat kami proses"; 
            return array();      
            // echo "</pre>"; 
        }

        $sql="  insert into db_temp.t_temp_outlet_transaksi
                select a.kode_comp, b.nama_comp, a.kodeprod,e.namaprod, kodesalur, c.jenis, d.sektor, count(distinct(outlet)) as ytd,urutan,$id, $tgl_created
                FROM
                (
                    $fi
                )a LEFT JOIN
                (
                    select kode_comp,nama_comp,urutan
                    from mpm.tbl_tabcomp
                    where `status` = 1
                    GROUP BY kode_comp
                )b on a.kode_comp = b.kode_comp
                LEFT JOIN
                (
                    select kode, jenis
                    from mpm.tbl_tabsalur
                )c on a.kodesalur = c.kode
                LEFT JOIN
                (
                    select a.kode_type, a.nama_type, a.sektor
                    from mpm.tbl_bantu_type a
                )d on a.KODE_TYPE = d.kode_type
                LEFT JOIN
                (
                    select a.kodeprod, a.namaprod
                    from mpm.tabprod a
                )e on a.kodeprod = e.kodeprod
                GROUP BY kode_comp $bd
                ORDER BY urutan asc, kodeprod, jenis, sektor     
                ";
        

        // echo "<pre>";
        // echo "<br><br><br><br><br>";
        // print_r($sql);
        // echo "</pre>";
        $sql_insert = $this->db->query($sql);

        /* PROSES TAMPIL KE WEBSITE */

        $sql = "select * from db_temp.t_temp_outlet_transaksi a where a.id = $id and created_date = $tgl_created ";      
        $proses = $this->db->query($sql);
        if ($proses->num_rows() > 0) 
        {
            return $proses->result();
        } else {
            return array();
        }
            
        /* END PROSES TAMPIL KE WEBSITE */
    }
	
}