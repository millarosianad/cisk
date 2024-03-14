<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_asset extends CI_Model 
{   
    public function getGrupassetcombo()
    {
        $sql='select id,namagrup from mpm.grupasset';
        return $this->db->query($sql);
    }

    public function getUser()
    {
        $sql='
            select id,username, email from mpm.user
            where level not in (4,5,6) and supp = 000 and active = 1
            order by username
            ';
        return $this->db->query($sql);
    }

    public function getPengajuan()
    {
        $f=strtotime("-6 Months");
        $tglawal=date("Y-m-d", $f);
        $tglakhir = date("Y-m-d");
        $sql="
            select a.id,a.no_po, a.user_req, b.username 
            from db_temp.t_temp_pengajuan_asset a LEFT JOIN mpm.`user` b
            on a.user_req = b.id
            where (tgl_po >= '$tglawal' and tgl_po <= '$tglakhir')
            ";
        return $this->db->query($sql);
    }

    public function getAssets_sds($kode)
    {   
        $nv = $kode['nv'];
        if ($nv == '' ){
            $no_voucher = '';
            $no_voucherx = '';
            $no_jurnal = '';
            $no_jurnalx = '';
        }else{
            $no_voucher = "where nick_voucher like '%$nv%'";
            $no_voucher = "and nick_voucher like '%$nv%'";
            $no_jurnal = "where nojurnal like '%$nv%'";
            $no_jurnalx = "and nojurnal like '%$nv%'";
        }
        $from = $kode['from'];
        $to = $kode['to'];
        if ($from > $to) {
            redirect('assets_2/view_assets');
        }
        
        $serverName = "backup.muliaputramandiri.com"; //serverName\instanceName, portNumber (default is 1433)
        $connectionInfo = array("Database" => "", "UID" => "sa", "PWD" => "mpm12345");
        $conn = sqlsrv_connect($serverName, $connectionInfo);

        // echo "<pre><br><br><br><br><br>";
        // print_r($nv);
        // print_r($from);
        // print_r($to);
        // echo "</pre>";
        $dt = $kode['dt'];
        if ($dt == '1' ){
            if ($conn) {
                echo "<script>
                alert('Koneksi dengan Server SDS Berhasil');
                </script>";
                $sql1 = "
                        SELECT	a.*
                        FROM    dbsls.dbo.t_gl_kas_detail a
                        WHERE   a.coa_id in ('1120000110','1120000120','1120000130','1120000140','1120000150','1120000160', '1120000170') and  (a.tgl_entry >= '$from' and a.tgl_entry <= '$to')
                ";
    
                $query = sqlsrv_query($conn, $sql1);
                
                $this->db->query('truncate db_temp.t_temp_asset_sds_kas');
    
                if ($query) {
                    while ($data = sqlsrv_fetch_array($query)){
                        $data = array(
                                        'siteid'                => $data['siteid'],
                                        'no_voucher'            => $data['no_voucher'],
                                        'nourut'                => $data['nourut'],
                                        'coa_id'                => $data['coa_id'],
                                        'cos_description'       => $data['cos_description'],
                                        'tipe_trans'            => $data['tipe_trans'],
                                        'tipe_bukti'            => $data['tipe_bukti'],
                                        'tipe_kas'              => $data['tipe_kas'],
                                        'tgl_trans'             => $data['tgl_trans']->format('Y-m-d H:i:s'),
                                        'debet'                 => $data['debet'],
                                        'kredit'                => $data['kredit'],
                                        'keterangan'            => $data['keterangan'],
                                        'currency_id'           => $data['currency_id'],
                                        'userid'                => $data['userid'],
                                        'tgl_entry'             => $data['tgl_entry']->format('Y-m-d H:i:s'),
                                        'nick_voucher'          => $data['nick_voucher'],
                                    );
    
                                    $this->db->insert('db_temp.t_temp_asset_sds_kas',$data);
                                }
                                
                                    $sql = "
                                        SELECT	*, concat(nourut,nick_voucher) as no_urut
                                        FROM    db_temp.t_temp_asset_sds_kas
                                        $no_voucher";
    
                                    $hasil = $this->db->query($sql);
                                
                                // echo "<pre><br><br><br><br><br>";
                                // print_r($sql);
                                // echo "</pre>";
                            }
            
            }else{
                echo 
                "<script>
                alert('Koneksi dengan Server SDS Gagal. Web akan mengambil data secara lokal. Mungkin data tidak sama dengan sds');
                </script>";
    
                $sql = "
                        SELECT	*, concat(nourut,nick_voucher) as no_urut
                        FROM    dbsls.t_gl_kas_detail a
                        WHERE   a.coa_id in ('1120000110','1120000120','1120000130','1120000140','1120000150','1120000160', '1120000170') $no_voucherx and  (a.tgl_entry >= '$from' and a.tgl_entry <= '$to')";
    
                $hasil = $this->db->query($sql);
            }
        }elseif ($dt == '2' ){
            if ($conn) {
                echo "<script>
                alert('Koneksi dengan Server SDS Berhasil');
                </script>";
                $sql1 = "
                        SELECT	a.*
                        FROM    dbsls.dbo.t_gl_jurnal a
                        WHERE   a.coa_id in ('1120000110','1120000120','1120000130','1120000140','1120000150','1120000160', '1120000170') and  (a.tgl_entry >= '$from' and a.tgl_entry <= '$to')
                ";
    
                $query = sqlsrv_query($conn, $sql1);
                
                $this->db->query('truncate db_temp.t_temp_asset_sds_jurnal');
    
                if ($query) {
                    while ($data = sqlsrv_fetch_array($query)){
                        $data = array(
                                        'siteid'        =>$data['siteid'],
                                        'nojurnal'      =>$data['nojurnal'],
                                        'coa_id'        =>$data['coa_id'],
                                        'nourut'        =>$data['nourut'],
                                        'description'   =>$data['description'],
                                        'tgl_trans'     =>$data['tgl_trans']->format('Y-m-d H:i:s'),
                                        'debet'         =>$data['debet'],
                                        'kredit'        =>$data['kredit'],
                                        'keterangan'    =>$data['keterangan'],
                                        'currency_id'   =>$data['currency_id'],
                                        'rate_currency' =>$data['rate_currency'],
                                        'group_saldo'   =>$data['group_saldo'],
                                        'tgl_entry'     =>$data['tgl_entry']->format('Y-m-d H:i:s'),
                                        'userid'        =>$data['userid'],
                                        'flag_jurnal'   =>$data['flag_jurnal']
                                    );
    
                                     $this->db->insert('db_temp.t_temp_asset_sds_jurnal',$data);
                                }
                                
                                    $sql = "
                                        SELECT	*, concat(nourut,nick_voucher) as no_urut
                                        FROM    db_temp.t_temp_asset_sds_jurnal
                                        $no_jurnal";
    
                                    $hasil = $this->db->query($sql);
                               
                                // echo "<pre><br><br><br><br><br>";
                                // print_r($sql);
                                // echo "</pre>";
                            }
             
            }else{
                echo 
                "<script>
                alert('Koneksi dengan Server SDS Gagal. Web akan mengambil data secara lokal. Mungkin data tidak sama dengan sds');
                </script>";
    
                $sql = "
                        SELECT	*, concat(nourut,nick_voucher) as no_urut
                        FROM    dbsls.t_gl_jurnal a
                        WHERE   a.coa_id in ('1120000110','1120000120','1120000130','1120000140','1120000150','1120000160', '1120000170') $no_jurnalx and  (a.tgl_entry >= '$from' and a.tgl_entry <= '$to')";
    
                $hasil = $this->db->query($sql);
            }
        }

        if ($hasil->num_rows() > 0) 
        {
            return $hasil->result();
        } else {
            return array();
        }
    }

    public function getAsset_mutasi($id)
    {
        $this->db->select('*');
        $this->db->where('id', $id);
        $proses =  $this->db->get('mpm.asset_mutasi');
        return $proses;
    }

    public function my_asset(){
        $id = $this->session->userdata('id');
        $sql = "
            SELECT a.*, c.id as id_mutasi, c.tgl_mutasi, c.userid, c.bukti_upload, c.bukti_upload2, c.status
            from 
            (
                select *
                from mpm.asset a
                where a.deleted = 0
            )a
            LEFT JOIN
            (
                select a.id, a.id_asset, a.userid, a.tgl_mutasi, a.bukti_upload, a.bukti_upload2, a.status
                from mpm.asset_mutasi a
                where a.userid = $id and a.tgl_mutasi = (
                    select max(b.tgl_mutasi)
                    from mpm.asset_mutasi b
                    where b.userid = $id
                )
            ) c on a.id = c.id_asset and a.userid = c.userid
            where a.userid = $id and status = '2'
                ";
        $hasil = $this->db->query($sql);
        // echo "<pre><br><br><br><br>";
        // print_r($sql);
        // echo "</pre>";
        if ($hasil->num_rows() > 0) 
        {
            return $hasil->result();
        } else {
            return array();
        }
        //  echo "<pre><br><br><br><br><br>";
        //                     print_r($sql);
        //                     echo "</pre>";
    }

    public function konfirmasi_asset(){
        $id = $this->session->userdata('id');
        $sql = "
            SELECT a.*, c.id as id_mutasi, c.tgl_mutasi, c.userid, c.bukti_upload, c.bukti_upload2, c.status
            from 
                    (
                        select *
                        from mpm.asset a
                        where a.deleted = 0
                    )a
                    LEFT JOIN
                    (
                        select a.id, a.id_asset, a.userid, a.tgl_mutasi, a.bukti_upload, a.bukti_upload2, a.status
                        from mpm.asset_mutasi a
                    ) c on a.id = c.id_asset
            where c.userid = $id
                ";
        $hasil = $this->db->query($sql);
        if ($hasil->num_rows() > 0) 
        {
            return $hasil->result();
        } else {
            return array();
        }
        //  echo "<pre><br><br><br><br><br>";
        //                     print_r($sql);
        //                     echo "</pre>";
    }

    public function save_konfirmasi_asset($data){
        $id=$this->session->userdata('id');
        $id_asset = $data['id_asset'];
        $id_mutasi = $data['id_mutasi'];

    	$sql = "
            Update mpm.asset_mutasi a
            set a.status = '2'
            where id = $id_mutasi
        ";

        $update_mutasi = $this->db->query($sql);

        $sql2 = "
                update mpm.asset a
                set a.username = (
                    select username from mpm.user
                    where id = (
                        SELECT userid FROM mpm.asset_mutasi
                        WHERE tgl_mutasi = (SELECT MAX(tgl_mutasi) FROM mpm.asset_mutasi WHERE id_asset = $id_asset)
                        and id_asset = $id_asset and userid = $id
                    )
                ),

                a.userid = (
                    SELECT userid FROM mpm.asset_mutasi
                    WHERE tgl_mutasi = (
                        SELECT MAX(tgl_mutasi) FROM mpm.asset_mutasi 
                        WHERE id_asset = $id_asset) and id_asset = $id_asset and userid = $id
                )
                where a.id = $id_asset 
            ";

        $update = $this->db->query($sql2);
        /*
            echo "<pre>";
            print_r($query);
            echo "</pre>";
        */
    }

    public function view_asset($id_asset = ''){
        if ($id_asset == '') {
            $id_assets = '';
        }else{
            $id_assets = "and id = $id_asset";
        }

        $sql = "
                SELECT a.*, b.namagrup
                from 
                    (
                        select a.*
                        from mpm.asset a
                        where a.deleted < 1 $id_assets
                    )a
                    LEFT JOIN
                    (
                        select a.id, a.namagrup
                        from mpm.grupasset a
                    )b on a.grupid = b.id
                order by id desc
                ";
        $hasil = $this->db->query($sql);
        if ($hasil->num_rows() > 0) 
        {
            return $hasil;
        } else {
            return array();
        }
                
            /* END PROSES TAMPIL KE WEBSITE */
      
    }

    public function input($table, $data){

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
        $this->db->where('id', $data['id']);
        return $this->db->delete($table, $data); 
    }
    
    public function history_asset(){

        $id = $this->uri->segment(3);
        $sql = "
                SELECT a.id, b.id as id_mutasi, a.namabarang, b.`userid`, b.tgl_mutasi, b.alasan_mutasi, b.alasan_approve, b.bukti_upload,  b.bukti_upload2, b.status, c.username, c.email
                from 
                    (
                        select a.id, a.namabarang
                        from mpm.asset a
                    )a
                     INNER JOIN
                    (
                        select a.id, a.id_asset, a.`userid`, a.alasan_mutasi, a.alasan_approve, a.tgl_mutasi, a.bukti_upload, a.bukti_upload2, a.status
                        from mpm.asset_mutasi a
                        where id_asset = $id
                    ) b on a.id = b.id_asset
                    left join
                    (
                        select id, username, email
                        from mpm.user
                    )c on b.userid = c.id
                where a.id = $id 
                order by b.tgl_mutasi desc
                ";
        $hasil = $this->db->query($sql);
        if ($hasil->num_rows() > 0) 
        {
            return $hasil->result();
        } else {
            return array();
        }
    }

    public function proses_delete_mutasi($id_mutasi){
    	
    	$this->db->query("
        delete from mpm.asset_mutasi
        where id = $id_mutasi");
    }

// ===================================PENGAJUAN ASSETS=========================================

    public function showbarang(){
        if ( function_exists( 'date_default_timezone_set' ) )
        date_default_timezone_set('Asia/Jakarta');
        $id=$this->session->userdata('id');
        $hasil = $this->db->query("select * from db_temp.t_temp_pengajuan_asset_temp where created_by = $id ");
        if ($hasil->num_rows() > 0) 
        {
            return $hasil->result();
        } else {
            return array();
        }
        
    }

    public function input_barang_pengajuan() {
        if ( function_exists( 'date_default_timezone_set' ) )
        date_default_timezone_set('Asia/Jakarta');
        $id=$this->session->userdata('id');
        $tax = $this->input->post('tax');
        $jumlah = $this->input->post('jb');
        $harga =$this->input->post('harga');
        $sub_harga =  $jumlah*$harga;
        $sub_tax = $sub_harga*$tax/100;

        $post['nama_barang']= $this->input->post('nb');
        $post['jumlah']= $this->input->post('jb');
        $post['harga']= $this->input->post('harga');
        $post['tax']= $sub_tax;
        $post['created_by']= $id;
        $post['tipe']= $this->input->post('tipe');
        $post['created_date']=date('Y-m-d h:i:s');
        $this->db->insert('db_temp.t_temp_pengajuan_asset_temp',$post);

        redirect('assets_2/pengajuan_assets/');
    }

    public function delete_barang_pengajuan() {
        $id = $this->uri->segment('3');
        $hasil = $this->db->query("Delete From db_temp.t_temp_pengajuan_asset_temp where `id` = $id ");
        
        redirect('assets_2/pengajuan_assets/');
    }

    public function save_pengajuan($data) {
        if ( function_exists( 'date_default_timezone_set' ) )
        date_default_timezone_set('Asia/Jakarta');
        $id=$this->session->userdata('id');
        $upload = $data['upload'];
        $no_po = $this->input->post('np');    
        $hasil = $this->db->query("select * from db_temp.t_temp_pengajuan_asset where no_po ='$no_po'");
        
        if ($hasil->num_rows() > 0) 
        {
            echo "Input Gagal, Nomer PO sudah digunakan silahkan coba lagi !!";
            
        } else {
                $post['no_po']= $no_po;
                $post['nama_toko']= $this->input->post('nt');
                $post['alamat']= $this->input->post('alamat');
                $post['telp']= $this->input->post('telp');
                $post['fax']= $this->input->post('fax');
                $post['attn']= $this->input->post('attn');
                $post['tgl_po']=$this->input->post('tgl');
                $post['user_req']=$this->input->post('user_req');
                $post['upload_req']= $upload;
                $post['created_date']=date('Y-m-d h:i:s');
                $post['created_by']= $id;
                $this->db->insert('db_temp.t_temp_pengajuan_asset',$post);

                $created_date = date('Y-m-d h:i:s');

                $sql =  "
                        INSERT INTO db_temp.t_temp_pengajuan_asset_detail
                        SELECT a.no_po, b.nama_barang, b.tipe, b.jumlah, b.harga, b.sub_harga, b.tax,  $id, '$created_date'
                        FROM
                            (	
                                SELECT no_po, created_by
                                FROM db_temp.t_temp_pengajuan_asset
                                WHERE created_by = $id and created_date =(SELECT MAX(created_date) as created_date from db_temp.t_temp_pengajuan_asset WHERE created_by = $id) )a
                                LEFT JOIN
                            (
                                SELECT nama_barang, tipe, jumlah, harga,(jumlah*harga) as sub_harga, tax, created_by
                                from db_temp.t_temp_pengajuan_asset_temp
                                WHERE created_by = $id)b
                        on a.created_by = b.created_by
            
                        ";
                $this->db->query($sql);

                $this->db->where('created_by', $id)
                        ->delete('db_temp.t_temp_pengajuan_asset_temp');
                        
                redirect('assets_2/view_pengajuan/');
        }
    }

    public function view_pengajuan(){
        if ( function_exists( 'date_default_timezone_set' ) )
        date_default_timezone_set('Asia/Jakarta');
        $id=$this->session->userdata('id');
        $sql = "
                SELECT a.no_po, a. nama_toko, a.alamat, a.telp, a.tgl_po, a.upload_req, b.sub_harga, b.sub_tax, b.total, b.created_date
                FROM
                            (	SELECT a.no_po, a.nama_toko, a.alamat, a.upload_req, a.tgl_po, a.telp
                                FROM db_temp.t_temp_pengajuan_asset a)a
                            LEFT JOIN
                            (	SELECT no_po, nama_barang, sum(sub_harga) as sub_harga, sum(tax) as sub_tax, SUM(sub_harga+tax) as total, created_date
                                FROM db_temp.t_temp_pengajuan_asset_detail
                                GROUP BY no_po)b
                            on a.no_po = b.no_po
                ORDER BY b.created_date desc
                ";
        $hasil = $this->db->query($sql);
        if ($hasil->num_rows() > 0) 
        {
            return $hasil->result();
        } else {
            return array();
        }
    }
    
}