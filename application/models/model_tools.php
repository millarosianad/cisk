<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_tools extends CI_Model
{
    public function get_rasio_harga_jual($data){
        $userid = $this->session->userdata('id');        
        $created_date = $data['created_date'];
        $from = $data['from'];
        $to = $data['to'];
        $tahun_from = $data['tahun_from'];
        $tahun_to = $data['tahun_to'];
        $kodeprod = $data['kodeprod'];
        $created_date = $data['created_date'];

        // echo "<pre><br><br><br>";
        // echo "userid : ".$userid."<br>";
        // echo "created_date : ".$created_date."<br>";
        // echo "from : ".$from."<br>";
        // echo "to : ".$to."<br>";
        // echo "tahun_from : ".$tahun_from."<br>";
        // echo "tahun_to : ".$tahun_to."<br>";
        // echo "kodeprod : ".$kodeprod."<br>";
        // echo "</pre>";

        if ($tahun_from - $tahun_to == 0) { // jika di tahun yang sama      
            $sql = "
            insert into db_temp.t_temp_rasio_harga_jual
            select a.kode, c.branch_name, c.nama_comp, faktur, tgl_faktur, a.kodeprod, banyak, harga, harga_2, b.h_jual_dp_retail, b.tgl_naik_harga, (a.harga - b.h_jual_dp_retail) as selisih_harga, (a.harga_2 - b.h_jual_dp_retail) as selisih_harga_2, 
            c.urutan, $created_date, $userid 
            from
            (
                select concat(KDDOKJDI, NODOKJDI) as faktur, concat(a.kode_comp,a.nocab) as kode, a.kodeprod, a.banyak, a.harga, a.tot1, date_format(concat(a.thndok,'-',a.blndok, '-', a.hrdok),'%Y-%m-%d') as tgl_faktur, (a.tot1 / a.banyak) as harga_2
                from data$tahun_from.fi a
                where (date_format(concat(a.thndok,'-',a.blndok, '-', a.hrdok),'%Y-%m-%d') between '$from' and '$to') and a.kodeprod in ($kodeprod)
                union all
                select concat(KDDOKJDI, NODOKJDI) as faktur, concat(a.kode_comp,a.nocab) as kode, a.kodeprod, a.banyak, a.harga, a.tot1, date_format(concat(a.thndok,'-',a.blndok, '-', a.hrdok),'%Y-%m-%d') as tgl_faktur, (a.tot1 / a.banyak) as harga_2
                from data$tahun_from.ri a
                where (date_format(concat(a.thndok,'-',a.blndok, '-', a.hrdok),'%Y-%m-%d') between '$from' and '$to') and a.kodeprod in ($kodeprod)

            )a LEFT JOIN (
                select a.kodeprod, a.h_jual_dp_retail, a.tgl_naik_harga, a.site_code
                from mpm.prod_detail_dp a
                where a.tgl_naik_harga = (
                    select max(b.tgl_naik_harga)
                    from mpm.prod_detail_dp b
                    where b.kodeprod in ($kodeprod) and b.kodeprod = a.kodeprod and b.tgl_naik_harga < '$to'
                ) and a.kodeprod in ($kodeprod)
            )b on a.kodeprod = b.kodeprod and a.kode = b.site_code LEFT JOIN
            (
                select concat(a.kode_comp, a.nocab) as kode, a.branch_name, a.nama_comp, a.urutan
                from mpm.tbl_tabcomp a
                where a.status = 1
                GROUP BY concat(a.kode_comp, a.nocab)	
            )c on a.kode = c.kode 
            ORDER BY c.urutan asc
            ";
        }else{
            $message = "Maaf, untuk saat ini belum memungkinkan penarikan lebih dari 1 tahun. Terima kasih.";
            echo "<script type='text/javascript'>alert('$message');
            window.location.href = 'omzet';
            </script>";
        }
        
        // echo "<pre>";
        // print_r($sql);
        // echo "</pre>";

        $proses = $this->db->query($sql);
        $sql_tampil = "select * from db_temp.t_temp_rasio_harga_jual a where a.created_by = $userid and a.created_date = (select max(b.created_date) from db_temp.t_temp_rasio_harga_jual b where a.created_by = $userid)";
        $proses_tampil = $this->db->query($sql_tampil)->result();
        if ($proses_tampil) {
            return $proses_tampil;
        }else{
            return array();
        }
        
    }

}