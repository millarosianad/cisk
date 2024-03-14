<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class CSalesOmzet extends MY_Controller
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

    function CSalesOmzet()
    {
        set_time_limit(0);
        $this->load->library(array('table','template','Excel_generator'));
        $this->load->helper(array('url','csv'));
        $this->load->model(array('mSalesOmzet','m_dp'));
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
        redirect('cSalesOmzet/formSalesOmzet','refresh');
    }

    public function formSalesOmzet()
    {   
        //$this->load->model('m_dp');
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
        $data['menu_uri1'] = $this->uri->segment('1');
        $data['menu_uri2'] = $this->uri->segment('2');
        $data['menu_uri3'] = $this->uri->segment('3');

        $data['page_content'] = 'omzet/formSalesOmzet';
        $data['url'] = 'cSalesOmzet/prosesSalesOmzet';
        $data['page_title'] = 'Sales Omzet';
        $data['supp'] = $this->m_dp->getSuppbyid();
        $data['getmenuid'] = $this->mSalesOmzet->getmenuid($data);
        $data['menu']=$this->db->query($this->querymenu);
        $this->template($data['page_content'],$data);      
    }

    public function prosesSalesOmzet()
    {
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
        
        $data['tahun'] = $this->input->post('tahun');
        $data['supp'] = $this->input->post('supp');
        $data['groupx'] = $this->input->post('group');
        $data['getSupp'] = $this->m_dp->getSuppbyid();
        // $data['getmenuid'] = $this->mSalesOmzet->getmenuid($data);
        $data['page_content'] = 'omzet/vOmzet';
        $data['menu']=$this->db->query($this->querymenu);     
        $data['url'] = 'cSalesOmzet/prosesSalesOmzet';
        $data['page_title'] = 'Sales Omzet';
        $data['listDp'] = $this->m_dp->getDp();
        // $a = $this->m_dp->getDp();
        // echo "<pre>";
        // echo "a : ".$a."<br>";
        // print_r($a);
        // echo "</pre>";
        $data['omzet'] = $this->mSalesOmzet->getSalesOmzet($data);
        $this->template($data['page_content'],$data);
        
    }

    function getOmzet(){

        $id_user=$this->session->userdata('id');
        $draw=$_REQUEST['draw'];
        $length=$_REQUEST['length'];
        $start=$_REQUEST['start'];
        $search=$_REQUEST['search']["value"];
        $this->db->where('id', $id_user);
        $total=$this->db->count_all_results("tbl_temp_omzet");
        $output=array();
        $output['draw']=$draw;
        $output['recordsTotal']=$output['recordsFiltered']=$total;
        $output['data']=array();
        if($search!=""){
        $this->db->where('id', $id_user);
        $this->db->like("nama_comp",$search);
        }
        $this->db->limit($length,$start);
        $this->db->order_by('urutan','ASC');
        $this->db->where('id', $id_user);
        $query=$this->db->get('tbl_temp_omzet');
    
        if($search!=""){
        $this->db->like("nama_comp",$search);
        $this->db->where('id', $id_user);
        $jum=$this->db->get('tbl_temp_omzet');
        $output['recordsTotal']=$output['recordsFiltered']=$jum->num_rows();
        }
    
        $nomor_urut=$start+1;
        foreach ($query->result_array() as $kode) {
          $output['data'][]=array(
            $nomor_urut,
            $kode['nama_comp'],
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


    public function export_SalesOmzet(){
        
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
        $id_user=$this->session->userdata('id');
        $id_repl = $this->uri->segment('3');
       
        $sql = "
            select  a.nocab as nocab, b.supp as supp, b.kodeprod as kodeprod, c.namaprod as namaprod, b.avg as avg, 
                    b.sl as sl, b.stdsl as stdsl, b.stock_akhir as stock_akhir, b.index_stock as index_stock, 
                    b.sales_lalu as sales_lalu, b.harga as harga, b.karton as karton, 
                    b.auto_repl1 as auto_repl1, b.auto_repl2 as auto_repl2, b.pesan1 as pesan1, b.pesan2 as pesan2 
            from    db_po.t_header_repl a inner join db_po.t_detail_repl b
                        on a.id_repl = b.ref_repl
                    INNER JOIN mpm.tabprod c
                        on b.kodeprod = c.kodeprod
            where   a.created_id = '$id_user' and a.id_repl = $id_repl
        ";
        $quer  =   $this->db->query($sql);
      
        query_to_csv($quer,TRUE,"repl-$id_repl.csv");
    }

    



    
}
?>
