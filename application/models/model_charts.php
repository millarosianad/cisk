<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_charts extends CI_Model 
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

    public function omzet_charts($dataSegment)
    {
    	/* ---------DEFINISI VARIABEL----------------- */
		if ( function_exists( 'date_default_timezone_set' ) )
        date_default_timezone_set('Asia/Jakarta');        
        $tgl_created='"'.date('Y-m-d H:i:s').'"';
        $jam=date('ymdHis');
        $key_jam = $jam;
        $id=$this->session->userdata('id');
        /*
        echo "<pre>tgl_created : ";
        print_r($tgl_created);
        echo "<br>jam : ";
        print_r($jam);
        echo "<br>key_jam : ";
        print_r($key_jam);
        echo "<br>";
        echo "</pre>";
		*/

		$year = $dataSegment['years'];
		$supplier = $dataSegment['suppliers'];
		$group = $dataSegment['group'];
		$branch = $dataSegment['branch'];
		$tabel = $dataSegment['tabel'];
		/*
		echo "<pre>";
		echo "year : ".$year."<br>";
		echo "supplier : ".$supplier."<br>";
		echo "group : ".$group."<br>";
		echo "branch : ".$branch."<br>";
		echo "tabel : ".$tabel."<br>";
		echo "</pre>";
		*/
		 $sql="
	    	select *
			from mpm.$tabel
			where `key` = (
				select `key`
				from mpm.$tabel
				where sub = '$branch' and tahun = '$year' and kategori_supplier = '$supplier'
				ORDER BY `key` desc
				limit 1
			) and sub = '$branch' and tahun = '$year' and kategori_supplier = '$supplier'
			ORDER BY urutan DESC
			limit 1
	    			";
	    $query = $this->db->query($sql);
	    /*
	    echo "<pre>";
	    print_r($sql);
	    echo "</pre>";
		*/
	    if ($query->num_rows() > 0) 
		{
			return $query->result();
		} else {
			return array();
		}



    }


    public function omzet_all_dp($dataSegment){

	/* ---------DEFINISI VARIABEL----------------- */
		if ( function_exists( 'date_default_timezone_set' ) )
        date_default_timezone_set('Asia/Jakarta');        
        $tgl_created='"'.date('Y-m-d H:i:s').'"';
        $jam=date('ymdHis');
        $key_jam = $jam;
        $id=$this->session->userdata('id');
        /*
        echo "<pre>";
        print_r($tgl_created);
        echo "<br>";
        print_r($jam);
        echo "<br>";
        print_r($key_jam);
        echo "<br>";
        print_r($kombinasi);
        echo "<br>";
        echo "</pre>";
		*/

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
		echo "daftar_dp di model : ".$daftar_dp."<br>";
		echo "tgl_created di model : ".$tgl_created."<br>";
		echo "jam di model : ".$jam."<br>";
		echo "key jam di model : ".$key_jam."<br>";
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

        /* cek apakah sudah ada user yang akses menu omzet dan supp sebelumnya di tabel temporary user */
	    $sql="
	    	select 	1 
	    	from 	mpm.tbl_temporary_user 
	    	where   menuid = $menuid and
	    			supp = '$supp' and
	    			tahun = '$year' and 
	    			nilai = $total
	    			";
	    $query = $this->db->query($sql);
        /*
        echo "<pre>";
        print_r($sql);
        echo "</pre>";
	    */
	        
        if($query->num_rows()>0) //kalau sudah ada
        {
        	/*
			echo "<pre>";
			echo "data belum berubah, langsung menjalankan tampil mpm.omzet_new";
			echo "</pre>";
			*/
			if ($dp == NULL || $dp == '') 
			{	
				/* PROSES TAMPIL KE WEBSITE */
				/*
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
				*/

				$query="
							select * 
							from 		mpm.omzet_new
							where 		tahun = $year and
										kategori_supplier = '$supp' and
										`key` = (
											select 	max(`key`)
											from 	mpm.omzet_new
											where 	tahun = $year and
													kategori_supplier = '$supp'
							)
							ORDER BY urutan asc
						";
					/*
					echo "<pre>";
					print_r($query);
					echo "</pre>";
					*/        
			        $hasil = $this->db->query($query);
			        if ($hasil->num_rows() > 0) 
					{
						return $hasil->result();
					} else {
						return array();
					}


				/* END PROSES TAMPIL KE WEBSITE */

		        } else {
		        	
		        	/* PROSES TAMPIL KE WEBSITE */
		        	/*
					$this->db->order_by('urutan','asc');
					$this->db->where("tahun = ".'"'.$year.'"');
					$this->db->where("naper in ($daftar_dp)");
					$this->db->where("kategori_supplier = ".'"'.$supp.'"');
					$hasil = $this->db->get('omzet_new');		
					if ($hasil->num_rows() > 0) 
					{
						return $hasil->result();
					} else {
						return array();
					}*/

					$query="
							select * 
							from 		mpm.omzet_new
							where 		tahun = $year and
										kategori_supplier = '$supp' and
										naper in ($daftar_dp) and
										`key` = (
											select 	max(`key`)
											from 	mpm.omzet_new
											where 	tahun = $year and
													kategori_supplier = '$supp'
							)
							ORDER BY urutan asc
						";
					/*
					echo "<pre>";
					print_r($query);
					echo "</pre>";
					*/	        
			        $hasil = $this->db->query($query);
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
	        	
	        	/* ===== defenisikan kodeproduk berdasarkan kategori supplier yang dipilih user ==== */

	        	if($supp=='000') //jika pilih supplier :  4 besar (delto,ultra,marguna,jaya agung)
		        {
		            $wheresupp='
		                and (kodeprod like "60%" or kodeprod like "01%" or 
		                    kodeprod like "50%" or kodeprod like "70%" or 
		                    kodeprod like "06%" or kodeprod like "02%" or
		                    kodeprod like "04%") and kodeprod not in (select kodeprod from mpm.tabprod_unilever)
		            ';
		        }
        		else if($supp=='001') //jika pilih supplier : deltomed
        		{ 	
					$wheresupp='and (kodeprod like "60%" or kodeprod like "01%" or kodeprod like "50%" or kodeprod like "70%") and kodeprod not in (select kodeprod from mpm.tabprod_unilever)';
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
				/* ========================================== */

				/* == PROSES DELETE MPM.OMZET_NEW ===== */
				$query = "	
						delete from mpm.omzet_new 
						where kategori_supplier = ".'"'.$supp.'"'." and tahun = $year and id = $id
							";
				$sql = $this->db->query($query);
				/*
				echo "<pre>";
				print_r($query);
				print_r($sql);
				echo "<br>sql : ".$sql;
				echo "</pre>";
				*/
				/* =========== */
				/* == PROSES DELETE mpm.tbl_temp_omzet = */
				$query = "	
						delete from mpm.tbl_temp_omzet 
						where kategori_supplier = ".'"'.$supp.'"'." and tahun = $year and and id = $id
							";
				$sql = $this->db->query($query);
				/*
				echo "<pre>";
				print_r($query);
				print_r($sql);
				echo "<br>sql : ".$sql;
				echo "</pre>";
				*/
				/* ================================================================= */

				/* ========== PROSES INSERT KE mpm.tbl_temp_omzet =================== */
			
$query = "	   
	select  a.nocab, a.kode_comp, b.sub, b.nama_comp,
			t1,b1,t2,b2,t3,b3,t4,b4,t5,b5,t6,b6,t7,b7,t8,b8,t9,b9,t10,b10,t11,b11,t12,b12, 
			".$id.", total,	totalx/jmlbulan rata,
			".'"'.$supp.'"'.",".$tgl_created.",".$year.", urutan, `status`, maxupload, '$key_jam'
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
	LEFT JOIN
	(
			select 	max(lastupload) as maxupload,
					substring(filename,3,2) naper  
			from 	mpm.upload 
			group by naper
	)c on a.nocab = c.naper
	";      
			
	        $gabung = "insert into mpm.tbl_temp_omzet ".$query;			        
	        $sql = $this->db->query($gabung);
	        
	        echo "<pre>";
	        print_r($query);
	        print_r($sql);
	        echo "<br>sql = ".$sql;
	        echo "</pre>";
	        
			
			/* ============================================================== */
			if ($sql == '1') {
				
				$query="
				select 	nocab, kode_comp, a.sub, b.nama, 
						sum(t1), sum(b1), sum(t2), sum(b2), sum(t3), sum(b3), 
						sum(t4), sum(b4), sum(t5), sum(b5), sum(t6), sum(b6), 
						sum(t7), sum(b7), sum(t8), sum(b8), sum(t9), sum(b9), 
						sum(t10), sum(b10), sum(t11), sum(b11), sum(t12), sum(b12),
						id,sum(total),sum(rata), ".'"'.$supp.'"'.",tgl_created,tahun,b.urutan,b.`status`,'','$key_jam'
				
				from 		mpm.tbl_temp_omzet a inner JOIN
				(
						select 	naper naper2,`status`, urutan, nama_comp nama, sub
						from 		mpm.tbl_tabcomp_new
						WHERE 	`status` = '2' and status_cluster <> '1'
				)b on a.sub = b.sub
				where a.kategori_supplier ='$supp' and a.tahun = $year and a.`key` = '$key_jam'
				GROUP BY sub
				ORDER BY a.urutan asc

				";
				$gabung = "insert into mpm.tbl_temp_omzet ".$query;			        
		        $sql = $this->db->query($gabung);
		        /*
		        echo "<pre>";
		        print_r($query);
		        print_r($sql);
		        echo "<br>sql = ".$sql;
		        echo "</pre>";
				*/
				if ($sql == '1') {
		        	
		        	$query="
					select  '', '', '', 'GRAND(TOTAL)', 
							sum(t1), sum(b1), sum(t2), sum(b2), sum(t3), sum(b3), sum(t4), sum(b4), 
							sum(t5), sum(b5), sum(t6), sum(b6), sum(t7), sum(b7), sum(t8), sum(b8), 
							sum(t9), sum(b9), sum(t10), sum(b10), sum(t11), sum(b11), 
							sum(t12), sum(b12),	id,sum(total),sum(total)/$rata, ".'"'.$supp.'"'.",
							tgl_created,tahun,'999','3','','$key_jam'
					from 	mpm.tbl_temp_omzet a
					where 	`status` = 1 and kategori_supplier ='$supp' and tahun = $year and a.key = '$key_jam'
					";
					$gabung = "insert into mpm.tbl_temp_omzet ".$query;			        
			        $sql = $this->db->query($gabung);
			        /*
			        echo "<pre>";
			        print_r($query);
			        print_r($sql);
			        echo "<br>sql = ".$sql;
			        echo "</pre>";
					*/
					if ($sql == '1') {
			        	
			        	$query="
							insert into mpm.tbl_temporary_user (userid,menuid,nilai,datetime,supp,tahun) 
	            			values ($id,$menuid,$total,$tgl_created,'$supp','$year')
						";
							        
				        $sql = $this->db->query($query);
				        /*
				        echo "<pre>";
				        print_r($query);
				        print_r($sql);
				        echo "<br>sql = ".$sql;
				        echo "</pre>";
						*/
			        }else{
			        	echo "ada kesalahan <i>Proses insert temp_omzet_grand_total</i>. silahkan ulangi";
			        }

		        }else{
		        	echo "ada kesalahan <i>Proses insert temp_omzet_sub_total</i>. silahkan ulangi";
		        }

			}else{

				echo "ada kesalahan <i>Proses insert temp_omzet_per_sub_branch</i>. silahkan ulangi";
			}

			$gabung = "
				insert into mpm.omzet_new
				select 	nocab, sub, kode_comp, nama_comp, 
						t1,b1,t2,b2,t3,b3,t4,b4,t5,b5,t6,b6,t7,b7,t8,b8,t9,b9,t10,b10,t11,b11,t12,b12,
						id,total,rata,kategori_supplier,tgl_created,tahun,urutan,lastupload,'$key_jam'
				from mpm.tbl_temp_omzet
				where kategori_supplier = ".'"'.$supp.'"'." and tahun = $year and `key` = '$key_jam'
				";			        
	        $sql = $this->db->query($gabung);
	        /*
	        echo "<pre>";
	        print_r($gabung);
	        print_r($sql);
	        echo "<br>sql = ".$sql;
	        echo "</pre>";
			*/
			if ($sql == '1') {
	        	
	        	if ($dp == NULL || $dp == '') {
		        	
				/* PROSES TAMPIL KE WEBSITE */
					/*
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
					*/

					$query="
							select * 
							from 		mpm.omzet_new
							where 		tahun = $year and
										kategori_supplier = '$supp' and
										`key` = (
											select 	max(`key`)
											from 	mpm.omzet_new
											where 	tahun = $year and
													kategori_supplier = '$supp'
							)
							ORDER BY urutan asc
						";
							        
			        $hasil = $this->db->query($query);
			        if ($hasil->num_rows() > 0) 
					{
						return $hasil->result();
					} else {
						return array();
					}

					/* END PROSES TAMPIL KE WEBSITE */

	        	} else {
		        	
		        	/* PROSES TAMPIL KE WEBSITE */
					/*
					$this->db->order_by('urutan','asc');
					$this->db->where("tahun = ".'"'.$year.'"');
					$this->db->where("naper in ($daftar_dp)");
					$this->db->where("kategori_supplier = ".'"'.$supp.'"');
					
					$hasil = $this->db->get('omzet_new');		
					if ($hasil->num_rows() > 0) 
					{
						return $hasil->result();
					} else {
						return array();
					}
					*/
					$query="
							select * 
							from 		mpm.omzet_new
							where 		tahun = $year and
										kategori_supplier = '$supp' and
										naper in ($daftar_dp) and
										`key` = (
											select 	max(`key`)
											from 	mpm.omzet_new
											where 	tahun = $year and
													kategori_supplier = '$supp'
							)
							ORDER BY urutan asc
						";
							        
			        $hasil = $this->db->query($query);
			        if ($hasil->num_rows() > 0) 
					{
						return $hasil->result();
					} else {
						return array();
					}
					/* END PROSES TAMPIL KE WEBSITE */
	        	}

	        }else{
	        	echo "ada kesalahan <i>Proses insert omzet_new</i>. silahkan ulangi";
	        }
									
        }
	    
	}

	public function omzet_all_dp_barat($dataSegment){

	/* ---------DEFINISI VARIABEL----------------- */
		if ( function_exists( 'date_default_timezone_set' ) )
        date_default_timezone_set('Asia/Jakarta');        
        $tgl_created='"'.date('Y-m-d H:i:s').'"';
        $jam=date('ymdHis');
        $key_jam = $jam;
        $id=$this->session->userdata('id');

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
		echo "daftar_dp di model : ".$daftar_dp."<br>";
		echo "tgl_created di model : ".$tgl_created."<br>";
		echo "jam di model : ".$jam."<br>";
		echo "key jam di model : ".$key_jam."<br>";
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

        /* cek apakah sudah ada user yang akses menu omzet dan supp sebelumnya di tabel temporary user */
	    $sql="
	    	select 	1 
	    	from 	mpm.tbl_temporary_user 
	    	where   menuid = $menuid and
	    			supp = '$supp' and
	    			tahun = '$year' and 
	    			nilai = $total
	    			";
	    $query = $this->db->query($sql);
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
	        
        if($query->num_rows()>0) //kalau sudah ada
        {
        	/*
			echo "<pre>";
			echo "data belum berubah, langsung menjalankan tampil mpm.omzet_new";
			echo "</pre>";
			*/
			if ($dp == NULL || $dp == '') 
			{	
				/* PROSES TAMPIL KE WEBSITE */
				/*
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
				*/

				$query="
							select * 
							from 		mpm.omzet_new a inner join
							(
								select naper, nocab, kode_comp, nama_comp, sub, urutan, $wilayah
								from mpm.tbl_tabcomp_new
								where $wilayah = '1'
								GROUP BY naper
								ORDER BY urutan asc
							)b on a.naper = b.naper
							where 		tahun = $year and
										kategori_supplier = '$supp' and
										`key` = (
											select 	max(`key`)
											from 	mpm.omzet_new
											where 	tahun = $year and
													kategori_supplier = '$supp'
							)
							ORDER BY a.urutan asc
						";
					/*
					echo "<pre>";
					print_r($query);
					echo "</pre>";
					*/      
			        $hasil = $this->db->query($query);
			        if ($hasil->num_rows() > 0) 
					{
						return $hasil->result();
					} else {
						return array();
					}


				/* END PROSES TAMPIL KE WEBSITE */

		        } else {
		        	
		        	/* PROSES TAMPIL KE WEBSITE */
		        	/*
					$this->db->order_by('urutan','asc');
					$this->db->where("tahun = ".'"'.$year.'"');
					$this->db->where("naper in ($daftar_dp)");
					$this->db->where("kategori_supplier = ".'"'.$supp.'"');
					$hasil = $this->db->get('omzet_new');		
					if ($hasil->num_rows() > 0) 
					{
						return $hasil->result();
					} else {
						return array();
					}*/

					$query="
							select * 
							from 		mpm.omzet_new a inner join
							(
								select naper, nocab, kode_comp, nama_comp, sub, urutan, $wilayah
								from mpm.tbl_tabcomp_new
								where $wilayah = '1'
								GROUP BY naper
								ORDER BY urutan asc
							)b on a.naper = b.naper
							where 		a.naper in ($dp) and tahun = $year and
										kategori_supplier = '$supp' and
										`key` = (
											select 	max(`key`)
											from 	mpm.omzet_new
											where 	tahun = $year and
													kategori_supplier = '$supp'
							)
							ORDER BY a.urutan asc
						";
					/*
					echo "<pre>";
					print_r($query);
					echo "</pre>";
					*/	        
			        $hasil = $this->db->query($query);
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
	        	
	        	/* ===== defenisikan kodeproduk berdasarkan kategori supplier yang dipilih user ==== */

	        	if($supp=='000') //jika pilih supplier :  4 besar (delto,ultra,marguna,jaya agung)
		        {
		            $wheresupp='
		                and (kodeprod like "60%" or kodeprod like "01%" or 
		                    kodeprod like "50%" or kodeprod like "70%" or 
		                    kodeprod like "06%" or kodeprod like "02%" or
		                    kodeprod like "04%") and kodeprod not in (select kodeprod from mpm.tabprod_unilever)
		            ';
		        }
        		else if($supp=='001') //jika pilih supplier : deltomed
        		{ 	
					$wheresupp='and (kodeprod like "60%" or kodeprod like "01%" or kodeprod like "50%" or kodeprod like "70%") and kodeprod not in (select kodeprod from mpm.tabprod_unilever)';
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
				/* ========================================== */

				/* == PROSES DELETE MPM.OMZET_NEW ===== */
				$query = "	
						delete from mpm.omzet_new 
						where kategori_supplier = ".'"'.$supp.'"'." and tahun = $year and id = $id
							";
				$sql = $this->db->query($query);
				/*
				echo "<pre>";
				print_r($query);
				print_r($sql);
				echo "<br>sql : ".$sql;
				echo "</pre>";
				*/
				/* =========== */
				/* == PROSES DELETE mpm.tbl_temp_omzet = */
				$query = "	
						delete from mpm.tbl_temp_omzet 
						where kategori_supplier = ".'"'.$supp.'"'." and tahun = $year and and id = $id
							";
				$sql = $this->db->query($query);
				/*
				echo "<pre>";
				print_r($query);
				print_r($sql);
				echo "<br>sql : ".$sql;
				echo "</pre>";
				*/
				/* ================================================================= */

				/* ========== PROSES INSERT KE mpm.tbl_temp_omzet =================== */
			
$query = "	   
	select  a.nocab, a.kode_comp, b.sub, b.nama_comp,
			t1,b1,t2,b2,t3,b3,t4,b4,t5,b5,t6,b6,t7,b7,t8,b8,t9,b9,t10,b10,t11,b11,t12,b12, 
			".$id.", total,	totalx/jmlbulan rata,
			".'"'.$supp.'"'.",".$tgl_created.",".$year.", urutan, `status`, maxupload, '$key_jam'
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
	LEFT JOIN
	(
			select 	max(lastupload) as maxupload,
					substring(filename,3,2) naper  
			from 	mpm.upload 
			group by naper
	)c on a.nocab = c.naper
	";      
			
	        $gabung = "insert into mpm.tbl_temp_omzet ".$query;			        
	        $sql = $this->db->query($gabung);
	        /*
	        echo "<pre>";
	        print_r($query);
	        print_r($sql);
	        echo "<br>sql = ".$sql;
	        echo "</pre>";
	        */
			
			/* ============================================================== */
			if ($sql == '1') {
				
				$query="
				select 	nocab, kode_comp, a.sub, b.nama, 
						sum(t1), sum(b1), sum(t2), sum(b2), sum(t3), sum(b3), 
						sum(t4), sum(b4), sum(t5), sum(b5), sum(t6), sum(b6), 
						sum(t7), sum(b7), sum(t8), sum(b8), sum(t9), sum(b9), 
						sum(t10), sum(b10), sum(t11), sum(b11), sum(t12), sum(b12),
						id,sum(total),sum(rata), ".'"'.$supp.'"'.",tgl_created,tahun,b.urutan,b.`status`,'','$key_jam'
				
				from 		mpm.tbl_temp_omzet a inner JOIN
				(
						select 	naper naper2,`status`, urutan, nama_comp nama, sub
						from 		mpm.tbl_tabcomp_new
						WHERE 	`status` = '2' and status_cluster <> '1'
				)b on a.sub = b.sub
				where a.kategori_supplier ='$supp' and a.tahun = $year and a.`key` = '$key_jam'
				GROUP BY sub
				ORDER BY a.urutan asc

				";
				$gabung = "insert into mpm.tbl_temp_omzet ".$query;			        
		        $sql = $this->db->query($gabung);
		        /*
		        echo "<pre>";
		        print_r($query);
		        print_r($sql);
		        echo "<br>sql = ".$sql;
		        echo "</pre>";
				*/
				if ($sql == '1') {
		        	
		        	$query="
					select  '', '', '', 'GRAND(TOTAL)', 
							sum(t1), sum(b1), sum(t2), sum(b2), sum(t3), sum(b3), sum(t4), sum(b4), 
							sum(t5), sum(b5), sum(t6), sum(b6), sum(t7), sum(b7), sum(t8), sum(b8), 
							sum(t9), sum(b9), sum(t10), sum(b10), sum(t11), sum(b11), 
							sum(t12), sum(b12),	id,sum(total),sum(total)/$rata, ".'"'.$supp.'"'.",
							tgl_created,tahun,'999','3','','$key_jam'
					from 	mpm.tbl_temp_omzet a
					where 	`status` = 1 and kategori_supplier ='$supp' and tahun = $year and a.key = '$key_jam'
					";
					$gabung = "insert into mpm.tbl_temp_omzet ".$query;			        
			        $sql = $this->db->query($gabung);
			        /*
			        echo "<pre>";
			        print_r($query);
			        print_r($sql);
			        echo "<br>sql = ".$sql;
			        echo "</pre>";
					*/
					if ($sql == '1') {
			        	
			        	$query="
							insert into mpm.tbl_temporary_user (userid,menuid,nilai,datetime,supp,tahun) 
	            			values ($id,$menuid,$total,$tgl_created,'$supp','$year')
						";
							        
				        $sql = $this->db->query($query);
				        /*
				        echo "<pre>";
				        print_r($query);
				        print_r($sql);
				        echo "<br>sql = ".$sql;
				        echo "</pre>";
						*/
			        }else{
			        	echo "ada kesalahan <i>Proses insert temp_omzet_grand_total</i>. silahkan ulangi";
			        }

		        }else{
		        	echo "ada kesalahan <i>Proses insert temp_omzet_sub_total</i>. silahkan ulangi";
		        }

			}else{

				echo "ada kesalahan <i>Proses insert temp_omzet_per_sub_branch</i>. silahkan ulangi";
			}

			$gabung = "
				insert into mpm.omzet_new
				select 	nocab, sub, kode_comp, nama_comp, 
						t1,b1,t2,b2,t3,b3,t4,b4,t5,b5,t6,b6,t7,b7,t8,b8,t9,b9,t10,b10,t11,b11,t12,b12,
						id,total,rata,kategori_supplier,tgl_created,tahun,urutan,lastupload,'$key_jam'
				from mpm.tbl_temp_omzet
				where kategori_supplier = ".'"'.$supp.'"'." and tahun = $year and `key` = '$key_jam'
				";			        
	        $sql = $this->db->query($gabung);
	        /*
	        echo "<pre>";
	        print_r($gabung);
	        print_r($sql);
	        echo "<br>sql = ".$sql;
	        echo "</pre>";
			*/
			if ($sql == '1') {
	        	
	        	if ($dp == NULL || $dp == '') {
		        	
				/* PROSES TAMPIL KE WEBSITE */
					/*
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
					*/

					$query="
							select * 
							from 	mpm.omzet_new a inner join
							(
								select naper, nocab, kode_comp, nama_comp, sub, urutan, $wilayah
								from mpm.tbl_tabcomp_new
								where $wilayah = '1'
								GROUP BY naper
								ORDER BY urutan asc
							)b on a.naper = b.naper
							where 		tahun = $year and
										kategori_supplier = '$supp' and
										`key` = (
											select 	max(`key`)
											from 	mpm.omzet_new
											where 	tahun = $year and
													kategori_supplier = '$supp'
							)
							ORDER BY a.urutan asc
						";
							        
			        $hasil = $this->db->query($query);
			        if ($hasil->num_rows() > 0) 
					{
						return $hasil->result();
					} else {
						return array();
					}

					/* END PROSES TAMPIL KE WEBSITE */

	        	} else {
		        	
		        	/* PROSES TAMPIL KE WEBSITE */
					/*
					$this->db->order_by('urutan','asc');
					$this->db->where("tahun = ".'"'.$year.'"');
					$this->db->where("naper in ($daftar_dp)");
					$this->db->where("kategori_supplier = ".'"'.$supp.'"');
					
					$hasil = $this->db->get('omzet_new');		
					if ($hasil->num_rows() > 0) 
					{
						return $hasil->result();
					} else {
						return array();
					}
					*/
					$query="
							select * 
							from 		mpm.omzet_new a inner join
							(
								select naper, nocab, kode_comp, nama_comp, sub, urutan, $wilayah
								from mpm.tbl_tabcomp_new
								where $wilayah = '1'
								GROUP BY naper
								ORDER BY urutan asc
							)b on a.naper = b.naper
							where 		a.naper in ($dp) and tahun = $year and
										kategori_supplier = '$supp' and
										`key` = (
											select 	max(`key`)
											from 	mpm.omzet_new
											where 	tahun = $year and
													kategori_supplier = '$supp'
							)
							ORDER BY a.urutan asc
						";
							        
			        $hasil = $this->db->query($query);
			        if ($hasil->num_rows() > 0) 
					{
						return $hasil->result();
					} else {
						return array();
					}
					/* END PROSES TAMPIL KE WEBSITE */
	        	}

	        }else{
	        	echo "ada kesalahan <i>Proses insert omzet_new</i>. silahkan ulangi";
	        }
									
        }

	}

	public function omzet_all_dp_timur($dataSegment){

	/* ---------DEFINISI VARIABEL----------------- */
		if ( function_exists( 'date_default_timezone_set' ) )
        date_default_timezone_set('Asia/Jakarta');        
        $tgl_created='"'.date('Y-m-d H:i:s').'"';
        $jam=date('ymdHis');
        $key_jam = $jam;
        $id=$this->session->userdata('id');

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
		echo "daftar_dp di model : ".$daftar_dp."<br>";
		echo "tgl_created di model : ".$tgl_created."<br>";
		echo "jam di model : ".$jam."<br>";
		echo "key jam di model : ".$key_jam."<br>";
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

        /* cek apakah sudah ada user yang akses menu omzet dan supp sebelumnya di tabel temporary user */
	    $sql="
	    	select 	1 
	    	from 	mpm.tbl_temporary_user 
	    	where   menuid = $menuid and
	    			supp = '$supp' and
	    			tahun = '$year' and 
	    			nilai = $total
	    			";
	    $query = $this->db->query($sql);
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
	        
        if($query->num_rows()>0) //kalau sudah ada
        {
        	/*
			echo "<pre>";
			echo "data belum berubah, langsung menjalankan tampil mpm.omzet_new";
			echo "</pre>";
			*/
			if ($dp == NULL || $dp == '') 
			{	
				/* PROSES TAMPIL KE WEBSITE */
				/*
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
				*/

				$query="
							select * 
							from 		mpm.omzet_new a inner join
							(
								select naper, nocab, kode_comp, nama_comp, sub, urutan, $wilayah
								from mpm.tbl_tabcomp_new
								where $wilayah = '2'
								GROUP BY naper
								ORDER BY urutan asc
							)b on a.naper = b.naper
							where 		tahun = $year and
										kategori_supplier = '$supp' and
										`key` = (
											select 	max(`key`)
											from 	mpm.omzet_new
											where 	tahun = $year and
													kategori_supplier = '$supp'
							)
							ORDER BY a.urutan asc
						";
					/*
					echo "<pre>";
					print_r($query);
					echo "</pre>";
					 */      
			        $hasil = $this->db->query($query);
			        if ($hasil->num_rows() > 0) 
					{
						return $hasil->result();
					} else {
						return array();
					}


				/* END PROSES TAMPIL KE WEBSITE */

		        } else {
		        	
		        	/* PROSES TAMPIL KE WEBSITE */
		        	/*
					$this->db->order_by('urutan','asc');
					$this->db->where("tahun = ".'"'.$year.'"');
					$this->db->where("naper in ($daftar_dp)");
					$this->db->where("kategori_supplier = ".'"'.$supp.'"');
					$hasil = $this->db->get('omzet_new');		
					if ($hasil->num_rows() > 0) 
					{
						return $hasil->result();
					} else {
						return array();
					}*/

					$query="
							select * 
							from 		mpm.omzet_new a inner join
							(
								select naper, nocab, kode_comp, nama_comp, sub, urutan, $wilayah
								from mpm.tbl_tabcomp_new
								where $wilayah = '2'
								GROUP BY naper
								ORDER BY urutan asc
							)b on a.naper = b.naper
							where 		a.naper in ($dp) and tahun = $year and
										kategori_supplier = '$supp' and
										`key` = (
											select 	max(`key`)
											from 	mpm.omzet_new
											where 	tahun = $year and
													kategori_supplier = '$supp'
							)
							ORDER BY a.urutan asc
						";
					/*
					echo "<pre>";
					print_r($query);
					echo "</pre>";
					*/	        
			        $hasil = $this->db->query($query);
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
	        	
	        	/* ===== defenisikan kodeproduk berdasarkan kategori supplier yang dipilih user ==== */

	        	if($supp=='000') //jika pilih supplier :  4 besar (delto,ultra,marguna,jaya agung)
		        {
		            $wheresupp='
		                and (kodeprod like "60%" or kodeprod like "01%" or 
		                    kodeprod like "50%" or kodeprod like "70%" or 
		                    kodeprod like "06%" or kodeprod like "02%" or
		                    kodeprod like "04%") and kodeprod not in (select kodeprod from mpm.tabprod_unilever)
		            ';
		        }
        		else if($supp=='001') //jika pilih supplier : deltomed
        		{ 	
					$wheresupp='and (kodeprod like "60%" or kodeprod like "01%" or kodeprod like "50%" or kodeprod like "70%") and kodeprod not in (select kodeprod from mpm.tabprod_unilever)';
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
				/* ========================================== */

				/* == PROSES DELETE MPM.OMZET_NEW ===== */
				$query = "	
						delete from mpm.omzet_new 
						where kategori_supplier = ".'"'.$supp.'"'." and tahun = $year and id = $id
							";
				$sql = $this->db->query($query);
				/*
				echo "<pre>";
				print_r($query);
				print_r($sql);
				echo "<br>sql : ".$sql;
				echo "</pre>";
				*/
				/* =========== */
				/* == PROSES DELETE mpm.tbl_temp_omzet = */
				$query = "	
						delete from mpm.tbl_temp_omzet 
						where kategori_supplier = ".'"'.$supp.'"'." and tahun = $year and and id = $id
							";
				$sql = $this->db->query($query);
				/*
				echo "<pre>";
				print_r($query);
				print_r($sql);
				echo "<br>sql : ".$sql;
				echo "</pre>";
				*/
				/* ================================================================= */

				/* ========== PROSES INSERT KE mpm.tbl_temp_omzet =================== */
			
$query = "	   
	select  a.nocab, a.kode_comp, b.sub, b.nama_comp,
			t1,b1,t2,b2,t3,b3,t4,b4,t5,b5,t6,b6,t7,b7,t8,b8,t9,b9,t10,b10,t11,b11,t12,b12, 
			".$id.", total,	totalx/jmlbulan rata,
			".'"'.$supp.'"'.",".$tgl_created.",".$year.", urutan, `status`, maxupload, '$key_jam'
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
	LEFT JOIN
	(
			select 	max(lastupload) as maxupload,
					substring(filename,3,2) naper  
			from 	mpm.upload 
			group by naper
	)c on a.nocab = c.naper
	";      
			
	        $gabung = "insert into mpm.tbl_temp_omzet ".$query;			        
	        $sql = $this->db->query($gabung);
	        /*
	        echo "<pre>";
	        print_r($query);
	        print_r($sql);
	        echo "<br>sql = ".$sql;
	        echo "</pre>";
	        */
			
			/* ============================================================== */
			if ($sql == '1') {
				
				$query="
				select 	nocab, kode_comp, a.sub, b.nama, 
						sum(t1), sum(b1), sum(t2), sum(b2), sum(t3), sum(b3), 
						sum(t4), sum(b4), sum(t5), sum(b5), sum(t6), sum(b6), 
						sum(t7), sum(b7), sum(t8), sum(b8), sum(t9), sum(b9), 
						sum(t10), sum(b10), sum(t11), sum(b11), sum(t12), sum(b12),
						id,sum(total),sum(rata), ".'"'.$supp.'"'.",tgl_created,tahun,b.urutan,b.`status`,'','$key_jam'
				
				from 		mpm.tbl_temp_omzet a inner JOIN
				(
						select 	naper naper2,`status`, urutan, nama_comp nama, sub
						from 		mpm.tbl_tabcomp_new
						WHERE 	`status` = '2' and status_cluster <> '1'
				)b on a.sub = b.sub
				where a.kategori_supplier ='$supp' and a.tahun = $year and a.`key` = '$key_jam'
				GROUP BY sub
				ORDER BY a.urutan asc

				";
				$gabung = "insert into mpm.tbl_temp_omzet ".$query;			        
		        $sql = $this->db->query($gabung);
		        /*
		        echo "<pre>";
		        print_r($query);
		        print_r($sql);
		        echo "<br>sql = ".$sql;
		        echo "</pre>";
				*/
				if ($sql == '1') {
		        	
		        	$query="
					select  '', '', '', 'GRAND(TOTAL)', 
							sum(t1), sum(b1), sum(t2), sum(b2), sum(t3), sum(b3), sum(t4), sum(b4), 
							sum(t5), sum(b5), sum(t6), sum(b6), sum(t7), sum(b7), sum(t8), sum(b8), 
							sum(t9), sum(b9), sum(t10), sum(b10), sum(t11), sum(b11), 
							sum(t12), sum(b12),	id,sum(total),sum(total)/$rata, ".'"'.$supp.'"'.",
							tgl_created,tahun,'999','3','','$key_jam'
					from 	mpm.tbl_temp_omzet a
					where 	`status` = 1 and kategori_supplier ='$supp' and tahun = $year and a.key = '$key_jam'
					";
					$gabung = "insert into mpm.tbl_temp_omzet ".$query;			        
			        $sql = $this->db->query($gabung);
			        /*
			        echo "<pre>";
			        print_r($query);
			        print_r($sql);
			        echo "<br>sql = ".$sql;
			        echo "</pre>";
					*/
					if ($sql == '1') {
			        	
			        	$query="
							insert into mpm.tbl_temporary_user (userid,menuid,nilai,datetime,supp,tahun) 
	            			values ($id,$menuid,$total,$tgl_created,'$supp','$year')
						";
							        
				        $sql = $this->db->query($query);
				        /*
				        echo "<pre>";
				        print_r($query);
				        print_r($sql);
				        echo "<br>sql = ".$sql;
				        echo "</pre>";
						*/
			        }else{
			        	echo "ada kesalahan <i>Proses insert temp_omzet_grand_total</i>. silahkan ulangi";
			        }

		        }else{
		        	echo "ada kesalahan <i>Proses insert temp_omzet_sub_total</i>. silahkan ulangi";
		        }

			}else{

				echo "ada kesalahan <i>Proses insert temp_omzet_per_sub_branch</i>. silahkan ulangi";
			}

			$gabung = "
				insert into mpm.omzet_new
				select 	nocab, sub, kode_comp, nama_comp, 
						t1,b1,t2,b2,t3,b3,t4,b4,t5,b5,t6,b6,t7,b7,t8,b8,t9,b9,t10,b10,t11,b11,t12,b12,
						id,total,rata,kategori_supplier,tgl_created,tahun,urutan,lastupload,'$key_jam'
				from mpm.tbl_temp_omzet
				where kategori_supplier = ".'"'.$supp.'"'." and tahun = $year and `key` = '$key_jam'
				";			        
	        $sql = $this->db->query($gabung);
	        /*
	        echo "<pre>";
	        print_r($gabung);
	        print_r($sql);
	        echo "<br>sql = ".$sql;
	        echo "</pre>";
			*/
			if ($sql == '1') {
	        	
	        	if ($dp == NULL || $dp == '') {
		        	
				/* PROSES TAMPIL KE WEBSITE */
					/*
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
					*/

					$query="
							select * 
							from 	mpm.omzet_new a inner join
							(
								select naper, nocab, kode_comp, nama_comp, sub, urutan, $wilayah
								from mpm.tbl_tabcomp_new
								where $wilayah = '2'
								GROUP BY naper
								ORDER BY urutan asc
							)b on a.naper = b.naper
							where 		tahun = $year and
										kategori_supplier = '$supp' and
										`key` = (
											select 	max(`key`)
											from 	mpm.omzet_new
											where 	tahun = $year and
													kategori_supplier = '$supp'
							)
							ORDER BY a.urutan asc
						";
							        
			        $hasil = $this->db->query($query);
			        if ($hasil->num_rows() > 0) 
					{
						return $hasil->result();
					} else {
						return array();
					}

					/* END PROSES TAMPIL KE WEBSITE */

	        	} else {
		        	
		        	/* PROSES TAMPIL KE WEBSITE */
					/*
					$this->db->order_by('urutan','asc');
					$this->db->where("tahun = ".'"'.$year.'"');
					$this->db->where("naper in ($daftar_dp)");
					$this->db->where("kategori_supplier = ".'"'.$supp.'"');
					
					$hasil = $this->db->get('omzet_new');		
					if ($hasil->num_rows() > 0) 
					{
						return $hasil->result();
					} else {
						return array();
					}
					*/
					$query="
							select * 
							from 		mpm.omzet_new a inner join
							(
								select naper, nocab, kode_comp, nama_comp, sub, urutan, $wilayah
								from mpm.tbl_tabcomp_new
								where $wilayah = '2'
								GROUP BY naper
								ORDER BY urutan asc
							)b on a.naper = b.naper
							where 		a.naper in ($dp) and tahun = $year and
										kategori_supplier = '$supp' and
										`key` = (
											select 	max(`key`)
											from 	mpm.omzet_new
											where 	tahun = $year and
													kategori_supplier = '$supp'
							)
							ORDER BY a.urutan asc
						";
							        
			        $hasil = $this->db->query($query);
			        if ($hasil->num_rows() > 0) 
					{
						return $hasil->result();
					} else {
						return array();
					}
					/* END PROSES TAMPIL KE WEBSITE */
	        	}

	        }else{
	        	echo "ada kesalahan <i>Proses insert omzet_new</i>. silahkan ulangi";
	        }
									
        }

	}

	public function get_group($key='')
    {
        //$year=year(now());
        //$year = '2017';
        $userid=$this->session->userdata('id');
        $nocab=$this->session->userdata('nocab');
        $kode_supp = $key['kode_supp'];


        $sql="
        select id_group, nama_group as nama_group
		FROM mpm.tbl_group
		where kode_supp ='$kode_supp'
        ";

        $query=$this->db->query($sql);
        return $query;

    }

    public function omzet_all_dp_group_x($dataSegment){

	/* ---------DEFINISI VARIABEL----------------- */
		if ( function_exists( 'date_default_timezone_set' ) )
        date_default_timezone_set('Asia/Jakarta');        
        $tgl_created='"'.date('Y-m-d H:i:s').'"';
        $jam=date('ymdHis');
        $key_jam = $jam;
        $id=$this->session->userdata('id');
     
		$year = $dataSegment['year'];
		$group = $dataSegment['group'];	
		//echo "group : ".$group;

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

        /* cek jumlah row(count) di data2017.fi */
    	$this->db->select('count(*) as rows');
        $query = $this->db->get("data$year.fi");
        foreach($query->result() as $r)
        {
           $total = $r->rows;
        }	
    	/* end cek jumlah row(count) di data2017.fi */ 

        /* cek apakah sudah ada user yang akses menu omzet dan supp sebelumnya di tabel temporary user */
	    $sql="
	    	select 	1 
	    	from 	mpm.tbl_temporary_user 
	    	where   menuid = $menuid and
	    			supp = '$supp$group' and
	    			tahun = '$year' and 
	    			nilai = $total
	    			";
	    $query = $this->db->query($sql);
        /*
        echo "<pre>";
        print_r($sql);
        echo "</pre>";	    
	    */  
        if($query->num_rows()>0) //kalau sudah ada
        {        	
			if ($dp == NULL || $dp == '') 
			{	
				$query="
							select * 
							from 		mpm.omzet_new_deltomed
							where 		tahun = $year and
										kategori_supplier = '$supp' and
										`key` = (
											select 	max(`key`)
											from 	mpm.omzet_new_deltomed
											where 	tahun = $year and
													kategori_supplier = '$supp'
							)
							ORDER BY urutan asc
						";
					/*
					echo "<pre>";
					print_r($query);
					echo "</pre>";
					 */      
			        $hasil = $this->db->query($query);
			        if ($hasil->num_rows() > 0) 
					{
						return $hasil->result();
					} else {
						return array();
					}

				/* END PROSES TAMPIL KE WEBSITE */

		        } else {
		        	
					$query="
							select * 
							from 		mpm.omzet_new_deltomed
							where 		tahun = $year and
										kategori_supplier = '$supp' and
										naper in ($daftar_dp) and
										`key` = (
											select 	max(`key`)
											from 	mpm.omzet_new_deltomed
											where 	tahun = $year and
													kategori_supplier = '$supp'
							)
							ORDER BY urutan asc
						";
					/*
					echo "<pre>";
					print_r($query);
					echo "</pre>";
					*/	        
			        $hasil = $this->db->query($query);
			        if ($hasil->num_rows() > 0) 
					{
						return $hasil->result();
					} else {
						return array();
					}

					/* END PROSES TAMPIL KE WEBSITE */
		        }				
	        }else{

	        	/* === Proses pendefinisian bulan, pembagi untuk menentukan total dan rata2 === */				
				$bulan = date("m");
				$tahunsekarang = date("Y");
				if ($year == $tahunsekarang) //jika memilih tahun berjalan
				{				
					if ($bulan == '01')
					{
						$totalx_all = 'b2+b3+b4+b5+b6+b7+b8+b9+b10+b11+b12';
						$totalx_candy = 'b2_candy+b3_candy+b4_candy+b5_candy+b6_candy+b7_candy+b8_candy+b9_candy+b10_candy+b11_candy+b12_candy';
						$totalx_herbal = 'b2_herbal+b3_herbal+b4_herbal+b5_herbal+b6_herbal+b7_herbal+b8_herbal+b9_herbal+b10_herbal+b11_herbal+b12_herbal';
						$pembagi_all = 'xb2_all+xb3_all+xb4_all+xb5_all+xb6_all+xb7_all+xb8_all+xb9_all+xb10_all+xb11_all+xb12_all';
						$pembagi_candy = 'xb2_candy+xb3_candy+xb4_candy+xb5_candy+xb6_candy+xb7_candy+xb8_candy+xb9_candy+xb10_candy+xb11_candy+xb12_candy';
						$pembagi_herbal = 'xb2_herbal+xb3_herbal+xb4_herbal+xb5_herbal+xb6_herbal+xb7_herbal+xb8_herbal+xb9_herbal+xb10_herbal+xb11_herbal+xb12_herbal';
						$rata = 0;
					}elseif ($bulan == '02')
					{
						$totalx_all = 'b1+b3+b4+b5+b6+b7+b8+b9+b10+b11+b12';
						$totalx_candy = 'b1_candy+b3_candy+b4_candy+b5_candy+b6_candy+b7_candy+b8_candy+b9_candy+b10_candy+b11_candy+b12_candy';
						$totalx_herbal = 'b1_herbal+b3_herbal+b4_herbal+b5_herbal+b6_herbal+b7_herbal+b8_herbal+b9_herbal+b10_herbal+b11_herbal+b12_herbal';
						$pembagi_all = 'xb1_all+xb3_all+xb4_all+xb5_all+xb6_all+xb7_all+xb8_all+xb9_all+xb10_all+xb11_all+xb12_all';
						$pembagi_candy = 'xb1_candy+xb3_candy+xb4_candy+xb5_candy+xb6_candy+xb7_candy+xb8_candy+xb9_candy+xb10_candy+xb11_candy+xb12_candy';
						$pembagi_herbal = 'xb1_herbal+xb3_herbal+xb4_herbal+xb5_herbal+xb6_herbal+xb7_herbal+xb8_herbal+xb9_herbal+xb10_herbal+xb11_herbal+xb12_herbal';
						$rata = 1;
					}elseif ($bulan == '03')
					{
						$totalx_all = 'b1+b2+b4+b5+b6+b7+b8+b9+b10+b11+b12';
						$totalx_candy = 'b1_candy+b2_candy+b4_candy+b5_candy+b6_candy+b7_candy+b8_candy+b9_candy+b10_candy+b11_candy+b12_candy';
						$totalx_herbal = 'b1_herbal+b2_herbal+b4_herbal+b5_herbal+b6_herbal+b7_herbal+b8_herbal+b9_herbal+b10_herbal+b11_herbal+b12_herbal';
						$pembagi_all = 'xb1_all+xb2_all+xb4_all+xb5_all+xb6_all+xb7_all+xb8_all+xb9_all+xb10_all+xb11_all+xb12_all';
						$pembagi_candy = 'xb1_candy+xb2_candy+xb4_candy+xb5_candy+xb6_candy+xb7_candy+xb8_candy+xb9_candy+xb10_candy+xb11_candy+xb12_candy';
						$pembagi_herbal = 'xb1_herbal+xb2_herbal+xb4_herbal+xb5_herbal+xb6_herbal+xb7_herbal+xb8_herbal+xb9_herbal+xb10_herbal+xb11_herbal+xb12_herbal';
						$rata = 2;
					}elseif ($bulan == '04')
					{
						$totalx_all = 'b1+b2+b3+b5+b6+b7+b8+b9+b10+b11+b12';
						$totalx_candy = 'b1_candy+b2_candy+b3_candy+b5_candy+b6_candy+b7_candy+b8_candy+b9_candy+b10_candy+b11_candy+b12_candy';
						$totalx_herbal = 'b1_herbal+b2_herbal+b3_herbal+b5_herbal+b6_herbal+b7_herbal+b8_herbal+b9_herbal+b10_herbal+b11_herbal+b12_herbal';
						$pembagi_all = 'xb1_all+xb2_all+xb3_all+xb5_all+xb6_all+xb7_all+xb8_all+xb9_all+xb10_all+xb11_all+xb12_all';
						$pembagi_candy = 'xb1_candy+xb2_candy+xb3_candy+xb5_candy+xb6_candy+xb7_candy+xb8_candy+xb9_candy+xb10_candy+xb11_candy+xb12_candy';
						$pembagi_herbal = 'xb1_herbal+xb2_herbal+xb3_herbal+xb5_herbal+xb6_herbal+xb7_herbal+xb8_herbal+xb9_herbal+xb10_herbal+xb11_herbal+xb12_herbal';
						$rata = 3;
					}elseif ($bulan == '05')
					{
						$totalx_all = 'b1+b2+b3+b4+b6+b7+b8+b9+b10+b11+b12';
						$totalx_candy = 'b1_candy+b2_candy+b3_candy+b4_candy+b6_candy+b7_candy+b8_candy+b9_candy+b10_candy+b11_candy+b12_candy';
						$totalx_herbal = 'b1_herbal+b2_herbal+b3_herbal+b4_herbal+b6_herbal+b7_herbal+b8_herbal+b9_herbal+b10_herbal+b11_herbal+b12_herbal';
						$pembagi_all = 'xb1_all+xb2_all+xb3_all+xb4_all+xb6_all+xb7_all+xb8_all+xb9_all+xb10_all+xb11_all+xb12_all';
						$pembagi_candy = 'xb1_candy+xb2_candy+xb3_candy+xb4_candy+xb6_candy+xb7_candy+xb8_candy+xb9_candy+xb10_candy+xb11_candy+xb12_candy';
						$pembagi_herbal = 'xb1_herbal+xb2_herbal+xb3_herbal+xb4_herbal+xb6_herbal+xb7_herbal+xb8_herbal+xb9_herbal+xb10_herbal+xb11_herbal+xb12_herbal';
						$rata = 4;
					}elseif ($bulan == '06')
					{
						$totalx_all = 'b1+b2+b3+b4+b5+b7+b8+b9+b10+b11+b12';
						$totalx_candy = 'b1_candy+b2_candy+b3_candy+b4_candy+b5_candy+b7_candy+b8_candy+b9_candy+b10_candy+b11_candy+b12_candy';
						$totalx_herbal = 'b1_herbal+b2_herbal+b3_herbal+b4_herbal+b5_herbal+b7_herbal+b8_herbal+b9_herbal+b10_herbal+b11_herbal+b12_herbal';
						$pembagi_all = 'xb1_all+xb2_all+xb3_all+xb4_all+xb5_all+xb7_all+xb8_all+xb9_all+xb10_all+xb11_all+xb12_all';
						$pembagi_candy = 'xb1_candy+xb2_candy+xb3_candy+xb4_candy+xb5_candy+xb7_candy+xb8_candy+xb9_candy+xb10_candy+xb11_candy+xb12_candy';
						$pembagi_herbal = 'xb1_herbal+xb2_herbal+xb3_herbal+xb4_herbal+xb5_herbal+xb7_herbal+xb8_herbal+xb9_herbal+xb10_herbal+xb11_herbal+xb12_herbal';
						$rata = 5;
					}elseif ($bulan == '07')
					{
						$totalx_all = 'b1+b2+b3+b4+b5+b6+b8+b9+b10+b11+b12';
						$totalx_candy = 'b1_candy+b2_candy+b3_candy+b4_candy+b5_candy+b6_candy+b8_candy+b9_candy+b10_candy+b11_candy+b12_candy';
						$totalx_herbal = 'b1_herbal+b2_herbal+b3_herbal+b4_herbal+b5_herbal+b6_herbal+b8_herbal+b9_herbal+b10_herbal+b11_herbal+b12_herbal';
						$pembagi_all = 'xb1_all+xb2_all+xb3_all+xb4_all+xb5_all+xb6_all+xb8_all+xb9_all+xb10_all+xb11_all+xb12_all';
						$pembagi_candy = 'xb1_candy+xb2_candy+xb3_candy+xb4_candy+xb5_candy+xb6_candy+xb8_candy+xb9_candy+xb10_candy+xb11_candy+xb12_candy';
						$pembagi_herbal = 'xb1_herbal+xb2_herbal+xb3_herbal+xb4_herbal+xb5_herbal+xb6_herbal+xb8_herbal+xb9_herbal+xb10_herbal+xb11_herbal+xb12_herbal';
						$rata = 6;
					}elseif ($bulan == '08')
					{
						$totalx_all = 'b1+b2+b3+b4+b5+b6+b7+b9+b10+b11+b12';
						$totalx_candy = 'b1_candy+b2_candy+b3_candy+b4_candy+b5_candy+b6_candy+b7_candy+b9_candy+b10_candy+b11_candy+b12_candy';
						$totalx_herbal = 'b1_herbal+b2_herbal+b3_herbal+b4_herbal+b5_herbal+b6_herbal+b7_herbal+b9_herbal+b10_herbal+b11_herbal+b12_herbal';
						$pembagi_all = 'xb1_all+xb2_all+xb3_all+xb4_all+xb5_all+xb6_all+xb7_all+xb9_all+xb10_all+xb11_all+xb12_all';
						$pembagi_candy = 'xb1_candy+xb2_candy+xb3_candy+xb4_candy+xb5_candy+xb6_candy+xb7_candy+xb9_candy+xb10_candy+xb11_candy+xb12_candy';
						$pembagi_herbal = 'xb1_herbal+xb2_herbal+xb3_herbal+xb4_herbal+xb5_herbal+xb6_herbal+xb7_herbal+xb9_herbal+xb10_herbal+xb11_herbal+xb12_herbal';
						$rata = 7;
					}elseif ($bulan == '09')
					{
						$totalx_all = 'b1+b2+b3+b4+b5+b6+b7+b8+b10+b11+b12';
						$totalx_candy = 'b1_candy+b2_candy+b3_candy+b4_candy+b5_candy+b6_candy+b7_candy+b8_candy+b10_candy+b11_candy+b12_candy';
						$totalx_herbal = 'b1_herbal+b2_herbal+b3_herbal+b4_herbal+b5_herbal+b6_herbal+b7_herbal+b8_herbal+b10_herbal+b11_herbal+b12_herbal';
						$pembagi_all = 'xb1_all+xb2_all+xb3_all+xb4_all+xb5_all+xb6_all+xb7_all+xb8_all+xb10_all+xb11_all+xb12_all';
						$pembagi_candy = 'xb1_candy+xb2_candy+xb3_candy+xb4_candy+xb5_candy+xb6_candy+xb7_candy+xb8_candy+xb10_candy+xb11_candy+xb12_candy';
						$pembagi_herbal = 'xb1_herbal+xb2_herbal+xb3_herbal+xb4_herbal+xb5_herbal+xb6_herbal+xb7_herbal+xb8_herbal+xb10_herbal+xb11_herbal+xb12_herbal';
						$rata = 8;
					}elseif ($bulan == '10')
					{
						$totalx_all = 'b1+b2+b3+b4+b5+b6+b7+b8+b9+b11+b12';
						$totalx_candy = 'b1_candy+b2_candy+b3_candy+b4_candy+b5_candy+b6_candy+b7_candy+b8_candy+b9_candy+b11_candy+b12_candy';
						$totalx_herbal = 'b1_herbal+b2_herbal+b3_herbal+b4_herbal+b5_herbal+b6_herbal+b7_herbal+b8_herbal+b9_herbal+b11_herbal+b12_herbal';
						$pembagi_all = 'xb1_all+xb2_all+xb3_all+xb4_all+xb5_all+xb6_all+xb7_all+xb8_all+xb9_all+xb11_all+xb12_all';
						$pembagi_candy = 'xb1_candy+xb2_candy+xb3_candy+xb4_candy+xb5_candy+xb6_candy+xb7_candy+xb8_candy+xb9_candy+xb11_candy+xb12_candy';
						$pembagi_herbal = 'xb1_herbal+xb2_herbal+xb3_herbal+xb4_herbal+xb5_herbal+xb6_herbal+xb7_herbal+xb8_herbal+xb9_herbal+xb11_herbal+xb12_herbal';
						$rata = 9;
					}elseif ($bulan == '11')
					{
						$totalx_all = 'b1+b2+b3+b4+b5+b6+b7+b8+b9+b10+b12';
						$totalx_candy = 'b1_candy+b2_candy+b3_candy+b4_candy+b5_candy+b6_candy+b7_candy+b8_candy+b9_candy+b10_candy+b12_candy';
						$totalx_herbal = 'b1_herbal+b2_herbal+b3_herbal+b4_herbal+b5_herbal+b6_herbal+b7_herbal+b8_herbal+b9_herbal+b10_herbal+b12_herbal';
						$pembagi_all = 'xb1_all+xb2_all+xb3_all+xb4_all+xb5_all+xb6_all+xb7_all+xb8_all+xb9_all+xb10_all+xb12_all';
						$pembagi_candy = 'xb1_candy+xb2_candy+xb3_candy+xb4_candy+xb5_candy+xb6_candy+xb7_candy+xb8_candy+xb9_candy+xb10_candy+xb12_candy';
						$pembagi_herbal = 'xb1_herbal+xb2_herbal+xb3_herbal+xb4_herbal+xb5_herbal+xb6_herbal+xb7_herbal+xb8_herbal+xb9_herbal+xb10_herbal+xb12_herbal';
						$rata = 10;
					}elseif ($bulan == '12')
					{
						$totalx_all = 'b1+b2+b3+b4+b5+b6+b7+b8+b9+b10+b11';
						$totalx_candy = 'b1_candy+b2_candy+b3_candy+b4_candy+b5_candy+b6_candy+b7_candy+b8_candy+b9_candy+b10_candy+b11_candy';
						$totalx_herbal = 'b1_herbal+b2_herbal+b3_herbal+b4_herbal+b5_herbal+b6_herbal+b7_herbal+b8_herbal+b9_herbal+b10_herbal+b11_herbal';
						$pembagi_all = 'xb1_all+xb2_all+xb3_all+xb4_all+xb5_all+xb6_all+xb7_all+xb8_all+xb9_all+xb10_all+xb11_all';
						$pembagi_candy = 'xb1_candy+xb2_candy+xb3_candy+xb4_candy+xb5_candy+xb6_candy+xb7_candy+xb8_candy+xb9_candy+xb10_candy+xb11_candy';
						$pembagi_herbal = 'xb1_herbal+xb2_herbal+xb3_herbal+xb4_herbal+xb5_herbal+xb6_herbal+xb7_herbal+xb8_herbal+xb9_herbal+xb10_herbal+xb11_herbal';
						$rata = 11;
					}else
					{
						$totalx_all = 'b1+b2+b3+b4+b5+b6+b7+b8+b9+b10+b11+b12';
						$totalx_candy = 'b1_candy+b2_candy+b3_candy+b4_candy+b5_candy+b6_candy+b7_candy+b8_candy+b9_candy+b10_candy+b11_candy+b12_candy';
						$totalx_herbal = 'b1_herbal+b2_herbal+b3_herbal+b4_herbal+b5_herbal+b6_herbal+b7_herbal+b8_herbal+b9_herbal+b10_herbal+b11_herbal+b12_herbal';
						$pembagi_all = 'xb1_all+xb2_all+xb3_all+xb4_all+xb5_all+xb6_all+xb7_all+xb8_all+xb9_all+xb10_all+xb11_all+xb12_all';
						$pembagi_candy = 'xb1_candy+xb2_candy+xb3_candy+xb4_candy+xb5_candy+xb6_candy+xb7_candy+xb8_candy+xb9_candy+xb10_candy+xb11_candy+xb12_candy';
						$pembagi_herbal = 'xb1_herbal+xb2_herbal+xb3_herbal+xb4_herbal+xb5_herbal+xb6_herbal+xb7_herbal+xb8_herbal+xb9_herbal+xb10_herbal+xb11_herbal+xb12_herbal';
						$rata = 12;
					}

				}else{
						$totalx_all = 'b1+b2+b3+b4+b5+b6+b7+b8+b9+b10+b11+b12';
						$totalx_candy = 'b1_candy+b2_candy+b3_candy+b4_candy+b5_candy+b6_candy+b7_candy+b8_candy+b9_candy+b10_candy+b11_candy+b12_candy';
						$totalx_herbal = 'b1_herbal+b2_herbal+b3_herbal+b4_herbal+b5_herbal+b6_herbal+b7_herbal+b8_herbal+b9_herbal+b10_herbal+b11_herbal+b12_herbal';
						$pembagi_all = 'xb1_all+xb2_all+xb3_all+xb4_all+xb5_all+xb6_all+xb7_all+xb8_all+xb9_all+xb10_all+xb11_all+xb12_all';
						$pembagi_candy = 'xb1_candy+xb2_candy+xb3_candy+xb4_candy+xb5_candy+xb6_candy+xb7_candy+xb8_candy+xb9_candy+xb10_candy+xb11_candy+xb12_candy';
						$pembagi_herbal = 'xb1_herbal+xb2_herbal+xb3_herbal+xb4_herbal+xb5_herbal+xb6_herbal+xb7_herbal+xb8_herbal+xb9_herbal+xb10_herbal+xb11_herbal+xb12_herbal';
						$rata = 12;
				}
				/* ========================================== */

				/* == PROSES DELETE MPM.OMZET_NEW ===== */
				$query = "	
						delete from mpm.omzet_new_deltomed
						where kategori_supplier = ".'"'.$supp.'"'." and tahun = $year and id = $id
							";
				//echo "<pre>";
				//print_r($query);
				//echo "</pre>";
				$sql = $this->db->query($query);

				/* == PROSES DELETE mpm.tbl_temp_omzet = */
				$query = "	
						delete from mpm.tbl_temp_omzet_deltomed 
						where kategori_supplier = ".'"'.$supp.'"'." and tahun = $year and and id = $id
							";
				$sql = $this->db->query($query);

				/* ========== PROSES INSERT KE mpm.tbl_temp_omzet =================== */
			
$query = "	   
	select  a.nocab, a.kode_comp, b.sub, b.nama_comp,
			b1_candy,b1_herbal,b1,
			b2_candy,b2_herbal,b2,
			b3_candy,b3_herbal,b3,
			b4_candy,b4_herbal,b4,
			b5_candy,b5_herbal,b5,
			b6_candy,b6_herbal,b6,
			b7_candy,b7_herbal,b7,
			b8_candy,b8_herbal,b8,
			b9_candy,b9_herbal,b9,
			b10_candy,b10_herbal,b10,
			b11_candy,b11_herbal,b11,
			b12_candy,b12_herbal,b12,
			t1_candy,t1_herbal,t1_all,
			t2_candy,t2_herbal,t2_all,
			t3_candy,t3_herbal,t3_all,
			t4_candy,t4_herbal,t4_all,
			t5_candy,t5_herbal,t5_all,
			t6_candy,t6_herbal,t6_all,
			t7_candy,t7_herbal,t7_all,
			t8_candy,t8_herbal,t8_all,
			t9_candy,t9_herbal,t9_all,
			t10_candy,t10_herbal,t10_all,
			t11_candy,t11_herbal,t11_all,
			t12_candy,t12_herbal,t12_all, 
			".$id.", 
			total_candy,total_herbal,total_all,
			totalx_candy/jmlbulan_candy rata_candy,
			totalx_herbal/jmlbulan_herbal rata_herbal,
			totalx_all/jmlbulan_all rata_all,
			".'"'.$supp.'"'.",".$tgl_created.",".$year.", urutan, status, maxupload, '$key_jam'
	FROM
	(
		select 	nocab,kode_comp,kode,
				b1_candy,b1_herbal,b1,
				b2_candy,b2_herbal,b2,
				b3_candy,b3_herbal,b3,
				b4_candy,b4_herbal,b4,
				b5_candy,b5_herbal,b5,
				b6_candy,b6_herbal,b6,
				b7_candy,b7_herbal,b7,
				b8_candy,b8_herbal,b8,
				b9_candy,b9_herbal,b9,
				b10_candy,b10_herbal,b10,
				b11_candy,b11_herbal,b11,
				b12_candy,b12_herbal,b12,
				t1_candy,t1_herbal,t1_all,
				t2_candy,t2_herbal,t2_all,
				t3_candy,t3_herbal,t3_all,
				t4_candy,t4_herbal,t4_all,
				t5_candy,t5_herbal,t5_all,
				t6_candy,t6_herbal,t6_all,
				t7_candy,t7_herbal,t7_all,
				t8_candy,t8_herbal,t8_all,
				t9_candy,t9_herbal,t9_all,
				t10_candy,t10_herbal,t10_all,
				t11_candy,t11_herbal,t11_all,
				t12_candy,t12_herbal,t12_all,
				".$id.",
				total_candy,total_herbal,total_all,
				totalx_candy,totalx_herbal,totalx_all,
				format(sum($pembagi_candy),0, 1) jmlbulan_candy, 
				format(sum($pembagi_herbal),0, 1) jmlbulan_herbal,
				format(sum($pembagi_all),0, 1) jmlbulan_all
		FROM
		(
			select  nocab,kode_comp, 
					b1_candy,b1_herbal,b1,
					b2_candy,b2_herbal,b2,
					b3_candy,b3_herbal,b3,
					b4_candy,b4_herbal,b4,
					b5_candy,b5_herbal,b5,
					b6_candy,b6_herbal,b6,
					b7_candy,b7_herbal,b7,
					b8_candy,b8_herbal,b8,
					b9_candy,b9_herbal,b9,
					b10_candy,b10_herbal,b10,
					b11_candy,b11_herbal,b11,
					b12_candy,b12_herbal,b12,
					".$id.",
					b1_candy+b2_candy+b3_candy+b4_candy+b5_candy+b6_candy+b7_candy+b8_candy+b9_candy+b10_candy+b11_candy+b12_candy as total_candy,
					b1_herbal+b2_herbal+b3_herbal+b4_herbal+b5_herbal+b6_herbal+b7_herbal+b8_herbal+b9_herbal+b10_herbal+b11_herbal+b12_herbal as total_herbal,	
					b1+b2+b3+b4+b5+b6+b7+b8+b9+b10+b11+b12 as total_all,
					$totalx_all as totalx_all,
					$totalx_candy as totalx_candy,
					$totalx_herbal as totalx_herbal,					                                
					IF(b1_candy = 0, 0, 1) xb1_candy,IF(b1_herbal = 0, 0, 1) xb1_herbal,IF(b1= 0, 0, 1) xb1_all,
					IF(b2_candy = 0, 0, 1) xb2_candy,IF(b2_herbal = 0, 0, 1) xb2_herbal,IF(b2= 0, 0, 1) xb2_all,
					IF(b3_candy = 0, 0, 1) xb3_candy,IF(b3_herbal = 0, 0, 1) xb3_herbal,IF(b3= 0, 0, 1) xb3_all,
					IF(b4_candy = 0, 0, 1) xb4_candy,IF(b4_herbal = 0, 0, 1) xb4_herbal,IF(b4= 0, 0, 1) xb4_all,
					IF(b5_candy = 0, 0, 1) xb5_candy,IF(b5_herbal = 0, 0, 1) xb5_herbal,IF(b5= 0, 0, 1) xb5_all,
					IF(b6_candy = 0, 0, 1) xb6_candy,IF(b6_herbal = 0, 0, 1) xb6_herbal,IF(b6= 0, 0, 1) xb6_all,
					IF(b7_candy = 0, 0, 1) xb7_candy,IF(b7_herbal = 0, 0, 1) xb7_herbal,IF(b7= 0, 0, 1) xb7_all,
					IF(b8_candy = 0, 0, 1) xb8_candy,IF(b8_herbal = 0, 0, 1) xb8_herbal,IF(b8= 0, 0, 1) xb8_all,
					IF(b9_candy = 0, 0, 1) xb9_candy,IF(b9_herbal = 0, 0, 1) xb9_herbal,IF(b9= 0, 0, 1) xb9_all,
					IF(b10_candy = 0, 0, 1) xb10_candy,IF(b10_herbal = 0, 0, 1) xb10_herbal,IF(b10= 0, 0, 1) xb10_all, 
					IF(b11_candy = 0, 0, 1) xb11_candy,IF(b11_herbal = 0, 0, 1) xb11_herbal,IF(b11= 0, 0, 1) xb11_all,
					IF(b12_candy = 0, 0, 1) xb12_candy,IF(b12_herbal = 0, 0, 1) xb12_herbal,IF(b12= 0, 0, 1) xb12_all,
					sum(t1_candy) as t1_candy,sum(t1_herbal) as t1_herbal,sum(t1) as t1_all,
					sum(t2_candy) as t2_candy,sum(t2_herbal) as t2_herbal,sum(t2) as t2_all,
					sum(t3_candy) as t3_candy,sum(t3_herbal) as t3_herbal,sum(t3) as t3_all,
					sum(t4_candy) as t4_candy,sum(t4_herbal) as t4_herbal,sum(t4) as t4_all,
					sum(t5_candy) as t5_candy,sum(t5_herbal) as t5_herbal,sum(t5) as t5_all,
					sum(t6_candy) as t6_candy,sum(t6_herbal) as t6_herbal,sum(t6) as t6_all,
					sum(t7_candy) as t7_candy,sum(t7_herbal) as t7_herbal,sum(t7) as t7_all,
					sum(t8_candy) as t8_candy,sum(t8_herbal) as t8_herbal,sum(t8) as t8_all,
					sum(t9_candy) as t9_candy,sum(t9_herbal) as t9_herbal,sum(t9) as t9_all,
					sum(t10_candy) as t10_candy,sum(t10_herbal) as t10_herbal,sum(t10) as t10_all,
					sum(t11_candy) as t11_candy,sum(t11_herbal) as t11_herbal,sum(t11) as t11_all,
					sum(t12_candy) as t12_candy,sum(t12_herbal) as t12_herbal,sum(t12) as t12_all,
					kode
			from
			(
				select	nocab,kode_comp,
						sum(if(blndok = 1, unit, 0)) as b1,
						sum(if(blndok = 1 and status = 'candy', unit, 0)) as b1_candy,
						sum(if(blndok = 1 and status = 'herbal', unit, 0)) as b1_herbal,
						sum(if(blndok = 2, unit, 0)) as b2,
						sum(if(blndok = 2 and status = 'candy', unit, 0)) as b2_candy,
						sum(if(blndok = 2 and status = 'herbal', unit, 0)) as b2_herbal,
						sum(if(blndok = 3, unit, 0)) as b3,
						sum(if(blndok = 3 and status = 'candy', unit, 0)) as b3_candy,
						sum(if(blndok = 3 and status = 'herbal', unit, 0)) as b3_herbal,
						sum(if(blndok = 4, unit, 0)) as b4,
						sum(if(blndok = 4 and status = 'candy', unit, 0)) as b4_candy,
						sum(if(blndok = 4 and status = 'herbal', unit, 0)) as b4_herbal,
						sum(if(blndok = 5, unit, 0)) as b5,
						sum(if(blndok = 5 and status = 'candy', unit, 0)) as b5_candy,
						sum(if(blndok = 5 and status = 'herbal', unit, 0)) as b5_herbal,
						sum(if(blndok = 6, unit, 0)) as b6,
						sum(if(blndok = 6 and status = 'candy', unit, 0)) as b6_candy,
						sum(if(blndok = 6 and status = 'herbal', unit, 0)) as b6_herbal,
						sum(if(blndok = 7, unit, 0)) as b7,
						sum(if(blndok = 7 and status = 'candy', unit, 0)) as b7_candy,
						sum(if(blndok = 7 and status = 'herbal', unit, 0)) as b7_herbal,
						sum(if(blndok = 8, unit, 0)) as b8,
						sum(if(blndok = 8 and status = 'candy', unit, 0)) as b8_candy,
						sum(if(blndok = 8 and status = 'herbal', unit, 0)) as b8_herbal,
						sum(if(blndok = 9, unit, 0)) as b9,
						sum(if(blndok = 9 and status = 'candy', unit, 0)) as b9_candy,
						sum(if(blndok = 9 and status = 'herbal', unit, 0)) as b9_herbal,
						sum(if(blndok = 10, unit, 0)) as b10,
						sum(if(blndok = 10 and status = 'candy', unit, 0)) as b10_candy,
						sum(if(blndok = 10 and status = 'herbal', unit, 0)) as b10_herbal,
						sum(if(blndok = 11, unit, 0)) as b11,
						sum(if(blndok = 11 and status = 'candy', unit, 0)) as b11_candy,
						sum(if(blndok = 11 and status = 'herbal', unit, 0)) as b11_herbal,
						sum(if(blndok = 12, unit, 0)) as b12,
						sum(if(blndok = 12 and status = 'candy', unit, 0)) as b12_candy,
						sum(if(blndok = 12 and status = 'herbal', unit, 0)) as b12_herbal,
						sum(if(blndok = 1, trans, 0)) as t1,
						sum(if(blndok = 1 and status = 'candy', trans, 0)) as t1_candy,
						sum(if(blndok = 1 and status = 'herbal', trans, 0)) as t1_herbal,
						sum(if(blndok = 2, trans, 0)) as t2,
						sum(if(blndok = 2 and status = 'candy', trans, 0)) as t2_candy,
						sum(if(blndok = 2 and status = 'herbal', trans, 0)) as t2_herbal,
						sum(if(blndok = 3, trans, 0)) as t3,
						sum(if(blndok = 3 and status = 'candy', trans, 0)) as t3_candy,
						sum(if(blndok = 3 and status = 'herbal', trans, 0)) as t3_herbal,
						sum(if(blndok = 4, trans, 0)) as t4,
						sum(if(blndok = 4 and status = 'candy', trans, 0)) as t4_candy,
						sum(if(blndok = 4 and status = 'herbal', trans, 0)) as t4_herbal,
						sum(if(blndok = 5, trans, 0)) as t5,
						sum(if(blndok = 5 and status = 'candy', trans, 0)) as t5_candy,
						sum(if(blndok = 5 and status = 'herbal', trans, 0)) as t5_herbal,
						sum(if(blndok = 6, trans, 0)) as t6,
						sum(if(blndok = 6 and status = 'candy', trans, 0)) as t6_candy,
						sum(if(blndok = 6 and status = 'herbal', trans, 0)) as t6_herbal,
						sum(if(blndok = 7, trans, 0)) as t7,
						sum(if(blndok = 7 and status = 'candy', trans, 0)) as t7_candy,
						sum(if(blndok = 7 and status = 'herbal', trans, 0)) as t7_herbal,
						sum(if(blndok = 8, trans, 0)) as t8,
						sum(if(blndok = 8 and status = 'candy', trans, 0)) as t8_candy,
						sum(if(blndok = 8 and status = 'herbal', trans, 0)) as t8_herbal,
						sum(if(blndok = 9, trans, 0)) as t9,
						sum(if(blndok = 9 and status = 'candy', trans, 0)) as t9_candy,
						sum(if(blndok = 9 and status = 'herbal', trans, 0)) as t9_herbal,
						sum(if(blndok = 10, trans, 0)) as t10,
						sum(if(blndok = 10 and status = 'candy', trans, 0)) as t10_candy,
						sum(if(blndok = 10 and status = 'herbal', trans, 0)) as t10_herbal,
						sum(if(blndok = 11, trans, 0)) as t11,
						sum(if(blndok = 11 and status = 'candy', trans, 0)) as t11_candy,
						sum(if(blndok = 11 and status = 'herbal', trans, 0)) as t11_herbal,
						sum(if(blndok = 12, trans, 0)) as t12,
						sum(if(blndok = 12 and status = 'candy', trans, 0)) as t12_candy,
						sum(if(blndok = 12 and status = 'herbal', trans, 0)) as t12_herbal,kode
				from
				(
					 select nocab,kode_comp,blndok,sum(tot1) as unit, 
				 			count(distinct(concat(kode_comp,kode_lang))) as trans, 
					 		concat(kode_comp,nocab) as kode,'candy' as status
					 from 	data".$year.".fi
					 where 	nodokjdi <> 'XXXXXX' and 
					 		(kodeprod like '60%' or kodeprod like '01%' or kodeprod like '50%' or kodeprod like '70%') and kodeprod not in (SELECT kodeprod from mpm.tabprod where supp ='001' and permen <> 1) and kodeprod not in (select kodeprod from mpm.tabprod_unilever)
					 group by kode,blndok
					 union all
					 select nocab,kode_comp,blndok,sum(tot1) as unit,0 as trans, 	
				 			concat(kode_comp,nocab) as kode,'candy' as status
					 from 	data".$year.".ri
					 where 	nodokjdi <> 'XXXXXX' and 
					 		(kodeprod like '60%' or kodeprod like '01%' or kodeprod like '50%' or kodeprod like '70%') and kodeprod not in (SELECT kodeprod from mpm.tabprod where supp ='001' and permen <> 1) and kodeprod not in (select kodeprod from mpm.tabprod_unilever)
					 group by kode,blndok
					 union all
					 select nocab,kode_comp,blndok,sum(tot1) as unit,
					 		count(distinct(concat(kode_comp,kode_lang))) as trans, 
				 			concat(kode_comp, nocab) as kode,'herbal' as status
					 from 	data".$year.".fi
					 where 	nodokjdi <> 'XXXXXX' and 
					 		(kodeprod like '60%' or kodeprod like '01%' or kodeprod like '50%' or kodeprod like '70%') and kodeprod not in (SELECT kodeprod from mpm.tabprod where supp ='001' and permen = 1) and kodeprod not in (select kodeprod from mpm.tabprod_unilever)
					 group by kode,blndok
					 union all
					 select nocab,kode_comp,blndok,sum(tot1) as unit,0 as trans, 	
					 		concat(kode_comp, nocab) as kode,'herbal' as status
					 from 	data".$year.".ri
					 where 	nodokjdi <> 'XXXXXX' and 
					 		(kodeprod like '60%' or kodeprod like '01%' or kodeprod like '50%' or kodeprod like '70%') and kodeprod not in (SELECT kodeprod from mpm.tabprod where supp ='001' and permen = 1) and kodeprod not in (select kodeprod from mpm.tabprod_unilever)
					 group by kode,blndok
				) a
				group by kode
			)a GROUP BY kode
		)a GROUP BY kode
	)a LEFT JOIN
	(
		select concat(kode_comp, nocab) as kode,naper,kode_comp, nama_comp, sub,urutan,status,status_cluster
		from mpm.tbl_tabcomp_new
		where status = 1
		GROUP BY concat(kode_comp, nocab)
	)b on a.kode = b.kode
	LEFT JOIN
	(
			select 	max(lastupload) as maxupload,
					substring(filename,3,2) naper  
			from 	mpm.upload 
			group by naper
	)c on a.nocab = c.naper
	";      
			
	        $gabung = "insert into mpm.tbl_temp_omzet_deltomed ".$query;			        
	        $sql = $this->db->query($gabung);

	        //echo "<pre>";
			//print_r($query);
			//print_r($gabung);
			//echo "</pre>";
			
			/* ============================================================== */
			if ($sql == '1') {
				
				$query="
				select 	nocab, kode_comp, a.sub, b.nama, 
						sum(b1_candy),sum(b1_herbal),sum(b1),
						sum(b2_candy),sum(b2_herbal),sum(b2),
						sum(b3_candy),sum(b3_herbal),sum(b3),
						sum(b4_candy),sum(b4_herbal),sum(b4),
						sum(b5_candy),sum(b5_herbal),sum(b5),
						sum(b6_candy),sum(b6_herbal),sum(b6),
						sum(b7_candy),sum(b7_herbal),sum(b7),
						sum(b8_candy),sum(b8_herbal),sum(b8),
						sum(b9_candy),sum(b9_herbal),sum(b9),
						sum(b10_candy),sum(b10_herbal),sum(b10),
						sum(b11_candy),sum(b11_herbal),sum(b11),
						sum(b12_candy),sum(b12_herbal),sum(b12),
						sum(t1_candy),sum(t1_herbal),sum(t1_all),
						sum(t2_candy),sum(t2_herbal),sum(t2_all),
						sum(t3_candy),sum(t3_herbal),sum(t3_all),
						sum(t4_candy),sum(t4_herbal),sum(t4_all),
						sum(t5_candy),sum(t5_herbal),sum(t5_all),
						sum(t6_candy),sum(t6_herbal),sum(t6_all),
						sum(t7_candy),sum(t7_herbal),sum(t7_all),
						sum(t8_candy),sum(t8_herbal),sum(t8_all),
						sum(t9_candy),sum(t9_herbal),sum(t9_all),
						sum(t10_candy),sum(t10_herbal),sum(t10_all),
						sum(t11_candy),sum(t11_herbal),sum(t11_all),
						sum(t12_candy),sum(t12_herbal),sum(t12_all),
						id,sum(total_candy),sum(total_herbal),sum(total_all),
						sum(rata_candy),sum(rata_herbal),sum(rata_all),
						 ".'"'.$supp.'"'.",tgl_created,tahun,b.urutan,b.status,'','$key_jam'
				
				from 		mpm.tbl_temp_omzet_deltomed a inner JOIN
				(
						select 	naper naper2,status, urutan, nama_comp nama, sub
						from 		mpm.tbl_tabcomp_new
						WHERE 	status = '2' and status_cluster <> '1'
				)b on a.sub = b.sub
				where a.kategori_supplier ='$supp' and a.tahun = $year and a.`key` = '$key_jam'
				GROUP BY sub
				ORDER BY a.urutan asc

				";
				$gabung = "insert into mpm.tbl_temp_omzet_deltomed ".$query;			        
		        $sql = $this->db->query($gabung);

		        //echo "<pre>";
		        //print_r($query);
		        //echo "</pre>";


				if ($sql == '1') {
		        	
		        	$query = "

					select 	'', '', '', 'GRAND(TOTAL)',
							b1_candy,b1_herbal,b1,
							b2_candy,b2_herbal,b2,
							b3_candy,b3_herbal,b3,
							b4_candy,b4_herbal,b4,
							b5_candy,b5_herbal,b5,
							b6_candy,b6_herbal,b6,
							b7_candy,b7_herbal,b7,
							b8_candy,b8_herbal,b8,
							b9_candy,b9_herbal,b9,
							b10_candy,b10_herbal,b10,
							b11_candy,b11_herbal,b11,
							b12_candy,b12_herbal,b12,
							t1_candy,t1_herbal,t1,
							t2_candy,t2_herbal,t2,
							t3_candy,t3_herbal,t3,
							t4_candy,t4_herbal,t4,
							t5_candy,t5_herbal,t5,
							t6_candy,t6_herbal,t6,
							t7_candy,t7_herbal,t7,
							t8_candy,t8_herbal,t8,
							t9_candy,t9_herbal,t9,
							t10_candy,t10_herbal,t10,
							t11_candy,t11_herbal,t11,
							t12_candy,t12_herbal,t12,
							id, total_candy, total_herbal, total_all,
							($totalx_candy)/$rata as rata_candy, ($totalx_herbal)/$rata as rata_herbal,($totalx_all)/$rata as rata_all,
						 	".'"'.$supp.'"'.",tgl_created,tahun,'999','3','','$key_jam'
					from
					(
						select 	sum(b1_candy) as b1_candy,sum(b1_herbal) as b1_herbal,sum(b1) as b1,
								sum(b2_candy) as b2_candy,sum(b2_herbal) as b2_herbal,sum(b2) as b2,
								sum(b3_candy) as b3_candy,sum(b3_herbal) as b3_herbal,sum(b3) as b3,
								sum(b4_candy) as b4_candy,sum(b4_herbal) as b4_herbal,sum(b4) as b4,
								sum(b5_candy) as b5_candy,sum(b5_herbal) as b5_herbal,sum(b5) as b5,
								sum(b6_candy) as b6_candy,sum(b6_herbal) as b6_herbal,sum(b6) as b6,
								sum(b7_candy) as b7_candy,sum(b7_herbal) as b7_herbal,sum(b7) as b7,
								sum(b8_candy) as b8_candy,sum(b8_herbal) as b8_herbal,sum(b8) as b8,
								sum(b9_candy) as b9_candy,sum(b9_herbal) as b9_herbal,sum(b9) as b9,
								sum(b10_candy) as b10_candy,sum(b10_herbal) as b10_herbal,sum(b10) as b10,
								sum(b11_candy) as b11_candy,sum(b11_herbal) as b11_herbal,sum(b11) as b11,
								sum(b12_candy) as b12_candy,sum(b12_herbal) as b12_herbal,sum(b12) as b12,
								sum(t1_candy) as t1_candy,sum(t1_herbal) as t1_herbal,sum(t1_all) as t1,
								sum(t2_candy) as t2_candy,sum(t2_herbal) as t2_herbal,sum(t2_all) as t2,
								sum(t3_candy) as t3_candy,sum(t3_herbal) as t3_herbal,sum(t3_all) as t3,
								sum(t4_candy) as t4_candy,sum(t4_herbal) as t4_herbal,sum(t4_all) as t4,
								sum(t5_candy) as t5_candy,sum(t5_herbal) as t5_herbal,sum(t5_all) as t5,
								sum(t6_candy) as t6_candy,sum(t6_herbal) as t6_herbal,sum(t6_all) as t6,
								sum(t7_candy) as t7_candy,sum(t7_herbal) as t7_herbal,sum(t7_all) as t7,
								sum(t8_candy) as t8_candy,sum(t8_herbal) as t8_herbal,sum(t8_all) as t8,
								sum(t9_candy) as t9_candy,sum(t9_herbal) as t9_herbal,sum(t9_all) as t9,
								sum(t10_candy) as t10_candy,sum(t10_herbal) as t10_herbal,sum(t10_all) as t10,
								sum(t11_candy) as t11_candy,sum(t11_herbal) as t11_herbal,sum(t11_all) as t11,
								sum(t12_candy) as t12_candy,sum(t12_herbal) as t12_herbal,sum(t12_all) as t12,
								sum(total_candy) as total_candy, sum(total_herbal) as total_herbal, sum(total_all) as total_all,
						 		".'"'.$supp.'"'.",tgl_created,tahun,'999','3','','$key_jam', id
						from 	mpm.tbl_temp_omzet_deltomed a 
						where 	status = 1 and kategori_supplier ='$supp' and tahun = $year and a.key = '$key_jam'
					)a


					";
					$gabung = "insert into mpm.tbl_temp_omzet_deltomed ".$query;			        
			        $sql = $this->db->query($gabung);

			        //echo "<pre>";
			        //print_r($gabung);
			        //echo "</pre>";

					if ($sql == '1') {
			        	
			        	$query="
							insert into mpm.tbl_temporary_user (userid,menuid,nilai,datetime,supp,tahun) 
	            			values ($id,$menuid,$total,$tgl_created,'$supp$group','$year')
						";
							        
				        $sql = $this->db->query($query);

			        }else{
			        	echo "ada kesalahan <i>Proses insert temp_omzet_grand_total</i>. silahkan ulangi";
			        }

		        }else{
		        	echo "ada kesalahan <i>Proses insert temp_omzet_sub_total</i>. silahkan ulangi";
		        }

			}else{

				echo "ada kesalahan <i>Proses insert temp_omzet_per_sub_branch</i>. silahkan ulangi";
			}

			$gabung = "
				insert into mpm.omzet_new_deltomed
				select 	nocab, sub, kode_comp, nama_comp,
						b1_candy,b1_herbal,b1,
						b2_candy,b2_herbal,b2,
						b3_candy,b3_herbal,b3,
						b4_candy,b4_herbal,b4,
						b5_candy,b5_herbal,b5,
						b6_candy,b6_herbal,b6,
						b7_candy,b7_herbal,b7,
						b8_candy,b8_herbal,b8,
						b9_candy,b9_herbal,b9,
						b10_candy,b10_herbal,b10,
						b11_candy,b11_herbal,b11,
						b12_candy,b12_herbal,b12,
						t1_candy,t1_herbal,t1_all,
						t2_candy,t2_herbal,t2_all,
						t3_candy,t3_herbal,t3_all,
						t4_candy,t4_herbal,t4_all,
						t5_candy,t5_herbal,t5_all,
						t6_candy,t6_herbal,t6_all,
						t7_candy,t7_herbal,t7_all,
						t8_candy,t8_herbal,t8_all,
						t9_candy,t9_herbal,t9_all,
						t10_candy,t10_herbal,t10_all,
						t11_candy,t11_herbal,t11_all,
						t12_candy,t12_herbal,t12_all,
						id,total_candy,total_herbal,total_all,
						rata_candy,rata_herbal,rata_all,
						kategori_supplier, tgl_created, tahun,
						urutan, lastupload, '$key_jam'
				from 	mpm.tbl_temp_omzet_deltomed
				where 	kategori_supplier = ".'"'.$supp.'"'." and tahun = $year and `key` = '$key_jam'
				";			        
	        $sql = $this->db->query($gabung);

	        //echo "<pre>";
	        //print_r($gabung);
	        //echo "</pre>";


			if ($sql == '1') {
	        	
	        	if ($dp == NULL || $dp == '') {
		        	
				$query="
							select * 
							from 		mpm.omzet_new_deltomed
							where 		tahun = $year and
										kategori_supplier = '$supp' and
										`key` = (
											select 	max(`key`)
											from 	mpm.omzet_new_deltomed
											where 	tahun = $year and
													kategori_supplier = '$supp'
							)
							ORDER BY urutan asc

						";
					//echo "<pre>";
					//print_r($query);
					//echo "</pre>";

							        
			        $hasil = $this->db->query($query);
			        if ($hasil->num_rows() > 0) 
					{
						return $hasil->result();
					} else {
						return array();
					}

					/* END PROSES TAMPIL KE WEBSITE */

	        	} else {
		     
					$query="
							select * 
							from 		mpm.omzet_new_deltomed
							where 		tahun = $year and
										kategori_supplier = '$supp' and
										naper in ($daftar_dp) and
										`key` = (
											select 	max(`key`)
											from 	mpm.omzet_new_deltomed
											where 	tahun = $year and
													kategori_supplier = '$supp'
							)
							ORDER BY urutan asc
						";
							        
			        $hasil = $this->db->query($query);
			        if ($hasil->num_rows() > 0) 
					{
						return $hasil->result();
					} else {
						return array();
					}
					/* END PROSES TAMPIL KE WEBSITE */
	        	}

	        }else{
	        	echo "ada kesalahan <i>Proses insert omzet_new</i>. silahkan ulangi";
	        }
									
        }
	    
	}


	public function omzet_all_dp_group_herbal($dataSegment)
	{

		/* ---------DEFINISI VARIABEL----------------- */		    
	    $tgl_created='"'.date('Y-m-d H:i:s').'"';
	    $jam=date('ymdHis');
	    $key_jam = $jam;
	    $id=$this->session->userdata('id');
		$year = $dataSegment['year'];
		$group = $dataSegment['group'];	
		$supp = $dataSegment['supp'];
		$uri1 = $dataSegment['menu_uri1'];
		$uri2 = $dataSegment['menu_uri2'];
		$uri3 = $dataSegment['menu_uri3'];
		$menuid = $dataSegment['menuid'];
	
	/* ---------Hak Lihat DP----------------- */		    
		$dp = $dataSegment['query_dp'];        
        if ($dp == NULL || $dp == '') {	$daftar_dp = ''; } else { $daftar_dp = $dp; }

    /* ---------Cari Total FI----------------- */
    	$this->db->select('count(*) as rows');
        $query = $this->db->get("data$year.fi");
        foreach($query->result() as $r)
        {
           $total = $r->rows;
        }	
    
    /* ---------Cari histori di temporary user----------------- */
	    $sql="
	    	select 	1 
	    	from 	mpm.tbl_temporary_user 
	    	where   menuid = $menuid and
	    			supp = '$supp$group' and
	    			tahun = '$year' and 
	    			nilai = $total
	    			";
	    $query = $this->db->query($sql);
        //echo "<pre>";
        //print_r($sql);
        //echo "</pre>";
        if($query->num_rows()>0) //kalau histori ditemukan
        {        	
			if ($dp == NULL || $dp == '') // kalau boleh lihat semua DP
			{	
				$query="
					select 	*
					from 		mpm.omzet_new_herbal
					where 		tahun = $year and
								kategori_supplier = '$supp' and
								`key` = (
									select 	max(`key`)
									from 	mpm.omzet_new_herbal
									where 	tahun = $year and
											kategori_supplier = '$supp'
									)
					ORDER BY urutan asc
				";
				    
		        $hasil = $this->db->query($query);
		        if ($hasil->num_rows() > 0) 
				{ return $hasil->result(); } else { return array(); }

	        } else {
		        	
					$query="
							select 	*
							from 		mpm.omzet_new_herbal
							where 		tahun = $year and
										kategori_supplier = '$supp' and
										naper in ($daftar_dp) and
										`key` = (
											select 	max(`key`)
											from 	mpm.omzet_new_herbal
											where 	tahun = $year and
													kategori_supplier = '$supp'
										)
							ORDER BY urutan asc
						"; 
			        $hasil = $this->db->query($query);
		        if ($hasil->num_rows() > 0) 
				{ return $hasil->result(); } else { return array(); }

					/* END PROSES TAMPIL KE WEBSITE */
	        }				
        }else{ //kalau histori tidak ditemukan

    	/* === Proses pendefinisian bulan, pembagi untuk menentukan total dan rata2 === */				
			$bulan = date("m");
			$tahunsekarang = date("Y");
				if ($year == $tahunsekarang) //jika memilih tahun berjalan
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
						$totalx = 'b1+b2+b3+b4+b5+b6+b7+b8+b9+b10+b11+b12';
						$pembagi = 'xb1+xb2+xb3+xb4+xb5+xb6+xb7+xb8+xb9+xb10+xb11+xb12';
						$rata = 12;
				}
				/* ========================================== */

				/* == PROSES DELETE MPM.OMZET_NEW ===== */
				$query = "	
						delete from mpm.omzet_new_herbal
						where kategori_supplier = ".'"'.$supp.'"'." and tahun = $year and id = $id
							";
				//echo "<pre>";
				//print_r($query);
				//echo "</pre>";
				$sql = $this->db->query($query);

				/* == PROSES DELETE mpm.tbl_temp_omzet = */
				$query = "	
						delete from mpm.tbl_temp_omzet_herbal
						where kategori_supplier = ".'"'.$supp.'"'." and tahun = $year and and id = $id
							";
				$sql = $this->db->query($query);

				/* ========== PROSES INSERT KE mpm.tbl_temp_omzet =================== */
			
$query = "	   
	select  a.nocab, a.kode_comp, b.sub, b.nama_comp,
			t1,b1,t2,b2,t3,b3,t4,b4,t5,b5,
			t6,b6,t7,b7,t8,b8,t9,b9,t10,b10,
			t11,b11,t12,b12,".$id.",total,totalx/jmlbulan rata,
			".'"'.$supp.'"'.",".$tgl_created.",".$year.", urutan, status, maxupload, '$key_jam'
	FROM
	(
		select 	nocab,kode_comp,kode,
				b1, b2, b3, b4, b5, b6, b7, b8, b9, b10, b11, b12,
				t1, t2, t3, t4,	t5, t6, t7, t8,	t9, t10, t11, t12, ".$id.",
				total, totalx, format(sum($pembagi),0, 1) jmlbulan
		FROM
		(
			select  nocab,kode_comp, 
					b1, b2, b3, b4, b5, b6, b7, b8, b9, b10, b11, b12,
					".$id.",
					b1+b2+b3+b4+b5+b6+b7+b8+b9+b10+b11+b12 as total,
					$totalx as totalx,
					IF(b1 = 0, 0, 1) xb1, 
					IF(b2 = 0, 0, 1) xb2, 
					IF(b3 = 0, 0, 1) xb3, 
					IF(b4 = 0, 0, 1) xb4, 
					IF(b5 = 0, 0, 1) xb5, 
					IF(b6 = 0, 0, 1) xb6, 
					IF(b7 = 0, 0, 1) xb7, 
					IF(b8 = 0, 0, 1) xb8, 
					IF(b9 = 0, 0, 1) xb9, 
					IF(b10 = 0, 0, 1) xb10,
					IF(b11 = 0, 0, 1) xb11, 
					IF(b12 = 0, 0, 1) xb12, 
					sum(t1) as t1, 
					sum(t2) as t2,  
					sum(t3) as t3, 
					sum(t4) as t4,  
					sum(t5) as t5,  
					sum(t6) as t6,  
					sum(t7) as t7, 
					sum(t8) as t8,  
					sum(t9) as t9, 
					sum(t10) as t10, 
					sum(t11) as t11, 
					sum(t12) as t12,
					kode
			from
			(				
				select 	nocab, kode_comp, kode,
						sum(if(blndok = 1 and grup = 'G0101', unit, 0)) as b1,
						count(DISTINCT(if(blndok = 1 and grup = 'G0101', outlet,0)))-1 as t1,
						sum(if(blndok = 2 and grup = 'G0101', unit, 0)) as b2,
						count(DISTINCT(if(blndok = 2 and grup = 'G0101', outlet,0)))-1 as t2,
						sum(if(blndok = 3 and grup = 'G0101', unit, 0)) as b3,
						count(DISTINCT(if(blndok = 3 and grup = 'G0101', outlet,0)))-1 as t3,
						sum(if(blndok = 4 and grup = 'G0101', unit, 0)) as b4,
						count(DISTINCT(if(blndok = 4 and grup = 'G0101', outlet,0)))-1 as t4,
						sum(if(blndok = 5 and grup = 'G0101', unit, 0)) as b5,
						count(DISTINCT(if(blndok = 5 and grup = 'G0101', outlet,0)))-1 as t5,
						sum(if(blndok = 6 and grup = 'G0101', unit, 0)) as b6,
						count(DISTINCT(if(blndok = 6 and grup = 'G0101', outlet,0)))-1 as t6,
						sum(if(blndok = 7 and grup = 'G0101', unit, 0)) as b7,
						count(DISTINCT(if(blndok = 7 and grup = 'G0101', outlet,0)))-1 as t7,
						sum(if(blndok = 8 and grup = 'G0101', unit, 0)) as b8,
						count(DISTINCT(if(blndok = 8 and grup = 'G0101', outlet,0)))-1 as t8,
						sum(if(blndok = 9 and grup = 'G0101', unit, 0)) as b9,
						count(DISTINCT(if(blndok = 9 and grup = 'G0101', outlet,0)))-1 as t9,
						sum(if(blndok = 10 and grup = 'G0101', unit, 0)) as b10,
						count(DISTINCT(if(blndok = 10 and grup = 'G0101', outlet,0)))-1 as t10,
						sum(if(blndok = 11 and grup = 'G0101', unit, 0)) as b11,
						count(DISTINCT(if(blndok = 11 and grup = 'G0101', outlet,0)))-1 as t11,
						sum(if(blndok = 12 and grup = 'G0101', unit, 0)) as b12,
						count(DISTINCT(if(blndok = 12 and grup = 'G0101', outlet,0)))-1 as t12
				FROM
				(
					select 	nocab, kode_comp, kode, unit, a.kodeprod, grup, outlet, blndok 
					FROM
					(
							select 	nocab,kode_comp,blndok,
									tot1 as unit, kodeprod, 
									concat(kode_comp,nocab) as kode, concat(kode_comp,kode_lang) as outlet
							from 	data".$year.".fi
							where 	nodokjdi <> 'XXXXXX'
									and (kodeprod like '60%' or kodeprod like '01%' or kodeprod like '50%' or kodeprod like '70%') and kodeprod not in (select kodeprod from mpm.tabprod_unilever)

							union ALL
							select 	nocab,kode_comp,blndok,
									tot1 as unit, kodeprod, 
									concat(kode_comp,nocab) as kode, 0
							from 	data".$year.".ri
							where 	nodokjdi <> 'XXXXXX' 
									and (kodeprod like '60%' or kodeprod like '01%' or kodeprod like '50%' or kodeprod like '70%') and kodeprod not in (select kodeprod from mpm.tabprod_unilever)
					)a left JOIN 
					(
						select kodeprod, grup 
						from mpm.tabprod
						where supp ='001' and report = '1'
					)b on a.kodeprod = b.kodeprod
				)a GROUP BY kode					 
			)a GROUP BY kode
		)a GROUP BY kode
	)a LEFT JOIN
	(
		select concat(kode_comp, nocab) as kode,naper,kode_comp, nama_comp, sub,urutan,status,status_cluster
		from mpm.tbl_tabcomp_new
		where status = 1
		GROUP BY concat(kode_comp, nocab)
	)b on a.kode = b.kode
	LEFT JOIN
	(
			select 	max(lastupload) as maxupload,
					substring(filename,3,2) naper  
			from 	mpm.upload 
			group by naper
	)c on a.nocab = c.naper
	";      
			
	        $gabung = "insert into mpm.tbl_temp_omzet_herbal ".$query;			        
	        $sql = $this->db->query($gabung);
	        
	        echo "<pre>";
			print_r($query);			
			echo "</pre>";
			
			/* ============================================================== */
			if ($sql == '1') {
				
				$query="
				select 	nocab, kode_comp, a.sub, b.nama, 
						sum(t1),sum(b1),sum(t2),sum(b2),sum(t3),sum(b3),sum(t4),sum(b4),sum(t5),sum(b5),
						sum(t6),sum(b6),sum(t7),sum(b7),sum(t8),sum(b8),sum(t9),sum(b9),sum(t10),sum(b10),sum(t11),sum(b11),
						sum(t12),sum(b12),id,sum(total),sum(rata), ".'"'.$supp.'"'.",tgl_created,tahun,b.urutan,b.status,'','$key_jam'
				
				from 	mpm.tbl_temp_omzet_herbal a inner JOIN
				(
						select 	naper naper2,status, urutan, nama_comp nama, sub
						from 		mpm.tbl_tabcomp_new
						WHERE 	status = '2' and status_cluster <> '1'
				)b on a.sub = b.sub
				where a.kategori_supplier ='$supp' and a.tahun = $year and a.`key` = '$key_jam'
				GROUP BY sub
				ORDER BY a.urutan asc

				";
				$gabung = "insert into mpm.tbl_temp_omzet_herbal ".$query;			        
		        $sql = $this->db->query($gabung);

		        //echo "<pre>";
		        //print_r($query);
		        //echo "</pre>";


				if ($sql == '1') {
		        	
					$query = "
					select 	'', '', '', 'GRAND(TOTAL)',
							t1,b1,t2,b2,t3,b3,t4,b4,t5,b5,t6,b6,
							t7,b7,t8,b8,t9,b9,t10,b10,t11,b11,t12,b12,id,																
							total, ($totalx)/$rata as rata, 
						 	".'"'.$supp.'"'.",tgl_created,tahun,'999','3','','$key_jam'
					from
					(
						select 	sum(b1) as b1,sum(b2) as b2,sum(b3) as b3,sum(b4) as b4,
								sum(b5) as b5,sum(b6) as b6,sum(b7) as b7,sum(b8) as b8,
								sum(b9) as b9,sum(b10) as b10,sum(b11) as b11,
								sum(b12) as b12,
								sum(t1) as t1,sum(t2) as t2,sum(t3) as t3,sum(t4) as t4,
								sum(t5) as t5,sum(t6) as t6,sum(t7) as t7,sum(t8) as t8,
								sum(t9) as t9,sum(t10) as t10,sum(t11) as t11,
								sum(t12) as t12,sum(total) as total, 
						 		".'"'.$supp.'"'.",tgl_created,tahun,'999','3','','$key_jam', id
						from 	mpm.tbl_temp_omzet_herbal a 
						where 	status = 1 and kategori_supplier ='$supp' and tahun = $year and a.key = '$key_jam'
					)a
					";

					$gabung = "insert into mpm.tbl_temp_omzet_herbal ".$query;			        
			        $sql = $this->db->query($gabung);
			        
			        //echo "<pre>";
			        //print_r($gabung);
			        //echo "</pre>";
					
					if ($sql == '1') {
			        	
			        	$query="
							insert into mpm.tbl_temporary_user (userid,menuid,nilai,datetime,supp,tahun) 
	            			values ($id,$menuid,$total,$tgl_created,'$supp$group','$year')
						";
							        
				        $sql = $this->db->query($query);

			        }else{
			        	echo "ada kesalahan <i>Proses insert temp_omzet_grand_total</i>. silahkan ulangi";
			        }

		        }else{
		        	echo "ada kesalahan <i>Proses insert temp_omzet_sub_total</i>. silahkan ulangi";
		        }

			}else{

				echo "ada kesalahan <i>Proses insert temp_omzet_per_sub_branch</i>. silahkan ulangi";
			}

			$gabung = "
				insert into mpm.omzet_new_herbal
				select 	nocab, sub, kode_comp, nama_comp, 
						t1,b1,t2,b2,t3,b3,t4,b4,t5,b5,t6,b6,t7,b7,t8,b8,t9,b9,t10,b10,t11,b11,t12,b12,id,total,rata,kategori_supplier,tgl_created,
						tahun, urutan, lastupload, `key`
				from 	mpm.tbl_temp_omzet_herbal
				where 	kategori_supplier = ".'"'.$supp.'"'." and tahun = $year and `key` = '$key_jam'
				";			        
	        $sql = $this->db->query($gabung);

	        //echo "<pre>";
	        //print_r($gabung);
	        //echo "</pre>";


			if ($sql == '1') {
	        	
	        	if ($dp == NULL || $dp == '') {
		        	
				$query="
							select 	* 
							from 	mpm.omzet_new_herbal
							where 	tahun = $year and
									kategori_supplier = '$supp' and
									`key` = (
											select 	max(`key`)
											from 	mpm.omzet_new_herbal
											where 	tahun = $year and
													kategori_supplier = '$supp'
							)
							ORDER BY urutan asc

						";
					/*
					echo "<pre>";
					print_r($query);
					echo "</pre>";
					*/
							        
			        $hasil = $this->db->query($query);
			        if ($hasil->num_rows() > 0) 
					{
						return $hasil->result();
					} else {
						return array();
					}

					/* END PROSES TAMPIL KE WEBSITE */

	        	} else {
		     
					$query="
							select 	*
							from 		mpm.omzet_new_herbal
							where 		tahun = $year and
										kategori_supplier = '$supp' and
										naper in ($daftar_dp) and
										`key` = (
											select 	max(`key`)
											from 	mpm.omzet_new_herbal
											where 	tahun = $year and
													kategori_supplier = '$supp'
							)
							ORDER BY urutan asc
						";
							        
			        $hasil = $this->db->query($query);
			        if ($hasil->num_rows() > 0) 
					{
						return $hasil->result();
					} else {
						return array();
					}
					/* END PROSES TAMPIL KE WEBSITE */
	        	}

	        }else{
	        	echo "ada kesalahan <i>Proses insert omzet_new</i>. silahkan ulangi";
	        }
									
        }
	    
	}


	public function omzet_all_dp_group_candy($dataSegment){

	/* ---------DEFINISI VARIABEL----------------- */		    
	    $tgl_created='"'.date('Y-m-d H:i:s').'"';
	    $jam=date('ymdHis');
	    $key_jam = $jam;
	    $id=$this->session->userdata('id');
		$year = $dataSegment['year'];
		$group = $dataSegment['group'];	
		$supp = $dataSegment['supp'];
		$uri1 = $dataSegment['menu_uri1'];
		$uri2 = $dataSegment['menu_uri2'];
		$uri3 = $dataSegment['menu_uri3'];
		$menuid = $dataSegment['menuid'];
	
	/* ---------Hak Lihat DP----------------- */		    
		$dp = $dataSegment['query_dp'];        
        if ($dp == NULL || $dp == '') {	$daftar_dp = ''; } else { $daftar_dp = $dp; }

    /* ---------Cari Total FI----------------- */
    	$this->db->select('count(*) as rows');
        $query = $this->db->get("data$year.fi");
        foreach($query->result() as $r)
        {
           $total = $r->rows;
        }	
    
    /* ---------Cari histori di temporary user----------------- */
	    $sql="
	    	select 	1 
	    	from 	mpm.tbl_temporary_user 
	    	where   menuid = $menuid and
	    			supp = '$supp$group' and
	    			tahun = '$year' and 
	    			nilai = $total
	    			";
	    $query = $this->db->query($sql);
        //echo "<pre>";
        //print_r($sql);
        //echo "</pre>";
        if($query->num_rows()>0) //kalau histori ditemukan
        {        	
			if ($dp == NULL || $dp == '') // kalau boleh lihat semua DP
			{	
				$query="
					select 	*
					from 		mpm.omzet_new_candy
					where 		tahun = $year and
								kategori_supplier = '$supp' and
								`key` = (
									select 	max(`key`)
									from 	mpm.omzet_new_candy
									where 	tahun = $year and
											kategori_supplier = '$supp'
									)
					ORDER BY urutan asc
				";
				    
		        $hasil = $this->db->query($query);
		        if ($hasil->num_rows() > 0) 
				{ return $hasil->result(); } else { return array(); }

	        } else {
		        	
					$query="
							select 	*
							from 		mpm.omzet_new_candy
							where 		tahun = $year and
										kategori_supplier = '$supp' and
										naper in ($daftar_dp) and
										`key` = (
											select 	max(`key`)
											from 	mpm.omzet_new_candy
											where 	tahun = $year and
													kategori_supplier = '$supp'
										)
							ORDER BY urutan asc
						"; 
			        $hasil = $this->db->query($query);
		        if ($hasil->num_rows() > 0) 
				{ return $hasil->result(); } else { return array(); }

					/* END PROSES TAMPIL KE WEBSITE */
	        }				
        }else{ //kalau histori tidak ditemukan

    	/* === Proses pendefinisian bulan, pembagi untuk menentukan total dan rata2 === */				
			$bulan = date("m");
			$tahunsekarang = date("Y");
				if ($year == $tahunsekarang) //jika memilih tahun berjalan
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
						$totalx = 'b1+b2+b3+b4+b5+b6+b7+b8+b9+b10+b11+b12';
						$pembagi = 'xb1+xb2+xb3+xb4+xb5+xb6+xb7+xb8+xb9+xb10+xb11+xb12';
						$rata = 12;
				}
				/* ========================================== */

				/* == PROSES DELETE MPM.OMZET_NEW ===== */
				$query = "	
						delete from mpm.omzet_new_candy
						where kategori_supplier = ".'"'.$supp.'"'." and tahun = $year and id = $id
							";
				//echo "<pre>";
				//print_r($query);
				//echo "</pre>";
				$sql = $this->db->query($query);

				/* == PROSES DELETE mpm.tbl_temp_omzet = */
				$query = "	
						delete from mpm.tbl_temp_omzet_candy
						where kategori_supplier = ".'"'.$supp.'"'." and tahun = $year and and id = $id
							";
				$sql = $this->db->query($query);

				/* ========== PROSES INSERT KE mpm.tbl_temp_omzet =================== */
			
$query = "	   
	select  a.nocab, a.kode_comp, b.sub, b.nama_comp,
			t1,b1,t2,b2,t3,b3,t4,b4,t5,b5,
			t6,b6,t7,b7,t8,b8,t9,b9,t10,b10,
			t11,b11,t12,b12,".$id.",total,totalx/jmlbulan rata,
			".'"'.$supp.'"'.",".$tgl_created.",".$year.", urutan, status, maxupload, '$key_jam'
	FROM
	(
		select 	nocab,kode_comp,kode,
				b1, b2, b3, b4, b5, b6, b7, b8, b9, b10, b11, b12,
				t1, t2, t3, t4,	t5, t6, t7, t8,	t9, t10, t11, t12, ".$id.",
				total, totalx, format(sum($pembagi),0, 1) jmlbulan
		FROM
		(
			select  nocab,kode_comp, 
					b1, b2, b3, b4, b5, b6, b7, b8, b9, b10, b11, b12,
					".$id.",
					b1+b2+b3+b4+b5+b6+b7+b8+b9+b10+b11+b12 as total,
					$totalx as totalx,
					IF(b1 = 0, 0, 1) xb1, 
					IF(b2 = 0, 0, 1) xb2, 
					IF(b3 = 0, 0, 1) xb3, 
					IF(b4 = 0, 0, 1) xb4, 
					IF(b5 = 0, 0, 1) xb5, 
					IF(b6 = 0, 0, 1) xb6, 
					IF(b7 = 0, 0, 1) xb7, 
					IF(b8 = 0, 0, 1) xb8, 
					IF(b9 = 0, 0, 1) xb9, 
					IF(b10 = 0, 0, 1) xb10,
					IF(b11 = 0, 0, 1) xb11, 
					IF(b12 = 0, 0, 1) xb12, 
					sum(t1) as t1, 
					sum(t2) as t2,  
					sum(t3) as t3, 
					sum(t4) as t4,  
					sum(t5) as t5,  
					sum(t6) as t6,  
					sum(t7) as t7, 
					sum(t8) as t8,  
					sum(t9) as t9, 
					sum(t10) as t10, 
					sum(t11) as t11, 
					sum(t12) as t12,
					kode
			from
			(				
				select 	nocab, kode_comp, kode,
						sum(if(blndok = 1 and grup = 'G0102', unit, 0)) as b1,
						count(DISTINCT(if(blndok = 1 and grup = 'G0102', outlet,0)))-1 as t1,
						sum(if(blndok = 2 and grup = 'G0102', unit, 0)) as b2,
						count(DISTINCT(if(blndok = 2 and grup = 'G0102', outlet,0)))-1 as t2,
						sum(if(blndok = 3 and grup = 'G0102', unit, 0)) as b3,
						count(DISTINCT(if(blndok = 3 and grup = 'G0102', outlet,0)))-1 as t3,
						sum(if(blndok = 4 and grup = 'G0102', unit, 0)) as b4,
						count(DISTINCT(if(blndok = 4 and grup = 'G0102', outlet,0)))-1 as t4,
						sum(if(blndok = 5 and grup = 'G0102', unit, 0)) as b5,
						count(DISTINCT(if(blndok = 5 and grup = 'G0102', outlet,0)))-1 as t5,
						sum(if(blndok = 6 and grup = 'G0102', unit, 0)) as b6,
						count(DISTINCT(if(blndok = 6 and grup = 'G0102', outlet,0)))-1 as t6,
						sum(if(blndok = 7 and grup = 'G0102', unit, 0)) as b7,
						count(DISTINCT(if(blndok = 7 and grup = 'G0102', outlet,0)))-1 as t7,
						sum(if(blndok = 8 and grup = 'G0102', unit, 0)) as b8,
						count(DISTINCT(if(blndok = 8 and grup = 'G0102', outlet,0)))-1 as t8,
						sum(if(blndok = 9 and grup = 'G0102', unit, 0)) as b9,
						count(DISTINCT(if(blndok = 9 and grup = 'G0102', outlet,0)))-1 as t9,
						sum(if(blndok = 10 and grup = 'G0102', unit, 0)) as b10,
						count(DISTINCT(if(blndok = 10 and grup = 'G0102', outlet,0)))-1 as t10,
						sum(if(blndok = 11 and grup = 'G0102', unit, 0)) as b11,
						count(DISTINCT(if(blndok = 11 and grup = 'G0102', outlet,0)))-1 as t11,
						sum(if(blndok = 12 and grup = 'G0102', unit, 0)) as b12,
						count(DISTINCT(if(blndok = 12 and grup = 'G0102', outlet,0)))-1 as t12
				FROM
				(
					select 	nocab, kode_comp, kode, unit, a.kodeprod, grup, outlet, blndok 
					FROM
					(
							select 	nocab,kode_comp,blndok,
									tot1 as unit, kodeprod, 
									concat(kode_comp,nocab) as kode, concat(kode_comp,kode_lang) as outlet
							from 	data".$year.".fi
							where 	nodokjdi <> 'XXXXXX'
									and (kodeprod like '60%' or kodeprod like '01%' or kodeprod like '50%' or kodeprod like '70%') and kodeprod not in (select kodeprod from mpm.tabprod_unilever)

							union ALL
							select 	nocab,kode_comp,blndok,
									tot1 as unit, kodeprod, 
									concat(kode_comp,nocab) as kode, 0
							from 	data".$year.".ri
							where 	nodokjdi <> 'XXXXXX' 
									and (kodeprod like '60%' or kodeprod like '01%' or kodeprod like '50%' or kodeprod like '70%') and kodeprod not in (select kodeprod from mpm.tabprod_unilever)
					)a left JOIN 
					(
						select kodeprod, grup 
						from mpm.tabprod
						where supp ='001' and report = '1'
					)b on a.kodeprod = b.kodeprod
				)a GROUP BY kode					 
			)a GROUP BY kode
		)a GROUP BY kode
	)a LEFT JOIN
	(
		select concat(kode_comp, nocab) as kode,naper,kode_comp, nama_comp, sub,urutan,status,status_cluster
		from mpm.tbl_tabcomp_new
		where status = 1
		GROUP BY concat(kode_comp, nocab)
	)b on a.kode = b.kode
	LEFT JOIN
	(
			select 	max(lastupload) as maxupload,
					substring(filename,3,2) naper  
			from 	mpm.upload 
			group by naper
	)c on a.nocab = c.naper
	";      
			
	        $gabung = "insert into mpm.tbl_temp_omzet_candy ".$query;			        
	        $sql = $this->db->query($gabung);
	        
	        echo "<pre>";
			print_r($query);			
			echo "</pre>";
			
			/* ============================================================== */
			if ($sql == '1') {
				
				$query="
				select 	nocab, kode_comp, a.sub, b.nama, 
						sum(t1),sum(b1),sum(t2),sum(b2),sum(t3),sum(b3),sum(t4),sum(b4),sum(t5),sum(b5),
						sum(t6),sum(b6),sum(t7),sum(b7),sum(t8),sum(b8),sum(t9),sum(b9),sum(t10),sum(b10),sum(t11),sum(b11),
						sum(t12),sum(b12),id,sum(total),sum(rata), ".'"'.$supp.'"'.",tgl_created,tahun,b.urutan,b.status,'','$key_jam'
				
				from 	mpm.tbl_temp_omzet_candy a inner JOIN
				(
						select 	naper naper2,status, urutan, nama_comp nama, sub
						from 		mpm.tbl_tabcomp_new
						WHERE 	status = '2' and status_cluster <> '1'
				)b on a.sub = b.sub
				where a.kategori_supplier ='$supp' and a.tahun = $year and a.`key` = '$key_jam'
				GROUP BY sub
				ORDER BY a.urutan asc

				";
				$gabung = "insert into mpm.tbl_temp_omzet_candy ".$query;			        
		        $sql = $this->db->query($gabung);

		        //echo "<pre>";
		        //print_r($query);
		        //echo "</pre>";


				if ($sql == '1') {
		        	
					$query = "
					select 	'', '', '', 'GRAND(TOTAL)',
							t1,b1,t2,b2,t3,b3,t4,b4,t5,b5,t6,b6,
							t7,b7,t8,b8,t9,b9,t10,b10,t11,b11,t12,b12,id,																
							total, ($totalx)/$rata as rata, 
						 	".'"'.$supp.'"'.",tgl_created,tahun,'999','3','','$key_jam'
					from
					(
						select 	sum(b1) as b1,sum(b2) as b2,sum(b3) as b3,sum(b4) as b4,
								sum(b5) as b5,sum(b6) as b6,sum(b7) as b7,sum(b8) as b8,
								sum(b9) as b9,sum(b10) as b10,sum(b11) as b11,
								sum(b12) as b12,
								sum(t1) as t1,sum(t2) as t2,sum(t3) as t3,sum(t4) as t4,
								sum(t5) as t5,sum(t6) as t6,sum(t7) as t7,sum(t8) as t8,
								sum(t9) as t9,sum(t10) as t10,sum(t11) as t11,
								sum(t12) as t12,sum(total) as total, 
						 		".'"'.$supp.'"'.",tgl_created,tahun,'999','3','','$key_jam', id
						from 	mpm.tbl_temp_omzet_candy a 
						where 	status = 1 and kategori_supplier ='$supp' and tahun = $year and a.key = '$key_jam'
					)a
					";

					$gabung = "insert into mpm.tbl_temp_omzet_candy ".$query;			        
			        $sql = $this->db->query($gabung);
			        
			        //echo "<pre>";
			        //print_r($gabung);
			        //echo "</pre>";
					
					if ($sql == '1') {
			        	
			        	$query="
							insert into mpm.tbl_temporary_user (userid,menuid,nilai,datetime,supp,tahun) 
	            			values ($id,$menuid,$total,$tgl_created,'$supp$group','$year')
						";
							        
				        $sql = $this->db->query($query);

			        }else{
			        	echo "ada kesalahan <i>Proses insert temp_omzet_grand_total</i>. silahkan ulangi";
			        }

		        }else{
		        	echo "ada kesalahan <i>Proses insert temp_omzet_sub_total</i>. silahkan ulangi";
		        }

			}else{

				echo "ada kesalahan <i>Proses insert temp_omzet_per_sub_branch</i>. silahkan ulangi";
			}

			$gabung = "
				insert into mpm.omzet_new_candy
				select 	nocab, sub, kode_comp, nama_comp, 
						t1,b1,t2,b2,t3,b3,t4,b4,t5,b5,t6,b6,t7,b7,t8,b8,t9,b9,t10,b10,t11,b11,t12,b12,id,total,rata,kategori_supplier,tgl_created,
						tahun, urutan, lastupload, `key`
				from 	mpm.tbl_temp_omzet_candy
				where 	kategori_supplier = ".'"'.$supp.'"'." and tahun = $year and `key` = '$key_jam'
				";			        
	        $sql = $this->db->query($gabung);

	        //echo "<pre>";
	        //print_r($gabung);
	        //echo "</pre>";


			if ($sql == '1') {
	        	
	        	if ($dp == NULL || $dp == '') {
		        	
				$query="
							select 	* 
							from 	mpm.omzet_new_candy
							where 	tahun = $year and
									kategori_supplier = '$supp' and
									`key` = (
											select 	max(`key`)
											from 	mpm.omzet_new_candy
											where 	tahun = $year and
													kategori_supplier = '$supp'
							)
							ORDER BY urutan asc

						";
					/*
					echo "<pre>";
					print_r($query);
					echo "</pre>";
					*/
							        
			        $hasil = $this->db->query($query);
			        if ($hasil->num_rows() > 0) 
					{
						return $hasil->result();
					} else {
						return array();
					}

					/* END PROSES TAMPIL KE WEBSITE */

	        	} else {
		     
					$query="
							select 	*
							from 		mpm.omzet_new_candy
							where 		tahun = $year and
										kategori_supplier = '$supp' and
										naper in ($daftar_dp) and
										`key` = (
											select 	max(`key`)
											from 	mpm.omzet_new_candy
											where 	tahun = $year and
													kategori_supplier = '$supp'
							)
							ORDER BY urutan asc
						";
							        
			        $hasil = $this->db->query($query);
			        if ($hasil->num_rows() > 0) 
					{
						return $hasil->result();
					} else {
						return array();
					}
					/* END PROSES TAMPIL KE WEBSITE */
	        	}

	        }else{
	        	echo "ada kesalahan <i>Proses insert omzet_new</i>. silahkan ulangi";
	        }
									
        }
	    
	}


	public function omzet_all_dp_group_us($dataSegment){

	/* ---------DEFINISI VARIABEL----------------- */		    
	    $tgl_created='"'.date('Y-m-d H:i:s').'"';
	    $jam=date('ymdHis');
	    $key_jam = $jam;
	    $id=$this->session->userdata('id');
		$year = $dataSegment['year'];
		$group = $dataSegment['group'];	
		$supp = $dataSegment['supp'];
		$uri1 = $dataSegment['menu_uri1'];
		$uri2 = $dataSegment['menu_uri2'];
		$uri3 = $dataSegment['menu_uri3'];
		$menuid = $dataSegment['menuid'];
	
	/* ---------Hak Lihat DP----------------- */		    
		$dp = $dataSegment['query_dp'];        
        if ($dp == NULL || $dp == '') {	$daftar_dp = ''; } else { $daftar_dp = $dp; }

    /* ---------Cari Total FI----------------- */
    	$this->db->select('count(*) as rows');
        $query = $this->db->get("data$year.fi");
        foreach($query->result() as $r)
        {
           $total = $r->rows;
        }	
    
    /* ---------Cari histori di temporary user----------------- */
	    $sql="
	    	select 	1 
	    	from 	mpm.tbl_temporary_user 
	    	where   menuid = $menuid and
	    			supp = '$supp$group' and
	    			tahun = '$year' and 
	    			nilai = $total
	    			";
	    $query = $this->db->query($sql);
        if($query->num_rows()>0) //kalau histori ditemukan
        {        	
			if ($dp == NULL || $dp == '') // kalau boleh lihat semua DP
			{	
				$query="
					select 	*
					from 		mpm.omzet_new_us
					where 		tahun = $year and
								kategori_supplier = '$supp' and
								`key` = (
									select 	max(`key`)
									from 	mpm.omzet_new_us
									where 	tahun = $year and
											kategori_supplier = '$supp'
									)
					ORDER BY urutan asc
				";

				echo "<pre>";
				print_r($query);
				echo "</pre>";
				    
		        $hasil = $this->db->query($query);
		        if ($hasil->num_rows() > 0) 
				{ return $hasil->result(); } else { return array(); }

	        } else {
		        	
					$query="
							select 	*
							from 		mpm.omzet_new_us
							where 		tahun = $year and
										kategori_supplier = '$supp' and
										naper in ($daftar_dp) and
										`key` = (
											select 	max(`key`)
											from 	mpm.omzet_new_us
											where 	tahun = $year and
													kategori_supplier = '$supp'
										)
							ORDER BY urutan asc
						";

						echo "<pre>";
						print_r($query);
						echo "</pre>";

			        $hasil = $this->db->query($query);
		        if ($hasil->num_rows() > 0) 
				{ return $hasil->result(); } else { return array(); }

					/* END PROSES TAMPIL KE WEBSITE */
	        }				
        }else{ //kalau histori tidak ditemukan

    	/* === Proses pendefinisian bulan, pembagi untuk menentukan total dan rata2 === */				
			$bulan = date("m");
			$tahunsekarang = date("Y");
				if ($year == $tahunsekarang) //jika memilih tahun berjalan
				{				
					if ($bulan == '01')
					{
						$totalx_all = 'b2_all+b3_all+b4_all+b5_all+b6_all+b7_all+b8_all+b9_all+b10_all+b11_all+b12_all';
						$totalx_fr = 'b2_fr+b3_fr+b4_fr+b5_fr+b6_fr+b7_fr+b8_fr+b9_fr+b10_fr+b11_fr+b12_fr';
						$totalx_ho = 'b2_ho+b3_ho+b4_ho+b5_ho+b6_ho+b7_ho+b8_ho+b9_ho+b10_ho+b11_ho+b12_ho';
						$totalx_ma = 'b2_ma+b3_ma+b4_ma+b5_ma+b6_ma+b7_ma+b8_ma+b9_ma+b10_ma+b11_ma+b12_ma';
						$totalx_my = 'b2_my+b3_my+b4_my+b5_my+b6_my+b7_my+b8_my+b9_my+b10_my+b11_my+b12_my';
						$totalx_tr = 'b2_tr+b3_tr+b4_tr+b5_tr+b6_tr+b7_tr+b8_tr+b9_tr+b10_tr+b11_tr+b12_tr';
						$totalx_ot = 'b2_ot+b3_ot+b4_ot+b5_ot+b6_ot+b7_ot+b8_ot+b9_ot+b10_ot+b11_ot+b12_ot';
						
						$pembagi_all = 'xb2_all+xb3_all+xb4_all+xb5_all+xb6_all+xb7_all+xb8_all+xb9_all+xb10_all+xb11_all+xb12_all';						
						$pembagi_fr = 'xb2_fr+xb3_fr+xb4_fr+xb5_fr+xb6_fr+xb7_fr+xb8_fr+xb9_fr+xb10_fr+xb11_fr+xb12_fr';
						$pembagi_ho = 'xb2_ho+xb3_ho+xb4_ho+xb5_ho+xb6_ho+xb7_ho+xb8_ho+xb9_ho+xb10_ho+xb11_ho+xb12_ho';
						$pembagi_ma = 'xb2_ma+xb3_ma+xb4_ma+xb5_ma+xb6_ma+xb7_ma+xb8_ma+xb9_ma+xb10_ma+xb11_ma+xb12_ma';
						$pembagi_my = 'xb2_my+xb3_my+xb4_my+xb5_my+xb6_my+xb7_my+xb8_my+xb9_my+xb10_my+xb11_my+xb12_my';
						$pembagi_tr = 'xb2_tr+xb3_tr+xb4_tr+xb5_tr+xb6_tr+xb7_tr+xb8_tr+xb9_tr+xb10_tr+xb11_tr+xb12_tr';
						$pembagi_ot = 'xb2_ot+xb3_ot+xb4_ot+xb5_ot+xb6_ot+xb7_ot+xb8_ot+xb9_ot+xb10_ot+xb11_ot+xb12_ot';
						$rata = 0;
					}elseif ($bulan == '02')
					{
						$totalx_all = 'b1_all+b3_all+b4_all+b5_all+b6_all+b7_all+b8_all+b9_all+b10_all+b11_all+b12_all';
						$totalx_fr = 'b1_fr+b3_fr+b4_fr+b5_fr+b6_fr+b7_fr+b8_fr+b9_fr+b10_fr+b11_fr+b12_fr';
						$totalx_ho = 'b1_ho+b3_ho+b4_ho+b5_ho+b6_ho+b7_ho+b8_ho+b9_ho+b10_ho+b11_ho+b12_ho';
						$totalx_ma = 'b1_ma+b3_ma+b4_ma+b5_ma+b6_ma+b7_ma+b8_ma+b9_ma+b10_ma+b11_ma+b12_ma';
						$totalx_my = 'b1_my+b3_my+b4_my+b5_my+b6_my+b7_my+b8_my+b9_my+b10_my+b11_my+b12_my';
						$totalx_tr = 'b1_tr+b3_tr+b4_tr+b5_tr+b6_tr+b7_tr+b8_tr+b9_tr+b10_tr+b11_tr+b12_tr';
						$totalx_ot = 'b1_ot+b3_ot+b4_ot+b5_ot+b6_ot+b7_ot+b8_ot+b9_ot+b10_ot+b11_ot+b12_ot';
						
						$pembagi_all = 'xb1_all+xb3_all+xb4_all+xb5_all+xb6_all+xb7_all+xb8_all+xb9_all+xb10_all+xb11_all+xb12_all';
						$pembagi_fr = 'xb1_fr+xb3_fr+xb4_fr+xb5_fr+xb6_fr+xb7_fr+xb8_fr+xb9_fr+xb10_fr+xb11_fr+xb12_fr';
						$pembagi_ho = 'xb1_ho+xb3_ho+xb4_ho+xb5_ho+xb6_ho+xb7_ho+xb8_ho+xb9_ho+xb10_ho+xb11_ho+xb12_ho';
						$pembagi_ma = 'xb1_ma+xb3_ma+xb4_ma+xb5_ma+xb6_ma+xb7_ma+xb8_ma+xb9_ma+xb10_ma+xb11_ma+xb12_ma';
						$pembagi_my = 'xb1_my+xb3_my+xb4_my+xb5_my+xb6_my+xb7_my+xb8_my+xb9_my+xb10_my+xb11_my+xb12_my';
						$pembagi_tr = 'xb1_tr+xb3_tr+xb4_tr+xb5_tr+xb6_tr+xb7_tr+xb8_tr+xb9_tr+xb10_tr+xb11_tr+xb12_tr';
						$pembagi_ot = 'xb1_ot+xb3_ot+xb4_ot+xb5_ot+xb6_ot+xb7_ot+xb8_ot+xb9_ot+xb10_ot+xb11_ot+xb12_ot';
						
						$rata = 1;
					}elseif ($bulan == '03')
					{
						$totalx_all = 'b1_all+b2_all+b4_all+b5_all+b6_all+b7_all+b8_all+b9_all+b10_all+b11_all+b12_all';
						$totalx_fr = 'b1_fr+b2_fr+b4_fr+b5_fr+b6_fr+b7_fr+b8_fr+b9_fr+b10_fr+b11_fr+b12_fr';
						$totalx_ho = 'b1_ho+b2_ho+b4_ho+b5_ho+b6_ho+b7_ho+b8_ho+b9_ho+b10_ho+b11_ho+b12_ho';
						$totalx_ma = 'b1_ma+b2_ma+b4_ma+b5_ma+b6_ma+b7_ma+b8_ma+b9_ma+b10_ma+b11_ma+b12_ma';
						$totalx_my = 'b1_my+b2_my+b4_my+b5_my+b6_my+b7_my+b8_my+b9_my+b10_my+b11_my+b12_my';
						$totalx_tr = 'b1_tr+b2_tr+b4_tr+b5_tr+b6_tr+b7_tr+b8_tr+b9_tr+b10_tr+b11_tr+b12_tr';
						$totalx_ot = 'b1_ot+b2_ot+b4_ot+b5_ot+b6_ot+b7_ot+b8_ot+b9_ot+b10_ot+b11_ot+b12_ot';
						
						$pembagi_all = 'xb1_all+xb2_all+xb4_all+xb5_all+xb6_all+xb7_all+xb8_all+xb9_all+xb10_all+xb11_all+xb12_all';
						$pembagi_fr = 'xb1_fr+xb2_fr+xb4_fr+xb5_fr+xb6_fr+xb7_fr+xb8_fr+xb9_fr+xb10_fr+xb11_fr+xb12_fr';
						$pembagi_ho = 'xb1_ho+xb2_ho+xb4_ho+xb5_ho+xb6_ho+xb7_ho+xb8_ho+xb9_ho+xb10_ho+xb11_ho+xb12_ho';
						$pembagi_ma = 'xb1_ma+xb2_ma+xb4_ma+xb5_ma+xb6_ma+xb7_ma+xb8_ma+xb9_ma+xb10_ma+xb11_ma+xb12_ma';
						$pembagi_my = 'xb1_my+xb2_my+xb4_my+xb5_my+xb6_my+xb7_my+xb8_my+xb9_my+xb10_my+xb11_my+xb12_my';
						$pembagi_tr = 'xb1_tr+xb2_tr+xb4_tr+xb5_tr+xb6_tr+xb7_tr+xb8_tr+xb9_tr+xb10_tr+xb11_tr+xb12_tr';
						$pembagi_ot = 'xb1_ot+xb2_ot+xb4_ot+xb5_ot+xb6_ot+xb7_ot+xb8_ot+xb9_ot+xb10_ot+xb11_ot+xb12_ot';
						$rata = 2;
					}elseif ($bulan == '04')
					{
						$totalx_all = 'b1_all+b2_all+b3_all+b5_all+b6_all+b7_all+b8_all+b9_all+b10_all+b11_all+b12_all';
						$totalx_fr = 'b1_fr+b2_fr+b3_fr+b5_fr+b6_fr+b7_fr+b8_fr+b9_fr+b10_fr+b11_fr+b12_fr';
						$totalx_ho = 'b1_ho+b2_ho+b3_ho+b5_ho+b6_ho+b7_ho+b8_ho+b9_ho+b10_ho+b11_ho+b12_ho';
						$totalx_ma = 'b1_ma+b2_ma+b3_ma+b5_ma+b6_ma+b7_ma+b8_ma+b9_ma+b10_ma+b11_ma+b12_ma';
						$totalx_my = 'b1_my+b2_my+b3_my+b5_my+b6_my+b7_my+b8_my+b9_my+b10_my+b11_my+b12_my';
						$totalx_tr = 'b1_tr+b2_tr+b3_tr+b5_tr+b6_tr+b7_tr+b8_tr+b9_tr+b10_tr+b11_tr+b12_tr';
						$totalx_ot = 'b1_ot+b2_ot+b3_ot+b5_ot+b6_ot+b7_ot+b8_ot+b9_ot+b10_ot+b11_ot+b12_ot';
						
						$pembagi_all = 'xb1_all+xb2_all+xb3_all+xb5_all+xb6_all+xb7_all+xb8_all+xb9_all+xb10_all+xb11_all+xb12_all';
						$pembagi_fr = 'xb1_fr+xb2_fr+xb3_fr+xb5_fr+xb6_fr+xb7_fr+xb8_fr+xb9_fr+xb10_fr+xb11_fr+xb12_fr';
						$pembagi_ho = 'xb1_ho+xb2_ho+xb3_ho+xb5_ho+xb6_ho+xb7_ho+xb8_ho+xb9_ho+xb10_ho+xb11_ho+xb12_ho';
						$pembagi_ma = 'xb1_ma+xb2_ma+xb3_ma+xb5_ma+xb6_ma+xb7_ma+xb8_ma+xb9_ma+xb10_ma+xb11_ma+xb12_ma';
						$pembagi_my = 'xb1_my+xb2_my+xb3_my+xb5_my+xb6_my+xb7_my+xb8_my+xb9_my+xb10_my+xb11_my+xb12_my';
						$pembagi_tr = 'xb1_tr+xb2_tr+xb3_tr+xb5_tr+xb6_tr+xb7_tr+xb8_tr+xb9_tr+xb10_tr+xb11_tr+xb12_tr';
						$pembagi_ot = 'xb1_ot+xb2_ot+xb3_ot+xb5_ot+xb6_ot+xb7_ot+xb8_ot+xb9_ot+xb10_ot+xb11_ot+xb12_ot';
						$rata = 3;
					}elseif ($bulan == '05')
					{
						$totalx_all = 'b1_all+b2_all+b3_all+b4_all+b6_all+b7_all+b8_all+b9_all+b10_all+b11_all+b12_all';
						$totalx_fr = 'b1_fr+b2_fr+b3_fr+b4_fr+b6_fr+b7_fr+b8_fr+b9_fr+b10_fr+b11_fr+b12_fr';
						$totalx_ho = 'b1_ho+b2_ho+b3_ho+b4_ho+b6_ho+b7_ho+b8_ho+b9_ho+b10_ho+b11_ho+b12_ho';
						$totalx_ma = 'b1_ma+b2_ma+b3_ma+b4_ma+b6_ma+b7_ma+b8_ma+b9_ma+b10_ma+b11_ma+b12_ma';
						$totalx_my = 'b1_my+b2_my+b3_my+b4_my+b6_my+b7_my+b8_my+b9_my+b10_my+b11_my+b12_my';
						$totalx_tr = 'b1_tr+b2_tr+b3_tr+b4_tr+b6_tr+b7_tr+b8_tr+b9_tr+b10_tr+b11_tr+b12_tr';
						$totalx_ot = 'b1_ot+b2_ot+b3_ot+b4_ot+b6_ot+b7_ot+b8_ot+b9_ot+b10_ot+b11_ot+b12_ot';
						
						$pembagi_all = 'xb1_all+xb2_all+xb3_all+xb4_all+xb6_all+xb7_all+xb8_all+xb9_all+xb10_all+xb11_all+xb12_all';
						$pembagi_fr = 'xb1_fr+xb2_fr+xb3_fr+xb4_fr+xb6_fr+xb7_fr+xb8_fr+xb9_fr+xb10_fr+xb11_fr+xb12_fr';
						$pembagi_ho = 'xb1_ho+xb2_ho+xb3_ho+xb4_ho+xb6_ho+xb7_ho+xb8_ho+xb9_ho+xb10_ho+xb11_ho+xb12_ho';
						$pembagi_ma = 'xb1_ma+xb2_ma+xb3_ma+xb4_ma+xb6_ma+xb7_ma+xb8_ma+xb9_ma+xb10_ma+xb11_ma+xb12_ma';
						$pembagi_my = 'xb1_my+xb2_my+xb3_my+xb4_my+xb6_my+xb7_my+xb8_my+xb9_my+xb10_my+xb11_my+xb12_my';
						$pembagi_tr = 'xb1_tr+xb2_tr+xb3_tr+xb4_tr+xb6_tr+xb7_tr+xb8_tr+xb9_tr+xb10_tr+xb11_tr+xb12_tr';
						$pembagi_ot = 'xb1_ot+xb2_ot+xb3_ot+xb4_ot+xb6_ot+xb7_ot+xb8_ot+xb9_ot+xb10_ot+xb11_ot+xb12_ot';
						$rata = 4;
					}elseif ($bulan == '06')
					{
						$totalx_all = 'b1_all+b2_all+b3_all+b4_all+b5_all+b7_all+b8_all+b9_all+b10_all+b11_all+b12_all';
						$totalx_fr = 'b1_fr+b2_fr+b3_fr+b4_fr+b5_fr+b7_fr+b8_fr+b9_fr+b10_fr+b11_fr+b12_fr';
						$totalx_ho = 'b1_ho+b2_ho+b3_ho+b4_ho+b5_ho+b7_ho+b8_ho+b9_ho+b10_ho+b11_ho+b12_ho';
						$totalx_ma = 'b1_ma+b2_ma+b3_ma+b4_ma+b5_ma+b7_ma+b8_ma+b9_ma+b10_ma+b11_ma+b12_ma';
						$totalx_my = 'b1_my+b2_my+b3_my+b4_my+b5_my+b7_my+b8_my+b9_my+b10_my+b11_my+b12_my';
						$totalx_tr = 'b1_tr+b2_tr+b3_tr+b4_tr+b5_tr+b7_tr+b8_tr+b9_tr+b10_tr+b11_tr+b12_tr';
						$totalx_ot = 'b1_ot+b2_ot+b3_ot+b4_ot+b5_ot+b7_ot+b8_ot+b9_ot+b10_ot+b11_ot+b12_ot';
						
						$pembagi_all = 'xb1_all+xb2_all+xb3_all+xb4_all+xb5_all+xb7_all+xb8_all+xb9_all+xb10_all+xb11_all+xb12_all';
						$pembagi_fr = 'xb1_fr+xb2_fr+xb3_fr+xb4_fr+xb5_fr+xb7_fr+xb8_fr+xb9_fr+xb10_fr+xb11_fr+xb12_fr';
						$pembagi_ho = 'xb1_ho+xb2_ho+xb3_ho+xb4_ho+xb5_ho+xb7_ho+xb8_ho+xb9_ho+xb10_ho+xb11_ho+xb12_ho';
						$pembagi_ma = 'xb1_ma+xb2_ma+xb3_ma+xb4_ma+xb5_ma+xb7_ma+xb8_ma+xb9_ma+xb10_ma+xb11_ma+xb12_ma';
						$pembagi_my = 'xb1_my+xb2_my+xb3_my+xb4_my+xb5_my+xb7_my+xb8_my+xb9_my+xb10_my+xb11_my+xb12_my';
						$pembagi_tr = 'xb1_tr+xb2_tr+xb3_tr+xb4_tr+xb5_tr+xb7_tr+xb8_tr+xb9_tr+xb10_tr+xb11_tr+xb12_tr';
						$pembagi_ot = 'xb1_ot+xb2_ot+xb3_ot+xb4_ot+xb5_ot+xb7_ot+xb8_ot+xb9_ot+xb10_ot+xb11_ot+xb12_ot';
						$rata = 5;
					}elseif ($bulan == '07')
					{
						$totalx_all = 'b1_all+b2_all+b3_all+b4_all+b5_all+b6_all+b8_all+b9_all+b10_all+b11_all+b12_all';
						$totalx_fr = 'b1_fr+b2_fr+b3_fr+b4_fr+b5_fr+b6_fr+b8_fr+b9_fr+b10_fr+b11_fr+b12_fr';
						$totalx_ho = 'b1_ho+b2_ho+b3_ho+b4_ho+b5_ho+b6_ho+b8_ho+b9_ho+b10_ho+b11_ho+b12_ho';
						$totalx_ma = 'b1_ma+b2_ma+b3_ma+b4_ma+b5_ma+b6_ma+b8_ma+b9_ma+b10_ma+b11_ma+b12_ma';
						$totalx_my = 'b1_my+b2_my+b3_my+b4_my+b5_my+b6_my+b8_my+b9_my+b10_my+b11_my+b12_my';
						$totalx_tr = 'b1_tr+b2_tr+b3_tr+b4_tr+b5_tr+b6_tr+b8_tr+b9_tr+b10_tr+b11_tr+b12_tr';
						$totalx_ot = 'b1_ot+b2_ot+b3_ot+b4_ot+b5_ot+b6_ot+b8_ot+b9_ot+b10_ot+b11_ot+b12_ot';
						
						$pembagi_all = 'xb1_all+xb2_all+xb3_all+xb4_all+xb5_all+xb6_all+xb8_all+xb9_all+xb10_all+xb11_all+xb12_all';
						$pembagi_fr = 'xb1_fr+xb2_fr+xb3_fr+xb4_fr+xb5_fr+xb6_fr+xb8_fr+xb9_fr+xb10_fr+xb11_fr+xb12_fr';
						$pembagi_ho = 'xb1_ho+xb2_ho+xb3_ho+xb4_ho+xb5_ho+xb6_ho+xb8_ho+xb9_ho+xb10_ho+xb11_ho+xb12_ho';
						$pembagi_ma = 'xb1_ma+xb2_ma+xb3_ma+xb4_ma+xb5_ma+xb6_ma+xb8_ma+xb9_ma+xb10_ma+xb11_ma+xb12_ma';
						$pembagi_my = 'xb1_my+xb2_my+xb3_my+xb4_my+xb5_my+xb6_my+xb8_my+xb9_my+xb10_my+xb11_my+xb12_my';
						$pembagi_tr = 'xb1_tr+xb2_tr+xb3_tr+xb4_tr+xb5_tr+xb6_tr+xb8_tr+xb9_tr+xb10_tr+xb11_tr+xb12_tr';
						$pembagi_ot = 'xb1_ot+xb2_ot+xb3_ot+xb4_ot+xb5_ot+xb6_ot+xb8_ot+xb9_ot+xb10_ot+xb11_ot+xb12_ot';
						
						$rata = 6;
					}elseif ($bulan == '08')
					{
						$totalx_all = 'b1_all+b2_all+b3_all+b4_all+b5_all+b6_all+b7_all+b9_all+b10_all+b11_all+b12_all';
						$totalx_fr = 'b1_fr+b2_fr+b3_fr+b4_fr+b5_fr+b6_fr+b7_fr+b9_fr+b10_fr+b11_fr+b12_fr';
						$totalx_ho = 'b1_ho+b2_ho+b3_ho+b4_ho+b5_ho+b6_ho+b7_ho+b9_ho+b10_ho+b11_ho+b12_ho';
						$totalx_ma = 'b1_ma+b2_ma+b3_ma+b4_ma+b5_ma+b6_ma+b7_ma+b9_ma+b10_ma+b11_ma+b12_ma';
						$totalx_my = 'b1_my+b2_my+b3_my+b4_my+b5_my+b6_my+b7_my+b9_my+b10_my+b11_my+b12_my';
						$totalx_tr = 'b1_tr+b2_tr+b3_tr+b4_tr+b5_tr+b6_tr+b7_tr+b9_tr+b10_tr+b11_tr+b12_tr';
						$totalx_ot = 'b1_ot+b2_ot+b3_ot+b4_ot+b5_ot+b6_ot+b7_ot+b9_ot+b10_ot+b11_ot+b12_ot';
						
						$pembagi_all = 'xb1_all+xb2_all+xb3_all+xb4_all+xb5_all+xb6_all+xb7_all+xb9_all+xb10_all+xb11_all+xb12_all';
						$pembagi_fr = 'xb1_fr+xb2_fr+xb3_fr+xb4_fr+xb5_fr+xb6_fr+xb7_fr+xb9_fr+xb10_fr+xb11_fr+xb12_fr';
						$pembagi_ho = 'xb1_ho+xb2_ho+xb3_ho+xb4_ho+xb5_ho+xb6_ho+xb7_ho+xb9_ho+xb10_ho+xb11_ho+xb12_ho';
						$pembagi_ma = 'xb1_ma+xb2_ma+xb3_ma+xb4_ma+xb5_ma+xb6_ma+xb7_ma+xb9_ma+xb10_ma+xb11_ma+xb12_ma';
						$pembagi_my = 'xb1_my+xb2_my+xb3_my+xb4_my+xb5_my+xb6_my+xb7_my+xb9_my+xb10_my+xb11_my+xb12_my';
						$pembagi_tr = 'xb1_tr+xb2_tr+xb3_tr+xb4_tr+xb5_tr+xb6_tr+xb7_tr+xb9_tr+xb10_tr+xb11_tr+xb12_tr';
						$pembagi_ot = 'xb1_ot+xb2_ot+xb3_ot+xb4_ot+xb5_ot+xb6_ot+xb7_ot+xb9_ot+xb10_ot+xb11_ot+xb12_ot';
						
						$rata = 7;
					}elseif ($bulan == '09')
					{
						$totalx_all = 'b1_all+b2_all+b3_all+b4_all+b5_all+b6_all+b7_all+b8_all+b10_all+b11_all+b12_all';
						$totalx_fr = 'b1_fr+b2_fr+b3_fr+b4_fr+b5_fr+b6_fr+b7_fr+b8_fr+b10_fr+b11_fr+b12_fr';
						$totalx_ho = 'b1_ho+b2_ho+b3_ho+b4_ho+b5_ho+b6_ho+b7_ho+b8_ho+b10_ho+b11_ho+b12_ho';
						$totalx_ma = 'b1_ma+b2_ma+b3_ma+b4_ma+b5_ma+b6_ma+b7_ma+b8_ma+b10_ma+b11_ma+b12_ma';
						$totalx_my = 'b1_my+b2_my+b3_my+b4_my+b5_my+b6_my+b7_my+b8_my+b10_my+b11_my+b12_my';
						$totalx_tr = 'b1_tr+b2_tr+b3_tr+b4_tr+b5_tr+b6_tr+b7_tr+b8_tr+b10_tr+b11_tr+b12_tr';
						$totalx_ot = 'b1_ot+b2_ot+b3_ot+b4_ot+b5_ot+b6_ot+b7_ot+b8_ot+b10_ot+b11_ot+b12_ot';
						
						$pembagi_all = 'xb1_all+xb2_all+xb3_all+xb4_all+xb5_all+xb6_all+xb7_all+xb8_all+xb10_all+xb11_all+xb12_all';
						$pembagi_fr = 'xb1_fr+xb2_fr+xb3_fr+xb4_fr+xb5_fr+xb6_fr+xb7_fr+xb8_fr+xb10_fr+xb11_fr+xb12_fr';
						$pembagi_ho = 'xb1_ho+xb2_ho+xb3_ho+xb4_ho+xb5_ho+xb6_ho+xb7_ho+xb8_ho+xb10_ho+xb11_ho+xb12_ho';
						$pembagi_ma = 'xb1_ma+xb2_ma+xb3_ma+xb4_ma+xb5_ma+xb6_ma+xb7_ma+xb8_ma+xb10_ma+xb11_ma+xb12_ma';
						$pembagi_my = 'xb1_my+xb2_my+xb3_my+xb4_my+xb5_my+xb6_my+xb7_my+xb8_my+xb10_my+xb11_my+xb12_my';
						$pembagi_tr = 'xb1_tr+xb2_tr+xb3_tr+xb4_tr+xb5_tr+xb6_tr+xb7_tr+xb8_tr+xb10_tr+xb11_tr+xb12_tr';
						$pembagi_ot = 'xb1_ot+xb2_ot+xb3_ot+xb4_ot+xb5_ot+xb6_ot+xb7_ot+xb8_ot+xb10_ot+xb11_ot+xb12_ot';
						
						$rata = 8;
					}elseif ($bulan == '10')
					{
						$totalx_all = 'b1_all+b2_all+b3_all+b4_all+b5_all+b6_all+b7_all+b8_all+b9_all+b11_all+b12_all';
						$totalx_fr = 'b1_fr+b2_fr+b3_fr+b4_fr+b5_fr+b6_fr+b7_fr+b8_fr+b9_fr+b11_fr+b12_fr';
						$totalx_ho = 'b1_ho+b2_ho+b3_ho+b4_ho+b5_ho+b6_ho+b7_ho+b8_ho+b9_ho+b11_ho+b12_ho';
						$totalx_ma = 'b1_ma+b2_ma+b3_ma+b4_ma+b5_ma+b6_ma+b7_ma+b8_ma+b9_ma+b11_ma+b12_ma';
						$totalx_my = 'b1_my+b2_my+b3_my+b4_my+b5_my+b6_my+b7_my+b8_my+b9_my+b11_my+b12_my';
						$totalx_tr = 'b1_tr+b2_tr+b3_tr+b4_tr+b5_tr+b6_tr+b7_tr+b8_tr+b9_tr+b11_tr+b12_tr';
						$totalx_ot = 'b1_ot+b2_ot+b3_ot+b4_ot+b5_ot+b6_ot+b7_ot+b8_ot+b9_ot+b11_ot+b12_ot';
						
						$pembagi_all = 'xb1_all+xb2_all+xb3_all+xb4_all+xb5_all+xb6_all+xb7_all+xb8_all+xb9_all+xb11_all+xb12_all';
						$pembagi_fr = 'xb1_fr+xb2_fr+xb3_fr+xb4_fr+xb5_fr+xb6_fr+xb7_fr+xb8_fr+xb9_fr+xb11_fr+xb12_fr';
						$pembagi_ho = 'xb1_ho+xb2_ho+xb3_ho+xb4_ho+xb5_ho+xb6_ho+xb7_ho+xb8_ho+xb9_ho+xb11_ho+xb12_ho';
						$pembagi_ma = 'xb1_ma+xb2_ma+xb3_ma+xb4_ma+xb5_ma+xb6_ma+xb7_ma+xb8_ma+xb9_ma+xb11_ma+xb12_ma';
						$pembagi_my = 'xb1_my+xb2_my+xb3_my+xb4_my+xb5_my+xb6_my+xb7_my+xb8_my+xb9_my+xb11_my+xb12_my';
						$pembagi_tr = 'xb1_tr+xb2_tr+xb3_tr+xb4_tr+xb5_tr+xb6_tr+xb7_tr+xb8_tr+xb9_tr+xb11_tr+xb12_tr';
						$pembagi_ot = 'xb1_ot+xb2_ot+xb3_ot+xb4_ot+xb5_ot+xb6_ot+xb7_ot+xb8_ot+xb9_ot+xb11_ot+xb12_ot';
						$rata = 9;
					}elseif ($bulan == '11')
					{
						$totalx_all = 'b1_all+b2_all+b3_all+b4_all+b5_all+b6_all+b7_all+b8_all+b9_all+b10_all+b12';
						$totalx_fr = 'b1_fr+b2_fr+b3_fr+b4_fr+b5_fr+b6_fr+b7_fr+b8_fr+b9_fr+b10_fr+b12_fr';
						$totalx_ho = 'b1_ho+b2_ho+b3_ho+b4_ho+b5_ho+b6_ho+b7_ho+b8_ho+b9_ho+b10_ho+b12_ho';
						$totalx_ma = 'b1_ma+b2_ma+b3_ma+b4_ma+b5_ma+b6_ma+b7_ma+b8_ma+b9_ma+b10_ma+b12_ma';
						$totalx_my = 'b1_my+b2_my+b3_my+b4_my+b5_my+b6_my+b7_my+b8_my+b9_my+b10_my+b12_my';
						$totalx_tr = 'b1_tr+b2_tr+b3_tr+b4_tr+b5_tr+b6_tr+b7_tr+b8_tr+b9_tr+b10_tr+b12_tr';
						$totalx_ot = 'b1_ot+b2_ot+b3_ot+b4_ot+b5_ot+b6_ot+b7_ot+b8_ot+b9_ot+b10_ot+b12_ot';
						$pembagi_all = 'xb1_all+xb2_all+xb3_all+xb4_all+xb5_all+xb6_all+xb7_all+xb8_all+xb9_all+xb10_all+xb12_all';
						$pembagi_fr = 'xb1_fr+xb2_fr+xb3_fr+xb4_fr+xb5_fr+xb6_fr+xb7_fr+xb8_fr+xb9_fr+xb10_fr+xb12_fr';
						$pembagi_ho = 'xb1_ho+xb2_ho+xb3_ho+xb4_ho+xb5_ho+xb6_ho+xb7_ho+xb8_ho+xb9_ho+xb10_ho+xb12_ho';
						$pembagi_ma = 'xb1_ma+xb2_ma+xb3_ma+xb4_ma+xb5_ma+xb6_ma+xb7_ma+xb8_ma+xb9_ma+xb10_ma+xb12_ma';
						$pembagi_my = 'xb1_my+xb2_my+xb3_my+xb4_my+xb5_my+xb6_my+xb7_my+xb8_my+xb9_my+xb10_my+xb12_my';
						$pembagi_tr = 'xb1_tr+xb2_tr+xb3_tr+xb4_tr+xb5_tr+xb6_tr+xb7_tr+xb8_tr+xb9_tr+xb10_tr+xb12_tr';
						$pembagi_ot = 'xb1_ot+xb2_ot+xb3_ot+xb4_ot+xb5_ot+xb6_ot+xb7_ot+xb8_ot+xb9_ot+xb10_ot+xb12_ot';
						$rata = 10;
					}elseif ($bulan == '12')
					{
						$totalx_all = 'b1_all+b2_all+b3_all+b4_all+b5_all+b6_all+b7_all+b8_all+b9_all+b10_all+b11_all';
						$totalx_fr = 'b1_fr+b2_fr+b3_fr+b4_fr+b5_fr+b6_fr+b7_fr+b8_fr+b9_fr+b10_fr+b11_fr';
						$totalx_ho = 'b1_ho+b2_ho+b3_ho+b4_ho+b5_ho+b6_ho+b7_ho+b8_ho+b9_ho+b10_ho+b11_ho';
						$totalx_ma = 'b1_ma+b2_ma+b3_ma+b4_ma+b5_ma+b6_ma+b7_ma+b8_ma+b9_ma+b10_ma+b11_ma';
						$totalx_my = 'b1_my+b2_my+b3_my+b4_my+b5_my+b6_my+b7_my+b8_my+b9_my+b10_my+b11_my';
						$totalx_tr = 'b1_tr+b2_tr+b3_tr+b4_tr+b5_tr+b6_tr+b7_tr+b8_tr+b9_tr+b10_tr+b11_tr';
						$totalx_ot = 'b1_ot+b2_ot+b3_ot+b4_ot+b5_ot+b6_ot+b7_ot+b8_ot+b9_ot+b10_ot+b11_ot';
						$pembagi_all = 'xb1_all+xb2_all+xb3_all+xb4_all+xb5_all+xb6_all+xb7_all+xb8_all+xb9_all+xb10_all+xb11_all';
						$pembagi_fr = 'xb1_fr+xb2_fr+xb3_fr+xb4_fr+xb5_fr+xb6_fr+xb7_fr+xb8_fr+xb9_fr+xb10_fr+xb11_fr';
						$pembagi_ho = 'xb1_ho+xb2_ho+xb3_ho+xb4_ho+xb5_ho+xb6_ho+xb7_ho+xb8_ho+xb9_ho+xb10_ho+xb11_ho';
						$pembagi_ma = 'xb1_ma+xb2_ma+xb3_ma+xb4_ma+xb5_ma+xb6_ma+xb7_ma+xb8_ma+xb9_ma+xb10_ma+xb11_ma';
						$pembagi_my = 'xb1_my+xb2_my+xb3_my+xb4_my+xb5_my+xb6_my+xb7_my+xb8_my+xb9_my+xb10_my+xb11_my';
						$pembagi_tr = 'xb1_tr+xb2_tr+xb3_tr+xb4_tr+xb5_tr+xb6_tr+xb7_tr+xb8_tr+xb9_tr+xb10_tr+xb11_tr';
						$pembagi_ot = 'xb1_ot+xb2_ot+xb3_ot+xb4_ot+xb5_ot+xb6_ot+xb7_ot+xb8_ot+xb9_ot+xb10_ot+xb11_ot';
						$rata = 11;
					}else
					{
						$totalx_all = 'b1_all+b2_all+b3_all+b4_all+b5_all+b6_all+b7_all+b8_all+b9_all+b10_all+b11_all+b12_all';
						$totalx_fr = 'b1_fr+b2_fr+b3_fr+b4_fr+b5_fr+b6_fr+b7_fr+b8_fr+b9_fr+b10_fr+b11_fr+b12_fr';
						$totalx_ho = 'b1_ho+b2_ho+b3_ho+b4_ho+b5_ho+b6_ho+b7_ho+b8_ho+b9_ho+b10_ho+b11_ho+b12_ho';
						$totalx_ma = 'b1_ma+b2_ma+b3_ma+b4_ma+b5_ma+b6_ma+b7_ma+b8_ma+b9_ma+b10_ma+b11_ma+b12_ma';
						$totalx_my = 'b1_my+b2_my+b3_my+b4_my+b5_my+b6_my+b7_my+b8_my+b9_my+b10_my+b11_my+b12_my';
						$totalx_tr = 'b1_tr+b2_tr+b3_tr+b4_tr+b5_tr+b6_tr+b7_tr+b8_tr+b9_tr+b10_tr+b11_tr+b12_tr';
						$totalx_ot = 'b1_ot+b2_ot+b3_ot+b4_ot+b5_ot+b6_ot+b7_ot+b8_ot+b9_ot+b10_ot+b11_ot+b12_ot';
						$pembagi_all = 'xb1_all+xb2_all+xb3_all+xb4_all+xb5_all+xb6_all+xb7_all+xb8_all+xb9_all+xb10_all+xb11_all+xb12_all';
						$pembagi_fr = 'xb1_fr+xb2_fr+xb3_fr+xb4_fr+xb5_fr+xb6_fr+xb7_fr+xb8_fr+xb9_fr+xb10_fr+xb11_fr+xb12_fr';
						$pembagi_ho = 'xb1_ho+xb2_ho+xb3_ho+xb4_ho+xb5_ho+xb6_ho+xb7_ho+xb8_ho+xb9_ho+xb10_ho+xb11_ho+xb12_ho';
						$pembagi_ma = 'xb1_ma+xb2_ma+xb3_ma+xb4_ma+xb5_ma+xb6_ma+xb7_ma+xb8_ma+xb9_ma+xb10_ma+xb11_ma+xb12_ma';
						$pembagi_my = 'xb1_my+xb2_my+xb3_my+xb4_my+xb5_my+xb6_my+xb7_my+xb8_my+xb9_my+xb10_my+xb11_my+xb12_my';
						$pembagi_tr = 'xb1_tr+xb2_tr+xb3_tr+xb4_tr+xb5_tr+xb6_tr+xb7_tr+xb8_tr+xb9_tr+xb10_tr+xb11_tr+xb12_tr';
						$pembagi_ot = 'xb1_ot+xb2_ot+xb3_ot+xb4_ot+xb5_ot+xb6_ot+xb7_ot+xb8_ot+xb9_ot+xb10_ot+xb11_ot+xb12_ot';
						$rata = 12;
					}

				}else{
						$totalx_all = 'b1_all+b2_all+b3_all+b4_all+b5_all+b6_all+b7_all+b8_all+b9_all+b10_all+b11_all+b12_all';
						$totalx_fr = 'b1_fr+b2_fr+b3_fr+b4_fr+b5_fr+b6_fr+b7_fr+b8_fr+b9_fr+b10_fr+b11_fr+b12_fr';
						$totalx_ho = 'b1_ho+b2_ho+b3_ho+b4_ho+b5_ho+b6_ho+b7_ho+b8_ho+b9_ho+b10_ho+b11_ho+b12_ho';
						$totalx_ma = 'b1_ma+b2_ma+b3_ma+b4_ma+b5_ma+b6_ma+b7_ma+b8_ma+b9_ma+b10_ma+b11_ma+b12_ma';
						$totalx_my = 'b1_my+b2_my+b3_my+b4_my+b5_my+b6_my+b7_my+b8_my+b9_my+b10_my+b11_my+b12_my';
						$totalx_tr = 'b1_tr+b2_tr+b3_tr+b4_tr+b5_tr+b6_tr+b7_tr+b8_tr+b9_tr+b10_tr+b11_tr+b12_tr';
						$totalx_ot = 'b1_ot+b2_ot+b3_ot+b4_ot+b5_ot+b6_ot+b7_ot+b8_ot+b9_ot+b10_ot+b11_ot+b12_ot';
						$pembagi_all = 'xb1_all+xb2_all+xb3_all+xb4_all+xb5_all+xb6_all+xb7_all+xb8_all+xb9_all+xb10_all+xb11_all+xb12_all';
						$pembagi_fr = 'xb1_fr+xb2_fr+xb3_fr+xb4_fr+xb5_fr+xb6_fr+xb7_fr+xb8_fr+xb9_fr+xb10_fr+xb11_fr+xb12_fr';
						$pembagi_ho = 'xb1_ho+xb2_ho+xb3_ho+xb4_ho+xb5_ho+xb6_ho+xb7_ho+xb8_ho+xb9_ho+xb10_ho+xb11_ho+xb12_ho';
						$pembagi_ma = 'xb1_ma+xb2_ma+xb3_ma+xb4_ma+xb5_ma+xb6_ma+xb7_ma+xb8_ma+xb9_ma+xb10_ma+xb11_ma+xb12_ma';
						$pembagi_my = 'xb1_my+xb2_my+xb3_my+xb4_my+xb5_my+xb6_my+xb7_my+xb8_my+xb9_my+xb10_my+xb11_my+xb12_my';
						$pembagi_tr = 'xb1_tr+xb2_tr+xb3_tr+xb4_tr+xb5_tr+xb6_tr+xb7_tr+xb8_tr+xb9_tr+xb10_tr+xb11_tr+xb12_tr';
						$pembagi_ot = 'xb1_ot+xb2_ot+xb3_ot+xb4_ot+xb5_ot+xb6_ot+xb7_ot+xb8_ot+xb9_ot+xb10_ot+xb11_ot+xb12_ot';
						$rata = 12;
				}
				/* ========================================== */

				/* == PROSES DELETE MPM.OMZET_NEW ===== */
				$query = "	
						delete from mpm.omzet_new_us
						where kategori_supplier = ".'"'.$supp.'"'." and tahun = $year and id = $id
							";
				echo "<pre>";
				print_r($query);
				echo "</pre>";
				$sql = $this->db->query($query);

				/* == PROSES DELETE mpm.tbl_temp_omzet = */
				$query = "	
						delete from mpm.tbl_temp_omzet_us 
						where kategori_supplier = ".'"'.$supp.'"'." and tahun = $year and and id = $id
							";
				echo "<pre>";
				print_r($query);
				echo "</pre>";

				$sql = $this->db->query($query);

				/* ========== PROSES INSERT KE mpm.tbl_temp_omzet =================== */
			
$query = "	   
	select  a.nocab, a.kode_comp, b.sub, b.nama_comp,
			b1_fr,b1_ho,b1_ma,b1_my,b1_tr,b1_ot,b1_all,
			b2_fr,b2_ho,b2_ma,b2_my,b2_tr,b2_ot,b2_all,
			b3_fr,b3_ho,b3_ma,b3_my,b3_tr,b3_ot,b3_all,
			b4_fr,b4_ho,b4_ma,b4_my,b4_tr,b4_ot,b4_all,
			b5_fr,b5_ho,b5_ma,b5_my,b5_tr,b5_ot,b5_all,
			b6_fr,b6_ho,b6_ma,b6_my,b6_tr,b6_ot,b6_all,
			b7_fr,b7_ho,b7_ma,b7_my,b7_tr,b7_ot,b7_all,
			b8_fr,b8_ho,b8_ma,b8_my,b8_tr,b8_ot,b8_all,
			b9_fr,b9_ho,b9_ma,b9_my,b9_tr,b9_ot,b9_all,
			b10_fr,b10_ho,b10_ma,b10_my,b10_tr,b10_ot,b10_all,
			b11_fr,b11_ho,b11_ma,b11_my,b11_tr,b11_ot,b11_all,
			b12_fr,b12_ho,b12_ma,b12_my,b12_tr,b12_ot,b12_all,
			t1_fr,t1_ho,t1_ma,t1_my,t1_tr,t1_ot,
			t2_fr,t2_ho,t2_ma,t2_my,t2_tr,t2_ot,
			t3_fr,t3_ho,t3_ma,t3_my,t3_tr,t3_ot,
			t4_fr,t4_ho,t4_ma,t4_my,t4_tr,t4_ot,
			t5_fr,t5_ho,t5_ma,t5_my,t5_tr,t5_ot,
			t6_fr,t6_ho,t6_ma,t6_my,t6_tr,t6_ot,
			t7_fr,t7_ho,t7_ma,t7_my,t7_tr,t7_ot,
			t8_fr,t8_ho,t8_ma,t8_my,t8_tr,t8_ot,
			t9_fr,t9_ho,t9_ma,t9_my,t9_tr,t9_ot,
			t10_fr,t10_ho,t10_ma,t10_my,t10_tr,t10_ot,
			t11_fr,t11_ho,t11_ma,t11_my,t11_tr,t11_ot,
			t12_fr,t12_ho,t12_ma,t12_my,t12_tr,t12_ot, 
			total_fr,total_ho,total_ma,total_my,total_tr,total_ot,total_all,
			totalx_fr/jmlbulan_fr rata_fr,
			totalx_ho/jmlbulan_ho rata_ho,
			totalx_ma/jmlbulan_ma rata_ma,
			totalx_my/jmlbulan_my rata_my,
			totalx_tr/jmlbulan_tr rata_tr,
			totalx_ot/jmlbulan_ot rata_ot,
			totalx_all/jmlbulan_all rata_all,
			".'"'.$supp.'"'.",".$tgl_created.",".$year.", urutan, status, maxupload, '$key_jam',".$id."
	FROM
	(
		select 	nocab,kode_comp,kode,
				b1_fr,b1_ho,b1_ma,b1_my,b1_tr,b1_ot,b1_all,
				b2_fr,b2_ho,b2_ma,b2_my,b2_tr,b2_ot,b2_all,
				b3_fr,b3_ho,b3_ma,b3_my,b3_tr,b3_ot,b3_all,
				b4_fr,b4_ho,b4_ma,b4_my,b4_tr,b4_ot,b4_all,
				b5_fr,b5_ho,b5_ma,b5_my,b5_tr,b5_ot,b5_all,
				b6_fr,b6_ho,b6_ma,b6_my,b6_tr,b6_ot,b6_all,
				b7_fr,b7_ho,b7_ma,b7_my,b7_tr,b7_ot,b7_all,
				b8_fr,b8_ho,b8_ma,b8_my,b8_tr,b8_ot,b8_all,
				b9_fr,b9_ho,b9_ma,b9_my,b9_tr,b9_ot,b9_all,
				b10_fr,b10_ho,b10_ma,b10_my,b10_tr,b10_ot,b10_all,
				b11_fr,b11_ho,b11_ma,b11_my,b11_tr,b11_ot,b11_all,
				b12_fr,b12_ho,b12_ma,b12_my,b12_tr,b12_ot,b12_all,
				t1_fr,t1_ho,t1_ma,t1_my,t1_tr,t1_ot,
				t2_fr,t2_ho,t2_ma,t2_my,t2_tr,t2_ot,
				t3_fr,t3_ho,t3_ma,t3_my,t3_tr,t3_ot,
				t4_fr,t4_ho,t4_ma,t4_my,t4_tr,t4_ot,
				t5_fr,t5_ho,t5_ma,t5_my,t5_tr,t5_ot,
				t6_fr,t6_ho,t6_ma,t6_my,t6_tr,t6_ot,
				t7_fr,t7_ho,t7_ma,t7_my,t7_tr,t7_ot,
				t8_fr,t8_ho,t8_ma,t8_my,t8_tr,t8_ot,
				t9_fr,t9_ho,t9_ma,t9_my,t9_tr,t9_ot,
				t10_fr,t10_ho,t10_ma,t10_my,t10_tr,t10_ot,
				t11_fr,t11_ho,t11_ma,t11_my,t11_tr,t11_ot,
				t12_fr,t12_ho,t12_ma,t12_my,t12_tr,t12_ot, ".$id.",
				total_fr,total_ho, total_ma,total_my, total_tr,total_ot,total_all,
				totalx_fr,totalx_ho, totalx_ma,totalx_my, totalx_tr,totalx_ot,totalx_all,
				format(sum($pembagi_fr),0, 1) jmlbulan_fr, 
				format(sum($pembagi_ho),0, 1) jmlbulan_ho,
				format(sum($pembagi_ma),0, 1) jmlbulan_ma, 
				format(sum($pembagi_my),0, 1) jmlbulan_my,
				format(sum($pembagi_tr),0, 1) jmlbulan_tr, 
				format(sum($pembagi_ot),0, 1) jmlbulan_ot,
				format(sum($pembagi_all),0, 1) jmlbulan_all
		FROM
		(
			select  nocab,kode_comp, 
					b1_fr,b1_ho,b1_ma,b1_my,b1_tr,b1_ot,b1_all,
					b2_fr,b2_ho,b2_ma,b2_my,b2_tr,b2_ot,b2_all,
					b3_fr,b3_ho,b3_ma,b3_my,b3_tr,b3_ot,b3_all,
					b4_fr,b4_ho,b4_ma,b4_my,b4_tr,b4_ot,b4_all,
					b5_fr,b5_ho,b5_ma,b5_my,b5_tr,b5_ot,b5_all,
					b6_fr,b6_ho,b6_ma,b6_my,b6_tr,b6_ot,b6_all,
					b7_fr,b7_ho,b7_ma,b7_my,b7_tr,b7_ot,b7_all,
					b8_fr,b8_ho,b8_ma,b8_my,b8_tr,b8_ot,b8_all,
					b9_fr,b9_ho,b9_ma,b9_my,b9_tr,b9_ot,b9_all,
					b10_fr,b10_ho,b10_ma,b10_my,b10_tr,b10_ot,b10_all,
					b11_fr,b11_ho,b11_ma,b11_my,b11_tr,b11_ot,b11_all,
					b12_fr,b12_ho,b12_ma,b12_my,b12_tr,b12_ot,b12_all,
					".$id.",
					b1_fr+b2_fr+b3_fr+b4_fr+b5_fr+b6_fr+b7_fr+b8_fr+b9_fr+b10_fr+b11_fr+b12_fr as total_fr,
					b1_ho+b2_ho+b3_ho+b4_ho+b5_ho+b6_ho+b7_ho+b8_ho+b9_ho+b10_ho+b11_ho+b12_ho as total_ho,
					b1_ma+b2_ma+b3_ma+b4_ma+b5_ma+b6_ma+b7_ma+b8_ma+b9_ma+b10_ma+b11_ma+b12_ma as total_ma,
					b1_my+b2_my+b3_my+b4_my+b5_my+b6_my+b7_my+b8_my+b9_my+b10_my+b11_my+b12_my as total_my,
					b1_tr+b2_tr+b3_tr+b4_tr+b5_tr+b6_tr+b7_tr+b8_tr+b9_tr+b10_tr+b11_tr+b12_tr as total_tr,
					b1_ot+b2_ot+b3_ot+b4_ot+b5_ot+b6_ot+b7_ot+b8_ot+b9_ot+b10_ot+b11_ot+b12_ot as total_ot,
					b1_all+b2_all+b3_all+b4_all+b5_all+b6_all+b7_all+b8_all+b9_all+b10_all+b11_all+b12_all as total_all,
					$totalx_all as totalx_all,					
					$totalx_fr as totalx_fr,
					$totalx_ho as totalx_ho,	
					$totalx_ma as totalx_ma,
					$totalx_my as totalx_my,	
					$totalx_tr as totalx_tr,
					$totalx_ot as totalx_ot,
					IF(b1_fr = 0, 0, 1) xb1_fr, IF(b1_ho = 0, 0, 1) xb1_ho,	IF(b1_ma = 0, 0, 1) xb1_ma,
					IF(b1_my = 0, 0, 1) xb1_my,	IF(b1_tr = 0, 0, 1) xb1_tr, IF(b1_ot = 0, 0, 1) xb1_ot,
					IF(b1_all= 0, 0, 1) xb1_all,
					IF(b2_fr = 0, 0, 1) xb2_fr, IF(b2_ho = 0, 0, 1) xb2_ho,	IF(b2_ma = 0, 0, 1) xb2_ma,
					IF(b2_my = 0, 0, 1) xb2_my,	IF(b2_tr = 0, 0, 1) xb2_tr, IF(b2_ot = 0, 0, 1) xb2_ot,
					IF(b2_all= 0, 0, 1) xb2_all,
					IF(b3_fr = 0, 0, 1) xb3_fr, IF(b3_ho = 0, 0, 1) xb3_ho,	IF(b3_ma = 0, 0, 1) xb3_ma,
					IF(b3_my = 0, 0, 1) xb3_my,	IF(b3_tr = 0, 0, 1) xb3_tr, IF(b3_ot = 0, 0, 1) xb3_ot,
					IF(b3_all= 0, 0, 1) xb3_all,
					IF(b4_fr = 0, 0, 1) xb4_fr, IF(b4_ho = 0, 0, 1) xb4_ho,	IF(b4_ma = 0, 0, 1) xb4_ma,
					IF(b4_my = 0, 0, 1) xb4_my,	IF(b4_tr = 0, 0, 1) xb4_tr, IF(b4_ot = 0, 0, 1) xb4_ot,
					IF(b4_all= 0, 0, 1) xb4_all,
					IF(b5_fr = 0, 0, 1) xb5_fr, IF(b5_ho = 0, 0, 1) xb5_ho,	IF(b5_ma = 0, 0, 1) xb5_ma,
					IF(b5_my = 0, 0, 1) xb5_my,	IF(b5_tr = 0, 0, 1) xb5_tr, IF(b5_ot = 0, 0, 1) xb5_ot,
					IF(b5_all= 0, 0, 1) xb5_all,
					IF(b6_fr = 0, 0, 1) xb6_fr, IF(b6_ho = 0, 0, 1) xb6_ho,	IF(b6_ma = 0, 0, 1) xb6_ma,
					IF(b6_my = 0, 0, 1) xb6_my,	IF(b6_tr = 0, 0, 1) xb6_tr, IF(b6_ot = 0, 0, 1) xb6_ot,
					IF(b6_all= 0, 0, 1) xb6_all,
					IF(b7_fr = 0, 0, 1) xb7_fr, IF(b7_ho = 0, 0, 1) xb7_ho,	IF(b7_ma = 0, 0, 1) xb7_ma,
					IF(b7_my = 0, 0, 1) xb7_my,	IF(b7_tr = 0, 0, 1) xb7_tr, IF(b7_ot = 0, 0, 1) xb7_ot,
					IF(b7_all= 0, 0, 1) xb7_all,
					IF(b8_fr = 0, 0, 1) xb8_fr, IF(b8_ho = 0, 0, 1) xb8_ho,	IF(b8_ma = 0, 0, 1) xb8_ma,
					IF(b8_my = 0, 0, 1) xb8_my,	IF(b8_tr = 0, 0, 1) xb8_tr, IF(b8_ot = 0, 0, 1) xb8_ot,
					IF(b8_all= 0, 0, 1) xb8_all,
					IF(b9_fr = 0, 0, 1) xb9_fr, IF(b9_ho = 0, 0, 1) xb9_ho,	IF(b9_ma = 0, 0, 1) xb9_ma,
					IF(b9_my = 0, 0, 1) xb9_my,	IF(b9_tr = 0, 0, 1) xb9_tr, IF(b9_ot = 0, 0, 1) xb9_ot,
					IF(b9_all= 0, 0, 1) xb9_all,
					IF(b10_fr = 0, 0, 1) xb10_fr, IF(b10_ho = 0, 0, 1) xb10_ho,	IF(b10_ma = 0, 0, 1) xb10_ma,
					IF(b10_my = 0, 0, 1) xb10_my,	IF(b10_tr = 0, 0, 1) xb10_tr, IF(b10_ot = 0, 0, 1) xb10_ot,
					IF(b10_all= 0, 0, 1) xb10_all,
					IF(b11_fr = 0, 0, 1) xb11_fr, IF(b11_ho = 0, 0, 1) xb11_ho,	IF(b11_ma = 0, 0, 1) xb11_ma,
					IF(b11_my = 0, 0, 1) xb11_my,	IF(b11_tr = 0, 0, 1) xb11_tr, IF(b11_ot = 0, 0, 1) xb11_ot,
					IF(b11_all= 0, 0, 1) xb11_all,
					IF(b12_fr = 0, 0, 1) xb12_fr, IF(b12_ho = 0, 0, 1) xb12_ho,	IF(b12_ma = 0, 0, 1) xb12_ma,
					IF(b12_my = 0, 0, 1) xb12_my,	IF(b12_tr = 0, 0, 1) xb12_tr, IF(b12_ot = 0, 0, 1) xb12_ot,
					IF(b12_all= 0, 0, 1) xb12_all,
					sum(t1_fr) as t1_fr, sum(t1_ho) as t1_ho, sum(t1_ma) as t1_ma, sum(t1_my) as t1_my,
					sum(t1_tr) as t1_tr, sum(t1_ot) as t1_ot, 
					sum(t2_fr) as t2_fr, sum(t2_ho) as t2_ho, sum(t2_ma) as t2_ma, sum(t2_my) as t2_my,
					sum(t2_tr) as t2_tr, sum(t2_ot) as t2_ot, 
					sum(t3_fr) as t3_fr, sum(t3_ho) as t3_ho, sum(t3_ma) as t3_ma, sum(t3_my) as t3_my,
					sum(t3_tr) as t3_tr, sum(t3_ot) as t3_ot, 
					sum(t4_fr) as t4_fr, sum(t4_ho) as t4_ho, sum(t4_ma) as t4_ma, sum(t4_my) as t4_my,
					sum(t4_tr) as t4_tr, sum(t4_ot) as t4_ot, 
					sum(t5_fr) as t5_fr, sum(t5_ho) as t5_ho, sum(t5_ma) as t5_ma, sum(t5_my) as t5_my,
					sum(t5_tr) as t5_tr, sum(t5_ot) as t5_ot, 
					sum(t6_fr) as t6_fr, sum(t6_ho) as t6_ho, sum(t6_ma) as t6_ma, sum(t6_my) as t6_my,
					sum(t6_tr) as t6_tr, sum(t6_ot) as t6_ot, 
					sum(t7_fr) as t7_fr, sum(t7_ho) as t7_ho, sum(t7_ma) as t7_ma, sum(t7_my) as t7_my,
					sum(t7_tr) as t7_tr, sum(t7_ot) as t7_ot, 
					sum(t8_fr) as t8_fr, sum(t8_ho) as t8_ho, sum(t8_ma) as t8_ma, sum(t8_my) as t8_my,
					sum(t8_tr) as t8_tr, sum(t8_ot) as t8_ot, 
					sum(t9_fr) as t9_fr, sum(t9_ho) as t9_ho, sum(t9_ma) as t9_ma, sum(t9_my) as t9_my,
					sum(t9_tr) as t9_tr, sum(t9_ot) as t9_ot, 
					sum(t10_fr) as t10_fr, sum(t10_ho) as t10_ho, sum(t10_ma) as t10_ma, sum(t10_my) as t10_my,
					sum(t10_tr) as t10_tr, sum(t10_ot) as t10_ot, 
					sum(t11_fr) as t11_fr, sum(t11_ho) as t11_ho, sum(t11_ma) as t11_ma, sum(t11_my) as t11_my,
					sum(t11_tr) as t11_tr, sum(t11_ot) as t11_ot, 
					sum(t12_fr) as t12_fr, sum(t12_ho) as t12_ho, sum(t12_ma) as t12_ma, sum(t12_my) as t12_my,
					sum(t12_tr) as t12_tr, sum(t12_ot) as t12_ot, 
					kode
			from
			(				
				select 	nocab, kode_comp, kode,
						sum(if(blndok = 1, unit, 0)) as b1_all,
						sum(if(blndok = 1 and grup = 'g0501', unit, 0)) as b1_fr,
						sum(if(blndok = 1 and grup = 'g0502', unit, 0)) as b1_ho,
						sum(if(blndok = 1 and grup = 'g0503', unit, 0)) as b1_ma,
						sum(if(blndok = 1 and grup = 'g0504', unit, 0)) as b1_my,
						sum(if(blndok = 1 and grup = 'g0505', unit, 0)) as b1_tr,
						sum(if(blndok = 1 and grup = 'g0506', unit, 0)) as b1_ot,
						count(DISTINCT(if(blndok = 1 and grup = 'g0501', outlet,0)))-1 as t1_fr,
						count(DISTINCT(if(blndok = 1 and grup = 'g0502', outlet,0)))-1 as t1_ho,
						count(DISTINCT(if(blndok = 1 and grup = 'g0503', outlet,0)))-1 as t1_ma,
						count(DISTINCT(if(blndok = 1 and grup = 'g0504', outlet,0)))-1 as t1_my,
						count(DISTINCT(if(blndok = 1 and grup = 'g0505', outlet,0)))-1 as t1_tr,
						count(DISTINCT(if(blndok = 1 and grup = 'g0506', outlet,0)))-1 as t1_ot,
						sum(if(blndok = 2, unit, 0)) as b2_all,
						sum(if(blndok = 2 and grup = 'g0501', unit, 0)) as b2_fr,
						sum(if(blndok = 2 and grup = 'g0502', unit, 0)) as b2_ho,
						sum(if(blndok = 2 and grup = 'g0503', unit, 0)) as b2_ma,
						sum(if(blndok = 2 and grup = 'g0504', unit, 0)) as b2_my,
						sum(if(blndok = 2 and grup = 'g0505', unit, 0)) as b2_tr,
						sum(if(blndok = 2 and grup = 'g0506', unit, 0)) as b2_ot,
						count(DISTINCT(if(blndok = 2 and grup = 'g0501', outlet,0)))-1 as t2_fr,
						count(DISTINCT(if(blndok = 2 and grup = 'g0502', outlet,0)))-1 as t2_ho,
						count(DISTINCT(if(blndok = 2 and grup = 'g0503', outlet,0)))-1 as t2_ma,
						count(DISTINCT(if(blndok = 2 and grup = 'g0504', outlet,0)))-1 as t2_my,
						count(DISTINCT(if(blndok = 2 and grup = 'g0505', outlet,0)))-1 as t2_tr,
						count(DISTINCT(if(blndok = 2 and grup = 'g0506', outlet,0)))-1 as t2_ot,
						sum(if(blndok = 3, unit, 0)) as b3_all,
						sum(if(blndok = 3 and grup = 'g0501', unit, 0)) as b3_fr,
						sum(if(blndok = 3 and grup = 'g0502', unit, 0)) as b3_ho,
						sum(if(blndok = 3 and grup = 'g0503', unit, 0)) as b3_ma,
						sum(if(blndok = 3 and grup = 'g0504', unit, 0)) as b3_my,
						sum(if(blndok = 3 and grup = 'g0505', unit, 0)) as b3_tr,
						sum(if(blndok = 3 and grup = 'g0506', unit, 0)) as b3_ot,
						count(DISTINCT(if(blndok = 3 and grup = 'g0501', outlet,0)))-1 as t3_fr,
						count(DISTINCT(if(blndok = 3 and grup = 'g0502', outlet,0)))-1 as t3_ho,
						count(DISTINCT(if(blndok = 3 and grup = 'g0503', outlet,0)))-1 as t3_ma,
						count(DISTINCT(if(blndok = 3 and grup = 'g0504', outlet,0)))-1 as t3_my,
						count(DISTINCT(if(blndok = 3 and grup = 'g0505', outlet,0)))-1 as t3_tr,
						count(DISTINCT(if(blndok = 3 and grup = 'g0506', outlet,0)))-1 as t3_ot,
						sum(if(blndok = 4, unit, 0)) as b4_all,
						sum(if(blndok = 4 and grup = 'g0501', unit, 0)) as b4_fr,
						sum(if(blndok = 4 and grup = 'g0502', unit, 0)) as b4_ho,
						sum(if(blndok = 4 and grup = 'g0503', unit, 0)) as b4_ma,
						sum(if(blndok = 4 and grup = 'g0504', unit, 0)) as b4_my,
						sum(if(blndok = 4 and grup = 'g0505', unit, 0)) as b4_tr,
						sum(if(blndok = 4 and grup = 'g0506', unit, 0)) as b4_ot,
						count(DISTINCT(if(blndok = 4 and grup = 'g0501', outlet,0)))-1 as t4_fr,
						count(DISTINCT(if(blndok = 4 and grup = 'g0502', outlet,0)))-1 as t4_ho,
						count(DISTINCT(if(blndok = 4 and grup = 'g0503', outlet,0)))-1 as t4_ma,
						count(DISTINCT(if(blndok = 4 and grup = 'g0504', outlet,0)))-1 as t4_my,
						count(DISTINCT(if(blndok = 4 and grup = 'g0505', outlet,0)))-1 as t4_tr,
						count(DISTINCT(if(blndok = 4 and grup = 'g0506', outlet,0)))-1 as t4_ot,
						sum(if(blndok = 5, unit, 0)) as b5_all,
						sum(if(blndok = 5 and grup = 'g0501', unit, 0)) as b5_fr,
						sum(if(blndok = 5 and grup = 'g0502', unit, 0)) as b5_ho,
						sum(if(blndok = 5 and grup = 'g0503', unit, 0)) as b5_ma,
						sum(if(blndok = 5 and grup = 'g0504', unit, 0)) as b5_my,
						sum(if(blndok = 5 and grup = 'g0505', unit, 0)) as b5_tr,
						sum(if(blndok = 5 and grup = 'g0506', unit, 0)) as b5_ot,
						count(DISTINCT(if(blndok = 5 and grup = 'g0501', outlet,0)))-1 as t5_fr,
						count(DISTINCT(if(blndok = 5 and grup = 'g0502', outlet,0)))-1 as t5_ho,
						count(DISTINCT(if(blndok = 5 and grup = 'g0503', outlet,0)))-1 as t5_ma,
						count(DISTINCT(if(blndok = 5 and grup = 'g0504', outlet,0)))-1 as t5_my,
						count(DISTINCT(if(blndok = 5 and grup = 'g0505', outlet,0)))-1 as t5_tr,
						count(DISTINCT(if(blndok = 5 and grup = 'g0506', outlet,0)))-1 as t5_ot,
						sum(if(blndok = 6, unit, 0)) as b6_all,
						sum(if(blndok = 6 and grup = 'g0501', unit, 0)) as b6_fr,
						sum(if(blndok = 6 and grup = 'g0502', unit, 0)) as b6_ho,
						sum(if(blndok = 6 and grup = 'g0503', unit, 0)) as b6_ma,
						sum(if(blndok = 6 and grup = 'g0504', unit, 0)) as b6_my,
						sum(if(blndok = 6 and grup = 'g0505', unit, 0)) as b6_tr,
						sum(if(blndok = 6 and grup = 'g0506', unit, 0)) as b6_ot,
						count(DISTINCT(if(blndok = 6 and grup = 'g0501', outlet,0)))-1 as t6_fr,
						count(DISTINCT(if(blndok = 6 and grup = 'g0502', outlet,0)))-1 as t6_ho,
						count(DISTINCT(if(blndok = 6 and grup = 'g0503', outlet,0)))-1 as t6_ma,
						count(DISTINCT(if(blndok = 6 and grup = 'g0504', outlet,0)))-1 as t6_my,
						count(DISTINCT(if(blndok = 6 and grup = 'g0505', outlet,0)))-1 as t6_tr,
						count(DISTINCT(if(blndok = 6 and grup = 'g0506', outlet,0)))-1 as t6_ot,
						sum(if(blndok = 7, unit, 0)) as b7_all,
						sum(if(blndok = 7 and grup = 'g0501', unit, 0)) as b7_fr,
						sum(if(blndok = 7 and grup = 'g0502', unit, 0)) as b7_ho,
						sum(if(blndok = 7 and grup = 'g0503', unit, 0)) as b7_ma,
						sum(if(blndok = 7 and grup = 'g0504', unit, 0)) as b7_my,
						sum(if(blndok = 7 and grup = 'g0505', unit, 0)) as b7_tr,
						sum(if(blndok = 7 and grup = 'g0506', unit, 0)) as b7_ot,
						count(DISTINCT(if(blndok = 7 and grup = 'g0501', outlet,0)))-1 as t7_fr,
						count(DISTINCT(if(blndok = 7 and grup = 'g0502', outlet,0)))-1 as t7_ho,
						count(DISTINCT(if(blndok = 7 and grup = 'g0503', outlet,0)))-1 as t7_ma,
						count(DISTINCT(if(blndok = 7 and grup = 'g0504', outlet,0)))-1 as t7_my,
						count(DISTINCT(if(blndok = 7 and grup = 'g0505', outlet,0)))-1 as t7_tr,
						count(DISTINCT(if(blndok = 7 and grup = 'g0506', outlet,0)))-1 as t7_ot,
						sum(if(blndok = 8, unit, 0)) as b8_all,
						sum(if(blndok = 8 and grup = 'g0501', unit, 0)) as b8_fr,
						sum(if(blndok = 8 and grup = 'g0502', unit, 0)) as b8_ho,
						sum(if(blndok = 8 and grup = 'g0503', unit, 0)) as b8_ma,
						sum(if(blndok = 8 and grup = 'g0504', unit, 0)) as b8_my,
						sum(if(blndok = 8 and grup = 'g0505', unit, 0)) as b8_tr,
						sum(if(blndok = 8 and grup = 'g0506', unit, 0)) as b8_ot,
						count(DISTINCT(if(blndok = 8 and grup = 'g0501', outlet,0)))-1 as t8_fr,
						count(DISTINCT(if(blndok = 8 and grup = 'g0502', outlet,0)))-1 as t8_ho,
						count(DISTINCT(if(blndok = 8 and grup = 'g0503', outlet,0)))-1 as t8_ma,
						count(DISTINCT(if(blndok = 8 and grup = 'g0504', outlet,0)))-1 as t8_my,
						count(DISTINCT(if(blndok = 8 and grup = 'g0505', outlet,0)))-1 as t8_tr,
						count(DISTINCT(if(blndok = 8 and grup = 'g0506', outlet,0)))-1 as t8_ot,
						sum(if(blndok = 9, unit, 0)) as b9_all,
						sum(if(blndok = 9 and grup = 'g0501', unit, 0)) as b9_fr,
						sum(if(blndok = 9 and grup = 'g0502', unit, 0)) as b9_ho,
						sum(if(blndok = 9 and grup = 'g0503', unit, 0)) as b9_ma,
						sum(if(blndok = 9 and grup = 'g0504', unit, 0)) as b9_my,
						sum(if(blndok = 9 and grup = 'g0505', unit, 0)) as b9_tr,
						sum(if(blndok = 9 and grup = 'g0506', unit, 0)) as b9_ot,
						count(DISTINCT(if(blndok = 9 and grup = 'g0501', outlet,0)))-1 as t9_fr,
						count(DISTINCT(if(blndok = 9 and grup = 'g0502', outlet,0)))-1 as t9_ho,
						count(DISTINCT(if(blndok = 9 and grup = 'g0503', outlet,0)))-1 as t9_ma,
						count(DISTINCT(if(blndok = 9 and grup = 'g0504', outlet,0)))-1 as t9_my,
						count(DISTINCT(if(blndok = 9 and grup = 'g0505', outlet,0)))-1 as t9_tr,
						count(DISTINCT(if(blndok = 9 and grup = 'g0506', outlet,0)))-1 as t9_ot,
						sum(if(blndok = 10, unit, 0)) as b10_all,
						sum(if(blndok = 10 and grup = 'g0501', unit, 0)) as b10_fr,
						sum(if(blndok = 10 and grup = 'g0502', unit, 0)) as b10_ho,
						sum(if(blndok = 10 and grup = 'g0503', unit, 0)) as b10_ma,
						sum(if(blndok = 10 and grup = 'g0504', unit, 0)) as b10_my,
						sum(if(blndok = 10 and grup = 'g0505', unit, 0)) as b10_tr,
						sum(if(blndok = 10 and grup = 'g0506', unit, 0)) as b10_ot,
						count(DISTINCT(if(blndok = 10 and grup = 'g0501', outlet,0)))-1 as t10_fr,
						count(DISTINCT(if(blndok = 10 and grup = 'g0502', outlet,0)))-1 as t10_ho,
						count(DISTINCT(if(blndok = 10 and grup = 'g0503', outlet,0)))-1 as t10_ma,
						count(DISTINCT(if(blndok = 10 and grup = 'g0504', outlet,0)))-1 as t10_my,
						count(DISTINCT(if(blndok = 10 and grup = 'g0505', outlet,0)))-1 as t10_tr,
						count(DISTINCT(if(blndok = 10 and grup = 'g0506', outlet,0)))-1 as t10_ot,
						sum(if(blndok = 11, unit, 0)) as b11_all,
						sum(if(blndok = 11 and grup = 'g0501', unit, 0)) as b11_fr,
						sum(if(blndok = 11 and grup = 'g0502', unit, 0)) as b11_ho,
						sum(if(blndok = 11 and grup = 'g0503', unit, 0)) as b11_ma,
						sum(if(blndok = 11 and grup = 'g0504', unit, 0)) as b11_my,
						sum(if(blndok = 11 and grup = 'g0505', unit, 0)) as b11_tr,
						sum(if(blndok = 11 and grup = 'g0506', unit, 0)) as b11_ot,
						count(DISTINCT(if(blndok = 11 and grup = 'g0501', outlet,0)))-1 as t11_fr,
						count(DISTINCT(if(blndok = 11 and grup = 'g0502', outlet,0)))-1 as t11_ho,
						count(DISTINCT(if(blndok = 11 and grup = 'g0503', outlet,0)))-1 as t11_ma,
						count(DISTINCT(if(blndok = 11 and grup = 'g0504', outlet,0)))-1 as t11_my,
						count(DISTINCT(if(blndok = 11 and grup = 'g0505', outlet,0)))-1 as t11_tr,
						count(DISTINCT(if(blndok = 11 and grup = 'g0506', outlet,0)))-1 as t11_ot,
						sum(if(blndok = 12, unit, 0)) as b12_all,
						sum(if(blndok = 12 and grup = 'g0501', unit, 0)) as b12_fr,
						sum(if(blndok = 12 and grup = 'g0502', unit, 0)) as b12_ho,
						sum(if(blndok = 12 and grup = 'g0503', unit, 0)) as b12_ma,
						sum(if(blndok = 12 and grup = 'g0504', unit, 0)) as b12_my,
						sum(if(blndok = 12 and grup = 'g0505', unit, 0)) as b12_tr,
						sum(if(blndok = 12 and grup = 'g0506', unit, 0)) as b12_ot,
						count(DISTINCT(if(blndok = 12 and grup = 'g0501', outlet,0)))-1 as t12_fr,
						count(DISTINCT(if(blndok = 12 and grup = 'g0502', outlet,0)))-1 as t12_ho,
						count(DISTINCT(if(blndok = 12 and grup = 'g0503', outlet,0)))-1 as t12_ma,
						count(DISTINCT(if(blndok = 12 and grup = 'g0504', outlet,0)))-1 as t12_my,
						count(DISTINCT(if(blndok = 12 and grup = 'g0505', outlet,0)))-1 as t12_tr,
						count(DISTINCT(if(blndok = 12 and grup = 'g0506', outlet,0)))-1 as t12_ot
				FROM
				(
					select 	nocab, kode_comp, kode, unit, a.kodeprod, grup, outlet, blndok 
					FROM
					(
							select 	nocab,kode_comp,blndok,
									tot1 as unit, kodeprod, 
									concat(kode_comp,nocab) as kode, concat(kode_comp,kode_lang) as outlet
							from 	data".$year.".fi
							where 	nodokjdi <> 'XXXXXX' and kodeprod like '06%' 
							union ALL
							select 	nocab,kode_comp,blndok,
									tot1 as unit, kodeprod, 
									concat(kode_comp,nocab) as kode, 0
							from 	data".$year.".ri
							where 	nodokjdi <> 'XXXXXX' and kodeprod like '06%' 
					)a LEFT JOIN 
					(
						select kodeprod, grup 
						from mpm.tabprod
						where supp ='005'
					)b on a.kodeprod = b.kodeprod
				)a GROUP BY kode					 
			)a GROUP BY kode
		)a GROUP BY kode
	)a LEFT JOIN
	(
		select concat(kode_comp, nocab) as kode,naper,kode_comp, nama_comp, sub,urutan,status,status_cluster
		from mpm.tbl_tabcomp_new
		where status = 1
		GROUP BY concat(kode_comp, nocab)
	)b on a.kode = b.kode
	LEFT JOIN
	(
			select 	max(lastupload) as maxupload,
					substring(filename,3,2) naper  
			from 	mpm.upload 
			group by naper
	)c on a.nocab = c.naper
	";      
			
	        $gabung = "insert into mpm.tbl_temp_omzet_us ".$query;			        
	        $sql = $this->db->query($gabung);
	        
	        echo "<pre>";
			print_r($query);			
			echo "</pre>";
			
			/* ============================================================== */
			if ($sql == '1') {
				
				$query="
				select 	nocab, kode_comp, a.sub, b.nama, 
						sum(b1_fr),sum(b1_ho),sum(b1_ma),sum(b1_my),sum(b1_tr),sum(b1_ot),sum(b1_all),
						sum(b2_fr),sum(b2_ho),sum(b2_ma),sum(b2_my),sum(b2_tr),sum(b2_ot),sum(b2_all),
						sum(b3_fr),sum(b3_ho),sum(b3_ma),sum(b3_my),sum(b3_tr),sum(b3_ot),sum(b3_all),
						sum(b4_fr),sum(b4_ho),sum(b4_ma),sum(b4_my),sum(b4_tr),sum(b4_ot),sum(b4_all),
						sum(b5_fr),sum(b5_ho),sum(b5_ma),sum(b5_my),sum(b5_tr),sum(b5_ot),sum(b5_all),
						sum(b6_fr),sum(b6_ho),sum(b6_ma),sum(b6_my),sum(b6_tr),sum(b6_ot),sum(b6_all),
						sum(b7_fr),sum(b7_ho),sum(b7_ma),sum(b7_my),sum(b7_tr),sum(b7_ot),sum(b7_all),
						sum(b8_fr),sum(b8_ho),sum(b8_ma),sum(b8_my),sum(b8_tr),sum(b8_ot),sum(b8_all),
						sum(b9_fr),sum(b9_ho),sum(b9_ma),sum(b9_my),sum(b9_tr),sum(b9_ot),sum(b9_all),
						sum(b10_fr),sum(b10_ho),sum(b10_ma),sum(b10_my),sum(b10_tr),sum(b10_ot),sum(b10_all),
						sum(b11_fr),sum(b11_ho),sum(b11_ma),sum(b11_my),sum(b11_tr),sum(b11_ot),sum(b11_all),
						sum(b12_fr),sum(b12_ho),sum(b12_ma),sum(b12_my),sum(b12_tr),sum(b12_ot),sum(b12_all),
						sum(t1_fr),sum(t1_ho),sum(t1_ma),sum(t1_my),sum(t1_tr),sum(t1_ot),
						sum(t2_fr),sum(t2_ho),sum(t2_ma),sum(t2_my),sum(t2_tr),sum(t2_ot),
						sum(t3_fr),sum(t3_ho),sum(t3_ma),sum(t3_my),sum(t3_tr),sum(t3_ot),
						sum(t4_fr),sum(t4_ho),sum(t4_ma),sum(t4_my),sum(t4_tr),sum(t4_ot),
						sum(t5_fr),sum(t5_ho),sum(t5_ma),sum(t5_my),sum(t5_tr),sum(t5_ot),
						sum(t6_fr),sum(t6_ho),sum(t6_ma),sum(t6_my),sum(t6_tr),sum(t6_ot),
						sum(t7_fr),sum(t7_ho),sum(t7_ma),sum(t7_my),sum(t7_tr),sum(t7_ot),
						sum(t8_fr),sum(t8_ho),sum(t8_ma),sum(t8_my),sum(t8_tr),sum(t8_ot),
						sum(t9_fr),sum(t9_ho),sum(t9_ma),sum(t9_my),sum(t9_tr),sum(t9_ot),
						sum(t10_fr),sum(t10_ho),sum(t10_ma),sum(t10_my),sum(t10_tr),sum(t10_ot),
						sum(t11_fr),sum(t11_ho),sum(t11_ma),sum(t11_my),sum(t11_tr),sum(t11_ot),
						sum(t12_fr),sum(t12_ho),sum(t12_ma),sum(t12_my),sum(t12_tr),sum(t12_ot),
						sum(total_fr),sum(total_ho),sum(total_ma),sum(total_my),sum(total_tr),sum(total_ot), 
						sum(total_all),sum(rata_fr), sum(rata_ho),sum(rata_ma),sum(rata_my),sum(rata_tr),
						sum(rata_ot),sum(rata_all), ".'"'.$supp.'"'.",tgl_created,tahun,b.urutan,b.status,'','$key_jam',id
				
				from 		mpm.tbl_temp_omzet_us a inner JOIN
				(
						select 	naper naper2,status, urutan, nama_comp nama, sub
						from 		mpm.tbl_tabcomp_new
						WHERE 	status = '2' and status_cluster <> '1'
				)b on a.sub = b.sub
				where a.kategori_supplier ='$supp' and a.tahun = $year and a.`key` = '$key_jam'
				GROUP BY sub
				ORDER BY a.urutan asc

				";
				$gabung = "insert into mpm.tbl_temp_omzet_us ".$query;			        
		        $sql = $this->db->query($gabung);
		        /*
		        echo "<pre>";
		        print_r($query);
		        echo "</pre>";
				*/

				if ($sql == '1') {
		        	
					$query = "

					select 	'', '', '', 'GRAND(TOTAL)',
							b1_fr,b1_ho,b1_ma,b1_my,b1_tr,b1_ot,b1_all,
							b2_fr,b2_ho,b2_ma,b2_my,b2_tr,b2_ot,b2_all,
							b3_fr,b3_ho,b3_ma,b3_my,b3_tr,b3_ot,b3_all,
							b4_fr,b4_ho,b4_ma,b4_my,b4_tr,b4_ot,b4_all,
							b5_fr,b5_ho,b5_ma,b5_my,b5_tr,b5_ot,b5_all,
							b6_fr,b6_ho,b6_ma,b6_my,b6_tr,b6_ot,b6_all,
							b7_fr,b7_ho,b7_ma,b7_my,b7_tr,b7_ot,b7_all,
							b8_fr,b8_ho,b8_ma,b8_my,b8_tr,b8_ot,b8_all,
							b9_fr,b9_ho,b9_ma,b9_my,b9_tr,b9_ot,b9_all,
							b10_fr,b10_ho,b10_ma,b10_my,b10_tr,b10_ot,b10_all,
							b11_fr,b11_ho,b11_ma,b11_my,b11_tr,b11_ot,b11_all,
							b12_fr,b12_ho,b12_ma,b12_my,b12_tr,b12_ot,b12_all,							
							t1_fr,t1_ho,t1_ma,t1_my,t1_tr,t1_ot,
							t2_fr,t2_ho,t2_ma,t2_my,t2_tr,t2_ot,
							t3_fr,t3_ho,t3_ma,t3_my,t3_tr,t3_ot,
							t4_fr,t4_ho,t4_ma,t4_my,t4_tr,t4_ot,
							t5_fr,t5_ho,t5_ma,t5_my,t5_tr,t5_ot,
							t6_fr,t6_ho,t6_ma,t6_my,t6_tr,t6_ot,
							t7_fr,t7_ho,t7_ma,t7_my,t7_tr,t7_ot,
							t8_fr,t8_ho,t8_ma,t8_my,t8_tr,t8_ot,
							t9_fr,t9_ho,t9_ma,t9_my,t9_tr,t9_ot,
							t10_fr,t10_ho,t10_ma,t10_my,t10_tr,t10_ot,
							t11_fr,t11_ho,t11_ma,t11_my,t11_tr,t11_ot,
							t12_fr,t12_ho,t12_ma,t12_my,t12_tr,t12_ot,
							total_fr, total_ho,total_ma, total_my,total_tr, total_ot, total_all,
							($totalx_fr)/$rata as rata_fr, 
							($totalx_ho)/$rata as rata_ho,
							($totalx_ma)/$rata as rata_ma, 
							($totalx_my)/$rata as rata_my,
							($totalx_tr)/$rata as rata_tr, 
							($totalx_ot)/$rata as rata_ot,
							($totalx_all)/$rata as rata_all,
						 	".'"'.$supp.'"'.",tgl_created,tahun,'999','3','','$key_jam',id
					from
					(
						select 	sum(b1_fr) as b1_fr,sum(b1_ho) as b1_ho,sum(b1_ma) as b1_ma,sum(b1_my) as b1_my, 
								sum(b1_tr) as b1_tr,sum(b1_ot) as b1_ot,sum(b1_all) as b1_all,
								sum(b2_fr) as b2_fr,sum(b2_ho) as b2_ho,sum(b2_ma) as b2_ma,sum(b2_my) as b2_my, 
								sum(b2_tr) as b2_tr,sum(b2_ot) as b2_ot,sum(b2_all) as b2_all,
								sum(b3_fr) as b3_fr,sum(b3_ho) as b3_ho,sum(b3_ma) as b3_ma,sum(b3_my) as b3_my, 
								sum(b3_tr) as b3_tr,sum(b3_ot) as b3_ot,sum(b3_all) as b3_all,
								sum(b4_fr) as b4_fr,sum(b4_ho) as b4_ho,sum(b4_ma) as b4_ma,sum(b4_my) as b4_my, 
								sum(b4_tr) as b4_tr,sum(b4_ot) as b4_ot,sum(b4_all) as b4_all,
								sum(b5_fr) as b5_fr,sum(b5_ho) as b5_ho,sum(b5_ma) as b5_ma,sum(b5_my) as b5_my, 
								sum(b5_tr) as b5_tr,sum(b5_ot) as b5_ot,sum(b5_all) as b5_all,
								sum(b6_fr) as b6_fr,sum(b6_ho) as b6_ho,sum(b6_ma) as b6_ma,sum(b6_my) as b6_my, 
								sum(b6_tr) as b6_tr,sum(b6_ot) as b6_ot,sum(b6_all) as b6_all,
								sum(b7_fr) as b7_fr,sum(b7_ho) as b7_ho,sum(b7_ma) as b7_ma,sum(b7_my) as b7_my, 
								sum(b7_tr) as b7_tr,sum(b7_ot) as b7_ot,sum(b7_all) as b7_all,
								sum(b8_fr) as b8_fr,sum(b8_ho) as b8_ho,sum(b8_ma) as b8_ma,sum(b8_my) as b8_my, 
								sum(b8_tr) as b8_tr,sum(b8_ot) as b8_ot,sum(b8_all) as b8_all,
								sum(b9_fr) as b9_fr,sum(b9_ho) as b9_ho,sum(b9_ma) as b9_ma,sum(b9_my) as b9_my, 
								sum(b9_tr) as b9_tr,sum(b9_ot) as b9_ot,sum(b9_all) as b9_all,
								sum(b10_fr) as b10_fr,sum(b10_ho) as b10_ho,sum(b10_ma) as b10_ma,sum(b10_my) as b10_my, 
								sum(b10_tr) as b10_tr,sum(b10_ot) as b10_ot,sum(b10_all) as b10_all,
								sum(b11_fr) as b11_fr,sum(b11_ho) as b11_ho,sum(b11_ma) as b11_ma,sum(b11_my) as b11_my, 
								sum(b11_tr) as b11_tr,sum(b11_ot) as b11_ot,sum(b11_all) as b11_all,
								sum(b12_fr) as b12_fr,sum(b12_ho) as b12_ho,sum(b12_ma) as b12_ma,sum(b12_my) as b12_my, 
								sum(b12_tr) as b12_tr,sum(b12_ot) as b12_ot,sum(b12_all) as b12_all,
								sum(t1_fr) as t1_fr,sum(t1_ho) as t1_ho,sum(t1_ma) as t1_ma,sum(t1_my) as t1_my, 
								sum(t1_tr) as t1_tr,sum(t1_ot) as t1_ot,
								sum(t2_fr) as t2_fr,sum(t2_ho) as t2_ho,sum(t2_ma) as t2_ma,sum(t2_my) as t2_my, 
								sum(t2_tr) as t2_tr,sum(t2_ot) as t2_ot,
								sum(t3_fr) as t3_fr,sum(t3_ho) as t3_ho,sum(t3_ma) as t3_ma,sum(t3_my) as t3_my, 
								sum(t3_tr) as t3_tr,sum(t3_ot) as t3_ot,
								sum(t4_fr) as t4_fr,sum(t4_ho) as t4_ho,sum(t4_ma) as t4_ma,sum(t4_my) as t4_my, 
								sum(t4_tr) as t4_tr,sum(t4_ot) as t4_ot,
								sum(t5_fr) as t5_fr,sum(t5_ho) as t5_ho,sum(t5_ma) as t5_ma,sum(t5_my) as t5_my, 
								sum(t5_tr) as t5_tr,sum(t5_ot) as t5_ot,
								sum(t6_fr) as t6_fr,sum(t6_ho) as t6_ho,sum(t6_ma) as t6_ma,sum(t6_my) as t6_my, 
								sum(t6_tr) as t6_tr,sum(t6_ot) as t6_ot,
								sum(t7_fr) as t7_fr,sum(t7_ho) as t7_ho,sum(t7_ma) as t7_ma,sum(t7_my) as t7_my, 
								sum(t7_tr) as t7_tr,sum(t7_ot) as t7_ot,
								sum(t8_fr) as t8_fr,sum(t8_ho) as t8_ho,sum(t8_ma) as t8_ma,sum(t8_my) as t8_my, 
								sum(t8_tr) as t8_tr,sum(t8_ot) as t8_ot,
								sum(t9_fr) as t9_fr,sum(t9_ho) as t9_ho,sum(t9_ma) as t9_ma,sum(t9_my) as t9_my, 
								sum(t9_tr) as t9_tr,sum(t9_ot) as t9_ot,
								sum(t10_fr) as t10_fr,sum(t10_ho) as t10_ho,sum(t10_ma) as t10_ma,sum(t10_my) as t10_my, 
								sum(t10_tr) as t10_tr,sum(t10_ot) as t10_ot,
								sum(t11_fr) as t11_fr,sum(t11_ho) as t11_ho,sum(t11_ma) as t11_ma,sum(t11_my) as t11_my, 
								sum(t11_tr) as t11_tr,sum(t11_ot) as t11_ot,
								sum(t12_fr) as t12_fr,sum(t12_ho) as t12_ho,sum(t12_ma) as t12_ma,sum(t12_my) as t12_my, 
								sum(t12_tr) as t12_tr,sum(t12_ot) as t12_ot,
								sum(total_fr) as total_fr, sum(total_ho) as total_ho, sum(total_ma) as total_ma, 
								sum(total_my) as total_my, sum(total_tr) as total_tr, sum(total_ot) as total_ot, 
								sum(total_all) as total_all,								
						 		".'"'.$supp.'"'.",tgl_created,tahun,'999','3','','$key_jam', id
						from 	mpm.tbl_temp_omzet_us a 
						where 	status = 1 and kategori_supplier ='$supp' and tahun = $year and a.key = '$key_jam'
					)a
					";

					$gabung = "insert into mpm.tbl_temp_omzet_us ".$query;			        
			        $sql = $this->db->query($gabung);
			        /*
			        echo "<pre>";
			        print_r($gabung);
			        echo "</pre>";
					*/
					if ($sql == '1') {
			        	
			        	$query="
							insert into mpm.tbl_temporary_user (userid,menuid,nilai,datetime,supp,tahun) 
	            			values ($id,$menuid,$total,$tgl_created,'$supp$group','$year')
						";
							        
				        $sql = $this->db->query($query);

			        }else{
			        	echo "ada kesalahan <i>Proses insert temp_omzet_grand_total</i>. silahkan ulangi";
			        }

		        }else{
		        	echo "ada kesalahan <i>Proses insert temp_omzet_sub_total</i>. silahkan ulangi";
		        }

			}else{

				echo "ada kesalahan <i>Proses insert temp_omzet_per_sub_branch</i>. silahkan ulangi";
			}

			$gabung = "
				insert into mpm.omzet_new_us
				select 	*
				from 	mpm.tbl_temp_omzet_us
				where 	kategori_supplier = ".'"'.$supp.'"'." and tahun = $year and `key` = '$key_jam'
				";			        
	        $sql = $this->db->query($gabung);
	        /*
	        echo "<pre>";
	        print_r($gabung);
	        echo "</pre>";
			*/

			if ($sql == '1') {
	        	
	        	if ($dp == NULL || $dp == '') {
		        	
				$query="
							select 	* 
							from 	mpm.omzet_new_us
							where 	tahun = $year and
									kategori_supplier = '$supp' and
									`key` = (
											select 	max(`key`)
											from 	mpm.omzet_new_us
											where 	tahun = $year and
													kategori_supplier = '$supp'
							)
							ORDER BY urutan asc

						";
					/*
					echo "<pre>";
					print_r($query);
					echo "</pre>";
					*/
							        
			        $hasil = $this->db->query($query);
			        if ($hasil->num_rows() > 0) 
					{
						return $hasil->result();
					} else {
						return array();
					}

					/* END PROSES TAMPIL KE WEBSITE */

	        	} else {
		     
					$query="
							select 	*
							from 		mpm.omzet_new_us
							where 		tahun = $year and
										kategori_supplier = '$supp' and
										naper in ($daftar_dp) and
										`key` = (
											select 	max(`key`)
											from 	mpm.omzet_new_us
											where 	tahun = $year and
													kategori_supplier = '$supp'
							)
							ORDER BY urutan asc
						";
							        
			        $hasil = $this->db->query($query);
			        if ($hasil->num_rows() > 0) 
					{
						return $hasil->result();
					} else {
						return array();
					}
					/* END PROSES TAMPIL KE WEBSITE */
	        	}

	        }else{
	        	echo "ada kesalahan <i>Proses insert omzet_new</i>. silahkan ulangi";
	        }
									
        }
	    
	}

	public function omzet_all_dp_group_beverages($dataSegment){

	/* ---------DEFINISI VARIABEL----------------- */		    
	    $tgl_created='"'.date('Y-m-d H:i:s').'"';
	    $jam=date('ymdHis');
	    $key_jam = $jam;
	    $id=$this->session->userdata('id');
		$year = $dataSegment['year'];
		$group = $dataSegment['group'];	
		$supp = $dataSegment['supp'];
		$uri1 = $dataSegment['menu_uri1'];
		$uri2 = $dataSegment['menu_uri2'];
		$uri3 = $dataSegment['menu_uri3'];
		$menuid = $dataSegment['menuid'];
	
	/* ---------Hak Lihat DP----------------- */		    
		$dp = $dataSegment['query_dp'];        
        if ($dp == NULL || $dp == '') {	$daftar_dp = ''; } else { $daftar_dp = $dp; }

    /* ---------Cari Total FI----------------- */
    	$this->db->select('count(*) as rows');
        $query = $this->db->get("data$year.fi");
        foreach($query->result() as $r)
        {
           $total = $r->rows;
        }	
    
    /* ---------Cari histori di temporary user----------------- */
	    $sql="
	    	select 	1 
	    	from 	mpm.tbl_temporary_user 
	    	where   menuid = $menuid and
	    			supp = '$supp$group' and
	    			tahun = '$year' and 
	    			nilai = $total
	    			";
	    $query = $this->db->query($sql);
        //echo "<pre>";
        //print_r($sql);
        //echo "</pre>";
        if($query->num_rows()>0) //kalau histori ditemukan
        {        	
			if ($dp == NULL || $dp == '') // kalau boleh lihat semua DP
			{	
				$query="
					select 	*
					from 		mpm.omzet_new_beverages
					where 		tahun = $year and
								kategori_supplier = '$supp' and
								`key` = (
									select 	max(`key`)
									from 	mpm.omzet_new_beverages
									where 	tahun = $year and
											kategori_supplier = '$supp'
									)
					ORDER BY urutan asc
				";
				    
		        $hasil = $this->db->query($query);
		        if ($hasil->num_rows() > 0) 
				{ return $hasil->result(); } else { return array(); }

	        } else {
		        	
					$query="
							select 	*
							from 		mpm.omzet_new_beverages
							where 		tahun = $year and
										kategori_supplier = '$supp' and
										naper in ($daftar_dp) and
										`key` = (
											select 	max(`key`)
											from 	mpm.omzet_new_beverages
											where 	tahun = $year and
													kategori_supplier = '$supp'
										)
							ORDER BY urutan asc
						"; 
			        $hasil = $this->db->query($query);
		        if ($hasil->num_rows() > 0) 
				{ return $hasil->result(); } else { return array(); }

					/* END PROSES TAMPIL KE WEBSITE */
	        }				
        }else{ //kalau histori tidak ditemukan

    	/* === Proses pendefinisian bulan, pembagi untuk menentukan total dan rata2 === */				
			$bulan = date("m");
			$tahunsekarang = date("Y");
				if ($year == $tahunsekarang) //jika memilih tahun berjalan
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
						$totalx = 'b1+b2+b3+b4+b5+b6+b7+b8+b9+b10+b11+b12';
						$pembagi = 'xb1+xb2+xb3+xb4+xb5+xb6+xb7+xb8+xb9+xb10+xb11+xb12';
						$rata = 12;
				}
				/* ========================================== */

				/* == PROSES DELETE MPM.OMZET_NEW ===== */
				$query = "	
						delete from mpm.omzet_new_beverages
						where kategori_supplier = ".'"'.$supp.'"'." and tahun = $year and id = $id
							";
				//echo "<pre>";
				//print_r($query);
				//echo "</pre>";
				$sql = $this->db->query($query);

				/* == PROSES DELETE mpm.tbl_temp_omzet = */
				$query = "	
						delete from mpm.tbl_temp_omzet_beverages
						where kategori_supplier = ".'"'.$supp.'"'." and tahun = $year and and id = $id
							";
				$sql = $this->db->query($query);

				/* ========== PROSES INSERT KE mpm.tbl_temp_omzet =================== */
			
$query = "	   
	select  a.nocab, a.kode_comp, b.sub, b.nama_comp,
			t1,b1,t2,b2,t3,b3,t4,b4,t5,b5,
			t6,b6,t7,b7,t8,b8,t9,b9,t10,b10,
			t11,b11,t12,b12,".$id.",total,totalx/jmlbulan rata,
			".'"'.$supp.'"'.",".$tgl_created.",".$year.", urutan, status, maxupload, '$key_jam'
	FROM
	(
		select 	nocab,kode_comp,kode,
				b1, b2, b3, b4, b5, b6, b7, b8, b9, b10, b11, b12,
				t1, t2, t3, t4,	t5, t6, t7, t8,	t9, t10, t11, t12, ".$id.",
				total, totalx, format(sum($pembagi),0, 1) jmlbulan
		FROM
		(
			select  nocab,kode_comp, 
					b1, b2, b3, b4, b5, b6, b7, b8, b9, b10, b11, b12,
					".$id.",
					b1+b2+b3+b4+b5+b6+b7+b8+b9+b10+b11+b12 as total,
					$totalx as totalx,
					IF(b1 = 0, 0, 1) xb1, 
					IF(b2 = 0, 0, 1) xb2, 
					IF(b3 = 0, 0, 1) xb3, 
					IF(b4 = 0, 0, 1) xb4, 
					IF(b5 = 0, 0, 1) xb5, 
					IF(b6 = 0, 0, 1) xb6, 
					IF(b7 = 0, 0, 1) xb7, 
					IF(b8 = 0, 0, 1) xb8, 
					IF(b9 = 0, 0, 1) xb9, 
					IF(b10 = 0, 0, 1) xb10,
					IF(b11 = 0, 0, 1) xb11, 
					IF(b12 = 0, 0, 1) xb12, 
					sum(t1) as t1, 
					sum(t2) as t2,  
					sum(t3) as t3, 
					sum(t4) as t4,  
					sum(t5) as t5,  
					sum(t6) as t6,  
					sum(t7) as t7, 
					sum(t8) as t8,  
					sum(t9) as t9, 
					sum(t10) as t10, 
					sum(t11) as t11, 
					sum(t12) as t12,
					kode
			from
			(				
				select 	nocab, kode_comp, kode,
						sum(if(blndok = 1 and grup = 'G0103', unit, 0)) as b1,
						count(DISTINCT(if(blndok = 1 and grup = 'G0103', outlet,0)))-1 as t1,
						sum(if(blndok = 2 and grup = 'G0103', unit, 0)) as b2,
						count(DISTINCT(if(blndok = 2 and grup = 'G0103', outlet,0)))-1 as t2,
						sum(if(blndok = 3 and grup = 'G0103', unit, 0)) as b3,
						count(DISTINCT(if(blndok = 3 and grup = 'G0103', outlet,0)))-1 as t3,
						sum(if(blndok = 4 and grup = 'G0103', unit, 0)) as b4,
						count(DISTINCT(if(blndok = 4 and grup = 'G0103', outlet,0)))-1 as t4,
						sum(if(blndok = 5 and grup = 'G0103', unit, 0)) as b5,
						count(DISTINCT(if(blndok = 5 and grup = 'G0103', outlet,0)))-1 as t5,
						sum(if(blndok = 6 and grup = 'G0103', unit, 0)) as b6,
						count(DISTINCT(if(blndok = 6 and grup = 'G0103', outlet,0)))-1 as t6,
						sum(if(blndok = 7 and grup = 'G0103', unit, 0)) as b7,
						count(DISTINCT(if(blndok = 7 and grup = 'G0103', outlet,0)))-1 as t7,
						sum(if(blndok = 8 and grup = 'G0103', unit, 0)) as b8,
						count(DISTINCT(if(blndok = 8 and grup = 'G0103', outlet,0)))-1 as t8,
						sum(if(blndok = 9 and grup = 'G0103', unit, 0)) as b9,
						count(DISTINCT(if(blndok = 9 and grup = 'G0103', outlet,0)))-1 as t9,
						sum(if(blndok = 10 and grup = 'G0103', unit, 0)) as b10,
						count(DISTINCT(if(blndok = 10 and grup = 'G0103', outlet,0)))-1 as t10,
						sum(if(blndok = 11 and grup = 'G0103', unit, 0)) as b11,
						count(DISTINCT(if(blndok = 11 and grup = 'G0103', outlet,0)))-1 as t11,
						sum(if(blndok = 12 and grup = 'G0103', unit, 0)) as b12,
						count(DISTINCT(if(blndok = 12 and grup = 'G0103', outlet,0)))-1 as t12
				FROM
				(
					select 	nocab, kode_comp, kode, unit, a.kodeprod, grup, outlet, blndok 
					FROM
					(
							select 	nocab,kode_comp,blndok,
									tot1 as unit, kodeprod, 
									concat(kode_comp,nocab) as kode, concat(kode_comp,kode_lang) as outlet
							from 	data".$year.".fi
							where 	nodokjdi <> 'XXXXXX' and kodeprod like '01%' 
							union ALL
							select 	nocab,kode_comp,blndok,
									tot1 as unit, kodeprod, 
									concat(kode_comp,nocab) as kode, 0
							from 	data".$year.".ri
							where 	nodokjdi <> 'XXXXXX' and kodeprod like '01%' 
					)a LEFT JOIN 
					(
						select kodeprod, grup 
						from mpm.tabprod
						where supp ='001'
					)b on a.kodeprod = b.kodeprod
				)a GROUP BY kode					 
			)a GROUP BY kode
		)a GROUP BY kode
	)a LEFT JOIN
	(
		select concat(kode_comp, nocab) as kode,naper,kode_comp, nama_comp, sub,urutan,status,status_cluster
		from mpm.tbl_tabcomp_new
		where status = 1
		GROUP BY concat(kode_comp, nocab)
	)b on a.kode = b.kode
	LEFT JOIN
	(
			select 	max(lastupload) as maxupload,
					substring(filename,3,2) naper  
			from 	mpm.upload 
			group by naper
	)c on a.nocab = c.naper
	";      
			
	        $gabung = "insert into mpm.tbl_temp_omzet_beverages ".$query;			        
	        $sql = $this->db->query($gabung);
	        
	        //echo "<pre>";
			//print_r($query);			
			//echo "</pre>";
			
			/* ============================================================== */
			if ($sql == '1') {
				
				$query="
				select 	nocab, kode_comp, a.sub, b.nama, 
						sum(b1),sum(b2),sum(b3),sum(b4),sum(b5),
						sum(b6),sum(b7),sum(b8),sum(b9),sum(b10),sum(b11),sum(b12),
						sum(t1),sum(t2),sum(t3),sum(t4),sum(t5),sum(t6),sum(t7),
						sum(t8),sum(t9),sum(t10), sum(t11), sum(t12), sum(total), 
						sum(rata), ".'"'.$supp.'"'.",tgl_created,tahun,b.urutan,b.status,'','$key_jam',id
				
				from 	mpm.tbl_temp_omzet_beverages a inner JOIN
				(
						select 	naper naper2,status, urutan, nama_comp nama, sub
						from 		mpm.tbl_tabcomp_new
						WHERE 	status = '2' and status_cluster <> '1'
				)b on a.sub = b.sub
				where a.kategori_supplier ='$supp' and a.tahun = $year and a.`key` = '$key_jam'
				GROUP BY sub
				ORDER BY a.urutan asc

				";
				$gabung = "insert into mpm.tbl_temp_omzet_beverages ".$query;			        
		        $sql = $this->db->query($gabung);

		        //echo "<pre>";
		        //print_r($query);
		        //echo "</pre>";


				if ($sql == '1') {
		        	
					$query = "
					select 	'', '', '', 'GRAND(TOTAL)',
							b1,	b2,	b3,	b4, b5, b6,
							b7,	b8,	b9,	b10, b11, b12,						
							t1,	t2,	t3,	t4,	t5,	t6,
							t7,	t8,	t9,	t10, t11, t12, total, 
							($totalx)/$rata as rata, 
						 	".'"'.$supp.'"'.",tgl_created,tahun,'999','3','','$key_jam',id
					from
					(
						select 	sum(b1) as b1,sum(b2) as b2,sum(b3) as b3,sum(b4) as b4,
								sum(b5) as b5,sum(b6) as b6,sum(b7) as b7,sum(b8) as b8,
								sum(b9) as b9,sum(b10) as b10,sum(b11) as b11,
								sum(b12) as b12,
								sum(t1) as t1,sum(t2) as t2,sum(t3) as t3,sum(t4) as t4,
								sum(t5) as t5,sum(t6) as t6,sum(t7) as t7,sum(t8) as t8,
								sum(t9) as t9,sum(t10) as t10,sum(t11) as t11,
								sum(t12) as t12,sum(total) as total, 
						 		".'"'.$supp.'"'.",tgl_created,tahun,'999','3','','$key_jam', id
						from 	mpm.tbl_temp_omzet_beverages a 
						where 	status = 1 and kategori_supplier ='$supp' and tahun = $year and a.key = '$key_jam'
					)a
					";

					$gabung = "insert into mpm.tbl_temp_omzet_beverages ".$query;			        
			        $sql = $this->db->query($gabung);
			        
			        //echo "<pre>";
			        //print_r($gabung);
			        //echo "</pre>";
					
					if ($sql == '1') {
			        	
			        	$query="
							insert into mpm.tbl_temporary_user (userid,menuid,nilai,datetime,supp,tahun) 
	            			values ($id,$menuid,$total,$tgl_created,'$supp$group','$year')
						";
							        
				        $sql = $this->db->query($query);

			        }else{
			        	echo "ada kesalahan <i>Proses insert temp_omzet_grand_total</i>. silahkan ulangi";
			        }

		        }else{
		        	echo "ada kesalahan <i>Proses insert temp_omzet_sub_total</i>. silahkan ulangi";
		        }

			}else{

				echo "ada kesalahan <i>Proses insert temp_omzet_per_sub_branch</i>. silahkan ulangi";
			}

			$gabung = "
				insert into mpm.omzet_new_beverages
				select 	nocab, sub, kode_comp, nama_comp, 
						t1,b1,t2,b2,t3,b3,t4,b4,t5,b5,t6,b6,t7,b7,t8,b8,t9,b9,t10,b10,t11,b11,t12,b12,id,total,rata,kategori_supplier,tgl_created,
						tahun, urutan, lastupload, `key`
				from 	mpm.tbl_temp_omzet_beverages
				where 	kategori_supplier = ".'"'.$supp.'"'." and tahun = $year and `key` = '$key_jam'
				";			        
	        $sql = $this->db->query($gabung);

	        //echo "<pre>";
	        //print_r($gabung);
	        //echo "</pre>";


			if ($sql == '1') {
	        	
	        	if ($dp == NULL || $dp == '') {
		        	
				$query="
							select 	* 
							from 	mpm.omzet_new_beverages
							where 	tahun = $year and
									kategori_supplier = '$supp' and
									`key` = (
											select 	max(`key`)
											from 	mpm.omzet_new_beverages
											where 	tahun = $year and
													kategori_supplier = '$supp'
							)
							ORDER BY urutan asc

						";
					/*
					echo "<pre>";
					print_r($query);
					echo "</pre>";
					*/
							        
			        $hasil = $this->db->query($query);
			        if ($hasil->num_rows() > 0) 
					{
						return $hasil->result();
					} else {
						return array();
					}

					/* END PROSES TAMPIL KE WEBSITE */

	        	} else {
		     
					$query="
							select 	*
							from 		mpm.omzet_new_beverages
							where 		tahun = $year and
										kategori_supplier = '$supp' and
										naper in ($daftar_dp) and
										`key` = (
											select 	max(`key`)
											from 	mpm.omzet_new_beverages
											where 	tahun = $year and
													kategori_supplier = '$supp'
							)
							ORDER BY urutan asc
						";
							        
			        $hasil = $this->db->query($query);
			        if ($hasil->num_rows() > 0) 
					{
						return $hasil->result();
					} else {
						return array();
					}
					/* END PROSES TAMPIL KE WEBSITE */
	        	}

	        }else{
	        	echo "ada kesalahan <i>Proses insert omzet_new</i>. silahkan ulangi";
	        }
									
        }
	    
	}


	public function omzet_all_dp_group_pilkita($dataSegment){

	/* ---------DEFINISI VARIABEL----------------- */		    
	    $tgl_created='"'.date('Y-m-d H:i:s').'"';
	    $jam=date('ymdHis');
	    $key_jam = $jam;
	    $id=$this->session->userdata('id');
		$year = $dataSegment['year'];
		$group = $dataSegment['group'];	
		$supp = $dataSegment['supp'];
		$uri1 = $dataSegment['menu_uri1'];
		$uri2 = $dataSegment['menu_uri2'];
		$uri3 = $dataSegment['menu_uri3'];
		$menuid = $dataSegment['menuid'];
	
	/* ---------Hak Lihat DP----------------- */		    
		$dp = $dataSegment['query_dp'];        
        if ($dp == NULL || $dp == '') {	$daftar_dp = ''; } else { $daftar_dp = $dp; }

    /* ---------Cari Total FI----------------- */
    	$this->db->select('count(*) as rows');
        $query = $this->db->get("data$year.fi");
        foreach($query->result() as $r)
        {
           $total = $r->rows;
        }	
    
    /* ---------Cari histori di temporary user----------------- */
	    $sql="
	    	select 	1 
	    	from 	mpm.tbl_temporary_user 
	    	where   menuid = $menuid and
	    			supp = '$supp$group' and
	    			tahun = '$year' and 
	    			nilai = $total
	    			";
	    $query = $this->db->query($sql);
        //echo "<pre>";
        //print_r($sql);
        //echo "</pre>";
        if($query->num_rows()>0) //kalau histori ditemukan
        {        	
			if ($dp == NULL || $dp == '') // kalau boleh lihat semua DP
			{	
				$query="
					select 	*
					from 		mpm.omzet_new_pilkita
					where 		tahun = $year and
								kategori_supplier = '$supp' and
								`key` = (
									select 	max(`key`)
									from 	mpm.omzet_new_pilkita
									where 	tahun = $year and
											kategori_supplier = '$supp'
									)
					ORDER BY urutan asc
				";
				    
		        $hasil = $this->db->query($query);
		        if ($hasil->num_rows() > 0) 
				{ return $hasil->result(); } else { return array(); }

	        } else {
		        	
					$query="
							select 	*
							from 		mpm.omzet_new_pilkita
							where 		tahun = $year and
										kategori_supplier = '$supp' and
										naper in ($daftar_dp) and
										`key` = (
											select 	max(`key`)
											from 	mpm.omzet_new_pilkita
											where 	tahun = $year and
													kategori_supplier = '$supp'
										)
							ORDER BY urutan asc
						"; 
			        $hasil = $this->db->query($query);
		        if ($hasil->num_rows() > 0) 
				{ return $hasil->result(); } else { return array(); }

					/* END PROSES TAMPIL KE WEBSITE */
	        }				
        }else{ //kalau histori tidak ditemukan

    	/* === Proses pendefinisian bulan, pembagi untuk menentukan total dan rata2 === */				
			$bulan = date("m");
			$tahunsekarang = date("Y");
				if ($year == $tahunsekarang) //jika memilih tahun berjalan
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
						$totalx = 'b1+b2+b3+b4+b5+b6+b7+b8+b9+b10+b11+b12';
						$pembagi = 'xb1+xb2+xb3+xb4+xb5+xb6+xb7+xb8+xb9+xb10+xb11+xb12';
						$rata = 12;
				}
				/* ========================================== */

				/* == PROSES DELETE MPM.OMZET_NEW ===== */
				$query = "	
						delete from mpm.omzet_new_pilkita
						where kategori_supplier = ".'"'.$supp.'"'." and tahun = $year and id = $id
							";
				//echo "<pre>";
				//print_r($query);
				//echo "</pre>";
				$sql = $this->db->query($query);

				/* == PROSES DELETE mpm.tbl_temp_omzet = */
				$query = "	
						delete from mpm.tbl_temp_omzet_pilkita
						where kategori_supplier = ".'"'.$supp.'"'." and tahun = $year and and id = $id
							";
				$sql = $this->db->query($query);

				/* ========== PROSES INSERT KE mpm.tbl_temp_omzet =================== */
			
$query = "	   
	select  a.nocab, a.kode_comp, b.sub, b.nama_comp,
			t1,b1,t2,b2,t3,b3,t4,b4,t5,b5,
			t6,b6,t7,b7,t8,b8,t9,b9,t10,b10,
			t11,b11,t12,b12,".$id.",total,totalx/jmlbulan rata,
			".'"'.$supp.'"'.",".$tgl_created.",".$year.", urutan, status, maxupload, '$key_jam'
	FROM
	(
		select 	nocab,kode_comp,kode,
				b1, b2, b3, b4, b5, b6, b7, b8, b9, b10, b11, b12,
				t1, t2, t3, t4,	t5, t6, t7, t8,	t9, t10, t11, t12, ".$id.",
				total, totalx, format(sum($pembagi),0, 1) jmlbulan
		FROM
		(
			select  nocab,kode_comp, 
					b1, b2, b3, b4, b5, b6, b7, b8, b9, b10, b11, b12,
					".$id.",
					b1+b2+b3+b4+b5+b6+b7+b8+b9+b10+b11+b12 as total,
					$totalx as totalx,
					IF(b1 = 0, 0, 1) xb1, 
					IF(b2 = 0, 0, 1) xb2, 
					IF(b3 = 0, 0, 1) xb3, 
					IF(b4 = 0, 0, 1) xb4, 
					IF(b5 = 0, 0, 1) xb5, 
					IF(b6 = 0, 0, 1) xb6, 
					IF(b7 = 0, 0, 1) xb7, 
					IF(b8 = 0, 0, 1) xb8, 
					IF(b9 = 0, 0, 1) xb9, 
					IF(b10 = 0, 0, 1) xb10,
					IF(b11 = 0, 0, 1) xb11, 
					IF(b12 = 0, 0, 1) xb12, 
					sum(t1) as t1, 
					sum(t2) as t2,  
					sum(t3) as t3, 
					sum(t4) as t4,  
					sum(t5) as t5,  
					sum(t6) as t6,  
					sum(t7) as t7, 
					sum(t8) as t8,  
					sum(t9) as t9, 
					sum(t10) as t10, 
					sum(t11) as t11, 
					sum(t12) as t12,
					kode
			from
			(				
				select 	nocab, kode_comp, kode,
						sum(if(blndok = 1 and grup = 'G0201', unit, 0)) as b1,
						count(DISTINCT(if(blndok = 1 and grup = 'G0201', outlet,0)))-1 as t1,
						sum(if(blndok = 2 and grup = 'G0201', unit, 0)) as b2,
						count(DISTINCT(if(blndok = 2 and grup = 'G0201', outlet,0)))-1 as t2,
						sum(if(blndok = 3 and grup = 'G0201', unit, 0)) as b3,
						count(DISTINCT(if(blndok = 3 and grup = 'G0201', outlet,0)))-1 as t3,
						sum(if(blndok = 4 and grup = 'G0201', unit, 0)) as b4,
						count(DISTINCT(if(blndok = 4 and grup = 'G0201', outlet,0)))-1 as t4,
						sum(if(blndok = 5 and grup = 'G0201', unit, 0)) as b5,
						count(DISTINCT(if(blndok = 5 and grup = 'G0201', outlet,0)))-1 as t5,
						sum(if(blndok = 6 and grup = 'G0201', unit, 0)) as b6,
						count(DISTINCT(if(blndok = 6 and grup = 'G0201', outlet,0)))-1 as t6,
						sum(if(blndok = 7 and grup = 'G0201', unit, 0)) as b7,
						count(DISTINCT(if(blndok = 7 and grup = 'G0201', outlet,0)))-1 as t7,
						sum(if(blndok = 8 and grup = 'G0201', unit, 0)) as b8,
						count(DISTINCT(if(blndok = 8 and grup = 'G0201', outlet,0)))-1 as t8,
						sum(if(blndok = 9 and grup = 'G0201', unit, 0)) as b9,
						count(DISTINCT(if(blndok = 9 and grup = 'G0201', outlet,0)))-1 as t9,
						sum(if(blndok = 10 and grup = 'G0201', unit, 0)) as b10,
						count(DISTINCT(if(blndok = 10 and grup = 'G0201', outlet,0)))-1 as t10,
						sum(if(blndok = 11 and grup = 'G0201', unit, 0)) as b11,
						count(DISTINCT(if(blndok = 11 and grup = 'G0201', outlet,0)))-1 as t11,
						sum(if(blndok = 12 and grup = 'G0201', unit, 0)) as b12,
						count(DISTINCT(if(blndok = 12 and grup = 'G0201', outlet,0)))-1 as t12
				FROM
				(
					select 	nocab, kode_comp, kode, unit, a.kodeprod, grup, outlet, blndok 
					FROM
					(
							select 	nocab,kode_comp,blndok,
									tot1 as unit, kodeprod, 
									concat(kode_comp,nocab) as kode, concat(kode_comp,kode_lang) as outlet
							from 	data".$year.".fi
							where 	nodokjdi <> 'XXXXXX' and kodeprod like '02%' 
							union ALL
							select 	nocab,kode_comp,blndok,
									tot1 as unit, kodeprod, 
									concat(kode_comp,nocab) as kode, 0
							from 	data".$year.".ri
							where 	nodokjdi <> 'XXXXXX' and kodeprod like '02%' 
					)a LEFT JOIN 
					(
						select kodeprod, grup 
						from mpm.tabprod
						where supp ='002'
					)b on a.kodeprod = b.kodeprod
				)a GROUP BY kode					 
			)a GROUP BY kode
		)a GROUP BY kode
	)a LEFT JOIN
	(
		select concat(kode_comp, nocab) as kode,naper,kode_comp, nama_comp, sub,urutan,status,status_cluster
		from mpm.tbl_tabcomp_new
		where status = 1
		GROUP BY concat(kode_comp, nocab)
	)b on a.kode = b.kode
	LEFT JOIN
	(
			select 	max(lastupload) as maxupload,
					substring(filename,3,2) naper  
			from 	mpm.upload 
			group by naper
	)c on a.nocab = c.naper
	";      
			
	        $gabung = "insert into mpm.tbl_temp_omzet_pilkita ".$query;			        
	        $sql = $this->db->query($gabung);
	        
	        //echo "<pre>";
			//print_r($query);			
			//echo "</pre>";
			
			/* ============================================================== */
			if ($sql == '1') {
				
				$query="
				select 	nocab, kode_comp, a.sub, b.nama, 
						sum(t1),sum(b1),sum(t2),sum(b2),sum(t3),sum(b3),sum(t4),sum(b4),sum(t5),sum(b5),
						sum(t6),sum(b6),sum(t7),sum(b7),sum(t8),sum(b8),sum(t9),sum(b9),sum(t10),sum(b10),sum(t11),sum(b11),
						sum(t12),sum(b12),id,sum(total),sum(rata), ".'"'.$supp.'"'.",tgl_created,tahun,b.urutan,b.status,'','$key_jam'
				
				from 	mpm.tbl_temp_omzet_pilkita a inner JOIN
				(
						select 	naper naper2,status, urutan, nama_comp nama, sub
						from 		mpm.tbl_tabcomp_new
						WHERE 	status = '2' and status_cluster <> '1'
				)b on a.sub = b.sub
				where a.kategori_supplier ='$supp' and a.tahun = $year and a.`key` = '$key_jam'
				GROUP BY sub
				ORDER BY a.urutan asc

				";
				$gabung = "insert into mpm.tbl_temp_omzet_pilkita ".$query;			        
		        $sql = $this->db->query($gabung);

		        //echo "<pre>";
		        //print_r($query);
		        //echo "</pre>";


				if ($sql == '1') {
		        	
					$query = "
					select 	'', '', '', 'GRAND(TOTAL)',
							t1,b1,t2,b2,t3,b3,t4,b4,t5,b5,t6,b6,
							t7,b7,t8,b8,t9,b9,t10,b10,t11,b11,t12,b12,id,																
							total, ($totalx)/$rata as rata, 
						 	".'"'.$supp.'"'.",tgl_created,tahun,'999','3','','$key_jam'
					from
					(
						select 	sum(b1) as b1,sum(b2) as b2,sum(b3) as b3,sum(b4) as b4,
								sum(b5) as b5,sum(b6) as b6,sum(b7) as b7,sum(b8) as b8,
								sum(b9) as b9,sum(b10) as b10,sum(b11) as b11,
								sum(b12) as b12,
								sum(t1) as t1,sum(t2) as t2,sum(t3) as t3,sum(t4) as t4,
								sum(t5) as t5,sum(t6) as t6,sum(t7) as t7,sum(t8) as t8,
								sum(t9) as t9,sum(t10) as t10,sum(t11) as t11,
								sum(t12) as t12,sum(total) as total, 
						 		".'"'.$supp.'"'.",tgl_created,tahun,'999','3','','$key_jam', id
						from 	mpm.tbl_temp_omzet_pilkita a 
						where 	status = 1 and kategori_supplier ='$supp' and tahun = $year and a.key = '$key_jam'
					)a
					";

					$gabung = "insert into mpm.tbl_temp_omzet_pilkita ".$query;			        
			        $sql = $this->db->query($gabung);
			        
			        //echo "<pre>";
			        //print_r($gabung);
			        //echo "</pre>";
					
					if ($sql == '1') {
			        	
			        	$query="
							insert into mpm.tbl_temporary_user (userid,menuid,nilai,datetime,supp,tahun) 
	            			values ($id,$menuid,$total,$tgl_created,'$supp$group','$year')
						";
							        
				        $sql = $this->db->query($query);

			        }else{
			        	echo "ada kesalahan <i>Proses insert temp_omzet_grand_total</i>. silahkan ulangi";
			        }

		        }else{
		        	echo "ada kesalahan <i>Proses insert temp_omzet_sub_total</i>. silahkan ulangi";
		        }

			}else{

				echo "ada kesalahan <i>Proses insert temp_omzet_per_sub_branch</i>. silahkan ulangi";
			}

			$gabung = "
				insert into mpm.omzet_new_pilkita
				select 	nocab, sub, kode_comp, nama_comp, 
						t1,b1,t2,b2,t3,b3,t4,b4,t5,b5,t6,b6,t7,b7,t8,b8,t9,b9,t10,b10,t11,b11,t12,b12,id,total,rata,kategori_supplier,tgl_created,
						tahun, urutan, lastupload, `key`
				from 	mpm.tbl_temp_omzet_pilkita
				where 	kategori_supplier = ".'"'.$supp.'"'." and tahun = $year and `key` = '$key_jam'
				";			        
	        $sql = $this->db->query($gabung);

	        //echo "<pre>";
	        //print_r($gabung);
	        //echo "</pre>";


			if ($sql == '1') {
	        	
	        	if ($dp == NULL || $dp == '') {
		        	
				$query="
							select 	* 
							from 	mpm.omzet_new_pilkita
							where 	tahun = $year and
									kategori_supplier = '$supp' and
									`key` = (
											select 	max(`key`)
											from 	mpm.omzet_new_pilkita
											where 	tahun = $year and
													kategori_supplier = '$supp'
							)
							ORDER BY urutan asc

						";
					
					//echo "<pre>";
					//print_r($query);
					//echo "</pre>";
					
							        
			        $hasil = $this->db->query($query);
			        if ($hasil->num_rows() > 0) 
					{
						return $hasil->result();
					} else {
						return array();
					}

					/* END PROSES TAMPIL KE WEBSITE */

	        	} else {
		     
					$query="
							select 	*
							from 		mpm.omzet_new_pilkita
							where 		tahun = $year and
										kategori_supplier = '$supp' and
										naper in ($daftar_dp) and
										`key` = (
											select 	max(`key`)
											from 	mpm.omzet_new_pilkita
											where 	tahun = $year and
													kategori_supplier = '$supp'
							)
							ORDER BY urutan asc
						";
							        
			        $hasil = $this->db->query($query);
			        if ($hasil->num_rows() > 0) 
					{
						return $hasil->result();
					} else {
						return array();
					}
					/* END PROSES TAMPIL KE WEBSITE */
	        	}

	        }else{
	        	echo "ada kesalahan <i>Proses insert omzet_new</i>. silahkan ulangi";
	        }
									
        }
	    
	}

	
	public function omzet_all_dp_group_otherm($dataSegment){

	/* ---------DEFINISI VARIABEL----------------- */		    
	    $tgl_created='"'.date('Y-m-d H:i:s').'"';
	    $jam=date('ymdHis');
	    $key_jam = $jam;
	    $id=$this->session->userdata('id');
		$year = $dataSegment['year'];
		$group = $dataSegment['group'];	
		$supp = $dataSegment['supp'];
		$uri1 = $dataSegment['menu_uri1'];
		$uri2 = $dataSegment['menu_uri2'];
		$uri3 = $dataSegment['menu_uri3'];
		$menuid = $dataSegment['menuid'];
	
	/* ---------Hak Lihat DP----------------- */		    
		$dp = $dataSegment['query_dp'];        
        if ($dp == NULL || $dp == '') {	$daftar_dp = ''; } else { $daftar_dp = $dp; }

    /* ---------Cari Total FI----------------- */
    	$this->db->select('count(*) as rows');
        $query = $this->db->get("data$year.fi");
        foreach($query->result() as $r)
        {
           $total = $r->rows;
        }	
    
    /* ---------Cari histori di temporary user----------------- */
	    $sql="
	    	select 	1 
	    	from 	mpm.tbl_temporary_user 
	    	where   menuid = $menuid and
	    			supp = '$supp$group' and
	    			tahun = '$year' and 
	    			nilai = $total
	    			";
	    $query = $this->db->query($sql);
        //echo "<pre>";
        //print_r($sql);
        //echo "</pre>";
        if($query->num_rows()>0) //kalau histori ditemukan
        {        	
			if ($dp == NULL || $dp == '') // kalau boleh lihat semua DP
			{	
				$query="
					select 	*
					from 		mpm.omzet_new_otherm
					where 		tahun = $year and
								kategori_supplier = '$supp' and
								`key` = (
									select 	max(`key`)
									from 	mpm.omzet_new_otherm
									where 	tahun = $year and
											kategori_supplier = '$supp'
									)
					ORDER BY urutan asc
				";
				    
		        $hasil = $this->db->query($query);
		        if ($hasil->num_rows() > 0) 
				{ return $hasil->result(); } else { return array(); }

	        } else {
		        	
					$query="
							select 	*
							from 		mpm.omzet_new_otherm
							where 		tahun = $year and
										kategori_supplier = '$supp' and
										naper in ($daftar_dp) and
										`key` = (
											select 	max(`key`)
											from 	mpm.omzet_new_otherm
											where 	tahun = $year and
													kategori_supplier = '$supp'
										)
							ORDER BY urutan asc
						"; 
			        $hasil = $this->db->query($query);
		        if ($hasil->num_rows() > 0) 
				{ return $hasil->result(); } else { return array(); }

					/* END PROSES TAMPIL KE WEBSITE */
	        }				
        }else{ //kalau histori tidak ditemukan

    	/* === Proses pendefinisian bulan, pembagi untuk menentukan total dan rata2 === */				
			$bulan = date("m");
			$tahunsekarang = date("Y");
				if ($year == $tahunsekarang) //jika memilih tahun berjalan
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
						$totalx = 'b1+b2+b3+b4+b5+b6+b7+b8+b9+b10+b11+b12';
						$pembagi = 'xb1+xb2+xb3+xb4+xb5+xb6+xb7+xb8+xb9+xb10+xb11+xb12';
						$rata = 12;
				}
				/* ========================================== */

				/* == PROSES DELETE MPM.OMZET_NEW ===== */
				$query = "	
						delete from mpm.omzet_new_otherm
						where kategori_supplier = ".'"'.$supp.'"'." and tahun = $year and id = $id
							";
				//echo "<pre>";
				//print_r($query);
				//echo "</pre>";
				$sql = $this->db->query($query);

				/* == PROSES DELETE mpm.tbl_temp_omzet = */
				$query = "	
						delete from mpm.tbl_temp_omzet_otherm
						where kategori_supplier = ".'"'.$supp.'"'." and tahun = $year and and id = $id
							";
				$sql = $this->db->query($query);

				/* ========== PROSES INSERT KE mpm.tbl_temp_omzet =================== */
			
$query = "	   
	select  a.nocab, a.kode_comp, b.sub, b.nama_comp,
			t1,b1,t2,b2,t3,b3,t4,b4,t5,b5,
			t6,b6,t7,b7,t8,b8,t9,b9,t10,b10,
			t11,b11,t12,b12,".$id.",total,totalx/jmlbulan rata,
			".'"'.$supp.'"'.",".$tgl_created.",".$year.", urutan, status, maxupload, '$key_jam'
	FROM
	(
		select 	nocab,kode_comp,kode,
				b1, b2, b3, b4, b5, b6, b7, b8, b9, b10, b11, b12,
				t1, t2, t3, t4,	t5, t6, t7, t8,	t9, t10, t11, t12, ".$id.",
				total, totalx, format(sum($pembagi),0, 1) jmlbulan
		FROM
		(
			select  nocab,kode_comp, 
					b1, b2, b3, b4, b5, b6, b7, b8, b9, b10, b11, b12,
					".$id.",
					b1+b2+b3+b4+b5+b6+b7+b8+b9+b10+b11+b12 as total,
					$totalx as totalx,
					IF(b1 = 0, 0, 1) xb1, 
					IF(b2 = 0, 0, 1) xb2, 
					IF(b3 = 0, 0, 1) xb3, 
					IF(b4 = 0, 0, 1) xb4, 
					IF(b5 = 0, 0, 1) xb5, 
					IF(b6 = 0, 0, 1) xb6, 
					IF(b7 = 0, 0, 1) xb7, 
					IF(b8 = 0, 0, 1) xb8, 
					IF(b9 = 0, 0, 1) xb9, 
					IF(b10 = 0, 0, 1) xb10,
					IF(b11 = 0, 0, 1) xb11, 
					IF(b12 = 0, 0, 1) xb12, 
					sum(t1) as t1, 
					sum(t2) as t2,  
					sum(t3) as t3, 
					sum(t4) as t4,  
					sum(t5) as t5,  
					sum(t6) as t6,  
					sum(t7) as t7, 
					sum(t8) as t8,  
					sum(t9) as t9, 
					sum(t10) as t10, 
					sum(t11) as t11, 
					sum(t12) as t12,
					kode
			from
			(				
				select 	nocab, kode_comp, kode,
						sum(if(blndok = 1 and grup = 'G0202', unit, 0)) as b1,
						count(DISTINCT(if(blndok = 1 and grup = 'G0202', outlet,0)))-1 as t1,
						sum(if(blndok = 2 and grup = 'G0202', unit, 0)) as b2,
						count(DISTINCT(if(blndok = 2 and grup = 'G0202', outlet,0)))-1 as t2,
						sum(if(blndok = 3 and grup = 'G0202', unit, 0)) as b3,
						count(DISTINCT(if(blndok = 3 and grup = 'G0202', outlet,0)))-1 as t3,
						sum(if(blndok = 4 and grup = 'G0202', unit, 0)) as b4,
						count(DISTINCT(if(blndok = 4 and grup = 'G0202', outlet,0)))-1 as t4,
						sum(if(blndok = 5 and grup = 'G0202', unit, 0)) as b5,
						count(DISTINCT(if(blndok = 5 and grup = 'G0202', outlet,0)))-1 as t5,
						sum(if(blndok = 6 and grup = 'G0202', unit, 0)) as b6,
						count(DISTINCT(if(blndok = 6 and grup = 'G0202', outlet,0)))-1 as t6,
						sum(if(blndok = 7 and grup = 'G0202', unit, 0)) as b7,
						count(DISTINCT(if(blndok = 7 and grup = 'G0202', outlet,0)))-1 as t7,
						sum(if(blndok = 8 and grup = 'G0202', unit, 0)) as b8,
						count(DISTINCT(if(blndok = 8 and grup = 'G0202', outlet,0)))-1 as t8,
						sum(if(blndok = 9 and grup = 'G0202', unit, 0)) as b9,
						count(DISTINCT(if(blndok = 9 and grup = 'G0202', outlet,0)))-1 as t9,
						sum(if(blndok = 10 and grup = 'G0202', unit, 0)) as b10,
						count(DISTINCT(if(blndok = 10 and grup = 'G0202', outlet,0)))-1 as t10,
						sum(if(blndok = 11 and grup = 'G0202', unit, 0)) as b11,
						count(DISTINCT(if(blndok = 11 and grup = 'G0202', outlet,0)))-1 as t11,
						sum(if(blndok = 12 and grup = 'G0202', unit, 0)) as b12,
						count(DISTINCT(if(blndok = 12 and grup = 'G0202', outlet,0)))-1 as t12
				FROM
				(
					select 	nocab, kode_comp, kode, unit, a.kodeprod, grup, outlet, blndok 
					FROM
					(
							select 	nocab,kode_comp,blndok,
									tot1 as unit, kodeprod, 
									concat(kode_comp,nocab) as kode, concat(kode_comp,kode_lang) as outlet
							from 	data".$year.".fi
							where 	nodokjdi <> 'XXXXXX' and kodeprod like '02%' 
							union ALL
							select 	nocab,kode_comp,blndok,
									tot1 as unit, kodeprod, 
									concat(kode_comp,nocab) as kode, 0
							from 	data".$year.".ri
							where 	nodokjdi <> 'XXXXXX' and kodeprod like '02%' 
					)a LEFT JOIN 
					(
						select kodeprod, grup 
						from mpm.tabprod
						where supp ='002'
					)b on a.kodeprod = b.kodeprod
				)a GROUP BY kode					 
			)a GROUP BY kode
		)a GROUP BY kode
	)a LEFT JOIN
	(
		select concat(kode_comp, nocab) as kode,naper,kode_comp, nama_comp, sub,urutan,status,status_cluster
		from mpm.tbl_tabcomp_new
		where status = 1
		GROUP BY concat(kode_comp, nocab)
	)b on a.kode = b.kode
	LEFT JOIN
	(
			select 	max(lastupload) as maxupload,
					substring(filename,3,2) naper  
			from 	mpm.upload 
			group by naper
	)c on a.nocab = c.naper
	";      
			
	        $gabung = "insert into mpm.tbl_temp_omzet_otherm ".$query;			        
	        $sql = $this->db->query($gabung);
	        
	        //echo "<pre>";
			//print_r($query);			
			//echo "</pre>";
			
			/* ============================================================== */
			if ($sql == '1') {
				
				$query="
				select 	nocab, kode_comp, a.sub, b.nama, 
						sum(t1),sum(b1),sum(t2),sum(b2),sum(t3),sum(b3),sum(t4),sum(b4),sum(t5),sum(b5),
						sum(t6),sum(b6),sum(t7),sum(b7),sum(t8),sum(b8),sum(t9),sum(b9),sum(t10),sum(b10),sum(t11),sum(b11),
						sum(t12),sum(b12),id,sum(total),sum(rata), ".'"'.$supp.'"'.",tgl_created,tahun,b.urutan,b.status,'','$key_jam'
				
				from 	mpm.tbl_temp_omzet_otherm a inner JOIN
				(
						select 	naper naper2,status, urutan, nama_comp nama, sub
						from 		mpm.tbl_tabcomp_new
						WHERE 	status = '2' and status_cluster <> '1'
				)b on a.sub = b.sub
				where a.kategori_supplier ='$supp' and a.tahun = $year and a.`key` = '$key_jam'
				GROUP BY sub
				ORDER BY a.urutan asc

				";
				$gabung = "insert into mpm.tbl_temp_omzet_otherm ".$query;			        
		        $sql = $this->db->query($gabung);

		        //echo "<pre>";
		        //print_r($query);
		        //echo "</pre>";


				if ($sql == '1') {
		        	
					$query = "
					select 	'', '', '', 'GRAND(TOTAL)',
							t1,b1,t2,b2,t3,b3,t4,b4,t5,b5,t6,b6,
							t7,b7,t8,b8,t9,b9,t10,b10,t11,b11,t12,b12,id,																
							total, ($totalx)/$rata as rata, 
						 	".'"'.$supp.'"'.",tgl_created,tahun,'999','3','','$key_jam'
					from
					(
						select 	sum(b1) as b1,sum(b2) as b2,sum(b3) as b3,sum(b4) as b4,
								sum(b5) as b5,sum(b6) as b6,sum(b7) as b7,sum(b8) as b8,
								sum(b9) as b9,sum(b10) as b10,sum(b11) as b11,
								sum(b12) as b12,
								sum(t1) as t1,sum(t2) as t2,sum(t3) as t3,sum(t4) as t4,
								sum(t5) as t5,sum(t6) as t6,sum(t7) as t7,sum(t8) as t8,
								sum(t9) as t9,sum(t10) as t10,sum(t11) as t11,
								sum(t12) as t12,sum(total) as total, 
						 		".'"'.$supp.'"'.",tgl_created,tahun,'999','3','','$key_jam', id
						from 	mpm.tbl_temp_omzet_otherm a 
						where 	status = 1 and kategori_supplier ='$supp' and tahun = $year and a.key = '$key_jam'
					)a
					";

					$gabung = "insert into mpm.tbl_temp_omzet_otherm ".$query;			        
			        $sql = $this->db->query($gabung);
			        
			        //echo "<pre>";
			        //print_r($gabung);
			        //echo "</pre>";
					
					if ($sql == '1') {
			        	
			        	$query="
							insert into mpm.tbl_temporary_user (userid,menuid,nilai,datetime,supp,tahun) 
	            			values ($id,$menuid,$total,$tgl_created,'$supp$group','$year')
						";
							        
				        $sql = $this->db->query($query);

			        }else{
			        	echo "ada kesalahan <i>Proses insert temp_omzet_grand_total</i>. silahkan ulangi";
			        }

		        }else{
		        	echo "ada kesalahan <i>Proses insert temp_omzet_sub_total</i>. silahkan ulangi";
		        }

			}else{

				echo "ada kesalahan <i>Proses insert temp_omzet_per_sub_branch</i>. silahkan ulangi";
			}

			$gabung = "
				insert into mpm.omzet_new_otherm
				select 	nocab, sub, kode_comp, nama_comp, 
						t1,b1,t2,b2,t3,b3,t4,b4,t5,b5,t6,b6,t7,b7,t8,b8,t9,b9,t10,b10,t11,b11,t12,b12,id,total,rata,kategori_supplier,tgl_created,
						tahun, urutan, lastupload, `key`
				from 	mpm.tbl_temp_omzet_otherm
				where 	kategori_supplier = ".'"'.$supp.'"'." and tahun = $year and `key` = '$key_jam'
				";			        
	        $sql = $this->db->query($gabung);

	        //echo "<pre>";
	        //print_r($gabung);
	        //echo "</pre>";


			if ($sql == '1') {
	        	
	        	if ($dp == NULL || $dp == '') {
		        	
				$query="
							select 	* 
							from 	mpm.omzet_new_otherm
							where 	tahun = $year and
									kategori_supplier = '$supp' and
									`key` = (
											select 	max(`key`)
											from 	mpm.omzet_new_otherm
											where 	tahun = $year and
													kategori_supplier = '$supp'
							)
							ORDER BY urutan asc

						";
					
					//echo "<pre>";
					//print_r($query);
					//echo "</pre>";
					
							        
			        $hasil = $this->db->query($query);
			        if ($hasil->num_rows() > 0) 
					{
						return $hasil->result();
					} else {
						return array();
					}

					/* END PROSES TAMPIL KE WEBSITE */

	        	} else {
		     
					$query="
							select 	*
							from 		mpm.omzet_new_otherm
							where 		tahun = $year and
										kategori_supplier = '$supp' and
										naper in ($daftar_dp) and
										`key` = (
											select 	max(`key`)
											from 	mpm.omzet_new_otherm
											where 	tahun = $year and
													kategori_supplier = '$supp'
							)
							ORDER BY urutan asc
						";
							        
			        $hasil = $this->db->query($query);
			        if ($hasil->num_rows() > 0) 
					{
						return $hasil->result();
					} else {
						return array();
					}
					/* END PROSES TAMPIL KE WEBSITE */
	        	}

	        }else{
	        	echo "ada kesalahan <i>Proses insert omzet_new</i>. silahkan ulangi";
	        }
									
        }
	    
	}


	public function omzet_all_dp_group_freshcare($dataSegment){

	/* ---------DEFINISI VARIABEL----------------- */		    
	    $tgl_created='"'.date('Y-m-d H:i:s').'"';
	    $jam=date('ymdHis');
	    $key_jam = $jam;
	    $id=$this->session->userdata('id');
		$year = $dataSegment['year'];
		$group = $dataSegment['group'];	
		$supp = $dataSegment['supp'];
		$uri1 = $dataSegment['menu_uri1'];
		$uri2 = $dataSegment['menu_uri2'];
		$uri3 = $dataSegment['menu_uri3'];
		$menuid = $dataSegment['menuid'];
	
	/* ---------Hak Lihat DP----------------- */		    
		$dp = $dataSegment['query_dp'];        
        if ($dp == NULL || $dp == '') {	$daftar_dp = ''; } else { $daftar_dp = $dp; }

    /* ---------Cari Total FI----------------- */
    	$this->db->select('count(*) as rows');
        $query = $this->db->get("data$year.fi");
        foreach($query->result() as $r)
        {
           $total = $r->rows;
        }	
    
    /* ---------Cari histori di temporary user----------------- */
	    $sql="
	    	select 	1 
	    	from 	mpm.tbl_temporary_user 
	    	where   menuid = $menuid and
	    			supp = '$supp$group' and
	    			tahun = '$year' and 
	    			nilai = $total
	    			";
	    $query = $this->db->query($sql);
        //echo "<pre>";
        //print_r($sql);
        //echo "</pre>";
        if($query->num_rows()>0) //kalau histori ditemukan
        {        	
			if ($dp == NULL || $dp == '') // kalau boleh lihat semua DP
			{	
				$query="
					select 	*
					from 		mpm.omzet_new_freshcare
					where 		tahun = $year and
								kategori_supplier = '$supp' and
								`key` = (
									select 	max(`key`)
									from 	mpm.omzet_new_freshcare
									where 	tahun = $year and
											kategori_supplier = '$supp'
									)
					ORDER BY urutan asc
				";
				    
		        $hasil = $this->db->query($query);
		        if ($hasil->num_rows() > 0) 
				{ return $hasil->result(); } else { return array(); }

	        } else {
		        	
					$query="
							select 	*
							from 		mpm.omzet_new_freshcare
							where 		tahun = $year and
										kategori_supplier = '$supp' and
										naper in ($daftar_dp) and
										`key` = (
											select 	max(`key`)
											from 	mpm.omzet_new_freshcare
											where 	tahun = $year and
													kategori_supplier = '$supp'
										)
							ORDER BY urutan asc
						"; 
			        $hasil = $this->db->query($query);
		        if ($hasil->num_rows() > 0) 
				{ return $hasil->result(); } else { return array(); }

					/* END PROSES TAMPIL KE WEBSITE */
	        }				
        }else{ //kalau histori tidak ditemukan

    	/* === Proses pendefinisian bulan, pembagi untuk menentukan total dan rata2 === */				
			$bulan = date("m");
			$tahunsekarang = date("Y");
				if ($year == $tahunsekarang) //jika memilih tahun berjalan
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
						$totalx = 'b1+b2+b3+b4+b5+b6+b7+b8+b9+b10+b11+b12';
						$pembagi = 'xb1+xb2+xb3+xb4+xb5+xb6+xb7+xb8+xb9+xb10+xb11+xb12';
						$rata = 12;
				}
				/* ========================================== */

				/* == PROSES DELETE MPM.OMZET_NEW ===== */
				$query = "	
						delete from mpm.omzet_new_freshcare
						where kategori_supplier = ".'"'.$supp.'"'." and tahun = $year and id = $id
							";
				//echo "<pre>";
				//print_r($query);
				//echo "</pre>";
				$sql = $this->db->query($query);

				/* == PROSES DELETE mpm.tbl_temp_omzet = */
				$query = "	
						delete from mpm.tbl_temp_omzet_freshcare
						where kategori_supplier = ".'"'.$supp.'"'." and tahun = $year and and id = $id
							";
				$sql = $this->db->query($query);

				/* ========== PROSES INSERT KE mpm.tbl_temp_omzet =================== */
			
$query = "	   
	select  a.nocab, a.kode_comp, b.sub, b.nama_comp,
			t1,b1,t2,b2,t3,b3,t4,b4,t5,b5,
			t6,b6,t7,b7,t8,b8,t9,b9,t10,b10,
			t11,b11,t12,b12,".$id.",total,totalx/jmlbulan rata,
			".'"'.$supp.'"'.",".$tgl_created.",".$year.", urutan, status, maxupload, '$key_jam'
	FROM
	(
		select 	nocab,kode_comp,kode,
				b1, b2, b3, b4, b5, b6, b7, b8, b9, b10, b11, b12,
				t1, t2, t3, t4,	t5, t6, t7, t8,	t9, t10, t11, t12, ".$id.",
				total, totalx, format(sum($pembagi),0, 1) jmlbulan
		FROM
		(
			select  nocab,kode_comp, 
					b1, b2, b3, b4, b5, b6, b7, b8, b9, b10, b11, b12,
					".$id.",
					b1+b2+b3+b4+b5+b6+b7+b8+b9+b10+b11+b12 as total,
					$totalx as totalx,
					IF(b1 = 0, 0, 1) xb1, 
					IF(b2 = 0, 0, 1) xb2, 
					IF(b3 = 0, 0, 1) xb3, 
					IF(b4 = 0, 0, 1) xb4, 
					IF(b5 = 0, 0, 1) xb5, 
					IF(b6 = 0, 0, 1) xb6, 
					IF(b7 = 0, 0, 1) xb7, 
					IF(b8 = 0, 0, 1) xb8, 
					IF(b9 = 0, 0, 1) xb9, 
					IF(b10 = 0, 0, 1) xb10,
					IF(b11 = 0, 0, 1) xb11, 
					IF(b12 = 0, 0, 1) xb12, 
					sum(t1) as t1, 
					sum(t2) as t2,  
					sum(t3) as t3, 
					sum(t4) as t4,  
					sum(t5) as t5,  
					sum(t6) as t6,  
					sum(t7) as t7, 
					sum(t8) as t8,  
					sum(t9) as t9, 
					sum(t10) as t10, 
					sum(t11) as t11, 
					sum(t12) as t12,
					kode
			from
			(				
				select 	nocab, kode_comp, kode,
						sum(if(blndok = 1 and grup = 'G0501', unit, 0)) as b1,
						count(DISTINCT(if(blndok = 1 and grup = 'G0501', outlet,0)))-1 as t1,
						sum(if(blndok = 2 and grup = 'G0501', unit, 0)) as b2,
						count(DISTINCT(if(blndok = 2 and grup = 'G0501', outlet,0)))-1 as t2,
						sum(if(blndok = 3 and grup = 'G0501', unit, 0)) as b3,
						count(DISTINCT(if(blndok = 3 and grup = 'G0501', outlet,0)))-1 as t3,
						sum(if(blndok = 4 and grup = 'G0501', unit, 0)) as b4,
						count(DISTINCT(if(blndok = 4 and grup = 'G0501', outlet,0)))-1 as t4,
						sum(if(blndok = 5 and grup = 'G0501', unit, 0)) as b5,
						count(DISTINCT(if(blndok = 5 and grup = 'G0501', outlet,0)))-1 as t5,
						sum(if(blndok = 6 and grup = 'G0501', unit, 0)) as b6,
						count(DISTINCT(if(blndok = 6 and grup = 'G0501', outlet,0)))-1 as t6,
						sum(if(blndok = 7 and grup = 'G0501', unit, 0)) as b7,
						count(DISTINCT(if(blndok = 7 and grup = 'G0501', outlet,0)))-1 as t7,
						sum(if(blndok = 8 and grup = 'G0501', unit, 0)) as b8,
						count(DISTINCT(if(blndok = 8 and grup = 'G0501', outlet,0)))-1 as t8,
						sum(if(blndok = 9 and grup = 'G0501', unit, 0)) as b9,
						count(DISTINCT(if(blndok = 9 and grup = 'G0501', outlet,0)))-1 as t9,
						sum(if(blndok = 10 and grup = 'G0501', unit, 0)) as b10,
						count(DISTINCT(if(blndok = 10 and grup = 'G0501', outlet,0)))-1 as t10,
						sum(if(blndok = 11 and grup = 'G0501', unit, 0)) as b11,
						count(DISTINCT(if(blndok = 11 and grup = 'G0501', outlet,0)))-1 as t11,
						sum(if(blndok = 12 and grup = 'G0501', unit, 0)) as b12,
						count(DISTINCT(if(blndok = 12 and grup = 'G0501', outlet,0)))-1 as t12
				FROM
				(
					select 	nocab, kode_comp, kode, unit, a.kodeprod, grup, outlet, blndok 
					FROM
					(
							select 	nocab,kode_comp,blndok,
									tot1 as unit, kodeprod, 
									concat(kode_comp,nocab) as kode, concat(kode_comp,kode_lang) as outlet
							from 	data".$year.".fi
							where 	nodokjdi <> 'XXXXXX' and kodeprod like '06%' 
							union ALL
							select 	nocab,kode_comp,blndok,
									tot1 as unit, kodeprod, 
									concat(kode_comp,nocab) as kode, 0
							from 	data".$year.".ri
							where 	nodokjdi <> 'XXXXXX' and kodeprod like '06%' 
					)a LEFT JOIN 
					(
						select kodeprod, grup 
						from mpm.tabprod
						where supp ='005'
					)b on a.kodeprod = b.kodeprod
				)a GROUP BY kode					 
			)a GROUP BY kode
		)a GROUP BY kode
	)a LEFT JOIN
	(
		select concat(kode_comp, nocab) as kode,naper,kode_comp, nama_comp, sub,urutan,status,status_cluster
		from mpm.tbl_tabcomp_new
		where status = 1
		GROUP BY concat(kode_comp, nocab)
	)b on a.kode = b.kode
	LEFT JOIN
	(
			select 	max(lastupload) as maxupload,
					substring(filename,3,2) naper  
			from 	mpm.upload 
			group by naper
	)c on a.nocab = c.naper
	";      
			
	        $gabung = "insert into mpm.tbl_temp_omzet_freshcare ".$query;			        
	        $sql = $this->db->query($gabung);
	        
	        //echo "<pre>";
			//print_r($query);			
			//echo "</pre>";
			
			/* ============================================================== */
			if ($sql == '1') {
				
				$query="
				select 	nocab, kode_comp, a.sub, b.nama, 
						sum(t1),sum(b1),sum(t2),sum(b2),sum(t3),sum(b3),sum(t4),sum(b4),sum(t5),sum(b5),
						sum(t6),sum(b6),sum(t7),sum(b7),sum(t8),sum(b8),sum(t9),sum(b9),sum(t10),sum(b10),sum(t11),sum(b11),
						sum(t12),sum(b12),id,sum(total),sum(rata), ".'"'.$supp.'"'.",tgl_created,tahun,b.urutan,b.status,'','$key_jam'
				
				from 	mpm.tbl_temp_omzet_freshcare a inner JOIN
				(
						select 	naper naper2,status, urutan, nama_comp nama, sub
						from 		mpm.tbl_tabcomp_new
						WHERE 	status = '2' and status_cluster <> '1'
				)b on a.sub = b.sub
				where a.kategori_supplier ='$supp' and a.tahun = $year and a.`key` = '$key_jam'
				GROUP BY sub
				ORDER BY a.urutan asc

				";
				$gabung = "insert into mpm.tbl_temp_omzet_freshcare ".$query;			        
		        $sql = $this->db->query($gabung);

		        //echo "<pre>";
		        //print_r($query);
		        //echo "</pre>";


				if ($sql == '1') {
		        	
					$query = "
					select 	'', '', '', 'GRAND(TOTAL)',
							t1,b1,t2,b2,t3,b3,t4,b4,t5,b5,t6,b6,
							t7,b7,t8,b8,t9,b9,t10,b10,t11,b11,t12,b12,id,																
							total, ($totalx)/$rata as rata, 
						 	".'"'.$supp.'"'.",tgl_created,tahun,'999','3','','$key_jam'
					from
					(
						select 	sum(b1) as b1,sum(b2) as b2,sum(b3) as b3,sum(b4) as b4,
								sum(b5) as b5,sum(b6) as b6,sum(b7) as b7,sum(b8) as b8,
								sum(b9) as b9,sum(b10) as b10,sum(b11) as b11,
								sum(b12) as b12,
								sum(t1) as t1,sum(t2) as t2,sum(t3) as t3,sum(t4) as t4,
								sum(t5) as t5,sum(t6) as t6,sum(t7) as t7,sum(t8) as t8,
								sum(t9) as t9,sum(t10) as t10,sum(t11) as t11,
								sum(t12) as t12,sum(total) as total, 
						 		".'"'.$supp.'"'.",tgl_created,tahun,'999','3','','$key_jam', id
						from 	mpm.tbl_temp_omzet_freshcare a 
						where 	status = 1 and kategori_supplier ='$supp' and tahun = $year and a.key = '$key_jam'
					)a
					";

					$gabung = "insert into mpm.tbl_temp_omzet_freshcare ".$query;			        
			        $sql = $this->db->query($gabung);
			        
			        echo "<pre>";
			        print_r($gabung);
			        echo "</pre>";
					
					if ($sql == '1') {
			        	
			        	$query="
							insert into mpm.tbl_temporary_user (userid,menuid,nilai,datetime,supp,tahun) 
	            			values ($id,$menuid,$total,$tgl_created,'$supp$group','$year')
						";
							        
				        $sql = $this->db->query($query);

			        }else{
			        	echo "ada kesalahan <i>Proses insert temp_omzet_grand_total</i>. silahkan ulangi";
			        }

		        }else{
		        	echo "ada kesalahan <i>Proses insert temp_omzet_sub_total</i>. silahkan ulangi";
		        }

			}else{

				echo "ada kesalahan <i>Proses insert temp_omzet_per_sub_branch</i>. silahkan ulangi";
			}

			$gabung = "
				insert into mpm.omzet_new_freshcare
				select 	nocab, sub, kode_comp, nama_comp, 
						t1,b1,t2,b2,t3,b3,t4,b4,t5,b5,t6,b6,t7,b7,t8,b8,t9,b9,t10,b10,t11,b11,t12,b12,id,total,rata,kategori_supplier,tgl_created,
						tahun, urutan, lastupload, `key`
				from 	mpm.tbl_temp_omzet_freshcare
				where 	kategori_supplier = ".'"'.$supp.'"'." and tahun = $year and `key` = '$key_jam'
				";			        
	        $sql = $this->db->query($gabung);

	        echo "<pre>";
	        print_r($gabung);
	        echo "</pre>";

			if ($sql == '1') {
	        	
	        	if ($dp == NULL || $dp == '') {
		        	
				$query="
							select 	* 
							from 	mpm.omzet_new_freshcare
							where 	tahun = $year and
									kategori_supplier = '$supp' and
									`key` = (
											select 	max(`key`)
											from 	mpm.omzet_new_freshcare
											where 	tahun = $year and
													kategori_supplier = '$supp'
							)
							ORDER BY urutan asc

						";
					
					echo "<pre>";
					print_r($query);
					echo "</pre>";
					
							        
			        $hasil = $this->db->query($query);
			        if ($hasil->num_rows() > 0) 
					{
						return $hasil->result();
					} else {
						return array();
					}

					/* END PROSES TAMPIL KE WEBSITE */

	        	} else {
		     
					$query="
							select 	*
							from 		mpm.omzet_new_freshcare
							where 		tahun = $year and
										kategori_supplier = '$supp' and
										naper in ($daftar_dp) and
										`key` = (
											select 	max(`key`)
											from 	mpm.omzet_new_freshcare
											where 	tahun = $year and
													kategori_supplier = '$supp'
							)
							ORDER BY urutan asc
						";
							        
			        $hasil = $this->db->query($query);
			        if ($hasil->num_rows() > 0) 
					{
						return $hasil->result();
					} else {
						return array();
					}
					/* END PROSES TAMPIL KE WEBSITE */
	        	}

	        }else{
	        	echo "ada kesalahan <i>Proses insert omzet_new</i>. silahkan ulangi";
	        }
									
        }
	    
	}


	public function omzet_all_dp_group_hotin($dataSegment){

	/* ---------DEFINISI VARIABEL----------------- */		    
	    $tgl_created='"'.date('Y-m-d H:i:s').'"';
	    $jam=date('ymdHis');
	    $key_jam = $jam;
	    $id=$this->session->userdata('id');
		$year = $dataSegment['year'];
		$group = $dataSegment['group'];	
		$supp = $dataSegment['supp'];
		$uri1 = $dataSegment['menu_uri1'];
		$uri2 = $dataSegment['menu_uri2'];
		$uri3 = $dataSegment['menu_uri3'];
		$menuid = $dataSegment['menuid'];
	
	/* ---------Hak Lihat DP----------------- */		    
		$dp = $dataSegment['query_dp'];        
        if ($dp == NULL || $dp == '') {	$daftar_dp = ''; } else { $daftar_dp = $dp; }

    /* ---------Cari Total FI----------------- */
    	$this->db->select('count(*) as rows');
        $query = $this->db->get("data$year.fi");
        foreach($query->result() as $r)
        {
           $total = $r->rows;
        }	
    
    /* ---------Cari histori di temporary user----------------- */
	    $sql="
	    	select 	1 
	    	from 	mpm.tbl_temporary_user 
	    	where   menuid = $menuid and
	    			supp = '$supp$group' and
	    			tahun = '$year' and 
	    			nilai = $total
	    			";
	    $query = $this->db->query($sql);
        //echo "<pre>";
        //print_r($sql);
        //echo "</pre>";
        if($query->num_rows()>0) //kalau histori ditemukan
        {        	
			if ($dp == NULL || $dp == '') // kalau boleh lihat semua DP
			{	
				$query="
					select 	*
					from 		mpm.omzet_new_hotin
					where 		tahun = $year and
								kategori_supplier = '$supp' and
								`key` = (
									select 	max(`key`)
									from 	mpm.omzet_new_hotin
									where 	tahun = $year and
											kategori_supplier = '$supp'
									)
					ORDER BY urutan asc
				";
				    
		        $hasil = $this->db->query($query);
		        if ($hasil->num_rows() > 0) 
				{ return $hasil->result(); } else { return array(); }

	        } else {
		        	
					$query="
							select 	*
							from 		mpm.omzet_new_hotin
							where 		tahun = $year and
										kategori_supplier = '$supp' and
										naper in ($daftar_dp) and
										`key` = (
											select 	max(`key`)
											from 	mpm.omzet_new_hotin
											where 	tahun = $year and
													kategori_supplier = '$supp'
										)
							ORDER BY urutan asc
						"; 
			        $hasil = $this->db->query($query);
		        if ($hasil->num_rows() > 0) 
				{ return $hasil->result(); } else { return array(); }

					/* END PROSES TAMPIL KE WEBSITE */
	        }				
        }else{ //kalau histori tidak ditemukan

    	/* === Proses pendefinisian bulan, pembagi untuk menentukan total dan rata2 === */				
			$bulan = date("m");
			$tahunsekarang = date("Y");
				if ($year == $tahunsekarang) //jika memilih tahun berjalan
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
						$totalx = 'b1+b2+b3+b4+b5+b6+b7+b8+b9+b10+b11+b12';
						$pembagi = 'xb1+xb2+xb3+xb4+xb5+xb6+xb7+xb8+xb9+xb10+xb11+xb12';
						$rata = 12;
				}
				/* ========================================== */

				/* == PROSES DELETE MPM.OMZET_NEW ===== */
				$query = "	
						delete from mpm.omzet_new_hotin
						where kategori_supplier = ".'"'.$supp.'"'." and tahun = $year and id = $id
							";
				//echo "<pre>";
				//print_r($query);
				//echo "</pre>";
				$sql = $this->db->query($query);

				/* == PROSES DELETE mpm.tbl_temp_omzet = */
				$query = "	
						delete from mpm.tbl_temp_omzet_hotin
						where kategori_supplier = ".'"'.$supp.'"'." and tahun = $year and and id = $id
							";
				$sql = $this->db->query($query);

				/* ========== PROSES INSERT KE mpm.tbl_temp_omzet =================== */
			
$query = "	   
	select  a.nocab, a.kode_comp, b.sub, b.nama_comp,
			t1,b1,t2,b2,t3,b3,t4,b4,t5,b5,
			t6,b6,t7,b7,t8,b8,t9,b9,t10,b10,
			t11,b11,t12,b12,".$id.",total,totalx/jmlbulan rata,
			".'"'.$supp.'"'.",".$tgl_created.",".$year.", urutan, status, maxupload, '$key_jam'
	FROM
	(
		select 	nocab,kode_comp,kode,
				b1, b2, b3, b4, b5, b6, b7, b8, b9, b10, b11, b12,
				t1, t2, t3, t4,	t5, t6, t7, t8,	t9, t10, t11, t12, ".$id.",
				total, totalx, format(sum($pembagi),0, 1) jmlbulan
		FROM
		(
			select  nocab,kode_comp, 
					b1, b2, b3, b4, b5, b6, b7, b8, b9, b10, b11, b12,
					".$id.",
					b1+b2+b3+b4+b5+b6+b7+b8+b9+b10+b11+b12 as total,
					$totalx as totalx,
					IF(b1 = 0, 0, 1) xb1, 
					IF(b2 = 0, 0, 1) xb2, 
					IF(b3 = 0, 0, 1) xb3, 
					IF(b4 = 0, 0, 1) xb4, 
					IF(b5 = 0, 0, 1) xb5, 
					IF(b6 = 0, 0, 1) xb6, 
					IF(b7 = 0, 0, 1) xb7, 
					IF(b8 = 0, 0, 1) xb8, 
					IF(b9 = 0, 0, 1) xb9, 
					IF(b10 = 0, 0, 1) xb10,
					IF(b11 = 0, 0, 1) xb11, 
					IF(b12 = 0, 0, 1) xb12, 
					sum(t1) as t1, 
					sum(t2) as t2,  
					sum(t3) as t3, 
					sum(t4) as t4,  
					sum(t5) as t5,  
					sum(t6) as t6,  
					sum(t7) as t7, 
					sum(t8) as t8,  
					sum(t9) as t9, 
					sum(t10) as t10, 
					sum(t11) as t11, 
					sum(t12) as t12,
					kode
			from
			(				
				select 	nocab, kode_comp, kode,
						sum(if(blndok = 1 and grup = 'G0502', unit, 0)) as b1,
						count(DISTINCT(if(blndok = 1 and grup = 'G0502', outlet,0)))-1 as t1,
						sum(if(blndok = 2 and grup = 'G0502', unit, 0)) as b2,
						count(DISTINCT(if(blndok = 2 and grup = 'G0502', outlet,0)))-1 as t2,
						sum(if(blndok = 3 and grup = 'G0502', unit, 0)) as b3,
						count(DISTINCT(if(blndok = 3 and grup = 'G0502', outlet,0)))-1 as t3,
						sum(if(blndok = 4 and grup = 'G0502', unit, 0)) as b4,
						count(DISTINCT(if(blndok = 4 and grup = 'G0502', outlet,0)))-1 as t4,
						sum(if(blndok = 5 and grup = 'G0502', unit, 0)) as b5,
						count(DISTINCT(if(blndok = 5 and grup = 'G0502', outlet,0)))-1 as t5,
						sum(if(blndok = 6 and grup = 'G0502', unit, 0)) as b6,
						count(DISTINCT(if(blndok = 6 and grup = 'G0502', outlet,0)))-1 as t6,
						sum(if(blndok = 7 and grup = 'G0502', unit, 0)) as b7,
						count(DISTINCT(if(blndok = 7 and grup = 'G0502', outlet,0)))-1 as t7,
						sum(if(blndok = 8 and grup = 'G0502', unit, 0)) as b8,
						count(DISTINCT(if(blndok = 8 and grup = 'G0502', outlet,0)))-1 as t8,
						sum(if(blndok = 9 and grup = 'G0502', unit, 0)) as b9,
						count(DISTINCT(if(blndok = 9 and grup = 'G0502', outlet,0)))-1 as t9,
						sum(if(blndok = 10 and grup = 'G0502', unit, 0)) as b10,
						count(DISTINCT(if(blndok = 10 and grup = 'G0502', outlet,0)))-1 as t10,
						sum(if(blndok = 11 and grup = 'G0502', unit, 0)) as b11,
						count(DISTINCT(if(blndok = 11 and grup = 'G0502', outlet,0)))-1 as t11,
						sum(if(blndok = 12 and grup = 'G0502', unit, 0)) as b12,
						count(DISTINCT(if(blndok = 12 and grup = 'G0502', outlet,0)))-1 as t12
				FROM
				(
					select 	nocab, kode_comp, kode, unit, a.kodeprod, grup, outlet, blndok 
					FROM
					(
							select 	nocab,kode_comp,blndok,
									tot1 as unit, kodeprod, 
									concat(kode_comp,nocab) as kode, concat(kode_comp,kode_lang) as outlet
							from 	data".$year.".fi
							where 	nodokjdi <> 'XXXXXX' and kodeprod like '06%' 
							union ALL
							select 	nocab,kode_comp,blndok,
									tot1 as unit, kodeprod, 
									concat(kode_comp,nocab) as kode, 0
							from 	data".$year.".ri
							where 	nodokjdi <> 'XXXXXX' and kodeprod like '06%' 
					)a LEFT JOIN 
					(
						select kodeprod, grup 
						from mpm.tabprod
						where supp ='005'
					)b on a.kodeprod = b.kodeprod
				)a GROUP BY kode					 
			)a GROUP BY kode
		)a GROUP BY kode
	)a LEFT JOIN
	(
		select concat(kode_comp, nocab) as kode,naper,kode_comp, nama_comp, sub,urutan,status,status_cluster
		from mpm.tbl_tabcomp_new
		where status = 1
		GROUP BY concat(kode_comp, nocab)
	)b on a.kode = b.kode
	LEFT JOIN
	(
			select 	max(lastupload) as maxupload,
					substring(filename,3,2) naper  
			from 	mpm.upload 
			group by naper
	)c on a.nocab = c.naper
	";      
			
	        $gabung = "insert into mpm.tbl_temp_omzet_hotin ".$query;			        
	        $sql = $this->db->query($gabung);
	        
	        //echo "<pre>";
			//print_r($query);			
			//echo "</pre>";
			
			/* ============================================================== */
			if ($sql == '1') {
				
				$query="
				select 	nocab, kode_comp, a.sub, b.nama, 
						sum(t1),sum(b1),sum(t2),sum(b2),sum(t3),sum(b3),sum(t4),sum(b4),sum(t5),sum(b5),
						sum(t6),sum(b6),sum(t7),sum(b7),sum(t8),sum(b8),sum(t9),sum(b9),sum(t10),sum(b10),sum(t11),sum(b11),
						sum(t12),sum(b12),id,sum(total),sum(rata), ".'"'.$supp.'"'.",tgl_created,tahun,b.urutan,b.status,'','$key_jam'
				
				from 	mpm.tbl_temp_omzet_hotin a inner JOIN
				(
						select 	naper naper2,status, urutan, nama_comp nama, sub
						from 		mpm.tbl_tabcomp_new
						WHERE 	status = '2' and status_cluster <> '1'
				)b on a.sub = b.sub
				where a.kategori_supplier ='$supp' and a.tahun = $year and a.`key` = '$key_jam'
				GROUP BY sub
				ORDER BY a.urutan asc

				";
				$gabung = "insert into mpm.tbl_temp_omzet_hotin ".$query;			        
		        $sql = $this->db->query($gabung);

		        //echo "<pre>";
		        //print_r($query);
		        //echo "</pre>";


				if ($sql == '1') {
		        	
					$query = "
					select 	'', '', '', 'GRAND(TOTAL)',
							t1,b1,t2,b2,t3,b3,t4,b4,t5,b5,t6,b6,
							t7,b7,t8,b8,t9,b9,t10,b10,t11,b11,t12,b12,id,																
							total, ($totalx)/$rata as rata, 
						 	".'"'.$supp.'"'.",tgl_created,tahun,'999','3','','$key_jam'
					from
					(
						select 	sum(b1) as b1,sum(b2) as b2,sum(b3) as b3,sum(b4) as b4,
								sum(b5) as b5,sum(b6) as b6,sum(b7) as b7,sum(b8) as b8,
								sum(b9) as b9,sum(b10) as b10,sum(b11) as b11,
								sum(b12) as b12,
								sum(t1) as t1,sum(t2) as t2,sum(t3) as t3,sum(t4) as t4,
								sum(t5) as t5,sum(t6) as t6,sum(t7) as t7,sum(t8) as t8,
								sum(t9) as t9,sum(t10) as t10,sum(t11) as t11,
								sum(t12) as t12,sum(total) as total, 
						 		".'"'.$supp.'"'.",tgl_created,tahun,'999','3','','$key_jam', id
						from 	mpm.tbl_temp_omzet_hotin a 
						where 	status = 1 and kategori_supplier ='$supp' and tahun = $year and a.key = '$key_jam'
					)a
					";

					$gabung = "insert into mpm.tbl_temp_omzet_hotin ".$query;			        
			        $sql = $this->db->query($gabung);
			        
			        echo "<pre>";
			        print_r($gabung);
			        echo "</pre>";
					
					if ($sql == '1') {
			        	
			        	$query="
							insert into mpm.tbl_temporary_user (userid,menuid,nilai,datetime,supp,tahun) 
	            			values ($id,$menuid,$total,$tgl_created,'$supp$group','$year')
						";
							        
				        $sql = $this->db->query($query);

			        }else{
			        	echo "ada kesalahan <i>Proses insert temp_omzet_grand_total</i>. silahkan ulangi";
			        }

		        }else{
		        	echo "ada kesalahan <i>Proses insert temp_omzet_sub_total</i>. silahkan ulangi";
		        }

			}else{

				echo "ada kesalahan <i>Proses insert temp_omzet_per_sub_branch</i>. silahkan ulangi";
			}

			$gabung = "
				insert into mpm.omzet_new_hotin
				select 	nocab, sub, kode_comp, nama_comp, 
						t1,b1,t2,b2,t3,b3,t4,b4,t5,b5,t6,b6,t7,b7,t8,b8,t9,b9,t10,b10,t11,b11,t12,b12,id,total,rata,kategori_supplier,tgl_created,
						tahun, urutan, lastupload, `key`
				from 	mpm.tbl_temp_omzet_hotin
				where 	kategori_supplier = ".'"'.$supp.'"'." and tahun = $year and `key` = '$key_jam'
				";			        
	        $sql = $this->db->query($gabung);

	        echo "<pre>";
	        print_r($gabung);
	        echo "</pre>";

			if ($sql == '1') {
	        	
	        	if ($dp == NULL || $dp == '') {
		        	
				$query="
							select 	* 
							from 	mpm.omzet_new_hotin
							where 	tahun = $year and
									kategori_supplier = '$supp' and
									`key` = (
											select 	max(`key`)
											from 	mpm.omzet_new_hotin
											where 	tahun = $year and
													kategori_supplier = '$supp'
							)
							ORDER BY urutan asc

						";
					
					echo "<pre>";
					print_r($query);
					echo "</pre>";
					
							        
			        $hasil = $this->db->query($query);
			        if ($hasil->num_rows() > 0) 
					{
						return $hasil->result();
					} else {
						return array();
					}

					/* END PROSES TAMPIL KE WEBSITE */

	        	} else {
		     
					$query="
							select 	*
							from 		mpm.omzet_new_hotin
							where 		tahun = $year and
										kategori_supplier = '$supp' and
										naper in ($daftar_dp) and
										`key` = (
											select 	max(`key`)
											from 	mpm.omzet_new_hotin
											where 	tahun = $year and
													kategori_supplier = '$supp'
							)
							ORDER BY urutan asc
						";
							        
			        $hasil = $this->db->query($query);
			        if ($hasil->num_rows() > 0) 
					{
						return $hasil->result();
					} else {
						return array();
					}
					/* END PROSES TAMPIL KE WEBSITE */
	        	}

	        }else{
	        	echo "ada kesalahan <i>Proses insert omzet_new</i>. silahkan ulangi";
	        }
									
        }
	    
	}


	public function omzet_all_dp_group_madu($dataSegment){

	/* ---------DEFINISI VARIABEL----------------- */		    
	    $tgl_created='"'.date('Y-m-d H:i:s').'"';
	    $jam=date('ymdHis');
	    $key_jam = $jam;
	    $id=$this->session->userdata('id');
		$year = $dataSegment['year'];
		$group = $dataSegment['group'];	
		$supp = $dataSegment['supp'];
		$uri1 = $dataSegment['menu_uri1'];
		$uri2 = $dataSegment['menu_uri2'];
		$uri3 = $dataSegment['menu_uri3'];
		$menuid = $dataSegment['menuid'];
	
	/* ---------Hak Lihat DP----------------- */		    
		$dp = $dataSegment['query_dp'];        
        if ($dp == NULL || $dp == '') {	$daftar_dp = ''; } else { $daftar_dp = $dp; }

    /* ---------Cari Total FI----------------- */
    	$this->db->select('count(*) as rows');
        $query = $this->db->get("data$year.fi");
        foreach($query->result() as $r)
        {
           $total = $r->rows;
        }	
    
    /* ---------Cari histori di temporary user----------------- */
	    $sql="
	    	select 	1 
	    	from 	mpm.tbl_temporary_user 
	    	where   menuid = $menuid and
	    			supp = '$supp$group' and
	    			tahun = '$year' and 
	    			nilai = $total
	    			";
	    $query = $this->db->query($sql);
        //echo "<pre>";
        //print_r($sql);
        //echo "</pre>";
        if($query->num_rows()>0) //kalau histori ditemukan
        {        	
			if ($dp == NULL || $dp == '') // kalau boleh lihat semua DP
			{	
				$query="
					select 	*
					from 		mpm.omzet_new_madu
					where 		tahun = $year and
								kategori_supplier = '$supp' and
								`key` = (
									select 	max(`key`)
									from 	mpm.omzet_new_madu
									where 	tahun = $year and
											kategori_supplier = '$supp'
									)
					ORDER BY urutan asc
				";
				    
		        $hasil = $this->db->query($query);
		        if ($hasil->num_rows() > 0) 
				{ return $hasil->result(); } else { return array(); }

	        } else {
		        	
					$query="
							select 	*
							from 		mpm.omzet_new_madu
							where 		tahun = $year and
										kategori_supplier = '$supp' and
										naper in ($daftar_dp) and
										`key` = (
											select 	max(`key`)
											from 	mpm.omzet_new_madu
											where 	tahun = $year and
													kategori_supplier = '$supp'
										)
							ORDER BY urutan asc
						"; 
			        $hasil = $this->db->query($query);
		        if ($hasil->num_rows() > 0) 
				{ return $hasil->result(); } else { return array(); }

					/* END PROSES TAMPIL KE WEBSITE */
	        }				
        }else{ //kalau histori tidak ditemukan

    	/* === Proses pendefinisian bulan, pembagi untuk menentukan total dan rata2 === */				
			$bulan = date("m");
			$tahunsekarang = date("Y");
				if ($year == $tahunsekarang) //jika memilih tahun berjalan
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
						$totalx = 'b1+b2+b3+b4+b5+b6+b7+b8+b9+b10+b11+b12';
						$pembagi = 'xb1+xb2+xb3+xb4+xb5+xb6+xb7+xb8+xb9+xb10+xb11+xb12';
						$rata = 12;
				}
				/* ========================================== */

				/* == PROSES DELETE MPM.OMZET_NEW ===== */
				$query = "	
						delete from mpm.omzet_new_madu
						where kategori_supplier = ".'"'.$supp.'"'." and tahun = $year and id = $id
							";
				//echo "<pre>";
				//print_r($query);
				//echo "</pre>";
				$sql = $this->db->query($query);

				/* == PROSES DELETE mpm.tbl_temp_omzet = */
				$query = "	
						delete from mpm.tbl_temp_omzet_madu
						where kategori_supplier = ".'"'.$supp.'"'." and tahun = $year and and id = $id
							";
				$sql = $this->db->query($query);

				/* ========== PROSES INSERT KE mpm.tbl_temp_omzet =================== */
			
$query = "	   
	select  a.nocab, a.kode_comp, b.sub, b.nama_comp,
			t1,b1,t2,b2,t3,b3,t4,b4,t5,b5,
			t6,b6,t7,b7,t8,b8,t9,b9,t10,b10,
			t11,b11,t12,b12,".$id.",total,totalx/jmlbulan rata,
			".'"'.$supp.'"'.",".$tgl_created.",".$year.", urutan, status, maxupload, '$key_jam'
	FROM
	(
		select 	nocab,kode_comp,kode,
				b1, b2, b3, b4, b5, b6, b7, b8, b9, b10, b11, b12,
				t1, t2, t3, t4,	t5, t6, t7, t8,	t9, t10, t11, t12, ".$id.",
				total, totalx, format(sum($pembagi),0, 1) jmlbulan
		FROM
		(
			select  nocab,kode_comp, 
					b1, b2, b3, b4, b5, b6, b7, b8, b9, b10, b11, b12,
					".$id.",
					b1+b2+b3+b4+b5+b6+b7+b8+b9+b10+b11+b12 as total,
					$totalx as totalx,
					IF(b1 = 0, 0, 1) xb1, 
					IF(b2 = 0, 0, 1) xb2, 
					IF(b3 = 0, 0, 1) xb3, 
					IF(b4 = 0, 0, 1) xb4, 
					IF(b5 = 0, 0, 1) xb5, 
					IF(b6 = 0, 0, 1) xb6, 
					IF(b7 = 0, 0, 1) xb7, 
					IF(b8 = 0, 0, 1) xb8, 
					IF(b9 = 0, 0, 1) xb9, 
					IF(b10 = 0, 0, 1) xb10,
					IF(b11 = 0, 0, 1) xb11, 
					IF(b12 = 0, 0, 1) xb12, 
					sum(t1) as t1, 
					sum(t2) as t2,  
					sum(t3) as t3, 
					sum(t4) as t4,  
					sum(t5) as t5,  
					sum(t6) as t6,  
					sum(t7) as t7, 
					sum(t8) as t8,  
					sum(t9) as t9, 
					sum(t10) as t10, 
					sum(t11) as t11, 
					sum(t12) as t12,
					kode
			from
			(				
				select 	nocab, kode_comp, kode,
						sum(if(blndok = 1 and grup = 'G0503', unit, 0)) as b1,
						count(DISTINCT(if(blndok = 1 and grup = 'G0503', outlet,0)))-1 as t1,
						sum(if(blndok = 2 and grup = 'G0503', unit, 0)) as b2,
						count(DISTINCT(if(blndok = 2 and grup = 'G0503', outlet,0)))-1 as t2,
						sum(if(blndok = 3 and grup = 'G0503', unit, 0)) as b3,
						count(DISTINCT(if(blndok = 3 and grup = 'G0503', outlet,0)))-1 as t3,
						sum(if(blndok = 4 and grup = 'G0503', unit, 0)) as b4,
						count(DISTINCT(if(blndok = 4 and grup = 'G0503', outlet,0)))-1 as t4,
						sum(if(blndok = 5 and grup = 'G0503', unit, 0)) as b5,
						count(DISTINCT(if(blndok = 5 and grup = 'G0503', outlet,0)))-1 as t5,
						sum(if(blndok = 6 and grup = 'G0503', unit, 0)) as b6,
						count(DISTINCT(if(blndok = 6 and grup = 'G0503', outlet,0)))-1 as t6,
						sum(if(blndok = 7 and grup = 'G0503', unit, 0)) as b7,
						count(DISTINCT(if(blndok = 7 and grup = 'G0503', outlet,0)))-1 as t7,
						sum(if(blndok = 8 and grup = 'G0503', unit, 0)) as b8,
						count(DISTINCT(if(blndok = 8 and grup = 'G0503', outlet,0)))-1 as t8,
						sum(if(blndok = 9 and grup = 'G0503', unit, 0)) as b9,
						count(DISTINCT(if(blndok = 9 and grup = 'G0503', outlet,0)))-1 as t9,
						sum(if(blndok = 10 and grup = 'G0503', unit, 0)) as b10,
						count(DISTINCT(if(blndok = 10 and grup = 'G0503', outlet,0)))-1 as t10,
						sum(if(blndok = 11 and grup = 'G0503', unit, 0)) as b11,
						count(DISTINCT(if(blndok = 11 and grup = 'G0503', outlet,0)))-1 as t11,
						sum(if(blndok = 12 and grup = 'G0503', unit, 0)) as b12,
						count(DISTINCT(if(blndok = 12 and grup = 'G0503', outlet,0)))-1 as t12
				FROM
				(
					select 	nocab, kode_comp, kode, unit, a.kodeprod, grup, outlet, blndok 
					FROM
					(
							select 	nocab,kode_comp,blndok,
									tot1 as unit, kodeprod, 
									concat(kode_comp,nocab) as kode, concat(kode_comp,kode_lang) as outlet
							from 	data".$year.".fi
							where 	nodokjdi <> 'XXXXXX' and kodeprod like '06%' 
							union ALL
							select 	nocab,kode_comp,blndok,
									tot1 as unit, kodeprod, 
									concat(kode_comp,nocab) as kode, 0
							from 	data".$year.".ri
							where 	nodokjdi <> 'XXXXXX' and kodeprod like '06%' 
					)a LEFT JOIN 
					(
						select kodeprod, grup 
						from mpm.tabprod
						where supp ='005'
					)b on a.kodeprod = b.kodeprod
				)a GROUP BY kode					 
			)a GROUP BY kode
		)a GROUP BY kode
	)a LEFT JOIN
	(
		select concat(kode_comp, nocab) as kode,naper,kode_comp, nama_comp, sub,urutan,status,status_cluster
		from mpm.tbl_tabcomp_new
		where status = 1
		GROUP BY concat(kode_comp, nocab)
	)b on a.kode = b.kode
	LEFT JOIN
	(
			select 	max(lastupload) as maxupload,
					substring(filename,3,2) naper  
			from 	mpm.upload 
			group by naper
	)c on a.nocab = c.naper
	";      
			
	        $gabung = "insert into mpm.tbl_temp_omzet_madu ".$query;			        
	        $sql = $this->db->query($gabung);
	        
	        //echo "<pre>";
			//print_r($query);			
			//echo "</pre>";
			
			/* ============================================================== */
			if ($sql == '1') {
				
				$query="
				select 	nocab, kode_comp, a.sub, b.nama, 
						sum(t1),sum(b1),sum(t2),sum(b2),sum(t3),sum(b3),sum(t4),sum(b4),sum(t5),sum(b5),
						sum(t6),sum(b6),sum(t7),sum(b7),sum(t8),sum(b8),sum(t9),sum(b9),sum(t10),sum(b10),sum(t11),sum(b11),
						sum(t12),sum(b12),id,sum(total),sum(rata), ".'"'.$supp.'"'.",tgl_created,tahun,b.urutan,b.status,'','$key_jam'
				
				from 	mpm.tbl_temp_omzet_madu a inner JOIN
				(
						select 	naper naper2,status, urutan, nama_comp nama, sub
						from 		mpm.tbl_tabcomp_new
						WHERE 	status = '2' and status_cluster <> '1'
				)b on a.sub = b.sub
				where a.kategori_supplier ='$supp' and a.tahun = $year and a.`key` = '$key_jam'
				GROUP BY sub
				ORDER BY a.urutan asc

				";
				$gabung = "insert into mpm.tbl_temp_omzet_madu ".$query;			        
		        $sql = $this->db->query($gabung);

		        //echo "<pre>";
		        //print_r($query);
		        //echo "</pre>";


				if ($sql == '1') {
		        	
					$query = "
					select 	'', '', '', 'GRAND(TOTAL)',
							t1,b1,t2,b2,t3,b3,t4,b4,t5,b5,t6,b6,
							t7,b7,t8,b8,t9,b9,t10,b10,t11,b11,t12,b12,id,																
							total, ($totalx)/$rata as rata, 
						 	".'"'.$supp.'"'.",tgl_created,tahun,'999','3','','$key_jam'
					from
					(
						select 	sum(b1) as b1,sum(b2) as b2,sum(b3) as b3,sum(b4) as b4,
								sum(b5) as b5,sum(b6) as b6,sum(b7) as b7,sum(b8) as b8,
								sum(b9) as b9,sum(b10) as b10,sum(b11) as b11,
								sum(b12) as b12,
								sum(t1) as t1,sum(t2) as t2,sum(t3) as t3,sum(t4) as t4,
								sum(t5) as t5,sum(t6) as t6,sum(t7) as t7,sum(t8) as t8,
								sum(t9) as t9,sum(t10) as t10,sum(t11) as t11,
								sum(t12) as t12,sum(total) as total, 
						 		".'"'.$supp.'"'.",tgl_created,tahun,'999','3','','$key_jam', id
						from 	mpm.tbl_temp_omzet_madu a 
						where 	status = 1 and kategori_supplier ='$supp' and tahun = $year and a.key = '$key_jam'
					)a
					";

					$gabung = "insert into mpm.tbl_temp_omzet_madu ".$query;			        
			        $sql = $this->db->query($gabung);
			        
			        //echo "<pre>";
			        //print_r($gabung);
			        //echo "</pre>";
					
					if ($sql == '1') {
			        	
			        	$query="
							insert into mpm.tbl_temporary_user (userid,menuid,nilai,datetime,supp,tahun) 
	            			values ($id,$menuid,$total,$tgl_created,'$supp$group','$year')
						";
							        
				        $sql = $this->db->query($query);

			        }else{
			        	echo "ada kesalahan <i>Proses insert temp_omzet_grand_total</i>. silahkan ulangi";
			        }

		        }else{
		        	echo "ada kesalahan <i>Proses insert temp_omzet_sub_total</i>. silahkan ulangi";
		        }

			}else{

				echo "ada kesalahan <i>Proses insert temp_omzet_per_sub_branch</i>. silahkan ulangi";
			}

			$gabung = "
				insert into mpm.omzet_new_madu
				select 	nocab, sub, kode_comp, nama_comp, 
						t1,b1,t2,b2,t3,b3,t4,b4,t5,b5,t6,b6,t7,b7,t8,b8,t9,b9,t10,b10,t11,b11,t12,b12,id,total,rata,kategori_supplier,tgl_created,
						tahun, urutan, lastupload, `key`
				from 	mpm.tbl_temp_omzet_madu
				where 	kategori_supplier = ".'"'.$supp.'"'." and tahun = $year and `key` = '$key_jam'
				";			        
	        $sql = $this->db->query($gabung);

	        //echo "<pre>";
	        //print_r($gabung);
	        //echo "</pre>";

			if ($sql == '1') {
	        	
	        	if ($dp == NULL || $dp == '') {
		        	
				$query="
							select 	* 
							from 	mpm.omzet_new_madu
							where 	tahun = $year and
									kategori_supplier = '$supp' and
									`key` = (
											select 	max(`key`)
											from 	mpm.omzet_new_madu
											where 	tahun = $year and
													kategori_supplier = '$supp'
							)
							ORDER BY urutan asc

						";
					
					//echo "<pre>";
					//print_r($query);
					//echo "</pre>";
					
							        
			        $hasil = $this->db->query($query);
			        if ($hasil->num_rows() > 0) 
					{
						return $hasil->result();
					} else {
						return array();
					}

					/* END PROSES TAMPIL KE WEBSITE */

	        	} else {
		     
					$query="
							select 	*
							from 		mpm.omzet_new_madu
							where 		tahun = $year and
										kategori_supplier = '$supp' and
										naper in ($daftar_dp) and
										`key` = (
											select 	max(`key`)
											from 	mpm.omzet_new_madu
											where 	tahun = $year and
													kategori_supplier = '$supp'
							)
							ORDER BY urutan asc
						";
							        
			        $hasil = $this->db->query($query);
			        if ($hasil->num_rows() > 0) 
					{
						return $hasil->result();
					} else {
						return array();
					}
					/* END PROSES TAMPIL KE WEBSITE */
	        	}

	        }else{
	        	echo "ada kesalahan <i>Proses insert omzet_new</i>. silahkan ulangi";
	        }
									
        }
	    
	}


	public function omzet_all_dp_group_mywell($dataSegment){

	/* ---------DEFINISI VARIABEL----------------- */		    
	    $tgl_created='"'.date('Y-m-d H:i:s').'"';
	    $jam=date('ymdHis');
	    $key_jam = $jam;
	    $id=$this->session->userdata('id');
		$year = $dataSegment['year'];
		$group = $dataSegment['group'];	
		$supp = $dataSegment['supp'];
		$uri1 = $dataSegment['menu_uri1'];
		$uri2 = $dataSegment['menu_uri2'];
		$uri3 = $dataSegment['menu_uri3'];
		$menuid = $dataSegment['menuid'];
	
	/* ---------Hak Lihat DP----------------- */		    
		$dp = $dataSegment['query_dp'];        
        if ($dp == NULL || $dp == '') {	$daftar_dp = ''; } else { $daftar_dp = $dp; }

    /* ---------Cari Total FI----------------- */
    	$this->db->select('count(*) as rows');
        $query = $this->db->get("data$year.fi");
        foreach($query->result() as $r)
        {
           $total = $r->rows;
        }	
    
    /* ---------Cari histori di temporary user----------------- */
	    $sql="
	    	select 	1 
	    	from 	mpm.tbl_temporary_user 
	    	where   menuid = $menuid and
	    			supp = '$supp$group' and
	    			tahun = '$year' and 
	    			nilai = $total
	    			";
	    $query = $this->db->query($sql);
        //echo "<pre>";
        //print_r($sql);
        //echo "</pre>";
        if($query->num_rows()>0) //kalau histori ditemukan
        {        	
			if ($dp == NULL || $dp == '') // kalau boleh lihat semua DP
			{	
				$query="
					select 	*
					from 		mpm.omzet_new_mywell
					where 		tahun = $year and
								kategori_supplier = '$supp' and
								`key` = (
									select 	max(`key`)
									from 	mpm.omzet_new_mywell
									where 	tahun = $year and
											kategori_supplier = '$supp'
									)
					ORDER BY urutan asc
				";
				    
		        $hasil = $this->db->query($query);
		        if ($hasil->num_rows() > 0) 
				{ return $hasil->result(); } else { return array(); }

	        } else {
		        	
					$query="
							select 	*
							from 		mpm.omzet_new_mywell
							where 		tahun = $year and
										kategori_supplier = '$supp' and
										naper in ($daftar_dp) and
										`key` = (
											select 	max(`key`)
											from 	mpm.omzet_new_mywell
											where 	tahun = $year and
													kategori_supplier = '$supp'
										)
							ORDER BY urutan asc
						"; 
			        $hasil = $this->db->query($query);
		        if ($hasil->num_rows() > 0) 
				{ return $hasil->result(); } else { return array(); }

					/* END PROSES TAMPIL KE WEBSITE */
	        }				
        }else{ //kalau histori tidak ditemukan

    	/* === Proses pendefinisian bulan, pembagi untuk menentukan total dan rata2 === */				
			$bulan = date("m");
			$tahunsekarang = date("Y");
				if ($year == $tahunsekarang) //jika memilih tahun berjalan
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
						$totalx = 'b1+b2+b3+b4+b5+b6+b7+b8+b9+b10+b11+b12';
						$pembagi = 'xb1+xb2+xb3+xb4+xb5+xb6+xb7+xb8+xb9+xb10+xb11+xb12';
						$rata = 12;
				}
				/* ========================================== */

				/* == PROSES DELETE MPM.OMZET_NEW ===== */
				$query = "	
						delete from mpm.omzet_new_mywell
						where kategori_supplier = ".'"'.$supp.'"'." and tahun = $year and id = $id
							";
				//echo "<pre>";
				//print_r($query);
				//echo "</pre>";
				$sql = $this->db->query($query);

				/* == PROSES DELETE mpm.tbl_temp_omzet = */
				$query = "	
						delete from mpm.tbl_temp_omzet_mywell
						where kategori_supplier = ".'"'.$supp.'"'." and tahun = $year and and id = $id
							";
				$sql = $this->db->query($query);

				/* ========== PROSES INSERT KE mpm.tbl_temp_omzet =================== */
			
$query = "	   
	select  a.nocab, a.kode_comp, b.sub, b.nama_comp,
			t1,b1,t2,b2,t3,b3,t4,b4,t5,b5,
			t6,b6,t7,b7,t8,b8,t9,b9,t10,b10,
			t11,b11,t12,b12,".$id.",total,totalx/jmlbulan rata,
			".'"'.$supp.'"'.",".$tgl_created.",".$year.", urutan, status, maxupload, '$key_jam'
	FROM
	(
		select 	nocab,kode_comp,kode,
				b1, b2, b3, b4, b5, b6, b7, b8, b9, b10, b11, b12,
				t1, t2, t3, t4,	t5, t6, t7, t8,	t9, t10, t11, t12, ".$id.",
				total, totalx, format(sum($pembagi),0, 1) jmlbulan
		FROM
		(
			select  nocab,kode_comp, 
					b1, b2, b3, b4, b5, b6, b7, b8, b9, b10, b11, b12,
					".$id.",
					b1+b2+b3+b4+b5+b6+b7+b8+b9+b10+b11+b12 as total,
					$totalx as totalx,
					IF(b1 = 0, 0, 1) xb1, 
					IF(b2 = 0, 0, 1) xb2, 
					IF(b3 = 0, 0, 1) xb3, 
					IF(b4 = 0, 0, 1) xb4, 
					IF(b5 = 0, 0, 1) xb5, 
					IF(b6 = 0, 0, 1) xb6, 
					IF(b7 = 0, 0, 1) xb7, 
					IF(b8 = 0, 0, 1) xb8, 
					IF(b9 = 0, 0, 1) xb9, 
					IF(b10 = 0, 0, 1) xb10,
					IF(b11 = 0, 0, 1) xb11, 
					IF(b12 = 0, 0, 1) xb12, 
					sum(t1) as t1, 
					sum(t2) as t2,  
					sum(t3) as t3, 
					sum(t4) as t4,  
					sum(t5) as t5,  
					sum(t6) as t6,  
					sum(t7) as t7, 
					sum(t8) as t8,  
					sum(t9) as t9, 
					sum(t10) as t10, 
					sum(t11) as t11, 
					sum(t12) as t12,
					kode
			from
			(				
				select 	nocab, kode_comp, kode,
						sum(if(blndok = 1 and grup = 'G0504', unit, 0)) as b1,
						count(DISTINCT(if(blndok = 1 and grup = 'G0504', outlet,0)))-1 as t1,
						sum(if(blndok = 2 and grup = 'G0504', unit, 0)) as b2,
						count(DISTINCT(if(blndok = 2 and grup = 'G0504', outlet,0)))-1 as t2,
						sum(if(blndok = 3 and grup = 'G0504', unit, 0)) as b3,
						count(DISTINCT(if(blndok = 3 and grup = 'G0504', outlet,0)))-1 as t3,
						sum(if(blndok = 4 and grup = 'G0504', unit, 0)) as b4,
						count(DISTINCT(if(blndok = 4 and grup = 'G0504', outlet,0)))-1 as t4,
						sum(if(blndok = 5 and grup = 'G0504', unit, 0)) as b5,
						count(DISTINCT(if(blndok = 5 and grup = 'G0504', outlet,0)))-1 as t5,
						sum(if(blndok = 6 and grup = 'G0504', unit, 0)) as b6,
						count(DISTINCT(if(blndok = 6 and grup = 'G0504', outlet,0)))-1 as t6,
						sum(if(blndok = 7 and grup = 'G0504', unit, 0)) as b7,
						count(DISTINCT(if(blndok = 7 and grup = 'G0504', outlet,0)))-1 as t7,
						sum(if(blndok = 8 and grup = 'G0504', unit, 0)) as b8,
						count(DISTINCT(if(blndok = 8 and grup = 'G0504', outlet,0)))-1 as t8,
						sum(if(blndok = 9 and grup = 'G0504', unit, 0)) as b9,
						count(DISTINCT(if(blndok = 9 and grup = 'G0504', outlet,0)))-1 as t9,
						sum(if(blndok = 10 and grup = 'G0504', unit, 0)) as b10,
						count(DISTINCT(if(blndok = 10 and grup = 'G0504', outlet,0)))-1 as t10,
						sum(if(blndok = 11 and grup = 'G0504', unit, 0)) as b11,
						count(DISTINCT(if(blndok = 11 and grup = 'G0504', outlet,0)))-1 as t11,
						sum(if(blndok = 12 and grup = 'G0504', unit, 0)) as b12,
						count(DISTINCT(if(blndok = 12 and grup = 'G0504', outlet,0)))-1 as t12
				FROM
				(
					select 	nocab, kode_comp, kode, unit, a.kodeprod, grup, outlet, blndok 
					FROM
					(
							select 	nocab,kode_comp,blndok,
									tot1 as unit, kodeprod, 
									concat(kode_comp,nocab) as kode, concat(kode_comp,kode_lang) as outlet
							from 	data".$year.".fi
							where 	nodokjdi <> 'XXXXXX' and kodeprod like '06%' 
							union ALL
							select 	nocab,kode_comp,blndok,
									tot1 as unit, kodeprod, 
									concat(kode_comp,nocab) as kode, 0
							from 	data".$year.".ri
							where 	nodokjdi <> 'XXXXXX' and kodeprod like '06%' 
					)a LEFT JOIN 
					(
						select kodeprod, grup 
						from mpm.tabprod
						where supp ='005'
					)b on a.kodeprod = b.kodeprod
				)a GROUP BY kode					 
			)a GROUP BY kode
		)a GROUP BY kode
	)a LEFT JOIN
	(
		select concat(kode_comp, nocab) as kode,naper,kode_comp, nama_comp, sub,urutan,status,status_cluster
		from mpm.tbl_tabcomp_new
		where status = 1
		GROUP BY concat(kode_comp, nocab)
	)b on a.kode = b.kode
	LEFT JOIN
	(
			select 	max(lastupload) as maxupload,
					substring(filename,3,2) naper  
			from 	mpm.upload 
			group by naper
	)c on a.nocab = c.naper
	";      
			
	        $gabung = "insert into mpm.tbl_temp_omzet_mywell ".$query;			        
	        $sql = $this->db->query($gabung);
	        
	        //echo "<pre>";
			//print_r($query);			
			//echo "</pre>";
			
			/* ============================================================== */
			if ($sql == '1') {
				
				$query="
				select 	nocab, kode_comp, a.sub, b.nama, 
						sum(t1),sum(b1),sum(t2),sum(b2),sum(t3),sum(b3),sum(t4),sum(b4),sum(t5),sum(b5),
						sum(t6),sum(b6),sum(t7),sum(b7),sum(t8),sum(b8),sum(t9),sum(b9),sum(t10),sum(b10),sum(t11),sum(b11),
						sum(t12),sum(b12),id,sum(total),sum(rata), ".'"'.$supp.'"'.",tgl_created,tahun,b.urutan,b.status,'','$key_jam'
				
				from 	mpm.tbl_temp_omzet_mywell a inner JOIN
				(
						select 	naper naper2,status, urutan, nama_comp nama, sub
						from 		mpm.tbl_tabcomp_new
						WHERE 	status = '2' and status_cluster <> '1'
				)b on a.sub = b.sub
				where a.kategori_supplier ='$supp' and a.tahun = $year and a.`key` = '$key_jam'
				GROUP BY sub
				ORDER BY a.urutan asc

				";
				$gabung = "insert into mpm.tbl_temp_omzet_mywell ".$query;			        
		        $sql = $this->db->query($gabung);

		        //echo "<pre>";
		        //print_r($query);
		        //echo "</pre>";


				if ($sql == '1') {
		        	
					$query = "
					select 	'', '', '', 'GRAND(TOTAL)',
							t1,b1,t2,b2,t3,b3,t4,b4,t5,b5,t6,b6,
							t7,b7,t8,b8,t9,b9,t10,b10,t11,b11,t12,b12,id,																
							total, ($totalx)/$rata as rata, 
						 	".'"'.$supp.'"'.",tgl_created,tahun,'999','3','','$key_jam'
					from
					(
						select 	sum(b1) as b1,sum(b2) as b2,sum(b3) as b3,sum(b4) as b4,
								sum(b5) as b5,sum(b6) as b6,sum(b7) as b7,sum(b8) as b8,
								sum(b9) as b9,sum(b10) as b10,sum(b11) as b11,
								sum(b12) as b12,
								sum(t1) as t1,sum(t2) as t2,sum(t3) as t3,sum(t4) as t4,
								sum(t5) as t5,sum(t6) as t6,sum(t7) as t7,sum(t8) as t8,
								sum(t9) as t9,sum(t10) as t10,sum(t11) as t11,
								sum(t12) as t12,sum(total) as total, 
						 		".'"'.$supp.'"'.",tgl_created,tahun,'999','3','','$key_jam', id
						from 	mpm.tbl_temp_omzet_mywell a 
						where 	status = 1 and kategori_supplier ='$supp' and tahun = $year and a.key = '$key_jam'
					)a
					";

					$gabung = "insert into mpm.tbl_temp_omzet_mywell ".$query;			        
			        $sql = $this->db->query($gabung);
			        
			        //echo "<pre>";
			        //print_r($gabung);
			        //echo "</pre>";
					
					if ($sql == '1') {
			        	
			        	$query="
							insert into mpm.tbl_temporary_user (userid,menuid,nilai,datetime,supp,tahun) 
	            			values ($id,$menuid,$total,$tgl_created,'$supp$group','$year')
						";
							        
				        $sql = $this->db->query($query);

			        }else{
			        	echo "ada kesalahan <i>Proses insert temp_omzet_grand_total</i>. silahkan ulangi";
			        }

		        }else{
		        	echo "ada kesalahan <i>Proses insert temp_omzet_sub_total</i>. silahkan ulangi";
		        }

			}else{

				echo "ada kesalahan <i>Proses insert temp_omzet_per_sub_branch</i>. silahkan ulangi";
			}

			$gabung = "
				insert into mpm.omzet_new_mywell
				select 	nocab, sub, kode_comp, nama_comp, 
						t1,b1,t2,b2,t3,b3,t4,b4,t5,b5,t6,b6,t7,b7,t8,b8,t9,b9,t10,b10,t11,b11,t12,b12,id,total,rata,kategori_supplier,tgl_created,
						tahun, urutan, lastupload, `key`
				from 	mpm.tbl_temp_omzet_mywell
				where 	kategori_supplier = ".'"'.$supp.'"'." and tahun = $year and `key` = '$key_jam'
				";			        
	        $sql = $this->db->query($gabung);

	        //echo "<pre>";
	        //print_r($gabung);
	        //echo "</pre>";

			if ($sql == '1') {
	        	
	        	if ($dp == NULL || $dp == '') {
		        	
				$query="
							select 	* 
							from 	mpm.omzet_new_mywell
							where 	tahun = $year and
									kategori_supplier = '$supp' and
									`key` = (
											select 	max(`key`)
											from 	mpm.omzet_new_mywell
											where 	tahun = $year and
													kategori_supplier = '$supp'
							)
							ORDER BY urutan asc

						";
					
					//echo "<pre>";
					//print_r($query);
					//echo "</pre>";
					
							        
			        $hasil = $this->db->query($query);
			        if ($hasil->num_rows() > 0) 
					{
						return $hasil->result();
					} else {
						return array();
					}

					/* END PROSES TAMPIL KE WEBSITE */

	        	} else {
		     
					$query="
							select 	*
							from 		mpm.omzet_new_mywell
							where 		tahun = $year and
										kategori_supplier = '$supp' and
										naper in ($daftar_dp) and
										`key` = (
											select 	max(`key`)
											from 	mpm.omzet_new_mywell
											where 	tahun = $year and
													kategori_supplier = '$supp'
							)
							ORDER BY urutan asc
						";
							        
			        $hasil = $this->db->query($query);
			        if ($hasil->num_rows() > 0) 
					{
						return $hasil->result();
					} else {
						return array();
					}
					/* END PROSES TAMPIL KE WEBSITE */
	        	}

	        }else{
	        	echo "ada kesalahan <i>Proses insert omzet_new</i>. silahkan ulangi";
	        }
									
        }
	    
	}

	public function omzet_all_dp_group_tresnojoyo($dataSegment){

	/* ---------DEFINISI VARIABEL----------------- */		    
	    $tgl_created='"'.date('Y-m-d H:i:s').'"';
	    $jam=date('ymdHis');
	    $key_jam = $jam;
	    $id=$this->session->userdata('id');
		$year = $dataSegment['year'];
		$group = $dataSegment['group'];	
		$supp = $dataSegment['supp'];
		$uri1 = $dataSegment['menu_uri1'];
		$uri2 = $dataSegment['menu_uri2'];
		$uri3 = $dataSegment['menu_uri3'];
		$menuid = $dataSegment['menuid'];
	
	/* ---------Hak Lihat DP----------------- */		    
		$dp = $dataSegment['query_dp'];        
        if ($dp == NULL || $dp == '') {	$daftar_dp = ''; } else { $daftar_dp = $dp; }

    /* ---------Cari Total FI----------------- */
    	$this->db->select('count(*) as rows');
        $query = $this->db->get("data$year.fi");
        foreach($query->result() as $r)
        {
           $total = $r->rows;
        }	
    
    /* ---------Cari histori di temporary user----------------- */
	    $sql="
	    	select 	1 
	    	from 	mpm.tbl_temporary_user 
	    	where   menuid = $menuid and
	    			supp = '$supp$group' and
	    			tahun = '$year' and 
	    			nilai = $total
	    			";
	    $query = $this->db->query($sql);
        //echo "<pre>";
        //print_r($sql);
        //echo "</pre>";
        if($query->num_rows()>0) //kalau histori ditemukan
        {        	
			if ($dp == NULL || $dp == '') // kalau boleh lihat semua DP
			{	
				$query="
					select 	*
					from 		mpm.omzet_new_tresnojoyo
					where 		tahun = $year and
								kategori_supplier = '$supp' and
								`key` = (
									select 	max(`key`)
									from 	mpm.omzet_new_tresnojoyo
									where 	tahun = $year and
											kategori_supplier = '$supp'
									)
					ORDER BY urutan asc
				";
				    
		        $hasil = $this->db->query($query);
		        if ($hasil->num_rows() > 0) 
				{ return $hasil->result(); } else { return array(); }

	        } else {
		        	
					$query="
							select 	*
							from 		mpm.omzet_new_tresnojoyo
							where 		tahun = $year and
										kategori_supplier = '$supp' and
										naper in ($daftar_dp) and
										`key` = (
											select 	max(`key`)
											from 	mpm.omzet_new_tresnojoyo
											where 	tahun = $year and
													kategori_supplier = '$supp'
										)
							ORDER BY urutan asc
						"; 
			        $hasil = $this->db->query($query);
		        if ($hasil->num_rows() > 0) 
				{ return $hasil->result(); } else { return array(); }

					/* END PROSES TAMPIL KE WEBSITE */
	        }				
        }else{ //kalau histori tidak ditemukan

    	/* === Proses pendefinisian bulan, pembagi untuk menentukan total dan rata2 === */				
			$bulan = date("m");
			$tahunsekarang = date("Y");
				if ($year == $tahunsekarang) //jika memilih tahun berjalan
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
						$totalx = 'b1+b2+b3+b4+b5+b6+b7+b8+b9+b10+b11+b12';
						$pembagi = 'xb1+xb2+xb3+xb4+xb5+xb6+xb7+xb8+xb9+xb10+xb11+xb12';
						$rata = 12;
				}
				/* ========================================== */

				/* == PROSES DELETE MPM.OMZET_NEW ===== */
				$query = "	
						delete from mpm.omzet_new_tresnojoyo
						where kategori_supplier = ".'"'.$supp.'"'." and tahun = $year and id = $id
							";
				//echo "<pre>";
				//print_r($query);
				//echo "</pre>";
				$sql = $this->db->query($query);

				/* == PROSES DELETE mpm.tbl_temp_omzet = */
				$query = "	
						delete from mpm.tbl_temp_omzet_tresnojoyo
						where kategori_supplier = ".'"'.$supp.'"'." and tahun = $year and and id = $id
							";
				$sql = $this->db->query($query);

				/* ========== PROSES INSERT KE mpm.tbl_temp_omzet =================== */
			
$query = "	   
	select  a.nocab, a.kode_comp, b.sub, b.nama_comp,
			t1,b1,t2,b2,t3,b3,t4,b4,t5,b5,
			t6,b6,t7,b7,t8,b8,t9,b9,t10,b10,
			t11,b11,t12,b12,".$id.",total,totalx/jmlbulan rata,
			".'"'.$supp.'"'.",".$tgl_created.",".$year.", urutan, status, maxupload, '$key_jam'
	FROM
	(
		select 	nocab,kode_comp,kode,
				b1, b2, b3, b4, b5, b6, b7, b8, b9, b10, b11, b12,
				t1, t2, t3, t4,	t5, t6, t7, t8,	t9, t10, t11, t12, ".$id.",
				total, totalx, format(sum($pembagi),0, 1) jmlbulan
		FROM
		(
			select  nocab,kode_comp, 
					b1, b2, b3, b4, b5, b6, b7, b8, b9, b10, b11, b12,
					".$id.",
					b1+b2+b3+b4+b5+b6+b7+b8+b9+b10+b11+b12 as total,
					$totalx as totalx,
					IF(b1 = 0, 0, 1) xb1, 
					IF(b2 = 0, 0, 1) xb2, 
					IF(b3 = 0, 0, 1) xb3, 
					IF(b4 = 0, 0, 1) xb4, 
					IF(b5 = 0, 0, 1) xb5, 
					IF(b6 = 0, 0, 1) xb6, 
					IF(b7 = 0, 0, 1) xb7, 
					IF(b8 = 0, 0, 1) xb8, 
					IF(b9 = 0, 0, 1) xb9, 
					IF(b10 = 0, 0, 1) xb10,
					IF(b11 = 0, 0, 1) xb11, 
					IF(b12 = 0, 0, 1) xb12, 
					sum(t1) as t1, 
					sum(t2) as t2,  
					sum(t3) as t3, 
					sum(t4) as t4,  
					sum(t5) as t5,  
					sum(t6) as t6,  
					sum(t7) as t7, 
					sum(t8) as t8,  
					sum(t9) as t9, 
					sum(t10) as t10, 
					sum(t11) as t11, 
					sum(t12) as t12,
					kode
			from
			(				
				select 	nocab, kode_comp, kode,
						sum(if(blndok = 1 and grup = 'G0505', unit, 0)) as b1,
						count(DISTINCT(if(blndok = 1 and grup = 'G0505', outlet,0)))-1 as t1,
						sum(if(blndok = 2 and grup = 'G0505', unit, 0)) as b2,
						count(DISTINCT(if(blndok = 2 and grup = 'G0505', outlet,0)))-1 as t2,
						sum(if(blndok = 3 and grup = 'G0505', unit, 0)) as b3,
						count(DISTINCT(if(blndok = 3 and grup = 'G0505', outlet,0)))-1 as t3,
						sum(if(blndok = 4 and grup = 'G0505', unit, 0)) as b4,
						count(DISTINCT(if(blndok = 4 and grup = 'G0505', outlet,0)))-1 as t4,
						sum(if(blndok = 5 and grup = 'G0505', unit, 0)) as b5,
						count(DISTINCT(if(blndok = 5 and grup = 'G0505', outlet,0)))-1 as t5,
						sum(if(blndok = 6 and grup = 'G0505', unit, 0)) as b6,
						count(DISTINCT(if(blndok = 6 and grup = 'G0505', outlet,0)))-1 as t6,
						sum(if(blndok = 7 and grup = 'G0505', unit, 0)) as b7,
						count(DISTINCT(if(blndok = 7 and grup = 'G0505', outlet,0)))-1 as t7,
						sum(if(blndok = 8 and grup = 'G0505', unit, 0)) as b8,
						count(DISTINCT(if(blndok = 8 and grup = 'G0505', outlet,0)))-1 as t8,
						sum(if(blndok = 9 and grup = 'G0505', unit, 0)) as b9,
						count(DISTINCT(if(blndok = 9 and grup = 'G0505', outlet,0)))-1 as t9,
						sum(if(blndok = 10 and grup = 'G0505', unit, 0)) as b10,
						count(DISTINCT(if(blndok = 10 and grup = 'G0505', outlet,0)))-1 as t10,
						sum(if(blndok = 11 and grup = 'G0505', unit, 0)) as b11,
						count(DISTINCT(if(blndok = 11 and grup = 'G0505', outlet,0)))-1 as t11,
						sum(if(blndok = 12 and grup = 'G0505', unit, 0)) as b12,
						count(DISTINCT(if(blndok = 12 and grup = 'G0505', outlet,0)))-1 as t12
				FROM
				(
					select 	nocab, kode_comp, kode, unit, a.kodeprod, grup, outlet, blndok 
					FROM
					(
							select 	nocab,kode_comp,blndok,
									tot1 as unit, kodeprod, 
									concat(kode_comp,nocab) as kode, concat(kode_comp,kode_lang) as outlet
							from 	data".$year.".fi
							where 	nodokjdi <> 'XXXXXX' and kodeprod like '06%' 
							union ALL
							select 	nocab,kode_comp,blndok,
									tot1 as unit, kodeprod, 
									concat(kode_comp,nocab) as kode, 0
							from 	data".$year.".ri
							where 	nodokjdi <> 'XXXXXX' and kodeprod like '06%' 
					)a LEFT JOIN 
					(
						select kodeprod, grup 
						from mpm.tabprod
						where supp ='005'
					)b on a.kodeprod = b.kodeprod
				)a GROUP BY kode					 
			)a GROUP BY kode
		)a GROUP BY kode
	)a LEFT JOIN
	(
		select concat(kode_comp, nocab) as kode,naper,kode_comp, nama_comp, sub,urutan,status,status_cluster
		from mpm.tbl_tabcomp_new
		where status = 1
		GROUP BY concat(kode_comp, nocab)
	)b on a.kode = b.kode
	LEFT JOIN
	(
			select 	max(lastupload) as maxupload,
					substring(filename,3,2) naper  
			from 	mpm.upload 
			group by naper
	)c on a.nocab = c.naper
	";      
			
	        $gabung = "insert into mpm.tbl_temp_omzet_tresnojoyo ".$query;			        
	        $sql = $this->db->query($gabung);
	        
	        //echo "<pre>";
			//print_r($query);			
			//echo "</pre>";
			
			/* ============================================================== */
			if ($sql == '1') {
				
				$query="
				select 	nocab, kode_comp, a.sub, b.nama, 
						sum(t1),sum(b1),sum(t2),sum(b2),sum(t3),sum(b3),sum(t4),sum(b4),sum(t5),sum(b5),
						sum(t6),sum(b6),sum(t7),sum(b7),sum(t8),sum(b8),sum(t9),sum(b9),sum(t10),sum(b10),sum(t11),sum(b11),
						sum(t12),sum(b12),id,sum(total),sum(rata), ".'"'.$supp.'"'.",tgl_created,tahun,b.urutan,b.status,'','$key_jam'
				
				from 	mpm.tbl_temp_omzet_tresnojoyo a inner JOIN
				(
						select 	naper naper2,status, urutan, nama_comp nama, sub
						from 		mpm.tbl_tabcomp_new
						WHERE 	status = '2' and status_cluster <> '1'
				)b on a.sub = b.sub
				where a.kategori_supplier ='$supp' and a.tahun = $year and a.`key` = '$key_jam'
				GROUP BY sub
				ORDER BY a.urutan asc

				";
				$gabung = "insert into mpm.tbl_temp_omzet_tresnojoyo ".$query;			        
		        $sql = $this->db->query($gabung);

		        //echo "<pre>";
		        //print_r($query);
		        //echo "</pre>";


				if ($sql == '1') {
		        	
					$query = "
					select 	'', '', '', 'GRAND(TOTAL)',
							t1,b1,t2,b2,t3,b3,t4,b4,t5,b5,t6,b6,
							t7,b7,t8,b8,t9,b9,t10,b10,t11,b11,t12,b12,id,																
							total, ($totalx)/$rata as rata, 
						 	".'"'.$supp.'"'.",tgl_created,tahun,'999','3','','$key_jam'
					from
					(
						select 	sum(b1) as b1,sum(b2) as b2,sum(b3) as b3,sum(b4) as b4,
								sum(b5) as b5,sum(b6) as b6,sum(b7) as b7,sum(b8) as b8,
								sum(b9) as b9,sum(b10) as b10,sum(b11) as b11,
								sum(b12) as b12,
								sum(t1) as t1,sum(t2) as t2,sum(t3) as t3,sum(t4) as t4,
								sum(t5) as t5,sum(t6) as t6,sum(t7) as t7,sum(t8) as t8,
								sum(t9) as t9,sum(t10) as t10,sum(t11) as t11,
								sum(t12) as t12,sum(total) as total, 
						 		".'"'.$supp.'"'.",tgl_created,tahun,'999','3','','$key_jam', id
						from 	mpm.tbl_temp_omzet_tresnojoyo a 
						where 	status = 1 and kategori_supplier ='$supp' and tahun = $year and a.key = '$key_jam'
					)a
					";

					$gabung = "insert into mpm.tbl_temp_omzet_tresnojoyo ".$query;			        
			        $sql = $this->db->query($gabung);
			        
			        //echo "<pre>";
			        //print_r($gabung);
			        //echo "</pre>";
					
					if ($sql == '1') {
			        	
			        	$query="
							insert into mpm.tbl_temporary_user (userid,menuid,nilai,datetime,supp,tahun) 
	            			values ($id,$menuid,$total,$tgl_created,'$supp$group','$year')
						";
							        
				        $sql = $this->db->query($query);

			        }else{
			        	echo "ada kesalahan <i>Proses insert temp_omzet_grand_total</i>. silahkan ulangi";
			        }

		        }else{
		        	echo "ada kesalahan <i>Proses insert temp_omzet_sub_total</i>. silahkan ulangi";
		        }

			}else{

				echo "ada kesalahan <i>Proses insert temp_omzet_per_sub_branch</i>. silahkan ulangi";
			}

			$gabung = "
				insert into mpm.omzet_new_tresnojoyo
				select 	nocab, sub, kode_comp, nama_comp, 
						t1,b1,t2,b2,t3,b3,t4,b4,t5,b5,t6,b6,t7,b7,t8,b8,t9,b9,t10,b10,t11,b11,t12,b12,id,total,rata,kategori_supplier,tgl_created,
						tahun, urutan, lastupload, `key`
				from 	mpm.tbl_temp_omzet_tresnojoyo
				where 	kategori_supplier = ".'"'.$supp.'"'." and tahun = $year and `key` = '$key_jam'
				";			        
	        $sql = $this->db->query($gabung);

	        //echo "<pre>";
	        //print_r($gabung);
	        //echo "</pre>";

			if ($sql == '1') {
	        	
	        	if ($dp == NULL || $dp == '') {
		        	
				$query="
							select 	* 
							from 	mpm.omzet_new_tresnojoyo
							where 	tahun = $year and
									kategori_supplier = '$supp' and
									`key` = (
											select 	max(`key`)
											from 	mpm.omzet_new_tresnojoyo
											where 	tahun = $year and
													kategori_supplier = '$supp'
							)
							ORDER BY urutan asc

						";
					
					//echo "<pre>";
					//print_r($query);
					//echo "</pre>";
					
							        
			        $hasil = $this->db->query($query);
			        if ($hasil->num_rows() > 0) 
					{
						return $hasil->result();
					} else {
						return array();
					}

					/* END PROSES TAMPIL KE WEBSITE */

	        	} else {
		     
					$query="
							select 	*
							from 		mpm.omzet_new_tresnojoyo
							where 		tahun = $year and
										kategori_supplier = '$supp' and
										naper in ($daftar_dp) and
										`key` = (
											select 	max(`key`)
											from 	mpm.omzet_new_tresnojoyo
											where 	tahun = $year and
													kategori_supplier = '$supp'
							)
							ORDER BY urutan asc
						";
							        
			        $hasil = $this->db->query($query);
			        if ($hasil->num_rows() > 0) 
					{
						return $hasil->result();
					} else {
						return array();
					}
					/* END PROSES TAMPIL KE WEBSITE */
	        	}

	        }else{
	        	echo "ada kesalahan <i>Proses insert omzet_new</i>. silahkan ulangi";
	        }
									
        }
	    
	}

	public function omzet_all_dp_group_otherus($dataSegment){

	/* ---------DEFINISI VARIABEL----------------- */		    
	    $tgl_created='"'.date('Y-m-d H:i:s').'"';
	    $jam=date('ymdHis');
	    $key_jam = $jam;
	    $id=$this->session->userdata('id');
		$year = $dataSegment['year'];
		$group = $dataSegment['group'];	
		$supp = $dataSegment['supp'];
		$uri1 = $dataSegment['menu_uri1'];
		$uri2 = $dataSegment['menu_uri2'];
		$uri3 = $dataSegment['menu_uri3'];
		$menuid = $dataSegment['menuid'];
	
	/* ---------Hak Lihat DP----------------- */		    
		$dp = $dataSegment['query_dp'];        
        if ($dp == NULL || $dp == '') {	$daftar_dp = ''; } else { $daftar_dp = $dp; }

    /* ---------Cari Total FI----------------- */
    	$this->db->select('count(*) as rows');
        $query = $this->db->get("data$year.fi");
        foreach($query->result() as $r)
        {
           $total = $r->rows;
        }	
    
    /* ---------Cari histori di temporary user----------------- */
	    $sql="
	    	select 	1 
	    	from 	mpm.tbl_temporary_user 
	    	where   menuid = $menuid and
	    			supp = '$supp$group' and
	    			tahun = '$year' and 
	    			nilai = $total
	    			";
	    $query = $this->db->query($sql);
        //echo "<pre>";
        //print_r($sql);
        //echo "</pre>";
        if($query->num_rows()>0) //kalau histori ditemukan
        {        	
			if ($dp == NULL || $dp == '') // kalau boleh lihat semua DP
			{	
				$query="
					select 	*
					from 		mpm.omzet_new_otherus
					where 		tahun = $year and
								kategori_supplier = '$supp' and
								`key` = (
									select 	max(`key`)
									from 	mpm.omzet_new_otherus
									where 	tahun = $year and
											kategori_supplier = '$supp'
									)
					ORDER BY urutan asc
				";
				    
		        $hasil = $this->db->query($query);
		        if ($hasil->num_rows() > 0) 
				{ return $hasil->result(); } else { return array(); }

	        } else {
		        	
					$query="
							select 	*
							from 		mpm.omzet_new_otherus
							where 		tahun = $year and
										kategori_supplier = '$supp' and
										naper in ($daftar_dp) and
										`key` = (
											select 	max(`key`)
											from 	mpm.omzet_new_otherus
											where 	tahun = $year and
													kategori_supplier = '$supp'
										)
							ORDER BY urutan asc
						"; 
			        $hasil = $this->db->query($query);
		        if ($hasil->num_rows() > 0) 
				{ return $hasil->result(); } else { return array(); }

					/* END PROSES TAMPIL KE WEBSITE */
	        }				
        }else{ //kalau histori tidak ditemukan

    	/* === Proses pendefinisian bulan, pembagi untuk menentukan total dan rata2 === */				
			$bulan = date("m");
			$tahunsekarang = date("Y");
				if ($year == $tahunsekarang) //jika memilih tahun berjalan
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
						$totalx = 'b1+b2+b3+b4+b5+b6+b7+b8+b9+b10+b11+b12';
						$pembagi = 'xb1+xb2+xb3+xb4+xb5+xb6+xb7+xb8+xb9+xb10+xb11+xb12';
						$rata = 12;
				}
				/* ========================================== */

				/* == PROSES DELETE MPM.OMZET_NEW ===== */
				$query = "	
						delete from mpm.omzet_new_otherus
						where kategori_supplier = ".'"'.$supp.'"'." and tahun = $year and id = $id
							";
				//echo "<pre>";
				//print_r($query);
				//echo "</pre>";
				$sql = $this->db->query($query);

				/* == PROSES DELETE mpm.tbl_temp_omzet = */
				$query = "	
						delete from mpm.tbl_temp_omzet_otherus
						where kategori_supplier = ".'"'.$supp.'"'." and tahun = $year and and id = $id
							";
				$sql = $this->db->query($query);

				/* ========== PROSES INSERT KE mpm.tbl_temp_omzet =================== */
			
$query = "	   
	select  a.nocab, a.kode_comp, b.sub, b.nama_comp,
			t1,b1,t2,b2,t3,b3,t4,b4,t5,b5,
			t6,b6,t7,b7,t8,b8,t9,b9,t10,b10,
			t11,b11,t12,b12,".$id.",total,totalx/jmlbulan rata,
			".'"'.$supp.'"'.",".$tgl_created.",".$year.", urutan, status, maxupload, '$key_jam'
	FROM
	(
		select 	nocab,kode_comp,kode,
				b1, b2, b3, b4, b5, b6, b7, b8, b9, b10, b11, b12,
				t1, t2, t3, t4,	t5, t6, t7, t8,	t9, t10, t11, t12, ".$id.",
				total, totalx, format(sum($pembagi),0, 1) jmlbulan
		FROM
		(
			select  nocab,kode_comp, 
					b1, b2, b3, b4, b5, b6, b7, b8, b9, b10, b11, b12,
					".$id.",
					b1+b2+b3+b4+b5+b6+b7+b8+b9+b10+b11+b12 as total,
					$totalx as totalx,
					IF(b1 = 0, 0, 1) xb1, 
					IF(b2 = 0, 0, 1) xb2, 
					IF(b3 = 0, 0, 1) xb3, 
					IF(b4 = 0, 0, 1) xb4, 
					IF(b5 = 0, 0, 1) xb5, 
					IF(b6 = 0, 0, 1) xb6, 
					IF(b7 = 0, 0, 1) xb7, 
					IF(b8 = 0, 0, 1) xb8, 
					IF(b9 = 0, 0, 1) xb9, 
					IF(b10 = 0, 0, 1) xb10,
					IF(b11 = 0, 0, 1) xb11, 
					IF(b12 = 0, 0, 1) xb12, 
					sum(t1) as t1, 
					sum(t2) as t2,  
					sum(t3) as t3, 
					sum(t4) as t4,  
					sum(t5) as t5,  
					sum(t6) as t6,  
					sum(t7) as t7, 
					sum(t8) as t8,  
					sum(t9) as t9, 
					sum(t10) as t10, 
					sum(t11) as t11, 
					sum(t12) as t12,
					kode
			from
			(				
				select 	nocab, kode_comp, kode,
						sum(if(blndok = 1 and grup = 'G0506', unit, 0)) as b1,
						count(DISTINCT(if(blndok = 1 and grup = 'G0506', outlet,0)))-1 as t1,
						sum(if(blndok = 2 and grup = 'G0506', unit, 0)) as b2,
						count(DISTINCT(if(blndok = 2 and grup = 'G0506', outlet,0)))-1 as t2,
						sum(if(blndok = 3 and grup = 'G0506', unit, 0)) as b3,
						count(DISTINCT(if(blndok = 3 and grup = 'G0506', outlet,0)))-1 as t3,
						sum(if(blndok = 4 and grup = 'G0506', unit, 0)) as b4,
						count(DISTINCT(if(blndok = 4 and grup = 'G0506', outlet,0)))-1 as t4,
						sum(if(blndok = 5 and grup = 'G0506', unit, 0)) as b5,
						count(DISTINCT(if(blndok = 5 and grup = 'G0506', outlet,0)))-1 as t5,
						sum(if(blndok = 6 and grup = 'G0506', unit, 0)) as b6,
						count(DISTINCT(if(blndok = 6 and grup = 'G0506', outlet,0)))-1 as t6,
						sum(if(blndok = 7 and grup = 'G0506', unit, 0)) as b7,
						count(DISTINCT(if(blndok = 7 and grup = 'G0506', outlet,0)))-1 as t7,
						sum(if(blndok = 8 and grup = 'G0506', unit, 0)) as b8,
						count(DISTINCT(if(blndok = 8 and grup = 'G0506', outlet,0)))-1 as t8,
						sum(if(blndok = 9 and grup = 'G0506', unit, 0)) as b9,
						count(DISTINCT(if(blndok = 9 and grup = 'G0506', outlet,0)))-1 as t9,
						sum(if(blndok = 10 and grup = 'G0506', unit, 0)) as b10,
						count(DISTINCT(if(blndok = 10 and grup = 'G0506', outlet,0)))-1 as t10,
						sum(if(blndok = 11 and grup = 'G0506', unit, 0)) as b11,
						count(DISTINCT(if(blndok = 11 and grup = 'G0506', outlet,0)))-1 as t11,
						sum(if(blndok = 12 and grup = 'G0506', unit, 0)) as b12,
						count(DISTINCT(if(blndok = 12 and grup = 'G0506', outlet,0)))-1 as t12
				FROM
				(
					select 	nocab, kode_comp, kode, unit, a.kodeprod, grup, outlet, blndok 
					FROM
					(
							select 	nocab,kode_comp,blndok,
									tot1 as unit, kodeprod, 
									concat(kode_comp,nocab) as kode, concat(kode_comp,kode_lang) as outlet
							from 	data".$year.".fi
							where 	nodokjdi <> 'XXXXXX' and kodeprod like '06%' 
							union ALL
							select 	nocab,kode_comp,blndok,
									tot1 as unit, kodeprod, 
									concat(kode_comp,nocab) as kode, 0
							from 	data".$year.".ri
							where 	nodokjdi <> 'XXXXXX' and kodeprod like '06%' 
					)a LEFT JOIN 
					(
						select kodeprod, grup 
						from mpm.tabprod
						where supp ='005'
					)b on a.kodeprod = b.kodeprod
				)a GROUP BY kode					 
			)a GROUP BY kode
		)a GROUP BY kode
	)a LEFT JOIN
	(
		select concat(kode_comp, nocab) as kode,naper,kode_comp, nama_comp, sub,urutan,status,status_cluster
		from mpm.tbl_tabcomp_new
		where status = 1
		GROUP BY concat(kode_comp, nocab)
	)b on a.kode = b.kode
	LEFT JOIN
	(
			select 	max(lastupload) as maxupload,
					substring(filename,3,2) naper  
			from 	mpm.upload 
			group by naper
	)c on a.nocab = c.naper
	";      
			
	        $gabung = "insert into mpm.tbl_temp_omzet_otherus ".$query;			        
	        $sql = $this->db->query($gabung);
	        
	        //echo "<pre>";
			//print_r($query);			
			//echo "</pre>";
			
			/* ============================================================== */
			if ($sql == '1') {
				
				$query="
				select 	nocab, kode_comp, a.sub, b.nama, 
						sum(t1),sum(b1),sum(t2),sum(b2),sum(t3),sum(b3),sum(t4),sum(b4),sum(t5),sum(b5),
						sum(t6),sum(b6),sum(t7),sum(b7),sum(t8),sum(b8),sum(t9),sum(b9),sum(t10),sum(b10),sum(t11),sum(b11),
						sum(t12),sum(b12),id,sum(total),sum(rata), ".'"'.$supp.'"'.",tgl_created,tahun,b.urutan,b.status,'','$key_jam'
				
				from 	mpm.tbl_temp_omzet_otherus a inner JOIN
				(
						select 	naper naper2,status, urutan, nama_comp nama, sub
						from 		mpm.tbl_tabcomp_new
						WHERE 	status = '2' and status_cluster <> '1'
				)b on a.sub = b.sub
				where a.kategori_supplier ='$supp' and a.tahun = $year and a.`key` = '$key_jam'
				GROUP BY sub
				ORDER BY a.urutan asc

				";
				$gabung = "insert into mpm.tbl_temp_omzet_otherus ".$query;			        
		        $sql = $this->db->query($gabung);

		        //echo "<pre>";
		        //print_r($query);
		        //echo "</pre>";


				if ($sql == '1') {
		        	
					$query = "
					select 	'', '', '', 'GRAND(TOTAL)',
							t1,b1,t2,b2,t3,b3,t4,b4,t5,b5,t6,b6,
							t7,b7,t8,b8,t9,b9,t10,b10,t11,b11,t12,b12,id,																
							total, ($totalx)/$rata as rata, 
						 	".'"'.$supp.'"'.",tgl_created,tahun,'999','3','','$key_jam'
					from
					(
						select 	sum(b1) as b1,sum(b2) as b2,sum(b3) as b3,sum(b4) as b4,
								sum(b5) as b5,sum(b6) as b6,sum(b7) as b7,sum(b8) as b8,
								sum(b9) as b9,sum(b10) as b10,sum(b11) as b11,
								sum(b12) as b12,
								sum(t1) as t1,sum(t2) as t2,sum(t3) as t3,sum(t4) as t4,
								sum(t5) as t5,sum(t6) as t6,sum(t7) as t7,sum(t8) as t8,
								sum(t9) as t9,sum(t10) as t10,sum(t11) as t11,
								sum(t12) as t12,sum(total) as total, 
						 		".'"'.$supp.'"'.",tgl_created,tahun,'999','3','','$key_jam', id
						from 	mpm.tbl_temp_omzet_otherus a 
						where 	status = 1 and kategori_supplier ='$supp' and tahun = $year and a.key = '$key_jam'
					)a
					";

					$gabung = "insert into mpm.tbl_temp_omzet_otherus ".$query;			        
			        $sql = $this->db->query($gabung);
			        
			        //echo "<pre>";
			        //print_r($gabung);
			        //echo "</pre>";
					
					if ($sql == '1') {
			        	
			        	$query="
							insert into mpm.tbl_temporary_user (userid,menuid,nilai,datetime,supp,tahun) 
	            			values ($id,$menuid,$total,$tgl_created,'$supp$group','$year')
						";
							        
				        $sql = $this->db->query($query);

			        }else{
			        	echo "ada kesalahan <i>Proses insert temp_omzet_grand_total</i>. silahkan ulangi";
			        }

		        }else{
		        	echo "ada kesalahan <i>Proses insert temp_omzet_sub_total</i>. silahkan ulangi";
		        }

			}else{

				echo "ada kesalahan <i>Proses insert temp_omzet_per_sub_branch</i>. silahkan ulangi";
			}

			$gabung = "
				insert into mpm.omzet_new_otherus
				select 	nocab, sub, kode_comp, nama_comp, 
						t1,b1,t2,b2,t3,b3,t4,b4,t5,b5,t6,b6,t7,b7,t8,b8,t9,b9,t10,b10,t11,b11,t12,b12,id,total,rata,kategori_supplier,tgl_created,
						tahun, urutan, lastupload, `key`
				from 	mpm.tbl_temp_omzet_otherus
				where 	kategori_supplier = ".'"'.$supp.'"'." and tahun = $year and `key` = '$key_jam'
				";			        
	        $sql = $this->db->query($gabung);

	        //echo "<pre>";
	        //print_r($gabung);
	        //echo "</pre>";

			if ($sql == '1') {
	        	
	        	if ($dp == NULL || $dp == '') {
		        	
				$query="
							select 	* 
							from 	mpm.omzet_new_otherus
							where 	tahun = $year and
									kategori_supplier = '$supp' and
									`key` = (
											select 	max(`key`)
											from 	mpm.omzet_new_otherus
											where 	tahun = $year and
													kategori_supplier = '$supp'
							)
							ORDER BY urutan asc

						";
					
					//echo "<pre>";
					//print_r($query);
					//echo "</pre>";
					
							        
			        $hasil = $this->db->query($query);
			        if ($hasil->num_rows() > 0) 
					{
						return $hasil->result();
					} else {
						return array();
					}

					/* END PROSES TAMPIL KE WEBSITE */

	        	} else {
		     
					$query="
							select 	*
							from 		mpm.omzet_new_otherus
							where 		tahun = $year and
										kategori_supplier = '$supp' and
										naper in ($daftar_dp) and
										`key` = (
											select 	max(`key`)
											from 	mpm.omzet_new_otherus
											where 	tahun = $year and
													kategori_supplier = '$supp'
							)
							ORDER BY urutan asc
						";
							        
			        $hasil = $this->db->query($query);
			        if ($hasil->num_rows() > 0) 
					{
						return $hasil->result();
					} else {
						return array();
					}
					/* END PROSES TAMPIL KE WEBSITE */
	        	}

	        }else{
	        	echo "ada kesalahan <i>Proses insert omzet_new</i>. silahkan ulangi";
	        }
									
        }
	    
	}



}