<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mpiutang extends CI_Model 
{    	
	function viewPiutang($data){
		if ( function_exists( 'date_default_timezone_set' ) )
        date_default_timezone_set('Asia/Jakarta');
		$dob1=trim($data['tanggal']);//$dob1='dd/mm/yyyy' format
        $tanggal=strftime('%Y-%m-%d',strtotime($dob1));

		$range = $data['range'];

		$awal=substr($tanggal,0,7).'-01';
		$year=substr($tanggal,2,2);
		$month=substr($tanggal,5,2);
		$bulan=$year.$month;

		// echo "<pre>";
		// echo "tanggal : ".$tanggal."<br>";
		// echo "awal : ".$awal."<br>";
		// echo "year : ".$year."<br>";
		// echo "month : ".$month."<br>";
		// echo "bulan : ".$bulan."<br>";
		// echo "range : ".$range;
		// echo "</pre>";

		$sql="
			select  kode_comp,grup_lang,group_descr grup_nama,saldoawal,debit,kredit,
					(saldoawal+debit-kredit) saldoakhir, current, duedate, 
					r1 ,r2 ,r3 ,r4, total, '$tanggal' as tanggal
			from
			(
					select  z.group_id grup_lang,z.group_descr,
							debitprev-if(isnull(kreditprev),0,kreditprev) saldoawal,
							if(isnull(debit),0,debit) debit,
							if(isnull(kredit),0,kredit) kredit,
							current,duedate,r1,r2,r3,r4,
							current+duedate+r1+r2+r3+r4 total
					from
					(   
						select  group_id, concat(group_descr,'') group_descr 
						from    dbsls.t_ar_ink_master a inner join dbsls.m_customer b using(customerid) 
						group by group_id
					)z                                            
					left join         
					(
						select  group_id, group_descr, 
								sum(if(retur=1,dokument*-1,dokument)) debitprev 
						from    dbsls.t_ar_ink_master a inner join dbsls.m_customer b using(customerid)
						where   tanggal<'".$awal."'
						group by group_id
					)a using(group_id)
					left join 
					(
						select  group_id, group_descr, 
								sum(bayar_transfer+bayar_giro+bayar_tunai) kreditprev 
						from    dbsls.t_ar_ink_detail a inner join dbsls.m_customer b using(customerid)
						where   tgl_transfer<'".$awal."'
						group by group_id
					)b using(group_id)
					left join 
					(
						select  group_id, group_descr, 
								sum(if(retur=1,dokument*-1,dokument)) debit 
						from    dbsls.t_ar_ink_master a inner join dbsls.m_customer b using(customerid)
						where   tanggal>='".$awal."' and tanggal<='".$tanggal."'
						group by group_id
					)c using (group_id)
					left join 
					(
						select  group_id, group_descr, 
								if(isnull(sum(bayar_transfer+bayar_giro+bayar_tunai)),0,sum(bayar_transfer+bayar_giro+bayar_tunai)) kredit 
						from    dbsls.t_ar_ink_detail a inner join dbsls.m_customer b using(customerid)
						where   tgl_transfer>='".$awal."' and tgl_transfer<='".$tanggal."'
						group by group_id
					)d using(group_id)
					left join
					(
						select  group_id, group_descr,
								SUM(IF(days_past_due < 0, amount_due, 0)) current,
								SUM(IF(days_past_due = 0, amount_due, 0)) duedate,
								SUM(IF(days_past_due BETWEEN 1 AND 30, amount_due, 0)) r1,
								SUM(IF(days_past_due BETWEEN 31 AND 60, amount_due, 0)) r2,
								SUM(IF(days_past_due BETWEEN 61 AND 90, amount_due, 0)) r3,
								SUM(IF(days_past_due > 90, amount_due, 0))r4
						FROM
						(
							SELECT  group_id, group_descr, 
									datediff('".$tanggal."',tgl_tempo) days_past_due, 
									sum(saldo) amount_due 
							FROM
							(
								SELECT  no_sales, a.customerid, tgl_tempo,
										(IF(a.retur = 1, a.dokument *- 1, a.dokument )) - IF(isnull(kredit), 0, kredit) saldo
								FROM    dbsls.t_ar_ink_master a LEFT JOIN 
								(
										SELECT  no_sales,customerid, 
												sum(bayar_transfer + bayar_giro + bayar_tunai) kredit 
										FROM    dbsls.t_ar_ink_detail 
										where tgl_transfer <='".$tanggal."'
										GROUP BY no_sales 
                            	) b USING (no_sales) 
								where   a.tanggal<='".$tanggal."' 
								GROUP BY no_sales
           					)a1 inner join dbsls.m_customer b1 using (customerid) 
            				WHERE saldo <> 0
							GROUP BY group_id,days_past_due
						)a group by group_id 
					)e using (group_id)
			)a
			LEFT JOIN mpm.tabcomp tab 
				ON a.grup_lang = tab.kode_lang
			where saldoawal+debit-kredit<>0 group by a.grup_lang
			order by a.group_descr
		";

		$query = $this->db->query($sql);
		
		// echo "<pre>";
		// print_r($sql);
		// print_r($query);
		// echo "</pre>";

		if ($query->num_rows() > 0) 
		{
			return $query->result();
		} else {
			return array();
		}



	}

	function detailPiutang($data){
		
		$key = $data['key'];
		$grup_lang = $data['grup_lang'];
		$tanggal = $data['tanggal'];

		$start=substr($tanggal,0,7).'-01';
		$end=$tanggal;
		$bulan = substr($tanggal,2,2).substr($tanggal,5,2);
		// echo "<pre>";
		// echo "key : ".$key."<br>";
		// echo "start : ".$start."<br>";
		// echo "end : ".$end."<br>";
		// echo "bulan : ".$bulan."<br>";
		// echo "</pre>";
		

		if ($key=='1') {
			$sql="
			select 	right(trim(no_polisi),8) nodokjdi,ref nodokacu, DATE_FORMAT(tanggal,'%d %M %Y') tgldokjdi,
					DATE_FORMAT(tgl_tempo,'%d %M %Y') tgl_jtempo,if(retur=1,dokument*-1,dokument)nilai 
			from 	dbsls.t_ar_ink_master a inner join dbsls.m_customer b using(customerid) 
			where 	group_id=$grup_lang and date(tanggal)>='$start' and date(tanggal)<='$end'";
		}elseif ($key=='2') {
			$sql="
			select 	right(trim(no_polisi),8) nodokjdi,ref nodokacu, 
					DATE_FORMAT(a.tanggal,'%d %M %Y') tgldokjdi,
					DATE_FORMAT(a.tgl_tempo,'%d %M %Y') tgl_jtempo,sum(bayar_transfer+bayar_giro+bayar_tunai) nilai 
			from 	dbsls.t_ar_ink_master a inner join dbsls.m_customer b using(customerid) 
					inner join dbsls.t_ar_ink_detail c using(no_sales)
			where 	group_id=$grup_lang and c.tgl_transfer>='$start' and tgl_transfer<='$end' 
			group by no_sales";
		}elseif ($key == '3') {
			$sql="
			select * from
			(
				select right(trim(no_polisi),8) nodokjdi,ref nodokacu,date_format(tgl_tempo,'%d %M %Y') tgl_jtempo,date_format(tanggal,'%d %M %Y') tgldokjdi,
				SUM(IF(days_past_due < 0, amount_due, 0)) nilai
				FROM(
					SELECT no_polisi,ref,tgl_tempo,tanggal,no_sales,group_id, group_descr, datediff('$end',date_format(tgl_tempo,'%Y-%m-%d')) days_past_due, sum(saldo) amount_due 
					FROM(
						SELECT no_polisi,ref,tanggal,no_sales ,a.customerid ,tgl_tempo ,( IF ( a.retur = 1, a.dokument *- 1, a.dokument )) - IF (isnull(kredit), 0, kredit) saldo
						FROM dbsls.t_ar_ink_master a 
						LEFT JOIN 
								(SELECT no_sales,customerid, sum( bayar_transfer + bayar_giro + bayar_tunai ) kredit 
								FROM dbsls.t_ar_ink_detail where date_format(tgl_transfer,'%Y-%m-%d') <='$end'
								GROUP BY no_sales ) b USING (no_sales) where date_format(a.tanggal,'%Y-%m-%d')<='$end' 
								GROUP BY no_sales)a1 
								inner join dbsls.m_customer b1 using (customerid) 
								WHERE saldo <>0
								GROUP BY no_sales,days_past_due
								)a 
					 where group_id=$grup_lang
				 group by no_sales )a 
			  where nilai<>0
			";
		}elseif ($key=='4') {
			$sql = "
			select * from
			(
				select right(trim(no_polisi),8) nodokjdi,ref nodokacu,date_format(tgl_tempo,'%d %M %Y') tgl_jtempo,date_format(tanggal,'%d %M %Y') tgldokjdi,
				SUM(IF(days_past_due = 0, amount_due, 0)) nilai
				FROM(
					SELECT no_polisi,ref,tgl_tempo,tanggal,no_sales,group_id, group_descr, datediff('$end',tgl_tempo) days_past_due, sum(saldo) amount_due 
					FROM(
						SELECT no_polisi,ref,tanggal,no_sales ,a.customerid ,tgl_tempo ,( IF ( a.retur = 1, a.dokument *- 1, a.dokument )) - IF (isnull(kredit), 0, kredit) saldo
						FROM dbsls.t_ar_ink_master a 
						LEFT JOIN 
								(SELECT no_sales,customerid, sum( bayar_transfer + bayar_giro + bayar_tunai ) kredit 
								FROM dbsls.t_ar_ink_detail where tgl_transfer <='$end'
								GROUP BY no_sales ) b USING (no_sales) where a.tanggal<='$end' 
								GROUP BY no_sales)a1 
								inner join dbsls.m_customer b1 using (customerid) 
								WHERE saldo <>0
								GROUP BY no_sales,days_past_due
								)a 
					 where group_id=$grup_lang
				 group by no_sales )a 
			  where nilai<>0";
		}elseif ($key=='5') {
			$sql="select * from(
				select right(trim(no_polisi),8) nodokjdi,ref nodokacu,date_format(tgl_tempo,'%d %M %Y') tgl_jtempo,date_format(tanggal,'%d %M %Y') tgldokjdi,
				SUM(IF(days_past_due BETWEEN 1 AND 30, amount_due, 0)) nilai
				FROM(
					SELECT no_polisi,ref,tgl_tempo,tanggal,no_sales,group_id, group_descr, datediff('$end',tgl_tempo) days_past_due, sum(saldo) amount_due 
					FROM(
						SELECT no_polisi,ref,tanggal,no_sales ,a.customerid ,tgl_tempo ,( IF ( a.retur = 1, a.dokument *- 1, a.dokument )) - IF (isnull(kredit), 0, kredit) saldo
						FROM dbsls.t_ar_ink_master a 
						LEFT JOIN 
								(SELECT no_sales,customerid, sum( bayar_transfer + bayar_giro + bayar_tunai ) kredit 
								FROM dbsls.t_ar_ink_detail where tgl_transfer <='$end'
								GROUP BY no_sales ) b USING (no_sales) where a.tanggal<='$end' 
								GROUP BY no_sales)a1 
								inner join dbsls.m_customer b1 using (customerid) 
								WHERE saldo <>0
								GROUP BY no_sales,days_past_due
								)a 
					 where group_id=$grup_lang 
				 group by no_sales )a 
			  where nilai<>0";
		}elseif ($key=='6') {
			$sql="select * from(
				select right(trim(no_polisi),8) nodokjdi,ref nodokacu,date_format(tgl_tempo,'%d %M %Y') tgl_jtempo,date_format(tanggal,'%d %M %Y') tgldokjdi,
				SUM(IF(days_past_due BETWEEN 31 AND 60, amount_due, 0)) nilai
				FROM(
					SELECT no_polisi,ref,tgl_tempo,tanggal,no_sales,group_id, group_descr, datediff('$end',tgl_tempo) days_past_due, sum(saldo) amount_due 
					FROM(
						SELECT no_polisi,ref,tanggal,no_sales ,a.customerid ,tgl_tempo ,( IF ( a.retur = 1, a.dokument *- 1, a.dokument )) - IF (isnull(kredit), 0, kredit) saldo
						FROM dbsls.t_ar_ink_master a 
						LEFT JOIN 
								(SELECT no_sales,customerid, sum( bayar_transfer + bayar_giro + bayar_tunai ) kredit 
								FROM dbsls.t_ar_ink_detail where tgl_transfer <='$end'
								GROUP BY no_sales ) b USING (no_sales) where a.tanggal<='$end' 
								GROUP BY no_sales)a1 
								inner join dbsls.m_customer b1 using (customerid) 
								WHERE saldo <>0
								GROUP BY no_sales,days_past_due
								)a 
					 where group_id=$grup_lang
				 group by no_sales )a 
			  where nilai<>0";
		}elseif ($key=='7') {
			$sql="select * from(
				select right(trim(no_polisi),8) nodokjdi,ref nodokacu,date_format(tgl_tempo,'%d %M %Y') tgl_jtempo,date_format(tanggal,'%d %M %Y') tgldokjdi,
				SUM(IF(days_past_due BETWEEN 61 AND 90, amount_due, 0)) nilai
				FROM(
					SELECT no_polisi,ref,tgl_tempo,tanggal,no_sales,group_id, group_descr, datediff('$end',tgl_tempo) days_past_due, sum(saldo) amount_due 
					FROM(
						SELECT no_polisi,ref,tanggal,no_sales ,a.customerid ,tgl_tempo ,( IF ( a.retur = 1, a.dokument *- 1, a.dokument )) - IF (isnull(kredit), 0, kredit) saldo
						FROM dbsls.t_ar_ink_master a 
						LEFT JOIN 
								(SELECT no_sales,customerid, sum( bayar_transfer + bayar_giro + bayar_tunai ) kredit 
								FROM dbsls.t_ar_ink_detail where tgl_transfer <='$end'
								GROUP BY no_sales ) b USING (no_sales) where a.tanggal<='$end' 
								GROUP BY no_sales)a1 
								inner join dbsls.m_customer b1 using (customerid) 
								WHERE saldo <>0
								GROUP BY no_sales,days_past_due
								)a 
					 where group_id=$grup_lang
				 group by no_sales )a 
			  where nilai<>0";
		}elseif ($key=='8') {
			$sql="select * from(
				select right(trim(no_polisi),8) nodokjdi,ref nodokacu,date_format(tgl_tempo,'%d %M %Y') tgl_jtempo,date_format(tanggal,'%d %M %Y') tgldokjdi,
				SUM(IF(days_past_due > 90, amount_due, 0)) nilai
				FROM(
					SELECT no_polisi,ref,tgl_tempo,tanggal,no_sales,group_id, group_descr, datediff('$end',tgl_tempo) days_past_due, sum(saldo) amount_due 
					FROM(
						SELECT no_polisi,ref,tanggal,no_sales ,a.customerid ,tgl_tempo ,( IF ( a.retur = 1, a.dokument *- 1, a.dokument )) - IF (isnull(kredit), 0, kredit) saldo
						FROM dbsls.t_ar_ink_master a 
						LEFT JOIN 
								(SELECT no_sales,customerid, sum( bayar_transfer + bayar_giro + bayar_tunai ) kredit 
								FROM dbsls.t_ar_ink_detail where tgl_transfer <='$end'
								GROUP BY no_sales ) b USING (no_sales) where a.tanggal<='$end' 
								GROUP BY no_sales)a1 
								inner join dbsls.m_customer b1 using (customerid) 
								WHERE saldo <>0
								GROUP BY no_sales,days_past_due
								)a 
					 where group_id=$grup_lang
				 group by no_sales )a 
			  where nilai<>0";
		}
		 else {
			# code...
		}

		// echo "<pre>";
		// print_r($sql);
		// echo "</pre>";
		
		$query = $this->db->query($sql);

		if ($query->num_rows() > 0) 
		{
			return $query->result();
		} else {
			return array();
		}
	}

	function viewPiutangMinggu($data){
		if ( function_exists( 'date_default_timezone_set' ) )
        date_default_timezone_set('Asia/Jakarta');
		$dob1=trim($data['tanggal']);//$dob1='dd/mm/yyyy' format
        $tanggal=strftime('%Y-%m-%d',strtotime($dob1));

		$range = $data['range'];

		$awal=substr($tanggal,0,7).'-01';
		$year=substr($tanggal,2,2);
		$month=substr($tanggal,5,2);
		$bulan=$year.$month;

		// echo "<pre>";
		// echo "tanggal : ".$tanggal."<br>";
		// echo "awal : ".$awal."<br>";
		// echo "year : ".$year."<br>";
		// echo "month : ".$month."<br>";
		// echo "bulan : ".$bulan."<br>";
		// echo "range : ".$range;
		// echo "</pre>";

		$sql="  
select  grup_lang,kode_comp,group_descr grup_nama,if(isnull(saldoawal),0,saldoawal)saldoawal,
		debit,kredit,(if(isnull(saldoawal),0,saldoawal)+debit-kredit) saldoakhir, current, duedate, r1 ,r2 ,r3 ,r4 ,
		total, '$tanggal' as tanggal 
from
(
        select  z.group_id grup_lang,z.group_descr,debitprev-if(isnull(kreditprev),0,kreditprev) saldoawal,if(isnull(debit),0,debit) debit,if(isnull(kredit),0,kredit) kredit
                ,current,duedate, r1 ,r2 ,r3 ,r4 ,current+duedate+r1+r2+r3+r4 total
        from
        (
                select  group_id, concat(group_descr,'') group_descr 
                from    dbsls.t_ar_ink_master a 
                        inner join dbsls.m_customer b using(customerid) 
                group by group_id
        )z                                                            
        left join                             
        (   
            select  group_id, group_descr, sum(if(retur=1,dokument*-1,dokument)) debitprev 
            from    dbsls.t_ar_ink_master a 
                    inner join dbsls.m_customer b using(customerid)
            where   tanggal<'".$awal."'
            group by group_id)a using(group_id)

            left join 
            (
                select  group_id, group_descr, sum(bayar_transfer+bayar_giro+bayar_tunai) kreditprev 
                from    dbsls.t_ar_ink_detail a 
                        inner join dbsls.m_customer b using(customerid)
                where  tgl_transfer<'".$awal."'
                group by group_id
            )b using(group_id)

            left join 
            (
                select  group_id, group_descr, sum(if(retur=1,dokument*-1,dokument)) debit 
                from    dbsls.t_ar_ink_master a 
                        inner join dbsls.m_customer b using(customerid)
                where   date(tanggal)>='".$awal."' and date(tanggal)<='".$tanggal."'
                group by group_id
            )c using (group_id)

            left join 
            (
                select  group_id, group_descr, 
                        if(isnull(sum(bayar_transfer+bayar_giro+bayar_tunai)),0,sum(bayar_transfer+bayar_giro+bayar_tunai)) kredit 
                from    dbsls.t_ar_ink_detail a 
                        inner join dbsls.m_customer b using(customerid)
                where   date(tgl_transfer)>='".$awal."' and date(tgl_transfer)<='".$tanggal."'
                group by group_id
            )d using(group_id)

            left join
            (
                select  group_id, group_descr,
                        SUM(IF(days_past_due < 0, amount_due, 0)) current,
                        SUM(IF(days_past_due = 0, amount_due, 0)) duedate,
                        SUM(IF(days_past_due BETWEEN 1 AND 7, amount_due, 0)) r1,
                        SUM(IF(days_past_due BETWEEN 8 AND 14, amount_due, 0)) r2,
                        SUM(IF(days_past_due BETWEEN 15 AND 30, amount_due, 0)) r3,
                        SUM(IF(days_past_due > 30, amount_due, 0))r4
                FROM
                (
                    SELECT  group_id, group_descr, datediff('".$tanggal."',tgl_tempo) days_past_due, sum(saldo) amount_due 
                    FROM
                    (
                        SELECT  no_sales ,a.customerid ,tgl_tempo ,
                                (IF(a.retur = 1,a.dokument*- 1,a.dokument))-IF(isnull(kredit), 0, kredit) saldo
                        FROM    dbsls.t_ar_ink_master a 
                        LEFT JOIN 
                        (
                                SELECT  no_sales,customerid, sum(bayar_transfer + bayar_giro + bayar_tunai) kredit 
                                FROM    dbsls.t_ar_ink_detail 
                                where   tgl_transfer <='".$tanggal."'
                                GROUP BY no_sales 
                        )b USING (no_sales) 
                        where a.tanggal<='".$tanggal."' 
                        GROUP BY no_sales
                    )a1 
                    inner join dbsls.m_customer b1 using (customerid) 
                    WHERE saldo <>0
                    GROUP BY group_id,days_past_due
                )a group by group_id 
            )e using (group_id)
        )a
        LEFT JOIN mpm.tabcomp tab ON a.grup_lang = tab.kode_lang
        where saldoawal+debit-kredit<>0 group by a.grup_lang
        order by a.group_descr
    ";

		$query = $this->db->query($sql);
		
		// echo "<pre>";
		// print_r($sql);
		// print_r($query);
		// echo "</pre>";

		if ($query->num_rows() > 0) 
		{
			return $query->result();
		} else {
			return array();
		}



	}

	function detailPiutangMinggu($data){
		
		$key = $data['key'];
		$grup_lang = $data['grup_lang'];
		$tanggal = $data['tanggal'];

		$start=substr($tanggal,0,7).'-01';
		$end=$tanggal;
		$bulan = substr($tanggal,2,2).substr($tanggal,5,2);
		// echo "<pre>";
		// echo "key : ".$key."<br>";
		// echo "start : ".$start."<br>";
		// echo "end : ".$end."<br>";
		// echo "bulan : ".$bulan."<br>";
		// echo "</pre>";
		

		if ($key=='1') {
			$sql="
			select 	right(trim(no_polisi),8) nodokjdi,ref nodokacu, DATE_FORMAT(tanggal,'%d %M %Y') tgldokjdi,
					DATE_FORMAT(tgl_tempo,'%d %M %Y') tgl_jtempo,if(retur=1,dokument*-1,dokument)nilai 
			from 	dbsls.t_ar_ink_master a inner join dbsls.m_customer b using(customerid) 
			where 	group_id=$grup_lang and date(tanggal)>='$start' and date(tanggal)<='$end'";
		}elseif ($key=='2') {
			$sql="
			select 	right(trim(no_polisi),8) nodokjdi,ref nodokacu, 
					DATE_FORMAT(a.tanggal,'%d %M %Y') tgldokjdi,
					DATE_FORMAT(a.tgl_tempo,'%d %M %Y') tgl_jtempo,sum(bayar_transfer+bayar_giro+bayar_tunai) nilai 
			from 	dbsls.t_ar_ink_master a inner join dbsls.m_customer b using(customerid) 
					inner join dbsls.t_ar_ink_detail c using(no_sales)
			where 	group_id=$grup_lang and c.tgl_transfer>='$start' and tgl_transfer<='$end' 
			group by no_sales";
		}elseif ($key == '3') {
			$sql="
			select * from
			(
				select right(trim(no_polisi),8) nodokjdi,ref nodokacu,date_format(tgl_tempo,'%d %M %Y') tgl_jtempo,date_format(tanggal,'%d %M %Y') tgldokjdi,
				SUM(IF(days_past_due < 0, amount_due, 0)) nilai
				FROM(
					SELECT no_polisi,ref,tgl_tempo,tanggal,no_sales,group_id, group_descr, datediff('$end',date_format(tgl_tempo,'%Y-%m-%d')) days_past_due, sum(saldo) amount_due 
					FROM(
						SELECT no_polisi,ref,tanggal,no_sales ,a.customerid ,tgl_tempo ,( IF ( a.retur = 1, a.dokument *- 1, a.dokument )) - IF (isnull(kredit), 0, kredit) saldo
						FROM dbsls.t_ar_ink_master a 
						LEFT JOIN 
								(SELECT no_sales,customerid, sum( bayar_transfer + bayar_giro + bayar_tunai ) kredit 
								FROM dbsls.t_ar_ink_detail where date_format(tgl_transfer,'%Y-%m-%d') <='$end'
								GROUP BY no_sales ) b USING (no_sales) where date_format(a.tanggal,'%Y-%m-%d')<='$end' 
								GROUP BY no_sales)a1 
								inner join dbsls.m_customer b1 using (customerid) 
								WHERE saldo <>0
								GROUP BY no_sales,days_past_due
								)a 
					 where group_id=$grup_lang
				 group by no_sales )a 
			  where nilai<>0
			";
		}elseif ($key=='4') {
			$sql = "
			select * from
			(
				select right(trim(no_polisi),8) nodokjdi,ref nodokacu,date_format(tgl_tempo,'%d %M %Y') tgl_jtempo,date_format(tanggal,'%d %M %Y') tgldokjdi,
				SUM(IF(days_past_due = 0, amount_due, 0)) nilai
				FROM(
					SELECT no_polisi,ref,tgl_tempo,tanggal,no_sales,group_id, group_descr, datediff('$end',tgl_tempo) days_past_due, sum(saldo) amount_due 
					FROM(
						SELECT no_polisi,ref,tanggal,no_sales ,a.customerid ,tgl_tempo ,( IF ( a.retur = 1, a.dokument *- 1, a.dokument )) - IF (isnull(kredit), 0, kredit) saldo
						FROM dbsls.t_ar_ink_master a 
						LEFT JOIN 
								(SELECT no_sales,customerid, sum( bayar_transfer + bayar_giro + bayar_tunai ) kredit 
								FROM dbsls.t_ar_ink_detail where tgl_transfer <='$end'
								GROUP BY no_sales ) b USING (no_sales) where a.tanggal<='$end' 
								GROUP BY no_sales)a1 
								inner join dbsls.m_customer b1 using (customerid) 
								WHERE saldo <>0
								GROUP BY no_sales,days_past_due
								)a 
					 where group_id=$grup_lang
				 group by no_sales )a 
			  where nilai<>0";
		}elseif ($key=='5') {
			$sql="select * from(
				select right(trim(no_polisi),8) nodokjdi,ref nodokacu,date_format(tgl_tempo,'%d %M %Y') tgl_jtempo,date_format(tanggal,'%d %M %Y') tgldokjdi,
				SUM(IF(days_past_due BETWEEN 1 AND 7, amount_due, 0)) nilai
				FROM(
					SELECT no_polisi,ref,tgl_tempo,tanggal,no_sales,group_id, group_descr, datediff('$end',tgl_tempo) days_past_due, sum(saldo) amount_due 
					FROM(
						SELECT no_polisi,ref,tanggal,no_sales ,a.customerid ,tgl_tempo ,( IF ( a.retur = 1, a.dokument *- 1, a.dokument )) - IF (isnull(kredit), 0, kredit) saldo
						FROM dbsls.t_ar_ink_master a 
						LEFT JOIN 
								(SELECT no_sales,customerid, sum( bayar_transfer + bayar_giro + bayar_tunai ) kredit 
								FROM dbsls.t_ar_ink_detail where tgl_transfer <='$end'
								GROUP BY no_sales ) b USING (no_sales) where a.tanggal<='$end' 
								GROUP BY no_sales)a1 
								inner join dbsls.m_customer b1 using (customerid) 
								WHERE saldo <>0
								GROUP BY no_sales,days_past_due
								)a 
					 where group_id=$grup_lang
				 group by no_sales )a 
			  where nilai<>0";
		}elseif ($key=='6') {
			$sql="select * from(
				select right(trim(no_polisi),8) nodokjdi,ref nodokacu,date_format(tgl_tempo,'%d %M %Y') tgl_jtempo,date_format(tanggal,'%d %M %Y') tgldokjdi,
				SUM(IF(days_past_due BETWEEN 8 AND 14, amount_due, 0)) nilai
				FROM(
					SELECT no_polisi,ref,tgl_tempo,tanggal,no_sales,group_id, group_descr, datediff('$end',tgl_tempo) days_past_due, sum(saldo) amount_due 
					FROM(
						SELECT no_polisi,ref,tanggal,no_sales ,a.customerid ,tgl_tempo ,( IF ( a.retur = 1, a.dokument *- 1, a.dokument )) - IF (isnull(kredit), 0, kredit) saldo
						FROM dbsls.t_ar_ink_master a 
						LEFT JOIN 
								(SELECT no_sales,customerid, sum( bayar_transfer + bayar_giro + bayar_tunai ) kredit 
								FROM dbsls.t_ar_ink_detail where tgl_transfer <='$end'
								GROUP BY no_sales ) b USING (no_sales) where a.tanggal<='$end' 
								GROUP BY no_sales)a1 
								inner join dbsls.m_customer b1 using (customerid) 
								WHERE saldo <>0
								GROUP BY no_sales,days_past_due
								)a 
					 where group_id=$grup_lang
				 group by no_sales )a 
			  where nilai<>0";
		$query=$this->db->query($sql,(array($end,$end,$end,$id)));       
		}elseif ($key=='7') {
			$sql="select * from(
				select right(trim(no_polisi),8) nodokjdi,ref nodokacu,date_format(tgl_tempo,'%d %M %Y') tgl_jtempo,date_format(tanggal,'%d %M %Y') tgldokjdi,
				SUM(IF(days_past_due BETWEEN 15 AND 30, amount_due, 0)) nilai
				FROM(
					SELECT no_polisi,ref,tgl_tempo,tanggal,no_sales,group_id, group_descr, datediff('$end',tgl_tempo) days_past_due, sum(saldo) amount_due 
					FROM(
						SELECT no_polisi,ref,tanggal,no_sales ,a.customerid ,tgl_tempo ,( IF ( a.retur = 1, a.dokument *- 1, a.dokument )) - IF (isnull(kredit), 0, kredit) saldo
						FROM dbsls.t_ar_ink_master a 
						LEFT JOIN 
								(SELECT no_sales,customerid, sum( bayar_transfer + bayar_giro + bayar_tunai ) kredit 
								FROM dbsls.t_ar_ink_detail where tgl_transfer <='$end'
								GROUP BY no_sales ) b USING (no_sales) where a.tanggal<='$end' 
								GROUP BY no_sales)a1 
								inner join dbsls.m_customer b1 using (customerid) 
								WHERE saldo <>0
								GROUP BY no_sales,days_past_due
								)a 
					 where group_id=$grup_lang
				 group by no_sales )a 
			  where nilai<>0";
		}elseif ($key=='8') {
			$sql="select * from(
				select right(trim(no_polisi),8) nodokjdi,ref nodokacu,date_format(tgl_tempo,'%d %M %Y') tgl_jtempo,date_format(tanggal,'%d %M %Y') tgldokjdi,
				SUM(IF(days_past_due > 30, amount_due, 0)) nilai
				FROM(
					SELECT no_polisi,ref,tgl_tempo,tanggal,no_sales,group_id, group_descr, datediff('$end',tgl_tempo) days_past_due, sum(saldo) amount_due 
					FROM(
						SELECT no_polisi,ref,tanggal,no_sales ,a.customerid ,tgl_tempo ,( IF ( a.retur = 1, a.dokument *- 1, a.dokument )) - IF (isnull(kredit), 0, kredit) saldo
						FROM dbsls.t_ar_ink_master a 
						LEFT JOIN 
								(SELECT no_sales,customerid, sum( bayar_transfer + bayar_giro + bayar_tunai ) kredit 
								FROM dbsls.t_ar_ink_detail where tgl_transfer <='$end'
								GROUP BY no_sales ) b USING (no_sales) where a.tanggal<='$end'
								GROUP BY no_sales)a1 
								inner join dbsls.m_customer b1 using (customerid) 
								WHERE saldo <>0
								GROUP BY no_sales,days_past_due
								)a 
					 where group_id=$grup_lang
				 group by no_sales )a 
			  where nilai<>0";
		}
		 else {
			# code...
		}

		// echo "<pre>";
		// print_r($sql);
		// echo "</pre>";
		
		$query = $this->db->query($sql);

		if ($query->num_rows() > 0) 
		{
			return $query->result();
		} else {
			return array();
		}
	}

	private function email_config_piutang()
    {
		$this->load->library(array('table','session','zip','stable','user_agent'));//session untuk sisi administrasi
		$this->load->helper(array('text_helper','array_helper','file_helper','to_excel_helper'));
		
         $config['protocol']  = 'smtp';
         $config['smtp_host'] = 'ssl://smtp.gmail.com';
         $config['smtp_port'] = '465';
         $config['smtp_timeout'] = '300';
        //  $config['smtp_user'] = 'surianampm@gmail.com';
        //  $config['smtp_pass'] = 'kasumaputra';
         $config['smtp_user'] = 'suffy.yanuar@gmail.com';
         $config['smtp_pass'] = 'yanuar123!@#';
         $config['charset']  = 'utf-8';
         $config['newline']  = "\r\n";
         $config['mailtype'] ="html";
         $config['use_ci_email'] = TRUE;

         $this->email->initialize($config);
	}

	public function getEmailFinance($data)
    {
		$grup_lang = substr($data['grup_lang'],1,6);
        $sql="select email_finance2 from mpm.user where kode_lang='$grup_lang'";
        $query=$this->db->query($sql);
        $row=$query->row();
        return $row->email_finance2;
        // echo $row->email_finance2;
        // echo $sql;
    }
	
	public function getCompanyByKodelang($data)
    {
		$grup_lang = substr($data['grup_lang'],1,6);
        $sql="select company from mpm.user where kode_lang='$grup_lang'";
		$query=$this->db->query($sql);
		// echo "<pre>";
		// print_r($sql);
		// eCHO "</pre>";
        $row=$query->row();
        return $row->company;
	}

	public function getMessage($data)
    {
		$key = $data['key'];
		$tanggal = $data['tanggal'];
		$grup_lang = $data['grup_lang'];

		// echo "<pre>";
		// echo "key mpiutang : ".$key."<br>";
		// echo "tanggal mpiutang: ".$tanggal."<br>";
		// echo "grup_lang mpiutang: ".$grup_lang."<br>";
		// echo "</pre>";

		if($key == 3){
			$cek = 'days_past_due < 0';
		}elseif($key == 4){
			$cek = 'days_past_due = 0';
		}elseif ($key == 5) {
			$cek = 'days_past_due BETWEEN 1 AND 30';
		}elseif ($key == 6) {
			$cek = 'days_past_due BETWEEN 31 AND 60';
		}elseif ($key == 7) {
			$cek ='days_past_due BETWEEN 61 AND 90';
		}elseif ($key == 8) {
			$cek = 'days_past_due > 90';
		}

		$sql="
		select * from
		(
			select 	right(trim(no_polisi),8) nodokjdi,
					ref nodokacu,
					date_format(tgl_tempo,'%d %M %Y') tgl_jtempo,
					date_format(tanggal,'%d %M %Y') tgldokjdi,
			SUM(IF($cek, amount_due, 0)) nilai
			FROM
			(
				SELECT 	no_polisi,ref,tgl_tempo,tanggal,no_sales,group_id, 
						group_descr, datediff('$tanggal',tgl_tempo) days_past_due, sum(saldo) amount_due 
				FROM
				(
					SELECT 	no_polisi,ref,tanggal,no_sales ,a.customerid ,tgl_tempo ,
							(IF(a.retur = 1, a.dokument *- 1, a.dokument )) - IF (isnull(kredit), 0, kredit) saldo
					FROM 	dbsls.t_ar_ink_master a 
					LEFT JOIN 
					(
							SELECT 	no_sales,customerid, 
									sum( bayar_transfer + bayar_giro + bayar_tunai ) kredit 
							FROM 	dbsls.t_ar_ink_detail 
							where 	tgl_transfer <='$tanggal'
							GROUP BY no_sales 
					) b USING (no_sales) 
					where a.tanggal<='$tanggal'
					GROUP BY no_sales
				)a1 
				inner join dbsls.m_customer b1 using (customerid) 
				WHERE saldo <>0
				GROUP BY no_sales,days_past_due
			)a 
			where group_id=$grup_lang
			group by no_sales 
		)a 
		where nilai<>0";
		
		$query = $this->db->query($sql);
		if ($query->num_rows() > 0) 
		{
			return $query->result();
		} else {
			return array();
		}


        // $sql="select company from mpm.user where kode_lang='$grup_lang'";
		// $query=$this->db->query($sql);
		// echo "<pre>";
		// print_r($sql);
		// echo "</pre>";
        // $row=$query->row();
        // return $row->company;
	}



	public function getMessageMinggu($data)
    {
		$key = $data['key'];
		$tanggal = $data['tanggal'];
		$grup_lang = $data['grup_lang'];

		// echo "<pre>";
		// echo "key mpiutang : ".$key."<br>";
		// echo "tanggal mpiutang: ".$tanggal."<br>";
		// echo "grup_lang mpiutang: ".$grup_lang."<br>";
		// echo "</pre>";

		if($key == 3){
			$cek = 'days_past_due < 0';
		}elseif($key == 4){
			$cek = 'days_past_due = 0';
		}elseif ($key == 5) {
			$cek = 'days_past_due BETWEEN 1 AND 7';
		}elseif ($key == 6) {
			$cek = 'days_past_due BETWEEN 8 AND 14';
		}elseif ($key == 7) {
			$cek ='days_past_due BETWEEN 15 AND 30';
		}elseif ($key == 8) {
			$cek = 'days_past_due > 30';
		}

		$sql="
		select * from
		(
			select 	right(trim(no_polisi),8) nodokjdi,
					ref nodokacu,
					date_format(tgl_tempo,'%d %M %Y') tgl_jtempo,
					date_format(tanggal,'%d %M %Y') tgldokjdi,
			SUM(IF($cek, amount_due, 0)) nilai
			FROM
			(
				SELECT 	no_polisi,ref,tgl_tempo,tanggal,no_sales,group_id, 
						group_descr, datediff('$tanggal',tgl_tempo) days_past_due, sum(saldo) amount_due 
				FROM
				(
					SELECT 	no_polisi,ref,tanggal,no_sales ,a.customerid ,tgl_tempo ,
							(IF(a.retur = 1, a.dokument *- 1, a.dokument )) - IF (isnull(kredit), 0, kredit) saldo
					FROM 	dbsls.t_ar_ink_master a 
					LEFT JOIN 
					(
							SELECT 	no_sales,customerid, 
									sum( bayar_transfer + bayar_giro + bayar_tunai ) kredit 
							FROM 	dbsls.t_ar_ink_detail 
							where 	tgl_transfer <='$tanggal'
							GROUP BY no_sales 
					) b USING (no_sales) 
					where a.tanggal<='$tanggal'
					GROUP BY no_sales
				)a1 
				inner join dbsls.m_customer b1 using (customerid) 
				WHERE saldo <>0
				GROUP BY no_sales,days_past_due
			)a 
			where group_id=$grup_lang
			group by no_sales 
		)a 
		where nilai<>0";
		
		$query = $this->db->query($sql);
		if ($query->num_rows() > 0) 
		{
			return $query->result();
		} else {
			return array();
		}


        // $sql="select company from mpm.user where kode_lang='$grup_lang'";
		// $query=$this->db->query($sql);
		// echo "<pre>";
		// print_r($sql);
		// echo "</pre>";
        // $row=$query->row();
        // return $row->company;
	}



}