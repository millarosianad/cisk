<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_omzet extends CI_Model 
{
	public function all(){
		//query semua record di table products
		$hasil = $this->db->get('user');
		if ($hasil->num_rows() > 0) {
			return $hasil->result();
		} else {
			return array();
		}
	}

	public function getmenuid($dataSegment){
		
		$menu_uri1 = $dataSegment['menu_uri1'];
		$menu_uri2 = $dataSegment['menu_uri2'];
		//echo "menu_uri1 : ".$menu_uri1;
		//echo "menu_uri2 : ".$menu_uri2;
		
		$this->db->where("target","$menu_uri1/$menu_uri2");
        $hasil = $this->db->get("mpm.menu");
        if ($hasil->num_rows() > 0) 
        {
            foreach($hasil->result() as $row)
            {
                $menuid = $row->id;
                //echo "<br>menu id model getmenuid : ".$menuid;
                return $menuid;
            }
        }else{
        	//echo "tidak ada";
        	//return array();
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

    public function omzet_all_dp($dataSegment){

	/* ---------DEFINISI VARIABEL----------------- */
		$id=$this->session->userdata('id');

		if ( function_exists( 'date_default_timezone_set' ) )
        date_default_timezone_set('Asia/Jakarta');        
        $tgl_created='"'.date('Y-m-d H:i:s').'"';

		$year = $dataSegment['tahun'];
		$supp = $dataSegment['supp'];
		$uri1 = $dataSegment['menu_uri1'];
		$uri2 = $dataSegment['menu_uri2'];
		$uri3 = $dataSegment['menu_uri3'];
		$menuid = $dataSegment['menuid'];
		$dp = $dataSegment['query_dp'];
        //echo "dp : ".$dp;
        if ($dp == NULL || $dp == '') {
        	$daftar_dp = '';
        } else {
        	//$daftar_dp = 'and nocab in ('.$dp.')';
        	$daftar_dp = $dp;
        	//echo " <br>daftar_dp : ".$daftar_dp;
        }
		/*
		echo "<pre>";
		echo "userid yg mengakses : ".$id."<br>";
		echo "tahun yg dipilih : ".$year."<br>";
		echo "supplier yg dipilih : ".$supp."<br>";
		echo "uri1 di model : ".$uri1."<br>";
		echo "uri2 di model : ".$uri2."<br>";
		echo "uri3 di model : ".$uri3."<br>";
		echo "menuid di model : ".$menuid."<br>";
		echo "</pre>";
		*/
        /* cek jumlah row(count) di data2017.fi */
        	$this->db->select('count(*) as rows');
            $query = $this->db->get("data$year.fi");
            foreach($query->result() as $r)
            {
               $total = $r->rows;
            }
            /*            
            echo "<pre>";
            echo "total_row_fi : ".$total;
            echo "</pre>";    		
    		*/
    	/* end cek jumlah row(count) di data2017.fi */ 

            /*cek apakah sudah ada user yang akses menu omzet dan supp sebelumnya di tabel temporary user */
            $sql="
            	select 	1 
            	from 	mpm.tbl_temporary_user 
            	where   menuid = $menuid and
            			supp = '$supp' and
            			tahun = '$year' and 
            			nilai = $total
            			";
            /*
            echo "<pre>";
            print_r($sql);
            echo "</pre>";
	        */
	        $query = $this->db->query($sql);
	        if($query->num_rows()>0) //kalau sudah ada
	        {
	        	/*
				echo "<pre>";
				echo "data belum berubah, langsung menjalankan tampil mpm.omzet_new";
				echo "</pre>";
				*/
				if ($dp == NULL || $dp == '') {
		        	
					/* PROSES TAMPIL KE WEBSITE */
					$this->db->order_by('urutan','asc');
					$this->db->where("kategori_supplier = ".'"'.$supp.'"');
					$this->db->where("tahun = ".'"'.$year.'"');
					
					//$this->db->where("id = ".'"'.$id.'"');
					$hasil = $this->db->get('omzet_new');		
					if ($hasil->num_rows() > 0) 
					{
						return $hasil->result();
					} else {
						return array();
					}
					/* END PROSES TAMPIL KE WEBSITE */

		        } else {
		        	
		        	/* PROSES TAMPIL KE WEBSITE */
					$this->db->order_by('urutan','asc');
					//$this->db->where("naper in (".'"'.$daftar_dp.'"'.")");
					$this->db->where("tahun = ".'"'.$year.'"');
					$this->db->where("naper in ($daftar_dp)");
					$this->db->where("kategori_supplier = ".'"'.$supp.'"');
					//$this->db->where("id = ".'"'.$id.'"');
					$hasil = $this->db->get('omzet_new');		
					if ($hasil->num_rows() > 0) 
					{
						return $hasil->result();
					} else {
						return array();
					}
					/* END PROSES TAMPIL KE WEBSITE */
		        }

				
	        }else{
	        	/*
	        	echo "data tidak ada / belum ada yang mengakses menu omzet dengan tahun dan supplier yang sama sebelumnya";
	        	*/
	        	$sql="
            	insert into mpm.tbl_temporary_user (userid,menuid,nilai,datetime,supp,tahun) 
            	values ($id,$menuid,$total,$tgl_created,'$supp','$year')
            	";
	        	$query = $this->db->query($sql);
	        	/*
	        	echo "<pre>";
	        	print_r($sql);
	        	echo "</pre>";
				*/
	        	/* ===== defenisikan kodeproduk berdasarkan kategori supplier yang dipilih user ==== */

	        	if($supp=='000') //jika pilih supplier :  4 besar (delto,ultra,marguna,jaya agung)
		        {
		            $wheresupp='
		                and (kodeprod like "60%" or kodeprod like "01%" or 
		                    kodeprod like "50%" or kodeprod like "70%" or 
		                    kodeprod like "06%" or kodeprod like "02%" or
		                    kodeprod like "04%") and (nocab != "r1")
		            ';
		        }
        		else if($supp=='001') //jika pilih supplier : deltomed
        		{ 	
					$wheresupp='and (kodeprod like "60%" or kodeprod like "01%" or kodeprod like "50%" or kodeprod like "70%") and nocab != "r1"';
				}            
	            else if($supp=='005') //jika pilih supplier : ultrasakti
	            {
	                $wheresupp='and kodeprod like "06%" ';
	            }
            	else if($supp=='002') //jika pilih supplier : marguna
	            {
	                $wheresupp='and kodeprod like "02%" ';
	            }
	            else if($supp=='009') //jika pilih supplier : unilever
	            {
	                $wheresupp='and supp = 009';
	            }
	            else if($supp=='XXX') //jika pilih supplier : all
	            {                    
	                $wheresupp='';
	            }
	            else //jika pilih supplier : selain yang disebutkan di atas
	            {
	                $wheresupp="and kodeprod like '".substr($supp,-2)."%'";
	            }	
	        	/* ========================================== */
	        	
	        	/* ===== Proses pendefinisian bulan, pembagi untuk menentukan total dan rata2 === */
				
				$bulan = date("m");
				$tahunsekarang = date("Y");
				/*
				echo "<pre>";
				echo "bulan : ".$bulan."<br>";
				echo "tahun : ".$tahunsekarang."<br>";
				echo "year : ".$year;
				echo "</pre>";
				*/
				if ($year == $tahunsekarang) //jika memilih tahun berjalan
				{				
					if ($bulan == '01')
					{
						$totalx = 'b2+b3+b4+b5+b6+b7+b8+b9+b10+b11+b12';
						$pembagi = 'xb2+xb3+xb4+xb5+xb6+xb7+xb8+xb9+xb10+xb11+xb12';
					}elseif ($bulan == '02')
					{
						$totalx = 'b1+b3+b4+b5+b6+b7+b8+b9+b10+b11+b12';
						$pembagi = 'xb1+xb3+xb4+xb5+xb6+xb7+xb8+xb9+xb10+xb11+xb12';
					}elseif ($bulan == '03')
					{
						$totalx = 'b1+b2+b4+b5+b6+b7+b8+b9+b10+b11+b12';
						$pembagi = 'xb1+xb2+xb4+xb5+xb6+xb7+xb8+xb9+xb10+xb11+xb12';
					}elseif ($bulan == '04')
					{
						$totalx = 'b1+b2+b3+b5+b6+b7+b8+b9+b10+b11+b12';
						$pembagi = 'xb1+xb2+xb3+xb5+xb6+xb7+xb8+xb9+xb10+xb11+xb12';
					}elseif ($bulan == '05')
					{
						$totalx = 'b1+b2+b3+b4+b6+b7+b8+b9+b10+b11+b12';
						$pembagi = 'xb1+xb2+xb3+xb4+xb6+xb7+xb8+xb9+xb10+xb11+xb12';
					}elseif ($bulan == '06')
					{
						$totalx = 'b1+b2+b3+b4+b5+b7+b8+b9+b10+b11+b12';
						$pembagi = 'xb1+xb2+xb3+xb4+xb5+xb7+xb8+xb9+xb10+xb11+xb12';
					}elseif ($bulan == '07')
					{
						$totalx = 'b1+b2+b3+b4+b5+b6+b8+b9+b10+b11+b12';
						$pembagi = 'xb1+xb2+xb3+xb4+xb5+xb6+xb8+xb9+xb10+xb11+xb12';
					}elseif ($bulan == '08')
					{
						$totalx = 'b1+b2+b3+b4+b5+b6+b7+b9+b10+b11+b12';
						$pembagi = 'xb1+xb2+xb3+xb4+xb5+xb6+xb7+xb9+xb10+xb11+xb12';
					}elseif ($bulan == '09')
					{
						$totalx = 'b1+b2+b3+b4+b5+b6+b7+b8+b10+b11+b12';
						$pembagi = 'xb1+xb2+xb3+xb4+xb5+xb6+xb7+xb8+xb10+xb11+xb12';
					}elseif ($bulan == '10')
					{
						$totalx = 'b1+b2+b3+b4+b5+b6+b7+b8+b9+b11+b12';
						$pembagi = 'xb1+xb2+xb3+xb4+xb5+xb6+xb7+xb8+xb9+xb11+xb12';
					}elseif ($bulan == '11')
					{
						$totalx = 'b1+b2+b3+b4+b5+b6+b7+b8+b9+b10+b12';
						$pembagi = 'xb1+xb2+xb3+xb4+xb5+xb6+xb7+xb8+xb9+xb10+xb12';
					}elseif ($bulan == '12')
					{
						$totalx = 'b1+b2+b3+b4+b5+b6+b7+b8+b9+b10+b11';
						$pembagi = 'xb1+xb2+xb3+xb4+xb5+xb6+xb7+xb8+xb9+xb10+xb11';
					}else
					{
						$totalx = 'b1+b2+b3+b4+b5+b6+b7+b8+b9+b10+b11+b12';
						$pembagi = 'xb1+xb2+xb3+xb4+xb5+xb6+xb7+xb8+xb9+xb10+xb11+xb12';
					}

				}else{
					$pembagi = 'xb1+xb2+xb3+xb4+xb5+xb6+xb7+xb8+xb9+xb10+xb11+xb12';
					$totalx = 'b1+b2+b3+b4+b5+b6+b7+b8+b9+b10+b11+b12';
				}
				/* ========================================== */

				/* =============== PROSES DELETE MPM.OMZET_NEW ===================== */
				$query = "	
						delete from mpm.omzet_new 
						where kategori_supplier = ".'"'.$supp.'"'." and tahun = $year
							";
				$sql = $this->db->query($query);
				/*
				echo "<pre>";
				print_r($query);
				echo "</pre>";
				*/
				/* ================================================================= */

				/* ========== PROSES INSERT KE TABEL OMZET_NEW =================== */
			
$query = "	        		
select 	a.nocab, sub, kode_comp, nama_comp, 
		t1, b1, t2, b2, t3, b3, t4, b4, t5, b5,	
		t6, b6, t7, b7, t8, b8, t9, b9, t10, b10, 
		t11, b11, t12, b12, ".$id.", total, rata,
		".'"'.$supp.'"'.",".$tgl_created.",".$year.", urutan, maxupload
FROM
(
	SELECT  a.nocab, a.kode_comp, b.sub, b.nama_comp,
			t1,b1,t2,b2,t3,b3,t4,b4,t5,b5,t6,b6,t7,b7,t8,b8,t9,b9,t10,b10,t11,b11,t12,b12, 
			".$id.", format(total,0) total,	format(totalx/jmlbulan,0) rata,
			".'"'.$supp.'"'.",".$tgl_created.",".$year.", urutan
	FROM
	(
		select 	nocab,kode_comp,kode,
				b1,b2,b3,b4,b5,b6,b7,b8,b9,b10,b11,b12,
				".$id.",total,totalx,
				format(sum($pembagi),0, 1) jmlbulan, 
				t1,t2,t3,t4,t5,t6,t7,t8,t9,t10,t11,t12
		FROM
		(
			select  nocab,kode_comp, 
					b1,b2,b3,b4,b5,b6,b7,b8,b9,b10,b11,b12,".$id.",
					b1+b2+b3+b4+b5+b6+b7+b8+b9+b10+b11+b12 as total,
					$totalx as totalx,                                
					IF(b1 = 0, 0, 1) xb1,IF(b2 = 0, 0, 1) xb2,IF(b3 = 0, 0, 1) xb3,
					IF(b4 = 0, 0, 1) xb4,IF(b5 = 0, 0, 1) xb5,IF(b6 = 0, 0, 1) xb6,
					IF(b7 = 0, 0, 1) xb7,IF(b8 = 0, 0, 1) xb8,IF(b9 = 0, 0, 1) xb9,
					IF(b10 = 0, 0, 1) xb10, IF(b11 = 0, 0, 1) xb11, IF(b12 = 0, 0, 1) xb12,
					sum(t1) as t1,sum(t2) as t2,sum(t3) as t3,sum(t4) as t4,
					sum(t5) as t5, sum(t6) as t6,sum(t7) as t7,sum(t8) as t8,
					sum(t9) as t9, sum(t10) as t10, sum(t11) as t11, sum(t12) as t12,kode
			from
			(
				select	`nocab`,kode_comp,
						sum(if(`blndok` = 1, `unit`, 0)) as `b1`,
						sum(if(`blndok` = 2, `unit`, 0)) as `b2`,
						sum(if(`blndok` = 3, `unit`, 0)) as `b3`,
						sum(if(`blndok` = 4, `unit`, 0)) as `b4`,
						sum(if(`blndok` = 5, `unit`, 0)) as `b5`,
						sum(if(`blndok` = 6, `unit`, 0)) as `b6`,
						sum(if(`blndok` = 7, `unit`, 0)) as `b7`,
						sum(if(`blndok` = 8, `unit`, 0)) as `b8`,
						sum(if(`blndok` = 9, `unit`, 0)) as `b9`,
						sum(if(`blndok` = 10, `unit`, 0)) as `b10`,
						sum(if(`blndok` = 11, `unit`, 0)) as `b11`,
						sum(if(`blndok` = 12, `unit`, 0)) as `b12`,
						sum(if(blndok = 1, trans, 0)) as t1,
						sum(if(blndok = 2, trans, 0)) as t2,
						sum(if(blndok = 3, trans, 0)) as t3,
						sum(if(blndok = 4, trans, 0)) as t4,
						sum(if(blndok = 5, trans, 0)) as t5,
						sum(if(blndok = 6, trans, 0)) as t6,
						sum(if(blndok = 7, trans, 0)) as t7,
						sum(if(blndok = 8, trans, 0)) as t8,
						sum(if(blndok = 9, trans, 0)) as t9,
						sum(if(blndok = 10, trans, 0)) as t10,
						sum(if(blndok = 11, trans, 0)) as t11,
						sum(if(blndok = 12, trans, 0)) as t12,kode
				from
				(
					 select `nocab`,kode_comp, `blndok`, sum(`tot1`) as `unit`, count(distinct(concat(kode_comp,kode_lang))) as trans, concat(kode_comp, nocab) as kode
					 from `data".$year."`.`fi`
					 where `nodokjdi` <> 'XXXXXX' 
							".$wheresupp."									
					 group by kode,`blndok`

					 union all

					 select `nocab`,kode_comp, `blndok`, sum(`tot1`) as `unit`,0 as trans, concat(kode_comp, nocab) as kode
					 from `data".$year."`.`ri`
					 where `nodokjdi` <> 'XXXXXX' 
							".$wheresupp."									
					 group by kode,`blndok`
				) `a`
				group by kode
			)`a` GROUP BY kode
		)a GROUP BY kode
	)a LEFT JOIN
	(
		select concat(kode_comp, nocab) as kode,naper,kode_comp, nama_comp, sub,urutan,`status`,status_cluster
		from mpm.tbl_tabcomp_new
		where `status` = 1
		GROUP BY concat(kode_comp, nocab)
	)b on a.kode = b.kode

	union ALL

	select *
	from
	(
		select	NAPER, '', sub, nama, 
				t1,b1, t2,b2, t3,b3, t4,b4,	t5,b5,
				t6,b6, t7,b7, t8,b8, t9,b9, t10,b10, 
				t11,b11,t12,b12,
				".$id.", format(total,0) as total,
				format(rata,0) as rata,3,4,5, c.urutan
		from
		(
			select	NAPER, sub, NAMA_COMP, 
					SUM(b1) b1,	SUM(b2) b2,	SUM(b3) b3,	SUM(b4) b4,
					SUM(b5) b5,	SUM(b6) b6,	SUM(b7) b7,	SUM(b8) b8,
					SUM(b9) b9,	SUM(b10) b10, SUM(b11) b11,
					SUM(b12) b12, sum(t1) as t1, sum(t2) as t2,				
					sum(t3) as t3,sum(t4) as t4,sum(t5) as t5,
					sum(t6) as t6,sum(t7) as t7,sum(t8) as t8,
					sum(t9) as t9,sum(t10) as t10,sum(t11) as t11,
					sum(t12) as t12,sum(total) as total,
					sum(rata) as rata
			from
			(
				select  naper, sub, b.NAMA_COMP,
						t1,b1, t2,b2, t3,b3,
						t4,b4, t5,b5, t6,b6,
						t7,b7, t8,b8, t9,b9,
						t10,b10, t11,b11, t12,b12, 
						".$id.", total,	(totalx/jmlbulan) as rata,
						".'"'.$supp.'"'.",".$tgl_created.",".$year."
				from
				(
					select  nocab, b1, b2, b3, b4, b5, b6, b7, b8, b9, 
							b10, b11, b12, ".$id.", total, totalx,
							format(sum($pembagi),0, 1) jmlbulan,
							t1,t2,t3,t4,t5,t6,t7,t8,t9,t10,t11,t12
					from
					(
						select  nocab, b1, b2, b3, b4, b5, 
								b6, b7, b8, b9, b10, b11, b12,
								".$id.",
								b1+b2+b3+b4+b5+b6+b7+b8+b9+b10+b11+b12 as total, 
								$totalx as totalx,
								IF(b1 = 0, 0, 1) xb1,IF(b2 = 0, 0, 1) xb2,
								IF(b3 = 0, 0, 1) xb3, IF(b4 = 0, 0, 1) xb4,
								IF(b5 = 0, 0, 1) xb5, IF(b6 = 0, 0, 1) xb6,
								IF(b7 = 0, 0, 1) xb7, IF(b8 = 0, 0, 1) xb8,
								IF(b9 = 0, 0, 1) xb9, IF(b10 = 0, 0, 1) xb10, 
								IF(b11 = 0, 0, 1) xb11, IF(b12 = 0, 0, 1) xb12,
								t1,t2,t3,t4,t5,t6,t7,t8,t9,t10,t11,t12
						from
						(
							select 	`nocab`, 
									sum(if(`blndok` = 1, `unit`, 0)) as `b1`,
									sum(if(`blndok` = 2, `unit`, 0)) as `b2`,
									sum(if(`blndok` = 3, `unit`, 0)) as `b3`,
									sum(if(`blndok` = 4, `unit`, 0)) as `b4`,
									sum(if(`blndok` = 5, `unit`, 0)) as `b5`,
									sum(if(`blndok` = 6, `unit`, 0)) as `b6`,
									sum(if(`blndok` = 7, `unit`, 0)) as `b7`,
									sum(if(`blndok` = 8, `unit`, 0)) as `b8`,
									sum(if(`blndok` = 9, `unit`, 0)) as `b9`,
									sum(if(`blndok` = 10, `unit`, 0)) as `b10`,
									sum(if(`blndok` = 11, `unit`, 0)) as `b11`,
									sum(if(`blndok` = 12, `unit`, 0)) as `b12`,
									sum(if(blndok = 1, trans, 0)) as t1,
									sum(if(blndok = 2, trans, 0)) as t2,
									sum(if(blndok = 3, trans, 0)) as t3,
									sum(if(blndok = 4, trans, 0)) as t4,
									sum(if(blndok = 5, trans, 0)) as t5,
									sum(if(blndok = 6, trans, 0)) as t6,
									sum(if(blndok = 7, trans, 0)) as t7,
									sum(if(blndok = 8, trans, 0)) as t8,
									sum(if(blndok = 9, trans, 0)) as t9,
									sum(if(blndok = 10, trans, 0)) as t10,
									sum(if(blndok = 11, trans, 0)) as t11,
									sum(if(blndok = 12, trans, 0)) as t12
							from
							(
								select 	`nocab`, `blndok`, 
										sum(`tot1`) as `unit`, 
										count(distinct(concat(kode_comp,kode_lang))) as trans
								from 	`data".$year."`.`fi`
								where 	`nodokjdi` <> 'XXXXXX' 					".$wheresupp."					
								group by `nocab`,`blndok`
								union all
 							 	select 	`nocab`, `blndok`, 
 							 			sum(`tot1`) as `unit`,0 as trans
								from 	`data".$year."`.`ri`
								where 	`nodokjdi` <> 'XXXXXX' 
										".$wheresupp."						
								group by `nocab`,`blndok`
							) `a`
							group by `nocab`
						)`a` GROUP BY nocab
					)a GROUP BY nocab
				)a
				inner join `mpm`.`tabcomp` `b` USING (`nocab`)
				group by `naper`
				order by sub, naper
			)a 
			GROUP BY sub
		)a inner JOIN 
		(
			select 	naper naper2,`status`, urutan, nama_comp nama
			from 	mpm.tbl_tabcomp_new
			WHERE 	`status` = '2' and status_cluster <> '1'
		)c on a.sub = c.naper2		
		GROUP BY sub
		ORDER BY c.urutan asc
	)b

	UNION ALL

	select	'Z', '', '','GRAND TOTAL', sum(if(blndok = 1, trans, 0)) as t1,
			sum(if(`blndok` = 1, `unit`, 0)) as `b1`,
			sum(if(blndok = 2, trans, 0)) as t2,
			sum(if(`blndok` = 2, `unit`, 0)) as `b2`,
			sum(if(blndok = 3, trans, 0)) as t3,
			sum(if(`blndok` = 3, `unit`, 0)) as `b3`,
			sum(if(blndok = 4, trans, 0)) as t4,
			sum(if(`blndok` = 4, `unit`, 0)) as `b4`,
			sum(if(blndok = 5, trans, 0)) as t5,
			sum(if(`blndok` = 5, `unit`, 0)) as `b5`,
			sum(if(blndok = 6, trans, 0)) as t6,
			sum(if(`blndok` = 6, `unit`, 0)) as `b6`,
			sum(if(blndok = 7, trans, 0)) as t7,
			sum(if(`blndok` = 7, `unit`, 0)) as `b7`,
			sum(if(blndok = 8, trans, 0)) as t8,
			sum(if(`blndok` = 8, `unit`, 0)) as `b8`,
			sum(if(blndok = 9, trans, 0)) as t9,
			sum(if(`blndok` = 9, `unit`, 0)) as `b9`,
			sum(if(blndok = 10, trans, 0)) as t10,
			sum(if(`blndok` = 10, `unit`, 0)) as `b10`,
			sum(if(blndok = 11, trans, 0)) as t11,
			sum(if(`blndok` = 11, `unit`, 0)) as `b11`,	
			sum(if(blndok = 12, trans, 0)) as t12,
			sum(if(`blndok` = 12, `unit`, 0)) as `b12`,
			".$id.",format(SUM(unit),0) as total,'-',0,0,0,999
	from
	(
		 select `nocab`, `blndok`, sum(`tot1`) as `unit`, 
		 		count(distinct(concat(kode_comp,kode_lang))) as trans
		 from 	`data".$year."`.`fi`
		 where 	`nodokjdi` <> 'XXXXXX' ".$wheresupp."
		 group by `nocab`,`blndok`

		 union all

		 select `nocab`, `blndok`, sum(`tot1`) as `unit`,0 as trans
		 from 	`data".$year."`.`ri`
		 where `nodokjdi` <> 'XXXXXX' ".$wheresupp."
		 group by `nocab`,`blndok`
	) `a`
	ORDER BY urutan
)a LEFT JOIN
	(
		select 	max(lastupload) as maxupload,
				substring(filename,3,2) naper  
		from 	mpm.upload 
		group by naper
	)b on a.nocab = b.naper
";
			        /*
			        echo "<pre>";
			        print_r($query);
			        echo "</pre>";
					*/
			        $gabung = "insert into mpm.omzet_new ".$query;			        
			        $sql = $this->db->query($gabung);

					/* ============================================================== */
					
					if ($dp == NULL || $dp == '') {
		        	
						/* PROSES TAMPIL KE WEBSITE */
						$this->db->order_by('urutan','asc');
						$this->db->where("kategori_supplier = ".'"'.$supp.'"');
						$this->db->where("tahun = ".'"'.$year.'"');
						$hasil = $this->db->get('omzet_new');		
						if ($hasil->num_rows() > 0) 
						{
							return $hasil->result();
						} else {
							return array();
						}
						/* END PROSES TAMPIL KE WEBSITE */

		        	} else {
			        	
			        	/* PROSES TAMPIL KE WEBSITE */
						$this->db->order_by('urutan','asc');
						$this->db->where("tahun = ".'"'.$year.'"');
						$this->db->where("naper in ($daftar_dp)");
						$this->db->where("kategori_supplier = ".'"'.$supp.'"');
						//$this->db->where("id = ".'"'.$id.'"');
						$hasil = $this->db->get('omzet_new');		
						if ($hasil->num_rows() > 0) 
						{
							return $hasil->result();
						} else {
							return array();
						}
						/* END PROSES TAMPIL KE WEBSITE */
		        	}
			
        }

	        
	    
	}

	public function omzet_all_dp_barat($dataSegment){

	/* ---------DEFINISI VARIABEL----------------- */
		$id=$this->session->userdata('id');

		if ( function_exists( 'date_default_timezone_set' ) )
        date_default_timezone_set('Asia/Jakarta');        
        $tgl_created='"'.date('Y-m-d H:i:s').'"';

		$year = $dataSegment['tahun'];
		$supp = $dataSegment['supp'];
		$uri1 = $dataSegment['menu_uri1'];
		$uri2 = $dataSegment['menu_uri2'];
		$uri3 = $dataSegment['menu_uri3'];
		$menuid = $dataSegment['menuid'];
		$dp = $dataSegment['query_dp'];
        //echo "dp : ".$dp;
        if ($dp == NULL || $dp == '') {
        	$daftar_dp = '';
        } else {
        	$daftar_dp = $dp;
        }
        /*
        echo "<pre>";
		echo "userid yg mengakses : ".$id."<br>";
		echo "tahun yg dipilih : ".$year."<br>";
		echo "supplier yg dipilih : ".$supp."<br>";
		echo "uri1 di model : ".$uri1."<br>";
		echo "uri2 di model : ".$uri2."<br>";
		echo "uri3 di model : ".$uri3."<br>";
		echo "menuid di model : ".$menuid."<br>";
		echo "</pre>";
		*/
        /* cek jumlah row(count) di data2017.fi */
        	$this->db->select('count(*) as rows');
            $query = $this->db->get("data$year.fi");
            foreach($query->result() as $r)
            {
               $total = $r->rows;
            }
    	/* end cek jumlah row(count) di data2017.fi */

    	/*cek apakah sudah ada user yang akses menu omzet dan supp sebelumnya di tabel temporary user */
            $sql="
            	select 	1 
            	from 	mpm.tbl_temporary_user 
            	where   menuid = $menuid and
            			supp = '$supp' and
            			tahun = '$year' and 
            			nilai = $total
            			";
            /*
            echo "<pre>";
			print_r($sql);
			echo "</pre>";
			*/
			/* pontianak masuk ke wilayah timur per 2017*/
			if ($year == '2010' || $year == '2011' || $year == '2012' || $year == '2013' || $year == '2014' || $year == '2015' || $year == '2016') 
			{
				//$wilayah = "wilayah = 2";
				//echo "wilayah : ".$wilayah."<br>";
				$wilayah = 'wilayah';
			} else {
				//$wilayah = "wilayah_2017 = 2";
				//echo "wilayah : ".$wilayah."<br>";
				$wilayah = 'wilayah_2017';
			}
		/* end pontianak masuk ke wilayah timur per 2017*/

            
            $query = $this->db->query($sql);
	        if($query->num_rows()>0) //kalau sudah ada
	        {	
	        	/*
				echo "<pre>";
				echo "data belum berubah, langsung menjalankan tampil mpm.omzet_new";
				echo "</pre>";
				*/

	        	if ($dp == NULL || $dp == '') {
		        	
					$sql = "
						select *
						from mpm.omzet_new a INNER JOIN
						(
							select naper, nocab, kode_comp, nama_comp, sub, urutan, $wilayah
							from mpm.tbl_tabcomp_new
							where $wilayah = '1'
							GROUP BY naper
							ORDER BY urutan asc
						)b on a.naper = b.naper
						where a.kategori_supplier = ".'"'.$supp.'"'." and tahun = $year
						ORDER BY a.urutan asc
					";

				

		        } else {
		        	
		        	$sql = "
						select *
						from mpm.omzet_new a LEFT JOIN
						(
							select naper, nocab, kode_comp, nama_comp, sub, urutan, $wilayah 
							from mpm.tbl_tabcomp_new
							where $wilayah = '1'
							GROUP BY naper
							ORDER BY urutan asc
						)b on a.naper = b.naper
						where a.naper in ($dp) and a.kategori_supplier = ".'"'.$supp.'"'." and tahun = $year
						ORDER BY a.urutan asc
					";

					
					
		        }
		        /*
		        	echo "<pre>";
					print_r($sql);
					echo "</pre>";
				*/
		        $query = $this->db->query($sql);

				if ($query->num_rows() > 0) 
				{
					return $query->result();
				} else {
					return array();
				}

		    }else{

		    	$sql="
            	insert into mpm.tbl_temporary_user (userid,menuid,nilai,datetime,supp,tahun) 
            	values ($id,$menuid,$total,$tgl_created,'$supp','$year')
            	";
	        	$query = $this->db->query($sql);

				/* ===== defenisikan kodeproduk berdasarkan kategori supplier yang dipilih user ==== */
	        	if($supp=='000') //jika pilih supplier :  4 besar (delto,ultra,marguna,jaya agung)
		        {
		            $wheresupp='
		                and (kodeprod like "60%" or kodeprod like "01%" or 
		                    kodeprod like "50%" or kodeprod like "70%" or 
		                    kodeprod like "06%" or kodeprod like "02%" or
		                    kodeprod like "04%") and (nocab != "r1")
		            ';
		        }
        		else if($supp=='001') //jika pilih supplier : deltomed
        		{ 	
					$wheresupp='and (kodeprod like "60%" or kodeprod like "01%" or kodeprod like "50%" or kodeprod like "70%") and nocab != "r1"';
				}            
	            else if($supp=='005') //jika pilih supplier : ultrasakti
	            {
	                $wheresupp='and kodeprod like "06%" ';
	            }
            	else if($supp=='002') //jika pilih supplier : marguna
	            {
	                $wheresupp='and kodeprod like "02%" ';
	            }
	            else if($supp=='009') //jika pilih supplier : unilever
	            {
	                $wheresupp='and supp = 009';
	            }
	            else if($supp=='XXX') //jika pilih supplier : all
	            {                    
	                $wheresupp='';
	            }
	            else //jika pilih supplier : selain yang disebutkan di atas
	            {
	                $wheresupp="and kodeprod like '".substr($supp,-2)."%'";
	            }	
	        	/* ========================================== */

		/* cari bulan berjalan */
			$bulan = date("m");
			$tahunsekarang = date("Y");

			if ($year == $tahunsekarang) {
				
				if ($bulan == '01')
				{
					$totalx = 'b2+b3+b4+b5+b6+b7+b8+b9+b10+b11+b12';
					$pembagi = 'xb2+xb3+xb4+xb5+xb6+xb7+xb8+xb9+xb10+xb11+xb12';
				}elseif ($bulan == '02')
				{
					$totalx = 'b1+b3+b4+b5+b6+b7+b8+b9+b10+b11+b12';
					$pembagi = 'xb1+xb3+xb4+xb5+xb6+xb7+xb8+xb9+xb10+xb11+xb12';
				}elseif ($bulan == '03')
				{
					$totalx = 'b1+b2+b4+b5+b6+b7+b8+b9+b10+b11+b12';
					$pembagi = 'xb1+xb2+xb4+xb5+xb6+xb7+xb8+xb9+xb10+xb11+xb12';
				}elseif ($bulan == '04')
				{
					$totalx = 'b1+b2+b3+b5+b6+b7+b8+b9+b10+b11+b12';
					$pembagi = 'xb1+xb2+xb3+xb5+xb6+xb7+xb8+xb9+xb10+xb11+xb12';
				}elseif ($bulan == '05')
				{
					$totalx = 'b1+b2+b3+b4+b6+b7+b8+b9+b10+b11+b12';
					$pembagi = 'xb1+xb2+xb3+xb4+xb6+xb7+xb8+xb9+xb10+xb11+xb12';
				}elseif ($bulan == '06')
				{
					$totalx = 'b1+b2+b3+b4+b5+b7+b8+b9+b10+b11+b12';
					$pembagi = 'xb1+xb2+xb3+xb4+xb5+xb7+xb8+xb9+xb10+xb11+xb12';
				}elseif ($bulan == '07')
				{
					$totalx = 'b1+b2+b3+b4+b5+b6+b8+b9+b10+b11+b12';
					$pembagi = 'xb1+xb2+xb3+xb4+xb5+xb6+xb8+xb9+xb10+xb11+xb12';
				}elseif ($bulan == '08')
				{
					$totalx = 'b1+b2+b3+b4+b5+b6+b7+b9+b10+b11+b12';
					$pembagi = 'xb1+xb2+xb3+xb4+xb5+xb6+xb7+xb9+xb10+xb11+xb12';
				}elseif ($bulan == '09')
				{
					$totalx = 'b1+b2+b3+b4+b5+b6+b7+b8+b10+b11+b12';
					$pembagi = 'xb1+xb2+xb3+xb4+xb5+xb6+xb7+xb8+xb10+xb11+xb12';
				}elseif ($bulan == '10')
				{
					$totalx = 'b1+b2+b3+b4+b5+b6+b7+b8+b9+b11+b12';
					$pembagi = 'xb1+xb2+xb3+xb4+xb5+xb6+xb7+xb8+xb9+xb11+xb12';
				}elseif ($bulan == '11')
				{
					$totalx = 'b1+b2+b3+b4+b5+b6+b7+b8+b9+b10+b12';
					$pembagi = 'xb1+xb2+xb3+xb4+xb5+xb6+xb7+xb8+xb9+xb10+xb12';
				}elseif ($bulan == '12')
				{
					$totalx = 'b1+b2+b3+b4+b5+b6+b7+b8+b9+b10+b11';
					$pembagi = 'xb1+xb2+xb3+xb4+xb5+xb6+xb7+xb8+xb9+xb10+xb11';
				}else
				{
					$totalx = 'b1+b2+b3+b4+b5+b6+b7+b8+b9+b10+b11+b12';
					$pembagi = 'xb1+xb2+xb3+xb4+xb5+xb6+xb7+xb8+xb9+xb10+xb11+xb12';
				}

			}else{
				$pembagi = 'xb1+xb2+xb3+xb4+xb5+xb6+xb7+xb8+xb9+xb10+xb11+xb12';
				$totalx = 'b1+b2+b3+b4+b5+b6+b7+b8+b9+b10+b11+b12';
			}
		/* end cari bulan saat ini */

		/* pontianak masuk ke wilayah timur per 2017*/
			if ($year == '2010' || $year == '2011' || $year == '2012' || $year == '2013' || $year == '2014' || $year == '2015' || $year == '2016') 
			{
				//$wilayah = "wilayah = 2";
				//echo "wilayah : ".$wilayah."<br>";
				$wilayah = 'wilayah';
			} else {
				//$wilayah = "wilayah_2017 = 2";
				//echo "wilayah : ".$wilayah."<br>";
				$wilayah = 'wilayah_2017';
			}
		/* end pontianak masuk ke wilayah timur per 2017*/

		/* =============== PROSES DELETE MPM.OMZET_NEW ===================== */
				$query = "	
						delete from mpm.omzet_new 
						where kategori_supplier = ".'"'.$supp.'"'." and tahun = '$year'
							";
				$sql = $this->db->query($query);

				/* ================================================================= */

		/* PROSES INSERT KE TABEL OMZET_NEW */
			
	        $query = "
select 	a.nocab, sub, kode_comp, nama_comp, 
		t1, b1, t2, b2, t3, b3, t4, b4, t5, b5,	
		t6, b6, t7, b7, t8, b8, t9, b9, t10, b10, 
		t11, b11, t12, b12, ".$id.", total, rata,
		".'"'.$supp.'"'.",".$tgl_created.",".$year.", urutan, maxupload
FROM
(
	SELECT  a.nocab, a.kode_comp, b.sub, b.nama_comp,
			t1,b1,t2,b2,t3,b3,t4,b4,t5,b5,t6,b6,t7,b7,t8,b8,t9,b9,t10,b10,t11,b11,t12,b12, 
			".$id.", format(total,0) total,	format(totalx/jmlbulan,0) rata,
			".'"'.$supp.'"'.",".$tgl_created.",".$year.", urutan
	FROM
	(
		select 	nocab,kode_comp,kode,
				b1,b2,b3,b4,b5,b6,b7,b8,b9,b10,b11,b12,
				".$id.",total,totalx,
				format(sum($pembagi),0, 1) jmlbulan, 
				t1,t2,t3,t4,t5,t6,t7,t8,t9,t10,t11,t12
		FROM
		(
			select  nocab,kode_comp, 
					b1,b2,b3,b4,b5,b6,b7,b8,b9,b10,b11,b12,".$id.",
					b1+b2+b3+b4+b5+b6+b7+b8+b9+b10+b11+b12 as total,
					$totalx as totalx,                                
					IF(b1 = 0, 0, 1) xb1,IF(b2 = 0, 0, 1) xb2,IF(b3 = 0, 0, 1) xb3,
					IF(b4 = 0, 0, 1) xb4,IF(b5 = 0, 0, 1) xb5,IF(b6 = 0, 0, 1) xb6,
					IF(b7 = 0, 0, 1) xb7,IF(b8 = 0, 0, 1) xb8,IF(b9 = 0, 0, 1) xb9,
					IF(b10 = 0, 0, 1) xb10, IF(b11 = 0, 0, 1) xb11, IF(b12 = 0, 0, 1) xb12,
					sum(t1) as t1,sum(t2) as t2,sum(t3) as t3,sum(t4) as t4,
					sum(t5) as t5, sum(t6) as t6,sum(t7) as t7,sum(t8) as t8,
					sum(t9) as t9, sum(t10) as t10, sum(t11) as t11, sum(t12) as t12,kode
			from
			(
				select	`nocab`,kode_comp,
						sum(if(`blndok` = 1, `unit`, 0)) as `b1`,
						sum(if(`blndok` = 2, `unit`, 0)) as `b2`,
						sum(if(`blndok` = 3, `unit`, 0)) as `b3`,
						sum(if(`blndok` = 4, `unit`, 0)) as `b4`,
						sum(if(`blndok` = 5, `unit`, 0)) as `b5`,
						sum(if(`blndok` = 6, `unit`, 0)) as `b6`,
						sum(if(`blndok` = 7, `unit`, 0)) as `b7`,
						sum(if(`blndok` = 8, `unit`, 0)) as `b8`,
						sum(if(`blndok` = 9, `unit`, 0)) as `b9`,
						sum(if(`blndok` = 10, `unit`, 0)) as `b10`,
						sum(if(`blndok` = 11, `unit`, 0)) as `b11`,
						sum(if(`blndok` = 12, `unit`, 0)) as `b12`,
						sum(if(blndok = 1, trans, 0)) as t1,
						sum(if(blndok = 2, trans, 0)) as t2,
						sum(if(blndok = 3, trans, 0)) as t3,
						sum(if(blndok = 4, trans, 0)) as t4,
						sum(if(blndok = 5, trans, 0)) as t5,
						sum(if(blndok = 6, trans, 0)) as t6,
						sum(if(blndok = 7, trans, 0)) as t7,
						sum(if(blndok = 8, trans, 0)) as t8,
						sum(if(blndok = 9, trans, 0)) as t9,
						sum(if(blndok = 10, trans, 0)) as t10,
						sum(if(blndok = 11, trans, 0)) as t11,
						sum(if(blndok = 12, trans, 0)) as t12,kode
				from
				(
					 select `nocab`,kode_comp, `blndok`, sum(`tot1`) as `unit`, count(distinct(concat(kode_comp,kode_lang))) as trans, concat(kode_comp, nocab) as kode
					 from `data".$year."`.`fi`
					 where `nodokjdi` <> 'XXXXXX' 
							".$wheresupp."									
					 group by kode,`blndok`

					 union all

					 select `nocab`,kode_comp, `blndok`, sum(`tot1`) as `unit`,0 as trans, concat(kode_comp, nocab) as kode
					 from `data".$year."`.`ri`
					 where `nodokjdi` <> 'XXXXXX' 
							".$wheresupp."									
					 group by kode,`blndok`
				) `a`
				group by kode
			)`a` GROUP BY kode
		)a GROUP BY kode
	)a LEFT JOIN
	(
		select concat(kode_comp, nocab) as kode,naper,kode_comp, nama_comp, sub,urutan,`status`,status_cluster
		from mpm.tbl_tabcomp_new
		where `status` = 1
		GROUP BY concat(kode_comp, nocab)
	)b on a.kode = b.kode

	union ALL

	select *
	from
	(
		select	NAPER, '', sub, nama, 
				t1,b1, t2,b2, t3,b3, t4,b4,	t5,b5,
				t6,b6, t7,b7, t8,b8, t9,b9, t10,b10, 
				t11,b11,t12,b12,
				".$id.", format(total,0) as total,
				format(rata,0) as rata,3,4,5, c.urutan
		from
		(
			select	NAPER, sub, NAMA_COMP, 
					SUM(b1) b1,	SUM(b2) b2,	SUM(b3) b3,	SUM(b4) b4,
					SUM(b5) b5,	SUM(b6) b6,	SUM(b7) b7,	SUM(b8) b8,
					SUM(b9) b9,	SUM(b10) b10, SUM(b11) b11,
					SUM(b12) b12, sum(t1) as t1, sum(t2) as t2,				
					sum(t3) as t3,sum(t4) as t4,sum(t5) as t5,
					sum(t6) as t6,sum(t7) as t7,sum(t8) as t8,
					sum(t9) as t9,sum(t10) as t10,sum(t11) as t11,
					sum(t12) as t12,sum(total) as total,
					sum(rata) as rata
			from
			(
				select  naper, sub, b.NAMA_COMP,
						t1,b1, t2,b2, t3,b3,
						t4,b4, t5,b5, t6,b6,
						t7,b7, t8,b8, t9,b9,
						t10,b10, t11,b11, t12,b12, 
						".$id.", total,	(totalx/jmlbulan) as rata,
						".'"'.$supp.'"'.",".$tgl_created.",".$year."
				from
				(
					select  nocab, b1, b2, b3, b4, b5, b6, b7, b8, b9, 
							b10, b11, b12, ".$id.", total, totalx,
							format(sum($pembagi),0, 1) jmlbulan,
							t1,t2,t3,t4,t5,t6,t7,t8,t9,t10,t11,t12
					from
					(
						select  nocab, b1, b2, b3, b4, b5, 
								b6, b7, b8, b9, b10, b11, b12,
								".$id.",
								b1+b2+b3+b4+b5+b6+b7+b8+b9+b10+b11+b12 as total, 
								$totalx as totalx,
								IF(b1 = 0, 0, 1) xb1,IF(b2 = 0, 0, 1) xb2,
								IF(b3 = 0, 0, 1) xb3, IF(b4 = 0, 0, 1) xb4,
								IF(b5 = 0, 0, 1) xb5, IF(b6 = 0, 0, 1) xb6,
								IF(b7 = 0, 0, 1) xb7, IF(b8 = 0, 0, 1) xb8,
								IF(b9 = 0, 0, 1) xb9, IF(b10 = 0, 0, 1) xb10, 
								IF(b11 = 0, 0, 1) xb11, IF(b12 = 0, 0, 1) xb12,
								t1,t2,t3,t4,t5,t6,t7,t8,t9,t10,t11,t12
						from
						(
							select 	`nocab`, 
									sum(if(`blndok` = 1, `unit`, 0)) as `b1`,
									sum(if(`blndok` = 2, `unit`, 0)) as `b2`,
									sum(if(`blndok` = 3, `unit`, 0)) as `b3`,
									sum(if(`blndok` = 4, `unit`, 0)) as `b4`,
									sum(if(`blndok` = 5, `unit`, 0)) as `b5`,
									sum(if(`blndok` = 6, `unit`, 0)) as `b6`,
									sum(if(`blndok` = 7, `unit`, 0)) as `b7`,
									sum(if(`blndok` = 8, `unit`, 0)) as `b8`,
									sum(if(`blndok` = 9, `unit`, 0)) as `b9`,
									sum(if(`blndok` = 10, `unit`, 0)) as `b10`,
									sum(if(`blndok` = 11, `unit`, 0)) as `b11`,
									sum(if(`blndok` = 12, `unit`, 0)) as `b12`,
									sum(if(blndok = 1, trans, 0)) as t1,
									sum(if(blndok = 2, trans, 0)) as t2,
									sum(if(blndok = 3, trans, 0)) as t3,
									sum(if(blndok = 4, trans, 0)) as t4,
									sum(if(blndok = 5, trans, 0)) as t5,
									sum(if(blndok = 6, trans, 0)) as t6,
									sum(if(blndok = 7, trans, 0)) as t7,
									sum(if(blndok = 8, trans, 0)) as t8,
									sum(if(blndok = 9, trans, 0)) as t9,
									sum(if(blndok = 10, trans, 0)) as t10,
									sum(if(blndok = 11, trans, 0)) as t11,
									sum(if(blndok = 12, trans, 0)) as t12
							from
							(
								select 	`nocab`, `blndok`, 
										sum(`tot1`) as `unit`, 
										count(distinct(concat(kode_comp,kode_lang))) as trans
								from 	`data".$year."`.`fi`
								where 	`nodokjdi` <> 'XXXXXX' 					".$wheresupp."					
								group by `nocab`,`blndok`
								union all
 							 	select 	`nocab`, `blndok`, 
 							 			sum(`tot1`) as `unit`,0 as trans
								from 	`data".$year."`.`ri`
								where 	`nodokjdi` <> 'XXXXXX' 
										".$wheresupp."						
								group by `nocab`,`blndok`
							) `a`
							group by `nocab`
						)`a` GROUP BY nocab
					)a GROUP BY nocab
				)a
				inner join `mpm`.`tabcomp` `b` USING (`nocab`)
				group by `naper`
				order by sub, naper
			)a 
			GROUP BY sub
		)a inner JOIN 
		(
			select 	naper naper2,`status`, urutan, nama_comp nama
			from 	mpm.tbl_tabcomp_new
			WHERE 	`status` = '2' and status_cluster <> '1'
		)c on a.sub = c.naper2		
		GROUP BY sub
		ORDER BY c.urutan asc
	)b

	UNION ALL

	select	'Z', '', '','GRAND TOTAL', sum(if(blndok = 1, trans, 0)) as t1,
			sum(if(`blndok` = 1, `unit`, 0)) as `b1`,
			sum(if(blndok = 2, trans, 0)) as t2,
			sum(if(`blndok` = 2, `unit`, 0)) as `b2`,
			sum(if(blndok = 3, trans, 0)) as t3,
			sum(if(`blndok` = 3, `unit`, 0)) as `b3`,
			sum(if(blndok = 4, trans, 0)) as t4,
			sum(if(`blndok` = 4, `unit`, 0)) as `b4`,
			sum(if(blndok = 5, trans, 0)) as t5,
			sum(if(`blndok` = 5, `unit`, 0)) as `b5`,
			sum(if(blndok = 6, trans, 0)) as t6,
			sum(if(`blndok` = 6, `unit`, 0)) as `b6`,
			sum(if(blndok = 7, trans, 0)) as t7,
			sum(if(`blndok` = 7, `unit`, 0)) as `b7`,
			sum(if(blndok = 8, trans, 0)) as t8,
			sum(if(`blndok` = 8, `unit`, 0)) as `b8`,
			sum(if(blndok = 9, trans, 0)) as t9,
			sum(if(`blndok` = 9, `unit`, 0)) as `b9`,
			sum(if(blndok = 10, trans, 0)) as t10,
			sum(if(`blndok` = 10, `unit`, 0)) as `b10`,
			sum(if(blndok = 11, trans, 0)) as t11,
			sum(if(`blndok` = 11, `unit`, 0)) as `b11`,	
			sum(if(blndok = 12, trans, 0)) as t12,
			sum(if(`blndok` = 12, `unit`, 0)) as `b12`,
			".$id.",format(SUM(unit),0) as total,'-',0,".$year.",0,999
	from
	(
		 select `nocab`, `blndok`, sum(`tot1`) as `unit`, 
		 		count(distinct(concat(kode_comp,kode_lang))) as trans
		 from 	`data".$year."`.`fi`
		 where 	`nodokjdi` <> 'XXXXXX' ".$wheresupp."
		 group by `nocab`,`blndok`

		 union all

		 select `nocab`, `blndok`, sum(`tot1`) as `unit`,0 as trans
		 from 	`data".$year."`.`ri`
		 where `nodokjdi` <> 'XXXXXX' ".$wheresupp."
		 group by `nocab`,`blndok`
	) `a`
	ORDER BY urutan
)a LEFT JOIN
	(
		select 	max(lastupload) as maxupload,
				substring(filename,3,2) naper  
		from 	mpm.upload 
		group by naper
	)b on a.nocab = b.naper
";
	        $gabung = "insert into mpm.omzet_new ".$query;	  		
	        $sql = $this->db->query($gabung);
	        /*
	        echo "<pre>";
	        print_r($sql);
	        echo "</pre>";
			*/
		/* ============================================= */

			if ($dp == NULL || $dp == '') {
		        	
					$sql = "
						select *
						from mpm.omzet_new a INNER JOIN
						(
							select naper, nocab, kode_comp, nama_comp, sub, urutan, $wilayah
							from mpm.tbl_tabcomp_new
							where $wilayah = '1'
							GROUP BY naper
							ORDER BY urutan asc
						)b on a.naper = b.naper
						where a.kategori_supplier = ".'"'.$supp.'"'." and a.tahun = $year
						ORDER BY a.urutan asc
					";
				

		        } else {
		        	
		        	$sql = "
						select *
						from mpm.omzet_new a LEFT JOIN
						(
							select naper, nocab, kode_comp, nama_comp, sub, urutan, $wilayah
							from mpm.tbl_tabcomp_new
							where $wilayah = '1'
							GROUP BY naper
							ORDER BY urutan asc
						)b on a.naper = b.naper
						where a.naper in ($dp) and a.kategori_supplier = ".'"'.$supp.'"'." and a.tahun = $year
						ORDER BY a.urutan asc
					";
					
					
		        }
		        /*
		        	echo "<pre>";
					print_r($sql);
					echo "</pre>";
				*/
		        $query = $this->db->query($sql);

				if ($query->num_rows() > 0) 
				{
					return $query->result();
				} else {
					return array();
				}

    	}

	}

	public function omzet_all_dp_timur($dataSegment){

	/* ---------DEFINISI VARIABEL----------------- */
		$id=$this->session->userdata('id');

		if ( function_exists( 'date_default_timezone_set' ) )
        date_default_timezone_set('Asia/Jakarta');        
        $tgl_created='"'.date('Y-m-d H:i:s').'"';

		$year = $dataSegment['tahun'];
		$supp = $dataSegment['supp'];
		$uri1 = $dataSegment['menu_uri1'];
		$uri2 = $dataSegment['menu_uri2'];
		$uri3 = $dataSegment['menu_uri3'];
		$menuid = $dataSegment['menuid'];
		$dp = $dataSegment['query_dp'];
        //echo "dp : ".$dp;
        if ($dp == NULL || $dp == '') {
        	$daftar_dp = '';
        } else {
        	$daftar_dp = $dp;
        }
        /*
        echo "<pre>";
		echo "userid yg mengakses : ".$id."<br>";
		echo "tahun yg dipilih : ".$year."<br>";
		echo "supplier yg dipilih : ".$supp."<br>";
		echo "uri1 di model : ".$uri1."<br>";
		echo "uri2 di model : ".$uri2."<br>";
		echo "uri3 di model : ".$uri3."<br>";
		echo "menuid di model : ".$menuid."<br>";
		echo "</pre>";
		*/
        /* cek jumlah row(count) di data2017.fi */
        	$this->db->select('count(*) as rows');
            $query = $this->db->get("data$year.fi");
            foreach($query->result() as $r)
            {
               $total = $r->rows;
            }
    	/* end cek jumlah row(count) di data2017.fi */

    	/*cek apakah sudah ada user yang akses menu omzet dan supp sebelumnya di tabel temporary user */
            $sql="
            	select 	1 
            	from 	mpm.tbl_temporary_user 
            	where   menuid = $menuid and
            			supp = '$supp' and
            			tahun = '$year' and 
            			nilai = $total
            			";
            /*
            echo "<pre>";
			print_r($sql);
			echo "</pre>";
			*/
			/* pontianak masuk ke wilayah timur per 2017*/
			if ($year == '2010' || $year == '2011' || $year == '2012' || $year == '2013' || $year == '2014' || $year == '2015' || $year == '2016') 
			{
				//$wilayah = "wilayah = 2";
				//echo "wilayah : ".$wilayah."<br>";
				$wilayah = 'wilayah';
			} else {
				//$wilayah = "wilayah_2017 = 2";
				//echo "wilayah : ".$wilayah."<br>";
				$wilayah = 'wilayah_2017';
			}
		/* end pontianak masuk ke wilayah timur per 2017*/

            
            $query = $this->db->query($sql);
	        if($query->num_rows()>0) //kalau sudah ada
	        {	
	        	/*
				echo "<pre>";
				echo "data belum berubah, langsung menjalankan tampil mpm.omzet_new";
				echo "</pre>";
				*/

	        	if ($dp == NULL || $dp == '') {
		        	
					$sql = "
						select *
						from mpm.omzet_new a INNER JOIN
						(
							select naper, nocab, kode_comp, nama_comp, sub, urutan, $wilayah
							from mpm.tbl_tabcomp_new
							where $wilayah = '2'
							GROUP BY naper
							ORDER BY urutan asc
						)b on a.naper = b.naper
						where a.kategori_supplier = ".'"'.$supp.'"'." and tahun = $year
						ORDER BY a.urutan asc
					";

				

		        } else {
		        	
		        	$sql = "
						select *
						from mpm.omzet_new a LEFT JOIN
						(
							select naper, nocab, kode_comp, nama_comp, sub, urutan, $wilayah 
							from mpm.tbl_tabcomp_new
							where $wilayah = '2'
							GROUP BY naper
							ORDER BY urutan asc
						)b on a.naper = b.naper
						where a.naper in ($dp) and a.kategori_supplier = ".'"'.$supp.'"'." and tahun = $year
						ORDER BY a.urutan asc
					";

					
					
		        }
		        /*
		        	echo "<pre>";
					print_r($sql);
					echo "</pre>";
				*/
		        $query = $this->db->query($sql);

				if ($query->num_rows() > 0) 
				{
					return $query->result();
				} else {
					return array();
				}

		    }else{

		    	$sql="
            	insert into mpm.tbl_temporary_user (userid,menuid,nilai,datetime,supp,tahun) 
            	values ($id,$menuid,$total,$tgl_created,'$supp','$year')
            	";
	        	$query = $this->db->query($sql);

				/* ===== defenisikan kodeproduk berdasarkan kategori supplier yang dipilih user ==== */
	        	if($supp=='000') //jika pilih supplier :  4 besar (delto,ultra,marguna,jaya agung)
		        {
		            $wheresupp='
		                and (kodeprod like "60%" or kodeprod like "01%" or 
		                    kodeprod like "50%" or kodeprod like "70%" or 
		                    kodeprod like "06%" or kodeprod like "02%" or
		                    kodeprod like "04%") and (nocab != "r1")
		            ';
		        }
        		else if($supp=='001') //jika pilih supplier : deltomed
        		{ 	
					$wheresupp='and (kodeprod like "60%" or kodeprod like "01%" or kodeprod like "50%" or kodeprod like "70%") and nocab != "r1"';
				}            
	            else if($supp=='005') //jika pilih supplier : ultrasakti
	            {
	                $wheresupp='and kodeprod like "06%" ';
	            }
            	else if($supp=='002') //jika pilih supplier : marguna
	            {
	                $wheresupp='and kodeprod like "02%" ';
	            }
	            else if($supp=='009') //jika pilih supplier : unilever
	            {
	                $wheresupp='and supp = 009';
	            }
	            else if($supp=='XXX') //jika pilih supplier : all
	            {                    
	                $wheresupp='';
	            }
	            else //jika pilih supplier : selain yang disebutkan di atas
	            {
	                $wheresupp="and kodeprod like '".substr($supp,-2)."%'";
	            }	
	        	/* ========================================== */

		/* cari bulan berjalan */
			$bulan = date("m");
			$tahunsekarang = date("Y");

			if ($year == $tahunsekarang) {
				
				if ($bulan == '01')
				{
					$totalx = 'b2+b3+b4+b5+b6+b7+b8+b9+b10+b11+b12';
					$pembagi = 'xb2+xb3+xb4+xb5+xb6+xb7+xb8+xb9+xb10+xb11+xb12';
				}elseif ($bulan == '02')
				{
					$totalx = 'b1+b3+b4+b5+b6+b7+b8+b9+b10+b11+b12';
					$pembagi = 'xb1+xb3+xb4+xb5+xb6+xb7+xb8+xb9+xb10+xb11+xb12';
				}elseif ($bulan == '03')
				{
					$totalx = 'b1+b2+b4+b5+b6+b7+b8+b9+b10+b11+b12';
					$pembagi = 'xb1+xb2+xb4+xb5+xb6+xb7+xb8+xb9+xb10+xb11+xb12';
				}elseif ($bulan == '04')
				{
					$totalx = 'b1+b2+b3+b5+b6+b7+b8+b9+b10+b11+b12';
					$pembagi = 'xb1+xb2+xb3+xb5+xb6+xb7+xb8+xb9+xb10+xb11+xb12';
				}elseif ($bulan == '05')
				{
					$totalx = 'b1+b2+b3+b4+b6+b7+b8+b9+b10+b11+b12';
					$pembagi = 'xb1+xb2+xb3+xb4+xb6+xb7+xb8+xb9+xb10+xb11+xb12';
				}elseif ($bulan == '06')
				{
					$totalx = 'b1+b2+b3+b4+b5+b7+b8+b9+b10+b11+b12';
					$pembagi = 'xb1+xb2+xb3+xb4+xb5+xb7+xb8+xb9+xb10+xb11+xb12';
				}elseif ($bulan == '07')
				{
					$totalx = 'b1+b2+b3+b4+b5+b6+b8+b9+b10+b11+b12';
					$pembagi = 'xb1+xb2+xb3+xb4+xb5+xb6+xb8+xb9+xb10+xb11+xb12';
				}elseif ($bulan == '08')
				{
					$totalx = 'b1+b2+b3+b4+b5+b6+b7+b9+b10+b11+b12';
					$pembagi = 'xb1+xb2+xb3+xb4+xb5+xb6+xb7+xb9+xb10+xb11+xb12';
				}elseif ($bulan == '09')
				{
					$totalx = 'b1+b2+b3+b4+b5+b6+b7+b8+b10+b11+b12';
					$pembagi = 'xb1+xb2+xb3+xb4+xb5+xb6+xb7+xb8+xb10+xb11+xb12';
				}elseif ($bulan == '10')
				{
					$totalx = 'b1+b2+b3+b4+b5+b6+b7+b8+b9+b11+b12';
					$pembagi = 'xb1+xb2+xb3+xb4+xb5+xb6+xb7+xb8+xb9+xb11+xb12';
				}elseif ($bulan == '11')
				{
					$totalx = 'b1+b2+b3+b4+b5+b6+b7+b8+b9+b10+b12';
					$pembagi = 'xb1+xb2+xb3+xb4+xb5+xb6+xb7+xb8+xb9+xb10+xb12';
				}elseif ($bulan == '12')
				{
					$totalx = 'b1+b2+b3+b4+b5+b6+b7+b8+b9+b10+b11';
					$pembagi = 'xb1+xb2+xb3+xb4+xb5+xb6+xb7+xb8+xb9+xb10+xb11';
				}else
				{
					$totalx = 'b1+b2+b3+b4+b5+b6+b7+b8+b9+b10+b11+b12';
					$pembagi = 'xb1+xb2+xb3+xb4+xb5+xb6+xb7+xb8+xb9+xb10+xb11+xb12';
				}

			}else{
				$pembagi = 'xb1+xb2+xb3+xb4+xb5+xb6+xb7+xb8+xb9+xb10+xb11+xb12';
				$totalx = 'b1+b2+b3+b4+b5+b6+b7+b8+b9+b10+b11+b12';
			}
		/* end cari bulan saat ini */

		/* pontianak masuk ke wilayah timur per 2017*/
			if ($year == '2010' || $year == '2011' || $year == '2012' || $year == '2013' || $year == '2014' || $year == '2015' || $year == '2016') 
			{
				//$wilayah = "wilayah = 2";
				//echo "wilayah : ".$wilayah."<br>";
				$wilayah = 'wilayah';
			} else {
				//$wilayah = "wilayah_2017 = 2";
				//echo "wilayah : ".$wilayah."<br>";
				$wilayah = 'wilayah_2017';
			}
		/* end pontianak masuk ke wilayah timur per 2017*/

		/* =============== PROSES DELETE MPM.OMZET_NEW ===================== */
				$query = "	
						delete from mpm.omzet_new 
						where kategori_supplier = ".'"'.$supp.'"'." and tahun = '$year'
							";
				$sql = $this->db->query($query);

				/* ================================================================= */

		/* PROSES INSERT KE TABEL OMZET_NEW */
			
	        $query = "
select 	a.nocab, sub, kode_comp, nama_comp, 
		t1, b1, t2, b2, t3, b3, t4, b4, t5, b5,	
		t6, b6, t7, b7, t8, b8, t9, b9, t10, b10, 
		t11, b11, t12, b12, ".$id.", total, rata,
		".'"'.$supp.'"'.",".$tgl_created.",".$year.", urutan, maxupload
FROM
(
	SELECT  a.nocab, a.kode_comp, b.sub, b.nama_comp,
			t1,b1,t2,b2,t3,b3,t4,b4,t5,b5,t6,b6,t7,b7,t8,b8,t9,b9,t10,b10,t11,b11,t12,b12, 
			".$id.", format(total,0) total,	format(totalx/jmlbulan,0) rata,
			".'"'.$supp.'"'.",".$tgl_created.",".$year.", urutan
	FROM
	(
		select 	nocab,kode_comp,kode,
				b1,b2,b3,b4,b5,b6,b7,b8,b9,b10,b11,b12,
				".$id.",total,totalx,
				format(sum($pembagi),0, 1) jmlbulan, 
				t1,t2,t3,t4,t5,t6,t7,t8,t9,t10,t11,t12
		FROM
		(
			select  nocab,kode_comp, 
					b1,b2,b3,b4,b5,b6,b7,b8,b9,b10,b11,b12,".$id.",
					b1+b2+b3+b4+b5+b6+b7+b8+b9+b10+b11+b12 as total,
					$totalx as totalx,                                
					IF(b1 = 0, 0, 1) xb1,IF(b2 = 0, 0, 1) xb2,IF(b3 = 0, 0, 1) xb3,
					IF(b4 = 0, 0, 1) xb4,IF(b5 = 0, 0, 1) xb5,IF(b6 = 0, 0, 1) xb6,
					IF(b7 = 0, 0, 1) xb7,IF(b8 = 0, 0, 1) xb8,IF(b9 = 0, 0, 1) xb9,
					IF(b10 = 0, 0, 1) xb10, IF(b11 = 0, 0, 1) xb11, IF(b12 = 0, 0, 1) xb12,
					sum(t1) as t1,sum(t2) as t2,sum(t3) as t3,sum(t4) as t4,
					sum(t5) as t5, sum(t6) as t6,sum(t7) as t7,sum(t8) as t8,
					sum(t9) as t9, sum(t10) as t10, sum(t11) as t11, sum(t12) as t12,kode
			from
			(
				select	`nocab`,kode_comp,
						sum(if(`blndok` = 1, `unit`, 0)) as `b1`,
						sum(if(`blndok` = 2, `unit`, 0)) as `b2`,
						sum(if(`blndok` = 3, `unit`, 0)) as `b3`,
						sum(if(`blndok` = 4, `unit`, 0)) as `b4`,
						sum(if(`blndok` = 5, `unit`, 0)) as `b5`,
						sum(if(`blndok` = 6, `unit`, 0)) as `b6`,
						sum(if(`blndok` = 7, `unit`, 0)) as `b7`,
						sum(if(`blndok` = 8, `unit`, 0)) as `b8`,
						sum(if(`blndok` = 9, `unit`, 0)) as `b9`,
						sum(if(`blndok` = 10, `unit`, 0)) as `b10`,
						sum(if(`blndok` = 11, `unit`, 0)) as `b11`,
						sum(if(`blndok` = 12, `unit`, 0)) as `b12`,
						sum(if(blndok = 1, trans, 0)) as t1,
						sum(if(blndok = 2, trans, 0)) as t2,
						sum(if(blndok = 3, trans, 0)) as t3,
						sum(if(blndok = 4, trans, 0)) as t4,
						sum(if(blndok = 5, trans, 0)) as t5,
						sum(if(blndok = 6, trans, 0)) as t6,
						sum(if(blndok = 7, trans, 0)) as t7,
						sum(if(blndok = 8, trans, 0)) as t8,
						sum(if(blndok = 9, trans, 0)) as t9,
						sum(if(blndok = 10, trans, 0)) as t10,
						sum(if(blndok = 11, trans, 0)) as t11,
						sum(if(blndok = 12, trans, 0)) as t12,kode
				from
				(
					 select `nocab`,kode_comp, `blndok`, sum(`tot1`) as `unit`, count(distinct(concat(kode_comp,kode_lang))) as trans, concat(kode_comp, nocab) as kode
					 from `data".$year."`.`fi`
					 where `nodokjdi` <> 'XXXXXX' 
							".$wheresupp."									
					 group by kode,`blndok`

					 union all

					 select `nocab`,kode_comp, `blndok`, sum(`tot1`) as `unit`,0 as trans, concat(kode_comp, nocab) as kode
					 from `data".$year."`.`ri`
					 where `nodokjdi` <> 'XXXXXX' 
							".$wheresupp."									
					 group by kode,`blndok`
				) `a`
				group by kode
			)`a` GROUP BY kode
		)a GROUP BY kode
	)a LEFT JOIN
	(
		select concat(kode_comp, nocab) as kode,naper,kode_comp, nama_comp, sub,urutan,`status`,status_cluster
		from mpm.tbl_tabcomp_new
		where `status` = 1
		GROUP BY concat(kode_comp, nocab)
	)b on a.kode = b.kode

	union ALL

	select *
	from
	(
		select	NAPER, '', sub, nama, 
				t1,b1, t2,b2, t3,b3, t4,b4,	t5,b5,
				t6,b6, t7,b7, t8,b8, t9,b9, t10,b10, 
				t11,b11,t12,b12,
				".$id.", format(total,0) as total,
				format(rata,0) as rata,3,4,5, c.urutan
		from
		(
			select	NAPER, sub, NAMA_COMP, 
					SUM(b1) b1,	SUM(b2) b2,	SUM(b3) b3,	SUM(b4) b4,
					SUM(b5) b5,	SUM(b6) b6,	SUM(b7) b7,	SUM(b8) b8,
					SUM(b9) b9,	SUM(b10) b10, SUM(b11) b11,
					SUM(b12) b12, sum(t1) as t1, sum(t2) as t2,				
					sum(t3) as t3,sum(t4) as t4,sum(t5) as t5,
					sum(t6) as t6,sum(t7) as t7,sum(t8) as t8,
					sum(t9) as t9,sum(t10) as t10,sum(t11) as t11,
					sum(t12) as t12,sum(total) as total,
					sum(rata) as rata
			from
			(
				select  naper, sub, b.NAMA_COMP,
						t1,b1, t2,b2, t3,b3,
						t4,b4, t5,b5, t6,b6,
						t7,b7, t8,b8, t9,b9,
						t10,b10, t11,b11, t12,b12, 
						".$id.", total,	(totalx/jmlbulan) as rata,
						".'"'.$supp.'"'.",".$tgl_created.",".$year."
				from
				(
					select  nocab, b1, b2, b3, b4, b5, b6, b7, b8, b9, 
							b10, b11, b12, ".$id.", total, totalx,
							format(sum($pembagi),0, 1) jmlbulan,
							t1,t2,t3,t4,t5,t6,t7,t8,t9,t10,t11,t12
					from
					(
						select  nocab, b1, b2, b3, b4, b5, 
								b6, b7, b8, b9, b10, b11, b12,
								".$id.",
								b1+b2+b3+b4+b5+b6+b7+b8+b9+b10+b11+b12 as total, 
								$totalx as totalx,
								IF(b1 = 0, 0, 1) xb1,IF(b2 = 0, 0, 1) xb2,
								IF(b3 = 0, 0, 1) xb3, IF(b4 = 0, 0, 1) xb4,
								IF(b5 = 0, 0, 1) xb5, IF(b6 = 0, 0, 1) xb6,
								IF(b7 = 0, 0, 1) xb7, IF(b8 = 0, 0, 1) xb8,
								IF(b9 = 0, 0, 1) xb9, IF(b10 = 0, 0, 1) xb10, 
								IF(b11 = 0, 0, 1) xb11, IF(b12 = 0, 0, 1) xb12,
								t1,t2,t3,t4,t5,t6,t7,t8,t9,t10,t11,t12
						from
						(
							select 	`nocab`, 
									sum(if(`blndok` = 1, `unit`, 0)) as `b1`,
									sum(if(`blndok` = 2, `unit`, 0)) as `b2`,
									sum(if(`blndok` = 3, `unit`, 0)) as `b3`,
									sum(if(`blndok` = 4, `unit`, 0)) as `b4`,
									sum(if(`blndok` = 5, `unit`, 0)) as `b5`,
									sum(if(`blndok` = 6, `unit`, 0)) as `b6`,
									sum(if(`blndok` = 7, `unit`, 0)) as `b7`,
									sum(if(`blndok` = 8, `unit`, 0)) as `b8`,
									sum(if(`blndok` = 9, `unit`, 0)) as `b9`,
									sum(if(`blndok` = 10, `unit`, 0)) as `b10`,
									sum(if(`blndok` = 11, `unit`, 0)) as `b11`,
									sum(if(`blndok` = 12, `unit`, 0)) as `b12`,
									sum(if(blndok = 1, trans, 0)) as t1,
									sum(if(blndok = 2, trans, 0)) as t2,
									sum(if(blndok = 3, trans, 0)) as t3,
									sum(if(blndok = 4, trans, 0)) as t4,
									sum(if(blndok = 5, trans, 0)) as t5,
									sum(if(blndok = 6, trans, 0)) as t6,
									sum(if(blndok = 7, trans, 0)) as t7,
									sum(if(blndok = 8, trans, 0)) as t8,
									sum(if(blndok = 9, trans, 0)) as t9,
									sum(if(blndok = 10, trans, 0)) as t10,
									sum(if(blndok = 11, trans, 0)) as t11,
									sum(if(blndok = 12, trans, 0)) as t12
							from
							(
								select 	`nocab`, `blndok`, 
										sum(`tot1`) as `unit`, 
										count(distinct(concat(kode_comp,kode_lang))) as trans
								from 	`data".$year."`.`fi`
								where 	`nodokjdi` <> 'XXXXXX' 					".$wheresupp."					
								group by `nocab`,`blndok`
								union all
 							 	select 	`nocab`, `blndok`, 
 							 			sum(`tot1`) as `unit`,0 as trans
								from 	`data".$year."`.`ri`
								where 	`nodokjdi` <> 'XXXXXX' 
										".$wheresupp."						
								group by `nocab`,`blndok`
							) `a`
							group by `nocab`
						)`a` GROUP BY nocab
					)a GROUP BY nocab
				)a
				inner join `mpm`.`tabcomp` `b` USING (`nocab`)
				group by `naper`
				order by sub, naper
			)a 
			GROUP BY sub
		)a inner JOIN 
		(
			select 	naper naper2,`status`, urutan, nama_comp nama
			from 	mpm.tbl_tabcomp_new
			WHERE 	`status` = '2' and status_cluster <> '1'
		)c on a.sub = c.naper2		
		GROUP BY sub
		ORDER BY c.urutan asc
	)b

	UNION ALL

	select	'Z', '', '','GRAND TOTAL', sum(if(blndok = 1, trans, 0)) as t1,
			sum(if(`blndok` = 1, `unit`, 0)) as `b1`,
			sum(if(blndok = 2, trans, 0)) as t2,
			sum(if(`blndok` = 2, `unit`, 0)) as `b2`,
			sum(if(blndok = 3, trans, 0)) as t3,
			sum(if(`blndok` = 3, `unit`, 0)) as `b3`,
			sum(if(blndok = 4, trans, 0)) as t4,
			sum(if(`blndok` = 4, `unit`, 0)) as `b4`,
			sum(if(blndok = 5, trans, 0)) as t5,
			sum(if(`blndok` = 5, `unit`, 0)) as `b5`,
			sum(if(blndok = 6, trans, 0)) as t6,
			sum(if(`blndok` = 6, `unit`, 0)) as `b6`,
			sum(if(blndok = 7, trans, 0)) as t7,
			sum(if(`blndok` = 7, `unit`, 0)) as `b7`,
			sum(if(blndok = 8, trans, 0)) as t8,
			sum(if(`blndok` = 8, `unit`, 0)) as `b8`,
			sum(if(blndok = 9, trans, 0)) as t9,
			sum(if(`blndok` = 9, `unit`, 0)) as `b9`,
			sum(if(blndok = 10, trans, 0)) as t10,
			sum(if(`blndok` = 10, `unit`, 0)) as `b10`,
			sum(if(blndok = 11, trans, 0)) as t11,
			sum(if(`blndok` = 11, `unit`, 0)) as `b11`,	
			sum(if(blndok = 12, trans, 0)) as t12,
			sum(if(`blndok` = 12, `unit`, 0)) as `b12`,
			".$id.",format(SUM(unit),0) as total,'-',0,".$year.",0,999
	from
	(
		 select `nocab`, `blndok`, sum(`tot1`) as `unit`, 
		 		count(distinct(concat(kode_comp,kode_lang))) as trans
		 from 	`data".$year."`.`fi`
		 where 	`nodokjdi` <> 'XXXXXX' ".$wheresupp."
		 group by `nocab`,`blndok`

		 union all

		 select `nocab`, `blndok`, sum(`tot1`) as `unit`,0 as trans
		 from 	`data".$year."`.`ri`
		 where `nodokjdi` <> 'XXXXXX' ".$wheresupp."
		 group by `nocab`,`blndok`
	) `a`
	ORDER BY urutan
)a LEFT JOIN
	(
		select 	max(lastupload) as maxupload,
				substring(filename,3,2) naper  
		from 	mpm.upload 
		group by naper
	)b on a.nocab = b.naper
";
	        $gabung = "insert into mpm.omzet_new ".$query;	  		
	        $sql = $this->db->query($gabung);
	        /*
	        echo "<pre>";
	        print_r($sql);
	        echo "</pre>";
			*/
		/* ============================================= */

			if ($dp == NULL || $dp == '') {
		        	
					$sql = "
						select *
						from mpm.omzet_new a INNER JOIN
						(
							select naper, nocab, kode_comp, nama_comp, sub, urutan, $wilayah
							from mpm.tbl_tabcomp_new
							where $wilayah = '2'
							GROUP BY naper
							ORDER BY urutan asc
						)b on a.naper = b.naper
						where a.kategori_supplier = ".'"'.$supp.'"'." and a.tahun = $year
						ORDER BY a.urutan asc
					";
				

		        } else {
		        	
		        	$sql = "
						select *
						from mpm.omzet_new a LEFT JOIN
						(
							select naper, nocab, kode_comp, nama_comp, sub, urutan, $wilayah
							from mpm.tbl_tabcomp_new
							where $wilayah = '2'
							GROUP BY naper
							ORDER BY urutan asc
						)b on a.naper = b.naper
						where a.naper in ($dp) and a.kategori_supplier = ".'"'.$supp.'"'." and a.tahun = $year
						ORDER BY a.urutan asc
					";
					
					
		        }
		        /*
		        	echo "<pre>";
					print_r($sql);
					echo "</pre>";
				*/
		        $query = $this->db->query($sql);

				if ($query->num_rows() > 0) 
				{
					return $query->result();
				} else {
					return array();
				}

    	}

	}



}