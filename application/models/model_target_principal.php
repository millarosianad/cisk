<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_target_principal extends CI_Model 
{
    public function get_log_import($signature = ''){

        if ($signature) {
            $params = "where signature = '$signature'";
        }else{
            $params = "";
        }

        $query = "
            select *
            from site.target_log_import a 
            $params
            order by a.id desc
        ";

        return $this->db->query($query);
    }

    public function get_data_import($signature = ''){

        if ($signature) {
            $params = "where signature = '$signature'";
        }else{
            $params = "";
        }

        $query = "
            select *
            from site.target_import_deltomed_by_subbranch a 
            $params
        ";

        return $this->db->query($query);
    }

    public function mapping_deltomed_target_by_subbranch($signature){

        $created_at = $this->model_outlet_transaksi->timezone();
        $created_by = $this->session->userdata('id');

        $query = "
        insert into site.target_mapping_deltomed
        select  '', a.bulan, a.site_code, a.target_in_unit, a.target_in_value,
                b.branch_name, b.nama_comp, 
                c.kodeprod, c.namaprod, '$signature', '$created_at', '$created_by'
        from site.target_import_deltomed_by_subbranch a LEFT JOIN
        (
            select concat(a.kode_comp, a.nocab) as site_code, a.branch_name, a.nama_comp, a.urutan
            from mpm.tbl_tabcomp a 
            where a.status = 1
            GROUP BY concat(a.kode_comp, a.nocab)
        )b on a.site_code = b.site_code join (
            select a.kodeprod, a.namaprod, a.new_divisi, a.grup, a.subgroup, a.new_group, a.new_subgroup, a.new_groupdiv, a.status_gimmick
            from mpm.tabprod a 
            where a.supp = 001 and a.active = 1 and a.status_gimmick is null and a.grup not in ('GIMMICK') and a.grup not in ('GIMMICK PROGRAM')
        )c
        where a.signature = '$signature'
        order by b.urutan asc
        ";

        return $this->db->query($query);
    }

    public function get_data_mapping_deltomed_by_subbranch($signature){
        if ($signature) {
            $params = "where a.signature = '$signature'";
        }else{
            $params = "";
        }

        $query = "
            select * from site.target_mapping_deltomed a 
            $params
        ";

        return $this->db->query($query);

    }

}