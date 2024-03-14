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

            //echo "<br><br>menu_uri1 : ".$data['menu_uri1']."";
            //echo "<br>menu_uri2 : ".$data['menu_uri2']."";
            $data['getmenuid'] = $this->model_omzet->getmenuid($data);
            //$data['getmenuid'] = 'bbb';
            $data['menu']=$this->db->query($this->querymenu);
            $this->template($data['page_content'],$data);       
        
    }

    public function data_omzet_hasil(){

        $data['page_content'] = 'sales/view_omzet';
        $data['query'] = $this->model_omzet->getSuppbyid();
        $data['menu']=$this->db->query($this->querymenu);
        $data['year']=$this->input->post('tahun');
        $data['menuid']=$this->input->post('menuid');
        $data['url'] = 'omzet/data_omzet_hasil/';

        $year = $this->input->post('tahun');
        $supplier = $this->input->post('supp');
        //$menuid = $this->input->post('menuid');
        
        //echo "controller data_omzet_hasil : ".$menuid;
        
        $data['menu_uri1'] = $this->uri->segment('1');
        //$data['menu_uri2'] = $this->uri->segment('2');
        $data['menu_uri2'] = "data_omzet";
        $data['menu_uri3'] = $this->uri->segment('3');
        $data['tahun'] = $year;
        $data['supp'] = $supplier;

        //echo "<br><br>menu id : ".$this->data_omzet('getmenuid');
        $data['query_dp']=$this->model_omzet->list_dp();
        $data['getmenuid'] = $this->model_omzet->getmenuid($data);
        $data['omzets']=$this->model_omzet->omzet_all_dp($data);
        $this->template($data['page_content'],$data);
           
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

            $this->db->where("omzet_new.kategori_supplier = ".'"'.$supplier.'"');
            $this->db->where("omzet_new.tahun = ".'"'.$segment3.'"');
            $this->db->order_by('omzet_new.urutan','asc');
            $hasil = $this->db->get('omzet_new');
        
        }else{

            $this->db->where("naper in ($wilayah_nocab)");
            $this->db->where("omzet_new.kategori_supplier = ".'"'.$supplier.'"');
            $this->db->where("omzet_new.tahun = ".'"'.$segment3.'"');
            $this->db->order_by('omzet_new.urutan','asc');
            $hasil = $this->db->get('omzet_new');
        
        }
       
        $this->excel_generator->set_query($hasil);
        $this->excel_generator->set_header(array
          (
            'Nocab', 'sub','kodeDP', 'DP','t1', 'Jan','t2', 'Feb', 't3','Mar', 't4','Apr','t5','Mei', 't6','Jun', 
            't7','Jul', 't8','Agus', 't9','Sep', 't10','Okt', 't11','Nov', 't12','Des', 'total', 'rata', 'last upload'
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

            $sql = "
                    select *
                    from mpm.omzet_new a INNER JOIN
                    (
                        select naper, nocab, kode_comp, nama_comp, sub, urutan, $wilayah
                        from mpm.tbl_tabcomp_new
                        where $wilayah = '2'
                        GROUP BY naper
                        ORDER BY urutan asc
                    )b on a.naper = b.naper
                    where a.kategori_supplier = ".'"'.$supplier.'"'." and tahun = $segment3
                    ORDER BY a.urutan asc
                ";

            $hasil = $this->db->query($sql);
        
        }else{
            $sql = "
                    select *
                    from mpm.omzet_new a INNER JOIN
                    (
                        select naper, nocab, kode_comp, nama_comp, sub, urutan, $wilayah
                        from mpm.tbl_tabcomp_new
                        where $wilayah = '2'
                        GROUP BY naper
                        ORDER BY urutan asc
                    )b on a.naper = b.naper
                    where   a.kategori_supplier = ".'"'.$supplier.'"'." and 
                            tahun = $segment3 and naper in ($wilayah_nocab)
                    ORDER BY a.urutan asc
                ";
            $hasil = $this->db->query($sql);
        
        }
       
        $this->excel_generator->set_query($hasil);
        $this->excel_generator->set_header(array
          (
            'Nocab', 'sub','kodeDP', 'DP','t1', 'Jan','t2', 'Feb', 't3','Mar', 't4','Apr','t5','Mei', 't6','Jun', 
            't7','Jul', 't8','Agus', 't9','Sep', 't10','Okt', 't11','Nov', 't12','Des', 'total', 'rata', 'last upload'
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

            $sql = "
                    select *
                    from mpm.omzet_new a INNER JOIN
                    (
                        select naper, nocab, kode_comp, nama_comp, sub, urutan, $wilayah
                        from mpm.tbl_tabcomp_new
                        where $wilayah = '1'
                        GROUP BY naper
                        ORDER BY urutan asc
                    )b on a.naper = b.naper
                    where a.kategori_supplier = ".'"'.$supplier.'"'." and tahun = $segment3
                    ORDER BY a.urutan asc
                ";

            $hasil = $this->db->query($sql);
        
        }else{
            $sql = "
                    select *
                    from mpm.omzet_new a INNER JOIN
                    (
                        select naper, nocab, kode_comp, nama_comp, sub, urutan, $wilayah
                        from mpm.tbl_tabcomp_new
                        where $wilayah = '1'
                        GROUP BY naper
                        ORDER BY urutan asc
                    )b on a.naper = b.naper
                    where   a.kategori_supplier = ".'"'.$supplier.'"'." and 
                            tahun = $segment3 and naper in ($wilayah_nocab)
                    ORDER BY a.urutan asc
                ";
            $hasil = $this->db->query($sql);
        
        }
       
        $this->excel_generator->set_query($hasil);
        $this->excel_generator->set_header(array
          (
            'Nocab', 'sub','kodeDP', 'DP','t1', 'Jan','t2', 'Feb', 't3','Mar', 't4','Apr','t5','Mei', 't6','Jun', 
            't7','Jul', 't8','Agus', 't9','Sep', 't10','Okt', 't11','Nov', 't12','Des', 'total', 'rata', 'last upload'
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

   
}
?>
