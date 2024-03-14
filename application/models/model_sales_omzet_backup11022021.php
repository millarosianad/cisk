<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_sales_omzet extends CI_Model
{

	public function line_sold($data){
        $id = $this->session->userdata('id');
        date_default_timezone_set('Asia/Jakarta');        
		$created_date='"'.date('Y-m-d H:i:s').'"';
        $periode_1 = $data['periode_1'];
        $periode_2 = $data['periode_2'];
        $tahun_periode_1 = substr($periode_1,0,4);
        $tahun_periode_2 = substr($periode_2,0,4);
        $kodeprod = $data['kodeprod']; 
        $breakdown_1 = $data['breakdown_1']; 
        $breakdown_2 = $data['breakdown_2']; 
        $wilayah_nocab = $data['wilayah_nocab']; 
        
        if ($breakdown_1 == 1) {
            $group_by_kodeprod = ",kodeprod";
        }else{
            $group_by_kodeprod = "";
        }

        if ($breakdown_2 == 1) {
            $group_by_kodesales = ",kodesales";
        }else{
            $group_by_kodesales = "";
        }

        if ($wilayah_nocab == null) {
            $wilayah = '';
        }else{
            $wilayah = "and nocab in ($wilayah_nocab)";
        }

        // echo "<pre>";
        // echo "periode_1 = ".$periode_1."<br>";
        // echo "periode_2 = ".$periode_2."<br>";
        // echo "tahun_periode_1 = ".$tahun_periode_1."<br>";
        // echo "tahun_periode_2 = ".$tahun_periode_2."<br>";
        // echo "kodeprod = ".$kodeprod."<br>";
        // echo "</pre>";

        // $this->db->query("delete from db_temp.t_temp_line_sold where id = $id");

        if ($tahun_periode_2 == $tahun_periode_1) {
            $fi = "
            select 	concat(a.KDDOKJDI,a.NODOKJDI) as faktur, concat(a.kode_comp,a.nocab) as kode, 
                    concat(a.KODESALES,a.NOCAB)as kodesales, a.kodeprod
            from 	data$tahun_periode_1.fi a
            where 	(a.banyak > 0 or a.tot1 > 0) and kodeprod in ($kodeprod) and 
                    (date_format(a.TGLDOKJDI,'%Y-%m-%d') >= '$periode_1' and date_format(a.TGLDOKJDI,'%Y-%m-%d') <= '$periode_2') $wilayah
            ";
        }elseif($tahun_periode_2 - $tahun_periode_1 == 1){
            $fi = "
            select 	concat(a.KDDOKJDI,a.NODOKJDI) as faktur, concat(a.kode_comp,a.nocab) as kode, 
                    concat(a.KODESALES,a.NOCAB)as kodesales, a.kodeprod
            from 	data$tahun_periode_1.fi a
            where 	(a.banyak > 0 or a.tot1 > 0) and kodeprod in ($kodeprod) and 
                    date_format(a.TGLDOKJDI,'%Y-%m-%d') >= '$periode_1' $wilayah
            union all
            select 	concat(a.KDDOKJDI,a.NODOKJDI) as faktur, concat(a.kode_comp,a.nocab) as kode, 
                    concat(a.KODESALES,a.NOCAB)as kodesales, a.kodeprod
            from 	data$tahun_periode_2.fi a
            where 	(a.banyak > 0 or a.tot1 > 0) and kodeprod in ($kodeprod) and 
                    date_format(a.TGLDOKJDI,'%Y-%m-%d') <= '$periode_2' $wilayah
            ";
        }else{
            // echo "<pre>";
            // echo "periode anda berjarak 2 tahun. Sehingga tidak dapat kami proses"; 
            return array();      
            // echo "</pre>"; 
        }

        // echo "<pre>";
        // print_r($fi);        
        // echo "</pre>";

        $sql = "
            insert into db_temp.t_temp_line_sold
            select a.kode, b.branch_name, b.nama_comp, b.sub, a.kodesales, c.namasales, a.kodeprod,d.namaprod, a.faktur, a.produk, a.line_sold,$id,$created_date
            FROM
            (
                select 	kode, kodesales, kodeprod, count(DISTINCT(faktur)) as faktur, count(kodeprod) as produk,
                        (count(kodeprod) / count(DISTINCT(faktur))) as line_sold
                from 
                (
                    $fi
                )a GROUP BY kode $group_by_kodeprod $group_by_kodesales
            )a LEFT JOIN
            (
                select concat(a.kode_comp, a.nocab) as kode, a.branch_name, a.nama_comp, a.urutan, a.sub
                from mpm.tbl_tabcomp a
                where a.status	= 1
                group by concat(a.kode_comp, a.nocab) 
            )b on a.kode = b.kode LEFT JOIN
            (
                select kode,kodesales,namasales
                from mpm.tbl_bantu_sales
            )c on a.kodesales = c.kode LEFT JOIN
            (
                select kodeprod, namaprod
                from mpm.tabprod a
                where kodeprod in ($kodeprod)
            )d on a.kodeprod = d.kodeprod
            ORDER BY urutan, kodesales,a.kodeprod
        ";
            // echo "<pre>";
            // print_r($sql);
            // echo "</pre>";

        $proses = $this->db->query($sql);
        if ($proses) {
            $hasil = $this->db->query("select * from db_temp.t_temp_line_sold where id = $id and created_date = (select max(created_date) from db_temp.t_temp_line_sold where id = $id)")->result();
            return $hasil;
        }else{
            return array();
        }

    }

    public function list_dp(){
    	$userid=$this->session->userdata('id');
    	//echo "id : ".$userid;

    	/*cek hak DP apa saja yang dapat dilihat*/
            $this->db->where('id = '.'"'.$userid.'"');
            $query = $this->db->get('mpm.user');
            foreach ($query->result() as $row) {
                $wilayah_nocab = $row->wilayah_nocab;
                //echo "nocab : ".$wilayah_nocab."<br>";
                return $wilayah_nocab;
            }
        /*end cek hak DP apa saja yang dapat dilihat*/
        
    }

    public function omzet_dp($data){
        if ( function_exists( 'date_default_timezone_set' ) )
        date_default_timezone_set('Asia/Jakarta');        
        $tgl_created='"'.date('Y-m-d H:i:s').'"';
        $bulan_closing = $data['bulan'];
        $id=$this->session->userdata('id');
        $tahun = $data['tahun'];
        $kodeprod = $data['cari_kodeprod'];
        $kode_type = $data['kode_type'];

        if ($kode_type == '') {
            $kode_typex = '';
        }else{
            $kode_typex = "and kode_type in ($kode_type)";
        }

        $wilayah_nocab = $data['wilayah_nocab'];
        if ($wilayah_nocab == null) {
            $wilayah = '';
        }else{
            $wilayah = "and nocab in ($wilayah_nocab)";
        }

        $bulan = date("m");
        // $bulan = '8';
        $tahunsekarang = date("Y");				
        if ($tahun == $tahunsekarang) //jika memilih tahun berjalan
        {				
            if ($bulan == '01')
            {
                $totalx = 'b2+b3+b4+b5+b6+b7+b8+b9+b10+b11+b12';
                $pembagi = 'xb2+xb3+xb4+xb5+xb6+xb7+xb8+xb9+xb10+xb11+xb12';
                $rata = 0;
            }elseif ($bulan == '02')
            {
                $totalx = 'b1+b3+b4+b5+b6+b7+b8+b9+b10+b11+b12';
                $pembagi = 'xb1+xb3+xb4+xb5+xb6+xb7+xb8+xb9+xb10+xb11+xb12';
                $rata = 1;
            }elseif ($bulan == '03')
            {
                $totalx = 'b1+b2+b4+b5+b6+b7+b8+b9+b10+b11+b12';
                $pembagi = 'xb1+xb2+xb4+xb5+xb6+xb7+xb8+xb9+xb10+xb11+xb12';
                $rata = 2;
            }elseif ($bulan == '04')
            {
                $totalx = 'b1+b2+b3+b5+b6+b7+b8+b9+b10+b11+b12';
                $pembagi = 'xb1+xb2+xb3+xb5+xb6+xb7+xb8+xb9+xb10+xb11+xb12';
                $rata = 3;
            }elseif ($bulan == '05')
            {
                $totalx = 'b1+b2+b3+b4+b6+b7+b8+b9+b10+b11+b12';
                $pembagi = 'xb1+xb2+xb3+xb4+xb6+xb7+xb8+xb9+xb10+xb11+xb12';
                $rata = 4;
            }elseif ($bulan == '06')
            {
                $totalx = 'b1+b2+b3+b4+b5+b7+b8+b9+b10+b11+b12';
                $pembagi = 'xb1+xb2+xb3+xb4+xb5+xb7+xb8+xb9+xb10+xb11+xb12';
                $rata = 5;
            }elseif ($bulan == '07')
            {
                $totalx = 'b1+b2+b3+b4+b5+b6+b8+b9+b10+b11+b12';
                $pembagi = 'xb1+xb2+xb3+xb4+xb5+xb6+xb8+xb9+xb10+xb11+xb12';
                $rata = 6;
            }elseif ($bulan == '08')
            {
                $totalx = 'b1+b2+b3+b4+b5+b6+b7+b9+b10+b11+b12';
                $pembagi = 'xb1+xb2+xb3+xb4+xb5+xb6+xb7+xb9+xb10+xb11+xb12';
                $rata = 7;
            }elseif ($bulan == '09')
            {
                $totalx = 'b1+b2+b3+b4+b5+b6+b7+b8+b10+b11+b12';
                $pembagi = 'xb1+xb2+xb3+xb4+xb5+xb6+xb7+xb8+xb10+xb11+xb12';
                $rata = 8;
            }elseif ($bulan == '10')
            {
                $totalx = 'b1+b2+b3+b4+b5+b6+b7+b8+b9+b11+b12';
                $pembagi = 'xb1+xb2+xb3+xb4+xb5+xb6+xb7+xb8+xb9+xb11+xb12';
                $rata = 9;
            }elseif ($bulan == '11')
            {
                $totalx = 'b1+b2+b3+b4+b5+b6+b7+b8+b9+b10+b12';
                $pembagi = 'xb1+xb2+xb3+xb4+xb5+xb6+xb7+xb8+xb9+xb10+xb12';
                $rata = 10;
            }elseif ($bulan == '12')
            {
                $totalx = 'b1+b2+b3+b4+b5+b6+b7+b8+b9+b10+b11';
                $pembagi = 'xb1+xb2+xb3+xb4+xb5+xb6+xb7+xb8+xb9+xb10+xb11';
                $rata = 11;
            }else
            {
                $totalx = 'b1+b2+b3+b4+b5+b6+b7+b8+b9+b10+b11+b12';
                $pembagi = 'xb1+xb2+xb3+xb4+xb5+xb6+xb7+xb8+xb9+xb10+xb11+xb12';
                $rata = 12;
            }
        }else{
            $pembagi = 'xb1+xb2+xb3+xb4+xb5+xb6+xb7+xb8+xb9+xb10+xb11+xb12';
            $totalx = 'b1+b2+b3+b4+b5+b6+b7+b8+b9+b10+b11+b12';
            $rata = 12;
        }
        

        /* == PROSES DELETE db_temp.tbl_temp_omzet ===== */
        $sql = "
        insert into db_temp.t_temp_omzet_dp
        SELECT	a.kode,a.branch_name,a.nama_comp,a.sub,
				b1,b2,b3,b4,b5,b6,b7,b8,b9,b10,b11,b12,
				t1,t2,t3,t4,t5,t6,t7,t8,t9,t10,t11,t12,
				total,
                (total / ($pembagi)) as rata,
                urutan,status,status_closing,lastupload,
                $id,
                $tgl_created
        FROM
        (
		    SELECT	a.kode,b.branch_name,b.nama_comp, b.sub,
                    b1,b2,b3,b4,b5,b6,b7,b8,b9,b10,b11,b12,
                    t1,t2,t3,t4,t5,t6,t7,t8,t9,t10,t11,t12,
                    ($totalx) as total,
                    if(b1 = 0,0,1) as xb1,
                    if(b2 = 0,0,1) as xb2,
                    if(b3 = 0,0,1) as xb3,
                    if(b4 = 0,0,1) as xb4,
                    if(b5 = 0,0,1) as xb5,
                    if(b6 = 0,0,1) as xb6,
                    if(b7 = 0,0,1) as xb7,
                    if(b8 = 0,0,1) as xb8,
                    if(b9 = 0,0,1) as xb9,
                    if(b10 = 0,0,1) as xb10,
                    if(b11 = 0,0,1) as xb11,
                    if(b12 = 0,0,1) as xb12,
                    b.urutan,b.status
            FROM
            (
                select 	a.kode,
                        sum(if(bulan = 1,a.unit, 0)) as b1,
                        sum(if(bulan = 2,a.unit, 0)) as b2,
                        sum(if(bulan = 3,a.unit, 0)) as b3,
                        sum(if(bulan = 4,a.unit, 0)) as b4,
                        sum(if(bulan = 5,a.unit, 0)) as b5,
                        sum(if(bulan = 6,a.unit, 0)) as b6,
                        sum(if(bulan = 7,a.unit, 0)) as b7,
                        sum(if(bulan = 8,a.unit, 0)) as b8,
                        sum(if(bulan = 9,a.unit, 0)) as b9,
                        sum(if(bulan = 10,a.unit, 0)) as b10,
                        sum(if(bulan = 11,a.unit, 0)) as b11,
                        sum(if(bulan = 12,a.unit, 0)) as b12,
                        sum(if(bulan = 1,a.outlet, 0)) as t1,
                        sum(if(bulan = 2,a.outlet, 0)) as t2,
                        sum(if(bulan = 3,a.outlet, 0)) as t3,
                        sum(if(bulan = 4,a.outlet, 0)) as t4,
                        sum(if(bulan = 5,a.outlet, 0)) as t5,
                        sum(if(bulan = 6,a.outlet, 0)) as t6,
                        sum(if(bulan = 7,a.outlet, 0)) as t7,
                        sum(if(bulan = 8,a.outlet, 0)) as t8,
                        sum(if(bulan = 9,a.outlet, 0)) as t9,
                        sum(if(bulan = 10,a.outlet, 0)) as t10,
                        sum(if(bulan = 11,a.outlet, 0)) as t11,
                        sum(if(bulan = 12,a.outlet, 0)) as t12
                FROM
                (
                    select 	concat(a.kode_comp,a.nocab) as kode, SUM(a.tot1) as unit, bulan, count(distinct(concat(kode_comp,kode_lang))) as outlet
                    from	data$tahun.fi a
                    where   kodeprod in ($kodeprod) $wilayah $kode_typex
                    GROUP BY kode, bulan            
                    union ALL
                    select 	concat(a.kode_comp,a.nocab) as kode, SUM(a.tot1) as unit, bulan, null
                    from	data$tahun.ri a
                    where   kodeprod in ($kodeprod) $wilayah $kode_typex
                    GROUP BY kode, bulan
                )a GROUP BY kode
            )a LEFT JOIN
            (
                SELECT 		concat(a.kode_comp,a.nocab) as kode, a.branch_name, a.nama_comp,a.sub,a.urutan,a.status
                from 		mpm.tbl_tabcomp a
                where 		`status` = 1
                GROUP BY 	concat(a.kode_comp,a.nocab)
            )b on a.kode = b.kode
        )a LEFT JOIN
		(
			select 	id ,userid, filename, tahun, bulan, status_closing,substring(filename,3,2) as nocab,lastupload
			from 	mpm.upload
			where 	bulan = $bulan_closing and tahun = $tahun and id in (
				select max(id)
				from mpm.upload
				where tahun = $tahun and bulan = $bulan_closing
				GROUP BY substring(filename,3,2)
			)and userid not in ('0','289') 
		)d on substr(a.kode,4,2) = d.nocab
        ";   
        $proses = $this->db->query($sql);
     
        // echo "<pre>";
        // print_r($sql);
        // echo "</pre>";

         /* ============================================================== */
        if ($proses == '1')
        {                        
            $sql_sub="
                insert into db_temp.t_temp_omzet_dp
                select 	'',a.branch_name,b.nama_comp,a.sub,
                        sum(a.b1), sum(a.b2),sum(a.b3), sum(a.b4),sum(a.b5), sum(a.b6),
                        sum(a.b7), sum(a.b8),sum(a.b9), sum(a.b10),sum(a.b11), sum(a.b12),
                        sum(a.t1), sum(a.t2),sum(a.t3), sum(a.t4),sum(a.t5), sum(a.t6),
                        sum(a.t7), sum(a.t8),sum(a.t9), sum(a.t10),sum(a.t11), sum(a.t12),
                        sum(a.total), sum(($totalx)/$rata) as rata, b.urutan,b.status, '' as status_closing,'', $id, $tgl_created
                from 	db_temp.t_temp_omzet_dp a INNER JOIN 
                (
                    select 	urutan, nama_comp, sub,status
                    from 	mpm.tbl_tabcomp
                    WHERE 	`status` = '2' and status_cluster <> '1'
                )b on a.sub = b.sub
                where 	a.id = $id and created_date = $tgl_created
                group by a.sub
                ORDER BY a.urutan
            ";
            // echo "<pre>";
            // print_r($sql_sub);
            // echo "</pre>";
            $proses_sub = $this->db->query($sql_sub);

            $sql_grand = "
            insert into db_temp.t_temp_omzet_dp
            select 	'','' ,'GRAND TOTAL',a.sub,
                    sum(a.b1), sum(a.b2),sum(a.b3), sum(a.b4),sum(a.b5), sum(a.b6),
                    sum(a.b7), sum(a.b8),sum(a.b9), sum(a.b10),sum(a.b11), sum(a.b12),
                    sum(a.t1), sum(a.t2),sum(a.t3), sum(a.t4),sum(a.t5), sum(a.t6),
                    sum(a.t7), sum(a.t8),sum(a.t9), sum(a.t10),sum(a.t11), sum(a.t12),
                    sum(a.total), sum(($totalx)/$rata) as rata, 999,'' as status, '' as status_closing,'', $id, $tgl_created
            from 	db_temp.t_temp_omzet_dp a 
            where 	a.status = 1 and a.id = $id and created_date = $tgl_created 
            ORDER BY a.urutan
            ";
            // echo "<pre>";
            // print_r($sql_grand);
            // echo "</pre>";
            $proses_grand = $this->db->query($sql_grand);
        }else{
            return array();
        }

        $sql = "select * from db_temp.t_temp_omzet_dp a where a.id = $id and created_date = $tgl_created order by urutan";
        $proses = $this->db->query($sql)->result();
        if ($proses) {
            return $proses;
        }else{
            return array();
        }
    }

    public function sales_per_product($data){
        $id=$this->session->userdata('id');
        if ( function_exists( 'date_default_timezone_set' ) )
        date_default_timezone_set('Asia/Jakarta');        
        $tgl_created='"'.date('Y-m-d H:i:s').'"';
        $tahun = $data['year'];
        $kodeprod = $data['kodeprod'];
        $kondisi_class = $data['kondisi_class']; 
        $breakdown_kodeprod = $data['breakdown_kodeprod'];
        $breakdown_kodesalur = $data['breakdown_kodesalur'];
        $breakdown_type = $data['breakdown_type'];
        $breakdown_kode = $data['breakdown_kode'];
        
        if ($breakdown_kode == 1) {
            $group_by_kode = ",a.kode";
        }else{
            $group_by_kode = "";
        }

        if ($breakdown_kodeprod == 1) {
            $group_by_kodeprod = ",a.kodeprod";
        }else{
            $group_by_kodeprod = "";
        }

        if ($breakdown_kodesalur == 1) {
            $group_by_kodesalur = ",a.kodesalur";
            $group_by_groupsalur = ",groupsalur";
        }else{
            $group_by_kodesalur = "";
            $group_by_groupsalur = "";
        }
        if ($breakdown_type == 1) {
            $group_by_type = ",a.kode_type";
            $group_by_sektor = ",sektor";
        }else{
            $group_by_type = "";
            $group_by_sektor = "";
        }

        if($breakdown_kode == '' && $breakdown_kodeprod == '' && $breakdown_kodesalur == '' && $breakdown_type == ''){
            $group_by = '';
        }else{
            $group_by = "group by '' $group_by_kode $group_by_kodeprod $group_by_groupsalur $group_by_sektor";
        }

        $wilayah_nocab = $data['wilayah_nocab']; 
        if ($wilayah_nocab == null) {
            $wilayah = '';
        }else{
            $wilayah = "and nocab in ($wilayah_nocab)";
        }

        if($kondisi_class == 2 && $breakdown_kodesalur == '1'){
            $fi = "
            select a.kode,a.kodeprod,a.bulan,a.outlet,a.unit,a.value,a.trans,b.kodesalur,c.namasalur,c.groupsalur, b.kode_type, d.sektor
            FROM
            (
                select kode, a.kodeprod, bulan, outlet, SUM(`banyak`) as unit, SUM(`tot1`) as value, COUNT(DISTINCT(outlet)) as trans, kodesalur, kode_type
                FROM
                (
                    select concat(kode_comp, nocab) as kode, CONCAT(KODE_COMP,kode_lang) as outlet, `banyak`, `tot1`, kodeprod, bulan, kodesalur, kode_type
                    from `data$tahun`.`fi`
                    where `kodeprod` in ($kodeprod) and `nodokjdi` <> 'XXXXXX' ".$wilayah."
                    union all
                    select concat(kode_comp, nocab) as kode, null, `banyak`, `tot1`, kodeprod, bulan, kodesalur, kode_type
                    from `data$tahun`.`ri`
                    where `kodeprod` in ($kodeprod) and `nodokjdi` <> 'XXXXXX' ".$wilayah."
                )a
                group by bulan ,a.outlet $group_by_kode $group_by_kodeprod $group_by_kodesalur $group_by_type                         
            )a LEFT JOIN
            (
                select  a.kode,a.kodesalur, a.kode_type
                from    db_lang.tbl_bantu_pelanggan_$tahun a
            )b on a.outlet = b.kode LEFT JOIN
            (
                select  a.kode, a.jenis as namasalur, a.`group` as groupsalur
                from    mpm.tbl_tabsalur a
            )c on b.kodesalur = c.kode LEFT JOIN
            (
                select  a.kode_type, a.sektor
                from    mpm.tbl_bantu_type a
            )d on b.kode_type = d.kode_type
            ";
        }else{
            $fi = "
            select kode, a.kodeprod, bulan, outlet, SUM(`banyak`) as unit, SUM(`tot1`) as value, COUNT(DISTINCT(outlet)) as trans, kodesalur, kode_type
            FROM
            (
                select concat(kode_comp, nocab) as kode, CONCAT(KODE_COMP,kode_lang) as outlet, `banyak`, `tot1`, kodeprod, bulan, kodesalur, kode_type
                from `data$tahun`.`fi`
                where `kodeprod` in ($kodeprod) and `nodokjdi` <> 'XXXXXX' ".$wilayah."
                union all
                select concat(kode_comp, nocab) as kode, null, `banyak`, `tot1`, kodeprod, bulan, kodesalur, kode_type
                from `data$tahun`.`ri`
                where `kodeprod` in ($kodeprod) and `nodokjdi` <> 'XXXXXX' ".$wilayah."
            )a
            group by bulan $group_by_kode $group_by_kodeprod $group_by_kodesalur $group_by_type 
            ";
        }

        $sql="
                insert into db_temp.t_temp_soprod
                select  a.kode,b.branch_name,b.nama_comp, b.sub,
                        a.kodeprod,c.namaprod,c.grup, d.nama_group,a.kodesalur,e.namasalur,e.groupsalur, a.kode_type, f.sektor,
                        sum(if(bulan = '01', trans, 0)) as ot_1,
                        sum(if(bulan = '02', trans, 0)) as ot_2,
                        sum(if(bulan = '03', trans, 0)) as ot_3,	
                        sum(if(bulan = '04', trans, 0)) as ot_4,
                        sum(if(bulan = '05', trans, 0)) as ot_5,
                        sum(if(bulan = '06', trans, 0)) as ot_6,
                        sum(if(bulan = '07', trans, 0)) as ot_7,
                        sum(if(bulan = '08', trans, 0)) as ot_8,		
                        sum(if(bulan = '09', trans, 0)) as ot_9,
                        sum(if(bulan = '10', trans, 0)) as ot_10,
                        sum(if(bulan = '11', trans, 0)) as ot_11,
                        sum(if(bulan = '12', trans, 0)) as ot_12,
                        sum(if(bulan = '01', unit, 0)) as unit_1,
                        sum(if(bulan = '02', unit, 0)) as unit_2,	
                        sum(if(bulan = '03', unit, 0)) as unit_3,
                        sum(if(bulan = '04', unit, 0)) as unit_4,
                        sum(if(bulan = '05', unit, 0)) as unit_5,
                        sum(if(bulan = '06', unit, 0)) as unit_6,
                        sum(if(bulan = '07', unit, 0)) as unit_7,
                        sum(if(bulan = '08', unit, 0)) as unit_8,
                        sum(if(bulan = '09', unit, 0)) as unit_9,
                        sum(if(bulan = '10', unit, 0)) as unit_10,	
                        sum(if(bulan = '11', unit, 0)) as unit_11,
                        sum(if(bulan = '12', unit, 0)) as unit_12,
                        sum(if(bulan = '01', value, 0)) as value_1,
                        sum(if(bulan = '02', value, 0)) as value_2,	
                        sum(if(bulan = '03', value, 0)) as value_3,
                        sum(if(bulan = '04', value, 0)) as value_4,
                        sum(if(bulan = '05', value, 0)) as value_5,
                        sum(if(bulan = '06', value, 0)) as value_6,
                        sum(if(bulan = '07', value, 0)) as value_7,
                        sum(if(bulan = '08', value, 0)) as value_8,
                        sum(if(bulan = '09', value, 0)) as value_9,
                        sum(if(bulan = '10', value, 0)) as value_10,	
                        sum(if(bulan = '11', value, 0)) as value_11,
                        sum(if(bulan = '12', value, 0)) as value_12,
                        b.urutan,b.status,$id,$tgl_created
                from
                (
                    $fi                                  
                )a LEFT JOIN
                (
                    select  concat(kode_comp, nocab) as kode, branch_name, nama_comp, sub, urutan,status
                    from     mpm.tbl_tabcomp
                    where   `status` = 1
                    GROUP BY concat(kode_comp, nocab)
                )b on a.kode = b.kode LEFT JOIN
                (
                    select a.kodeprod, a.namaprod, a.grup
                    from mpm.tabprod a
                )c on a.kodeprod = c.kodeprod left join 
                (
                    select a.kode_group, a.nama_group
                    from mpm.tbl_group a
                )d on c.grup = d.kode_group LEFT JOIN
                (
                    select a.kode, a.jenis as namasalur, a.`group` as groupsalur
                    from mpm.tbl_tabsalur a
                )e on a.kodesalur = e.kode LEFT JOIN
                (
                    select a.kode_type, a.sektor
                    from mpm.tbl_bantu_type a
                )f on a.kode_type= f.kode_type
                $group_by
                 ";
        echo "<pre>";
        echo "<br><br><br>";
        print_r($sql);
        echo "</pre>";
	        
        $proses = $this->db->query($sql);   
        if ($proses) 
        {

            if($breakdown_kode == ''){
                
            }else{
                    $sql_subtotal = "
                    insert into db_temp.t_temp_soprod
                    select  '', a.branch_name, b.nama_comp, b.sub,
                            '' as kodeprod, '' as namaprod, '' as `group`, '' as nama_group,'' as kodesalur,'' as namasalur,'' as groupsalur, '' as kode_type, '' as sektor,
                            sum(ot_1),sum(ot_2),sum(ot_3),sum(ot_4),sum(ot_5),sum(ot_6),
                            sum(ot_7),sum(ot_8),sum(ot_9),sum(ot_10),sum(ot_11),sum(ot_12),
                            sum(unit_1),sum(unit_2),sum(unit_3),sum(unit_4),sum(unit_5),sum(unit_6),
                            sum(unit_7),sum(unit_8),sum(unit_9),sum(unit_10),sum(unit_11),sum(unit_12),
                            sum(value_1),sum(value_2),sum(value_3),sum(value_4),sum(value_5),sum(value_6),
                            sum(value_7),sum(value_8),sum(value_9),sum(value_10),sum(value_11),sum(value_12),
                            b.urutan,b.status,$id,$tgl_created
                    from    db_temp.t_temp_soprod a inner JOIN
                    (
                        select  nama_comp, sub, urutan, status
                        from    mpm.tbl_tabcomp
                        where   `status` = 2
                    )b on a.sub = b.sub
                    where id = $id and a.created_date = $tgl_created
                    GROUP BY sub
                    ORDER BY a.sub
                    ";

                $sql_subtotal_proses = $this->db->query($sql_subtotal);
                
                // echo "<pre>";
                // print_r($sql_subtotal);
                // echo "</pre>";

                $sql_grandtotal = "
                    insert into db_temp.t_temp_soprod
                    select  '', '','Z_GRAND TOTAL', '', '', '', '', '', '', '', '','','',
                            sum(ot_1),sum(ot_2),sum(ot_3),sum(ot_4),sum(ot_5),sum(ot_6),
                            sum(ot_7),sum(ot_8),sum(ot_9),sum(ot_10),sum(ot_11),sum(ot_12),
                            sum(unit_1),sum(unit_2),sum(unit_3),sum(unit_4),sum(unit_5),sum(unit_6),
                            sum(unit_7),sum(unit_8),sum(unit_9),sum(unit_10),sum(unit_11),sum(unit_12),
                            sum(value_1),sum(value_2),sum(value_3),sum(value_4),sum(value_5),sum(value_6),
                            sum(value_7),sum(value_8),sum(value_9),sum(value_10),sum(value_11),sum(value_12),
                            '999','',$id,$tgl_created
                    from    db_temp.t_temp_soprod a
                    where id = $id and a.status = 1 and a.created_date = $tgl_created
                    ";

                $sql_grandtotal_proses = $this->db->query($sql_grandtotal);
            }

            
        }else{
            return array();
        }         
        $sql = "select * from db_temp.t_temp_soprod a where a.id = $id and created_date = $tgl_created order by urutan";
        $proses = $this->db->query($sql)->result();
        if ($proses) {
            return $proses;
        }else{
            return array();
        }
        
    }

    // public function sales_per_product($data){
    //     $id=$this->session->userdata('id');
    //     if ( function_exists( 'date_default_timezone_set' ) )
    //     date_default_timezone_set('Asia/Jakarta');        
    //     $tgl_created='"'.date('Y-m-d H:i:s').'"';
    //     $tahun = $data['year'];
    //     $kodeprod = $data['kodeprod']; 
    //     $groupby= $data['groupby'];
    //     if ($groupby =='1') {
    //         $grup = ",a.kodeprod";
    //     }else{
    //         $grup = "";
    //     }

    //     $wilayah_nocab = $data['wilayah_nocab']; 
        
    //     if ($wilayah_nocab == null) {
    //         $wilayah = '';
    //     }else{
    //         $wilayah = "and nocab in ($wilayah_nocab)";
    //     }
    //         //echo " <br>daftar_dp : ".$daftar_dp;        
    //         //$daftar_dp = 'nocab in ('.'"'.$dp.'"'.')';
        
    //     /* ---------END DEFINISI VARIABEL---------------- */

    //     // /* PROSES DELETE MPM.soprod_new */
    //     // $query = "delete from db_temp.t_temp_soprod where id = ".$id."";
    //     // $sql = $this->db->query($query);

    //          /* PROSES INSERT KE MPM.soprod_new */
    //     $sql="
    //             select  a.kode,a.nocab,a.kode_comp, b.branch_name,b.nama_comp, b.sub,
    //                     a.kodeprod,c.namaprod,c.grup, d.nama_group,'' as jenis,
    //                     sum(if(blndok = '01', trans, 0)) as ot_1,
    //                     sum(if(blndok = '02', trans, 0)) as ot_2,
    //                     sum(if(blndok = '03', trans, 0)) as ot_3,	
    //                     sum(if(blndok = '04', trans, 0)) as ot_4,
    //                     sum(if(blndok = '05', trans, 0)) as ot_5,
    //                     sum(if(blndok = '06', trans, 0)) as ot_6,
    //                     sum(if(blndok = '07', trans, 0)) as ot_7,
    //                     sum(if(blndok = '08', trans, 0)) as ot_8,		
    //                     sum(if(blndok = '09', trans, 0)) as ot_9,
    //                     sum(if(blndok = '10', trans, 0)) as ot_10,
    //                     sum(if(blndok = '11', trans, 0)) as ot_11,
    //                     sum(if(blndok = '12', trans, 0)) as ot_12,
    //                     sum(if(blndok = '01', unit, 0)) as unit_1,
    //                     sum(if(blndok = '02', unit, 0)) as unit_2,	
    //                     sum(if(blndok = '03', unit, 0)) as unit_3,
    //                     sum(if(blndok = '04', unit, 0)) as unit_4,
    //                     sum(if(blndok = '05', unit, 0)) as unit_5,
    //                     sum(if(blndok = '06', unit, 0)) as unit_6,
    //                     sum(if(blndok = '07', unit, 0)) as unit_7,
    //                     sum(if(blndok = '08', unit, 0)) as unit_8,
    //                     sum(if(blndok = '09', unit, 0)) as unit_9,
    //                     sum(if(blndok = '10', unit, 0)) as unit_10,	
    //                     sum(if(blndok = '11', unit, 0)) as unit_11,
    //                     sum(if(blndok = '12', unit, 0)) as unit_12,
    //                     sum(if(blndok = '01', value, 0)) as value_1,
    //                     sum(if(blndok = '02', value, 0)) as value_2,	
    //                     sum(if(blndok = '03', value, 0)) as value_3,
    //                     sum(if(blndok = '04', value, 0)) as value_4,
    //                     sum(if(blndok = '05', value, 0)) as value_5,
    //                     sum(if(blndok = '06', value, 0)) as value_6,
    //                     sum(if(blndok = '07', value, 0)) as value_7,
    //                     sum(if(blndok = '08', value, 0)) as value_8,
    //                     sum(if(blndok = '09', value, 0)) as value_9,
    //                     sum(if(blndok = '10', value, 0)) as value_10,	
    //                     sum(if(blndok = '11', value, 0)) as value_11,
    //                     sum(if(blndok = '12', value, 0)) as value_12,
    //                     b.urutan,$id,$tgl_created
    //             from
    //             (
    //                 select nocab, kode_comp,blndok, outlet, SUM(`banyak`) as unit, SUM(`tot1`) as value, COUNT(DISTINCT(outlet)) as trans, kode,a.kodeprod
    //                 FROM
    //                 (
    //                         select nocab, kode_comp,blndok, `banyak`, `tot1`, CONCAT(KODE_COMP,kode_lang) as outlet,concat(kode_comp, nocab) as kode,kodeprod
    //                         from `data$tahun`.`fi`
    //                         where `kodeprod` in ($kodeprod) and `nodokjdi` <> 'XXXXXX' ".$wilayah."
    //                         union all
    //                         select nocab, kode_comp, blndok, `banyak`, `tot1`, null,concat(kode_comp, nocab) as kode,kodeprod
    //                         from `data$tahun`.`ri`
    //                         where `kodeprod` in ($kodeprod) and `nodokjdi` <> 'XXXXXX' ".$wilayah."
    //                 )a
    //                 group by kode, blndok $grup                                
    //             )a LEFT JOIN
    //             (
    //                             select  concat(kode_comp, nocab) as kode,naper,kode_comp, nama_comp, sub,urutan,`status`,status_cluster,branch_name
    //                             from     mpm.tbl_tabcomp
    //                             where   `status` = 1
    //                             GROUP BY concat(kode_comp, nocab)
    //             )b on a.kode = b.kode LEFT JOIN
    //             (
    //                     select a.kodeprod, a.namaprod, a.grup
    //                     from mpm.tabprod a
    //             )c on a.kodeprod = c.kodeprod left join 
    //             (
    //                     select a.kode_group, a.nama_group
    //                     from mpm.tbl_group a
    //             )d on c.grup = d.kode_group
    //             group by a.kode $grup 
    //              ";

    //     $gabung = "insert into db_temp.t_temp_soprod ".$sql;			        
    //     $sql = $this->db->query($gabung);                            
    
    //     // $hasil = $this->db->get('db_temp.t_temp_soprod');       
    //     // if ($hasil->num_rows() > 0) 
    //     // {
    //     //     return $hasil->result();
    //     // } else {
    //     //     return array();
    //     // } 
    //     if ($sql == '1') {
    //         $sql_subtotal = "
    //                         insert into db_temp.t_temp_soprod
    //                         select '','','', a.branch_name, b.nama_comp, b.sub,
    //                                 a.kodeprod, a.namaprod, a.`group`, a.nama_group,'',
    //                                 sum(ot_1),sum(ot_2),sum(ot_3),sum(ot_4),sum(ot_5),sum(ot_6),
    //                                 sum(ot_7),sum(ot_8),sum(ot_9),sum(ot_10),sum(ot_11),sum(ot_12),
    //                                 sum(unit_1),sum(unit_2),sum(unit_3),sum(unit_4),sum(unit_5),sum(unit_6),
    //                                 sum(unit_7),sum(unit_8),sum(unit_9),sum(unit_10),sum(unit_11),sum(unit_12),
    //                                 sum(value_1),sum(value_2),sum(value_3),sum(value_4),sum(value_5),sum(value_6),
    //                                 sum(value_7),sum(value_8),sum(value_9),sum(value_10),sum(value_11),sum(value_12),
    //                                 b.urutan,$id,$tgl_created
    //                         from    db_temp.t_temp_soprod a inner JOIN
    //                         (
    //                             select  nama_comp, sub, urutan
    //                             from    mpm.tbl_tabcomp
    //                             where   `status` = 2
    //                         )b on a.sub = b.sub
    //                         where id = ".$id."
    //                         GROUP BY sub
    //                         ORDER BY a.sub
    //                         ";

    //         $sql_subtotal_proses = $this->db->query($sql_subtotal);
    //         /*
    //         echo "<pre>";
    //         print_r($sql_subtotal);
    //         echo "</pre>";
    //         */
    //         if ($sql_subtotal_proses == '1' ) {
                   
    //             $sql_grandtotal = "
    //                                 insert into db_temp.t_temp_soprod
    //                                 select '','','','', 'Z_GRAND TOTAL', '',
    //                                         '', '', '', '','',
    //                                         sum(ot_1),sum(ot_2),sum(ot_3),sum(ot_4),sum(ot_5),sum(ot_6),
    //                                         sum(ot_7),sum(ot_8),sum(ot_9),sum(ot_10),sum(ot_11),sum(ot_12),
    //                                         sum(unit_1),sum(unit_2),sum(unit_3),sum(unit_4),sum(unit_5),sum(unit_6),
    //                                         sum(unit_7),sum(unit_8),sum(unit_9),sum(unit_10),sum(unit_11),sum(unit_12),
    //                                         sum(value_1),sum(value_2),sum(value_3),sum(value_4),sum(value_5),sum(value_6),
    //                                         sum(value_7),sum(value_8),sum(value_9),sum(value_10),sum(value_11),sum(value_12),
    //                                         '999',$id,$tgl_created
    //                                 from    db_temp.t_temp_soprod a inner JOIN
    //                                 (
    //                                     select  kode_comp, urutan
    //                                     from    mpm.tbl_tabcomp
    //                                     where   `status` = 1
    //                                     GROUP BY kode_comp
    //                                 )b on a.kode_comp = b.kode_comp
    //                                 where id = $id
    //                                 ";

    //             $sql_grandtotal_proses = $this->db->query($sql_grandtotal);
    //             /*
    //             echo "<pre>";
    //             print_r($sql_grandtotal);
    //             echo "</pre>";
    //             */
    //         }else{
    //                 echo "gagal dalam pembuatan grand total";
    //         }
        
    //     }else{
    //             echo "gagal dalam pembuatan sub total";
    //     }         
    //     $sql = "select * from db_temp.t_temp_soprod a where a.id = $id and created_date = $tgl_created order by urutan";
    //     $proses = $this->db->query($sql)->result();
    //     if ($proses) {
    //         return $proses;
    //     }else{
    //         return array();
    //     }
        
    // }

    public function sales_per_product_class($data){
        $id=$this->session->userdata('id');
        if ( function_exists( 'date_default_timezone_set' ) )
        date_default_timezone_set('Asia/Jakarta');        
        $tgl_created='"'.date('Y-m-d H:i:s').'"';
        $tahun = $data['year'];
        $kodeprod = $data['kodeprod']; 
        $kondisi = $data['kondisi_class'];
        $wilayah_nocab = $data['wilayah_nocab']; 

        if ($wilayah_nocab == null) {
            $wilayah = '';
        }else{
            $wilayah = "and nocab in ($wilayah_nocab)";
        }
        
        /* ---------END DEFINISI VARIABEL---------------- */

        // // /* PROSES DELETE MPM.soprod_new */
        // $query = "delete from db_temp.t_temp_soprod where id = ".$id."";
        // $sql = $this->db->query($query);

             /* PROSES INSERT KE MPM.soprod_new */
        
             if  ($kondisi == '1') {

                $sql="
                        select  kode, nocab, a.kode_comp, '' as branch_name, b.nama_comp,'' as sub,
                                '' as kodeprod,'' as namaprod,'' as grup,'' as nama_grup,jenis,
                                ot_1, ot_2, ot_3, ot_4, ot_5, ot_6,ot_7, ot_8, ot_9, ot_10, ot_11,  ot_12,
                                unit_1, unit_2, unit_3, unit_4, unit_5, unit_6, unit_7, unit_8, unit_9, unit_10, unit_11, unit_12,
                                value_1, value_2, value_3, value_4, value_5, value_6, value_7, value_8, value_9, value_10, value_11, value_12,
                                b.urutan,$id,$tgl_created
                        FROM
                        (
                            select 	nocab, kode_comp, jenis,
                                    sum(if(blndok = '01', trans, 0)) as ot_1,
                                    sum(if(blndok = '02', trans, 0)) as ot_2,
                                    sum(if(blndok = '03', trans, 0)) as ot_3,	
                                    sum(if(blndok = '04', trans, 0)) as ot_4,
                                    sum(if(blndok = '05', trans, 0)) as ot_5,
                                    sum(if(blndok = '06', trans, 0)) as ot_6,
                                    sum(if(blndok = '07', trans, 0)) as ot_7,
                                    sum(if(blndok = '08', trans, 0)) as ot_8,		
                                    sum(if(blndok = '09', trans, 0)) as ot_9,
                                    sum(if(blndok = '10', trans, 0)) as ot_10,
                                    sum(if(blndok = '11', trans, 0)) as ot_11,
                                    sum(if(blndok = '12', trans, 0)) as ot_12,
                                    sum(if(blndok = '01', unit, 0)) as unit_1,
                                    sum(if(blndok = '02', unit, 0)) as unit_2,	
                                    sum(if(blndok = '03', unit, 0)) as unit_3,
                                    sum(if(blndok = '04', unit, 0)) as unit_4,
                                    sum(if(blndok = '05', unit, 0)) as unit_5,
                                    sum(if(blndok = '06', unit, 0)) as unit_6,
                                    sum(if(blndok = '07', unit, 0)) as unit_7,
                                    sum(if(blndok = '08', unit, 0)) as unit_8,
                                    sum(if(blndok = '09', unit, 0)) as unit_9,
                                    sum(if(blndok = '10', unit, 0)) as unit_10,	
                                    sum(if(blndok = '11', unit, 0)) as unit_11,
                                    sum(if(blndok = '12', unit, 0)) as unit_12,
                                    sum(if(blndok = '01', value, 0)) as value_1,
                                    sum(if(blndok = '02', value, 0)) as value_2,	
                                    sum(if(blndok = '03', value, 0)) as value_3,
                                    sum(if(blndok = '04', value, 0)) as value_4,
                                    sum(if(blndok = '05', value, 0)) as value_5,
                                    sum(if(blndok = '06', value, 0)) as value_6,
                                    sum(if(blndok = '07', value, 0)) as value_7,
                                    sum(if(blndok = '08', value, 0)) as value_8,
                                    sum(if(blndok = '09', value, 0)) as value_9,
                                    sum(if(blndok = '10', value, 0)) as value_10,	
                                    sum(if(blndok = '11', value, 0)) as value_11,
                                    sum(if(blndok = '12', value, 0)) as value_12,		
                                    kode, kodesalur
                            from	
                                (
                                    select 	nocab, kode_comp, blndok, unit, trans, kode, kodesalur, jenis, value
                                    from
                                    (
                                        select	nocab, kode_comp, blndok, outlet, unit, trans, a.kode, a.kodesalur, b.jenis, value
                                        from
                                        (
                                            select 	nocab, kode_comp,blndok, outlet, 
                                                    SUM(`banyak`) as unit, SUM(`tot1`) as value, COUNT(DISTINCT(outlet)) as trans, kode, kodesalur
                                            FROM
                                            (
                                                select 	nocab, kode_comp,blndok, `banyak`, `tot1`,
                                                        CONCAT(KODE_COMP,kode_lang) as outlet,
                                                        concat(kode_comp, nocab) as kode, KODESALUR
                                                from 	`data$tahun`.`fi`
                                                where `kodeprod` in ($kodeprod) and `nodokjdi` <> 'XXXXXX' ".$wilayah."
                                                
                                                union all
                                                
                                                select 	nocab, kode_comp, blndok, `banyak`, `tot1`,
                                                        null,concat(kode_comp, nocab) as kode, KODESALUR
                                                from 	`data$tahun`.`ri`
                                                where   `kodeprod` in ($kodeprod) and `nodokjdi` <> 'XXXXXX' ".$wilayah."
                                            )a
                                            group by kode, blndok,kodesalur     
                                        )a LEFT JOIN 
                                            (
                                                select kode,jenis 
                                                from mpm.tbl_tabsalur
                                            )b on a.kodesalur = b.kode
                                            ORDER BY nocab, blndok
                                    )a
                                )a GROUP BY jenis, kode
                            )a LEFT JOIN 
                            (
                            select 	kode_comp, nama_comp, urutan
                            from 		mpm.tbl_tabcomp
                            where		status = 1
                            GROUP BY kode_comp
                            )b on a.kode_comp = b.kode_comp
                            ORDER BY urutan asc, jenis
                            ";
            }else{
                    $sql="
                        select  kode, nocab, a.kode_comp, '' as branch_name, b.nama_comp, '' as sub,
                                '' as kodeprod,'' as namaprod,'' as grup,'' as nama_grup,jenis,
                                ot_1, ot_2, ot_3, ot_4, ot_5, ot_6, ot_7, ot_8, ot_9, ot_10, ot_11, ot_12,
                                unit_1, unit_2, unit_3, unit_4, unit_5, unit_6, unit_7, unit_8, unit_9, unit_10, unit_11, unit_12,
                                value_1, value_2, value_3, value_4, value_5, value_6, value_7, value_8, value_9, value_10, value_11, value_12,
                                urutan, $id,$tgl_created
                        FROM
                        (
                            select 	nocab, kode_comp, jenis, kode, kodesalur,
                                    sum(if(blndok = '01', trans, 0)) as ot_1,
                                    sum(if(blndok = '02', trans, 0)) as ot_2,
                                    sum(if(blndok = '03', trans, 0)) as ot_3,	
                                    sum(if(blndok = '04', trans, 0)) as ot_4,
                                    sum(if(blndok = '05', trans, 0)) as ot_5,
                                    sum(if(blndok = '06', trans, 0)) as ot_6,
                                    sum(if(blndok = '07', trans, 0)) as ot_7,
                                    sum(if(blndok = '08', trans, 0)) as ot_8,		
                                    sum(if(blndok = '09', trans, 0)) as ot_9,
                                    sum(if(blndok = '10', trans, 0)) as ot_10,
                                    sum(if(blndok = '11', trans, 0)) as ot_11,
                                    sum(if(blndok = '12', trans, 0)) as ot_12,
                                    sum(if(blndok = '01', unit, 0)) as unit_1,
                                    sum(if(blndok = '02', unit, 0)) as unit_2,	
                                    sum(if(blndok = '03', unit, 0)) as unit_3,
                                    sum(if(blndok = '04', unit, 0)) as unit_4,
                                    sum(if(blndok = '05', unit, 0)) as unit_5,
                                    sum(if(blndok = '06', unit, 0)) as unit_6,
                                    sum(if(blndok = '07', unit, 0)) as unit_7,
                                    sum(if(blndok = '08', unit, 0)) as unit_8,
                                    sum(if(blndok = '09', unit, 0)) as unit_9,
                                    sum(if(blndok = '10', unit, 0)) as unit_10,	
                                    sum(if(blndok = '11', unit, 0)) as unit_11,
                                    sum(if(blndok = '12', unit, 0)) as unit_12,
                                    sum(if(blndok = '01', value, 0)) as value_1,
                                    sum(if(blndok = '02', value, 0)) as value_2,	
                                    sum(if(blndok = '03', value, 0)) as value_3,
                                    sum(if(blndok = '04', value, 0)) as value_4,
                                    sum(if(blndok = '05', value, 0)) as value_5,
                                    sum(if(blndok = '06', value, 0)) as value_6,
                                    sum(if(blndok = '07', value, 0)) as value_7,
                                    sum(if(blndok = '08', value, 0)) as value_8,
                                    sum(if(blndok = '09', value, 0)) as value_9,
                                    sum(if(blndok = '10', value, 0)) as value_10,	
                                    sum(if(blndok = '11', value, 0)) as value_11,
                                    sum(if(blndok = '12', value, 0)) as value_12           
                            FROM
                            (
                                select a.nocab,a.kode_comp,a.kode,outlet,unit, trans, blndok, b.kodesalur, c.jenis, value
                                FROM
                                (
                                    select 	nocab, kode_comp, blndok, outlet, 
                                            SUM(`banyak`) as unit,  SUM(`tot1`) as value, COUNT(DISTINCT(outlet)) as trans, kode
                                    FROM
                                    (
                                        select 	nocab, kode_comp,blndok, `banyak`, `tot1`, 
                                                CONCAT(KODE_COMP,kode_lang) as outlet,
                                                concat(kode_comp, nocab) as kode, KODESALUR
                                        from 	`data$tahun`.`fi`
                                        where `kodeprod` in ($kodeprod) and `nodokjdi` <> 'XXXXXX' ".$wilayah."                            
                                        union all
                                        select 	nocab, kode_comp, blndok, `banyak`, `tot1`, 
                                                null,concat(kode_comp, nocab) as kode, KODESALUR
                                        from 	`data$tahun`.`ri`
                                        where   `kodeprod` in ($kodeprod) and `nodokjdi` <> 'XXXXXX' ".$wilayah."
                                    )a
                                    group by kode, outlet, blndok     
                                )a LEFT JOIN
                                (
                                    select kode, kodesalur
                                    from db_lang.tbl_bantu_pelanggan_$tahun a
                                )b on a.outlet = b.kode LEFT JOIN
                                (
                                    select a.kode,a.jenis 
                                    from mpm.tbl_tabsalur a
                                )c on b.kodesalur = c.kode
                            )a GROUP BY jenis, kode
                        )a LEFT JOIN 
                        (
                            select 	kode_comp, nama_comp, urutan
                            from 	mpm.tbl_tabcomp
                            where	status = 1
                            GROUP BY kode_comp
                        )b on a.kode_comp = b.kode_comp
                        ORDER BY urutan asc, jenis
                        ";
    
                }
    
            $gabung = "insert into db_temp.t_temp_soprod ".$sql;			        
            $sql = $this->db->query($gabung);
     
              
            $sql = "select * from db_temp.t_temp_soprod a where a.id = $id and created_date = $tgl_created order by urutan";
            $proses = $this->db->query($sql)->result();
            if ($proses) {
                return $proses;
            }else{
                return array();
            }
        }
        
        public function outlet($data,$kodeprod=null){
       
            $user=$this->session->userdata('username');
            if ( function_exists( 'date_default_timezone_set' ) )
            date_default_timezone_set('Asia/Jakarta');        
    
            /* ---------DEFINISI VARIABEL----------------- */
        
            $tgl_created='"'.date('Y-m-d H:i:s').'"';        
            $id=$this->session->userdata('id');
            $tahun = $data['tahun'];
            $kodeprod = $data['kodeprod']; 
            $sales = $data['sm'];
            $bd =$data['bd'];
            $naper = $data['dp'];
            if($bd == 1){
                $tipe = ',kodeprod';
            }else{
                $tipe = '';
            }
    
                 /* ---------END DEFINISI VARIABEL---------------- */
    
            foreach($naper as $dp){
    
                $nocab = substr($dp, 3);
    
                // echo "nocab : ".$nocab;
                // echo "<br>dp : ".$dp;
    
                /* cari nilai kode_comp */
                $this->db->where('concat(kode_comp,nocab) = '.'"'.$dp.'"');
                $this->db->where('status = 1');
                $query = $this->db->get('mpm.tbl_tabcomp');
                foreach ($query->result() as $row) {
                    $kode_comp = $row->kode_comp;
                    // echo "kode_comp : ".$kode_comp."<br />";
                }
                /* end cari nilai kode_comp */
    
                /* cek jumlah row master outlet di tblang */
                $this->db->select('count(*) as rows');
                $this->db->where('kode_comp = '.'"'.$kode_comp.'"');
                $query = $this->db->get("data$tahun.tblang");
                foreach($query->result() as $r)
                {
                    $total = $r->rows;
                }
                // echo "<pre>";
                // print_r($total);
                // echo "</pre>";
                /* cek jumlah row master outlet di tblang */
    
                /* cek jumlah row outlet di doutlet */
                $this->db->select('count(*) as rows');
                $query = $this->db->get("dboutlet.tblang$kode_comp");
                foreach($query->result() as $r)
                {
                    $total2 = $r->rows;
                }
                /*
                echo "<pre>";
                print_r($total2);
                echo "</pre>";*/
               /* cek jumlah row master outlet di tblang */
    
              if ($total <> $total2) {
                $sql = "delete from dboutlet.tblang$kode_comp";
                $this->db->query($sql);
    
                $query2 = "
                    insert into dboutlet.tblang$kode_comp
                    select  *
                    from    data$tahun.tblang
                    where   nocab = ".'"'.$nocab.'"'."
                ";
                $sql2 = $this->db->query($query2);
    
              }
    
               $naper = $nocab;
    
               if ($naper == '91') {
                
                $sql=" 
                insert into db_temp.t_temp_outlet
                select  a.kode,nama_lang as outlet,alamat1 as address,
                        kode_type,b.kodesalur,b.koderayon,b.kode_kota as kota,kodeprod,
                        b1,b2,b3,b4,b5,b6,
                        b7,b8,b9,b10,b11,b12,
                        v1,v2,v3,v4,v5,v6,
                        v7,v8,v9,v10,v11,v12, $id, $tgl_created
                from    (
                            select  kode, kode_type, kodesalur,kodeprod,
                                    sum(if(blndok=1,unit,0)) as b1,sum(if(blndok=2,unit,0)) as b2,
                                    sum(if(blndok=3,unit,0)) as b3,sum(if(blndok=4,unit,0)) as b4,
                                    sum(if(blndok=5,unit,0)) as b5,sum(if(blndok=6,unit,0)) as b6,
                                    sum(if(blndok=7,unit,0)) as b7,sum(if(blndok=8,unit,0)) as b8,
                                    sum(if(blndok=9,unit,0)) as b9,sum(if(blndok=10,unit,0)) as b10,
                                    sum(if(blndok=11,unit,0)) as b11,sum(if(blndok=12,unit,0)) as b12,
                                    sum(if(blndok=1,`value`,0)) as v1,sum(if(blndok=2,`value`,0)) as v2,
                                    sum(if(blndok=3,`value`,0)) as v3,sum(if(blndok=4,`value`,0)) as v4,
                                    sum(if(blndok=5,`value`,0)) as v5,sum(if(blndok=6,`value`,0)) as v6,
                                    sum(if(blndok=7,`value`,0)) as v7,sum(if(blndok=8,`value`,0)) as v8,
                                    sum(if(blndok=9,`value`,0)) as v9,sum(if(blndok=10,`value`,0)) as v10,
                                    sum(if(blndok=11,`value`,0)) as v11,sum(if(blndok=12,`value`,0)) as v12
                            from
                            (
                                select  concat(kode_comp,kode_lang,kode_kota,koderayon) kode, KODE_TYPE, kodesalur,
                                        blndok,kodeprod, sum(banyak) as unit, sum(tot1) as `value`
                                from    data$tahun.fi 
                                where   nocab = ".'"'.$naper.'"'." and 
                                        concat(kodesales) like '%$sales%' and 
                                        kodeprod in ($kodeprod)  
                                group by kode, blndok $tipe
                                            
                                union all
                                
                                select  concat(kode_comp,kode_lang,kode_kota,koderayon) kode, KODE_TYPE,  kodesalur,
                                        blndok,kodeprod, sum(banyak) as unit, sum(tot1) as `value`
                                from    data$tahun.ri 
                                where   nocab = ".'"'.$naper.'"'."and concat(kodesales) like '%$sales%' and 
                                        kodeprod in ($kodeprod)  
                                group by kode, blndok $tipe
                            ) a                               
                            group by kode
                        )a
                        
                        left join
                        (
                            select  distinct
                                    concat(kode_comp,kode_lang,kode_kota,koderayon) kode,
                                    koderayon,
                                    nama_lang,
                                    alamat1,
                                    kode_kota, kodesalur
                            from    dboutlet.tblang".$kode_comp."
                        )b on a.kode=b.kode
                        group by kode $tipe                                   
                    ";
    
                    // echo "<pre>";
                    // print_r($sql);
                    // echo "</pre>";
    
            }
    
            if ($naper == '91' && $tahun == '2016') {
                        
            $sql=" 
                 INSERT into db_temp.t_temp_outlet
                    select  a.kode,nama_lang as outlet,alamat1 as address,
                            kode_type,b.kodesalur,b.koderayon,b.kode_kota as kota,kodeprod,
                            b1,b2,b3,b4,b5,b6,
                            b7,b8,b9,b10,b11,b12,
                            v1,v2,v3,v4,v5,v6,
                            v7,v8,v9,v10,v11,v12, $id, $tgl_created
                    from    (
                                select  kode, kode_type, kodesalur,kodeprod,
                                        sum(if(blndok=1,unit,0)) as b1,sum(if(blndok=2,unit,0)) as b2,
                                        sum(if(blndok=3,unit,0)) as b3,sum(if(blndok=4,unit,0)) as b4,
                                        sum(if(blndok=5,unit,0)) as b5,sum(if(blndok=6,unit,0)) as b6,
                                        sum(if(blndok=7,unit,0)) as b7,sum(if(blndok=8,unit,0)) as b8,
                                        sum(if(blndok=9,unit,0)) as b9,sum(if(blndok=10,unit,0)) as b10,
                                        sum(if(blndok=11,unit,0)) as b11,sum(if(blndok=12,unit,0)) as b12,
                                        sum(if(blndok=1,`value`,0)) as v1,sum(if(blndok=2,`value`,0)) as v2,
                                        sum(if(blndok=3,`value`,0)) as v3,sum(if(blndok=4,`value`,0)) as v4,
                                        sum(if(blndok=5,`value`,0)) as v5,sum(if(blndok=6,`value`,0)) as v6,
                                        sum(if(blndok=7,`value`,0)) as v7,sum(if(blndok=8,`value`,0)) as v8,
                                        sum(if(blndok=9,`value`,0)) as v9,sum(if(blndok=10,`value`,0)) as v10,
                                        sum(if(blndok=11,`value`,0)) as v11,sum(if(blndok=12,`value`,0)) as v12
                                from
                                    (
                                        select  concat(kode_comp,kode_lang) kode, KODE_TYPE, kodesalur,
                                                blndok,kodeprod, sum(banyak) as unit, sum(tot1) as `value`
                                        from    data$tahun.fi 
                                        where   nocab = ".'"'.$naper.'"'." and 
                                                concat(kodesales) like '%$sales%' and 
                                                kodeprod in ($kodeprod)  
                                        group by kode, blndok $tipe
                                                    
                                        union all
                                        
                                        select  concat(kode_comp,kode_lang) kode, KODE_TYPE, kodesalur,
                                                blndok,kodeprod, sum(banyak) as unit, sum(tot1) as `value`
                                        from    data$tahun.ri 
                                        where   nocab = ".'"'.$naper.'"'."and concat(kodesales) like '%$sales%' and 
                                                kodeprod in ($kodeprod)  
                                        group by kode, blndok $tipe
                                    ) a                               
                                    group by kode
                            )a
                            left join
                            (
                                select  distinct
                                        concat(kode_comp,kode_lang) kode,
                                        koderayon,
                                        nama_lang,
                                        alamat1,
                                        kode_kota, kodesalur
                                from    dboutlet.tblang".$kode_comp."
                            )b on a.kode=b.kode group by kode                                       
                             ";
    
                            // echo "<pre>";
                            // print_r($sql);
                            // echo "</pre>";
    
            }
             /* -- KHUSUS SIODARJO, KODE OUTLET NYA CONCAT(NOCAB,KODE_LANG) */
            elseif ($naper == '46' && $tahun== '2016' || $tahun== '2015' || $tahun== '2014' || $tahun== '2013' || $tahun== '2012' || $tahun== '2011' || $tahun== '2010') { 
                        
                $sql=" 
                INSERT into db_temp.t_temp_outlet
                select  a.kode,nama_lang as outlet,alamat1 as address,
                        kode_type,b.kodesalur,b.koderayon,b.kode_kota as kota,kodeprod,
                        b1,b2,b3,b4,b5,b6,
                        b7,b8,b9,b10,b11,b12,
                        v1,v2,v3,v4,v5,v6,
                        v7,v8,v9,v10,v11,v12, $id, $tgl_created
                from    (
                            select  kode, kode_type, kodesalur,kodeprod,
                                    sum(if(blndok=1,unit,0)) as b1,sum(if(blndok=2,unit,0)) as b2,
                                    sum(if(blndok=3,unit,0)) as b3,sum(if(blndok=4,unit,0)) as b4,
                                    sum(if(blndok=5,unit,0)) as b5,sum(if(blndok=6,unit,0)) as b6,
                                    sum(if(blndok=7,unit,0)) as b7,sum(if(blndok=8,unit,0)) as b8,
                                    sum(if(blndok=9,unit,0)) as b9,sum(if(blndok=10,unit,0)) as b10,
                                    sum(if(blndok=11,unit,0)) as b11,sum(if(blndok=12,unit,0)) as b12,
                                    sum(if(blndok=1,`value`,0)) as v1,sum(if(blndok=2,`value`,0)) as v2,
                                    sum(if(blndok=3,`value`,0)) as v3,sum(if(blndok=4,`value`,0)) as v4,
                                    sum(if(blndok=5,`value`,0)) as v5,sum(if(blndok=6,`value`,0)) as v6,
                                    sum(if(blndok=7,`value`,0)) as v7,sum(if(blndok=8,`value`,0)) as v8,
                                    sum(if(blndok=9,`value`,0)) as v9,sum(if(blndok=10,`value`,0)) as v10,
                                    sum(if(blndok=11,`value`,0)) as v11,sum(if(blndok=12,`value`,0)) as v12
                            from
                            (
                                select  concat(nocab,kode_lang) kode, KODE_TYPE, kodesalur,
                                        blndok,kodeprod, sum(banyak) as unit, sum(tot1) as `value`
                                from    data$tahun.fi 
                                where   nocab = ".'"'.$naper.'"'." and 
                                        concat(kodesales) like '%$sales%' and 
                                        kodeprod in ($kodeprod)  
                                group by kode, blndok $tipe
                                            
                                union all
                                
                                select  concat(nocab,kode_lang) kode, KODE_TYPE, kodesalur,
                                        blndok,kodeprod, sum(banyak) as unit, sum(tot1) as `value`
                                from    data$tahun.ri 
                                where   nocab = ".'"'.$naper.'"'."and concat(kodesales) like '%$sales%' and 
                                        kodeprod in ($kodeprod)  
                                group by kode, blndok $tipe
                            ) a                               
                            group by kode
                        )a
                        
                        left join
                        (
                            select  distinct
                                    concat(nocab,kode_lang) kode,
                                    koderayon,
                                    nama_lang,
                                    alamat1,
                                    kode_kota, kodesalur
                            from    dboutlet.tblang".$kode_comp."
                        )b on a.kode=b.kode group by kode                                         
                    ";
                    // echo "<pre>";
                    // print_r($sql);
                    // echo "</pre>";
    
            }
            /* -- KHUSUS SIODARJO 2017, KODE OUTLET NYA CONCAT(NOCAB,KODE_COMP,KODE_LANG) */
            elseif ($naper == '46' && $year == '2017') { 
                        
                $sql=" 
                    INSERT into db_temp.t_temp_outlet
                    select  a.kode,nama_lang as outlet,alamat1 as address,
                            kode_type,b.kodesalur,b.koderayon,b.kode_kota as kota,kodeprod,
                            b1,b2,b3,b4,b5,b6,
                            b7,b8,b9,b10,b11,b12,
                            v1,v2,v3,v4,v5,v6,
                            v7,v8,v9,v10,v11,v12, $id, $tgl_created
                    from    (
                                select  kode, kode_type, kodesalur,kodeprod,
                                        sum(if(blndok=1,unit,0)) as b1,sum(if(blndok=2,unit,0)) as b2,
                                        sum(if(blndok=3,unit,0)) as b3,sum(if(blndok=4,unit,0)) as b4,
                                        sum(if(blndok=5,unit,0)) as b5,sum(if(blndok=6,unit,0)) as b6,
                                        sum(if(blndok=7,unit,0)) as b7,sum(if(blndok=8,unit,0)) as b8,
                                        sum(if(blndok=9,unit,0)) as b9,sum(if(blndok=10,unit,0)) as b10,
                                        sum(if(blndok=11,unit,0)) as b11,sum(if(blndok=12,unit,0)) as b12,
                                        sum(if(blndok=1,`value`,0)) as v1,sum(if(blndok=2,`value`,0)) as v2,
                                        sum(if(blndok=3,`value`,0)) as v3,sum(if(blndok=4,`value`,0)) as v4,
                                        sum(if(blndok=5,`value`,0)) as v5,sum(if(blndok=6,`value`,0)) as v6,
                                        sum(if(blndok=7,`value`,0)) as v7,sum(if(blndok=8,`value`,0)) as v8,
                                        sum(if(blndok=9,`value`,0)) as v9,sum(if(blndok=10,`value`,0)) as v10,
                                        sum(if(blndok=11,`value`,0)) as v11,sum(if(blndok=12,`value`,0)) as v12
                                from
                                (
                                    select  concat(nocab,kode_comp,kode_lang) kode, KODE_TYPE, kodesalur,
                                            blndok,kodeprod, sum(banyak) as unit, sum(tot1) as `value`
                                    from    data$tahun.fi 
                                    where   nocab = ".'"'.$naper.'"'." and 
                                            concat(kodesales) like '%$sales%' and 
                                            kodeprod in ($kodeprod)  
                                    group by kode, blndok $tipe
                                                
                                    union all
                                    
                                    select  concat(nocab,kode_comp,kode_lang) kode, KODE_TYPE, kodesalur,
                                            blndok,kodeprod, sum(banyak) as unit, sum(tot1) as `value`
                                    from    data$tahun.ri 
                                    where   nocab = ".'"'.$naper.'"'."and concat(kodesales) like '%$sales%' and 
                                            kodeprod in ($kodeprod)  
                                    group by kode, blndok $tipe
                                ) a                               
                                group by kode
                            )a
                            
                            left join
                            (
                                select  distinct
                                        concat(nocab,kode_comp,kode_lang) kode,
                                        koderayon,
                                        nama_lang,
                                        alamat1,
                                        kode_kota, kodesalur
                                from    dboutlet.tblang".$kode_comp."
                            )b on a.kode=b.kode group by kode                                         
                        ";
                        // echo "<pre>";
                        // print_r($sql);
                        // echo "</pre>";
            }
            /* -- KHUSUS karawang, KODE OUTLET NYA CONCAT(NOCAB,KODE_COMP) */
            elseif ($naper == '07') { 
                        
                $sql=" 
                    insert into db_temp.t_temp_outlet
                    select  a.kode,nama_lang as outlet,alamat1 as address,
                            kode_type,b.kodesalur,b.koderayon,b.kode_kota as kota,kodeprod,
                            b1,b2,b3,b4,b5,b6,
                            b7,b8,b9,b10,b11,b12,
                            v1,v2,v3,v4,v5,v6,
                            v7,v8,v9,v10,v11,v12, $id, $tgl_created
                    from    (
                                select  kode, kode_type, kodesalur,kodeprod,
                                        sum(if(blndok=1,unit,0)) as b1,sum(if(blndok=2,unit,0)) as b2,
                                        sum(if(blndok=3,unit,0)) as b3,sum(if(blndok=4,unit,0)) as b4,
                                        sum(if(blndok=5,unit,0)) as b5,sum(if(blndok=6,unit,0)) as b6,
                                        sum(if(blndok=7,unit,0)) as b7,sum(if(blndok=8,unit,0)) as b8,
                                        sum(if(blndok=9,unit,0)) as b9,sum(if(blndok=10,unit,0)) as b10,
                                        sum(if(blndok=11,unit,0)) as b11,sum(if(blndok=12,unit,0)) as b12,
                                        sum(if(blndok=1,`value`,0)) as v1,sum(if(blndok=2,`value`,0)) as v2,
                                        sum(if(blndok=3,`value`,0)) as v3,sum(if(blndok=4,`value`,0)) as v4,
                                        sum(if(blndok=5,`value`,0)) as v5,sum(if(blndok=6,`value`,0)) as v6,
                                        sum(if(blndok=7,`value`,0)) as v7,sum(if(blndok=8,`value`,0)) as v8,
                                        sum(if(blndok=9,`value`,0)) as v9,sum(if(blndok=10,`value`,0)) as v10,
                                        sum(if(blndok=11,`value`,0)) as v11,sum(if(blndok=12,`value`,0)) as v12
                                from
                                (
                                    select  concat(kode_comp,kode_lang) kode, kode_lang, KODE_TYPE, kodesalur,
                                            blndok,kodeprod, sum(banyak) as unit, sum(tot1) as `value`
                                    from    data$tahun.fi 
                                    where   nocab = ".'"'.$naper.'"'." and 
                                            concat(kodesales) like '%$sales%' and 
                                            kodeprod in ($kodeprod)  
                                    group by kode, blndok $tipe
                                                
                                    union all
                                    
                                    select  concat(kode_comp,kode_lang) kode, kode_lang, KODE_TYPE,  kodesalur,
                                            blndok,kodeprod, sum(banyak) as unit, sum(tot1) as `value`
                                    from    data$tahun.ri 
                                    where   nocab = ".'"'.$naper.'"'."and concat(kodesales) like '%$sales%' and 
                                            kodeprod in ($kodeprod)  
                                    group by kode, blndok $tipe
                                ) a
                                
                                group by kode_lang
                            )a
                            
                            left join
                            (
                                select  distinct concat(kode_comp,kode_lang) kode,
                                        koderayon,
                                        nama_lang,
                                        alamat1,
                                        kode_kota, kodesalur
                                from    dboutlet.tblang".$kode_comp."
                            )b on a.kode=b.kode group by kode                                        
                    ";
                    // echo "<pre>";
                    // print_r($sql);
                    // echo "</pre>";
    
            } 
            else{
                $sql=" 
                    INSERT into db_temp.t_temp_outlet
                    select  a.kode,nama_lang as outlet,alamat1 as address,
                            kode_type,b.kodesalur,b.koderayon,b.kode_kota as kota,kodeprod,
                            b1,b2,b3,b4,b5,b6,
                            b7,b8,b9,b10,b11,b12,
                            v1,v2,v3,v4,v5,v6,
                            v7,v8,v9,v10,v11,v12, $id, $tgl_created
                    from    (
                                select  kode, kode_type, kodesalur,kodeprod,
                                        sum(if(blndok=1,unit,0)) as b1,sum(if(blndok=2,unit,0)) as b2,
                                        sum(if(blndok=3,unit,0)) as b3,sum(if(blndok=4,unit,0)) as b4,
                                        sum(if(blndok=5,unit,0)) as b5,sum(if(blndok=6,unit,0)) as b6,
                                        sum(if(blndok=7,unit,0)) as b7,sum(if(blndok=8,unit,0)) as b8,
                                        sum(if(blndok=9,unit,0)) as b9,sum(if(blndok=10,unit,0)) as b10,
                                        sum(if(blndok=11,unit,0)) as b11,sum(if(blndok=12,unit,0)) as b12,
                                        sum(if(blndok=1,`value`,0)) as v1,sum(if(blndok=2,`value`,0)) as v2,
                                        sum(if(blndok=3,`value`,0)) as v3,sum(if(blndok=4,`value`,0)) as v4,
                                        sum(if(blndok=5,`value`,0)) as v5,sum(if(blndok=6,`value`,0)) as v6,
                                        sum(if(blndok=7,`value`,0)) as v7,sum(if(blndok=8,`value`,0)) as v8,
                                        sum(if(blndok=9,`value`,0)) as v9,sum(if(blndok=10,`value`,0)) as v10,
                                        sum(if(blndok=11,`value`,0)) as v11,sum(if(blndok=12,`value`,0)) as v12
                                from
                                (
                                        select  concat(kode_comp,kode_lang) kode, KODE_TYPE, kodesalur,
                                                blndok,kodeprod, sum(banyak) as unit, sum(tot1) as `value`
                                        from    data$tahun.fi 
                                        where   nocab = ".'"'.$naper.'"'." and 
                                                concat(kodesales) like '%$sales%' and 
                                                kodeprod in ($kodeprod)  
                                        group by kode, blndok $tipe
                                                                
                                        union all
                                        
                                        select  concat(kode_comp,kode_lang) kode, KODE_TYPE, kodesalur,
                                                blndok,kodeprod, sum(banyak) as unit, sum(tot1) as `value`
                                        from    data$tahun.ri 
                                        where   nocab = ".'"'.$naper.'"'."and concat(kodesales) like '%$sales%' and 
                                                kodeprod in ($kodeprod)  
                                        group by kode, blndok $tipe
                                ) a                               
                                group by kode $tipe
                        )a  
                        left join
                        (
                                select  distinct concat(kode_comp,kode_lang) kode,
                                        koderayon, nama_lang, alamat1,
                                        kode_kota, kodesalur
                                from    dboutlet.tblang$kode_comp
                        )b on a.kode=b.kode 
                        group by kode $tipe
    
                    union all
                    
                    select  '','z_total_$kode_comp','','','','','','',
                            b1,b2,b3,b4,b5,b6,
                            b7,b8,b9,b10,b11,b12,
                            v1,v2,v3,v4,v5,v6,
                            v7,v8,v9,v10,v11,v12, $id, $tgl_created
                    FROM
                    (
                            select  kode, kode_type, kodesalur,
                                    sum(if(blndok=1,unit,0)) as b1,sum(if(blndok=2,unit,0)) as b2,
                                    sum(if(blndok=3,unit,0)) as b3,sum(if(blndok=4,unit,0)) as b4,
                                    sum(if(blndok=5,unit,0)) as b5,sum(if(blndok=6,unit,0)) as b6,
                                    sum(if(blndok=7,unit,0)) as b7,sum(if(blndok=8,unit,0)) as b8,
                                    sum(if(blndok=9,unit,0)) as b9,sum(if(blndok=10,unit,0)) as b10,
                                    sum(if(blndok=11,unit,0)) as b11,sum(if(blndok=12,unit,0)) as b12,
                                    sum(if(blndok=1,`value`,0)) as v1,sum(if(blndok=2,`value`,0)) as v2,
                                    sum(if(blndok=3,`value`,0)) as v3,sum(if(blndok=4,`value`,0)) as v4,
                                    sum(if(blndok=5,`value`,0)) as v5,sum(if(blndok=6,`value`,0)) as v6,
                                    sum(if(blndok=7,`value`,0)) as v7,sum(if(blndok=8,`value`,0)) as v8,
                                    sum(if(blndok=9,`value`,0)) as v9,sum(if(blndok=10,`value`,0)) as v10,
                                    sum(if(blndok=11,`value`,0)) as v11,sum(if(blndok=12,`value`,0)) as v12
                            from
                            (
                                    select  concat(kode_comp,kode_lang) kode, KODE_TYPE, kodesalur,
                                            blndok, sum(banyak) as unit, sum(tot1) as `value`
                                    from    data$tahun.fi 
                                    where   nocab = ".'"'.$naper.'"'."and concat(kodesales) like '%$sales%' and 
                                            kodeprod in ($kodeprod)  
                                    group by kode, blndok
                                                            
                                    union all
                                    
                                    select  concat(kode_comp,kode_lang) kode, KODE_TYPE, kodesalur,
                                            blndok, sum(banyak) as unit, sum(tot1) as `value`
                                    from    data$tahun.ri 
                                    where   nocab = ".'"'.$naper.'"'." and 
                                            concat(kodesales) like '%$sales%' and 
                                            kodeprod in ($kodeprod)  
                                    group by kode, blndok 
                            )a
                            
                    )a                                          
                    ";
                
                    // echo "<pre>";
                    // print_r($sql);
                    // echo "</pre>";
                }      
             /* PROSES INSERT KE db_temp.t_temp_outlet */
               
            $sql_insert = $this->db->query($sql);
    
            /* PROSES TAMPIL KE WEBSITE */
          }   
            $sql = "select * from db_temp.t_temp_outlet a where a.id = $id and created_date = $tgl_created ";      
            $proses = $this->db->query($sql);
            if ($proses->num_rows() > 0) 
            {
                return $proses->result();
            } else {
                return array();
            }
                
            /* END PROSES TAMPIL KE WEBSITE */
      
        }

        public function raw_data($data){
            $supp = $this->session->userdata('supp');
            echo "supp : ".$supp;

            if ($supp == '000') {
                $where = '';
            }else{
                $where = "and supp = '$supp'";
            }

            $sql = "
                select a.supp, a.nama, a.target, a.keterangan
                from db_raw.t_list_raw a
                where a.status = 1 $where
                order by id desc
            ";
            $proses = $this->db->query($sql)->result();
            if ($proses) {
                return $proses;
            }else{
                return array();
            }
        }
    
    public function actual_vs_target($data){
        $id=$this->session->userdata('id');
        if ( function_exists( 'date_default_timezone_set' ) )
        date_default_timezone_set('Asia/Jakarta');        
        $tgl_created='"'.date('Y-m-d H:i:s').'"';
        $tahun = $data['tahun'];
        $bulan = $data['bulan'];
        $kodeprod = $data['kodeprod'];
        $breakdown_kodeprod = $data['breakdown_kodeprod'];
        // $breakdown_kodesalur = $data['breakdown_kodesalur'];
            
        if ($breakdown_kodeprod == 1) {
            $group_by_kodeprod = ",a.kodeprod";
            $group_by_kodeprod_on = "and a.kodeprod = b.kodeprod";
        }else{
            $group_by_kodeprod = "";
            $group_by_kodeprod_on = "";
        }

        // if ($breakdown_kodesalur == 1) {
        //     $group_by_kodesalur = ",a.kodesalur";
        // }else{
        //     $group_by_kodesalur = "";
        // }

        $wilayah_nocab = $data['wilayah_nocab']; 
        if ($wilayah_nocab == null) {
            $wilayah = '';
        }else{
            $wilayah = "and nocab in ($wilayah_nocab)";
        }

        // echo "<pre>";
        // echo "tahun : ".$tahun."<br>";
        // echo "bulan : ".$bulan."<br>";
        // echo "kodeprod : ".$kodeprod."<br>";
        // echo "breakdown_kodeprod : ".$breakdown_kodeprod."<br>";
        // echo "breakdown_kodesalur : ".$breakdown_kodesalur."<br>";
        // echo "</pre>";

        $sql = "
        insert db_temp.t_temp_actual_vs_target
        select	a.kode,c.branch_name,c.nama_comp,c.sub,
                a.kodeprod,d.namaprod,d.grup,e.nama_group,
                bulan,a.unit as actual_unit, b.unit as target_unit, a.unit / b.unit as acv_unit,
                a.value as actual_value, b.value as target_value, a.value / b.value as acv_value, c.urutan, c.status, $id,$tgl_created
        FROM
        (
            select kode, a.kodeprod, bulan, SUM(banyak) as unit, SUM(tot1) as value
            FROM
            (
                select concat(kode_comp, nocab) as kode, banyak,tot1, kodeprod, bulan
                from `data$tahun`.`fi`
                where `kodeprod` in ($kodeprod) and `nodokjdi` <> 'XXXXXX' 
                and bulan = $bulan $wilayah
                union all
                select concat(kode_comp, nocab) as kode, banyak, tot1,kodeprod, bulan
                from `data$tahun`.`ri`
                where `kodeprod` in ($kodeprod) and `nodokjdi` <> 'XXXXXX' 
                and bulan = $bulan $wilayah
            )a
            group by kode  $group_by_kodeprod
        )a LEFT JOIN 
        (
            select a.kode,a.kodeprod,sum(a.value) as value, sum(a.unit) as unit
            from db_temp.t_temp_monitoring_stock_target_mentah a
            where bulan = $bulan
            group by a.kode $group_by_kodeprod
        )b on a.kode = b.kode $group_by_kodeprod_on LEFT JOIN
        (
            select  concat(kode_comp, nocab) as kode, branch_name, nama_comp, sub, urutan,status
            from     mpm.tbl_tabcomp
            where   `status` = 1
            GROUP BY concat(kode_comp, nocab)
        )c on a.kode = c.kode LEFT JOIN
        (
            select a.kodeprod, a.namaprod, a.grup
            from mpm.tabprod a
        )d on a.kodeprod = d.kodeprod left join 
        (
            select a.kode_group, a.nama_group
            from mpm.tbl_group a
        )e on d.grup = e.kode_group
        order by urutan asc, kodeprod
        ";
        $proses = $this->db->query($sql);

        if ($proses) {
            $sql_subtotal = "
                insert db_temp.t_temp_actual_vs_target
                select  '' as kode, a.branch_name, a.nama_comp,a.sub,
                        '' as kodeprod, '' as namaprod, '' as grup,'' as nama_grup,
                        bulan,act_u, t_u, act_u/t_u as acv_unit,act_v,
                        t_v, act_v/t_v as acv_value,a.urutan,a.status,
                        $id,$tgl_created
                from (
                select  a.branch_name, b.nama_comp,b.sub,
                        bulan,sum(a.actual_unit) as act_u, sum(a.target_unit) as t_u,sum(a.actual_value) as act_v,
                        sum(a.target_value) as t_v,b.urutan,b.status
                from    db_temp.t_temp_actual_vs_target a inner JOIN
                (
                    select  nama_comp, status, sub, urutan
                    from    mpm.tbl_tabcomp
                    WHERE 	`status` = '2' and status_cluster <> '1'
                )b on a.sub = b.sub
                where id = $id and a.created_date = $tgl_created
                GROUP BY a.sub
                ORDER BY a.urutan asc
                ) a
                ";
                // echo "<pre>";
                // print_r($sql);
                // echo "</pre>";

            $sql_subtotal_proses = $this->db->query($sql_subtotal);

            $sql_grandtotal = "
                insert into db_temp.t_temp_actual_vs_target
                select  '' as kode, '' as branch,'Z_GRAND TOTAL', '' as sub, '' as kodeprod, '' as namaprod,
                        '' as grup,'' as nama_grup, a.bulan, act_u,
                        t_u,act_u/t_u as acv_unit,act_v,
                        t_v, act_v/t_v as acv_value,'999', '' as status,
                        $id,$tgl_created
                from(
                        select  bulan,sum(a.actual_unit) as act_u, sum(a.target_unit) as t_u,sum(a.actual_value) as act_v,
                                sum(a.target_value) as t_v,a.urutan,a.status
                        from 	db_temp.t_temp_actual_vs_target a
                        where 	a.status = 1 and a.id = $id and created_date = $tgl_created
                )a
                ORDER BY a.urutan
            "; 
            // echo "<pre>";
            // print_r($sql);
            // echo "</pre>";

            $sql_grandtotal_proses = $this->db->query($sql_grandtotal);

        }else{
            return array();
        }    

        if ($proses) {
            $sql = "
                select *
                from db_temp.t_temp_actual_vs_target a
                where id = $id and a.created_date = $tgl_created
                order by urutan 
            ";
            $proses = $this->db->query($sql)->result();
            return $proses;
        }else{
            return array();
        }
        
                
    }

    public function sell_out_nasional($data){
        
        $user=$this->session->userdata('username');
        if ( function_exists( 'date_default_timezone_set' ) )
        date_default_timezone_set('Asia/Jakarta');        

        /* ---------DEFINISI VARIABEL----------------- */
        
            $tgl_created='"'.date('Y-m-d H:i:s').'"';        
            $id=$this->session->userdata('id');
            
            $periode_1 = $data['periode_1'];
            $tahun_periode_1 = substr($periode_1,0,4);
            $bulan_periode_1 = substr($periode_1,5,2);
            $supp = $data['supp'];
            $group = $data['group'];
            /*
            echo "<pre>";
            echo "year : ".$year."<br>";
            echo "bulan : ".$bulan."<br>";
            echo "supp : ".$supp."<br>";
            echo "group : ".$group."<br>";
            echo "</pre>";          
            */
            if ($group == '0') {
                $tampil_group = "";
            } else {
                $tampil_group = "and grup = '$group'";
            }
            
            $sql = "
                insert into db_temp.t_temp_sell_out_nasional
                select a.kodeprod, b.namaprod, sum(banyak) as unit, sum(tot1) as `value`, BULAN, $id, $tgl_created
                from
                (
                            SELECT kodeprod, banyak, tot1, bulan
                            from data$tahun_periode_1.fi a
                            where bulan in ($bulan_periode_1)
                            union ALL
                            SELECT kodeprod, banyak, tot1, bulan
                            from data$tahun_periode_1.ri a
                            where bulan in ($bulan_periode_1)
                )a INNER JOIN 
                (
                    SELECT kodeprod, namaprod
                    from mpm.tabprod
                    where supp = '$supp'
                    $tampil_group
                )b on a.kodeprod = b.kodeprod
                GROUP BY kodeprod, bulan
                union all
                select '', 'Grand Total', sum(banyak) as unit, sum(tot1) as `value`, BULAN, $id, $tgl_created
                from
                (
                            SELECT kodeprod, banyak, tot1, bulan
                            from data$tahun_periode_1.fi a
                            where bulan in ($bulan_periode_1)
                            union ALL
                            SELECT kodeprod, banyak, tot1, bulan
                            from data$tahun_periode_1.ri a
                            where bulan in ($bulan_periode_1)
                )a INNER JOIN 
                (
                    SELECT kodeprod, namaprod
                    from mpm.tabprod
                    where supp = '$supp'
                    $tampil_group
                )b on a.kodeprod = b.kodeprod
            ";
            
            // echo "<pre>";
            // print($sql);
            // echo "</pre>";
            
            $sql_insert = $this->db->query($sql);

            $sql = "select * from db_temp.t_temp_sell_out_nasional a where a.id = $id and created_date = $tgl_created ";
            $proses = $this->db->query($sql)->result();
            if ($proses) {
                return $proses;
            }else{
                return array();
        }
    }

}