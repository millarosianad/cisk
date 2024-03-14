<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class All_po extends MY_Controller
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

    function all_po()
    {
        set_time_limit(0);
        $this->load->library(array('table','template','Excel_generator', 'form_validation','email'));
        $this->load->helper('url');
        $this->load->helper('csv');
        $this->load->model('model_po');
        $this->load->database();
        $this->querymenu='select  a.id,
                        a.menuview,
                        a.target,
                        a.groupname 
                from    mpm.menu a inner join mpm.menudetail b 
                            on a.id=b.menuid 
                where   b.userid='.$this->session->userdata('id').' and 
                        active=1 
                order by a.groupname,menuview ';

        

    }
    function index()
    {

        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }

        //$this->view_raw();

        echo "a";

    }

    private function template($view,$data)
    {
        $this->template->set_title('MPM SQUARE');
        $this->template->add_js('modules/skeleton.js');
        $this->template->add_css('modules/skeleton.css');
        $this->template->load_view($view, $data);
    }

    public function update_doi(){
      $id_po = $this->uri->segment('3');
      $supp_po = $this->uri->segment('4');
      
      echo $id_po;

      $data['id_po'] = $id_po;
      $data['supp_po'] = $supp_po;
      $query = $this->model_po->update_stock_akhir($data);

    }

    public function generate(){
      $id_po = $this->uri->segment('3');
      $supp_po = $this->uri->segment('4');
      
      echo $id_po;
      echo $supp_po;

      $tanggal = getdate();
      $tahun = $tanggal['year'];
      $bulan = date("m");
      /*
      echo "<br>tahun : ".$tahun;
      echo "<br>bulan : ".$bulan;
      echo "<br>supp_po : ".$supp_po;
      */

      //cek apakah PO dari PT.DJIGOLAK 
      $sql_cek_userid = "        
        select userid
        from mpm.po
        where id = $id_po
      ";
      $cek_userid = $this->db->query($sql_cek_userid);
      foreach ($cek_userid->result() as $ck_userid) {
        $userid = $ck_userid->userid;
      }
      //echo "userid : ".$userid;


      if ($supp_po == '001')
      {        
          $supp_kode = 'DL';
          $tambahan = "";
      }elseif($supp_po =='002'){
          $supp_kode ='PK';
          $tambahan = "";
      }elseif($supp_po =='005')
      {
          if ($userid == '233') 
          {
              $supp_kode_dgl = 'FCB';
              $supp_kode = 'FC';
              $tambahan = "";
          }else{
              $supp_kode = 'FC';
              $tambahan = "and userid <> '233'";
          }          
      }elseif($supp_po == '004'){
          $supp_kode = 'SJ';
          $tambahan = "";
      }elseif($supp_po == '010'){
          $supp_kode = 'AS';
          $tambahan = "";
      }

      //membuat cek ke database
      $sql = "        
        select nopo_sort
        FROM
        (
          select  nopo, SUBSTR(nopo,3,4) as nopo_sort, YEAR(open_date) as tahun_sort,SUBSTR(nopo,1,2) as supp_sort,
                  month(open_date) as bulan_sort,
                  tipe, `open`,  company, supp, id, open_date,userid
          from    mpm.po
          where   supp = '$supp_po'
          ORDER BY id desc, open_date desc 
        )a where tahun_sort = $tahun and bulan_sort = $bulan and supp_sort ='$supp_kode' ".$tambahan."
        ORDER BY nopo_sort desc, id desc
        limit 1
      ";

      echo "<pre>";
      print_r($sql);
      echo "</pre>";
      $cek = $this->db->query($sql);
      $jumlah = $cek->num_rows();
      foreach ($cek->result() as $ck) {
        $nodaftar = $ck->nopo_sort;
      }

      echo "<pre>";
      print_r($sql);
      print_r($nodaftar);
      echo "</pre>";

      //kondisi jika jumlah lebih dari nol dan kurang dari 1 :
      if ($jumlah <> 0) 
      {
        $kode = intval($nodaftar)+1;
      }else{
        $kode = 1;
      }

      //hasil kode
      
      echo "<br>supp_kode : ".$supp_kode."<br>";

      

      //echo "<br>kode : ".$kode."<br>";
      //echo "kodemax :".$kodemax;

      if ($userid == '233')//jika po djigolak 
      {
        $kodemax = str_pad($kode, 3, "0", STR_PAD_LEFT);
        $kode_join = "$supp_kode_dgl$kodemax";
      }else{
        $kodemax = str_pad($kode, 4, "0", STR_PAD_LEFT);
        $kode_join = "$supp_kode$kodemax";
      }
      
      //echo "<br>kode_join : ".$kode_join."<br>";


      redirect('trans/po/show_detail/'.$id_po.'/'.$supp_po.'/'.$kode_join);



    }

    

}
?>
