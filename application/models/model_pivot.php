<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_pivot extends CI_Model {

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
                return $this->db->query('select distinct naper, nama_comp from mpm.tabcomp where  naper!=99 and kode_comp != "XXX" order by nama_comp  ');
            }
        }
    }

    public function list_kategori_pivot()
    {
        $userid=$this->session->userdata('id');
        $nocab=$this->session->userdata('nocab');
        /*
        echo "userid : ".$userid."<br>"; 
        echo "nocab : ".$nocab."<br>"; 
        echo "nocab : ".$nocab."<br>"; 
        */
        $this->db->where('id_kategori in (1,2,4)');
        $query = $this->db->get('mpm.tbl_kategori_pivot');
        return $query;
    }

    public function list_wilayah_pivot()
    {
        $userid=$this->session->userdata('id');
        $nocab=$this->session->userdata('nocab');
        /*
        echo "userid : ".$userid."<br>"; 
        echo "nocab : ".$nocab."<br>"; 
        echo "nocab : ".$nocab."<br>"; 
        */

        /*cek hak akses wilayah pivot*/
            $this->db->where('id = '.'"'.$userid.'"');
            $query = $this->db->get('mpm.user');
            foreach ($query->result() as $row) {
                $wilayah_pivot = $row->wilayah_pivot;
                //echo "wilayah pivot : ".$wilayah_pivot."<br>";
            }
        /*end cek hak akses wilayah pivot*/

        if ($wilayah_pivot == NULL || $wilayah_pivot == '' || $wilayah_pivot == 0) {
            $wilayah_pivot_hasil = '1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16';
        } else {
            $wilayah_pivot_hasil = $wilayah_pivot;
        }
        

        $this->db->where('id_wilayah in ( '.$wilayah_pivot_hasil.')');
        $this->db->order_by("id_wilayah", "asc"); 
        $query2 = $this->db->get('mpm.tbl_wilayah_pivot');
        return $query2;
    }

    public function pivot_dp($dataSegment)
    {   
        $tahun = $dataSegment['year'];
        $wilayah = $dataSegment['wilayah'];
        $kategori = $dataSegment['kategori'];
        /*
        echo "model tahun : ".$tahun."<br>";
        echo "model wilayah : ".$wilayah."<br>";
        echo "model kategori : ".$kategori."<br>";
        */
        if ($kategori == '1') //jika pilih kategori DP
        {

            /* cek tahun yg dipilih, mulai 2017 maka yg di gunakan adalah kolom WILAYAH_2017, dibawah 2017 yg digunakan adlh kolom WILAYAH */

           if ($tahun == '2010' || $tahun == '2011' || $tahun == '2012' || $tahun == '2013' || $tahun == '2014' || $tahun == '2015' || $tahun == '2016') 
            {
                $kondisi=$wilayah>0?" where bagian =".$wilayah:"";
                switch($wilayah)
                {
                    case '0':$kondisi='';break;
                    case '1':
                    case '2':
                    case '3':
                    case '4':
                    case '5':
                    case '6':$kondisi="where bagian=".$wilayah;break;    
                    case '7':$kondisi="where wilayah in (1)";break;
                    case '8':$kondisi="where wilayah in (2)";break;
                    case '9':$kondisi="where custom_bagian in (4,5,6)";break;
                    case '10':$kondisi="where b.nocab = 89";break;
                       
                }
            } 
            else 
            {

                $kondisi=$wilayah>0?" where bagian =".$wilayah:"";
                switch($wilayah)
                {
                    case '0':$kondisi='';break;
                    case '1':
                    case '2':
                    case '3':
                    case '4':
                    case '5':
                    case '6':$kondisi="where bagian=".$wilayah;break;    
                    case '7':$kondisi="where wilayah_2017 in (1)";break;
                    case '8':$kondisi="where wilayah_2017 in (2)";break;
                    case '9':$kondisi="where custom_bagian in (4,5,6)";break;
                    case '10':$kondisi="where b.nocab = 89";break;
                       
                }

            }
            /*
            echo "<pre>";
            print_r($kondisi);
            echo "</pre>";
            */
             $sql="

             select format(target,2)target,
                    namaprod,namasupp,nama_comp, grupprod,ot,a.supp,
                    sum(unit) as unit,sum(value) as value,a.blndok
            from 
            (
                select  nocab,case when left(kodeprod,2)in('01',50,60,70) then '001'
                        when left(kodeprod,2)='06' then '005'
                        else concat('0',left(kodeprod,2)) end as supp,kodeprod,count(distinct kode_lang) ot,
                        sum(banyak) unit, 
                        sum(tot1) value, 
                        blndok
                from    data".$tahun.".fi
                group by kodeprod,blndok,nocab,supp
                
                union all 
                
                select  nocab,case when left(kodeprod,2)in('01',50,60,70) then '001'
                        when left(kodeprod,2)='06' then '005'
                        else concat('0',left(kodeprod,2)) end as supp,kodeprod,0 ot,
                        sum(banyak) unit, 
                        sum(tot1) value, 
                        blndok 
                from    data".$tahun.".ri
                group by kodeprod,blndok,nocab,supp
           )a 
                   inner join mpm.tabcomp b using(nocab) 
                   left join mpm.tabprod c using(kodeprod)
                   left join mpm.tabsupp d on a.supp=d.supp
                   left join mpm.target2 e on a.nocab=e.nocab and a.blndok=e.bulan and a.supp = e.supp
                    ".$kondisi." 
                  
                   group by namaprod,nama_comp,c.grupprod,unit,value,blndok
           "; 
            /*
                    ".$kondisi." 
                   where nama_comp ='tegal'
                   group by namaprod,nama_comp,c.grupprod,unit,value,month
            */
            
            echo "<pre><br>";
            print_r($sql);
            echo "</pre>";
            
            $query=$this->db->query($sql);
            return json_encode($query->result());
            
        }elseif ($kategori == '2') //jika pilih kategori BSP
        {
            $kondisi=$wilayah>0?" where bagian =".$wilayah:"";
                switch($wilayah)
                {
                    case '0':$kondisi='';break;
                    case '1':
                    case '2':
                    case '3':
                    case '4':
                    case '5':
                    case '6':    
                        $kondisi="where bagian=".$wilayah;break;
                    case '7':$kondisi="where wilayah in (1)";break;
                    case '8':$kondisi="where wilayah in (2)";break;
                }
                
                 $tahun=$this->input->post('year');
                 /*
                 $sql="select   a.nama_comp cabang,
                                nama_type tipe,
                                deskripsi produk,
                                sum(banyak/isisatuan) unit,
                                sum(jumlah) value,
                                substr(tgldokjdi,6,2) bulan
                            from 
                                bsp.bspsales".$tahun." a
                                inner join  bsp.tabprod b 
                                        on a.deskripsi=b.namaprod
                                inner join bsp.tabcomp c
                                        on a.kode_comp = c.kode_comp
                                ".$kondisi." 
                                group by cabang,tipe,produk,bulan";
                                */
                
                $sql = "
                            select  cabang,
                                    tipe,
                                    produk,
                                    unit,
                                    value,
                                    bulan
                            FROM
                            (
                                    select  kode_comp,
                                                    a.nama_comp cabang,
                                                    nama_type tipe,
                                                    deskripsi produk,
                                                    sum(banyak/isisatuan) unit,
                                                    sum(jumlah) value,
                                                    substr(tgldokjdi,6,2) bulan
                                    from        bsp.bspsales".$tahun." a left join  bsp.tabprod b 
                                                            on a.deskripsi=b.namaprod
                                                    GROUP BY cabang, tipe, produk, bulan
                            )a INNER JOIN 
                            (
                                    select  kode_comp,bagian
                                    from    bsp.tabcomp
                            ) b on a.kode_comp = b.kode_comp
                            ".$kondisi."
                            ORDER BY cabang, tipe, produk, bulan
                ";
                /*
                echo "<pre>";
                print_r($sql);
                echo "</pre>";
                */
                 $query=$this->db->query($sql);
                 return json_encode($query->result()); 
        } 
        else {
            # code...
        }
        

        
    }
}

/* End of file model_outlet.php */
/* Location: ./application/models/model_outlet.php */