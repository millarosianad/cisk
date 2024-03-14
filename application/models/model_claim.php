<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require 'vendor/autoload.php';
use GuzzleHttp\Client;
use GuzzleHttp\Psr7;
use GuzzleHttp\Exception\RequestException;

class Model_claim extends CI_Model {

	public function proses_claim($data)
    {
        $user=$this->session->userdata('username');
        if ( function_exists( 'date_default_timezone_set' ) )
        date_default_timezone_set('Asia/Jakarta');        

        /* ---------DEFINISI VARIABEL----------------- */        
        $dp =  implode(", ", $data['nocab']); 
        // $filename = implode(", ", $data['filenames']);   

        $jumlahDp = explode(", ",$dp);
        // echo count($jumlahDp);        
        $data = array(
            'principal' => $data['principal'] ,
            'no_surat_program' => $data['no_surat_program'],
            'no_ap' => $data['no_ap'],
            'nama_program' => $data['nama_program'],
            'tipe_claim' => $data['tipe_claim'],
            'from' => date_format(date_create($data['from']),"Y/m/d"),
            'to' => date_format(date_create($data['to']),"Y/m/d"),
            'area' => $dp,
            'filename' => $data['filenames'],
            'folder' => $data['folder'],
            'created_by' => $this->session->userdata('id'),
            'created_date' => date('Y-m-d H:i:s'),
            'created_username' => $data['username'],
            );
            
        $hasil = $this->db->insert('mpm.tbl_monitor_claim', $data); 
        if($hasil)
        { 
            echo "<script>alert('Data sudah masuk. Terima kasih');document.location='view_claim'</script>";       
        } else {
            return array();
        }

    }

    public function proses_program($data)
    {

        // echo "kodeprod_beli1 : ".implode(", ", $data['kodeprod_beli1']);
        // echo "unit_beli1 : ".$data['unit_beli1'];
        // echo "value_beli1 : ".$data['value_beli1'];

        // echo "kodeprod_beli2 : ".implode(", ", $data['kodeprod_beli2']);
        // echo "unit_beli2 : ".$data['unit_beli2'];
        // echo "value_beli2 : ".$data['value_beli2'];

        // echo "kodeprod_beli3 : ".implode(", ", $data['kodeprod_beli3']);
        // echo "unit_beli3 : ".$data['unit_beli3'];
        // echo "value_beli3 : ".$data['value_beli3'];

        $user=$this->session->userdata('username');
        if ( function_exists( 'date_default_timezone_set' ) )
        date_default_timezone_set('Asia/Jakarta');        
     
        $id_claim = $data['id'];
        $data = array(
            'id_claim'          => $data['id'],
            'kodeprod_beli1'    => implode(", ", $data['kodeprod_beli1']),
            'unit_beli1'        => $data['unit_beli1'],
            'value_beli1'       => $data['value_beli1'],
            'kodeprod_beli2'    => implode(", ", $data['kodeprod_beli2']),
            'unit_beli2'        => $data['unit_beli2'],
            'value_beli2'       => $data['value_beli2'],
            'kodeprod_beli3'    => implode(", ", $data['kodeprod_beli3']),
            'unit_beli3'        => $data['unit_beli3'],
            'value_beli3'       => $data['value_beli3'],
            'kodeprod_bonus'    => implode(", ", $data['kodeprod_bonus']),
            'unit_bonus'        => $data['unit_bonus'],
            'value_bonus'       => $data['value_bonus'],
            'keterangan'        => $data['keterangan'],
            'status_kelipatan'  => $data['status_kelipatan'],
            'status_faktur'     => $data['status_faktur'],
            'created_by'        => $this->session->userdata('id'),
            'created_date'      => date('Y-m-d H:i:s'),
        );
        $hasil = $this->db->insert('mpm.tbl_program_claim', $data); 
        if($hasil)
        { 
            echo "<script>alert('Data tersimpan');document.location='view_program/$id_claim'</script>";       
        } else {
            return array();
        }
    }

    public function proses_hitung($data)
    {
        $id = $this->session->userdata('id');
        if ( function_exists( 'date_default_timezone_set' ) )
        date_default_timezone_set('Asia/Jakarta');  
        
        $from = $data['from'];
        $to = $data['to'];
        $kodeprod_beli1 = $data['kodeprod_beli1'] ;
        $unit_beli1 = $data['unit_beli1'] ;
        $value_beli1 = $data['value_beli1'] ;
        $kodeprod_beli2 = $data['kodeprod_beli2'] ;
        $unit_beli2 = $data['unit_beli2'] ;
        $value_beli2 = $data['value_beli2'] ;
        $kodeprod_beli3 = $data['kodeprod_beli3'] ;
        $unit_beli3 = $data['unit_beli3'] ;
        $value_beli3 = $data['value_beli3'] ;

        $kodeprod_bonus = $data['kodeprod_bonus'] ;
        $unit_bonus = $data['unit_bonus'] ;
        $value_bonus = $data['value_bonus'] ;

        $max_bonus_unit_faktur = $data['max_bonus_unit_faktur'] ;
        $max_bonus_value_faktur = $data['max_bonus_value_faktur'] ;
        $max_bonus_unit_outlet = $data['max_bonus_unit_outlet'] ;
        $max_bonus_value_outlet = $data['max_bonus_value_outlet'] ;

        $keterangan = $data['keterangan'] ;
        $status_kelipatan = $data['status_kelipatan'] ;
        $status_faktur = $data['status_faktur'] ;
        $tanggal_buat = date('Y-m-d H:i:s');

        echo "<pre>";
        echo "<br><br><br>";
        echo "from : ".$from."<br>";
        echo "to : ".$to."<br>";
        echo "kodeprod_beli1 : ".$kodeprod_beli1."<br>";
        echo "unit_beli1 : ".$unit_beli1."<br>";
        echo "value_beli1 : ".$value_beli1."<br>";
        echo "kodeprod_beli2 : ".$kodeprod_beli2."<br>";
        echo "unit_beli2 : ".$unit_beli2."<br>";
        echo "value_beli2 : ".$value_beli2."<br>";
        echo "kodeprod_beli3 : ".$kodeprod_beli3."<br>";
        echo "unit_beli3 : ".$unit_beli3."<br>";
        echo "value_beli3 : ".$value_beli3."<br>";
        echo "kodeprod_bonus : ".$kodeprod_bonus."<br>";
        echo "unit_bonus : ".$unit_bonus."<br>";
        echo "value_bonus : ".$value_bonus."<br>";
        echo "max_bonus_unit_faktur : ".$max_bonus_unit_faktur."<br>";
        echo "max_bonus_value_faktur : ".$max_bonus_value_faktur."<br>";
        echo "max_bonus_unit_outlet : ".$max_bonus_unit_outlet."<br>";
        echo "max_bonus_value_outlet : ".$max_bonus_value_outlet."<br>";
        echo "keterangan : ".$keterangan."<br>";
        echo "status_kelipatan : ".$status_kelipatan."<br>";
        echo "status_faktur : ".$status_faktur."<br>";
        echo "</pre>";

        if ($kodeprod_beli2 == '') {
            $where2 = '';
            $produk2 ="''";
            $isi_kondisi_2 = 0;
        } else {
            $where2 = "and kodeprod2 in ($kodeprod_beli2)";
            $produk2 = "$kodeprod_beli2";
            $isi_kondisi_2 = 1;
        }

        if ($kodeprod_beli3 == '') {
            $where3 = '';
            $produk3 ="''";
            $isi_kondisi_3 = 0;
        } else {
            $where3 = "and kodeprod3 in ($kodeprod_beli3)";
            $produk3 = "$kodeprod_beli3";
            $isi_kondisi_3 = 1;
        }

        if ($unit_beli1 == 0 && $value_beli1 <> 0) {
            $jumlah1 = "a.value1";
            $nama_jumlah1 = "tot1";
            $isi1 = "$value_beli1";
            $nama_kelipatan1 = "value1";
            $isikelipatan1 = "$value_beli1";
        } elseif($value_beli1 == 0 && $unit_beli1 <> 0) {
            $jumlah1 = "a.banyak1";
            $nama_jumlah1="banyak";
            $isi1 = "$unit_beli1";
            $nama_kelipatan1 = "banyak1";
            $isikelipatan1 = "$unit_beli1";
        } else{
            $jumlah1 = "a.banyak1";
            $nama_jumlah1="banyak";
            $isi1 = "$unit_beli1";
            $nama_kelipatan1 = "banyak1";
            $isikelipatan1 = "$unit_beli1";
        }

        if ($unit_beli2 == 0 && $value_beli2 <> 0) {
            $jumlah2 = "a.value2";
            $nama_jumlah2="tot1";
            $isi2 = "$value_beli2";
            $nama_kelipatan2 = "value2";
            $isikelipatan2 = "$value_beli2";
        } elseif($value_beli2 == 0 && $unit_beli2 <> 0) {
            $jumlah2 = "a.banyak2";
            $nama_jumlah2="banyak";
            $isi2 = "$unit_beli2";
            $nama_kelipatan2 = "banyak2";
            $isikelipatan2 = "$unit_beli2";
        } else{
            $jumlah2 = "a.banyak2";
            $nama_jumlah2="banyak";
            $isi2 = "$unit_beli2";
            $nama_kelipatan2 = "banyak2";
            $isikelipatan2 = "$unit_beli2";
        }

        if ($unit_beli3 == 0 && $value_beli3 <> 0) {
            $jumlah3 = "a.value3";
            $nama_jumlah3="tot1";
            $isi3 = "$value_beli3";
            $nama_kelipatan3 = "value3";
            $isikelipatan3 = "$value_beli3";
        } elseif($value_beli3 == 0 && $unit_beli3 <> 0) {
            $jumlah3 = "a.banyak3";
            $nama_jumlah3="banyak";
            $isi3 = "$unit_beli3";
            $nama_kelipatan3 = "banyak3";
            $isikelipatan3 = "$unit_beli3";
        } else{
            $jumlah3 = "a.banyak3";
            $nama_jumlah3="banyak";
            $isi3 = "$unit_beli3";
            $nama_kelipatan3 = "banyak3";
            $isikelipatan3 = "$unit_beli3";
        }        

        if ($status_kelipatan == 1 && $status_faktur == 1) {
            $kelipatan1 = "floor($nama_kelipatan1/$isikelipatan1)";
            $kelipatan2 = "floor($nama_kelipatan2/$isikelipatan2)";
            $kelipatan3 = "floor($nama_kelipatan3/$isikelipatan1)";
            $bonus = "(if(kelipatan1 is null, 0, kelipatan1)+if(kelipatan2 is null, 0, kelipatan2)+if(kelipatan3 is null, 0, kelipatan3)*$unit_bonus)";

            $kelipatan_1 = "floor($nama_jumlah1/$isi1)";
            $kelipatan_2 = "floor($nama_jumlah2/$isi2)";
            $kelipatan_3 = "floor($nama_jumlah3/$isi3)";

            $group_by = ",faktur";

        }elseif ($status_kelipatan == 1 && $status_faktur == 0) {
            $kelipatan1 = "floor($nama_kelipatan1/$isikelipatan1)";
            $kelipatan2 = "floor($nama_kelipatan2/$isikelipatan2)";
            $kelipatan3 = "floor($nama_kelipatan3/$isikelipatan1)";
            $bonus = "(if(kelipatan1 is null, 0, kelipatan1)+if(kelipatan2 is null, 0, kelipatan2)+if(kelipatan3 is null, 0, kelipatan3)*$unit_bonus)";

            $kelipatan_1 = "floor(sum($nama_jumlah1)/$isi1)";
            $kelipatan_2 = "floor(sum($nama_jumlah2)/$isi2)";
            $kelipatan_3 = "floor(sum($nama_jumlah3)/$isi3)";

            $group_by = ",outlet";

        }elseif ($status_kelipatan == 0 && $status_faktur == 1) {
            $kelipatan1 = "floor($nama_kelipatan1/$isikelipatan1)";
            $kelipatan2 = "floor($nama_kelipatan2/$isikelipatan2)";
            $kelipatan3 = "floor($nama_kelipatan3/$isikelipatan1)";
            $bonus = "(if(kelipatan1 is null, 0, kelipatan1)+if(kelipatan2 is null, 0, kelipatan2)+if(kelipatan3 is null, 0, kelipatan3)*$unit_bonus)";

            $kelipatan_1 = "floor($nama_jumlah1/$nama_jumlah1)";
            $kelipatan_2 = "floor($nama_jumlah2/$nama_jumlah2)";
            $kelipatan_3 = "floor($nama_jumlah3/$nama_jumlah3)";

            $group_by = ",faktur";

        } else {
            $kelipatan1 = "'-'";
            $kelipatan2 = "'-'";
            $kelipatan3 = "'-'";
            $bonus = $unit_bonus;

            $kelipatan_1 = "floor(sum($nama_jumlah1)/sum($nama_jumlah1))";
            $kelipatan_2 = "floor(sum($nama_jumlah2)/sum($nama_jumlah2))";
            $kelipatan_3 = "floor(sum($nama_jumlah3)/sum($nama_jumlah3))";

            $group_by = ",outlet";
        }

        if($max_bonus_unit_faktur == '0' || $max_bonus_unit_faktur == ''){
            $bonus = "(kelipatan*$unit_bonus)";   
            echo "bonus : ".$bonus;         
        }else{
            $bonus = "if((kelipatan*1) > $max_bonus_unit_faktur, $max_bonus_unit_faktur, (kelipatan*1))";
            echo "bonus : ".$bonus;
        }        

        if($max_bonus_unit_outlet == '0' || $max_bonus_unit_outlet == ''){
            $max_bonus_unit_outlet_x = "sum(a.bonus)";
            echo "max_bonus_unit_outlet_x : ".$max_bonus_unit_outlet_x;
        }else{
            $max_bonus_unit_outlet_x = $max_bonus_unit_outlet;
            echo "max_bonus_unit_outlet_x : ".$max_bonus_unit_outlet_x;
        }

        $tahun_bulan_from = substr($from,0,7);
        $tahun_bulan_to = substr($to,0,7);

        $tahun_from = substr($from,0,4);
        $tahun_to = substr($to,0,4);

        $bulan_from = substr($from,5,2);
        $bulan_to = substr($to,5,2);
    
        if($tahun_bulan_from <> $tahun_bulan_to){
            echo "tidak bisa beda tahun dan bulan";
        }else{
                echo "nama_kelipatan1 : ".$nama_kelipatan1."<br>";
                echo "isikelipatan1 : ".$isikelipatan1."<br>";
                echo "kelipatan1 : ".$kelipatan1."<br>";
                echo "bonus : ".$bonus."<br>";
                echo "kelipatan_1 : ".$kelipatan_1."<br>";
                echo "kelipatan_2 : ".$kelipatan_2."<br>";
                echo "kelipatan_3 : ".$kelipatan_3."<br>";

                $this->db->query("delete from db_klaim.t_detail_faktur where userid = $id");
                $sql = "
                    insert into db_klaim.t_detail_faktur
                    SELECT a.kode, b.branch_name, b.nama_comp, a.faktur, a.outlet, a.unit, a.value, a.kelipatan, a.kelompok, $id
                    from
                    (
                        select kode, faktur, outlet, sum(banyak) as unit, sum(tot1) as value, $kelipatan_1 as kelipatan, 'A' as kelompok, $id
                        FROM
                        (
                            select concat(KDDOKJDI, NODOKJDI) as faktur, concat(kode_comp,nocab) as kode, concat(kode_comp, kode_lang) outlet, kodeprod, banyak, tot1
                            from data$tahun_from.fi
                            where bulan = $bulan_from  and kodeprod in ($kodeprod_beli1) 
                            union ALL
                            select concat(KDDOKJDI, NODOKJDI) as faktur, concat(kode_comp,nocab) as kode, concat(kode_comp, kode_lang) outlet, kodeprod, banyak, tot1
                            from data$tahun_from.ri
                            where bulan = $bulan_from  and kodeprod in ($kodeprod_beli1) 
                        )a where $nama_jumlah1 >= $isi1
                        GROUP BY kode $group_by
                        union ALL
                        select kode, faktur, outlet, sum(banyak), sum(tot1), $kelipatan_2, 'B', $id
                        FROM
                        (
                            select concat(KDDOKJDI, NODOKJDI) as faktur, concat(kode_comp,nocab) as kode, concat(kode_comp, kode_lang) outlet, kodeprod, banyak, tot1
                            from data$tahun_from.fi
                            where bulan = $bulan_from  and kodeprod in ($produk2)
                            union ALL
                            select concat(KDDOKJDI, NODOKJDI) as faktur, concat(kode_comp,nocab) as kode, concat(kode_comp, kode_lang) outlet, kodeprod, banyak, tot1
                            from data$tahun_from.ri
                            where bulan = $bulan_from  and kodeprod in ($produk2)
                        )a where $nama_jumlah2 >= $isi2
                        GROUP BY kode $group_by
                        union ALL
                        select kode, faktur, outlet, sum(banyak), sum(tot1), $kelipatan_3, 'C', $id
                        FROM
                        (
                            select concat(KDDOKJDI, NODOKJDI) as faktur, concat(kode_comp,nocab) as kode, concat(kode_comp, kode_lang) outlet, kodeprod, banyak, tot1
                            from data$tahun_from.fi
                            where bulan = $bulan_from  and kodeprod in ($produk3)
                            union ALL
                            select concat(KDDOKJDI, NODOKJDI) as faktur, concat(kode_comp,nocab) as kode, concat(kode_comp, kode_lang) outlet, kodeprod, banyak, tot1
                            from data$tahun_from.ri
                            where bulan = $bulan_from  and kodeprod in ($produk3)
                        )a where $nama_jumlah3 >= $isi3
                        GROUP BY kode $group_by
                        ORDER BY faktur
                    )a LEFT JOIN
                    (
                        select concat(a.kode_comp, a.nocab) as kode, a.branch_name, a.nama_comp
                        from mpm.tbl_tabcomp a
                        where a.`status` = 1
                        GROUP BY concat(a.kode_comp, a.nocab)
                    )b on a.kode = b.kode
                ";
                $proses = $this->db->query($sql);
                echo "<pre>";
                print_r($sql);
                echo "</pre>";

                $this->db->query("delete from db_klaim.t_summary_faktur where userid = $id");
                $total_kondisi = (int)1 + $isi_kondisi_2 + $isi_kondisi_3;
                $sql = "
                    insert into db_klaim.t_summary_faktur
                    select a.kode, b.branch_name,b.nama_comp, faktur, outlet, total_unit, total_value, kelipatan, $bonus as bonus,$id
                    FROM
                    (
                        select a.kode, faktur, count(a.faktur) as jumlah_faktur, a.outlet, sum(a.total_unit) as total_unit, sum(a.total_value) as total_value, a.kelompok, max(a.kelipatan) as kelipatan
                        from db_klaim.t_detail_faktur a
                        where a.userid = $id
                        GROUP BY kode, faktur
                        order BY faktur
                    )a LEFT JOIN
                    (
                        select concat(a.kode_comp, a.nocab) as kode, a.branch_name, a.nama_comp
                        from mpm.tbl_tabcomp a
                        where a.`status` = 1
                        GROUP BY concat(a.kode_comp, a.nocab)
                    )b on a.kode = b.kode
                    where jumlah_faktur = $total_kondisi
                ";
                $proses = $this->db->query($sql);
                echo "<pre>";
                print_r($sql);
                echo "</pre>";

                $this->db->query("delete from db_klaim.t_summary_report where userid = $id");
                $sql = "
                insert into db_klaim.t_summary_report
                select a.kode, b.branch_name, b.nama_comp, sum(bonus), '$keterangan' as nama_program, '$from' as `from`, '$to' as `to`, urutan,b.sub,$id, '$tanggal_buat'
                from db_klaim.t_summary_faktur a LEFT JOIN
                (
                    select concat(a.kode_comp,a.nocab) as kode, a.branch_name, a.nama_comp, a.urutan,a.sub
                    from mpm.tbl_tabcomp a
                    where a.`status` = 1
                    GROUP BY concat(a.kode_comp,a.nocab)
                )b on a.kode = b.kode
                where a.userid = $id
                group by a.kode
                ";
                $proses = $this->db->query($sql);
                echo "<pre>";
                print_r($sql);
                echo "</pre>";

                $this->db->query("delete from db_klaim.t_branch_report where userid = $id");
                $sql = "
                insert into db_klaim.t_branch_report
                select a.kode, if(b.branch_name is null,a.branch_name,b.branch_name), b.nama_comp, sum(a.total_bonus), a.nama_program, a.from,a.to, a.urutan,b.sub,a.userid, '$tanggal_buat'
                from db_klaim.t_summary_report a LEFT JOIN
                (
                    select concat(a.kode_comp,a.nocab) as kode, a.branch_name, a.nama_comp, a.urutan,a.sub
                    from mpm.tbl_tabcomp a
                    where a.`status` = 2
                )b on a.sub = b.sub
                where a.userid = $id
                group by a.sub
                ORDER BY a.urutan
                ";
                $proses = $this->db->query($sql);
                echo "<pre>";
                print_r($sql);
                echo "</pre>";

                $this->db->query("delete from db_klaim.t_detail_report where userid = $id");
                $sql = "
                insert into db_klaim.t_detail_report
                select a.kode,a.branch_name,a.nama_comp,a.outlet,
				b.nama_lang,sum(a.total_unit) as total_unit,sum(a.total_value) as total_value,count(a.faktur) as jumlah_faktur, if(sum(a.bonus) >$max_bonus_unit_outlet_x,$max_bonus_unit_outlet_x,sum(a.bonus)) as jumlah_bonus, '$keterangan' as nama_program, '$from' as `from`, '$to' as `to`, $id,'$tanggal_buat'
                from db_klaim.t_summary_faktur a LEFT JOIN
                (
                    select a.kode, a.kode_lang, a.nama_lang
                    from mpm.tbl_bantu_pelanggan_2020 a
                )b on a.outlet = b.kode_lang
                GROUP BY a.kode, a.outlet
                ";
                $proses = $this->db->query($sql);
                echo "<pre>";
                print_r($sql);
                echo "</pre>";             
        }

        $sql_summary = $this->db->query("select * from db_klaim.t_summary_report where userid = $id order by urutan")->result();
        // $sql_detail = $this->db->query("select * from db_klaim.t_detail_report where userid = $id order by urutan")->result();

        if ($sql_summary) {
            return $sql_summary;
        }else{
            return array();
        }
    }

    public function proses_rekap($data)
    {
        $user = $this->session->userdata('username');
        $id = $this->session->userdata('id');
        if ( function_exists( 'date_default_timezone_set' ) )
        date_default_timezone_set('Asia/Jakarta');  
        
        $idProgram = $data['idProgram'];
        // echo "idProgram : ".$idProgram;

        $sql = "delete from mpm.tbl_rekap_claim where userid = $id";
        $hasil = $this->db->query($sql);

        $sql = "
        insert into mpm.tbl_rekap_claim
        select 	a.id, a.id_ref, b.no_surat_program, b.nama_program,
                c.keterangan,
                d.nama_comp, a.keterangan ket, 
                a. tgl_claim_dr_dp, a.status_verifikasi, 
                a.ket_penggantian, a.tgl_penggantian, 
                filename_to_principal, filename_principal,$id
        from 	mpm.tbl_detail_claim a LEFT JOIN mpm.tbl_monitor_claim b
                on a.id_ref = b.id
                LEFT JOIN mpm.tbl_program_claim c
                    on c.id_claim = b.id
                LEFT JOIN
                (
                    SELECT concat(kode_comp,nocab) as kode, nama_comp, urutan
                    from mpm.tbl_tabcomp_new
                    where `status` = 1
                    GROUP BY concat(kode_comp,nocab)
                )d on a.area = d.kode
        where c.id = $idProgram
        ORDER BY d.urutan
        ";

        $proses = $this->db->query($sql);
        
        $sql = "select * from mpm.tbl_rekap_claim where userid = $id";
        $hasil = $this->db->query($sql);
        if ($hasil->num_rows() > 0) 
        {
            return $hasil->result();
        } else {
            return array();
        }
        
    }

    public function proses_claim_principal($id, $data)
    {
        $user=$this->session->userdata('username');
        if ( function_exists( 'date_default_timezone_set' ) )
        date_default_timezone_set('Asia/Jakarta');        
      
        $data = array(
            'filename_principal' => $data['filenames'],
            'ket_penggantian' => $data['keterangan'],
            'tgl_penggantian' => date('Y-m-d'),
            'penggantian_date' => date('Y-m-d H:i:s'),
            'penggantian_username' => $this->session->userdata('username'),
            'penggantian_by' => $this->session->userdata('id'),
        );
        $this->db->where("id in ($id)");
        $proses = $this->db->update('mpm.tbl_detail_claim', $data);
        if ($proses) {            
            echo "<script>alert('Data sudah masuk. Terima kasih');document.location='../view_claim_principal'</script>";
        }else{
            echo "<script>alert('Error..!!');document.location='../view_claim_principal'</script>";
        }

    }

    public function proses_edit_claim($data)
    {
        $user=$this->session->userdata('username');
        if ( function_exists( 'date_default_timezone_set' ) )
        date_default_timezone_set('Asia/Jakarta');        

        $filenames = $filename;
        $principal = $this->input->post('principal');
        $username = $this->input->post('username');  
        $no_surat_program= $this->input->post('no_surat_program');
        $no_ap= $this->input->post('no_ap');
        $nama_program = $this->input->post('nama_program');
        $tipe_claim = $this->input->post('tipe_claim');
        $from = $this->input->post('from');
        $to = $this->input->post('to');
        $nocab = $this->input->post('nocab');  
        $folder = date('Ym');

        echo "<pre>";
        echo "principal : ".$principal."<br>";
        echo "username : ".$username."<br>";
        echo "no_surat_program : ".$no_surat_program."<br>";
        echo "no_ap : ".$no_ap."<br>";
        echo "nama_program : ".$nama_program."<br>";
        echo "tipe_claim : ".$tipe_claim."<br>";
        echo "from : ".$from."<br>";
        echo "to : ".$to."<br>";
        echo "nocab : ".$nocab."<br>";
        echo "folder : ".$folder."<br>";
        
        echo "</pre>";


        $filename = implode(", ", $data['filenames']);           
        $data = array(
            'id' => $data['id'],
            'tgl_claim_dr_dp' => date('Y-m-d'),
            'status_verifikasi' => $data['status'],
            'keterangan_dr_dp' => $data['keterangan_dr_dp'],
            'created_dp_by' => $data['created_dp_by'],
            'created_dp_username' => $data['created_dp_username'],
            'created_dp_date' => date('Y-m-d H:i:s'),
            'filename' => $filename,
            'folder' => $data['folder']
        );

        // $this->db->where('id', $data['id']);
        // $proses = $this->db->update('mpm.tbl_detail_claim', $data);
        // var_dump($proses);
        // if ($proses) {
        //     // echo "xx";
        //     echo "<script>alert('Data sudah masuk. Terima kasih');document.location='../view_claim_dp'</script>";
        // }else{
        //     echo "<script>alert('Error..!!');document.location='view_claim_dp'</script>";
        // }
    }

    public function proses_update_claim($data)
    {
        $user=$this->session->userdata('username');
        if ( function_exists( 'date_default_timezone_set' ) )
        date_default_timezone_set('Asia/Jakarta');        
         
        $data = array(
            'id' => $data['id'],
            'tgl_claim_dr_dp' => date('Y-m-d'),
            'status_verifikasi' => $data['status'],
            'keterangan_dr_dp' => $data['keterangan_dr_dp'],
            'created_dp_by' => $data['created_dp_by'],
            'created_dp_username' => $data['created_dp_username'],
            'created_dp_date' => date('Y-m-d H:i:s'),
            'filename' => $data['file'],
            'folder' => $data['folder']
        );

        $this->db->where('id', $data['id']);
        $proses = $this->db->update('mpm.tbl_detail_claim', $data);
        var_dump($proses);
        if ($proses) {
            // echo "xx";
            echo "<script>alert('Data sudah masuk. Terima kasih');document.location='../view_claim_dp'</script>";
        }else{
            echo "<script>alert('Error..!!');document.location='view_claim_dp'</script>";
        }
    }

    public function proses_status_claim($data)
    {
        $user=$this->session->userdata('username');
        if ( function_exists( 'date_default_timezone_set' ) )
        date_default_timezone_set('Asia/Jakarta');        

        $id = $data['id'];
        $id_ref = $data['id_ref'];
        $status = $data['status'];

      
        $data = array(
            // 'id' => $data['id'],
            'status_verifikasi' => $status,
            'verifikasi_by' => $data['verifikasi_by'],
            'verifikasi_username' => $data['verifikasi_username'],
            'verifikasi_date' => date('Y-m-d H:i:s'),
            'filename_to_principal' => $data['file'],
            'folder' => $data['folder'],
            'keterangan' => $data['keterangan']
        );

        $this->db->where('id', $id);
        $proses = $this->db->update('mpm.tbl_detail_claim', $data);
        if ($proses) {
            echo "<script>alert('Data sudah masuk. Terima kasih');document.location='../detail_claim/$id_ref'</script>";
        }else{
            echo "<script>alert('Error..!!');document.location='../detail_claim/$id_ref'</script>";
        }
    }

    public function proses_update_principal($data)
    {
        $user=$this->session->userdata('username');
        if ( function_exists( 'date_default_timezone_set' ) )
        date_default_timezone_set('Asia/Jakarta');     

        $id = implode(", ", $data['id']); 
        $data = array(
            'status_verifikasi' => '4',
            'tgl_penggantian' => date('Y-m-d'),
            'penggantian_date' => date('Y-m-d H:i:s'),
            'penggantian_username' => $this->session->userdata('username'),
            'penggantian_by' => $this->session->userdata('id'),
        );
        $this->db->where("id in ($id)");
        $proses = $this->db->update('mpm.tbl_detail_claim', $data);
        if ($proses) {            
            echo "<script>alert('Data sudah masuk. Terima kasih');document.location='../view_claim_principal'</script>";
        }else{
            echo "<script>alert('Error..!!');document.location='../view_claim_principal'</script>";
        }
    }

    public function generate_claim($id)
    {
        $cek_detail = $this->get_detail_claim($id);
        foreach($cek_detail as $key){
            $id_ref = $key->id_ref;
            var_dump($id_ref);
        }

        if ($id_ref == NULL) 
        {            
            $cek = $this->get_claim($id);
            foreach($cek as $key){
                $area = $key->area;
            }            
            $dp =  explode(", ", $area); 
            $jumlahDp = count(explode(", ",$area));
            for ( $i = 0; $i < $jumlahDp; $i++ ) {                
                $data = array(
                    'id_ref' => $id ,
                    'area' => $dp[$i],
                    'generated_by' => $this->session->userdata('id'),
                    'generated_date' => date('Y-m-d H:i:s')
                );
                $proses = $this->db->insert('mpm.tbl_detail_claim', $data);     
            }
            return 1;
               
        }else{
            return 2;
        }        
    }

    function get_claim($id)
    {
        $sql = "
            select *
            from mpm.tbl_monitor_claim
            where id = $id
        ";
        return $this->db->query($sql)->result();
    }

    function get_program($id)
    {
        $sql = "
            select *
            from mpm.tbl_program_claim
            where id_claim = $id

        ";
        // return $this->db->query($sql)->result();
        return $this->db->query($sql);
    }

    function get_program_by_bulan($id,$id_tahun)
    {
        $sql = "
            select *
            from mpm.tbl_monitor_claim
            where deleted is null and month(`from`) = $id and year(`from`) = $id_tahun
        ";
        // return $this->db->query($sql)->result();
        return $this->db->query($sql);
    }

    function get_program_by_periode($from,$to)
    {
        $sql = "
        select *
        from mpm.tbl_monitor_claim a
        where deleted is null and a.`from` = '$from' and a.`to` = '$to'
        ";
        // return $this->db->query($sql)->result();
        return $this->db->query($sql);
    }

    function get_detail_program($id_program)
    {
        $sql = "
            select *
            from mpm.tbl_program_claim a
            where a.id_claim = $id_program
        ";
        // return $this->db->query($sql)->result();
        return $this->db->query($sql);
    }

    function get_claim_all()
    {
        $sql = "
            select *
            from mpm.tbl_monitor_claim
            where deleted is null
        ";
        // return $this->db->query($sql)->result();
        return $this->db->query($sql);
    }

    public function get_namacomp($key='')
    {
        $userid=$this->session->userdata('id');
        $nocab=$this->session->userdata('nocab');
        $year = $key['year'];

        if ($nocab!='')
        {
            $sql="
            select  concat(a.kode_comp, a.nocab)kode, b.nama_comp as nama_comp
            FROM
            (
                    select  kode_comp, nocab
                    from        data$year.fi  
                    GROUP BY kode_comp
            )a INNER JOIN 
            (
                select  naper, nocab, kode_comp, nama_comp
                FROM        mpm.tbl_tabcomp_new
                WHERE       status = 1 and nocab = '$nocab'
                order by nama_comp
            )b on concat(a.kode_comp, a.nocab) = concat(b.kode_comp, b.nocab)
            ORDER BY b.nama_comp";
        }else{
                $this->db->where('id = '.'"'.$userid.'"');
                $query = $this->db->get('mpm.user');
                foreach ($query->result() as $row) {
                    $dp = $row->wilayah_nocab;
                }

                if ($dp == NULL || $dp == '' )
                {
                    $daftar_dp = '';
                } else {
                    $daftar_dp = 'and nocab in ('.$dp.')';
                }

                $sql=
                "
                select  concat(a.kode_comp, a.nocab) as kode, b.nama_comp
                from    db_dp.t_dp a INNER JOIN 
                (
                    select  naper, nocab, kode_comp, nama_comp
                    FROM    mpm.tbl_tabcomp_new
                    WHERE   status = 1 $daftar_dp
                    order by nama_comp
                )b on concat(a.kode_comp, a.nocab) = concat(b.kode_comp, b.nocab)
                where a.tahun = $year
                order by nama_comp
                ";
        }

        //$query=$this->db->query($sql,array($key));
        $query=$this->db->query($sql);
        return $query;
    }

    function getDetailProgram($id)
    {
        $sql = "
        select * from mpm.tbl_program_claim a LEFT JOIN mpm.tbl_monitor_claim b on a.id_claim = b.id 
        where a.id = $id
        ";
        // return $this->db->query($sql)->result();
        return $this->db->query($sql);
        // echo "<pre>";
        // print_r($sql);
        // echo "</pre>";
    }

    public function getProduct($supp)
    {    
        $hasil = $this->db->query("select kodeprod,namaprod from mpm.tabprod where supp = '$supp' order by namaprod");
        // echo "supp model : ".$supp;
        return $hasil;    
    }

    public function getProductDeltomed($group)
    {    
        $hasil = $this->db->query("select kodeprod,namaprod from mpm.tabprod where grup = '$group' order by namaprod");
        // echo "supp model : ".$supp;
        return $hasil;    
    }

    public function get_program_detail($id)
    {    
        $sql = "
        select 	a.id, a.id_claim, a.kodeprod_beli1, a.unit_beli1, a.value_beli1,
                a.kodeprod_beli2, a.unit_beli2, a.value_beli2,
                a.kodeprod_beli3, a.unit_beli3, a.value_beli3,
                a.kodeprod_bonus, 
                a.unit_bonus, a.value_bonus,a.keterangan, b.principal, 
                a.status_kelipatan, a.status_faktur
        from 	mpm.tbl_program_claim a INNER JOIN mpm.tbl_monitor_claim b on a.id_claim = b.id
        where 	a.id = $id
        ";

        $hasil = $this->db->query($sql);
        // echo "supp model : ".$supp;
        return $hasil;    
    }

    function get_detail_claim($id)
    {
        $sql = "
        select 	a.id, a.id_ref id_ref, b.no_surat_program, 
                b.no_ap, b.nama_program, b.`from`, b.`to`, 
                a.tgl_claim_dr_dp, a.keterangan_dr_dp, a.created_dp_username,
                a.created_dp_date, c.nama_comp, b.principal,a.keterangan
        from 	mpm.tbl_detail_claim a INNER JOIN mpm.tbl_monitor_claim b on a.id_ref = b.id
                LEFT JOIN
                (
                    select concat(kode_comp, nocab) as kode, nama_comp
                    from mpm.tbl_tabcomp_new
                    GROUP BY concat(kode_comp, nocab)
                )c on a.area = c.kode
        where  a.id_ref = $id
        ";
        // echo "<pre>";
        // print_r($sql);
        // echo "</pre>";
        return $this->db->query($sql)->result();
    }

    function get_detail_claim_untuk_ubah($id)
    {
        $sql = "
        select 	a.id, a.id_ref id_ref, b.no_surat_program, 
                b.no_ap, b.nama_program, b.`from`, b.`to`, 
                a.tgl_claim_dr_dp, a.keterangan_dr_dp, a.created_dp_username,
                a.created_dp_date, c.nama_comp, b.principal,a.keterangan
        from 	mpm.tbl_detail_claim a INNER JOIN mpm.tbl_monitor_claim b on a.id_ref = b.id
                LEFT JOIN
                (
                    select concat(kode_comp, nocab) as kode, nama_comp
                    from mpm.tbl_tabcomp_new
                    GROUP BY concat(kode_comp, nocab)
                )c on a.area = c.kode
        where  a.id = $id
        ";
        // echo "<pre>";
        // print_r($sql);
        // echo "</pre>";
        return $this->db->query($sql)->result();
    }

    function get_claim_principal()
    {
        $sql = "
        select 	a.id, a.id_ref id_ref, b.no_surat_program, 
                b.no_ap, b.nama_program, b.`from`, b.`to`, 
                a.tgl_claim_dr_dp, a.keterangan_dr_dp, a.created_dp_username,a.filename_principal,a.filename_to_principal,
                a.created_dp_date, c.nama_comp, b.principal,status_verifikasi status, ket_penggantian, tgl_penggantian
        from 	mpm.tbl_detail_claim a INNER JOIN mpm.tbl_monitor_claim b on a.id_ref = b.id
                LEFT JOIN
                (
                    select concat(kode_comp, nocab) as kode, nama_comp
                    from mpm.tbl_tabcomp_new
                    GROUP BY concat(kode_comp, nocab)
                )c on a.area = c.kode
        where   a.status_verifikasi in (3,4,5)
        ";
        // echo "<pre>";
        // print_r($sql);
        // echo "</pre>";
        return $this->db->query($sql)->result();
    }

    function get_detail_principal($id)
    {
        // $id = $data['id'];
        $sql = "
        select 	a.id, a.area, a.filename_to_principal, a.filename_principal, 
				a.ket_penggantian, a.penggantian_date,a.tgl_penggantian,
                a.penggantian_by, a.penggantian_username, b.nama_comp,
                c.no_surat_program, c.no_surat_program, c.no_ap, c.nama_program,
                c.from, c.to, c.tipe_claim
        from 	mpm.tbl_detail_claim a LEFT JOIN
                (
                    SELECT concat(kode_comp,nocab) as kode, nama_comp
                    from mpm.tbl_tabcomp_new
                    where `status` = 1
                    GROUP BY concat(kode_comp,nocab)
                )b on a.area = b.kode
                LEFT JOIN mpm.tbl_monitor_claim c on a.id_ref = c.id
        where 	a.id = $id
        ";
        // echo "<pre>";
        // print_r($sql);
        // echo "</pre>";
        return $this->db->query($sql)->result();
    }

    function get_claim_dp()
    {
        $username = $this->session->userdata('username');
        $sql = "
            select 	a.id id, a.id_ref id_ref, b.no_surat_program no_surat_program, 
                    b.no_ap, b.nama_program, concat(b.`from`,' sd ', b.`to`) as periode, b.from,b.to,
                    a.tgl_claim_dr_dp tgl_claim_dp, a.keterangan_dr_dp ket, a.created_dp_username,a.filename file_dp,
                    a.created_dp_date,b.area, a.status_verifikasi `status`
            from 		mpm.tbl_detail_claim a INNER JOIN mpm.tbl_monitor_claim b on a.id_ref = b.id
            where 	a.area like '$username%'
        ";
        // echo "<pre>";
        // print_r($sql);
        // echo "</pre>";
        return $this->db->query($sql)->result();
    }

    public function get_log_claim()
    {
		$client = new Client();        
        try {
            $res = $client->request('GET', 'http://localhost/restapi/api/claim', [
                // 'auth' => ['X-API-KEY', '123'],
                'query' => [
                    'X-API-KEY' => '123'
                ]
            ]);
            $result = json_decode($res->getBody()->getContents(),true);

            if ($result) {
                return $result['data'];
            }else{
                return array();
            }

        } catch (RequestException $e) {
            return array();   
        }

    }    

    public function get_prinsipal()
    {
		$client = new Client();        
        $response = $client->request('GET', "http://localhost/restapi/api/prinsipal?X-API-KEY=123&jenis=claim");
		$result = json_decode($response->getBody()->getContents(),true);
		return $result;		
    }

    public function get_claim_master($periode1,$periode2,$supp)
    {
        $id=$this->session->userdata('id');
		$client = new Client();        
        try {
            $res = $client->request('GET', 'http://localhost/restapi/api/claim/master', [
                // 'auth' => ['X-API-KEY', '123'],
                'query' => [
                    'X-API-KEY' => '123',
                    'periode1' => $periode1,
                    'periode2' => $periode2,
                    'supp' => $supp
                ]
            ]);
            $result = json_decode($res->getBody()->getContents(),true);

            if ($result) {
                $sql = "delete from dbklaim.t_claim_laporan where id = $id";
                $proses = $this->db->query($sql);
                // echo "proses : ".$proses;
            }
            // var_dump($result['data']);

            foreach ($result['data'] as $key) {
                $data = array(
                    'id_ref' => $key["id_ref"],
                    'nocab' => $key["nocab"],
                    'siteid' => $key["siteid"],
                    'branch_name' => $key["branch_name"],
                    'nama_comp' => $key["nama_comp"],
                    'no_claim' => $key["no_claim"],
                    'tanggal_claim' => $key["tanggal_claim"],
                    'supplierid' => $key["supplierid"],
                    'claim_descripiton' => $key["claim_descripiton"],
                    'memoid' => $key["memoid"],
                    'tipe_trans' => $key["tipe_trans"],
                    'periode_1' => $key["periode_1"],
                    'periode_2' => $key["periode_2"],
                    'tgl_surat_klaim' => $key["tgl_surat_klaim"],
                    'no_resi' => $key["no_resi"],
                    'tgl_kirim' => $key["tgl_kirim"],
                    'tgl_akan_dibayar' => $key["tgl_akan_dibayar"],
                    'nilai_klaim' => $key["nilai_klaim"],
                    'bayar_klaim' => $key["bayar_klaim"],
                    'status_lunas' => $key["status_lunas"],
                    'status_proses' => $key["status_proses"],
                    'nilai1' => $key["nilai1"],
                    'nilai2' => $key["nilai2"],
                    'nilai3' => $key["nilai3"],
                    'ket1' => $key["ket1"],
                    'ket2' => $key["ket2"],
                    'ket3' => $key["ket3"],
                    'date_create' => $key["date_create"],
                    'user_create' => $key["user_create"],
                    'date_update' => $key["date_update"],
                    'no_pembayaran_klaim' => $key["no_pembayaran_klaim"],
                    'tgl_pembayaran' => $key["tgl_pembayaran"],
                    'no_transfer' => $key["no_transfer"],
                    'tgl_transfer' => $key["tgl_transfer"],
                    'bayar_tunai' => $key["bayar_tunai"],
                    'bayar_transfer' => $key["bayar_transfer"],
                    'id' => $id
                );
                
                $proses = $this->db->insert('dbklaim.t_claim_laporan', $data);
            }

            $sql = "
                select  a.nocab, a.siteid, a.branch_name, a.nama_comp,a.no_claim, replace(no_claim,'/','_') as no_claim2, a.tanggal_claim, a.supplierid, a.claim_descripiton, a.memoid, a.tipe_trans, a.periode_1,
                        a.periode_2, a.tgl_surat_klaim, a.no_resi, a.tgl_kirim, a.tgl_akan_dibayar, a.nilai_klaim, a.bayar_klaim, a.status_lunas,
                        a.status_proses, a.date_create, a.user_create, a.date_update, a.no_pembayaran_klaim, a.tgl_pembayaran, a.no_transfer,
                        a.tgl_transfer, a.bayar_tunai, a.bayar_transfer, a.proses_pembayaran, a.update_date, a.user_update,
                        a.nilai1, a.nilai2, a.nilai3, a.ket1, a.ket2, a.ket3 
                from    dbklaim.t_claim_laporan a where id = $id";
            $hasil = $this->db->query($sql);
            if ($hasil->num_rows() > 0) {
                return $hasil->result();
            } else {
                return array();
            }

        } catch (RequestException $e) {
            return array();   
        }
    }

    public function get_claim_by_no_claim($no_claim)
    {
        $id=$this->session->userdata('id');
		$client = new Client();        
        try {
            $res = $client->request('GET', 'http://localhost/restapi/api/claim/by_no_claim', [
                // 'auth' => ['X-API-KEY', '123'],
                'query' => [
                    'X-API-KEY' => '123',
                    'no_claim' => $no_claim
                ]
            ]);
            $result = json_decode($res->getBody()->getContents(),true);

            if ($result) {
                $sql = "delete from dbklaim.t_claim_laporan_detail_produk where id = $id";
                $proses = $this->db->query($sql);
                // echo "proses : ".$proses;
            }
            // var_dump($result['data']);

            foreach ($result['data'] as $key) {
                $data = array(
                    'id_ref' => $key["id_ref"],
                    'nocab' => $key["nocab"],
                    'siteid' => $key["siteid"],
                    'no_claim' => $key["no_claim"],
                    'productid' => $key["productid"],
                    'namaprod' => $key["namaprod"],
                    'memoid' => $key["memoid"],
                    'disc_prinsipal' => $key["disc_prinsipal"],
                    'disc_xtra' => $key["disc_xtra"],
                    'disc_cash' => $key["disc_cash"],
                    'qty_bonus' => $key["qty_bonus"],
                    'nilai_bonus' => $key["nilai_bonus"],
                    'create_date' => $key["create_date"],
                    'user_create' => $key["user_create"],
                    'update_date' => $key["update_date"],
                    'user_update' => $key["user_update"],
                    'periode_1' => $key["periode_1"],
                    'periode_2' => $key["periode_2"],
                    'nilai1' => $key["nilai1"],
                    'nilai2' => $key["nilai2"],
                    'nilai3' => $key["nilai3"],
                    'ket1' => $key["ket1"],
                    'ket2' => $key["ket2"],
                    'ket3' => $key["ket3"],
                    'id' => $id
                );
                
                $proses = $this->db->insert('dbklaim.t_claim_laporan_detail_produk', $data);
            }

            $sql = "
            select 	a.id_ref, a.nocab, a.no_claim, a.productid, a.namaprod, a.memoid, a.disc_prinsipal, a.disc_xtra, a.disc_cash, 
                    a.qty_bonus, a.nilai_bonus, a.create_date, a.user_create, a.update_date, a.user_update, 
                    a.periode_1, a.periode_2, a.nilai1, a.nilai2, a.nilai3, a.ket1, a.ket2, a.ket3
            from    dbklaim.t_claim_laporan_detail_produk a where id = $id";
            $hasil = $this->db->query($sql);
            if ($hasil->num_rows() > 0) {
                return $hasil->result();
            } else {
                return array();
            }

        } catch (RequestException $e) {
            return array();   
        }
    }

    public function get_detail_transaksi($no_claim)
    {
        $id=$this->session->userdata('id');
		$client = new Client();        
        try {
            $res = $client->request('GET', 'http://localhost/restapi/api/claim/detail_transaksi', [
                // 'auth' => ['X-API-KEY', '123'],
                'query' => [
                    'X-API-KEY' => '123',
                    'no_claim' => $no_claim
                ]
            ]);
            $result = json_decode($res->getBody()->getContents(),true);

            if ($result) {
                $sql = "delete from dbklaim.t_claim_laporan_detail_transaksi where id = $id";
                $proses = $this->db->query($sql);
            }

            foreach ($result['data'] as $key) {
                $data = array(
                    'id_ref' => $key["id_ref"],
                    'nocab' => $key["nocab"],
                    'siteid' => $key["siteid"],
                    'nama_site' => $key["nama_site"],
                    'no_klaim' => $key["no_klaim"],
                    'no_sales' => $key["no_sales"],
                    'tanggal' => $key["tanggal"],
                    'flag_transaksi' => $key["flag_transaksi"],
                    'customerid' => $key["customerid"],
                    'nama_customer' => $key["nama_customer"],
                    'alamat' => $key["alamat"],
                    'prinsipalid' => $key["prinsipalid"],
                    'prinsipal' => $key["prinsipal"],
                    'productid' => $key["productid"],
                    'nama_invoice' => $key["nama_invoice"],
                    'qty_jual' => $key["qty_jual"],
                    'qty_bonus' => $key["qty_bonus"],
                    'rp_kotor' => $key["rp_kotor"],
                    'disc_prinsipal' => $key["disc_prinsipal"],
                    'disc_xtra' => $key["disc_xtra"],
                    'disc_cod' => $key["disc_cod"],
                    'rp_netto' => $key["rp_netto"],
                    'kodeprod' => $key["kodeprod"],
                    'namaprod' => $key["namaprod"],
                    'branch_name' => $key["branch_name"],
                    'nama_comp' => $key["nama_comp"],
                    'id' => $id
                );
                
                $proses = $this->db->insert('dbklaim.t_claim_laporan_detail_transaksi', $data);
            }

            $sql = "
            select 	*
            from    dbklaim.t_claim_laporan_detail_transaksi a where id = $id";
            $hasil = $this->db->query($sql);
            if ($hasil->num_rows() > 0) {
                return $hasil->result();
            } else {
                return array();
            }

        } catch (RequestException $e) {
            return array();   
        }
    }

    function proses_log($id){

        $userid = $this->session->userdata('id');
        $sql = "select username from mpm.user where id = $userid";
        $proses= $this->db->query($sql)->result();
        foreach ($proses as $key) {
            $username = $key->username;
        }
        
        $sql = "select filename,nocab from dbklaim.t_log where id = $id";
        $proses = $this->db->query($sql)->result();
        foreach ($proses as $key) {
            $filename = substr($key->filename,0,10);
            $nocab = $key->nocab;
        }
        echo "filename : ".$filename."<br>";
        echo "filename : ".substr($filename,0,10);
        // echo "base_url : ".base_url();
        $direktori = "http://localhost/restapi/";

        // $zip = new ZipArchive;
        // $file = "C:/xampp/htdocs/restapi/assets/klaim/".$filename;

        $ambil = file_get_contents($direktori."assets/klaim/".$filename.".json");
        $decode = json_decode($ambil, true);
        $t_claim_detail_transaksi = $decode["t_claim_detail_transaksi"];
        $t_claim_master = $decode["t_claim_master"];
        $t_claim_detail = $decode["t_claim_detail"];
        $t_claim_detail_product = $decode["t_claim_detai_product"];

        // echo "t_claim_master : ".$t_claim_master;
        var_dump($t_claim_master);

        if ($t_claim_detail_transaksi == null) {
            $proses_t_claim_detail_transaksi = '0';
        }else{}

        if ($t_claim_master == null) {
            $proses_t_claim_master = '0';
        }else{}

        if ($t_claim_detail_product == null) {
            $proses_t_claim_detail_product = '0';
        }else{}

        if ($t_claim_detail == null) {
            $proses_t_claim_detail = '0';
        }else{}

        $kosongkan = "delete from dbklaim.t_claim_master_temp where nocab = '$nocab'";
        $proses_kosong = $this->db->query($kosongkan);

        foreach ($t_claim_master as $key) 
        {
            $data = array(
                "nocab" => $nocab,
                "siteid" => $key['siteid'],
                "no_claim" => $key['no_claim'],
                "tanggal_claim" => $key['tanggal_claim'],
                "supplierid" => $key['supplierid'],
                "claim_descripiton" => $key['claim_descripiton'],
                "memoid" => $key['memoid'],
                "tipe_trans" => $key['tipe_trans'],
                "periode_1" => $key['periode_1'],
                "periode_2" => $key['periode_2'],
                "tgl_surat_klaim" => $key['tgl_surat_klaim'],
                "no_resi" => $key['no_resi'],
                "tgl_kirim" => $key['tgl_kirim'],
                "tgl_akan_dibayar" => $key['tgl_akan_dibayar'],
                "nilai_klaim" => $key['nilai_klaim'],
                "bayar_klaim" => $key['bayar_klaim'],
                "status_lunas" => $key['status_lunas'],
                "status_proses" => $key['status_proses'],
                "date_create" => $key['date_create'],
                "user_create" => $key['user_create'],
                "date_update" => $key['date_update'],
                "user_update" => $key['user_update'],
                "nilai1" => $key['nilai1'],
                "nilai2" => $key['nilai2'],
                "nilai3" => $key['nilai3'],
                "ket1" => $key['ket1'],
                "ket2" => $key['ket2'],
                "ket3" => $key['ket3']
            );
            $proses_t_claim_master = $this->db->insert("dbklaim.t_claim_master_temp", $data);
            // echo "proses : ".$proses_t_claim_master;
        }

        $insert = "
                insert into dbklaim.t_claim_master
                select 	if(b.id_ref is null, 1, b.id_ref + 1), a.nocab, a.siteid, a.no_claim, a.tanggal_claim, 
                        a.supplierid, a.claim_descripiton, a.memoid, a.tipe_trans, a.periode_1,
                        a.periode_2, a.tgl_surat_klaim, a.no_resi, a.tgl_kirim, a.tgl_akan_dibayar, a.nilai_klaim,
                        a.bayar_klaim, a.status_lunas, a.status_proses, a.date_create, a.user_create, a.date_update, a.user_update,
                        a.nilai1, a.nilai2, a.nilai3, a.ket1, a.ket2, a.ket3, a.clustered
                from dbklaim.t_claim_master_temp a left JOIN (
                    select *
                    from dbklaim.t_claim_master a
                    where a.id_ref = (
                        select max(b.id_ref)
                        from dbklaim.t_claim_master b
                        where b.no_claim = a.no_claim and a.nocab = b.nocab
                    )                                 
                )b on a.no_claim = b.no_claim and a.nocab = b.nocab and a.nocab = '$nocab'
            ";
        $proses_update = $this->db->query($insert);

        $kosongkan = "delete from dbklaim.t_claim_detail_temp where nocab = '$nocab'";
        $proses_kosong = $this->db->query($kosongkan);

        foreach ($t_claim_detail as $key) 
        {
            $data = array(
                "nocab" => $nocab,
                "siteid" => $key['siteid'],
                "no_pembayaran_klaim" => $key['no_pembayaran_klaim'],
                "no_claim" => $key['no_claim'],
                "tgl_pembayaran" => $key['tgl_pembayaran'],
                "tanggal_claim" => $key['tanggal_claim'],
                "supplierid" => $key['supplierid'],
                "tipe_trans" => $key['tipe_trans'],
                "no_transfer" => $key['no_transfer'],
                "tgl_transfer" => $key['tgl_transfer'],
                "bayar_tunai" => $key['bayar_tunai'],
                "bayar_transfer" => $key['bayar_transfer'],
                "proses_pembayaran" => $key['proses_pembayaran'],
                "create_date" => $key['create_date'],
                "user_create" => $key['user_create'],
                "update_date" => $key['update_date'],
                "user_update" => $key['user_update'],
                "nilai1" => $key['nilai1'],
                "nilai2" => $key['nilai2'],
                "nilai3" => $key['user_create'],
                "ket1" => $key['ket1'],
                "ket2" => $key['ket2'],
                "ket3" => $key['ket3']
            );
            $proses_t_claim_detail = $this->db->insert("dbklaim.t_claim_detail_temp", $data);
        }

        $insert = "
            insert into dbklaim.t_claim_detail
            select	if(b.id_ref is null, 1, b.id_ref + 1), a.nocab, a.siteid, a.no_pembayaran_klaim, a.no_claim, a.tgl_pembayaran, a.tanggal_claim, a.supplierid, 
                            a.tipe_trans, a.no_transfer, a.tgl_transfer, a.bayar_tunai, a.bayar_transfer, a.proses_pembayaran,
                            a.create_date, a.user_create, a.update_date, a.user_update, a.nilai1, a.nilai2, a.nilai3, 
                            a.ket1, a.ket2, a.ket3
            from dbklaim.t_claim_detail_temp a left JOIN (
                select *
                from dbklaim.t_claim_detail a
                where a.id_ref = (
                    select max(b.id_ref)
                    from dbklaim.t_claim_detail b
                    where b.no_claim = a.no_claim and b.no_pembayaran_klaim = a.no_pembayaran_klaim and a.nocab = b.nocab
                ) 
            )b on a.no_claim = b.no_claim and b.no_pembayaran_klaim = a.no_pembayaran_klaim and a.nocab = b.nocab and a.nocab = '$nocab'
        ";
        $proses_update = $this->db->query($insert);


                            
        $kosongkan = "delete from dbklaim.t_claim_detail_product_temp where nocab = '$nocab'";
        $proses_kosong = $this->db->query($kosongkan);

        foreach ($t_claim_detail_product as $key) 
        {
            $data = array(
                "nocab" => $nocab,
                "siteid" => $key['siteid'],
                "no_claim" => $key['no_claim'],
                "productid" => $key['productid'],
                "memoid" => $key['memoid'],
                "disc_prinsipal" => $key['disc_prinsipal'],
                "disc_xtra" => $key['disc_xtra'],
                "disc_cash" => $key['disc_cash'],
                "qty_bonus" => $key['qty_bonus'],
                "nilai_bonus" => $key['nilai_bonus'],
                "create_date" => $key['create_date'],
                "user_create" => $key['user_create'],
                "update_date" => $key['udate_date'],
                "user_update" => $key['user_update'],
                "periode_1" => $key['periode_1'],
                "periode_2" => $key['periode_2'],
                "nilai1" => $key['nilai1'],
                "nilai2" => $key['nilai2'],
                "nilai3" => $key['user_create'],
                "ket1" => $key['ket1'],
                "ket2" => $key['ket2'],
                "ket3" => $key['ket3']
            );
            $proses_t_claim_detail_product = $this->db->insert("dbklaim.t_claim_detail_product_temp", $data);
            // var_dump($proses_t_claim_detail_product);
        }

        $insert = "
            insert into dbklaim.t_claim_detail_product
            select 	if(b.id_ref is null, 1, b.id_ref + 1), a.nocab, a.siteid, a.no_claim, a.productid, a.memoid, a.disc_prinsipal, a.disc_xtra, a.disc_cash, a.qty_bonus,
                            a.nilai_bonus, a.create_date, a.user_create, a.update_date, a.user_update, a.periode_1, a.periode_2, a.nilai1,
                            a.nilai2, a.nilai3, a.ket1, a.ket2, a.ket3
            from dbklaim.t_claim_detail_product_temp a left JOIN (
                    select *
                    from dbklaim.t_claim_detail_product a
                    where a.id_ref = (
                            select max(b.id_ref)
                            from dbklaim.t_claim_detail_product b
                            where b.no_claim = a.no_claim and a.nocab = b.nocab and a.productid = b.productid
                    ) 
            )b on a.no_claim = b.no_claim and a.nocab = b.nocab and a.productid = b.productid and a.nocab = '$nocab'
        ";
        $proses_update = $this->db->query($insert);

        $kosongkan = "delete from dbklaim.t_claim_detail_transaksi_temp where nocab = '$nocab'";
        $proses_kosong = $this->db->query($kosongkan);

        foreach ($t_claim_detail_transaksi as $key) 
        {
            $data = array(
                "nocab" => $nocab,
                "siteid" => $key['siteid'],
                "nama_site" => $key['nama_site'],
                "no_klaim" => $key['no_klaim'],
                "no_sales" => $key['no_sales'],
                "tanggal" => $key['tanggal'],
                "flag_transaksi" => $key['flag_transaksi'],
                "customerid" => $key['customerid'],
                "nama_customer" => $key['nama_customer'],
                "alamat" => $key['alamat'],
                "prinsipalid" => $key['prinsipalid'],
                "prinsipal" => $key['prinsipal'],
                "productid" => $key['productid'],
                "nama_invoice" => $key['nama_invoice'],
                "qty_jual" => $key['qty_jual'],
                "qty_bonus" => $key['qty_bonus'],
                "rp_kotor" => $key['rp_kotor'],
                "disc_prinsipal" => $key['disc_prinsipal'],
                "disc_xtra" => $key['disc_xtra'],
                "disc_cod" => $key['disc_cod'],
                "rp_netto" => $key['rp_netto'],
            );
            $proses_t_claim_detail_transaksi = $this->db->insert("dbklaim.t_claim_detail_transaksi_temp", $data);
            // var_dump($proses_t_claim_detail_transaksi);
        }

        $insert = "
            insert into dbklaim.t_claim_detail_transaksi
            select 	if(b.id_ref is null, 1, b.id_ref + 1), a.nocab, a.siteid, a.nama_site, a.no_klaim, a.no_sales, a.tanggal, a.flag_transaksi, a.customerid, a.nama_customer,
                                a.alamat, a.prinsipalid, a.prinsipal, a.productid, a.nama_invoice, a.qty_jual, a.qty_bonus, a.rp_kotor, 
                                a.disc_prinsipal, a.disc_xtra, a.disc_cod, a.rp_netto
                from dbklaim.t_claim_detail_transaksi_temp a left JOIN (
                    select *
                    from dbklaim.t_claim_detail_transaksi a
                    where a.id_ref = (
                            select max(b.id_ref)
                            from dbklaim.t_claim_detail_transaksi b
                            where a.no_sales = b.no_sales and a.nocab = b.nocab and a.no_klaim = b.no_klaim and a.customerid = b.customerid and a.productid = b.productid
                    )                                 
            )b on a.no_sales = b.no_sales and a.nocab = b.nocab and a.no_klaim = b.no_klaim and a.customerid = b.customerid and a.productid = b.productid and a.nocab = '$nocab'
        ";
        $proses_update = $this->db->query($insert);

        $sql = "update dbklaim.t_log a
                set a.t_master = $proses_t_claim_master, a.t_detail = $proses_t_claim_detail, a.t_detail_product = $proses_t_claim_detail_product, a.t_detail_transaksi = $proses_t_claim_detail_transaksi, a.status = 1, a.username = '$username'
                where a.id = $id
        ";
        $proses = $this->db->query($sql);

        if ($proses) {
            return $proses;
        } else {
            return array();
        }


    }

    public function hitung_klaim_proses($data){
        date_default_timezone_set('Asia/Jakarta');        
        $tgl_created=date('Y-m-d H:i:s');

        $tahun = $data['tahun'];
        $bulan = $data['bulan'];
        $supp = $data['supp'];
        $kondisi = $data['kondisi'];
        $id = $this->session->userdata('id');

        // echo "tahun : ".$tahun;
        // echo "bulan : ".$bulan;
        // echo "supp : ".$supp;

        $sql_del = $this->db->query("delete from dbklaim.t_rekap where id = '$id'");
        $sql = "            
            insert into dbklaim.t_rekap
            select a.kode, b.branch_name, b.nama_comp, b.sub,b.urutan,'1',a.kodeprod, c.namaprod, sum(a.banyak) as sales_unit, sum(a.tot1) as sales_value,sum(a.UNITBONUS) as bonus_unit, $id, '$tgl_created'
            FROM
            (
                    select a.NODOKJDI, kode_lang, kodeprod, a.KODEBONUS, a.BANYAK, a.tot1, a.UNITBONUS, a.GRUPBONUS, concat(a.kode_comp, a.nocab) as kode
                    from data$tahun.fi a
                    where bulan = $bulan
                    union all
                    select a.NODOKJDI, kode_lang, kodeprod, a.KODEBONUS, a.BANYAK, a.tot1,a.UNITBONUS, a.GRUPBONUS, concat(a.kode_comp, a.nocab) as kode
                    from data$tahun.ri a
                    where bulan = $bulan
            )a LEFT JOIN
            (
                select a.branch_name,a.nama_comp,concat(a.kode_comp, a.nocab) as kode, a.urutan, a.sub
                from mpm.tbl_tabcomp_new a
                where a.status = 1
                GROUP BY concat(a.kode_comp, a.nocab)
            )b on a.kode = b.kode
            inner JOIN
            (
                select a.kodeprod,a.namaprod
                from mpm.tabprod a
                where a.supp = '$supp'
            )c on a.kodeprod = c.kodeprod
            GROUP BY a.kode, a.kodeprod
            ORDER BY b.urutan, a.kodeprod
        ";

        $proses = $this->db->query($sql);

        if ($kondisi == '2') 
        {
            $sql = "
                insert into dbklaim.t_rekap
                select 	a.kode, a.branch_name, b.nama_comp, a.sub, b.urutan, '2',a.kodeprod, 
                        a.namaprod, sum(a.sales_unit), sum(a.sales_value), sum(a.bonus_unit),
                        $id,'$tgl_created'
                from dbklaim.t_rekap a inner JOIN
                (
                    select concat(kode_comp,nocab), nama_comp, branch_name, sub, urutan
                    from mpm.tbl_tabcomp_new a
                    where a.`status` = 2
                    GROUP BY concat(kode_comp,nocab)
                )b on a.sub = b.sub
                GROUP BY sub, a.kodeprod
                ORDER BY urutan          
            
            ";
            $proses = $this->db->query($sql);
        
        }else{
        }

        $sql_tampil = "select * from dbklaim.t_rekap where id = $id";
        $proses_tampil = $this->db->query($sql_tampil)->result();
        if ($proses_tampil) {
            return $proses_tampil;
        }else{
            return array();
        }

    }

    

}

/* End of file model_sales.php */
/* Location: ./application/models/model_sales.php */