<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Analisis extends MY_Controller
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

    function analisis()
    {
        set_time_limit(0);
        $this->load->library(array('table','template','Excel_generator', 'form_validation','email'));
        $this->load->helper('url');
        $this->load->helper('csv');
        $this->load->model('model_analisis');
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
        $this->piutang();
    }

    private function template($view,$data)
    {
        $this->template->set_title('MPM SQUARE');
        $this->template->add_js('modules/skeleton.js');
        $this->template->add_css('modules/skeleton.css');
        $this->template->load_view($view, $data);
    }

    public function piutang()
    {
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
        $data['page_content'] = 'analisis/form_piutang';
        $data['url'] = 'analisis/proses_piutang/';
        $data['page_title'] = 'Analisa Piutang';
        $data['menu']=$this->db->query($this->querymenu);
        $this->template($data['page_content'],$data);       
    }

    public function proses_piutang()
    {
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
        $data['periode'] = $this->input->post('periode');
        $data['page_content'] = 'analisis/proses_piutang';
        $data['url'] = 'analisis/proses_piutang/';
        $data['page_title'] = 'Analisa Piutang';
        $data['menu']=$this->db->query($this->querymenu);
        $data['hasil']= $this->model_analisis->view_analisis_piutang($data);
        $this->template($data['page_content'],$data);  
    }

    public function export()
    {
        $id= $this->session->userdata('id');
        $query="
            select * from db_analisis.t_piutang_temp
            where userid = $id order by group_descr
        ";

        $hasil = $this->db->query($query);
        $this->excel_generator->set_query($hasil);
        $this->excel_generator->set_header(array
        (
        'Group Customer', 'belum jatuh tempo','1-7', '8-15','16-30', '31-45','46-60', '>60', 'total'
        ));
            $this->excel_generator->set_column(array
              (
                'group_descr', 
                'a', 
                'b',
                'c',
                'd',
                'e',
                'f', 
                'g',
                'total'
              ));       
        
        $this->excel_generator->set_width(array(15,10,10,10,10,10,10,10,10));
        $this->excel_generator->exportTo2007('analisis_piutang'); 
    }

    public function export_detail()
    {
        $id = $this->session->userdata('id');
  
          $sql = "
          select * from db_analisis.t_piutang
          where userid = $id 
          ";
          
          $quer = $this->db->query($sql);
  
          query_to_csv($quer,TRUE,'Detail Analisis Piutang.csv');
         
    }
}
?>
