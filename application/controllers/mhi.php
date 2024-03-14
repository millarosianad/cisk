<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Mhi extends MY_Controller
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

    function mhi()
    {
        set_time_limit(0);
        $this->load->library(array('table','template','Excel_generator', 'form_validation','email'));
        $this->load->helper('url');
        $this->load->helper('csv');
        $this->load->model('M_mhi');
        $this->load->database();
        $this->querymenu='select  a.id,
                        a.menuview,
                        a.target,
                        a.groupname 
                from    mpm.menu a inner join mpm.menudetail b 
                            on a.id=b.menuid 
                where   b.userid='.$this->session->userdata('id').' and 
                        active=1 
                order by a.groupname,menuview ';
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
        $this->template->set_title('MPM SQUARE');
        $this->template->add_js('modules/skeleton.js');
        $this->template->add_css('modules/skeleton.css');
        $this->template->load_view($view, $data);
    }

    public function list_asn(){
        $data = [
            'id' => $this->session->userdata('id'),
            'title' => 'Advanced Shipping Notes - ASN',
            'get_po' => $this->M_mhi->get_po_mhi()
        ];
        $this->load->view('template/header_admin');
        $this->load->view('template/nav_header');
        $this->load->view('template/sidebar_admin');
        $this->load->view('mhi/list_asn',$data);
        $this->load->view('template/footer_admin');
    }

    public function tambah_asn($id){
        $data = [
            'id' => $this->session->userdata('id'),
            'title' => 'Input Advanced Shipping Notes (ASN)',
            'get_po_by_produk' => $this->M_mhi->get_po_by_produk($id),
            'get_po' => $this->M_mhi->get_po_mhi_by_id($id),
            'url' => 'mhi/proses_tambah_asn'
        ];
        $this->load->view('template/header_admin');
        $this->load->view('template/nav_header');
        $this->load->view('template/sidebar_admin');
        $this->load->view('mhi/tambah_asn',$data);
        $this->load->view('template/footer_admin');
    }

    public function proses_tambah_asn(){

        $data = [
            'id' => $this->session->userdata('id'),
            'id_po' => $this->input->post('id'),
            'asn_kodeprod' => $this->input->post('asn_kodeprod'),
            'asn_tanggalKirim' => $this->input->post('asn_tanggalKirim'),
            'asn_unit' => $this->input->post('asn_unit'),
            'asn_nama_expedisi' => $this->input->post('asn_nama_expedisi'),
            'asn_est_lead_time' => $this->input->post('asn_est_lead_time'),
            'asn_eta' => $this->input->post('asn_eta')
        ];

        $data['proses'] = $this->M_mhi->proses_tambah_asn($data);

    }

    public function list_do(){
        $data = [
            'id' => $this->session->userdata('id'),
            'title' => 'Delivery Order - DO',
            'get_po' => $this->M_mhi->get_po_sudah_asn()
        ];
        $this->load->view('template/header_admin');
        $this->load->view('template/nav_header');
        $this->load->view('template/sidebar_admin');
        $this->load->view('mhi/list_do',$data);
        $this->load->view('template/footer_admin');
    }



}
?>
