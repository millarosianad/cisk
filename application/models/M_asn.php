<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_asn extends CI_Model 
{   
    public function get_po(){
        $supp = $this->session->userdata('supp');
        if($supp == 000){
            // $suppx = '';
            $suppx = "a.supp = '012' and";
        }else{
            $suppx = "a.supp = '$supp' and";
        }
        // $f=strtotime("-1 Months");
        // $from=date("Y-m-d", $f);
        // $to=date("Y-m-d");
        $thn = date('Y');
        $bln = date('m');


        $sql = "
        select 	d.branch_name,d.nama_comp,a.id, DATE_FORMAT(a.tglpo,'%Y-%m-%d') as tglpo,a.nopo, a.company, a.tipe,
                a.alamat,b.kodeprod,b.namaprod,sum(b.banyak) as total_unit,sum(b.banyak_karton) as total_karton,sum(round((b.berat*b.banyak_karton))) as total_berat, e.total_produk_po,e.total_produk_asn,e.total_unit_po,e.total_unit_asn,e.total_karton_po,e.total_karton_asn, f.status_closed,
                replace(a.nopo,'/','_') as nopox
        from mpm.po a INNER JOIN 
        (
            select a.id_ref, a.kodeprod, a.banyak, a.banyak_karton, a.berat, a.namaprod
            from mpm.po_detail a
            where a.deleted = 0
        )b on a.id = b.id_ref LEFT JOIN
        (
            select a.id, a.username
            from mpm.`user` a
        )c on a.userid = c.id LEFT JOIN
        (
            select a.kode,a.branch_name,a.nama_comp,a.kode_comp
            from
            (
                select concat(a.kode_comp,a.nocab) as kode, a.branch_name, a.nama_comp, a.kode_comp
                from mpm.tbl_tabcomp a
                where a.`status` = 1
                GROUP BY kode
            )a INNER JOIN
            (
                select concat(a.kode_comp,a.nocab) as kode
                from db_dp.t_dp a
                where tahun = $thn and a.`status` = 1
            )b on a.kode = b.kode
            GROUP BY kode_comp
        )d on c.username = d.kode_comp LEFT JOIN
        (
            select a.id,a.id_po,a.total_unit_po,a.total_unit_asn,a.total_produk_po,a.total_produk_asn, a.total_karton_po, a.total_karton_asn
            from mpm.t_cek_asn a
            where a.id = (
                select max(b.id)
                from mpm.t_cek_asn b
                where a.id_po = b.id_po
            )
        )e on a.id = e.id_po LEFT JOIN
        (
            select a.id_po,a.status_closed
            from mpm.t_asn a
            GROUP BY a.id_po
        )f on a.id = f.id_po
        where $suppx a.deleted = 0 and a.nopo not like '/mpm%' and a.nopo not like '%batal%' and
        ((
            year(a.tglpesan) in (year(date(now()))) and month(a.tglpesan) in (month(date(now())))
        ) or
        (
            year(a.tglpesan) in (date_format(date(now()) - INTERVAL '1' MONTH,'%Y')) and 
            month(a.tglpesan) in (date_format(date(now()) - INTERVAL '1' MONTH,'%m'))
        ))
        GROUP BY a.id
        ORDER BY a.id DESC
        
        ";
        
        // echo "<pre>";
        // echo "<br><br><br><br><br>";
        // print_r($sql);
        // echo "</pre>";

        $proses = $this->db->query($sql)->result();
        return $proses;
    }

    public function get_po_by_produk_asn($id_po, $kodeprod = '', $id_asn = ''){

        // if ($kodeprod == '') {
        //     $kodeprod_params = "";
        // }else{
        //     $kodeprod_params = "and b.kodeprod = '$kodeprod'";
        // }

        // $retVal = (condition) ? a : b ;

        $kodeprod_params = ($kodeprod == '') ? "" : "and b.kodeprod = '$kodeprod'" ;
        $id_asn_params = ($id_asn == '') ? "" : "where a.id = '$id_asn'" ;

        $sql= "
        select 	a.id, a.nopo, date_format(a.tglpo,'%d/%m/%Y') as tglpo, 
				b.kodeprod, b.namaprod, b.banyak, b.banyak_karton, 
                c.no_asn, c.jumlah_unit, c.jumlah_karton, c.tanggal_kirim, 
                c.nama_ekspedisi, c.est_lead_time, c.tanggal_tiba, c.status_closed, c.batch_number, c.keterangan, c.status_pemenuhan, c.id_asn, a.supp, c.nodo, c.ed
        from    mpm.po a INNER JOIN 
        (
            select 	a.id_ref, a.kodeprod, a.banyak, a.banyak_karton, a.berat, a.namaprod
            from	mpm.po_detail a
            where 	a.deleted = 0 and a.id_ref = $id_po
        )b on a.id = b.id_ref left JOIN
        (
            select 	a.id as id_asn, a.id_po, a.no_asn, a.kodeprod, a.tanggal_kirim, a.est_lead_time, a.nama_ekspedisi, a.jumlah_unit, a.jumlah_karton, a.tanggal_tiba, a.status_closed, batch_number, keterangan, a.status_pemenuhan, a.nodo, a.ed
            from 	mpm.t_asn a
            $id_asn_params
        )c on a.id = c.id_po and b.kodeprod = c.kodeprod
        where 	a.id = $id_po and a.deleted = 0 $kodeprod_params
        ";

        // echo "<pre>";
        // echo "<br><br><br>";
        // print_r($sql);
        // echo "</pre>";

        $proses = $this->db->query($sql)->result();
        return $proses;
    }

    public function get_table_produk_asn($id_po){
        $sql = "
        select 	a.id, a.supp, c.id_asn, a.nopo, date_format(a.tglpo,'%d/%m/%Y') as tglpo, b.kodeprod, b.namaprod, b.banyak, b.banyak_karton,
                c.no_asn, c.kodeprod, c.jumlah_unit, c.jumlah_karton, c.tanggal_kirim, c.nama_ekspedisi, c.est_lead_time, 
                c.tanggal_tiba, c.keterangan, c.status_pemenuhan, c.batch_number, c.signature, c.id_asn, c.nodo, date_format(c.ed,'%d/%m/%Y') as ed
        from mpm.po a INNER JOIN mpm.po_detail b 
            on a.id = b.id_ref INNER JOIN
        (
            select a.id as id_asn, a.id_po, a.no_asn, a.kodeprod, a.tanggal_kirim, a.est_lead_time,
            a.nama_ekspedisi, a.jumlah_unit, a.jumlah_karton,a.tanggal_tiba, a.keterangan, a.status_pemenuhan, a.batch_number, a.signature, a.nodo, a.ed
            from mpm.t_asn a
        )c on a.id = c.id_po and b.kodeprod = c.kodeprod
        where a.id = $id_po and a.deleted = 0 and b.deleted = 0
        ";

        // echo "<pre>";
        // print_r($sql);
        // echo "</pre>";

        $proses = $this->db->query($sql)->result();
        return $proses;
    }

    public function insert($table,$data){
        $insert = $this->db->insert($table,$data);
        if ($insert) {
            return $this->db->insert_id();
        }else{
            return array();
        }
    }

    public function insert_cek_asn($data){
        $insert = $this->db->insert('mpm.t_cek_asn',$data);
        if ($insert) {
            return $this->db->insert_id();
        }else{
            return array();
        }
    }

    public function proses_tambah_asn($data){
        
        $id = $this->session->userdata('id');
        $id_po = $data['id_po'];
        // $no_asn = $data['no_asn'];
        $no_asn = '';
        $kodeprod = $data['kodeprod'];
        $keterangan = $data['keterangan'];        
        $jumlah_karton = $data['jumlah_karton'];
        $nama_ekspedisi = $data['nama_ekspedisi'];
        $tanggal_tiba = $data['tanggal_tiba'];        
        $created_date = date('Y-m-d H:i:s');
        $status_pemenuhan = $data['status_pemenuhan'];
        // echo "status_pemenuhan : ".$status_pemenuhan;
        if ($status_pemenuhan == null) {
            $params_status_pemenuhan = 0;
        }else{
            $params_status_pemenuhan = $status_pemenuhan;
        }
        
        $tanggal_kirim = trim($data['tanggal_kirim']);        
        if ($tanggal_kirim == '') {
            $convert_tanggal_kirim='';
        }else{
            $convert_tanggal_kirim=strftime('%Y-%m-%d',strtotime($tanggal_kirim));
        }      

        $tanggal_tiba = trim($data['tanggal_tiba']);
        if ($tanggal_tiba == '') {
            $convert_tanggal_tiba='';
        }else{
            $convert_tanggal_tiba=strftime('%Y-%m-%d',strtotime($tanggal_tiba));
        }

        // echo "<pre>";
        // echo "id_po : ".$id_po;
        // echo "asn_kodeprod : ".$asn_kodeprod;
        // echo "convert_asn_tanggalKirim : ".$convert_asn_tanggalKirim;
        // echo "asn_unit : ".$asn_unit;
        // echo "asn_nama_expedisi : ".$asn_nama_expedisi;
        // echo "asn_est_lead_time : ".$asn_est_lead_time;
        // echo "convert_asn_eta : ".$convert_asn_eta;
        // echo "</pre>";

        $sql="
            INSERT INTO mpm.t_asn
            SELECT '' as id, $id_po, '' as no_asn, '$kodeprod', '' as jumlah_unit, $jumlah_karton, $params_status_pemenuhan,'$convert_tanggal_kirim', '$nama_ekspedisi',
            datediff('$convert_tanggal_tiba',  '$convert_tanggal_kirim') as est_lead_time, '$convert_tanggal_tiba', '$keterangan','' as status_closed,
            '$created_date' as created_date, $id, '$created_date' as last_updated, $id as last_updated_by
        ";
        $proses = $this->db->query($sql);

        $get_id_asn = $this->db->query("select id from mpm.t_asn where created_date = '$created_date'")->result();
        foreach ($get_id_asn as $key) {
            $id_asn = $key->id;
        }
        
        foreach ($this->get_total_unit_po($id_po) as $key) {
            $total_unit_po = $key->total_unit;
            $total_karton_po = $key->total_karton;
            $total_produk_po = $key->total_produk;
        }

        foreach ($this->get_total_unit_asn($id_po) as $key) {
            $total_unit_asn = $key->total_unit_asn;
            $total_karton_asn = $key->total_karton_asn;
            $total_produk_asn = $key->total_produk_asn;
        }      

        $insert_cek_asn = "
            insert into mpm.t_cek_asn
            select '' as id, $id_asn, $id_po, $total_unit_po, $total_unit_asn, $total_karton_po, $total_karton_asn, $total_produk_po,$total_produk_asn, 1
        ";
        $proses_insert_cek_asn = $this->db->query($insert_cek_asn);

        // print_r($insert_cek_asn);

        if ($proses_insert_cek_asn) {
            redirect("asn/input_asn/$id_po");
        }else{
            return false;
        }
    }

    public function edit_produk_asn($id_po,$id_asn){
        
        $sql = "
        select 	a.id, c.id_asn, a.nopo, date_format(a.tglpo,'%d/%m/%Y') as tglpo, b.kodeprod, b.namaprod, b.banyak, b.banyak_karton,
				c.no_asn, c.jumlah_unit,c.jumlah_karton, c.tanggal_kirim, c.nama_ekspedisi, c.est_lead_time,
				c.tanggal_tiba, c.keterangan,c.status_pemenuhan
        from mpm.po a INNER JOIN mpm.po_detail b 
                on a.id = b.id_ref INNER JOIN
        (
            select a.id as id_asn, a.id_po, a.no_asn, a.kodeprod, a.tanggal_kirim, a.est_lead_time,
            a.nama_ekspedisi, a.jumlah_unit, a.jumlah_karton, a.tanggal_tiba, a.keterangan, a.status_pemenuhan
            from mpm.t_asn a
        )c on a.id = c.id_po and b.kodeprod = c.kodeprod
        where c.id_asn = $id_asn and a.deleted = 0 and b.deleted = 0 and a.id = $id_po
        ";

        // echo "<pre>";
        // echo "<br><br><br>";
        // print_r($sql);
        // echo "</pre>";

        $proses = $this->db->query($sql)->result();
        return $proses;
    }

    public function get_total_unit_po($id_po){
        $sql = "
        select sum(a.banyak) as total_unit, sum(a.banyak_karton) as total_karton, count(kodeprod) as total_produk
        from mpm.po_detail a
        where a.id_ref = $id_po and a.deleted = 0";

        // print_r($sql);

        $proses = $this->db->query($sql)->result();
        return $proses;
    }

    public function get_total_unit_asn($id_po){
        $sql = "
        select if(sum(a.jumlah_unit) is null,0,sum(a.jumlah_unit)) as total_unit_asn, sum(a.jumlah_karton) as total_karton_asn, count(a.kodeprod) as total_produk_asn
        from mpm.t_asn a
        where a.id_po = $id_po";

        // print_r($sql);

        $proses = $this->db->query($sql)->result();
        return $proses;
    }

    public function update_asn($table, $data,$id){
        $this->db->where('id',$id);
        $update = $this->db->update($table,$data);
        if ($update) {
            return true;
        }else{
            return false;
        }
    }

    public function update_cek_asn($data){
        $insert = $this->db->insert('mpm.t_cek_asn',$data);
        if ($insert) {
            return $this->db->insert_id();
        }else{
            return array();
        }
    }

    public function proses_edit_asn($data){
        $id = $this->session->userdata('id');
        $id_po = $data['id_po'];
        $id_asn = $data['id_asn'];
        // $no_asn = $data['no_asn'];
        $kodeprod = $data['kodeprod'];
        $keterangan = $data['keterangan'];
        $status_pemenuhan = $data['status_pemenuhan'];

        // echo "status_pemenuhan : ".$status_pemenuhan;
        if ($status_pemenuhan == null) {
            $params_status_pemenuhan = 0;
        }else{
            $params_status_pemenuhan = $status_pemenuhan;
        }
        
        $tanggal_kirim = trim($data['tanggal_kirim']);
        if ($tanggal_kirim == '') {
            $convert_tanggal_kirim='';
        }else{
            $convert_tanggal_kirim=strftime('%Y-%m-%d',strtotime($tanggal_kirim));
        }

        $jumlah_karton = $data['jumlah_karton'];
        $nama_ekspedisi = $data['nama_ekspedisi'];
        $est_lead_time = $data['est_lead_time'];
        $tanggal_tiba = $data['tanggal_tiba'];

        $tanggal_tiba = trim($data['tanggal_tiba']);
        if ($tanggal_tiba == '') {
            $convert_tanggal_tiba='';
        }else{
            $convert_tanggal_tiba=strftime('%Y-%m-%d',strtotime($tanggal_tiba));
        }
        $last_updated = date('Y-m-d H:i:s');

        // echo "<pre>";
        // echo "id_po : ".$id_po;
        // echo "no_asn : ".$id_asn;
        // echo "asn_kodeprod : ".$asn_kodeprod;
        // echo "convert_asn_tanggalKirim : ".$convert_asn_tanggalKirim;
        // echo "asn_unit : ".$asn_unit;
        // echo "asn_nama_expedisi : ".$asn_nama_expedisi;
        // echo "asn_est_lead_time : ".$asn_est_lead_time;
        // echo "convert_asn_eta : ".$convert_asn_eta;
        // echo "</pre>";
        
        $sql="
            UPDATE mpm.t_asn
            set jumlah_karton = '$jumlah_karton', tanggal_kirim = '$convert_tanggal_kirim', nama_ekspedisi = '$nama_ekspedisi',
            est_lead_time = datediff('$convert_tanggal_tiba',  '$convert_tanggal_kirim'), tanggal_tiba = '$convert_tanggal_tiba', keterangan = '$keterangan', last_updated = '$last_updated' , last_updated_by = $id, status_pemenuhan = $params_status_pemenuhan
            WHERE id= $id_asn
        ";
        $proses = $this->db->query($sql);

        // $get_id_asn = $this->db->query("select id from mpm.t_asn where created_date = '$created_date'")->result();
        // foreach ($get_id_asn as $key) {
        //     $id_asn = $key->id;
        // }
        
        foreach ($this->get_total_unit_po($id_po) as $key) {
            $total_unit_po = $key->total_unit;
            $total_karton_po = $key->total_karton;
            $total_produk_po = $key->total_produk;
        }

        foreach ($this->get_total_unit_asn($id_po) as $key) {
            $total_unit_asn = $key->total_unit_asn;
            $total_karton_asn = $key->total_karton_asn;
            $total_produk_asn = $key->total_produk_asn;
        }      

        $insert_cek_asn = "
            insert into mpm.t_cek_asn
            select '' as id, $id_asn, $id_po, $total_unit_po, $total_unit_asn, $total_karton_po, $total_karton_asn, $total_produk_po,$total_produk_asn, 1
        ";
        $proses_insert_cek_asn = $this->db->query($insert_cek_asn);

        if ($proses_insert_cek_asn) {
            redirect("asn/input_asn/$id_po");
        }else{
            return false;
        }
    }

    public function delete_produk_asn($id_po,$id_asn) {
        $hasil = $this->db->query("delete From mpm.t_asn where `id` = $id_asn ");
        if ($hasil) {
            return true;
        }else{
            return false;
        }
        
    }

    public function closed_asn($id_po) {
        $sql = "
            update mpm.t_asn a 
            set a.status_closed = 1
            where a.id_po = $id_po
        ";
        $hasil = $this->db->query($sql);

        //jalankan fungsi generate csv
        $this->generate_asn($id_po);
        // jalankan fungsi kirim email
        $this->email_asn($id_po);

        // if ($hasil) {
        //     redirect("asn/list_asn");
        // }else{
        //     return false;
        // }
        
    }

    public function email_dp($id_po){
        $sql = "
            select email,company,supp from mpm.po where id = $id_po
        ";
        $proses = $this->db->query($sql)->result();
        return $proses;
    } 
    public function email_principal($supp){
        $sql = "
        select email,NAMASUPP as namasupp
        from mpm.tabsupp where supp = $supp
        ";
        $proses = $this->db->query($sql)->result();
        return $proses;
    } 

    public function email_asn($id_po){

        $get_email_dp = $this->email_dp($id_po);
        foreach ($get_email_dp as $key) {
            // $email_dp = $key->email.",suffy.mpm@gmail.com";
            $email_dp = $key->email;
            $company = $key->company;
            $supp = $key->supp;
        }

        $get_email_principal = $this->email_principal($supp);
        foreach ($get_email_principal as $key) {
            $email_principal = $key->email;
            $namasupp = $key->namasupp;
        }

        $from = "suffy.yanuar@gmail.com";
        // $to = $email_dp; //after rilis
        $to = "suffy.yanuar@gmail.com";
        $cc = "suffy.yanuar@gmail.com";
        // $cc = $email_principal.",fakhrul@muliaputramandiri.com,tria@muliaputramandiri.com,suffy@muliaputramandiri.com,ilham@muliaputramandiri.com"; //after rilis
        $subject = "site.muliaputramandiri.com|Advanced Shipping Notes From $namasupp";
        $message = "Automatic Email By Sistem. Dear $company. email berisi lampiran informasi pengiriman PO dari principal";
        $this->load->library('email');
        $config['protocol']     = 'smtp';
        $config['smtp_host']    = 'ssl://smtp.gmail.com';
        $config['smtp_port']    = '465';
        $config['smtp_timeout'] = '300';
        $config['smtp_user']    = 'suffy.yanuar@gmail.com';
        $config['smtp_pass']    = 'ririn123!@#';
        $config['charset']      = 'utf-8';
        $config['newline']      = "\r\n";
        $config['mailtype']     ="html";
        $config['use_ci_email'] = TRUE;
        $config['wordwrap']     = TRUE;

        $this->email->initialize($config);
        $this->email->from($from,'PT. Mulia Putra Mandiri');
        $this->email->to($to);
        $this->email->cc($cc);
        $this->email->subject($subject);
        $this->email->message($message);
        $this->email->attach('assets/file/asn/'.$id_po.'.csv');
        $send = $this->email->send();
        // echo $this->email->print_debugger();
        if ($send) {
            echo "<script>alert('pengiriman email berhasil'); </script>";
            redirect('asn/list_asn','refresh');
        }else{
            echo "<script>alert('pengiriman email gagal'); </script>";
            redirect('asn/list_asn','refresh');
        }
    }

    public function generate_asn($id_po){
        $sql = "
        select 	a.nopo, date_format(a.tglpo,'%d/%m/%Y') as tglpo, 
				b.kodeprod, b.namaprod, b.banyak, b.banyak_karton,c.jumlah_unit as asn_unit, 
				c.jumlah_karton as asn_karton, c.batch_number, c.nodo, c.ed, c.tanggal_kirim, 
				c.nama_ekspedisi, c.est_lead_time, c.tanggal_tiba, c.status_closed
        from    mpm.po a INNER JOIN 
        (
                select 	a.id_ref, a.kodeprod, a.banyak, a.banyak_karton, a.berat, a.namaprod
                from	mpm.po_detail a
                where 	a.deleted = 0 and a.id_ref = $id_po
        )b on a.id = b.id_ref left JOIN
        (
            select 	a.id as id_asn, a.id_po, a.no_asn, a.kodeprod, a.tanggal_kirim, a.est_lead_time, a.nama_ekspedisi, a.jumlah_unit, a.jumlah_karton, a.tanggal_tiba, a.status_closed, a.batch_number, a.nodo, a.ed
            from 	mpm.t_asn a
        )c on a.id = c.id_po and b.kodeprod = c.kodeprod
        where 	a.id = $id_po and a.deleted = 0"
        ;

        $hasil = $this->db->query($sql);
        $file = fopen(APPPATH . '/../assets/file/asn/'.$id_po.'.csv', 'wb');

        $csv_fields=array();
        // $csv_fields[] = 'id';
        $csv_fields[] = 'nopo';
        $csv_fields[] = 'tglpo';
        $csv_fields[] = 'kodeprod';
        $csv_fields[] = 'namaprod';
        $csv_fields[] = 'unit_po';
        $csv_fields[] = 'karton_po';
        $csv_fields[] = 'asn_unit';
        $csv_fields[] = 'asn_karton';
        $csv_fields[] = 'batch_number';
        $csv_fields[] = 'nodo';
        $csv_fields[] = 'ed';
        $csv_fields[] = 'tanggal_kirim';
        $csv_fields[] = 'nama_ekspedisi';
        $csv_fields[] = 'est_lead_time';
        $csv_fields[] = 'tanggal_tiba';
        $csv_fields[] = 'status_closed';
        fputcsv($file, $csv_fields);

        foreach ($hasil->result() as $row) 
        {
            // $id = $row->id;
            $nopo = $row->nopo;
            $tglpo = $row->tglpo;
            $kodeprod = $row->kodeprod;
            $namaprod = $row->namaprod;
            $banyak = $row->banyak;
            $banyak_karton = $row->banyak_karton;
            $asn_unit = $row->asn_unit;
            $asn_karton = $row->asn_karton;
            $batch_number = $row->batch_number;
            $nodo = $row->nodo;
            $ed = $row->ed;
            $tanggal_kirim = $row->tanggal_kirim;
            $nama_ekspedisi = $row->nama_ekspedisi;
            $est_lead_time = $row->est_lead_time;
            $tanggal_tiba = $row->tanggal_tiba;
            $status_closed = $row->status_closed;
            fputcsv($file, array($nopo,$tglpo,$kodeprod,$namaprod,$banyak,$banyak_karton,$asn_unit,$asn_karton,$batch_number,$nodo,$ed,$tanggal_kirim,$nama_ekspedisi,$est_lead_time,$tanggal_tiba,$status_closed));
        }
    }

    public function upload_asn($data){
        $this->db->query("truncate mpm.t_upload_asn");
        $this->db->insert_batch('mpm.t_upload_asn', $data);
        // redirect('asn/list_asn');

        $sql = "
        select *
        from mpm.t_upload_asn a
        where a.jumlah_karton is not null
        ";
        $get_data_upload = $this->db->query($sql)->result();
        foreach ($get_data_upload as $x) {
            $id_po = $x->id_po;
            $kodeprod = $x->kodeprod;
            $jumlah_karton = $x->jumlah_karton;
            $status_pemenuhan = $x->status_pemenuhan;
            $tanggal_kirim = $x->tanggal_kirim;
            $nama_ekspedisi = $x->nama_ekspedisi;
            $est_lead_time = $x->est_lead_time;
            $tanggal_tiba = $x->tanggal_tiba;
            $keterangan = $x->keterangan;
            $status_closed = $x->status_closed;
            $created_date = $x->created_date;
            $created_by = $x->created_by;

            $cek = "
                select *
                from mpm.t_asn a
                where a.id_po = $id_po and a.kodeprod = $kodeprod
            ";
            $proses_cek = $this->db->query($cek)->result();
            if ($proses_cek) {
                // echo "update

                $update = "
                    update mpm.t_asn a
                    set a.jumlah_karton = $jumlah_karton, a.status_pemenuhan = '$status_pemenuhan', a.tanggal_kirim = '$tanggal_kirim', 
                        a.nama_ekspedisi = '$nama_ekspedisi', a.est_lead_time = $e,km7ist_lead_time,
                        a.tanggal_tiba = '$tanggal_tiba', a.keterangan = '$keterangan', a.status_closed = '$status_closed',
                        a.last_updated = '$created_date', a.last_updated_by = $created_by
                    where a.id_po = $id_po and a.kodeprod = '$kodeprod'
                ";
                // print_r($update);
                $proses_update = $this->db->query($update);

                $get_id_asn = $this->db->query("select id from mpm.t_asn where last_updated = '$created_date'")->result();
                foreach ($get_id_asn as $key) {
                    $id_asn = $key->id;
                }

                foreach ($this->get_total_unit_po($id_po) as $key) {
                    $total_unit_po = $key->total_unit;
                    $total_karton_po = $key->total_karton;
                    $total_produk_po = $key->total_produk;
                }
        
                foreach ($this->get_total_unit_asn($id_po) as $key) {
                    $total_unit_asn = $key->total_unit_asn;
                    $total_karton_asn = $key->total_karton_asn;
                    $total_produk_asn = $key->total_produk_asn;
                }      
        
                $insert_cek_asn = "
                    insert into mpm.t_cek_asn
                    select '' as id, $id_asn, $id_po, $total_unit_po, $total_unit_asn, $total_karton_po, $total_karton_asn, $total_produk_po,$total_produk_asn, 1
                ";
                $proses_insert_cek_asn = $this->db->query($insert_cek_asn);


            }else{
                // echo "insert";
                $insert = "
                    insert into mpm.t_asn (id_po,kodeprod,jumlah_karton,status_pemenuhan,tanggal_kirim, nama_ekspedisi, est_lead_time, tanggal_tiba, keterangan, status_closed, created_date, created_by, last_updated, last_updated_by) values ($id_po,$kodeprod,$jumlah_karton,'$status_pemenuhan','$tanggal_kirim','$nama_ekspedisi','$est_lead_time','$tanggal_tiba','$keterangan','$status_closed','$created_date',$created_by,'$created_date',$created_by)
                ";
                // print_r($update);
                $proses_insert = $this->db->query($insert);

                $get_id_asn = $this->db->query("select id from mpm.t_asn where created_date = '$created_date'")->result();
                foreach ($get_id_asn as $key) {
                    $id_asn = $key->id;
                }

                foreach ($this->get_total_unit_po($id_po) as $key) {
                    $total_unit_po = $key->total_unit;
                    $total_karton_po = $key->total_karton;
                    $total_produk_po = $key->total_produk;
                }
        
                foreach ($this->get_total_unit_asn($id_po) as $key) {
                    $total_unit_asn = $key->total_unit_asn;
                    $total_karton_asn = $key->total_karton_asn;
                    $total_produk_asn = $key->total_produk_asn;
                }      
        
                $insert_cek_asn = "
                    insert into mpm.t_cek_asn
                    select '' as id, $id_asn, $id_po, $total_unit_po, $total_unit_asn, $total_karton_po, $total_karton_asn, $total_produk_po,$total_produk_asn, 1
                ";
                $proses_insert_cek_asn = $this->db->query($insert_cek_asn);

            }
            
            // echo "<br>";
        }
 
        echo "<script>alert('data anda sudah di proses'); </script>";
        redirect('asn/list_asn','refresh');

        // var_dump($get_data_upload);
    }

    public function get_po_asn(){
        $supp = $this->session->userdata('supp');
        if($supp == 000){
            $sql = "
            select 	a.id, a.nopo, a.company, d.branch_name, d.nama_comp,
                    a.userid,a.tipe,date_format(a.tglpo,'%Y-%m-%d') as tglpo,
                    sum(b.banyak) as u, sum(e.asn_unit) as u_asn, sum(b.banyak * b.harga) as v, e.no_asn
            from    mpm.po a INNER JOIN mpm.po_detail b
                            on a.id = b.id_ref LEFT JOIN
            (
                select a.id, a.username
                from mpm.`user` a
            )c on a.userid = c.id LEFT JOIN 
            (
                select a.kode, a.branch_name, a.nama_comp, a.kode_comp
                FROM
                (
                        select 	concat(a.kode_comp,a.nocab) as kode, a.branch_name,a.nama_comp, a.kode_comp
                        from 	mpm.tbl_tabcomp a
                        WHERE	a.`status` = 1
                        GROUP BY a.kode_comp
                )a
            )d on c.username = d.kode_comp INNER JOIN
            (
                select a.id as id_asn, a.id_po, a.no_asn, a.asn_kodeprod, a.asn_unit
                from mpm.t_asn a
            )e on a.id = e.id_po and b.kodeprod = e.asn_kodeprod
            where a.deleted = 0 and b.deleted = 0 and nopo not like '/mpm%' and a.nopo not like '%batal%' 
            GROUP BY a.nopo, e.no_asn
            ORDER BY a.id DESC
            ";
        }else{
            $sql = "
            select 	a.id, a.nopo, a.company, d.branch_name, d.nama_comp,
                    a.userid,a.tipe,date_format(a.tglpo,'%Y-%m-%d') as tglpo,
                    sum(b.banyak) as u, sum(e.asn_unit) as u_asn, sum(b.banyak * b.harga) as v, e.no_asn
            from    mpm.po a INNER JOIN mpm.po_detail b
                            on a.id = b.id_ref LEFT JOIN
            (
                select a.id, a.username
                from mpm.`user` a
            )c on a.userid = c.id LEFT JOIN 
            (
                select a.kode, a.branch_name, a.nama_comp, a.kode_comp
                FROM
                (
                        select 	concat(a.kode_comp,a.nocab) as kode, a.branch_name,a.nama_comp, a.kode_comp
                        from 	mpm.tbl_tabcomp a
                        WHERE	a.`status` = 1
                        GROUP BY a.kode_comp
                )a
            )d on c.username = d.kode_comp INNER JOIN
            (
                select a.id as id_asn, a.id_po, a.no_asn, a.asn_kodeprod, a.asn_unit
                from mpm.t_asn a
            )e on a.id = e.id_po and b.kodeprod = e.asn_kodeprod
            where a.supp = '$supp' and a.deleted = 0 and b.deleted = 0 and nopo not like '/mpm%' and a.nopo not like '%batal%' 
            GROUP BY a.nopo, e.no_asn
            ORDER BY a.id DESC
            ";
        }

        // echo "<pre>";
        // print_r($sql);
        // echo "</pre>";

        $proses = $this->db->query($sql)->result();
        return $proses;
    }
    
    public function get_po_by_produk_do(){
        $id_po = $this->uri->segment('3');
        $no_asn = $this->uri->segment('4');
        $sql = "
        select 	a.id, c.id_asn, a.nopo, date_format(a.tglpo,'%d/%m/%Y') as tglpo, b.kodeprod, b.namaprod, b.banyak, 
                c.no_asn, c.asn_kodeprod, c.asn_unit, c.asn_tanggal_kirim, c.asn_nama_expedisi, c.asn_est_lead_time, c.asn_eta
        from mpm.po a INNER JOIN mpm.po_detail b 
            on a.id = b.id_ref INNER JOIN
        (
            select a.id as id_asn, a.id_po, a.no_asn, a.asn_kodeprod, a.asn_tanggal_kirim, a.asn_est_lead_time, a.asn_nama_expedisi, a.asn_unit, a.asn_eta
            from mpm.t_asn a
        )c on a.id = c.id_po and b.kodeprod = c.asn_kodeprod
        LEFT JOIN
        (
            select *
            from mpm.t_do a
        )d on a.id = d.id_po and b.kodeprod = d.do_kodeprod
        where a.id = $id_po and c.no_asn = '$no_asn' and a.deleted = 0 and b.deleted = 0
        ";

        // echo "<pre>";
        // print_r($sql);
        // echo "</pre>";

        $proses = $this->db->query($sql)->result();
        return $proses;
    }

    public function get_table_produk_do(){
        $id_po = $this->uri->segment('3');
        $no_asn = $this->uri->segment('4');
        $sql = "
        select 	a.id, c.no_asn, d.id_do, a.nopo, c.no_asn, c.asn_unit, date_format(a.tglpo,'%d/%m/%Y') as tglpo, b.kodeprod, b.namaprod, b.banyak, 
                d.no_do, d.do_kodeprod, d.do_unit, d.do_tanggal_kirim, d.do_nama_expedisi, d.do_est_lead_time, d.do_eta
        from mpm.po a INNER JOIN mpm.po_detail b 
            on a.id = b.id_ref LEFT JOIN
        (
            select a.id as id_asn, a.id_po, a.no_asn, a.asn_kodeprod, a.asn_tanggal_kirim, a.asn_est_lead_time, a.asn_nama_expedisi, a.asn_unit, a.asn_eta
            from mpm.t_asn a
        )c on a.id = c.id_po and b.kodeprod = c.asn_kodeprod INNER JOIN
        (
            select a.id as id_do, a.id_po, a.no_do, a.do_kodeprod, a.do_tanggal_kirim, a.do_est_lead_time, a.do_nama_expedisi, a.do_unit, a.do_eta
            from mpm.t_do a
        )d on a.id = d.id_po and b.kodeprod = d.do_kodeprod
        where a.id = $id_po and c.no_asn = '$no_asn' and a.deleted = 0 and b.deleted = 0
        ";

        // echo "<pre>";
        // print_r($sql);
        // echo "</pre>";

        $proses = $this->db->query($sql)->result();
        return $proses;
    }

    public function proses_tambah_do($data){
        date_default_timezone_set('Asia/Jakarta'); 
        
        $id = $this->session->userdata('id');
        $id_po = $data['id_po'];
        $do_kodeprod = $data['do_kodeprod'];
        $no_asn = $data['no_asn'];
        $no_do = $data['no_do'];
        
        $do_tanggalKirim = trim($data['do_tanggalKirim']);
        if ($do_tanggalKirim == '') {
            $convert_do_tanggalKirim='';
        }else{
            $convert_do_tanggalKirim=strftime('%Y-%m-%d',strtotime($do_tanggalKirim));
        }

        $do_unit = $data['do_unit'];
        $do_nama_expedisi = $data['do_nama_expedisi'];
        $do_est_lead_time = $data['do_est_lead_time'];
        $do_eta = $data['do_eta'];

        $do_eta = trim($data['do_eta']);
        if ($do_eta == '') {
            $convert_do_eta='';
        }else{
            $convert_do_eta=strftime('%Y-%m-%d',strtotime($do_eta));
        }
        $creatdedate = date('Y-m-d H:i:s');
        $lastupdated = date('Y-m-d H:i:s');

        // echo "<pre>";
        // echo "id_po : ".$id_po;
        // echo "do_kodeprod : ".$do_kodeprod;
        // echo "convert_do_tanggalKirim : ".$convert_do_tanggalKirim;
        // echo "do_unit : ".$do_unit;
        // echo "do_nama_expedisi : ".$do_nama_expedisi;
        // echo "do_est_lead_time : ".$do_est_lead_time;
        // echo "convert_do_eta : ".$convert_do_eta;
        // echo "</pre>";

        $sql="
            INSERT INTO mpm.t_do
            SELECT '' as id, $id_po, '$no_do', '$do_kodeprod', '$do_unit', '$convert_do_tanggalKirim', '$do_nama_expedisi',
            datediff('$convert_do_eta',  '$convert_do_tanggalKirim') as asn_do_lead_time, '$convert_do_eta',
            '$creatdedate' as createdDate, $id, '$lastupdated' as lastupdated, $id as lastupdatedbyid
        ";
        $proses = $this->db->query($sql);

        if ($proses) {
            redirect("asn/input_do/$id_po/$no_asn");
        }else{
            return false;
        }
    }

    public function delete_produk_do() {
        $id_po = $this->uri->segment('3');        
        $no_asn = $this->uri->segment('4');
        $id_do = $this->uri->segment('5');
        $hasil = $this->db->query("Delete From mpm.t_do where `id` = $id_do");
        
        redirect("asn/input_do/$id_po/$no_asn");
    }

    public function edit_produk_do(){
        $id_do = $this->uri->segment('5');
        $sql = "
        select 	a.id, c.id_do, a.nopo, date_format(a.tglpo,'%d/%m/%Y') as tglpo, b.kodeprod, b.namaprod, b.banyak, 
                c.no_do, c.do_kodeprod, c.do_unit, c.do_tanggal_kirim, c.do_nama_expedisi, c.do_est_lead_time, c.do_eta
        from mpm.po a INNER JOIN mpm.po_detail b 
            on a.id = b.id_ref INNER JOIN
        (
            select a.id as id_do, a.id_po, a.no_do, a.do_kodeprod, a.do_tanggal_kirim, a.do_est_lead_time, a.do_nama_expedisi, a.do_unit, a.do_eta
            from mpm.t_do a
        )c on a.id = c.id_po and b.kodeprod = c.do_kodeprod
        where c.id_do = $id_do and a.deleted = 0 and b.deleted = 0
        ";

        // echo "<pre>";
        // print_r($sql);
        // echo "</pre>";

        $proses = $this->db->query($sql)->result();
        return $proses;
    }

    public function proses_edit_do($data){
        date_default_timezone_set('Asia/Jakarta'); 
        
        $id = $this->session->userdata('id');
        $id_po = $data['id_po'];
        $no_asn = $data['no_asn'];
        $no_do = $data['no_do'];
        $id_do = $data['id_do'];
        $do_kodeprod = $data['do_kodeprod'];
        
        $do_tanggalKirim = trim($data['do_tanggal_kirim']);
        if ($do_tanggalKirim == '') {
            $convert_do_tanggalKirim='';
        }else{
            $convert_do_tanggalKirim=strftime('%Y-%m-%d',strtotime($do_tanggalKirim));
        }

        $do_unit = $data['do_unit'];
        $do_nama_expedisi = $data['do_nama_expedisi'];
        $do_est_lead_time = $data['do_est_lead_time'];
        $do_eta = $data['do_eta'];

        $do_eta = trim($data['do_eta']);
        if ($do_eta == '') {
            $convert_do_eta='';
        }else{
            $convert_do_eta=strftime('%Y-%m-%d',strtotime($do_eta));
        }

        // echo "<pre>";
        // echo "id_po : ".$id_po;
        // echo "no_do : ".$id_do;
        // echo "do_kodeprod : ".$do_kodeprod;
        // echo "convert_do_tanggalKirim : ".$convert_do_tanggalKirim;
        // echo "do_unit : ".$do_unit;
        // echo "do_nama_expedisi : ".$do_nama_expedisi;
        // echo "do_est_lead_time : ".$do_est_lead_time;
        // echo "convert_do_eta : ".$convert_do_eta;
        // echo "</pre>";

        $sql="
            UPDATE mpm.t_do
            set no_do = '$no_do', do_unit = '$do_unit', do_tanggal_kirim = '$convert_do_tanggalKirim', do_nama_expedisi = '$do_nama_expedisi',
            do_est_lead_time = datediff('$convert_do_eta',  '$convert_do_tanggalKirim'), do_eta = '$convert_do_eta', lastUpdated = '$lastupdated' , lastUpdatedById = $id
            WHERE id= $id_do
        ";
        $proses = $this->db->query($sql);

        if ($proses) {
            redirect("asn/input_do/$id_po/$no_asn");
        }else{
            return false;
        }
    }

    public function report(){
        $supp = $this->session->userdata('supp');
        if($supp == 000){
            $sql="
            SELECT 	a.id, a.nopo, a.no_asn, a.no_do, a.company, a.branch_name, a.nama_comp,
                    a.userid,a.tipe, a.do_tanggal_kirim, a.do_nama_expedisi,date_format(a.tglpo,'%Y-%m-%d') as tglpo,
                    a.u, a.u_asn, a.u_do, a.v, a.v_do, FORMAT(SUM(a.u_do/a.u*100),2) as persen

            FROM(
                    select 	a.id, a.nopo, e.no_asn, f.no_do, a.company, d.branch_name, d.nama_comp,
                    a.userid,a.tipe, f.do_tanggal_kirim, f.do_nama_expedisi, date_format(a.tglpo,'%Y-%m-%d') as tglpo,
                    sum(b.banyak) as u, sum(e.asn_unit) as u_asn, sum(f.do_unit) as u_do,FORMAT(SUM(f.do_unit/b.banyak),2) as persen, sum(b.banyak * b.harga) as v,sum(f.do_unit * b.harga) as v_do
                    from    mpm.po a INNER JOIN mpm.po_detail b
                                                    on a.id = b.id_ref LEFT JOIN
                    (
                            select a.id, a.username
                            from mpm.`user` a
                    )c on a.userid = c.id INNER JOIN 
                    (
                            select a.kode, a.branch_name, a.nama_comp, a.kode_comp
                            FROM
                            (
                                            select 	concat(a.kode_comp,a.nocab) as kode, a.branch_name,a.nama_comp, a.kode_comp
                                            from 	mpm.tbl_tabcomp a
                                            WHERE	a.`status` = 1
                                            GROUP BY a.kode_comp
                            )a
                    )d on c.username = d.kode_comp LEFT JOIN
                    (
                            select a.id as id_asn, a.id_po, a.no_asn, a.asn_kodeprod, a.asn_unit
                            from mpm.t_asn a
                    )e on a.id = e.id_po and b.kodeprod = e.asn_kodeprod INNER JOIN
                    (
                            select a.id as id_do, a.id_po, a.no_do, a.do_kodeprod, a.do_unit, a.do_tanggal_kirim, a.do_nama_expedisi
                            from mpm.t_do a
                    )f on a.id = f.id_po and b.kodeprod = f.do_kodeprod
                    where a.deleted = 0 and b.deleted = 0 and nopo not like '/mpm%' and a.nopo not like '%batal%' 
                    GROUP BY a.nopo,e.no_asn, f.no_do
            )a 
            GROUP BY a.nopo,a.no_asn,a.no_do
            ORDER BY a.id DESC
            ";
        }else{
            $sql="
            SELECT 	a.id, a.nopo, a.no_asn, a.no_do, a.company, a.branch_name, a.nama_comp,
                    a.userid,a.tipe, a.do_tanggal_kirim, a.do_nama_expedisi,date_format(a.tglpo,'%Y-%m-%d') as tglpo,
                    a.u, a.u_asn, a.u_do, a.v, a.v_do, FORMAT(SUM(a.u_do/a.u*100),2) as persen

            FROM(
                    select 	a.id, a.nopo, e.no_asn, f.no_do, a.company, d.branch_name, d.nama_comp,
                    a.userid,a.tipe, f.do_tanggal_kirim, f.do_nama_expedisi,date_format(a.tglpo,'%Y-%m-%d') as tglpo,
                    sum(b.banyak) as u, sum(e.asn_unit) as u_asn, sum(f.do_unit) as u_do,FORMAT(SUM(f.do_unit/b.banyak),2) as persen, sum(b.banyak * b.harga) as v,sum(f.do_unit * b.harga) as v_do
                    from    mpm.po a INNER JOIN mpm.po_detail b
                                                    on a.id = b.id_ref LEFT JOIN
                    (
                            select a.id, a.username
                            from mpm.`user` a
                    )c on a.userid = c.id INNER JOIN 
                    (
                            select a.kode, a.branch_name, a.nama_comp, a.kode_comp
                            FROM
                            (
                                            select 	concat(a.kode_comp,a.nocab) as kode, a.branch_name,a.nama_comp, a.kode_comp
                                            from 	mpm.tbl_tabcomp a
                                            WHERE	a.`status` = 1
                                            GROUP BY a.kode_comp
                            )a
                    )d on c.username = d.kode_comp LEFT JOIN
                    (
                            select a.id as id_asn, a.id_po, a.no_asn, a.asn_kodeprod, a.asn_unit
                            from mpm.t_asn a
                    )e on a.id = e.id_po and b.kodeprod = e.asn_kodeprod INNER JOIN
                    (
                            select a.id as id_do, a.id_po, a.no_do, a.do_kodeprod, a.do_unit, a.do_tanggal_kirim, a.do_nama_expedisi
                            from mpm.t_do a
                    )f on a.id = f.id_po and b.kodeprod = f.do_kodeprod
                    where a.supp = '$supp' and a.deleted = 0 and b.deleted = 0 and nopo not like '/mpm%' and a.nopo not like '%batal%' 
                    GROUP BY a.nopo,e.no_asn, f.no_do
            )a 
            GROUP BY a.nopo,a.no_asn,a.no_do
            ORDER BY a.id DESC
            ";
        }
        // echo "<pre>";
        // print_r($sql);
        // echo "</pre>";
        $proses = $this->db->query($sql)->result();
        return $proses;
    }

}