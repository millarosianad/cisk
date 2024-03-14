<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class All_report extends MY_Controller
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

    function All_report()
    {
        set_time_limit(0);
        $this->load->library(array('table','template','Excel_generator', 'form_validation'));
        $this->load->helper('url');
        //$this->load->model('sales_model','bmodel');
        $this->load->model('model_report');
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

        //echo "suffy";

    }

    private function template($view,$data)
    {
        $this->template->set_title('MPM SQUARE');
        $this->template->add_js('modules/skeleton.js');
        $this->template->add_css('modules/skeleton.css');
        $this->template->load_view($view, $data);
    }

    public function report_po(){

        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
            $data['page_content'] = 'report/view_report_po_kosong';
            //$data['query'] = $this->model_omzet->getSuppbyid();
            $data['url'] = 'all_report/report_po/';
            $data['page_title'] = 'Report PO';
            //$data['query'] = $this->model_omzet->getSuppbyid();
            $data['menu']=$this->db->query($this->querymenu);
            $this->template($data['page_content'],$data);        
    }

    public function data_po_hasil(){

        $data['page_content'] = 'report/view_po';
        //$data['query'] = $this->model_po->getSuppbyid();
        $data['menu']=$this->db->query($this->querymenu);
        //$data['year']=$this->input->post('tahun');
        $year = $this->input->post('tahun');
        $month = $this->input->post('bulan');
        echo $year;
        echo $month;
        
        $data['tahun'] = $year;
        $data['bulan'] = $month;
        $data['reports']=$this->model_report->report_po($data);
        $this->template($data['page_content'],$data);
           
    }

    public function list_po(){

        $this->load->library('form_validation');

        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
            $data['page_content'] = 'report/view_list_po';
            
            $data['url'] = 'all_report/list_po/';
            $data['page_title'] = 'List PO';            
            $data['menu']=$this->db->query($this->querymenu);
            $data['reports']=$this->model_report->cek_status_po($data);
            $data['reports']=$this->model_report->list_po($data);
            $this->template($data['page_content'],$data);        
    }

    public function list_po_branch(){

        $this->load->library('form_validation');

        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
            $data['page_content'] = 'report/view_list_po_branch';
            
            $data['url'] = 'all_report/list_po/';
            $data['page_title'] = 'List PO';            
            $data['menu']=$this->db->query($this->querymenu);
            $data['reports']=$this->model_report->cek_status_po($data);
            $data['reports']=$this->model_report->list_po_branch($data);
            $this->template($data['page_content'],$data);        
    }

    public function list_po_custom($kategori, $supp, $tahun, $bulan ){

        $this->load->library('form_validation');
        /*
        echo $kategori;
        echo $supp;
        echo $bulan;
        echo $tahun;
        */
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
            $data['page_content'] = 'report/view_list_po';
            
            $data['url'] = 'all_report/list_po/';
            $data['page_title'] = 'List PO';            
            $data['menu']=$this->db->query($this->querymenu);

            $data['kategori'] = $kategori;
            $data['supplier'] = $supp;
            $data['bulan'] = $bulan;
            $data['tahun'] = $tahun;

            $data['reports']=$this->model_report->list_po_custom($data);
            $this->template($data['page_content'],$data);        
    }

    public function detail_po(){

        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }

        $this->load->library('form_validation');

        $segment = $this->uri->segment('3');

        $data['page_content'] = 'report/view_detail_po';
        $data['segments'] = $segment;
        $data['url'] = 'all_report/detail_po/';
        $data['page_title'] = 'Detail PO'; 
        $data['menu']=$this->db->query($this->querymenu);

        $data['reports']=$this->model_report->detail_po($data);
        $data['singles']=$this->model_report->single_po($data);
        $this->template($data['page_content'],$data);
    }

    public function detail_po_branch(){

        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }

        $this->load->library('form_validation');

        $segment = $this->uri->segment('3');

        $data['page_content'] = 'report/view_detail_po_branch';
        $data['segments'] = $segment;
        $data['url'] = 'all_report/detail_po/';
        $data['page_title'] = 'Detail PO'; 
        $data['menu']=$this->db->query($this->querymenu);

        $data['reports']=$this->model_report->detail_po($data);
        $data['singles']=$this->model_report->single_po($data);
        $data['principles']=$this->model_report->single_po_branch($data);
        $this->template($data['page_content'],$data);
    }

    public function proses($id){
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }

        $id=$this->session->userdata('id');
        //echo "<br><br>id : ".$id; 

        $this->form_validation->set_rules('nodo', 'No Surat Jalan', 'required');
        $this->form_validation->set_rules('tgldo', 'Tanggal Pengiriman', 'required');

        if($this->form_validation->run() === FALSE)
        {
            $segment = $this->uri->segment('3');
            $data['page_content'] = 'report/view_detail_po';
            $data['segments'] = $segment;
            $data['url'] = 'all_report/detail_po/';
            $data['page_title'] = 'Detail PO';            
            $data['menu']=$this->db->query($this->querymenu);

            $data['reports']=$this->model_report->detail_po($data);
            $data['singles']=$this->model_report->single_po($data);
            $this->template($data['page_content'],$data);


            //$this->load->view('report/view_detail_po');
            //echo "ada kesalahan";
            //redirect('all_report/list_po','refresh');
            
        }else{
            
            $data_report = array(
                'nodo'      => set_value('nodo'),
                'tgldo'     => set_value('tgldo')
            );
            
            //$this->model_report->update($id, $data_report);
            //redirect('all_report/list_po');
            
            $data['nodo'] = $data_report['nodo'];
            $data['tgldo'] = $data_report['tgldo'];
            $segment = $this->uri->segment('3');
            $data['segments'] = $segment;
            //echo $data['nodo'];
            //echo $data['tgldo'];

            $data['reports']=$this->model_report->proses_po($data);

            //echo "validation success";
        }
    }

    public function proses_branch($id){
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }

        $id=$this->session->userdata('id');
        //echo "<br><br>id : ".$id; 

        $this->form_validation->set_rules('tglterima', 'Tanggal Terima', 'required');

        if($this->form_validation->run() === FALSE)
        {
            $segment = $this->uri->segment('3');
            $data['page_content'] = 'report/view_detail_po_branch';
            $data['segments'] = $segment;
            
            $data['page_title'] = 'Detail PO';            
            $data['menu']=$this->db->query($this->querymenu);

            $data['reports']=$this->model_report->detail_po($data);
            $data['singles']=$this->model_report->single_po($data);
            $data['principles']=$this->model_report->single_po_branch($data);
            $this->template($data['page_content'],$data);


            //$this->load->view('report/view_detail_po');
            //echo "ada kesalahan";
            //redirect('all_report/list_po','refresh');
            
        }else{
            
            $data_report = array(
                'tglterima' => set_value('tglterima')
            );
            
            //$this->model_report->update($id, $data_report);
            //redirect('all_report/list_po');
            
            $data['tglterima'] = $data_report['tglterima'];
            $segment = $this->uri->segment('3');
            $data['segments'] = $segment;
            //echo $data['tglterima'];
            $data['reports']=$this->model_report->proses_po_branch($data);
        }
        
        

    }

    public function test_email(){
        
        $config['protocol'] = "smtp";
        $config['smtp_host'] = "ssl://smtp.gmail.com";
        $config['smtp_port'] = "465";
        $config['smtp_user'] = "suffy.yanuar@gmail.com";
        $config['smtp_pass'] = "yanuar123!@#";
        $config['charset'] = "utf-8";
        $config['mailtype'] = "html";
        $config['newline'] = "\r\n";

        $this->load->library('email', $config);

        $this->email->to('suffy.yanuar@gmail.com');
        $this->email->from('suffy.yanuar@gmail.com','test');
        $this->email->subject('JUDUL EMAIL (Teks)');
        $this->email->message('Isi email ditulis disini');
        $this->email->send();
        echo $this->email->print_debugger();
    }
   
}
?>
