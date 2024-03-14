<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_management_office extends CI_Model 
{
    public function get_kalender_data(){    
        $query = "select * from management_office.kalender_data";
        return $this->db->query($query);
    }

    public function monitoring_count(){
        $query = "select * from management_office.monitoring_count a where a.created_at = (select max(b.created_at) from management_office.monitoring_count b)";
        return $this->db->query($query);
    }

    public function monitoring_kam(){
        $query = "
            select *
            from site.dashboard_mti a
        ";
        return $this->db->query($query);
    }

    public function update_data_kam($data){
        $tahun_saat_ini = date('Y');
        $bulan_saat_ini = date('m');
        // $tahun_saat_ini = 2023;
        // $bulan_saat_ini = 05;
        $herbal = $data['herbal'];
        $candy = $data['candy'];
        $kode_type = $data['kode_type'];
        $kode_type_ph = $data['kode_type_ph'];
        $all_principal = $data['all_principal'];
        $created_at = $this->model_outlet_transaksi->timezone();

        // echo "kode_type : ".$kode_type;
        // echo "<br>";
        // echo "kode_type_ph : ".$kode_type_ph;

        // die;

        $truncate = $this->db->query('truncate site.dashboard_mti');

        $query = "
            insert into site.dashboard_mti
            select '', $tahun_saat_ini as tahun, $bulan_saat_ini as bulan, 'MTI', 'D1' as divisi, sum(a.omzet) as omzet, sum(a.unit) as unit, '$created_at' as created_at
            from 
            (
                select sum(a.tot1) as omzet, sum(a.banyak) as unit
                from data$tahun_saat_ini.fi a 
                where a.bulan in ($bulan_saat_ini) and a.kode_type in ($kode_type) and a.kodeprod in ($herbal)
                union all 
                select sum(a.tot1) as omzet, sum(a.banyak) as unit
                from data$tahun_saat_ini.ri a 
                where a.bulan in ($bulan_saat_ini) and a.kode_type in ($kode_type) and a.kodeprod in ($herbal)
            )a
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        $proses = $this->db->query($query);

        $query2 = "
            insert into site.dashboard_mti            
            select '', $tahun_saat_ini as tahun, $bulan_saat_ini as bulan, 'MTI',  'D2 (exclude rtd)' as divisi, sum(a.omzet) as omzet, sum(a.unit) as unit, '$created_at' as created_at
            from 
            (
                select sum(a.tot1) as omzet, sum(a.banyak) as unit
                from data$tahun_saat_ini.fi a 
                where a.bulan in ($bulan_saat_ini) and a.kode_type in ($kode_type) and a.kodeprod in ($candy)
                union all 
                select sum(a.tot1) as omzet, sum(a.banyak) as unit
                from data$tahun_saat_ini.ri a 
                where a.bulan in ($bulan_saat_ini) and a.kode_type in ($kode_type) and a.kodeprod in ($candy)
            )a
        ";

        // echo "<pre>";
        // print_r($query2);
        // echo "</pre>";

        $proses = $this->db->query($query2);

        $query3 = "
            insert into site.dashboard_mti            
            select '', $tahun_saat_ini as tahun, $bulan_saat_ini as bulan, 'MTI', 'RTD' as divisi, sum(a.omzet) as omzet, sum(a.unit) as unit, '$created_at' as created_at
            from 
            (
                select sum(a.tot1) as omzet, sum(a.banyak) as unit
                from data$tahun_saat_ini.fi a 
                where a.bulan in ($bulan_saat_ini) and a.kode_type in ($kode_type) and a.kodeprod in (010121) and a.kodeprod in ($herbal, $candy, '010121')
                union all 
                select sum(a.tot1) as omzet, sum(a.banyak) as unit
                from data$tahun_saat_ini.ri a 
                where a.bulan in ($bulan_saat_ini) and a.kode_type in ($kode_type) and a.kodeprod in (010121) and a.kodeprod in ($herbal, $candy, '010121')
            )a
        ";

        // echo "<pre>";
        // print_r($query3);
        // echo "</pre>";

        $proses = $this->db->query($query3);

        $query4 = "
            insert into site.dashboard_mti            
            select '', $tahun_saat_ini as tahun, $bulan_saat_ini as bulan, 'MTI', 'TOTAL' as divisi, sum(a.omzet) as omzet, sum(a.unit) as unit, '$created_at' as created_at
            from 
            (
                select sum(a.tot1) as omzet, sum(a.banyak) as unit
                from data$tahun_saat_ini.fi a 
                where a.bulan in ($bulan_saat_ini) and a.kode_type in ($kode_type) and a.kodeprod in ($herbal, $candy, '010121')
                union all 
                select sum(a.tot1) as omzet, sum(a.banyak) as unit
                from data$tahun_saat_ini.ri a 
                where a.bulan in ($bulan_saat_ini) and a.kode_type in ($kode_type) and a.kodeprod in ($herbal, $candy, '010121')
            )a
        ";

        // echo "<pre>";
        // print_r($query4);
        // echo "</pre>";

        $proses = $this->db->query($query4);

        $query_deltomed = "
            insert into site.dashboard_mti            
            select '', $tahun_saat_ini as tahun, $bulan_saat_ini as bulan, 'MTI', 'ALL-PRINCIPAL' as divisi, sum(a.omzet) as omzet, sum(a.unit) as unit, '$created_at' as created_at
            from 
            (
                select sum(a.tot1) as omzet, sum(a.banyak) as unit
                from data$tahun_saat_ini.fi a 
                where a.bulan in ($bulan_saat_ini) and a.kode_type in ($kode_type) and a.kodeprod in ($all_principal)
                union all 
                select sum(a.tot1) as omzet, sum(a.banyak) as unit
                from data$tahun_saat_ini.ri a 
                where a.bulan in ($bulan_saat_ini) and a.kode_type in ($kode_type) and a.kodeprod in ($all_principal)
            )a
        ";

        // echo "<pre>";
        // print_r($query_deltomed);
        // echo "</pre>";

        $proses = $this->db->query($query_deltomed);

        $query_d1_ph = "
            insert into site.dashboard_mti            
            select '', $tahun_saat_ini as tahun, $bulan_saat_ini as bulan, 'PH', 'D1' as divisi, sum(a.omzet) as omzet, sum(a.unit) as unit, '$created_at' as created_at
            from 
            (
                select sum(a.tot1) as omzet, sum(a.banyak) as unit
                from data$tahun_saat_ini.fi a 
                where a.bulan in ($bulan_saat_ini) and a.kode_type in ($kode_type_ph) and a.kodeprod in ($herbal)
                union all 
                select sum(a.tot1) as omzet, sum(a.banyak) as unit
                from data$tahun_saat_ini.ri a 
                where a.bulan in ($bulan_saat_ini) and a.kode_type in ($kode_type_ph) and a.kodeprod in ($herbal)
            )a
        ";

        // echo "<pre>";
        // print_r($query_d1_ph);
        // echo "</pre>";

        $proses = $this->db->query($query_d1_ph);

        $query_d2_ph = "
            insert into site.dashboard_mti            
            select '', $tahun_saat_ini as tahun, $bulan_saat_ini as bulan, 'PH',  'D2 (exclude rtd)' as divisi, sum(a.omzet) as omzet, sum(a.unit) as unit, '$created_at' as created_at
            from 
            (
                select sum(a.tot1) as omzet, sum(a.banyak) as unit
                from data$tahun_saat_ini.fi a 
                where a.bulan in ($bulan_saat_ini) and a.kode_type in ($kode_type_ph) and a.kodeprod in ($candy)
                union all 
                select sum(a.tot1) as omzet, sum(a.banyak) as unit
                from data$tahun_saat_ini.ri a 
                where a.bulan in ($bulan_saat_ini) and a.kode_type in ($kode_type_ph) and a.kodeprod in ($candy)
            )a
        ";

        // echo "<pre>";
        // print_r($query_d2_ph);
        // echo "</pre>";

        $proses = $this->db->query($query_d2_ph);

        $query_rtd_ph = "
            insert into site.dashboard_mti            
            select '', $tahun_saat_ini as tahun, $bulan_saat_ini as bulan, 'PH', 'RTD' as divisi, sum(a.omzet) as omzet, sum(a.unit) as unit, '$created_at' as created_at
            from 
            (
                select sum(a.tot1) as omzet, sum(a.banyak) as unit
                from data$tahun_saat_ini.fi a 
                where a.bulan in ($bulan_saat_ini) and a.kode_type in ($kode_type_ph) and a.kodeprod in (010121) and a.kodeprod in ($herbal, $candy, '010121')
                union all 
                select sum(a.tot1) as omzet, sum(a.banyak) as unit
                from data$tahun_saat_ini.ri a 
                where a.bulan in ($bulan_saat_ini) and a.kode_type in ($kode_type_ph) and a.kodeprod in (010121) and a.kodeprod in ($herbal, $candy, '010121')
            )a
        ";

        // echo "<pre>";
        // print_r($query_rtd_ph);
        // echo "</pre>";

        $proses = $this->db->query($query_rtd_ph);

        $query_total_ph = "
            insert into site.dashboard_mti            
            select '', $tahun_saat_ini as tahun, $bulan_saat_ini as bulan, 'PH', 'TOTAL' as divisi, sum(a.omzet) as omzet, sum(a.unit) as unit, '$created_at' as created_at
            from 
            (
                select sum(a.tot1) as omzet, sum(a.banyak) as unit
                from data$tahun_saat_ini.fi a 
                where a.bulan in ($bulan_saat_ini) and a.kode_type in ($kode_type_ph) and a.kodeprod in ($herbal, $candy, '010121')
                union all 
                select sum(a.tot1) as omzet, sum(a.banyak) as unit
                from data$tahun_saat_ini.ri a 
                where a.bulan in ($bulan_saat_ini) and a.kode_type in ($kode_type_ph) and a.kodeprod in ($herbal, $candy, '010121')
            )a
        ";

        // echo "<pre>";
        // print_r($query_total_ph);
        // echo "</pre>";

        $proses = $this->db->query($query_total_ph);

        return true;

        

        // $query5 = "
        //     insert into site.dashboard_mti_breakdown_site_code  
        //     select '', $tahun_saat_ini as tahun, $bulan_saat_ini as bulan, 'herbal' as divisi, a.site_code, b.branch_name, b.nama_comp, a.omzet, a.unit, '$created_at' as created_at
        //     from 
        //     (
        //         select site_code, sum(a.omzet) as omzet, sum(a.unit) as unit
        //         from 
        //         (
        //             select concat(a.kode_comp, a.nocab) as site_code, sum(a.tot1) as omzet, sum(a.banyak) as unit
        //             from data$tahun_saat_ini.fi a 
        //             where a.bulan in ($bulan_saat_ini) and a.kode_type in ($kode_type) and a.kodeprod in ($herbal)
        //             GROUP BY concat(a.kode_comp, a.nocab)
        //             union all 
        //             select concat(a.kode_comp, a.nocab) as site_code, sum(a.tot1) as omzet, sum(a.banyak) as unit
        //             from data$tahun_saat_ini.ri a 
        //             where a.bulan in ($bulan_saat_ini) and a.kode_type in ($kode_type) and a.kodeprod in ($herbal)
        //             GROUP BY concat(a.kode_comp, a.nocab)
        //         )a GROUP BY site_code
        //     )a left join (
        //         select concat(a.kode_comp, a.nocab) as site_code, a.branch_name, a.nama_comp, a.urutan
        //         from mpm.tbl_tabcomp a 
        //         where a.`status` = 1
        //         GROUP BY concat(a.kode_comp, a.nocab)
        //     )b on a.site_code = b.site_code
        //     ORDER BY b.urutan
        // ";

        // echo "<pre>";
        // print_r($query5);
        // echo "</pre>";

        // $proses = $this->db->query($query5);

        // $query6 = "
        //     insert into site.dashboard_mti_breakdown_site_code  
        //     select '', $tahun_saat_ini as tahun, $bulan_saat_ini as bulan, 'candy' as divisi, a.site_code, b.branch_name, b.nama_comp, a.omzet, a.unit, '$created_at' as created_at
        //     from 
        //     (
        //         select site_code, sum(a.omzet) as omzet, sum(a.unit) as unit
        //         from 
        //         (
        //             select concat(a.kode_comp, a.nocab) as site_code, sum(a.tot1) as omzet, sum(a.banyak) as unit
        //             from data$tahun_saat_ini.fi a 
        //             where a.bulan in ($bulan_saat_ini) and a.kode_type in ($kode_type) and a.kodeprod in ($candy)
        //             GROUP BY concat(a.kode_comp, a.nocab)
        //             union all 
        //             select concat(a.kode_comp, a.nocab) as site_code, sum(a.tot1) as omzet, sum(a.banyak) as unit
        //             from data$tahun_saat_ini.ri a 
        //             where a.bulan in ($bulan_saat_ini) and a.kode_type in ($kode_type) and a.kodeprod in ($candy)
        //             GROUP BY concat(a.kode_comp, a.nocab)
        //         )a GROUP BY site_code
        //     )a left join (
        //         select concat(a.kode_comp, a.nocab) as site_code, a.branch_name, a.nama_comp, a.urutan
        //         from mpm.tbl_tabcomp a 
        //         where a.`status` = 1
        //         GROUP BY concat(a.kode_comp, a.nocab)
        //     )b on a.site_code = b.site_code
        //     ORDER BY b.urutan
        // ";

        // echo "<pre>";
        // print_r($query6);
        // echo "</pre>";

        // $proses = $this->db->query($query6);

        // $query6 = "
        //     insert into site.dashboard_mti_breakdown_site_code  
        //     select '', $tahun_saat_ini as tahun, $bulan_saat_ini as bulan, 'RTD' as divisi, a.site_code, b.branch_name, b.nama_comp, a.omzet, a.unit, '$created_at' as created_at
        //     from 
        //     (
        //         select site_code, sum(a.omzet) as omzet, sum(a.unit) as unit
        //         from 
        //         (
        //             select concat(a.kode_comp, a.nocab) as site_code, sum(a.tot1) as omzet, sum(a.banyak) as unit
        //             from data$tahun_saat_ini.fi a 
        //             where a.bulan in ($bulan_saat_ini) and a.kode_type in ($kode_type) and a.kodeprod in ('010121')
        //             GROUP BY concat(a.kode_comp, a.nocab)
        //             union all 
        //             select concat(a.kode_comp, a.nocab) as site_code, sum(a.tot1) as omzet, sum(a.banyak) as unit
        //             from data$tahun_saat_ini.ri a 
        //             where a.bulan in ($bulan_saat_ini) and a.kode_type in ($kode_type) and a.kodeprod in ('010121')
        //             GROUP BY concat(a.kode_comp, a.nocab)
        //         )a GROUP BY site_code
        //     )a left join (
        //         select concat(a.kode_comp, a.nocab) as site_code, a.branch_name, a.nama_comp, a.urutan
        //         from mpm.tbl_tabcomp a 
        //         where a.`status` = 1
        //         GROUP BY concat(a.kode_comp, a.nocab)
        //     )b on a.site_code = b.site_code
        //     ORDER BY b.urutan
        // ";

        // echo "<pre>";
        // print_r($query6);
        // echo "</pre>";

        // $proses = $this->db->query($query6);

        
        

        // return $proses;

    }

    public function monitoring_sell_out(){
        $query = "
            select *
            from site.temp_sell_out a
        ";
        return $this->db->query($query);
    }
    
}