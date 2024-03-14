<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_master_data extends CI_Model 
{
    
    public function get_salesman(){
        date_default_timezone_set('Asia/Jakarta'); 
        $date = date_create(date('Y-m-d H:i:s'));
        date_add($date, date_interval_create_from_date_string('-7 hours'));
        $tgl = date_format($date, 'Y-m-d H:i:s'); 

        $tahun_sekarang = date('Y');
        $truncate = "truncate db_master_data.t_salesman";
        $proses_truncate = $this->db->query($truncate);

        if ($proses_truncate) {
            $insert_salesman = "
            insert into db_master_data.t_salesman    
            select a.kodesales,a.kodesales_erp,a.namasales,b.kode,b.branch_name, b.nama_comp, b.sub, b.kode_comp, b.nocab,'$tgl'
            FROM
            (
                select concat(a.KODESALES, a.nocab) as kodesales, a.KODESALES as kodesales_erp, a.NAMASALES as namasales,a.nocab
                from data$tahun_sekarang.tabsales a
                GROUP BY concat(a.KODESALES, a.nocab)
            )a LEFT JOIN 
            (
                select a.kode,a.branch_name,a.nama_comp,a.kode_comp,a.nocab,a.sub
                FROM
                (
                    select concat(a.kode_comp,a.nocab) as kode, a.branch_name, a.nama_comp,a.kode_comp,a.nocab, a.sub
                    from mpm.tbl_tabcomp a
                    where a.`status` = 1
                    GROUP BY kode
                )a INNER JOIN
                (
                    select concat(a.kode_comp,a.nocab) as kode
                    from db_dp.t_dp a
                    where a.tahun = $tahun_sekarang and `status` =1
                )b on a.kode = b.kode
                ORDER BY nocab
            )b on a.nocab = b.nocab
            ";
            $proses_insert_salesman = $this->db->query($insert_salesman);
        }else{
            return array();
        }

    }

    public function get_customer(){
        date_default_timezone_set('Asia/Jakarta'); 
        $date = date_create(date('Y-m-d H:i:s'));
        date_add($date, date_interval_create_from_date_string('-7 hours'));
        $tgl = date_format($date, 'Y-m-d H:i:s'); 

        $tahun_sekarang = date('Y');
        $truncate = "truncate db_master_data.t_customer";
        // $delete_customer = "delete from db_master_data.t_customer where";
        $proses_truncate = $this->db->query($truncate);
        $truncate_salesman_ob = "truncate db_master_data.t_salesman_ob";
        $proses_truncate_salesman_ob = $this->db->query($truncate_salesman_ob);
        $truncate_salesman = "truncate db_master_data.t_salesman";
        $proses_truncate_salesman = $this->db->query($truncate_salesman);
        $truncate_stock = "truncate db_master_data.t_temp_stock";
        $proses_truncate_stock = $this->db->query($truncate_stock);
        $truncate_credit_limit = "truncate db_master_data.m_customer_creditlimit_prinsipal";
        $proses_truncate_credit_limit = $this->db->query($truncate_credit_limit);

        if ($proses_truncate) {
            $insert_customer = "
            insert into db_master_data.t_customer                
            select 	a.kode_lang,a.nama_lang,a.kodesalur,a.kode_type,
                    a.alamat, a.id_provinsi,a.nama_provinsi,
                    a.id_kota,a.nama_kota,a.id_kecamatan,a.nama_kecamatan,a.id_kelurahan,a.nama_kelurahan,a.telp,
                    a.credit_limit, a.status_payment,
                    b.kode,b.branch_name,b.nama_comp,
                    b.sub,b.kode_comp,b.nocab, concat(7,right(substr(a.kode_lang,4,6) * 1234,5)) as code_approval,erp_updated,'$tgl'
            FROM
            (
                select 	concat(a.kode_comp,a.kode_lang) as kode_lang, 
                        a.NAMA_LANG as nama_lang,a.KODESALUR as kodesalur,
                        a.KODE_TYPE as kode_type,a.ALAMAT1 as alamat, 
                        a.id_provinsi, a.nama_provinsi,
                        a.id_kota, a.nama_kota,
                        a.id_kecamatan, a.nama_kecamatan,
                        a.id_kelurahan, a.nama_kelurahan,
                        concat(a.kode_comp,a.nocab) as kode, a.credit_limit,a.tipe_bayar as status_payment, a.telp,
                        a.last_updated as erp_updated
                from    data$tahun_sekarang.tblang a  
                
                where a.kode_comp in ('CLP','DIY','KBM','MGL','PWJ','PWT','TMG','BGR','CKG','CKR','JK1','JK3','JKT','SRG','TGR') and year(a.last_updated) = $tahun_sekarang
                GROUP BY concat(a.kode_comp,a.kode_lang,nocab)
            )a INNER JOIN
            (
                select a.kode,a.branch_name,a.nama_comp,a.kode_comp,a.nocab,a.sub
                FROM
                (
                    select concat(a.kode_comp,a.nocab) as kode, a.branch_name, a.nama_comp,a.kode_comp,a.nocab, a.sub
                    from mpm.tbl_tabcomp a
                    where a.`status` = 1
                    GROUP BY kode
                )a INNER JOIN
                (
                    select concat(a.kode_comp,a.nocab) as kode
                    from db_dp.t_dp a
                    where a.tahun = $tahun_sekarang and `status`= 1
                )b on a.kode = b.kode
                ORDER BY nocab
            )b on a.kode = b.kode
            ";
            $proses_insert_customer = $this->db->query($insert_customer);

            // ('CLP','DIY','KBM','MGL','PWJ','PWT','TMG')
            // FLOOR(RAND() * (<max> - <min> + 1)) + <min>

            echo "<pre>";
            print_r($insert_customer);
            echo "</pre>";

            $sql_insert_salesman_ob = "
            insert into db_master_data.t_salesman_ob
            select a.*,'$tgl'
            from data$tahun_sekarang.m_sales_salsman_ob a
            ";
            $proses_sql_insert_salesman_ob = $this->db->query($sql_insert_salesman_ob);

            $insert_salesman = "
            insert into db_master_data.t_salesman    
            select a.kodesales,a.kodesales_erp,a.namasales,b.kode,b.branch_name, b.nama_comp, b.sub, b.kode_comp, b.nocab,'$tgl'
            FROM
            (
                select concat(a.KODESALES, a.nocab) as kodesales, a.KODESALES as kodesales_erp, a.NAMASALES as namasales,a.nocab
                from data$tahun_sekarang.tabsales a
                GROUP BY concat(a.KODESALES, a.nocab)
            )a LEFT JOIN 
            (
                select a.kode,a.branch_name,a.nama_comp,a.kode_comp,a.nocab,a.sub
                FROM
                (
                    select concat(a.kode_comp,a.nocab) as kode, a.branch_name, a.nama_comp,a.kode_comp,a.nocab, a.sub
                    from mpm.tbl_tabcomp a
                    where a.`status` = 1
                    GROUP BY kode
                )a INNER JOIN
                (
                    select concat(a.kode_comp,a.nocab) as kode
                    from db_dp.t_dp a
                    where a.tahun = $tahun_sekarang and `status` =1
                )b on a.kode = b.kode
                ORDER BY nocab
            )b on a.nocab = b.nocab
            ";
            $proses_insert_salesman = $this->db->query($insert_salesman);
            
            $insert_stock = "
            insert into db_master_data.t_temp_stock
            select nocab, kodeprod, bulan, stock_akhir,'$tgl'
            FROM
            (
                select  nocab,kodeprod,substr(bulan,3) as bulan,
                        sum( if(kode_gdg='PST',
                        ((Saldoawal+masuk_pbk+masuk_supp+retur_sal+bPinjam+kvretur+kr_gudang+retur_depo)-
                        (sales+pinjam+minta_depo+kvbeli+kr_min)-(rusak+sisih)),(Saldoawal+masuk_pbk+retur_sal+kvretur+bPinjam+tukar_msk+msk_gddepo) -
                        (sales+Pinjam+kvbeli+retur_depo+tukar_klr+klr_gddepo))) as stock_akhir
                from    data$tahun_sekarang.st a
                group by nocab, kodeprod,bulan
                order by kodeprod
            )a  
            where a.bulan = (
                select max(substr(b.bulan,3)) as bulan
                from data$tahun_sekarang.st b
                where b.nocab = a.nocab
                GROUP BY b.nocab
            )

            ";
            $proses_insert_stock = $this->db->query($insert_stock);

            $insert_credit_limit = "
            insert into db_master_data.m_customer_creditlimit_prinsipal
            select *,'$tgl'
            from data$tahun_sekarang.m_customer_creditlimit_prinsipal a
            where a.kode_comp in ('CLP','DIY','KBM','MGL','PWJ','PWT','TMG')

            ";
            $proses_insert_credit_limit = $this->db->query($insert_credit_limit);

        }else{
            return array();
        }

    }

    public function get_monitoring_stock(){
        date_default_timezone_set('Asia/Jakarta'); 
        $date = date_create(date('Y-m-d H:i:s'));
        date_add($date, date_interval_create_from_date_string('-7 hours'));
        $created_date = date_format($date, 'Y-m-d H:i:s'); 

        $tahun_sekarang = date('Y');
        $bulan_sekarang = date('m');

        $kodeprod = $this->model_per_hari->cari_kodeprod_supp('XXX');
        // $kodeprod = '010074';

        $truncate = "truncate db_master_data.t_temp_monitoring_stock_akhir";
        $proses_truncate = $this->db->query($truncate);
        $truncate_avg = "truncate db_master_data.t_temp_monitoring_stock_avg";
        $proses_truncate_avg = $this->db->query($truncate_avg);
        $truncate_sales_berjalan = "truncate db_master_data.t_temp_monitoring_stock_sales_berjalan";
        $proses_truncate_sales_berjalan = $this->db->query($truncate_sales_berjalan);
        $truncate_upload = "truncate db_master_data.t_temp_monitoring_stock_upload";
        $proses_truncate_upload = $this->db->query($truncate_upload);
        $truncate_git = "truncate db_master_data.t_temp_monitoring_stock_git";
        $proses_truncate_git = $this->db->query($truncate_git);
        $truncate_produk = "truncate db_master_data.t_temp_monitoring_stock_produk";
        $proses_truncate_produk = $this->db->query($truncate_produk);
        $truncate_join = "truncate db_master_data.t_temp_monitoring_stock_join";
        $proses_truncate_join = $this->db->query($truncate_join);
        $truncate_log_user = "truncate db_master_data.t_temp_monitoring_stock_log_user";
        $proses_truncate_log_user = $this->db->query($truncate_log_user);

        if ($proses_truncate) {
            $insert_stock_akhir = "
            insert into db_master_data.t_temp_monitoring_stock_akhir
            select c.kode, a.nocab, a.kodeprod, stock, '$created_date'
            FROM
            (
                select a.nocab, a.kodeprod, a.stock
                FROM
                (
                    select  nocab,kodeprod,namaprod,substr(bulan,3) as bulan,                            
                            sum((Saldoawal+masuk_pbk+retur_sal+retur_depo)-(sales+minta_depo)) as stock
                    from    data$tahun_sekarang.st 
                    where 	substr(bulan,3) = $bulan_sekarang and kodeprod in ($kodeprod) and kode_gdg in ('pst',1) 
                    group by nocab, kodeprod,bulan
                    order by kodeprod
                )a 
            )a INNER JOIN
            (
                select a.kode, nocab, a.branch_name, a.nama_comp
                FROM
                (
                    select concat(a.kode_comp, a.nocab) as kode, a.branch_name, a.nama_comp, a.nocab
                    from mpm.tbl_tabcomp a
                    where a.`status` = 1 and (a.stock <> 2 or a.stock is null)
                    GROUP BY concat(a.kode_comp, a.nocab)
                )a INNER JOIN
                (
                    SELECT concat(a.kode_comp,a.nocab) as kode
                    from db_dp.t_dp a
                    where tahun = $tahun_sekarang
                )b on a.kode = b.kode
                ORDER BY nocab
            )c on a.nocab = c.nocab
            ORDER BY nocab, kodeprod
            ";
            $proses_insert_stock_akhir = $this->db->query($insert_stock_akhir);


            echo "<pre>";
            print_r($insert_stock_akhir);
            echo "</pre>";

            // $bulan_sekarang = '5';
            $bulan_sebelumnya = $bulan_sekarang - 1;
            $bulan_avg = $bulan_sekarang - 6;
            $tahun_sebelumnya = $tahun_sekarang - 1;

            echo "bulan_sebelumnya : ".$bulan_sebelumnya."<br>";
            echo "bulan_avg : ".$bulan_avg;

            if ($bulan_avg > 0) 
            {            
                for ($i=$bulan_avg; $i < $bulan_sekarang ; $i++) { 
                    $bulan_avg_x[] = $i;
                }            
                $bulan_avg_y = implode(', ', $bulan_avg_x);
                $fi = "
                    select 	if(concat(a.kode_comp, a.nocab) = 'JBG46' OR concat(a.kode_comp, a.nocab) = 'MJK46','SDO46',concat(a.kode_comp, a.nocab)) as kode, tot1, banyak, kodeprod
                    from 	data$tahun_sekarang.fi a
                    where 	bulan in ($bulan_avg_y) and 
                            kodeprod in ($kodeprod)
                    union all
                    select 	if(concat(a.kode_comp, a.nocab) = 'JBG46' OR concat(a.kode_comp, a.nocab) = 'MJK46','SDO46',concat(a.kode_comp, a.nocab)) as kode, tot1, banyak, kodeprod
                    from 	data$tahun_sekarang.ri a
                    where 	bulan in ($bulan_avg_y) and 
                            kodeprod in ($kodeprod)
                ";

              
            }elseif($bulan_avg == 0){
                // echo "bulan_sekarang : ".$bulan_sekarang;
                $tahun_avg_x = $tahun_sekarang - 1;
                // echo "tahun_avg_x : ".$tahun_avg_x;

                for ($i=1; $i < $bulan_sekarang ; $i++) { 
                    $bulan_avg_x[] = $i;
                }            
                $bulan_avg_y = implode(', ', $bulan_avg_x);
                // echo "bulan_avg_y : ".$bulan_avg_y;
                
                $fi = "
                    select 	if(concat(a.kode_comp, a.nocab) = 'JBG46' OR concat(a.kode_comp, a.nocab) = 'MJK46','SDO46',concat(a.kode_comp, a.nocab)) as kode, tot1, banyak, kodeprod
                    from 	data$tahun_avg_x.fi a
                    where 	bulan in (12) and 
                            kodeprod in ($kodeprod) 
                    union all
                    select 	if(concat(a.kode_comp, a.nocab) = 'JBG46' OR concat(a.kode_comp, a.nocab) = 'MJK46','SDO46',concat(a.kode_comp, a.nocab)) as kode, tot1, banyak, kodeprod
                    from 	data$tahun_avg_x.ri a
                    where 	bulan in (12) and 
                            kodeprod in ($kodeprod) 
                    union all
                    select 	if(concat(a.kode_comp, a.nocab) = 'JBG46' OR concat(a.kode_comp, a.nocab) = 'MJK46','SDO46',concat(a.kode_comp, a.nocab)) as kode, tot1, banyak, kodeprod
                    from 	data$tahun_sekarang.fi a
                    where 	bulan in ($bulan_avg_y) and 
                            kodeprod in ($kodeprod) 
                    union all
                    select 	if(concat(a.kode_comp, a.nocab) = 'JBG46' OR concat(a.kode_comp, a.nocab) = 'MJK46','SDO46',concat(a.kode_comp, a.nocab)) as kode, tot1, banyak, kodeprod
                    from 	data$tahun_sekarang.ri a
                    where 	bulan in ($bulan_avg_y) and 
                            kodeprod in ($kodeprod) 
                ";
            }else{
                
                $cari_bulan_awal = 12 + $bulan_avg;
                for ($i=$cari_bulan_awal; $i <= 12 ; $i++) { 
                    $bulan_avg_ay[] = $i;
                }      

                $bulan_avg = implode(', ', $bulan_avg_ay);
                echo "bulan_avg : ".$bulan_avg;

                for ($i=1; $i < $bulan_sekarang ; $i++) { 
                    $bulan_avg_x[] = $i;
                }            
                $bulan_avg_y = implode(', ', $bulan_avg_x);
                echo "bulan_avg_y : ".$bulan_avg_y;

                // die;

                $fi = "
                    select 	if(concat(a.kode_comp, a.nocab) = 'JBG46' OR concat(a.kode_comp, a.nocab) = 'MJK46','SDO46',concat(a.kode_comp, a.nocab)) as kode, tot1, banyak, kodeprod
                    from 	data$tahun_sebelumnya.fi a
                    where 	bulan in ($bulan_avg) and 
                            kodeprod in ($kodeprod) 
                    union all
                    select 	if(concat(a.kode_comp, a.nocab) = 'JBG46' OR concat(a.kode_comp, a.nocab) = 'MJK46','SDO46',concat(a.kode_comp, a.nocab)) as kode, tot1, banyak, kodeprod
                    from 	data$tahun_sebelumnya.ri a
                    where 	bulan in ($bulan_avg) and 
                            kodeprod in ($kodeprod) 

                    union all
                    select 	if(concat(a.kode_comp, a.nocab) = 'JBG46' OR concat(a.kode_comp, a.nocab) = 'MJK46','SDO46',concat(a.kode_comp, a.nocab)) as kode, tot1, banyak, kodeprod
                    from 	data$tahun_sekarang.fi a
                    where 	bulan in ($bulan_avg_y) and 
                            kodeprod in ($kodeprod) 
                    union all
                    select 	if(concat(a.kode_comp, a.nocab) = 'JBG46' OR concat(a.kode_comp, a.nocab) = 'MJK46','SDO46',concat(a.kode_comp, a.nocab)) as kode, tot1, banyak, kodeprod
                    from 	data$tahun_sekarang.ri a
                    where 	bulan in ($bulan_avg_y) and 
                            kodeprod in ($kodeprod) 
                ";
            }

            $insert_avg = "
                insert into db_master_data.t_temp_monitoring_stock_avg
                SELECT kode, kodeprod, round(sum(banyak)/6) as rata,'$created_date'
                FROM
                (
                    $fi
                )a GROUP BY kode, kodeprod
            ";
            
            echo "<pre>";
            print_r($insert_avg);
            echo "</pre>";
            $proses_insert_avg = $this->db->query($insert_avg);

            // die;

            $insert_sales_berjalan = "
            insert into db_master_data.t_temp_monitoring_stock_sales_berjalan
            select kode, KODEPROD, sum(banyak) as omzet_unit,'$created_date'
            FROM
            (
                select if(concat(a.kode_comp, a.nocab) = 'JBG46' OR concat(a.kode_comp, a.nocab) = 'MJK46','SDO46',concat(a.kode_comp, a.nocab)) as kode, banyak, kodeprod
                from data$tahun_sekarang.fi a
                where bulan in ($bulan_sekarang) and kodeprod in ($kodeprod) 
                union all
                select if(concat(a.kode_comp, a.nocab) = 'JBG46' OR concat(a.kode_comp, a.nocab) = 'MJK46','SDO46',concat(a.kode_comp, a.nocab)) as kode, banyak, kodeprod
                from data$tahun_sekarang.ri a
                where bulan in ($bulan_sekarang) and kodeprod in ($kodeprod) 
            )a 
            GROUP BY kode, kodeprod
            ";

            echo "<pre>";
            print_r($insert_sales_berjalan);
            echo "</pre>";
            $proses_insert_sales_berjalan = $this->db->query($insert_sales_berjalan);

            $insert_upload = "   
                insert into db_master_data.t_temp_monitoring_stock_upload         
                select 	substring(filename,3,2) as nocab,date(lastupload) as lastupload, '$created_date'
                from 	mpm.upload
                where 	bulan in ($bulan_sekarang,$bulan_sebelumnya) and tahun = $tahun_sekarang and id in (
                    select max(id)
                    from mpm.upload
                    where tahun = $tahun_sekarang and bulan in ($bulan_sekarang,$bulan_sebelumnya)
                    GROUP BY substring(filename,3,2)
                )and userid not in ('0','289')
            ";

            echo "<pre>";
            print_r($insert_upload);
            echo "</pre>";
            $proses_insert_sales_berjalan = $this->db->query($insert_upload);

            $insert_git = "
            insert into db_master_data.t_temp_monitoring_stock_git 
            select a.kode_alamat, a.kodeprod, a.git, '$created_date'
            from
            (
                select 	a.kode_alamat, a.kodeprod, sum(a.qty_po) as git
                from 	db_po.t_po_outstanding_deltomed a
                where 	((
                            year(a.tglpo) in (year(date(now()))) and month(a.tglpo) in (month(date(now())))
                        ) or
                        (
                            year(a.tglpo) in (date_format(date(now()) - INTERVAL '1' MONTH,'%Y')) and 
                            month(a.tglpo) in (date_format(date(now()) - INTERVAL '1' MONTH,'%m'))
                        )) and a.tanggal_terima is null 
                GROUP BY a.kode_alamat, a.kodeprod
                union all
                select 	a.kode_alamat, a.kodeprod, sum(a.qty_po) as git
                from 	db_po.t_po_outstanding_us a
                where 	((
                            year(a.tglpo) in (year(date(now()))) and month(a.tglpo) in (month(date(now())))
                        ) or
                        (
                            year(a.tglpo) in (date_format(date(now()) - INTERVAL '1' MONTH,'%Y')) and 
                            month(a.tglpo) in (date_format(date(now()) - INTERVAL '1' MONTH,'%m'))
                        )) and a.tanggal_terima is null 
                GROUP BY a.kode_alamat, a.kodeprod
                union all
                select 	a.kode_alamat, a.kodeprod, sum(a.qty_po) as git
                from 	db_po.t_po_outstanding_intrafood a
                where 	((
                            year(a.tglpo) in (year(date(now()))) and month(a.tglpo) in (month(date(now())))
                        ) or
                        (
                            year(a.tglpo) in (date_format(date(now()) - INTERVAL '1' MONTH,'%Y')) and 
                            month(a.tglpo) in (date_format(date(now()) - INTERVAL '1' MONTH,'%m'))
                        )) and a.tanggal_terima is null 
                GROUP BY a.kode_alamat, a.kodeprod

                union all
                select 	a.kode_alamat, a.kodeprod, sum(a.qty_po) as git
                from 	db_po.t_po_outstanding_marguna a
                where 	((
                            year(a.tglpo) in (year(date(now()))) and month(a.tglpo) in (month(date(now())))
                        ) or
                        (
                            year(a.tglpo) in (date_format(date(now()) - INTERVAL '1' MONTH,'%Y')) and 
                            month(a.tglpo) in (date_format(date(now()) - INTERVAL '1' MONTH,'%m'))
                        ))
                GROUP BY a.kode_alamat, a.kodeprod

                union all
                select 	a.kode_alamat, a.kodeprod, sum(a.qty_po) as git
                from 	db_po.t_po_outstanding_jaya a
                where 	((
                            year(a.tglpo) in (year(date(now()))) and month(a.tglpo) in (month(date(now())))
                        ) or
                        (
                            year(a.tglpo) in (date_format(date(now()) - INTERVAL '1' MONTH,'%Y')) and 
                            month(a.tglpo) in (date_format(date(now()) - INTERVAL '1' MONTH,'%m'))
                        ))
                GROUP BY a.kode_alamat, a.kodeprod

                union all
                select 	a.kode_alamat, a.kodeprod, sum(a.qty_po) as git
                from 	db_po.t_po_outstanding_strive a
                where 	((
                            year(a.tglpo) in (year(date(now()))) and month(a.tglpo) in (month(date(now())))
                        ) or
                        (
                            year(a.tglpo) in (date_format(date(now()) - INTERVAL '1' MONTH,'%Y')) and 
                            month(a.tglpo) in (date_format(date(now()) - INTERVAL '1' MONTH,'%m'))
                        ))
                GROUP BY a.kode_alamat, a.kodeprod

                union all
                select 	a.kode_alamat, a.kodeprod, sum(a.qty_po) as git
                from 	db_po.t_po_outstanding_hni a
                where 	((
                            year(a.tglpo) in (year(date(now()))) and month(a.tglpo) in (month(date(now())))
                        ) or
                        (
                            year(a.tglpo) in (date_format(date(now()) - INTERVAL '1' MONTH,'%Y')) and 
                            month(a.tglpo) in (date_format(date(now()) - INTERVAL '1' MONTH,'%m'))
                        ))
                GROUP BY a.kode_alamat, a.kodeprod
            )a
            ";
            echo "<pre>";
            print_r($insert_git);
            echo "</pre>";
            $proses_insert_sales_berjalan = $this->db->query($insert_git);

            $insert_produk = "
            insert into db_master_data.t_temp_monitoring_stock_produk
            select	b.branch_name, b.nama_comp, a.supp, a.namasupp, a.kodeprod, a.namaprod, a.isisatuan,
                    a.berat, a.berat_gr, a.volume, a.harga, b.kode, '$created_date'
            from
            (
                select a.supp, d.namasupp, a.kodeprod, a.namaprod, a.isisatuan, a.berat, a.berat_gr, a.volume, b.h_dp as harga
                from mpm.tabprod a inner join mpm.prod_detail b 
                    on a.kodeprod = b.kodeprod LEFT JOIN mpm.tabsupp d 
                    on a.supp = d.supp
                where b.tgl = (
                    select max(c.tgl)
                    from mpm.prod_detail c
                    where c.kodeprod = b.kodeprod
                )and a.active = 1
                ORDER BY kodeprod
            )a join 
            (
                select a.kode, a.branch_name, a.nama_comp, a.urutan
                from 
                (
                    select if(concat(a.kode_comp, a.nocab) = 'JBG46' OR concat(a.kode_comp, a.nocab) = 'MJK46','SDO46',concat(a.kode_comp, a.nocab)) as kode, a.branch_name, a.nama_comp, a.urutan
                    from mpm.tbl_tabcomp a
                    where a.`status` = 1
                    GROUP BY kode
                )a INNER JOIN 
                (
                    select concat(a.kode_comp, a.nocab) as kode
                    from db_dp.t_dp a
                    where a.tahun = $tahun_sekarang
                )b on a.kode = b.kode 	
            )b 
            ORDER BY b.kode, a.kodeprod
            ";

            
            echo "<pre>";
            print_r($insert_produk);
            echo "</pre>";
            $proses_insert_produk = $this->db->query($insert_produk);

            $insert_join = "           
            insert into db_master_data.t_temp_monitoring_stock_join
            select 	a.branch_name, a.nama_comp, a.kode, a.supp, a.namasupp, a.kodeprod, a.namaprod, a.isisatuan, a.berat, 
                    a.volume, a.harga, b.rata,	c.stock_akhir_unit,	d.sales_berjalan, e.lastupload,	f.git,	g.stock_ideal, 
                    '' as stock_ideal_unit, '$created_date'
            from db_master_data.t_temp_monitoring_stock_produk a LEFT JOIN
            (
                select a.kode,a.kodeprod, a.rata
                from db_master_data.t_temp_monitoring_stock_avg a
            )b on a.kode = b.kode and a.kodeprod = b.kodeprod LEFT JOIN
            (
                select a.kode, a.kodeprod, a.stock_akhir_unit
                from db_master_data.t_temp_monitoring_stock_akhir a
            )c on a.kode = c.kode and a.kodeprod = c.kodeprod LEFT JOIN
            (
                select a.kode, a.kodeprod, a.sales_berjalan
                from db_master_data.t_temp_monitoring_stock_sales_berjalan a
            )d on a.kode = d.kode and a.kodeprod = d.kodeprod LEFT JOIN
            (
                select a.nocab, a.lastupload
                from db_master_data.t_temp_monitoring_stock_upload a
            )e on right(a.kode,2) = e.nocab LEFT JOIN
            (
                select a.kode, a.kodeprod, a.git
                from db_master_data.t_temp_monitoring_stock_git a
            )f on a.kode = f.kode and a.kodeprod = f.kodeprod LEFT JOIN
            (
                select a.kode, a.kodeprod, a.stock_ideal
                from db_master_data.t_temp_monitoring_stock_ideal a
                where a.`status` = 1
            )g on a.kode = g.kode and a.kodeprod = g.kodeprod

            ";

            echo "<pre>";
            print_r($insert_join);
            echo "</pre>";
            $proses_insert_join = $this->db->query($insert_join);

            // update stock_akhir_unit dan sales_berjalan
            $this->db->query("update db_master_data.t_temp_monitoring_stock_join a set a.stock_akhir_unit = 0 where a.stock_akhir_unit is null");
            $this->db->query("update db_master_data.t_temp_monitoring_stock_join a set a.git = 0 where a.git is null");
            $this->db->query("update db_master_data.t_temp_monitoring_stock_join a set a.sales_berjalan = 0 where a.sales_berjalan is null");
            $this->db->query("update db_master_data.t_temp_monitoring_stock_join a set a.stock_ideal = 21 where a.stock_ideal is null");
            $this->db->query("update db_master_data.t_temp_monitoring_stock_join a set a.stock_ideal_unit = round(a.rata / 30 * a.stock_ideal)");
            
            $this->get_monitoring_stock_to_doi($created_date);


        }else{
            return array();
        }

    }

    public function get_monitoring_stock_harian(){
        date_default_timezone_set('Asia/Jakarta'); 
        $date = date_create(date('Y-m-d H:i:s'));
        date_add($date, date_interval_create_from_date_string('-7 hours'));
        $created_date = date_format($date, 'Y-m-d H:i:s'); 

        $tahun_sekarang = date('Y');
        $bulan_sekarang = date('m');

        $kodeprod = $this->model_per_hari->cari_kodeprod_supp('XXX');
        // $kodeprod = '010074';

        $truncate = "truncate db_master_data.t_temp_monitoring_stock_akhir";
        $proses_truncate = $this->db->query($truncate);
        // $truncate_avg = "truncate db_master_data.t_temp_monitoring_stock_avg";
        // $proses_truncate_avg = $this->db->query($truncate_avg);
        $truncate_sales_berjalan = "truncate db_master_data.t_temp_monitoring_stock_sales_berjalan";
        $proses_truncate_sales_berjalan = $this->db->query($truncate_sales_berjalan);
        $truncate_upload = "truncate db_master_data.t_temp_monitoring_stock_upload";
        $proses_truncate_upload = $this->db->query($truncate_upload);
        $truncate_git = "truncate db_master_data.t_temp_monitoring_stock_git";
        $proses_truncate_git = $this->db->query($truncate_git);
        // $truncate_produk = "truncate db_master_data.t_temp_monitoring_stock_produk";
        // $proses_truncate_produk = $this->db->query($truncate_produk);
        $truncate_join = "truncate db_master_data.t_temp_monitoring_stock_join";
        $proses_truncate_join = $this->db->query($truncate_join);
        // $truncate_log_user = "truncate db_master_data.t_temp_monitoring_stock_log_user";
        // $proses_truncate_log_user = $this->db->query($truncate_log_user);

        // $cari_tanggal_log_user = "
        //     select max(a.created_date) as created_date
        //     from db_master_data.t_temp_monitoring_stock_log_user a
        // ";
        // $proses_cari_tanggal_log_user = $this->db->query($cari_tanggal_log_user)->result();
        // foreach ($proses_cari_tanggal_log_user as $key) {
        //     $created_date_log_user = $key->created_date;
        // }

        // echo "created_date_log_user : ".$created_date_log_user;

        $truncate_log_user = "truncate db_master_data.t_temp_monitoring_stock_log_user";
        $proses_truncate_log_user = $this->db->query($truncate_log_user);

        $insert_log_user = "
            insert into db_master_data.t_temp_monitoring_stock_log_user
            select kode, kodeprod, estimasi_sales, stock_ideal, '$created_date', '', '',''
            from db_master_data.t_temp_monitoring_stock_suggest_po
        ";
        $proses_insert_log_user = $this->db->query($insert_log_user);

        
            $insert_stock_akhir = "
            insert into db_master_data.t_temp_monitoring_stock_akhir
            select c.kode, a.nocab, a.kodeprod, stock, '$created_date'
            FROM
            (
                select a.nocab, a.kodeprod, a.stock
                FROM
                (
                    select  nocab,kodeprod,namaprod,substr(bulan,3) as bulan,                            
                            sum((Saldoawal+masuk_pbk+retur_sal+retur_depo)-(sales+minta_depo)) as stock
                    from    data$tahun_sekarang.st 
                    where 	substr(bulan,3) = $bulan_sekarang and kodeprod in ($kodeprod) and kode_gdg in ('pst',1) 
                    group by nocab, kodeprod,bulan
                    order by kodeprod
                )a 
            )a INNER JOIN
            (
                select a.kode, nocab, a.branch_name, a.nama_comp
                FROM
                (
                    select concat(a.kode_comp, a.nocab) as kode, a.branch_name, a.nama_comp, a.nocab
                    from mpm.tbl_tabcomp a
                    where a.`status` = 1 and (a.stock <> 2 or a.stock is null)
                    GROUP BY concat(a.kode_comp, a.nocab)
                )a INNER JOIN
                (
                    SELECT concat(a.kode_comp,a.nocab) as kode
                    from db_dp.t_dp a
                    where tahun = $tahun_sekarang
                )b on a.kode = b.kode
                ORDER BY nocab
            )c on a.nocab = c.nocab
            ORDER BY nocab, kodeprod
            ";
            $proses_insert_stock_akhir = $this->db->query($insert_stock_akhir);


            echo "<pre>";
            print_r($insert_stock_akhir);
            echo "</pre>";

            // $bulan_sekarang = '5';
            $bulan_sebelumnya = $bulan_sekarang - 1;
            $bulan_avg = $bulan_sekarang - 6;
            $tahun_sebelumnya = $tahun_sekarang - 1;

          

            $insert_sales_berjalan = "
            insert into db_master_data.t_temp_monitoring_stock_sales_berjalan
            select kode, KODEPROD, sum(banyak) as omzet_unit,'$created_date'
            FROM
            (
                select concat(a.kode_comp, a.nocab) as kode, banyak, kodeprod
                from data$tahun_sekarang.fi a
                where bulan in ($bulan_sekarang) and kodeprod in ($kodeprod) 
                union all
                select concat(a.kode_comp, a.nocab) as kode, banyak, kodeprod
                from data$tahun_sekarang.ri a
                where bulan in ($bulan_sekarang) and kodeprod in ($kodeprod) 
            )a 
            GROUP BY kode, kodeprod
            ";

            echo "<pre>";
            print_r($insert_sales_berjalan);
            echo "</pre>";
            $proses_insert_sales_berjalan = $this->db->query($insert_sales_berjalan);

            $insert_upload = "   
                insert into db_master_data.t_temp_monitoring_stock_upload         
                select 	substring(filename,3,2) as nocab,date(lastupload) as lastupload, '$created_date'
                from 	mpm.upload
                where 	bulan in ($bulan_sekarang,$bulan_sebelumnya) and tahun = $tahun_sekarang and id in (
                    select max(id)
                    from mpm.upload
                    where tahun = $tahun_sekarang and bulan in ($bulan_sekarang,$bulan_sebelumnya)
                    GROUP BY substring(filename,3,2)
                )and userid not in ('0','289')
            ";

            echo "<pre>";
            print_r($insert_upload);
            echo "</pre>";
            $proses_insert_sales_berjalan = $this->db->query($insert_upload);

            $insert_git = "
            insert into db_master_data.t_temp_monitoring_stock_git 
            select a.kode_alamat, a.kodeprod, a.git, '$created_date'
            from
            (
                select 	a.kode_alamat, a.kodeprod, sum(a.qty_po) as git
                from 	db_po.t_po_outstanding_deltomed a
                where 	((
                            year(a.tglpo) in (year(date(now()))) and month(a.tglpo) in (month(date(now())))
                        ) or
                        (
                            year(a.tglpo) in (date_format(date(now()) - INTERVAL '1' MONTH,'%Y')) and 
                            month(a.tglpo) in (date_format(date(now()) - INTERVAL '1' MONTH,'%m'))
                        )) and a.tanggal_terima is null 
                GROUP BY a.kode_alamat, a.kodeprod
                union all
                select 	a.kode_alamat, a.kodeprod, sum(a.qty_po) as git
                from 	db_po.t_po_outstanding_us a
                where 	((
                            year(a.tglpo) in (year(date(now()))) and month(a.tglpo) in (month(date(now())))
                        ) or
                        (
                            year(a.tglpo) in (date_format(date(now()) - INTERVAL '1' MONTH,'%Y')) and 
                            month(a.tglpo) in (date_format(date(now()) - INTERVAL '1' MONTH,'%m'))
                        )) and a.tanggal_terima is null 
                GROUP BY a.kode_alamat, a.kodeprod
                union all
                select 	a.kode_alamat, a.kodeprod, sum(a.qty_pemenuhan_unit) as git
                from 	db_po.t_po_outstanding_intrafood a
                where 	((
                            year(a.tglpo) in (year(date(now()))) and month(a.tglpo) in (month(date(now())))
                        ) or
                        (
                            year(a.tglpo) in (date_format(date(now()) - INTERVAL '1' MONTH,'%Y')) and 
                            month(a.tglpo) in (date_format(date(now()) - INTERVAL '1' MONTH,'%m'))
                        )) and a.tanggal_terima is null 
                GROUP BY a.kode_alamat, a.kodeprod

                union all
                select 	a.kode_alamat, a.kodeprod, sum(a.qty_pemenuhan_unit) as git
                from 	db_po.t_po_outstanding_marguna a
                where 	((
                            year(a.tglpo) in (year(date(now()))) and month(a.tglpo) in (month(date(now())))
                        ) or
                        (
                            year(a.tglpo) in (date_format(date(now()) - INTERVAL '1' MONTH,'%Y')) and 
                            month(a.tglpo) in (date_format(date(now()) - INTERVAL '1' MONTH,'%m'))
                        ))
                GROUP BY a.kode_alamat, a.kodeprod

                union all
                select 	a.kode_alamat, a.kodeprod, sum(a.qty_po) as git
                from 	db_po.t_po_outstanding_jaya a
                where 	((
                            year(a.tglpo) in (year(date(now()))) and month(a.tglpo) in (month(date(now())))
                        ) or
                        (
                            year(a.tglpo) in (date_format(date(now()) - INTERVAL '1' MONTH,'%Y')) and 
                            month(a.tglpo) in (date_format(date(now()) - INTERVAL '1' MONTH,'%m'))
                        ))
                GROUP BY a.kode_alamat, a.kodeprod

                union all
                select 	a.kode_alamat, a.kodeprod, sum(a.qty_po) as git
                from 	db_po.t_po_outstanding_strive a
                where 	((
                            year(a.tglpo) in (year(date(now()))) and month(a.tglpo) in (month(date(now())))
                        ) or
                        (
                            year(a.tglpo) in (date_format(date(now()) - INTERVAL '1' MONTH,'%Y')) and 
                            month(a.tglpo) in (date_format(date(now()) - INTERVAL '1' MONTH,'%m'))
                        ))
                GROUP BY a.kode_alamat, a.kodeprod

                union all
                select 	a.kode_alamat, a.kodeprod, sum(a.qty_po) as git
                from 	db_po.t_po_outstanding_hni a
                where 	((
                            year(a.tglpo) in (year(date(now()))) and month(a.tglpo) in (month(date(now())))
                        ) or
                        (
                            year(a.tglpo) in (date_format(date(now()) - INTERVAL '1' MONTH,'%Y')) and 
                            month(a.tglpo) in (date_format(date(now()) - INTERVAL '1' MONTH,'%m'))
                        ))
                GROUP BY a.kode_alamat, a.kodeprod
            )a
            ";
            echo "<pre>";
            print_r($insert_git);
            echo "</pre>";
            $proses_insert_sales_berjalan = $this->db->query($insert_git);


            $insert_join = "           
            insert into db_master_data.t_temp_monitoring_stock_join
            select 	a.branch_name, a.nama_comp, a.kode, a.supp, a.namasupp, a.kodeprod, a.namaprod, a.isisatuan, a.berat, 
                    a.volume, a.harga, b.rata,	c.stock_akhir_unit,	d.sales_berjalan, e.lastupload,	f.git,	g.stock_ideal, 
                    '' as stock_ideal_unit, '$created_date'
            from db_master_data.t_temp_monitoring_stock_produk a LEFT JOIN
            (
                select a.kode,a.kodeprod, a.rata
                from db_master_data.t_temp_monitoring_stock_avg a
            )b on a.kode = b.kode and a.kodeprod = b.kodeprod LEFT JOIN
            (
                select a.kode, a.kodeprod, a.stock_akhir_unit
                from db_master_data.t_temp_monitoring_stock_akhir a
            )c on a.kode = c.kode and a.kodeprod = c.kodeprod LEFT JOIN
            (
                select a.kode, a.kodeprod, a.sales_berjalan
                from db_master_data.t_temp_monitoring_stock_sales_berjalan a
            )d on a.kode = d.kode and a.kodeprod = d.kodeprod LEFT JOIN
            (
                select a.nocab, a.lastupload
                from db_master_data.t_temp_monitoring_stock_upload a
            )e on right(a.kode,2) = e.nocab LEFT JOIN
            (
                select a.kode, a.kodeprod, a.git
                from db_master_data.t_temp_monitoring_stock_git a
            )f on a.kode = f.kode and a.kodeprod = f.kodeprod LEFT JOIN
            (
                select a.kode, a.kodeprod, a.estimasi_sales, a.stock_ideal
                from db_master_data.t_temp_monitoring_stock_log_user a
                
            )g on a.kode = g.kode and a.kodeprod = g.kodeprod

            ";

            echo "<pre>";
            print_r($insert_join);
            echo "</pre>";
            $proses_insert_join = $this->db->query($insert_join);

            echo "<br><hr>aaaa";
            // die;

            // update stock_akhir_unit dan sales_berjalan
            $this->db->query("update db_master_data.t_temp_monitoring_stock_join a set a.stock_akhir_unit = 0 where a.stock_akhir_unit is null");
            $this->db->query("update db_master_data.t_temp_monitoring_stock_join a set a.rata = 0 where a.rata is null");
            $this->db->query("update db_master_data.t_temp_monitoring_stock_join a set a.git = 0 where a.git is null");
            $this->db->query("update db_master_data.t_temp_monitoring_stock_join a set a.sales_berjalan = 0 where a.sales_berjalan is null");
            $this->db->query("update db_master_data.t_temp_monitoring_stock_join a set a.stock_ideal = 21 where a.stock_ideal is null");
            $this->db->query("update db_master_data.t_temp_monitoring_stock_join a set a.stock_ideal_unit = round(a.rata / 30 * a.stock_ideal)");
            
            $this->get_monitoring_stock_to_doi_harian($created_date);


        

    }

    public function get_monitoring_stock_to_doi($created_date){

        $truncate_doi = "truncate db_master_data.t_temp_monitoring_stock_doi";
        $proses_truncate_doi = $this->db->query($truncate_doi);

        $sql_doi = "
            insert into db_master_data.t_temp_monitoring_stock_doi
            select  a.branch_name, a.nama_comp, a.kode, a.supp, a.namasupp,a.kodeprod, a.namaprod, a.isisatuan, a.berat, a.volume, a.harga, a.lastupload,
                    a.rata, a.stock_akhir_unit, a.git, (a.stock_akhir_unit + a.git) as total_stock, a.sales_berjalan, a.rata as estimasi_sales,
                    (a.rata - a.sales_berjalan) as potensi_sales, (a.stock_akhir_unit + a.git) - (a.rata - a.sales_berjalan) as stock_berjalan,
                    round(((a.stock_akhir_unit + a.git) - (a.rata - a.sales_berjalan)) / a.rata * 30) as doi, a.stock_ideal,a.stock_ideal_unit, '$created_date'
            from db_master_data.t_temp_monitoring_stock_join a
            where a.created_date = '$created_date'
            ";
        $proses_sql_doi = $this->db->Query($sql_doi);

        echo "<pre>";
        print_r($sql_doi);
        echo "</pre>";

        $this->get_monitoring_stock_suggest_po($created_date);

    }

    public function get_monitoring_stock_to_doi_harian($created_date){

        $truncate_doi = "truncate db_master_data.t_temp_monitoring_stock_doi";
        $proses_truncate_doi = $this->db->query($truncate_doi);

        $sql_doi = "
            insert into db_master_data.t_temp_monitoring_stock_doi
            select  a.branch_name, a.nama_comp, a.kode, a.supp, a.namasupp,a.kodeprod, a.namaprod, a.isisatuan, a.berat, 
                    a.volume, a.harga, a.lastupload, a.rata, a.stock_akhir_unit, a.git, 
                    (a.stock_akhir_unit + a.git) as total_stock, a.sales_berjalan, b.estimasi_sales,
                    (b.estimasi_sales - a.sales_berjalan) as potensi_sales, (a.stock_akhir_unit + a.git) - (b.estimasi_sales - a.sales_berjalan) as stock_berjalan,
                    round(((a.stock_akhir_unit + a.git) - (b.estimasi_sales - a.sales_berjalan)) / a.rata * 30) as doi, a.stock_ideal,a.stock_ideal_unit, '$created_date'
            from db_master_data.t_temp_monitoring_stock_join a left join
            (
                select a.kode, a.kodeprod, a.estimasi_sales, a.stock_ideal
                from db_master_data.t_temp_monitoring_stock_log_user a
            )b on a.kode = b.kode and a.kodeprod = b.kodeprod
            where a.created_date = '$created_date'
            ";
        $proses_sql_doi = $this->db->Query($sql_doi);

        echo "<pre>";
        print_r($sql_doi);
        echo "</pre>";

        echo "<hr>bbbb";

        $this->get_monitoring_stock_suggest_po_harian($created_date);

    }

    public function get_monitoring_stock_suggest_po($created_date){
        $truncate_suggest_po = "truncate db_master_data.t_temp_monitoring_stock_suggest_po";
        $proses_truncate_suggest_po = $this->db->query($truncate_suggest_po);
        $sql = "
        insert into db_master_data.t_temp_monitoring_stock_suggest_po
        select 	a.branch_name, a.nama_comp, a.kode, a.supp, a.namasupp,a.kodeprod, a.namaprod, a.isisatuan,a.berat,a.volume,
		        a.harga, a.lastupload, a.rata, a.stock_akhir_unit, a.git, a.total_stock, a.sales_berjalan, a.estimasi_sales,
		        a.potensi_sales, a.stock_berjalan, a.doi, a.stock_ideal, a.stock_ideal_unit, 
		        if(a.stock_ideal_unit - a.stock_berjalan < 0,0,a.stock_ideal_unit - a.stock_berjalan) as pp,
		        a.stock_berjalan + if(a.stock_ideal_unit - a.stock_berjalan < 0,0,a.stock_ideal_unit - a.stock_berjalan) as estimasi_saldo_akhir, 
                round((a.stock_berjalan + if(a.stock_ideal_unit - a.stock_berjalan < 0,0,a.stock_ideal_unit - a.stock_berjalan)) / a.rata * 30) as estimasi_doi_akhir,
                round((if(a.stock_ideal_unit - a.stock_berjalan < 0,0,a.stock_ideal_unit - a.stock_berjalan)) / a.isisatuan) as pp_karton,
                round(((if(a.stock_ideal_unit - a.stock_berjalan < 0,0,a.stock_ideal_unit - a.stock_berjalan)) / a.isisatuan) * a.berat) as pp_kg,
                round(((if(a.stock_ideal_unit - a.stock_berjalan < 0,0,a.stock_ideal_unit - a.stock_berjalan)) / a.isisatuan) * a.volume) as pp_volume,
                round(((if(a.stock_ideal_unit - a.stock_berjalan < 0,0,a.stock_ideal_unit - a.stock_berjalan)) / a.isisatuan) * a.isisatuan * a.harga) as pp_value, '' as signature,'$created_date', '' as last_updated, '' as last_updated_by
        from db_master_data.t_temp_monitoring_stock_doi a
        where a.created_date = '$created_date'
        ";
        $proses = $this->db->query($sql);

        echo "<pre>";
        print_r($sql);
        echo "</pre>";

        $this->db->query("update db_master_data.t_temp_monitoring_stock_suggest_po a set a.signature = concat(replace(right(a.created_date,8),':',''),'-',md5(concat(a.kode,a.kodeprod)))");

        // $insert_log_user = "
        //     insert into db_master_data.t_temp_monitoring_stock_log_user
        //     select a.kode, a.kodeprod, a.estimasi_sales, a.stock_ideal, a.created_date, a.last_updated_by, a.last_updated, a.last_updated_by
        //     from db_master_data.t_temp_monitoring_stock_suggest_po a
        // ";
        // $proses_insert_log_user = $this->db->query($insert_log_user);
        


    }

    public function get_monitoring_stock_suggest_po_harian($created_date){
        $truncate_suggest_po = "truncate db_master_data.t_temp_monitoring_stock_suggest_po";
        $proses_truncate_suggest_po = $this->db->query($truncate_suggest_po);
        $sql = "
        insert into db_master_data.t_temp_monitoring_stock_suggest_po
        select 	a.branch_name, a.nama_comp, a.kode, a.supp, a.namasupp,a.kodeprod, a.namaprod, a.isisatuan,a.berat,a.volume,
		        a.harga, a.lastupload, a.rata, a.stock_akhir_unit, a.git, a.total_stock, a.sales_berjalan, a.estimasi_sales,
		        a.potensi_sales, a.stock_berjalan, a.doi, a.stock_ideal, a.stock_ideal_unit, 
		        if(a.stock_ideal_unit - a.stock_berjalan < 0,0,a.stock_ideal_unit - a.stock_berjalan) as pp,
		        a.stock_berjalan + if(a.stock_ideal_unit - a.stock_berjalan < 0,0,a.stock_ideal_unit - a.stock_berjalan) as estimasi_saldo_akhir, 
                round((a.stock_berjalan + if(a.stock_ideal_unit - a.stock_berjalan < 0,0,a.stock_ideal_unit - a.stock_berjalan)) / a.rata * 30) as estimasi_doi_akhir,
                round((if(a.stock_ideal_unit - a.stock_berjalan < 0,0,a.stock_ideal_unit - a.stock_berjalan)) / a.isisatuan) as pp_karton,
                round(((if(a.stock_ideal_unit - a.stock_berjalan < 0,0,a.stock_ideal_unit - a.stock_berjalan)) / a.isisatuan) * a.berat) as pp_kg,
                round(((if(a.stock_ideal_unit - a.stock_berjalan < 0,0,a.stock_ideal_unit - a.stock_berjalan)) / a.isisatuan) * a.volume,2) as pp_volume,
                ((round(if(a.stock_ideal_unit - a.stock_berjalan < 0,0,a.stock_ideal_unit - a.stock_berjalan) / a.isisatuan)) * a.isisatuan * a.harga) as pp_value, '' as signature,'$created_date', '' as last_updated, '' as last_updated_by
        from db_master_data.t_temp_monitoring_stock_doi a
        where a.created_date = '$created_date'
        ";
        $proses = $this->db->query($sql);

        echo "<pre>";
        print_r($sql);
        echo "</pre>";
        echo "<hr> : ".$proses;

        if ($proses) {
            
            $this->db->query("update db_master_data.t_temp_monitoring_stock_suggest_po a set a.signature = concat(replace(right(a.created_date,8),':',''),'-',md5(concat(a.kode,a.kodeprod)))");

            echo "<hr>xxxxx";
        }


        $insert_log_user = "
            insert into db_master_data.t_temp_monitoring_stock_log_user
            select a.kode, a.kodeprod, a.estimasi_sales, a.stock_ideal, a.created_date, a.last_updated_by, a.last_updated, a.last_updated_by
            from db_master_data.t_temp_monitoring_stock_suggest_po a
            where a.created_date = '$created_date'
        ";
        $proses_insert_log_user = $this->db->query($insert_log_user);
        


    }

    public function get_customer_intrafood(){
        //query insert
        date_default_timezone_set('Asia/Jakarta'); 
        $date = date_create(date('Y-m-d H:i:s'));
        date_add($date, date_interval_create_from_date_string('-7 hours'));
        $tgl = date_format($date, 'Y-m-d H:i:s'); 

        $tahun_sekarang = date('Y');
        $bulan_sekarang = date('m');

        $truncate = "truncate db_master_data.t_temp_customer_intrafood";
        $proses_truncate = $this->db->query($truncate);
        $truncate = "truncate db_master_data.t_customer_intrafood";
        $proses_truncate = $this->db->query($truncate);

        $sql = "
            insert into db_master_data.t_temp_customer_intrafood
            select concat(a.kode_comp,a.kode_lang) as kode
            from data$tahun_sekarang.fi a
            where bulan = $bulan_sekarang and kodeprod in (
                select kodeprod
                from mpm.tabprod a
                where SUPP = 012
            )
            union ALL
            select concat(a.kode_comp,a.kode_lang) as kode
            from data$tahun_sekarang.ri a
            where bulan = $bulan_sekarang and kodeprod in (
                select kodeprod
                from mpm.tabprod a
                where SUPP = 012
            )
            GROUP BY kode
        ";
        $proses = $this->db->query($sql);
        
        $sql_final = "
        insert into db_master_data.t_customer_intrafood
        select b.*
        from db_master_data.t_temp_customer_intrafood a LEFT JOIN
        (
            select *
            from db_master_data.t_customer a
        )b on a.kode = b.kode_lang
            "; 
        $proses_final = $this->db->query($sql_final);

        //fungsi export csv, simpan ke folder suffy
        redirect('master_data/export_customer_intrafood');
        
    }

    public function get_po_outstanding_deltomed(){
        date_default_timezone_set('Asia/Jakarta'); 
        $date = date_create(date('Y-m-d H:i:s'));
        date_add($date, date_interval_create_from_date_string('-7 hours'));
        $tgl = date_format($date, 'Y-m-d H:i:s'); 
        // $bln_1 = date('Y-m-d', strtotime($tgl));
        $tahun_sekarang = date('Y', strtotime($tgl));
        $bulan_sekarang = date('m', strtotime($tgl));

        // $tahun_sekarang = 2021;

        $kunci_traffic = "update db_temp.t_traffic a set a.status = 1, a.created_date = '$tgl' where a.id_menu = 77";
        $this->db->query($kunci_traffic);

        
        $this->db->query("truncate db_po.t_temp_report_po_update");

        // deltomed
        $this->db->query("truncate db_po.t_temp_do_po_outstanding");
        $this->db->query("delete from db_po.t_po_outstanding_deltomed where year(tglpo) = 2022");
        // $this->db->query("delete from db_po.t_po_outstanding_deltomed where month(tglpo) in ($bulan_sekarang)");
        
        // us
        $this->db->query("truncate db_po.t_temp_do_po_outstanding_us");
        $this->db->query("delete from db_po.t_po_outstanding_us where year(tglpo) = 2022");
        // $this->db->query("delete from db_po.t_po_outstanding_us where month(tglpo) in ($bulan_sekarang)");
        
        // intrafood
        $this->db->query("delete from db_po.t_po_outstanding_intrafood where year(tglpo) = 2022");
        // $this->db->query("delete from db_po.t_po_outstanding_intrafood where month(tglpo) in ($bulan_sekarang)");

        // marguna
        $this->db->query("delete from db_po.t_po_outstanding_marguna where year(tglpo) = 2022");
        $this->db->query("delete from db_po.t_po_outstanding_jaya where year(tglpo) = 2022");
        $this->db->query("delete from db_po.t_po_outstanding_strive where year(tglpo) = 2022");
        $this->db->query("delete from db_po.t_po_outstanding_hni where year(tglpo) = 2022");
        
        $insert_do_deltomed = "
        insert into db_po.t_temp_do_po_outstanding
        select 	a.nodo, a.kodedp, a.company, a.kodeprod_delto, a.namaprod, a.qty, a.nopo, 
                str_to_date(a.tgldo,'%d/%m/%Y') as tgldo,'$tgl',297
        from db_po.t_do_deltomed a
        where year(str_to_date(a.tgldo,'%d/%m/%Y')) >= $tahun_sekarang
        ";
        $this->db->query($insert_do_deltomed);

        $insert_do_us = "
        insert into db_po.t_temp_do_po_outstanding_us
        select 	a.nodo, a.tgldo, a.kodeprod,a.nopo,
                if(b.satuan_box is null, a.qty_pemenuhan, a.qty_pemenuhan * b.satuan_box) as qty_pemenuhan,
                '$tgl',297
        FROM
        (
            select 	a.nodo, a.tgldo,
                    a.kodeprod, sum(a.banyak) as qty_pemenuhan, a.nopo
            from 	db_po.t_do_us a
            where year(a.tgldo) >= $tahun_sekarang
            GROUP BY a.nopo, a.kodeprod
        )a LEFT JOIN
        (
            select a.kodeprod, a.satuan_box, a.`status`
            from db_produk.t_product_po a
            where a.`status` = 1
        )b on a.kodeprod = b.kodeprod
        ";
        $this->db->query($insert_do_us);

        $insert_po_nasional = "
        INSERT INTO db_po.t_temp_report_po_update
        select 	'', a.id, b.id as id_po_detail,a.supp, a.grup, a.nopo, a.tglpo, a.nodo, a.tgldo, a.tglpesan,
                a.created, a.modified, a.created_by, a.modified_by, a.tipe, a.`open`, a.open_by, a.open_date,
                a.company, a.npwp, a.email, a.alamat, a.ambil, a.note, a.note_acc, a.`status`, a.status_approval,
                a.alasan_approval, a.po_ref, a.`lock`, a.kode_alamat, b.kodeprod, b.namaprod, 
                b.banyak, b.banyak_karton, b.`backup`, b.harga, b.kode_prc, b.berat, b.volume, b.stock_akhir, 
                b.rata, b.git, b.doi, b.status_terima, b.tanggal_terima, b.tanggal_terima_created_date, a.userid, a.deleted,297,'$tgl'
        from 	mpm.po a INNER JOIN 
        (
                select a.*
                from mpm.po_detail a
                where a.deleted = 0
        )b on a.id = b.id_ref 
        where	 a.deleted = 0 and b.deleted = 0 and
        left(a.nopo,4) <> '/MPM' and 
        a.nopo not like '%batal%' and year(a.tglpo) >= $tahun_sekarang 
        ";
        $this->db->query($insert_po_nasional);
        echo "<pre><br><br><br>";
        print_r($insert_po_nasional);
        echo "</pre>";

        $insert_po_outstanding_deltomed = "
        insert into db_po.t_po_outstanding_deltomed
        select 	a.branch_name, a.nama_comp,a.company, a.tglpo, a.nopo,a.tipe, a.kodeprod, a.namaprod, 
                a.banyak as qty_po, b.qty as qty_pemenuhan, a.harga,(a.banyak*a.harga) as value_po,(b.qty*a.harga) as value_pemenuhan,
                a.berat, b.tgldo, b.nodo, (b.qty / a.banyak) * 100 as fulfilment,
                datediff(b.tgldo,DATE_FORMAT(a.tglpo,'%Y-%m-%d')) as lead_time_proses_do,
                a.po_ref, a.tanggal_terima, datediff(a.tanggal_terima,b.tgldo) as leadtime_proses_kirim,
                (a.banyak - b.qty) as outstanding_po,a.kode_alamat,'$tgl', 297
        from
        (
            select 	d.branch_name, d.nama_comp, a.nopo, a.tglpo, a.tipe, a.userid, a.company, a.alamat, 
                    a.kodeprod, a.banyak, a.harga, e.namaprod,e.kodeprod_deltomed, 
                    a.po_ref, a.berat, a.status_terima, a.tanggal_terima, a.kode_alamat
            from
            (
                select 	a.nopo, a.tglpo, a.tipe, a.userid, a.company, a.alamat, 
                        a.kodeprod, a.banyak, a.harga, a.po_ref, (a.berat * a.banyak_karton) as berat, a.status_terima, a.tanggal_terima, a.kode_alamat
                from 	db_po.t_temp_report_po_update a  
                where   a.supp ='001' and year(a.tglpo) in ($tahun_sekarang)
                        
            )a LEFT JOIN
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
                    where tahun = $tahun_sekarang and a.`status` = 1
                )b on a.kode = b.kode
                GROUP BY kode_comp
            )d on c.username = d.kode_comp LEFT JOIN
            (
                select  a.kodeprod, a.namaprod, a.kodeprod_deltomed
                from    mpm.tabprod a
                where   supp = 001
            )e on a.kodeprod = e.kodeprod
        )a LEFT JOIN 
        (
            select a.nodo, a.kodedp, a.company, a.kodeprod_deltomed, a.namaprod, a.qty, a.nopo, tgldo
            from db_po.t_temp_do_po_outstanding a
        )b on a.nopo = b.nopo and a.kodeprod_deltomed = b.kodeprod_deltomed 
        order by nama_comp, kodeprod
        ";
        $this->db->query($insert_po_outstanding_deltomed);

        $insert_po_outstanding_us = "
        insert into db_po.t_po_outstanding_us
        select 	a.company, a.branch_name, a.nama_comp, date(a.tglpo) as tglpo,a.nopo,a.tipe, a.kodeprod,  
                a.namaprod, a.banyak as qty_po, a.berat, a.volume,
                b.qty_pemenuhan,b.tgldo, b.nodo, (b.qty_pemenuhan / a.banyak * 100) as fulfilment,
                datediff(b.tgldo,DATE_FORMAT(a.tglpo,'%Y-%m-%d')) as lead_time_proses_do,  
                a.tanggal_terima, datediff(a.tanggal_terima, b.tgldo) as leadtime_proses_kirim, 
                (a.banyak - b.qty_pemenuhan) as outstanding_po, a.kode_alamat,'$tgl',297
        FROM 
        ( 
            select 	d.branch_name, d.nama_comp, 
                    a.nopo, a.tglpo, a.tipe, 
                    a.userid, a.company, a.alamat, 
                    a.kodeprod, a.banyak, a.harga, e.namaprod,a.po_ref,a.tanggal_terima, a.kode_alamat,a.berat,a.volume
            from
            (
                select 	a.nopo, a.tglpo, a.tipe, 
                        a.userid, a.company, a.alamat, 
                        a.kodeprod, a.banyak, a.harga, a.po_ref, a.tanggal_terima, a.kode_alamat,a.berat,a.volume
                from 	db_po.t_temp_report_po_update a
                where    a.supp ='005' and year(a.tglpo) in ($tahun_sekarang)
            )a LEFT JOIN
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
                    where tahun = $tahun_sekarang and a.`status` = 1
                )b on a.kode = b.kode
                GROUP BY kode_comp
            )d on c.username = d.kode_comp LEFT JOIN
            (
                select a.kodeprod, a.namaprod
                from mpm.tabprod a
            )e on a.kodeprod = e.kodeprod
        )a LEFT JOIN
        (
            select 	a.nodo, a.tgldo, a.kodeprod,a.nopo, qty_pemenuhan
            FROM db_po.t_temp_do_po_outstanding_us a	
        )b on a.nopo = b.nopo and a.kodeprod = b.kodeprod
        order by nama_comp, kodeprod
        ";
        echo "<pre>";
        print_r($insert_po_outstanding_us);
        echo "</pre>";
        $this->db->query($insert_po_outstanding_us);

        // $insert_po_outstanding_intrafood = "
        // insert into db_po.t_po_outstanding_intrafood
        // select 	a.id, d.branch_name,d.nama_comp, DATE_FORMAT(a.tglpo,'%Y-%m-%d') as tglpo,a.nopo, a.tipe,
		// 		b.kodeprod,b.namaprod,sum(b.banyak_karton) as qty_po, e.total_karton as qty_pemenuhan,
		// 		e.tanggal_kirim as tanggal_kirim,e.tanggal_tiba as tanggal_tiba,
		// 		(if(e.total_karton = null,0,e.total_karton) / sum(b.banyak_karton) * 100 ) as fulfilment, 
		// 		datediff(e.tanggal_tiba,DATE_FORMAT(a.tglpo,'%Y-%m-%d')) as lead_time, f.status_closed, f.color, 
        //         b.tanggal_terima, datediff(b.tanggal_terima, e.tanggal_kirim) as leadtime_proses_kirim, 
        //         (sum(b.banyak_karton) - e.total_karton) as outstanding_po, a.kode_alamat, '$tgl', 297
        // from mpm.po a INNER JOIN 
        // (
        //     select a.id_ref, a.kodeprod, a.banyak, a.banyak_karton, a.berat, a.namaprod, a.tanggal_terima
        //     from mpm.po_detail a
        //     where a.deleted = 0
        // )b on a.id = b.id_ref LEFT JOIN
        // (
        //     select a.id, a.username
        //     from mpm.`user` a
        // )c on a.userid = c.id LEFT JOIN
        // (
        //     select a.kode,a.branch_name,a.nama_comp,a.kode_comp
        //     from
        //     (
        //         select concat(a.kode_comp,a.nocab) as kode, a.branch_name, a.nama_comp, a.kode_comp
        //         from mpm.tbl_tabcomp a
        //         where a.`status` = 1
        //         GROUP BY kode
        //     )a INNER JOIN
        //     (
        //         select concat(a.kode_comp,a.nocab) as kode
        //         from db_dp.t_dp a
        //         where tahun = $tahun_sekarang and a.`status` = 1
        //     )b on a.kode = b.kode
        //     GROUP BY kode_comp
        // )d on c.username = d.kode_comp LEFT JOIN
        // (
        //     SELECT 	a.id_po, a.kodeprod, sum(a.jumlah_unit) as total_unit, sum(a.jumlah_karton) as total_karton, 
        //             a.tanggal_kirim, a.tanggal_tiba, a.nama_ekspedisi, a.keterangan, a.status_pemenuhan, a.status_closed
        //     from mpm.t_asn a
        //     GROUP BY a.id_po, a.kodeprod
        // )e on a.id = e.id_po and b.kodeprod = e.kodeprod left join 
        // (
        //     select 	a.id,f.status_closed, if(e.total_produk_po is null, 'red', if(e.total_karton_po = e.total_karton_asn, 'green', 'yellow')) as color
        //     from mpm.po a INNER JOIN 
        //     (
        //         select a.id_ref, a.kodeprod, a.banyak, a.banyak_karton, a.berat, a.namaprod
        //         from mpm.po_detail a
        //         where a.deleted = 0
        //     )b on a.id = b.id_ref LEFT JOIN
        //     (
        //         select a.id,a.id_po,a.total_unit_po,a.total_unit_asn,a.total_produk_po,a.total_produk_asn, a.total_karton_po, a.total_karton_asn
        //         from mpm.t_cek_asn a
        //         where a.id = (
        //             select max(b.id)
        //             from mpm.t_cek_asn b
        //             where a.id_po = b.id_po
        //         )
        //     )e on a.id = e.id_po LEFT JOIN
        //     (
        //         select a.id_po,a.status_closed
        //         from mpm.t_asn a
        //         GROUP BY a.id_po
        //     )f on a.id = f.id_po
        //     where supp=012 and a.deleted = 0 and a.nopo not like '/mpm%' and a.nopo not like '%batal%' and year(a.tglpo) in ($tahun_sekarang)
        //     GROUP BY a.id
        //     ORDER BY a.id DESC
        // )f on a.id = f.id
        // where supp=012 and a.deleted = 0 and a.nopo not like '/mpm%' and a.nopo not like '%batal%' and 
        // year(a.tglpo) in ($tahun_sekarang)
        // GROUP BY a.id, b.kodeprod
        // ORDER BY a.id DESC
        // ";

        $insert_po_outstanding_intrafood = "
        insert into db_po.t_po_outstanding_intrafood
        select 	a.id_ref,b.branch_name, b.nama_comp, a.company, date(a.tglpo) as tglpo,
                a.nopo,a.tipe,a.kodeprod,a.namaprod,a.banyak,
                a.banyak_karton, if(c.pemenuhan_unit is null, 0, c.pemenuhan_unit) as pemenuhan_unit, 
                if(c.pemenuhan_karton is null, 0, c.pemenuhan_karton) as pemenuhan_karton, c.tanggal_kirim, c.tanggal_tiba, 
                (if(c.pemenuhan_unit is null, 0, c.pemenuhan_unit) / a.banyak * 100) as fulfilment_unit, 
                (if(c.pemenuhan_karton is null, 0, c.pemenuhan_karton) / a.banyak_karton * 100) as fulfilment_karton,
                datediff(c.tanggal_tiba,DATE_FORMAT(a.tglpo,'%Y-%m-%d')) as lead_time, c.status_closed, a.tanggal_terima,
                datediff(a.tanggal_terima, c.tanggal_kirim) as leadtime_proses_kirim, 
                (a.banyak - if(c.pemenuhan_unit is null, 0, c.pemenuhan_unit)) as outstanding_po_unit,
                (a.banyak_karton - if(c.pemenuhan_karton is null, 0, c.pemenuhan_karton)) as outstanding_po_karton, a.kode_alamat,'$tgl', 297
        from
        (
            select 	a.id, a.id_ref, a.id_po_detail, a.supp, a.grup, a.nopo, a.tglpo, a.nodo, a.tgldo, 
                    a.tglpesan, a.created, a.tipe, a.company, a.alamat, a.kode_alamat, 
                    a.kodeprod, a.namaprod, a.banyak, a.banyak_karton, 
                    a.harga, a.kode_prc, a.berat, a.volume, a.stock_akhir, a.rata, a.git, a.doi, 
                    a.status_terima, a.tanggal_terima, a.userid, a.deleted
            from db_po.t_temp_report_po_update a
            where a.supp = 012
        )a LEFT JOIN
        (
            select	concat(a.kode_comp, a.nocab) as site_code, a.branch_name, a.nama_comp
            from	mpm.tbl_tabcomp a
            where 	a.`status` = 1
            group by site_code
        )b on a.kode_alamat = b.site_code LEFT JOIN
        (
            select 	a.id_po, a.no_asn, a.batch_number, a.nodo, a.ed, 
                    a.kodeprod, sum(a.jumlah_unit) as pemenuhan_unit, sum(a.jumlah_karton) as pemenuhan_karton, a.status_pemenuhan,
                    a.tanggal_kirim, a.nama_ekspedisi, a.est_lead_time, a.tanggal_tiba, a.keterangan, a.status_closed, a.signature,
                    a.created_date, a.created_by, a.last_updated, a.last_updated_by
            from    mpm.t_asn a
            GROUP BY a.id_po, a.kodeprod
        )c on a.id_ref = c.id_po and a.kodeprod = c.kodeprod
        ";

        echo "<pre>";
        print_r($insert_po_outstanding_intrafood);
        echo "</pre>";
        $this->db->query($insert_po_outstanding_intrafood);

        echo "<pre>";
        print_r($insert_po_outstanding_intrafood);
        echo "</pre>";
        $this->db->query($insert_po_outstanding_intrafood);

        // $insert_po_outstanding_marguna = "
        // insert into db_po.t_po_outstanding_marguna
        // select 	a.id, d.branch_name,d.nama_comp,a.company, DATE_FORMAT(a.tglpo,'%Y-%m-%d') as tglpo,a.nopo, a.tipe,
		// 		b.kodeprod,b.namaprod,sum(b.banyak_karton) as qty_po, a.kode_alamat, '$tgl', 297
        // from mpm.po a INNER JOIN 
        // (
        //     select a.id_ref, a.kodeprod, a.banyak, a.banyak_karton, a.berat, a.namaprod, a.tanggal_terima
        //     from mpm.po_detail a
        //     where a.deleted = 0
        // )b on a.id = b.id_ref LEFT JOIN
        // (
        //     select a.id, a.username
        //     from mpm.`user` a
        // )c on a.userid = c.id LEFT JOIN
        // (
        //     select a.kode,a.branch_name,a.nama_comp,a.kode_comp
        //     from
        //     (
        //         select concat(a.kode_comp,a.nocab) as kode, a.branch_name, a.nama_comp, a.kode_comp
        //         from mpm.tbl_tabcomp a
        //         where a.`status` = 1
        //         GROUP BY kode
        //     )a INNER JOIN
        //     (
        //         select concat(a.kode_comp,a.nocab) as kode
        //         from db_dp.t_dp a
        //         where tahun = $tahun_sekarang and a.`status` = 1
        //     )b on a.kode = b.kode
        //     GROUP BY kode_comp
        // )d on c.username = d.kode_comp 
        // where supp=002 and a.deleted = 0 and a.nopo not like '/mpm%' and a.nopo not like '%batal%' and 
        // year(a.tglpo) in ($tahun_sekarang)
        // GROUP BY a.id, b.kodeprod
        // ORDER BY a.id DESC
        // ";

        $insert_po_outstanding_marguna = "
        insert into db_po.t_po_outstanding_marguna
        select 	a.id_ref,b.branch_name, b.nama_comp, a.company, date(a.tglpo) as tglpo,
                a.nopo,a.tipe,a.kodeprod,a.namaprod,a.banyak,
                a.banyak_karton, if(c.pemenuhan_unit is null, 0, c.pemenuhan_unit) as pemenuhan_unit, 
                if(c.pemenuhan_karton is null, 0, c.pemenuhan_karton) as pemenuhan_karton, c.tanggal_kirim, c.tanggal_tiba, 
                (if(c.pemenuhan_unit is null, 0, c.pemenuhan_unit) / a.banyak * 100) as fulfilment_unit, 
                (if(c.pemenuhan_karton is null, 0, c.pemenuhan_karton) / a.banyak_karton * 100) as fulfilment_karton,
                datediff(c.tanggal_tiba,DATE_FORMAT(a.tglpo,'%Y-%m-%d')) as lead_time, c.status_closed, a.tanggal_terima,
                datediff(a.tanggal_terima, c.tanggal_kirim) as leadtime_proses_kirim, 
                (a.banyak - if(c.pemenuhan_unit is null, 0, c.pemenuhan_unit)) as outstanding_po_unit,
                (a.banyak_karton - if(c.pemenuhan_karton is null, 0, c.pemenuhan_karton)) as outstanding_po_karton, a.kode_alamat,'$tgl', 297
        from
        (
            select 	a.id, a.id_ref, a.id_po_detail, a.supp, a.grup, a.nopo, a.tglpo, a.nodo, a.tgldo, 
                    a.tglpesan, a.created, a.tipe, a.company, a.alamat, a.kode_alamat, 
                    a.kodeprod, a.namaprod, a.banyak, a.banyak_karton, 
                    a.harga, a.kode_prc, a.berat, a.volume, a.stock_akhir, a.rata, a.git, a.doi, 
                    a.status_terima, a.tanggal_terima, a.userid, a.deleted
            from db_po.t_temp_report_po_update a
            where a.supp = 002
        )a LEFT JOIN
        (
            select	concat(a.kode_comp, a.nocab) as site_code, a.branch_name, a.nama_comp
            from	mpm.tbl_tabcomp a
            where 	a.`status` = 1
            group by site_code
        )b on a.kode_alamat = b.site_code LEFT JOIN
        (
            select 	a.id_po, a.no_asn, a.batch_number, a.nodo, a.ed, 
                    a.kodeprod, sum(a.jumlah_unit) as pemenuhan_unit, sum(a.jumlah_karton) as pemenuhan_karton, a.status_pemenuhan,
                    a.tanggal_kirim, a.nama_ekspedisi, a.est_lead_time, a.tanggal_tiba, a.keterangan, a.status_closed, a.signature,
                    a.created_date, a.created_by, a.last_updated, a.last_updated_by
            from    mpm.t_asn a
            GROUP BY a.id_po, a.kodeprod
        )c on a.id_ref = c.id_po and a.kodeprod = c.kodeprod
        ";
        echo "<pre>";
        print_r($insert_po_outstanding_marguna);
        echo "</pre>";
        $this->db->query($insert_po_outstanding_marguna);

        $insert_po_outstanding_jaya_agung = "
        insert into db_po.t_po_outstanding_jaya
        select 	a.id, d.branch_name,d.nama_comp,a.company, DATE_FORMAT(a.tglpo,'%Y-%m-%d') as tglpo,a.nopo, a.tipe,
				b.kodeprod,b.namaprod,sum(b.banyak_karton) as qty_po, a.kode_alamat, '$tgl', 297
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
                where tahun = $tahun_sekarang and a.`status` = 1
            )b on a.kode = b.kode
            GROUP BY kode_comp
        )d on c.username = d.kode_comp 
        where supp=004 and a.deleted = 0 and a.nopo not like '/mpm%' and a.nopo not like '%batal%' and 
        year(a.tglpo) in ($tahun_sekarang)
        GROUP BY a.id, b.kodeprod
        ORDER BY a.id DESC
        ";
        echo "<pre>";
        print_r($insert_po_outstanding_jaya_agung);
        echo "</pre>";
        $this->db->query($insert_po_outstanding_jaya_agung);

        $insert_po_outstanding_strive = "
        insert into db_po.t_po_outstanding_strive
        select 	a.id, d.branch_name,d.nama_comp,a.company, DATE_FORMAT(a.tglpo,'%Y-%m-%d') as tglpo,a.nopo, a.tipe,
				b.kodeprod,b.namaprod,sum(b.banyak_karton) as qty_po, a.kode_alamat, '$tgl', 297
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
                where tahun = $tahun_sekarang and a.`status` = 1
            )b on a.kode = b.kode
            GROUP BY kode_comp
        )d on c.username = d.kode_comp 
        where supp=013 and a.deleted = 0 and a.nopo not like '/mpm%' and a.nopo not like '%batal%' and 
        year(a.tglpo) in ($tahun_sekarang)
        GROUP BY a.id, b.kodeprod
        ORDER BY a.id DESC
        ";
        echo "<pre>";
        print_r($insert_po_outstanding_strive);
        echo "</pre>";
        $this->db->query($insert_po_outstanding_strive);

        $insert_po_outstanding_hni = "
        insert into db_po.t_po_outstanding_hni
        select 	a.id, d.branch_name,d.nama_comp,a.company, DATE_FORMAT(a.tglpo,'%Y-%m-%d') as tglpo,a.nopo, a.tipe,
				b.kodeprod,b.namaprod,sum(b.banyak_karton) as qty_po, a.kode_alamat, '$tgl', 297
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
                where tahun = $tahun_sekarang and a.`status` = 1
            )b on a.kode = b.kode
            GROUP BY kode_comp
        )d on c.username = d.kode_comp 
        where supp=014 and a.deleted = 0 and a.nopo not like '/mpm%' and a.nopo not like '%batal%' and 
        year(a.tglpo) in ($tahun_sekarang)
        GROUP BY a.id, b.kodeprod
        ORDER BY a.id DESC
        ";
        echo "<pre>";
        print_r($insert_po_outstanding_hni);
        echo "</pre>";
        $this->db->query($insert_po_outstanding_hni);

        $buka_traffic = "update db_temp.t_traffic a set a.status = 0, a.created_date = '$tgl' where a.id_menu = 77";
        $this->db->query($buka_traffic);

    }

    public function proses_schedule_truncate_auto(){
        mysql_query('TRUNCATE db_temp.t_temp_line_sold;');
        mysql_query('TRUNCATE db_temp.t_temp_omzet_dp;');
        mysql_query('TRUNCATE db_temp.t_temp_pengambilan;');
        mysql_query('TRUNCATE db_temp.t_temp_pengambilan_report;');
        mysql_query('TRUNCATE db_temp.t_temp_sales_per_hari;');
        mysql_query('TRUNCATE db_temp.t_temp_soprod;');
        mysql_query('TRUNCATE db_temp.t_verifikasi_harga_temp;');
        mysql_query('TRUNCATE db_temp.t_temp_outlet;');
        mysql_query('TRUNCATE db_temp.t_temp_outlet_transaksi;');
        mysql_query('TRUNCATE db_temp.t_temp_sell_out_nasional;');
        mysql_query('TRUNCATE db_menu.t_menu_temp;');
    }

    public function get_outlet(){

        date_default_timezone_set('Asia/Jakarta'); 
        $date = date_create(date('Y-m-d H:i:s'));
        date_add($date, date_interval_create_from_date_string('-7 hours'));
        $tgl = date_format($date, 'Y-m-d H:i:s'); 
        // $bln_1 = date('Y-m-d', strtotime($tgl));
        $tahun_sekarang = date('Y', strtotime($tgl));
        
        $this->db->query("truncate db_master_data.master_outlet_$tahun_sekarang");
        
        $insert_outlet = "
        insert into db_master_data.master_outlet_$tahun_sekarang
        select 		a.kode_lang,a.nama_lang,a.kodesalur,c.jenis, c.`group`, 
                    a.kode_type, d.nama_type, d.sektor, d.segment, 
                    a.alamat, a.id_provinsi,a.nama_provinsi,
                    a.id_kota,a.nama_kota,a.id_kecamatan,a.nama_kecamatan,a.id_kelurahan,a.nama_kelurahan,
                    a.KODEPOS, a.TELP, a.tipe_bayar, a.credit_limit, 
                    b.kode,b.branch_name,b.nama_comp,
                    b.sub,b.kode_comp,b.nocab, '$tgl'
        FROM
        (
            select 	concat(a.kode_comp,a.kode_lang) as kode_lang, 
                    a.NAMA_LANG as nama_lang,a.KODESALUR as kodesalur,
                    a.KODE_TYPE as kode_type,a.ALAMAT1 as alamat, 
                    a.id_provinsi, a.nama_provinsi,
                    a.id_kota, a.nama_kota,
                    a.id_kecamatan, a.nama_kecamatan,
                    a.id_kelurahan, a.nama_kelurahan,
                    concat(a.kode_comp,a.nocab) as kode, a.credit_limit,a.status_payment, a.KODEPOS, a.TELP, a.tipe_bayar
            from    data$tahun_sekarang.tblang a
            GROUP BY concat(a.kode_comp,a.kode_lang)
        )a LEFT JOIN
        (
            select a.kode,a.branch_name,a.nama_comp,a.kode_comp,a.nocab,a.sub
            FROM
            (
                select concat(a.kode_comp,a.nocab) as kode, a.branch_name, a.nama_comp,a.kode_comp,a.nocab, a.sub
                from mpm.tbl_tabcomp a
                where a.`status` = 1
                GROUP BY kode
            )a INNER JOIN
            (
                select concat(a.kode_comp,a.nocab) as kode
                from db_dp.t_dp a
                where a.tahun = $tahun_sekarang and `status`= 1
            )b on a.kode = b.kode
            ORDER BY nocab
        )b on a.kode = b.kode LEFT JOIN
        (
            select a.kode, a.jenis, a.`group`
            from mpm.tbl_tabsalur a
        )c on a.kodesalur = c.kode LEFT JOIN
        (
            select a.kode_type, a.nama_type, a.sektor, a.segment
            from mpm.tbl_bantu_type a
        )d on a.kode_type = d.kode_type

        ";

        $this->db->query($insert_outlet);


    }
    
    // insert ke db_temp.t_m_customer

    public function insert_t_m_customer(){
        
        $serverName = "backup.muliaputramandiri.com"; //serverName\instanceName, portNumber (default is 1433)
        $connectionInfo = array("Database" => "", "UID" => "sa", "PWD" => "mpm12345");
        $conn = sqlsrv_connect($serverName, $connectionInfo);
        if ($conn) {
            echo "<br><br><pre>Koneksi dengan Server SDS berhasil </pre><br />";
            $this->db->query('truncate db_temp.t_m_customer');

                $m_customer = "
                    select customerid, nama_customer, prefix, alamat, kelurahanid, kecamatanid, kotaid, propinsiid,
                            kodepos, telp, fax, email, segmentid, typeid, area_kirim, ktp, kk, pln, nama_dihubungi,
                            alamat_dihubungi, telp_dihubungi, hubungan, siteid, branchid, companyid, headofficeid, 
                            blacklist, prospect, datecreate, member, regionalid, areaid, areakirimid, tipe_tax, classid,
                            member_proses, debitur_id, subareaid, depo_id, group_id, group_descr, top_cust, ppn, materia, 
                            khusus, view_faktur, spot_id, latitude, longitude, Alatitude, Alongitude, mcc, mnc, lac, cid,
                            map_uli_cust, status_outlet, std, denah, id_ktp, tt, flag_multi_ob, status_fjp, customer_maap_delto,
                            customer_maap_us, flag_batch, groupid_discount, customerid_perfeti, Show_alamat_pkp, sales, target,
                            salesVstarget, flag_konsinyasi, SUB_CUSTOMER, KATEGORI_CUSTOMER, inkasoid, nama_inkaso, kolektorid,
                            nama_kolektor, status_ob_bi, fugu_status, limit_kredit
                    from    dbsls.dbo.m_customer  
                    ";
                $query = sqlsrv_query($conn, $m_customer); 
                if ($query) {
                    while ($data = sqlsrv_fetch_array($query)){
                        $data = array(
                            'customerid'    => $data['customerid'],
                            'nama_customer' => $data['nama_customer'],
                            'prefix'    => $data['prefix'],
                            'alamat'    => $data['alamat'],
                            'kelurahanid'   => $data['kelurahanid'],
                            'kecamatanid'   => $data['kecamatanid'],
                            'kotaid'    => $data['kotaid'],
                            'propinsiid'    => $data['propinsiid'],
                            'kodepos'   => $data['kodepos'],
                            'telp'  => $data['telp'],
                            'fax'   => $data['fax'],
                            'email' => $data['email'],
                            'segmentid' => $data['segmentid'],
                            'typeid'    => $data['typeid'],
                            'area_kirim'    => $data['area_kirim'],
                            'ktp'   => $data['ktp'],
                            'kk'    => $data['kk'],
                            'pln'   => $data['pln'],
                            'nama_dihubungi'    => $data['nama_dihubungi'],
                            'alamat_dihubungi'  => $data['alamat_dihubungi'],
                            'telp_dihubungi'    => $data['telp_dihubungi'],
                            'hubungan'  => $data['hubungan'],
                            'siteid'    => $data['siteid'],
                            'branchid'  => $data['branchid'],
                            'companyid' => $data['companyid'],
                            'headofficeid'  => $data['headofficeid'],
                            'blacklist' => $data['blacklist'],
                            'prospect'  => $data['prospect'],
                            'datecreate'    => $data['datecreate']->format('Y-m-d H:i:s'),
                            'member'    => $data['member'],
                            'regionalid'    => $data['regionalid'],
                            'areaid'    => $data['areaid'],
                            'areakirimid'   => $data['areakirimid'],
                            'tipe_tax'  => $data['tipe_tax'],
                            'classid'   => $data['classid'],
                            'member_proses' => $data['member_proses'],
                            'debitur_id'    => $data['debitur_id'],
                            'subareaid' => $data['subareaid'],
                            'depo_id'   => $data['depo_id'],
                            'group_id'  => $data['group_id'],
                            'group_descr'   => $data['group_descr'],
                            'top_cust'  => $data['top_cust'],
                            'ppn'   => $data['ppn'],
                            'materia'   => $data['materia'],
                            'khusus'    => $data['khusus'],
                            'view_faktur'   => $data['view_faktur'],
                            'spot_id'   => $data['spot_id'],
                            'latitude'  => $data['latitude'],
                            'longitude' => $data['longitude'],
                            'Alatitude' => $data['Alatitude'],
                            'Alongitude'    => $data['Alongitude'],
                            'mcc'   => $data['mcc'],
                            'mnc'   => $data['mnc'],
                            'lac'   => $data['lac'],
                            'cid'   => $data['cid'],
                            'map_uli_cust'  => $data['map_uli_cust'],
                            'status_outlet' => $data['status_outlet'],
                            'std'   => $data['std'],
                            'denah' => $data['denah'],
                            'id_ktp'    => $data['id_ktp'],
                            'tt'    => $data['tt'],
                            'flag_multi_ob' => $data['flag_multi_ob'],
                            'status_fjp'    => $data['status_fjp'],
                            'customer_maap_delto'   => $data['customer_maap_delto'],
                            'customer_maap_us'  => $data['customer_maap_us'],
                            'flag_batch'    => $data['flag_batch'],
                            'groupid_discount'  => $data['groupid_discount'],
                            'customerid_perfeti'    => $data['customerid_perfeti'],
                            'Show_alamat_pkp'   => $data['Show_alamat_pkp'],
                            'sales' => $data['sales'],
                            'target'    => $data['target'],
                            'salesVstarget' => $data['salesVstarget'],
                            'flag_konsinyasi'   => $data['flag_konsinyasi'],
                            'SUB_CUSTOMER'  => $data['SUB_CUSTOMER'],
                            'KATEGORI_CUSTOMER' => $data['KATEGORI_CUSTOMER'],
                            'inkasoid'  => $data['inkasoid'],
                            'nama_inkaso'   => $data['nama_inkaso'],
                            'kolektorid'    => $data['kolektorid'],
                            'nama_kolektor' => $data['nama_kolektor'],
                            'status_ob_bi'  => $data['status_ob_bi'],
                            'fugu_status'   => $data['fugu_status'],
                            'limit_kredit'  => $data['limit_kredit'],
                        );
                        
                        $proses = $this->db->insert('db_temp.t_m_customer',$data);
                    }
                    if($proses){
                        $this->insert_t_sales_master();
                }
            }
        }
    }

    // insert ke db_temp.t_sales_master

    public function insert_t_sales_master(){
        $f=strtotime("-6 Months");
        $tahun = date('Y-m-d', $f) ;
        // var_dump($tahun);die;
        
        $serverName = "backup.muliaputramandiri.com"; //serverName\instanceName, portNumber (default is 1433)
        $connectionInfo = array("Database" => "", "UID" => "sa", "PWD" => "mpm12345");
        $conn = sqlsrv_connect($serverName, $connectionInfo);
        if ($conn) {
            echo "<br><br><pre>Koneksi dengan Server SDS berhasil </pre><br />";
            $this->db->query('truncate db_temp.t_sales_master');

            $t_sales_master = "
                    SELECT siteid, no_sales, tanggal, flag_dealer, salesmanid, supervisorid, customerid,
                            type_bayar, term_payment, garansi, term_garansi, ref, keterangan, no_polisi, no_rangka,
                            nilai_invoice, userid, tgl_created, status, proses, counter_print, retur, user_app, 
                            CASE
                            WHEN date_app > 1 THEN date_app
                            ELSE 0000-00-00
                            END as date_app, flag_masalah, flag_overdue, flag_overlimit, flag_blacklist, flag_top, flag_stock,
                            program, dp_value, CASE
                            WHEN tgl_periode > 1 THEN tgl_periode
                            ELSE 0000-00-00
                            END as tgl_periode, tipe_sales, tipe_trans, tipe_tax, no_seri_pajak, counter_seri_pajak,
                            ex_no_sales, p_status, proses_sp, tipe_order, status_retur, categoryid, created_date, 
                            CASE
                            WHEN tgl_po > 1 THEN tgl_po
                            ELSE 0000-00-00
                            END as tgl_po,
                            debitur_id, b_no_sales, flag_sp_tt, flag_konsinyasi, tanggal_fjp_bi, areaid, area_desc, kl
                    FROM dbsls.dbo.t_sales_master
                    WHERE created_date >= '$tahun'
                    ";
            $query2 = sqlsrv_query($conn, $t_sales_master); 
            if ($query2) {
                while ($data = sqlsrv_fetch_array($query2)){
                    $data = array(
                        'siteid'  => $data['siteid'],
                        'no_sales'=> $data['no_sales'], 
                        'tanggal' => $data['tanggal']->format('Y-m-d H:i:s'), 
                        'flag_dealer' => $data['flag_dealer'], 
                        'salesmanid'  => $data['salesmanid'], 
                        'supervisorid'=> $data['supervisorid'], 
                        'customerid'  => $data['customerid'],   
                        'type_bayar'  => $data['type_bayar'], 
                        'term_payment'=> $data['term_payment'], 
                        'garansi' => $data['garansi'], 
                        'term_garansi'=> $data['term_garansi'], 
                        'ref'=> $data['ref'], 
                        'keterangan'  => $data['keterangan'], 
                        'no_polisi'   => $data['no_polisi'], 
                        'no_rangka'   => $data['no_rangka'],
                        'nilai_invoice'   => $data['nilai_invoice'], 
                        'userid'  => $data['userid'], 
                        'tgl_created' => $data['tgl_created']->format('Y-m-d H:i:s'), 
                        'status'   => $data['status'],
                        'proses'  => $data['proses'], 
                        'counter_print'   => $data['counter_print'], 
                        'retur'   => $data['retur'], 
                        'user_app'=> $data['user_app'], 
                        'date_app'=> $data['date_app']->format('Y-m-d H:i:s'), 
                        'flag_masalah'=> $data['flag_masalah'], 
                        'flag_overdue'=> $data['flag_overdue'], 
                        'flag_overlimit'  => $data['flag_overlimit'], 
                        'flag_blacklist'  => $data['flag_blacklist'], 
                        'flag_top'=> $data['flag_top'], 
                        'flag_stock'  => $data['flag_stock'],
                        'program' => $data['program'], 
                        'dp_value'=> $data['dp_value'], 
                        'tgl_periode' => $data['tgl_periode']->format('Y-m-d H:i:s'), 
                        'tipe_sales'  => $data['tipe_sales'], 
                        'tipe_trans'  => $data['tipe_trans'], 
                        'tipe_tax'=> $data['tipe_tax'], 
                        'no_seri_pajak'   => $data['no_seri_pajak'], 
                        'counter_seri_pajak'  => $data['counter_seri_pajak'],
                        'ex_no_sales' => $data['ex_no_sales'], 
                        'p_status'=> $data['p_status'], 
                        'proses_sp'   => $data['proses_sp'], 
                        'tipe_order'  => $data['tipe_order'], 
                        'status_retur'=> $data['status_retur'], 
                        'categoryid'  => $data['categoryid'], 
                        'created_date'=> $data['created_date']->format('Y-m-d H:i:s'), 
                        'tgl_po'  => $data['tgl_po']->format('Y-m-d H:i:s'),
                        'debitur_id'  => $data['debitur_id'], 
                        'b_no_sales'  => $data['b_no_sales'], 
                        'flag_sp_tt'  => $data['flag_sp_tt'], 
                        'flag_konsinyasi' => $data['flag_konsinyasi'], 
                        'tanggal_fjp_bi'  => $data['tanggal_fjp_bi'], 
                        'areaid'  => $data['areaid'], 
                        'area_desc'   => $data['area_desc'], 
                        'kl'    => $data['kl']
                    );
                    $proses =$this->db->insert('db_temp.t_sales_master',$data);
                }
                if($proses){
                   $this->insert_t_sales_detail();
                }
            }
        }
    }

     // insert ke db_temp.t_sales_detail

    public function insert_t_sales_detail(){
        $f=strtotime("-6 Months");
        $tahun = date('Y-m-d', $f) ;
        
        $serverName = "backup.muliaputramandiri.com"; //serverName\instanceName, portNumber (default is 1433)
        $connectionInfo = array("Database" => "", "UID" => "sa", "PWD" => "mpm12345");
        $conn = sqlsrv_connect($serverName, $connectionInfo);
        if ($conn) {
            echo "<br><br><pre>Koneksi dengan Server SDS berhasil </pre><br />";
            $this->db->query('truncate db_temp.t_sales_detail');

                $t_sales_detail = "
                SELECT b.siteid, b.no_sales, b.productid, b.nourut, b.type_gudang, b.sat_besar, b.sat_sedang, b.sat_kecil,
                        b.isi_besar, b.isi_sedang, b.isi_kecil, b.qty1, b.qty2, b.qty3, b.qty_kecil, b.sat_bonus, b.qty_bonus,
                        b.flag_bonus, b.flag_garansi, b.disc_persen, b.disc_rp, b.disc_value, b.beli, b.jual, b.nego, b.karoseri,
                        b.ppnbm, b.total_harga, b.flag_delete, b.jasa, b.jasa_id, b.biaya_jasa, b.flag_stock, b.no_mesin,
                        b.no_rangka, b.tahun, b.warna, b.ket_mesin, b.flag_karoseri, b.program, b.disc_cabang, b.disc_prinsipal,
                        b.disc_xtra, b.disc_cod, b.rp_cabang, b.rp_prinsipal, b.rp_xtra, b.rp_cod, b.flag_xtra, b.flag_cod, b.bonus,
                        b.net_prinsipal, b.net_xtra, b.net_cod, b.flag_special_price, b.product_desc, b.harga_beli, b.harga_grosir,
                        b.harga_retail, b.harga_motoris_retail, b.memo_disc_reguler, b.memo_disc_prinsipal, b.memo_disc_extra,
                        b.memo_disc_cod, b.memo_disc_bonus, b.batch_outlet, b.expierd, b.prinsipalid, b.prinsipal, b.nilai_order,
                        b.saldo_piutang, b.nilai_limit, b.piutang_ovedue, b.flag_limit, b.DIRECTION, b.nama_salesman, b.source_data,
                        b.tax_value, b.RP_CBP, b.RP_HNA, b.status_tipe_order, b.areaid, b.area_desc
                FROM
                (
                    SELECT no_sales 
                    FROM dbsls.dbo.t_sales_master
                    WHERE created_date >= '$tahun'
                )a 
                INNER JOIN 
                (
                    SELECT a.siteid, a.no_sales, a.productid, a.nourut, a.type_gudang, a.sat_besar, a.sat_sedang, a.sat_kecil,
                            a.isi_besar, a.isi_sedang, a.isi_kecil, a.qty1, a.qty2, a.qty3, a.qty_kecil, a.sat_bonus, a.qty_bonus,
                            a.flag_bonus, a.flag_garansi, a.disc_persen, a.disc_rp, a.disc_value, a.beli, a.jual, a.nego, a.karoseri,
                            a.ppnbm, a.total_harga, a.flag_delete, a.jasa, a.jasa_id, a.biaya_jasa, a.flag_stock, a.no_mesin,
                            a.no_rangka, a.tahun, a.warna, a.ket_mesin, a.flag_karoseri, a.program, a.disc_cabang, a.disc_prinsipal,
                            a.disc_xtra, a.disc_cod, a.rp_cabang, a.rp_prinsipal, a.rp_xtra, a.rp_cod, a.flag_xtra, a.flag_cod, a.bonus,
                            a.net_prinsipal, a.net_xtra, a.net_cod, a.flag_special_price, a.product_desc, a.harga_beli, a.harga_grosir,
                            a.harga_retail, a.harga_motoris_retail, a.memo_disc_reguler, a.memo_disc_prinsipal, a.memo_disc_extra,
                            a.memo_disc_cod, a.memo_disc_bonus, a.batch_outlet, a.expierd, a.prinsipalid, a.prinsipal, a.nilai_order,
                            a.saldo_piutang, a.nilai_limit, a.piutang_ovedue, a.flag_limit, a.DIRECTION, a.nama_salesman, a.source_data,
                            a.tax_value, a.RP_CBP, a.RP_HNA, a.status_tipe_order, a.areaid, a.area_desc
                    FROM dbsls.dbo.t_sales_detail a
                )b on a.no_sales = b.no_sales
                ";
                $query3 = sqlsrv_query($conn, $t_sales_detail); 
                if ($query3) {
                    while ($data = sqlsrv_fetch_array($query3)){
                        $data = array(
                            'siteid'    => $data['siteid'],
                            'no_sales'  => $data['no_sales'],  
                            'productid' => $data['productid'], 
                            'nourut'    => $data['nourut'],    
                            'type_gudang'   => $data['type_gudang'],   
                            'sat_besar' => $data['sat_besar'], 
                            'sat_sedang'    => $data['sat_sedang'],    
                            'sat_kecil' => $data['sat_kecil'], 
                            'isi_besar' => $data['isi_besar'],
                            'isi_sedang'    => $data['isi_sedang'],    
                            'isi_kecil' => $data['isi_kecil'], 
                            'qty1'  => $data['qty1'],  
                            'qty2'  => $data['qty2'],  
                            'qty3'  => $data['qty3'],  
                            'qty_kecil' => $data['qty_kecil'], 
                            'sat_bonus' => $data['sat_bonus'], 
                            'qty_bonus' => $data['qty_bonus'], 
                            'flag_bonus'    => $data['flag_bonus'],
                            'flag_garansi'  => $data['flag_garansi'],  
                            'disc_persen'   => $data['disc_persen'],   
                            'disc_rp'   => $data['disc_rp'],   
                            'disc_value'    => $data['disc_value'],    
                            'beli'  => $data['beli'],  
                            'jual'  => $data['jual'],  
                            'nego'  => $data['nego'],  
                            'karoseri'  => $data['karoseri'],  
                            'ppnbm' => $data['ppnbm'],
                            'total_harga'   => $data['total_harga'],   
                            'flag_delete'   => $data['flag_delete'],   
                            'jasa'  => $data['jasa'],  
                            'jasa_id'   => $data['jasa_id'],   
                            'biaya_jasa'    => $data['biaya_jasa'],    
                            'flag_stock'    => $data['flag_stock'],    
                            'no_mesin'  => $data['no_mesin'],  
                            'no_rangka' => $data['no_rangka'],  
                            'tahun' => $data['tahun'], 
                            'warna' => $data['warna'], 
                            'ket_mesin' => $data['ket_mesin'], 
                            'flag_karoseri' => $data['flag_karoseri'], 
                            'program'   => $data['program'],   
                            'disc_cabang'   => $data['disc_cabang'],   
                            'disc_prinsipal'    => $data['disc_prinsipal'],    
                            'disc_xtra' => $data['disc_xtra'],
                            'disc_cod'  => $data['disc_cod'],  
                            'rp_cabang' => $data['rp_cabang'], 
                            'rp_prinsipal'  => $data['rp_prinsipal'],  
                            'rp_xtra'   => $data['rp_xtra'],   
                            'rp_cod'    => $data['rp_cod'],    
                            'flag_xtra' => $data['flag_xtra'], 
                            'flag_cod'  => $data['flag_cod'],  
                            'bonus' => $data['bonus'], 
                            'net_prinsipal' => $data['net_prinsipal'],
                            'net_xtra'  => $data['net_xtra'],  
                            'net_cod'   => $data['net_cod'],   
                            'flag_special_price'    => $data['flag_special_price'],    
                            'product_desc'  => $data['product_desc'],  
                            'harga_beli'    => $data['harga_beli'],    
                            'harga_grosir'  => $data['harga_grosir'],  
                            'harga_retail'  => $data['harga_retail'],
                            'harga_motoris_retail'  => $data['harga_motoris_retail'],  
                            'memo_disc_reguler' => $data['memo_disc_reguler'], 
                            'memo_disc_prinsipal'   => $data['memo_disc_prinsipal'],   
                            'memo_disc_extra'   => $data['memo_disc_extra'],   
                            'memo_disc_cod' => $data['memo_disc_cod'],
                            'memo_disc_bonus'   => $data['memo_disc_bonus'],   
                            'batch_outlet'  => $data['batch_outlet'],  
                            'expierd'   => $data['expierd'],   
                            'prinsipalid'   => $data['prinsipalid'],   
                            'prinsipal' => $data['prinsipal'], 
                            'nilai_order'   => $data['nilai_order'],   
                            'saldo_piutang' => $data['saldo_piutang'],
                            'nilai_limit'   => $data['nilai_limit'],   
                            'piutang_ovedue'    => $data['piutang_ovedue'],    
                            'flag_limit'    => $data['flag_limit'],    
                            'DIRECTION' => $data['DIRECTION'], 
                            'nama_salesman' => $data['nama_salesman'], 
                            'source_data'   => $data['source_data'],   
                            'tax_value' => $data['tax_value'],
                            'RP_CBP'    => $data['RP_CBP'],    
                            'RP_HNA'    => $data['RP_HNA'],    
                            'status_tipe_order' => $data['status_tipe_order'], 
                            'areaid'    => $data['areaid'],    
                            'area_desc' => $data['area_desc']
                        );
                        $this->db->insert('db_temp.t_sales_detail',$data);
                }
            }
        }
    }

    // Data Customer Android

    public function master_customer(){
        $sql ="
            SELECT a.id, a.id_user, a.`name`, b.shop_name, b.address, b.provinsi, b.kota, a.phone, a.site_code
            FROM
            (
                SELECT a.id, a.id_user, a.`name`, a.phone, a.site_code
                FROM db_master_data.t_users a
                WHERE a.`server` = 'live'
            )a
            LEFT JOIN 
            (
                SELECT b.id, b.id_user, b.shop_name, b.address, b.provinsi, b.kota
                FROM db_master_data.t_users_address b
                WHERE b.`server` = 'live'
            ) b on a.id_user = b.id_user
            ORDER BY a.id desc
        ";
        $proses= $this->db->query($sql)->result();
        return $proses;
    }

    public function master_customer_by_ID($custID){
        $sql ="
            SELECT a.id, a.id_user, a.`name`, b.shop_name, b.address, b.provinsi, b.kota, b.kecamatan, b.kelurahan, b.kode_pos, a.phone, a.site_code, a.created_at
            FROM
            (
                SELECT a.id, a.id_user, a.`name`, a.phone, a.site_code, a.created_at
                FROM db_master_data.t_users a
                WHERE a.`server` = 'live' and id = '$custID'
            )a
            LEFT JOIN 
            (
                SELECT b.id, b.id_user, b.shop_name, b.address, b.provinsi, b.kota, b.kecamatan, b.kelurahan, b.kode_pos
                FROM db_master_data.t_users_address b
                WHERE b.`server` = 'live'
            ) b on a.id_user = b.id_user
            ORDER BY a.id_user
        ";
        $proses= $this->db->query($sql);
        return $proses;
    }

    public function site_code(){
        $year = date('Y');
        $sql = "
            SELECT a.*
            FROM
            (
                SELECT CONCAT(KODE_COMP,NOCAB) as kode, KODE_COMP, NOCAB, nama_comp FROM mpm.tabcomp a
            )a INNER JOIN
            (
                SELECT CONCAT(KODE_COMP,NOCAB) as kode, KODE_COMP, NOCAB
                FROM db_dp.t_dp
                WHERE tahun = '$year'
            )b on a.kode = b.kode
        ";
        $proses= $this->db->query($sql)->result();
        return $proses;
    }

    public function edit_datacustomer()
    {
        
        $id = $this->input->post('id');
        $kirim = $this->input->post('kirim');

        $post['name']                           =$this->input->post('name');
        $post['phone']                          =$this->input->post('phone');
        $post['site_code']                      =$this->input->post('site_code');
        $post['status_update_erp']             = '1';
        $post['last_updated']                   = date('Y-m-d H:i:s');
        $post['last_updated_erp']               = date('Y-m-d H:i:s');
        $this->db->where('id', $id);
        $this->db->update('db_master_data.t_users', $post);

        if($kirim == 'ya'){
            redirect("master_data/email_datacustomer/$id");
        }else{
            redirect("master_data/data_customer/$id");
        }

    }
    // ====================================================================================================

    // dashboard sales
    public function dashboard_sales($created_date)
    {
        $tgl = date('Y-m-d');
        $thn_sekarang = date('Y');
        $thn_lalu = date('Y', strtotime('-1 year', strtotime($tgl)));;
        // var_dump($thn_lalu);die;

        $this->db->query('truncate db_temp.t_temp_dashboard_sales');

        $sql = "
        INSERT INTO db_temp.t_temp_dashboard_sales
        SELECT a.*, sum(tot1_1) as tot1_1 ,sum(tot1_2) as tot1_2 ,sum(tot1_3) as tot1_3 ,sum(tot1_4) as tot1_4 ,sum(tot1_5) as tot1_5 ,
                sum(tot1_6) as tot1_6 ,sum(tot1_7) as tot1_7 ,sum(tot1_8) as tot1_8 ,sum(tot1_9) as tot1_9 ,sum(tot1_10) as tot1_10 ,sum(tot1_11) as tot1_11 ,
                sum(tot1_12) as tot1_12 ,sum(tot2_1) as tot2_1 ,sum(tot2_2) as tot2_2 ,sum(tot2_3) as tot2_3 ,sum(tot2_4) as tot2_4 ,sum(tot2_5) as tot2_5 ,
                sum(tot2_6) as tot2_6 ,sum(tot2_7) as tot2_7 ,sum(tot2_8) as tot2_8 ,sum(tot2_9) as tot2_9 ,sum(tot2_10) as tot2_10 ,sum(tot2_11) as tot2_11 ,
                sum(tot2_12) as tot2_12, '$created_date'
        FROM
        (
            SELECT a.kode, a.nocab, a.nama_comp, b.SUPP, b.NAMASUPP
            FROM
            (	
                SELECT CONCAT(kode_comp,nocab) as kode, nocab, nama_comp, active FROM mpm.tbl_tabcomp
                WHERE `status` = 1
                GROUP BY kode
            )a INNER JOIN
            (
                SELECT SUPP, NAMASUPP from mpm.tabsupp
                where active = 1 and supp not in ('BSP','xxx','000')
            )b
        )a 
        LEFT JOIN
        (
            SELECT kode, supp, 
                    sum(if(bulan = '01', total, 0)) as tot1_1,
                    sum(if(bulan = '02', total, 0)) as tot1_2,
                    sum(if(bulan = '03', total, 0)) as tot1_3,	
                    sum(if(bulan = '04', total, 0)) as tot1_4,
                    sum(if(bulan = '05', total, 0)) as tot1_5,
                    sum(if(bulan = '06', total, 0)) as tot1_6,
                    sum(if(bulan = '07', total, 0)) as tot1_7,
                    sum(if(bulan = '08', total, 0)) as tot1_8,		
                    sum(if(bulan = '09', total, 0)) as tot1_9,
                    sum(if(bulan = '10', total, 0)) as tot1_10,
                    sum(if(bulan = '11', total, 0)) as tot1_11,
                    sum(if(bulan = '12', total, 0)) as tot1_12
            FROM(
                    select CONCAT(KODE_COMP,NOCAB) as kode, supp, sum(a.tot1) as total, bulan
                    from	data$thn_lalu.fi a
                    GROUP BY kode, supp, bulan
                    union ALL
                    select CONCAT(KODE_COMP,NOCAB) as kode, supp, sum(a.tot1) as total, bulan
                    from	data$thn_lalu.ri a
                    GROUP BY kode, supp, bulan
                )a GROUP BY kode, supp
        )b on a.kode = b.kode  and b.supp = a.supp
        LEFT JOIN
        (
            SELECT kode, supp, 
                    sum(if(bulan = '01', total, 0)) as tot2_1,
                    sum(if(bulan = '02', total, 0)) as tot2_2,
                    sum(if(bulan = '03', total, 0)) as tot2_3,	
                    sum(if(bulan = '04', total, 0)) as tot2_4,
                    sum(if(bulan = '05', total, 0)) as tot2_5,
                    sum(if(bulan = '06', total, 0)) as tot2_6,
                    sum(if(bulan = '07', total, 0)) as tot2_7,
                    sum(if(bulan = '08', total, 0)) as tot2_8,		
                    sum(if(bulan = '09', total, 0)) as tot2_9,
                    sum(if(bulan = '10', total, 0)) as tot2_10,
                    sum(if(bulan = '11', total, 0)) as tot2_11,
                    sum(if(bulan = '12', total, 0)) as tot2_12
            FROM(
                    select CONCAT(KODE_COMP,NOCAB) as kode, supp, sum(a.tot1) as total, bulan
                    from	data$thn_sekarang.fi a
                    GROUP BY kode, supp, bulan
                    union ALL
                    select CONCAT(KODE_COMP,NOCAB) as kode, supp, sum(a.tot1) as total, bulan
                    from	data$thn_sekarang.ri a
                    GROUP BY kode, supp, bulan
                )a GROUP BY kode, supp
        )c on a.kode = c.kode  and c.supp = a.supp
        GROUP BY kode, supp
        ";

        $proses= $this->db->query($sql);
    }

    public function dashboard_stock($data)
    {
        
        $created_date = date("Y-m-d",strtotime($data['created_date']));
        $tahun = substr($created_date,0,4);
        $bulan = substr($created_date,5,2);

        // echo "<pre>";
        // echo "tahun : ".$tahun."<br>";
        // echo "bulan : ".$bulan."<br>";
        // echo "</pre>";
        // die;
        
        // $bulan = 3;

        $this->db->query('truncate db_temp.t_temp_dashboard_stock');

        $bulan_sebelumnya = $bulan - 1;
        $bulan_avg = $bulan - 6;
        if ($bulan_avg >= 0) {            
            for ($i=$bulan_avg + 1; $i <= $bulan ; $i++) { 
                $bulan_avg_x[] = $i;
            }            
            $bulan_avg_y = implode(', ', $bulan_avg_x);
            $fi = "
                select 	if(concat(a.kode_comp, a.nocab) = 'JBG46' OR concat(a.kode_comp, a.nocab) = 'MJK46','SDO46',concat(a.kode_comp, a.nocab)) as kode, tot1, banyak, kodeprod, supp
                from 	data$tahun.fi a
                where 	bulan in ($bulan_avg_y)
                union all
                select 	if(concat(a.kode_comp, a.nocab) = 'JBG46' OR concat(a.kode_comp, a.nocab) = 'MJK46','SDO46',concat(a.kode_comp, a.nocab)) as kode, tot1, banyak, kodeprod, supp
                from 	data$tahun.ri a
                where 	bulan in ($bulan_avg_y) and 
            ";
        }else{
            $tahun_avg_x = $tahun - 1;
            $bulan_avg_a = $bulan - 6;
            $bulan_avg_ax = 12 + $bulan_avg_a;
            for ($i=$bulan_avg_ax + 1; $i <= 12 ; $i++) { 
                $bulan_avg_ay[] = $i;
            }            
            $bulan_avg_a = implode(', ', $bulan_avg_ay);

            for ($i=1; $i <= $bulan ; $i++) { 
                $bulan_avg_x[] = $i;
            }            
            $bulan_avg_y = implode(', ', $bulan_avg_x);
            
            $fi = "
                select 	if(concat(a.kode_comp, a.nocab) = 'JBG46' OR concat(a.kode_comp, a.nocab) = 'MJK46','SDO46',concat(a.kode_comp, a.nocab)) as kode, tot1, banyak, kodeprod, supp
                from 	data$tahun_avg_x.fi a
                where 	bulan in ($bulan_avg_a)
                union all
                select 	if(concat(a.kode_comp, a.nocab) = 'JBG46' OR concat(a.kode_comp, a.nocab) = 'MJK46','SDO46',concat(a.kode_comp, a.nocab)) as kode, tot1, banyak, kodeprod, supp
                from 	data$tahun_avg_x.ri a
                where 	bulan in ($bulan_avg_a)
                union all
                select 	if(concat(a.kode_comp, a.nocab) = 'JBG46' OR concat(a.kode_comp, a.nocab) = 'MJK46','SDO46',concat(a.kode_comp, a.nocab)) as kode, tot1, banyak, kodeprod, supp
                from 	data$tahun.fi a
                where 	bulan in ($bulan_avg_y)
                union all
                select 	if(concat(a.kode_comp, a.nocab) = 'JBG46' OR concat(a.kode_comp, a.nocab) = 'MJK46','SDO46',concat(a.kode_comp, a.nocab)) as kode, tot1, banyak, kodeprod, supp
                from 	data$tahun.ri a
                where 	bulan in ($bulan_avg_y)
            ";
        }

        $sql = "
            insert into db_temp.t_temp_dashboard_stock
            SELECT a.kode, a.branch_name, a.nama_comp, a.supp, avg(a.doi_unit) as doi_unit
            FROM(
                select 	a.kode, b.branch_name, b.nama_comp, a.supp, (c.stok / avg_unit*30) as doi_unit, b.urutan
                from
                (
                    select 	kode, right(kode,2) as nocab, KODEPROD, sum(banyak/6) as avg_unit, supp
                    FROM
                    (  
                        $fi           
                    )a GROUP BY kode, kodeprod
                )a inner JOIN 
                (
                    select a.kode, a.branch_name, a.nama_comp, a.urutan
                    from 
                    (
                        select concat(a.kode_comp,a.nocab) as kode, a.branch_name, a.nama_comp, a.urutan
                        from mpm.tbl_tabcomp a
                        where a.status = 1
                        GROUP BY concat(a.kode_comp,a.nocab)
                    )a left join (
                        select concat(a.kode_comp, a.nocab) as kode, a.kode_comp, a.nocab
                        from db_dp.t_dp a
                        where a.tahun = $tahun and a.status = 1
                    )b on a.kode = b.kode
                )b on a.kode = b.kode LEFT JOIN
                (
                    select  nocab,kodeprod,namaprod,substr(bulan,3) as bulan,
                            sum((Saldoawal+masuk_pbk+retur_sal+retur_depo)-(sales+minta_depo)) as stok
                    from    data$tahun.st 
                    where 	substr(bulan,3) = $bulan and kode_gdg in ('pst',1)
                    group by nocab, kodeprod,bulan
                    order by kodeprod
                )c on a.nocab = c.nocab and a.kodeprod = c.kodeprod left join 
                (
                    select a.supp,a.kodeprod, a.namaprod, a.grup, a.subgroup, b.nama_group, c.nama_sub_group
                    from mpm.tabprod a LEFT JOIN 
                    (
                        select a.kode_group, a.nama_group
                        from mpm.tbl_group a
                    )b on a.grup = b.kode_group LEFT JOIN
                    (
                        select a.sub_group, a.nama_sub_group
                        from db_produk.t_sub_group a
                    )c on a.subgroup = c.sub_group
                )e on a.kodeprod = e.kodeprod
            )a
            GROUP BY kode, supp
            ORDER BY urutan
            ";
        $proses = $this->db->query($sql);
        
        // echo "<pre><br><br><br>";
        // print_r($sql);
        // echo "</pre>";
    }

    public function generate_dashboard($created_date){
        $this->db->query('truncate db_temp.t_temp_dashboard_mpm'); //herbal 2022
        $this->db->query('truncate db_temp.t_temp_dashboard_mpm_candy'); //candy 2022
        $sql = "
        insert into db_temp.t_temp_dashboard_mpm
        select b.branch_name, b.nama_comp, a.kodeprod, d.namasupp as principal, c.namaprod, a.bulan, a.unit, a.ot, '$created_date'
        from
        (
            select site_code,kodeprod,bulan,sum(unit) as unit, sum(ot) as ot
            from
            (
                select 	concat(a.kode_comp,a.nocab) as site_code, kodeprod, a.bulan, 
                        sum(a.banyak) as unit, count(distinct(concat(a.kode_comp,a.kode_lang))) as ot
                from data2022.fi a
                group by site_code, a.kodeprod, a.bulan
                union all
                select 	concat(a.kode_comp,a.nocab) as site_code, kodeprod, a.bulan, 
                        sum(a.banyak) as unit, null
                from data2022.ri a
                group by site_code, a.kodeprod, a.bulan
            )a group by site_code, a.kodeprod, a.bulan
        )a LEFT JOIN
        (
            select concat(a.kode_comp, a.nocab) as site_code, a.branch_name, a.nama_comp
            from mpm.tbl_tabcomp a
            where a.status = 1
            GROUP BY concat(a.kode_comp, a.nocab)
        )b on a.site_code = b.site_code INNER JOIN
        (
            select a.kodeprod, a.namaprod, a.grup, a.subgroup, a.supp
            from mpm.tabprod a
            where a.supp = 001 and a.grup ='G0101'
        )c on a.kodeprod = c.kodeprod LEFT JOIN 
        (
            select a.supp, a.namasupp
            from mpm.tabsupp a
        )d on c.supp = d.supp
        ";
        $this->db->query($sql);

        $sql = "
        insert into db_temp.t_temp_dashboard_mpm_candy
        select b.branch_name, b.nama_comp, a.kodeprod, d.namasupp as principal, c.namaprod, a.bulan, a.unit, a.ot, '$created_date'
        from
        (
            select site_code,kodeprod,bulan,sum(unit) as unit, sum(ot) as ot
            from
            (
                select 	concat(a.kode_comp,a.nocab) as site_code, kodeprod, a.bulan, 
                        sum(a.banyak) as unit, count(distinct(concat(a.kode_comp,a.kode_lang))) as ot
                from data2022.fi a
                group by site_code, a.kodeprod, a.bulan
                union all
                select 	concat(a.kode_comp,a.nocab) as site_code, kodeprod, a.bulan, 
                        sum(a.banyak) as unit, null
                from data2022.ri a
                group by site_code, a.kodeprod, a.bulan
            )a group by site_code, a.kodeprod, a.bulan
        )a LEFT JOIN
        (
            select concat(a.kode_comp, a.nocab) as site_code, a.branch_name, a.nama_comp
            from mpm.tbl_tabcomp a
            where a.status = 1
            GROUP BY concat(a.kode_comp, a.nocab)
        )b on a.site_code = b.site_code INNER JOIN
        (
            select a.kodeprod, a.namaprod, a.grup, a.subgroup, a.supp
            from mpm.tabprod a
            where a.supp = 001 and a.grup ='G0102'
        )c on a.kodeprod = c.kodeprod LEFT JOIN 
        (
            select a.supp, a.namasupp
            from mpm.tabsupp a
        )d on c.supp = d.supp
        ";
        $this->db->query($sql);
    }

    public function generate_dashboard_tahun_lalu($created_date){
        $this->db->query('truncate db_temp.t_temp_dashboard_mpm_tahun_lalu'); //herbal 2021
        $this->db->query('truncate db_temp.t_temp_dashboard_mpm_candy_tahun_lalu'); //candy 2021
        $sql = "
        insert into db_temp.t_temp_dashboard_mpm_tahun_lalu
        select b.branch_name, b.nama_comp, a.kodeprod, d.namasupp as principal, c.namaprod, a.bulan, a.unit, a.ot, '$created_date'
        from
        (
            select site_code,kodeprod,bulan,sum(unit) as unit, sum(ot) as ot
            from
            (
                select 	concat(a.kode_comp,a.nocab) as site_code, kodeprod, a.bulan, 
                        sum(a.banyak) as unit, count(distinct(concat(a.kode_comp,a.kode_lang))) as ot
                from data2021.fi a
                group by site_code, a.kodeprod, a.bulan
                union all
                select 	concat(a.kode_comp,a.nocab) as site_code, kodeprod, a.bulan, 
                        sum(a.banyak) as unit, null
                from data2021.ri a
                group by site_code, a.kodeprod, a.bulan
            )a group by site_code, a.kodeprod, a.bulan
        )a LEFT JOIN
        (
            select concat(a.kode_comp, a.nocab) as site_code, a.branch_name, a.nama_comp
            from mpm.tbl_tabcomp a
            where a.status = 1
            GROUP BY concat(a.kode_comp, a.nocab)
        )b on a.site_code = b.site_code INNER JOIN
        (
            select a.kodeprod, a.namaprod, a.grup, a.subgroup, a.supp
            from mpm.tabprod a
            where a.supp = 001 and a.grup ='G0101'
        )c on a.kodeprod = c.kodeprod LEFT JOIN 
        (
            select a.supp, a.namasupp
            from mpm.tabsupp a
        )d on c.supp = d.supp
        ";
        $this->db->query($sql);

        $sql = "
        insert into db_temp.t_temp_dashboard_mpm_candy_tahun_lalu
        select b.branch_name, b.nama_comp, a.kodeprod, d.namasupp as principal, c.namaprod, a.bulan, a.unit, a.ot, '$created_date'
        from
        (
            select site_code,kodeprod,bulan,sum(unit) as unit, sum(ot) as ot
            from
            (
                select 	concat(a.kode_comp,a.nocab) as site_code, kodeprod, a.bulan, 
                        sum(a.banyak) as unit, count(distinct(concat(a.kode_comp,a.kode_lang))) as ot
                from data2021.fi a
                group by site_code, a.kodeprod, a.bulan
                union all
                select 	concat(a.kode_comp,a.nocab) as site_code, kodeprod, a.bulan, 
                        sum(a.banyak) as unit, null
                from data2021.ri a
                group by site_code, a.kodeprod, a.bulan
            )a group by site_code, a.kodeprod, a.bulan
        )a LEFT JOIN
        (
            select concat(a.kode_comp, a.nocab) as site_code, a.branch_name, a.nama_comp
            from mpm.tbl_tabcomp a
            where a.status = 1
            GROUP BY concat(a.kode_comp, a.nocab)
        )b on a.site_code = b.site_code INNER JOIN
        (
            select a.kodeprod, a.namaprod, a.grup, a.subgroup, a.supp
            from mpm.tabprod a
            where a.supp = 001 and a.grup ='G0102'
        )c on a.kodeprod = c.kodeprod LEFT JOIN 
        (
            select a.supp, a.namasupp
            from mpm.tabsupp a
        )d on c.supp = d.supp
        ";
        $this->db->query($sql);
    }

    public function semut_gajah_site($created_date){

        $tahun_sekarang = date('Y');
        $truncate = $this->db->query("truncate db_master_data.t_site");

        if ($truncate) {
            $insert = "
            insert into db_master_data.t_site
            select a.site_code, a.branch_name,a.nama_comp,a.sub,a.status_ho,a.telp_wa,$created_date
            from 
            (
                select concat(a.kode_comp,a.nocab) as site_code, a.branch_name, a.nama_comp, a.sub, a.status_ho,a.telp_wa
                from mpm.tbl_tabcomp a 
                where a.`status` = 1
                GROUP BY concat(a.kode_comp,a.nocab)
            )a inner join 
            (
                select concat(a.kode_comp,a.nocab ) as site_code
                from db_dp.t_dp a
                where a.tahun = $tahun_sekarang and a.status = 1
            )b on a.site_code = b.site_code
            ";
            $proses = $this->db->query($insert);
            return $proses;
        }else{
            return array();
        }

    }

}