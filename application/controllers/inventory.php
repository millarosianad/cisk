<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Inventory extends MY_Controller
{

    function inventory()
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
        $this->load->model('model_outlet_transaksi');
        $this->load->model('model_inventory');
        $this->load->database();
    }
    function index()
    {
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
    }

    public function monitoring_stock_deltomed(){
        $data = [
            'id' => $this->session->userdata('id'),
            'url' => 'sales_omzet/line_sold_hasil/',
            'title' => 'Monitoring Stock Deltomed',
            'get_label' => $this->M_menu->get_label(),
            'get_monitoring_stock_deltomed_last' => $this->model_inventory->get_monitoring_stock_deltomed_last(),
        ];

        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar',$data);
        $this->load->view('template_claim/top_content',$data);
        $this->load->view("inventory/monitoring_stock_deltomed",$data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
    }

    public function monitoring_stock_deltomed_update(){
        $data = [
            'id' => $this->session->userdata('id'),
            'url' => 'sales_omzet/line_sold_hasil/',
            'title' => 'Monitoring Stock Deltomed',
            'get_label' => $this->M_menu->get_label(),
            'monitoring_stock_deltomed_update' => $this->model_inventory->monitoring_stock_deltomed_update(),
        ];

        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar',$data);
        $this->load->view('template_claim/top_content',$data);
        $this->load->view("inventory/monitoring_stock_deltomed_update",$data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
    }

    public function monitoring_stock_deltomed_update_next(){
        $data = [
            'id' => $this->session->userdata('id'),
            'url' => 'sales_omzet/line_sold_hasil/',
            'title' => 'Monitoring Stock Deltomed',
            'get_label' => $this->M_menu->get_label(),
            'monitoring_stock_deltomed_update_next' => $this->model_inventory->monitoring_stock_deltomed_update_next(),
        ];

        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar',$data);
        $this->load->view('template_claim/top_content',$data);
        $this->load->view("inventory/monitoring_stock_deltomed_update_next",$data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
    }

    public function monitoring_stock_deltomed_clear(){

        $x = $this->model_inventory->monitoring_stock_deltomed_clear();
        if ($x) {
            redirect('inventory/monitoring_stock_deltomed');
        }else{
            echo "sistem gagal. Silahkan infokan IT";
        }

    }

    public function export_monitoring_stock_deltomed_po_outstanding(){
        $id=$this->session->userdata('id');

        $query="

        select a.kode, b.branch_name,b.nama_comp,a.nopo, a.tglpo, a.kodeprod, a.unit, a.nodo, a.tgldo, if(jawa = 1 and selisih >=2,'y',if(nodo is null,'n','')) as `sampai?(y/n)`
        from db_temp.t_temp_monitoring_stock_po_blank a LEFT JOIN
        (
            select concat(a.kode_comp,a.nocab) as kode, a.branch_name,a.nama_comp,a.urutan,jawa
            from mpm.tbl_tabcomp a
            where a.`status` = 1 
            GROUP BY concat(a.kode_comp,a.nocab)
        )b on a.kode = b.kode
        where id = $id and a.kode is not null and created_date = (select max(created_date) from db_temp.t_temp_monitoring_stock_po_blank where id = $id)
        ORDER BY kode,kodeprod
        ";

        $hasil = $this->db->query($query);
        
        query_to_csv($hasil,TRUE,"export_monitoring_stock_deltomed_po_outstanding.csv");
    }

    public function export_monitoring_stock_deltomed(){
        // $query="
        // select * from db_temp.t_temp_monitoring_stock_report_update_hasil a where a.created_date = (select max(created_date) from db_temp.t_temp_monitoring_stock_report_update_hasil)
        // ";
        $query="
        select * from db_temp.t_temp_monitoring_stock_report_update_hasil a where a.created_date = (select max(created_date) from db_temp.t_temp_monitoring_stock_report_update_hasil)
        ";

        $hasil = $this->db->query($query);        
        query_to_csv($hasil,TRUE,"export_monitoring_stock_deltomed.csv");
    }

    public function export_monitoring_stock_deltomed_hasil(){
        $query="
        select * from db_temp.t_temp_monitoring_stock_report_update_hasil a where a.created_date = (select max(created_date) from db_temp.t_temp_monitoring_stock_report_update_hasil)
        ";

        $hasil = $this->db->query($query);        
        query_to_csv($hasil,TRUE,"export_monitoring_stock_deltomed.csv");
    }

    public function export_master_produk(){
        $id=$this->session->userdata('id');

        $query="
        select a.supp, d.NAMASUPP, a.KODEPROD, a.NAMAPROD, a.kodeprod_deltomed, a.ISISATUAN, a.volume, a.berat, b.h_dp, a.active
        from mpm.tabprod a INNER JOIN mpm.prod_detail b 
            on a.kodeprod = b.kodeprod LEFT JOIN mpm.tabsupp d on a.supp = d.supp
        where b.tgl = (
            select max(c.tgl)
            from mpm.prod_detail c
            where c.kodeprod = b.kodeprod
        )
        and a.active = 1
        ORDER BY kodeprod
        ";

        $hasil = $this->db->query($query);
        
        query_to_csv($hasil,TRUE,"export master product.csv");
    }

    public function stock_akhir_doi(){
        $data = [
            'id' => $this->session->userdata('id'),
            'url' => 'inventory/stock_akhir_doi_hasil/',
            'title' => 'Stock Akhir dan DOI',
            'get_label' => $this->M_menu->get_label(),
        ];
        $supp = $this->session->userdata('supp');
        $data['header_supp'] = $this->model_outlet_transaksi->get_header_supp($supp);
        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar',$data);
        $this->load->view('template_claim/top_content',$data);
        $this->load->view("inventory/stock_akhir_doi",$data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
    }

    public function stock_akhir_doi_hasil()
    {

        // echo "bulan : ".$this->input->post('bulan');
        // die;

        $kodeprod = $this->input->post('options');
        $code = '';
        foreach($kodeprod as $kode)
        {
            $code.=",".$kode;
        }

        $created_at = $this->model_outlet_transaksi->timezone();

        $data = [
            'id'                    => $this->session->userdata('id'),
            'url'                   => 'inventory/stock_akhir_doi_hasil/',
            'get_label'             => $this->M_menu->get_label(),
            'get_supp'              => $this->model_omzet->getSuppbyid(),
            'bulan'                 => $this->input->post('bulan'),
            'kodeprod'              => preg_replace('/,/', '', $code,1),
            'wilayah_nocab'         => $this->model_per_hari->wilayah_nocab($this->session->userdata('id')),
            'title'                 => 'Stock Akhir dan DOI'
        ];

        $data['proses'] = $this->model_inventory->stock_akhir_doi($data, $created_at);
        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar',$data);
        $this->load->view('template_claim/top_content',$data);
        $this->load->view("inventory/stock_akhir_doi_hasil",$data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');   

    }

    public function export_stock_akhir_doi(){
        $created_by = $this->session->userdata('id');
        $query="
        select * 
        from site.temp_stock_doi_report a 
        where a.created_by = $created_by and a.created_at = (select max(created_at) from site.temp_stock_doi_report where created_by = $created_by)
        order by branch_name, nama_comp
        ";

        $hasil = $this->db->query($query);        
        query_to_csv($hasil,TRUE,"export_stock_akhir_doi.csv");
    }

    public function laporan_po(){
        // $this->load->model('M_menu');
        // $this->load->model('model_per_hari');
        // $this->load->model('model_outlet_transaksi');
        $data = [
            'id' => $this->session->userdata('id'),
            'url' => 'inventory/laporan_po_hasil/',
            'title' => 'Laporan PO',
            'get_label' => $this->M_menu->get_label(),
        ];
        $supp = $this->session->userdata('supp');
        $data['header_supp'] = $this->model_outlet_transaksi->get_header_supp($supp);
        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar',$data);
        $this->load->view('template_claim/top_content',$data);
        $this->load->view("inventory/laporan_po",$data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
    }

    public function laporan_po_hasil(){

        $log = [
            'userid'    => $this->session->userdata('id'),
            'menu'      => $this->uri->segment('2'),
            'created_at'=> $this->model_outlet_transaksi->timezone()
        ];
        $this->db->insert('site.log_kunjungan_website', $log);

        $this->load->model('M_menu');
        $this->load->model('model_per_hari');
        $this->load->model('model_outlet_transaksi');
        $this->load->model('model_inventory');

        $kodeprod = $this->input->post('options');
        $code = '';
        foreach($kodeprod as $kode)
        {
            $code.=",".$kode;
        }

        $data = [
            'id' => $this->session->userdata('id'),
            'title' => 'Laporan PO',
            'get_label' => $this->M_menu->get_label(),
            'periode_1' => $this->input->post('periode_1'),
            'periode_2' => $this->input->post('periode_2'),
            'bukan_po' => $this->input->post('bukan_po'),
            'kodeprod'  => preg_replace('/,/', '', $code,1),
            'wilayah_nocab' => $this->model_per_hari->wilayah_nocab($this->session->userdata('id')),
            'created_date'  => $this->model_outlet_transaksi->timezone(),
            'get_userid' => $this->model_per_hari->get_userid($this->model_per_hari->wilayah_nocab($this->session->userdata('id')))
        ];

        $data['proses'] = $this->model_inventory->laporan_po($data);
        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar',$data);
        $this->load->view('template_claim/top_content',$data);
        $this->load->view("inventory/laporan_po_hasil",$data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
    }

    public function export_laporan_po(){
        $id = $this->session->userdata('id');
        $query="
        select * from site.temp_laporan_po a where a.id = $id and a.created_date = (select max(created_date) from site.temp_laporan_po where a.id = $id)
        ";

        $hasil = $this->db->query($query);        
        query_to_csv($hasil,TRUE,"export_laporan_po.csv");
    }

    public function master_product(){
        $data = [
            'id' => $this->session->userdata('id'),
            'url' => 'inventory/master_product/',
            'title' => 'Master Product',
            'get_label' => $this->M_menu->get_label(),
        ];
        $supp = $this->session->userdata('supp');
        $data['header_supp'] = $this->model_outlet_transaksi->get_header_supp($supp);
        $data['proses'] = $this->model_inventory->master_product();
        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar',$data);
        $this->load->view('template_claim/top_content',$data);
        $this->load->view("inventory/master_product",$data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
    }

    public function master_product_detail($kodeprod){
        $data = [
            'id' => $this->session->userdata('id'),
            'url' => 'inventory/master_product_detail_update/',
            'title' => 'Master Product',
            'get_label' => $this->M_menu->get_label(),
        ];
        $data['proses'] = $this->model_inventory->master_product_detail($kodeprod);
        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar',$data);
        $this->load->view('template_claim/top_content',$data);
        $this->load->view("inventory/master_product_detail",$data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
    }

    public function master_product_detail_update(){
        $data = [
            'id' => $this->session->userdata('id'),
            'kodeprod' => $this->input->post('kodeprod'),
            'kodeprod_deltomed' => $this->input->post('kodeprod_deltomed'),
            'berat' => $this->input->post('berat'),
            'volume' => $this->input->post('volume'),
            'satuan' => $this->input->post('satuan'),
            'title' => 'Master Produk',
            'get_label' => $this->M_menu->get_label(),
        ];

        $data['proses'] = $this->model_inventory->master_product_detail_update($data);

    }

    public function monitoring_stock_us(){
        $id = $this->session->userdata('id');
        $get_kode = $this->model_inventory->get_kode($id);
        $code = '';
        foreach($get_kode as $kode)
        {
            $code.=","."'".$kode->kode."'";
        }
        $kode = preg_replace('/,/', '', $code,1);

        $data = [
            'id' => $this->session->userdata('id'),
            'url' => 'inventory/monitoring_stock_us_proses/',
            'title' => 'Purchase Plan Ultra Sakti',
            'get_label' => $this->M_menu->get_label(),
            'get_monitoring_stock_deltomed_last' => $this->model_inventory->get_monitoring_stock_last("005",$kode),
            'url_proses' => 'monitoring_stock_us'
        ];

        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar',$data);
        $this->load->view('template_claim/top_content',$data);
        $this->load->view("inventory/monitoring_stock_us",$data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
    }

    public function monitoring_stock_us_proses(){

        $proses = $this->model_inventory->monitoring_stock_us_proses();
        // echo "proses controller : ".$proses;
        // if ($proses) {
        //     redirect('inventory/monitoring_stock_us');
        // }else{
        //     echo "error. hub IT";
        // }

    }

    public function clear_monitoring(){

        $proses = $this->model_inventory->clear();
        // echo "proses controller : ".$proses;
        // if ($proses) {
        //     redirect('inventory/monitoring_stock_us');
        // }else{
        //     echo "error. hub IT";
        // }

    }

    public function monitoring_stock_us_proses_regenerate($supp){

        $proses = $this->model_inventory->monitoring_stock_us_proses_regenerate($supp);

    }

    public function update_monitoring_stock($kode,$kodeprod,$supp){
        $data = [
            'id' => $this->session->userdata('id'),
            'url' => 'inventory/update_monitoring_stock_proses/',
            'title' => 'Update Estimasi Sales',
            'get_label' => $this->M_menu->get_label(),
            'get_est_sales' => $this->model_inventory->get_est_sales($kode,$kodeprod,$supp),
        ];

        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar',$data);
        $this->load->view('template_claim/top_content',$data);
        $this->load->view("inventory/update_monitoring_stock",$data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
    }

    public function update_monitoring_stock_proses(){

        // echo $this->input->post('kode');
        $data = [
            'id' => $this->session->userdata('id'),
            'est_sales' => $this->input->post('est_sales'),
            'git' => $this->input->post('git'),
            'stock_level' => $this->input->post('stock_level'),
            'kode' => $this->input->post('kode'),
            'kodeprod' => $this->input->post('kodeprod'),
            'supp' => $this->input->post('supp'),
            'created_date' => $this->input->post('created_date'),
        ];

        $proses = $this->model_inventory->update_monitoring_stock_proses($data);
        // if ($proses) {
        //     redirect('inventory/monitoring_stock_us');
        // }else{
        //     echo "error. hub IT";
        // }
    }

    public function export_monitoring_stock($supp){

        $id = $this->session->userdata('id');
        $sql = "
        select kode
        from mpm.t_pp a 
        where a.userid = $id and `status` = 1
        ";
        $proses = $this->db->query($sql)->result();
        foreach ($proses as $key) {
            $kode = $key->kode;
        }

        if (isset($kode)) {
           $kodex = "and kode ='$kode'";
        }else{
            $kodex = "";
        }

        $query="
        select * from db_temp.t_temp_monitoring_stock a 
        where a.created_date = (select max(created_date) from db_temp.t_temp_monitoring_stock) and a.supp = $supp $kodex
        ";

        $hasil = $this->db->query($query);        
        query_to_csv($hasil,TRUE,"export purchase plan.csv");
    }

    public function konfirmasi_po(){
        
        $get_kode_alamat = $this->model_inventory->get_kode_alamat();
        $code = '';
        foreach ($get_kode_alamat as $key) {
            $code.= ","."'".$key->kode_alamat."'";
        }

        $kode_alamat = preg_replace('/,/', '', $code,1);
        // echo $kode_alamat;

        $data = [
            'id'            => $this->session->userdata('id'),
            'url'           => 'master_product/product/',
            'title'         => 'Konfirmasi PO',
            'get_label'     => $this->M_menu->get_label(),
            'get_po'        => $this->model_inventory->get_po($kode_alamat),
        ];
        
        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar',$data);
        $this->load->view('template_claim/top_content',$data);
        $this->load->view('inventory/konfirmasi_po',$data);
        // $this->load->view('master_product/product',$data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
    }

    public function konfirmasi_po_detail($supp,$id){
        
        $data = [
            'id'            => $this->session->userdata('id'),
            'url'           => 'inventory/konfirmasi_po_detail_proses/',
            'title'         => 'Konfirmasi PO',
            'get_label'     => $this->M_menu->get_label(),
            'get_do'        => $this->model_inventory->get_do($supp,$id),
        ];
        
        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar',$data);
        $this->load->view('template_claim/top_content',$data);
        $this->load->view('inventory/konfirmasi_po_detail',$data);
        // $this->load->view('master_product/product',$data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
    }

    public function konfirmasi_po_detail_proses(){

        $request = $this->input->post('options');
        $code = '';
        foreach($request as $kode)
        {
            $code.=",".$kode;
        }

        // var_dump($request);

        $btn_terima = $this->input->post('btn_terima');
        $btn_cancel = $this->input->post('btn_cancel');

        if ($btn_terima == 1) {
            $btn_status = 1;
        }elseif($btn_cancel == 1){
            $btn_status = 0;
        }

        $data = [
            'id_po' => $this->input->post('id_po'),
            'supp' => $this->input->post('supp'),
            'tanggal_terima' => $this->input->post('tanggal_terima'),
            'btn_status' => $btn_status,
            // 'id_detail'  => preg_replace('/,/', '', $code,1),
            'kodeprod'  => preg_replace('/,/', '', $code,1),

        ];
        $proses = $this->model_inventory->konfirmasi_po_detail_proses($data); 

    }    

    public function po_outstanding(){
        // $data = [
        //     'id'            => $this->session->userdata('id'),
        //     'url'           => 'inventory/po_outstanding_proses/',
        //     'title'         => 'PO Outstanding',
        //     'get_label'     => $this->M_menu->get_label(),
        // ];
        
        // $this->load->view('template_claim/top_header');
        // $this->load->view('template_claim/header');
        // $this->load->view('template_claim/sidebar',$data);
        // $this->load->view('template_claim/top_content',$data);
        // $this->load->view('inventory/po_outstanding',$data);
        // $this->load->view('template_claim/bottom_content');
        // $this->load->view('template_claim/footer');

        // if ($this->session->userdata('id')==625) {
        //     # code...
        //     echo "<script>alert('maaf sedang ada maintenance. Segera kami bereskan'); </script>";
        //     redirect('dashboard_dummy','refresh');
        // }

        $data = [
            'id' => $this->session->userdata('id'),
            'url' => 'inventory/po_outstanding_proses/',
            'title' => 'PO Outstanding',
            'get_label' => $this->M_menu->get_label(),
            'get_supp' => $this->model_omzet->getSuppbyid(),
        ];

        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar',$data);
        $this->load->view('template_claim/top_content',$data);
        $this->load->view("inventory/po_outstanding",$data);
        // $this->load->view("sales_omzet/line_sold",$data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');

    }

    public function po_outstanding_proses(){

        $log = [
            'userid'    => $this->session->userdata('id'),
            'menu'      => $this->uri->segment('2'),
            'created_at'=> $this->model_outlet_transaksi->timezone()
        ];
        $this->db->insert('site.log_kunjungan_website', $log);
        
        // die;
        $supp = $this->input->post('supp');
        $get_kode_alamat = $this->model_inventory->get_kode_alamat();
        $code = '';
        foreach ($get_kode_alamat as $key) {
            $code.= ","."'".$key->kode_alamat."'";
        }

        $data = [
            "supp" => $this->input->post('supp'),
            "periode_1" => $this->input->post('periode_1'),
            "periode_2" => $this->input->post('periode_2'),
            "kode_alamat" => preg_replace('/,/', '', $code,1),            
            'created_date'  => $this->model_sales_omzet->timezone(),
        ];

        
            if ($supp == '001') {
                $data = [
                    'id'            => $this->session->userdata('id'),
                    'url'           => 'inventory/po_outstanding_proses/',
                    'title'         => 'PO Outstanding Deltomed',
                    'get_label'     => $this->M_menu->get_label(),
                    "periode_1"     => $this->input->post('periode_1'),
                    "periode_2"     => $this->input->post('periode_2'),
                    'proses'        => $this->model_inventory->po_outstanding_deltomed($data)
                ];
    
                $view = "inventory/po_outstanding_deltomed";
            }elseif($supp == '005'){
                $data = [
                    'id'            => $this->session->userdata('id'),
                    'url'           => 'inventory/po_outstanding_proses/',
                    'title'         => 'PO Outstanding Ultra Sakti',
                    'get_label'     => $this->M_menu->get_label(),
                    "periode_1"     => $this->input->post('periode_1'),
                    "periode_2"     => $this->input->post('periode_2'),
                    'proses'        => $this->model_inventory->po_outstanding_us($data)
                ];
    
                $view = "inventory/po_outstanding_us";
            }elseif($supp == '012'){
                $data = [
                    'id'            => $this->session->userdata('id'),
                    'url'           => 'inventory/po_outstanding_proses/',
                    'title'         => 'PO Outstanding Intrafood',
                    'get_label'     => $this->M_menu->get_label(),
                    "periode_1"     => $this->input->post('periode_1'),
                    "periode_2"     => $this->input->post('periode_2'),
                    'created_date'  => $this->model_outlet_transaksi->timezone(),
                    'proses'        => $this->model_inventory->po_outstanding_intrafood($data)
                ];
    
                $view = "inventory/po_outstanding_intrafood";
            }elseif($supp == '002'){
                $data = [
                    'id'            => $this->session->userdata('id'),
                    'url'           => 'inventory/po_outstanding_proses/',
                    'title'         => 'PO Outstanding Marguna',
                    'get_label'     => $this->M_menu->get_label(),
                    "periode_1"     => $this->input->post('periode_1'),
                    "periode_2"     => $this->input->post('periode_2'),
                    'created_date'  => $this->model_outlet_transaksi->timezone(),
                    'proses'        => $this->model_inventory->po_outstanding_marguna($data)
                ];
    
                $view = "inventory/po_outstanding_marguna";
            }elseif($supp == '004'){
                $data = [
                    'id'            => $this->session->userdata('id'),
                    'url'           => 'inventory/po_outstanding_proses/',
                    'title'         => 'PO Outstanding Jaya',
                    'get_label'     => $this->M_menu->get_label(),
                    "periode_1"     => $this->input->post('periode_1'),
                    "periode_2"     => $this->input->post('periode_2'),
                    'proses'        => $this->model_inventory->po_outstanding_jaya($data)
                ];
    
                $view = "inventory/po_outstanding_jaya";
            }elseif($supp == '013'){
                $data = [
                    'id'            => $this->session->userdata('id'),
                    'url'           => 'inventory/po_outstanding_proses/',
                    'title'         => 'PO Outstanding Strive',
                    'get_label'     => $this->M_menu->get_label(),
                    "periode_1"     => $this->input->post('periode_1'),
                    "periode_2"     => $this->input->post('periode_2'),
                    'proses'        => $this->model_inventory->po_outstanding_strive($data)
                ];
    
                $view = "inventory/po_outstanding_strive";
            }elseif($supp == '014'){
                $data = [
                    'id'            => $this->session->userdata('id'),
                    'url'           => 'inventory/po_outstanding_proses/',
                    'title'         => 'PO Outstanding Hni',
                    'get_label'     => $this->M_menu->get_label(),
                    "periode_1"     => $this->input->post('periode_1'),
                    "periode_2"     => $this->input->post('periode_2'),
                    'proses'        => $this->model_inventory->po_outstanding_hni($data)
                ];
    
                $view = "inventory/po_outstanding_hni";
            }elseif($supp == '015'){
                $data = [
                    'id'            => $this->session->userdata('id'),
                    'url'           => 'inventory/po_outstanding_proses/',
                    'title'         => 'PO Outstanding MDJ',
                    'get_label'     => $this->M_menu->get_label(),
                    "periode_1"     => $this->input->post('periode_1'),
                    "periode_2"     => $this->input->post('periode_2'),
                    'proses'        => $this->model_inventory->po_outstanding_mdj($data)
                ];
    
                $view = "inventory/po_outstanding_mdj";
            }else{
                echo "<script>alert('khusus menu ini, silahkan pilih single principal'); </script>";
                redirect('inventory/po_outstanding','refresh');
            }
   

        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar',$data);
        $this->load->view('template_claim/top_content',$data);
        $this->load->view($view,$data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');

    }

    public function po_outstanding_deltomed_export() {
        
        $id = $this->session->userdata('id');
        $sql = " select  * from db_po.t_po_outstanding_deltomed_print where created_by =$id and created_date = (select max(created_date) from db_po.t_po_outstanding_deltomed_print where created_by = $id)";
        $quer = $this->db->query($sql);

        // echo "<pre>";
        // print_r($sql);
        // echo "</pre>";
    
        query_to_csv($quer,TRUE,'Po_OutStanding_deltomed.csv');
    
    }

    public function po_outstanding_us_export() {
        
        $id = $this->session->userdata('id');
        $sql = " 
        select a.branch_name, a.nama_comp, a.company, a.tglpo, a.bulan_po, a.nopo, a.tipe, a.kodeprod, a.namaprod,
        a.qty_po, a.qty_pemenuhan, '' as harga, '' as value_po, '' as value_pemenuhan, a.berat, a.volume, a.tgldo, a.nodo,
        a.fulfilment, a.leadtime_proses_do, '' as po_ref, a.tanggal_terima, a.leadtime_proses_kirim, a.outstanding_po,
        a.kode_alamat, a.created_date, a.created_by 
        from db_po.t_po_outstanding_us_print a where created_by =$id and created_date = (select max(created_date) from db_po.t_po_outstanding_us_print where created_by = $id)";
        $quer = $this->db->query($sql);
    
        query_to_csv($quer,TRUE,'Po_OutStanding_US.csv');
    
    }
    
    public function po_outstanding_intrafood_export() {
            
        $id = $this->session->userdata('id');
        // $sql = " select  * from db_po.t_po_outstanding_intrafood_print where created_by =$id and created_date = (select max(created_date) from db_po.t_po_outstanding_intrafood_print where created_by = $id)";
        $sql = "
        select 	a.branch_name, a.nama_comp, a.company, a.tglpo, a.nopo, a.tipe,
                a.kodeprod, a.namaprod, a.qty_po_unit, a.qty_po_karton,  a.qty_pemenuhan_karton,
                a.tanggal_kirim, a.est_tanggal_tiba, a.fulfilment_karton, a.leadtime_proses_do,
                a.status_closed, a.tanggal_terima, a.leadtime_proses_kirim, a.outstanding_po_karton
        from db_po.t_po_outstanding_intrafood_print a
        where created_by = $id
        ";
        $quer = $this->db->query($sql);
    
        query_to_csv($quer,TRUE,'Po Outstanding Intrafood.csv');
    
    }

    public function po_outstanding_marguna_export() {
            
        $id = $this->session->userdata('id');
        $sql = " select  * from db_po.t_po_outstanding_marguna_print where created_by =$id and created_date = (select max(created_date) from db_po.t_po_outstanding_marguna_print where created_by = $id)";
        $quer = $this->db->query($sql);
    
        query_to_csv($quer,TRUE,'Po Outstanding Marguna.csv');
    
    }

    public function po_outstanding_jaya_export() {
            
        $id = $this->session->userdata('id');
        $sql = " select  * from db_po.t_po_outstanding_jaya_print where created_by =$id and created_date = (select max(created_date) from db_po.t_po_outstanding_jaya_print where created_by = $id)";
        $quer = $this->db->query($sql);
    
        query_to_csv($quer,TRUE,'Po Outstanding Jaya Agung.csv');
    
    }

    public function po_outstanding_strive_export() {
            
        $id = $this->session->userdata('id');
        $sql = " select  * from db_po.t_po_outstanding_strive_print where created_by =$id and created_date = (select max(created_date) from db_po.t_po_outstanding_strive_print where created_by = $id)";
        $quer = $this->db->query($sql);
    
        query_to_csv($quer,TRUE,'Po Outstanding Strive.csv');
    
    }

    public function po_outstanding_hni_export() {
            
        $id = $this->session->userdata('id');
        $sql = " select  * from db_po.t_po_outstanding_hni_print where created_by =$id and created_date = (select max(created_date) from db_po.t_po_outstanding_hni_print where created_by = $id)";
        $quer = $this->db->query($sql);
    
        query_to_csv($quer,TRUE,'Po Outstanding Hni.csv');
    
    }

    public function po_outstanding_mdj_export() {
            
        $id = $this->session->userdata('id');
        $sql = " select  * from db_po.t_po_outstanding_mdj_print where created_by =$id and created_date = (select max(created_date) from db_po.t_po_outstanding_mdj_print where created_by = $id)";
        $quer = $this->db->query($sql);
    
        query_to_csv($quer,TRUE,'Po Outstanding Mdj.csv');
    
    }

    public function ms_deltomed(){
        
        $get_kode_alamat = $this->model_inventory->get_kode_alamat();
        $code = '';
        foreach ($get_kode_alamat as $key) {
            $code.= ","."'".$key->kode_alamat."'";
        }

        $kode_alamat = preg_replace('/,/', '', $code,1);
        // echo $kode_alamat;

        $data = [
            'id'                => $this->session->userdata('id'),
            // 'url'            => 'inventory/product/',
            'title'             => 'Monitoring Stock',
            'get_label'         => $this->M_menu->get_label(),
            'get_ms_deltomed'   => $this->model_inventory->ms_deltomed($kode_alamat),
        ];
        
        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar',$data);
        $this->load->view('template_claim/top_content',$data);
        $this->load->view('inventory/ms_deltomed',$data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
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
            $kode['kode'],
            $kode['supp'],
            $kode['namasupp'],
            $kode['kodeprod'],
            $kode['namaprod'],
            $kode['isisatuan'],
            $kode['berat'],
            $kode['volume'],
            $kode['harga'],
            $kode['rata'],
            $kode['stock_akhir_unit'],
            $kode['git'],
            $kode['total_stock'],
            $kode['sales_berjalan'],
            $kode['estimasi_sales'],
            $kode['potensi_sales'],
            $kode['stock_berjalan'],
            $kode['doi'],
            $kode['stock_ideal'],
            $kode['stock_ideal_unit'],
            $kode['pp'],
            $kode['estimasi_saldo_akhir'],
            $kode['estimasi_doi_akhir'],
            $kode['pp_karton'],
            $kode['pp_kg'],
            $kode['pp_volume'],
            $kode['pp_value'],
            $kode['lastupload']
          );
        $nomor_urut++;
        }

        echo json_encode($output);
    }

    public function ms_edit($signature){
    $data = [
        'id' => $this->session->userdata('id'),
        'url' => 'inventory/ms_edit_proses/',
        'title' => 'Monitoring Stock',
        'get_label' => $this->M_menu->get_label(),
    ];

    // die;
    $supp = $this->session->userdata('supp');
    $data['get_ms'] = $this->model_inventory->get_ms($signature);
    // die;
    $this->load->view('template_claim/top_header');
    $this->load->view('template_claim/header');
    $this->load->view('template_claim/sidebar',$data);
    $this->load->view('template_claim/top_content',$data);
    $this->load->view("inventory/ms_edit",$data);
    $this->load->view('template_claim/bottom_content');
    $this->load->view('template_claim/footer');
  }

  public function ms_edit_proses(){
    $data = [
        'signature' => $this->input->post('signature'),
        'kode' => $this->input->post('kode'),
        'kodeprod' => $this->input->post('kodeprod'),
        'stock_ideal' => $this->input->post('stock_ideal'),
        'estimasi_sales' => $this->input->post('estimasi_sales'),
        'created_date'  => $this->model_sales_omzet->timezone()
    ];
    $proses = $this->model_inventory->update_ms($data);

  }

  public function konsolidasi_ms(){
    $data = [
        'get_log_ms' => $this->model_inventory->get_log_ms()
    ];
    $proses = $this->model_inventory->konsolidasi_ms($data);

  }

  public function export_ms(){
    $get_kode_alamat = $this->model_inventory->get_kode_alamat();
    $code = '';
    foreach ($get_kode_alamat as $key) {
        $code.= ","."'".$key->kode_alamat."'";
    }

    $kode_alamat = preg_replace('/,/', '', $code,1);
    // echo $kode_alamat;

    $query="
    select 	a.branch_name, a.nama_comp, a.kode, a.supp, a.namasupp, a.kodeprod, a.namaprod, a.isisatuan, a.berat, a.volume, a.harga, a.lastupload,
            a.rata, a.stock_akhir_unit, a.git, a.total_stock, a.sales_berjalan as actual_sales, a.estimasi_sales, a.potensi_sales, a.stock_berjalan, a.doi as estimasi_doi_berjalan,
            a.stock_ideal, a.stock_ideal_unit, a.pp as suggest_kebutuhan_stock, a.estimasi_saldo_akhir as estimasi_stock_akhir_bulan, a.estimasi_doi_akhir as estimasi_doi_akhir_bulan, a.pp_karton,
            a.pp_kg, a.pp_volume, a.pp_value, a.created_date, a.last_updated, a.last_updated_by
    from db_master_data.t_temp_monitoring_stock_suggest_po a
    where a.kode in ($kode_alamat)
    order by branch_name, nama_comp, kodeprod
    ";

    $hasil = $this->db->query($query);
    query_to_csv($hasil,TRUE,"export monitoring stock.csv");
    }

    public function export_template_ms(){

        $supp = $this->input->post('supp');
        // echo "supp : ".$supp;

        $get_kode_alamat = $this->model_inventory->get_kode_alamat();
        $code = '';
        foreach ($get_kode_alamat as $key) {
            $code.= ","."'".$key->kode_alamat."'";
        }

        $kode_alamat = preg_replace('/,/', '', $code,1);
        // echo $kode_alamat;

        $query="
        select 	a.branch_name, a.nama_comp, a.kode, a.supp, a.namasupp, a.kodeprod, a.namaprod, a.status, a.isisatuan, a.berat, a.volume, a.harga, a.lastupload,
                a.rata, a.stock_akhir_unit, a.git, a.total_stock, a.sales_berjalan as actual_sales, a.estimasi_sales as estimasi_sales_isi_disini, a.potensi_sales, a.stock_berjalan, a.doi as estimasi_doi_berjalan,
                a.stock_ideal as stock_ideal_isi_disini, a.stock_ideal_unit, a.pp as suggest_kebutuhan_stock, a.estimasi_saldo_akhir as estimasi_stock_akhir_bulan, a.estimasi_doi_akhir as estimasi_doi_akhir_bulan, a.pp_karton,
                a.pp_kg, a.pp_volume, a.pp_value, a.signature, a.created_date, a.last_updated, a.last_updated_by, a.estimasi_sales_value, a.actual_sales_value, a.akurasi, a.achievement
        from db_master_data.t_temp_monitoring_stock_suggest_po a
        where a.supp = $supp and a.kode in ($kode_alamat)
        order by branch_name, nama_comp, kodeprod
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        $hasil = $this->db->query($query);
        query_to_csv($hasil,TRUE,"export monitoring stock.csv");

        
    }

    public function export_template_setting_product(){


        $query="
        select b.namasupp, a.kodeprod, a.namaprod, '' as status_isi_disini
        from mpm.tabprod a LEFT JOIN mpm.tabsupp b 
            on a.supp = b.supp
        where a.active= 1
        ORDER BY a.supp, a.kodeprod
        ";

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        $hasil = $this->db->query($query);
        query_to_csv($hasil,TRUE,"setting status product.csv");

        
    }

    public function import_ms(){
        $time = date('his');
        $created_by = $this->session->userdata('id');
        $tgl = $this->model_outlet_transaksi->timezone();
        $this->load->library('upload'); // Load librari upload        
        $config['upload_path'] = './assets/uploads/import_ms/';  
        $config['allowed_types'] = '*';    
        $config['max_size']  = '5000';    
        $config['overwrite'] = false;   
        $this->upload->initialize($config); 
    
        // Load konfigurasi uploadnya    
        if($this->upload->do_upload('file'))
        { 
          $upload_data = $this->upload->data();
          $filename = $upload_data['file_name'];
          $this->load->library('excel');
          $object = PHPExcel_IOFactory::load("assets/uploads/import_ms/$filename");

            foreach($object->getWorksheetIterator() as $worksheet)
            {
                $highestRow = $worksheet->getHighestRow();
                $highestColumn = $worksheet->getHighestColumn();

                for($row=2; $row<=$highestRow; $row++)
                {
                    $site_code      = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
                    $kodeprod       = $worksheet->getCellByColumnAndRow(5, $row)->getValue();
                    $estimasi_sales = $worksheet->getCellByColumnAndRow(18, $row)->getValue();
                    $stock_ideal    = $worksheet->getCellByColumnAndRow(22, $row)->getValue();        
                    $signature      = $worksheet->getCellByColumnAndRow(31, $row)->getValue();     

                    $data[] = array(
                        'site_code'		    =>	$site_code,
                        'kodeprod'		    =>	$kodeprod,
                        'estimasi_sales'    =>	$estimasi_sales,
                        'stock_ideal'	    =>	$stock_ideal,
                        'signature'	        =>	$signature,
                        'created_at'	    =>	$tgl,
                        'created_by'	    =>	$created_by,
                        'filename'  	    =>	$filename,
                    );
                }
            }
            $insert = $this->model_inventory->insert_ms($data, 'db_master_data.t_import_ms');
        }else{  
            $return = array('result' => 'failed', 'file' => '', 'error' => $this->upload->display_errors());      
            echo "<pre>";
            print_r($return);
            echo "</pre>";
        }
        $update = "
            update db_master_data.t_import_ms a 
            set a.kodeprod = concat('0', a.kodeprod) 
            where length(a.kodeprod) = 5 and a.created_by = $created_by and a.created_at = '$tgl'
        ";

        $proses_update = $this->db->query($update);

        $data = [
            'id'            => $this->session->userdata('id'),
            'url'           => 'inventory/save_import_ms',
            'title'         => 'Review Import Monitoring Stock | Harap Review Kembali Data Anda',
            'get_label'     => $this->M_menu->get_label(),
            'get_import_ms' => $this->model_inventory->get_import_ms($created_by, $tgl),
            'created_at'    => $tgl,
            'created_by'    => $created_by
        ];

        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar',$data);
        $this->load->view('template_claim/top_content',$data);
        $this->load->view('inventory/review_import_ms',$data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
    }

    public function import_ms_setting_product(){
        $time = date('his');
        $created_by = $this->session->userdata('id');
        $tgl = $this->model_outlet_transaksi->timezone();
        $this->load->library('upload'); // Load librari upload        
        $config['upload_path'] = './assets/uploads/import_ms/';  
        $config['allowed_types'] = '*';    
        $config['max_size']  = '5000';    
        $config['overwrite'] = false;   
        $this->upload->initialize($config); 
    
        // Load konfigurasi uploadnya    
        if($this->upload->do_upload('file'))
        { 
          $upload_data = $this->upload->data();
          $filename = $upload_data['file_name'];

        //   echo "filename : ".$filename ;
        //   die;
          $this->load->library('excel');
          $object = PHPExcel_IOFactory::load("assets/uploads/import_ms/$filename");

            foreach($object->getWorksheetIterator() as $worksheet)
            {
                $highestRow = $worksheet->getHighestRow();
                $highestColumn = $worksheet->getHighestColumn();

                for($row=2; $row<=$highestRow; $row++)
                {
                    $kodeprod       = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
                    $status = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
                 
                    $data[] = array(
                        'kodeprod'		    =>	$kodeprod,
                        'status'            =>	$status,
                        'created_at'	    =>	$tgl,
                        'created_by'	    =>	$created_by,
                        'filename'  	    =>	$filename,
                    );
                }
            }
            $insert = $this->model_inventory->insert_ms($data, 'db_master_data.t_import_ms_setting_product');
        }else{  
            $return = array('result' => 'failed', 'file' => '', 'error' => $this->upload->display_errors());      
            echo "<pre>";
            print_r($return);
            echo "</pre>";
        }
        $update = "
            update db_master_data.t_import_ms_setting_product a 
            set a.kodeprod = concat('0', a.kodeprod) 
            where length(a.kodeprod) = 5 and a.created_by = $created_by and a.created_at = '$tgl'
        ";

        $proses_update = $this->db->query($update);

        $data = [
            'id'            => $this->session->userdata('id'),
            'url'           => 'inventory/save_import_ms_setting_product',
            'title'         => 'Review Product Setting | Harap Review Kembali Data Anda',
            'get_label'     => $this->M_menu->get_label(),
            'get_import_ms' => $this->model_inventory->get_import_ms_setting_product($created_by, $tgl),
            'created_at'    => $tgl,
            'created_by'    => $created_by
        ];

        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar',$data);
        $this->load->view('template_claim/top_content',$data);
        $this->load->view('inventory/review_import_ms_setting_product',$data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
    }

    public function save_import_ms(){
        $created_at = $this->input->post('created_at');
        $created_by = $this->input->post('created_by');

        $sql = "
            select  *
            from    db_master_data.t_import_ms a
            where   (a.estimasi_sales is not null and a.estimasi_sales <> '0') and a.stock_ideal is not null and 
                    a.created_by = $created_by and a.created_at = '$created_at'
        ";
        $get_import_ms = $this->db->query($sql)->result();

        echo "<pre>";
        print_r($sql);
        echo "</pre>";

        $no = 1;
        foreach ($get_import_ms as $key) {

            echo "no:".$no++;
            echo " | site_code:".$key->site_code;
            echo " | kodeprod:".$key->kodeprod;
            echo " | signature:".$key->signature;
            echo " | estimasi_sales:".$key->estimasi_sales;
            echo " | stock_ideal:".$key->stock_ideal." <i>updated</i><hr>";

            
            $sql = "
            update db_master_data.t_temp_monitoring_stock_suggest_po a 
                set a.estimasi_sales = $key->estimasi_sales, 
                a.stock_ideal = $key->stock_ideal,
                a.last_updated = '$created_at',
                a.last_updated_by = $created_by
            where a.signature = '$key->signature' and a.kode = '$key->site_code' and a.kodeprod = '$key->kodeprod'";

            $proses = $this->db->query($sql);

            // echo "<pre>";
            // print_r($sql);
            // echo "</pre>";
            // die; 

            if ($proses) {
                $proses = $this->db->query($sql);
                if ($proses) {
                    // jika berhasil update, maka insert ke log
                    $update_log = "
                        update db_master_data.t_temp_monitoring_stock_log a 
                        set a.status = 0
                        where a.signature = '$key->signature' and a.kode = '$key->site_code' and a.kodeprod = '$key->kodeprod'
                    ";
                    $proses_update_log = $this->db->query($update_log);
                    $insert_log = "
                        insert into db_master_data.t_temp_monitoring_stock_log
                        select '',$key->estimasi_sales, $key->stock_ideal, '$key->site_code', '$key->kodeprod', '$key->signature', '$created_at', $created_by, 1
                    ";
                    $proses = $this->db->query($insert_log);
    
                    // echo "<script>alert('update berhasil. anda akan di arahkan ke halaman awal. Jika ingin mengupdate seluruh data, klik tombol konsolidasi'); window.location = 'ms_deltomed/';</script>";
                }else{
                    echo "<script>alert('update gagal di site_code : '.'$key->site_code'.' kodeproduk : '.'$key->kodeprod'. Silahkan Capture info ini dan kabari ini ke IT (suffy)'); window.location = 'ms_deltomed/';</script>";
                }
            }
            // echo "<script>alert('update berhasil .. 1. anda akan di arahkan ke halaman awal. Jika ingin mengupdate seluruh kolom, klik tombol konsolidasi'); window.location = 'ms_deltomed/';</script>";
        }
        echo "<script>alert('update berhasil .. 2. anda akan di arahkan ke halaman awal. Jika ingin mengupdate seluruh kolom, klik tombol konsolidasi'); window.location = 'ms_deltomed/';</script>";
    }

    public function save_import_ms_setting_product(){
        $created_at = $this->input->post('created_at');
        $created_by = $this->input->post('created_by');

        $truncate = $this->db->query("truncate db_master_data.t_import_ms_setting_product_result");

        $sql = "
            insert into db_master_data.t_import_ms_setting_product_result
            select  *
            from    db_master_data.t_import_ms_setting_product a
            where   a.created_by = $created_by and a.created_at = '$created_at'
        ";
        $get_import_ms = $this->db->query($sql);

        echo "<script>alert('update berhasil ... anda akan di arahkan ke halaman awal.'); window.location = 'ms_deltomed/';</script>";
    }

}
?>
