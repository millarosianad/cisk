<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_dashboard_dummy extends CI_Model
{
    public function get_bulan_sekarang(){
        // $tahun_sekarang = 2022;
        $tahun_sekarang = date('Y');
        $bulan_sekarang = date('m');
        $tanggal_sekarang = date('d');
        // echo "bulan : ".$bulan_sekarang;
        // echo "tanggal : ".$tanggal_sekarang;

        if ($tanggal_sekarang <= '7') {
            // $bulan_sekarang_x = (int)12; 
            $bulan_sekarang_x = (int)$bulan_sekarang - 1; 
        }else{
            $bulan_sekarang_x = $bulan_sekarang;
        }

        if ($bulan_sekarang_x == '1') {
            $bulan = "Januari";
        }elseif ($bulan_sekarang_x == '2') {
            $bulan = "Februari";
        }elseif ($bulan_sekarang_x == '3') {
            $bulan = "Maret";
        }elseif ($bulan_sekarang_x == '4') {
            $bulan = "April";
        }elseif ($bulan_sekarang_x == '5') {
            $bulan = "Mei";
        }elseif ($bulan_sekarang_x == '6') {
            $bulan = "Juni";
        }elseif ($bulan_sekarang_x == '7') {
            $bulan = "Juli";
        }elseif ($bulan_sekarang_x == '8') {
            $bulan = "Agustus";
        }elseif ($bulan_sekarang_x == '9') {
            $bulan = "September";
        }elseif ($bulan_sekarang_x == '10') {
            $bulan = "Oktober";
        }elseif ($bulan_sekarang_x == '11') {
            $bulan = "November";
        }elseif ($bulan_sekarang_x == '12') {
            $bulan = "Desember";
        }else{
            $bulan = "Desember";
        }
        return $bulan;
        // echo "bulan_sekarang_x : ".$bulan_sekarang_x;
    }

    public function get_bulan_sebelumnya(){
        $tahun_sekarang = date('Y');
        $bulan_sekarang = date('m');
        $tanggal_sekarang = date('d');
        // echo "bulan : ".$bulan_sekarang;
        // echo "tanggal : ".$tanggal_sekarang;

        if ($tanggal_sekarang <= '7') {
            $bulan_sekarang_x = (int)$bulan_sekarang - 1; 
            $bulan_sebelumnya = (int)$bulan_sekarang_x - 1;
        }else{
            $bulan_sekarang_x = (int)$bulan_sekarang;
            $bulan_sebelumnya = (int)$bulan_sekarang_x - 1;
        }

        if ($bulan_sebelumnya == '1') {
            $bulan = "Januari";
        }elseif ($bulan_sebelumnya == '2') {
            $bulan = "Februari";
        }elseif ($bulan_sebelumnya == '3') {
            $bulan = "Maret";
        }elseif ($bulan_sebelumnya == '4') {
            $bulan = "April";
        }elseif ($bulan_sebelumnya == '5') {
            $bulan = "Mei";
        }elseif ($bulan_sebelumnya == '6') {
            $bulan = "Juni";
        }elseif ($bulan_sebelumnya == '7') {
            $bulan = "Juli";
        }elseif ($bulan_sebelumnya == '8') {
            $bulan = "Agustus";
        }elseif ($bulan_sebelumnya == '9') {
            $bulan = "September";
        }elseif ($bulan_sebelumnya == '10') {
            $bulan = "Oktober";
        }elseif ($bulan_sebelumnya == '11') {
            $bulan = "November";
        }elseif ($bulan_sebelumnya == '12') {
            $bulan = "Desember";
        }else{
            $bulan = "Desember";
        }
        return $bulan;
        // echo "bulan_sekarang_x : ".$bulan_sekarang_x;
    }

    public function get_tanggal_data($site_code = ''){
        
        $tahun_sekarang = date('Y');
        $bulan_sekarang = date('m');
        $tanggal_sekarang = date('d');

        if ($tanggal_sekarang <= '7') {
            // $bulan_sekarang_x = (int)12; 
            $bulan_sekarang_x = (int)$bulan_sekarang - 1; 
        }else{
            $bulan_sekarang_x = $bulan_sekarang;
        }

        // if ($site_code) {
        //     $params_site_code = "and concat(a.kode_comp,a.nocab) in ($site_code)";
        // }else{
        //     $params_site_code = "";
        // }

        $sql = "
        select a.kode,b.branch_name,b.nama_comp, a.tanggal_data
        FROM
        (
            select concat(a.kode_comp,a.nocab) as kode,max(CAST(a.HRDOK as int)) as tanggal_data
            from data$tahun_sekarang.fi a
            where bulan = $bulan_sekarang_x
            GROUP BY kode
        )a LEFT JOIN
        (
            select concat(a.kode_comp,a.nocab) as kode, a.branch_name,a.nama_comp, a.urutan
            from mpm.tbl_tabcomp a
            where a.`status` = 1
            GROUP BY concat(a.kode_comp,a.nocab)
        )b on a.kode = b.kode
        ORDER BY urutan
        ";

        // echo "<pre>";
        // print_r($sql);
        // echo "</pre>";

        $proses = $this->db->query($sql)->result();
        return $proses;

    }

    public function get_closing(){
        $tahun_sekarang = date('Y');
        // $tahun_sekarang = 2022;
        $bulan_sekarang = date('m');
        $tanggal_sekarang = date('d');
        // echo "bulan : ".$bulan_sekarang;
        // echo "tanggal : ".$tanggal_sekarang;

        if ($tanggal_sekarang <= '7') {
            
            $tahun_sekarang_x = date("Y", strtotime("-1 month"));
            $bulan_sekarang_x = date("m", strtotime("-1 month"));
            // $bulan_sekarang_x = 12;
        }else{
            $bulan_sekarang_x = $bulan_sekarang;
            $tahun_sekarang_x = $tahun_sekarang;
        }
        // echo "bulan_sekarang_x : ".$bulan_sekarang_x;

        
        $sql = "
        select 	if(status_closing = 1, 'Sudah Closing','Belum Closing') as status, count(status_closing) as jumlah_dp
        from 	mpm.upload
        where 	bulan = $bulan_sekarang_x and tahun = $tahun_sekarang_x and id in (
            select max(id)
            from mpm.upload
            where tahun = $tahun_sekarang_x and bulan = $bulan_sekarang_x
            GROUP BY userid
        )and userid not in ('0','289')
        GROUP BY status_closing
        ORDER BY status_closing desc
        ";
        $proses = $this->db->query($sql)->result();
        // echo "<pre>";
        // echo "<br><br><br><br>";
        // print_r($sql);
        // echo "</pre>";
        return $proses;
    }

    public function get_dp_belum_closing(){
        $tahun_sekarang = date('Y');
        // $tahun_sekarang = 2022;
        $bulan_sekarang = date('m');
        $tanggal_sekarang = date('d');
        // echo "bulan : ".$bulan_sekarang;
        // echo "tanggal : ".$tanggal_sekarang;

        if ($tanggal_sekarang <= '10') {
            // $bulan_sekarang_x = (int)12; 
            $bulan_sekarang_x = (int)$bulan_sekarang - 1; 
            // $bulan_sekarang_x = 12;
        }else{
            $bulan_sekarang_x = $bulan_sekarang;
        }

        $cek = "
            select *
            from mpm.upload a
            where tahun = $tahun_sekarang and bulan = $bulan_sekarang_x and a.status_closing = 1
            GROUP BY substring(filename,3,2), userid
        ";
        $query = $this->db->query($cek)->result();
        if ($query) {
            
            // $sql = "
            //     select a.userid, a.nocab, b.username, concat(b.username,a.nocab) as kode, c.branch_name, c.nama_comp
            //     from
            //     (
            //         select 	id ,userid, filename, tahun, bulan, status_closing,substring(filename,3,2) as nocab,lastupload
            //         from 	mpm.upload
            //         where 	bulan = $bulan_sekarang_x and tahun = $tahun_sekarang and id in (
            //             select max(id)
            //             from mpm.upload
            //             where tahun = $tahun_sekarang and bulan = $bulan_sekarang_x
            //             GROUP BY userid
            //         )and userid not in ('0','289') and status_closing = 0
            //     )a LEFT JOIN mpm.`user` b on a.userid = b.id LEFT JOIN
            //     (
            //         select concat(a.kode_comp,a.nocab) as kode, a.branch_name, a.nama_comp, a.urutan
            //         from mpm.tbl_tabcomp a
            //         where a.status = 1
            //         GROUP BY concat(a.kode_comp,a.nocab)
            //     )c on c.kode = concat(b.username,a.nocab)
            //     where b.active = 1
            //     ORDER BY c.urutan
            //     limit 6
            //     ";

                $sql = "
                select a.userid, a.nocab, b.username, concat(b.username,a.nocab) as kode, c.branch_name, c.nama_comp
                from
                (
                    select 	a.id ,userid, filename, tahun, bulan, status_closing,substring(filename,3,2) as nocab,lastupload, b.username
                    from 	mpm.upload a LEFT JOIN (
                        select a.id, a.username
                        from mpm.user a 
                    )b on a.userid = b.id
                    where 	bulan = $bulan_sekarang_x and tahun = $tahun_sekarang and a.id in (
                        select max(id)
                        from mpm.upload
                        where tahun = $tahun_sekarang and bulan = $bulan_sekarang_x
                        GROUP BY userid
                    )and userid not in ('0','289') and status_closing = 0
                )a LEFT JOIN mpm.`user` b on a.userid = b.id LEFT JOIN
                (
                    select concat(a.kode_comp,a.nocab) as kode, a.branch_name, a.nama_comp, a.urutan, a.kode_comp
                    from mpm.tbl_tabcomp a
                    where a.status = 1
                    GROUP BY concat(a.kode_comp,a.nocab)
                )c on c.kode_comp = a.username
                where b.active = 1
                ORDER BY c.urutan
                limit 6
                ";

                // echo "<pre><br><br><br>";
                // print_r($sql);
                // echo "</pre>";
                // die;

                $proses = $this->db->query($sql)->result();
                return $proses;

        }else{

            return array();
        }

        
    }

    public function get_kalender_data(){
        $tahun_sekarang = date('Y');
        $bulan_sekarang = date('m');
        $tanggal_sekarang = date('d');
        // echo "bulan : ".$bulan_sekarang;
        // echo "tanggal : ".$tanggal_sekarang;
        $sql = "
            SELECT a.nama_comp, b.tanggal, b.lastupload
            FROM
            (
                SELECT c.id, b.kode, a.branch_name, a.nama_comp, a.urutan
                FROM
                (
                    select concat(a.kode_comp,a.nocab) as kode, a.branch_name,a.nama_comp, a.urutan
                    from mpm.tbl_tabcomp a
                    where a.`status` = 1 and active = '1'
                    GROUP BY concat(a.kode_comp,a.nocab)
                )a
                INNER JOIN
                (
                    SELECT CONCAT(kode_comp,nocab) as kode, kode_comp from db_dp.t_dp
                    WHERE tahun = $tahun_sekarang and`status` = 1
                )b on a.kode = b.kode
                INNER JOIN
                (
                    SELECT * FROM mpm.`user`
                )c on b.kode_comp = c.username
            )a
            LEFT OUTER JOIN
            (
                SELECT * FROM mpm.upload
                WHERE tahun = $tahun_sekarang and bulan = $bulan_sekarang
            )b on a.id = b.userid
            order by b.lastupload desc
        ";

        $proses = $this->db->query($sql)->result();
        return $proses;

    }

    public function get_kode_alamat(){
        $id = $this->session->userdata('id');
        if ($id == '297' | $id == '588' || $id == '442') {
            $params = "b.kode_alamat is not null";
        }else{
            $params = "a.id = $id";
        }
        
        $sql = "
            select b.kode_alamat
            from    mpm.user a LEFT JOIN mpm.t_alamat b
                on a.username = b.username
            where $params
        ";       
        $proses = $this->db->query($sql)->result();
        if ($proses) {
            return $proses;
        }else{
            return array();
        }
    }

    public function get_upload_tercepat($kode_alamat)
    {
        $tahun = date('Y');
        $bulan = date('m');
        $tanggal = date('d');
        // echo "bulan : ".$bulan_sekarang;
        
        if($kode_alamat == "''"){
            $kodex ="";
        }else{
            $kodex ="where a.kode in ($kode_alamat)";
        }

        $sql = "
            SELECT a.kode, concat(a.hr,'-',a.bln,'-',a.thn) as tgl, b.branch_name, b.nama_comp
            FROM
            (
                SELECT CONCAT(KODE_COMP, NOCAB) as kode, MAX(HRDOK) as hr, MAX(BLNDOK) as bln, MAX(THNDOK) as thn FROM data$tahun.fi
                WHERE BLNDOK = $bulan and THNDOK = $tahun
                GROUP BY kode
            )a LEFT JOIN 
            (
                SELECT b.kode, a.branch_name, a.nama_comp, a.urutan
                FROM
                (
                        select concat(a.kode_comp,a.nocab) as kode, a.branch_name,a.nama_comp, a.urutan
                        from mpm.tbl_tabcomp a
                        where a.`status` = 1 
                        GROUP BY concat(a.kode_comp,a.nocab)
                )a
                INNER JOIN
                (
                        SELECT CONCAT(kode_comp,nocab) as kode, kode_comp from db_dp.t_dp
                        WHERE tahun = $tahun and`status` = 1
                )b on a.kode = b.kode
            )b on a.kode = b.kode
            $kodex
            order by hr desc
            limit 6
            ";
        echo "<pre>";
        echo "<br><br><br><br>";
        print_r($sql);
        echo "</pre>";
        $proses = $this->db->query($sql)->result();
        return $proses;
    }

    public function get_upload_terlama($kode_alamat)
    {
        $tahun = date('Y');
        $bulan = date('m');
        $tanggal = date('d');

        if($kode_alamat == "''"){
            $kodex ="";
        }else{
            $kodex ="where a.kode in ($kode_alamat)";
        }
        // echo "bulan : ".$bulan_sekarang;
        // echo "tanggal : ".$tanggal_sekarang;
        // $sql = "
        //     SELECT a.kode, a.nama_comp, concat(b.tgl,'-',b.bulan,'-',b.tahun) as tgl, b.tgl_upload
        //     FROM
        //     (
        //         SELECT c.id, b.kode, a.branch_name, a.nama_comp, a.urutan
        //         FROM
        //         (
        //             select concat(a.kode_comp,a.nocab) as kode, a.branch_name,a.nama_comp, a.urutan
        //             from mpm.tbl_tabcomp a
        //             where a.`status` = 1 and active = '1'
        //             GROUP BY concat(a.kode_comp,a.nocab)
        //         )a
        //         INNER JOIN
        //         (
        //             SELECT CONCAT(kode_comp,nocab) as kode, kode_comp from db_dp.t_dp
        //             WHERE tahun = $tahun and`status` = 1
        //         )b on a.kode = b.kode
        //         INNER JOIN
        //         (
        //             SELECT * FROM mpm.`user`
        //         )c on b.kode_comp = c.username
        //     )a
        //     INNER JOIN
        //     (
        //         SELECT a.* 
        //         FROM
        //         (
        //         SELECT *, MAX(tanggal) as tgl, MAX(lastupload) as tgl_upload FROM mpm.upload
        //         WHERE tahun = $tahun and bulan = $bulan
        //         GROUP BY userid
        //         )a
        //         WHERE a.tgl < $tanggal
        //     )b on a.id = b.userid
        //     $kodex
        //     ORDER BY tgl asc
        //     limit 7
        // ";

        $sql = "
            SELECT a.kode, concat(a.hr,'-',a.bln,'-',a.thn) as tgl, b.branch_name, b.nama_comp
            FROM
            (
                SELECT CONCAT(KODE_COMP, NOCAB) as kode, MAX(HRDOK) as hr, MAX(BLNDOK) as bln, MAX(THNDOK) as thn FROM data$tahun.fi
                WHERE BLNDOK = $bulan and THNDOK = $tahun
                GROUP BY kode
            )a LEFT JOIN 
            (
                SELECT b.kode, a.branch_name, a.nama_comp, a.urutan
                FROM
                (
                        select concat(a.kode_comp,a.nocab) as kode, a.branch_name,a.nama_comp, a.urutan
                        from mpm.tbl_tabcomp a
                        where a.`status` = 1
                        GROUP BY concat(a.kode_comp,a.nocab)
                )a
                INNER JOIN
                (
                        SELECT CONCAT(kode_comp,nocab) as kode, kode_comp from db_dp.t_dp
                        WHERE tahun = $tahun and`status` = 1
                )b on a.kode = b.kode
            )b on a.kode = b.kode
            $kodex
            order by hr asc
            limit 6
            ";

        $proses = $this->db->query($sql)->result();
        return $proses;
    }

    public function get_noted()
    {
        $sql = "
            Select * from db_temp.t_temp_dashboard
            order by id desc
            ";
        return $this->db->query($sql);
    }

    public function sales_year_principal($kode_alamat)
    {
        $id = $this->session->userdata('id');
        $supp = $this->session->userdata('supp');

        
        // $query = $this->db->query("select username, wilayah_nocab from mpm.user where id = '$id'")->row();
        // $username = $query->username;
        // $wilayah_nocab = $query->wilayah_nocab;
        // $count = $this->db->query("select * from mpm.tbl_tabcomp where kode_comp = '$username'");

        // if ($wilayah_nocab == null) {
        //     $nocab = '';
        //     $nocabx = '';
        // }else{
        //     $nocab = "and nocab in ($wilayah_nocab)";
        //     $nocabx= "where nocab in ($wilayah_nocab)";
        // }
        // var_dump($count);die;

        if($kode_alamat == "''"){
            $kode ="";
            $kodex ="";
        }else{
            $kode ="where kode in ($kode_alamat)";
            $kodex ="and kode in ($kode_alamat)";
        }
        
        if($supp == '000' || $supp == ''){
            $sql = "
            select supp, namasupp, sum(tot1_1 + tot1_2 + tot1_3 + tot1_4 + tot1_5 + tot1_6 +  
                    tot1_7 + tot1_8 + tot1_9 + tot1_10 + tot1_11 + tot1_12) as tot1,
                    sum(tot2_1 + tot2_2 + tot2_3 + tot2_4 + tot2_5 + tot2_6 +  
                    tot2_7 + tot2_8 + tot2_9 + tot2_10 + tot2_11 + tot2_12) as tot2 
            from db_temp.t_temp_dashboard_sales
            $kode
            GROUP BY supp
            ";
        }else{
            $sql = "
            select supp, namasupp, sum(tot1_1 + tot1_2 + tot1_3 + tot1_4 + tot1_5 + tot1_6 +  
                    tot1_7 + tot1_8 + tot1_9 + tot1_10 + tot1_11 + tot1_12) as tot1,
                    sum(tot2_1 + tot2_2 + tot2_3 + tot2_4 + tot2_5 + tot2_6 +  
                    tot2_7 + tot2_8 + tot2_9 + tot2_10 + tot2_11 + tot2_12) as tot2 
            from db_temp.t_temp_dashboard_sales
            where supp = '$supp' $kodex
            GROUP BY supp
            ";
                }
        return $this->db->query($sql)->result();
    }

    public function sales_month_principal($kode_alamat)
    {
        $id = $this->session->userdata('id');
        $supp = $this->session->userdata('supp');

        
        // $query = $this->db->query("select username, wilayah_nocab from mpm.user where id = '$id'")->row();
        // $username = $query->username;
        // $wilayah_nocab = $query->wilayah_nocab;
        // $count = $this->db->query("select * from mpm.tbl_tabcomp where kode_comp = '$username'");

        // if ($wilayah_nocab == null) {
        //     $nocab = '';
        //     $nocabx = '';
        // }else{
        //     $nocab = "and nocab in ($wilayah_nocab)";
        //     $nocabx= "where nocab in ($wilayah_nocab)";
        // }
        // var_dump($thn_lalu);die;
        
        if($kode_alamat == "''"){
            $kode ="";
            $kodex ="";
        }else{
            $kode ="where kode in ($kode_alamat)";
            $kodex ="and kode in ($kode_alamat)";
        }

        if($supp == '000' || $supp ==''){
            $sql="
                select namasupp, sum(tot1_1) as tot1_1 ,sum(tot1_2) as tot1_2 ,sum(tot1_3) as tot1_3 ,sum(tot1_4) as tot1_4 ,sum(tot1_5) as tot1_5 ,
                        sum(tot1_6) as tot1_6 ,sum(tot1_7) as tot1_7 ,sum(tot1_8) as tot1_8 ,sum(tot1_9) as tot1_9 ,sum(tot1_10) as tot1_10 ,sum(tot1_11) as tot1_11 ,
                        sum(tot1_12) as tot1_12 ,sum(tot2_1) as tot2_1 ,sum(tot2_2) as tot2_2 ,sum(tot2_3) as tot2_3 ,sum(tot2_4) as tot2_4 ,sum(tot2_5) as tot2_5 ,
                        sum(tot2_6) as tot2_6 ,sum(tot2_7) as tot2_7 ,sum(tot2_8) as tot2_8 ,sum(tot2_9) as tot2_9 ,sum(tot2_10) as tot2_10 ,sum(tot2_11) as tot2_11 ,
                        sum(tot2_12) as tot2_12 
                from db_temp.t_temp_dashboard_sales
                $kode
                group by supp
            ";
        }else{
            $sql="
                select namasupp, sum(tot1_1) as tot1_1 ,sum(tot1_2) as tot1_2 ,sum(tot1_3) as tot1_3 ,sum(tot1_4) as tot1_4 ,sum(tot1_5) as tot1_5 ,
                        sum(tot1_6) as tot1_6 ,sum(tot1_7) as tot1_7 ,sum(tot1_8) as tot1_8 ,sum(tot1_9) as tot1_9 ,sum(tot1_10) as tot1_10 ,sum(tot1_11) as tot1_11 ,
                        sum(tot1_12) as tot1_12 ,sum(tot2_1) as tot2_1 ,sum(tot2_2) as tot2_2 ,sum(tot2_3) as tot2_3 ,sum(tot2_4) as tot2_4 ,sum(tot2_5) as tot2_5 ,
                        sum(tot2_6) as tot2_6 ,sum(tot2_7) as tot2_7 ,sum(tot2_8) as tot2_8 ,sum(tot2_9) as tot2_9 ,sum(tot2_10) as tot2_10 ,sum(tot2_11) as tot2_11 ,
                        sum(tot2_12) as tot2_12 
                from db_temp.t_temp_dashboard_sales
                where supp = '$supp' $kodex
                group by supp 
                ";
        }

        return $this->db->query($sql)->result();
    }
    
    public function stock_doi($kode_alamat)
    {
        $supp = $this->session->userdata('supp');

        if($kode_alamat == "''"){
            $kode ="";
            $kodex ="";
        }else{
            $kode ="where kode in ($kode_alamat)";
            $kodex ="and kode in ($kode_alamat)";
        }
        if($supp == '000' || $supp ==''){
            $sql = "
                select b.namasupp, a.nama_comp, if(a.doi is null,0,a.doi) as doi from db_temp.t_temp_dashboard_stock a
                left join mpm.tabsupp b on a.supp = b.supp 
                $kode
            ";
        }else{
            $sql = "
                select b.namasupp, a.nama_comp, if(a.doi is null,0,a.doi) as doi from db_temp.t_temp_dashboard_stock a
                left join mpm.tabsupp b on a.supp = b.supp 
                where supp = $supp $kodex
            ";
        }
        return $this->db->query($sql);
    }
}