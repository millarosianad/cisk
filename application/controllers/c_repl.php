<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class C_repl extends MY_Controller
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

    function C_repl()
    {
        set_time_limit(0);
        $this->load->library(array('table','template','Excel_generator'));
        $this->load->helper(array('url','csv'));
        $this->load->model('m_repl');
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

        redirect('c_repl/form_repl','refresh');
    }

    public function form_repl(){

        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
            $data['page_content'] = 'repl/form_repl';
            $data['url'] = 'c_repl/proses_repl';
            $data['page_title'] = 'Proses Replinehment';
            $data['menu']=$this->db->query($this->querymenu);
            $this->template($data['page_content'],$data);      
    }

    public function proses_repl()
    {
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
        $data['year'] = $this->input->post('year');
        $data['nocab'] = $this->input->post('nocab');
        $data['cycle'] = $this->input->post('cycle');
        $data['tglrepl'] = $this->input->post('tglrepl');
        $data['git'] = $this->input->post('git');

        $data['page_content'] = 'repl/repl';

        $data['hari_kerja'] = $this->m_repl->getharikerja($data);
        $data['last_id_header'] = $this->m_repl->getidheader($data);
        $data['status_jawa'] = $this->m_repl->getjawa($data);
        $data['query'] = $this->m_repl->repl($data);
        $data['menu']=$this->db->query($this->querymenu);     
        $data['url'] = 'c_repl/proses_repl/';
        $data['page_title'] = 'Replineshment';
        $this->template($data['page_content'],$data);
    }

    public function export(){
        
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

    public function to_po(){
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
        if ( function_exists( 'date_default_timezone_set' ) )
        date_default_timezone_set('Asia/Jakarta'); 

        $id_user=$this->session->userdata('id');
        $id_repl = $this->uri->segment('3');
        $supp = $this->uri->segment('4');  
        $date=date('Y-m-d H:i:s');      
        
        $sql = "
                insert into mpm.po(company,alamat,email,npwp,userid,supp,tglpesan,created,created_by,tipe, kode_alamat) 
                select  d.company,d.address,d.email,d.npwp,d.id,'$supp' as supp, a.created_date,'$date',a.created_id,'R',d.kode_alamat
                from    db_po.t_header_repl a 
                        LEFT JOIN
                        (
                            select 	concat(a.kode_comp, a.nocab) as kode, b.company, b.address, b.email,
                            b.npwp, b.id, c.kode_alamat
                            from 	mpm.tbl_tabcomp a LEFT JOIN mpm.`user` b on a.kode_comp = b.username
                            Left Join
                            (
                                Select * FROM mpm.t_alamat
                                where status_ho = 1
                            )c on b.username = c.username
                            where 	a.status = 1 
                            GROUP BY concat(a.kode_comp, a.nocab)
                        )d on a.nocab = d.kode
                where   a.created_id = '$id_user' and a.id_repl = $id_repl
        ";
        
        $this->db->query($sql);
        /*echo "<pre>";
        print_r($sql);
        echo "</pre>";
        */
        $sql='select id from po where created = ?';
        $query = $this->db->query($sql,array($date));
        $row = $query->row();

        $sql = "
            insert into po_detail(id_ref,supp,kodeprod,namaprod,banyak,banyak_karton, harga,kode_prc, berat, volume, userid) 
            select  '$row->id', b.supp, b.kodeprod, c.namaprod,b.unit1,FORMAT(b.unit1/c.ISISATUAN ,0) as banyak_karton, b.harga,c.KODE_PRC, c.berat, c.volume, a.created_id
            from    db_po.t_header_repl a inner join db_po.t_detail_repl b
                                    on a.id_repl = b.ref_repl
                            INNER JOIN mpm.tabprod c
                                    on b.kodeprod = c.kodeprod
            where   a.created_id = '$id_user' and a.id_repl = $id_repl and b.supp = '$supp' and b.unit1 >0
        ";

        $this->db->query($sql);
        /*
        echo "<pre>";
        print_r($sql);
        echo "</pre>";
        */

        $this->email_repl($row->id);


    }

    function email_repl($id){
        // echo "id po : ".$id;
        $this->load->helper('url');
        $this->load->model('trans_model');

        $sql = "
            select a.email
            from mpm.po a
            where id = $id
        ";

        $query = $this->db->query($sql)->row();
        $data['email_to'] = $query->email;       
        $data['id_po'] = $id;       
        $data['company'] = $this->trans_model->getPo($id)->company;       

        
        $server='localhost:3307';
        $user='root';
        $pass='mpm123#@!';
        $db='mpm';

        $filename="(draft) replineshment-".$id."-".$this->trans_model->getPo($id)->company;
        $data['filename'] = $filename;
        $this->load->library('PHPJasperXML');

        $cekSupp = substr($id,-3);

        if ($cekSupp == '012') {
            echo "ini konfigurasi intrafood";
            echo$xml = simplexml_load_file("assets/report/report_po_intrafood.jrxml");
        } else {
            echo$xml = simplexml_load_file("assets/report/report_po.jrxml");
        }

        // echo$xml = simplexml_load_file("assets/report/report_po.jrxml");
        @$this->phpjasperxml->debugsql=false;
        @$this->phpjasperxml->arrayParameter=array('nopesan'=>$id);

        @$this->phpjasperxml->xml_dismantle($xml);

        @$this->phpjasperxml->transferDBtoArray($server,$user,$pass,$db);
        @$this->phpjasperxml->outpage("F",'assets/po/'.$filename.'.pdf');

        $send_email = $this->m_repl->send_email($data); 


    }



    
}
?>