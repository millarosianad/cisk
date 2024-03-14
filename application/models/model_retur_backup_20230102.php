<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_retur extends CI_Model 
{
    function insert($data)
	{
        $this->db->query("truncate db_retur.t_import_retur");
		$this->db->insert_batch('db_retur.t_import_retur', $data);
    }
    
    function get_import_retur()
	{
        $sql = "
        select * from db_retur.t_import_retur a
        union all
        select '','','','','','total',sum(banyak),'','','','','','','','' from db_retur.t_import_retur a
        ";
        $proses = $this->db->query($sql)->result();
        return $proses;
    }

    function transfer_retur()
    {
        date_default_timezone_set('Asia/Jakarta');        
        $tgl_created='"'.date('Y-m-d H:i:s').'"';
        $id = $this->session->userdata('id');

        $jumlah ="
            select count(*) as a
            FROM
            (
                select *
                from db_retur.t_import_retur a
                GROUP BY a.noseri
            )a
        ";
        $jumlah_proses = $this->db->query($jumlah)->result();
        foreach ($jumlah_proses as $b) 
        {
            $jumlah = $b->a;
        }

        $sql ="
            select *
            from db_retur.t_import_retur a
            GROUP BY a.noseri
        ";
        $proses = $this->db->query($sql)->result();

        foreach ($proses as $a) 
        {            
            $noseri = $a->noseri;
            $noseri_beli = $a->noseri_beli;
            $userid = $a->userid;
            $supp = $a->supp;
            $nopo = $a->nopo;
            $nodo = $a->nodo;
            $tgldo = $a->tgldo;
            $nodo_beli = $a->nodo_beli;
            $tglbuat = $a->tglbuat;

            $data = array(
                'client'   => $userid,
                'tgl'      => $tgldo,
            );            
            $this->session->set_userdata($data);

            

            $sql = "
                insert into mpm.trans
                select 	'', '$noseri' as noseri,'$noseri_beli' as noseri_beli,$userid as userid,b.company, b.nama_wp,b.npwp,b.alamat_wp,b.email,
                        '$supp' as supp,'$nopo' as nopo,'' as tglpo,'$nodo' as nodo,'$tgldo' as tgldo,'$nodo_beli' as nodo_beli,'$tgldo' as tgldo_beli, '' as tglpesan, '$tgl_created' as created,'$tgl_created' as modified, $id as created_by, $id as modified_by,'R' as tipe, b.address as alamat, '$tglbuat' as tglbuat, 0 as deleted, 0 as ambil
                from db_retur.t_import_retur a LEFT JOIN
                (
                    select 	a.id, a.company,a.nama_wp,a.npwp,a.email, a.alamat_wp, a.address
                    from	mpm.`user` a
                )b on a.userid = b.id
                where a.userid = $userid
                GROUP BY a.noseri
            ";
            $this->db->query($sql);

            $id_ref_retur =$this->db->query("select max(id) as id from mpm.trans where userid = $userid")->result();
            foreach ($id_ref_retur as $c) {
                $id_ref_retur = $c->id;
            }

            $sql = "
                insert into mpm.trans_detail
                select 	'' as id, $id_ref_retur as id_ref, a.supp, a.kodeprod, b.namaprod,a.banyak,
            		    '' as harga,'' as harga_beli,'' as diskon,'' as diskon_beli,b.kode_prc,
            		    a.userid, 0 as deleted, $tgl_created as created, $tgl_created as modified, $id as created_by, $id as modified_by
                from db_retur.t_import_retur a LEFT JOIN
                (
                    select 	a.kodeprod, a.namaprod,a.kode_prc
                    from		mpm.tabprod a
                )b on a.kodeprod = b.kodeprod
                where a.userid = $userid
            ";
            $proses = $this->db->query($sql);

            $this->load->model('trans_model');
            $cari_produk = $this->db->query("select kodeprod from mpm.trans_detail a where a.id_ref = $id_ref_retur group by kodeprod")->result();
            
            foreach ($cari_produk as $d) 
            {
                $kodeprod_trans_detail = $d->kodeprod;

                $detail = $this->trans_model->getProductRetur($kodeprod_trans_detail)->result();
                // var_dump($detail);
                foreach ($detail as $e) {
                    $harga    = $e->harga;  
                    $harga_beli = $e->harga_beli;
                    $diskon = $e->diskon;
                    $diskon_beli  = $e->diskon_beli;
                }

                $sql = "update mpm.trans_detail a set a.harga = '$harga', a.harga_beli = '$harga_beli', a.diskon = '$diskon', a.diskon_beli = '$diskon_beli' where a.id_ref=$id_ref_retur and a.kodeprod = $kodeprod_trans_detail";
                $proses = $this->db->query($sql);
            }
        }
        return 1;
        

    }

    public function generate($data){
        $id=$this->session->userdata('id');
        if ( function_exists( 'date_default_timezone_set' ) )
        date_default_timezone_set('Asia/Jakarta');        
        $tgl_created='"'.date('Y-m-d H:i:s').'"';
        
        $periode_1 = $data['periode_1'];
        $periode_2 = $data['periode_2'];

        // echo $periode_1;
        // echo $periode_2;

        $sql = "
        update mpm.trans a INNER JOIN mpm.trans_detail b
            on a.id = b.id_ref
        set b.bruto = b.banyak * b.harga_beli * -1,
                b.disc = b.banyak * b.harga_beli * b.diskon_beli / 100 * -1,
                b.dpp = (b.banyak * b.harga_beli * -1) - (b.banyak * b.harga_beli * b.diskon_beli / 100*-1)
        where a.tgl_beli BETWEEN '$periode_1' and '$periode_2'
        ";
        $proses = $this->db->query($sql);
        
        $sql = "
        update mpm.trans a INNER JOIN mpm.trans_detail b
            on a.id = b.id_ref
        set b.bruto = b.banyak * b.harga_beli * -1,
            b.disc = b.banyak * floor(b.harga_beli * b.diskon_beli / 100) * -1,
            b.dpp = (b.banyak * b.harga_beli * -1) - (b.banyak * floor(b.harga_beli * b.diskon_beli / 100)*-1)
        where a.supp = 005 and b.kodeprod not in (select c.kodeprod from db_produk.t_product_retur c where c.aktif = 1)
        and a.tgl_beli BETWEEN '$periode_1' and '$periode_2'

        ";
        $proses = $this->db->query($sql);

        if ($proses) 
        {
            $sql = "
            insert into db_temp.t_temp_generate_retur 
            select a.noseri,a.noseri_beli,a.nodo_beli,company,a.supp,c.namasupp,a.tgldo_beli,sum(b.bruto) as bruto,sum(b.disc) as disc,sum(b.dpp) as dpp,$id,$tgl_created
            from mpm.trans a INNER JOIN mpm.trans_detail b
                on a.id = b.id_ref left join mpm.tabsupp c
                on a.supp = c.supp
            where a.tgl_beli BETWEEN '$periode_1' and '$periode_2'
            GROUP BY a.nodo_beli
            ORDER BY a.id desc
            ";
            $proses = $this->db->query($sql);
            if ($proses) {
                $sql = "
                    select *
                    from db_temp.t_temp_generate_retur a
                    where a.id = $id and a.created_date = $tgl_created
                ";
                $proses = $this->db->query($sql)->result();
                return $proses;
            }else{
                return array();
            }
        }else
        {
            return array();
        }
        
    }

    public function generate_no_pengajuan_retur($site_code,$tanggal_pengajuan){
  
        // $sql = "select a.no_pengajuan, right(a.no_pengajuan,1) as urut, a.tanggal_pengajuan
        //         from db_temp.t_temp_pengajuan_retur a
        //         where a.site_code = '$site_code'
        //         ORDER BY a.id desc
        //         limit 1
        // ";
        $sql = "select a.no_pengajuan, REPLACE(right(a.no_pengajuan,2),'/',0) as urut, a.tanggal_pengajuan
                from db_temp.t_temp_pengajuan_retur a
                where a.site_code = '$site_code'
                ORDER BY a.id desc
                limit 1
        ";
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0)
        {
            $row = $query->row();
            $urut = $row->urut + 1;
            if (strlen($urut) == 1) {
                $urut = '0'.$urut;
                // echo "urut ";
            }
            // echo $row->urut."<br>";
            // echo $urut;

            // $no_pengajuan = "RTR/".$site_code."/".$tanggal_pengajuan."/".$urut;
            $no_pengajuan = "RTR/".$site_code."/".$this->session->userdata('username')."/".$tanggal_pengajuan."/".$urut;
        }else{
            $no_pengajuan = "RTR/".$site_code."/".$tanggal_pengajuan."/01";
        }

        // echo "no_pengajuan : ".$no_pengajuan;
        return $no_pengajuan;

    }
    
    public function insert_pengajuan_retur($data){
        $this->db->insert('db_temp.t_temp_pengajuan_retur',$data);
        // return $this->db->insert_id(); 
        $signature = $this->get_signature($this->db->insert_id());
        return $signature;
    }

    public function get_signature($id){
        $sql = $this->db->query("select signature from db_temp.t_temp_pengajuan_retur a where a.id = $id")->row()->signature;
        return $sql;
    }

    public function get_id_by_signature($signature){
        $sql = $this->db->query("select id from db_temp.t_temp_pengajuan_retur a where a.signature = '$signature'")->row()->id;
        return $sql;
    }

    public function get_supp_by_signature($signature){
        $sql = $this->db->query("select supp from db_temp.t_temp_pengajuan_retur a where a.signature = '$signature'")->row()->supp;
        return $sql;
    }

    public function insert_produk_ajuan_retur($data){
        $this->db->insert('db_temp.t_temp_produk_pengajuan_retur',$data);

        //insert log
        $created_date = $this->model_outlet_transaksi->timezone();
        $id = $this->session->userdata('id');
        $params = [
            'id_ref' => $this->db->insert_id(),
            'created_date' =>$created_date,
            'created_by' =>$id,
            'action'    => 'user add'
        ];
        $this->db->insert('db_temp.t_temp_produk_pengajuan_retur_log',$params);
        return $this->db->affected_rows(); 
    }

    public function history_pengajuan_retur($kode_alamat, $id = null)
    {
        if ($id == null) {
            $params = "";
        }else{
            $params = "and a.id = $id";
        }
        $sql = "
        select a.id, a.no_pengajuan, a.site_code, a.nama, a.supp, a.tanggal_pengajuan, a.`status`, a.signature, c.namasupp, a.nama_status, b.nama_comp
        from db_temp.t_temp_pengajuan_retur a LEFT JOIN 
        (
            select concat(a.kode_comp, a.nocab) as kode, a.branch_name, a.nama_comp
            from mpm.tbl_tabcomp a
            where a.status = 1
            GROUP BY concat(a.kode_comp, a.nocab)
        )b on a.site_code = b.kode left join mpm.tabsupp c on a.supp = c.supp
        where a.deleted is null and a.site_code in ($kode_alamat) $params order by id desc";
        echo "<pre>";
        print_r($sql);
        echo "</pre>";
        $proses = $this->db->query($sql)->result();
        return $proses;
    }
    
    // public function log_retur($signature)
    // {
    //     if ($signature == null) {
    //         $params = "";
    //     }else{
    //         $params = "and a.signature = '$signature'";
    //     }
    //     $sql = "
    //         select a.id, a.no_pengajuan, a.site_code, a.nama, a.supp, a.tanggal_pengajuan, a.`status`, a.signature,
    //                 a.nama_status, a.nama, a.file, a.file_principal, a.file_pengiriman, a.file_pemusnahan,
    //                 a.foto_pemusnahan_1, a.foto_pemusnahan_2, a.file_terima, c.namasupp
    //         from db_temp.t_temp_pengajuan_retur a LEFT JOIN mpm.tabsupp c on a.supp = c.SUPP
    //         where a.deleted is null $params
    //         order by id desc";
    //     $proses = $this->db->query($sql);
    //     return $proses;
    // }

    public function log_retur($signature)
    {
        if ($signature == null) {
            $params = "";
        }else{
            $params = "and a.signature = '$signature'";
        }
        $sql = "
            select a.*, b.branch_name, b.nama_comp, c.namasupp
            from db_temp.t_temp_pengajuan_retur a LEFT JOIN 
            (
                select concat(a.kode_comp, a.nocab) as kode, a.branch_name, a.nama_comp
                from mpm.tbl_tabcomp a
                where a.status = 1
                GROUP BY concat(a.kode_comp, a.nocab)
            )b on a.site_code = b.kode LEFT JOIN mpm.tabsupp c on a.supp = c.SUPP
            where a.deleted is null $params
            order by id desc";
        $proses = $this->db->query($sql);
        return $proses;
    }

    public function produk_pengajuan_retur($id)
    {
        // $sql = "select *
        // from db_temp.t_temp_pengajuan_retur a
        // where a.site_code in ($kode_alamat) order by id desc";
        // $proses = $this->db->query($sql)->result();
        // return $proses;
    }

    public function get_produk_pengajuan($id, $supp)
    {
        // echo "id : ".$id;
        // die;

        if ($supp == '005') {
            $proses = $this->db->query("select a.id, a.id_pengajuan, a.kodeprod, a.namaprod, a.batch_number, a.expired_date, a.jumlah, 
            a.alasan, a.satuan, a.nama_outlet, a.keterangan, a.`status`, a.nama_status, a.deskripsi, b.no_pengajuan, b.tanggal_pengajuan, b.signature, a.total_karton, a.total_dus, a.total_pcs, a.harga_karton, a.harga_dus, a.harga_pcs, a.value, a.kode_produksi
            from db_temp.t_temp_produk_pengajuan_retur a left join db_temp.t_temp_pengajuan_retur b on a.id_pengajuan = b.id where a.id_pengajuan = $id and a.deleted is null")->result();

        }else{

            $proses = $this->db->query("select a.id, a.id_pengajuan, a.kodeprod, a.namaprod, a.batch_number, a.expired_date, a.jumlah, 
            a.alasan, a.satuan, a.nama_outlet, a.keterangan, a.`status`, a.nama_status, a.deskripsi, b.no_pengajuan, b.tanggal_pengajuan, b.signature, a.total_karton, a.total_dus, a.total_pcs, a.harga_karton, a.harga_dus, a.harga_pcs, a.value, a.kode_produksi
            from db_temp.t_temp_produk_pengajuan_retur a left join db_temp.t_temp_pengajuan_retur b on a.id_pengajuan = b.id where a.id_pengajuan = $id and a.deleted is null")->result();

        }
     
        return $proses;
    }

    public function get_produk_retur_by_id($id)
    {
        $proses = $this->db->query("select a.id, a.id_pengajuan, a.kodeprod, a.namaprod, a.batch_number, a.expired_date, a.jumlah, a.alasan, a.satuan, a.nama_outlet, a.keterangan, a.`status`, a.nama_status, a.deskripsi, a.deleted, b.signature, concat(a.jumlah,' ', a.satuan) as jumlahsatuan,a.total_karton, a.total_dus, a.total_pcs, b.supp
        from db_temp.t_temp_produk_pengajuan_retur a left join db_temp.t_temp_pengajuan_retur b on a.id_pengajuan = b.id where a.id = $id")->row();
        return $proses;
    }

    public function get_produk_retur_by_id_intrafood($id)
    {
        $proses = $this->db->query("select a.id, a.id_pengajuan, a.kodeprod, a.namaprod, a.batch_number, a.expired_date, a.jumlah, a.alasan, a.satuan, a.nama_outlet, a.keterangan, a.`status`, a.nama_status, a.deskripsi, a.deleted, b.signature, concat(a.jumlah,' ', a.satuan) as jumlahsatuan, a.
        from db_temp.t_temp_produk_pengajuan_retur a left join db_temp.t_temp_pengajuan_retur b on a.id_pengajuan = b.id where a.id = $id")->row();
        return $proses;
    }

    public function get_supp_pengajuan($id){
        $proses = $this->db->query("select supp from db_temp.t_temp_pengajuan_retur a where a.id = $id")->result();
        foreach ($proses as $x) {
            $supp = $x->supp;
        }
        return $supp;

    }

    public function get_supp_pengajuan_by_signature($signature){
        $proses = $this->db->query("select supp from db_temp.t_temp_pengajuan_retur a where a.signature = '$signature'")->result();
        foreach ($proses as $x) {
            $supp = $x->supp;
        }
        return $supp;

    }

    public function email(){
        $this->load->library('email');

        $config['protocol']     = 'smtp';
        $config['smtp_host']    = 'ssl://mail.muliaputramandiri.com';
        $config['smtp_port']    = '465';
        $config['smtp_timeout'] = '300';
        $config['smtp_user']    = 'support@muliaputramandiri.com';
        $config['smtp_pass']    = 'support123!@#';
        $config['charset']      = 'utf-8';
        $config['newline']      = "\r\n";
        $config['mailtype']     ="html";
        $config['use_ci_email'] = TRUE;
        $config['wordwrap']     = TRUE;

        
        // $config['protocol']     = 'smtp';
        // $config['smtp_host']    = 'ssl://smtp.gmail.com';
        // $config['smtp_port']    = '465';
        // $config['smtp_timeout'] = '300';
        // $config['smtp_user']    = 'suffy.yanuar@gmail.com';
        // $config['smtp_pass']    = 'ririn123!@#';
        // $config['charset']      = 'utf-8';
        // $config['newline']      = "\r\n";
        // $config['mailtype']     ="html";
        // $config['use_ci_email'] = TRUE;
        // $config['wordwrap']     = TRUE;

        $this->email->initialize($config);
    }

    public function get_no_pengajuan_by_id($id){
        return $this->db->query("select replace(no_pengajuan,'/','_') as no_pengajuan from db_temp.t_temp_pengajuan_retur a where a.id = $id")->row()->no_pengajuan;
    }

    public function generate_attachment_pengajuan_retur($id,$supp){
        
        $no_pengajuan = $this->get_no_pengajuan_by_id($id);

        $sql = "
        select 	a.no_pengajuan, a.site_code, a.nama, a.supp, a.tanggal_pengajuan, a.file, a.created_date, a.created_by,
                b.kodeprod, b.namaprod, b.batch_number, b.expired_date, b.jumlah, 
                b.alasan, b.nama_status , b.deskripsi, b.satuan,b.nama_outlet,b.keterangan, b.total_karton, b.total_dus, b.total_pcs, b.harga_karton,b.harga_dus, b.harga_pcs, b.value, b.kode_produksi
        from 	db_temp.t_temp_pengajuan_retur a LEFT JOIN db_temp.t_temp_produk_pengajuan_retur b
                    on a.id = b.id_pengajuan
        where 	a.id = $id and b.deleted is null
        ";

        $hasil = $this->db->query($sql);
        // print_r($sql);
        // die();

        $file = fopen(APPPATH . '/../assets/file/retur/'.$no_pengajuan.'.csv', 'wb');

        

        if ($supp == '001' || $supp == '002') {
            
            $csv_fields=array();
            $csv_fields[] = 'no_pengajuan';
            $csv_fields[] = 'kodeprod';
            $csv_fields[] = 'namaprod';
            $csv_fields[] = 'batch_number';
            $csv_fields[] = 'expired_date';
            $csv_fields[] = 'jumlah';
            $csv_fields[] = 'alasan';
            $csv_fields[] = 'status';
            $csv_fields[] = 'deskripsi';
            fputcsv($file, $csv_fields);
            
            foreach ($hasil->result() as $row) 
            {
                $no_pengajuan = $row->no_pengajuan;
                $kodeprod = $row->kodeprod;
                $namaprod = $row->namaprod;
                $batch_number = $row->batch_number;
                $expired_date = $row->expired_date;
                $jumlah = $row->jumlah;
                $alasan = $row->alasan;
                $nama_status = $row->nama_status;
                $deskripsi = $row->deskripsi;
                fputcsv($file, array($no_pengajuan,$kodeprod,$namaprod,$batch_number,$expired_date,$jumlah,$alasan,$nama_status,$deskripsi));
            }
        }elseif ($supp == '005') {

            $csv_fields=array();
            $csv_fields[] = 'no_pengajuan';
            $csv_fields[] = 'kodeprod';
            $csv_fields[] = 'namaprod';
            $csv_fields[] = 'batch_number';
            $csv_fields[] = 'expired_date';
            $csv_fields[] = 'jumlah';
            $csv_fields[] = 'satuan';
            $csv_fields[] = 'alasan';
            $csv_fields[] = 'nama_outlet';
            $csv_fields[] = 'keterangan';
            $csv_fields[] = 'status';
            $csv_fields[] = 'deskripsi';
            fputcsv($file, $csv_fields);

            foreach ($hasil->result() as $row) 
            {
                $no_pengajuan = $row->no_pengajuan;
                $kodeprod = $row->kodeprod;
                $namaprod = $row->namaprod;
                $batch_number = $row->batch_number;
                $expired_date = $row->expired_date;
                $jumlah = $row->jumlah;
                $satuan = $row->satuan;
                $alasan = $row->alasan;
                $nama_outlet = $row->nama_outlet;
                $keterangan = $row->keterangan;
                $nama_status = $row->nama_status;
                $deskripsi = $row->deskripsi;
                fputcsv($file, array($no_pengajuan,$kodeprod,$namaprod,$batch_number,$expired_date,$jumlah,$satuan,$alasan,$nama_outlet,$keterangan,$nama_status,$deskripsi));
            }
        }elseif ($supp == '012') {

            $csv_fields=array();
            $csv_fields[] = 'no_pengajuan';
            $csv_fields[] = 'kodeprod';
            $csv_fields[] = 'namaprod';
            $csv_fields[] = 'total_karton';
            $csv_fields[] = 'total_dus';
            $csv_fields[] = 'total_pcs';
            $csv_fields[] = 'harga_karton';
            $csv_fields[] = 'harga_dus';
            $csv_fields[] = 'harga_pcs';
            $csv_fields[] = 'value';
            $csv_fields[] = 'kode_produksi';
            $csv_fields[] = 'keterangan';
            $csv_fields[] = 'status';
            $csv_fields[] = 'deskripsi';
            fputcsv($file, $csv_fields);

            foreach ($hasil->result() as $row) 
            {
                $no_pengajuan = $row->no_pengajuan;
                $kodeprod = $row->kodeprod;
                $namaprod = $row->namaprod;
                $total_karton = $row->total_karton;
                $total_dus = $row->total_dus;
                $total_pcs = $row->total_pcs;
                $harga_karton = $row->harga_karton;
                $harga_dus = $row->harga_dus;
                $harga_pcs = $row->harga_pcs;
                $value = $row->value;
                $kode_produksi = $row->kode_produksi;
                $keterangan = $row->keterangan;
                $nama_status = $row->nama_status;
                $deskripsi = $row->deskripsi;
                fputcsv($file, array($no_pengajuan,$kodeprod,$namaprod,$total_karton,$total_dus,$total_pcs,$harga_karton,$harga_dus,$harga_pcs,$value, $kode_produksi, $keterangan,$nama_status,$deskripsi));
            }
        }


        
    }

    public function generate_attachment_pengajuan_retur_principal($id,$supp){
        
        $no_pengajuan = $this->get_no_pengajuan_by_id($id);

        if ($supp == '005') 
        {
            $sql = "
            select  c.branch_name, c.nama_comp as subbranch, a.no_pengajuan, b.kodeprod, b.namaprod, 
                    b.batch_number, b.expired_date, b.jumlah, 
                    b.satuan, b.nama_outlet, b.alasan, b.keterangan
            from db_temp.t_temp_pengajuan_retur a INNER JOIN
            (
                select * 
                from db_temp.t_temp_produk_pengajuan_retur a
            )b on a.id = b.id_pengajuan LEFT JOIN mpm.tabsupp c
                on a.supp = c.supp LEFT JOIN 
                (
                        select concat(a.kode_comp,a.nocab) as site_code, a.branch_name, a.nama_comp
                        from mpm.tbl_tabcomp a 
                        where a.`status` = 1
                        GROUP BY concat(a.kode_comp,a.nocab)
                )c on a.site_code = c.site_code
            where a.id = $id and b.deleted is null
            ";

            // echo "<pre>";
            // print_r($sql);
            // echo "</pre>";

        }else if($supp == '012'){
            $sql = "
            select  c.branch_name, c.nama_comp as subbranch, a.no_pengajuan, 
                    b.kodeprod, b.namaprod, 
                    b.total_karton,b.total_dus, b.total_pcs,
                    b.harga_karton, b.harga_dus, b.harga_pcs, b.value, b.kode_produksi, b.keterangan
            from db_temp.t_temp_pengajuan_retur a INNER JOIN
            (
                select * 
                from db_temp.t_temp_produk_pengajuan_retur a
            )b on a.id = b.id_pengajuan LEFT JOIN mpm.tabsupp c
                on a.supp = c.supp LEFT JOIN 
                (
                        select concat(a.kode_comp,a.nocab) as site_code, a.branch_name, a.nama_comp
                        from mpm.tbl_tabcomp a 
                        where a.`status` = 1
                        GROUP BY concat(a.kode_comp,a.nocab)
                )c on a.site_code = c.site_code
            where a.id = $id and b.deleted is null
            ";
        }else{

            $sql = "
            select 	a.no_pengajuan, a.site_code, a.nama, a.supp, a.tanggal_pengajuan, a.file, a.created_date, a.created_by,
                    b.kodeprod, b.namaprod, b.batch_number, b.expired_date, b.jumlah,b.alasan, b.nama_status , b.deskripsi, b.total_karton, b.total_dus, b.total_pcs, b.harga_karton,b.harga_dus, b.harga_pcs, b.value, b.kode_produksi
            from 	db_temp.t_temp_pengajuan_retur a LEFT JOIN db_temp.t_temp_produk_pengajuan_retur b
                        on a.id = b.id_pengajuan
            where 	a.id = $id and b.deleted is null
            ";
        }

        

        $hasil = $this->db->query($sql);
        // print_r($sql);
        // die();

        $file = fopen(APPPATH . '/../assets/file/retur/'.$no_pengajuan.'.csv', 'wb');

        if ($supp == '001' || $supp == '002') {
            
            $csv_fields=array();
            $csv_fields[] = 'no_pengajuan';
            $csv_fields[] = 'kodeprod';
            $csv_fields[] = 'namaprod';
            $csv_fields[] = 'batch_number';
            $csv_fields[] = 'expired_date';
            $csv_fields[] = 'jumlah';
            $csv_fields[] = 'alasan';
            // $csv_fields[] = 'status';
            $csv_fields[] = 'deskripsi';
            fputcsv($file, $csv_fields);
            
            foreach ($hasil->result() as $row) 
            {
                $no_pengajuan = $row->no_pengajuan;
                $kodeprod = $row->kodeprod;
                $namaprod = $row->namaprod;
                $batch_number = $row->batch_number;
                $expired_date = $row->expired_date;
                $jumlah = $row->jumlah;
                $alasan = $row->alasan;
                // $nama_status = $row->nama_status;
                $deskripsi = $row->deskripsi;
                fputcsv($file, array($no_pengajuan,$kodeprod,$namaprod,$batch_number,$expired_date,$jumlah,$alasan,$deskripsi));
            }
        }elseif ($supp == '005') {

            
            $csv_fields=array();
            $csv_fields[] = 'branch_name';
            $csv_fields[] = 'subbranch';
            $csv_fields[] = 'no_pengajuan';
            $csv_fields[] = 'kodeprod';
            $csv_fields[] = 'namaprod';
            $csv_fields[] = 'batch_number';
            $csv_fields[] = 'expired_date';
            $csv_fields[] = 'jumlah';
            $csv_fields[] = 'satuan';
            $csv_fields[] = 'nama_outlet';
            $csv_fields[] = 'alasan';
            $csv_fields[] = 'keterangan';
            fputcsv($file, $csv_fields);

            foreach ($hasil->result() as $row) 
            {
                $branch_name = $row->branch_name;
                $subbranch = $row->subbranch;
                $no_pengajuan = $row->no_pengajuan;
                $kodeprod = $row->kodeprod;
                $namaprod = $row->namaprod;
                $batch_number = $row->batch_number;
                $expired_date = $row->expired_date;
                $jumlah = $row->jumlah;
                $satuan = $row->satuan;
                $nama_outlet = $row->nama_outlet;
                $alasan = $row->alasan;
                $keterangan = $row->keterangan;
                fputcsv($file, array($branch_name,$subbranch,$no_pengajuan,$kodeprod,$namaprod,$batch_number,$expired_date,$jumlah,$satuan,$nama_outlet,$alasan,$keterangan));
            }
        }elseif ($supp == '012') {

            $csv_fields=array();
            $csv_fields[] = 'branch_name';
            $csv_fields[] = 'subbranch';
            $csv_fields[] = 'no_pengajuan';
            $csv_fields[] = 'kodeprod';
            $csv_fields[] = 'namaprod';
            $csv_fields[] = 'total_karton';
            $csv_fields[] = 'total_dus';
            $csv_fields[] = 'total_pcs';
            $csv_fields[] = 'harga_karton';
            $csv_fields[] = 'harga_dus';
            $csv_fields[] = 'harga_pcs';
            $csv_fields[] = 'value';
            $csv_fields[] = 'kode_produksi';
            $csv_fields[] = 'keterangan';
            // $csv_fields[] = 'status';
            // $csv_fields[] = 'deskripsi';
            fputcsv($file, $csv_fields);

            foreach ($hasil->result() as $row) 
            {
                $branch_name = $row->branch_name;
                $subbranch = $row->subbranch;
                $no_pengajuan = $row->no_pengajuan;
                $kodeprod = $row->kodeprod;
                $namaprod = $row->namaprod;
                $total_karton = $row->total_karton;
                $total_dus = $row->total_dus;
                $total_pcs = $row->total_pcs;
                $harga_karton = $row->harga_karton;
                $harga_dus = $row->harga_dus;
                $harga_pcs = $row->harga_pcs;
                $value = $row->value;
                $kode_produksi = $row->kode_produksi;
                $keterangan = $row->keterangan;
                // $nama_status = $row->nama_status;
                // $deskripsi = $row->deskripsi;
                fputcsv($file, array($branch_name,$subbranch,$no_pengajuan,$kodeprod,$namaprod,$total_karton,$total_dus,$total_pcs,$harga_karton,$harga_dus,$harga_pcs,$value, $kode_produksi, $keterangan));
            }
        }

    }

    public function get_pengajuan_retur($id){
        $sql = "
        select 	a.no_pengajuan, a.site_code, a.nama, a.supp, a.tanggal_pengajuan, 
                a.file, a.status, a.created_date, a.created_by, b.branch_name, b.nama_comp,c.namasupp,
                d.company, a.file_principal, a.keterangan_principal, a.file_pengiriman, a.tanggal_kirim_barang, a.nama_ekspedisi, a.est_tanggal_tiba, a.tanggal_tiba, a.tanggal_approval, a.nama_status, a.file_terima, a.nama_penerima, a.no_terima, a.file_terima, a.tanggal_terima,
                a.tanggal_pemusnahan, a.nama_pemusnahan, a.file_pemusnahan, foto_pemusnahan_1, foto_pemusnahan_2
        from 	db_temp.t_temp_pengajuan_retur a LEFT JOIN
        (
            select concat(a.kode_comp, a.nocab) as kode, a.branch_name, a.nama_comp
            from mpm.tbl_tabcomp a
            where a.`status` = 1
            GROUP BY kode
        )b on a.site_code = b.kode LEFT JOIN mpm.tabsupp c 
        on a.supp = c.supp  LEFT JOIN 
        (
            select a.username, a.company
            from mpm.user a
        )d on left(a.site_code,3) = d.username
        where 	a.id = $id
        ";
        $proses = $this->db->query($sql)->result();
        if ($proses) {
            return $proses;
        }else{
            return array();
        }
    }

    public function get_email_dp($site_code){
        $sql = "
            select a.email
            from mpm.`user` a
            where a.username =left('$site_code',3)
        ";
        $proses = $this->db->query($sql)->result();
        if ($proses) {
            return $proses;
        }else{
            return array();
        }
    }

    public function get_email_principal($supp){
        $sql = "
            select a.email_retur as email
            from mpm.tabsupp a
            where a.supp = $supp
        ";
        $proses = $this->db->query($sql)->result();
        if ($proses) {
            return $proses;
        }else{
            return array();
        }
    }

    public function get_status_pengajuan($id){
        $sql = "
            select a.status, a.nama_status
            from db_temp.t_temp_pengajuan_retur a
            where a.id = $id
        ";
        $proses = $this->db->query($sql)->result();
        if ($proses) {
            return $proses;
        }else{
            return array();
        }
    }

    public function approval_produk_pengajuan($data,$id){
        $this->db->where('id', $id);
        $this->db->update('db_temp.t_temp_produk_pengajuan_retur', $data);

        //insert log
        $created_date = $this->model_outlet_transaksi->timezone();
        $userid = $this->session->userdata('id');
        $params = [
            'id_ref'        => $id,
            'created_date'  => $created_date,
            'created_by'    => $userid,
            'action'        => 'admin_verification'
        ];
        $this->db->insert('db_temp.t_temp_produk_pengajuan_retur_log',$params);
        return $this->db->affected_rows(); 
    }

    public function delete_produk_pengajuan_retur($data,$id){
        
        $this->db->where('id', $id);
        $this->db->update('db_temp.t_temp_produk_pengajuan_retur', $data); 

        //insert log
        $created_date = $this->model_outlet_transaksi->timezone();
        $userid = $this->session->userdata('id');
        $params = [
            'id_ref' => $id,
            'created_date' =>$created_date,
            'created_by' =>$userid,
            'action'    => 'user deleted'
        ];
        $this->db->insert('db_temp.t_temp_produk_pengajuan_retur_log',$params);
        return $this->db->affected_rows();         

    }

    public function generate_pdf_pengajuan_retur_principal(){
        $this->load->library('mypdf');

        // download($view, $data = array(), $filename = 'Laporan', $paper = 'A4', $orientation = 'portrait')
        $this->mypdf->download('laporan/dompdf');
    }
    
    public function update_pengajuan_retur($data,$id){
        $this->db->where('id', $id);
        $this->db->update('db_temp.t_temp_pengajuan_retur', $data);
        return $this->db->affected_rows();
    }

    public function insert_diskon($data){
   
        $this->db->select('*');
        $this->db->from('db_temp.t_temp_diskon_pengajuan_retur');
        $this->db->where('id_retur',$data['id_retur']);
        $row = $this->db->get()->num_rows();
        if ($row) {
            //jika ditemukan, lakukan update
            $this->db->set($data);
            $this->db->where('id_retur',$data['id_retur']);
            $this->db->update('db_temp.t_temp_diskon_pengajuan_retur');
            $this->db->trans_complete();
        }else{
            //jika tidak ditemukan, lakukan insert
            $this->db->insert('db_temp.t_temp_diskon_pengajuan_retur',$data);
            $this->db->trans_complete();
        }


        if ($this->db->trans_status() === FALSE)
        {
            return false;
        }else{
            return true;
        }

    }

    public function get_discount($id){
        $this->db->select('diskon,ppn');
        $this->db->from('db_temp.t_temp_diskon_pengajuan_retur');
        $this->db->where('id_retur',$id);
        
        $proses = $this->db->get()->result();
        if ($proses) {
            return $proses;
        }else{
            return array();
        }
        // return $this->db->get()->result();
    }

    public function get_total($id){
        $sql = "
            select 	round(sum(a.value)) as total
            from db_temp.t_temp_produk_pengajuan_retur a
            where a.id_pengajuan = $id and a.deleted is null
        ";
        return $this->db->query($sql)->result();
    }

    public function get_tableimport_product()
    {
        $id = $this->session->userdata('id');
        $signature = $this->uri->segment(3);
        $sql = "
            select a.id, a.id_pengajuan, a.kodeprod, a.batch_number, a.expired_date, sum(a.jumlah) as jumlah, 
                    a.alasan, a.satuan, a.nama_outlet, a.keterangan, b.namaprod
            from db_temp.t_temp_pengajuan_retur_import a
            LEFT JOIN mpm.tabprod b on a.kodeprod = b.kodeprod
            where a.signature = '$signature'
            group by a.kodeprod, a.batch_number, a.expired_date, a.nama_outlet
            order by a.kodeprod, a.batch_number
            
        ";
        return $this->db->query($sql);
    }

    public function get_tableimport_product_deltomed()
    {
        $id = $this->session->userdata('id');
        $signature = $this->uri->segment(3);
        $sql = "
            select a.id, a.id_pengajuan, a.kodeprod, a.batch_number, a.expired_date, sum(a.jumlah) as jumlah, a.alasan, a.satuan, a.nama_outlet, a.keterangan, b.namaprod
            from db_temp.t_temp_pengajuan_retur_import a
            LEFT JOIN mpm.tabprod b on a.kodeprod = b.kodeprod
            where a.signature = '$signature' and a.created_date = (
                select max(b.created_date)
                from db_temp.t_temp_pengajuan_retur_import b
                where b.signature = '$signature' 
            ) 
            group by a.kodeprod, a.batch_number, a.expired_date
            order by a.kodeprod, a.batch_number
            
        ";
        return $this->db->query($sql);
    }
    
    public function get_dataimport_product()
    {
        $id = $this->session->userdata('id');
        $signature = $this->uri->segment(3);
        $sql = "
            select a.*, b.namaprod
            from db_temp.t_temp_pengajuan_retur_import a
            LEFT JOIN mpm.tabprod b on a.kodeprod = b.kodeprod
            where a.created_by = $id and a.signature = '$signature'
        ";
        return $this->db->query($sql);
    }

    public function delete_retur($signature){
        $sql = "
            update db_temp.t_temp_pengajuan_retur a 
            set a.deleted = 1
            where a.signature = '$signature'
        ";
        $deleted = $this->db->query($sql);
        if ($deleted) {
            redirect('retur/pengajuan');
        }
    }
    
}