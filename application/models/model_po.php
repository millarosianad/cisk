<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_po extends CI_Model 
{
    // public function __construct(){
    //     if ( function_exists( 'date_default_timezone_set' ) )
    //     date_default_timezone_set('Asia/Jakarta'); 
    // }

	public function update_stock_akhir($data){

        $this->load->helper('url');
        $userid=$this->session->userdata('id');
		$id_po = $data['id_po'];
        $supp_po = $data['supp_po'];

        /* cari nilai kode_comp */            
         	$this->db->join('po', 'po.id = po_detail.id_ref','left');
         	$this->db->join('user', 'user.id = po.userid','left');
            $this->db->where('id_ref = '.'"'.$id_po.'"');
            $query = $this->db->get('mpm.po_detail');
            foreach ($query->result_array() as $row) {
                $kodeprod[] =  $row['kodeprod'];
                $kode_comp = $row['username'];
                $tahun = $row['tglpesan'];            
                //print_r($x);
			}
			$jumlah_array = count($kodeprod);
			echo "<br>jumlah kodeprod : ".$jumlah_array."<br>";
			
			print_r($kodeprod);

			$convert = implode(",",$kodeprod);
			echo "<br>hasilnya : ".$convert;

			$kode_compx = $kode_comp; 
			echo "kode_compx  : ".$kode_compx;
			$tahunx = substr($tahun, 0,4);

        /* end cari nilai kode_comp */

        /* cari nilai nocab */            
            $this->db->where('kode_comp = '.'"'.$kode_compx.'"');
            $query = $this->db->get('mpm.tbl_tabcomp');
            foreach ($query->result() as $row) {
                $nocab = $row->nocab;
			}
        /* end cari nilai nocab */


        $tahun = date('Y');

        //$sql='select max(bulan) as bulan from data'.$year.'.st where bulan='.date('m');
        $sql = "
                select bulan
                from data$tahun.fi
                where nocab = ".'"'.$nocab.'"'."
                ORDER BY bulan desc
                limit 1
        ";

        //echo "<pre>";
        //print_r($sql);
        
        $query = $this->db->query($sql);
        $row=$query->row();
        $bulan=$row->bulan;

        //print_r($query);
        //echo "<br>bulan terbesar di data2017.fi :";
        //print_r($bulan);
        //echo "<br><br><br>";
        

        $tanggal = "$tahun-$bulan-01";
        $date[1]=date('Y-m-d',strtotime('-1 month',strtotime($tanggal)));
        $date[2]=date('Y-m-d',strtotime('-2 month',strtotime($tanggal)));
        $date[3]=date('Y-m-d',strtotime('-3 month',strtotime($tanggal)));
        
        //print_r($tanggal);
        //echo "<br>";
        //print_r($date);

        $data_fi_ri = "
                select  nocab, kode_comp, blndok, banyak, tot1, kodeprod, namaprod
                from    data".date('Y',strtotime($date[1])).".fi
                where   kodeprod in (".$convert.") and `nodokjdi` <> 'XXXXXX' and bulan = ".date('m',strtotime($date[1]))." and nocab = ".'"'.$nocab.'"'."

                union all

                select  nocab, kode_comp, blndok, banyak, tot1, kodeprod, namaprod
                from    data".date('Y',strtotime($date[1])).".ri
                where   kodeprod in (".$convert.") and `nodokjdi` <> 'XXXXXX' and bulan = ".date('m',strtotime($date[1]))." and nocab = ".'"'.$nocab.'"'."
        ";

        for($i=2;$i<=3;$i++)
        {
             $data_fi_ri.=" 
                union all

                select  nocab, kode_comp, blndok, banyak, tot1, kodeprod, namaprod
                from    data".date('Y',strtotime($date[$i])).".fi
                where   kodeprod in (".$convert.") and `nodokjdi` <> 'XXXXXX' and bulan = ".date('m',strtotime($date[$i]))." and nocab = ".'"'.$nocab.'"'."

                union all

                select  nocab, kode_comp, blndok, banyak, tot1, kodeprod, namaprod
                from    data".date('Y',strtotime($date[$i])).".ri
                where   kodeprod in (".$convert.") and `nodokjdi` <> 'XXXXXX' and bulan = ".date('m',strtotime($date[$i]))." and nocab = ".'"'.$nocab.'"'."
            ";
        }
        //print_r($data_fi_ri);

        $data_sum = "
                sum(if(blndok=".date('m',strtotime($date[1])).", banyak, 0)) as 'unit".date('m',strtotime($date[1]))."',
        ";
        for($i=2;$i<=2;$i++)
        {
             $data_sum.=" 
                sum(if(blndok=".date('m',strtotime($date[$i])).", banyak, 0)) as 'unit".date('m',strtotime($date[$i]))."',
            ";
        }
        $data_sum_last = "
                sum(if(blndok=".date('m',strtotime($date[3])).", banyak, 0)) as 'unit".date('m',strtotime($date[3]))."'
        ";


        $data_untuk_pembagi = "
                sum(if(unit".date('m',strtotime($date[1]))." <> 0,1,0))+
        ";
        for($i=2;$i<=2;$i++)
        {
             $data_untuk_pembagi.=" 
                sum(if(unit".date('m',strtotime($date[$i]))." <> 0,1,0))+
            ";
        }
        $data_untuk_pembagi_last = "
                sum(if(unit".date('m',strtotime($date[3]))." <> 0,1,0)) as pembagi
        ";

$query = "
delete from mpm.tbl_po_detail_doi
where userid = $userid
";

$hasil = $this->db->query($query);

//echo "<pre>";
//print_r($query);
//echo "</pre>";

        $sql = "
insert into mpm.tbl_po_detail_doi            
select  $id_po, a.nocab, a.kodeprod, namaprod,  
	sum(unit/pembagi) as avg_unit_3_bln, 0, $userid 
from
(
    select  nocab, kodeprod, namaprod, unit, val, 
            ".$data_untuk_pembagi."
            ".$data_untuk_pembagi_last."
    from
    (
        select  nocab, kode_comp, kodeprod, namaprod, 
                SUM(banyak) as unit, sum(tot1) as val, 
                ".$data_sum."
                ".$data_sum_last."  
        FROM        
        (
            ".$data_fi_ri."
        )a GROUP BY nocab, kodeprod
    )a GROUP BY nocab, kodeprod
)a GROUP BY kodeprod
        ";        

        $sql_insert = $this->db->query($sql);
        //echo "<pre>";
        //print_r($sql);
        //echo "</pre>";


$query = "
update mpm.po_detail a
set rata3bulan = 
(
    select  avg_unit_3bln
    from        mpm.tbl_po_detail_doi b
    where   a.id_ref = b.id and a.kodeprod = b.kodeprod
)
where a.id_ref = $id_po 
";

        $hasil = $this->db->query($query);

        //echo "<pre>";
        //print_r($query);
        //echo "</pre>";

        	for ($i=0; $i < $jumlah_array; $i++)
        	{
$query = "
update mpm.po_detail
set stock_akhir = 
(
	select  sum(if(kode_gdg='PST',
			((Saldoawal+masuk_pbk+masuk_supp+retur_sal+bPinjam+kvretur+kr_gudang+retur_depo)-
			(sales+pinjam+minta_depo+kvbeli+kr_min)-(rusak+sisih)),(Saldoawal+masuk_pbk+retur_sal+kvretur+bPinjam+tukar_msk+msk_gddepo) -
			(sales+Pinjam+kvbeli+retur_depo+tukar_klr+klr_gddepo))) as stok
	FROM    data$tahunx.st a
	where   a.kodeprod in ($kodeprod[$i]) AND 
			kode_gdg != ''  and nocab = "."'".$nocab."'"."
			and	substr(bulan, 3) = (
				select max(substr(bulan, 3)) 
				from		data$tahunx.st
				where		nocab = "."'".$nocab."'"."
			)
	GROUP BY    nocab,bulan,kodeprod
	ORDER BY    nocab
)where id_ref = $id_po and kodeprod = $kodeprod[$i]

";

			$hasil = $this->db->query($query);
			//echo $hasil;

			//echo "<pre>";
			//print_r($query);
			//echo "</pre>";
			//echo "<hr>";

        	}redirect('trans/po/show_detail/'.$id_po.'/'.$supp_po);
            
	}

    public function getpo_ex($data){

        $kode_lang = $data['kode_lang'];
        $id = $this->session->userdata('id');

        $sql = "
            select  a.id, a.userid, a.company, a.created, a.created_by, a.nopo, c.no_sj, a.tglpo, a.alamat, 
                    a.tipe, a.tglpesan, b.username, b.kode_lang 
            from    mpm.po a LEFT JOIN mpm.`user` b 
                        on a.userid = b.id
            LEFT JOIN 
            (
                select  *
                from        dbsls.t_inv_master
                where   status_faktur = 1 and year(tgl_sj) ='2018' 
            )c on a.nopo = c.from_po  
            where   a.userid = $id and a.deleted = 0 and a.`open` = 1 and year(tglpesan) = 2018 and 
                    month(tglpesan) not in (1,2,3,4,5,6)
            ORDER BY a.id desc
        ";

        echo "<pre>";
        print_r($sql);
        echo "</pre>";
        
        $hasil = $this->db->query($sql);
        if ($hasil->num_rows() > 0) {
            return $hasil->result();
        } else {
            return array();
        }


        echo "<pre>";
        print_r($kode_lang);
        print_r($sql);
        echo "</pre>";
    }


    public function getpo($data){

        $kode_lang = $data['kode_lang'];
        $id = $this->session->userdata('id');
        
        //mengambil tahun saat ini 
        $tahun_sekarang= date('y');
        $bulan_sekarang= date('m');
        $bulan_sebelumnya= date('m')-1;

        $sql_del = "
            delete from db_po.t_temp_history_po
            where userid = $id
        ";
        $proses_del = $this->db->query($sql_del);

        $sql_ins = "
            insert into db_po.t_temp_history_po
            select      id, userid, company, email, a.nopo, a.supp, a.tipe, a.tglpesan, a.tglpo, `open`/* open_date*/,  alamat
            from        mpm.po a
            where       userid = $id and year(tglpo) = 20$tahun_sekarang and month(tglpo) in ($bulan_sekarang,$bulan_sebelumnya) and deleted = 0 and nopo not like '/mpm%'
            ORDER BY    id desc
        ";
        $proses_ins = $this->db->query($sql_ins);

        $sql_del_do_master = "
            delete from db_po.t_temp_do_master
            where kode_lang ='1$kode_lang' 
        ";
        $proses_del_do_master = $this->db->query($sql_del_do_master);

        $sql_ins_do_master = "
            insert into db_po.t_temp_do_master
            select  a.ref, a.nodokacu, a.tgldokjdi, kode_lang, nama_lang 
            from    dbsls.t_inv_do_master a 
            where   year(a.tgldokjdi) = '2018' and /*month(a.tgldokjdi) in (10) and */
                            a.nodokacu not like '%xxxx%' and kode_lang ='1$kode_lang' 
                            and a.kddokjdi ='do' and ref is not null
            ORDER BY day(a.tgldokjdi) desc
        "; 

        $proses_ins_do_master = $this->db->query($sql_ins_do_master);

        $sql_del_inv_master = "
            delete from db_po.t_temp_inv_master
            where customerid ='1$kode_lang' 
        ";
        $proses_del_inv_master = $this->db->query($sql_del_inv_master);

        $sql_ins_inv_master = "
            insert into db_po.t_temp_inv_master
            select  a.from_po, a.no_sj, a.no_inv, no_sales, a.tgl_terima,a.tgl_sj, customerid
            from    dbsls.t_inv_master a
            where   year(tgl_terima) = 2018 and customerid = '1$kode_lang' and status_faktur = 1
            ORDER BY day(tgl_terima) desc
        ";

        $proses_ins_inv_master = $this->db->query($sql_ins_inv_master);

        //update tabel t_temp_inv_master
        $sql_upd_inv_master = "
            update  db_po.t_temp_inv_master a INNER JOIN db_po.t_temp_do_master c on a.from_po = c.nodokacu
            set a.from_po = (
                    select  b.ref
                    from    db_po.t_temp_do_master b
                    where   kode_lang = '1$kode_lang' 
            )
        ";
        
        $proses_upd_inv_master = $this->db->query($sql_upd_inv_master);




        $sql_del_konf_po = "
            delete from db_po.t_konf_po
            where userid = '$id'
        ";
        $proses_del_konf_po = $this->db->query($sql_del_konf_po);

        $sql_ins_konf_po = "
            insert  into db_po.t_konf_po
            select  a.nopo, a.tglpo, b.no_sj, b.no_sales, b.tgl_sj, '$id','',''
            from    mpm.po a left JOIN db_po.t_temp_inv_master b
                        on (a.nopo = b.from_po) or (substr(a.nopo,1,6) = b.from_po and month(a.tglpo) = month(b.tgl_sj))
            where   a.deleted = 0 and a.userid = '$id' and a.nopo not like '/MPM%' 
            ORDER BY a.tglpo desc

        ";

        $proses_ins_konf_po = $this->db->query($sql_ins_konf_po);
        /*
        $sql_update_po = "
            update  mpm.po a
            set a.nodo = (
                select  no_sj
                from        db_po.t_konf_po b
                where       a.nopo = b.nopo and b.kode_lang = '1$kode_lang' 
            ), a.tgldo = (
                select  tgl_sj
                from        db_po.t_konf_po b
                where       a.nopo = b.nopo and b.kode_lang = '1$kode_lang' 
            ), a.no_sales = (
                select  no_sales
                from        db_po.t_konf_po b
                where       a.nopo = b.nopo and b.kode_lang = '1$kode_lang' 
            )
            where       a.userid = '$id' and a.deleted = 0 and a.nodo is null
            ORDER BY a.id desc, a.tglpo desc
            limit 100
        ";

        $proses_upd_po = $this->db->query($sql_update_po);
        */
        $sql_view_po = "
            select  a.tglpo, a.nopo, a.tgl_sj, a.no_sj, a.no_sales
            from    db_po.t_konf_po a 
            where   a.userid = '$id'
            order by a.tglpo desc
        ";

        $proses_view_po = $this->db->query($sql_view_po);
        /*
        echo "<pre>";
        echo "kode_lang : ".$kode_lang;
        print_r($sql_del);
        print_r($sql_ins);
        print_r($sql_del_do_master);
        print_r($sql_ins_do_master);
        print_r($sql_del_inv_master);
        print_r($sql_ins_inv_master);
        print_r($sql_upd_inv_master);
        print_r($sql_del_konf_po);
        print_r($sql_ins_konf_po);
        //print_r($sql_update_po);
        print_r($sql_view_po);
        echo "</pre>";
        */
        if ($proses_view_po->num_rows() > 0) {
            return $proses_view_po->result();
        } else {
            return array();
        }

    }

    

    public function getpo_deltomed(){

        $view_po = "
        select a.id, a.nopo, a.tglpo, a.company, a.email, a.alamat, a.tipe, a.tgl_kirim
        from mpm.po a 
        where a.supp ='001' and a.deleted = 0 and a.nopo not like '/mpm%' 
        ORDER BY a.id desc
        limit 100
        ";
        $hasil = $this->db->query($view_po);
        /*
        echo "<pre>";
        print_r($view_po);
        echo "</pre>";
        */
        if ($hasil->num_rows() > 0) {
            return $hasil->result();
        } else {
            return array();
        }

    }

    public function getpo_deltomed_per_id($data){

        $view_po = "
        select a.id, a.nopo, a.tglpo, a.company, a.email, a.alamat, a.tipe, a.tgl_kirim
        from mpm.po a 
        where a.supp ='001' and a.deleted = 0 and a.nopo not like '/mpm%' and id = ".$data['id_po']."
        ORDER BY a.id desc
        ";

        $hasil = $this->db->query($view_po);

        if ($hasil->num_rows() > 0) {
            return $hasil->result();
        } else {
            return array();
        }

    }

    public function getproduk_deltomed_per_id($data){

        $view_po = "
        select b.kodeprod,c.dl_kodeprod,c.dl_description, b.namaprod, b.banyak
        from mpm.po a INNER JOIN mpm.po_detail b
                    on a.id = b.id_ref
                            LEFT JOIN db_produk.tbl_produk c
                                on b.kodeprod = c.kodeprod
        where a.supp ='001' and a.deleted = 0 and a.nopo not like '/mpm%' and a.id = ".$data['id_po']."
        ORDER BY a.id desc
        ";

        $hasil = $this->db->query($view_po);

        if ($hasil->num_rows() > 0) {
            return $hasil->result();
        } else {
            return array();
        }

    }

    public function update_tgl_kirim($data){

        $tgl=trim($data['tgl_kirim']);//$dob1='dd/mm/yyyy' format
        $tgl_convert=strftime('%Y-%m-%d',strtotime($tgl));

        if($tgl_convert == '1970-01-01'){
            redirect('all_po/get_po');
        }

        $view_po = "
        update mpm.po
        set tgl_kirim = '".$tgl_convert."'
        where id = ".$data['id_po']."
        ";
        /*
        $insert_log = "
            insert db_po.log_update_tgl_kirim
            select 
        ";
        */
        /*
        echo "<pre><br><br>";
        print_r($view_po);
        echo "</pre>";
        */
        $hasil = $this->db->query($view_po);

        if ($hasil == 0) {
            //echo "gagal";
        } else {
            //echo "success";
            redirect('all_po/get_po');
        }

    }


    public function show_po($data){

        $kode_lang = $data['kode_lang'];
        $id = $this->session->userdata('id');
        
        //mengambil tahun saat ini 
        $tahun_sekarang= date('y');
        $bulan_sekarang= date('m');
        $bulan_sebelumnya= date('m')-1;

        /* mencari max nilai kolom pk */
        $this->db->select_max('pk');
        $this->db->where('userid',$id);
        $query = $this->db->get('db_po.t_do');
        foreach ($query->result() as $row) {
            $max_pk = $row->pk + 1;
            $pk = $row->pk;
        }


        $sql_ins_do = "
            insert into db_po.t_do
            select  c.no_sj,c.tgl_sj,c.no_sales, c.no_inv, a.id as id_po,$id ,d.`status` as `status`, $max_pk
            from    mpm.po a 
                    LEFT JOIN 
                    (
                        select a.no_sj, a.from_po, a.no_inv, a.no_sales, a.tgl_sj
                        from db_po.t_temp_inv_master a
                        where a.customerid ='1$kode_lang'
                    )c on (a.nopo = c.from_po or substr(a.nopo,1,6) = c.from_po) and (year(c.tgl_sj) = year(a.tglpo) and month(c.tgl_sj) = month(a.tglpo)) 
                    LEFT JOIN (
                        select *
                        from db_po.t_do
                        where userid = '$id' and `status` = 1 and pk = '$pk'
                )d on c.no_sales = d.no_sales
            where   a.userid = '$id' and a.deleted = 0 and a.open = 1 and a.nopo not like '/MPM%'
            order by a.tglpo desc
        ";

        $proses_ins_do = $this->db->query($sql_ins_do);

        $sql_view_do = "
            select b.nopo as nopo, b.tglpo, a.no_do, a.tgl_do, a.no_sales, a.no_inv, a.id_po, pk, c.NAMASUPP as namasupp, b.tipe, a.`status`
            from db_po.t_do a LEFT JOIN mpm.po b
                on a.id_po = b.id
                LEFT JOIN mpm.tabsupp c
                    on b.supp = c.supp
            where a.userid = $id and pk = (select max(pk) from db_po.t_do where userid = $id) 
                and year(b.tglpo) = 20$tahun_sekarang and month(b.tglpo) in ($bulan_sekarang,$bulan_sebelumnya)
        ";
        $proses_view_do = $this->db->query($sql_view_do);
        /*
        echo "<pre>";
            print_r($sql_ins_do);
            print_r($sql_view_do);
        echo "</pre>";
        */
        if ($proses_view_do->num_rows() > 0) {
            return $proses_view_do->result();
        } else {
            return array();
        }

    }

    public function detail_do($data)
    {
        $no_sales = $this->uri->segment('3');
        $kode_lang = $data['kode_lang'];
        $id = $this->session->userdata('id');   

        /* mencari nopo di tabel mpm.po */
        $this->db->where('no_sales = '.'"'.$no_sales.'"');
        $query = $this->db->get('dbsls.t_inv_master');
        foreach ($query->result() as $row) {
            $no_inv = $row->no_inv;
            $no_sj = $row->no_sj;
            $tgl_sj = $row->tgl_sj;
        }

        /* mencari nopo di tabel mpm.po */
        $this->db->where('no_inv = '.'"'.$no_inv.'"');
        $query = $this->db->get('dbsls.t_inv_detail');
        foreach ($query->result() as $row) {
            $kodeprod = $row->productid;
            $banyak = $row->qty3;
        }

        $sql = "
            select  no_inv, productid, NAMAPROD as namaprod, qty3, '$no_sales' as no_sales, 
                    '$no_sj' as no_sj, '$tgl_sj' as tgl_sj 
            from dbsls.t_inv_detail a inner join mpm.tabprod b 
                on a.productid = b.kodeprod
            where no_inv = '$no_inv'
        ";
        $proses = $this->db->query($sql);

        $sql_insert = "
            insert into db_po.t_do


        ";
        /*
        echo "<pre>";
        print_r($sql);
        echo "</pre>";
        */
        if ($proses->num_rows() > 0) {
            return $proses->result();
        } else {
            return array();
        }

    }

    public function detail_po($data){

        $id_po = $this->uri->segment('3');
        $kode_lang = $data['kode_lang'];
        $id = $this->session->userdata('id');
        
        $sql_view_po = "
            select a.id, a.nopo, a.tglpo, b.kodeprod, b.namaprod, b.banyak,(b.berat*b.banyak_karton) as subberat, (b.volume*b.banyak_karton) as subvolume,status_terima
            from mpm.po a INNER JOIN mpm.po_detail b
                        on a.id = b.id_ref
            where a.id = '$id_po' and a.deleted = 0 and b.deleted = 0
        ";
        //echo "<pre>";
        //print_r($sql_view_po);
        //echo "</pre>";

        $proses_sql_view_po = $this->db->query($sql_view_po);

        if ($proses_sql_view_po->num_rows() > 0) {
            return $proses_sql_view_po->result();
        } else {
            return array();
        }

    }

    public function jumlah_po($data){

        $id_po = $this->uri->segment('3');
        $kode_lang = $data['kode_lang'];
        $id = $this->session->userdata('id');
        
        $sql_view_po = "
            select a.id, a.subberat, a.subvolume, sum(a.subberat) as tot_berat, sum(a.subvolume) as tot_volume
            from (
                        select a.id, b.kodeprod, (b.berat*b.banyak_karton) as subberat, (b.volume*b.banyak_karton) as subvolume
                        from mpm.po a INNER JOIN mpm.po_detail b
                                                on a.id = b.id_ref
                        where a.id = '$id_po' and a.deleted = 0 and b.deleted = 0
                        group by b.kodeprod
            )a
        ";
        //echo "<pre>";
        //print_r($sql_view_po);
        //echo "</pre>";

        $proses_sql_view_po = $this->db->query($sql_view_po);

        if ($proses_sql_view_po->num_rows() > 0) {
            return $proses_sql_view_po->result();
        } else {
            return array();
        }

    }

    public function update_do($data){

        $id_po = $this->uri->segment('3');
        $kode_lang = $data['kode_lang'];
        $id = $this->session->userdata('id');
        
        //mengambil tahun saat ini 
        $tahun_sekarang= date('y');
        $bulan_sekarang= date('m');
        $bulan_sebelumnya= date('m')-1;

        $sql_del = "
            delete from db_po.t_temp_history_po
            where userid = $id
        ";
        $proses_del = $this->db->query($sql_del);

        $sql_ins = "
            insert into db_po.t_temp_history_po
            select      id, userid, company, email, a.nopo, a.supp, a.tipe, a.tglpesan, a.tglpo, `open`/* open_date*/,  alamat, a.flag_terima
            from        mpm.po a
            where       userid = $id and year(tglpo) = 20$tahun_sekarang and month(tglpo) in ($bulan_sekarang,$bulan_sebelumnya) and deleted = 0 and nopo not like '/mpm%'
            ORDER BY    id desc
        ";
        $proses_ins = $this->db->query($sql_ins);


        $sql_del_do_master = "
            delete from db_po.t_temp_do_master
            where kode_lang ='1$kode_lang' 
        ";
        $proses_del_do_master = $this->db->query($sql_del_do_master);

        $sql_ins_do_master = "
            insert into db_po.t_temp_do_master
            select  a.ref, a.nodokacu, a.tgldokjdi, kode_lang, nama_lang 
            from    dbsls.t_inv_do_master a 
            where   year(a.tgldokjdi) = '20$tahun_sekarang' and month(a.tgldokjdi) in ($bulan_sekarang,$bulan_sebelumnya) and 
                            a.nodokacu not like '%xxxx%' and kode_lang ='1$kode_lang' 
                            and a.kddokjdi ='do' and ref is not null
            ORDER BY day(a.tgldokjdi) desc
        "; 

        $proses_ins_do_master = $this->db->query($sql_ins_do_master);

        $sql_del_inv_master = "
            delete from db_po.t_temp_inv_master
            where customerid ='1$kode_lang' 
        ";
        $proses_del_inv_master = $this->db->query($sql_del_inv_master);

        $sql_ins_inv_master = "
            insert into db_po.t_temp_inv_master
            select  a.from_po, a.no_sj, a.no_inv, no_sales, a.tgl_terima,a.tgl_sj, customerid
            from    dbsls.t_inv_master a
            where   year(tgl_terima) = 20$tahun_sekarang and customerid = '1$kode_lang' and status_faktur = 1
            ORDER BY tgl_terima desc
        ";

        $proses_ins_inv_master = $this->db->query($sql_ins_inv_master);

        //update tabel t_temp_inv_master
        $sql_upd_inv_master = "
            update db_po.t_temp_inv_master a 
            inner JOIN 
            (
                select ref, nodokacu
                from db_po.t_temp_do_master
            )b on a.no_sj = b.nodokacu
            set a.from_po = b.ref
            where a.customerid ='1$kode_lang'
        ";
        
        $proses_upd_inv_master = $this->db->query($sql_upd_inv_master);

        
        echo "<pre>";
        print_r($sql_ins);
        print_r($sql_ins_do_master);
        print_r($sql_ins_inv_master);
        print_r($sql_upd_inv_master);
        echo "</pre>";
        
        if($proses_upd_inv_master == 1)
        {
            //redirect('all_po/show_po');
            return $proses_upd_inv_master;
        }else{
            echo "ada error, harap hubungi IT";
        }

    }


    public function update_status_do($data){

        $no_sales = $this->uri->segment('3');
        $id = $this->session->userdata('id');
        
        //mengambil tahun saat ini 
        $tahun_sekarang= date('y');
        $bulan_sekarang= date('m');
        $bulan_sebelumnya= date('m')-1;

        /* mencari max nilai kolom pk */
        $this->db->select_max('pk');
        $this->db->where('userid',$id);
        $query = $this->db->get('db_po.t_do');
        foreach ($query->result() as $row) {
            $max_pk = $row->pk;
            
        }

        //update tabel db_po.t_do
        $sql_upd_do = "
            update db_po.t_do a
            set a.`status` = 1
            where userid = '$id' and pk = $max_pk
                        and no_sales = '$no_sales'
        ";
        
        $proses_upd_do = $this->db->query($sql_upd_do);
        /*
        echo "<pre>";
        print_r($sql_upd_do);
        echo "</pre>";
        */
        if($proses_upd_do == 1)
        {
            redirect('all_po/show_po');
            //return $proses_upd_do;
        }else{
            echo "ada error, harap hubungi IT";
        }

    }

    public function view_po($data){

        $kode_lang = $data['kode_lang'];
        $id = $this->session->userdata('id');
        $tgl_created=date('Y-m-d H:i:s');
        $tahun=date('Y');
        $bulan=date('m');
        // $bulan_sebelumnya= date('m')-1;
        
        // echo "<pre>";
        // print_r($kode_lang);
        // print_r($id);
        // echo "<br>";
        // print_r($tahun);
        // echo "<br>";
        // print_r($bulan);
        // echo "<br>";
        // print_r($bulan_sebelumnya);
        

        $sql = "
            select  a.id as id_po, a.nopo, a.tglpo, a.tglpesan, a.tipe, a.company, a.alamat, a.status, a.open
            from    mpm.po a 
            where   a.userid = $id and a.deleted = 0 and
                    ((
                        year(a.tglpesan) in (year(date(now()))) and month(a.tglpesan) in (month(date(now())))
                    ) or
                    (
                        year(a.tglpesan) in (date_format(date(now()) - INTERVAL '1' MONTH,'%Y')) and 
                        month(a.tglpesan) in (date_format(date(now()) - INTERVAL '1' MONTH,'%m'))
                    ) or
                    (
                        year(a.tglpesan) in (date_format(date(now()) - INTERVAL '2' MONTH,'%Y')) and 
                        month(a.tglpesan) in (date_format(date(now()) - INTERVAL '2' MONTH,'%m'))
                    ) or
                    (
                        year(a.tglpesan) in (date_format(date(now()) - INTERVAL '3' MONTH,'%Y')) and 
                        month(a.tglpesan) in (date_format(date(now()) - INTERVAL '3' MONTH,'%m'))
                    )or
                    (
                        year(a.tglpesan) in (date_format(date(now()) - INTERVAL '4' MONTH,'%Y')) and 
                        month(a.tglpesan) in (date_format(date(now()) - INTERVAL '4' MONTH,'%m'))
                    ))

            ORDER BY a.id desc
        ";

        $query = $this->db->query($sql);
        // echo "<pre><br><br><br>";
        // print_r($sql);
        // echo "</pre>";

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return array();
        }

    }

    public function updateStatusBarang($data){

        // $id_po = $this->uri->segment('3');
        // $kode_lang = $data['kode_lang'];
        // $id = $this->session->userdata('id');
        date_default_timezone_set('Asia/Jakarta'); 
        $created = date('Y-m-d H:i:s');

        $sql = "
            update mpm.po_detail a
            set a.status_terima = ".$data['statusTombol'].", a.tgl_terima = '$created'
            where id_ref = "."'".$data['idPo']."'"."and kodeprod = "."'".$data['kodeprod']."'"."
        ";
        // echo "<pre>";
        // print_r($sql);
        // echo "</pre>";

        $query = $this->db->query($sql);

        $sql_log = "insert into db_po.t_logUpdateStatusBarang select '', "."'".$this->session->userdata('id')."',"."'".$data['idPo']."',"."'".$data['kodeprod']."',".$data['statusTombol'].",'".$created."'"."";
        $query_log = $this->db->query($sql_log);

        // echo "<pre>";
        // print_r($sql_log);
        // echo "</pre>";

        if ($query && $query_log) {
            //return $query->result();
            redirect('all_po/konfirmasi/'.$data['idPo']);
        } else {
            return array();
        }

    }

    public function getSuppbyid()
    {
        $supp=$this->session->userdata('supp');
        if($supp=='000')
        {
            return $this->db->query('select supp,namasupp from mpm.tabsupp');
        }
        else
        {
            return $this->db->query('select supp,namasupp from mpm.tabsupp where supp='.$supp);
        }
    }

    public function laporan_po($data){

        $tahun = $data['tahun'];
        $id = $this->session->userdata('id');
        $bulan= $data['bulan'];
        $supp= $data['supp'];

        $sql_del = "delete from db_po.t_laporan_po where userid = $id";
        $query_del = $this->db->query($sql_del);

        // echo "<pre>";
        // echo br(5);
        // print_r($sql_del);
        // echo "</pre>";

        $sql_del = "delete from db_po.t_laporan_po_temp where id = $id";
        $query_del = $this->db->query($sql_del);

        // echo "<pre>";
        // print_r($sql_del);
        // echo "</pre>";

        $sql_del = "delete from db_po.t_laporan_po_harga_temp where id = $id";
        $query_del = $this->db->query($sql_del);

        // echo "<pre>";
        // print_r($sql_del);
        // echo "</pre>";

        $sql = "
            insert into db_po.t_laporan_po_temp
            select 	a.nopo, a.company,a.tipe, date(a.tglpo), a.alamat, b.kodeprod, b.banyak, 
                    c.namaprod, c.grup, d.nama_group, a.userid, $id
            from mpm.po a INNER JOIN mpm.po_detail b 
            on a.id = b.id_ref LEFT JOIN mpm.tabprod c
            on b.kodeprod = c.kodeprod	LEFT JOIN mpm.tbl_group d
            on c.grup = d.kode_group 
            where `open` = 1  and a.deleted = 0 and b.deleted = 0 and year(tglpo) = $tahun 
                and month(tglpo) = $bulan and a.supp in ($supp) and nopo not like '/mpm%' and a.nopo not like '%batal%'
            ORDER BY a.id desc
        ";

        // echo "<pre>";
        // print_r($sql);
        // echo "</pre>";

        $query = $this->db->query($sql);

        $sql = "
            insert into db_po.t_laporan_po_harga_temp
            select  a.kodeprod, 
                    a.h_dp * (100-d_dp)/100 as h_dp, $id
            from    mpm.prod_detail a 
                    inner join mpm.tabprod b using(kodeprod)
            where   tgl=(
                        select  max(tgl) 
                        from    mpm.prod_detail 
                        where   kodeprod=a.kodeprod
            )ORDER BY KODEPROD
        ";
        // echo "<pre>";
        // print_r($sql);
        // echo "</pre>";

        $query = $this->db->query($sql);

        $sql = "
            insert into db_po.t_laporan_po
            select 	a.nopo, a.company, d.branch_name, d.nama_comp, a.tipe, a.tglpo, a.alamat, a.kodeprod,  a.namaprod, a.grup, a.nama_group, 
                    a.banyak,	b.h_dp as harga, (a.banyak*b.h_dp) as total, urutan, $id
            from db_po.t_laporan_po_temp a LEFT JOIN db_po.t_laporan_po_harga_temp b 
            on a.kodeprod = b.kodeprod and b.id=$id LEFT JOIN mpm.`user` c
            on c.id = a.userid LEFT JOIN
            (
                    select concat(a.kode_comp, a.nocab) as kode, a.branch_name, a.nama_comp, a.kode_comp, urutan
                    from mpm.tbl_tabcomp a
                    where a.`status` = 1
                    GROUP BY a.kode_comp
            )d on d.kode_comp = c.username where a.id = $id
        ";
        // echo "<pre>";
        // print_r($sql);
        // echo "</pre>";

        $proses = $this->db->query($sql);
       
        if($proses)
        {
            $sql = "
                insert into db_po.t_laporan_po
                select  '','','','TOTAL', '', '', '', '', '', '', '',
                        sum(banyak), '', sum(a.total),'999',$id
                from db_po.t_laporan_po a
                where userid = $id
            ";
            $proses = $this->db->query($sql);
            if($proses)
            {
                $sql= "select * from db_po.t_laporan_po where userid = $id order by urutan";
                $proses = $this->db->query($sql)->result();
                if($proses){
                    return $proses;
                }else{
                    return array();
                }

            }else{
                return array();
            }
        }else{
            return array();
        }

    }

    public function get_company()
    {
        // $sql = "
        //     select a.id, a.username, a.company from mpm.`user` a
        //     where a.kode_lang is not null and a.kode_lang <> ''
        //     ORDER BY company asc
        // ";

        $sql = "
        select a.id, a.username, a.company, a.active
        from mpm.`user` a
        where a.kode_lang is not null and a.kode_lang <> '' and a.active = 1 and a.username not in ('sadmin','deltomed')
        ORDER BY company asc
        ";
        
        $proses = $this->db->query($sql);
        return $proses;

    }

    public function get_company_all()
    {
        // $sql = "
        //     select a.id, a.username, a.company from mpm.`user` a
        //     where a.kode_lang is not null and a.kode_lang <> ''
        //     ORDER BY company asc
        // ";

        $sql = "
        select a.id, a.username, a.company, a.active
        from mpm.`user` a
        where a.kode_lang is not null and a.kode_lang <> '' and a.username not in ('sadmin','deltomed')
        ORDER BY company asc
        ";
        
        $proses = $this->db->query($sql);
        return $proses;

    }

    public function get_company_po()
    {
        $id = $this->session->userdata('id');
        $sql = "
            select a.id, a.username, a.company 
            from mpm.`user` a
                INNER JOIN
                (
                    SELECT userid, wilayah FROM mpm.t_wilayah_po
                    where userid = $id and status = 1
                )b on a.id = b.wilayah
            where a.kode_lang is not null and a.kode_lang <> ''
            ORDER BY company asc
        ";
        
        $proses = $this->db->query($sql);
        return $proses;

    }

    public function list_order_company($userid)
    {

        $sql = "
        select 	a.id, a.company, a.nopo, date(a.tglpo) as tglpo, date(a.tglpesan) as tglpesan, a.supp, a.tipe, a.userid,a.open as open,a.status,
				b.banyak, b.harga, sum(b.banyak * b.harga) as total,
				d.branch_name, d.nama_comp, e.NAMASUPP as namasupp
        from mpm.po a INNER JOIN mpm.po_detail b 
            on a.id = b.id_ref LEFT JOIN mpm.`user` c 
            on a.userid = c.id LEFT JOIN
            (
                select a.branch_name, a.nama_comp, a.kode_comp
                from mpm.tbl_tabcomp a
                where `status` = 1
                GROUP BY a.kode_comp
            )d on c.username = d.kode_comp LEFT JOIN mpm.tabsupp e
            on a.supp = e.SUPP
        where b.deleted = 0 and a.deleted = 0 and a.userid = $userid 
        GROUP BY a.id
        ORDER BY id desc
        limit 5000
        ";

        // echo "<pre>";
        // print_r($sql);
        // echo "</pre>";

        $proses = $this->db->query($sql)->result();
        if($proses){
            return $proses;
        }else{
            return array();
        }

    }

    public function list_order()
    {

        $sql = "
        select 	a.id, a.company, a.nopo, date(a.tglpo) as tglpo, date(a.tglpesan) as tglpesan, a.supp, a.tipe, a.userid,a.open as open,a.status,
				b.banyak, b.harga, sum(b.banyak * b.harga) as total,
				d.branch_name, d.nama_comp, e.NAMASUPP as namasupp
        from mpm.po a INNER JOIN mpm.po_detail b 
            on a.id = b.id_ref LEFT JOIN mpm.`user` c 
            on a.userid = c.id LEFT JOIN
            (
                select a.branch_name, a.nama_comp, a.kode_comp
                from mpm.tbl_tabcomp a
                where `status` = 1
                GROUP BY a.kode_comp
            )d on c.username = d.kode_comp LEFT JOIN mpm.tabsupp e
            on a.supp = e.SUPP
        where b.deleted = 0 and a.deleted = 0 
        GROUP BY a.id
        ORDER BY id desc
        limit 1000
        ";

        // echo "<pre><br><br><br><br>";
        // print_r($sql);
        // echo "</pre>";

        $proses = $this->db->query($sql)->result();
        if($proses){
            return $proses;
        }else{
            return array();
        }

    }
    

    public function unlock_finance($id,$supp){
        $sql = "
            update mpm.po a
            set a.lock = null, open = null, open_by = null, status = null, status_approval = null, alasan_approval = null
            where a.id = $id and a.supp = $supp
        ";
        $proses = $this->db->query($sql);
        if($proses){
            return $proses;
        }else{
            return array();
        }
    }

    public function list_order_header($id_po)
    {

        $sql = "
        select 	a.id,a.userid, a.nopo, a.supp, a.tglpo, date(a.tglpesan) as tglpesan, a.tipe, a.`open`, a.company, 
                a.alamat, a.alamat_kirim, a.note,a.status,a.supp,
                a.status_approval, a.alasan_approval,a.po_ref,
                c.branch_name,c.nama_comp,d.NAMASUPP as namasupp, c.kode, year(a.tglpesan) as tahun, month(a.tglpesan) as bulan, a.lock
        from mpm.po a LEFT JOIN mpm.`user` b
            on a.userid = b.id LEFT JOIN
            (
                    select a.branch_name, a.nama_comp, a.kode_comp, concat(a.kode_comp,a.nocab) as kode
                    from mpm.tbl_tabcomp a
                    where a.`status` = 1 and active='1'
                    GROUP BY a.kode_comp
            )c on b.username = c.kode_comp LEFT JOIN mpm.tabsupp d 
                on a.supp = d.SUPP
        where a.id = $id_po
        ";

        // echo "<pre>";
        // print_r($sql);
        // echo "</pre>";

        $proses = $this->db->query($sql)->result();
        if($proses){
            return $proses;
        }else{
            return array();
        }

    }

    public function list_order_detail($id_po)
    {

        $sql = "
            select  b.id as id_kodeprod, b.kodeprod, b.namaprod, b.kode_prc, b.banyak, b.stock_akhir, b.rata as rata, b.doi, b.deleted, b.git as git, b.supp
            from    mpm.po_detail b
            where   b.id_ref = '$id_po' and b.deleted <> 1
        ";

        // echo "<pre>";
        // print_r($sql);
        // echo "</pre>";

        $proses = $this->db->query($sql)->result();
        if($proses){
            return $proses;
        }else{
            return array();
        }

    }

    public function proses_doi($id_po,$tahun,$bulan,$tglpesan,$kode,$supp)
    {
        if ( function_exists( 'date_default_timezone_set' ) )
        date_default_timezone_set('Asia/Jakarta');

        $id = $this->session->userdata('id');
        $created = date('Y-m-d H:i:s');
        $nocab = substr($kode,3,2);
        $kode_comp = substr($kode,0,3);
        $tahun_2 = $tahun-1;

        $sql = "
            update mpm.po_detail a
            set a.stock_akhir = (
                select  sum( if(kode_gdg='PST',
                        ((Saldoawal+masuk_pbk+masuk_supp+retur_sal+bPinjam+kvretur+kr_gudang+retur_depo)-
                        (sales+pinjam+minta_depo+kvbeli+kr_min)-(rusak+sisih)),(Saldoawal+masuk_pbk+retur_sal+kvretur+bPinjam+tukar_msk+msk_gddepo) -
                        (sales+Pinjam+kvbeli+retur_depo+tukar_klr+klr_gddepo))) as stok
                from    data$tahun.st b
                where	b.nocab = '$nocab' and substr(b.bulan,3) = $bulan and a.kodeprod = b.kodeprod
                group by b.nocab, b.kodeprod
                order by b.kodeprod
            )
            where a.id_ref = $id_po
        ";

        $proses = $this->db->query($sql);

        // $bulan = 8;
        // $tglpesan = '2020-08-01';
        $akhirbulan = $bulan - 6;

        // echo "bulan : ".$bulan."br>";
        // echo "tglpesan : ".$tglpesan."br>";
        // echo "akhirbulan : ".$akhirbulan."br>";

        if($akhirbulan<=0)
        {             
            $timestamp = strtotime($tglpesan);
            $bulan_awal = date('m', strtotime('-6 month', $timestamp));
            echo "bulan_awal : ".$bulan_awal."<br>";

            for ($i=(int)$bulan_awal; $i<12 ; $i++) { 
                $x[] = $i.',';
            }

            $bulan_1 = implode($x).'12';
            echo "bulan_1 : ".$bulan_1."<br>";

            $bulan_awal_b = '1';
            $bulan_akhir_b = (int)$bulan-1;
            echo "bulan_akhir_b : ".$bulan_akhir_b."<br>";

            if ($bulan_akhir_b == '0') {  

                $sql = "delete from db_po.t_rata_temp where id_po = $id_po";
                $proses = $this->db->query($sql);

                $fi = "             
                    insert into db_po.t_rata_temp       
                    select $id_po, '$kode', kodeprod, sum(banyak) as unit, sum(banyak)/6 as rata, '$created', $id
                    from
                    (
                        select if(kodeprod = '700009','010067',if(kodeprod = '700012', '010094', kodeprod)) kodeprod,banyak
                        from data$tahun_2.fi
                        where bulan in ($bulan_1) and nocab = '$nocab'                        
                        union ALL                    
                        select if(kodeprod = '700009','010067',if(kodeprod = '700012', '010094', kodeprod)) kodeprod,banyak
                        from data$tahun_2.ri
                        where bulan in ($bulan_1) and nocab = '$nocab'
                    )a group by kodeprod
                ";
                $proses = $this->db->query($fi);

                echo "<pre>";
                print_r($fi);
                echo "</pre>";

            }else{

                for ($i=1; $i<(int)$bulan_akhir_b ; $i++) { 
                    $a[] = $i.',';
                }$bulan_2 = implode($a).(int)$bulan_akhir_b;
                echo "bulan_2 : ".$bulan_2."<br>";

                $sql = "delete from db_po.t_rata_temp where id_po = $id_po";
                $proses = $this->db->query($sql);

                $fi = "            
                    insert into db_po.t_rata_temp        
                    select $id_po, '$kode', kodeprod, sum(banyak) as unit, sum(banyak)/6 as rata, '$created', $id
                    from
                    (
                        select if(kodeprod = '700009','010067',if(kodeprod = '700012', '010094', kodeprod)) kodeprod,banyak
                        from data$tahun_2.fi
                        where bulan in ($bulan_1) and nocab = '$nocab'                        
                        union ALL
                        select if(kodeprod = '700009','010067',if(kodeprod = '700012', '010094', kodeprod)) kodeprod,banyak
                        from data$tahun_2.fi
                        where bulan in ($bulan_2) and nocab = '$nocab'                        
                        union ALL                    
                        select if(kodeprod = '700009','010067',if(kodeprod = '700012', '010094', kodeprod)) kodeprod,banyak
                        from data$tahun.ri
                        where bulan in ($bulan_1) and nocab = '$nocab'                        
                        union ALL
                        select if(kodeprod = '700009','010067',if(kodeprod = '700012', '010094', kodeprod)) kodeprod,banyak
                        from data$tahun.ri
                        where bulan in ($bulan_2) and nocab = '$nocab'
                    )a group by kodeprod
                ";
                $proses = $this->db->query($fi);
                echo "<pre>";
                print_r($fi);
                echo "</pre>";
            }
        }else{

            $timestamp = strtotime($tglpesan);
            $bulan_awal = date('m', strtotime('-6 month', $timestamp));
            echo "bulan_awal : ".$bulan_awal."<br>";
            $bulan_2 = $bulan - 1;

            for ($i=(int)$bulan_awal; $i<$bulan_2 ; $i++) { 
                $x[] = $i.',';
            }

            $bulan_1 = implode($x).$bulan_2;
            echo "bulan_1 : ".$bulan_1."<br>";
            
            $sql = "delete from db_po.t_rata_temp where id_po = $id_po";
            $proses = $this->db->query($sql);

            $fi = "             
                insert into db_po.t_rata_temp       
                select $id_po, '$kode', kodeprod, sum(banyak) as unit, sum(banyak)/6 as rata, '$created', $id
                from
                (
                    select if(kodeprod = '700009','010067',if(kodeprod = '700012', '010094', kodeprod)) kodeprod,banyak
                    from data$tahun.fi
                    where bulan in ($bulan_1) and nocab = '$nocab'                        
                    union ALL                    
                    select if(kodeprod = '700009','010067',if(kodeprod = '700012', '010094', kodeprod)) kodeprod,banyak
                    from data$tahun.ri
                    where bulan in ($bulan_1) and nocab = '$nocab'
                )a group by kodeprod
            ";
            $proses = $this->db->query($fi);
            
            echo "<pre>";
            print_r($fi);
            echo "</pre>";

        }

        $sql = "
            update mpm.po_detail a
            set a.rata = (
                select 	b.rata
                from 	db_po.t_rata_temp b
                where 	a.kodeprod = b.kodeprod and b.id_po = $id_po
            )
            where a.id_ref = $id_po
        ";
        $proses = $this->db->query($sql);

        $sql = "delete from db_po.t_git_temp where id_po = $id_po";
        $proses = $this->db->query($sql);

        $sql="
            insert into db_po.t_git_temp
            select  $id_po, '$kode',if(b.kodeprod = '700009','010067',if(b.kodeprod = '700012', '010094', b.kodeprod)) kodeprod, 
                    sum(b.banyak) as git, '$created',$id
            from    mpm.po a INNER JOIN mpm.po_detail b
                        on a.id = b.id_ref
                    LEFT JOIN mpm.`user` c on a.userid = c.id
            where   year(tglpo) in (date_format(date(now()) - INTERVAL '1' MONTH,'%Y'),year(date(now()))) and 
                    month(tglpo) in (date_format(date(now()) - INTERVAL '1' MONTH,'%m'),month(date(now()))) and  
                    a.deleted = 0 and b.deleted = 0 and (b.status_terima is null or b.status_terima = null or b.status_terima = '0') and 
                    (a.nopo is not null and left(a.nopo,4) <> '/mpm') and c.username = '$kode_comp'
            GROUP BY b.kodeprod
            ORDER BY b.kodeprod
        ";
        
        $proses = $this->db->query($sql);

        $sql = "
            update mpm.po_detail a
            set a.git = (
                select 	b.git
                from 	db_po.t_git_temp b
                where 	a.kodeprod = b.kodeprod and b.id_po = $id_po
            )
            where a.id_ref = $id_po
        ";
        $proses = $this->db->query($sql);

        $sql = "delete from db_po.t_doi_temp where id_po = $id_po";
        $proses = $this->db->query($sql);

        $sql = " 
            insert into db_po.t_doi_temp
            select $id_po, '$kode',  a.kodeprod,(a.stock / a.rata * 30) as doi, '$created', $id
            FROM
            (
                select  a.kodeprod, a.stock_akhir, a.git, if(a.git is null, a.stock_akhir, a.stock_akhir + a.git) as stock, a.rata
                from mpm.po_detail a
                where a.id_ref = $id_po
            )a
        ";

        $proses = $this->db->query($sql);

        $sql = "
            update mpm.po_detail a
            set a.doi = (
                select 	b.doi
                from 	db_po.t_doi_temp b
                where 	a.kodeprod = b.kodeprod and b.id_po = $id_po
            )
            where a.id_ref = $id_po
        ";
        $proses = $this->db->query($sql);

        $sql = "
                update mpm.po a
                set a.status = 1
                where a.id = $id_po
        ";
        $proses = $this->db->query($sql);

        if($proses){
            redirect('all_po/list_order_detail/'.$id_po.'/'.$supp);
        }else{
            return array();
        }

    }

    public function list_product_supp_admin($id_po,$supp)
    {
        $grup = "
        select a.grup
        from mpm.po a
        where a.id = $id_po
        ";
        $proses = $this->db->query($grup)->result();
        foreach ($proses as $a) {
            $kodegrup = $a->grup;
        }

        if($kodegrup == '' || $kodegrup == null || $kodegrup == '0') {
            $group = "";
            // echo "aaaaaa";
        }else{
            $group = "and grup = '$kodegrup'";
            // echo "bbbbbb";
        }
        $sql = "
        select kodeprod, namaprod,isisatuan,odrunit 
        from mpm.tabprod  
        where produksi=1 and supp=$supp $group
        order by kodeprod
        ";

        // echo "<pre>";
        // echo "<br><br><br>";
        // echo "kodegrup : ".$kodegrup;
        // echo "group : ".$group;
        // print_r($sql);
        // echo "</pre>";
        return $this->db->query($sql);    
    
    }

    public function getProductDetail($kodeprod,$userid)
    {    
        $sql="select exclude,level,diskon from mpm.user where id='$userid'";
        $query=$this->db->query($sql);

        $row=$query->row();
        $level=$row->level;
        $diskon=$row->diskon;
        $exclude=$row->exclude;

        switch($level)
        {
            case 4:
                if($exclude==1)
                {
                    $sql="
                        select  a.kodeprod,a.kode_prc,a.namaprod,a.supp,
                                a.grupprod,a.isisatuan,b.h_pbf as harga,
                                b.d_dp as diskon,h_beli_dp as harga_beli,
                                d_beli_dp as diskon_beli, a.berat, a.volume
                        from    mpm.tabprod a inner join mpm.prod_detail b using(kodeprod)
                                
                        where   a.kodeprod='$kodeprod' and tgl=(
                                select max(tgl) 
                                from mpm.prod_detail 
                                where kodeprod='$kodeprod'
                                )
                    ";
                }
                else
                {
                    $sql="
                        select  a.kodeprod,a.kode_prc,a.namaprod,
                                a.supp,a.grupprod,a.isisatuan,
                                b.h_dp as harga,
                                b.d_dp as diskon, h_beli_dp as harga_beli,d_beli_dp as diskon_beli, a.berat, a.volume
                        from    mpm.tabprod a inner join mpm.prod_detail b using(kodeprod) 
                                
                        where   a.kodeprod='$kodeprod' and 
                                tgl=(
                                    select max(tgl) 
                                    from    mpm.prod_detail 
                                    where   kodeprod='$kodeprod'
                                )
                    ";
                }
                break;
            case 5:
                    $sql="
                        select  a.kodeprod,a.kode_prc,a.namaprod,a.supp,a.grupprod,a.isisatuan,
                                b.h_pbf as harga,
                                '$diskon' as diskon,
                                h_beli_pbf as harga_beli,d_beli_pbf as diskon_beli, a.berat, a.volume
                        from    mpm.tabprod a inner join mpm.prod_detail b using(kodeprod)
                                
                        where   a.kodeprod='$kodeprod' and tgl=(
                                    select  max(tgl) 
                                    from    mpm.prod_detail 
                                    where   kodeprod='$kodeprod'
                                    )
                    "; 
                    // echo "<pre>";
                    // print_r($sql);
                    // echo "</pre>";
                break;

            case 6:
                $sql="
                    select  a.kodeprod,
                            a.kode_prc,
                            a.namaprod,
                            a.supp,a.grupprod,a.isisatuan,b.h_bsp as harga ,b.d_bsp as diskon, 
                            h_beli_bsp as harga_beli,d_beli_bsp as diskon_beli, a.berat, a.volume
                    from    mpm.tabprod a inner join mpm.prod_detail b using(kodeprod) 
                            
                    where   a.kodeprod='$kodeprod' and 
                            tgl=(
                                select  max(tgl) 
                                from    mpm.prod_detail 
                                where   kodeprod='$kodeprod'
                            )
                ";
                break;
            case 7:
                $sql="
                    select  a.kodeprod,
                            a.kode_prc,
                            a.namaprod,a.supp,a.grupprod,a.isisatuan,
                            b.h_dp as harga,b.d_dp as diskon, 
                            h_beli_dp as harga_beli,d_beli_dp as diskon_beli, a.berat, a.volume
                    from    mpm.tabprod a inner join mpm.prod_detail b using(kodeprod) 
                            
                    where   a.kodeprod='$kodeprod' and 
                            tgl=(
                                select  max(tgl) 
                                from    mpm.prod_detail 
                                where   kodeprod='$kodeprod'
                            )
                ";        
                break;
        }

        $hasil=$this->db->query($sql);
        return $hasil->result_array(); 

    }

    public function tambah_product($data)
    {
        $id = $this->session->userdata('id');
        $id_po = $data['id_po'];
        $kodeprod = $data['kodeprod'];
        $jumlah = $data['jumlah'];
        $userid = $data['userid'];
        
        $data = $this->getProductDetail($kodeprod, $userid);
        foreach ($data as $key) {
            $namaprod = $key['namaprod'];
            $kode_prc = $key['kode_prc'];
            $isisatuan = $key['isisatuan'];
            $harga = $key['harga'];
            $supp = $key['supp'];
            $berat = $key['berat'];
            $volume = $key['volume'];
        }

        $karton = $isisatuan * $jumlah;
        // $sql = "
        //     insert into mpm.po_detail (id_ref, supp, kodeprod, namaprod, kode_prc, banyak,banyak_karton, harga, berat, volume, userid) 
        //     values ($id_po, '$supp', '$kodeprod', '$namaprod', '$kode_prc', $karton, $jumlah,$harga, '$berat', '$volume', $id)
        // ";
        // $proses = $this->db->query($sql);

        $sql_insert = "
            insert into mpm.po_detail (id_ref, supp, kodeprod, namaprod, kode_prc, banyak,banyak_karton, harga, berat, volume, userid) 
            values ($id_po, '$supp', '$kodeprod', '$namaprod', '$kode_prc', $karton, $jumlah,$harga, '$berat', '$volume', $id)
        ";
        $proses_insert = $this->db->query($sql_insert);

        $sql = "
            update mpm.po a
            set a.lock = null, open = null, open_by = null, status = null, status_approval = null, alasan_approval = null
            where a.id = $id_po and a.supp = $supp
        ";
        $proses = $this->db->query($sql);
        
        
        if ($proses) {
            redirect('all_po/list_order_detail/'.$id_po.'/'.$supp.'/'.$userid);
        }else{
            return array();
        }
    }

    public function proses_po_to_finance($id_po,$supp){
        
        $sql = "
            select * from mpm.po_detail a inner join mpm.po b
                on a.id_ref = b.id
            where id_ref =$id_po and (a.doi is null or a.doi >= 60 or b.status_approval <> '1') and a.deleted = 0 
        ";

        $proses = $this->db->query($sql)->num_rows();
        if ($proses) {
            echo "<script>
			alert('DOI > 60 atau NULL. Silahkan Cek DOI atau isi alasan approval di bawah ini');
			window.location.href='../../list_order_detail/$id_po/$supp';
			</script>";
        }else{
            
            $sql = "
                    update mpm.po a
                    set a.status = 2
                    where a.id = $id_po
            ";
            $proses = $this->db->query($sql);

            if($proses){
                echo "<script>
            	alert('Proses Berhasil. Silahkan tunggu approval dari Finance');
            	close();
                </script>";
            }else{
                return array();
            }


        }
        
        

    }

    public function proses_approval($data){
        
        $id_po = $data['id_po'];
        $alasan_approval = $data['alasan_approval'];
        $supp = $data['supp']; 
        $userid = $data['userid']; 
        
        $sql = "
            update mpm.po a 
            set a.alasan_approval = '$alasan_approval', a.status_approval = 1, a.status = 2
            where a.id = $id_po
        ";

        $proses = $this->db->query($sql);
        if ($proses) {
            echo "<script>
			alert('update approval berhasil dan terkirim ke Finance');
			window.location.href='../all_po/list_order_detail/$id_po/$supp/$userid';
			</script>";
        }else{            
            echo "<script>
			alert('update approval gagal');
			window.location.href='../all_po/list_order_detail/$id_po/$supp/$userid';
			</script>";
        }
        
    }

    public function submit_po($data){
        $date = date_create(date('Y-m-d H:i:s'));
        date_add($date, date_interval_create_from_date_string('-7 hours'));
        $tgl = date_format($date, 'Y-m-d H:i:s');

        $id = $this->session->userdata('id');
        $id_po = $data['id_po'];
        $nopo = $data['nopo'];
        $alamat_kirim = $data['alamat_kirim'];
        $note = $data['note'];
        $tglpo = $tgl;
        $po_ref = $data['po_ref'];



        $sql = "
            update mpm.po a
            set a.nopo = '$nopo', a.alamat = '$alamat_kirim', a.note = '$note', a.tglpo = '$tglpo', a.modified = '$tglpo', a.po_ref = '$po_ref'
            where a.id = $id_po
        ";
        $proses = $this->db->query($sql);
        if ($proses) {
            echo "<script>
			alert('Update po berhasil');
			window.location.href='../all_po/list_order';
			</script>";
        }else{            
            echo "<script>
			alert('Update po Gagal. Harap ulangi kembali atau hubungi IT');
			window.location.href='../all_po/list_order';
			</script>";
        }


    }

    public function insert_do($data){

        $id = $this->session->userdata('id');
        if ( function_exists( 'date_default_timezone_set' ) )
        date_default_timezone_set('Asia/Jakarta');  
        $tgl_created=date('Y-m-d H:i:s');

        $sql = "delete from db_po.t_do_deltomed_temp where id = $id";
        $proses = $this->db->query($sql);
        
        $proses = $this->db->insert_batch('db_po.t_do_deltomed_temp', $data);

        $sql = "select tgldo from db_po.t_do_deltomed_temp where id = $id limit 1";
        $proses = $this->db->query($sql)->result();

        foreach($proses as $x){
            $tgldo = $x->tgldo;
        }

        $sql = "select * from db_po.t_do_deltomed where tgldo = '$tgldo'";
        $proses = $this->db->query($sql)->num_rows();

        // echo $proses;
        if ($proses >= 1) {
            $sql = "delete from db_po.t_do_deltomed where tgldo = '$tgldo'";
            $proses = $this->db->query($sql);

            if($proses){
                $sql = "
                    insert into db_po.t_do_deltomed
                    select kode,nodo,tgldo,kodedp,company,kosong1,kosong2,kosong3,kosong4,kodeprod_delto,kosong5,namaprod,qty,kosong6,nopo,$id,'$tgl_created'
                    from db_po.t_do_deltomed_temp where id = $id
                ";      
                $proses = $this->db->query($sql);
            }
            
        }else{
            $sql = "
                insert into db_po.t_do_deltomed
                select kode,nodo,tgldo,kodedp,company,kosong1,kosong2,kosong3,kosong4,kodeprod_delto,kosong5,namaprod,qty,kosong6,nopo,$id,'$tgl_created'
                from db_po.t_do_deltomed_temp where id = $id
            ";
            $proses = $this->db->query($sql);
        }

        $sql_update_nopo = "
        update db_po.t_do_deltomed a
        set a.nopo = replace(a.nopo,'.1',''),
                a.nopo = replace(a.nopo,'.2',''),
                a.nopo = replace(a.nopo,'.3',''),
                a.nopo = replace(a.nopo,'.4',''),
                a.nopo = replace(a.nopo,'.5',''),
                a.nopo = replace(a.nopo,'.6',''),
                a.nopo = replace(a.nopo,'.7',''),
                a.nopo = replace(a.nopo,'.8',''),
                a.nopo = replace(a.nopo,'.9',''),
                a.nopo = replace(a.nopo,'.10','')
        ";
        $this->db->query($sql_update_nopo);
        
        $sql_update_dl = "
        update db_po.t_do_deltomed a
        set a.nopo = replace(a.nopo,'D0','DL0')
        where a.nopo like 'D0%'
        ";
        $this->db->query($sql_update_dl);

        

        if($proses){
            $sql = "Select * from db_po.t_do_deltomed where tgldo = '$tgldo'";
            $proses = $this->db->query($sql)->result();
            echo "<pre><br><br><br>";
            print_r($sql);
            echo "</pre>";
            return $proses;
        }else{
            return array();
        }
    }

    public function getPoDo_deltomed(){
        $sql = "
            select tglpo, format(u,0) as unit, format(v,0) as `value`, tgldo, b.username, b.lastupdate, format(b.unit_do,0) as unit_do
            FROM
            (
                select date(a.tglpo) as tglpo, a.nopo, sum(b.banyak) as u, sum(b.banyak * b.harga) as v
                from mpm.po a INNER JOIN mpm.po_detail b on a.id = b.id_ref
                where a.deleted = 0 and b.deleted = 0 and nopo like 'DL%' 
                GROUP BY date(a.tglpo)
                ORDER BY tglpo desc
                limit 50
            )a LEFT JOIN
            (	
                select a.tgldo, b.username, a.lastupdate, unit_do
                from 
                (
                    select str_to_date(tgldo,'%d/%m/%Y') as tgldo, id, lastupdate, sum(a.qty) as unit_do
                    from db_po.t_do_deltomed a
                    GROUP BY a.tgldo
                    ORDER BY tgldo desc
                )a LEFT JOIN mpm.`user` b on a.id = b.id 
            )b on a.tglpo = b.tgldo
        ";
        $proses = $this->db->query($sql)->result();
        
        if($proses){
            return $proses;
        }else{
            return array();
        }
    }
    public function do_deltomed(){
        $sql = "
            select a.tgldo, b.username, a.lastupdate, unit_do
            from 
            (
                select str_to_date(tgldo,'%d/%m/%Y') as tgldo, id, lastupdate, sum(a.qty) as unit_do
                from db_po.t_do_deltomed a
                GROUP BY a.tgldo
                ORDER BY tgldo desc
            )a LEFT JOIN mpm.`user` b on a.id = b.id
        ";
        $proses = $this->db->query($sql)->result();
        
        if($proses){
            return $proses;
        }else{
            return array();
        }
    }

    public function do_us(){
        $sql = "
            select a.tgldo, b.username, a.lastupdate, a.unit_do
            from
            (
                select a.nodo, str_to_date(tgldo,'%Y%m%d') as tgldo, sum(a.banyak) as unit_do, a.userid, a.lastupdate
                from db_po.t_do_us a
                GROUP BY a.tgldo
            )a LEFT JOIN mpm.`user` b on a.userid = b.id
        ";
        $proses = $this->db->query($sql)->result();
        
        if($proses){
            return $proses;
        }else{
            return array();
        }
    }

    public function po_outstanding($data){
        date_default_timezone_set('Asia/Jakarta'); 
        $date = date_create(date('Y-m-d H:i:s'));
        date_add($date, date_interval_create_from_date_string('-7 hours'));
        $tgl = date_format($date, 'Y-m-d H:i:s'); 
        $thn = date('Y');

        $id = $this->session->userdata('id');
        $supp = $data['supp'];
        $periode1 = $data['periode1'];
        $periode2 = $data['periode2'];
        $status_do = $data['status_do'];
        $status_total = $data['status_total'];
        // echo "<pre>";
        // echo " supp : ".$supp;
        // echo " periode1 : ".$periode1;
        // echo " periode2 : ".$periode2;
        // echo " status_do : ".$status_do;
        // echo "</pre>";

        if ($status_do == '1') {
            $do_null = "where b.nodo is null";
        }else{
            $do_null = "";
        }        

        // $sql = "delete from db_po.t_temp_po_outstanding where id = $id";
        // $proses = $this->db->query($sql);
        
        // $sql = "
        // insert into db_po.t_temp_po_outstanding
        // select 	a.branch_name, a.nama_comp, a.company, a.alamat,a.nopo, a.po_ref,
        //         date(a.tglpo) as tglpo,b.nodo ,str_to_date(b.tgldo,'%d/%m/%Y') as tgldo,
        //         a.tipe, a.kodeprod, a.dl_kodeprod, a.namaprod, a.banyak,a.harga,(a.banyak*a.harga) as `value`, b.kodeprod_delto, b.qty, (b.qty*a.harga) as 'value', a.berat, '$supp', $id, 0 as status
        // FROM
        // (
        //     select 	d.branch_name, d.nama_comp, a.nopo, a.tglpo, a.tipe, a.userid, a.company, a.alamat, 
        //             b.kodeprod, b.banyak, b.harga, e.NAMAPROD, e.dl_kodeprod, a.po_ref, b.berat * b.banyak_karton as berat
        //     from    mpm.po a INNER JOIN mpm.po_detail b
        //                 on a.id = b.id_ref LEFT JOIN mpm.`user` c
        //                 on a.userid = c.id LEFT JOIN
        //     (
        //         select a.kode_comp, a.branch_name, a.nama_comp, a.urutan
        //         from mpm.tbl_tabcomp a
        //         where `status` = 1
        //         GROUP BY kode_comp
        //     )d on c.username = d.kode_comp LEFT JOIN 
        //     (
        //         select a.kodeprod, a.NAMAPROD, a.kodeprod_deltomed as dl_kodeprod
        //         from mpm.tabprod a 
        //         where a.supp = 001
        //     )e on b.kodeprod = e.kodeprod
        //     where   a.deleted = 0 and b.deleted = 0 and a.supp ='$supp' and left(a.nopo,4) <> '/MPM' and a.nopo not like '%batal%' and
        //             (date(a.tglpo) >= '$periode1' and date(a.tglpo) <= '$periode2')
        // )a LEFT JOIN 
        // (
        //     select a.nodo, a.tgldo, a.kodedp, a.company, a.kodeprod_delto, a.namaprod, a.qty, a.nopo 
        //     from db_po.t_do_deltomed a
        // )b on a.nopo = b.nopo and a.dl_kodeprod = b.kodeprod_delto $do_null
        // ";
        // $proses = $this->db->query($sql);

        // echo "<pre><br><br><br><br><br>";
        // print_r($sql);
        // echo "</pre>";

        $sql = "
        insert into db_po.t_po_outstanding_deltomed
        select 	a.branch_name, a.nama_comp,a.company, a.tglpo, a.nopo, a.tipe, a.kodeprod, a.namaprod, 
                a.banyak as qty_po, b.qty as qty_pemenuhan, a.harga,(a.banyak*a.harga) as value_po,(b.qty*a.harga) as value_pemenuhan,a.berat, b.tgldo, b.nodo, (b.qty / a.banyak) * 100 as fulfilment,
                datediff(b.tgldo,DATE_FORMAT(a.tglpo,'%Y-%m-%d')) as lead_time_proses_do,a.po_ref, a.tanggal_terima, datediff(a.tanggal_terima,b.tgldo) as leadtime_proses_kirim, (a.banyak - b.qty) as outstanding_po, '$tgl', $id
        from
        (
            select 	d.branch_name, d.nama_comp, a.nopo, a.tglpo, a.tipe, 
                    a.userid, a.company, a.alamat, 
                    b.kodeprod, b.banyak, b.harga, e.namaprod,e.kodeprod_deltomed, a.po_ref, (b.berat * b.banyak_karton) as berat, b.status_terima, b.tanggal_terima
        from 	mpm.po a INNER JOIN 
        (
            select a.id_ref, a.kodeprod, a.banyak, a.banyak_karton, a.berat, a.namaprod, a.harga, a.deleted, a.status_terima, a.tanggal_terima
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
            select  a.kodeprod, a.namaprod, a.kodeprod_deltomed
            from    mpm.tabprod a
            where   supp = 001
        )e on b.kodeprod = e.kodeprod
        where	a.deleted = 0 and b.deleted = 0 and a.supp ='001' and 
                left(a.nopo,4) <> '/MPM' and 
                a.nopo not like '%batal%' and (date(a.tglpo) >= '$periode1' and date(a.tglpo) <= '$periode2')
        )a LEFT JOIN 
        (
        select a.nodo, a.kodedp, a.company, a.kodeprod_delto, a.namaprod, a.qty, a.nopo, str_to_date(a.tgldo,'%d/%m/%Y') as tgldo
        from db_po.t_do_deltomed a
        where year(str_to_date(a.tgldo,'%d/%m/%Y')) = '$tgl'
        )b on a.nopo = b.nopo and a.kodeprod_deltomed = b.kodeprod_delto 
        ";

        $proses = $this->db->query($sql);

        echo "<pre><br><br><br>";
        print_r($sql);
        echo "</pre>";

        if ($status_total == '1') {
            $sql = "
            insert into db_po.t_temp_po_outstanding
            select 	'x_TOTAL' as branch_name, '' as nama_comp, '' as company, '' as alamat, 
                    '' as nopo, 'null' as po_ref, 'null' as tglpo, '' as nodo, 'null' as tgldo, '' as tipe, kodeprod as kodeprod,
                    dl_kodeprod as dl_kodeprod, namaprod as namaprod, sum(a.banyak) as banyak, harga as harga, sum(a.value_po) as value_po,
                    kodeprod_do as kodeprod_do, sum(a.qty) as qty, sum(a.value_do) as value_do,sum(a.berat) as berat, '' as supp, $id, 1
            from db_po.t_temp_po_outstanding a
            where id = $id and a.status = 0
            GROUP BY a.kodeprod
            UNION ALL
            select 	'z_GRAND_TOTAL' as branch_name, '' as nama_comp, '' as company, '' as alamat, 
                    '' as nopo, '' as po_ref, '' as tglpo, '' as nodo, '' as tgldo, '' as tipe, '' as kodeprod,
                    '' as dl_kodeprod, '' as namaprod, sum(a.banyak) as banyak, harga as harga, sum(a.value_po) as value_po,
                    '' as kodeprod_do, sum(a.qty) as qty, sum(a.value_do) as value_do,sum(a.berat) as berat, '' as supp, $id, 1
            from db_po.t_temp_po_outstanding a
            where id = $id and a.status = 0

            ";
            $proses = $this->db->query($sql);
        }else{
            
        }


        if($proses){
            $sql = "select * from db_po.t_po_outstanding_deltomed where created_by = $id and created_date = '$tgl'";
            $proses = $this->db->query($sql)->result();
            if($proses){
                return $proses;
            }else{
                return array();
            }
        }else{
            return array();
        }
               
    }

    public function po_outstanding_us($data){
        date_default_timezone_set('Asia/Jakarta'); 
        $date = date_create(date('Y-m-d H:i:s'));
        date_add($date, date_interval_create_from_date_string('-7 hours'));
        $tgl = date_format($date, 'Y-m-d H:i:s'); 

        $thn = date('Y');

        $id = $this->session->userdata('id');
        $supp = $data['supp'];
        $periode1 = $data['periode1'];
        $periode2 = $data['periode2'];
        $status_do = $data['status_do'];
        $status_total = $data['status_total'];
        // echo "<pre>";
        // echo " supp : ".$supp;
        // echo " periode1 : ".$periode1;
        // echo " periode2 : ".$periode2;
        // echo " status_do : ".$status_do;
        // echo "</pre>";

        if ($status_do == '1') {
            $do_null = "where b.banyak_do is null";
        }else{
            $do_null = "";
        } 

        // $sql = "delete from db_po.t_temp_po_outstanding_new where id = $id";
        // $proses = $this->db->query($sql);

        // $sql = "
        // insert into db_po.t_temp_po_outstanding_new
        // select 	a.branch_name, a.nama_comp, date(a.tglpo) as tglpo,a.nopo, a.kodeprod,  
		//  		a.namaprod, a.banyak as qty_po, 
		// 		b.qty_pemenuhan,b.tgldo, (a.banyak_po - b.qty_pemenuhan) as fulfilment, '$tgl',$id
        // FROM 
        // ( 
		// 	select 	d.branch_name, d.nama_comp, 
        //             a.nopo, a.tglpo, a.tipe, 
        //             a.userid, a.company, a.alamat, 
        //             b.kodeprod, b.banyak, b.harga, e.namaprod,a.po_ref
		// 	from 	mpm.po a INNER JOIN mpm.po_detail b
        //                 on a.id = b.id_ref LEFT JOIN mpm.`user` c 
        //                 on a.userid = c.id LEFT JOIN 
        //                 ( 
        //                     select 	a.kode_comp, a.branch_name, a.nama_comp, a.urutan 
        //                     from 		mpm.tbl_tabcomp a 
        //                     where 	`status` = 1 
        //                     GROUP BY kode_comp 
        //                 )d on c.username = d.kode_comp LEFT JOIN
        //                 (
        //                     select a.kodeprod, a.namaprod
        //                     from mpm.tabprod a
        //                 )e on b.kodeprod = e.kodeprod
		// 	where	a.deleted = 0 and b.deleted = 0 and a.supp ='005' and 
        //             left(a.nopo,4) <> '/MPM' and 
        //             a.nopo not like '%batal%' and 
        //             (date(a.tglpo) >= '$periode1' and date(a.tglpo) <= '$periode2')
        // )a LEFT JOIN
        // (
        //     select a.nodo, a.tgldo, a.kodeprod,a.qty_pemenuhan,a.nopo
        //     FROM
        //     (
        //         select 	a.nodo, a.tgldo,
        //                 a.kodeprod, sum(a.banyak) as qty_pemenuhan, a.nopo 
        //         from 	db_po.t_do_us a
        //         GROUP BY a.nopo, a.kodeprod
        //     )a
        // )b on a.nopo = b.nopo and a.kodeprod = b.kodeprod $do_null
        
        // ";

        // datediff(e.tanggal_tiba,DATE_FORMAT(a.tglpo,'%Y-%m-%d')) as lead_time
        $sql = "
        insert into db_po.t_po_outstanding_us
        select 	a.branch_name, a.nama_comp, date(a.tglpo) as tglpo,a.nopo,a.tipe, a.kodeprod,  
		 		a.namaprod, a.banyak as qty_po, 
				b.qty_pemenuhan,b.tgldo, b.nodo, (b.qty_pemenuhan / a.banyak * 100) as fulfilment,datediff(b.tgldo,DATE_FORMAT(a.tglpo,'%Y-%m-%d')) as lead_time_proses_do, a.tanggal_terima, datediff(a.tanggal_terima, b.tgldo) as leadtime_proses_kirim, 
				(a.banyak - b.qty_pemenuhan) as outstanding_po,
                '$tgl',$id                
        FROM 
        ( 
                select 	d.branch_name, d.nama_comp, 
                        a.nopo, a.tglpo, a.tipe, 
                        a.userid, a.company, a.alamat, 
                        b.kodeprod, b.banyak, b.harga, e.namaprod,a.po_ref, b.tanggal_terima
                from 	mpm.po a INNER JOIN 
                        (
                            select a.id_ref, a.kodeprod, a.banyak, a.banyak_karton, a.berat, a.namaprod, a.harga, a.deleted, a.tanggal_terima
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
                            select a.kodeprod, a.namaprod
                            from mpm.tabprod a
                        )e on b.kodeprod = e.kodeprod
                where	a.deleted = 0 and b.deleted = 0 and a.supp ='005' and 
                        left(a.nopo,4) <> '/MPM' and 
                        a.nopo not like '%batal%' and (date(a.tglpo) >= '$periode1' and date(a.tglpo) <= '$periode2')
        )a LEFT JOIN
        (
            select 	a.nodo, a.tgldo, a.kodeprod,a.nopo,
				    if(b.satuan_box is null, a.qty_pemenuhan, a.qty_pemenuhan * b.satuan_box) as qty_pemenuhan 
            FROM
            (
                    select 	a.nodo, a.tgldo,
                            a.kodeprod, sum(a.banyak) as qty_pemenuhan, a.nopo
                    from 	db_po.t_do_us a
                    GROUP BY a.nopo, a.kodeprod
            )a LEFT JOIN
            (
                select a.kodeprod, a.satuan_box, a.`status`
                from db_produk.t_product_po a
                where a.`status` = 1
            )b on a.kodeprod = b.kodeprod
        )b on a.nopo = b.nopo and a.kodeprod = b.kodeprod $do_null
        ";



        $proses = $this->db->query($sql);
        // echo "<pre><br><br>";
        // print_r($sql);
        // echo "</pre>";
        

        if ($status_total == '1') {
            $sql = "
            insert into db_po.t_temp_po_outstanding_new
            select 	'x_TOTAL' as branch_name, '' as nama_comp, '' as company, '' as alamat, 
                    '' as nopo,'' as po_ref, 'null' as tglpo, '' as tipe, kodeprod as kodeprod, 
                    namaprod as namaprod, sum(a.banyak) as banyak, harga as harga, sum(a.value_po) as value_po,
                    sum(a.banyak_do) as banyak_do, sum(a.value_do) as value_do, '' as supp, $id
            from db_po.t_temp_po_outstanding_new a
            where id = $id
            GROUP BY a.kodeprod
            UNION ALL
            select 	'z_GRAND_TOTAL' as branch_name, '' as nama_comp, '' as company, '' as alamat, 
                    '' as nopo, '' as po_ref, 'null' as tglpo, '' as tipe, '' as kodeprod, 
                    '' as namaprod, sum(a.banyak) as banyak, harga as harga, sum(a.value_po) as value_po,
                    sum(a.banyak_do) as banyak_do, sum(a.value_do) as value_do, '' as supp, $id
            from db_po.t_temp_po_outstanding_new a
            where id = $id

            ";
            $proses = $this->db->query($sql);

            // echo "<pre>";
            // print_r($sql);
            // echo "</pre>";

        }else{
            
        }


        if($proses){
            $sql = "select * from db_po.t_po_outstanding_us where created_by = $id and created_date = '$tgl'";
            $proses = $this->db->query($sql)->result();
            if($proses){
                return $proses;
            }else{
                return array();
            }
        }else{
            return array();
        }   
    }

    public function po_outstanding_intrafood($data){

        date_default_timezone_set('Asia/Jakarta'); 
        $date = date_create(date('Y-m-d H:i:s'));
        date_add($date, date_interval_create_from_date_string('-7 hours'));
        $tgl = date_format($date, 'Y-m-d H:i:s'); 

        $thn = date('Y');
        $bln = date('m');
        $supp = $this->session->userdata('supp');
        if($supp == 000){
            // $suppx = '';
            $suppx = "a.supp = '012' and";
        }else{
            $suppx = "a.supp = '$supp' and";
        }

        $id = $this->session->userdata('id');
        $supp = $data['supp'];
        $periode1 = $data['periode1'];
        $periode2 = $data['periode2'];
        $status_do = $data['status_do'];
        $status_total = $data['status_total'];
        // echo "<pre>";
        // echo " supp : ".$supp;
        // echo " periode1 : ".$periode1;
        // echo " periode2 : ".$periode2;
        // echo " status_do : ".$status_do;
        // echo "</pre>";

        if ($status_do == '1') {
            $do_null = "where b.banyak_do is null";
        }else{
            $do_null = "";
        } 

        // $sql = "delete from db_po.t_temp_po_outstanding_new where id = $id";
        // $proses = $this->db->query($sql);

        $sql = "
        insert into db_po.t_po_outstanding_intrafood 
        select 	a.id, d.branch_name,d.nama_comp, DATE_FORMAT(a.tglpo,'%Y-%m-%d') as tglpo,a.nopo,a.tipe,b.kodeprod,b.namaprod,sum(b.banyak_karton) as qty_po, e.total_karton as qty_pemenuhan,e.tanggal_kirim as tanggal_kirim,e.tanggal_tiba as tanggal_tiba,(if(e.total_karton = null,0,e.total_karton) / sum(b.banyak_karton) * 100 ) as fulfilment, datediff(e.tanggal_tiba,DATE_FORMAT(a.tglpo,'%Y-%m-%d')) as lead_time, f.status_closed, f.color, 
        b.tanggal_terima,datediff(b.tanggal_terima, e.tanggal_kirim) as leadtime_proses_kirim, (sum(b.banyak_karton) - e.total_karton) as outstanding_po,
        '$tgl', $id
        from mpm.po a INNER JOIN 
        (
            select a.id_ref, a.kodeprod, a.banyak, a.banyak_karton, a.berat, a.namaprod, a.tanggal_terima
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
            SELECT a.id_po, a.kodeprod, sum(a.jumlah_unit) as total_unit, sum(a.jumlah_karton) as total_karton, a.tanggal_kirim, a.tanggal_tiba, a.nama_ekspedisi, a.keterangan, a.status_pemenuhan, a.status_closed
            from mpm.t_asn a
            GROUP BY a.id_po, a.kodeprod
        )e on a.id = e.id_po and b.kodeprod = e.kodeprod left join 
        (
            select 	a.id,f.status_closed, if(e.total_produk_po is null, 'red', if(e.total_karton_po = e.total_karton_asn, 'green', 'yellow')) as color
            from mpm.po a INNER JOIN 
            (
                select a.id_ref, a.kodeprod, a.banyak, a.banyak_karton, a.berat, a.namaprod
                from mpm.po_detail a
                where a.deleted = 0
            )b on a.id = b.id_ref LEFT JOIN
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
            (date(a.tglpo) >= '$periode1' and date(a.tglpo) <= '$periode2')
            GROUP BY a.id
            ORDER BY a.id DESC
        )f on a.id = f.id
        where $suppx a.deleted = 0 and a.nopo not like '/mpm%' and a.nopo not like '%batal%' and 
        (date(a.tglpo) >= '$periode1' and date(a.tglpo) <= '$periode2')
        GROUP BY a.id, b.kodeprod
        ORDER BY a.id DESC
        ";
        $proses = $this->db->query($sql);
        // echo "<pre><br><br><br><br>";
        // print_r($sql);
        // echo "</pre>";
        

        if($proses){
            $sql = "select * from db_po.t_po_outstanding_intrafood where created_by = $id and created_date = '$tgl'";
            $proses = $this->db->query($sql)->result();
            if($proses){
                return $proses;
            }else{
                return array();
            }
        }else{
            return array();
        }   
    }

    public function cek_supp($id_po){
        $sql = $this->db->query("select supp from mpm.po where id = $id_po")->result();
        foreach($sql as $x){
            return $x->supp;
        }
    }

    public function getCustInfo()
    {
        $id=$this->session->userdata('id');
        // $ka=$this->uri->segment('4');
        // if ($ka == ''){
        //     $sql="select company,npwp,a.address,email, b.kode_alamat from mpm.user a
        //          left JOIN mpm.t_alamat b on a.username = b.username WHERE id=297 ";
        // }else{
        $sql="
                select company,npwp, address,email, b.alamat, b.kode_alamat from mpm.user a
                LEFT JOIN mpm.t_alamat b on a.username = b.username 
                WHERE id=$id and b.status ='1'";
        // }
        // $query=$this->db->query($sql,array($id));
        // return $query->row();
        $proses = $this->db->query($sql)->result();
        return $proses;

    }

    public function getCustInfo2($id=null)
    {
        //$id=$this->session->userdata('id');
        $sql="select company,nama_wp,npwp,address,email,alamat_wp, b.alamat, b.kode_alamat from mpm.user a
        left JOIN mpm.t_alamat b on a.username = b.username where id=$id and b.status ='1'";
        $proses = $this->db->query($sql)->result();
        return $proses;
    }

}