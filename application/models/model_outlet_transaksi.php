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
        // echo "<pre>";
        // echo "tahun_periode_1 : ".$tahun_periode_1;
        // echo "tahun_periode_2 : ".$tahun_periode_2;
        // echo "</pre>";

        if ($tipe_1 == 1 && $tipe_2 == 1 ) {
            $bd = ',class ,kode_type';
        }elseif ($tipe_1 == 1 && $tipe_2 == 0 ) {
            $bd = ',class';
        }elseif ($tipe_1 == 0 && $tipe_2 == 1 ) {
            $bd = ',kode_type';
        }else{
            $bd = '';
        }

        if ($wilayah_nocab == null) {
            $wilayah = '';
        }else{
            $wilayah = "and nocab in ($wilayah_nocab)";
        }

        if ($tahun_periode_2 == $tahun_periode_1) {
            // echo "a";
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
            // echo "b";
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
                select a.kode,a.kodesalur,a.kode_type
                FROM
                (
                    select a.kode,kodesalur,kode_type
                    from db_lang.tbl_bantu_pelanggan_$tahun_periode_2 a
                    union all
                    select a.kode,kodesalur,kode_type
                    from db_lang.tbl_bantu_pelanggan_$tahun_periode_1 a
                )a GROUP BY kode
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
            // echo "<pre>";
            // echo "<br><br><br><br>";
            // print_r($fi);
            // print_r($proses);
            // echo "</pre>";
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
                a.`group` as class, a.kode_type, a.nama_type, a.sektor,$i as pengambilan, count(outlet) as jumlah,$id,$created_date
                FROM
                (	
                    select *
                    from db_temp.t_temp_pengambilan_master a
                    where a.created_date = $created_date and a.id = $id
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
            a.`group` as class,a.kode_type, a.nama_type, a.sektor, $i as pengambilan, count(outlet) as jumlah,$id,$created_date
            FROM
            (	
                select *
                from db_temp.t_temp_pengambilan_master a
                where a.created_date = $created_date and a.id = $id
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
            SELECT 	a.kode, a.branch_name,a.nama_comp,class,kode_type, nama_type, sektor,
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

            $tampil = "select * from db_temp.t_temp_pengambilan_report a where a.id = $id and created_date = $created_date";
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
            $bd = ',kodesalur ,kode_type, kodeprod';
        }elseif ($tipe_1 == 1 && $tipe_2 == 1 && $tipe_3 == 0 ) {
            $bd = ',kodesalur, kode_type';
        }elseif ($tipe_1 == 1 && $tipe_2 == 0 && $tipe_3 == 1 ) {
            $bd = ',kodesalur, kodeprod';
        }elseif ($tipe_1 == 1 && $tipe_2 == 0 && $tipe_3 == 0 ) {
            $bd = ',kodesalur';
        }elseif ($tipe_1 == 0 && $tipe_2 == 1 && $tipe_3 == 1 ) {
            $bd = ',kode_type, kodeprod';
        }elseif ($tipe_1 == 0 && $tipe_2 == 1 && $tipe_3 == 0 ) {
            $bd = ',kode_type';
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
                        date_format(a.TGLDOKJDI,'%Y-%m-%d') <= '$periode_2' $wilayah
            ";

        }else{
            echo '<script>alert("periode anda berjarak 2 tahun / lebih. Sehingga tidak dapat kami proses");</script>';
            redirect('outlet_transaksi/outlet_transaksi_ytd','refresh'); 
        }

        $sql="  insert into db_temp.t_temp_outlet_transaksi
                select a.kode_comp, b.nama_comp, a.kodeprod,e.namaprod, kodesalur, c.jenis, d.kode_type, d.nama_type, d.sektor, count(distinct(outlet)) as ytd,urutan,$id, $tgl_created
                FROM
                (
                    $fi
                )a LEFT JOIN
                (
                    select kode_comp,nama_comp,urutan, concat(a.kode_comp, a.nocab) as kode
                    from mpm.tbl_tabcomp a
                    where `status` = 1
                    GROUP BY concat(a.kode_comp, a.nocab)
                )b on a.kode = b.kode
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
                GROUP BY a.kode $bd
                ORDER BY urutan asc, kodeprod, jenis, kode_type    
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

    public function timezone(){
        date_default_timezone_set('Asia/Jakarta');
        $date = date_create(date('Y-m-d H:i:s'));
        date_add($date, date_interval_create_from_date_string('-7 hours'));
        $tgl = date_format($date, 'Y-m-d H:i:s');
        return $tgl;
      }

      public function otsc($data){

        $userid = $data['userid'];
        $created_date = $data['created_date'];

        $from_outlet = $data['from_outlet'];
        $to_outlet = $data['to_outlet'];
        $from_otsc = $data['from_otsc'];
        $to_otsc = $data['to_otsc'];
        $tahun_from_outlet = substr($from_outlet,0,4);
        $tahun_to_outlet = substr($to_outlet,0,4);
        $tahun_from_otsc = substr($from_otsc,0,4);
        $tahun_to_otsc = substr($to_otsc,0,4);        

        $wilayah_nocab = $data['wilayah_nocab'];
        if ($wilayah_nocab == null) {
            $wilayah = '';
        }else{
            $wilayah = "and nocab in ($wilayah_nocab)";
        }

        $output = $data['output'];
        // echo "<br><br><br><br><br><br><br>output : ".$output;

        if ($output == 2) {
            $break_hari = ",tahun,bulan,hrdok";
            // $break_hari = ",hrdok";
            // echo "aaa";

        }elseif ($output == 3) {
            $break_hari = ",kode_type";
            // $break_hari = ",hrdok";
            // echo "b";

        }else{
            $break_hari = "";
            // echo "c";
        }
        // echo "break_hari : ".$break_hari;

        // echo "<pre><br><br><br><br><br>";
        // echo "from_outlet : ".$from_outlet."<br>";
        // echo "to_outlet : ".$to_outlet."<br>";
        // echo "from_otsc : ".$from_otsc."<br>";
        // echo "to_otsc : ".$to_otsc."<br>";
        // echo "tahun_from_outlet : ".$tahun_from_outlet."<br>";
        // echo "tahun_to_outlet : ".$tahun_to_outlet."<br>";
        // echo "tahun_from_otsc : ".$tahun_from_otsc."<br>";
        // echo "tahun_to_otsc : ".$tahun_to_otsc."<br>";
        // echo "</pre>";

        $tahun_tengah_outlet = $tahun_to_outlet - 1;
        // echo "tahun_tengah_outlet : ".$tahun_tengah_outlet;
        $tahun_tengah_otsc = $tahun_to_otsc - 1;
        // echo "tahun_tengah_otsc : ".$tahun_tengah_otsc;       

        $kodeprod = $data['kodeprod'];
        $hasil_traffic = '';
        $cek_traffic = $this->db->query("select * from db_temp.t_traffic where id_menu = 75")->result();
        foreach ($cek_traffic as $key) {
            $hasil_traffic = $key->status;
        }

        if ($hasil_traffic == 1) {
            $message = "Sedang dalam antrian mengakses menu ini. Silahkan coba beberapa saat lagi atau hubungi IT !";
            echo "<script type='text/javascript'>alert('$message');
            window.location.href = 'otsc';
            </script>";
        }else{

            $kunci_traffic = "update db_temp.t_traffic a set a.status = 1, a.created_date = '$created_date' where a.id_menu = 75";
            $this->db->query($kunci_traffic);

            // $this->db->query("truncate db_temp.t_temp_outlet_tidak_dihitung");
            $this->db->query("truncate db_temp.t_temp_outlet_tidak_dihitung_core");
            // $this->db->query("truncate db_temp.t_temp_outlet_di_bulan_transaksi");
            $this->db->query("truncate db_temp.t_temp_outlet_di_bulan_transaksi_core");
            // $this->db->query("truncate db_temp.t_temp_outlet_seluruh_bulan");
            $this->db->query("truncate db_temp.t_temp_outlet_hasil_join");
            $this->db->query("truncate db_temp.t_temp_outlet_siap_export");

     
            if ($tahun_from_outlet - $tahun_to_outlet == 0) {                
                $insert_outlet_tidak_dihitung_core = "
                insert into db_temp.t_temp_outlet_tidak_dihitung_core
                SELECT  concat(a.kode_comp, a.nocab) as kode, concat(a.kode_comp,a.kode_lang) as outlet, a.nama_lang, a.kodesalur, a.kode_type,
                        $userid, '$created_date'
                from    data$tahun_from_outlet.fi a
                where   kodeprod in ($kodeprod) and 
                        (date_format(a.TGLDOKJDI,'%Y-%m-%d') between '$from_outlet' and '$to_outlet') $wilayah
                GROUP BY outlet
                ";
            }elseif($tahun_from_outlet - $tahun_to_outlet == -1){                
                $insert_outlet_tidak_dihitung_core = "                
                insert into db_temp.t_temp_outlet_tidak_dihitung_core
                SELECT  concat(a.kode_comp, a.nocab) as kode, concat(a.kode_comp,a.kode_lang) as outlet, a.nama_lang, a.kodesalur, a.kode_type,
                        $userid, '$created_date'
                from    data$tahun_to_outlet.fi a
                where   kodeprod in ($kodeprod) and 
                        (date_format(a.TGLDOKJDI,'%Y-%m-%d') between '$tahun_to_outlet-01-01' and '$to_outlet') $wilayah                
                union all
                SELECT  concat(a.kode_comp, a.nocab) as kode, concat(a.kode_comp,a.kode_lang) as outlet, a.nama_lang, a.kodesalur, a.kode_type,
                        $userid, '$created_date'
                from    data$tahun_from_outlet.fi a
                where   kodeprod in ($kodeprod) and 
                        (date_format(a.TGLDOKJDI,'%Y-%m-%d') between '$from_outlet' and '$tahun_from_outlet-12-31') $wilayah
                GROUP BY outlet                
                ";
            }elseif($tahun_from_outlet - $tahun_to_outlet == -2){                
                $insert_outlet_tidak_dihitung_core = "                
                insert into db_temp.t_temp_outlet_tidak_dihitung_core
                SELECT  concat(a.kode_comp, a.nocab) as kode, concat(a.kode_comp,a.kode_lang) as outlet, a.nama_lang, a.kodesalur, a.kode_type,
                        $userid, '$created_date'
                from    data$tahun_to_outlet.fi a
                where   kodeprod in ($kodeprod) and 
                        (date_format(a.TGLDOKJDI,'%Y-%m-%d') between '$tahun_to_outlet-01-01' and '$to_outlet') $wilayah                
                union all
                SELECT  concat(a.kode_comp, a.nocab) as kode, concat(a.kode_comp,a.kode_lang) as outlet, a.nama_lang, a.kodesalur, a.kode_type,
                        $userid, '$created_date'
                from    data$tahun_from_outlet.fi a
                where   kodeprod in ($kodeprod) and 
                        (date_format(a.TGLDOKJDI,'%Y-%m-%d') between '$from_outlet' and '$tahun_from_outlet-12-31') $wilayah
                union all
                SELECT  concat(a.kode_comp, a.nocab) as kode, concat(a.kode_comp,a.kode_lang) as outlet, a.nama_lang, a.kodesalur, a.kode_type,
                        $userid, '$created_date'
                from    data$tahun_tengah_outlet.fi a
                where   kodeprod in ($kodeprod) and 
                        (date_format(a.TGLDOKJDI,'%Y-%m-%d') between '$tahun_tengah_outlet-01-01' and '$tahun_tengah_outlet-12-31') $wilayah   
                GROUP BY outlet                
                ";
            }else{
                $message = "Rentang periode yang anda masukkan tidak valid atau mungkin terlalu panjang (melewati batas server). Halaman akan dikembalikan ke awal";
                echo "<script type='text/javascript'>alert('$message');
                window.location.href = 'otsc';
                </script>";
            }
            // echo "<pre><br><br><br><br><br><br>";
            // print_r($insert_outlet_tidak_dihitung_core);
            // echo "</pre>";
            // die;

            if ($tahun_from_otsc - $tahun_to_otsc == 0) {                
                $insert_outlet_di_bulan_transaksi_core = "
                insert into db_temp.t_temp_outlet_di_bulan_transaksi_core
                SELECT  concat(a.kode_comp, a.nocab) as kode, concat(a.kode_comp,a.kode_lang) as outlet, a.nama_lang, a.kodesalur, a.kode_type, $tahun_from_otsc as tahun, a.bulan, a.hrdok,
                        $userid, '$created_date'
                from    data$tahun_from_otsc.fi a
                where   kodeprod in ($kodeprod) and 
                        (date_format(a.TGLDOKJDI,'%Y-%m-%d') between '$from_otsc' and '$to_otsc') $wilayah
                GROUP BY outlet $break_hari               
                ";
            }elseif($tahun_from_otsc - $tahun_to_otsc == -1){                
                $insert_outlet_di_bulan_transaksi_core = "
                insert into db_temp.t_temp_outlet_di_bulan_transaksi_core
                SELECT  concat(a.kode_comp, a.nocab) as kode, concat(a.kode_comp,a.kode_lang) as outlet, a.nama_lang, a.kodesalur, a.kode_type, $tahun_to_otsc as tahun, a.bulan, a.hrdok,
                        $userid, '$created_date'
                from    data$tahun_to_otsc.fi a
                where   kodeprod in ($kodeprod) and 
                        (date_format(a.TGLDOKJDI,'%Y-%m-%d') between '$tahun_to_otsc-01-01' and '$to_otsc') $wilayah
                union all
                SELECT  concat(a.kode_comp, a.nocab) as kode, concat(a.kode_comp,a.kode_lang) as outlet, a.nama_lang, a.kodesalur, a.kode_type, $tahun_from_otsc as tahun, a.bulan, a.hrdok,
                        $userid, '$created_date'
                from    data$tahun_from_otsc.fi a
                where   kodeprod in ($kodeprod) and 
                        (date_format(a.TGLDOKJDI,'%Y-%m-%d') between '$from_otsc' and '$tahun_from_otsc-12-31') $wilayah
                
                GROUP BY outlet $break_hari                 
                ";
            }elseif($tahun_from_otsc - $tahun_to_otsc == -2){                
                $insert_outlet_di_bulan_transaksi_core = "
                insert into db_temp.t_temp_outlet_di_bulan_transaksi_core
                SELECT  concat(a.kode_comp, a.nocab) as kode, concat(a.kode_comp,a.kode_lang) as outlet, a.nama_lang, a.kodesalur, a.kode_type, $tahun_to_otsc as tahun, a.bulan, a.hrdok,
                        $userid, '$created_date'
                from    data$tahun_to_otsc.fi a
                where   kodeprod in ($kodeprod) and 
                        (date_format(a.TGLDOKJDI,'%Y-%m-%d') between '$tahun_to_otsc-01-01' and '$to_otsc') $wilayah
                union all
                SELECT  concat(a.kode_comp, a.nocab) as kode, concat(a.kode_comp,a.kode_lang) as outlet, a.nama_lang, a.kodesalur, a.kode_type, $tahun_from_otsc as tahun, a.bulan, a.hrdok,
                        $userid, '$created_date'
                from    data$tahun_from_otsc.fi a
                where   kodeprod in ($kodeprod) and 
                        (date_format(a.TGLDOKJDI,'%Y-%m-%d') between '$from_otsc' and '$tahun_from_otsc-12-31') $wilayah
                union all
                SELECT  concat(a.kode_comp, a.nocab) as kode, concat(a.kode_comp,a.kode_lang) as outlet, a.nama_lang, a.kodesalur, a.kode_type, $tahun_from_otsc as tahun, a.bulan, a.hrdok,
                        $userid, '$created_date'
                from    data$tahun_tengah_otsc.fi a
                where   kodeprod in ($kodeprod) and 
                        (date_format(a.TGLDOKJDI,'%Y-%m-%d') between '$tahun_tengah_otsc-01-01' and '$tahun_tengah_otsc-12-31') $wilayah
                
                GROUP BY outlet $break_hari                 
                ";
            }else{
                $message = "Rentang periode yang anda masukkan tidak valid atau mungkin terlalu panjang (melewati batas server). Halaman akan dikembalikan ke awal";
                echo "<script type='text/javascript'>alert('$message');
                window.location.href = 'otsc';
                </script>";
            }

            // echo "<pre><br><br><br><br>";
            // print_r($insert_outlet_di_bulan_transaksi_core);
            // echo "</pre>";
            // die;
        

        
            $this->db->query($insert_outlet_tidak_dihitung_core);
            $this->db->query($insert_outlet_di_bulan_transaksi_core);
            // echo "<pre>";
            // print_r($insert_outlet_di_bulan_transaksi_core);
            // echo "</pre>";

            $insert_outlet_hasil_join = "
            insert into db_temp.t_temp_outlet_hasil_join
            select 	a.kode, a.outlet, a.kodesalur, a.kode_type, a.tahun, a.bulan, a.hrdok, c.jenis, c.`group`, 
                    d.nama_type, d.sektor, d.segment, $userid, '$created_date'
            from    db_temp.t_temp_outlet_di_bulan_transaksi_core a LEFT JOIN
            (
                select a.kode, a.jenis, a.`group`
                from mpm.tbl_tabsalur a
            )c on a.kodesalur = c.kode LEFT JOIN
            (
                select a.kode_type, a.nama_type, a.sektor, a.segment
                from mpm.tbl_bantu_type a
            )d on a.kode_type = d.kode_type
            where a.outlet not in 
            (
                select b.outlet
                from db_temp.t_temp_outlet_tidak_dihitung_core b
            ) and a.created_by = $userid and a.created_date = '$created_date' 
            ";
            $this->db->query($insert_outlet_hasil_join);
            // echo "<pre>";
            // print_r($insert_outlet_hasil_join);
            // echo "</pre>";

            $insert_siap_export = "
            insert into db_temp.t_temp_outlet_siap_export
            select  a.kode, b.branch_name, b.nama_comp, 
                    '' as kodeprod, '' as namaprod, '' as group_product, '' as sub_group,
                    '' as kodesalur, '' as jenis, '' as `group`, a.kode_type as kode_type, a.nama_type as nama_type, a.sektor as sektor, a.segment as segment, a.tahun, a.bulan, a.hrdok,
                    count(a.outlet) as ot, 
                    $userid, '$created_date'
            from db_temp.t_temp_outlet_hasil_join a LEFT JOIN
            (
                select concat(a.kode_comp, a.nocab) as kode, a.branch_name, a.nama_comp, a.urutan
                from mpm.tbl_tabcomp a
                where a.`status` = 1
                GROUP BY kode
            )b on a.kode = b.kode
            where a.created_by = $userid and a.created_date = '$created_date'
            GROUP BY a.kode $break_hari
            ORDER BY urutan $break_hari
            ";
            $this->db->query($insert_siap_export);
            // echo "<pre>";
            // print_r($insert_siap_export);
            // echo "</pre>";

            $kunci_traffic = "update db_temp.t_traffic a set a.status = 0, a.created_date = '$created_date' where a.id_menu = 75";
            $this->db->query($kunci_traffic);
        
            $sql = "
                select *
                from db_temp.t_temp_outlet_siap_export a
                where a.created_by = $userid and a.created_date = '$created_date'
            ";

            // echo "<pre>";
            // print_r($sql);
            // echo "</pre>";
            $proses = $this->db->query($sql)->result();
            if ($proses) {
                return $proses;
            }else{
                return array();
            }

        }

    }
      
}