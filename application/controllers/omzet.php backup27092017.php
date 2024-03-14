<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Omzet extends MY_Controller
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

    function Omzet()
    {
        set_time_limit(0);
        $this->load->library(array('table','template','Excel_generator'));
        $this->load->helper('url');
        //$this->load->model('sales_model','bmodel');
        $this->load->model('model_omzet');
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

        //redirect('omzet/omzet_2016','refresh');
        redirect('omzet/data_omzet','refresh');

    }

    public function data_omzet(){

        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
            $data['page_content'] = 'sales/view_omzet_kosong';
            $data['query'] = $this->model_omzet->getSuppbyid();
            $data['url'] = 'omzet/data_omzet_hasil/';
            $data['page_title'] = 'sales omzet';
            $data['query'] = $this->model_omzet->getSuppbyid();
            $data['menu']=$this->db->query($this->querymenu);
            $this->template($data['page_content'],$data);       
        
    }

    public function data_omzet_hasil(){

        $data['page_content'] = 'sales/view_omzet';
        $data['query'] = $this->model_omzet->getSuppbyid();
        $data['menu']=$this->db->query($this->querymenu);
        $data['year']=$this->input->post('tahun');
        $data['url'] = 'omzet/data_omzet_hasil/';
        $year = $this->input->post('tahun');
        $supplier = $this->input->post('supp');
        //echo $year;
        $data['tahun'] = $year;
        $data['supp'] = $supplier;
        $data['omzets']=$this->model_omzet->omzet_all_dp($data);
        $this->template($data['page_content'],$data);
           
    }

    public function omzets(){

        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }

        $data['page_content'] = 'sales/view_omzet_kosong';
        $data['omzets']=$this->model_omzet->omzet_all();
        $data['menu']=$this->db->query($this->querymenu);
        $this->template($data['page_content'],$data);
    }

    private function template($view,$data)
    {
        $this->template->set_title('MPM SQUARE');
        $this->template->add_js('modules/skeleton.js');
        $this->template->add_css('modules/skeleton.css');
        $this->template->load_view($view, $data);
    }



    public function export_omzet() {
        
        $segment2 = $this->uri->segment('2');
        $segment3 = $this->uri->segment('3');
        $id_user=$this->session->userdata('id');

        //$this->db->join('tbl_tabcomp_new', 'tbl_tabcomp_new.naper = omzet_new.naper','inner');
        //$this->db->where("status = 1 and status_cluster <> '1' and omzet_new.id = ".'"'.$id_user.'"');
        $this->db->where("omzet_new.id = ".'"'.$id_user.'"');
        
        $this->db->order_by('omzet_new.urutan','asc');
        $hasil = $this->db->get('omzet_new');
       
        $this->excel_generator->set_query($hasil);
        $this->excel_generator->set_header(array
          (
            'Nocab', 'sub','kodeDP', 'DP','t1', 'Jan','t2', 'Feb', 't3','Mar', 't4','Apr','t5','Mei', 't6','Jun', 
            't7','Jul', 't8','Agus', 't9','Sep', 't10','Okt', 't11','Nov', 't12','Des', 'total', 'rata', 'last upload'
          ));
        $this->excel_generator->set_column(array
          (
            'naper', 
            'sub', 
            'kode_comp',
            'namacomp',
            't1',
            'b1',
            't2', 
            'b2',
            't3', 
            'b3',
            't4', 
            'b4',
            't5',
            'b5',
            't6', 
            'b6',
            't7',            
            'b7',
            't8', 
            'b8',
            't9', 
            'b9',
            't10', 
            'b10',
            't11', 
            'b11',
            't12', 
            'b12', 
            'total', 
            'rata', 
            'lastupload'
          ));
        $this->excel_generator->set_width(array(8, 5, 5, 20,5, 15,5, 15, 5,15, 5,15, 5,15, 5,15, 5,15, 5,15, 5,15, 5,15, 5,15, 5,15, 15, 15, 15));
        //$this->excel_generator->exportTo2007('Omzet'.'_'.$tahun.'_'.$supplier);   
        $this->excel_generator->exportTo2007('Omzet'.'_'.$segment3);    
    }

     public function data_omzet_barat(){

        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
            $data['page_content'] = 'sales/view_omzet_kosong_barat';
            $data['query'] = $this->model_omzet->getSuppbyid();
            $data['url'] = 'omzet/data_omzet_hasil/';
            $data['page_title'] = 'sales omzet';
            //$data['query'] = $this->model_omzet->getSuppbyid();
            $data['menu']=$this->db->query($this->querymenu);
            $this->template($data['page_content'],$data);
            
    }

    public function data_omzet_hasil_barat(){

        $data['page_content'] = 'sales/view_omzet_barat';
        $data['query'] = $this->model_omzet->getSuppbyid();
        $data['menu']=$this->db->query($this->querymenu);
        $data['year']=$this->input->post('tahun');
        $year = $this->input->post('tahun');
        $supplier = $this->input->post('supp');
        //echo $year;
        $data['tahun'] = $year;
        $data['supp'] = $supplier;
        $data['omzets']=$this->model_omzet->omzet_all_dp_barat($data);
        $this->template($data['page_content'],$data);
           
    }

    public function data_omzet_timur(){

        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
            $data['page_content'] = 'sales/view_omzet_kosong_timur';
            $data['query'] = $this->model_omzet->getSuppbyid();
            $data['url'] = 'omzet/data_omzet_hasil/';
            $data['page_title'] = 'sales omzet';
            $data['query'] = $this->model_omzet->getSuppbyid();
            $data['menu']=$this->db->query($this->querymenu);
            $this->template($data['page_content'],$data);
            
    }

    public function data_omzet_hasil_timur(){

        $data['page_content'] = 'sales/view_omzet_timur';
        $data['query'] = $this->model_omzet->getSuppbyid();
        $data['menu']=$this->db->query($this->querymenu);
        $data['year']=$this->input->post('tahun');
        $year = $this->input->post('tahun');
        $supplier = $this->input->post('supp');
        //echo $year;
        $data['tahun'] = $year;
        $data['supp'] = $supplier;
        $data['omzets']=$this->model_omzet->omzet_all_dp_timur($data);
        $this->template($data['page_content'],$data);
           
    }   

   
}
?>
