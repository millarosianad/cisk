<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_monitor extends CI_Model 
{
    public function get_summary(){
        
    }

    public function get_kodeprod_by_group($group)
    {
        if ($group == '') {
            $where = "where supp <>'BSP'";
        } else {
            $where = "where a.grup = '$group' and supp <> 'BSP'";
        }

        $sql_kodeprod = "
            select kodeprod
            from mpm.tabprod a
            $where
        ";

        $proses_kodeprod = $this->db->query($sql_kodeprod)->result_array();
        foreach ($proses_kodeprod as $a) {
            $kodeprodx[] = $a['kodeprod'];
        }

        $kodeprod = implode(', ', $kodeprodx);
        return $kodeprod;
    }

    public function get_kode_type_by_sektor($sektor)
    {
        if ($sektor == '') {
            $where = "";
        } else {
            $where = "where a.sektor = '$sektor'";
        }

        $sql_kode_type = "
            select a.kode_type
            from mpm.tbl_bantu_type a
            $where
        ";

        $proses_kode_type = $this->db->query($sql_kode_type)->result_array();
        foreach ($proses_kode_type as $a) {
            $kode_typex[] = "'" . $a['kode_type'] . "'";
        }

        $kode_type = implode(", ", $kode_typex);
        return $kode_type;
    }

    

    public function get_kodeprod_by_group_exception($group, $exception)
    {
        if ($group == '') {
            $where = "where supp <>'BSP'";
        } else {
            $where = "where a.grup = '$group' and supp <> 'BSP'";
        }

        $sql_kodeprod = "
            select kodeprod
            from mpm.tabprod a
            $where and a.kodeprod not in ($exception)
        ";

        // echo "<pre>";
        // print_r($sql_kodeprod);
        // echo "</pre>";

        // die;

        $proses_kodeprod = $this->db->query($sql_kodeprod)->result_array();
        foreach ($proses_kodeprod as $a) {
            $kodeprodx[] = $a['kodeprod'];
        }

        $kodeprod = implode(', ', $kodeprodx);
        return $kodeprod;
    }

    public function get_kodeprod_by_supp($supp = '')
    {
        if ($supp == '') {
            $where = "where supp <>'BSP'";
        } else {
            $where = "where a.supp = '$supp'";
        }

        $sql_kodeprod = "
            select kodeprod
            from mpm.tabprod a
            $where
        ";

        // echo "<pre>";
        // print_r($sql_kodeprod);
        // echo "</pre>";

        // die;

        $proses_kodeprod = $this->db->query($sql_kodeprod)->result_array();
        foreach ($proses_kodeprod as $a) {
            $kodeprodx[] = $a['kodeprod'];
        }

        $kodeprod = implode(', ', $kodeprodx);
        return $kodeprod;
    }

    public function get_dashboard_monitor($signature = '')
    {
        if ($signature) {
            $params = "and signature = '$signature'";
        }else{
            $params = "";
        }
        // $query = "
        //     select * from site.dashboard_monitor a where a.created_at = (select max(a.created_at) from site.dashboard_monitor a)
        // ";

        $query = "
        select 	a.tahun, a.bulan, 
                sum(if(a.divisi = 'D1', a.omzet, 0)) as D1,
                sum(if(a.divisi = 'D2-EXCLUDE-RTD', a.omzet, 0)) as D2,
                sum(if(a.divisi = 'RTD', a.omzet, 0)) as RTD,
                sum(if(a.divisi = 'TOTAL', a.omzet, 0)) as TOTAL,
                a.created_at, a.signature
        from site.dashboard_monitor a
        where a.created_at = (
            select max(b.created_at) 
            from site.dashboard_monitor b
            where a.tahun = b.tahun
        ) $params
        GROUP BY a.bulan, a.tahun
        ORDER BY a.tahun desc, a.bulan asc
        ";

        $proses = $this->db->query($query);

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        return $proses;
    }

    public function get_dashboard_monitor_custom($tahun = '')
    {
        if ($tahun) {
            $params = "and tahun = '$tahun'";
        }else{

            $params = "and tahun = 2023";
        }
        // $query = "
        //     select * from site.dashboard_monitor a where a.created_at = (select max(a.created_at) from site.dashboard_monitor a)
        // ";

        $query = "
        select 	a.tahun, a.bulan, 
                sum(if(a.divisi = 'D1', a.omzet, 0)) as D1,
                sum(if(a.divisi = 'D2-EXCLUDE-RTD', a.omzet, 0)) as D2,
                sum(if(a.divisi = 'RTD', a.omzet, 0)) as RTD,
                sum(if(a.divisi = 'TOTAL', a.omzet, 0)) as TOTAL,
                a.created_at, a.signature
        from site.dashboard_monitor a
        where a.created_at = (
            select max(b.created_at) 
            from site.dashboard_monitor b
            where a.tahun = b.tahun
        ) $params
        GROUP BY a.bulan, a.tahun
        ORDER BY a.tahun desc, a.bulan asc
        ";

        $proses = $this->db->query($query);

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        return $proses;
    }

    public function get_mti_breakdown_site_code($divisi)
    {
        $query = "
            select * 
            from site.dashboard_mti_breakdown_site_code a 
            where a.divisi = '$divisi' and a.created_at = (select max(b.created_at) from site.dashboard_mti_breakdown_site_code b where b.divisi = '$divisi')
        ";
        $proses = $this->db->query($query);
        return $proses;
    }

    public function update_data($data){
        $tahun_saat_ini = date('Y');
        // $bulan_saat_ini = date('m');
        // $tahun_saat_ini = 2018;
        // $bulan_saat_ini = 12;
        $d1 = $data['d1'];
        $d2_exclude_rtd = $data['d2_exclude_rtd'];
        $created_at = $this->model_outlet_transaksi->timezone();
        // echo 'created_at: '.$created_at;

        // die;

        $signature = md5($created_at);

        // $truncate = $this->db->query('truncate site.dashboard_mti');

        $query = "
            insert into site.dashboard_monitor
            select '', $tahun_saat_ini as tahun, a.bulan, 'D1' as divisi, sum(a.omzet) as omzet, sum(a.unit) as unit, '$created_at' as created_at, '$signature'
            from 
            (
                select a.bulan, sum(a.tot1) as omzet, sum(a.banyak) as unit
                from data$tahun_saat_ini.fi a 
                where a.kodeprod in ($d1)
                group by a.bulan
                union all 
                select a.bulan, sum(a.tot1) as omzet, sum(a.banyak) as unit
                from data$tahun_saat_ini.ri a 
                where a.kodeprod in ($d1)
                group by a.bulan
            )a group by a.bulan
        ";
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        $proses = $this->db->query($query);

        $query2 = "
            insert into site.dashboard_monitor
            select '', $tahun_saat_ini as tahun, a.bulan, 'D2-EXCLUDE-RTD' as divisi, sum(a.omzet) as omzet, sum(a.unit) as unit, '$created_at' as created_at, '$signature'
            from 
            (
                select a.bulan, sum(a.tot1) as omzet, sum(a.banyak) as unit
                from data$tahun_saat_ini.fi a 
                where a.kodeprod in ($d2_exclude_rtd)
                group by a.bulan
                union all 
                select a.bulan, sum(a.tot1) as omzet, sum(a.banyak) as unit
                from data$tahun_saat_ini.ri a 
                where a.kodeprod in ($d2_exclude_rtd)
                group by a.bulan
            )a group by a.bulan
        ";
        // echo "<pre>";
        // print_r($query2);
        // echo "</pre>";
        $proses = $this->db->query($query2);

        $query3 = "
            insert into site.dashboard_monitor       
            select '', $tahun_saat_ini as tahun, a.bulan, 'RTD' as divisi, sum(a.omzet) as omzet, sum(a.unit) as unit, '$created_at' as created_at, '$signature'
            from 
            (
                select a.bulan, sum(a.tot1) as omzet, sum(a.banyak) as unit
                from data$tahun_saat_ini.fi a 
                where a.kodeprod in (010121)
                group by a.bulan
                union all 
                select a.bulan, sum(a.tot1) as omzet, sum(a.banyak) as unit
                from data$tahun_saat_ini.ri a 
                where a.kodeprod in (010121)
                group by a.bulan
            )a group by a.bulan
        ";
        // echo "<pre>";
        // print_r($query3);
        // echo "</pre>";
        $proses = $this->db->query($query3);

        $query4 = "
            insert into site.dashboard_monitor         
            select '', $tahun_saat_ini as tahun, a.bulan, 'TOTAL' as divisi, sum(a.omzet) as omzet, sum(a.unit) as unit, '$created_at' as created_at, '$signature'
            from 
            (
                select a.bulan, sum(a.tot1) as omzet, sum(a.banyak) as unit
                from data$tahun_saat_ini.fi a 
                where a.kodeprod in ($d1, $d2_exclude_rtd, '010121')
                group by a.bulan
                union all 
                select a.bulan, sum(a.tot1) as omzet, sum(a.banyak) as unit
                from data$tahun_saat_ini.ri a 
                where a.kodeprod in ($d1, $d2_exclude_rtd, '010121')
                group by a.bulan
            )a group by a.bulan
        ";
        // echo "<pre>";
        // print_r($query4);
        // echo "</pre>";
        $proses = $this->db->query($query4);        

        // echo "<pre>";
        // print_r($query_deltomed);
        // echo "</pre>";

        // die;

        return $proses;

    }

    function get_last_signature(){
        $query = "select signature from site.dashboard_monitor a where a.created_at = (select max(b.created_at) from site.dashboard_monitor b)";

        return $this->db->query($query);
        
    }

    function get_email(){
        $query = "
            select *
            from site.dashboard_monitor_email a
        ";
        return $this->db->query($query);
    }

    function get_data_barchart(){
        $query = "
            select * from test.barchart
        ";
        return $this->db->query($query);
    }

    function get_library_raw_data(){
        $query = "
            select * from db_raw_cloning.t_library_raw_data
        ";
        return $this->db->query($query);
    }

    function get_max_created_at(){
        $query = "
            select max(a.created_at) as created_at
            from site.dashboard_monitor a"
        ;

        return $this->db->query($query);
    }

    

}