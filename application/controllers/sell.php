<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Sell extends MY_Controller
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

    function Sell()
    {
        set_time_limit(0);
        $this->load->library(array('table','template','Excel_generator'));
        $this->load->helper('url');
        $this->load->model(array('model_sell','m_dp'));
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

    function index()
    {

        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }

        //redirect('omzet/omzet_2016','refresh');
        redirect('sell/sell_out_dp','refresh');

    }

    public function sell_out_dp(){

        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
            //$data['page_content'] = 'sell/view_sell_out_dp_kosong';
            $data['page_content'] = 'sell/form_sell_out_dp';
            $data['query'] = $this->m_dp->getSuppbyid();
            $data['url'] = 'sell/sell_out_dp_hasil/';
            $data['page_title'] = 'Sell Out DP';
            $data['menu']=$this->db->query($this->querymenu);
            $data['query2']=$this->m_dp->list_dp();
            $this->template($data['page_content'],$data);
            
    }

    public function sell_out_dp_hasil(){
    
        $data['page_content'] = 'sell/view_sell_out_dp';
        $data['query'] = $this->m_dp->getSuppbyid();
        $data['url'] = 'sell/sell_out_dp_hasil/';
        $data['page_title'] = 'Sell Out DP';
        $data['menu']=$this->db->query($this->querymenu);
        $data['query2']=$this->m_dp->list_dp();

        $data['year'] = $this->input->post('year');
        $data['dp'] = $this->input->post('nocab');
        $data['supp'] = $this->input->post('supp');
        $data['group'] = $this->input->post('group');
        $data['uv'] = $this->input->post('uv');
        
        $data['kode_group'] = $this->m_dp->get_kode_group($data);
        $data['sell_out_dp_hasil']=$this->model_sell->sell_out_dp($data);        
        $this->template($data['page_content'],$data);
         
    }

    function get_sell_out_dp(){

        $id_user=$this->session->userdata('id');
        $draw=$_REQUEST['draw'];
        $length=$_REQUEST['length'];
        $start=$_REQUEST['start'];
        $search=$_REQUEST['search']["value"];
        $this->db->where('id', $id_user);
        $total=$this->db->count_all_results("sodp_new");
        $output=array();
        $output['draw']=$draw;
        $output['recordsTotal']=$output['recordsFiltered']=$total;
        $output['data']=array();
        if($search!=""){
        $this->db->like("kodeprod",$search);
        }
        $this->db->limit($length,$start);
        $this->db->order_by('kodeprod','ASC');
        $this->db->where('id', $id_user);
        $query=$this->db->get('sodp_new');
    
        if($search!=""){
        $this->db->like("kodeprod",$search);
        $jum=$this->db->get('sodp_new');
        $output['recordsTotal']=$output['recordsFiltered']=$jum->num_rows();
        }
    
        $nomor_urut=$start+1;
        foreach ($query->result_array() as $kode) {
          $output['data'][]=array(
            $nomor_urut,
            $kode['kodeprod'],
            $kode['namaprod'],
            number_format($kode['b1']),
            number_format($kode['b2']),
            number_format($kode['b3']),
            number_format($kode['b4']),
            number_format($kode['b5']),
            number_format($kode['b6']),
            number_format($kode['b7']),
            number_format($kode['b8']),
            number_format($kode['b9']),
            number_format($kode['b10']),
            number_format($kode['b11']),
            number_format($kode['b12'])
          );
        $nomor_urut++;
        }
    
        echo json_encode($output);
    }

    public function export() {
        
        $id_user=$this->session->userdata('id');

        $this->db->where("id = ".'"'.$id_user.'"');
        $this->db->order_by('kodeprod','asc');
        $hasil = $this->db->get('sodp_new');
       
        $this->excel_generator->set_query($hasil);
        $this->excel_generator->set_header(array
          (
            'kodeprod', 'namaprod', 'Jan', 'Feb','Mar','Apr','Mei','Jun', 'Jul','Agus','Sep','Okt','Nov','Des'
          ));
        $this->excel_generator->set_column(array
          (
            'kodeprod', 
            'namaprod', 
            'b1',
            'b2', 
            'b3', 
            'b4',
            'b5', 
            'b6',            
            'b7', 
            'b8', 
            'b9',
            'b10', 
            'b11', 
            'b12'
          ));
        $this->excel_generator->set_width(array(10,60,8,8,8,8,8,8,8,8,8,8,8,8));
        //$this->excel_generator->exportTo2007('Omzet'.'_'.$tahun.'_'.$supplier);   
        $this->excel_generator->exportTo2007('SellOutDP');   
    }
        
   
}
?>
