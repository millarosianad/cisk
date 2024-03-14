<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ModelDatabaseAfiliasi extends CI_Model 
{   
    public function get_profile($kode_alamat = '', $id = ''){
        
        // echo "Aaa : ".$kode_alamat;
        // die;

        if ($id == '') {
            $params_where = '';
        }else{
            $params_where = "a.id = $id";
        }

        if ($kode_alamat == '') {
            $params_kode_alamat = '';
        }else{
            $params_kode_alamat = "a.site_code in ($kode_alamat)";
        }

        if ($id != '' && $kode_alamat != '') {
            $params_and = 'and';
        }else{
            $params_and = '';
        }

        $sql = "
            select *
            from site.t_dbafiliasi_tabel_profile a left join 
            (
                select concat(a.kode_comp, a.nocab) as site_code, a.branch_name, a.nama_comp
                from mpm.tbl_tabcomp a
                where a.status = 1
                group by concat(a.kode_comp, a.nocab)
            )b on a.site_code = b.site_code
            where $params_kode_alamat $params_and $params_where    
        ";       
        // echo "<pre>";
        // print_r($sql);
        // die;

        $proses = $this->db->query($sql);
        // $proses = $this->db->query($sql)->result();
        return $proses;
    }

    public function get_detail($kode_alamat, $id=''){
        
        if ($id == '') {
            $params_where = '';
        }else{
            $params_where = "a.id = $id";
        }

        if ($kode_alamat == '') {
            $params_kode_alamat = '';
        }else{
            $params_kode_alamat = "a.site_code in ($kode_alamat)";
        }

        if ($id != '' && $kode_alamat != '') {
            $params_and = 'and';
        }else{
            $params_and = '';
        }

        $sql = "
            select *
            from site.t_dbafiliasi_tabel_detail_gudang a left join 
            (
                select concat(a.kode_comp, a.nocab) as site_code, a.branch_name, a.nama_comp
                from mpm.tbl_tabcomp a
                where a.status = 1
                group by concat(a.kode_comp, a.nocab)
            )b on a.site_code = b.site_code
            where $params_kode_alamat $params_and $params_where       
        ";        

        $proses = $this->db->query($sql);
        return $proses;
    }
    
    public function get_karyawan($kode_alamat, $id=''){
        
        if ($id == '') {
            $params_where = '';
        }else{
            $params_where = "a.id = $id";
        }
    
        if ($kode_alamat == '') {
            $params_kode_alamat = '';
        }else{
            $params_kode_alamat = "a.site_code in ($kode_alamat)";
        }
    
        if ($id != '' && $kode_alamat != '') {
            $params_and = 'and';
        }else{
            $params_and = '';
        }

        $sql = "
            select *
            from site.t_dbafiliasi_tabel_karyawan a left join 
            (
                select concat(a.kode_comp, a.nocab) as site_code, a.branch_name, a.nama_comp
                from mpm.tbl_tabcomp a
                where a.status = 1
                group by concat(a.kode_comp, a.nocab)
            )b on a.site_code = b.site_code
            where $params_kode_alamat $params_and $params_where       
        ";
        

        $proses = $this->db->query($sql);
        return $proses;
    }

    public function get_niaga($kode_alamat, $id=''){
        if ($id == '') {
            $params_where = '';
        }else{
            $params_where = "a.id = $id";
        }
    
        if ($kode_alamat == '') {
            $params_kode_alamat = '';
        }else{
            $params_kode_alamat = "a.site_code in ($kode_alamat)";
        }
    
        if ($id != '' && $kode_alamat != '') {
            $params_and = 'and';
        }else{
            $params_and = '';
        }

        $sql = "
            select *
            from site.t_dbafiliasi_tabel_niaga a left join 
            (
                select concat(a.kode_comp, a.nocab) as site_code, a.branch_name, a.nama_comp
                from mpm.tbl_tabcomp a
                where a.status = 1
                group by concat(a.kode_comp, a.nocab)
            )b on a.site_code = b.site_code
            where $params_kode_alamat $params_and $params_where         
        ";        

        $proses = $this->db->query($sql);
        return $proses;
    }

    public function get_non_niaga($kode_alamat, $id=''){
        if ($id == '') {
            $params_where = '';
        }else{
            $params_where = "a.id = $id";
        }
    
        if ($kode_alamat == '') {
            $params_kode_alamat = '';
        }else{
            $params_kode_alamat = "a.site_code in ($kode_alamat)";
        }
    
        if ($id != '' && $kode_alamat != '') {
            $params_and = 'and';
        }else{
            $params_and = '';
        }
        
        $sql = "
            select *
            from site.t_dbafiliasi_tabel_non_niaga a left join 
            (
                select concat(a.kode_comp, a.nocab) as site_code, a.branch_name, a.nama_comp
                from mpm.tbl_tabcomp a
                where a.status = 1
                group by concat(a.kode_comp, a.nocab)
            )b on a.site_code = b.site_code
            where $params_kode_alamat $params_and $params_where         
        ";        

        // echo "<pre><br><br><br>";
        // print_r($sql);
        // echo "</pre>";

        $proses = $this->db->query($sql);
        return $proses;
    }

    public function get_it_asset($kode_alamat, $id=''){
        if ($id == '') {
            $params_where = '';
        }else{
            $params_where = "a.id = $id";
        }
    
        if ($kode_alamat == '') {
            $params_kode_alamat = '';
        }else{
            $params_kode_alamat = "a.site_code in ($kode_alamat)";
        }
    
        if ($id != '' && $kode_alamat != '') {
            $params_and = 'and';
        }else{
            $params_and = '';
        }

        $sql = "
            select *
            from site.t_dbafiliasi_tabel_it_asset a left join 
            (
                select concat(a.kode_comp, a.nocab) as site_code, a.branch_name, a.nama_comp
                from mpm.tbl_tabcomp a
                where a.status = 1
                group by concat(a.kode_comp, a.nocab)
            )b on a.site_code = b.site_code
            where $params_kode_alamat $params_and $params_where       
        ";        

        $proses = $this->db->query($sql);
        return $proses;
    }

    public function get_asset($kode_alamat, $id=''){
        if ($id == '') {
            $params_where = '';
        }else{
            $params_where = "a.id = $id";
        }
    
        if ($kode_alamat == '') {
            $params_kode_alamat = '';
        }else{
            $params_kode_alamat = "a.site_code in ($kode_alamat)";
        }
    
        if ($id != '' && $kode_alamat != '') {
            $params_and = 'and';
        }else{
            $params_and = '';
        }

        $sql = "
            select *
            from site.t_dbafiliasi_tabel_asset a left join 
            (
                select concat(a.kode_comp, a.nocab) as site_code, a.branch_name, a.nama_comp
                from mpm.tbl_tabcomp a
                where a.status = 1
                group by concat(a.kode_comp, a.nocab)
            )b on a.site_code = b.site_code
            where $params_kode_alamat $params_and $params_where         
        ";        

        $proses = $this->db->query($sql);
        return $proses;
    }

    public function get_strukturorganisasi($kode_alamat){
        
        $sql = "
            select *
            from site.t_dbafiliasi_tabel_struktur_organisasi a
            where a.site_code in ($kode_alamat)        
        ";        

        $proses = $this->db->query($sql)->result();
        return $proses;
    }

    public function get_sitecode()
    {
        $id = $this->session->userdata('id');
        $year = date('Y');
        if($id == 547 || $id == 297 || $id == 588){
            $sql = "
            SELECT a.id, a.username, a.company, CONCAT(b.kode_comp,b.nocab) as site_code, b.nama_comp
            FROM
            (
                SELECT *
                FROM mpm.`user`
            )a LEFT JOIN
            (
                SELECT a.*
                FROM
                (
                SELECT CONCAT(kode_comp, nocab) as kode, KODE_COMP, NOCAB, nama_comp
                FROM mpm.tbl_tabcomp a
                where a.status = 1
                )a INNER JOIN
                (
                SELECT CONCAT(kode_comp, nocab) as kode
                FROM db_dp.t_dp
                WHERE tahun = $year AND `status` = 1
                )b on a.kode = b.kode
            )b on a.username = b.kode_comp";
        }else{
            $sql = "
                select a.username, a.kode_alamat as site_code, b.nama_comp, a.company
                from
                (
                    select a.username, b.kode_alamat, a.company
                    from mpm.user a INNER JOIN mpm.t_alamat b
                        on a.username = b.username
                    where id = $id
                )a LEFT JOIN 
                (
                    SELECT a.kode, a.nama_comp
                    FROM
                    (
                        SELECT CONCAT(kode_comp, nocab) as kode, KODE_COMP, NOCAB, nama_comp
                        FROM mpm.tbl_tabcomp a
                        where a.status = 1
                        group by CONCAT(kode_comp, nocab)
                    )a INNER JOIN
                    (
                        SELECT CONCAT(kode_comp, nocab) as kode
                        FROM db_dp.t_dp
                        WHERE tahun = $year AND `status` = 1
                    )b on a.kode = b.kode
                )b on a.kode_alamat = b.kode
                GROUP BY a.kode_alamat            
            ";

            // echo "<pre><br><br><br><br><br>";
            // print_r($sql);
            // echo "</pre>";
        }
        $proses = $this->db->query($sql)->result();
        return $proses;
    }

    public function get_sitecode_new(){
        $id = $this->session->userdata('id');
        $year = date('Y');
        if($id == 547 || $id == 297 || $id == 588){
            $params_username = "";
        }else{
            $params_username = "and a.id = $id";
        }
        $sql = "
            select a.id, a.username, a.company, a.kode_alamat as site_code, a.alamat, a.status_ho, b.branch_name, b.nama_comp
            from 
            (
                select 	a.id, a.username, a.company, b.kode_alamat, b.alamat, b.status_ho
                from	 	mpm.user a LEFT JOIN
                (
                    SELECT	a.username, a.kode_alamat, a.alamat, a.`status`, a.status_ho
                    from mpm.t_alamat a
                    where a.`status` = 1
                )b on a.username = b.username
                where 	a.active = 1 $params_username
            )a INNER JOIN 
            (
                select a.site_code, a.branch_name, a.nama_comp, a.kode_comp
                FROM
                (
                    select 	concat(a.kode_comp, a.nocab) as site_code, a.branch_name, a.nama_comp, a.kode_comp
                    from 		mpm.tbl_tabcomp a
                    where		a.`status` = 1
                    GROUP BY concat(a.kode_comp, a.nocab)
                )a INNER JOIN 
                (
                    select concat(a.kode_comp, a.nocab) as site_code
                    from db_dp.t_dp a 
                    where a.tahun = $year and a.`status` = 1
                )b on a.site_code = b.site_code            
            )b on a.username = b.kode_comp
            ORDER BY nama_comp
        ";
        $proses = $this->db->query($sql);

        // echo "<pre><br><br><br><br>";
        // print_r($sql);
        // echo "</pre>";
        return $proses;
    }

    public function get_provinsi(){
        return $this->db->get('db_dp.provinsi')->result();
    }

    public function tambah($table, $data){
        // var_dump($data);die;
        $this->db->insert($table, $data);
        return $this->db->insert_id(); 
        // $signature = $this->get_signature($this->db->insert_id());
        // return $signature;
    }

    public function edit($table, $data){
        // var_dump($data);die;
        $this->db->where('id', $data['id']);
        return $this->db->update($table, $data); 
        // $signature = $this->get_signature($this->db->insert_id());
        // return $signature;
    }

    public function delete($table, $data)
    {
        $this->db->where('md5(id)', $data);
        return $this->db->delete($table);
    }

    // public function delete($table, $data)
    // {
    //     $this->db->where('md5(id)', $data);
    //     return $this->db->update($table, $data); 
    // }

    public function pdf_profile($id){
        $sql = "
        SELECT *
        FROM
        (
            select a.site_code, IF(LENGTH(a.nama)> 90,CONCAT(SUBSTR(a.nama,1,90),' ','...'),a.nama) as nama, a.status_afiliasi,
                    IF(LENGTH(a.alamat)> 65,CONCAT(SUBSTR(a.alamat,1,65),' ','...'),a.alamat) as alamat, 
                    IF(LENGTH(a.propinsi)> 65,CONCAT(SUBSTR(a.propinsi,1,65),' ','...'),a.propinsi) as propinsi,
                    IF(LENGTH(a.kota)> 65,CONCAT(SUBSTR(a.kota,1,65),' ','...'),a.kota) as kota,
                    IF(LENGTH(a.kecamatan)> 20,CONCAT(SUBSTR(a.kecamatan,1,20),' ','...'),a.kecamatan) as kecamatan,
                    IF(LENGTH(a.kelurahan)> 20,CONCAT(SUBSTR(a.kelurahan,1,20),' ','...'),a.kelurahan) as kelurahan,
                    IF(LENGTH(a.kodepos)> 20,CONCAT(SUBSTR(a.kodepos,1,20),' ','...'),a.kodepos) as kodepos,
                    IF(LENGTH(a.telp)> 20,CONCAT(SUBSTR(a.telp,1,20),' ','...'),a.telp) as telp, a.status_properti,
                    a.sewa_from, a.sewa_to, a.harga_sewa, a.bentuk_bangunan, a.foto_tampak_depan, a.foto_gudang, 
                    a.foto_kantor, a.foto_area_loading_gudang, a.foto_gudang_baik, a.foto_gudang_retur
            from site.t_dbafiliasi_tabel_profile a 
            where a.id = $id
        )a
        LEFT JOIN
        (
            SELECT b.site_code, b.luas_gudang, b.pallet_gudang, b.luas_gudang_baik, b.pallet_gudang_baik, b.luas_gudang_retur,
                            b.pallet_gudang_retur, b.luas_gudang_karantina, b.pallet_gudang_karantina, b.luas_gudang_ac,
                            b.pallet_gudang_ac,b.luas_loading_dock, b.pallet_gudang_loading, b.jumlah_pallet, b.jumlah_hand_pallet,
                            b.jumlah_trolley, b.luas_kantor_div_logistik, b.total_mobil_penumpang, b.total_mobil_pengiriman,
                            b.total_blind_van, b.total_engkel, b.total_double, b.total_motor_pengiriman, b.total_saddle_bag, b.luas_kantor_total,
                            b.luas_ruang_sales, b.luas_ruang_finance, b.luas_ruang_logistik, b.luas_gudang_arsip
            FROM site.t_dbafiliasi_tabel_detail_gudang b
        )b on a.site_code = b.site_code
        LEFT JOIN
        (
            SELECT COUNT(c.id) as total_karyawan, c.site_code
            FROM site.t_dbafiliasi_tabel_karyawan c
            GROUP BY c.site_code
        )c on  a.site_code = c.site_code
        LEFT JOIN
        (
            SELECT COUNT(d.id) as total_manager, d.site_code
            FROM site.t_dbafiliasi_tabel_karyawan d
            WHERE d.jabatan in('bm','sm','abm','finance_manager','logistik_manager')
            GROUP BY d.site_code
        )d on  a.site_code = d.site_code
        LEFT JOIN
        (
            SELECT COUNT(e.id) as total_spv, e.site_code
            FROM site.t_dbafiliasi_tabel_karyawan e
            WHERE e.jabatan in('spv_sales','spv_logistik','spv_sales','spv_finance','admin_spv')
            GROUP BY e.site_code
        )e on  a.site_code = e.site_code
        LEFT JOIN
        (
            SELECT COUNT(f.id) as total_staff, f.site_code
            FROM site.t_dbafiliasi_tabel_karyawan f
            WHERE f.jabatan NOT in ('direktur','bm','sm','abm','finance_manager','logistik_manager','spv_sales','spv_logistik','spv_sales','spv_finance','admin_spv')
            GROUP BY f.site_code
        )f on  a.site_code = f.site_code
        LEFT JOIN
        (
            SELECT COUNT(g.id) as total_pria, g.site_code
            FROM site.t_dbafiliasi_tabel_karyawan g
            WHERE g.jenis_kelamin = 'pria'
            GROUP BY g.site_code
        )g on  a.site_code = g.site_code
        LEFT JOIN
        (
            SELECT COUNT(h.id) as total_wanita, h.site_code
            FROM site.t_dbafiliasi_tabel_karyawan h
            WHERE h.jenis_kelamin = 'wanita'
            GROUP BY h.site_code
        )h on  a.site_code = h.site_code
        LEFT JOIN
        (
            SELECT COUNT(i.id) as total_sales, i.site_code
            FROM site.t_dbafiliasi_tabel_karyawan i
            WHERE i.jabatan in('sm','spv_sales','salesforce_grosir','salesforce_reguler','salesforce_mt','salesforce_apotik')
            GROUP BY i.site_code
        )i on a.site_code = i.site_code
        LEFT JOIN
        (
            SELECT COUNT(j.id) as total_gudang, j.site_code
            FROM site.t_dbafiliasi_tabel_karyawan j
            WHERE j.jabatan in('logistik_manager','spv_logistik','kepala_logistik','admin_logistik')
            GROUP BY j.site_code
        )j on a.site_code = j.site_code
        LEFT JOIN
        (
            SELECT COUNT(k.id) as total_ekspedisi, k.site_code
            FROM site.t_dbafiliasi_tabel_karyawan k
            WHERE k.jabatan in('admin_ekspedisi')
            GROUP BY k.site_code
        )k on a.site_code = k.site_code
        LEFT JOIN
        (
            SELECT COUNT(l.id) as total_finance, l.site_code
            FROM site.t_dbafiliasi_tabel_karyawan l
            WHERE l.jabatan in('finance_manager','spv_finance')
            GROUP BY l.site_code
        )l on a.site_code = l.site_code
        ";
        $proses = $this->db->query($sql);
        return $proses ;
    }

    public function omzet_herbal_pdf_profile($site_code)
    {
        $sql = "
            select if(sum(omzet) is null,0,omzet) as omzet
            from site.t_temp_dbafiliasi_omzet_profile
            where site_code = '$site_code' and `group` = 'herbal'
        ";
        $proses = $this->db->query($sql);
        return $proses;
    }

    public function omzet_candy_pdf_profile($site_code)
    {
        $sql = "
            select if(sum(omzet) is null,0,omzet) as omzet
            from site.t_temp_dbafiliasi_omzet_profile
            where site_code = '$site_code' and `group` = 'candy'
        ";
        $proses = $this->db->query($sql);
        return $proses;
    }
    
    public function omzet_marguna_pdf_profile($site_code)
    {
        $sql = "
            select if(sum(omzet) is null,0,omzet) as omzet
            from site.t_temp_dbafiliasi_omzet_profile
            where site_code = '$site_code' and `group` = 'marguna'
        ";
        $proses = $this->db->query($sql);
        return $proses;
    }

    public function omzet_jaya_pdf_profile($site_code)
    {
        $sql = "
            select if(sum(omzet) is null,0,omzet) as omzet
            from site.t_temp_dbafiliasi_omzet_profile
            where site_code = '$site_code' and `group` = 'jaya'
        ";
        $proses = $this->db->query($sql);
        return $proses;
    }

    public function omzet_us_pdf_profile($site_code)
    {
        $sql = "
            select if(sum(omzet) is null,0,omzet) as omzet
            from site.t_temp_dbafiliasi_omzet_profile
            where site_code = '$site_code' and `group` = 'us'
        ";
        $proses = $this->db->query($sql);
        return $proses;
    }
    
    public function omzet_intrafood_pdf_profile($site_code)
    {
        $sql = "
            select if(sum(omzet) is null,0,omzet) as omzet
            from site.t_temp_dbafiliasi_omzet_profile
            where site_code = '$site_code' and `group` = 'intrafood'
        ";
        $proses = $this->db->query($sql);
        return $proses;
    }

    public function omzet_strive_pdf_profile($site_code)
    {
        $sql = "
            select if(sum(omzet) is null,0,omzet) as omzet
            from site.t_temp_dbafiliasi_omzet_profile
            where site_code = '$site_code' and `group` = 'strive'
        ";
        // $proses = $this->db->query($sql);
        $proses = $this->db->query($sql);
        return $proses;
    }
    
    public function omzet_hni_pdf_profile($site_code)
    {
        $sql = "
            select if(sum(omzet) is null,0,omzet) as omzet
            from site.t_temp_dbafiliasi_omzet_profile
            where site_code = '$site_code' and `group` = 'hni'
        ";
        $proses = $this->db->query($sql);
        return $proses;
    }
    
    public function omzet_mdj_pdf_profile($site_code)
    {
        $sql = "
            select if(sum(omzet) is null,0,omzet) as omzet
            from site.t_temp_dbafiliasi_omzet_profile
            where site_code = '$site_code' and `group` = 'mdj'
        ";
        $proses = $this->db->query($sql);
        return $proses;
        
    }

    public function omzet_total_pdf_profile($site_code)
    {
        $sql = "
            select if(sum(omzet) is null,0,omzet) as omzet
            from site.t_temp_dbafiliasi_omzet_profile
            where site_code = '$site_code'
        ";
        $proses = $this->db->query($sql);
        return $proses ;
    }

    public function pdf_detail_gudang($id)
    {
        $sql = "
        SELECT *
        FROM
        (
            SELECT *
            FROM site.t_dbafiliasi_tabel_detail_gudang b
            where b.id = $id
        )b
        LEFT JOIN
        (
            SELECT COUNT(c.id) as total_pria, c.site_code
            FROM site.t_dbafiliasi_tabel_karyawan c
            WHERE c.jenis_kelamin = 'pria'
            GROUP BY c.site_code
        )c on  b.site_code = c.site_code
        LEFT JOIN
        (
            SELECT COUNT(d.id) as total_wanita, d.site_code
            FROM site.t_dbafiliasi_tabel_karyawan d
            WHERE d.jenis_kelamin = 'wanita'
            GROUP BY d.site_code
        )d on  b.site_code = d.site_code
        LEFT JOIN
        (
            SELECT COUNT(e.id) as total_karyawan, e.site_code
            FROM site.t_dbafiliasi_tabel_karyawan e
            GROUP BY e.site_code
        )e on  b.site_code = e.site_code
        LEFT JOIN
        (
            SELECT COUNT(f.id) as total_gudang, f.site_code
            FROM site.t_dbafiliasi_tabel_karyawan f
            WHERE f.jabatan in('logistik_manager','spv_logistik','kepala_logistik','admin_logistik')
            GROUP BY f.site_code
        )f on b.site_code = f.site_code
        LEFT JOIN
        (
            SELECT COUNT(g.id) as total_ekspedisi, g.site_code
            FROM site.t_dbafiliasi_tabel_karyawan g
            WHERE g.jabatan in('admin_ekspedisi')
            GROUP BY g.site_code
        )g on b.site_code = g.site_code
        LEFT JOIN
        (
            SELECT COUNT(h.id) as total_spv, h.site_code
            FROM site.t_dbafiliasi_tabel_karyawan h
            WHERE h.jabatan in('spv_sales','spv_logistik','spv_sales','spv_finance','admin_spv')
            GROUP BY h.site_code
        )h on  b.site_code = h.site_code
        LEFT JOIN
        (
            SELECT COUNT(i.id) as total_staff, i.site_code
            FROM site.t_dbafiliasi_tabel_karyawan i
            WHERE i.jabatan NOT in ('direktur','bm','sm','abm','finance_manager','logistik_manager','spv_sales','spv_logistik','spv_sales','spv_finance','admin_spv')
            GROUP BY i.site_code
        )i on  b.site_code = i.site_code
        LEFT JOIN
        (
            SELECT COUNT(j.id) as total_kalog, j.site_code
            FROM site.t_dbafiliasi_tabel_karyawan j
            WHERE j.jabatan in ('kepala_logistik')
            GROUP BY j.site_code
        )j on  b.site_code = j.site_code
        LEFT JOIN
        (
            SELECT COUNT(k.id) as total_admin, k.site_code
            FROM site.t_dbafiliasi_tabel_karyawan k
            WHERE k.jabatan in ('admin_logistik','admin_ekspedisi','admin_spv')
            GROUP BY k.site_code
        )k on  b.site_code = k.site_code
        LEFT JOIN
        (
            SELECT COUNT(l.id) as total_picker, l.site_code
            FROM site.t_dbafiliasi_tabel_karyawan l
            WHERE l.jabatan in ('helper_picker','checker')
            GROUP BY l.site_code
        )l on  b.site_code = l.site_code
        LEFT JOIN
        (
            SELECT COUNT(m.id) as total_driver, m.site_code
            FROM site.t_dbafiliasi_tabel_karyawan m
            WHERE m.jabatan in ('driver')
            GROUP BY m.site_code
        )m on  b.site_code = m.site_code
        LEFT JOIN
        (
            SELECT COUNT(n.id) as total_spv_sales, n.site_code
            FROM site.t_dbafiliasi_tabel_karyawan n
            WHERE n.jabatan in ('spv_sales')
            GROUP BY n.site_code
        )n on  b.site_code = n.site_code
        LEFT JOIN
        (
            SELECT COUNT(o.id) as total_salesforce, o.site_code
            FROM site.t_dbafiliasi_tabel_karyawan o
            WHERE o.jabatan in ('salesforce_grosir','salesforce_reguler','salesforce_mt','salesforce_apotik')
            GROUP BY o.site_code
        )o on  b.site_code = o.site_code
        LEFT JOIN
        (
            SELECT COUNT(p.id) as total_admin_spv, p.site_code
            FROM site.t_dbafiliasi_tabel_karyawan p
            WHERE p.jabatan in ('admin_spv')
            GROUP BY p.site_code
        )p on  b.site_code = p.site_code
        LEFT JOIN
        (
            SELECT COUNT(q.id) as total_kasir, q.site_code
            FROM site.t_dbafiliasi_tabel_karyawan q
            WHERE q.jabatan in ('kasir')
            GROUP BY q.site_code
        )q on  b.site_code = q.site_code
        LEFT JOIN
        (
            SELECT COUNT(r.id) as total_fakturis, r.site_code
            FROM site.t_dbafiliasi_tabel_karyawan r
            WHERE r.jabatan in ('fakturis')
            GROUP BY r.site_code
        )r on  b.site_code = r.site_code
        LEFT JOIN
        (
            SELECT COUNT(s.id) as total_admin_logistik, s.site_code
            FROM site.t_dbafiliasi_tabel_karyawan s
            WHERE s.jabatan in ('admin_logistik')
            GROUP BY s.site_code
        )s on  b.site_code = s.site_code
        LEFT JOIN
        (
            SELECT COUNT(t.id) as total_admin_ekspedisi, t.site_code
            FROM site.t_dbafiliasi_tabel_karyawan t
            WHERE t.jabatan in ('admin_ekspedisi')
            GROUP BY t.site_code
        )t on  b.site_code = t.site_code
        ";
        $proses = $this->db->query($sql);
        return $proses ;
    }

    public function get_preview_profile($id, $date)
    {
        $sql = "
            select *
            from site.t_temp_dbafiliasi_tabel_profile a left join 
            (
                select concat(a.kode_comp, a.nocab) as site_code, a.branch_name, a.nama_comp
                from mpm.tbl_tabcomp a
                where a.status = 1
                group by concat(a.kode_comp, a.nocab)
            )b on a.site_code = b.site_code
            where created_by = $id and created_at = '$date' 
        ";       

        $proses = $this->db->query($sql);
        return $proses;
    }

    public function get_preview_detail_gudang($id, $date)
    {
        $sql = "
            select *
            from site.t_temp_dbafiliasi_tabel_detail_gudang a left join 
            (
                select concat(a.kode_comp, a.nocab) as site_code, a.branch_name, a.nama_comp
                from mpm.tbl_tabcomp a
                where a.status = 1
                group by concat(a.kode_comp, a.nocab)
            )b on a.site_code = b.site_code
            where created_by = $id and created_at = '$date' 
        ";       

        $proses = $this->db->query($sql);
        return $proses;
    }

    public function get_preview_karyawan($id, $date)
    {
        $sql = "
            select *
            from site.t_temp_dbafiliasi_tabel_karyawan a left join 
            (
                select concat(a.kode_comp, a.nocab) as site_code, a.branch_name, a.nama_comp
                from mpm.tbl_tabcomp a
                where a.status = 1
                group by concat(a.kode_comp, a.nocab)
            )b on a.site_code = b.site_code
            where created_by = $id and created_at = '$date' 
        ";       

        $proses = $this->db->query($sql);
        return $proses;
    }

    public function get_preview_niaga($id, $date)
    {
        $sql = "
            select *
            from site.t_temp_dbafiliasi_tabel_niaga a left join 
            (
                select concat(a.kode_comp, a.nocab) as site_code, a.branch_name, a.nama_comp
                from mpm.tbl_tabcomp a
                where a.status = 1
                group by concat(a.kode_comp, a.nocab)
            )b on a.site_code = b.site_code
            where created_by = $id and created_at = '$date' 
        ";       

        $proses = $this->db->query($sql);
        return $proses;
    }

    public function get_preview_non_niaga($id, $date)
    {
        $sql = "
            select *
            from site.t_temp_dbafiliasi_tabel_non_niaga a left join 
            (
                select concat(a.kode_comp, a.nocab) as site_code, a.branch_name, a.nama_comp
                from mpm.tbl_tabcomp a
                where a.status = 1
                group by concat(a.kode_comp, a.nocab)
            )b on a.site_code = b.site_code
            where created_by = $id and created_at = '$date' 
        ";       

        $proses = $this->db->query($sql);
        return $proses;
    }

    public function get_preview_it_asset($id, $date)
    {
        $sql = "
            select *
            from site.t_temp_dbafiliasi_tabel_it_asset a left join 
            (
                select concat(a.kode_comp, a.nocab) as site_code, a.branch_name, a.nama_comp
                from mpm.tbl_tabcomp a
                where a.status = 1
                group by concat(a.kode_comp, a.nocab)
            )b on a.site_code = b.site_code
            where created_by = $id and created_at = '$date' 
        ";       

        $proses = $this->db->query($sql);
        return $proses;
    }

    public function get_preview_asset($id, $date)
    {
        $sql = "
            select *
            from site.t_temp_dbafiliasi_tabel_asset a left join 
            (
                select concat(a.kode_comp, a.nocab) as site_code, a.branch_name, a.nama_comp
                from mpm.tbl_tabcomp a
                where a.status = 1
                group by concat(a.kode_comp, a.nocab)
            )b on a.site_code = b.site_code
            where created_by = $id and created_at = '$date' 
        ";       

        $proses = $this->db->query($sql);
        return $proses;
    }

    public function simpan_preview($created_date)
    {
        $id = $this->session->userdata('id');

        $profile = "
        insert into site.t_dbafiliasi_tabel_profile (
            site_code,  nama,  status_afiliasi,  alamat,  propinsi,  kota,  kecamatan,  kelurahan,
            kodepos,  telp,  status_properti,  sewa_from,  sewa_to,  harga_sewa,  bentuk_bangunan,
            foto_tampak_depan, foto_gudang, foto_kantor, foto_area_loading_gudang, foto_gudang_baik,
            foto_gudang_retur, created_at, created_by
        )

        SELECT site_code,  nama,  status_afiliasi,  alamat,  propinsi,  kota,  kecamatan,  kelurahan,
                kodepos,  telp,  status_properti,  sewa_from,  sewa_to,  harga_sewa,  bentuk_bangunan,
                foto_tampak_depan, foto_gudang, foto_kantor, foto_area_loading_gudang, foto_gudang_baik,
                foto_gudang_retur, created_at, created_by
        FROM site.t_temp_dbafiliasi_tabel_profile
        where created_by = '$id' and created_at = '$created_date'
        ";
        $proses_profile = $this->db->query($profile);
        
        $detail_gudang = "
        insert into site.t_dbafiliasi_tabel_detail_gudang (
            site_code, luas_gudang, panjang_gudang, lebar_gudang, pallet_gudang, racking_gudang,
            luas_gudang_baik, panjang_gudang_baik, lebar_gudang_baik, pallet_gudang_baik, racking_gudang_baik,
            luas_gudang_retur, panjang_gudang_retur, lebar_gudang_retur, pallet_gudang_retur, racking_gudang_retur,
            luas_gudang_karantina, panjang_gudang_karantina, lebar_gudang_karantina, pallet_gudang_karantina, racking_gudang_karantina,
            luas_gudang_ac, panjang_gudang_ac, lebar_gudang_ac, pallet_gudang_ac, racking_gudang_ac,
            luas_loading_dock, panjang_loading_dock, lebar_loading_dock, pallet_gudang_loading, racking_gudang_loading,
            jumlah_pallet, jumlah_hand_pallet, jumlah_trolley, jumlah_sealer, jumlah_ac, jumlah_exhaust_fan, jumlah_kipas_angin,
            luas_kantor_div_logistik, panjang_kantor_div_logistik, lebar_kantor_div_logistik, total_mobil_penumpang,
            jumlah_mobil_penumpang_milik_sendiri, jumlah_mobil_penumpang_sewa, total_mobil_pengiriman, jumlah_mobil_pengiriman_blind_van,
            jumlah_mobil_pengiriman_double, jumlah_mobil_pengiriman_engkel, total_blind_van, jumlah_blind_van_milik_sendiri,
            jumlah_blind_van_sewa, total_engkel, jumlah_engkel_milik_sendiri, jumlah_engkel_sewa, total_double, jumlah_double_sewa,
            jumlah_double_milik_sendiri, total_motor_pengiriman, jumlah_motor_pengiriman_milik_sendiri, jumlah_motor_pengiriman_sewa,
            total_saddle_bag, jumlah_saddle_bag_dipakai, jumlah_saddle_bag_cadangan, luas_kantor_total, panjang_kantor_total,
            lebar_kantor_total, luas_ruang_sales, panjang_ruang_sales, lebar_ruang_sales, luas_ruang_finance, panjang_ruang_finance,
            lebar_ruang_finance, luas_ruang_logistik, panjang_ruang_logistik, lebar_ruang_logistik, luas_gudang_arsip, panjang_gudang_arsip,
            lebar_gudang_arsip, created_at, created_by
        )
        select site_code, luas_gudang, panjang_gudang, lebar_gudang, pallet_gudang, racking_gudang,
                luas_gudang_baik, panjang_gudang_baik, lebar_gudang_baik, pallet_gudang_baik, racking_gudang_baik,
                luas_gudang_retur, panjang_gudang_retur, lebar_gudang_retur, pallet_gudang_retur, racking_gudang_retur,
                luas_gudang_karantina, panjang_gudang_karantina, lebar_gudang_karantina, pallet_gudang_karantina, racking_gudang_karantina,
                luas_gudang_ac, panjang_gudang_ac, lebar_gudang_ac, pallet_gudang_ac, racking_gudang_ac,
                luas_loading_dock, panjang_loading_dock, lebar_loading_dock, pallet_gudang_loading, racking_gudang_loading,
                jumlah_pallet, jumlah_hand_pallet, jumlah_trolley, jumlah_sealer, jumlah_ac, jumlah_exhaust_fan, jumlah_kipas_angin,
                luas_kantor_div_logistik, panjang_kantor_div_logistik, lebar_kantor_div_logistik, total_mobil_penumpang,
                jumlah_mobil_penumpang_milik_sendiri, jumlah_mobil_penumpang_sewa, total_mobil_pengiriman, jumlah_mobil_pengiriman_blind_van,
                jumlah_mobil_pengiriman_double, jumlah_mobil_pengiriman_engkel, total_blind_van, jumlah_blind_van_milik_sendiri,
                jumlah_blind_van_sewa, total_engkel, jumlah_engkel_milik_sendiri, jumlah_engkel_sewa, total_double, jumlah_double_sewa,
                jumlah_double_milik_sendiri, total_motor_pengiriman, jumlah_motor_pengiriman_milik_sendiri, jumlah_motor_pengiriman_sewa,
                total_saddle_bag, jumlah_saddle_bag_dipakai, jumlah_saddle_bag_cadangan, luas_kantor_total, panjang_kantor_total,
                lebar_kantor_total, luas_ruang_sales, panjang_ruang_sales, lebar_ruang_sales, luas_ruang_finance, panjang_ruang_finance,
                lebar_ruang_finance, luas_ruang_logistik, panjang_ruang_logistik, lebar_ruang_logistik, luas_gudang_arsip, panjang_gudang_arsip,
                lebar_gudang_arsip, created_at, created_by
        from site.t_temp_dbafiliasi_tabel_detail_gudang
        where created_by = '$id' and created_at = '$created_date'
        ";
        $proses_detail_gudang = $this->db->query($detail_gudang);
        
        $karyawan = "
        insert into site.t_dbafiliasi_tabel_karyawan (
            site_code, nama, jenis_kelamin, tempat, tanggal_lahir, tingkat_pendidikan,
            status_pernikahan, department, jabatan, status_karyawan, tanggal_masuk_kerja,
            created_at, created_by
        )
        select site_code, nama, jenis_kelamin, tempat, tanggal_lahir, tingkat_pendidikan,
                status_pernikahan, department, jabatan, status_karyawan, tanggal_masuk_kerja,
                created_at, created_by
        from site.t_temp_dbafiliasi_tabel_karyawan
        where created_by = '$id' and created_at = '$created_date'
        ";
        $proses_karyawan = $this->db->query($karyawan);
        
        $niaga = "
        insert into site.t_dbafiliasi_tabel_niaga (
            site_code, jenis_kendaraan, kepemilikan, bahan_bakar, no_polisi, tahun_pembuatan, tanggal_pajak_berakhir,
            tanggal_pajak_kir, vendor, tanggal_awal_sewa, tanggal_akhir_sewa, created_at, created_by
        )
        select site_code, jenis_kendaraan, kepemilikan, bahan_bakar, no_polisi, tahun_pembuatan, tanggal_pajak_berakhir,
                tanggal_pajak_kir, vendor, tanggal_awal_sewa, tanggal_akhir_sewa, created_at, created_by
        from site.t_temp_dbafiliasi_tabel_niaga
        where created_by = '$id' and created_at = '$created_date'
        ";
        $proses_niaga = $this->db->query($niaga);
        
        $non_niaga = "
        insert into site.t_dbafiliasi_tabel_non_niaga (
            site_code, jenis_kendaraan, kepemilikan, nama_pemakai, jabatan, bahan_bakar, no_polisi, tahun_pembuatan,tanggal_pajak_berakhir, vendor, tanggal_awal_sewa, tanggal_akhir_sewa, created_at, created_by
        )
        select site_code, jenis_kendaraan, kepemilikan, nama_pemakai, jabatan, bahan_bakar, no_polisi, tahun_pembuatan,
                tanggal_pajak_berakhir, vendor, tanggal_awal_sewa, tanggal_akhir_sewa, created_at, created_by
        from site.t_temp_dbafiliasi_tabel_non_niaga
        where created_by = '$id' and created_at = '$created_date'
        ";
        $proses_non_niaga = $this->db->query($non_niaga);
        
        $it_asset = "
        insert into site.t_dbafiliasi_tabel_it_asset (
            site_code, nama_asset, merk, type, tanggal_pembelian, operating_system, processor, ram,
            `storage`, kapasitas_baterai, divisi_pemakai, jabatan_pemakai, created_at, created_by
        )
        select site_code, nama_asset, merk, type, tanggal_pembelian, operating_system, processor, ram,
                `storage`, kapasitas_baterai, divisi_pemakai, jabatan_pemakai, created_at, created_by from site.t_temp_dbafiliasi_tabel_it_asset
        where created_by = '$id' and created_at = '$created_date'
        ";
        $proses_it_asset = $this->db->query($it_asset);
        
        $asset = "
        insert into site.t_dbafiliasi_tabel_asset (
            site_code, jenis_asset, merk, type, tanggal_pembelian, divisi_pemakai, jabatan_pemakai, created_at, created_by
        )
        select site_code, jenis_asset, merk, type, tanggal_pembelian, divisi_pemakai, jabatan_pemakai, created_at, created_by
        from site.t_temp_dbafiliasi_tabel_asset
        where created_by = '$id' and created_at = '$created_date'
        ";
        $proses_asset = $this->db->query($asset);
        
    }
}