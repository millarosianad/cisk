<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_outlet extends CI_Model {

    public function list_dp_outlet()
    {
        $userid=$this->session->userdata('id');
        $nocab=$this->session->userdata('nocab');

        //echo "userid : ".$userid."<br>"; 
        //echo "nocab : ".$nocab."<br>"; 
        //echo "nocab : ".$nocab."<br>"; 

        if ($nocab!='')
        {
            if($userid =='191')//pak jan
            {                
                return $this->db->query('select distinct naper, nama_comp from mpm.tabcomp where  naper!=99 and wilayah=2 and naper like "%'.$nocab.'%" order by nama_comp');
               
            }
            else if($userid == '232')//pak kartono
            {
                return $this->db->query('select distinct naper, nama_comp from mpm.tabcomp where  naper!=99 and wilayah=1 and naper like "%'.$nocab.'%" order by nama_comp');
            }
            else if($userid == '327')//JTS
            {
                return $this->db->query('select distinct naper, nama_comp from mpm.tabcomp where naper like "%'.$nocab.'%" order by nama_comp');
            }
            else if($userid == '78')//krw
            {
                return $this->db->query('select distinct naper, nama_comp from mpm.tabcomp where naper like "07" order by nama_comp');
            }
             else{
                return $this->db->query('select distinct naper, nama_comp from mpm.tabcomp where  naper!=99 and naper like "%'.$nocab.'%" order by kode_comp');
            }       
            
        }
        else

        {
            if($userid == '318'){ //user dimas, all jkt & karawang
                return $this->db->query('select distinct naper, nama_comp, nocab from mpm.tabcomp 
                                        where  naper!=99 and naper in(88, 53, 01, 41 ,42, 75, 39, 58, 40, 04)
                                        order by nama_comp');
            }
            elseif($userid == '319'){ //user sutrisno, all jbr
                return $this->db->query('select distinct naper, nama_comp, nocab from mpm.tabcomp 
                                        where  naper!=99 and naper in(95, 02, 52, 47 ,60, "g2", 09, 79, 08, "k1")
                                        order by nama_comp');
            }

            elseif($userid == '320'){ //user kristiyanto, all jtg
                return $this->db->query('select distinct naper, nama_comp, nocab from mpm.tabcomp 
                                        where  naper!=99 and naper in(89, 14, 57, 64 , 78, 76, 06, 63, 73, "k1", 19, 32, "p3", "j1")
                                        order by nama_comp');
            }

            elseif($userid == '321'){ //user hengki, kudus, blora, pati, jepara
                return $this->db->query('select distinct naper, nama_comp, nocab from mpm.tabcomp 
                                        where  naper!=99 and naper in(19, 32, "p3", "j1")
                                        order by nama_comp');
            }

            elseif($userid == '322'){ //user dawam, diy, solo
                return $this->db->query('select distinct naper, nama_comp, nocab from mpm.tabcomp 
                                        where  naper!=99 and naper in(98, 96, 18)
                                        order by nama_comp');
            }

            elseif($userid == '323'){ //user miko, all jtm dan seluruh dp jawa timur (mlg, sdo, kdr, tga, mdu, blt)
                return $this->db->query('select distinct naper, nama_comp, nocab from mpm.tabcomp 
                                        where  naper!=99 and naper in(91, 28, 50, 83, 25, "g1", "b1", "p1", "p2", "t1", 27, 46, 24, 26, 23, 69, "s1", "l1", "c1", "p5", "p4") or (sub = 91)
                                        order by sub, nama_comp');
            }

            elseif($userid == '336'){ //user ghazali, all pulau jawa)
                return $this->db->query('select distinct naper, nama_comp, nocab from mpm.tabcomp 
                                        where  naper!=99 and naper in(01,02,03,04,06,07,08,09,10,11,12,13,14,15,16,17,18,19,23,24,25,26,27,28,29,39,40,41,42,46,47,48,50,52,53,54,56,57,58,60,61,62,63,64,66,67,68,69,74,91,73,79,75,76,81,78,83,84,88,89,95,96,"G1","P2","P1","T1","G2","P3","J1","S1","L1","K1","B2","B3",99,"C1","P4","P5",98)
                                        order by nama_comp');
            }

            else{
                //return $this->db->query('select distinct naper, nama_comp from mpm.tabcomp where  naper!=99 and kode_comp != "XXX" order by nama_comp  ');
            
                /*cek hak DP apa saja yang dapat dilihat*/
                    $this->db->where('id = '.'"'.$userid.'"');
                    $query = $this->db->get('mpm.user');
                    foreach ($query->result() as $row) {
                        $dp = $row->wilayah_nocab;
                        //echo "nocab : ".$dp."<br>";
                        //return $wilayah_nocab;
                    }

                    if ($dp == NULL || $dp == '' )
                    {
                        $daftar_dp = '';
                    } else {
                        $daftar_dp = 'and nocab in ('.$dp.')';
                    }
                    
                /*end cek hak DP apa saja yang dapat dilihat*/

                return $this->db->query('
                    select      distinct naper, nama_comp 
                    from        mpm.tabcomp 
                    where       naper!=99 and 
                                kode_comp != "XXX"
                                '.$daftar_dp.'
                    order by    nama_comp
                    ');


            }
        }
    }

    public function list_product()
    {
        $supp=$this->session->userdata('supp');
        
        if($supp=='000')
        {
            return $this->db->query('select supp,namasupp,kodeprod, namaprod from mpm.tabprod a left join mpm.tabsupp b using(supp) where left(kodeprod,3)<>"BSP"  and a.report=1 order by supp,namaprod');
        }else if($supp=='009')
        {
            return $this->db->query("
                select supp, kodeprod, namaprod, namasupp 
                from data2017.fi a LEFT JOIN mpm.tabsupp b USING(supp)
                where nocab = 'r1'
                GROUP BY KODEPROD
                ORDER BY namaprod");
        }
        else
        {
            return $this->db->query('select supp,namasupp,kodeprod, namaprod from mpm.tabprod a left join mpm.tabsupp b using(supp) where supp='.$supp.' and left(kodeprod,3)<>"BSP" and a.report=1 order by namaprod');
        }
    }

    protected function getNocab($naper)
    {
        $sql="select nocab from mpm.tabcomp where naper=?";
        $query = $this->db->query($sql,array($naper));
        $nocab='';
        foreach($query->result() as $row)
        {
            $nocab.=",".$row->nocab;
        }
        $naper=preg_replace('/,/', '', $nocab,1);
        return $naper;
    }

    public function outlet_dp($dataSegment,$kodeprod=null){
       
        $user=$this->session->userdata('username');
        if ( function_exists( 'date_default_timezone_set' ) )
        date_default_timezone_set('Asia/Jakarta');        

        /* ---------DEFINISI VARIABEL----------------- */
        
            $tgl_created='"'.date('Y-m-d H:i:s').'"';        
            $id=$this->session->userdata('id');
            $uv = $dataSegment['uv'];
                switch($uv)
                {
                    case 0:
                        $unit='banyak';
                        break;
                    case 1:
                        $unit='tot1';
                        break;
                }
            $kode_sales = $dataSegment['sm'];
            $year = $dataSegment['tahun'];
            $naper = $dataSegment['dp'];
            $kodeprod = $dataSegment['code'];

            //echo "year : ".$year;
            //echo "<br />dp : ".$naper."<br>";
        /* ---------END DEFINISI VARIABEL---------------- */


        /* cari nilai kode_comp */            
         
            $this->db->where('naper = '.'"'.$naper.'"');
            $query = $this->db->get('mpm.tabcomp');
            foreach ($query->result() as $row) {
                $kode_comp = $row->KODE_COMP;
                //echo "kode_comp : ".$kode_comp."<br />";
            }
        
        /* end cari nilai kode_comp */

        /* truncate tabel outlet masing-masing */
            $query = $this->db->truncate('dboutlet.tblang'.$kode_comp.'');            
            //echo "truncate : ".$query;        
        /* end truncate tabel outlet masing-masing */

        /* insert into tabel outlet masing-masing */
            
            $query = "
                    insert into dboutlet.tblang".$kode_comp."
                    select  *
                    from    data".$year.".tblang
                    where   nocab = ".'"'.$naper.'"'." 
            ";
                                    
            $sql = $this->db->query($query);
            /*
            echo "<pre>";
            print_r($query);
            echo "</pre>";
            */      
        /* end insert into tabel outlet masing-masing */


        /* PROSES DELETE MPM.OUTLET_NEW */
            $query = "delete from mpm.outlet_new where id = ".$id."";
            $sql = $this->db->query($query);
            /*
            echo "<pre>";
            print_r($query);
            echo "</pre>";            
            */
        /* END PROSES DELETE MPM.OUTLET_NEW */


        if ($naper == '91') {
            
            $sql=" 
            insert into mpm.outlet_new
            select  a.kode,
                    nama_lang as outlet,
                    alamat1 as address,
                    kode_type,
                    kodesalur,
                    b.koderayon,
                    b.kode_kota as kota,
                    b1,
                    b2,
                    b3,
                    b4,
                    b5,
                    b6,b7,b8,b9,b10,b11,b12,
                    (b1+b2+b3+b4+b5+b6+b7+b8+b9+b10+b11+b12)/12 as rata,
                    id 
            from    (
                        select  kode, kode_type, kodesalur,
                                sum(if(blndok=1,unit,0)) as b1,sum(if(blndok=2,unit,0)) as b2,
                                sum(if(blndok=3,unit,0)) as b3,sum(if(blndok=4,unit,0)) as b4,
                                sum(if(blndok=5,unit,0)) as b5,sum(if(blndok=6,unit,0)) as b6,
                                sum(if(blndok=7,unit,0)) as b7,sum(if(blndok=8,unit,0)) as b8,
                                sum(if(blndok=9,unit,0)) as b9,sum(if(blndok=10,unit,0)) as b10,
                                sum(if(blndok=11,unit,0)) as b11,sum(if(blndok=12,unit,0)) as b12,
                                ".$id." as id
                        from
                        (
                            select  concat(kode_comp,kode_lang,kode_kota,koderayon) kode, KODE_TYPE, kodesalur,
                                    blndok,
                                    sum(".$unit.") as unit 
                            from    data".$year.".fi 
                            where   nocab = ".'"'.$naper.'"'." and 
                                    concat(kodesales) like '%".$kode_sales."%' and 
                                    kodeprod in (".$kodeprod.")  
                            group by    kode,
                                        blndok
                                        
                            union all
                            
                            select  concat(kode_comp,kode_lang,kode_kota,koderayon) kode, KODE_TYPE, kodesalur,
                                    blndok,
                                    sum(".$unit.") as unit 
                            from    data".$year.".ri 
                            where   nocab = ".'"'.$naper.'"'." and 
                                    concat(kodesales) like '%".$kode_sales."%' and 
                                    kodeprod in (".$kodeprod.")  
                            group by kode,
                                    blndok 
                        ) a                               
                        group by kode
                    )a
                    
                    left join
                    (
                        select  distinct
                                concat(kode_comp,kode_lang,kode_kota,koderayon) kode,
                                koderayon,
                                nama_lang,
                                alamat1,
                                kode_kota 
                        from    dboutlet.tblang".$kode_comp."
                    )b on a.kode=b.kode group by kode                                       
                ";

        }
        if ($naper == '91' && $year == '2016') {
            
            $sql=" 
            insert into mpm.outlet_new
            select  a.kode,
                    nama_lang as outlet,
                    alamat1 as address,
                    kode_type,
                    kodesalur,
                    b.koderayon,
                    b.kode_kota as kota,
                    b1,
                    b2,
                    b3,
                    b4,
                    b5,
                    b6,b7,b8,b9,b10,b11,b12,
                    (b1+b2+b3+b4+b5+b6+b7+b8+b9+b10+b11+b12)/12 as rata,
                    id 
            from    (
                        select  kode, kode_type, kodesalur,
                                sum(if(blndok=1,unit,0)) as b1,sum(if(blndok=2,unit,0)) as b2,
                                sum(if(blndok=3,unit,0)) as b3,sum(if(blndok=4,unit,0)) as b4,
                                sum(if(blndok=5,unit,0)) as b5,sum(if(blndok=6,unit,0)) as b6,
                                sum(if(blndok=7,unit,0)) as b7,sum(if(blndok=8,unit,0)) as b8,
                                sum(if(blndok=9,unit,0)) as b9,sum(if(blndok=10,unit,0)) as b10,
                                sum(if(blndok=11,unit,0)) as b11,sum(if(blndok=12,unit,0)) as b12,
                                ".$id." as id
                        from
                        (
                            select  concat(kode_comp,kode_lang) kode, KODE_TYPE, kodesalur,
                                    blndok,
                                    sum(".$unit.") as unit 
                            from    data".$year.".fi 
                            where   nocab = ".'"'.$naper.'"'." and 
                                    concat(kodesales) like '%".$kode_sales."%' and 
                                    kodeprod in (".$kodeprod.")  
                            group by    kode,
                                        blndok
                                        
                            union all
                            
                            select  concat(kode_comp,kode_lang) kode, KODE_TYPE, kodesalur,
                                    blndok,
                                    sum(".$unit.") as unit 
                            from    data".$year.".ri 
                            where   nocab = ".'"'.$naper.'"'." and 
                                    concat(kodesales) like '%".$kode_sales."%' and 
                                    kodeprod in (".$kodeprod.")  
                            group by kode,
                                    blndok 
                        ) a                               
                        group by kode
                    )a
                    
                    left join
                    (
                        select  distinct
                                concat(kode_comp,kode_lang) kode,
                                koderayon,
                                nama_lang,
                                alamat1,
                                kode_kota 
                        from    dboutlet.tblang".$kode_comp."
                    )b on a.kode=b.kode group by kode                                       
                ";

        }

        /* -- KHUSUS SIODARJO, KODE OUTLET NYA CONCAT(NOCAB,KODE_LANG) */
        elseif ($naper == '46' && $year == '2016' || $year == '2015' || $year == '2014' || $year == '2013' || $year == '2012' || $year == '2011' || $year == '2010') { 
            
            $sql=" 
            insert into mpm.outlet_new
            select  a.kode,
                    nama_lang as outlet,
                    alamat1 as address,
                    kode_type,
                    kodesalur,
                    b.koderayon,
                    b.kode_kota as kota,
                    b1,
                    b2,
                    b3,
                    b4,
                    b5,
                    b6,b7,b8,b9,b10,b11,b12,
                    (b1+b2+b3+b4+b5+b6+b7+b8+b9+b10+b11+b12)/12 as rata,
                    id 
            from    (
                        select  kode, kode_type, kodesalur,
                                sum(if(blndok=1,unit,0)) as b1,sum(if(blndok=2,unit,0)) as b2,
                                sum(if(blndok=3,unit,0)) as b3,sum(if(blndok=4,unit,0)) as b4,
                                sum(if(blndok=5,unit,0)) as b5,sum(if(blndok=6,unit,0)) as b6,
                                sum(if(blndok=7,unit,0)) as b7,sum(if(blndok=8,unit,0)) as b8,
                                sum(if(blndok=9,unit,0)) as b9,sum(if(blndok=10,unit,0)) as b10,
                                sum(if(blndok=11,unit,0)) as b11,sum(if(blndok=12,unit,0)) as b12,
                                ".$id." as id
                        from
                        (
                            select  concat(nocab,kode_lang) kode, KODE_TYPE, kodesalur,
                                    blndok,
                                    sum(".$unit.") as unit 
                            from    data".$year.".fi 
                            where   nocab = ".'"'.$naper.'"'." and 
                                    concat(kodesales) like '%".$kode_sales."%' and 
                                    kodeprod in (".$kodeprod.")  
                            group by    kode,
                                        blndok
                                        
                            union all
                            
                            select  concat(nocab,kode_lang) kode, KODE_TYPE, kodesalur,
                                    blndok,
                                    sum(".$unit.") as unit 
                            from    data".$year.".ri 
                            where   nocab = ".'"'.$naper.'"'." and 
                                    concat(kodesales) like '%".$kode_sales."%' and 
                                    kodeprod in (".$kodeprod.")  
                            group by kode,
                                    blndok 
                        ) a                               
                        group by kode
                    )a
                    
                    left join
                    (
                        select  distinct
                                concat(nocab,kode_lang) kode,
                                koderayon,
                                nama_lang,
                                alamat1,
                                kode_kota 
                        from    dboutlet.tblang".$kode_comp."
                    )b on a.kode=b.kode group by kode                                         
                ";

        }

        /* -- KHUSUS SIODARJO 2017, KODE OUTLET NYA CONCAT(NOCAB,KODE_COMP,KODE_LANG) */
        elseif ($naper == '46' && $year == '2017') { 
            
            $sql=" 
            insert into mpm.outlet_new
            select  a.kode,
                    nama_lang as outlet,
                    alamat1 as address,
                    kode_type,
                    kodesalur,
                    b.koderayon,
                    b.kode_kota as kota,
                    b1,
                    b2,
                    b3,
                    b4,
                    b5,
                    b6,b7,b8,b9,b10,b11,b12,
                    (b1+b2+b3+b4+b5+b6+b7+b8+b9+b10+b11+b12)/12 as rata,
                    id 
            from    (
                        select  kode, kode_type, kodesalur,
                                sum(if(blndok=1,unit,0)) as b1,sum(if(blndok=2,unit,0)) as b2,
                                sum(if(blndok=3,unit,0)) as b3,sum(if(blndok=4,unit,0)) as b4,
                                sum(if(blndok=5,unit,0)) as b5,sum(if(blndok=6,unit,0)) as b6,
                                sum(if(blndok=7,unit,0)) as b7,sum(if(blndok=8,unit,0)) as b8,
                                sum(if(blndok=9,unit,0)) as b9,sum(if(blndok=10,unit,0)) as b10,
                                sum(if(blndok=11,unit,0)) as b11,sum(if(blndok=12,unit,0)) as b12,
                                ".$id." as id
                        from
                        (
                            select  concat(nocab,kode_comp,kode_lang) kode, KODE_TYPE, kodesalur,
                                    blndok,
                                    sum(".$unit.") as unit 
                            from    data".$year.".fi 
                            where   nocab = ".'"'.$naper.'"'." and 
                                    concat(kodesales) like '%".$kode_sales."%' and 
                                    kodeprod in (".$kodeprod.")  
                            group by    kode,
                                        blndok
                                        
                            union all
                            
                            select  concat(nocab,kode_comp,kode_lang) kode, KODE_TYPE, kodesalur,
                                    blndok,
                                    sum(".$unit.") as unit 
                            from    data".$year.".ri 
                            where   nocab = ".'"'.$naper.'"'." and 
                                    concat(kodesales) like '%".$kode_sales."%' and 
                                    kodeprod in (".$kodeprod.")  
                            group by kode,
                                    blndok 
                        ) a                               
                        group by kode
                    )a
                    
                    left join
                    (
                        select  distinct
                                concat(nocab,kode_comp,kode_lang) kode,
                                koderayon,
                                nama_lang,
                                alamat1,
                                kode_kota 
                        from    dboutlet.tblang".$kode_comp."
                    )b on a.kode=b.kode group by kode                                         
                ";

        }

        /* -- KHUSUS karawang, KODE OUTLET NYA CONCAT(NOCAB,KODE_COMP) */
        elseif ($naper == '07') { 
            
            $sql=" 
            insert into mpm.outlet_new
            select  kode_lang as kode,
                            nama_lang as outlet,
                            alamat1 as address,
                            kode_type,
                            kodesalur,
                            koderayon,
                            kode_kota as kota,
                            b1,
                            b2,
                            b3,
                            b4,
                            b5,
                            b6,b7,b8,b9,b10,b11,b12,
                            (b1+b2+b3+b4+b5+b6+b7+b8+b9+b10+b11+b12)/12 as rata,
                            id 
                    from    (
                                select  kode_lang, kode_type, kodesalur,
                                        sum(if(blndok=1,unit,0)) as b1,sum(if(blndok=2,unit,0)) as b2,
                                        sum(if(blndok=3,unit,0)) as b3,sum(if(blndok=4,unit,0)) as b4,
                                        sum(if(blndok=5,unit,0)) as b5,sum(if(blndok=6,unit,0)) as b6,
                                        sum(if(blndok=7,unit,0)) as b7,sum(if(blndok=8,unit,0)) as b8,
                                        sum(if(blndok=9,unit,0)) as b9,sum(if(blndok=10,unit,0)) as b10,
                                        sum(if(blndok=11,unit,0)) as b11,sum(if(blndok=12,unit,0)) as b12,
                                        ".$id." as id
                                from
                                (
                                    select  kode_lang, KODE_TYPE, kodesalur,
                                            blndok,
                                            sum(".$unit.") as unit 
                                    from    data".$year.".fi 
                                    where   nocab = ".'"'.$naper.'"'." and 
                                            concat(kodesales) like '%".$kode_sales."%' and 
                                            kodeprod in (".$kodeprod.")  
                                    group by    kode_lang,
                                                blndok
                                                
                                    union all
                                    
                                    select  kode_lang, KODE_TYPE, kodesalur,
                                            blndok,
                                            sum(".$unit.") as unit 
                                    from    data".$year.".ri 
                                    where   nocab = ".'"'.$naper.'"'." and 
                                            concat(kodesales) like '%".$kode_sales."%' and 
                                            kodeprod in (".$kodeprod.")  
                                    group by kode_lang,
                                            blndok 
                                ) a
                                
                                group by kode_lang
                            )a
                            
                            left join
                            (
                                select  distinct kode_lang,
                                        koderayon,
                                        nama_lang,
                                        alamat1,
                                        kode_kota 
                                from    data".$year.".tblang 
                                where   nocab = ".'"'.$naper.'"'." 
                            ) b using(kode_lang)                                       
                ";

        }

        
        else{
            $sql=" 
            insert into mpm.outlet_new
            select  a.kode,
                    nama_lang as outlet,
                    alamat1 as address,
                    kode_type,
                    kodesalur,
                    b.koderayon,
                    b.kode_kota as kota,
                    b1,
                    b2,
                    b3,
                    b4,
                    b5,
                    b6,b7,b8,b9,b10,b11,b12,
                    (b1+b2+b3+b4+b5+b6+b7+b8+b9+b10+b11+b12)/12 as rata,
                    id 
            from    (
                        select  kode, kode_type, kodesalur,
                                sum(if(blndok=1,unit,0)) as b1,sum(if(blndok=2,unit,0)) as b2,
                                sum(if(blndok=3,unit,0)) as b3,sum(if(blndok=4,unit,0)) as b4,
                                sum(if(blndok=5,unit,0)) as b5,sum(if(blndok=6,unit,0)) as b6,
                                sum(if(blndok=7,unit,0)) as b7,sum(if(blndok=8,unit,0)) as b8,
                                sum(if(blndok=9,unit,0)) as b9,sum(if(blndok=10,unit,0)) as b10,
                                sum(if(blndok=11,unit,0)) as b11,sum(if(blndok=12,unit,0)) as b12,
                                ".$id." as id
                        from
                        (
                            select  concat(kode_comp,kode_lang) kode, KODE_TYPE, kodesalur,
                                    blndok,
                                    sum(".$unit.") as unit 
                            from    data".$year.".fi 
                            where   nocab = ".'"'.$naper.'"'." and 
                                    concat(kodesales) like '%".$kode_sales."%' and 
                                    kodeprod in (".$kodeprod.")  
                            group by    kode,
                                        blndok
                                        
                            union all
                            
                            select  concat(kode_comp,kode_lang) kode, KODE_TYPE, kodesalur,
                                    blndok,
                                    sum(".$unit.") as unit 
                            from    data".$year.".ri 
                            where   nocab = ".'"'.$naper.'"'." and 
                                    concat(kodesales) like '%".$kode_sales."%' and 
                                    kodeprod in (".$kodeprod.")  
                            group by kode,
                                    blndok 
                        ) a                               
                        group by kode
                    )a
                    
                    left join
                    (
                        select  distinct
                                concat(kode_comp,kode_lang) kode,
                                koderayon,
                                nama_lang,
                                alamat1,
                                kode_kota 
                        from    dboutlet.tblang".$kode_comp."
                    )b on a.kode=b.kode group by kode                                       
                ";
        }


        /* PROSES INSERT KE TABEL OUTLET_NEW */
            /*
            echo "<pre>";
            print_r($sql);
            echo "</pre>";
            */
           $sql_insert = $this->db->query($sql);

        /* PROSES TAMPIL KE WEBSITE */
            //$this->db->join('tbl_tabcomp_new', 'tbl_tabcomp_new.naper = omzet_new.naper','inner');
            //$this->db->where("status = 1");
            //$this->db->order_by('tbl_tabcomp_new.urutan','asc');
            
            $this->db->order_by('kode','asc');
            $this->db->where("id = ".'"'.$id.'"');
            $hasil = $this->db->get('outlet_new');       
            if ($hasil->num_rows() > 0) 
            {
                return $hasil->result();
            } else {
                return array();
            }
            
        /* END PROSES TAMPIL KE WEBSITE */
    }


    public function outlet_dp_new($dataSegment,$kodeprod=null){
       
        $user=$this->session->userdata('username');
        if ( function_exists( 'date_default_timezone_set' ) )
        date_default_timezone_set('Asia/Jakarta');        

        /* ---------DEFINISI VARIABEL----------------- */
        
            $tgl_created='"'.date('Y-m-d H:i:s').'"';        
            $id=$this->session->userdata('id');
            $uv = $dataSegment['uv'];
                switch($uv)
                {
                    case 0:
                        $unit='banyak';
                        break;
                    case 1:
                        $unit='tot1';
                        break;
                }
            $kode_sales = $dataSegment['sm'];
            $year = $dataSegment['tahun'];
            $naper = $dataSegment['dp'];
            $kodeprod = $dataSegment['code'];

            //echo "year : ".$year;
            //echo "<br />dp : ".$naper."<br>";
        /* ---------END DEFINISI VARIABEL---------------- */

        /* cari nilai kode_comp */            
            $this->db->where('naper = '.'"'.$naper.'"');
            $query = $this->db->get('mpm.tabcomp');
            foreach ($query->result() as $row) {
                $kode_comp = $row->KODE_COMP;
                echo "kode_comp : ".$kode_comp."<br />";
            }
        /* end cari nilai kode_comp */

        /* PROSES DELETE MPM.OUTLET_NEW */
            $query = "delete from mpm.outlet_new where id = ".$id."";
            $sql = $this->db->query($query);
            
            //echo "<pre>";
            //print_r($query);
            //echo "</pre>";            
        
        /* END PROSES DELETE MPM.OUTLET_NEW */

        /* PROSES INSERT KE TABEL OUTLET_NEW */
        $sql=" 
            insert into mpm.outlet_new
            select  concat(kode_comp,kode_lang) as kode,
                    nama_lang as outlet,
                    alamat1 as address,
                    kode_type,
                    kodesalur,
                    koderayon,
                    kode_kota as kota,
                    b1,
                    b2,
                    b3,
                    b4,
                    b5,
                    b6,b7,b8,b9,b10,b11,b12,
                    (b1+b2+b3+b4+b5+b6+b7+b8+b9+b10+b11+b12)/12 as rata,
                    id 
            from    (
                        select  kode_comp,kode_lang, kode_type, kodesalur,
                                sum(if(blndok=1,unit,0)) as b1,sum(if(blndok=2,unit,0)) as b2,
                                sum(if(blndok=3,unit,0)) as b3,sum(if(blndok=4,unit,0)) as b4,
                                sum(if(blndok=5,unit,0)) as b5,sum(if(blndok=6,unit,0)) as b6,
                                sum(if(blndok=7,unit,0)) as b7,sum(if(blndok=8,unit,0)) as b8,
                                sum(if(blndok=9,unit,0)) as b9,sum(if(blndok=10,unit,0)) as b10,
                                sum(if(blndok=11,unit,0)) as b11,sum(if(blndok=12,unit,0)) as b12,
                                ".$id." as id
                        from
                        (
                            select  kode_comp,kode_lang, KODE_TYPE, kodesalur,
                                    blndok,
                                    sum(".$unit.") as unit 
                            from    db".$kode_comp.".fi".$year."
                            where   nocab = ".'"'.$naper.'"'." and 
                                    concat(kodesales) like '%".$kode_sales."%' and 
                                    kodeprod in (".$kodeprod.")  
                            group by    kode_lang,
                                        blndok
                                        
                            union all
                            
                            select  kode_comp,kode_lang, KODE_TYPE, kodesalur,
                                    blndok,
                                    sum(".$unit.") as unit 
                            from    db".$kode_comp.".ri".$year."
                            where   nocab = ".'"'.$naper.'"'." and 
                                    concat(kodesales) like '%".$kode_sales."%' and 
                                    kodeprod in (".$kodeprod.")  
                            group by kode_lang,
                                    blndok 
                        ) a                               
                        group by kode_lang
                    )a
                    
                    left join
                    (
                        select  distinct kode_lang,
                                koderayon,
                                nama_lang,
                                alamat1,
                                kode_kota 
                        from    db".$kode_comp.".tblang".$year."
                        where   nocab = ".'"'.$naper.'"'." 
                    ) b using(kode_lang)                                          
                ";

            //echo "<pre>";
            //print_r($sql);
            //echo "</pre>";

            $sql_insert = $this->db->query($sql);

        /* PROSES TAMPIL KE WEBSITE */
            //$this->db->join('tbl_tabcomp_new', 'tbl_tabcomp_new.naper = omzet_new.naper','inner');
            //$this->db->where("status = 1");
            //$this->db->order_by('tbl_tabcomp_new.urutan','asc');
            $this->db->order_by('kode','asc');
            $this->db->where("id = ".'"'.$id.'"');
            $hasil = $this->db->get('outlet_new');       
            if ($hasil->num_rows() > 0) 
            {
                return $hasil->result();
            } else {
                return array();
            }
        /* END PROSES TAMPIL KE WEBSITE */
    }


    

}

/* End of file model_outlet.php */
/* Location: ./application/models/model_outlet.php */