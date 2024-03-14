<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Tools extends MY_Controller
{
    function tools()
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
        $this->load->model('model_omzet');
        $this->load->model('model_outlet_transaksi');
        $this->load->model('model_tools');
        $this->load->database();
    }

    public function rasio_harga_jual(){
        
        $supp = $this->session->userdata('supp');
        $data = [
            'id' => $this->session->userdata('id'),
            'url' => 'tools/rasio_harga_jual_hasil',
            // 'url' => 'sales_omzet/#',
            'title' => 'Rasio Harga Jual',
            'query' => $this->model_omzet->getSuppbyid(),
            'get_label' => $this->M_menu->get_label(),
            'header_supp'   => $this->model_outlet_transaksi->get_header_supp($supp),
            'get_supp' => $this->model_omzet->getSuppbyid(),
        ];
        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar',$data);
        $this->load->view('template_claim/top_content',$data);
        $this->load->view('tools/rasio_harga_jual',$data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
    }

    public function rasio_harga_jual_hasil(){

        $kodeprod = $this->input->post('options');
        var_dump($kodeprod);
        $code = '';
        foreach($kodeprod as $kode)
        {
            $code.=",".$kode;
        }

        $from = $this->input->post('from');
        $to = $this->input->post('to');
        $data = [
            'title'         => 'Rasio Harga Jual DP',
            'id'            => $this->session->userdata('id'),
            'get_label'     => $this->M_menu->get_label(),
            'created_date'  => $this->model_sales_omzet->timezone(),
            // 'supp'          => $this->model_omzet->get_supp(),
            'from'          => $this->input->post('from'),
            'to'            => $this->input->post('to'),
            'tahun_from'    => substr($from,0,4),
            'tahun_to'      => substr($to,0,4),
            'wilayah_nocab' => $this->model_per_hari->wilayah_nocab($this->session->userdata('id')),
            'kodeprod'      => preg_replace('/,/', '', $code,1),
        ];
        $data['proses'] = $this->model_tools->get_rasio_harga_jual($data);

        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar',$data);
        $this->load->view('template_claim/top_content',$data);
        $this->load->view('tools/rasio_harga_jual_hasil',$data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');

    }

    public function export_rasio_harga_jual(){
        $id = $this->session->userdata('id');
        $sql = "
            select  *
            from    db_temp.t_temp_rasio_harga_jual a
            where   a.created_by = $id and a.created_date = (
                select max(created_date) from db_temp.t_temp_rasio_harga_jual where created_by = $id
            )
            ORDER BY a.urutan
        ";
        $proses = $this->db->query($sql);              
        query_to_csv($proses,TRUE,'rasio harga jual.csv');
    }


}
?>
