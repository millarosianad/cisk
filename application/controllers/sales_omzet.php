<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Sales_omzet extends MY_Controller
{
    function sales_omzet()
    {
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
        set_time_limit(0);
        $this->load->library(array('table','template','Excel_generator', 'form_validation','email'));
        $this->load->helper('url');
        $this->load->helper('csv');
        $this->load->model('model_omzet');
        $this->load->model('M_menu');
        $this->load->model('model_per_hari');
        $this->load->model('model_sales_omzet');
        $this->load->model('model_sales');
        $this->load->model('model_outlet_transaksi');
        $this->load->database();
    }

    public function line_sold(){
        $data = [
            'id' => $this->session->userdata('id'),
            'url' => 'sales_omzet/line_sold_hasil/',
            'title' => 'Line Sold',
            'get_label' => $this->M_menu->get_label(),
            'get_supp' => $this->model_omzet->getSuppbyid(),
        ];
        $supp = $this->session->userdata('supp');
        $data['header_supp'] = $this->model_outlet_transaksi->get_header_supp($supp);
        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar',$data);
        $this->load->view('template_claim/top_content',$data);
        $this->load->view('sales_omzet/line_sold',$data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
    }

    public function line_sold_hasil(){
        $kodeprod = $this->input->post('options');
        $code = '';
        foreach($kodeprod as $kode)
        {
            $code.=",".$kode;
        }

        $data = [
            'id' => $this->session->userdata('id'),
            'url' => 'sales_omzet/line_sold_hasil/',
            'title' => 'Line Sold',
            'get_label' => $this->M_menu->get_label(),
            'get_supp' => $this->model_omzet->getSuppbyid(),
            'periode_1' => $this->input->post('periode_1'),
            'periode_2' => $this->input->post('periode_2'),
            'breakdown_1' => $this->input->post('breakdown_1'),
            'breakdown_2' => $this->input->post('breakdown_2'),
            'breakdown_3' => $this->input->post('breakdown_3'),
            'breakdown_4' => $this->input->post('breakdown_4'),
            'kodeprod'  => preg_replace('/,/', '', $code,1),
        ];

        // $supp = $this->input->post('supp');
        // $group = $this->input->post('group');
        $breakdown_1 = $this->input->post('breakdown_1');
        $breakdown_2 = $this->input->post('breakdown_2');
        $breakdown_3 = $this->input->post('breakdown_3');
        $breakdown_4 = $this->input->post('breakdown_4');        

        // if ($group == '0') {
        //     $data['cari_kodeprod'] = $this->model_per_hari->cari_kodeprod_supp($supp);
        // }else{
        //     $data['cari_kodeprod'] = $this->model_per_hari->cari_kodeprod($group);
        // }

        $data['wilayah_nocab'] = $this->model_per_hari->wilayah_nocab($this->session->userdata('id'));
        // $data['namasupp'] = $this->model_per_hari->get_namasupp($supp);
        // $data['namagroup'] = $this->model_per_hari->get_namagroup($group);
        
        $data['proses'] = $this->model_sales_omzet->line_sold($data);

        if ($breakdown_2 == '1' && $breakdown_3 == '' && $breakdown_4 == '') {
            $view_content = 'line_sold_hasil_kodesales';
        }
        elseif
            ($breakdown_2 == '1' && $breakdown_3 == '1' && $breakdown_4 == '') {
                $view_content = 'line_sold_hasil_kodesales_kodesalur';
        }
        elseif
            ($breakdown_2 == '1' && $breakdown_3 == '' && $breakdown_4 == '1') {
                $view_content = 'line_sold_hasil_kodesales_type';
        }
        elseif
            ($breakdown_2 == '1' && $breakdown_3 == '1' && $breakdown_4 == '1') {
                $view_content = 'line_sold_hasil_kodesales_kodesalur_type';
        }
        elseif
            ($breakdown_2 == '' && $breakdown_3 == '1' && $breakdown_4 == '') {
                $view_content = 'line_sold_hasil_kodesalur';
        }
        elseif
            ($breakdown_2 == '' && $breakdown_3 == '1' && $breakdown_4 == '1') {
                $view_content = 'line_sold_hasil_kodesalur_type';
        }
        elseif
            ($breakdown_2 == '' && $breakdown_3 == '' && $breakdown_4 == '1') {
                $view_content = 'line_sold_hasil_type';
        }
        else{
            $view_content = 'line_sold_hasil';
        }


        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar',$data);
        $this->load->view('template_claim/top_content',$data);
        $this->load->view("sales_omzet/$view_content",$data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
    }

    public function export_csv_linesold() {
        
        $id=$this->session->userdata('id');
        if ( function_exists( 'date_default_timezone_set' ) )
        date_default_timezone_set('Asia/Jakarta');        
        $tgl_created= date('d-m-Y') ;
        $kondisi = $this->uri->segment('3');

        //kodesales
        if ($kondisi === '1'){
        $query="
            select  substr(kode,1,3) as kode_comp, a.kode,a.branch_name,a.nama_comp,a.kodesales, a.namasales,a.faktur as total_faktur, a.produk as total_produk, a.line_sold
            from db_temp.t_temp_line_sold a
            where a.id = $id and a.created_date = (select max(created_date) from db_temp.t_temp_line_sold where id = $id)
        ";                        
        $hsl = $this->db->query($query);
        query_to_csv($hsl,TRUE,'Line Sold - breakdown salesman.csv');
        }
        //kodesales, kodesalur
        elseif ($kondisi === '2'){
        $query="
            select  substr(kode,1,3) as kode_comp, a.kode,a.branch_name,a.nama_comp,a.kodesales, a.namasales, a.kodesalur, a.namasalur, a.faktur as total_faktur, a.produk as total_produk, a.line_sold
            from db_temp.t_temp_line_sold a
            where a.id = $id and a.created_date = (select max(created_date) from db_temp.t_temp_line_sold where id = $id)
        ";                        
        $hsl = $this->db->query($query);
        query_to_csv($hsl,TRUE,'Line Sold - breakdown salesman dan class.csv');
        }
        //kodesales, kode_type
        elseif ($kondisi === '3'){
        $query="
            select  substr(kode,1,3) as kode_comp, a.kode,a.branch_name,a.nama_comp,a.kodesales, a.namasales, a.kode_type, a.nama_type, a.faktur as total_faktur, a.produk as total_produk, a.line_sold
            from db_temp.t_temp_line_sold a
            where a.id = $id and a.created_date = (select max(created_date) from db_temp.t_temp_line_sold where id = $id)
        ";                        
        $hsl = $this->db->query($query);
        query_to_csv($hsl,TRUE,'Line Sold - breakdown salesman dan type.csv');
        }
        //kodesales, kodesalur, kode_type
        elseif ($kondisi === '4'){
        $query="
            select  substr(kode,1,3) as kode_comp, a.kode,a.branch_name,a.nama_comp,a.kodesales, a.namasales, a.kodesalur, a.namasalur, a.kode_type, a.nama_type, a.faktur as total_faktur, a.produk as total_produk, a.line_sold
            from db_temp.t_temp_line_sold a
            where a.id = $id and a.created_date = (select max(created_date) from db_temp.t_temp_line_sold where id = $id)
        ";                        
        $hsl = $this->db->query($query);
        query_to_csv($hsl,TRUE,'Line Sold - breakdown salesman, class dan type.csv');
        }
        //kodesalur
        elseif ($kondisi === '5'){
        $query="
            select  substr(kode,1,3) as kode_comp, a.kode,a.branch_name,a.nama_comp, a.kodesalur, a.namasalur,a.faktur as total_faktur, a.produk as total_produk, a.line_sold
            from db_temp.t_temp_line_sold a
            where a.id = $id and a.created_date = (select max(created_date) from db_temp.t_temp_line_sold where id = $id)
        ";                        
        $hsl = $this->db->query($query);
        query_to_csv($hsl,TRUE,'Line Sold - breakdown class.csv');
        }
        //kodesalur, kode_type
        elseif ($kondisi === '6'){
        $query="
            select  substr(kode,1,3) as kode_comp, a.kode,a.branch_name,a.nama_comp, a.kodesalur, a.namasalur, a.kode_type, a.nama_type, a.faktur as total_faktur, a.produk as total_produk, a.line_sold
            from db_temp.t_temp_line_sold a
            where a.id = $id and a.created_date = (select max(created_date) from db_temp.t_temp_line_sold where id = $id)
        ";                        
        $hsl = $this->db->query($query);
        query_to_csv($hsl,TRUE,'Line Sold - breakdown class dan type.csv');
        }
        //kode_type
        elseif ($kondisi === '7'){
        $query="
            select  substr(kode,1,3) as kode_comp, a.kode,a.branch_name,a.nama_comp, a.kode_type, a.nama_type, a.faktur as total_faktur, a.produk as total_produk, a.line_sold
            from db_temp.t_temp_line_sold a
            where a.id = $id and a.created_date = (select max(created_date) from db_temp.t_temp_line_sold where id = $id)
        ";                        
        $hsl = $this->db->query($query);
        query_to_csv($hsl,TRUE,'Line Sold - breakdown type.csv');
        }
        else{
        $query="
            select  substr(kode,1,3) as kode_comp, a.kode,a.branch_name,a.nama_comp,a.faktur as total_faktur, a.produk as total_produk, a.line_sold
            from db_temp.t_temp_line_sold a 
            where a.id = $id and a.created_date = (select max(created_date) from db_temp.t_temp_line_sold where id = $id)
        ";                        
        $hsl = $this->db->query($query);
        query_to_csv($hsl,TRUE,'Line Sold.csv');
        }
    }

    public function omzet(){

        $id = $this->session->userdata('id');
        // if ($id == 297) {
            
        // }else{
        //     redirect('sales_omzet/omzet_dp');
        // }

        $data = [
            'id' => $this->session->userdata('id'),
            'url' => 'sales_omzet/omzet_hasil',
            // 'url' => 'sales_omzet/#',
            'title' => 'Omzet DP',
            'query' => $this->model_omzet->getSuppbyid(),
            'get_label' => $this->M_menu->get_label()
        ];
        $this->load->view('template_claim/top_header');
        // $this->load->view('template/top_header_bootstrap5');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar',$data);
        $this->load->view('template_claim/top_content',$data);
        $this->load->view('sales_omzet/omzet',$data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
    }

    public function omzet_hasil(){
        $from = $this->input->post('from');
        $to = $this->input->post('to');
        $output = $this->input->post('output');      
        $principal =  $this->input->post('principal');
        $deltomed =  $this->input->post('deltomed');
        $us =  $this->input->post('us');
        $marguna =  $this->input->post('marguna');
        $jaya =  $this->input->post('jaya');
        $intrafood =  $this->input->post('intrafood');
        $strive =  $this->input->post('strive');
        $hni =  $this->input->post('hni');
        $natura =  $this->input->post('natura');
        $vonix =  $this->input->post('vonix');
        $mdj =  $this->input->post('mdj');

        if ($principal === "XXX") {   
            $kodeprod = $this->model_sales_omzet->get_kodeprod($principal);
        }else{
            $deltomed!=null ? $supp_deltomed = $deltomed : $supp_deltomed = null;
            $us!=null ? $supp_us = $us : $supp_us = null;
            $marguna!=null ? $supp_marguna = $marguna : $supp_marguna = null;
            $jaya!=null ? $supp_jaya = $jaya : $supp_jaya = null;
            $intrafood!=null ? $supp_intrafood = $intrafood : $supp_intrafood = null;
            $strive!=null ? $supp_strive = $strive : $supp_strive = null;
            $hni!=null ? $supp_hni = $hni : $supp_hni = null;
            $natura!=null ? $supp_natura = $natura : $supp_natura = null;
            $vonix!=null ? $supp_vonix = $vonix : $supp_vonix = null;
            $mdj!=null ? $supp_mdj = $mdj : $supp_mdj = null;

            $split =  str_split($supp_deltomed.$supp_us.$supp_marguna.$supp_jaya.$supp_intrafood.$supp_strive.$supp_hni.$supp_natura.$supp_vonix.$supp_mdj,"3");

            $implode = implode(",",$split);
            $kodeprod = $this->model_sales_omzet->get_kodeprod($implode);
        }
        
        $data = [
            'id'        => $this->session->userdata('id'),
            'get_label' => $this->M_menu->get_label(),
            'created_date' => $this->model_sales_omzet->timezone(),
            'supp'      => $this->model_omzet->get_supp(),
            'from'      => $this->input->post('from'),
            'to'        => $this->input->post('to'),
            'tahun_from'=> substr($from,0,4),
            'tahun_to'  => substr($to,0,4),
            'output'    => $this->input->post('output'),
            'bulan_closing'=> $this->input->post('bulan_closing'),
            'kodeprod'=> $kodeprod,
            'wilayah_nocab' => $this->model_per_hari->wilayah_nocab($this->session->userdata('id'))
        ];

        if ($output == '1') {
            $data['break'] = "group by kode";
            $proses_core = $this->model_sales_omzet->omzet($data);
            if ($proses_core) {
                $data['header'] = "a.branch_name, a.nama_comp, unit, omzet, ot, urutan";
                $data['header_total'] = "'GRAND TOTAL', '', sum(unit), sum(omzet), sum(ot), 999"; 
                $data['proses'] = $this->model_sales_omzet->omzet_break_dp($data);                
            }else{
            }   
            $data['output_title'] = "omzet breakdown dp";
            $data['url_export'] = "export_omzet_breakdown_dp";
            $view = "omzet_breakdown_dp";
        }elseif($output == '2'){
            $data['break'] = "group by kode,bulan";
            $proses_core = $this->model_sales_omzet->omzet($data);
            if ($proses_core) {
                $data['header'] = "a.branch_name, a.nama_comp, a.bulan, unit, omzet, ot, urutan";
                $data['header_total'] = "'GRAND TOTAL', '', '', sum(unit), sum(omzet), sum(ot), 999";
                $data['proses'] = $this->model_sales_omzet->omzet_break_dp($data);                
            }else{
            }
            $data['output_title'] = "omzet breakdown dp,bulan";
            $data['url_export'] = "export_omzet_breakdown_dp_bulan";
            $view = "omzet_breakdown_dp_bulan";
        }elseif($output == '3'){
            $data['break'] = "group by supp";
            $proses_core = $this->model_sales_omzet->omzet($data);
            if ($proses_core) {
                $data['header'] = "a.namasupp, unit, omzet, ot, urutan";
                $data['header_total'] = "'GRAND TOTAL', sum(unit), sum(omzet), sum(ot), 999";
                $data['proses'] = $this->model_sales_omzet->omzet_break_principal($data);                
            }else{
            }
            $data['output_title'] = "omzet breakdown principal";
            $data['url_export'] = "export_omzet_breakdown_principal";
            $view = "omzet_breakdown_principal";
        }elseif($output == '4'){
            $data['break'] = "group by supp,bulan";
            $proses_core = $this->model_sales_omzet->omzet($data);
            if ($proses_core) {
                $data['header'] = "a.namasupp, a.bulan, unit, omzet, ot, urutan";
                $data['header_total'] = "'GRAND TOTAL', '',sum(unit), sum(omzet), sum(ot), 999";
                $data['proses'] = $this->model_sales_omzet->omzet_break_principal($data);                
            }else{
            }
            $data['output_title'] = "omzet breakdown principal, bulan";
            $data['url_export'] = "export_omzet_breakdown_principal_bulan";
            $view = "omzet_breakdown_principal_bulan";
        }

        $data['title'] = 'Omzet DP';
        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar',$data);
        $this->load->view('template_claim/top_content',$data);
        $this->load->view("sales_omzet/$view",$data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');  

    }

    public function export_omzet_breakdown_dp(){
        $id = $this->session->userdata('id');
        $sql = "
            select  a.branch_name, a.nama_comp, a.unit, a.omzet, a.ot 
            from    db_temp.t_temp_omzet a
            where   a.created_by = $id and a.created_date = (
                select max(created_date) from db_temp.t_temp_omzet where created_by = $id
            )
            ORDER BY a.urutan
        ";
        $proses = $this->db->query($sql);              
        query_to_csv($proses,TRUE,'Omzet Breakdown DP.csv');
    }

    public function export_omzet_breakdown_dp_bulan(){
        $id = $this->session->userdata('id');
        $sql = "
            select  a.branch_name, a.nama_comp, a.bulan, a.unit, a.omzet, a.ot 
            from    db_temp.t_temp_omzet a
            where   a.created_by = $id and a.created_date = (
                select max(created_date) from db_temp.t_temp_omzet where created_by = $id
            )
            ORDER BY a.urutan
        ";
        $proses = $this->db->query($sql);              
        query_to_csv($proses,TRUE,'Omzet Breakdown DP Bulan.csv');
    }

    public function export_omzet_breakdown_principal(){
        $id = $this->session->userdata('id');
        $sql = "
            select  a.namasupp, a.unit, a.omzet, a.ot
            from    db_temp.t_temp_omzet a
            where   a.created_by = $id and a.created_date = (
                select max(created_date) from db_temp.t_temp_omzet where created_by = $id
            )
            ORDER BY a.namasupp
        ";
        $proses = $this->db->query($sql);              
        query_to_csv($proses,TRUE,'Omzet Breakdown Principal.csv');
    }

    public function export_omzet_breakdown_principal_bulan(){
        $id = $this->session->userdata('id');
        $sql = "
            select  a.namasupp, a.bulan, a.unit, a.omzet, a.ot
            from    db_temp.t_temp_omzet a
            where   a.created_by = $id and a.created_date = (
                select max(created_date) from db_temp.t_temp_omzet where created_by = $id
            )
            ORDER BY a.namasupp
        ";
        $proses = $this->db->query($sql);              
        query_to_csv($proses,TRUE,'Omzet Breakdown Principal Bulan.csv');
    }

    public function omzet_dp(){
        $data = [
            'id' => $this->session->userdata('id'),
            'url' => 'sales_omzet/omzet_dp_hasil',
            'title' => 'Omzet DP',
            'query' => $this->model_omzet->getSuppbyid(),
            'get_label' => $this->M_menu->get_label()
        ];
        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar',$data);
        $this->load->view('template_claim/top_content',$data);
        $this->load->view('sales_omzet/omzet_dp',$data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
    }

    public function omzet_dp_hasil()
    {
        $data = [
            'id'        => $this->session->userdata('id'),
            'url'       => 'sales_omzet/omzet_dp_hasil/',
            'get_label' => $this->M_menu->get_label(),
            'get_supp'  => $this->model_omzet->getSuppbyid(),
            'supp'      => $this->input->post('supp'),
            'tahun'     => $this->input->post('tahun'),
            'bulan'     => $this->input->post('bulan'),
            'group'     => $this->input->post('group'),
            'tipe_1'    => $this->input->post('tipe_1'),
            'tipe_2'    => $this->input->post('tipe_2'),
            'tipe_3'    => $this->input->post('tipe_3'),
            'wilayah_nocab' => $this->model_per_hari->wilayah_nocab($this->session->userdata('id'))
        ];
        $group = $this->input->post('group');
        $supp = $this->input->post('supp');
        $tipe_1 = $this->input->post('tipe_1');
        $tipe_2 = $this->input->post('tipe_2');
        $tipe_3 = $this->input->post('tipe_3');
        if ($tipe_1 == 1 || $tipe_2 == 1 || $tipe_3 == 1) {
            $data['kode_type'] = $this->model_per_hari->get_kode_type($tipe_1,$tipe_2,$tipe_3);
        }else{
            $data['kode_type'] = '';
        }
        $data['namasupp'] = $this->model_per_hari->get_namasupp($supp);
        $data['namagroup'] = $this->model_per_hari->get_namagroup($group);

        // echo "<br><br><br><br><br><br>group : ".$group;
        if ($group == '0') {
            // echo "a";
            $data['cari_kodeprod'] = $this->model_per_hari->cari_kodeprod_supp($supp);
            // $data['cari_kodeprod'] = $this->model_per_hari->cari_kodeprod_supp($supp);




        }else{
            // echo "b";
            $data['cari_kodeprod'] = $this->model_per_hari->cari_kodeprod($group);
        }

        $data['proses'] = $this->model_sales_omzet->omzet_dp($data);

        if ($tipe_1 == 1){
            $data['tipex_1'] = "APOTIK";
        }else{
            $data['tipex_1'] = "";
        }

        if ($tipe_2 == 1){
            $data['tipex_2'] = "PBF";
        }else{
            $data['tipex_2'] = "";
        }

        if ($tipe_3 == 1){
            $data['tipex_3'] = "MTI";
        }else{
            $data['tipex_3'] = "";
        }

        $data['title'] = 'Omzet DP';
        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar',$data);
        $this->load->view('template_claim/top_content',$data);
        $this->load->view("sales_omzet/omzet_dp_hasil",$data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');            
    }
    
    public function export_omzet(){
        $id = $this->session->userdata('id');
        $sql = "
        select  substr(kode,1,3) as kode_comp, a.kode,a.branch_name,a.nama_comp, 
                a.b1,a.b2,a.b3,a.b4,a.b5,a.b6,a.b7,a.b8,a.b9,a.b10,a.b11,a.b12,
                a.t1,a.t2,a.t3,a.t4,a.t5,a.t6,a.t7,a.t8,a.t9,a.t10,a.t11,a.t12,
                a.total, a.rata as avg, if(a.status_closing = 1,'closing','belum') as status_closing, a.lastupload
        from    db_temp.t_temp_omzet_dp a 
        where   id = $id and created_date = (select max(created_date) from db_temp.t_temp_omzet_dp where id = $id) 
        order by urutan asc
        ";
        $proses = $this->db->query($sql);              
        query_to_csv($proses,TRUE,'Omzet DP.csv');
    }

    public function info(){
        $this->load->view('info');
    }

    public function sell_out_product(){           

        // $id = $this->session->userdata('id');
        // if($id == 297){

        // }else{
        //     $this->info();
        // }
        $data = [
            'id' => $this->session->userdata('id'),
            'url' => 'sales_omzet/sell_out_product_hasil/',
            'title' => 'Sell Out Product',
            'get_label' => $this->M_menu->get_label(),
            'get_supp' => $this->model_omzet->getSuppbyid(),
        ];

        $supp = $this->session->userdata('supp');
        $data['header_supp'] = $this->model_outlet_transaksi->get_header_supp($supp);
        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar',$data);
        $this->load->view('template_claim/top_content',$data);
        $this->load->view('sales_omzet/sell_out_product',$data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');

    }

    public function sell_out_product_kosong(){           

       $message = "Pilih salah satu breakdown";
       echo "<script type='text/javascript'>alert('$message');</script>"; 

       echo anchor('sales_omzet/sell_out_product','<h2>klik disini untuk kembali</h2>');
    }

    public function sell_out_product_belum_tersedia(){           

        $message = "Halaman Belum Tersedia";
        echo "<script type='text/javascript'>alert('$message');</script>"; 
 
        echo anchor('sales_omzet/sell_out_product','<h2>klik disini untuk kembali</h2>');
     }

   public function sell_out_product_hasil(){
        $kodeprod = $this->input->post('options');
        $code = '';
        foreach($kodeprod as $kode)
        {
            $code.=",".$kode;
        }

        $data = [
            'id' => $this->session->userdata('id'),
            'url' => 'sales_omzet/sell_out_product_hasil/',
            'title' => 'Sell Out Product',
            'get_label' => $this->M_menu->get_label(),
            'get_supp' => $this->model_omzet->getSuppbyid(),
            'year' => $this->input->post('year'),
            'groupby' => $this->input->post('groupby'),
            'kondisi_class' => $this->input->post('kondisi_class'),
            'custom_outlet' => $this->input->post('custom_outlet'),
            'satuan' => $this->input->post('satuan'),
            'kodeprod'  => preg_replace('/,/', '', $code,1),
            'wilayah_nocab' => $this->model_per_hari->wilayah_nocab($this->session->userdata('id')),
            'breakdown_kodeprod' => $this->input->post('breakdown_kodeprod'),
            'breakdown_kodesalur' => $this->input->post('breakdown_kodesalur'),
            'breakdown_type' => $this->input->post('breakdown_type'),
            'breakdown_kode' => $this->input->post('breakdown_kode'),
            'breakdown_group' => $this->input->post('breakdown_group'),
            'breakdown_subgroup' => $this->input->post('breakdown_subgroup'),
            'breakdown_salesman' => $this->input->post('breakdown_salesman')
        ];

        $kdc=$this->input->post('kondisi_class');
        $data['kdc']=$kdc;
        if($kdc == 1){
            $data['kdc'] = "Pencatatan di faktur transaksi";
        }else{
            $data['kdc'] = "Data terbaru";
        }

        $breakdown_kodeprod = $this->input->post('breakdown_kodeprod');
        $breakdown_kodesalur = $this->input->post('breakdown_kodesalur');
        $breakdown_type = $this->input->post('breakdown_type');
        $breakdown_kode = $this->input->post('breakdown_kode');
        $breakdown_group = $this->input->post('breakdown_group');
        $breakdown_subgroup = $this->input->post('breakdown_subgroup');
        $breakdown_salesman = $this->input->post('breakdown_salesman');

        if ($breakdown_kode == '' && $breakdown_kodeprod == '' && $breakdown_kodesalur == '' && $breakdown_type == '' && $breakdown_group == '' && $breakdown_subgroup == ''  && $breakdown_salesman == '') {
            redirect('sales_omzet/sell_out_product_kosong');
        }
        // 1 pilihan
        elseif($breakdown_kode == '1' && $breakdown_kodeprod == '' && $breakdown_kodesalur == '' && $breakdown_type == '' && $breakdown_group == '' && $breakdown_subgroup == '' && $breakdown_salesman == ''){
            $view_content = 'sell_out_product_hasil_kode';
        }elseif($breakdown_kode == '' && $breakdown_kodeprod == '1' && $breakdown_kodesalur == '' && $breakdown_type == '' && $breakdown_group == '' && $breakdown_subgroup == '' && $breakdown_salesman == ''){
            $view_content = 'sell_out_product_hasil_kodeprod';
        }elseif($breakdown_kode == '' && $breakdown_kodeprod == '' && $breakdown_kodesalur == '1' && $breakdown_type == '' && $breakdown_group == '' && $breakdown_subgroup == '' && $breakdown_salesman == ''){
            $view_content = 'sell_out_product_hasil_kodesalur';
        }elseif($breakdown_kode == '' && $breakdown_kodeprod == '' && $breakdown_kodesalur == '' && $breakdown_type == '1' && $breakdown_group == '' && $breakdown_subgroup == '' && $breakdown_salesman == ''){
            $view_content = 'sell_out_product_hasil_kode_type';
        }elseif($breakdown_kode == '' && $breakdown_kodeprod == '' && $breakdown_kodesalur == '' && $breakdown_type == '' && $breakdown_group == '1' && $breakdown_subgroup == '' && $breakdown_salesman == ''){
            $view_content = 'sell_out_product_hasil_grup';
        }elseif($breakdown_kode == '' && $breakdown_kodeprod == '' && $breakdown_kodesalur == '' && $breakdown_type == '' && $breakdown_group == '' && $breakdown_subgroup == '1' && $breakdown_salesman == ''){
            $view_content = 'sell_out_product_hasil_subgrup';
        }elseif ($breakdown_kode == '' && $breakdown_kodeprod == '' && $breakdown_kodesalur == '' && $breakdown_type == '' && $breakdown_group == '' && $breakdown_subgroup == '' && $breakdown_salesman == '1') {
            $view_content = 'sell_out_product_hasil_salesman';
        }
        
        // 2 pilihan
        elseif($breakdown_kode == '1' && $breakdown_kodeprod == '1' && $breakdown_kodesalur == '' && $breakdown_type == '' && $breakdown_group == '' && $breakdown_subgroup == '' && $breakdown_salesman == ''){
            $view_content = 'sell_out_product_hasil_kode_kodeprod';
        }elseif($breakdown_kode == '1' && $breakdown_kodeprod == '' && $breakdown_kodesalur == '1' && $breakdown_type == '' && $breakdown_group == '' && $breakdown_subgroup == '' && $breakdown_salesman == ''){
            $view_content = 'sell_out_product_hasil_kode_kodesalur';
        }elseif($breakdown_kode == '1' && $breakdown_kodeprod == '' && $breakdown_kodesalur == '' && $breakdown_type == '1' && $breakdown_group == '' && $breakdown_subgroup == '' && $breakdown_salesman == ''){
            $view_content = 'sell_out_product_hasil_kode_kode_type';
        }elseif($breakdown_kode == '1' && $breakdown_kodeprod == '' && $breakdown_kodesalur == '' && $breakdown_type == '' && $breakdown_group == '1' && $breakdown_subgroup == '' && $breakdown_salesman == ''){
            $view_content = 'sell_out_product_hasil_kode_grup';
        }elseif($breakdown_kode == '1' && $breakdown_kodeprod == '' && $breakdown_kodesalur == '' && $breakdown_type == '' && $breakdown_group == '' && $breakdown_subgroup == '1' && $breakdown_salesman == ''){
            $view_content = 'sell_out_product_hasil_kode_subgrup';
        } elseif ($breakdown_kode == '1' && $breakdown_kodeprod == '' && $breakdown_kodesalur == '' && $breakdown_type == '' && $breakdown_group == '' && $breakdown_subgroup == '' && $breakdown_salesman == '1' ) {
            $view_content = 'sell_out_product_hasil_kode_salesman';
        } 
        
        elseif($breakdown_kode == '' && $breakdown_kodeprod == '1' && $breakdown_kodesalur == '1' && $breakdown_type == '' && $breakdown_group == '' && $breakdown_subgroup == ''){
            $view_content = 'sell_out_product_hasil_kodeprod_kodesalur';
        }elseif($breakdown_kode == '' && $breakdown_kodeprod == '1' && $breakdown_kodesalur == '' && $breakdown_type == '1' && $breakdown_group == '' && $breakdown_subgroup == ''){
            $view_content = 'sell_out_product_hasil_kodeprod_kode_type';
        }elseif($breakdown_kode == '' && $breakdown_kodeprod == '1' && $breakdown_kodesalur == '' && $breakdown_type == '' && $breakdown_group == '1' && $breakdown_subgroup == ''){
            $view_content = 'sell_out_product_hasil_kodeprod_grup';
        }elseif($breakdown_kode == '' && $breakdown_kodeprod == '1' && $breakdown_kodesalur == '' && $breakdown_type == '' && $breakdown_group == '' && $breakdown_subgroup == '1'){
            $view_content = 'sell_out_product_hasil_kodeprod_subgrup';
        }
        
        elseif($breakdown_kode == '' && $breakdown_kodeprod == '' && $breakdown_kodesalur == '1' && $breakdown_type == '1' && $breakdown_group == '' && $breakdown_subgroup == ''){
            $view_content = 'sell_out_product_hasil_kodesalur_kode_type';
        }elseif($breakdown_kode == '' && $breakdown_kodeprod == '' && $breakdown_kodesalur == '1' && $breakdown_type == '' && $breakdown_group == '1' && $breakdown_subgroup == ''){
            $view_content = 'sell_out_product_hasil_kodesalur_grup';
        }elseif($breakdown_kode == '' && $breakdown_kodeprod == '' && $breakdown_kodesalur == '1' && $breakdown_type == '' && $breakdown_group == '' && $breakdown_subgroup == '1'){
            $view_content = 'sell_out_product_hasil_kodesalur_subgrup';
        }

        elseif($breakdown_kode == '' && $breakdown_kodeprod == '' && $breakdown_kodesalur == '' && $breakdown_type == '1' && $breakdown_group == '1' && $breakdown_subgroup == ''){
            $view_content = 'sell_out_product_hasil_kode_type_grup';
        }elseif($breakdown_kode == '' && $breakdown_kodeprod == '' && $breakdown_kodesalur == '' && $breakdown_type == '1' && $breakdown_group == '' && $breakdown_subgroup == '1'){
            $view_content = 'sell_out_product_hasil_kode_type_subgrup';
        }
        elseif($breakdown_kode == '' && $breakdown_kodeprod == '' && $breakdown_kodesalur == '' && $breakdown_type == '' && $breakdown_group == '1' && $breakdown_subgroup == '1'){
            $view_content = 'sell_out_product_hasil_grup_subgrup';
        }

        // 3 pilihan
        
        elseif($breakdown_kode == '1' && $breakdown_kodeprod == '1' && $breakdown_kodesalur == '1' && $breakdown_type == '' && $breakdown_group == '' && $breakdown_subgroup == ''){
            $view_content = 'sell_out_product_hasil_kode_kodeprod_kodesalur';
        }elseif($breakdown_kode == '1' && $breakdown_kodeprod == '1' && $breakdown_kodesalur == '' && $breakdown_type == '1' && $breakdown_group == '' && $breakdown_subgroup == ''){
            $view_content = 'sell_out_product_hasil_kode_kodeprod_kode_type';
        }elseif($breakdown_kode == '1' && $breakdown_kodeprod == '1' && $breakdown_kodesalur == '' && $breakdown_type == '' && $breakdown_group == '1' && $breakdown_subgroup == ''){
            $view_content = 'sell_out_product_hasil_kode_kodeprod_grup';
        }elseif($breakdown_kode == '1' && $breakdown_kodeprod == '' && $breakdown_kodesalur == '1' && $breakdown_type == '1' && $breakdown_group == '' && $breakdown_subgroup == ''){
            $view_content = 'sell_out_product_hasil_kode_kodesalur_kode_type';
        }elseif($breakdown_kode == '1' && $breakdown_kodeprod == '' && $breakdown_kodesalur == '1' && $breakdown_type == '' && $breakdown_group == '1' && $breakdown_subgroup == ''){
            $view_content = 'sell_out_product_hasil_kode_kodesalur_grup';
        }elseif($breakdown_kode == '1' && $breakdown_kodeprod == '' && $breakdown_kodesalur == '' && $breakdown_type == '1' && $breakdown_group == '1' && $breakdown_subgroup == ''){
            $view_content = 'sell_out_product_hasil_kode_kode_type_grup';
        }elseif($breakdown_kode == '1' && $breakdown_kodeprod == '1' && $breakdown_kodesalur == '' && $breakdown_type == '' && $breakdown_group == '' && $breakdown_subgroup == '1'){
            $view_content = 'sell_out_product_hasil_kode_kodeprod_subgrup';
        }elseif($breakdown_kode == '1' && $breakdown_kodeprod == '' && $breakdown_kodesalur == '1' && $breakdown_type == '' && $breakdown_group == '' && $breakdown_subgroup == '1'){
                $view_content = 'sell_out_product_hasil_kode_kodesalur_subgrup';
        }elseif($breakdown_kode == '1' && $breakdown_kodeprod == '' && $breakdown_kodesalur == '' && $breakdown_type == '1' && $breakdown_group == '' && $breakdown_subgroup == '1'){
            $view_content = 'sell_out_product_hasil_kode_kodetype_subgrup';
        }elseif($breakdown_kode == '1' && $breakdown_kodeprod == '' && $breakdown_kodesalur == '' && $breakdown_type == '' && $breakdown_group == '1' && $breakdown_subgroup == '1'){
            $view_content = 'sell_out_product_hasil_kode_grup_subgrup';
        }
        
        
        elseif($breakdown_kode == '' && $breakdown_kodeprod == '1' && $breakdown_kodesalur == '1' && $breakdown_type == '1' && $breakdown_group == '' && $breakdown_subgroup == ''){
            $view_content = 'sell_out_product_hasil_kodeprod_kodesalur_kode_type';
        }elseif($breakdown_kode == '' && $breakdown_kodeprod == '1' && $breakdown_kodesalur == '1' && $breakdown_type == '' && $breakdown_group == '1' && $breakdown_subgroup == ''){
            $view_content = 'sell_out_product_hasil_kodeprod_kodesalur_grup';
        }elseif($breakdown_kode == '' && $breakdown_kodeprod == '1' && $breakdown_kodesalur == '' && $breakdown_type == '1' && $breakdown_group == '1' && $breakdown_subgroup == ''){
            $view_content = 'sell_out_product_hasil_kodeprod_kode_type_grup';
        }elseif($breakdown_kode == '' && $breakdown_kodeprod == '1' && $breakdown_kodesalur == '1' && $breakdown_type == '' && $breakdown_group == '' && $breakdown_subgroup == '1'){
            $view_content = 'sell_out_product_hasil_kodeprod_kodesalur_subgrup';
        }elseif($breakdown_kode == '' && $breakdown_kodeprod == '1' && $breakdown_kodesalur == '' && $breakdown_type == '1' && $breakdown_group == '' && $breakdown_subgroup == '1'){
            $view_content = 'sell_out_product_hasil_kodeprod_kode_type_subgrup';
        }elseif($breakdown_kode == '' && $breakdown_kodeprod == '1' && $breakdown_kodesalur == '' && $breakdown_type == '' && $breakdown_group == '1' && $breakdown_subgroup == '1'){
            $view_content = 'sell_out_product_hasil_kodeprod_grup_subgrup';
        }
        
        
        elseif($breakdown_kode == '' && $breakdown_kodeprod == '' && $breakdown_kodesalur == '1' && $breakdown_type == '1' && $breakdown_group == '1' && $breakdown_subgroup == ''){
            $view_content = 'sell_out_product_hasil_kodesalur_kode_type_grup';
        }elseif($breakdown_kode == '' && $breakdown_kodeprod == '' && $breakdown_kodesalur == '1' && $breakdown_type == '1' && $breakdown_group == '' && $breakdown_subgroup == '1'){
            $view_content = 'sell_out_product_hasil_kodesalur_kode_type_subgrup';
        }elseif($breakdown_kode == '' && $breakdown_kodeprod == '' && $breakdown_kodesalur == '1' && $breakdown_type == '' && $breakdown_group == '1' && $breakdown_subgroup == '1'){
            $view_content = 'sell_out_product_hasil_kodesalur_grup_subgrup';
        }

        elseif($breakdown_kode == '' && $breakdown_kodeprod == '' && $breakdown_kodesalur == '' && $breakdown_type == '1' && $breakdown_group == '1' && $breakdown_subgroup == '1'){
            $view_content = 'sell_out_product_hasil_kode_type_grup_subgrup';
        }

       // 4 pilihan
        elseif($breakdown_kode == '1' && $breakdown_kodeprod == '1' && $breakdown_kodesalur == '1' && $breakdown_type == '1' && $breakdown_group == '' && $breakdown_subgroup == ''){
            $view_content = 'sell_out_product_hasil_kode_kodeprod_kodesalur_kode_type';
        }elseif($breakdown_kode == '1' && $breakdown_kodeprod == '1' && $breakdown_kodesalur == '1' && $breakdown_type == '' && $breakdown_group == '1' && $breakdown_subgroup == ''){
            $view_content = 'sell_out_product_hasil_kode_kodeprod_kodesalur_grup';
        }elseif($breakdown_kode == '1' && $breakdown_kodeprod == '1' && $breakdown_kodesalur == '' && $breakdown_type == '1' && $breakdown_group == '1' && $breakdown_subgroup == ''){
            $view_content = 'sell_out_product_hasil_kode_kodeprod_kode_type_grup';
        }elseif($breakdown_kode == '1' && $breakdown_kodeprod == '' && $breakdown_kodesalur == '1' && $breakdown_type == '1' && $breakdown_group == '1' && $breakdown_subgroup == ''){
            $view_content = 'sell_out_product_hasil_kode_kodesalur_kode_type_grup';
        }elseif($breakdown_kode == '1' && $breakdown_kodeprod == '1' && $breakdown_kodesalur == '1' && $breakdown_type == '' && $breakdown_group == '' && $breakdown_subgroup == '1'){
            $view_content = 'sell_out_product_hasil_kode_kodeprod_kodesalur_subgroup';
        }elseif($breakdown_kode == '1' && $breakdown_kodeprod == '1' && $breakdown_kodesalur == '' && $breakdown_type == '1' && $breakdown_group == '' && $breakdown_subgroup == '1'){
            $view_content = 'sell_out_product_hasil_kode_kodeprod_kode_type_subgroup';
        }elseif($breakdown_kode == '1' && $breakdown_kodeprod == '1' && $breakdown_kodesalur == '' && $breakdown_type == '' && $breakdown_group == '1' && $breakdown_subgroup == '1'){
            $view_content = 'sell_out_product_hasil_kode_kodeprod_grup_subgroup';
        }elseif($breakdown_kode == '1' && $breakdown_kodeprod == '' && $breakdown_kodesalur == '1' && $breakdown_type == '1' && $breakdown_group == '' && $breakdown_subgroup == '1'){
            $view_content = 'sell_out_product_hasil_kode_kodesalur_kode_type_subgroup';
        }elseif($breakdown_kode == '1' && $breakdown_kodeprod == '' && $breakdown_kodesalur == '1' && $breakdown_type == '' && $breakdown_group == '1' && $breakdown_subgroup == '1'){
            $view_content = 'sell_out_product_hasil_kode_kodesalur_grup_subgroup';
        }elseif($breakdown_kode == '1' && $breakdown_kodeprod == '' && $breakdown_kodesalur == '' && $breakdown_type == '1' && $breakdown_group == '1' && $breakdown_subgroup == '1'){
            $view_content = 'sell_out_product_hasil_kod_kode_type_grup_subgroup';
        }
        
        elseif($breakdown_kode == '' && $breakdown_kodeprod == '1' && $breakdown_kodesalur == '1' && $breakdown_type == '1' && $breakdown_group == '1' && $breakdown_subgroup == ''){
            $view_content = 'sell_out_product_hasil_kodeprod_kodesalur_kode_type_grup';
        }elseif($breakdown_kode == '' && $breakdown_kodeprod == '1' && $breakdown_kodesalur == '1' && $breakdown_type == '1' && $breakdown_group == '' && $breakdown_subgroup == '1'){
            $view_content = 'sell_out_product_hasil_kodeprod_kodesalur_kode_type_subgrup';
        }elseif($breakdown_kode == '' && $breakdown_kodeprod == '1' && $breakdown_kodesalur == '1' && $breakdown_type == '' && $breakdown_group == '1' && $breakdown_subgroup == '1'){
            $view_content = 'sell_out_product_hasil_kodeprod_kodesalur_grup_subgrup';
        }elseif($breakdown_kode == '' && $breakdown_kodeprod == '1' && $breakdown_kodesalur == '' && $breakdown_type == '1' && $breakdown_group == '1' && $breakdown_subgroup == '1'){
            $view_content = 'sell_out_product_hasil_kodeprod_kode_type_grup_subgrup';
        }
        
        elseif($breakdown_kode == '' && $breakdown_kodeprod == '' && $breakdown_kodesalur == '1' && $breakdown_type == '1' && $breakdown_group == '1' && $breakdown_subgroup == '1'){
            $view_content = 'sell_out_product_hasil_kodesalur_kode_type_grup_subgrup';
        }
        // //5 pilihan
        // elseif($breakdown_kode == '1' && $breakdown_kodeprod == '1' && $breakdown_kodesalur == '1' && $breakdown_type == '1' && $breakdown_group == '1' && $breakdown_subgroup == ''){
        //     $view_content = 'sell_out_product_hasil_kode_kodeprod_kodesalur_kode_type_grup';
        // }elseif($breakdown_kode == '1' && $breakdown_kodeprod == '1' && $breakdown_kodesalur == '1' && $breakdown_type == '1' && $breakdown_group == '' && $breakdown_subgroup == '1'){
        //     $view_content = 'sell_out_product_hasil_kode_kodeprod_kodesalur_kode_type_subgrup';
        // }elseif($breakdown_kode == '1' && $breakdown_kodeprod == '1' && $breakdown_kodesalur == '1' && $breakdown_type == '' && $breakdown_group == '1' && $breakdown_subgroup == '1'){
        //     $view_content = 'sell_out_product_hasil_kode_kodeprod_kodesalur_grup_subgrup';
        // }elseif($breakdown_kode == '1' && $breakdown_kodeprod == '1' && $breakdown_kodesalur == '' && $breakdown_type == '1' && $breakdown_group == '1' && $breakdown_subgroup == '1'){
        //     $view_content = 'sell_out_product_hasil_kode_kodeprod_kode_type_grup_subgrup';
        // }elseif($breakdown_kode == '1' && $breakdown_kodeprod == '' && $breakdown_kodesalur == '1' && $breakdown_type == '1' && $breakdown_group == '1' && $breakdown_subgroup == '1'){
        //     $view_content = 'sell_out_product_hasil_kode_kodesalur_kode_type_grup_subgrup';
        // }elseif($breakdown_kode == '' && $breakdown_kodeprod == '1' && $breakdown_kodesalur == '1' && $breakdown_type == '1' && $breakdown_group == '1' && $breakdown_subgroup == '1'){
        //     $view_content = 'sell_out_product_hasil_kodeprod_kodesalur_kode_type_grup_subgrup';
        // }

        // //6pilihan
        // elseif($breakdown_kode == '1' && $breakdown_kodeprod == '1' && $breakdown_kodesalur == '1' && $breakdown_type == '1' && $breakdown_group == '1' && $breakdown_subgroup == '1'){
        //     $view_content = 'sell_out_product_hasil_kode_kodeprod_kodesalur_kode_type_grup_subgrup';
        // }
        
        else{
            redirect('sales_omzet/sell_out_product_belum_tersedia');
        }

        $data['proses'] = $this->model_sales_omzet->sales_per_product($data);
        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar',$data);
        $this->load->view('template_claim/top_content',$data);
        $this->load->view("sales_omzet/$view_content",$data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');            
    }

    public function export_sales_per_product_kode(){
        $id=$this->session->userdata('id');
        $query="
            select  substr(kode,1,3) as kode_comp, kode, branch_name,nama_comp,
                    unit_1,unit_2,unit_3,unit_4,unit_5,unit_6,
                    unit_7,unit_8,unit_9,unit_10,unit_11,unit_12,
                    value_1,value_2,value_3,value_4,value_5,value_6,
                    value_7,value_8,value_9,value_10,value_11,value_12, 
                    ot_1,ot_2,ot_3,ot_4,ot_5,ot_6,
                    ot_7,ot_8,ot_9,ot_10,ot_11,ot_12,
                    ec_1,ec_2,ec_3,ec_4,ec_5,ec_6,
                    ec_7,ec_8,ec_9,ec_10,ec_11,ec_12               
            from   db_temp.t_temp_soprod
            where  id = $id and created_date = (select max(created_date) from db_temp.t_temp_soprod where id = $id)
            ORDER BY urutan asc
        ";                            
        $hasil = $this->db->query($query);
        query_to_csv($hasil,TRUE,"Sell Out Product.csv");
    }

    public function export_sales_per_product_kodeprod(){
        $id=$this->session->userdata('id');
        $query="
            select  kodeprod, namaprod,`group`,nama_group,
                    unit_1,unit_2,unit_3,unit_4,unit_5,unit_6,
                    unit_7,unit_8,unit_9,unit_10,unit_11,unit_12,
                    value_1,value_2,value_3,value_4,value_5,value_6,
                    value_7,value_8,value_9,value_10,value_11,value_12, 
                    ot_1,ot_2,ot_3,ot_4,ot_5,ot_6,
                    ot_7,ot_8,ot_9,ot_10,ot_11,ot_12,
                    ec_1,ec_2,ec_3,ec_4,ec_5,ec_6,
                    ec_7,ec_8,ec_9,ec_10,ec_11,ec_12               
            from   db_temp.t_temp_soprod
            where  id = $id and created_date = (select max(created_date) from db_temp.t_temp_soprod where id = $id)
            ORDER BY urutan asc
        ";                            
        $hasil = $this->db->query($query);
        query_to_csv($hasil,TRUE,"Sell Out Product.csv");
    }

    public function export_sales_per_product_kodesalur(){
        $id=$this->session->userdata('id');
        $query="
            select  groupsalur,
                    unit_1,unit_2,unit_3,unit_4,unit_5,unit_6,
                    unit_7,unit_8,unit_9,unit_10,unit_11,unit_12,
                    value_1,value_2,value_3,value_4,value_5,value_6,
                    value_7,value_8,value_9,value_10,value_11,value_12, 
                    ot_1,ot_2,ot_3,ot_4,ot_5,ot_6,
                    ot_7,ot_8,ot_9,ot_10,ot_11,ot_12,
                    ec_1,ec_2,ec_3,ec_4,ec_5,ec_6,
                    ec_7,ec_8,ec_9,ec_10,ec_11,ec_12               
            from   db_temp.t_temp_soprod
            where  id = $id and created_date = (select max(created_date) from db_temp.t_temp_soprod where id = $id)
            ORDER BY urutan asc
        ";                            
        $hasil = $this->db->query($query);
        query_to_csv($hasil,TRUE,"Sell Out Product.csv");
    }

    public function export_sales_per_product_kode_type(){
        $id=$this->session->userdata('id');
        $query="
            select  kode_type, nama_type, sektor, segment,
                    unit_1,unit_2,unit_3,unit_4,unit_5,unit_6,
                    unit_7,unit_8,unit_9,unit_10,unit_11,unit_12,
                    value_1,value_2,value_3,value_4,value_5,value_6,
                    value_7,value_8,value_9,value_10,value_11,value_12, 
                    ot_1,ot_2,ot_3,ot_4,ot_5,ot_6,
                    ot_7,ot_8,ot_9,ot_10,ot_11,ot_12,
                    ec_1,ec_2,ec_3,ec_4,ec_5,ec_6,
                    ec_7,ec_8,ec_9,ec_10,ec_11,ec_12               
            from   db_temp.t_temp_soprod
            where  id = $id and created_date = (select max(created_date) from db_temp.t_temp_soprod where id = $id)
            ORDER BY urutan asc
        ";                            
        $hasil = $this->db->query($query);
        query_to_csv($hasil,TRUE,"Sell Out Product.csv");
    }

    public function export_sales_per_product_salesman()
    {
        $id = $this->session->userdata('id');
        $query = "
            select  kode, kodesales, namasales,
                    unit_1,unit_2,unit_3,unit_4,unit_5,unit_6,
                    unit_7,unit_8,unit_9,unit_10,unit_11,unit_12,
                    value_1,value_2,value_3,value_4,value_5,value_6,
                    value_7,value_8,value_9,value_10,value_11,value_12, 
                    ot_1,ot_2,ot_3,ot_4,ot_5,ot_6,
                    ot_7,ot_8,ot_9,ot_10,ot_11,ot_12,
                    ec_1,ec_2,ec_3,ec_4,ec_5,ec_6,
                    ec_7,ec_8,ec_9,ec_10,ec_11,ec_12               
            from   db_temp.t_temp_soprod
            where  id = $id and created_date = (select max(created_date) from db_temp.t_temp_soprod where id = $id)
            ORDER BY urutan asc
        ";
        $hasil = $this->db->query($query);
        query_to_csv($hasil, TRUE, "Sell Out Product.csv");
    }

    public function export_sales_per_product_kode_kodeprod(){
        $id=$this->session->userdata('id');
        $query="
            select  substr(kode,1,3) as kode_comp, kode, branch_name,nama_comp, kodeprod,namaprod,
                    unit_1,unit_2,unit_3,unit_4,unit_5,unit_6,
                    unit_7,unit_8,unit_9,unit_10,unit_11,unit_12,
                    value_1,value_2,value_3,value_4,value_5,value_6,
                    value_7,value_8,value_9,value_10,value_11,value_12, 
                    ot_1,ot_2,ot_3,ot_4,ot_5,ot_6,
                    ot_7,ot_8,ot_9,ot_10,ot_11,ot_12,
                    ec_1,ec_2,ec_3,ec_4,ec_5,ec_6,
                    ec_7,ec_8,ec_9,ec_10,ec_11,ec_12               
            from   db_temp.t_temp_soprod
            where  id = $id and created_date = (select max(created_date) from db_temp.t_temp_soprod where id = $id)
            ORDER BY urutan asc
        ";                            
        $hasil = $this->db->query($query);
        query_to_csv($hasil,TRUE,"Sell Out Product.csv");
    }

    public function export_sales_per_product_kode_kodesalur(){
        $id=$this->session->userdata('id');
        $query="
            select  substr(kode,1,3) as kode_comp, kode, branch_name,nama_comp, groupsalur,
                    unit_1,unit_2,unit_3,unit_4,unit_5,unit_6,
                    unit_7,unit_8,unit_9,unit_10,unit_11,unit_12,
                    value_1,value_2,value_3,value_4,value_5,value_6,
                    value_7,value_8,value_9,value_10,value_11,value_12, 
                    ot_1,ot_2,ot_3,ot_4,ot_5,ot_6,
                    ot_7,ot_8,ot_9,ot_10,ot_11,ot_12,
                    ec_1,ec_2,ec_3,ec_4,ec_5,ec_6,
                    ec_7,ec_8,ec_9,ec_10,ec_11,ec_12               
            from   db_temp.t_temp_soprod
            where  id = $id and created_date = (select max(created_date) from db_temp.t_temp_soprod where id = $id)
            ORDER BY urutan asc
        ";                            
        $hasil = $this->db->query($query);
        query_to_csv($hasil,TRUE,"Sell Out Product.csv");
    }

    public function export_sales_per_product_kode_kode_type(){
        $id=$this->session->userdata('id');
        $query="
            select  substr(kode,1,3) as kode_comp, kode, branch_name,nama_comp, kode_type, sektor,segment,
                    unit_1,unit_2,unit_3,unit_4,unit_5,unit_6,
                    unit_7,unit_8,unit_9,unit_10,unit_11,unit_12,
                    value_1,value_2,value_3,value_4,value_5,value_6,
                    value_7,value_8,value_9,value_10,value_11,value_12, 
                    ot_1,ot_2,ot_3,ot_4,ot_5,ot_6,
                    ot_7,ot_8,ot_9,ot_10,ot_11,ot_12,
                    ec_1,ec_2,ec_3,ec_4,ec_5,ec_6,
                    ec_7,ec_8,ec_9,ec_10,ec_11,ec_12               
            from   db_temp.t_temp_soprod
            where  id = $id and created_date = (select max(created_date) from db_temp.t_temp_soprod where id = $id)
            ORDER BY urutan asc
        ";                            
        $hasil = $this->db->query($query);
        query_to_csv($hasil,TRUE,"Sell Out Product.csv");
    }

    public function export_sales_per_product_kode_salesman()
    {
        $id = $this->session->userdata('id');
        $query = "
            select  substr(kode,1,3) as kode_comp, kode, branch_name, kodesales, namasales,
                    unit_1,unit_2,unit_3,unit_4,unit_5,unit_6,
                    unit_7,unit_8,unit_9,unit_10,unit_11,unit_12,
                    value_1,value_2,value_3,value_4,value_5,value_6,
                    value_7,value_8,value_9,value_10,value_11,value_12, 
                    ot_1,ot_2,ot_3,ot_4,ot_5,ot_6,
                    ot_7,ot_8,ot_9,ot_10,ot_11,ot_12,
                    ec_1,ec_2,ec_3,ec_4,ec_5,ec_6,
                    ec_7,ec_8,ec_9,ec_10,ec_11,ec_12               
            from   db_temp.t_temp_soprod
            where  id = $id and created_date = (select max(created_date) from db_temp.t_temp_soprod where id = $id)
            ORDER BY urutan asc
        ";
        $hasil = $this->db->query($query);
        query_to_csv($hasil, TRUE, "Sell Out Product.csv");
    }

    public function export_sales_per_product_kodeprod_kodesalur(){
        $id=$this->session->userdata('id');
        $query="
            select  kodeprod,namaprod, groupsalur,
                    unit_1,unit_2,unit_3,unit_4,unit_5,unit_6,
                    unit_7,unit_8,unit_9,unit_10,unit_11,unit_12,
                    value_1,value_2,value_3,value_4,value_5,value_6,
                    value_7,value_8,value_9,value_10,value_11,value_12, 
                    ot_1,ot_2,ot_3,ot_4,ot_5,ot_6,
                    ot_7,ot_8,ot_9,ot_10,ot_11,ot_12,
                    ec_1,ec_2,ec_3,ec_4,ec_5,ec_6,
                    ec_7,ec_8,ec_9,ec_10,ec_11,ec_12               
            from   db_temp.t_temp_soprod
            where  id = $id and created_date = (select max(created_date) from db_temp.t_temp_soprod where id = $id)
            ORDER BY urutan asc
        ";                            
        $hasil = $this->db->query($query);
        query_to_csv($hasil,TRUE,"Sell Out Product.csv");
    }

    public function export_sales_per_product_kodeprod_kode_type(){
        $id=$this->session->userdata('id');
        $query="
            select  kodeprod,namaprod, sektor,segment,
                    unit_1,unit_2,unit_3,unit_4,unit_5,unit_6,
                    unit_7,unit_8,unit_9,unit_10,unit_11,unit_12,
                    value_1,value_2,value_3,value_4,value_5,value_6,
                    value_7,value_8,value_9,value_10,value_11,value_12, 
                    ot_1,ot_2,ot_3,ot_4,ot_5,ot_6,
                    ot_7,ot_8,ot_9,ot_10,ot_11,ot_12,
                    ec_1,ec_2,ec_3,ec_4,ec_5,ec_6,
                    ec_7,ec_8,ec_9,ec_10,ec_11,ec_12               
            from   db_temp.t_temp_soprod
            where  id = $id and created_date = (select max(created_date) from db_temp.t_temp_soprod where id = $id)
            ORDER BY urutan asc
        ";                            
        $hasil = $this->db->query($query);
        query_to_csv($hasil,TRUE,"Sell Out Product.csv");
    }

    public function export_sales_per_product_kodesalur_kode_type(){
        $id=$this->session->userdata('id');
        $query="
            select  groupsalur,sektor,segment,
                    unit_1,unit_2,unit_3,unit_4,unit_5,unit_6,
                    unit_7,unit_8,unit_9,unit_10,unit_11,unit_12,
                    value_1,value_2,value_3,value_4,value_5,value_6,
                    value_7,value_8,value_9,value_10,value_11,value_12, 
                    ot_1,ot_2,ot_3,ot_4,ot_5,ot_6,
                    ot_7,ot_8,ot_9,ot_10,ot_11,ot_12,
                    ec_1,ec_2,ec_3,ec_4,ec_5,ec_6,
                    ec_7,ec_8,ec_9,ec_10,ec_11,ec_12               
            from   db_temp.t_temp_soprod
            where  id = $id and created_date = (select max(created_date) from db_temp.t_temp_soprod where id = $id)
            ORDER BY urutan asc
        ";                            
        $hasil = $this->db->query($query);
        query_to_csv($hasil,TRUE,"Sell Out Product.csv");
    }

    public function export_sales_per_product_kode_kodeprod_kodesalur(){
        $id=$this->session->userdata('id');
        $query="
            select  substr(kode,1,3) as kode_comp, kode, branch_name,nama_comp, kodeprod, namaprod,groupsalur,
                    unit_1,unit_2,unit_3,unit_4,unit_5,unit_6,
                    unit_7,unit_8,unit_9,unit_10,unit_11,unit_12,
                    value_1,value_2,value_3,value_4,value_5,value_6,
                    value_7,value_8,value_9,value_10,value_11,value_12, 
                    ot_1,ot_2,ot_3,ot_4,ot_5,ot_6,
                    ot_7,ot_8,ot_9,ot_10,ot_11,ot_12,
                    ec_1,ec_2,ec_3,ec_4,ec_5,ec_6,
                    ec_7,ec_8,ec_9,ec_10,ec_11,ec_12               
            from   db_temp.t_temp_soprod
            where  id = $id and created_date = (select max(created_date) from db_temp.t_temp_soprod where id = $id)
            ORDER BY urutan asc
        ";                            
        $hasil = $this->db->query($query);
        query_to_csv($hasil,TRUE,"Sell Out Product.csv");
    }

    public function export_sales_per_product_kode_kodeprod_kode_type(){
        $id=$this->session->userdata('id');
        $query="
            select  substr(kode,1,3) as kode_comp, kode, branch_name,nama_comp, kodeprod, namaprod,sektor,segment,
                    unit_1,unit_2,unit_3,unit_4,unit_5,unit_6,
                    unit_7,unit_8,unit_9,unit_10,unit_11,unit_12,
                    value_1,value_2,value_3,value_4,value_5,value_6,
                    value_7,value_8,value_9,value_10,value_11,value_12, 
                    ot_1,ot_2,ot_3,ot_4,ot_5,ot_6,
                    ot_7,ot_8,ot_9,ot_10,ot_11,ot_12,
                    ec_1,ec_2,ec_3,ec_4,ec_5,ec_6,
                    ec_7,ec_8,ec_9,ec_10,ec_11,ec_12               
            from   db_temp.t_temp_soprod
            where  id = $id and created_date = (select max(created_date) from db_temp.t_temp_soprod where id = $id)
            ORDER BY urutan asc
        ";                            
        $hasil = $this->db->query($query);
        query_to_csv($hasil,TRUE,"Sell Out Product.csv");
    }

    public function export_sales_per_product_kode_kodesalur_kode_type(){
        $id=$this->session->userdata('id');
        $query="
            select  substr(kode,1,3) as kode_comp, kode, branch_name,nama_comp, groupsalur,sektor,segment,
                    unit_1,unit_2,unit_3,unit_4,unit_5,unit_6,
                    unit_7,unit_8,unit_9,unit_10,unit_11,unit_12,
                    value_1,value_2,value_3,value_4,value_5,value_6,
                    value_7,value_8,value_9,value_10,value_11,value_12, 
                    ot_1,ot_2,ot_3,ot_4,ot_5,ot_6,
                    ot_7,ot_8,ot_9,ot_10,ot_11,ot_12,
                    ec_1,ec_2,ec_3,ec_4,ec_5,ec_6,
                    ec_7,ec_8,ec_9,ec_10,ec_11,ec_12               
            from   db_temp.t_temp_soprod
            where  id = $id and created_date = (select max(created_date) from db_temp.t_temp_soprod where id = $id)
            ORDER BY urutan asc
        ";                            
        $hasil = $this->db->query($query);
        query_to_csv($hasil,TRUE,"Sell Out Product.csv");
    }

    public function export_sales_per_product_kodeprod_kodesalur_kode_type(){
        $id=$this->session->userdata('id');
        $query="
            select  kodeprod, namaprod, groupsalur,sektor,segment,
                    unit_1,unit_2,unit_3,unit_4,unit_5,unit_6,
                    unit_7,unit_8,unit_9,unit_10,unit_11,unit_12,
                    value_1,value_2,value_3,value_4,value_5,value_6,
                    value_7,value_8,value_9,value_10,value_11,value_12, 
                    ot_1,ot_2,ot_3,ot_4,ot_5,ot_6,
                    ot_7,ot_8,ot_9,ot_10,ot_11,ot_12,
                    ec_1,ec_2,ec_3,ec_4,ec_5,ec_6,
                    ec_7,ec_8,ec_9,ec_10,ec_11,ec_12               
            from   db_temp.t_temp_soprod
            where  id = $id and created_date = (select max(created_date) from db_temp.t_temp_soprod where id = $id)
            ORDER BY urutan asc
        ";                            
        $hasil = $this->db->query($query);
        query_to_csv($hasil,TRUE,"Sell Out Product.csv");
    }

    public function export_sales_per_product_kode_kodeprod_kodesalur_kode_type(){
        $id=$this->session->userdata('id');
        $query="
            select  substr(kode,1,3) as kode_comp, kode, branch_name,nama_comp, kodeprod, namaprod, groupsalur,sektor,segment,
                    unit_1,unit_2,unit_3,unit_4,unit_5,unit_6,
                    unit_7,unit_8,unit_9,unit_10,unit_11,unit_12,
                    value_1,value_2,value_3,value_4,value_5,value_6,
                    value_7,value_8,value_9,value_10,value_11,value_12, 
                    ot_1,ot_2,ot_3,ot_4,ot_5,ot_6,
                    ot_7,ot_8,ot_9,ot_10,ot_11,ot_12,
                    ec_1,ec_2,ec_3,ec_4,ec_5,ec_6,
                    ec_7,ec_8,ec_9,ec_10,ec_11,ec_12               
            from   db_temp.t_temp_soprod
            where  id = $id and created_date = (select max(created_date) from db_temp.t_temp_soprod where id = $id)
            ORDER BY urutan asc
        ";                            
        $hasil = $this->db->query($query);
        query_to_csv($hasil,TRUE,"Sell Out Product.csv");
    }

    public function export_sales_per_product_grup(){
        $id=$this->session->userdata('id');
        $query="
            select  `group`,nama_group,
                    unit_1,unit_2,unit_3,unit_4,unit_5,unit_6,
                    unit_7,unit_8,unit_9,unit_10,unit_11,unit_12,
                    value_1,value_2,value_3,value_4,value_5,value_6,
                    value_7,value_8,value_9,value_10,value_11,value_12, 
                    ot_1,ot_2,ot_3,ot_4,ot_5,ot_6,
                    ot_7,ot_8,ot_9,ot_10,ot_11,ot_12,
                    ec_1,ec_2,ec_3,ec_4,ec_5,ec_6,
                    ec_7,ec_8,ec_9,ec_10,ec_11,ec_12               
            from   db_temp.t_temp_soprod
            where  id = $id and created_date = (select max(created_date) from db_temp.t_temp_soprod where id = $id)
            ORDER BY urutan asc
        ";                            
        $hasil = $this->db->query($query);
        query_to_csv($hasil,TRUE,"Sell Out Product.csv");
    }

    public function export_sales_per_product_kode_grup(){
        $id=$this->session->userdata('id');
        $query="
            select  substr(kode,1,3) as kode_comp, kode, branch_name,nama_comp,`group`,nama_group,
                    unit_1,unit_2,unit_3,unit_4,unit_5,unit_6,
                    unit_7,unit_8,unit_9,unit_10,unit_11,unit_12,
                    value_1,value_2,value_3,value_4,value_5,value_6,
                    value_7,value_8,value_9,value_10,value_11,value_12, 
                    ot_1,ot_2,ot_3,ot_4,ot_5,ot_6,
                    ot_7,ot_8,ot_9,ot_10,ot_11,ot_12,
                    ec_1,ec_2,ec_3,ec_4,ec_5,ec_6,
                    ec_7,ec_8,ec_9,ec_10,ec_11,ec_12               
            from   db_temp.t_temp_soprod
            where  id = $id and created_date = (select max(created_date) from db_temp.t_temp_soprod where id = $id)
            ORDER BY urutan asc
        ";                            
        $hasil = $this->db->query($query);
        query_to_csv($hasil,TRUE,"Sell Out Product.csv");
    }

    public function export_sales_per_product_kodeprod_grup(){
        $id=$this->session->userdata('id');
        $query="
            select  kodeprod,namaprod,`group`,nama_group,
                    unit_1,unit_2,unit_3,unit_4,unit_5,unit_6,
                    unit_7,unit_8,unit_9,unit_10,unit_11,unit_12,
                    value_1,value_2,value_3,value_4,value_5,value_6,
                    value_7,value_8,value_9,value_10,value_11,value_12, 
                    ot_1,ot_2,ot_3,ot_4,ot_5,ot_6,
                    ot_7,ot_8,ot_9,ot_10,ot_11,ot_12,
                    ec_1,ec_2,ec_3,ec_4,ec_5,ec_6,
                    ec_7,ec_8,ec_9,ec_10,ec_11,ec_12               
            from   db_temp.t_temp_soprod
            where  id = $id and created_date = (select max(created_date) from db_temp.t_temp_soprod where id = $id)
            ORDER BY urutan asc
        ";                            
        $hasil = $this->db->query($query);
        query_to_csv($hasil,TRUE,"Sell Out Product.csv");
    }

    public function export_sales_per_product_kodesalur_grup(){
        $id=$this->session->userdata('id');
        $query="
            select  groupsalur,`group`,nama_group,
                    unit_1,unit_2,unit_3,unit_4,unit_5,unit_6,
                    unit_7,unit_8,unit_9,unit_10,unit_11,unit_12,
                    value_1,value_2,value_3,value_4,value_5,value_6,
                    value_7,value_8,value_9,value_10,value_11,value_12, 
                    ot_1,ot_2,ot_3,ot_4,ot_5,ot_6,
                    ot_7,ot_8,ot_9,ot_10,ot_11,ot_12,
                    ec_1,ec_2,ec_3,ec_4,ec_5,ec_6,
                    ec_7,ec_8,ec_9,ec_10,ec_11,ec_12               
            from   db_temp.t_temp_soprod
            where  id = $id and created_date = (select max(created_date) from db_temp.t_temp_soprod where id = $id)
            ORDER BY urutan asc
        ";                            
        $hasil = $this->db->query($query);
        query_to_csv($hasil,TRUE,"Sell Out Product.csv");
    }

    public function export_sales_per_product_kode_type_grup(){
        $id=$this->session->userdata('id');
        $query="
            select  sektor,segment,`group`,nama_group,
                    unit_1,unit_2,unit_3,unit_4,unit_5,unit_6,
                    unit_7,unit_8,unit_9,unit_10,unit_11,unit_12,
                    value_1,value_2,value_3,value_4,value_5,value_6,
                    value_7,value_8,value_9,value_10,value_11,value_12, 
                    ot_1,ot_2,ot_3,ot_4,ot_5,ot_6,
                    ot_7,ot_8,ot_9,ot_10,ot_11,ot_12,
                    ec_1,ec_2,ec_3,ec_4,ec_5,ec_6,
                    ec_7,ec_8,ec_9,ec_10,ec_11,ec_12               
            from   db_temp.t_temp_soprod
            where  id = $id and created_date = (select max(created_date) from db_temp.t_temp_soprod where id = $id)
            ORDER BY urutan asc
        ";                            
        $hasil = $this->db->query($query);
        query_to_csv($hasil,TRUE,"Sell Out Product.csv");
    }

    public function export_sales_per_product_kode_kodeprod_grup(){
        $id=$this->session->userdata('id');
        $query="
            select  substr(kode,1,3) as kode_comp, kode, branch_name,nama_comp, kodeprod,namaprod,`group`, nama_group,
                    unit_1,unit_2,unit_3,unit_4,unit_5,unit_6,
                    unit_7,unit_8,unit_9,unit_10,unit_11,unit_12,
                    value_1,value_2,value_3,value_4,value_5,value_6,
                    value_7,value_8,value_9,value_10,value_11,value_12, 
                    ot_1,ot_2,ot_3,ot_4,ot_5,ot_6,
                    ot_7,ot_8,ot_9,ot_10,ot_11,ot_12,
                    ec_1,ec_2,ec_3,ec_4,ec_5,ec_6,
                    ec_7,ec_8,ec_9,ec_10,ec_11,ec_12               
            from   db_temp.t_temp_soprod
            where  id = $id and created_date = (select max(created_date) from db_temp.t_temp_soprod where id = $id)
            ORDER BY urutan asc
        ";                            
        $hasil = $this->db->query($query);
        query_to_csv($hasil,TRUE,"Sell Out Product.csv");
    }

    public function export_sales_per_product_kode_kodesalur_grup(){
        $id=$this->session->userdata('id');
        $query="
            select  substr(kode,1,3) as kode_comp, kode, branch_name,nama_comp, groupsalur,`group`, nama_group,
                    unit_1,unit_2,unit_3,unit_4,unit_5,unit_6,
                    unit_7,unit_8,unit_9,unit_10,unit_11,unit_12,
                    value_1,value_2,value_3,value_4,value_5,value_6,
                    value_7,value_8,value_9,value_10,value_11,value_12, 
                    ot_1,ot_2,ot_3,ot_4,ot_5,ot_6,
                    ot_7,ot_8,ot_9,ot_10,ot_11,ot_12,
                    ec_1,ec_2,ec_3,ec_4,ec_5,ec_6,
                    ec_7,ec_8,ec_9,ec_10,ec_11,ec_12               
            from   db_temp.t_temp_soprod
            where  id = $id and created_date = (select max(created_date) from db_temp.t_temp_soprod where id = $id)
            ORDER BY urutan asc
        ";                            
        $hasil = $this->db->query($query);
        query_to_csv($hasil,TRUE,"Sell Out Product.csv");
    }

    public function export_sales_per_product_kode_kode_type_grup(){
        $id=$this->session->userdata('id');
        $query="
            select  substr(kode,1,3) as kode_comp, kode, branch_name,nama_comp, sektor,segment,`group`, nama_group,
                    unit_1,unit_2,unit_3,unit_4,unit_5,unit_6,
                    unit_7,unit_8,unit_9,unit_10,unit_11,unit_12,
                    value_1,value_2,value_3,value_4,value_5,value_6,
                    value_7,value_8,value_9,value_10,value_11,value_12, 
                    ot_1,ot_2,ot_3,ot_4,ot_5,ot_6,
                    ot_7,ot_8,ot_9,ot_10,ot_11,ot_12,
                    ec_1,ec_2,ec_3,ec_4,ec_5,ec_6,
                    ec_7,ec_8,ec_9,ec_10,ec_11,ec_12               
            from   db_temp.t_temp_soprod
            where  id = $id and created_date = (select max(created_date) from db_temp.t_temp_soprod where id = $id)
            ORDER BY urutan asc
        ";                            
        $hasil = $this->db->query($query);
        query_to_csv($hasil,TRUE,"Sell Out Product.csv");
    }

    public function export_sales_per_product_kodeprod_kodesalur_grup(){
        $id=$this->session->userdata('id');
        $query="
            select  kodeprod,namaprod, groupsalur,`group`, nama_group,
                    unit_1,unit_2,unit_3,unit_4,unit_5,unit_6,
                    unit_7,unit_8,unit_9,unit_10,unit_11,unit_12,
                    value_1,value_2,value_3,value_4,value_5,value_6,
                    value_7,value_8,value_9,value_10,value_11,value_12, 
                    ot_1,ot_2,ot_3,ot_4,ot_5,ot_6,
                    ot_7,ot_8,ot_9,ot_10,ot_11,ot_12,
                    ec_1,ec_2,ec_3,ec_4,ec_5,ec_6,
                    ec_7,ec_8,ec_9,ec_10,ec_11,ec_12               
            from   db_temp.t_temp_soprod
            where  id = $id and created_date = (select max(created_date) from db_temp.t_temp_soprod where id = $id)
            ORDER BY urutan asc
        ";                            
        $hasil = $this->db->query($query);
        query_to_csv($hasil,TRUE,"Sell Out Product.csv");
    }

    public function export_sales_per_product_kodeprod_kode_type_grup(){
        $id=$this->session->userdata('id');
        $query="
            select  kodeprod,namaprod, sektor,segment,`group`, nama_group,
                    unit_1,unit_2,unit_3,unit_4,unit_5,unit_6,
                    unit_7,unit_8,unit_9,unit_10,unit_11,unit_12,
                    value_1,value_2,value_3,value_4,value_5,value_6,
                    value_7,value_8,value_9,value_10,value_11,value_12, 
                    ot_1,ot_2,ot_3,ot_4,ot_5,ot_6,
                    ot_7,ot_8,ot_9,ot_10,ot_11,ot_12,
                    ec_1,ec_2,ec_3,ec_4,ec_5,ec_6,
                    ec_7,ec_8,ec_9,ec_10,ec_11,ec_12               
            from   db_temp.t_temp_soprod
            where  id = $id and created_date = (select max(created_date) from db_temp.t_temp_soprod where id = $id)
            ORDER BY urutan asc
        ";                            
        $hasil = $this->db->query($query);
        query_to_csv($hasil,TRUE,"Sell Out Product.csv");
    }

    public function export_sales_per_product_kodesalur_kode_type_grup(){
        $id=$this->session->userdata('id');
        $query="
            select  groupsalur, sektor,segment,`group`, nama_group,
                    unit_1,unit_2,unit_3,unit_4,unit_5,unit_6,
                    unit_7,unit_8,unit_9,unit_10,unit_11,unit_12,
                    value_1,value_2,value_3,value_4,value_5,value_6,
                    value_7,value_8,value_9,value_10,value_11,value_12, 
                    ot_1,ot_2,ot_3,ot_4,ot_5,ot_6,
                    ot_7,ot_8,ot_9,ot_10,ot_11,ot_12,
                    ec_1,ec_2,ec_3,ec_4,ec_5,ec_6,
                    ec_7,ec_8,ec_9,ec_10,ec_11,ec_12               
            from   db_temp.t_temp_soprod
            where  id = $id and created_date = (select max(created_date) from db_temp.t_temp_soprod where id = $id)
            ORDER BY urutan asc
        ";                            
        $hasil = $this->db->query($query);
        query_to_csv($hasil,TRUE,"Sell Out Product.csv");
    }

    public function export_sales_per_product_kode_kodeprod_kodesalur_grup(){
        $id=$this->session->userdata('id');
        $query="
            select  substr(kode,1,3) as kode_comp, kode, branch_name,nama_comp, kodeprod,namaprod,groupsalur,`group`, nama_group,
                    unit_1,unit_2,unit_3,unit_4,unit_5,unit_6,
                    unit_7,unit_8,unit_9,unit_10,unit_11,unit_12,
                    value_1,value_2,value_3,value_4,value_5,value_6,
                    value_7,value_8,value_9,value_10,value_11,value_12, 
                    ot_1,ot_2,ot_3,ot_4,ot_5,ot_6,
                    ot_7,ot_8,ot_9,ot_10,ot_11,ot_12,
                    ec_1,ec_2,ec_3,ec_4,ec_5,ec_6,
                    ec_7,ec_8,ec_9,ec_10,ec_11,ec_12               
            from   db_temp.t_temp_soprod
            where  id = $id and created_date = (select max(created_date) from db_temp.t_temp_soprod where id = $id)
            ORDER BY urutan asc
        ";                            
        $hasil = $this->db->query($query);
        query_to_csv($hasil,TRUE,"Sell Out Product.csv");
    }

    public function export_sales_per_product_kode_kodeprod_kode_type_grup(){
        $id=$this->session->userdata('id');
        $query="
            select  substr(kode,1,3) as kode_comp, kode, branch_name,nama_comp, kodeprod,namaprod,sektor,segment,`group`, nama_group,
                    unit_1,unit_2,unit_3,unit_4,unit_5,unit_6,
                    unit_7,unit_8,unit_9,unit_10,unit_11,unit_12,
                    value_1,value_2,value_3,value_4,value_5,value_6,
                    value_7,value_8,value_9,value_10,value_11,value_12, 
                    ot_1,ot_2,ot_3,ot_4,ot_5,ot_6,
                    ot_7,ot_8,ot_9,ot_10,ot_11,ot_12,
                    ec_1,ec_2,ec_3,ec_4,ec_5,ec_6,
                    ec_7,ec_8,ec_9,ec_10,ec_11,ec_12               
            from   db_temp.t_temp_soprod
            where  id = $id and created_date = (select max(created_date) from db_temp.t_temp_soprod where id = $id)
            ORDER BY urutan asc
        ";                            
        $hasil = $this->db->query($query);
        query_to_csv($hasil,TRUE,"Sell Out Product.csv");
    }
    
    public function export_sales_per_product_kodeprod_kodesalur_kode_type_grup(){
        $id=$this->session->userdata('id');
        $query="
            select  kodeprod,namaprod,groupsalur,sektor,segment,`group`, nama_group,
                    unit_1,unit_2,unit_3,unit_4,unit_5,unit_6,
                    unit_7,unit_8,unit_9,unit_10,unit_11,unit_12,
                    value_1,value_2,value_3,value_4,value_5,value_6,
                    value_7,value_8,value_9,value_10,value_11,value_12, 
                    ot_1,ot_2,ot_3,ot_4,ot_5,ot_6,
                    ot_7,ot_8,ot_9,ot_10,ot_11,ot_12,
                    ec_1,ec_2,ec_3,ec_4,ec_5,ec_6,
                    ec_7,ec_8,ec_9,ec_10,ec_11,ec_12               
            from   db_temp.t_temp_soprod
            where  id = $id and created_date = (select max(created_date) from db_temp.t_temp_soprod where id = $id)
            ORDER BY urutan asc
        ";                            
        $hasil = $this->db->query($query);
        query_to_csv($hasil,TRUE,"Sell Out Product.csv");
    }

    public function export_sales_per_product_kode_kodeprod_kodesalur_kode_type_grup(){
        $id=$this->session->userdata('id');
        $query="
            select  substr(kode,1,3) as kode_comp, kode, branch_name,nama_comp, kodeprod,namaprod,groupsalur,sektor,segment,`group`, nama_group,
                    unit_1,unit_2,unit_3,unit_4,unit_5,unit_6,
                    unit_7,unit_8,unit_9,unit_10,unit_11,unit_12,
                    value_1,value_2,value_3,value_4,value_5,value_6,
                    value_7,value_8,value_9,value_10,value_11,value_12, 
                    ot_1,ot_2,ot_3,ot_4,ot_5,ot_6,
                    ot_7,ot_8,ot_9,ot_10,ot_11,ot_12,
                    ec_1,ec_2,ec_3,ec_4,ec_5,ec_6,
                    ec_7,ec_8,ec_9,ec_10,ec_11,ec_12               
            from   db_temp.t_temp_soprod
            where  id = $id and created_date = (select max(created_date) from db_temp.t_temp_soprod where id = $id)
            ORDER BY urutan asc
        ";                            
        $hasil = $this->db->query($query);
        query_to_csv($hasil,TRUE,"Sell Out Product.csv");
    }

    public function export_sales_per_product_subgrup(){
        $id=$this->session->userdata('id');
        $query="
            select  subgroup, nama_sub_group,
                    unit_1,unit_2,unit_3,unit_4,unit_5,unit_6,
                    unit_7,unit_8,unit_9,unit_10,unit_11,unit_12,
                    value_1,value_2,value_3,value_4,value_5,value_6,
                    value_7,value_8,value_9,value_10,value_11,value_12, 
                    ot_1,ot_2,ot_3,ot_4,ot_5,ot_6,
                    ot_7,ot_8,ot_9,ot_10,ot_11,ot_12,
                    ec_1,ec_2,ec_3,ec_4,ec_5,ec_6,
                    ec_7,ec_8,ec_9,ec_10,ec_11,ec_12               
            from   db_temp.t_temp_soprod
            where  id = $id and created_date = (select max(created_date) from db_temp.t_temp_soprod where id = $id)
            ORDER BY urutan asc
        ";                            
        $hasil = $this->db->query($query);
        query_to_csv($hasil,TRUE,"Sell Out Product.csv");
    }

    public function export_sales_per_product_kode_subgrup(){
        $id=$this->session->userdata('id');
        $query="
            select  substr(kode,1,3) as kode_comp, kode, branch_name,nama_comp, subgroup, nama_sub_group,
                    unit_1,unit_2,unit_3,unit_4,unit_5,unit_6,
                    unit_7,unit_8,unit_9,unit_10,unit_11,unit_12,
                    value_1,value_2,value_3,value_4,value_5,value_6,
                    value_7,value_8,value_9,value_10,value_11,value_12, 
                    ot_1,ot_2,ot_3,ot_4,ot_5,ot_6,
                    ot_7,ot_8,ot_9,ot_10,ot_11,ot_12,
                    ec_1,ec_2,ec_3,ec_4,ec_5,ec_6,
                    ec_7,ec_8,ec_9,ec_10,ec_11,ec_12               
            from   db_temp.t_temp_soprod
            where  id = $id and created_date = (select max(created_date) from db_temp.t_temp_soprod where id = $id)
            ORDER BY urutan asc
        ";                            
        $hasil = $this->db->query($query);
        query_to_csv($hasil,TRUE,"Sell Out Product.csv");
    }

    public function export_sales_per_product_kodeprod_subgrup(){
        $id=$this->session->userdata('id');
        $query="
            select  kodeprod,namaprod, subgroup, nama_sub_group,
                    unit_1,unit_2,unit_3,unit_4,unit_5,unit_6,
                    unit_7,unit_8,unit_9,unit_10,unit_11,unit_12,
                    value_1,value_2,value_3,value_4,value_5,value_6,
                    value_7,value_8,value_9,value_10,value_11,value_12, 
                    ot_1,ot_2,ot_3,ot_4,ot_5,ot_6,
                    ot_7,ot_8,ot_9,ot_10,ot_11,ot_12,
                    ec_1,ec_2,ec_3,ec_4,ec_5,ec_6,
                    ec_7,ec_8,ec_9,ec_10,ec_11,ec_12               
            from   db_temp.t_temp_soprod
            where  id = $id and created_date = (select max(created_date) from db_temp.t_temp_soprod where id = $id)
            ORDER BY urutan asc
        ";                            
        $hasil = $this->db->query($query);
        query_to_csv($hasil,TRUE,"Sell Out Product.csv");
    }

    public function export_sales_per_product_kodesalur_subgrup(){
        $id=$this->session->userdata('id');
        $query="
            select  groupsalur, subgroup, nama_sub_group,
                    unit_1,unit_2,unit_3,unit_4,unit_5,unit_6,
                    unit_7,unit_8,unit_9,unit_10,unit_11,unit_12,
                    value_1,value_2,value_3,value_4,value_5,value_6,
                    value_7,value_8,value_9,value_10,value_11,value_12, 
                    ot_1,ot_2,ot_3,ot_4,ot_5,ot_6,
                    ot_7,ot_8,ot_9,ot_10,ot_11,ot_12,
                    ec_1,ec_2,ec_3,ec_4,ec_5,ec_6,
                    ec_7,ec_8,ec_9,ec_10,ec_11,ec_12               
            from   db_temp.t_temp_soprod
            where  id = $id and created_date = (select max(created_date) from db_temp.t_temp_soprod where id = $id)
            ORDER BY urutan asc
        ";                            
        $hasil = $this->db->query($query);
        query_to_csv($hasil,TRUE,"Sell Out Product.csv");
    }

    public function export_sales_per_product_kode_type_subgrup(){
        $id=$this->session->userdata('id');
        $query="
            select  sektor,segment, subgroup, nama_sub_group,
                    unit_1,unit_2,unit_3,unit_4,unit_5,unit_6,
                    unit_7,unit_8,unit_9,unit_10,unit_11,unit_12,
                    value_1,value_2,value_3,value_4,value_5,value_6,
                    value_7,value_8,value_9,value_10,value_11,value_12, 
                    ot_1,ot_2,ot_3,ot_4,ot_5,ot_6,
                    ot_7,ot_8,ot_9,ot_10,ot_11,ot_12,
                    ec_1,ec_2,ec_3,ec_4,ec_5,ec_6,
                    ec_7,ec_8,ec_9,ec_10,ec_11,ec_12               
            from   db_temp.t_temp_soprod
            where  id = $id and created_date = (select max(created_date) from db_temp.t_temp_soprod where id = $id)
            ORDER BY urutan asc
        ";                            
        $hasil = $this->db->query($query);
        query_to_csv($hasil,TRUE,"Sell Out Product.csv");
    }

    public function export_sales_per_product_grup_subgrup(){
        $id=$this->session->userdata('id');
        $query="
            select  `group`, nama_group, subgroup, nama_sub_group,
                    unit_1,unit_2,unit_3,unit_4,unit_5,unit_6,
                    unit_7,unit_8,unit_9,unit_10,unit_11,unit_12,
                    value_1,value_2,value_3,value_4,value_5,value_6,
                    value_7,value_8,value_9,value_10,value_11,value_12, 
                    ot_1,ot_2,ot_3,ot_4,ot_5,ot_6,
                    ot_7,ot_8,ot_9,ot_10,ot_11,ot_12,
                    ec_1,ec_2,ec_3,ec_4,ec_5,ec_6,
                    ec_7,ec_8,ec_9,ec_10,ec_11,ec_12               
            from   db_temp.t_temp_soprod
            where  id = $id and created_date = (select max(created_date) from db_temp.t_temp_soprod where id = $id)
            ORDER BY urutan asc
        ";                            
        $hasil = $this->db->query($query);
        query_to_csv($hasil,TRUE,"Sell Out Product.csv");
    }

    public function export_sales_per_product_kode_kodeprod_subgrup(){
        $id=$this->session->userdata('id');
        $query="
            select  substr(kode,1,3) as kode_comp, kode, branch_name,nama_comp, kodeprod, namaprod, subgroup, nama_sub_group,
                    unit_1,unit_2,unit_3,unit_4,unit_5,unit_6,
                    unit_7,unit_8,unit_9,unit_10,unit_11,unit_12,
                    value_1,value_2,value_3,value_4,value_5,value_6,
                    value_7,value_8,value_9,value_10,value_11,value_12, 
                    ot_1,ot_2,ot_3,ot_4,ot_5,ot_6,
                    ot_7,ot_8,ot_9,ot_10,ot_11,ot_12,
                    ec_1,ec_2,ec_3,ec_4,ec_5,ec_6,
                    ec_7,ec_8,ec_9,ec_10,ec_11,ec_12               
            from   db_temp.t_temp_soprod
            where  id = $id and created_date = (select max(created_date) from db_temp.t_temp_soprod where id = $id)
            ORDER BY urutan asc
        ";                            
        $hasil = $this->db->query($query);
        query_to_csv($hasil,TRUE,"Sell Out Product.csv");
    }

    public function export_sales_per_product_kode_kodesalur_subgrup(){
        $id=$this->session->userdata('id');
        $query="
            select  substr(kode,1,3) as kode_comp, kode, branch_name,nama_comp,groupsalur, subgroup, nama_sub_group,
                    unit_1,unit_2,unit_3,unit_4,unit_5,unit_6,
                    unit_7,unit_8,unit_9,unit_10,unit_11,unit_12,
                    value_1,value_2,value_3,value_4,value_5,value_6,
                    value_7,value_8,value_9,value_10,value_11,value_12, 
                    ot_1,ot_2,ot_3,ot_4,ot_5,ot_6,
                    ot_7,ot_8,ot_9,ot_10,ot_11,ot_12,
                    ec_1,ec_2,ec_3,ec_4,ec_5,ec_6,
                    ec_7,ec_8,ec_9,ec_10,ec_11,ec_12               
            from   db_temp.t_temp_soprod
            where  id = $id and created_date = (select max(created_date) from db_temp.t_temp_soprod where id = $id)
            ORDER BY urutan asc
        ";                            
        $hasil = $this->db->query($query);
        query_to_csv($hasil,TRUE,"Sell Out Product.csv");
    }

    public function export_sales_per_product_kode_kode_type_subgrup(){
        $id=$this->session->userdata('id');
        $query="
            select  substr(kode,1,3) as kode_comp, kode, branch_name,nama_comp, sektor, segment, subgroup, nama_sub_group,
                    unit_1,unit_2,unit_3,unit_4,unit_5,unit_6,
                    unit_7,unit_8,unit_9,unit_10,unit_11,unit_12,
                    value_1,value_2,value_3,value_4,value_5,value_6,
                    value_7,value_8,value_9,value_10,value_11,value_12, 
                    ot_1,ot_2,ot_3,ot_4,ot_5,ot_6,
                    ot_7,ot_8,ot_9,ot_10,ot_11,ot_12,
                    ec_1,ec_2,ec_3,ec_4,ec_5,ec_6,
                    ec_7,ec_8,ec_9,ec_10,ec_11,ec_12               
            from   db_temp.t_temp_soprod
            where  id = $id and created_date = (select max(created_date) from db_temp.t_temp_soprod where id = $id)
            ORDER BY urutan asc
        ";                            
        $hasil = $this->db->query($query);
        query_to_csv($hasil,TRUE,"Sell Out Product.csv");
    }

    public function export_sales_per_product_kode_grup_subgrup(){
        $id=$this->session->userdata('id');
        $query="
            select  substr(kode,1,3) as kode_comp, kode, branch_name,nama_comp,`group`, nama_group, subgroup, nama_sub_group,
                    unit_1,unit_2,unit_3,unit_4,unit_5,unit_6,
                    unit_7,unit_8,unit_9,unit_10,unit_11,unit_12,
                    value_1,value_2,value_3,value_4,value_5,value_6,
                    value_7,value_8,value_9,value_10,value_11,value_12, 
                    ot_1,ot_2,ot_3,ot_4,ot_5,ot_6,
                    ot_7,ot_8,ot_9,ot_10,ot_11,ot_12,
                    ec_1,ec_2,ec_3,ec_4,ec_5,ec_6,
                    ec_7,ec_8,ec_9,ec_10,ec_11,ec_12               
            from   db_temp.t_temp_soprod
            where  id = $id and created_date = (select max(created_date) from db_temp.t_temp_soprod where id = $id)
            ORDER BY urutan asc
        ";                            
        $hasil = $this->db->query($query);
        query_to_csv($hasil,TRUE,"Sell Out Product.csv");
    }

    public function export_sales_per_product_kodeprod_kodesalur_subgrup(){
        $id=$this->session->userdata('id');
        $query="
            select  kodeprod,namaprod, groupsalur, subgroup, nama_sub_group,
                    unit_1,unit_2,unit_3,unit_4,unit_5,unit_6,
                    unit_7,unit_8,unit_9,unit_10,unit_11,unit_12,
                    value_1,value_2,value_3,value_4,value_5,value_6,
                    value_7,value_8,value_9,value_10,value_11,value_12, 
                    ot_1,ot_2,ot_3,ot_4,ot_5,ot_6,
                    ot_7,ot_8,ot_9,ot_10,ot_11,ot_12,
                    ec_1,ec_2,ec_3,ec_4,ec_5,ec_6,
                    ec_7,ec_8,ec_9,ec_10,ec_11,ec_12               
            from   db_temp.t_temp_soprod
            where  id = $id and created_date = (select max(created_date) from db_temp.t_temp_soprod where id = $id)
            ORDER BY urutan asc
        ";                            
        $hasil = $this->db->query($query);
        query_to_csv($hasil,TRUE,"Sell Out Product.csv");
    }

    public function export_sales_per_product_kodeprod_kode_type_subgrup(){
        $id=$this->session->userdata('id');
        $query="
            select  kodeprod,namaprod, sektor, segment, subgroup, nama_sub_group,
                    unit_1,unit_2,unit_3,unit_4,unit_5,unit_6,
                    unit_7,unit_8,unit_9,unit_10,unit_11,unit_12,
                    value_1,value_2,value_3,value_4,value_5,value_6,
                    value_7,value_8,value_9,value_10,value_11,value_12, 
                    ot_1,ot_2,ot_3,ot_4,ot_5,ot_6,
                    ot_7,ot_8,ot_9,ot_10,ot_11,ot_12,
                    ec_1,ec_2,ec_3,ec_4,ec_5,ec_6,
                    ec_7,ec_8,ec_9,ec_10,ec_11,ec_12               
            from   db_temp.t_temp_soprod
            where  id = $id and created_date = (select max(created_date) from db_temp.t_temp_soprod where id = $id)
            ORDER BY urutan asc
        ";                            
        $hasil = $this->db->query($query);
        query_to_csv($hasil,TRUE,"Sell Out Product.csv");
    }

    public function export_sales_per_product_kodeprod_grup_subgrup(){
        $id=$this->session->userdata('id');
        $query="
            select  kodeprod,namaprod,`group`, nama_group, subgroup, nama_sub_group,
                    unit_1,unit_2,unit_3,unit_4,unit_5,unit_6,
                    unit_7,unit_8,unit_9,unit_10,unit_11,unit_12,
                    value_1,value_2,value_3,value_4,value_5,value_6,
                    value_7,value_8,value_9,value_10,value_11,value_12, 
                    ot_1,ot_2,ot_3,ot_4,ot_5,ot_6,
                    ot_7,ot_8,ot_9,ot_10,ot_11,ot_12,
                    ec_1,ec_2,ec_3,ec_4,ec_5,ec_6,
                    ec_7,ec_8,ec_9,ec_10,ec_11,ec_12               
            from   db_temp.t_temp_soprod
            where  id = $id and created_date = (select max(created_date) from db_temp.t_temp_soprod where id = $id)
            ORDER BY urutan asc
        ";                            
        $hasil = $this->db->query($query);
        query_to_csv($hasil,TRUE,"Sell Out Product.csv");
    }

    public function export_sales_per_product_kodesalur_kode_type_subgrup(){
        $id=$this->session->userdata('id');
        $query="
            select  groupsalur, sektor, segment,subgroup, nama_sub_group,
                    unit_1,unit_2,unit_3,unit_4,unit_5,unit_6,
                    unit_7,unit_8,unit_9,unit_10,unit_11,unit_12,
                    value_1,value_2,value_3,value_4,value_5,value_6,
                    value_7,value_8,value_9,value_10,value_11,value_12, 
                    ot_1,ot_2,ot_3,ot_4,ot_5,ot_6,
                    ot_7,ot_8,ot_9,ot_10,ot_11,ot_12,
                    ec_1,ec_2,ec_3,ec_4,ec_5,ec_6,
                    ec_7,ec_8,ec_9,ec_10,ec_11,ec_12               
            from   db_temp.t_temp_soprod
            where  id = $id and created_date = (select max(created_date) from db_temp.t_temp_soprod where id = $id)
            ORDER BY urutan asc
        ";                            
        $hasil = $this->db->query($query);
        query_to_csv($hasil,TRUE,"Sell Out Product.csv");
    }

    public function export_sales_per_product_kodesalur_grup_subgrup(){
        $id=$this->session->userdata('id');
        $query="
            select  groupsalur,`group`,nama_group, subgroup, nama_sub_group,
                    unit_1,unit_2,unit_3,unit_4,unit_5,unit_6,
                    unit_7,unit_8,unit_9,unit_10,unit_11,unit_12,
                    value_1,value_2,value_3,value_4,value_5,value_6,
                    value_7,value_8,value_9,value_10,value_11,value_12, 
                    ot_1,ot_2,ot_3,ot_4,ot_5,ot_6,
                    ot_7,ot_8,ot_9,ot_10,ot_11,ot_12,
                    ec_1,ec_2,ec_3,ec_4,ec_5,ec_6,
                    ec_7,ec_8,ec_9,ec_10,ec_11,ec_12               
            from   db_temp.t_temp_soprod
            where  id = $id and created_date = (select max(created_date) from db_temp.t_temp_soprod where id = $id)
            ORDER BY urutan asc
        ";                            
        $hasil = $this->db->query($query);
        query_to_csv($hasil,TRUE,"Sell Out Product.csv");
    }

    public function export_sales_per_product_kode_type_grup_subgrup(){
        $id=$this->session->userdata('id');
        $query="
            select  sektor, segment, `group`,nama_group, subgroup, nama_sub_group,
                    unit_1,unit_2,unit_3,unit_4,unit_5,unit_6,
                    unit_7,unit_8,unit_9,unit_10,unit_11,unit_12,
                    value_1,value_2,value_3,value_4,value_5,value_6,
                    value_7,value_8,value_9,value_10,value_11,value_12, 
                    ot_1,ot_2,ot_3,ot_4,ot_5,ot_6,
                    ot_7,ot_8,ot_9,ot_10,ot_11,ot_12,
                    ec_1,ec_2,ec_3,ec_4,ec_5,ec_6,
                    ec_7,ec_8,ec_9,ec_10,ec_11,ec_12               
            from   db_temp.t_temp_soprod
            where  id = $id and created_date = (select max(created_date) from db_temp.t_temp_soprod where id = $id)
            ORDER BY urutan asc
        ";                            
        $hasil = $this->db->query($query);
        query_to_csv($hasil,TRUE,"Sell Out Product.csv");
    }

    public function export_sales_per_product_kode_kodeprod_kodesalur_subgrup(){
        $id=$this->session->userdata('id');
        $query="
            select  substr(kode,1,3) as kode_comp, kode, branch_name,nama_comp, kodeprod,namaprod,groupsalur, subgroup, nama_sub_group,
                    unit_1,unit_2,unit_3,unit_4,unit_5,unit_6,
                    unit_7,unit_8,unit_9,unit_10,unit_11,unit_12,
                    value_1,value_2,value_3,value_4,value_5,value_6,
                    value_7,value_8,value_9,value_10,value_11,value_12, 
                    ot_1,ot_2,ot_3,ot_4,ot_5,ot_6,
                    ot_7,ot_8,ot_9,ot_10,ot_11,ot_12,
                    ec_1,ec_2,ec_3,ec_4,ec_5,ec_6,
                    ec_7,ec_8,ec_9,ec_10,ec_11,ec_12               
            from   db_temp.t_temp_soprod
            where  id = $id and created_date = (select max(created_date) from db_temp.t_temp_soprod where id = $id)
            ORDER BY urutan asc
        ";                            
        $hasil = $this->db->query($query);
        query_to_csv($hasil,TRUE,"Sell Out Product.csv");
    }
    
    public function export_sales_per_product_kode_kodeprod_kode_type_subgrup(){
        $id=$this->session->userdata('id');
        $query="
            select  substr(kode,1,3) as kode_comp, kode, branch_name,nama_comp, kodeprod,namaprod,sektor, segment, subgroup, nama_sub_group,
                    unit_1,unit_2,unit_3,unit_4,unit_5,unit_6,
                    unit_7,unit_8,unit_9,unit_10,unit_11,unit_12,
                    value_1,value_2,value_3,value_4,value_5,value_6,
                    value_7,value_8,value_9,value_10,value_11,value_12, 
                    ot_1,ot_2,ot_3,ot_4,ot_5,ot_6,
                    ot_7,ot_8,ot_9,ot_10,ot_11,ot_12,
                    ec_1,ec_2,ec_3,ec_4,ec_5,ec_6,
                    ec_7,ec_8,ec_9,ec_10,ec_11,ec_12               
            from   db_temp.t_temp_soprod
            where  id = $id and created_date = (select max(created_date) from db_temp.t_temp_soprod where id = $id)
            ORDER BY urutan asc
        ";                            
        $hasil = $this->db->query($query);
        query_to_csv($hasil,TRUE,"Sell Out Product.csv");
    }

    public function export_sales_per_product_kode_kodeprod_grup_subgrup(){
        $id=$this->session->userdata('id');
        $query="
            select  substr(kode,1,3) as kode_comp, kode, branch_name,nama_comp, kodeprod,namaprod,`group`,nama_sub_group, subgroup, nama_sub_group,
                    unit_1,unit_2,unit_3,unit_4,unit_5,unit_6,
                    unit_7,unit_8,unit_9,unit_10,unit_11,unit_12,
                    value_1,value_2,value_3,value_4,value_5,value_6,
                    value_7,value_8,value_9,value_10,value_11,value_12, 
                    ot_1,ot_2,ot_3,ot_4,ot_5,ot_6,
                    ot_7,ot_8,ot_9,ot_10,ot_11,ot_12,
                    ec_1,ec_2,ec_3,ec_4,ec_5,ec_6,
                    ec_7,ec_8,ec_9,ec_10,ec_11,ec_12               
            from   db_temp.t_temp_soprod
            where  id = $id and created_date = (select max(created_date) from db_temp.t_temp_soprod where id = $id)
            ORDER BY urutan asc
        ";                            
        $hasil = $this->db->query($query);
        query_to_csv($hasil,TRUE,"Sell Out Product.csv");
    }

    public function export_sales_per_product_kode_kodesalur_kode_type_subgrup(){
        $id=$this->session->userdata('id');
        $query="
            select  substr(kode,1,3) as kode_comp, kode, branch_name,nama_comp, groupsalur, sektor, segment, subgroup, nama_sub_group,
                    unit_1,unit_2,unit_3,unit_4,unit_5,unit_6,
                    unit_7,unit_8,unit_9,unit_10,unit_11,unit_12,
                    value_1,value_2,value_3,value_4,value_5,value_6,
                    value_7,value_8,value_9,value_10,value_11,value_12, 
                    ot_1,ot_2,ot_3,ot_4,ot_5,ot_6,
                    ot_7,ot_8,ot_9,ot_10,ot_11,ot_12,
                    ec_1,ec_2,ec_3,ec_4,ec_5,ec_6,
                    ec_7,ec_8,ec_9,ec_10,ec_11,ec_12               
            from   db_temp.t_temp_soprod
            where  id = $id and created_date = (select max(created_date) from db_temp.t_temp_soprod where id = $id)
            ORDER BY urutan asc
        ";                            
        $hasil = $this->db->query($query);
        query_to_csv($hasil,TRUE,"Sell Out Product.csv");
    }

    public function export_sales_per_product_kode_kodesalur_grup_subgrup(){
        $id=$this->session->userdata('id');
        $query="
            select  substr(kode,1,3) as kode_comp, kode, branch_name,nama_comp, groupsalur, `group`,nama_sub_group, subgroup, nama_sub_group,
                    unit_1,unit_2,unit_3,unit_4,unit_5,unit_6,
                    unit_7,unit_8,unit_9,unit_10,unit_11,unit_12,
                    value_1,value_2,value_3,value_4,value_5,value_6,
                    value_7,value_8,value_9,value_10,value_11,value_12, 
                    ot_1,ot_2,ot_3,ot_4,ot_5,ot_6,
                    ot_7,ot_8,ot_9,ot_10,ot_11,ot_12,
                    ec_1,ec_2,ec_3,ec_4,ec_5,ec_6,
                    ec_7,ec_8,ec_9,ec_10,ec_11,ec_12               
            from   db_temp.t_temp_soprod
            where  id = $id and created_date = (select max(created_date) from db_temp.t_temp_soprod where id = $id)
            ORDER BY urutan asc
        ";                            
        $hasil = $this->db->query($query);
        query_to_csv($hasil,TRUE,"Sell Out Product.csv");
    }

    public function export_sales_per_product_kode_kode_type_grup_subgrup(){
        $id=$this->session->userdata('id');
        $query="
            select  substr(kode,1,3) as kode_comp, kode, branch_name,nama_comp, sektor, segment, `group`, nama_sub_group, subgroup, nama_sub_group,
                    unit_1,unit_2,unit_3,unit_4,unit_5,unit_6,
                    unit_7,unit_8,unit_9,unit_10,unit_11,unit_12,
                    value_1,value_2,value_3,value_4,value_5,value_6,
                    value_7,value_8,value_9,value_10,value_11,value_12, 
                    ot_1,ot_2,ot_3,ot_4,ot_5,ot_6,
                    ot_7,ot_8,ot_9,ot_10,ot_11,ot_12,
                    ec_1,ec_2,ec_3,ec_4,ec_5,ec_6,
                    ec_7,ec_8,ec_9,ec_10,ec_11,ec_12               
            from   db_temp.t_temp_soprod
            where  id = $id and created_date = (select max(created_date) from db_temp.t_temp_soprod where id = $id)
            ORDER BY urutan asc
        ";                            
        $hasil = $this->db->query($query);
        query_to_csv($hasil,TRUE,"Sell Out Product.csv");
    }

    public function export_sales_per_product_kodeprod_kodesalur_kode_type_subgrup(){
        $id=$this->session->userdata('id');
        $query="
            select  kodeprod,namaprod,groupsalur,sektor, segment, subgroup,nama_sub_group,
                    unit_1,unit_2,unit_3,unit_4,unit_5,unit_6,
                    unit_7,unit_8,unit_9,unit_10,unit_11,unit_12,
                    value_1,value_2,value_3,value_4,value_5,value_6,
                    value_7,value_8,value_9,value_10,value_11,value_12, 
                    ot_1,ot_2,ot_3,ot_4,ot_5,ot_6,
                    ot_7,ot_8,ot_9,ot_10,ot_11,ot_12,
                    ec_1,ec_2,ec_3,ec_4,ec_5,ec_6,
                    ec_7,ec_8,ec_9,ec_10,ec_11,ec_12               
            from   db_temp.t_temp_soprod
            where  id = $id and created_date = (select max(created_date) from db_temp.t_temp_soprod where id = $id)
            ORDER BY urutan asc
        ";                            
        $hasil = $this->db->query($query);
        query_to_csv($hasil,TRUE,"Sell Out Product.csv");
    }

    public function export_sales_per_product_kodeprod_kodesalur_grup_subgrup(){
        $id=$this->session->userdata('id');
        $query="
            select  kodeprod,namaprod,groupsalur,`group`, nama_group, subgroup,nama_sub_group,
                    unit_1,unit_2,unit_3,unit_4,unit_5,unit_6,
                    unit_7,unit_8,unit_9,unit_10,unit_11,unit_12,
                    value_1,value_2,value_3,value_4,value_5,value_6,
                    value_7,value_8,value_9,value_10,value_11,value_12, 
                    ot_1,ot_2,ot_3,ot_4,ot_5,ot_6,
                    ot_7,ot_8,ot_9,ot_10,ot_11,ot_12,
                    ec_1,ec_2,ec_3,ec_4,ec_5,ec_6,
                    ec_7,ec_8,ec_9,ec_10,ec_11,ec_12               
            from   db_temp.t_temp_soprod
            where  id = $id and created_date = (select max(created_date) from db_temp.t_temp_soprod where id = $id)
            ORDER BY urutan asc
        ";                            
        $hasil = $this->db->query($query);
        query_to_csv($hasil,TRUE,"Sell Out Product.csv");
    }

    public function export_sales_per_product_kodeprod_kode_type_grup_subgrup(){
        $id=$this->session->userdata('id');
        $query="
            select  kodeprod,namaprod,sektor, segment, `group`, nama_group,subgroup,nama_sub_group,
                    unit_1,unit_2,unit_3,unit_4,unit_5,unit_6,
                    unit_7,unit_8,unit_9,unit_10,unit_11,unit_12,
                    value_1,value_2,value_3,value_4,value_5,value_6,
                    value_7,value_8,value_9,value_10,value_11,value_12, 
                    ot_1,ot_2,ot_3,ot_4,ot_5,ot_6,
                    ot_7,ot_8,ot_9,ot_10,ot_11,ot_12,
                    ec_1,ec_2,ec_3,ec_4,ec_5,ec_6,
                    ec_7,ec_8,ec_9,ec_10,ec_11,ec_12               
            from   db_temp.t_temp_soprod
            where  id = $id and created_date = (select max(created_date) from db_temp.t_temp_soprod where id = $id)
            ORDER BY urutan asc
        ";                            
        $hasil = $this->db->query($query);
        query_to_csv($hasil,TRUE,"Sell Out Product.csv");
    }

    public function export_sales_per_product_kodesalur_kode_type_grup_subgrup(){
        $id=$this->session->userdata('id');
        $query="
            select  groupsalur,sektor, segment, `group`, nama_group,subgroup,nama_sub_group,
                    unit_1,unit_2,unit_3,unit_4,unit_5,unit_6,
                    unit_7,unit_8,unit_9,unit_10,unit_11,unit_12,
                    value_1,value_2,value_3,value_4,value_5,value_6,
                    value_7,value_8,value_9,value_10,value_11,value_12, 
                    ot_1,ot_2,ot_3,ot_4,ot_5,ot_6,
                    ot_7,ot_8,ot_9,ot_10,ot_11,ot_12,
                    ec_1,ec_2,ec_3,ec_4,ec_5,ec_6,
                    ec_7,ec_8,ec_9,ec_10,ec_11,ec_12               
            from   db_temp.t_temp_soprod
            where  id = $id and created_date = (select max(created_date) from db_temp.t_temp_soprod where id = $id)
            ORDER BY urutan asc
        ";                            
        $hasil = $this->db->query($query);
        query_to_csv($hasil,TRUE,"Sell Out Product.csv");
    }





    // =============================================== Export Excel sell out produk =================================================

    
    public function export_excel_sales_per_product() {
        
        $id=$this->session->userdata('id');
        if ( function_exists( 'date_default_timezone_set' ) )
        date_default_timezone_set('Asia/Jakarta');
        $id=$this->session->userdata('id');
        $kondisi = $this->uri->segment('3');

        //kode
       if ($kondisi == '1'){  
            $hasil = $this->db->query("
            select  substr(kode,1,3) as kode_comp, kode, branch_name,nama_comp,
                    unit_1,unit_2,unit_3,unit_4,unit_5,unit_6,
                    unit_7,unit_8,unit_9,unit_10,unit_11,unit_12,
                    value_1,value_2,value_3,value_4,value_5,value_6,
                    value_7,value_8,value_9,value_10,value_11,value_12, 
                    ot_1,ot_2,ot_3,ot_4,ot_5,ot_6,
                    ot_7,ot_8,ot_9,ot_10,ot_11,ot_12,
                    ec_1,ec_2,ec_3,ec_4,ec_5,ec_6,
                    ec_7,ec_8,ec_9,ec_10,ec_11,ec_12               
            from   db_temp.t_temp_soprod
            where  id = $id and created_date = (select max(created_date) from db_temp.t_temp_soprod where id = $id)
            ORDER BY urutan asc");   
        
            $this->excel_generator->set_query($hasil);
            $this->excel_generator->set_header(array
            (
                'kode_comp', 'kode', 'branch_name','nama_comp',
                'unit_1','unit_2','unit_3','unit_4','unit_5','unit_6',
                'unit_7','unit_8','unit_9','unit_10','unit_11','unit_12',
                'value_1','value_2','value_3','value_4','value_5','value_6',
                'value_7','value_8','value_9','value_10','value_11','value_12', 
                'ot_1','ot_2','ot_3','ot_4','ot_5','ot_6',
                'ot_7','ot_8','ot_9','ot_10','ot_11','ot_12',
                'ec_1','ec_2','ec_3','ec_4','ec_5','ec_6',
                'ec_7','ec_8','ec_9','ec_10','ec_11','ec_12'
            ));
            $this->excel_generator->set_column(array
            ( 
                'kode_comp', 'kode', 'branch_name','nama_comp',
                'unit_1','unit_2','unit_3','unit_4','unit_5','unit_6',
                'unit_7','unit_8','unit_9','unit_10','unit_11','unit_12',
                'value_1','value_2','value_3','value_4','value_5','value_6',
                'value_7','value_8','value_9','value_10','value_11','value_12', 
                'ot_1','ot_2','ot_3','ot_4','ot_5','ot_6',
                'ot_7','ot_8','ot_9','ot_10','ot_11','ot_12',
                'ec_1','ec_2','ec_3','ec_4','ec_5','ec_6',
                'ec_7','ec_8','ec_9','ec_10','ec_11','ec_12'
            ));
            $this->excel_generator->set_width(array(10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10));
            //$this->excel_generator->exportTo2007('Omzet'.'_'.$tahun.'_'.$supplier);   
            $this->excel_generator->exportTo2007('Sell_Out_Product'); 
       }
       //kodeprod
       elseif ($kondisi == '2'){
            $hasil = $this->db->query("
            select  kodeprod, namaprod,`group`,nama_group,
                    unit_1,unit_2,unit_3,unit_4,unit_5,unit_6,
                    unit_7,unit_8,unit_9,unit_10,unit_11,unit_12,
                    value_1,value_2,value_3,value_4,value_5,value_6,
                    value_7,value_8,value_9,value_10,value_11,value_12, 
                    ot_1,ot_2,ot_3,ot_4,ot_5,ot_6,
                    ot_7,ot_8,ot_9,ot_10,ot_11,ot_12,
                    ec_1,ec_2,ec_3,ec_4,ec_5,ec_6,
                    ec_7,ec_8,ec_9,ec_10,ec_11,ec_12               
            from   db_temp.t_temp_soprod
            where  id = $id and created_date = (select max(created_date) from db_temp.t_temp_soprod where id = $id)
            ORDER BY urutan asc");   
        
            $this->excel_generator->set_query($hasil);
            $this->excel_generator->set_header(array
            (
                'kodeprod', 'namaprod','group','nama_group',
                'unit_1','unit_2','unit_3','unit_4','unit_5','unit_6',
                'unit_7','unit_8','unit_9','unit_10','unit_11','unit_12',
                'value_1','value_2','value_3','value_4','value_5','value_6',
                'value_7','value_8','value_9','value_10','value_11','value_12', 
                'ot_1','ot_2','ot_3','ot_4','ot_5','ot_6',
                'ot_7','ot_8','ot_9','ot_10','ot_11','ot_12',
                'ec_1','ec_2','ec_3','ec_4','ec_5','ec_6',
                'ec_7','ec_8','ec_9','ec_10','ec_11','ec_12'
            ));
            $this->excel_generator->set_column(array
            ( 
                'kodeprod', 'namaprod','group','nama_group',
                'unit_1','unit_2','unit_3','unit_4','unit_5','unit_6',
                'unit_7','unit_8','unit_9','unit_10','unit_11','unit_12',
                'value_1','value_2','value_3','value_4','value_5','value_6',
                'value_7','value_8','value_9','value_10','value_11','value_12', 
                'ot_1','ot_2','ot_3','ot_4','ot_5','ot_6',
                'ot_7','ot_8','ot_9','ot_10','ot_11','ot_12',
                'ec_1','ec_2','ec_3','ec_4','ec_5','ec_6',
                'ec_7','ec_8','ec_9','ec_10','ec_11','ec_12'
            ));
            $this->excel_generator->set_width(array(10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10));
            //$this->excel_generator->exportTo2007('Omzet'.'_'.$tahun.'_'.$supplier);   
            $this->excel_generator->exportTo2007('Sell_Out_Product'); 
        }
        //class (kodesalur)
        elseif ($kondisi == '3'){
            $hasil = $this->db->query("
            select  groupsalur,
                    unit_1,unit_2,unit_3,unit_4,unit_5,unit_6,
                    unit_7,unit_8,unit_9,unit_10,unit_11,unit_12,
                    value_1,value_2,value_3,value_4,value_5,value_6,
                    value_7,value_8,value_9,value_10,value_11,value_12, 
                    ot_1,ot_2,ot_3,ot_4,ot_5,ot_6,
                    ot_7,ot_8,ot_9,ot_10,ot_11,ot_12,
                    ec_1,ec_2,ec_3,ec_4,ec_5,ec_6,
                    ec_7,ec_8,ec_9,ec_10,ec_11,ec_12               
            from   db_temp.t_temp_soprod
            where  id = $id and created_date = (select max(created_date) from db_temp.t_temp_soprod where id = $id)
            ORDER BY urutan asc");   
        
            $this->excel_generator->set_query($hasil);
            $this->excel_generator->set_header(array
            (
                'groupsalur',
                'unit_1','unit_2','unit_3','unit_4','unit_5','unit_6',
                'unit_7','unit_8','unit_9','unit_10','unit_11','unit_12',
                'value_1','value_2','value_3','value_4','value_5','value_6',
                'value_7','value_8','value_9','value_10','value_11','value_12', 
                'ot_1','ot_2','ot_3','ot_4','ot_5','ot_6',
                'ot_7','ot_8','ot_9','ot_10','ot_11','ot_12',
                'ec_1','ec_2','ec_3','ec_4','ec_5','ec_6',
                'ec_7','ec_8','ec_9','ec_10','ec_11','ec_12'
            ));
            $this->excel_generator->set_column(array
            ( 
                'groupsalur',
                'unit_1','unit_2','unit_3','unit_4','unit_5','unit_6',
                'unit_7','unit_8','unit_9','unit_10','unit_11','unit_12',
                'value_1','value_2','value_3','value_4','value_5','value_6',
                'value_7','value_8','value_9','value_10','value_11','value_12', 
                'ot_1','ot_2','ot_3','ot_4','ot_5','ot_6',
                'ot_7','ot_8','ot_9','ot_10','ot_11','ot_12',
                'ec_1','ec_2','ec_3','ec_4','ec_5','ec_6',
                'ec_7','ec_8','ec_9','ec_10','ec_11','ec_12'
            ));
            $this->excel_generator->set_width(array(10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10));
            //$this->excel_generator->exportTo2007('Omzet'.'_'.$tahun.'_'.$supplier);   
            $this->excel_generator->exportTo2007('Sell_Out_Product'); 
        }
        //tipe
        elseif ($kondisi == '4'){
            $hasil = $this->db->query("
            select  kode_type, nama_type,sektor,segment,
                    unit_1,unit_2,unit_3,unit_4,unit_5,unit_6,
                    unit_7,unit_8,unit_9,unit_10,unit_11,unit_12,
                    value_1,value_2,value_3,value_4,value_5,value_6,
                    value_7,value_8,value_9,value_10,value_11,value_12, 
                    ot_1,ot_2,ot_3,ot_4,ot_5,ot_6,
                    ot_7,ot_8,ot_9,ot_10,ot_11,ot_12,
                    ec_1,ec_2,ec_3,ec_4,ec_5,ec_6,
                    ec_7,ec_8,ec_9,ec_10,ec_11,ec_12               
            from   db_temp.t_temp_soprod
            where  id = $id and created_date = (select max(created_date) from db_temp.t_temp_soprod where id = $id)
            ORDER BY urutan asc");   
        
            $this->excel_generator->set_query($hasil);
            $this->excel_generator->set_header(array
            (
                'kode_type', 'nama_type','sektor','segment',
                'unit_1','unit_2','unit_3','unit_4','unit_5','unit_6',
                'unit_7','unit_8','unit_9','unit_10','unit_11','unit_12',
                'value_1','value_2','value_3','value_4','value_5','value_6',
                'value_7','value_8','value_9','value_10','value_11','value_12', 
                'ot_1','ot_2','ot_3','ot_4','ot_5','ot_6',
                'ot_7','ot_8','ot_9','ot_10','ot_11','ot_12',
                'ec_1','ec_2','ec_3','ec_4','ec_5','ec_6',
                'ec_7','ec_8','ec_9','ec_10','ec_11','ec_12'
            ));
            $this->excel_generator->set_column(array
            ( 
                'kode_type', 'nama_type','sektor','segment',
                'unit_1','unit_2','unit_3','unit_4','unit_5','unit_6',
                'unit_7','unit_8','unit_9','unit_10','unit_11','unit_12',
                'value_1','value_2','value_3','value_4','value_5','value_6',
                'value_7','value_8','value_9','value_10','value_11','value_12', 
                'ot_1','ot_2','ot_3','ot_4','ot_5','ot_6',
                'ot_7','ot_8','ot_9','ot_10','ot_11','ot_12',
                'ec_1','ec_2','ec_3','ec_4','ec_5','ec_6',
                'ec_7','ec_8','ec_9','ec_10','ec_11','ec_12'
            ));
            $this->excel_generator->set_width(array(10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10));
            //$this->excel_generator->exportTo2007('Omzet'.'_'.$tahun.'_'.$supplier);   
            $this->excel_generator->exportTo2007('Sell_Out_Product'); 
        }

        // salesman

        elseif ($kondisi == '58') {
            $hasil = $this->db->query("
            select  substr(kode,1,3) as kode_comp, kodesales, namasales,
                    unit_1,unit_2,unit_3,unit_4,unit_5,unit_6,
                    unit_7,unit_8,unit_9,unit_10,unit_11,unit_12,
                    value_1,value_2,value_3,value_4,value_5,value_6,
                    value_7,value_8,value_9,value_10,value_11,value_12, 
                    ot_1,ot_2,ot_3,ot_4,ot_5,ot_6,
                    ot_7,ot_8,ot_9,ot_10,ot_11,ot_12,
                    ec_1,ec_2,ec_3,ec_4,ec_5,ec_6,
                    ec_7,ec_8,ec_9,ec_10,ec_11,ec_12               
            from   db_temp.t_temp_soprod
            where  id = $id and created_date = (select max(created_date) from db_temp.t_temp_soprod where id = $id)
            ORDER BY urutan asc");

            $this->excel_generator->set_query($hasil);
            $this->excel_generator->set_header(array(
                'kode_comp', 'kodesales', 'namasales',
                'unit_1', 'unit_2', 'unit_3', 'unit_4', 'unit_5', 'unit_6',
                'unit_7', 'unit_8', 'unit_9', 'unit_10', 'unit_11', 'unit_12',
                'value_1', 'value_2', 'value_3', 'value_4', 'value_5', 'value_6',
                'value_7', 'value_8', 'value_9', 'value_10', 'value_11', 'value_12',
                'ot_1', 'ot_2', 'ot_3', 'ot_4', 'ot_5', 'ot_6',
                'ot_7', 'ot_8', 'ot_9', 'ot_10', 'ot_11', 'ot_12',
                'ec_1', 'ec_2', 'ec_3', 'ec_4', 'ec_5', 'ec_6',
                'ec_7', 'ec_8', 'ec_9', 'ec_10', 'ec_11', 'ec_12'
            ));
            $this->excel_generator->set_column(array(
                'kode_comp', 'kodesales', 'namasales',
                'unit_1', 'unit_2', 'unit_3', 'unit_4', 'unit_5', 'unit_6',
                'unit_7', 'unit_8', 'unit_9', 'unit_10', 'unit_11', 'unit_12',
                'value_1', 'value_2', 'value_3', 'value_4', 'value_5', 'value_6',
                'value_7', 'value_8', 'value_9', 'value_10', 'value_11', 'value_12',
                'ot_1', 'ot_2', 'ot_3', 'ot_4', 'ot_5', 'ot_6',
                'ot_7', 'ot_8', 'ot_9', 'ot_10', 'ot_11', 'ot_12',
                'ec_1', 'ec_2', 'ec_3', 'ec_4', 'ec_5', 'ec_6',
                'ec_7', 'ec_8', 'ec_9', 'ec_10', 'ec_11', 'ec_12'
            ));
            $this->excel_generator->set_width(array(10,10,10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10));
            //$this->excel_generator->exportTo2007('Omzet'.'_'.$tahun.'_'.$supplier);   
            $this->excel_generator->exportTo2007('Sell_Out_Product');
        }

        //kode, kodeprod
        elseif ($kondisi == '5'){
            $hasil = $this->db->query("
            select  substr(kode,1,3) as kode_comp, kode, branch_name,nama_comp, kodeprod,namaprod,`group`,nama_group,
                    unit_1,unit_2,unit_3,unit_4,unit_5,unit_6,
                    unit_7,unit_8,unit_9,unit_10,unit_11,unit_12,
                    value_1,value_2,value_3,value_4,value_5,value_6,
                    value_7,value_8,value_9,value_10,value_11,value_12, 
                    ot_1,ot_2,ot_3,ot_4,ot_5,ot_6,
                    ot_7,ot_8,ot_9,ot_10,ot_11,ot_12,
                    ec_1,ec_2,ec_3,ec_4,ec_5,ec_6,
                    ec_7,ec_8,ec_9,ec_10,ec_11,ec_12               
            from   db_temp.t_temp_soprod
            where  id = $id and created_date = (select max(created_date) from db_temp.t_temp_soprod where id = $id)
            ORDER BY urutan asc");   
        
            $this->excel_generator->set_query($hasil);
            $this->excel_generator->set_header(array
            (
                'kode_comp', 'kode','branch_name','nama_comp', 'kodeprod','namaprod','group','nama_group',
                'unit_1','unit_2','unit_3','unit_4','unit_5','unit_6',
                'unit_7','unit_8','unit_9','unit_10','unit_11','unit_12',
                'value_1','value_2','value_3','value_4','value_5','value_6',
                'value_7','value_8','value_9','value_10','value_11','value_12', 
                'ot_1','ot_2','ot_3','ot_4','ot_5','ot_6',
                'ot_7','ot_8','ot_9','ot_10','ot_11','ot_12',
                'ec_1','ec_2','ec_3','ec_4','ec_5','ec_6',
                'ec_7','ec_8','ec_9','ec_10','ec_11','ec_12'
            ));
            $this->excel_generator->set_column(array
            ( 
                'kode_comp','kode', 'branch_name','nama_comp', 'kodeprod','namaprod','group','nama_group',
                'unit_1','unit_2','unit_3','unit_4','unit_5','unit_6',
                'unit_7','unit_8','unit_9','unit_10','unit_11','unit_12',
                'value_1','value_2','value_3','value_4','value_5','value_6',
                'value_7','value_8','value_9','value_10','value_11','value_12', 
                'ot_1','ot_2','ot_3','ot_4','ot_5','ot_6',
                'ot_7','ot_8','ot_9','ot_10','ot_11','ot_12',
                'ec_1','ec_2','ec_3','ec_4','ec_5','ec_6',
                'ec_7','ec_8','ec_9','ec_10','ec_11','ec_12'
            ));
            $this->excel_generator->set_width(array(10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10));
            //$this->excel_generator->exportTo2007('Omzet'.'_'.$tahun.'_'.$supplier);   
            $this->excel_generator->exportTo2007('Sell_Out_Product'); 
        }
        //kode, kodesalur
        elseif ($kondisi == '6'){
            $hasil = $this->db->query("
            select  substr(kode,1,3) as kode_comp, kode, branch_name,nama_comp, groupsalur,
                    unit_1,unit_2,unit_3,unit_4,unit_5,unit_6,
                    unit_7,unit_8,unit_9,unit_10,unit_11,unit_12,
                    value_1,value_2,value_3,value_4,value_5,value_6,
                    value_7,value_8,value_9,value_10,value_11,value_12, 
                    ot_1,ot_2,ot_3,ot_4,ot_5,ot_6,
                    ot_7,ot_8,ot_9,ot_10,ot_11,ot_12,
                    ec_1,ec_2,ec_3,ec_4,ec_5,ec_6,
                    ec_7,ec_8,ec_9,ec_10,ec_11,ec_12               
            from   db_temp.t_temp_soprod
            where  id = $id and created_date = (select max(created_date) from db_temp.t_temp_soprod where id = $id)
            ORDER BY urutan asc");   
        
            $this->excel_generator->set_query($hasil);
            $this->excel_generator->set_header(array
            (
                'kode_comp', 'kode','branch_name','nama_comp', 'groupsalur',
                'unit_1','unit_2','unit_3','unit_4','unit_5','unit_6',
                'unit_7','unit_8','unit_9','unit_10','unit_11','unit_12',
                'value_1','value_2','value_3','value_4','value_5','value_6',
                'value_7','value_8','value_9','value_10','value_11','value_12', 
                'ot_1','ot_2','ot_3','ot_4','ot_5','ot_6',
                'ot_7','ot_8','ot_9','ot_10','ot_11','ot_12',
                'ec_1','ec_2','ec_3','ec_4','ec_5','ec_6',
                'ec_7','ec_8','ec_9','ec_10','ec_11','ec_12'
            ));
            $this->excel_generator->set_column(array
            ( 
                'kode_comp','kode', 'branch_name','nama_comp', 'groupsalur',
                'unit_1','unit_2','unit_3','unit_4','unit_5','unit_6',
                'unit_7','unit_8','unit_9','unit_10','unit_11','unit_12',
                'value_1','value_2','value_3','value_4','value_5','value_6',
                'value_7','value_8','value_9','value_10','value_11','value_12', 
                'ot_1','ot_2','ot_3','ot_4','ot_5','ot_6',
                'ot_7','ot_8','ot_9','ot_10','ot_11','ot_12',
                'ec_1','ec_2','ec_3','ec_4','ec_5','ec_6',
                'ec_7','ec_8','ec_9','ec_10','ec_11','ec_12'
            ));
            $this->excel_generator->set_width(array(10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10));
            //$this->excel_generator->exportTo2007('Omzet'.'_'.$tahun.'_'.$supplier);   
            $this->excel_generator->exportTo2007('Sell_Out_Product'); 
        }
        //kode, kode_type
        elseif ($kondisi == '7'){
            $hasil = $this->db->query("
            select  substr(kode,1,3) as kode_comp, kode, branch_name,nama_comp,sektor,segment,
                    unit_1,unit_2,unit_3,unit_4,unit_5,unit_6,
                    unit_7,unit_8,unit_9,unit_10,unit_11,unit_12,
                    value_1,value_2,value_3,value_4,value_5,value_6,
                    value_7,value_8,value_9,value_10,value_11,value_12, 
                    ot_1,ot_2,ot_3,ot_4,ot_5,ot_6,
                    ot_7,ot_8,ot_9,ot_10,ot_11,ot_12,
                    ec_1,ec_2,ec_3,ec_4,ec_5,ec_6,
                    ec_7,ec_8,ec_9,ec_10,ec_11,ec_12               
            from   db_temp.t_temp_soprod
            where  id = $id and created_date = (select max(created_date) from db_temp.t_temp_soprod where id = $id)
            ORDER BY urutan asc");   
        
            $this->excel_generator->set_query($hasil);
            $this->excel_generator->set_header(array
            (
                'kode_comp', 'kode','branch_name','nama_comp', 'sektor','segment',
                'unit_1','unit_2','unit_3','unit_4','unit_5','unit_6',
                'unit_7','unit_8','unit_9','unit_10','unit_11','unit_12',
                'value_1','value_2','value_3','value_4','value_5','value_6',
                'value_7','value_8','value_9','value_10','value_11','value_12', 
                'ot_1','ot_2','ot_3','ot_4','ot_5','ot_6',
                'ot_7','ot_8','ot_9','ot_10','ot_11','ot_12',
                'ec_1','ec_2','ec_3','ec_4','ec_5','ec_6',
                'ec_7','ec_8','ec_9','ec_10','ec_11','ec_12'
            ));
            $this->excel_generator->set_column(array
            ( 
                'kode_comp','kode', 'branch_name','nama_comp', 'sektor','segment',
                'unit_1','unit_2','unit_3','unit_4','unit_5','unit_6',
                'unit_7','unit_8','unit_9','unit_10','unit_11','unit_12',
                'value_1','value_2','value_3','value_4','value_5','value_6',
                'value_7','value_8','value_9','value_10','value_11','value_12', 
                'ot_1','ot_2','ot_3','ot_4','ot_5','ot_6',
                'ot_7','ot_8','ot_9','ot_10','ot_11','ot_12',
                'ec_1','ec_2','ec_3','ec_4','ec_5','ec_6',
                'ec_7','ec_8','ec_9','ec_10','ec_11','ec_12'
            ));
            $this->excel_generator->set_width(array(10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10));
            //$this->excel_generator->exportTo2007('Omzet'.'_'.$tahun.'_'.$supplier);   
            $this->excel_generator->exportTo2007('Sell_Out_Product'); 
        }

        // kode, salesman

        elseif($kondisi == '59'){  
            $hasil = $this->db->query("
            select  substr(kode,1,3) as kode_comp, kode, branch_name, nama_comp, kodesales, namasales,
                    unit_1,unit_2,unit_3,unit_4,unit_5,unit_6,
                    unit_7,unit_8,unit_9,unit_10,unit_11,unit_12,
                    value_1,value_2,value_3,value_4,value_5,value_6,
                    value_7,value_8,value_9,value_10,value_11,value_12, 
                    ot_1,ot_2,ot_3,ot_4,ot_5,ot_6,
                    ot_7,ot_8,ot_9,ot_10,ot_11,ot_12,
                    ec_1,ec_2,ec_3,ec_4,ec_5,ec_6,
                    ec_7,ec_8,ec_9,ec_10,ec_11,ec_12               
            from   db_temp.t_temp_soprod
            where  id = $id and created_date = (select max(created_date) from db_temp.t_temp_soprod where id = $id)
            ORDER BY urutan asc");   
        
            $this->excel_generator->set_query($hasil);
            $this->excel_generator->set_header(array
            (
                'kode_comp', 'kode', 'branch_name','nama_comp','kodesales', 'namasales',
                'unit_1','unit_2','unit_3','unit_4','unit_5','unit_6',
                'unit_7','unit_8','unit_9','unit_10','unit_11','unit_12',
                'value_1','value_2','value_3','value_4','value_5','value_6',
                'value_7','value_8','value_9','value_10','value_11','value_12', 
                'ot_1','ot_2','ot_3','ot_4','ot_5','ot_6',
                'ot_7','ot_8','ot_9','ot_10','ot_11','ot_12',
                'ec_1','ec_2','ec_3','ec_4','ec_5','ec_6',
                'ec_7','ec_8','ec_9','ec_10','ec_11','ec_12'
            ));
            $this->excel_generator->set_column(array
            ( 
                'kode_comp', 'kode', 'branch_name','nama_comp','kodesales', 'namasales',
                'unit_1','unit_2','unit_3','unit_4','unit_5','unit_6',
                'unit_7','unit_8','unit_9','unit_10','unit_11','unit_12',
                'value_1','value_2','value_3','value_4','value_5','value_6',
                'value_7','value_8','value_9','value_10','value_11','value_12', 
                'ot_1','ot_2','ot_3','ot_4','ot_5','ot_6',
                'ot_7','ot_8','ot_9','ot_10','ot_11','ot_12',
                'ec_1','ec_2','ec_3','ec_4','ec_5','ec_6',
                'ec_7','ec_8','ec_9','ec_10','ec_11','ec_12'
            ));
            $this->excel_generator->set_width(array(10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10));
            //$this->excel_generator->exportTo2007('Omzet'.'_'.$tahun.'_'.$supplier);   
            $this->excel_generator->exportTo2007('Sell_Out_Product'); 
       }

        //kodeprod, kodesalur
        elseif ($kondisi == '8'){
            $hasil = $this->db->query("
            select  kodeprod,namaprod, groupsalur,
                    unit_1,unit_2,unit_3,unit_4,unit_5,unit_6,
                    unit_7,unit_8,unit_9,unit_10,unit_11,unit_12,
                    value_1,value_2,value_3,value_4,value_5,value_6,
                    value_7,value_8,value_9,value_10,value_11,value_12, 
                    ot_1,ot_2,ot_3,ot_4,ot_5,ot_6,
                    ot_7,ot_8,ot_9,ot_10,ot_11,ot_12,
                    ec_1,ec_2,ec_3,ec_4,ec_5,ec_6,
                    ec_7,ec_8,ec_9,ec_10,ec_11,ec_12               
            from   db_temp.t_temp_soprod
            where  id = $id and created_date = (select max(created_date) from db_temp.t_temp_soprod where id = $id)
            ORDER BY urutan asc");   
        
            $this->excel_generator->set_query($hasil);
            $this->excel_generator->set_header(array
            (
                'kodeprod','namaprod', 'groupsalur',
                'unit_1','unit_2','unit_3','unit_4','unit_5','unit_6',
                'unit_7','unit_8','unit_9','unit_10','unit_11','unit_12',
                'value_1','value_2','value_3','value_4','value_5','value_6',
                'value_7','value_8','value_9','value_10','value_11','value_12', 
                'ot_1','ot_2','ot_3','ot_4','ot_5','ot_6',
                'ot_7','ot_8','ot_9','ot_10','ot_11','ot_12',
                'ec_1','ec_2','ec_3','ec_4','ec_5','ec_6',
                'ec_7','ec_8','ec_9','ec_10','ec_11','ec_12'
            ));
            $this->excel_generator->set_column(array
            ( 
                'kodeprod','namaprod', 'groupsalur',
                'unit_1','unit_2','unit_3','unit_4','unit_5','unit_6',
                'unit_7','unit_8','unit_9','unit_10','unit_11','unit_12',
                'value_1','value_2','value_3','value_4','value_5','value_6',
                'value_7','value_8','value_9','value_10','value_11','value_12', 
                'ot_1','ot_2','ot_3','ot_4','ot_5','ot_6',
                'ot_7','ot_8','ot_9','ot_10','ot_11','ot_12',
                'ec_1','ec_2','ec_3','ec_4','ec_5','ec_6',
                'ec_7','ec_8','ec_9','ec_10','ec_11','ec_12'
            ));
            $this->excel_generator->set_width(array(10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10));
            //$this->excel_generator->exportTo2007('Omzet'.'_'.$tahun.'_'.$supplier);   
            $this->excel_generator->exportTo2007('Sell_Out_Product'); 
        }
        //kodeprod, kodetipe
        elseif ($kondisi == '9'){
            $hasil = $this->db->query("
            select  kodeprod,namaprod, sektor,
                    unit_1,unit_2,unit_3,unit_4,unit_5,unit_6,
                    unit_7,unit_8,unit_9,unit_10,unit_11,unit_12,
                    value_1,value_2,value_3,value_4,value_5,value_6,
                    value_7,value_8,value_9,value_10,value_11,value_12, 
                    ot_1,ot_2,ot_3,ot_4,ot_5,ot_6,
                    ot_7,ot_8,ot_9,ot_10,ot_11,ot_12,
                    ec_1,ec_2,ec_3,ec_4,ec_5,ec_6,
                    ec_7,ec_8,ec_9,ec_10,ec_11,ec_12               
            from   db_temp.t_temp_soprod
            where  id = $id and created_date = (select max(created_date) from db_temp.t_temp_soprod where id = $id)
            ORDER BY urutan asc");   
        
            $this->excel_generator->set_query($hasil);
            $this->excel_generator->set_header(array
            (
                'kodeprod','namaprod', 'sektor',
                'unit_1','unit_2','unit_3','unit_4','unit_5','unit_6',
                'unit_7','unit_8','unit_9','unit_10','unit_11','unit_12',
                'value_1','value_2','value_3','value_4','value_5','value_6',
                'value_7','value_8','value_9','value_10','value_11','value_12', 
                'ot_1','ot_2','ot_3','ot_4','ot_5','ot_6',
                'ot_7','ot_8','ot_9','ot_10','ot_11','ot_12',
                'ec_1','ec_2','ec_3','ec_4','ec_5','ec_6',
                'ec_7','ec_8','ec_9','ec_10','ec_11','ec_12'
            ));
            $this->excel_generator->set_column(array
            ( 
                'kodeprod','namaprod', 'sektor',
                'unit_1','unit_2','unit_3','unit_4','unit_5','unit_6',
                'unit_7','unit_8','unit_9','unit_10','unit_11','unit_12',
                'value_1','value_2','value_3','value_4','value_5','value_6',
                'value_7','value_8','value_9','value_10','value_11','value_12', 
                'ot_1','ot_2','ot_3','ot_4','ot_5','ot_6',
                'ot_7','ot_8','ot_9','ot_10','ot_11','ot_12',
                'ec_1','ec_2','ec_3','ec_4','ec_5','ec_6',
                'ec_7','ec_8','ec_9','ec_10','ec_11','ec_12'
            ));
            $this->excel_generator->set_width(array(10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10));
            //$this->excel_generator->exportTo2007('Omzet'.'_'.$tahun.'_'.$supplier);   
            $this->excel_generator->exportTo2007('Sell_Out_Product'); 
        }
        //kodesalur, kodetipe
        elseif ($kondisi == '10'){
            $hasil = $this->db->query("
            select  groupsalur,sektor,
                    unit_1,unit_2,unit_3,unit_4,unit_5,unit_6,
                    unit_7,unit_8,unit_9,unit_10,unit_11,unit_12,
                    value_1,value_2,value_3,value_4,value_5,value_6,
                    value_7,value_8,value_9,value_10,value_11,value_12, 
                    ot_1,ot_2,ot_3,ot_4,ot_5,ot_6,
                    ot_7,ot_8,ot_9,ot_10,ot_11,ot_12,
                    ec_1,ec_2,ec_3,ec_4,ec_5,ec_6,
                    ec_7,ec_8,ec_9,ec_10,ec_11,ec_12               
            from   db_temp.t_temp_soprod
            where  id = $id and created_date = (select max(created_date) from db_temp.t_temp_soprod where id = $id)
            ORDER BY urutan asc");   
        
            $this->excel_generator->set_query($hasil);
            $this->excel_generator->set_header(array
            (
                'groupsalur','sektor',
                'unit_1','unit_2','unit_3','unit_4','unit_5','unit_6',
                'unit_7','unit_8','unit_9','unit_10','unit_11','unit_12',
                'value_1','value_2','value_3','value_4','value_5','value_6',
                'value_7','value_8','value_9','value_10','value_11','value_12', 
                'ot_1','ot_2','ot_3','ot_4','ot_5','ot_6',
                'ot_7','ot_8','ot_9','ot_10','ot_11','ot_12',
                'ec_1','ec_2','ec_3','ec_4','ec_5','ec_6',
                'ec_7','ec_8','ec_9','ec_10','ec_11','ec_12'
            ));
            $this->excel_generator->set_column(array
            ( 
                'groupsalur','sektor',
                'unit_1','unit_2','unit_3','unit_4','unit_5','unit_6',
                'unit_7','unit_8','unit_9','unit_10','unit_11','unit_12',
                'value_1','value_2','value_3','value_4','value_5','value_6',
                'value_7','value_8','value_9','value_10','value_11','value_12', 
                'ot_1','ot_2','ot_3','ot_4','ot_5','ot_6',
                'ot_7','ot_8','ot_9','ot_10','ot_11','ot_12',
                'ec_1','ec_2','ec_3','ec_4','ec_5','ec_6',
                'ec_7','ec_8','ec_9','ec_10','ec_11','ec_12'
            ));
            $this->excel_generator->set_width(array(10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10));
            //$this->excel_generator->exportTo2007('Omzet'.'_'.$tahun.'_'.$supplier);   
            $this->excel_generator->exportTo2007('Sell_Out_Product'); 
        }
        //kode, kodeprod, kodesalur
        elseif ($kondisi == '11'){
            $hasil = $this->db->query("
            select  substr(kode,1,3) as kode_comp, kode, branch_name,nama_comp, kodeprod, namaprod,groupsalur,
                    unit_1,unit_2,unit_3,unit_4,unit_5,unit_6,
                    unit_7,unit_8,unit_9,unit_10,unit_11,unit_12,
                    value_1,value_2,value_3,value_4,value_5,value_6,
                    value_7,value_8,value_9,value_10,value_11,value_12, 
                    ot_1,ot_2,ot_3,ot_4,ot_5,ot_6,
                    ot_7,ot_8,ot_9,ot_10,ot_11,ot_12,
                    ec_1,ec_2,ec_3,ec_4,ec_5,ec_6,
                    ec_7,ec_8,ec_9,ec_10,ec_11,ec_12               
            from   db_temp.t_temp_soprod
            where  id = $id and created_date = (select max(created_date) from db_temp.t_temp_soprod where id = $id)
            ORDER BY urutan asc");   
        
            $this->excel_generator->set_query($hasil);
            $this->excel_generator->set_header(array
            (
                'kode_comp', 'kode', 'branch_name','nama_comp', 'kodeprod', 'namaprod','groupsalur',
                'unit_1','unit_2','unit_3','unit_4','unit_5','unit_6',
                'unit_7','unit_8','unit_9','unit_10','unit_11','unit_12',
                'value_1','value_2','value_3','value_4','value_5','value_6',
                'value_7','value_8','value_9','value_10','value_11','value_12', 
                'ot_1','ot_2','ot_3','ot_4','ot_5','ot_6',
                'ot_7','ot_8','ot_9','ot_10','ot_11','ot_12',
                'ec_1','ec_2','ec_3','ec_4','ec_5','ec_6',
                'ec_7','ec_8','ec_9','ec_10','ec_11','ec_12'
            ));
            $this->excel_generator->set_column(array
            ( 
                'kode_comp', 'kode', 'branch_name','nama_comp', 'kodeprod', 'namaprod','groupsalur',
                'unit_1','unit_2','unit_3','unit_4','unit_5','unit_6',
                'unit_7','unit_8','unit_9','unit_10','unit_11','unit_12',
                'value_1','value_2','value_3','value_4','value_5','value_6',
                'value_7','value_8','value_9','value_10','value_11','value_12', 
                'ot_1','ot_2','ot_3','ot_4','ot_5','ot_6',
                'ot_7','ot_8','ot_9','ot_10','ot_11','ot_12',
                'ec_1','ec_2','ec_3','ec_4','ec_5','ec_6',
                'ec_7','ec_8','ec_9','ec_10','ec_11','ec_12'
            ));
            $this->excel_generator->set_width(array(10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10));
            //$this->excel_generator->exportTo2007('Omzet'.'_'.$tahun.'_'.$supplier);   
            $this->excel_generator->exportTo2007('Sell_Out_Product'); 
        }
        //kode, kodeprod, kode_tipe
        elseif ($kondisi == '12'){
            $hasil = $this->db->query("
            select  substr(kode,1,3) as kode_comp, kode, branch_name,nama_comp, kodeprod, namaprod,sektor,
                    unit_1,unit_2,unit_3,unit_4,unit_5,unit_6,
                    unit_7,unit_8,unit_9,unit_10,unit_11,unit_12,
                    value_1,value_2,value_3,value_4,value_5,value_6,
                    value_7,value_8,value_9,value_10,value_11,value_12, 
                    ot_1,ot_2,ot_3,ot_4,ot_5,ot_6,
                    ot_7,ot_8,ot_9,ot_10,ot_11,ot_12,
                    ec_1,ec_2,ec_3,ec_4,ec_5,ec_6,
                    ec_7,ec_8,ec_9,ec_10,ec_11,ec_12               
            from   db_temp.t_temp_soprod
            where  id = $id and created_date = (select max(created_date) from db_temp.t_temp_soprod where id = $id)
            ORDER BY urutan asc");   
        
            $this->excel_generator->set_query($hasil);
            $this->excel_generator->set_header(array
            (
                'kode_comp', 'kode', 'branch_name','nama_comp', 'kodeprod', 'namaprod','sektor',
                'unit_1','unit_2','unit_3','unit_4','unit_5','unit_6',
                'unit_7','unit_8','unit_9','unit_10','unit_11','unit_12',
                'value_1','value_2','value_3','value_4','value_5','value_6',
                'value_7','value_8','value_9','value_10','value_11','value_12', 
                'ot_1','ot_2','ot_3','ot_4','ot_5','ot_6',
                'ot_7','ot_8','ot_9','ot_10','ot_11','ot_12',
                'ec_1','ec_2','ec_3','ec_4','ec_5','ec_6',
                'ec_7','ec_8','ec_9','ec_10','ec_11','ec_12'
            ));
            $this->excel_generator->set_column(array
            ( 
                'kode_comp', 'kode', 'branch_name','nama_comp', 'kodeprod', 'namaprod','sektor',
                'unit_1','unit_2','unit_3','unit_4','unit_5','unit_6',
                'unit_7','unit_8','unit_9','unit_10','unit_11','unit_12',
                'value_1','value_2','value_3','value_4','value_5','value_6',
                'value_7','value_8','value_9','value_10','value_11','value_12', 
                'ot_1','ot_2','ot_3','ot_4','ot_5','ot_6',
                'ot_7','ot_8','ot_9','ot_10','ot_11','ot_12',
                'ec_1','ec_2','ec_3','ec_4','ec_5','ec_6',
                'ec_7','ec_8','ec_9','ec_10','ec_11','ec_12'
            ));
            $this->excel_generator->set_width(array(10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10));
            //$this->excel_generator->exportTo2007('Omzet'.'_'.$tahun.'_'.$supplier);   
            $this->excel_generator->exportTo2007('Sell_Out_Product'); 
        }
        //kode, kodesalur, kode_tipe
        elseif ($kondisi == '13'){
            $hasil = $this->db->query("
            select  substr(kode,1,3) as kode_comp, kode, branch_name,nama_comp, groupsalur,sektor,
                    unit_1,unit_2,unit_3,unit_4,unit_5,unit_6,
                    unit_7,unit_8,unit_9,unit_10,unit_11,unit_12,
                    value_1,value_2,value_3,value_4,value_5,value_6,
                    value_7,value_8,value_9,value_10,value_11,value_12, 
                    ot_1,ot_2,ot_3,ot_4,ot_5,ot_6,
                    ot_7,ot_8,ot_9,ot_10,ot_11,ot_12,
                    ec_1,ec_2,ec_3,ec_4,ec_5,ec_6,
                    ec_7,ec_8,ec_9,ec_10,ec_11,ec_12               
            from   db_temp.t_temp_soprod
            where  id = $id and created_date = (select max(created_date) from db_temp.t_temp_soprod where id = $id)
            ORDER BY urutan asc");   
        
            $this->excel_generator->set_query($hasil);
            $this->excel_generator->set_header(array
            (
                'kode_comp', 'kode', 'branch_name','nama_comp', 'groupsalur','sektor',
                'unit_1','unit_2','unit_3','unit_4','unit_5','unit_6',
                'unit_7','unit_8','unit_9','unit_10','unit_11','unit_12',
                'value_1','value_2','value_3','value_4','value_5','value_6',
                'value_7','value_8','value_9','value_10','value_11','value_12', 
                'ot_1','ot_2','ot_3','ot_4','ot_5','ot_6',
                'ot_7','ot_8','ot_9','ot_10','ot_11','ot_12',
                'ec_1','ec_2','ec_3','ec_4','ec_5','ec_6',
                'ec_7','ec_8','ec_9','ec_10','ec_11','ec_12'
            ));
            $this->excel_generator->set_column(array
            ( 
                'kode_comp', 'kode', 'branch_name','nama_comp', 'groupsalur','sektor',
                'unit_1','unit_2','unit_3','unit_4','unit_5','unit_6',
                'unit_7','unit_8','unit_9','unit_10','unit_11','unit_12',
                'value_1','value_2','value_3','value_4','value_5','value_6',
                'value_7','value_8','value_9','value_10','value_11','value_12', 
                'ot_1','ot_2','ot_3','ot_4','ot_5','ot_6',
                'ot_7','ot_8','ot_9','ot_10','ot_11','ot_12',
                'ec_1','ec_2','ec_3','ec_4','ec_5','ec_6',
                'ec_7','ec_8','ec_9','ec_10','ec_11','ec_12'
            ));
            $this->excel_generator->set_width(array(10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10));
            //$this->excel_generator->exportTo2007('Omzet'.'_'.$tahun.'_'.$supplier);   
            $this->excel_generator->exportTo2007('Sell_Out_Product'); 
        }
        //kodeprod, kodesalur,kodetipe
        elseif ($kondisi == '14'){
            $hasil = $this->db->query("
            select  kodeprod, namaprod, groupsalur,sektor,
                    unit_1,unit_2,unit_3,unit_4,unit_5,unit_6,
                    unit_7,unit_8,unit_9,unit_10,unit_11,unit_12,
                    value_1,value_2,value_3,value_4,value_5,value_6,
                    value_7,value_8,value_9,value_10,value_11,value_12, 
                    ot_1,ot_2,ot_3,ot_4,ot_5,ot_6,
                    ot_7,ot_8,ot_9,ot_10,ot_11,ot_12,
                    ec_1,ec_2,ec_3,ec_4,ec_5,ec_6,
                    ec_7,ec_8,ec_9,ec_10,ec_11,ec_12               
            from   db_temp.t_temp_soprod
            where  id = $id and created_date = (select max(created_date) from db_temp.t_temp_soprod where id = $id)
            ORDER BY urutan asc");   
        
            $this->excel_generator->set_query($hasil);
            $this->excel_generator->set_header(array
            (
                'kodeprod', 'namaprod', 'groupsalur','sektor',
                'unit_1','unit_2','unit_3','unit_4','unit_5','unit_6',
                'unit_7','unit_8','unit_9','unit_10','unit_11','unit_12',
                'value_1','value_2','value_3','value_4','value_5','value_6',
                'value_7','value_8','value_9','value_10','value_11','value_12', 
                'ot_1','ot_2','ot_3','ot_4','ot_5','ot_6',
                'ot_7','ot_8','ot_9','ot_10','ot_11','ot_12',
                'ec_1','ec_2','ec_3','ec_4','ec_5','ec_6',
                'ec_7','ec_8','ec_9','ec_10','ec_11','ec_12'
            ));
            $this->excel_generator->set_column(array
            ( 
                'kodeprod', 'namaprod', 'groupsalur','sektor',
                'unit_1','unit_2','unit_3','unit_4','unit_5','unit_6',
                'unit_7','unit_8','unit_9','unit_10','unit_11','unit_12',
                'value_1','value_2','value_3','value_4','value_5','value_6',
                'value_7','value_8','value_9','value_10','value_11','value_12', 
                'ot_1','ot_2','ot_3','ot_4','ot_5','ot_6',
                'ot_7','ot_8','ot_9','ot_10','ot_11','ot_12',
                'ec_1','ec_2','ec_3','ec_4','ec_5','ec_6',
                'ec_7','ec_8','ec_9','ec_10','ec_11','ec_12'
            ));
            $this->excel_generator->set_width(array(10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10));
            //$this->excel_generator->exportTo2007('Omzet'.'_'.$tahun.'_'.$supplier);   
            $this->excel_generator->exportTo2007('Sell_Out_Product'); 
        }
        //kode, kodeprod, kodesalur ,kode_tipe
        elseif ($kondisi == '15'){
            $hasil = $this->db->query("
            select  substr(kode,1,3) as kode_comp, kode, branch_name,nama_comp, kodeprod, namaprod, groupsalur,sektor,
                    unit_1,unit_2,unit_3,unit_4,unit_5,unit_6,
                    unit_7,unit_8,unit_9,unit_10,unit_11,unit_12,
                    value_1,value_2,value_3,value_4,value_5,value_6,
                    value_7,value_8,value_9,value_10,value_11,value_12, 
                    ot_1,ot_2,ot_3,ot_4,ot_5,ot_6,
                    ot_7,ot_8,ot_9,ot_10,ot_11,ot_12,
                    ec_1,ec_2,ec_3,ec_4,ec_5,ec_6,
                    ec_7,ec_8,ec_9,ec_10,ec_11,ec_12               
            from   db_temp.t_temp_soprod
            where  id = $id and created_date = (select max(created_date) from db_temp.t_temp_soprod where id = $id)
            ORDER BY urutan asc");   
        
            $this->excel_generator->set_query($hasil);
            $this->excel_generator->set_header(array
            (
                'kode_comp', 'kode', 'branch_name','nama_comp', 'kodeprod', 'namaprod', 'groupsalur','sektor',
                'unit_1','unit_2','unit_3','unit_4','unit_5','unit_6',
                'unit_7','unit_8','unit_9','unit_10','unit_11','unit_12',
                'value_1','value_2','value_3','value_4','value_5','value_6',
                'value_7','value_8','value_9','value_10','value_11','value_12', 
                'ot_1','ot_2','ot_3','ot_4','ot_5','ot_6',
                'ot_7','ot_8','ot_9','ot_10','ot_11','ot_12',
                'ec_1','ec_2','ec_3','ec_4','ec_5','ec_6',
                'ec_7','ec_8','ec_9','ec_10','ec_11','ec_12'
            ));
            $this->excel_generator->set_column(array
            ( 
                'kode_comp', 'kode', 'branch_name','nama_comp', 'kodeprod', 'namaprod', 'groupsalur','sektor',
                'unit_1','unit_2','unit_3','unit_4','unit_5','unit_6',
                'unit_7','unit_8','unit_9','unit_10','unit_11','unit_12',
                'value_1','value_2','value_3','value_4','value_5','value_6',
                'value_7','value_8','value_9','value_10','value_11','value_12', 
                'ot_1','ot_2','ot_3','ot_4','ot_5','ot_6',
                'ot_7','ot_8','ot_9','ot_10','ot_11','ot_12',
                'ec_1','ec_2','ec_3','ec_4','ec_5','ec_6',
                'ec_7','ec_8','ec_9','ec_10','ec_11','ec_12'
            ));
            $this->excel_generator->set_width(array(10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10));
            //$this->excel_generator->exportTo2007('Omzet'.'_'.$tahun.'_'.$supplier);   
            $this->excel_generator->exportTo2007('Sell_Out_Product'); 
        }
        //group product
        elseif ($kondisi == '16'){
            $hasil = $this->db->query("
            select  `group`,nama_group,
                    unit_1,unit_2,unit_3,unit_4,unit_5,unit_6,
                    unit_7,unit_8,unit_9,unit_10,unit_11,unit_12,
                    value_1,value_2,value_3,value_4,value_5,value_6,
                    value_7,value_8,value_9,value_10,value_11,value_12, 
                    ot_1,ot_2,ot_3,ot_4,ot_5,ot_6,
                    ot_7,ot_8,ot_9,ot_10,ot_11,ot_12,
                    ec_1,ec_2,ec_3,ec_4,ec_5,ec_6,
                    ec_7,ec_8,ec_9,ec_10,ec_11,ec_12               
            from   db_temp.t_temp_soprod
            where  id = $id and created_date = (select max(created_date) from db_temp.t_temp_soprod where id = $id)
            ORDER BY urutan asc");   
        
            $this->excel_generator->set_query($hasil);
            $this->excel_generator->set_header(array
            (
                'group', 'nama_group',
                'unit_1','unit_2','unit_3','unit_4','unit_5','unit_6',
                'unit_7','unit_8','unit_9','unit_10','unit_11','unit_12',
                'value_1','value_2','value_3','value_4','value_5','value_6',
                'value_7','value_8','value_9','value_10','value_11','value_12', 
                'ot_1','ot_2','ot_3','ot_4','ot_5','ot_6',
                'ot_7','ot_8','ot_9','ot_10','ot_11','ot_12',
                'ec_1','ec_2','ec_3','ec_4','ec_5','ec_6',
                'ec_7','ec_8','ec_9','ec_10','ec_11','ec_12'
            ));
            $this->excel_generator->set_column(array
            ( 
                'group', 'nama_group',
                'unit_1','unit_2','unit_3','unit_4','unit_5','unit_6',
                'unit_7','unit_8','unit_9','unit_10','unit_11','unit_12',
                'value_1','value_2','value_3','value_4','value_5','value_6',
                'value_7','value_8','value_9','value_10','value_11','value_12', 
                'ot_1','ot_2','ot_3','ot_4','ot_5','ot_6',
                'ot_7','ot_8','ot_9','ot_10','ot_11','ot_12',
                'ec_1','ec_2','ec_3','ec_4','ec_5','ec_6',
                'ec_7','ec_8','ec_9','ec_10','ec_11','ec_12'
            ));
            $this->excel_generator->set_width(array(10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10));
            //$this->excel_generator->exportTo2007('Omzet'.'_'.$tahun.'_'.$supplier);   
            $this->excel_generator->exportTo2007('Sell_Out_Product'); 
        }
        //kode, group product
        elseif ($kondisi == '17'){
            $hasil = $this->db->query("
            select  substr(kode,1,3) as kode_comp, kode, branch_name,nama_comp, `group`, nama_group,
                    unit_1,unit_2,unit_3,unit_4,unit_5,unit_6,
                    unit_7,unit_8,unit_9,unit_10,unit_11,unit_12,
                    value_1,value_2,value_3,value_4,value_5,value_6,
                    value_7,value_8,value_9,value_10,value_11,value_12, 
                    ot_1,ot_2,ot_3,ot_4,ot_5,ot_6,
                    ot_7,ot_8,ot_9,ot_10,ot_11,ot_12,
                    ec_1,ec_2,ec_3,ec_4,ec_5,ec_6,
                    ec_7,ec_8,ec_9,ec_10,ec_11,ec_12               
            from   db_temp.t_temp_soprod
            where  id = $id and created_date = (select max(created_date) from db_temp.t_temp_soprod where id = $id)
            ORDER BY urutan asc");   
        
            $this->excel_generator->set_query($hasil);
            $this->excel_generator->set_header(array
            (
                'kode_comp', 'kode', 'branch_name','nama_comp', 'group', 'nama_group',
                'unit_1','unit_2','unit_3','unit_4','unit_5','unit_6',
                'unit_7','unit_8','unit_9','unit_10','unit_11','unit_12',
                'value_1','value_2','value_3','value_4','value_5','value_6',
                'value_7','value_8','value_9','value_10','value_11','value_12', 
                'ot_1','ot_2','ot_3','ot_4','ot_5','ot_6',
                'ot_7','ot_8','ot_9','ot_10','ot_11','ot_12',
                'ec_1','ec_2','ec_3','ec_4','ec_5','ec_6',
                'ec_7','ec_8','ec_9','ec_10','ec_11','ec_12'
            ));
            $this->excel_generator->set_column(array
            ( 
                'kode_comp', 'kode', 'branch_name','nama_comp', 'group', 'nama_group',
                'unit_1','unit_2','unit_3','unit_4','unit_5','unit_6',
                'unit_7','unit_8','unit_9','unit_10','unit_11','unit_12',
                'value_1','value_2','value_3','value_4','value_5','value_6',
                'value_7','value_8','value_9','value_10','value_11','value_12', 
                'ot_1','ot_2','ot_3','ot_4','ot_5','ot_6',
                'ot_7','ot_8','ot_9','ot_10','ot_11','ot_12',
                'ec_1','ec_2','ec_3','ec_4','ec_5','ec_6',
                'ec_7','ec_8','ec_9','ec_10','ec_11','ec_12'
            ));
            $this->excel_generator->set_width(array(10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10));
            //$this->excel_generator->exportTo2007('Omzet'.'_'.$tahun.'_'.$supplier);   
            $this->excel_generator->exportTo2007('Sell_Out_Product'); 
        }
        //kodeprod, group product
        elseif ($kondisi == '18'){
            $hasil = $this->db->query("
            select  kodeprod, namaprod, group,nama_group,
                    unit_1,unit_2,unit_3,unit_4,unit_5,unit_6,
                    unit_7,unit_8,unit_9,unit_10,unit_11,unit_12,
                    value_1,value_2,value_3,value_4,value_5,value_6,
                    value_7,value_8,value_9,value_10,value_11,value_12, 
                    ot_1,ot_2,ot_3,ot_4,ot_5,ot_6,
                    ot_7,ot_8,ot_9,ot_10,ot_11,ot_12,
                    ec_1,ec_2,ec_3,ec_4,ec_5,ec_6,
                    ec_7,ec_8,ec_9,ec_10,ec_11,ec_12               
            from   db_temp.t_temp_soprod
            where  id = $id and created_date = (select max(created_date) from db_temp.t_temp_soprod where id = $id)
            ORDER BY urutan asc");   
        
            $this->excel_generator->set_query($hasil);
            $this->excel_generator->set_header(array
            (
                'kodeprod', 'namaprod', 'group','nama_group',
                'unit_1','unit_2','unit_3','unit_4','unit_5','unit_6',
                'unit_7','unit_8','unit_9','unit_10','unit_11','unit_12',
                'value_1','value_2','value_3','value_4','value_5','value_6',
                'value_7','value_8','value_9','value_10','value_11','value_12', 
                'ot_1','ot_2','ot_3','ot_4','ot_5','ot_6',
                'ot_7','ot_8','ot_9','ot_10','ot_11','ot_12',
                'ec_1','ec_2','ec_3','ec_4','ec_5','ec_6',
                'ec_7','ec_8','ec_9','ec_10','ec_11','ec_12'
            ));
            $this->excel_generator->set_column(array
            ( 
                'kodeprod', 'namaprod', 'group','nama_group',
                'unit_1','unit_2','unit_3','unit_4','unit_5','unit_6',
                'unit_7','unit_8','unit_9','unit_10','unit_11','unit_12',
                'value_1','value_2','value_3','value_4','value_5','value_6',
                'value_7','value_8','value_9','value_10','value_11','value_12', 
                'ot_1','ot_2','ot_3','ot_4','ot_5','ot_6',
                'ot_7','ot_8','ot_9','ot_10','ot_11','ot_12',
                'ec_1','ec_2','ec_3','ec_4','ec_5','ec_6',
                'ec_7','ec_8','ec_9','ec_10','ec_11','ec_12'
            ));
            $this->excel_generator->set_width(array(10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,));
            //$this->excel_generator->exportTo2007('Omzet'.'_'.$tahun.'_'.$supplier);   
            $this->excel_generator->exportTo2007('Sell_Out_Product'); 
        }
        //kodesalur, group product
        elseif ($kondisi == '19'){
            $hasil = $this->db->query("
            select  groupsalur,`group`,nama_group,
                    unit_1,unit_2,unit_3,unit_4,unit_5,unit_6,
                    unit_7,unit_8,unit_9,unit_10,unit_11,unit_12,
                    value_1,value_2,value_3,value_4,value_5,value_6,
                    value_7,value_8,value_9,value_10,value_11,value_12, 
                    ot_1,ot_2,ot_3,ot_4,ot_5,ot_6,
                    ot_7,ot_8,ot_9,ot_10,ot_11,ot_12,
                    ec_1,ec_2,ec_3,ec_4,ec_5,ec_6,
                    ec_7,ec_8,ec_9,ec_10,ec_11,ec_12               
            from   db_temp.t_temp_soprod
            where  id = $id and created_date = (select max(created_date) from db_temp.t_temp_soprod where id = $id)
            ORDER BY urutan asc");   
        
            $this->excel_generator->set_query($hasil);
            $this->excel_generator->set_header(array
            (
                'groupsalur', 'group', 'nama_group',
                'unit_1','unit_2','unit_3','unit_4','unit_5','unit_6',
                'unit_7','unit_8','unit_9','unit_10','unit_11','unit_12',
                'value_1','value_2','value_3','value_4','value_5','value_6',
                'value_7','value_8','value_9','value_10','value_11','value_12', 
                'ot_1','ot_2','ot_3','ot_4','ot_5','ot_6',
                'ot_7','ot_8','ot_9','ot_10','ot_11','ot_12',
                'ec_1','ec_2','ec_3','ec_4','ec_5','ec_6',
                'ec_7','ec_8','ec_9','ec_10','ec_11','ec_12'
            ));
            $this->excel_generator->set_column(array
            ( 
                'groupsalur', 'group', 'nama_group',
                'unit_1','unit_2','unit_3','unit_4','unit_5','unit_6',
                'unit_7','unit_8','unit_9','unit_10','unit_11','unit_12',
                'value_1','value_2','value_3','value_4','value_5','value_6',
                'value_7','value_8','value_9','value_10','value_11','value_12', 
                'ot_1','ot_2','ot_3','ot_4','ot_5','ot_6',
                'ot_7','ot_8','ot_9','ot_10','ot_11','ot_12',
                'ec_1','ec_2','ec_3','ec_4','ec_5','ec_6',
                'ec_7','ec_8','ec_9','ec_10','ec_11','ec_12'
            ));
            $this->excel_generator->set_width(array(10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10));
            //$this->excel_generator->exportTo2007('Omzet'.'_'.$tahun.'_'.$supplier);   
        }
        //kode_type, group product
        elseif ($kondisi == '20'){
            $hasil = $this->db->query("
            select  sektor, segment, `group`,nama_group,
                    unit_1,unit_2,unit_3,unit_4,unit_5,unit_6,
                    unit_7,unit_8,unit_9,unit_10,unit_11,unit_12,
                    value_1,value_2,value_3,value_4,value_5,value_6,
                    value_7,value_8,value_9,value_10,value_11,value_12, 
                    ot_1,ot_2,ot_3,ot_4,ot_5,ot_6,
                    ot_7,ot_8,ot_9,ot_10,ot_11,ot_12,
                    ec_1,ec_2,ec_3,ec_4,ec_5,ec_6,
                    ec_7,ec_8,ec_9,ec_10,ec_11,ec_12               
            from   db_temp.t_temp_soprod
            where  id = $id and created_date = (select max(created_date) from db_temp.t_temp_soprod where id = $id)
            ORDER BY urutan asc");   
        
            $this->excel_generator->set_query($hasil);
            $this->excel_generator->set_header(array
            (
                'sektor','segment','group','nama_group',
                'unit_1','unit_2','unit_3','unit_4','unit_5','unit_6',
                'unit_7','unit_8','unit_9','unit_10','unit_11','unit_12',
                'value_1','value_2','value_3','value_4','value_5','value_6',
                'value_7','value_8','value_9','value_10','value_11','value_12', 
                'ot_1','ot_2','ot_3','ot_4','ot_5','ot_6',
                'ot_7','ot_8','ot_9','ot_10','ot_11','ot_12',
                'ec_1','ec_2','ec_3','ec_4','ec_5','ec_6',
                'ec_7','ec_8','ec_9','ec_10','ec_11','ec_12'
            ));
            $this->excel_generator->set_column(array
            ( 
                'sektor','segment','group','nama_group',
                'unit_1','unit_2','unit_3','unit_4','unit_5','unit_6',
                'unit_7','unit_8','unit_9','unit_10','unit_11','unit_12',
                'value_1','value_2','value_3','value_4','value_5','value_6',
                'value_7','value_8','value_9','value_10','value_11','value_12', 
                'ot_1','ot_2','ot_3','ot_4','ot_5','ot_6',
                'ot_7','ot_8','ot_9','ot_10','ot_11','ot_12',
                'ec_1','ec_2','ec_3','ec_4','ec_5','ec_6',
                'ec_7','ec_8','ec_9','ec_10','ec_11','ec_12'
            ));
            $this->excel_generator->set_width(array(10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10));
            //$this->excel_generator->exportTo2007('Omzet'.'_'.$tahun.'_'.$supplier);   
        }
        //kode,kodeprod, group product
        elseif ($kondisi == '21'){
            $hasil = $this->db->query("
            select  substr(kode,1,3) as kode_comp, kode, branch_name,nama_comp, kodeprod, namaprod, `group`, nama_group,
                    unit_1,unit_2,unit_3,unit_4,unit_5,unit_6,
                    unit_7,unit_8,unit_9,unit_10,unit_11,unit_12,
                    value_1,value_2,value_3,value_4,value_5,value_6,
                    value_7,value_8,value_9,value_10,value_11,value_12, 
                    ot_1,ot_2,ot_3,ot_4,ot_5,ot_6,
                    ot_7,ot_8,ot_9,ot_10,ot_11,ot_12,
                    ec_1,ec_2,ec_3,ec_4,ec_5,ec_6,
                    ec_7,ec_8,ec_9,ec_10,ec_11,ec_12               
            from   db_temp.t_temp_soprod
            where  id = $id and created_date = (select max(created_date) from db_temp.t_temp_soprod where id = $id)
            ORDER BY urutan asc");   
        
            $this->excel_generator->set_query($hasil);
            $this->excel_generator->set_header(array
            (
                'kode_comp', 'kode', 'branch_name','nama_comp', 'kodeprod', 'namaprod', 'group','nama_group',
                'unit_1','unit_2','unit_3','unit_4','unit_5','unit_6',
                'unit_7','unit_8','unit_9','unit_10','unit_11','unit_12',
                'value_1','value_2','value_3','value_4','value_5','value_6',
                'value_7','value_8','value_9','value_10','value_11','value_12', 
                'ot_1','ot_2','ot_3','ot_4','ot_5','ot_6',
                'ot_7','ot_8','ot_9','ot_10','ot_11','ot_12',
                'ec_1','ec_2','ec_3','ec_4','ec_5','ec_6',
                'ec_7','ec_8','ec_9','ec_10','ec_11','ec_12'
            ));
            $this->excel_generator->set_column(array
            ( 
                'kode_comp', 'kode', 'branch_name','nama_comp', 'kodeprod', 'namaprod', 'group','nama_group',
                'unit_1','unit_2','unit_3','unit_4','unit_5','unit_6',
                'unit_7','unit_8','unit_9','unit_10','unit_11','unit_12',
                'value_1','value_2','value_3','value_4','value_5','value_6',
                'value_7','value_8','value_9','value_10','value_11','value_12', 
                'ot_1','ot_2','ot_3','ot_4','ot_5','ot_6',
                'ot_7','ot_8','ot_9','ot_10','ot_11','ot_12',
                'ec_1','ec_2','ec_3','ec_4','ec_5','ec_6',
                'ec_7','ec_8','ec_9','ec_10','ec_11','ec_12'
            ));
            $this->excel_generator->set_width(array(10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10));
            //$this->excel_generator->exportTo2007('Omzet'.'_'.$tahun.'_'.$supplier);   
            $this->excel_generator->exportTo2007('Sell_Out_Product'); 
        }
        //kode, kodesalur, group product
        elseif ($kondisi == '22'){
            $hasil = $this->db->query("
            select  substr(kode,1,3) as kode_comp, kode, branch_name,nama_comp, groupsalur,`group`,nama_group,
                    unit_1,unit_2,unit_3,unit_4,unit_5,unit_6,
                    unit_7,unit_8,unit_9,unit_10,unit_11,unit_12,
                    value_1,value_2,value_3,value_4,value_5,value_6,
                    value_7,value_8,value_9,value_10,value_11,value_12, 
                    ot_1,ot_2,ot_3,ot_4,ot_5,ot_6,
                    ot_7,ot_8,ot_9,ot_10,ot_11,ot_12,
                    ec_1,ec_2,ec_3,ec_4,ec_5,ec_6,
                    ec_7,ec_8,ec_9,ec_10,ec_11,ec_12               
            from   db_temp.t_temp_soprod
            where  id = $id and created_date = (select max(created_date) from db_temp.t_temp_soprod where id = $id)
            ORDER BY urutan asc");   
        
            $this->excel_generator->set_query($hasil);
            $this->excel_generator->set_header(array
            (
                'kode_comp', 'kode', 'branch_name','nama_comp', 'groupsalur','group','nama_group',
                'unit_1','unit_2','unit_3','unit_4','unit_5','unit_6',
                'unit_7','unit_8','unit_9','unit_10','unit_11','unit_12',
                'value_1','value_2','value_3','value_4','value_5','value_6',
                'value_7','value_8','value_9','value_10','value_11','value_12', 
                'ot_1','ot_2','ot_3','ot_4','ot_5','ot_6',
                'ot_7','ot_8','ot_9','ot_10','ot_11','ot_12',
                'ec_1','ec_2','ec_3','ec_4','ec_5','ec_6',
                'ec_7','ec_8','ec_9','ec_10','ec_11','ec_12'
            ));
            $this->excel_generator->set_column(array
            ( 
                'kode_comp', 'kode', 'branch_name','nama_comp', 'groupsalur','group','nama_group',
                'unit_1','unit_2','unit_3','unit_4','unit_5','unit_6',
                'unit_7','unit_8','unit_9','unit_10','unit_11','unit_12',
                'value_1','value_2','value_3','value_4','value_5','value_6',
                'value_7','value_8','value_9','value_10','value_11','value_12', 
                'ot_1','ot_2','ot_3','ot_4','ot_5','ot_6',
                'ot_7','ot_8','ot_9','ot_10','ot_11','ot_12',
                'ec_1','ec_2','ec_3','ec_4','ec_5','ec_6',
                'ec_7','ec_8','ec_9','ec_10','ec_11','ec_12'
            ));
            $this->excel_generator->set_width(array(10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10));
            //$this->excel_generator->exportTo2007('Omzet'.'_'.$tahun.'_'.$supplier);   
            $this->excel_generator->exportTo2007('Sell_Out_Product'); 
        }
        //kode, kode_type, group product
        elseif ($kondisi == '23'){
            $hasil = $this->db->query("
            select  substr(kode,1,3) as kode_comp, kode, branch_name,nama_comp,sektor,segment,`group`,nama_group,
                    unit_1,unit_2,unit_3,unit_4,unit_5,unit_6,
                    unit_7,unit_8,unit_9,unit_10,unit_11,unit_12,
                    value_1,value_2,value_3,value_4,value_5,value_6,
                    value_7,value_8,value_9,value_10,value_11,value_12, 
                    ot_1,ot_2,ot_3,ot_4,ot_5,ot_6,
                    ot_7,ot_8,ot_9,ot_10,ot_11,ot_12,
                    ec_1,ec_2,ec_3,ec_4,ec_5,ec_6,
                    ec_7,ec_8,ec_9,ec_10,ec_11,ec_12               
            from   db_temp.t_temp_soprod
            where  id = $id and created_date = (select max(created_date) from db_temp.t_temp_soprod where id = $id)
            ORDER BY urutan asc");   
        
            $this->excel_generator->set_query($hasil);
            $this->excel_generator->set_header(array
            (
                'kode_comp', 'kode', 'branch_name','nama_comp', 'sektor','segment','group','nama_group',
                'unit_1','unit_2','unit_3','unit_4','unit_5','unit_6',
                'unit_7','unit_8','unit_9','unit_10','unit_11','unit_12',
                'value_1','value_2','value_3','value_4','value_5','value_6',
                'value_7','value_8','value_9','value_10','value_11','value_12', 
                'ot_1','ot_2','ot_3','ot_4','ot_5','ot_6',
                'ot_7','ot_8','ot_9','ot_10','ot_11','ot_12',
                'ec_1','ec_2','ec_3','ec_4','ec_5','ec_6',
                'ec_7','ec_8','ec_9','ec_10','ec_11','ec_12'
            ));
            $this->excel_generator->set_column(array
            ( 
                'kode_comp', 'kode', 'branch_name','nama_comp', 'sektor','segment','group','nama_group',
                'unit_1','unit_2','unit_3','unit_4','unit_5','unit_6',
                'unit_7','unit_8','unit_9','unit_10','unit_11','unit_12',
                'value_1','value_2','value_3','value_4','value_5','value_6',
                'value_7','value_8','value_9','value_10','value_11','value_12', 
                'ot_1','ot_2','ot_3','ot_4','ot_5','ot_6',
                'ot_7','ot_8','ot_9','ot_10','ot_11','ot_12',
                'ec_1','ec_2','ec_3','ec_4','ec_5','ec_6',
                'ec_7','ec_8','ec_9','ec_10','ec_11','ec_12'
            ));
            $this->excel_generator->set_width(array(10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10));
            //$this->excel_generator->exportTo2007('Omzet'.'_'.$tahun.'_'.$supplier);   
            $this->excel_generator->exportTo2007('Sell_Out_Product'); 
        }
        //kodeprod, kodesalur, grup
        elseif ($kondisi == '24'){
            $hasil = $this->db->query("
            select  kodeprod, namaprod, groupsalur,`group`,nama_group,
                    unit_1,unit_2,unit_3,unit_4,unit_5,unit_6,
                    unit_7,unit_8,unit_9,unit_10,unit_11,unit_12,
                    value_1,value_2,value_3,value_4,value_5,value_6,
                    value_7,value_8,value_9,value_10,value_11,value_12, 
                    ot_1,ot_2,ot_3,ot_4,ot_5,ot_6,
                    ot_7,ot_8,ot_9,ot_10,ot_11,ot_12,
                    ec_1,ec_2,ec_3,ec_4,ec_5,ec_6,
                    ec_7,ec_8,ec_9,ec_10,ec_11,ec_12               
            from   db_temp.t_temp_soprod
            where  id = $id and created_date = (select max(created_date) from db_temp.t_temp_soprod where id = $id)
            ORDER BY urutan asc");   
        
            $this->excel_generator->set_query($hasil);
            $this->excel_generator->set_header(array
            (
                'kodeprod', 'namaprod', 'groupsalur','group','nama_group',
                'unit_1','unit_2','unit_3','unit_4','unit_5','unit_6',
                'unit_7','unit_8','unit_9','unit_10','unit_11','unit_12',
                'value_1','value_2','value_3','value_4','value_5','value_6',
                'value_7','value_8','value_9','value_10','value_11','value_12', 
                'ot_1','ot_2','ot_3','ot_4','ot_5','ot_6',
                'ot_7','ot_8','ot_9','ot_10','ot_11','ot_12',
                'ec_1','ec_2','ec_3','ec_4','ec_5','ec_6',
                'ec_7','ec_8','ec_9','ec_10','ec_11','ec_12'
            ));
            $this->excel_generator->set_column(array
            ( 
                'kodeprod', 'namaprod', 'groupsalur','group','nama_group',
                'unit_1','unit_2','unit_3','unit_4','unit_5','unit_6',
                'unit_7','unit_8','unit_9','unit_10','unit_11','unit_12',
                'value_1','value_2','value_3','value_4','value_5','value_6',
                'value_7','value_8','value_9','value_10','value_11','value_12', 
                'ot_1','ot_2','ot_3','ot_4','ot_5','ot_6',
                'ot_7','ot_8','ot_9','ot_10','ot_11','ot_12',
                'ec_1','ec_2','ec_3','ec_4','ec_5','ec_6',
                'ec_7','ec_8','ec_9','ec_10','ec_11','ec_12'
            ));
            $this->excel_generator->set_width(array(10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10));
            //$this->excel_generator->exportTo2007('Omzet'.'_'.$tahun.'_'.$supplier);   
            $this->excel_generator->exportTo2007('Sell_Out_Product'); 
        }
        //kodeprod, kode_type, group product
        elseif ($kondisi == '25'){
            $hasil = $this->db->query("
            select  kodeprod, namaprod, sektor, segment,`group`,nama_group,
                    unit_1,unit_2,unit_3,unit_4,unit_5,unit_6,
                    unit_7,unit_8,unit_9,unit_10,unit_11,unit_12,
                    value_1,value_2,value_3,value_4,value_5,value_6,
                    value_7,value_8,value_9,value_10,value_11,value_12, 
                    ot_1,ot_2,ot_3,ot_4,ot_5,ot_6,
                    ot_7,ot_8,ot_9,ot_10,ot_11,ot_12,
                    ec_1,ec_2,ec_3,ec_4,ec_5,ec_6,
                    ec_7,ec_8,ec_9,ec_10,ec_11,ec_12               
            from   db_temp.t_temp_soprod
            where  id = $id and created_date = (select max(created_date) from db_temp.t_temp_soprod where id = $id)
            ORDER BY urutan asc");   
        
            $this->excel_generator->set_query($hasil);
            $this->excel_generator->set_header(array
            (
                'kodeprod', 'namaprod', 'sektor','segment','group','nama_group',
                'unit_1','unit_2','unit_3','unit_4','unit_5','unit_6',
                'unit_7','unit_8','unit_9','unit_10','unit_11','unit_12',
                'value_1','value_2','value_3','value_4','value_5','value_6',
                'value_7','value_8','value_9','value_10','value_11','value_12', 
                'ot_1','ot_2','ot_3','ot_4','ot_5','ot_6',
                'ot_7','ot_8','ot_9','ot_10','ot_11','ot_12',
                'ec_1','ec_2','ec_3','ec_4','ec_5','ec_6',
                'ec_7','ec_8','ec_9','ec_10','ec_11','ec_12'
            ));
            $this->excel_generator->set_column(array
            ( 
                'kodeprod', 'namaprod', 'sektor','segment','group','nama_group',
                'unit_1','unit_2','unit_3','unit_4','unit_5','unit_6',
                'unit_7','unit_8','unit_9','unit_10','unit_11','unit_12',
                'value_1','value_2','value_3','value_4','value_5','value_6',
                'value_7','value_8','value_9','value_10','value_11','value_12', 
                'ot_1','ot_2','ot_3','ot_4','ot_5','ot_6',
                'ot_7','ot_8','ot_9','ot_10','ot_11','ot_12',
                'ec_1','ec_2','ec_3','ec_4','ec_5','ec_6',
                'ec_7','ec_8','ec_9','ec_10','ec_11','ec_12'
            ));
            $this->excel_generator->set_width(array(10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10));
            //$this->excel_generator->exportTo2007('Omzet'.'_'.$tahun.'_'.$supplier);   
            $this->excel_generator->exportTo2007('Sell_Out_Product'); 
        }

        //kodesalur ,kode_tipe, group produk
        elseif ($kondisi == '26'){
            $hasil = $this->db->query("
            select  groupsalur,sektor,`group`,nama_group,
                    unit_1,unit_2,unit_3,unit_4,unit_5,unit_6,
                    unit_7,unit_8,unit_9,unit_10,unit_11,unit_12,
                    value_1,value_2,value_3,value_4,value_5,value_6,
                    value_7,value_8,value_9,value_10,value_11,value_12, 
                    ot_1,ot_2,ot_3,ot_4,ot_5,ot_6,
                    ot_7,ot_8,ot_9,ot_10,ot_11,ot_12,
                    ec_1,ec_2,ec_3,ec_4,ec_5,ec_6,
                    ec_7,ec_8,ec_9,ec_10,ec_11,ec_12               
            from   db_temp.t_temp_soprod
            where  id = $id and created_date = (select max(created_date) from db_temp.t_temp_soprod where id = $id)
            ORDER BY urutan asc");   
        
            $this->excel_generator->set_query($hasil);
            $this->excel_generator->set_header(array
            (
                'groupsalur','sektor','group', 'nama_group',
                'unit_1','unit_2','unit_3','unit_4','unit_5','unit_6',
                'unit_7','unit_8','unit_9','unit_10','unit_11','unit_12',
                'value_1','value_2','value_3','value_4','value_5','value_6',
                'value_7','value_8','value_9','value_10','value_11','value_12', 
                'ot_1','ot_2','ot_3','ot_4','ot_5','ot_6',
                'ot_7','ot_8','ot_9','ot_10','ot_11','ot_12',
                'ec_1','ec_2','ec_3','ec_4','ec_5','ec_6',
                'ec_7','ec_8','ec_9','ec_10','ec_11','ec_12'
            ));
            $this->excel_generator->set_column(array
            ( 
                'groupsalur','sektor','group', 'nama_group',
                'unit_1','unit_2','unit_3','unit_4','unit_5','unit_6',
                'unit_7','unit_8','unit_9','unit_10','unit_11','unit_12',
                'value_1','value_2','value_3','value_4','value_5','value_6',
                'value_7','value_8','value_9','value_10','value_11','value_12', 
                'ot_1','ot_2','ot_3','ot_4','ot_5','ot_6',
                'ot_7','ot_8','ot_9','ot_10','ot_11','ot_12',
                'ec_1','ec_2','ec_3','ec_4','ec_5','ec_6',
                'ec_7','ec_8','ec_9','ec_10','ec_11','ec_12'
            ));
            $this->excel_generator->set_width(array(10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10));
            //$this->excel_generator->exportTo2007('Omzet'.'_'.$tahun.'_'.$supplier);   
            $this->excel_generator->exportTo2007('Sell_Out_Product'); 
        }
        //kode, kodeprod, kodesalur ,group product
        elseif ($kondisi == '27'){
            $hasil = $this->db->query("
            select  substr(kode,1,3) as kode_comp, kode, branch_name,nama_comp, kodeprod, namaprod, groupsalur,`group`,nama_group,
                    unit_1,unit_2,unit_3,unit_4,unit_5,unit_6,
                    unit_7,unit_8,unit_9,unit_10,unit_11,unit_12,
                    value_1,value_2,value_3,value_4,value_5,value_6,
                    value_7,value_8,value_9,value_10,value_11,value_12, 
                    ot_1,ot_2,ot_3,ot_4,ot_5,ot_6,
                    ot_7,ot_8,ot_9,ot_10,ot_11,ot_12,
                    ec_1,ec_2,ec_3,ec_4,ec_5,ec_6,
                    ec_7,ec_8,ec_9,ec_10,ec_11,ec_12               
            from   db_temp.t_temp_soprod
            where  id = $id and created_date = (select max(created_date) from db_temp.t_temp_soprod where id = $id)
            ORDER BY urutan asc");   
        
            $this->excel_generator->set_query($hasil);
            $this->excel_generator->set_header(array
            (
                'kode_comp', 'kode', 'branch_name','nama_comp', 'kodeprod', 'namaprod', 'groupsalur','group','nama_group',
                'unit_1','unit_2','unit_3','unit_4','unit_5','unit_6',
                'unit_7','unit_8','unit_9','unit_10','unit_11','unit_12',
                'value_1','value_2','value_3','value_4','value_5','value_6',
                'value_7','value_8','value_9','value_10','value_11','value_12', 
                'ot_1','ot_2','ot_3','ot_4','ot_5','ot_6',
                'ot_7','ot_8','ot_9','ot_10','ot_11','ot_12',
                'ec_1','ec_2','ec_3','ec_4','ec_5','ec_6',
                'ec_7','ec_8','ec_9','ec_10','ec_11','ec_12'
            ));
            $this->excel_generator->set_column(array
            ( 
                'kode_comp', 'kode', 'branch_name','nama_comp', 'kodeprod', 'namaprod', 'groupsalur','group','nama_group',
                'unit_1','unit_2','unit_3','unit_4','unit_5','unit_6',
                'unit_7','unit_8','unit_9','unit_10','unit_11','unit_12',
                'value_1','value_2','value_3','value_4','value_5','value_6',
                'value_7','value_8','value_9','value_10','value_11','value_12', 
                'ot_1','ot_2','ot_3','ot_4','ot_5','ot_6',
                'ot_7','ot_8','ot_9','ot_10','ot_11','ot_12',
                'ec_1','ec_2','ec_3','ec_4','ec_5','ec_6',
                'ec_7','ec_8','ec_9','ec_10','ec_11','ec_12'
            ));
            $this->excel_generator->set_width(array(10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10));
            //$this->excel_generator->exportTo2007('Omzet'.'_'.$tahun.'_'.$supplier);   
            $this->excel_generator->exportTo2007('Sell_Out_Product'); 
        }

        //kode, kodeprod,kode_tipe, group
        elseif ($kondisi == '28'){
            $hasil = $this->db->query("
            select  substr(kode,1,3) as kode_comp, kode, branch_name,nama_comp, kodeprod, namaprod,sektor,`group`,nama_group,
                    unit_1,unit_2,unit_3,unit_4,unit_5,unit_6,
                    unit_7,unit_8,unit_9,unit_10,unit_11,unit_12,
                    value_1,value_2,value_3,value_4,value_5,value_6,
                    value_7,value_8,value_9,value_10,value_11,value_12, 
                    ot_1,ot_2,ot_3,ot_4,ot_5,ot_6,
                    ot_7,ot_8,ot_9,ot_10,ot_11,ot_12,
                    ec_1,ec_2,ec_3,ec_4,ec_5,ec_6,
                    ec_7,ec_8,ec_9,ec_10,ec_11,ec_12               
            from   db_temp.t_temp_soprod
            where  id = $id and created_date = (select max(created_date) from db_temp.t_temp_soprod where id = $id)
            ORDER BY urutan asc");   
        
            $this->excel_generator->set_query($hasil);
            $this->excel_generator->set_header(array
            (
                'kode_comp', 'kode', 'branch_name','nama_comp', 'kodeprod', 'namaprod','sektor','group','nama_group',
                'unit_1','unit_2','unit_3','unit_4','unit_5','unit_6',
                'unit_7','unit_8','unit_9','unit_10','unit_11','unit_12',
                'value_1','value_2','value_3','value_4','value_5','value_6',
                'value_7','value_8','value_9','value_10','value_11','value_12', 
                'ot_1','ot_2','ot_3','ot_4','ot_5','ot_6',
                'ot_7','ot_8','ot_9','ot_10','ot_11','ot_12',
                'ec_1','ec_2','ec_3','ec_4','ec_5','ec_6',
                'ec_7','ec_8','ec_9','ec_10','ec_11','ec_12'
            ));
            $this->excel_generator->set_column(array
            ( 
                'kode_comp', 'kode', 'branch_name','nama_comp', 'kodeprod', 'namaprod','sektor','group','nama_group',
                'unit_1','unit_2','unit_3','unit_4','unit_5','unit_6',
                'unit_7','unit_8','unit_9','unit_10','unit_11','unit_12',
                'value_1','value_2','value_3','value_4','value_5','value_6',
                'value_7','value_8','value_9','value_10','value_11','value_12', 
                'ot_1','ot_2','ot_3','ot_4','ot_5','ot_6',
                'ot_7','ot_8','ot_9','ot_10','ot_11','ot_12',
                'ec_1','ec_2','ec_3','ec_4','ec_5','ec_6',
                'ec_7','ec_8','ec_9','ec_10','ec_11','ec_12'
            ));
            $this->excel_generator->set_width(array(10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10));
            //$this->excel_generator->exportTo2007('Omzet'.'_'.$tahun.'_'.$supplier);   
            $this->excel_generator->exportTo2007('Sell_Out_Product'); 
        }

        //kode, kodesalur ,kode_tipe, group
        elseif ($kondisi == '29'){
            $hasil = $this->db->query("
            select  substr(kode,1,3) as kode_comp, kode, branch_name,nama_comp, groupsalur,sektor,`group`, nama_group,
                    unit_1,unit_2,unit_3,unit_4,unit_5,unit_6,
                    unit_7,unit_8,unit_9,unit_10,unit_11,unit_12,
                    value_1,value_2,value_3,value_4,value_5,value_6,
                    value_7,value_8,value_9,value_10,value_11,value_12, 
                    ot_1,ot_2,ot_3,ot_4,ot_5,ot_6,
                    ot_7,ot_8,ot_9,ot_10,ot_11,ot_12,
                    ec_1,ec_2,ec_3,ec_4,ec_5,ec_6,
                    ec_7,ec_8,ec_9,ec_10,ec_11,ec_12               
            from   db_temp.t_temp_soprod
            where  id = $id and created_date = (select max(created_date) from db_temp.t_temp_soprod where id = $id)
            ORDER BY urutan asc");   
        
            $this->excel_generator->set_query($hasil);
            $this->excel_generator->set_header(array
            (
                'kode_comp', 'kode', 'branch_name','nama_comp', 'groupsalur','sektor', 'group', 'nama_group',
                'unit_1','unit_2','unit_3','unit_4','unit_5','unit_6',
                'unit_7','unit_8','unit_9','unit_10','unit_11','unit_12',
                'value_1','value_2','value_3','value_4','value_5','value_6',
                'value_7','value_8','value_9','value_10','value_11','value_12', 
                'ot_1','ot_2','ot_3','ot_4','ot_5','ot_6',
                'ot_7','ot_8','ot_9','ot_10','ot_11','ot_12',
                'ec_1','ec_2','ec_3','ec_4','ec_5','ec_6',
                'ec_7','ec_8','ec_9','ec_10','ec_11','ec_12'
            ));
            $this->excel_generator->set_column(array
            ( 
                'kode_comp', 'kode', 'branch_name','nama_comp', 'groupsalur','sektor', 'group', 'nama_group',
                'unit_1','unit_2','unit_3','unit_4','unit_5','unit_6',
                'unit_7','unit_8','unit_9','unit_10','unit_11','unit_12',
                'value_1','value_2','value_3','value_4','value_5','value_6',
                'value_7','value_8','value_9','value_10','value_11','value_12', 
                'ot_1','ot_2','ot_3','ot_4','ot_5','ot_6',
                'ot_7','ot_8','ot_9','ot_10','ot_11','ot_12',
                'ec_1','ec_2','ec_3','ec_4','ec_5','ec_6',
                'ec_7','ec_8','ec_9','ec_10','ec_11','ec_12'
            ));
            $this->excel_generator->set_width(array(10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10));
            //$this->excel_generator->exportTo2007('Omzet'.'_'.$tahun.'_'.$supplier);   
            $this->excel_generator->exportTo2007('Sell_Out_Product'); 
        }

        //kodeprod, kodesalur ,kode_tipe, group produk
        elseif ($kondisi == '30'){
            $hasil = $this->db->query("
            select  kodeprod, namaprod, groupsalur,sektor,`group`, nama_group,
                    unit_1,unit_2,unit_3,unit_4,unit_5,unit_6,
                    unit_7,unit_8,unit_9,unit_10,unit_11,unit_12,
                    value_1,value_2,value_3,value_4,value_5,value_6,
                    value_7,value_8,value_9,value_10,value_11,value_12, 
                    ot_1,ot_2,ot_3,ot_4,ot_5,ot_6,
                    ot_7,ot_8,ot_9,ot_10,ot_11,ot_12,
                    ec_1,ec_2,ec_3,ec_4,ec_5,ec_6,
                    ec_7,ec_8,ec_9,ec_10,ec_11,ec_12               
            from   db_temp.t_temp_soprod
            where  id = $id and created_date = (select max(created_date) from db_temp.t_temp_soprod where id = $id)
            ORDER BY urutan asc");   
        
            $this->excel_generator->set_query($hasil);
            $this->excel_generator->set_header(array
            (
                'kodeprod', 'namaprod', 'groupsalur','sektor','group','nama_group',
                'unit_1','unit_2','unit_3','unit_4','unit_5','unit_6',
                'unit_7','unit_8','unit_9','unit_10','unit_11','unit_12',
                'value_1','value_2','value_3','value_4','value_5','value_6',
                'value_7','value_8','value_9','value_10','value_11','value_12', 
                'ot_1','ot_2','ot_3','ot_4','ot_5','ot_6',
                'ot_7','ot_8','ot_9','ot_10','ot_11','ot_12',
                'ec_1','ec_2','ec_3','ec_4','ec_5','ec_6',
                'ec_7','ec_8','ec_9','ec_10','ec_11','ec_12'
            ));
            $this->excel_generator->set_column(array
            ( 
                'kodeprod', 'namaprod', 'groupsalur','sektor','group','nama_group',
                'unit_1','unit_2','unit_3','unit_4','unit_5','unit_6',
                'unit_7','unit_8','unit_9','unit_10','unit_11','unit_12',
                'value_1','value_2','value_3','value_4','value_5','value_6',
                'value_7','value_8','value_9','value_10','value_11','value_12', 
                'ot_1','ot_2','ot_3','ot_4','ot_5','ot_6',
                'ot_7','ot_8','ot_9','ot_10','ot_11','ot_12',
                'ec_1','ec_2','ec_3','ec_4','ec_5','ec_6',
                'ec_7','ec_8','ec_9','ec_10','ec_11','ec_12'
            ));
            $this->excel_generator->set_width(array(10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10));
            //$this->excel_generator->exportTo2007('Omzet'.'_'.$tahun.'_'.$supplier);   
            $this->excel_generator->exportTo2007('Sell_Out_Product'); 
        }

        //kode, kodeprod, kodesalur ,kode_tipe, group product
        elseif ($kondisi == '31'){
            $hasil = $this->db->query("
            select  substr(kode,1,3) as kode_comp, kode, branch_name,nama_comp, kodeprod, namaprod, groupsalur,sektor,`group`,nama_group,
                    unit_1,unit_2,unit_3,unit_4,unit_5,unit_6,
                    unit_7,unit_8,unit_9,unit_10,unit_11,unit_12,
                    value_1,value_2,value_3,value_4,value_5,value_6,
                    value_7,value_8,value_9,value_10,value_11,value_12, 
                    ot_1,ot_2,ot_3,ot_4,ot_5,ot_6,
                    ot_7,ot_8,ot_9,ot_10,ot_11,ot_12,
                    ec_1,ec_2,ec_3,ec_4,ec_5,ec_6,
                    ec_7,ec_8,ec_9,ec_10,ec_11,ec_12               
            from   db_temp.t_temp_soprod
            where  id = $id and created_date = (select max(created_date) from db_temp.t_temp_soprod where id = $id)
            ORDER BY urutan asc");   
        
            $this->excel_generator->set_query($hasil);
            $this->excel_generator->set_header(array
            (
                'kode_comp', 'kode', 'branch_name','nama_comp', 'kodeprod', 'namaprod', 'groupsalur','sektor','group','nama_group',
                'unit_1','unit_2','unit_3','unit_4','unit_5','unit_6',
                'unit_7','unit_8','unit_9','unit_10','unit_11','unit_12',
                'value_1','value_2','value_3','value_4','value_5','value_6',
                'value_7','value_8','value_9','value_10','value_11','value_12', 
                'ot_1','ot_2','ot_3','ot_4','ot_5','ot_6',
                'ot_7','ot_8','ot_9','ot_10','ot_11','ot_12',
                'ec_1','ec_2','ec_3','ec_4','ec_5','ec_6',
                'ec_7','ec_8','ec_9','ec_10','ec_11','ec_12'
            ));
            $this->excel_generator->set_column(array
            ( 
                'kode_comp', 'kode', 'branch_name','nama_comp', 'kodeprod', 'namaprod', 'groupsalur','sektor','group','nama_group',
                'unit_1','unit_2','unit_3','unit_4','unit_5','unit_6',
                'unit_7','unit_8','unit_9','unit_10','unit_11','unit_12',
                'value_1','value_2','value_3','value_4','value_5','value_6',
                'value_7','value_8','value_9','value_10','value_11','value_12', 
                'ot_1','ot_2','ot_3','ot_4','ot_5','ot_6',
                'ot_7','ot_8','ot_9','ot_10','ot_11','ot_12',
                'ec_1','ec_2','ec_3','ec_4','ec_5','ec_6',
                'ec_7','ec_8','ec_9','ec_10','ec_11','ec_12'
            ));
            $this->excel_generator->set_width(array(10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10));
            //$this->excel_generator->exportTo2007('Omzet'.'_'.$tahun.'_'.$supplier);   
            $this->excel_generator->exportTo2007('Sell_Out_Product'); 
        }
        //subgroup
        elseif ($kondisi == '32'){
            $hasil = $this->db->query("
            select  subgroup, nama_sub_group,
                    unit_1,unit_2,unit_3,unit_4,unit_5,unit_6,
                    unit_7,unit_8,unit_9,unit_10,unit_11,unit_12,
                    value_1,value_2,value_3,value_4,value_5,value_6,
                    value_7,value_8,value_9,value_10,value_11,value_12, 
                    ot_1,ot_2,ot_3,ot_4,ot_5,ot_6,
                    ot_7,ot_8,ot_9,ot_10,ot_11,ot_12,
                    ec_1,ec_2,ec_3,ec_4,ec_5,ec_6,
                    ec_7,ec_8,ec_9,ec_10,ec_11,ec_12               
            from   db_temp.t_temp_soprod
            where  id = $id and created_date = (select max(created_date) from db_temp.t_temp_soprod where id = $id)
            ORDER BY urutan asc");   
        
            $this->excel_generator->set_query($hasil);
            $this->excel_generator->set_header(array
            (
                'subgroup','nama_sub_group',
                'unit_1','unit_2','unit_3','unit_4','unit_5','unit_6',
                'unit_7','unit_8','unit_9','unit_10','unit_11','unit_12',
                'value_1','value_2','value_3','value_4','value_5','value_6',
                'value_7','value_8','value_9','value_10','value_11','value_12', 
                'ot_1','ot_2','ot_3','ot_4','ot_5','ot_6',
                'ot_7','ot_8','ot_9','ot_10','ot_11','ot_12',
                'ec_1','ec_2','ec_3','ec_4','ec_5','ec_6',
                'ec_7','ec_8','ec_9','ec_10','ec_11','ec_12'
            ));
            $this->excel_generator->set_column(array
            ( 
                'subgroup','nama_sub_group',
                'unit_1','unit_2','unit_3','unit_4','unit_5','unit_6',
                'unit_7','unit_8','unit_9','unit_10','unit_11','unit_12',
                'value_1','value_2','value_3','value_4','value_5','value_6',
                'value_7','value_8','value_9','value_10','value_11','value_12', 
                'ot_1','ot_2','ot_3','ot_4','ot_5','ot_6',
                'ot_7','ot_8','ot_9','ot_10','ot_11','ot_12',
                'ec_1','ec_2','ec_3','ec_4','ec_5','ec_6',
                'ec_7','ec_8','ec_9','ec_10','ec_11','ec_12'
            ));
            $this->excel_generator->set_width(array(10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10));
            //$this->excel_generator->exportTo2007('Omzet'.'_'.$tahun.'_'.$supplier);   
            $this->excel_generator->exportTo2007('Sell_Out_Product'); 
        }
        //kode, subgroup
        elseif ($kondisi == '33'){
            $hasil = $this->db->query("
            select  substr(kode,1,3) as kode_comp, kode, branch_name,nama_comp, subgroup, nama_sub_group,
                    unit_1,unit_2,unit_3,unit_4,unit_5,unit_6,
                    unit_7,unit_8,unit_9,unit_10,unit_11,unit_12,
                    value_1,value_2,value_3,value_4,value_5,value_6,
                    value_7,value_8,value_9,value_10,value_11,value_12, 
                    ot_1,ot_2,ot_3,ot_4,ot_5,ot_6,
                    ot_7,ot_8,ot_9,ot_10,ot_11,ot_12,
                    ec_1,ec_2,ec_3,ec_4,ec_5,ec_6,
                    ec_7,ec_8,ec_9,ec_10,ec_11,ec_12               
            from   db_temp.t_temp_soprod
            where  id = $id and created_date = (select max(created_date) from db_temp.t_temp_soprod where id = $id)
            ORDER BY urutan asc");   
        
            $this->excel_generator->set_query($hasil);
            $this->excel_generator->set_header(array
            (
                'kode_comp', 'kode', 'branch_name','nama_comp','subgroup','nama_sub_group',
                'unit_1','unit_2','unit_3','unit_4','unit_5','unit_6',
                'unit_7','unit_8','unit_9','unit_10','unit_11','unit_12',
                'value_1','value_2','value_3','value_4','value_5','value_6',
                'value_7','value_8','value_9','value_10','value_11','value_12', 
                'ot_1','ot_2','ot_3','ot_4','ot_5','ot_6',
                'ot_7','ot_8','ot_9','ot_10','ot_11','ot_12',
                'ec_1','ec_2','ec_3','ec_4','ec_5','ec_6',
                'ec_7','ec_8','ec_9','ec_10','ec_11','ec_12'
            ));
            $this->excel_generator->set_column(array
            ( 
                'kode_comp', 'kode', 'branch_name','nama_comp','subgroup','nama_sub_group',
                'unit_1','unit_2','unit_3','unit_4','unit_5','unit_6',
                'unit_7','unit_8','unit_9','unit_10','unit_11','unit_12',
                'value_1','value_2','value_3','value_4','value_5','value_6',
                'value_7','value_8','value_9','value_10','value_11','value_12', 
                'ot_1','ot_2','ot_3','ot_4','ot_5','ot_6',
                'ot_7','ot_8','ot_9','ot_10','ot_11','ot_12',
                'ec_1','ec_2','ec_3','ec_4','ec_5','ec_6',
                'ec_7','ec_8','ec_9','ec_10','ec_11','ec_12'
            ));
            $this->excel_generator->set_width(array(10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10));
            //$this->excel_generator->exportTo2007('Omzet'.'_'.$tahun.'_'.$supplier);   
            $this->excel_generator->exportTo2007('Sell_Out_Product'); 
        }
        //kodeprod, subgroup
        elseif ($kondisi == '34'){
            $hasil = $this->db->query("
            select  kodeprod,namaprod, subgroup, nama_sub_group,
                    unit_1,unit_2,unit_3,unit_4,unit_5,unit_6,
                    unit_7,unit_8,unit_9,unit_10,unit_11,unit_12,
                    value_1,value_2,value_3,value_4,value_5,value_6,
                    value_7,value_8,value_9,value_10,value_11,value_12, 
                    ot_1,ot_2,ot_3,ot_4,ot_5,ot_6,
                    ot_7,ot_8,ot_9,ot_10,ot_11,ot_12,
                    ec_1,ec_2,ec_3,ec_4,ec_5,ec_6,
                    ec_7,ec_8,ec_9,ec_10,ec_11,ec_12               
            from   db_temp.t_temp_soprod
            where  id = $id and created_date = (select max(created_date) from db_temp.t_temp_soprod where id = $id)
            ORDER BY urutan asc");   
        
            $this->excel_generator->set_query($hasil);
            $this->excel_generator->set_header(array
            (
                'kodeprod', 'namaprod','subgroup','nama_sub_group',
                'unit_1','unit_2','unit_3','unit_4','unit_5','unit_6',
                'unit_7','unit_8','unit_9','unit_10','unit_11','unit_12',
                'value_1','value_2','value_3','value_4','value_5','value_6',
                'value_7','value_8','value_9','value_10','value_11','value_12', 
                'ot_1','ot_2','ot_3','ot_4','ot_5','ot_6',
                'ot_7','ot_8','ot_9','ot_10','ot_11','ot_12',
                'ec_1','ec_2','ec_3','ec_4','ec_5','ec_6',
                'ec_7','ec_8','ec_9','ec_10','ec_11','ec_12'
            ));
            $this->excel_generator->set_column(array
            ( 
                'kodeprod', 'namaprod','subgroup','nama_sub_group',
                'unit_1','unit_2','unit_3','unit_4','unit_5','unit_6',
                'unit_7','unit_8','unit_9','unit_10','unit_11','unit_12',
                'value_1','value_2','value_3','value_4','value_5','value_6',
                'value_7','value_8','value_9','value_10','value_11','value_12', 
                'ot_1','ot_2','ot_3','ot_4','ot_5','ot_6',
                'ot_7','ot_8','ot_9','ot_10','ot_11','ot_12',
                'ec_1','ec_2','ec_3','ec_4','ec_5','ec_6',
                'ec_7','ec_8','ec_9','ec_10','ec_11','ec_12'
            ));
            $this->excel_generator->set_width(array(10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10));
            //$this->excel_generator->exportTo2007('Omzet'.'_'.$tahun.'_'.$supplier);   
            $this->excel_generator->exportTo2007('Sell_Out_Product'); 
        }
        //kodesalur, subgroup
        elseif ($kondisi == '35'){
            $hasil = $this->db->query("
            select  groupsalur, subgroup, nama_sub_group,
                    unit_1,unit_2,unit_3,unit_4,unit_5,unit_6,
                    unit_7,unit_8,unit_9,unit_10,unit_11,unit_12,
                    value_1,value_2,value_3,value_4,value_5,value_6,
                    value_7,value_8,value_9,value_10,value_11,value_12, 
                    ot_1,ot_2,ot_3,ot_4,ot_5,ot_6,
                    ot_7,ot_8,ot_9,ot_10,ot_11,ot_12,
                    ec_1,ec_2,ec_3,ec_4,ec_5,ec_6,
                    ec_7,ec_8,ec_9,ec_10,ec_11,ec_12               
            from   db_temp.t_temp_soprod
            where  id = $id and created_date = (select max(created_date) from db_temp.t_temp_soprod where id = $id)
            ORDER BY urutan asc");   
        
            $this->excel_generator->set_query($hasil);
            $this->excel_generator->set_header(array
            (
                'groupsalur','subgroup','nama_sub_group',
                'unit_1','unit_2','unit_3','unit_4','unit_5','unit_6',
                'unit_7','unit_8','unit_9','unit_10','unit_11','unit_12',
                'value_1','value_2','value_3','value_4','value_5','value_6',
                'value_7','value_8','value_9','value_10','value_11','value_12', 
                'ot_1','ot_2','ot_3','ot_4','ot_5','ot_6',
                'ot_7','ot_8','ot_9','ot_10','ot_11','ot_12',
                'ec_1','ec_2','ec_3','ec_4','ec_5','ec_6',
                'ec_7','ec_8','ec_9','ec_10','ec_11','ec_12'
            ));
            $this->excel_generator->set_column(array
            ( 
                'groupsalur','subgroup','nama_sub_group',
                'unit_1','unit_2','unit_3','unit_4','unit_5','unit_6',
                'unit_7','unit_8','unit_9','unit_10','unit_11','unit_12',
                'value_1','value_2','value_3','value_4','value_5','value_6',
                'value_7','value_8','value_9','value_10','value_11','value_12', 
                'ot_1','ot_2','ot_3','ot_4','ot_5','ot_6',
                'ot_7','ot_8','ot_9','ot_10','ot_11','ot_12',
                'ec_1','ec_2','ec_3','ec_4','ec_5','ec_6',
                'ec_7','ec_8','ec_9','ec_10','ec_11','ec_12'
            ));
            $this->excel_generator->set_width(array(10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10));
            //$this->excel_generator->exportTo2007('Omzet'.'_'.$tahun.'_'.$supplier);   
            $this->excel_generator->exportTo2007('Sell_Out_Product'); 
        }
        //kode type, subgroup
        elseif ($kondisi == '36'){
            $hasil = $this->db->query("
            select  sektor, subgroup, nama_sub_group,
                    unit_1,unit_2,unit_3,unit_4,unit_5,unit_6,
                    unit_7,unit_8,unit_9,unit_10,unit_11,unit_12,
                    value_1,value_2,value_3,value_4,value_5,value_6,
                    value_7,value_8,value_9,value_10,value_11,value_12, 
                    ot_1,ot_2,ot_3,ot_4,ot_5,ot_6,
                    ot_7,ot_8,ot_9,ot_10,ot_11,ot_12,
                    ec_1,ec_2,ec_3,ec_4,ec_5,ec_6,
                    ec_7,ec_8,ec_9,ec_10,ec_11,ec_12               
            from   db_temp.t_temp_soprod
            where  id = $id and created_date = (select max(created_date) from db_temp.t_temp_soprod where id = $id)
            ORDER BY urutan asc");   
        
            $this->excel_generator->set_query($hasil);
            $this->excel_generator->set_header(array
            (
                'sektor','subgroup','nama_sub_group',
                'unit_1','unit_2','unit_3','unit_4','unit_5','unit_6',
                'unit_7','unit_8','unit_9','unit_10','unit_11','unit_12',
                'value_1','value_2','value_3','value_4','value_5','value_6',
                'value_7','value_8','value_9','value_10','value_11','value_12', 
                'ot_1','ot_2','ot_3','ot_4','ot_5','ot_6',
                'ot_7','ot_8','ot_9','ot_10','ot_11','ot_12',
                'ec_1','ec_2','ec_3','ec_4','ec_5','ec_6',
                'ec_7','ec_8','ec_9','ec_10','ec_11','ec_12'
            ));
            $this->excel_generator->set_column(array
            ( 
                'sektor','subgroup','nama_sub_group',
                'unit_1','unit_2','unit_3','unit_4','unit_5','unit_6',
                'unit_7','unit_8','unit_9','unit_10','unit_11','unit_12',
                'value_1','value_2','value_3','value_4','value_5','value_6',
                'value_7','value_8','value_9','value_10','value_11','value_12', 
                'ot_1','ot_2','ot_3','ot_4','ot_5','ot_6',
                'ot_7','ot_8','ot_9','ot_10','ot_11','ot_12',
                'ec_1','ec_2','ec_3','ec_4','ec_5','ec_6',
                'ec_7','ec_8','ec_9','ec_10','ec_11','ec_12'
            ));
            $this->excel_generator->set_width(array(10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10));
            //$this->excel_generator->exportTo2007('Omzet'.'_'.$tahun.'_'.$supplier);   
            $this->excel_generator->exportTo2007('Sell_Out_Product'); 
        }
        //grup, subgroup
        elseif ($kondisi == '37'){
            $hasil = $this->db->query("
            select  `group`,nama_group, subgroup, nama_sub_group,
                    unit_1,unit_2,unit_3,unit_4,unit_5,unit_6,
                    unit_7,unit_8,unit_9,unit_10,unit_11,unit_12,
                    value_1,value_2,value_3,value_4,value_5,value_6,
                    value_7,value_8,value_9,value_10,value_11,value_12, 
                    ot_1,ot_2,ot_3,ot_4,ot_5,ot_6,
                    ot_7,ot_8,ot_9,ot_10,ot_11,ot_12,
                    ec_1,ec_2,ec_3,ec_4,ec_5,ec_6,
                    ec_7,ec_8,ec_9,ec_10,ec_11,ec_12               
            from   db_temp.t_temp_soprod
            where  id = $id and created_date = (select max(created_date) from db_temp.t_temp_soprod where id = $id)
            ORDER BY urutan asc");   
        
            $this->excel_generator->set_query($hasil);
            $this->excel_generator->set_header(array
            (
                'group','nama_group','subgroup','nama_sub_group',
                'unit_1','unit_2','unit_3','unit_4','unit_5','unit_6',
                'unit_7','unit_8','unit_9','unit_10','unit_11','unit_12',
                'value_1','value_2','value_3','value_4','value_5','value_6',
                'value_7','value_8','value_9','value_10','value_11','value_12', 
                'ot_1','ot_2','ot_3','ot_4','ot_5','ot_6',
                'ot_7','ot_8','ot_9','ot_10','ot_11','ot_12',
                'ec_1','ec_2','ec_3','ec_4','ec_5','ec_6',
                'ec_7','ec_8','ec_9','ec_10','ec_11','ec_12'
            ));
            $this->excel_generator->set_column(array
            ( 
                'group','nama_group','subgroup','nama_sub_group',
                'unit_1','unit_2','unit_3','unit_4','unit_5','unit_6',
                'unit_7','unit_8','unit_9','unit_10','unit_11','unit_12',
                'value_1','value_2','value_3','value_4','value_5','value_6',
                'value_7','value_8','value_9','value_10','value_11','value_12', 
                'ot_1','ot_2','ot_3','ot_4','ot_5','ot_6',
                'ot_7','ot_8','ot_9','ot_10','ot_11','ot_12',
                'ec_1','ec_2','ec_3','ec_4','ec_5','ec_6',
                'ec_7','ec_8','ec_9','ec_10','ec_11','ec_12'
            ));
            $this->excel_generator->set_width(array(10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10));
            //$this->excel_generator->exportTo2007('Omzet'.'_'.$tahun.'_'.$supplier);   
            $this->excel_generator->exportTo2007('Sell_Out_Product'); 
        }
        //kode, kodeprod, subgroup
        elseif ($kondisi == '38'){
            $hasil = $this->db->query("
            select  substr(kode,1,3) as kode_comp, kode, branch_name,nama_comp, kodeprod, namaprod, subgroup, nama_sub_group,
                    unit_1,unit_2,unit_3,unit_4,unit_5,unit_6,
                    unit_7,unit_8,unit_9,unit_10,unit_11,unit_12,
                    value_1,value_2,value_3,value_4,value_5,value_6,
                    value_7,value_8,value_9,value_10,value_11,value_12, 
                    ot_1,ot_2,ot_3,ot_4,ot_5,ot_6,
                    ot_7,ot_8,ot_9,ot_10,ot_11,ot_12,
                    ec_1,ec_2,ec_3,ec_4,ec_5,ec_6,
                    ec_7,ec_8,ec_9,ec_10,ec_11,ec_12               
            from   db_temp.t_temp_soprod
            where  id = $id and created_date = (select max(created_date) from db_temp.t_temp_soprod where id = $id)
            ORDER BY urutan asc");   
        
            $this->excel_generator->set_query($hasil);
            $this->excel_generator->set_header(array
            (
                'kode_comp', 'kode', 'branch_name','nama_comp', 'kodeprod','namaprod','subgroup','nama_sub_group',
                'unit_1','unit_2','unit_3','unit_4','unit_5','unit_6',
                'unit_7','unit_8','unit_9','unit_10','unit_11','unit_12',
                'value_1','value_2','value_3','value_4','value_5','value_6',
                'value_7','value_8','value_9','value_10','value_11','value_12', 
                'ot_1','ot_2','ot_3','ot_4','ot_5','ot_6',
                'ot_7','ot_8','ot_9','ot_10','ot_11','ot_12',
                'ec_1','ec_2','ec_3','ec_4','ec_5','ec_6',
                'ec_7','ec_8','ec_9','ec_10','ec_11','ec_12'
            ));
            $this->excel_generator->set_column(array
            ( 
                'kode_comp', 'kode', 'branch_name','nama_comp', 'kodeprod','namaprod','subgroup','nama_sub_group',
                'unit_1','unit_2','unit_3','unit_4','unit_5','unit_6',
                'unit_7','unit_8','unit_9','unit_10','unit_11','unit_12',
                'value_1','value_2','value_3','value_4','value_5','value_6',
                'value_7','value_8','value_9','value_10','value_11','value_12', 
                'ot_1','ot_2','ot_3','ot_4','ot_5','ot_6',
                'ot_7','ot_8','ot_9','ot_10','ot_11','ot_12',
                'ec_1','ec_2','ec_3','ec_4','ec_5','ec_6',
                'ec_7','ec_8','ec_9','ec_10','ec_11','ec_12'
            ));
            $this->excel_generator->set_width(array(10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10));
            //$this->excel_generator->exportTo2007('Omzet'.'_'.$tahun.'_'.$supplier);   
            $this->excel_generator->exportTo2007('Sell_Out_Product'); 
        }
        //kode,kodesalur, subgroup
        elseif ($kondisi == '39'){
            $hasil = $this->db->query("
            select  substr(kode,1,3) as kode_comp, kode, branch_name,nama_comp, groupsalur, subgroup, nama_sub_group,
                    unit_1,unit_2,unit_3,unit_4,unit_5,unit_6,
                    unit_7,unit_8,unit_9,unit_10,unit_11,unit_12,
                    value_1,value_2,value_3,value_4,value_5,value_6,
                    value_7,value_8,value_9,value_10,value_11,value_12, 
                    ot_1,ot_2,ot_3,ot_4,ot_5,ot_6,
                    ot_7,ot_8,ot_9,ot_10,ot_11,ot_12,
                    ec_1,ec_2,ec_3,ec_4,ec_5,ec_6,
                    ec_7,ec_8,ec_9,ec_10,ec_11,ec_12               
            from   db_temp.t_temp_soprod
            where  id = $id and created_date = (select max(created_date) from db_temp.t_temp_soprod where id = $id)
            ORDER BY urutan asc");   
        
            $this->excel_generator->set_query($hasil);
            $this->excel_generator->set_header(array
            (
                'kode_comp', 'kode', 'branch_name','nama_comp','subgroup', 'groupsalur','nama_sub_group',
                'unit_1','unit_2','unit_3','unit_4','unit_5','unit_6',
                'unit_7','unit_8','unit_9','unit_10','unit_11','unit_12',
                'value_1','value_2','value_3','value_4','value_5','value_6',
                'value_7','value_8','value_9','value_10','value_11','value_12', 
                'ot_1','ot_2','ot_3','ot_4','ot_5','ot_6',
                'ot_7','ot_8','ot_9','ot_10','ot_11','ot_12',
                'ec_1','ec_2','ec_3','ec_4','ec_5','ec_6',
                'ec_7','ec_8','ec_9','ec_10','ec_11','ec_12'
            ));
            $this->excel_generator->set_column(array
            ( 
                'kode_comp', 'kode', 'branch_name','nama_comp','subgroup', 'groupsalur','nama_sub_group',
                'unit_1','unit_2','unit_3','unit_4','unit_5','unit_6',
                'unit_7','unit_8','unit_9','unit_10','unit_11','unit_12',
                'value_1','value_2','value_3','value_4','value_5','value_6',
                'value_7','value_8','value_9','value_10','value_11','value_12', 
                'ot_1','ot_2','ot_3','ot_4','ot_5','ot_6',
                'ot_7','ot_8','ot_9','ot_10','ot_11','ot_12',
                'ec_1','ec_2','ec_3','ec_4','ec_5','ec_6',
                'ec_7','ec_8','ec_9','ec_10','ec_11','ec_12'
            ));
            $this->excel_generator->set_width(array(10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10));
            //$this->excel_generator->exportTo2007('Omzet'.'_'.$tahun.'_'.$supplier);   
            $this->excel_generator->exportTo2007('Sell_Out_Product'); 
        }
        //kode,kode_type, subgroup
        elseif ($kondisi == '40'){
            $hasil = $this->db->query("
            select  substr(kode,1,3) as kode_comp, kode, branch_name,nama_comp, sektor, segment, subgroup, nama_sub_group,
                    unit_1,unit_2,unit_3,unit_4,unit_5,unit_6,
                    unit_7,unit_8,unit_9,unit_10,unit_11,unit_12,
                    value_1,value_2,value_3,value_4,value_5,value_6,
                    value_7,value_8,value_9,value_10,value_11,value_12, 
                    ot_1,ot_2,ot_3,ot_4,ot_5,ot_6,
                    ot_7,ot_8,ot_9,ot_10,ot_11,ot_12,
                    ec_1,ec_2,ec_3,ec_4,ec_5,ec_6,
                    ec_7,ec_8,ec_9,ec_10,ec_11,ec_12               
            from   db_temp.t_temp_soprod
            where  id = $id and created_date = (select max(created_date) from db_temp.t_temp_soprod where id = $id)
            ORDER BY urutan asc");   
        
            $this->excel_generator->set_query($hasil);
            $this->excel_generator->set_header(array
            (
                'kode_comp', 'kode', 'branch_name','nama_comp','sektor','segment','subgroup','nama_sub_group',
                'unit_1','unit_2','unit_3','unit_4','unit_5','unit_6',
                'unit_7','unit_8','unit_9','unit_10','unit_11','unit_12',
                'value_1','value_2','value_3','value_4','value_5','value_6',
                'value_7','value_8','value_9','value_10','value_11','value_12', 
                'ot_1','ot_2','ot_3','ot_4','ot_5','ot_6',
                'ot_7','ot_8','ot_9','ot_10','ot_11','ot_12',
                'ec_1','ec_2','ec_3','ec_4','ec_5','ec_6',
                'ec_7','ec_8','ec_9','ec_10','ec_11','ec_12'
            ));
            $this->excel_generator->set_column(array
            ( 
                'kode_comp', 'kode', 'branch_name','nama_comp','sektor','segment','subgroup','nama_sub_group',
                'unit_1','unit_2','unit_3','unit_4','unit_5','unit_6',
                'unit_7','unit_8','unit_9','unit_10','unit_11','unit_12',
                'value_1','value_2','value_3','value_4','value_5','value_6',
                'value_7','value_8','value_9','value_10','value_11','value_12', 
                'ot_1','ot_2','ot_3','ot_4','ot_5','ot_6',
                'ot_7','ot_8','ot_9','ot_10','ot_11','ot_12',
                'ec_1','ec_2','ec_3','ec_4','ec_5','ec_6',
                'ec_7','ec_8','ec_9','ec_10','ec_11','ec_12'
            ));
            $this->excel_generator->set_width(array(10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10));
            //$this->excel_generator->exportTo2007('Omzet'.'_'.$tahun.'_'.$supplier);   
            $this->excel_generator->exportTo2007('Sell_Out_Product'); 
        }
         //kode, grup, subgroup
         elseif ($kondisi == '41'){
            $hasil = $this->db->query("
            select  substr(kode,1,3) as kode_comp, kode, branch_name,nama_comp, `group`, nama_group, subgroup, nama_sub_group,
                    unit_1,unit_2,unit_3,unit_4,unit_5,unit_6,
                    unit_7,unit_8,unit_9,unit_10,unit_11,unit_12,
                    value_1,value_2,value_3,value_4,value_5,value_6,
                    value_7,value_8,value_9,value_10,value_11,value_12, 
                    ot_1,ot_2,ot_3,ot_4,ot_5,ot_6,
                    ot_7,ot_8,ot_9,ot_10,ot_11,ot_12,
                    ec_1,ec_2,ec_3,ec_4,ec_5,ec_6,
                    ec_7,ec_8,ec_9,ec_10,ec_11,ec_12               
            from   db_temp.t_temp_soprod
            where  id = $id and created_date = (select max(created_date) from db_temp.t_temp_soprod where id = $id)
            ORDER BY urutan asc");   
        
            $this->excel_generator->set_query($hasil);
            $this->excel_generator->set_header(array
            (
                'kode_comp', 'kode', 'branch_name','nama_comp','group','nama_group','subgroup','nama_sub_group',
                'unit_1','unit_2','unit_3','unit_4','unit_5','unit_6',
                'unit_7','unit_8','unit_9','unit_10','unit_11','unit_12',
                'value_1','value_2','value_3','value_4','value_5','value_6',
                'value_7','value_8','value_9','value_10','value_11','value_12', 
                'ot_1','ot_2','ot_3','ot_4','ot_5','ot_6',
                'ot_7','ot_8','ot_9','ot_10','ot_11','ot_12',
                'ec_1','ec_2','ec_3','ec_4','ec_5','ec_6',
                'ec_7','ec_8','ec_9','ec_10','ec_11','ec_12'
            ));
            $this->excel_generator->set_column(array
            ( 
                'kode_comp', 'kode', 'branch_name','nama_comp','group','nama_group','subgroup','nama_sub_group',
                'unit_1','unit_2','unit_3','unit_4','unit_5','unit_6',
                'unit_7','unit_8','unit_9','unit_10','unit_11','unit_12',
                'value_1','value_2','value_3','value_4','value_5','value_6',
                'value_7','value_8','value_9','value_10','value_11','value_12', 
                'ot_1','ot_2','ot_3','ot_4','ot_5','ot_6',
                'ot_7','ot_8','ot_9','ot_10','ot_11','ot_12',
                'ec_1','ec_2','ec_3','ec_4','ec_5','ec_6',
                'ec_7','ec_8','ec_9','ec_10','ec_11','ec_12'
            ));
            $this->excel_generator->set_width(array(10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10));
            //$this->excel_generator->exportTo2007('Omzet'.'_'.$tahun.'_'.$supplier);   
            $this->excel_generator->exportTo2007('Sell_Out_Product'); 
        }
        //kodeprod, kodesalur, subgroup
        elseif ($kondisi == '42'){
            $hasil = $this->db->query("
            select  kodeprod,namaprod, groupsalur, subgroup, nama_sub_group,
                    unit_1,unit_2,unit_3,unit_4,unit_5,unit_6,
                    unit_7,unit_8,unit_9,unit_10,unit_11,unit_12,
                    value_1,value_2,value_3,value_4,value_5,value_6,
                    value_7,value_8,value_9,value_10,value_11,value_12, 
                    ot_1,ot_2,ot_3,ot_4,ot_5,ot_6,
                    ot_7,ot_8,ot_9,ot_10,ot_11,ot_12,
                    ec_1,ec_2,ec_3,ec_4,ec_5,ec_6,
                    ec_7,ec_8,ec_9,ec_10,ec_11,ec_12               
            from   db_temp.t_temp_soprod
            where  id = $id and created_date = (select max(created_date) from db_temp.t_temp_soprod where id = $id)
            ORDER BY urutan asc");   
        
            $this->excel_generator->set_query($hasil);
            $this->excel_generator->set_header(array
            (
                'kodeprod', 'namaprod','groupsalur','subgroup','nama_sub_group',
                'unit_1','unit_2','unit_3','unit_4','unit_5','unit_6',
                'unit_7','unit_8','unit_9','unit_10','unit_11','unit_12',
                'value_1','value_2','value_3','value_4','value_5','value_6',
                'value_7','value_8','value_9','value_10','value_11','value_12', 
                'ot_1','ot_2','ot_3','ot_4','ot_5','ot_6',
                'ot_7','ot_8','ot_9','ot_10','ot_11','ot_12',
                'ec_1','ec_2','ec_3','ec_4','ec_5','ec_6',
                'ec_7','ec_8','ec_9','ec_10','ec_11','ec_12'
            ));
            $this->excel_generator->set_column(array
            ( 
                'kodeprod', 'namaprod','groupsalur','subgroup','nama_sub_group',
                'unit_1','unit_2','unit_3','unit_4','unit_5','unit_6',
                'unit_7','unit_8','unit_9','unit_10','unit_11','unit_12',
                'value_1','value_2','value_3','value_4','value_5','value_6',
                'value_7','value_8','value_9','value_10','value_11','value_12', 
                'ot_1','ot_2','ot_3','ot_4','ot_5','ot_6',
                'ot_7','ot_8','ot_9','ot_10','ot_11','ot_12',
                'ec_1','ec_2','ec_3','ec_4','ec_5','ec_6',
                'ec_7','ec_8','ec_9','ec_10','ec_11','ec_12'
            ));
            $this->excel_generator->set_width(array(10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10));
            //$this->excel_generator->exportTo2007('Omzet'.'_'.$tahun.'_'.$supplier);   
            $this->excel_generator->exportTo2007('Sell_Out_Product'); 
        }
        //kodeprod, kode_type, subgroup
        elseif ($kondisi == '43'){
            $hasil = $this->db->query("
            select  kodeprod,namaprod, sektor, segment, subgroup, nama_sub_group,
                    unit_1,unit_2,unit_3,unit_4,unit_5,unit_6,
                    unit_7,unit_8,unit_9,unit_10,unit_11,unit_12,
                    value_1,value_2,value_3,value_4,value_5,value_6,
                    value_7,value_8,value_9,value_10,value_11,value_12, 
                    ot_1,ot_2,ot_3,ot_4,ot_5,ot_6,
                    ot_7,ot_8,ot_9,ot_10,ot_11,ot_12,
                    ec_1,ec_2,ec_3,ec_4,ec_5,ec_6,
                    ec_7,ec_8,ec_9,ec_10,ec_11,ec_12               
            from   db_temp.t_temp_soprod
            where  id = $id and created_date = (select max(created_date) from db_temp.t_temp_soprod where id = $id)
            ORDER BY urutan asc");   
        
            $this->excel_generator->set_query($hasil);
            $this->excel_generator->set_header(array
            (
                'kodeprod', 'namaprod','sektor','segment','subgroup','nama_sub_group',
                'unit_1','unit_2','unit_3','unit_4','unit_5','unit_6',
                'unit_7','unit_8','unit_9','unit_10','unit_11','unit_12',
                'value_1','value_2','value_3','value_4','value_5','value_6',
                'value_7','value_8','value_9','value_10','value_11','value_12', 
                'ot_1','ot_2','ot_3','ot_4','ot_5','ot_6',
                'ot_7','ot_8','ot_9','ot_10','ot_11','ot_12',
                'ec_1','ec_2','ec_3','ec_4','ec_5','ec_6',
                'ec_7','ec_8','ec_9','ec_10','ec_11','ec_12'
            ));
            $this->excel_generator->set_column(array
            ( 
                'kodeprod', 'namaprod','sektor','segment','subgroup','nama_sub_group',
                'unit_1','unit_2','unit_3','unit_4','unit_5','unit_6',
                'unit_7','unit_8','unit_9','unit_10','unit_11','unit_12',
                'value_1','value_2','value_3','value_4','value_5','value_6',
                'value_7','value_8','value_9','value_10','value_11','value_12', 
                'ot_1','ot_2','ot_3','ot_4','ot_5','ot_6',
                'ot_7','ot_8','ot_9','ot_10','ot_11','ot_12',
                'ec_1','ec_2','ec_3','ec_4','ec_5','ec_6',
                'ec_7','ec_8','ec_9','ec_10','ec_11','ec_12'
            ));
            $this->excel_generator->set_width(array(10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10));
            //$this->excel_generator->exportTo2007('Omzet'.'_'.$tahun.'_'.$supplier);   
            $this->excel_generator->exportTo2007('Sell_Out_Product'); 
        }
        //kodeprod, grup, subgroup
        elseif ($kondisi == '44'){
            $hasil = $this->db->query("
            select  kodeprod,namaprod, `group`, nama_group,subgroup, nama_sub_group,
                    unit_1,unit_2,unit_3,unit_4,unit_5,unit_6,
                    unit_7,unit_8,unit_9,unit_10,unit_11,unit_12,
                    value_1,value_2,value_3,value_4,value_5,value_6,
                    value_7,value_8,value_9,value_10,value_11,value_12, 
                    ot_1,ot_2,ot_3,ot_4,ot_5,ot_6,
                    ot_7,ot_8,ot_9,ot_10,ot_11,ot_12,
                    ec_1,ec_2,ec_3,ec_4,ec_5,ec_6,
                    ec_7,ec_8,ec_9,ec_10,ec_11,ec_12               
            from   db_temp.t_temp_soprod
            where  id = $id and created_date = (select max(created_date) from db_temp.t_temp_soprod where id = $id)
            ORDER BY urutan asc");   
        
            $this->excel_generator->set_query($hasil);
            $this->excel_generator->set_header(array
            (
                'kodeprod', 'namaprod','group', 'nama_group','subgroup','nama_sub_group',
                'unit_1','unit_2','unit_3','unit_4','unit_5','unit_6',
                'unit_7','unit_8','unit_9','unit_10','unit_11','unit_12',
                'value_1','value_2','value_3','value_4','value_5','value_6',
                'value_7','value_8','value_9','value_10','value_11','value_12', 
                'ot_1','ot_2','ot_3','ot_4','ot_5','ot_6',
                'ot_7','ot_8','ot_9','ot_10','ot_11','ot_12',
                'ec_1','ec_2','ec_3','ec_4','ec_5','ec_6',
                'ec_7','ec_8','ec_9','ec_10','ec_11','ec_12'
            ));
            $this->excel_generator->set_column(array
            ( 
                'kodeprod', 'namaprod','group', 'nama_group','subgroup','nama_sub_group',
                'unit_1','unit_2','unit_3','unit_4','unit_5','unit_6',
                'unit_7','unit_8','unit_9','unit_10','unit_11','unit_12',
                'value_1','value_2','value_3','value_4','value_5','value_6',
                'value_7','value_8','value_9','value_10','value_11','value_12', 
                'ot_1','ot_2','ot_3','ot_4','ot_5','ot_6',
                'ot_7','ot_8','ot_9','ot_10','ot_11','ot_12',
                'ec_1','ec_2','ec_3','ec_4','ec_5','ec_6',
                'ec_7','ec_8','ec_9','ec_10','ec_11','ec_12'
            ));
            $this->excel_generator->set_width(array(10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10));
            //$this->excel_generator->exportTo2007('Omzet'.'_'.$tahun.'_'.$supplier);   
            $this->excel_generator->exportTo2007('Sell_Out_Product'); 
        }
        //kodesalur,kode_type,subgroup
        elseif ($kondisi == '45'){
            $hasil = $this->db->query("
            select  groupsalur,sektor, segment, subgroup, nama_sub_group,
                    unit_1,unit_2,unit_3,unit_4,unit_5,unit_6,
                    unit_7,unit_8,unit_9,unit_10,unit_11,unit_12,
                    value_1,value_2,value_3,value_4,value_5,value_6,
                    value_7,value_8,value_9,value_10,value_11,value_12, 
                    ot_1,ot_2,ot_3,ot_4,ot_5,ot_6,
                    ot_7,ot_8,ot_9,ot_10,ot_11,ot_12,
                    ec_1,ec_2,ec_3,ec_4,ec_5,ec_6,
                    ec_7,ec_8,ec_9,ec_10,ec_11,ec_12               
            from   db_temp.t_temp_soprod
            where  id = $id and created_date = (select max(created_date) from db_temp.t_temp_soprod where id = $id)
            ORDER BY urutan asc");   
        
            $this->excel_generator->set_query($hasil);
            $this->excel_generator->set_header(array
            (
                'groupsalur','sektor','segment','subgroup','nama_sub_group',
                'unit_1','unit_2','unit_3','unit_4','unit_5','unit_6',
                'unit_7','unit_8','unit_9','unit_10','unit_11','unit_12',
                'value_1','value_2','value_3','value_4','value_5','value_6',
                'value_7','value_8','value_9','value_10','value_11','value_12', 
                'ot_1','ot_2','ot_3','ot_4','ot_5','ot_6',
                'ot_7','ot_8','ot_9','ot_10','ot_11','ot_12',
                'ec_1','ec_2','ec_3','ec_4','ec_5','ec_6',
                'ec_7','ec_8','ec_9','ec_10','ec_11','ec_12'
            ));
            $this->excel_generator->set_column(array
            ( 
                'groupsalur','sektor','segment','subgroup','nama_sub_group',
                'unit_1','unit_2','unit_3','unit_4','unit_5','unit_6',
                'unit_7','unit_8','unit_9','unit_10','unit_11','unit_12',
                'value_1','value_2','value_3','value_4','value_5','value_6',
                'value_7','value_8','value_9','value_10','value_11','value_12', 
                'ot_1','ot_2','ot_3','ot_4','ot_5','ot_6',
                'ot_7','ot_8','ot_9','ot_10','ot_11','ot_12',
                'ec_1','ec_2','ec_3','ec_4','ec_5','ec_6',
                'ec_7','ec_8','ec_9','ec_10','ec_11','ec_12'
            ));
            $this->excel_generator->set_width(array(10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10));
            //$this->excel_generator->exportTo2007('Omzet'.'_'.$tahun.'_'.$supplier);   
            $this->excel_generator->exportTo2007('Sell_Out_Product'); 
        }
        //kodesalur, group, subgroup
        elseif ($kondisi == '46'){
            $hasil = $this->db->query("
            select  groupsalur,`group`,nama_group, subgroup, nama_sub_group,
                    unit_1,unit_2,unit_3,unit_4,unit_5,unit_6,
                    unit_7,unit_8,unit_9,unit_10,unit_11,unit_12,
                    value_1,value_2,value_3,value_4,value_5,value_6,
                    value_7,value_8,value_9,value_10,value_11,value_12, 
                    ot_1,ot_2,ot_3,ot_4,ot_5,ot_6,
                    ot_7,ot_8,ot_9,ot_10,ot_11,ot_12,
                    ec_1,ec_2,ec_3,ec_4,ec_5,ec_6,
                    ec_7,ec_8,ec_9,ec_10,ec_11,ec_12               
            from   db_temp.t_temp_soprod
            where  id = $id and created_date = (select max(created_date) from db_temp.t_temp_soprod where id = $id)
            ORDER BY urutan asc");   
        
            $this->excel_generator->set_query($hasil);
            $this->excel_generator->set_header(array
            (
                'groupsalur','group','nama_group','subgroup','nama_sub_group',
                'unit_1','unit_2','unit_3','unit_4','unit_5','unit_6',
                'unit_7','unit_8','unit_9','unit_10','unit_11','unit_12',
                'value_1','value_2','value_3','value_4','value_5','value_6',
                'value_7','value_8','value_9','value_10','value_11','value_12', 
                'ot_1','ot_2','ot_3','ot_4','ot_5','ot_6',
                'ot_7','ot_8','ot_9','ot_10','ot_11','ot_12',
                'ec_1','ec_2','ec_3','ec_4','ec_5','ec_6',
                'ec_7','ec_8','ec_9','ec_10','ec_11','ec_12'
            ));
            $this->excel_generator->set_column(array
            ( 
                'groupsalur','group','nama_group','subgroup','nama_sub_group',
                'unit_1','unit_2','unit_3','unit_4','unit_5','unit_6',
                'unit_7','unit_8','unit_9','unit_10','unit_11','unit_12',
                'value_1','value_2','value_3','value_4','value_5','value_6',
                'value_7','value_8','value_9','value_10','value_11','value_12', 
                'ot_1','ot_2','ot_3','ot_4','ot_5','ot_6',
                'ot_7','ot_8','ot_9','ot_10','ot_11','ot_12',
                'ec_1','ec_2','ec_3','ec_4','ec_5','ec_6',
                'ec_7','ec_8','ec_9','ec_10','ec_11','ec_12'
            ));
            $this->excel_generator->set_width(array(10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10));
            //$this->excel_generator->exportTo2007('Omzet'.'_'.$tahun.'_'.$supplier);   
            $this->excel_generator->exportTo2007('Sell_Out_Product'); 
        }
        //kode type, grup, subgroup
        elseif ($kondisi == '47'){
            $hasil = $this->db->query("
            select  sektor,`group`,nama_group, subgroup, nama_sub_group,
                    unit_1,unit_2,unit_3,unit_4,unit_5,unit_6,
                    unit_7,unit_8,unit_9,unit_10,unit_11,unit_12,
                    value_1,value_2,value_3,value_4,value_5,value_6,
                    value_7,value_8,value_9,value_10,value_11,value_12, 
                    ot_1,ot_2,ot_3,ot_4,ot_5,ot_6,
                    ot_7,ot_8,ot_9,ot_10,ot_11,ot_12,
                    ec_1,ec_2,ec_3,ec_4,ec_5,ec_6,
                    ec_7,ec_8,ec_9,ec_10,ec_11,ec_12               
            from   db_temp.t_temp_soprod
            where  id = $id and created_date = (select max(created_date) from db_temp.t_temp_soprod where id = $id)
            ORDER BY urutan asc");   
        
            $this->excel_generator->set_query($hasil);
            $this->excel_generator->set_header(array
            (
                'sektor','group','nama_group','subgroup','nama_sub_group',
                'unit_1','unit_2','unit_3','unit_4','unit_5','unit_6',
                'unit_7','unit_8','unit_9','unit_10','unit_11','unit_12',
                'value_1','value_2','value_3','value_4','value_5','value_6',
                'value_7','value_8','value_9','value_10','value_11','value_12', 
                'ot_1','ot_2','ot_3','ot_4','ot_5','ot_6',
                'ot_7','ot_8','ot_9','ot_10','ot_11','ot_12',
                'ec_1','ec_2','ec_3','ec_4','ec_5','ec_6',
                'ec_7','ec_8','ec_9','ec_10','ec_11','ec_12'
            ));
            $this->excel_generator->set_column(array
            ( 
                'sektor','group','nama_group','subgroup','nama_sub_group',
                'unit_1','unit_2','unit_3','unit_4','unit_5','unit_6',
                'unit_7','unit_8','unit_9','unit_10','unit_11','unit_12',
                'value_1','value_2','value_3','value_4','value_5','value_6',
                'value_7','value_8','value_9','value_10','value_11','value_12', 
                'ot_1','ot_2','ot_3','ot_4','ot_5','ot_6',
                'ot_7','ot_8','ot_9','ot_10','ot_11','ot_12',
                'ec_1','ec_2','ec_3','ec_4','ec_5','ec_6',
                'ec_7','ec_8','ec_9','ec_10','ec_11','ec_12'
            ));
            $this->excel_generator->set_width(array(10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10));
            //$this->excel_generator->exportTo2007('Omzet'.'_'.$tahun.'_'.$supplier);   
            $this->excel_generator->exportTo2007('Sell_Out_Product'); 
        }

        //kode, kodeprod, kodesalur, subgroup
        elseif ($kondisi == '48'){
            $hasil = $this->db->query("
            select  substr(kode,1,3) as kode_comp, kode, branch_name,nama_comp, kodeprod, namaprod, groupsalur, subgroup, nama_sub_group,
                    unit_1,unit_2,unit_3,unit_4,unit_5,unit_6,
                    unit_7,unit_8,unit_9,unit_10,unit_11,unit_12,
                    value_1,value_2,value_3,value_4,value_5,value_6,
                    value_7,value_8,value_9,value_10,value_11,value_12, 
                    ot_1,ot_2,ot_3,ot_4,ot_5,ot_6,
                    ot_7,ot_8,ot_9,ot_10,ot_11,ot_12,
                    ec_1,ec_2,ec_3,ec_4,ec_5,ec_6,
                    ec_7,ec_8,ec_9,ec_10,ec_11,ec_12               
            from   db_temp.t_temp_soprod
            where  id = $id and created_date = (select max(created_date) from db_temp.t_temp_soprod where id = $id)
            ORDER BY urutan asc");   
        
            $this->excel_generator->set_query($hasil);
            $this->excel_generator->set_header(array
            (
                'kode_comp', 'kode', 'branch_name','nama_comp', 'kodeprod','namaprod','groupsalur','subgroup','nama_sub_group',
                'unit_1','unit_2','unit_3','unit_4','unit_5','unit_6',
                'unit_7','unit_8','unit_9','unit_10','unit_11','unit_12',
                'value_1','value_2','value_3','value_4','value_5','value_6',
                'value_7','value_8','value_9','value_10','value_11','value_12', 
                'ot_1','ot_2','ot_3','ot_4','ot_5','ot_6',
                'ot_7','ot_8','ot_9','ot_10','ot_11','ot_12',
                'ec_1','ec_2','ec_3','ec_4','ec_5','ec_6',
                'ec_7','ec_8','ec_9','ec_10','ec_11','ec_12'
            ));
            $this->excel_generator->set_column(array
            ( 
                'kode_comp', 'kode', 'branch_name','nama_comp', 'kodeprod','namaprod','groupsalur','subgroup','nama_sub_group',
                'unit_1','unit_2','unit_3','unit_4','unit_5','unit_6',
                'unit_7','unit_8','unit_9','unit_10','unit_11','unit_12',
                'value_1','value_2','value_3','value_4','value_5','value_6',
                'value_7','value_8','value_9','value_10','value_11','value_12', 
                'ot_1','ot_2','ot_3','ot_4','ot_5','ot_6',
                'ot_7','ot_8','ot_9','ot_10','ot_11','ot_12',
                'ec_1','ec_2','ec_3','ec_4','ec_5','ec_6',
                'ec_7','ec_8','ec_9','ec_10','ec_11','ec_12'
            ));
            $this->excel_generator->set_width(array(10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10));
            //$this->excel_generator->exportTo2007('Omzet'.'_'.$tahun.'_'.$supplier);   
            $this->excel_generator->exportTo2007('Sell_Out_Product'); 
        }

        //kode, kodeprod, kode_type, subgroup
        elseif ($kondisi == '49'){
            $hasil = $this->db->query("
            select  substr(kode,1,3) as kode_comp, kode, branch_name,nama_comp, kodeprod, namaprod, sektor, segment, subgroup, nama_sub_group,
                    unit_1,unit_2,unit_3,unit_4,unit_5,unit_6,
                    unit_7,unit_8,unit_9,unit_10,unit_11,unit_12,
                    value_1,value_2,value_3,value_4,value_5,value_6,
                    value_7,value_8,value_9,value_10,value_11,value_12, 
                    ot_1,ot_2,ot_3,ot_4,ot_5,ot_6,
                    ot_7,ot_8,ot_9,ot_10,ot_11,ot_12,
                    ec_1,ec_2,ec_3,ec_4,ec_5,ec_6,
                    ec_7,ec_8,ec_9,ec_10,ec_11,ec_12               
            from   db_temp.t_temp_soprod
            where  id = $id and created_date = (select max(created_date) from db_temp.t_temp_soprod where id = $id)
            ORDER BY urutan asc");   
        
            $this->excel_generator->set_query($hasil);
            $this->excel_generator->set_header(array
            (
                'kode_comp', 'kode', 'branch_name','nama_comp', 'kodeprod','namaprod','sektor','segment','subgroup','nama_sub_group',
                'unit_1','unit_2','unit_3','unit_4','unit_5','unit_6',
                'unit_7','unit_8','unit_9','unit_10','unit_11','unit_12',
                'value_1','value_2','value_3','value_4','value_5','value_6',
                'value_7','value_8','value_9','value_10','value_11','value_12', 
                'ot_1','ot_2','ot_3','ot_4','ot_5','ot_6',
                'ot_7','ot_8','ot_9','ot_10','ot_11','ot_12',
                'ec_1','ec_2','ec_3','ec_4','ec_5','ec_6',
                'ec_7','ec_8','ec_9','ec_10','ec_11','ec_12'
            ));
            $this->excel_generator->set_column(array
            ( 
                'kode_comp', 'kode', 'branch_name','nama_comp', 'kodeprod','namaprod','sektor','segment','subgroup','nama_sub_group',
                'unit_1','unit_2','unit_3','unit_4','unit_5','unit_6',
                'unit_7','unit_8','unit_9','unit_10','unit_11','unit_12',
                'value_1','value_2','value_3','value_4','value_5','value_6',
                'value_7','value_8','value_9','value_10','value_11','value_12', 
                'ot_1','ot_2','ot_3','ot_4','ot_5','ot_6',
                'ot_7','ot_8','ot_9','ot_10','ot_11','ot_12',
                'ec_1','ec_2','ec_3','ec_4','ec_5','ec_6',
                'ec_7','ec_8','ec_9','ec_10','ec_11','ec_12'
            ));
            $this->excel_generator->set_width(array(10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10));
            //$this->excel_generator->exportTo2007('Omzet'.'_'.$tahun.'_'.$supplier);   
            $this->excel_generator->exportTo2007('Sell_Out_Product'); 
        }

        //kode, kodeprod, group, subgroup
        elseif ($kondisi == '50'){
            $hasil = $this->db->query("
            select  substr(kode,1,3) as kode_comp, kode, branch_name,nama_comp, kodeprod, namaprod, `group`, nama_group, subgroup, nama_sub_group,
                    unit_1,unit_2,unit_3,unit_4,unit_5,unit_6,
                    unit_7,unit_8,unit_9,unit_10,unit_11,unit_12,
                    value_1,value_2,value_3,value_4,value_5,value_6,
                    value_7,value_8,value_9,value_10,value_11,value_12, 
                    ot_1,ot_2,ot_3,ot_4,ot_5,ot_6,
                    ot_7,ot_8,ot_9,ot_10,ot_11,ot_12,
                    ec_1,ec_2,ec_3,ec_4,ec_5,ec_6,
                    ec_7,ec_8,ec_9,ec_10,ec_11,ec_12               
            from   db_temp.t_temp_soprod
            where  id = $id and created_date = (select max(created_date) from db_temp.t_temp_soprod where id = $id)
            ORDER BY urutan asc");   
        
            $this->excel_generator->set_query($hasil);
            $this->excel_generator->set_header(array
            (
                'kode_comp', 'kode', 'branch_name','nama_comp', 'kodeprod','namaprod','group','nama_sub_group','subgroup','nama_sub_group',
                'unit_1','unit_2','unit_3','unit_4','unit_5','unit_6',
                'unit_7','unit_8','unit_9','unit_10','unit_11','unit_12',
                'value_1','value_2','value_3','value_4','value_5','value_6',
                'value_7','value_8','value_9','value_10','value_11','value_12', 
                'ot_1','ot_2','ot_3','ot_4','ot_5','ot_6',
                'ot_7','ot_8','ot_9','ot_10','ot_11','ot_12',
                'ec_1','ec_2','ec_3','ec_4','ec_5','ec_6',
                'ec_7','ec_8','ec_9','ec_10','ec_11','ec_12'
            ));
            $this->excel_generator->set_column(array
            ( 
                'kode_comp', 'kode', 'branch_name','nama_comp', 'kodeprod','namaprod','group','nama_group','subgroup','nama_sub_group',
                'unit_1','unit_2','unit_3','unit_4','unit_5','unit_6',
                'unit_7','unit_8','unit_9','unit_10','unit_11','unit_12',
                'value_1','value_2','value_3','value_4','value_5','value_6',
                'value_7','value_8','value_9','value_10','value_11','value_12', 
                'ot_1','ot_2','ot_3','ot_4','ot_5','ot_6',
                'ot_7','ot_8','ot_9','ot_10','ot_11','ot_12',
                'ec_1','ec_2','ec_3','ec_4','ec_5','ec_6',
                'ec_7','ec_8','ec_9','ec_10','ec_11','ec_12'
            ));
            $this->excel_generator->set_width(array(10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10));
            //$this->excel_generator->exportTo2007('Omzet'.'_'.$tahun.'_'.$supplier);   
            $this->excel_generator->exportTo2007('Sell_Out_Product'); 
        }

        //kode, kodesalur, kode_type, subgroup
        elseif ($kondisi == '51'){
            $hasil = $this->db->query("
            select  substr(kode,1,3) as kode_comp, kode, branch_name,nama_comp, groupsalur, sektor, segment,subgroup, nama_sub_group,
                    unit_1,unit_2,unit_3,unit_4,unit_5,unit_6,
                    unit_7,unit_8,unit_9,unit_10,unit_11,unit_12,
                    value_1,value_2,value_3,value_4,value_5,value_6,
                    value_7,value_8,value_9,value_10,value_11,value_12, 
                    ot_1,ot_2,ot_3,ot_4,ot_5,ot_6,
                    ot_7,ot_8,ot_9,ot_10,ot_11,ot_12,
                    ec_1,ec_2,ec_3,ec_4,ec_5,ec_6,
                    ec_7,ec_8,ec_9,ec_10,ec_11,ec_12               
            from   db_temp.t_temp_soprod
            where  id = $id and created_date = (select max(created_date) from db_temp.t_temp_soprod where id = $id)
            ORDER BY urutan asc");   
        
            $this->excel_generator->set_query($hasil);
            $this->excel_generator->set_header(array
            (
                'kode_comp', 'kode', 'branch_name','nama_comp', 'groupsalur','sektor','segment','subgroup','nama_sub_group',
                'unit_1','unit_2','unit_3','unit_4','unit_5','unit_6',
                'unit_7','unit_8','unit_9','unit_10','unit_11','unit_12',
                'value_1','value_2','value_3','value_4','value_5','value_6',
                'value_7','value_8','value_9','value_10','value_11','value_12', 
                'ot_1','ot_2','ot_3','ot_4','ot_5','ot_6',
                'ot_7','ot_8','ot_9','ot_10','ot_11','ot_12',
                'ec_1','ec_2','ec_3','ec_4','ec_5','ec_6',
                'ec_7','ec_8','ec_9','ec_10','ec_11','ec_12'
            ));
            $this->excel_generator->set_column(array
            ( 
                'kode_comp', 'kode', 'branch_name','nama_comp', 'groupsalur','sektor','segment','subgroup','nama_sub_group',
                'unit_1','unit_2','unit_3','unit_4','unit_5','unit_6',
                'unit_7','unit_8','unit_9','unit_10','unit_11','unit_12',
                'value_1','value_2','value_3','value_4','value_5','value_6',
                'value_7','value_8','value_9','value_10','value_11','value_12', 
                'ot_1','ot_2','ot_3','ot_4','ot_5','ot_6',
                'ot_7','ot_8','ot_9','ot_10','ot_11','ot_12',
                'ec_1','ec_2','ec_3','ec_4','ec_5','ec_6',
                'ec_7','ec_8','ec_9','ec_10','ec_11','ec_12'
            ));
            $this->excel_generator->set_width(array(10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10));
            //$this->excel_generator->exportTo2007('Omzet'.'_'.$tahun.'_'.$supplier);   
            $this->excel_generator->exportTo2007('Sell_Out_Product'); 
        }

        //kode, kodesalur, group, subgroup
        elseif ($kondisi == '52'){
            $hasil = $this->db->query("
            select  substr(kode,1,3) as kode_comp, kode, branch_name,nama_comp, groupsalur, `group`, nama_group, subgroup, nama_sub_group,
                    unit_1,unit_2,unit_3,unit_4,unit_5,unit_6,
                    unit_7,unit_8,unit_9,unit_10,unit_11,unit_12,
                    value_1,value_2,value_3,value_4,value_5,value_6,
                    value_7,value_8,value_9,value_10,value_11,value_12, 
                    ot_1,ot_2,ot_3,ot_4,ot_5,ot_6,
                    ot_7,ot_8,ot_9,ot_10,ot_11,ot_12,
                    ec_1,ec_2,ec_3,ec_4,ec_5,ec_6,
                    ec_7,ec_8,ec_9,ec_10,ec_11,ec_12               
            from   db_temp.t_temp_soprod
            where  id = $id and created_date = (select max(created_date) from db_temp.t_temp_soprod where id = $id)
            ORDER BY urutan asc");   
        
            $this->excel_generator->set_query($hasil);
            $this->excel_generator->set_header(array
            (
                'kode_comp', 'kode', 'branch_name','nama_comp', 'groupsalur','group','nama_group','subgroup','nama_sub_group',
                'unit_1','unit_2','unit_3','unit_4','unit_5','unit_6',
                'unit_7','unit_8','unit_9','unit_10','unit_11','unit_12',
                'value_1','value_2','value_3','value_4','value_5','value_6',
                'value_7','value_8','value_9','value_10','value_11','value_12', 
                'ot_1','ot_2','ot_3','ot_4','ot_5','ot_6',
                'ot_7','ot_8','ot_9','ot_10','ot_11','ot_12',
                'ec_1','ec_2','ec_3','ec_4','ec_5','ec_6',
                'ec_7','ec_8','ec_9','ec_10','ec_11','ec_12'
            ));
            $this->excel_generator->set_column(array
            ( 
                'kode_comp', 'kode', 'branch_name','nama_comp', 'groupsalur','group','nama_group','subgroup','nama_sub_group',
                'unit_1','unit_2','unit_3','unit_4','unit_5','unit_6',
                'unit_7','unit_8','unit_9','unit_10','unit_11','unit_12',
                'value_1','value_2','value_3','value_4','value_5','value_6',
                'value_7','value_8','value_9','value_10','value_11','value_12', 
                'ot_1','ot_2','ot_3','ot_4','ot_5','ot_6',
                'ot_7','ot_8','ot_9','ot_10','ot_11','ot_12',
                'ec_1','ec_2','ec_3','ec_4','ec_5','ec_6',
                'ec_7','ec_8','ec_9','ec_10','ec_11','ec_12'
            ));
            $this->excel_generator->set_width(array(10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10));
            //$this->excel_generator->exportTo2007('Omzet'.'_'.$tahun.'_'.$supplier);   
            $this->excel_generator->exportTo2007('Sell_Out_Product'); 
        }

         //kode, kode_type, group, subgroup
         elseif ($kondisi == '53'){
            $hasil = $this->db->query("
            select  substr(kode,1,3) as kode_comp, kode, branch_name,nama_comp, sektor, segment, `group`, nama_group, subgroup, nama_sub_group,
                    unit_1,unit_2,unit_3,unit_4,unit_5,unit_6,
                    unit_7,unit_8,unit_9,unit_10,unit_11,unit_12,
                    value_1,value_2,value_3,value_4,value_5,value_6,
                    value_7,value_8,value_9,value_10,value_11,value_12, 
                    ot_1,ot_2,ot_3,ot_4,ot_5,ot_6,
                    ot_7,ot_8,ot_9,ot_10,ot_11,ot_12,
                    ec_1,ec_2,ec_3,ec_4,ec_5,ec_6,
                    ec_7,ec_8,ec_9,ec_10,ec_11,ec_12               
            from   db_temp.t_temp_soprod
            where  id = $id and created_date = (select max(created_date) from db_temp.t_temp_soprod where id = $id)
            ORDER BY urutan asc");   
        
            $this->excel_generator->set_query($hasil);
            $this->excel_generator->set_header(array
            (
                'kode_comp', 'kode', 'branch_name','nama_comp', 'sektor','segment','group','nama_group','subgroup','nama_sub_group',
                'unit_1','unit_2','unit_3','unit_4','unit_5','unit_6',
                'unit_7','unit_8','unit_9','unit_10','unit_11','unit_12',
                'value_1','value_2','value_3','value_4','value_5','value_6',
                'value_7','value_8','value_9','value_10','value_11','value_12', 
                'ot_1','ot_2','ot_3','ot_4','ot_5','ot_6',
                'ot_7','ot_8','ot_9','ot_10','ot_11','ot_12',
                'ec_1','ec_2','ec_3','ec_4','ec_5','ec_6',
                'ec_7','ec_8','ec_9','ec_10','ec_11','ec_12'
            ));
            $this->excel_generator->set_column(array
            ( 
                'kode_comp', 'kode', 'branch_name','nama_comp', 'sektor','segment','group','nama_group','subgroup','nama_sub_group',
                'unit_1','unit_2','unit_3','unit_4','unit_5','unit_6',
                'unit_7','unit_8','unit_9','unit_10','unit_11','unit_12',
                'value_1','value_2','value_3','value_4','value_5','value_6',
                'value_7','value_8','value_9','value_10','value_11','value_12', 
                'ot_1','ot_2','ot_3','ot_4','ot_5','ot_6',
                'ot_7','ot_8','ot_9','ot_10','ot_11','ot_12',
                'ec_1','ec_2','ec_3','ec_4','ec_5','ec_6',
                'ec_7','ec_8','ec_9','ec_10','ec_11','ec_12'
            ));
            $this->excel_generator->set_width(array(10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10));
            //$this->excel_generator->exportTo2007('Omzet'.'_'.$tahun.'_'.$supplier);   
            $this->excel_generator->exportTo2007('Sell_Out_Product'); 
        }

        //kodeprod, kodesalur, kode_type, subgroup
        elseif ($kondisi == '54'){
            $hasil = $this->db->query("
            select  kodeprod,namaprod, groupsalur, sektor, segment, subgroup, nama_sub_group,
                    unit_1,unit_2,unit_3,unit_4,unit_5,unit_6,
                    unit_7,unit_8,unit_9,unit_10,unit_11,unit_12,
                    value_1,value_2,value_3,value_4,value_5,value_6,
                    value_7,value_8,value_9,value_10,value_11,value_12, 
                    ot_1,ot_2,ot_3,ot_4,ot_5,ot_6,
                    ot_7,ot_8,ot_9,ot_10,ot_11,ot_12,
                    ec_1,ec_2,ec_3,ec_4,ec_5,ec_6,
                    ec_7,ec_8,ec_9,ec_10,ec_11,ec_12               
            from   db_temp.t_temp_soprod
            where  id = $id and created_date = (select max(created_date) from db_temp.t_temp_soprod where id = $id)
            ORDER BY urutan asc");   
        
            $this->excel_generator->set_query($hasil);
            $this->excel_generator->set_header(array
            (
                'kodeprod', 'namaprod','groupsalur','sektor','segment','subgroup','nama_sub_group',
                'unit_1','unit_2','unit_3','unit_4','unit_5','unit_6',
                'unit_7','unit_8','unit_9','unit_10','unit_11','unit_12',
                'value_1','value_2','value_3','value_4','value_5','value_6',
                'value_7','value_8','value_9','value_10','value_11','value_12', 
                'ot_1','ot_2','ot_3','ot_4','ot_5','ot_6',
                'ot_7','ot_8','ot_9','ot_10','ot_11','ot_12',
                'ec_1','ec_2','ec_3','ec_4','ec_5','ec_6',
                'ec_7','ec_8','ec_9','ec_10','ec_11','ec_12'
            ));
            $this->excel_generator->set_column(array
            ( 
                'kodeprod', 'namaprod','groupsalur','sektor','segment','subgroup','nama_sub_group',
                'unit_1','unit_2','unit_3','unit_4','unit_5','unit_6',
                'unit_7','unit_8','unit_9','unit_10','unit_11','unit_12',
                'value_1','value_2','value_3','value_4','value_5','value_6',
                'value_7','value_8','value_9','value_10','value_11','value_12', 
                'ot_1','ot_2','ot_3','ot_4','ot_5','ot_6',
                'ot_7','ot_8','ot_9','ot_10','ot_11','ot_12',
                'ec_1','ec_2','ec_3','ec_4','ec_5','ec_6',
                'ec_7','ec_8','ec_9','ec_10','ec_11','ec_12'
            ));
            $this->excel_generator->set_width(array(10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10));
            //$this->excel_generator->exportTo2007('Omzet'.'_'.$tahun.'_'.$supplier);   
            $this->excel_generator->exportTo2007('Sell_Out_Product'); 
        }

        //kodeprod, kodesalur, grup, subgroup
        elseif ($kondisi == '55'){
            $hasil = $this->db->query("
            select  kodeprod,namaprod, groupsalur, `group`, nama_group, subgroup, nama_sub_group,
                    unit_1,unit_2,unit_3,unit_4,unit_5,unit_6,
                    unit_7,unit_8,unit_9,unit_10,unit_11,unit_12,
                    value_1,value_2,value_3,value_4,value_5,value_6,
                    value_7,value_8,value_9,value_10,value_11,value_12, 
                    ot_1,ot_2,ot_3,ot_4,ot_5,ot_6,
                    ot_7,ot_8,ot_9,ot_10,ot_11,ot_12,
                    ec_1,ec_2,ec_3,ec_4,ec_5,ec_6,
                    ec_7,ec_8,ec_9,ec_10,ec_11,ec_12               
            from   db_temp.t_temp_soprod
            where  id = $id and created_date = (select max(created_date) from db_temp.t_temp_soprod where id = $id)
            ORDER BY urutan asc");   
        
            $this->excel_generator->set_query($hasil);
            $this->excel_generator->set_header(array
            (
                'kodeprod', 'namaprod','groupsalur','group','nama_group','subgroup','nama_sub_group',
                'unit_1','unit_2','unit_3','unit_4','unit_5','unit_6',
                'unit_7','unit_8','unit_9','unit_10','unit_11','unit_12',
                'value_1','value_2','value_3','value_4','value_5','value_6',
                'value_7','value_8','value_9','value_10','value_11','value_12', 
                'ot_1','ot_2','ot_3','ot_4','ot_5','ot_6',
                'ot_7','ot_8','ot_9','ot_10','ot_11','ot_12',
                'ec_1','ec_2','ec_3','ec_4','ec_5','ec_6',
                'ec_7','ec_8','ec_9','ec_10','ec_11','ec_12'
            ));
            $this->excel_generator->set_column(array
            ( 
                'kodeprod', 'namaprod','groupsalur','group','nama_group','subgroup','nama_sub_group',
                'unit_1','unit_2','unit_3','unit_4','unit_5','unit_6',
                'unit_7','unit_8','unit_9','unit_10','unit_11','unit_12',
                'value_1','value_2','value_3','value_4','value_5','value_6',
                'value_7','value_8','value_9','value_10','value_11','value_12', 
                'ot_1','ot_2','ot_3','ot_4','ot_5','ot_6',
                'ot_7','ot_8','ot_9','ot_10','ot_11','ot_12',
                'ec_1','ec_2','ec_3','ec_4','ec_5','ec_6',
                'ec_7','ec_8','ec_9','ec_10','ec_11','ec_12'
            ));
            $this->excel_generator->set_width(array(10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10));
            //$this->excel_generator->exportTo2007('Omzet'.'_'.$tahun.'_'.$supplier);   
            $this->excel_generator->exportTo2007('Sell_Out_Product'); 
        }

        //kodeprod, kode_type, grup, subgroup
        elseif ($kondisi == '56'){
            $hasil = $this->db->query("
            select  kodeprod,namaprod, sektor, segment, `group`, nama_group, subgroup, nama_sub_group,
                    unit_1,unit_2,unit_3,unit_4,unit_5,unit_6,
                    unit_7,unit_8,unit_9,unit_10,unit_11,unit_12,
                    value_1,value_2,value_3,value_4,value_5,value_6,
                    value_7,value_8,value_9,value_10,value_11,value_12, 
                    ot_1,ot_2,ot_3,ot_4,ot_5,ot_6,
                    ot_7,ot_8,ot_9,ot_10,ot_11,ot_12,
                    ec_1,ec_2,ec_3,ec_4,ec_5,ec_6,
                    ec_7,ec_8,ec_9,ec_10,ec_11,ec_12               
            from   db_temp.t_temp_soprod
            where  id = $id and created_date = (select max(created_date) from db_temp.t_temp_soprod where id = $id)
            ORDER BY urutan asc");   
        
            $this->excel_generator->set_query($hasil);
            $this->excel_generator->set_header(array
            (
                'kodeprod', 'namaprod','sektor','segment','group','nama_group','subgroup','nama_sub_group',
                'unit_1','unit_2','unit_3','unit_4','unit_5','unit_6',
                'unit_7','unit_8','unit_9','unit_10','unit_11','unit_12',
                'value_1','value_2','value_3','value_4','value_5','value_6',
                'value_7','value_8','value_9','value_10','value_11','value_12', 
                'ot_1','ot_2','ot_3','ot_4','ot_5','ot_6',
                'ot_7','ot_8','ot_9','ot_10','ot_11','ot_12',
                'ec_1','ec_2','ec_3','ec_4','ec_5','ec_6',
                'ec_7','ec_8','ec_9','ec_10','ec_11','ec_12'
            ));
            $this->excel_generator->set_column(array
            ( 
                'kodeprod', 'namaprod','sektor','segment','group','nama_group','subgroup','nama_sub_group',
                'unit_1','unit_2','unit_3','unit_4','unit_5','unit_6',
                'unit_7','unit_8','unit_9','unit_10','unit_11','unit_12',
                'value_1','value_2','value_3','value_4','value_5','value_6',
                'value_7','value_8','value_9','value_10','value_11','value_12', 
                'ot_1','ot_2','ot_3','ot_4','ot_5','ot_6',
                'ot_7','ot_8','ot_9','ot_10','ot_11','ot_12',
                'ec_1','ec_2','ec_3','ec_4','ec_5','ec_6',
                'ec_7','ec_8','ec_9','ec_10','ec_11','ec_12'
            ));
            $this->excel_generator->set_width(array(10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10));
            //$this->excel_generator->exportTo2007('Omzet'.'_'.$tahun.'_'.$supplier);   
            $this->excel_generator->exportTo2007('Sell_Out_Product'); 
        }

        //kodesalur, kode_type, grup, subgroup
        elseif ($kondisi == '57'){
            $hasil = $this->db->query("
            select  groupsalur, sektor, segment, `group`, nama_group, subgroup, nama_sub_group,
                    unit_1,unit_2,unit_3,unit_4,unit_5,unit_6,
                    unit_7,unit_8,unit_9,unit_10,unit_11,unit_12,
                    value_1,value_2,value_3,value_4,value_5,value_6,
                    value_7,value_8,value_9,value_10,value_11,value_12, 
                    ot_1,ot_2,ot_3,ot_4,ot_5,ot_6,
                    ot_7,ot_8,ot_9,ot_10,ot_11,ot_12,
                    ec_1,ec_2,ec_3,ec_4,ec_5,ec_6,
                    ec_7,ec_8,ec_9,ec_10,ec_11,ec_12               
            from   db_temp.t_temp_soprod
            where  id = $id and created_date = (select max(created_date) from db_temp.t_temp_soprod where id = $id)
            ORDER BY urutan asc");   
        
            $this->excel_generator->set_query($hasil);
            $this->excel_generator->set_header(array
            (
                'groupsalur','sektor','segment','group','nama_group','subgroup','nama_sub_group',
                'unit_1','unit_2','unit_3','unit_4','unit_5','unit_6',
                'unit_7','unit_8','unit_9','unit_10','unit_11','unit_12',
                'value_1','value_2','value_3','value_4','value_5','value_6',
                'value_7','value_8','value_9','value_10','value_11','value_12', 
                'ot_1','ot_2','ot_3','ot_4','ot_5','ot_6',
                'ot_7','ot_8','ot_9','ot_10','ot_11','ot_12',
                'ec_1','ec_2','ec_3','ec_4','ec_5','ec_6',
                'ec_7','ec_8','ec_9','ec_10','ec_11','ec_12'
            ));
            $this->excel_generator->set_column(array
            ( 
                'groupsalur','sektor','segment','group','nama_group','subgroup','nama_sub_group',
                'unit_1','unit_2','unit_3','unit_4','unit_5','unit_6',
                'unit_7','unit_8','unit_9','unit_10','unit_11','unit_12',
                'value_1','value_2','value_3','value_4','value_5','value_6',
                'value_7','value_8','value_9','value_10','value_11','value_12', 
                'ot_1','ot_2','ot_3','ot_4','ot_5','ot_6',
                'ot_7','ot_8','ot_9','ot_10','ot_11','ot_12',
                'ec_1','ec_2','ec_3','ec_4','ec_5','ec_6',
                'ec_7','ec_8','ec_9','ec_10','ec_11','ec_12'
            ));
            $this->excel_generator->set_width(array(10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10));
            //$this->excel_generator->exportTo2007('Omzet'.'_'.$tahun.'_'.$supplier);   
            $this->excel_generator->exportTo2007('Sell_Out_Product'); 
        }

    }

    // =========================================================================================================================================================================================================

    // public function sell_out_product(){           

    //     $data = [
    //         'id' => $this->session->userdata('id'),
    //         'url' => 'sales_omzet/sell_out_product_hasil/',
    //         'title' => 'Sale Out Product',
    //         'get_label' => $this->M_menu->get_label(),
    //         'get_supp' => $this->model_omzet->getSuppbyid(),
    //     ];

    //     $supp = $this->session->userdata('supp');
    //     $data['header_supp'] = $this->model_outlet_transaksi->get_header_supp($supp);
    //     $this->load->view('template_claim/top_header');
    //     $this->load->view('template_claim/header');
    //     $this->load->view('template_claim/sidebar',$data);
    //     $this->load->view('template_claim/top_content',$data);
    //     $this->load->view('sales_omzet/sell_out_product',$data);
    //     $this->load->view('template_claim/bottom_content');
    //     $this->load->view('template_claim/footer');

    //     }

    // public function sell_out_product_hasil(){
    //     $kodeprod = $this->input->post('options');
    //     $code = '';
    //     foreach($kodeprod as $kode)
    //     {
    //         $code.=",".$kode;
    //     }

    //     $data = [
    //         'id' => $this->session->userdata('id'),
    //         'url' => 'sales_omzet/sell_out_product_hasil/',
    //         'title' => 'Sales Per Product',
    //         'get_label' => $this->M_menu->get_label(),
    //         'get_supp' => $this->model_omzet->getSuppbyid(),
    //         'year' => $this->input->post('year'),
    //         'groupby' => $this->input->post('groupby'),
    //         'kondisi_class' => $this->input->post('kondisi_class'),
    //         'kodeprod'  => preg_replace('/,/', '', $code,1),
    //         'wilayah_nocab' => $this->model_per_hari->wilayah_nocab($this->session->userdata('id'))
    //     ];

    //     $kdc=$this->input->post('kondisi_class');
    //     $data['kdc']=$kdc;
    //     if($kdc == 1){
    //         $data['kdc'] = "On Faktur Transaction";
    //     }else{
    //         $data['kdc'] = "Current / Saat ini";
    //     }


    //     $data['query']=$this->model_sales_omzet->list_dp();
        
    //     $groupby = $this->input->post('groupby');
    //     if ($groupby == 1 ) {
    //         $view_content = 'sell_out_product_hasil_kodeprod';
    //         $data['proses'] = $this->model_sales_omzet->sales_per_product($data);
    //     }elseif($groupby == 2 ){
    //             $view_content = 'sell_out_product_hasil_class';
    //             $data['proses'] = $this->model_sales_omzet->sales_per_product_class($data);
    //     }else{
    //         $view_content = 'sell_out_product_hasil';
    //         $data['proses'] = $this->model_sales_omzet->sales_per_product($data);
    //     }

    //     $this->load->view('template_claim/top_header');
    //     $this->load->view('template_claim/header');
    //     $this->load->view('template_claim/sidebar',$data);
    //     $this->load->view('template_claim/top_content',$data);
    //     $this->load->view("sales_omzet/$view_content",$data);
    //     $this->load->view('template_claim/bottom_content');
    //     $this->load->view('template_claim/footer');            
    // }

    // public function export_sales_per_product(){
    //     $id=$this->session->userdata('id');
    //     $tahun = $this->uri->segment('3');
    //     $kondisi = $this->uri->segment('4');
    //     $class = $this->uri->segment('5');

    //     if ($kondisi == '1'){
    //         $query="
    //                 select  kode, branch_name,nama_comp as SubBranch, kodeprod,namaprod,
    //                         ot_1,ot_2,,ot_3,ot_4,ot_5,ot_6,
    //                         ot_7,,ot_8,ot_9,ot_10,ot_11,ot_12,
    //                         unit_1,unit_2,,unit_3,unit_4,unit_5,unit_6,
    //                         unit_7,,unit_8,unit_9,unit_10,unit_11,unit_12,
    //                         value_1,value_2,,value_3,value_4,value_5,value_6,
    //                         value_7,,value_8,value_9,value_10,value_11,value_12, substr(kode,1,3) as kode_comp
    //                 from   db_temp.t_temp_soprod
    //                 where  id = $id and created_date = (select max(created_date) from db_temp.t_temp_soprod where id = $id)
    //                 ORDER BY urutan asc
    //             ";
                            
    //         $hasil = $this->db->query($query);

    //     }elseif ($kondisi == '2'){

    //         $query="
    //             select  kode,nama_comp as SubBranch,jenis as Class,
    //                     ot_1,ot_2,,ot_3,ot_4,ot_5,ot_6,
    //                     ot_7,,ot_8,ot_9,ot_10,ot_11,ot_12,
    //                     unit_1,unit_2,,unit_3,unit_4,unit_5,unit_6,
    //                     unit_7,,unit_8,unit_9,unit_10,unit_11,unit_12,
    //                     value_1,value_2,,value_3,value_4,value_5,value_6,
    //                     value_7,,value_8,value_9,value_10,value_11,value_12, substr(kode,1,3) as kode_comp
    //             from   db_temp.t_temp_soprod
    //             where  id = $id and created_date = (select max(created_date) from db_temp.t_temp_soprod where id = $id)
    //             ORDER BY urutan asc
    //             ";
                            
    //         $hasil = $this->db->query($query);

    //       }else{
    //         $query="
    //             select  kode, branch_name,nama_comp as SubBranch,
    //                     ot_1,ot_2,,ot_3,ot_4,ot_5,ot_6,
    //                     ot_7,,ot_8,ot_9,ot_10,ot_11,ot_12,
    //                     unit_1,unit_2,,unit_3,unit_4,unit_5,unit_6,
    //                     unit_7,,unit_8,unit_9,unit_10,unit_11,unit_12,
    //                     value_1,value_2,,value_3,value_4,value_5,value_6,
    //                     value_7,,value_8,value_9,value_10,value_11,value_12, substr(kode,1,3) as kode_comp
    //             from   db_temp.t_temp_soprod
    //             where  id = $id and created_date = (select max(created_date) from db_temp.t_temp_soprod where id = $id)
    //             ORDER BY urutan asc
    //         ";
                        
    //     $hasil = $this->db->query($query);
              
    //       }                       
    //     query_to_csv($hasil,TRUE,"SalesPerProduct($class)_$tahun.csv");
    // }

    public function sales_outlet(){

        $data = [
            'id' => $this->session->userdata('id'),
            'url' => 'sales_omzet/sales_outlet_hasil',
            'title' => 'Sales Outlet',
            'get_label' => $this->M_menu->get_label(),
           ];
        
        $supp = $this->session->userdata('supp');
        $data['header_supp'] = $this->model_outlet_transaksi->get_header_supp($supp);
        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar',$data);
        $this->load->view('template_claim/top_content',$data);
        $this->load->view('sales_omzet/sales_outlet',$data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');

    }

    public function sales_outlet_hasil(){
        $kodeprod = $this->input->post('options');
        $code = '';
        foreach($kodeprod as $kode)
        {
            $code.=",".$kode;
        }
        
        $data = [
            'id' => $this->session->userdata('id'),
            'title' => 'Sales Outlet',
            'url' => 'sales_omzet/sales_outlet_hasil',
            'tahun' => $this->input->post('year'),
            'dp' => $this->input->post('nocab') ,
            'bd' => $this->input->post('bd'),
            'sm' => $this->input->post('sm'),
            'get_label' => $this->M_menu->get_label(),
            'kodeprod'  => preg_replace('/,/', '', $code,1),
            'wilayah_nocab' => $this->model_per_hari->wilayah_nocab($this->session->userdata('id'))
        ];

        
        $bd = $this->input->post('bd');
        $sm = $this->input->post('sm');
        $jmlh_dp = count($this->input->post('nocab'));
        if ($jmlh_dp == 1 ) {
            if ($bd == '1' && $sm == '0'){
                $view_content = 'sales_outlet_hasil_kodeprod';
            }elseif ($bd == '0' && $sm == '1'){
                $view_content = 'sales_outlet_hasil_salesman';
            }elseif ($bd == '1' && $sm == '1'){
                $view_content = 'sales_outlet_hasil_kodeprod_salesman';
            }else{
                $view_content = 'sales_outlet_hasil';
            }
        }else{
            if ($bd == '1' && $sm == '0'){
                $view_content = 'sales_outlet_hasil_dp_kodeprod';
            }elseif ($bd == '0' && $sm == '1'){
                $view_content = 'sales_outlet_hasil_dp_salesman';
            }elseif ($bd == '1' && $sm == '1'){
                $view_content = 'sales_outlet_hasil_dp_kodeprod_salesman';
            }else{
                $view_content = 'sales_outlet_hasil_dp';
            }
        }
       

        $supp = $this->session->userdata('supp');
        $data['header_supp'] = $this->model_outlet_transaksi->get_header_supp($supp);
        $data['proses']=$this->model_sales_omzet->outlet($data);
        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar',$data);
        $this->load->view('template_claim/top_content',$data);
        $this->load->view("sales_omzet/$view_content",$data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');

    }

    public function export_outlet(){
        $id=$this->session->userdata('id');
        if ( function_exists( 'date_default_timezone_set' ) )
        date_default_timezone_set('Asia/Jakarta');        
        $tgl_created= date('d-m-Y') ;
        $tahun = $this->uri->segment('3');
        $kondisi = $this->uri->segment('4');
        $bd = $this->uri->segment('5');

        if ($kondisi == '1'){
            $query="
                    SELECT  substr(kode,1,3) as kode_comp, kode, outlet, kodesales as kode_sales, namasales as salesman,kode_type, kodesalur, rayon, kota,
                            unit_1,unit_2,unit_3,unit_4,unit_5,unit_6,
                            unit_7,unit_8,unit_9,unit_10,unit_11,unit_12,
                            value_1,value_2,value_3,value_4,value_5,value_6,
                            value_7,value_8,value_9,value_10,value_11,value_12
                    FROM db_temp.t_temp_outlet
                    where  id = $id and created_date = (select max(created_date) from db_temp.t_temp_outlet where id = $id)
                     ";
                            
            $hasil = $this->db->query($query);

        }elseif ($kondisi == '2'){
            $query="
                    SELECT  substr(kode,1,3) as kode_comp, kode, outlet, kode_type, kodesalur, rayon, kota, kodeprod as kode_produk, namaprod as nama_produk,
                            unit_1,unit_2,unit_3,unit_4,unit_5,unit_6,
                            unit_7,unit_8,unit_9,unit_10,unit_11,unit_12,
                            value_1,value_2,value_3,value_4,value_5,value_6,
                            value_7,value_8,value_9,value_10,value_11,value_12
                    FROM db_temp.t_temp_outlet
                    where  id = $id and created_date = (select max(created_date) from db_temp.t_temp_outlet where id = $id)
                     ";
                            
            $hasil = $this->db->query($query);
        }elseif ($kondisi == '3'){
            $query="
                    SELECT  substr(kode,1,3) as kode_comp, kode, outlet, kodesales as kode_sales, namasales as salesman,kode_type, kodesalur, rayon, kota, kodeprod as kode_produk, namaprod as nama_produk,
                            unit_1,unit_2,unit_3,unit_4,unit_5,unit_6,
                            unit_7,unit_8,unit_9,unit_10,unit_11,unit_12,
                            value_1,value_2,value_3,value_4,value_5,value_6,
                            value_7,value_8,value_9,value_10,value_11,value_12
                    FROM db_temp.t_temp_outlet
                    where  id = $id and created_date = (select max(created_date) from db_temp.t_temp_outlet where id = $id)
                     ";
                            
            $hasil = $this->db->query($query);

        }else{
            $query="
                    select  substr(kode,1,3) as kode_comp, kode, outlet, kode_type, kodesalur, rayon, kota,
                            unit_1,unit_2,unit_3,unit_4,unit_5,unit_6,
                            unit_7,unit_8,unit_9,unit_10,unit_11,unit_12,
                            value_1,value_2,value_3,value_4,value_5,value_6,
                            value_7,value_8,value_9,value_10,value_11,value_12
                    from   db_temp.t_temp_outlet
                    where  id = $id and created_date = (select max(created_date) from db_temp.t_temp_outlet where id = $id)
                   ";

            $hasil = $this->db->query($query);
        }
         query_to_csv($hasil,TRUE,"Outlet_$tgl_created.csv");
    }
    // ============================================ Export excel outlet ================================================================================
   
    public function export_excel_outlet() {
        
        $id=$this->session->userdata('id');
        if ( function_exists( 'date_default_timezone_set' ) )
        date_default_timezone_set('Asia/Jakarta');        
        $tgl_created= date('d-m-Y') ;
        $tahun = $this->uri->segment('3');
        $kondisi = $this->uri->segment('4');

        //salesman
        if($kondisi == '1'){
            $hasil = $this->db->query("
            SELECT  substr(kode,1,3) as kode_comp, kode, outlet, kodesales as kode_sales, namasales as salesman,kode_type, kodesalur, rayon, kota,
                    unit_1,unit_2,unit_3,unit_4,unit_5,unit_6,
                    unit_7,unit_8,unit_9,unit_10,unit_11,unit_12,
                    value_1,value_2,value_3,value_4,value_5,value_6,
                    value_7,value_8,value_9,value_10,value_11,value_12
            FROM db_temp.t_temp_outlet
            where  id = $id and created_date = (select max(created_date) from db_temp.t_temp_outlet where id = $id)
            ");   
        
            $this->excel_generator->set_query($hasil);
            $this->excel_generator->set_header(array
            (
                'kode_comp', 'kode', 'outlet',  'kode_sales', 'salesman','kode_type', 'kodesalur', 'rayon', 'kota',
                'unit_1','unit_2','unit_3','unit_4','unit_5','unit_6',
                'unit_7','unit_8','unit_9','unit_10','unit_11','unit_12',
                'value_1','value_2','value_3','value_4','value_5','value_6',
                'value_7','value_8','value_9','value_10','value_11','value_12'
            ));
            $this->excel_generator->set_column(array
            ( 
                'kode_comp',
                'kode',
                'outlet',
                'kode_sales',
                'salesman',
                'kode_type',
                'kodesalur',
                'rayon',
                'kota',  
                'unit_1',
                'unit_2',
                'unit_3',
                'unit_4',
                'unit_5',
                'unit_6',
                'unit_7',
                'unit_8',
                'unit_9',
                'unit_10',
                'unit_11',
                'unit_12',
                'value_1',
                'value_2',
                'value_3',
                'value_4',
                'value_5',
                'value_6',
                'value_7',
                'value_8',
                'value_9',
                'value_10',
                'value_11',
                'value_12'
            ));
            $this->excel_generator->set_width(array(10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10));
            //$this->excel_generator->exportTo2007('Omzet'.'_'.$tahun.'_'.$supplier);   
            $this->excel_generator->exportTo2007('Outlet');
            
        }
        //kodeprod
        elseif($kondisi == '2') {
            $hasil = $this->db->query("
            SELECT  substr(kode,1,3) as kode_comp, kode, outlet, kode_type, kodesalur, rayon, kota, kodeprod as kode_produk, namaprod as nama_produk,
                    unit_1,unit_2,unit_3,unit_4,unit_5,unit_6,
                    unit_7,unit_8,unit_9,unit_10,unit_11,unit_12,
                    value_1,value_2,value_3,value_4,value_5,value_6,
                    value_7,value_8,value_9,value_10,value_11,value_12
            FROM db_temp.t_temp_outlet
            where  id = $id and created_date = (select max(created_date) from db_temp.t_temp_outlet where id = $id)
            ");   
        
            $this->excel_generator->set_query($hasil);
            $this->excel_generator->set_header(array
            (
                'kode_comp', 'kode', 'outlet', 'kode_produk', 'nama_produk','kode_type', 'kodesalur', 'rayon', 'kota',
                'unit_1','unit_2','unit_3','unit_4','unit_5','unit_6',
                'unit_7','unit_8','unit_9','unit_10','unit_11','unit_12',
                'value_1','value_2','value_3','value_4','value_5','value_6',
                'value_7','value_8','value_9','value_10','value_11','value_12'
            ));
            $this->excel_generator->set_column(array
            ( 
                'kode_comp',
                'kode',
                'outlet',
                'kode_produk',
                'nama_produk',
                'kode_type',
                'kodesalur',
                'rayon',
                'kota',  
                'unit_1',
                'unit_2',
                'unit_3',
                'unit_4',
                'unit_5',
                'unit_6',
                'unit_7',
                'unit_8',
                'unit_9',
                'unit_10',
                'unit_11',
                'unit_12',
                'value_1',
                'value_2',
                'value_3',
                'value_4',
                'value_5',
                'value_6',
                'value_7',
                'value_8',
                'value_9',
                'value_10',
                'value_11',
                'value_12'
            ));
            $this->excel_generator->set_width(array(10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10));
            //$this->excel_generator->exportTo2007('Omzet'.'_'.$tahun.'_'.$supplier);   
            $this->excel_generator->exportTo2007('Outlet');    
        }
        //kodepored, salesman
        elseif($kondisi == '3'){
            $hasil = $this->db->query("
            SELECT  substr(kode,1,3) as kode_comp, kode, outlet, kodesales as kode_sales, namasales as salesman,  kodeprod as kode_produk, namaprod as nama_produk, kode_type, kodesalur, rayon, kota,
                    unit_1,unit_2,unit_3,unit_4,unit_5,unit_6,
                    unit_7,unit_8,unit_9,unit_10,unit_11,unit_12,
                    value_1,value_2,value_3,value_4,value_5,value_6,
                    value_7,value_8,value_9,value_10,value_11,value_12
            FROM db_temp.t_temp_outlet
            where  id = $id and created_date = (select max(created_date) from db_temp.t_temp_outlet where id = $id)
            ");   
        
            $this->excel_generator->set_query($hasil);
            $this->excel_generator->set_header(array
            (
                'kode_comp', 'kode', 'outlet', 'kode_sales', 'salesman', 'kode_produk', 'nama_produk','kode_type', 'kodesalur', 'rayon', 'kota',
                'unit_1','unit_2','unit_3','unit_4','unit_5','unit_6',
                'unit_7','unit_8','unit_9','unit_10','unit_11','unit_12',
                'value_1','value_2','value_3','value_4','value_5','value_6',
                'value_7','value_8','value_9','value_10','value_11','value_12'
            ));
            $this->excel_generator->set_column(array
            ( 
                'kode_comp',
                'kode',
                'outlet',
                'kode_sales',
                'salesman',
                'kode_produk',
                'nama_produk',
                'kode_type',
                'kodesalur',
                'rayon',
                'kota',  
                'unit_1',
                'unit_2',
                'unit_3',
                'unit_4',
                'unit_5',
                'unit_6',
                'unit_7',
                'unit_8',
                'unit_9',
                'unit_10',
                'unit_11',
                'unit_12',
                'value_1',
                'value_2',
                'value_3',
                'value_4',
                'value_5',
                'value_6',
                'value_7',
                'value_8',
                'value_9',
                'value_10',
                'value_11',
                'value_12'
            ));
            $this->excel_generator->set_width(array(10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10));
            //$this->excel_generator->exportTo2007('Omzet'.'_'.$tahun.'_'.$supplier);   
            $this->excel_generator->exportTo2007('Outlet');    
        }
        else{
            $hasil = $this->db->query("
            SELECT  substr(kode,1,3) as kode_comp, kode, outlet, kode_type, kodesalur, rayon, kota,
                    unit_1,unit_2,unit_3,unit_4,unit_5,unit_6,
                    unit_7,unit_8,unit_9,unit_10,unit_11,unit_12,
                    value_1,value_2,value_3,value_4,value_5,value_6,
                    value_7,value_8,value_9,value_10,value_11,value_12
            FROM db_temp.t_temp_outlet
            where  id = $id and created_date = (select max(created_date) from db_temp.t_temp_outlet where id = $id)
            ");   
        
            $this->excel_generator->set_query($hasil);
            $this->excel_generator->set_header(array
            (
                'kode_comp', 'kode', 'outlet', 'kode_type', 'kodesalur', 'rayon', 'kota',
                'unit_1','unit_2','unit_3','unit_4','unit_5','unit_6',
                'unit_7','unit_8','unit_9','unit_10','unit_11','unit_12',
                'value_1','value_2','value_3','value_4','value_5','value_6',
                'value_7','value_8','value_9','value_10','value_11','value_12'
            ));
            $this->excel_generator->set_column(array
            ( 
                'kode_comp',
                'kode',
                'outlet',
                'kode_type',
                'kodesalur',
                'rayon',
                'kota',  
                'unit_1',
                'unit_2',
                'unit_3',
                'unit_4',
                'unit_5',
                'unit_6',
                'unit_7',
                'unit_8',
                'unit_9',
                'unit_10',
                'unit_11',
                'unit_12',
                'value_1',
                'value_2',
                'value_3',
                'value_4',
                'value_5',
                'value_6',
                'value_7',
                'value_8',
                'value_9',
                'value_10',
                'value_11',
                'value_12'
            ));
            $this->excel_generator->set_width(array(10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10));
            //$this->excel_generator->exportTo2007('Omzet'.'_'.$tahun.'_'.$supplier);   
            $this->excel_generator->exportTo2007('Outlet');    
        }
    }
    // ============================================================================================================================================================================

    public function insert_user(){

        $sql = "
        select userid
        from mpm.menudetail a
        where menuid = 143 and userid not in (297)
        ";

        // $sql = "
        // select id as userid
        // from mpm.`user` a
        // where username in ('ilham','sufi')
        // ";

        $proses = $this->db->query($sql)->result();

        foreach ($proses as $x) {
            echo $x->userid."<br>";

            $sql = "
                insert into db_menu.t_akses
                select '',$x->userid,39,1
            ";
            $proses = $this->db->query($sql);

        }


    }

    public function insert_dashboard(){

        $sql = "
            select a.userid
            FROM
            (
                    select a.userid
                                    from mpm.menudetail a 
                                    where menuid not in (147)
                    GROUP BY userid
            )a inner JOIN mpm.user b on a.userid = b.id
            where b.active = 1
        ";
        $proses = $this->db->query($sql)->result();
        foreach ($proses as $a) {
            $userid = $a->userid;

            $sql = "
            insert into mpm.menudetail
            select '',147,$userid,547,547,'',''
            from mpm.menudetail a
            ";
            $proses = $this->db->query($sql);
        }
    }

    public function raw_data(){

        // echo strlen($this->session->userdata('username'));
        // if ($this->session->userdata('username') != 'suffy' ||$this->session->userdata('username') != 'suffy' ||$this->session->userdata('username') != 'suffy' ||$this->session->userdata('username') != 'suffy' ||) {
        //     echo "<script>alert('maaf. belum ini belum bisa diakses oleh dp sampai informasi selanjutnya'); </script>";
        //     redirect('dashboard_dummy','refresh');
        // }

        $session_username = $this->session->userdata('username');


        $sql = "
        select *
        from mpm.user a 
        where (a.username in ('suffy','ilham','felix','andy','junius','fardison','hendra','dewi') or a.supp not in (000)) and a.active = 1 and a.username in ('$session_username')
        ";

        $get_data = $this->db->query($sql)->num_rows();
        // $params_username = $get_data->username;
        // echo $params_username;

        // die;
        if ($get_data) {
            # code...
        }else{
            echo "<script>alert('maaf. belum ini belum bisa anda akses sampai pemberitahuan selanjutnya'); </script>";
            redirect('dashboard_dummy','refresh');
        }

        $data = [
            'id' => $this->session->userdata('id'),
            'url' => 'sales_omzet/omzet_dp_hasil',
            'title' => 'Raw data',
            'query' => $this->model_omzet->getSuppbyid(),
            'get_label' => $this->M_menu->get_label()
        ];

        $data['proses'] = $this->model_sales_omzet->raw_data($data);
        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar',$data);
        $this->load->view('template_claim/top_content',$data);
        // view info_maintenance
        // $this->load->view('info_maintenance');
        $this->load->view('sales_omzet/raw_data',$data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
        // $this->info_maintenance();
    }
    
    public function info_maintenance(){
        $this->load->view('info_maintenance');
    }

    public function actual_vs_target(){
        $data = [
            'id' => $this->session->userdata('id'),
            'url' => 'sales_omzet/actual_vs_target_hasil',
            'title' => 'Actual VS Target',
            'get_label' => $this->M_menu->get_label(),
            'get_supp' => $this->model_omzet->getSuppbyid(),
        ];
        $supp = $this->session->userdata('supp');
        $data['header_supp'] = $this->model_outlet_transaksi->get_header_supp($supp);
        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar',$data);
        $this->load->view('template_claim/top_content',$data);
        $this->load->view('sales_omzet/actual_vs_target',$data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
    }

    public function actual_vs_target_hasil()
    {
        $kodeprod = $this->input->post('options');
        $code = '';
        foreach($kodeprod as $kode)
        {
            $code.=",".$kode;
        }

        $data = [
            'id'                    => $this->session->userdata('id'),
            'url'                   => 'sales_omzet/omzet_dp_hasil/',
            'get_label'             => $this->M_menu->get_label(),
            'get_supp'              => $this->model_omzet->getSuppbyid(),
            'tahun'                 => $this->input->post('tahun'),
            'bulan'                 => $this->input->post('bulan'),
            'breakdown_kodeprod'    => $this->input->post('breakdown_kodeprod'),
            // 'breakdown_kodesalur'   => $this->input->post('breakdown_kodesalur'),
            'kodeprod'              => preg_replace('/,/', '', $code,1),
            'wilayah_nocab'         => $this->model_per_hari->wilayah_nocab($this->session->userdata('id')),
            'title'                 => 'actual vs target'
        ];

        $breakdown_kodeprod = $this->input->post('breakdown_kodeprod');
        // $breakdown_kodesalur = $this->input->post('breakdown_kodesalur');
        // if ($breakdown_kodeprod == '1' && $breakdown_kodesalur == '') {
        //     $view_content = 'actual_vs_target_hasil_kodeprod';
        // }elseif ($breakdown_kodeprod == '1' && $breakdown_kodesalur == '1') {
        //     $view_content = 'actual_vs_target_hasil_kodeprod_kodesalur';
        // }elseif ($breakdown_kodeprod == '' && $breakdown_kodesalur == '1') {
        //     $view_content = 'actual_vs_target_hasil_kodesalur';
        // }else{
        //     $view_content = 'actual_vs_target_hasil';
        // }
        if ($breakdown_kodeprod == '1') {
            $view_content = 'actual_vs_target_hasil_kodeprod';
        }else{
            $view_content = 'actual_vs_target_hasil';
        }

        $data['proses'] = $this->model_sales_omzet->actual_vs_target($data);
        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar',$data);
        $this->load->view('template_claim/top_content',$data);
        $this->load->view("sales_omzet/$view_content",$data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');   

    }

    public function export_actual_vs_target(){
        $id=$this->session->userdata('id');
        $kondisi = $this->uri->segment('3');

        if ($kondisi == '1'){
            $query="
                select a.kode,a.branch_name,a.nama_comp,a.bulan,a.actual_unit,a.target_unit,a.acv_unit,a.actual_value,a.target_value,a.acv_value
                from db_temp.t_temp_actual_vs_target a
                where a.id = $id and a.created_date = (select max(created_date) from db_temp.t_temp_actual_vs_target where id = $id)
                ORDER BY urutan asc
            ";            
        }else{
            $query="
            select a.kode,a.branch_name,a.nama_comp,a.bulan,a.kodeprod,a.namaprod,a.actual_unit,a.target_unit,a.acv_unit,a.actual_value,a.target_value,a.acv_value
            from db_temp.t_temp_actual_vs_target a
            where a.id = $id and a.created_date = (select max(created_date) from db_temp.t_temp_actual_vs_target where id = $id)
            ORDER BY urutan asc
        ";
        }
        $hasil = $this->db->query($query);
        query_to_csv($hasil,TRUE,"Actual vs Target.csv");
    }

    public function download_target(){
        $bulan = $this->uri->segment('3');
        $tahun = $this->uri->segment('4');        
        $query="
        select a.kode, b.branch_name, b.nama_comp, a.kodeprod, c.namaprod, a.unit, a.`value`, a.bulan, a.tahun
        from db_temp.t_temp_monitoring_stock_target_mentah a INNER JOIN
        (
            select concat(a.kode_comp,a.nocab) as kode, a.branch_name, a.nama_comp, a.urutan
            from mpm.tbl_tabcomp a
            GROUP BY concat(a.kode_comp,a.nocab) 
        )b on a.kode = b.kode INNER JOIN mpm.tabprod c on a.kodeprod = c.kodeprod
        where a.tahun = $tahun and bulan = $bulan
        ORDER BY urutan, kodeprod
        ";
        $hasil = $this->db->query($query);
        query_to_csv($hasil,TRUE,"Target Deltomed tahun : $tahun | bulan : $bulan.csv");
    }

    public function sell_out_nasional()
    {
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
        $data = [
            'id' => $this->session->userdata('id'),
            'url' => 'sales_omzet/sell_out_nasional_hasil',
            'title' => 'Sell Out Nasional',
            'get_label' => $this->M_menu->get_label(),
            'get_supp' => $this->model_omzet->getSuppbyid(),
            'query' => $this->model_sales->list_product()
           ];

        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar',$data);
        $this->load->view('template_claim/top_content',$data);
        $this->load->view('sales_omzet/sell_out_nasional',$data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
    }

    public function sell_out_nasional_hasil()
    {
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
        $data = [
            'id' => $this->session->userdata('id'),
            'url' => 'sales_omzet/sell_out_nasional_hasil',
            'title' => 'Sell Out Nasional',
            'get_label' => $this->M_menu->get_label(),
            'get_supp' => $this->model_omzet->getSuppbyid(),
            'periode_1' => $this->input->post('periode_1'),
            'supp' => $this->input->post('supp'),
            'group' => $this->input->post('group')
           ];

        $data['proses'] = $this->model_sales_omzet->sell_out_nasional($data);
        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar',$data);
        $this->load->view('template_claim/top_content',$data);
        $this->load->view('sales_omzet/sell_out_nasional_hasil',$data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
    }

    public function export_sell_out_nasional(){
        $id=$this->session->userdata('id');
        $tahun = $this->uri->segment('3');

        $query="
                select  a.kodeprod, a.namaprod, a.unit, a.value, a.bulan
                from db_temp.t_temp_sell_out_nasional a
                where a.id = $id and a.created_date = (select max(created_date) from db_temp.t_temp_sell_out_nasional where id = $id)
                ";
                        
        $hasil = $this->db->query($query);
        
        query_to_csv($hasil,TRUE,"Sell_out_nasional_$tahun.csv");
    }

    public function export_mapping_sektor(){                       
        $sql = "
            select a.kode_lang as kode_lang_isi_disini, a.kode_type as kode_type_isi_disini, a.sektor as sektor_isi_disini, a.status as aktif_isi_disini, a.deleted as deleted_isi_disini, a.filename, a.last_updated, b.username
            from db_delto.t_sales_outlet_fokus a LEFT JOIN
            (
                select a.id, a.username
                from mpm.user a
            )b on a.last_updated_by = b.id
            
        ";                   
        $result = $this->db->query($sql);
        // $result = $this->db->query("select * from db_delto.t_sales_outlet_fokus");
        query_to_csv($result,TRUE,'Export Override Sektor.csv');
        
    }

    public function import_mapping_sektor(){              

        $this->load->model('model_outlet_transaksi');
        if (!is_dir('./assets/uploads/mapping_sektor/' . date('Ym') . '/')) {
            @mkdir('./assets/uploads/mapping_sektor/' . date('Ym') . '/', 0777);
        }


        $id = $this->session->userdata('id');
        $created_date = $this->model_outlet_transaksi->timezone();
        $this->load->library('upload'); // Load librari upload        
        $config['upload_path'] = './assets/uploads/mapping_sektor/' . date('Ym') . '';
        // $config['allowed_types'] = 'csv';
        $config['allowed_types'] = '*';    
        $config['max_size']  = '2048';
        $config['overwrite'] = true;
        $this->upload->initialize($config);

        if ($this->upload->do_upload('file')) 
        {
            $upload_data = $this->upload->data();
            $filename = $upload_data['orig_name'];
            // $file_type = $upload_data['file_type'];
            $this->load->library('excel');
            $object = PHPExcel_IOFactory::load("assets/uploads/mapping_sektor/" . date('Ym') . "/$filename");

            $jumlahSheet = $object->getSheetCount();
            // echo "jumlahsheet : ".$jumlahSheet."<br>";
            // die;
            if ($jumlahSheet > 1) {
                echo "jumlah_sheet : ".$jumlahSheet;
                echo "<script>alert('import gagal. karena file mempunyai lebih dari 1 sheet'); </script>";
                redirect('sales_omzet/sell_out_product/','refresh');
            }
            foreach ($object->getWorksheetIterator() as $worksheet) {

                $highestRow = $worksheet->getHighestRow();
                $highestColumn = $worksheet->getHighestColumn();

                for ($row = 2; $row <= $highestRow; $row++) {          
                
                    $kode_lang = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
                    $kode_type = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
                    $sektor = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
                    $status = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
                    $deleted = $worksheet->getCellByColumnAndRow(4, $row)->getValue();

                    $this->db->where('kode_lang', $kode_lang);
                    $cek = $this->db->get('db_delto.t_sales_outlet_fokus')->num_rows();
                    // echo "cek : ".$cek;

                    if ($cek) {
                        
                        $data = [
                            'kode_lang'         => $kode_lang,
                            'kode_type'         => $kode_type,
                            'sektor'            => $sektor,
                            'status'            => $status,
                            'deleted'            => $deleted,
                            'filename'          => $filename,
                            'last_updated'      => $created_date,
                            'last_updated_by'   => $id
                        ]; 
                        $update = $this->model_sales_omzet->update('db_delto.t_sales_outlet_fokus', $data, 'kode_lang',$kode_lang);

                    }else{
                        
                        $data = [
                            'kode_lang'         => $kode_lang,
                            'kode_type'         => $kode_type,
                            'sektor'            => $sektor,
                            'status'            => $status,
                            'filename'          => $filename,
                            'last_updated'      => $created_date,
                            'last_updated_by'   => $id
                        ]; 
                        $insert = $this->model_sales_omzet->insert('db_delto.t_sales_outlet_fokus', $data);

                    }

                    // die;
                }

                echo "<script>alert('import berhasil....'); </script>";
                redirect('sales_omzet/sell_out_product','refresh');  

            }
        
        } else {
            echo "<script>alert('import gagal. hubungi IT'); </script>";
        }
     
    }

    public function bonus_csv()
    {
        $this->db->select('id_program, kodeprod, pembagi, tahun, bulan');
        $proses = $this->db->get('site.m_bonus');
        query_to_csv($proses,TRUE,'template_skema_bonus.csv');
    }

    public function config_upload(){        
        $this->load->library('upload'); // Load librari upload        
        $config['upload_path'] = './assets/uploads/import_bonus/';  
        $config['allowed_types'] = '*';    
        $config['max_size']  = '5000';    
        $config['overwrite'] = false;   
        $this->upload->initialize($config);
    }

    public function proses_mapping_product()
    {
        $created_at = $this->model_outlet_transaksi->timezone();
        $this->config_upload();
        // Load konfigurasi uploadnya    
        if($this->upload->do_upload('file'))
        { 
            $upload_data = $this->upload->data();
            $filename_csv = $upload_data['file_name'];
            $this->load->library('excel');
            $object = PHPExcel_IOFactory::load("assets/uploads/import_bonus/$filename_csv");


            foreach($object->getWorksheetIterator() as $worksheet)
            {
                $highestRow = $worksheet->getHighestRow();
                $highestColumn = $worksheet->getHighestColumn();                

                for($row=2; $row<=$highestRow; $row++)
                {
                    $id_program = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
                    $kodeprod = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
                    $pembagi = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
                    $tahun = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
                    $bulan = $worksheet->getCellByColumnAndRow(4, $row)->getValue();
                    $date = $worksheet->getCellByColumnAndRow(5, $row)->getValue();
                    // $created_date = date("Y-m-d", strtotime($date));  
                    // var_dump($created_date);die;

                    if ($id_program == null) {
                        echo "<script>alert('ada kesalahan. id_program tidak boleh null. cek kembali file anda'); </script>";
                        redirect('mt/import_order','refresh');   
                    }
                    if ($kodeprod == null) {
                        echo "<script>alert('ada kesalahan. kodeprod tidak boleh null. cek kembali file anda'); </script>";
                        redirect('mt/import_order','refresh');   
                    }
                    if ($pembagi == null) {
                        echo "<script>alert('ada kesalahan. pembagi$pembagi tidak boleh null. cek kembali file anda'); </script>";
                        redirect('mt/import_order','refresh');   
                    }
                    if ($tahun == null) {
                        echo "<script>alert('ada kesalahan. tahun tidak boleh null. cek kembali file anda'); </script>";
                        redirect('mt/import_order','refresh');   
                    }
                    if ($bulan == null) {
                        echo "<script>alert('ada kesalahan. bulan tidak boleh null. cek kembali file anda'); </script>";
                        redirect('mt/import_order','refresh');   
                    }
                
                    // echo $kodeprod;
                    if (strlen($kodeprod) == '5') {
                        $kodeprod = '0'.$kodeprod; 
                    }
                    $cek = "
                    select *
                    from site.m_bonus a 
                    where a.id_program = '$id_program' and a.kodeprod = '$kodeprod'
                    and a.tahun = '$tahun' and a.bulan = '$bulan'
                    ";

                    // print_r($cek);
                    // die;

                    $proses_cek = $this->db->query($cek)->num_rows();
                    if ($proses_cek) {
                        // echo "ada<br>";
                        $data = [
                            // 'id_program' => $id_program,  
                            // 'kodeprod' => $kodeprod,  
                            'pembagi' => $pembagi,  
                            // 'tahun' => $tahun,  
                            // 'bulan' => $bulan
                        ]; 

                        $this->db->where('id_program',$id_program);
                        $this->db->where('kodeprod',$kodeprod);
                        $this->db->where('tahun',$tahun);
                        $this->db->where('bulan',$bulan);
                        $update = $this->db->update('site.m_bonus',$data);


                    }else{
                        // echo "baru<br>";
                        $data = [
                            'id_program' => $id_program,  
                            'kodeprod' => $kodeprod,  
                            'pembagi' => $pembagi,  
                            'tahun' => $tahun,  
                            'bulan' => $bulan 
                        ];
                        $insert = $this->db->insert('site.m_bonus', $data);
                    }
                }
            }

            // $update = "
            //     update site.map_product_mt a 
            //     set a.kodeprod_mpm = concat('0',a.kodeprod_mpm)
            //     where length(a.kodeprod_mpm) = 5 and a.created_at = '$created_at'
            // ";
            // $proses_update = $this->db->query($update);

        }else{  
            $return = array('result' => 'failed', 'file' => '', 'error' => $this->upload->display_errors());      
            echo "<pre>";
            print_r($return);
            echo "</pre>";
        }

        echo "<script>alert('import berhasil'); </script>";
        redirect('sales_omzet/raw_data','refresh');      

    }

}
