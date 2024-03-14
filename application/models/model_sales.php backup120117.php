<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_sales extends CI_Model {

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

    public function list_product()
    {
        $supp=$this->session->userdata('supp');
        
        if($supp=='000')
        {
            return $this->db->query('select supp,namasupp,kodeprod, namaprod from mpm.tabprod a left join mpm.tabsupp b using(supp) where left(kodeprod,3)<>"BSP"  and a.report=1 order by supp,namaprod');

        }
        else
        {
            return $this->db->query('select supp,namasupp,kodeprod, namaprod from mpm.tabprod a left join mpm.tabsupp b using(supp) where supp='.$supp.' and left(kodeprod,3)<>"BSP" and a.report=1 order by namaprod');
        }
    }

    public function list_product_permen()
    {
        $supp=$this->session->userdata('supp');
        
        if($supp=='000')
        {
            return $this->db->query('select supp,namasupp,kodeprod, namaprod from mpm.tabprod a left join mpm.tabsupp b using(supp) where left(kodeprod,3)<>"BSP"  and a.report=1 and permen=1 order by supp,namaprod');
        }
        else
        {
            return $this->db->query('select supp,namasupp,kodeprod, namaprod from mpm.tabprod a left join mpm.tabsupp b using(supp) where supp='.$supp.' and left(kodeprod,3)<>"BSP" and a.report=1 and permen=1 order by namaprod');
        }
    }

    public function sales_per_product($dataSegment){
    	
    	$user=$this->session->userdata('username');
        if ( function_exists( 'date_default_timezone_set' ) )
        date_default_timezone_set('Asia/Jakarta');        

        /* ---------DEFINISI VARIABEL----------------- */
        
            $tgl_created='"'.date('Y-m-d H:i:s').'"';        
            $id=$this->session->userdata('id');
            $uv = $dataSegment['uv'];

            switch($uv)
			   {
				case 0:
					$unit='banyak';
					break;
				case 1:
					$unit='tot1';
					break;
			   }

			   //cek userid
					if ($id == '318') { //jika user dimas, lihat all jkt & karawang
						$tambahan='and nocab = 88 or nocab = 07';
					}

					elseif ($id == '319') { //user sutrisno, all jbr
						$tambahan='and nocab = 95';
					}

					elseif ($id == '320') { //user kristiyanto, all jtg
						$tambahan='and nocab = 89 or nocab = 19 or nocab = 32 or nocab = "p3" or nocab = "j1"';
					}

					elseif ($id == '321') { //user hengki, kudus, blora, pati, jepara
						$tambahan='and nocab = 19 or nocab = 32';
					}

					elseif ($id == '322') { //user dawam, diy, solo
						$tambahan='and nocab = 98 or nocab = 18 or nocab = 96';
					}

					elseif ($id == '323') { //user miko, all jtm dan seluruh dp jawa timur (mlg, sdo, kdr, tga, mdu, blt)
						$tambahan='and (nocab = 91 or nocab = 27 or nocab = 46 or nocab = 74 or nocab = 24 or nocab = 26 or nocab = 23 or nocab = 69 or nocab = "c1" or nocab = "p4" or nocab = "p5" or nocab = 25 or nocab = 29 or nocab = 50 or nocab = 83 or nocab = "G1" or nocab = "P1" or nocab = "T1" or nocab = "S1" or nocab = "L1" or nocab = "B2" or nocab = "B1") ';
					}

					elseif ($id == '365') { //user wahyu
						$tambahan='and nocab = "J2"';
					}

					else{
						$tambahan='';
					}

            $year = $dataSegment['tahun'];
            $code = $dataSegment['code'];
            //echo "year : ".$year;
            //echo "<br />uv : ".$uv."<br>";

        /* ---------END DEFINISI VARIABEL---------------- */

        /* PROSES DELETE MPM.soprod_new */
            $query = "delete from mpm.soprod_new where id = ".$id."";
            $sql = $this->db->query($query);
            
            //echo "<pre>";
            //print_r($query);
            //echo "</pre>";            
        
        /* END PROSES DELETE MPM.OUTLET_NEW */

        /* PROSES INSERT KE MPM.soprod_new */
        
        	$sql="
            insert into mpm.soprod_new
            select	naper, nama_comp, 
					t1, b1, 
					t2, b2, 
					t3, b3, 
					t4, b4, 
					t5, b5,
					t6, b6, 
					t7, b7, 
					t8, b8, 
					t9, b9, 
					t10, b10,
					t11, b11,
					t12, b12, sub, urutan,".$id."
			FROM
			(		
					select `naper`,`b`.`nama_comp`,
							sum(`t1`) as `t1`,sum(`b1`) as `b1`,
							sum(`t2`) as `t2`,sum(`b2`) as `b2`,
							sum(`t3`) as `t3`,sum(`b3`) as `b3`,
							sum(`t4`) as `t4`,sum(`b4`) as `b4`,
							sum(`t5`) as `t5`,sum(`b5`) as `b5`,
							sum(`t6`) as `t6`,sum(`b6`) as `b6`,
							sum(`t7`) as `t7`,sum(`b7`) as `b7`,
							sum(`t8`) as `t8`,sum(`b8`) as `b8`,
							sum(`t9`) as `t9`,sum(`b9`) as `b9`,
							sum(`t10`) as `t10`,sum(`b10`) as `b10`,
							sum(`t11`) as `t11`,sum(`b11`) as `b11`,
							sum(`t12`) as `t12`,sum(`b12`) as `b12`,
							".$id.", sub
					from
					(
							select `nocab`,
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
									sum(if(`blndok` = 1, `trans`, 0)) as `t1`,
									sum(if(`blndok` = 2, `trans`, 0)) as `t2`,
									sum(if(`blndok` = 3, `trans`, 0)) as `t3`,
									sum(if(`blndok` = 4, `trans`, 0)) as `t4`,
									sum(if(`blndok` = 5, `trans`, 0)) as `t5`,
									sum(if(`blndok` = 6, `trans`, 0)) as `t6`,
									sum(if(`blndok` = 7, `trans`, 0)) as `t7`,
									sum(if(`blndok` = 8, `trans`, 0)) as `t8`,
									sum(if(`blndok` = 9, `trans`, 0)) as `t9`,
									sum(if(`blndok` = 10, `trans`, 0)) as `t10`,
									sum(if(`blndok` = 11, `trans`, 0)) as `t11`,
									sum(if(`blndok` = 12, `trans`, 0)) as `t12`
							from
							(
								select nocab, blndok, outlet, SUM(`".$unit."`) as unit, COUNT(DISTINCT(outlet)) as trans
								FROM
								(
									select nocab, blndok, `".$unit."`, CONCAT(KODE_COMP,kode_lang) as outlet
									from `data".$year."`.`fi`
									where`kodeprod` in (".$code.") and `nodokjdi` <> 'XXXXXX' ".$tambahan."

									union all

									select nocab, blndok, `".$unit."`, null
									from `data".$year."`.`ri`
									where`kodeprod` in (".$code.") and `nodokjdi` <> 'XXXXXX' ".$tambahan."
								)a
								group by nocab, blndok                                 
							) `a`
							group by `nocab`
					) `a` inner join `mpm`.`tabcomp` `b` USING (`nocab`)
					group by `naper`
					order by `naper`
			)a LEFT JOIN 
			(
				select 	naper naper2,`status`, urutan, nama_comp nama
				from 		mpm.tbl_tabcomp_new
				WHERE 	`status` = '1'
			)c on a.naper = c.naper2

			union all

			select 	naper, c.nama, 
							t1, b1, 
							t2, b2, 
							t3, b3, 
							t4, b4, 
							t5, b5, 
							t6, b6, 
							t7, b7, 
							t8, b8, 
							t9, b9, 
							t10, b10,
							t11, b11,
							t12, b12, sub, urutan,".$id."
			FROM
			(
					SELECT	naper, nama_comp, sub, 
									SUM(t1) as t1, SUM(b1) as b1,
									SUM(t2) as t2, SUM(b2) as b2,
									SUM(t3) as t3, SUM(b3) as b3,
									SUM(t4) as t4, SUM(b4) as b4,
									SUM(t5) as t5, SUM(b5) as b5,
									SUM(t6) as t6, SUM(b6) as b6,
									SUM(t7) as t7, SUM(b7) as b7,
									SUM(t8) as t8, SUM(b8) as b8,
									SUM(t9) as t9, SUM(b9) as b9,
									SUM(t10) as t10, SUM(b10) as b10,
									SUM(t11) as t11, SUM(b11) as b11,
									SUM(t12) as t12, SUM(b12) as b12
					FROM
					(
							
							select `naper`,`b`.`nama_comp`, sub,
									sum(`t1`) as `t1`,sum(`b1`) as `b1`,
									sum(`t2`) as `t2`,sum(`b2`) as `b2`,
									sum(`t3`) as `t3`,sum(`b3`) as `b3`,
									sum(`t4`) as `t4`,sum(`b4`) as `b4`,
									sum(`t5`) as `t5`,sum(`b5`) as `b5`,
									sum(`t6`) as `t6`,sum(`b6`) as `b6`,
									sum(`t7`) as `t7`,sum(`b7`) as `b7`,
									sum(`t8`) as `t8`,sum(`b8`) as `b8`,
									sum(`t9`) as `t9`,sum(`b9`) as `b9`,
									sum(`t10`) as `t10`,sum(`b10`) as `b10`,
									sum(`t11`) as `t11`,sum(`b11`) as `b11`,
									sum(`t12`) as `t12`,sum(`b12`) as `b12`,
									".$id."
							from
							(
									select `nocab`,
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
											sum(if(`blndok` = 1, `trans`, 0)) as `t1`,
											sum(if(`blndok` = 2, `trans`, 0)) as `t2`,
											sum(if(`blndok` = 3, `trans`, 0)) as `t3`,
											sum(if(`blndok` = 4, `trans`, 0)) as `t4`,
											sum(if(`blndok` = 5, `trans`, 0)) as `t5`,
											sum(if(`blndok` = 6, `trans`, 0)) as `t6`,
											sum(if(`blndok` = 7, `trans`, 0)) as `t7`,
											sum(if(`blndok` = 8, `trans`, 0)) as `t8`,
											sum(if(`blndok` = 9, `trans`, 0)) as `t9`,
											sum(if(`blndok` = 10, `trans`, 0)) as `t10`,
											sum(if(`blndok` = 11, `trans`, 0)) as `t11`,
											sum(if(`blndok` = 12, `trans`, 0)) as `t12`
									from
									(
										select nocab, blndok, outlet, SUM(`".$unit."`) as unit, COUNT(DISTINCT(outlet)) as trans
										FROM
										(
											select nocab, blndok, `".$unit."`, CONCAT(KODE_COMP,kode_lang) as outlet
											from `data".$year."`.`fi`
											where`kodeprod` in (".$code.") and `nodokjdi` <> 'XXXXXX' ".$tambahan."

											union all

											select nocab, blndok, `".$unit."`, null
											from `data".$year."`.`ri`
											where`kodeprod` in (".$code.") and `nodokjdi` <> 'XXXXXX' ".$tambahan."
										)a
										group by nocab, blndok                                 
									) `a`
									group by `nocab`
							) `a` inner join `mpm`.`tabcomp` `b` USING (`nocab`)
							group by `naper`
							order by `sub` desc
					)a GROUP BY sub
			)a INNER JOIN 
			(
				select 	naper naper2,`status`, urutan, nama_comp nama
				from 		mpm.tbl_tabcomp_new
				WHERE 	`status` = '2'
			)c on a.sub = c.naper2

			UNION ALL

			SELECT	'Z', 'TOTAL', 
							t1, b1, 
							t2, b2, 
							t3, b3, 
							t4, b4, 
							t5, b5, 
							t6, b6, 
							t7, b7, 
							t8, b8, 
							t9, b9, 
							t10, b10,
							t11, b11,
							t12, b12, 0,999,".$id."
			FROM
			(		
					select `naper`,`b`.`nama_comp`,
							sum(`t1`) as `t1`,sum(`b1`) as `b1`,
							sum(`t2`) as `t2`,sum(`b2`) as `b2`,
							sum(`t3`) as `t3`,sum(`b3`) as `b3`,
							sum(`t4`) as `t4`,sum(`b4`) as `b4`,
							sum(`t5`) as `t5`,sum(`b5`) as `b5`,
							sum(`t6`) as `t6`,sum(`b6`) as `b6`,
							sum(`t7`) as `t7`,sum(`b7`) as `b7`,
							sum(`t8`) as `t8`,sum(`b8`) as `b8`,
							sum(`t9`) as `t9`,sum(`b9`) as `b9`,
							sum(`t10`) as `t10`,sum(`b10`) as `b10`,
							sum(`t11`) as `t11`,sum(`b11`) as `b11`,
							sum(`t12`) as `t12`,sum(`b12`) as `b12`,
							".$id.", sub
					from
					(
							select `nocab`,
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
									sum(if(`blndok` = 1, `trans`, 0)) as `t1`,
									sum(if(`blndok` = 2, `trans`, 0)) as `t2`,
									sum(if(`blndok` = 3, `trans`, 0)) as `t3`,
									sum(if(`blndok` = 4, `trans`, 0)) as `t4`,
									sum(if(`blndok` = 5, `trans`, 0)) as `t5`,
									sum(if(`blndok` = 6, `trans`, 0)) as `t6`,
									sum(if(`blndok` = 7, `trans`, 0)) as `t7`,
									sum(if(`blndok` = 8, `trans`, 0)) as `t8`,
									sum(if(`blndok` = 9, `trans`, 0)) as `t9`,
									sum(if(`blndok` = 10, `trans`, 0)) as `t10`,
									sum(if(`blndok` = 11, `trans`, 0)) as `t11`,
									sum(if(`blndok` = 12, `trans`, 0)) as `t12`
							from
							(
								select nocab, blndok, outlet, SUM(`".$unit."`) as unit, COUNT(DISTINCT(outlet)) as trans
								FROM
								(
									select nocab, blndok, `".$unit."`, CONCAT(KODE_COMP,kode_lang) as outlet
									from `data".$year."`.`fi`
									where`kodeprod` in (".$code.") and `nodokjdi` <> 'XXXXXX' ".$tambahan."

									union all

									select nocab, blndok, `".$unit."`, null
									from `data".$year."`.`ri`
									where`kodeprod` in (".$code.") and `nodokjdi` <> 'XXXXXX' ".$tambahan."
								)a
								group by nocab, blndok                                 
							) `a`
							group by `nocab`
					) `a` inner join `mpm`.`tabcomp` `b` USING (`nocab`)					
					order by `naper`
			)a 

			ORDER BY urutan
                    ";


        /* END PROSES DELETE MPM.soprod_NEW */

            //echo "<pre>";
            //print_r($sql);
            //echo "</pre>";

            $sql_insert = $this->db->query($sql);

        /* PROSES TAMPIL KE WEBSITE */
            //$this->db->join('tbl_tabcomp_new', 'tbl_tabcomp_new.naper = soprod_new.naper','inner');
            //$this->db->where("status = 1");
            $this->db->order_by('urutan','asc');           
            $this->db->where("soprod_new.id = ".'"'.$id.'"');
            $hasil = $this->db->get('soprod_new');       
            if ($hasil->num_rows() > 0) 
            {
                return $hasil->result();
            } else {
                return array();
            }
        /* END PROSES TAMPIL KE WEBSITE */

    }

    

}

/* End of file model_sales.php */
/* Location: ./application/models/model_sales.php */