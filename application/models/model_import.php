<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_import extends CI_Model 
{
    function insert($data)
	{
        $id = $this->session->userdata('id');
        $sql_del = "delete from db_import.t_import where created_by = $id";
        $proses_del = $this->db->query($sql_del);

        // $this->db->query("truncate db_import.t_import");
		$this->db->insert_batch('db_import.t_import', $data);
    }
    
    function get_import()
	{
        $id = $this->session->userdata('id');
        $sql = "
        select * from db_import.t_import a 
        where created_by = $id
        ";
        $proses = $this->db->query($sql)->result();
        return $proses;
    }

    function konversi()
    {
        date_default_timezone_set('Asia/Jakarta');        
        $tgl_created=date('Y-m-d H:i:s');
        $id = $this->session->userdata('id');

        $sql_user = $this->db->query("select username from mpm.`user` a  where a.id = $id")->result();
        foreach($sql_user as $a){
            $username = $a->username;
        }

        $sql_kode_comp = "
            select  a.kode_comp,a.nama_comp,a.nocab 
            from    mpm.tbl_tabcomp a
            where   a.`status` = 1 and a.kode_comp ='$username'
            GROUP BY a.kode_comp
        "; 
        $proses_kode_comp = $this->db->query($sql_kode_comp)->result();

        foreach($proses_kode_comp as $a){
            $kode_comp = $a->kode_comp;
            $nocab = $a->nocab;
            $nama_comp = $a->nama_comp;
        }

        // echo $kode_comp;
        // echo $nocab;
        // echo $nama_comp;

        $sql_bulan = "
            select substr(a.tanggal,6,2) as bulan,year(a.tanggal) as tahun from db_import.t_import a
            where a.created_by =$id
            GROUP BY bulan, tahun
        ";
        $proses_bulan = $this->db->query($sql_bulan)->result();
        foreach($proses_bulan as $a){
            $bulan = $a->bulan;
            $tahun = $a->tahun;
            // echo "bulan : ".$bulan;
        }

        $sql_tanggal = "
            select max(day(a.tanggal)) as tanggal 
            from db_import.t_import a
            where a.created_by =$id
        ";
        $proses_tanggal = $this->db->query($sql_tanggal)->result();
        foreach($proses_tanggal as $a){
            $tanggal = $a->tanggal;
           
        }

        $proses_bulan = $this->db->query($sql_bulan)->num_rows();
        // echo "jml_bulan : ".$proses_bulan;

        if ($proses_bulan > 1) {
            // redirect('import/import_dp');
            echo "gagal ";
        }else{
            $sql_upload_terakhir = "
                select tahun, bulan, tanggal, status_closing
                from mpm.upload a
                where userid = $id
                ORDER BY id desc
                limit 1
            ";
            $proses_upload_terakhir = $this->db->query($sql_upload_terakhir)->result();
            foreach($proses_upload_terakhir as $a){
                $tahun_db = $a->tahun;
                $bulan_db = $a->bulan;
                $tanggal_db = $a->tanggal;
                $status_closing = $a->status_closing;
                // echo "tahun_db : ".$tahun_db."<br>";
                // echo "bulan_db : ".$bulan_db."<br>";
                // echo "tanggal_db : ".$tanggal_db."<br>";
                // echo "status_closing_db : ".$status_closing."<br>"."<br>";
                // ;
            }

            // echo "tahun : ".$tahun."<br>";
            // echo "bulan : ".$bulan."<br>";
            // echo "tanggal : ".$tanggal."<br>";

            if ($tahun_db == $tahun) { //misal lebih dari sama dengan 2020
                if ($bulan_db == $bulan) { //misal sama2 mei
                    if ($status_closing == '1') {
                        echo "gagal";
                        // redirect('import/import_dp');
                    }else{ //jika blm closing
                        if($tanggal_db > $tanggal){
                            echo "gagal";
                            // redirect('import/import_dp'); 
                        }else{
                            // echo "aman";
                        }
                    }
                }elseif($bulan_db < $bulan){ // misal db = mei, upload = juni
                    if ($status_closing == '1') {
                        echo "aman";
                        
                    }else{
                        echo "gagal";
                        // redirect('import/import_dp');
                    }
                }
            }elseif($tahun_db < $tahun){
                if ($status_closing == '1') {
                    echo "aman";
                    
                }else{
                    echo "gagal";
                    // redirect('import/import_dp');
                }
            }
        }

        $sql_del_fi_konversi = $this->db->query("delete from data$tahun.fi where nocab = '$nocab' and bulan = $bulan");
        $sql = "
        insert into data$tahun.fi
        select 	'07' as KDOKJDI, a.no_transaksi as NODOKJDI, 
				a.no_transaksi as NODOKACU, a.tanggal as TGLDOKJDI,	
				a.kodesalesman as KODESALES, '$kode_comp' as KODE_COMP,
				c.kode_kota as kode_kota,
				if(a.nama_outlet like'%AP %','AP',if(a.nama_outlet like'%Mart%','MM','TK'))as kode_type,
				a.kode_outlet as kode_lang,
				'' as koderayon,
				a.kodeprod as kodeprod,
				b.SUPP as supp,
				day(a.tanggal) as hrdok,
				month(a.tanggal) as blndok,
				year(a.tanggal) as thndok,
				b.namaprod as namaprod,
				b.GRUPPROD as groupprod,
				if(a.total_harga='0','0',a.qty) as banyak,
				a.total_harga / a.qty as harga,
				if(a.potongan=100,0,a.potongan) as potongan,
				a.total_harga + a.potongan as tot1,
				'' as jum_promo,
				if(a.total_harga='0',concat(a.kodeprod,' ',a.qty),'') as keterangan,
				'' as USER_ISI,
				'' as JAM_ISI,
				'' as TGL_ISI, 
				'' as USER_EDIT, 
				'' as JAM_EDIT, 
				'' as TGL_EDIT,
				'' as USER_DEL, 
				'' as JAM_DEL, 
				'' as TGL_DEL, 
				'' as NO, 
				'' as BACKUP, 
				'' as NO_URUT, 
				'PST' as KODE_GDG, 
				'' as NAMA_GDG, 
				a.tipe_outlet as KODESALUR, 
				'' as KODEBONUS,
				'' as NAMABONUS, 
				'' as GRUPBONUS, 
				'' as UNITBONUS, 
				a.salesman as LAMPIRAN, 
				'' as H_BELI, 
				'' as KODEAREA, 
				a.alamat_outlet as NAMAAREA, 
				'' as PINJAM, 
				'' as JUALBANYAK, 
				'' as JUALPINJAM, 
				'' as HARGA_EXCL, 
				'' as TOT1_EXCL, 
				a.nama_outlet as NAMA_LANG,
				'$nocab' as nocab,
				'$bulan' as bulan
        from 	db_import.t_import a LEFT JOIN mpm.tabprod b
                    on a.kodeprod = b.kodeprod LEFT JOIN
        (
            select a.kode_kota, a.kota_excel
            from db_import.t_mapping_kota a
            where a.kode_comp ='$kode_comp' and a.nocab='$nocab'
        )c on a.kota = c.kota_excel
        where 	a.created_by = '$id'
        ";

        // echo "<pre>";
        // print_r($sql);
        // echo "</pre>";

        $proses = $this->db->query($sql);

        $sql_del_tblang_konversi = $this->db->query("delete from data$tahun.tblang where nocab = '$nocab'");

        $sql = "
        insert into data$tahun.tblang
        SELECT	KODE_COMP,
                KODE_KOTA,
                KODE_TYPE,
                KODE_LANG,
                KODERAYON,
                NAMA_LANG,
                NAMAAREA as ALAMAT1,
                '' as ALAMAT2,
                '' as TELP,
                '' as KODEPOS,
                '' as TGL,
                '' as NPWP,
                '0' as BTS_UTANG,
                '0' as SALES01,
                '0' as SALES02,
                '0' as SALES03,
                '0' as SALES04,
                '0' as SALES05,
                '0' as SALES06,
                '0' as SALES07,
                '0' as SALES08,
                '0' as SALES09,
                '0' as SALES10,
                '0' as SALES11,
                '0' as SALES12,
                '0' as KET,
                '0' as DEBIT,
                '0' as KREDIT,
                KODESALUR as KODESALUR,
                '0' as TOP,
                'Y' as AKTIF,
                '' as TGL_AKTIF,
                'T' as PPN,
                '0' as KODE_LAMA,
                '1' as JUM_DOK,
                '0' as STATJUAL,
                '0' as LIMIT1,
                '' as TGLNAKTIF,
                '' as ALAMAT_WP,
                '' as NILAI_PPN,
                '' as NAMA_WP,
                '' as NEWFLD,'A1' as NOCAB,
                '' as CUSTID,
                '' as COMPID,
                '' as LATITUDE,
                '' as LONGITUDE,
                '' as FOTO_DISP,
                '' as FOTO_TOKO
        FROM
        (
        SELECT	CONCAT(KODE_COMP,KODE_LANG,max(BULAN))	as mapp
        FROM 	db_import.t_fi_konversi  
        WHERE 	NOCAB = '$nocab' 
        GROUP BY kode_comp,KODE_LANG 
        )A  LEFT JOIN 
        (
            SELECT * FROM
            (
                SELECT *,
                CONCAT(KODE_COMP,KODE_LANG,BULAN)	as mapp
                FROM db_import.t_fi_konversi  
                WHERE NOCAB='$nocab' 
                GROUP BY MAPP 
            )A
        )C USING(MAPP)
        ";
        $proses = $this->db->query($sql);

        $sql_del_tabsales_konversi = $this->db->query("delete from data$tahun.tabsales where nocab = '$nocab'");

        $sql = "
        insert into data$tahun.tabsales
        select  
                KODESALES,
                lampiran as NAMASALES,
                ''AS KODERAYON,
                'S'AS `STATUS`,
                'BANDA ACEH' AS ALAMAT1,
                'BANDA ACEH' AS ALAMAT2,
                ''AS NO_TELP,
                '' AS KODEPOS,
                b.propinsi AS PROPINSI,
                '' AS DATA1,
                '' AS TAHAP,
                '' AS FILEID,
                '' AS NAMA_DEPO,
                KODE_KOTA,
                '' AS KODE_GDG,
                '' AS NAMA_GDG,
                'Y' AS AKTIF,
				NOCAB 
        from    db_import.t_fi_konversi
        LEFT JOIN PMU.Propinsi b using (Nocab)
        where nocab ='$nocab' group by kodesales
        ";
        $proses = $this->db->query($sql);

        $sql_del_tbkota_konversi = $this->db->query("delete from data$tahun.tbkota where nocab = '$nocab'");

        $sql = "
        insert into data$tahun.tbkota
        select 
                KODE_COMP AS KODE_COMP, 
                a.KODE_KOTA AS KODE_KOTA, b.nama_kota AS NAMA_KOTA,
                '$nocab' AS NOCAB
        from db_import.t_fi_konversi a LEFT JOIN
        (
            select a.kode_kota, a.nama_kota
            from db_import.t_mapping_kota a
            where a.nocab ='$nocab'
        )b on a.KODE_KOTA = B.kode_kota
        where nocab = '$nocab' 
        group by KODE_COMP,KODE_KOTA
        ";
        $proses = $this->db->query($sql);

        $sql_del_tabtype_konversi = $this->db->query("delete from data$tahun.tabtype where nocab = '$nocab'");

        $sql = "
        insert into data$tahun.tabtype
        select
                KODE_TYPE,
                case 
                when KODE_TYPE = 'AP' THEN 'APOTEK' 
                when KODE_TYPE = 'MM' THEN 'MINI MARKET'
                when KODE_TYPE = 'SM' THEN 'SUPERMARKET'
                when KODE_TYPE = 'PB' THEN 'PERUSAHAAN BESAR'   
                when KODE_TYPE = 'GR' THEN 'GROSIR'
                when KODE_TYPE = 'RT' THEN 'RETAIL'
                when KODE_TYPE = 'TO' THEN 'TOKO OBAT'
                ELSE
                'TOKO KELONTONG'
                    END AS NAMA_TYPE,
                NOCAB AS NOCAB 
                FROM db_import.t_fi_konversi
                WHERE NOCAB='$nocab'
                GROUP BY KODE_TYPE
        ";
        $proses = $this->db->query($sql);

        $sql_omzet = "
            select format(sum(x),0) as omzet
            FROM
            (
                    select sum(tot1) as x
                    from data$tahun.fi a 
                    where a.nocab ='$nocab' and kode_comp ='$kode_comp' and a.bulan = '$bulan'
                    union all
                    select sum(tot1) as x
                    from data$tahun.ri a 
                    where a.nocab ='$nocab' and kode_comp ='$kode_comp' and a.bulan = '$bulan'
            )a ";
        $proses_omzet = $this->db->query($sql_omzet)->result();
        foreach ($proses_omzet as $a) {
            $omzet = $a->omzet;
        }

        $sql_insert = "
        insert into mpm.upload
        select '', '$id' as userid, '$tgl_created' as lastupload, concat('DT','$nocab','$bulan','$tanggal','.ZIP') as filename, 2 as flag, 
        '$tanggal' as tanggal, '$bulan' as bulan, '$tahun' as tahun, '1' as `status`, '0' as status_closing, '$omzet' as omzet";

        $proses_insert = $this->db->query($sql_insert);

        if ($proses_insert) {
            $sql = "select * from mpm.upload where userid = $id order by id desc limit 1";
            $proses= $this->db->query($sql)->result();
            if ($proses) {
                return $proses;
            }else{
                return array();
            }
        }else{
            return array();
        }
        

    }

    function transfer()
    {
        date_default_timezone_set('Asia/Jakarta');        
        $tgl_created='"'.date('Y-m-d H:i:s').'"';
        $id = $this->session->userdata('id');

        // $sql = "select from db_import.t_import a where a."
        

    }
    
}