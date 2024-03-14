<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_verifikasi_harga extends CI_Model
{

	public function input_data_verifikasi($data){

        $id = $this->session->userdata('id');
        $tahun = $data['tahun'];
        $bulan = $data['bulan'];

        // query delete mpm t_harga_temp
        $this->db->query("delete from db_temp.t_harga_temp where id = $id");

        // query insert mpm t_harga_temp
        $this->db->query("
                            insert into db_temp.t_harga_temp
                            select kodeprod, h_dp, $id
                            FROM
                            (
                                    select a.kodeprod, b.h_dp
                                    from mpm.tabprod a LEFT JOIN mpm.prod_detail b
                                    on a.kodeprod = b.kodeprod
                                    where   tgl=(
                                                                select  max(tgl) 
                                                                from    mpm.prod_detail c
                                                                where   c.kodeprod=a.kodeprod
                                    )
                                    ORDER BY kodeprod
                            )a ");

                     // query delete mpm t_verifikasi_harga_temp
        $this->db->query("delete from db_temp.t_verifikasi_harga_temp where id = $id");

        // query insert mpm t_verifikasi_harga_temp
        $sql = "
                insert into db_temp.t_verifikasi_harga_temp
                select a.kode, b.branch_name, b.nama_comp, a.kodeprod, c.namaprod, harga, d.h_dp, $id
                FROM
                (
                            select concat(kode_comp,nocab) as kode, a.kodeprod, a.HARGA
                            from data$tahun.fi a
                            where bulan = $bulan
                            GROUP BY kode, kodeprod, harga
                            ORDER BY kodeprod
                )a LEFT JOIN 
                (
                    select concat(kode_comp,nocab) as kode, branch_name, nama_comp, urutan
                    from mpm.tbl_tabcomp a
                    where a.`status` = 1
                    GROUP BY concat(kode_comp,nocab)
                )b 	on a.kode = b.kode LEFT JOIN mpm.tabprod c 
                        on a.kodeprod = c.KODEPROD LEFT JOIN
                (
                    select kodeprod, h_dp
                    from db_temp.t_harga_temp a
                    where a.id = $id
                
                )d on a.kodeprod = d.kodeprod
                ORDER BY urutan, kodeprod, harga

                ";

                $proses = $this->db->query($sql);
                if ($proses) {
        
                    $sql = "select * from db_temp.t_verifikasi_harga_temp where id = $id";
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
}