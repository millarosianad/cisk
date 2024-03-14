<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Report_sales extends MY_Controller
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

    function report_sales()
    {
        set_time_limit(0);
        $this->load->library(array('table','template','Excel_generator', 'form_validation','email'));
        $this->load->helper('url');
        $this->load->helper('csv');
        $this->load->model('M_loyalty');
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

    public function ant_group(){
        $data = [
            'id' => $this->session->userdata('id'),
            'title' => 'Loyalty - Antangin Group',
            'get_herbal_sm_1' => $this->M_loyalty->get_herbal_sm_1()
        ];
        $this->load->view('template_sales/top_header');
        $this->load->view('template_sales/header');
        $this->load->view('template_sales/sidebar');
        $this->load->view('template_sales/top_content',$data);
        $this->load->view('template_sales/content',$data);
        $this->load->view('template_sales/bottom_content');
        $this->load->view('template_sales/footer');
    }

    public function export_ant_group(){
        $this->load->helper('csv');
        $id=$this->session->userdata('id');
        $sql = "
        select * from db_loyalty.t_report_loyalty_sm1_antangin_group
        ";
        $quer = $this->db->query($sql);

        query_to_csv($quer,TRUE,'Loyalty Semester 1 2020 - Antangin Group.csv');
    }

    public function ob_herbal(){
        $data = [
            'id' => $this->session->userdata('id'),
            'title' => 'Loyalty - OB Herbal',
            'get_data' => $this->M_loyalty->get_ob_herbal()
        ];
        $this->load->view('template_sales/top_header');
        $this->load->view('template_sales/header');
        $this->load->view('template_sales/sidebar');
        $this->load->view('template_sales/top_content',$data);
        $this->load->view('template_sales/content_ob_herbal',$data);
        $this->load->view('template_sales/bottom_content');
        $this->load->view('template_sales/footer');
    }

    public function export_ob_herbal(){
        $this->load->helper('csv');
        $id=$this->session->userdata('id');
        $sql = "
        select * from db_loyalty.t_report_loyalty_sm1_ob_herbal
        ";
        $quer = $this->db->query($sql);

        query_to_csv($quer,TRUE,'Loyalty Semester 1 2020 - OB Herbal.csv');
    }

    public function candy(){
        $data = [
            'id' => $this->session->userdata('id'),
            'title' => 'Loyalty - Candy',
            'get_data' => $this->M_loyalty->get_candy()
        ];
        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_sales/sidebar');
        $this->load->view('template_claim/top_content',$data);
        $this->load->view('template_sales/content_candy',$data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
    }

    public function export_candy(){
        $this->load->helper('csv');
        $id=$this->session->userdata('id');
        $sql = "
        select * from db_loyalty.t_report_loyalty_sm1_candy
        ";
        $quer = $this->db->query($sql);

        query_to_csv($quer,TRUE,'Loyalty Semester 1 2020 - Candy.csv');
    }



}
?>
