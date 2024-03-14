<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_transaction extends CI_Model 
{
    public function tampil_po(){
        //query semua record di table products
        $this->db->order_by('id','desc');
        $hasil = $this->db->get('po',10);
        if ($hasil->num_rows() > 0) {
            return $hasil->result();
        } else {
            return array();
        }
    }

    public function tampil_credit_limit(){
        $id = $this->session->userdata('id');
        $serverName = "192.168.7.3"; //serverName\instanceName, portNumber (default is 1433)
        $connectionInfo = array("Database" => "", "UID" => "sa", "PWD" => "mpm12345");
        $conn = sqlsrv_connect($serverName, $connectionInfo);
    
        if ($conn) {
            // echo "koneksi berhasil <br />";
        } else {
            echo "Connection could not be established.<br />";
            die(print_r(sqlsrv_errors(), true));
        }
    $sql = "
            select  * 
            from 
            (
                    select  a.*,
                            b.value 
                            
                    from    (
                                select  a.open,
                                        a.open_by, c.username,
                                        a.open_date,
                                        a.id,
                                        a.supp,
                                        b.company,
                                        b.id as userid,
                                        b.kode_lang,
                                        a.email,
                                        tipe,
                                        tglpesan,
                                        nopo, 
                                        date_format(tglpo,'%d %M %Y, %T') as tglpo,
                                        tglpo as tglpox,
                                        nodo, 
                                        date_format(tgldo,'%d %M %Y, %T') as tgldo, 
                                        b.bank_garansi, note_acc
                                from    mpm.po a inner join mpm.user b 
                                            on a.userid=b.id
                                        LEFT JOIN mpm.`user` c
                                        on a.open_by = c.id
                                where   a.deleted=0
                                order by id desc 
                                limit 1000
                            )a
                            
                            left join 
                            (       
                                select  id_ref,
                                        sum(harga*banyak) as value 
                                        from mpm.po_detail 
                                where   deleted=0 
                                group by id_ref
                            )b  on a.id=b.id_ref                
                            order by id desc
                            
            )a

            left join
            (
                select * from
                (
                    select kode_lang, sum(dokument-bayar) as saldoakhir
                    FROM
                    (
                            select  substr(customerid,2,5) kode_lang, 
                                            if(substr(no_sales,1,1) = 'R', dokument * -1, dokument) as dokument, bayar, customerid
                            from    dbsls.t_ar_ink_master 
                            where   dokument-bayar>0 
                    )a GROUP BY kode_lang
                )a inner join 
                (
                    select kode_lang, sum(dokument-bayar) as jt
                    FROM
                    (
                            select  substr(customerid,2,5) kode_lang, 
                                            if(substr(no_sales,1,1) = 'R', dokument * -1, dokument) as dokument, bayar, customerid
                            from    dbsls.t_ar_ink_master 
                            where   dokument-bayar>0 and datediff(tgl_tempo,curdate())<108 
                    )a GROUP BY kode_lang
                )b
                using (kode_lang)  
                
            )b 
            using(kode_lang)

            left join 
            (
                select  id as userid, kode_lang,cl 
                from    mpm.user
            )c  on a.userid = c.userid
        ";

        // echo "<pre>";
        // print_r($sql);
        // echo "</pre>";

        $hasil = $this->db->query($sql);

        if ($hasil->num_rows() > 0) {
            return $hasil->result();
        } else {
            return array();
        }
        

    }

    public function tampil_credit_limit_client($data_segment){
        
        $id=$this->session->userdata('id');
        $grup_lang = $data_segment['grup_lang'];
        $tgl = $data_segment['tgl'];
        $kode_lang_x = substr($grup_lang, 1,10);

        /*
        echo "<pre>";
        print_r($grup_lang);
        echo "<br>";
        print_r($kode_lang_x);
        echo "<br>";
        print_r($tgl);
        echo "</pre>";
        */
        if ($kode_lang_x == '') {
            $kl = "";
            $limit = "limit 100";
        }else{
            $kl = "and b.kode_lang = ".$kode_lang_x;
            $limit = '';
        }

        if ($tgl == '' || $tgl == '1970-01-01') {
            $time = "";
        }else{
            $time = "and tglpesan like ".'"'.$tgl.'%"';
        }

        
        //echo "</pre>";
        
        
        $sql = "
            select  * 
            from 
            (
                    select  a.*,
                            b.value 
                            
                    from    (
                                select  a.open,
                                        a.open_by, c.username,
                                        a.open_date,
                                        a.id,
                                        a.supp,
                                        b.company,
                                        b.id as userid,
                                        b.kode_lang,
                                        a.email,
                                        tipe,
                                        tglpesan,
                                        nopo, 
                                        tglpo,
                                        tglpo as tglpox,
                                        nodo, 
                                        tgldo, 
                                        b.bank_garansi, note_acc
                                from    mpm.po a inner join mpm.user b 
                                            on a.userid=b.id 
                                        LEFT JOIN mpm.`user` c
                                        on a.open_by = c.id
                                where   a.deleted=0 $kl $time
                                order by id desc 
                                $limit
                            )a
                            
                            left join 
                            (       
                                select  id_ref,
                                        sum(harga*banyak) as value 
                                        from mpm.po_detail 
                                where   deleted=0 
                                group by id_ref
                            )b  on a.id=b.id_ref                
                            order by id desc
                            
            )a

            left join
            (
                select * from
                (
                    select kode_lang, sum(dokument-bayar) as saldoakhir
                    FROM
                    (
                            select  substr(customerid,2,5) kode_lang, 
                                            if(substr(no_sales,1,1) = 'R', dokument * -1, dokument) as dokument, bayar, customerid
                            from    dbsls.t_ar_ink_master 
                            where   dokument-bayar>0 
                    )a GROUP BY kode_lang
                )a inner join 
                (
                    select kode_lang, sum(dokument-bayar) as jt
                    FROM
                    (
                            select  substr(customerid,2,5) kode_lang, 
                                            if(substr(no_sales,1,1) = 'R', dokument * -1, dokument) as dokument, bayar, customerid
                            from    dbsls.t_ar_ink_master 
                            where   dokument-bayar>0 and datediff(tgl_tempo,curdate())<108 
                    )a GROUP BY kode_lang
                )b
                using (kode_lang)  
                
            )b 
            using(kode_lang)

            left join 
            (
                select  id as userid, kode_lang,cl 
                from    mpm.user
            )c  on a.userid = c.userid
        ";
        
        // echo "<pre>";
        // print_r($sql);
        // echo "</pre>";
        
        $hasil = $this->db->query($sql);

        if ($hasil->num_rows() > 0) {
            return $hasil->result();
        } else {
            return array();
        }

    }

    public function list_pelanggan($dataSegment = ''){
    
    //echo "<br>ini model list_pelanggan<br>";
    $grup_lang = $dataSegment['grup_lang'];
        
        /*
        $sql="
            select distinct * 
            from
            (
                select  distinct concat('1',grup_lang) grup_lang,
                        grup_nama 
                from    pusat.user 
                where   grup_lang<>'' and 
                        grup_lang<>'00159'   
                union all   
                select  distinct group_id grup_lang,
                        group_descr grup_nama 
                from    dbsls.m_customer 
                where   group_id<>'' and 
                        group_id<>'100159'
            )a 
            union all

            select kode,nama_lang from mpm.tbl_bantu_pelanggan_po
            order by grup_lang = ".'"'.$grup_lang.'" desc,'."grup_nama"
            ;
        */

        $sql="
            select distinct * 
            from
            (
                select  distinct concat('1',grup_lang) grup_lang,
                        grup_nama 
                from    pusat.user 
                where   grup_lang<>'' and 
                        grup_lang<>'00159'   and GRUP_LANG <> '00121'
                union all   
                select  distinct group_id grup_lang,
                        group_descr grup_nama 
                from    dbsls.m_customer 
                where   group_id<>'' and 
                        group_id<>'100159'
            )a 
            "
            ;

        /*
        echo "<pre>";
        echo "<br><br>";
        echo "grup_lang : ".$grup_lang;
        print_r($sql);
        echo "</pre>";
        */
        return $this->db->query($sql,$grup_lang);
    }

    public function list_pelanggan_po($dataSegment = ''){
    
    //echo "<br>ini model list_pelanggan<br>";
    $grup_lang = $dataSegment['grup_lang'];
    
        $sql="
            select distinct * 
            from
            (
                select  distinct concat('1',grup_lang) grup_lang,
                        concat(grup_nama,'-',kode_kota) as grup_nama  
                from    pusat.user 
                where   grup_lang<>'' and 
                        grup_lang<>'00159'   and GRUP_LANG <> '00121'
                union all   
                select  distinct group_id grup_lang,
                        group_descr grup_nama 
                from    dbsls.m_customer 
                where   group_id<>'' and 
                        group_id<>'100159'
            )a union all

            select kode,nama_lang from mpm.tbl_bantu_pelanggan_po
            order by grup_lang = ".'"'.$grup_lang.'" desc,'."grup_nama"
            ;

        
        // echo "<pre>";
        // echo "grup_lang : ".$grup_lang;
        // print_r($sql);
        // echo "</pre>";
        
        return $this->db->query($sql,$grup_lang);
    }

    public function detail_po()
    {

        $id_po = $this->uri->segment(3);
        //echo "<br><br>id_po : ".$id_po;

        
        
        $sql = "
            select  supp, kode_lang, `open`, username, open_date, id, company, userid, tipe,
                tglpesan, nopo, tglpo, bank_garansi, `value`, saldoakhir, jt, cl, namasupp, note_acc
            from 
            (
                    select  a.*, b.value                            
                    from    
                    (
                        select  a.open, a.open_by, c.username, a.open_date, a.id,
                                a.supp, a.company, b.id as userid, b.kode_lang,
                                a.email, tipe, date_format(tglpesan,'%d %M %Y, %T') as tglpesan,
                                nopo, date_format(tglpo,'%d %M %Y, %T') as tglpo,
                                nodo, date_format(tgldo,'%d %M %Y, %T') as tgldo, 
                                b.bank_garansi, note, note_acc
                        from    mpm.po a inner join mpm.user b 
                                    on a.userid=b.id 
                                LEFT JOIN mpm.`user` c
                                on a.open_by = c.id
                        where   a.deleted=0 and a.id = $id_po
                        order by id desc
                    )a                            
                    left join 
                    (       
                        select  id_ref,
                                sum(harga*banyak) as value 
                                from mpm.po_detail 
                        where   deleted=0 
                        group by id_ref
                    )b  on a.id=b.id_ref                
                    order by id desc                            
            )a
            left join
            (
                select * from
                (
                    select  substr(customerid,2,5) kode_lang, 
                            sum(dokument-bayar) saldoakhir 
                    from    dbsls.t_ar_ink_master 
                    where   dokument-bayar>0 
                    group by customerid
                )a                
                inner join 
                (
                    select  substr(customerid,2,5) kode_lang,
                            sum(dokument-bayar) jt 
                    from    dbsls.t_ar_ink_master 
                    where   dokument-bayar>0 and 
                            datediff(tgl_tempo,curdate())<108 
                    group by customerid
                )b
                using (kode_lang)                
            )b 
            using(kode_lang)
            left join 
            (
                select  kode_lang,cl 
                from    mpm.user
            )c  using(kode_lang)
            LEFT JOIN mpm.tabsupp_new_new using(supp)
            
        ";
        /*
        echo "<pre><br><br><br>";
        print_r($sql);
        echo "</pre>";
        */
        $hasil = $this->db->query($sql);

        if ($hasil->num_rows() > 0) {
            return $hasil->result();
        } else {
            return array();
        }

    }

    public function proses_open_credit_limit($data_segment)
    {

        $id=$data_segment['id'];
        $userid=$data_segment['userid'];
        $note_acc=$data_segment['note_acc'];

        echo "id : ".$id;
        echo "userid : ".$userid;
        echo "note_acc : ".$note_acc;
    
        $data = array(
                'id'           => $data_segment['id'],
                'userid'       => $data_segment['userid'],
                'note_acc'     => $data_segment['note_acc'],
            );

        $hasil = $this->db->where('id',$data_segment['id'])
                 ->update('mpm.po', $data);
        
        //print_r($hasil);

        if ($hasil == '1') 
            {
                echo "update berhasil";
                
                redirect('all_transaction/open_credit_limit');
            } else {
                return array();
            }
        

    }

    public function proses_unlock_credit_limit($data_segment)
    {
        $logged_in= $this->session->userdata('logged_in');
          if(!isset($logged_in) || $logged_in != TRUE)
          {
              redirect('login/','refresh');
          }

        $id=$data_segment['id'];
        $userid=$data_segment['userid'];
        
        
        $data = array(
                'id'           => $data_segment['id'],
                'open'         => '1',
                'open_by'      => $userid,
                'open_date'    => date('Y-m-d H:i:s')
            );

        $hasil = $this->db->where('id',$data_segment['id'])
                 ->update('mpm.po', $data);
        
        
        if ($hasil == '1') 
            {
                echo "update berhasil";
                
                redirect('all_transaction/open_credit_limit');
            } else {
                return array();
            }
        

    }

    public function piutang()
    {

        $id_po = $this->uri->segment(3);
        //echo "<br><br>id_po : ".$id_po;

        //cari kode_lang dan open_date dari id
        /*$sql_x = "
            select a.id, a.userid, a.company, a.tglpesan, b.username, b.kode_lang 
            from mpm.po a LEFT JOIN mpm.`user` b
                        on a.userid = b.id
            where a.id = ".$id_po."
        ";

        $query = $this->db->query($sql_x);
        foreach ($query->result() as $row) {
            $kode_lang = $row->kode_lang;
            $tglpesan = $row->tglpesan;
        }*/

        //cari open_date dari po
        $sql_tglpesan = "
            select a.tglpesan, b.kode_lang 
            from mpm.po a LEFT JOIN mpm.`user` b
                        on a.userid = b.id
            where a.id = ".$id_po."
        ";

        $query = $this->db->query($sql_tglpesan);
        foreach ($query->result() as $row) {
            $tglpesan = $row->tglpesan;
            $kode_lang = $row->kode_lang;
        }
        //echo "tglpesan : ".$tglpesan;
        //echo "kode_lang : ".$kode_lang;

        $sql = "
            select * FROM
            (
            select  substr(customerid,2,5) kode_lang, 
                    tanggal, format(dokument,0), format(bayar,0), sum(dokument-bayar) as piutang
            from    dbsls.t_ar_ink_master 
            where   dokument-bayar>0  and substr(customerid,2,5) = '$kode_lang' and 
                    tanggal BETWEEN '0000-00-00 00:00:00' and '$tglpesan'
            )a ORDER BY tanggal
        ";
        /*
        echo "<pre><br><br><br>";
        print_r($sql);
        echo "</pre>";
        */
        $hasil = $this->db->query($sql);

        if ($hasil->num_rows() > 0) {
            return $hasil->result();
        } else {
            return array();
        }




    } 

    



}