<?php

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class All_charts extends MY_Controller
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

    function All_charts()
    {
        set_time_limit(0);
        $this->load->library(array('table','template','Excel_generator'));
        $this->load->helper('url');
        $this->load->model('model_charts');
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

        redirect('all_charts/charts_omzet','refresh');

    }

    private function template($view,$data)
    {
        $this->template->set_title('MPM');
        $this->template->add_js('modules/skeleton.js');
        $this->template->add_css('modules/skeleton.css');
        $this->template->load_view($view, $data);
    }

    public function charts_omzet(){

        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
            $data['page_content'] = 'charts/view_omzet_kosong';
            $data['url'] = 'all_charts/charts_omzet_hasil/';
            $data['page_title'] = 'Chart Omzet & Trendline';
            $data['query'] = $this->model_charts->getSuppbyid();
            $data['menu_uri1'] = $this->uri->segment('1');
            $data['menu_uri2'] = $this->uri->segment('2');
            $data['menu_uri3'] = $this->uri->segment('3');

            $data['getmenuid'] = $this->model_charts->getmenuid($data);
            $data['menu']=$this->db->query($this->querymenu);
            $this->template($data['page_content'],$data);       

        
    }

    public function charts_omzet_hasil(){

        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }

        $year = $this->input->post('year');
        $periode = $this->input->post('periode');
        $supplier = $this->input->post('supplier');
        $group = $this->input->post('group');
        $branch = $this->input->post('branch');
        /*
        echo "<pre>";
        echo "year : ".$year."<br>";
        echo "periode : ".$periode."<br>";
        echo "year : ".$year."<br>";
        echo "supplier : ".$supplier."<br>";
        echo "group : ".$group."<br>";
        echo "branch : ".$branch."<br>";
        echo "</pre>";
        */
        if($group == '0') {
            $data['tabel'] = 'omzet_new';
        }elseif($group == '4'){
            $data['tabel'] = 'omzet_new_herbal';
        }elseif($group == '5'){
            $data['tabel'] = 'omzet_new_candy';
        }elseif($group == '6'){
            $data['tabel'] = 'omzet_new_freshcare';
        }elseif($group == '7'){
            $data['tabel'] = 'omzet_new_hotin';
        }elseif($group == '8'){
            $data['tabel'] = 'omzet_new_madu';
        }elseif($group == '9'){
            $data['tabel'] = 'omzet_new_mywell';
        }elseif($group == '10'){
            $data['tabel'] = 'omzet_new_tresnojoyo';
        }elseif($group == '11'){
            $data['tabel'] = 'omzet_new_otherus';
        }elseif($group == '12'){
            $data['tabel'] = 'omzet_new_beverages';
        }elseif($group == '13'){
            $data['tabel'] = 'omzet_new_pilkita';
        }elseif($group == '14'){
            $data['tabel'] = 'omzet_new_otherm';
        }elseif($group == '10'){
            $data['tabel'] = 'omzet_new_tresnojoyo';
        }

        else{
            echo "ada kesalahan";
        }

        if ($periode == '1') {
            $page = 'charts/view_omzet';
            $periode_note = 'full 1 tahun';
        }elseif ($periode == '2') {
            $page = 'charts/view_omzet_a';
            $periode_note = 'Jan - Jun';
        } 
        else {
            $page = 'charts/view_omzet_b';
            $periode_note = 'Jul - Des';
        }
        
        
        $data['years'] = $this->input->post('year');
        $data['periodes'] = $periode_note;
        $data['suppliers'] = $this->input->post('supplier');
        $data['group'] = $this->input->post('group');
        $data['branch'] = $this->input->post('branch');

        $data['page_content'] = $page;
        $data['url'] = 'all_charts/charts_omzet_hasil/';
        $data['page_title'] = 'Chart Omzet & Trendline';
        $data['query'] = $this->model_charts->getSuppbyid();
        $data['menu_uri1'] = $this->uri->segment('1');
        $data['menu_uri2'] = $this->uri->segment('2');
        $data['menu_uri3'] = $this->uri->segment('3');

        $data['getmenuid'] = $this->model_charts->getmenuid($data);
        $data['menu']=$this->db->query($this->querymenu);

        $data['note'] ='';
        $data['omzets']=$this->model_charts->omzet_charts($data);
        $this->template($data['page_content'],$data);     
        
    }

    

   
}
?>
