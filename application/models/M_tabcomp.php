<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_tabcomp extends CI_Model
{
    public function Master_tabcomp()
    {
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
        set_time_limit(0);
        $this->load->library(array('table','template','Excel_generator', 'form_validation','email'));
        $this->load->helper('url');
        $this->load->helper('csv');
        $this->load->model('M_menu');
        $this->load->model('M_product');
        $this->load->database();
    }

    public function get_tabcomp(){
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
        $tahun = date('Y');
        $sql='
            select a.id, a.kode,a.branch_name,a.nama_comp,a.kode_comp,a.nocab,a.sub, a.active, a.stok, a.status_api,
                    a.jawa, a.status_cluster, a.active_repl, a.status_group_repl, a.group_repl
            FROM
            (
                    select  a.id, concat(a.kode_comp,a.nocab) as kode, a.branch_name, a.nama_comp,a.kode_comp,a.nocab,
                            a.sub, a.active, a.stok, a.status_api, a.jawa, a.status_cluster, a.active_repl, 
                            a.status_group_repl, a.group_repl
                    from mpm.tbl_tabcomp a
                    where a.`status` = 1
                    GROUP BY kode
            )a INNER JOIN
            (
                    select concat(a.kode_comp,a.nocab) as kode
                    from db_dp.t_dp a
                    where a.tahun = "'.$tahun.'" and `status` =1
            )b on a.kode = b.kode
            ORDER BY nocab
        ';
        // var_dump($sql);die;
        $proses = $this->db->query($sql)->result();
        return $proses;
    }

    public function get_tabcomp_by_id($tabcompID){
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
        $tahun = date('Y');
        $sql='
            select a.id, a.kode,a.branch_name,a.nama_comp,a.kode_comp,a.nocab,a.sub,a.status,
                    a.status, a.group_repl, a.customerid, a.urutan
            FROM
            (
                    select  a.id, concat(a.kode_comp,a.nocab) as kode, a.branch_name, a.nama_comp,a.kode_comp,a.nocab,
                            a.sub, a.status, a.group_repl, a.customerid, a.urutan
                    from mpm.tbl_tabcomp a
                    where a.`status` = 1
                    GROUP BY kode
            )a INNER JOIN
            (
                    select concat(a.kode_comp,a.nocab) as kode
                    from db_dp.t_dp a
                    where a.tahun = "'.$tahun.'" and `status` =1
            )b on a.kode = b.kode
            where a.id = "'.$tabcompID.'"
            ORDER BY nocab
        ';
        // var_dump($sql);die;
        $proses = $this->db->query($sql)->row();
        return $proses;
    }

    public function tambah_tabcomp(){
        date_default_timezone_set('Asia/Jakarta'); 
        $date = date_create(date('Y-m-d H:i:s'));
        date_add($date, date_interval_create_from_date_string('-7 hours'));
        $tgl = date_format($date, 'Y-m-d H:i:s'); 
        $id  =$this->session->userdata('id');
        $tahun = date('Y');
        // var_dump($tahun);die;

        $tipe_1 = $this->input->post('tipe_1');
        if ($tipe_1 == 1){
            $status = '2';
        }else{
            $status = '1';
        }

        $post['branch_name']        = $this->input->post('branch');
        $post['nama_comp']          = $this->input->post('s_branch');
        $post['kode_comp']          = $this->input->post('kode_comp');
        $post['nocab']              = $this->input->post('nocab');
        $post['naper']              = $this->input->post('nocab');
        $post['sub']                = $this->input->post('sub');
        $post['group_repl']         = $this->input->post('group_r');
        $post['status']             = $status;
        $post['customerid']         = $this->input->post('cust_id');
        $post['urutan']             = $this->input->post('urutan');
        $post['last_updated']       =date('Y-m-d H:i:s');
        $post['last_updated_by']    =$id;
        // var_dump($post);die;
        $proses = $this->db->insert('mpm.tbl_tabcomp',$post);
        if ($proses){
            $dpost['kode_comp']      = $this->input->post('kode_comp');
            $dpost['nocab']          = $this->input->post('nocab');
            $dpost['tahun']          = $tahun;
            $dpost['status']         = '1';

            $this->db->insert('db_dp.t_dp',$dpost);
        }

        redirect('master_tabcomp/tabcomp/');
    }

    public function edit_tabcomp(){
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
        $id  =$this->session->userdata('id');
        $id_tabcomp = $this->input->post('tabcomp_id');
        $kodecomp = $this->input->post('kodecomp_id');
        $nocab = $this->input->post('nocab_id');
        $tahun = date('Y');
        // var_dump($nocab, $kodecomp);die;

        $post['branch_name']        = $this->input->post('branch');
        $post['nama_comp']          = $this->input->post('s_branch');
        $post['kode_comp']          = $this->input->post('kode_comp');
        $post['nocab']              = $this->input->post('nocab');
        $post['naper']              = $this->input->post('nocab');
        $post['sub']                = $this->input->post('sub');
        $post['group_repl']         = $this->input->post('group_r');
        $post['customerid']         = $this->input->post('cust_id');
        $post['urutan']             = $this->input->post('urutan');
        $post['last_updated']       =date('Y-m-d H:i:s');
        $post['last_updated_by']    =$id;

        $this->db->where('id',$id_tabcomp);
        $proses= $this->db->update('mpm.tbl_tabcomp',$post);

        if ($proses){
            $dpost['kode_comp']      = $this->input->post('kode_comp');
            $dpost['nocab']          = $this->input->post('nocab');
            $dpost['tahun']          = $tahun;
            $dpost['status']         = '1';

            $this->db->where('kode_comp',$kodecomp);
            $this->db->where('tahun',$tahun);
            $this->db->where('nocab',$nocab);
            $this->db->update('db_dp.t_dp',$dpost);
        }
        redirect('master_tabcomp/tabcomp/');
    }
    
    public function activer_tabcomp($flag,$id)
    {
        $sql='update mpm.tbl_tabcomp set active=? where id=?';
        $this->db->trans_begin();
        $this->db->query($sql,array($flag,$id));
        $this->db->trans_complete();
        if($this->db->trans_status()===FALSE)
        {
            $this->db->trans_rollback();
        }
        else
        {
            $this->db->trans_commit();
        }
        redirect('master_tabcomp/tabcomp/');
    }

    public function activer_stok($flag,$id)
    {
        $sql='update mpm.tbl_tabcomp set stok=? where id=?';
        $this->db->trans_begin();
        $this->db->query($sql,array($flag,$id));
        $this->db->trans_complete();
        if($this->db->trans_status()===FALSE)
        {
            $this->db->trans_rollback();
        }
        else
        {
            $this->db->trans_commit();
        }
        redirect('master_tabcomp/tabcomp/');
    }

    public function activer_api($flag,$id)
    {
        $sql='update mpm.tbl_tabcomp set status_api=? where id=?';
        $this->db->trans_begin();
        $this->db->query($sql,array($flag,$id));
        $this->db->trans_complete();
        if($this->db->trans_status()===FALSE)
        {
            $this->db->trans_rollback();
        }
        else
        {
            $this->db->trans_commit();
        }
        redirect('master_tabcomp/tabcomp/');
    }

    public function activer_jawa($flag,$id)
    {
        $sql='update mpm.tbl_tabcomp set jawa=? where id=?';
        $this->db->trans_begin();
        $this->db->query($sql,array($flag,$id));
        $this->db->trans_complete();
        if($this->db->trans_status()===FALSE)
        {
            $this->db->trans_rollback();
        }
        else
        {
            $this->db->trans_commit();
        }
        redirect('master_tabcomp/tabcomp/');
    }

    public function activer_cluster($flag,$id)
    {
        $sql='update mpm.tbl_tabcomp set status_cluster=? where id=?';
        $this->db->trans_begin();
        $this->db->query($sql,array($flag,$id));
        $this->db->trans_complete();
        if($this->db->trans_status()===FALSE)
        {
            $this->db->trans_rollback();
        }
        else
        {
            $this->db->trans_commit();
        }
        redirect('master_tabcomp/tabcomp/');
    }

    public function activer_repl($flag,$id)
    {
        $sql='update mpm.tbl_tabcomp set active_repl=? where id=?';
        $this->db->trans_begin();
        $this->db->query($sql,array($flag,$id));
        $this->db->trans_complete();
        if($this->db->trans_status()===FALSE)
        {
            $this->db->trans_rollback();
        }
        else
        {
            $this->db->trans_commit();
        }
        redirect('master_tabcomp/tabcomp/');
    }

    public function activer_grouprepl($flag,$id)
    {
        $sql='update mpm.tbl_tabcomp set status_group_repl=? where id=?';
        $this->db->trans_begin();
        $this->db->query($sql,array($flag,$id));
        $this->db->trans_complete();
        if($this->db->trans_status()===FALSE)
        {
            $this->db->trans_rollback();
        }
        else
        {
            $this->db->trans_commit();
        }
        redirect('master_tabcomp/tabcomp/');
    }
}