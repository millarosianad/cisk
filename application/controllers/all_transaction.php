<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class All_transaction extends MY_Controller
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

    function All_transaction()
    {
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
        
        set_time_limit(0);
        $this->load->library(array('table','template','Excel_generator'));
        $this->load->helper('url');
        //$this->load->model('sales_model','bmodel');
        $this->load->model('model_transaction');
        $this->load->model('model_sales_omzet');
        $this->load->database();
        $this->querymenu='select a.id,a.menuview,a.target,a.groupname from mpm.menu a inner join mpm.menudetail b on a.id=b.menuid where b.userid='. $this->session->userdata('id') . ' and active=1 order by a.groupname,a.menuview ';
    }

    private function template($view,$data)
    {
        $this->template->set_title('MPM');
        $this->template->add_js('modules/skeleton.js');
        $this->template->add_css('modules/skeleton.css');
        $this->template->load_view($view, $data);
    }


    function index()
    {
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }

        //$this->data_po();
        //redirect('omzet/omzet_2016','refresh');
        //redirect('omzet/data_omzet','refresh');

    }

    public function open_credit_limit(){

        $data['grup_lang'] = "";
        $grup_lang = $this->input->post('grup_lang');
        
        $data['query_pel']=$this->model_transaction->list_pelanggan_po($data);
        $id = $this->session->userdata('id');
        $date = $this->model_sales_omzet->timezone2();
        $delete = $this->model_transaction->update_piutang_sds($id,$date);
        $data['query'] = $this->model_transaction->tampil_credit_limit();
        
        if ($id == '231' || $id == '134'|| $id == '297' || $id == '547') {
            $data['page_content'] = 'all_transaction/view_po';
            $data['last_updated'] = $this->model_transaction->get_tanggal_piutang_sds($id);
        }else{
            $data['page_content'] = 'all_transaction/view_po_no_approval';
        }
        
        
        $data['url'] = 'All_transaction/open_credit_limit_client/';
        $data['page_title'] = 'data transaction';
        $data['menu']=$this->db->query($this->querymenu);
        $this->template($data['page_content'],$data); 
    
    }

    public function open_credit_limit_client(){

        //$data['grup_lang'] = "";
        $data['grup_lang'] = $this->input->post('grup_lang');
        $tgl = $this->input->post('tgl');
        //echo "<br><br><br>grup lang controller : ".$grup_lang;
        $data['query_pel']=$this->model_transaction->list_pelanggan_po($data);
        $data['grup_lang']=$this->input->post('grup_lang');
        $data['tgl'] = date('Y-m-d', strtotime($tgl)); 
        
        $id = $this->session->userdata('id');
        if ($id == '231' || $id == '134' || $id == '297' || $id == '547') {
            $data['last_updated'] = $this->model_transaction->get_tanggal_piutang_sds($id);
            $data['page_content'] = 'all_transaction/view_po';
        }else{
            $data['page_content'] = 'all_transaction/view_po_no_approval';
        }
        $data['query'] = $this->model_transaction->tampil_credit_limit_client($data);
        $data['url'] = 'All_transaction/open_credit_limit_client/';
        $data['page_title'] = 'data transaction';
        $data['menu']=$this->db->query($this->querymenu);
        $this->template($data['page_content'],$data); 
        
    }

    public function open_credit_limit_detail(){

        $this->load->library('form_validation');

        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
        $data['page_content'] = 'All_transaction/detail_po';                      
        $data['menu']=$this->db->query($this->querymenu);
        $data['query_piutang']=$this->model_transaction->piutang();
        $data['query']=$this->model_transaction->detail_po();
        $this->template($data['page_content'],$data);      
            
    }

    public function open_credit_limit_update($id){

        $segment = $this->uri->segment(3);
        $id=$this->session->userdata('id');
        echo "<br><br>id : ".$id; 
        echo "<br><br>segment : ".$segment;
        $note_acc = $this->input->post('note_acc');
        echo "x : ".$note_acc;
        
        $data = array(
            'id'          => $segment,
            'userid'      => $id,
            'note_acc'    => $note_acc
        );    
        //echo "<br>note_acc : ".$data['note_acc'];
            
        $data['query']=$this->model_transaction->proses_open_credit_limit($data);            
        
    }

    public function unlock($id){

        $segment = $this->uri->segment(3);
        $id=$this->session->userdata('id');
        echo "<br><br>id : ".$id; 
        echo "<br><br>segment : ".$segment;
        
        $data = array(
            'id'          => $segment,
            'userid'      => $id
        );            
            
        $data['query']=$this->model_transaction->proses_unlock_credit_limit($data);         

    }

    public function manual_update()
    {
        $id = $this->session->userdata('id');
        $date = $this->model_sales_omzet->timezone2();
        $this->db->where('id', $id);
        $this->db->delete('db_analisis.t_temp_piutang');
        $this->model_transaction->get_data_dbsls($date);

        redirect('all_transaction/open_credit_limit');
    }
}
?>
