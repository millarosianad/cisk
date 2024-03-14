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

    public function get_namacomp($key='')
    {
        //$year=year(now());
        //$year = '2017';
        $userid=$this->session->userdata('id');
        $nocab=$this->session->userdata('nocab');
        $year = $key['year'];

        if ($nocab!='')
        {
        
            $sql="

            select a.nocab, b.nama_comp as nama_comp 
                from data$year.th a INNER JOIN
                (
                    select  naper, nocab, kode_comp, nama_comp
                    FROM    mpm.tbl_tabcomp
                    WHERE   status = 1 and nocab = '$nocab'
                    GROUP BY nocab
                    order by nama_comp
                )b on a.NOCAB = b.nocab
                GROUP BY a.nocab
                ORDER BY b.nama_comp";
        }else{

            /*cek hak DP apa saja yang dapat dilihat*/
                $this->db->where('id = '.'"'.$userid.'"');
                $query = $this->db->get('mpm.user');
                foreach ($query->result() as $row) {
                    $dp = $row->wilayah_nocab;
                    //echo "nocab : ".$dp."<br>";
                    //return $wilayah_nocab;
                }

                if ($dp == NULL || $dp == '' )
                {
                    $daftar_dp = '';
                } else {
                    $daftar_dp = 'and nocab in ('.$dp.')';
                }

                $sql="
                select a.nocab, b.nama_comp as nama_comp 
                from data$year.th a INNER JOIN
                (
                    select  naper, nocab, kode_comp, nama_comp
                    FROM    mpm.tbl_tabcomp
                    WHERE   status = 1 $daftar_dp
                    GROUP BY nocab
                    order by nama_comp
                )b on a.NOCAB = b.nocab
                GROUP BY a.nocab
                ORDER BY b.nama_comp";
                    
                /*end cek hak DP apa saja yang dapat dilihat*/

        }

        //$query=$this->db->query($sql,array($key));
        $query=$this->db->query($sql);
        return $query;

    }

    public function list_product()
    {
        $supp=$this->session->userdata('supp');
        $id=$this->session->userdata('id');
        //echo "<pre>";
        //print_r($supp);
        //print_r($sql);
        //echo "</pre>";

        if($supp=='000')
        {
            return $this->db->query("
                select supp, namasupp,kodeprod, namaprod, concat(supp,grup) as suppx
                FROM
                (
                        select  supp,namasupp,kodeprod, namaprod,if(grup is null, '',grup) as grup
                        FROM
                        (
                                select  supp,namasupp,kodeprod, namaprod,grup
                                from    mpm.tabprod a left join mpm.tabsupp b using(supp) 
                                where   left(kodeprod,3)<>'BSP'  and a.report=1 
                                order   by supp,namaprod
                        )a
                )a ORDER BY supp, namaprod
            ");

        }elseif ($supp=='001' and $id =='374') { //khusus pak mardohar digabung antara candy dan natura prima
             $sql = "
                select supp, namasupp,kodeprod, namaprod, concat(supp,grup) as suppx
                FROM
                (
                    select  supp,namasupp,kodeprod, namaprod,if(grup is null, '',grup) as grup
                    FROM
                    (
                                    select  supp,namasupp,kodeprod, namaprod,grup
                                    from    mpm.tabprod a left join mpm.tabsupp b using(supp) 
                                    where   left(kodeprod,3)<>'BSP'  and a.report=1 and (grup='G0102' or supp ='010')
                                    order   by supp,namaprod
                    )a
                )a 

                ORDER BY supp, namaprod
             ";

             $sql = "
                select supp, namasupp,kodeprod, namaprod, concat(supp,grup) as suppx
                FROM
                (
                        select  supp,namasupp,kodeprod, namaprod,if(grup is null, '',grup) as grup
                        FROM
                        (
                                select  supp,namasupp,kodeprod, namaprod,grup
                                from    mpm.tabprod a left join mpm.tabsupp b using(supp) 
                                where   left(kodeprod,3)<>'BSP'  and a.report=1 
                                order   by supp,namaprod
                        )a
                )a ORDER BY supp, namaprod
             ";

             return $this->db->query($sql);
        }
        else
        {
            
            $sql = "
                select supp, namasupp,kodeprod, namaprod, concat(supp,grup) as suppx
                FROM
                (
                        select  supp,namasupp,kodeprod, namaprod,if(grup is null, '',grup) as grup
                        FROM
                        (
                                select  supp,namasupp,kodeprod, namaprod,grup
                                from    mpm.tabprod a left join mpm.tabsupp b using(supp) 
                                where   supp='$supp' and left(kodeprod,3)<>'BSP'  and a.report=1 
                                order   by supp,namaprod
                        )a
                )a ORDER BY supp, namaprod

            ";

            return $this->db->query($sql);
        }
    }

    public function list_product_unilever()
    {
        $supp=$this->session->userdata('supp');
        
        if($supp=='000')
        {
            return $this->db->query('
            		select 	supp,namasupp,kodeprod, namaprod 
            		from 	mpm.tabprod_unilever a left join mpm.tabsupp b using(supp) 
            		where 	left(kodeprod,3)<>"BSP"  order by supp,namaprod');

        }
        else
        {
            return $this->db->query('select supp,namasupp,kodeprod, namaprod from mpm.tabprod a left join mpm.tabsupp b using(supp) where supp='.$supp.' and left(kodeprod,3)<>"BSP" and a.report=1 order by namaprod');
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

    public function list_subbranch($data){
    /*
        $this->db->group_by('kode_comp');
        $this->db->where('status =1');
        $this->db->where('sub = '.'"'.$data['branch'].'"');
        $query = $this->db->get('mpm.tbl_tabcomp');
        foreach ($query->result_array() as $row) {
            $kode_comp[] = $row['kode_comp'];
        }
    */
        $x = $data['branch'];

        $sql = "
            select  kode_comp
            from    mpm.tbl_tabcomp
            where   `status` = 1 and sub = '$x' and kode_comp not in (
                select  kode_comp    
                from    mpm.tbl_tabcomp
                where   `status` = 1 and sub <> '$x'
            )
            GROUP BY kode_comp
        ";

        $query = $this->db->query($sql);
        foreach ($query->result_array() as $row) {
            $kode_comp[] = $row['kode_comp'];
        }



        $jumlah_array = count($kode_comp);
        //echo "<br>jumlah kode_comp : ".$jumlah_array."<br>";
        //print_r($kode_comp);
        $convert = implode("','",$kode_comp);
        return $convert;
        

    }

    public function list_product_permen()
    {
        $supp=$this->session->userdata('supp');
        $id=$this->session->userdata('id');
        
        //echo "<pre>supp : ".$supp."</pre>";
        //echo "<pre>id : ".$id."</pre>";

        //kondisi jika user id milik pak mardohar ditambah produk natura
        if($supp=='000' and $id <> '374')
        {
            $sql = "
               select supp, namasupp,kodeprod, namaprod, concat(supp,grup) as suppx
                FROM
                (
                        select  supp,namasupp,kodeprod, namaprod,grup
                        FROM
                        (
                                select  supp,namasupp,kodeprod, namaprod,grup
                                from    mpm.tabprod a left join mpm.tabsupp b using(supp) 
                                where   supp='001' and left(kodeprod,3)<>'BSP'  and a.report=1 and grup ='G0102'
                                order   by supp,namaprod
                        )a 
                )a ORDER BY supp, namaprod
            ";
            //echo "<pre>";
            //print_r($sql);
            //echo "</pre>";
            return $this->db->query($sql);

        }elseif ($supp=='001' and $id =='374') {
             $sql = "
                 select supp, namasupp,kodeprod, namaprod, concat(supp,grup) as suppx
                FROM
                (
                        select  supp,namasupp,kodeprod, namaprod,grup
                        FROM
                        (
                                select  supp,namasupp,kodeprod, namaprod,grup
                                from    mpm.tabprod a left join mpm.tabsupp b using(supp) 
                                where   supp='001' and left(kodeprod,3)<>'BSP'  and a.report=1 and grup ='G0102'
                                union all
                                select  supp,namasupp,kodeprod, namaprod,grup
                                from    mpm.tabprod a left join mpm.tabsupp b using(supp) 
                                where   supp='010' and left(kodeprod,3)<>'BSP'  and a.report=1 
                                order   by supp,namaprod
    
                        )a 
                )a ORDER BY supp, namaprod


             ";

             return $this->db->query($sql);
        }
        else
        {
             $sql = "
                select supp, namasupp,kodeprod, namaprod, concat(supp,grup) as suppx
                FROM
                (
                        select  supp,namasupp,kodeprod, namaprod,grup
                        FROM
                        (
                                select  supp,namasupp,kodeprod, namaprod,grup
                                from    mpm.tabprod a left join mpm.tabsupp b using(supp) 
                                where   supp='$supp' and left(kodeprod,3)<>'BSP'  and a.report=1 and grup ='G0102'
                                order   by supp,namaprod
                        )a 
                )a ORDER BY supp, namaprod
            ";
            return $this->db->query($sql);
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

            $year = $dataSegment['tahun'];
            $code = $dataSegment['code'];
            //echo "year : ".$year;
            //echo "<br />uv : ".$uv."<br>";
            $groupby = $dataSegment['groupby'];
            if ($groupby =='1') {
                $groupbyx = "a.kodeprod,";
                $groupbyy = ",a.kodeprod";
            }else{
                $groupbyx = "";
                $groupbyy = "";
            }

            $dp = $dataSegment['query'];
            //echo "dp : ".$dp;
            if ($dp == NULL || $dp == '') {
                $daftar_dp = '';
            } else {
                $daftar_dp = 'and nocab in ('.$dp.')';
                //echo " <br>daftar_dp : ".$daftar_dp;
            }
           
            //$daftar_dp = 'nocab in ('.'"'.$dp.'"'.')';
           
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
            select `nocab`,a.kode_comp, b.branch_name,b.nama_comp,
                    a.kodeprod,c.namaprod,c.grup, d.nama_group,
                    sum(if(`blndok` = 1, `trans`, 0)) as `t1`,
                    sum(if(`blndok` = 1, `unit`, 0)) as `b1`,
                    sum(if(`blndok` = 2, `trans`, 0)) as `t2`,
                    sum(if(`blndok` = 2, `unit`, 0)) as `b2`,
                    sum(if(`blndok` = 3, `trans`, 0)) as `t3`,
                    sum(if(`blndok` = 3, `unit`, 0)) as `b3`,
                    sum(if(`blndok` = 4, `trans`, 0)) as `t4`,
                    sum(if(`blndok` = 4, `unit`, 0)) as `b4`,
                    sum(if(`blndok` = 5, `trans`, 0)) as `t5`,
                    sum(if(`blndok` = 5, `unit`, 0)) as `b5`,
                    sum(if(`blndok` = 6, `trans`, 0)) as `t6`,
                    sum(if(`blndok` = 6, `unit`, 0)) as `b6`,
                    sum(if(`blndok` = 7, `trans`, 0)) as `t7`,
                    sum(if(`blndok` = 7, `unit`, 0)) as `b7`,
                    sum(if(`blndok` = 8, `trans`, 0)) as `t8`,
                    sum(if(`blndok` = 8, `unit`, 0)) as `b8`,
                    sum(if(`blndok` = 9, `trans`, 0)) as `t9`,
                    sum(if(`blndok` = 9, `unit`, 0)) as `b9`,
                    sum(if(`blndok` = 10, `trans`, 0)) as `t10`,
                    sum(if(`blndok` = 10, `unit`, 0)) as `b10`,
                    sum(if(`blndok` = 11, `trans`, 0)) as `t11`,
                    sum(if(`blndok` = 11, `unit`, 0)) as `b11`,
                    sum(if(`blndok` = 12, `trans`, 0)) as `t12`,
                    sum(if(`blndok` = 12, `unit`, 0)) as `b12`,b.sub,b.urutan,".$id."
            from
            (
                select nocab, kode_comp,blndok, outlet, SUM(`".$unit."`) as unit, COUNT(DISTINCT(outlet)) as trans, kode,a.kodeprod
                FROM
                (
                    select nocab, kode_comp,blndok, `".$unit."`, CONCAT(KODE_COMP,kode_lang) as outlet,concat(kode_comp, nocab) as kode,kodeprod
                    from `data".$year."`.`fi`
                    where`kodeprod` in (".$code.") and `nodokjdi` <> 'XXXXXX' ".$daftar_dp."
                    union all
                    select nocab, kode_comp, blndok, `".$unit."`, null,concat(kode_comp, nocab) as kode,kodeprod
                    from `data".$year."`.`ri`
                    where`kodeprod` in (".$code.") and `nodokjdi` <> 'XXXXXX' ".$daftar_dp."
                )a
                group by kode, $groupbyx blndok                                 
            )a LEFT JOIN
            (
                    select  concat(kode_comp, nocab) as kode,naper,kode_comp, nama_comp, sub,urutan,`status`,status_cluster,branch_name
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
            )d on c.grup = d.kode_group
            group by a.kode $groupbyy
            ";
        
            $sql_insert = $this->db->query($sql);
            
            // echo "<pre>";
            // print_r($sql);
            // echo "</pre>";
            
            if ($sql_insert == true) {
                $sql_subtotal = "
                insert into mpm.soprod_new
                select '','', a.branch_name,b.nama_comp, a.kodeprod,a.namaprod, a.group, a.nama_group,
                        sum(t1),sum(b1),sum(t2),sum(b2),sum(t3),sum(b3),
                        sum(t4),sum(b4),sum(t5),sum(b5),sum(t6),sum(b6),
                        sum(t7),sum(b7),sum(t8),sum(b8),sum(t9),sum(b9),
                        sum(t10),sum(b10),sum(t11),sum(b11),sum(t12),sum(b12),b.sub,b.urutan,".$id."
                    from    mpm.soprod_new a inner JOIN
                    (
                        select  nama_comp, sub, urutan
                        from    mpm.tbl_tabcomp
                        where   `status` = 2
                    )b on a.sub = b.sub
                    where id = ".$id."
                    GROUP BY sub
                    ORDER BY a.sub
                ";

                $sql_subtotal_proses = $this->db->query($sql_subtotal);
                /*
                echo "<pre>";
                print_r($sql_subtotal);
                echo "</pre>";
                */
                if ($sql_subtotal_proses == TRUE ) {
                   
                    $sql_grandtotal = "
                    insert into mpm.soprod_new
                    select '','', '','Z_GRAND TOTAL','','','','',
                            sum(t1),sum(b1),sum(t2),sum(b2),sum(t3),sum(b3),
                            sum(t4),sum(b4),sum(t5),sum(b5),sum(t6),sum(b6),
                            sum(t7),sum(b7),sum(t8),sum(b8),sum(t9),sum(b9),
                            sum(t10),sum(b10),sum(t11),sum(b11),sum(t12),sum(b12),'',999,".$id."
                    from    mpm.soprod_new a INNER JOIN
                    (
                        select  kode_comp, urutan
                        from    mpm.tbl_tabcomp
                        where   `status` = 1
                        GROUP BY kode_comp
                    )b on a.kode_comp = b.kode_comp
                    where id = ".$id."
                    ";

                    $sql_grandtotal_proses = $this->db->query($sql_grandtotal);
                    /*
                    echo "<pre>";
                    print_r($sql_grandtotal);
                    echo "</pre>";
                    */
                } else {
                    echo "gagal dalam pembuatan grand total";
                }
                


            } else {
                echo "gagal dalam pembuatan sub total";
            }
            

            



        /* PROSES TAMPIL KE WEBSITE */
            //$this->db->join('tbl_tabcomp', 'tbl_tabcomp.naper = soprod_new.naper','inner');
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

    public function sales_per_product_per_class($dataSegment){
    	
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

            $year = $dataSegment['tahun'];
            $code = $dataSegment['code'];
            //echo "year : ".$year;
            //echo "<br />uv : ".$uv."<br>";

            $dp = $dataSegment['query'];
            echo "dp : ".$dp;
            if ($dp == NULL || $dp == '') {
            	$daftar_dp = '';
            } else {
            	$daftar_dp = 'and nocab in ('.$dp.')';
            	//echo " <br>daftar_dp : ".$daftar_dp;
            }
           
            //$daftar_dp = 'nocab in ('.'"'.$dp.'"'.')';
           
        /* ---------END DEFINISI VARIABEL---------------- */

        /* PROSES DELETE MPM.soprod_new */
            $query = "delete from mpm.soprod_class where id = ".$id."";
            $sql = $this->db->query($query);
            
            //echo "<pre>";
            //print_r($query);
            //echo "</pre>";            
        
        /* END PROSES DELETE MPM.OUTLET_NEW */

        /* PROSES INSERT KE MPM.soprod_new */
        
        	$sql="
            insert into mpm.soprod_class
select nocab, a.kode_comp, b.nama_comp, jenis, 
		t1, b1, t2, b2, t3, b3, t4, b4, t5, b5, t6, b6, 
		t7, b7, t8, b8, t9, b9, t10, b10, t11, b11, t12, b12,urutan,".$id."
FROM
(
	select 	nocab, kode_comp, jenis,
			sum(if(blndok = '01', trans, 0)) as t1,
			sum(if(blndok = '01', unit, 0)) as b1,
			sum(if(blndok = '02', trans, 0)) as t2,
			sum(if(blndok = '02', unit, 0)) as b2,
			sum(if(blndok = '03', trans, 0)) as t3,	
			sum(if(blndok = '03', unit, 0)) as b3,	
			sum(if(blndok = '04', trans, 0)) as t4,
			sum(if(blndok = '04', unit, 0)) as b4,
			sum(if(blndok = '05', trans, 0)) as t5,
			sum(if(blndok = '05', unit, 0)) as b5,
			sum(if(blndok = '06', trans, 0)) as t6,
			sum(if(blndok = '06', unit, 0)) as b6,
			sum(if(blndok = '07', trans, 0)) as t7,
			sum(if(blndok = '07', unit, 0)) as b7,
			sum(if(blndok = '08', trans, 0)) as t8,	
			sum(if(blndok = '08', unit, 0)) as b8,	
			sum(if(blndok = '09', trans, 0)) as t9,
			sum(if(blndok = '09', unit, 0)) as b9,
			sum(if(blndok = '10', trans, 0)) as t10,
			sum(if(blndok = '10', unit, 0)) as b10,
			sum(if(blndok = '11', trans, 0)) as t11,	
			sum(if(blndok = '11', unit, 0)) as b11,	
			sum(if(blndok = '12', trans, 0)) as t12,
			sum(if(blndok = '12', unit, 0)) as b12,
			kode, kodesalur
	from	
	(
			select 	nocab, kode_comp, blndok, unit, trans, kode, kodesalur, jenis
			from
			(
				select	nocab, kode_comp, blndok, outlet, unit, trans, a.kode, a.kodesalur, b.jenis
				from
				(
					select 	nocab, kode_comp,blndok, outlet, 
							SUM(`".$unit."`) as unit, COUNT(DISTINCT(outlet)) as trans, kode, kodesalur
					FROM
					(
						select 	nocab, kode_comp,blndok, `".$unit."`, 
								CONCAT(KODE_COMP,kode_lang) as outlet,
								concat(kode_comp, nocab) as kode, KODESALUR
						from 	`data".$year."`.`fi`
						where `kodeprod` in (".$code.") and `nodokjdi` <> 'XXXXXX' ".$daftar_dp."
						
						union all
						
						select 	nocab, kode_comp, blndok, `".$unit."`, 
								null,concat(kode_comp, nocab) as kode, KODESALUR
						from 	`data".$year."`.`ri`
						where   `kodeprod` in (".$code.") and `nodokjdi` <> 'XXXXXX' ".$daftar_dp."
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


        /* END PROSES DELETE MPM.soprod_NEW */

        
            // echo "<pre>";
            // print_r($sql);
            // echo "</pre>";
		
            $sql_insert = $this->db->query($sql);

        /* PROSES TAMPIL KE WEBSITE */
            //$this->db->join('tbl_tabcomp', 'tbl_tabcomp.naper = soprod_new.naper','inner');
            //$this->db->where("status = 1");

            $this->db->order_by('urutan','asc'); 
            $this->db->order_by('jenis','asc');           
            $this->db->where("soprod_class.id = ".'"'.$id.'"');
            $hasil = $this->db->get('soprod_class');       
            if ($hasil->num_rows() > 0) 
            {
                return $hasil->result();
            } else {
                return array();
            }
        /* END PROSES TAMPIL KE WEBSITE */

    }

    public function sales_per_product_per_class_current($dataSegment){
    	
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

            $year = $dataSegment['tahun'];
            $code = $dataSegment['code'];
            //echo "year : ".$year;
            //echo "<br />uv : ".$uv."<br>";

            $dp = $dataSegment['query'];
            echo "dp : ".$dp;
            if ($dp == NULL || $dp == '') {
            	$daftar_dp = '';
            } else {
            	$daftar_dp = 'and nocab in ('.$dp.')';
            	//echo " <br>daftar_dp : ".$daftar_dp;
            }
           
            //$daftar_dp = 'nocab in ('.'"'.$dp.'"'.')';
           
        /* ---------END DEFINISI VARIABEL---------------- */

        /* PROSES DELETE MPM.soprod_new */
            $query = "delete from mpm.soprod_class where id = ".$id."";
            $sql = $this->db->query($query);
            
            //echo "<pre>";
            //print_r($query);
            //echo "</pre>";            
        
        /* END PROSES DELETE MPM.OUTLET_NEW */

        /* PROSES INSERT KE MPM.soprod_new */
        
        	$sql="
            insert into mpm.soprod_class
            select 	nocab, a.kode_comp, b.nama_comp, jenis, 
                    t1, b1, t2, b2, t3, b3, t4, b4, t5, b5, t6, b6, 
                    t7, b7, t8, b8, t9, b9, t10, b10, t11, b11, t12, b12,urutan,".$id."
            FROM
            (
                select 	nocab, kode_comp, jenis,
                        sum(if(blndok = '01', trans, 0)) as t1,
                        sum(if(blndok = '01', unit, 0)) as b1,
                        sum(if(blndok = '02', trans, 0)) as t2,
                        sum(if(blndok = '02', unit, 0)) as b2,
                        sum(if(blndok = '03', trans, 0)) as t3,	
                        sum(if(blndok = '03', unit, 0)) as b3,	
                        sum(if(blndok = '04', trans, 0)) as t4,
                        sum(if(blndok = '04', unit, 0)) as b4,
                        sum(if(blndok = '05', trans, 0)) as t5,
                        sum(if(blndok = '05', unit, 0)) as b5,
                        sum(if(blndok = '06', trans, 0)) as t6,
                        sum(if(blndok = '06', unit, 0)) as b6,
                        sum(if(blndok = '07', trans, 0)) as t7,
                        sum(if(blndok = '07', unit, 0)) as b7,
                        sum(if(blndok = '08', trans, 0)) as t8,	
                        sum(if(blndok = '08', unit, 0)) as b8,	
                        sum(if(blndok = '09', trans, 0)) as t9,
                        sum(if(blndok = '09', unit, 0)) as b9,
                        sum(if(blndok = '10', trans, 0)) as t10,
                        sum(if(blndok = '10', unit, 0)) as b10,
                        sum(if(blndok = '11', trans, 0)) as t11,	
                        sum(if(blndok = '11', unit, 0)) as b11,	
                        sum(if(blndok = '12', trans, 0)) as t12,
                        sum(if(blndok = '12', unit, 0)) as b12,
                        kode, kodesalur
                FROM
                (
                    select a.nocab,a.kode_comp,a.kode,outlet,unit, trans, blndok, b.kodesalur, c.jenis
                    FROM
                    (
                        select 	nocab, kode_comp,blndok, outlet, 
                                SUM(`".$unit."`) as unit, COUNT(DISTINCT(outlet)) as trans, kode
                        FROM
                        (
                            select 	nocab, kode_comp,blndok, `".$unit."`, 
                                    CONCAT(KODE_COMP,kode_lang) as outlet,
                                    concat(kode_comp, nocab) as kode, KODESALUR
                            from 	`data".$year."`.`fi`
                            where `kodeprod` in (".$code.") and `nodokjdi` <> 'XXXXXX' ".$daftar_dp."                            
                            union all
                            select 	nocab, kode_comp, blndok, `".$unit."`, 
                                    null,concat(kode_comp, nocab) as kode, KODESALUR
                            from 	`data".$year."`.`ri`
                            where   `kodeprod` in (".$code.") and `nodokjdi` <> 'XXXXXX' ".$daftar_dp."
                        )a
                        group by kode, outlet, blndok     
                    )a LEFT JOIN
                    (
                        select kode, nama_lang, kodesalur
                        from db_lang.tbl_bantu_pelanggan_$year a
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


        /* END PROSES DELETE MPM.soprod_NEW */

        
            // echo "<pre>";
            // print_r($sql);
            // echo "</pre>";
		
            $sql_insert = $this->db->query($sql);

        /* PROSES TAMPIL KE WEBSITE */
            //$this->db->join('tbl_tabcomp', 'tbl_tabcomp.naper = soprod_new.naper','inner');
            //$this->db->where("status = 1");

            $this->db->order_by('urutan','asc'); 
            $this->db->order_by('jenis','asc');           
            $this->db->where("soprod_class.id = ".'"'.$id.'"');
            $hasil = $this->db->get('soprod_class');       
            if ($hasil->num_rows() > 0) 
            {
                return $hasil->result();
            } else {
                return array();
            }
        /* END PROSES TAMPIL KE WEBSITE */

    }

    public function ot_per_class($dataSegment){
        
        $user=$this->session->userdata('username');
        if ( function_exists( 'date_default_timezone_set' ) )
        date_default_timezone_set('Asia/Jakarta');        

        /* ---------DEFINISI VARIABEL----------------- */
        
            $tgl_created='"'.date('Y-m-d H:i:s').'"';        
            $id=$this->session->userdata('id');
           
            $dp = $dataSegment['query'];
            if ($dp == NULL || $dp == '') {
                $daftar_dp = '';
            } else {
                $daftar_dp = 'and nocab in ('.$dp.')';
                //echo " <br>daftar_dp : ".$daftar_dp;
            }
            $start_tahun = $dataSegment['start_year'];
            $start_bulan = $dataSegment['start_month'];
            $end_tahun = $dataSegment['end_year'];
            $end_bulan = $dataSegment['end_month'];
            $code = $dataSegment['code'];

            if ($start_tahun <> $end_tahun && $end_tahun - $start_tahun == 1)
            {
                $kondisi = "1";
                $end_bulan_modif = '12';
                $start_bulan_modif = '1';

                if ($start_bulan == $end_bulan_modif) {
                    $bulan_1 = $end_bulan_modif;
                }else{
                    for ($i=(int)$start_bulan; $i<(int)$end_bulan_modif ; $i++) { 
                        $x[] = $i.',';
                    }$bulan_1 = implode($x).(int)$end_bulan_modif;
                }                
                //echo "bulan_1 : ".$bulan_1;
                
                if ($end_bulan == $start_bulan_modif) {
                    $bulan_2 = $start_bulan_modif;
                }else{
                    for ($i=(int)$start_bulan_modif; $i<(int)$end_bulan ; $i++) { 
                        $y[] = $i.',';
                    }
                    $bulan_2 = implode($y).(int)$end_bulan;
                }

                
                //echo "bulan_2 : ".$bulan_2;

            }elseif($start_tahun == $end_tahun && $end_bulan > $start_bulan){
                $kondisi = "2";

                for ($i=(int)$start_bulan; $i<(int)$end_bulan ; $i++) {                     
                    $x[] = $i.',';
                }
                $bulan = implode($x).(int)$end_bulan;
                //echo "bulan : ".$bulan;
            }elseif($start_tahun == $end_tahun && $end_bulan = $start_bulan)
            {
                $kondisi = "2";
                $bulan = $end_bulan;
                //echo "bulan : ".$bulan;
            }else{
                //echo "Saat ini ketentuan Maksimal selisih <strong>Start Date dan End Date</strong> adalah 1 tahun";
                redirect('all_sales/error', refresh);
            }

        /* ---------END DEFINISI VARIABEL---------------- */

        /* PROSES DELETE MPM.tbl_ot_class */
            $query = "delete from mpm.tbl_ot_class where id = ".$id."";
            /*
            echo "<pre>";
            print_r($query);
            echo "</pre>";
            */
            $sql = $this->db->query($query);

        if ($kondisi == '1') {

            $sql="  insert into mpm.tbl_ot_class         
                    select a.kode_comp, b.nama_comp, kodesalur, c.jenis, count(distinct(outlet)) as ytd,urutan,$id
                    FROM
                    (
                        select  nocab, kode_comp,blndok, 
                                CONCAT(KODE_COMP,kode_lang) as outlet,
                                concat(kode_comp, nocab) as kode, KODESALUR
                        from    `data".$start_tahun."`.`fi`
                        where   `kodeprod` in (".$code.") and 
                                `nodokjdi` <> 'XXXXXX' ".$daftar_dp." and
                                blndok in ($bulan_1)
                        
                        union all

                        select  nocab, kode_comp,blndok, 
                                CONCAT(KODE_COMP,kode_lang) as outlet,
                                concat(kode_comp, nocab) as kode, KODESALUR
                        from    `data".$end_tahun."`.`fi`
                        where   `kodeprod` in (".$code.") and 
                                `nodokjdi` <> 'XXXXXX' ".$daftar_dp." and
                                blndok in ($bulan_2)

                    )a LEFT JOIN
                    (
                        select kode_comp,nama_comp,urutan
                        from mpm.tbl_tabcomp
                        where `status` = 1
                        GROUP BY kode_comp
                    )b on a.kode_comp = b.kode_comp
                    LEFT JOIN
                    (
                        select kode, jenis
                        from mpm.tbl_tabsalur
                    )c on a.kodesalur = c.kode
                    GROUP BY kode_comp, a.kodesalur
                    ORDER BY urutan asc     
                    ";
        /* END PROSES DELETE MPM.soprod_NEW */
            
            // echo "<pre>";
            // print($sql);
            // echo "</pre>";
            
            $sql_insert = $this->db->query($sql);
            

        }elseif($kondisi == '2'){

            $sql="  insert into mpm.tbl_ot_class
                    select a.kode_comp, b.nama_comp, kodesalur, c.jenis, count(distinct(outlet)) as ytd,urutan,$id
                    FROM
                    (
                        select  nocab, kode_comp,blndok, 
                                CONCAT(KODE_COMP,kode_lang) as outlet,
                                concat(kode_comp, nocab) as kode, KODESALUR
                        from    `data".$start_tahun."`.`fi`
                        where   `kodeprod` in (".$code.") and 
                                `nodokjdi` <> 'XXXXXX' ".$daftar_dp." and
                                blndok in ($bulan)
                    )a LEFT JOIN
                    (
                        select kode_comp,nama_comp,urutan
                        from mpm.tbl_tabcomp
                        where `status` = 1
                        GROUP BY kode_comp
                    )b on a.kode_comp = b.kode_comp
                    LEFT JOIN
                    (
                        select kode, jenis
                        from mpm.tbl_tabsalur
                    )c on a.kodesalur = c.kode
                    GROUP BY kode_comp, a.kodesalur
                    ORDER BY urutan asc     
                    ";
        /* END PROSES DELETE MPM.soprod_NEW */
            
            // echo "<pre>";
            // print($sql);
            // echo "</pre>";
            $sql_insert = $this->db->query($sql);
        }
            

        /* PROSES TAMPIL KE WEBSITE */

            $this->db->order_by('urutan','asc');        
            $this->db->where("tbl_ot_class.id = ".'"'.$id.'"');
            $hasil = $this->db->get('tbl_ot_class');       
            if ($hasil->num_rows() > 0) 
            {
                return $hasil->result();
            } else {
                return array();
            }
        /* END PROSES TAMPIL KE WEBSITE */

    }

    public function ot_per_type($dataSegment){
        
        $user=$this->session->userdata('username');
        if ( function_exists( 'date_default_timezone_set' ) )
        date_default_timezone_set('Asia/Jakarta');        

        /* ---------DEFINISI VARIABEL----------------- */
        
            $tgl_created='"'.date('Y-m-d H:i:s').'"';        
            $id=$this->session->userdata('id');
           
            $dp = $dataSegment['query'];
            if ($dp == NULL || $dp == '') {
                $daftar_dp = '';
            } else {
                $daftar_dp = 'and nocab in ('.$dp.')';
                //echo " <br>daftar_dp : ".$daftar_dp;
            }
            $start_tahun = $dataSegment['start_year'];
            $start_bulan = $dataSegment['start_month'];
            $end_tahun = $dataSegment['end_year'];
            $end_bulan = $dataSegment['end_month'];
            $code = $dataSegment['code'];

            if ($start_tahun <> $end_tahun && $end_tahun - $start_tahun == 1)
            {
                $kondisi = "1";
                $end_bulan_modif = '12';
                $start_bulan_modif = '1';

                if ($start_bulan == $end_bulan_modif) {
                    $bulan_1 = $end_bulan_modif;
                }else{
                    for ($i=(int)$start_bulan; $i<(int)$end_bulan_modif ; $i++) { 
                        $x[] = $i.',';
                    }$bulan_1 = implode($x).(int)$end_bulan_modif;
                }                
                //echo "bulan_1 : ".$bulan_1;
                
                if ($end_bulan == $start_bulan_modif) {
                    $bulan_2 = $start_bulan_modif;
                }else{
                    for ($i=(int)$start_bulan_modif; $i<(int)$end_bulan ; $i++) { 
                        $y[] = $i.',';
                    }
                    $bulan_2 = implode($y).(int)$end_bulan;
                }

                
                //echo "bulan_2 : ".$bulan_2;

            }elseif($start_tahun == $end_tahun && $end_bulan > $start_bulan){
                $kondisi = "2";

                for ($i=(int)$start_bulan; $i<(int)$end_bulan ; $i++) {                     
                    $x[] = $i.',';
                }
                $bulan = implode($x).(int)$end_bulan;
                //echo "bulan : ".$bulan;
            }elseif($start_tahun == $end_tahun && $end_bulan = $start_bulan)
            {
                $kondisi = "2";
                $bulan = $end_bulan;
                //echo "bulan : ".$bulan;
            }else{
                //echo "Saat ini ketentuan Maksimal selisih <strong>Start Date dan End Date</strong> adalah 1 tahun";
                redirect('all_sales/error', refresh);
            }

        /* ---------END DEFINISI VARIABEL---------------- */

        /* PROSES DELETE MPM.tbl_ot_class */
            $query = "delete from mpm.tbl_ot_class where id = ".$id."";
            /*
            echo "<pre>";
            print_r($query);
            echo "</pre>";
            */
            $sql = $this->db->query($query);

        if ($kondisi == '1') {

            $sql="  insert into mpm.tbl_ot_class         
                    select a.kode_comp, b.nama_comp, '',c.sektor, count(distinct(outlet)) as ytd,urutan,$id
                    FROM
                    (
                        select  nocab, kode_comp,blndok, 
                                CONCAT(KODE_COMP,kode_lang) as outlet,
                                concat(kode_comp, nocab) as kode, KODE_TYPE
                        from    `data".$start_tahun."`.`fi`
                        where   `kodeprod` in (".$code.") and 
                                `nodokjdi` <> 'XXXXXX' ".$daftar_dp." and
                                blndok in ($bulan_1)
                        
                        union all

                        select  nocab, kode_comp,blndok, 
                                CONCAT(KODE_COMP,kode_lang) as outlet,
                                concat(kode_comp, nocab) as kode, KODE_TYPE
                        from    `data".$end_tahun."`.`fi`
                        where   `kodeprod` in (".$code.") and 
                                `nodokjdi` <> 'XXXXXX' ".$daftar_dp." and
                                blndok in ($bulan_2)

                    )a LEFT JOIN
                    (
                        select kode_comp,nama_comp,urutan
                        from mpm.tbl_tabcomp
                        where `status` = 1
                        GROUP BY kode_comp
                    )b on a.kode_comp = b.kode_comp
                    LEFT JOIN
                    (
                        select a.kode_type, a.nama_type, a.sektor
                        from mpm.tbl_bantu_type a
                    )c on a.KODE_TYPE = c.kode_type
                    GROUP BY kode_comp, c.sektor
                    ORDER BY urutan asc, c.sektor     
                    ";
        /* END PROSES DELETE MPM.soprod_NEW */
            
            // echo "<pre>";
            // print($sql);
            // echo "</pre>";
            
            $sql_insert = $this->db->query($sql);
            

        }elseif($kondisi == '2'){

            $sql="  insert into mpm.tbl_ot_class
                    select a.kode_comp, b.nama_comp, '',c.sektor, count(distinct(outlet)) as ytd,urutan,$id
                    FROM
                    (
                        select  nocab, kode_comp,blndok, 
                                CONCAT(KODE_COMP,kode_lang) as outlet,
                                concat(kode_comp, nocab) as kode, KODE_TYPE
                        from    `data".$start_tahun."`.`fi`
                        where   `kodeprod` in (".$code.") and 
                                `nodokjdi` <> 'XXXXXX' ".$daftar_dp." and
                                blndok in ($bulan)
                    )a LEFT JOIN
                    (
                        select kode_comp,nama_comp,urutan
                        from mpm.tbl_tabcomp
                        where `status` = 1
                        GROUP BY kode_comp
                    )b on a.kode_comp = b.kode_comp
                    LEFT JOIN
                    (
                        select a.kode_type, a.nama_type, a.sektor
                        from mpm.tbl_bantu_type a
                    )c on a.KODE_TYPE = c.kode_type
                    GROUP BY kode_comp, c.sektor
                    ORDER BY urutan asc, c.sektor     
                    ";
        /* END PROSES DELETE MPM.soprod_NEW */
            
            // echo "<pre>";
            // print($sql);
            // echo "</pre>";
            $sql_insert = $this->db->query($sql);
        }
            

        /* PROSES TAMPIL KE WEBSITE */

            $this->db->order_by('urutan','asc');        
            $this->db->where("tbl_ot_class.id = ".'"'.$id.'"');
            $hasil = $this->db->get('tbl_ot_class');       
            if ($hasil->num_rows() > 0) 
            {
                return $hasil->result();
            } else {
                return array();
            }
        /* END PROSES TAMPIL KE WEBSITE */

    }

    public function ot($dataSegment){
        
        $user=$this->session->userdata('username');
        if ( function_exists( 'date_default_timezone_set' ) )
        date_default_timezone_set('Asia/Jakarta');        

        /* ---------DEFINISI VARIABEL----------------- */
        
            $tgl_created='"'.date('Y-m-d H:i:s').'"';        
            $id=$this->session->userdata('id');
           
            $dp = $dataSegment['query'];
            if ($dp == NULL || $dp == '') {
                $daftar_dp = '';
            } else {
                $daftar_dp = 'and nocab in ('.$dp.')';
                //echo " <br>daftar_dp : ".$daftar_dp;
            }
            $start_tahun = $dataSegment['start_year'];
            $start_bulan = $dataSegment['start_month'];
            $end_tahun = $dataSegment['end_year'];
            $end_bulan = $dataSegment['end_month'];
            $code = $dataSegment['code'];

            if ($start_tahun <> $end_tahun && $end_tahun - $start_tahun == 1)
            {
                $kondisi = "1";
                $end_bulan_modif = '12';
                $start_bulan_modif = '1';

                if ($start_bulan == $end_bulan_modif) {
                    $bulan_1 = $end_bulan_modif;
                }else{
                    for ($i=(int)$start_bulan; $i<(int)$end_bulan_modif ; $i++) { 
                        $x[] = $i.',';
                    }$bulan_1 = implode($x).(int)$end_bulan_modif;
                }                
                //echo "bulan_1 : ".$bulan_1;
                
                if ($end_bulan == $start_bulan_modif) {
                    $bulan_2 = $start_bulan_modif;
                }else{
                    for ($i=(int)$start_bulan_modif; $i<(int)$end_bulan ; $i++) { 
                        $y[] = $i.',';
                    }
                    $bulan_2 = implode($y).(int)$end_bulan;
                }

                
                //echo "bulan_2 : ".$bulan_2;

            }elseif($start_tahun == $end_tahun && $end_bulan > $start_bulan){
                $kondisi = "2";

                for ($i=(int)$start_bulan; $i<(int)$end_bulan ; $i++) {                     
                    $x[] = $i.',';
                }
                $bulan = implode($x).(int)$end_bulan;
                //echo "bulan : ".$bulan;
            }elseif($start_tahun == $end_tahun && $end_bulan = $start_bulan)
            {
                $kondisi = "2";
                $bulan = $end_bulan;
                //echo "bulan : ".$bulan;
            }else{
                //echo "Saat ini ketentuan Maksimal selisih <strong>Start Date dan End Date</strong> adalah 1 tahun";
                redirect('all_sales/error', refresh);
            }

        /* ---------END DEFINISI VARIABEL---------------- */

        /* PROSES DELETE MPM.tbl_ot_class */
            $query = "delete from mpm.tbl_ot_class where id = ".$id."";
            /*
            echo "<pre>";
            print_r($query);
            echo "</pre>";
            */
            $sql = $this->db->query($query);

        if ($kondisi == '1') {

            $sql="  insert into mpm.tbl_ot_class         
                    select a.kode_comp, b.nama_comp, '','', count(distinct(outlet)) as ytd,urutan,$id
                    FROM
                    (
                        select  nocab, kode_comp,blndok, 
                                CONCAT(KODE_COMP,kode_lang) as outlet,
                                concat(kode_comp, nocab) as kode
                        from    `data".$start_tahun."`.`fi`
                        where   `kodeprod` in (".$code.") and 
                                `nodokjdi` <> 'XXXXXX' ".$daftar_dp." and
                                blndok in ($bulan_1)
                        
                        union all

                        select  nocab, kode_comp,blndok, 
                                CONCAT(KODE_COMP,kode_lang) as outlet,
                                concat(kode_comp, nocab) as kode
                        from    `data".$end_tahun."`.`fi`
                        where   `kodeprod` in (".$code.") and 
                                `nodokjdi` <> 'XXXXXX' ".$daftar_dp." and
                                blndok in ($bulan_2)

                    )a LEFT JOIN
                    (
                        select kode_comp,nama_comp,urutan
                        from mpm.tbl_tabcomp
                        where `status` = 1
                        GROUP BY kode_comp
                    )b on a.kode_comp = b.kode_comp
                    GROUP BY kode_comp
                    ORDER BY urutan asc     
                    ";
        /* END PROSES DELETE MPM.soprod_NEW */
            
            // echo "<pre>";
            // print($sql);
            // echo "</pre>";
            
            $sql_insert = $this->db->query($sql);
            

        }elseif($kondisi == '2'){

            $sql="  insert into mpm.tbl_ot_class
                    select a.kode_comp, b.nama_comp, '','', count(distinct(outlet)) as ytd,urutan,$id
                    FROM
                    (
                        select  nocab, kode_comp,blndok, 
                                CONCAT(KODE_COMP,kode_lang) as outlet,
                                concat(kode_comp, nocab) as kode
                        from    `data".$start_tahun."`.`fi`
                        where   `kodeprod` in (".$code.") and 
                                `nodokjdi` <> 'XXXXXX' ".$daftar_dp." and
                                blndok in ($bulan)
                    )a LEFT JOIN
                    (
                        select kode_comp,nama_comp,urutan
                        from mpm.tbl_tabcomp
                        where `status` = 1
                        GROUP BY kode_comp
                    )b on a.kode_comp = b.kode_comp
                    GROUP BY kode_comp
                    ORDER BY urutan asc    
                    ";
        /* END PROSES DELETE MPM.soprod_NEW */
            
            // echo "<pre>";
            // print($sql);
            // echo "</pre>";
            $sql_insert = $this->db->query($sql);
        }
            

        /* PROSES TAMPIL KE WEBSITE */

            $this->db->order_by('urutan','asc');        
            $this->db->where("tbl_ot_class.id = ".'"'.$id.'"');
            $hasil = $this->db->get('tbl_ot_class');       
            if ($hasil->num_rows() > 0) 
            {
                return $hasil->result();
            } else {
                return array();
            }
        /* END PROSES TAMPIL KE WEBSITE */

    }

    public function sell_in($data){
        
        $user=$this->session->userdata('username');
        if ( function_exists( 'date_default_timezone_set' ) )
        date_default_timezone_set('Asia/Jakarta');        

        /* ---------DEFINISI VARIABEL----------------- */
        
            $tgl_created='"'.date('Y-m-d H:i:s').'"';        
            $id=$this->session->userdata('id');
            
            $year = $data['year'];
            $nocab = $data['nocab'];
            $uv = $data['uv'];
/*
            echo "<pre>";
            echo "year : ".$year."<br>";
            echo "nocab : ".$nocab."<br>";
            echo "uv : ".$uv."<br>";
            echo "</pre>";          
*/
            if ($uv == '0') {
                 $unit = 'banyak';
             } else {
                 $unit = 'jumlah';
             }
              

        /* PROSES DELETE MPM.tbl_ot_class */
            $query = "delete from mpm.tbl_sell_in where id = ".$id."";
            $sql = $this->db->query($query);

            $query_insert = "
                insert into mpm.tbl_sell_in
                select  a.nocab, b.nama_comp, a.kodeprod, c.namaprod,
                        sum(if(bulan =  1 and hrdok < 7 , banyak,0)) as  b1_a, 
                        sum(if(bulan =  1 and (hrdok > 7 and hrdok < 15), banyak,0)) as  b1_b,
                        sum(if(bulan =  1 and (hrdok > 15 and hrdok < 22), banyak,0)) as  b1_c,
                        sum(if(bulan =  1 and hrdok > 22, banyak,0)) as  b1_d,
                        sum(if(bulan =  2 and hrdok < 7 , banyak,0)) as  b2_a, 
                        sum(if(bulan =  2 and (hrdok > 7 and hrdok < 15), banyak,0)) as  b2_b,
                        sum(if(bulan =  2 and (hrdok > 15 and hrdok < 22), banyak,0)) as  b2_c,
                        sum(if(bulan =  2 and hrdok > 22, banyak,0)) as  b2_d,
                        sum(if(bulan =  3 and hrdok < 7 , banyak,0)) as  b3_a, 
                        sum(if(bulan =  3 and (hrdok > 7 and hrdok < 15), banyak,0)) as  b3_b,
                        sum(if(bulan =  3 and (hrdok > 15 and hrdok < 22), banyak,0)) as  b3_c,
                        sum(if(bulan =  3 and hrdok > 22, banyak,0)) as  b3_d,
                        sum(if(bulan =  4 and hrdok < 7 , banyak,0)) as  b4_a, 
                        sum(if(bulan =  4 and (hrdok > 7 and hrdok < 15), banyak,0)) as  b4_b,
                        sum(if(bulan =  4 and (hrdok > 15 and hrdok < 22), banyak,0)) as  b4_c,
                        sum(if(bulan =  4 and hrdok > 22, banyak,0)) as  b4_d,
                        sum(if(bulan =  5 and hrdok < 7 , banyak,0)) as  b5_a, 
                        sum(if(bulan =  5 and (hrdok > 7 and hrdok < 15), banyak,0)) as  b5_b,
                        sum(if(bulan =  5 and (hrdok > 15 and hrdok < 22), banyak,0)) as  b5_c,
                        sum(if(bulan =  5 and hrdok > 22, banyak,0)) as  b5_d,
                        sum(if(bulan =  6 and hrdok < 7 , banyak,0)) as  b6_a, 
                        sum(if(bulan =  6 and (hrdok > 7 and hrdok < 15), banyak,0)) as  b6_b,
                        sum(if(bulan =  6 and (hrdok > 15 and hrdok < 22), banyak,0)) as  b6_c,
                        sum(if(bulan =  6 and hrdok > 22, banyak,0)) as  b6_d,
                        sum(if(bulan =  7 and hrdok < 7 , banyak,0)) as  b7_a, 
                        sum(if(bulan =  7 and (hrdok > 7 and hrdok < 15), banyak,0)) as  b7_b,
                        sum(if(bulan =  7 and (hrdok > 15 and hrdok < 22), banyak,0)) as  b7_c,
                        sum(if(bulan =  7 and hrdok > 22, banyak,0)) as  b7_d,
                        sum(if(bulan =  8 and hrdok < 7 , banyak,0)) as  b8_a, 
                        sum(if(bulan =  8 and (hrdok > 7 and hrdok < 15), banyak,0)) as  b8_b,
                        sum(if(bulan =  8 and (hrdok > 15 and hrdok < 22), banyak,0)) as  b8_c,
                        sum(if(bulan =  8 and hrdok > 22, banyak,0)) as  b8_d,
                        sum(if(bulan =  9 and hrdok < 7 , banyak,0)) as  b9_a, 
                        sum(if(bulan =  9 and (hrdok > 7 and hrdok < 15), banyak,0)) as  b9_b,
                        sum(if(bulan =  9 and (hrdok > 15 and hrdok < 22), banyak,0)) as  b9_c,
                        sum(if(bulan =  9 and hrdok > 22, banyak,0)) as  b9_d,
                        sum(if(bulan =  10 and hrdok < 7 , banyak,0)) as  b10_a, 
                        sum(if(bulan =  10 and (hrdok > 7 and hrdok < 15), banyak,0)) as  b10_b,
                        sum(if(bulan =  10 and (hrdok > 15 and hrdok < 22), banyak,0)) as  b10_c,
                        sum(if(bulan =  10 and hrdok > 22, banyak,0)) as  b10_d,
                        sum(if(bulan =  11 and hrdok < 7 , banyak,0)) as  b11_a, 
                        sum(if(bulan =  11 and (hrdok > 7 and hrdok < 15), banyak,0)) as  b11_b,
                        sum(if(bulan =  11 and (hrdok > 15 and hrdok < 22), banyak,0)) as  b11_c,
                        sum(if(bulan =  11 and hrdok > 22, banyak,0)) as  b11_d,
                        sum(if(bulan =  12 and hrdok < 7 , banyak,0)) as  b12_a, 
                        sum(if(bulan =  12 and (hrdok > 7 and hrdok < 15), banyak,0)) as  b12_b,
                        sum(if(bulan =  12 and (hrdok > 15 and hrdok < 22), banyak,0)) as  b12_c,
                        sum(if(bulan =  12 and hrdok > 22, banyak,0)) as  b12_d, $id
                FROM
                (
                    select nocab, bulan, kodeprod,hrdok, banyak
                    from data$year.th a INNER JOIN 
                            (                               
                                select NODOKJDI, KODEPROD, `$unit` as BANYAK, JUMLAH, HRDOK
                                from data$year.ti
                                where nocab = '$nocab'
                            )b on a.NODOKJDI = b.NODOKJDI
                    where a.nocab = '$nocab'
                    ORDER BY kodeprod, bulan asc, hrdok asc
                )a LEFT JOIN
                (
                    select nocab, nama_comp
                    from mpm.tbl_tabcomp
                    where `status` = 1
                    GROUP BY nocab
                )b on a.nocab = b.nocab
                LEFT JOIN
                (
                    select kodeprod, namaprod
                    from mpm.tabprod
                )c on a.kodeprod = c.kodeprod
                GROUP BY kodeprod
            ";
            /*
            echo "<pre>";
            print($query_insert);
            echo "</pre>";
            */
            $sql_insert = $this->db->query($query_insert);

        /* PROSES TAMPIL KE WEBSITE */

            //$this->db->order_by('urutan','asc');        
            $this->db->where("tbl_sell_in.id = ".'"'.$id.'"');
            $hasil = $this->db->get('tbl_sell_in');       
            if ($hasil->num_rows() > 0) 
            {
                return $hasil->result();
            } else {
                return array();
            }
        /* END PROSES TAMPIL KE WEBSITE */

    }

    public function sell_out_nasional($data){
        
        $user=$this->session->userdata('username');
        if ( function_exists( 'date_default_timezone_set' ) )
        date_default_timezone_set('Asia/Jakarta');        

        /* ---------DEFINISI VARIABEL----------------- */
        
            $tgl_created='"'.date('Y-m-d H:i:s').'"';        
            $id=$this->session->userdata('id');
            
            $year = $data['year'];
            $bulan = $data['bulan'];
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
            

        /* PROSES DELETE MPM.tbl_ot_class */
            $query = "delete from mpm.tbl_sell_out_nasional where id = ".$id."";
            $sql = $this->db->query($query);

            $query_insert = "
                insert into mpm.tbl_sell_out_nasional
                select a.kodeprod, b.namaprod, sum(banyak) as unit, sum(tot1) as `value`, BULAN, $id
                from
                (
                            SELECT kodeprod, banyak, tot1, bulan
                            from data$year.fi
                            where bulan in ($bulan)
                            union ALL
                            SELECT kodeprod, banyak, tot1, bulan
                            from data$year.ri
                            where bulan in ($bulan)
                )a INNER JOIN 
                (
                    SELECT kodeprod, namaprod
                    from mpm.tabprod
                    where supp = '$supp'
                    $tampil_group
                )b on a.kodeprod = b.kodeprod
                GROUP BY kodeprod, bulan
                union all
                select '', 'Grand Total', sum(banyak) as unit, sum(tot1) as `value`, BULAN, $id
                from
                (
                            SELECT kodeprod, banyak, tot1, bulan
                            from data$year.fi
                            where bulan in ($bulan)
                            union ALL
                            SELECT kodeprod, banyak, tot1, bulan
                            from data$year.ri
                            where bulan in ($bulan)
                )a INNER JOIN 
                (
                    SELECT kodeprod, namaprod
                    from mpm.tabprod
                    where supp = '$supp'
                    $tampil_group
                )b on a.kodeprod = b.kodeprod
            ";
          
            // echo "<pre>";
            // print($query);
            // print($query_insert);
            // echo "</pre>";
            
            $sql_insert = $this->db->query($query_insert);

        /* PROSES TAMPIL KE WEBSITE */

            //$this->db->order_by('urutan','asc');        
            $this->db->where("tbl_sell_out_nasional.id = ".'"'.$id.'"');
            $hasil = $this->db->get('tbl_sell_out_nasional');       
            if ($hasil->num_rows() > 0) 
            {
                return $hasil->result();
            } else {
                return array();
            }
        /* END PROSES TAMPIL KE WEBSITE */

    }

    public function sales_per_product_ob($data){
        
        $user=$this->session->userdata('username');
        if ( function_exists( 'date_default_timezone_set' ) )
        date_default_timezone_set('Asia/Jakarta');        

        /* ---------DEFINISI VARIABEL----------------- */
        
        $tgl_created='"'.date('Y-m-d H:i:s').'"';        
        $id=$this->session->userdata('id');

        $tahun = $data['tahun'];
        $code = $data['code'];
        $bulan = $data['bulan'];
        $kode_comp = $data['query'];
        $branch = $data['branch'];
        /*
        echo "tahun : ".$tahun;
        echo "<br>bulan : ".$bulan;
        echo "<br>branch : ".$branch;
        */
        if ($branch == 88) {
            $tabel_ob = 'mpm.m_sales_salsman_ob_jkt';
        }elseif ($branch == 91) {
            $tabel_ob = 'mpm.m_sales_salsman_ob_jtm';
        }elseif ($branch == 95) {
            $tabel_ob = 'mpm.m_sales_salsman_ob_jbr';
        }elseif ($branch == 89) {
            $tabel_ob = 'mpm.m_sales_salsman_ob_jtg';
        }elseif ($branch == 'j2') {
            $tabel_ob = 'mpm.m_sales_salsman_ob_jbl';
        }elseif ($branch == 27) {
            $tabel_ob = 'mpm.m_sales_salsman_ob_jts';
        }elseif ($branch == 98) {
            $tabel_ob = 'mpm.m_sales_salsman_ob_diy';
        }
        else {
            $tabel_ob = 'mpm.tbl_sales_per_product_ob';
        }
        
        
           
        /* ---------END DEFINISI VARIABEL---------------- */

        /* PROSES DELETE MPM.soprod_new */
            $query_a = "delete from mpm.tbl_ob where userid = ".$id."";
            $sql_a = $this->db->query($query_a);
            
            $query_b = "
                insert into mpm.tbl_ob
                select a.siteid, b.kode_comp,customerid,$id 
                from $tabel_ob a LEFT JOIN mpm.tbl_siteid b
                            on a.siteid = b.siteid
            ";

            $sql_b = $this->db->query($query_b);


        /* END PROSES DELETE MPM.OUTLET_NEW */

        /* PROSES DELETE MPM.soprod_new */
            $query = "delete from mpm.tbl_sales_per_product_ob where userid = ".$id."";
            $sql = $this->db->query($query);
            /*
            echo "<pre>";
            print_r($query_a);
            print_r($query_b);
            print_r($query);
            echo "</pre>";            
            */
        /* END PROSES DELETE MPM.OUTLET_NEW */

        /* PROSES INSERT KE MPM.soprod_new */
            
            $sql="
            insert into mpm.tbl_sales_per_product_ob
            select  nocab, a.kode_comp, nama_comp,$tahun, blndok, 
                    sum(trans) as ot,
                    sum(unit) as unit,
                    sum(`value`) as `value`,
                    sum(if(ob=1,trans,0)) as ot_ob,
                    sum(if(ob=1,unit,0)) as unit_ob, 
                    sum(if(ob=1,`value`,0)) as value_ob,
                    $id
            FROM
            (
                select nocab, kode_comp, blndok, a.outlet, unit, `value`,trans, kode, flag as ob
                FROM
                (
                        select nocab, kode_comp,blndok, outlet, SUM(`banyak`) as unit,sum(tot1) as `value`,  COUNT(DISTINCT(outlet)) as trans, kode
                        FROM
                        (
                                select nocab, kode_comp,blndok, `banyak`,tot1, CONCAT(KODE_COMP,kode_lang) as outlet,concat(kode_comp, nocab) as kode
                                from `data$tahun`.`fi`
                                where`kodeprod` in ($code) and `nodokjdi` <> 'XXXXXX' 
                                union all
                                select nocab, kode_comp, blndok, `banyak`, tot1, null,concat(kode_comp, nocab) as kode
                                from `data$tahun`.`ri`
                                where`kodeprod` in ($code) and `nodokjdi` <> 'XXXXXX' 
                        )a where kode_comp in ('$kode_comp') and blndok in ($bulan)
                        group by kode, blndok, outlet
                )a LEFT JOIN 
                (
                    select concat(kode_comp, kode_lang) as outlet, 1 as flag
                    from mpm.tbl_ob
                    where userid = $id
                )b on a.outlet = b.outlet
            )a LEFT JOIN (
                select kode_comp,urutan,nama_comp
                from mpm.tbl_tabcomp
                where `status` = 1
                GROUP BY kode_comp
            )b on a.kode_comp = b.kode_comp
            GROUP BY kode
            ORDER BY urutan asc
            
            ";
            
        /* END PROSES DELETE MPM.soprod_NEW */
            /*
            echo "<pre>";
            print_r($sql);
            echo "</pre>";
            */
            $sql_insert = $this->db->query($sql);

            if ($sql_insert == 1) {
                $sql_total = "
                    insert into mpm.tbl_sales_per_product_ob
                    select nocab, 'Z','TOTAL',tahun,bulan,sum(ot),sum(unit),sum(`value`),sum(ot_ob),sum(unit_ob),sum(value_ob),$id 
                    from mpm.tbl_sales_per_product_ob
                    where userid = '$id'
                ";
                $sql_insert_total = $this->db->query($sql_total);


            } else {
               echo "<pre>";
               echo "ada kesalahan proses Total";
               echo "</pre>";
            }
            


        /* PROSES TAMPIL KE WEBSITE */
            //$this->db->join('tbl_tabcomp', 'tbl_tabcomp.naper = soprod_new.naper','inner');
            //$this->db->where("status = 1");
            //$this->db->order_by('urutan','asc');           
            $this->db->where("mpm.tbl_sales_per_product_ob.userid = ".'"'.$id.'"');
            $hasil = $this->db->get('mpm.tbl_sales_per_product_ob');       
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