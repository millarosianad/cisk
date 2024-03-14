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
		//echo "tahun di model : ".$year;
		$supp = $dataSegment['supp'];
		//echo "supplier di model : ".$supp;

			if($supp=='000')
            {
                $wheresupp='
                    and (kodeprod like "60%" or kodeprod like "01%" or 
                        kodeprod like "50%" or kodeprod like "70%" or 
                        kodeprod like "06%" or kodeprod like "02%" or
                        kodeprod like "04%") and (nocab != "r1")
                ';
            }
            else if($supp=='001')
            {                
                if ($id == '318') { //jika user dimas, lihat all jkt & karawang
						$wheresupp='and (kodeprod like "60%" or kodeprod like "01%" or kodeprod like "50%" or kodeprod like "70%") and (nocab != "r1") and
									and (nocab = 88 or nocab = 07)';
					}

					elseif ($id == '319') { //user sutrisno, all jbr
						$wheresupp='and (kodeprod like "60%" or kodeprod like "01%" or kodeprod like "50%" or kodeprod like "70%") and (nocab != "r1") and
									and nocab = 95';
					}

					elseif ($id == '320') { //user kristiyanto, all jtg
						$wheresupp='and (kodeprod like "60%" or kodeprod like "01%" or kodeprod like "50%" or kodeprod like "70%") and (nocab != "r1") and
									and (nocab = 89 or nocab = 19 or nocab = 32 or nocab = "p3" or nocab = "j1")';
					}

					elseif ($id == '321') { //user hengki, kudus, blora, pati, jepara
						$wheresupp='and (kodeprod like "60%" or kodeprod like "01%" or kodeprod like "50%" or kodeprod like "70%") and (nocab != "r1") and
									and nocab = 19';
					}

					elseif ($id == '322') { //user dawam, diy, solo
						$wheresupp='and (kodeprod like "60%" or kodeprod like "01%" or kodeprod like "50%" or kodeprod like "70%") and (nocab != "r1") and
									and (nocab = 98 or nocab = 18 or nocab = 96)';
					}

					elseif ($id == '323') { //user miko, all jtm dan seluruh dp jawa timur (mlg, sdo, kdr, tga, mdu, blt)
						$wheresupp='and (kodeprod like "60%" or kodeprod like "01%" or kodeprod like "50%" or kodeprod like "70%") and (nocab != "r1") and
									and (nocab = 91 or nocab = 27 or nocab = 46 or nocab = 74 or nocab = 24 or nocab = 26 or nocab = 23 or nocab = 69 or nocab = "c1" or nocab = "p4"
										or nocab = "p5" or nocab = 25 or nocab = 29 or nocab = 50 or nocab = 83 or nocab = "G1" or nocab = "P1" or nocab = "T1" or nocab = "S1" or nocab = "L1" or nocab = "B2" or nocab = "B1") ';
					}

					elseif ($id == '336') { //user ghazali, all pulau jawa)
						$wheresupp='and (kodeprod like "60%" or kodeprod like "01%" or kodeprod like "50%" or kodeprod like "70%") and (nocab != "r1") and
									and (nocab in (01,02,03,04,06,07,08,09,10,11,12,13,14,15,16,17,18,19,23,24,25,26,27,28,29,39,40,41,42,46,47,48,50,52,53,54,56,57,58,60,61,62,63,64,66,67,68,69,74,91,73,79,75,76,81,78,83,84,88,89,95,96,"G1","P2","P1","T1","G2","P3","J1","S1","L1","K1","B2","B3",99,"C1","P4","P5",98)) ';
					}


					else{
						$wheresupp='and (kodeprod like "60%" or kodeprod like "01%" or kodeprod like "50%" or kodeprod like "70%") and nocab != "r1"';
					}
            }
            else if($supp=='005')
            {
                $wheresupp='and kodeprod like "06%" ';
            }
            else if($supp=='002')
            {
                $wheresupp='and kodeprod like "02%" ';
            }
            else if($supp=='009') //UNILEVER
            {
                //$wheresupp='and kodeprod like "20%" or kodeprod like "21%" or kodeprod like "62%" or kodeprod like "67%"  ';

                $wheresupp='and supp = 009';
            }
            else if($supp=='XXX')
            {                    
                $wheresupp='';
            }
            else
            {
                $wheresupp="and kodeprod like '".substr($supp,-2)."%'";
            }	

		/* ---------END DEFINISI VARIABEL---------------- */

		/* PROSES DELETE MPM.OMZET_NEW */
			$query = "delete from mpm.omzet_new where id = ".$id."";
			$sql = $this->db->query($query);
			/*
			echo "<pre>";
			print_r($query);
			echo "</pre>";
			*/
		/* END PROSES DELETE MPM.OMZET_NEW */

		/* cari bulan saat ini */
			$bulan = date("m");
			$tahunsekarang = date("Y");
			/*
			echo "bulan : ".$bulan."<br>";
			echo "tahun : ".$tahunsekarang."<br>";
			echo "year : ".$year;
			*/
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

		/* PROSES INSERT KE TABEL OMZET_NEW */
			
	        $query = "	        		
	        		select 	a.naper, sub, nama_comp, t1, b1, t2, b2, t3, b3, t4, b4, t5, b5, t6, b6, t7, b7, t8, b8, t9, b9, t10, b10, t11, b11, t12, b12, ".$id.", total, rata,".'"'.$supp.'"'.",".$tgl_created.",".$year.", urutan, maxupload
					FROM
					(
						select *
						FROM
						(
								select *
								from
								(
									SELECT  naper, sub, b.NAMA_COMP,
											t1,b1,
											t2,b2,
											t3,b3,
											t4,b4,
											t5,b5,
											t6,b6,
											t7,b7,
											t8,b8,
											t9,b9,
											t10,b10, 
											t11,b11,
											t12,b12, 
											".$id.", 
											format(total,0) total, 
											format(totalx/jmlbulan,0) rata,".'"'.$supp.'"'.",".$tgl_created.",".$year."
									FROM
									(
											select  nocab, b1, b2, b3, b4, b5, b6, b7, b8, b9, b10, b11, b12, ".$id.", 
													total, totalx,
													format(sum($pembagi),0, 1) jmlbulan,t1,t2,t3,t4,t5,t6,t7,t8,t9,t10,t11,t12
											FROM
											(
												select  nocab,                              
														b1, b2, b3, b4, b5, b6, b7, b8, b9, b10, b11, b12,
														".$id.",
														b1+b2+b3+b4+b5+b6+b7+b8+b9+b10+b11+b12 as total,
														$totalx as totalx,                                
														IF(b1 = 0, 0, 1) xb1,IF(b2 = 0, 0, 1) xb2,IF(b3 = 0, 0, 1) xb3,
														IF(b4 = 0, 0, 1) xb4,IF(b5 = 0, 0, 1) xb5,IF(b6 = 0, 0, 1) xb6,
														IF(b7 = 0, 0, 1) xb7,IF(b8 = 0, 0, 1) xb8,IF(b9 = 0, 0, 1) xb9,
														IF(b10 = 0, 0, 1) xb10, IF(b11 = 0, 0, 1) xb11, IF(b12 = 0, 0, 1) xb12,
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
														sum(t12) as t12
												from
												(
														select
																`nocab`,
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
															 select `nocab`, `blndok`, sum(`tot1`) as `unit`, count(distinct(concat(kode_comp,kode_lang))) as trans
															 from `data".$year."`.`fi`
															 where `nodokjdi` <> 'XXXXXX' 
																	".$wheresupp."									
															 group by `nocab`,`blndok`

															 union all

															 select `nocab`, `blndok`, sum(`tot1`) as `unit`,0 as trans
															 from `data".$year."`.`ri`
															 where `nodokjdi` <> 'XXXXXX' 
																	".$wheresupp."									
															 group by `nocab`,`blndok`
														) `a`
															group by `nocab`
												)`a` GROUP BY nocab
											)a GROUP BY nocab
									)a

									inner join `mpm`.`tabcomp` `b` USING (`nocab`)
									group by `naper`
									order by `naper`
								)a LEFT JOIN 
									(
										select 	naper naper2,`status`, urutan
										from 		mpm.tbl_tabcomp_new
										WHERE 	`status` = '1' and status_cluster <> '1'
									)c on a.naper = c.naper2			
									ORDER BY c.urutan asc

						)a

						union ALL

						select *
						FROM
						(
						select	NAPER, sub, nama, t1,b1,
											t2,b2,
											t3,b3,
											t4,b4,
											t5,b5,
											t6,b6,
											t7,b7,
											t8,b8,
											t9,b9,
											t10,b10, 
											t11,b11,
											t12,b12,
											".$id.", 
											format(total,0) as total,
											format(rata,0) as rata,3,4,5,6,7,c.urutan
						from
						(
								select	NAPER, sub, NAMA_COMP, SUM(b1) b1,
												SUM(b2) b2,
												SUM(b3) b3,
												SUM(b4) b4,
												SUM(b5) b5,
												SUM(b6) b6,
												SUM(b7) b7,
												SUM(b8) b8,
												SUM(b9) b9,
												SUM(b10) b10,
												SUM(b11) b11,
												SUM(b12) b12,
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
												sum(total) as total,
												sum(rata) as rata
						from
								(
											SELECT  naper, sub, b.NAMA_COMP,
													t1,b1,
													t2,b2,
													t3,b3,
													t4,b4,
													t5,b5,
													t6,b6,
													t7,b7,
													t8,b8,
													t9,b9,
													t10,b10, 
													t11,b11,
													t12,b12, 
													".$id.", 
													total, 
													(totalx/jmlbulan) as rata,".'"'.$supp.'"'.",".$tgl_created.",".$year."
											FROM
											(
													select  nocab, b1, b2, b3, b4, b5, b6, b7, b8, b9, b10, b11, b12, ".$id.", 
															total, totalx,
															format(sum($pembagi),0, 1) jmlbulan,t1,t2,t3,t4,t5,t6,t7,t8,t9,t10,t11,t12
													FROM
													(
														select  nocab,                              
																b1, b2, b3, b4, b5, b6, b7, b8, b9, b10, b11, b12,
																".$id.",
																b1+b2+b3+b4+b5+b6+b7+b8+b9+b10+b11+b12 as total,
																$totalx as totalx,                                
																IF(b1 = 0, 0, 1) xb1,IF(b2 = 0, 0, 1) xb2,IF(b3 = 0, 0, 1) xb3,
																IF(b4 = 0, 0, 1) xb4,IF(b5 = 0, 0, 1) xb5,IF(b6 = 0, 0, 1) xb6,
																IF(b7 = 0, 0, 1) xb7,IF(b8 = 0, 0, 1) xb8,IF(b9 = 0, 0, 1) xb9,
																IF(b10 = 0, 0, 1) xb10, IF(b11 = 0, 0, 1) xb11, IF(b12 = 0, 0, 1) xb12,t1,t2,t3,t4,t5,t6,t7,t8,t9,t10,t11,t12
														from
														(
																select
																		`nocab`,
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
																	 select `nocab`, `blndok`, sum(`tot1`) as `unit`, count(distinct(concat(kode_comp,kode_lang))) as trans
																	 from `data".$year."`.`fi`
																	 where `nodokjdi` <> 'XXXXXX' 
																			".$wheresupp."									
																	 group by `nocab`,`blndok`

																	 union all

																	 select `nocab`, `blndok`, sum(`tot1`) as `unit`,0 as trans
																	 from `data".$year."`.`ri`
																	 where `nodokjdi` <> 'XXXXXX' 
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
										from 		mpm.tbl_tabcomp_new
										WHERE 	`status` = '2' and status_cluster <> '1'
									)c on a.sub = c.naper2			
									
						GROUP BY sub
						ORDER BY c.urutan asc
						)b

						UNION ALL

						select	'Z', '', 
								'TOTAL',
								sum(if(blndok = 1, trans, 0)) as t1,
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
								".$id.",format(SUM(unit),0) as total,'-',0,0,0,0,0,999
						from
						(
							 select `nocab`, `blndok`, sum(`tot1`) as `unit`, count(distinct(concat(kode_comp,kode_lang))) as trans
							 from `data".$year."`.`fi`
							 where `nodokjdi` <> 'XXXXXX' 
									".$wheresupp."
							 group by `nocab`,`blndok`

							 union all

							 select `nocab`, `blndok`, sum(`tot1`) as `unit`,0 as trans
							 from `data".$year."`.`ri`
							 where `nodokjdi` <> 'XXXXXX' 
									".$wheresupp."
							 group by `nocab`,`blndok`
						) `a`
						ORDER BY urutan
					)a LEFT JOIN
					(
							select 	max(lastupload) as maxupload,
											substring(filename,3,2) naper  
									from 	mpm.upload 
									group by naper
					)b on a.naper = b.naper
	        ";
	        /*
	        echo "<pre>";
	        	print_r($query);
	        echo "</pre>";
			*/
	        $gabung = "insert into mpm.omzet_new ".$query;
	        
	        $sql = $this->db->query($gabung);

		/* END PROSES INSERT KE TABEL OMZET_NEW */

		/* PROSES TAMPIL KE WEBSITE */

			$this->db->order_by('urutan','asc');
			$this->db->where("id = ".'"'.$id.'"');
			$hasil = $this->db->get('omzet_new');		
			if ($hasil->num_rows() > 0) 
			{
				return $hasil->result();
			} else {
				return array();
			}
		/* END PROSES TAMPIL KE WEBSITE */
	}

	public function omzet_all_dp_barat($dataSegment){

	/* ---------DEFINISI VARIABEL----------------- */
		$id=$this->session->userdata('id');

		if ( function_exists( 'date_default_timezone_set' ) )
        date_default_timezone_set('Asia/Jakarta');        
        $tgl_created='"'.date('Y-m-d H:i:s').'"';

		$year = $dataSegment['tahun'];
		//echo "tahun di model : ".$year;
		$supp = $dataSegment['supp'];
		//echo "supplier di model : ".$supp;

			if($supp=='000')
            {
                if ($id == '191')//jika user janleo 
					{ 
						$wheresupp='and (
											kodeprod like "60%" or kodeprod like "01%" or 
											kodeprod like "50%" or kodeprod like "70%" or 
											kodeprod like "06%" or kodeprod like "02%" or
											kodeprod like "04%"
										) and (nocab != "r1") or nocab = "98" 
									';

						$tambahDP='or nocab in(98,89,91)';
					}

					else{

						$wheresupp='
									and (kodeprod like "60%" or kodeprod like "01%" or 
										kodeprod like "50%" or kodeprod like "70%" or 
										kodeprod like "06%" or kodeprod like "02%" or
										kodeprod like "04%")and (nocab != "r1")
						';

						$tambahDP='';
					}
            }
            else if($supp=='001') //DELTO
            {                
                $wheresupp='and (kodeprod like "60%" or kodeprod like "01%" or kodeprod like "50%" or kodeprod like "70%")and (nocab != "r1")';
                $tambahDP='';
            }
            else if($supp=='005') //ULTRA SAKTI
            {
                $wheresupp='and kodeprod like "06%" ';
                $tambahDP='';
            }
            else if($supp=='002') //marguna
            {
                $wheresupp='and kodeprod like "02%" ';
                $tambahDP='';
            }
            else if($supp=='009') //UNILEVER
            {
                $wheresupp='and kodeprod like "20%" or kodeprod like "21%" or kodeprod like "62%" or kodeprod like "67%"  ';
                 $tambahDP='';
            }
            else if($supp=='XXX')
            {                    
                $wheresupp='';
                $tambahDP='';
            }
            else
            {
                $wheresupp="and kodeprod like '".substr($supp,-2)."%'";
                $tambahDP='';
            }	

		/* ---------END DEFINISI VARIABEL---------------- */

		/* PROSES DELETE MPM.OMZET_NEW */
			$query = "delete from mpm.omzet_new where id = ".$id."";
			$sql = $this->db->query($query);
			/*
			echo "<pre>";
			print_r($query);
			echo "</pre>";
			*/
		/* END PROSES DELETE MPM.OMZET_NEW */

		/* cari bulan saat ini */
			$bulan = date("m");
			$tahunsekarang = date("Y");
			/*
			echo "bulan : ".$bulan."<br>";
			echo "tahun : ".$tahunsekarang."<br>";
			echo "year : ".$year;
			*/
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
				$wilayah = "wilayah = 1";
				//echo "wilayah : ".$wilayah."<br>";
			} else {
				$wilayah = "wilayah_2017 = 1";
				//echo "wilayah : ".$wilayah."<br>";
			}
		/* end pontianak masuk ke wilayah timur per 2017*/

		/* PROSES INSERT KE TABEL OMZET_NEW */			
	        $query = "	        		
	        		select 	a.naper, sub, nama_comp, t1, b1, t2, b2, t3, b3, t4, b4, t5, b5, t6, b6, t7, b7, t8, b8, t9, b9, t10, b10, t11, b11, t12, b12, ".$id.", total, rata,".'"'.$supp.'"'.",".$tgl_created.",".$year.", urutan, maxupload
					FROM
					(
						select *
						FROM
						(
								select *
								from
								(
									SELECT  naper, sub, b.NAMA_COMP,
											t1,b1,
											t2,b2,
											t3,b3,
											t4,b4,
											t5,b5,
											t6,b6,
											t7,b7,
											t8,b8,
											t9,b9,
											t10,b10, 
											t11,b11,
											t12,b12, 
											".$id.", 
											format(total,0) total, 
											format(totalx/jmlbulan,0) rata,".'"'.$supp.'"'.",".$tgl_created.",".$year."
									FROM
									(
											select  nocab, b1, b2, b3, b4, b5, b6, b7, b8, b9, b10, b11, b12, ".$id.", 
													total, totalx,
													format(sum($pembagi),0, 1) jmlbulan,t1,t2,t3,t4,t5,t6,t7,t8,t9,t10,t11,t12
											FROM
											(
												select  nocab,                              
														b1, b2, b3, b4, b5, b6, b7, b8, b9, b10, b11, b12,
														".$id.",
														b1+b2+b3+b4+b5+b6+b7+b8+b9+b10+b11+b12 as total,
														$totalx as totalx,                                
														IF(b1 = 0, 0, 1) xb1,IF(b2 = 0, 0, 1) xb2,IF(b3 = 0, 0, 1) xb3,
														IF(b4 = 0, 0, 1) xb4,IF(b5 = 0, 0, 1) xb5,IF(b6 = 0, 0, 1) xb6,
														IF(b7 = 0, 0, 1) xb7,IF(b8 = 0, 0, 1) xb8,IF(b9 = 0, 0, 1) xb9,
														IF(b10 = 0, 0, 1) xb10, IF(b11 = 0, 0, 1) xb11, IF(b12 = 0, 0, 1) xb12,
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
														sum(t12) as t12
												from
												(
														select
																`nocab`,
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
															 select `nocab`, `blndok`, sum(`tot1`) as `unit`, count(distinct(concat(kode_comp,kode_lang))) as trans
															 from `data".$year."`.`fi`
															 where `nodokjdi` <> 'XXXXXX' 
																	".$wheresupp."									
															 group by `nocab`,`blndok`

															 union all

															 select `nocab`, `blndok`, sum(`tot1`) as `unit`,0 as trans
															 from `data".$year."`.`ri`
															 where `nodokjdi` <> 'XXXXXX' 
																	".$wheresupp."									
															 group by `nocab`,`blndok`
														) `a`
															group by `nocab`
												)`a` GROUP BY nocab
											)a GROUP BY nocab
									)a

									inner join `mpm`.`tabcomp` `b` USING (`nocab`)
									where b.".$wilayah."".$tambahDP."
									group by `naper`
									order by `naper`
								)a LEFT JOIN 
									(
										select 	naper naper2,`status`, urutan
										from 		mpm.tbl_tabcomp_new
										WHERE 	`status` = '1' and status_cluster <> '1'
									)c on a.naper = c.naper2			
									ORDER BY c.urutan asc

						)a

						union ALL

						select *
						FROM
						(
						select	NAPER, sub, nama, t1,b1,
											t2,b2,
											t3,b3,
											t4,b4,
											t5,b5,
											t6,b6,
											t7,b7,
											t8,b8,
											t9,b9,
											t10,b10, 
											t11,b11,
											t12,b12,
											".$id.", 
											format(total,0) as total,
											format(rata,0) as rata,3,4,5,6,7,c.urutan
						from
						(
								select	NAPER, sub, NAMA_COMP, SUM(b1) b1,
												SUM(b2) b2,
												SUM(b3) b3,
												SUM(b4) b4,
												SUM(b5) b5,
												SUM(b6) b6,
												SUM(b7) b7,
												SUM(b8) b8,
												SUM(b9) b9,
												SUM(b10) b10,
												SUM(b11) b11,
												SUM(b12) b12,
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
												sum(total) as total,
												sum(rata) as rata
						from
								(
											SELECT  naper, sub, b.NAMA_COMP,
													t1,b1,
													t2,b2,
													t3,b3,
													t4,b4,
													t5,b5,
													t6,b6,
													t7,b7,
													t8,b8,
													t9,b9,
													t10,b10, 
													t11,b11,
													t12,b12, 
													".$id.", 
													total, 
													(totalx/jmlbulan) as rata,".'"'.$supp.'"'.",".$tgl_created.",".$year."
											FROM
											(
													select  nocab, b1, b2, b3, b4, b5, b6, b7, b8, b9, b10, b11, b12, ".$id.", 
															total, totalx,
															format(sum($pembagi),0, 1) jmlbulan,t1,t2,t3,t4,t5,t6,t7,t8,t9,t10,t11,t12
													FROM
													(
														select  nocab,                              
																b1, b2, b3, b4, b5, b6, b7, b8, b9, b10, b11, b12,
																".$id.",
																b1+b2+b3+b4+b5+b6+b7+b8+b9+b10+b11+b12 as total,
																$totalx as totalx,                                
																IF(b1 = 0, 0, 1) xb1,IF(b2 = 0, 0, 1) xb2,IF(b3 = 0, 0, 1) xb3,
																IF(b4 = 0, 0, 1) xb4,IF(b5 = 0, 0, 1) xb5,IF(b6 = 0, 0, 1) xb6,
																IF(b7 = 0, 0, 1) xb7,IF(b8 = 0, 0, 1) xb8,IF(b9 = 0, 0, 1) xb9,
																IF(b10 = 0, 0, 1) xb10, IF(b11 = 0, 0, 1) xb11, IF(b12 = 0, 0, 1) xb12,t1,t2,t3,t4,t5,t6,t7,t8,t9,t10,t11,t12
														from
														(
																select
																		`nocab`,
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
																	 select `nocab`, `blndok`, sum(`tot1`) as `unit`, count(distinct(concat(kode_comp,kode_lang))) as trans
																	 from `data".$year."`.`fi`
																	 where `nodokjdi` <> 'XXXXXX' 
																			".$wheresupp."									
																	 group by `nocab`,`blndok`

																	 union all

																	 select `nocab`, `blndok`, sum(`tot1`) as `unit`,0 as trans
																	 from `data".$year."`.`ri`
																	 where `nodokjdi` <> 'XXXXXX' 
																			".$wheresupp."									
																	 group by `nocab`,`blndok`
																) `a`
																	group by `nocab`
														)`a` GROUP BY nocab
													)a GROUP BY nocab
											)a

											inner join `mpm`.`tabcomp` `b` USING (`nocab`)
											where b.".$wilayah."".$tambahDP."
											group by `naper`
											order by sub, naper
								)a 
								GROUP BY sub
						)a inner JOIN 
									(
										select 	naper naper2,`status`, urutan, nama_comp nama
										from 		mpm.tbl_tabcomp_new
										WHERE 	`status` = '2' and status_cluster <> '1'
									)c on a.sub = c.naper2			
									
						GROUP BY sub
						ORDER BY c.urutan asc
						)b

						UNION ALL

						select	'Z', '', 
								'TOTAL',
								sum(if(blndok = 1, trans, 0)) as t1,
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
								".$id.",format(SUM(unit),0) as total,'-',0,0,0,0,0,999
						from
						(
							 select `nocab`, `blndok`, sum(`tot1`) as `unit`, count(distinct(concat(kode_comp,kode_lang))) as trans
							 from `data".$year."`.`fi`
							 where `nodokjdi` <> 'XXXXXX' 
									".$wheresupp."
							 group by `nocab`,`blndok`

							 union all

							 select `nocab`, `blndok`, sum(`tot1`) as `unit`,0 as trans
							 from `data".$year."`.`ri`
							 where `nodokjdi` <> 'XXXXXX' 
									".$wheresupp."
							 group by `nocab`,`blndok`
						) `a` inner join `mpm`.`tabcomp` `b` USING (`nocab`)
						where b.".$wilayah."".$tambahDP."
						ORDER BY urutan
					)a LEFT JOIN
					(
							select 	max(lastupload) as maxupload,
											substring(filename,3,2) naper  
									from 	mpm.upload 
									group by naper
					)b on a.naper = b.naper
	        ";

	        //echo "<pre>";
	        	//print_r($query);
	        //echo "</pre>";

	        $gabung = "insert into mpm.omzet_new ".$query;
			
	        $sql = $this->db->query($gabung);

		/* END PROSES INSERT KE TABEL OMZET_NEW */

		/* PROSES TAMPIL KE WEBSITE */

			$this->db->order_by('urutan','asc');
			$this->db->where("id = ".'"'.$id.'"');
			$hasil = $this->db->get('omzet_new');		
			if ($hasil->num_rows() > 0) 
			{
				return $hasil->result();
			} else {
				return array();
			}
		/* END PROSES TAMPIL KE WEBSITE */
	}

	public function omzet_all_dp_timur($dataSegment){

	/* ---------DEFINISI VARIABEL----------------- */
		$id=$this->session->userdata('id');

		if ( function_exists( 'date_default_timezone_set' ) )
        date_default_timezone_set('Asia/Jakarta');        
        $tgl_created='"'.date('Y-m-d H:i:s').'"';

		$year = $dataSegment['tahun'];
		//echo "tahun di model : ".$year;
		$supp = $dataSegment['supp'];
		//echo "supplier di model : ".$supp;

			if($supp=='000')
            {
                if ($id == '191')//jika user janleo 
					{ 
						$wheresupp='and (
											kodeprod like "60%" or kodeprod like "01%" or 
											kodeprod like "50%" or kodeprod like "70%" or 
											kodeprod like "06%" or kodeprod like "02%" or
											kodeprod like "04%"
										) and (nocab != "r1") or nocab = "98" 
									';

						$tambahDP='or nocab in(98,89,91)';
					}

					else{

						$wheresupp='
									and (kodeprod like "60%" or kodeprod like "01%" or 
										kodeprod like "50%" or kodeprod like "70%" or 
										kodeprod like "06%" or kodeprod like "02%" or
										kodeprod like "04%") and (nocab != "r1")
						';

						$tambahDP='';
					}
            }
            else if($supp=='001') //DELTO
            {                
                $wheresupp='and (kodeprod like "60%" or kodeprod like "01%" or kodeprod like "50%" or kodeprod like "70%") and (nocab != "r1")';
                $tambahDP='';
            }
            else if($supp=='005') //ULTRA SAKTI
            {
                $wheresupp='and kodeprod like "06%" ';
                $tambahDP='';
            }
            else if($supp=='002') //marguna
            {
                $wheresupp='and kodeprod like "02%" ';
                $tambahDP='';
            }
            else if($supp=='009') //UNILEVER
            {
                $wheresupp='and kodeprod like "20%" or kodeprod like "21%" or kodeprod like "62%" or kodeprod like "67%"  ';
                 $tambahDP='';
            }
            else if($supp=='XXX')
            {                    
                $wheresupp='';
                $tambahDP='';
            }
            else
            {
                $wheresupp="and kodeprod like '".substr($supp,-2)."%'";
                $tambahDP='';
            }	

		/* ---------END DEFINISI VARIABEL---------------- */

		/* PROSES DELETE MPM.OMZET_NEW */
			$query = "delete from mpm.omzet_new where id = ".$id."";
			$sql = $this->db->query($query);
			/*
			echo "<pre>";
			print_r($query);
			echo "</pre>";
			*/
		/* END PROSES DELETE MPM.OMZET_NEW */

		/* cari bulan berjalan */
			$bulan = date("m");
			$tahunsekarang = date("Y");
			/*
			echo "bulan : ".$bulan."<br>";
			echo "tahun : ".$tahunsekarang."<br>";
			echo "year : ".$year;
			*/
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
				$wilayah = "wilayah = 2";
				//echo "wilayah : ".$wilayah."<br>";
			} else {
				$wilayah = "wilayah_2017 = 2";
				//echo "wilayah : ".$wilayah."<br>";
			}
		/* end pontianak masuk ke wilayah timur per 2017*/

		/* PROSES INSERT KE TABEL OMZET_NEW */
			
	        $query = "	        		
	        		select 	a.naper, sub, nama_comp, t1, b1, t2, b2, t3, b3, t4, b4, t5, b5, t6, b6, t7, b7, t8, b8, t9, b9, t10, b10, t11, b11, t12, b12, ".$id.", total, rata,".'"'.$supp.'"'.",".$tgl_created.",".$year.", urutan, maxupload
					FROM
					(
						select *
						FROM
						(
								select *
								from
								(
									SELECT  naper, sub, b.NAMA_COMP,
											t1,b1,
											t2,b2,
											t3,b3,
											t4,b4,
											t5,b5,
											t6,b6,
											t7,b7,
											t8,b8,
											t9,b9,
											t10,b10, 
											t11,b11,
											t12,b12, 
											".$id.", 
											format(total,0) total, 
											format(totalx/jmlbulan,0) rata,".'"'.$supp.'"'.",".$tgl_created.",".$year."
									FROM
									(
											select  nocab, b1, b2, b3, b4, b5, b6, b7, b8, b9, b10, b11, b12, ".$id.", 
													total, totalx,
													format(sum($pembagi),0, 1) jmlbulan,t1,t2,t3,t4,t5,t6,t7,t8,t9,t10,t11,t12
											FROM
											(
												select  nocab,                              
														b1, b2, b3, b4, b5, b6, b7, b8, b9, b10, b11, b12,
														".$id.",
														b1+b2+b3+b4+b5+b6+b7+b8+b9+b10+b11+b12 as total,
														$totalx as totalx,                                
														IF(b1 = 0, 0, 1) xb1,IF(b2 = 0, 0, 1) xb2,IF(b3 = 0, 0, 1) xb3,
														IF(b4 = 0, 0, 1) xb4,IF(b5 = 0, 0, 1) xb5,IF(b6 = 0, 0, 1) xb6,
														IF(b7 = 0, 0, 1) xb7,IF(b8 = 0, 0, 1) xb8,IF(b9 = 0, 0, 1) xb9,
														IF(b10 = 0, 0, 1) xb10, IF(b11 = 0, 0, 1) xb11, IF(b12 = 0, 0, 1) xb12,
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
														sum(t12) as t12
												from
												(
														select
																`nocab`,
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
															 select `nocab`, `blndok`, sum(`tot1`) as `unit`, count(distinct(concat(kode_comp,kode_lang))) as trans
															 from `data".$year."`.`fi`
															 where `nodokjdi` <> 'XXXXXX' 
																	".$wheresupp."									
															 group by `nocab`,`blndok`

															 union all

															 select `nocab`, `blndok`, sum(`tot1`) as `unit`,0 as trans
															 from `data".$year."`.`ri`
															 where `nodokjdi` <> 'XXXXXX' 
																	".$wheresupp."									
															 group by `nocab`,`blndok`
														) `a`
															group by `nocab`
												)`a` GROUP BY nocab
											)a GROUP BY nocab
									)a

									inner join `mpm`.`tabcomp` `b` USING (`nocab`)
									where b.".$wilayah."".$tambahDP."
									group by `naper`
									order by `naper`
								)a LEFT JOIN 
									(
										select 	naper naper2,`status`, urutan
										from 		mpm.tbl_tabcomp_new
										WHERE 	`status` = '1' and status_cluster <> '1'
									)c on a.naper = c.naper2			
									ORDER BY c.urutan asc

						)a

						union ALL

						select *
						FROM
						(
						select	NAPER, sub, nama, t1,b1,
											t2,b2,
											t3,b3,
											t4,b4,
											t5,b5,
											t6,b6,
											t7,b7,
											t8,b8,
											t9,b9,
											t10,b10, 
											t11,b11,
											t12,b12,
											".$id.", 
											format(total,0) as total,
											format(rata,0) as rata,3,4,5,6,7,c.urutan
						from
						(
								select	NAPER, sub, NAMA_COMP, SUM(b1) b1,
												SUM(b2) b2,
												SUM(b3) b3,
												SUM(b4) b4,
												SUM(b5) b5,
												SUM(b6) b6,
												SUM(b7) b7,
												SUM(b8) b8,
												SUM(b9) b9,
												SUM(b10) b10,
												SUM(b11) b11,
												SUM(b12) b12,
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
												sum(total) as total,
												sum(rata) as rata
						from
								(
											SELECT  naper, sub, b.NAMA_COMP,
													t1,b1,
													t2,b2,
													t3,b3,
													t4,b4,
													t5,b5,
													t6,b6,
													t7,b7,
													t8,b8,
													t9,b9,
													t10,b10, 
													t11,b11,
													t12,b12, 
													".$id.", 
													total, 
													(totalx/jmlbulan) as rata,".'"'.$supp.'"'.",".$tgl_created.",".$year."
											FROM
											(
													select  nocab, b1, b2, b3, b4, b5, b6, b7, b8, b9, b10, b11, b12, ".$id.", 
															total, totalx,
															format(sum($pembagi),0, 1) jmlbulan,t1,t2,t3,t4,t5,t6,t7,t8,t9,t10,t11,t12
													FROM
													(
														select  nocab,                              
																b1, b2, b3, b4, b5, b6, b7, b8, b9, b10, b11, b12,
																".$id.",
																b1+b2+b3+b4+b5+b6+b7+b8+b9+b10+b11+b12 as total,
																$totalx as totalx,                                
																IF(b1 = 0, 0, 1) xb1,IF(b2 = 0, 0, 1) xb2,IF(b3 = 0, 0, 1) xb3,
																IF(b4 = 0, 0, 1) xb4,IF(b5 = 0, 0, 1) xb5,IF(b6 = 0, 0, 1) xb6,
																IF(b7 = 0, 0, 1) xb7,IF(b8 = 0, 0, 1) xb8,IF(b9 = 0, 0, 1) xb9,
																IF(b10 = 0, 0, 1) xb10, IF(b11 = 0, 0, 1) xb11, IF(b12 = 0, 0, 1) xb12,t1,t2,t3,t4,t5,t6,t7,t8,t9,t10,t11,t12
														from
														(
																select
																		`nocab`,
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
																	 select `nocab`, `blndok`, sum(`tot1`) as `unit`, count(distinct(concat(kode_comp,kode_lang))) as trans
																	 from `data".$year."`.`fi`
																	 where `nodokjdi` <> 'XXXXXX' 
																			".$wheresupp."									
																	 group by `nocab`,`blndok`

																	 union all

																	 select `nocab`, `blndok`, sum(`tot1`) as `unit`,0 as trans
																	 from `data".$year."`.`ri`
																	 where `nodokjdi` <> 'XXXXXX' 
																			".$wheresupp."									
																	 group by `nocab`,`blndok`
																) `a`
																	group by `nocab`
														)`a` GROUP BY nocab
													)a GROUP BY nocab
											)a

											inner join `mpm`.`tabcomp` `b` USING (`nocab`)
											where b.".$wilayah."".$tambahDP." 
											group by `naper`
											order by sub, naper
								)a 
								GROUP BY sub
						)a inner JOIN 
									(
										select 	naper naper2,`status`, urutan, nama_comp nama
										from 		mpm.tbl_tabcomp_new
										WHERE 	`status` = '2' and status_cluster <> '1'
									)c on a.sub = c.naper2			
									
						GROUP BY sub
						ORDER BY c.urutan asc
						)b

						UNION ALL

						select	'Z', '', 
								'TOTAL',
								sum(if(blndok = 1, trans, 0)) as t1,
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
								".$id.",format(SUM(unit),0) as total,'-',0,0,0,0,0,999
						from
						(
							 select `nocab`, `blndok`, sum(`tot1`) as `unit`, count(distinct(concat(kode_comp,kode_lang))) as trans
							 from `data".$year."`.`fi`
							 where `nodokjdi` <> 'XXXXXX' 
									".$wheresupp."
							 group by `nocab`,`blndok`

							 union all

							 select `nocab`, `blndok`, sum(`tot1`) as `unit`,0 as trans
							 from `data".$year."`.`ri`
							 where `nodokjdi` <> 'XXXXXX' 
									".$wheresupp."
							 group by `nocab`,`blndok`
						) `a` inner join `mpm`.`tabcomp` `b` USING (`nocab`)
						where b.".$wilayah."".$tambahDP."
						ORDER BY urutan
					)a LEFT JOIN
					(
							select 	max(lastupload) as maxupload,
											substring(filename,3,2) naper  
									from 	mpm.upload 
									group by naper
					)b on a.naper = b.naper
	        ";
	        /*
	        echo "<pre>";
	        	print_r($query);
	        echo "</pre>";
			*/
	        $gabung = "insert into mpm.omzet_new ".$query;
	  		
	        $sql = $this->db->query($gabung);

		/* END PROSES INSERT KE TABEL OMZET_NEW */


		/* PROSES TAMPIL KE WEBSITE */
		
			$this->db->order_by('urutan','asc');
			$this->db->where("id = ".'"'.$id.'"');
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