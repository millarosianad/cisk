<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require 'vendor/autoload.php';
use GuzzleHttp\Client;
use GuzzleHttp\Psr7;
use GuzzleHttp\Exception\RequestException;

class Model_stock extends CI_Model 
{
    public function getSuppbyid()
    {
        $supp=$this->session->userdata('supp');
        if($supp=='000')
        {
            return $this->db->query('select supp,namasupp from mpm.tabsupp');
        }
        else
        {
            return $this->db->query('select supp,namasupp from mpm.tabsupp where supp='.$supp);
        }
    }

    public function getSuppbyid_stok()
    {
        $supp=$this->session->userdata('supp');
        if($supp=='000')
        {
            return $this->db->query("select supp,namasupp from mpm.tabsupp where supp not in ('000','bsp','xxx')");
        }
        else
        {
            return $this->db->query('select supp,namasupp from mpm.tabsupp where supp='.$supp);
        }
    }

    public function list_dp()
    {
        $userid=$this->session->userdata('id');
        $nocab=$this->session->userdata('nocab');

        //echo "userid : ".$userid."<br>"; 
        //echo "nocab : ".$nocab."<br>"; 
        echo "nocab : ".$nocab."<br>"; 

        if ($nocab!='')
        {
            if($userid =='191')//pak jan
            {                
                return $this->db->query('select distinct naper, nama_comp from mpm.tbl_tabcomp where  naper!=99 and wilayah=2 and naper like "%'.$nocab.'%" order by nama_comp');
               
            }
            else if($userid == '232')//pak kartono
            {
                return $this->db->query('select distinct naper, nama_comp from mpm.tbl_tabcomp where  naper!=99 and wilayah=1 and naper like "%'.$nocab.'%" order by nama_comp');
            }
            else if($userid == '327')//JTS
            {
                return $this->db->query('select distinct naper, nama_comp from mpm.tbl_tabcomp where naper like "%'.$nocab.'%" order by nama_comp');
            }
             else{
                //return $this->db->query("select distinct naper, nama_comp from mpm.tbl_tabcomp where  naper!=99 and naper like "%$nocab%" order by kode_comp");
            }       
            
        }
        else

        {
            if($userid == '318'){ //user dimas, all jkt & karawang
                return $this->db->query('select distinct naper, nama_comp, nocab from mpm.tbl_tabcomp 
                                        where  naper!=99 and naper in(88, 53, 01, 41 ,42, 75, 39, 58, 40, 04)
                                        order by nama_comp');
            }
            elseif($userid == '319'){ //user sutrisno, all jbr
                return $this->db->query('select distinct naper, nama_comp, nocab from mpm.tbl_tabcomp 
                                        where  naper!=99 and naper in(95, 02, 52, 47 ,60, "g2", 09, 79, 08, "k1")
                                        order by nama_comp');
            }

            elseif($userid == '320'){ //user kristiyanto, all jtg
                return $this->db->query('select distinct naper, nama_comp, nocab from mpm.tbl_tabcomp 
                                        where  naper!=99 and naper in(89, 14, 57, 64 , 78, 76, 06, 63, 73, "k1", 19, 32, "p3", "j1")
                                        order by nama_comp');
            }

            elseif($userid == '321'){ //user hengki, kudus, blora, pati, jepara
                return $this->db->query('select distinct naper, nama_comp, nocab from mpm.tbl_tabcomp 
                                        where  naper!=99 and naper in(19, 32, "p3", "j1")
                                        order by nama_comp');
            }

            elseif($userid == '322'){ //user dawam, diy, solo
                return $this->db->query('select distinct naper, nama_comp, nocab from mpm.tbl_tabcomp 
                                        where  naper!=99 and naper in(98, 96, 18)
                                        order by nama_comp');
            }

            elseif($userid == '323'){ //user miko, all jtm dan seluruh dp jawa timur (mlg, sdo, kdr, tga, mdu, blt)
                return $this->db->query('select distinct naper, nama_comp, nocab from mpm.tbl_tabcomp 
                                        where  naper!=99 and naper in(91, 28, 50, 83, 25, "g1", "b1", "p1", "p2", "t1", 27, 46, 24, 26, 23, 69, "s1", "l1", "c1", "p5", "p4") or (sub = 91)
                                        order by sub, nama_comp');
            }

            elseif($userid == '336'){ //user ghazali, all pulau jawa)
                return $this->db->query('select distinct naper, nama_comp, nocab from mpm.tbl_tabcomp 
                                        where  naper!=99 and naper in(01,02,03,04,06,07,08,09,10,11,12,13,14,15,16,17,18,19,23,24,25,26,27,28,29,39,40,41,42,46,47,48,50,52,53,54,56,57,58,60,61,62,63,64,66,67,68,69,74,91,73,79,75,76,81,78,83,84,88,89,95,96,"G1","P2","P1","T1","G2","P3","J1","S1","L1","K1","B2","B3",99,"C1","P4","P5",98)
                                        order by nama_comp');
            }

            else{
                return $this->db->query('select distinct naper, nama_comp from mpm.tbl_tabcomp where  naper!=99 and kode_comp != "XXX" order by nama_comp  ');
            }
        }
    }

    public function list_dp_stock(){
        $userid=$this->session->userdata('id');
        //echo "id : ".$userid;

        /*cek hak DP apa saja yang dapat dilihat*/
            $this->db->where('id = '.'"'.$userid.'"');
            $query = $this->db->get('mpm.user');
            foreach ($query->result() as $row) {
                $wilayah_nocab = $row->wilayah_nocab;
                //echo "nocab : ".$wilayah_nocab."<br>";
                return $wilayah_nocab;
            }
        /*end cek hak DP apa saja yang dapat dilihat*/
        
    }

    public function list_product()
    {
        $supp=$this->session->userdata('supp');
        $id=$this->session->userdata('id');
        //echo "<pre>";
        //print_r($supp);
        //print_r($sql);
        //echo "</pre>";

        if($supp=='000')
        {
            return $this->db->query("
                select supp, namasupp,kodeprod, namaprod, concat(supp,grup) as suppx
                FROM
                (
                        select  supp,namasupp,kodeprod, namaprod,grup
                        FROM
                        (
                                select  supp,namasupp,kodeprod, namaprod,grup
                                from    mpm.tabprod a left join mpm.tabsupp b using(supp) 
                                where   left(kodeprod,3)<>'BSP'  and a.report=1 
                                order   by supp,namaprod
                        )a
                )a ORDER BY supp, namaprod
            ");

        }elseif ($supp=='001' and $id =='374') { //khusus pak mardohar digabung antara candy dan natura prima
             $sql = "
                select supp, namasupp,kodeprod, namaprod, concat(supp,grup) as suppx
                FROM
                (
                    select  supp,namasupp,kodeprod, namaprod,grup
                    FROM
                    (
                                    select  supp,namasupp,kodeprod, namaprod,grup
                                    from    mpm.tabprod a left join mpm.tabsupp b using(supp) 
                                    where   left(kodeprod,3)<>'BSP'  and a.report=1 and (grup='G0102' or supp ='010')
                                    order   by supp,namaprod
                    )a
                )a 

                ORDER BY supp, namaprod
             ";

             $sql = "
                select supp, namasupp,kodeprod, namaprod, concat(supp,grup) as suppx
                FROM
                (
                        select  supp,namasupp,kodeprod, namaprod,grup
                        FROM
                        (
                                select  supp,namasupp,kodeprod, namaprod,grup
                                from    mpm.tabprod a left join mpm.tabsupp b using(supp) 
                                where   left(kodeprod,3)<>'BSP'  and a.report=1 
                                order   by supp,namaprod
                        )a
                )a ORDER BY supp, namaprod
             ";

             return $this->db->query($sql);
        }
        else
        {
            
            $sql = "
                select supp, namasupp,kodeprod, namaprod, concat(supp,grup) as suppx
                FROM
                (
                        select  supp,namasupp,kodeprod, namaprod,grup
                        FROM
                        (
                                select  supp,namasupp,kodeprod, namaprod,grup
                                from    mpm.tabprod a left join mpm.tabsupp b using(supp) 
                                where   supp='$supp' and left(kodeprod,3)<>'BSP'  and a.report=1 
                                order   by supp,namaprod
                        )a
                )a ORDER BY supp, namaprod

            ";

            return $this->db->query($sql);
        }
    }
    
    public function stock_by_product($dataSegment,$kodeprod=null){

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
                    {
                         $harga=1;
                        
                    }break;
                    case 1:
                    {
                         $harga='h_dp';
                         
                     }break;
                }
            //$kode_sales = $dataSegment['sm'];
            $year = $dataSegment['tahun'];
            //$naper = $dataSegment['dp'];
            $code = $dataSegment['code'];

            //echo "year : ".$year;
            //echo "<br />uv : ".$uv."<br>";
        /* ---------END DEFINISI VARIABEL---------------- */

        /* PROSES DELETE MPM.stokprod_new */
            $query = "delete from mpm.stokprod_new where id = ".$id."";
            $sql = $this->db->query($query);
            
            //echo "<pre>";
            //print_r($query);
            //echo "</pre>";            
        
        /* END PROSES DELETE MPM.OUTLET_NEW */

        /* wilayah nocab */
        $dp = $dataSegment['query'];
            //echo "dp : ".$dp;
            if ($dp == NULL || $dp == '') {
                $daftar_dp = '';
            } else {
                $daftar_dp = 'where a.nocab in ('.$dp.')';
                //echo " <br>daftar_dp : ".$daftar_dp;
            }

        /* end wilayah nocab */


        /* PROSES INSERT KE MPM.stokprod_new */
            $sql="
                insert into mpm.stokprod_new
                SELECT a.naper, a.nama_comp, format(b1,0) b1,format(b2,0) b2,format(b3,0) b3,format(b4,0) b4,format(b5,0) b5,format(b6,0) b6,format(b7,0) b7,format(b8,0) b8,format(b9,0) b9,format(b10,0) b10,format(b11,0) b11,format(b12,0) b12,0,".$id.", urutan 
                FROM
                (    
                    select  a.naper, a.nama_comp, b1,b2,b3,b4,b5,b6,b7,b8,b9,b10,b11,b12,0,".$id.", naper2, `status`, urutan    
                    from
                    (
                        select  c.naper,c.nama_comp,b1,b2,b3,b4,b5,b6,b7,b8,b9,b10,b11,b12,0,".$id." 
                        from
                        (
                            select  nocab, 
                                    sum(if(bulan=1,stok*".$harga.",0)) b1,
                                    sum(if(bulan=2,stok*".$harga.",0)) b2,
                                    sum(if(bulan=3,stok*".$harga.",0)) b3,
                                    sum(if(bulan=4,stok*".$harga.",0)) b4,
                                    sum(if(bulan=5,stok*".$harga.",0)) b5,
                                    sum(if(bulan=6,stok*".$harga.",0)) b6,
                                    sum(if(bulan=7,stok*".$harga.",0)) b7,
                                    sum(if(bulan=8,stok*".$harga.",0)) b8,
                                    sum(if(bulan=9,stok*".$harga.",0)) b9,
                                    sum(if(bulan=10,stok*".$harga.",0)) b10,
                                    sum(if(bulan=11,stok*".$harga.",0)) b11,
                                    sum(if(bulan=12,stok*".$harga.",0)) b12
                            from
                            (
                                select  a.nocab,b.h_dp,
                                        substr(bulan, 3)AS bulan,a.kodeprod,
                                        sum(if(kode_gdg='PST',
                                        ((Saldoawal+masuk_pbk+masuk_supp+retur_sal+bPinjam+kvretur+kr_gudang+retur_depo)-
                                        (sales+pinjam+minta_depo+kvbeli+kr_min)-(rusak+sisih)),(Saldoawal+masuk_pbk+retur_sal+kvretur+bPinjam+tukar_msk+msk_gddepo) -
                                        (sales+Pinjam+kvbeli+retur_depo+tukar_klr+klr_gddepo))) as stok
                                FROM    data".$year.".st a inner join 
                                        (
                                            SELECT  kodeprod, h_dp as h_dp, jual as h_dpy
                                            FROM
                                            (
                                                        select  a.kodeprod, 
                                                                a.h_dp * (100-d_dp)/100 as h_dp 
                                                        from    mpm.prod_detail a 
                                                                inner join mpm.tabprod b using(kodeprod)
                                                        where   tgl=(
                                                                        select  max(tgl) 
                                                                        from    mpm.prod_detail 
                                                                        where   kodeprod=a.kodeprod
                                                                )ORDER BY KODEPROD
                                            )a left JOIN
                                            (
                                                select  productid, jual
                                                from        mpm.m_product
                                                ORDER BY    productid
                                            )b on a.kodeprod = b.productid
                                            ORDER BY productid asc
                                        ) b using(kodeprod)
                                where   a.kodeprod in (".$code.") AND kode_gdg != ''    
                                GROUP BY    nocab,bulan,kodeprod
                                ORDER BY    nocab
                            ) a group by nocab
                        )a inner join (
                            select nocab, naper, nama_comp
                            from mpm.tbl_tabcomp
                            where status = 1
                            group by nocab
                        )c on a.nocab = c.nocab 
                        $daftar_dp
                        group by naper
                        )a LEFT JOIN 
                            (
                                select  naper naper2,`status`, urutan
                                from        mpm.tbl_tabcomp
                                WHERE   `status` = '1' and status_cluster <> '1'
                                GROUP BY    naper2
                            )c on a.naper = c.naper2

                        
                    )a order by urutan asc
                    ";
        /* END PROSES DELETE MPM.OUTLET_NEW */
            
            echo "<pre>";
            print_r($sql);
            echo "</pre>";
            
            $sql_insert = $this->db->query($sql);

        /* PROSES TAMPIL KE WEBSITE */
            //$this->db->join('tbl_tabcomp', 'tbl_tabcomp.naper = stokprod_new.naper','inner');
            //$this->db->where("status_cluster <> '1'");
            //$this->db->where("status = 1");
            //$this->db->order_by('tbl_tabcomp.urutan','asc');           
            $this->db->order_by('stokprod_new.urutan','asc');
            $this->db->where("stokprod_new.id = ".'"'.$id.'"');
            $hasil = $this->db->get('stokprod_new');       
            if ($hasil->num_rows() > 0) 
            {
                return $hasil->result();
            } else {
                return array();
            }
        /* END PROSES TAMPIL KE WEBSITE */
    }

    public function stock_by_product_avg($dataSegment,$kodeprod=null){

        $user=$this->session->userdata('username');
        if ( function_exists( 'date_default_timezone_set' ) )
        date_default_timezone_set('Asia/Jakarta');   

        /* --------- DEFINISI VARIABEL---------------- */
        //echo "<pre>";
        $year = $dataSegment['tahun'];
        $code = $dataSegment['code'];
        $nocab = $dataSegment['dp'];
        $id=$this->session->userdata('id');

        /*
        echo "<pre>";
        echo "kode produk yang dipilih (model): ";
        print_r($code);
        echo "year : ".$year."<br>";
        echo "nocab : ".$nocab;
        */
        
        /* ---------END DEFINISI VARIABEL---------------- */

        /* PROSES DELETE MPM.tbl_avg_sales_stok */
            $query = "delete from mpm.tbl_avg_sales_stok where id = ".$id."";
            $sql = $this->db->query($query);
            /*
            echo "<pre>";
            print_r($query);
            echo "</pre>";            
            */
        /* END PROSES DELETE MPM.OUTLET_NEW */
        
        //echo "cek bulan terbesar dari database fi<br>";
        
        $sql = "
                select bulan
                from data$year.fi
                ORDER BY bulan desc
                limit 1
        ";

        $query = $this->db->query($sql);
        $row=$query->row();
        $bulan=$row->bulan;

        //echo "<br>bulan terbesar di data2017.fi :";
        //print_r($bulan);
        //echo "<br><br>";

        $tahun = date('Y');
        //echo "tahun sekarang : ".$tahun;

        $sql="select max(bulan) as bulan from data$year.st where nocab=".'"'.$nocab.'"';
        $query = $this->db->query($sql);        
        $row=$query->row();
        $bulan_max_st=$row->bulan;
        //echo "<pre>";
        //print_r($bulan_max_st);
        //echo "</pre>";

        //ini harus diubah jadi dinamis
        $tanggal = "$tahun-$bulan-01";
        $date[1]=date('Y-m-d',strtotime('-1 month',strtotime($tanggal)));
        $date[2]=date('Y-m-d',strtotime('-2 month',strtotime($tanggal)));
        $date[3]=date('Y-m-d',strtotime('-3 month',strtotime($tanggal)));
        $date[4]=date('Y-m-d',strtotime('-4 month',strtotime($tanggal)));
        $date[5]=date('Y-m-d',strtotime('-5 month',strtotime($tanggal)));
        $date[6]=date('Y-m-d',strtotime('-6 month',strtotime($tanggal)));

        //print_r($tanggal);
        //echo "<br>";
        //print_r($date);
        //echo "</pre>";


        $data_fi_ri = "
                select  nocab, kode_comp, blndok, banyak, tot1, kodeprod, namaprod
                from    data".date('Y',strtotime($date[1])).".fi
                where   kodeprod in (".$code.") and `nodokjdi` <> 'XXXXXX' and bulan = ".date('m',strtotime($date[1]))." and nocab = '$nocab'

                union all

                select  nocab, kode_comp, blndok, banyak, tot1, kodeprod, namaprod
                from    data".date('Y',strtotime($date[1])).".ri
                where   kodeprod in (".$code.") and `nodokjdi` <> 'XXXXXX' and bulan = ".date('m',strtotime($date[1]))." and nocab = '$nocab'
        ";

        for($i=2;$i<=6;$i++)
        {
             $data_fi_ri.=" 
                union all

                select  nocab, kode_comp, blndok, banyak, tot1, kodeprod, namaprod
                from    data".date('Y',strtotime($date[$i])).".fi
                where   kodeprod in (".$code.") and `nodokjdi` <> 'XXXXXX' and bulan = ".date('m',strtotime($date[$i]))." and nocab = '$nocab'

                union all

                select  nocab, kode_comp, blndok, banyak, tot1, kodeprod, namaprod
                from    data".date('Y',strtotime($date[$i])).".ri
                where   kodeprod in (".$code.") and `nodokjdi` <> 'XXXXXX' and bulan = ".date('m',strtotime($date[$i]))." and nocab = '$nocab'
            ";
        }

        //echo "<br>";
        //print_r($data_fi_ri);

        $data_sum = "
                sum(if(blndok=".date('m',strtotime($date[1])).", banyak, 0)) as 'unit".date('m',strtotime($date[1]))."',
        ";
        for($i=2;$i<=5;$i++)
        {
             $data_sum.=" 
                sum(if(blndok=".date('m',strtotime($date[$i])).", banyak, 0)) as 'unit".date('m',strtotime($date[$i]))."',
            ";
        }
        $data_sum_last = "
                sum(if(blndok=".date('m',strtotime($date[6])).", banyak, 0)) as 'unit".date('m',strtotime($date[6]))."'
        ";


        $data_untuk_pembagi = "
                sum(if(unit".date('m',strtotime($date[1]))." <> 0,1,0))+
        ";
        for($i=2;$i<=5;$i++)
        {
             $data_untuk_pembagi.=" 
                sum(if(unit".date('m',strtotime($date[$i]))." <> 0,1,0))+
            ";
        }
        $data_untuk_pembagi_last = "
                sum(if(unit".date('m',strtotime($date[6]))." <> 0,1,0)) as pembagi
        ";

        $sql = "
            insert into mpm.tbl_avg_sales_stok
            select  a.nocab, c.nama_comp, a.kodeprod, namaprod, unit as total_unit, val as total_val, 
                    sum(unit/pembagi) as avg_unit_6_bln, 
                    sum(val/pembagi) as avg_value_6_bln, stok_akhir, 
                    ($bulan_max_st/(sum(unit/pembagi))*30) as doi,
                    urutan, $id
            from
            (
                select  nocab, kodeprod, namaprod, unit, val, 
                        ".$data_untuk_pembagi."
                        ".$data_untuk_pembagi_last."
                from
                (
                    select  nocab, kode_comp, kodeprod, namaprod, 
                            SUM(banyak) as unit, sum(tot1) as val, 
                            ".$data_sum."
                            ".$data_sum_last."  
                    FROM        
                    (
                        ".$data_fi_ri."
                    )a GROUP BY nocab, kodeprod
                )a GROUP BY nocab, kodeprod
            )a LEFT JOIN
            (
                select nocab,kodeprod,stok as stok_akhir,bulan
                from
                (
                    select  a.nocab as nocab,
                            substr(bulan, 3)AS bulan,a.kodeprod as kodeprod,
                            sum(if(kode_gdg='PST',
                            ((Saldoawal+masuk_pbk+masuk_supp+retur_sal+bPinjam+kvretur+kr_gudang+retur_depo)-
                            (sales+pinjam+minta_depo+kvbeli+kr_min)-(rusak+sisih)),(Saldoawal+masuk_pbk+retur_sal+kvretur+bPinjam+tukar_msk+msk_gddepo) -
                            (sales+Pinjam+kvbeli+retur_depo+tukar_klr+klr_gddepo))) as stok
                    FROM    data$year.st a
                    where   a.kodeprod in (".$code.") AND kode_gdg != ''  and nocab = '$nocab'  
                    GROUP BY    nocab,bulan,kodeprod
                    ORDER BY    nocab
                )a
                where bulan = (
                                select max(substr(bulan, 3)) as BULAN
                                from data$year.st
                                where nocab ='$nocab'
                            )
                ORDER BY nocab, bulan desc
            )b on a.nocab = b.nocab and a.kodeprod = b.kodeprod
            LEFT JOIN
            (
                select  kode_comp, naper, nama_comp, urutan
                from    mpm.tbl_tabcomp
                where   `status` = 1
                GROUP BY    naper
                ORDER BY urutan asc
            )c on a.nocab = c.naper
            GROUP BY a.nocab,kodeprod
        ";
        /*
        echo "<br>";
        echo "<pre>";
        print_r($sql);
        echo "</pre>";
        */
        $sql_insert = $this->db->query($sql);

        /* PROSES TAMPIL KE WEBSITE */
          
            //$this->db->order_by('tbl_avg_sales_stok.nocab','asc');
            $this->db->order_by('tbl_avg_sales_stok.urutan','asc');
            //$this->db->order_by('tbl_avg_sales_stok.kodeprod','asc');
            $this->db->where("tbl_avg_sales_stok.id = ".'"'.$id.'"');
            $hasil = $this->db->get('tbl_avg_sales_stok');       
            if ($hasil->num_rows() > 0) 
            {
                return $hasil->result();
            } else {
                return array();
            }
        /* END PROSES TAMPIL KE WEBSITE */

        
    }

    public function get_namacomp($key='')
    {
        
        $userid=$this->session->userdata('id');
        //echo "id : ".$userid;

        /*cek hak DP apa saja yang dapat dilihat*/
            $this->db->where('id = '.'"'.$userid.'"');
            // $this->db->where('id','391');
            $query = $this->db->get('mpm.user');
            foreach ($query->result() as $row) {
                $wilayah_nocab = $row->wilayah_nocab;
            }
        /*end cek hak DP apa saja yang dapat dilihat*/


        if ($wilayah_nocab == '' || $wilayah_nocab == null) {
            $wil = '';
        } else {
            $wil = "where a.nocab in ($wilayah_nocab)";
        }
        

        $year = $key['year'];
        $sql="
        select  a.nocab, concat(a.kode_comp, a.nocab)kode, b.nama_comp as nama_comp, b.branch_name
FROM
(
                select  kode_comp, nocab
                from        data$year.fi  
                GROUP BY kode_comp
)a INNER JOIN 
(
        select  naper, nocab, kode_comp, nama_comp, branch_name
        FROM        mpm.tbl_tabcomp
        WHERE       status = 1
        order by nama_comp
)b on concat(a.kode_comp, a.nocab) = concat(b.kode_comp, b.nocab)
$wil
GROUP BY nocab
ORDER BY b.branch_name";

        //$query=$this->db->query($sql,array($key));
        $query=$this->db->query($sql);
        return $query;

    }

    public function stock_dp($dataSegment){

        $user=$this->session->userdata('username');
        if ( function_exists( 'date_default_timezone_set' ) )
        date_default_timezone_set('Asia/Jakarta');   

        /* --------- DEFINISI VARIABEL---------------- */
        //echo "<pre>";
        $year = $dataSegment['year'];
        $kode = $dataSegment['kode'];
        //$dp = substr($kode, 3,2);
        $dp = $kode;
        $uv = $dataSegment['uv'];
        $id=$this->session->userdata('id');
        /*
        echo "<pre>";
        echo "kode : ";
        print_r($kode);
        echo "<br>nocab : ";
        print_r($dp);
        echo "<br>year : ";
        print_r($year);
        echo "<br>unit/value";
        print_r($uv);
        echo "</pre>";
        */
        if ($year == '0') {
            
            echo '<script>alert("Anda belum meilih Tahun.");</script>';
            redirect('all_stock/stock_dp',refresh);

        } else {
            
            
        }

        /* cek apakah user sudah meilih tahun dan sub branch dengan benar */
        $sql = "
            select  1
            from
            (
                select      concat(kode_comp, nocab) as kode, nocab
                from        data$year.fi
                GROUP BY    kode_comp
            )a 
            where nocab = "."'".$kode."'".
        "";
        /* end */

        $query = $this->db->query($sql);
        
        if ($query->num_rows() > 0) {
            
            //echo "data benar";

        } else {
            
            echo '<script>alert("Ada kesalahan dalam pemilihan Tahun dan Sub Branch (Tahun dan Sub Branch yang dipilih tidak cocok). \n \n Website akan kembali ke halaman awal");</script>';
            //redirect('all_stock/stock_dp',refresh);
        }

        /*
        echo "<pre>";
        print_r($sql);
        echo "<br>";
        print_r($query);
        echo "</pre>";
        */

        $sql="select max(bulan) as bulan from data$year.st where nocab=".'"'.$dp.'"';
        $query = $this->db->query($sql);        
        $row=$query->row();
        $bulan=$row->bulan;
        $tanggal=$year.'-'.substr('00'.$bulan,-2).'-01';
        $year2=substr($year,-2);
        $date[6]=date('Y-m-d',strtotime('-6 month',strtotime($tanggal)));
        $date[5]=date('Y-m-d',strtotime('-5 month',strtotime($tanggal)));
        $date[4]=date('Y-m-d',strtotime('-4 month',strtotime($tanggal)));
        $date[3]=date('Y-m-d',strtotime('-3 month',strtotime($tanggal)));
        $date[2]=date('Y-m-d',strtotime('-2 month',strtotime($tanggal)));
        $date[1]=date('Y-m-d',strtotime('-1 month',strtotime($tanggal)));
        /*
        echo "<pre>";
        echo "<br>";
        echo "sql : ".$sql."<br>";
        echo "bulan : ".$bulan."<br>";
        echo "tanggal : ".$tanggal."<br>";
        echo "year2 : ".$year2."<br>";
        echo "</pre>";
        */
        $selectfi="
            select  kodeprod,
                    sum(banyak) as average 
            from    data".date('Y',strtotime($date[1])).".fi
            where   nocab=".'"'.$dp.'"'." and kode_type<>'TD' and bulan =".date('m',strtotime($date[1]))."
            group by kodeprod";
                
        for($i=2;$i<=6;$i++)
        {
             $selectfi.=" union all
                select  kodeprod,
                        sum(banyak) as average 
                from    data".date('Y',strtotime($date[$i])).".fi
                where   nocab=".'"'.$dp.'"'." and kode_type<>'TD' and 
                        bulan =".date('m',strtotime($date[$i]))." 
                group by kodeprod
                ";
        }

        $selectri="
            select  kodeprod,
                    sum(banyak) as  average 
            from    data".date('Y',strtotime($date[1])).".ri
            where   nocab=".'"'.$dp.'"'." and bulan =".date('m',strtotime($date[1]))." 
            group by kodeprod";

        for($i=2;$i<=6;$i++)
        {
            $selectri.=" union all
             select kodeprod,
                    sum(banyak) as average 
            from    data".date('Y',strtotime($date[$i])).".ri
            where   nocab=".'"'.$dp.'"'." and bulan =".date('m',strtotime($date[$i]))." 
            group by kodeprod";
        }

        switch($uv)
        {
             case 0:
             {
                 $harga=1;
                 $harga2=1;
             }break;
             case 1:
             {
                 $harga='b.h_dp';
                 $harga2='h_dp';
             }break;
        }

        /* PROSES DELETE MPM.stokdp */
            $query = "delete from mpm.stokdp where id = ".'"'.$id.'"'."";
            $sql = $this->db->query($query);
            /*
            echo "<pre>";
            print_r($query);
            print_r($sql);
            echo "</pre>";
            */
        /* END PROSES DELETE MPM.OMZET_NEW */

        $sql=" 
        insert into mpm.stokdp
        select kodeprod,namaprod,b1,b2,b3,b4,b5,b6,b7,b8,b9,b10,b11,b12,
                (b.rata*".$harga2.")as rata,
                ((".$bulan."/(b.rata*".$harga2."))*30) as doi,
                ".$id." 
        from
        (
            select  a.kodeprod,
                    a.namaprod,
                    ".$harga."
                    ,sum(if(bulan=01,stok,0))*".$harga." as b1
                    ,sum(if(bulan=02,stok,0))*".$harga." as b2
                    ,sum(if(bulan=03,stok,0))*".$harga." as b3
                    ,sum(if(bulan=04,stok,0))*".$harga." as b4
                    ,sum(if(bulan=05,stok,0))*".$harga." as b5
                    ,sum(if(bulan=06,stok,0))*".$harga." as b6
                    ,sum(if(bulan=07,stok,0))*".$harga." as b7
                    ,sum(if(bulan=08,stok,0))*".$harga." as b8
                    ,sum(if(bulan=09,stok,0))*".$harga." as b9
                    ,sum(if(bulan=10,stok,0))*".$harga." as b10
                    ,sum(if(bulan=11,stok,0))*".$harga." as b11
                    ,sum(if(bulan=12,stok,0))*".$harga." as b12
            from
            (
                select  kodeprod,namaprod,substr(bulan,3) as bulan,
                        sum( if(kode_gdg='PST',
                        ((Saldoawal+masuk_pbk+masuk_supp+retur_sal+bPinjam+kvretur+kr_gudang+retur_depo)-
                        (sales+pinjam+minta_depo+kvbeli+kr_min)-(rusak+sisih)),(Saldoawal+masuk_pbk+retur_sal+kvretur+bPinjam+tukar_msk+msk_gddepo) -
                        (sales+Pinjam+kvbeli+retur_depo+tukar_klr+klr_gddepo))) as stok
                from    data".$year.".st 
                where   nocab in (".'"'.$dp.'"'.") and kode_gdg!= '' 
                group by kodeprod,bulan
                order by kodeprod
            )a inner join 
                (
                    SELECT  kodeprod, h_dp as h_dpx, jual as h_dp
                    FROM
                    (
                                select  a.kodeprod, 
                                        a.h_dp * (100-d_dp)/100 as h_dp 
                                from    mpm.prod_detail a 
                                        inner join mpm.tabprod b using(kodeprod)
                                where   tgl=(
                                                select  max(tgl) 
                                                from    mpm.prod_detail 
                                                where   kodeprod=a.kodeprod
                                        )ORDER BY KODEPROD
                    )a left JOIN
                    (
                        select  productid, jual
                        from        mpm.m_product
                        ORDER BY    productid
                    )b on a.kodeprod = b.productid
                    ORDER BY productid asc
                ) b using(kodeprod) 
                    group by kodeprod
                ) a left join (select kodeprod,sum(average)/6 as rata from(
                    ".$selectfi."
                    union all
                    ".$selectri."
            )a group by kodeprod )b using(kodeprod)

            union all

            select 'z_','grand total',sum(b1),sum(b2),sum(b3),sum(b4),sum(b5),sum(b6),sum(b7),sum(b8),sum(b9),sum(b10),sum(b11),sum(b12),
                sum((b.rata*".$harga2."))as rata,
                sum(((".$bulan."/(b.rata*".$harga2."))*30)) as doi,
                ".$id." 
        from
        (
            select  a.kodeprod,
                    a.namaprod,
                    ".$harga."
                    ,sum(if(bulan=01,stok,0))*".$harga." as b1
                    ,sum(if(bulan=02,stok,0))*".$harga." as b2
                    ,sum(if(bulan=03,stok,0))*".$harga." as b3
                    ,sum(if(bulan=04,stok,0))*".$harga." as b4
                    ,sum(if(bulan=05,stok,0))*".$harga." as b5
                    ,sum(if(bulan=06,stok,0))*".$harga." as b6
                    ,sum(if(bulan=07,stok,0))*".$harga." as b7
                    ,sum(if(bulan=08,stok,0))*".$harga." as b8
                    ,sum(if(bulan=09,stok,0))*".$harga." as b9
                    ,sum(if(bulan=10,stok,0))*".$harga." as b10
                    ,sum(if(bulan=11,stok,0))*".$harga." as b11
                    ,sum(if(bulan=12,stok,0))*".$harga." as b12
            from
            (
                select  kodeprod,namaprod,substr(bulan,3) as bulan,
                        sum( if(kode_gdg='PST',
                        ((Saldoawal+masuk_pbk+masuk_supp+retur_sal+bPinjam+kvretur+kr_gudang+retur_depo)-
                        (sales+pinjam+minta_depo+kvbeli+kr_min)-(rusak+sisih)),(Saldoawal+masuk_pbk+retur_sal+kvretur+bPinjam+tukar_msk+msk_gddepo) -
                        (sales+Pinjam+kvbeli+retur_depo+tukar_klr+klr_gddepo))) as stok
                from    data".$year.".st 
                where   nocab in (".'"'.$dp.'"'.") and kode_gdg!= '' 
                group by kodeprod,bulan
                order by kodeprod
            )a inner join 
                (
                    SELECT  kodeprod, h_dp as h_dpx, jual as h_dp
                    FROM
                    (
                                select  a.kodeprod, 
                                        a.h_dp * (100-d_dp)/100 as h_dp 
                                from    mpm.prod_detail a 
                                        inner join mpm.tabprod b using(kodeprod)
                                where   tgl=(
                                                select  max(tgl) 
                                                from    mpm.prod_detail 
                                                where   kodeprod=a.kodeprod
                                        )ORDER BY KODEPROD
                    )a left JOIN
                    (
                        select  productid, jual
                        from        mpm.m_product
                        ORDER BY    productid
                    )b on a.kodeprod = b.productid
                    ORDER BY productid asc
                ) b using(kodeprod) 
                    group by kodeprod
                ) a left join (select kodeprod,sum(average)/6 as rata from(
                    ".$selectfi."
                    union all
                    ".$selectri."
            )a group by kodeprod )b using(kodeprod)

            ";
            /*
            echo "<pre>";
            print_r($sql);
            echo "</pre>";
            */
            $query = $this->db->query($sql);

            /* PROSES TAMPIL KE WEBSITE */

            $this->db->order_by('kodeprod','asc');
            $this->db->where("id = ".'"'.$id.'"');
            $hasil = $this->db->get('stokdp');       
            if ($hasil->num_rows() > 0) 
            {
                return $hasil->result();
            } else {
                return array();
            }
        /* END PROSES TAMPIL KE WEBSITE */

    
    }           

    public function stock_by_principal($dataSegment,$kodeprod=null){

        $user=$this->session->userdata('username');
        if ( function_exists( 'date_default_timezone_set' ) )
        date_default_timezone_set('Asia/Jakarta');        

        /* ---------DEFINISI VARIABEL----------------- */
        
            $tgl_created='"'.date('Y-m-d H:i:s').'"';        
            $id=$this->session->userdata('id');
            $supp = $dataSegment['supp'];
            $uv = $dataSegment['uv'];
                switch($uv)
                {
                    case 0:
                    {
                         $harga=1;
                        
                    }break;
                    case 1:
                    {
                         $harga='h_dp';
                         
                     }break;
                }
            $year = $dataSegment['tahun'];

                if($supp=='001') //jika pilih supplier : deltomed
                {   
                    $wheresupp='(a.kodeprod like "60%" or a.kodeprod like "01%" or a.kodeprod like "50%" or a.kodeprod like "70%" or a.kodeprod like "110%") and a.kodeprod not in (select kodeprod from mpm.tabprod_unilever)';
                }            
                else if($supp=='005') //jika pilih supplier : ultrasakti
                {
                    $wheresupp='a.kodeprod like "06%" ';
                }
                else if($supp=='002') //jika pilih supplier : marguna
                {
                    $wheresupp='a.kodeprod like "02%" ';
                }
                else if($supp=='009') //jika pilih supplier : unilever
                {
                    $wheresupp='supp = 009';
                }
                else //jika pilih supplier : selain yang disebutkan di atas
                {
                    $wheresupp="a.kodeprod like '".substr($supp,-2)."%'";
                }   

            //echo "year : ".$year;
            //echo "<br />uv : ".$uv."<br>";
        /* ---------END DEFINISI VARIABEL---------------- */

        /* PROSES DELETE MPM.stokprod_new */
            $query = "delete from mpm.stok_principal where id = ".$id."";
            $sql = $this->db->query($query);
            
            //echo "<pre>";
            //print_r($query);
            //echo "</pre>";            
        
        /* END PROSES DELETE MPM.OUTLET_NEW */

        /* PROSES INSERT KE MPM.stokprod_new */
            
        $sql="
        insert into mpm.stok_principal
        select  a.nocab, b.branch_name, b.nama_comp, a.kodeprod, c.namaprod, c.grup, c.nama_group,
                b1,b2,b3,b4,b5,b6,b7,b8,b9,b10,b11,b12, 
                h_dp,(b1*h_dp) as v1,(b2*h_dp) as v2,(b3*h_dp) as v3,(b4*h_dp) as v4,(b5*h_dp) as v5,(b6*h_dp) as v6,
                (b7*h_dp) as v7,(b8*h_dp) as v8,(b9*h_dp) as v9,(b10*h_dp) as v10,(b11*h_dp) as v11,(b12*h_dp) as v12,
                $id, urutan
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
                        sum( if(kode_gdg='PST',
                        ((Saldoawal+masuk_pbk+masuk_supp+retur_sal+bPinjam+kvretur+kr_gudang+retur_depo)-
                        (sales+pinjam+minta_depo+kvbeli+kr_min)-(rusak+sisih)),(Saldoawal+masuk_pbk+retur_sal+kvretur+bPinjam+tukar_msk+msk_gddepo) -
                        (sales+Pinjam+kvbeli+retur_depo+tukar_klr+klr_gddepo))) as stok
                from    data$year.st                              
                group by nocab, kodeprod,bulan
                order by kodeprod
            )a GROUP BY nocab, kodeprod
        )a LEFT JOIN
        (
            select  nocab, kode_comp, nama_comp, urutan, branch_name
            FROM    mpm.tbl_tabcomp
            where   `status` = 1
            GROUP BY nocab
        )b on a.nocab = b.nocab
        INNER JOIN
        (
            select  a.kodeprod, namaprod,b.h_dp, a.grup, c.nama_group
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
            )c on a.grup = c.kode_group
            where   supp = '$supp'
        )c on a.kodeprod = c.kodeprod
        ORDER BY urutan asc, nocab asc, kodeprod

    ";
        /* END PROSES DELETE MPM.OUTLET_NEW */
            /*
            echo "<pre>";
            print_r($sql);
            echo "</pre>";
            */
            $sql_insert = $this->db->query($sql);

        /* PROSES TAMPIL KE WEBSITE */      
            $this->db->order_by('stok_principal.urutan','asc');
            $this->db->where("stok_principal.id = ".'"'.$id.'"');
            $hasil = $this->db->get('stok_principal');       
            if ($hasil->num_rows() > 0) 
            {
                return $hasil->result();
            } else {
                return array();
            }
        /* END PROSES TAMPIL KE WEBSITE */
    }

    public function stock_git($data){

        $user=$this->session->userdata('username');
        if ( function_exists( 'date_default_timezone_set' ) )
        date_default_timezone_set('Asia/Jakarta');        

        /* ---------DEFINISI VARIABEL----------------- */
        $tgl_created='"'.date('Y-m-d H:i:s').'"';        
        $id_user=$this->session->userdata('id');
        $supp = $data['supp'];
        $tahun = $data['tahun'];
        $bulan = $data['bulan'];

        if($supp=='001') //jika pilih supplier : deltomed
        {   
            $wheresupp='(a.kodeprod like "60%" or a.kodeprod like "01%" or a.kodeprod like "50%" or a.kodeprod like "70%") and a.kodeprod not in (select kodeprod from mpm.tabprod_unilever)';
        }            
        else if($supp=='005') //jika pilih supplier : ultrasakti
        {
            $wheresupp='a.kodeprod like "06%" ';
        }
        else if($supp=='002') //jika pilih supplier : marguna
        {
            $wheresupp='a.kodeprod like "02%" ';
        }
        else if($supp=='009') //jika pilih supplier : unilever
        {
            $wheresupp='supp = 009';
        }
        else //jika pilih supplier : selain yang disebutkan di atas
        {
            $wheresupp="a.kodeprod like '".substr($supp,-2)."%'";
        }   

        /* PROSES DELETE db_po.t_temp_git */
            $query = "delete from db_po.t_temp_git where userid = ".$id_user."";
            $sql = $this->db->query($query);

        /* PROSES INSERT KE db_po.t_temp_git */
            $sql = "
            insert into db_po.t_temp_git
            select  a.nocab, kodeprod, namaprod, stok, git, $tgl_created, $id_user, pk
FROM
(
        select  a.nocab,a.kodeprod,namaprod,
                                sum(if(kode_gdg='PST',
                                ((Saldoawal+masuk_pbk+masuk_supp+retur_sal+bPinjam+kvretur+kr_gudang+retur_depo)-
                                (sales+pinjam+minta_depo+kvbeli+kr_min)-(rusak+sisih)),(Saldoawal+masuk_pbk+retur_sal+kvretur+bPinjam+tukar_msk+msk_gddepo) -
                                (sales+Pinjam+kvbeli+retur_depo+tukar_klr+klr_gddepo))) as stok
        from    data$tahun.st a
        where   substr(bulan,3) = $bulan and $wheresupp       
        group by a.nocab, kodeprod,bulan
        order by a.nocab, kodeprod  
)a LEFT JOIN
(
            SELECT a.userid, a.username, a.nocab, a.productid, b.stok as stock_akhir, qty as git,pk
            FROM
            (
                    select a.userid,a.username, a.nocab, a.productid, a.qty, max(pk) as pk
                    FROM
                    (
                            select  a.userid, c.username, d.nocab, b.productid, sum(b.qty3) as qty, max(pk) as pk
                            from    db_po.t_do a left join dbsls.t_inv_detail b
                                                    on a.no_inv = b.no_inv
                                            LEFT JOIN mpm.`user` c on a.userid = c.id
                                            LEFT JOIN 
                                            (
                                                    select kode_comp, nocab, nama_comp
                                                    from mpm.tbl_tabcomp
                                                    where `status` = 1
                                                    GROUP BY kode_comp
                                            )d on c.username = d.kode_comp
                            where year(a.tgl_do) = $tahun and month(a.tgl_do) = $bulan and a.`status` is null
                            GROUP BY c.username, b.productid,pk
                            order by productid, pk desc
                    )a GROUP BY username, productid
            )a LEFT JOIN
            (
                    select  nocab,kodeprod,namaprod,substr(bulan,3) as bulan,
                                                    sum( if(kode_gdg='PST',
                                                    ((Saldoawal+masuk_pbk+masuk_supp+retur_sal+bPinjam+kvretur+kr_gudang+retur_depo)-
                                                    (sales+pinjam+minta_depo+kvbeli+kr_min)-(rusak+sisih)),(Saldoawal+masuk_pbk+retur_sal+kvretur+bPinjam+tukar_msk+msk_gddepo) -
                                                    (sales+Pinjam+kvbeli+retur_depo+tukar_klr+klr_gddepo))) as stok
                    from    data$tahun.st 
                    where   substr(bulan,3) = $bulan
                    group by nocab, kodeprod,bulan
                    order by nocab, kodeprod
        )b on a.nocab = b.nocab and a.productid = b.kodeprod
)b on a.nocab = b.nocab and a.kodeprod = b.productid
            ";
            
            echo "<pre>";
            print_r($sql);
            echo "</pre>";
            
            $proses = $this->db->query($sql);
      
            
        /* PROSES TAMPIL KE WEBSITE */  
                            
            $sql = "
            select b.nama_comp, a.kodeprod,a.namaprod,a.stok,a.git,a.userid
            from db_po.t_temp_git a left join (
                select nocab,nama_comp, urutan
                from mpm.tbl_tabcomp
                where `status` = 1
                GROUP BY nocab
            )b on a.nocab = b.nocab
            where a.userid = $id_user
            ORDER BY urutan asc
                
            ";

            $hasil = $this->db->query($sql);
            if ($hasil->num_rows() > 0) 
            {
                return $hasil->result();
            } else {
                return array();
            }
                          
        /* END PROSES TAMPIL KE WEBSITE */
    } 

    public function git($data){

        $user=$this->session->userdata('username');
        if ( function_exists( 'date_default_timezone_set' ) )
        date_default_timezone_set('Asia/Jakarta');        

        /* ---------DEFINISI VARIABEL----------------- */
        $tgl_created=date('Y-m-d H:i:s');        
        $id_user=$this->session->userdata('id');
        $supp = $data['supp'];
        $tahun = $data['tahun'];
        $bulan = $data['bulan'];

        if($supp=='001') //jika pilih supplier : deltomed
        {   
            $wheresupp='and (kodeprod like "60%" or kodeprod like "01%" or kodeprod like "50%" or kodeprod like "70%") and kodeprod not in (select kodeprod from mpm.tabprod_unilever)';
            
        }            
        else if($supp=='005') //jika pilih supplier : ultrasakti
        {
            $wheresupp='and kodeprod like "06%" ';
        }
        else if($supp=='002') //jika pilih supplier : marguna
        {
            $wheresupp='and kodeprod like "02%" ';
        }
        else if($supp=='009') //jika pilih supplier : unilever
        {
            $wheresupp='and supp = 009';
        }
        else //jika pilih supplier : selain yang disebutkan di atas
        {
            $wheresupp="and kodeprod like '".substr($supp,-2)."%'";
        }   

        /* PROSES DELETE db_po.t_temp_git */
            $query = "delete from db_po.t_temp_git where userid = ".$id_user."";
            $sql = $this->db->query($query);

        /* PROSES INSERT KE db_po.t_temp_git */
            $sql = "
            insert into db_po.t_temp_git
            select a.nocab, a.nama_comp, a.kodeprod, b.stok, git, '$tgl_created', $id_user, $tahun, a.bulan
            from
            (       select username, kode_comp, nama_comp,nocab, kodeprod, git, bulan
                    FROM
                    (
                            select c.username, a.userid, b.kodeprod, sum(b.banyak) as git, month(a.tglpo) as bulan
                            from   mpm.po a INNER JOIN mpm.po_detail b
                                     on a.id = b.id_ref
                                   LEFT JOIN mpm.`user` c on a.userid = c.id
                            where   year(tglpo) = $tahun and month(tglpo) = $bulan and a.deleted = 0 and 
                                    b.deleted = 0 and (b.status_terima is null or b.status_terima = null or b.status_terima = '0') $wheresupp
                            GROUP BY b.kodeprod, a.userid
                            ORDER BY bulan asc, b.kodeprod
                    )a inner JOIN
                    (
                        select kode_comp,nama_comp,nocab
                        from mpm.tbl_tabcomp
                        where `status` = 1 and active = 1
                        GROUP BY concat(kode_comp,nocab)
                    )b on a.username = b.kode_comp
                    ORDER BY b.kode_comp, nocab, kodeprod
            )a INNER JOIN
            (
                select  nocab,kodeprod,namaprod,substr(bulan,3) as bulan,
                                sum( if(kode_gdg='PST',
                                ((Saldoawal+masuk_pbk+masuk_supp+retur_sal+bPinjam+kvretur+kr_gudang+retur_depo)-
                                (sales+pinjam+minta_depo+kvbeli+kr_min)-(rusak+sisih)),(Saldoawal+masuk_pbk+retur_sal+kvretur+bPinjam+tukar_msk+msk_gddepo) -
                                (sales+Pinjam+kvbeli+retur_depo+tukar_klr+klr_gddepo))) as stok
                from    data$tahun.st 
                where   substr(bulan,3) = $bulan
                group by nocab, kodeprod,bulan
                order by nocab, kodeprod

            )b on a.nocab = b.nocab and a.kodeprod = b.kodeprod
            ORDER BY kode_comp, a.nocab, a.kodeprod
            ";
            
            //echo "<pre>";
            //print_r($sql);
            //echo "</pre>";
            
            $proses = $this->db->query($sql);
      
            
        /* PROSES TAMPIL KE WEBSITE */  
                            
            $sql = "
            select a.nocab, a.nama_comp, a.kodeprod, c.namaprod, a.stok, a.git, a.tahun, a.bulan
            from db_po.t_temp_git a
            LEFT JOIN
            (
                select nocab,urutan
                from mpm.tbl_tabcomp
                where `status` = 1
                GROUP BY nocab
            )b on a.nocab = b.nocab
            LEFT JOIN mpm.tabprod c on a.kodeprod = c.kodeprod
            where a.userid = $id_user
            ORDER BY urutan asc
                
            ";

            $hasil = $this->db->query($sql);
            if ($hasil->num_rows() > 0) 
            {
                return $hasil->result();
            } else {
                return array();
            }
                          
        /* END PROSES TAMPIL KE WEBSITE */
    } 

    public function git_new($data){

        if ( function_exists( 'date_default_timezone_set' ) )
        date_default_timezone_set('Asia/Jakarta');        

        /* ---------DEFINISI VARIABEL----------------- */
        $tgl_created=date('Y-m-d H:i:s');        
        $id=$this->session->userdata('id');
        $supp = $data['supp'];
        $tahun = $data['tahun'];
        $bulan = $data['bulan'];

        /* PROSES DELETE db_po.t_temp_git */
            $query = "delete from db_po.t_git_new_temp where id = ".$id."";
            $sql = $this->db->query($query);

            $query = "delete from db_po.t_git_new where id = ".$id."";
            $sql = $this->db->query($query);

        /* PROSES INSERT KE db_po.t_temp_git */
            $sql = "
            insert into db_po.t_git_new_temp            
            select a.nocab, a.kodeprod, stok, git, $id
            from 
            (
                select  nocab,a.kodeprod,
                        sum( if(kode_gdg='PST',
                        ((Saldoawal+masuk_pbk+masuk_supp+retur_sal+bPinjam+kvretur+kr_gudang+retur_depo)-
                        (sales+pinjam+minta_depo+kvbeli+kr_min)-(rusak+sisih)),(Saldoawal+masuk_pbk+retur_sal+kvretur+bPinjam+tukar_msk+msk_gddepo) -
                        (sales+Pinjam+kvbeli+retur_depo+tukar_klr+klr_gddepo))) as stok
                from    data$tahun.st a INNER JOIN mpm.tabprod b
                            on a.kodeprod = b.kodeprod
                where   substr(bulan,3) = $bulan and b.supp ='$supp'
                group by nocab, kodeprod,bulan
                order by nocab, kodeprod
            )a LEFT JOIN
            (		
                select a.username, a.kodeprod, a.git, b.nocab
                FROM
                (
					select c.username, a.userid, b.kodeprod, sum(b.banyak) as git, month(a.tglpo) as bulan
					from   mpm.po a INNER JOIN mpm.po_detail b
                                on a.id = b.id_ref LEFT JOIN mpm.`user` c 
                                on a.userid = c.id
					where   year(tglpo) in (date_format(date(now()) - INTERVAL '1' MONTH,'%Y'),year(date(now()))) and 
                            month(tglpo) in (date_format(date(now()) - INTERVAL '1' MONTH,'%m'),month(date(now()))) and
                            a.deleted = 0 and b.deleted = 0 and (b.status_terima is null or b.status_terima = null or b.status_terima = '0')
					GROUP BY b.kodeprod, a.userid
					ORDER BY c.username, b.kodeprod
                )a INNER JOIN
		        (	
                    select a.id, a.kode, a.kode_comp, a.nocab, a.active
                    FROM
                    (
                        select a.id, concat(a.kode_comp, a.nocab) as kode, kode_comp, nocab, active
                        from 	mpm.tbl_tabcomp a
                        where a.`status` = 1 and active = 1
                        GROUP BY concat(a.kode_comp, a.nocab)
                        ORDER BY kode_comp
			        )a where a.id = 
			        (
                        select max(b.id) 
                        from mpm.tbl_tabcomp b
                        WHERE b.`status` = 1 and b.active = 1 and a.kode_comp = b.kode_comp
                        GROUP BY b.kode_comp
			        )
		        )b on a.username = b.kode_comp
            )b on a.nocab = b.nocab and a.kodeprod = b.kodeprod
            where stok <> 0
            ";
            
            // echo "<pre>";
            // print_r($sql);
            // echo "</pre>";
            
            $proses = $this->db->query($sql);

            $sql = "
                insert into db_po.t_git_new
                select b.branch_name, b.nama_comp, a.nocab, a.kodeprod, c.namaprod, a.stock,a.git, b.urutan, '$tahun', '$bulan',  $id
                from db_po.t_git_new_temp a LEFT JOIN
                (
                    select nocab, branch_name, nama_comp, urutan
                    from mpm.tbl_tabcomp b
                    where `status` = 1 and active = 1
                    GROUP BY nocab
                )b on a.nocab = b.nocab INNER JOIN mpm.tabprod c
                on a.kodeprod = c.KODEPROD
                ORDER BY urutan
            ";

            $proses = $this->db->query($sql);

            $sql = "select * from db_po.t_git_new where id = $id";
            $hasil = $this->db->query($sql);
            if ($hasil->num_rows() > 0) 
            {
                return $hasil->result();
            } else {
                return array();
            } 
    } 

    public function data_stock_mpi()
	{
		$sql = "
            select  a.cut_off, a.last_update, b.username, date(a.cut_off) as tgl, 
                    sum(a.onhand) as onhand, sum(a.git) as git, sum(a.onhand+a.git) as stock,
                    sum((a.onhand + a.git) * a.hna) as stock_value
            from mpi.t_stock_mpi a LEFT JOIN mpm.`user` b on a.id = b.id
            GROUP BY a.cut_off
		";
		$hasil = $this->db->query($sql);
		if ($hasil->num_rows() > 0) {
			return $hasil->result();
		} else {
			return array();
		}

    }
    
    public function insert_stock_mpi(){
        $id = $this->session->userdata('id');
        if ( function_exists( 'date_default_timezone_set' ) )
        date_default_timezone_set('Asia/Jakarta');  
        $created=date('Y-m-d H:i:s');

		$client = new Client();

		$response = $client->request('GET', "http://222.165.240.170:8012/report-api/public/stock_data", [
			'query' => [
				'key' => 'c089ed651ff206a7361406b0a1929db0'
			]
		]);

		$result = json_decode($response->getBody()->getContents(),true);
        // return $result;
        // echo "<pre>";
        // var_dump($result(array()));
        // echo "</pre>";

		if($result != array())
		{
			$sql = "delete from mpi.t_temp_stock_mpi where id = $id";
			$proses = $this->db->query($sql);
		}else{
            echo "b";
        }
		foreach ($result as $key) {
            $data = array(
                'cut_off' => $key["CUT_OFF"],
                'classprod' => $key["CLASSPROD"],
                'class_name' => $key["CLASS_NAME"],
                'item' => $key["ITEM"],
                'produk' => $key["PRODUK"],
                'hna' => $key["HNA"],
                'kemasan' => $key["KEMASAN"],
                'lot_number' => $key["LOT_NUMBER"],
                'ed' => $key["ED"],
                'onhand' => $key["ONHAND"],
                'git' => $key["GIT"],
                'branch_id' => $key["BRANCH_ID"],
                'branch_code' => $key["BRANCH_CODE"],
                'branch_name' => $key["BRANCH_NAME"],
                'receipt_date' => $key["RECEIPT_DATE"],
                'last_activity_date' => $key["LAST_ACTIVITY_DATE"],
                'tgl_git' => $key["TGL_GIT"],
                'id' => $id,
                'last_update' => $created
			);
			$proses = $this->db->insert('mpi.t_temp_stock_mpi', $data);
		}	
		$sql = "select * from mpi.t_temp_stock_mpi where id = $id";
		$hasil = $this->db->query($sql);
		// $hasil = $this->db->get('user');
		if ($hasil->num_rows() > 0) {
			return $hasil->result();
		} else {
			return array();
		}
    }
    
    public function insert_mpi_to_db()
	{
        $id = $this->session->userdata('id');
        
        $proses = $this->db->query("select cut_off from mpi.t_temp_stock_mpi limit 1")->result();
        foreach ($proses as $x) {
            $cut_off = $x->cut_off;
        }

        $sql = "select * from mpi.t_stock_mpi a where a.cut_off = '$cut_off' limit 1";
        $proses = $this->db->query($sql)->num_rows();
        echo "proses: ".$proses;
        if ($proses > 0) {
            $proses = $this->db->query("delete from mpi.t_stock_mpi where cut_off='$cut_off'");
        }

		$sql = "
			insert into mpi.t_stock_mpi
			select * from mpi.t_temp_stock_mpi where id = $id
		";
		$hasil = $this->db->query($sql);
		if ($hasil) {
			echo "<script>
			alert('Insert Berhasil');
			window.location.href='../../all_stock/insert_stock_mpi';
			</script>";
		} else {
			echo '<script>alert("Insert gagal. Harap hubungi IT !");</script>';
		}

    }
    
    public function monitoring_stock_mpi($cut_off_stock,$avg)
	{
        $id = $this->session->userdata('id');
        if ( function_exists( 'date_default_timezone_set' ) )
        date_default_timezone_set('Asia/Jakarta');  
        $created=date('Y-m-d H:i:s');

        // echo "avg : ".$avg."<br>";
        // echo "cut_off_stock : ".$cut_off_stock."<br>";

        $sql = "
            select 	date(a.cut_off) as cut_off
            from	mpi.t_stock_mpi a
            where 	date(a.cut_off) <= '$cut_off_stock'
            ORDER BY a.cut_off desc
            limit 1
        ";
        $proses = $this->db->query($sql)->result();
        foreach ($proses as $key) {
            $cut_off_stockx = $key->cut_off;
        }

        $del = $this->db->query("delete from mpi.t_temp_monitoring_stock_mpi where id = $id");
        $del = $this->db->query("delete from mpi.t_temp_monitoring_stock_sales_mpi where id = $id");
        $del = $this->db->query("delete from mpi.t_temp_monitoring_sales_berjalan_mpi where id = $id");
        $del = $this->db->query("delete from mpi.t_temp_monitoring_doi_mpi where id = $id");

		$sql = "
            insert into mpi.t_temp_monitoring_stock_mpi
            select a.item, a.produk, a.branch_id, a.branch_code, a.branch_name, 
            sum(a.onhand) as onhand_unit, sum(a.git) as git_unit, a.hna, sum(a.onhand) * a.hna as onhand_value,sum(a.git) * a.hna as git_value, 
            $id, '$created'
            from
            (
                select	a.item, a.produk, a.branch_id, a.branch_code, a.branch_name, a.onhand, a.git, a.hna
                from	mpi.t_stock_mpi a
                where 	date(a.cut_off) = '$cut_off_stockx'
            )a GROUP BY a.item, a.branch_code
		";
        $proses = $this->db->query($sql);

        // echo "<pre>";
        // print_r($sql);
        // echo "</pre>";

        //mencari max lastupdated dari setiap bulan

        $sql_del_datampi_temp = $this->db->query("delete from mpi.datampi_temp where created_by = $id");

        $sql_cari_max_lastupdated = "
            select  a.bulan, max(a.last_updated) as last_updated_x
            from    mpi.datampi a
            where   a.periode >= date_format(date(now()) - INTERVAL '$avg' MONTH, '%Y-%m') and
                        a.periode <= date_format(date(now()), '%Y-%m')
            GROUP BY a.bulan
        ";
        $proses_cari_max_lastupdated = $this->db->query($sql_cari_max_lastupdated)->result();
        foreach($proses_cari_max_lastupdated as $a){
            $sql_insert = "
                insert into mpi.datampi_temp
                select 	a.*, $id 
                from 	mpi.datampi a
                where 	a.bulan = $a->bulan and last_updated = '$a->last_updated_x'
            ";
            $proses_insert = $this->db->query($sql_insert);

        }


        $sql = "
            insert into mpi.t_temp_monitoring_stock_sales_mpi
            select	a.tgl_invoice,a.kode_cab, a.nama_cab,a.item_code, a.nama_produk, a.kemasan,sum(a.banyak) as total_unit,sum(a.banyak)/$avg as avg_unit, 
                    a.hna, sum(a.thna) as total_value, sum(a.thna)/$avg as avg_value, $id, '$created'
            from    mpi.datampi_temp a
            where   a.created_by = $id and a.periode >= date_format(date(now()) - INTERVAL '$avg' MONTH, '%Y-%m') and
                    a.periode <= date_format(date(now()), '%Y-%m')
            GROUP BY a.item_code, a.kode_cab
		";
        $proses = $this->db->query($sql);

        // echo "<pre>";
        // print_r($sql);
        // echo "</pre>";

        $sql = "
            insert into mpi.t_temp_monitoring_sales_berjalan_mpi
            select	a.tgl_invoice,a.kode_cab, a.nama_cab,a.item_code, a.nama_produk, a.kemasan,sum(a.banyak) as total_unit,sum(a.banyak)/$avg as avg_unit, 
                    a.hna, sum(a.thna) as total_value, sum(a.thna)/$avg as avg_value, $id, '$created'
            from    mpi.datampi_temp a
            where   a.created_by = $id and a.periode = date_format(date(now()), '%Y-%m')
            GROUP BY a.item_code, a.kode_cab
		";
        $proses = $this->db->query($sql);

        // echo "<pre>";
        // print_r($sql);
        // echo "</pre>";

        $sql = "
            insert into mpi.t_temp_monitoring_doi_mpi
            select	'$cut_off_stockx',a.kode_cab, a.nama_cab, a.item_code, a.nama_produk, a.kemasan, 
                    '' as kodeprod, '' as namaprod, '' as satuan, '' as `group`, '' as nama_group, '' as supp, '' as principal,
                    a.total_unit, a.avg_unit, a.total_value, a.avg_value, '' as unit_berjalan, '' as value_berjalan,
                    b.onhand_unit, b.git_unit, (b.onhand_unit / a.avg_unit * 30) as doi_onhand_unit, 
                    ((b.onhand_unit + b.git_unit) / a.avg_unit * 30) as doi_stock_unit, 
                    b.onhand_value, b.git_value, (b.onhand_value / a.avg_value * 30) as doi_onhand_value, 
                    ((b.onhand_value + b.git_value) / a.avg_value * 30) as doi_stock_value,
                    '' as po_outstanding_unit, '' as po_outstanding_value,
                    $id, '$created'			
            from 	mpi.t_temp_monitoring_stock_sales_mpi a LEFT JOIN (
                select 	a.item, a.produk, a.branch_id, a.branch_code, a.branch_name, a.onhand_unit, a.git_unit,a.onhand_value, a.git_value
                from 	mpi.t_temp_monitoring_stock_mpi a
                where 	a.id = $id
            )b on a.kode_cab = b.branch_id and a.item_code = b.item 
            where 	a.id = $id
            ORDER BY a.nama_cab, a.nama_produk
		";
        $proses = $this->db->query($sql);

        // echo "<pre>";
        // print_r($sql);
        // echo "</pre>";

        $sql = "                
            update mpi.t_temp_monitoring_doi_mpi a
            set a.kodeprod = (
                select b.kodeprod
                from mpi.t_produk_mapping b
                where a.item_code = b.kodeprod_mpi
            ),a.namaprod = (
                select b.namaprod
                from mpi.t_produk_mapping b
                where a.item_code = b.kodeprod_mpi
            ),a.satuan = (
                select b.satuan
                from mpi.t_produk_mapping b
                where a.item_code = b.kodeprod_mpi
            ),a.supp = (
                select b.supp
                from mpi.t_produk_mapping b
                where a.item_code = b.kodeprod_mpi
            ),  a.`group` = (
                select c.`grup`
                from mpm.tabprod c
                where a.kodeprod =c.kodeprod
            ), a.nama_group = (
                select d.nama_group
                from mpm.tbl_group d
                where a.`group` = d.kode_group
            ), a.principal = (
                select e.namasupp
                from mpm.tabsupp e
                where a.supp = e.supp
            )
            where a.id = $id
        ";
        $proses = $this->db->query($sql);

        $sql = "                
            update mpi.t_temp_monitoring_doi_mpi a
            set a.unit_berjalan = (
                select b.total_unit
                from mpi.t_temp_monitoring_sales_berjalan_mpi b
                where a.kode_cab = b.kode_cab and a.item_code = b.item_code and b.id = $id      
            ),a.value_berjalan = (
                select b.total_value
                from mpi.t_temp_monitoring_sales_berjalan_mpi b
                where a.kode_cab = b.kode_cab and a.item_code = b.item_code and b.id = $id      
            )
            where a.id = $id
        ";
        $proses = $this->db->query($sql);

        // echo "<pre>";
        // print_r($sql);
        // echo "</pre>";

        $hasil = $this->db->query("select * from mpi.t_temp_monitoring_doi_mpi where id=$id");

		if ($hasil->num_rows() > 0) {
			return $hasil->result();
		} else {
			return array();
		}

    }
    
}