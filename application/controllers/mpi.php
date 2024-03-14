<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Mpi extends MY_Controller
{
    function mpi()
    {
        $logged_in = $this->session->userdata('logged_in');
        if (!isset($logged_in) || $logged_in != TRUE) {
            redirect('login/', 'refresh');
        }
        set_time_limit(0);
        $this->load->library(array('table', 'template', 'Excel_generator', 'form_validation', 'email'));
        $this->load->helper('url');
        $this->load->helper('csv');
        $this->load->model('M_menu');
        $this->load->model('M_mpi');
        $this->load->database();
    }

    public function insert_mpi()
    {
        $data = [
            'id' => $this->session->userdata('id'),
            'title' => 'Insert MPI',
            'get_label' => $this->M_menu->get_label(),
            'omzets' => $this->M_mpi->data_mpi(),
            'url' => 'mpi/proses_insert_mpi/'
        ];

        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar', $data);
        $this->load->view('mpi_new/form_mpi', $data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
    }

    public function proses_insert_mpi()
    {
        $from_a = trim($this->input->post('from'));
        $to_a =trim($this->input->post('to'));

        $data = [
            'id' => $this->session->userdata('id'),
            'title' => 'Insert MPI',
            'get_label' => $this->M_menu->get_label(),
            'url' => 'mpi/proses_insert_mpi/',
            'from'=> strftime('%Y-%m-%d',strtotime($from_a)),
            'to'=> strftime('%Y-%m-%d',strtotime($to_a)),
        ];
        $data['omzets'] = $this->M_mpi->insert_mpi($data);
        
        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar', $data);
        $this->load->view('mpi_new/form_mpi_proses', $data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
    }

    public function insert_mpi_to_db(){

        $proses= $this->M_mpi->insert_mpi_to_db();
    }

    public function omzet_mpi(){

        $data = [
            'id' => $this->session->userdata('id'),
            'title' => 'Omzet MPI',
            'get_label' => $this->M_menu->get_label(),
            'url' => 'mpi/omzet_mpi_proses/',
        ];
        
        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar', $data);
        $this->load->view('mpi_new/omzet_mpi', $data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');

    }

    public function omzet_mpi_proses()
    {
        $id=$this->session->userdata('id');
        $data['tahun']=$this->input->post('tahun');
        $data['uv']=$this->input->post('uv');
        $data['apotik']=$this->input->post('apotik');
        $data['kimia_farma']=$this->input->post('kimia_farma');
        $data['pbf_kimia_farma']=$this->input->post('pbf_kimia_farma');
        $data['minimarket']=$this->input->post('minimarket');
        $data['pd']=$this->input->post('pd');
        $data['pbf']=$this->input->post('pbf');
        $data['rs']=$this->input->post('rs');
        $data['supermarket']=$this->input->post('supermarket');
        $data['tokoobat']=$this->input->post('tokoobat');
        $data['group']=$this->input->post('group_by');
        
        $group_by=$this->input->post('group_by');
        if ($group_by == '1') {
            $view = 'mpi_new/omzet_mpi_proses';
        }else if ($group_by == '2') {
            $view = 'mpi_new/omzet_mpi_proses_groupby_kodeprod';
        }else if ($group_by == '3') {
            $view = 'mpi_new/omzet_mpi_proses_groupby_kodecab_kodeprod';
        } else {
            
        }

        $data = [
            'id' => $this->session->userdata('id'),
            'title' => 'Omzet MPI',
            'get_label' => $this->M_menu->get_label(),
            'url' => 'mpi/omzet_mpi_proses/',
            'omzets' => $this->M_mpi->omzet_mpi_proses($data)
        ];
        
        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar', $data);
        $this->load->view("$view", $data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
    }
    
    public function export_omzet_mpi()
    {
        $this->load->helper('csv');
        $id=$this->session->userdata('id');
        $sql = "
        select * from mpi.tbl_mpi
        where id =$id
        ";
        $quer = $this->db->query($sql);
        
        query_to_csv($quer,TRUE,'Sales MPI.csv');
    }
    
    public function export_omzet_mpi_temp()
    {
        $this->load->helper('csv');
        $id=$this->session->userdata('id');
        $sql = "
        select kode_cab, nama_cab, b1, b2, b3, b4, b5, b6, b7, b8, b9, b10, b11, b12, t1, t2, t3, t4, t5, t6, t7, t8, t9, t10, t11, t12, id
        from mpi.t_temp_omzet_mpi
        where id =$id
        ";
        $quer = $this->db->query($sql);

        query_to_csv($quer,TRUE,'Sales Omzet MPI.csv');
    }

    public function export_omzet_mpi_groupby_kodeprod_temp()
    {
        $this->load->helper('csv');
        $id=$this->session->userdata('id');
        $sql = "
        select kodeprod_mpi, namaprod_mpi, kodeprod_mpm, namaprod_mpm, grup, nama_group, supp, namasupp, b1, b2, b3, b4, b5, b6, b7, b8, b9, b10, b11, b12, t1, t2, t3, t4, t5, t6, t7, t8, t9, t10, t11, t12, id
        from mpi.t_temp_omzet_mpi
        where id =$id
        ";
        $quer = $this->db->query($sql);

        query_to_csv($quer,TRUE,'Sales Omzet MPI Group By Kode Produk.csv');
    }

    public function export_omzet_mpi_groupby_kodecab_kodeprod_temp()
    {
        $this->load->helper('csv');
        $id=$this->session->userdata('id');
        $sql = "
        select * from mpi.t_temp_omzet_mpi
        where id =$id
        ";
        $quer = $this->db->query($sql);

        query_to_csv($quer,TRUE,'Sales Omzet MPI Group By Kode Cabang dan Kode Produk.csv');
    }


    public function raw_sales_mpi()
    {
        $data = [
            'id' => $this->session->userdata('id'),
            'title' => 'Raw Sales MPI ',
            'get_label' => $this->M_menu->get_label(),
            'url' => 'mpi/raw_sales_mpi_proses/',
            'query' => $this->M_mpi->get_raw_mpi()
        ];

        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar', $data);
        $this->load->view('mpi_new/raw_sales_mpi', $data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');

    }

    public function raw_sales_mpi_proses()
    {
        $id=$this->session->userdata('id');
        $from_a = trim($this->input->post('from'));
        $data['from']=strftime('%Y-%m-%d',strtotime($from_a));
        $to_a = trim($this->input->post('to'));
        $data['to']=strftime('%Y-%m-%d',strtotime($to_a));

        $database = $this->input->post('database');
        // echo "database : ".$database;
        if ($database == 1) {
            $omzets = $this->M_mpi->omzet_mpi_lokal($data);
        }else{
            $omzets = $this->M_mpi->omzet_mpi($data);
        }

        $data = [
            'id' => $this->session->userdata('id'),
            'title' => 'Raw Sales MPI ',
            'get_label' => $this->M_menu->get_label(),
            'url' => 'mpi/raw_sales_mpi_proses/',
            'omzets' => $omzets
        ];

        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar', $data);
        $this->load->view('mpi_new/raw_sales_mpi_proses', $data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
    }

    public function insert_stock_mpi()
    {
        $data = [
            'id' => $this->session->userdata('id'),
            'title' => 'Raw Stock MPI ',
            'get_label' => $this->M_menu->get_label(),
            'url' => 'mpi/proses_insert_stock_mpi/',
            'stock' => $this->M_mpi->data_stock_mpi()
        ];

        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar', $data);
        $this->load->view('mpi_new/insert_stock_mpi', $data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
    }

    public function proses_insert_stock_mpi()
    {
        $data = [
            'id' => $this->session->userdata('id'),
            'title' => 'Raw Stock MPI ',
            'get_label' => $this->M_menu->get_label(),
            'url' => 'mpi/proses_insert_stock_mpi/',
            'proses' => $this->M_mpi->insert_stock_mpi()
        ];

        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar', $data);
        $this->load->view('mpi_new/proses_insert_stock_mpi', $data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
    }

    public function insert_stock_to_db()
    {
        $proses= $this->M_mpi->insert_stock_mpi_to_db();
    }

    public function export_stock_mpi()
    {
        $cut_off = $this->uri->segment('3');
        
        $sql = "
        select 	* from mpi.t_stock_mpi a
        where 	date(a.cut_off) = '$cut_off'
        ";
        $quer = $this->db->query($sql);
        query_to_csv($quer,TRUE,'export stock mpi.csv');
    }

    public function monitoring_stock_mpi()
    {
        $data = [
            'id' => $this->session->userdata('id'),
            'title' => 'Stock dan AVG Sales MPI  ',
            'get_label' => $this->M_menu->get_label(),
            'url' => 'mpi/proses_monitoring_stock_mpi/'
        ];

        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar', $data);
        $this->load->view('mpi_new/monitoring_stock_mpi', $data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
    }

    public function proses_monitoring_stock_mpi()
    {    
        $p1 = trim($this->input->post("cut_off_stock"));
        $cut_off_stock=strftime('%Y-%m-%d',strtotime($p1));
        $avg = trim($this->input->post("avg"));

        $data = [
            'id' => $this->session->userdata('id'),
            'title' => 'Stock dan AVG Sales MPI  ',
            'get_label' => $this->M_menu->get_label(),
            'url' => 'mpi/proses_monitoring_stock_mpi/',
            'stock' => $this->M_mpi->monitoring_stock_mpi($cut_off_stock,$avg)
        ];

        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar', $data);
        $this->load->view('mpi_new/proses_monitoring_stock_mpi', $data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
    }

    public function export_monitoring_doi()
    {    
      $id = $this->session->userdata('id');
      $sql = "
        select 	* from mpi.t_temp_monitoring_doi_mpi a
        where 	id = '$id'
      ";
      $quer = $this->db->query($sql);
      query_to_csv($quer,TRUE,'export_stock_avg_sales.csv');
        
    }
}