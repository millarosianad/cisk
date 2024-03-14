<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class All_status_proses_data extends MY_Controller
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

    function all_status_proses_data()
    {
        set_time_limit(0);
        $this->load->library(array('table','template','Excel_generator', 'form_validation'));
        $this->load->helper('url');
        $this->load->model('model_status');
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

        $this->view_status();
    }

    private function template($view,$data)
    {
        $this->template->set_title('MPM SQUARE');
        $this->template->add_js('modules/skeleton.js');
        $this->template->add_css('modules/skeleton.css');
        $this->template->load_view($view, $data);
    }

    public function view_status(){

      $this->load->library('form_validation');

      $logged_in= $this->session->userdata('logged_in');
      if(!isset($logged_in) || $logged_in != TRUE)
      {
          redirect('login/','refresh');
      }

        $data['page_content'] = 'proses_data/view_status_daily';                      
        $data['menu']=$this->db->query($this->querymenu);
        //$data['surat_jalan']=$this->model_surat_jalan->view_surat_jalan();
        $data['status']=$this->model_status->view_status();
        $this->template($data['page_content'],$data);        
    }

    public function input_afiliasi()
    {

      $logged_in= $this->session->userdata('logged_in');
      if(!isset($logged_in) || $logged_in != TRUE)
      {
          redirect('login/','refresh');
      }
          /*
          $data['page_content'] = 'asset/view_input_assets';
          $data['url'] = 'all_assets/proses_input_assets/';
          $data['page_title'] = 'Input Asset';           
          $data['menu']=$this->db->query($this->querymenu);
          $data['query']=$this->model_assets->getGrupassetcombo();
          $this->template($data['page_content'],$data);
          */
          $data['page_content'] = 'proses_data/view_input_afiliasi';
          $data['url'] = 'all_assets/proses_input_assets/';
          //$data['page_title'] = 'Input Asset';           
          $data['menu']=$this->db->query($this->querymenu);
          $data['query_afiliasi']=$this->model_status->list_afiliasi();
          $data['query']=$this->model_status->getGrupassetcombo();
          $this->template($data['page_content'],$data);
    }

    public function proses_input_daily(){

        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }

        $this->load->library('form_validation');

        $this->form_validation->set_rules('afiliasi', '"Nama Afiliasi"', 'required');
        $this->form_validation->set_rules('tgldata', '"tanggal data"', 'required');
        $this->form_validation->set_rules('keterangan', '"keterangan"', 'required');
           

        if($this->form_validation->run() === FALSE)
        {

          //echo "ada kesalahan";

            $data['page_content'] = 'proses_data/view_input_afiliasi';
            $data['url'] = 'all_assets/proses_input_assets/';
            $data['page_title'] = 'Input Afiliasi';           
            $data['menu']=$this->db->query($this->querymenu);
            $data['query']=$this->model_status->getGrupassetcombo();
            $this->template($data['page_content'],$data); 

            
        }else{
            
            $data = array(
                'id_afiliasi'  => set_value('afiliasi'),
                'tgldata'        => set_value('tgldata'),
                'keterangan'     => set_value('keterangan'),                
                'status'         => $this->input->post('var'),
            );
            
            echo "id_afiliasi : ".$data['id_afiliasi']."<br>";
            
            echo "tgldata : ".$data['tgldata']."<br>";
            echo "status : ".$data['status']."<br>";
            echo "keterangan : ".$data['keterangan']."<br>";
            

            $data['statuss']=$this->model_status->proses_input_status($data);

          }

        }

        public function detail_status()
        {
          $this->load->library('form_validation');
          $logged_in= $this->session->userdata('logged_in');
          if(!isset($logged_in) || $logged_in != TRUE)
          {
              redirect('login/','refresh');
          }
              /*
              $data['page_content'] = 'asset/detail_assets';                      
              $data['menu']=$this->db->query($this->querymenu);
              $data['assets']=$this->model_assets->detail_assets();
              $this->template($data['page_content'],$data);
              */

              $data['page_content'] = 'proses_data/detail_status_daily';                      
              $data['menu']=$this->db->query($this->querymenu);
              $data['query']=$this->model_status->getGrupassetcombo();
              $data['statuss']=$this->model_status->detail_status_daily();
              $this->template($data['page_content'],$data);
        }

        public function proses_update_daily(){

            $id=$this->session->userdata('id');
            //echo "<br><br>id : ".$id; 

            $logged_in= $this->session->userdata('logged_in');
            if(!isset($logged_in) || $logged_in != TRUE)
            {
                redirect('login/','refresh');
            }
            $segment = $this->uri->segment('3');
            $this->load->library('form_validation');

            $this->form_validation->set_rules('nama_afiliasi', '"Nama Afiliasi"', 'required');
            $this->form_validation->set_rules('tgl_data', '"tanggal data"', 'required');
            $this->form_validation->set_rules('keterangan', '"keterangan"', 'required');   
            
            if($this->form_validation->run() === FALSE)
            {
                
                $segment = $this->uri->segment('3');
                $data['page_content'] = 'proses_data/detail_status_daily';
                $data['segments'] = $segment;
                //$data['url'] = 'all_report/detail_po/';
                //$data['page_title'] = 'Detail PO';            
                $data['menu']=$this->db->query($this->querymenu);
                $data['query']=$this->model_status->getGrupassetcombo();
                $data['statuss']=$this->model_status->detail_status_daily();
                $this->template($data['page_content'],$data);

                
            }else{
                
                $data = array(
                    'id'            => $segment,
                    'nama_afiliasi' => set_value('nama_afiliasi'),
                    'tgl_data'      => set_value('tgl_data'),
                    'keterangan'    => set_value('keterangan'),
                    'status'        => $this->input->post('var')
                );
                //$dob1=trim($dataSegment['tglperol']);//$dob1='dd/mm/yyyy' format
                //$dob_disp1=strftime('%Y-%m-%d',strtotime($dob1));

                //echo "tgl perol controller : ".set_value('tglperol')."<br>";

                $data['statuss']=$this->model_status->proses_update_daily($data);
                

            }

        }

      public function delete_status($id){

        $this->model_status->proses_delete_daily($id);
        redirect('all_status_proses_data/','refresh');

      }

      public function view(){

        $this->load->library('form_validation');

        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }

          $data['page_content'] = 'proses_data/view_status_daily_read';                      
          $data['menu']=$this->db->query($this->querymenu);
          //$data['surat_jalan']=$this->model_surat_jalan->view_surat_jalan();
          $data['status']=$this->model_status->view_status();
          $this->template($data['page_content'],$data);

      }
    
   
}
?>
