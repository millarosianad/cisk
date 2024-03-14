<?php if( ! defined('BASEPATH')) exit('Akses langsung tidak diperkenankan');

error_reporting(E_ALL);
ini_set('display_errors', '1');
class Trans_model extends CI_Model
{
    var $total_query='';
    var $limit=20;
    var $js=array('onclick'=>"return confirm('Are you sure you want to delete?')");
    var $output_table = '';
    var $output_print='';
    var $image_properties_edit=array(
                 'src'    => 'assets/css/images/icon_edit.gif',
            );
    var $image_properties_del=array(
         'src'    => 'assets/css/images/delete.png',
         'width'  => '20',
         'height' => '20',
    );
    var $image_properties_detail=array(
        'src'    => 'assets/css/images/detail.gif',
                    );
    var $image_properties_locked=array(
        'src'    => 'assets/css/images/locked.gif',
                    );
    var $image_properties_open=array(
        'src'    => 'assets/css/images/open.gif',
                    );
    var $image_properties_print=array(
                 'src'    => 'assets/css/images/print.png',
                 'height' => '20',
                 'weight' => '20'
            );
    var $image_properties_excel=array(
                 'src'    => 'assets/css/images/excel.png',
                 'height' => '20',
                 'weight' => '20'
            );
    var $image_properties_print_off=array(
                 'src'    => 'assets/css/images/print_off.png',
                 'height' => '20',
                 'weight' => '20'
            );
    var $image_properties_email=array(
                 'src'    => 'assets/css/images/mail.png',
                 'height' => '20',
                 'weight' => '20'
            );
    var $image_properties_email_off=array(
                 'src'    => 'assets/css/images/mail_off.png',
                 'height' => '20',
                 'weight' => '20'
            );
    var $image_properties_download=array(
                 'src'    => 'assets/css/images/download.png',
                 'height' => '20',
                 'weight' => '20');
            
    var $tmpl=array(
                'table_open'=>'<table class="table" style="font-size:12px">'
                ,'heading_start'=>'<thead>'
                ,'heading_end'=>'</thead>'
                ,'heading_row_start' =>'<tr class="success">'
                ,'heading_row_end'   =>'</tr>'
                //,'heading_cell_start'=>'<th bgcolor = "#666666">'
                ,'heading_cell_start'=>'<th>'
                ,'heading_cell_end'=>'</th>'

                ,'body_start'=>'<tbody>'
                ,'body_end'=>'</tbody>'
                ,'row_start' =>'<tr>'
                ,'row_end'   =>'</tr>'
                ,'cell_start'=>'<td>'
                ,'cell_end'=>'</td>'

                ,'row_alt_start' =>'<tr bgcolor="#AFDEF8">'
                ,'row_alt_end'   =>'</tr>'
                ,'cell_alt_start'=>'<td>'
                ,'cell_alt_end'=>'</td>'
        
                ,'foot_start'=>'<tfoot>'
                ,'foot_end'=>'</tfoot>'
                ,'foot_row_start' =>'<tr class="danger">'
                ,'foot_row_end'   =>'</tr>'
                ,'foot_cell_start'=>'<th>'
                ,'foot_cell_end'=>'</th>'
                ,'table_close'=>'</table>'
                );
      var $tmpl_piutang=array(
                'table_open'=>'<table id="thetable">'
                ,'heading_start'=>'<thead>'
                ,'heading_end'=>'</thead>'
                ,'heading_row_start' =>'<tr class="warning">'
                ,'heading_row_end'   =>'</tr>'
                //,'heading_cell_start'=>'<th bgcolor = "#666666">'
                ,'heading_cell_start'=>'<th>'
                ,'heading_cell_end'=>'</th>'

                ,'body_start'=>'<tbody>'
                ,'body_end'=>'</tbody>'
                ,'row_start' =>'<tr>'
                ,'row_end'   =>'</tr>'
                ,'cell_start'=>'<td>'
                ,'cell_end'=>'</td>'

                ,'row_alt_start' =>'<tr class="success">'
                ,'row_alt_end'   =>'</tr>'
                ,'cell_alt_start'=>'<td>'
                ,'cell_alt_end'=>'</td>'
        
                ,'foot_start'=>'<tfoot>'
                ,'foot_end'=>'</tfoot>'
                ,'foot_row_start' =>'<tr class="danger">'
                ,'foot_row_end'   =>'</tr>'
                ,'foot_cell_start'=>'<th>'
                ,'foot_cell_end'=>'</th>'
                ,'table_close'=>'</table>'
                );
    public function Trans_model()
    {
        set_time_limit(0);

        $this->load->database();
        $this->load->library(array('table','session','zip','stable','user_agent'));//session untuk sisi administrasi
        $this->load->helper(array('text_helper','array_helper','file_helper','to_excel_helper'));
        //$this->load->plugin('to_excel');
        //$this->config->load('sorot');
    }

    public function check($field,$str)
    {
        //$sql='select menuname from mpm.menu where '.$field.' = "'.$str.'"';
        $sql='select 1 from mpm.tabprod where kodeprod = ?';
        $query = $this->db->query($sql,array($str));
        if($query->num_rows()>0)
        {
            return FALSE;
        }
        else
        {
            return TRUE;
        }
    }
    public function getTotalQuery()
    {
        return $this->total_query;
    }
    private function getTipe2($transid=null)
    {
        $sql='select tipe from mpm.po where id=?';
        $query=$this->db->query($sql,array($transid));
        $row=$query->row();
        return $row->tipe;
    }
    public function getSupp()
    {
        if($this->session->userdata('level')==7)
        {
            $sql='select supp, namasupp from mpm.tabsupp where supp = "001"';
        }
        else
        {
            $sql='select supp, namasupp from mpm.tabsupp where supp <>"000" and active=1';
        }
        return $this->db->query($sql);
    }
    public function getPelanggan($kode)
    {
        $sql='select grup_nama from pusat.user where grup_lang=? limit 1';
        $query=$this->db->query($sql,array($kode));
        if($query->num_rows()>0)
        {
            $row=$query->row();
            return $row->grup_nama;
        }
        else
        {
            return false;
        }
    }
    public function getCustomer($nocab)
    {
        $sql='select nama_comp from tabcomp where nocab=?';
        $query=$this->db->query($sql,array($nocab));
        $row=$query->row();
        return $row->nama_comp;
    }
    public function getCompanyByKodelang($kode_lang)
    {
        $sql='select company from mpm.user where kode_lang=?';
        $query=$this->db->query($sql,array($kode_lang));
        $row=$query->row();
        return $row->company;
    }
    public function getReturInfo($key=null)
    {
        $sql="select * from mpm.trans where id=?";
        $query=$this->db->query($sql,array($key));
        return $row=$query->row();
    }
    private function getHarga($kode=null,$user=null)
    {
        $client=isset($user)?$user:$this->session->userdata('client');
        $sql='select exclude,level,diskon from user where id='.$client;
        $query=$this->db->query($sql);
        $row=$query->row();
        $level=$row->level;
        $diskon=$row->diskon;
        $exclude=$row->exclude;
        switch($level)
        {
            case 4:
                if($exclude==1){
                $sql="select h_dp-(h_pbf*d_dp/100) as harga from prod_detail where kodeprod='".$kode."' and tgl=(select max(tgl) from prod_detail where kodeprod='".$kode."')";
                }
                else
                $sql="select h_dp-(h_dp*d_dp/100) as harga from prod_detail where kodeprod='".$kode."' and tgl=(select max(tgl) from prod_detail where kodeprod='".$kode."')";
                break;
            case 5:
                $sql="select h_pbf-(h_pbf*".$diskon."/100) as harga from prod_detail where kodeprod='".$kode."' and tgl=(select max(tgl) from prod_detail where kodeprod='".$kode."')";
                break;
            case 6:
                $sql="select h_bsp-(h_bsp*d_bsp/100) as harga from prod_detail where kodeprod='".$kode."' and tgl=(select max(tgl) from prod_detail where kodeprod='".$kode."')";
                break;
            case 7:
                 $sql="select h_pbf-(h_pbf*".$diskon."/100) as harga from prod_detail where kodeprod='".$kode."' and tgl=(select max(tgl) from prod_detail where kodeprod='".$kode."')";
                    break;
        }
        $query=$this->db->query($sql);
        $row=$query->row();
        return $row->harga;
    }
    public function getCustInfo()
    {
        $id=$this->session->userdata('id');
        $sql='select company,npwp,address,email from mpm.user where id=?';
        $query=$this->db->query($sql,array($id));
        return $query->row();
    }
    public function getCustInfo2($id=null)
    {
        //$id=$this->session->userdata('id');
        $sql='select company,nama_wp,npwp,address,email,alamat_wp from mpm.user where id=?';
        $query=$this->db->query($sql,array($id));
        return $query->row();
    }
    public function getCompany($userid)
    {
        $sql='select company from user where id=?';
        $query=$this->db->query($sql,array($userid));
        $row=$query->row();
        return $row->company;
    }
    public function getNomorsj()
    {
        $sql='select max(nomor) as nomor from sj where deleted=0 and month(tanggal)='.date('m');
        $query=$this->db->query($sql);
        $row=$query->row();
        return substr(date('Y').'/'.$this->getRome(date('m')).'/'.'000'.($row->nomor+1),-3);
    }
    private function getRome($number=null)
    {
        switch($number)
        {
            case 1: return 'I';break;
            case 2: return 'II';break;
            case 3: return 'III';break;
            case 4: return 'IV';break;
            case 5: return 'V';break;
            case 6: return 'VI';break;
            case 7: return 'VII';break;
            case 8: return 'VIII';break;
            case 9: return 'IX';break;
            case 10: return 'X';break;
            case 11: return 'XI';break;
            case 12: return 'XII';break;
        }
    }
    public function list_product()
    {
        return $this->db->query('select kodeprod, namaprod from mpm.tabprod  where active=1 order by namaprod');
    }
    public function list_product_admin()
    {
        return $this->db->query('select kodeprod, namaprod from mpm.tabprod  where produksi=1 order by namaprod');
    }
    public function list_product_retur()
    {
        return $this->db->query('select kodeprod, namaprod from mpm.tabprod  where active=1 order by namaprod');
    }
    public function list_product_supp($supp)
    {
        $prod_tambahan = '010070';
        
        if($this->session->userdata('level')==7)
        {
            return $this->db->query('select kodeprod, namaprod,isisatuan,odrunit from mpm.tabprod  where active=1  and permen=1 and supp="'.$supp.'" or (KODEPROD = "'.$prod_tambahan.'")order by namaprod');
        }
        else 
        {
            return $this->db->query('select kodeprod, namaprod,isisatuan,odrunit from mpm.tabprod  where active=1 and supp="'.$supp.'" order by namaprod');
        }
    }
    public function list_product_supp_admin($supp)
    {
        return $this->db->query('select kodeprod, namaprod,isisatuan,odrunit from mpm.tabprod  where produksi=1 and supp="'.$supp.'" order by namaprod');
    }
    public function list_product_supp_retur($supp)
    {
        return $this->db->query('select kodeprod, namaprod,isisatuan from mpm.tabprod  where supp="'.$supp.'" order by namaprod');
    }
    public function getProduct($kodeprod='')
    {
        return $this->db->query('select supp,kodeprod,namaprod,kode_prc,grupprod,isisatuan,h_dp from mpm.tabprod where kodeprod="'.$kodeprod.'"');
    }
    public function getProductReturAjax($namaprod=null,$tanggal=null,$user=null)
    {
        $tgl=isset($tanggal)?$tanggal:$this->session->userdata('tgl');
        $client=isset($user)?$user:$this->session->userdata('client');
        $sql='select exclude,level,diskon from user where id='.$client;
        $query=$this->db->query($sql);
        $row=$query->row();
        $level=$row->level;
        $diskon=$row->diskon;
        $exclude=$row->exclude;

        echo "tgl : ".$tgl."<br>";
        echo "client : ".$client."<br>";
        echo "level : ".$level."<br>";
        echo "diskon : ".$diskon."<br>";

        switch($level)
        {
            case 4:
                if($exclude==1){
                $sql='select a.kodeprod,a.kode_prc,a.namaprod,a.supp,a.grupprod,a.isisatuan,b.h_pbf as harga,b.d_dp as diskon,h_beli_dp as harga_beli,d_beli_dp as diskon_beli from tabprod a inner join prod_detail b using(kodeprod) 
                    where kodeprod = "%'.$namaprod.'%" and tgl=(select max(tgl) from prod_detail where kodeprod = "%'.$namaprod.'%" and tgl<="'.$tgl.'")';
                }
                else
                $sql='select a.kodeprod,a.kode_prc,a.namaprod,a.supp,a.grupprod,a.isisatuan,b.h_dp as harga,b.d_dp as diskon, h_beli_dp as harga_beli,d_beli_dp as diskon_beli from tabprod a inner join prod_detail b using(kodeprod) 
                    where kodeprod = "%'.$namaprod.'%" and tgl=(select max(tgl) from prod_detail where kodeprod = "%'.$namaprod.'%" and tgl<="'.$tgl.'")';
                break;
            case 5:
                $sql='select a.kodeprod,a.kode_prc,a.namaprod,a.supp,a.grupprod,a.isisatuan,b.h_pbf as harga,'.$diskon.' as diskon,h_beli_pbf as harga_beli,d_beli_pbf as diskon_beli from tabprod a inner join prod_detail b using(kodeprod) 
                    where kodeprod = "%'.$namaprod.'%" and tgl=(select max(tgl) from prod_detail where kodeprod = "%'.$namaprod.'%" and tgl<="'.$tgl.'")';
                break;
            case 6:
                $sql='select a.kodeprod,a.kode_prc,a.namaprod,a.supp,a.grupprod,a.isisatuan,b.h_bsp as harga ,b.d_bsp as diskon, h_beli_bsp as harga_beli,d_beli_bsp as diskon_beli from tabprod a inner join prod_detail b using(kodeprod) 
                    where kodeprod = "%'.$namaprod.'%" and tgl=(select max(tgl) from prod_detail where kodeprod = "%'.$namaprod.'%" and tgl<="'.$tgl.'")';
                break;
            case 7:
                $sql='select a.kodeprod,a.kode_prc,a.namaprod,a.supp,a.grupprod,a.isisatuan,b.h_dp as harga,b.d_dp as diskon, h_beli_dp as harga_beli,d_beli_dp as diskon_beli from tabprod a inner join prod_detail b using(kodeprod) where kodeprod="'.$kodeprod.'" and tgl=(select max(tgl) from prod_detail where kodeprod="'.$kodeprod.'" and tgl<="'.$tgl.'")';
                break;
        }
        $sql='select * from mpm.tabprod where namaprod like "%'.$namaprod.'%"';
        $query=$this->db->query($sql);
       
        if($query->num_rows > 0)
        {
            foreach ($query->result_array() as $row)
            {
                $new_row['label']=htmlentities(stripslashes($row['namaprod']));
                $new_row['value']=htmlentities(stripslashes($row['kodeprod']));
                $row_set[] = $new_row; //build an array
            }
            echo json_encode($row_set); 
        }
       

    }
    public function getProductRetur($kodeprod=null,$tanggal=null,$user=null)
    {
        $tgl=isset($tanggal)?$tanggal:$this->session->userdata('tgl');
        $client=isset($user)?$user:$this->session->userdata('client');
        $sql='select exclude,level,diskon from user where id='.$client;
        $query=$this->db->query($sql);
        $row=$query->row();
        $level=$row->level;
        $diskon=$row->diskon;
        $exclude=$row->exclude;
        switch($level)
        {
            case 4:
                if($exclude==1){
                    $sql='select a.kodeprod,a.kode_prc,a.namaprod,a.supp,a.grupprod,a.isisatuan,b.h_pbf as harga,b.d_dp as diskon,h_beli_dp as harga_beli,d_beli_dp as diskon_beli from tabprod a inner join prod_detail b using(kodeprod) where kodeprod="'.$kodeprod.'" and tgl=(select max(tgl) from prod_detail where kodeprod="'.$kodeprod.'" and tgl<="'.$tgl.'")';
                }
                else
                $sql='select a.kodeprod,a.kode_prc,a.namaprod,a.supp,a.grupprod,a.isisatuan,b.h_dp as harga,b.d_dp as diskon, h_beli_dp as harga_beli,d_beli_dp as diskon_beli from tabprod a inner join prod_detail b using(kodeprod) where kodeprod="'.$kodeprod.'" and tgl=(select max(tgl) from prod_detail where kodeprod="'.$kodeprod.'" and tgl<="'.$tgl.'")';
                break;
            case 5:
                $sql='select a.kodeprod,a.kode_prc,a.namaprod,a.supp,a.grupprod,a.isisatuan,b.h_pbf as harga,'.$diskon.' as diskon,h_beli_pbf as harga_beli,d_beli_pbf as diskon_beli from tabprod a inner join prod_detail b using(kodeprod) where kodeprod="'.$kodeprod.'" and tgl=(select max(tgl) from prod_detail where kodeprod="'.$kodeprod.'" and tgl<="'.$tgl.'")';
                break;
            case 6:
                $sql='select a.kodeprod,a.kode_prc,a.namaprod,a.supp,a.grupprod,a.isisatuan,b.h_bsp as harga ,b.d_bsp as diskon, h_beli_bsp as harga_beli,d_beli_bsp as diskon_beli from tabprod a inner join prod_detail b using(kodeprod) where kodeprod="'.$kodeprod.'" and tgl=(select max(tgl) from prod_detail where kodeprod="'.$kodeprod.'" and tgl<="'.$tgl.'")';
                break;
            case 7:
                $sql='select a.kodeprod,a.kode_prc,a.namaprod,a.supp,a.grupprod,a.isisatuan,b.h_dp as harga,b.d_dp as diskon, h_beli_dp as harga_beli,d_beli_dp as diskon_beli from tabprod a inner join prod_detail b using(kodeprod) where kodeprod="'.$kodeprod.'" and tgl=(select max(tgl) from prod_detail where kodeprod="'.$kodeprod.'" and tgl<="'.$tgl.'")';
                break;
        }
        $query=$this->db->query($sql);
        if($query->num_rows()>0)
            return $query;
        else
            return false;

    }
    public function piutang($state=null,$key=0,$id='',$init='')
    {   
        switch($state)
        {
            case 'email':
            {
                    switch($key)
                    {
                        case '51':
                            $message='1-7';
                        break;
                        case '61':
                            $messadivinege='8-14';
                        break;  
                        case '71':
                            $message='15-30';
                        break; 
                        case '81':
                            $message='>30';
                        break; 
                    }
                    
                    $this->email_config_piutang();
                    $this->email->from('trisnandha@gmail.com', "PT. Mulia Putra Mandiri");
                    //$this->email->from('suffy.yanuar@gmail.com', "PT. Mulia Putra Mandiri");
                    
                    //$this->email->to($this->getEmailSupp(substr($id,-3)));//$this->email->to('one@example.com, two@example.com, three@example.com');
                    $this->email->to($this->getEmailFinance(substr($id,1)));
                    //$this->email->to("ninol_cute@yahoo.com");
                   // $list = array('henryjoseph760@gmail.com','nanita7974@gmail.com','hwiryanto@gmail.com','herman_oscar@yahoo.com');
                    $this->email->cc($list);//'henryjoseph760@gmail.com','herman_oscar@yahoo.com');
                    //$this->email->bcc('them@their-example.com');

                    $this->email->subject("Tagihan overdue ".$this->getCompanyByKodelang(substr($id,1)));
                    //$this->email->message("This Email is sent by system,"."\r\n"."\r\n"."Process By Yunita");
                    $this->email->message("Dear ".$this->getCompanyByKodelang(substr($id,1)).
                            "<br/><br/>Sehubungan dengan tagihan ".$this->getCompanyByKodelang(substr($id,1))." yang telah jatuh tempo ".$message." hari ,dengan rincian sbb<br/><br/>"
                            .$this->piutang('detail',$key,$id,$init)."<br/><br/>Kami meminta bantuannya untuk dapat melunasi tagihan yang telah jatuh tempo dalam minggu ini. "
                            ."Bila ada pertanyaan dapat menghubungi PT.MPM.<br/><br/>Terima Kasih<br/><br/>Rgds<br/><br/>Trisnandha<br/><br/>NB:Abaikan email ini jika sudah melakukan pembayaran atas faktur tersebut"
                            );
                    $this->email->send();
            }break;
            case 'email2':
            {
                            
                    //echo "a";   
                    $this->email_config_piutang();
                    $this->email->from('trisnandha@gmail.com', "PT. Mulia Putra Mandiri");
                    //$this->email->from('suffy.yanuar@gmail.com', "PT. Mulia Putra Mandiri");
                    

                    //$this->email->to($this->getEmailSupp(substr($id,-3)));//$this->email->to('one@example.com, two@example.com, three@example.com');
                    $this->email->to($this->getEmailFinance(substr($id,1)));
                    //$this->email->to("ninol_cute@yahoo.com");
                    $list = array('henryjoseph760@gmail.com','nanita7974@gmail.com','hwiryanto@gmail.com','herman_oscar@yahoo.com');
                    $this->email->cc($list);//'henryjoseph760@gmail.com','herman_oscar@yahoo.com');
                    //$this->email->bcc('them@their-example.com');

                    $this->email->subject("Tagihan Overdue ".$this->getCompanyByKodelang(substr($id,1)));
                    //$this->email->message("This Email is sent by system,"."\r\n"."\r\n"."Process By Yunita");
                    $this->email->message("Dear ".$this->getCompanyByKodelang(substr($id,1)).
                            "<br/><br/>Sehubungan dengan tagihan ".$this->getCompanyByKodelang(substr($id,1))." yang telah jatuh tempo lebih dari tujuh hari ,dengan rincian sbb<br/><br/>"
                            .$this->piutang('detail7up',$key,$id,$init)."<br/><br/>Kami meminta bantuannya untuk dapat melunasi tagihan yang telah jatuh tempo dalam minggu ini. "
                            ."Bila ada pertanyaan dapat menghubungi PT.MPM.<br/><br/>Terima Kasih<br/><br/>Rgds<br/><br/>Trisnandha<br/><br/>NB:Abaikan email ini jika sudah melakukan pembayaran atas faktur tersebut"
                            );
                    $this->email->send();
            }break;
            case 'test':
            {
                    /*ini_set('display_errors', 1);
                    $server = '192.168.0.1:1433';
                    $link = mssql_connect($server, 'sa', 'mpm12345');

                    if (!$link) {
                    die('<br/><br/>Something went wrong while connecting to MSSQL');
                    }
                    else {
                    $selected = mssql_select_db("dbsls", $link)
                    or die("Couldn't open database databasename");
                    echo "connected to databasename<br/>";

                    $result = mssql_query("select tanggal from t_ar_ink_master");

                    while($row = mssql_fetch_array($result))
                    echo $row["name"] . "<br/>";
                    }*/
                $sqlserver=$this->load->database('mssql',true);
                $query=$sqlserver->query("select * from dbsls.t_ar_ink_master");
                $i=0;$sum=0;
               
                
                if($query->num_rows()>0)
                {
                    //$this->table->set_empty('');
                    $this->stable->set_template($this->tmpl_piutang);

                    $this->stable->set_caption("Test");
                    $this->stable->set_heading('No','Nomor Faktur','NO DO','Supplier','Tanggal','J. Tempo','Nilai');
                    foreach ($query->result() as $value)
                    {
                        $js=array('onclick'=>"return confirm('Are you sure you want to delete?')");

                        $this->stable->add_row(
                                $i++
                                ,anchor_popup('trans/piutang/faktur/'.$value->ref.'/'.$value->tanggal,$value->ref)
                                ,$value->ref
                                ,$value->ref//$value->supp!='006'?$this->getSuppname($value->supp):"PT. ULTRA SAKTI"
                                ,$value->tanggal
                                ,$value->tgl_tempo

                                ,'<div  style="text-align:right">'.number_format($value->rp_kotor,2).'</div>'

                                //,'<div style="text-align:center">'.anchor('trans/permit/delete_temp/'.$value->id,img($this->image_properties_del),$js).'</div>'
                                );
                                $sum+=$value->rp_kotor;
                    }
                    $this->stable->set_foot(
                                ''
                                ,''
                                ,''
                                ,''
                                ,''
                                ,'Total'
                                ,'<div  style="text-align:right">'.number_format($sum,2).'</div>');
                    $this->output_table .= $this->stable->generate();
                    $this->output_table .= '<br />';
                    //$this->table->clear();
                    $query->free_result();
                    $this->output_table;
                    return $this->output_table;
                }
                else
                {
                    return FALSE;
                }             
         
            }break;
            case 'faktur':
            {
                    $server='192.168.1.2';
                    $user='root';
                    $pass='mpm123';
                    $db='mpm';
                    $this->load->library('PHPJasperXML');
                    $xml_fk = simplexml_load_file("assets/report/trans/faktur_komersil_baru.jrxml");
                    $PHPJasperXML = new PHPJasperXML();
                    $PHPJasperXML->debugsql=false;
                    $PHPJasperXML->arrayParameter=array('id'=>$key,'bulan'=>$id);
                    $PHPJasperXML->xml_dismantle($xml_fk);
                    $PHPJasperXML->transferDBtoArray($server,$user,$pass,$db);
                    $PHPJasperXML->outpage("I");
            }break;
            case 'detail7up':
            {
                $start=substr($init,0,7).'-01';
                $end=$init;
                $bulan = substr($init,2,2).substr($init,5,2);
                $sql="select * from(
                                select right(trim(no_polisi),8) nodokjdi,ref nodokacu,date_format(tgl_tempo,'%d %M %Y') tgl_jtempo,date_format(tanggal,'%d %M %Y') tgldokjdi,
                                SUM(IF(days_past_due > 7, amount_due, 0)) nilai
                                FROM(
                                    SELECT no_polisi,ref,tgl_tempo,tanggal,no_sales,group_id, group_descr, datediff(?,tgl_tempo) days_past_due, sum(saldo) amount_due 
                                    FROM(
                                        SELECT no_polisi,ref,tanggal,no_sales ,a.customerid ,tgl_tempo ,( IF ( a.retur = 1, a.dokument *- 1, a.dokument )) - IF (isnull(kredit), 0, kredit) saldo
                                        FROM dbsls.t_ar_ink_master a 
                                        LEFT JOIN 
                                                (SELECT no_sales,customerid, sum( bayar_transfer + bayar_giro + bayar_tunai ) kredit 
                                                FROM dbsls.t_ar_ink_detail where tgl_transfer <=?
                                                GROUP BY no_sales ) b USING (no_sales) where a.tanggal<=? 
                                                GROUP BY no_sales)a1 
                                                inner join dbsls.m_customer b1 using (customerid) 
                                                WHERE saldo <>0
                                                GROUP BY no_sales,days_past_due
                                                )a 
                                     where group_id=? 
                                 group by no_sales )a 
                              where nilai<>0";
                         $query=$this->db->query($sql,(array($end,$end,$end,$id))); 
                        $i=1;
                        $sum=0;
                        if($query->num_rows()>0)
                        {
                            //$this->table->set_empty('');
                            $this->stable->set_template($this->tmpl);
                            
                            $this->stable->set_caption("<h1>".$this->getPelanggan($id)."</h1>");
                            $this->stable->set_heading('No','Nomor Faktur','NO DO','Tanggal','Tanggal J. Tempo','Nilai');
                            foreach ($query->result() as $value)
                            {
                                $js=array('onclick'=>"return confirm('Are you sure you want to delete?')");

                                $this->stable->add_row(
                                        $i++
                                        ,$value->nodokjdi
                                        ,$value->nodokacu
                                        //,$value->supp!='006'?$this->getSuppname($value->supp):"PT. ULTRA SAKTI"
                                        ,$value->tgldokjdi
                                        ,$value->tgl_jtempo
                                      
                                        ,'<div  style="text-align:right">'.number_format($value->nilai,2).'</div>'
                                        
                                        //,'<div style="text-align:center">'.anchor('trans/permit/delete_temp/'.$value->id,img($this->image_properties_del),$js).'</div>'
                                        );
                                        $sum+=$value->nilai;
                            }
                            $this->stable->set_foot(
                                        ''
                                        ,''
                                        ,''
                                        ,''
                                        ,'Total'
                                        ,'<div  style="text-align:right">'.number_format($sum,2).'</div>');
                            $this->output_table .= $this->stable->generate();
                            $this->output_table .= '<br />';
                            //$this->table->clear();
                            $query->free_result();
                            $this->output_table;
                            return $this->output_table;
                        }
                        else
                        {
                            return FALSE;
                        }                
            }break;
            case 'detail':
            {
                $start=substr($init,0,7).'-01';
                $end=$init;
                $bulan = substr($init,2,2).substr($init,5,2);
                switch($key)
                {
                    case 1://debit
                    {
                        //$sql='select supp,nama_lang,nodokjdi, nodokacu,date_format(tgldokjdi,"%y%m") bulan, date_format(tgldokjdi,"%d %M %Y") tgldokjdi,date_format(tgl_jtempo,"%d %M %Y")tgl_jtempo,(nildok-aknildok) nilai from pusat.mp where (nildok-aknildok)<>0 and tgldokjdi>"20'.substr($init,0,2).'-'.substr($init,-2).'-01" and grup_lang =? and bulan=? order by tgldokjdi';
                        $sql="select right(trim(no_polisi),8) nodokjdi,ref nodokacu, DATE_FORMAT(tanggal,'%d %M %Y') tgldokjdi,DATE_FORMAT(tgl_tempo,'%d %M %Y') tgl_jtempo,if(retur=1,dokument*-1,dokument)nilai from dbsls.t_ar_ink_master a inner join dbsls.m_customer b using(customerid) where group_id=? and date(tanggal)>=? and date(tanggal)<=?";
                        $query=$this->db->query($sql,(array($id,$start,$end)));       
                    }break;
                    case 2://kredit
                    {
                        //$sql='select supp,nama_lang,nodokjdi,nodokacu, date_format(tgldokjdi,"%y%m") bulan,date_format(tgldokjdi,"%d %M %Y") tgldokjdi,date_format(tgl_jtempo,"%d %M %Y")tgl_jtempo,(nldokacu) nilai from pusat.mp where grup_lang =? and bulan=? and nldokacu!=0 order by tgldokjdi';
                        $sql="select right(trim(no_polisi),8) nodokjdi,ref nodokacu, DATE_FORMAT(a.tanggal,'%d %M %Y') tgldokjdi,DATE_FORMAT(a.tgl_tempo,'%d %M %Y') tgl_jtempo,sum(bayar_transfer+bayar_giro+bayar_tunai) nilai 
                        from dbsls.t_ar_ink_master a inner join dbsls.m_customer b using(customerid) 
                        inner join dbsls.t_ar_ink_detail c using(no_sales)
                        where group_id=? and c.tgl_transfer>=? and tgl_transfer<=? 
                        group by no_sales";
                        $query=$this->db->query($sql,(array($id,$start,$end)));       
                    }break;
                    case 3://current
                    {
                        //$sql='select supp,nama_lang,nodokjdi,nodokacu, date_format(tgldokjdi,"%y%m") bulan,date_format(tgldokjdi,"%d %M %Y") tgldokjdi,date_format(tgl_jtempo,"%d %M %Y")tgl_jtempo,(nildok-aknildok-nldokacu) nilai from pusat.mp where (nildok-aknildok-nldokacu)<>0 and grup_lang =? and tgl_jtempo>? and bulan=? order by tgldokjdi';
                        $sql="select * from(
                                select right(trim(no_polisi),8) nodokjdi,ref nodokacu,date_format(tgl_tempo,'%d %M %Y') tgl_jtempo,date_format(tanggal,'%d %M %Y') tgldokjdi,
                                SUM(IF(days_past_due < 0, amount_due, 0)) nilai
                                FROM(
                                    SELECT no_polisi,ref,tgl_tempo,tanggal,no_sales,group_id, group_descr, datediff(?,date_format(tgl_tempo,'%Y-%m-%d')) days_past_due, sum(saldo) amount_due 
                                    FROM(
                                        SELECT no_polisi,ref,tanggal,no_sales ,a.customerid ,tgl_tempo ,( IF ( a.retur = 1, a.dokument *- 1, a.dokument )) - IF (isnull(kredit), 0, kredit) saldo
                                        FROM dbsls.t_ar_ink_master a 
                                        LEFT JOIN 
                                                (SELECT no_sales,customerid, sum( bayar_transfer + bayar_giro + bayar_tunai ) kredit 
                                                FROM dbsls.t_ar_ink_detail where date_format(tgl_transfer,'%Y-%m-%d') <=?
                                                GROUP BY no_sales ) b USING (no_sales) where date_format(a.tanggal,'%Y-%m-%d')<=? 
                                                GROUP BY no_sales)a1 
                                                inner join dbsls.m_customer b1 using (customerid) 
                                                WHERE saldo <>0
                                                GROUP BY no_sales,days_past_due
                                                )a 
                                     where group_id=? 
                                 group by no_sales )a 
                              where nilai<>0";
                        $query=$this->db->query($sql,(array($end,$end,$end,$id)));       
                    }break;
                    case 4://duedate
                    {
                        //$sql='select supp,nama_lang,nodokjdi,nodokacu, date_format(tgldokjdi,"%y%m") bulan,date_format(tgldokjdi,"%d %M %Y") tgldokjdi,date_format(tgl_jtempo,"%d %M %Y")tgl_jtempo,(nildok-aknildok-nldokacu) nilai from pusat.mp where (nildok-aknildok-nldokacu)<>0 and grup_lang =? and tgl_jtempo=? and bulan=? order by tgldokjdi';
                        $sql="select * from(
                                select right(trim(no_polisi),8) nodokjdi,ref nodokacu,date_format(tgl_tempo,'%d %M %Y') tgl_jtempo,date_format(tanggal,'%d %M %Y') tgldokjdi,
                                SUM(IF(days_past_due = 0, amount_due, 0)) nilai
                                FROM(
                                    SELECT no_polisi,ref,tgl_tempo,tanggal,no_sales,group_id, group_descr, datediff(?,tgl_tempo) days_past_due, sum(saldo) amount_due 
                                    FROM(
                                        SELECT no_polisi,ref,tanggal,no_sales ,a.customerid ,tgl_tempo ,( IF ( a.retur = 1, a.dokument *- 1, a.dokument )) - IF (isnull(kredit), 0, kredit) saldo
                                        FROM dbsls.t_ar_ink_master a 
                                        LEFT JOIN 
                                                (SELECT no_sales,customerid, sum( bayar_transfer + bayar_giro + bayar_tunai ) kredit 
                                                FROM dbsls.t_ar_ink_detail where tgl_transfer <=?
                                                GROUP BY no_sales ) b USING (no_sales) where a.tanggal<=? 
                                                GROUP BY no_sales)a1 
                                                inner join dbsls.m_customer b1 using (customerid) 
                                                WHERE saldo <>0
                                                GROUP BY no_sales,days_past_due
                                                )a 
                                     where group_id=? 
                                 group by no_sales )a 
                              where nilai<>0";
                        $query=$this->db->query($sql,(array($end,$end,$end,$id)));       
                    }break;
                    case 51://r1
                    {
                        //$sql='select supp,nama_lang,nodokjdi,nodokacu, date_format(tgldokjdi,"%y%m") bulan,date_format(tgldokjdi,"%d %M %Y") tgldokjdi,date_format(tgl_jtempo,"%d %M %Y")tgl_jtempo,(nildok-aknildok-nldokacu) nilai from pusat.mp where (nildok-aknildok-nldokacu)<>0 and grup_lang =? and tgl_jtempo<? and tgl_jtempo>=SUBDATE(?,interval 7 day) and bulan=? order by tgldokjdi';
                        $sql="select * from(
                                select right(trim(no_polisi),8) nodokjdi,ref nodokacu,date_format(tgl_tempo,'%d %M %Y') tgl_jtempo,date_format(tanggal,'%d %M %Y') tgldokjdi,
                                SUM(IF(days_past_due BETWEEN 1 AND 7, amount_due, 0)) nilai
                                FROM(
                                    SELECT no_polisi,ref,tgl_tempo,tanggal,no_sales,group_id, group_descr, datediff(?,tgl_tempo) days_past_due, sum(saldo) amount_due 
                                    FROM(
                                        SELECT no_polisi,ref,tanggal,no_sales ,a.customerid ,tgl_tempo ,( IF ( a.retur = 1, a.dokument *- 1, a.dokument )) - IF (isnull(kredit), 0, kredit) saldo
                                        FROM dbsls.t_ar_ink_master a 
                                        LEFT JOIN 
                                                (SELECT no_sales,customerid, sum( bayar_transfer + bayar_giro + bayar_tunai ) kredit 
                                                FROM dbsls.t_ar_ink_detail where tgl_transfer <=?
                                                GROUP BY no_sales ) b USING (no_sales) where a.tanggal<=? 
                                                GROUP BY no_sales)a1 
                                                inner join dbsls.m_customer b1 using (customerid) 
                                                WHERE saldo <>0
                                                GROUP BY no_sales,days_past_due
                                                )a 
                                     where group_id=? 
                                 group by no_sales )a 
                              where nilai<>0";
                         $query=$this->db->query($sql,(array($end,$end,$end,$id)));       
                    }break;
                    case 52://r1
                    {
                        //$sql='select supp,nama_lang,nodokjdi,nodokacu, date_format(tgldokjdi,"%y%m") bulan,date_format(tgldokjdi,"%d %M %Y") tgldokjdi,date_format(tgl_jtempo,"%d %M %Y")tgl_jtempo,(nildok-aknildok-nldokacu) nilai from pusat.mp where (nildok-aknildok-nldokacu)<>0 and grup_lang =? and tgl_jtempo<? and tgl_jtempo>=SUBDATE(?,interval 30 day) and bulan=? order by tgldokjdi';
                        $sql="select * from(
                                select right(trim(no_polisi),8) nodokjdi,ref nodokacu,date_format(tgl_tempo,'%d %M %Y') tgl_jtempo,date_format(tanggal,'%d %M %Y') tgldokjdi,
                                SUM(IF(days_past_due BETWEEN 1 AND 30, amount_due, 0)) nilai
                                FROM(
                                    SELECT no_polisi,ref,tgl_tempo,tanggal,no_sales,group_id, group_descr, datediff(?,tgl_tempo) days_past_due, sum(saldo) amount_due 
                                    FROM(
                                        SELECT no_polisi,ref,tanggal,no_sales ,a.customerid ,tgl_tempo ,( IF ( a.retur = 1, a.dokument *- 1, a.dokument )) - IF (isnull(kredit), 0, kredit) saldo
                                        FROM dbsls.t_ar_ink_master a 
                                        LEFT JOIN 
                                                (SELECT no_sales,customerid, sum( bayar_transfer + bayar_giro + bayar_tunai ) kredit 
                                                FROM dbsls.t_ar_ink_detail where tgl_transfer <=?
                                                GROUP BY no_sales ) b USING (no_sales) where a.tanggal<=? 
                                                GROUP BY no_sales)a1 
                                                inner join dbsls.m_customer b1 using (customerid) 
                                                WHERE saldo <>0
                                                GROUP BY no_sales,days_past_due
                                                )a 
                                     where group_id=? 
                                 group by no_sales )a 
                              where nilai<>0";
                         $query=$this->db->query($sql,(array($end,$end,$end,$id)));       
                    }break;
                    case 61://r2
                    {
                        //$sql='select supp,nama_lang,nodokjdi,nodokacu, date_format(tgldokjdi,"%y%m") bulan,date_format(tgldokjdi,"%d %M %Y") tgldokjdi,date_format(tgl_jtempo,"%d %M %Y")tgl_jtempo,(nildok-aknildok-nldokacu) nilai from pusat.mp where (nildok-aknildok-nldokacu)<>0 and grup_lang =? and  tgl_jtempo<SUBDATE(?,interval 7 day) and tgl_jtempo>=SUBDATE(?,interval 14 day) and bulan=? order by tgldokjdi';
                        $sql="select * from(
                                select right(trim(no_polisi),8) nodokjdi,ref nodokacu,date_format(tgl_tempo,'%d %M %Y') tgl_jtempo,date_format(tanggal,'%d %M %Y') tgldokjdi,
                                SUM(IF(days_past_due BETWEEN 8 AND 14, amount_due, 0)) nilai
                                FROM(
                                    SELECT no_polisi,ref,tgl_tempo,tanggal,no_sales,group_id, group_descr, datediff(?,tgl_tempo) days_past_due, sum(saldo) amount_due 
                                    FROM(
                                        SELECT no_polisi,ref,tanggal,no_sales ,a.customerid ,tgl_tempo ,( IF ( a.retur = 1, a.dokument *- 1, a.dokument )) - IF (isnull(kredit), 0, kredit) saldo
                                        FROM dbsls.t_ar_ink_master a 
                                        LEFT JOIN 
                                                (SELECT no_sales,customerid, sum( bayar_transfer + bayar_giro + bayar_tunai ) kredit 
                                                FROM dbsls.t_ar_ink_detail where tgl_transfer <=?
                                                GROUP BY no_sales ) b USING (no_sales) where a.tanggal<=? 
                                                GROUP BY no_sales)a1 
                                                inner join dbsls.m_customer b1 using (customerid) 
                                                WHERE saldo <>0
                                                GROUP BY no_sales,days_past_due
                                                )a 
                                     where group_id=? 
                                 group by no_sales )a 
                              where nilai<>0";
                        $query=$this->db->query($sql,(array($end,$end,$end,$id)));       
                    }break;
                    case 62://r2
                    {
                        //$sql='select supp,nama_lang,nodokjdi,nodokacu, date_format(tgldokjdi,"%y%m") bulan,date_format(tgldokjdi,"%d %M %Y") tgldokjdi,date_format(tgl_jtempo,"%d %M %Y")tgl_jtempo,(nildok-aknildok-nldokacu) nilai from pusat.mp where (nildok-aknildok-nldokacu)<>0 and grup_lang =? and  tgl_jtempo<SUBDATE(?,interval 30 day) and tgl_jtempo>=SUBDATE(?,interval 60 day) and bulan=? order by tgldokjdi';
                        $sql="select * from(
                                select right(trim(no_polisi),8) nodokjdi,ref nodokacu,date_format(tgl_tempo,'%d %M %Y') tgl_jtempo,date_format(tanggal,'%d %M %Y') tgldokjdi,
                                SUM(IF(days_past_due BETWEEN 31 AND 60, amount_due, 0)) nilai
                                FROM(
                                    SELECT no_polisi,ref,tgl_tempo,tanggal,no_sales,group_id, group_descr, datediff(?,tgl_tempo) days_past_due, sum(saldo) amount_due 
                                    FROM(
                                        SELECT no_polisi,ref,tanggal,no_sales ,a.customerid ,tgl_tempo ,( IF ( a.retur = 1, a.dokument *- 1, a.dokument )) - IF (isnull(kredit), 0, kredit) saldo
                                        FROM dbsls.t_ar_ink_master a 
                                        LEFT JOIN 
                                                (SELECT no_sales,customerid, sum( bayar_transfer + bayar_giro + bayar_tunai ) kredit 
                                                FROM dbsls.t_ar_ink_detail where tgl_transfer <=?
                                                GROUP BY no_sales ) b USING (no_sales) where a.tanggal<=? 
                                                GROUP BY no_sales)a1 
                                                inner join dbsls.m_customer b1 using (customerid) 
                                                WHERE saldo <>0
                                                GROUP BY no_sales,days_past_due
                                                )a 
                                     where group_id=? 
                                 group by no_sales )a 
                              where nilai<>0";
                        $query=$this->db->query($sql,(array($end,$end,$end,$id)));       
                    }break;
                    case 71://r3
                    {
                        //$sql='select supp,nama_lang,nodokjdi,nodokacu, date_format(tgldokjdi,"%y%m") bulan,date_format(tgldokjdi,"%d %M %Y") tgldokjdi,date_format(tgl_jtempo,"%d %M %Y")tgl_jtempo,(nildok-aknildok-nldokacu) nilai from pusat.mp where (nildok-aknildok-nldokacu)<>0 and grup_lang =? and  tgl_jtempo<SUBDATE(?,interval 14 day) and tgl_jtempo>=SUBDATE(?,interval 30 day) and bulan=? order by tgldokjdi';
                        $sql="select * from(
                                select right(trim(no_polisi),8) nodokjdi,ref nodokacu,date_format(tgl_tempo,'%d %M %Y') tgl_jtempo,date_format(tanggal,'%d %M %Y') tgldokjdi,
                                SUM(IF(days_past_due BETWEEN 15 AND 30, amount_due, 0)) nilai
                                FROM(
                                    SELECT no_polisi,ref,tgl_tempo,tanggal,no_sales,group_id, group_descr, datediff(?,tgl_tempo) days_past_due, sum(saldo) amount_due 
                                    FROM(
                                        SELECT no_polisi,ref,tanggal,no_sales ,a.customerid ,tgl_tempo ,( IF ( a.retur = 1, a.dokument *- 1, a.dokument )) - IF (isnull(kredit), 0, kredit) saldo
                                        FROM dbsls.t_ar_ink_master a 
                                        LEFT JOIN 
                                                (SELECT no_sales,customerid, sum( bayar_transfer + bayar_giro + bayar_tunai ) kredit 
                                                FROM dbsls.t_ar_ink_detail where tgl_transfer <=?
                                                GROUP BY no_sales ) b USING (no_sales) where a.tanggal<=? 
                                                GROUP BY no_sales)a1 
                                                inner join dbsls.m_customer b1 using (customerid) 
                                                WHERE saldo <>0
                                                GROUP BY no_sales,days_past_due
                                                )a 
                                     where group_id=? 
                                 group by no_sales )a 
                              where nilai<>0";
                        $query=$this->db->query($sql,(array($end,$end,$end,$id)));       
                    }break;
                    case 72://r3
                    {
                        //$sql='select supp,nama_lang,nodokjdi,nodokacu, date_format(tgldokjdi,"%y%m") bulan,date_format(tgldokjdi,"%d %M %Y") tgldokjdi,date_format(tgl_jtempo,"%d %M %Y")tgl_jtempo,(nildok-aknildok-nldokacu) nilai from pusat.mp where (nildok-aknildok-nldokacu)<>0 and grup_lang =? and  tgl_jtempo<SUBDATE(?,interval 60 day) and tgl_jtempo>=SUBDATE(?,interval 90 day) and bulan=? order by tgldokjdi';
                        $sql="select * from(
                                select right(trim(no_polisi),8) nodokjdi,ref nodokacu,date_format(tgl_tempo,'%d %M %Y') tgl_jtempo,date_format(tanggal,'%d %M %Y') tgldokjdi,
                                SUM(IF(days_past_due BETWEEN 61 AND 90, amount_due, 0)) nilai
                                FROM(
                                    SELECT no_polisi,ref,tgl_tempo,tanggal,no_sales,group_id, group_descr, datediff(?,tgl_tempo) days_past_due, sum(saldo) amount_due 
                                    FROM(
                                        SELECT no_polisi,ref,tanggal,no_sales ,a.customerid ,tgl_tempo ,( IF ( a.retur = 1, a.dokument *- 1, a.dokument )) - IF (isnull(kredit), 0, kredit) saldo
                                        FROM dbsls.t_ar_ink_master a 
                                        LEFT JOIN 
                                                (SELECT no_sales,customerid, sum( bayar_transfer + bayar_giro + bayar_tunai ) kredit 
                                                FROM dbsls.t_ar_ink_detail where tgl_transfer <=?
                                                GROUP BY no_sales ) b USING (no_sales) where a.tanggal<=? 
                                                GROUP BY no_sales)a1 
                                                inner join dbsls.m_customer b1 using (customerid) 
                                                WHERE saldo <>0
                                                GROUP BY no_sales,days_past_due
                                                )a 
                                     where group_id=? 
                                 group by no_sales )a 
                              where nilai<>0";
                        $query=$this->db->query($sql,(array($end,$end,$end,$id)));       
                    }break;
                    case 81://r4
                    {
                        //$sql='select supp,nama_lang,nodokjdi,nodokacu, date_format(tgldokjdi,"%y%m") bulan,date_format(tgldokjdi,"%d %M %Y") tgldokjdi,date_format(tgl_jtempo,"%d %M %Y")tgl_jtempo,(nildok-aknildok-nldokacu) nilai from pusat.mp where (nildok-aknildok-nldokacu)!=0 and grup_lang =? and  tgl_jtempo<SUBDATE(?,interval 30 day) and bulan=? order by tgldokjdi';
                        $sql="select * from(
                                select right(trim(no_polisi),8) nodokjdi,ref nodokacu,date_format(tgl_tempo,'%d %M %Y') tgl_jtempo,date_format(tanggal,'%d %M %Y') tgldokjdi,
                                SUM(IF(days_past_due > 30, amount_due, 0)) nilai
                                FROM(
                                    SELECT no_polisi,ref,tgl_tempo,tanggal,no_sales,group_id, group_descr, datediff(?,tgl_tempo) days_past_due, sum(saldo) amount_due 
                                    FROM(
                                        SELECT no_polisi,ref,tanggal,no_sales ,a.customerid ,tgl_tempo ,( IF ( a.retur = 1, a.dokument *- 1, a.dokument )) - IF (isnull(kredit), 0, kredit) saldo
                                        FROM dbsls.t_ar_ink_master a 
                                        LEFT JOIN 
                                                (SELECT no_sales,customerid, sum( bayar_transfer + bayar_giro + bayar_tunai ) kredit 
                                                FROM dbsls.t_ar_ink_detail where tgl_transfer <=?
                                                GROUP BY no_sales ) b USING (no_sales) where a.tanggal<=? 
                                                GROUP BY no_sales)a1 
                                                inner join dbsls.m_customer b1 using (customerid) 
                                                WHERE saldo <>0
                                                GROUP BY no_sales,days_past_due
                                                )a 
                                     where group_id=? 
                                 group by no_sales )a 
                              where nilai<>0";
                        $query=$this->db->query($sql,(array($end,$end,$end,$id)));       
                    }break;
                    case 82://r4
                    {
                        //$sql='select supp,nama_lang,nodokjdi,nodokacu, date_format(tgldokjdi,"%y%m") bulan,date_format(tgldokjdi,"%d %M %Y") tgldokjdi,date_format(tgl_jtempo,"%d %M %Y")tgl_jtempo,(nildok-aknildok-nldokacu) nilai from pusat.mp where (nildok-aknildok-nldokacu)<>0 and grup_lang =? and  tgl_jtempo<SUBDATE(?,interval 90 day) and bulan=? order by tgldokjdi';
                        $sql="select * from(
                                select right(trim(no_polisi),8) nodokjdi,ref nodokacu,date_format(tgl_tempo,'%d %M %Y') tgl_jtempo,date_format(tanggal,'%d %M %Y') tgldokjdi,
                                SUM(IF(days_past_due > 90, amount_due, 0)) nilai
                                FROM(
                                    SELECT no_polisi,ref,tgl_tempo,tanggal,no_sales,group_id, group_descr, datediff(?,tgl_tempo) days_past_due, sum(saldo) amount_due 
                                    FROM(
                                        SELECT no_polisi,ref,tanggal,no_sales ,a.customerid ,tgl_tempo ,( IF ( a.retur = 1, a.dokument *- 1, a.dokument )) - IF (isnull(kredit), 0, kredit) saldo
                                        FROM dbsls.t_ar_ink_master a 
                                        LEFT JOIN 
                                                (SELECT no_sales,customerid, sum( bayar_transfer + bayar_giro + bayar_tunai ) kredit 
                                                FROM dbsls.t_ar_ink_detail where tgl_transfer <=?
                                                GROUP BY no_sales ) b USING (no_sales) where a.tanggal<=? 
                                                GROUP BY no_sales)a1 
                                                inner join dbsls.m_customer b1 using (customerid) 
                                                WHERE saldo <>0
                                                GROUP BY no_sales,days_past_due
                                                )a 
                                     where group_id=? 
                                 group by no_sales )a 
                              where nilai<>0";
                        $query=$this->db->query($sql,(array($end,$end,$end,$id)));       
                    }break;
                }
                        //$query=$this->db->query($sql,(array($id,$init)));   
                        $i=1;
                        $sum=0;
                        if($query->num_rows()>0)
                        {
                            //$this->table->set_empty('');
                            $this->stable->set_template($this->tmpl);
                            
                            $this->stable->set_caption("<h1>".$this->getPelanggan($id)."</h1>");
                            $this->stable->set_heading('No','Nomor Faktur','NO DO','Tanggal','Tanggal J. Tempo','Nilai');
                            foreach ($query->result() as $value)
                            {
                                $js=array('onclick'=>"return confirm('Are you sure you want to delete?')");

                                $this->stable->add_row(
                                        $i++
                                        ,anchor_popup('trans/piutang/faktur/'.$value->nodokjdi.'/'.$bulan,$value->nodokjdi)
                                        ,$value->nodokacu
                                        //,$value->supp!='006'?$this->getSuppname($value->supp):"PT. ULTRA SAKTI"
                                        ,$value->tgldokjdi
                                        ,$value->tgl_jtempo
                                      
                                        ,'<div  style="text-align:right">'.number_format($value->nilai,2).'</div>'
                                        
                                        //,'<div style="text-align:center">'.anchor('trans/permit/delete_temp/'.$value->id,img($this->image_properties_del),$js).'</div>'
                                        );
                                        $sum+=$value->nilai;
                            }
                            $this->stable->set_foot(
                                        ''
                                        ,''
                                        ,''
                                        ,''
                                        ,'Total'
                                        ,'<div  style="text-align:right">'.number_format($sum,2).'</div>');
                            $this->output_table .= $this->stable->generate();
                            $this->output_table .= '<br />';
                            //$this->table->clear();
                            $query->free_result();
                            $this->output_table;
                            return $this->output_table;
                        }
                        else
                        {
                            return FALSE;
                        }                
            }break;
            case 'show':
            {
                $sql='';
                $tanggal=$this->input->post('tanggal');
                $awal=substr($tanggal,0,7).'-01';
                $year=substr($tanggal,2,2);
                $month=substr($tanggal,5,2);
                $bulan=$year.$month;
                $filename='';
                $range=$this->input->post('range');
                switch($range)
                {
                    case '1':
                    {
                         /*$sql="select grup_lang,grup_nama,lang.kode_kota,saldoawal,debit,kredit,saldoakhir,current,duedate,r1 ,r2,r3 ,r4  from pusat.mp
                                left join
                                (select GRUP_LANG,round(sum(nildok-aknildok),2)saldoawal from pusat.mp  where TGLDOKJDI<'".$awal."' and bulan=".$bulan." group by GRUP_LANG)
                                a0 using(grup_lang)
                                left join
                                (select GRUP_LANG,round(sum(NILDOK-AKNILDOK),2)debit from pusat.mp where tgldokjdi>='".$awal."' and bulan=".$bulan." group by GRUP_LANG)
                                a  using(grup_lang)
                                left join
                                (select grup_lang,round(sum(nildok-aknildok-nldokacu),2) saldoakhir,round(sum(nldokacu),2) kredit from pusat.mp where bulan=".$bulan." group by grup_lang)
                                b using(grup_lang)
                                left join
                                (select grup_lang,round(sum(nildok-aknildok-nldokacu),2) current from pusat.mp where TGL_JTEMPO>'".$tanggal."' and bulan=".$bulan." group by grup_lang)
                                c using(grup_lang)
                                left join
                                (select grup_lang,round(sum(nildok-aknildok-nldokacu),2) duedate from pusat.mp where TGL_JTEMPO='".$tanggal."' and bulan=".$bulan." group by grup_lang)
                                d using(grup_lang)
                                left join
                                (select grup_lang,round(sum(nildok-aknildok-nldokacu),2) r1 from pusat.mp where TGL_JTEMPO<'".$tanggal."' and TGL_JTEMPO>=SUBDATE('".$tanggal."',interval 7 day) and bulan=".$bulan." group by grup_lang)
                                e using(grup_lang)
                                left join
                                (select grup_lang,round(sum(nildok-aknildok-nldokacu),2) r2 from pusat.mp where TGL_JTEMPO<SUBDATE('".$tanggal."',interval 7 day) and TGL_JTEMPO>=SUBDATE('".$tanggal."',interval 14 day)  and bulan=".$bulan." group by grup_lang)
                                f using(grup_lang)
                                left join
                                (select grup_lang,round(sum(nildok-aknildok-nldokacu),2) r3 from pusat.mp where TGL_JTEMPO<SUBDATE('".$tanggal."',interval 14 day) and TGL_JTEMPO>=SUBDATE('".$tanggal."',interval 30 day) and bulan=".$bulan." group by grup_lang)
                                g using(grup_lang)
                                left join
                                (select grup_lang,round(sum(nildok-aknildok-nldokacu),2) r4 from pusat.mp where TGL_JTEMPO<SUBDATE('".$tanggal."',interval 30 day) and bulan=".$bulan." group by grup_lang)
                                h using(grup_lang)

                                inner join (select grup_lang,grup_nama,kode_kota from pusat.user ) lang using(grup_lang)
                                where saldoawal !=0
                                group by grup_lang order by grup_nama";*/
                        $filename='piutang_minggu_'.$tanggal.'_'.date('YmdHis');
                        $sql="  select grup_lang,kode_comp,group_descr grup_nama,if(isnull(saldoawal),0,saldoawal)saldoawal,debit,kredit,(if(isnull(saldoawal),0,saldoawal)+debit-kredit) saldoakhir, current, duedate, r1 ,r2 ,r3 ,r4 , total 
                                from(
                                   select z.group_id grup_lang,z.group_descr,debitprev-if(isnull(kreditprev),0,kreditprev) saldoawal,if(isnull(debit),0,debit) debit,if(isnull(kredit),0,kredit) kredit
                                            ,current,duedate, r1 ,r2 ,r3 ,r4 ,current+duedate+r1+r2+r3+r4 total
                                    from(

                                    select group_id, concat(group_descr,'') group_descr from dbsls.t_ar_ink_master a 
                        inner join dbsls.m_customer b using(customerid) group by group_id)z
                                                                    
                            left join 
                                    
                                    (select group_id, group_descr, sum(if(retur=1,dokument*-1,dokument)) debitprev from dbsls.t_ar_ink_master a 
                                    inner join dbsls.m_customer b using(customerid)where  tanggal<'".$awal."'
                                    group by group_id)a using(group_id)

                                    left join 

                                    (select group_id, group_descr, sum(bayar_transfer+bayar_giro+bayar_tunai) kreditprev from dbsls.t_ar_ink_detail a 
                                    inner join dbsls.m_customer b using(customerid)where  tgl_transfer<'".$awal."'
                                    group by group_id)b using(group_id)

                                    left join 

                                    (select group_id, group_descr, sum(if(retur=1,dokument*-1,dokument)) debit from dbsls.t_ar_ink_master a 
                                    inner join dbsls.m_customer b using(customerid)where  date(tanggal)>='".$awal."' and date(tanggal)<='".$tanggal."'
                                    group by group_id)c using (group_id)

                                    left join 

                                    (select group_id, group_descr, if(isnull(sum(bayar_transfer+bayar_giro+bayar_tunai)),0,sum(bayar_transfer+bayar_giro+bayar_tunai)) kredit from dbsls.t_ar_ink_detail a 
                                    inner join dbsls.m_customer b using(customerid)where  date(tgl_transfer)>='".$awal."' and date(tgl_transfer)<='".$tanggal."'
                                    group by group_id)d using(group_id)

                                    left join
                                    (select group_id, group_descr,
                                    SUM(IF(days_past_due < 0, amount_due, 0)) current,
                                    SUM(IF(days_past_due = 0, amount_due, 0)) duedate,
                                    SUM(IF(days_past_due BETWEEN 1 AND 7, amount_due, 0)) r1,
                                    SUM(IF(days_past_due BETWEEN 8 AND 14, amount_due, 0)) r2,
                                    SUM(IF(days_past_due BETWEEN 15 AND 30, amount_due, 0)) r3,
                                    SUM(IF(days_past_due > 30, amount_due, 0))r4
                                    FROM(
                                            SELECT group_id, group_descr, datediff('".$tanggal."',tgl_tempo) days_past_due, sum(saldo) amount_due 
                                            FROM(
                                                    SELECT no_sales ,a.customerid ,tgl_tempo ,( IF ( a.retur = 1, a.dokument *- 1, a.dokument )) - IF (isnull(kredit), 0, kredit) saldo
                                                    FROM dbsls.t_ar_ink_master a 
                                                    LEFT JOIN 
                                                            (SELECT no_sales,customerid, sum( bayar_transfer + bayar_giro + bayar_tunai ) kredit 
                                                            FROM dbsls.t_ar_ink_detail where tgl_transfer <='".$tanggal."'
                                                            GROUP BY no_sales ) b USING (no_sales) where a.tanggal<='".$tanggal."' 
                                                            GROUP BY no_sales)a1 
                                                            inner join dbsls.m_customer b1 using (customerid) 
                                                            WHERE saldo <>0
                                                            GROUP BY group_id,days_past_due
                                                            )a group by group_id 
                                    )e using (group_id)
                                )a
                                LEFT JOIN mpm.tabcomp tab ON a.grup_lang = tab.kode_lang
                                where saldoawal+debit-kredit<>0 group by a.grup_lang
                                order by a.group_descr
                            ";
                    }
                        break;
                    case '2':
                         /*$sql="select grup_lang,grup_nama,lang.kode_kota,saldoawal,debit,kredit,saldoakhir,current,duedate,r1,r2,r3,r4 from pusat.mp
                                left join
                                (select GRUP_LANG,round(sum(nildok-aknildok),2)saldoawal from pusat.mp  where TGLDOKJDI<'".$awal."' and bulan=".$bulan." group by GRUP_LANG)
                                a0 using(grup_lang)
                                left join
                                (select GRUP_LANG,round(sum(NILDOK-AKNILDOK),2)debit from pusat.mp where tgldokjdi>='".$awal."' and bulan=".$bulan." group by GRUP_LANG)
                                a  using(grup_lang)
                                left join
                                (select grup_lang,round(sum(nildok-aknildok-nldokacu),2) saldoakhir,round(sum(nldokacu),2)kredit from pusat.mp where bulan=".$bulan." group by grup_lang)
                                b using(grup_lang)
                                left join
                                (select grup_lang,round(sum(nildok-aknildok-nldokacu),2) current from pusat.mp where TGL_JTEMPO>'".$tanggal."' and bulan=".$bulan." group by grup_lang)
                                c using(grup_lang)
                                left join
                                (select grup_lang,round(sum(nildok-aknildok-nldokacu),2) duedate from pusat.mp where TGL_JTEMPO='".$tanggal."' and bulan=".$bulan." group by grup_lang)
                                d using(grup_lang)
                                left join
                                (select grup_lang,round(sum(nildok-aknildok-nldokacu),2) r1 from pusat.mp where TGL_JTEMPO<'".$tanggal."' and TGL_JTEMPO>=SUBDATE('".$tanggal."',interval 30 day) and bulan=".$bulan." group by grup_lang)
                                e using(grup_lang)
                                left join
                                (select grup_lang,round(sum(nildok-aknildok-nldokacu),2) r2 from pusat.mp where TGL_JTEMPO<SUBDATE('".$tanggal."',interval 30 day) and TGL_JTEMPO>=SUBDATE('".$tanggal."',interval 60 day) and bulan=".$bulan." group by grup_lang)
                                f using(grup_lang)
                                left join
                                (select grup_lang,round(sum(nildok-aknildok-nldokacu),2) r3 from pusat.mp where TGL_JTEMPO<SUBDATE('".$tanggal."',interval 60 day) and TGL_JTEMPO>=SUBDATE('".$tanggal."',interval 90 day) and bulan=".$bulan." group by grup_lang)
                                g using(grup_lang)
                                left join
                                (select grup_lang,round(sum(nildok-aknildok-nldokacu),2) r4 from pusat.mp where TGL_JTEMPO<SUBDATE('".$tanggal."',interval 90 day) and bulan=".$bulan." group by grup_lang)
                                h using(grup_lang)
                                inner join (select grup_lang,grup_nama,kode_kota from pusat.user ) lang using(grup_lang)
                                group by grup_lang order by grup_nama";*/
                                $filename='piutang_bulan_'.$tanggal.'_'.date('YmdHis');
                                
                         $sql=" select kode_comp,grup_lang,group_descr grup_nama,saldoawal,debit,kredit,(saldoawal+debit-kredit) saldoakhir, current, duedate, r1 ,r2 ,r3 ,r4 , total 
                                from(
                                    select z.group_id grup_lang,z.group_descr,debitprev-if(isnull(kreditprev),0,kreditprev) saldoawal,if(isnull(debit),0,debit) debit,if(isnull(kredit),0,kredit) kredit
                                            ,current,duedate, r1 ,r2 ,r3 ,r4 ,current+duedate+r1+r2+r3+r4 total
                                    from(

                                    select group_id, concat(group_descr,'') group_descr from dbsls.t_ar_ink_master a 
                        inner join dbsls.m_customer b using(customerid) group by group_id)z
                                                                    
                            left join 
                                    
                                    (select group_id, group_descr, sum(if(retur=1,dokument*-1,dokument)) debitprev from dbsls.t_ar_ink_master a 
                                    inner join dbsls.m_customer b using(customerid)where  tanggal<'".$awal."'
                                    group by group_id)a using(group_id)

                                    left join 

                                    (select group_id, group_descr, sum(bayar_transfer+bayar_giro+bayar_tunai) kreditprev from dbsls.t_ar_ink_detail a 
                                    inner join dbsls.m_customer b using(customerid)where  tgl_transfer<'".$awal."'
                                    group by group_id)b using(group_id)

                                    left join 

                                    (select group_id, group_descr, sum(if(retur=1,dokument*-1,dokument)) debit from dbsls.t_ar_ink_master a 
                                    inner join dbsls.m_customer b using(customerid)where  tanggal>='".$awal."' and tanggal<='".$tanggal."'
                                    group by group_id)c using (group_id)

                                    left join 

                                    (select group_id, group_descr, if(isnull(sum(bayar_transfer+bayar_giro+bayar_tunai)),0,sum(bayar_transfer+bayar_giro+bayar_tunai)) kredit from dbsls.t_ar_ink_detail a 
                                    inner join dbsls.m_customer b using(customerid)where  tgl_transfer>='".$awal."' and tgl_transfer<='".$tanggal."'
                                    group by group_id)d using(group_id)

                                    left join
                                    (select group_id, group_descr,
                                    SUM(IF(days_past_due < 0, amount_due, 0)) current,
                                    SUM(IF(days_past_due = 0, amount_due, 0)) duedate,
                                    SUM(IF(days_past_due BETWEEN 1 AND 30, amount_due, 0)) r1,
                                    SUM(IF(days_past_due BETWEEN 31 AND 60, amount_due, 0)) r2,
                                    SUM(IF(days_past_due BETWEEN 61 AND 90, amount_due, 0)) r3,
                                    SUM(IF(days_past_due > 90, amount_due, 0))r4
                                    FROM(
                                            SELECT group_id, group_descr, datediff('".$tanggal."',tgl_tempo) days_past_due, sum(saldo) amount_due 
                                            FROM(
                                                    SELECT no_sales ,a.customerid ,tgl_tempo ,( IF ( a.retur = 1, a.dokument *- 1, a.dokument )) - IF (isnull(kredit), 0, kredit) saldo
                                                    FROM dbsls.t_ar_ink_master a 
                                                    LEFT JOIN 
                                                            (SELECT no_sales,customerid, sum( bayar_transfer + bayar_giro + bayar_tunai ) kredit 
                                                            FROM dbsls.t_ar_ink_detail where tgl_transfer <='".$tanggal."'
                                                            GROUP BY no_sales ) b USING (no_sales) where a.tanggal<='".$tanggal."' 
                                                            GROUP BY no_sales)a1 
                                                            inner join dbsls.m_customer b1 using (customerid) 
                                                            WHERE saldo <>0
                                                            GROUP BY group_id,days_past_due
                                                            )a group by group_id 
                                    )e using (group_id)
                                )a
                                 LEFT JOIN mpm.tabcomp tab ON a.grup_lang = tab.kode_lang
                                where saldoawal+debit-kredit<>0 group by a.grup_lang
                                order by a.group_descr
                            ";
                        break;
                }
                switch($this->input->post('format'))
                {
                    case 1:
                    {
                        $query=$this->db->query($sql);
                        if($query->num_rows()>0)
                        {
                            $this->load->library('table');
                            //$this->table->set_empty('0');
                            $this->stable->set_template($this->tmpl_piutang);
                            //$this->stable->set_caption('PIUTANG');
                            switch($this->input->post('range'))
                            {
                                case 1:
                                $this->stable->set_heading('Customer','Saldo Awal','Debit','Kredit','Saldo Akhir','Current','Duedate','1-7','8-14','15-30','>30','Total Over Due');
                                break;
                                case 2:
                                $this->stable->set_heading('Customer','Saldo Awal','Debit','Kredit','Saldo Akhir','Current','Duedate','1-30','31-60','61-90','>90','Total Over Due');
                                break;
                            }
                            $footer=array(
                                'saldoawal'=>0
                                ,'debit'=>0
                                ,'kredit'=>0
                                ,'saldoakhir'=>0
                                ,'current'=>0
                                ,'duedate'=>0
                                ,'r1'=>0
                                ,'r2'=>0
                                ,'r3'=>0
                                ,'r4'=>0
                                ,'total'=>0
                                );
                            foreach ($query->result() as $value)
                            {
                                //$js=array('onclick'=>"return confirm('Are you sure you want to delete?')");
                                //$total=$value->current+$value->duedate+$value->r1+$value->r2+$value->r3+$value->r4;
                                $total=$value->r1+$value->r2+$value->r3+$value->r4;
                                $this->stable->add_row(
                                        $value->grup_nama.($value->kode_comp<>""? ".$value->kode_comp.":"")
                                        ,'<div  style="text-align:right">'.number_format($value->saldoawal,2).'</div>'
                                        ,'<div  style="text-align:right">'.anchor_popup('trans/piutang/detail/1/'.$value->grup_lang.'/'.$tanggal,number_format($value->debit,2)).'</div>'
                                        ,'<div  style="text-align:right">'.anchor_popup('trans/piutang/detail/2/'.$value->grup_lang.'/'.$tanggal,number_format($value->kredit,2)).'</div>'
                                        ,'<div  style="text-align:right">'.number_format($value->saldoakhir,2).'</div>'
                                        ,'<div  style="text-align:right">'.anchor_popup('trans/piutang/detail/3/'.$value->grup_lang.'/'.$tanggal,number_format($value->current,2)).'</div>'
                                        ,'<div  style="text-align:right">'.anchor_popup('trans/piutang/detail/4/'.$value->grup_lang.'/'.$tanggal,number_format($value->duedate,2)).'</div>'
                                        ,'<div  style="text-align:right">'.anchor_popup('trans/piutang/detail/5'.$range.'/'.$value->grup_lang.'/'.$tanggal,number_format($value->r1,2)).'</div>'
                                        ,'<div  style="text-align:right">'.anchor_popup('trans/piutang/detail/6'.$range.'/'.$value->grup_lang.'/'.$tanggal,number_format($value->r2,2)).'</div>'
                                        ,'<div  style="text-align:right">'.anchor_popup('trans/piutang/detail/7'.$range.'/'.$value->grup_lang.'/'.$tanggal,number_format($value->r3,2)).'</div>'
                                        ,'<div  style="text-align:right">'.anchor_popup('trans/piutang/detail/8'.$range.'/'.$value->grup_lang.'/'.$tanggal,number_format($value->r4,2)).'</div>'
                                        ,'<div  style="text-align:right">'.number_format($total,2).'</div>'
                                        //,'<div style="text-align:center">'.anchor('trans/permit/delete_temp/'.$value->id,img($this->image_properties_del),$js).'</div>'
                                        );
                                $footer['saldoawal']+=$value->saldoawal;
                                $footer['debit']+=$value->debit;
                                $footer['kredit']+=$value->kredit;
                                $footer['saldoakhir']+=$value->saldoakhir;
                                $footer['current']+=$value->current;
                                $footer['duedate']+=$value->duedate;
                                $footer['r1']+=$value->r1;
                                $footer['r2']+=$value->r2;
                                $footer['r3']+=$value->r3;
                                $footer['r4']+=$value->r4;
                                $footer['total']+=$total;
                                
                            }
                            $this->stable->set_foot(
                                    'TOTAL'
                                    ,'<div  style="text-align:right">'.number_format($footer['saldoawal'],0).'</div>'
                                    ,'<div  style="text-align:right">'.number_format($footer['debit'],0).'</div>'
                                    ,'<div  style="text-align:right">'.number_format($footer['kredit'],0).'</div>'
                                    ,'<div  style="text-align:right">'.number_format($footer['saldoakhir'],0).'</div>'
                                    ,'<div  style="text-align:right">'.number_format($footer['current'],0).'</div>'
                                    ,'<div  style="text-align:right">'.number_format($footer['duedate'],0).'</div>'
                                    ,'<div  style="text-align:right">'.number_format($footer['r1'],0).'</div>'
                                    ,'<div  style="text-align:right">'.number_format($footer['r2'],0).'</div>'
                                    ,'<div  style="text-align:right">'.number_format($footer['r3'],0).'</div>'
                                    ,'<div  style="text-align:right">'.number_format($footer['r4'],0).'</div>'
                                    ,'<div  style="text-align:right">'.number_format($footer['total'],0).'</div>'
                                    );
                            $this->output_table .= $this->stable->generate();
                            $this->output_table .= '<br />';
                            //$this->stable->clear();
                            $query->free_result();
                            $this->output_table;
                            return $this->output_table;
                        }
                        else
                        {
                            return FALSE;
                        }
                    }break;
                    case 2:
                    {
                        $server='192.168.1.2';
                        $user='root';
                        $pass='mpm123';
                        $db='mpm';
                        $this->load->library('PHPJasperXML');
                        if($this->input->post('range')==1)
                        $xml = simplexml_load_file("assets/report/trans/piutang_minggu.jrxml");
                        else
                        $xml = simplexml_load_file("assets/report/trans/piutang_bulan.jrxml");
                        //@$xml = simplexml_load_file("assets/report/trans/aaa.jrxml");
                        @$this->phpjasperxml->debugsql=false;
                        @$this->phpjasperxml->arrayParameter=array('tanggal'=>str_replace('-','',$tanggal),'awal'=>str_replace('-','',$awal));
                        //@$this->phpjasperxml->arrayParameter=array();
                        @$this->phpjasperxml->xml_dismantle($xml);
                        @$this->phpjasperxml->transferDBtoArray($server,$user,$pass,$db);
                        @$this->phpjasperxml->outpage("I");
                  
                    }break;
                    case 3:
                    {
                       $query=$this->db->query($sql);
                       return to_excel($query,$filename);
                    }break;
                }
            }break;
            case 'show_barat':
            {
                $sql='';
                $tanggal=$this->input->post('tanggal');
                $awal=substr($tanggal,0,7).'-01';
                $year=substr($tanggal,2,2);
                $month=substr($tanggal,5,2);
                $bulan=$year.$month;
                $filename='';
                $range=$this->input->post('range');
                switch($range)
                {
                    case '1':
                    {
                        $filename='piutang_minggu_'.$tanggal.'_'.date('YmdHis');
                        $sql="  select grup_lang,kode_comp,group_descr grup_nama,if(isnull(saldoawal),0,saldoawal)saldoawal,debit,kredit,(if(isnull(saldoawal),0,saldoawal)+debit-kredit) saldoakhir, current, duedate, r1 ,r2 ,r3 ,r4 , total 
                                from(
                                   select z.group_id grup_lang,z.group_descr,debitprev-if(isnull(kreditprev),0,kreditprev) saldoawal,if(isnull(debit),0,debit) debit,if(isnull(kredit),0,kredit) kredit
                                            ,current,duedate, r1 ,r2 ,r3 ,r4 ,current+duedate+r1+r2+r3+r4 total
                                    from(

                                    select group_id, concat(group_descr,'') group_descr from dbsls.t_ar_ink_master a 
                        inner join dbsls.m_customer b using(customerid) group by group_id)z
                                                                    
                            left join 
                                    
                                    (select group_id, group_descr, sum(if(retur=1,dokument*-1,dokument)) debitprev from dbsls.t_ar_ink_master a 
                                    inner join dbsls.m_customer b using(customerid)where  tanggal<'".$awal."'
                                    group by group_id)a using(group_id)

                                    left join 

                                    (select group_id, group_descr, sum(bayar_transfer+bayar_giro+bayar_tunai) kreditprev from dbsls.t_ar_ink_detail a 
                                    inner join dbsls.m_customer b using(customerid)where  tgl_transfer<'".$awal."'
                                    group by group_id)b using(group_id)

                                    left join 

                                    (select group_id, group_descr, sum(if(retur=1,dokument*-1,dokument)) debit from dbsls.t_ar_ink_master a 
                                    inner join dbsls.m_customer b using(customerid)where  date(tanggal)>='".$awal."' and date(tanggal)<='".$tanggal."'
                                    group by group_id)c using (group_id)

                                    left join 

                                    (select group_id, group_descr, if(isnull(sum(bayar_transfer+bayar_giro+bayar_tunai)),0,sum(bayar_transfer+bayar_giro+bayar_tunai)) kredit from dbsls.t_ar_ink_detail a 
                                    inner join dbsls.m_customer b using(customerid)where  date(tgl_transfer)>='".$awal."' and date(tgl_transfer)<='".$tanggal."'
                                    group by group_id)d using(group_id)

                                    left join
                                    (select group_id, group_descr,
                                    SUM(IF(days_past_due < 0, amount_due, 0)) current,
                                    SUM(IF(days_past_due = 0, amount_due, 0)) duedate,
                                    SUM(IF(days_past_due BETWEEN 1 AND 7, amount_due, 0)) r1,
                                    SUM(IF(days_past_due BETWEEN 8 AND 14, amount_due, 0)) r2,
                                    SUM(IF(days_past_due BETWEEN 15 AND 30, amount_due, 0)) r3,
                                    SUM(IF(days_past_due > 30, amount_due, 0))r4
                                    FROM(
                                            SELECT group_id, group_descr, datediff('".$tanggal."',tgl_tempo) days_past_due, sum(saldo) amount_due 
                                            FROM(
                                                    SELECT no_sales ,a.customerid ,tgl_tempo ,( IF ( a.retur = 1, a.dokument *- 1, a.dokument )) - IF (isnull(kredit), 0, kredit) saldo
                                                    FROM dbsls.t_ar_ink_master a 
                                                    LEFT JOIN 
                                                            (SELECT no_sales,customerid, sum( bayar_transfer + bayar_giro + bayar_tunai ) kredit 
                                                            FROM dbsls.t_ar_ink_detail where tgl_transfer <='".$tanggal."'
                                                            GROUP BY no_sales ) b USING (no_sales) where a.tanggal<='".$tanggal."' 
                                                            GROUP BY no_sales)a1 
                                                            inner join dbsls.m_customer b1 using (customerid) 
                                                            WHERE saldo <>0
                                                            GROUP BY group_id,days_past_due
                                                            )a group by group_id 
                                    )e using (group_id)
                                )a
                                LEFT JOIN mpm.tabcomp tab ON a.grup_lang = tab.kode_lang
                                where saldoawal+debit-kredit<>0 and wilayah =1 group by a.grup_lang
                                order by a.group_descr
                            ";
                    }
                        break;
                    case '2':
                         $filename='piutang_bulan_'.$tanggal.'_'.date('YmdHis');
                                
                         $sql=" select kode_comp,grup_lang,group_descr grup_nama,saldoawal,debit,kredit,(saldoawal+debit-kredit) saldoakhir, current, duedate, r1 ,r2 ,r3 ,r4 , total 
                                from(
                                    select z.group_id grup_lang,z.group_descr,debitprev-if(isnull(kreditprev),0,kreditprev) saldoawal,if(isnull(debit),0,debit) debit,if(isnull(kredit),0,kredit) kredit
                                            ,current,duedate, r1 ,r2 ,r3 ,r4 ,current+duedate+r1+r2+r3+r4 total
                                    from(

                                    select group_id, concat(group_descr,'') group_descr from dbsls.t_ar_ink_master a 
                        inner join dbsls.m_customer b using(customerid) group by group_id)z
                                                                    
                            left join 
                                    
                                    (select group_id, group_descr, sum(if(retur=1,dokument*-1,dokument)) debitprev from dbsls.t_ar_ink_master a 
                                    inner join dbsls.m_customer b using(customerid)where  tanggal<'".$awal."'
                                    group by group_id)a using(group_id)

                                    left join 

                                    (select group_id, group_descr, sum(bayar_transfer+bayar_giro+bayar_tunai) kreditprev from dbsls.t_ar_ink_detail a 
                                    inner join dbsls.m_customer b using(customerid)where  tgl_transfer<'".$awal."'
                                    group by group_id)b using(group_id)

                                    left join 

                                    (select group_id, group_descr, sum(if(retur=1,dokument*-1,dokument)) debit from dbsls.t_ar_ink_master a 
                                    inner join dbsls.m_customer b using(customerid)where  tanggal>='".$awal."' and tanggal<='".$tanggal."'
                                    group by group_id)c using (group_id)

                                    left join 

                                    (select group_id, group_descr, if(isnull(sum(bayar_transfer+bayar_giro+bayar_tunai)),0,sum(bayar_transfer+bayar_giro+bayar_tunai)) kredit from dbsls.t_ar_ink_detail a 
                                    inner join dbsls.m_customer b using(customerid)where  tgl_transfer>='".$awal."' and tgl_transfer<='".$tanggal."'
                                    group by group_id)d using(group_id)

                                    left join
                                    (select group_id, group_descr,
                                    SUM(IF(days_past_due < 0, amount_due, 0)) current,
                                    SUM(IF(days_past_due = 0, amount_due, 0)) duedate,
                                    SUM(IF(days_past_due BETWEEN 1 AND 30, amount_due, 0)) r1,
                                    SUM(IF(days_past_due BETWEEN 31 AND 60, amount_due, 0)) r2,
                                    SUM(IF(days_past_due BETWEEN 61 AND 90, amount_due, 0)) r3,
                                    SUM(IF(days_past_due > 90, amount_due, 0))r4
                                    FROM(
                                            SELECT group_id, group_descr, datediff('".$tanggal."',tgl_tempo) days_past_due, sum(saldo) amount_due 
                                            FROM(
                                                    SELECT no_sales ,a.customerid ,tgl_tempo ,( IF ( a.retur = 1, a.dokument *- 1, a.dokument )) - IF (isnull(kredit), 0, kredit) saldo
                                                    FROM dbsls.t_ar_ink_master a 
                                                    LEFT JOIN 
                                                            (SELECT no_sales,customerid, sum( bayar_transfer + bayar_giro + bayar_tunai ) kredit 
                                                            FROM dbsls.t_ar_ink_detail where tgl_transfer <='".$tanggal."'
                                                            GROUP BY no_sales ) b USING (no_sales) where a.tanggal<='".$tanggal."' 
                                                            GROUP BY no_sales)a1 
                                                            inner join dbsls.m_customer b1 using (customerid) 
                                                            WHERE saldo <>0
                                                            GROUP BY group_id,days_past_due
                                                            )a group by group_id 
                                    )e using (group_id)
                                )a
                                 LEFT JOIN mpm.tabcomp tab ON a.grup_lang = tab.kode_lang
                                where saldoawal+debit-kredit<>0 and wilayah = 1 group by a.grup_lang
                                order by a.group_descr
                            ";
                        break;
                }
                switch($this->input->post('format'))
                {
                    case 1:
                    {
                        $query=$this->db->query($sql);
                        if($query->num_rows()>0)
                        {
                            $this->load->library('table');
                            //$this->table->set_empty('0');
                            $this->stable->set_template($this->tmpl_piutang);
                            //$this->stable->set_caption('PIUTANG');
                            switch($this->input->post('range'))
                            {
                                case 1:
                                $this->stable->set_heading('Customer','Saldo Awal','Debit','Kredit','Saldo Akhir','Current','Duedate','1-7','8-14','15-30','>30','Total Over Due');
                                break;
                                case 2:
                                $this->stable->set_heading('Customer','Saldo Awal','Debit','Kredit','Saldo Akhir','Current','Duedate','1-30','31-60','61-90','>90','Total Over Due');
                                break;
                            }
                            $footer=array(
                                'saldoawal'=>0
                                ,'debit'=>0
                                ,'kredit'=>0
                                ,'saldoakhir'=>0
                                ,'current'=>0
                                ,'duedate'=>0
                                ,'r1'=>0
                                ,'r2'=>0
                                ,'r3'=>0
                                ,'r4'=>0
                                ,'total'=>0
                                );
                            foreach ($query->result() as $value)
                            {
                                //$js=array('onclick'=>"return confirm('Are you sure you want to delete?')");
                                //$total=$value->current+$value->duedate+$value->r1+$value->r2+$value->r3+$value->r4;
                                $total=$value->r1+$value->r2+$value->r3+$value->r4;
                                $this->stable->add_row(
                                        $value->grup_nama.($value->kode_comp<>""?" (".$value->kode_comp.")":"")
                                        ,'<div  style="text-align:right">'.number_format($value->saldoawal,2).'</div>'
                                        ,'<div  style="text-align:right">'.number_format($value->debit,2).'</div>'
                                        ,'<div  style="text-align:right">'.number_format($value->kredit,2).'</div>'
                                        ,'<div  style="text-align:right">'.number_format($value->saldoakhir,2).'</div>'
                                        ,'<div  style="text-align:right">'.number_format($value->current,2).'</div>'
                                        ,'<div  style="text-align:right">'.number_format($value->duedate,2).'</div>'
                                        ,'<div  style="text-align:right">'.number_format($value->r1,2).'</div>'
                                        ,'<div  style="text-align:right">'.number_format($value->r2,2).'</div>'
                                        ,'<div  style="text-align:right">'.number_format($value->r3,2).'</div>'
                                        ,'<div  style="text-align:right">'.number_format($value->r4,2).'</div>'
                                        ,'<div  style="text-align:right">'.number_format($total,2).'</div>'
                                        //,'<div style="text-align:center">'.anchor('trans/permit/delete_temp/'.$value->id,img($this->image_properties_del),$js).'</div>'
                                        );
                                $footer['saldoawal']+=$value->saldoawal;
                                $footer['debit']+=$value->debit;
                                $footer['kredit']+=$value->kredit;
                                $footer['saldoakhir']+=$value->saldoakhir;
                                $footer['current']+=$value->current;
                                $footer['duedate']+=$value->duedate;
                                $footer['r1']+=$value->r1;
                                $footer['r2']+=$value->r2;
                                $footer['r3']+=$value->r3;
                                $footer['r4']+=$value->r4;
                                $footer['total']+=$total;
                                
                            }
                            $this->stable->set_foot(
                                    'TOTAL'
                                    ,'<div  style="text-align:right">'.number_format($footer['saldoawal'],0).'</div>'
                                    ,'<div  style="text-align:right">'.number_format($footer['debit'],0).'</div>'
                                    ,'<div  style="text-align:right">'.number_format($footer['kredit'],0).'</div>'
                                    ,'<div  style="text-align:right">'.number_format($footer['saldoakhir'],0).'</div>'
                                    ,'<div  style="text-align:right">'.number_format($footer['current'],0).'</div>'
                                    ,'<div  style="text-align:right">'.number_format($footer['duedate'],0).'</div>'
                                    ,'<div  style="text-align:right">'.number_format($footer['r1'],0).'</div>'
                                    ,'<div  style="text-align:right">'.number_format($footer['r2'],0).'</div>'
                                    ,'<div  style="text-align:right">'.number_format($footer['r3'],0).'</div>'
                                    ,'<div  style="text-align:right">'.number_format($footer['r4'],0).'</div>'
                                    ,'<div  style="text-align:right">'.number_format($footer['total'],0).'</div>'
                                    );
                            $this->output_table .= $this->stable->generate();
                            $this->output_table .= '<br />';
                            //$this->stable->clear();
                            $query->free_result();
                            $this->output_table;
                            return $this->output_table;
                        }
                        else
                        {
                            return FALSE;
                        }
                    }break;
                    case 2:
                    {
                        $server='192.168.1.2';
                        $user='root';
                        $pass='mpm123';
                        $db='mpm';
                        $this->load->library('PHPJasperXML');
                        if($this->input->post('range')==1)
                        $xml = simplexml_load_file("assets/report/trans/piutang_minggu.jrxml");
                        else
                        $xml = simplexml_load_file("assets/report/trans/piutang_bulan.jrxml");
                        //@$xml = simplexml_load_file("assets/report/trans/aaa.jrxml");
                        @$this->phpjasperxml->debugsql=false;
                        @$this->phpjasperxml->arrayParameter=array('tanggal'=>str_replace('-','',$tanggal),'awal'=>str_replace('-','',$awal));
                        //@$this->phpjasperxml->arrayParameter=array();
                        @$this->phpjasperxml->xml_dismantle($xml);
                        @$this->phpjasperxml->transferDBtoArray($server,$user,$pass,$db);
                        @$this->phpjasperxml->outpage("I");
                  
                    }break;
                    case 3:
                    {
                       $query=$this->db->query($sql);
                       return to_excel($query,$filename);
                    }break;
                }
            }break;
            case 'show_timur':
            {
                $sql='';
                $tanggal=$this->input->post('tanggal');
                $awal=substr($tanggal,0,7).'-01';
                $year=substr($tanggal,2,2);
                $month=substr($tanggal,5,2);
                $bulan=$year.$month;
                $filename='';
                $range=$this->input->post('range');
                switch($range)
                {
                    case '1':
                    {
                        $filename='piutang_minggu_'.$tanggal.'_'.date('YmdHis');
                        $sql="  select grup_lang,kode_comp,group_descr grup_nama,if(isnull(saldoawal),0,saldoawal)saldoawal,debit,kredit,(if(isnull(saldoawal),0,saldoawal)+debit-kredit) saldoakhir, current, duedate, r1 ,r2 ,r3 ,r4 , total 
                                from(
                                   select z.group_id grup_lang,z.group_descr,debitprev-if(isnull(kreditprev),0,kreditprev) saldoawal,if(isnull(debit),0,debit) debit,if(isnull(kredit),0,kredit) kredit
                                            ,current,duedate, r1 ,r2 ,r3 ,r4 ,current+duedate+r1+r2+r3+r4 total
                                    from(

                                    select group_id, concat(group_descr,'') group_descr from dbsls.t_ar_ink_master a 
                        inner join dbsls.m_customer b using(customerid) group by group_id)z
                                                                    
                            left join 
                                    
                                    (select group_id, group_descr, sum(if(retur=1,dokument*-1,dokument)) debitprev from dbsls.t_ar_ink_master a 
                                    inner join dbsls.m_customer b using(customerid)where  tanggal<'".$awal."'
                                    group by group_id)a using(group_id)

                                    left join 

                                    (select group_id, group_descr, sum(bayar_transfer+bayar_giro+bayar_tunai) kreditprev from dbsls.t_ar_ink_detail a 
                                    inner join dbsls.m_customer b using(customerid)where  tgl_transfer<'".$awal."'
                                    group by group_id)b using(group_id)

                                    left join 

                                    (select group_id, group_descr, sum(if(retur=1,dokument*-1,dokument)) debit from dbsls.t_ar_ink_master a 
                                    inner join dbsls.m_customer b using(customerid)where  date(tanggal)>='".$awal."' and date(tanggal)<='".$tanggal."'
                                    group by group_id)c using (group_id)

                                    left join 

                                    (select group_id, group_descr, if(isnull(sum(bayar_transfer+bayar_giro+bayar_tunai)),0,sum(bayar_transfer+bayar_giro+bayar_tunai)) kredit from dbsls.t_ar_ink_detail a 
                                    inner join dbsls.m_customer b using(customerid)where  date(tgl_transfer)>='".$awal."' and date(tgl_transfer)<='".$tanggal."'
                                    group by group_id)d using(group_id)

                                    left join
                                    (select group_id, group_descr,
                                    SUM(IF(days_past_due < 0, amount_due, 0)) current,
                                    SUM(IF(days_past_due = 0, amount_due, 0)) duedate,
                                    SUM(IF(days_past_due BETWEEN 1 AND 7, amount_due, 0)) r1,
                                    SUM(IF(days_past_due BETWEEN 8 AND 14, amount_due, 0)) r2,
                                    SUM(IF(days_past_due BETWEEN 15 AND 30, amount_due, 0)) r3,
                                    SUM(IF(days_past_due > 30, amount_due, 0))r4
                                    FROM(
                                            SELECT group_id, group_descr, datediff('".$tanggal."',tgl_tempo) days_past_due, sum(saldo) amount_due 
                                            FROM(
                                                    SELECT no_sales ,a.customerid ,tgl_tempo ,( IF ( a.retur = 1, a.dokument *- 1, a.dokument )) - IF (isnull(kredit), 0, kredit) saldo
                                                    FROM dbsls.t_ar_ink_master a 
                                                    LEFT JOIN 
                                                            (SELECT no_sales,customerid, sum( bayar_transfer + bayar_giro + bayar_tunai ) kredit 
                                                            FROM dbsls.t_ar_ink_detail where tgl_transfer <='".$tanggal."'
                                                            GROUP BY no_sales ) b USING (no_sales) where a.tanggal<='".$tanggal."' 
                                                            GROUP BY no_sales)a1 
                                                            inner join dbsls.m_customer b1 using (customerid) 
                                                            WHERE saldo <>0
                                                            GROUP BY group_id,days_past_due
                                                            )a group by group_id 
                                    )e using (group_id)
                                )a
                                LEFT JOIN mpm.tabcomp tab ON a.grup_lang = tab.kode_lang
                                where saldoawal+debit-kredit<>0 and wilayah =2 group by a.grup_lang
                                order by a.group_descr
                            ";
                    }
                        break;
                    case '2':
                         $filename='piutang_bulan_'.$tanggal.'_'.date('YmdHis');
                                
                         $sql=" select kode_comp,grup_lang,group_descr grup_nama,saldoawal,debit,kredit,(saldoawal+debit-kredit) saldoakhir, current, duedate, r1 ,r2 ,r3 ,r4 , total 
                                from(
                                    select z.group_id grup_lang,z.group_descr,debitprev-if(isnull(kreditprev),0,kreditprev) saldoawal,if(isnull(debit),0,debit) debit,if(isnull(kredit),0,kredit) kredit
                                            ,current,duedate, r1 ,r2 ,r3 ,r4 ,current+duedate+r1+r2+r3+r4 total
                                    from(

                                    select group_id, concat(group_descr,'') group_descr from dbsls.t_ar_ink_master a 
                        inner join dbsls.m_customer b using(customerid) group by group_id)z
                                                                    
                            left join 
                                    
                                    (select group_id, group_descr, sum(if(retur=1,dokument*-1,dokument)) debitprev from dbsls.t_ar_ink_master a 
                                    inner join dbsls.m_customer b using(customerid)where  tanggal<'".$awal."'
                                    group by group_id)a using(group_id)

                                    left join 

                                    (select group_id, group_descr, sum(bayar_transfer+bayar_giro+bayar_tunai) kreditprev from dbsls.t_ar_ink_detail a 
                                    inner join dbsls.m_customer b using(customerid)where  tgl_transfer<'".$awal."'
                                    group by group_id)b using(group_id)

                                    left join 

                                    (select group_id, group_descr, sum(if(retur=1,dokument*-1,dokument)) debit from dbsls.t_ar_ink_master a 
                                    inner join dbsls.m_customer b using(customerid)where  tanggal>='".$awal."' and tanggal<='".$tanggal."'
                                    group by group_id)c using (group_id)

                                    left join 

                                    (select group_id, group_descr, if(isnull(sum(bayar_transfer+bayar_giro+bayar_tunai)),0,sum(bayar_transfer+bayar_giro+bayar_tunai)) kredit from dbsls.t_ar_ink_detail a 
                                    inner join dbsls.m_customer b using(customerid)where  tgl_transfer>='".$awal."' and tgl_transfer<='".$tanggal."'
                                    group by group_id)d using(group_id)

                                    left join
                                    (select group_id, group_descr,
                                    SUM(IF(days_past_due < 0, amount_due, 0)) current,
                                    SUM(IF(days_past_due = 0, amount_due, 0)) duedate,
                                    SUM(IF(days_past_due BETWEEN 1 AND 30, amount_due, 0)) r1,
                                    SUM(IF(days_past_due BETWEEN 31 AND 60, amount_due, 0)) r2,
                                    SUM(IF(days_past_due BETWEEN 61 AND 90, amount_due, 0)) r3,
                                    SUM(IF(days_past_due > 90, amount_due, 0))r4
                                    FROM(
                                            SELECT group_id, group_descr, datediff('".$tanggal."',tgl_tempo) days_past_due, sum(saldo) amount_due 
                                            FROM(
                                                    SELECT no_sales ,a.customerid ,tgl_tempo ,( IF ( a.retur = 1, a.dokument *- 1, a.dokument )) - IF (isnull(kredit), 0, kredit) saldo
                                                    FROM dbsls.t_ar_ink_master a 
                                                    LEFT JOIN 
                                                            (SELECT no_sales,customerid, sum( bayar_transfer + bayar_giro + bayar_tunai ) kredit 
                                                            FROM dbsls.t_ar_ink_detail where tgl_transfer <='".$tanggal."'
                                                            GROUP BY no_sales ) b USING (no_sales) where a.tanggal<='".$tanggal."' 
                                                            GROUP BY no_sales)a1 
                                                            inner join dbsls.m_customer b1 using (customerid) 
                                                            WHERE saldo <>0
                                                            GROUP BY group_id,days_past_due
                                                            )a group by group_id 
                                    )e using (group_id)
                                )a
                                 LEFT JOIN mpm.tabcomp tab ON a.grup_lang = tab.kode_lang
                                where saldoawal+debit-kredit<>0 and wilayah = 2 group by a.grup_lang
                                order by a.group_descr
                            ";
                        break;
                }
                switch($this->input->post('format'))
                {
                    case 1:
                    {
                        $query=$this->db->query($sql);
                        if($query->num_rows()>0)
                        {
                            $this->load->library('table');
                            //$this->table->set_empty('0');
                            $this->stable->set_template($this->tmpl_piutang);
                            //$this->stable->set_caption('PIUTANG');
                            switch($this->input->post('range'))
                            {
                                case 1:
                                $this->stable->set_heading('Customer','Saldo Awal','Debit','Kredit','Saldo Akhir','Current','Duedate','1-7','8-14','15-30','>30','Total Over Due');
                                break;
                                case 2:
                                $this->stable->set_heading('Customer','Saldo Awal','Debit','Kredit','Saldo Akhir','Current','Duedate','1-30','31-60','61-90','>90','Total Over Due');
                                break;
                            }
                            $footer=array(
                                'saldoawal'=>0
                                ,'debit'=>0
                                ,'kredit'=>0
                                ,'saldoakhir'=>0
                                ,'current'=>0
                                ,'duedate'=>0
                                ,'r1'=>0
                                ,'r2'=>0
                                ,'r3'=>0
                                ,'r4'=>0
                                ,'total'=>0
                                );
                            foreach ($query->result() as $value)
                            {
                                //$js=array('onclick'=>"return confirm('Are you sure you want to delete?')");
                                //$total=$value->current+$value->duedate+$value->r1+$value->r2+$value->r3+$value->r4;
                                $total=$value->r1+$value->r2+$value->r3+$value->r4;
                                $this->stable->add_row(
                                        $value->grup_nama.($value->kode_comp<>""? " (".$value->kode_comp.")":"")
                                        ,'<div  style="text-align:right">'.number_format($value->saldoawal,2).'</div>'
                                        ,'<div  style="text-align:right">'.number_format($value->debit,2).'</div>'
                                        ,'<div  style="text-align:right">'.number_format($value->kredit,2).'</div>'
                                        ,'<div  style="text-align:right">'.number_format($value->saldoakhir,2).'</div>'
                                        ,'<div  style="text-align:right">'.number_format($value->current,2).'</div>'
                                        ,'<div  style="text-align:right">'.number_format($value->duedate,2).'</div>'
                                        ,'<div  style="text-align:right">'.number_format($value->r1,2).'</div>'
                                        ,'<div  style="text-align:right">'.number_format($value->r2,2).'</div>'
                                        ,'<div  style="text-align:right">'.number_format($value->r3,2).'</div>'
                                        ,'<div  style="text-align:right">'.number_format($value->r4,2).'</div>'
                                        ,'<div  style="text-align:right">'.number_format($total,2).'</div>'
                                        //,'<div style="text-align:center">'.anchor('trans/permit/delete_temp/'.$value->id,img($this->image_properties_del),$js).'</div>'
                                        );
                                $footer['saldoawal']+=$value->saldoawal;
                                $footer['debit']+=$value->debit;
                                $footer['kredit']+=$value->kredit;
                                $footer['saldoakhir']+=$value->saldoakhir;
                                $footer['current']+=$value->current;
                                $footer['duedate']+=$value->duedate;
                                $footer['r1']+=$value->r1;
                                $footer['r2']+=$value->r2;
                                $footer['r3']+=$value->r3;
                                $footer['r4']+=$value->r4;
                                $footer['total']+=$total;
                                
                            }
                            $this->stable->set_foot(
                                    'TOTAL'
                                    ,'<div  style="text-align:right">'.number_format($footer['saldoawal'],0).'</div>'
                                    ,'<div  style="text-align:right">'.number_format($footer['debit'],0).'</div>'
                                    ,'<div  style="text-align:right">'.number_format($footer['kredit'],0).'</div>'
                                    ,'<div  style="text-align:right">'.number_format($footer['saldoakhir'],0).'</div>'
                                    ,'<div  style="text-align:right">'.number_format($footer['current'],0).'</div>'
                                    ,'<div  style="text-align:right">'.number_format($footer['duedate'],0).'</div>'
                                    ,'<div  style="text-align:right">'.number_format($footer['r1'],0).'</div>'
                                    ,'<div  style="text-align:right">'.number_format($footer['r2'],0).'</div>'
                                    ,'<div  style="text-align:right">'.number_format($footer['r3'],0).'</div>'
                                    ,'<div  style="text-align:right">'.number_format($footer['r4'],0).'</div>'
                                    ,'<div  style="text-align:right">'.number_format($footer['total'],0).'</div>'
                                    );
                            $this->output_table .= $this->stable->generate();
                            $this->output_table .= '<br />';
                            //$this->stable->clear();
                            $query->free_result();
                            $this->output_table;
                            return $this->output_table;
                        }
                        else
                        {
                            return FALSE;
                        }
                    }break;
                    case 2:
                    {
                        $server='192.168.1.2';
                        $user='root';
                        $pass='mpm123';
                        $db='mpm';
                        $this->load->library('PHPJasperXML');
                        if($this->input->post('range')==1)
                        $xml = simplexml_load_file("assets/report/trans/piutang_minggu.jrxml");
                        else
                        $xml = simplexml_load_file("assets/report/trans/piutang_bulan.jrxml");
                        //@$xml = simplexml_load_file("assets/report/trans/aaa.jrxml");
                        @$this->phpjasperxml->debugsql=false;
                        @$this->phpjasperxml->arrayParameter=array('tanggal'=>str_replace('-','',$tanggal),'awal'=>str_replace('-','',$awal));
                        //@$this->phpjasperxml->arrayParameter=array();
                        @$this->phpjasperxml->xml_dismantle($xml);
                        @$this->phpjasperxml->transferDBtoArray($server,$user,$pass,$db);
                        @$this->phpjasperxml->outpage("I");
                  
                    }break;
                    case 3:
                    {
                       $query=$this->db->query($sql);
                       return to_excel($query,$filename);
                    }break;
                }
            }break;
        }
    }
    public function sell($state=null,$key='',$id='',$init='')
    {
        switch($state)
        {
            case 'download':
            {
                $bulan=substr($this->input->post('tanggal'),2,2).substr($this->input->post('tanggal'),5,2);
                $tanggal=$this->input->post('tanggal');
                $sql='';
                switch($this->input->post('format'))
                {
                    case 1:
                    {
                        /*$sql="select * from(select if(supp='006','PT. ULTRA SAKTI',namasupp) as supplier,supp,NODOKJDI as 'no_fktr',nodokacu as no_do,nama_wp,npwp,TGLDOKJDI as tanggal,format(tot1-potongan-potong2,2,'id_ID') as dpp_nett,format(a.ppn,2,'id_ID') as ppn,format(materai,2,'id_ID')as materai from pusat.fh a  inner join pusat.user b using(kode_lang) where bulan=".$bulan."
                              union ALL
                              select if(supp='006','PT. ULTRA SAKTI',c.namasupp), supp,NODOKJDI,nodokacu as 'no_do',nama_wp,b.npwp,TGLDOKJDI,format(tot1-potongan-potong2,2,'id_ID') as dpp_nett,format(a.ppn,2,'id_ID'),format(materai,2,'id_ID')as materai from pusat.rh a  inner join pusat.user b using(kode_lang) inner join mpm.tabsupp c using(supp) where bulan=".$bulan.") a order by nama_wp";
                        */
                         $sql="select c.nama_pkp,c.npwp,no_seri_pajak no_fktr,ref no_do,tanggal,dp_value materai, if(a.retur=1,sum(total_harga)*-1,sum(total_harga)) dpp_net, sum(total_harga)/10 ppn 
                                from dbsls.t_sales_master a 
                                inner join dbsls.t_sales_detail b using(no_sales)
                                inner join dbsls.m_customer_pkp c using(customerid)
                                where DATE_FORMAT(a.tanggal,'%y%m')=? 
                                group by no_sales order by nama_pkp";
                         $query=$this->db->query($sql,array($bulan));
                         
                        return to_excel($query,'PPN_'.date('Ymd_His'));
                    }break;
                    case 2:
                    {
                        /*$sql="select nama_lang as pelanggan,format(sum(a),0,'id_ID') as dpp_gross, format(sum(b),0,'id_ID') as dpp_net,format(sum(ppn),0,'id_ID') as ppn,format(potong2,0,'id_ID') as potong2,format(sum(materai),0,'id_ID') as materai,NODOKACU as 'No DO' from(
                            select b.nama_lang,grup_nama,grup_lang, sum(tot1-potongan) as a, sum(tot1-potongan-potong2) as b,sum(a.ppn) as ppn,sum(potong2) as potong2,sum(materai) as materai,nodokacu
                            from pusat.fh a inner join pusat.user b using (kode_lang) where bulan=".$bulan." and potong2=0 and kode_lang<>'00117' group by kode_lang)a
                            GROUP BY grup_lang
                            union ALL
                            select grup_nama,format(a,0,'id_ID') as dpp_gross, format(b,0,'id_ID') as dpp_net,format(ppn,0,'id_ID'),format(potong2,0,'id_ID'),format(materai,0,'id_ID'), NODOKACU from(
                            select grup_nama, (tot1-potongan) as a, (tot1-potongan-potong2) as b,a.ppn, potong2,materai,nodokacu
                            from pusat.fh a inner join pusat.user b using (kode_lang) where bulan=".$bulan." and potong2<>0 )a
                            union ALL
                            select grup_nama,format(a,0,'id_ID') as dpp_gross, format(b,0,'id_ID') as dpp_net,format(ppn,0,'id_ID'),format(potong2,0,'id_ID'),format(materai,0,'id_ID'), NODOKACU from(
                            select grup_nama, (tot1-potongan) as a, (tot1-potongan-potong2) as b,a.ppn, potong2,materai,nodokacu
                            from pusat.fh a inner join pusat.user b using (kode_lang) where bulan=".$bulan." and kode_lang='00117' )a
                            union ALL
                            select nama_lang,format(sum(a),0,'id_ID') as dpp_gross, format(sum(b),0,'id_ID') as dpp_net,format(sum(ppn),0,'id_ID'),format(potong2,0,'id_ID'),format(sum(materai),0,'id_ID'),NODOKACU from(
                            select b.nama_lang,grup_nama,grup_lang, sum(tot1-potongan) as a, sum(tot1-potongan-potong2) as b,sum(a.ppn) as ppn,sum(potong2) as potong2,sum(materai) as materai,nodokacu
                            from pusat.rh a inner join pusat.user b using (kode_lang) where bulan=".$bulan." and  potong2=0 group by kode_lang)a
                            GROUP BY grup_lang
                            union ALL
                            select grup_nama,format(a,0,'id_ID') as dpp_gross, format(b,0,'id_ID') as dpp_net,format(ppn,0,'id_ID'),format(potong2,0,'id_ID'),format(materai,0,'id_ID'),NODOKACU from(
                            select grup_nama, (tot1-potongan) as a, (tot1-potongan-potong2) as b,a.ppn, potong2,materai,nodokacu
                            from pusat.rh a inner join pusat.user b using (kode_lang) where bulan=".$bulan." and potong2<>0 )a
                            ";*/
                            $sql="select group_descr pelanggan,ref no_do,sum(if(retur=1,biaya_jasa*-1,biaya_jasa)) dpp_gross, sum(if(retur=1,rp_netto*-1,rp_netto)) dpp_net, sum(if(retur=1,rp_netto/10*-1,rp_netto/10)) ppn
                                    , sum(disc_value) potong2,sum(dp_value) materai  from dbsls.t_ar_ink_master
                                    a inner join dbsls.m_customer b using(customerid)
                                    where disc_value=0 and customerid<>'100117' and date_format(tanggal,'%y%m')=? and retur=0
                                    group by group_id
                                    union all
                                    select group_descr pelanggan,ref no_do,sum(if(retur=1,biaya_jasa*-1,biaya_jasa)) dpp_gross, sum(if(retur=1,rp_netto*-1,rp_netto)) dpp_net, sum(if(retur=1,rp_netto/10*-1,rp_netto/10)) ppn
                                    , sum(disc_value) potong2,sum(dp_value) materai from dbsls.t_ar_ink_master 
                                    a inner join dbsls.m_customer b using(customerid)
                                    where disc_value<>0 and date_format(tanggal,'%y%m')=? and retur=0
                                    group by group_id
                                    union all 
                                    select group_descr pelanggan,ref no_do,sum(if(retur=1,biaya_jasa*-1,biaya_jasa)) dpp_gross, sum(if(retur=1,rp_netto*-1,rp_netto)) dpp_net, sum(if(retur=1,rp_netto/10*-1,rp_netto/10)) ppn
                                    , sum(disc_value) potong2,sum(dp_value) materai from dbsls.t_ar_ink_master 
                                    a inner join dbsls.m_customer b using(customerid)
                                    where customerid='100117' and date_format(tanggal,'%y%m')=? and retur=0
                                    group by group_id
                                    union all 
                                    select group_descr pelanggan,ref no_do,sum(if(retur=1,biaya_jasa*-1,biaya_jasa)) dpp_gross, sum(if(retur=1,rp_netto*-1,rp_netto)) dpp_net, sum(if(retur=1,rp_netto/10*-1,rp_netto/10)) ppn
                                    , sum(disc_value) potong2,sum(dp_value) materai from dbsls.t_ar_ink_master 
                                    a inner join dbsls.m_customer b using(customerid)
                                    where disc_value<>0 and date_format(tanggal,'%y%m')=? and retur=1
                                    group by group_id
                                    union all
                                    select group_descr pelanggan,ref no_do,sum(if(retur=1,biaya_jasa*-1,biaya_jasa)) dpp_gross, sum(if(retur=1,rp_netto*-1,rp_netto)) dpp_net, sum(if(retur=1,rp_netto/10*-1,rp_netto/10)) ppn
                                    , sum(disc_value) potong2,sum(dp_value) materai from dbsls.t_ar_ink_master 
                                    a inner join dbsls.m_customer b using(customerid)
                                    where disc_value=0 and date_format(tanggal,'%y%m')=? and retur=1
                                    group by group_id";
                        $query=$this->db->query($sql,array($bulan,$bulan,$bulan,$bulan,$bulan));
                        return to_excel($query,'MYOB_'.date('Ymd_His'));
                    }break;
                    case 3:
                    {
                        $sql="select if(supp='006','PT. ULTRA SAKTI',namasupp) as supplier,DATE_FORMAT(TGLDOKJDI,'%d-%m-%Y') as tgltrans ,NODOKACU as nodo, format(tot1,2,'ID_id') as dpp_jual, format(tot1*0.85,2,'ID_id') as dpp_beli, format(tot1*0.85*0.1,2,'ID_id') as ppn_beli from pusat.fh where supp not in ('001','000') and
                           bulan=".$bulan." order by supp,tgldokjdi";
                        $query=$this->db->query($sql);
                        return to_excel($query,'BELI_'.date('Ymd_His'));
                    }break;
                    case 4:
                    {
                        $tanggal=$this->input->post('tanggal');
                        delete_files('./assets/faktur');
                        //$sql="select kode_lang,faktur, pajak from pusat.permit a inner join pusat.permit_detail b on a.id=b.id_ref where id_ref=? and keterangan='Copy Faktur'";
                        //$sql='select nodokjdi,nama_lang from pusat.fh where tgldokjdi="'.$tanggal.'"';
                        $sql="select substr(trim(no_polisi),9,8) nodokjdi,nama_customer nama_lang from dbsls.t_ar_ink_master a inner join dbsls.m_customer b using(customerid) where date_format(tanggal,'%Y-%m-%d')=?";
                        $query=$this->db->query($sql,array($tanggal));
                        if($query->num_rows() > 0)
                        {
                            $row=$query->row();

                            $server='192.168.1.2';
                            $user='root';
                            $pass='mpm123';
                            $db='mpm';
                            $this->load->library('PHPJasperXML');
                            $xml_fk = simplexml_load_file("assets/report/trans/faktur_komersil_baru.jrxml");
                            $xml_fp = simplexml_load_file("assets/report/trans/faktur_pajak_baru.jrxml");

                            foreach ($query->result() as $value)
                            {
                                 $PHPJasperXML = new PHPJasperXML();
                                 $PHPJasperXML->debugsql=false;
                                 $PHPJasperXML->arrayParameter=array('id'=>$value->nodokjdi,'bulan'=>$bulan);
                                 //$PHPJasperXML->arrayParameter=array('id'=>$tanggal);
                                 $PHPJasperXML->xml_dismantle($xml_fk);
                                 $PHPJasperXML->transferDBtoArray($server,$user,$pass,$db);
                                 $PHPJasperXML->outpage("F",'assets/faktur/FK'.str_replace(' ','_', $value->nodokjdi.'_'.$value->nama_lang).'.pdf');
                                 //$this->email->attach('assets/faktur/'.str_replace('/','_', $value->faktur).'.pdf');
                                 
                                 $this->zip->read_file('assets/faktur/FK'.str_replace(' ','_', $value->nodokjdi.'_'.$value->nama_lang).'.pdf');
                                 $PHPJasperXML = new PHPJasperXML();
                                 $PHPJasperXML->debugsql=false;
                                 $PHPJasperXML->arrayParameter=array('id'=>$value->nodokjdi,'bulan'=>$bulan);
                                 //$PHPJasperXML->arrayParameter=array('id'=>$tanggal);
                                 $PHPJasperXML->xml_dismantle($xml_fp);
                                 $PHPJasperXML->transferDBtoArray($server,$user,$pass,$db);
                                 $PHPJasperXML->outpage("F",'assets/faktur/FP'.str_replace(' ','_', $value->nodokjdi.'_'.$value->nama_lang).'.pdf');
                                 $this->zip->read_file('assets/faktur/FP'.str_replace(' ','_', $value->nodokjdi.'_'.$value->nama_lang).'.pdf');
                            }
                            $filename=md5(date("d-m-Y H:i:s")).'.zip';
                            $this->zip->download($filename);
                        }
                        else
                        {
                            return false;
                        }
                    }break;
                    case 5:
                    {
                        $bulan=substr($this->input->post('tanggal'),5,2);
                        $tahun=substr($this->input->post('tanggal'),0,4);
                        //$tahun=substr($this->input->post('tanggal'),0,4);
                        delete_files('./assets/faktur');
                        //$sql="select kode_lang,faktur, pajak from pusat.permit a inner join pusat.permit_detail b on a.id=b.id_ref where id_ref=? and keterangan='Copy Faktur'";
                        $sql='select id,nodo_beli,supp from mpm.trans where month(tglbuat)=? and deleted=0 and year(tglbuat)=?' ;
                        $query=$this->db->query($sql,array($bulan,$tahun));
                        if($query->num_rows() > 0)
                        {
                            $row=$query->row();

                            $server='192.168.1.2';
                            $user='root';
                            $pass='mpm123';
                            $db='mpm';
                            $this->load->library('PHPJasperXML');
                            $xml = simplexml_load_file("assets/report/report_retur_supp.jrxml");
                            
                            foreach ($query->result() as $value)
                            {
                                 $PHPJasperXML = new PHPJasperXML();
                                 $PHPJasperXML->debugsql=false;
                                 $PHPJasperXML->arrayParameter=array('nopesan'=>$value->id);
                                 //$PHPJasperXML->arrayParameter=array('id'=>$tanggal);
                                 $PHPJasperXML->xml_dismantle($xml);
                                 $PHPJasperXML->transferDBtoArray($server,$user,$pass,$db);
                                 $PHPJasperXML->outpage("F",'assets/faktur/'.$value->supp.'_'.str_replace('/','_',$value->nodo_beli).'.pdf');
                                 $this->zip->read_file('assets/faktur/'.$value->supp.'_'.str_replace('/','_',$value->nodo_beli).'.pdf');
                            }
                            $filename='RETUR'.md5(date("d-m-Y H:i:s")).'.zip';
                            $this->zip->download($filename);
                        }
                        else
                        {
                            return false;
                        }
                    }break;
                    case 6:
                    {
                         $server='192.168.1.2';
                         $user='root';
                         $pass='mpm123';
                         $db='mpm';
                         $this->load->library('PHPJasperXML');
                         $xml = simplexml_load_file("assets/report/trans/formtagihan.jrxml");
                         
                         $PHPJasperXML = new PHPJasperXML();
                         $PHPJasperXML->debugsql=false;
                         $PHPJasperXML->arrayParameter=array('tanggal'=>str_replace('-','',$tanggal));
                         //$PHPJasperXML->arrayParameter=array('id'=>$tanggal);
                         $PHPJasperXML->xml_dismantle($xml);
                         $PHPJasperXML->transferDBtoArray($server,$user,$pass,$db);
                         $PHPJasperXML->outpage("D",'SKP_'.$tanggal.'.pdf');
                     }
                     break;
                    case 7:
                    {
                        $bulan=substr($this->input->post('tanggal'),5,2);
                        $tahun=substr($this->input->post('tanggal'),0,4);
                        //$tahun=substr($this->input->post('tanggal'),0,4);
                        delete_files('./assets/faktur');
                        //$sql="select kode_lang,faktur, pajak from pusat.permit a inner join pusat.permit_detail b on a.id=b.id_ref where id_ref=? and keterangan='Copy Faktur'";
                        $sql='select id,nodo_beli,supp from mpm.trans where month(tglbuat)=? and deleted=0 and year(tglbuat)=?' ;
                        $query=$this->db->query($sql,array($bulan,$tahun));
                        if($query->num_rows() > 0)
                        {
                            $row=$query->row();

                            $server='192.168.1.2';
                            $user='root';
                            $pass='mpm123';
                            $db='mpm';
                            $this->load->library('PHPJasperXML');
                            $xml = simplexml_load_file("assets/report/report_retur_supp_non_tdt.jrxml");
                            
                            foreach ($query->result() as $value)
                            {
                                 $PHPJasperXML = new PHPJasperXML();
                                 $PHPJasperXML->debugsql=false;
                                 $PHPJasperXML->arrayParameter=array('nopesan'=>$value->id);
                                 //$PHPJasperXML->arrayParameter=array('id'=>$tanggal);
                                 $PHPJasperXML->xml_dismantle($xml);
                                 $PHPJasperXML->transferDBtoArray($server,$user,$pass,$db);
                                 $PHPJasperXML->outpage("F",'assets/faktur/'.$value->supp.'_'.str_replace('/','_',$value->nodo_beli).'.pdf');
                                 $this->zip->read_file('assets/faktur/'.$value->supp.'_'.str_replace('/','_',$value->nodo_beli).'.pdf');
                            }
                            $filename='RETUR'.md5(date("d-m-Y H:i:s")).'.zip';
                            $this->zip->download($filename);
                        }
                        else
                        {
                            return false;
                        }
                    }break;
                }
            }break;
        }
    }
    public function repl($state=null,$key=0,$id='',$init='')
    {
        switch($state)
        {
            case 'detail':
            {
                $sql='update mpm.upload set flag=1 where id=?';
                $this->db->trans_begin();
                $this->db->query($sql,array($key));
                $this->db->trans_complete();
                if($this->db->trans_status()===FALSE)
                {
                    $this->db->trans_rollback();
                }
                else
                {
                    $this->db->trans_commit();
                }
            }break;
            case 'delete_temp':
            {
                $id=$this->session->userdata('id');
                $sql='delete from replresult where created_by=?';
                $query = $this->db->query($sql,array($id));
            }break;
            case 'show_temp':
            {

                $userid=$this->session->userdata('id');
                $customerid=$this->input->post('customerid');
                $cycle=(int)($this->input->post('cycle'));
                $supp=$this->input->post('supp');
                $counter=$cycle+1;
                //$tanggal=strftime('%Y-%m-%d',strtotime(trim($this->input->post('tanggal'))));
                $tanggal=$this->input->post('tanggal');
                $date=array();
                $dp=$key;
                $year=substr($tanggal,0,4);
                $month=substr($tanggal,5,2);
                //echo date('m');
                //echo date('Y')-1;
                $date[6]=date('Y-m-d',strtotime('-6 month',strtotime($tanggal)));
                $date[5]=date('Y-m-d',strtotime('-5 month',strtotime($tanggal)));
                $date[4]=date('Y-m-d',strtotime('-4 month',strtotime($tanggal)));
                $date[3]=date('Y-m-d',strtotime('-3 month',strtotime($tanggal)));
                $date[2]=date('Y-m-d',strtotime('-2 month',strtotime($tanggal)));
                $date[1]=date('Y-m-d',strtotime('-1 month',strtotime($tanggal)));

                $selectfi="select if(kodeprod='010074','010020',IF(kodeprod='010075','010057',IF(kodeprod='010076','010058',kodeprod)))kodeprod,sum(banyak) as  average from data".date('Y',strtotime($date[1])).".fi
                    where nocab=".$dp." and kode_type<>'TD' and bulan =".date('m',strtotime($date[1]))." group by kodeprod";
                
                //echo "selectfi : ".$selectfi."<br><hr>";

                for($i=2;$i<$counter;$i++)
                {
                    $selectfi.=" union all
                        select if(kodeprod='010074','010020',IF(kodeprod='010075','010057',IF(kodeprod='010076','010058',kodeprod)))kodeprod,sum(banyak) as  average from data".date('Y',strtotime($date[$i])).".fi
                        where nocab=".$dp." and kode_type<>'TD' and bulan =".date('m',strtotime($date[$i]))." group by kodeprod
                        ";
                }
                $selectri="select if(kodeprod='010074','010020',IF(kodeprod='010075','010057',IF(kodeprod='010076','010058',kodeprod)))kodeprod,sum(banyak) as  average from data".date('Y',strtotime($date[1])).".ri
                    where nocab=".$dp." and bulan =".date('m',strtotime($date[1]))." group by kodeprod";
                for($i=2;$i<$counter;$i++)
                {
                    $selectri.=" union all
                        select if(kodeprod='010074','010020',IF(kodeprod='010075','010057',IF(kodeprod='010076','010058',kodeprod)))kodeprod,sum(banyak) as  average from data".date('Y',strtotime($date[$i])).".ri
                        where nocab=".$dp." and bulan =".date('m',strtotime($date[$i]))." group by kodeprod
                        ";
                }
                $row=$this->getCustInfo2($customerid);
                $sql="
                        insert into mpm.replresult select '".$customerid."','".$row->company."','".$row->npwp."','".$row->email."','".$row->address."', ".$dp." ,supp,kodeprod,namaprod,kode_prc,sl,rata,stok,stdsl,delta,harga,karton,pesan,(pesan*karton) as unit,'".$tanggal."','".date('Y-m-d H:i:s')."',".$userid." from(
                        select if(kodeprod='010020','010074',IF(kodeprod='010057','010075',IF(kodeprod='010058','010076',kodeprod))) kodeprod,namaprod,b.supp,kode_prc,sl,rata, stok as stok,stdsl as stdsl
                        ,delta,karton as karton,if(delta<0,if((abs(delta)/karton)-floor(abs(delta)/karton)>=0.2,ceiling(abs(delta)/karton),floor(abs(delta)/karton)),0) as pesan from(
                        select kodeprod,namaprod,sl,rata,stok,cast((rata/30*sl) as unsigned) as stdsl,(stok-cast(rata/30*sl as unsigned)) as delta
                        from(
                        select if(kodeprod='010074','010020',IF(kodeprod='010075','010057',IF(kodeprod='010076','010058',kodeprod)))kodeprod,namaprod,sl,sum((Saldoawal+masuk_pbk+masuk_supp+retur_sal+Bpinjam+kvretur+retur_depo+tukar_msk+msk_gd_pst) - (sales+kvbeli+pinjam+retur_supp+minta_depo+tukar_klr+klr_gd_pst) )as stok
                        from data".$year.".st a inner join (select kodeprod,sl from mpm.sl where nocab=".$dp.")b using (kodeprod) where nocab=".$dp." and bulan=(select max(bulan) from data".$year.".st where nocab=".$dp.") and kode_gdg='PST' and sl<>0 group by kodeprod ORDER BY KODEPROD) a
                        left join (select kodeprod,cast(sum(average)/".$cycle." as unsigned) as rata from(
                        ".$selectfi."
                        union all
                        ".$selectri."
                        ) a group by kodeprod) b using (kodeprod)
                        ) a
                        inner join (select supp,kodeprod,isisatuan as karton,h_dp,kode_prc from mpm.tabprod ) b using(kodeprod)
                        order by kodeprod
                        )a left join  (select kodeprod,(a.h_dp-(a.h_dp*d_dp/100)) as harga from mpm.prod_detail a where tgl=(select max(tgl) from prod_detail where kodeprod=a.kodeprod))b using(kodeprod)
                       
                        ";

                //echo "sql : ".$sql."<br><hr>";

                //koreksi
                //(select  a.kodeprod,(a.h_dp-(a.h_dp*d_dp/100)) as harga from mpm.prod_detail a inner join (select kodeprod,max(tgl) as tgl from prod_detail group by kodeprod)b
                        //on a.kodeprod =b.kodeprod and a.tgl=b.tgl group by a.kodeprod
                        //)b using(kodeprod)
                    $query = $this->db->query($sql);


                $sql="select * from mpm.replresult where created_by=".$userid." order by kodeprod";
                $query = $this->db->query($sql);

                if($query->num_rows() > 0)
                {
                    $this->load->library('table');
                    $this->table->set_empty('0');

                    $this->table->set_template($this->tmpl);
                    $this->table->set_caption('Replenishment');

                    $this->table->set_heading('KODEPROD','SUPPLIER','KODE PRC','NAMAPROD','SL','AVG','STOK','STDSL','DELTA','KARTON','PESAN','UNIT');
                    //$this->table->set_heading('DELETE','KODE PRODUK','KODE PRC','NAMA PRODUK','UNIT');
                    foreach ($query->result() as $value)
                    {
                        if($value->pesan==0)
                        {
                            $this->table->add_row(
                                    //'<div div style="text-align:center">'.anchor('trans/repl/manual_delete/',img($this->image_properties_del),$this->js).'</div>'
                                    $value->kodeprod,
                                    $this->getSuppname($value->supp),
                                    $value->kode_prc,
                                    $value->namaprod,
                                    '<div style="text-align:right">' .number_format($value->sl,0) .'</div>',
                                    '<div style="text-align:right">' .number_format($value->avg,0).'</div>',
                                    '<div style="text-align:right">' .number_format($value->stok,0).'</div>',
                                    '<div style="text-align:right">' .number_format($value->stdsl,0).'</div>',
                                    '<div style="text-align:right">' .number_format($value->delta,0).'</div>',
                                    '<div style="text-align:right">' .number_format($value->karton,0).'</div>',
                                    '<div style="text-align:right">' .number_format($value->pesan,0).'</div>',
                                    '<div style="text-align:right">' .number_format($value->unit,0).'</div>'

                            );
                        }
                        else
                        {
                            $this->table->add_row(
                                    //'<div div style="text-align:center">'.anchor('trans/repl/manual_delete/',img($this->image_properties_del),$this->js).'</div>'
                                    '<b>'.$value->kodeprod.'</b>',
                                    '<b>'.$this->getSuppname($value->supp).'</b>',
                                    '<b>'.$value->kode_prc.'</b>',
                                    '<b>'.$value->namaprod.'</b>',
                                    '<b><div style="text-align:right">' .number_format($value->sl,0) .'</div></b>',
                                    '<b><div style="text-align:right">' .number_format($value->avg,0).'</div></b>',
                                    '<b><div style="text-align:right">' .number_format($value->stok,0).'</div></b>',
                                    '<b><div style="text-align:right">' .number_format($value->stdsl,0).'</div></b>',
                                    '<b><div style="text-align:right">' .number_format($value->delta,0).'</div></b>',
                                    '<b><div style="text-align:right">' .number_format($value->karton,0).'</div></b>',
                                    '<b><div style="text-align:right">' .number_format($value->pesan,0).'</div></b>',
                                    '<b><div style="text-align:right">' .number_format($value->unit,0).'</div></b>'
                            );
                        }
                    }
                    $this->output_table .= $this->table->generate();
                    $this->output_table .= '<br />';
                    $this->table->clear();
                    return $this->output_table;
                    }
                    else
                    {
                        return FALSE;
                    }
            }break;
            case 'save':
            {
                $supp=$this->input->post('supp');
                $sql='delete from repl where tglpesan=? and nocab=?';
                $query = $this->db->query($sql,array($key,$id));
                $id=$this->session->userdata('id');
                $sql='insert into repl select * from replresult where created_by=?';
                $query = $this->db->query($sql,array($id));
                $date=date('Y-m-d H:i:s');
                $sql='insert into po(company,alamat,email,npwp,userid,supp,tglpesan,created,created_by,tipe) select company,alamat,email,npwp, customerid,"'.$supp.'",tglpesan,"'.$date.'",'.$id.',"R" from replresult where created_by=? limit 1';
                $query = $this->db->query($sql,array($id));
                $sql='select id from po where created = ?';
                $query = $this->db->query($sql,array($date));
                $row = $query->row();
                $sql='insert into po_detail(id_ref,supp,kodeprod,namaprod,banyak,harga,kode_prc,userid) select '.$row->id.',supp,kodeprod,namaprod,unit,harga,kode_prc,'.$id.' from replresult where created_by='.$id.' and supp='.$supp.' and unit>0';
                $query = $this->db->query($sql);
            }break;
            case 'save2':
            {
                $supp=$this->input->post('supp');
                $date=date('Y-m-d H:i:s');
                $created=$this->session->userdata('id');
                $sql='insert into po(company,alamat,email,npwp,userid,supp,tglpesan,created,created_by,tipe) select company,alamat,email,npwp, customerid,"'.$supp.'",tglpesan,"'.$date.'",'.$created.',"R" from repl where tglpesan="'.$key.'" and nocab="'.$id.'" limit 1';
                $query = $this->db->query($sql,array($key,$id));
                $sql='select id from po where created ="'.$date.'"';
                $query = $this->db->query($sql);
                $row = $query->row();
                $sql='insert into po_detail(id_ref,supp,kodeprod,namaprod,banyak,harga,kode_prc,userid) select '.$row->id.',supp,kodeprod,namaprod,unit,harga,kode_prc,'.$created.' from repl where nocab='.$id.' and supp='.$supp.' and unit>0 and tglpesan="'.$key.'"';
                $query = $this->db->query($sql);
            }break;
            case 'show':
            {
                switch($id)
                {
                     case '0':
                     {
                       $sql='select b.nocab,tglpesan,nama_comp from ((select distinct tglpesan,nocab from repl) a inner join tabcomp b using(nocab)) where nocab ="'.$key.'"  order by tglpesan desc';
                     }break;
                     case '1':
                     {
                        $sql='select b.nocab,tglpesan,nama_comp from ((select distinct tglpesan,nocab from repl) a inner join tabcomp b using(nocab)) where tglpesan like "'.$key.'"  order by nama_comp asc';
                     }break;
                     default:
                        $sql='select 1 from mpm.tabcomp where nocab="A"';break;
                }
                $query = $this->db->query($sql);
                $this->total_query = $query->num_rows();
                $sql2=$sql.' limit 20 offset '.$init;
                $query = $this->db->query($sql2);
                if($query->num_rows() > 0)
                {
                    $this->load->library('table');
                    $this->table->set_empty('0');

                    $this->table->set_template($this->tmpl);
                    $this->table->set_caption('Replenishment');

                    $this->table->set_heading('TGL PESAN','DP','DETAIL','PRINT','EXCEL');
                    foreach ($query->result() as $value)
                    {
                        $this->table->add_row(
                             $value->tglpesan
                             ,$value->nama_comp
                             ,'<div div style="text-align:center">'.anchor('trans/repl/show_detail/'.$value->tglpesan.'/'.$value->nocab,img($this->image_properties_detail)).'</div>'
                             ,'<div div style="text-align:center">'.anchor('trans/repl/print/'.$value->tglpesan.'/'.$value->nocab.'/PDF',img($this->image_properties_print)).'</div>'
                             ,'<div div style="text-align:center">'.anchor('trans/repl/print/'.$value->tglpesan.'/'.$value->nocab.'/EXCEL',img($this->image_properties_excel)).'</div>'
                         );
                    }
                    $this->output_table .= $this->table->generate();
                    $this->output_table .= '<br />';
                    $this->table->clear();
                    return $this->output_table;
                 }
                 else
                 {
                    return FALSE;
                 }

            }break;
            case 'print':
            {
                //$sql='select kodeprod,kode_prc,namaprod,sl,avg,stok,stdsl,delta,karton,pesan,unit from repl where nocab ='.$id.' and tglpesan="'.$key.'" order by kodeprod';
                $sql='select kodeprod,kode_prc,namaprod,avg,stok,doi,sl,stdsl,delta,karton,pesan,unit,npesan,nstok from
                    (select a.*,stok/avg*30 as doi,a.stok*b.harga as nstok,a.unit*b.harga as npesan from repl a left join (select a.kodeprod, a.h_dp * (100-d_dp)/100 as harga from prod_detail a inner join tabprod b using(kodeprod)
                        where tgl=(select max(tgl) from prod_detail where kodeprod=a.kodeprod)) b using(kodeprod) where nocab =? and tglpesan=? order by kodeprod
                        )a
                        union 
                        select "TOTAL","","","","","","","","","","","TOTAL",sum(npesan),sum(nstok) from (select a.stok*b.price as nstok,a.unit*b.price as npesan from repl a left join (select a.kodeprod, a.h_dp * (100-d_dp)/100 as price from prod_detail a inner join tabprod b using(kodeprod)
                        where tgl=(select max(tgl) from prod_detail where kodeprod=a.kodeprod)) b using(kodeprod) where nocab =? and tglpesan=? order by kodeprod
                        )b'
                        ;
                $query = $this->db->query($sql,array($id,$key,$id,$key));
                //$query = $this->db->query($sql);
                $dp= strtoupper($this->getCustomer($id));
                switch($init)
                {
                    case 'PDF':
                    {
                        $this->load->library('cezpdf');
                        $this->cezpdf->Cezpdf('A3','portrait');

                        foreach($query->result() as $value)
                        {
                            $db_data[] = array(
                                    'kodeprod'=>$value->kodeprod,
                                    'kode_prc'=>$value->kode_prc,
                                    'namaprod'=>$value->namaprod,
                                    'avg'     =>number_format($value->avg,0),
                                    'stok'    =>number_format($value->stok,0),
                                    'doi'     =>number_format($value->doi,0),
                                    'sl'      =>number_format($value->sl,0),
                                    'stdsl'   =>number_format($value->stdsl,0),
                                    'delta'   =>number_format($value->delta,0),
                                    'karton'  =>number_format($value->karton,0),
                                    'pesan'   =>number_format($value->pesan,0),
                                    'unit'    =>number_format($value->unit,0),
                                    'npesan'  =>number_format($value->npesan,0),
                                    'nstok'   =>number_format($value->nstok,0),
                                );
                        }
                        $col_names = array(
                            'kodeprod'=>'KODE',
                            'kode_prc'=>'KODE PRC',
                            'namaprod'=>'NAMA PRODUK',
                            'avg'=>'AVG',
                            'stok'=>'STOK',
                            'doi'=>'DOI',
                            'sl'=>'SL',
                            'stdsl'=>'STDSL',
                            'delta'=>'DELTA',
                            'karton'=>'KARTON',
                            'pesan'=>'PESAN',
                            'unit'=>'UNIT',
                            'npesan'=>'V. PESAN',
                            'nstok'=>'V. STOK',
                            );

                        $this->cezpdf->ezTable($db_data, $col_names, 'REPLENISHMENT '.$dp.' ('.$key.' )', array('width'=>2,'fontSize' =>7));
                        $this->cezpdf->ezStream();

                    }
                    break;
                    case 'EXCEL':
                    {
                        return to_excel($query,'replenishment_'.$dp.'_'.$key);
                    }break;
                }
            }break;
            case 'show_detail':
            {
                $sql='select a.*,b.harga,stok/avg*30 as doi,a.stok*b.harga as nstok,a.unit*b.harga as npesan from repl a left join (select a.kodeprod, a.h_dp * (100-d_dp)/100 as harga from prod_detail a inner join tabprod b using(kodeprod)
                        where tgl=(select max(tgl) from prod_detail where kodeprod=a.kodeprod)) b using(kodeprod) where nocab =? and tglpesan=? order by kodeprod';
                $query = $this->db->query($sql,array($id,$key));

                if($query->num_rows() > 0)
                {
                    $this->load->library('table');
                    $this->table->set_empty('0');

                    $this->table->set_template($this->tmpl);
                    $this->table->set_caption('Replenishment');

                    $this->table->set_heading('KODE','KODE PRC','NAMA PRODUK','SL','AVG','STOK','STDSL','DELTA','KARTON','PESAN','UNIT','DOI','NILAI PESAN','NILAI STOK');
                    //$this->table->set_heading('DELETE','KODE PRODUK','KODE PRC','NAMA PRODUK','UNIT');
                    $npesan=0;
                    $nstok=0;
                    foreach ($query->result() as $value)
                    {
                        $npesan+=$value->npesan;
                        $nstok+=$value->nstok;
                        if($value->pesan==0)
                        {
                            $this->table->add_row(
                                    //'<div div style="text-align:center">'.anchor('trans/repl/manual_delete/',img($this->image_properties_del),$this->js).'</div>'
                                    $value->kodeprod,
                                    $value->kode_prc,
                                    $value->namaprod,
                                    '<div style="text-align:right">' .number_format($value->sl,0) .'</div>',
                                    '<div style="text-align:right">' .number_format($value->avg,0).'</div>',
                                    '<div style="text-align:right">' .number_format($value->stok,0).'</div>',
                                    '<div style="text-align:right">' .number_format($value->stdsl,0).'</div>',
                                    '<div style="text-align:right">' .number_format($value->delta,0).'</div>',
                                    '<div style="text-align:right">' .number_format($value->karton,0).'</div>',
                                    '<div style="text-align:right">' .number_format($value->pesan,0).'</div>',
                                    '<div style="text-align:right">' .number_format($value->unit,0).'</div>',
                                    '<div style="text-align:right">' .number_format($value->doi,0).'</div>',
                                    '<div style="text-align:right">' .number_format($value->npesan,0).'</div>',
                                    '<div style="text-align:right">' .number_format($value->nstok,0).'</div>'

                            );
                        }
                        else
                        {
                            $this->table->add_row(
                                    //'<div div style="text-align:center">'.anchor('trans/repl/manual_delete/',img($this->image_properties_del),$this->js).'</div>'
                                    '<b>'.$value->kodeprod.'</b>',
                                    '<b>'.$value->kode_prc.'</b>',
                                    '<b>'.$value->namaprod.'</b>',
                                    '<b><div style="text-align:right">' .number_format($value->sl,0) .'</div></b>',
                                    '<b><div style="text-align:right">' .number_format($value->avg,0).'</div></b>',
                                    '<b><div style="text-align:right">' .number_format($value->stok,0).'</div></b>',
                                    '<b><div style="text-align:right">' .number_format($value->stdsl,0).'</div></b>',
                                    '<b><div style="text-align:right">' .number_format($value->delta,0).'</div></b>',
                                    '<b><div style="text-align:right">' .number_format($value->karton,0).'</div></b>',
                                    '<b><div style="text-align:right">' .number_format($value->pesan,0).'</div></b>',
                                    '<b><div style="text-align:right">' .number_format($value->unit,0).'</div></b>',
                                    '<b><div style="text-align:right">' .number_format($value->doi,0).'</div></b>',
                                    '<b><div style="text-align:right">' .number_format($value->npesan,0).'</div></b>',
                                    '<b><div style="text-align:right">' .number_format($value->nstok,0).'</div></b>'
                            );
                        }
                    }
                    $cell=array('data'=>'TOTAL');
                    $this->table->add_row('','','','','','','','','','','','',number_format($npesan,2),number_format($nstok,2));
                    $this->output_table .= $this->table->generate();
                    $this->output_table .= '<br />';
                    $this->table->clear();
                    return $this->output_table;
                    }
                    else
                    {
                        return FALSE;
                    }
            }break;
            case 'list_upload':
            {
                $limit=20;
                $sql1=
                '   select * from (
                    select substr(filename,3,2) as nocab,b.userid,b.id,a.company,date_format((lastupload),"%d %M %Y, %T") as last,filename,flag from mpm.user a
                    right join (select id,userid,lastupload,filename,flag from mpm.upload order by lastupload desc) b on a.id=b.userid order by lastupload desc
                    ) a inner join (select nocab,nama_comp from mpm.tabcomp) b using(nocab) where userid like "'.$id.'"';

                $query=$this->db->query($sql1);
                $this->total_query = $query->num_rows();
                $sql2= $sql1.' limit ? offset ?';
                $query = $this->db->query($sql2,array($limit,$key));
                if($query->num_rows()>0)
                {
                $this->load->library('table');
                $this->table->set_empty('0');
                $this->table->set_empty('0');
                $this->table->set_template($this->tmpl);
                $this->table->set_caption('LIST UPLOAD');
                $this->table->set_heading('LAST UPLOAD','UPLOAD BY','DP','FILE UPLOAD','REPLENISHMENT');
                $image_properties=array(
                         'src'    => 'assets/css/images/detail.gif',
                    );

                foreach($query->result() as $value)
                {
                     switch($value->flag)
                     {
                        case 0:
                         $this->table->add_row(
                                '<b>'.$value->last.'</b>'
                                ,'<b>'.$value->company.'</b>'
                                ,'<b>'.$value->nama_comp.'</b>'
                                ,'<b>'.$value->filename.'</b>'
                                ,'<div div style="text-align:center">'.anchor('trans/repl/detail/'.$value->id.'/'.$value->nocab.'/'.$value->userid,img($image_properties)).'</div>'
                                );break;
                        case 1:
                           $this->table->add_row(
                                $value->last
                                ,$value->company
                                ,$value->nama_comp
                                ,$value->filename
                                ,'<div div style="text-align:center">'.anchor('trans/repl/detail/'.$value->id.'/'.$value->nocab.'/'.$value->userid,img($image_properties)).'</div>'
                                );break;

                     }
                }

                $this->output_table .= '<br />';
                $this->output_table .= $this->table->generate();
                $this->output_table .= '<br />';
                $this->table->clear();
                $query->free_result();
                return $this->output_table;
                }
                else
                    return false;
            }break;
        }
    }
    public function spk($state='show_supp',$key=null,$id=null)
    {
        switch(strtolower($state))
        {
            case 'check_order':
            {
                $userid=$this->session->userdata('id');
                $sql="select a.id,a.supp,b.company,
                    date_format(tglpesan,'%d %M %Y, %T') as tglpesan,
                    nopo, date_format(tglpo,'%d %M %Y, %T') as tglpo,
                    nodo, date_format(tgldo,'%d %M %Y, %T') as tgldo
                    from mpm.po a inner join mpm.user b on a.userid=b.id where userid=".$userid." and a.created_by = ".$userid." and deleted=0 order by tglpesan desc";
                $query=$this->db->query($sql);

                $this->total_query = $query->num_rows();
                $sql2= $sql.' limit ? offset ?';
                $query = $this->db->query($sql2,array($key,$id));


                //echo $key;
                if($query->num_rows()>0)
                {
                    $image_properties=array(
                         'src'    => 'assets/css/images/detail.gif',
                    );
                    $this->load->library('table');
                    $this->table->set_empty('0');

                    $this->table->set_empty('0');
                    $this->table->set_template($this->tmpl);
                    $this->table->set_caption('ORDER LIST');
                    $this->table->set_heading('NO ORDER','COMPANY','ORDER DATE', 'NO PO','PO DATE','PRINT');
                    foreach ($query->result() as $value)
                    {
                        $js=array('onclick'=>"return confirm('Are you sure you want to delete?')");
                        $tglpo=$value->tglpo;
                        $image_print_po = ($value->tglpo!='') ?
                                '<div div style="text-align:center">'.anchor('trans/po/print/'.$value->id,img($this->image_properties_print)).'</div>' :
                                '<div div style="text-align:center">'.img($this->image_properties_print_off).'</div>';
                        $image_email_po = ($value->tglpo!='') ?
                                '<div div style="text-align:center">'.anchor('trans/po/email/'.$value->id,img($this->image_properties_email)).'</div>' :
                                '<div div style="text-align:center">'.img($this->image_properties_email_off).'</div>';
                        $this->table->add_row(
                                //'<div div style="text-align:center">'.anchor('trans/po/delete/'.$value->id,img($this->image_properties_del),$js).'</div>'
                                'ODR'.str_pad($value->id,6, '0', STR_PAD_LEFT)
                                ,$value->company
                                ,$value->tglpesan
                                ,$value->nopo
                                ,$value->tglpo

                                //,'<div div style="text-align:center">'.anchor('trans/po/show_detail/'.$value->id.'/'.$value->supp,img($image_properties)).'</div>'
                                ,'<div div style="text-align:center">'.anchor('trans/po/print/'.$value->id,img($this->image_properties_print)).'</div>'
                                //,'<div div style="text-align:center">'.anchor('trans/po_delete/'.$value->nopesan,img($image_properties_del),$js).'</div>'
                               );
                    }

                    $this->output_table .= $this->table->generate();
                    $this->output_table .= '<br />';
                    $this->table->clear();
                    $query->free_result();
                    $this->output_table;
                    return $this->output_table;
                }
                else
                {
                    return FALSE;
                }
            }break;
            case 'delete_temp':
            {
                $id=$this->session->userdata('id');
                $sql='delete from mpm.po_temp where id=?';
                $query=$this->db->query($sql,array($id));
            }break;
            case 'show_temp':
            {
                $id=$this->session->userdata('id');
                $supp = $this->session->userdata('supplier');
                $sql1='select * from mpm.po_temp where userid=? and supp=? order by kodeprod';
                $query = $this->db->query($sql1,array($id,$supp));
                $this->total_query = $query->num_rows();
                //$sql2= $sql1.' limit ? offset ?';
                //$query = $this->db->query($sql2,array($limit,$offset));

                if($query->num_rows() > 0)
                {
                    $this->load->library('table');
                    $this->table->set_empty('0');

                    $this->table->set_empty('0');
                    $this->table->set_template($this->tmpl);
                    $this->table->set_caption('PURCHASE ORDER');
                    $this->table->set_heading('CODE','PRODUCT', 'PRC CODE','AMOUNT','DELETE');
                    foreach ($query->result() as $value)
                    {
                        $js=array('onclick'=>"return confirm('Are you sure you want to delete?')");
                        $this->table->add_row(
                                $value->kodeprod
                                ,$value->namaprod
                                ,$value->kode_prc
                                ,number_format($value->banyak)
                                ,anchor('trans/spk/delete/'.$value->id,img($this->image_properties_del),$js)
                        );
                    }

                    $this->output_table .= $this->table->generate();
                    $this->output_table .= '<br />';
                    $this->table->clear();
                    $query->free_result();
                    $this->output_table;
                    return $this->output_table;
                }
                else
                {
                    return FALSE;
                }
            }break;
            case 'addtemp':
            {
                $id=$this->session->userdata('id');
                //$this->db->query('delete from po_temp where userid='.$id);
                $kodeprod=$this->input->post('product');
                $query=$this->getProduct($kodeprod);
                $row=$query->row();
                $post['userid']=$id;
                $post['kodeprod']=$kodeprod;
                $post['kode_prc']=$row->kode_prc;
                $post['namaprod']=$row->namaprod;
                $post['banyak']  =$this->input->post('amount')*$row->isisatuan;
                $post['harga']   =$this->getHarga($kodeprod,$id);
                $post['supp']    =$row->supp;
                //$post['tgldokjdi']=date('Y-m-d H:i:s');
                $this->db->insert('mpm.po_temp',$post);
                //$temp= $this->out_sales($id);
                //return $temp;
                redirect('trans/spk/add');
            }break;
            case 'delete':
            {
                $sql='delete from mpm.po_temp where id=? and userid=?';
                $this->db->trans_begin();

                $this->db->query($sql,array($key,$this->session->userdata('id')));
                $this->db->trans_complete();
                if($this->db->trans_status()===FALSE)
                {
                    $this->db->trans_rollback();
                }
                else
                {
                    $this->db->trans_commit();
                }
                redirect('trans/spk/add');
            }break;
            case 'save'://purchase
            {
                switch($this->input->post('alamat'))
                {
                    case 'new':
                        $alamat=$this->input->post('alamatlain');break;
                    case 'saved':
                        $alamat=$this->input->post('alamatsaved');break;
                }
                
                $supp=$this->session->userdata('supplier');
                $id=$this->session->userdata('id');
                $userid=$this->session->userdata('id');
                $row=$this->getCustInfo2($id);
                $post['created_by'] =$userid;
                $post['created']=date('Y-m-d H:i:s');
                $post['tglpesan']=date('Y-m-d H:i:s');
                $post['userid']= $id;
                $post['supp']=$this->session->userdata('supplier');
                $post['tipe']='S';
                $post['alamat']=$alamat;
                $post['company']=$row->company;
                $post['email']=$row->email;
                $post['npwp']=$row->npwp;

                $tglpesan=date('Y-m-d H:i:s');

                $this->db->trans_begin();
                $this->db->insert('mpm.po',$post);
                $query=$this->db->query("select id from mpm.po where created='".$post['created']."' and userid=".$id);
                $row=$query->row();
                $sql='insert into mpm.po_detail(id_ref,supp,kodeprod,namaprod,banyak,harga,kode_prc,userid) select '.$row->id.',supp,kodeprod,namaprod,sum(banyak),sum(harga),kode_prc,userid from mpm.po_temp where userid='.$id.' and supp='.$supp.' group by kodeprod';
                $this->db->query($sql);
                $this->db->query('delete from mpm.po_temp where userid=?',array($id));
                $this->db->trans_complete();
                if($this->db->trans_status()===FALSE)
                {
                    $this->db->trans_rollback();
                }
                else
                {
                    $this->db->trans_commit();
                }
                redirect('trans/spk/check_order');
            }break;
        }
    }
    private function generate_faktur($value=null)
    {
        $server='192.168.1.2';
        $user='root';
        $pass='mpm123';
        $db='mpm';
        $this->load->library('PHPJasperXML');
        $xml = simplexml_load_file("assets/report/trans/faktur_komersil_1.jrxml");
        @$this->phpjasperxml->debugsql=false;
        @$this->phpjasperxml->arrayParameter=array('id'=>substr($value,0,strlen($value)-9));
        @$this->phpjasperxml->xml_dismantle($xml);
        @$this->phpjasperxml->transferDBtoArray($server,$user,$pass,$db);
                        //@$this->phpjasperxml->outpage("I");
        
    }
    public function permit($state='show',$key="",$id=null,$init=null)
    {
        $userid=$this->session->userdata('id');
        switch(strtolower($state))
        {
            case 'download_email':
            {
                delete_files('./assets/faktur');
                
                    $key=trim($key);
                    $server='192.168.1.2';
                    $user='root';
                    $pass='mpm123';
                    $db='mpm';
                  
                    $this->load->library('PHPJasperXML');
                    $xml_fk = simplexml_load_file("assets/report/trans/faktur_komersil_email.jrxml");
                    $xml_fp = simplexml_load_file("assets/report/trans/faktur_pajak_email.jrxml");

                    $PHPJasperXML = new PHPJasperXML();
                    $PHPJasperXML->debugsql=false;
                    $PHPJasperXML->arrayParameter=array('id'=>$key);
                    $PHPJasperXML->xml_dismantle($xml_fk);
                    $PHPJasperXML->transferDBtoArray($server,$user,$pass,$db);
                    //$PHPJasperXML->outpage("I");*/
                    $PHPJasperXML->outpage("F",'assets/faktur/FK_'.$key.'.pdf');
                      
                    $this->zip->read_file('assets/faktur/FK_'.$key.'.pdf');
                         
                    $PHPJasperXML = new PHPJasperXML();
                    $PHPJasperXML->debugsql=false;
                    $PHPJasperXML->arrayParameter=array('key'=>$key);
                    $PHPJasperXML->xml_dismantle($xml_fp);
                    $PHPJasperXML->transferDBtoArray($server,$user,$pass,$db);
                    //$PHPJasperXML->outpage("I");
                    $PHPJasperXML->outpage("F",'assets/faktur/pajak_'.$key.'.pdf');
                       
                    $this->zip->read_file('assets/faktur/pajak_'.$key.'.pdf');
                   
                    $filename=md5(date("d-m-Y H:i:s")).'.zip';
                    $this->zip->download($filename);
                    
            }break;
            case 'email_faktur':
            {
                delete_files('./assets/faktur');
                
                    
                    $server='192.168.1.2';
                    $user='root';
                    $pass='mpm123';
                    $db='mpm';
                    
                    $this->load->library('PHPJasperXML');
                    $xml_fk = simplexml_load_file("assets/report/trans/faktur_komersil_email.jrxml");
                    $xml_fp = simplexml_load_file("assets/report/trans/faktur_pajak_email.jrxml");

                    $this->email_config();
                    $this->email->from('muliaputramandiri@gmail.com', "PT. Mulia Putra Mandiri");
                    
                    //$this->email->to($this->getEmailSupp(substr($id,-3)));//$this->email->to('one@example.com, two@example.com, three@example.com');
                    $this->email->to($this->getEmailFinanceFaktur(substr($id,1)));
                    //$this->email->to('divine.ogre@gmail.com');
                    $list = array("yunitasasmita@gmail.com");
                    $this->email->cc($list);//'boedhi.harjanto@gmail.com','henryjoseph760@gmail.com','herman_oscar@yahoo.com');
                    //$this->email->bcc('them@their-example.com');

                    $this->email->subject("Faktur Pajak dan Faktur Penjualan");
                    $this->email->message("This Email is sent by system,"."\r\n"."\r\n"."Process By Yunita");
                    
                    $PHPJasperXML = new PHPJasperXML();
                    $PHPJasperXML->debugsql=false;
                    $PHPJasperXML->arrayParameter=array('id'=>$key);
                    $PHPJasperXML->xml_dismantle($xml_fk);
                    $PHPJasperXML->transferDBtoArray($server,$user,$pass,$db);
                    //$PHPJasperXML->outpage("I");
                    $PHPJasperXML->outpage("F",'assets/faktur/FK_'.$key.'.pdf');
                      
                    $this->zip->read_file('assets/faktur/FK_'.$key.'.pdf');
                         
                    $PHPJasperXML = new PHPJasperXML();
                    $PHPJasperXML->debugsql=false;
                    $PHPJasperXML->arrayParameter=array('key'=>$key);
                    $PHPJasperXML->xml_dismantle($xml_fp);
                    $PHPJasperXML->transferDBtoArray($server,$user,$pass,$db);
                    $PHPJasperXML->outpage("F",'assets/faktur/pajak_'.$key.'.pdf');
                       
                    $this->zip->read_file('assets/faktur/pajak_'.$key.'.pdf');
                   
                    $filename=md5(date("d-m-Y H:i:s")).'.zip';
                    $this->zip->archive('assets/faktur/'.$filename);
                    $this->email->attach('assets/faktur/'.$filename);
                    $this->email->send();
               
            }break;
            case 'show_faktur':
            {
                if(strlen($key)==10)
                //$sql="select * from pusat.permit a  where tanggal like '%".$key."%' and a.deleted=0 order by a.id desc";
                $sql="select customerid,nama_customer,no_sales,no_seri_pajak,ref, date_format(tanggal,'%d %M %Y') tanggal from dbsls.t_sales_master a inner join dbsls.m_customer b using(customerid) 
                    where date_format(tanggal,'%Y-%m-%d')='".$key."'";
                else if(strlen($key)==4)
                $sql="select * from pusat.permit_detail a inner join pusat.permit b on a.id_ref=b.id  where left(faktur,4)=".$key." and b.deleted=0";
                else
                $sql="select * from pusat.permit_detail a inner join pusat.permit b on a.id_ref=b.id  where left(faktur,8)=".$key." and b.deleted=0";
                
                $query=$this->db->query($sql);
                $this->total_query = $query->num_rows();
                $sql2= $sql.' limit ? offset ?';
                $query = $this->db->query($sql2,array(10,$id));
                if($query->num_rows()>0)
                {
                    $this->load->library('table');
                    $this->table->set_empty('0');

                    $this->table->set_empty('0');
                    $this->table->set_template($this->tmpl);
                    $this->table->set_caption('Tanda Terima');
                    $this->table->set_heading('Client','Tanggal','No Sales','No Seri Pajak','No. DO','Download','Email');
                    foreach ($query->result() as $value)
                    {
                        //$bulan=substr($value->faktur,-2,2).substr($value->faktur,-4,2);
                        $js=array('onclick'=>"return confirm('Are you sure you want to delete?')");

                        $this->table->add_row(
                                //'<div style="text-align:center">'.anchor('trans/permit/delete/'.$value->id,img($this->image_properties_del),$js).'</div>'
                                $value->nama_customer
                                ,$value->tanggal
                                ,$value->no_sales
                                ,$value->no_seri_pajak
                                ,$value->ref
                                ,'<div style="text-align:center">'.anchor('trans/permit/download_email/'.$value->no_seri_pajak.'/'.$value->customerid,img($this->image_properties_download)).'</div>'
                                ,'<div style="text-align:center">'.anchor('trans/permit/email_faktur/'.$value->no_seri_pajak.'/'.$value->customerid,img($this->image_properties_email)).'</div>'
                                );
                    }

                    $this->output_table .= $this->table->generate();
                    $this->output_table .= '<br />';
                    $this->table->clear();
                    $query->free_result();
                    $this->output_table;
                    return $this->output_table;
                }
                else
                {
                    return FALSE;
                }
            }break;
            case 'amplop':
            {
                $server='192.168.1.2';
                $user='root';
                $pass='mpm123';
                $db='mpm';
                $this->load->library('PHPJasperXML');
                $xml = simplexml_load_file("assets/report/trans/amplop.jrxml");
                @$this->phpjasperxml->debugsql=false;
                @$this->phpjasperxml->arrayParameter=array('id'=>substr($key,1));
                @$this->phpjasperxml->xml_dismantle($xml);

                @$this->phpjasperxml->transferDBtoArray($server,$user,$pass,$db);
                @$this->phpjasperxml->outpage("D",$key.'.pdf');
            }break;
            case 'amplop_coklat':
            {
                $server='192.168.1.2';
                $user='root';
                $pass='mpm123';
                $db='pusat';
                $this->load->library('PHPJasperXML');
                $xml = simplexml_load_file("assets/report/trans/amplop_coklat.jrxml");
                @$this->phpjasperxml->debugsql=false;
                @$this->phpjasperxml->arrayParameter=array('id'=>substr($key,1));
                @$this->phpjasperxml->xml_dismantle($xml);

                @$this->phpjasperxml->transferDBtoArray($server,$user,$pass,$db);
                @$this->phpjasperxml->outpage("D",$key.'.pdf');
                //@$this->phpjasperxml->outpage("I");
            }break;
            case 'print_rekap':
            {
                $startdate=$this->input->post('startdate');
                $enddate=$this->input->post('enddate');
                $sql = "SELECT tanggal,nama_lang, keterangan FROM pusat.permit a  inner join pusat.permit_detail b on a.id=b.id_ref  where tanggal BETWEEN '".$startdate."' and '".$enddate."' group by a.id order by tanggal";
                //$sql='select * from pusat.permit where tanggal between "'.$startdate.'" and "'.$enddate.'"';
                $query=$this->db->query($sql);
                return to_excel($query);
            }break;
            case 'delete':
            {

                $this->db->trans_begin();
                $sql='update pusat.permit set deleted = 1,modified_by='.$userid.',modified="'.date('Y-m-d H:i:s').'" where id='.$key;
                $this->db->query($sql,array($key));
                $sql='update pusat.permit_detail set deleted=1 where id_ref='.$key;
                $this->db->query($sql,array($key));
                $this->db->trans_complete();
                if($this->db->trans_status()===FALSE)
                {
                    $this->db->trans_rollback();
                }
                else
                {
                    $this->db->trans_commit();
                }

            }break;
            case 'show':
            {
                if(strlen($key)==10)
                //$sql="select * from pusat.permit a  where tanggal like '%".$key."%' and a.deleted=0 order by a.id desc";
                $sql="select DISTINCT a.id, kode_lang,nama_lang,tanggal,keterangan from pusat.permit a inner join pusat.permit_detail b on a.id=b.id_ref where tanggal like '%".$key."%' and a.deleted=0 order by keterangan,a.id desc";
                
                else if(strlen($key)==4)
                $sql="select * from pusat.permit_detail a inner join pusat.permit b on a.id_ref=b.id  where left(faktur,4)=".$key." and b.deleted=0";
                else
                $sql="select * from pusat.permit_detail a inner join pusat.permit b on a.id_ref=b.id  where left(faktur,8)=".$key." and b.deleted=0";
                
                $query=$this->db->query($sql);
                $this->total_query = $query->num_rows();
                $sql2= $sql.' limit ? offset ?';
                $query = $this->db->query($sql2,array(10,$id));
                if($query->num_rows()>0)
                {
                    $this->load->library('table');
                    $this->table->set_empty('0');

                    $this->table->set_empty('0');
                    $this->table->set_template($this->tmpl);
                    $this->table->set_caption('Tanda Terima');
                    $this->table->set_heading('Delete','Client','Keterangan','Tanggal','Print','Amplop K.','Amplop B.','DL','Email');
                    foreach ($query->result() as $value)
                    {
                        //$bulan=substr($value->faktur,-2,2).substr($value->faktur,-4,2);
                        $js=array('onclick'=>"return confirm('Are you sure you want to delete?')");

                        $this->table->add_row(
                                '<div style="text-align:center">'.anchor('trans/permit/delete/'.$value->id,img($this->image_properties_del),$js).'</div>'
                                ,$value->nama_lang
                                ,$value->keterangan
                                ,$value->tanggal
                                //,($value->keterangan=='Copy Faktur'?'0':'1')
                                ,'<div style="text-align:center">'.anchor_popup('trans/permit/print/'.$value->id.'/'.($value->keterangan=='Copy Faktur'?0:1),img($this->image_properties_print)).'</div>'
                                ,'<div style="text-align:center">'.anchor_popup('trans/permit/amplop/'.$value->kode_lang,img($this->image_properties_print)).'</div>'
                                ,'<div style="text-align:center">'.anchor_popup('trans/permit/amplop_coklat/'.$value->kode_lang,img($this->image_properties_print)).'</div>'
                                ,'<div style="text-align:center">'.anchor('trans/permit/download/'.$value->id,img($this->image_properties_download)).'</div>'
                                ,'<div style="text-align:center">'.anchor('trans/permit/email/'.$value->id.'/'.$value->kode_lang,img($this->image_properties_email)).'</div>'
                                );
                    }

                    $this->output_table .= $this->table->generate();
                    $this->output_table .= '<br />';
                    $this->table->clear();
                    $query->free_result();
                    $this->output_table;
                    return $this->output_table;
                }
                else
                {
                    return FALSE;
                }
            }break;
            case 'delete_temp':
            {
                $sql='delete from pusat.permit_temp where id='.$key;
                $this->db->query($sql);
            }break;
            case 'save':
            {
               $sql='select customerid,group_descr from dbsls.m_customer where group_id="'.$key.'"';
               $query=$this->db->query($sql);
               $row=$query->row();
               $post['kode_lang']=$row->customerid;
               $post['nama_lang']=$row->group_descr;
               $post['tanggal']=$this->input->post('tanggal');
               $post['created_by']=$userid;
               $post['created']=date('Y-m-d H:i:s');
               $this->db->trans_begin();
               $insert=$this->db->insert('pusat.permit',$post);
               $sql='select id from pusat.permit where created="'.$post['created'].'"';
               $query=$this->db->query($sql);
               $row=$query->row();
               $id_ref=$row->id;
               $sql="insert into pusat.permit_detail(id_ref,faktur,nildok,pajak,keterangan) select ".$id_ref.",faktur,nildok,pajak,keterangan from pusat.permit_temp where userid=".$userid;
               $this->db->query($sql);
               $sql='delete from pusat.permit_temp where userid='.$userid;
               $this->db->query($sql);
               if($this->db->trans_status()===FALSE)
                {
                    $this->db->trans_rollback();
                }
                else
                {
                    $this->db->trans_commit();
                }
             }break;
            case 'add':
            {

                $row=$this->get_faktur($this->input->post('faktur'));
                $post['faktur']=$row->nomor;
                $post['nildok']=$row->nildok;
                $post['keterangan']=$this->input->post('keterangan');
                $post['pajak']=$row->pajak;
                $post['userid']=$userid;

                $this->db->trans_begin();
                $sql=$this->db->insert('pusat.permit_temp',$post);
                if($this->db->trans_status()===FALSE)
                {
                    $this->db->trans_rollback();
                }
                else
                {
                    $this->db->trans_commit();
                }
            }break;
            case 'print':
            {
                $server='192.168.1.2';
                $user='root';
                $pass='mpm123';
                $db='mpm';
                $this->load->library('PHPJasperXML');
                //$xml = simplexml_load_file("assets/report/trans/permit.jrxml");
                $xml='';
                 if($id==1)
                 {
                      $xml = simplexml_load_file("assets/report/trans/permit_lunas.jrxml");
                 }
                 else
                 {
                      $xml = simplexml_load_file("assets/report/trans/permit_copy.jrxml");
                 }
                 
                 
                @$this->phpjasperxml->debugsql=false;
                @$this->phpjasperxml->arrayParameter=array('id'=>$key);
                @$this->phpjasperxml->xml_dismantle($xml);

                @$this->phpjasperxml->transferDBtoArray($server,$user,$pass,$db);
                @$this->phpjasperxml->outpage("D",$key.'.pdf');

            }break;
            case 'print_range':
            {
                $server='192.168.1.2';
                $user='root';
                $pass='mpm123';
                $db='mpm';
                $this->load->library('PHPJasperXML');
                //$xml = simplexml_load_file("assets/report/trans/permit_range.jrxml");
                
                 if($id==1)
                 {
                      $xml = simplexml_load_file("assets/report/trans/permit_range_lunas.jrxml");
                 }
                 else
                 {
                      $xml = simplexml_load_file("assets/report/trans/permit_range_copy.jrxml");
                 }
                @$this->phpjasperxml->debugsql=false;
                @$this->phpjasperxml->arrayParameter=array('id'=>$key);
                @$this->phpjasperxml->xml_dismantle($xml);

                @$this->phpjasperxml->transferDBtoArray($server,$user,$pass,$db);
                @$this->phpjasperxml->outpage("D",$key.'.pdf');

            }break;
            case 'download_baru':
            {
                $server='192.168.1.2';
                $user='root';
                $pass='mpm123';
                $db='mpm';
                $this->load->library('PHPJasperXML');
                echo$xml = simplexml_load_file("assets/report/trans/faktur_komersil_baru.jrxml");
                @$this->phpjasperxml->debugsql=false;
                @$this->phpjasperxml->arrayParameter=array('id'=>$key,'bulan'=>$id);

                @$this->phpjasperxml->xml_dismantle($xml);

                @$this->phpjasperxml->transferDBtoArray($server,$user,$pass,$db);
                @$this->phpjasperxml->outpage("I");
            }
            case 'download':
            {
                delete_files('./assets/faktur');
                $sql="select if(tanggal>='2013-08-01',1,0) flag,kode_lang,faktur, pajak from pusat.permit a inner join pusat.permit_detail b on a.id=b.id_ref where id_ref=".$key." and keterangan='Copy Faktur'";
                $query=$this->db->query($sql,array($key));
                if($query->num_rows() > 0)
                {
                    $row=$query->row();

                    $server='192.168.1.2';
                    $user='root';
                    $pass='mpm123';
                    $db='mpm';
                    $this->load->library('PHPJasperXML');
                    $xml_fk = simplexml_load_file("assets/report/trans/faktur_komersil_baru.jrxml");
                    $xml_fp = simplexml_load_file("assets/report/trans/faktur_pajak_baru.jrxml");
                    $xml_fkl = simplexml_load_file("assets/report/trans/faktur_komersil.jrxml");
                    $xml_fpl = simplexml_load_file("assets/report/trans/faktur_pajak.jrxml");
            
                    foreach ($query->result() as $value)
                    {
                         if($value->flag)
                         {    
                            $PHPJasperXML = new PHPJasperXML();
                            $PHPJasperXML->debugsql=false;
                            $PHPJasperXML->arrayParameter=array('id'=>substr($value->faktur,0,strlen($value->faktur)-9),'bulan'=>substr($value->faktur,-2,2).substr($value->faktur,-4,2));
                            $PHPJasperXML->xml_dismantle($xml_fk);
                            $PHPJasperXML->transferDBtoArray($server,$user,$pass,$db);
                            $PHPJasperXML->outpage("F",'assets/faktur/'.str_replace('/','_', $value->faktur).'.pdf');
                           //$this->email->attach('assets/faktur/'.str_replace('/','_', $value->faktur).'.pdf');
                            $this->zip->read_file('assets/faktur/'.str_replace('/','_', $value->faktur).'.pdf');

                            $PHPJasperXML = new PHPJasperXML();
                            $PHPJasperXML->debugsql=false;
                            $PHPJasperXML->arrayParameter=array('id'=>substr($value->pajak,0,strlen($value->pajak)-5),'bulan'=>substr($value->faktur,-2,2).substr($value->faktur,-4,2));
                            $PHPJasperXML->xml_dismantle($xml_fp);
                            $PHPJasperXML->transferDBtoArray($server,$user,$pass,$db);
                            $PHPJasperXML->outpage("F",'assets/faktur/'.str_replace('/','_', $value->pajak).'.pdf');
                            $this->zip->read_file('assets/faktur/'.str_replace('/','_', $value->pajak).'.pdf');
                         }
                         else {
                            $PHPJasperXML = new PHPJasperXML();
                            $PHPJasperXML->debugsql=false;
                            $PHPJasperXML->arrayParameter=array('id'=>substr($value->faktur,0,strlen($value->faktur)-9),'bulan'=>substr($value->faktur,-2,2).substr($value->faktur,-4,2));
                            $PHPJasperXML->xml_dismantle($xml_fkl);
                            $PHPJasperXML->transferDBtoArray($server,$user,$pass,$db);
                            $PHPJasperXML->outpage("F",'assets/faktur/'.str_replace('/','_', $value->faktur).'.pdf');
                           //$this->email->attach('assets/faktur/'.str_replace('/','_', $value->faktur).'.pdf');
                            $this->zip->read_file('assets/faktur/'.str_replace('/','_', $value->faktur).'.pdf');

                            $PHPJasperXML = new PHPJasperXML();
                            $PHPJasperXML->debugsql=false;
                            $PHPJasperXML->arrayParameter=array('id'=>substr($value->pajak,0,strlen($value->pajak)-5),'bulan'=>substr($value->faktur,-2,2).substr($value->faktur,-4,2));
                            $PHPJasperXML->xml_dismantle($xml_fpl);
                            $PHPJasperXML->transferDBtoArray($server,$user,$pass,$db);
                            $PHPJasperXML->outpage("F",'assets/faktur/'.str_replace('/','_', $value->pajak).'.pdf');
                            $this->zip->read_file('assets/faktur/'.str_replace('/','_', $value->pajak).'.pdf');
                         }
                    }
                    $filename=md5(date("d-m-Y H:i:s")).'.zip';
                    $this->zip->download($filename);
                }
                else
                {
                    return false;
                }
            }break;
            case 'email':
            {
                delete_files('./assets/faktur');
                $sql="select kode_lang,faktur, pajak from pusat.permit a inner join pusat.permit_detail b on a.id=b.id_ref where id_ref=? and keterangan='Copy Faktur'";
                $query=$this->db->query($sql,array($key));
                if($query->num_rows() > 0)
                {
                    $row=$query->row();

                    $server='192.168.1.2';
                    $user='root';
                    $pass='mpm123';
                    $db='mpm';
                    $this->load->library('PHPJasperXML');
                    $xml_fk = simplexml_load_file("assets/report/trans/faktur_komersil_baru.jrxml");
                    $xml_fp = simplexml_load_file("assets/report/trans/faktur_pajak_baru.jrxml");

                    $this->email_config();
                    $this->email->from('muliaputramandiri@gmail.com', "PT. Mulia Putra Mandiri");
                    
                    //$this->email->to($this->getEmailSupp(substr($id,-3)));//$this->email->to('one@example.com, two@example.com, three@example.com');
                    $this->email->to($this->getEmailFinanceFaktur(substr($row->kode_lang,1)));
                    $list = array("yunitasasmita@gmail.com");
                    $this->email->cc($list);//'boedhi.harjanto@gmail.com','henryjoseph760@gmail.com','herman_oscar@yahoo.com');
                    //$this->email->bcc('them@their-example.com');

                    $this->email->subject("Faktur Pajak dan Faktur Penjualan");
                    $this->email->message("This Email is sent by system,"."\r\n"."\r\n"."Process By Yunita"."\r\n"."\r\n"."Note : Mohon agar Faktur Pajak dapat di abaikan karena per tgl 1 Juli sudah menggunakan E-Faktur");

                    foreach ($query->result() as $value)
                    {
                         $PHPJasperXML = new PHPJasperXML();
                         $PHPJasperXML->debugsql=false;
                         $PHPJasperXML->arrayParameter=array('id'=>substr($value->faktur,0,strlen($value->faktur)-9),'bulan'=>substr($value->faktur,-2,2).substr($value->faktur,-4,2));
                         $PHPJasperXML->xml_dismantle($xml_fk);
                         $PHPJasperXML->transferDBtoArray($server,$user,$pass,$db);
                         $PHPJasperXML->outpage("F",'assets/faktur/'.str_replace('/','_', $value->faktur).'.pdf');
                         
                         
                         //$this->email->attach('assets/faktur/'.str_replace('/','_', $value->faktur).'.pdf');
                         $this->zip->read_file('assets/faktur/'.str_replace('/','_', $value->faktur).'.pdf');
                         $PHPJasperXML = new PHPJasperXML();
                         $PHPJasperXML->debugsql=false;
                         $PHPJasperXML->arrayParameter=array('id'=>substr($value->pajak,0,strlen($value->pajak)-5),'bulan'=>substr($value->faktur,-2,2).substr($value->faktur,-4,2));
                         $PHPJasperXML->xml_dismantle($xml_fp);
                         $PHPJasperXML->transferDBtoArray($server,$user,$pass,$db);
                         $PHPJasperXML->outpage("F",'assets/faktur/'.str_replace('/','_', $value->pajak).'.pdf');
                         $this->zip->read_file('assets/faktur/'.str_replace('/','_', $value->pajak).'.pdf');
                         
                    }
                    $filename=md5(date("d-m-Y H:i:s")).'.zip';
                    $this->zip->archive('assets/faktur/'.$filename);
                    $this->email->attach('assets/faktur/'.$filename);
                    $this->email->send();
                }
                else
                {
                    return false;
                }
            }break;
            case 'show_add':
            {
                $sql='select * from pusat.permit_temp where userid='.$this->session->userdata('id');
                $query=$this->db->query($sql);
                if($query->num_rows()>0)
                {
                    $this->load->library('table');
                    $this->table->set_empty('0');

                    $this->table->set_empty('0');
                    $this->table->set_template($this->tmpl);
                    $this->table->set_caption('Surat Jalan');
                    $this->table->set_heading('Faktur No.', 'Rp,-','Faktur Pajak No','Keterangan','Delete');
                    foreach ($query->result() as $value)
                    {
                        $js=array('onclick'=>"return confirm('Are you sure you want to delete?')");

                        $this->table->add_row(
                                $value->faktur
                                ,'<div  style="text-align:right">'.number_format($value->nildok,2).'</div>'
                                ,$value->pajak
                                ,$value->keterangan
                                ,'<div style="text-align:center">'.anchor('trans/permit/delete_temp/'.$value->id,img($this->image_properties_del),$js).'</div>'
                                );
                    }

                    $this->output_table .= $this->table->generate();
                    $this->output_table .= '<br />';
                    $this->table->clear();
                    $query->free_result();
                    $this->output_table;
                    return $this->output_table;
                }
                else
                {
                    return FALSE;
                }
     
            }break;
        }
    }
    public function sj($state='show',$key=0,$id=null,$init=null)
    {
        $userid=$this->session->userdata('id');
        switch(strtolower($state))
        {

            case 'print_range':
            {
                $awal=str_replace('-','',$this->input->post('awal'));
                $akhir=str_replace('-','',$this->input->post('akhir'));
                $server='192.168.1.2';
                $user='root';
                $pass='mpm123';
                $db='mpm';
                $this->load->library('PHPJasperXML');
                echo$xml = simplexml_load_file("assets/report/akun/tanda_terima_range.jrxml");
                @$this->phpjasperxml->debugsql=false;
                @$this->phpjasperxml->arrayParameter=array('awal'=>$awal,'akhir'=>$akhir);
                @$this->phpjasperxml->xml_dismantle($xml);

                @$this->phpjasperxml->transferDBtoArray($server,$user,$pass,$db);
                @$this->phpjasperxml->outpage("I");
            }break;
            case 'show':
            {
                $sql="select * from mpm.sj where tanggal like '%".$key."%' and deleted=0 order by nomor desc";
                $query=$this->db->query($sql);
                $this->total_query = $query->num_rows();
                $sql2= $sql.' limit ? offset ?';
                $query = $this->db->query($sql2,array(10,$id));
                if($query->num_rows()>0)
                {
                    $this->load->library('table');
                    $this->table->set_empty('0');

                    $this->table->set_empty('0');
                    $this->table->set_template($this->tmpl);
                    $this->table->set_caption('Tanda Terima');
                    $this->table->set_heading('Delete','Nomor', 'Tanggal','Print');
                    foreach ($query->result() as $value)
                    {
                        $js=array('onclick'=>"return confirm('Are you sure you want to delete?')");

                        $this->table->add_row(
                                '<div style="text-align:center">'.anchor('trans/sj/delete/'.$value->id,img($this->image_properties_del),$js).'</div>'
                                ,$value->nomor
                                ,$value->tanggal
                                ,'<div style="text-align:center">'.anchor('trans/sj/print/'.$value->id,img($this->image_properties_print)).'</div>'
                                );
                    }

                    $this->output_table .= $this->table->generate();
                    $this->output_table .= '<br />';
                    $this->table->clear();
                    $query->free_result();
                    $this->output_table;
                    return $this->output_table;
                }
                else
                {
                    return FALSE;
                }
            }break;
            case 'delete_temp':
            {
                $sql='delete from mpm.sj_temp where id='.$key;
                $this->db->query($sql);
            }break;
            case 'delete':
            {

                $this->db->trans_begin();
                $sql='update mpm.sj set deleted = 1,modified_by='.$userid.',modified="'.date('Y-m-d H:i:s').'" where id='.$key;
                $this->db->query($sql,array($key));
                $sql='update mpm.sj_detail set deleted=1 where id_ref='.$key;
                $this->db->query($sql,array($key));
                $this->db->trans_complete();
                if($this->db->trans_status()===FALSE)
                {
                    $this->db->trans_rollback();
                }
                else
                {
                    $this->db->trans_commit();
                }

            }break;
            case 'show_add':
            {
                //$sql='delete from mpm.sj_temp where userid='.$userid;
                //$this->db->query($sql);

                $sql='select id,namaprod,format(banyak,0) as banyak from sj_temp where userid='.$this->session->userdata('id');
                $query=$this->db->query($sql);
                if($query->num_rows()>0)
                {
                    $this->load->library('table');
                    $this->table->set_empty('0');

                    $this->table->set_empty('0');
                    $this->table->set_template($this->tmpl);
                    $this->table->set_caption('Tanda Terima');
                    $this->table->set_heading('Nama Barang', 'Quantity','Delete');
                    foreach ($query->result() as $value)
                    {
                        $js=array('onclick'=>"return confirm('Are you sure you want to delete?')");
                                
                        $this->table->add_row(
                                $value->namaprod
                                ,'<div  style="text-align:right">'.$value->banyak.'</div>'
                                ,'<div style="text-align:center">'.anchor('trans/sj/delete_temp/'.$value->id,img($this->image_properties_del),$js).'</div>'
                                );
                    }

                    $this->output_table .= $this->table->generate();
                    $this->output_table .= '<br />';
                    $this->table->clear();
                    $query->free_result();
                    $this->output_table;
                    return $this->output_table;
                }
                else
                {
                    return FALSE;
                }
            }break;
            case 'print':
            {
                $server='192.168.1.2';
                $user='root';
                $pass='mpm123';
                $db='mpm';
                $this->load->library('PHPJasperXML');
                echo$xml = simplexml_load_file("assets/report/akun/tanda_terima.jrxml");
                @$this->phpjasperxml->debugsql=false;
                @$this->phpjasperxml->arrayParameter=array('id'=>$key);
                @$this->phpjasperxml->xml_dismantle($xml);

                @$this->phpjasperxml->transferDBtoArray($server,$user,$pass,$db);
                @$this->phpjasperxml->outpage("I");
            }break;
            case 'add':
            {
                $post['namaprod']=$this->input->post('namaprod');
                $post['banyak']=$this->input->post('banyak');
                $post['userid']=$userid;
                $this->db->trans_begin();
                $sql=$this->db->insert('sj_temp',$post);
                if($this->db->trans_status()===FALSE)
                {
                    $this->db->trans_rollback();
                }
                else
                {
                    $this->db->trans_commit();
                }
            }break;
            case 'save':
            {
               $post['nomor']=$this->input->post('nomor');
               $post['tanggal']=$this->input->post('tanggal');
               $post['kode_ts']=$this->input->post('job');
               $post['created_by']=$userid;
               $post['created']=date('Y-m-d H:i:s');
               $this->db->trans_begin();
               $insert=$this->db->insert('sj',$post);
               $sql='select id from mpm.sj where created="'.$post['created'].'"';
               $query=$this->db->query($sql);
               $row=$query->row();
               $id_ref=$row->id;
               $sql="insert into mpm.sj_detail(id_ref,namaprod,banyak) select ".$id_ref.",namaprod,banyak from mpm.sj_temp";
               $this->db->query($sql);
               $sql='delete from mpm.sj_temp where userid='.$userid;
               $this->db->query($sql);
               if($this->db->trans_status()===FALSE)
                {
                    $this->db->trans_rollback();
                }
                else
                {
                    $this->db->trans_commit();
                }
             }break;

        }
    }
    public function retur($state='show_supp',$key=null,$id=null,$init=null)
    {
        switch(strtolower($state))
        {
            case 'edit_detail':
            {
                $sql='select * from trans_detail where id='.$key;
                $query=$this->db->query($sql);
                return $query->row();
            }break;
            case 'update_detail':
            {
                $userid=$this->session->userdata('id');
                $post['banyak']=$this->input->post('unit');
                $post['harga']=$this->input->post('hjual');
                $post['diskon']= $this->input->post('djual');
                $post['harga_beli']=$this->input->post('hbeli');
                $post['diskon_beli']=$this->input->post('dbeli');
                $post['modified']=date('Y-m-d H:i:s');
                $post['modified_by']=$userid;
                $where='id='.$key;
                $this->db->trans_begin();
                $sql=$this->db->update_string('trans_detail',$post,$where);
                $this->db->query($sql);
                if($this->db->trans_status()===FALSE)
                {
                    $this->db->trans_rollback();
                }
                else
                {
                    $this->db->trans_commit();
                }
            }break;
            case 'print_rekap':
            {
                $format=$this->input->post('format');
                $report=$this->input->post('report');
                $tanggal=substr($this->input->post('tanggal'),5,2).substr($this->input->post('tanggal'),0,4);
                switch($report){
                    case 1:
                        $sql="select npwp,nama_wp,noseri,nodo,date_format(tgldo,'%d/%m/%Y') as tglfaktur,date_format(tglbuat,'%d/%m/%Y') as tglbuat,format(sum((banyak*harga)-(banyak*harga*diskon/100)),2) as dpp,
                            format(sum((banyak*harga)-(banyak*harga*diskon/100))/10,2) as ppn
                            from  mpm.trans a inner join mpm.trans_detail b on a.id=b.id_ref where a.deleted=0 and b.deleted=0
                            and date_format(tglbuat,'%m%Y')='".$tanggal."' group by a.id";
                        return to_excel($this->db->query($sql),'client'.$tanggal);
                        break;
                    case 2:
                        $sql="select b.npwp,b.namasupp,a.* from (select a.supp,noseri_beli,nodo_beli,date_format(tgldo_beli,'%d/%m/%Y') as tglfaktur,date_format(tglbuat,'%d/%m/%Y') as tglbuat,
                            format(sum((banyak*harga_beli)-(banyak*harga_beli*diskon_beli/100)),2) as dpp,
                            format(sum((banyak*harga_beli)-(banyak*harga_beli*diskon_beli/100))/10,2) as ppn from  mpm.trans a
                            inner join mpm.trans_detail b on a.id=b.id_ref where a.deleted=0 and b.deleted=0 and date_format(tglbuat,'%m%Y')='".$tanggal."' group by a.id)a
                            inner join  mpm.tabsupp b using(supp)";
                        return to_excel($this->db->query($sql),'supp'.$tanggal);
                        break;
                    case 3:
                        $sql="select a.tglbuat,a.nopo as nott,a.npwp,a.nama_wp,a.nodo,a.tglfaktur_beli,a.noseri,a.dpp,a.ppn,(a.dpp+a.ppn)tot_ret_client,a.supp, b.npwp as npwp_supp,b.namasupp,a.nodo_beli,a.tglfaktur,a.noseri_beli,a.dpp_beli,a.ppn_beli,(a.dpp_beli+a.ppn_beli) tot_ret_supp 
                            from (select npwp,nama_wp,a.supp,noseri,noseri_beli,nodo,nopo,nodo_beli,date_format(tgldo,'%d/%m/%Y') as tglfaktur,date_format(tgldo_beli,'%d/%m/%Y') as tglfaktur_beli,date_format(tglbuat,'%d/%m/%Y') as tglbuat,
                            format(sum((banyak*harga)-(banyak*harga*diskon/100)),2) as dpp,
                            format(sum((banyak*harga_beli)-(banyak*harga_beli*diskon_beli/100)),2) as dpp_beli,
                            format(sum((banyak*harga)-(banyak*harga*diskon/100))/10,2) as ppn,
                            format(sum((banyak*harga_beli)-(banyak*harga_beli*diskon_beli/100))/10,2) as ppn_beli from  mpm.trans a
                            inner join mpm.trans_detail b on a.id=b.id_ref where a.deleted=0 and b.deleted=0 and date_format(tglbuat,'%m%Y')='".$tanggal."' group by a.id)a
                            inner join  mpm.tabsupp b using(supp)";
                        return to_excel($this->db->query($sql),'gabung'.$tanggal);
                        break;
                }   
            }
            case 'print':
            {
                $server='192.168.1.2';
                $user='root';
                $pass='mpm123';
                $db='mpm';
                $this->load->library('PHPJasperXML');
                $xml = simplexml_load_file("assets/report/report_retur.jrxml");
                @$this->phpjasperxml->debugsql=false;
                @$this->phpjasperxml->arrayParameter=array('nopesan'=>$key);
                @$this->phpjasperxml->xml_dismantle($xml);

                @$this->phpjasperxml->transferDBtoArray($server,$user,$pass,$db);
                @$this->phpjasperxml->outpage("I");
            }break;
            case 'print_beli':
            {
                $server='192.168.1.2';
                $user='root';
                $pass='mpm123';
                $db='mpm';
                $this->load->library('PHPJasperXML');
                echo$xml = simplexml_load_file("assets/report/report_retur_supp.jrxml");
                @$this->phpjasperxml->debugsql=false;
                @$this->phpjasperxml->arrayParameter=array('nopesan'=>$key);
                @$this->phpjasperxml->xml_dismantle($xml);

                @$this->phpjasperxml->transferDBtoArray($server,$user,$pass,$db);
                @$this->phpjasperxml->outpage("I");
            }break;
            case 'show':
            {
                $userid=$this->session->userdata('id');
               
                switch($id)
                {
                     case '0':
                     {
                          $sql="select a.id,a.supp,a.userid,a.company,a.tipe,a.deleted,tglbuat,a.created,a.nodo_beli,
                               nodo, date_format(tglbuat,'%d %M %Y, %T') as tglbuat,date_format(a.tgldo,'%Y-%m-%d') as tgldo,noseri
                               from mpm.trans a inner join mpm.user b on a.userid=b.id where  b.id=".$key." and a.deleted = 0 order by a.tglbuat desc";
                     }break;
                     case '1':
                     {
                          $sql="select a.id,a.supp,a.userid,a.company,a.tipe,a.deleted,tglbuat,a.created,a.nodo_beli,
                              nodo, date_format(tglbuat,'%d %M %Y, %T') as tglbuat,date_format(a.tgldo,'%Y-%m-%d') as tgldo,noseri
                              from mpm.trans a inner join mpm.user b on a.userid=b.id where date_format(tglbuat,'%Y-%m-%d') like '%".$key."%' and a.deleted=0 order by a.tglbuat desc";
                    }break;
                    default:
                        $sql="select a.id,a.supp,a.userid,a.company,a.tipe,a.deleted,tglbuat,a.created,a.nodo_beli,
                              nodo, date_format(tglbuat,'%d %M %Y, %T') as tglbuat,date_format(a.tgldo,'%Y-%m-%d') as tgldo,noseri
                              from mpm.trans a inner join mpm.user b on a.userid=b.id where date_format(tglbuat,'%Y-%m-%d') and deleted=0 order by a.tglbuat desc, a.company asc ";
                }
                $query=$this->db->query($sql);

                $this->total_query = $query->num_rows();
                $sql2= $sql.' limit ? offset ?';
                $query = $this->db->query($sql2,array(10,$init));

                if($query->num_rows()>0)
                {
                    $image_properties=array(
                         'src'    => 'assets/css/images/detail.gif',
                    );
                    $this->load->library('table');
                    $this->table->set_empty('0');

                    $this->table->set_empty('0');
                    $this->table->set_template($this->tmpl);
                    $this->table->set_caption('TRANSACTION JOURNAL');
                    $this->table->set_heading('COMPANY', 'NO FAKTUR','NO FAKTUR SUPP','DATE','NOSERI','TYPE','DETAIL','CLIENT','SUPP','DELETE');
                    foreach ($query->result() as $value)
                    {
                        $js=array('onclick'=>"return confirm('Are you sure you want to delete?')");
                        
                        /*$image_print_po = ($value->tglpo!='') ?
                                '<div div style="text-align:center">'.anchor('trans/po/print/'.$value->id,img($this->image_properties_print)).'</div>' :
                                '<div div style="text-align:center">'.img($this->image_properties_print_off).'</div>';
                        $image_email_po = ($value->tglpo!='') ?
                                '<div div style="text-align:center">'.anchor('trans/po/email/'.$value->id,img($this->image_properties_email)).'</div>' :
                                '<div div style="text-align:center">'.img($this->image_properties_email_off).'</div>';*/
                        $this->table->add_row(
                                //'<div div style="text-align:center">'.anchor('trans/po/delete/'.$value->id,img($this->image_properties_del),$js).'</div>'
                                //'ODR'.str_pad($value->id,6, '0', STR_PAD_LEFT)
                                $value->company
                                ,$value->nodo
                                ,$value->nodo_beli
                                ,$value->tglbuat
                                ,$value->noseri
                                ,$value->tipe

                                ,'<div div style="text-align:center">'.anchor('trans/retur/show_detail/'.$value->id.'/'.$value->supp.'/'.$value->tgldo.'/'.$value->userid,img($image_properties)).'</div>'
                                ,'<div div style="text-align:center">'.anchor_popup('trans/retur/print/'.$value->id,img($this->image_properties_print)).'</div>'
                                ,'<div div style="text-align:center">'.anchor_popup('trans/retur/print_beli/'.$value->id,img($this->image_properties_print)).'</div>'
                                ,'<div div style="text-align:center">'.anchor('trans/retur/delete/'.$value->id,img($this->image_properties_del),$js).'</div>'
                                );
                    }

                    $this->output_table .= $this->table->generate();
                    $this->output_table .= '<br />';
                    $this->table->clear();
                    $query->free_result();
                    $this->output_table;
                    return $this->output_table;
                }
                else
                {
                    return FALSE;
                }
            }break;
            case 'delete_temp':
            {
                $userid=$this->session->userdata('id');
                $sql='delete from mpm.trans_temp where id=? and userid=?';
                $query=$this->db->query($sql,array($key,$userid));
            }break;
            case 'clear_temp':
            {
                $userid=$this->session->userdata('id');
                $sql='delete from mpm.trans_temp where userid=?';
                $query=$this->db->query($sql,array($userid));
            }break;
            case 'edit':
            {
                $userid=$this->session->userdata('id');
                //$this->db->query('delete from po_temp where userid='.$id);
                $kodeprod=$this->input->post('product');
                $query=$this->getProductRetur($kodeprod,$id,$init);
                if(!$query)
                {
                    return false;
                }
                //echo $id.'--'.$init;
                $row=$query->row();
                $post['userid']     =$userid;
                $post['id_ref']     =(int)$key;
                $post['kodeprod']   =$kodeprod;
                $post['kode_prc']   =$row->kode_prc;
                $post['namaprod']   =$row->namaprod;
                $post['banyak']     =(-1)*$this->input->post('amount');
                $post['harga']      =$row->harga;
                $post['harga_beli'] =$row->harga_beli;
                $post['diskon']     =$row->diskon;
                $post['diskon_beli']=$row->diskon_beli;
                $post['supp']       =$row->supp;
                $post['created']    =$userid;
                $post['created_by'] =date('Y-m-d H:i:s');

                //$post['tgldokjdi']=date('Y-m-d H:i:s');
                $this->db->insert('mpm.trans_detail',$post);
                //$temp= $this->out_sales($id);
                //return $temp;
                redirect('trans/retur/show_detail/'.$key.'/'.$post['supp'].'/'.$id.'/'.$init);
            }break;
            case 'show_detail':
            {
                $sql="select * from mpm.trans a inner join mpm.trans_detail b on a.id=b.id_ref where a.id=? and b.deleted=0 and a.deleted=0 order by kodeprod";
                $query=$this->db->query($sql,array($key));
                if($query->num_rows()>0)
                {
                    $image_properties_back=array(
                         'src' => 'assets/css/images/back.png',
                    );

                    $this->load->library('table');
                    $this->table->set_empty('0');

                    $this->table->set_empty('0');
                    $this->table->set_template($this->tmpl);
                    $this->table->set_caption('PURCHASE ORDER DETAIL');
                    $this->table->set_heading('DELETE','CODE','PRODUCT', 'PRC CODE','AMOUNT','SELL PRICE','SELL DISC(%)','SELL NETT','BUY PRICE','BUY DISC(%)','BUY NETT','EDIT');
                    foreach ($query->result() as $value)
                    {
                        $js=array('onclick'=>"return confirm('Are you sure you want to delete?')");
                        $this->table->add_row(
                                '<div div style="text-align:center">'.anchor('trans/retur/delete_detail/'.$value->id,img($this->image_properties_del),$js).'</div>'
                                ,$value->kodeprod
                                ,$value->namaprod
                                ,$value->kode_prc
                                ,'<div div style="text-align:right">'.number_format($value->banyak).'</div>'
                                ,'<div div style="text-align:right">'.number_format($value->harga,2).'</div>'
                                ,'<div div style="text-align:right">'.number_format($value->diskon,2).'</div>'
                                ,'<div div style="text-align:right">'.number_format($value->harga-($value->harga*$value->diskon/100),2).'</div>'
                                ,'<div div style="text-align:right">'.number_format($value->harga_beli,2).'</div>'
                                ,'<div div style="text-align:right">'.number_format($value->diskon_beli,2).'</div>'
                                ,'<div div style="text-align:right">'.number_format($value->harga_beli-($value->harga_beli*$value->diskon_beli/100),2).'</div>'
                                ,'<div div style="text-align:center">'.anchor('trans/retur/edit_detail/'.$value->id,img($this->image_properties_edit)).'</div>'
                                //,'<div div style="text-align:center">'.anchor('trans/check_order_all/',img($image_properties_back)).'</div>'
                        );
                    }

                    $this->output_table .= $this->table->generate();
                    $this->output_table .= '<br />';
                    $this->table->clear();
                    $query->free_result();
                    $this->output_table;
                    return $this->output_table;
                }
                else
                {
                    return FALSE;
                }
            }break;
            case 'show_temp':
            {
                $id=$this->session->userdata('id');
                $supp = $this->session->userdata('supplier');
                $sql1='select * from mpm.trans_temp where userid=? and supp=? order by kodeprod';
                $query = $this->db->query($sql1,array($id,$supp));
                $this->total_query = $query->num_rows();
                //$sql2= $sql1.' limit ? offset ?';
                //$query = $this->db->query($sql2,array($limit,$offset));

                if($query->num_rows() > 0)
                {
                    $this->load->library('table');
                    $this->table->set_empty('0');

                    $this->table->set_empty('0');
                    $this->table->set_template($this->tmpl);
                    $this->table->set_caption('PURCHASE ORDER');
                    $this->table->set_heading('CODE','PRODUCT', 'PRC CODE','AMOUNT','PRICE','DISC(%)','NETT','DELETE');
                    foreach ($query->result() as $value)
                    {
                        $js=array('onclick'=>"return confirm('Are you sure you want to delete?')");
                        $this->table->add_row(
                                $value->kodeprod
                                ,$value->namaprod
                                ,$value->kode_prc
                                ,'<div div style="text-align:right">'.number_format($value->banyak).'</div>'
                                ,'<div div style="text-align:right">'.number_format($value->harga,2).'</div>'
                                ,'<div div style="text-align:right">'.number_format($value->diskon,2).'</div>'
                                ,'<div div style="text-align:right">'.number_format($value->harga-($value->harga*$value->diskon/100),2).'</div>'
                                ,'<div div style="text-align:center">'.anchor('trans/retur/delete_temp/'.$value->id,img($this->image_properties_del),$js).'</div>'
                        );
                    }

                    $this->output_table .= $this->table->generate();
                    $this->output_table .= '<br />';
                    $this->table->clear();
                    $query->free_result();
                    $this->output_table;
                    return $this->output_table;
                }
                else
                {
                    return FALSE;
                }
            }break;
            case 'addtemp':
            {
                $id=$this->session->userdata('id');
                //$this->db->query('delete from po_temp where userid='.$id);
                $kodeprod=$this->input->post('kode');
                $sql='select * from mpm.tabprod where kodeprod='.$kodeprod;
                $query=$this->db->query($sql);
                $row=$query->row();
                              
                $post['userid']=$id;
                $post['kodeprod']   =$kodeprod;
                $post['kode_prc']   =$row->KODE_PRC;
                $post['namaprod']   =$row->NAMAPROD;
                $post['banyak']     =$this->input->post('amount');
                $post['harga']      =$this->input->post('hjual');
                $post['harga_beli'] =$this->input->post('hbeli');
                $post['diskon']     =$this->input->post('djual');
                $post['diskon_beli']=$this->input->post('dbeli');
                $post['supp']       =$row->SUPP;
                //$post['tgldokjdi']=date('Y-m-d H:i:s');
                $this->db->insert('mpm.trans_temp',$post);
                //$temp= $this->out_sales($id);
                //return $temp;
                redirect('trans/retur/add');
            }break;
            case 'delete':
            {
                $user=$this->session->userdata('id');
                $this->db->trans_begin();
                $sql='update mpm.trans set deleted = 1,modified_by='.$user.',modified="'.date('Y-m-d H:i:s').'" where id='.$key;
                $this->db->query($sql,array($key));
                $sql='update mpm.trans_detail set deleted=1,userid='.$user.' where id_ref='.$key;
                $this->db->query($sql,array($key));
                $this->db->trans_complete();
                if($this->db->trans_status()===FALSE)
                {
                    $this->db->trans_rollback();
                }
                else
                {
                    $this->db->trans_commit();
                }
                redirect('trans/retur/');
            }break;
            case 'delete_detail':
            {
                $id=$this->session->userdata('id');
                $query=$this->db->query('select id_ref from mpm.trans_detail where id=? and deleted=0',array($key));
                $row=$query->row();
                //$sql='delete from mpm.po_detail where id=?';
                $sql='update mpm.trans_detail set deleted=1 where id=?';
                $this->db->query($sql,array($key));
                $query=$this->db->query('select 1 from mpm.trans_detail where id_ref=? and deleted=0',array($row->id_ref));
                if($query->num_rows()<=0)
                {
                    $sql='update mpm.trans set deleted=1,modified_by='.$id.' ,modified="'.date('Y-m-d H:i:s').'" where id=?';
                    $this->db->query($sql,array($row->id_ref));
                    //redirect('trans/retur/show/');
                }
            }break;
            case 'update':
            {
                $id=$this->session->userdata('id');
                $post['modified_by']=$id;
                $post['modified']=date('Y-m-d H:i:s');;
                $post['nodo']=$this->input->post('nodo');
                $post['nodo_beli']=$this->input->post('nodo_beli');
                $post['tgldo_beli']=$this->input->post('tgldo_beli');
                $post['noseri']=$this->input->post('noseri');
                $post['noseri_beli']=$this->input->post('noseri_beli');
                $post['nopo']=$this->input->post('nopo');
                $post['tglbuat']=$this->input->post('tglbuat');
                $where='id='.$key;
                $this->db->trans_begin();
                $sql=$this->db->update_string('trans',$post,$where);
                $this->db->query($sql);
                if($this->db->trans_status()===FALSE)
                {
                    $this->db->trans_rollback();
                }
                else
                {
                    $this->db->trans_commit();
                }
                redirect('trans/retur/show/');
            }break;
            case 'save'://purchase
            {
                $supp=$this->session->userdata('supplier');
                $id=$this->session->userdata('id');
                $userid=$this->session->userdata('id');
                $client=$this->session->userdata('client');
                $row=$this->getCustInfo2($client);
                $post['created_by'] =$userid;
                $post['created']=date('Y-m-d H:i:s');
                $post['tgldo']=$this->session->userdata('tgl');
                $post['tgldo_beli']=$this->session->userdata('tgl');;
                $post['nodo']=$this->input->post('nodo');
                $post['nodo_beli']=$this->input->post('nodo_beli');
                $post['nopo']=$this->input->post('nopo');
                $post['noseri']=$this->input->post('noseri');
                $post['noseri_beli']=$this->input->post('noseri_beli');
                $post['userid']= $this->session->userdata('client');
                $post['supp']=$this->session->userdata('supplier');
                $post['tglbuat']=$this->input->post('tglbuat');
                $post['tipe']='R';
                $post['company']=$row->company;
                $post['nama_wp']=$row->nama_wp;
                $post['email']=$row->email;
                $post['npwp']=$row->npwp;
                $post['alamat_wp']=$row->alamat_wp;
                //$tglpesan=date('Y-m-d H:i:s');

                $this->db->trans_begin();
                $this->db->insert('mpm.trans',$post);
                $query=$this->db->query("select id from mpm.trans where created='".$post['created']."'");
                $row=$query->row();
                $sql='insert into mpm.trans_detail(id_ref,supp,kodeprod,namaprod,banyak,harga,harga_beli,kode_prc,userid,diskon,diskon_beli,created_by,created) select '.$row->id.',supp,kodeprod,namaprod,sum(banyak)*(-1),harga,harga_beli,kode_prc,'.$post['userid'].',diskon,diskon_beli,'.$id.',"'.$post['created'].'" from mpm.trans_temp where userid='.$id.' and supp="'.$supp.'" group by kodeprod';
                $this->db->query($sql);
                $this->db->query('delete from mpm.trans_temp where userid=?',array($id));
                $this->db->trans_complete();
                if($this->db->trans_status()===FALSE)
                {
                    $this->db->trans_rollback();
                }
                else
                {
                    $this->db->trans_commit();
                }
                redirect('trans/retur/');
            }break;
        }
    }
    private function email_config()
    {
         $config['protocol']  = 'smtp';
         $config['smtp_host'] = 'ssl://smtp.gmail.com';
         $config['smtp_port'] = '465';
         $config['smtp_timeout'] = '3000';
         $config['smtp_user'] = 'muliaputramandiri@gmail.com';
         $config['smtp_pass'] = 'mpmdelto12345';
         $config['charset']  = 'utf-8';
         $config['newline']  = "\r\n";
         $config['use_ci_email'] = TRUE;

         $this->email->initialize($config);
    }
    private function email_config_piutang()
    {
         $config['protocol']  = 'smtp';
         $config['smtp_host'] = 'ssl://smtp.gmail.com';
         $config['smtp_port'] = '465';
         $config['smtp_timeout'] = '300';
         $config['smtp_user'] = 'trisnandha@gmail.com';
         $config['smtp_pass'] = '16anakkuda';
         $config['charset']  = 'utf-8';
         $config['newline']  = "\r\n";
         $config['mailtype'] ="html";
         $config['use_ci_email'] = TRUE;

         $this->email->initialize($config);
    }
    public function po($state=null,$key=0,$id='',$init='')
    {
        switch($state)
        {
            case 'half':
            {
                $sql='update mpm.po_detail set backup=banyak where id_ref='.$key;
                $this->db->query($sql);
                $sql='update mpm.po_detail a inner join mpm.tabprod b using(kodeprod) set a.banyak=ceil(a.backup/b.isisatuan/2)*b.isisatuan where a.id_ref='.$key;
                $this->db->query($sql);
            }break;
            case 'unlock':
            {
                $sql='update mpm.po set open=1,open_by='.$this->session->userdata('id').',open_date="'.date('Y-m-d H:i:s').'" where id='.$key;
                $this->db->query($sql);
            }break;
            case 'open':
            {
                switch($id)
                {
                     case '0':
                     {
                        
                        $sql="select * from (select a.*,b.value from(select a.open,a.open_by,a.open_date,a.id,a.supp,a.company,b.id as userid,b.kode_lang,a.email,tipe,
                            date_format(tglpesan,'%d %M %Y, %T') as tglpesan,
                            nopo, date_format(tglpo,'%d %M %Y, %T') as tglpo,
                            nodo, date_format(tgldo,'%d %M %Y, %T') as tgldo,b.bank_garansi
                            from mpm.po a inner join mpm.user b on a.userid=b.id where b.id=".$key." and a.deleted=0 ) a
                            left join (select id_ref,sum(harga*banyak) as value from po_detail where deleted=0 group by id_ref)b on a.id=b.id_ref
                            order by id desc) a
                            left join
                            (
                                select * from
                                (select substr(customerid,2,5) kode_lang, sum(dokument-bayar) saldoakhir from dbsls.t_ar_ink_master where dokument-bayar>0 group by customerid) a
                                inner join 
                                (select substr(customerid,2,5) kode_lang ,sum(dokument-bayar) jt from dbsls.t_ar_ink_master where dokument-bayar>0 and datediff(tgl_tempo,curdate())<108 group by customerid)b
                                using (kode_lang)
                            )b using(kode_lang)
                            left join 
                            (select kode_lang,cl from mpm.user)c using(kode_lang)
                            ";
                     }break;
                     case '1':
                     {
                        $sql="SELECT * FROM (select a.*,b.value from(select a.open,a.open_by,a.open_date,a.id,a.supp,a.company,b.id as userid,b.kode_lang,a.email,tipe,
                            date_format(tglpesan,'%d %M %Y, %T') as tglpesan,
                            nopo, date_format(tglpo,'%d %M %Y, %T') as tglpo,
                            nodo, date_format(tgldo,'%d %M %Y, %T') as tgldo
                            from mpm.po a inner join mpm.user b on a.userid=b.id where date_format(tglpesan,'%Y-%m-%d') like '%".$key."%' and a.deleted=0 order by id desc) a
                            left join (select id_ref,sum(harga*banyak) as value from po_detail where deleted=0 group by id_ref)b on a.id=b.id_ref
                            order by id desc)a
                            left join
                            (
                                select * from
                                (select substr(customerid,2,5) kode_lang, sum(dokument-bayar) saldoakhir from dbsls.t_ar_ink_master where dokument-bayar>0 group by customerid) a
                                inner join 
                                (select substr(customerid,2,5) kode_lang ,sum(dokument-bayar) jt from dbsls.t_ar_ink_master where dokument-bayar>0 and datediff(tgl_tempo,curdate())<8 group by customerid)b
                                using (kode_lang)
                            )b using(kode_lang)
                            left join 
                            (select kode_lang,cl from mpm.user)c using(kode_lang)
                            ";
                    }break;
                    default:
                        /*$sql="select * from (select a.*,b.value from(select a.open,a.open_by,a.open_date,a.id,a.supp,a.company,b.id as userid,b.kode_lang,a.email,tipe,
                            date_format(tglpesan,'%d %M %Y, %T') as tglpesan,
                            nopo, date_format(tglpo,'%d %M %Y, %T') as tglpo,
                            nodo, date_format(tgldo,'%d %M %Y, %T') as tgldo
                            from mpm.po a inner join mpm.user b on a.userid=b.id where a.deleted=0 order by id desc limit 100)a
                            left join (select id_ref,sum(harga*banyak) as value from po_detail where deleted=0 group by id_ref)b on a.id=b.id_ref
                            order by id desc) a
                            left JOIN(
                            SELECT kode_lang,round(sum(nildok - aknildok - nldokacu),2)jt
                            FROM pusat.mp WHERE
                            TGL_JTEMPO < SUBDATE(curdate(), INTERVAL 8 DAY ) and bulan=(select max(bulan) from pusat.mp)
                            GROUP BY kode_lang) g using(kode_lang)
                            left JOIN(
                            SELECT kode_lang,round(sum(nildok - aknildok - nldokacu),2)saldoakhir
                            FROM pusat.mp WHERE
                            bulan=(select max(bulan) from pusat.mp)
                            GROUP BY kode_lang) h using(kode_lang)
                            left join(
                            select kode_lang,cl from mpm.user)i using (kode_lang)
                            ";*/
                        $sql="
                                select  * 
                                from 
                                (
                                        select  a.*,
                                                b.value 
                                                
                                        from    (
                                                    select  a.open,
                                                            a.open_by,
                                                            a.open_date,
                                                            a.id,
                                                            a.supp,
                                                            a.company,
                                                            b.id as userid,
                                                            b.kode_lang,
                                                            a.email,
                                                            tipe,
                                                            date_format(tglpesan,'%d %M %Y, %T') as tglpesan,
                                                            nopo, 
                                                            date_format(tglpo,'%d %M %Y, %T') as tglpo,
                                                            nodo, 
                                                            date_format(tgldo,'%d %M %Y, %T') as tgldo
                                                    from    mpm.po a inner join mpm.user b 
                                                                on a.userid=b.id 
                                                    where   a.deleted=0 
                                                    order by id desc 
                                                    limit 100
                                                )a
                                                
                                                left join 
                                                (       
                                                    select  id_ref,
                                                            sum(harga*banyak) as value 
                                                            from mpm.po_detail 
                                                    where   deleted=0 
                                                    group by id_ref
                                                )b  on a.id=b.id_ref                
                                                order by id desc
                                                
                                )a

                                left join
                                (
                                    select * from
                                    (
                                        select  substr(customerid,2,5) kode_lang, 
                                                sum(dokument-bayar) saldoakhir 
                                        from    dbsls.t_ar_ink_master 
                                        where   dokument-bayar>0 
                                        group by customerid
                                    )a
                                    
                                    inner join 
                                    (
                                        select  substr(customerid,2,5) kode_lang,
                                                sum(dokument-bayar) jt 
                                        from    dbsls.t_ar_ink_master 
                                        where   dokument-bayar>0 and 
                                                datediff(tgl_tempo,curdate())<108 
                                        group by customerid
                                    )b
                                    using (kode_lang)
                                    
                                )b 
                                using(kode_lang)

                                left join 
                                (
                                    select  kode_lang,bank_garansi,cl 
                                    from    mpm.user
                                )c  using(kode_lang)
                            ";
                        //$sql='select 1 from mpm.tabcomp where nocab="A"';break;
                }
                $query=$this->db->query($sql);
                $this->total_query = $query->num_rows();
                $sql2= $sql.' limit ? offset ?';
                $query = $this->db->query($sql2,array(10,$init));
                //echo $key;
                $id=$this->session->userdata('id');
                //echo $id."<br>";

                if($id == '17' or $id == '290' )
                {
                        if($query->num_rows()>0)
                        {
                            $image_properties=array(
                                 'src'    => 'assets/css/images/detail.gif',
                            );
                            $this->load->library('table');
                            $this->table->set_empty('0');

                            $this->table->set_empty('0');
                            $this->table->set_template($this->tmpl);
                            $this->table->set_caption('ORDER LIST');
                            $this->table->set_heading('NO ORDER','COMPANY','ORDER DATE', 'SUPPLIER','TIPE','NO PO','ORDER VALUE','PRINT','PIUTANG','BANK GARANSI','CREDIT LIMIT','DUEDATE (>7)');

                            foreach ($query->result() as $value)
                            {

                                if($value->tipe=='N')
                                {
                                    $detail=img($this->image_properties_open);
                                }
                                else{
                                    if($value->jt<=0){
                                        if(($value->value+$value->saldoakhir)<$value->cl){
                                            $detail=img($this->image_properties_open);
                                        }
                                        else{
                                            if($value->open==1){
                                                $detail=img($this->image_properties_open);
                                            }
                                            else{
                                                $detail=anchor('trans/po/unlock/'.$value->id,img($this->image_properties_locked));
                                            }
                                        }
                                    }
                                    else{
                                        if($value->open==1){
                                                $detail=img($this->image_properties_open);
                                            }
                                            else{
                                                $detail=anchor('trans/po/unlock/'.$value->id,img($this->image_properties_locked));
                                            }
                                        }
                                    }
                                //$detail=anchor('trans/po/show_detail/'.$value->id.'/'.$value->supp,img($this->image_properties_detail));

                                $js=array('onclick'=>"return confirm('Are you sure you want to delete?')");
                                $tglpo=$value->tglpo;
                                $image_print_po = ($value->tglpo!='') ?
                                        '<div div style="text-align:center">'.anchor('trans/po/print/'.$value->id,img($this->image_properties_print)).'</div>' :
                                        '<div div style="text-align:center">'.img($this->image_properties_print_off).'</div>';
                                $image_email_po = ($value->tglpo!='' and $value->email!='') ?
                                        '<div div style="text-align:center">'.anchor('trans/po/email/'.$value->id.'/'.$value->userid.$value->supp,img($this->image_properties_email)).'</div>' :
                                        '<div div style="text-align:center">'.img($this->image_properties_email_off).'</div>';
                                $image_email_warn = ($value->jt>0 and $value->email!='') ?
                                        '<div div style="text-align:center">'.anchor('trans/po/email_warn/'.$value->id.'/'.$value->userid.$value->supp,img($this->image_properties_email)).'</div>' :
                                        '<div div style="text-align:center">'.img($this->image_properties_email_off).'</div>';
                                $this->table->add_row(
                                        //'<div div style="text-align:center">'.anchor('trans/po/delete/'.$value->id,img($this->image_properties_del),$js).'</div>'
                                        'ODR'.$value->id//'ODR'.str_pad($value->id,6, '0', STR_PAD_LEFT)
                                        ,$value->company
                                        //,$value->email
                                        ,$value->tglpesan
                                        //,$value->tglpo
                                        ,$this->getSuppname($value->supp)
                                        ,$value->tipe
                                        ,$value->nopo
                                        ,'<div style="text-align:right">'.number_format($value->value,2).'</div>'
                                        ,'<div style="text-align:center">'.anchor_popup('trans/po/print/'.$value->id,img($this->image_properties_print)).'</div>'//$image_print_po//'<div div style="text-align:center">'.anchor('trans/print_po_mpm/'.$value->nopesan,img($image_print)).'</div>'
                                        ,'<div style="text-align:right">'.number_format($value->saldoakhir,2).'</div>'
                                        ,'<div style="text-align:right">'.($value->bank_garansi).'</div>'
                                        ,'<div style="text-align:right">'.number_format($value->cl,2).'</div>'
                                        ,'<div style="text-align:right">'.number_format($value->jt,2).'</div>'
                                        //,'<div style="text-align:center">'.anchor('trans/po/show_detail/'.$value->id.'/'.$value->supp,img($image_properties)).'</div>'
                                        
                                            //,$image_email_po
                                        //,'<div style="text-align:right">'.number_format($value->cl,2).'</div>'
                                        //,'<div div style="text-align:center">'.anchor('trans/po_delete/'.$value->nopesan,img($image_properties_del),$js).'</div>'
                                        //,$image_email_warn
                                        );
                                    }

                                    $this->output_table .= $this->table->generate();
                                    $this->output_table .= '<br />';
                                    $this->table->clear();
                                    $query->free_result();
                                    $this->output_table;
                                    return $this->output_table;
                        }
                        else
                        {
                            return FALSE;
                        }
                }else{

                    if($query->num_rows()>0)
                        {
                            $image_properties=array(
                                 'src'    => 'assets/css/images/detail.gif',
                            );
                            $this->load->library('table');
                            $this->table->set_empty('0');

                            $this->table->set_empty('0');
                            $this->table->set_template($this->tmpl);
                            $this->table->set_caption('ORDER LIST');
                            $this->table->set_heading('NO ORDER','COMPANY','ORDER DATE', 'SUPPLIER','TIPE','NO PO','ORDER VALUE','PRINT','PIUTANG','BANK GARANSI','CREDIT LIMIT','DUEDATE (>7)','LOCK/UNLOCK');

                            foreach ($query->result() as $value)
                            {

                                if($value->tipe=='N')
                                {
                                    $detail=img($this->image_properties_open);
                                }
                                else{
                                    if($value->jt<=0){
                                        if(($value->value+$value->saldoakhir)<$value->cl){
                                            $detail=img($this->image_properties_open);
                                        }
                                        else{
                                            if($value->open==1){
                                                $detail=img($this->image_properties_open);
                                            }
                                            else{
                                                $detail=anchor('trans/po/unlock/'.$value->id,img($this->image_properties_locked));
                                            }
                                        }
                                    }
                                    else{
                                        if($value->open==1){
                                                $detail=img($this->image_properties_open);
                                            }
                                            else{
                                                $detail=anchor('trans/po/unlock/'.$value->id,img($this->image_properties_locked));
                                            }
                                        }
                                    }
                                //$detail=anchor('trans/po/show_detail/'.$value->id.'/'.$value->supp,img($this->image_properties_detail));

                                $js=array('onclick'=>"return confirm('Are you sure you want to delete?')");
                                $tglpo=$value->tglpo;
                                $image_print_po = ($value->tglpo!='') ?
                                        '<div div style="text-align:center">'.anchor('trans/po/print/'.$value->id,img($this->image_properties_print)).'</div>' :
                                        '<div div style="text-align:center">'.img($this->image_properties_print_off).'</div>';
                                $image_email_po = ($value->tglpo!='' and $value->email!='') ?
                                        '<div div style="text-align:center">'.anchor('trans/po/email/'.$value->id.'/'.$value->userid.$value->supp,img($this->image_properties_email)).'</div>' :
                                        '<div div style="text-align:center">'.img($this->image_properties_email_off).'</div>';
                                $image_email_warn = ($value->jt>0 and $value->email!='') ?
                                        '<div div style="text-align:center">'.anchor('trans/po/email_warn/'.$value->id.'/'.$value->userid.$value->supp,img($this->image_properties_email)).'</div>' :
                                        '<div div style="text-align:center">'.img($this->image_properties_email_off).'</div>';
                                $this->table->add_row(
                                        //'<div div style="text-align:center">'.anchor('trans/po/delete/'.$value->id,img($this->image_properties_del),$js).'</div>'
                                        'ODR'.$value->id//'ODR'.str_pad($value->id,6, '0', STR_PAD_LEFT)
                                        ,$value->company
                                        //,$value->email
                                        ,$value->tglpesan
                                        //,$value->tglpo
                                        ,$this->getSuppname($value->supp)
                                        ,$value->tipe
                                        ,$value->nopo
                                        ,'<div style="text-align:right">'.number_format($value->value,2).'</div>'
                                        ,'<div style="text-align:center">'.anchor_popup('trans/po/print/'.$value->id,img($this->image_properties_print)).'</div>'//$image_print_po//'<div div style="text-align:center">'.anchor('trans/print_po_mpm/'.$value->nopesan,img($image_print)).'</div>'
                                        ,'<div style="text-align:right">'.number_format($value->saldoakhir,2).'</div>'
                                        ,'<div style="text-align:right">'.($value->bank_garansi).'</div>'
                                        ,'<div style="text-align:right">'.number_format($value->cl,2).'</div>'
                                        ,'<div style="text-align:right">'.number_format($value->jt,2).'</div>'
                                        //,'<div style="text-align:center">'.anchor('trans/po/show_detail/'.$value->id.'/'.$value->supp,img($image_properties)).'</div>'
                                        ,$detail
                                            //,$image_email_po
                                        //,'<div style="text-align:right">'.number_format($value->cl,2).'</div>'
                                        //,'<div div style="text-align:center">'.anchor('trans/po_delete/'.$value->nopesan,img($image_properties_del),$js).'</div>'
                                        //,$image_email_warn
                                        );
                                    }

                                    $this->output_table .= $this->table->generate();
                                    $this->output_table .= '<br />';
                                    $this->table->clear();
                                    $query->free_result();
                                    $this->output_table;
                                    return $this->output_table;
                        }
                        else
                        {
                            return FALSE;
                        }

                }
            }break;
            case 'rekap':
            {
                $supp=$this->input->post('supp');
                $month=$this->input->post('month');
                $year=$this->input->post('year');
                $sql="select a.company, kodeprod,b.namaprod,b.banyak,b.harga,b.banyak*b.harga total,tglpo,nopo from mpm.po a inner join  mpm.po_detail b on a.id=b.id_ref  where a.deleted=0 and b.deleted=0 and b.supp=".$supp."  and month(tglpo)=".$month." and year(tglpo)=".$year." order by nopo,kodeprod";
                $query=$this->db->query($sql);
                return to_excel($query);
            }break;
            case 'show':
            {
                switch($id)
                {
                     case '0':
                     {
                         $sql="
                            select * from 
                            (
                                    select  a.*,
                                            b.value 
                                    from
                                    (
                                        select  a.open,
                                                a.id,
                                                a.supp,
                                                a.company,
                                                b.id as userid,
                                                b.kode_lang,
                                                a.email,
                                                tipe,
                                                date_format(tglpesan,'%d %M %Y, %T') as tglpesan,
                                                nopo, date_format(tglpo,'%d %M %Y, %T') as tglpo,
                                                nodo, date_format(tgldo,'%d %M %Y, %T') as tgldo
                                        from    mpm.po a 
                                                inner join mpm.user b 
                                                    on a.userid=b.id 
                                        where   b.id=".$key." and a.deleted=0 
                                    ) a
                                        
                                    left join 
                                    (
                                        select  id_ref,
                                                sum(harga*banyak) as value 
                                        from    po_detail 
                                        where   deleted=0 
                                        group by id_ref
                                    )b 
                                        on a.id=b.id_ref
                                        order by id desc
                            )a
                                                         
                            left join
                            (
                                select * from
                                (
                                    select  substr(customerid,2,5) kode_lang, 
                                            sum(dokument-bayar) saldoakhir 
                                    from    dbsls.t_ar_ink_master 
                                    where   dokument-bayar>0 
                                    group by customerid
                                ) a

                                inner join 
                                (
                                    select  substr(customerid,2,5) kode_lang ,
                                            sum(dokument-bayar) jt 
                                    from    dbsls.t_ar_ink_master 
                                    where   dokument-bayar>0 
                                            and datediff(tgl_tempo,curdate())<8 group by customerid
                                )b
                                    using (kode_lang)
                                
                            )b using(kode_lang)
                            left join 
                            (
                                select  kode_lang,
                                        cl 
                                from    mpm.user
                                where   id = ".$key."
                            )c using(kode_lang)
                            ";
                         $sql2="select * from (select a.*,b.value from(select a.open,a.id,a.supp,a.company,b.id as userid,b.kode_lang,a.email,tipe,
                            date_format(tglpesan,'%d %M %Y, %T') as tglpesan,
                            nopo, date_format(tglpo,'%d %M %Y, %T') as tglpo,
                            nodo, date_format(tgldo,'%d %M %Y, %T') as tgldo
                            from mpm.po a inner join mpm.user b on a.userid=b.id where b.id=".$key." and a.deleted=0 ) a
                            left join (select id_ref,sum(harga*banyak) as value from po_detail where deleted=0 group by id_ref)b on a.id=b.id_ref
                            order by id desc) a
                            left join
                            (
                            select right(group_id,5) kode_lang, (saldoawal+debit-kredit) saldoakhir, jt
                            from(
                                            select a.group_id,a.group_descr,debitprev-if(isnull(kreditprev),0,kreditprev) saldoawal,if(isnull(debit),0,debit) debit,if(isnull(kredit),0,kredit) kredit,jt

                                            from(

                                            select group_id, group_descr, sum(if(retur=1,dokument*-1,dokument)) debitprev from dbsls.t_ar_ink_master a 
                                            inner join dbsls.m_customer b using(customerid)where  tanggal<concat(substr(curdate(),1,8),'01')
                                            group by group_id)a 

                                            left join 

                                            (select group_id, group_descr, sum(bayar_transfer+bayar_giro+bayar_tunai) kreditprev from dbsls.t_ar_ink_detail a 
                                            inner join dbsls.m_customer b using(customerid)where  tgl_transfer<concat(substr(curdate(),1,8),'01')
                                            group by group_id)b using(group_id)

                                            left join 

                                            (select group_id, group_descr, sum(if(retur=1,dokument*-1,dokument)) debit from dbsls.t_ar_ink_master a 
                                            inner join dbsls.m_customer b using(customerid)where  tanggal>=concat(substr(curdate(),1,8),'01') and tanggal<=curdate()
                                            group by group_id)c using (group_id)

                                            left join 

                                            (select group_id, group_descr, if(isnull(sum(bayar_transfer+bayar_giro+bayar_tunai)),0,sum(bayar_transfer+bayar_giro+bayar_tunai)) kredit from dbsls.t_ar_ink_detail a 
                                            inner join dbsls.m_customer b using(customerid)where  tgl_transfer>=concat(substr(curdate(),1,8),'01') and tgl_transfer<=curdate()
                                            group by group_id)d using(group_id)

                                            left join
                                            (select group_id, group_descr,

                                            SUM(IF(days_past_due > 8, amount_due, 0))jt
                                            FROM(
                                                    SELECT group_id, group_descr, datediff(curdate(),tgl_tempo) days_past_due, sum(saldo) amount_due 
                                                    FROM(
                                                            SELECT no_sales ,a.customerid ,tgl_tempo ,( IF ( a.retur = 1, a.dokument *- 1, a.dokument )) - IF (isnull(kredit), 0, kredit) saldo
                                                            FROM dbsls.t_ar_ink_master a 
                                                            LEFT JOIN 
                                                                    (SELECT no_sales,customerid, sum( bayar_transfer + bayar_giro + bayar_tunai ) kredit 
                                                                    FROM dbsls.t_ar_ink_detail where tgl_transfer <=curdate()
                                                                    GROUP BY no_sales ) b USING (no_sales) where a.tanggal<=curdate()
                                                                    GROUP BY no_sales)a1 
                                                                    inner join dbsls.m_customer b1 using (customerid) 
                                                                    WHERE saldo <>0
                                                                    GROUP BY group_id,days_past_due
                                                                    )a group by group_id 
                                            )e using (group_id)
                            )a
                            order by a.group_descr) b using(kode_lang)
                            left join 
                            (select kode_lang,cl from mpm.user)c using(kode_lang)
                            ";
                     }break;
                     case '1':
                     {
                        $sql="SELECT * FROM (select a.*,b.value from(select a.open,a.id,a.supp,a.company,b.id as userid,b.kode_lang,a.email,tipe,
                            date_format(tglpesan,'%d %M %Y, %T') as tglpesan,
                            nopo, date_format(tglpo,'%d %M %Y, %T') as tglpo,
                            nodo, date_format(tgldo,'%d %M %Y, %T') as tgldo
                            from mpm.po a inner join mpm.user b on a.userid=b.id where date_format(tglpesan,'%Y-%m-%d') like '%".$key."%' and a.deleted=0 order by id desc) a
                            left join (select id_ref,sum(harga*banyak) as value from po_detail where deleted=0 group by id_ref)b on a.id=b.id_ref
                            order by id desc)a
                            left join
                            (
                                select * from
                                (select substr(customerid,2,5) kode_lang, sum(dokument-bayar) saldoakhir from dbsls.t_ar_ink_master where dokument-bayar>0 group by customerid) a
                                inner join 
                                (select substr(customerid,2,5) kode_lang ,sum(dokument-bayar) jt from dbsls.t_ar_ink_master where dokument-bayar>0 and datediff(tgl_tempo,curdate())<8 group by customerid)b
                                using (kode_lang)
                            )b using(kode_lang)
                            left join 
                            (select kode_lang,cl from mpm.user)c using(kode_lang)
                            ";
                         $sql2="SELECT * FROM (select a.*,b.value from(select a.open,a.id,a.supp,a.company,b.id as userid,b.kode_lang,a.email,tipe,
                            date_format(tglpesan,'%d %M %Y, %T') as tglpesan,
                            nopo, date_format(tglpo,'%d %M %Y, %T') as tglpo,
                            nodo, date_format(tgldo,'%d %M %Y, %T') as tgldo
                            from mpm.po a inner join mpm.user b on a.userid=b.id where date_format(tglpesan,'%Y-%m-%d') like '%".$key."%' and a.deleted=0 order by id desc) a
                            left join (select id_ref,sum(harga*banyak) as value from po_detail where deleted=0 group by id_ref)b on a.id=b.id_ref
                            order by id desc)a
                            left join
                            (
                            select right(group_id,5) kode_lang, (saldoawal+debit-kredit) saldoakhir, jt
                            from(
                                            select a.group_id,a.group_descr,debitprev-if(isnull(kreditprev),0,kreditprev) saldoawal,if(isnull(debit),0,debit) debit,if(isnull(kredit),0,kredit) kredit,jt

                                            from(

                                            select group_id, group_descr, sum(if(retur=1,dokument*-1,dokument)) debitprev from dbsls.t_ar_ink_master a 
                                            inner join dbsls.m_customer b using(customerid)where  tanggal<concat(substr(curdate(),1,8),'01')
                                            group by group_id)a 

                                            left join 

                                            (select group_id, group_descr, sum(bayar_transfer+bayar_giro+bayar_tunai) kreditprev from dbsls.t_ar_ink_detail a 
                                            inner join dbsls.m_customer b using(customerid)where  tgl_transfer<concat(substr(curdate(),1,8),'01')
                                            group by group_id)b using(group_id)

                                            left join 

                                            (select group_id, group_descr, sum(if(retur=1,dokument*-1,dokument)) debit from dbsls.t_ar_ink_master a 
                                            inner join dbsls.m_customer b using(customerid)where  tanggal>=concat(substr(curdate(),1,8),'01') and tanggal<=curdate()
                                            group by group_id)c using (group_id)

                                            left join 

                                            (select group_id, group_descr, if(isnull(sum(bayar_transfer+bayar_giro+bayar_tunai)),0,sum(bayar_transfer+bayar_giro+bayar_tunai)) kredit from dbsls.t_ar_ink_detail a 
                                            inner join dbsls.m_customer b using(customerid)where  tgl_transfer>=concat(substr(curdate(),1,8),'01') and tgl_transfer<=curdate()
                                            group by group_id)d using(group_id)

                                            left join
                                            (select group_id, group_descr,

                                            SUM(IF(days_past_due > 8, amount_due, 0))jt
                                            FROM(
                                                    SELECT group_id, group_descr, datediff(curdate(),tgl_tempo) days_past_due, sum(saldo) amount_due 
                                                    FROM(
                                                            SELECT no_sales ,a.customerid ,tgl_tempo ,( IF ( a.retur = 1, a.dokument *- 1, a.dokument )) - IF (isnull(kredit), 0, kredit) saldo
                                                            FROM dbsls.t_ar_ink_master a 
                                                            LEFT JOIN 
                                                                    (SELECT no_sales,customerid, sum( bayar_transfer + bayar_giro + bayar_tunai ) kredit 
                                                                    FROM dbsls.t_ar_ink_detail where tgl_transfer <=curdate()
                                                                    GROUP BY no_sales ) b USING (no_sales) where a.tanggal<=curdate()
                                                                    GROUP BY no_sales)a1 
                                                                    inner join dbsls.m_customer b1 using (customerid) 
                                                                    WHERE saldo <>0
                                                                    GROUP BY group_id,days_past_due
                                                                    )a group by group_id 
                                            )e using (group_id)
                            )a
                            order by a.group_descr) b using(kode_lang)
                            left join 
                            (select kode_lang,cl from mpm.user)c using(kode_lang)
                            ";
                    }break;
                    default:
                        $sql="
                            select * from   
                                (
                                select  a.*,
                                        b.value from
                                        (   
                                            select  a.open,
                                                    a.id,
                                                    a.supp,
                                                    a.company,
                                                    b.id as userid,
                                                    b.kode_lang,
                                                    a.email,
                                                    tipe,
                                                    date_format(tglpesan,'%d %M %Y, %T') as tglpesan,
                                                    nopo, date_format(tglpo,'%d %M %Y, %T') as tglpo,
                                                    nodo, date_format(tgldo,'%d %M %Y, %T') as tgldo,
                                                    b.cl          
                                            from    mpm.po a inner join mpm.user b 
                                                        on a.userid=b.id 
                                            where   a.deleted=0
                                            order by id desc limit 150
                                        )a
                                                        
                                        left join 
                                        (
                                            select  id_ref,
                                                    sum(harga*banyak) as value 
                                            from    mpm.po_detail 
                                            where deleted=0 
                                            group by id_ref
                                        )b 
                                            on a.id=b.id_ref
                                                            
                                order by id desc
                                ) a
                                                            
                                left join
                                (
                                    select * from
                                    (       
                                            select  substr(customerid,2,5) kode_lang, 
                                                    sum(dokument-bayar) saldoakhir 
                                            from    dbsls.t_ar_ink_master 
                                            where   dokument-bayar>0 
                                            group by customerid
                                    ) a
                                    
                                    inner join 
                                    (
                                            select  substr(customerid,2,5) kode_lang,
                                                    sum(dokument-bayar) jt 
                                            from    dbsls.t_ar_ink_master 
                                            where   dokument-bayar>0 
                                                    and datediff(tgl_tempo,curdate())<8 
                                            group by customerid
                                    )b
                                    using (kode_lang)
                                )b 
                                using(kode_lang)

                            ";
                        $sql2="select * from (select a.*,b.value from(select a.open,a.id,a.supp,a.company,b.id as userid,b.kode_lang,a.email,tipe,
                            date_format(tglpesan,'%d %M %Y, %T') as tglpesan,
                            nopo, date_format(tglpo,'%d %M %Y, %T') as tglpo,
                            nodo, date_format(tgldo,'%d %M %Y, %T') as tgldo
                            from mpm.po a inner join mpm.user b on a.userid=b.id where a.deleted=0 order by id desc limit 100)a
                            left join (select id_ref,sum(harga*banyak) as value from po_detail where deleted=0 group by id_ref)b on a.id=b.id_ref
                            order by id desc) a
                            left join
                            (
                            select right(group_id,5) kode_lang, (saldoawal+debit-kredit) saldoakhir, jt
                            from(
                                            select a.group_id,a.group_descr,debitprev-if(isnull(kreditprev),0,kreditprev) saldoawal,if(isnull(debit),0,debit) debit,if(isnull(kredit),0,kredit) kredit,jt

                                            from(

                                            select group_id, group_descr, sum(if(retur=1,dokument*-1,dokument)) debitprev from dbsls.t_ar_ink_master a 
                                            inner join dbsls.m_customer b using(customerid)where  tanggal<concat(substr(curdate(),1,8),'01')
                                            group by group_id)a 

                                            left join 

                                            (select group_id, group_descr, sum(bayar_transfer+bayar_giro+bayar_tunai) kreditprev from dbsls.t_ar_ink_detail a 
                                            inner join dbsls.m_customer b using(customerid)where  tgl_transfer<concat(substr(curdate(),1,8),'01')
                                            group by group_id)b using(group_id)

                                            left join 

                                            (select group_id, group_descr, sum(if(retur=1,dokument*-1,dokument)) debit from dbsls.t_ar_ink_master a 
                                            inner join dbsls.m_customer b using(customerid)where  tanggal>=concat(substr(curdate(),1,8),'01') and tanggal<=curdate()
                                            group by group_id)c using (group_id)

                                            left join 

                                            (select group_id, group_descr, if(isnull(sum(bayar_transfer+bayar_giro+bayar_tunai)),0,sum(bayar_transfer+bayar_giro+bayar_tunai)) kredit from dbsls.t_ar_ink_detail a 
                                            inner join dbsls.m_customer b using(customerid)where  tgl_transfer>=concat(substr(curdate(),1,8),'01') and tgl_transfer<=curdate()
                                            group by group_id)d using(group_id)

                                            left join
                                            (select group_id, group_descr,

                                            SUM(IF(days_past_due > 8, amount_due, 0))jt
                                            FROM(
                                                    SELECT group_id, group_descr, datediff(curdate(),tgl_tempo) days_past_due, sum(saldo) amount_due 
                                                    FROM(
                                                            SELECT no_sales ,a.customerid ,tgl_tempo ,( IF ( a.retur = 1, a.dokument *- 1, a.dokument )) - IF (isnull(kredit), 0, kredit) saldo
                                                            FROM dbsls.t_ar_ink_master a 
                                                            LEFT JOIN 
                                                                    (SELECT no_sales,customerid, sum( bayar_transfer + bayar_giro + bayar_tunai ) kredit 
                                                                    FROM dbsls.t_ar_ink_detail where tgl_transfer <=curdate()
                                                                    GROUP BY no_sales ) b USING (no_sales) where a.tanggal<=curdate()
                                                                    GROUP BY no_sales)a1 
                                                                    inner join dbsls.m_customer b1 using (customerid) 
                                                                    WHERE saldo <>0
                                                                    GROUP BY group_id,days_past_due
                                                                    )a group by group_id 
                                            )e using (group_id)
                            )a
                            order by a.group_descr) b using(kode_lang)
                            left join 
                            (select kode_lang,cl from mpm.user)c using(kode_lang)
                            ";
                        //$sql='select 1 from mpm.tabcomp where nocab="A"';break;
                }
                $query=$this->db->query($sql);
                $this->total_query = $query->num_rows();
                $sql2= $sql.' limit ? offset ?';
                $query = $this->db->query($sql2,array(10,$init));
                //echo $key;
                if($query->num_rows()>0)
                {
                    $image_properties=array(
                         'src'    => 'assets/css/images/detail.gif',
                    );
                    $this->load->library('table');
                    $this->table->set_empty('0');

                    $this->table->set_empty('0');
                    $this->table->set_template($this->tmpl);
                    $this->table->set_caption('ORDER LIST');
                    $this->table->set_heading('DELETE','NO ORDER','COMPANY','ORDER DATE', 'NO PO','PO DATE','SUPPLIER','TIPE','TOTAL VALUE','DETAIL','PRINT','EMAIL');

                    foreach ($query->result() as $value)
                    {

                        if($value->tipe=='N'){
                            $detail=anchor('trans/po/show_detail/'.$value->id.'/'.$value->supp,img($this->image_properties_detail));
                        }
                        else{
                            if($value->jt<=0){
                                if(($value->value+$value->saldoakhir)<=$value->cl){
                                    $detail=anchor('trans/po/show_detail/'.$value->id.'/'.$value->supp,img($this->image_properties_detail));
                                }
                                else{
                                    if($value->open==1){
                                        $detail=anchor('trans/po/show_detail/'.$value->id.'/'.$value->supp,img($this->image_properties_detail));
                                    }
                                    else{
                                        $detail=img($this->image_properties_locked);
                                    }
                                }
                            }
                            else{
                                if($value->open==1){
                                        $detail=anchor('trans/po/show_detail/'.$value->id.'/'.$value->supp,img($this->image_properties_detail));
                                    }
                                    else{
                                        $detail=img($this->image_properties_locked);
                                    }
                            }
                        }
                        //$detail=anchor('trans/po/show_detail/'.$value->id.'/'.$value->supp,img($this->image_properties_detail));
             
                        $js=array('onclick'=>"return confirm('Are you sure you want to delete?')");
                        $tglpo=$value->tglpo;
                        $image_print_po = ($value->tglpo!='') ?
                                '<div div style="text-align:center">'.anchor('trans/po/print/'.$value->id,img($this->image_properties_print)).'</div>' :
                                '<div div style="text-align:center">'.img($this->image_properties_print_off).'</div>';
                        $image_email_po = ($value->tglpo!='' and $value->email!='') ?
                                '<div div style="text-align:center">'.anchor('trans/po/email/'.$value->id.'/'.$value->userid.$value->supp,img($this->image_properties_email)).'</div>' :
                                '<div div style="text-align:center">'.img($this->image_properties_email_off).'</div>';
                        $image_email_warn = ($value->jt>0 and $value->email!='') ?
                                '<div div style="text-align:center">'.anchor('trans/po/email_warn/'.$value->id.'/'.$value->userid.$value->supp,img($this->image_properties_email)).'</div>' :
                                '<div div style="text-align:center">'.img($this->image_properties_email_off).'</div>';
                        $this->table->add_row(
                                '<div div style="text-align:center">'.anchor('trans/po/delete/'.$value->id,img($this->image_properties_del),$js).'</div>'
                                ,'ODR'.$value->id//'ODR'.str_pad($value->id,6, '0', STR_PAD_LEFT)
                                ,$value->company
                                //,$value->email
                                ,$value->tglpesan
                                ,$value->nopo
                                ,$value->tglpo
                                ,$this->getSuppname($value->supp)
                                ,$value->tipe
                                ,'<div style="text-align:right">'.number_format($value->value,2).'</div>'
                                //,'<div style="text-align:center">'.anchor('trans/po/show_detail/'.$value->id.'/'.$value->supp,img($image_properties)).'</div>'
                                ,$detail
                                ,'<div style="text-align:center">'.anchor_popup('trans/po/print/'.$value->id,img($this->image_properties_print)).'</div>'//$image_print_po//'<div div style="text-align:center">'.anchor('trans/print_po_mpm/'.$value->nopesan,img($image_print)).'</div>'
                                ,$image_email_po
                                //,'<div style="text-align:right">'.number_format($value->cl,2).'</div>'
                                //,'<div div style="text-align:center">'.anchor('trans/po_delete/'.$value->nopesan,img($image_properties_del),$js).'</div>'
                                //,$image_email_warn
                                );
                    }

                    $this->output_table .= $this->table->generate();
                    $this->output_table .= '<br />';
                    $this->table->clear();
                    $query->free_result();
                    $this->output_table;
                    return $this->output_table;
                }
                else
                {
                    return FALSE;
                }
            }break;
            case 'download':
            {
                delete_files('assets/po/temp/');
                //echo 'sdada';
                $date=$this->input->post('download');
                $sofile=str_replace('\\','/',realpath('.'))."/assets/po/temp/so".substr($date,2,2).substr($date,5,2).substr($date,8,2).'.txt';
                $spfile=str_replace('\\','/',realpath('.'))."/assets/po/temp/sp".substr($date,2,2).substr($date,5,2).substr($date,8,2).'.txt';
                //$sofile=site_url('assets/po/temp').'/so'.substr($date,2,2).substr($date,5,2).substr($date,8,2).'txt';
                //$spfile=site_url('assets/po/temp').'/sp'.substr($date,2,2).substr($date,5,2).substr($date,8,2).'txt';
                $pofile='po'.substr($date,2,2).substr($date,5,2).substr($date,8,2);
                $so="select 'PO'
                ,''
                ,left(nopo,6)
                ,''
                ,date_format(tglpo,'%d-%m-%Y')
                ,''
                ,date_format(tglpo+interval tempo day,'%d-%m-%Y')
                ,'02'
                ,tempo
                ,a.supp
                ,''
                ,''
                ,''
                ,''
                ,''
                ,npwp
                ,address
                ,''
                ,kode_lang
                ,company
                ,''
                ,'1'
                ,''
                ,''
                ,''
                ,''
                ,''
                ,''
                ,''
                ,''
                ,''
                ,''
                ,''
                ,concat(substr(nopo,3),'/DEL')
                ,tipe
                ,''
                ,''
                ,''
                ,''
                ''
                from mpm.po a inner join mpm.`user`b on a.userid=b.id where date_format(tglpo,'%Y-%m-%d')='".$date."'
                and a.supp='001' and deleted=0 into outfile '".$sofile."' FIELDS TERMINATED BY ',' ENCLOSED BY '~' LINES TERMINATED BY '\\n' ";
                $this->db->query($so);

                $sp="select 'PO'
                ,''
                ,left(nopo,6)
                ,''
                ,date_format(tglpo,'%d-%m-%Y')
                ,''
                ,kodeprod
                ,kode_prc
                ,namaprod
                ,''
                ,banyak
                ,''
                ,''
                ,''
                ,''
                ,''
                ,''
                ,kode_lang
                ,company
                ,''
                ,'1'
                ,''
                ,kode_lang
                ,company
                ,''
                ,''
                ,''
                ,''
                ,''
                ,''
                ,''
                ,concat(substr(nopo,3),'/DEL')
                ,''
                ,''
                ,''
                ,tipe
                ,''
                ,''
                from mpm.po a inner join mpm.`user`b inner join mpm.po_detail c on a.userid=b.id and a.id=c.id_ref
                where date_format(tglpo,'%Y-%m-%d')='".$date."' and a.supp='001' and a.deleted=0
                into outfile '".$spfile."' FIELDS TERMINATED BY ','ENCLOSED BY '~' LINES TERMINATED BY '\\n'";
                $this->db->query($sp);
                $this->zip->read_file($spfile);
                $this->zip->read_file($sofile);
                $this->zip->download($pofile);
            }break;
            case 'delete':
            {
                //$sql='delete from mpm.po where id=?';
                $id=$this->session->userdata('id');
                $sql='update mpm.po set deleted=1, modified_by='.$id.' ,modified="'.date('Y-m-d H:i:s').'" where id=?';
                $this->db->query($sql,array($key));
                $sql='update mpm.po_detail set deleted=1 where id_ref=?';
                $this->db->query($sql,array($key));
                redirect('trans/po/show');
            }break;
            case 'delete_temp':
            {
                $id=$this->session->userdata('id');
                $sql='delete from mpm.po_temp where userid=?';
                $query=$this->db->query($sql,array($id));
            }break;
            case 'delete_detail':
            {
                $id=$this->session->userdata('id');
                $query=$this->db->query('select id_ref from mpm.po_detail where id=? and deleted=0',array($key));
                $row=$query->row();
                //$sql='delete from mpm.po_detail where id=?';
                $sql='update mpm.po_detail set deleted=1 where id=?';
                $this->db->query($sql,array($key));
                $query=$this->db->query('select 1 from mpm.po_detail where id_ref=? and deleted=0',array($row->id_ref));
                if($query->num_rows()>0)
                {
                    redirect('trans/po/show_detail/'.$row->id_ref.'/'.$this->session->userdata('supplier'));
                }
                else
                {
                    $sql='update mpm.po set deleted=1,modified_by='.$id.' ,modified="'.date('Y-m-d H:i:s').'" where id=?';
                    $this->db->query($sql,array($row->id_ref));
                    redirect('trans/po/show/');
                }
            }break;
            case 'save':
            {
                $id=$this->session->userdata('id');
                $post['modified_by']=$id;
                $post['modified']=date('Y-m-d H:i:s');;
                $post['tglpo']= date('Y-m-d H:i:s');
                $post['nopo']=$this->input->post('nopo').'/MPM/'.$this->getRome($this->input->post('mpo')).'/'.$this->input->post('ypo');
                $post['note']=$this->input->post('note');
                $where='id='.$key;
                $this->db->trans_begin();
                $sql=$this->db->update_string('po',$post,$where);
                $this->db->query($sql);
                if($this->db->trans_status()===FALSE)
                {
                    $this->db->trans_rollback();
                }
                else
                {
                    $this->db->trans_commit();
                }
                //redirect('trans/po/show/'.$post['kategori']);
            }break;
            case 'print':
            {
                $server='192.168.1.2';
                $user='root';
                $pass='mpm123';
                $db='mpm';
                $this->load->library('PHPJasperXML');
                echo$xml = simplexml_load_file("assets/report/report_po.jrxml");
                @$this->phpjasperxml->debugsql=false;
                @$this->phpjasperxml->arrayParameter=array('nopesan'=>$key);

                @$this->phpjasperxml->xml_dismantle($xml);

                @$this->phpjasperxml->transferDBtoArray($server,$user,$pass,$db);
                @$this->phpjasperxml->outpage("I");
            }break;

            case 'email':
            {

                $server='192.168.1.2';
                $user='root';
                $pass='mpm123';
                $db='mpm';
                $filename=str_replace('/','_',$this->getPO($key)->nopo);
                $this->load->library('PHPJasperXML');
                echo$xml = simplexml_load_file("assets/report/report_po.jrxml");
                @$this->phpjasperxml->debugsql=false;
                @$this->phpjasperxml->arrayParameter=array('nopesan'=>$key);

                @$this->phpjasperxml->xml_dismantle($xml);

                @$this->phpjasperxml->transferDBtoArray($server,$user,$pass,$db);
                @$this->phpjasperxml->outpage("F",'assets/po/'.$filename.'.pdf');
                

                $this->email_config();

               
                if($this->getTipe2($key)=='B')
                {
                    //$this->email->from('muliaputramandiri@gmail.com', '('.$this->getTipe($key).') '.'Automatic Email Sender');
                    $this->email->from('muliaputramandiri@gmail.com', 'PO Kebutuhan Stok '.date('F Y'));
                }
                else
                {
                    $this->email->from('muliaputramandiri@gmail.com', '('.$this->getTipe($key).') '.'Automatic Email Sender');
                }
                
                $cekSupp = substr($id,-3);
                if($cekSupp == '002')
                {
                    //$this->email->to("suffy.yanuar@gmail.com");
                    $this->email->to($this->getEmailSupp(substr($id,-3)));
                    $list = array($this->getEmail(substr($id,0,-3)), "rikisugiarto@gmail.com");
                    $this->email->cc($list);//'henryjoseph760@gmail.com','herman_oscar@yahoo.com');
                
                    $this->email->subject('PO NO '.$filename);
                    $this->email->message('This email is sent by system');
                    $this->email->attach('assets/po/'.$filename.'.pdf');


                    $this->email->send();
                    unlink('./assets/po/'.$filename.'.pdf');
                    //echo $this->email->print_debugger();
                    //redirect('trans/po/show');
                }
                
                else
                {
                    $this->email->to($this->getEmailSupp(substr($id,-3)));//$this->email->to('one@example.com, two@example.com, three@example.com');
                    //$this->email->to('muliaputramandiri@gmail.com');
                    $list = array($this->getEmail(substr($id,0,-3)));
                    $this->email->cc($list);//'henryjoseph760@gmail.com','herman_oscar@yahoo.com');
                    //$this->email->bcc('them@their-example.com');

                    $this->email->subject('PO NO '.$filename);
                    $this->email->message('This email is sent by system');
                    $this->email->attach('assets/po/'.$filename.'.pdf');


                    $this->email->send();
                    unlink('./assets/po/'.$filename.'.pdf');
                    //echo $this->email->print_debugger();
                    //redirect('trans/po/show');
                }

                
            }break;
            case 'email_warn':
            {
                $filename=$this->getPO($key)->nopo;
                $this->email_config();

                $this->email->from('muliaputramandiri@gmail.com', "PT. MULIA PUTRA MANDIRI");

                $this->email->to($this->getEmail(substr($id,0,-3)));//$this->email->to('one@example.com, two@example.com, three@example.com');
                //$this->email->to('muliaputramandiri@gmail.com');
                //$list = array('henryjoseph760@gmail.com','herman_oscar@yahoo.com');
                $this->email->cc($list);//'henryjoseph760@gmail.com','herman_oscar@yahoo.com');
                //$this->email->bcc('them@their-example.com');

                $this->email->subject('Automatic Reminder');
                $this->email->message('Yth. Pelanggan, Pesanan ( '.$this->getTipe($key).' ) belum dapat diproses sehubungan dengan hutang yang telah jatuh tempo');
                //$this->email->attach('assets/po/'.$filename.'.pdf');


                $this->email->send();
                //echo $this->email->print_debugger();
                //redirect('trans/po/show');
            }break;
            case 'manual_delete':
            {
                $sql='delete from mpm.po_temp where id=? and userid=?';
                $this->db->trans_begin();

                $this->db->query($sql,array($key,$this->session->userdata('id')));
                $this->db->trans_complete();
                if($this->db->trans_status()===FALSE)
                {
                    $this->db->trans_rollback();
                }
                else
                {
                    $this->db->trans_commit();
                }
                redirect('trans/po/manual');
            }break;
            case 'manual_save':
            {
                //$id=$this->session->userdata('id');
                $supp=$this->session->userdata('supplier');
                switch($this->input->post('alamat'))
                {
                    case 'new':
                        $alamat=$this->input->post('alamatlain');break;
                    case 'saved':
                        $alamat=$this->input->post('alamatsaved');break;
                }
                $row=$this->getCustInfo2($this->session->userdata('client'));
                $userid=$this->session->userdata('id');
                $post['created_by'] =$userid;
                $post['created']=date('Y-m-d H:i:s');
                $post['tglpesan']=date('Y-m-d H:i:s');
                $post['userid']= $this->session->userdata('client');
                $post['supp']=$this->session->userdata('supplier');
                $post['tipe']=$this->input->post('tipe');
                $post['alamat']=$alamat;
                $post['company']=$row->company;
                $post['email']=$row->email;
                $post['npwp']=$row->npwp;
                $tglpesan=date('Y-m-d H:i:s');

                $this->db->trans_begin();
                $this->db->insert('mpm.po',$post);
                $query=$this->db->query("select id from mpm.po where created='".$post['created']."'");
                $row=$query->row();
                //$sql='insert into mpm.po_detail(id_ref,supp,kodeprod,namaprod,banyak,harga,kode_prc,userid) select '.$row->id.',supp,kodeprod,namaprod,sum(banyak),sum(harga),kode_prc,userid from mpm.po_temp group by kodeprod';
                $sql='insert into mpm.po_detail(id_ref,supp,kodeprod,namaprod,banyak,harga,kode_prc,userid) select '.$row->id.',supp,kodeprod,namaprod,sum(banyak),sum(harga),kode_prc,userid from mpm.po_temp where userid='.$userid.' and supp='.$supp.' group by kodeprod';
                $this->db->query($sql);
                $this->db->query('delete from mpm.po_temp where userid=?',array($userid));
                $this->db->trans_complete();
                if($this->db->trans_status()===FALSE)
                {
                    $this->db->trans_rollback();
                }
                else
                {
                    $this->db->trans_commit();
                }
                redirect('trans/po/show');
            }break;
            case 'manual_addtemp':
            {
                $id=$this->session->userdata('id');
                //$this->db->query('delete from po_temp where userid='.$id);
                $kodeprod=$this->input->post('product');
                $query=$this->getProduct($kodeprod);
                $row=$query->row();
                $post['userid']=$id;
                $post['kodeprod']=$kodeprod;
                $post['kode_prc']=$row->kode_prc;
                $post['namaprod']=$row->namaprod;
                $post['banyak']  =$this->input->post('amount')*$row->isisatuan;
                $post['harga']   =$this->getHarga($kodeprod);
                $post['supp']    =$row->supp;
                //$post['tgldokjdi']=date('Y-m-d H:i:s');
                $this->db->insert('mpm.po_temp',$post);
                //$temp= $this->out_sales($id);
                //return $temp;
                redirect('trans/po/manual');
            }break;
          
            case 'add':
            {
                $id=$this->session->userdata('id');
                //$this->db->query('delete from po_temp where userid='.$id);
                $kodeprod=$this->input->post('product');
                $query=$this->getProduct($kodeprod);
                $row=$query->row();
                $post['userid']=$id;
                $post['id_ref']= (int)$key;

                $sql2="select userid from mpm.po where id=".$post['id_ref'];
                $query2=$this->db->query($sql2);
                $row2=$query2->row();

                $post['kodeprod']=$kodeprod;
                $post['kode_prc']=$row->kode_prc;
                $post['namaprod']=$row->namaprod;
                $post['banyak']  =$this->input->post('amount')*$row->isisatuan;
                $post['harga']   =$this->getHarga($kodeprod,$row2->userid);
                $post['supp']    =$row->supp;

                //$post['tgldokjdi']=date('Y-m-d H:i:s');
                $this->db->insert('mpm.po_detail',$post);
                //$temp= $this->out_sales($id);
                //return $temp;
                $sql='update mpm.po set modified="'.date('Y-m-d H:i:s').'" and modified_by='.$id.' where id='.$post['id_ref'];
                $this->db->query($sql);
                redirect('trans/po/show_detail/'.$key.'/'.$post['supp']);
            }break;
            case 'show_temp':
            {
                $id=$this->session->userdata('id');

                $sql1='select * from mpm.po_temp where userid=? order by kodeprod';
                $query = $this->db->query($sql1,array($id));
                $this->total_query = $query->num_rows();
                //$sql2= $sql1.' limit ? offset ?';
                //$query = $this->db->query($sql2,array($limit,$offset));

                if($query->num_rows() > 0)
                {
                    $this->load->library('table');
                    $this->table->set_empty('0');

                    $this->table->set_empty('0');
                    $this->table->set_template($this->tmpl);
                    $this->table->set_caption('PURCHASE ORDER');
                    $this->table->set_heading('CODE','PRODUCT', 'PRC CODE','AMOUNT','DELETE');
                    foreach ($query->result() as $value)
                    {
                        $js=array('onclick'=>"return confirm('Are you sure you want to delete?')");
                        $this->table->add_row(
                                $value->kodeprod
                                ,$value->namaprod
                                ,$value->kode_prc
                                ,'<div div style="text-align:right">'.number_format($value->banyak).'</div>'
                                ,'<div div style="text-align:center">'.anchor('trans/po/manual_delete/'.$value->id,img($this->image_properties_del),$js).'</div>'
                        );
                    }

                    $this->output_table .= $this->table->generate();
                    $this->output_table .= '<br />';
                    $this->table->clear();
                    $query->free_result();
                    $this->output_table;
                    return $this->output_table;
                }
                else
                {
                    return FALSE;
                }
            }break;
            
            case 'show_detail':
            {
                $sql="select * from mpm.po a inner join mpm.po_detail b on a.id=b.id_ref where a.id=? and b.deleted=0 and a.deleted=0 order by kodeprod";
                $query=$this->db->query($sql,array($key));
                if($query->num_rows()>0)
                {
                    $image_properties_back=array(
                         'src' => 'assets/css/images/back.png',
                    );

                    $this->load->library('table');
                    $this->table->set_empty('0');

                    $this->table->set_empty('0');
                    $this->table->set_template($this->tmpl);
                    $this->table->set_caption('PURCHASE ORDER DETAIL');
                    $this->table->set_heading('CODE','PRODUCT', 'PRC CODE','AMOUNT','DELETE');
                    foreach ($query->result() as $value)
                    {
                        $js=array('onclick'=>"return confirm('Are you sure you want to delete?')");
                        $this->table->add_row(
                                $value->kodeprod
                                ,$value->namaprod
                                ,$value->kode_prc
                                ,'<div div style="text-align:right">'.number_format($value->banyak).'</div>'
                                ,'<div div style="text-align:center">'.anchor('trans/po/delete_detail/'.$value->id,img($this->image_properties_del),$js).'</div>'
                                //,'<div div style="text-align:center">'.anchor('trans/check_order_all/',img($image_properties_back)).'</div>'
                        );
                    }

                    $this->output_table .= $this->table->generate();
                    $this->output_table .= '<br />';
                    $this->table->clear();
                    $query->free_result();
                    $this->output_table;
                    return $this->output_table;
                }
                else
                {
                    return FALSE;
                }
            }break;
        }
    }
    public function getPO($id=0)
    {
        $sql='select nopo,note from mpm.po where id=?';
        $query=$this->db->query($sql,array($id));
        return $query->row();
    }
    public function getJob($kode)
    {
        $sql='select job ,job.desc from mpm.job where job like "'.$kode.'%"';
        $query=$this->db->query($sql);
        return $query;
    }
    private function getEmail($id=0)
    {
        $sql='select email from mpm.user where id=?';
        $query=$this->db->query($sql,array($id));
        $row=$query->row();
        return $row->email;
    }
    private function getEmailFinance($id=0)
    {
        $sql='select email_finance2 from mpm.user where kode_lang=?';
        $query=$this->db->query($sql,array($id));
        $row=$query->row();
        return $row->email_finance2;
        echo $row->email_finance2;
        echo $sql;
    }
    private function getEmailFinanceFaktur($id=0)
    {
        $sql='select email_finance from mpm.user where kode_lang=?';
        $query=$this->db->query($sql,array($id));
        $row=$query->row();
        return $row->email_finance;
    }
    private function getEmail2($id=0)
    {
        $sql='select email from mpm.user where kode_lang=?';
        $query=$this->db->query($sql,array($id));
        $row=$query->row();
        return $row->email;
    }
    private function getTipe($id=0)
    {
        $sql='select tipe from mpm.po where id=?';
        $query=$this->db->query($sql,array($id));
        $row=$query->row();
        $tipe=$row->tipe;
        switch($tipe)
        {
            case 'S':$return = 'SPK';break;
            case 'A':$return = 'ALOKASI';break;
            case 'R':$return = 'REPLENISHMENT';break;
        }
        return $return;
    }
    private function getEmailSupp($supp)
    {
        $sql='select email from mpm.tabsupp where supp=?';
        $query=$this->db->query($sql,array($supp));
        $row=$query->row();
        return $row->email;
    }
    private function getSuppname($supp)
    {
        $sql='select namasupp from tabsupp where supp=?';
        $query=$this->db->query($sql,array($supp));
        $row=$query->row();
        return $row->namasupp;
    }
    public function getDO($id=0,$userid=0)
    {
        $sql='select date_format(tglpo,"%d %M %Y, %T") as tglpo,date_format(tgldo,"%d %M %Y, %T") as tgldo,nodo,nopo from mpm.sales_po where nopesan=? and userid=?';
        $query=$this->db->query($sql,array($id,$userid));
        return $query->row();
    }
    public function list_client2($userid=0)
    {
        $sql="select distinct b.naper as nocab,a.id,kode_comp,nama_comp,company from mpm.user a inner join mpm.tabcomp b on b.kode_comp=a.username order by nama_comp";
        return $this->db->query($sql,array($userid));
        //return $query->row();
    }
    public function list_client($userid=0)
    {
        $sql="select id,kode_dp,company from mpm.user where level in (4,5,6,7) order by company";
        return $this->db->query($sql,array($userid));
        //return $query->row();
    }
    public function list_pelanggan()
    {
        $sql="select distinct * from(
              select distinct concat('1',grup_lang) grup_lang,grup_nama from pusat.user where grup_lang<>'' and grup_lang<>'00159'   
              union all   
              select distinct group_id grup_lang,group_descr grup_nama from dbsls.m_customer where group_id<>'' and group_id<>'100159' )a order by grup_nama";
        return $this->db->query($sql);
    }
    public function list_faktur($kode=0)
    {
        $sql="select * from (
                select tgldokjdi, concat(nodokjdi,substr(tgldokjdi,6,2) ,substr(tgldokjdi,3,2))as nodokjdi,concat(cast(nodokjdi AS unsigned),'/MPM/',substr(tgldokjdi,6,2),substr(tgldokjdi,3,2)) as nomor from pusat.fh a inner join pusat.user b using(kode_lang) where concat('1',grup_lang) =".$kode." 
                union all 
                select date_format(tanggal,'%Y-%m-%d'),concat(right(no_seri_pajak,8),substr(tanggal,6,2),substr(tanggal,3,2))nodokjdi
                , concat(right(no_seri_pajak,8),'/MPM/',substr(tanggal,6,2),substr(tanggal,3,2)) nomor from dbsls.t_sales_master 
                inner join dbsls.m_customer using(customerid) where group_id = ".$kode." and retur=0 ) a order by nodokjdi desc"; 
        $query=$this->db->query($sql);
        if($query->num_rows()>0)
           return $query;
        else
            return false;
    }
    private function get_faktur($kode="")
    {
        $kodefikasi = $this->session->userdata('client');
        //echo $kode.br(2);
        if (strlen($kode)==12)
        {
            //echo '888';break;
            $nofaktur=substr($kode,0,8);
            $bulan=substr($kode,10,2).substr($kode,8,2);
        }
        else
        {
            $nofaktur=substr($kode,0,6);
            $bulan=substr($kode,8,2).substr($kode,6,2);
        }
        /*
        $sql="select concat(cast(nodokjdi AS unsigned),'/MPM/',substr(tgldokjdi,6,2),substr(tgldokjdi,3,2)) as nomor,nildok,concat(cast(noseri AS unsigned),'/',substr(tgldokjdi,6,2),substr(tgldokjdi,3,2)) as pajak from pusat.fh where nodokjdi=".$nofaktur." and bulan=".$bulan."
            union all
            select 
            concat(right(a.no_seri_pajak,8),'/MPM/',substr(a.tanggal,6,2),substr(a.tanggal,3,2)) nomor
            ,sum(total_harga+(total_harga/10))+a.dp_value as nildok
            ,concat(right(a.no_seri_pajak,8),'/',substr(a.tanggal,6,2),substr(a.tanggal,3,2)) pajak
            from dbsls.t_sales_master a inner join dbsls.t_sales_detail b using(no_sales)
            where right(a.no_seri_pajak,8)=".$nofaktur." and left(a.no_sales,3)='SLS' and concat(substr(a.tanggal,3,2),substr(a.tanggal,6,2))=".$bulan." and a.customerid = ".$kodefikasi."
                group by nomor";
        */
        //di backup tanggal 290220161051
        /*
        $sql="
                select  concat(right(a.no_seri_pajak,8),'/MPM/',substr(a.tanggal,6,2),substr(a.tanggal,3,2)) nomor,
                        (sum(biaya_jasa) - (SUM(rp_cabang) + nilai_invoice)) + ((sum(biaya_jasa) - (SUM(rp_cabang) + nilai_invoice))/10) + dp_value as nildok,
                        concat(right(a.no_seri_pajak,8),'/',substr(a.tanggal,6,2),substr(a.tanggal,3,2)) pajak
                from    dbsls.t_sales_master a inner join dbsls.t_sales_detail b 
                            on a.no_sales = b.no_sales
                where   right(a.no_seri_pajak,8)=".$nofaktur." and left(a.no_sales,3)='SLS' and 
                        concat(substr(a.tanggal,3,2),substr(a.tanggal,6,2))=".$bulan." and a.customerid = ".$kodefikasi."
                group by nomor        
        ";
        */
        $sql="
                select  concat(right(a.no_seri_pajak,8),'/MPM/',substr(a.tanggal,6,2),substr(a.tanggal,3,2)) nomor,
                        sum(total_harga+(total_harga/10))+a.dp_value as nildok,
                        concat(right(a.no_seri_pajak,8),'/',substr(a.tanggal,6,2),substr(a.tanggal,3,2)) pajak
                FROM
                (
                        select no_sales, tgl_periode, dp_value, no_seri_pajak, tanggal
                        FROM    dbsls.t_sales_master a
                        where   right(a.no_seri_pajak,8)=".$nofaktur." and left(a.no_sales,3)='SLS' and 
                                concat(substr(a.tanggal,3,2),substr(a.tanggal,6,2))=".$bulan." and a.customerid = ".$kodefikasi."
                        ORDER BY tgl_periode DESC
                        limit 1
                )a INNER JOIN dbsls.t_sales_detail b using(no_sales)
                group by nomor        
        ";      
       
        $query=$this->db->query($sql);
        return $query->row();
    }


}
?>
