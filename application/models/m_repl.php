<?php if( ! defined('BASEPATH')) exit('Akses langsung tidak diperkenankan');

class M_repl extends CI_Model
{
    public function M_repl()
    {
        set_time_limit(0);

        $this->load->database();
        $this->load->library(array('table','session','zip','stable','user_agent'));//session untuk sisi administrasi
        $this->load->helper(array('text_helper','array_helper','file_helper','to_excel_helper'));
        //$this->load->plugin('to_excel');
        //$this->config->load('sorot');
    }

    public function getharikerja($data)
    {
        $dob2=trim($data['tglrepl']);//$dob1='dd/mm/yyyy' format
        $dob_tglrepl=strftime('%Y-%m-%d',strtotime($dob2));
        $year = substr($dob_tglrepl, 0,4);
        $month = substr($dob_tglrepl, 5,2);
        //mencari hari kerja (hari libur tidak dihitung) 
        $this->db->where('tahun',$year);
        $this->db->where('bulan',$month);
        $query = $this->db->get('db_po.t_bulan');
        foreach ($query->result() as $row) {
            $hr = $row->hari_kerja;
            return $hr;
        }
    }

    public function getidheader($data)
    {
        $userid=$this->session->userdata('id');
        $sql = "
        select max(last_id_repl) as last_id_repl
        FROM
        (
            select  max(id_repl) as last_id_repl 
            from    db_po.t_header_repl a 
            union ALL
            select max(id_repl) from db_po.t_header_repl_group
        )a
            
        ";
        $query = $this->db->query($sql);
        foreach ($query->result() as $row) {
            $last_id_repl = $row->last_id_repl;
            return $last_id_repl;
        }
    }

    public function getjawa($data)
    {
        $nocab=trim($data['nocab']);
        $userid=$this->session->userdata('id');
        $sql = "
            select  jawa from mpm.tbl_tabcomp 
            where   concat(kode_comp,nocab) = '$nocab' 
        ";
        $query = $this->db->query($sql);
        foreach ($query->result() as $row) {
            $status_jawa = $row->jawa;
            return $status_jawa;
        }
    }

    public function repl($data)
    {
        if ( function_exists( 'date_default_timezone_set' ) )
        date_default_timezone_set('Asia/Jakarta');  
        $userid=$this->session->userdata('id');
        $cycle=(int)($data['cycle']);
        $git=$data['git'];
        
        if($git == '0'){
            $bulan_git = "and ((year(a.tglpo) in (year(date(now()))) and month(a.tglpo) in (month(date(now())))))";
        }elseif($git == '1'){
            $bulan_git = "
            and ((
                year(a.tglpo) in (year(date(now()))) and month(a.tglpo) in (month(date(now())))
            )or
            (
                year(a.tglpo) in (date_format(date(now()) - INTERVAL '1' MONTH,'%Y')) and 
                month(a.tglpo) in (date_format(date(now()) - INTERVAL '1' MONTH,'%m'))
            ))";
        }elseif($git == '2'){
            $bulan_git = "
            and ((
                year(a.tglpo) in (year(date(now()))) and month(a.tglpo) in (month(date(now())))
            )or
            (
                year(a.tglpo) in (date_format(date(now()) - INTERVAL '1' MONTH,'%Y')) and 
                month(a.tglpo) in (date_format(date(now()) - INTERVAL '1' MONTH,'%m'))
            )or
            (
                year(a.tglpo) in (date_format(date(now()) - INTERVAL '2' MONTH,'%Y')) and 
                month(a.tglpo) in (date_format(date(now()) - INTERVAL '2' MONTH,'%m'))
            ))";
        }elseif($git == '3'){
            $bulan_git = "and ((
                year(a.tglpo) in (year(date(now()))) and month(a.tglpo) in (month(date(now())))
            )or
            (
                year(a.tglpo) in (date_format(date(now()) - INTERVAL '1' MONTH,'%Y')) and 
                month(a.tglpo) in (date_format(date(now()) - INTERVAL '1' MONTH,'%m'))
            )or
            (
                year(a.tglpo) in (date_format(date(now()) - INTERVAL '2' MONTH,'%Y')) and 
                month(a.tglpo) in (date_format(date(now()) - INTERVAL '2' MONTH,'%m'))
            )or
            (
                year(a.tglpo) in (date_format(date(now()) - INTERVAL '3' MONTH,'%Y')) and 
                month(a.tglpo) in (date_format(date(now()) - INTERVAL '3' MONTH,'%m'))
            ))";
        }else{
            $bulan_git ='';
        }

        $created = date('Y-m-d H:i:s');
        $dob2=trim($data['tglrepl']);//$dob1='dd/mm/yyyy' format
        $dob_tglrepl=strftime('%Y-%m-%d',strtotime($dob2));
        $year = substr($dob_tglrepl, 0,4);
        $year_2 = $year - 1;
        $month = substr($dob_tglrepl, 5,2);
        $end_bulan = $month - $cycle ;
        $nocab = $data['nocab'];
        $dp = substr($nocab,3,2);
        $kode = $nocab;
        // echo "nocab : ".$nocab."<br>";
        // echo "dp : ".$dp."<br>";
        $hr = $data['hari_kerja'];
        $last_id_repl = $data['last_id_header'];
        $jawa = $data['status_jawa'];
        if ($jawa == '1')
        {  $harga =  '(a.h_dp-(a.h_dp*d_dp/100))';  }else{ $harga = '(a.h_luarjawa-(a.h_luarjawa*d_luarjawa/100))'; }

        $cari_group_repl= "
            select  group_repl,status_group_repl 
            from    mpm.tbl_tabcomp 
            where   concat(kode_comp,nocab) ='$nocab' and `status` = 1
        ";

        // echo "<pre>";
        // echo "<br><br><br>";
        // print_r($cari_group_repl);
        // echo "</pre>";
        $proses_cari_group_repl = $this->db->query($cari_group_repl);
        $row=$proses_cari_group_repl->row();
        $group_repl = $row->group_repl;

        if($group_repl == '') //JIKA DP TIDAK PUNYA GROUP / NORMAL
        {             

            if($end_bulan<=0)
            {            
                $cycles = '- '.$cycle.' month';
                $timestamp = strtotime($dob_tglrepl);
                // echo "dob_tglrepl : ".$dob_tglrepl."<br>";
                // echo "timestamp : ".$timestamp."<br>";
                $bulan_awal = date('m', strtotime($cycles, $timestamp))."<br>"; // 01-03-2017
                // var_dump($bulan_awal);
                // echo "<br>bulan awal : ".$bulan_awal;
                
                for ($i=(int)$bulan_awal; $i<12 ; $i++) { 
                    $x[] = $i.',';
                }

                $bulan_1 = implode($x).'12';
                // echo "bulan_1 : ".$bulan_1."<br>";

                $bulan_awal_b = '1';
                $bulan_akhir_b = (int)$month-1;
                // echo "bulan_akhir_b : ".$bulan_akhir_b."<br>";

                if ($bulan_akhir_b == '0') {
                    
                    
                    $fi = "         
                            select a.kode, b.kodeprod_to as kodeprod, sum(a.banyak) as unit,adjustments
                            from
                            (
                                select concat(a.kode_comp, a.nocab) as kode, kodeprod,banyak
                                from data$year_2.fi a
                                where bulan in ($bulan_1) and concat(a.kode_comp, a.nocab) = '$kode'                                 
                                union ALL                    
                                select concat(a.kode_comp, a.nocab) as kode,kodeprod,banyak
                                from data$year_2.ri a
                                where bulan in ($bulan_1) and concat(a.kode_comp, a.nocab) = '$kode'
                            )a INNER JOIN
                            (
                                select a.kode,a.kodeprod_from,a.kodeprod_to,adjustments
                                from db_po.t_master_produk_repl a
                                where a.`status` = 1  and a.kode = '$kode' 
                            )b on a.kode = b.kode and a.kodeprod = b.kodeprod_from
                            GROUP BY kodeprod_to
                            ORDER BY kodeprod_to                            
                        ";

                }else{

                    for ($i=1; $i<(int)$bulan_akhir_b ; $i++) { 
                        $a[] = $i.',';
                    }$bulan_2 = implode($a).(int)$bulan_akhir_b;
                    // echo "bulan_2 : ".$bulan_2."<br>";
    
                    $fi = "         
                        select a.kode, b.kodeprod_to as kodeprod, sum(a.banyak) as unit,adjustments
                        from
                        (
                            select concat(a.kode_comp, a.nocab) as kode, kodeprod,banyak
                            from data$year_2.fi a
                            where bulan in ($bulan_1) and concat(a.kode_comp, a.nocab) = '$kode'                                 
                            union ALL     
                            select concat(a.kode_comp, a.nocab) as kode, kodeprod,banyak
                            from data$year.fi a
                            where bulan in ($bulan_2) and concat(a.kode_comp, a.nocab) = '$kode'                                 
                            union ALL                    
                            select concat(a.kode_comp, a.nocab) as kode, kodeprod,banyak
                            from data$year_2.ri a
                            where bulan in ($bulan_1) and concat(a.kode_comp, a.nocab) = '$kode'                                 
                            union ALL     
                            select concat(a.kode_comp, a.nocab) as kode, kodeprod,banyak
                            from data$year.ri a
                            where bulan in ($bulan_2) and concat(a.kode_comp, a.nocab) = '$kode'   
                        )a INNER JOIN
                        (
                            select a.kode,a.kodeprod_from,a.kodeprod_to,adjustments
                            from db_po.t_master_produk_repl a
                            where a.`status` = 1  and a.kode = '$kode' 
                        )b on a.kode = b.kode and a.kodeprod = b.kodeprod_from
                        GROUP BY kodeprod_to
                        ORDER BY kodeprod_to                            
                    ";

                }
                // echo"<pre>";
                // print_r($fi);
                // echo"</pre>";
                
            }else{
                for ($i=(int)$month-1; $i>(int)$end_bulan ; $i--) { 
                    $x[] = $i.',';
                    }$bulan = implode($x).(int)$end_bulan;
                    // echo "bulan : ".$bulan;

                $fi = "                    
                    select a.kode, b.kodeprod_to as kodeprod, sum(a.banyak) as unit,adjustments
                    from
                    (
                        select concat(a.kode_comp, a.nocab) as kode, kodeprod,banyak
                        from data$year.fi a
                        where bulan in ($bulan) and concat(a.kode_comp, a.nocab) = '$kode'                                 
                        union ALL                    
                        select concat(a.kode_comp, a.nocab) as kode,kodeprod,banyak
                        from data$year.ri a
                        where bulan in ($bulan) and concat(a.kode_comp, a.nocab) = '$kode'
                    )a INNER JOIN
                    (
                        select a.kode,a.kodeprod_from,a.kodeprod_to,adjustments
                        from db_po.t_master_produk_repl a
                        where a.`status` = 1  and a.kode = '$kode' 
                    )b on a.kode = b.kode and a.kodeprod = b.kodeprod_from
                    GROUP BY kodeprod_to
                    ORDER BY kodeprod_to                             
                ";
                // echo"<pre>";
                // print_r($fi);
                // echo"</pre>";
            }

            for ($i=(int)$month-1; $i>(int)$end_bulan ; $i--) { 
            $x[] = $i.',';
            }$bulan = implode($x).(int)$end_bulan;
            // echo "bulan : ".$bulan."<br>";
            // echo "month : ".$month;
        
            $new_id_repl = $last_id_repl + 1;     
            $sql = "insert into db_po.t_header_repl values('$new_id_repl', '$nocab', $userid, '$created') ";
            $query = $this->db->query($sql);
            // echo "<pre>";
            // print_r($sql);
            
            if($query){
                // echo "insert t_header_repl berhasil";
            }else{
                // echo "insert t_header_repl gagal";
             }echo "</pre>";

            //end mencari id max dari db_po.t_header_repl
            $sql = "
            insert into db_po.t_git
            select $new_id_repl, nocab, c.kodeprod_to as kodeprod, sum(git) as git
            FROM
            (
                select 	c.username, a.userid, 
                        kodeprod, 
                        sum(b.banyak) as git, 
                        month(a.tglpo) as bulan
                from   	mpm.po a INNER JOIN mpm.po_detail b
                            on a.id = b.id_ref LEFT JOIN mpm.`user` c on a.userid = c.id
                where   a.deleted = 0 and 
                        b.deleted = 0 and 
                        (b.status_terima is null or b.status_terima = null or b.status_terima = '0') and 
                        (a.nopo is not null and left(a.nopo,4) <> '/mpm' and a.nopo not like '%batal%') $bulan_git
                GROUP BY b.kodeprod, a.userid
                ORDER BY bulan asc, b.kodeprod
            )a inner JOIN
            (
                select kode_comp,nama_comp,nocab,concat(a.kode_comp,a.nocab) as kode
                from mpm.tbl_tabcomp a
                where `status` = 1 and active = 1
                GROUP BY concat(kode_comp,nocab)
            )b on a.username = b.kode_comp INNER JOIN
            (
                select a.kode,a.kodeprod_from,a.kodeprod_to
                from db_po.t_master_produk_repl a
                where a.`status` = 1  and a.kode = '$kode' 
            )c on b.kode = c.kode and a.kodeprod = c.kodeprod_from
            where nocab = '$dp'
            group by kodeprod
            ORDER BY b.kode_comp, nocab, kodeprod
        ";
        $query = $this->db->query($sql);
        // echo "<pre>";
        // print_r($sql);
        // echo "</pre>";
            //menghitung pesanan replineshment
            $sql = "
                insert into db_po.t_detail_repl
                select  $new_id_repl, a.supp,a.kodeprod, a.avg, a.sl, a.hrkerja, a.stdsl,a.stok as stok,a.git as git, a.stock_akhir, a.index_stock, a.sales_lalu, a.harga, a.karton, a.adjustments,a.auto_repl1, a.auto_repl2,
                        if(auto_repl1<0,if((abs(auto_repl1)/karton)-floor(abs(auto_repl1)/karton)>=0.2,ceiling(abs(auto_repl1)/karton),floor(abs(auto_repl1)/karton)),0) as pesan1,
                        if(auto_repl2<0,if((abs(auto_repl2)/karton)-floor(abs(auto_repl2)/karton)>=0.2,ceiling(abs(auto_repl2)/karton),floor(abs(auto_repl2)/karton)),0) as pesan2,
                        if(auto_repl1<0,if((abs(auto_repl1)/karton)-floor(abs(auto_repl1)/karton)>=0.2,ceiling(abs(auto_repl1)/karton),floor(abs(auto_repl1)/karton)),0) * karton as unit1,
                        if(auto_repl2<0,if((abs(auto_repl2)/karton)-floor(abs(auto_repl2)/karton)>=0.2,ceiling(abs(auto_repl2)/karton),floor(abs(auto_repl2)/karton)),0) * karton as unit2
                from
                (
                    select  b.supp,a.kodeprod, a.avg, a.sl, a.hrkerja, a.stdsl, a.stock_akhir, ((stock_akhir / avg) * hrkerja) as index_stock, 
                            a.sales_lalu, (stock_akhir - stdsl + if(adjustments is null, 0, adjustments)) as auto_repl1, (stock_akhir - stdsl - sales_lalu) as auto_repl2,
                            karton, kode_prc, harga, stok, git,adjustments         
                    from
                    (
                        select  a.kodeprod,cast(sum(unit)/$cycle as unsigned) as avg,
                                b.sl, $hr as hrkerja, (cast(sum(unit)/$cycle as unsigned) / $hr)*b.sl as stdsl,
                                d.sales_lalu, if(git is null, stok, stok+git) as stock_akhir, stok, git,adjustments
                        from
                        (
                            $fi
                        )a LEFT JOIN
                        (
                                select kodeprod,sl
                                from mpm.tbl_stok_level
                                where nocab = '$dp'  
                                group by kodeprod   
                        )b on a.kodeprod = b.kodeprod
                        LEFT JOIN
                        (
                            select kodeprod, sum(stok) as stok, nocab
                            from
                            (
                                select  if(a.kodeprod = '700009','010067',if(a.kodeprod = '700012', '010094', a.kodeprod)) kodeprod,nocab,
                                sum((saldoawal+masuk_pbk+retur_sal+retur_depo)-(sales+minta_depo)) as stok
                                from data$year.st a
                                where nocab = '$dp' and bulan = (
                                    select  max(bulan) 
                                    from    data$year.st 
                                    where   nocab='$dp'
                                )and kode_gdg in ('pst',1) 
                                GROUP BY KODEPROD
                            )a group by kodeprod
                        )c on a.kodeprod = c.kodeprod
                        LEFT JOIN
                        (
                            select kodeprod, sales_lalu
                            FROM
                            (
                                select kodeprod, sum(unit) as sales_lalu
                                FROM
                                (
                                    select KODEPROD, sum(banyak) as unit
                                    from data$year.fi
                                    where bulan = $month and nocab = '$dp'
                                    GROUP BY KODEPROD
                                    union ALL
                                    select KODEPROD, sum(banyak) as unit
                                    from data$year.ri
                                    where bulan = $month and nocab = '$dp'
                                    GROUP BY kodeprod
                                )a GROUP BY kodeprod
                            )a
                        )d on a.kodeprod = d.kodeprod
                        
                        LEFT JOIN
                        (
                            select kodeprod, sum(git) as git
                            from db_po.t_git
                            where id = $new_id_repl and nocab = '$dp'
                            GROUP BY kodeprod
                        )e on c.kodeprod = e.kodeprod
                        group by kodeprod
                    )a INNER JOIN
                    (
                        select  supp,
                                    kodeprod,
                                    isisatuan as karton,
                                    h_dp,
                                    kode_prc, namaprod
                        from    mpm.tabprod 
                        where active = 1
                    )b on a.kodeprod = b.kodeprod
                    LEFT JOIN
                    (
                        select  kodeprod,
                                $harga as harga 
                        from    mpm.prod_detail a 
                        where   tgl=
                                (
                                    select  max(tgl) 
                                    from    mpm.prod_detail 
                                    where   kodeprod=a.kodeprod
                                )
                    )c on a.kodeprod = c.kodeprod
                )a
            ";
            
            $query = $this->db->query($sql); 
            
            //  echo "<pre>";
            //  print_r($sql);       
            //  echo "</pre>";
            if($query){
                // echo "insert t_detail_repl berhasil";
            }else{
                echo "insert t_detail_repl gagal";
            }echo "</pre>";        
            

        }else{

            // echo "bbbb";
            // die;

            //jika punya group
            $cari_dp_lain= "
                    select  concat(kode_comp,nocab) as kode 
                    from    mpm.tbl_tabcomp 
                    where   group_repl ='$group_repl' and `status` = 1 and active_repl = 1
                    group by kode 
                    ORDER BY status_group_repl desc";
            
            // echo "<pre>";
            // print_r($cari_dp_lain);
            // echo "</pre>";        
            
            // die;
            $query = $this->db->query($cari_dp_lain);

            // echo "month : ".$month."<br>";
            for ($i=(int)$month-1; $i>(int)$end_bulan ; $i--) 
            { 
                $x[] = $i.',';
                // echo "i : ".$i."<br>";
            }
            $bulan = implode($x).(int)$end_bulan;
            // echo "bulan : ".$bulan;

            // die;
            foreach ($query->result() as $row)
            {        
                $kode = $row->kode;
                $dp = substr($kode,3,2);
                // echo "kode : ".$kode."<br>";     
                // echo "dp : ".$dp."<br>";  
                // echo "<br>end_bulan : ".$end_bulan."<br>"; 
                
                if($end_bulan<=0)
                {            
                    $cycles = '- '.$cycle.' month';
                    // echo "cycles : ".$cycles."<br>";
                    $timestamp = strtotime($dob_tglrepl);
                    $bulan_awal = date('m', strtotime($cycles, $timestamp))."<br>"; // 01-03-2017
                    
                    // $bulan_awal = 8;
                    // echo "bulan awal : ".$bulan_awal;
                    
                    $bulan_akhir = '12';
                    
                    for ($i=(int)$bulan_awal; $i<12 ; $i++) { 
                        $y[] = $i.',';
                        // echo "<br>i : ".$i;
                    }
                    $bulan_1 = implode($y).'12';
                    // echo "<br>bulan_1 : ".$bulan_1."<br>";

                    $bulan_awal_b = '1';
                    $bulan_akhir_b = (int)$month-1;
                    // echo "bulan_akhir_b : ".$bulan_akhir_b."<br>";

                    // $bulan_akhir_b = 0;

                    if ($bulan_akhir_b == '0') {                        
                        // $fi = "                    
                        // select kodeprod, sum(banyak) as unit
                        // from
                        // (
                        //     select if(kodeprod = '700009','010067',if(kodeprod = '700012', '010094', kodeprod)) kodeprod,banyak
                        //     from data$year_2.fi
                        //     where bulan in ($bulan_1) and nocab = '$dp'                        
                        //     union ALL                    
                        //     select if(kodeprod = '700009','010067',if(kodeprod = '700012', '010094', kodeprod)) kodeprod,banyak
                        //     from data$year_2.ri
                        //     where bulan in ($bulan_1) and nocab = '$dp'
    
                        // )a group by kodeprod
                            
                        // ";

                        // echo "<pre>";
                        // print_r($fi);
                        // echo "</pre>";

                        $fi = "         
                            select a.kode, b.kodeprod_to as kodeprod, sum(a.banyak) as unit,adjustments
                            from
                            (
                                select concat(a.kode_comp, a.nocab) as kode, kodeprod,banyak
                                from data$year_2.fi a
                                where bulan in ($bulan_1) and concat(a.kode_comp, a.nocab) = '$kode'                                 
                                union ALL                    
                                select concat(a.kode_comp, a.nocab) as kode,kodeprod,banyak
                                from data$year_2.ri a
                                where bulan in ($bulan_1) and concat(a.kode_comp, a.nocab) = '$kode'
                            )a INNER JOIN
                            (
                                select a.kode,a.kodeprod_from,a.kodeprod_to,adjustments
                                from db_po.t_master_produk_repl a
                                where a.`status` = 1  and a.kode = '$kode' 
                            )b on a.kode = b.kode and a.kodeprod = b.kodeprod_from
                            GROUP BY kodeprod_to
                            ORDER BY kodeprod_to                            
                        ";
    
                    }else{
                        for ($i=(int)$bulan_awal_b; $i<(int)$bulan_akhir_b ; $i++) { 
                            $a[] = $i.',';
                        }$bulan_2 = implode($a).(int)$bulan_akhir_b;
                        // echo "bulan_2 : ".$bulan_2."<br>";

                        $fi = "         
                            select a.kode, b.kodeprod_to as kodeprod, sum(a.banyak) as unit,adjustments
                            from
                            (
                                select concat(a.kode_comp, a.nocab) as kode, kodeprod,banyak
                                from data$year_2.fi a
                                where bulan in ($bulan_1) and concat(a.kode_comp, a.nocab) = '$kode'                                 
                                union ALL     
                                select concat(a.kode_comp, a.nocab) as kode, kodeprod,banyak
                                from data$year.fi a
                                where bulan in ($bulan_2) and concat(a.kode_comp, a.nocab) = '$kode'                                 
                                union ALL                    
                                select concat(a.kode_comp, a.nocab) as kode, kodeprod,banyak
                                from data$year_2.ri a
                                where bulan in ($bulan_1) and concat(a.kode_comp, a.nocab) = '$kode'                                 
                                union ALL     
                                select concat(a.kode_comp, a.nocab) as kode, kodeprod,banyak
                                from data$year.ri a
                                where bulan in ($bulan_2) and concat(a.kode_comp, a.nocab) = '$kode'   
                            )a INNER JOIN
                            (
                                select a.kode,a.kodeprod_from,a.kodeprod_to,adjustments
                                from db_po.t_master_produk_repl a
                                where a.`status` = 1  and a.kode = '$kode' 
                            )b on a.kode = b.kode and a.kodeprod = b.kodeprod_from
                            GROUP BY kodeprod_to
                            ORDER BY kodeprod_to                            
                        ";
                    }
                    // echo"<pre>";
                    // print_r($fi);
                    // echo"</pre>";
                        
                }else{
                    
                    $fi = "         
                        select a.kode, b.kodeprod_to as kodeprod, sum(a.banyak) as unit,adjustments
                        from
                        (
                            select concat(a.kode_comp, a.nocab) as kode, kodeprod,banyak
                            from data$year.fi a
                            where bulan in ($bulan) and concat(a.kode_comp, a.nocab) = '$kode'                                 
                            union ALL                    
                            select concat(a.kode_comp, a.nocab) as kode,kodeprod,banyak
                            from data$year.ri a
                            where bulan in ($bulan) and concat(a.kode_comp, a.nocab) = '$kode'
                        )a INNER JOIN
                        (
                            select a.kode,a.kodeprod_from,a.kodeprod_to,adjustments
                            from db_po.t_master_produk_repl a
                            where a.`status` = 1  and a.kode = '$kode' 
                        )b on a.kode = b.kode and a.kodeprod = b.kodeprod_from
                        GROUP BY kodeprod_to
                        ORDER BY kodeprod_to                            
                    ";
                    // echo"<pre>";
                    // print_r($fi);
                    // echo"</pre>";
                }
                    
                $new_id_repl = $last_id_repl + 1;     
                $sql = "insert into db_po.t_header_repl_group values('','$new_id_repl','$group_repl', '$kode', $userid, '$created') ";
                $query = $this->db->query($sql);
                
                // echo "<pre>";
                // print_r($sql);
                // echo "</pre>";
                
                $sql = "
                    insert into db_po.t_git
                    select $new_id_repl, nocab, c.kodeprod_to as kodeprod, sum(git) as git
                    FROM
                    (
                        select 	c.username, a.userid, 
                                kodeprod, 
                                sum(b.banyak) as git, 
                                month(a.tglpo) as bulan
                        from   	mpm.po a INNER JOIN mpm.po_detail b
                                    on a.id = b.id_ref LEFT JOIN mpm.`user` c on a.userid = c.id
                        where   a.deleted = 0 and 
                                b.deleted = 0 and 
                                (b.status_terima is null or b.status_terima = null or b.status_terima = '0') and 
                                (a.nopo is not null and left(a.nopo,4) <> '/mpm' and a.nopo not like '%batal%') $bulan_git
                        GROUP BY b.kodeprod, a.userid
                        ORDER BY bulan asc, b.kodeprod
                    )a inner JOIN
                    (
                        select kode_comp,nama_comp,nocab,concat(a.kode_comp,a.nocab) as kode
                        from mpm.tbl_tabcomp a
                        where `status` = 1 and active = 1
                        GROUP BY concat(kode_comp,nocab)
                    )b on a.username = b.kode_comp INNER JOIN
                    (
                        select a.kode,a.kodeprod_from,a.kodeprod_to
                        from db_po.t_master_produk_repl a
                        where a.`status` = 1  and a.kode = '$kode' 
                    )c on b.kode = c.kode and a.kodeprod = c.kodeprod_from
                    where nocab = '$dp'
                    group by kodeprod
                    ORDER BY b.kode_comp, nocab, kodeprod
                ";
                $query = $this->db->query($sql);
                // echo "<pre>";
                // print_r($sql);
                // echo "</pre>";

                // die;

                //menghitung pesanan replineshment
                $sql = "
                    insert into db_po.t_detail_repl_group
                    select  $new_id_repl, '$kode', a.supp,a.kodeprod, a.avg, a.sl, a.hrkerja, a.stdsl,a.stok,a.git, a.stock_akhir, a.index_stock, a.sales_lalu, a.harga, a.karton, a.adjustments,a.auto_repl1, a.auto_repl2,
                            if(auto_repl1<0,if((abs(auto_repl1)/karton)-floor(abs(auto_repl1)/karton)>=0.2,ceiling(abs(auto_repl1)/karton),floor(abs(auto_repl1)/karton)),0) as pesan1,
                            if(auto_repl2<0,if((abs(auto_repl2)/karton)-floor(abs(auto_repl2)/karton)>=0.2,ceiling(abs(auto_repl2)/karton),floor(abs(auto_repl2)/karton)),0) as pesan2,
                            if(auto_repl1<0,if((abs(auto_repl1)/karton)-floor(abs(auto_repl1)/karton)>=0.2,ceiling(abs(auto_repl1)/karton),floor(abs(auto_repl1)/karton)),0) * karton as unit1,
                            if(auto_repl2<0,if((abs(auto_repl2)/karton)-floor(abs(auto_repl2)/karton)>=0.2,ceiling(abs(auto_repl2)/karton),floor(abs(auto_repl2)/karton)),0) * karton as unit2,
                            '$created'
                    from
                    (
                        select  b.supp,a.kodeprod, a.avg, a.sl, a.hrkerja, a.stdsl, a.stock_akhir, ((stock_akhir / avg) * hrkerja) as index_stock, 
                                a.sales_lalu, (stock_akhir - stdsl + if(adjustments is null, 0, adjustments)) as auto_repl1, (stock_akhir - stdsl - sales_lalu) as auto_repl2,
                                karton, kode_prc, harga,stok,git,adjustments
                        from
                        (
                            select  a.kodeprod,cast(sum(unit)/$cycle as unsigned) as avg,
                                    b.sl, $hr as hrkerja, (cast(sum(unit)/$cycle as unsigned) / $hr)*b.sl as stdsl,
                                    d.sales_lalu, if(git is null, stok, stok+git) as stock_akhir,stok,git,adjustments
                            from
                            (
                                $fi
                            )a LEFT JOIN
                            (
                                    select kodeprod,sl
                                    from mpm.tbl_stok_level
                                    where nocab = '$dp'    
                                    group by kodeprod 
                            )b on a.kodeprod = b.kodeprod
                            LEFT JOIN
                            (   
                                select kodeprod, sum(stok) as stok,nocab
                                from
                                (
                                    select  if(a.kodeprod = '700009','010067',if(a.kodeprod = '700012', '010094', a.kodeprod)) kodeprod, nocab,
                                    sum((saldoawal+masuk_pbk+retur_sal+retur_depo)-(sales+minta_depo)) as stok
                                    from data$year.st a
                                    where nocab = '$dp' and bulan = (
                                        select  max(bulan) 
                                        from    data$year.st 
                                        where   nocab='$dp'
                                    )and kode_gdg in ('pst',1) 
                                    GROUP BY KODEPROD
                                )a group by kodeprod
                            )c on a.kodeprod = c.kodeprod
                            LEFT JOIN
                            (
                                select kodeprod, sales_lalu
                                FROM
                                (
                                    select kodeprod, sum(unit) as sales_lalu
                                    FROM
                                    (
                                        select KODEPROD, sum(banyak) as unit
                                        from data$year.fi
                                        where bulan = $month and nocab = '$dp'
                                        GROUP BY KODEPROD
                                        union ALL
                                        select KODEPROD, sum(banyak) as unit
                                        from data$year.ri
                                        where bulan = $month and nocab = '$dp'
                                        GROUP BY kodeprod
                                    )a GROUP BY kodeprod
                                )a
                            )d on a.kodeprod = d.kodeprod
                            LEFT JOIN
                            (
                                select kodeprod, sum(git) as git
                                from db_po.t_git
                                where id = $new_id_repl and nocab = '$dp'
                                GROUP BY kodeprod
                            )e on c.kodeprod = e.kodeprod

                            group by kodeprod
                        )a INNER JOIN
                        (
                            select  supp,
                                        kodeprod,
                                        isisatuan as karton,
                                        h_dp,
                                        kode_prc, namaprod
                            from    mpm.tabprod 
                            where active = 1
                        )b on a.kodeprod = b.kodeprod
                        LEFT JOIN
                        (
                            select  kodeprod,
                                    $harga as harga 
                            from    mpm.prod_detail a 
                            where   tgl=
                                    (
                                        select  max(tgl) 
                                        from    mpm.prod_detail 
                                        where   kodeprod=a.kodeprod
                                    )
                        )c on a.kodeprod = c.kodeprod
                    )a
                ";
                
                $this->db->query($sql);   
                
                //  echo "<pre>";
                //  print_r($sql);
                //  echo "</pre>";
                 
                //  die;
                
            } 

                $sql = "
                    insert into db_po.t_header_repl
                    select a.id_repl, a.kode, a.created_id, a.created_date 
                    from db_po.t_header_repl_group a INNER JOIN(
                        select concat(a.kode_comp,a.nocab) as kode
                        from mpm.tbl_tabcomp a
                        where a.`status` = 1 and group_repl ='$group_repl' and status_group_repl = 1
                    )b on a.kode = b.kode
                    where id_repl = $new_id_repl
                "; 
                
                    // echo "<pre>";
                    // print_r($sql);
                    // echo "</pre>";
                
                $query = $this->db->query($sql);

                // die;

                $sql = "
                    insert into db_po.t_detail_repl
                    select  a.ref_repl,a.supp,a.kodeprod,a.avg,a.sl,
                            a.hrkerja,a.stdsl,a.stok,a.git,a.stock_akhir,a.index_stock,a.sales_lalu,
                            a.harga,a.karton,a.adjustments,sum(a.auto_repl1),sum(a.auto_repl2),sum(a.pesan1),sum(a.pesan2),
                            sum(a.unit1),sum(a.unit2)
                    from    db_po.t_detail_repl_group a
                    where   a.ref_repl = $new_id_repl
                    GROUP BY a.kodeprod
                "; 
                
                    // echo "<pre>";
                    // print_r($sql);
                    // echo "</pre>";
                
                $query = $this->db->query($sql);

            

        }

        /* PROSES TAMPIL KE WEBSITE */              
        $sql="
        select  nocab, b.supp, b.kodeprod, c.namaprod, b.avg, b.sl, b.stdsl, b.stok, b.git, b.stock_akhir, 
                b.index_stock, b.sales_lalu, b.harga, b.karton, b.adjustments, b.auto_repl1, b.auto_repl2, b.pesan1, b.pesan2, b.unit1, b.unit2
        from        db_po.t_header_repl a INNER JOIN db_po.t_detail_repl b
                    on a.id_repl = b.ref_repl
                INNER JOIN mpm.tabprod c
                    on b.kodeprod = c.kodeprod
        where id_repl = $new_id_repl
        order by b.supp, b.kodeprod
        ";
        
           // echo "<pre>";
            //print_r($sql);
            //echo "</pre>";
        
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) 
        {
            return $query->result();
        } else {
            return array();
        }
        /* END PROSES TAMPIL KE WEBSITE */

    }

    function send_email($data){
        // echo "email to : ".$data['email_to'];
        $to = $data['email_to'];
        // $to = "suffy@muliaputramandiri.com";
        // $from = "suffy@muliaputramandiri.com";
        $from = "tria@muliaputramandiri.com";
        $cc = "milla@muliaputramandiri.com,tria@muliaputramandiri.com, fakhrul@muliaputramandiri.com, suffy.yanuar@gmail.com";
        $subject = "site.muliaputramandiri.com|draft replineshment| No".$data['id_po']."|".$data['company'];
        // $subject = "Sedang Test- mohon hiraukan email ini| No".$data['id_po']."|".$data['company'];
        $this->load->library('email');
        $message = "Automatic Email By Sistem. Mohon download attachment dan konfirmasi nya dengan cara me-reply email ini";
        $config['protocol']     = 'smtp';
        $config['smtp_host']    = 'ssl://smtp.gmail.com';
        $config['smtp_port']    = '465';
        $config['smtp_timeout'] = '300';
        $config['smtp_user']    = 'suffy@muliaputramandiri.net';
        $config['smtp_pass']    = 'vruzinbjlnsgzagy';
        $config['charset']      = 'utf-8';
        $config['newline']      = "\r\n";
        $config['mailtype']     = "html";
        $config['use_ci_email'] = TRUE;
        $config['wordwrap']     = TRUE;

        // $config['protocol']     = 'smtp';
        // $config['smtp_host']    = 'ssl://smtp.gmail.com';
        // $config['smtp_port']    = '465';
        // $config['smtp_timeout'] = '300';
        // $config['smtp_user'] = 'agustriatriani@gmail.com';
        // $config['smtp_pass'] = '080890TRIA';
        // $config['charset']      = 'utf-8';
        // $config['newline']      = "\r\n";
        // $config['mailtype']     ="html";
        // $config['use_ci_email'] = TRUE;
        // $config['wordwrap']     = TRUE;

        $this->email->initialize($config);
        $this->email->from($from,'PT. Mulia Putra Mandiri');
        $this->email->to($to);
        $this->email->cc($cc);
        $this->email->subject($subject);
        $this->email->message($message);
        $this->email->attach("assets/po/".$data['filename'].".pdf");
        $send = $this->email->send();
        echo $this->email->print_debugger();
        if ($send) {
            return 1;
        }else{
            return 0;
        }

    }


    


}
?>