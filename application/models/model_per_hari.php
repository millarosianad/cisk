<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_per_hari extends CI_Model
{

	public function input_data_hari($data){

        $id = $this->session->userdata('id');
        date_default_timezone_set('Asia/Jakarta');        
		$created_date='"'.date('Y-m-d H:i:s').'"';
        $supp = $data['supp'];
        $tahun = $data['tahun'];
        $bulan = $data['bulan'];
        $group = $data['group'];
        $tipe_1 = $data['tipe_1'];
        $tipe_2 = $data['tipe_2'];
        $tipe_3 = $data['tipe_3'];
        $kodeprod = $data['cari_kodeprod'];        

        if ($tipe_1 == 1) {
            $group_by_1 = ",kodeprod";
        }else{
            $group_by_1 = "";
        }

        if ($tipe_2 == 1) {
            $group_by_2 = ",kodesalur";
        }else{
            $group_by_2 = "";
        }

        if ($tipe_3 == 1) {
            $group_by_3 = ",kode_type";
        }else{
            $group_by_3 = "";
        }

        // $this->db->query("delete from mpm.t_sales_per_hari where id = $id");

        $wilayah_nocab = $data['wilayah_nocab'];
        // echo "wilayah_nocab : ".$wilayah_nocab;

        if ($wilayah_nocab == null) {
            $wilayah = '';
        }else{
            $wilayah = "and nocab in ($wilayah_nocab)";
        }

        $sql = " insert into db_temp.t_temp_sales_per_hari
                select 	a.kode, b.branch_name, b.nama_comp, a.kodeprod, e.namaprod,a.KODESALUR,c.namasalur,a.KODE_TYPE,d.nama_type,d.sektor,
                sum(if(a.HRDOK = 1,a.unit,0)) as unit_1,
                sum(if(a.HRDOK = 2,a.unit,0)) as unit_2,
                sum(if(a.HRDOK = 3,a.unit,0)) as unit_3,
                sum(if(a.HRDOK = 4,a.unit,0)) as unit_4,
                sum(if(a.HRDOK = 5,a.unit,0)) as unit_5,
                sum(if(a.HRDOK = 6,a.unit,0)) as unit_6,
                sum(if(a.HRDOK = 7,a.unit,0)) as unit_7,
                sum(if(a.HRDOK = 8,a.unit,0)) as unit_8,
                sum(if(a.HRDOK = 9,a.unit,0)) as unit_9,
                sum(if(a.HRDOK = 10,a.unit,0)) as unit_10,
                sum(if(a.HRDOK = 11,a.unit,0)) as unit_11,
                sum(if(a.HRDOK = 12,a.unit,0)) as unit_12,
                sum(if(a.HRDOK = 13,a.unit,0)) as unit_13,
                sum(if(a.HRDOK = 14,a.unit,0)) as unit_14,
                sum(if(a.HRDOK = 15,a.unit,0)) as unit_15,
                sum(if(a.HRDOK = 16,a.unit,0)) as unit_16,
                sum(if(a.HRDOK = 17,a.unit,0)) as unit_17,
                sum(if(a.HRDOK = 18,a.unit,0)) as unit_18,
                sum(if(a.HRDOK = 19,a.unit,0)) as unit_19,
                sum(if(a.HRDOK = 20,a.unit,0)) as unit_20,
                sum(if(a.HRDOK = 21,a.unit,0)) as unit_21,
                sum(if(a.HRDOK = 22,a.unit,0)) as unit_22,
                sum(if(a.HRDOK = 23,a.unit,0)) as unit_23,
                sum(if(a.HRDOK = 24,a.unit,0)) as unit_24,
                sum(if(a.HRDOK = 25,a.unit,0)) as unit_25,
                sum(if(a.HRDOK = 26,a.unit,0)) as unit_26,
                sum(if(a.HRDOK = 27,a.unit,0)) as unit_27,
                sum(if(a.HRDOK = 28,a.unit,0)) as unit_28,
                sum(if(a.HRDOK = 29,a.unit,0)) as unit_29,
                sum(if(a.HRDOK = 30,a.unit,0)) as unit_30,
                sum(if(a.HRDOK = 31,a.unit,0)) as unit_31,

                sum(if(a.HRDOK = 1,a.value,0)) as value_1,
                sum(if(a.HRDOK = 2,a.value,0)) as value_2,
                sum(if(a.HRDOK = 3,a.value,0)) as value_3,
                sum(if(a.HRDOK = 4,a.value,0)) as value_4,
                sum(if(a.HRDOK = 5,a.value,0)) as value_5,
                sum(if(a.HRDOK = 6,a.value,0)) as value_6,
                sum(if(a.HRDOK = 7,a.value,0)) as value_7,
                sum(if(a.HRDOK = 8,a.value,0)) as value_8,
                sum(if(a.HRDOK = 9,a.value,0)) as value_9,
                sum(if(a.HRDOK = 10,a.value,0)) as value_10,
                sum(if(a.HRDOK = 11,a.value,0)) as value_11,
                sum(if(a.HRDOK = 12,a.value,0)) as value_12,
                sum(if(a.HRDOK = 13,a.value,0)) as value_13,
                sum(if(a.HRDOK = 14,a.value,0)) as value_14,
                sum(if(a.HRDOK = 15,a.value,0)) as value_15,
                sum(if(a.HRDOK = 16,a.value,0)) as value_16,
                sum(if(a.HRDOK = 17,a.value,0)) as value_17,
                sum(if(a.HRDOK = 18,a.value,0)) as value_18,
                sum(if(a.HRDOK = 19,a.value,0)) as value_19,
                sum(if(a.HRDOK = 20,a.value,0)) as value_20,
                sum(if(a.HRDOK = 21,a.value,0)) as value_21,
                sum(if(a.HRDOK = 22,a.value,0)) as value_22,
                sum(if(a.HRDOK = 23,a.value,0)) as value_23,
                sum(if(a.HRDOK = 24,a.value,0)) as value_24,
                sum(if(a.HRDOK = 25,a.value,0)) as value_25,
                sum(if(a.HRDOK = 26,a.value,0)) as value_26,
                sum(if(a.HRDOK = 27,a.value,0)) as value_27,
                sum(if(a.HRDOK = 28,a.value,0)) as value_28,
                sum(if(a.HRDOK = 29,a.value,0)) as value_29,
                sum(if(a.HRDOK = 30,a.value,0)) as value_30,
                sum(if(a.HRDOK = 31,a.value,0)) as value_31,

                sum(if(a.HRDOK = 1,a.outlet,0)) as outlet_1,
                sum(if(a.HRDOK = 2,a.outlet,0)) as outlet_2,
                sum(if(a.HRDOK = 3,a.outlet,0)) as outlet_3,
                sum(if(a.HRDOK = 4,a.outlet,0)) as outlet_4,
                sum(if(a.HRDOK = 5,a.outlet,0)) as outlet_5,
                sum(if(a.HRDOK = 6,a.outlet,0)) as outlet_6,
                sum(if(a.HRDOK = 7,a.outlet,0)) as outlet_7,
                sum(if(a.HRDOK = 8,a.outlet,0)) as outlet_8,
                sum(if(a.HRDOK = 9,a.outlet,0)) as outlet_9,
                sum(if(a.HRDOK = 10,a.outlet,0)) as outlet_10,
                sum(if(a.HRDOK = 11,a.outlet,0)) as outlet_11,
                sum(if(a.HRDOK = 12,a.outlet,0)) as outlet_12,
                sum(if(a.HRDOK = 13,a.outlet,0)) as outlet_13,
                sum(if(a.HRDOK = 14,a.outlet,0)) as outlet_14,
                sum(if(a.HRDOK = 15,a.outlet,0)) as outlet_15,
                sum(if(a.HRDOK = 16,a.outlet,0)) as outlet_16,
                sum(if(a.HRDOK = 17,a.outlet,0)) as outlet_17,
                sum(if(a.HRDOK = 18,a.outlet,0)) as outlet_18,
                sum(if(a.HRDOK = 19,a.outlet,0)) as outlet_19,
                sum(if(a.HRDOK = 20,a.outlet,0)) as outlet_20,
                sum(if(a.HRDOK = 21,a.outlet,0)) as outlet_21,
                sum(if(a.HRDOK = 22,a.outlet,0)) as outlet_22,
                sum(if(a.HRDOK = 23,a.outlet,0)) as outlet_23,
                sum(if(a.HRDOK = 24,a.outlet,0)) as outlet_24,
                sum(if(a.HRDOK = 25,a.outlet,0)) as outlet_25,
                sum(if(a.HRDOK = 26,a.outlet,0)) as outlet_26,
                sum(if(a.HRDOK = 27,a.outlet,0)) as outlet_27,
                sum(if(a.HRDOK = 28,a.outlet,0)) as outlet_28,
                sum(if(a.HRDOK = 29,a.outlet,0)) as outlet_29,
                sum(if(a.HRDOK = 30,a.outlet,0)) as outlet_30,
                sum(if(a.HRDOK = 31,a.outlet,0)) as outlet_31, $bulan, $id,$created_date
        FROM
        (
                select 	concat(a.kode_comp,a.nocab) as kode, a.kodeprod, a.KODESALUR, a.KODE_TYPE,
                        sum(a.BANYAK) as unit,
                        sum(a.TOT1) as `value`, HRDOK,
                        count(distinct(concat(a.kode_comp,a.kode_lang))) as outlet
                from data$tahun.fi a
                where bulan = $bulan and kodeprod in ($kodeprod) $wilayah
                GROUP BY kode, hrdok $group_by_1 $group_by_2 $group_by_3
                union ALL
                select 	concat(a.kode_comp,a.nocab) as kode, a.kodeprod, a.KODESALUR, a.KODE_TYPE,
                        sum(a.BANYAK) as unit, sum(a.TOT1) as `value`, HRDOK,
                        null
                from data$tahun.ri a
                where bulan = $bulan and kodeprod in ($kodeprod) $wilayah
                GROUP BY kode, hrdok $group_by_1 $group_by_2 $group_by_3
        )a LEFT JOIN
        (
            select concat(a.kode_comp,a.nocab) as kode, a.branch_name, a.nama_comp, a.urutan
            from mpm.tbl_tabcomp a
            where a.`status` = 1
            GROUP BY concat(a.kode_comp,a.nocab)
        )b on a.kode = b.kode LEFT JOIN
        (
            select a.kode, a.jenis as namasalur
            from mpm.tbl_tabsalur a
        )c on a.kodesalur = c.kode LEFT JOIN
        (
            select a.kode_type,a.nama_type,a.sektor
            from mpm.tbl_bantu_type a
        )d on a.kode_type = d.kode_type left join
        (
            SELECT kodeprod,namaprod
            from mpm.tabprod
        )e on a.kodeprod = e.kodeprod
        GROUP BY kode $group_by_1 $group_by_2 $group_by_3
        order by urutan asc, kodeprod, kodesalur, sektor

        ";

        // echo "<pre>";
        // print_r($sql);
        // echo "</pre>";

        $proses = $this->db->query($sql);
        if ($proses) {
            $sql = "select * from db_temp.t_temp_sales_per_hari where id = $id and created_date = (select max(created_date) from db_temp.t_temp_sales_per_hari where id = $id)";
            $hasil = $this->db->query($sql)->result();
            if($hasil){
                return $hasil;
            }else{
                return array();
            }
        }else{
            return array();
        }

    }

    public function cari_kodeprod($group){

        $sql = "select kode_group
                from mpm.tbl_group a
                where id_group = $group
            ";
        
        $proses = $this->db->query($sql)->result();
        foreach ($proses as $i) {
            $kode_group = $i->kode_group;
        }

        $sql_kodeprod = "select kodeprod
                from mpm.tabprod a
                where a.grup = '$kode_group'
            ";
        $proses_kodeprod = $this->db->query($sql_kodeprod)->result_array();
        foreach ($proses_kodeprod as $a) {
            $kodeprodx[] = $a['kodeprod'];
        }

        $kodeprod = implode(', ', $kodeprodx);
        return $kodeprod;
    }

    public function cari_kodeprod_supp($supp){
        // echo "supp : ".$supp;
        if ($supp == '000') {
            $sql_kodeprod = "
                select kodeprod
                from mpm.tabprod a
                where a.supp in ('001','002','004','005')
            ";
        }elseif ($supp == 'XXX') {
            $sql_kodeprod = "
                select kodeprod
                from mpm.tabprod a
                where a.supp not in ('BSP') and a.kodeprod <> ''
            ";
        }else{
            // echo "supp : ".$supp;
            $sql_kodeprod = "
                select kodeprod
                from mpm.tabprod a
                where a.supp = '$supp'
            ";
            // echo "<pre><br><br><br><br>";
            // print_r($sql_kodeprod);
            // echo "</pre>";
        }
        
        $proses_kodeprod = $this->db->query($sql_kodeprod)->result_array();
        foreach ($proses_kodeprod as $a) {
            $kodeprodx[] = $a['kodeprod'];
        }

        $kodeprod = implode(', ', $kodeprodx);
        return $kodeprod;
    }

    public function cari_kodeprod_view($supp){   
        $sql_kodeprod = "
            select kodeprod, namaprod, grup, supp, subgroup
            from mpm.tabprod a
            where a.supp = '$supp'
            order by namaprod asc
        ";
        $proses_kodeprod = $this->db->query($sql_kodeprod)->result();
        return $proses_kodeprod;
    }

    public function get_namasupp($supp){
        $sql = "
            select namasupp
            from mpm.tabsupp a
            where a.supp = '$supp'
        ";
        
        $proses = $this->db->query($sql)->result();
        foreach ($proses as $a) {
            $namasupp = $a->namasupp;
        }
        return $namasupp;
    }

    public function get_namagroup($group){
        $sql = "
            SELECT nama_group
            from mpm.tbl_group a
            where a.id_group = '$group'
        ";
        
        $proses = $this->db->query($sql)->result();
        if ($proses) {
            foreach ($proses as $a) {
                $nama_group = $a->nama_group;
            }
            return $nama_group;
        }else{
            return '-';
        }
        
    }

    public function wilayah_nocab($id){
        $sql = "
            select wilayah_nocab
            from mpm.`user` a
            where id = $id        
        ";
        $proses = $this->db->query($sql)->result();
        foreach ($proses as $i) {
            $wilayah_nocab = $i->wilayah_nocab;
        }
        return $wilayah_nocab;
    }

    public function get_userid($nocab){
        // echo "nocab : ".$nocab;
        // die;

        if ($nocab == null) {
            return null;
        }else{
            $sql = "
            select a.id, a.username
            from mpm.user a inner JOIN 
            (
                select concat(a.kode_comp, a.nocab) as site_code, a.branch_name, a.nama_comp, kode_comp
                from mpm.tbl_tabcomp a 
                where a.`status` = 1 and nocab in ($nocab) 
                GROUP BY site_code
            )b on a.username = b.kode_comp   
            ";
            $proses = $this->db->query($sql)->result();
            $userid_po = '';

            foreach ($proses as $i) {
                $userid_po.=",".$i->id;
            }

            return preg_replace('/,/', '', $userid_po,1);
        }

        

    }

    public function get_kode_type($type_1 = 0,$type_2 = 0,$type_3 = 0){
        if ($type_1 == 1) {
            $kode_type_1 = "apotik";
        }else{
            $kode_type_1 = "";
        }

        if ($type_2 == 1) {
            $kode_type_2 = "pbf";
        }else{
            $kode_type_2 = "";
        }

        if ($type_3 == 1) {
            $kode_type_3 = "mti";
        }else{
            $kode_type_3 = "";
        }
        
        $sql = "
        select a.kode_type, a.nama_type, a.sektor
        from mpm.tbl_bantu_type a
        where a.sektor in ('$kode_type_1','$kode_type_2','$kode_type_3')
        ";

        // echo "<pre>";
        // print_r($sql);
        // echo "</pre>";

        $proses = $this->db->query($sql)->result_array();
        foreach ($proses as $a) {
            $kode_typex[] = "'".$a['kode_type']."'";
        }

        $kode_type = implode(', ', $kode_typex);
        // echo $kode_type;

        return $kode_type;
    }

}