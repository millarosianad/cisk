<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_menu extends CI_Model 
{
    public function get_strukur(){
        $id = $this->session->userdata('id');
        date_default_timezone_set('Asia/Jakarta');        
		$created_date='"'.date('Y-m-d H:i:s').'"';

        $query = "
            select *
            from db_menu.t_menu_temp a 
            where a.userid = $id
        ";
        $cek_kondisi = $this->db->query($query)->num_rows();
        if ($cek_kondisi) {
            return 1;
        }else{

            $sql = "
            select 	a.id_menu,b.nama_menu,b.parent,b.target, c.id_label, c.nama_label
            from 	db_menu.t_akses a LEFT JOIN db_menu.t_menu b 
                        on a.id_menu = b.id_menu left join db_menu.t_label c
                        on b.id_label = c.id_label
            where   a.userid = $id and b.`status` = 1 and a.status = 1
            order by id_label
            ";

            $proses = $this->db->query($sql)->result();
            foreach ($proses as $a) {
                $id_menu = $a->id_menu;
                $nama_menu = $a->nama_menu;
                $parent = $a->parent;
                $target = $a->target;
                $id_label = $a->id_label;
                $nama_label = $a->nama_label;

                $this->db->query("insert into db_menu.t_menu_temp select $id_menu,$id_label,'$nama_menu',$parent,'$target',4,$id,$created_date");            

                $sql = "
                select 	a.id_menu, a.nama_menu, a.parent, a.target
                from 	db_menu.t_menu a
                where   a.id_menu = $parent
                order by id_label
                ";
                $proses = $this->db->query($sql)->result();
                foreach ($proses as $a) {
                    $id_menu = $a->id_menu;
                    $nama_menu = $a->nama_menu;
                    $parent = $a->parent;
                    $target = $a->target;          

                    if ($parent == null) {
                        $this->db->query("insert into db_menu.t_menu_temp select $id_menu,$id_label,'$nama_menu',0,'$target',2,$id,$created_date");
                    }else{

                        $this->db->query("insert into db_menu.t_menu_temp select $id_menu,$id_label,'$nama_menu',$parent,'$target',3,$id,$created_date");

                        $sql = "
                        select 	a.id_menu, a.nama_menu, a.parent, a.target
                        from 	db_menu.t_menu a
                        where   a.id_menu = $parent
                        order by id_label
                        ";
                        $proses = $this->db->query($sql)->result();
                        foreach ($proses as $a) {
                            $id_menu = $a->id_menu;
                            $nama_menu = $a->nama_menu;
                            $this->db->query("insert into db_menu.t_menu_temp select $id_menu,$id_label,'$nama_menu',$parent,'$target',2,$id,$created_date");
                        }
                    }                
                }    
            }
            return 1;
        }
    }

    public function get_label(){
        $id = $this->session->userdata('id');
        $sql = " 
        select 	a.id_akses, a.userid, a.id_menu, b.nama_menu, c.id_label, c.nama_label, b.target
        from 	db_menu.t_akses a LEFT JOIN db_menu.t_menu b
                    on a.id_menu = b.id_menu LEFT JOIN db_menu.t_label c
                    on b.id_label = c.id_label
        where   a.`status` = 1 and b.`status` = 1 and c.`status` = 1 and a.userid = $id
        GROUP BY c.id_label
        limit 100
        ";
        // echo "<pre>";
        // print_r($sql);
        // echo "</pre>";
        $hasil = $this->db->query($sql)->result();
        if ($hasil) {
            return $hasil;
        }else{
            return array();
        }
    }
    
    public function get_group_label(){
        $id = $this->session->userdata('id');
        $sql = " 
        SELECT a.label
        FROM mpm.menu a INNER JOIN mpm.menudetail b 
                ON a.id = b.menuid LEFT JOIN mpm.groupmenu c on a.groupid = c.id
        WHERE b.userid = '$id' AND a.active = 1 and a.status_bertingkat is null
        GROUP BY a.label
        ORDER BY a.label desc
        ";
        $hasil = $this->db->query($sql)->result();
        if ($hasil) {
            return $hasil;
        }else{
            return array();
        }
    }

    public function get_group_menu(){
        $id = $this->session->userdata('id');
        $sql = " 
        SELECT a.id, a.menuview, a.target, a.groupname, a.groupid,c.icon, a.label
        FROM mpm.menu a INNER JOIN mpm.menudetail b 
                ON a.id = b.menuid LEFT JOIN mpm.groupmenu c on a.groupid = c.id
        WHERE b.userid = '$id' AND a.active = 1 and a.status_bertingkat is null
        GROUP BY groupname
        ORDER BY a.label desc, a.groupname,a.menuview
        ";
        // echo "<pre>";
        // print_r($sql);
        // echo "</pre>";
        $hasil = $this->db->query($sql)->result();
        if ($hasil) {
            return $hasil;
        }else{
            return array();
        }
    }

    

}