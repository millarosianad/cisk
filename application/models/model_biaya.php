<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_biaya extends CI_Model 
{   

    public function hak_akses($userid){
        // echo "userid : ".$userid;

        if ($userid == '297' || $userid == '561' || $userid == '444' || $userid == '231' || $userid == '547' || $userid == '18' || $userid == '557' || $userid == '362') {
            return true;
        }else{
            return $userid;
        }

    }

    public function list_user()
    {
        $id=$this->session->userdata('id');
        
        $sql="
                select  a.id,a.kode_dp, a.username, a.company 
                from    mpm.`user` a
                where   active = 1
                order by a.company
                
            ";
        return $this->db->query($sql);
    }

    public function get_history($id= '', $hak_akses = '')
    {

        if ($hak_akses == '1') {
            $hak_aksesx = '';
        }else{
            $hak_aksesx = "and a.created_by in ($hak_akses)";
        }

        if ($id == '') {
            $idx = '';
        } else {
            $idx = "and a.id = $id";
        }
        $sql = "
        select  a.id, a.tanggal_transaksi, a.kategori, if(a.kategori = 1, 'bensin', 'null') as kategori_input, a.signature, a.created_at, a.created_by, b.km_awal, b.km_akhir, (b.km_akhir - b.km_awal) as total_km, b.liter, b.biaya, b.total_biaya, b.foto_km, b.foto_struk, c.username, a.kode, round((b.km_akhir - b.km_awal)/b.liter,2) as konsumsi, b.reimburse
        from site.t_biaya_operasional_header a LEFT JOIN
        (
            select a.id_header, a.id, a.km_awal, a.km_akhir, a.biaya, a.liter, a.total_biaya, a.foto_km, a.foto_struk, a.reimburse
            from site.t_biaya_operasional_detail a
        )b on a.id = b.id_header LEFT JOIN
        (
            select a.id, a.username 
            from mpm.user a 
        )c on a.created_by = c.id
        where a.deleted = 0 $idx $hak_aksesx
        ";
        // echo "<pre><br><br><br><br>";
        // print_r($sql);
        // echo "</pre>";

        $proses = $this->db->query($sql);
        return $proses;
    }

    public function insert($data){
        $header = [
            "kode"              => $data['kode'],
            "tanggal_transaksi" => $data['tanggal_transaksi'],
            "kategori"          => $data['kategori'],
            "signature"         => $data['signature'],
            "created_at"        => $data['created_at'],
            "created_by"        => $data['created_by']
        ];

        $insert_header = $this->db->insert("site.t_biaya_operasional_header", $header);
        $id_header = $this->db->insert_id();

        if ($insert_header) {
            // echo "id_header : ".$id_header;
            $detail = [
                "km_akhir" => $data['km_akhir'],
                "tanggal_transaksi" => $data['tanggal_transaksi'],
                "liter" => $data['liter'],
                "biaya" => $data['biaya'],
                // "keterangan" => $keterangan,
                "foto_km" => $data['foto_km'],
                "foto_struk" => $data['foto_struk'],
                "id_header" => $id_header,
                "created_at" => $data['created_at'],
                "created_by" => $data['created_by'],
            ];
            $insert_detail = $this->db->insert("site.t_biaya_operasional_detail", $detail);
            $id_detail = $this->db->insert_id();
            if ($insert_detail) {
                return $id_detail;
            }else{
                return array();
            }
        }else{
            return array();
        }
    }

    public function edit($data){
        $kode = $data['kode'];
        $kategori = $data['kategori'];
        $tanggal_transaksi = $data['tanggal_transaksi'];
        $km_akhir = $data['km_akhir'];
        $liter = $data['liter'];
        $biaya = $data['biaya'];
        // $keterangan = $data['keterangan'];
        $foto_km = $data['foto_km'];
        $foto_struk = $data['foto_struk'];
        $signature = $data['signature'];
        $updated_at = $data['updated_at'];
        $updated_by = $data['updated_by'];

        $header = [
            "kode"              => $data['kode'],
            "tanggal_transaksi" => $data['tanggal_transaksi'],
            "kategori"          => $data['kategori'],
            "signature"         => $data['signature'],
            "updated_at"        => $data['updated_at'],
            "updated_by"        => $data['updated_by']
        ];

        
        $this->db->where('id', $data['id']);
        $insert_header = $this->db->update('site.t_biaya_operasional_header', $header); 

        if ($insert_header) {
            // echo "id_header : ".$id_header;
            $detail = [
                "km_akhir" => $data['km_akhir'],
                "tanggal_transaksi" => $data['tanggal_transaksi'],
                "liter" => $data['liter'],
                "biaya" => $data['biaya'],
                // "keterangan" => $keterangan,
                "foto_km" => $data['foto_km'],
                "foto_struk" => $data['foto_struk'],
                "id_header" => $data['id'],
                "updated_at" => $data['updated_at'],
                "updated_by" => $data['updated_by'],
            ];
            $this->db->where('id_header', $data['id']);
            $insert_detail = $this->db->update("site.t_biaya_operasional_detail", $detail);
            // var_dump($id_detail);die;
            if ($insert_detail) {
                return $insert_detail;
            }else{
                return array();
            }

        }else{
            return array();
        }
    }

    public function total_biaya($id_detail){
        $sql = "
            select *
            from site.t_biaya_operasional_detail a
            where a.id = $id_detail
        ";
        
        $get_data = $this->db->query($sql)->row();
        $tanggal_transaksi = $get_data->tanggal_transaksi;
        $created_by = $get_data->created_by;

        // echo "tanggal_transaksi : ".$tanggal_transaksi;

        $tanggal_terakhir = "
            select *
            from site.t_biaya_operasional_detail a
            where a.created_by = $created_by and a.tanggal_transaksi <= '$tanggal_transaksi' and a.id <> $id_detail and a.deleted = 0
            ORDER BY tanggal_transaksi desc
			limit 1
        ";

        // echo "<pre>";
        // print_r($tanggal_terakhir);
        // echo "</pre>";
        // die;
        $get_km_awal = $this->db->query($tanggal_terakhir)->row();
        $km = count($get_km_awal);
        
        if ($km == '0') {
            $km_awal = '';
        } else {
            $km_awal = $get_km_awal->km_akhir;
        }
        

        // var_dump($km_awal);die;

        $update = "
            update site.t_biaya_operasional_detail a 
            set a.total_biaya = a.biaya, a.km_awal = '$km_awal'
            where a.id = '$id_detail'
        ";
        $proses = $this->db->query($update);

        // die;

        return $proses;
    }

    public function generate($kategori, $userid, $tanggal_transaksi)
    {
        if ($kategori == '1') {
            $param_kategori ="BENSIN";
        }

        $get_by_user = $this->db->get_where("site.t_biaya_operasional_header", array("created_by" => $userid))->num_rows();
        if (!$get_by_user) { 
            $reserve_nomor_params = "MPM_$param_kategori/" . date('Y') . date('m'). "/" . $userid . "-001";
            
        } else { 

            //jika sudah ada sebelumnya

            $sql = "
                select  a.kode, right(a.kode,1) as urut
                from    site.t_biaya_operasional_header a
                where   a.created_by = '$userid' and year(a.tanggal_transaksi)= year('$tanggal_transaksi')
                ORDER BY a.id desc
                limit 1
            ";


            $sql = "
                select  right(a.kode,3) as urut
                from    site.t_biaya_operasional_header a
                where   a.created_by = '$userid'
                ORDER BY right(a.kode,3) desc
                limit 1
            ";

            $proses = $this->db->query($sql)->row();
            $reserve_nomor = $proses->urut + 1;
            if (strlen($reserve_nomor) === 1) {
                $reserve_nomor_params = "MPM_$param_kategori/" . date('Y') . date('m') . "/" . $userid . "-00" . $reserve_nomor;
            } else if (strlen($reserve_nomor) === 2) {
                $reserve_nomor_params = "MPM_$param_kategori/" . date('Y') . date('m') . "/" . $userid . "-0" . $reserve_nomor;
            } else {
                $reserve_nomor_params = "MPM_$param_kategori/" . date('Y') . date('m') . "/" . $userid . "-" . $reserve_nomor;
            }


        }

        return $reserve_nomor_params;
    }

    public function get_report_by_periode($data)
    {
        $from = $data['from'];
        $to = $data['to'];
        $userid = $data['userid'];

        if ($userid == '0') {
            $userid = $this->session->userdata('id');
        }else{
            $userid = "$userid";
        }

        $query = "
            select 	c.username, a.tanggal_transaksi, b.km_awal, b.km_akhir, (b.km_akhir - b.km_awal) as total_km, b.liter, b.biaya, b.total_biaya, round((b.km_akhir - b.km_awal)/b.liter,2) as konsumsi, b.reimburse
            from site.t_biaya_operasional_header a INNER JOIN
            (
                select a.id, a.id_header, a.km_awal, a.km_akhir, a.biaya, a.liter, a.foto_km, a.foto_struk, a.keterangan, a.total_biaya, a.reimburse
                from site.t_biaya_operasional_detail a
                where a.deleted = 0
            )b on a.id = b.id_header LEFT JOIN
            (
                select a.id, a.username
                from mpm.user a
            )c on a.created_by = c.id
            where a.deleted = 0 and a.tanggal_transaksi between '$from' and '$to' and a.created_by in ($userid)
        ";

        return $this->db->query($query);
    }

    public function get_total_reimburse_periode($data)
    {
        $from = $data['from'];
        $to = $data['to'];
        $userid = $data['userid'];

        if ($userid == '0') {
            $userid = $this->session->userdata('id');
        }else{
            $userid = "$userid";
        }

        $query = "
            select sum(b.biaya) as total_biaya, b.created_by
            from site.t_biaya_operasional_header a INNER JOIN
            (
                select a.id, a.id_header, a.km_awal, a.km_akhir, a.biaya, a.liter, a.foto_km, a.foto_struk, a.keterangan, a.total_biaya, a.reimburse, a.created_by
                from site.t_biaya_operasional_detail a
                where a.reimburse = 1 and a.deleted = 0
            )b on a.id = b.id_header
            where a.deleted = 0 and a.tanggal_transaksi between '$from' and '$to' and a.created_by in ($userid)
        ";

        return $this->db->query($query);
    }
    
    public function get_total_biaya_periode($data)
    {
        $from = $data['from'];
        $to = $data['to'];
        $userid = $data['userid'];

        if ($userid == '0') {
            $userid = $this->session->userdata('id');
        }else{
            $userid = "$userid";
        }

        $query = "
            select sum(b.biaya) as total_biaya, b.created_by
            from site.t_biaya_operasional_header a INNER JOIN
            (
                select a.id, a.id_header, a.km_awal, a.km_akhir, a.biaya, a.liter, a.foto_km, a.foto_struk, a.keterangan, a.total_biaya, a.reimburse, a.created_by
                from site.t_biaya_operasional_detail a
                where a.deleted = 0
            )b on a.id = b.id_header
            where a.deleted = 0 and a.tanggal_transaksi between '$from' and '$to' and a.created_by in ($userid)
            group by b.created_by
        ";

        return $this->db->query($query);
    }

    public function get_report_reimburse($data)
    {   
        $this->db->select('*');
        $this->db->where('reimburse', '1');
        $this->db->where('deleted', '0');
        $this->db->where_in('id_header', $data);
        $proses = $this->db->get('site.t_biaya_operasional_detail');

        return $proses;
    }

    public function get_report_total_reimburse($data)
    {   
        $this->db->select('sum(biaya) as total_biaya');
        $this->db->where('reimburse', '1');
        $this->db->where('deleted', '0');
        $this->db->where_in('id_header', $data);
        $proses = $this->db->get('site.t_biaya_operasional_detail');

        return $proses;
    }

    public function get_history_by_periode($data)
    {
        $from = $data['from'];
        $to = $data['to'];
        $userid = $data['userid'];

        if ($userid == '0') {
            $hak_aksesx = '';
        }else{
            $hak_aksesx = "and a.created_by in ($userid)";
        }

        $query = "
            select 	a.id, c.username, a.kategori, a.signature, a.tanggal_transaksi, b.km_awal, b.km_akhir, 
                    (b.km_akhir - b.km_awal) as total_km, b.liter, b.biaya, b.total_biaya, round((b.km_akhir - b.km_awal)/b.liter,2) as konsumsi, b.reimburse
            from    site.t_biaya_operasional_header a INNER JOIN
            (
                select a.id, a.id_header, a.km_awal, a.km_akhir, a.biaya, a.liter, a.foto_km, a.foto_struk, a.keterangan, a.total_biaya, a.reimburse
                from site.t_biaya_operasional_detail a
            )b on a.id = b.id_header LEFT JOIN
            (
                select a.id, a.username
                from mpm.user a
            )c on a.created_by = c.id
            where a.deleted = 0 and b.reimburse = 0 and a.tanggal_transaksi between '$from' and '$to' $hak_aksesx
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        // die;

        return $this->db->query($query);
    }

    public function update_reimburse($data)
    {
        $this->db->set('reimburse', 1);
        $this->db->where_in('id', $data);
        $proses = $this->db->update('site.t_biaya_operasional_detail'); 
        return $proses;
    }
    
    public function delete_biaya_operasional($data)
    {

        $this->db->set('deleted', 1);
        $this->db->where('signature', $data['signature']);
        $this->db->update('site.t_biaya_operasional_header');

        $this->db->set('deleted', 1);
        $this->db->where('id_header', $data['id_header']);
        $this->db->update('site.t_biaya_operasional_detail');
        
    }
}