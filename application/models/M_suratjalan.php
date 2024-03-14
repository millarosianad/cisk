<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_suratjalan extends CI_Model 
{  
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
            // echo "<hr>";
            // echo $array_kode[$i]."<br>";
            // echo strlen($array_kode[$i]);

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
                            sum(total_harga+(total_harga*11/100))+a.dp_value as nildok,
                            concat(right(a.no_seri_pajak,8),'/',substr(a.tanggal,6,2),substr(a.tanggal,3,2)) pajak, '".$keterangan."', $userid, no_sales
                    FROM
                    (
                            select no_sales, tgl_periode, dp_value, no_seri_pajak, tanggal
                            FROM    db_temp.t_sales_master a
                            where   right(a.no_seri_pajak,8)=".$nofaktur." and left(a.no_sales,3)='SLS' and 
                                    concat(substr(a.tanggal,3,2),substr(a.tanggal,6,2))=".$bulan." and a.customerid = ".$grup_lang." and a.counter_print > 0
                            ORDER BY tgl_periode DESC
                            limit 1
                    )a INNER JOIN db_temp.t_sales_detail b using(no_sales)
                    group by nomor        
            ";

            $query=$this->db->query($sql);

            // echo "<pre>";
            // print_r($sql);
            // echo "</pre>";
            
        }      

        // die;

        //redirect('all_surat_jalan/view_input_surat_jalan_show');
        //redirect($this->session->userdata('redirect'));
    }

    public function list_pelanggan($dataSegment = ''){

        $grup_lang = $dataSegment['grup_lang'];
        $sql2 ="
            select distinct * 
            from
            (
                  
                select  distinct group_id grup_lang,
                        group_descr grup_nama 
                from    db_temp.t_m_customer 
                where   group_id<>'' and 
                        group_id<>'100159'
            )a 
            union all

            select * from mpm.tbl_bantu_pelanggan_po
            order by grup_lang = ".'"'.$grup_lang.'" desc,'."grup_nama"
        ;

        // echo "<pre>";
        // print_r($sql2);
        // echo "</pre>";

        // die;

        return $this->db->query($sql2,$grup_lang);
    }

    public function list_faktur($dataSegment = '')
    {
        $grup_lang = $dataSegment['grup_lang'];
        $jenis_faktur = $dataSegment['jenis_faktur'];
        if ($jenis_faktur == '0') {
            $jenis_faktur_pilih = "Copy Faktur";
        } elseif ($jenis_faktur == '1') {
            $jenis_faktur_pilih = "Faktur Lunas";
        } else{
            $jenis_faktur_pilih = "x";
        }
        $from = date_format(date_create($dataSegment['from']),"Y/m/d");
        $to = date_format(date_create($dataSegment['to']),"Y/m/d");
        // echo "<pre>";
        // echo "from : ".$from."<br>";
        // echo "to : ".$to;
        // echo "</pre>";
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
                from    db_temp.t_sales_master                 
                inner join db_temp.t_m_customer using(customerid) 
                where group_id = ".$grup_lang." and retur=0 and (date_format(tanggal,'%Y/%m/%d') between '$from' and '$to')
            ) a inner join
            (
                select  right(a.no_seri_pajak,8) as x, 
                                no_sales, 
                                tgl_periode, 
                                dp_value, 
                                no_seri_pajak, 
                                tanggal,
                                customerid
                FROM        db_temp.t_sales_master a
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
        
        //  echo "<pre>";
        //  print_r($sql);
        //  echo "</pre>";    
        // die;
        if($query->num_rows()>0)
        {
           return $query;
        }
        else{
                $message = "Data Tidak Ditemukan!";
                echo "<script type='text/javascript'>alert('$message');
                window.location = 'http://site.muliaputramandiri.com/cisk/surat_jalan';
                </script>"; 
            }
        
    }

    public function proses_delete($id){
        // var_dump($id);die;
        $proses = $this->db->where('id', $id)->delete('pusat.permit_temp');
        if ($proses) {
            return $proses;
        }

    }

    public function proses_delete_surat_jalan($id){
        // echo $id;
        $key = $id;
        // echo $key;

        $userid=$this->session->userdata('id');
        // echo $userid;
        
        $message = "Data Terhapus !";
        echo "<script type='text/javascript'>alert('$message');
        </script>"; 

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

    public function tampil_temp($dataSegment)
    {

        $userid=$this->session->userdata('id');
        $this->db->where('pusat.permit_temp.userid',$userid);           
        $this->db->order_by("pusat.permit_temp.faktur", "asc");
        $hasil = $this->db->get('pusat.permit_temp');

        if ($hasil->num_rows() > 0) 
        {
            return $hasil->result();
        } else {
            redirect('surat_jalan');
        }
    }
    
    public function view_surat_jalan()
    {
        $this->db->select('pusat.permit.id, kode_lang, nama_lang, tanggal, keterangan');
        $this->db->join('pusat.permit_detail', 'pusat.permit.id = pusat.permit_detail.id_ref','inner');
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

    public function proses_save_surat_jalan($dataSegment){
        if ( function_exists( 'date_default_timezone_set' ) )
        date_default_timezone_set('Asia/Jakarta');

        // echo "userid : ".$dataSegment['userid']."<br>";
        // echo "created : ".$dataSegment['created']."<br>";
        // echo "tanggal : ".$dataSegment['tanggal']."<br>";

        $sql='
            select  customerid,group_descr 
            from    db_temp.t_m_customer 
            where group_id="'.$this->uri->segment(3).'"';

        // echo "<pre>";
        // print_r($sql);
        // echo "</pre>";

        $query=$this->db->query($sql);
        $row=$query->row();

        $post['kode_lang']=$row->customerid;
        $post['nama_lang']=$row->group_descr;
        $post['tanggal']=$dataSegment['tanggal'];
        $post['created_by']=$dataSegment['userid'];
        $post['created']=date('Y-m-d H:i:s');
        $message = "Data Tersimpan !";
        echo "<script type='text/javascript'>alert('$message');
        </script>"; 
        // echo "row->customerid : ".$row->customerid."<br>";
        // echo "row->group_descr : ".$row->group_descr."<br>";
        // echo "this->input->post('tanggal') : ".$dataSegment['tanggal']."<br>";
        // echo "created_by : ".$dataSegment['userid']."<br>";
        // echo "created : ".date('Y-m-d H:i:s')."<br>";

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
        // echo "<pre>";
        // print_r($sql);
        // echo "</pre>";
        $sql='delete from pusat.permit_temp where userid='.$dataSegment['userid'];
        $this->db->query($sql);
        if($this->db->trans_status()===FALSE)
        {
            $this->db->trans_rollback();
        }
        else
        {
            $this->db->trans_commit();
            redirect('surat_jalan','refresh');
        }
        
    }

}