<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class C_dp extends MY_Controller
{
    

    function C_dp()
    {
        set_time_limit(0);
        $this->load->library(array('table','template','Excel_generator'));
        $this->load->helper('url');
        $this->load->model('m_dp');
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

        $data['page_content'] = 'sales/form_outlet';         
        
        //$data['omzets']=$this->model_omzet->omzet_all();
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


    function build_namacomp()
    {
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
        $year = $this->input->post('id_year',TRUE);
        //$output="<option value=''>- Pilih Sub Branch -</option>";
    
        $data['year'] = $year;
        $query=$this->m_dp->get_namacomp($data);
        foreach($query->result() as $row)
        {
            $output .= "<option value='".$row->kode."'>".$row->nama_comp."</option>";
        }
        echo $output;
    }

    function buildgroup()
    {
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
        $kode_supp = $this->input->post('kode_supp',TRUE);
        $output="<option value='0'>--</option>";
        $data['kode_supp'] = $kode_supp;
        $query=$this->m_dp->get_group($data);
        foreach($query->result() as $row)
        {
            $output .= "<option value='".$row->id_group."'>".$row->nama_group."</option>";
        }
        echo $output;
    }


}
?>
