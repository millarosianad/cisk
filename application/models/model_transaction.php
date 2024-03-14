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

    public function get_data_dbsls_old(){
        $id = $this->session->userdata('id');
        $tgl_hari_ini=date('Y-m-d');
        $tgl_created=date('Y-m-d H:i:s');

        $sql_cek = "
            select date(a.last_updated) as last_updated
            from db_analisis.t_temp_piutang a
            where a.id = $id
            limit 1 ";
        $proses = $this->db->query($sql_cek)->result();
        // var_dump($proses);

        if ($proses) { 
            foreach ($proses as $x) {
                $last_updated = $x->last_updated;
            }
            
            if ($last_updated == $tgl_hari_ini) {
                // echo "<hr>sama ";
            }else{
                $proses= $this->db->query("delete from db_analisis.t_temp_piutang where id = $id");
                if($proses){
                    redirect(base_url().'all_transaction/open_credit_limit');
                }
            }
            
        }else{ //jika ditemukan belum pernah tarik data dbsls
            
            $serverName = "backup.muliaputramandiri.com"; //serverName\instanceName, portNumber (default is 1433)
            $connectionInfo = array("Database" => "", "UID" => "sa", "PWD" => "mpm12345");
            $conn = sqlsrv_connect($serverName, $connectionInfo);

            if ($conn) {
                echo "koneksi berhasil <br />";
            } else {
                echo "Koneksi ke server sds gagal.<br />";
                die(print_r(sqlsrv_errors(), true));
            }

            // $datanya = "select productid,nama_product,jual from jts.dbo.m_product where left(productid,2) = '01'";
            $sql = "
                select '1' + a.kode_lang as kode_lang,saldoakhir,jt  
                from
                (
                    select kode_lang, sum(dokument-bayar) as saldoakhir
                    FROM
                    (
                        select  SUBSTRING(customerid,2,5) kode_lang,                              
                                CASE 
                                WHEN SUBSTRING(no_sales,1,1) = 'R' then dokument * -1 else dokument
                                end as dokument, bayar
                        from    dbsls.dbo.t_ar_ink_master 
                        where   dokument-bayar>0 
                        )a GROUP BY kode_lang
                )a inner join
                (	
                    select kode_lang, sum(dokument-bayar) as jt
                    FROM
                    (
                        select  SUBSTRING(customerid,2,5) kode_lang,                              
                                CASE 
                                WHEN SUBSTRING(no_sales,1,1) = 'R' then dokument * -1 else dokument
                                end as dokument, bayar, tgl_tempo,datediff(day, tgl_tempo, GETDATE()) * -1 as c
                        from    dbsls.dbo.t_ar_ink_master 
                        where   dokument-bayar>0  
                    )a  where c<108
                    GROUP BY kode_lang
                )b on a.kode_lang = b.kode_lang
                ORDER BY a.kode_lang
            ";
            $query = sqlsrv_query($conn, $sql); 
            
            $sql = "delete from db_analisis.t_temp_piutang where id = $id";
            $proses= $this->db->query($sql);
            if ($query) {
                while ($data = sqlsrv_fetch_array($query)){
                    $kode_lang =  $data['kode_lang'];
                    $saldoakhir = $data['saldoakhir'];
                    $jt = $data['jt'];
                    $sql = "insert into db_analisis.t_temp_piutang
                            select $kode_lang, $saldoakhir, $jt, $id, '$tgl_created'
                    ";
                    $proses= $this->db->query($sql);
                }
            }             
            redirect(base_url().'all_transaction/open_credit_limit');
        }

        return $last_updated;
    }

    public function get_data_dbsls($date){
        $id = $this->session->userdata('id');
        $tgl_created= $date;
        // var_dump($tgl_created);die;

        $serverName = "backup.muliaputramandiri.com"; //serverName\instanceName, portNumber (default is 1433)
        $connectionInfo = array("Database" => "", "UID" => "sa", "PWD" => "mpm12345");
        $conn = sqlsrv_connect($serverName, $connectionInfo);

        if ($conn) {
            echo "koneksi berhasil <br />";
        } else {
            echo "Koneksi ke server sds gagal.<br />";
            // die(print_r(sqlsrv_errors(), true));
        }

        // $datanya = "select productid,nama_product,jual from jts.dbo.m_product where left(productid,2) = '01'";
        $sql = "
            select '1' + a.kode_lang as kode_lang,saldoakhir,jt  
            from
            (
                select kode_lang, sum(dokument-bayar) as saldoakhir
                FROM
                (
                    select  SUBSTRING(customerid,2,5) kode_lang,                              
                            CASE 
                            WHEN SUBSTRING(no_sales,1,1) = 'R' then dokument * -1 else dokument
                            end as dokument, bayar
                    from    dbsls.dbo.t_ar_ink_master 
                    where   dokument-bayar>0 
                    )a GROUP BY kode_lang
            )a inner join
            (	
                select kode_lang, sum(dokument-bayar) as jt
                FROM
                (
                    select  SUBSTRING(customerid,2,5) kode_lang,                              
                            CASE 
                            WHEN SUBSTRING(no_sales,1,1) = 'R' then dokument * -1 else dokument
                            end as dokument, bayar, tgl_tempo,datediff(day, tgl_tempo, GETDATE()) * -1 as c
                    from    dbsls.dbo.t_ar_ink_master 
                    where   dokument-bayar>0  
                )a  where c<108
                GROUP BY kode_lang
            )b on a.kode_lang = b.kode_lang
            ORDER BY a.kode_lang
        ";
        $query = sqlsrv_query($conn, $sql); 
        
        // $sql = "delete from db_analisis.t_temp_piutang where id = $id";
        // $proses= $this->db->query($sql);
        if ($query) 
        {
            while ($data = sqlsrv_fetch_array($query)){
                $kode_lang =  $data['kode_lang'];
                $saldoakhir = $data['saldoakhir'];
                $jt = $data['jt'];
                $sql = "insert into db_analisis.t_temp_piutang
                        select $kode_lang, $saldoakhir, $jt, $id, '$tgl_created'
                ";
                $proses= $this->db->query($sql);
            }
        }  
    }

    public function get_tanggal_piutang_sds($id){
        $sql_cek = "
            select a.last_updated
            from db_analisis.t_temp_piutang a
            where a.id = $id
            limit 1 ";
        $proses = $this->db->query($sql_cek)->result();
        // var_dump($proses);

        foreach ($proses as $key) {
            $last_updated = $key->last_updated;
        }

        if ($proses) { 
            return $last_updated;            
        }else{
            return '0';
        }
    }

    public function update_piutang_sds($id, $date){
        $sql = "
            select SUBSTR(a.last_updated,1,10) as last_updated
            from db_analisis.t_temp_piutang a
            where id = $id
        ";
        $hasil = $this->db->query($sql)->row();
        $last_updated = $hasil->last_updated;
        $tgl = substr($date, 0,10);
        // var_dump($date);die;
        // var_dump($last_updated);
        // var_dump($tgl);die;
        if ($last_updated != $tgl ) {
            $proses = $this->db->query("delete from db_analisis.t_temp_piutang where id = $id");
            
            $this->get_data_dbsls($date);
        }
    }

    public function tampil_credit_limit()
    {
    $id = $this->session->userdata('id');

    // cek hak akses
    $cek = "select a.wilayah_nocab from mpm.`user` a where id = $id";
    $proses_cek = $this->db->query($cek)->result();

    foreach($proses_cek as $a){
        $nocab = $a->wilayah_nocab;
    }
    // echo "nocab : ".$nocab;
    if ($nocab == '') {
        $useridy = "";
    }else{
        $sql = "            
            select b.id as userid
            from mpm.tbl_tabcomp a INNER JOIN mpm.`user` b
                on a.kode_comp = b.username
            where `status` = 1 and a.nocab in ($nocab)
            GROUP BY concat(a.kode_comp, a.nocab), b.username
        ";
        $proses = $this->db->query($sql)->result();
        foreach($proses as $a){
            $userid[] = ','.$a->userid;
        }
        $userid = implode($userid);
        $useridx=preg_replace('/,/', '', $userid,1);
        $useridy = " and a.userid in ($useridx)";
    }        
       
    $sql = "
            select  * 
            from 
            (
                select  a.*,
                        b.value 
                        
                from    
                (
                    select  if(a.`open` = 0,if(concat(year(a.tglpesan),month(a.tglpesan)) = concat(year(date(now())),month(date(now()))),a.`open`,3),a.`open`) as `open`,
                            a.open_by, c.username,
                            a.open_date,
                            a.id,
                            a.supp,
                            b.company,
                            b.id as userid,
                            b.kode_lang,
                            a.email,a.status,a.status_approval,
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
                    where   a.deleted=0 and datediff(date(now()),date(tglpesan))<=180 $useridy
                    order by id desc 
                    limit 250
                )a
                
                inner join 
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
                select kode_lang, sum(saldoakhir) as saldoakhir, sum(jt) as jt
                FROM
                (
                    select  if(right(a.kode_lang,5) = '20252','20156',if(right(a.kode_lang,5) = '20251','20250',if(right(a.kode_lang,5) = '20268','20256',if(right(a.kode_lang,5) = '20267','20256',right(a.kode_lang,5))))) as kode_lang, 
                            a.saldoakhir, a.jt
                    from    db_analisis.t_temp_piutang a where id = $id
                )a GROUP BY kode_lang                    
            )b on a.kode_lang = b.kode_lang
            left join 
            (
                select  id as userid, kode_lang,cl 
                from    mpm.user
            )c  on a.userid = c.userid
            LEFT JOIN
            (
                select kode_lang, sum(banyak*harga) as total_po
                from 	mpm.po a INNER JOIN mpm.po_detail b
                            on a.id = b.id_ref LEFT JOIN
                        mpm.`user` c 
                            on a.userid = c.id
                where a.deleted = 0 and b.deleted = 0 and year(tglpo) = year(now()) and month(tglpo) = month(now()) and open = 1
                GROUP BY kode_lang
            )d on a.kode_lang = d.kode_lang
            order by id desc
        ";

        // echo "<pre><br><br><br><br><br>";
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

        
        // echo "<pre>";
        // echo "<br><br><br>";
        // print_r($grup_lang);
        // echo "<br>";
        // print_r($kode_lang_x);
        // echo "<br>";
        // print_r($tgl);
        // echo "</pre>";
    
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

        
        // cek hak akses
        $cek = "select a.wilayah_nocab from mpm.`user` a where id = $id";
        // echo "<pre>";
        // print_r($cek);
        // echo "</pre>";
        $proses_cek = $this->db->query($cek)->result();

        foreach($proses_cek as $a){
            $nocab = $a->wilayah_nocab;
        }
        // echo "nocab : ".$nocab;
        if ($nocab == '') {
            $useridy = "";
        }else{
            $sql = "            
                select b.id as userid
                from mpm.tbl_tabcomp a INNER JOIN mpm.`user` b
                    on a.kode_comp = b.username
                where `status` = 1 and a.nocab in ($nocab)
                GROUP BY concat(a.kode_comp, a.nocab), b.username
            ";
            // echo "<pre>";
            // print_r($sql);
            // echo "</pre>";
            $proses = $this->db->query($sql)->result();
            foreach($proses as $a){
                $userid[] = ','.$a->userid;
            }
            $userid = implode($userid);
            $useridx=preg_replace('/,/', '', $userid,1);
            $useridy = " and a.userid in ($useridx)";
        }     
        
        
        $sql = "
        select  * 
        from 
        (
                select  a.*,
                        b.value 
                        
                from    (
                            select  if(a.`open` = 0,if(concat(year(a.tglpesan),month(a.tglpesan)) = concat(year(date(now())),month(date(now()))),a.`open`,3),a.`open`) as `open`,
                                    a.open_by, c.username,
                                    a.open_date,
                                    a.id,
                                    a.supp,
                                    b.company,a.status,a.status_approval,
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
                            where   a.deleted=0 $kl $time and datediff(date(now()),date(tglpesan))<=180 $useridy
                            order by id desc
                            limit 100 
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
            select kode_lang, sum(saldoakhir) as saldoakhir, sum(jt) as jt
            FROM
            (
                select  if(right(a.kode_lang,5) = '20252','20156',if(right(a.kode_lang,5) = '20251','20250',if(right(a.kode_lang,5) = '20268','20256',if(right(a.kode_lang,5) = '20267','20256',right(a.kode_lang,5))))) as kode_lang, 
                        a.saldoakhir, a.jt
                from    db_analisis.t_temp_piutang a where id = $id
            )a GROUP BY kode_lang                       
        )b on a.kode_lang = b.kode_lang
        left join 
        (
            select  id as userid, kode_lang,cl 
            from    mpm.user
        )c  on a.userid = c.userid
        LEFT JOIN
        (
            select kode_lang, sum(banyak*harga) as total_po
            from 	mpm.po a INNER JOIN mpm.po_detail b
                        on a.id = b.id_ref LEFT JOIN
                    mpm.`user` c 
                        on a.userid = c.id
            where a.deleted = 0 and b.deleted = 0 and year(tglpo) = year(now()) and month(tglpo) = month(now()) and open = 1
            GROUP BY kode_lang
        )d on a.kode_lang = d.kode_lang
        ";
        
        // echo "<pre><BR><BR><BR><BR>";
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
    
        // $sql="
        //     select distinct * 
        //     from
        //     (
        //         select  distinct concat('1',grup_lang) grup_lang,
        //                 concat(grup_nama,'-',kode_kota) as grup_nama  
        //         from    pusat.user 
        //         where   grup_lang<>'' and 
        //                 grup_lang<>'00159'   and GRUP_LANG <> '00121'
        //         union all   
        //         select  distinct group_id grup_lang,
        //                 group_descr grup_nama 
        //         from    dbsls.m_customer 
        //         where   group_id<>'' and 
        //                 group_id<>'100159'
        //     )a union all

        //     select kode,nama_lang from mpm.tbl_bantu_pelanggan_po
        //     order by grup_lang = ".'"'.$grup_lang.'" desc,'."grup_nama"
        //     ;

        $sql = "
            select distinct a.grup_lang, a.grup_nama, b.branch_name, b.username, a.nama_customer
            from
            (
                    select  distinct concat('1',grup_lang) grup_lang,
                                    concat(grup_nama,'-',kode_kota) as grup_nama, ''as nama_customer
                    from    pusat.user 
                    where   grup_lang<>'' and 
                                    grup_lang<>'00159'   and GRUP_LANG <> '00121'
                    union all   
                    select  distinct group_id grup_lang,
                                    group_descr grup_nama, nama_customer 
                    from    dbsls.m_customer 
                    where   group_id<>'' and 
                                    group_id<>'100159'
            )a LEFT JOIN 
            (
                select b.kode_lang, b.branch_name, b.username
                from mpm.`user` b
                GROUP BY b.kode_lang
            )b on a.grup_lang = concat('1',b.kode_lang)
            where b.branch_name is not null
            GROUP BY a.grup_lang
            ORDER BY b.branch_name
            ";

        
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
            LEFT JOIN mpm.tabsupp using(supp)
            
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
        $date = date_create(date('Y-m-d H:i:s'));
        date_add($date, date_interval_create_from_date_string('-7 hours'));
        $tgl = date_format($date, 'Y-m-d H:i:s');
        
        $data = array(
                'id'           => $data_segment['id'],
                'open'         => '1',
                'lock'         => '1',
                'open_by'      => $userid,
                'open_date'    => $tgl
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
    public function get_traffic_po()
    {
        $query = "select * from mpm.traffic_po a order by a.id desc limit 1";
        return $this->db->query($query);

    }

    public function insert_traffic_po($user = '', $created_by, $status)
    {
        $created_at = $this->model_outlet_transaksi->timezone();

        $data = [
            "user"          => $user,
            "created_by"    => $created_by,
            "created_at"    => $created_at,
            "status" => $status
        ];

        $insert = $this->db->insert("mpm.traffic_po", $data);
        return $insert;

    }
}