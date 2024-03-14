<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Dashboard_dummy extends MY_Controller
{
    function dashboard_dummy()
    {
        set_time_limit(0);
        $this->load->library(array('table','template','Excel_generator', 'form_validation','email'));
        $this->load->helper('url');
        $this->load->helper('csv');
        $this->load->model('model_omzet');
        $this->load->model('M_menu');
        $this->load->model('model_dashboard_dummy');
        $this->load->model('model_per_hari');
        $this->load->model('model_inventory');
        $this->load->model('model_sales_omzet');
        $this->load->database();
    }
    function index()
    {
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
        $this->default_user();
        // $this->load->view('info_website');
    }

    public function default_user(){
       
        $id = $this->session->userdata('id');
        if ($id == '297x' ||$id == '547x' ||$id == '290x' ||$id == '306x' ||$id == '417x' ||$id == '433x' ||$id == '445x' ||$id == '515x' ||$id == '516x' ||$id == '521x' ||$id == '556x' ||$id == '437x' ||$id == '231x') {
            
            $data = [
                'id' => $this->session->userdata('id'),
                'url' => 'sales_omzet/line_sold_hasil/',
                'title' => 'Dashboard',
                'get_label' => $this->M_menu->get_label(),
            ];

            $this->load->view('template_claim/top_header');
            $this->load->view('template_claim/header');
            $this->load->view('template_claim/sidebar',$data);
            $this->load->view('dashboard/dashboard_nasional_mpm',$data);
            $this->load->view('template_claim/bottom_content');
            $this->load->view('template_claim/footer');

        }else{
            $get_kode_alamat = $this->model_dashboard_dummy->get_kode_alamat();
            $code = '';
            foreach ($get_kode_alamat as $key) {
                $code.= ","."'".$key->kode_alamat."'";
            }
            $kode_alamat = preg_replace('/,/', '', $code,1);

            $data = [
                'id' => $this->session->userdata('id'),
                'url' => 'sales_omzet/line_sold_hasil/',
                'title' => 'Dashboard',
                'get_label' => $this->M_menu->get_label(),
                'get_bulan_sekarang' => $this->model_dashboard_dummy->get_bulan_sekarang(),
                'get_bulan_sebelumnya' => $this->model_dashboard_dummy->get_bulan_sebelumnya(),
                'get_closing' => $this->model_dashboard_dummy->get_closing(),
                'get_dp_belum_closing' => $this->model_dashboard_dummy->get_dp_belum_closing(),
                'get_noted' => $this->model_dashboard_dummy->get_noted()->row(),
                'get_tanggal_data' => $this->model_dashboard_dummy->get_tanggal_data($kode_alamat),
            ];

            $this->load->view('template_claim/top_header');
            $this->load->view('template_claim/header');
            $this->load->view('template_claim/sidebar',$data);
            $this->load->view('dashboard/closing',$data);
            $this->load->view('template_claim/bottom_content');
            $this->load->view('template_claim/footer');

        }
    }

    public function input_noted()
    {
        $data['noted'] = $this->input->post('noted');
        $data['userid'] = $this->session->userdata('id');
        $data['last_update'] = $this->model_sales_omzet->timezone2();

        // var_dump($noted);die;
        $this->db->insert('db_temp.t_temp_dashboard', $data);
        redirect('dashboard_dummy');
    }

    public function delete_noted()
    {
        $this->db->query('truncate db_temp.t_temp_dashboard');
        redirect('dashboard_dummy');
    }

    function get_data_upload_tercepat(){
        // note : where a.kode not in ('JKT88') dikarenakan dp untuk stok 
        $tahun_sekarang = date('Y');
        // $tahun_sekarang = 2023;
        $bulan_sekarang = date('m');
        $tanggal_sekarang = date('d');
        // echo "bulan : ".$bulan_sekarang;
        // echo "tanggal : ".$tanggal_sekarang;

        if ($tanggal_sekarang <= '7') {
            // $bulan_sekarang_x = (int)$bulan_sekarang - 1; 
            $bulan_sekarang_x = (int)12; 
        }else{
            $bulan_sekarang_x = $bulan_sekarang;
        }

        $get_kode_alamat = $this->model_inventory->get_kode_alamat();
        $code = '';
        foreach ($get_kode_alamat as $key) {
            $code.= ","."'".$key->kode_alamat."'";
        }
        $kode_alamat = preg_replace('/,/', '', $code,1);
        // echo $kode_alamat;

        $id_user=$this->session->userdata('id');
        $draw=$_REQUEST['draw'];
        $length=5;
        // $length=$_REQUEST['length'];
        $start=$_REQUEST['start'];
        $search=$_REQUEST['search']["value"];

        // $sql_total = "
        //     select concat(a.kode_comp,a.nocab) as kode from mpm.tbl_tabcomp a
        //     where concat(a.kode_comp,a.nocab) in ($kode_alamat)  
        // ";
        $sql_total = "
        select a.kode, a.branch_name, left(a.nama_comp,15) as nama_comp,c.tgl
        from
        (
            SELECT concat(a.kode_comp, a.nocab) as kode, a.branch_name, a.nama_comp
            FROM mpm.tbl_tabcomp a
            where a.`status` = 1 and a.active = 1
            GROUP BY concat(a.kode_comp, a.nocab)
        )a INNER join 
        (
            select concat(a.kode_comp,a.nocab) as kode
            from db_dp.t_dp a
            where a.tahun = $tahun_sekarang
        )b on a.kode = b.kode LEFT JOIN (
            select 	concat(a.kode_comp,a.nocab) as kode, 
                    concat(max(hrdok), '-', max(blndok), '-', max(a.thndok)) as tgl
            from data$tahun_sekarang.fi a
            where bulan in ($bulan_sekarang_x)
            GROUP BY concat(a.kode_comp,a.nocab)
            ORDER BY tgl desc, concat(a.kode_comp,a.nocab) asc
            limit 5
        )c on a.kode = c.kode
        where a.kode not in ('JKT88') 
        ORDER BY c.tgl desc
            limit 5
        ";
        $total = $this->db->query($sql_total)->num_rows();

        $output=array();
        /*Token yang dikrimkan client, akan dikirim balik ke client*/
        $output['draw']=$draw;
        $output['recordsTotal']=$output['recordsFiltered']=$total;
        $output['data']=array();

        if($search!=""){
        // $sql = "
        //     select * 
        //     from mpm.tbl_tabcomp a 
        //     where concat(a.kode_comp,a.nocab) in ($kode_alamat) and (nama_comp like '%$search%')
        //     order by a.nama_comp      
        //     limit $start,$length        
        // ";
        $sql = "
            
        select a.kode, a.branch_name, left(a.nama_comp,15) as nama_comp,c.tgl
        from
        (
            SELECT concat(a.kode_comp, a.nocab) as kode, a.branch_name, a.nama_comp
            FROM mpm.tbl_tabcomp a
            where a.`status` = 1 and a.active = 1
            GROUP BY concat(a.kode_comp, a.nocab)
        )a INNER join 
        (
            select concat(a.kode_comp,a.nocab) as kode
            from db_dp.t_dp a
            where a.tahun = $tahun_sekarang
        )b on a.kode = b.kode LEFT JOIN (
            select 	concat(a.kode_comp,a.nocab) as kode, 
                    concat(max(hrdok), '-', max(blndok), '-', max(a.thndok)) as tgl
            from data$tahun_sekarang.fi a
            where bulan in ($bulan_sekarang_x)
            GROUP BY concat(a.kode_comp,a.nocab)
            ORDER BY tgl desc, concat(a.kode_comp,a.nocab) asc
            limit 5
        )c on a.kode = c.kode
        where a.kode not in ('JKT88') 
        ORDER BY c.tgl desc
            limit $start,$length
        ";
        }else{

            // $sql = "
            //     select * from mpm.tbl_tabcomp a 
            //     where concat(a.kode_comp,a.nocab) in ($kode_alamat)    
            //     order by a.branch_name, a.nama_comp      
            //     limit $start,$length        
            // ";
            $sql = "
                
                select a.kode, a.branch_name, left(a.nama_comp,25) as nama_comp,c.tgl
                from
                (
                    SELECT concat(a.kode_comp, a.nocab) as kode, a.branch_name, a.nama_comp
                    FROM mpm.tbl_tabcomp a
                    where a.`status` = 1 and a.active = 1
                    GROUP BY concat(a.kode_comp, a.nocab)
                )a INNER join 
                (
                    select concat(a.kode_comp,a.nocab) as kode
                    from db_dp.t_dp a
                    where a.tahun = $tahun_sekarang
                )b on a.kode = b.kode LEFT JOIN (
                    select 	concat(a.kode_comp,a.nocab) as kode, 
                            concat(max(hrdok), '-', max(blndok), '-', max(a.thndok)) as tgl
                    from data$tahun_sekarang.fi a
                    where bulan in ($bulan_sekarang_x)
                    GROUP BY concat(a.kode_comp,a.nocab)
                    ORDER BY tgl desc, concat(a.kode_comp,a.nocab) asc
                    
                )c on a.kode = c.kode
                where a.kode not in ('JKT88') 
                ORDER BY c.tgl desc,a.nama_comp asc
                limit $start,$length     
            ";
        }
        $query = $this->db->query($sql);

        if($search!=""){
        $this->db->like("nama_comp",$search);
        // $jum=$this->db->get('mpm.tbl_tabcomp');

        // $sql = "select * from mpm.tbl_tabcomp";
        $sql = "        
        select a.kode, a.branch_name, left(a.nama_comp,15) as nama_comp,c.tgl
        from
        (
            SELECT concat(a.kode_comp, a.nocab) as kode, a.branch_name, a.nama_comp
            FROM mpm.tbl_tabcomp a
            where a.`status` = 1 and a.active = 1
            GROUP BY concat(a.kode_comp, a.nocab)
        )a INNER join 
        (
            select concat(a.kode_comp,a.nocab) as kode
            from db_dp.t_dp a
            where a.tahun = $tahun_sekarang
        )b on a.kode = b.kode LEFT JOIN (
            select 	concat(a.kode_comp,a.nocab) as kode, 
                    concat(max(hrdok), '-', max(blndok), '-', max(a.thndok)) as tgl
            from data$tahun_sekarang.fi a
            where bulan in ($bulan_sekarang_x)
            GROUP BY concat(a.kode_comp,a.nocab)
            ORDER BY tgl desc, concat(a.kode_comp,a.nocab) asc
            limit 5
        )c on a.kode = c.kode
        where a.kode not in ('JKT88') 
        ORDER BY c.tgl desc
        ";
        $jum = $this->db->query($sql);

        $output['recordsTotal']=$output['recordsFiltered']=$jum->num_rows();
        }
        $nomor_urut=$start+1;

        foreach ($query->result_array() as $kode) {

            // $y = anchor(base_url().'inventory/ms_edit/'.$kode['signature'],'edit',[
            //     'class' => 'btn btn-primary',
            //     'role'  => 'button',
            //     'target' => 'blank'
            // ]);

          $output['data'][]=array( 
            // $y,
            $nomor_urut,
            // '<button class="fa fa-edit fa-xl btn-info" target="blank" id="testOnclick" onclick="getEditIDProduct('.$kode['kodeprod'].')"></button>',
            $kode['nama_comp']." (".$kode['kode'].")",
            $kode['tgl'],
          );
        $nomor_urut++;
        }

        echo json_encode($output);
    }


    function get_data_upload_terlama(){
        // note : where a.kode not in ('JKT88') dikarenakan dp untuk stok 
        $tahun_sekarang = date('Y');
        // $tahun_sekarang = 2022;
        $bulan_sekarang = date('m');
        $tanggal_sekarang = date('d');
        // echo "bulan : ".$bulan_sekarang;
        // echo "tanggal : ".$tanggal_sekarang;

        if ($tanggal_sekarang <= '6') {
            // $bulan_sekarang_x = (int)$bulan_sekarang - 1; 
            $bulan_sekarang_x = (int)12; 
        }else{
            $bulan_sekarang_x = $bulan_sekarang;
        }

        $get_kode_alamat = $this->model_inventory->get_kode_alamat();
        $code = '';
        foreach ($get_kode_alamat as $key) {
            $code.= ","."'".$key->kode_alamat."'";
        }
        $kode_alamat = preg_replace('/,/', '', $code,1);
        // echo $kode_alamat;

        $id_user=$this->session->userdata('id');
        $draw=$_REQUEST['draw'];
        $length=5;
        // $length=$_REQUEST['length'];
        $start=$_REQUEST['start'];
        $search=$_REQUEST['search']["value"];

        $sql_total = "
            select a.kode, a.branch_name, a.nama_comp,if(c.tgl = null, 'belum tersedia',c.tgl) as tgl
            from
            (
                SELECT concat(a.kode_comp, a.nocab) as kode, a.branch_name, a.nama_comp
                FROM mpm.tbl_tabcomp a
                where a.`status` = 1 and a.active = 1
                GROUP BY concat(a.kode_comp, a.nocab)
            )a INNER join 
            (
                select concat(a.kode_comp,a.nocab) as kode
                from db_dp.t_dp a
                where a.tahun = $tahun_sekarang
            )b on a.kode = b.kode LEFT JOIN (
                select 	concat(a.kode_comp,a.nocab) as kode, 
                        concat(min(hrdok), '-', min(blndok), '-', min(a.thndok)) as tgl
                from data$tahun_sekarang.fi a
                where bulan in ($bulan_sekarang_x)
                GROUP BY concat(a.kode_comp,a.nocab)
                ORDER BY tgl desc
                limit 5
            )c on a.kode = c.kode
            where a.kode not in ('JKT88') 
            ORDER BY c.tgl asc
            limit 5
        ";
        $total = $this->db->query($sql_total)->num_rows();

        $output=array();
        $output['draw']=$draw;
        $output['recordsTotal']=$output['recordsFiltered']=$total;
        $output['data']=array();

        if($search!=""){
        $sql = "
            
            select a.kode, a.branch_name, a.nama_comp,if(c.tgl = null, 'belum tersedia','x') as tgl
            from
            (
                SELECT concat(a.kode_comp, a.nocab) as kode, a.branch_name, a.nama_comp
                FROM mpm.tbl_tabcomp a
                where a.`status` = 1 and a.active = 1
                GROUP BY concat(a.kode_comp, a.nocab)
            )a INNER join 
            (
                select concat(a.kode_comp,a.nocab) as kode
                from db_dp.t_dp a
                where a.tahun = $tahun_sekarang
            )b on a.kode = b.kode LEFT JOIN (
                select 	concat(a.kode_comp,a.nocab) as kode, 
                        concat(max(hrdok), '-', max(blndok), '-', max(a.thndok)) as tgl
                from data$tahun_sekarang.fi a
                where bulan in ($bulan_sekarang_x)
                GROUP BY concat(a.kode_comp,a.nocab)
                ORDER BY tgl desc
                limit 5
            )c on a.kode = c.kode
            where a.kode not in ('JKT88') 
            ORDER BY c.tgl asc
            limit $start,$length
        ";
        }else{
            $sql = "
                
                select a.kode, a.branch_name, left(a.nama_comp,25) as nama_comp,if(c.tgl is null, 'belum tersedia',c.tgl) as tgl
                from
                (
                    SELECT concat(a.kode_comp, a.nocab) as kode, a.branch_name, a.nama_comp
                    FROM mpm.tbl_tabcomp a
                    where a.`status` = 1 and a.active =1
                    GROUP BY concat(a.kode_comp, a.nocab)
                )a INNER join 
                (
                    select concat(a.kode_comp,a.nocab) as kode
                    from db_dp.t_dp a
                    where a.tahun = $tahun_sekarang
                )b on a.kode = b.kode LEFT JOIN (
                    select 	concat(a.kode_comp,a.nocab) as kode, 
                            concat(max(hrdok), '-', max(blndok), '-', max(a.thndok)) as tgl
                    from data$tahun_sekarang.fi a
                    where bulan in ($bulan_sekarang_x)
                    GROUP BY concat(a.kode_comp,a.nocab)
                    ORDER BY tgl desc
                )c on a.kode = c.kode
                where a.kode not in ('JKT88') 
                ORDER BY c.tgl asc, a.nama_comp asc
                limit $start,$length     
            ";
        }
        $query = $this->db->query($sql);

        if($search!=""){
        $this->db->like("nama_comp",$search);
        $sql = "        
            select a.kode, a.branch_name, a.nama_comp,if(c.tgl = null, 'belum tersedia',c.tgl) as tgl
            from
            (
                SELECT concat(a.kode_comp, a.nocab) as kode, a.branch_name, a.nama_comp
                FROM mpm.tbl_tabcomp a
                where a.`status` = 1 and a.active = 1
                GROUP BY concat(a.kode_comp, a.nocab)
            )a INNER join 
            (
                select concat(a.kode_comp,a.nocab) as kode
                from db_dp.t_dp a
                where a.tahun = $tahun_sekarang
            )b on a.kode = b.kode LEFT JOIN (
                select 	concat(a.kode_comp,a.nocab) as kode, 
                        concat(min(hrdok), '-', min(blndok), '-', min(a.thndok)) as tgl
                from data$tahun_sekarang.fi a
                where bulan in ($bulan_sekarang_x)
                GROUP BY concat(a.kode_comp,a.nocab)
                ORDER BY tgl desc
                limit 5
            )c on a.kode = c.kode
            where a.kode not in ('JKT88') 
            ORDER BY c.tgl asc
            limit 5    
        ";
        $jum = $this->db->query($sql);

        $output['recordsTotal']=$output['recordsFiltered']=$jum->num_rows();
        }
        $nomor_urut=$start+1;

        foreach ($query->result_array() as $kode) {

          $output['data'][]=array( 
            $nomor_urut,
            $kode['nama_comp']." (".$kode['kode'].")",
            $kode['tgl'],
          );
        $nomor_urut++;
        }

        echo json_encode($output);
    }

    function get_sales_tahunan(){

        $get_kode_alamat = $this->model_inventory->get_kode_alamat();
        $code = '';
        foreach ($get_kode_alamat as $key) {
            $code.= ","."'".$key->kode_alamat."'";
        }
        $kode_alamat = preg_replace('/,/', '', $code,1);
        // echo $kode_alamat;

        $id_user=$this->session->userdata('id');
        $draw=$_REQUEST['draw'];
        $length=10;
        // $length=$_REQUEST['length'];
        // $start=$_REQUEST['start'];
        $start=0;
        $search=$_REQUEST['search']["value"];

        $sql_total = "
        select a.supp, b.namasupp, a.total_2021, a.total_2022, (a.total_2022 - a.total_2021) as gap_tahunan
        from
        (
            select a.supp, sum(a.total_2021) as total_2021, sum(a.total_2022) as total_2022
            from 
            (
                select a.supp, sum(x) as total_2021, '' as total_2022
                from
                (
                    select a.supp, sum(tot1) as x
                    from data2021.fi a
                    GROUP BY a.supp
                    union all
                    select a.supp, sum(tot1) as x
                    from data2021.ri a
                    GROUP BY a.supp
                )a GROUP BY supp
        
                union all
        
                select a.supp, '' as total_2021, sum(x) as total_2022
                from
                (
                    select a.supp, sum(tot1) as x
                    from data2022.fi a
                    GROUP BY a.supp
                    union all
                    select a.supp, sum(tot1) as x
                    from data2022.ri a
                    GROUP BY a.supp
                )a GROUP BY supp
            )a GROUP BY a.supp
        )a left join (
            select a.supp,a.namasupp
            from mpm.tabsupp a
        )b on a.supp = b.supp
        ";
        $total = $this->db->query($sql_total)->num_rows();

        $output=array();
        $output['draw']=$draw;
        $output['recordsTotal']=$output['recordsFiltered']=$total;
        $output['data']=array();

        if($search!=""){
        $sql = "
            
        select a.supp, b.namasupp, a.total_2021, a.total_2022, (a.total_2022 - a.total_2021) as gap_tahunan
        from
        (
            select a.supp, sum(a.total_2021) as total_2021, sum(a.total_2022) as total_2022
            from 
            (
                select a.supp, sum(x) as total_2021, '' as total_2022
                from
                (
                    select a.supp, sum(tot1) as x
                    from data2021.fi a
                    GROUP BY a.supp
                    union all
                    select a.supp, sum(tot1) as x
                    from data2021.ri a
                    GROUP BY a.supp
                )a GROUP BY supp
        
                union all
        
                select a.supp, '' as total_2021, sum(x) as total_2022
                from
                (
                    select a.supp, sum(tot1) as x
                    from data2022.fi a
                    GROUP BY a.supp
                    union all
                    select a.supp, sum(tot1) as x
                    from data2022.ri a
                    GROUP BY a.supp
                )a GROUP BY supp
            )a GROUP BY a.supp
        )a left join (
            select a.supp,a.namasupp
            from mpm.tabsupp a
        )b on a.supp = b.supp
            limit $start,$length
        ";
        }else{
            $sql = "
                
            select a.supp, b.namasupp, a.total_2021, a.total_2022, (a.total_2022 - a.total_2021) as gap_tahunan
            from
            (
                select a.supp, sum(a.total_2021) as total_2021, sum(a.total_2022) as total_2022
                from 
                (
                    select a.supp, sum(x) as total_2021, '' as total_2022
                    from
                    (
                        select a.supp, sum(tot1) as x
                        from data2021.fi a
                        GROUP BY a.supp
                        union all
                        select a.supp, sum(tot1) as x
                        from data2021.ri a
                        GROUP BY a.supp
                    )a GROUP BY supp
            
                    union all
            
                    select a.supp, '' as total_2021, sum(x) as total_2022
                    from
                    (
                        select a.supp, sum(tot1) as x
                        from data2022.fi a
                        GROUP BY a.supp
                        union all
                        select a.supp, sum(tot1) as x
                        from data2022.ri a
                        GROUP BY a.supp
                    )a GROUP BY supp
                )a GROUP BY a.supp
            )a left join (
                select a.supp,a.namasupp
                from mpm.tabsupp a
            )b on a.supp = b.supp
                limit $start,$length     
            ";
        }
        $query = $this->db->query($sql);

        if($search!=""){
        $this->db->like("nama_comp",$search);
        $sql = "        
        select a.supp, b.namasupp, a.total_2021, a.total_2022, (a.total_2022 - a.total_2021) as gap_tahunan
        from
        (
            select a.supp, sum(a.total_2021) as total_2021, sum(a.total_2022) as total_2022
            from 
            (
                select a.supp, sum(x) as total_2021, '' as total_2022
                from
                (
                    select a.supp, sum(tot1) as x
                    from data2021.fi a
                    GROUP BY a.supp
                    union all
                    select a.supp, sum(tot1) as x
                    from data2021.ri a
                    GROUP BY a.supp
                )a GROUP BY supp
        
                union all
        
                select a.supp, '' as total_2021, sum(x) as total_2022
                from
                (
                    select a.supp, sum(tot1) as x
                    from data2022.fi a
                    GROUP BY a.supp
                    union all
                    select a.supp, sum(tot1) as x
                    from data2022.ri a
                    GROUP BY a.supp
                )a GROUP BY supp
            )a GROUP BY a.supp
        )a left join (
            select a.supp,a.namasupp
            from mpm.tabsupp a
        )b on a.supp = b.supp
        ";
        $jum = $this->db->query($sql);

        $output['recordsTotal']=$output['recordsFiltered']=$jum->num_rows();
        }
        $nomor_urut=$start+1;

        foreach ($query->result_array() as $kode) {

          $output['data'][]=array( 
            $nomor_urut,
            $kode['namasupp'],
            $kode['tahun_2021'],
            $kode['tahun_2022'],
            $kode['gap_tahunan'],
          );
        $nomor_urut++;
        }

        echo json_encode($output);
    }

    function get_sales_bulanan(){

        $get_kode_alamat = $this->model_inventory->get_kode_alamat();
        $code = '';
        foreach ($get_kode_alamat as $key) {
            $code.= ","."'".$key->kode_alamat."'";
        }
        $kode_alamat = preg_replace('/,/', '', $code,1);
        // echo $kode_alamat;

        $id_user=$this->session->userdata('id');
        $draw=$_REQUEST['draw'];
        $length=10;
        // $length=$_REQUEST['length'];
        // $start=$_REQUEST['start'];
        $start=0;
        $search=$_REQUEST['search']["value"];

        $sql_total = "
        select bulan_2021, bulan_2022, (bulan_2022 - bulan_2021) as gap
        from
        (
            select sum(bulan_2021) as bulan_2021, sum(bulan_2022) as bulan_2022
            from
            (
                select '2021', sum(x) as bulan_2021, '' as bulan_2022
                from
                (
                    select sum(tot1) as x 
                    from data2021.fi a 
                    where bulan = 2
                    GROUP BY a.supp 
                    union all 
                    select sum(tot1) as x 
                    from data2021.ri a 
                    where bulan = 2
                    GROUP BY a.supp 
                )a 
                union all
                select '2022', '',sum(x) as bulan
                from
                (
                    select sum(tot1) as x 
                    from data2022.fi a 
                    where bulan = 2
                    GROUP BY a.supp 
                    union all 
                    select sum(tot1) as x 
                    from data2022.ri a 
                    where bulan = 2
                    GROUP BY a.supp 
                )a
            )a
        )a
        ";
        $total = $this->db->query($sql_total)->num_rows();

        $output=array();
        $output['draw']=$draw;
        $output['recordsTotal']=$output['recordsFiltered']=$total;
        $output['data']=array();

        if($search!=""){
        $sql = "
            select bulan_2021, bulan_2022, (bulan_2022 - bulan_2021) as gap
            from
            (
                select sum(bulan_2021) as bulan_2021, sum(bulan_2022) as bulan_2022
                from
                (
                    select '2021', sum(x) as bulan_2021, '' as bulan_2022
                    from
                    (
                        select sum(tot1) as x 
                        from data2021.fi a 
                        where bulan = 2
                        GROUP BY a.supp 
                        union all 
                        select sum(tot1) as x 
                        from data2021.ri a 
                        where bulan = 2
                        GROUP BY a.supp 
                    )a 
                    union all            
                    select '2022', '',sum(x) as bulan
                    from
                    (
                        select sum(tot1) as x 
                        from data2022.fi a 
                        where bulan = 2
                        GROUP BY a.supp 
                        union all 
                        select sum(tot1) as x 
                        from data2022.ri a 
                        where bulan = 2
                        GROUP BY a.supp 
                    )a
                )a
            )a
            limit $start,$length
        ";
        }else{
            $sql = "
                select bulan_2021, bulan_2022, (bulan_2022 - bulan_2021) as gap
                from
                (
                    select sum(bulan_2021) as bulan_2021, sum(bulan_2022) as bulan_2022
                    from
                    (
                        select '2021', sum(x) as bulan_2021, '' as bulan_2022
                        from
                        (
                            select sum(tot1) as x 
                            from data2021.fi a 
                            where bulan = 2
                            GROUP BY a.supp 
                            union all 
                            select sum(tot1) as x 
                            from data2021.ri a 
                            where bulan = 2
                            GROUP BY a.supp 
                        )a 
                        union all
                
                        select '2022', '',sum(x) as bulan
                        from
                        (
                            select sum(tot1) as x 
                            from data2022.fi a 
                            where bulan = 2
                            GROUP BY a.supp 
                            union all 
                            select sum(tot1) as x 
                            from data2022.ri a 
                            where bulan = 2
                            GROUP BY a.supp 
                        )a
                    )a
                )a            
                limit $start,$length     
            ";
        }
        $query = $this->db->query($sql);

        if($search!=""){
        $this->db->like("nama_comp",$search);
        $sql = "        
        select bulan_2021, bulan_2022, (bulan_2022 - bulan_2021) as gap
        from
        (
            select sum(bulan_2021) as bulan_2021, sum(bulan_2022) as bulan_2022
            from
            (
                select '2021', sum(x) as bulan_2021, '' as bulan_2022
                from
                (
                    select sum(tot1) as x 
                    from data2021.fi a 
                    where bulan = 2
                    GROUP BY a.supp 
                    union all 
                    select sum(tot1) as x 
                    from data2021.ri a 
                    where bulan = 2
                    GROUP BY a.supp 
                )a 
                union all

                select '2022', '',sum(x) as bulan
                from
                (
                    select sum(tot1) as x 
                    from data2022.fi a 
                    where bulan = 2
                    GROUP BY a.supp 
                    union all 
                    select sum(tot1) as x 
                    from data2022.ri a 
                    where bulan = 2
                    GROUP BY a.supp 
                )a
            )a
        )a
        ";
        $jum = $this->db->query($sql);

        $output['recordsTotal']=$output['recordsFiltered']=$jum->num_rows();
        }
        $nomor_urut=$start+1;

        foreach ($query->result_array() as $kode) {

          $output['data'][]=array( 
            $nomor_urut,
            $kode['bulan_2021'],
            $kode['bulan_2022'],
            $kode['gap'],
          );
        $nomor_urut++;
        }

        echo json_encode($output);
    }

    function ambil_data(){

        $get_kode_alamat = $this->model_inventory->get_kode_alamat();
        $code = '';
        foreach ($get_kode_alamat as $key) {
            $code.= ","."'".$key->kode_alamat."'";
        }
        $kode_alamat = preg_replace('/,/', '', $code,1);
        // echo $kode_alamat;

        $id_user=$this->session->userdata('id');
        $draw=$_REQUEST['draw'];
        $length=$_REQUEST['length'];
        $start=$_REQUEST['start'];
        $search=$_REQUEST['search']["value"];

        $sql_total = "
            select * from db_master_data.t_temp_monitoring_stock_suggest_po a
            where a.kode in ($kode_alamat)  
        ";
        $total = $this->db->query($sql_total)->num_rows();

        $output=array();
        /*Token yang dikrimkan client, akan dikirim balik ke client*/
        $output['draw']=$draw;
        $output['recordsTotal']=$output['recordsFiltered']=$total;
        $output['data']=array();

        if($search!=""){
        $sql = "
            select * from db_master_data.t_temp_monitoring_stock_suggest_po a 
            where a.kode in ($kode_alamat) and (nama_comp like '%$search%' or namaprod like '%$search%')
            order by a.branch_name, a.nama_comp, a.kodeprod        
            limit $start,$length        
        ";
        }else{

            $sql = "
                select * from db_master_data.t_temp_monitoring_stock_suggest_po a 
                where a.kode in ($kode_alamat)    
                order by a.branch_name, a.nama_comp, a.kodeprod        
                limit $start,$length        
            ";
        }
        $query = $this->db->query($sql);

        if($search!=""){
        $this->db->like("nama_comp",$search);
        // $jum=$this->db->get('mpm.tbl_tabcomp');

        $sql = "select * from db_master_data.t_temp_monitoring_stock_suggest_po";
        $jum = $this->db->query($sql);

        $output['recordsTotal']=$output['recordsFiltered']=$jum->num_rows();
        }
        $nomor_urut=$start+1;

        foreach ($query->result_array() as $kode) {

            $y = anchor(base_url().'inventory/ms_edit/'.$kode['signature'],'edit',[
                'class' => 'btn btn-primary',
                'role'  => 'button',
                'target' => 'blank'
            ]);

          $output['data'][]=array( 
            $y,
            // $nomor_urut,
            // '<button class="fa fa-edit fa-xl btn-info" target="blank" id="testOnclick" onclick="getEditIDProduct('.$kode['kodeprod'].')"></button>',
            $kode['branch_name'],
            $kode['nama_comp'],
          );
        $nomor_urut++;
        }

        echo json_encode($output);
    }



    function get_bulan_berjalan(){

        $supp = $this->session->userdata('supp');
        if ($supp == '000') {
            $suppx = "a.supp in ('001','002','004','005','012','013','015')";
        }else{
            $suppx = "a.supp in ($supp)";
        }
        
        $this->load->model('model_per_hari');
        

        $tahun_sekarang = date('Y');
        $bulan_sekarang = date('m');
        $tanggal_sekarang = date('d');
        // echo "bulan : ".$bulan_sekarang;
        // echo "tanggal : ".$tanggal_sekarang;

        if ($tanggal_sekarang <= '6') {
            $bulan_sekarang_x = (int)$bulan_sekarang - 1; 
            $bulan_sekarang_a = $bulan_sekarang_x - 1; 

        }else{
            $bulan_sekarang_x = (int)$bulan_sekarang;
            $bulan_sekarang_a = $bulan_sekarang_x - 1; 
        }

        // echo "bulan : ".$bulan_sekarang_x;
        // echo "bulan : ".$bulan_sekarang_a;

        $wilayah_nocab = $this->model_per_hari->wilayah_nocab($this->session->userdata('id'));

        if ($wilayah_nocab == null) {
            $wilayah = '';
        }else{
            $wilayah = "and right(a.kode,2) in ($wilayah_nocab)";
        }

        $get_kode_alamat = $this->model_inventory->get_kode_alamat();
        $code = '';
        foreach ($get_kode_alamat as $key) {
            $code.= ","."'".$key->kode_alamat."'";
        }
        $kode_alamat = preg_replace('/,/', '', $code,1);
        // echo $kode_alamat;

        $id_user=$this->session->userdata('id');
        $draw=$_REQUEST['draw'];
        // $length=$_REQUEST['length'];
        $length=10;
        // $start=$_REQUEST['start'];
        $start=0;
        $search=$_REQUEST['search']["value"];

        $sql_total = "
        select namasupp, format(sum(a.tot1_$bulan_sekarang_x),0) as x, format(sum(a.tot2_$bulan_sekarang_x),0) as y,
                        format((sum(a.tot2_$bulan_sekarang_x) - sum(a.tot1_$bulan_sekarang_x)),0) as gap, a.created_date,
                        round((sum(a.tot2_$bulan_sekarang_x) - sum(a.tot1_$bulan_sekarang_x)) / sum(a.tot1_$bulan_sekarang_x) * 100) as percent
                from db_temp.t_temp_dashboard_sales a
                where a.supp in ('001','002','004','005','012','013','015') and a.kode in ($kode_alamat)
                GROUP BY namasupp        
                order by (sum(a.tot2_$bulan_sekarang_x) - sum(a.tot1_$bulan_sekarang_x)) desc  
        ";
        $total = $this->db->query($sql_total)->num_rows();

        $output=array();
        /*Token yang dikrimkan client, akan dikirim balik ke client*/
        $output['draw']=$draw;
        $output['recordsTotal']=$output['recordsFiltered']=$total;
        $output['data']=array();

        if($search!=""){
        $sql = "
            select namasupp, format(sum(a.tot1_$bulan_sekarang_x),0) as x, format(sum(a.tot2_$bulan_sekarang_x),0) as y,
            format((sum(a.tot2_$bulan_sekarang_x) - sum(a.tot1_$bulan_sekarang_x)),0) as gap, a.created_date,
            round((sum(a.tot2_$bulan_sekarang_x) - sum(a.tot1_$bulan_sekarang_x)) / sum(a.tot1_$bulan_sekarang_x) * 100) as percent
            from db_temp.t_temp_dashboard_sales a
            where $suppx and a.kode in ($kode_alamat)
            GROUP BY namasupp        
            order by (sum(a.tot2_$bulan_sekarang_x) - sum(a.tot1_$bulan_sekarang_x)) desc       
            limit $start,$length        
        ";
        }else{

            $sql = "
                select namasupp, format(sum(a.tot1_$bulan_sekarang_x),0) as x, format(sum(a.tot2_$bulan_sekarang_x),0) as y,
                        format((sum(a.tot2_$bulan_sekarang_x) - sum(a.tot1_$bulan_sekarang_x)),0) as gap_x, a.created_date,
                        round((sum(a.tot2_$bulan_sekarang_x) - sum(a.tot1_$bulan_sekarang_x)) / sum(a.tot1_$bulan_sekarang_x) * 100) as percent_x,
                        format(sum(a.tot1_$bulan_sekarang_a),0) as a, format(sum(a.tot2_$bulan_sekarang_a),0) as b,
                        format((sum(a.tot2_$bulan_sekarang_a) - sum(a.tot1_$bulan_sekarang_a)),0) as gap_a,
                        round((sum(a.tot2_$bulan_sekarang_a) - sum(a.tot1_$bulan_sekarang_a)) / sum(a.tot1_$bulan_sekarang_a) * 100) as percent_a
                from db_temp.t_temp_dashboard_sales a
                where $suppx $wilayah
                GROUP BY namasupp        
                order by a.supp asc
                limit $start,$length        
            ";
        }
        $query = $this->db->query($sql);

        if($search!=""){
        $this->db->like("nama_comp",$search);
        // $jum=$this->db->get('mpm.tbl_tabcomp');

        $sql = "
        select namasupp, format(sum(a.tot1_$bulan_sekarang_x),0) as x, format(sum(a.tot2_$bulan_sekarang_x),0) as y,
        format((sum(a.tot2_$bulan_sekarang_x) - sum(a.tot1_$bulan_sekarang_x)),0) as gap, a.created_date,
        round((sum(a.tot2_$bulan_sekarang_x) - sum(a.tot1_$bulan_sekarang_x)) / sum(a.tot1_$bulan_sekarang_x) * 100) as percent
from db_temp.t_temp_dashboard_sales a
where a.supp in ('001','002','004','005','012','013','015') and a.kode in ($kode_alamat)
GROUP BY namasupp        
order by (sum(a.tot2_$bulan_sekarang_x) - sum(a.tot1_$bulan_sekarang_x)) desc"
        ;
        $jum = $this->db->query($sql);

        $output['recordsTotal']=$output['recordsFiltered']=$jum->num_rows();
        }
        $nomor_urut=$start+1;

        foreach ($query->result_array() as $kode) {

     

          $output['data'][]=array( 
            // $y,
            // $nomor_urut,
            // '<button class="fa fa-edit fa-xl btn-info" target="blank" id="testOnclick" onclick="getEditIDProduct('.$kode['kodeprod'].')"></button>',
            $kode['namasupp'],
            $kode['a'],
            $kode['b'],
            // $kode['gap']." (".$kode['percent'].")",
            $kode['percent_a'].' %',
            $kode['x'],
            $kode['y'],
            // $kode['gap']." (".$kode['percent'].")",
            $kode['percent_x'].' %',
            $kode['created_date'],
          );
        $nomor_urut++;
        }

        echo json_encode($output);
    }

    function get_kalender_data(){

        $tahun_sekarang = date('Y');
        $bulan_sekarang = date('m');
        $tanggal_sekarang = date('d');
        // echo "bulan : ".$bulan_sekarang;
        // echo "tanggal : ".$tanggal_sekarang;

        if ($tanggal_sekarang <= '10') {
            $bulan_sekarang_x = (int)$bulan_sekarang - 1; 
        }else{
            $bulan_sekarang_x = $bulan_sekarang;
        }

        $id_user=$this->session->userdata('id');
        $draw=$_REQUEST['draw'];
        // $length=5;
        $length=$_REQUEST['length'];
        $start=$_REQUEST['start'];
        $search=$_REQUEST['search']["value"];

        // $sql_total = "
        //     select concat(a.kode_comp,a.nocab) as kode from mpm.tbl_tabcomp a
        //     where concat(a.kode_comp,a.nocab) in ($kode_alamat)  
        // ";
        $sql_total = "
        select a.kode, a.branch_name, left(a.nama_comp,25) as nama_comp,c.tgl
                from
                (
                    SELECT concat(a.kode_comp, a.nocab) as kode, a.branch_name, a.nama_comp, a.urutan
                    FROM mpm.tbl_tabcomp a
                    where a.`status` = 1 and a.active = 1
                    GROUP BY concat(a.kode_comp, a.nocab)
                )a INNER join 
                (
                    select concat(a.kode_comp,a.nocab) as kode
                    from db_dp.t_dp a
                    where a.tahun = $tahun_sekarang
                )b on a.kode = b.kode LEFT JOIN (
                    select 	concat(a.kode_comp,a.nocab) as kode, 
                            concat(max(hrdok), '-', max(blndok), '-', max(a.thndok)) as tgl
                    from data$tahun_sekarang.fi a
                    where bulan in ($bulan_sekarang_x)
                    GROUP BY concat(a.kode_comp,a.nocab)
                    ORDER BY tgl desc, concat(a.kode_comp,a.nocab) asc
                    
                )c on a.kode = c.kode
                ORDER BY urutan
                limit $start,$length 
        ";
        $total = $this->db->query($sql_total)->num_rows();

        $output=array();
        /*Token yang dikrimkan client, akan dikirim balik ke client*/
        $output['draw']=$draw;
        $output['recordsTotal']=$output['recordsFiltered']=$total;
        $output['data']=array();

        if($search!=""){
        $sql = "
            
        select a.kode, a.branch_name, left(a.nama_comp,25) as nama_comp,c.tgl
                from
                (
                    SELECT concat(a.kode_comp, a.nocab) as kode, a.branch_name, a.nama_comp, a.urutan
                    FROM mpm.tbl_tabcomp a
                    where a.`status` = 1 and a.active = 1
                    GROUP BY concat(a.kode_comp, a.nocab)
                )a INNER join 
                (
                    select concat(a.kode_comp,a.nocab) as kode
                    from db_dp.t_dp a
                    where a.tahun = $tahun_sekarang
                )b on a.kode = b.kode LEFT JOIN (
                    select 	concat(a.kode_comp,a.nocab) as kode, 
                            concat(max(hrdok), '-', max(blndok), '-', max(a.thndok)) as tgl
                    from data$tahun_sekarang.fi a
                    where bulan in ($bulan_sekarang_x)
                    GROUP BY concat(a.kode_comp,a.nocab)
                    ORDER BY tgl desc, concat(a.kode_comp,a.nocab) asc
                    
                )c on a.kode = c.kode
                ORDER BY urutan
                limit $start,$length 
        ";
        }else{

            $sql = "
                
                select a.kode, a.branch_name, left(a.nama_comp,25) as nama_comp,c.tgl
                from
                (
                    SELECT concat(a.kode_comp, a.nocab) as kode, a.branch_name, a.nama_comp, a.urutan
                    FROM mpm.tbl_tabcomp a
                    where a.`status` = 1 and a.active = 1
                    GROUP BY concat(a.kode_comp, a.nocab)
                )a INNER join 
                (
                    select concat(a.kode_comp,a.nocab) as kode
                    from db_dp.t_dp a
                    where a.tahun = $tahun_sekarang
                )b on a.kode = b.kode LEFT JOIN (
                    select 	concat(a.kode_comp,a.nocab) as kode, 
                            concat(max(hrdok), '-', max(blndok), '-', max(a.thndok)) as tgl
                    from data$tahun_sekarang.fi a
                    where bulan in ($bulan_sekarang_x)
                    GROUP BY concat(a.kode_comp,a.nocab)
                    ORDER BY tgl desc, concat(a.kode_comp,a.nocab) asc
                    
                )c on a.kode = c.kode
                ORDER BY urutan
                limit $start,$length     
            ";
        }
        $query = $this->db->query($sql);

        if($search!=""){
        $this->db->like("nama_comp",$search);
        // $jum=$this->db->get('mpm.tbl_tabcomp');

        // $sql = "select * from mpm.tbl_tabcomp";
        $sql = "        
        select a.kode, a.branch_name, left(a.nama_comp,25) as nama_comp,c.tgl
                from
                (
                    SELECT concat(a.kode_comp, a.nocab) as kode, a.branch_name, a.nama_comp, a.urutan
                    FROM mpm.tbl_tabcomp a
                    where a.`status` = 1 and a.active = 1
                    GROUP BY concat(a.kode_comp, a.nocab)
                )a INNER join 
                (
                    select concat(a.kode_comp,a.nocab) as kode
                    from db_dp.t_dp a
                    where a.tahun = $tahun_sekarang
                )b on a.kode = b.kode LEFT JOIN (
                    select 	concat(a.kode_comp,a.nocab) as kode, 
                            concat(max(hrdok), '-', max(blndok), '-', max(a.thndok)) as tgl
                    from data$tahun_sekarang.fi a
                    where bulan in ($bulan_sekarang_x)
                    GROUP BY concat(a.kode_comp,a.nocab)
                    ORDER BY tgl desc, concat(a.kode_comp,a.nocab) asc
                    
                )c on a.kode = c.kode
                ORDER BY urutan
                limit $start,$length 
        ";
        $jum = $this->db->query($sql);

        $output['recordsTotal']=$output['recordsFiltered']=$jum->num_rows();
        }
        $nomor_urut=$start+1;

        foreach ($query->result_array() as $kode) {

            // $y = anchor(base_url().'inventory/ms_edit/'.$kode['signature'],'edit',[
            //     'class' => 'btn btn-primary',
            //     'role'  => 'button',
            //     'target' => 'blank'
            // ]);

          $output['data'][]=array( 
            // $y,
            // $nomor_urut,
            // '<button class="fa fa-edit fa-xl btn-info" target="blank" id="testOnclick" onclick="getEditIDProduct('.$kode['kodeprod'].')"></button>',
            $kode['branch_name'],
            $kode['nama_comp'],
            $kode['tgl'],
          );
        $nomor_urut++;
        }

        echo json_encode($output);
    }

    public function detail_kalender_data($site_code){

        $this->load->model('model_dashboard');
    
        $this->db->where('username', substr($site_code,0,3));
        $get_userid = $this->db->get('mpm.user')->row();
        $userid = $get_userid->id;
        // echo "userid : ".$userid;
        // die;

        $get_company = $get_userid->company;


        $data = [
            'id'          => $this->session->userdata('id'),
            'title'       => 'Pengajuan Relokasi Stock',
            'get_label'   => $this->M_menu->get_label(),
            'detail_kalender_data'     => $this->model_dashboard->detail_kalender_data($userid),
            'company'     => $get_company
        ];

        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar', $data);
        $this->load->view('dashboard/detail_kalender_data', $data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
    

    }



}
?>
