<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Target extends MY_Controller
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

    function Target()
    {
        set_time_limit(0);
        $this->load->library(array('table','template','Excel_generator'));
        $this->load->helper(array('url','csv'));
        //$this->load->model('sales_model','bmodel');
        $this->load->model(array('model_omzet','livetable_model'));
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
        redirect('target/inputTarget','refresh');
    }

    private function template($view,$data)
    {
        $this->template->set_title('MPM');
        $this->template->add_js('modules/skeleton.js');
        $this->template->add_css('modules/skeleton.css');
        $this->template->load_view($view, $data);
    }

    public function inputTarget(){
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
            $data['page_content'] = 'target/formTarget';
            $data['query'] = $this->model_omzet->getSuppbyid();
            $data['url'] = 'target/inputTargetProses/';
            $data['page_title'] = 'input target';
            $data['query'] = $this->model_omzet->getSuppbyid();
            $data['menu']=$this->db->query($this->querymenu);
            $this->template($data['page_content'],$data);   
    }

    public function inputTargetProses(){
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
        $data['tahun'] = $this->input->post('tahun');
        $data['bulan'] = $this->input->post('bulan');
        $data['page_content'] = 'target/live_table';
        $data['query'] = $this->model_omzet->getSuppbyid();
        $data['url'] = 'target/inputTargetProses/';
        $data['page_title'] = 'input target';
        $data['menu']=$this->db->query($this->querymenu);
        $this->template($data['page_content'],$data);
    }

    public function getData(){        
        
        // $this->load->view('live_table');
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
        $data['page_content'] = 'target/live_table';
        $data['page_title'] = 'sales omzet';
        $data['query'] = $this->model_omzet->getSuppbyid();
        $data['menu']=$this->db->query($this->querymenu);
        $this->template($data['page_content'],$data); 
    }

    function load_data()
    {
        $data['tahun'] = $this->uri->segment('3');
        $data['bulan'] = $this->uri->segment('4');
        $data = $this->livetable_model->load_data($data);
        echo json_encode($data);
    }

    function insert()
    {
        $data = array(
        'first_name' => $this->input->post('first_name'),
        'last_name'  => $this->input->post('last_name'),
        'age'   => $this->input->post('age')
        );

        $this->livetable_model->insert($data);
    }

    function update()
    {
        $data = array(
        $this->input->post('table_column') => $this->input->post('value')
        );

        $this->livetable_model->update($data, $this->input->post('id'));
    }

    function delete()
    {
        $this->livetable_model->delete($this->input->post('id'));
    }

    public function export(){
        
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
        $tahun=$this->uri->segment('3');
        $bulan = $this->uri->segment('4');
       
        $sql = "
        select  id, kode_comp, nama_comp, 
                if(bulan = 1,'jan',if(bulan = 2,'feb',if(bulan = 3,'mar',if(bulan = 4,'apr',if(bulan = 5,'mei',if(bulan=6,'jun',if(bulan=7,'jul',if(bulan=8,'agu',if(bulan=9,'sep',if(bulan=10,'okt',if(bulan=11,'nov',if(bulan=12,'des',0)))))))))))) as bulan,
                tahun,candy,herbal,(candy + herbal) as deltomed,marguna,us,natura,intrafood
        from db_target.t_target_omzet
        where tahun = $tahun and bulan = $bulan
        order by urutan asc
        ";
        $quer  =   $this->db->query($sql);
      
        query_to_csv($quer,TRUE,"target-$tahun-$bulan.csv");
    }

    

   
}
?>
