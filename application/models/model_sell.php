<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_sell extends CI_Model 
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

    public function list_dp()
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
            else if($userid == '78')//KRW
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
                //return $this->db->query('select distinct naper, nama_comp from mpm.tabcomp where  naper!=99 and kode_comp != "XXX" order by nama_comp limit 1');

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

                return $this->db->query(                    
                    'select nocab as naper, kode_comp,nama_comp
                    from mpm.tbl_tabcomp_new
                    where `status` = 1 '.$daftar_dp.'
                    GROUP BY kode_comp
                    '
                );


            }
        }
    }
    
   public function sell_out_dp($data){

        $user=$this->session->userdata('username');
        if ( function_exists( 'date_default_timezone_set' ) )
        date_default_timezone_set('Asia/Jakarta');        

        /* ---------DEFINISI VARIABEL----------------- */
        
            $tgl_created='"'.date('Y-m-d H:i:s').'"';        
            $id=$this->session->userdata('id');
            $uv = $data['uv'];
                switch($uv)
                {
                    case 0:
                        $unit='banyak';
                        break;
                    case 1:
                        $unit='tot1';
                        break;
                }
            $year = $data['year'];
            $naper = $data['dp'];
            $supp = $data['supp'];
            $group = $data['group'];
            $kode_group = $data['kode_group'];
            /*
            echo "<pre>";
            echo "year: ".$year;
            echo "naper : ".$naper;
            echo "supp : ".$supp;
            echo "group : ".$group;
            echo "kode_group : ".$kode_group;
            echo "</pre>";
            */
            $query = "delete from mpm.sodp_new where id = ".$id."";
            $sql = $this->db->query($query);
            
            if($supp=='000' || $supp=='XXX'){
                $wheresupp='';
            }else if($supp=='001'){
                $wheresupp='and (kodeprod like "60%" or kodeprod like "01%" or kodeprod like "50%" or kodeprod like "70%") and kodeprod not in (select kodeprod from mpm.tabprod_unilever)';
            }else if($supp=='005'){
                $wheresupp='and kodeprod like "06%" ';
            }else if($supp=='002'){
                $wheresupp='and kodeprod like "02%" ';
            }else if($supp=='004'){
                $wheresupp='and kodeprod like "04%" ';
            }else if($supp=='007'){
                $wheresupp='and kodeprod like "07%" ';
            }else if($supp=='009'){//unilever
                $wheresupp='and kodeprod like "20%" or kodeprod like "21%" or kodeprod like "62%" or kodeprod like "67%"  ';
            }else{
                $wheresupp="and kodeprod like '".substr($supp,-2)."%'";
            }

            if($group=='' || $group == '0'){
                $wheregroup = "";
            }else{
                $wheregroup = "where b.grup = '$kode_group'";
            }


            $query="
                    select a.kodeprod,a.namaprod,0 as rata,
                    b1,b2,b3,b4,b5,b6,b7,b8,b9,b10,b11,b12,".$id.", ".$tgl_created."
                    from(
                        select a.kodeprod,namaprod,
                        sum(if(blndok=1,unit,0)) as b1,sum(if(blndok=2,unit,0)) as b2,
                        sum(if(blndok=3,unit,0)) as b3,sum(if(blndok=4,unit,0)) as b4,
                        sum(if(blndok=5,unit,0)) as b5,sum(if(blndok=6,unit,0)) as b6,
                        sum(if(blndok=7,unit,0)) as b7,sum(if(blndok=8,unit,0)) as b8,
                        sum(if(blndok=9,unit,0)) as b9,sum(if(blndok=10,unit,0)) as b10,
                        sum(if(blndok=11,unit,0)) as b11,sum(if(blndok=12,unit,0)) as b12
                        from
                        (
                            select  kodeprod,sum(".$unit.") as unit,blndok 
                            from    data".$year.".fi 
                            where   nodokjdi<>'XXXXXX' and concat(kode_comp,nocab) in (".'"'.$naper.'"'.")
                                    ".$wheresupp." 
                            group by kodeprod, blndok                          
                            union all
                            select  kodeprod,sum(".$unit.") as unit,blndok 
                            from    data".$year.".ri 
                            where   nodokjdi<>'XXXXXX' and concat(kode_comp,nocab) in (".'"'.$naper.'"'.")
                                    ".$wheresupp." 
                            group by kodeprod, blndok
                        )a INNER JOIN mpm.tabprod b on a.kodeprod = b.KODEPROD
                        $wheregroup
                        group by kodeprod
                    )a
                    
                    ";
        /* PROSES */

        $gabung = "insert into mpm.sodp_new ".$query;
        /*
        echo "<pre>";
        print_r($query);
        echo "</pre>";
        */
        $sql = $this->db->query($gabung);

        /* PROSES TAMPIL KE WEBSITE */
            //$this->db->join('tbl_tabcomp_new', 'tbl_tabcomp_new.naper = omzet_new.naper','inner');
            //$this->db->where("status = 1");
            //$this->db->order_by('tbl_tabcomp_new.urutan','asc');
            $this->db->order_by('kodeprod','asc');
            $this->db->where("id = ".'"'.$id.'"');
            $hasil = $this->db->get('sodp_new');       
            if ($hasil->num_rows() > 0) 
            {
                return $hasil->result();
            } else {
                return array();
            }
        /* END PROSES TAMPIL KE WEBSITE */

   }


}