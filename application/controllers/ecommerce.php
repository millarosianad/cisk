<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Ecommerce extends MY_Controller
{
    function Ecommerce()
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
        $this->load->model('M_ecommerce');
        $this->load->model('M_menu');
        $this->load->model('model_sales_omzet');
        $this->load->database();
    }

    public function get_transaksi()
    {
        $orderID = $_GET['id'];
        $data['edit']   = $this->M_ecommerce->get_transaksi($orderID)->row();
        echo json_encode($data);
    }

    public function get_subbranch()
    {
        $site_code = $_GET['id'];
        $data['edit']   = $this->M_ecommerce->get_subbranch($site_code)->row();
        echo json_encode($data);
    }

    function transaksi(){
        $get_site_code = $this->M_ecommerce->get_site_code($this->session->userdata('username'));

        $site_code = '';
        foreach($get_site_code as $a)
        {
            $site_code.=",$a->kode_alamat";
        }

        $site_code = preg_replace('/,/', '', $site_code,1);

        $data = [
            'id' => $this->session->userdata('id'),
            'url' => 'ecommerce/update_transaksi/',
            'title' => 'Transaksi Apps Semut Gajah',
            'get_label' => $this->M_menu->get_label(),
            'transaksi' => $this->M_ecommerce->get_transaksi('',$site_code)->result()
        ];
        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar',$data);
        $this->load->view('template_claim/top_content',$data);
        $this->load->view('ecommerce/transaksi',$data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
    }

    function detail_transaksi($invoice){
        $data = [
            'id' => $this->session->userdata('id'),
            'url' => 'ecommerce/update_transaksi/',
            'title' => 'Apps Semut Gajah | Detail Transaksi',
            'get_label' => $this->M_menu->get_label(),
            'transaksi' => $this->M_ecommerce->get_transaksi($invoice)->result()
        ];
        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar',$data);
        $this->load->view('template_claim/top_content',$data);
        $this->load->view('ecommerce/detail_transaksi',$data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
    }

    function update_transaksi(){
        $order = $this->input->post('order');
        $data = [
            'status' => $this->input->post('status'),
            'last_updated' => $this->model_sales_omzet->timezone2()
        ];
        $update = $this->db->update('db_master_data.t_orders', $data, "order_id = $order");

        $message = "Data Berhasil diUpdate !";
        echo "<script type='text/javascript'>alert('$message');
            window.location.href = 'transaksi';
            </script>";
    }

    function kontak_subbranch(){

        $get_site_code = $this->M_ecommerce->get_site_code($this->session->userdata('username'));

        $site_code = '';
        foreach($get_site_code as $a)
        {
            $site_code.=",$a->kode_alamat";
        }

        $site_code = preg_replace('/,/', '', $site_code,1);

        $data = [
            'id' => $this->session->userdata('id'),
            'url' => 'ecommerce/update_subbranch/',
            'title' => 'Kontak Branch',
            'get_label' => $this->M_menu->get_label(),
            'subbranch' => $this->M_ecommerce->get_subbranch($site_code)->result()
        ];
        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar',$data);
        $this->load->view('template_claim/top_content',$data);
        $this->load->view('ecommerce/kontak_subbranch',$data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
    }

    function update_kontak_subbranch(){
        $site = $this->input->post('site_code');
        $data = [
            'status_ho' => $this->input->post('status'),
            'telp_wa' => $this->input->post('telp'),
            'last_updated' => $this->model_sales_omzet->timezone2()
        ];
        $update = $this->db->update('db_master_data.t_site', $data, "site_code = '$site'");

        $message = "Data Berhasil diUpdate !";
        echo "<script type='text/javascript'>alert('$message');
            window.location.href = 'kontak_subbranch';
            </script>";
    }

    function otp_user(){
        $get_site_code = $this->M_ecommerce->get_site_code($this->session->userdata('username'));

        $site_code = '';
        foreach($get_site_code as $a)
        {
            $site_code.=",$a->kode_alamat";
        }

        $site_code = preg_replace('/,/', '', $site_code,1);

        $data = [
            'id' => $this->session->userdata('id'),
            'title' => 'Manage Customer / Toko (Apps Semut Gajah)',
            'get_label' => $this->M_menu->get_label(),
            // 'otp' => $this->M_ecommerce->get_otp($site_code)->result()
        ];
        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar',$data);
        $this->load->view('template_claim/top_content',$data);
        $this->load->view('ecommerce/otp',$data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
    }

    function ambil_data(){

        $this->load->model('model_inventory');
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
            select * from db_master_data.t_customer a
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
            select * from db_master_data.t_customer a
            where a.kode in ($kode_alamat) and (kode_lang like '%$search%' or nama_lang like '%$search%' or nama_comp like '%$search%')
            order by a.kode, a.kode_lang        
            limit $start,$length        
        ";
        }else{

            $sql = "
                select * from db_master_data.t_customer a
                where a.kode in ($kode_alamat)    
                order by a.kode, a.kode_lang         
                limit $start,$length        
            ";
        }
        $query = $this->db->query($sql);

        if($search!=""){
        $this->db->like("nama_lang",$search);
        // $jum=$this->db->get('mpm.tbl_tabcomp');

        $sql = "select * from db_master_data.t_customer a";
        $jum = $this->db->query($sql);

        $output['recordsTotal']=$output['recordsFiltered']=$jum->num_rows();
        }
        $nomor_urut=$start+1;

        foreach ($query->result_array() as $kode) {

            $y = anchor(base_url().'ecommerce/detail_otp/'.$kode['kode_lang'],'click here',[
                'class' => 'btn btn-primary',
                'role'  => 'button',
                'target' => 'blank'
            ]);

          $output['data'][]=array( 
            // $y,
            // $nomor_urut,
            // '<button class="fa fa-edit fa-xl btn-info" target="blank" id="testOnclick" onclick="getEditIDProduct('.$kode['kodeprod'].')"></button>',
            $kode['branch_name'],
            $kode['nama_comp'],
            $kode['kode_lang'],
            $kode['nama_lang'],
            $kode['alamat'],
            $kode['code_approval'],
            $y 
          );
        $nomor_urut++;
        }

        echo json_encode($output);
    }

    public function detail_otp($kode_lang){
        $data = [
            'id' => $this->session->userdata('id'),
            'title' => 'Detail OTP',
            'get_label' => $this->M_menu->get_label(),
            'get_detail_otp' => $this->M_ecommerce->get_detail_otp($kode_lang)
        ];
        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar',$data);
        $this->load->view('template_claim/top_content',$data);
        $this->load->view('ecommerce/detail_otp',$data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
    }

    public function log_otp(){
        $this->load->model('model_inventory');
        $get_kode_alamat = $this->model_inventory->get_kode_alamat();
        $code = '';
        foreach ($get_kode_alamat as $key) {
            $code.= ","."'".$key->kode_alamat."'";
        }
        $kode_alamat = preg_replace('/,/', '', $code,1);

        // echo "kode_alamat : ".$kode_alamat;
        // die;

        $data = [
            'id' => $this->session->userdata('id'),
            'title' => 'Log OTP',
            'get_label' => $this->M_menu->get_label(),
            'get_log_otp' => $this->M_ecommerce->get_log_otp($kode_alamat)
        ];
        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar',$data);
        $this->load->view('template_claim/top_content',$data);
        $this->load->view('ecommerce/log_otp',$data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
    }

    public function export_csv()
    {
        $this->load->model('model_inventory');
        $get_kode_alamat = $this->model_inventory->get_kode_alamat();
        $code = '';
        foreach ($get_kode_alamat as $key) {
            $code.= ","."'".$key->kode_alamat."'";
        }
        $kode_alamat = preg_replace('/,/', '', $code,1);

        $kondisi = $this->uri->segment('3');
        // var_dump($kondisi);die;
        if($kondisi == 'kontak'){
            // $code = explode(",",$code);
            // $this->db->where_in('site_code', $code);
            // $result = $this->db->get('db_master_data.t_site');
            // query_to_csv($result,TRUE,"kontak_branch.csv");
            $sql = "
                SELECT * FROM db_master_data.t_site
                where site_code in ($kode_alamat)
            ";
            $result = $this->db->query($sql);
            query_to_csv($result,TRUE,"kontak_branch.csv");

        }elseif($kondisi == 'otp'){
            // $code = explode(",",$site_code);
            // $this->db->where_in('kode', $code);
            // $this->db->select("branch_name as branch,nama_comp as subbranch,kode_lang as customer_code_mpm,right(`kode_lang`,2)` as customer_code,alamat,code_approval");
            // $result = $this->db->get('db_master_data.t_customer');
            $sql = "
                select branch_name as branch,nama_comp as subbranch,kode_lang as customer_code_mpm,right(kode_lang,6) as customer_code,alamat,code_approval
                from db_master_data.t_customer a
                where a.kode in ($kode_alamat)
            ";
            $result = $this->db->query($sql);
            query_to_csv($result,TRUE,"otp_user.csv");
        }elseif($kondisi == 'log_otp'){
            $sql = "
            select b.branch_name, b.nama_comp, a.customer_code as customer_code_mpm, right(a.customer_code,6) as customer_code, 
            a.email_phone, a.type, a.otp_code, a.created_at, a.valid_until
                from db_master_data.t_otp a LEFT JOIN
                (
                    select a.site_code, left(a.site_code, 3) as kode_comp,  a.branch_name, a.nama_comp
                    from db_master_data.t_site a
                )b on left(a.customer_code,3) = b.kode_comp
                where a.`server` = 'live'  and b.site_code in ($kode_alamat)
                ORDER BY a.id desc
            ";
            $result = $this->db->query($sql);
            query_to_csv($result,TRUE,"log otp.csv");
        }elseif($kondisi == 'transaksi'){
            $sql = "
            select b.branch_name, b.nama_comp, a.invoice, a.customer_id, a.`name`, a.phone, a.address, a.site_code, a.created_at, a.payment_final, a.status_update_erp, a.last_updated_erp, a.order_id
            from db_master_data.t_orders a INNER join 
            (
                select a.site_code, a.branch_name, a.nama_comp
                from db_master_data.t_site a
                
            )b on a.site_code = b.site_code
            where a.`server` = 'live' and a.site_code in ($kode_alamat)
            ORDER BY a.id desc
            ";
            $result = $this->db->query($sql);
            query_to_csv($result,TRUE,"transaksi.csv");
        }
    }

    public function export_xls()
    {
        // $hasil = $this->db->query("
        //     select  substr(kode,1,3) as kode_comp, kode, branch_name,nama_comp,
        //             unit_1,unit_2,unit_3,unit_4,unit_5,unit_6,
        //             unit_7,unit_8,unit_9,unit_10,unit_11,unit_12,
        //             value_1,value_2,value_3,value_4,value_5,value_6,
        //             value_7,value_8,value_9,value_10,value_11,value_12, 
        //             ot_1,ot_2,ot_3,ot_4,ot_5,ot_6,
        //             ot_7,ot_8,ot_9,ot_10,ot_11,ot_12,
        //             ec_1,ec_2,ec_3,ec_4,ec_5,ec_6,
        //             ec_7,ec_8,ec_9,ec_10,ec_11,ec_12               
        //     from   db_temp.t_temp_soprod
        //     where  id = $id and created_date = (select max(created_date) from db_temp.t_temp_soprod where id = $id)
        //     ORDER BY urutan asc");   
        
        //     $this->excel_generator->set_query($hasil);
        //     $this->excel_generator->set_header(array
        //     (
        //         'kode_comp', 'kode', 'branch_name','nama_comp',
        //         'unit_1','unit_2','unit_3','unit_4','unit_5','unit_6',
        //         'unit_7','unit_8','unit_9','unit_10','unit_11','unit_12',
        //         'value_1','value_2','value_3','value_4','value_5','value_6',
        //         'value_7','value_8','value_9','value_10','value_11','value_12', 
        //         'ot_1','ot_2','ot_3','ot_4','ot_5','ot_6',
        //         'ot_7','ot_8','ot_9','ot_10','ot_11','ot_12',
        //         'ec_1','ec_2','ec_3','ec_4','ec_5','ec_6',
        //         'ec_7','ec_8','ec_9','ec_10','ec_11','ec_12'
        //     ));
        //     $this->excel_generator->set_column(array
        //     ( 
        //         'kode_comp', 'kode', 'branch_name','nama_comp',
        //         'unit_1','unit_2','unit_3','unit_4','unit_5','unit_6',
        //         'unit_7','unit_8','unit_9','unit_10','unit_11','unit_12',
        //         'value_1','value_2','value_3','value_4','value_5','value_6',
        //         'value_7','value_8','value_9','value_10','value_11','value_12', 
        //         'ot_1','ot_2','ot_3','ot_4','ot_5','ot_6',
        //         'ot_7','ot_8','ot_9','ot_10','ot_11','ot_12',
        //         'ec_1','ec_2','ec_3','ec_4','ec_5','ec_6',
        //         'ec_7','ec_8','ec_9','ec_10','ec_11','ec_12'
        //     ));
        //     $this->excel_generator->set_width(array(10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10));
        //     //$this->excel_generator->exportTo2007('Omzet'.'_'.$tahun.'_'.$supplier);   
        //     $this->excel_generator->exportTo2007('Sell_Out_Product'); 
    }

    public function export()
    {
        $this->load->model('model_inventory');
        $get_kode_alamat = $this->model_inventory->get_kode_alamat();
        $code = '';
        foreach ($get_kode_alamat as $key) {
            $code.= ","."'".$key->kode_alamat."'";
        }
        $kode_alamat = preg_replace('/,/', '', $code,1);

        $format = $this->input->post('format');
        $from = $this->input->post('from');
        $to = $this->input->post('to');

        if ($format == 1) {

        $sql = "
            select a.site_code, b.branch_name, b.nama_comp, sum(a.payment_final) as payment_final
            from db_master_data.t_orders a LEFT JOIN 
            (
                select a.site_code, a.branch_name, a.nama_comp
                from db_master_data.t_site a 
            )b on a.site_code = b.site_code
            where a.`server` = 'live' and a.status_update_erp <> 10 and created_at BETWEEN '$from' and '$to' and a.site_code in ($kode_alamat)
            GROUP BY b.nama_comp
            ORDER BY b.branch_name, b.nama_comp
        ";

        $result = $this->db->query($sql);
        query_to_csv($result,TRUE,"breakdown subbranch.csv");

        }elseif ($format == 2) {
            
            $sql = "
            select  a.site_code, c.branch_name, c.nama_comp, a.invoice, a.customer_id, a.name, a.phone, 
                    a.address, a.provinsi, a.kota, a.kecamatan, a.kelurahan, a.kode_pos, a.payment_method, a.payment_final, a.created_at, a.status_update_erp,
                    b.invoice, b.product_id, b.small_qty, b.qty_konversi, b.harga_product, b.harga_product_konversi, b.item_disc, b.total_price
            from db_master_data.t_orders a INNER JOIN 
            (
                select a.invoice, a.product_id, a.small_qty, a.qty_konversi, a.harga_product, a.harga_product_konversi, a.item_disc, a.total_price
                from db_master_data.t_orders_detail a
            )b on a.invoice = b.invoice LEFT JOIN
            (
                select a.site_code, a.branch_name, a.nama_comp
                from db_master_data.t_site a 
            )c on a.site_code = c.site_code
            where a.`server` = 'live' and created_at BETWEEN '$from' and '$to' and a.site_code in ($kode_alamat)

            ";

            $result = $this->db->query($sql);
            query_to_csv($result,TRUE,"raw data.csv");

        }
        
        $result = $this->db->query($sql);
    }

    public function test_omdb(){
        $this->load->view('test/test_omdb');
    }
}