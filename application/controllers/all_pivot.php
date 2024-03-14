<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class All_pivot extends MY_Controller {

    function All_pivot()
    {
        set_time_limit(0);
        $this->load->library(array('table','template','Excel_generator'));
        $this->load->helper('url');
        $this->load->model('model_pivot');
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

        $data['page_content'] = 'pivot/form_pivot'; 
        $data['url'] = 'all_pivot/data_pivot_hasil/';
        $data['page_title'] = 'Pivot Table';
        $data['query2']=$this->model_pivot->list_wilayah_pivot();
        $data['query']=$this->model_pivot->list_kategori_pivot();
            
        $data['menu']=$this->db->query($this->querymenu);
        $this->template($data['page_content'],$data);
       
    }

    public function data_pivot_hasil(){
    
      $data['year'] = $this->input->post('year');
      $data['kategori'] = $this->input->post('id_kategori');
      $data['wilayah'] = $this->input->post('no_wilayah');

      $data['url'] = 'all_pivot/data_pivot_hasil/';
      $data['page_title'] = 'Pivot Table';
      $data['query2']=$this->model_pivot->list_wilayah_pivot();
      $data['query']=$this->model_pivot->list_kategori_pivot(); 

      $data['query3']=$this->model_pivot->pivot_dp($data);
      $data['menu']=$this->db->query($this->querymenu);

      /* view disesuaikan dengan kategori yang dipilih */
      $kategori = $this->input->post('id_kategori');
      //echo "kategori : ".$kategori;
      if ($kategori == '1') //jika pilih kategori DP
      {
         $data['page_content'] = 'pivot/pivot_view';
      }elseif ($kategori == '2') //jika pilih kategori BSP
      {
         $data['page_content'] = 'pivot/pivot_bsp';
      }elseif ($kategori == '4') //jika pilih kategori PERMEN
      {
         $data['page_content'] = 'pivot/pivot_permen';
      } 
      else {
          # code...
      }
      
      /* end view disesuaikan dengan kategori yang dipilih */

      
      
      $this->template($data['page_content'],$data);
    

    }

}

/* End of file all_pivot.php */
/* Location: ./application/controllers/all_pivot.php */