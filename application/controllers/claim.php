<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Claim extends MY_Controller
{
    function claim()
    {
        set_time_limit(0);
        $this->load->library(array('table','template','Excel_generator', 'form_validation','email'));
        $this->load->helper('url');
        $this->load->helper('csv');
        $this->load->model('M_loyalty');
        $this->load->model('M_claim');
        $this->load->model('M_menu');
        // $this->load->model('M_claim');
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

    public function ant_group(){
        $data = [
            'id' => $this->session->userdata('id'),
            'title' => 'Loyalty - Antangin Group Q3',
            'get_label' => $this->M_menu->get_label(),
            'get_sales' => $this->M_loyalty->get_sales_q3(),
            // 'get_ant_group' => $this->M_loyalty->get_ant_group(),
            // 'get_ant_group' => $this->M_loyalty->get_ant_group_q3()
        ];
        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar',$data);
        $this->load->view('template_claim/top_content',$data);
        $this->load->view('loyalty/ant_group_q3',$data);
        // $this->load->view('loyalty/ant_group',$data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
    }

    public function export_ant_group_q3(){
        $this->load->helper('csv');
        $id=$this->session->userdata('id');
        $sql = "
        select * from db_loyalty.t_loyalty_ant_group_q3_report a order by urutan
        ";
        $quer = $this->db->query($sql);

        query_to_csv($quer,TRUE,'Loyalty Q3 2020 - Antangin Group.csv');
    }

    public function ant_group_sm1(){
        $data = [
            'id' => $this->session->userdata('id'),
            'title' => 'Loyalty - Antangin Group SM 1',
            'get_label' => $this->M_menu->get_label(),
            // 'get_sales' => $this->M_loyalty->get_sales_q3(),
            'get_ant_group' => $this->M_loyalty->get_herbal_sm_1(),
            // 'get_ant_group' => $this->M_loyalty->get_ant_group_q3()
        ];
        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar',$data);
        $this->load->view('template_claim/top_content',$data);
        // $this->load->view('loyalty/ant_group_q3',$data);
        $this->load->view('loyalty/ant_group',$data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
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
            'get_label' => $this->M_menu->get_label(),
            'get_data' => $this->M_loyalty->get_ob_herbal()
        ];
        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar',$data);
        $this->load->view('template_claim/top_content',$data);
        $this->load->view('template_sales/content_ob_herbal',$data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
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
            'get_label' => $this->M_menu->get_label(),
            'get_data' => $this->M_loyalty->get_candy()
        ];
        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar',$data);
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

    public function tabel_program(){
        $data = [
            'id' => $this->session->userdata('id'),
            'title' => 'Tabel Program',
            'url' => 'claim/tabel_program_proses',
            'get_group_menu' => $this->M_menu->get_group_menu(),
            'get_program' => $this->M_claim->get_program()
        ];
        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar',$data);
        $this->load->view('template_claim/top_content',$data);
        $this->load->view('template_claim/tabel_program',$data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
    }

    public function potensi_claim(){
        $data = [
            'id'                => $this->session->userdata('id'),
            'title'             => 'Potensi Claim - DP',
            'url'               => 'claim/potensi_claim_proses',
            // 'get_struktur'      => $this->M_menu->get_strukur(),
            'get_label'         => $this->M_menu->get_label()
        ];
        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar',$data);
        $this->load->view('template_claim/top_content',$data);
        $this->load->view('template_claim/potensi_claim',$data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
    }

    public function potensi_claim_proses(){
        $from = $this->input->post('from');
        $to = $this->input->post('to');
        
        $data = [
            'id' => $this->session->userdata('id'),
            'title' => 'Potensi Claim - DP',
            'url' => 'claim/potensi_claim_proses',
            'get_group_menu' => $this->M_menu->get_group_menu(),
            'get_program' => $this->M_claim->get_program($from,$to)
        ];
        
        $this->load->view('template_claim/top_header');
        $this->load->view('template_claim/header');
        $this->load->view('template_claim/sidebar',$data);
        $this->load->view('template_claim/top_content',$data);
        $this->load->view('template_claim/potensi_claim_proses',$data);
        $this->load->view('template_claim/bottom_content');
        $this->load->view('template_claim/footer');
    }




}
?>
