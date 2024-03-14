<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Verifikasi_harga_product extends MY_Controller
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

    function Verifikasi_harga_product()
    {
        set_time_limit(0);
        $this->load->library(array('table','template','Excel_generator'));
        $this->load->helper('url');
        $this->load->model('model_omzet');
        $this->load->model('model_verifikasi_harga');
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
        $this->template->set_title('MPM');
        $this->template->add_js('modules/skeleton.js');
        $this->template->add_css('modules/skeleton.css');
        $this->template->load_view($view, $data);
    }

    public function input_data_verifikasi(){

        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
            $data['page_content'] = 'verifikasi_harga_product/input_data';
            $data['url'] = 'verifikasi_harga_product/input_data_verifikasi_hasil/';
            $data['page_title'] = 'Verifikasi Harga Product';
            $data['query'] = $this->model_omzet->getSuppbyid();
            $data['menu']=$this->db->query($this->querymenu);
            $this->template($data['page_content'],$data);  
    }

    public function input_data_verifikasi_hasil(){

        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }

        $data['tahun'] = $this->input->post('tahun');
        $data['bulan'] = $this->input->post('bulan');

        $data['proses'] = $this->model_verifikasi_harga->input_data_verifikasi($data);

        $data['page_content'] = 'verifikasi_harga_product/input_data_verifikasi_hasil';
        $data['url'] = 'verifikasi_harga_product/input_data_verifikasi_hasil/';
        $data['page_title'] = 'Verifikasi Harga Product';
        $data['query'] = $this->model_omzet->getSuppbyid();
        $data['menu']=$this->db->query($this->querymenu);
        $this->template($data['page_content'],$data);  
            
    }
    // export excel
   public function export_verifikasi_harga(){
    $id = $this->session->userdata('id');
    
        // $tipe_1 = $this->input->post('tipe_1');
        // $tipe_2 = $this->input->post('tipe_2');
        // $tipe_3 = $this->input->post('tipe_3');

        //  if ($tipe_1 == 1 && $tipe_2 == 1 && $tipe_3 == 1) {


            $query="
                    select *
                    from db_temp_new.t_verifikasi_harga_temp
                    where id = $id
                ";
                            
            $hsl = $this->db->query($query);

        // }
       
        $this->excel_generator->set_query($hsl);
        $this->excel_generator->set_header(array
          (
            'Kode', 'Branch Name','Sub','Kodeprod','Nama Produk','Harga Lama','Harga Terbaru'
          ));
        $this->excel_generator->set_column(array
          (
            'kode', 'branch_name','nama_comp','kodeprod','namaprod','harga','h_dp'
            
          ));
          $this->excel_generator->set_width(array(10,10,10,10,10,10,10));
          $this->excel_generator->exportTo2007('verifikasi_harga');

        }
}