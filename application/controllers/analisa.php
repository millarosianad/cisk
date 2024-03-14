<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Analisa extends MY_Controller
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

    function analisa()
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
        $this->load->model('model_analisa');
        $this->load->model('model_analisis');
        $this->load->model('M_menu');
        $this->load->database();
    }

    public function analisa_piutang (){

        $data = [
                'id' => $this->session->userdata('id'),
                'url'       => 'analisa/analisa_piutang_hasil/',
                'title'     => 'Analisa Piutang',
                'get_label' => $this->M_menu->get_label()
                ];

        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar',$data);
        $this->load->view('template_claim/top_content',$data);
        $this->load->view('analisa/analisa_piutang');
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
    }

    public function analisa_piutang_hasil(){

        $data = [
                'id'        => $this->session->userdata('id'),
                'periode'   => $this->input->post('periode'),
                'title'     => 'Analisa Piutang',
                'get_label' => $this->M_menu->get_label(),
                ];

        $data['proses']= $this->model_analisa->analisa_piutang($data);
        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar',$data);
        $this->load->view('template_claim/top_content',$data);
        $this->load->view('analisa/analisa_piutang_hasil',$data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
    }

    public function export()
    {
        $id = $this->session->userdata('id');
        $sql = "
                select  group_descr as 'Group Customer', a as 'Belom Jatuh Tempo', b as '1-7', c as '8-15',
                        c as '16-30', d as '31-45', e as '46-60', f as '>60', g as 'Total'
                from db_temp.t_temp_piutang_temp
                where userid = $id order by group_descr
                ";
        $proses = $this->db->query($sql);              
        query_to_csv($proses,TRUE,'Analisa_piutang.csv');
    }

    public function export_detail()
    {
        $id = $this->session->userdata('id');
  
          $sql = "
          select * from db_temp.t_temp_piutang
          where userid = $id 
          ";
          
          $quer = $this->db->query($sql);
  
          query_to_csv($quer,TRUE,'Detail_Analisis_Piutang.csv');
         
    }
}
   