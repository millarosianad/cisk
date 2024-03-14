<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Report_sales_per_hari extends MY_Controller
{
    var $nocab;
    var $options;
    var $image_properties_pdf = array(
          'src' => 'assets/css/images/pdf.png',
          'alt' => 'Printing Document',
          'class' => 'post_images',
          'width' => '50',
          'height' => '50',
          'title' => 'Printer',
          'border' => 0,
    );
    var $image_properties_excel = array(
          'src' => 'assets/css/images/excel.png',
          'alt' => 'Printing Document',
          'class' => 'post_images',
          'width' => '50',
          'height' => '50',
          'title' => 'Printer',
          'border' => 0,
    );
    var $querymenu;
    var $attr = array(
              'width'      => '800',
              'height'     => '600',
              'scrollbars' => 'yes',
              'status'     => 'yes',
              'resizable'  => 'yes',
              'screenx'   =>  '\'+((parseInt(screen.width) - 800)/2)+\'',
              'screeny'   =>  '\'+((parseInt(screen.height) - 600)/2)+\'',

            );

    function Report_sales_per_hari()
    {
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }

        set_time_limit(0);
        $this->load->library(array('table','template','Excel_generator'));
        $this->load->helper('url');
        $this->load->helper('csv');
        $this->load->model('model_omzet');
        $this->load->model('model_per_hari');
        $this->load->model('M_menu');
        $this->load->database();
        $this->querymenu='select a.id,a.menuview,a.target,a.groupname from mpm.menu a inner join mpm.menudetail b on a.id=b.menuid where b.userid='. $this->session->userdata('id') . ' and active=1 order by a.groupname,a.menuview ';
    }
    function index()
    {

        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }

    }

    private function template($view,$data)
    {
        // $this->template->set_title('MPM');
        // $this->template->add_js('modules/skeleton.js');
        // $this->template->add_css('modules/skeleton.css');
        $this->template->load_view($view, $data);
    }

    public function input_data_hari_old(){

        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
            $data['page_content'] = 'report_sales_per_hari/input_data';
            $data['url'] = 'report_sales_per_hari/input_data_hari_hasil/';
            $data['page_title'] = 'Report Sales per hari';
            $data['query'] = $this->model_omzet->getSuppbyid();
            $data['menu']=$this->db->query($this->querymenu);
            $this->template($data['page_content'],$data);  
    }

    public function input_data_hari(){        
        $data = [
            'id' => $this->session->userdata('id'),
            'url' => 'report_sales_per_hari/input_data_hari_hasil/',
            'title' => 'Sales dan Outlet Transaksi Harian',
            'get_label' => $this->M_menu->get_label(),
            'get_supp' => $this->model_omzet->getSuppbyid(),
        ];
        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar',$data);
        $this->load->view('template_claim/top_content',$data);
        $this->load->view('report_sales_per_hari/input_data_hari',$data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
    }

    public function input_data_hari_hasil(){
        $data = [
            'id'        => $this->session->userdata('id'),
            'url'       => 'report_sales_per_hari/input_data_hari_hasil/',
            'get_label' => $this->M_menu->get_label(),
            'get_supp'  => $this->model_omzet->getSuppbyid(),
            'supp'      => $this->input->post('supp'),
            'tahun'     => $this->input->post('tahun'),
            'bulan'     => $this->input->post('bulan'),
            'group'     => $this->input->post('group'),
            'tipe_1'    => $this->input->post('tipe_1'),
            'tipe_2'    => $this->input->post('tipe_2'),
            'tipe_3'    => $this->input->post('tipe_3'),
        ];

        $group = $this->input->post('group');
        $supp = $this->input->post('supp');
        $tipe_1 = $this->input->post('tipe_1');
        $tipe_2 = $this->input->post('tipe_2');
        $tipe_3 = $this->input->post('tipe_3');

        $data['bln'] = $this->input->post('bulan');
        $data['namasupp'] = $this->model_per_hari->get_namasupp($supp);
        $data['namagroup'] = $this->model_per_hari->get_namagroup($group);

        if ($group == '0') {
            $data['cari_kodeprod'] = $this->model_per_hari->cari_kodeprod_supp($supp);
        }else{
            $data['cari_kodeprod'] = $this->model_per_hari->cari_kodeprod($group);
        }

        $data['proses'] = $this->model_per_hari->input_data_hari($data);

        if ($tipe_1 == 1 && $tipe_2 == 1 && $tipe_3 == 1) {
            $data['page_content'] = 'report_sales_per_hari/input_data_hari_hasil_kodeprod_kodesalur_kode_type';
            $view_content = 'input_data_hari_hasil_kodeprod_kodesalur_kode_type';
            $data['title'] = 'Sales dan Outlet Transaksi Harian - breakdown kodeproduk, class & tipe outlet';
        }elseif($tipe_1 == 1 && $tipe_2 == 1 && $tipe_3 == 0){
            $view_content = 'input_data_hari_hasil_kodeprod_kodesalur';
            $data['title'] = 'Sales dan Outlet Transaksi Harian - breakdown kodeproduk & class outlet';
        }elseif($tipe_1 == 1 && $tipe_2 == 0 && $tipe_3 == 1){
            $view_content = 'input_data_hari_hasil_kodeprod_kode_type';
            $data['title'] = 'Sales dan Outlet Transaksi Harian - breakdown kodeproduk & tipe outlet';
        }elseif($tipe_1 == 1 && $tipe_2 == 0 && $tipe_3 == 0){
            $view_content = 'input_data_hari_hasil_kodeprod';
            $data['title'] = 'Sales dan Outlet Transaksi Harian - breakdown kodeproduk';           
        }elseif($tipe_1 == 0 && $tipe_2 == 1 && $tipe_3 == 0){
            $view_content = 'input_data_hari_hasil_kodesalur';
            $data['title'] = 'Sales dan Outlet Transaksi Harian - breakdown class outlet';
        }elseif($tipe_1 == 0 && $tipe_2 == 0 && $tipe_3 == 1){
            $view_content = 'input_data_hari_hasil_kode_type';
            $data['title'] = 'Sales dan Outlet Transaksi Harian - breakdown type outlet';
        }elseif($tipe_1 == 0 && $tipe_2 == 1 && $tipe_3 == 1){
            $view_content = 'input_data_hari_hasil_kodesalur_kode_type';
            $data['title'] = 'Sales dan Outlet Transaksi Harian - breakdown class & tipe outlet';
        }elseif($tipe_1 == 0 && $tipe_2 == 0 && $tipe_3 == 0){
            $view_content = 'input_data_hari_hasil';
            $data['title'] = 'Sales dan Outlet Transaksi Harian';
        }else{

        }

        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar',$data);
        $this->load->view('template_claim/top_content',$data);
        $this->load->view("report_sales_per_hari/$view_content",$data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');            
    }
                                           
   public function export_sales(){
    $id = $this->session->userdata('id'); 
        $query="
            select  a.kode,a.branch_name,a.nama_comp,a.bulan,
                    a.unit_1,a.unit_2,a.unit_3,a.unit_4,a.unit_5,a.unit_6,a.unit_7,a.unit_8,a.unit_9,a.unit_10,
                    a.unit_11,a.unit_12,a.unit_13,a.unit_14,a.unit_15,a.unit_16,a.unit_17,a.unit_18,a.unit_19,a.unit_20,
                    a.unit_21,a.unit_22,a.unit_23,a.unit_24,a.unit_25,a.unit_26,a.unit_27,a.unit_28,a.unit_29,a.unit_30,a.unit_31,
                    a.value_1,a.value_2,a.value_3,a.value_4,a.value_5,a.value_6,a.value_7,a.value_8,a.value_9,a.value_10,
                    a.value_11,a.value_12,a.value_13,a.value_14,a.value_15,a.value_16,a.value_17,a.value_18,a.value_19,a.value_20,
                    a.value_21,a.value_22,a.value_23,a.value_24,a.value_25,a.value_26,a.value_27,a.value_28,a.value_29,a.value_30,a.value_31,
                    a.ot_1,a.ot_2,a.ot_3,a.ot_4,a.ot_5,a.ot_6,a.ot_7,a.ot_8,a.ot_9,a.ot_10,
                    a.ot_11,a.ot_12,a.ot_13,a.ot_14,a.ot_15,a.ot_16,a.ot_17,a.ot_18,a.ot_19,a.ot_20,
                    a.ot_21,a.ot_22,a.ot_23,a.ot_24,a.ot_25,a.ot_26,a.ot_27,a.ot_28,a.ot_29,a.ot_30,a.ot_31
            from mpm.t_sales_per_hari a
            where a.id = $id
        ";                        
        $hsl = $this->db->query($query);
        query_to_csv($hsl,TRUE,'Sales dan Outlet Transaksi Harian.csv');
    }

    public function export_sales_kodeprod(){
        $id = $this->session->userdata('id');    
        $query="
            select  a.kode,a.branch_name,a.nama_comp,a.kodeprod,a.namaprod,a.bulan,
                    a.unit_1,a.unit_2,a.unit_3,a.unit_4,a.unit_5,a.unit_6,a.unit_7,a.unit_8,a.unit_9,a.unit_10,
                    a.unit_11,a.unit_12,a.unit_13,a.unit_14,a.unit_15,a.unit_16,a.unit_17,a.unit_18,a.unit_19,a.unit_20,
                    a.unit_21,a.unit_22,a.unit_23,a.unit_24,a.unit_25,a.unit_26,a.unit_27,a.unit_28,a.unit_29,a.unit_30,a.unit_31,
                    a.value_1,a.value_2,a.value_3,a.value_4,a.value_5,a.value_6,a.value_7,a.value_8,a.value_9,a.value_10,
                    a.value_11,a.value_12,a.value_13,a.value_14,a.value_15,a.value_16,a.value_17,a.value_18,a.value_19,a.value_20,
                    a.value_21,a.value_22,a.value_23,a.value_24,a.value_25,a.value_26,a.value_27,a.value_28,a.value_29,a.value_30,a.value_31,
                    a.ot_1,a.ot_2,a.ot_3,a.ot_4,a.ot_5,a.ot_6,a.ot_7,a.ot_8,a.ot_9,a.ot_10,
                    a.ot_11,a.ot_12,a.ot_13,a.ot_14,a.ot_15,a.ot_16,a.ot_17,a.ot_18,a.ot_19,a.ot_20,
                    a.ot_21,a.ot_22,a.ot_23,a.ot_24,a.ot_25,a.ot_26,a.ot_27,a.ot_28,a.ot_29,a.ot_30,a.ot_31
            from mpm.t_sales_per_hari a
            where a.id = $id
        ";
                        
        $hsl = $this->db->query($query);
        query_to_csv($hsl,TRUE,'Sales dan Outlet Transaksi Harian - breakdown kodeproduk.csv');
    }

    public function export_sales_kodeprod_kodesalur(){
        $id = $this->session->userdata('id');    
        $query="
            select  a.kode,a.branch_name,a.nama_comp,a.kodeprod,a.namaprod,a.kodesalur,a.namasalur,a.bulan,
                    a.unit_1,a.unit_2,a.unit_3,a.unit_4,a.unit_5,a.unit_6,a.unit_7,a.unit_8,a.unit_9,a.unit_10,
                    a.unit_11,a.unit_12,a.unit_13,a.unit_14,a.unit_15,a.unit_16,a.unit_17,a.unit_18,a.unit_19,a.unit_20,
                    a.unit_21,a.unit_22,a.unit_23,a.unit_24,a.unit_25,a.unit_26,a.unit_27,a.unit_28,a.unit_29,a.unit_30,a.unit_31,
                    a.value_1,a.value_2,a.value_3,a.value_4,a.value_5,a.value_6,a.value_7,a.value_8,a.value_9,a.value_10,
                    a.value_11,a.value_12,a.value_13,a.value_14,a.value_15,a.value_16,a.value_17,a.value_18,a.value_19,a.value_20,
                    a.value_21,a.value_22,a.value_23,a.value_24,a.value_25,a.value_26,a.value_27,a.value_28,a.value_29,a.value_30,a.value_31,
                    a.ot_1,a.ot_2,a.ot_3,a.ot_4,a.ot_5,a.ot_6,a.ot_7,a.ot_8,a.ot_9,a.ot_10,
                    a.ot_11,a.ot_12,a.ot_13,a.ot_14,a.ot_15,a.ot_16,a.ot_17,a.ot_18,a.ot_19,a.ot_20,
                    a.ot_21,a.ot_22,a.ot_23,a.ot_24,a.ot_25,a.ot_26,a.ot_27,a.ot_28,a.ot_29,a.ot_30,a.ot_31
            from mpm.t_sales_per_hari a
            where a.id = $id
        ";
                        
        $hsl = $this->db->query($query);
        query_to_csv($hsl,TRUE,'Sales dan Outlet Transaksi Harian - breakdown kodeproduk & class outlet.csv');
    }
    public function export_sales_kodeprod_kode_type(){
        $id = $this->session->userdata('id');    
        $query="
            select  a.kode,a.branch_name,a.nama_comp,a.kodeprod,a.namaprod,a.kode_type,a.nama_type,a.sektor,a.bulan,
                    a.unit_1,a.unit_2,a.unit_3,a.unit_4,a.unit_5,a.unit_6,a.unit_7,a.unit_8,a.unit_9,a.unit_10,
                    a.unit_11,a.unit_12,a.unit_13,a.unit_14,a.unit_15,a.unit_16,a.unit_17,a.unit_18,a.unit_19,a.unit_20,
                    a.unit_21,a.unit_22,a.unit_23,a.unit_24,a.unit_25,a.unit_26,a.unit_27,a.unit_28,a.unit_29,a.unit_30,a.unit_31,
                    a.value_1,a.value_2,a.value_3,a.value_4,a.value_5,a.value_6,a.value_7,a.value_8,a.value_9,a.value_10,
                    a.value_11,a.value_12,a.value_13,a.value_14,a.value_15,a.value_16,a.value_17,a.value_18,a.value_19,a.value_20,
                    a.value_21,a.value_22,a.value_23,a.value_24,a.value_25,a.value_26,a.value_27,a.value_28,a.value_29,a.value_30,a.value_31,
                    a.ot_1,a.ot_2,a.ot_3,a.ot_4,a.ot_5,a.ot_6,a.ot_7,a.ot_8,a.ot_9,a.ot_10,
                    a.ot_11,a.ot_12,a.ot_13,a.ot_14,a.ot_15,a.ot_16,a.ot_17,a.ot_18,a.ot_19,a.ot_20,
                    a.ot_21,a.ot_22,a.ot_23,a.ot_24,a.ot_25,a.ot_26,a.ot_27,a.ot_28,a.ot_29,a.ot_30,a.ot_31
            from mpm.t_sales_per_hari a
            where a.id = $id
        ";
                        
        $hsl = $this->db->query($query);
        query_to_csv($hsl,TRUE,'Sales dan Outlet Transaksi Harian - breakdown kodeproduk & tipe outlet.csv');
    } 

    public function export_sales_kodesalur(){
        $id = $this->session->userdata('id');    
        $query="
            select  a.kode,a.branch_name,a.nama_comp,a.kodesalur,a.namasalur,a.bulan,
                    a.unit_1,a.unit_2,a.unit_3,a.unit_4,a.unit_5,a.unit_6,a.unit_7,a.unit_8,a.unit_9,a.unit_10,
                    a.unit_11,a.unit_12,a.unit_13,a.unit_14,a.unit_15,a.unit_16,a.unit_17,a.unit_18,a.unit_19,a.unit_20,
                    a.unit_21,a.unit_22,a.unit_23,a.unit_24,a.unit_25,a.unit_26,a.unit_27,a.unit_28,a.unit_29,a.unit_30,a.unit_31,
                    a.value_1,a.value_2,a.value_3,a.value_4,a.value_5,a.value_6,a.value_7,a.value_8,a.value_9,a.value_10,
                    a.value_11,a.value_12,a.value_13,a.value_14,a.value_15,a.value_16,a.value_17,a.value_18,a.value_19,a.value_20,
                    a.value_21,a.value_22,a.value_23,a.value_24,a.value_25,a.value_26,a.value_27,a.value_28,a.value_29,a.value_30,a.value_31,
                    a.ot_1,a.ot_2,a.ot_3,a.ot_4,a.ot_5,a.ot_6,a.ot_7,a.ot_8,a.ot_9,a.ot_10,
                    a.ot_11,a.ot_12,a.ot_13,a.ot_14,a.ot_15,a.ot_16,a.ot_17,a.ot_18,a.ot_19,a.ot_20,
                    a.ot_21,a.ot_22,a.ot_23,a.ot_24,a.ot_25,a.ot_26,a.ot_27,a.ot_28,a.ot_29,a.ot_30,a.ot_31
            from mpm.t_sales_per_hari a
            where a.id = $id
        ";
                        
        $hsl = $this->db->query($query);
        query_to_csv($hsl,TRUE,'Sales dan Outlet Transaksi Harian - breakdown class outlet.csv');
    } 
    
    public function export_sales_kode_type(){
        $id = $this->session->userdata('id');    
        $query="
            select  a.kode,a.branch_name,a.nama_comp,a.kode_type,a.nama_type,a.sektor,a.bulan,
                    a.unit_1,a.unit_2,a.unit_3,a.unit_4,a.unit_5,a.unit_6,a.unit_7,a.unit_8,a.unit_9,a.unit_10,
                    a.unit_11,a.unit_12,a.unit_13,a.unit_14,a.unit_15,a.unit_16,a.unit_17,a.unit_18,a.unit_19,a.unit_20,
                    a.unit_21,a.unit_22,a.unit_23,a.unit_24,a.unit_25,a.unit_26,a.unit_27,a.unit_28,a.unit_29,a.unit_30,a.unit_31,
                    a.value_1,a.value_2,a.value_3,a.value_4,a.value_5,a.value_6,a.value_7,a.value_8,a.value_9,a.value_10,
                    a.value_11,a.value_12,a.value_13,a.value_14,a.value_15,a.value_16,a.value_17,a.value_18,a.value_19,a.value_20,
                    a.value_21,a.value_22,a.value_23,a.value_24,a.value_25,a.value_26,a.value_27,a.value_28,a.value_29,a.value_30,a.value_31,
                    a.ot_1,a.ot_2,a.ot_3,a.ot_4,a.ot_5,a.ot_6,a.ot_7,a.ot_8,a.ot_9,a.ot_10,
                    a.ot_11,a.ot_12,a.ot_13,a.ot_14,a.ot_15,a.ot_16,a.ot_17,a.ot_18,a.ot_19,a.ot_20,
                    a.ot_21,a.ot_22,a.ot_23,a.ot_24,a.ot_25,a.ot_26,a.ot_27,a.ot_28,a.ot_29,a.ot_30,a.ot_31
            from mpm.t_sales_per_hari a
            where a.id = $id
        ";
                        
        $hsl = $this->db->query($query);
        query_to_csv($hsl,TRUE,'Sales dan Outlet Transaksi Harian - breakdown type outlet.csv');
    }

    public function export_sales_kodesalur_kode_type(){
        $id = $this->session->userdata('id');    
        $query="
            select  a.kode,a.branch_name,a.nama_comp,a.kodesalur,a.namasalur,a.kode_type,a.nama_type,a.sektor,a.bulan,
                    a.unit_1,a.unit_2,a.unit_3,a.unit_4,a.unit_5,a.unit_6,a.unit_7,a.unit_8,a.unit_9,a.unit_10,
                    a.unit_11,a.unit_12,a.unit_13,a.unit_14,a.unit_15,a.unit_16,a.unit_17,a.unit_18,a.unit_19,a.unit_20,
                    a.unit_21,a.unit_22,a.unit_23,a.unit_24,a.unit_25,a.unit_26,a.unit_27,a.unit_28,a.unit_29,a.unit_30,a.unit_31,
                    a.value_1,a.value_2,a.value_3,a.value_4,a.value_5,a.value_6,a.value_7,a.value_8,a.value_9,a.value_10,
                    a.value_11,a.value_12,a.value_13,a.value_14,a.value_15,a.value_16,a.value_17,a.value_18,a.value_19,a.value_20,
                    a.value_21,a.value_22,a.value_23,a.value_24,a.value_25,a.value_26,a.value_27,a.value_28,a.value_29,a.value_30,a.value_31,
                    a.ot_1,a.ot_2,a.ot_3,a.ot_4,a.ot_5,a.ot_6,a.ot_7,a.ot_8,a.ot_9,a.ot_10,
                    a.ot_11,a.ot_12,a.ot_13,a.ot_14,a.ot_15,a.ot_16,a.ot_17,a.ot_18,a.ot_19,a.ot_20,
                    a.ot_21,a.ot_22,a.ot_23,a.ot_24,a.ot_25,a.ot_26,a.ot_27,a.ot_28,a.ot_29,a.ot_30,a.ot_31
            from mpm.t_sales_per_hari a
            where a.id = $id
        ";
                        
        $hsl = $this->db->query($query);
        query_to_csv($hsl,TRUE,'Sales dan Outlet Transaksi Harian - breakdown class & type outlet.csv');
    }

    public function export_sales_kodeprod_kodesalur_kode_type(){
        $id = $this->session->userdata('id');    
        $query="
            select  a.kode,a.branch_name,a.nama_comp,a.kodeprod,a.namaprod,a.kodesalur,a.namasalur,a.kode_type,a.nama_type,a.sektor,a.bulan,
                    a.unit_1,a.unit_2,a.unit_3,a.unit_4,a.unit_5,a.unit_6,a.unit_7,a.unit_8,a.unit_9,a.unit_10,
                    a.unit_11,a.unit_12,a.unit_13,a.unit_14,a.unit_15,a.unit_16,a.unit_17,a.unit_18,a.unit_19,a.unit_20,
                    a.unit_21,a.unit_22,a.unit_23,a.unit_24,a.unit_25,a.unit_26,a.unit_27,a.unit_28,a.unit_29,a.unit_30,a.unit_31,
                    a.value_1,a.value_2,a.value_3,a.value_4,a.value_5,a.value_6,a.value_7,a.value_8,a.value_9,a.value_10,
                    a.value_11,a.value_12,a.value_13,a.value_14,a.value_15,a.value_16,a.value_17,a.value_18,a.value_19,a.value_20,
                    a.value_21,a.value_22,a.value_23,a.value_24,a.value_25,a.value_26,a.value_27,a.value_28,a.value_29,a.value_30,a.value_31,
                    a.ot_1,a.ot_2,a.ot_3,a.ot_4,a.ot_5,a.ot_6,a.ot_7,a.ot_8,a.ot_9,a.ot_10,
                    a.ot_11,a.ot_12,a.ot_13,a.ot_14,a.ot_15,a.ot_16,a.ot_17,a.ot_18,a.ot_19,a.ot_20,
                    a.ot_21,a.ot_22,a.ot_23,a.ot_24,a.ot_25,a.ot_26,a.ot_27,a.ot_28,a.ot_29,a.ot_30,a.ot_31
            from mpm.t_sales_per_hari a
            where a.id = $id
        ";
                        
        $hsl = $this->db->query($query);
        query_to_csv($hsl,TRUE,'Sales dan Outlet Transaksi Harian - breakdown kodeproduk, class & type outlet.csv');
    }
}
 function buildgroup()
    {
        //$this->load->model('basic_model','bmodel');
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
        $kode_supp = $this->input->post('kode_supp',TRUE);
        $output="<option value='0'>--</option>";
        //$query=$this->model_stock->get_namacomp($dp);

        $data['kode_supp'] = $kode_supp;
        $query=$this->model_omzet->get_group($data);
        foreach($query->result() as $row)
        {
            $output .= "<option value='".$row->id_group."'>".$row->nama_group."</option>";
        }
        echo $output;
    }
?>
