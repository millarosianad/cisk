<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mkam extends CI_Model 
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

	public function omzet_all_dp($dataSegment)
	{
		if ( function_exists( 'date_default_timezone_set' ) )
		date_default_timezone_set('Asia/Jakarta');        
		$tgl_created='"'.date('Y-m-d H:i:s').'"';
		$jam=date('ymdHis');
		$key_jam = $jam;
		$id=$this->session->userdata('id');	
		$bln = $dataSegment['bln'];
		$year = $dataSegment['tahun'];
		$supp = $dataSegment['supp'];
		$uri1 = $dataSegment['menu_uri1'];
		$uri2 = $dataSegment['menu_uri2'];
		$uri3 = $dataSegment['menu_uri3'];
		$menuid = $dataSegment['menuid'];
		
		$dp = $dataSegment['query_dp'];
		if ($dp == NULL || $dp == '') {
			$daftar_dp = '';
		} else {
			$daftar_dp = $dp;
		}

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
					
		/* ===== Proses pendefinisian bulan, pembagi untuk menentukan total dan rata2 === */					
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
			$pembagi = 'xb1+xb2+xb3+xb4+xb5+xb6+xb7+xb8+xb9+xb10+xb11+xb12';
			$totalx = 'b1+b2+b3+b4+b5+b6+b7+b8+b9+b10+b11+b12';
			$rata = 12;
		}
						
		/* == PROSES DELETE MPM.OMZET_NEW dan tbl_temp_omzet ===== */
		$query = "	
				delete from mpm.omzet_new 
				where kategori_supplier = ".'"'.$supp.'"'." and tahun = $year and id = $id
					";
		$sql = $this->db->query($query);
		
		$query = "	
				delete from mpm.tbl_temp_omzet 
				where kategori_supplier = ".'"'.$supp.'"'." and tahun = $year and id = $id
					";
		$sql = $this->db->query($query);
	
		/* ========== PROSES INSERT KE mpm.tbl_temp_omzet =================== */
		$query = "	   
			insert into mpm.tbl_temp_omzet
			select  a.nocab, a.kode_comp, b.sub, b.nama_comp,t1,b1,t2,b2,t3,b3,t4,b4,t5,b5,t6,b6,t7,b7,t8,b8,t9,b9,t10,b10,t11,b11,t12,b12,".$id.", total,
					totalx/jmlbulan rata,".'"'.$supp.'"'.",".$tgl_created.",".$year.", urutan, `status`, c.maxupload,status_closing,sts, '$key_jam'
			FROM
			(
				select 	nocab,kode_comp,kode,b1,b2,b3,b4,b5,b6,b7,b8,b9,b10,b11,b12,".$id.",total,totalx,format(sum($pembagi),0, 1) jmlbulan, 
						t1,t2,t3,t4,t5,t6,t7,t8,t9,t10,t11,t12
				FROM
				(
					select  nocab,kode_comp, b1,b2,b3,b4,b5,b6,b7,b8,b9,b10,b11,b12,".$id.",b1+b2+b3+b4+b5+b6+b7+b8+b9+b10+b11+b12 as total,
							$totalx as totalx,IF(b1 = 0, 0, 1) xb1,IF(b2 = 0, 0, 1) xb2,IF(b3 = 0, 0, 1) xb3,IF(b4 = 0, 0, 1) xb4,IF(b5 = 0, 0, 1) xb5,IF(b6 = 0, 0, 1) xb6,IF(b7 = 0, 0, 1) xb7,IF(b8 = 0, 0, 1) xb8,IF(b9 = 0, 0, 1) xb9,IF(b10 = 0, 0, 1) xb10, IF(b11 = 0, 0, 1) xb11, IF(b12 = 0, 0, 1) xb12,sum(t1) as t1,sum(t2) as t2,sum(t3) as t3,sum(t4) as t4,sum(t5) as t5, sum(t6) as t6,sum(t7) as t7,sum(t8) as t8,
							sum(t9) as t9, sum(t10) as t10, sum(t11) as t11, sum(t12) as t12,kode
					from
					(
						select	`nocab`,kode_comp,
								sum(if(`blndok` = 1, `unit`, 0)) as `b1`,sum(if(`blndok` = 2, `unit`, 0)) as `b2`,sum(if(`blndok` = 3, `unit`, 0)) as `b3`,
								sum(if(`blndok` = 4, `unit`, 0)) as `b4`,sum(if(`blndok` = 5, `unit`, 0)) as `b5`,sum(if(`blndok` = 6, `unit`, 0)) as `b6`,
								sum(if(`blndok` = 7, `unit`, 0)) as `b7`,sum(if(`blndok` = 8, `unit`, 0)) as `b8`,sum(if(`blndok` = 9, `unit`, 0)) as `b9`,
								sum(if(`blndok` = 10, `unit`, 0)) as `b10`,sum(if(`blndok` = 11, `unit`, 0)) as `b11`,
								sum(if(`blndok` = 12, `unit`, 0)) as `b12`,sum(if(blndok = 1, trans, 0)) as t1,
								sum(if(blndok = 2, trans, 0)) as t2,sum(if(blndok = 3, trans, 0)) as t3,sum(if(blndok = 4, trans, 0)) as t4,
								sum(if(blndok = 5, trans, 0)) as t5,sum(if(blndok = 6, trans, 0)) as t6,sum(if(blndok = 7, trans, 0)) as t7,
								sum(if(blndok = 8, trans, 0)) as t8,sum(if(blndok = 9, trans, 0)) as t9,sum(if(blndok = 10, trans, 0)) as t10,
								sum(if(blndok = 11, trans, 0)) as t11,sum(if(blndok = 12, trans, 0)) as t12,kode
						from
						(
							select 	`nocab`,kode_comp, `blndok`, sum(`tot1`) as `unit`, count(distinct(concat(kode_comp,kode_lang))) as trans, 
									concat(kode_comp, nocab) as kode
							from 	`data".$year."`.`fi`
							where 	`nodokjdi` <> 'XXXXXX' ".$wheresupp." and
									kode_type in ('AP','APC','APT','PB','PBF','MM','MML','MMN','SM','SML','SMN','SMR')								
							group by kode,`blndok`		
							union all
							select 	`nocab`,kode_comp, `blndok`, sum(`tot1`) as `unit`,null as trans, concat(kode_comp, nocab) as kode
							from 	`data".$year."`.`ri`
							where 	`nodokjdi` <> 'XXXXXX' ".$wheresupp." and
									kode_type in ('AP','APC','APT','PB','PBF','MM','MML','MMN','SM','SML','SMN','SMR')								
							group by kode,`blndok`
						) `a`
						group by kode
					)a GROUP BY kode
				)a GROUP BY kode
			)a LEFT JOIN
			(
				select 	concat(kode_comp, nocab) as kode,naper,kode_comp, nama_comp, sub,urutan,`status`,status_cluster
				from 	mpm.tbl_tabcomp_new
				where 	`status` = 1
				GROUP BY concat(kode_comp, nocab)
			)b on a.kode = b.kode
			LEFT JOIN
			(
				select 	max(lastupload) as maxupload,substring(filename,3,2) naper  
				from 	mpm.upload 
				group by naper
			)c on a.nocab = c.naper
			LEFT JOIN
			(
				select 	id ,userid, filename, tahun, bulan, status_closing,`status` as sts,substring(filename,3,2) naper
				from 	mpm.upload
				where 	bulan = $bln and tahun = $year and id in(
					select max(id)
					from mpm.upload
					where tahun = $year and bulan = $bln
					GROUP BY substring(filename,3,2)
				)and userid not in ('0','289') 
			)d on a.nocab = d.naper
			";      	      
			$sql = $this->db->query($query);			
				
			if ($sql) 
			{					
				$query="
					insert into mpm.tbl_temp_omzet
					select 	nocab, kode_comp, a.sub, b.nama, 
							sum(t1), sum(b1), sum(t2), sum(b2), sum(t3), sum(b3), sum(t4), sum(b4), sum(t5), sum(b5), sum(t6), sum(b6), 
							sum(t7), sum(b7), sum(t8), sum(b8), sum(t9), sum(b9), sum(t10), sum(b10), sum(t11), sum(b11), sum(t12), sum(b12),
							id,sum(total),sum(($totalx)/$rata), ".'"'.$supp.'"'.",tgl_created,tahun,b.urutan,b.`status`,'',null,null,'$key_jam'
					
					from 	mpm.tbl_temp_omzet a inner JOIN
					(
						select 	naper naper2,`status`, urutan, nama_comp nama, sub
						from 	mpm.tbl_tabcomp_new
						WHERE 	`status` = '2' and status_cluster <> '1'
					)b on a.sub = b.sub
					where a.kategori_supplier ='$supp' and a.tahun = $year and a.`key` = '$key_jam'
					GROUP BY sub
					ORDER BY a.urutan asc	
				";		        
				$sql = $this->db->query($query);
					
				if ($sql) 
				{	
					$query="
						insert into mpm.tbl_temp_omzet
						select  '', '', '', 'GRAND(TOTAL)', sum(t1), sum(b1), sum(t2), sum(b2), sum(t3), sum(b3), sum(t4), sum(b4), sum(t5), sum(b5), 
								sum(t6),sum(b6), sum(t7), sum(b7), sum(t8), sum(b8), sum(t9), sum(b9), sum(t10), sum(b10), sum(t11), sum(b11), 	sum(t12), sum(b12), id,sum(total),sum(($totalx)/$rata), ".'"'.$supp.'"'.", tgl_created,tahun,'999','3','',null,null,'$key_jam'
						from 	mpm.tbl_temp_omzet a
						where 	`status` = 1 and kategori_supplier ='$supp' and tahun = $year and a.key = '$key_jam'
					";
					$sql = $this->db->query($query);
				}else
				{	
					echo "ada kesalahan <i>Proses insert temp_omzet_grand_total</i>. silahkan ulangi";	
				}	
			}else
			{
				echo "ada kesalahan <i>Proses insert temp_omzet_sub_total</i>. silahkan ulangi";
			}
	
			$gabung = "
				insert into mpm.omzet_new
				select 	nocab, sub, kode_comp, nama_comp, t1,b1,t2,b2,t3,b3,t4,b4,t5,b5,t6,b6,t7,b7,t8,b8,t9,b9,t10,b10,t11,b11,t12,b12,
						id,total,rata,kategori_supplier,tgl_created,tahun,urutan,lastupload,status_closing,status_submit,'$key_jam'
				from mpm.tbl_temp_omzet
				where kategori_supplier = ".'"'.$supp.'"'." and tahun = $year and `key` = '$key_jam'
				";			        
			$sql = $this->db->query($gabung);
				
			if ($dp == NULL || $dp == '') 
			{
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
			}else
			{		
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
			}
	}

	public function omzet_all_dp_group_herbal($dataSegment)
	{
		if ( function_exists( 'date_default_timezone_set' ) )
		date_default_timezone_set('Asia/Jakarta');        
		$tgl_created='"'.date('Y-m-d H:i:s').'"';
		$jam=date('ymdHis');
		$key_jam = $jam;
		$id=$this->session->userdata('id');	
		$bln = $dataSegment['bln'];
		$year = $dataSegment['tahun'];
		$supp = $dataSegment['supp'];
		$uri1 = $dataSegment['menu_uri1'];
		$uri2 = $dataSegment['menu_uri2'];
		$uri3 = $dataSegment['menu_uri3'];
		$menuid = $dataSegment['menuid'];
		
		$dp = $dataSegment['query_dp'];
		if ($dp == NULL || $dp == '') {
			$daftar_dp = '';
		} else {
			$daftar_dp = $dp;
		}

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

		/* ===== Proses pendefinisian bulan, pembagi untuk menentukan total dan rata2 === */					
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
			$pembagi = 'xb1+xb2+xb3+xb4+xb5+xb6+xb7+xb8+xb9+xb10+xb11+xb12';
			$totalx = 'b1+b2+b3+b4+b5+b6+b7+b8+b9+b10+b11+b12';
			$rata = 12;
		}
				
		/* == PROSES DELETE MPM.OMZET_NEW ===== */
		$query = "	
				delete from mpm.omzet_new_herbal
				where kategori_supplier = ".'"'.$supp.'"'." and tahun = $year and id = $id
					";
		$sql = $this->db->query($query);

		/* == PROSES DELETE mpm.tbl_temp_omzet = */
		$query = "	
				delete from mpm.tbl_temp_omzet_herbal
				where kategori_supplier = ".'"'.$supp.'"'." and tahun = $year and id = $id
					";
		$sql = $this->db->query($query);
				
		$query = "	   
		select  a.nocab, a.kode_comp, b.sub, b.nama_comp,t1,b1,t2,b2,t3,b3,t4,b4,t5,b5,t6,b6,t7,b7,t8,b8,t9,b9,t10,b10,t11,b11,t12,b12,
				".$id.",total,totalx/jmlbulan rata,	".'"'.$supp.'"'.",".$tgl_created.",".$year.", urutan, status, c.maxupload,status_closing,sts, '$key_jam'
		FROM
		(
			select 	nocab,kode_comp,kode,b1, b2, b3, b4, b5, b6, b7, b8, b9, b10, b11, b12,	t1, t2, t3, t4,	t5, t6, t7, t8,	t9, t10, t11, t12, ".$id.",
					total, totalx, format(sum($pembagi),0, 1) jmlbulan
			FROM
			(
				select  nocab,kode_comp,b1, b2, b3, b4, b5, b6, b7, b8, b9, b10, b11, b12,".$id.",b1+b2+b3+b4+b5+b6+b7+b8+b9+b10+b11+b12 as total,
						$totalx as totalx,IF(b1 = 0, 0, 1) xb1,IF(b2 = 0, 0, 1) xb2,IF(b3 = 0, 0, 1) xb3,IF(b4 = 0, 0, 1) xb4,IF(b5 = 0, 0, 1) xb5, 
						IF(b6 = 0, 0, 1) xb6, IF(b7 = 0, 0, 1) xb7,	IF(b8 = 0, 0, 1) xb8,IF(b9 = 0, 0, 1) xb9,IF(b10 = 0, 0, 1) xb10,
						IF(b11 = 0, 0, 1) xb11,IF(b12 = 0, 0, 1) xb12,sum(t1) as t1,sum(t2) as t2,sum(t3) as t3,sum(t4) as t4,sum(t5) as t5,  
						sum(t6) as t6,sum(t7) as t7,sum(t8) as t8,sum(t9) as t9,sum(t10) as t10,sum(t11) as t11,sum(t12) as t12,kode
				from
				(				
					select 	nocab, kode_comp, kode,	sum(if(blndok = 1 and grup = 'G0101', unit, 0)) as b1,
							count(DISTINCT(if(blndok = 1 and grup = 'G0101', outlet,0))) as t1,
							sum(if(blndok = 2 and grup = 'G0101', unit, 0)) as b2,
							count(DISTINCT(if(blndok = 2 and grup = 'G0101', outlet,0))) as t2,
							sum(if(blndok = 3 and grup = 'G0101', unit, 0)) as b3,
							count(DISTINCT(if(blndok = 3 and grup = 'G0101', outlet,0))) as t3,
							sum(if(blndok = 4 and grup = 'G0101', unit, 0)) as b4,
							count(DISTINCT(if(blndok = 4 and grup = 'G0101', outlet,0))) as t4,
							sum(if(blndok = 5 and grup = 'G0101', unit, 0)) as b5,
							count(DISTINCT(if(blndok = 5 and grup = 'G0101', outlet,0))) as t5,
							sum(if(blndok = 6 and grup = 'G0101', unit, 0)) as b6,
							count(DISTINCT(if(blndok = 6 and grup = 'G0101', outlet,0))) as t6,
							sum(if(blndok = 7 and grup = 'G0101', unit, 0)) as b7,
							count(DISTINCT(if(blndok = 7 and grup = 'G0101', outlet,0))) as t7,
							sum(if(blndok = 8 and grup = 'G0101', unit, 0)) as b8,
							count(DISTINCT(if(blndok = 8 and grup = 'G0101', outlet,0))) as t8,
							sum(if(blndok = 9 and grup = 'G0101', unit, 0)) as b9,
							count(DISTINCT(if(blndok = 9 and grup = 'G0101', outlet,0))) as t9,
							sum(if(blndok = 10 and grup = 'G0101', unit, 0)) as b10,
							count(DISTINCT(if(blndok = 10 and grup = 'G0101', outlet,0))) as t10,
							sum(if(blndok = 11 and grup = 'G0101', unit, 0)) as b11,
							count(DISTINCT(if(blndok = 11 and grup = 'G0101', outlet,0))) as t11,
							sum(if(blndok = 12 and grup = 'G0101', unit, 0)) as b12,
							count(DISTINCT(if(blndok = 12 and grup = 'G0101', outlet,0))) as t12
					FROM
					(
						select 	nocab, kode_comp, kode, unit, a.kodeprod, grup, outlet, blndok 
						FROM
						(
								select 	nocab,kode_comp,blndok,	tot1 as unit, kodeprod, concat(kode_comp,nocab) as kode, concat(kode_comp,kode_lang) as outlet
								from 	data".$year.".fi
								where 	nodokjdi <> 'XXXXXX'
										and (kodeprod like '60%' or kodeprod like '01%' or kodeprod like '50%' or kodeprod like '70%') and kodeprod not in (select kodeprod from mpm.tabprod_unilever) and
										kode_type in ('AP','APC','APT','PB','PBF','MM','MML','MMN','SM','SML','SMN','SMR')
	
								union ALL
								select 	nocab,kode_comp,blndok,tot1 as unit, kodeprod, concat(kode_comp,nocab) as kode, null
								from 	data".$year.".ri
								where 	nodokjdi <> 'XXXXXX' 
										and (kodeprod like '60%' or kodeprod like '01%' or kodeprod like '50%' or kodeprod like '70%') and kodeprod not in (select kodeprod from mpm.tabprod_unilever) and
										kode_type in ('AP','APC','APT','PB','PBF','MM','MML','MMN','SM','SML','SMN','SMR')
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
		LEFT JOIN
		(
			select 	id ,userid, filename, tahun, bulan, status_closing,`status` as sts,substring(filename,3,2) naper
			from 	mpm.upload
			where 	bulan = $bln and tahun = $year and id in (
				select max(id)
				from mpm.upload
				where tahun = $year and bulan = $bln
				GROUP BY substring(filename,3,2)
			)and userid not in ('0','289')
	
		)d on a.nocab = d.naper
		";      
				
				$gabung = "insert into mpm.tbl_temp_omzet_herbal ".$query;			        
				$sql = $this->db->query($gabung);
				
				// //echo "<pre>";
				// echo "<br><br><br>";
				// //print_r($query);			
				// //echo "</pre>";
				
				/* ============================================================== */
				if ($sql == '1') {
					
					$query="
					select 	nocab, kode_comp, a.sub, b.nama, 
							sum(t1),sum(b1),sum(t2),sum(b2),sum(t3),sum(b3),sum(t4),sum(b4),sum(t5),sum(b5),
							sum(t6),sum(b6),sum(t7),sum(b7),sum(t8),sum(b8),sum(t9),sum(b9),sum(t10),sum(b10),sum(t11),sum(b11),
							sum(t12),sum(b12),id,sum(total),sum(($totalx)/$rata), ".'"'.$supp.'"'.",tgl_created,tahun,b.urutan,b.status,'',null,null,'$key_jam'
					
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
	
					// //echo "<pre>";
					// //print_r($query);
					// //echo "</pre>";
	
	
					if ($sql == '1') {
						
						$query = "
						select 	'', '', '', 'GRAND(TOTAL)',
								t1,b1,t2,b2,t3,b3,t4,b4,t5,b5,t6,b6,
								t7,b7,t8,b8,t9,b9,t10,b10,t11,b11,t12,b12,id,																
								total, ($totalx)/$rata as rata, 
								 ".'"'.$supp.'"'.",tgl_created,tahun,'999','3','',null,null,'$key_jam'
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
									 ".'"'.$supp.'"'.",tgl_created,tahun,'999','3','','A','B','$key_jam', id
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
							tahun, urutan, lastupload,status_closing,status_submit,'$key_jam'
					from 	mpm.tbl_temp_omzet_herbal
					where 	kategori_supplier = ".'"'.$supp.'"'." and tahun = $year and `key` = '$key_jam'
					";			        
				$sql = $this->db->query($gabung);
	
				// //echo "<pre>";
				// //print_r($gabung);
				// //echo "</pre>";
	
	
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
						//echo "<pre>";
						//print_r($query);
						//echo "</pre>";
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

}