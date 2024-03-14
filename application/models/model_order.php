<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_order extends CI_Model
{
    public function getSupp()
    {
        $supp=$this->session->userdata('supp');
        if($supp=='000')
        {
            return $this->db->query("select a.supp, a.namasupp
            from mpm.tabsupp a INNER JOIN mpm.tabprod b 
            on a.SUPP = b.SUPP
            where b.active = 1
            GROUP BY b.supp");
        }
        else
        {
            return $this->db->query("select a.supp, a.namasupp
            from mpm.tabsupp a INNER JOIN mpm.tabprod b 
            on a.SUPP = b.SUPP
            where b.active = 1 and supp='.$supp
            GROUP BY b.supp");
        }
    }

    public function get_kodeprod($supp){   
        $sql_kodeprod = "
            select kodeprod, namaprod, grup, supp, subgroup
            from mpm.tabprod a
            where a.supp = '$supp' and produksi = '1'
            order by kodeprod asc
        ";
        $proses_kodeprod = $this->db->query($sql_kodeprod)->result();
        return $proses_kodeprod;
    }

    public function getCustInfo($customer = null)
    {
        if ($customer == null) {
            $userid = $this->session->userdata('id');
        } else {
            $userid = $customer;
        }
        
        $sql="
                select id, a.name, company,npwp, address,email, b.alamat, b.kode_alamat from mpm.user a
                LEFT JOIN mpm.t_alamat b on a.username = b.username 
                WHERE id = $userid and b.status = '1'
                ORDER BY b.kode_alamat = 'S1D1D' desc
                ";
        $proses = $this->db->query($sql);
        // echo "<pre>";
        // print_r($sql);
        // echo "</pre>";
        return $proses;

    }

    public function getPo_temp($signature)
    {
        $sql = "
            SELECT a.*, b.h_dp
            FROM
            (
                SELECT a.*, b.kode_prc, b.namaprod, b.isisatuan, b.berat, b.volume
                FROM site.t_temp_po a
                INNER JOIN mpm.tabprod b on a.kodeprod = b.kodeprod
                WHERE signature = '$signature'
            )a LEFT JOIN
            (
                SELECT a.kodeprod, a.h_dp FROM mpm.prod_detail a
                WHERE a.tgl = (
                    SELECT MAX(b.tgl)
                    FROM mpm.prod_detail b
                    WHERE a.kodeprod = b.kodeprod
                    GROUP BY b.kodeprod)
            )b on a.kodeprod = b.kodeprod
        ";

        $proses = $this->db->query($sql);
        return $proses;
    }

    public function getProductDetail($kodeprod,$userid)
    {    
        $sql="select exclude,level,diskon from mpm.user where id='$userid'";
        $query=$this->db->query($sql);

        $row=$query->row();
        $level=$row->level;
        $diskon=$row->diskon;
        $exclude=$row->exclude;

        switch($level)
        {
            case 4:
                if($exclude==1)
                {
                    $sql="
                        select  a.kodeprod,a.kode_prc,a.namaprod,a.supp,
                                a.grupprod,a.isisatuan,b.h_pbf as harga,
                                b.d_dp as diskon,h_beli_dp as harga_beli,
                                d_beli_dp as diskon_beli, a.berat, a.volume
                        from    mpm.tabprod a inner join mpm.prod_detail b using(kodeprod)
                                
                        where   a.kodeprod='$kodeprod' and tgl=(
                                select max(tgl) 
                                from mpm.prod_detail 
                                where kodeprod='$kodeprod'
                                )
                    ";
                }
                else
                {
                    $sql="
                        select  a.kodeprod,a.kode_prc,a.namaprod,
                                a.supp,a.grupprod,a.isisatuan,
                                b.h_dp as harga,
                                b.d_dp as diskon, h_beli_dp as harga_beli,d_beli_dp as diskon_beli, a.berat, a.volume
                        from    mpm.tabprod a inner join mpm.prod_detail b using(kodeprod) 
                                
                        where   a.kodeprod='$kodeprod' and 
                                tgl=(
                                    select max(tgl) 
                                    from    mpm.prod_detail 
                                    where   kodeprod='$kodeprod'
                                )
                    ";
                }
                break;
            case 5:
                    $sql="
                        select  a.kodeprod,a.kode_prc,a.namaprod,a.supp,a.grupprod,a.isisatuan,
                                b.h_pbf as harga,
                                '$diskon' as diskon,
                                h_beli_pbf as harga_beli,d_beli_pbf as diskon_beli, a.berat, a.volume
                        from    mpm.tabprod a inner join mpm.prod_detail b using(kodeprod)
                                
                        where   a.kodeprod='$kodeprod' and tgl=(
                                    select  max(tgl) 
                                    from    mpm.prod_detail 
                                    where   kodeprod='$kodeprod'
                                    )
                    "; 
                    // echo "<pre>";
                    // print_r($sql);
                    // echo "</pre>";
                break;

            case 6:
                $sql="
                    select  a.kodeprod,
                            a.kode_prc,
                            a.namaprod,
                            a.supp,a.grupprod,a.isisatuan,b.h_bsp as harga ,b.d_bsp as diskon, 
                            h_beli_bsp as harga_beli,d_beli_bsp as diskon_beli, a.berat, a.volume
                    from    mpm.tabprod a inner join mpm.prod_detail b using(kodeprod) 
                            
                    where   a.kodeprod='$kodeprod' and 
                            tgl=(
                                select  max(tgl) 
                                from    mpm.prod_detail 
                                where   kodeprod='$kodeprod'
                            )
                ";
                break;
            case 7:
                $sql="
                    select  a.kodeprod,
                            a.kode_prc,
                            a.namaprod,a.supp,a.grupprod,a.isisatuan,
                            b.h_dp as harga,b.d_dp as diskon, 
                            h_beli_dp as harga_beli,d_beli_dp as diskon_beli, a.berat, a.volume
                    from    mpm.tabprod a inner join mpm.prod_detail b using(kodeprod) 
                            
                    where   a.kodeprod='$kodeprod' and 
                            tgl=(
                                select  max(tgl) 
                                from    mpm.prod_detail 
                                where   kodeprod='$kodeprod'
                            )
                ";        
                break;
        }

        $hasil=$this->db->query($sql);
        return $hasil; 

    }

    // public function list_client()
    // {
    //     $sql="
    //         select  a.id,a.kode_dp, a.username, a.company 
    //         from    mpm.`user` a
    //         where   level in (4,5,6,7) and active = 1
    //         order by a.company       
    //     ";
    //     return $this->db->query($sql);
    // }

    public function list_client()
    {
        $sql="
            select  a.id,a.kode_dp, a.username, a.company 
            from    mpm.`user` a
            where   a.kode_lang is not null and a.kode_lang <> '' and a.active = 1 and a.username not in ('sadmin','deltomed')
            order by a.company       
        ";
        // $sql = "
        // select a.id, a.username, a.company, a.active
        // from mpm.`user` a
        // where a.kode_lang is not null and a.kode_lang <> '' and a.active = 1 and a.username not in ('sadmin','deltomed')
        // ORDER BY company asc
        // ";
        return $this->db->query($sql);
    }

    public function tambah($table, $data){
        $this->db->insert($table, $data);
        return $this->db->insert_id(); 
    }

    public function edit($table, $data){
        // var_dump($data);die;
        $this->db->where('id', $data['id']);
        return $this->db->update($table, $data); 
        // $signature = $this->get_signature($this->db->insert_id());
        // return $signature;
    }

    public function get_data_cart($signature){  
        $id = $this->session->userdata('id');
        
        $sql = "
            select a.id, a.kodeprod, a.qty, b.namaprod, if(b.berat is null, 0, b.berat) as berat, if(b.volume is null, 0, b.volume) as volume, a.status_sudah_updated
            from site.t_temp_po a
            left join mpm.tabprod b on a.kodeprod = b.kodeprod
            where a.created_by = '$id' and signature = '$signature'
        ";
        $proses = $this->db->query($sql);
        return $proses;
    }

    public function get_total_berat($signature){  
        $id = $this->session->userdata('id');
        
        $sql = "
            select sum(if(b.berat is null, 0, b.berat)*a.qty) as total_berat, format(sum(if(b.volume is null, 0, b.volume)*a.qty),4) as total_volume
            from site.t_temp_po a
            left join mpm.tabprod b on a.kodeprod = b.kodeprod
            where a.created_by = '$id' and signature = '$signature'
        ";
        $proses = $this->db->query($sql);
        return $proses;
    }

    public function get_status_sudah_updated($signature){  
        $id = $this->session->userdata('id');
        
        $sql = "
            select a.status_sudah_updated
            from site.t_temp_po a
            where a.created_by = '$id' and signature = '$signature'
            order by a.status_sudah_updated asc
            limit 1
        ";
        $proses = $this->db->query($sql);
        return $proses;
    }

    // public function pdf($id, $supp)
    // {
    //     if ($supp != '012') {
    //         $sql = "
    //             select 	'1' as urutan, b.kodeprod,b.namaprod,b.kode_prc,b.banyak as banyak ,if(isnull(c.kode_dp),a.company,
    //                     concat(a.company,concat(' (',concat(c.kode_dp,')')))) as company,
    //                     a.npwp,d.alamat as alamat, a.alamat_kirim,
    //                     if(a.tipe='S','SPK',if(a.tipe='R','REPLENISHMENT','ALOKASI'))as tipe,
    //                     date_format(a.tglpo,'%d %M %Y') as tglpo,a.nopo,a.po_ref,c.kode_dp, note,
    //                     format((b.banyak_karton*if(b.berat is null,0,b.berat)),0) as sub_berat, format((b.banyak_karton*if(b.volume is null,0,b.volume)),3) as sub_volume
    //             from mpm.po a inner join mpm.po_detail b inner join mpm.user c on a.id=b.id_ref and a.userid=c.id 
    //             LEFT JOIN
    //             (
    //                 select a.kode_alamat,a.alamat
    //                 from mpm.t_alamat a
    //                 where a.`status` = 1
    //                 GROUP BY a.kode_alamat
    //             )d on a.kode_alamat = d.kode_alamat
    //             where a.id = $id and a.deleted = 0 and b.deleted = 0 and b.kodeprod not in (select kodeprod
    //             from db_produk.t_product_po)
        
    //             union all
        
    //             select 	'1' as urutan, b.kodeprod,b.namaprod,b.kode_prc, concat(b.banyak/e.satuan_box, ' Dus') as banyak ,if(isnull(c.kode_dp),a.company,
    //                     concat(a.company,concat(' (',concat(c.kode_dp,')')))) as company,
    //                     a.npwp,d.alamat as alamat, a.alamat_kirim, 
    //                     if(a.tipe='S','SPK',if(a.tipe='R','REPLENISHMENT','ALOKASI'))as tipe,
    //                     date_format(a.tglpo,'%d %M %Y') as tglpo,a.nopo,a.po_ref,c.kode_dp, note,
    //                     format((b.banyak_karton*if(b.berat is null,0,b.berat)),0) as sub_berat, format((b.banyak_karton*if(b.volume is null,0,b.volume)),3) as sub_volume
    //             from mpm.po a inner join mpm.po_detail b inner join mpm.user c on a.id=b.id_ref and a.userid=c.id LEFT JOIN
    //             (
    //                 select a.kode_alamat,a.alamat
    //                 from mpm.t_alamat a
    //                 where a.`status` = 1
    //                 GROUP BY a.kode_alamat
    //             )d on a.kode_alamat = d.kode_alamat
    //             left join
    //             (
    //                 select *
    //                 from db_produk.t_product_po
    //             )e on b.kodeprod = e.kodeprod
    //             where a.id = $id and a.deleted = 0 and b.deleted = 0 and b.kodeprod in (select kodeprod
    //             from db_produk.t_product_po )
        
    //             union all
        
    //             SELECT '2' as urutan, 'TOTAL','','',sum(a.banyak), a.company,a.npwp,a.alamat, a.alamat_kirim, a.tipe,
    //                     a.tglpo,a.nopo,a.po_ref,a.kode_dp, a.note,format(sum(if(a.total_berat is null, 0, a.total_berat)),0) as total_berat,
    //                     format(sum(if(a.total_volume is null, 0,a.total_volume)),3) as total_volume
    //             FROM
    //             (
    //                 select 	b.banyak as banyak,if(isnull(c.kode_dp),a.company,
    //                         concat(a.company,concat(' (',concat(c.kode_dp,')')))) as company,
    //                         a.npwp,d.alamat as alamat, a.alamat_kirim,
    //                         if(a.tipe='S','SPK',if(a.tipe='R','REPLENISHMENT','ALOKASI'))as tipe,
    //                         date_format(a.tglpo,'%d %M %Y') as tglpo,a.nopo,a.po_ref,c.kode_dp, note,
    //                         b.banyak_karton*b.berat as total_berat, b.banyak_karton*b.volume as total_volume
    //                 from mpm.po a inner join mpm.po_detail b inner join mpm.user c on a.id=b.id_ref and a.userid=c.id 
    //                 LEFT JOIN
    //                 (
    //                     select a.kode_alamat,a.alamat
    //                     from mpm.t_alamat a
    //                     where a.`status` = 1
    //                     GROUP BY a.kode_alamat
    //                 )d on a.kode_alamat = d.kode_alamat
    //                 where a.id = $id and a.deleted = 0 and b.deleted = 0 
    //             )a
    //             order by urutan, kodeprod
    //         ";
    //     }else {
    //         $sql = "
    //             select 	'1' as urutan, b.kodeprod,b.namaprod,b.kode_prc,b.banyak_karton as banyak ,if(isnull(c.kode_dp),a.company,
    //                     concat(a.company,concat(' (',concat(c.kode_dp,')')))) as company,
    //                     a.npwp,d.alamat as alamat, a.alamat_kirim,
    //                     if(a.tipe='S','SPK',if(a.tipe='R','REPLENISHMENT','ALOKASI'))as tipe,
    //                     date_format(a.tglpo,'%d %M %Y') as tglpo,a.nopo,a.po_ref,c.kode_dp, note,
    //                     format((b.banyak_karton*if(b.berat is null,0,b.berat)),0) as sub_berat, format((b.banyak_karton*if(b.volume is null,0,b.volume)),3) as sub_volume
    //             from mpm.po a inner join mpm.po_detail b inner join mpm.user c on a.id=b.id_ref and a.userid=c.id 
    //             LEFT JOIN
    //             (
    //                 select a.kode_alamat,a.alamat
    //                 from mpm.t_alamat a
    //                 where a.`status` = 1
    //                 GROUP BY a.kode_alamat
    //             )d on a.kode_alamat = d.kode_alamat
    //             where a.id = $id and a.deleted = 0 and b.deleted = 0 and b.kodeprod not in (select kodeprod
    //             from db_produk.t_product_po)

    //             union all

    //             select 	'1' as urutan, b.kodeprod,b.namaprod,b.kode_prc, concat(b.banyak/e.satuan_box, ' Dus') as banyak ,if(isnull(c.kode_dp),a.company,
    //                     concat(a.company,concat(' (',concat(c.kode_dp,')')))) as company,
    //                     a.npwp,d.alamat as alamat, a.alamat_kirim,
    //                     if(a.tipe='S','SPK',if(a.tipe='R','REPLENISHMENT','ALOKASI'))as tipe,
    //                     date_format(a.tglpo,'%d %M %Y') as tglpo,a.nopo,a.po_ref,c.kode_dp, note,
    //                     format((b.banyak_karton*if(b.berat is null,0,b.berat)),0) as sub_berat, format((b.banyak_karton*if(b.volume is null,0,b.volume)),3) as sub_volume
    //             from mpm.po a inner join mpm.po_detail b inner join mpm.user c on a.id=b.id_ref and a.userid=c.id LEFT JOIN
    //             (
    //                 select a.kode_alamat,a.alamat
    //                 from mpm.t_alamat a
    //                 where a.`status` = 1
    //                 GROUP BY a.kode_alamat
    //             )d on a.kode_alamat = d.kode_alamat
    //             left join
    //             (
    //                 select *
    //                 from db_produk.t_product_po
    //             )e on b.kodeprod = e.kodeprod
    //             where a.id = $id and a.deleted = 0 and b.deleted = 0 and b.kodeprod in (select kodeprod
    //             from db_produk.t_product_po )

    //             union all

    //             SELECT '2' as urutan, 'TOTAL','','',sum(a.banyak), a.company,a.npwp,a.alamat, a.alamat_kirim, a.tipe,
    //                     a.tglpo,a.nopo,a.po_ref,a.kode_dp, a.note,format(sum(if(a.total_berat is null, 0, a.total_berat)),0) as total_berat,
    //                     format(sum(if(a.total_volume is null, 0, a.total_volume)),3) as total_volume
    //             FROM
    //             (
    //                 select 	b.banyak_karton as banyak,if(isnull(c.kode_dp),a.company,
    //                         concat(a.company,concat(' (',concat(c.kode_dp,')')))) as company,
    //                         a.npwp,d.alamat as alamat, a.alamat_kirim,
    //                         if(a.tipe='S','SPK',if(a.tipe='R','REPLENISHMENT','ALOKASI'))as tipe,
    //                         date_format(a.tglpo,'%d %M %Y') as tglpo,a.nopo,a.po_ref,c.kode_dp, note,
    //                         b.banyak_karton*b.berat as total_berat, b.banyak_karton*b.volume as total_volume
    //                 from mpm.po a inner join mpm.po_detail b inner join mpm.user c on a.id=b.id_ref and a.userid=c.id 
    //                 LEFT JOIN
    //                 (
    //                     select a.kode_alamat,a.alamat
    //                     from mpm.t_alamat a
    //                     where a.`status` = 1
    //                     GROUP BY a.kode_alamat
    //                 )d on a.kode_alamat = d.kode_alamat
    //                 where a.id = $id and a.deleted = 0 and b.deleted = 0 
    //             )a
    //             order by urutan, kodeprod
    //         ";
    //     }
        
        
    //     $proses = $this->db->query($sql);
    //     return $proses;
    // }

    public function pdf($id, $supp)
    {
        if ($supp != '012') {
            $sql = "
                select 	'1' as urutan, b.kodeprod,b.namaprod,b.kode_prc,
                        b.banyak as banyak,
                        b.banyak_karton as banyak_karton,
                        if(isnull(c.kode_dp),a.company,
                        concat(a.company,concat(' (',concat(c.kode_dp,')')))) as company,
                        a.npwp,d.alamat as alamat, a.alamat_kirim,
                        if(a.tipe='S','SPK',if(a.tipe='R','REPLENISHMENT','ALOKASI'))as tipe,
                        date_format(a.tglpo,'%d %M %Y') as tglpo,a.nopo,a.po_ref,c.kode_dp, note,
                        format((b.banyak_karton*if(b.berat is null,0,b.berat)),0) as sub_berat, format((b.banyak_karton*if(b.volume is null,0,b.volume)),3) as sub_volume
                from mpm.po a inner join mpm.po_detail b inner join mpm.user c on a.id=b.id_ref and a.userid=c.id 
                LEFT JOIN
                (
                    select a.kode_alamat,a.alamat
                    from mpm.t_alamat a
                    where a.`status` = 1
                    GROUP BY a.kode_alamat
                )d on a.kode_alamat = d.kode_alamat
                where a.id = $id and a.deleted = 0 and b.deleted = 0 and b.kodeprod not in (select kodeprod
                from db_produk.t_product_po)
        
                union all
        
                select 	'1' as urutan, b.kodeprod,b.namaprod,b.kode_prc, 
                        concat(b.banyak/e.satuan_box, ' Dus') as banyak,
                        b.banyak_karton as banyak_karton,
                        if(isnull(c.kode_dp),a.company,
                        concat(a.company,concat(' (',concat(c.kode_dp,')')))) as company,
                        a.npwp,d.alamat as alamat, a.alamat_kirim, 
                        if(a.tipe='S','SPK',if(a.tipe='R','REPLENISHMENT','ALOKASI'))as tipe,
                        date_format(a.tglpo,'%d %M %Y') as tglpo,a.nopo,a.po_ref,c.kode_dp, note,
                        format((b.banyak_karton*if(b.berat is null,0,b.berat)),0) as sub_berat, format((b.banyak_karton*if(b.volume is null,0,b.volume)),3) as sub_volume
                from mpm.po a inner join mpm.po_detail b inner join mpm.user c on a.id=b.id_ref and a.userid=c.id LEFT JOIN
                (
                    select a.kode_alamat,a.alamat
                    from mpm.t_alamat a
                    where a.`status` = 1
                    GROUP BY a.kode_alamat
                )d on a.kode_alamat = d.kode_alamat
                left join
                (
                    select *
                    from db_produk.t_product_po
                )e on b.kodeprod = e.kodeprod
                where a.id = $id and a.deleted = 0 and b.deleted = 0 and b.kodeprod in (select kodeprod
                from db_produk.t_product_po )
        
                union all
        
                SELECT  '2' as urutan, 'TOTAL','','',
                        '', '' as banyak_karton, 
                        a.company,a.npwp,a.alamat, a.alamat_kirim, a.tipe,
                        a.tglpo,a.nopo,a.po_ref,a.kode_dp, a.note,format(sum(if(a.total_berat is null, 0, a.total_berat)),0) as total_berat,
                        format(sum(if(a.total_volume is null, 0,a.total_volume)),3) as total_volume
                FROM
                (
                    select 	b.banyak as banyak,
                            b.banyak_karton as banyak_karton,
                            if(isnull(c.kode_dp),a.company,
                            concat(a.company,concat(' (',concat(c.kode_dp,')')))) as company,
                            a.npwp,d.alamat as alamat, a.alamat_kirim,
                            if(a.tipe='S','SPK',if(a.tipe='R','REPLENISHMENT','ALOKASI'))as tipe,
                            date_format(a.tglpo,'%d %M %Y') as tglpo,a.nopo,a.po_ref,c.kode_dp, note,
                            b.banyak_karton*b.berat as total_berat, b.banyak_karton*b.volume as total_volume
                    from mpm.po a inner join mpm.po_detail b inner join mpm.user c on a.id=b.id_ref and a.userid=c.id 
                    LEFT JOIN
                    (
                        select a.kode_alamat,a.alamat
                        from mpm.t_alamat a
                        where a.`status` = 1
                        GROUP BY a.kode_alamat
                    )d on a.kode_alamat = d.kode_alamat
                    where a.id = $id and a.deleted = 0 and b.deleted = 0 
                )a
                order by urutan
            ";
        }else {
            $sql = "
                select 	'1' as urutan, b.kodeprod,b.namaprod,b.kode_prc,
                        b.banyak_karton as banyak,if(isnull(c.kode_dp),a.company,
                        concat(a.company,concat(' (',concat(c.kode_dp,')')))) as company,
                        a.npwp,d.alamat as alamat, a.alamat_kirim,
                        if(a.tipe='S','SPK',if(a.tipe='R','REPLENISHMENT','ALOKASI'))as tipe,
                        date_format(a.tglpo,'%d %M %Y') as tglpo,a.nopo,a.po_ref,c.kode_dp, note,
                        format((b.banyak_karton*if(b.berat is null,0,b.berat)),0) as sub_berat, format((b.banyak_karton*if(b.volume is null,0,b.volume)),3) as sub_volume
                from mpm.po a inner join mpm.po_detail b inner join mpm.user c on a.id=b.id_ref and a.userid=c.id 
                LEFT JOIN
                (
                    select a.kode_alamat,a.alamat
                    from mpm.t_alamat a
                    where a.`status` = 1
                    GROUP BY a.kode_alamat
                )d on a.kode_alamat = d.kode_alamat
                where a.id = $id and a.deleted = 0 and b.deleted = 0 and b.kodeprod not in (select kodeprod
                from db_produk.t_product_po)

                union all

                select 	'1' as urutan, b.kodeprod,b.namaprod,b.kode_prc, concat(b.banyak/e.satuan_box, ' Dus') as banyak ,if(isnull(c.kode_dp),a.company,
                        concat(a.company,concat(' (',concat(c.kode_dp,')')))) as company,
                        a.npwp,d.alamat as alamat, a.alamat_kirim,
                        if(a.tipe='S','SPK',if(a.tipe='R','REPLENISHMENT','ALOKASI'))as tipe,
                        date_format(a.tglpo,'%d %M %Y') as tglpo,a.nopo,a.po_ref,c.kode_dp, note,
                        format((b.banyak_karton*if(b.berat is null,0,b.berat)),0) as sub_berat, format((b.banyak_karton*if(b.volume is null,0,b.volume)),3) as sub_volume
                from mpm.po a inner join mpm.po_detail b inner join mpm.user c on a.id=b.id_ref and a.userid=c.id LEFT JOIN
                (
                    select a.kode_alamat,a.alamat
                    from mpm.t_alamat a
                    where a.`status` = 1
                    GROUP BY a.kode_alamat
                )d on a.kode_alamat = d.kode_alamat
                left join
                (
                    select *
                    from db_produk.t_product_po
                )e on b.kodeprod = e.kodeprod
                where a.id = $id and a.deleted = 0 and b.deleted = 0 and b.kodeprod in (select kodeprod
                from db_produk.t_product_po )

                union all

                SELECT '2' as urutan, 'TOTAL','','',sum(a.banyak), a.company,a.npwp,a.alamat, a.alamat_kirim, a.tipe,
                        a.tglpo,a.nopo,a.po_ref,a.kode_dp, a.note,format(sum(if(a.total_berat is null, 0, a.total_berat)),0) as total_berat,
                        format(sum(if(a.total_volume is null, 0, a.total_volume)),3) as total_volume
                FROM
                (
                    select 	b.banyak_karton as banyak,if(isnull(c.kode_dp),a.company,
                            concat(a.company,concat(' (',concat(c.kode_dp,')')))) as company,
                            a.npwp,d.alamat as alamat, a.alamat_kirim,
                            if(a.tipe='S','SPK',if(a.tipe='R','REPLENISHMENT','ALOKASI'))as tipe,
                            date_format(a.tglpo,'%d %M %Y') as tglpo,a.nopo,a.po_ref,c.kode_dp, note,
                            b.banyak_karton*b.berat as total_berat, b.banyak_karton*b.volume as total_volume
                    from mpm.po a inner join mpm.po_detail b inner join mpm.user c on a.id=b.id_ref and a.userid=c.id 
                    LEFT JOIN
                    (
                        select a.kode_alamat,a.alamat
                        from mpm.t_alamat a
                        where a.`status` = 1
                        GROUP BY a.kode_alamat
                    )d on a.kode_alamat = d.kode_alamat
                    where a.id = $id and a.deleted = 0 and b.deleted = 0 
                )a
                order by urutan, kodeprod
            ";
        }

        // echo "<pre>";
        // print_r($sql);
        // echo "</pre>";

        // die;        
        
        $proses = $this->db->query($sql);
        return $proses;
    }

    public function list_order()
    {

        $segment = $this->uri->segment('3');
        if ($segment) {
            $limit = $segment;
        }else{
            $limit = 20;
        }

        $sql = "
        select 	a.id, a.company, a.nopo, date(a.tglpo) as tglpo, date(a.tglpesan) as tglpesan, a.supp, a.tipe, a.userid,a.open as open,a.status,
				b.banyak, b.harga, sum(b.banyak * b.harga) as total,
				d.branch_name, d.nama_comp, e.NAMASUPP as namasupp
        from mpm.po a INNER JOIN 
        (
            select *
            from mpm.po_detail a
            where a.deleted = 0
            ORDER BY a.id desc
            limit 10000 
        )b on a.id = b.id_ref LEFT JOIN mpm.`user` c 
            on a.userid = c.id LEFT JOIN
            (
                select a.branch_name, a.nama_comp, a.kode_comp
                from mpm.tbl_tabcomp a
                where `status` = 1
                GROUP BY a.kode_comp
            )d on c.username = d.kode_comp LEFT JOIN mpm.tabsupp e
            on a.supp = e.SUPP
        where b.deleted = 0 and a.deleted = 0
        GROUP BY a.id
        ORDER BY id desc
        limit $limit
        ";

        // echo "<pre><br><br><br><br>";
        // print_r($sql);
        // echo "</pre>";

        $proses = $this->db->query($sql);
        if($proses){
            return $proses;
        }else{
            return array();
        }
    }

    public function list_order_detail($id_po)
    {

        $sql = "
        select 	a.id,a.userid, a.nopo, a.supp, a.tglpo, date(a.tglpesan) as tglpesan, a.tipe, a.`open`, a.company, 
                a.alamat, a.alamat_kirim, a.note,a.status,a.supp,
                a.status_approval, a.alasan_approval,a.po_ref,
                c.branch_name,c.nama_comp,d.NAMASUPP as namasupp, c.kode, year(a.tglpesan) as tahun, month(a.tglpesan) as bulan, a.lock
        from mpm.po a LEFT JOIN mpm.`user` b
            on a.userid = b.id LEFT JOIN
            (
                    select a.branch_name, a.nama_comp, a.kode_comp, concat(a.kode_comp,a.nocab) as kode
                    from mpm.tbl_tabcomp a
                    where a.`status` = 1 and active='1'
                    GROUP BY a.kode_comp
            )c on b.username = c.kode_comp LEFT JOIN mpm.tabsupp d 
                on a.supp = d.SUPP
        where a.id = '$id_po'
        ";

        // echo "<pre>";
        // print_r($sql);
        // echo "</pre>";

        $proses = $this->db->query($sql);
        if($proses){
            return $proses;
        }else{
            return array();
        }

    }

    public function list_order_produk($id_po)
    {

        $sql = "
            select  b.id as id_kodeprod, b.kodeprod, b.namaprod, b.kode_prc, b.banyak, b.stock_akhir, b.rata as rata, b.doi, b.deleted, b.git as git, b.supp
            from    mpm.po_detail b
            where   b.id_ref = '$id_po' and b.deleted <> 1
            order by b.kodeprod asc
        ";

        // echo "<pre>";
        // print_r($sql);
        // echo "</pre>";

        $proses = $this->db->query($sql);
        if($proses){
            return $proses;
        }else{
            return array();
        }
    }

    public function proses_doi($id_po,$tahun,$bulan,$tglpesan,$kode,$supp,$created)
    {
        $id = $this->session->userdata('id');
        $nocab = substr($kode,3,2);
        $kode_comp = substr($kode,0,3);
        $tahun_2 = $tahun-1;

        // $bulan = 8;
        // $tglpesan = '2020-08-01';
        $akhirbulan = $bulan - 6;

        // echo "bulan : ".$bulan."br>";
        // echo "tglpesan : ".$tglpesan."br>";
        // echo "akhirbulan : ".$akhirbulan."br>";

        if($akhirbulan<=0)
        {             
            $timestamp = strtotime($tglpesan);
            $bulan_awal = date('m', strtotime('-6 month', $timestamp));
            echo "bulan_awal : ".$bulan_awal."<br>";

            for ($i=(int)$bulan_awal; $i<12 ; $i++) { 
                $x[] = $i.',';
            }

            $bulan_1 = implode($x).'12';
            echo "bulan_1 : ".$bulan_1."<br>";

            $bulan_awal_b = '1';
            $bulan_akhir_b = (int)$bulan-1;
            echo "bulan_akhir_b : ".$bulan_akhir_b."<br>";

            if ($bulan_akhir_b == '0') {  

                $fi = "             
                    insert into db_po.t_rata_temp       
                    select $id_po, '$kode', kodeprod, sum(banyak) as unit, sum(banyak)/6 as rata, '$created', $id
                    from
                    (
                        select if(kodeprod = '700009','010067',if(kodeprod = '700012', '010094', kodeprod)) kodeprod,banyak
                        from data$tahun_2.fi
                        where bulan in ($bulan_1) and nocab = '$nocab'                        
                        union ALL                    
                        select if(kodeprod = '700009','010067',if(kodeprod = '700012', '010094', kodeprod)) kodeprod,banyak
                        from data$tahun_2.ri
                        where bulan in ($bulan_1) and nocab = '$nocab'
                    )a group by kodeprod
                ";
                $proses = $this->db->query($fi);

                // echo "<pre>";
                // print_r($fi);
                // echo "</pre>";

            }else{

                for ($i=1; $i<(int)$bulan_akhir_b ; $i++) { 
                    $a[] = $i.',';
                }$bulan_2 = implode($a).(int)$bulan_akhir_b;
                echo "bulan_2 : ".$bulan_2."<br>";

                $fi = "            
                    insert into db_po.t_rata_temp        
                    select $id_po, '$kode', kodeprod, sum(banyak) as unit, sum(banyak)/6 as rata, '$created', $id
                    from
                    (
                        select if(kodeprod = '700009','010067',if(kodeprod = '700012', '010094', kodeprod)) kodeprod,banyak
                        from data$tahun_2.fi
                        where bulan in ($bulan_1) and nocab = '$nocab'                        
                        union ALL
                        select if(kodeprod = '700009','010067',if(kodeprod = '700012', '010094', kodeprod)) kodeprod,banyak
                        from data$tahun_2.fi
                        where bulan in ($bulan_2) and nocab = '$nocab'                        
                        union ALL                    
                        select if(kodeprod = '700009','010067',if(kodeprod = '700012', '010094', kodeprod)) kodeprod,banyak
                        from data$tahun.ri
                        where bulan in ($bulan_1) and nocab = '$nocab'                        
                        union ALL
                        select if(kodeprod = '700009','010067',if(kodeprod = '700012', '010094', kodeprod)) kodeprod,banyak
                        from data$tahun.ri
                        where bulan in ($bulan_2) and nocab = '$nocab'
                    )a group by kodeprod
                ";
                $proses = $this->db->query($fi);
                // echo "<pre>";
                // print_r($fi);
                // echo "</pre>";
            }
        }else{

            $timestamp = strtotime($tglpesan);
            $bulan_awal = date('m', strtotime('-6 month', $timestamp));
            echo "bulan_awal : ".$bulan_awal."<br>";
            $bulan_2 = $bulan - 1;

            for ($i=(int)$bulan_awal; $i<$bulan_2 ; $i++) { 
                $x[] = $i.',';
            }

            $bulan_1 = implode($x).$bulan_2;
            echo "bulan_1 : ".$bulan_1."<br>";

            $fi = "             
                insert into db_po.t_rata_temp       
                select $id_po, '$kode', kodeprod, sum(banyak) as unit, sum(banyak)/6 as rata, '$created', $id
                from
                (
                    select if(kodeprod = '700009','010067',if(kodeprod = '700012', '010094', kodeprod)) kodeprod,banyak
                    from data$tahun.fi
                    where bulan in ($bulan_1) and nocab = '$nocab'                        
                    union ALL                    
                    select if(kodeprod = '700009','010067',if(kodeprod = '700012', '010094', kodeprod)) kodeprod,banyak
                    from data$tahun.ri
                    where bulan in ($bulan_1) and nocab = '$nocab'
                )a group by kodeprod
            ";
            $proses = $this->db->query($fi);
            
            // echo "<pre>";
            // print_r($fi);
            // echo "</pre>";

        }
        $sql="
            insert into db_po.t_git_temp
            select  $id_po, '$kode',if(b.kodeprod = '700009','010067',if(b.kodeprod = '700012', '010094', b.kodeprod)) kodeprod, 
                    sum(b.banyak) as git, '$created',$id
            from    mpm.po a INNER JOIN mpm.po_detail b
                        on a.id = b.id_ref
                    LEFT JOIN mpm.`user` c on a.userid = c.id
            where   year(tglpo) in (date_format(date(now()) - INTERVAL '1' MONTH,'%Y'),year(date(now()))) and 
                    month(tglpo) in (date_format(date(now()) - INTERVAL '1' MONTH,'%m'),month(date(now()))) and  
                    a.deleted = 0 and b.deleted = 0 and (b.status_terima is null or b.status_terima = null or b.status_terima = '0') and 
                    (a.nopo is not null and left(a.nopo,4) <> '/mpm') and c.username = '$kode_comp'
            GROUP BY b.kodeprod
            ORDER BY b.kodeprod
        ";
        
        $proses = $this->db->query($sql);

        $sql = "
            update mpm.po_detail a
            set a.stock_akhir = (
                select  sum( if(kode_gdg='PST',
                        ((Saldoawal+masuk_pbk+masuk_supp+retur_sal+bPinjam+kvretur+kr_gudang+retur_depo)-
                        (sales+pinjam+minta_depo+kvbeli+kr_min)-(rusak+sisih)),(Saldoawal+masuk_pbk+retur_sal+kvretur+bPinjam+tukar_msk+msk_gddepo) -
                        (sales+Pinjam+kvbeli+retur_depo+tukar_klr+klr_gddepo))) as stok
                from    data$tahun.st b
                where	b.nocab = '$nocab' and substr(b.bulan,3) = $bulan and a.kodeprod = b.kodeprod
                group by b.nocab, b.kodeprod
                order by b.kodeprod
            ), a.rata = (
                select 	b.rata
                from 	db_po.t_rata_temp b
                where 	a.kodeprod = b.kodeprod and b.id_po = $id_po and created_date = '$created'
            ), a.git = (
                select 	b.git
                from 	db_po.t_git_temp b
                where 	a.kodeprod = b.kodeprod and b.id_po = $id_po and created_date = '$created'
            )
            where a.id_ref = $id_po
        ";
        // echo "<pre>";
        // print_r($sql);
        // echo "</pre>";
        $proses = $this->db->query($sql);

        $sql = " 
            insert into db_po.t_doi_temp
            select $id_po, '$kode',  a.kodeprod,(a.stock / a.rata * 30) as doi, '$created', $id
            FROM
            (
                select  a.kodeprod, a.stock_akhir, a.git, if(a.git is null, a.stock_akhir, a.stock_akhir + a.git) as stock, a.rata
                from mpm.po_detail a
                where a.id_ref = $id_po
            )a
        ";

        $proses = $this->db->query($sql);

        $sql = "
            update mpm.po_detail a
            set a.doi = (
                select 	b.doi
                from 	db_po.t_doi_temp b
                where 	a.kodeprod = b.kodeprod and b.id_po = $id_po and created_date = '$created'
            )
            where a.id_ref = $id_po
        ";
        $proses = $this->db->query($sql);

        $sql = "
                update mpm.po a
                set a.status = 1
                where a.id = $id_po
        ";
        $proses = $this->db->query($sql);

        if($proses){
            redirect('transaction/list_order_detail/'.$id_po);
        }else{
            return array();
        }

    }
}