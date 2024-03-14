<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_surat_jalan extends CI_Model 
{    
    
    public function view_surat_jalan()
    {
        $this->db->join('pusat.permit_detail', 'pusat.permit.id = pusat.permit_detail.id_ref','inner');
        $this->db->select('pusat.permit.id, kode_lang, nama_lang, tanggal, keterangan');
        $this->db->where('pusat.permit.deleted = 0');           
        $this->db->order_by("pusat.permit.id", "desc");
        $this->db->order_by("keterangan");
        $this->db->group_by("pusat.permit.id");
        $hasil = $this->db->get('pusat.permit',1000);

        if ($hasil->num_rows() > 0) 
        {

            return $hasil->result();
        } else {
            return array();
        }
    }

    public function view_surat_jalan_by_tgl($dataSegment)
    {
        $tgl = $dataSegment['tgl'];
        $format = date('Y-m-d', strtotime($tgl));

        // echo "format : ".$format;
        
        $this->db->join('pusat.permit_detail', 'pusat.permit.id = pusat.permit_detail.id_ref','inner');
        $this->db->select('pusat.permit.id, kode_lang, nama_lang, tanggal, keterangan');
        $this->db->where('pusat.permit.deleted = 0');
        //$this->db->where("tanggal like '%2017-02-21%' "); 
        $this->db->where("tanggal like '".$format.'%'."'");
        $this->db->order_by("pusat.permit.id", "desc");
        $this->db->order_by("keterangan");
        $this->db->distinct();
        $hasil = $this->db->get('pusat.permit');

        if ($hasil->num_rows() > 0) 
        {
            // echo "ada";
            return $hasil->result();
        } else {
            // echo "tidak";
            return array();
        }
    }

    public function list_pelanggan($dataSegment = ''){
    
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
            )a 
            union all

            select * from mpm.tbl_bantu_pelanggan_po
            order by grup_lang = ".'"'.$grup_lang.'" desc,'."grup_nama"
            ;
        /*
        $sql = "
            select kode_lang as grup_lang, nama_lang as grup_nama
            from dbsls.t_inv_do_master
            where kddokjdi ='do'
            GROUP BY kode_lang
            ORDER BY nama_lang
        ";
        */
        /*
        echo "<pre>";
        echo "<br><br>";
        echo "grup_lang : ".$grup_lang;
        print_r($sql);
        echo "</pre>";
        */
        return $this->db->query($sql,$grup_lang);
    }

    public function list_pelanggan_do($dataSegment = ''){
    
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

            select * from mpm.tbl_bantu_pelanggan_po
            order by grup_lang = ".'"'.$grup_lang.'" desc,'."grup_nama"
            ;
        */
        /*$sql = "
            select kode_lang as grup_lang, nama_lang as grup_nama
            from dbsls.t_inv_do_master
            where kddokjdi ='do'
            GROUP BY kode_lang
            ORDER BY nama_lang
        ";*/

        $sql = "
            select a.grup_lang, a.grup_nama, b.grup_lang, b.grup_nama, status_head, b.grup
            FROM
            (
                select  kode_lang as grup_lang, nama_lang as grup_nama
                from    dbsls.t_inv_do_master
                where   kddokjdi ='do'
                GROUP BY kode_lang
                ORDER BY nama_lang
            ) a INNER JOIN 
            (
                select  grup_lang, grup_nama, status_head, grup
                from    mpm.tbl_pelanggan_do
                where   status_head = 1
            )b on a.grup_lang = b.grup_lang

        ";
        
        /*
        echo "<pre>";
        echo "<br><br>";
        echo "grup_lang : ".$grup_lang;
        print_r($sql);
        echo "</pre>";
        */
        
        return $this->db->query($sql,$grup_lang);
    }

    public function getPelanggan($kode)
    {

        $sql='
            select  grup_nama 
            from    pusat.user 
            where   grup_lang=? limit 1';
        $query=$this->db->query($sql,array($kode));
        /*
        echo "<pre>";
        print_r($sql);
        echo "</pre>";
        */
        if($query->num_rows()>0)
        {
            $row=$query->row();
            return $row->grup_nama;
        }
        else
        {
            return false;
        }
    }

    public function list_faktur($dataSegment = '')
    {
        $grup_lang = $dataSegment['grup_lang'];
        //echo "grup lang model function list_faktur : ".$grup_lang."<br>";
        $jenis_faktur = $dataSegment['jenis_faktur'];
        //echo "jenis_faktur model : ".$jenis_faktur."<br>";

        if ($jenis_faktur == '0') {
            //memilih copy faktur
            $jenis_faktur_pilih = "Copy Faktur";
        } elseif ($jenis_faktur == '1') {
            $jenis_faktur_pilih = "Faktur Lunas";
        } else{
            $jenis_faktur_pilih = "x";
        }
        
        
        $sql="
            select tgldokjdi, nodokjdi, a.nomor, no_sales
            from 
            (
                select  tgldokjdi, 
                        concat(nodokjdi,substr(tgldokjdi,6,2) ,substr(tgldokjdi,3,2))as nodokjdi,
                        concat(cast(nodokjdi AS unsigned),'/MPM/',substr(tgldokjdi,6,2),substr(tgldokjdi,3,2)) as nomor 
                from    pusat.fh a inner join pusat.user b using(kode_lang) 
                where   concat('1',grup_lang) =".$grup_lang." 
                
                union all 
                
                select  date_format(tanggal,'%Y-%m-%d'), 
                        concat(right(no_seri_pajak,8),substr(tanggal,6,2),substr(tanggal,3,2))  nodokjdi, 
                        concat(right(no_seri_pajak,8),'/MPM/',substr(tanggal,6,2),substr(tanggal,3,2)) nomor 
                from    dbsls.t_sales_master 
                
                inner join dbsls.m_customer using(customerid) 
                where group_id = ".$grup_lang." and retur=0 and (tanggal like '2019%' or tanggal like '2018%')
            ) a inner join
            (
                select  right(a.no_seri_pajak,8) as x, 
                                no_sales, 
                                tgl_periode, 
                                dp_value, 
                                no_seri_pajak, 
                                tanggal,
                                customerid
                FROM        dbsls.t_sales_master a
                where   customerid = ".$grup_lang." 
                ORDER BY x
            )b on left(a.nomor,8) = x
            where a.nomor not in(
                SELECT  faktur
                from    pusat.permit_detail
                where keterangan = ".'"'.$jenis_faktur_pilih.'"'." and deleted <> '1'
                ORDER BY id_ref desc
            ) and a.nomor not in(
                SELECT  faktur
                from    pusat.permit_temp
                where keterangan = '$jenis_faktur_pilih' 
            )        
            order by nodokjdi desc 
                ";
        
       
        $query=$this->db->query($sql);
        
         //echo "<pre>";
         //print_r($sql);
         //echo "</pre>";    
        
        if($query->num_rows()>0)
        {
           return $query;
        }
        else{
                redirect('all_surat_jalan/tidak_ada_data','refresh');   
            }
        
    }

    public function proses_delete($id){
        
        $this->db->where('id', $id)
                 ->delete('pusat.permit_temp');
    }

    public function show_add()
    {
        $sql='select * from pusat.permit_temp where userid='.$this->session->userdata('id');
                $query=$this->db->query($sql);
                if($query->num_rows()>0)
                {
                    $this->load->library('table');
                    $this->table->set_empty('0');

                    $this->table->set_empty('0');
                    $this->table->set_template($this->tmpl);
                    $this->table->set_caption('Surat Jalan');
                    $this->table->set_heading('Faktur No.', 'Rp,-','Faktur Pajak No','Keterangan','Delete');
                    foreach ($query->result() as $value)
                    {
                        $js=array('onclick'=>"return confirm('Are you sure you want to delete?')");

                        $this->table->add_row(
                                $value->faktur
                                ,'<div  style="text-align:right">'.number_format($value->nildok,2).'</div>'
                                ,$value->pajak
                                ,$value->keterangan
                                ,'<div style="text-align:center">'.anchor('trans/permit/delete_temp/'.$value->id,img($this->image_properties_del),$js).'</div>'
                                );
                    }

                    $this->output_table .= $this->table->generate();
                    $this->output_table .= '<br />';
                    $this->table->clear();
                    $query->free_result();
                    $this->output_table;
                    return $this->output_table;
                }
                else
                {
                    return FALSE;
                }

    }

    public function get_faktur($data,$kode="")
    {
        //$this->session->set_userdata('redirect', $_SERVER['HTTP_REFERER']);
        //$kodefikasi = $this->session->userdata('client');
        
        $userid=$this->session->userdata('id');
        $keterangan = $data['keterangan'];
        $kode = $data['code'];
        $grup_lang = $data['grup_lang'];
        
        // echo "<pre>";
        // echo "model : keterangan : ".$keterangan."<br>";
        // echo "model : userid : ".$userid."<br>";     
        // echo "model : kode : ".$kode."<br>";   
        // echo "model : grup_lang : ".$grup_lang."<br>";   
        // echo "</pre>";

        // $kodefikasi = $this->uri->segment(3);
        // echo "<br>model : kodefikasi : ".$kodefikasi;
        // echo "<br>model : kode : ".$kode;
        // echo "<br>model : jumlah panjang string : ".strlen($kode)."<br>";

        //mengubah string menjadi array
        $array_kode = explode (",",$kode);
        // echo "<pre>";
        // print_r($array_kode);
        // echo "</pre>";

        //menghitung jumlah array
        $jumlah_array_kode = count($array_kode);
        // echo "<br>Jumlah kode yang dipilih : ".$jumlah_array_kode."<br>";

        for ($i=0; $i < $jumlah_array_kode; $i++)
        { 
            //echo "<hr>";
            //echo $array_kode[$i]."<br>";
            //echo strlen($array_kode[$i]);

            if (strlen($array_kode[$i])==12) //menghitung panjang kode
            {
                //echo "<br>panjang kode = 12<br>";
                $nofaktur=substr($array_kode[$i],0,8);
                $bulan=substr($array_kode[$i],10,2).substr($array_kode[$i],8,2);
                // echo "no faktur : ".$nofaktur."<br>";
                // echo "bulan : ".$bulan."<br>";
                   
            }
            else
            {
                //echo "<br>panjang kode else dari 12<br>";
                $nofaktur=substr($array_kode[$i],0,6);
                $bulan=substr($array_kode[$i],8,2).substr($array_kode[$i],6,2);
                // echo "no faktur : ".$nofaktur."<br>";
                // echo "bulan : ".$bulan."<br>";
            }
        
            $sql="
                    insert into pusat.permit_temp (id,faktur,nildok,pajak,keterangan,userid,no_sales)
                    select  '',concat(right(a.no_seri_pajak,8),'/MPM/',substr(a.tanggal,6,2),substr(a.tanggal,3,2)) nomor,
                            sum(total_harga+(total_harga/10))+a.dp_value as nildok,
                            concat(right(a.no_seri_pajak,8),'/',substr(a.tanggal,6,2),substr(a.tanggal,3,2)) pajak, '".$keterangan."', $userid, no_sales
                    FROM
                    (
                            select no_sales, tgl_periode, dp_value, no_seri_pajak, tanggal
                            FROM    dbsls.t_sales_master a
                            where   right(a.no_seri_pajak,8)=".$nofaktur." and left(a.no_sales,3)='SLS' and 
                                    concat(substr(a.tanggal,3,2),substr(a.tanggal,6,2))=".$bulan." and a.customerid = ".$grup_lang."
                            ORDER BY tgl_periode DESC
                            limit 1
                    )a INNER JOIN dbsls.t_sales_detail b using(no_sales)
                    group by nomor        
            ";

            $query=$this->db->query($sql);
            
            // echo "<pre>";
            // print_r($sql);
            // echo "</pre>";
            
        }      

        //redirect('all_surat_jalan/view_input_surat_jalan_show');
        //redirect($this->session->userdata('redirect'));
    }

    public function proses_delete_surat_jalan($id){
        echo $id;
        $key = $id;
        echo $key;

        $userid=$this->session->userdata('id');
        echo $userid;

        $this->db->trans_begin();
        $sql='update pusat.permit set deleted = 1,modified_by='.$userid.',modified="'.date('Y-m-d H:i:s').'" where id='.$key;
        $this->db->query($sql,array($key));
        $sql='update pusat.permit_detail set deleted=1 where id_ref='.$key;
        $this->db->query($sql,array($key));
        
        $this->db->trans_complete();
        if($this->db->trans_status()===FALSE)
        {
            $this->db->trans_rollback();
        }
        else
        {
            $this->db->trans_commit();
        }
        
    }

    public function tambah_faktur_temp()
    {
        echo "x";

        $row=$this->get_faktur($this->input->post('faktur'));
                
        $faktur = $this->input->post('faktur');
        echo "<pre>";
        print_r($faktur);
        echo "</pre>";

        /*
        
        $post['faktur']=$row->nomor;
        $post['nildok']=$row->nildok;
        $post['keterangan']=$this->input->post('keterangan');
        $post['pajak']=$row->pajak;
        $post['userid']=$userid;
        

        
        $this->db->trans_begin();
        $sql=$this->db->insert('pusat.permit_temp',$post);
        if($this->db->trans_status()===FALSE)
        {
            $this->db->trans_rollback();
        }
        else
        {
            $this->db->trans_commit();
        }

        */
    }

    public function tampil_temp($dataSegment)
    {

        $userid=$this->session->userdata('id');
        $this->db->where('pusat.permit_temp.userid',$userid);           
        $this->db->order_by("pusat.permit_temp.id", "asc");
        $hasil = $this->db->get('pusat.permit_temp');

        if ($hasil->num_rows() > 0) 
        {
            return $hasil->result();
        } else {
            return array();
        }
    }

    public function proses_save_surat_jalan($dataSegment){
        if ( function_exists( 'date_default_timezone_set' ) )
        date_default_timezone_set('Asia/Jakarta');

        echo "userid : ".$dataSegment['userid']."<br>";
        echo "created : ".$dataSegment['created']."<br>";
        echo "tanggal : ".$dataSegment['tanggal']."<br>";

        $sql='
            select  customerid,group_descr 
            from    dbsls.m_customer 
            where group_id="'.$this->uri->segment(3).'"';

        echo "<pre>";
        print_r($sql);
        echo "</pre>";

        $query=$this->db->query($sql);
        $row=$query->row();

        $post['kode_lang']=$row->customerid;
        $post['nama_lang']=$row->group_descr;
        $post['tanggal']=$dataSegment['tanggal'];
        $post['created_by']=$dataSegment['userid'];
        $post['created']=date('Y-m-d H:i:s');
           
        echo "row->customerid : ".$row->customerid."<br>";
        echo "row->group_descr : ".$row->group_descr."<br>";
        echo "this->input->post('tanggal') : ".$dataSegment['tanggal']."<br>";
        echo "created_by : ".$dataSegment['userid']."<br>";
        echo "created : ".date('Y-m-d H:i:s')."<br>";

        $this->db->trans_begin();
        $insert=$this->db->insert('pusat.permit',$post);
        $sql='select id from pusat.permit where created="'.$post['created'].'"';
        $query=$this->db->query($sql);
        $row=$query->row();
        $id_ref=$row->id;
        $sql="
            insert into pusat.permit_detail(id_ref,faktur,nildok,pajak,keterangan,no_sales) 
            select  ".$id_ref.",faktur,nildok,pajak,keterangan, no_sales
            from    pusat.permit_temp 
            where   userid=".$dataSegment['userid'];
        $this->db->query($sql);
        echo "<pre>";
        print_r($sql);
        echo "</pre>";
        $sql='delete from pusat.permit_temp where userid='.$dataSegment['userid'];
        $this->db->query($sql);
        if($this->db->trans_status()===FALSE)
        {
            $this->db->trans_rollback();
        }
        else
        {
            $this->db->trans_commit();
            redirect('all_surat_jalan','refresh');
        }
        
    }

    public function status($dataSegment){

        //echo "grup_langnya : ".$dataSegment['grup_lang'];
    }

    public function list_do($data = '')
    {
        $grup_lang = $data['grup_lang'];
        $tahun = $data['tahun'];
        $bulan = $data['bulan'];

        /*cek grup*/
            $this->db->where('grup_lang = '.'"'.$grup_lang.'"');
            $query = $this->db->get('mpm.tbl_pelanggan_do');
            foreach ($query->result() as $row) {
                $grup = $row->grup;
                //echo "nocab : ".$wilayah_nocab."<br>";
                //return $grup;
            }

        if ($grup == null || $grup == '' ) {

            $code = $grup_lang;
            

        }else{

            /*list grup_lang*/
                $this->db->where('grup = '.'"'.$grup.'"');
                $query = $this->db->get('mpm.tbl_pelanggan_do');
                foreach ($query->result() as $row) {
                    $list_grup_lang[] = $row->grup_lang.',';
                    //echo $list_grup_lang."<br>";
                    //return $grup;
                }

                $kode_lang = implode($list_grup_lang).',';
                //echo $kode_lang."<br>";

                $code=preg_replace('/,,/', '', $kode_lang,1);
                echo $code;


        }

        /*$sql="
                select * 
                from dbsls.t_inv_do_master
                where   kddokjdi ='do' and year(tgldokjdi) = '$tahun' and 
                        kode_lang = '$grup_lang' and MONTH(tgldokjdi) in ($bulan) and nodokacu not in (SELECT `do` from pusat.rekap_do)
                ORDER BY nodokacu
            ";*/

        $sql="
                select * 
                from dbsls.t_inv_do_master
                where   kddokjdi ='do' and year(tgldokjdi) = '$tahun' and 
                        kode_lang in ($code) and MONTH(tgldokjdi) in ($bulan) and nodokacu not in (SELECT `do` from pusat.rekap_do)
                ORDER BY MONTH(tgldokjdi)
            ";

        /*
        $sql="
            select * 
            from dbsls.t_inv_do_master
            where   kddokjdi ='do' and year(tgldokjdi) = '$tahun' and 
                    nama_lang like '%$grup_lang%' and MONTH(tgldokjdi) in ($bulan) and nodokacu not in (SELECT `do` from pusat.rekap_do)
            ORDER BY nodokacu
        ";
        */
        
        $query=$this->db->query($sql);
        
        /*
        echo "<pre>";
        echo "grup : ".$grup."<br>";
        print_r($sql);
        echo "</pre>";    
        */
        if($query->num_rows()>0)
        {
           return $query;
        }
        else{
                //redirect('all_surat_jalan/tidak_ada_data_do','refresh');   
                echo "tidak ditemukan";
            }
        
    }



    public function rekap_do($dataSegment,$kode="")
    {
        //$this->session->set_userdata('redirect', $_SERVER['HTTP_REFERER']);
        //$kodefikasi = $this->session->userdata('client');
        
        $userid=$this->session->userdata('id');
        
        $kode = $dataSegment['code'];
        $tahun = $dataSegment['tahun'];
        $bulan = $dataSegment['bulan'];
        $created = date('Y-m-d H:i:s');
        /*
        echo "kode : ".$kode."<br>";  
        echo "userid : ".$userid."<br>";       
        echo "tahun : ".$tahun."<br>"; 
        echo "bulan : ".$bulan."<br>";  
        */
        $kodefikasi = $this->uri->segment(3);
        /*
        echo "<br>kodefikasi : ".$kodefikasi;
        echo "<br>kode : ".$kode;
        echo "<br>jumlah panjang string : ".strlen($kode)."<br>";
        */
        //mengubah string menjadi array
        $array_kode = explode (",",$kode);
        /*
        echo "<pre>";
        print_r($array_kode);
        echo "</pre>";
        */
        //menghitung jumlah array
        $jumlah_array_kode = count($array_kode);
        //echo "<br>Jumlah kode yang dipilih : ".$jumlah_array_kode."<br>";
        
        for ($i=0; $i < $jumlah_array_kode; $i++)
        { 
            
            $sql = "
                insert into pusat.rekap_do
                select '', '$array_kode[$i]', '$tahun', '$bulan', '$kodefikasi', '$created'
            ";

            $query=$this->db->query($sql);
            
            echo "<pre>";
            print_r($sql);
            echo "</pre>";
            
        }
        

    }

     public function list_dp($dataSegment = ''){
    
    //echo "<br>ini model list_pelanggan<br>";
    $grup_lang = $dataSegment['grup_lang'];
        
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
            where grup_lang = $grup_lang"
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

    public function tampil_do($dataSegment){

        $grup_lang = $dataSegment['grup_lang'];
        //echo "grup_lang : ".$grup_lang;

        $this->db->where("pusat.rekap_do.grup_lang",$grup_lang);
        $this->db->order_by("pusat.rekap_do.id_rekap_do", "desc");
        $hasil = $this->db->get('pusat.rekap_do');

        if ($hasil->num_rows() > 0) 
        {
            return $hasil->result();
        } else {
            return array();
        }
    }

    public function proses_delete_do($id){
        //echo "id : ".$id;
        $this->db->where('id_rekap_do', $id)
                 ->delete('pusat.rekap_do');

    }

    public function export_do($dataSegment){

        $grup_lang = $dataSegment['grup_lang'];
        $tahun = $dataSegment['tahun'];
        $bulan = $dataSegment['bulan'];
        //echo "grup_lang : ".$grup_lang;
        $kode = $dataSegment['kode'];
        //echo "kode : ".$kode;
        
        $sql="
                select nodokacu,month(tgldokjdi) as bulan, tgldokjdi
                from dbsls.t_inv_do_master
                where   kddokjdi ='do' and year(tgldokjdi) = '$tahun' and 
                        kode_lang in ($kode) and MONTH(tgldokjdi) in ($bulan) and nodokacu not in (SELECT `do` from pusat.rekap_do)
                ORDER BY bulan,nodokacu
            ";
        
        //echo "<pre>";
        //print_r($sql);
        //echo "</pre>";
        
        $query=$this->db->query($sql);

        if($query->num_rows()>0)
        {
           return $query;
           //echo "ditemukan";
        }
        else{
                //redirect('all_surat_jalan/tidak_ada_data_do','refresh');   
                //echo "tidak ditemukan";
            }
    }

    public function list_do_group($data)
    {
        $grup_lang = $data['grup_lang'];
        //echo "grup lang ; ".$grup_lang;

        /*cek grup*/
            $this->db->where('grup_lang = '.'"'.$grup_lang.'"');
            $query = $this->db->get('mpm.tbl_pelanggan_do');
            foreach ($query->result() as $row) {
                $grup = $row->grup;
                //echo "nocab : ".$wilayah_nocab."<br>";
                //return $grup;
            }

        if ($grup == null || $grup == '' ) {

            $code = $grup_lang;
            

        }else{

            /*list grup_lang*/
                $this->db->where('grup = '.'"'.$grup.'"');
                $query = $this->db->get('mpm.tbl_pelanggan_do');
                foreach ($query->result() as $row) {
                    $list_grup_lang[] = $row->grup_lang.',';
                    //echo $list_grup_lang."<br>";
                    //return $grup;
                }

                $kode_lang = implode($list_grup_lang).',';
                //echo $kode_lang."<br>";

                $code=preg_replace('/,,/', '', $kode_lang,1);
                //echo "kodenya : ".$code;


        }

        return $code;
    }


}