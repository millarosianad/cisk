<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_kalender_data extends CI_Model 
{    
    public function view_kalender_data_closing(){

        $id=$this->session->userdata('id');
        
        $sql = "
            select  a.kode_comp, nama_comp, lastupload, sum(maks) as tgl_data_terakhir, filename, sum(omzet_all) as omzet_all, status_closing, userid
            FROM
            (
                select  kode_comp, lastupload, '' as maks, filename, '' as omzet_all, status_closing, userid
                FROM
                (   
                    select  a.id as id, b.username as kode_comp, b.company as company, /*c.nama_comp,*/
                            a.userid, 
                            a.filename, 
                            a.lastupload, 
                            a.tanggal, 
                            a.bulan, 
                            a.tahun, 
                            a.status_closing,
                            a.`status`
                    from    mpm.upload a 
                            LEFT JOIN mpm.`user` b
                                on a.userid = b.id                                          
                    where bulan = 05 and flag <> 2
                    ORDER BY a.id desc, concat(tahun,bulan) desc
                )a 
                union ALL

                select      a.kode_comp,'', MAX(hrdok) as maks, '','','',''
                from        data2017.fi a 
                where       bulan = 05
                GROUP BY    kode_comp

                union ALL

                select  kode_comp, '', '', '', sum(omzet) as omzet_all, '',''
                FROM
                (
                        select  kode_comp, SUM(tot1) as omzet
                        FROM    data2017.fi
                        where   bulan = 05
                        GROUP BY kode_comp

                        union all

                        select  kode_comp, SUM(tot1) as omzet
                        FROM    data2017.ri
                        where   bulan = 05
                        GROUP BY kode_comp
                )a GROUP BY kode_comp
            )a LEFT JOIN
            (
                select  kode_comp, nama_comp, urutan
                from        mpm.tbl_tabcomp
                where `status` = 1
                GROUP BY kode_comp
            )b on a.kode_comp = b.kode_comp
            where a.kode_comp is not null
            GROUP BY a.kode_comp
            ORDER BY urutan asc


        ";

        //echo "<pre>";
        //print_r($sql);
        //echo "</pre>";

        $hasil = $this->db->query($sql);

        if ($hasil->num_rows() > 0) 
        {
            return $hasil->result();
        } else {
            return array();
        }

    }    

    public function view_kalender_data($datacontroller){

        $id=$this->session->userdata('id');
        $jenis_data = $datacontroller['jenis_data'];
        $tahun = $datacontroller['tahun'];
        
        /*echo "jenis_data mod : ".$jenis_data."<br>";
        echo "tahun mod: ".$tahun;        
        */
        if ($jenis_data == '1') {
            //jenis data text mpm

            $sql = "                
                select  '' as kode_comp, nama_afiliasi as nama_comp, 
                        sum(jan) as januari,
                        sum(feb) as februari,
                        sum(mar) as maret,
                        sum(apr) as april,
                        sum(mei) as mei,
                        sum(jun) as juni,
                        sum(jul) as juli,
                        sum(agus) as agustus,
                        sum(sep) as september,
                        sum(okt) as oktober,
                        sum(nov) as november,
                        sum(des) as desember
                FROM
                (
                    select      nama_afiliasi, 
                                    if(bulan = 1, hrdok, '') as jan,
                                    if(bulan = 2, hrdok, '') as feb,
                                    if(bulan = 3, hrdok, '') as mar,
                                    if(bulan = 4, hrdok, '') as apr,
                                    if(bulan = 5, hrdok, '') as mei,
                                    if(bulan = 6, hrdok, '') as jun,
                                    if(bulan = 7, hrdok, '') as jul,
                                    if(bulan = 8, hrdok, '') as agus,
                                    if(bulan = 9, hrdok, '') as sep,
                                    if(bulan = 10, hrdok, '') as okt,
                                    if(bulan = 11, hrdok, '') as nov,
                                    if(bulan = 12, hrdok, '') as des
                    FROM
                    (
                        select  nama_afiliasi, max(tanggal) as hrdok, bulan
                        from
                        (
                            SELECT  nama_afiliasi, tgl_data, SUBSTR(tgl_data,1,4) as tahun,
                                    SUBSTR(tgl_data,6,2) as bulan, SUBSTR(tgl_data,9,2) as tanggal
                            FROM
                            (
                                select  b.nama_afiliasi, 
                                        a.tgl_data
                                from    mpm.tbl_status_proses_data a left JOIN 
                                        mpm.tbl_afiliasi b
                                            on a.id_afiliasi = b.id_afiliasi
                                where   tahun = $tahun
                            )a 
                            where nama_afiliasi is not null
                        )a GROUP BY nama_afiliasi, bulan
                    )a
                )a GROUP BY a.nama_afiliasi
            ";

        }else{
            //jenis data web
                $sql = "
                        select  a.kode_comp,b.nama_comp, 
                            sum(jan) as januari,
                            sum(feb) as februari,
                            sum(mar) as maret,
                            sum(apr) as april,
                            sum(mei) as mei,
                            sum(jun) as juni,
                            sum(jul) as juli,
                            sum(agus) as agustus,
                            sum(sep) as september,
                            sum(okt) as oktober,
                            sum(nov) as november,
                            sum(des) as desember
                        FROM
                        (
                        select  kode_comp, if(bulan = 1, hrdok, '') as jan,
                                    if(bulan = 2, hrdok, '') as feb,
                                    if(bulan = 3, hrdok, '') as mar,
                                    if(bulan = 4, hrdok, '') as apr,
                                    if(bulan = 5, hrdok, '') as mei,
                                    if(bulan = 6, hrdok, '') as jun,
                                    if(bulan = 7, hrdok, '') as jul,
                                    if(bulan = 8, hrdok, '') as agus,
                                    if(bulan = 9, hrdok, '') as sep,
                                    if(bulan = 10, hrdok, '') as okt,
                                    if(bulan = 11, hrdok, '') as nov,
                                    if(bulan = 12, hrdok, '') as des

                        FROM
                        (
                            select kode_comp, max(hrdok) as hrdok, BULAN
                            from data".$tahun.".fi
                            GROUP BY kode_comp, bulan
                        )a 
                        )a LEFT JOIN
                        (
                            select kode_comp, nama_comp, urutan
                            from mpm.tbl_tabcomp
                            where `status` = 1
                            GROUP BY kode_comp
                        )b on a.kode_comp = b.kode_comp
                        where a.kode_comp is not null
                        GROUP BY a.kode_comp
                        ORDER BY b.urutan asc
            ";
        }
             
        /*
        echo "<pre>";
        print_r($sql);
        echo "</pre>";
        */
        $hasil = $this->db->query($sql);

        if ($hasil->num_rows() > 0) 
        {
            return $hasil->result();
        } else {
            return array();
        }
        

    } 

    public function view_history_data($datacontroller){

        $userid = $datacontroller['userid'];
        //echo "<br>model :".$userid;
        $id=$this->session->userdata('id');
        
        $sql = "
            select  a.id, c.nama_comp, a.userid, a.lastupload, a.filename, a.flag, a.tanggal, a.bulan, a.tahun, a.`status`, a.status_closing, b.username, a.omzet
            from    mpm.upload a LEFT JOIN mpm.`user` b
                    on a.userid = b.id
                LEFT JOIN 
                (
                    SELECT kode_comp, nama_comp, urutan
                    from mpm.tbl_tabcomp 
                    where `status` = 1
                    GROUP BY kode_comp
                )c on b.username = c.kode_comp
            where username = ".'"'.$userid.'"'."
            order by a.id desc
            limit 5000
        ";
        /*
        echo "<pre>";
        print_r($sql);
        echo "</pre>";
        */
        $hasil = $this->db->query($sql);

        if ($hasil->num_rows() > 0) 
        {
            return $hasil->result();
        } else {
            //return array();
        }
    } 

}