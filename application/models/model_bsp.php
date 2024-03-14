<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_bsp extends CI_Model 
{
    public function getSuppbyid()
    {
        $supp=$this->session->userdata('supp');
        if($supp=='000')
        {            
            return $this->db->query('
                select  supp,namasupp 
                from    mpm.tabsupp
                where   status_bsp = 1
                ');
        }
        else
        {
            return $this->db->query('select supp,namasupp from mpm.tabsupp where supp='.$supp);
        }
    }
    
    public function omzet_all_bsp($dataSegment){

    /* ---------DEFINISI VARIABEL----------------- */
        $id=$this->session->userdata('id');

        if ( function_exists( 'date_default_timezone_set' ) )
        date_default_timezone_set('Asia/Jakarta');        
        $tgl_created='"'.date('Y-m-d H:i:s').'"';

        $year = $dataSegment['tahun'];
        //echo "tahun di model : ".$year;

        $supp = $dataSegment['supp'];
        //echo "supplier di model : ".$supp;

            if($supp=='000')
            {
                $wheresupp='
                    where (kodeprod like "60%" or kodeprod like "01%" or 
                        kodeprod like "50%" or kodeprod like "70%" or 
                        kodeprod like "06%" or kodeprod like "02%" or
                        kodeprod like "04%")
                ';
            }
            else if($supp=='001')
            {                
                $wheresupp='where (kodeprod like "60%" or kodeprod like "01%" or kodeprod like "50%" or kodeprod like "70%")';                    
            }
            else if($supp=='005')
            {
                $wheresupp='where kodeprod like "06%" ';
            }
            else if($supp=='002')
            {
                $wheresupp='where kodeprod like "02%" ';
            }
            else if($supp=='009') //UNILEVER
            {
                $wheresupp='where supp = 009';
            }
            else if($supp=='XXX')
            {                    
                $wheresupp='';
            }
            else
            {
                $wheresupp="where kodeprod like '".substr($supp,-2)."%'";
            }   
        /* cari bulan saat ini */
            $bulan = date("m");
            $tahunsekarang = date("Y");
            
            //echo "bulan saat ini : ".$bulan."<br>";
            //echo "tahun saat ini: ".$tahunsekarang."<br>";
            //echo "tahun yang dipilih user : ".$year;
        
            if ($year == $tahunsekarang) {
                
                if ($bulan == '01')
                {
                    $totalx = 'b2+b3+b4+b5+b6+b7+b8+b9+b10+b11+b12';
                    $pembagi = 'xb2+xb3+xb4+xb5+xb6+xb7+xb8+xb9+xb10+xb11+xb12';
                }elseif ($bulan == '02')
                {
                    $totalx = 'b1+b3+b4+b5+b6+b7+b8+b9+b10+b11+b12';
                    $pembagi = 'xb1+xb3+xb4+xb5+xb6+xb7+xb8+xb9+xb10+xb11+xb12';
                }elseif ($bulan == '03')
                {
                    $totalx = 'b1+b2+b4+b5+b6+b7+b8+b9+b10+b11+b12';
                    $pembagi = 'xb1+xb2+xb4+xb5+xb6+xb7+xb8+xb9+xb10+xb11+xb12';
                }elseif ($bulan == '04')
                {
                    $totalx = 'b1+b2+b3+b5+b6+b7+b8+b9+b10+b11+b12';
                    $pembagi = 'xb1+xb2+xb3+xb5+xb6+xb7+xb8+xb9+xb10+xb11+xb12';
                }elseif ($bulan == '05')
                {
                    $totalx = 'b1+b2+b3+b4+b6+b7+b8+b9+b10+b11+b12';
                    $pembagi = 'xb1+xb2+xb3+xb4+xb6+xb7+xb8+xb9+xb10+xb11+xb12';
                }elseif ($bulan == '06')
                {
                    $totalx = 'b1+b2+b3+b4+b5+b7+b8+b9+b10+b11+b12';
                    $pembagi = 'xb1+xb2+xb3+xb4+xb5+xb7+xb8+xb9+xb10+xb11+xb12';
                }elseif ($bulan == '07')
                {
                    $totalx = 'b1+b2+b3+b4+b5+b6+b8+b9+b10+b11+b12';
                    $pembagi = 'xb1+xb2+xb3+xb4+xb5+xb6+xb8+xb9+xb10+xb11+xb12';
                }elseif ($bulan == '08')
                {
                    $totalx = 'b1+b2+b3+b4+b5+b6+b7+b9+b10+b11+b12';
                    $pembagi = 'xb1+xb2+xb3+xb4+xb5+xb6+xb7+xb9+xb10+xb11+xb12';
                }elseif ($bulan == '09')
                {
                    $totalx = 'b1+b2+b3+b4+b5+b6+b7+b8+b10+b11+b12';
                    $pembagi = 'xb1+xb2+xb3+xb4+xb5+xb6+xb7+xb8+xb10+xb11+xb12';
                }elseif ($bulan == '10')
                {
                    $totalx = 'b1+b2+b3+b4+b5+b6+b7+b8+b9+b11+b12';
                    $pembagi = 'xb1+xb2+xb3+xb4+xb5+xb6+xb7+xb8+xb9+xb11+xb12';
                }elseif ($bulan == '11')
                {
                    $totalx = 'b1+b2+b3+b4+b5+b6+b7+b8+b9+b10+b12';
                    $pembagi = 'xb1+xb2+xb3+xb4+xb5+xb6+xb7+xb8+xb9+xb10+xb12';
                }elseif ($bulan == '12')
                {
                    $totalx = 'b1+b2+b3+b4+b5+b6+b7+b8+b9+b10+b11';
                    $pembagi = 'xb1+xb2+xb3+xb4+xb5+xb6+xb7+xb8+xb9+xb10+xb11';
                }else
                {
                    $totalx = 'b1+b2+b3+b4+b5+b6+b7+b8+b9+b10+b11+b12';
                    $pembagi = 'xb1+xb2+xb3+xb4+xb5+xb6+xb7+xb8+xb9+xb10+xb11+xb12';
                }

            }else{
                $pembagi = 'xb1+xb2+xb3+xb4+xb5+xb6+xb7+xb8+xb9+xb10+xb11+xb12';
                $totalx = 'b1+b2+b3+b4+b5+b6+b7+b8+b9+b10+b11+b12';
            }

            /* end cari bulan saat ini */        
        
        /* ---------END DEFINISI VARIABEL---------------- */

        /* PROSES DELETE MPM.OMZET_NEW */
            $query = "delete from bsp.omzet_new where id = ".$id."";
            $sql = $this->db->query($query);
            
            //echo "<pre>";
            //print_r($query);
            //echo "</pre>";
            
        /* END PROSES DELETE BSP.OMZET_NEW */

        /* PROSES INSERT KE TABEL BSP.OMZET_NEW */
            
            $query = "
                    insert into bsp.omzet_new(namacomp,b1,b2,b3,b4,b5,b6,b7,b8,b9,b10,b11,b12,id,total,rata)
            select  cabang,
                    b1,b2,b3,b4,b5,b6,b7,b8,b9,b10,b11,b12, ".$id.",
                    total,(totalx/jmlbulan) as rata
            from
            (
                select  cabang,
                        b1,b2,b3,b4,b5,b6,b7,b8,b9,b10,b11,b12, ".$id.",
                        total, totalx, format(sum($pembagi),0, 1) jmlbulan
                from
                (                
                    select  cabang,
                            b1,b2,b3,b4,b5,b6,b7,b8,b9,b10,b11,b12,
                            ".$id.", 
                            (b1+b2+b3+b4+b5+b6+b7+b8+b9+b10+b11+b12) as total,$totalx as totalx, 
                            IF(b1 = 0, 0, 1) xb1,IF(b2 = 0, 0, 1) xb2,IF(b3 = 0, 0, 1) xb3,
                            IF(b4 = 0, 0, 1) xb4,IF(b5 = 0, 0, 1) xb5,IF(b6 = 0, 0, 1) xb6,
                            IF(b7 = 0, 0, 1) xb7,IF(b8 = 0, 0, 1) xb8,IF(b9 = 0, 0, 1) xb9,
                            IF(b10 = 0, 0, 1) xb10, IF(b11 = 0, 0, 1) xb11, IF(b12 = 0, 0, 1) xb12
                    FROM
                    (
                        select  cabang, 
                                sum(if(bulan=1,omzet,0)) b1,
                                sum(if(bulan=2,omzet,0)) b2,
                                sum(if(bulan=3,omzet,0)) b3,
                                sum(if(bulan=4,omzet,0)) b4,
                                sum(if(bulan=5,omzet,0)) b5,
                                sum(if(bulan=6,omzet,0)) b6,
                                sum(if(bulan=7,omzet,0)) b7,
                                sum(if(bulan=8,omzet,0)) b8,
                                sum(if(bulan=9,omzet,0)) b9,
                                sum(if(bulan=10,omzet,0)) b10,
                                sum(if(bulan=11,omzet,0)) b11,
                                sum(if(bulan=12,omzet,0)) b12,".$id."
                        from
                        (
                            select  nama_comp cabang, a.KODE_BSP, b.kodeprod,
                                    month(tgldokjdi) bulan,
                                    sum(jumlah)omzet 
                            from    bsp.bspsales".$year."  a LEFT JOIN
                                    (
                                        select  * 
                                        from     bsp.mapprod
                                        GROUP BY kode_bsp
                                    )b on a.KODE_BSP = b.kode_bsp
                                       ".$wheresupp."
                                    group by kode_comp,month(tgldokjdi)
                        )a group by cabang
                    )a
                )a group by cabang
            )a group by cabang
                    
                    union all
                    
                    select  'Z_TOTAL', 
                            sum(if(bulan=1,omzet,0)) b1,
                            sum(if(bulan=2,omzet,0)) b2,
                            sum(if(bulan=3,omzet,0)) b3,
                            sum(if(bulan=4,omzet,0)) b4,
                            sum(if(bulan=5,omzet,0)) b5,
                            sum(if(bulan=6,omzet,0)) b6,
                            sum(if(bulan=7,omzet,0)) b7,
                            sum(if(bulan=8,omzet,0)) b8,
                            sum(if(bulan=9,omzet,0)) b9,
                            sum(if(bulan=10,omzet,0)) b10,
                            sum(if(bulan=11,omzet,0)) b11,
                            sum(if(bulan=12,omzet,0)) b12,".$id.",
                            sum(omzet) as total, '0'
                    from
                    (
                        select  nama_comp cabang, a.KODE_BSP, b.kodeprod,
                                    month(tgldokjdi) bulan,
                                    sum(jumlah)omzet 
                            from    bsp.bspsales".$year."  a LEFT JOIN
                                    (
                                        select  * 
                                        from     bsp.mapprod
                                        GROUP BY kode_bsp
                                    )b on a.KODE_BSP = b.kode_bsp
                                       ".$wheresupp."
                                    group by kode_comp,month(tgldokjdi)
                        )a                    
            ";

            //echo "<pre>";
            //print_r($query);
            //echo $sql;
            //echo "</pre>";
            
            $sql = $this->db->query($query);

        /* END PROSES INSERT KE TABEL BSP.OMZET_NEW */

        /* PROSES TAMPIL KE WEBSITE */
            //$this->db->join('tbl_tabcomp', 'tbl_tabcomp.naper = omzet_new.naper','inner');
            //$this->db->where("status = 1");
            //$this->db->order_by('tbl_tabcomp.urutan','asc');
            $this->db->order_by('namacomp','asc');
            $this->db->where("id = ".'"'.$id.'"');
            $hasil = $this->db->get('bsp.omzet_new');       
            if ($hasil->num_rows() > 0) 
            {
                return $hasil->result();
            } else {
                return array();
            }
        /* END PROSES TAMPIL KE WEBSITE */
    }

    public function omzet_all_bsp_herbal($dataSegment){

    //echo "aa";
    /* ---------DEFINISI VARIABEL----------------- */
        $id=$this->session->userdata('id');

        if ( function_exists( 'date_default_timezone_set' ) )
        date_default_timezone_set('Asia/Jakarta');        
        $tgl_created='"'.date('Y-m-d H:i:s').'"';

        $year = $dataSegment['tahun'];
        //echo "tahun di model : ".$year;

        $supp = $dataSegment['supp'];
        //echo "supplier di model : ".$supp;

        $group = $dataSegment['note_x'];
        //echo "group di model : ".$group;
        if ($group == '4') {
            $var_group = 'G0101';
        }elseif ($group == '5') {
            $var_group = 'G0102';
        }elseif ($group == '12') {
            $var_group = 'G0103';
        }elseif ($group == '13') {
            $var_group = 'G0201';
        }elseif ($group == '14') {
            $var_group = 'G0202';
        }

            if($supp=='000')
            {
                $wheresupp='
                    where (kodeprod like "60%" or kodeprod like "01%" or 
                        kodeprod like "50%" or kodeprod like "70%" or 
                        kodeprod like "06%" or kodeprod like "02%" or
                        kodeprod like "04%")
                ';
            }
            else if($supp=='001')
            {                
                $wheresupp='where (kodeprod like "60%" or kodeprod like "01%" or kodeprod like "50%" or kodeprod like "70%")';                    
            }
            else if($supp=='005')
            {
                $wheresupp='where kodeprod like "06%" ';
            }
            else if($supp=='002')
            {
                $wheresupp='where kodeprod like "02%" ';
            }
            else if($supp=='009') //UNILEVER
            {
                $wheresupp='where supp = 009';
            }
            else if($supp=='XXX')
            {                    
                $wheresupp='';
            }
            else
            {
                $wheresupp="where kodeprod like '".substr($supp,-2)."%'";
            }   
        /* cari bulan saat ini */
            $bulan = date("m");
            $tahunsekarang = date("Y");
            
            //echo "bulan saat ini : ".$bulan."<br>";
            //echo "tahun saat ini: ".$tahunsekarang."<br>";
            //echo "tahun yang dipilih user : ".$year;
        
            if ($year == $tahunsekarang) {
                
                if ($bulan == '01')
                {
                    $totalx = 'b2+b3+b4+b5+b6+b7+b8+b9+b10+b11+b12';
                    $pembagi = 'xb2+xb3+xb4+xb5+xb6+xb7+xb8+xb9+xb10+xb11+xb12';
                }elseif ($bulan == '02')
                {
                    $totalx = 'b1+b3+b4+b5+b6+b7+b8+b9+b10+b11+b12';
                    $pembagi = 'xb1+xb3+xb4+xb5+xb6+xb7+xb8+xb9+xb10+xb11+xb12';
                }elseif ($bulan == '03')
                {
                    $totalx = 'b1+b2+b4+b5+b6+b7+b8+b9+b10+b11+b12';
                    $pembagi = 'xb1+xb2+xb4+xb5+xb6+xb7+xb8+xb9+xb10+xb11+xb12';
                }elseif ($bulan == '04')
                {
                    $totalx = 'b1+b2+b3+b5+b6+b7+b8+b9+b10+b11+b12';
                    $pembagi = 'xb1+xb2+xb3+xb5+xb6+xb7+xb8+xb9+xb10+xb11+xb12';
                }elseif ($bulan == '05')
                {
                    $totalx = 'b1+b2+b3+b4+b6+b7+b8+b9+b10+b11+b12';
                    $pembagi = 'xb1+xb2+xb3+xb4+xb6+xb7+xb8+xb9+xb10+xb11+xb12';
                }elseif ($bulan == '06')
                {
                    $totalx = 'b1+b2+b3+b4+b5+b7+b8+b9+b10+b11+b12';
                    $pembagi = 'xb1+xb2+xb3+xb4+xb5+xb7+xb8+xb9+xb10+xb11+xb12';
                }elseif ($bulan == '07')
                {
                    $totalx = 'b1+b2+b3+b4+b5+b6+b8+b9+b10+b11+b12';
                    $pembagi = 'xb1+xb2+xb3+xb4+xb5+xb6+xb8+xb9+xb10+xb11+xb12';
                }elseif ($bulan == '08')
                {
                    $totalx = 'b1+b2+b3+b4+b5+b6+b7+b9+b10+b11+b12';
                    $pembagi = 'xb1+xb2+xb3+xb4+xb5+xb6+xb7+xb9+xb10+xb11+xb12';
                }elseif ($bulan == '09')
                {
                    $totalx = 'b1+b2+b3+b4+b5+b6+b7+b8+b10+b11+b12';
                    $pembagi = 'xb1+xb2+xb3+xb4+xb5+xb6+xb7+xb8+xb10+xb11+xb12';
                }elseif ($bulan == '10')
                {
                    $totalx = 'b1+b2+b3+b4+b5+b6+b7+b8+b9+b11+b12';
                    $pembagi = 'xb1+xb2+xb3+xb4+xb5+xb6+xb7+xb8+xb9+xb11+xb12';
                }elseif ($bulan == '11')
                {
                    $totalx = 'b1+b2+b3+b4+b5+b6+b7+b8+b9+b10+b12';
                    $pembagi = 'xb1+xb2+xb3+xb4+xb5+xb6+xb7+xb8+xb9+xb10+xb12';
                }elseif ($bulan == '12')
                {
                    $totalx = 'b1+b2+b3+b4+b5+b6+b7+b8+b9+b10+b11';
                    $pembagi = 'xb1+xb2+xb3+xb4+xb5+xb6+xb7+xb8+xb9+xb10+xb11';
                }else
                {
                    $totalx = 'b1+b2+b3+b4+b5+b6+b7+b8+b9+b10+b11+b12';
                    $pembagi = 'xb1+xb2+xb3+xb4+xb5+xb6+xb7+xb8+xb9+xb10+xb11+xb12';
                }

            }else{
                $pembagi = 'xb1+xb2+xb3+xb4+xb5+xb6+xb7+xb8+xb9+xb10+xb11+xb12';
                $totalx = 'b1+b2+b3+b4+b5+b6+b7+b8+b9+b10+b11+b12';
            }

            /* end cari bulan saat ini */        
        
        /* ---------END DEFINISI VARIABEL---------------- */

        /* PROSES DELETE MPM.OMZET_NEW */
            $query = "delete from bsp.omzet_new where id = ".$id."";
            $sql = $this->db->query($query);
            
            //echo "<pre>";
            //print_r($query);
            //echo "</pre>";
            
        /* END PROSES DELETE BSP.OMZET_NEW */

        /* PROSES INSERT KE TABEL BSP.OMZET_NEW */
            
            $query = "
                    insert into bsp.omzet_new(namacomp,b1,b2,b3,b4,b5,b6,b7,b8,b9,b10,b11,b12,id,total,rata)
            select  cabang,
                    b1,b2,b3,b4,b5,b6,b7,b8,b9,b10,b11,b12, ".$id.",
                    total,(totalx/jmlbulan) as rata
            from
            (
                select  cabang,
                        b1,b2,b3,b4,b5,b6,b7,b8,b9,b10,b11,b12, ".$id.",
                        total, totalx, format(sum($pembagi),0, 1) jmlbulan
                from
                (                
                    select  cabang,
                            b1,b2,b3,b4,b5,b6,b7,b8,b9,b10,b11,b12,
                            ".$id.", 
                            (b1+b2+b3+b4+b5+b6+b7+b8+b9+b10+b11+b12) as total,$totalx as totalx, 
                            IF(b1 = 0, 0, 1) xb1,IF(b2 = 0, 0, 1) xb2,IF(b3 = 0, 0, 1) xb3,
                            IF(b4 = 0, 0, 1) xb4,IF(b5 = 0, 0, 1) xb5,IF(b6 = 0, 0, 1) xb6,
                            IF(b7 = 0, 0, 1) xb7,IF(b8 = 0, 0, 1) xb8,IF(b9 = 0, 0, 1) xb9,
                            IF(b10 = 0, 0, 1) xb10, IF(b11 = 0, 0, 1) xb11, IF(b12 = 0, 0, 1) xb12
                    FROM
                    (
                        select  cabang, 
                                sum(if(bulan=1,omzet,0)) b1,
                                sum(if(bulan=2,omzet,0)) b2,
                                sum(if(bulan=3,omzet,0)) b3,
                                sum(if(bulan=4,omzet,0)) b4,
                                sum(if(bulan=5,omzet,0)) b5,
                                sum(if(bulan=6,omzet,0)) b6,
                                sum(if(bulan=7,omzet,0)) b7,
                                sum(if(bulan=8,omzet,0)) b8,
                                sum(if(bulan=9,omzet,0)) b9,
                                sum(if(bulan=10,omzet,0)) b10,
                                sum(if(bulan=11,omzet,0)) b11,
                                sum(if(bulan=12,omzet,0)) b12,".$id."
                        from
                        (
                            select  nama_comp cabang, a.KODE_BSP, b.kodeprod,
                                    month(tgldokjdi) bulan,
                                    sum(jumlah)omzet 
                            from    bsp.bspsales".$year."  a LEFT JOIN
                                    (
                                        select  * 
                                        from     bsp.mapprod
                                        GROUP BY kode_bsp
                                    )b on a.KODE_BSP = b.kode_bsp
                                       ".$wheresupp." and grup = '$var_group'
                                    group by kode_comp,month(tgldokjdi)
                        )a group by cabang
                    )a
                )a group by cabang
            )a group by cabang
                    
                    union all
                    
                    select  'Z_TOTAL', 
                            sum(if(bulan=1,omzet,0)) b1,
                            sum(if(bulan=2,omzet,0)) b2,
                            sum(if(bulan=3,omzet,0)) b3,
                            sum(if(bulan=4,omzet,0)) b4,
                            sum(if(bulan=5,omzet,0)) b5,
                            sum(if(bulan=6,omzet,0)) b6,
                            sum(if(bulan=7,omzet,0)) b7,
                            sum(if(bulan=8,omzet,0)) b8,
                            sum(if(bulan=9,omzet,0)) b9,
                            sum(if(bulan=10,omzet,0)) b10,
                            sum(if(bulan=11,omzet,0)) b11,
                            sum(if(bulan=12,omzet,0)) b12,".$id.",
                            sum(omzet) as total, '0'
                    from
                    (
                        select  nama_comp cabang, a.KODE_BSP, b.kodeprod,
                                    month(tgldokjdi) bulan,
                                    sum(jumlah)omzet 
                            from    bsp.bspsales".$year."  a LEFT JOIN
                                    (
                                        select  * 
                                        from     bsp.mapprod
                                        GROUP BY kode_bsp
                                    )b on a.KODE_BSP = b.kode_bsp
                                       ".$wheresupp." and grup = '$var_group'
                                    group by kode_comp,month(tgldokjdi)
                        )a                    
            ";

            // echo "<pre>";
            // print_r($query);
            // echo "</pre>";
            
            $sql = $this->db->query($query);

        /* END PROSES INSERT KE TABEL BSP.OMZET_NEW */

        /* PROSES TAMPIL KE WEBSITE */
            //$this->db->join('tbl_tabcomp', 'tbl_tabcomp.naper = omzet_new.naper','inner');
            //$this->db->where("status = 1");
            //$this->db->order_by('tbl_tabcomp.urutan','asc');
            $this->db->order_by('namacomp','asc');
            $this->db->where("id = ".'"'.$id.'"');
            $hasil = $this->db->get('bsp.omzet_new');       
            if ($hasil->num_rows() > 0) 
            {
                return $hasil->result();
            } else {
                return array();
            }
        /* END PROSES TAMPIL KE WEBSITE */
    }


    public function omzet_all_bsp_group($dataSegment){

    /* ---------DEFINISI VARIABEL----------------- */
        $id=$this->session->userdata('id');

        if ( function_exists( 'date_default_timezone_set' ) )
        date_default_timezone_set('Asia/Jakarta');        
        $tgl_created='"'.date('Y-m-d H:i:s').'"';
        $year = $dataSegment['tahun'];        
        $supp = $dataSegment['supp'];
        $jam=date('ymdHis');
        $key_jam = $jam;
        /*
        echo "<pre>";
        echo "tgl_created : ".$tgl_created."<br>";
        echo "jam : ".$jam."<br>";
        echo "key_jam : ".$key_jam;
        echo "</pre>";
        */
        //echo "supplier di model : ".$supp;

            if($supp=='000')
            {
                $wheresupp='
                    where (kodeprod like "60%" or kodeprod like "01%" or 
                        kodeprod like "50%" or kodeprod like "70%" or 
                        kodeprod like "06%" or kodeprod like "02%" or
                        kodeprod like "04%")
                ';
            }
            else if($supp=='001')
            {                
                $wheresupp='where (kodeprod like "60%" or kodeprod like "01%" or kodeprod like "50%" or kodeprod like "70%")';              
            }
            else if($supp=='005')
            {
                $wheresupp='where kodeprod like "06%" ';
            }
            else if($supp=='002')
            {
                $wheresupp='where kodeprod like "02%" ';
            }
            else if($supp=='009') //UNILEVER
            {
                $wheresupp='where supp = 009';
            }
            else if($supp=='XXX')
            {                    
                $wheresupp='';
            }
            else
            {
                $wheresupp="where kodeprod like '".substr($supp,-2)."%'";
            }   
        /* cari bulan saat ini */
            $bulan = date("m");
            $tahunsekarang = date("Y");
            
            //echo "bulan saat ini : ".$bulan."<br>";
            //echo "tahun saat ini: ".$tahunsekarang."<br>";
            //echo "tahun yang dipilih user : ".$year;
        
            if ($year == $tahunsekarang) {
                
                if ($bulan == '01')
                {
                    $totalx = 'b2+b3+b4+b5+b6+b7+b8+b9+b10+b11+b12';
                    $pembagi = 'xb2+xb3+xb4+xb5+xb6+xb7+xb8+xb9+xb10+xb11+xb12';
                    $totalx_candy = 'b2_candy+b3_candy+b4_candy+b5_candy+b6_candy+b7_candy+b8_candy+b9_candy+b10_candy+b11_candy+b12_candy';
                    $pembagi_candy = 'xb2_candy+xb3_candy+xb4_candy+xb5_candy+xb6_candy+xb7_candy+xb8_candy+xb9_candy+xb10_candy+xb11_candy+xb12_candy';
                    $totalx_herbal = 'b2_herbal+b3_herbal+b4_herbal+b5_herbal+b6_herbal+b7_herbal+b8_herbal+b9_herbal+b10_herbal+b11_herbal+b12_herbal';
                    $pembagi_herbal = 'xb2_herbal+xb3_herbal+xb4_herbal+xb5_herbal+xb6_herbal+xb7_herbal+xb8_herbal+xb9_herbal+xb10_herbal+xb11_herbal+xb12_herbal';
                }elseif ($bulan == '02')
                {
                    $totalx = 'b1+b3+b4+b5+b6+b7+b8+b9+b10+b11+b12';
                    $pembagi = 'xb1+xb3+xb4+xb5+xb6+xb7+xb8+xb9+xb10+xb11+xb12';
                    $totalx_candy = 'b1_candy+b3_candy+b4_candy+b5_candy+b6_candy+b7_candy+b8_candy+b9_candy+b10_candy+b11_candy+b12_candy';
                    $pembagi_candy = 'xb1_candy+xb3_candy+xb4_candy+xb5_candy+xb6_candy+xb7_candy+xb8_candy+xb9_candy+xb10_candy+xb11_candy+xb12_candy';
                    $totalx_herbal = 'b1_herbal+b3_herbal+b4_herbal+b5_herbal+b6_herbal+b7_herbal+b8_herbal+b9_herbal+b10_herbal+b11_herbal+b12_herbal';
                    $pembagi_herbal = 'xb1_herbal+xb3_herbal+xb4_herbal+xb5_herbal+xb6_herbal+xb7_herbal+xb8_herbal+xb9_herbal+xb10_herbal+xb11_herbal+xb12_herbal';
                }elseif ($bulan == '03')
                {
                    $totalx = 'b1+b2+b4+b5+b6+b7+b8+b9+b10+b11+b12';
                    $pembagi = 'xb1+xb2+xb4+xb5+xb6+xb7+xb8+xb9+xb10+xb11+xb12';
                    $totalx_candy = 'b1_candy+b2_candy+b4_candy+b5_candy+b6_candy+b7_candy+b8_candy+b9_candy+b10_candy+b11_candy+b12_candy';
                    $pembagi_candy = 'xb1_candy+xb2_candy+xb4_candy+xb5_candy+xb6_candy+xb7_candy+xb8_candy+xb9_candy+xb10_candy+xb11_candy+xb12_candy';
                    $totalx_herbal = 'b1_herbal+b2_herbal+b4_herbal+b5_herbal+b6_herbal+b7_herbal+b8_herbal+b9_herbal+b10_herbal+b11_herbal+b12_herbal';
                    $pembagi_herbal = 'xb1_herbal+xb2_herbal+xb4_herbal+xb5_herbal+xb6_herbal+xb7_herbal+xb8_herbal+xb9_herbal+xb10_herbal+xb11_herbal+xb12_herbal';
                }elseif ($bulan == '04')
                {
                    $totalx = 'b1+b2+b3+b5+b6+b7+b8+b9+b10+b11+b12';
                    $pembagi = 'xb1+xb2+xb3+xb5+xb6+xb7+xb8+xb9+xb10+xb11+xb12';
                    $totalx_candy = 'b1_candy+b2_candy+b3_candy+b5_candy+b6_candy+b7_candy+b8_candy+b9_candy+b10_candy+b11_candy+b12_candy';
                    $pembagi_candy = 'xb1_candy+xb2_candy+xb3_candy+xb5_candy+xb6_candy+xb7_candy+xb8_candy+xb9_candy+xb10_candy+xb11_candy+xb12_candy';
                    $totalx_herbal = 'b1_herbal+b2_herbal+b3_herbal+b5_herbal+b6_herbal+b7_herbal+b8_herbal+b9_herbal+b10_herbal+b11_herbal+b12_herbal';
                    $pembagi_herbal = 'xb1_herbal+xb2_herbal+xb3_herbal+xb5_herbal+xb6_herbal+xb7_herbal+xb8_herbal+xb9_herbal+xb10_herbal+xb11_herbal+xb12_herbal';
                }elseif ($bulan == '05')
                {
                    $totalx = 'b1+b2+b3+b4+b6+b7+b8+b9+b10+b11+b12';
                    $pembagi = 'xb1+xb2+xb3+xb4+xb6+xb7+xb8+xb9+xb10+xb11+xb12';
                    $totalx_candy = 'b1_candy+b2_candy+b3_candy+b4_candy+b6_candy+b7_candy+b8_candy+b9_candy+b10_candy+b11_candy+b12_candy';
                    $pembagi_candy = 'xb1_candy+xb2_candy+xb3_candy+xb4_candy+xb6_candy+xb7_candy+xb8_candy+xb9_candy+xb10_candy+xb11_candy+xb12_candy';
                    $totalx_herbal = 'b1_herbal+b2_herbal+b3_herbal+b4_herbal+b6_herbal+b7_herbal+b8_herbal+b9_herbal+b10_herbal+b11_herbal+b12_herbal';
                    $pembagi_herbal = 'xb1_herbal+xb2_herbal+xb3_herbal+xb4_herbal+xb6_herbal+xb7_herbal+xb8_herbal+xb9_herbal+xb10_herbal+xb11_herbal+xb12_herbal';
                }elseif ($bulan == '06')
                {
                    $totalx = 'b1+b2+b3+b4+b5+b7+b8+b9+b10+b11+b12';
                    $pembagi = 'xb1+xb2+xb3+xb4+xb5+xb7+xb8+xb9+xb10+xb11+xb12';
                    $totalx_candy = 'b1_candy+b2_candy+b3_candy+b4_candy+b5_candy+b7_candy+b8_candy+b9_candy+b10_candy+b11_candy+b12_candy';
                    $pembagi_candy = 'xb1_candy+xb2_candy+xb3_candy+xb4_candy+xb5_candy+xb7_candy+xb8_candy+xb9_candy+xb10_candy+xb11_candy+xb12_candy';
                    $totalx_herbal = 'b1_herbal+b2_herbal+b3_herbal+b4_herbal+b5_herbal+b7_herbal+b8_herbal+b9_herbal+b10_herbal+b11_herbal+b12_herbal';
                    $pembagi_herbal = 'xb1_herbal+xb2_herbal+xb3_herbal+xb4_herbal+xb5_herbal+xb7_herbal+xb8_herbal+xb9_herbal+xb10_herbal+xb11_herbal+xb12_herbal';
                }elseif ($bulan == '07')
                {
                    $totalx = 'b1+b2+b3+b4+b5+b6+b8+b9+b10+b11+b12';
                    $pembagi = 'xb1+xb2+xb3+xb4+xb5+xb6+xb8+xb9+xb10+xb11+xb12';
                    $totalx_candy = 'b1_candy+b2_candy+b3_candy+b4_candy+b5_candy+b6_candy+b8_candy+b9_candy+b10_candy+b11_candy+b12_candy';
                    $pembagi_candy = 'xb1_candy+xb2_candy+xb3_candy+xb4_candy+xb5_candy+xb6_candy+xb8_candy+xb9_candy+xb10_candy+xb11_candy+xb12_candy';
                    $totalx_herbal = 'b1_herbal+b2_herbal+b3_herbal+b4_herbal+b5_herbal+b6_herbal+b8_herbal+b9_herbal+b10_herbal+b11_herbal+b12_herbal';
                    $pembagi_herbal = 'xb1_herbal+xb2_herbal+xb3_herbal+xb4_herbal+xb5_herbal+xb6_herbal+xb8_herbal+xb9_herbal+xb10_herbal+xb11_herbal+xb12_herbal';
                }elseif ($bulan == '08')
                {
                    $totalx = 'b1+b2+b3+b4+b5+b6+b7+b9+b10+b11+b12';
                    $pembagi = 'xb1+xb2+xb3+xb4+xb5+xb6+xb7+xb9+xb10+xb11+xb12';
                    $totalx_candy = 'b1_candy+b2_candy+b3_candy+b4_candy+b5_candy+b6_candy+b7_candy+b9_candy+b10_candy+b11_candy+b12_candy';
                    $pembagi_candy = 'xb1_candy+xb2_candy+xb3_candy+xb4_candy+xb5_candy+xb6_candy+xb7_candy+xb9_candy+xb10_candy+xb11_candy+xb12_candy';
                    $totalx_herbal = 'b1_herbal+b2_herbal+b3_herbal+b4_herbal+b5_herbal+b6_herbal+b7_herbal+b9_herbal+b10_herbal+b11_herbal+b12_herbal';
                    $pembagi_herbal = 'xb1_herbal+xb2_herbal+xb3_herbal+xb4_herbal+xb5_herbal+xb6_herbal+xb7_herbal+xb9_herbal+xb10_herbal+xb11_herbal+xb12_herbal';
                }elseif ($bulan == '09')
                {
                    $totalx = 'b1+b2+b3+b4+b5+b6+b7+b8+b10+b11+b12';
                    $pembagi = 'xb1+xb2+xb3+xb4+xb5+xb6+xb7+xb8+xb10+xb11+xb12';
                    $totalx_candy = 'b1_candy+b2_candy+b3_candy+b4_candy+b5_candy+b6_candy+b7_candy+b8_candy+b10_candy+b11_candy+b12_candy';
                    $pembagi_candy = 'xb1_candy+xb2_candy+xb3_candy+xb4_candy+xb5_candy+xb6_candy+xb7_candy+xb8_candy+xb10_candy+xb11_candy+xb12_candy';
                    $totalx_herbal = 'b1_herbal+b2_herbal+b3_herbal+b4_herbal+b5_herbal+b6_herbal+b7_herbal+b8_herbal+b10_herbal+b11_herbal+b12_herbal';
                    $pembagi_herbal = 'xb1_herbal+xb2_herbal+xb3_herbal+xb4_herbal+xb5_herbal+xb6_herbal+xb7_herbal+xb8_herbal+xb10_herbal+xb11_herbal+xb12_herbal';
                }elseif ($bulan == '10')
                {
                    $totalx = 'b1+b2+b3+b4+b5+b6+b7+b8+b9+b11+b12';
                    $pembagi = 'xb1+xb2+xb3+xb4+xb5+xb6+xb7+xb8+xb9+xb11+xb12';
                    $totalx_candy = 'b1_candy+b2_candy+b3_candy+b4_candy+b5_candy+b6_candy+b7_candy+b8_candy+b9_candy+b11_candy+b12_candy';
                    $pembagi_candy = 'xb1_candy+xb2_candy+xb3_candy+xb4_candy+xb5_candy+xb6_candy+xb7_candy+xb8_candy+xb9_candy+xb11_candy+xb12_candy';
                    $totalx_herbal = 'b1_herbal+b2_herbal+b3_herbal+b4_herbal+b5_herbal+b6_herbal+b7_herbal+b8_herbal+b9_herbal+b11_herbal+b12_herbal';
                    $pembagi_herbal = 'xb1_herbal+xb2_herbal+xb3_herbal+xb4_herbal+xb5_herbal+xb6_herbal+xb7_herbal+xb8_herbal+xb9_herbal+xb11_herbal+xb12_herbal';
                }elseif ($bulan == '11')
                {
                    $totalx = 'b1+b2+b3+b4+b5+b6+b7+b8+b9+b10+b12';
                    $pembagi = 'xb1+xb2+xb3+xb4+xb5+xb6+xb7+xb8+xb9+xb10+xb12';
                    $totalx_candy = 'b1_candy+b2_candy+b3_candy+b4_candy+b5_candy+b6_candy+b7_candy+b8_candy+b9_candy+b10_candy+b12_candy';
                    $pembagi_candy = 'xb1_candy+xb2_candy+xb3_candy+xb4_candy+xb5_candy+xb6_candy+xb7_candy+xb8_candy+xb9_candy+xb10_candy+xb12_candy';
                    $totalx_herbal = 'b1_herbal+b2_herbal+b3_herbal+b4_herbal+b5_herbal+b6_herbal+b7_herbal+b8_herbal+b9_herbal+b10_herbal+b12_herbal';
                    $pembagi_herbal = 'xb1_herbal+xb2_herbal+xb3_herbal+xb4_herbal+xb5_herbal+xb6_herbal+xb7_herbal+xb8_herbal+xb9_herbal+xb10_herbal+xb12_herbal';
                }elseif ($bulan == '12')
                {
                    $totalx = 'b1+b2+b3+b4+b5+b6+b7+b8+b9+b10+b11';
                    $pembagi = 'xb1+xb2+xb3+xb4+xb5+xb6+xb7+xb8+xb9+xb10+xb11';
                    $totalx_candy = 'b1_candy+b2_candy+b3_candy+b4_candy+b5_candy+b6_candy+b7_candy+b8_candy+b9_candy+b10_candy+b11_candy';
                    $pembagi_candy = 'xb1_candy+xb2_candy+xb3_candy+xb4_candy+xb5_candy+xb6_candy+xb7_candy+xb8_candy+xb9_candy+xb10_candy+xb11_candy';
                    $totalx_herbal = 'b1_herbal+b2_herbal+b3_herbal+b4_herbal+b5_herbal+b6_herbal+b7_herbal+b8_herbal+b9_herbal+b10_herbal+b11_herbal';
                    $pembagi_herbal = 'xb1_herbal+xb2_herbal+xb3_herbal+xb4_herbal+xb5_herbal+xb6_herbal+xb7_herbal+xb8_herbal+xb9_herbal+xb10_herbal+xb11_herbal';
                }else
                {
                    $totalx = 'b1+b2+b3+b4+b5+b6+b7+b8+b9+b10+b11+b12';
                    $pembagi = 'xb1+xb2+xb3+xb4+xb5+xb6+xb7+xb8+xb9+xb10+xb11+xb12';
                    $totalx_candy= 'b1_candy+b2_candy+b3_candy+b4_candy+b5_candy+b6_candy+b7_candy+b8_candy+b9_candy+b10_candy+b11_candy+b12_candy';
                    $pembagi_candy = 'xb1_candy+xb2_candy+xb3_candy+xb4_candy+xb5_candy+xb6_candy+xb7_candy+xb8_candy+xb9_candy+xb10_candy+xb11_candy+xb12_candy';
                    $totalx_herbal = 'b1_herbal+b2_herbal+b3_herbal+b4_herbal+b5_herbal+b6_herbal+b7_herbal+b8_herbal+b9_herbal+b10_herbal+b11_herbal+b12_herbal';
                    $pembagi_herbal = 'xb1_herbal+xb2_herbal+xb3_herbal+xb4_herbal+xb5_herbal+xb6_herbal+xb7_herbal+xb8_herbal+xb9_herbal+xb10_herbal+xb11_herbal+xb12_herbal';
                }

            }else{
                $totalx = 'b1+b2+b3+b4+b5+b6+b7+b8+b9+b10+b11+b12';
                $pembagi = 'xb1+xb2+xb3+xb4+xb5+xb6+xb7+xb8+xb9+xb10+xb11+xb12';
                $totalx_candy= 'b1_candy+b2_candy+b3_candy+b4_candy+b5_candy+b6_candy+b7_candy+b8_candy+b9_candy+b10_candy+b11_candy+b12_candy';
                $pembagi_candy = 'xb1_candy+xb2_candy+xb3_candy+xb4_candy+xb5_candy+xb6_candy+xb7_candy+xb8_candy+xb9_candy+xb10_candy+xb11_candy+xb12_candy';
                $totalx_herbal = 'b1_herbal+b2_herbal+b3_herbal+b4_herbal+b5_herbal+b6_herbal+b7_herbal+b8_herbal+b9_herbal+b10_herbal+b11_herbal+b12_herbal';
                $pembagi_herbal = 'xb1_herbal+xb2_herbal+xb3_herbal+xb4_herbal+xb5_herbal+xb6_herbal+xb7_herbal+xb8_herbal+xb9_herbal+xb10_herbal+xb11_herbal+xb12_herbal';
            }

            /* end cari bulan saat ini */        
        
        /* ---------END DEFINISI VARIABEL---------------- */

        /* PROSES DELETE MPM.OMZET_NEW */
            $query = "delete from bsp.omzet_new where id = ".$id."";
            $sql = $this->db->query($query);
            
            //echo "<pre>";
            //print_r($query);
            //echo "</pre>";
            
        /* END PROSES DELETE BSP.OMZET_NEW */

        /* PROSES INSERT KE TABEL BSP.OMZET_NEW */
            
            $query = "
                    insert into bsp.omzet_new_deltomed
            select  cabang,
                    b1,b2,b3,b4,b5,b6,b7,b8,b9,b10,b11,b12,
                    b1_candy,b2_candy,b3_candy,b4_candy,b5_candy,b6_candy,b7_candy,b8_candy,b9_candy,
                    b10_candy,b11_candy,b12_candy,b1_herbal,b2_herbal,b3_herbal,b4_herbal,b5_herbal,
                    b6_herbal,b7_herbal,b8_herbal,b9_herbal,b10_herbal,b11_herbal,b12_herbal,
                    total,total_candy,total_herbal,(totalx/jmlbulan) as rata,(totalx_candy/jmlbulan_candy) as rata_candy,(totalx_herbal/jmlbulan_herbal) as rata_herbal, ".$id.",".$year.",".$key_jam."
            from
            (
                select  cabang,
                        b1,b2,b3,b4,b5,b6,b7,b8,b9,b10,b11,b12,
                        b1_candy,b2_candy,b3_candy,b4_candy,b5_candy,b6_candy,b7_candy,b8_candy,b9_candy,
                        b10_candy,b11_candy,b12_candy,b1_herbal,b2_herbal,b3_herbal,b4_herbal,b5_herbal,
                        b6_herbal,b7_herbal,b8_herbal,b9_herbal,b10_herbal,b11_herbal,b12_herbal,
                        total,total_candy,total_herbal, totalx,totalx_candy,totalx_herbal, format(sum($pembagi),0, 1) jmlbulan,format(sum($pembagi_candy),0, 1) jmlbulan_candy,format(sum($pembagi_herbal),0, 1) jmlbulan_herbal,".$id."
                from
                (                
                    select  cabang,
                            b1,b2,b3,b4,b5,b6,b7,b8,b9,b10,b11,b12, 
                            b1_candy,b2_candy,b3_candy,b4_candy,b5_candy,b6_candy,b7_candy,b8_candy,b9_candy,b10_candy,b11_candy,b12_candy,
                            b1_herbal,b2_herbal,b3_herbal,b4_herbal,b5_herbal,b6_herbal,b7_herbal,b8_herbal,b9_herbal,b10_herbal,b11_herbal,b12_herbal,
                            (b1+b2+b3+b4+b5+b6+b7+b8+b9+b10+b11+b12) as total,
                            (b1_candy+b2_candy+b3_candy+b4_candy+b5_candy+b6_candy+b7_candy+b8_candy+b9_candy+b10_candy+b11_candy+b12_candy) as total_candy,
                            (b1_herbal+b2_herbal+b3_herbal+b4_herbal+b5_herbal+b6_herbal+b7_herbal+b8_herbal+b9_herbal+b10_herbal+b11_herbal+b12_herbal) as total_herbal,
                            $totalx as totalx, $totalx_candy as totalx_candy, $totalx_herbal as totalx_herbal, 
                            IF(b1 = 0, 0, 1) xb1,IF(b1_candy = 0, 0, 1) xb1_candy,IF(b1_herbal = 0, 0, 1) xb1_herbal,
                            IF(b2 = 0, 0, 1) xb2,IF(b2_candy = 0, 0, 1) xb2_candy,IF(b2_herbal = 0, 0, 1) xb2_herbal,
                            IF(b3 = 0, 0, 1) xb3,IF(b3_candy = 0, 0, 1) xb3_candy,IF(b3_herbal = 0, 0, 1) xb3_herbal,
                            IF(b4 = 0, 0, 1) xb4,IF(b4_candy = 0, 0, 1) xb4_candy,IF(b4_herbal = 0, 0, 1) xb4_herbal,
                            IF(b5 = 0, 0, 1) xb5,IF(b5_candy = 0, 0, 1) xb5_candy,IF(b5_herbal = 0, 0, 1) xb5_herbal,
                            IF(b6 = 0, 0, 1) xb6,IF(b6_candy = 0, 0, 1) xb6_candy,IF(b6_herbal = 0, 0, 1) xb6_herbal,
                            IF(b7 = 0, 0, 1) xb7,IF(b7_candy = 0, 0, 1) xb7_candy,IF(b7_herbal = 0, 0, 1) xb7_herbal,
                            IF(b8 = 0, 0, 1) xb8,IF(b8_candy = 0, 0, 1) xb8_candy,IF(b8_herbal = 0, 0, 1) xb8_herbal,
                            IF(b9 = 0, 0, 1) xb9,IF(b9_candy = 0, 0, 1) xb9_candy,IF(b9_herbal = 0, 0, 1) xb9_herbal,
                            IF(b10 = 0, 0, 1) xb10, IF(b10_candy = 0, 0, 1) xb10_candy, IF(b10_herbal = 0, 0, 1) xb10_herbal, 
                            IF(b11 = 0, 0, 1) xb11, IF(b11_candy = 0, 0, 1) xb11_candy, IF(b11_herbal = 0, 0, 1) xb11_herbal, 
                            IF(b12 = 0, 0, 1) xb12,IF(b12_candy = 0, 0, 1) xb12_candy,IF(b12_herbal = 0, 0, 1) xb12_herbal,".$id."
                    FROM
                    (
                        select  cabang, 
                                sum(if(bulan = 1 and `status` = 'all', omzet, 0)) as b1,
                                sum(if(bulan = 1 and `status` = 'candy', omzet, 0)) as b1_candy,
                                sum(if(bulan = 1 and `status` = 'herbal', omzet, 0)) as b1_herbal,
                                sum(if(bulan = 2 and `status` = 'all', omzet, 0)) as b2,
                                sum(if(bulan = 2 and `status` = 'candy', omzet, 0)) as b2_candy,
                                sum(if(bulan = 2 and `status` = 'herbal', omzet, 0)) as b2_herbal,
                                sum(if(bulan = 3 and `status` = 'all', omzet, 0)) as b3,
                                sum(if(bulan = 3 and `status` = 'candy', omzet, 0)) as b3_candy,
                                sum(if(bulan = 3 and `status` = 'herbal', omzet, 0)) as b3_herbal,
                                sum(if(bulan = 4 and `status` = 'all', omzet, 0)) as b4,
                                sum(if(bulan = 4 and `status` = 'candy', omzet, 0)) as b4_candy,
                                sum(if(bulan = 4 and `status` = 'herbal', omzet, 0)) as b4_herbal,
                                sum(if(bulan = 5 and `status` = 'all', omzet, 0)) as b5,
                                sum(if(bulan = 5 and `status` = 'candy', omzet, 0)) as b5_candy,
                                sum(if(bulan = 5 and `status` = 'herbal', omzet, 0)) as b5_herbal,
                                sum(if(bulan = 6 and `status` = 'all', omzet, 0)) as b6,
                                sum(if(bulan = 6 and `status` = 'candy', omzet, 0)) as b6_candy,
                                sum(if(bulan = 6 and `status` = 'herbal', omzet, 0)) as b6_herbal,
                                sum(if(bulan = 7 and `status` = 'all', omzet, 0)) as b7,
                                sum(if(bulan = 7 and `status` = 'candy', omzet, 0)) as b7_candy,
                                sum(if(bulan = 7 and `status` = 'herbal', omzet, 0)) as b7_herbal,
                                sum(if(bulan = 8 and `status` = 'all', omzet, 0)) as b8,
                                sum(if(bulan = 8 and `status` = 'candy', omzet, 0)) as b8_candy,
                                sum(if(bulan = 8 and `status` = 'herbal', omzet, 0)) as b8_herbal,
                                sum(if(bulan = 9 and `status` = 'all', omzet, 0)) as b9,
                                sum(if(bulan = 9 and `status` = 'candy', omzet, 0)) as b9_candy,
                                sum(if(bulan = 9 and `status` = 'herbal', omzet, 0)) as b9_herbal,
                                sum(if(bulan = 10 and `status` = 'all', omzet, 0)) as b10,
                                sum(if(bulan = 10 and `status` = 'candy', omzet, 0)) as b10_candy,
                                sum(if(bulan = 10 and `status` = 'herbal', omzet, 0)) as b10_herbal,
                                sum(if(bulan = 11 and `status` = 'all', omzet, 0)) as b11,
                                sum(if(bulan = 11 and `status` = 'candy', omzet, 0)) as b11_candy,
                                sum(if(bulan = 11 and `status` = 'herbal', omzet, 0)) as b11_herbal,
                                sum(if(bulan = 12 and `status` = 'all', omzet, 0)) as b12,
                                sum(if(bulan = 12 and `status` = 'candy', omzet, 0)) as b12_candy,
                                sum(if(bulan = 12 and `status` = 'herbal', omzet, 0)) as b12_herbal,".$id."
                        from
                        (
                            select  nama_comp cabang, a.KODE_BSP, b.kodeprod,
                                    month(tgldokjdi) bulan,
                                    sum(jumlah)omzet, 'all' as 'status' 
                            from    bsp.bspsales".$year."  a LEFT JOIN
                                    (
                                        select  * 
                                        from     bsp.mapprod
                                        GROUP BY kode_bsp
                                    )b on a.KODE_BSP = b.kode_bsp
                                       ".$wheresupp."
                            group by kode_comp,month(tgldokjdi)

                            union all

                            select  nama_comp cabang, a.KODE_BSP, b.kodeprod,
                                    month(tgldokjdi) bulan,
                                    sum(jumlah)omzet, 'candy' as 'status' 
                            from    bsp.bspsales".$year."  a LEFT JOIN
                                    (
                                        select  * 
                                        from     bsp.mapprod
                                        GROUP BY kode_bsp
                                    )b on a.KODE_BSP = b.kode_bsp
                                       ".$wheresupp." and 
                                       kodeprod not in (SELECT kodeprod from mpm.tabprod where supp ='001' and permen <> 1)  
                            group by kode_comp,month(tgldokjdi)

                            union all

                            select  nama_comp cabang, a.KODE_BSP, b.kodeprod,
                                    month(tgldokjdi) bulan,
                                    sum(jumlah)omzet, 'herbal' as 'status' 
                            from    bsp.bspsales".$year."  a LEFT JOIN
                                    (
                                        select  * 
                                        from     bsp.mapprod
                                        GROUP BY kode_bsp
                                    )b on a.KODE_BSP = b.kode_bsp
                                       ".$wheresupp." and 
                                       kodeprod not in (SELECT kodeprod from mpm.tabprod where supp ='001' and permen = 1)  
                            group by kode_comp,month(tgldokjdi)


                        )a group by cabang
                    )a
                )a group by cabang
            )a group by cabang                 
            ";
            /*
            echo "<pre>";
            print_r($query);
            //echo $sql;
            echo "</pre>";
            */
            $sql = $this->db->query($query);

            if ($sql == '1') {
                
                $query ="
                    insert into bsp.omzet_new_deltomed
                    select  'z_Grand Total',b1,b2,b3,b4,b5,b6,b7,b8,b9,b10,b11,b12,
                            b1_candy,b2_candy,b3_candy,b4_candy,b5_candy,b6_candy,b7_candy,b8_candy,b9_candy,b10_candy,
                            b11_candy,b12_candy,
                            b1_herbal,b2_herbal,b3_herbal,b4_herbal,b5_herbal,b6_herbal,b7_herbal,b8_herbal,b9_herbal,
                            b10_herbal,b11_herbal,b12_herbal,total,total_candy,total_herbal,
                            sum(total/12) as rata,sum(total_candy/12) as rata_candy,sum(total_herbal/12) as rata_herbal,
                            ".$id.",".$year.",".$key_jam."
                    from
                    (


                        select  sum(b1) as b1,sum(b2) as b2,sum(b3) as b3,sum(b4) as b4,sum(b5) as b5,
                                sum(b6) as b6, sum(b7) as b7,sum(b8) as b8,sum(b9) as b9,sum(b10) as b10,
                                sum(b11) as b11,sum(b12) as b12, 
                                sum(b1_candy) as b1_candy,sum(b2_candy) as b2_candy,sum(b3_candy) as b3_candy,
                                sum(b4_candy) as b4_candy,sum(b5_candy) as b5_candy,sum(b6_candy) as b6_candy, 
                                sum(b7_candy) as b7_candy,sum(b8_candy) as b8_candy,sum(b9_candy) as b9_candy,
                                sum(b10_candy) as b10_candy,sum(b11_candy) as b11_candy,sum(b12_candy) as b12_candy,
                                sum(b1_herbal) as b1_herbal,sum(b2_herbal) as b2_herbal,sum(b3_herbal) as b3_herbal,
                                sum(b4_herbal) as b4_herbal,sum(b5_herbal) as b5_herbal,sum(b6_herbal) as b6_herbal,
                                sum(b7_herbal) as b7_herbal,sum(b8_herbal) as b8_herbal,sum(b9_herbal) as b9_herbal,
                                sum(b10_herbal) as b10_herbal,sum(b11_herbal) as b11_herbal,sum(b12_herbal) as b12_herbal,
                                sum(total) as total,sum(total_candy) as total_candy,sum(total_herbal) as total_herbal
                        from bsp.omzet_new_deltomed
                        where `key` = '$key_jam' 
                    )a
                ";
                /*
                echo "<pre>";
                print_r($query);
                echo "</pre>";
                */
                $sql = $this->db->query($query);

                if ($sql == '1'){

                }else{
                    echo "grand total error";
                }

            } else {
                
                echo "ada error. Silahkan ulangi atau hubungi IT";
            }
            

        /* END PROSES INSERT KE TABEL BSP.OMZET_NEW */

        /* PROSES TAMPIL KE WEBSITE */
            //$this->db->join('tbl_tabcomp', 'tbl_tabcomp.naper = omzet_new.naper','inner');
            //$this->db->where("status = 1");
            //$this->db->order_by('tbl_tabcomp.urutan','asc');
            //$this->db->order_by('namacomp','asc');
            //$this->db->where("id = ".'"'.$id.'"');
            
            $query="
                select * from bsp.omzet_new_deltomed
                where tahun = '$year' and `key` = (
                    select max(`key`)
                    from bsp.omzet_new_deltomed
                )
            ";
            $hasil = $this->db->query($query);
            //$hasil = $this->db->get('bsp.omzet_new_deltomed'); 

            if ($hasil->num_rows() > 0) 
            {
                return $hasil->result();
            } else {
                return array();
            }
        /* END PROSES TAMPIL KE WEBSITE */
    }

    public function get_group($key='')
    {
        //$year=year(now());
        //$year=year(now());
        //$year = '2017';
        $userid=$this->session->userdata('id');
        $nocab=$this->session->userdata('nocab');
        $kode_supp = $key['kode_supp'];


        $sql="
        select id_group, nama_group as nama_group
        FROM mpm.tbl_group
        where kode_supp ='$kode_supp'
        ";

        $query=$this->db->query($sql);
        return $query;

    }

    public function list_product()
    {
        $supp=$this->session->userdata('supp');
        
        if($supp=='000')
        {
            return $this->db->query('
                select  b.supp, namasupp, kodeprod, DESKRIPSI as namaprod, a.kode_bsp as kode_bsp,concat(b.supp,grup) suppx
                from    bsp.bspsales2019 a LEFT JOIN bsp.mapprod b
                            on a.KODE_BSP = b.kode_bsp
                        LEFT JOIN mpm.tabsupp c
                            on b.supp = c.supp
                GROUP BY    a.kode_bsp
                ORDER BY  b.supp, kode_bsp
            ');

        }
        else
        {
            /*
            return $this->db->query('select supp,namasupp,kodeprod, namaprod from mpm.tabprod a left join mpm.tabsupp b using(supp) where supp='.$supp.' and left(kodeprod,3)<>"BSP" and a.report=1 order by namaprod');
            */
            return $this->db->query('
                select  b.supp, namasupp, kodeprod, DESKRIPSI as namaprod, a.kode_bsp as kode_bsp,concat(b.supp,grup) suppx
                from    bsp.bspsales2019 a LEFT JOIN bsp.mapprod b
                            on a.KODE_BSP = b.kode_bsp
                        LEFT JOIN mpm.tabsupp c
                            on b.supp = c.supp
                where c.supp = '.$supp.'
                GROUP BY    a.kode_bsp
                ORDER BY  b.supp, kode_bsp
            ');
        }
    }

    public function bsp_per_product($dataSegment){
        $user=$this->session->userdata('username');
        if ( function_exists( 'date_default_timezone_set' ) )
        date_default_timezone_set('Asia/Jakarta');        

        /* ---------DEFINISI VARIABEL----------------- */
        
            $tgl_created='"'.date('Y-m-d H:i:s').'"';        
            $id=$this->session->userdata('id');
            $uv = $dataSegment['uv'];

            switch($uv)
            {
                case 0:$unit='sum(banyak)/isisatuan';break;
                case 1:$unit='sum(jumlah)';break;
            }

            $year = $dataSegment['tahun'];
            $code = $dataSegment['code'];
            //echo "year : ".$year;
            //echo "<br />uv : ".$uv."<br>";
            //echo "<br />code : ".$code."<br>";
           
        /* ---------END DEFINISI VARIABEL---------------- */

        /* PROSES DELETE MPM.soprod_new */
            $query = "delete from bsp.soprod where id = ".$id."";
            $sql = $this->db->query($query);
            
            //echo "<pre>";
            //print_r($query);
            //echo "</pre>";            
        
        /* END PROSES DELETE MPM.OUTLET_NEW */

        /* PROSES INSERT KE MPM.soprod_new */

            $sql = "
                insert into bsp.soprod
                select  nama_comp, 
                        sum(if(bulan=1,unit,0)) b1, 
                        sum(if(bulan=2,unit,0)) b2, 
                        sum(if(bulan=3,unit,0)) b3,
                        sum(if(bulan=4,unit,0)) b4,
                        sum(if(bulan=5,unit,0)) b5,
                        sum(if(bulan=6,unit,0)) b6,
                        sum(if(bulan=7,unit,0)) b7,
                        sum(if(bulan=8,unit,0)) b8,
                        sum(if(bulan=9,unit,0)) b9,
                        sum(if(bulan=10,unit,0)) b10,
                        sum(if(bulan=11,unit,0)) b11,
                        sum(if(bulan=12,unit,0)) b12,".$id."
                from
                (
                    select  nama_comp,  
                            month(tgldokjdi) as bulan,
                            ".$unit." as unit 
                    from    bsp.bspsales".$year." a LEFT JOIN
                    (
                        SELECT KODE_BSP, namaprod, isisatuan 
                        from bsp.tabprod
                        GROUP BY KODE_BSP
                    )b on a.KODE_BSP = b.KODE_BSP
                    where a.KODE_BSP in (".$code.") 
                    group by nama_comp,bulan 
                ) a group by nama_comp
                union all
                select  'z_TOTAL', 
                        sum(if(bulan=1,unit,0)) b1, 
                        sum(if(bulan=2,unit,0)) b2, 
                        sum(if(bulan=3,unit,0)) b3,
                        sum(if(bulan=4,unit,0)) b4,
                        sum(if(bulan=5,unit,0)) b5,
                        sum(if(bulan=6,unit,0)) b6,
                        sum(if(bulan=7,unit,0)) b7,
                        sum(if(bulan=8,unit,0)) b8,
                        sum(if(bulan=9,unit,0)) b9,
                        sum(if(bulan=10,unit,0)) b10,
                        sum(if(bulan=11,unit,0)) b11,
                        sum(if(bulan=12,unit,0)) b12,".$id."
                from
                (
                    select  nama_comp,  
                            month(tgldokjdi) as bulan,
                            ".$unit." as unit 
                    from    bsp.bspsales".$year." a LEFT JOIN
                    (
                        SELECT KODE_BSP, namaprod, isisatuan 
                        from bsp.tabprod
                        GROUP BY KODE_BSP
                    )b on a.KODE_BSP = b.KODE_BSP
                    where a.KODE_BSP in (".$code.") 
                    group by nama_comp,bulan 
                ) a 

                ";


        /* END PROSES DELETE MPM.soprod_NEW */
            //echo "<pre>";
            //print_r($sql);
            //echo "</pre>";
        
            $sql_insert = $this->db->query($sql);

        /* PROSES TAMPIL KE WEBSITE */
            //$this->db->join('tbl_tabcomp', 'tbl_tabcomp.naper = soprod_new.naper','inner');
            //$this->db->where("status = 1");
            $this->db->order_by('nama_comp','asc');           
            $this->db->where("bsp.soprod.id = ".'"'.$id.'"');
            $hasil = $this->db->get('bsp.soprod');       
            if ($hasil->num_rows() > 0) 
            {
                return $hasil->result();
            } else {
                return array();
            }
        /* END PROSES TAMPIL KE WEBSITE */
    }

    public function list_bsp_outlet()
    {
        $sql='select kode_comp from bsp.bspsales'.date('Y').' limit 1';
        $query = $this->db->query($sql);
        if($query->num_rows() > 0)
        {
                $sql='select distinct kode_comp,nama_comp from bsp.bspsales'.date('Y').' order by nama_comp';
        }
        else
        {
                $sql='select distinct kode_comp,nama_comp from bsp.bspsales'.(date('Y')-1).' order by nama_comp';
        }
        return $query = $this->db->query($sql);
    }

    public function outlet_bsp($dataSegment)
    {
        //$naper=$this->getNocab($dp);
        $id=$this->session->userdata('id');
        $kode_sales = $dataSegment['sm'];
        $year = $dataSegment['tahun'];
        $dp = $dataSegment['dp'];
        $kodeprod = $dataSegment['code'];

        //echo "id : ".$id."<br>";
        //echo "dp : ".$dp."<br>";
        //echo "kodeprod : ".$kodeprod."<br>";
        $code=preg_replace('/,/', '","', $kodeprod,1);
        //echo "kode : ".$code."<br>";
      
        $uv=$this->input->post('uv');
        //echo "uv : ".$uv."<br>";

        switch($uv)
        {
            case 0:
              $unit='banyak';
              break;
            case 1:
              $unit='jumlah';
              break;
        }

        

        /* PROSES DELETE bsp.OUTLET_NEW */
            $query = "delete from bsp.outlet_new where id = ".$id."";
            $sql = $this->db->query($query);
            
            //echo "<pre>";
            //print_r($query);
            //echo "<br><br>";
            //print_r($sql);
            //echo "</pre>";            
            
        /* END PROSES DELETE MPM.OUTLET_NEW */


        $sql=" 
                insert into bsp.outlet_new
                select  kode,tipe,outlet,address,
                        b1,b2,b3,b4,b5,b6,b7,b8,b9,b10,b11,b12,
                        (b1+b2+b3+b4+b5+b6+b7+b8+b9+b10+b11+b12)/12 as rata,
                        id 
                from
                (
                    select  kode, outlet,address,tipe,
                            sum(if(blndok=1,unit/isisatuan,0)) as b1,
                            sum(if(blndok=2,unit/isisatuan,0)) as b2, 
                            sum(if(blndok=3,unit/isisatuan,0)) as b3,
                            sum(if(blndok=4,unit/isisatuan,0)) as b4, 
                            sum(if(blndok=5,unit/isisatuan,0)) as b5,
                            sum(if(blndok=6,unit/isisatuan,0)) as b6,
                            sum(if(blndok=7,unit/isisatuan,0)) as b7,
                            sum(if(blndok=8,unit/isisatuan,0)) as b8, 
                            sum(if(blndok=9,unit/isisatuan,0)) as b9,
                            sum(if(blndok=10,unit/isisatuan,0)) as b10, 
                            sum(if(blndok=11,unit/isisatuan,0)) as b11,
                            sum(if(blndok=12,unit/isisatuan,0)) as b12,
                            ".$id." as id
                    from 
                    (
                        select  kode_lang as kode,
                                isisatuan, 
                                nama_type as tipe,
                                nama_lang as outlet,
                                alamat as address,
                                sum(".$unit.")as unit,
                                month(tgldokjdi) as blndok  
                        from    bsp.bspsales".$year." a inner join bsp.tabprod b 
                                    on a.deskripsi=b.namaprod
                        where   kode_comp = ".'"'.$dp.'"'." and 
                                deskripsi in (".'"'.$code.'"'.") 
                        group by kode_lang,blndok
                    )a group by kode
                )a                                 
                ";       


        /* PROSES INSERT KE TABEL BSP.OUTLET_NEW */
            
            //echo "<pre>";
            //print_r($sql);
            //echo "</pre>";
            
           $sql_insert = $this->db->query($sql);

        /* PROSES TAMPIL KE WEBSITE */
            //$this->db->join('tbl_tabcomp', 'tbl_tabcomp.naper = omzet_new.naper','inner');
            //$this->db->where("status = 1");
            //$this->db->order_by('tbl_tabcomp.urutan','asc');
            
            /*
            $this->db->order_by('kode','asc');
            $this->db->where("id = ".'"'.$id.'"');
            $hasil = $this->db->get('bsp.outlet_new');       
            if ($hasil->num_rows() > 0) 
            {
                return $hasil->result();
            } else {
                return array();
            }*/
            
        /* END PROSES TAMPIL KE WEBSITE */

        

    }

    public function sell_out($dataSegment){
        $user=$this->session->userdata('username');
        if ( function_exists( 'date_default_timezone_set' ) )
        date_default_timezone_set('Asia/Jakarta');        

        /* ---------DEFINISI VARIABEL----------------- */
        
            $tgl_created='"'.date('Y-m-d H:i:s').'"';        
            $id=$this->session->userdata('id');
            $uv = $dataSegment['uv'];

            switch($uv)
            {
                //case 0:$unit='sum(banyak)/isisatuan';break;
                //case 1:$unit='sum(jumlah)';break;
                case 0:
                      $unit='banyak/isisatuan';
                      break;
                case 1:
                      $unit='jumlah';
                      break;
            }

            $year = $dataSegment['tahun'];
            //$code = $dataSegment['code'];
            echo "year : ".$year;
            echo "<br />uv : ".$uv."<br>";
            
        /* ---------END DEFINISI VARIABEL---------------- */

        /* PROSES DELETE MPM.soprod_new */
            $query = "delete from bsp.sobsp where id = ".$id."";
            $sql = $this->db->query($query);
            
            //echo "<pre>";
            //print_r($query);
            //echo "</pre>";            
        
        /* END PROSES DELETE MPM.OUTLET_NEW */

        /* PROSES INSERT KE MPM.soprod_new */

            $sql = "
insert into bsp.sobsp
select  a.kode_bsp, deskripsi, (b1+b2+b3+b4+b5+b6+b7+b8+b9+b10+b11+b12) as rata,
        b1,b2,b3,b4,b5,b6,b7,b8,b9,b10,b11,b12, ".$id."
from
(
    select  deskripsi, a.kode_bsp,
            sum(if(bulan=1,unit,0)) as b1,sum(if(bulan=2,unit,0))as b2,
            sum(if(bulan=3,unit,0))  as b3,sum(if(bulan=4,unit,0)) as b4,
            sum(if(bulan=5,unit,0)) as b5,sum(if(bulan=6,unit,0)) as b6,
            sum(if(bulan=7,unit,0)) as b7,sum(if(bulan=8,unit,0)) as b8,
            sum(if(bulan=9,unit,0)) as b9,sum(if(bulan=10,unit,0)) as b10,
            sum(if(bulan=11,unit,0)) as b11,sum(if(bulan=12,unit,0)) as b12
    from 
    (
        select  deskripsi,a.kode_bsp,
                month(tgldokjdi) as bulan, 
                sum(".$unit.") as unit
        from    bsp.bspsales".$year." a LEFT JOIN
        (
            SELECT  KODE_BSP, namaprod, isisatuan 
            from    bsp.tabprod
            GROUP BY KODE_BSP
        )b on a.KODE_BSP = b.KODE_BSP
                group by a.KODE_BSP, bulan
    )a group by a.kode_bsp
)a 
union all
select  '', 'Z_TOTAL', sum((b1+b2+b3+b4+b5+b6+b7+b8+b9+b10+b11+b12)) as rata,
        sum(b1),sum(b2),sum(b3),sum(b4),sum(b5),sum(b6),sum(b7),sum(b8),sum(b9),sum(b10),sum(b11),sum(b12), ".$id."
from
(
    select  deskripsi, a.kode_bsp,
            sum(if(bulan=1,unit,0)) as b1,sum(if(bulan=2,unit,0))as b2,
            sum(if(bulan=3,unit,0))  as b3,sum(if(bulan=4,unit,0)) as b4,
            sum(if(bulan=5,unit,0)) as b5,sum(if(bulan=6,unit,0)) as b6,
            sum(if(bulan=7,unit,0)) as b7,sum(if(bulan=8,unit,0)) as b8,
            sum(if(bulan=9,unit,0)) as b9,sum(if(bulan=10,unit,0)) as b10,
            sum(if(bulan=11,unit,0)) as b11,sum(if(bulan=12,unit,0)) as b12
    from 
    (
        select  deskripsi,a.kode_bsp,
                month(tgldokjdi) as bulan, 
                sum(".$unit.") as unit
        from    bsp.bspsales".$year." a LEFT JOIN
        (
            SELECT  KODE_BSP, namaprod, isisatuan 
            from    bsp.tabprod
            GROUP BY KODE_BSP
        )b on a.KODE_BSP = b.KODE_BSP
                group by a.KODE_BSP, bulan
    )a group by a.kode_bsp
)a 
                ";


        /* END PROSES DELETE MPM.soprod_NEW */
            //echo "<pre>";
            //print_r($sql);
            //echo "</pre>";
        
            $sql_insert = $this->db->query($sql);

        /* PROSES TAMPIL KE WEBSITE */
            //$this->db->join('tbl_tabcomp', 'tbl_tabcomp.naper = soprod_new.naper','inner');
            //$this->db->where("status = 1");
            $this->db->order_by('deskripsi','asc');           
            $this->db->where("bsp.sobsp.id = ".'"'.$id.'"');
            $hasil = $this->db->get('bsp.sobsp');       
            if ($hasil->num_rows() > 0) 
            {
                return $hasil->result();
            } else {
                return array();
            }
        /* END PROSES TAMPIL KE WEBSITE */
    }

    public function stock_bsp_nasional($dataSegment)
    {
    /* ---------DEFINISI VARIABEL----------------- */
        $id=$this->session->userdata('id');
        if ( function_exists( 'date_default_timezone_set' ) )
        date_default_timezone_set('Asia/Jakarta');        
        $tgl_created='"'.date('Y-m-d H:i:s').'"';

        $year = $dataSegment['tahun'];
        $supp = $dataSegment['supp'];
        $group = $dataSegment['note_x'];
        /*
        echo "<br>id di model : ".$id;
        echo "<br>tahun di model : ".$year;
        echo "<br>supplier di model : ".$supp;
        echo "<br>group di model : ".$group;
        */
        if ($group == '4') {
            $var_group = "where grup = 'G0101'";
            $suppx = "and supp = '001'";
        }elseif ($group == '5') {
            $var_group = "where grup = 'G0102'";
            $suppx = "and supp = '001'";
        }elseif ($group == '12') {
            $var_group = "where grup = 'G0103'";
            $suppx = "and supp = '001'";
        }elseif ($group == '13') {
            $var_group = "where grup = 'G0201'";
            $suppx = "and supp = '002'";
        }elseif ($group == '14') {
            $var_group = "where grup = 'G0202'";
            $suppx = "and supp = '002'";
        }else{

            $var_group = '';

            if($supp == '001') {
                $suppx = "where supp = '001'";
            }elseif($supp == '002') {
                $suppx = "where supp = '002'";
            }elseif($supp == '004') {
                $suppx = "where supp = '004'";
            }elseif($supp == 'XXX'){
                $suppx = "";
            }

        }
            
        /* cari bulan saat ini */
            $bulan = date("m");
            $tahunsekarang = date("Y");
            /*
            echo "bulan saat ini : ".$bulan."<br>";
            echo "tahun saat ini: ".$tahunsekarang."<br>";
            echo "tahun yang dipilih user : ".$year;
            */
        /* end cari bulan saat ini */        
        
        /* ---------END DEFINISI VARIABEL---------------- */

        /* PROSES DELETE MPM.OMZET_NEW */
            $query = "delete from bsp.stokbsp where id = ".$id."";
            $sql = $this->db->query($query);
            /*
            echo "<pre>";
            print_r($query);
            echo "</pre>";
            */
        /* END PROSES DELETE BSP.OMZET_NEW */

        /* PROSES INSERT KE TABEL BSP.OMZET_NEW */
            
            $query = "
                insert into bsp.stokbsp
                select  kodeprod, namaprod,
                        sum(if(bulan=1,stok,0)) as b1,sum(if(bulan=2,stok,0))as b2,
                        sum(if(bulan=3,stok,0)) as b3,sum(if(bulan=4,stok,0)) as b4,
                        sum(if(bulan=5,stok,0)) as b5,sum(if(bulan=6,stok,0)) as b6,
                        sum(if(bulan=7,stok,0)) as b7,sum(if(bulan=8,stok,0)) as b8,
                        sum(if(bulan=9,stok,0)) as b9,sum(if(bulan=10,stok,0)) as b10,
                        sum(if(bulan=11,stok,0)) as b11,sum(if(bulan=12,stok,0)) as b12,
                        $id
                from 
                (
                        select  namaprod,sum(stok)/isisatuan as stok,bulan, kodeprod, grup
                        from    bsp.stok$year a inner join bsp.tabprod b using(namaprod) 
                                LEFT JOIN 
                                (
                                    select kode_bsp,grup,supp
                                    from bsp.mapprod
                                )c on a.kodeprod = c.kode_bsp
                        $var_group $suppx
                        group by namaprod,bulan
                        
                )a group by namaprod                 
            ";
            /*
            echo "<pre>";
            print_r($query);
            echo "</pre>";
            */
            $sql = $this->db->query($query);

        /* END PROSES INSERT KE TABEL BSP.OMZET_NEW */

        /* PROSES TAMPIL KE WEBSITE */
            $this->db->order_by('deskripsi','asc');
            $this->db->where("id = ".'"'.$id.'"');
            $hasil = $this->db->get('bsp.stokbsp');       
            if ($hasil->num_rows() > 0) 
            {
                return $hasil->result();
            } else {
                return array();
            }
        /* END PROSES TAMPIL KE WEBSITE */


    }

}
