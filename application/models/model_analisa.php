<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_analisa extends CI_Model 
{
	function analisa_piutang($data){
        
        $id = $this->session->userdata('id');
        $periode = $data['periode'];
        $periode_x=strftime('%Y-%m-%d',strtotime($periode));
        $periode_y=strftime('%m/%d/%Y 23:59:59',strtotime($periode));
        $awal=substr($periode_x,0,7).'-01';

        $sql = "delete from db_temp.t_temp_piutang where userid = $id";
        $proses = $this->db->query($sql);
        $sql = "delete from db_temp.t_temp_piutang_temp where userid = $id";
        $proses = $this->db->query($sql);

        // cek nocab
        $cek = "
                select a.wilayah_nocab
                from mpm.`user` a
                where id = $id        
            ";

        $proses = $this->db->query($cek)->result();

        foreach($proses as $a){
            $nocab = $a->wilayah_nocab;
        }
        // echo "nocab : ".$nocab."<br>";
        if ($nocab == '') {
            $customerid = "";
            $customeridy = "";
        }else{
            $nocabx = " and nocab in ($nocab)";

            $sql = "            
                select kode, kode_comp, nocab, nama_comp, b.username, concat('1',b.kode_lang) as customerid
                FROM
                (
                    select concat(kode_comp, a.nocab) as kode, kode_comp, nocab, nama_comp
                    from mpm.tbl_tabcomp a
                    where a.`status` = 1 and nocab in ($nocab)
                    GROUP BY concat(kode_comp, a.nocab)
                    ORDER BY nocab
                )a LEFT JOIN mpm.`user` b on a.kode_comp = b.username
                where kode_lang is not null
                GROUP BY kode_lang
            ";
            // echo "<pre>";
            // print_r($sql);
            // echo "</pre>";
            $proses = $this->db->query($sql)->result_array();

            foreach ($proses as $b) {
                $customerid[] = ','.$b['customerid'];
            }
            $x = implode($customerid);
            $customeridx=preg_replace('/,/', '', $x,1);
            // echo "customeridx : ".$customeridx;
            
            $customerid = "and dbsls.dbo.t_ar_ink_master.customerid in ($customeridx)";
            $customeridy = "and a.customerid in ($customeridx)";
        }        

        $serverName = "backup.muliaputramandiri.com"; //serverName\instanceName, portNumber (default is 1433)
        $connectionInfo = array("Database" => "", "UID" => "sa", "PWD" => "mpm12345");
        $conn = sqlsrv_connect($serverName, $connectionInfo);

        if ($conn) {
            // echo "<pre>Koneksi dengan Server SDS berhasil </pre>";
            $berhasil="Koneksi dengan Server SDS berhasil";
            echo "<pre>";
            print_r($berhasil);
            echo "</pre>";
            $sql = "
                    SELECT	ref, group_descr, no_polisi, depo_id, customerid, 
                            siteid, nama_site, alamat_site, branchid, nama_branch, 
                            alamat_branch, no_sales, tanggal, term_payment, 
                            tgl_tempo, tgl_tempo2,  ket, salesmanid, 
                            nama_salesman, nama_customer, prefix, alamat, 
                            segmentid, nama_segment, typeid, nama_type, regionalid, 
                            nama_regional, areaid, nama_area, classid, nama_class, 
                            debitur_id, debitur_name, nilai_faktur, bayar, saldo, 
                            umur, lewat, aging, type_bayar, kredit, debit, (debitprev-kreditprev) as saldoawal
                    FROM     
                    (
                        SELECT	data_faktur.ref, 
                                ISNULL(data_faktur.group_descr, data_faktur.nama_customer) AS group_descr, 
                                data_faktur.no_polisi, data_faktur.depo_id, 
                                data_faktur.customerid, data_faktur.siteid, data_faktur.nama_site, data_faktur.alamat_site, data_faktur.branchid, data_faktur.nama_branch, 
                                data_faktur.alamat_branch, data_faktur.no_sales, data_faktur.tanggal, data_faktur.term_payment, data_faktur.tgl_tempo, data_faktur.tgl_tempo2, 
                                data_faktur.ket, data_faktur.salesmanid, data_faktur.nama_salesman, data_faktur.nama_customer, data_faktur.prefix, data_faktur.alamat, 
                                data_faktur.segmentid, data_faktur.nama_segment, data_faktur.typeid, data_faktur.nama_type, data_faktur.regionalid, data_faktur.nama_regional, 
                                data_faktur.areaid, data_faktur.nama_area, data_faktur.classid, data_faktur.nama_class, data_faktur.debitur_id, data_faktur.debitur_name, 
                                data_faktur.debet AS nilai_faktur, 
                                ISNULL(data_ink.Bayar, 0) AS bayar, 
                                (CASE 	WHEN data_faktur.retur = 0 THEN abs(isnull(data_faktur.debet, 0)) - abs(isnull(data_ink.Bayar, 0)) ELSE - (abs(isnull(data_faktur.debet, 0)) - abs(isnull(data_ink.Bayar, 0))) END) AS saldo,
                                data_faktur.umur, data_faktur.lewat, 
                                (
                                    CASE 	WHEN data_faktur.lewat <= 0 THEN 'A. Belum Jatuh Tempo' 
                                            WHEN (data_faktur.lewat >= 1) AND (data_faktur.lewat <= 7) THEN 'B. 1 - 7'
                                            WHEN (data_faktur.lewat >= 8) AND (data_faktur.lewat <= 15) THEN 'C. 8 - 15' 
                                            WHEN (data_faktur.lewat >= 16) AND (data_faktur.lewat <= 30) THEN 'D. 16 - 30' 
                                            WHEN (data_faktur.lewat >= 31) AND (data_faktur.lewat <= 45) THEN 'E. 31 - 45'
                                            WHEN (data_faktur.lewat >= 46) AND (data_faktur.lewat <= 60) THEN 'F. 46 - 60' 
                                            WHEN (data_faktur.lewat > 60) THEN 'G. > 60 ' ELSE '' END
                                ) AS aging, 
                                (
                                    CASE 	WHEN data_faktur.type_bayar = 1 THEN 'Tunai' 
                                            WHEN data_faktur.type_bayar = 2 THEN 'Kredit' ELSE 'Checq/Giro' END
                                ) AS type_bayar,
                                ISNULL(data_ink_kreditprev.kreditprev, 0) AS kreditprev,
                                ISNULL(data_ink_debitprev.debitprev, 0) AS debitprev,
                                ISNULL(data_ink_kredit.kredit, 0) AS kredit,
                                ISNULL(data_ink_debit.debit, 0) AS debit
                        FROM
                        (
                            SELECT	t_ar_ink_master.ref, 
                                    LEFT(t_ar_ink_master.no_polisi, 3) + '.' + SUBSTRING(t_ar_ink_master.no_polisi, 4, 3) 
                                    + '-' + SUBSTRING(t_ar_ink_master.no_polisi, 7, 2) 
                                    + '.' + SUBSTRING(t_ar_ink_master.no_polisi, 9, 8) AS no_polisi, 
                                    t_ar_ink_master.retur, t_ar_ink_master.customerid, 
                                    t_ar_ink_master.siteid, m_setup_site.nama_site, 
                                    m_setup_site.alamat_site, m_setup_branch.branchid, 		
                                    m_setup_branch.nama_branch, m_setup_branch.alamat_branch, 
                                    t_ar_ink_master.no_sales, t_ar_ink_master.tanggal,
                                    t_ar_ink_master.type_bayar, t_ar_ink_master.term_payment, 
                                    t_ar_ink_master.tgl_tempo, t_ar_ink_master.tanggal + t_ar_ink_master.term_payment AS tgl_tempo2, 
                                    (
                                        CASE WHEN t_ar_ink_master.retur = 0 THEN t_ar_ink_master.dokument ELSE - t_ar_ink_master.dokument END
                                    ) AS debet, 0 AS kredit, 
                                    (
                                        CASE 	WHEN LEFT(t_ar_ink_master.no_sales, 1) = 'S' THEN 'Faktur' 
                                                    WHEN LEFT(t_ar_ink_master.no_sales, 1) = 'R' THEN 'Retur' 
                                                    WHEN LEFT(t_ar_ink_master.no_sales, 1) = 'C' THEN 'CN' 
                                                    WHEN LEFT(t_ar_ink_master.no_sales, 1) = 'D' THEN 'DN' END
                                    ) 
                                    + '/' + (
                                        CASE 	WHEN t_ar_ink_master.type_bayar = '1' THEN 'COD' 
                                                WHEN t_ar_ink_master.type_bayar = '2' THEN 'Kredit' 
                                                WHEN t_ar_ink_master.type_bayar = '3' THEN 'Giro or Cheq' END
                                    )
                                    + '/' + t_ar_ink_master.no_sales AS ket, 
                                    DATEDIFF(day, t_ar_ink_master.tanggal, CONVERT(DATETIME,'$periode_y', 102)) AS umur, 
                                    DATEDIFF(day, t_ar_ink_master.tanggal + t_ar_ink_master.term_payment, CONVERT(DATETIME, '$periode_y', 102)) AS lewat, 
                                    t_ar_ink_master.salesmanid, m_sales_salesman.nama_salesman, m_customer.nama_customer, 
                                    m_customer.depo_id, m_customer.prefix, m_customer.alamat, m_customer.segmentid, m_customer_segment.nama_segment, 
                                    m_customer.typeid, m_customer_type.nama_type, m_customer.regionalid, m_area_regional.nama_regional, m_customer.areaid, 
                                    m_area_areasite.nama_area, m_customer.classid, m_customer_class.nama_class, m_customer.debitur_id, m_debitur.debitur_name, 
                                    m_customer.group_descr, m_customer.group_id
                            FROM    dbsls.dbo.t_ar_ink_master INNER JOIN dbsls.dbo.m_setup_site 
                                        ON dbsls.dbo.t_ar_ink_master.siteid = dbsls.dbo.m_setup_site.siteid 
                                    INNER JOIN dbsls.dbo.m_setup_branch 
                                        ON dbsls.dbo.m_setup_site.branchid = dbsls.dbo.m_setup_branch.branchid 
                                    INNER JOIN dbsls.dbo.m_customer 
                                        ON dbsls.dbo.t_ar_ink_master.customerid = dbsls.dbo.m_customer.customerid 
                                    INNER JOIN dbsls.dbo.m_sales_salesman 
                                        ON 	dbsls.dbo.t_ar_ink_master.salesmanid = dbsls.dbo.m_sales_salesman.salesmanid AND 
                                        dbsls.dbo.t_ar_ink_master.siteid = dbsls.dbo.m_sales_salesman.siteid 
                                    LEFT OUTER JOIN dbsls.dbo.m_customer_class 
                                        ON dbsls.dbo.m_customer.classid = dbsls.dbo.m_customer_class.classid 
                                    LEFT OUTER JOIN dbsls.dbo.m_customer_segment 
                                        ON dbsls.dbo.m_customer.segmentid = dbsls.dbo.m_customer_segment.segmentid 
                                    LEFT OUTER JOIN dbsls.dbo.m_customer_type 
                                        ON dbsls.dbo.m_customer.typeid = dbsls.dbo.m_customer_type.typeid 
                                    LEFT OUTER JOIN dbsls.dbo.m_debitur 
                                        ON 	dbsls.dbo.m_customer.debitur_id = dbsls.dbo.m_debitur.debitur_id AND 
                                        dbsls.dbo.m_customer.siteid = dbsls.dbo.m_debitur.siteid 
                                    LEFT OUTER JOIN dbsls.dbo.m_area_regional 
                                        ON 	dbsls.dbo.m_customer.regionalid = dbsls.dbo.m_area_regional.regionalid AND 
                                        dbsls.dbo.m_customer.siteid = dbsls.dbo.m_area_regional.siteid 
                                    LEFT OUTER JOIN dbsls.dbo.m_area_areasite 
                                        ON 	dbsls.dbo.m_customer.areaid = dbsls.dbo.m_area_areasite.areaid AND 
                                        dbsls.dbo.m_customer.regionalid = dbsls.dbo.m_area_areasite.regionalid AND 
                                        dbsls.dbo.m_customer.siteid = dbsls.dbo.m_area_areasite.siteid
                            WHERE	(dbsls.dbo.t_ar_ink_master.siteid = 'KPS111') AND 
                                    (dbsls.dbo.t_ar_ink_master.tanggal <= CONVERT(DATETIME, '$periode_y', 102)) AND 
                                    (dbsls.dbo.t_ar_ink_master.program = 1) $customerid
                        ) AS data_faktur LEFT OUTER JOIN
                        (
                            SELECT	siteid, no_sales, SUM(bayar_tunai) + SUM(bayar_transfer) + SUM(bayar_giro) AS Bayar
                            FROM    dbsls.dbo.t_ar_ink_detail
                            WHERE   (tgl_ink <= CONVERT(DATETIME, '$periode_y', 102)) AND 
                                        (Counter_print >= 1) AND 
                                            (siteid = 'KPS111') AND 
                                        (ISNULL(status_giro, '') IN ('', 'C')) AND 
                                            (program = 1)
                            GROUP BY siteid, no_sales
                        ) AS data_ink ON data_faktur.no_sales = data_ink.no_sales AND 
                            data_faktur.siteid = data_ink.siteid                 
                            LEFT OUTER JOIN
                        (
                                select  siteid, no_sales, sum(bayar_transfer+bayar_giro+bayar_tunai) as kreditprev 
                                from    dbsls.dbo.t_ar_ink_detail
                                where   tgl_transfer<'$awal'
                                group by siteid, no_sales
                        ) AS data_ink_kreditprev ON data_faktur.no_sales = data_ink_kreditprev.no_sales and
                                data_faktur.siteid = data_ink_kreditprev.siteid
                                LEFT OUTER JOIN
                        (
                                select  siteid, no_sales,
                                                sum( CASE WHEN t_ar_ink_master.retur = 0 THEN dokument ELSE dokument*-1 END ) as debitprev
                                from    dbsls.dbo.t_ar_ink_master
                                where   tanggal<'$awal'
                                group by siteid, no_sales
                        ) AS data_ink_debitprev ON data_faktur.no_sales = data_ink_debitprev.no_sales and
                                data_faktur.siteid = data_ink_debitprev.siteid                 
                                LEFT OUTER JOIN
                        (
                                select  siteid, no_sales, sum(bayar_transfer+bayar_giro+bayar_tunai) as kredit 
                                from    dbsls.dbo.t_ar_ink_detail
                                where   tgl_transfer>='$awal' and tgl_transfer<='$periode_x'
                                group by siteid, no_sales
                        ) AS data_ink_kredit ON data_faktur.no_sales = data_ink_kredit.no_sales and
                                data_faktur.siteid = data_ink_kredit.siteid
                                LEFT OUTER JOIN
                        (
                                select  siteid, no_sales,
                                                sum( CASE WHEN t_ar_ink_master.retur = 0 THEN dokument ELSE dokument*-1 END ) as debit
                                from    dbsls.dbo.t_ar_ink_master
                                where   tanggal>='$awal' and tanggal<='$periode_x'
                                group by siteid, no_sales
                        ) AS data_ink_debit ON data_faktur.no_sales = data_ink_debit.no_sales and
                                data_faktur.siteid = data_ink_debit.siteid
                    ) AS data
                    WHERE (saldo <>0) 
                    ";

            // echo "<pre>";
            // print_r($sql);
            // echo "</pre>";
            
            $query = sqlsrv_query($conn, $sql); 
            if ($query) {
                while ($data = sqlsrv_fetch_array($query)){
                    $data = array(
                                    'ref'           => $data['ref'],
                                    'group_descr'   => $data['group_descr'],
                                    'no_polisi'     => $data['no_polisi'],
                                    'depo_id'       => $data['depo_id'],
                                    'customerid'    => $data['customerid'],
                                    'siteid'        => $data['siteid'],
                                    'nama_site'     => $data['nama_site'],
                                    'alamat_site'   => $data['alamat_site'],
                                    'branchid'      => $data['branchid'],
                                    'nama_branch'   => $data['nama_branch'],
                                    'alamat_branch' => $data['alamat_branch'],
                                    'no_sales'      => $data['no_sales'],
                                    'tanggal'       => $data['tanggal']->format('Y-m-d H:i:s'),
                                    'term_payment'  => $data['term_payment'],
                                    'tgl_tempo'     => $data['tgl_tempo']->format('Y-m-d H:i:s'),
                                    'tgl_tempo2'    => $data['tgl_tempo2']->format('Y-m-d H:i:s'),
                                    'ket'           => $data['ket'],
                                    'salesmanid'    => $data['salesmanid'],
                                    'nama_salesman' => $data['nama_salesman'],
                                    'nama_customer' => $data['nama_customer'],
                                    'prefix'        => $data['prefix'],
                                    'alamat'        => $data['alamat'],
                                    'segmentid'     => $data['segmentid'],
                                    'nama_segment'  => $data['nama_segment'],
                                    'typeid'        => $data['typeid'],
                                    'nama_type'     => $data['nama_type'],
                                    'regionalid'    => $data['regionalid'],
                                    'nama_regional' => $data['nama_regional'],
                                    'areaid'        => $data['areaid'],
                                    'nama_area'     => $data['nama_area'],
                                    'classid'       => $data['classid'],
                                    'nama_class'    => $data['nama_class'],
                                    'debitur_id'    => $data['debitur_id'],
                                    'debitur_name'  => $data['debitur_name'],
                                    'nilai_faktur'  => $data['nilai_faktur'],
                                    'bayar'         => $data['bayar'],
                                    'saldo'         => $data['saldo'],
                                    'umur'          => $data['umur'],
                                    'lewat'         => $data['lewat'],
                                    'aging'         => $data['aging'],
                                    'type_bayar'    => $data['type_bayar'],
                                    'userid'        => $id,
                                    'kredit'    => $data['kredit'],
                                    'debit'    => $data['debit'],
                                    'saldoawal'    => $data['saldoawal']
                                );

                    $this->db->insert('db_temp.t_temp_piutang',$data);
                }
            }

        } else {
            echo "<br><br><pre>Koneksi dengan server SDS gagal. Web akan mengambil data secara lokal. Mungkin data tidak sama dengan sds</pre><br />";
            // die(print_r(sqlsrv_errors(), true));
            $gagal="Koneksi dengan server SDS gagal. Web akan mengambil data secara lokal. Mungkin data tidak sama dengan sds";
            echo "<pre>";
            print_r($gagal);
            echo "</pre>";

            $sql = "
                    INSERT INTO db_temp.t_temp_piutang
                    SELECT	ref, group_descr, no_polisi, depo_id, customerid, siteid, nama_site, alamat_site, 
                            branchid, nama_branch, alamat_branch, no_sales, tanggal, term_payment, tgl_tempo, tgl_tempo2, kredit, ket, salesmanid, nama_salesman, nama_customer, prefix, alamat, segmentid, nama_segment, typeid, nama_type, regionalid, nama_regional, areaid, nama_area, classid, nama_class, debitur_id, 
                            debitur_name, nilai_faktur, bayar, saldo, umur, lewat, aging, type_bayar, $id
                    FROM    
                    (
                        select 	a.ref,ifnull(a.group_descr, a.nama_customer) as group_descr,a.no_polisi,
                                a.depo_id,a.customerid,a.siteid,a.nama_site,a.alamat_site,a.branchid,
                                a.nama_branch,a.alamat_branch,a.no_sales,a.tanggal,a.term_payment,a.tgl_tempo,a.tgl_tempo2,a.kredit,a.ket,a.salesmanid,a.nama_salesman,a.nama_customer,
                                a.prefix, a.alamat, a.segmentid, 
                                a.nama_segment, a.typeid, 
                                a.nama_type, a.regionalid, a.nama_regional, 
                                a.areaid, a.nama_area, a.classid, 
                                a.nama_class, a.debitur_id, a.debitur_name, 
                                a.debet AS nilai_faktur,
                                ifnull(b.bayar,0) as bayar,
                                (
                                    CASE WHEN a.retur = 0 THEN abs(ifnull(a.debet, 0)) - abs(ifnull(b.bayar, 0)) ELSE - (abs(ifnull(a.debet, 0)) - abs(ifnull(b.bayar, 0))) END
                                ) as saldo, a.umur, a.lewat,
                                (
                                    CASE WHEN a.lewat >= 0 THEN 'A. Belum Jatuh Tempo' 
                                    WHEN (a.lewat <= -1) AND (a.lewat >= -7) THEN 'B. 1 - 7'
                                    WHEN (a.lewat <= -8) AND (a.lewat >= -15) THEN 'C. 8 - 15' 
                                    WHEN (a.lewat <= -16) AND (a.lewat >= -30) THEN 'D. 16 - 30' 
                                    WHEN (a.lewat <= -31) AND (a.lewat >= -45) THEN 'E. 31 - 45'
                                    WHEN (a.lewat <= -46) AND (a.lewat >= 60) THEN 'F. 46 - 60' 
                                    WHEN (a.lewat < -60) THEN 'G. > 60 ' ELSE '' END
                                ) as aging, 
                                (
                                    CASE WHEN a.type_bayar = 1 THEN 'Tunai' WHEN a.type_bayar = 2 THEN 'Kredit' ELSE 'Checq/Giro' END
                                ) as type_bayar
                        FROM
                        (
                            SELECT 	a.ref,a.type_bayar,
                                    concat(LEFT(a.no_polisi, 3),'.',SUBSTRING(a.no_polisi, 4, 3),'-',SUBSTRING(a.no_polisi, 7, 2),'.',SUBSTRING(a.no_polisi, 9, 8)) as no_polisi,
                                    a.customerid, a.siteid, a.no_sales, a.tanggal, a.term_payment, a.tgl_tempo,
                                    concat(a.tanggal, a.term_payment) as tgl_tempo2,
                                    b.nama_site, b.alamat_site, c.branchid, c.nama_branch, c.alamat_branch,
                                    a.retur, a.dokument,
                                    (
                                        CASE WHEN a.retur = 0 THEN a.dokument ELSE - a.dokument END
                                    ) AS debet, 0 AS kredit,
                                    concat(
                                    (
                                        CASE WHEN LEFT(a.no_sales, 1) = 'S' THEN 'Faktur' 
                                        WHEN LEFT(a.no_sales, 1) = 'R' THEN 'Retur' 
                                        WHEN LEFT(a.no_sales, 1) = 'C' THEN 'CN' 
                                        WHEN LEFT(a.no_sales, 1) = 'D' THEN 'DN' END
                                    ),'/',
                                    (
                                        CASE WHEN a.type_bayar = '1' THEN 'COD' 
                                        WHEN a.type_bayar = '2' THEN 'Kredit' 
                                        WHEN a.type_bayar = '3' THEN 'Giro or Cheq' END
                                    ),'/',a.no_sales) as ket,
                                    DATEDIFF(a.tanggal, '$periode_x') AS umur,
                                    DATEDIFF(DATE_FORMAT(a.tgl_tempo, '%Y-%m-%d'), '$periode_x') as lewat,
                                    a.salesmanid, d.nama_salesman, e.nama_customer, e.depo_id, e.prefix, e.alamat, 
                                    e.segmentid, f.nama_segment, e.typeid, g.nama_type, e.regionalid, h.nama_regional,
                                    e.areaid, i.nama_area, e.classid, j.nama_class, e.debitur_id, k.debitur_name, e.group_descr
                            FROM	dbsls.t_ar_ink_master a INNER JOIN dbsls.m_setup_site b
                                    ON a.siteid = b.siteid INNER JOIN dbsls.m_setup_branch c
                                    on b.branchid = c.branchid INNER JOIN dbsls.m_sales_salesman d
                                    on a.salesmanid = d.salesmanid INNER JOIN dbsls.m_customer e
                                    on a.customerid = e.customerid INNER JOIN dbsls.m_customer_segment f
                                    on e.segmentid = f.segmentid INNER JOIN dbsls.m_customer_type g
                                    on e.typeid = g.typeid LEFT OUTER JOIN dbsls.m_area_regional h
                                    on e.regionalid = h.regionalid LEFT OUTER JOIN dbsls.m_area_areasite i
                                    on e.areaid = i.areaid LEFT OUTER JOIN dbsls.m_customer_class j
                                    on e.classid = j.classid LEFT OUTER JOIN dbsls.m_debitur k
                                    on e.debitur_id = k.debitur_id
                            where   (a.siteid = 'KPS111') and (a.tanggal <= '$periode_x') and (a.program = 1) $customeridy 
                        )a LEFT OUTER JOIN
                        (
                                SELECT	siteid, no_sales, SUM(bayar_tunai) + SUM(bayar_transfer) + SUM(bayar_giro) AS bayar, 
                                        SUM(bayar_tunai) AS bayar_tunai, SUM(bayar_transfer) AS bayar_transfer, SUM(bayar_giro) AS bayar_giro, SUM(bayar_lebih) AS bayar_lebih, status_giro, program,
                                        SUM(bayar_kurang) AS bayar_kurang, tgl_ink, Counter_print
                                FROM    dbsls.t_ar_ink_detail
                                WHERE   (
                                            tgl_ink <= '$periode_x') AND (Counter_print >= 1) AND 
                                        (siteid = 'KPS111')  and (ifnull(status_giro,'') in ('', 'C')) AND (program = 1)
                                GROUP BY siteid, no_sales
                        )b on a.no_sales = b.no_sales and a.siteid = b.siteid
                    )a 
                ";

            $proses = $this->db->query($sql);
            // echo "<pre>";
            // print_r($sql);
            // echo "</pre>";
        }

        $sql = "
            insert  into db_temp.t_temp_piutang_temp
            select  a.customerid, a.group_descr, 
                    sum(saldoawal) as saldoawal,
                    sum(debit) as debit,
                    sum(kredit) as kredit,
                    SUM(IF(tgl_tempo < 0, saldo, 0)) current,
                    SUM(IF(tgl_tempo = 0, saldo, 0)) duedate,
                    sum(if(substr(a.aging,1,1) = 'A',saldo,0)) as belum_jatuh_tempo,
                    sum(if(substr(a.aging,1,1) = 'B',saldo,0)) as '1 - 7',
                    sum(if(substr(a.aging,1,1) = 'C',saldo,0)) as '8 - 15',
                    sum(if(substr(a.aging,1,1) = 'D',saldo,0)) as '16 - 30',
                    sum(if(substr(a.aging,1,1) = 'E',saldo,0)) as '31 - 45',
                    sum(if(substr(a.aging,1,1) = 'F',saldo,0)) as '46 - 60',
                    sum(if(substr(a.aging,1,1) = 'G',saldo,0)) as '>60',
                    sum(saldo) as 'total', $id
            from db_temp.t_temp_piutang a where a.userid = $id
            GROUP BY group_descr
            union ALL
            select  '','z_TOTAL', 
                    sum(saldoawal) as saldoawal,
                    sum(debit) as debit,
                    sum(kredit) as kredit,
                    SUM(IF(tgl_tempo < 0, saldo, 0)) current,
                    SUM(IF(tgl_tempo = 0, saldo, 0)) duedate,
                    sum(if(substr(a.aging,1,1) = 'A',saldo,0)) as belum_jatuh_tempo,
                    sum(if(substr(a.aging,1,1) = 'B',saldo,0)) as '1 - 7',
                    sum(if(substr(a.aging,1,1) = 'C',saldo,0)) as '8 - 15',
                    sum(if(substr(a.aging,1,1) = 'D',saldo,0)) as '16 - 30',
                    sum(if(substr(a.aging,1,1) = 'E',saldo,0)) as '31 - 45',
                    sum(if(substr(a.aging,1,1) = 'F',saldo,0)) as '46 - 60',
                    sum(if(substr(a.aging,1,1) = 'G',saldo,0)) as '>60',
                    sum(saldo) as 'total', $id
            from    db_temp.t_temp_piutang a where a.userid = $id
        ";
        // echo "<pre>";
        // print_r($sql);
        // echo "</pre>";
        $proses = $this->db->query($sql);

        $hasil = "select * from db_temp.t_temp_piutang_temp where userid = $id order by group_descr";
        $proses_hasil= $this->db->query($hasil)->result();
        return $proses_hasil;
    }
}