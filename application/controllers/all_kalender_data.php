<?php

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class All_kalender_data extends MY_Controller
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

    function all_kalender_data()
    {
        set_time_limit(0);
        $this->load->library(array('table','template','Excel_generator', 'form_validation','email'));
        $this->load->helper('url');
        $this->load->model('model_kalender_data');
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

        $this->view_kalender_data();

    }

    private function template($view,$data)
    {
        $this->template->set_title('MPM SQUARE');
        $this->template->add_js('modules/skeleton.js');
        $this->template->add_css('modules/skeleton.css');
        $this->template->load_view($view, $data);
    }

    public function view_kalender_data_closing(){    

        $data['page_content'] = 'kalender_data/view_kalender_data_closing';                      
        $data['menu']=$this->db->query($this->querymenu);
        $data['url']="all_kalender_data/proses_kalender_data_closing";
        $data['page_title']="Kalender Data";
        $data['query']=$this->model_kalender_data->view_kalender_data_closing();
        $this->template($data['page_content'],$data); 
              
    }

    public function proses_kalender_data_closing(){    

        $data['page_content'] = 'kalender_data/view_kalender_data_closing';                      
        $data['menu']=$this->db->query($this->querymenu);
        $data['url']="all_kalender_data/proses_kalender_data_closing";
        $data['page_title']="Kalender Data";
        $data['query']=$this->model_kalender_data->view_kalender_data_closing();
        $this->template($data['page_content'],$data); 
              
    }

    public function view_kalender_data(){    

        $data['page_content'] = 'kalender_data/view_kalender_data';                      
        $data['menu']=$this->db->query($this->querymenu);
        $data['url']="all_kalender_data/proses_kalender_data";
        $data['page_title']="Kalender Data";
        //$data['query']=$this->model_kalender_data->view_kalender_data();
        $this->template($data['page_content'],$data); 
              
    }

    public function proses_kalender_data(){    

        $data = array(                
                'jenis_data'       => $this->input->post('jenis_data'),
                'tahun'           => $this->input->post('tahun')
        );
        /*
        echo "jenis_data  : ".$data['jenis_data']."<br>";
        echo "tahun       : ".$data['tahun']."<br>";                 
        */
        $data['page_content'] = 'kalender_data/view_kalender_data_hasil';                      
        $data['menu']=$this->db->query($this->querymenu);
        $data['url']="all_kalender_data/proses_kalender_data";
        $data['page_title']="Kalender Data";
        $data['tahun_pilih'] = $this->input->post('tahun');
        $jenis_data_pilih = $this->input->post('jenis_data');
        if ($jenis_data_pilih == 1) {
            $data['jenis_data_pilih_x'] = 'Data Text MPM';
        }else{
            $data['jenis_data_pilih_x'] = 'Data Upload Website';
        }

        $data['query']=$this->model_kalender_data->view_kalender_data($data);
        $this->template($data['page_content'],$data); 
              
    }

    public function history_upload(){    

        $data['userid'] = $this->uri->Segment('3');
        //echo $data['userid'];
        $data['page_content'] = 'kalender_data/view_history_data';                      
        $data['menu']=$this->db->query($this->querymenu);
        //$data['url']="all_kalender_data/proses_kalender_data";
        $data['page_title']="History Data";
        $data['query']=$this->model_kalender_data->view_history_data($data);
        $this->template($data['page_content'],$data); 
              
    }

    
   
}
?>
