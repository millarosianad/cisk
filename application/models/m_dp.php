<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_dp extends CI_Model {

    public function get_namacomp($key='')
    {
        $userid=$this->session->userdata('id');
        $nocab=$this->session->userdata('nocab');
        $year = $key['year'];

        $this->db->where('id = '.'"'.$userid.'"');
        $query = $this->db->get('mpm.user');
        foreach ($query->result() as $row) {
            $dp = $row->wilayah_nocab;
        }

        if ($dp == NULL || $dp == '' )
        {
            $daftar_dp = '';
        } else {
            $daftar_dp = 'and nocab in ('.$dp.')';
        }

        $sql=
        "
        select  concat(a.kode_comp, a.nocab) as kode, b.nama_comp
        from    db_dp.t_dp a INNER JOIN 
        (
            select  naper, nocab, kode_comp, nama_comp
            FROM    mpm.tbl_tabcomp
            WHERE   status = 1 $daftar_dp
            order by nama_comp
        )b on concat(a.kode_comp, a.nocab) = concat(b.kode_comp, b.nocab)

        where a.tahun = $year and a.status = 1
        order by nama_comp
        ";

        //$query=$this->db->query($sql,array($key));
        $query=$this->db->query($sql);
        return $query;
    }

    public function get_namacomp_backup($key='')
    {
        $userid=$this->session->userdata('id');
        $nocab=$this->session->userdata('nocab');
        $year = $key['year'];

        if ($nocab!='')
        {
            $sql="
            select  concat(a.kode_comp, a.nocab)kode, b.nama_comp as nama_comp
            FROM
            (
                    select  kode_comp, nocab
                    from        data$year.fi  
                    GROUP BY kode_comp
            )a INNER JOIN 
            (
                select  naper, nocab, kode_comp, nama_comp
                FROM        mpm.tbl_tabcomp
                WHERE       status = 1 and nocab in ('$nocab')
                order by nama_comp
            )b on concat(a.kode_comp, a.nocab) = concat(b.kode_comp, b.nocab)
            ORDER BY b.nama_comp";
        }else{
                $this->db->where('id = '.'"'.$userid.'"');
                $query = $this->db->get('mpm.user');
                foreach ($query->result() as $row) {
                    $dp = $row->wilayah_nocab;
                }

                if ($dp == NULL || $dp == '' )
                {
                    $daftar_dp = '';
                } else {
                    $daftar_dp = 'and nocab in ('.$dp.')';
                }

                $sql=
                "
                select  concat(a.kode_comp, a.nocab) as kode, b.nama_comp
                from    db_dp.t_dp a INNER JOIN 
                (
                    select  naper, nocab, kode_comp, nama_comp
                    FROM    mpm.tbl_tabcomp
                    WHERE   status = 1 $daftar_dp
                    order by nama_comp
                )b on concat(a.kode_comp, a.nocab) = concat(b.kode_comp, b.nocab)

                where a.tahun = $year
                order by nama_comp
                ";
        }

        //$query=$this->db->query($sql,array($key));
        $query=$this->db->query($sql);
        return $query;
    }

    public function getSuppbyid()
    {
        $supp=$this->session->userdata('supp');
        if($supp=='000')
        {
            return $this->db->query('select supp,namasupp from mpm.tabsupp where active = 1');
        }
        else
        {
            return $this->db->query('select supp,namasupp from mpm.tabsupp where active = 1 and supp='.$supp);
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
                    from mpm.tbl_tabcomp
                    where `status` = 1 '.$daftar_dp.'
                    GROUP BY kode_comp
                    '
                );


            }
        }
    }

    public function get_group($key='')
    {
        $userid=$this->session->userdata('id');
        $nocab=$this->session->userdata('nocab');
        $kode_supp = $key['kode_supp'];
        $sql="
        select id_group, nama_group as nama_group, kode_group
		FROM mpm.tbl_group
		where kode_supp ='$kode_supp'
        ";
       
        $query=$this->db->query($sql);
        return $query;

    }

    public function get_kode_group($data)
    {
        $group = $data['group'];
        $this->db->where('id_group = '.'"'.$group.'"');
        $query = $this->db->get('mpm.tbl_group');
        foreach ($query->result() as $row) {
            $kode_group = $row->kode_group;
            return $kode_group;
        }
        
    }
}