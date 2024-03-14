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
            'kodeprod'  => preg_replace('/,/', '', $code,1),
        ];

        // $supp = $this->input->post('supp');
        // $group = $this->input->post('group');
        $breakdown_1 = $this->input->post('breakdown_1');
        $breakdown_2 = $this->input->post('breakdown_2');        

        // if ($group == '0') {
        //     $data['cari_kodeprod'] = $this->model_per_hari->cari_kodeprod_supp($supp);
        // }else{
        //     $data['cari_kodeprod'] = $this->model_per_hari->cari_kodeprod($group);
        // }

        $data['wilayah_nocab'] = $this->model_per_hari->wilayah_nocab($this->session->userdata('id'));
        // $data['namasupp'] = $this->model_per_hari->get_namasupp($supp);
        // $data['namagroup'] = $this->model_per_hari->get_namagroup($group);
        
        $data['proses'] = $this->model_sales_omzet->line_sold($data);

        if ($breakdown_1 == 1 && $breakdown_2 == 1) {
            $data['page_content'] = 'sales_omzet/line_sold_kodeprod_kodesales';
            $view_content = 'line_sold_hasil_kodeprod_kodesales';
            $data['title'] = 'Line Sold - breakdown kodeproduk & kodesales';
        }elseif ($breakdown_1 == 1 && $breakdown_2 == 0) {
            $data['page_content'] = 'sales_omzet/line_sold_kodeprod';
            $view_content = 'line_sold_hasil_kodeprod';
            $data['title'] = 'Line Sold - breakdown kodeproduk';
        }elseif ($breakdown_1 == 0 && $breakdown_2 == 1) {
            $data['page_content'] = 'sales_omzet/line_sold_kodesales';
            $view_content = 'line_sold_hasil_kodesales';
            $data['title'] = 'Line Sold - breakdown kodesales';
        }else{
            $data['page_content'] = 'sales_omzet/line_sold';
            $view_content = 'line_sold_hasil';
            $data['title'] = 'Line Sold';
        }

        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar',$data);
        $this->load->view('template_claim/top_content',$data);
        $this->load->view("sales_omzet/$view_content",$data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
    }

    public function export_line_sold(){
        $id = $this->session->userdata('id'); 
        $query="
            select  substr(kode,1,3) as kode_comp, a.kode,a.branch_name,a.nama_comp,a.faktur as total_faktur, a.produk as total_produk, a.line_sold
            from db_temp.t_temp_line_sold a 
            where a.id = $id and a.created_date = (select max(created_date) from db_temp.t_temp_line_sold where id = $id)
        ";                        
        $hsl = $this->db->query($query);
        query_to_csv($hsl,TRUE,'Line Sold.csv');
    }

    public function export_line_sold_kodeprod(){
        $id = $this->session->userdata('id'); 
        $query="
            select  substr(kode,1,3) as kode_comp, a.kode,a.branch_name,a.nama_comp,a.kodeprod, a.namaprod,a.faktur as total_faktur, a.produk as total_produk, a.line_sold
            from db_temp.t_temp_line_sold a
            where a.id = $id
        ";                        
        $hsl = $this->db->query($query);
        query_to_csv($hsl,TRUE,'Line Sold - breakdown produk.csv');
    }

    public function export_line_sold_kodesales(){
        $id = $this->session->userdata('id'); 
        $query="
            select  substr(kode,1,3) as kode_comp, a.kode,a.branch_name,a.nama_comp,a.kodesales, a.namasales,a.faktur as total_faktur, a.produk as total_produk, a.line_sold
            from db_temp.t_temp_line_sold a
            where a.id = $id and a.created_date = (select max(created_date) from db_temp.t_temp_line_sold where id = $id)
        ";                        
        $hsl = $this->db->query($query);
        query_to_csv($hsl,TRUE,'Line Sold - breakdown salesman.csv');
    }

    public function export_line_sold_kodeprod_kodesales(){
        $id = $this->session->userdata('id'); 
        $query="
            select  substr(kode,1,3) as kode_comp, a.kode,a.branch_name,a.nama_comp,a.kodeprod,a.namaprod,a.kodesales, a.namasales,a.faktur as total_faktur, a.produk as total_produk, a.line_sold
            from db_temp.t_temp_line_sold a
            where a.id = $id
        ";                        
        $hsl = $this->db->query($query);
        query_to_csv($hsl,TRUE,'Line Sold - breakdown produk dan salesman.csv');
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

        if ($group == '0') {
            $data['cari_kodeprod'] = $this->model_per_hari->cari_kodeprod_supp($supp);
        }else{
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

    public function sell_out_product(){           

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
            'kodeprod'  => preg_replace('/,/', '', $code,1),
            'wilayah_nocab' => $this->model_per_hari->wilayah_nocab($this->session->userdata('id')),
            'breakdown_kodeprod' => $this->input->post('breakdown_kodeprod'),
            'breakdown_kodesalur' => $this->input->post('breakdown_kodesalur'),
            'breakdown_type' => $this->input->post('breakdown_type'),
            'breakdown_kode' => $this->input->post('breakdown_kode')
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

        if ($breakdown_kode == '' && $breakdown_kodeprod == '' && $breakdown_kodesalur == '' && $breakdown_type == '') {
            // $view_content = 'sell_out_product_hasil';
            // echo "tidak dapat menampilkan data";
            // $message = "Pilih salah satu";
            // echo "<script type='text/javascript'>alert('$message');</script>";
            redirect('sales_omzet/sell_out_product_kosong');
        }elseif($breakdown_kode == '1' && $breakdown_kodeprod == '' && $breakdown_kodesalur == '' && $breakdown_type == ''){
            $view_content = 'sell_out_product_hasil_kode';
        }elseif($breakdown_kode == '' && $breakdown_kodeprod == '1' && $breakdown_kodesalur == '' && $breakdown_type == ''){
            $view_content = 'sell_out_product_hasil_kodeprod';
        }elseif($breakdown_kode == '' && $breakdown_kodeprod == '' && $breakdown_kodesalur == '1' && $breakdown_type == ''){
            $view_content = 'sell_out_product_hasil_kodesalur';
        }elseif($breakdown_kode == '' && $breakdown_kodeprod == '' && $breakdown_kodesalur == '' && $breakdown_type == '1'){
            $view_content = 'sell_out_product_hasil_kode_type';
        }elseif($breakdown_kode == '1' && $breakdown_kodeprod == '1' && $breakdown_kodesalur == '' && $breakdown_type == ''){
            $view_content = 'sell_out_product_hasil_kode_kodeprod';
        }elseif($breakdown_kode == '1' && $breakdown_kodeprod == '' && $breakdown_kodesalur == '1' && $breakdown_type == ''){
            $view_content = 'sell_out_product_hasil_kode_kodesalur';
        }elseif($breakdown_kode == '1' && $breakdown_kodeprod == '' && $breakdown_kodesalur == '' && $breakdown_type == '1'){
            $view_content = 'sell_out_product_hasil_kode_kode_type';
        }elseif($breakdown_kode == '' && $breakdown_kodeprod == '1' && $breakdown_kodesalur == '1' && $breakdown_type == ''){
            $view_content = 'sell_out_product_hasil_kodeprod_kodesalur';
        }elseif($breakdown_kode == '' && $breakdown_kodeprod == '1' && $breakdown_kodesalur == '' && $breakdown_type == '1'){
            $view_content = 'sell_out_product_hasil_kodeprod_kode_type';
        }elseif($breakdown_kode == '' && $breakdown_kodeprod == '' && $breakdown_kodesalur == '1' && $breakdown_type == '1'){
            $view_content = 'sell_out_product_hasil_kodesalur_kode_type';
        }elseif($breakdown_kode == '1' && $breakdown_kodeprod == '1' && $breakdown_kodesalur == '1' && $breakdown_type == ''){
            $view_content = 'sell_out_product_hasil_kode_kodeprod_kodesalur';
        }elseif($breakdown_kode == '1' && $breakdown_kodeprod == '1' && $breakdown_kodesalur == '' && $breakdown_type == '1'){
            $view_content = 'sell_out_product_hasil_kode_kodeprod_kode_type';
        }elseif($breakdown_kode == '1' && $breakdown_kodeprod == '' && $breakdown_kodesalur == '1' && $breakdown_type == '1'){
            $view_content = 'sell_out_product_hasil_kode_kodesalur_kode_type';
        }elseif($breakdown_kode == '' && $breakdown_kodeprod == '1' && $breakdown_kodesalur == '1' && $breakdown_type == '1'){
            $view_content = 'sell_out_product_hasil_kodeprod_kodesalur_kode_type';
        }elseif($breakdown_kode == '1' && $breakdown_kodeprod == '1' && $breakdown_kodesalur == '1' && $breakdown_type == '1'){
            $view_content = 'sell_out_product_hasil_kode_kodeprod_kodesalur_kode_type';
        }

        // if ($breakdown_kodeprod == '1' && $breakdown_kodesalur == '' && $breakdown_type == '') {
        //     $view_content = 'sell_out_product_hasil_kodeprod';
        // }elseif ($breakdown_kodeprod == '1' && $breakdown_kodesalur == '1' && $breakdown_type == '') {
        //     $view_content = 'sell_out_product_hasil_kodeprod_kodesalur';
        // }elseif ($breakdown_kodeprod == '1' && $breakdown_kodesalur == '' && $breakdown_type == '1') {
        //     $view_content = 'sell_out_product_hasil_kodeprod_type';
        // }elseif ($breakdown_kodeprod == '1' && $breakdown_kodesalur == '1' && $breakdown_type == '1') {
        //     $view_content = 'sell_out_product_hasil_kodeprod_kodesalur_type';
        // }elseif ($breakdown_kodeprod == '' && $breakdown_kodesalur == '1' && $breakdown_type == '') {
        //     $view_content = 'sell_out_product_hasil_kodesalur';
        // }elseif ($breakdown_kodeprod == '' && $breakdown_kodesalur == '1' && $breakdown_type == '1') {
        //     $view_content = 'sell_out_product_hasil_kodesalur_type';
        // }elseif ($breakdown_kodeprod == '' && $breakdown_kodesalur == '' && $breakdown_type == '1') {
        //     $view_content = 'sell_out_product_hasil_type';
        // }elseif ($breakdown_kodeprod == '' && $breakdown_kodesalur == '' && $breakdown_type == '1') {
        //     $view_content = 'sell_out_product_hasil_type';
        // }else{
        //     $view_content = 'sell_out_product_hasil';
        // }
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
                    ot_7,ot_8,ot_9,ot_10,ot_11,ot_12               
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
                    ot_7,ot_8,ot_9,ot_10,ot_11,ot_12               
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
                    ot_7,ot_8,ot_9,ot_10,ot_11,ot_12               
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
            select  sektor,
                    unit_1,unit_2,unit_3,unit_4,unit_5,unit_6,
                    unit_7,unit_8,unit_9,unit_10,unit_11,unit_12,
                    value_1,value_2,value_3,value_4,value_5,value_6,
                    value_7,value_8,value_9,value_10,value_11,value_12, 
                    ot_1,ot_2,ot_3,ot_4,ot_5,ot_6,
                    ot_7,ot_8,ot_9,ot_10,ot_11,ot_12               
            from   db_temp.t_temp_soprod
            where  id = $id and created_date = (select max(created_date) from db_temp.t_temp_soprod where id = $id)
            ORDER BY urutan asc
        ";                            
        $hasil = $this->db->query($query);
        query_to_csv($hasil,TRUE,"Sell Out Product.csv");
    }

    public function export_sales_per_product_kode_kodeprod(){
        $id=$this->session->userdata('id');
        $query="
            select  substr(kode,1,3) as kode_comp, kode, branch_name,nama_comp, kodeprod,namaprod,`group`,nama_group,
                    unit_1,unit_2,unit_3,unit_4,unit_5,unit_6,
                    unit_7,unit_8,unit_9,unit_10,unit_11,unit_12,
                    value_1,value_2,value_3,value_4,value_5,value_6,
                    value_7,value_8,value_9,value_10,value_11,value_12, 
                    ot_1,ot_2,ot_3,ot_4,ot_5,ot_6,
                    ot_7,ot_8,ot_9,ot_10,ot_11,ot_12               
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
                    ot_7,ot_8,ot_9,ot_10,ot_11,ot_12               
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
            select  substr(kode,1,3) as kode_comp, kode, branch_name,nama_comp, kode_type,sektor,
                    unit_1,unit_2,unit_3,unit_4,unit_5,unit_6,
                    unit_7,unit_8,unit_9,unit_10,unit_11,unit_12,
                    value_1,value_2,value_3,value_4,value_5,value_6,
                    value_7,value_8,value_9,value_10,value_11,value_12, 
                    ot_1,ot_2,ot_3,ot_4,ot_5,ot_6,
                    ot_7,ot_8,ot_9,ot_10,ot_11,ot_12               
            from   db_temp.t_temp_soprod
            where  id = $id and created_date = (select max(created_date) from db_temp.t_temp_soprod where id = $id)
            ORDER BY urutan asc
        ";                            
        $hasil = $this->db->query($query);
        query_to_csv($hasil,TRUE,"Sell Out Product.csv");
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
                    ot_7,ot_8,ot_9,ot_10,ot_11,ot_12               
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
            select  kodeprod,namaprod, sektor,
                    unit_1,unit_2,unit_3,unit_4,unit_5,unit_6,
                    unit_7,unit_8,unit_9,unit_10,unit_11,unit_12,
                    value_1,value_2,value_3,value_4,value_5,value_6,
                    value_7,value_8,value_9,value_10,value_11,value_12, 
                    ot_1,ot_2,ot_3,ot_4,ot_5,ot_6,
                    ot_7,ot_8,ot_9,ot_10,ot_11,ot_12               
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
            select  groupsalur,sektor,
                    unit_1,unit_2,unit_3,unit_4,unit_5,unit_6,
                    unit_7,unit_8,unit_9,unit_10,unit_11,unit_12,
                    value_1,value_2,value_3,value_4,value_5,value_6,
                    value_7,value_8,value_9,value_10,value_11,value_12, 
                    ot_1,ot_2,ot_3,ot_4,ot_5,ot_6,
                    ot_7,ot_8,ot_9,ot_10,ot_11,ot_12               
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
                    ot_7,ot_8,ot_9,ot_10,ot_11,ot_12               
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
            select  substr(kode,1,3) as kode_comp, kode, branch_name,nama_comp, kodeprod, namaprod,sektor,
                    unit_1,unit_2,unit_3,unit_4,unit_5,unit_6,
                    unit_7,unit_8,unit_9,unit_10,unit_11,unit_12,
                    value_1,value_2,value_3,value_4,value_5,value_6,
                    value_7,value_8,value_9,value_10,value_11,value_12, 
                    ot_1,ot_2,ot_3,ot_4,ot_5,ot_6,
                    ot_7,ot_8,ot_9,ot_10,ot_11,ot_12               
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
            select  substr(kode,1,3) as kode_comp, kode, branch_name,nama_comp, groupsalur,sektor,
                    unit_1,unit_2,unit_3,unit_4,unit_5,unit_6,
                    unit_7,unit_8,unit_9,unit_10,unit_11,unit_12,
                    value_1,value_2,value_3,value_4,value_5,value_6,
                    value_7,value_8,value_9,value_10,value_11,value_12, 
                    ot_1,ot_2,ot_3,ot_4,ot_5,ot_6,
                    ot_7,ot_8,ot_9,ot_10,ot_11,ot_12               
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
            select  kodeprod, namaprod, groupsalur,sektor,
                    unit_1,unit_2,unit_3,unit_4,unit_5,unit_6,
                    unit_7,unit_8,unit_9,unit_10,unit_11,unit_12,
                    value_1,value_2,value_3,value_4,value_5,value_6,
                    value_7,value_8,value_9,value_10,value_11,value_12, 
                    ot_1,ot_2,ot_3,ot_4,ot_5,ot_6,
                    ot_7,ot_8,ot_9,ot_10,ot_11,ot_12               
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
            select  substr(kode,1,3) as kode_comp, kode, branch_name,nama_comp, kodeprod, namaprod, groupsalur,sektor,
                    unit_1,unit_2,unit_3,unit_4,unit_5,unit_6,
                    unit_7,unit_8,unit_9,unit_10,unit_11,unit_12,
                    value_1,value_2,value_3,value_4,value_5,value_6,
                    value_7,value_8,value_9,value_10,value_11,value_12, 
                    ot_1,ot_2,ot_3,ot_4,ot_5,ot_6,
                    ot_7,ot_8,ot_9,ot_10,ot_11,ot_12               
            from   db_temp.t_temp_soprod
            where  id = $id and created_date = (select max(created_date) from db_temp.t_temp_soprod where id = $id)
            ORDER BY urutan asc
        ";                            
        $hasil = $this->db->query($query);
        query_to_csv($hasil,TRUE,"Sell Out Product.csv");
    }

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
                    SELECT  substr(kode,1,3) as kode_comp, kode, outlet, alamat, kodesales as kode_sales, namasales as salesman,kode_type, kodesalur, rayon, kota,
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
                    SELECT  substr(kode,1,3) as kode_comp, kode, outlet, alamat, kode_type, kodesalur, rayon, kota, kodeprod as kode_produk, namaprod as nama_produk,
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
                    SELECT  substr(kode,1,3) as kode_comp, kode, outlet, alamat, kodesales as kode_sales, namasales as salesman,kode_type, kodesalur, rayon, kota, kodeprod as kode_produk, namaprod as nama_produk,
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
                    select  substr(kode,1,3) as kode_comp, kode, outlet, alamat, kode_type, kodesalur, rayon, kota,
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
        $this->load->view('sales_omzet/raw_data',$data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
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

}
?>
