<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Sales_custom extends MY_Controller
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

    function Sales_custom()
    {
        set_time_limit(0);
        $this->load->library(array('table','template','Excel_generator'));
        $this->load->helper('url');
        //$this->load->model('sales_model','bmodel');
        $this->load->model('model_sales_custom','custom');
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
        redirect('sales_custom/omzetd2','refresh');
    }

    private function template($view,$data)
    {
        $this->template->set_title('MPM');
        $this->template->add_js('modules/skeleton.js');
        $this->template->add_css('modules/skeleton.css');
        $this->template->load_view($view, $data);
    }

    public function omzetd2()
    {
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
        $data['page_content'] = 'sales_custom/form_omzetd2';
        $data['url'] = 'sales_custom/proses_omzetd2/';
        $data['page_title'] = 'Omzet D2 ';
        $data['menu']=$this->db->query($this->querymenu);
        $this->template($data['page_content'],$data);  
    }

    public function proses_omzetd2()
    {
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
        $data['page_content'] = 'sales_custom/proses_omzetd2';
        $data['url'] = 'sales_custom/proses_omzetd2/';
        $data['page_title'] = 'Omzet D2 ';
        $data['tahun'] = $this->input->post('tahun');
        $data['query'] = $this->custom->proses_omzetd2($data);
        $data['menu']=$this->db->query($this->querymenu);
        $this->template($data['page_content'],$data);  
    }

    public function export_d2_all()
    {
        $id=$this->session->userdata('id');   
        /*cek hak DP apa saja yang dapat dilihat*/
        $this->db->where('id = '.'"'.$id.'"');
        $query = $this->db->get('mpm.user');
        foreach ($query->result() as $row) {
            $wilayah_nocab = $row->wilayah_nocab;
        }

        if ($wilayah_nocab == NULL || $wilayah_nocab == '')  
        {
            $query="
                select *
                from mpm.tbl_sales_custom a
                where a.userid = $id
                order by urutan
            ";       
            $hasil = $this->db->query($query);
        }else{
            $query="
                select *
                from mpm.tbl_sales_custom a
                where a.userid = $id and substr(kode,4,2) in ($wilayah_nocab)
                order by urutan
            ";                            
            $hasil = $this->db->query($query);
        }           
        $this->excel_generator->set_query($hasil);
        $this->excel_generator->set_header(array
            (
            'kode', 'nama_comp','sub', 'group','nama_group', 'unit1','unit2', 'unit3', 'unit4','unit5', 'unit6','unit7','unit8','unit9', 'unit10','unit11', 
            'unit12','omzet1', 'omzet2','omzet3', 'omzet4','omzet5', 'omzet6','omzet7', 'omzet8','omzet9', 'omzet10','omzet11', 'omzet12', 'ot1', 'ot2','ot3','ot4','ot5','ot6','ot7','ot8','ot9','ot10','ot11','ot12'
            ));
        $this->excel_generator->set_column(array
            (
            'kode', 'nama_comp','sub', 'group','nama_group', 'unit1','unit2', 'unit3', 'unit4','unit5', 'unit6','unit7','unit8','unit9', 'unit10','unit11', 
            'unit12','omzet1', 'omzet2','omzet3', 'omzet4','omzet5', 'omzet6','omzet7', 'omzet8','omzet9', 'omzet10','omzet11', 'omzet12', 'ot1', 'ot2','ot3','ot4','ot5','ot6','ot7','ot8','ot9','ot10','ot11','ot12'
            ));        
   
        $this->excel_generator->set_width(array(8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8));
        $this->excel_generator->exportTo2007('Omzet D2 (candy n beverages)');     

    }

    public function export_d2_candy()
    {
        $id=$this->session->userdata('id');   
        /*cek hak DP apa saja yang dapat dilihat*/
        $this->db->where('id = '.'"'.$id.'"');
        $query = $this->db->get('mpm.user');
        foreach ($query->result() as $row) {
            $wilayah_nocab = $row->wilayah_nocab;
        }

        if ($wilayah_nocab == NULL || $wilayah_nocab == '')  
        {
            $query="
                select *
                from mpm.tbl_sales_custom a
                where a.userid = $id and `group` ='G0102'
                order by urutan
            ";       
            $hasil = $this->db->query($query);
        }else{
            $query="
                select *
                from mpm.tbl_sales_custom a
                where a.userid = $id and substr(kode,4,2) in ($wilayah_nocab) and `group` ='G0102'
                order by urutan
            ";                            
            $hasil = $this->db->query($query);
        }           
        $this->excel_generator->set_query($hasil);
        $this->excel_generator->set_header(array
            (
            'kode', 'nama_comp','sub', 'group','nama_group', 'unit1','unit2', 'unit3', 'unit4','unit5', 'unit6','unit7','unit8','unit9', 'unit10','unit11', 
            'unit12','omzet1', 'omzet2','omzet3', 'omzet4','omzet5', 'omzet6','omzet7', 'omzet8','omzet9', 'omzet10','omzet11', 'omzet12', 'ot1', 'ot2','ot3','ot4','ot5','ot6','ot7','ot8','ot9','ot10','ot11','ot12'
            ));
        $this->excel_generator->set_column(array
            (
            'kode', 'nama_comp','sub', 'group','nama_group', 'unit1','unit2', 'unit3', 'unit4','unit5', 'unit6','unit7','unit8','unit9', 'unit10','unit11', 
            'unit12','omzet1', 'omzet2','omzet3', 'omzet4','omzet5', 'omzet6','omzet7', 'omzet8','omzet9', 'omzet10','omzet11', 'omzet12', 'ot1', 'ot2','ot3','ot4','ot5','ot6','ot7','ot8','ot9','ot10','ot11','ot12'
            ));        
   
        $this->excel_generator->set_width(array(8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8));
        $this->excel_generator->exportTo2007('Omzet D2 (candy)');     

    }

    public function export_d2_beverages()
    {
        $id=$this->session->userdata('id');   
        /*cek hak DP apa saja yang dapat dilihat*/
        $this->db->where('id = '.'"'.$id.'"');
        $query = $this->db->get('mpm.user');
        foreach ($query->result() as $row) {
            $wilayah_nocab = $row->wilayah_nocab;
        }

        if ($wilayah_nocab == NULL || $wilayah_nocab == '')  
        {
            $query="
                select *
                from mpm.tbl_sales_custom a
                where a.userid = $id and `group` ='G0103'
                order by urutan
            ";       
            $hasil = $this->db->query($query);
        }else{
            $query="
                select *
                from mpm.tbl_sales_custom a
                where a.userid = $id and substr(kode,4,2) in ($wilayah_nocab) and `group` ='G0103'
                order by urutan
            ";                            
            $hasil = $this->db->query($query);
        }           
        $this->excel_generator->set_query($hasil);
        $this->excel_generator->set_header(array
            (
            'kode', 'nama_comp','sub', 'group','nama_group', 'unit1','unit2', 'unit3', 'unit4','unit5', 'unit6','unit7','unit8','unit9', 'unit10','unit11', 
            'unit12','omzet1', 'omzet2','omzet3', 'omzet4','omzet5', 'omzet6','omzet7', 'omzet8','omzet9', 'omzet10','omzet11', 'omzet12', 'ot1', 'ot2','ot3','ot4','ot5','ot6','ot7','ot8','ot9','ot10','ot11','ot12'
            ));
        $this->excel_generator->set_column(array
            (
            'kode', 'nama_comp','sub', 'group','nama_group', 'unit1','unit2', 'unit3', 'unit4','unit5', 'unit6','unit7','unit8','unit9', 'unit10','unit11', 
            'unit12','omzet1', 'omzet2','omzet3', 'omzet4','omzet5', 'omzet6','omzet7', 'omzet8','omzet9', 'omzet10','omzet11', 'omzet12', 'ot1', 'ot2','ot3','ot4','ot5','ot6','ot7','ot8','ot9','ot10','ot11','ot12'
            ));        
   
        $this->excel_generator->set_width(array(8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8));
        $this->excel_generator->exportTo2007('Omzet D2 (beverages)');     

    }

    public function export_d2_jayaagung()
    {
        $id=$this->session->userdata('id');   
        /*cek hak DP apa saja yang dapat dilihat*/
        $this->db->where('id = '.'"'.$id.'"');
        $query = $this->db->get('mpm.user');
        foreach ($query->result() as $row) {
            $wilayah_nocab = $row->wilayah_nocab;
        }

        if ($wilayah_nocab == NULL || $wilayah_nocab == '')  
        {
            $query="
                select *
                from mpm.tbl_sales_custom a
                where a.userid = $id and `group` ='G0401'
                order by urutan
            ";       
            $hasil = $this->db->query($query);
        }else{
            $query="
                select *
                from mpm.tbl_sales_custom a
                where a.userid = $id and substr(kode,4,2) in ($wilayah_nocab) and `group` ='G0401'
                order by urutan
            ";                            
            $hasil = $this->db->query($query);
        }           
        $this->excel_generator->set_query($hasil);
        $this->excel_generator->set_header(array
            (
            'kode', 'nama_comp','sub', 'group','nama_group', 'unit1','unit2', 'unit3', 'unit4','unit5', 'unit6','unit7','unit8','unit9', 'unit10','unit11', 
            'unit12','omzet1', 'omzet2','omzet3', 'omzet4','omzet5', 'omzet6','omzet7', 'omzet8','omzet9', 'omzet10','omzet11', 'omzet12', 'ot1', 'ot2','ot3','ot4','ot5','ot6','ot7','ot8','ot9','ot10','ot11','ot12'
            ));
        $this->excel_generator->set_column(array
            (
            'kode', 'nama_comp','sub', 'group','nama_group', 'unit1','unit2', 'unit3', 'unit4','unit5', 'unit6','unit7','unit8','unit9', 'unit10','unit11', 
            'unit12','omzet1', 'omzet2','omzet3', 'omzet4','omzet5', 'omzet6','omzet7', 'omzet8','omzet9', 'omzet10','omzet11', 'omzet12', 'ot1', 'ot2','ot3','ot4','ot5','ot6','ot7','ot8','ot9','ot10','ot11','ot12'
            ));        
   
        $this->excel_generator->set_width(array(8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8));
        $this->excel_generator->exportTo2007('Omzet D2 (jaya agung)');     

    }

    public function export_d2_intrafood()
    {
        $id=$this->session->userdata('id');   
        /*cek hak DP apa saja yang dapat dilihat*/
        $this->db->where('id = '.'"'.$id.'"');
        $query = $this->db->get('mpm.user');
        foreach ($query->result() as $row) {
            $wilayah_nocab = $row->wilayah_nocab;
        }

        if ($wilayah_nocab == NULL || $wilayah_nocab == '')  
        {
            $query="
                select *
                from mpm.tbl_sales_custom a
                where a.userid = $id and `group` ='G1201'
                order by urutan
            ";       
            $hasil = $this->db->query($query);
        }else{
            $query="
                select *
                from mpm.tbl_sales_custom a
                where a.userid = $id and substr(kode,4,2) in ($wilayah_nocab) and `group` ='G1201'
                order by urutan
            ";                            
            $hasil = $this->db->query($query);
        }           
        $this->excel_generator->set_query($hasil);
        $this->excel_generator->set_header(array
            (
            'kode', 'nama_comp','sub', 'group','nama_group', 'unit1','unit2', 'unit3', 'unit4','unit5', 'unit6','unit7','unit8','unit9', 'unit10','unit11', 
            'unit12','omzet1', 'omzet2','omzet3', 'omzet4','omzet5', 'omzet6','omzet7', 'omzet8','omzet9', 'omzet10','omzet11', 'omzet12', 'ot1', 'ot2','ot3','ot4','ot5','ot6','ot7','ot8','ot9','ot10','ot11','ot12'
            ));
        $this->excel_generator->set_column(array
            (
            'kode', 'nama_comp','sub', 'group','nama_group', 'unit1','unit2', 'unit3', 'unit4','unit5', 'unit6','unit7','unit8','unit9', 'unit10','unit11', 
            'unit12','omzet1', 'omzet2','omzet3', 'omzet4','omzet5', 'omzet6','omzet7', 'omzet8','omzet9', 'omzet10','omzet11', 'omzet12', 'ot1', 'ot2','ot3','ot4','ot5','ot6','ot7','ot8','ot9','ot10','ot11','ot12'
            ));        
   
        $this->excel_generator->set_width(array(8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8));
        $this->excel_generator->exportTo2007('Omzet D2 (intrafood)');     

    }



   
}
?>