<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Maps extends MY_Controller
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

    function Maps()
    {
        set_time_limit(0);
        $this->load->library(array('table','template','Excel_generator'));
        $this->load->helper('url');
        //$this->load->model('sales_model','bmodel');
        $this->load->model('model_maps');
        
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
        redirect('maps/sumatera','refresh');

    }

    private function template($view,$data)
    {
        $this->template->set_title('MPM SQUARE');
        $this->template->add_js('modules/skeleton.js');
        $this->template->add_css('modules/skeleton.css');
        $this->template->load_view($view, $data);
    }

    public function home_peta(){

      $this->load->library('form_validation');

        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
            $data['page_content'] = 'maps/view_list_peta';
            //$data['query'] = $this->model_omzet->getSuppbyid();
            $data['url'] = 'maps/home_peta';
            $data['page_title'] = 'List Peta';            
            $data['menu']=$this->db->query($this->querymenu);

            //$data['reports']=$this->model_maps->list_peta($data);
            $this->template($data['page_content'],$data);       

    }

    function sumatera()
    {
        /*
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
        
        $data['page_content'] = 'maps/sumatera';
        $data['map'] = $this->model_maps->peta();
        $data['menu']=$this->db->query($this->querymenu);
        $this->template($data['page_content'],$data);
        */
        
        $data['map'] = $this->model_maps->peta();
        $this->load->view('maps/sumatera', $data);
    }

    function jawa()
    {
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
        $data['page_content'] = 'maps/sumatera';
        $data['map'] = $this->model_maps->peta_jawa();
        $data['menu']=$this->db->query($this->querymenu);
        $this->template($data['page_content'],$data);
    }

    public function tampil_peta(){

      $this->load->library('form_validation');

        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
            $data['page_content'] = 'maps/view_peta';
           
            $data['url'] = 'maps/home_peta';
            $data['page_title'] = 'List Peta';            
            $data['menu']=$this->db->query($this->querymenu);

            $subbranch = $this->input->post('subbranch');
            
            //echo $subbranch;
            
            
            $data['subbranch'] = $subbranch;
            
            $data['map']=$this->model_maps->peta_outlet_subbranch($data);
            $this->template($data['page_content'],$data);     
    }
}
?>
