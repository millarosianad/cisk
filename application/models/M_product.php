<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_product extends CI_Model
{
    public function get_product()
    {
        $sql = '
                select  a.kodeprod, a.kode_prc, a.namaprod, a.supp, b.namasupp , 
                        a.active, a.produksi, a.report
                from    mpm.tabprod a LEFT JOIN mpm.tabsupp b on a.supp = b.supp
        ';
        $proses = $this->db->query($sql)->result();
        return $proses;
    }
    
    public function get_product_by_ID($prodID)
    {
        $sql = '
            select  a.kodeprod, a.kode_prc, a.namaprod, a.namainvoice, a.supp, a.grup, a.subgroup,
                    a.active, a.produksi, a.report, a.kodeprod_deltomed,
                    a.active, a.produksi, a.report, a.supp, a.isisatuan, a.odrunit,
                    a.berat, a.volume, a.apps_namaprod, a.apps_deskripsi, a.apps_images,
                    a.apps_konversi_sedang_ke_kecil, a.apps_min_pembelian, a.apps_satuan_online,
                    a.apps_status_aktif, a.apps_status_promosi_coret, a.apps_status_terbaru,
                    a.apps_status_terlaris, a.apps_kategori_online, a.apps_status_herbana, a.apps_urutan,
                    a.divisiid, a.beli, a.h_ritel, a.h_motoris_ritel,a.karoseri, a.ppnbm, a.locker, a.dimensi1, 
                    a.dimensi2, a.pallete1, a.pallete2, a.barcode, a.product_supplier, a.group1, a.group2, a.group3, 
                    a.buffer_stock, a.status_po, a.product_id_supplier, a.free_tax, a.harga_toko1, a.harga_toko2, 
                    a.harga_toko3, a.group_id_target, a.product_focus, a.discount_grosir, a.discount_ritel, a.discount_motoris_ritel,
                    a.besar, a.sedang, a.kecil, a.qty1, a.qty2, a.qty3, a.status, a.deskripsi, a.image, a.h_grosir
            from    mpm.tabprod a
            where   kodeprod = "'.$prodID.'"
        ';
        $proses = $this->db->query($sql)->row();
        return $proses;
    }

    public function get_hargaproduct($id_product=null)
    {
        $sql = '
            select  a.KODEPROD, a.namaprod, b.*
            from    mpm.tabprod a LEFT JOIN mpm.prod_detail b on a.KODEPROD = b.kodeprod
            where   a.kodeprod = "'.$id_product.'"
            order by b.tgl desc
        ';
        $proses = $this->db->query($sql)->result();
        return $proses;
    }

    public function get_hargaproduct_apps($id_product=null)
    {
        $sql = '
            select  a.KODEPROD, a.namaprod, b.*
            from    mpm.tabprod a LEFT JOIN mpm.prod_detail_apps b on a.KODEPROD = b.kodeprod
            where   a.kodeprod = "'.$id_product.'"
            order by b.tgl desc
        ';
        $proses = $this->db->query($sql)->result();
        return $proses;
    }

    public function get_hargaproduct_dp($kodeprod = null)
    {
        $sql = "
        select 	a.id, a.kodeprod, b.namaprod, a.tgl_naik_harga, 
                a.h_jual_dp_grosir, a.h_jual_dp_retail,
                a.h_jual_dp_motoris_retail, a.site_code, c.branch_name, c.nama_comp
        from mpm.prod_detail_dp a LEFT JOIN 
        (
            select a.kodeprod, a.namaprod
            from mpm.tabprod a
        )b on a.kodeprod = b.kodeprod LEFT JOIN
        (
            select concat(a.kode_comp,a.nocab) as kode, a.branch_name, a.nama_comp, a.urutan
            from mpm.tbl_tabcomp a
            where a.`status` = 1
            GROUP BY kode
        )c on a.site_code = c.kode
        where a.kodeprod = $kodeprod
        ORDER BY c.urutan asc
        ";

        // echo "<pre><br><br><br><br>";
        // print_r($sql);
        // echo "</pre>";
        $proses = $this->db->query($sql)->result();
        return $proses;
    }

    public function get_hargaproduct_by_ID($prodID)
    {
        $sql = '
            select  a.KODEPROD, a.namaprod, b.*
            from    mpm.tabprod a LEFT JOIN mpm.prod_detail b on a.KODEPROD = b.kodeprod
            where   b.id = "'.$prodID.'"
        ';
        $proses = $this->db->query($sql)->row();
        return $proses;
    }

    public function get_hargaproduct_apps_by_ID($prodID)
    {
        $sql = '
            select  a.KODEPROD, a.namaprod, b.*
            from    mpm.tabprod a LEFT JOIN mpm.prod_detail_apps b on a.KODEPROD = b.kodeprod
            where   b.id = "'.$prodID.'"
        ';
        $proses = $this->db->query($sql)->row();
        return $proses;
    }

    public function get_hargaproduct_dp_by_ID($prodID)
    {
        $sql = "
        select 	a.id, a.kodeprod, b.namaprod, a.tgl_naik_harga, 
                a.h_jual_dp_grosir, a.h_jual_dp_retail,
                a.h_jual_dp_motoris_retail, a.site_code, c.branch_name, c.nama_comp
        from mpm.prod_detail_dp a LEFT JOIN 
        (
            select a.kodeprod, a.namaprod
            from mpm.tabprod a
        )b on a.kodeprod = b.kodeprod LEFT JOIN
        (
            select concat(a.kode_comp,a.nocab) as kode, a.branch_name, a.nama_comp, a.urutan
            from mpm.tbl_tabcomp a
            where a.`status` = 1
            GROUP BY kode
        )c on a.site_code = c.kode
        where a.id = $prodID
        ORDER BY c.urutan asc
        ";

        $proses = $this->db->query($sql)->row();
        return $proses;
    }

    
    public function getSupp()
    {
        $sql="select supp, namasupp from mpm.tabsupp
        where active = '1' and supp not in ('000','xxx')";
        return $this->db->query($sql);
    }

    public function getGroup()
    {
        $sql="select a.grup as kode_group, a.varian as nama_group
        from mpm.tabprod a
        order by nama_group
        ";
        return $this->db->query($sql);
    }

    public function getBuild_Group($data = null)
    {
        $supp= $data['supp'];
        $sql="
            select a.grup as kode_group, a.varian as nama_group
            from mpm.tabprod a
            where a.grup is not null and a.grup <> '0' and supp ='$supp'
            GROUP BY a.grup
        ";
        return $this->db->query($sql);
    }
    
    public function getSubgroup()
    {
        $sql="
        select a.grup, a.subgroup as sub_group, a.group as nama_sub_group
        from mpm.tabprod a
        where a.grup is not null and a.grup <> '0' and a.subgroup is not null
        GROUP BY a.subgroup
        order by nama_sub_group
        ";
        return $this->db->query($sql);
    }

    public function getBuild_Subgroup($data = null)
    {
        //$year=year(now());
        //$year = '2017';
        $group = $data['grup'];
        if($group == null){
            $sql = '';
        }else{
            $sql="
            select a.grup, a.subgroup as sub_group, a.group as nama_sub_group
            from mpm.tabprod a
            where a.grup is not null and a.grup <> '0' and a.subgroup is not null
            and grup ='$group'
            GROUP BY a.subgroup
            ";
        }
        /*
        //echo "<pre>";
        //print_r($sql);
        //echo "</pre>";
		*/
        $query=$this->db->query($sql);
        return $query;

    }

    public function getJenis()
    {
        $sql="
        SELECT odrunit, besar, sedang, kecil FROM mpm.tabprod
        GROUP BY odrunit, besar, sedang, kecil
        ";
        return $this->db->query($sql);
    }

    public function activer_product($flag,$id)
    {
        $sql='update mpm.tabprod set active=? where kodeprod=?';
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
        redirect('master_product/product/');
    }
    public function activer_produksi($flag,$id)
    {
        $sql='update mpm.tabprod set produksi=? where kodeprod=?';
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
        redirect('master_product/product/');
    }
    public function activer_report($flag,$id)
    {
        $sql='update mpm.tabprod set report=? where kodeprod=?';
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
        redirect('master_product/product/');
    }

    public function tambah_product()
    {
        date_default_timezone_set('Asia/Jakarta'); 
        $date = date_create(date('Y-m-d H:i:s'));
        date_add($date, date_interval_create_from_date_string('-7 hours'));
        $created_date = '"'.date_format($date, 'Y-m-d H:i:s').'"';
        $id  =$this->session->userdata('id');

        $post['kodeprod']                       = $this->input->post('kodeprod');
        $post['kode_prc']                       = $this->input->post('prc');
        $post['namaprod']                       = $this->input->post('namaprod');
        $post['namainvoice']                    = $this->input->post('namainvoice');
        $post['supp']                           = $this->input->post('supp');
        $post['isisatuan']                      = $this->input->post('unit');
        $post['odrunit']                        = $this->input->post('odr_unit');
        $post['tgl_mulai']                      = $created_date;
        $post['grup']                           = $this->input->post('group');
        $post['subgroup']                       = $this->input->post('s_group');
        $post['berat']                          = $this->input->post('berat');
        $post['volume']                         = $this->input->post('volume');
        $post['kodeprod_deltomed']              = $this->input->post('kd_delto');
        $post['created']                        = $created_date;
        $post['created_by']                     = $id;
        $post['apps_namaprod']                  = $this->input->post('apps_namaprod');
        $post['apps_deskripsi']                 = $this->input->post('apps_deskripsi');
        $post['apps_images']                    = $this->input->post('apps_image');
        $post['apps_konversi_sedang_ke_kecil']  = $this->input->post('apps_konversi');
        $post['apps_min_pembelian']             = $this->input->post('apps_min_pembelian');
        $post['apps_satuan_online']             = $this->input->post('apps_satuan');
        $post['apps_status_aktif']              = $this->input->post('apps_aktif');
        $post['apps_status_promosi_coret']      = $this->input->post('apps_promo_coret');
        $post['apps_status_terbaru']            = $this->input->post('apps_terbaru');
        $post['apps_status_terlaris']           = $this->input->post('apps_terlaris');
        $post['apps_kategori_online']           = $this->input->post('apps_kategori');
        $post['apps_status_herbana']            = $this->input->post('apps_status_herbana');
        $post['apps_urutan']                    = $this->input->post('apps_urutan');
        $post['last_updated']                   = $created_date;
        $post['divisiid']                       = $this->input->post('divisi');
        $post['brand_id']                       = 11..$this->input->post('supp');
        $post['besar']                          = $this->input->post('sat_besar');
        $post['qty1']                           = $this->input->post('qty_besar');
        $post['sedang']                         = $this->input->post('sat_sedang');
        $post['qty2']                           = $this->input->post('qty_sedang');
        $post['kecil']                          = $this->input->post('sat_kecil');
        $post['qty3']                           = $this->input->post('qty_kecil');
        $post['beli']                           = $this->input->post('hrg_beli');
        $post['h_grosir']                       = $this->input->post('hrg_grosir');
        $post['h_ritel']                        = $this->input->post('hrg_ritel');
        $post['h_motoris_ritel']                = $this->input->post('hrg_motoris_ritel');
        $post['karoseri']                       = $this->input->post('karoseri');
        $post['deskripsi']                      = $this->input->post('deskripsi');
        $post['ppnbm']                          = $this->input->post('ppnbm');
        $post['locker']                         = $this->input->post('locker'); 
        $post['dimensi1']                       = $this->input->post('dimensi_1'); 
        $post['dimensi2']                       = $this->input->post('dimensi_2'); 
        $post['pallete1']                       = $this->input->post('pallete_1'); 
        $post['pallete2']                       = $this->input->post('pallete_2'); 
        $post['barcode']                        = $this->input->post('barcode'); 
        $post['product_supplier']               = $this->input->post('prod_supplier'); 
        $post['product_id_supplier']            = $this->input->post('prodid_supplier');
        $post['group1']                         = $this->input->post('group_1'); 
        $post['group2']                         = $this->input->post('group_2'); 
        $post['group3']                         = $this->input->post('group_3');  
        $post['buffer_stock']                   = $this->input->post('buffer_stock'); 
        $post['status']                         = $this->input->post('status'); 
        $post['image']                          = $this->input->post('image'); 
        $post['status_po']                      = $this->input->post('status_po'); 
        $post['free_tax']                       = $this->input->post('free_tax'); 
        $post['harga_toko1']                    = $this->input->post('hrg_toko_1'); 
        $post['harga_toko2']                    = $this->input->post('hrg_toko_2'); 
        $post['harga_toko3']                    = $this->input->post('hrg_toko_3'); 
        $post['group_id_target']                = $this->input->post('group_id_target'); 
        $post['product_focus']                  = $this->input->post('prod_focus'); 
        $post['discount_grosir']                = $this->input->post('disc_grosir');
        $post['discount_ritel']                 = $this->input->post('disc_ritel');
        $post['discount_motoris_ritel']         = $this->input->post('disc_motoris_ritel');

        // var_dump($aktif);
        // var_dump($herbana);
        // var_dump($promo);die;
        $this->db->insert('mpm.tabprod',$post);

        redirect('master_product/product/');
    }

    public function get_pricelist()
    {
        $proses = $this->db->query("select * from mpm.t_pricelist")->result();
        return $proses;
    }

    public function get_pricelist_by_id($prodID)
    {
        // $sql = "
        // select 	a.id, a.kodeprod, b.namaprod, a.tgl_naik_harga, 
        //         a.h_jual_dp_grosir, a.h_jual_dp_retail,
        //         a.h_jual_dp_motoris_retail, a.site_code, c.branch_name, c.nama_comp
        // from mpm.prod_detail_dp a LEFT JOIN 
        // (
        //     select a.kodeprod, a.namaprod
        //     from mpm.tabprod a
        // )b on a.kodeprod = b.kodeprod LEFT JOIN
        // (
        //     select concat(a.kode_comp,a.nocab) as kode, a.branch_name, a.nama_comp, a.urutan
        //     from mpm.tbl_tabcomp a
        //     where a.`status` = 1
        //     GROUP BY kode
        // )c on a.site_code = c.kode
        // where a.id = $prodID
        // ORDER BY c.urutan asc
        // ";

        $sql = "select 	* from mpm.t_pricelist a
        where a.id = $prodID";

        $proses = $this->db->query($sql)->row();
        return $proses;
    }

    public function tambah_pricelist_dp($data){

        $id = $this->input->post('id');
        $post['kodeprod']           = $this->input->post('kodeprod');
        $post['created_by']         = $this->session->userdata('id');
        $post['created_date']       = $data['timezone'];
        $post['last_updated_by']    = $this->session->userdata('id');
        $post['last_updated_date']  = $data['timezone'];
        $post['id_ref']             = $id;
        $post['versi']              = $this->input->post('versi');
        $post['h_jual_dp_grosir']   = $this->input->post('h_jual_dp_grosir');
        $post['h_jual_dp_retail']   = $this->input->post('h_jual_dp_retail');
        $post['h_jual_dp_motoris_retail']  = $this->input->post('h_jual_dp_motoris_retail');
        $post['status_aktif']              = '1';
        $site_code                         = $this->input->post('site_code');        
        
        // echo "id : ".$id."<br>";
        // echo "tgl : ".$tgl;
        if ($site_code == 5) {
            $post['site_code'] = $this->input->post('branch');            
            $post['signature']      = md5($data['timezone'].$this->input->post('kodeprod').$this->input->post('branch'));
            $proses = $this->db->insert('mpm.t_pricelist_dp', $post);
        } else {
            $site_codex = $this->get_site_code($site_code);
            foreach ($site_codex as $x) {
                // echo $x->kode;
                $post['site_code'] = $x->kode;
                $post['signature'] = md5($data['timezone'].$this->input->post('kodeprod').$x->kode);
                $proses = $this->db->insert('mpm.t_pricelist_dp', $post);
            }
        }
        $dpost['last_updated_date'] = $data['timezone'];
        $this->db->where('id', $id);
        $this->db->update('mpm.t_pricelist', $dpost);  

        $sql_update = "update mpm.t_pricelist a set a.last_updated_date = "."'".$data['timezone']."'".", a.last_updated_by = $id";
        $proses_update = $this->db->query($sql_update);
        if ($proses_update) {
            echo "<script>alert('tambah pricelist sukses'); </script>";
        }else{
            echo "<script>alert('tambah pricelist gagal, harap cek manual ke tabel t_pricelist dan t_pricelist_dp'); </script>";
        }
        redirect('master_product/pricelist','refresh'); 
    }

    function get_namaprod($kodeprod)
    {
        $sql = "
        select kodeprod,namaprod
        from mpm.tabprod a
        where kodeprod = '$kodeprod'
        ";
        // return $this->db->query($sql);
        $proses = $this->db->query($sql)->result();
        if ($proses) {
            return $proses;
        }else{
            return array();
        }
        // return $this->db->query($sql);
    }

    public function tambah_pricelist($data)
    {
        $post['versi']          = $this->input->post('versi');
        $post['keterangan']     = $this->input->post('keterangan');
        $post['tgl_naik_harga'] = $this->input->post('tgl_naik_harga');
        $post['status_aktif']   = $this->input->post('status_aktif');     
        $post['created_date']   = $data['timezone'];    
        $post['created_by']     = $this->session->userdata('id');   
        $post['signature']      = md5($this->input->post('versi').$data['timezone'].$this->input->post('tgl_naik_harga'));
        
        $this->db->insert('mpm.t_pricelist',$post);

        redirect('master_product/pricelist/');
    }

    public function edit_product()
    {
        date_default_timezone_set('Asia/Jakarta'); 
        $date = date_create(date('Y-m-d H:i:s'));
        date_add($date, date_interval_create_from_date_string('-7 hours'));
        $created_date = '"'.date_format($date, 'Y-m-d H:i:s').'"';
        $id=$this->session->userdata('id');
        
        $post['kodeprod']                       = $this->input->post('kodeprod');
        $post['kode_prc']                       = $this->input->post('prc');
        $post['namaprod']                       = $this->input->post('namaprod');
        $post['namainvoice']                    = $this->input->post('namainvoice');
        $post['supp']                           = $this->input->post('supp');
        $post['isisatuan']                      = $this->input->post('unit');
        $post['odrunit']                        = $this->input->post('odr_unit');
        $post['tgl_mulai']                      = $created_date;
        $post['grup']                           = $this->input->post('group');
        $post['subgroup']                       = $this->input->post('s_group');
        $post['berat']                          = $this->input->post('berat');
        $post['volume']                         = $this->input->post('volume');
        $post['kodeprod_deltomed']              = $this->input->post('kd_delto');
        $post['modified']                       = $created_date;
        $post['modified_by']                    = $id;
        $post['last_updated']                   = $created_date;
        

        // var_dump($namagroup);
        // var_dump($namasupp);
        // var_dump($post);die;
        $this->db->where('kodeprod', $post['kodeprod']);
        $this->db->update('mpm.tabprod', $post);

        redirect('master_product/product/');
    }

    public function edit_product_apps()
    {
        date_default_timezone_set('Asia/Jakarta'); 
        $date = date_create(date('Y-m-d H:i:s'));
        date_add($date, date_interval_create_from_date_string('-7 hours'));
        $created_date = '"'.date_format($date, 'Y-m-d H:i:s').'"';
        $id = $this->session->userdata('id');

        $post['kodeprod']                       = $this->input->post('kodeprod');
        $post['kode_prc']                       = $this->input->post('prc');
        $post['namaprod']                       = $this->input->post('namaprod');
        $post['namainvoice']                    = $this->input->post('namainvoice');
        $post['supp']                           = $this->input->post('supp');
        $post['isisatuan']                      = $this->input->post('unit');
        $post['odrunit']                        = $this->input->post('odr_unit');
        $post['tgl_mulai']                      = $created_date;
        $post['grup']                           = $this->input->post('group');
        $post['subgroup']                       = $this->input->post('s_group');
        $post['berat']                          = $this->input->post('berat');
        $post['volume']                         = $this->input->post('volume');
        $post['kodeprod_deltomed']              = $this->input->post('kd_delto');
        $post['modified']                       = $created_date;
        $post['modified_by']                    = $id;
        $post['last_updated']                   = $created_date;
        $post['apps_namaprod']                  = $this->input->post('apps_namaprod');
        $post['apps_deskripsi']                 = $this->input->post('apps_deskripsi');
        $post['apps_images']                    = $this->input->post('apps_image');
        $post['apps_konversi_sedang_ke_kecil']  = $this->input->post('apps_konversi');
        $post['apps_min_pembelian']             = $this->input->post('apps_min_pembelian');
        $post['apps_satuan_online']             = $this->input->post('apps_satuan');
        $post['apps_status_aktif']              = $this->input->post('apps_aktif');
        $post['apps_status_promosi_coret']      = $this->input->post('apps_promo_coret');
        $post['apps_status_terbaru']            = $this->input->post('apps_terbaru');
        $post['apps_status_terlaris']           = $this->input->post('apps_terlaris');
        $post['apps_kategori_online']           = $this->input->post('apps_kategori');
        $post['apps_urutan']                    = $this->input->post('apps_urutan');
        $post['apps_status_herbana']            = $this->input->post('apps_status_herbana');
        $post['apps_last_updated']              = $created_date;
        $post['apps_created_by']                = $id;
        $post['divisiid']                       = $this->input->post('divisi');
        $post['besar']                          = $this->input->post('sat_besar');
        $post['qty1']                           = $this->input->post('qty_besar');
        $post['sedang']                         = $this->input->post('sat_sedang');
        $post['qty2']                           = $this->input->post('qty_sedang');
        $post['kecil']                          = $this->input->post('sat_kecil');
        $post['qty3']                           = $this->input->post('qty_kecil');
        $post['beli']                           = $this->input->post('hrg_beli');
        $post['h_grosir']                       = $this->input->post('hrg_grosir');
        $post['h_ritel']                        = $this->input->post('hrg_ritel');
        $post['h_motoris_ritel']                = $this->input->post('hrg_motoris_ritel');
        $post['karoseri']                       = $this->input->post('karoseri');
        $post['deskripsi']                      = $this->input->post('deskripsi');
        $post['ppnbm']                          = $this->input->post('ppnbm');
        $post['locker']                         = $this->input->post('locker'); 
        $post['dimensi1']                       = $this->input->post('dimensi_1'); 
        $post['dimensi2']                       = $this->input->post('dimensi_2'); 
        $post['pallete1']                       = $this->input->post('pallete_1'); 
        $post['pallete2']                       = $this->input->post('pallete_2'); 
        $post['barcode']                        = $this->input->post('barcode'); 
        $post['product_supplier']               = $this->input->post('prod_supplier'); 
        $post['product_id_supplier']            = $this->input->post('prodid_supplier');
        $post['group1']                         = $this->input->post('group_1'); 
        $post['group2']                         = $this->input->post('group_2'); 
        $post['group3']                         = $this->input->post('group_3');  
        $post['buffer_stock']                   = $this->input->post('buffer_stock'); 
        $post['status']                         = $this->input->post('status'); 
        $post['image']                          = $this->input->post('image'); 
        $post['status_po']                      = $this->input->post('status_po'); 
        $post['free_tax']                       = $this->input->post('free_tax'); 
        $post['harga_toko1']                    = $this->input->post('hrg_toko_1'); 
        $post['harga_toko2']                    = $this->input->post('hrg_toko_2'); 
        $post['harga_toko3']                    = $this->input->post('hrg_toko_3'); 
        $post['group_id_target']                = $this->input->post('group_id_target'); 
        $post['product_focus']                  = $this->input->post('prod_focus'); 
        $post['discount_grosir']                = $this->input->post('disc_grosir');
        $post['discount_ritel']                 = $this->input->post('disc_ritel');
        $post['discount_motoris_ritel']         = $this->input->post('disc_motoris_ritel');

        // var_dump($namagroup);
        // var_dump($namasupp);
        // var_dump($post);die;
        $this->db->where('kodeprod', $post['kodeprod']);
        $this->db->update('mpm.tabprod', $post);

        redirect('master_product/product/');
    }
    
    public function tambah_harga()
    {
        date_default_timezone_set('Asia/Jakarta'); 
        $date = date_create(date('Y-m-d H:i:s'));
        date_add($date, date_interval_create_from_date_string('-7 hours'));
        $created_date = '"'.date_format($date, 'Y-m-d H:i:s').'"';

        $id                                                 = $this->session->userdata('id');
        $post['kodeprod']                                   = $this->input->post('kodeprod');
        $post['h_dp']                                       = $this->input->post('h_dp');
        $post['h_bsp']                                      = $this->input->post('h_bsp');
        $post['h_pbf']                                      = $this->input->post('h_pbf');
        $post['h_beli_dp']                                  = $this->input->post('hb_dp');
        $post['h_beli_bsp']                                 = $this->input->post('hb_bsp');
        $post['h_beli_pbf']                                 = $this->input->post('hb_pbf');
        $post['h_luarjawa']                                 = $this->input->post('h_dp_ljawa');
        $post['d_dp']                                       = $this->input->post('d_dp');
        $post['d_bsp']                                      = $this->input->post('d_bsp');
        $post['d_pbf']                                      = $this->input->post('d_pbf');
        $post['d_beli_dp']                                  = $this->input->post('db_dp');
        $post['d_beli_bsp']                                 = $this->input->post('db_bsp');
        $post['d_beli_pbf']                                 = $this->input->post('db_pbf');
        $post['d_luarjawa']                                 = $this->input->post('d_dp_ljawa');
        $post['tgl']                                        = $this->input->post('tgl');
        $post['created_by']                                 = $id;
        $post['created']                                    = $created_date;
        $post['h_dpbatam']                                  = $this->input->post('hk_batam');
        $post['d_dpbatam']                                  = $this->input->post('dk_batam');
        $post['h_beli_mpm']                                 = $this->input->post('hbm_dp');
        $post['h_beli_mpm_bsp']                             = $this->input->post('hbm_bsp');
        $post['h_beli_mpm_batam']                           = $this->input->post('hbm_batam');
        $post['h_beli_mpm_candy_jawa']                      = $this->input->post('hbm_candy_pj');
        $post['h_beli_mpm_candy_ljawa']                     = $this->input->post('hbm_candy_lj');

        // var_dump($post);die; 
        $proses = $this->db->insert('mpm.prod_detail',$post);
        redirect('master_product/product/');      
    }

    public function tambah_harga_apps()
    {
        date_default_timezone_set('Asia/Jakarta'); 
        $date = date_create(date('Y-m-d H:i:s'));
        date_add($date, date_interval_create_from_date_string('-7 hours'));
        $created_date = '"'.date_format($date, 'Y-m-d H:i:s').'"';

        $id                                                 = $this->session->userdata('id');
        $kodeprod                                           = $this->input->post('kodeprod');
        $post['kodeprod']                                   = $this->input->post('kodeprod');
        $post['apps_harga_ritel_gt']                        = $this->input->post('apps_hrg');
        $post['apps_harga_grosir_mt']                       = $this->input->post('apps_hgm');
        $post['apps_harga_semi_grosir']                     = $this->input->post('apps_hsg');
        $post['apps_harga_promosi_coret']                   = $this->input->post('apps_hpc');
        $post['apps_harga_promosi_coret_ritel_gt']          = $this->input->post('apps_hpc_rg');
        $post['apps_harga_promosi_coret_grosir_mt']         = $this->input->post('apps_hpc_gm');
        $post['apps_harga_promosi_coret_semi_grosir']       = $this->input->post('apps_hpc_sg');
        $post['tgl']                                        = $this->input->post('tgl');
        $post['created_by']                                 = $id;
        $post['created']                                    = $created_date;
        $dpost['apps_last_updated']                         = $created_date;

        // var_dump($post);die; 
        $proses = $this->db->insert('mpm.prod_detail_apps',$post);
        $this->db->where('kodeprod', $kodeprod);
        $this->db->update('mpm.tabprod', $dpost);
        redirect('master_product/product/');      
    }

    public function tambah_harga_jual_dp()
    {
        date_default_timezone_set('Asia/Jakarta'); 
        $date = date_create(date('Y-m-d H:i:s'));
        date_add($date, date_interval_create_from_date_string('-7 hours'));
        $created_date = '"'.date_format($date, 'Y-m-d H:i:s').'"';
        
        $id                                                 = $this->session->userdata('id');
        $kodeprod                                           = $this->input->post('kodeprod');
        $post['kodeprod']                                   = $this->input->post('kodeprod');
        $post['tgl_naik_harga']                         = $this->input->post('tgl_naik_harga');
        $post['h_jual_dp_grosir']                       = $this->input->post('h_jual_dp_grosir');
        $post['h_jual_dp_retail']                       = $this->input->post('h_jual_dp_retail');
        $post['h_jual_dp_motoris_retail']               = $this->input->post('h_jual_dp_motoris_retail');
        $post['created_by']                                 = $id;
        $post['last_updated_by']                            = $id;
        $post['created_date']                               = $created_date;
        $post['last_updated']                               = $created_date;

        // var_dump($post);die; 
        $site_code = $this->input->post('site_code');
        if ($site_code == 5) {
            $post['site_code'] = $this->input->post('branch');
            $proses = $this->db->insert('mpm.prod_detail_dp', $post);
        } else {
            $site_codex = $this->get_site_code($site_code);
            foreach ($site_codex as $x) {
                // echo $x->kode;
                $post['site_code'] = $x->kode;
                $proses = $this->db->insert('mpm.prod_detail_dp', $post);
            }
        }
        $dpost['apps_last_updated']                         = $created_date;
        $this->db->where('kodeprod', $kodeprod);
        $this->db->update('mpm.tabprod', $dpost);
        // redirect('master_product/product/');     
        echo "<script>alert('tambah harga dp sukses'); </script>";
        redirect('master_product/product','refresh'); 
    }

    function get_site_code($area){
        $thn = date('Y');
        if ($area == "1") {
            $where = "";
        }elseif($area == "2"){
            $where = "and area = 1";
        }elseif($area == "3"){
            $where = "and area = 2";
        }elseif($area == "4"){
            $where = "and area = 3";
        }

        $sql = "
            select a.kode
            from
            (
                select concat(a.kode_comp, a.nocab) as kode
                from mpm.tbl_tabcomp a
                where a.`status` = 1 $where
                GROUP BY kode
            )a INNER JOIN 
            (
                select concat(a.kode_comp, a.nocab) as kode
                from db_dp.t_dp a
                where a.tahun = $thn
                GROUP BY kode
            )b on a.kode = b.kode
            ";
        $proses = $this->db->query($sql)->result();

        return $proses;
    }

    public function update_harga()
    {
        date_default_timezone_set('Asia/Jakarta'); 
        $date = date_create(date('Y-m-d H:i:s'));
        date_add($date, date_interval_create_from_date_string('-7 hours'));
        $created_date = '"'.date_format($date, 'Y-m-d H:i:s').'"';

        $id                                                 = $this->session->userdata('id');
        $id_harga                                           = $this->input->post('harga_id');
        $kodeprod                                           = $this->input->post('kodeprod');
        $post['kodeprod']                                   = $this->input->post('kodeprod');
        $post['h_dp']                                       = $this->input->post('h_dp');
        $post['h_bsp']                                      = $this->input->post('h_bsp');
        $post['h_pbf']                                      = $this->input->post('h_pbf');
        $post['h_beli_dp']                                  = $this->input->post('hb_dp');
        $post['h_beli_bsp']                                 = $this->input->post('hb_bsp');
        $post['h_beli_pbf']                                 = $this->input->post('hb_pbf');
        $post['h_luarjawa']                                 = $this->input->post('h_dp_ljawa');
        $post['d_dp']                                       = $this->input->post('d_dp');
        $post['d_bsp']                                      = $this->input->post('d_bsp');
        $post['d_pbf']                                      = $this->input->post('d_pbf');
        $post['d_beli_dp']                                  = $this->input->post('db_dp');
        $post['d_beli_bsp']                                 = $this->input->post('db_bsp');
        $post['d_beli_pbf']                                 = $this->input->post('db_pbf');
        $post['d_luarjawa']                                 = $this->input->post('d_dp_ljawa');
        $post['tgl']                                        = $this->input->post('tgl');
        $post['modified_by']                                = $id;
        $post['modified']                                   = $created_date;
        $post['h_dpbatam']                                  = $this->input->post('hk_batam');
        $post['d_dpbatam']                                  = $this->input->post('dk_batam');
        $post['h_beli_mpm']                                 = $this->input->post('hbm_dp');
        $post['h_beli_mpm_bsp']                             = $this->input->post('hbm_bsp');
        $post['h_beli_mpm_batam']                           = $this->input->post('hbm_batam');
        $post['h_beli_mpm_candy_jawa']                      = $this->input->post('hbm_candy_pj');
        $post['h_beli_mpm_candy_ljawa']                     = $this->input->post('hbm_candy_lj');

        // var_dump($id_harga);die; 
        $this->db->where('id', $id_harga);
        $this->db->update('mpm.prod_detail', $post);
        redirect("master_product/product_detail_harga/$kodeprod");      
    }

    public function update_harga_apps()
    {
        date_default_timezone_set('Asia/Jakarta'); 
        $date = date_create(date('Y-m-d H:i:s'));
        date_add($date, date_interval_create_from_date_string('-7 hours'));
        $created_date = '"'.date_format($date, 'Y-m-d H:i:s').'"';

        $id                                                 = $this->session->userdata('id');
        $id_harga                                           = $this->input->post('harga_id');
        $kodeprod                                           = $this->input->post('kodeprod');
        $post['kodeprod']                                   = $this->input->post('kodeprod');
        $post['apps_harga_ritel_gt']                        = $this->input->post('apps_hrg');
        $post['apps_harga_grosir_mt']                       = $this->input->post('apps_hgm');
        $post['apps_harga_semi_grosir']                     = $this->input->post('apps_hsg');
        $post['apps_harga_promosi_coret']                   = $this->input->post('apps_hpc');
        $post['apps_harga_promosi_coret_ritel_gt']          = $this->input->post('apps_hpc_rg');
        $post['apps_harga_promosi_coret_grosir_mt']        = $this->input->post('apps_hpc_gm');
        $post['apps_harga_promosi_coret_semi_grosir']      = $this->input->post('apps_hpc_sg');
        $post['tgl']                                        = $this->input->post('tgl');
        $post['modified_by']                                = $id;
        $post['modified']                                   = $created_date;
        $dpost['apps_last_updated']                         = $created_date;
        // var_dump($id_harga);die; 
        $this->db->where('id', $id_harga);
        $this->db->update('mpm.prod_detail_apps', $post);
        $this->db->where('kodeprod', $kodeprod);
        $this->db->update('mpm.tabprod', $dpost);
        redirect("master_product/product_detail_harga_apps/$kodeprod");      
    }

    public function update_harga_dp()
    {
        date_default_timezone_set('Asia/Jakarta'); 
        $date = date_create(date('Y-m-d H:i:s'));
        date_add($date, date_interval_create_from_date_string('-7 hours'));
        $created_date = '"'.date_format($date, 'Y-m-d H:i:s').'"';

        $id = $this->session->userdata('id');
        $id_harga = $this->input->post('harga_id');
        $kodeprod = $this->input->post('kodeprod');
        $post['h_jual_dp_grosir'] = $this->input->post('h_jual_dp_grosir');
        $post['h_jual_dp_retail'] = $this->input->post('h_jual_dp_retail');
        $post['h_jual_dp_motoris_retail'] = $this->input->post('h_jual_dp_motoris_retail');
        $post['last_updated_by'] = $id;
        $post['last_updated'] = $created_date;
        $dpost['dp_last_updated'] = $created_date;
        // var_dump($id_harga);die; 
        $this->db->where('id', $id_harga);
        $this->db->update('mpm.prod_detail_dp', $post);

        $this->db->where('kodeprod', $kodeprod);
        $this->db->update('mpm.tabprod', $dpost);
        redirect("master_product/product_detail_harga_dp/$kodeprod");      
    }

    public function delete_harga($id=null)
    {
        $this->db->where('id', $id);
        $this->db->delete('mpm.prod_detail');
    }

    public function delete_harga_apps($id=null)
    {
        $this->db->where('id', $id);
        $this->db->delete('mpm.prod_detail_apps');
    }

    public function delete_harga_dp($id=null)
    {
        $this->db->where('id', $id);
        $this->db->delete('mpm.prod_detail_dp');
    }
}
