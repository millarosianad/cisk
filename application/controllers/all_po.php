<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class All_po extends MY_Controller
{

    function all_po()
    {
      $logged_in= $this->session->userdata('logged_in');
      if(!isset($logged_in) || $logged_in != TRUE)
      {
          redirect('login/','refresh');
      }

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
      echo "userid : ".$userid;


      if ($supp_po == '001')
      {        
          $supp_kode = 'DL';
          $tambahan = "";
      }elseif($supp_po =='002'){
          $supp_kode ='PK';
          $tambahan = "";
      }elseif($supp_po =='012'){
        $supp_kode ='IF';
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
              $tambahan = "or supp_sort ='FCB' and userid <> '233'";
          }          
      }elseif($supp_po == '004'){
          $supp_kode = 'SJ';
          $tambahan = "";
      }elseif($supp_po == '010'){
          $supp_kode = 'AS';
          $tambahan = "";
      }

      //membuat cek ke database

      /*
      $sql = "        
      select nopo_sort
      FROM(
            select nopo_sort as x, SUBSTR(nopo_sort,2,4) as nopo_sort
            FROM
            (
              select  nopo, SUBSTR(nopo,3,4) as nopo_sort, YEAR(open_date) as tahun_sort,SUBSTR(nopo,1,2) as supp_sort,
                      month(open_date) as bulan_sort,
                      tipe, `open`,  company, supp, id, open_date,userid
              from    mpm.po
              where   supp = '$supp_po'
              ORDER BY id desc, open_date desc 
            )a where tahun_sort = $tahun and bulan_sort = $bulan and (supp_sort ='$supp_kode' $tambahan) 
            ORDER BY nopo_sort desc, id desc
      )a ORDER BY nopo_sort desc limit 1
      ";
      */

      $sql = "        
        select nopo_sort
        FROM
        (
          select  nopo, SUBSTR(nopo,4,3) as nopo_sort, YEAR(open_date) as tahun_sort,SUBSTR(nopo,1,2) as supp_sort,
                  month(tglpo) as bulan_sort,
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

      $cek = $this->db->query($sql);
      $jumlah = $cek->num_rows();
      foreach ($cek->result() as $ck) {
        $nodaftar = $ck->nopo_sort;
      }

      
      echo "<br>";
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

    public function history()
    {

        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
            $data['page_content'] = 'po/history';
            $data['url'] = 'omzet/data_omzet_hasil/';
            $data['page_title'] = 'sales omzet';
            $data['kode_lang'] = $this->session->userdata('kode_lang');
            
            $data['getpo'] = $this->model_po->getpo($data);
            $data['menu']=$this->db->query($this->querymenu);
            $this->template($data['page_content'],$data);   
    }

    public function export_po($tahun = "", $bulan ="", $nopo = "") {
        
        //$segment3 = $this->uri->segment('3');
      /*
      echo "<pre>";
      echo "no po : ".$nopo."<br>";
      echo "bulan : ".$bulan."<br>";
      echo "tahun : ".$tahun;
      echo "</pre>";
        */
        $sql = "
          select a.nopo, a.company,b.kodeprod, b.namaprod, b.banyak, b.harga
          from mpm.po a INNER JOIN mpm.po_detail b
            on a.id = b.id_ref
          where month(tglpo) =$bulan and year(tglpo) =$tahun and nopo like '$nopo%' and a.deleted  = 0 and b.deleted = 0


        ";

        //print_r($sql);

        $hasil = $this->db->query($sql);

       
        $this->excel_generator->set_query($hasil);
        $this->excel_generator->set_header(array
          (
             'nopo','company', 'kodeprod', 'namaprod','banyak'
          ));
        $this->excel_generator->set_column(array
          ( 
            'nopo',
            'company', 
            'kodeprod',
            'namaprod',
            'banyak'
          ));
        $this->excel_generator->set_width(array(7, 20, 10, 7,13));
        //$this->excel_generator->exportTo2007('Omzet'.'_'.$tahun.'_'.$supplier);   
        $this->excel_generator->exportTo2007('export PO Nita');   
    }

    
    public function get_po()
    {
      $logged_in= $this->session->userdata('logged_in');
      if(!isset($logged_in) || $logged_in != TRUE)
      {
          redirect('login/','refresh');
      }
      $data['page_content'] = 'po/v_po';
      $data['url'] = 'omzet/data_omzet_hasil/';
      $data['page_title'] = 'Get PO';
      $data['kode_lang'] = $this->session->userdata('kode_lang');
      $data['getpo'] = $this->model_po->getpo_deltomed($data);
      $data['menu']=$this->db->query($this->querymenu);
      $this->template($data['page_content'],$data);   

    }

    public function update_tgl()
    {
      $logged_in= $this->session->userdata('logged_in');
      if(!isset($logged_in) || $logged_in != TRUE)
      {
          redirect('login/','refresh');
      }

      $data['id_po'] = $this->uri->segment('3');
      $data['page_content'] = 'po/i_tgl_kirim';
      $data['url'] = 'omzet/data_omzet_hasil/';
      $data['page_title'] = 'Get PO';
      $data['kode_lang'] = $this->session->userdata('kode_lang');
      $data['getpo'] = $this->model_po->getpo_deltomed_per_id($data);
      $data['getproduk'] = $this->model_po->getproduk_deltomed_per_id($data);
      $data['menu']=$this->db->query($this->querymenu);
      $this->template($data['page_content'],$data);  
       
    }

    public function proses_update_tgl_kirim()
    {
      $logged_in= $this->session->userdata('logged_in');
      if(!isset($logged_in) || $logged_in != TRUE)
      {
          redirect('login/','refresh');
      }

      $data['id_po'] = $this->uri->segment('3');
      $data['tgl_kirim'] = $this->input->post('tgl_kirim');
  
      $data['page_content'] = 'po/i_tgl_kirim';
      $data['url'] = 'omzet/data_omzet_hasil/';
      $data['page_title'] = 'Get PO';
      $data['kode_lang'] = $this->session->userdata('kode_lang');
      $data['update_tgl_kirim'] = $this->model_po->update_tgl_kirim($data);
      $data['menu']=$this->db->query($this->querymenu);
      $this->template($data['page_content'],$data);  
       
    }

    public function getPO($id=0)
    {
        $sql='select nopo,note,alamat from mpm.po where id=?';
        $query=$this->db->query($sql,array($id));
        return $query->row();
    }

    public function export_csv() {
        
    $id_po = $this->uri->segment('3');
    $x = $this->uri->segment('3');
    $filename=str_replace('/','_',$this->getPO($id_po)->nopo);
    /*
    echo "<pre>";
    echo "no po : ".$nopo."<br>";
    echo "bulan : ".$bulan."<br>";
    echo "tahun : ".$tahun;
    echo "</pre>";
      */
      $sql = "
      select  a.nopo, d.customerid, c.company, d.alamat,  c.address as ship_to,  
              b.kodeprod, e.kodeprod_deltomed, e.namaprod as dl_description, e.namaprod, banyak
      from    mpm.po a LEFT JOIN
      (
        select  id_ref,kodeprod, namaprod, banyak, deleted
        from    mpm.po_detail
        )b on a.id = b.id_ref
      left JOIN
      (
        SELECT  id, username, company, kode_lang, address
        from    mpm.`user`
      )c on a.userid = c.id
      LEFT JOIN
      (
        select customerid, nama_customer, alamat
        from   dbsls.m_customer
      )d on concat(1,c.kode_lang) = d.customerid
      LEFT JOIN mpm.tabprod e on b.kodeprod = e.kodeprod
      where a.id = '$id_po'  and a.deleted = 0 and b.deleted = 0

      ";

      $quer = $this->db->query($sql);

      query_to_csv($quer,FALSE,''.$filename.'.csv');


  }


  public function show_po_first()
    {
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
        //$data['page_content'] = 'po/show_po';
        $data['url'] = 'omzet/data_omzet_hasil/';
        $data['page_title'] = 'History PO & SPK';
        $data['kode_lang'] = $this->session->userdata('kode_lang');


        $data['x'] = $this->model_po->update_do($data);
        
        //echo "a : ".$this->session->userdata('kode_lang');

        if($data['x']==1)
        {
          redirect('all_po/show_po');
        }else{
          echo "error saat mengupdate data DO dari database. Silahkan hubungi IT MPM.";
        }

        $data['menu']=$this->db->query($this->querymenu);
        //$this->template($data['page_content'],$data);   
    }



    public function show_po()
    {
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
        $data['page_content'] = 'po/show_po';
        $data['url'] = 'omzet/data_omzet_hasil/';
        $data['page_title'] = 'History PO & SPK';
        $data['kode_lang'] = $this->session->userdata('kode_lang');
        $data['getpo'] = $this->model_po->show_po($data);
        
        $data['menu']=$this->db->query($this->querymenu);
        $this->template($data['page_content'],$data);   
    }

    public function detail_po()
    {
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
        $data['page_content'] = 'po/detail_po';
        $data['url'] = 'omzet/data_omzet_hasil/';
        $data['page_title'] = 'History PO & SPK';
        $data['kode_lang'] = $this->session->userdata('kode_lang');
        //$data['getpo'] = $this->model_po->getpo($data);
        $data['getpo'] = $this->model_po->detail_po($data);
        
        $data['menu']=$this->db->query($this->querymenu);
        $this->template($data['page_content'],$data);   
    }

    public function detail_do()
    {
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
        $data['page_content'] = 'po/detail_do';
        $data['page_title'] = 'History PO & SPK';
        $data['kode_lang'] = $this->session->userdata('kode_lang');
        //$data['getpo'] = $this->model_po->getpo($data);
        $data['getpo'] = $this->model_po->detail_do($data);
        
        $data['menu']=$this->db->query($this->querymenu);
        $this->template($data['page_content'],$data);   
    }

    public function update_do(){
      $logged_in= $this->session->userdata('logged_in');
      if(!isset($logged_in) || $logged_in != TRUE)
      {
          redirect('login/','refresh');
      }

        $data['page_content'] = 'po/show_po';
        $data['kode_lang'] = $this->session->userdata('kode_lang');
        //$data['getpo'] = $this->model_po->getpo($data);
        $data['getpo'] = $this->model_po->update_do($data);
        
        $data['menu']=$this->db->query($this->querymenu);
        $this->template($data['page_content'],$data);   
    
    }

    public function update_status_do()
    {
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
        //$data['page_content'] = 'po/show_po';
        
        $data['no_sales'] = $this->uri->segment('3');
        
        $data['page_title'] = 'History PO & SPK';
        $data['kode_lang'] = $this->session->userdata('kode_lang');
        $data['getpo'] = $this->model_po->update_status_do($data);

        echo "no_sales : ".$data['no_sales'];

        $data['menu']=$this->db->query($this->querymenu);
        //$this->template($data['page_content'],$data);   
    }

    public function export_po_us($id = "") {

    //echo "b";
    if(!is_dir('./assets/us/po/archive/'.date('Ym').'/'))
    {
        @mkdir('./assets/us/po/archive/'.date('Ym').'/',0777);
    }

    //cek nopo untuk digunakan sebagai filename
    
    $sql_cek = "        
      select nopo
      from mpm.po
      where id = $id
    ";
    /*
    echo "<pre>";
    print_r($sql_cek);
    echo "</pre>";
*/


    $proses = $this->db->query($sql_cek);
    foreach ($proses->result() as $proses) {
      $full_nopo = $proses->nopo;
      $nopo = substr($proses->nopo,0,6);
      //$nopo_2 = substr($proses->nopo,11,1);
      $nopo_2 = substr($proses->nopo,-8,3);
      //$nopo_3 = substr($proses->nopo,15,5);
      $nopo_3 = substr($proses->nopo,-4);
        /*
        echo "full_nopo :".$full_nopo."<br>";
        echo "nopo :".$nopo."<br>";
        echo "nopo_2 :".$nopo_2."<br>";
        echo "nopo_3 :".$nopo_3."<br>";*/
    }

      $file = fopen(APPPATH . '/../assets/us/po/archive/'.date('Ym').'/'.$nopo.'_MPM_'.$nopo_2.'_'.$nopo_3.'-N'.'.csv', 'wb');

                // set the column headers
                fputcsv($file, array('nopo', 'tglpo', 'tipe', 'customerid', 'company','alamat','note','kodeprod','kode_prc','namaprod','unit'));

                $sql = "
                    select  a.nopo, a.tglpo,a.tipe, d.customerid, a.company,a.alamat,a.note,b.kodeprod, b.kode_prc, b.namaprod,b.banyak
                    from    mpm.po a LEFT JOIN
                    (
                        select  id_ref,kodeprod, namaprod, banyak, deleted,kode_prc
                        from    mpm.po_detail
                        )b on a.id = b.id_ref
                    left JOIN
                    (
                        SELECT  id, username, company, kode_lang, address
                        from    mpm.`user`
                    )c on a.userid = c.id
                    LEFT JOIN
                    (
                        select customerid, nama_customer, alamat
                        from   dbsls.m_customer
                    )d on concat(1,c.kode_lang) = d.customerid
                    LEFT JOIN db_produk.tbl_produk e on b.kodeprod = e.kodeprod
                    where a.id = '$id' and a.deleted = 0 and b.deleted = 0
                ";
                /*
                echo "<pre>";
                print_r($sql);
                echo "</pre>";
                */
                $hasil = $this->db->query($sql);

                foreach ($hasil->result() as $row) 
                {
                    $nopo = $row->nopo;
                    $tglpo = $row->tglpo;
                    $tipe = $row->tipe;
                    $customerid = $row->customerid;
                    $company = $row->company;
                    $alamat = $row->alamat;
                    $note = $row->note;
                    $kodeprod = $row->kodeprod;
                    $kode_prc = $row->kode_prc;
                    $namaprod = $row->namaprod;
                    $banyak = $row->banyak;
                    fputcsv($file, array($nopo,$tglpo,$tipe,$customerid,$company,$alamat,$note,$kodeprod,$kode_prc,$namaprod,$banyak));
                }
                redirect('trans/po/show');

                
    }

    public function export_po_us_r($id = "") {

      if(!is_dir('./assets/us/po/archive/'.date('Ym').'/'))
      {
          @mkdir('./assets/us/po/archive/'.date('Ym').'/',0777);
      }
  
      //cek nopo untuk digunakan sebagai filename
      
      $sql_cek = "        
        select nopo
        from mpm.po
        where id = $id
      ";
      $proses = $this->db->query($sql_cek);
      foreach ($proses->result() as $proses) {
        $full_nopo = $proses->nopo;
      $nopo = substr($proses->nopo,0,6);
      //$nopo_2 = substr($proses->nopo,11,1);
      $nopo_2 = substr($proses->nopo,-8,3);
      //$nopo_3 = substr($proses->nopo,15,5);
      $nopo_3 = substr($proses->nopo,-4);
      
        echo "nopo ".$nopo;
        echo "nopo_2 ".$nopo_2;
        echo "nopo_3 ".$nopo_3;
      }
        //$file = fopen(APPPATH . '/../assets/us/po/archive/'.date('Ym').'/'.$nopo.'.csv', 'wb');
        $file = fopen(APPPATH . "/../assets/us/po/archive/".date('Ym')."/".$nopo."_MPM_".$nopo_2."_".$nopo_3."-R".".csv", "wb");
  
                  // set the column headers
                  fputcsv($file, array('nopo', 'tglpo', 'tipe', 'customerid', 'company','alamat','note','kodeprod','kode_prc','namaprod','unit'));
  
                  $sql = "
                      select  a.nopo, a.tglpo,a.tipe, d.customerid, a.company,a.alamat,a.note,b.kodeprod, b.kode_prc, b.namaprod,b.banyak
                      from    mpm.po a LEFT JOIN
                      (
                          select  id_ref,kodeprod, namaprod, banyak, deleted,kode_prc
                          from    mpm.po_detail
                          )b on a.id = b.id_ref
                      left JOIN
                      (
                          SELECT  id, username, company, kode_lang, address
                          from    mpm.`user`
                      )c on a.userid = c.id
                      LEFT JOIN
                      (
                          select customerid, nama_customer, alamat
                          from   dbsls.m_customer
                      )d on concat(1,c.kode_lang) = d.customerid
                      LEFT JOIN db_produk.tbl_produk e on b.kodeprod = e.kodeprod
                      where a.id = '$id' and a.deleted = 0 and b.deleted = 0
                  ";
                  /*
                  echo "<pre>";
                  print_r($sql);
                  echo "</pre>";
                  */
                  $hasil = $this->db->query($sql);
  
                  foreach ($hasil->result() as $row) 
                  {
                      $nopo = $row->nopo;
                      $tglpo = $row->tglpo;
                      $tipe = $row->tipe;
                      $customerid = $row->customerid;
                      $company = $row->company;
                      $alamat = $row->alamat;
                      $note = $row->note;
                      $kodeprod = $row->kodeprod;
                      $kode_prc = $row->kode_prc;
                      $namaprod = $row->namaprod;
                      $banyak = $row->banyak;
                      fputcsv($file, array($nopo,$tglpo,$tipe,$customerid,$company,$alamat,$note,$kodeprod,$kode_prc,$namaprod,$banyak));
                  }
  
                  
      }

      public function test(){
        if ( function_exists( 'date_default_timezone_set' ) )
        date_default_timezone_set('Asia/Jakarta');  
        echo date('Y-m-d H:i:s');
      }

      public function view_po()
    {
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
        $data['page_content'] = 'po/view_po';
        $data['url'] = 'omzet/data_omzet_hasil/';
        $data['page_title'] = 'Konfirmasi terima PO';
        $data['kode_lang'] = $this->session->userdata('kode_lang');
        $data['getpo'] = $this->model_po->view_po($data);
        
        $data['menu']=$this->db->query($this->querymenu);
        $this->template($data['page_content'],$data);   
    }

    public function konfirmasi(){
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
        $data['id_po'] = $this->uri->segment('3');
        $data['page_content'] = 'po/view_konf';
        $data['url'] = 'all_po/update_konf/';
        $data['page_title'] = 'Konfirmasi terima PO';
        $data['kode_lang'] = $this->session->userdata('kode_lang');
        $data['query'] = $this->model_po->detail_po($data);
        $data['proses'] = $this->model_po->jumlah_po($data);         
        $data['menu']=$this->db->query($this->querymenu);

        $this->template($data['page_content'],$data);
    }

    public function updateStatusBarang(){
        $logged_in= $this->session->userdata('logged_in');
        if(!isset($logged_in) || $logged_in != TRUE)
        {
            redirect('login/','refresh');
        }
        $data['statusTombol'] = $this->uri->segment('3');
        $data['idPo'] = $this->uri->segment('4');
        $data['kodeprod'] = $this->uri->segment('5');
        //$data['url'] = 'all_po/prosesUpdateStatusBarang/';
        $data['page_content'] = 'po/view_konf';
        $data['page_title'] = 'Konfirmasi terima PO';
        $data['query'] = $this->model_po->updateStatusBarang($data);        
        $data['menu']=$this->db->query($this->querymenu);
        $this->template($data['page_content'],$data);

    }

    public function kirimEmailPo(){
      $logged_in= $this->session->userdata('logged_in');
      if(!isset($logged_in) || $logged_in != TRUE)
      {
          redirect('login/','refresh');
      }

      $this->load->model('trans_model','po');

      $supp = $this->uri->segment('3');
      $userid = $this->uri->segment('4');
      $filename = $this->uri->segment('5');
      echo "supp : ".$supp."<br>";
      echo "userid : ".$userid."<br>";
      echo "filename : ".$filename."<br>";
      $emailSupp = $this->po->getEmailSupp($supp);
      echo "emailSupp : ".$emailSupp;
      $hasilto = explode(',', $emailSupp);
      print_r($hasilto);


      $from = "tria@muliaputramandiri.com";
      // $from = "suffy.yanuar@gmail.com";
      // $to = "suffy@muliaputramandiri.com";
      $to = $hasilto;
      $cc = "tria@muliaputramandiri.com, herman.oscar@muliaputramandiri.com";
      $subject = "PO NO ".$filename;
      $message = "This email is sent by system";


      $this->load->library('email');
      
      $config['protocol']     = 'smtp';
      $config['smtp_host']    = 'ssl://smtp.gmail.com';
      $config['smtp_port']    = '465';
      $config['smtp_timeout'] = '300';
      $config['smtp_user']    = 'suffy@muliaputramandiri.net';
      $config['smtp_pass']    = 'vruzinbjlnsgzagy';
      $config['charset']      = 'utf-8';
      $config['newline']      = "\r\n";
      $config['mailtype']     ="html";
      $config['use_ci_email'] = TRUE;
      $config['wordwrap']     = TRUE;

    //   $config['protocol']  = 'smtp';
    // $config['smtp_host'] = 'ssl://smtp.gmail.com';
    // $config['smtp_port'] = '465';
    // $config['smtp_timeout'] = '300';
    // $config['smtp_user'] = 'agustriatriani@gmail.com';
    //   $config['smtp_pass'] = '080890TRIA';
    // $config['charset']  = 'utf-8';
    // $config['newline']  = "\r\n";
    // $config['mailtype'] ="html";
    // $config['use_ci_email'] = TRUE;
    // $config['wordwrap'] = TRUE;

      $this->email->initialize($config);
      $this->email->from($from,'PT. Mulia Putra Mandiri');
      $this->email->to($to);
      $this->email->cc($cc);
      $this->email->subject($subject);
      $this->email->message($message);
      $this->email->attach('assets/po/'.$filename.'.pdf');
      $this->email->attach('assets/po/'.$filename.'.csv');
      $this->email->send();

      // echo $this->email->print_debugger();

      redirect("all_po/kirimEmailPoDp/$supp/$userid/$filename");

      // $this->template($data['page_content'],$data);


  }

  public function kirimEmailPoDp(){
    $logged_in= $this->session->userdata('logged_in');
    if(!isset($logged_in) || $logged_in != TRUE)
    {
        redirect('login/','refresh');
    }

    $this->load->model('trans_model','po');

    $supp = $this->uri->segment('3');
    $userid = $this->uri->segment('4');
    $filename = $this->uri->segment('5');
    // echo "supp : ".$supp."<br>";
    // echo "userid : ".$userid."<br>";
    // echo "filename : ".$filename."<br>";
    $email = $this->po->getEmail($userid);
    echo "email : ".$email;
    $hasilto = explode(',', $email);
    print_r($hasilto);


    $from = "tria@muliaputramandiri.com";
    // $from = "agustriatriani@gmail.com";
    $to = $hasilto;
    // $to = "suffy.yanuar@gmail.com";
    $cc = "herman.oscar@muliaputramandiri.com, dian.nugraha@deltomed.com";
    $subject = "PO NO ".$filename;
    $message = "This email is sent by system";

    $this->load->library('email');

    $config['protocol']     = 'smtp';
    $config['smtp_host']    = 'ssl://mail.muliaputramandiri.com';
    $config['smtp_port']    = '465';
    $config['smtp_timeout'] = '300';
    $config['smtp_user']    = 'support@muliaputramandiri.com';
    $config['smtp_pass']    = 'support123!@#';
    $config['charset']      = 'utf-8';
    $config['newline']      = "\r\n";
    $config['mailtype']     ="html";
    $config['use_ci_email'] = TRUE;
    $config['wordwrap']     = TRUE;

    // $config['protocol']  = 'smtp';
    // $config['smtp_host'] = 'ssl://smtp.gmail.com';
    // $config['smtp_port'] = '465';
    // $config['smtp_timeout'] = '300';
    // $config['smtp_user'] = 'agustriatriani@gmail.com';
    //   $config['smtp_pass'] = '080890TRIA';
    // $config['charset']  = 'utf-8';
    // $config['newline']  = "\r\n";
    // $config['mailtype'] ="html";
    // $config['use_ci_email'] = TRUE;
    // $config['wordwrap'] = TRUE;

    $this->email->initialize($config);
    $this->email->from($from,'PT. Mulia Putra Mandiri');
    $this->email->to($to);
    // $this->email->cc($cc);
    $this->email->subject($subject);
    $this->email->message($message);
    $this->email->attach('assets/po/'.$filename.'.pdf');
    $this->email->send();
    $this->email->print_debugger();


    // $this->template($data['page_content'],$data);
  }

  public function export_po_dp()
  {
    $logged_in= $this->session->userdata('logged_in');
    if(!isset($logged_in) || $logged_in != TRUE)
    {
        redirect('login/','refresh');
    }
        $data['page_content'] = 'po/form_export_po_dp';
        $data['url'] = 'all_po/proses_export_po_dp/';
        $data['page_title'] = 'export po';

        $data['menu']=$this->db->query($this->querymenu);
        $this->template($data['page_content'],$data);   
  }

  public function proses_export_po_dp()
  {
    $id = $this->session->userdata('id');
    $tahun = $this->input->post('tahun');
    $bulan = $this->input->post('bulan');
    $sql = "
      select 	a.userid, a.nopo, c.NAMASUPP, a.tglpo, day(a.tglpo) as 'tanggal', 
              a.tglpesan, a.tipe, a.alamat, b.kodeprod, b.kode_prc, b.banyak
      from    mpm.po a INNER JOIN mpm.po_detail b 
                on a.id = b.id_ref
              INNER JOIN mpm.tabsupp c 
				        on a.supp = c.SUPP
      where 	year(a.tglpo) = $tahun and month(a.tglpo) = $bulan and a.userid = $id
              and a.deleted = 0 and b.deleted = 0 and a.nopo not like '/MPM%'

    ";
    $quer = $this->db->query($sql);
    query_to_csv($quer,TRUE,'export po per dp.csv');
      
  }

  public function laporan_po()
  {
      $logged_in= $this->session->userdata('logged_in');
      if(!isset($logged_in) || $logged_in != TRUE)
      {
          redirect('login/','refresh');
      }

      // $id =$this->session->userdata('id');
      // if($id =='297'){
        $data['page_content'] = 'po/form_laporan';
      $data['query'] = $this->model_po->getSuppbyid();
      $data['url'] = 'all_po/laporan_po_hasil/';
      $data['page_title'] = 'Laporan PO';
      $data['menu']=$this->db->query($this->querymenu);
      $this->template($data['page_content'],$data);
      // }else{
      //   echo "sedang ada perbaikan. Silahkan kunjungi menu ini nanti";
      // }

            
  }

  public function laporan_po_hasil()
  {
      $logged_in= $this->session->userdata('logged_in');
      if(!isset($logged_in) || $logged_in != TRUE)
      {
          redirect('login/','refresh');
      }
      // if ($this->session->userdata('id') != '297') {
      //   echo "Sedang ada perbaikan. Mohon kunjungi beberapa saat lagi";
      // }else{
        $data['tahun'] = $this->input->post('tahun');
        $data['bulan'] = $this->input->post('bulan');
        $data['supp'] = $this->input->post('supp');
        $data['url'] = 'all_po/laporan_po_hasil/';
        $data['page_title'] = 'Laporan PO';
        $data['page_content'] = 'po/laporan_po';
        $data['query'] = $this->model_po->getSuppbyid();
        $data['hasil']=$this->model_po->laporan_po($data);
        $data['menu']=$this->db->query($this->querymenu);
        $this->template($data['page_content'],$data);
      //}
  }

  public function export_laporan_po()
  {
    $id = $this->session->userdata('id');
    
    $sql = "
      select 	* from db_po.t_laporan_po
      where 	userid = $id
    ";
    $quer = $this->db->query($sql);
    query_to_csv($quer,TRUE,'export laporan po.csv');
      
  }

  public function list_order()
  {
    
    $id = $this->session->userdata('id');
    if($id == 5880 || $id == 4420){
      $data['query'] = $this->model_po->get_company_po();
    }else{
      $data['query'] = $this->model_po->get_company();
    }

    $data['page_content'] = 'po/list_order';
    $data['hasil'] = $this->model_po->list_order();
    $data['url'] = 'all_po/list_order_company/';
    $data['page_title'] = 'List Order';
    $data['menu']=$this->db->query($this->querymenu);
    $this->template($data['page_content'],$data);  
      
  }

  public function unlock_finance($id,$supp,$userid){
    $logged_in= $this->session->userdata('logged_in');
    if(!isset($logged_in) || $logged_in != TRUE)
    {
        redirect('login/','refresh');
    }
    $proses = $this->model_po->unlock_finance($id,$supp);
    if ($proses) {
      redirect('all_po/list_order_detail/'.$id.'/'.$supp.'/'.$userid);
    }else{
      return array();
    }
  }

  public function list_order_company()
  {
    $logged_in= $this->session->userdata('logged_in');
    if(!isset($logged_in) || $logged_in != TRUE)
    {
        redirect('login/','refresh');
    }
    $userid = $this->input->post('company');
    $data['page_content'] = 'po/list_order';
    $data['hasil'] = $this->model_po->list_order_company($userid);
    $data['url'] = 'all_po/list_order_company/';
    $data['query'] = $this->model_po->get_company_po();
    $data['page_title'] = 'List Order';
    $data['menu']=$this->db->query($this->querymenu);
    $this->template($data['page_content'],$data);  
      
  }

  public function list_order_detail()
  {
    $logged_in= $this->session->userdata('logged_in');
    if(!isset($logged_in) || $logged_in != TRUE)
    {
        redirect('login/','refresh');
    }

    $id = $this->session->userdata('id');
    $id_po = $this->uri->segment('3');
    $supp = $this->uri->segment('4');
    $nocab = $this->uri->segment('5');

    // $sql = "
    //     Select * from mpm.t_wilayah_po
    //     where userid = $id and wilayah = $nocab
    // ";

    // die;

    // $proses = $this->db->query($sql)->result();
    if($id == 547 || $id == 297 || $id == 515){
      $data['page_content'] = 'po/list_order_detail';
    }elseif($id == 588|| $id == 442 || $id == 681){
      // foreach ($proses as $key) {
      //   $userid = $key->userid;
      //   $wilayah = $key->wilayah;
      //   $status = $key->status;

      //   echo "<pre>";
      //   echo "userid : ".$userid;
      //   echo "wilayah : ".$wilayah;
      //   echo "status : ".$status;
      //   echo "</pre>";
        
      //   if($userid == $id && $wilayah == $nocab && $status == 1){
      //     echo "a";
      //     $data['page_content'] = 'po/list_order_detail';
      //   }elseif($userid == $id && $wilayah == $nocab && $status != 1){
      //     echo "b";
      //     $data['page_content'] = 'po/list_order_detail_readonly';
      //   }
      // }

      $data['page_content'] = 'po/list_order_detail';


    }else{
      $data['page_content'] = 'po/list_order_detail_readonly';
    }

    $data['header'] = $this->model_po->list_order_header($id_po);
    $data['detail'] = $this->model_po->list_order_detail($id_po);
    $data['product'] = $this->model_po->list_product_supp_admin($id_po,$supp);
    $data['url'] = 'all_po/tambah_product/';
    $data['url_po'] = 'all_po/submit_po/';
    $data['url_approval'] = 'all_po/proses_approval/';
    $data['page_title'] = 'List Order Detail';
    $data['menu']=$this->db->query($this->querymenu);
    $this->template($data['page_content'],$data);   
      
  }

  public function tambah_product(){
    $logged_in= $this->session->userdata('logged_in');
    if(!isset($logged_in) || $logged_in != TRUE)
    {
        redirect('login/','refresh');
    }
    $data['id_po'] = $this->input->post('id_po');
    $data['kodeprod'] = $this->input->post('kodeprod');
    $data['jumlah'] = $this->input->post('jumlah');
    $data['userid'] = $this->input->post('userid');
    $data['proses'] = $this->model_po->tambah_product($data);

  }

  function delete_Product($id_po,$id_kodeprod,$supp,$userid)
  {
      
      // $this->db->where('id', $id_kodeprod);
      // $this->db->where('id_ref', $id_po)
      //           ->delete('mpm.po_detail');
      // redirect('all_po/list_order_detail/'.$id_po.'/'.$supp);
      $sql_delete = "
      update mpm.po_detail a
      set a.deleted = 1
      where a.id_ref = $id_po and a.id = $id_kodeprod
    ";
    $proses = $this->db->query($sql_delete);

    
      $sql = "
          update mpm.po a
          set a.lock = null, open = null, open_by = null, status = null, status_approval = null, alasan_approval = null
          where a.id = $id_po and a.supp = $supp
      ";
      $proses = $this->db->query($sql);
      if ($proses) {
        redirect('all_po/list_order_detail/'.$id_po.'/'.$supp.'/'.$userid);
      }else{
        return array();
      }
  }

  public function proses_doi($id_po,$tahun,$bulan,$tglpesan,$kode,$supp)
  {
    $logged_in= $this->session->userdata('logged_in');
    if(!isset($logged_in) || $logged_in != TRUE)
    {
        redirect('login/','refresh');
    }
    $data['proses'] = $this->model_po->proses_doi($id_po,$tahun,$bulan,$tglpesan,$kode,$supp);
  }

  public function proses_po_to_finance($id_po,$supp)
  {
    $logged_in= $this->session->userdata('logged_in');
    if(!isset($logged_in) || $logged_in != TRUE)
    {
        redirect('login/','refresh');
    }
    $data['proses'] = $this->model_po->proses_po_to_finance($id_po,$supp);
  }

  public function proses_approval()
  {
    $logged_in= $this->session->userdata('logged_in');
    if(!isset($logged_in) || $logged_in != TRUE)
    {
        redirect('login/','refresh');
    }
    $data['id_po'] = $this->input->post('id_po');
    $data['supp'] = $this->input->post('supp');
    $data['alasan_approval'] = $this->input->post('alasan_approval');
    $data['userid'] = $this->input->post('userid');

    // echo "zzzzzz : ".$this->input->post('userid');
    $data['proses'] = $this->model_po->proses_approval($data);
  }

  public function generate_new($id_po,$supp){

    date_default_timezone_set('Asia/Jakarta'); 
    $date = date_create(date('Y-m-d H:i:s'));
    date_add($date, date_interval_create_from_date_string('-7 hours'));
    $tgl = date_format($date, 'Y-m-d H:i:s'); 

    $tanggal = getdate();
    $tahun = $tanggal['year'];
    $bulan = date("m");
    $userid = $this->uri->segment('5');
    
    // echo "id_po : ".$id_po."<br>";
    // echo "supp : ".$supp_po."<br>";
    // echo "tahun : ".$tahun;
    // echo "<br>bulan : ".$bulan;
    // echo "<br>supp_po : ".$supp_po;

    $sql = "select a.`open` from mpm.po a where a.id = $id_po";
    $proses = $this->db->query($sql)->result();
    
    foreach($proses as $x){
      $status_open = $x->open;
    }

    // echo "status_open : ".$status_open;

    if ($status_open != '1') {
      // echo "<script>
			// alert('Generate nopo Gagal karena order belum di open oleh finance');
			// window.location.href='../../list_order_detail/$id_po/$supp';
			// </script>";
      echo "<script>alert('Generate nopo Gagal karena order belum di open oleh finance'); </script>";
      redirect('all_po/list_order_detail/'.$id_po.'/'.$supp.'/'.$userid,'refresh');
    }else{
      
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
        echo "userid : ".$userid;

        if ($supp == '001')
        {        
            $supp_kode = 'DL';
            $tambahan = "";
        }elseif($supp =='002'){
            $supp_kode ='PK';
            $tambahan = "";
        }elseif($supp =='012'){
          $supp_kode ='IF';
          $tambahan = "";
        }elseif($supp =='005')
        {
            if ($userid == '233') 
            {
                $supp_kode_dgl = 'FCB';
                $supp_kode = 'FC';
                $tambahan = "";
            }else{
                $supp_kode = 'FC';
                $tambahan = " and userid <> '233' or supp_sort ='FCB'";
            }          
        }elseif($supp == '004'){
            $supp_kode = 'SJ';
            $tambahan = "";
        }elseif($supp == '010'){
            $supp_kode = 'AS';
            $tambahan = "";
        }elseif($supp == '013'){
          $supp_kode = 'ST';
          $tambahan = "";
        }elseif($supp == '014'){
          $supp_kode = 'HN';
          $tambahan = "";
        }elseif($supp == '015'){
          $supp_kode = 'MD';
          $tambahan = "";
        }


        //default = 4,3
        $sql = "        
          select nopo_sort
          FROM
          (
            select  nopo, SUBSTR(nopo,3,4) as nopo_sort, YEAR(open_date) as tahun_sort,SUBSTR(nopo,1,2) as supp_sort,
                    month(tglpo) as bulan_sort,
                    tipe, `open`,  company, supp, id, open_date,userid
            from    mpm.po
            where   supp = '$supp' and nopo not like 'fcb%'
            ORDER BY id desc, open_date desc 
          )a where tahun_sort = $tahun and bulan_sort = $bulan and supp_sort ='$supp_kode' ".$tambahan."
          ORDER BY nopo_sort desc, id desc
          limit 1
        ";

        // echo "<pre>";
        // print_r($sql);
        // echo "</pre>";

        $cek = $this->db->query($sql);
        $jumlah = $cek->num_rows();
        foreach ($cek->result() as $ck) {
          $nodaftar = $ck->nopo_sort;
        }

        //kondisi jika jumlah lebih dari nol dan kurang dari 1 :
        if ($jumlah <> 0) 
        {
          $kode = intval($nodaftar)+1;
        }else{
          $kode = 1;
        }

        if ($userid == '233')//jika po djigolak 
        {
          $kodemax = str_pad($kode, 3, "0", STR_PAD_LEFT);
          $kode_join = "$supp_kode_dgl$kodemax";
        }else{
          $kodemax = str_pad($kode, 4, "0", STR_PAD_LEFT);
          $kode_join = "$supp_kode$kodemax";
        }
        
        redirect('all_po/list_order_detail/'.$id_po.'/'.$supp.'/'.$userid.'/'.$kode_join);


    }

    

    
  }

  public function submit_po(){
    $nopo = $this->input->post('nopo');  
    if ($nopo == '' || $nopo == null) {
      echo "<script>alert('Nomor PO tidak boleh kosong'); </script>";
      redirect('all_po/list_order','refresh');
    }
    $data['id_po'] = $this->input->post('id_po');
    $data['nopo'] = $this->input->post('nopo');
    $data['alamat_kirim'] = $this->input->post('alamat_kirim');
    $data['note'] = $this->input->post('note');
    $data['po_ref'] = $this->input->post('po_ref');
    
    $proses = $this->model_po->submit_po($data);

  }

  public function timezone(){
    $date = date_create(date('Y-m-d H:i:s'));
    date_add($date, date_interval_create_from_date_string('-7 hours'));
    $tgl = date_format($date, 'Y-m-d H:i:s');
  }

  function delete_po($id_po)
  {
      
      // $this->db->where('id', $id_kodeprod);
      // $this->db->where('id_ref', $id_po)
      //           ->delete('mpm.po_detail');
      // redirect('all_po/list_order_detail/'.$id_po.'/'.$supp);
      $sql = "
        update mpm.po a
        set a.deleted = 1
        where a.id = $id_po
      ";
      $proses = $this->db->query($sql);
      if ($proses) {
        redirect('all_po/list_order');
      }else{
        return array();
      }
  }

  public function insert_do()
  {
    $logged_in= $this->session->userdata('logged_in');
    if(!isset($logged_in) || $logged_in != TRUE)
    {
        redirect('login/','refresh');
    }
    $data['page_content'] = 'po/insert_do';
    $data['url'] = 'all_po/insert_do_proses/';
    $data['query'] = $this->model_po->getSuppbyid();
    $data['page_title'] = 'Insert DO Deltomed';
    $data['menu']=$this->db->query($this->querymenu);
    $this->template($data['page_content'],$data);

  }

  public function insert_do_proses(){

    // echo "<br><br><br><br><br><br>Aaaaaa";
    $id = $this->session->userdata('id');
    $this->load->library('upload'); // Load librari upload        
    $config['upload_path'] = './assets/uploads/do/';    
    $config['allowed_types'] = '*';    
    $config['max_size']  = '2048';    
    $config['overwrite'] = true;    
    // $config['file_name'] = 'xx';      
    $this->upload->initialize($config); 
    
    // Load konfigurasi uploadnya    
    if($this->upload->do_upload('file')){ 
      $upload_data = $this->upload->data();
      $filename = $upload_data['orig_name'];
                     
      $csvreader = PHPExcel_IOFactory::createReader('CSV');        
      $loadcsv = $csvreader->load('assets/uploads/do/'.$filename); 
      $sheet = $loadcsv->getActiveSheet()->getRowIterator();                
      $data['sheet'] = $sheet;

      // echo "<br><br><br>";
      // var_dump($sheet);

      $data = array();        
      $numrow = 0;    
      foreach($sheet as $row){         
        if($numrow >= 0){        
          // echo $numrow;
          $cellIterator = $row->getCellIterator();        
          $cellIterator->setIterateOnlyExistingCells(false); 

          $get = array(); 
          foreach ($cellIterator as $cell) {          
            array_push($get, $cell->getValue());        
          }        
          // // <-- END                
          // // Ambil data value yang telah di ambil dan dimasukkan ke variabel $get        
          $kode = $get[0];
          $nodo = $get[1];
          $tgldo = $get[2];
          $kode_dp = $get[3];
          $nama_dp = $get[4];
          $kosong1 = $get[5];
          $kosong2 = $get[6];
          $kosong3 = $get[7];
          $kosong4 = $get[8];
          $kodeprod = $get[9];
          $kosong5 = $get[10];
          $namaprod = $get[11];
          $banyak = $get[12];
          $kosong6 = $get[13];
          $nopo = $get[14];
            
          // // Kita push (add) array data ke variabel data        
          array_push($data, array(          
            'kode'=>$kode,           
            'nodo'=>$nodo,         
            'tgldo'=>$tgldo,        
            'kodedp'=>$kode_dp,      
            'company'=>$nama_dp,           
            'kosong1'=>$kosong1,         
            'kosong2'=>$kosong2,        
            'kosong3'=>$kosong3,      
            'kosong4'=>$kosong4,           
            'kodeprod_delto'=>$kodeprod,         
            'kosong5'=>$kosong5,        
            'namaprod'=>$namaprod,      
            'qty'=>$banyak,           
            'kosong6'=>$kosong6,         
            'nopo'=>$nopo,
            'id'=>$id     
          ));     
         }            
         $numrow++; // Tambah 1 setiap kali looping 
          
        }
        $data['proses'] = $this->model_po->insert_do($data);  
        
    }else{  
        $return = array('result' => 'failed', 'file' => '', 'error' => $this->upload->display_errors());      
        var_dump($return);
    }

    $data['page_content'] = 'po/insert_do_proses';
    $data['url'] = 'all_po/insert_do_proses/';
    $data['query'] = $this->model_po->getSuppbyid();
    $data['page_title'] = 'Insert DO';
    $data['menu']=$this->db->query($this->querymenu);
    $this->template($data['page_content'],$data);

  }

  public function insert_do_us()
  {
    $logged_in= $this->session->userdata('logged_in');
    if(!isset($logged_in) || $logged_in != TRUE)
    {
        redirect('login/','refresh');
    }

    if(!is_dir('./assets/uploads/do/do_us/'.date('Ym').'/'))
    {
        @mkdir('./assets/uploads/do/do_us/'.date('Ym').'/',0777);
    }

    $data['page_content'] = 'po/insert_do_us';
    $data['url'] = 'all_po/insert_do_us_proses/';
    $data['query'] = $this->model_po->getSuppbyid();
    $data['page_title'] = 'Insert DO Ultra Sakti';
    $data['menu']=$this->db->query($this->querymenu);
    $this->template($data['page_content'],$data);

  }

  public function insert_do_us_proses(){
    if ( function_exists( 'date_default_timezone_set' ) )
    date_default_timezone_set('Asia/Jakarta');
    $id = $this->session->userdata('id');
    $this->load->library('upload'); // Load librari upload        
    $config['upload_path'] = './assets/uploads/do/do_us/'.date('Ym');    
    $config['allowed_types'] = '*';    
    $config['max_size']  = '2048';    
    $config['overwrite'] = true;         
    $this->upload->initialize($config); 
    $created = date('Y-m-d H:i:s');
    
    // Load konfigurasi uploadnya    
    if($this->upload->do_upload('file')){ 
      $upload_data = $this->upload->data();
      $filename = $upload_data['file_name'];
      $raw_name = $upload_data['raw_name'];
      // $tgldo = substr($upload_data['raw_name'],7,2)."/".substr($upload_data['raw_name'],5,2)."/"."20".substr($upload_data['raw_name'],3,2);
      // $tgldo = substr($upload_data['raw_name'],5,2)."/".substr($upload_data['raw_name'],7,2)."/"."20".substr($upload_data['raw_name'],3,2);
      $tgldo = "20".substr($upload_data['raw_name'],3,2).substr($upload_data['raw_name'],5,2).substr($upload_data['raw_name'],7,2);
      $filename_tgl = substr($upload_data['raw_name'],7,2);
      // echo "filename_tgl : ".$filename_tgl."<br>";
      // echo "tgldo : ".$tgldo;

      if(!is_dir('./assets/uploads/do/do_us/'.date('Ym').'/'.$raw_name.'/'))
      {
        @mkdir('./assets/uploads/do/do_us/'.date('Ym').'/'.$raw_name.'/',0777);
      }

      $zip = new ZipArchive;
      $file = "C:/xampp/htdocs/cisk/assets/uploads/do/do_us/".date('Ym')."/".$filename;
      $openZip = $zip->open($file);
      
      if ($openZip === TRUE) 
      {  
          if (!$zip->extractTo('./assets/uploads/do/do_us/'.date('Ym').'/'.$raw_name.'/'))
          {
            echo "gagal extract";
          }else{
            
            $header = fopen("C:/xampp/htdocs/cisk/assets/uploads/do/do_us/".date('Ym')."/".$raw_name."/".$tgldo."_DO".".txt", "r") or die("file cannot open");
            $detail = fopen("C:/xampp/htdocs/cisk/assets/uploads/do/do_us/".date('Ym')."/".$raw_name."/".$tgldo."_DX".".txt", "r") or die("file cannot open");

            if ($header) 
            {
              $proses = $this->db->query("delete from db_po.t_do_us_header where tgldo = '$tgldo'");
              while (($line = fgets($header)) !== false) 
              {
                  $lineArr = explode("\t", $line);
                  $data = array(
                    'kode'     => str_replace('"','',$lineArr[0]),
                    'nodo'    => str_replace('"','',$lineArr[2]),
                    'tgldo'   => str_replace('"','',$lineArr[3]),
                    'kodedp'  => str_replace('"','',$lineArr[4]),
                    'company' => str_replace('"','',$lineArr[5]),
                    'nopo'    => str_replace('"','',$lineArr[12]),
                    'userid'  => $id,
                    'lastupdate' => $created
                  );
                  $insert = $this->db->insert('db_po.t_do_us_header',$data) ;    
              }
              if (fclose($header)) {
                  echo "update header do us berhasil";
                  echo "<br>";
                }
            } 
            else{
                echo "file cannot open";
            } 

            if ($detail) 
            {
                $proses = $this->db->query("delete from db_po.t_do_us_detail where tgldo = '$tgldo'");
                while (($line = fgets($detail)) !== false) 
                {
                    $lineArr = explode("\t", $line);
                    $data = array(
                      'kode'      => str_replace('"','',$lineArr[0]),
                      'nodo'      => str_replace('"','',$lineArr[1]),
                      'tgldo'     => str_replace('"','',$lineArr[2]),
                      'kodedp'    => str_replace('"','',$lineArr[3]),
                      'company'   => str_replace('"','',$lineArr[4]),
                      'kodeprod'  => str_replace('"','',$lineArr[9]),
                      'namaprod'  => str_replace('"','',$lineArr[11]),
                      'banyak'    => str_replace('"','',$lineArr[12]),
                      'userid'    => $id,
                      'lastupdate' => $created
                   );
                    $insert = $this->db->insert('db_po.t_do_us_detail',$data) ;    
                }
                if (fclose($detail)) {
                  echo "update detail do us berhasil";
                    // redirect(base_url());
                }
            } 
            else{
                echo "file cannot open";
            }

            $sql_del = $this->db->query("delete from db_po.t_do_us where tgldo = '$tgldo'");
            $sql = "
                    insert into db_po.t_do_us
                    select	'',a.kode, a.nodo, a.tgldo, a.kodedp, a.company, a.nopo,
                            b.kodeprod, c.namaprod, b.banyak, $id, '$created'
                    from 		db_po.t_do_us_header a LEFT JOIN db_po.t_do_us_detail b
                              on a.nodo = b.nodo LEFT JOIN 
                              (
                                select 	a.kodeprod, a.namaprod
                                from		mpm.tabprod a
                                where 	a.SUPP ='005'
                              )c on b.kodeprod = c.kodeprod
                    where		a.tgldo = '$tgldo'    
            ";
            $proses = $this->db->query($sql);

            $sql = "
              update db_po.t_do_us a
              set a.nopo = replace(a.nopo,'\n','')
              where a.tgldo = '$tgldo'
            ";
            $proses = $this->db->query($sql);

            // $sql = "
            //   update db_po.t_do_us a
            //   set a.nopo = replace(a.nopo,right(a.nopo,'1'),'')
            // ";
            // $proses = $this->db->query($sql);

          }
      }else{
        echo "gagal";
      } 
        
    }else{  
        $return = array('result' => 'failed', 'file' => '', 'error' => $this->upload->display_errors());      
        var_dump($return);
    }

    $data['page_content'] = 'po/insert_do_us_proses';
    $data['url'] = 'all_po/insert_do_us_proses/';
    $data['page_title'] = 'Insert DO US';
    $data['menu']=$this->db->query($this->querymenu);
    $data['proses'] = $this->db->query("select * from db_po.t_do_us a where a.tgldo = '$tgldo'")->result();
    $this->template($data['page_content'],$data);

  }

  public function po_monitoring(){
    $logged_in= $this->session->userdata('logged_in');
    if(!isset($logged_in) || $logged_in != TRUE)
    {
        redirect('login/','refresh');
    }
    $data['page_content'] = 'po/po_monitoring';
    // $data['url'] = 'all_po/insert_do_proses/';
    $data['query'] = $this->model_po->do_deltomed();
    $data['queryUs'] = $this->model_po->do_us();
    $data['page_title'] = 'PO Monitoring';
    $data['menu']=$this->db->query($this->querymenu);
    $this->template($data['page_content'],$data);


  }

  public function po_outstanding(){
    $logged_in= $this->session->userdata('logged_in');
    if(!isset($logged_in) || $logged_in != TRUE)
    {
        redirect('login/','refresh');
    }
    $data['page_content'] = 'po/po_outstanding';
    $data['url'] = 'all_po/po_outstanding_proses/';
    $data['query'] = $this->model_po->getSuppbyid();
    $data['page_title'] = 'PO Outstanding';
    $data['menu']=$this->db->query($this->querymenu);
    $this->template($data['page_content'],$data);
  }

  public function po_outstanding_proses(){
    $logged_in= $this->session->userdata('logged_in');
    if(!isset($logged_in) || $logged_in != TRUE)
    {
        redirect('login/','refresh');
    }

    $data['supp'] = $this->input->post('supp');
    $p1 = trim($this->input->post("periode1"));
    $data['periode1']=strftime('%Y-%m-%d',strtotime($p1));
    $p2 = trim($this->input->post("periode2"));
    $data['periode2']=strftime('%Y-%m-%d',strtotime($p2));
    $data['status_do'] = $this->input->post('status_do');
    $data['status_total'] = $this->input->post('status_total');

    $supp = $this->input->post('supp');
    if ($supp == '001') {
      $data['proses'] = $this->model_po->po_outstanding($data);  
      $data['page_content'] = 'po/po_outstanding_proses';
    }elseif ($supp == '005') {
      $data['proses'] = $this->model_po->po_outstanding_us($data);  
      $data['page_content'] = 'po/po_outstanding_proses_us';
    }elseif ($supp == '012') {
      $data['proses'] = $this->model_po->po_outstanding_intrafood($data);  
      $data['page_content'] = 'po/po_outstanding_proses_intrafood';
    }
        
    $data['url'] = 'all_po/po_outstanding_proses/';
    $data['query'] = $this->model_po->getSuppbyid();
    $data['page_title'] = 'PO Outstanding';
    $data['menu']=$this->db->query($this->querymenu);
    $this->template($data['page_content'],$data);
  }

  public function export_po_outstanding() {
        
    $id = $this->session->userdata('id');
    $sql = " select  * from db_po.t_po_outstanding_deltomed where created_by =$id and created_date = (select max(created_date) from db_po.t_po_outstanding_deltomed where created_by = $id)";
    $quer = $this->db->query($sql);

    query_to_csv($quer,TRUE,'Po_OutStanding_deltomed.csv');

  }

  public function export_po_outstanding_us() {
        
    $id = $this->session->userdata('id');
    $sql = " select  * from db_po.t_po_outstanding_us where created_by =$id and created_date = (select max(created_date) from db_po.t_po_outstanding_us where created_by = $id)";
    $quer = $this->db->query($sql);

    query_to_csv($quer,TRUE,'Po_OutStanding_US.csv');

  }

  public function export_po_outstanding_intrafood() {
        
    $id = $this->session->userdata('id');
    $sql = " select  * from db_po.t_po_outstanding_intrafood where created_by =$id and created_date = (select max(created_date) from db_po.t_po_outstanding_intrafood where created_by = $id)";
    $quer = $this->db->query($sql);

    query_to_csv($quer,TRUE,'Po Outstanding Intrafood.csv');

  }

  public function export_do_deltomed($tgldo)
  {
    $sql = "
      select * from db_po.t_do_deltomed a
      where a.tgldo = date_format('$tgldo', '%d/%m/%Y')
    ";
    $quer = $this->db->query($sql);
    query_to_csv($quer,TRUE,"export do deltomed $tgldo.csv");
      
  }

  public function export_do_us($tgldo)
  {
    $sql = "
      select * from db_po.t_do_us a
      where a.tgldo = date_format('$tgldo', '%m/%d/%Y')
    ";
    $quer = $this->db->query($sql);
    query_to_csv($quer,TRUE,"export do us $tgldo.csv");
      
  }

  public function detail_po_outstanding($nopo,$kodeprod){

    // $data['url'] = 'all_po/detail_po_outstanding/';
    // $data['page_content'] = 'po/detail_po_outstanding';
    // $data['detail'] = $this->model_po->get_do($nopo);
    // $data['page_title'] = 'Detail PO Outstanding';
    // $data['menu']=$this->db->query($this->querymenu);
    // $this->template($data['page_content'],$data);

    $sql = "
    select 	a.nodo, replace(a.nopo,right(a.nopo,'1'),'') as nopo , a.tgldo, a.kodeprod, banyak as banyak_do
    from 	  db_po.t_do_us a
    where   replace(a.nopo,right(a.nopo,'1'),'') = replace('$nopo','_','/') and kodeprod ='$kodeprod'
    ";
    $quer = $this->db->query($sql);
    query_to_csv($quer,TRUE,"detail_do.csv");
  }

  public function test_time(){
    echo date_default_timezone_get();
  }

  public function konfirmasi_alamat_spk(){
    echo "spk";

    $this->load->model('model_po');
    // var_dump($data);
    $data['page_title'] = 'Confirmation';
    $data['client']=$this->model_po->getCustInfo();
    $client=$this->model_po->getCustInfo();
    // var_dump($client);

    // $data['url'] = site_url('trans/spk/save');
    
    // foreach ($client as $a) {
    //   // echo $a->alamat;
    // }

    $data['page_content'] = 'po/konfirmasi_alamat_spk';
    $data['url'] = 'trans/spk/save';
    $data['query'] = $this->model_po->getSuppbyid();
    $data['page_title'] = 'Confirmation';
    $data['menu']=$this->db->query($this->querymenu);
    $this->template($data['page_content'],$data);

  }

  public function konfirmasi_alamat_po(){
    echo "po";

    $this->load->model('model_po');
    $tipe=$this->input->post('tipe');
    $id=$this->input->post('userid');
    $data['page_title'] = 'Confirmation';
    $data['client']=$this->model_po->getCustInfo2($this->session->userdata('client'));
    $data['url'] = site_url('trans/po/manual_save/'.$id);

    $data['page_content'] = 'po/konfirmasi_alamat_po';
    $data['query'] = $this->model_po->getSuppbyid();
    $data['menu']=$this->db->query($this->querymenu);
    $this->template($data['page_content'],$data);

  }

  public function override_alamat($id_po, $supp, $id_dp){
    
    $alamat_dc = "jalan abc surabaya";
    $update = "update mpm.po a set a.alamat_kirim = '$alamat_dc', a.status_override = 1 where a.id = $id_po";
    $proses_update = $this->db->query($update);
    if ($proses_update) {
        echo "<script>alert('override berhasil'); </script>";
        redirect("all_po/list_order_detail/$id_po/$supp/$id_dp",'refresh');
    }else{
        echo "<script>alert('override gagal'); </script>";
        redirect("all_po/list_order_detail/$id_po/$supp/$id_dp",'refresh');
    }

  }


}
?>
