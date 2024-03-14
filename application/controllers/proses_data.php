<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Proses_data extends MY_Controller {

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

  public function index()
  {
    // 1. cek tabel mpm.upload
    
    $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
        $this->view_assets();

    // 2. 
  }

  function proses_data()
  {
    set_time_limit(0);
        $this->load->library(array('table','template'));
        $this->load->helper('url');
        $this->load->model('model_proses_data');
        $this->load->database();
        $this->querymenu='select a.id,a.menuview,a.target,a.groupname from mpm.menu a inner join mpm.menudetail b on a.id=b.menuid where b.userid='. $this->session->userdata('id') . ' and active=1 order by a.groupname,a.menuview ';
  }

  private function template($view,$data)
    {
        $this->template->set_title('MPM SQUARE');
        $this->template->add_js('modules/skeleton.js');
        $this->template->add_css('modules/skeleton.css');
        $this->template->load_view($view, $data);
    }

  public function view_upload(){

      $logged_in= $this->session->userdata('logged_in');
      if(!isset($logged_in) || $logged_in != TRUE)
      {
          redirect('login/','refresh');
      }

      $data['page_content'] = 'upload/view_upload';
      $data['uploads']=$this->model_proses_data->view_upload();
      //$data['hitungs']=$this->model_proses_data->hitung_omzet();        
      $data['menu']=$this->db->query($this->querymenu);
      $this->template($data['page_content'],$data); 
  }

  public function view_upload_all_dp(){

      $logged_in= $this->session->userdata('logged_in');
      if(!isset($logged_in) || $logged_in != TRUE)
      {
          redirect('login/','refresh');
      }

      $data['page_content'] = 'upload/view_upload_all_dp';
      $data['uploads']=$this->model_proses_data->view_upload_all_dp();
      //$data['hitungs']=$this->model_proses_data->hitung_omzet();        
      $data['menu']=$this->db->query($this->querymenu);
      $this->template($data['page_content'],$data); 
  }

  public function proses_data_omzet(){
    $this->load->helper('url');

    $logged_in= $this->session->userdata('logged_in');
      if(!isset($logged_in) || $logged_in != TRUE)
      {
          redirect('login/','refresh');
      }

      $data['page_content'] = 'upload/view_upload';
      $data['uploads']=$this->model_proses_data->proses_data_omzet();                  
      $data['menu']=$this->db->query($this->querymenu);
      $this->template($data['page_content'],$data);
  }

  public function proses_data_omzet_all_dp(){
    $this->load->helper('url');

    $logged_in= $this->session->userdata('logged_in');
      if(!isset($logged_in) || $logged_in != TRUE)
      {
          redirect('login/','refresh');
      }
      $data['page_content'] = 'upload/view_upload_all_dp';
      //$data['hitungs']=$this->model_proses_data->hitung_omzet();
      $data['a']="1";
      $data['uploads']=$this->model_proses_data->proses_data_omzet_all_dp($data);                   
      $data['menu']=$this->db->query($this->querymenu);
      $this->template($data['page_content'],$data);
  }

  public function submit()
  {
    $id_upload = $this->uri->segment('3');

    $sql = "
      select filename, tanggal, bulan, tahun
      from mpm.upload 
      where id = $id_upload
    ";

    $proses= $this->db->query($sql)->result();
    foreach ($proses as $key) {
      $tanggal = $key->tanggal;
      $bulan = $key->bulan;
      $tahun = $key->tahun;
      $nocab = substr($key->filename,2,2);
    }

    $data['tanggal'] = $tanggal;
    $data['bulan'] = $bulan;
    $data['tahun'] = $tahun;
    $data['nocab'] = $nocab;
    $data['id'] = $id_upload; 

    $submit = $this->model_proses_data->submit_manual($data);
    if ($submit) {
      redirect('proses_data/view_upload');
    }

  }

}

/* End of file proses_data.php */
/* Location: ./application/controllers/proses_data.php */