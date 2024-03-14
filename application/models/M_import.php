<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_import extends CI_Model
{
    public function cek_upload_terakhir($id)
    {
        //echo "ccccccccc";
        if ($id == '547' || $id == '297' || $id == '289') {
            $this->db->select('*');
            $this->db->where('upload.userid', '384');
            $this->db->where('upload.status', '1');
            $this->db->order_by('id', 'desc');
            $this->db->limit(100);
            $hasil = $this->db->get('mpm.upload');
        } else {
            $this->db->select('*');
            $this->db->where('upload.userid', $id);
            $this->db->where('upload.status', '1');
            $this->db->order_by('id', 'desc');
            $hasil = $this->db->get('mpm.upload');
        }
        return $hasil;
    }

    public function cek_bulan_closing($id)
    {
        $this->db->select('*');
        $this->db->where('upload.userid', $id);
        $this->db->where('upload.status_closing', '1');
        $this->db->order_by('id', 'desc');
        $hasil = $this->db->get('mpm.upload');
        return $hasil;
    }

    public function insert($table,$data){
        $insert = $this->db->insert($table,$data);
        if ($insert) {
            return $this->db->insert_id();
        }else{
            return array();
        }
    }

    public function get_data_preview($table,$signature)
    {
        $id = $this->session->userdata('id');

        $this->db->select('*');
        $this->db->where('created_by', $id );
        $this->db->where('signature', $signature );
        $hasil = $this->db->get("$table");
        return $hasil;
    }

    public function get_data_validasi_sales($table,$userid,$signature)
    {
        $id = $this->session->userdata('id');
        // var_dump($id);die;
        $sql = "
            SELECT IF(a.status_closing = 0,a.bulan,CONCAT(0,a.bulan+1)) as bulan, a.tahun FROM mpm.upload a
            where a.userid = $userid
            ORDER BY id DESC
        ";

        $upload = $this->db->query($sql)->row();

        $sql2 = "
            SELECT a.* FROM site.t_temp_import_sales a
            INNER JOIN mpm.tabprod b on a.kodeprod = b.kodeprod
            WHERE a.created_by = '$id'and a.signature = '$signature' and a.bulan = '$upload->bulan' and a.tahun = '$upload->tahun'
        ";
        
        $hasil = $this->db->query($sql2);
        return $hasil;
    }

    public function get_data_validasi_data_salah($table,$userid,$signature)
    {
        $id = $this->session->userdata('id');

        $sql = "
            SELECT IF(a.status_closing = 0,a.bulan,CONCAT(0,a.bulan+1)) as bulan, a.tahun FROM mpm.upload a
            where a.userid = $userid
            ORDER BY id DESC
        ";

        $upload = $this->db->query($sql)->row();

        $sql = "
            SELECT a.* FROM site.t_temp_import_sales a
            WHERE a.created_by = '$id' and a.signature = '$signature' and a.kodeprod not in (SELECT kodeprod FROM mpm.tabprod)
            union all
            SELECT a.* FROM site.t_temp_import_sales a
            WHERE a.created_by = '$id' and a.signature = '$signature' and a.bulan != '$upload->bulan' or a.tahun != '$upload->tahun' 
        ";

        $hasil = $this->db->query($sql);
        return $hasil;
    }

    public function get_data_validasi_kodeprod_salah($table,$userid,$signature)
    {
        $id = $this->session->userdata('id');

        $sql = "
            SELECT IF(a.status_closing = 0,a.bulan,CONCAT(0,a.bulan+1)) as bulan, a.tahun FROM mpm.upload a
            where a.userid = $userid
            ORDER BY id DESC
        ";

        $upload = $this->db->query($sql)->row();

        $sql = "
            SELECT a.* FROM site.t_temp_import_sales a
            WHERE a.created_by = '$id' and a.signature = '$signature' and a.kodeprod not in (SELECT kodeprod FROM mpm.tabprod)
        ";

        $hasil = $this->db->query($sql);
        return $hasil;
    }

    public function get_data_validasi_tgl_salah($table,$userid,$signature)
    {
        $id = $this->session->userdata('id');

        $sql = "
            SELECT IF(a.status_closing = 0,a.bulan,CONCAT(0,a.bulan+1)) as bulan, a.tahun FROM mpm.upload a
            where a.userid = $userid
            ORDER BY id DESC
        ";

        $upload = $this->db->query($sql)->row();

        $sql = "
            SELECT a.* FROM site.t_temp_import_sales a
            WHERE a.created_by = '$id' and a.signature = '$signature' and a.bulan != '$upload->bulan' or a.tahun != '$upload->tahun' 
        ";

        $hasil = $this->db->query($sql);
        return $hasil;
    }

    public function hitung_omzet4($userid,$site)
    {
        /* cari nilai filename,tahun */
        $this->db->where('userid', $userid);
        $this->db->order_by('id', 'DESC');
        $upload = $this->db->get('mpm.upload',1);
        foreach ($upload->result() as $row) {
            $filename = $row->filename;
            $year = $row->tahun;
            $month = $row->bulan;
        }
        $sql = "
                select format(sum(val),2) val 
                from
                (
                    select sum(tot1) val from data$year.fi where bulan = $month and concat(kode_comp,nocab) = '$site'
                    union ALL
                    select sum(tot1) val from data$year.ri where bulan = $month and concat(kode_comp,nocab) = '$site'
                )a
            ";

        //print_r($sql);

        $result = $this->db->query($sql)->row_array();
        return $result['val'];
    }

    public function hitung_stock($data)
    {
        $nocab = $data['nocab'];
        $tahun = $data['tahun'];
        $bln_stock = $data['bln_stock'];
        $sql = "
                SELECT SUM(a.SALDOAWAL) as unit, SUM(a.val) as 'value'
                FROM
                (
                    SELECT a.KODEPROD, a.SALDOAWAL, (a.SALDOAWAL*b.h_dp) as val
                    FROM
                    (
                        SELECT a.KODEPROD, a.SALDOAWAL, a.NOCAB, a.BULAN
                        FROM data$tahun.st a
                        WHERE a.NOCAB = '$nocab' and a.BULAN = '$bln_stock'
                    )a
                    LEFT JOIN
                    (
                        SELECT a.kodeprod, a.h_dp
                        FROM mpm.prod_detail a
                        WHERE a.tgl = ( SELECT MAX(b.tgl)
                        FROM mpm.prod_detail b
                        WHERE a.kodeprod = b.kodeprod
                        GROUP BY b.kodeprod)
                    )b on a.kodeprod = b.kodeprod
                )a
            ";
        $stock = $this->db->query($sql);
        return $stock ;
    }

    public function import_simpan($data)
    {
        $id = $this->session->userdata('id');
        $userid = $data['userid'];
        $this->db->select('*');
        $this->db->where('userid', $userid);
        $this->db->where('flag', '2');
        $this->db->order_by('id', 'DESC');
        $upload_sa = $this->db->get('mpm.upload',1)->row();

        $bln_sa = $upload_sa->bulan;
        $thn_sa = $upload_sa->tahun;
        $site = $data['site'];
        $kode_comp = substr($site,0,3);
        $nocab = substr($site,3,2);
        $signature = $data['signature'];
        $closing = $data['closing'];
        
        $this->db->query("DELETE FROM DATA$thn_sa.fi where concat(kode_comp,nocab) = '$site' and bulan = '$bln_sa'");
        $this->db->query("DELETE FROM DATA$thn_sa.ri where concat(kode_comp,nocab) = '$site' and bulan = '$bln_sa'");
        $this->db->query("DELETE FROM DATA$thn_sa.tblang where concat(kode_comp,nocab) = '$site'");
        $this->db->query("DELETE FROM DATA$thn_sa.tabsales where nocab = '$nocab'");
        $this->db->query("DELETE FROM DATA$thn_sa.tbkota where concat(kode_comp,nocab) = '$site'");
        $this->db->query("DELETE FROM DATA$thn_sa.tabtype where nocab = '$nocab'");
        
        $fi ="
            insert into data$thn_sa.fi
            select 
                '07' as KDOKJDI,
                a.NO_TRANSAKSI as NODOKJDI,
                a.NO_TRANSAKSI as NODOKACU,
                a.TANGGAL as TGLDOKJDI,
                a.salesman as KODESALES,
                '$kode_comp' as KODE_COMP,
                'TJP' as KODE_KOTA,
                a.tipe_outlet as KODE_TYPE,
                a.KODE_OUTLET as KODE_LANG,
                '' as KODERAYON,
                a.kodeprod as KODEPROD,
                if(a.nama_supp = b.namasupp,b.supp,a.nama_supp) as SUPP,
                RIGHT(a.TANGGAL,2) as HRDOK,
                $bln_sa as BLNDOK,
                LEFT(a.TANGGAL,4) as THNDOK,
                c.NAMAPROD as NAMAPROD,
                c.GRUPPROD as GROUPPROD,
                IF(BRUTO='0','0',QTY) as BANYAK,
                a.harga as HARGA,
                a.POTONGAN as POTONGAN,
                a.BRUTO as TOT1,
                '' as JUM_PROMO, 
                if(a.BRUTO='0',concat(a.kodeprod,' ',a.QTY),'') as KETERANGAN,  
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
                if(a.class_outlet like'WS%','WS','RT') as KODESALUR, 
                '' as KODEBONUS,
                '' as NAMABONUS, 
                '' as GRUPBONUS, 
                '' as UNITBONUS, 
                a.salesman as LAMPIRAN, 
                '' as H_BELI, 
                '' as KODEAREA, 
                a.ALAMAT_OUTLET as NAMAAREA, 
                '' as PINJAM, 
                '' as JUALBANYAK, 
                '' as JUALPINJAM, 
                '' as HARGA_EXCL, 
                '' as TOT1_EXCL, 
                a.NAMA_OUTLET as NAMA_LANG,
                '$nocab' as NOCAB,
                '$bln_sa' as BULAN,
                ' ' as siteid,
                ' ' as qty1,
                ' ' as qty2,
                ' ' as qty3,
                ' ' as qty_bonus,
                ' ' as flag_bonus,
                ' ' as disc_persen,
                ' ' as disc_rp,
                ' ' as disc_value,
                ' ' as disc_cabang,
                ' ' as disc_prinsipal,
                ' ' as disc_xtra,
                ' ' as rp_cabang,
                ' ' as rp_prinsipal,
                ' ' as rp_xtra,
                ' ' as bonus,
                ' ' as prinsipalid,
                ' ' as ex_no_sales,
                ' ' as status_retur,
                ' ' as ref,
                ' ' as term_payment,
                ' ' as tipe_kl
            from site.t_temp_import_sales a
            INNER JOIN mpm.tabprod c on a.kodeprod = c.KODEPROD 
            LEFT JOIN mpm.tabsupp b on a.nama_supp = b.namasupp 
            where a.created_by = $id and a.signature = '$signature' and qty not like'-%' and harga not like'-%' and bruto not like'-%' and a.bulan = $bln_sa and a.tahun = $thn_sa
            order by harga
        ";
        $this->db->query($fi);
        
        $ri = "
            insert into data$thn_sa.ri
            select 
                '07' as KDOKJDI,
                a.NO_TRANSAKSI as NODOKJDI,
                a.NO_TRANSAKSI as NODOKACU,
                a.TANGGAL as TGLDOKJDI,
                a.salesman as KODESALES,
                '$kode_comp' as KODE_COMP,
                'TJP' as KODE_KOTA,
                CASE
                when a.nama_outlet like'%Apotik%' or a.nama_outlet like'%Apotek%' THEN 'APT'
                when a.nama_outlet like'%Mart%'or a.nama_outlet like '%MM%' THEN 'MML'
                when a.nama_outlet like'%Supermarket%'or a.nama_outlet like '%Super market%' THEN 'MMN'
                when a.nama_outlet like'%PT%' THEN 'PBF'
                ELSE 'TKL'
                END 
                as KODE_TYPE,
                a.KODE_OUTLET as KODE_LANG,
                '' as KODERAYON,
                a.kodeprod as KODEPROD,
                if(a.nama_supp = b.namasupp,b.supp,a.nama_supp) as SUPP,
                RIGHT(a.TANGGAL,2) as HRDOK,
                '$bln_sa' as BLNDOK,
                LEFT(a.TANGGAL,4) as THNDOK,
                c.NAMAPROD as NAMAPROD,
                c.GRUPPROD as GROUPPROD,
                IF(a.BRUTO='0','0',a.QTY) as BANYAK,
                a.harga as HARGA,
                a.POTONGAN as POTONGAN,
                a.BRUTO as TOT1,
                '' as JUM_PROMO, 
                if(a.BRUTO='0',concat(a.kodeprod,' ',a.QTY),'') as KETERANGAN,  
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
                CASE 
                when a.nama_outlet like'%Apotik%' or a.nama_outlet like'%Apotek%' THEN 'WS'
                when a.nama_outlet like'%Mart%'or a.nama_outlet like '%MM%' THEN 'WS'
                when a.nama_outlet like'%Supermarket%'or a.nama_outlet like '%Super market%' THEN 'WS'
                when a.nama_outlet like'%PT%' THEN 'SO' 
                ELSE 'RT' 
                END as KODESALUR, 
                '' as KODEBONUS,
                '' as NAMABONUS, 
                '' as GRUPBONUS, 
                '' as UNITBONUS, 
                a.salesman as LAMPIRAN, 
                '' as H_BELI, 
                '' as KODEAREA, 
                a.ALAMAT_OUTLET as NAMAAREA, 
                '' as PINJAM, 
                '' as JUALBANYAK, 
                '' as JUALPINJAM, 
                '' as HARGA_EXCL, 
                a.NAMA_OUTLET as NAMA_LANG,
                '$nocab' as NOCAB,
                '$bln_sa' as BULAN,
                ' ' as siteid,
                ' ' as qty1,
                ' ' as qty2,
                ' ' as qty3,
                ' ' as qty_bonus,
                ' ' as flag_bonus,
                ' ' as disc_persen,
                ' ' as disc_rp,
                ' ' as disc_value,
                ' ' as disc_cabang,
                ' ' as disc_prinsipal,
                ' ' as disc_xtra,
                ' ' as rp_cabang,
                ' ' as rp_prinsipal,
                ' ' as rp_xtra,
                ' ' as bonus,
                ' ' as prinsipalid,
                ' ' as ex_no_sales,
                ' ' as status_retur,
                ' ' as ref,
                ' ' as term_payment,
                ' ' as tipe_kl
            from site.t_temp_import_sales a
            INNER JOIN mpm.tabprod c on a.kodeprod = c.KODEPROD 
            LEFT JOIN mpm.tabsupp b on a.nama_supp = b.namasupp 
            where a.created_by = $id and a.signature = '$signature' and qty like'-%' and harga like'-%' and bruto like'-%' and a.bulan = $bln_sa and a.tahun = $thn_sa
            order by harga
        ";
        $this->db->query($ri);
        
        $update_fi = "
            update data$thn_sa.fi
            set banyak=banyak/50 , harga=round(harga*50)
            where concat(kode_comp,nocab) = '$site' and bulan= '$bln_sa' and harga<250;
        ";
        $this->db->query($update_fi);
        
        $update_ri = "
            update data$thn_sa.ri
            set banyak=banyak/50 , harga=round(harga*50)
            where concat(kode_comp,nocab) = '$site' and bulan= '$bln_sa' and harga<250;
        ";
        $this->db->query($update_ri);

        $tblang = "
            insert into data$thn_sa.tblang
            SELECT
                KODE_COMP,
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
                '' as NEWFLD,
                '$nocab' as NOCAB,
                '' as kodelang_copy,
                '' as id_provinsi,
                '' as nama_provinsi,
                '' as id_kota,
                '' as nama_kota,
                '' as id_kecamatan,
                '' as nama_kecamatan,
                '' as id_kelurahan,
                '' as nama_kelurahan,
                '' as credit_limit,
                '' as tipe_bayar,
                '' as phone,
                '' as last_updated,
                '' as status_payment,
                '' as CUSTID,
                '' as COMPID,
                '' as LATITUDE,
                '' as LONGITUDE,
                '' as FOTO_DISP,
                '' as FOTO_TOKO
            FROM
            (
                SELECT CONCAT(KODE_COMP,KODE_LANG,MAX(BULAN)) as mapp
                FROM data$thn_sa.fi
                WHERE concat(kode_comp,nocab) = '$site' 
                GROUP BY kode_comp,KODE_LANG 
            )A
            LEFT JOIN 
            (
                SELECT * 
                FROM(
                    SELECT *, CONCAT(KODE_COMP,KODE_LANG,BULAN) as mapp
                    FROM data$thn_sa.fi
                    WHERE concat(kode_comp,nocab) = '$site' 
                    GROUP BY MAPP 
                    )a
            )C USING(MAPP)
        ";
        $this->db->query($tblang);
        
        $tabsales ="
            insert into data$thn_sa.tabsales
            select  
                a.KODESALES,
                a.lampiran as NAMASALES,
                ''AS KODERAYON,
                'S' AS `STATUS`,
                'TANJUNG PINANG' AS ALAMAT1,
                'TANJUNG PINANG' AS ALAMAT2,
                ''AS NO_TELP,
                '' AS KODEPOS,
                b.propinsi AS PROPINSI,
                '' AS DATA1,
                '' AS TAHAP,
                '' AS FILEID,
                '' AS NAMA_DEPO,
                a.KODE_KOTA,
                '' AS KODE_GDG,
                '' AS NAMA_GDG,
                'Y' AS AKTIF,
                a.NOCAB 
            from data$thn_sa.fi a
            LEFT JOIN PMU.Propinsi b using (Nocab)
            where nocab = '$nocab' group by kodesales
        ";
        $this->db->query($tabsales);
        
        $tbkota = "
            insert into data$thn_sa.tbkota
            select 
                a.KODE_COMP AS KODE_COMP, 
                a.KODE_KOTA AS KODE_KOTA,
                'TANJUNG PINANG' AS NAMA_KOTA,
                '$nocab' AS NOCAB
            from data$thn_sa.fi a
            where concat(a.kode_comp, a.nocab) = '$site'
            group by a.KODE_COMP,a.KODE_KOTA
        ";
        $this->db->query($tbkota);
        
        $tabtype = "
            insert into data$thn_sa.tabtype
            select
                a.KODE_TYPE,
                case 
                when a.KODE_TYPE = 'APT' THEN 'APOTEK' 
                when a.KODE_TYPE = 'MMN' THEN 'MINIMARKET NASIONAL'
                when a.KODE_TYPE = 'MML' THEN 'MINIMARKET LOCAL'
                when a.KODE_TYPE = 'PBF' THEN 'PERUSAHAAN BESAR FARMASI'   
                when a.KODE_TYPE = 'TOB%' THEN 'TOKO OBAT'
                when a.KODE_TYPE = 'TCO%' THEN 'TOKO KOSMETIK'
                ELSE
                'TOKO KELONTONG'
                END AS NAMA_TYPE,
                NOCAB AS NOCAB 
                FROM data$thn_sa.fi a
            WHERE a.NOCAB= '$nocab' 
            GROUP BY a.KODE_TYPE
            ";
        $this->db->query($tabtype);

        
        $x = $this->hitung_omzet4($userid,$site);
        // var_dump($x);die;
 
        // ++++++++++++++++++++++++++++++++++++++++++++++++++ STOK ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
        // if ($upload_sa->filename_stock != '') {
        //     $bln_stok = substr($upload_sa->tahun,2,2).$upload_sa->bulan;
        
        //     $this->db->query("DELETE from DATA$upload_sa->tahun.st where bulan = '$bln_stok' and nocab = '$nocab'");
    
        //     $stok = "
        //             INSERT INTO data$upload_sa->tahun.st
        //             SELECT 
        //             a.kodeprod as KODEPROD,
        //             '' as KODE_PRC,
        //             a.NAMAPROD,
        //             a.SUPP,
        //             '' as GRUPPROD,
        //             a.satuan as SATUAN,
        //             '' as ISI_SATUAN,
        //             b.H_DP as H_BELI,
        //             '' as H_JUAL,
        //             (REPLACE(a.saldo_akhir,',','')*a.konversi) as saldo_awal,
        //             '' as MASUK_PBK,
        //             '' as MASUK_SUPP,
        //             '' as RETUR_SAL,
        //             '' as RETUR_SUPP,
        //             '' as SALES,
        //             '' as KVBELI,
        //             '' as KVJUAL,
        //             '' as KVSISA,
        //             '' as KVRETUR,
        //             '' as RUSAK,
        //             '' as SISIH,
        //             '' as PINJAM,
        //             '' as BPINJAM,
        //             '' as KR_GUDANG,
        //             '' as KR_KANVAS,
        //             'PST' as KODE_GDG,
        //             'PUSAT' as NAMA_GDG,
        //             '' as HASIL,
        //             '' as RETUR_DEPO,
        //             '' as MINTA_DEPO,
        //             '' as LIMIT_STOK,
        //             '' as STOK_DEPO,
        //             '' as KR_MIN,
        //             '' as TUKAR_MSK,
        //             '' as TUKAR_KLR,
        //             '' as MSK_GDG_PST,
        //             '' as KLR_GDDEPO,
        //             '' as KLR_GD_PST,
        //             '' as MSK_GDDEPO,
        //             '' as SLDO_TUKAR,
        //             '' as TUKAR_PRC,
        //             '$nocab' as NOCAB,
        //             '$bln_stok' as BULAN
                    
        //             FROM 
        //             (
        //                 SELECT a.kodeprod, b.NAMAPROD, b.supp, a.satuan, a.saldo_akhir, a.konversi
        //                 FROM site.t_temp_import_stock a
        //                 INNER JOIN mpm.tabprod b ON a.kodeprod = b.KODEPROD
        //                 WHERE a.created_by = $upload_sa->userid and a.created_date = (
        //                             SELECT MAX(b.created_date) FROM site.t_temp_import_stock b
        //                             WHERE b.created_by = $upload_sa->userid)
        //             )a LEFT JOIN
        //             (
        //                 SELECT a.* from mpm.prod_detail a
        //                 WHERE a.created = (
        //                         SELECT MAX(b.created)
        //                         FROM mpm.prod_detail b
        //                         where a.kodeprod = b.kodeprod
        //                         GROUP BY a.kodeprod
        //                     )
        //             )b on a.kodeprod = b.kodeprod
        //         ";
        //     $this->db->query($stok);    
        // }
        
        // ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

        $update_upload = "update mpm.upload set status='1', status_closing = $closing, omzet ='$x' where id='$upload_sa->id'";
        $this->db->query($update_upload);
    }
}
?>