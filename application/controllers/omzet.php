<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Omzet extends MY_Controller
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

    function Omzet()
    {
        set_time_limit(0);
        $this->load->library(array('table','template','Excel_generator'));
        $this->load->helper('url');
        //$this->load->model('sales_model','bmodel');
        $this->load->model('model_omzet');
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

        //redirect('omzet/omzet_2016','refresh');
        redirect('omzet/data_omzet','refresh');

    }

    public function data_omzet(){

        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
            $data['page_content'] = 'sales/view_omzet_kosong';
            $data['query'] = $this->model_omzet->getSuppbyid();
            $data['url'] = 'omzet/data_omzet_hasil/';
            $data['page_title'] = 'sales omzet';
            $data['query'] = $this->model_omzet->getSuppbyid();
            $data['menu_uri1'] = $this->uri->segment('1');
            $data['menu_uri2'] = $this->uri->segment('2');
            $data['menu_uri3'] = $this->uri->segment('3');

            $data['getmenuid'] = $this->model_omzet->getmenuid($data);
            $data['menu']=$this->db->query($this->querymenu);
            $this->template($data['page_content'],$data);  

            // $id = $this->session->userdata('id');
            // //echo "id : ".$id;
            // if ($id == 297) {
            //     //$this->info();
            // } else {
            //     $this->info();
            // }
            
                 
        
    }

    public function info(){
            $this->load->view('info_pop_up');
        }

        public function data_omzet_hasil()
        {
            $data['query'] = $this->model_omzet->getSuppbyid();
            $data['menu']=$this->db->query($this->querymenu);
            $data['year']=$this->input->post('tahun');
            $data['menuid']=$this->input->post('menuid');
            $data['url'] = 'omzet/data_omzet_hasil/';
            $data['group']=$this->input->post('group');
            $data['tipe1']=$this->input->post('tipe_1');
            $data['tipe2']=$this->input->post('tipe_2');
            $data['tipe3']=$this->input->post('tipe_3');
            $bln=$this->input->post('bulan');
            $data['bln']=$bln;
            if($bln == 1){
                $data['header'] = "Januari";
            }elseif($bln == 2){
                $data['header'] = "Februari";
            }elseif($bln == 3){
                $data['header'] = "Maret";
            }elseif($bln == 4){
                $data['header'] = "April";
            }elseif($bln == 5){
                $data['header'] = "Mei";
            }elseif($bln == 6){
                $data['header'] = "Juni";
            }elseif($bln == 7){
                $data['header'] = "Juli";
            }elseif($bln == 8){
                $data['header'] = "Agustus";
            }elseif($bln == 9){
                $data['header'] = "September";
            }elseif($bln == 10){
                $data['header'] = "Oktober";
            }elseif($bln == 11){
                $data['header'] = "November";
            }elseif($bln == 12){
                $data['header'] = "Desember";
            }
    
            $year = $this->input->post('tahun');
            $supplier = $this->input->post('supp');
            $group=$this->input->post('group');
            
            $data['menu_uri1'] = $this->uri->segment('1');
            $data['menu_uri2'] = "data_omzet";
            $data['menu_uri3'] = $this->uri->segment('3');
            $data['tahun'] = $year;
            $data['supp'] = $supplier;
    
            if ($group == '3') {
                
                $data['note'] ='Candy & Herbal';
                $data['note_x'] ='3';
                $data['page_content'] = 'omzet/view_omzet_group';
                $data['query_dp']=$this->model_omzet->list_dp();
                $data['getmenuid'] = $this->model_omzet->getmenuid($data);
                $data['omzets']=$this->model_omzet->omzet_all_dp_group_x($data);
                $this->template($data['page_content'],$data);
            } 
    
            elseif ($group == '4') 
            {            
                $data['note'] ='Herbal (divisi 1)';
                $data['note_x'] ='4';
                $data['page_content'] = 'omzet/view_omzet';
                $data['query_dp']=$this->model_omzet->list_dp();
                $data['getmenuid'] = $this->model_omzet->getmenuid($data);
                $data['omzets']=$this->model_omzet->omzet_all_dp_group_herbal($data);
                $this->template($data['page_content'],$data);
            }
            elseif ($group == '5') 
            {
                $data['note'] ='Candy (divisi 2)';
                $data['note_x'] ='5';
                $data['page_content'] = 'omzet/view_omzet';
                $data['query_dp']=$this->model_omzet->list_dp();
                $data['getmenuid'] = $this->model_omzet->getmenuid($data);
                $data['omzets']=$this->model_omzet->omzet_all_dp_group_candy($data);
                $this->template($data['page_content'],$data);
            } elseif ($group == '6') 
            {    
                $data['note'] ='Freshcare';
                $data['note_x'] ='6';
                $data['page_content'] = 'omzet/view_omzet';
                $data['query_dp']=$this->model_omzet->list_dp();
                $data['getmenuid'] = $this->model_omzet->getmenuid($data);
                $data['omzets']=$this->model_omzet->omzet_all_dp_group_freshcare($data);
                $this->template($data['page_content'],$data);
            } elseif ($group == '7') {
                $data['note'] ='Hotin';
                $data['note_x'] ='7';
                $data['page_content'] = 'omzet/view_omzet';
                $data['query_dp']=$this->model_omzet->list_dp();
                $data['getmenuid'] = $this->model_omzet->getmenuid($data);
                $data['omzets']=$this->model_omzet->omzet_all_dp_group_hotin($data);
                $this->template($data['page_content'],$data);
            } elseif ($group == '8') {
                $data['note'] ='Madu';
                $data['note_x'] ='8';
                $data['page_content'] = 'omzet/view_omzet';
                $data['query_dp']=$this->model_omzet->list_dp();
                $data['getmenuid'] = $this->model_omzet->getmenuid($data);
                $data['omzets']=$this->model_omzet->omzet_all_dp_group_madu($data);
                $this->template($data['page_content'],$data);
            } elseif ($group == '9') {
                $data['note'] ='Mywell';
                $data['note_x'] ='9';
                $data['page_content'] = 'omzet/view_omzet';
                $data['query_dp']=$this->model_omzet->list_dp();
                $data['getmenuid'] = $this->model_omzet->getmenuid($data);
                $data['omzets']=$this->model_omzet->omzet_all_dp_group_mywell($data);
                $this->template($data['page_content'],$data);
            } elseif ($group == '10') { 
                $data['note'] ='Tresnojoyo';
                $data['note_x'] ='10';
                $data['page_content'] = 'omzet/view_omzet';
                $data['query_dp']=$this->model_omzet->list_dp();
                $data['getmenuid'] = $this->model_omzet->getmenuid($data);
                $data['omzets']=$this->model_omzet->omzet_all_dp_group_tresnojoyo($data);
                $this->template($data['page_content'],$data);
            } elseif ($group == '11') {
                $data['note'] ='Otherus';
                $data['note_x'] ='11';
                $data['page_content'] = 'omzet/view_omzet';
                $data['query_dp']=$this->model_omzet->list_dp();
                $data['getmenuid'] = $this->model_omzet->getmenuid($data);
                $data['omzets']=$this->model_omzet->omzet_all_dp_group_otherus($data);
                $this->template($data['page_content'],$data);
    
            } elseif ($group == '12') {
                
                $data['note'] ='Herbana (divisi 3)';
                $data['note_x'] ='12';
                $data['page_content'] = 'omzet/view_omzet';
                $data['query_dp']=$this->model_omzet->list_dp();
                $data['getmenuid'] = $this->model_omzet->getmenuid($data);
                $data['omzets']=$this->model_omzet->omzet_all_dp_group_beverages($data);
                $this->template($data['page_content'],$data);
    
            }elseif ($group == '13') {
                
                $data['note'] ='Pilkita';
                $data['note_x'] ='13';
                $data['page_content'] = 'omzet/view_omzet';
                $data['query_dp']=$this->model_omzet->list_dp();
                $data['getmenuid'] = $this->model_omzet->getmenuid($data);
                $data['omzets']=$this->model_omzet->omzet_all_dp_group_pilkita($data);
                $this->template($data['page_content'],$data);
    
            } elseif ($group == '14') {
                
                $data['note'] ='Others_Marguna';
                $data['note_x'] ='14';
                $data['page_content'] = 'omzet/view_omzet';
                $data['query_dp']=$this->model_omzet->list_dp();
                $data['getmenuid'] = $this->model_omzet->getmenuid($data);
                $data['omzets']=$this->model_omzet->omzet_all_dp_group_otherm($data);
                $this->template($data['page_content'],$data);
            }
    
            else {
                $data['note'] ='';
                $data['note_x'] ='';
                $data['page_content'] = 'omzet/view_omzet';
                $data['query_dp']=$this->model_omzet->list_dp();
                $data['getmenuid'] = $this->model_omzet->getmenuid($data);
                $data['omzets']=$this->model_omzet->omzet_all_dp($data);
                $this->template($data['page_content'],$data);
            }           
        }

    public function omzets(){

        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }

        $data['page_content'] = 'sales/view_omzet_kosong';
        $data['omzets']=$this->model_omzet->omzet_all();
        $data['menu']=$this->db->query($this->querymenu);
        $this->template($data['page_content'],$data);
    }

    private function template($view,$data)
    {
        $this->template->set_title('MPM');
        $this->template->add_js('modules/skeleton.js');
        $this->template->add_css('modules/skeleton.css');
        $this->template->load_view($view, $data);
    }

    public function export_omzet() {
        
        $segment2 = $this->uri->segment('2');
        $segment3 = $this->uri->segment('3');
        $supplier = $this->uri->segment('4');
        $group = $this->uri->segment('6');
        $header = $this->uri->segment('5');
        $id_user=$this->session->userdata('id');
        
        // echo "<pre>";
        // print_r($segment2);
        // print_r($segment3);
        // print_r($supplier);
        // print_r($header);
        // print_r($group);
        // echo "</pre>";
        
        if ($group == '') {
            
            /*cek hak DP apa saja yang dapat dilihat*/
            $this->db->where('id = '.'"'.$id_user.'"');
            $query = $this->db->get('mpm.user');
            foreach ($query->result() as $row) {
                $wilayah_nocab = $row->wilayah_nocab;
            }

            if ($wilayah_nocab == NULL || $wilayah_nocab == '')  {


                $query="
                        select naper,sub,kode_comp,namacomp,t1,b1,t2,b2,t3,b3,t4,b4,t5,b5,t6,b6,t7,b7,t8,b8,t9,b9,t10,b10,t11,b11,t12,b12,id,total,rata,kategori_supplier,tgl_created,tahun,urutan,lastupload,if(status_closing = 1,'closing',if(status_closing = 0,'belum','-')) as status_closing
                        from        mpm.omzet_new
                        where       tahun = $segment3 and
                                    kategori_supplier = '$supplier' and
                                    `key` = (
                                        select  max(`key`)
                                        from    mpm.omzet_new
                                        where   tahun = $segment3 and
                                                kategori_supplier = '$supplier'
                        )
                        ORDER BY urutan asc
                    ";
                                
                $hasil = $this->db->query($query);

            }else{

                $query="
                        select naper,sub,kode_comp,namacomp,t1,b1,t2,b2,t3,b3,t4,b4,t5,b5,t6,b6,t7,b7,t8,b8,t9,b9,t10,b10,t11,b11,t12,b12,id,total,rata,kategori_supplier,tgl_created,tahun,urutan,lastupload,if(status_closing = 1,'closing',if(status_closing = 0,'belum','-')) as status_closing
                        from        mpm.omzet_new
                        where       tahun = $segment3 and
                                    kategori_supplier = '$supplier' and
                                            naper in ($wilayah_nocab) and
                                    `key` = (
                                        select  max(`key`)
                                        from    mpm.omzet_new
                                        where   tahun = $segment3 and
                                                kategori_supplier = '$supplier'
                        )
                        ORDER BY urutan asc
                    ";
                                
                $hasil = $this->db->query($query);

            }
           
            $this->excel_generator->set_query($hasil);
            $this->excel_generator->set_header(array
              (
                'Nocab', 'sub','kodeDP', 'DP','t1', 'Jan','t2', 'Feb', 't3','Mar', 't4','Apr','t5','Mei', 't6','Jun', 
                't7','Jul', 't8','Agus', 't9','Sep', 't10','Okt', 't11','Nov', 't12','Des', 'total', 'rata', 'last upload',$header
              ));
            $this->excel_generator->set_column(array
              (
                'naper', 
                'sub', 
                'kode_comp',
                'namacomp',
                't1',
                'b1',
                't2', 
                'b2',
                't3', 
                'b3',
                't4', 
                'b4',
                't5',
                'b5',
                't6', 
                'b6',
                't7',            
                'b7',
                't8', 
                'b8',
                't9', 
                'b9',
                't10', 
                'b10',
                't11', 
                'b11',
                't12', 
                'b12', 
                'total', 
                'rata', 
                'lastupload','status_closing'
              ));


        } elseif($group == '4'){
            /*cek hak DP apa saja yang dapat dilihat*/
            $this->db->where('id = '.'"'.$id_user.'"');
            $query = $this->db->get('mpm.user');
            foreach ($query->result() as $row) {
                $wilayah_nocab = $row->wilayah_nocab;
            }

            if ($wilayah_nocab == NULL || $wilayah_nocab == '')  {

                $query="
                        select naper,sub,kode_comp,namacomp,t1,b1,t2,b2,t3,b3,t4,b4,t5,b5,t6,b6,t7,b7,t8,b8,t9,b9,t10,b10,t11,b11,t12,b12,id,total,rata,kategori_supplier,tgl_created,tahun,urutan,lastupload,if(status_closing = 1,'closing',if(status_closing = 0,'belum','-')) as status_closing
                        from        mpm.omzet_new_herbal
                        where       tahun = $segment3 and
                                    kategori_supplier = '$supplier' and
                                    `key` = (
                                        select  max(`key`)
                                        from    mpm.omzet_new_herbal
                                        where   tahun = $segment3 and
                                                kategori_supplier = '$supplier'
                        )
                        ORDER BY urutan asc
                    ";       
                $hasil = $this->db->query($query);
            }else{

                $query="
                        select naper,sub,kode_comp,namacomp,t1,b1,t2,b2,t3,b3,t4,b4,t5,b5,t6,b6,t7,b7,t8,b8,t9,b9,t10,b10,t11,b11,t12,b12,id,total,rata,kategori_supplier,tgl_created,tahun,urutan,lastupload,if(status_closing = 1,'closing',if(status_closing = 0,'belum','-')) as status_closing
                        from        mpm.omzet_new_herbal
                        where       tahun = $segment3 and
                                    kategori_supplier = '$supplier' and
                                            naper in ($wilayah_nocab) and
                                    `key` = (
                                        select  max(`key`)
                                        from    mpm.omzet_new_herbal
                                        where   tahun = $segment3 and
                                                kategori_supplier = '$supplier'
                        )
                        ORDER BY urutan asc
                    ";
                                
                $hasil = $this->db->query($query);

            }
           
            $this->excel_generator->set_query($hasil);
            $this->excel_generator->set_header(array
              (
                'Nocab', 'sub','kodeDP', 'DP','t1', 'Jan','t2', 'Feb', 't3','Mar', 't4','Apr','t5','Mei', 't6','Jun', 
                't7','Jul', 't8','Agus', 't9','Sep', 't10','Okt', 't11','Nov', 't12','Des', 'total', 'rata', 'last upload',$header
              ));
            $this->excel_generator->set_column(array
              (
                'naper', 
                'sub', 
                'kode_comp',
                'namacomp',
                't1',
                'b1',
                't2', 
                'b2',
                't3', 
                'b3',
                't4', 
                'b4',
                't5',
                'b5',
                't6', 
                'b6',
                't7',            
                'b7',
                't8', 
                'b8',
                't9', 
                'b9',
                't10', 
                'b10',
                't11', 
                'b11',
                't12', 
                'b12', 
                'total', 
                'rata', 
                'lastupload','status_closing'
              ));
        }
      
            elseif($group == '5'){


            /*cek hak DP apa saja yang dapat dilihat*/
            $this->db->where('id = '.'"'.$id_user.'"');
            $query = $this->db->get('mpm.user');
            foreach ($query->result() as $row) {
                $wilayah_nocab = $row->wilayah_nocab;
            }

            if ($wilayah_nocab == NULL || $wilayah_nocab == '')  {


                $query="
                        select naper,sub,kode_comp,namacomp,t1,b1,t2,b2,t3,b3,t4,b4,t5,b5,t6,b6,t7,b7,t8,b8,t9,b9,t10,b10,t11,b11,t12,b12,id,total,rata,kategori_supplier,tgl_created,tahun,urutan,lastupload,if(status_closing = 1,'closing',if(status_closing = 0,'belum','-')) as status_closing
                        from        mpm.omzet_new_candy
                        where       tahun = $segment3 and
                                    kategori_supplier = '$supplier' and
                                    `key` = (
                                        select  max(`key`)
                                        from    mpm.omzet_new_candy
                                        where   tahun = $segment3 and
                                                kategori_supplier = '$supplier'
                        )
                        ORDER BY urutan asc
                    ";       
                $hasil = $this->db->query($query);
            }else{

                $query="
                        select naper,sub,kode_comp,namacomp,t1,b1,t2,b2,t3,b3,t4,b4,t5,b5,t6,b6,t7,b7,t8,b8,t9,b9,t10,b10,t11,b11,t12,b12,id,total,rata,kategori_supplier,tgl_created,tahun,urutan,lastupload,if(status_closing = 1,'closing',if(status_closing = 0,'belum','-')) as status_closing
                        from        mpm.omzet_new_candy
                        where       tahun = $segment3 and
                                    kategori_supplier = '$supplier' and
                                            naper in ($wilayah_nocab) and
                                    `key` = (
                                        select  max(`key`)
                                        from    mpm.omzet_new_candy
                                        where   tahun = $segment3 and
                                                kategori_supplier = '$supplier'
                        )
                        ORDER BY urutan asc
                    ";
                                
                $hasil = $this->db->query($query);

            }
           
            $this->excel_generator->set_query($hasil);
            $this->excel_generator->set_header(array
              (
                'Nocab', 'sub','kodeDP', 'DP','t1', 'Jan','t2', 'Feb', 't3','Mar', 't4','Apr','t5','Mei', 't6','Jun', 
                't7','Jul', 't8','Agus', 't9','Sep', 't10','Okt', 't11','Nov', 't12','Des', 'total', 'rata', 'last upload',$header
              ));
            $this->excel_generator->set_column(array
              (
                'naper', 
                'sub', 
                'kode_comp',
                'namacomp',
                't1',
                'b1',
                't2', 
                'b2',
                't3', 
                'b3',
                't4', 
                'b4',
                't5',
                'b5',
                't6', 
                'b6',
                't7',            
                'b7',
                't8', 
                'b8',
                't9', 
                'b9',
                't10', 
                'b10',
                't11', 
                'b11',
                't12', 
                'b12', 
                'total', 
                'rata', 
                'lastupload','status_closing'
              ));


        }elseif($group == '12'){

            /*cek hak DP apa saja yang dapat dilihat*/
            $this->db->where('id = '.'"'.$id_user.'"');
            $query = $this->db->get('mpm.user');
            foreach ($query->result() as $row) {
                $wilayah_nocab = $row->wilayah_nocab;
            }

            if ($wilayah_nocab == NULL || $wilayah_nocab == '')  {


                $query="
                        select naper,sub,kode_comp,namacomp,t1,b1,t2,b2,t3,b3,t4,b4,t5,b5,t6,b6,t7,b7,t8,b8,t9,b9,t10,b10,t11,b11,t12,b12,id,total,rata,kategori_supplier,tgl_created,tahun,urutan,lastupload,if(status_closing = 1,'closing',if(status_closing = 0,'belum','-')) as status_closing
                        from        mpm.omzet_new_beverages
                        where       tahun = $segment3 and
                                    kategori_supplier = '$supplier' and
                                    `key` = (
                                        select  max(`key`)
                                        from    mpm.omzet_new_beverages
                                        where   tahun = $segment3 and
                                                kategori_supplier = '$supplier'
                        )
                        ORDER BY urutan asc
                    ";       
                $hasil = $this->db->query($query);
            }else{

                $query="
                        select naper,sub,kode_comp,namacomp,t1,b1,t2,b2,t3,b3,t4,b4,t5,b5,t6,b6,t7,b7,t8,b8,t9,b9,t10,b10,t11,b11,t12,b12,id,total,rata,kategori_supplier,tgl_created,tahun,urutan,lastupload,if(status_closing = 1,'closing',if(status_closing = 0,'belum','-')) as status_closing
                        from        mpm.omzet_new_beverages
                        where       tahun = $segment3 and
                                    kategori_supplier = '$supplier' and
                                            naper in ($wilayah_nocab) and
                                    `key` = (
                                        select  max(`key`)
                                        from    mpm.omzet_new_beverages
                                        where   tahun = $segment3 and
                                                kategori_supplier = '$supplier'
                        )
                        ORDER BY urutan asc
                    ";
                                
                $hasil = $this->db->query($query);

            }
           
            $this->excel_generator->set_query($hasil);
            $this->excel_generator->set_header(array
              (
                'Nocab', 'sub','kodeDP', 'DP','t1', 'Jan','t2', 'Feb', 't3','Mar', 't4','Apr','t5','Mei', 't6','Jun', 
                't7','Jul', 't8','Agus', 't9','Sep', 't10','Okt', 't11','Nov', 't12','Des', 'total', 'rata', 'last upload',$header
              ));
            $this->excel_generator->set_column(array
              (
                'naper', 
                'sub', 
                'kode_comp',
                'namacomp',
                't1',
                'b1',
                't2', 
                'b2',
                't3', 
                'b3',
                't4', 
                'b4',
                't5',
                'b5',
                't6', 
                'b6',
                't7',            
                'b7',
                't8', 
                'b8',
                't9', 
                'b9',
                't10', 
                'b10',
                't11', 
                'b11',
                't12', 
                'b12', 
                'total', 
                'rata', 
                'lastupload','status_closing'
              ));
        }elseif($group == '6'){

            /*cek hak DP apa saja yang dapat dilihat*/
            $this->db->where('id = '.'"'.$id_user.'"');
            $query = $this->db->get('mpm.user');
            foreach ($query->result() as $row) {
                $wilayah_nocab = $row->wilayah_nocab;
            }

            if ($wilayah_nocab == NULL || $wilayah_nocab == '')  {


                $query="
                        select naper,sub,kode_comp,namacomp,t1,b1,t2,b2,t3,b3,t4,b4,t5,b5,t6,b6,t7,b7,t8,b8,t9,b9,t10,b10,t11,b11,t12,b12,id,total,rata,kategori_supplier,tgl_created,tahun,urutan,lastupload,if(status_closing = 1,'closing',if(status_closing = 0,'belum','-')) as status_closing
                        from        mpm.omzet_new_freshcare
                        where       tahun = $segment3 and
                                    kategori_supplier = '$supplier' and
                                    `key` = (
                                        select  max(`key`)
                                        from    mpm.omzet_new_freshcare
                                        where   tahun = $segment3 and
                                                kategori_supplier = '$supplier'
                        )
                        ORDER BY urutan asc
                    ";       
                $hasil = $this->db->query($query);
            }else{

                $query="
                        select naper,sub,kode_comp,namacomp,t1,b1,t2,b2,t3,b3,t4,b4,t5,b5,t6,b6,t7,b7,t8,b8,t9,b9,t10,b10,t11,b11,t12,b12,id,total,rata,kategori_supplier,tgl_created,tahun,urutan,lastupload,if(status_closing = 1,'closing',if(status_closing = 0,'belum','-')) as status_closing
                        from        mpm.omzet_new_freshcare
                        where       tahun = $segment3 and
                                    kategori_supplier = '$supplier' and
                                            naper in ($wilayah_nocab) and
                                    `key` = (
                                        select  max(`key`)
                                        from    mpm.omzet_new_freshcare
                                        where   tahun = $segment3 and
                                                kategori_supplier = '$supplier'
                        )
                        ORDER BY urutan asc
                    ";
                                
                $hasil = $this->db->query($query);

            }
           
            $this->excel_generator->set_query($hasil);
            $this->excel_generator->set_header(array
              (
                'Nocab', 'sub','kodeDP', 'DP','t1', 'Jan','t2', 'Feb', 't3','Mar', 't4','Apr','t5','Mei', 't6','Jun', 
                't7','Jul', 't8','Agus', 't9','Sep', 't10','Okt', 't11','Nov', 't12','Des', 'total', 'rata', 'last upload',$header
              ));
            $this->excel_generator->set_column(array
              (
                'naper', 
                'sub', 
                'kode_comp',
                'namacomp',
                't1',
                'b1',
                't2', 
                'b2',
                't3', 
                'b3',
                't4', 
                'b4',
                't5',
                'b5',
                't6', 
                'b6',
                't7',            
                'b7',
                't8', 
                'b8',
                't9', 
                'b9',
                't10', 
                'b10',
                't11', 
                'b11',
                't12', 
                'b12', 
                'total', 
                'rata', 
                'lastupload','status_closing'
              ));
        }elseif($group == '7'){

            /*cek hak DP apa saja yang dapat dilihat*/
            $this->db->where('id = '.'"'.$id_user.'"');
            $query = $this->db->get('mpm.user');
            foreach ($query->result() as $row) {
                $wilayah_nocab = $row->wilayah_nocab;
            }

            if ($wilayah_nocab == NULL || $wilayah_nocab == '')  {


                $query="
                        select naper,sub,kode_comp,namacomp,t1,b1,t2,b2,t3,b3,t4,b4,t5,b5,t6,b6,t7,b7,t8,b8,t9,b9,t10,b10,t11,b11,t12,b12,id,total,rata,kategori_supplier,tgl_created,tahun,urutan,lastupload,if(status_closing = 1,'closing',if(status_closing = 0,'belum','-')) as status_closing
                        from        mpm.omzet_new_hotin
                        where       tahun = $segment3 and
                                    kategori_supplier = '$supplier' and
                                    `key` = (
                                        select  max(`key`)
                                        from    mpm.omzet_new_hotin
                                        where   tahun = $segment3 and
                                                kategori_supplier = '$supplier'
                        )
                        ORDER BY urutan asc
                    ";       
                $hasil = $this->db->query($query);
            }else{

                $query="
                        select naper,sub,kode_comp,namacomp,t1,b1,t2,b2,t3,b3,t4,b4,t5,b5,t6,b6,t7,b7,t8,b8,t9,b9,t10,b10,t11,b11,t12,b12,id,total,rata,kategori_supplier,tgl_created,tahun,urutan,lastupload,if(status_closing = 1,'closing',if(status_closing = 0,'belum','-')) as status_closing
                        from        mpm.omzet_new_hotin
                        where       tahun = $segment3 and
                                    kategori_supplier = '$supplier' and
                                            naper in ($wilayah_nocab) and
                                    `key` = (
                                        select  max(`key`)
                                        from    mpm.omzet_new_hotin
                                        where   tahun = $segment3 and
                                                kategori_supplier = '$supplier'
                        )
                        ORDER BY urutan asc
                    ";
                                
                $hasil = $this->db->query($query);

            }
           
            $this->excel_generator->set_query($hasil);
            $this->excel_generator->set_header(array
              (
                'Nocab', 'sub','kodeDP', 'DP','t1', 'Jan','t2', 'Feb', 't3','Mar', 't4','Apr','t5','Mei', 't6','Jun', 
                't7','Jul', 't8','Agus', 't9','Sep', 't10','Okt', 't11','Nov', 't12','Des', 'total', 'rata', 'last upload',$header
              ));
            $this->excel_generator->set_column(array
              (
                'naper', 
                'sub', 
                'kode_comp',
                'namacomp',
                't1',
                'b1',
                't2', 
                'b2',
                't3', 
                'b3',
                't4', 
                'b4',
                't5',
                'b5',
                't6', 
                'b6',
                't7',            
                'b7',
                't8', 
                'b8',
                't9', 
                'b9',
                't10', 
                'b10',
                't11', 
                'b11',
                't12', 
                'b12', 
                'total', 
                'rata', 
                'lastupload','status_closing'
              ));
        }elseif($group == '8'){

            /*cek hak DP apa saja yang dapat dilihat*/
            $this->db->where('id = '.'"'.$id_user.'"');
            $query = $this->db->get('mpm.user');
            foreach ($query->result() as $row) {
                $wilayah_nocab = $row->wilayah_nocab;
            }

            if ($wilayah_nocab == NULL || $wilayah_nocab == '')  {


                $query="
                        select naper,sub,kode_comp,namacomp,t1,b1,t2,b2,t3,b3,t4,b4,t5,b5,t6,b6,t7,b7,t8,b8,t9,b9,t10,b10,t11,b11,t12,b12,id,total,rata,kategori_supplier,tgl_created,tahun,urutan,lastupload,if(status_closing = 1,'closing',if(status_closing = 0,'belum','-')) as status_closing
                        from        mpm.omzet_new_madu
                        where       tahun = $segment3 and
                                    kategori_supplier = '$supplier' and
                                    `key` = (
                                        select  max(`key`)
                                        from    mpm.omzet_new_madu
                                        where   tahun = $segment3 and
                                                kategori_supplier = '$supplier'
                        )
                        ORDER BY urutan asc
                    ";       
                $hasil = $this->db->query($query);
            }else{

                $query="
                        select naper,sub,kode_comp,namacomp,t1,b1,t2,b2,t3,b3,t4,b4,t5,b5,t6,b6,t7,b7,t8,b8,t9,b9,t10,b10,t11,b11,t12,b12,id,total,rata,kategori_supplier,tgl_created,tahun,urutan,lastupload,if(status_closing = 1,'closing',if(status_closing = 0,'belum','-')) as status_closing
                        from        mpm.omzet_new_madu
                        where       tahun = $segment3 and
                                    kategori_supplier = '$supplier' and
                                            naper in ($wilayah_nocab) and
                                    `key` = (
                                        select  max(`key`)
                                        from    mpm.omzet_new_madu
                                        where   tahun = $segment3 and
                                                kategori_supplier = '$supplier'
                        )
                        ORDER BY urutan asc
                    ";
                                
                $hasil = $this->db->query($query);

            }
           
            $this->excel_generator->set_query($hasil);
            $this->excel_generator->set_header(array
              (
                'Nocab', 'sub','kodeDP', 'DP','t1', 'Jan','t2', 'Feb', 't3','Mar', 't4','Apr','t5','Mei', 't6','Jun', 
                't7','Jul', 't8','Agus', 't9','Sep', 't10','Okt', 't11','Nov', 't12','Des', 'total', 'rata', 'last upload',$header
              ));
            $this->excel_generator->set_column(array
              (
                'naper', 
                'sub', 
                'kode_comp',
                'namacomp',
                't1',
                'b1',
                't2', 
                'b2',
                't3', 
                'b3',
                't4', 
                'b4',
                't5',
                'b5',
                't6', 
                'b6',
                't7',            
                'b7',
                't8', 
                'b8',
                't9', 
                'b9',
                't10', 
                'b10',
                't11', 
                'b11',
                't12', 
                'b12', 
                'total', 
                'rata', 
                'lastupload','status_closing'
              ));
        }elseif($group == '9'){

            /*cek hak DP apa saja yang dapat dilihat*/
            $this->db->where('id = '.'"'.$id_user.'"');
            $query = $this->db->get('mpm.user');
            foreach ($query->result() as $row) {
                $wilayah_nocab = $row->wilayah_nocab;
            }

            if ($wilayah_nocab == NULL || $wilayah_nocab == '')  {


                $query="
                        select naper,sub,kode_comp,namacomp,t1,b1,t2,b2,t3,b3,t4,b4,t5,b5,t6,b6,t7,b7,t8,b8,t9,b9,t10,b10,t11,b11,t12,b12,id,total,rata,kategori_supplier,tgl_created,tahun,urutan,lastupload,if(status_closing = 1,'closing',if(status_closing = 0,'belum','-')) as status_closing
                        from        mpm.omzet_new_mywell
                        where       tahun = $segment3 and
                                    kategori_supplier = '$supplier' and
                                    `key` = (
                                        select  max(`key`)
                                        from    mpm.omzet_new_mywell
                                        where   tahun = $segment3 and
                                                kategori_supplier = '$supplier'
                        )
                        ORDER BY urutan asc
                    ";       
                $hasil = $this->db->query($query);
            }else{

                $query="
                        select naper,sub,kode_comp,namacomp,t1,b1,t2,b2,t3,b3,t4,b4,t5,b5,t6,b6,t7,b7,t8,b8,t9,b9,t10,b10,t11,b11,t12,b12,id,total,rata,kategori_supplier,tgl_created,tahun,urutan,lastupload,if(status_closing = 1,'closing',if(status_closing = 0,'belum','-')) as status_closing
                        from        mpm.omzet_new_mywell
                        where       tahun = $segment3 and
                                    kategori_supplier = '$supplier' and
                                            naper in ($wilayah_nocab) and
                                    `key` = (
                                        select  max(`key`)
                                        from    mpm.omzet_new_mywell
                                        where   tahun = $segment3 and
                                                kategori_supplier = '$supplier'
                        )
                        ORDER BY urutan asc
                    ";
                                
                $hasil = $this->db->query($query);

            }
           
            $this->excel_generator->set_query($hasil);
            $this->excel_generator->set_header(array
              (
                'Nocab', 'sub','kodeDP', 'DP','t1', 'Jan','t2', 'Feb', 't3','Mar', 't4','Apr','t5','Mei', 't6','Jun', 
                't7','Jul', 't8','Agus', 't9','Sep', 't10','Okt', 't11','Nov', 't12','Des', 'total', 'rata', 'last upload',$header
              ));
            $this->excel_generator->set_column(array
              (
                'naper', 
                'sub', 
                'kode_comp',
                'namacomp',
                't1',
                'b1',
                't2', 
                'b2',
                't3', 
                'b3',
                't4', 
                'b4',
                't5',
                'b5',
                't6', 
                'b6',
                't7',            
                'b7',
                't8', 
                'b8',
                't9', 
                'b9',
                't10', 
                'b10',
                't11', 
                'b11',
                't12', 
                'b12', 
                'total', 
                'rata', 
                'lastupload','status_closing'
              ));
        }elseif($group == '10'){

            /*cek hak DP apa saja yang dapat dilihat*/
            $this->db->where('id = '.'"'.$id_user.'"');
            $query = $this->db->get('mpm.user');
            foreach ($query->result() as $row) {
                $wilayah_nocab = $row->wilayah_nocab;
            }

            if ($wilayah_nocab == NULL || $wilayah_nocab == '')  {


                $query="
                        select naper,sub,kode_comp,namacomp,t1,b1,t2,b2,t3,b3,t4,b4,t5,b5,t6,b6,t7,b7,t8,b8,t9,b9,t10,b10,t11,b11,t12,b12,id,total,rata,kategori_supplier,tgl_created,tahun,urutan,lastupload,if(status_closing = 1,'closing',if(status_closing = 0,'belum','-')) as status_closing
                        from        mpm.omzet_new_tresnojoyo
                        where       tahun = $segment3 and
                                    kategori_supplier = '$supplier' and
                                    `key` = (
                                        select  max(`key`)
                                        from    mpm.omzet_new_tresnojoyo
                                        where   tahun = $segment3 and
                                                kategori_supplier = '$supplier'
                        )
                        ORDER BY urutan asc
                    ";       
                $hasil = $this->db->query($query);
            }else{

                $query="
                        select naper,sub,kode_comp,namacomp,t1,b1,t2,b2,t3,b3,t4,b4,t5,b5,t6,b6,t7,b7,t8,b8,t9,b9,t10,b10,t11,b11,t12,b12,id,total,rata,kategori_supplier,tgl_created,tahun,urutan,lastupload,if(status_closing = 1,'closing',if(status_closing = 0,'belum','-')) as status_closing
                        from        mpm.omzet_new_tresnojoyo
                        where       tahun = $segment3 and
                                    kategori_supplier = '$supplier' and
                                            naper in ($wilayah_nocab) and
                                    `key` = (
                                        select  max(`key`)
                                        from    mpm.omzet_new_tresnojoyo
                                        where   tahun = $segment3 and
                                                kategori_supplier = '$supplier'
                        )
                        ORDER BY urutan asc
                    ";
                                
                $hasil = $this->db->query($query);

            }
           
            $this->excel_generator->set_query($hasil);
            $this->excel_generator->set_header(array
              (
                'Nocab', 'sub','kodeDP', 'DP','t1', 'Jan','t2', 'Feb', 't3','Mar', 't4','Apr','t5','Mei', 't6','Jun', 
                't7','Jul', 't8','Agus', 't9','Sep', 't10','Okt', 't11','Nov', 't12','Des', 'total', 'rata', 'last upload',$header
              ));
            $this->excel_generator->set_column(array
              (
                'naper', 
                'sub', 
                'kode_comp',
                'namacomp',
                't1',
                'b1',
                't2', 
                'b2',
                't3', 
                'b3',
                't4', 
                'b4',
                't5',
                'b5',
                't6', 
                'b6',
                't7',            
                'b7',
                't8', 
                'b8',
                't9', 
                'b9',
                't10', 
                'b10',
                't11', 
                'b11',
                't12', 
                'b12', 
                'total', 
                'rata', 
                'lastupload','status_closing'
              ));
        }elseif($group == '11'){

            /*cek hak DP apa saja yang dapat dilihat*/
            $this->db->where('id = '.'"'.$id_user.'"');
            $query = $this->db->get('mpm.user');
            foreach ($query->result() as $row) {
                $wilayah_nocab = $row->wilayah_nocab;
            }

            if ($wilayah_nocab == NULL || $wilayah_nocab == '')  {


                $query="
                        select naper,sub,kode_comp,namacomp,t1,b1,t2,b2,t3,b3,t4,b4,t5,b5,t6,b6,t7,b7,t8,b8,t9,b9,t10,b10,t11,b11,t12,b12,id,total,rata,kategori_supplier,tgl_created,tahun,urutan,lastupload,if(status_closing = 1,'closing',if(status_closing = 0,'belum','-')) as status_closing
                        from        mpm.omzet_new_otherus
                        where       tahun = $segment3 and
                                    kategori_supplier = '$supplier' and
                                    `key` = (
                                        select  max(`key`)
                                        from    mpm.omzet_new_otherus
                                        where   tahun = $segment3 and
                                                kategori_supplier = '$supplier'
                        )
                        ORDER BY urutan asc
                    ";       
                $hasil = $this->db->query($query);
            }else{

                $query="
                        select naper,sub,kode_comp,namacomp,t1,b1,t2,b2,t3,b3,t4,b4,t5,b5,t6,b6,t7,b7,t8,b8,t9,b9,t10,b10,t11,b11,t12,b12,id,total,rata,kategori_supplier,tgl_created,tahun,urutan,lastupload,if(status_closing = 1,'closing',if(status_closing = 0,'belum','-')) as status_closing
                        from        mpm.omzet_new_otherus
                        where       tahun = $segment3 and
                                    kategori_supplier = '$supplier' and
                                            naper in ($wilayah_nocab) and
                                    `key` = (
                                        select  max(`key`)
                                        from    mpm.omzet_new_otherus
                                        where   tahun = $segment3 and
                                                kategori_supplier = '$supplier'
                        )
                        ORDER BY urutan asc
                    ";
                                
                $hasil = $this->db->query($query);

            }
           
            $this->excel_generator->set_query($hasil);
            $this->excel_generator->set_header(array
              (
                'Nocab', 'sub','kodeDP', 'DP','t1', 'Jan','t2', 'Feb', 't3','Mar', 't4','Apr','t5','Mei', 't6','Jun', 
                't7','Jul', 't8','Agus', 't9','Sep', 't10','Okt', 't11','Nov', 't12','Des', 'total', 'rata', 'last upload',$header
              ));
            $this->excel_generator->set_column(array
              (
                'naper', 
                'sub', 
                'kode_comp',
                'namacomp',
                't1',
                'b1',
                't2', 
                'b2',
                't3', 
                'b3',
                't4', 
                'b4',
                't5',
                'b5',
                't6', 
                'b6',
                't7',            
                'b7',
                't8', 
                'b8',
                't9', 
                'b9',
                't10', 
                'b10',
                't11', 
                'b11',
                't12', 
                'b12', 
                'total', 
                'rata', 
                'lastupload','status_closing'
              ));
        }elseif($group == '13'){

            /*cek hak DP apa saja yang dapat dilihat*/
            $this->db->where('id = '.'"'.$id_user.'"');
            $query = $this->db->get('mpm.user');
            foreach ($query->result() as $row) {
                $wilayah_nocab = $row->wilayah_nocab;
            }

            if ($wilayah_nocab == NULL || $wilayah_nocab == '')  {


                $query="
                        select naper,sub,kode_comp,namacomp,t1,b1,t2,b2,t3,b3,t4,b4,t5,b5,t6,b6,t7,b7,t8,b8,t9,b9,t10,b10,t11,b11,t12,b12,id,total,rata,kategori_supplier,tgl_created,tahun,urutan,lastupload,if(status_closing = 1,'closing',if(status_closing = 0,'belum','-')) as status_closing
                        from        mpm.omzet_new_pilkita
                        where       tahun = $segment3 and
                                    kategori_supplier = '$supplier' and
                                    `key` = (
                                        select  max(`key`)
                                        from    mpm.omzet_new_pilkita
                                        where   tahun = $segment3 and
                                                kategori_supplier = '$supplier'
                        )
                        ORDER BY urutan asc
                    ";       
                $hasil = $this->db->query($query);
            }else{

                $query="
                        select naper,sub,kode_comp,namacomp,t1,b1,t2,b2,t3,b3,t4,b4,t5,b5,t6,b6,t7,b7,t8,b8,t9,b9,t10,b10,t11,b11,t12,b12,id,total,rata,kategori_supplier,tgl_created,tahun,urutan,lastupload,if(status_closing = 1,'closing',if(status_closing = 0,'belum','-')) as status_closing
                        from        mpm.omzet_new_pilkita
                        where       tahun = $segment3 and
                                    kategori_supplier = '$supplier' and
                                            naper in ($wilayah_nocab) and
                                    `key` = (
                                        select  max(`key`)
                                        from    mpm.omzet_new_pilkita
                                        where   tahun = $segment3 and
                                                kategori_supplier = '$supplier'
                        )
                        ORDER BY urutan asc
                    ";
                                
                $hasil = $this->db->query($query);

            }
           
            $this->excel_generator->set_query($hasil);
            $this->excel_generator->set_header(array
              (
                'Nocab', 'sub','kodeDP', 'DP','t1', 'Jan','t2', 'Feb', 't3','Mar', 't4','Apr','t5','Mei', 't6','Jun', 
                't7','Jul', 't8','Agus', 't9','Sep', 't10','Okt', 't11','Nov', 't12','Des', 'total', 'rata', 'last upload',$header
              ));
            $this->excel_generator->set_column(array
              (
                'naper', 
                'sub', 
                'kode_comp',
                'namacomp',
                't1',
                'b1',
                't2', 
                'b2',
                't3', 
                'b3',
                't4', 
                'b4',
                't5',
                'b5',
                't6', 
                'b6',
                't7',            
                'b7',
                't8', 
                'b8',
                't9', 
                'b9',
                't10', 
                'b10',
                't11', 
                'b11',
                't12', 
                'b12', 
                'total', 
                'rata', 
                'lastupload','status_closing'
              ));
        }elseif($group == 'Others_Marguna'){

            /*cek hak DP apa saja yang dapat dilihat*/
            $this->db->where('id = '.'"'.$id_user.'"');
            $query = $this->db->get('mpm.user');
            foreach ($query->result() as $row) {
                $wilayah_nocab = $row->wilayah_nocab;
            }

            if ($wilayah_nocab == NULL || $wilayah_nocab == '')  {


                $query="
                        select naper,sub,kode_comp,namacomp,t1,b1,t2,b2,t3,b3,t4,b4,t5,b5,t6,b6,t7,b7,t8,b8,t9,b9,t10,b10,t11,b11,t12,b12,id,total,rata,kategori_supplier,tgl_created,tahun,urutan,lastupload,if(status_closing = 1,'closing',if(status_closing = 0,'belum','-')) as status_closing
                        from        mpm.omzet_new_otherm
                        where       tahun = $segment3 and
                                    kategori_supplier = '$supplier' and
                                    `key` = (
                                        select  max(`key`)
                                        from    mpm.omzet_new_otherm
                                        where   tahun = $segment3 and
                                                kategori_supplier = '$supplier'
                        )
                        ORDER BY urutan asc
                    ";       
                $hasil = $this->db->query($query);
            }else{

                $query="
                        select naper,sub,kode_comp,namacomp,t1,b1,t2,b2,t3,b3,t4,b4,t5,b5,t6,b6,t7,b7,t8,b8,t9,b9,t10,b10,t11,b11,t12,b12,id,total,rata,kategori_supplier,tgl_created,tahun,urutan,lastupload,if(status_closing = 1,'closing',if(status_closing = 0,'belum','-')) as status_closing
                        from        mpm.omzet_new_otherm
                        where       tahun = $segment3 and
                                    kategori_supplier = '$supplier' and
                                            naper in ($wilayah_nocab) and
                                    `key` = (
                                        select  max(`key`)
                                        from    mpm.omzet_new_otherm
                                        where   tahun = $segment3 and
                                                kategori_supplier = '$supplier'
                        )
                        ORDER BY urutan asc
                    ";
                                
                $hasil = $this->db->query($query);

            }
           
            $this->excel_generator->set_query($hasil);
            $this->excel_generator->set_header(array
              (
                'Nocab', 'sub','kodeDP', 'DP','t1', 'Jan','t2', 'Feb', 't3','Mar', 't4','Apr','t5','Mei', 't6','Jun', 
                't7','Jul', 't8','Agus', 't9','Sep', 't10','Okt', 't11','Nov', 't12','Des', 'total', 'rata', 'last upload',$header
              ));
            $this->excel_generator->set_column(array
              (
                'naper', 
                'sub', 
                'kode_comp',
                'namacomp',
                't1',
                'b1',
                't2', 
                'b2',
                't3', 
                'b3',
                't4', 
                'b4',
                't5',
                'b5',
                't6', 
                'b6',
                't7',            
                'b7',
                't8', 
                'b8',
                't9', 
                'b9',
                't10', 
                'b10',
                't11', 
                'b11',
                't12', 
                'b12', 
                'total', 
                'rata', 
                'lastupload','status_closing'
              ));
        }
        
        $this->excel_generator->set_width(array(8, 5, 5, 20,5, 15,5, 15, 5,15, 5,15, 5,15, 5,15, 5,15, 5,15, 5,15, 5,15, 5,15, 5,15, 15, 15, 15,10));
        $this->excel_generator->exportTo2007('Omzet'.'_'.$segment3);   
    }

    public function export_omzet_timur() {
        
        $segment2 = $this->uri->segment('2');
        $segment3 = $this->uri->segment('3');
        $supplier = $this->uri->segment('4');
        $id_user=$this->session->userdata('id');


        /*cek hak DP apa saja yang dapat dilihat*/
            $this->db->where('id = '.'"'.$id_user.'"');
            $query = $this->db->get('mpm.user');
            foreach ($query->result() as $row) {
                $wilayah_nocab = $row->wilayah_nocab;
                //echo "nocab : ".$wilayah_nocab."<br>";
                //return $wilayah_nocab;
            }

        if ($segment3 == '2010' || $segment3 == '2011' || $segment3 == '2012' || $segment3 == '2013' || $segment3 == '2014' || $segment3 == '2015' || $segment3 == '2016') 
        {
            //$wilayah = "wilayah = 2";
            //echo "wilayah : ".$wilayah."<br>";
            $wilayah = 'wilayah';
        } else {
            //$wilayah = "wilayah_2017 = 2";
            //echo "wilayah : ".$wilayah."<br>";
            $wilayah = 'wilayah_2017';
        }

        if ($wilayah_nocab == NULL || $wilayah_nocab == '')  {

            /*
            $this->db->where("omzet_new.kategori_supplier = ".'"'.$supplier.'"');
            $this->db->where("omzet_new.tahun = ".'"'.$segment3.'"');
            $this->db->order_by('omzet_new.urutan','asc');
            $hasil = $this->db->get('omzet_new');
            */

            $query="
                select * 
                from   mpm.omzet_new a inner join
                (
                    select naper, nocab, kode_comp, nama_comp, sub, urutan, $wilayah
                    from mpm.tbl_tabcomp
                    where $wilayah = '2'
                    GROUP BY naper
                    ORDER BY urutan asc
                )b on a.naper = b.naper
                where   tahun = $segment3 and
                        kategori_supplier = '$supplier' and
                        `key` = (
                            select  max(`key`)
                            from    mpm.omzet_new
                            where   tahun = $segment3 and
                                    kategori_supplier = '$supplier'
                        )
                ORDER BY a.urutan asc
            ";
                            
            $hasil = $this->db->query($query);




        
        }else{

            /*
            $this->db->where("naper in ($wilayah_nocab)");
            $this->db->where("omzet_new.kategori_supplier = ".'"'.$supplier.'"');
            $this->db->where("omzet_new.tahun = ".'"'.$segment3.'"');
            $this->db->order_by('omzet_new.urutan','asc');
            $hasil = $this->db->get('omzet_new');
            */

            $query="
                select * 
                from   mpm.omzet_new a inner join
                (
                    select naper, nocab, kode_comp, nama_comp, sub, urutan, $wilayah
                    from mpm.tbl_tabcomp
                    where $wilayah = '2'
                    GROUP BY naper
                    ORDER BY urutan asc
                )b on a.naper = b.naper
                where   a.naper in ($dp) and tahun = $segment3 and
                        kategori_supplier = '$supplier' and
                        `key` = (
                            select  max(`key`)
                            from    mpm.omzet_new
                            where   tahun = $segment3 and
                                    kategori_supplier = '$supplier'
                        )
                ORDER BY a.urutan asc
            ";
                            
            $hasil = $this->db->query($query);

        }
       
        $this->excel_generator->set_query($hasil);
        $this->excel_generator->set_header(array
          (
            'Nocab', 'sub','kodeDP', 'DP','t1', 'Jan','t2', 'Feb', 't3','Mar', 't4','Apr','t5','Mei', 't6','Jun', 
            't7','Jul', 't8','Agus', 't9','Sep', 't10','Okt', 't11','Nov', 't12','Des', 'total', 'rata', 'last upload',$header
          ));
        $this->excel_generator->set_column(array
          (
            'naper', 
            'sub', 
            'kode_comp',
            'namacomp',
            't1',
            'b1',
            't2', 
            'b2',
            't3', 
            'b3',
            't4', 
            'b4',
            't5',
            'b5',
            't6', 
            'b6',
            't7',            
            'b7',
            't8', 
            'b8',
            't9', 
            'b9',
            't10', 
            'b10',
            't11', 
            'b11',
            't12', 
            'b12', 
            'total', 
            'rata', 
            'lastupload'
          ));
        $this->excel_generator->set_width(array(8, 5, 5, 20,5, 15,5, 15, 5,15, 5,15, 5,15, 5,15, 5,15, 5,15, 5,15, 5,15, 5,15, 5,15, 15, 15, 15));
        $this->excel_generator->exportTo2007('Omzet'.'_'.$segment3);
    }

    public function export_omzet_barat() {
        
        $segment2 = $this->uri->segment('2');
        $segment3 = $this->uri->segment('3');
        $supplier = $this->uri->segment('4');
        $id_user=$this->session->userdata('id');


        /*cek hak DP apa saja yang dapat dilihat*/
            $this->db->where('id = '.'"'.$id_user.'"');
            $query = $this->db->get('mpm.user');
            foreach ($query->result() as $row) {
                $wilayah_nocab = $row->wilayah_nocab;
                //echo "nocab : ".$wilayah_nocab."<br>";
                //return $wilayah_nocab;
            }

        if ($segment3 == '2010' || $segment3 == '2011' || $segment3 == '2012' || $segment3 == '2013' || $segment3 == '2014' || $segment3 == '2015' || $segment3 == '2016') 
        {
            //$wilayah = "wilayah = 2";
            //echo "wilayah : ".$wilayah."<br>";
            $wilayah = 'wilayah';
        } else {
            //$wilayah = "wilayah_2017 = 2";
            //echo "wilayah : ".$wilayah."<br>";
            $wilayah = 'wilayah_2017';
        }

        if ($wilayah_nocab == NULL || $wilayah_nocab == '')  {

            /*
            $this->db->where("omzet_new.kategori_supplier = ".'"'.$supplier.'"');
            $this->db->where("omzet_new.tahun = ".'"'.$segment3.'"');
            $this->db->order_by('omzet_new.urutan','asc');
            $hasil = $this->db->get('omzet_new');
            */

            $query="
                select * 
                from   mpm.omzet_new a inner join
                (
                    select naper, nocab, kode_comp, nama_comp, sub, urutan, $wilayah
                    from mpm.tbl_tabcomp
                    where $wilayah = '1'
                    GROUP BY naper
                    ORDER BY urutan asc
                )b on a.naper = b.naper
                where   tahun = $segment3 and
                        kategori_supplier = '$supplier' and
                        `key` = (
                            select  max(`key`)
                            from    mpm.omzet_new
                            where   tahun = $segment3 and
                                    kategori_supplier = '$supplier'
                        )
                ORDER BY a.urutan asc
            ";
                            
            $hasil = $this->db->query($query);




        
        }else{

            /*
            $this->db->where("naper in ($wilayah_nocab)");
            $this->db->where("omzet_new.kategori_supplier = ".'"'.$supplier.'"');
            $this->db->where("omzet_new.tahun = ".'"'.$segment3.'"');
            $this->db->order_by('omzet_new.urutan','asc');
            $hasil = $this->db->get('omzet_new');
            */

            $query="
                select * 
                from   mpm.omzet_new a inner join
                (
                    select naper, nocab, kode_comp, nama_comp, sub, urutan, $wilayah
                    from mpm.tbl_tabcomp
                    where $wilayah = '1'
                    GROUP BY naper
                    ORDER BY urutan asc
                )b on a.naper = b.naper
                where   a.naper in ($dp) and tahun = $segment3 and
                        kategori_supplier = '$supplier' and
                        `key` = (
                            select  max(`key`)
                            from    mpm.omzet_new
                            where   tahun = $segment3 and
                                    kategori_supplier = '$supplier'
                        )
                ORDER BY a.urutan asc
            ";
                            
            $hasil = $this->db->query($query);

        }
       
        $this->excel_generator->set_query($hasil);
        $this->excel_generator->set_header(array
          (
            'Nocab', 'sub','kodeDP', 'DP','t1', 'Jan','t2', 'Feb', 't3','Mar', 't4','Apr','t5','Mei', 't6','Jun', 
            't7','Jul', 't8','Agus', 't9','Sep', 't10','Okt', 't11','Nov', 't12','Des', 'total', 'rata', 'last upload',$header
          ));
        $this->excel_generator->set_column(array
          (
            'naper', 
            'sub', 
            'kode_comp',
            'namacomp',
            't1',
            'b1',
            't2', 
            'b2',
            't3', 
            'b3',
            't4', 
            'b4',
            't5',
            'b5',
            't6', 
            'b6',
            't7',            
            'b7',
            't8', 
            'b8',
            't9', 
            'b9',
            't10', 
            'b10',
            't11', 
            'b11',
            't12', 
            'b12', 
            'total', 
            'rata', 
            'lastupload'
          ));
        $this->excel_generator->set_width(array(8, 5, 5, 20,5, 15,5, 15, 5,15, 5,15, 5,15, 5,15, 5,15, 5,15, 5,15, 5,15, 5,15, 5,15, 15, 15, 15));
        $this->excel_generator->exportTo2007('Omzet'.'_'.$segment3);  
    }

    public function data_omzet_barat(){

        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
            $data['page_content'] = 'sales/view_omzet_kosong_barat';
            $data['url'] = 'omzet/data_omzet_hasil_barat/';
            $data['page_title'] = 'sales omzet';
            $data['query'] = $this->model_omzet->getSuppbyid();
            $data['menu_uri1'] = $this->uri->segment('1');
            //$data['menu_uri2'] = $this->uri->segment('2');
            $data['menu_uri2'] = "data_omzet";
            $data['menu_uri3'] = $this->uri->segment('3');
            $data['getmenuid'] = $this->model_omzet->getmenuid($data);
            $data['menu']=$this->db->query($this->querymenu);
            $this->template($data['page_content'],$data);
            
    }



    public function data_omzet_hasil_barat(){

        $data['page_content'] = 'sales/view_omzet_barat';
        $data['query'] = $this->model_omzet->getSuppbyid();
        $data['menu']=$this->db->query($this->querymenu);
        $data['year']=$this->input->post('tahun');
        $data['menuid']=$this->input->post('menuid');
        $data['url'] = 'omzet/data_omzet_hasil_barat/';

        $year = $this->input->post('tahun');
        $supplier = $this->input->post('supp');
        
        $data['menu_uri1'] = $this->uri->segment('1');
        $data['menu_uri2'] = "data_omzet";
        $data['menu_uri3'] = $this->uri->segment('3');
        $data['tahun'] = $year;
        $data['supp'] = $supplier;
        $data['query_dp']=$this->model_omzet->list_dp();
        $data['getmenuid'] = $this->model_omzet->getmenuid($data);
        $data['omzets']=$this->model_omzet->omzet_all_dp_barat($data);
        $this->template($data['page_content'],$data);
           
    }

    public function data_omzet_timur(){

        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
            $data['page_content'] = 'sales/view_omzet_kosong_timur';
            $data['url'] = 'omzet/data_omzet_hasil_timur/';
            $data['page_title'] = 'sales omzet';
            $data['query'] = $this->model_omzet->getSuppbyid();
            $data['menu_uri1'] = $this->uri->segment('1');
            //$data['menu_uri2'] = $this->uri->segment('2');
            $data['menu_uri2'] = "data_omzet";
            $data['menu_uri3'] = $this->uri->segment('3');
            $data['getmenuid'] = $this->model_omzet->getmenuid($data);
            $data['menu']=$this->db->query($this->querymenu);
            $this->template($data['page_content'],$data);
            
    }

    public function data_omzet_hasil_timur(){


        $data['page_content'] = 'sales/view_omzet_timur';
        $data['query'] = $this->model_omzet->getSuppbyid();
        $data['menu']=$this->db->query($this->querymenu);
        $data['year']=$this->input->post('tahun');
        $data['menuid']=$this->input->post('menuid');
        $data['url'] = 'omzet/data_omzet_hasil_timur/';

        $year = $this->input->post('tahun');
        $supplier = $this->input->post('supp');
        
        $data['menu_uri1'] = $this->uri->segment('1');
        $data['menu_uri2'] = "data_omzet";
        $data['menu_uri3'] = $this->uri->segment('3');
        $data['tahun'] = $year;
        $data['supp'] = $supplier;
        $data['query_dp']=$this->model_omzet->list_dp();
        $data['getmenuid'] = $this->model_omzet->getmenuid($data);
        $data['omzets']=$this->model_omzet->omzet_all_dp_timur($data);
        $this->template($data['page_content'],$data);
           
    }

    function buildgroup()
    {
        //$this->load->model('basic_model','bmodel');
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
        $kode_supp = $this->input->post('kode_supp',TRUE);
        $output="<option value='0'>--</option>";
        //$query=$this->model_stock->get_namacomp($dp);

        $data['kode_supp'] = $kode_supp;
        $query=$this->model_omzet->get_group($data);
        foreach($query->result() as $row)
        {
            $output .= "<option value='".$row->id_group."'>".$row->nama_group."</option>";
        }
        echo $output;
    }

    public function export_omzet_group() {
        
        $segment2 = $this->uri->segment('2');
        $segment3 = $this->uri->segment('3');
        $supplier = $this->uri->segment('4');
        $segment5 = $this->uri->segment('5');
        $id_user=$this->session->userdata('id');


        /*cek hak DP apa saja yang dapat dilihat*/
            $this->db->where('id = '.'"'.$id_user.'"');
            $query = $this->db->get('mpm.user');
            foreach ($query->result() as $row) {
                $wilayah_nocab = $row->wilayah_nocab;
                //echo "nocab : ".$wilayah_nocab."<br>";
                //return $wilayah_nocab;
            }

        if ($wilayah_nocab == NULL || $wilayah_nocab == '')  {
            /*
            $query="
                    select * 
                    from        mpm.omzet_new_deltomed
                    where       tahun = $segment3 and
                                kategori_supplier = '$supplier' and
                                `key` = (
                                    select  max(`key`)
                                    from    mpm.omzet_new
                                    where   tahun = $segment3 and
                                            kategori_supplier = '$supplier'
                    )
                    ORDER BY urutan asc
                ";
            */

            $query="
                    select * 
                    from        mpm.omzet_new_deltomed
                    where       tahun = $segment3 and
                                kategori_supplier = '$supplier' and
                                `key` = (
                                    select  max(`key`)
                                    from    mpm.omzet_new_deltomed
                                    where   tahun = $segment3 and
                                            kategori_supplier = '$supplier'
                    )
                    ORDER BY urutan asc
                ";

            $hasil = $this->db->query($query);




        
        }else{

            /*
            $this->db->where("naper in ($wilayah_nocab)");
            $this->db->where("omzet_new.kategori_supplier = ".'"'.$supplier.'"');
            $this->db->where("omzet_new.tahun = ".'"'.$segment3.'"');
            $this->db->order_by('omzet_new.urutan','asc');
            $hasil = $this->db->get('omzet_new');
            */

            $query="
                    select * 
                    from        mpm.omzet_new_deltomed
                    where       tahun = $segment3 and
                                kategori_supplier = '$supplier' and
                                        naper in ($wilayah_nocab) and
                                `key` = (
                                    select  max(`key`)
                                    from    mpm.omzet_new_deltomed
                                    where   tahun = $segment3 and
                                            kategori_supplier = '$supplier'
                    )
                    ORDER BY urutan asc
                ";
                            
            $hasil = $this->db->query($query);

        }
       
        $this->excel_generator->set_query($hasil);
        $this->excel_generator->set_header(array
          (
            'Nocab', 'sub','kodeDP', 'DP',
            'b1 candy','t1 candy','b1 herbal','t1 herbal','b1',
            'b2 candy','t2 candy','b2 herbal','t2 herbal','b2',
            'b3 candy','t3 candy','b3 herbal','t3 herbal','b3',
            'b4 candy','t4 candy','b4 herbal','t4 herbal','b4',
            'b5 candy','t5 candy','b5 herbal','t5 herbal','b5',
            'b6 candy','t6 candy','b6 herbal','t6 herbal','b6',
            'b7 candy','t7 candy','b7 herbal','t7 herbal','b7',
            'b8 candy','t8 candy','b8 herbal','t8 herbal','b8',
            'b9 candy','t9 candy','b9 herbal','t9 herbal','b9',
            'b10 candy','t10 candy','b10 herbal','t10 herbal','b10',
            'b11 candy','t11 candy','b11 herbal','t11 herbal','b11',
            'b12 candy','t12 candy','b12 herbal','t12 herbal','b12',
            'total candy','total herbal','total all',
            'rata candy','rata herbal','rata all'
          ));
        $this->excel_generator->set_column(array
          (
            'naper', 
            'sub', 
            'kode_comp',
            'nama_comp',
            'b1_candy','t1_candy','b1_herbal','t1_herbal','b1',
            'b2_candy','t2_candy','b2_herbal','t2_herbal','b2',
            'b3_candy','t3_candy','b3_herbal','t3_herbal','b3',
            'b4_candy','t4_candy','b4_herbal','t4_herbal','b4',
            'b5_candy','t5_candy','b5_herbal','t5_herbal','b5',
            'b6_candy','t6_candy','b6_herbal','t6_herbal','b6',
            'b7_candy','t7_candy','b7_herbal','t7_herbal','b7',
            'b8_candy','t8_candy','b8_herbal','t8_herbal','b8',
            'b9_candy','t9_candy','b9_herbal','t9_herbal','b9',
            'b10_candy','t10_candy','b10_herbal','t10_herbal','b10',
            'b11_candy','t11_candy','b11_herbal','t11_herbal','b11',
            'b12_candy','t12_candy','b12_herbal','t12_herbal','b12',
            'total_candy','total_herbal','total_all',
            'rata_candy','rata_herbal','rata_all',
          ));
        $this->excel_generator->set_width(array(8, 5, 5, 20, 8,8,8,8,8,8, 8,8,8,8,8,8, 8,8,8,8,8,8, 8,8,8,8,8,8, 8,8,8,8,8,8, 8,8,8,8,8,8, 8,8,8,8,8,8, 8,8,8,8,8,8, 8,8,8,8,8,8, 8,8,8,8,8,8, 11,11,11, 11,11,11));
        $this->excel_generator->exportTo2007('Omzet'.'_'.$segment3);   
    }


    public function export_omzet_group_us() {
        
        $segment2 = $this->uri->segment('2');
        $segment3 = $this->uri->segment('3');
        $supplier = $this->uri->segment('4');
        $segment5 = $this->uri->segment('5');
        $id_user=$this->session->userdata('id');

        //echo "<pre>";
        //echo "segment2 : ".$segment2."<br>";
        //echo "segment3 : ".$segment3."<br>";
        //echo "segment5 : ".$segment5."<br>";
        //echo "supplier : ".$supplier."<br>";
        

        /*cek hak DP apa saja yang dapat dilihat*/
            $this->db->where('id = '.'"'.$id_user.'"');
            $query = $this->db->get('mpm.user');
            foreach ($query->result() as $row) {
                $wilayah_nocab = $row->wilayah_nocab;
            }

        if ($wilayah_nocab == NULL || $wilayah_nocab == '')  {

            $query="
                    select * 
                    from        mpm.omzet_new_us
                    where       tahun = $segment3 and
                                kategori_supplier = '$supplier' and
                                `key` = (
                                    select  max(`key`)
                                    from    mpm.omzet_new_us
                                    where   tahun = $segment3 and
                                            kategori_supplier = '$supplier'
                    )
                    ORDER BY urutan asc
                ";

            $hasil = $this->db->query($query);

            //print_r($query);
            //echo "</pre>";

        
        }else{

            $query="
                    select * 
                    from        mpm.omzet_new_us
                    where       tahun = $segment3 and
                                kategori_supplier = '$supplier' and
                                        naper in ($wilayah_nocab) and
                                `key` = (
                                    select  max(`key`)
                                    from    mpm.omzet_new_us
                                    where   tahun = $segment3 and
                                            kategori_supplier = '$supplier'
                    )
                    ORDER BY urutan asc
                ";
                            
            $hasil = $this->db->query($query);

        }
       
        $this->excel_generator->set_query($hasil);
        $this->excel_generator->set_header(array
        (
            'Nocab', 'sub','kodeDP', 'DP',
            'b1','t1',
            'b2','t2',
            'b3','t3',
            'b4','t4',
            'b5','t5',
            'b6','t6',
            'b7','t7',
            'b8','t8',
            'b9','t9',
            'b10','t10',
            'b11','t11',
            'b12','t12',                
            'total',
            'rata'
        ));

        if($segment5 == '6'){            
            $this->excel_generator->set_column(array
            (
                'nocab', 'sub','kode_comp', 'nama_comp',
                'b1_fr','t1_fr',
                'b2_fr','t2_fr',
                'b3_fr','t3_fr',
                'b4_fr','t4_fr',
                'b5_fr','t5_fr',
                'b6_fr','t6_fr',
                'b7_fr','t7_fr',
                'b8_fr','t8_fr',
                'b9_fr','t9_fr',
                'b10_fr','t10_fr',
                'b11_fr','t11_fr',
                'b12_fr','t12_fr',                
                'total_fr',
                'rata_fr'
            ));
        }elseif($segment5 == '7'){
            $this->excel_generator->set_column(array
            (
                'nocab', 'sub','kode_comp', 'nama_comp',
                'b1_ho','t1_ho',
                'b2_ho','t2_ho',
                'b3_ho','t3_ho',
                'b4_ho','t4_ho',
                'b5_ho','t5_ho',
                'b6_ho','t6_ho',
                'b7_ho','t7_ho',
                'b8_ho','t8_ho',
                'b9_ho','t9_ho',
                'b10_ho','t10_ho',
                'b11_ho','t11_ho',
                'b12_ho','t12_ho',                
                'total_ho',
                'rata_ho'
            ));
        }elseif($segment5 == '8'){
            $this->excel_generator->set_column(array
            (
                'nocab', 'sub','kode_comp', 'nama_comp',
                'b1_ma','t1_ma',
                'b2_ma','t2_ma',
                'b3_ma','t3_ma',
                'b4_ma','t4_ma',
                'b5_ma','t5_ma',
                'b6_ma','t6_ma',
                'b7_ma','t7_ma',
                'b8_ma','t8_ma',
                'b9_ma','t9_ma',
                'b10_ma','t10_ma',
                'b11_ma','t11_ma',
                'b12_ma','t12_ma',                
                'total_ma',
                'rata_ma'
            ));
        }elseif($segment5 == '9'){
            $this->excel_generator->set_column(array
            (
                'nocab', 'sub','kode_comp', 'nama_comp',
                'b1_my','t1_my',
                'b2_my','t2_my',
                'b3_my','t3_my',
                'b4_my','t4_my',
                'b5_my','t5_my',
                'b6_my','t6_my',
                'b7_my','t7_my',
                'b8_my','t8_my',
                'b9_my','t9_my',
                'b10_my','t10_my',
                'b11_my','t11_my',
                'b12_my','t12_my',                
                'total_my',
                'rata_my'
            ));
        }elseif($segment5 == '10'){
            $this->excel_generator->set_column(array
            (
                'nocab', 'sub','kode_comp', 'nama_comp',
                'b1_tr','t1_tr',
                'b2_tr','t2_tr',
                'b3_tr','t3_tr',
                'b4_tr','t4_tr',
                'b5_tr','t5_tr',
                'b6_tr','t6_tr',
                'b7_tr','t7_tr',
                'b8_tr','t8_tr',
                'b9_tr','t9_tr',
                'b10_tr','t10_tr',
                'b11_tr','t11_tr',
                'b12_tr','t12_tr',                
                'total_tr',
                'rata_tr'
            ));
        }elseif($segment5 == '11'){
            $this->excel_generator->set_column(array
            (
                'nocab', 'sub','kode_comp', 'nama_comp',
                'b1_ot','t1_ot',
                'b2_ot','t2_ot',
                'b3_ot','t3_ot',
                'b4_ot','t4_ot',
                'b5_ot','t5_ot',
                'b6_ot','t6_ot',
                'b7_ot','t7_ot',
                'b8_ot','t8_ot',
                'b9_ot','t9_ot',
                'b10_ot','t10_ot',
                'b11_ot','t11_ot',
                'b12_ot','t12_ot',                
                'total_ot',
                'rata_ot'
            ));
        }

        $this->excel_generator->set_width(array(8,8,8,8,8, 8,8,8,8,8, 8,8,8,8,8, 8,8,8,8,8, 8,8,8,8,8, 8,8,8,8,8));
        $this->excel_generator->exportTo2007('Omzet'.'_'.$segment3);   
    }

    public function omzet_target()
    {
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
            $data['page_content'] = 'omzet/view_omzet_target_form';
            $data['url'] = 'omzet/omzet_target_proses/';
            $data['page_title'] = 'sales omzet';
            $data['query'] = $this->model_omzet->getSuppbyid_target();
            //$data['getmenuid'] = $this->model_omzet->getmenuid($data);
            $data['menu']=$this->db->query($this->querymenu);
            $this->template($data['page_content'],$data);   
    }

    public function omzet_target_proses()
    {
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }

        $data['query'] = $this->model_omzet->getSuppbyid_target();
        $data['menu']=$this->db->query($this->querymenu);
        $data['year']=$this->input->post('tahun');
        $data['bulan'] = $this->input->post('bulan');
        $data['supplier'] = $this->input->post('supp');
        $data['group']=$this->input->post('group');
        $data['url'] = 'omzet/omzet_target_proses/';
        /*
        echo "<pre>";
        //echo "menu : ".$data['menu']."<br>";
        echo "year : ".$data['year']."<br>";
        echo "bulan : ".$data['bulan']."<br>";
        echo "supplier : ".$data['supplier']."<br>";
        echo "group : ".$data['group']."<br>";
        echo "</pre>";
        */
        $data['page_content'] = 'omzet/view_omzet_target';
        //$data['getmenuid'] = $this->model_omzet->getmenuid($data);
        $data['omzets']=$this->model_omzet->omzet_target($data);
        $this->template($data['page_content'],$data);

        
    }

    function buildgroup_target()
    {
        //$this->load->model('basic_model','bmodel');
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
        $kode_supp = $this->input->post('kode_supp',TRUE);
        $output="<option value='0'>--</option>";
        //$query=$this->model_stock->get_namacomp($dp);

        $data['kode_supp'] = $kode_supp;
        $query=$this->model_omzet->get_group($data);
        foreach($query->result() as $row)
        {
            $output .= "<option value='".$row->kode_group."'>".$row->nama_group."</option>";
        }
        echo $output;
    }

    public function export_omzet_target(){

        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }

        $id_user=$this->session->userdata('id');

        $query="
                select * from db_target.t_sales_omzet
                where userid = '$id_user'
                order by urutan asc
                ";
                            
        $hasil = $this->db->query($query);

        $this->excel_generator->set_query($hasil);
        $this->excel_generator->set_header(array
          (
            'kode_comp', 
            'nama_comp',
            'target',
            'omzet',
            'persen',
            'tgl_data'
          ));

        $this->excel_generator->set_column(array
        (
            'kode_comp', 
            'nama_comp',
            'target',
            'omzet',
            'persen',
            'tgl_data'
        ));
        

        $this->excel_generator->set_width(array(8,8,8,8,8,8));
        $this->excel_generator->exportTo2007('Omzet_target');   


    }

    function get_omzet_target(){

        $id_user=$this->session->userdata('id');
        $draw=$_REQUEST['draw'];
        $length=$_REQUEST['length'];
        $start=$_REQUEST['start'];
        $search=$_REQUEST['search']["value"];
        $this->db->where('userid', $id_user);
        $total=$this->db->count_all_results("db_target.t_sales_omzet");
        $output=array();
        $output['draw']=$draw;
        $output['recordsTotal']=$output['recordsFiltered']=$total;
        $output['data']=array();
        if($search!=""){
        $this->db->like("nama_comp",$search);
        }
        $this->db->limit($length,$start);
        $this->db->order_by('urutan','ASC');
        $this->db->where('userid', $id_user);
        $query=$this->db->get('db_target.t_sales_omzet');
    
        if($search!=""){
        $this->db->like("nama_comp",$search);
        $this->db->where('userid', $id_user);
        $jum=$this->db->get('db_target.t_sales_omzet');
        $output['recordsTotal']=$output['recordsFiltered']=$jum->num_rows();
        }
    
        $nomor_urut=$start+1;
        foreach ($query->result_array() as $kode) {
          $output['data'][]=array(
            $nomor_urut,
            $kode['nama_comp'],
            number_format($kode['target']),
            number_format($kode['omzet']),
            $kode['persen'],
            $kode['tgl_data']
          );
        $nomor_urut++;
        }
    
        echo json_encode($output);
    }

    public function form_omzet_mpi(){
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
        $data['page_content'] = 'mpi/form_omzet_mpi';
        $data['url'] = 'omzet/proses_omzet_mpi/';
        $data['page_title'] = 'Raw Sales MPI ';
        $data['query'] = $this->model_omzet->get_raw_mpi();
        $data['menu']=$this->db->query($this->querymenu);
        $this->template($data['page_content'],$data);

    }

    public function proses_omzet_mpi(){
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
        $id=$this->session->userdata('id');
        $from_a = trim($this->input->post('from'));
        $data['from']=strftime('%Y-%m-%d',strtotime($from_a));
        $to_a = trim($this->input->post('to'));
        $data['to']=strftime('%Y-%m-%d',strtotime($to_a));

        $database = $this->input->post('database');
        // echo "database : ".$database;
        if ($database == 1) {
            $data['omzets'] = $this->model_omzet->omzet_mpi_lokal($data);
        }else{
            $data['omzets'] = $this->model_omzet->omzet_mpi($data);
        }
        
        $data['page_content'] = 'mpi/proses_omzet_mpi';
        $data['url'] = 'omzet/proses_omzet_mpi/';
        $data['page_title'] = 'Raw Sales MPI ';
        $data['menu']=$this->db->query($this->querymenu);
        $this->template($data['page_content'],$data);
    }

    public function export_omzet_mpi(){
        $this->load->helper('csv');
        $id=$this->session->userdata('id');
        $sql = "
        select * from mpi.tbl_mpi
        where id =$id
        ";
        $quer = $this->db->query($sql);

        query_to_csv($quer,TRUE,'Sales MPI.csv');
    }

    public function insert_mpi(){
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
        $data['page_content'] = 'mpi/insert_mpi';
        $data['url'] = 'omzet/proses_insert_mpi/';
        $data['page_title'] = 'Insert MPI ';
        $data['omzets'] = $this->model_omzet->data_mpi($data);
        $data['menu']=$this->db->query($this->querymenu);
        $this->template($data['page_content'],$data);

    }

    public function proses_insert_mpi(){
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
        // $data['tahun'] = $this->input->post('tahun');
        $from_a = trim($this->input->post('from'));
        $data['from']=strftime('%Y-%m-%d',strtotime($from_a));
        $to_a = trim($this->input->post('to'));
        $data['to']=strftime('%Y-%m-%d',strtotime($to_a));

        $data['omzets'] = $this->model_omzet->insert_mpi($data);
        $data['page_content'] = 'mpi/proses_insert_mpi';
        $data['url'] = 'omzet/proses_insert_mpi/';
        $data['page_title'] = 'Insert MPI ';
        $data['menu']=$this->db->query($this->querymenu);
        $this->template($data['page_content'],$data);
    }

    public function insert_mpi_to_db(){
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }

        $proses= $this->model_omzet->insert_mpi_to_db();
    }

    public function omzet_mpi_default(){
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
        $this->load->view('info_mpi');

    }

    public function omzet_mpi(){
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
        $data['page_content'] = 'mpi/omzet_mpi';
        $data['url'] = 'omzet/omzet_mpi_proses/';
        $data['page_title'] = 'Omzet MPI ';
        $data['menu']=$this->db->query($this->querymenu);
        $this->template($data['page_content'],$data);

    }

    public function omzet_mpi_proses(){
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
        $id=$this->session->userdata('id');
        $data['tahun']=$this->input->post('tahun');
        $data['uv']=$this->input->post('uv');
        $data['apotik']=$this->input->post('apotik');
        $data['kimia_farma']=$this->input->post('kimia_farma');
        $data['pbf_kimia_farma']=$this->input->post('pbf_kimia_farma');
        $data['minimarket']=$this->input->post('minimarket');
        $data['pd']=$this->input->post('pd');
        $data['pbf']=$this->input->post('pbf');
        $data['rs']=$this->input->post('rs');
        $data['supermarket']=$this->input->post('supermarket');
        $data['tokoobat']=$this->input->post('tokoobat');
        
        $group_by=$this->input->post('group_by');
        if ($group_by == '1') {
            $data['omzets'] = $this->model_omzet->omzet_mpi_proses($data);
            $data['page_content'] = 'mpi/omzet_mpi_proses';
        }else if ($group_by == '2') {
            $data['omzets'] = $this->model_omzet->omzet_mpi_proses_groupby_kodeprod($data);
            $data['page_content'] = 'mpi/omzet_mpi_proses_groupby_kodeprod';
        }else if ($group_by == '3') {
            $data['omzets'] = $this->model_omzet->omzet_mpi_proses_groupby_kodecab_kodeprod($data);
            $data['page_content'] = 'mpi/omzet_mpi_proses_groupby_kodecab_kodeprod';
        } else {
            
        }
        
        $data['url'] = 'omzet/omzet_mpi_proses/';
        $data['page_title'] = 'Omzet MPI ';
        $data['menu']=$this->db->query($this->querymenu);
        $this->template($data['page_content'],$data);
    }

    public function export_omzet_mpi_groupby_kodeprod_temp(){
        $this->load->helper('csv');
        $id=$this->session->userdata('id');
        $sql = "
        select * from mpi.t_omzet_mpi_groupby_kodeprod_temp
        where id =$id
        ";
        $quer = $this->db->query($sql);

        query_to_csv($quer,TRUE,'Sales Omzet MPI Group By Kode Produk.csv');
    }

    public function export_omzet_mpi_groupby_kodecab_kodeprod_temp(){
        $this->load->helper('csv');
        $id=$this->session->userdata('id');
        $sql = "
        select * from mpi.t_omzet_mpi_groupby_kodecab_kodeprod_temp
        where id =$id
        ";
        $quer = $this->db->query($sql);

        query_to_csv($quer,TRUE,'Sales Omzet MPI Group By Kode Cabang dan Kode Produk.csv');
    }

    public function omzet_cut_off(){
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
        $id= $this->session->userdata('id');

        // if ($id == '297') {
            $data['page_content'] = 'omzet/cut_off';
            $data['query'] = $this->model_omzet->getSuppbyid();
            $data['url'] = 'omzet/omzet_cut_off_proses/';
            $data['page_title'] = 'sales omzet (Cut Off)';
            $data['query'] = $this->model_omzet->getSuppbyid();
            $data['menu_uri1'] = $this->uri->segment('1');
            $data['menu_uri2'] = $this->uri->segment('2');
            $data['menu_uri3'] = $this->uri->segment('3');
            

            $data['getmenuid'] = $this->model_omzet->getmenuid($data);
            $data['menu']=$this->db->query($this->querymenu);
            $this->template($data['page_content'],$data); 
        // }else{
        //     echo "sedang diperbaiki. Mohon kunjungi beberapa saat lagi";
        // }
         

    }

    public function omzet_cut_off_proses(){
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
        $from_a = trim($this->input->post('tanggal'));
        $data['tanggal']=strftime('%Y-%m-%d',strtotime($from_a));
        $data['tahun'] = $this->input->post('tahun');
        $data['bulan'] = $this->input->post('bulan');
        $data['supp'] = $this->input->post('supp');
        $data['group'] = $this->input->post('group');
        $data['query'] = $this->model_omzet->getSuppbyid();
        $data['proses'] = $this->model_omzet->omzet_cut_off($data);
        $data['page_content'] = 'omzet/cut_off_proses';
        $data['url'] = 'omzet/omzet_cut_off_proses/';
        $data['page_title'] = 'sales omzet (Cut Off)';
        $data['menu']=$this->db->query($this->querymenu);
        $this->template($data['page_content'],$data);


    }
    public function export_omzet_mpi_temp(){
        $this->load->helper('csv');
        $id=$this->session->userdata('id');
        $sql = "
        select * from mpi.t_omzet_mpi_temp
        where id =$id
        ";
        $quer = $this->db->query($sql);

        query_to_csv($quer,TRUE,'Sales Omzet MPI.csv');
    }

    public function export_cut_off(){
        $this->load->helper('csv');
        $id=$this->session->userdata('id');
        $sql = "
        select * from mpm.t_cut_off
        where id =$id
        order by urutan
        ";
        $quer = $this->db->query($sql);

        query_to_csv($quer,TRUE,'Sales Omzet Cut Off.csv');
    }

    public function proses_insert_mpi_auto(){
        $data['proses'] = $this->model_omzet->insert_mpi_auto();
    }

    public function proses_insert_stock_mpi_auto(){
        $data['proses'] = $this->model_omzet->insert_stock_mpi_auto();
    }


   
}
?>
